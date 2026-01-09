<?php
/**
 * Nombre de la clase           : CheckPackageFeature
 * Descripción de la clase      : Middleware que verifica que el paquete activo
 *                                tenga habilitada una característica específica
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
 * CheckPackageFeature
 * 
 * Middleware para verificar que el paquete activo tenga una característica específica.
 *
 * @package App\Http\Middleware
 */
class CheckPackageFeature
{
    /**
     * Maneja una petición entrante.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @param string $feature
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function handle(Request $request, Closure $next, string $feature): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        $user = auth()->user();

        if (!$user->isBusinessAdministrator()) {
            return $next($request);
        }

        $business = $user->business;
        $activePackage = $business?->activePackage;

        if (!$activePackage || !$activePackage->package->{"has_{$feature}"}) {
            abort(403, 'Tu paquete actual no incluye esta funcionalidad. Actualiza tu plan.');
        }

        return $next($request);
    }
}