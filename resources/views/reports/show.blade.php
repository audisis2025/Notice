{{--
/**
 * Nombre de la vista           : show.blade.php
 * Descripción de la vista      : Muestra los resultados del reporte generado
 *                                Con el mismo estilo de la aplicación
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Sistema
 * Versión                      : 1.0
 */
--}}
<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="p-6">
        {{-- Encabezado --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black dark:text-white">
                    {{ __('Reporte de Órdenes') }}
                </h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Del {{ $report['period']['start'] }} al {{ $report['period']['end'] }}
                </p>
            </div>
            <div class="flex space-x-3">
                <a href="{{ route('reports.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-5 h-5 mr-2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18" />
                    </svg>
                    Nuevo Reporte
                </a>
                <form action="{{ route('reports.export') }}" method="GET" class="inline">
                    <input type="hidden" name="start_date" value="{{ $report['period']['start'] }}">
                    <input type="hidden" name="end_date" value="{{ $report['period']['end'] }}">
                    <input type="hidden" name="format" value="csv">
                    <button type="submit"
                        class="inline-flex items-center px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3" />
                        </svg>
                        Exportar CSV
                    </button>
                </form>
            </div>
        </div>

        {{-- Estadísticas del reporte --}}
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-1">Total Órdenes</p>
                        <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $report['total_orders'] }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <div
                class="bg-green-50 dark:bg-green-900/20 rounded-lg shadow border border-green-200 dark:border-green-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-700 dark:text-green-300 uppercase mb-1">Ingresos Totales</p>
                        <p class="text-3xl font-bold text-green-900 dark:text-green-100">
                            ${{ number_format($report['total_revenue'], 2) }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10 text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 18.75a60.07 60.07 0 0115.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 013 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125H20.25M2.25 6v9m18-10.5v.75c0 .414.336.75.75.75h.75m-1.5-1.5h.375c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125h-.375m1.5-1.5H21a.75.75 0 00-.75.75v.75m0 0H3.75m0 0h-.375a1.125 1.125 0 01-1.125-1.125V15m1.5 1.5v-.75A.75.75 0 003 15h-.75M15 10.5a3 3 0 11-6 0 3 3 0 016 0zm3 0h.008v.008H18V10.5zm-12 0h.008v.008H6V10.5z" />
                    </svg>
                </div>
            </div>

            <div
                class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow border border-blue-200 dark:border-blue-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 uppercase mb-1">Promedio por Orden</p>
                        <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">
                            ${{ number_format($report['average_amount'], 2) }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                    </svg>
                </div>
            </div>

            <div
                class="bg-purple-50 dark:bg-purple-900/20 rounded-lg shadow border border-purple-200 dark:border-purple-800 p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-purple-700 dark:text-purple-300 uppercase mb-1">Entregadas</p>
                        <p class="text-3xl font-bold text-purple-900 dark:text-purple-100">
                            {{ $report['status_distribution']['delivered'] }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-10 h-10 text-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Distribución por estado --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 p-6 mb-6">
            <h2 class="text-xl font-bold text-black dark:text-white mb-4">Distribución por Estado</h2>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-4">
                <div
                    class="text-center p-4 bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800">
                    <p class="text-3xl font-bold text-yellow-900 dark:text-yellow-100">
                        {{ $report['status_distribution']['pending'] }}</p>
                    <p class="text-sm text-yellow-700 dark:text-yellow-300 mt-1">Pendientes</p>
                </div>
                <div
                    class="text-center p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg border border-blue-200 dark:border-blue-800">
                    <p class="text-3xl font-bold text-blue-900 dark:text-blue-100">
                        {{ $report['status_distribution']['paid'] }}</p>
                    <p class="text-sm text-blue-700 dark:text-blue-300 mt-1">Pagadas</p>
                </div>
                <div
                    class="text-center p-4 bg-orange-50 dark:bg-orange-900/20 rounded-lg border border-orange-200 dark:border-orange-800">
                    <p class="text-3xl font-bold text-orange-900 dark:text-orange-100">
                        {{ $report['status_distribution']['ready'] }}</p>
                    <p class="text-sm text-orange-700 dark:text-orange-300 mt-1">Listas</p>
                </div>
                <div
                    class="text-center p-4 bg-green-50 dark:bg-green-900/20 rounded-lg border border-green-200 dark:border-green-800">
                    <p class="text-3xl font-bold text-green-900 dark:text-green-100">
                        {{ $report['status_distribution']['delivered'] }}</p>
                    <p class="text-sm text-green-700 dark:text-green-300 mt-1">Entregadas</p>
                </div>
                <div
                    class="text-center p-4 bg-red-50 dark:bg-red-900/20 rounded-lg border border-red-200 dark:border-red-800">
                    <p class="text-3xl font-bold text-red-900 dark:text-red-100">
                        {{ $report['status_distribution']['cancelled'] }}</p>
                    <p class="text-sm text-red-700 dark:text-red-300 mt-1">Canceladas</p>
                </div>
            </div>
        </div>

        {{-- Tabla de órdenes --}}
        <div
            class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200 dark:border-zinc-700">
                <h2 class="text-xl font-bold text-black dark:text-white">Detalle de Órdenes</h2>
            </div>

            @if ($report['orders']->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Número
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th
                                    class="px-6 py-3 text-right text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Monto
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach ($report['orders'] as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $order->order_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' =>
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                                'paid' =>
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                                'ready' =>
                                                    'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
                                                'delivered' =>
                                                    'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                                'cancelled' =>
                                                    'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                            ];
                                            $statusText = [
                                                'pending' => 'Pendiente',
                                                'paid' => 'Pagada',
                                                'ready' => 'Lista',
                                                'delivered' => 'Entregada',
                                                'cancelled' => 'Cancelada',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] }}">
                                            {{ $statusText[$order->status] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right">
                                        <span class="text-sm font-medium text-gray-900 dark:text-white">
                                            ${{ number_format($order->amount ?? 0, 2) }}
                                        </span>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="px-6 py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-12 h-12 mx-auto mb-2 text-gray-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">
                        No se encontraron órdenes en el período seleccionado
                    </p>
                </div>
            @endif
        </div>
    </div>

    @push('scripts')
        <script>
            @if (session('success'))
                showSuccess('{{ session('success') }}');
            @endif

            @if (session('error'))
                showError('{{ session('error') }}');
            @endif
        </script>
    @endpush
</x-layouts.app.sidebar>
