<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\ActivityLog;

/**
 * LogActivity
 * 
 * Middleware para registrar actividades importantes del usuario.
 *
 * @package App\Http\Middleware
 */
class LogActivity
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
        $response = $next($request);

        // Registrar solo ciertas rutas o métodos
        if (auth()->check() && in_array($request->method(), ['POST', 'PUT', 'DELETE', 'PATCH'])) {
            ActivityLog::create([
                'log_name' => 'user_actions',
                'description' => $this->getActionDescription($request),
                'causer_type' => get_class(auth()->user()),
                'causer_id' => auth()->id(),
                'properties' => json_encode([
                    'url' => $request->fullUrl(),
                    'method' => $request->method(),
                    'ip' => $request->ip(),
                    'user_agent' => $request->userAgent(),
                ]),
                'created_at' => now(),
            ]);
        }

        return $response;
    }

    /**
     * Genera una descripción de la acción realizada.
     *
     * @param \Illuminate\Http\Request $request
     * @return string
     */
    protected function getActionDescription(Request $request): string
    {
        $method = $request->method();
        $route = $request->route()?->getName() ?? $request->path();

        return match($method) {
            'POST' => "Creó un registro en: {$route}",
            'PUT', 'PATCH' => "Actualizó un registro en: {$route}",
            'DELETE' => "Eliminó un registro en: {$route}",
            default => "Acción en: {$route}",
        };
    }
}