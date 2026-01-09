<?php
/**
 * Nombre de la clase           : CheckBusinessActive
 * Descripción de la clase      : Middleware que verifica que el negocio esté
 *                                activo y no suspendido
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
 * CheckBusinessActive
 * 
 * Middleware para verificar que el negocio esté activo.
 *
 * @package App\Http\Middleware
 */
class CheckBusinessActive
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

        if (!$business || !$business->is_active) {
            return redirect()->route('dashboard')
                ->with('error', 'Tu negocio está suspendido. Contacta al administrador.');
        }

        return $next($request);
    }
}