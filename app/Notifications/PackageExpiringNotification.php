<?php
/**
 * Nombre de la clase           : PackageExpiringNotification
 * Descripción de la clase      : Notificación que alerta al negocio sobre
 *                                la próxima expiración de su paquete
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
use App\Models\BusinessPackage;

class PackageExpiringNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $businessPackage;
    protected $daysRemaining;

    /**
     * Create a new notification instance.
     */
    public function __construct(BusinessPackage $businessPackage, int $daysRemaining)
    {
        $this->businessPackage = $businessPackage;
        $this->daysRemaining = $daysRemaining;
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
            ->subject('Tu paquete está por vencer - SISNOTICE')
            ->greeting('¡Hola ' . $notifiable->name . '!')
            ->line('Tu paquete **' . $this->businessPackage->package->name . '** está por vencer.')
            ->line('Días restantes: **' . $this->daysRemaining . '**')
            ->line('Fecha de vencimiento: **' . $this->businessPackage->end_date->format('d/m/Y') . '**')
            ->line('Para evitar la interrupción de tu servicio, te recomendamos renovar tu paquete.')
            ->action('Renovar Paquete', url('/packages/available'))
            ->line('¡Gracias por usar SISNOTICE!');
    }

    /**
     * Get the array representation of the notification.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'package_expiring',
            'package_id' => $this->businessPackage->package_id,
            'package_name' => $this->businessPackage->package->name,
            'days_remaining' => $this->daysRemaining,
            'end_date' => $this->businessPackage->end_date->format('Y-m-d'),
            'message' => "Tu paquete {$this->businessPackage->package->name} vence en {$this->daysRemaining} días",
        ];
    }
}