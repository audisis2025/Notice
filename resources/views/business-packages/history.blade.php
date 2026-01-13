<x-layouts.app.sidebar>
    @section('page-title', 'Historial de Paquetes')

    <div class="max-w-6xl mx-auto">
        {{-- Encabezado --}}
        <div class="flex justify-between items-center mb-6">
            <div class="flex items-center">
                <x-heroicon-o-clock class="w-8 h-8 text-gray-700 mr-3" />
                <h2 class="text-3xl font-bold text-gray-900">Historial de Paquetes</h2>
            </div>

            <flux:button 
                variant="ghost"
                href="{{ route('packages.available') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Ver paquetes disponibles
            </flux:button>
        </div>

        {{-- Paquete activo --}}
        @if($activePackage)
            <div class="bg-green-50 border-2 border-green-500 rounded-lg shadow-lg p-6 mb-8">
                <div class="flex items-start justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-16 w-16 bg-green-500 rounded-full flex items-center justify-center">
                            <x-heroicon-o-check-badge class="w-10 h-10 text-white" />
                        </div>
                        <div class="ml-4">
                            <h3 class="text-2xl font-bold text-green-900">{{ $activePackage->package->name }}</h3>
                            <p class="text-green-700 mt-1">Paquete Activo Actual</p>
                        </div>
                    </div>

                    <div class="text-right">
                        <p class="text-sm text-green-700">Vence el:</p>
                        <p class="text-xl font-bold text-green-900">{{ $activePackage->end_date->format('d/m/Y') }}</p>
                        <p class="text-sm text-green-600 mt-1">
                            {{ $activePackage->end_date->diffForHumans() }}
                        </p>
                    </div>
                </div>

                <div class="mt-4 pt-4 border-t border-green-200 grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-green-700">Fecha de inicio</p>
                        <p class="font-semibold text-green-900">{{ $activePackage->start_date->format('d/m/Y') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-green-700">Duración</p>
                        <p class="font-semibold text-green-900">{{ $activePackage->package->duration_days }} días</p>
                    </div>
                    <div>
                        <p class="text-sm text-green-700">Precio pagado</p>
                        <p class="font-semibold text-green-900">${{ number_format($activePackage->price_paid, 2) }}</p>
                    </div>
                </div>
            </div>
        @endif

        {{-- Historial --}}
        <div class="bg-white rounded-lg shadow">
            <div class="px-6 py-4 border-b">
                <h3 class="text-lg font-semibold text-gray-900">Paquetes Anteriores</h3>
            </div>

            <div class="divide-y">
                @forelse($history as $businessPackage)
                    <div class="p-6 hover:bg-gray-50 transition">
                        <div class="flex items-start justify-between">
                            <div class="flex-1">
                                <div class="flex items-center mb-2">
                                    <x-heroicon-o-cube class="w-6 h-6 text-gray-400 mr-2" />
                                    <h4 class="text-lg font-semibold text-gray-900">{{ $businessPackage->package->name }}</h4>
                                </div>

                                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
                                    <div>
                                        <p class="text-gray-500">Inicio</p>
                                        <p class="font-medium text-gray-900">{{ $businessPackage->start_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Fin</p>
                                        <p class="font-medium text-gray-900">{{ $businessPackage->end_date->format('d/m/Y') }}</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Duración</p>
                                        <p class="font-medium text-gray-900">{{ $businessPackage->package->duration_days }} días</p>
                                    </div>
                                    <div>
                                        <p class="text-gray-500">Precio</p>
                                        <p class="font-medium text-gray-900">${{ number_format($businessPackage->price_paid, 2) }}</p>
                                    </div>
                                </div>

                                @if($businessPackage->coupon_used)
                                    <div class="mt-3">
                                        <flux:badge variant="info">
                                            <x-heroicon-o-ticket class="w-4 h-4 mr-1" />
                                            Cupón aplicado: {{ $businessPackage->coupon_used }}
                                        </flux:badge>
                                    </div>
                                @endif
                            </div>

                            <div class="ml-4">
                                @if($businessPackage->is_active)
                                    <flux:badge variant="success">Activo</flux:badge>
                                @else
                                    <flux:badge variant="gray">Expirado</flux:badge>
                                @endif
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="p-12">
                        <x-empty-state
                            icon="cube"
                            title="Sin historial"
                            description="No tienes paquetes anteriores"
                        />
                    </div>
                @endforelse
            </div>

            @if($history->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $history->links() }}
                </div>
            @endif
        </div>
    </div>
</x-layouts.app.sidebar>