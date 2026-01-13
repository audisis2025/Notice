<?php

/**
 * Nombre de la clase           : DashboardController
 * Descripción de la clase      : Controlador que gestiona la lógica del dashboard
 *                                redirigiendo según el rol del usuario autenticado
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

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * DashboardController
 * 
 * Controlador para gestionar los dashboards según rol de usuario.
 *
 * @package App\Http\Controllers
 */
class DashboardController extends Controller
{
    /**
     * Instancia del servicio de reportes.
     *
     * @var ReportService
     */
    protected ReportService $reportService;

    /**
     * Constructor del controlador.
     *
     * @param ReportService $reportService
     */
    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    /**
     * Muestra el dashboard según el rol del usuario autenticado.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        /** @var User $user */
        $user = Auth::user();

        if ($user->isSuperAdministrator()) {
            return $this->superAdminDashboard();
        } elseif ($user->isBusinessAdministrator()) {
            return $this->businessAdminDashboard();
        } else {
            abort(403, 'Acceso no autorizado');
        }
    }

    /**
     * Dashboard para Administrador de Negocio.
     *
     * @return \Illuminate\View\View
     */
    protected function businessAdminDashboard()
    {
        $user = Auth::user();
        $business = $user->business;

        // Si no tiene negocio, mostrar mensaje
        if (!$business) {
            return view('dashboard.business-admin', [
                'business' => null,
                'stats' => [],
                'chartData' => [],
            ]);
        }

        // Obtener estadísticas de órdenes
        $stats = [
            'total_orders' => $business->orders()->count(),
            'pending_orders' => $business->orders()->where('status', 'pending')->count(),
            'completed_orders' => $business->orders()->where('status', 'completed')->count(),
            'average_rating' => $business->ratings()->avg('rating') ?? 0,
        ];

        // Datos para gráficas
        $chartData = [
            'orders_by_status' => $this->getOrdersByStatus($business),
            'orders_by_month' => $this->getOrdersByMonth($business),
            'revenue' => $this->getRevenueByMonth($business),
        ];

        return view('dashboard.business-admin', [
            'business' => $business,
            'stats' => $stats,
            'chartData' => $chartData,
        ]);
    }

    /**
     * Obtener órdenes por estado
     */
    private function getOrdersByStatus($business)
    {
        return [
            ['label' => 'Pendientes', 'count' => $business->orders()->where('status', 'pending')->count()],
            ['label' => 'En Proceso', 'count' => $business->orders()->where('status', 'processing')->count()],
            ['label' => 'Completadas', 'count' => $business->orders()->where('status', 'completed')->count()],
            ['label' => 'Canceladas', 'count' => $business->orders()->where('status', 'cancelled')->count()],
        ];
    }

    /**
     * Obtener órdenes por mes
     */
    private function getOrdersByMonth($business)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = $business->orders()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();

            $months[] = [
                'label' => $date->format('M Y'),
                'count' => $count
            ];
        }
        return $months;
    }

    /**
     * Obtener ingresos por mes
     */
    private function getRevenueByMonth($business)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $revenue = $business->orders()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'completed')
                ->sum('total');

            $months[] = [
                'label' => $date->format('M Y'),
                'value' => $revenue
            ];
        }
        return $months;
    }
}
