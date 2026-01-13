<?php
/**
 * Nombre de la clase           : ChatComponent
 * Descripción de la clase      : Componente Livewire que gestiona la interfaz
 *                                de chat en tiempo real
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
namespace App\Livewire\Chat;

use Livewire\Component;
use App\Models\Order;
use App\Models\Chat;
use App\Models\Message;
use App\Services\ChatService;
use Illuminate\Support\Facades\Auth;

class ChatComponent extends Component
{
    public Order $order;
    public Chat $chat;
    public $messages;
    public $messageText = '';

    /**
     * Reglas de validación
     */
    protected $rules = [
        'messageText' => 'required|string|max:1000',
    ];

    /**
     * Mensajes de validación
     */
    protected $messages = [
        'messageText.required' => 'El mensaje no puede estar vacío.',
        'messageText.max' => 'El mensaje no puede tener más de 1000 caracteres.',
    ];

    /**
     * Monta el componente
     */
    public function mount(Order $order)
    {
        $this->order = $order;
        
        // Verificar que el usuario tenga acceso a este chat
        $user = Auth::user();
        
        if ($user->isBusinessAdministrator()) {
            if ($order->business_id !== $user->business->id) {
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
     * Carga o crea el chat
     */
    public function loadChat()
    {
        $chatService = app(ChatService::class);
        $this->chat = $chatService->getOrCreateChat($this->order->id);
        
        // Cargar mensajes
        $this->messages = $this->chat->messages()
            ->with('sender')
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get();
        
        // Marcar mensajes como leídos
        $chatService->markMessagesAsRead($this->chat->id, Auth::id());
    }

    /**
     * Envía un mensaje
     */
    public function sendMessage()
    {
        $this->validate();

        try {
            $chatService = app(ChatService::class);
            
            $message = $chatService->sendMessage(
                $this->chat->id,
                Auth::id(),
                $this->messageText
            );

            // Limpiar el campo de texto
            $this->messageText = '';
            
            // Recargar mensajes
            $this->loadChat();
            
            // Disparar evento para actualizar la UI
            $this->dispatch('message-sent');

        } catch (\Exception $e) {
            $this->dispatch('error', message: 'Error al enviar mensaje: ' . $e->getMessage());
        }
    }

    /**
     * Renderiza el componente
     */
    public function render()
    {
        return view('livewire.chat.chat-component');
    }
}