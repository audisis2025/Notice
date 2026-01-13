<x-layouts.app.sidebar>
    @section('page-title', 'Contratar Paquete')

    <div class="max-w-7xl mx-auto">
        {{-- Información del paquete actual --}}
        @if($business->activePackage)
            <div class="mb-8 bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <x-heroicon-o-check-badge class="w-6 h-6 mr-2" />
                            <h3 class="text-xl font-semibold">Paquete Activo: {{ $business->activePackage->package->name }}</h3>
                        </div>
                        <p class="text-green-100">
                            Válido hasta: {{ $business->activePackage->end_date->format('d/m/Y') }}
                            ({{ $business->activePackage->end_date->diffInDays(now()) }} días restantes)
                        </p>
                    </div>
                    <div>
                        <flux:button 
                            variant="ghost" 
                            href="{{ route('packages.history') }}"
                            class="text-white border-white hover:bg-white hover:text-green-600"
                        >
                            <x-heroicon-o-clock class="w-5 h-5 mr-2" />
                            Ver Historial
                        </flux:button>
                    </div>
                </div>
            </div>
        @else
            <div class="mb-8 bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                <div class="flex items-center">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 mr-3" />
                    <div>
                        <h3 class="text-lg font-semibold text-yellow-800">No tienes un paquete activo</h3>
                        <p class="text-yellow-700 mt-1">Selecciona un paquete para comenzar a utilizar todas las funcionalidades.</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Grid de paquetes disponibles --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $package)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-2xl transition transform hover:-translate-y-1">
                    {{-- Header del paquete --}}
                    <div class="bg-gradient-to-r @if($package->max_orders === null) from-purple-600 to-purple-700 @else from-gray-800 to-gray-900 @endif p-8 text-white text-center">
                        <h3 class="text-3xl font-bold mb-3">{{ $package->name }}</h3>
                        <div class="flex items-baseline justify-center">
                            <span class="text-5xl font-bold">${{ number_format($package->price, 0) }}</span>
                        </div>
                        <p class="mt-2 text-sm opacity-90">{{ $package->duration_days }} días de servicio</p>
                    </div>

                    {{-- Características --}}
                    <div class="p-6 space-y-4">
                        <div class="flex items-start">
                            @if($package->max_orders === null)
                                <x-heroicon-o-infinity class="w-6 h-6 text-purple-500 mr-3 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Órdenes Ilimitadas</p>
                                    <p class="text-sm text-gray-500">Sin límite de órdenes</p>
                                </div>
                            @else
                                <x-heroicon-o-shopping-bag class="w-6 h-6 text-blue-500 mr-3 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">{{ number_format($package->max_orders) }} Órdenes</p>
                                    <p class="text-sm text-gray-500">Máximo mensual</p>
                                </div>
                            @endif
                        </div>

                        @if($package->has_reports)
                            <div class="flex items-start">
                                <x-heroicon-o-chart-bar class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Reportes Avanzados</p>
                                    <p class="text-sm text-gray-500">Análisis detallado</p>
                                </div>
                            </div>
                        @endif

                        @if($package->has_statistics)
                            <div class="flex items-start">
                                <x-heroicon-o-presentation-chart-line class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Estadísticas</p>
                                    <p class="text-sm text-gray-500">Métricas en tiempo real</p>
                                </div>
                            </div>
                        @endif

                        @if($package->has_filters)
                            <div class="flex items-start">
                                <x-heroicon-o-funnel class="w-6 h-6 text-green-500 mr-3 flex-shrink-0" />
                                <div>
                                    <p class="font-semibold text-gray-900">Filtros Avanzados</p>
                                    <p class="text-sm text-gray-500">Búsqueda mejorada</p>
                                </div>
                            </div>
                        @endif

                        <div class="flex items-start">
                            <x-heroicon-o-server class="w-6 h-6 text-gray-400 mr-3 flex-shrink-0" />
                            <div>
                                <p class="font-semibold text-gray-900">Retención de Datos</p>
                                <p class="text-sm text-gray-500">{{ $package->data_retention_days }} días</p>
                            </div>
                        </div>
                    </div>

                    {{-- Botón de contratación --}}
                    <div class="p-6 pt-0">
                        <flux:button 
                            variant="primary"
                            href="{{ route('packages.show', $package) }}"
                            class="w-full bg-black hover:bg-[#494949]"
                        >
                            <x-heroicon-o-shopping-cart class="w-5 h-5 mr-2" />
                            Contratar Ahora
                        </flux:button>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-layouts.app.sidebar>