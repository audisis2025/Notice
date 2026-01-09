<?php
/**
 * Nombre de la clase           : ChatService
 * Descripción de la clase      : Servicio que encapsula la lógica de negocio
 *                                para la gestión de chats y mensajes
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
namespace App\Services;

use App\Models\Chat;
use App\Models\Message;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * ChatService
 * 
 * Servicio para gestionar chats entre usuarios y negocios.
 *
 * @package App\Services
 */
class ChatService
{
    /**
     * Crea o recupera un chat para una orden.
     *
     * @param Order $order Orden
     * @param User $user Usuario
     * @return Chat
     * @throws \Exception
     */
    public function getOrCreateChat(Order $order, User $user): Chat
    {
        // Verificar que el chat esté habilitado para la orden
        if (!$order->chat_enabled) {
            throw new \Exception('El chat no está habilitado para esta orden.');
        }
        
        // Verificar que el usuario sea el dueño de la orden
        if ($order->user_id !== $user->id) {
            throw new \Exception('No tienes permiso para acceder a este chat.');
        }
        
        $chat = Chat::firstOrCreate(
            [
                'order_id' => $order->id,
                'user_id' => $user->id,
            ],
            [
                'business_id' => $order->business_id,
                'is_active' => true,
            ]
        );
        
        return $chat;
    }

    /**
     * Envía un mensaje en el chat.
     *
     * @param Chat $chat Chat
     * @param User $sender Remitente
     * @param string $messageText Texto del mensaje
     * @return Message
     */
    public function sendMessage(Chat $chat, User $sender, string $messageText): Message
    {
        DB::beginTransaction();
        
        try {
            $message = Message::create([
                'chat_id' => $chat->id,
                'sender_id' => $sender->id,
                'message' => $messageText,
                'is_read' => false,
            ]);
            
            // Notificar al destinatario
            $recipient = $sender->id === $chat->user_id ? $chat->business->user : $chat->user;
            $recipient->notify(new \App\Notifications\NewMessageNotification($message));
            
            DB::commit();
            
            return $message;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Marca mensajes como leídos.
     *
     * @param Chat $chat Chat
     * @param User $reader Usuario que lee
     * @return void
     */
    public function markMessagesAsRead(Chat $chat, User $reader): void
    {
        Message::where('chat_id', $chat->id)
            ->where('sender_id', '!=', $reader->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Cierra un chat.
     *
     * @param Chat $chat Chat
     * @return Chat
     */
    public function closeChat(Chat $chat): Chat
    {
        $chat->update(['is_active' => false]);
        return $chat;
    }
}