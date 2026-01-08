<?php

namespace App\Services;

use App\Models\Rating;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * RatingService
 * 
 * Servicio para gestionar calificaciones de negocios.
 *
 * @package App\Services
 */
class RatingService
{
    /**
     * Crea una calificación para un negocio.
     *
     * @param Order $order Orden
     * @param User $user Usuario
     * @param array $data Datos de la calificación
     * @return Rating
     * @throws \Exception
     */
    public function createRating(Order $order, User $user, array $data): Rating
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
        
        DB::beginTransaction();
        
        try {
            $rating = Rating::create([
                'order_id' => $order->id,
                'business_id' => $order->business_id,
                'user_id' => $user->id,
                'stars' => $data['stars'],
                'comment' => $data['comment'] ?? null,
            ]);
            
            // Registrar actividad
            activity()
                ->performedOn($rating)
                ->causedBy($user)
                ->log('Calificación creada');
            
            DB::commit();
            
            return $rating;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Obtiene estadísticas de calificaciones de un negocio.
     *
     * @param int $businessId ID del negocio
     * @return array
     */
    public function getBusinessRatingStats(int $businessId): array
    {
        $ratings = Rating::forBusiness($businessId)->get();
        
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