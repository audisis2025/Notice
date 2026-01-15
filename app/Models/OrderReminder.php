<?php

/**
 * Nombre de la clase           : OrderReminder
 * Descripción de la clase      : Modelo Eloquent que representa un recordatorio
 *                                con lógica de negocio integrada
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class OrderReminder extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'order_id',
        'reminder_minutes',
        'scheduled_at',
        'sent',
        'sent_at',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'reminder_minutes' => 'integer',
        'scheduled_at' => 'datetime',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * Relación: Un recordatorio pertenece a una orden.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Filtra recordatorios pendientes de envío.
     */
    public function scopePending($query)
    {
        return $query->where('sent', false)
            ->where('scheduled_at', '<=', now());
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en OrderReminderService)
    // ====================================================================

    /**
     * Programa recordatorios para una orden.
     *
     * @param Order $order
     * @param array $reminderMinutes
     */
    public static function scheduleReminders(Order $order, array $reminderMinutes): void
    {
        if (!$order->ready_at) {
            return;
        }

        $readyTime = Carbon::parse($order->ready_at);

        foreach ($reminderMinutes as $minutes) {
            $scheduledAt = $readyTime->copy()->addMinutes($minutes);

            self::create([
                'order_id' => $order->id,
                'reminder_minutes' => $minutes,
                'scheduled_at' => $scheduledAt,
                'sent' => false,
            ]);
        }
    }

    /**
     * Envía recordatorios pendientes.
     */
    public static function sendPendingReminders(): void
    {
        $pendingReminders = self::pending()->get();

        foreach ($pendingReminders as $reminder) {
            $order = $reminder->order;

            // Solo enviar si la orden sigue lista
            if ($order->status === 'ready' && $order->user) {
                $order->user->notify(new \App\Notifications\OrderReminderNotification($order));

                $reminder->update([
                    'sent' => true,
                    'sent_at' => now(),
                ]);
            }
        }
    }
}
