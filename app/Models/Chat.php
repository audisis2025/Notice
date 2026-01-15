<?php

/**
 * Nombre de la clase           : Chat
 * Descripción de la clase      : Modelo Eloquent que representa una conversación
 *                                con lógica de negocio integrada
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'order_id',
        'business_id',
        'user_id',
        'is_active',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'is_active' => 'boolean',
    ];

    /**
     * Relación: Un chat pertenece a una orden.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación: Un chat pertenece a un negocio.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Un chat pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un chat tiene muchos mensajes.
     */
    public function messages()
    {
        return $this->hasMany(Message::class);
    }

    /**
     * Obtiene el último mensaje del chat.
     */
    public function getLastMessageAttribute()
    {
        return $this->messages()->latest()->first();
    }

    /**
     * Cuenta mensajes no leídos para un usuario específico.
     */
    public function unreadMessagesCount(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    /**
     * Scope: Filtra chats activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en ChatService)
    // ====================================================================

    /**
     * Crea o recupera un chat para una orden.
     *
     * @param Order $order
     * @param User $user
     * @return Chat
     * @throws \Exception
     */
    public static function getOrCreateChat(Order $order, User $user): Chat
    {
        // Verificar que el chat esté habilitado para la orden
        if (!$order->chat_enabled) {
            throw new \Exception('El chat no está habilitado para esta orden.');
        }

        // Verificar que el usuario sea el dueño de la orden
        if ($order->user_id !== $user->id) {
            throw new \Exception('No tienes permiso para acceder a este chat.');
        }

        return self::firstOrCreate(
            [
                'order_id' => $order->id,
                'user_id' => $user->id,
            ],
            [
                'business_id' => $order->business_id,
                'is_active' => true,
            ]
        );
    }

    /**
     * Envía un mensaje en el chat.
     *
     * @param User $sender
     * @param string $messageText
     * @return Message
     */
    public function sendMessage(User $sender, string $messageText): Message
    {
        $message = Message::create([
            'chat_id' => $this->id,
            'sender_id' => $sender->id,
            'message' => $messageText,
            'is_read' => false,
        ]);

        // Notificar al destinatario
        $recipient = $sender->id === $this->user_id ? $this->business->user : $this->user;
        $recipient->notify(new \App\Notifications\NewMessageNotification($message));

        return $message;
    }

    /**
     * Marca mensajes como leídos.
     *
     * @param User $reader
     */
    public function markMessagesAsRead(User $reader): void
    {
        Message::where('chat_id', $this->id)
            ->where('sender_id', '!=', $reader->id)
            ->where('is_read', false)
            ->update([
                'is_read' => true,
                'read_at' => now(),
            ]);
    }

    /**
     * Cierra el chat.
     *
     * @return bool
     */
    public function closeChat(): bool
    {
        return $this->update(['is_active' => false]);
    }
}
