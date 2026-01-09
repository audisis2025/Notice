<?php
/**
 * Nombre de la clase           : Business
 * Descripción de la clase      : Modelo Eloquent que representa un negocio registrado
 *                                en el sistema con su información y configuración
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

/**
 * Modelo Business
 * 
 * Representa un negocio registrado en el sistema.
 *
 * @property int $id
 * @property int $user_id
 * @property string $business_name
 * @property string $legal_name
 * @property string $tax_id
 * @property string $address
 * @property bool $can_be_rated
 * @property int $delivery_period_minutes
 * @property bool $is_active
 */
class Business extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'business_name',
        'legal_name',
        'tax_id',
        'address',
        'city',
        'state',
        'country',
        'postal_code',
        'phone',
        'email',
        'website',
        'description',
        'logo',
        'latitude',
        'longitude',
        'can_be_rated',
        'delivery_period_minutes',
        'is_active',
        'service_suspended_at',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'can_be_rated' => 'boolean',
        'delivery_period_minutes' => 'integer',
        'is_active' => 'boolean',
        'service_suspended_at' => 'datetime',
    ];

    /**
     * Relación: Un negocio pertenece a un usuario administrador.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un negocio puede tener muchas órdenes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un negocio puede tener muchas calificaciones.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relación: Un negocio puede tener muchas suscripciones a paquetes.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function businessPackages()
    {
        return $this->hasMany(BusinessPackage::class);
    }

    /**
     * Relación: Un negocio puede tener muchos pagos.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relación: Un negocio puede tener muchos chats.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Obtiene el paquete activo actual del negocio.
     *
     * @return \App\Models\BusinessPackage|null
     */
    public function getActivePackageAttribute()
    {
        return $this->businessPackages()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->latest()
            ->first();
    }

    /**
     * Verifica si el negocio tiene un paquete activo.
     *
     * @return bool
     */
    public function hasActivePackage(): bool
    {
        return $this->activePackage !== null;
    }

    /**
     * Calcula el promedio de calificaciones del negocio.
     *
     * @return float
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('stars') ?? 0;
    }

    /**
     * Scope: Filtra negocios activos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereNull('service_suspended_at');
    }

    /**
     * Scope: Filtra negocios que pueden ser calificados.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCanBeRated($query)
    {
        return $query->where('can_be_rated', true);
    }
}