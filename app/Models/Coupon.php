<?php

/**
 * Nombre de la clase           : Coupon
 * Descripción de la clase      : Modelo Eloquent que representa un cupón de descuento
 *                                con lógica de negocio integrada
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica movida al modelo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'code',
        'discount_percentage',
        'expiration_date',
        'is_used',
        'used_by_business_id',
        'used_at',
        'is_active',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'expiration_date' => 'date',
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    /**
     * Relación: Un cupón puede ser usado por un negocio.
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'used_by_business_id');
    }

    /**
     * Relación: Un cupón puede estar en muchas suscripciones.
     */
    public function businessPackages()
    {
        return $this->hasMany(BusinessPackage::class);
    }

    /**
     * Verifica si el cupón está disponible para uso.
     */
    public function isAvailable(): bool
    {
        return $this->is_active &&
               !$this->is_used &&
               Carbon::parse($this->expiration_date)->isFuture();
    }

    /**
     * Verifica si el cupón está vencido.
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->expiration_date)->isPast();
    }

    /**
     * Calcula el monto de descuento para un precio dado.
     */
    public function calculateDiscount(float $price): float
    {
        return $price * ($this->discount_percentage / 100);
    }

    /**
     * Scope: Filtra cupones disponibles.
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->where('is_used', false)
            ->where('expiration_date', '>=', now());
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en CouponService)
    // ====================================================================

    /**
     * Genera un nuevo cupón.
     *
     * @param array $data
     * @return Coupon
     */
    public static function generateCoupon(array $data): Coupon
    {
        // Si no se proporciona código, generar uno automáticamente
        if (!isset($data['code']) || empty($data['code'])) {
            $data['code'] = self::generateUniqueCode();
        }

        return self::create($data);
    }

    /**
     * Genera un código único para un cupón.
     *
     * @param int $length
     * @return string
     */
    protected static function generateUniqueCode(int $length = 8): string
    {
        do {
            $code = strtoupper(Str::random($length));
        } while (self::where('code', $code)->exists());

        return $code;
    }

    /**
     * Valida un cupón por su código.
     *
     * @param string $code
     * @return Coupon
     * @throws \Exception
     */
    public static function validateCoupon(string $code): Coupon
    {
        $coupon = self::where('code', $code)->first();

        if (!$coupon) {
            throw new \Exception('El cupón no existe.');
        }

        if (!$coupon->is_active) {
            throw new \Exception('El cupón está inactivo.');
        }

        if ($coupon->is_used) {
            throw new \Exception('El cupón ya ha sido utilizado.');
        }

        if ($coupon->isExpired()) {
            throw new \Exception('El cupón ha expirado.');
        }

        return $coupon;
    }

    /**
     * Actualiza el cupón.
     *
     * @param array $data
     * @return bool
     */
    public function updateCoupon(array $data): bool
    {
        return $this->update($data);
    }

    /**
     * Activa o desactiva el cupón.
     *
     * @param bool $isActive
     * @return bool
     */
    public function toggleStatus(bool $isActive): bool
    {
        return $this->update(['is_active' => $isActive]);
    }
}
