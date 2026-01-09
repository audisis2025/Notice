<?php
/**
 * Nombre de la clase           : Coupon
 * Descripción de la clase      : Modelo Eloquent que representa un cupón de descuento
 *                                para la contratación de paquetes
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
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Carbon\Carbon;

/**
 * Modelo Coupon
 * 
 * Representa un cupón de descuento generado por el SuperAdministrador.
 *
 * @property int $id
 * @property string $code
 * @property float $discount_percentage
 * @property string $expiration_date
 * @property bool $is_used
 * @property bool $is_active
 */
class Coupon extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
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
     *
     * @var array<string, string>
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class, 'used_by_business_id');
    }

    /**
     * Relación: Un cupón puede estar en muchas suscripciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function businessPackages()
    {
        return $this->hasMany(BusinessPackage::class);
    }

    /**
     * Verifica si el cupón está disponible para uso.
     *
     * @return bool
     */
    public function isAvailable(): bool
    {
        return $this->is_active && 
               !$this->is_used && 
               Carbon::parse($this->expiration_date)->isFuture();
    }

    /**
     * Verifica si el cupón está vencido.
     *
     * @return bool
     */
    public function isExpired(): bool
    {
        return Carbon::parse($this->expiration_date)->isPast();
    }

    /**
     * Calcula el monto de descuento para un precio dado.
     *
     * @param float $price
     * @return float
     */
    public function calculateDiscount(float $price): float
    {
        return $price * ($this->discount_percentage / 100);
    }

    /**
     * Scope: Filtra cupones disponibles.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAvailable($query)
    {
        return $query->where('is_active', true)
            ->where('is_used', false)
            ->where('expiration_date', '>=', now());
    }
}