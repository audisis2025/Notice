<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Business;
use App\Services\BusinessService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * BusinessController
 * 
 * Controlador API para gestionar negocios desde la app móvil.
 *
 * @package App\Http\Controllers\Api
 */
class BusinessController extends Controller
{
    /**
     * Instancia del servicio de negocios.
     *
     * @var BusinessService
     */
    protected BusinessService $businessService;

    /**
     * Constructor del controlador.
     *
     * @param BusinessService $businessService
     */
    public function __construct(BusinessService $businessService)
    {
        $this->businessService = $businessService;
    }

    /**
     * Lista todos los negocios activos.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $query = Business::active()->with('ratings');

        // Filtros opcionales
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('business_name', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('city')) {
            $query->where('city', $request->city);
        }

        $businesses = $query->paginate(20);

        // Agregar promedio de calificaciones
        $businesses->getCollection()->transform(function ($business) {
            $business->average_rating = $business->averageRating;
            return $business;
        });

        return response()->json([
            'success' => true,
            'data' => $businesses,
        ]);
    }

    /**
     * Obtiene negocios cercanos a una ubicación.
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function nearby(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180',
            'radius_km' => 'nullable|integer|min:1|max:50',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        $businesses = $this->businessService->getNearbyBusinesses(
            $request->latitude,
            $request->longitude,
            $request->radius_km ?? 10
        );

        // Agregar promedio de calificaciones
        $businesses->transform(function ($business) {
            $business->average_rating = $business->averageRating;
            return $business;
        });

        return response()->json([
            'success' => true,
            'data' => $businesses,
        ]);
    }

    /**
     * Muestra los detalles de un negocio específico.
     *
     * @param Business $business
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Business $business)
    {
        if (!$business->is_active) {
            return response()->json([
                'success' => false,
                'message' => 'Negocio no disponible',
            ], 404);
        }

        $business->load('ratings.user');
        $business->average_rating = $business->averageRating;

        return response()->json([
            'success' => true,
            'data' => $business,
        ]);
    }
}