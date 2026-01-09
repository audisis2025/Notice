<x-app-layout>
    @section('page-title', 'Reporte de Órdenes')

    <div class="max-w-7xl mx-auto">
        {{-- Encabezado --}}
        <div class="flex justify-between items-center mb-6">
            <div>
                <h2 class="text-3xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-chart-bar class="w-8 h-8 mr-3" />
                    Reporte de Órdenes
                </h2>
                <p class="text-gray-600 mt-1">
                    Del {{ $startDate->format('d/m/Y') }} al {{ $endDate->format('d/m/Y') }}
                </p>
            </div>

            <div class="flex space-x-3">
                <flux:button 
                    variant="ghost"
                    href="{{ route('reports.index') }}"
                >
                    <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                    Nuevo Reporte
                </flux:button>

                <flux:button 
                    variant="success"
                    href="{{ route('reports.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}"
                >
                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                    Exportar CSV
                </flux:button>
            </div>
        </div>

        {{-- Resumen general --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500 uppercase">Total Órdenes</p>
                        <p class="text-3xl font-bold text-gray-900 mt-2">{{ $summary['total_orders'] }}</p>
                    </div>
                    <x-heroicon-o-shopping-bag class="w-12 h-12 text-gray-400" />
                </div>
            </div>

            <div class="bg-green-50 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-green-700 uppercase">Ingresos Totales</p>
                        <p class="text-3xl font-bold text-green-900 mt-2">${{ number_format($summary['total_revenue'], 2) }}</p>
                    </div>
                    <x-heroicon-o-currency-dollar class="w-12 h-12 text-green-500" />
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-blue-700 uppercase">Promedio por Orden</p>
                        <p class="text-3xl font-bold text-blue-900 mt-2">${{ number_format($summary['average_order'], 2) }}</p>
                    </div>
                    <x-heroicon-o-chart-bar class="w-12 h-12 text-blue-500" />
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-yellow-700 uppercase">Tasa Completadas</p>
                        <p class="text-3xl font-bold text-yellow-900 mt-2">{{ number_format($summary['completion_rate'], 1) }}%</p>
                    </div>
                    <x-heroicon-o-check-circle class="w-12 h-12 text-yellow-500" />
                </div>
            </div>
        </div>

        {{-- Distribución por estado --}}
        <div class="bg-white rounded-lg shadow mb-8 p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-6">Distribución por Estado</h3>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                @foreach($summary['by_status'] as $status => $count)
                    <div class="text-center p-4 rounded-lg @if($status === 'delivered') bg-green-50 @elseif($status === 'cancelled') bg-red-50 @else bg-gray-50 @endif">
                        <p class="text-sm text-gray-600 uppercase mb-2">{{ ucfirst($status) }}</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $count }}</p>
                        <p class="text-xs text-gray-500 mt-1">
                            {{ $summary['total_orders'] > 0 ? number_format(($count / $summary['total_orders']) * 100, 1) : 0 }}%
                        </p>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Tabla detallada --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Detalle de Órdenes</h3>
            </div>

            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Número</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm font-semibold">{{ $order->order_number }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($order->user)
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                            <p class="text-xs text-gray-500">{{ $order->user->phone }}</p>
                                        </div>
                                    @else
                                        <span class="text-gray-400">Sin asociar</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-lg font-bold text-gray-900">${{ number_format($order->amount, 2) }}</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <x-badge-status :status="$order->status" />
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-6 py-12 text-center text-gray-500">
                                    No hay órdenes en el período seleccionado
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($orders->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
