<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Business;
use App\Services\RatingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * RatingController
 * 
 * Controlador API para gestionar calificaciones desde la app móvil.
 *
 * @package App\Http\Controllers\Api
 */
class RatingController extends Controller
{
    /**
     * Instancia del servicio de calificaciones.
     *
     * @var RatingService
     */
    protected RatingService $ratingService;

    /**
     * Constructor del controlador.
     *
     * @param RatingService $ratingService
     */
    public function __construct(RatingService $ratingService)
    {
        $this->ratingService = $ratingService;
    }

    /**
     * Crea una calificación para una orden.
     *
     * @param Request $request
     * @param Order $order
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request, Order $order)
    {
        $validator = Validator::make($request->all(), [
            'stars' => 'required|integer|min:0|max:5',
            'comment' => 'nullable|string|max:500',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $rating = $this->ratingService->createRating(
                $order,
                $request->user(),
                $request->only(['stars', 'comment'])
            );

            return response()->json([
                'success' => true,
                'message' => 'Calificación registrada exitosamente',
                'data' => $rating,
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);
        }
    }

    /**
     * Lista las calificaciones de un negocio.
     *
     * @param Business $business
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Business $business)
    {
        $ratings = $business->ratings()
            ->with('user')
            ->latest()
            ->paginate(20);

        $stats = $this->ratingService->getBusinessRatingStats($business->id);

        return response()->json([
            'success' => true,
            'data' => [
                'ratings' => $ratings,
                'stats' => $stats,
            ],
        ]);
    }
}