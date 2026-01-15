<?php

/**
 * Nombre de la clase           : Package
 * Descripción de la clase      : Modelo Eloquent que representa un paquete comercial
 *                                con lógica de negocio integrada
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminación de Services - Lógica movida al modelo
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Package extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'name',
        'description',
        'price',
        'duration_days',
        'has_reports',
        'has_statistics',
        'has_filters',
        'data_retention_days',
        'max_orders',
        'is_active',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'price' => 'decimal:2',
        'duration_days' => 'integer',
        'has_reports' => 'boolean',
        'has_statistics' => 'boolean',
        'has_filters' => 'boolean',
        'data_retention_days' => 'integer',
        'max_orders' => 'integer',
        'is_active' => 'boolean',
    ];

    /**
     * Relación: Un paquete puede estar en muchas suscripciones.
     */
    public function businessPackages()
    {
        return $this->hasMany(BusinessPackage::class);
    }

    /**
     * Scope: Filtra paquetes activos.
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verifica si el paquete tiene órdenes ilimitadas.
     */
    public function hasUnlimitedOrders(): bool
    {
        return $this->max_orders === null;
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en PackageService)
    // ====================================================================

    /**
     * Crea un nuevo paquete comercial.
     *
     * @param array $data
     * @return Package
     */
    public static function createPackage(array $data): Package
    {
        return self::create($data);
    }

    /**
     * Actualiza el paquete.
     *
     * @param array $data
     * @return bool
     */
    public function updatePackage(array $data): bool
    {
        return $this->update($data);
    }

    /**
     * Obtiene todos los paquetes activos ordenados por precio.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function getActivePackages()
    {
        return self::active()->orderBy('price')->get();
    }

    /**
     * Activa o desactiva el paquete.
     *
     * @param bool $isActive
     * @return bool
     */
    public function toggleStatus(bool $isActive): bool
    {
        return $this->update(['is_active' => $isActive]);
    }

    /**
     * Suscribe un negocio a este paquete.
     *
     * @param Business $business
     * @return BusinessPackage
     */
    public function subscribeBusinessTo(Business $business): BusinessPackage
    {
        return BusinessPackage::create([
            'business_id' => $business->id,
            'package_id' => $this->id,
            'start_date' => now(),
            'end_date' => now()->addDays($this->duration_days),
            'price_paid' => $this->price,
            'status' => 'active',
        ]);
    }
}
