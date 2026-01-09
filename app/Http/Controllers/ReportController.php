<?php

namespace App\Http\Controllers;

use App\Services\ReportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Controller;

/**
 * ReportController
 * 
 * Controlador para generar reportes y estadísticas.
 *
 * @package App\Http\Controllers
 */
class ReportController extends Controller
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
        $this->middleware('auth');
        $this->reportService = $reportService;
    }

    /**
     * Muestra el formulario de generación de reportes.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $business = Auth::user()->business;

        return view('reports.index', compact('business'));
    }

    /**
     * Genera un reporte de órdenes.
     *
     * @param Request $request
     * @return \Illuminate\View\View
     */
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $business = Auth::user()->business;
            $report = $this->reportService->generateOrdersReport(
                $business,
                $request->start_date,
                $request->end_date
            );

            return view('reports.show', compact('report', 'business'));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al generar reporte: ' . $e->getMessage());
        }
    }

    /**
     * Exporta un reporte a CSV.
     *
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\StreamedResponse
     */
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $business = Auth::user()->business;
            $report = $this->reportService->generateOrdersReport(
                $business,
                $request->start_date,
                $request->end_date
            );

            $filename = "reporte_{$business->business_name}_" . now()->format('Y-m-d') . ".csv";
            $path = $this->reportService->exportToCSV($report, $filename);

            return response()->download(storage_path("app/public/{$path}"));
        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar reporte: ' . $e->getMessage());
        }
    }
}