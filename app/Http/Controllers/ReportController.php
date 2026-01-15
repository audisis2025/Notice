<?php

/**
 * Nombre de la clase           : ReportController
 * Descripción de la clase      : Controlador que gestiona reportes sin Services
 * Versión                      : 2.2
 * Fecha de mantenimiento       : 15/01/2026
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Mejora de mensajes SweetAlert sin modificar lógica
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
    /**
     * Muestra el formulario de generación de reportes.
     */
    public function index()
    {
        $business = Auth::user()->business;

        if (!$business) {
            return redirect()->route('business.create')
                ->with('error', 'Primero debes registrar tu negocio.');
        }

        // Estadísticas rápidas para la vista
        $totalOrders = Order::where('business_id', $business->id)->count();
        $currentMonthOrders = Order::where('business_id', $business->id)
            ->whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $totalRevenue = Order::where('business_id', $business->id)
            ->whereNotNull('amount')
            ->sum('amount');

        return view('reports.index', compact('business', 'totalOrders', 'currentMonthOrders', 'totalRevenue'));
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

            if (!$business) {
                return redirect()->route('business.create')
                    ->with('error', 'Primero debes registrar tu negocio.');
            }
            
            // Generar reporte directamente en el controlador
            $report = $this->generateOrdersReport(
                $business,
                $request->start_date,
                $request->end_date
            );

            return view('reports.show', compact('report', 'business'));

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al generar reporte: ' . $e->getMessage());
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

            if (!$business) {
                return redirect()->route('business.create')
                    ->with('error', 'Primero debes registrar tu negocio.');
            }
            
            $report = $this->generateOrdersReport(
                $business,
                $request->start_date,
                $request->end_date
            );

            $filename = "reporte_{$business->business_name}_" . now()->format('Y-m-d') . ".csv";
            $path = $this->exportToCSV($report, $filename);

            return response()->download(storage_path("app/public/{$path}"));

        } catch (\Exception $e) {
            return back()
                ->with('error', 'Error al exportar reporte: ' . $e->getMessage());
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

        // UTF-8 BOM para Excel
        fprintf($csv, chr(0xEF).chr(0xBB).chr(0xBF));

        // Encabezados
        fputcsv($csv, ['Número de Orden', 'Fecha', 'Monto', 'Estado']);

        // Datos
        foreach ($reportData['orders'] as $order) {
            fputcsv($csv, [
                $order->order_number,
                $order->created_at->format('Y-m-d H:i:s'),
                $order->amount ?? 0,
                ucfirst($order->status),
            ]);
        }

        rewind($csv);
        $content = stream_get_contents($csv);
        fclose($csv);

        // Crear directorio si no existe
        $directory = storage_path('app/public/reports');
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = "reports/{$filename}";
        Storage::disk('public')->put($path, $content);

        return $path;
    }
}