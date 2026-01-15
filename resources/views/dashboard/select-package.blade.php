{{--
/**
 * Nombre de la vista           : select-package.blade.php
 * Descripción de la vista      : Muestra paquete actual detallado + paquetes disponibles
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Sistema
 * Versión                      : 1.0
 */
--}}
<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="p-6">
        {{-- Encabezado --}}
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black dark:text-white">
                {{ __('Mi Paquete') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Información de tu paquete actual y paquetes disponibles
            </p>
        </div>

        {{-- PAQUETE ACTUAL - DETALLADO --}}
        @if (isset($currentPackage) && $currentPackage)
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 p-6 mb-8">
                {{-- Header con estado --}}
                <div class="flex items-start justify-between mb-6">
                    <div class="flex items-center gap-3">
                        <div class="flex h-12 w-12 items-center justify-center rounded-lg bg-purple-600">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-7 h-7 text-white">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 7.5l-2.25-1.313M21 7.5v2.25m0-2.25l-2.25 1.313M3 7.5l2.25-1.313M3 7.5l2.25 1.313M3 7.5v2.25m9 3l2.25-1.313M12 12.75l-2.25-1.313M12 12.75V15m0 6.75l2.25-1.313M12 21.75V19.5m0 2.25l-2.25-1.313m0-16.875L12 2.25l2.25 1.313M21 14.25v2.25l-2.25 1.313m-13.5 0L3 16.5v-2.25" />
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-2xl font-bold text-black dark:text-white">
                                {{ $currentPackage->package->name }}
                            </h2>
                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                Tu paquete actual
                            </p>
                        </div>
                    </div>
                    
                    @if($currentPackage->status === 'active')
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300">
                            ✓ Activo
                        </span>
                    @else
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-semibold bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300">
                            {{ ucfirst($currentPackage->status) }}
                        </span>
                    @endif
                </div>

                {{-- Grid de información --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                    {{-- Órdenes --}}
                    <div class="bg-gray-50 dark:bg-zinc-900 rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-2">Órdenes Usadas</p>
                        <div class="flex items-baseline gap-2">
                            <p class="text-3xl font-bold text-gray-900 dark:text-white">
                                {{ $currentPackage->orders_used }}
                            </p>
                            <p class="text-lg text-gray-500 dark:text-gray-400">
                                / {{ $currentPackage->package->max_orders }}
                            </p>
                        </div>
                        {{-- Barra de progreso --}}
                        @php
                            $percentage = $currentPackage->package->max_orders > 0 
                                ? min(100, ($currentPackage->orders_used / $currentPackage->package->max_orders) * 100) 
                                : 0;
                        @endphp
                        <div class="mt-3">
                            <div class="w-full bg-gray-200 dark:bg-zinc-700 rounded-full h-2">
                                <div class="h-2 rounded-full transition-all duration-300
                                    @if($percentage < 50) bg-green-500
                                    @elseif($percentage < 80) bg-yellow-500
                                    @else bg-red-500
                                    @endif"
                                    style="width: {{ $percentage }}%">
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                {{ number_format($percentage, 1) }}% utilizado
                            </p>
                        </div>
                    </div>

                    {{-- Fechas --}}
                    <div class="bg-gray-50 dark:bg-zinc-900 rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-2">Vigencia</p>
                        <p class="text-sm text-gray-700 dark:text-gray-300 mb-1">
                            <span class="font-semibold">Inicio:</span> 
                            {{ \Carbon\Carbon::parse($currentPackage->start_date)->format('d/m/Y') }}
                        </p>
                        <p class="text-sm text-gray-700 dark:text-gray-300">
                            <span class="font-semibold">Vence:</span> 
                            {{ \Carbon\Carbon::parse($currentPackage->end_date)->format('d/m/Y') }}
                        </p>
                        @php
                            $daysLeft = \Carbon\Carbon::now()->diffInDays(\Carbon\Carbon::parse($currentPackage->end_date), false);
                        @endphp
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                            @if($daysLeft > 0)
                                {{ $daysLeft }} días restantes
                            @else
                                Expirado
                            @endif
                        </p>
                    </div>

                    {{-- Precio --}}
                    <div class="bg-gray-50 dark:bg-zinc-900 rounded-lg p-4">
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase mb-2">Inversión</p>
                        <p class="text-3xl font-bold text-purple-600 dark:text-purple-400">
                            ${{ number_format($currentPackage->package->price, 2) }}
                        </p>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                            por {{ $currentPackage->package->duration_days }} días
                        </p>
                    </div>
                </div>

                {{-- Características del paquete --}}
                <div class="border-t border-gray-200 dark:border-zinc-700 pt-6">
                    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-4">Características incluidas:</p>
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
                        <div class="flex items-center gap-2 text-sm">
                            @if($currentPackage->package->has_reports)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Reportes</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="text-gray-400">Reportes</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            @if($currentPackage->package->has_statistics)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Estadísticas</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="text-gray-400">Estadísticas</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            @if($currentPackage->package->has_filters)
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-green-600">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="text-gray-700 dark:text-gray-300">Filtros avanzados</span>
                            @else
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 text-gray-400">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                <span class="text-gray-400">Filtros básicos</span>
                            @endif
                        </div>

                        <div class="flex items-center gap-2 text-sm">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-5 h-5 text-blue-600">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            <span class="text-gray-700 dark:text-gray-300">
                                {{ $currentPackage->package->data_retention_days }} días retención
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        @else
            {{-- Sin paquete activo --}}
            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg border border-yellow-200 dark:border-yellow-800 p-6 mb-8">
                <div class="flex items-start gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-12 h-12 text-yellow-600 dark:text-yellow-400 flex-shrink-0">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126zM12 15.75h.007v.008H12v-.008z" />
                    </svg>
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-yellow-800 dark:text-yellow-300 mb-2">
                            No tienes un paquete activo
                        </h3>
                        <p class="text-sm text-yellow-700 dark:text-yellow-400 mb-4">
                            Selecciona uno de los paquetes disponibles abajo para comenzar a usar todas las funcionalidades del sistema.
                        </p>
                    </div>
                </div>
            </div>
        @endif

        {{-- PAQUETES DISPONIBLES --}}
        <div class="mb-6">
            <h2 class="text-2xl font-bold text-black dark:text-white mb-2">
                Paquetes Disponibles
            </h2>
            <p class="text-gray-600 dark:text-gray-400">
                Elige el plan que mejor se adapte a las necesidades de tu negocio
            </p>
        </div>

        {{-- Grid de paquetes --}}
        <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
            @forelse($packages as $package)
                <div class="flex flex-col rounded-lg border border-gray-200 dark:border-zinc-700 bg-white dark:bg-zinc-800 shadow-sm hover:shadow-lg transition-shadow duration-200">
                    {{-- Encabezado del paquete --}}
                    <div class="border-b border-gray-200 dark:border-zinc-700 p-6 pb-4">
                        <h3 class="text-xl font-bold text-black dark:text-white">
                            {{ $package->name }}
                        </h3>
                        @if ($package->description)
                            <p class="mt-2 text-sm text-gray-600 dark:text-gray-400">
                                {{ $package->description }}
                            </p>
                        @endif
                    </div>

                    {{-- Precio --}}
                    <div class="py-6 text-center px-6">
                        <div class="text-3xl font-bold text-black dark:text-white">
                            ${{ number_format($package->price, 2) }}
                        </div>
                        <div class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                            por {{ $package->duration_days }} días
                        </div>
                    </div>

                    {{-- Características resumidas --}}
                    <div class="flex-1 px-6 pb-6 text-sm text-gray-600 dark:text-gray-400">
                        <p class="mb-2">✓ Hasta {{ number_format($package->max_orders) }} órdenes</p>
                        <p class="mb-2">✓ {{ $package->data_retention_days }} días de retención</p>
                        <p class="mb-2">{{ $package->has_reports ? '✓' : '✗' }} Reportes</p>
                        <p>{{ $package->has_statistics ? '✓' : '✗' }} Estadísticas</p>
                    </div>

                    {{-- Botón contratar --}}
                    <div class="p-6 pt-0 border-t border-gray-200 dark:border-zinc-700">
                        <form method="POST" action="{{ route('packages.subscribe', $package) }}">
                            @csrf
                            <button type="submit"
                                class="w-full rounded-lg bg-black dark:bg-white px-4 py-2.5 text-sm font-semibold text-white dark:text-black hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors duration-200">
                                Contratar Paquete
                            </button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-12 text-gray-500 dark:text-gray-400">
                    No hay paquetes disponibles en este momento
                </div>
            @endforelse
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