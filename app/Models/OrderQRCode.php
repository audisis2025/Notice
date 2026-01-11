<?php
/**
 * Nombre de la clase           : OrderQRCode
 * Descripción de la clase      : Modelo Eloquent que representa un código QR generado
 *                                para asociación o entrega de una orden
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
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderQRCode extends Model
{
    use HasFactory;

    protected $table = 'order_qr_codes';

    protected $fillable = [
        'order_id',
        'type',
        'code',
        'qr_image',
        'is_used',
        'used_at',
        'used_by',
        'expires_at',
    ];

    protected $casts = [
        'is_used' => 'boolean',
        'used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Relación: Un QR pertenece a una orden
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación: Un QR fue usado por un usuario
     */
    public function usedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'used_by');
    }

    /**
     * Scope: Solo QR de asociación
     */
    public function scopeAssociation($query)
    {
        return $query->where('type', 'association');
    }

    /**
     * Scope: Solo QR de entrega
     */
    public function scopeDelivery($query)
    {
        return $query->where('type', 'delivery');
    }

    /**
     * Scope: Solo QR no usados
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope: Solo QR válidos (no expirados)
     */
    public function scopeValid($query)
    {
        return $query->where(function ($q) {
            $q->whereNull('expires_at')
              ->orWhere('expires_at', '>', now());
        });
    }

    /**
     * Verificar si el QR está expirado
     */
    public function isExpired(): bool
    {
        if (!$this->expires_at) {
            return false;
        }

        return $this->expires_at->isPast();
    }

    /**
     * Verificar si el QR es válido (no usado y no expirado)
     */
    public function isValid(): bool
    {
        return !$this->is_used && !$this->isExpired();
    }

    /**
     * Marcar el QR como usado
     */
    public function markAsUsed(int $userId): void
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'used_by' => $userId,
        ]);
    }
}