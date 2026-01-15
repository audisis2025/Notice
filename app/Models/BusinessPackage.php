<?php

/**
 * Nombre de la clase           : BusinessPackage
 * Descripción de la clase      : Modelo Eloquent que representa la contratación
 *                                de un paquete con lógica de negocio integrada
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica movida al modelo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

class BusinessPackage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'business_id',
        'package_id',
        'start_date',
        'end_date',
        'price_paid',
        'discount_applied',
        'coupon_id',
        'status',
        'notification_sent',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'price_paid' => 'decimal:2',
        'discount_applied' => 'decimal:2',
        'notification_sent' => 'boolean',
    ];

    /**
     * Relación: Una suscripción pertenece a un negocio.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Una suscripción pertenece a un paquete.
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relación: Una suscripción puede tener un cupón asociado.
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Relación: Una suscripción puede tener un pago.
     */
    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    /**
     * Verifica si la suscripción está activa.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    /**
     * Verifica si la suscripción está próxima a vencer.
     */
    public function isNearExpiration(int $days = 7): bool
    {
        $daysUntilExpiration = now()->diffInDays($this->end_date, false);
        return $daysUntilExpiration > 0 && $daysUntilExpiration <= $days;
    }

    /**
     * Scope: Filtra suscripciones activas.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>=', now());
    }

    /**
     * Scope: Filtra suscripciones próximas a vencer.
     */
    public function scopeNearExpiration($query, int $days = 7)
    {
        return $query->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays($days)])
            ->where('notification_sent', false);
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en BusinessPackageService)
    // ====================================================================

    /**
     * Contrata un paquete para un negocio.
     *
     * @param Business $business
     * @param Package $package
     * @param array $paymentData
     * @param string|null $couponCode
     * @return BusinessPackage
     */
    public static function contractPackage(
        Business $business,
        Package $package,
        array $paymentData,
        ?string $couponCode = null
    ): BusinessPackage {
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

        $businessPackage = self::create([
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
        Payment::processPayment($business, $businessPackage, $paymentData, $price);

        return $businessPackage;
    }

    /**
     * Verifica y notifica paquetes próximos a vencer.
     *
     * @param int $days
     */
    public static function checkExpiringPackages(int $days = 7): void
    {
        $expiringPackages = self::nearExpiration($days)->get();

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
     * Cancela la suscripción.
     *
     * @return bool
     */
    public function cancelPackage(): bool
    {
        return $this->update(['status' => 'cancelled']);
    }
}
