<?php
/**
 * Nombre de la clase           : Order
 * Descripción de la clase      : Modelo Eloquent que representa una orden creada por
 *                                un negocio para gestionar servicios o productos
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
 * Modelo Order
 * 
 * Representa una orden creada por un negocio para un servicio o producto.
 *
 * @property int $id
 * @property string $order_number
 * @property int $business_id
 * @property int|null $user_id
 * @property string $description
 * @property float $amount
 * @property string $status
 */
class Order extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_number',
        'business_id',
        'user_id',
        'description',
        'amount',
        'status',
        'qr_code',
        'qr_delivery_code',
        'paid_at',
        'ready_at',
        'delivered_at',
        'cancelled_at',
        'cancellation_reason',
        'associated_at',
        'chat_enabled',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'amount' => 'decimal:2',
        'paid_at' => 'datetime',
        'ready_at' => 'datetime',
        'delivered_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'associated_at' => 'datetime',
        'chat_enabled' => 'boolean',
    ];

    /**
     * Relación: Una orden pertenece a un negocio.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Una orden puede pertenecer a un usuario.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Una orden puede tener muchos recordatorios.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function reminders()
    {
        return $this->hasMany(OrderReminder::class);
    }

    /**
     * Relación: Una orden puede tener una calificación.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function rating()
    {
        return $this->hasOne(Rating::class);
    }

    /**
     * Relación: Una orden puede tener un chat.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function chat()
    {
        return $this->hasOne(Chat::class);
    }

    /**
     * Verifica si la orden está asociada a un usuario.
     *
     * @return bool
     */
    public function isAssociated(): bool
    {
        return $this->user_id !== null && $this->associated_at !== null;
    }

    /**
     * Verifica si la orden puede ser entregada.
     *
     * @return bool
     */
    public function canBeDelivered(): bool
    {
        return $this->status === 'ready' && $this->isAssociated();
    }

    /**
     * Verifica si se ha excedido el período de entrega.
     *
     * @return bool
     */
    public function isDelayedDelivery(): bool
    {
        if (!$this->ready_at || $this->status !== 'ready') {
            return false;
        }

        $deliveryPeriod = $this->business->delivery_period_minutes;
        $readyTime = Carbon::parse($this->ready_at);
        $expectedDeliveryTime = $readyTime->addMinutes($deliveryPeriod);

        return Carbon::now()->isAfter($expectedDeliveryTime);
    }

    /**
     * Scope: Filtra órdenes por estado.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeByStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope: Filtra órdenes listas.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeReady($query)
    {
        return $query->where('status', 'ready');
    }

    /**
     * Scope: Filtra órdenes con retraso en entrega.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDelayed($query)
    {
        return $query->where('status', 'ready')
            ->whereNotNull('ready_at')
            ->where('chat_enabled', false)
            ->get()
            ->filter(function ($order) {
                return $order->isDelayedDelivery();
            });
    }
}