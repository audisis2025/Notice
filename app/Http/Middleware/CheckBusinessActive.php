<?php

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