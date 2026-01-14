{{--
/**
 * Nombre de la vista           : available.blade.php
 * Descripción de la vista      : Muestra paquetes disponibles para contratar
 * Fecha de creación            : 13/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 13/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 */
--}}

<x-layouts.app.sidebar>
    <div class="min-h-screen bg-white dark:bg-zinc-800">
        <div class="mx-auto max-w-7xl px-4 py-8 sm:px-6 lg:px-8">

            {{-- Encabezado --}}
            <div class="mb-8">
                <flux:heading size="xl" class="text-black dark:text-white">
                    Paquetes Disponibles
                </flux:heading>
                <flux:subheading class="mt-2">
                    Elige el plan que mejor se adapte a las necesidades de tu negocio
                </flux:subheading>
            </div>

            {{-- Paquete actual --}}
            @if (isset($currentPackage) && $currentPackage)
                <flux:card class="mb-8 bg-green-50 dark:bg-green-900/20 border-green-200 dark:border-green-800">
                    <div class="flex items-center gap-3">
                        <div class="flex h-10 w-10 items-center justify-center rounded-lg bg-green-600">
                            <x-heroicon-o-check-circle class="h-6 w-6 text-white" />
                        </div>
                        <div>
                            <flux:heading size="sm" class="text-green-800 dark:text-green-200">
                                Paquete Actual: {{ $currentPackage->package->name }}
                            </flux:heading>
                            <flux:subheading class="text-green-700 dark:text-green-300">
                                Expira: {{ $currentPackage->end_date->format('d/m/Y') }}
                            </flux:subheading>
                        </div>
                    </div>
                </flux:card>
            @endif

            {{-- Grid de paquetes --}}
            <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                @forelse($packages as $package)
                    <flux:card class="flex flex-col hover:shadow-lg transition-shadow duration-200">
                        {{-- Encabezado del paquete --}}
                        <div class="border-b border-zinc-200 dark:border-zinc-700 pb-4">
                            <flux:heading size="lg" class="text-black dark:text-white">
                                {{ $package->name }}
                            </flux:heading>

                            @if ($package->description)
                                <flux:subheading class="mt-2">
                                    {{ $package->description }}
                                </flux:subheading>
                            @endif
                        </div>

                        {{-- Precio --}}
                        <div class="py-6 text-center">
                            <div class="text-4xl font-bold text-black dark:text-white">
                                ${{ number_format($package->price, 2) }}
                            </div>
                            <div class="mt-1 text-sm text-zinc-500 dark:text-zinc-400">
                                por {{ $package->duration_days }} días
                            </div>
                        </div>

                        {{-- Características --}}
                        <div class="flex-1 space-y-3 py-4 border-t border-zinc-200 dark:border-zinc-700">
                            <flux:subheading class="font-semibold">Características:</flux:subheading>

                            <div class="space-y-2 text-sm">
                                {{-- Reportes --}}
                                <div class="flex items-center gap-2">
                                    @if ($package->has_reports)
                                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-600" />
                                        <span class="text-zinc-700 dark:text-zinc-300">Reportes incluidos</span>
                                    @else
                                        <x-heroicon-o-x-circle class="h-5 w-5 text-zinc-400" />
                                        <span class="text-zinc-400">Sin reportes</span>
                                    @endif
                                </div>

                                {{-- Estadísticas --}}
                                <div class="flex items-center gap-2">
                                    @if ($package->has_statistics)
                                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-600" />
                                        <span class="text-zinc-700 dark:text-zinc-300">Estadísticas incluidas</span>
                                    @else
                                        <x-heroicon-o-x-circle class="h-5 w-5 text-zinc-400" />
                                        <span class="text-zinc-400">Sin estadísticas</span>
                                    @endif
                                </div>

                                {{-- Filtros --}}
                                <div class="flex items-center gap-2">
                                    @if ($package->has_filters)
                                        <x-heroicon-o-check-circle class="h-5 w-5 text-green-600" />
                                        <span class="text-zinc-700 dark:text-zinc-300">Filtros avanzados</span>
                                    @else
                                        <x-heroicon-o-x-circle class="h-5 w-5 text-zinc-400" />
                                        <span class="text-zinc-400">Filtros básicos</span>
                                    @endif
                                </div>

                                {{-- Retención de datos --}}
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-clock class="h-5 w-5 text-blue-600" />
                                    <span class="text-zinc-700 dark:text-zinc-300">
                                        Retención: {{ $package->data_retention_days }} días
                                    </span>
                                </div>

                                {{-- Límite de órdenes --}}
                                <div class="flex items-center gap-2">
                                    <x-heroicon-o-shopping-bag class="h-5 w-5 text-purple-600" />
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
                        <div class="pt-4 border-t border-zinc-200 dark:border-zinc-700">
                            <form method="POST" action="{{ route('packages.subscribe', $package) }}">
                                @csrf
                                <flux:button type="submit" variant="primary" class="w-full">
                                    Contratar Paquete
                                </flux:button>
                            </form>
                        </div>
                    </flux:card>
                @empty
                    <div class="col-span-full">
                        <flux:card class="text-center py-12">
                            <div
                                class="mx-auto flex h-16 w-16 items-center justify-center rounded-full bg-zinc-100 dark:bg-zinc-800">
                                <x-heroicon-o-cube class="h-8 w-8 text-zinc-400" />
                            </div>
                            <flux:heading size="lg" class="mt-4">
                                No hay paquetes disponibles
                            </flux:heading>
                            <flux:subheading class="mt-2">
                                Por favor, contacta al administrador del sistema.
                            </flux:subheading>
                        </flux:card>
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
