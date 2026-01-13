<?php
/**
 * Nombre de la clase           : EnsureHasBusiness
 * Descripción de la clase      : Middleware que verifica si un BusinessAdministrator
 *                                tiene su negocio registrado
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

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureHasBusiness
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Si no es BusinessAdministrator, dejar pasar
        if ($user->role !== 'BusinessAdministrator') {
            return $next($request);
        }

        // Verificar si tiene negocio
        if (!$user->business) {
            // Si está intentando registrar su negocio, permitir
            if ($request->routeIs('business.create') || $request->routeIs('business.store')) {
                return $next($request);
            }

            // Si no tiene negocio y no está registrándolo, redirigir
            return redirect()->route('business.create')
                ->with('warning', 'Debes registrar tu negocio para continuar.');
        }

        return $next($request);
    }
}