<?php

/**
 * Nombre de la clase           : BusinessPackage
 * Descripción de la clase      : Modelo Eloquent que representa la contratación de un
 *                                paquete por parte de un negocio
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
 * Modelo BusinessPackage
 * * @property int $id
 * @property int $business_id
 * @property int $package_id
 * @property \Illuminate\Support\Carbon $start_date  // <--- CAMBIAR A CARBON
 * @property \Illuminate\Support\Carbon $end_date    // <--- CAMBIAR A CARBON
 * @property float $price_paid
 * @property string $status
 */
class BusinessPackage extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
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
     *
     * @var array<string, string>
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Una suscripción pertenece a un paquete.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function package()
    {
        return $this->belongsTo(Package::class);
    }

    /**
     * Relación: Una suscripción puede tener un cupón asociado.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function coupon()
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Relación: Una suscripción puede tener un pago.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
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
        // $this->end_date YA ES un objeto Carbon gracias al cast
        return $this->status === 'active' && $this->end_date->isFuture();
    }

    /**
     * Verifica si la suscripción está próxima a vencer.
     */
    public function isNearExpiration(int $days = 7): bool
    {
        // Mucho más directo:
        $daysUntilExpiration = now()->diffInDays($this->end_date, false);
        return $daysUntilExpiration > 0 && $daysUntilExpiration <= $days;
    }

    /**
     * Scope: Filtra suscripciones activas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')
            ->where('end_date', '>=', now());
    }

    /**
     * Scope: Filtra suscripciones próximas a vencer.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int $days
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNearExpiration($query, int $days = 7)
    {
        return $query->where('status', 'active')
            ->whereBetween('end_date', [now(), now()->addDays($days)])
            ->where('notification_sent', false);
    }
}
