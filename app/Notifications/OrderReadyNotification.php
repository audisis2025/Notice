<?php
/**
 * Nombre de la clase           : OrderReadyNotification
 * Descripción de la clase      : Notificación que alerta al usuario que su
 *                                orden está lista para recoger
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

class OrderReadyNotification extends Notification implements ShouldQueue
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
            ->subject('¡Tu orden está lista! - SISNOTICE')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu orden **' . $this->order->order_number . '** está lista para recoger.')
            ->line('Negocio: **' . $this->order->business->business_name . '**')
            ->line('Descripción: ' . $this->order->description)
            ->line('Para retirar tu orden, escanea el código QR que te proporcionaremos en el establecimiento.')
            ->action('Ver Mi Orden', url('/orders/' . $this->order->id))
            ->line('¡Gracias por tu preferencia!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'order_ready',
            'order_id' => $this->order->id,
            'order_number' => $this->order->order_number,
            'business_name' => $this->order->business->business_name,
            'message' => "Tu orden {$this->order->order_number} está lista para recoger",
        ];
    }
}