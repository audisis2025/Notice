<?php

/**
 * Nombre de la clase           : DashboardController
 * Descripción de la clase      : Controlador que gestiona dashboards sin Services
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Order;
use App\Models\User;
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
     * Muestra el dashboard según el rol del usuario autenticado.
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
     * Dashboard para SuperAdministrador.
     */
    protected function superAdminDashboard()
    {
        // Generar estadísticas globales directamente
        $statistics = [
            'total_businesses' => Business::count(),
            'active_businesses' => Business::active()->count(),
            'total_orders' => Order::count(),
            'total_revenue' => Order::sum('amount'),
            'orders_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
            'revenue_this_month' => Order::whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->sum('amount'),
        ];

        return view('dashboard.super-admin', [
            'statistics' => $statistics,
        ]);
    }

    /**
     * Dashboard para Administrador de Negocio.
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
            'completed_orders' => $business->orders()->where('status', 'delivered')->count(),
            'average_rating' => $business->ratings()->avg('stars') ?? 0,
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
     * Obtener órdenes por estado.
     */
    private function getOrdersByStatus($business)
    {
        return [
            ['label' => 'Pendientes', 'count' => $business->orders()->where('status', 'pending')->count()],
            ['label' => 'Pagadas', 'count' => $business->orders()->where('status', 'paid')->count()],
            ['label' => 'Listas', 'count' => $business->orders()->where('status', 'ready')->count()],
            ['label' => 'Entregadas', 'count' => $business->orders()->where('status', 'delivered')->count()],
            ['label' => 'Canceladas', 'count' => $business->orders()->where('status', 'cancelled')->count()],
        ];
    }

    /**
     * Obtener órdenes por mes.
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
     * Obtener ingresos por mes.
     */
    private function getRevenueByMonth($business)
    {
        $months = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            
            $revenue = $business->orders()
                ->whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->where('status', 'delivered')
                ->sum('amount');

            $months[] = [
                'label' => $date->format('M Y'),
                'value' => $revenue
            ];
        }
        return $months;
    }
}
