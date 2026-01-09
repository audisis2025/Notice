<?php
/**
 * Nombre de la clase           : OrderReminder
 * Descripción de la clase      : Modelo Eloquent que representa un recordatorio
 *                                programado para una orden
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
 * Modelo OrderReminder
 * 
 * Representa un recordatorio programado para una orden.
 *
 * @property int $id
 * @property int $order_id
 * @property int $reminder_minutes
 * @property string $scheduled_at
 * @property bool $sent
 */
class OrderReminder extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'order_id',
        'reminder_minutes',
        'scheduled_at',
        'sent',
        'sent_at',
    ];

    /**
     * Los atributos que deben ser convertidos.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'reminder_minutes' => 'integer',
        'scheduled_at' => 'datetime',
        'sent' => 'boolean',
        'sent_at' => 'datetime',
    ];

    /**
     * Relación: Un recordatorio pertenece a una orden.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Scope: Filtra recordatorios pendientes de envío.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('sent', false)
            ->where('scheduled_at', '<=', now());
    }
}
