<?php

/**
 * Nombre de la clase           : DashboardController
 * Descripción de la clase      : Controlador que gestiona dashboards sin Services
 * Versión                      : 2.1
 * Fecha de mantenimiento       : 15/01/2026
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Corrección de datos para dashboard super-admin
 *                                SIN afectar dashboard business-admin
 */

namespace App\Http\Controllers;

use App\Models\Business;
use App\Models\Order;
use App\Models\User;
use App\Models\Package;
use App\Models\Coupon;
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
     * 
     * ✅ MODIFICADO: Ahora envía $stats y $chartData en lugar de $statistics
     */
    protected function superAdminDashboard()
    {
        // Estadísticas principales (las que espera la vista)
        $stats = [
            'total_users' => User::count(),
            'total_businesses' => Business::count(),
            'active_businesses' => Business::where('is_active', true)->count(),
            'available_coupons' => Coupon::where('is_active', true)
                ->where('is_used', false)
                ->where('expiration_date', '>=', now())
                ->count(),
        ];

        // Datos para gráficas (las que espera la vista)
        $chartData = [
            'users_by_role' => $this->getUsersByRole(),
            'businesses' => $this->getBusinessesData(),
            'packages' => $this->getPackagesData(),
        ];

        return view('dashboard.super-admin', [
            'stats' => $stats,
            'chartData' => $chartData,
        ]);
    }

    /**
     * ✅ NUEVO: Obtener usuarios por rol para gráfica
     */
    private function getUsersByRole()
    {
        $roles = [
            'SuperAdministrator' => 'Super Admin',
            'BusinessAdministrator' => 'Admin Negocio',
            'MobileUser' => 'Usuario Móvil',
        ];

        $data = [];
        foreach ($roles as $roleKey => $roleLabel) {
            $count = User::where('role', $roleKey)->count();
            $data[] = [
                'label' => $roleLabel,
                'total' => $count,
            ];
        }

        return $data;
    }

    /**
     * ✅ NUEVO: Obtener datos de negocios para gráfica
     */
    private function getBusinessesData()
    {
        return [
            ['label' => 'Activos', 'total' => Business::where('is_active', true)->count()],
            ['label' => 'Inactivos', 'total' => Business::where('is_active', false)->count()],
        ];
    }

    /**
     * ✅ NUEVO: Obtener datos de paquetes contratados para gráfica
     */
    private function getPackagesData()
    {
        $packages = Package::withCount('businessPackages')->get();

        $data = [];
        foreach ($packages as $package) {
            $data[] = [
                'label' => $package->name,
                'total' => $package->business_packages_count ?? 0,
            ];
        }

        // Si no hay datos, mostrar mensaje por defecto
        if (empty($data)) {
            $data[] = [
                'label' => 'Sin paquetes',
                'total' => 0,
            ];
        }

        return $data;
    }

    /**
     * Dashboard para Administrador de Negocio.
     * 
     * ✅ SIN CAMBIOS: Se mantiene exactamente igual que antes
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
     * 
     * ✅ SIN CAMBIOS: Se mantiene exactamente igual que antes
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
     * 
     * ✅ SIN CAMBIOS: Se mantiene exactamente igual que antes
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
     * 
     * ✅ SIN CAMBIOS: Se mantiene exactamente igual que antes
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