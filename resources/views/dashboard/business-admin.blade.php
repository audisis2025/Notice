{{--
/**
 * Nombre de la vista           : business-admin.blade.php
 * Descripción de la vista      : Panel principal del Administrador de Negocio con estadísticas
 *                                de órdenes y rendimiento (sin botones de acción)
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.1
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 5
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Eliminados botones de acción, info en sidebar
 * Responsable                  : Sistema
 * Revisor                      : Jesús Núñez
 */
--}}

<x-layouts.app.sidebar :title="__('Dashboard')">

    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#000000',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    {{-- Si NO tiene negocio --}}
    @if(!isset($business) || !$business)
    <div class="max-w-6xl mx-auto p-6">
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5 mb-6">
            <flux:heading size="sm" class="text-black dark:text-white">
                Configura tu negocio
            </flux:heading>

            <p class="text-sm text-black/70 dark:text-white/70 mt-1">
                Aún no has registrado tu negocio. Para comenzar a operar y ver estadísticas,
                primero crea tu negocio.
            </p>

            <div class="mt-3 flex justify-end"> 
                <flux:button 
                    icon="plus" 
                    icon-variant="outline" 
                    :href="route('business.create')" 
                    variant="primary" 
                    class="bg-green-600 hover:bg-green-700 text-white text-sm"
                >
                    Crear negocio
                </flux:button>
            </div>
        </div>
    </div>
    @endif

    {{-- Si tiene negocio --}}
    @if(isset($business) && $business)
    <div class="max-w-6xl mx-auto p-6 space-y-8">

        {{-- Título del Dashboard --}}
        <div>
            <h1 class="text-3xl font-bold text-black dark:text-white">Panel de Control</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Resumen de tu negocio <span class="font-semibold">{{ $business->business_name }}</span>
            </p>
        </div>

        {{-- Estadísticas de órdenes --}}
        <div class="grid md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Total Órdenes
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-green-600">
                    {{ $stats['total_orders'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Órdenes Pendientes
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-yellow-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-yellow-600">
                    {{ $stats['pending_orders'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Órdenes Completadas
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-blue-600">
                    {{ $stats['completed_orders'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Calificación Promedio
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M11.48 3.499a.562.562 0 011.04 0l2.125 5.111a.563.563 0 00.475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 00-.182.557l1.285 5.385a.562.562 0 01-.84.61l-4.725-2.885a.563.563 0 00-.586 0L6.982 20.54a.562.562 0 01-.84-.61l1.285-5.386a.562.562 0 00-.182-.557l-4.204-3.602a.563.563 0 01.321-.988l5.518-.442a.563.563 0 00.475-.345L11.48 3.5z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-purple-600">
                    {{ number_format($stats['average_rating'] ?? 0, 1) }}
                    <span class="text-base text-gray-500">/ 5</span>
                </div>
            </div>
        </div>

        {{-- Gráficas --}}
        <div class="grid md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Órdenes por Estado
                </flux:heading>

                <div style="height:220px">
                    <canvas id="chartOrdersByStatus"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Órdenes por Mes
                </flux:heading>

                <div style="height:220px">
                    <canvas id="chartOrdersByMonth"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Ingresos Mensuales
                </flux:heading>

                <div style="height:220px">
                    <canvas id="chartRevenue"></canvas>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

        <script>
            function renderDashboardCharts() 
            {
                const dataOrdersByStatus = @json($chartData['orders_by_status'] ?? []);
                const dataOrdersByMonth = @json($chartData['orders_by_month'] ?? []);
                const dataRevenue = @json($chartData['revenue'] ?? []);

                const L = (arr) => arr.map(i => i.label || i.name || '');
                const V = (arr) => arr.map(i => Number(i.total || i.value || i.count || 0));

                const baseOptions = 
                {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: 
                    {
                        legend: 
                        { 
                            display: false 
                        },
                        tooltip: 
                        { 
                            enabled: true 
                        }
                    },
                    scales: 
                    {
                        x: 
                        {
                            ticks: 
                            {
                                maxRotation: 0,
                                autoSkip: true
                            }
                        },
                        y: 
                        {
                            beginAtZero: true,
                            ticks: 
                            { 
                                precision: 0 
                            }
                        }
                    }
                };

                const barDataset = (values, color) => (
                {
                    data: values,
                    maxBarThickness: 28,
                    barPercentage: 0.9,
                    categoryPercentage: 0.9,
                    backgroundColor: color
                });

                if (window.dashboardCharts) 
                {
                    window.dashboardCharts.forEach(c => c.destroy());
                }
                window.dashboardCharts = [];

                const ctx1 = document.getElementById('chartOrdersByStatus');
                const ctx2 = document.getElementById('chartOrdersByMonth');
                const ctx3 = document.getElementById('chartRevenue');

                if (ctx1) 
                {
                    window.dashboardCharts.push(new Chart(ctx1, 
                    {
                        type: 'bar',
                        data: 
                        {
                            labels: L(dataOrdersByStatus),
                            datasets: [
                            {
                                ...barDataset(V(dataOrdersByStatus), '#10b981')
                            }]
                        },
                        options: baseOptions
                    }));
                }

                if (ctx2) 
                {
                    window.dashboardCharts.push(new Chart(ctx2, 
                    {
                        type: 'bar',
                        data: 
                        {
                            labels: L(dataOrdersByMonth),
                            datasets: [
                            {
                                ...barDataset(V(dataOrdersByMonth), '#3b82f6')
                            }]
                        },
                        options: baseOptions
                    }));
                }

                if (ctx3) 
                {
                    window.dashboardCharts.push(new Chart(ctx3, 
                    {
                        type: 'bar',
                        data: 
                        {
                            labels: L(dataRevenue),
                            datasets: [
                            {
                                ...barDataset(V(dataRevenue), '#a855f7')
                            }]
                        },
                        options: baseOptions
                    }));
                }
            }

            document.addEventListener('DOMContentLoaded', renderDashboardCharts);
            document.addEventListener('livewire:navigated', renderDashboardCharts);
        </script>
    @endpush
    @endif
</x-layouts.app.sidebar>