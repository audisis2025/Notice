<?php

/**
 * Nombre de la clase           : Business
 * Descripción de la clase      : Modelo Eloquent que representa un negocio registrado
 *                                en el sistema con su información, configuración y lógica de negocio
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.1
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 2
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Agregado método currentPackage() como alias
 * Responsable                  : Sistema
 * Revisor                      : Jesús Núñez
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

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
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relación: Un negocio puede tener muchas órdenes.
     */
    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Relación: Un negocio puede tener muchas calificaciones.
     */
    public function ratings()
    {
        return $this->hasMany(Rating::class);
    }

    /**
     * Relación: Un negocio puede tener muchas suscripciones a paquetes.
     */
    public function businessPackages()
    {
        return $this->hasMany(BusinessPackage::class);
    }

    /**
     * Relación: Un negocio puede tener muchos pagos.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    /**
     * Relación: Un negocio puede tener muchos chats.
     */
    public function chats()
    {
        return $this->hasMany(Chat::class);
    }

    /**
     * Obtiene el paquete activo actual del negocio.
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
     */
    public function hasActivePackage(): bool
    {
        return $this->activePackage !== null;
    }

    /**
     * Calcula el promedio de calificaciones del negocio.
     */
    public function getAverageRatingAttribute(): float
    {
        return $this->ratings()->avg('stars') ?? 0;
    }

    /**
     * Scope: Filtra negocios activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->whereNull('service_suspended_at');
    }

    /**
     * Scope: Filtra negocios que pueden ser calificados.
     */
    public function scopeCanBeRated($query)
    {
        return $query->where('can_be_rated', true);
    }

    /**
     * Obtiene el paquete activo con detalles.
     */
    public function activePackage()
    {
        return $this->businessPackages()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->with('package')
            ->latest()
            ->first();
    }

    /**
     * Alias de activePackage() para mantener consistencia en el código.
     * 
     * @return \App\Models\BusinessPackage|null
     */
    public function currentPackage()
    {
        return $this->activePackage();
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en BusinessService)
    // ====================================================================

    /**
     * Crea un nuevo negocio con manejo de logo.
     *
     * @param User $user
     * @param array $data
     * @return Business
     */
    public static function createBusiness(User $user, array $data): Business
    {
        // Procesar logo si existe
        if (isset($data['logo']) && $data['logo']) {
            $logoPath = $data['logo']->store('businesses/logos', 'public');
            $data['logo'] = $logoPath;
        }

        // Asegurar que can_be_rated sea boolean
        $data['can_be_rated'] = isset($data['can_be_rated']) ? (bool)$data['can_be_rated'] : true;
        $data['user_id'] = $user->id;

        return self::create($data);
    }

    /**
     * Actualiza el negocio con manejo de logo.
     *
     * @param array $data
     * @return bool
     */
    public function updateBusiness(array $data): bool
    {
        // Procesar logo si existe
        if (isset($data['logo']) && $data['logo']) {
            // Eliminar logo anterior
            if ($this->logo) {
                Storage::disk('public')->delete($this->logo);
            }

            $logoPath = $data['logo']->store('businesses/logos', 'public');
            $data['logo'] = $logoPath;
        }

        return $this->update($data);
    }

    /**
     * Suspende el servicio del negocio.
     *
     * @param string|null $reason
     * @return bool
     */
    public function suspend(?string $reason = null): bool
    {
        return $this->update([
            'is_active' => false,
            'service_suspended_at' => now(),
        ]);
    }

    /**
     * Reactiva el servicio del negocio.
     *
     * @return bool
     */
    public function reactivate(): bool
    {
        return $this->update([
            'is_active' => true,
            'service_suspended_at' => null,
        ]);
    }

    /**
     * Obtiene negocios cercanos a una ubicación usando la fórmula de Haversine.
     *
     * @param float $latitude
     * @param float $longitude
     * @param int $radiusKm
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getNearbyBusinesses(float $latitude, float $longitude, int $radiusKm = 10)
    {
        return self::active()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->selectRaw("
                *,
                ( 6371 * acos( cos( radians(?) ) *
                cos( radians( latitude ) ) *
                cos( radians( longitude ) - radians(?) ) +
                sin( radians(?) ) *
                sin( radians( latitude ) ) ) ) AS distance
            ", [$latitude, $longitude, $latitude])
            ->having('distance', '<', $radiusKm)
            ->orderBy('distance')
            ->get();
    }

    /**
     * Configura si el negocio puede ser calificado.
     *
     * @param bool $canBeRated
     * @return bool
     */
    public function toggleRatings(bool $canBeRated): bool
    {
        return $this->update(['can_be_rated' => $canBeRated]);
    }

    /**
     * Actualiza el período de entrega.
     *
     * @param int $minutes
     * @return bool
     */
    public function updateDeliveryPeriod(int $minutes): bool
    {
        return $this->update(['delivery_period_minutes' => $minutes]);
    }
}