<?php
/**
 * Nombre de la clase           : CheckActivePackage
 * Descripción de la clase      : Middleware que verifica que el negocio tenga
 *                                un paquete activo
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

/**
 * CheckActivePackage
 * 
 * Middleware para verificar que el negocio tenga un paquete activo.
 *
 * @package App\Http\Middleware
 */
class CheckActivePackage
{
    /**
     * Maneja una petición entrante.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isBusinessAdministrator()) {
            return $next($request);
        }

        $business = $user->business;

        if (!$business || !$business->hasActivePackage()) {
            return redirect()->route('packages.index')
                ->with('warning', 'Debes contratar un paquete para acceder a esta funcionalidad.');
        }

        return $next($request);
    }
}