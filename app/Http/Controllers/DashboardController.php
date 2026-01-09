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
        $user = Auth::user();
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