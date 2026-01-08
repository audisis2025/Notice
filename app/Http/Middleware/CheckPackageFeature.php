<?php

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