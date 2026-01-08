<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;

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
        $user = auth()->user();

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
     * @return \Illuminate\View\View
     */
    protected function superAdminDashboard()
    {
        $statistics = $this->reportService->generateGlobalStatistics();

        return view('dashboard.super-admin', [
            'statistics' => $statistics,
        ]);
    }

    /**
     * Dashboard para Administrador de Negocio.
     *
     * @return \Illuminate\View\View
     */
    protected function businessAdminDashboard()
    {
        $user = auth()->user();
        $business = $user->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('info', 'Primero debes registrar tu negocio.');
        }

        $recentOrders = $business->orders()
            ->latest()
            ->take(10)
            ->get();

        $activePackage = $business->activePackage;

        return view('dashboard.business-admin', [
            'business' => $business,
            'recentOrders' => $recentOrders,
            'activePackage' => $activePackage,
        ]);
    }
}