<x-app-layout>
    @section('page-title', 'Gestión de Negocios')

    <div>
        {{-- Encabezado --}}
        <div class="mb-6">
            <div class="flex items-center">
                <x-heroicon-o-building-storefront class="w-8 h-8 text-gray-700 mr-3" />
                <h2 class="text-3xl font-bold text-gray-900">Negocios Registrados</h2>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <flux:input 
                        name="search"
                        :value="request('search')"
                        placeholder="Buscar por nombre o RFC..."
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                <flux:select name="status">
                    <option value="">Todos los estados</option>
                    <option value="1" @selected(request('status') === '1')>Activos</option>
                    <option value="0" @selected(request('status') === '0')>Suspendidos</option>
                </flux:select>
            </form>
        </div>

        {{-- Grid de negocios --}}
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($businesses as $business)
                <div class="bg-white rounded-lg shadow-lg overflow-hidden hover:shadow-xl transition @if(!$business->is_active) opacity-60 @endif">
                    {{-- Logo o icono --}}
                    <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        @if($business->logo)
                            <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->business_name }}" class="h-full w-full object-cover">
                        @else
                            <x-heroicon-o-building-storefront class="w-24 h-24 text-gray-400" />
                        @endif
                    </div>

                    {{-- Información --}}
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $business->business_name }}</h3>
                        <p class="text-sm text-gray-600 mb-4">{{ $business->legal_name }}</p>

                        <div class="space-y-2 text-sm">
                            <div class="flex items-center text-gray-600">
                                <x-heroicon-o-identification class="w-4 h-4 mr-2" />
                                <span>RFC: {{ $business->rfc }}</span>
                            </div>

                            <div class="flex items-center text-gray-600">
                                <x-heroicon-o-phone class="w-4 h-4 mr-2" />
                                <span>{{ $business->phone }}</span>
                            </div>

                            @if($business->email)
                                <div class="flex items-center text-gray-600">
                                    <x-heroicon-o-envelope class="w-4 h-4 mr-2" />
                                    <span>{{ $business->email }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Estado --}}
                        <div class="mt-4 pt-4 border-t">
                            @if($business->is_active)
                                <flux:badge variant="success">
                                    <x-heroicon-o-check-badge class="w-4 h-4 mr-1" />
                                    Activo
                                </flux:badge>
                            @else
                                <flux:badge variant="danger">
                                    <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                    Suspendido
                                </flux:badge>
                            @endif

                            @if($business->hasActivePackage())
                                <flux:badge variant="info" class="ml-2">
                                    <x-heroicon-o-cube class="w-4 h-4 mr-1" />
                                    Con Paquete
                                </flux:badge>
                            @endif
                        </div>
                    </div>

                    {{-- Acciones --}}
                    <div class="px-6 pb-6 flex space-x-2">
                        <flux:button 
                            variant="primary" 
                            outline 
                            size="sm"
                            href="{{ route('businesses.show', $business) }}"
                            class="flex-1"
                        >
                            <x-heroicon-o-eye class="w-4 h-4 mr-1" />
                            Ver
                        </flux:button>

                        @if($business->is_active)
                            <form action="{{ route('businesses.suspend', $business) }}" method="POST" class="flex-1">
                                @csrf
                                <flux:button 
                                    type="submit"
                                    variant="danger"
                                    outline 
                                    size="sm"
                                    class="w-full"
                                    onclick="return confirm('¿Suspender este negocio?')"
                                >
                                    <x-heroicon-o-pause-circle class="w-4 h-4 mr-1" />
                                    Suspender
                                </flux:button>
                            </form>
                        @else
                            <form action="{{ route('businesses.reactivate', $business) }}" method="POST" class="flex-1">
                                @csrf
                                <flux:button 
                                    type="submit"
                                    variant="success"
                                    outline 
                                    size="sm"
                                    class="w-full"
                                >
                                    <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                    Reactivar
                                </flux:button>
                            </form>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-3">
                    <x-empty-state
                        icon="building-storefront"
                        title="No se encontraron negocios"
                        description="No hay negocios registrados en el sistema"
                    />
                </div>
            @endforelse
        </div>

        {{-- Paginación --}}
        @if($businesses->hasPages())
            <div class="mt-6">
                {{ $businesses->links() }}
            </div>
        @endif
    </div>
</x-app-layout>
