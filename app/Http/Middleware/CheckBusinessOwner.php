<?php
/**
 * Nombre de la clase           : CheckBusinessOwner
 * Descripción de la clase      : Middleware que verifica que el usuario sea
 *                                propietario del negocio
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