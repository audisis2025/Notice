<?php
/**
 * Nombre de la clase           : OrderService
 * Descripción de la clase      : Servicio que encapsula la lógica de negocio
 *                                para la gestión de órdenes y códigos QR
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

use App\Models\Order;
use App\Models\Business;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Str;
use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;

/**
 * OrderService
 * 
 * Servicio para gestionar órdenes del sistema.
 *
 * @package App\Services
 */
class OrderService
{
    /**
     * Crea una nueva orden.
     *
     * @param Business $business Negocio
     * @param array $data Datos de la orden
     * @return Order
     */
    public function createOrder(Business $business, array $data): Order
    {
        DB::beginTransaction();
        
        try {
            // Generar número de orden único
            $orderNumber = $this->generateOrderNumber($business);
            
            $order = Order::create([
                'order_number' => $orderNumber,
                'business_id' => $business->id,
                'description' => $data['description'],
                'amount' => $data['amount'],
                'status' => 'pending',
            ]);
            
            // Registrar actividad
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Orden creada');
            
            DB::commit();
            
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Genera un número de orden único.
     *
     * @param Business $business Negocio
     * @return string
     */
    protected function generateOrderNumber(Business $business): string
    {
        $prefix = strtoupper(substr($business->business_name, 0, 3));
        $timestamp = now()->format('YmdHis');
        $random = strtoupper(Str::random(4));
        
        return "{$prefix}-{$timestamp}-{$random}";
    }

    /**
     * Marca una orden como pagada y genera QR.
     *
     * @param Order $order Orden
     * @return Order
     */
    public function markAsPaid(Order $order): Order
    {
        DB::beginTransaction();
        
        try {
            // Generar código QR para asociar orden
            $qrCode = $this->generateQRCode($order, 'association');
            
            $order->update([
                'status' => 'paid',
                'paid_at' => now(),
                'qr_code' => $qrCode,
            ]);
            
            // Registrar actividad
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Orden marcada como pagada');
            
            DB::commit();
            
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Marca una orden como lista y notifica al usuario.
     *
     * @param Order $order Orden
     * @return Order
     */
    public function markAsReady(Order $order): Order
    {
        DB::beginTransaction();
        
        try {
            // Generar QR para confirmación de entrega
            $qrDeliveryCode = $this->generateQRCode($order, 'delivery');
            
            $order->update([
                'status' => 'ready',
                'ready_at' => now(),
                'qr_delivery_code' => $qrDeliveryCode,
            ]);
            
            // Notificar al usuario si está asociado
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderReadyNotification($order));
            }
            
            // Registrar actividad
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Orden marcada como lista');
            
            DB::commit();
            
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Asocia una orden con un usuario mediante escaneo de QR.
     *
     * @param Order $order Orden
     * @param User $user Usuario
     * @return Order
     */
    public function associateOrderWithUser(Order $order, User $user): Order
    {
        DB::beginTransaction();
        
        try {
            $order->update([
                'user_id' => $user->id,
                'associated_at' => now(),
            ]);
            
            // Registrar actividad
            activity()
                ->performedOn($order)
                ->causedBy($user)
                ->log('Orden asociada con usuario');
            
            DB::commit();
            
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Confirma la entrega de una orden mediante QR.
     *
     * @param Order $order Orden
     * @param User $user Usuario que confirma
     * @return Order
     * @throws \Exception
     */
    public function confirmDelivery(Order $order, User $user): Order
    {
        // Verificar que el usuario sea el dueño de la orden
        if ($order->user_id !== $user->id) {
            throw new \Exception('Solo el usuario asociado puede confirmar la entrega.');
        }
        
        // Verificar que la orden esté lista
        if ($order->status !== 'ready') {
            throw new \Exception('La orden no está lista para entrega.');
        }
        
        DB::beginTransaction();
        
        try {
            $order->update([
                'status' => 'delivered',
                'delivered_at' => now(),
            ]);
            
            // Registrar actividad
            activity()
                ->performedOn($order)
                ->causedBy($user)
                ->log('Entrega confirmada');
            
            DB::commit();
            
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Cancela una orden.
     *
     * @param Order $order Orden
     * @param string $reason Motivo de cancelación
     * @return Order
     */
    public function cancelOrder(Order $order, string $reason): Order
    {
        DB::beginTransaction();
        
        try {
            $order->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancellation_reason' => $reason,
            ]);
            
            // Notificar al usuario si está asociado
            if ($order->user) {
                $order->user->notify(new \App\Notifications\OrderCancelledNotification($order));
            }
            
            // Registrar actividad
            activity()
                ->performedOn($order)
                ->causedBy(auth()->user())
                ->log('Orden cancelada');
            
            DB::commit();
            
            return $order;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Genera un código QR para una orden.
     *
     * @param Order $order Orden
     * @param string $type Tipo de QR (association o delivery)
     * @return string Path del archivo QR
     */
    protected function generateQRCode(Order $order, string $type): string
    {
        $data = json_encode([
            'order_id' => $order->id,
            'order_number' => $order->order_number,
            'type' => $type,
            'business_id' => $order->business_id,
        ]);
        
        $qrCode = QrCode::format('png')
            ->size(300)
            ->generate($data);
        
        $filename = "{$order->order_number}_{$type}_" . time() . '.png';
        $path = "qrcodes/{$filename}";
        
        Storage::disk('public')->put($path, $qrCode);
        
        return $path;
    }

    /**
     * Verifica órdenes con retraso y habilita chat.
     *
     * @return void
     */
    public function checkDelayedOrders(): void
    {
        $delayedOrders = Order::ready()
            ->whereNotNull('ready_at')
            ->where('chat_enabled', false)
            ->get()
            ->filter(function ($order) {
                return $order->isDelayedDelivery();
            });
        
        foreach ($delayedOrders as $order) {
            $order->update(['chat_enabled' => true]);
            
            // Notificar al usuario que puede abrir chat
            if ($order->user) {
                $order->user->notify(new \App\Notifications\ChatEnabledNotification($order));
            }
        }
    }
}