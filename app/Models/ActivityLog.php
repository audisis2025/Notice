<?php
/**
 * Nombre de la clase           : ActivityLog
 * Descripción de la clase      : Modelo Eloquent que representa un registro de
 *                                actividad de usuario en el sistema
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

/**
 * Modelo ActivityLog
 * 
 * Representa un registro de actividad en el sistema.
 *
 * @property int $id
 * @property string|null $log_name
 * @property string $description
 * @property string|null $event
 */
class ActivityLog extends Model
{
    use HasFactory;

    /**
     * La tabla asociada con el modelo.
     *
     * @var string
     */
    protected $table = 'activity_logs';

    /**
     * Indica si el modelo debe tener timestamps.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'log_name',
        'description',
        'subject_type',
        'subject_id',
        'causer_type',
        'causer_id',
        'properties',
        'event',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'properties' => 'array',
        'created_at' => 'datetime',
    ];

    /**
     * Relación polimórfica: El sujeto de la actividad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function subject()
    {
        return $this->morphTo();
    }

    /**
     * Relación polimórfica: El causante de la actividad.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphTo
     */
    public function causer()
    {
        return $this->morphTo();
    }

    /**
     * Scope: Filtra por nombre de log.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $logName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeInLog($query, string $logName)
    {
        return $query->where('log_name', $logName);
    }
}
