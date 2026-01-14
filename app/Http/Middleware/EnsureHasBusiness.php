<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureHasBusiness
{
    public function handle(Request $request, Closure $next)
    {
        $user = auth()->user();

        // Solo aplica a BusinessAdministrator
        if ($user->role !== 'BusinessAdministrator') {
            return $next($request);
        }

        // ✅ RUTAS QUE NO REQUIEREN NEGOCIO
        $publicRoutes = [
            'business.create',
            'business.store',
            'dashboard',
        ];

        if ($request->routeIs($publicRoutes)) {
            return $next($request);
        }

        // ✅ VERIFICAR QUE TENGA NEGOCIO
        $business = $user->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('error', 'Debes registrar tu negocio primero.');
        }

        // ✅ RUTAS QUE NO REQUIEREN PAQUETE ACTIVO
        $routesWithoutPackageRequirement = [
            'packages.available',    // ← CLAVE: Permitir ver paquetes
            'packages.subscribe',    // ← CLAVE: Permitir contratar
            'business.edit',
            'business.update',
            'business.toggle-ratings',
            'business.update-delivery-period',
            'business.ratings',
        ];

        if ($request->routeIs($routesWithoutPackageRequirement)) {
            return $next($request);
        }

        // ✅ VERIFICAR PAQUETE ACTIVO SOLO PARA OTRAS RUTAS
        $hasActivePackage = $business->businessPackages()
            ->where('status', 'active')
            ->where('end_date', '>=', now())
            ->exists();

        if (!$hasActivePackage) {
            return redirect()->route('packages.available')
                ->with('warning', 'Tu paquete ha expirado. Contrata uno nuevo para continuar.');
        }

        return $next($request);
    }
}