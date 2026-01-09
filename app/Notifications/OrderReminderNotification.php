<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\OrderReminder;

class OrderReminderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $reminder;

    /**
     * Create a new notification instance.
     */
    public function __construct(OrderReminder $reminder)
    {
        $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $order = $this->reminder->order;

        return (new MailMessage)
            ->subject('Recordatorio de orden - SISNOTICE')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Este es un recordatorio sobre tu orden **' . $order->order_number . '**')
            ->line('Negocio: **' . $order->business->business_name . '**')
            ->line('Descripción: ' . $order->description)
            ->line('Estado actual: **' . $this->getStatusText($order->status) . '**')
            ->action('Ver Mi Orden', url('/orders/' . $order->id))
            ->line('¡Gracias por tu preferencia!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        $order = $this->reminder->order;

        return [
            'type' => 'order_reminder',
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'business_name' => $order->business->business_name,
            'order_status' => $order->status,
            'message' => "Recordatorio: Orden {$order->order_number} - {$this->getStatusText($order->status)}",
        ];
    }

    /**
     * Obtiene el texto del estado
     */
    private function getStatusText(string $status): string
    {
        return match($status) {
            'pending' => 'Pendiente',
            'paid' => 'Pagada',
            'ready' => 'Lista para recoger',
            'delivered' => 'Entregada',
            'cancelled' => 'Cancelada',
            default => ucfirst($status),
        };
    }
}