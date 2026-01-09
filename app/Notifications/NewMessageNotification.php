<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Message;

class NewMessageNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'new_message',
            'message_id' => $this->message->id,
            'chat_id' => $this->message->chat_id,
            'sender_name' => $this->message->sender->name,
            'message_preview' => substr($this->message->message, 0, 50),
            'order_number' => $this->message->chat->order->order_number,
            'message' => "Nuevo mensaje de {$this->message->sender->name}",
        ];
    }
}