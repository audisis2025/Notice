<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderReminder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * OrderReminderService
 * 
 * Servicio para gestionar recordatorios de órdenes.
 *
 * @package App\Services
 */
class OrderReminderService
{
    /**
     * Programa recordatorios para una orden.
     *
     * @param Order $order Orden
     * @param array $reminderMinutes Array de minutos para recordatorios
     * @return void
     */
    public function scheduleReminders(Order $order, array $reminderMinutes): void
    {
        if (!$order->ready_at) {
            return;
        }
        
        DB::beginTransaction();
        
        try {
            $readyTime = Carbon::parse($order->ready_at);
            
            foreach ($reminderMinutes as $minutes) {
                $scheduledAt = $readyTime->copy()->addMinutes($minutes);
                
                OrderReminder::create([
                    'order_id' => $order->id,
                    'reminder_minutes' => $minutes,
                    'scheduled_at' => $scheduledAt,
                    'sent' => false,
                ]);
            }
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Envía recordatorios pendientes.
     *
     * @return void
     */
    public function sendPendingReminders(): void
    {
        $pendingReminders = OrderReminder::pending()->get();
        
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