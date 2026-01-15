{{--
/**
 * Nombre de la vista           : super-admin.blade.php
 * Descripción de la vista      : Panel principal del SuperAdministrador con estadísticas
 *                                generales del sistema
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.1
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 4
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Actualización del diseño para coincidir con estándar
 *                                del dashboard de negocios
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */
--}}

<x-layouts.app.sidebar :title="__('Dashboard')">
    <x-flash-messages />
    @if (session('success'))
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

    @if (session('error'))
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

        {{-- Título del Dashboard --}}
        <div>
            <h1 class="text-3xl font-bold text-black dark:text-white">Panel de Control</h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Gestión del Sistema SISNOTICE
            </p>
        </div>

        {{-- Estadísticas principales --}}
        <div class="grid md:grid-cols-4 gap-4">
            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Total Usuarios
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15 19.128a9.38 9.38 0 002.625.372 9.337 9.337 0 004.121-.952 4.125 4.125 0 00-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 018.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0111.964-3.07M12 6.375a3.375 3.375 0 11-6.75 0 3.375 3.375 0 016.75 0zm8.25 2.25a2.625 2.625 0 11-5.25 0 2.625 2.625 0 015.25 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-green-600">
                    {{ $stats['total_users'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Total Negocios
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-blue-600">
                    {{ $stats['total_businesses'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Negocios Activos
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-emerald-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-green-500">
                    {{ $stats['active_businesses'] ?? 0 }}
                </div>
            </div>

            <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 p-4 bg-white dark:bg-zinc-900">
                <div class="flex items-center justify-between mb-2">
                    <flux:heading size="sm" class="text-black dark:text-white">
                        Cupones Disponibles
                    </flux:heading>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-purple-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16.5 6v.75m0 3v.75m0 3v.75m0 3V18m-9-5.25h5.25M7.5 15h3M3.375 5.25c-.621 0-1.125.504-1.125 1.125v3.026a2.999 2.999 0 010 5.198v3.026c0 .621.504 1.125 1.125 1.125h17.25c.621 0 1.125-.504 1.125-1.125v-3.026a2.999 2.999 0 010-5.198V6.375c0-.621-.504-1.125-1.125-1.125H3.375z" />
                    </svg>
                </div>
                <div class="text-3xl font-bold text-purple-600">
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
            function renderDashboardCharts() {
                const dataUsersByRole = @json($chartData['users_by_role'] ?? []);
                const dataBusinesses = @json($chartData['businesses'] ?? []);
                const dataPackages = @json($chartData['packages'] ?? []);

                const L = (arr) => arr.map(i => i.label || i.name || '');
                const V = (arr) => arr.map(i => Number(i.total || i.value || i.count || 0));

                const baseOptions = {
                    responsive: true,
                    maintainAspectRatio: false,
                    animation: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: true
                        }
                    },
                    scales: {
                        x: {
                            ticks: {
                                maxRotation: 0,
                                autoSkip: true
                            }
                        },
                        y: {
                            beginAtZero: true,
                            ticks: {
                                precision: 0
                            }
                        }
                    }
                };

                const barDataset = (values, color) => ({
                    data: values,
                    maxBarThickness: 28,
                    barPercentage: 0.9,
                    categoryPercentage: 0.9,
                    backgroundColor: color
                });

                if (window.dashboardCharts) {
                    window.dashboardCharts.forEach(c => c.destroy());
                }
                window.dashboardCharts = [];

                const ctx1 = document.getElementById('chartUsersByRole');
                const ctx2 = document.getElementById('chartBusinesses');
                const ctx3 = document.getElementById('chartPackages');

                if (ctx1) {
                    window.dashboardCharts.push(new Chart(ctx1, {
                        type: 'bar',
                        data: {
                            labels: L(dataUsersByRole),
                            datasets: [{
                                ...barDataset(V(dataUsersByRole), '#10b981')
                            }]
                        },
                        options: baseOptions
                    }));
                }

                if (ctx2) {
                    window.dashboardCharts.push(new Chart(ctx2, {
                        type: 'bar',
                        data: {
                            labels: L(dataBusinesses),
                            datasets: [{
                                ...barDataset(V(dataBusinesses), '#3b82f6')
                            }]
                        },
                        options: baseOptions
                    }));
                }

                if (ctx3) {
                    window.dashboardCharts.push(new Chart(ctx3, {
                        type: 'bar',
                        data: {
                            labels: L(dataPackages),
                            datasets: [{
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
