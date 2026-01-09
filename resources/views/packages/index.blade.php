<x-app-layout>
    @section('page-title', 'Paquetes Comerciales')

    <div>
        {{-- Encabezado --}}
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <x-heroicon-o-cube class="w-8 h-8 text-gray-700 mr-3" />
                <h2 class="text-3xl font-bold text-gray-900">Gestión de Paquetes</h2>
            </div>
            
            <flux:button 
                variant="primary" 
                href="{{ route('packages.create') }}"
                class="bg-black hover:bg-[#494949]"
            >
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Crear Paquete
            </flux:button>
        </div>

        {{-- Grid de paquetes --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($packages as $package)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition @if(!$package->is_active) opacity-60 @endif">
                    {{-- Header del paquete --}}
                    <div class="bg-gradient-to-r @if($package->max_orders === null) from-purple-600 to-purple-700 @else from-gray-800 to-gray-900 @endif p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold">${{ number_format($package->price, 2) }}</span>
                            <span class="ml-2 text-sm opacity-75">/ {{ $package->duration_days }} días</span>
                        </div>
                    </div>

                    {{-- Características --}}
                    <div class="p-6 space-y-3">
                        <div class="flex items-center text-sm">
                            @if($package->max_orders === null)
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span class="font-medium">Órdenes ilimitadas</span>
                            @else
                                <x-heroicon-o-shopping-bag class="w-5 h-5 text-blue-500 mr-2" />
                                <span>Hasta {{ number_format($package->max_orders) }} órdenes</span>
                            @endif
                        </div>

                        @if($package->has_reports)
                            <div class="flex items-center text-sm">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Reportes avanzados</span>
                            </div>
                        @endif

                        @if($package->has_statistics)
                            <div class="flex items-center text-sm">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Estadísticas detalladas</span>
                            </div>
                        @endif

                        @if($package->has_filters)
                            <div class="flex items-center text-sm">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Filtros avanzados</span>
                            </div>
                        @endif

                        <div class="flex items-center text-sm">
                            <x-heroicon-o-clock class="w-5 h-5 text-gray-400 mr-2" />
                            <span>Retención de {{ $package->data_retention_days }} días</span>
                        </div>

                        {{-- Estado --}}
                        <div class="pt-4 border-t">
                            @if($package->is_active)
                                <flux:badge variant="success">
                                    <x-heroicon-o-check-badge class="w-4 h-4 mr-1" />
                                    Activo
                                </flux:badge>
                            @else
                                <flux:badge variant="danger">
                                    <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                    Inactivo
                                </flux:badge>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="px-6 pb-6 flex space-x-2">
                        <flux:button 
                            variant="warning" 
                            outline 
                            size="sm"
                            href="{{ route('packages.edit', $package) }}"
                            class="flex-1"
                        >
                            <x-heroicon-o-pencil class="w-4 h-4 mr-1" />
                            Editar
                        </flux:button>

                        <form action="{{ route('packages.toggle-status', $package) }}" method="POST" class="flex-1">
                            @csrf
                            <input type="hidden" name="is_active" value="{{ $package->is_active ? 0 : 1 }}">
                            <flux:button 
                                type="submit"
                                variant="{{ $package->is_active ? 'danger' : 'success' }}"
                                outline 
                                size="sm"
                                class="w-full"
                            >
                                @if($package->is_active)
                                    <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                    Desactivar
                                @else
                                    <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                    Activar
                                @endif
                            </flux:button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</x-app-layout>