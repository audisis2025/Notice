<?php

namespace App\Services;

use App\Models\Business;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * ReportService
 * 
 * Servicio para generar reportes y estadísticas.
 *
 * @package App\Services
 */
class ReportService
{
    /**
     * Genera reporte de órdenes para un negocio.
     *
     * @param Business $business Negocio
     * @param string $startDate Fecha inicio
     * @param string $endDate Fecha fin
     * @return array
     */
    public function generateOrdersReport(Business $business, string $startDate, string $endDate): array
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
     * Genera estadísticas globales para SuperAdministrador.
     *
     * @return array
     */
    public function generateGlobalStatistics(): array
    {
        $totalBusinesses = Business::count();
        $activeBusinesses = Business::active()->count();
        $totalOrders = Order::count();
        $totalRevenue = Order::sum('amount');
        
        $ordersThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->count();
        
        $revenueThisMonth = Order::whereMonth('created_at', now()->month)
            ->whereYear('created_at', now()->year)
            ->sum('amount');
        
        return [
            'total_businesses' => $totalBusinesses,
            'active_businesses' => $activeBusinesses,
            'total_orders' => $totalOrders,
            'total_revenue' => round($totalRevenue, 2),
            'orders_this_month' => $ordersThisMonth,
            'revenue_this_month' => round($revenueThisMonth, 2),
        ];
    }

    /**
     * Exporta reporte a CSV.
     *
     * @param array $reportData Datos del reporte
     * @param string $filename Nombre del archivo
     * @return string Path del archivo
     */
    public function exportToCSV(array $reportData, string $filename): string
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