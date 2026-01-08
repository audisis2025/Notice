<?php

namespace App\Services;

use App\Models\Business;
use App\Models\BusinessPackage;
use App\Models\Package;
use App\Models\Coupon;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

/**
 * BusinessPackageService
 * 
 * Servicio para gestionar la contratación de paquetes por negocios.
 *
 * @package App\Services
 */
class BusinessPackageService
{
    /**
     * Contrata un paquete para un negocio.
     *
     * @param Business $business Negocio
     * @param Package $package Paquete a contratar
     * @param array $paymentData Datos de pago
     * @param string|null $couponCode Código de cupón opcional
     * @return BusinessPackage
     */
    public function contractPackage(
        Business $business,
        Package $package,
        array $paymentData,
        ?string $couponCode = null
    ): BusinessPackage {
        DB::beginTransaction();
        
        try {
            $price = $package->price;
            $discount = 0;
            $coupon = null;
            
            // Aplicar cupón si existe
            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                
                if ($coupon && $coupon->isAvailable()) {
                    $discount = $coupon->calculateDiscount($price);
                    $price -= $discount;
                    
                    // Marcar cupón como usado
                    $coupon->update([
                        'is_used' => true,
                        'used_by_business_id' => $business->id,
                        'used_at' => now(),
                    ]);
                }
            }
            
            // Crear suscripción
            $startDate = Carbon::now();
            $endDate = $startDate->copy()->addDays($package->duration_days);
            
            $businessPackage = BusinessPackage::create([
                'business_id' => $business->id,
                'package_id' => $package->id,
                'start_date' => $startDate,
                'end_date' => $endDate,
                'price_paid' => $price,
                'discount_applied' => $discount,
                'coupon_id' => $coupon ? $coupon->id : null,
                'status' => 'active',
            ]);
            
            // Procesar pago (simulado)
            $this->processPayment($business, $businessPackage, $paymentData, $price);
            
            // Registrar actividad
            activity()
                ->performedOn($businessPackage)
                ->causedBy(auth()->user())
                ->withProperties([
                    'package' => $package->name,
                    'price' => $price,
                    'discount' => $discount,
                ])
                ->log('Paquete contratado');
            
            DB::commit();
            
            return $businessPackage;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Procesa un pago simulado.
     *
     * @param Business $business Negocio
     * @param BusinessPackage $businessPackage Suscripción
     * @param array $paymentData Datos de pago
     * @param float $amount Monto
     * @return Payment
     */
    protected function processPayment(
        Business $business,
        BusinessPackage $businessPackage,
        array $paymentData,
        float $amount
    ): Payment {
        // Generar ID de transacción único
        $transactionId = 'TXN-' . strtoupper(uniqid());
        
        // Obtener últimos 4 dígitos de la tarjeta
        $cardLastFour = substr($paymentData['card_number'], -4);
        
        // Simular tipo de tarjeta
        $cardBrand = $this->detectCardBrand($paymentData['card_number']);
        
        $payment = Payment::create([
            'business_id' => $business->id,
            'business_package_id' => $businessPackage->id,
            'payment_method' => $paymentData['payment_method'],
            'card_last_four' => $cardLastFour,
            'card_brand' => $cardBrand,
            'amount' => $amount,
            'status' => 'completed',
            'transaction_id' => $transactionId,
        ]);
        
        return $payment;
    }

    /**
     * Detecta la marca de tarjeta por el número (simulado).
     *
     * @param string $cardNumber Número de tarjeta
     * @return string
     */
    protected function detectCardBrand(string $cardNumber): string
    {
        $firstDigit = substr($cardNumber, 0, 1);
        
        return match($firstDigit) {
            '4' => 'Visa',
            '5' => 'Mastercard',
            '3' => 'American Express',
            default => 'Unknown',
        };
    }

    /**
     * Verifica y notifica paquetes próximos a vencer.
     *
     * @param int $days Días antes del vencimiento
     * @return void
     */
    public function checkExpiringPackages(int $days = 7): void
    {
        $expiringPackages = BusinessPackage::nearExpiration($days)->get();
        
        foreach ($expiringPackages as $businessPackage) {
            // Enviar notificación al negocio
            $businessPackage->business->user->notify(
                new \App\Notifications\PackageExpiringNotification($businessPackage)
            );
            
            // Marcar como notificado
            $businessPackage->update(['notification_sent' => true]);
        }
    }

    /**
     * Cancela una suscripción de paquete.
     *
     * @param BusinessPackage $businessPackage Suscripción
     * @return BusinessPackage
     */
    public function cancelPackage(BusinessPackage $businessPackage): BusinessPackage
    {
        DB::beginTransaction();
        
        try {
            $businessPackage->update(['status' => 'cancelled']);
            
            // Registrar actividad
            activity()
                ->performedOn($businessPackage)
                ->causedBy(auth()->user())
                ->log('Paquete cancelado');
            
            DB::commit();
            
            return $businessPackage;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}