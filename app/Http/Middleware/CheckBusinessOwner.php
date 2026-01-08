<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * CheckBusinessOwner
 * 
 * Middleware para verificar que el usuario sea propietario del negocio.
 *
 * @package App\Http\Middleware
 */
class CheckBusinessOwner
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
            abort(403, 'Acceso no autorizado.');
        }

        if (!$user->business) {
            return redirect()->route('business.create')
                ->with('info', 'Primero debes registrar tu negocio.');
        }

        return $next($request);
    }
}