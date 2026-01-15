<x-layouts.app.sidebar>
    <x-flash-messages />

    @section('page-title', $business->business_name)

    <div class="max-w-6xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button variant="ghost" href="{{ route('businesses.index') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Volver al listado
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Información principal --}}
            <div class="lg:col-span-2 space-y-6">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    {{-- Header con logo --}}
                    <div class="h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                        @if ($business->logo)
                            <img src="{{ Storage::url($business->logo) }}" alt="{{ $business->business_name }}"
                                class="h-full w-full object-cover">
                        @else
                            <x-heroicon-o-building-storefront class="w-32 h-32 text-gray-400" />
                        @endif
                    </div>

                    <div class="p-8">
                        <h1 class="text-3xl font-bold text-gray-900 mb-2">{{ $business->business_name }}</h1>
                        <p class="text-lg text-gray-600 mb-6">{{ $business->legal_name }}</p>

                        @if ($business->description)
                            <p class="text-gray-700 mb-6">{{ $business->description }}</p>
                        @endif

                        {{-- Información de contacto --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-3">Contacto</h3>
                                <div class="space-y-2">
                                    <div class="flex items-center text-gray-700">
                                        <x-heroicon-o-phone class="w-5 h-5 mr-2" />
                                        {{ $business->phone }}
                                    </div>
                                    @if ($business->email)
                                        <div class="flex items-center text-gray-700">
                                            <x-heroicon-o-envelope class="w-5 h-5 mr-2" />
                                            {{ $business->email }}
                                        </div>
                                    @endif
                                    @if ($business->website)
                                        <div class="flex items-center text-gray-700">
                                            <x-heroicon-o-globe-alt class="w-5 h-5 mr-2" />
                                            <a href="{{ $business->website }}" target="_blank"
                                                class="text-blue-600 hover:underline">
                                                Sitio web
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-3">Dirección</h3>
                                <div class="text-gray-700">
                                    <p>{{ $business->address }}</p>
                                    <p>{{ $business->city }}, {{ $business->state }}</p>
                                    <p>CP: {{ $business->postal_code }}</p>
                                    <p>{{ $business->country }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Configuraciones --}}
                        <div class="mt-6 pt-6 border-t">
                            <h3 class="text-sm font-medium text-gray-500 mb-3">Configuraciones</h3>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div class="flex items-center">
                                    <x-heroicon-o-clock class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-gray-700">Período de entrega:
                                        {{ $business->delivery_period_minutes }} min</span>
                                </div>
                                <div class="flex items-center">
                                    @if ($business->can_be_rated)
                                        <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                        <span class="text-gray-700">Calificaciones habilitadas</span>
                                    @else
                                        <x-heroicon-o-x-circle class="w-5 h-5 text-red-500 mr-2" />
                                        <span class="text-gray-700">Calificaciones deshabilitadas</span>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Estadísticas --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Estadísticas</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-blue-50 rounded-lg p-4">
                            <p class="text-sm text-blue-700">Total Órdenes</p>
                            <p class="text-3xl font-bold text-blue-900">{{ $business->orders()->count() }}</p>
                        </div>
                        <div class="bg-green-50 rounded-lg p-4">
                            <p class="text-sm text-green-700">Órdenes Completadas</p>
                            <p class="text-3xl font-bold text-green-900">
                                {{ $business->orders()->where('status', 'delivered')->count() }}</p>
                        </div>
                        <div class="bg-yellow-50 rounded-lg p-4">
                            <p class="text-sm text-yellow-700">Calificación Promedio</p>
                            <p class="text-3xl font-bold text-yellow-900">
                                {{ number_format($business->averageRating(), 1) }}</p>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Sidebar --}}
            <div class="space-y-6">
                {{-- Estado --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Estado</h3>
                    <div class="space-y-3">
                        @if ($business->is_active)
                            <flux:badge variant="success" class="w-full justify-center">
                                <x-heroicon-o-check-badge class="w-5 h-5 mr-2" />
                                Negocio Activo
                            </flux:badge>
                            <form action="{{ route('businesses.suspend', $business) }}" method="POST">
                                @csrf
                                <flux:button type="submit" variant="danger" class="w-full"
                                    onclick="return confirm('¿Suspender este negocio?')">
                                    <x-heroicon-o-pause-circle class="w-5 h-5 mr-2" />
                                    Suspender Negocio
                                </flux:button>
                            </form>
                        @else
                            <flux:badge variant="danger" class="w-full justify-center">
                                <x-heroicon-o-x-circle class="w-5 h-5 mr-2" />
                                Negocio Suspendido
                            </flux:badge>
                            <form action="{{ route('businesses.reactivate', $business) }}" method="POST">
                                @csrf
                                <flux:button type="submit" variant="success" class="w-full">
                                    <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                                    Reactivar Negocio
                                </flux:button>
                            </form>
                        @endif
                    </div>
                </div>

                {{-- Paquete actual --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Paquete Actual</h3>
                    @if ($business->hasActivePackage())
                        <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                            <p class="font-semibold text-green-900">{{ $business->activePackage->package->name }}</p>
                            <p class="text-sm text-green-700 mt-2">
                                Vence: {{ $business->activePackage->end_date->format('d/m/Y') }}
                            </p>
                        </div>
                    @else
                        <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                            <p class="text-sm text-yellow-800">Sin paquete activo</p>
                        </div>
                    @endif
                </div>

                {{-- Información del administrador --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Administrador</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                            <x-heroicon-o-user class="w-7 h-7 text-gray-500" />
                        </div>
                        <div class="ml-3">
                            <p class="font-medium text-gray-900">{{ $business->user->name }}</p>
                            <p class="text-sm text-gray-500">{{ $business->user->phone }}</p>
                        </div>
                    </div>
                    <flux:button variant="ghost" size="sm" href="{{ route('users.show', $business->user) }}"
                        class="w-full mt-4">
                        Ver perfil
                    </flux:button>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.sidebar>
