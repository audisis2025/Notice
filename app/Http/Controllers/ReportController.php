<?php

/**
 * Nombre de la clase           : ReportController
 * Descripción de la clase      : Controlador que gestiona reportes sin Services
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Tipo de mantenimiento        : Perfectivo
 */

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

/**
 * ReportController
 * 
 * Controlador para generar reportes y estadísticas.
 *
 * @package App\Http\Controllers
 */
class ReportController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Muestra el formulario de generación de reportes.
     */
    public function index()
    {
        $business = Auth::user()->business;

        return view('reports.index', compact('business'));
    }

    /**
     * Genera un reporte de órdenes.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $business = Auth::user()->business;
            
            // Generar reporte directamente en el controlador
            $report = $this->generateOrdersReport(
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
     */
    public function export(Request $request)
    {
        $request->validate([
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        try {
            $business = Auth::user()->business;
            
            $report = $this->generateOrdersReport(
                $business,
                $request->start_date,
                $request->end_date
            );

            $filename = "reporte_{$business->business_name}_" . now()->format('Y-m-d') . ".csv";
            $path = $this->exportToCSV($report, $filename);

            return response()->download(storage_path("app/public/{$path}"));

        } catch (\Exception $e) {
            return back()->with('error', 'Error al exportar reporte: ' . $e->getMessage());
        }
    }

    // ====================================================================
    // MÉTODOS PRIVADOS DE LÓGICA (Anteriormente en ReportService)
    // ====================================================================

    /**
     * Genera reporte de órdenes para un negocio.
     */
    private function generateOrdersReport($business, string $startDate, string $endDate): array
    {
        $start = Carbon::parse($startDate)->startOfDay();
        $end = Carbon::parse($endDate)->endOfDay();

        $orders = Order::where('business_id', $business->id)
            ->whereBetween('created_at', [$start, $end])
            ->get();

        $totalOrders = $orders->count();
        $totalRevenue = $orders->sum('amount');

        $statusDistribution = [
            'pending' => $orders->where('status', 'pending')->count(),
            'paid' => $orders->where('status', 'paid')->count(),
            'ready' => $orders->where('status', 'ready')->count(),
            'delivered' => $orders->where('status', 'delivered')->count(),
            'cancelled' => $orders->where('status', 'cancelled')->count(),
        ];

        $averageAmount = $totalOrders > 0 ? $totalRevenue / $totalOrders : 0;

        return [
            'period' => [
                'start' => $start->format('Y-m-d'),
                'end' => $end->format('Y-m-d'),
            ],
            'total_orders' => $totalOrders,
            'total_revenue' => round($totalRevenue, 2),
            'average_amount' => round($averageAmount, 2),
            'status_distribution' => $statusDistribution,
            'orders' => $orders,
        ];
    }

    /**
     * Exporta reporte a CSV.
     */
    private function exportToCSV(array $reportData, string $filename): string
    {
        $csv = fopen('php://temp', 'w');

        // Encabezados
        fputcsv($csv, ['Número de Orden', 'Fecha', 'Monto', 'Estado']);

        // Datos
        foreach ($reportData['orders'] as $order) {
            fputcsv($csv, [
                $order->order_number,
                $order->created_at->format('Y-m-d H:i:s'),
                $order->amount,
                $order->status,
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        $path = "reports/{$filename}";
        Storage::disk('public')->put($path, $content);

        return $path;
    }
}
