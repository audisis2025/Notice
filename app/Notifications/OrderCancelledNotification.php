<?php
/**
 * Nombre de la clase           : OrderCancelledNotification
 * Descripción de la clase      : Notificación que alerta al usuario sobre
 *                                la cancelación de su orden
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
namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Order;

class OrderCancelledNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order;

    /**
     * Create a new notification instance.
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
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
        return (new MailMessage)
            ->subject('Orden cancelada - SISNOTICE')
            ->greeting('Hola ' . $notifiable->name . ',')
            ->line('Lamentamos informarte que tu orden **' . $this->order->order_number . '** ha sido cancelada.')
            ->line('Negocio: **' . $this->order->business->business_name . '**')
            ->line('Motivo: ' . ($this->order->cancellation_reason ?? 'No especificado'))
            ->line('Para más información, por favor contacta directamente al negocio.')
            ->line('Disculpa las molestias ocasionadas.');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_cancelled',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'business_name' => $this->order->business->business_name,
            'cancellation_reason' => $this->order->cancellation_reason,
            'message' => "Tu orden {$this->order->order_number} ha sido cancelada",
        ];
    }
}