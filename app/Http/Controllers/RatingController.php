<?php

namespace App\Http\Controllers;

use App\Models\Business;
use App\Services\RatingService;
use Illuminate\Http\Request;

/**
 * RatingController
 * 
 * Controlador para gestionar calificaciones de negocios.
 *
 * @package App\Http\Controllers
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
     * Muestra las calificaciones de un negocio.
     *
     * @param Business $business
     * @return \Illuminate\View\View
     */
    public function index(Business $business)
    {
        $ratings = $business->ratings()
            ->with('user', 'order')
            ->latest()
            ->paginate(15);

        $stats = $this->ratingService->getBusinessRatingStats($business->id);

        return view('ratings.index', compact('business', 'ratings', 'stats'));
    }
}