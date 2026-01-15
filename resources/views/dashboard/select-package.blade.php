{{--
/**
 * Nombre de la vista           : available.blade.php
 * Descripción de la vista      : Muestra paquetes disponibles para contratar
 * Fecha de creación            : 13/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 14/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Mantenimiento                : Compatibilidad con Flux FREE (sin PRO)
 */
--}}

<x-layouts.app.sidebar>
    <div class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- Encabezado --}}
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-black dark:text-white">
                    Paquetes Disponibles
                </h1>
                <p class="mt-2 text-zinc-600 dark:text-zinc-400">
                    Elige el plan que mejor se adapte a las necesidades de tu negocio
                </p>
            </div>

            {{-- Paquete actual --}}
            @if (isset($currentPackage) && $currentPackage)
                <div
                    class="mb-8 rounded-lg bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 p-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="h-6 w-6 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-semibold text-green-800 dark:text-green-200">
                                Paquete Actual: {{ $currentPackage->package->name }}
                            </h3>
                            <p class="text-sm text-green-700 dark:text-green-300">
                                Expira: {{ $currentPackage->end_date->format('d/m/Y') }}
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            {{-- Grid de paquetes --}}
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($packages as $package)
                    <div
                        class="flex flex-col rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900 shadow-sm hover:shadow-lg transition-shadow duration-200">
                        {{-- Encabezado del paquete --}}
                        <div class="border-b border-zinc-200 dark:border-zinc-700 p-6 pb-4">
                            <h2 class="text-2xl font-bold text-black dark:text-white">
                                {{ $package->name }}
                            </h2>

                            @if ($package->description)
                                <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                    {{ $package->description }}
                                </p>
                            @endif
                        </div>

                        {{-- Precio --}}
                        <div class="py-6 text-center px-6">
                            <div class="text-4xl font-bold text-black dark:text-white">
                                ${{ number_format($package->price, 2) }}
                            </div>
                            <div class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                por {{ $package->duration_days }} días
                            </div>
                        </div>

                        {{-- Características --}}
                        <div class="flex-1 space-y-3 py-4 px-6 border-t border-zinc-200 dark:border-zinc-700">
                            <p class="text-sm font-semibold text-zinc-700 dark:text-zinc-300">Características:</p>

                            <div class="space-y-2 text-sm">
                                {{-- Reportes --}}
                                <div class="flex items-center gap-2">
                                    @if ($package->has_reports)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-zinc-700 dark:text-zinc-300">Reportes incluidos</span>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-zinc-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-zinc-400">Sin reportes</span>
                                    @endif
                                </div>

                                {{-- Estadísticas --}}
                                <div class="flex items-center gap-2">
                                    @if ($package->has_statistics)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-zinc-700 dark:text-zinc-300">Estadísticas incluidas</span>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-zinc-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-zinc-400">Sin estadísticas</span>
                                    @endif
                                </div>

                                {{-- Filtros --}}
                                <div class="flex items-center gap-2">
                                    @if ($package->has_filters)
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-green-600">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-zinc-700 dark:text-zinc-300">Filtros avanzados</span>
                                    @else
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-zinc-400">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M9.75 9.75l4.5 4.5m0-4.5l-4.5 4.5M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                        </svg>
                                        <span class="text-zinc-400">Filtros básicos</span>
                                    @endif
                                </div>

                                {{-- Retención de datos --}}
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-blue-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    <span class="text-zinc-700 dark:text-zinc-300">
                                        Retención: {{ $package->data_retention_days }} días
                                    </span>
                                </div>

                                {{-- Límite de órdenes --}}
                                <div class="flex items-center gap-2">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="h-5 w-5 text-purple-600">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                                    </svg>
                                    <span class="text-zinc-700 dark:text-zinc-300">
                                        @if ($package->max_orders)
                                            Hasta {{ number_format($package->max_orders) }} órdenes
                                        @else
                                            Órdenes ilimitadas
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>

                        {{-- Botón contratar --}}
                        <div class="p-6 pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <form method="POST" action="{{ route('packages.subscribe', $package) }}">
                                @csrf
                                <button type="submit"
                                    class="w-full rounded-lg bg-black dark:bg-white px-4 py-2.5 text-sm font-semibold text-white dark:text-black hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors duration-200">
                                    Contratar Paquete
                                </button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="col-span-full">
                        <div
                            class="text-center py-12 rounded-lg border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-900">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="h-8 w-8 text-zinc-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M21 7.5l-9-5.25L3 7.5m18 0l-9 5.25m9-5.25v9l-9 5.25M3 7.5l9 5.25M3 7.5v9l9 5.25m0-9v9" />
                                </svg>
                            </div>
                            <h3 class="mt-4 text-lg font-semibold text-black dark:text-white">
                                No hay paquetes disponibles
                            </h3>
                            <p class="mt-2 text-sm text-zinc-600 dark:text-zinc-400">
                                Por favor, contacta al administrador del sistema.
                            </p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
        <script>
            @if (session('success'))
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonText: 'Continuar',
                    confirmButtonColor: '#000000'
                });
            @endif

            @if (session('error'))
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#dc2626'
                });
            @endif

            @if (session('warning'))
                Swal.fire({
                    icon: 'warning',
                    title: 'Atención',
                    text: '{{ session('warning') }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#f59e0b'
                });
            @endif

            @if (session('info'))
                Swal.fire({
                    icon: 'info',
                    title: 'Información',
                    text: '{{ session('info') }}',
                    confirmButtonText: 'Entendido',
                    confirmButtonColor: '#3b82f6'
                });
            @endif
        </script>
    @endpush
</x-layouts.app.sidebar>
