<?php

/**
 * Nombre de la clase           : ChatComponent
 * Descripción de la clase      : Componente Livewire que gestiona la interfaz
 *                                de chat en tiempo real
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica usando métodos del modelo
 *                                Corrección de error toJSON en Livewire 3
 *                                Cambio de propiedades tipadas a IDs
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */

namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Order;
use App\Models\Chat;
use App\Models\Message;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ChatComponent extends Component
{
    // ✅ CORRECCIÓN: Guardar solo IDs, no modelos completos
    public ?int $orderId = null;
    public ?int $chatId = null;
    public string $messageText = '';

    /**
     * Reglas de validación
     */
    protected $rules = [
        'messageText' => 'required|string|max:1000',
    ];

    /**
     * Mensajes de validación personalizados
     */
    protected $validationMessages = [
        'messageText.required' => 'El mensaje no puede estar vacío.',
        'messageText.max' => 'El mensaje no puede tener más de 1000 caracteres.',
    ];

    /**
     * Monta el componente
     */
    public function mount(Order $order)
    {
        // ✅ Guardar solo el ID
        $this->orderId = $order->id;

        // Verificar que el usuario tenga acceso a este chat
        $user = Auth::user();

        if ($user->isBusinessAdministrator()) {
            if ($order->business_id !== $user->business?->id) {
                abort(403, 'No tienes acceso a este chat.');
            }
        } elseif ($user->isMobileUser()) {
            if ($order->user_id !== $user->id) {
                abort(403, 'No tienes acceso a este chat.');
            }
        }

        // Verificar que el chat esté habilitado
        if (!$order->chat_enabled) {
            abort(403, 'El chat no está habilitado para esta orden.');
        }

        $this->loadChat();
    }

    /**
     * Carga o crea el chat usando métodos del modelo
     */
    public function loadChat()
    {
        $order = Order::find($this->orderId);

        if (!$order) {
            return;
        }

        // ✅ Obtener o crear chat usando método del modelo
        $chat = Chat::firstOrCreate(
            ['order_id' => $this->orderId],
            [
                'business_id' => $order->business_id,
                'user_id' => $order->user_id,
                'is_active' => true,
            ]
        );

        $this->chatId = $chat->id;

        // Marcar mensajes como leídos
        $this->markMessagesAsRead();
    }

    /**
     * Marca los mensajes como leídos
     */
    private function markMessagesAsRead()
    {
        if (!$this->chatId) {
            return;
        }

        Message::where('chat_id', $this->chatId)
            ->where('sender_id', '!=', Auth::id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
    }

    /**
     * Obtiene los mensajes del chat
     */
    public function getMessagesProperty()
    {
        if (!$this->chatId) {
            return collect();
        }

        return Message::where('chat_id', $this->chatId)
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
    }

    /**
     * Obtiene la orden actual
     */
    public function getOrderProperty()
    {
        return $this->orderId ? Order::find($this->orderId) : null;
    }

    /**
     * Obtiene el chat actual
     */
    public function getChatProperty()
    {
        return $this->chatId ? Chat::find($this->chatId) : null;
    }

    /**
     * Envía un mensaje
     */
    public function sendMessage()
    {
        $this->validate();

        if (!$this->chatId) {
            $this->dispatch('error', message: 'Chat no disponible.');
            return;
        }

        DB::beginTransaction();

        try {
            // ✅ Crear mensaje directamente usando el modelo
            Message::create([
                'chat_id' => $this->chatId,
                'sender_id' => Auth::id(),
                'message' => $this->messageText,
                'sent_at' => now(),
            ]);

            // Actualizar última actividad del chat
            Chat::where('id', $this->chatId)->update([
                'last_message_at' => now(),
            ]);

            DB::commit();

            // Limpiar el campo de texto
            $this->messageText = '';

            // Disparar evento para actualizar la UI
            $this->dispatch('message-sent');
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('error', message: 'Error al enviar mensaje: ' . $e->getMessage());
        }
    }

    /**
     * Renderiza el componente
     */
    public function render()
    {
        return view('livewire.chat.chat-component', [
            'messages' => $this->messages,
            'order' => $this->order,
            'chat' => $this->chat,
        ]);
    }
}
