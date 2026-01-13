{{--
/**
 * Nombre de la vista           : business-admin.blade.php
 * Descripción de la vista      : Panel principal del Administrador de Negocio con estadísticas
 *                                de órdenes y rendimiento
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 13/01/2026
 * Folio de mantenimiento       : 4
 * Tipo de mantenimiento        : Correctivo y Perfectivo
 * Descripción del mantenimiento: Corrección de variable undefined y mejora visual
 *                                del dashboard para coincider con estándar
 * Responsable                  : Jesús Núñez
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

        {{-- Tarjeta de acciones principales --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading size="sm" class="text-black dark:text-white">
                Gestiona tu negocio
            </flux:heading>

            <p class="text-sm text-black/70 dark:text-white/70 mt-1">
                Administra tus órdenes, revisa estadísticas y genera reportes de tu negocio.
            </p>

            <div class="mt-3 flex flex-wrap gap-2 justify-end"> 
                <flux:button 
                    icon="cube" 
                    icon-variant="outline" 
                    :href="route('packages.available')" 
                    variant="primary" 
                    class="bg-purple-600 hover:bg-purple-700 text-white text-sm"
                >
                    Ver Paquetes
                </flux:button>

                <flux:button 
                    icon="shopping-bag" 
                    icon-variant="outline" 
                    :href="route('orders.index')" 
                    variant="primary" 
                    class="bg-gray-600 hover:bg-gray-700 text-white text-sm"
                >
                    Ver Órdenes
                </flux:button>

                <flux:button 
                    icon="chart-bar" 
                    icon-variant="outline" 
                    :href="route('reports.index')" 
                    variant="primary" 
                    class="bg-gray-500 hover:bg-gray-600 text-white text-sm"
                >
                    Ver Reportes
                </flux:button>
            </div>
        </div>

        {{-- Estadísticas de órdenes --}}
        <div class="grid md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Total Órdenes
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-green-600">
                    {{ $stats['total_orders'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Órdenes Pendientes
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-yellow-600">
                    {{ $stats['pending_orders'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Órdenes Completadas
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-blue-600">
                    {{ $stats['completed_orders'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Calificación Promedio
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-purple-600">
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