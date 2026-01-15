<?php

/**
 * Nombre de la clase           : Rating
 * Descripción de la clase      : Modelo Eloquent que representa una calificación
 *                                con lógica de negocio integrada
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rating extends Model
{
    use HasFactory;

    /**
     * Los atributos que son asignables en masa.
     */
    protected $fillable = [
        'order_id',
        'business_id',
        'user_id',
        'stars',
        'comment',
    ];

    /**
     * Los atributos que deben ser convertidos.
     */
    protected $casts = [
        'stars' => 'integer',
    ];

    /**
     * Relación: Una calificación pertenece a una orden.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Relación: Una calificación pertenece a un negocio.
     */
    public function business()
    {
        return $this->belongsTo(Business::class);
    }

    /**
     * Relación: Una calificación pertenece a un usuario.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Filtra calificaciones por negocio.
     */
    public function scopeForBusiness($query, int $businessId)
    {
        return $query->where('business_id', $businessId);
    }

    // ====================================================================
    // MÉTODOS DE LÓGICA DE NEGOCIO (Anteriormente en RatingService)
    // ====================================================================

    /**
     * Crea una calificación para un negocio.
     *
     * @param Order $order
     * @param User $user
     * @param array $data
     * @return Rating
     * @throws \Exception
     */
    public static function createRating(Order $order, User $user, array $data): Rating
    {
        // Verificar que el usuario sea el dueño de la orden
        if ($order->user_id !== $user->id) {
            throw new \Exception('Solo el usuario de la orden puede calificar.');
        }

        // Verificar que la orden esté entregada
        if ($order->status !== 'delivered') {
            throw new \Exception('Solo se pueden calificar órdenes entregadas.');
        }

        // Verificar que el negocio permita calificaciones
        if (!$order->business->can_be_rated) {
            throw new \Exception('Este negocio no acepta calificaciones.');
        }

        // Verificar que no exista calificación previa
        if ($order->rating) {
            throw new \Exception('Esta orden ya ha sido calificada.');
        }

        return self::create([
            'order_id' => $order->id,
            'business_id' => $order->business_id,
            'user_id' => $user->id,
            'stars' => $data['stars'],
            'comment' => $data['comment'] ?? null,
        ]);
    }

    /**
     * Obtiene estadísticas de calificaciones de un negocio.
     *
     * @param int $businessId
     * @return array
     */
    public static function getBusinessRatingStats(int $businessId): array
    {
        $ratings = self::forBusiness($businessId)->get();

        $totalRatings = $ratings->count();
        $averageStars = $ratings->avg('stars') ?? 0;

        // Distribución de estrellas
        $distribution = [];
        for ($i = 0; $i <= 5; $i++) {
            $count = $ratings->where('stars', $i)->count();
            $percentage = $totalRatings > 0 ? ($count / $totalRatings) * 100 : 0;

            $distribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 2),
            ];
        }

        return [
            'total_ratings' => $totalRatings,
            'average_stars' => round($averageStars, 2),
            'distribution' => $distribution,
        ];
    }
}
