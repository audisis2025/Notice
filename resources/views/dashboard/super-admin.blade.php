{{--
/**
 * Nombre de la vista           : super-admin.blade.php
 * Descripción de la vista      : Panel principal del SuperAdministrador con estadísticas
 *                                generales del sistema
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 13/01/2026
 * Folio de mantenimiento       : 3
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Mejora visual del dashboard para coincidir con estándar
 *                                de diseño del sistema
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

    <div class="max-w-6xl mx-auto p-6 space-y-8">

        {{-- Tarjeta de bienvenida --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 p-5">
            <flux:heading size="sm" class="text-black dark:text-white">
                Gestión del Sistema SISNOTICE
            </flux:heading>

            <p class="text-sm text-black/70 dark:text-white/70 mt-1">
                Panel de control para administrar usuarios, negocios, paquetes y cupones del sistema.
            </p>

            <div class="mt-3 flex flex-wrap gap-2 justify-end"> 
                <flux:button 
                    icon="users" 
                    icon-variant="outline" 
                    :href="route('users.index')" 
                    variant="primary" 
                    class="bg-black hover:bg-gray-900 text-white text-sm"
                >
                    Gestionar Usuarios
                </flux:button>

                <flux:button 
                    icon="building-storefront" 
                    icon-variant="outline" 
                    :href="route('businesses.index')" 
                    variant="primary" 
                    class="bg-gray-600 hover:bg-gray-700 text-white text-sm"
                >
                    Ver Negocios
                </flux:button>

                <flux:button 
                    icon="cube" 
                    icon-variant="outline" 
                    :href="route('packages.index')" 
                    variant="primary" 
                    class="bg-gray-500 hover:bg-gray-600 text-white text-sm"
                >
                    Gestionar Paquetes
                </flux:button>

                <flux:button 
                    icon="ticket" 
                    icon-variant="outline" 
                    :href="route('coupons.index')" 
                    variant="primary" 
                    class="bg-purple-600 hover:bg-purple-700 text-white text-sm"
                >
                    Gestionar Cupones
                </flux:button>
            </div>
        </div>

        {{-- Estadísticas principales --}}
        <div class="grid md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Total Usuarios
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-green-600">
                    {{ $stats['total_users'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Total Negocios
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-blue-600">
                    {{ $stats['total_businesses'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Negocios Activos
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-green-500">
                    {{ $stats['active_businesses'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Cupones Disponibles
                </flux:heading>

                <div class="text-3xl font-bold mt-1 text-purple-600">
                    {{ $stats['available_coupons'] ?? 0 }}
                </div>
            </div>
        </div>

        {{-- Gráficas --}}
        <div class="grid md:grid-cols-3 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Usuarios por Rol
                </flux:heading>

                <div style="height:220px">
                    <canvas id="chartUsersByRole"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Negocios Registrados
                </flux:heading>

                <div style="height:220px">
                    <canvas id="chartBusinesses"></canvas>
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <flux:heading size="sm" class="mb-2 text-black dark:text-white">
                    Paquetes Contratados
                </flux:heading>

                <div style="height:220px">
                    <canvas id="chartPackages"></canvas>
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
                const dataUsersByRole = @json($chartData['users_by_role'] ?? []);
                const dataBusinesses = @json($chartData['businesses'] ?? []);
                const dataPackages = @json($chartData['packages'] ?? []);

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

                const ctx1 = document.getElementById('chartUsersByRole');
                const ctx2 = document.getElementById('chartBusinesses');
                const ctx3 = document.getElementById('chartPackages');

                if (ctx1) 
                {
                    window.dashboardCharts.push(new Chart(ctx1, 
                    {
                        type: 'bar',
                        data: 
                        {
                            labels: L(dataUsersByRole),
                            datasets: [
                            {
                                ...barDataset(V(dataUsersByRole), '#10b981')
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
                            labels: L(dataBusinesses),
                            datasets: [
                            {
                                ...barDataset(V(dataBusinesses), '#3b82f6')
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
                            labels: L(dataPackages),
                            datasets: [
                            {
                                ...barDataset(V(dataPackages), '#a855f7')
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
</x-layouts.app.sidebar>