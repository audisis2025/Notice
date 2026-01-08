<?php

namespace App\Models;
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Modelo Payment
 * 
 * Representa un pago simulado realizado por un negocio.
 *
 * @property int $id
 * @property int $business_id
 * @property int $business_package_id
 * @property string $payment_method
 * @property float $amount
 * @property string $status
 */
class Payment extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'business_id',
        'business_package_id',
        'payment_method',
        'card_last_four',
        'card_brand',
        'amount',
        'status',
        'transaction_id',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
    ];

    /**
     * Relación: Un pago pertenece a un negocio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Un pago pertenece a una suscripción de paquete.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function businessPackage()
    {
        return $this->belongsTo(BusinessPackage::class);
    }

    /**
     * Scope: Filtra pagos completados.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}