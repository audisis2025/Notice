<?php
/**
 * Nombre de la clase           : Package
 * Descripción de la clase      : Modelo Eloquent que representa un paquete comercial
 *                                del sistema con sus características y precios
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
 * Modelo Package
 * 
 * Representa un paquete/plan comercial que pueden contratar los negocios.
 *
 * @property int $id
 * @property string $name
 * @property string $description
 * @property float $price
 * @property int $duration_days
 * @property bool $has_reports
 * @property bool $has_statistics
 * @property bool $has_filters
 * @property bool $is_active
 */
class Package extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
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
     *
     * @var array<string, string>
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
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function businessPackages()
    {
        return $this->hasMany(BusinessPackage::class);
    }

    /**
     * Scope: Filtra paquetes activos.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Verifica si el paquete tiene órdenes ilimitadas.
     *
     * @return bool
     */
    public function hasUnlimitedOrders(): bool
    {
        return $this->max_orders === null;
    }
}