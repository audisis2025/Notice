<?php

/**
 * Nombre de la clase           : RatingController
 * Descripción de la clase      : Controlador que gestiona calificaciones sin Services
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Rating;
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
     * Muestra las calificaciones de un negocio.
     */
    public function index(Business $business)
    {
        $ratings = $business->ratings()
            ->with('user', 'order')
            ->latest()
            ->paginate(15);

        // Obtener estadísticas usando el método del modelo
        $stats = Rating::getBusinessRatingStats($business->id);

        return view('ratings.index', compact('business', 'ratings', 'stats'));
    }
}
