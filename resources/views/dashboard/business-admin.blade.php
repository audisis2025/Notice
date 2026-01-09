<x-app-layout>
    @section('page-title', 'Dashboard - ' . $business->business_name)

    <div class="space-y-6">
        <!-- Paquete Activo -->
        @if($activePackage)
            <div class="bg-gradient-to-r from-green-500 to-green-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <div class="flex items-center mb-2">
                            <x-heroicon-o-check-badge class="w-6 h-6 mr-2" />
                            <h3 class="text-lg font-semibold">Paquete Activo: {{ $activePackage->package->name }}</h3>
                        </div>
                        <p class="text-green-100">
                            Válido hasta: {{ $activePackage->end_date->format('d/m/Y') }}
                            ({{ $activePackage->end_date->diffInDays(now()) }} días restantes)
                        </p>
                    </div>
                    <div>
                        <flux:button 
                            variant="ghost" 
                            href="{{ route('packages.available') }}"
                            class="text-white border-white hover:bg-white hover:text-green-600"
                        >
                            Ver Paquetes
                        </flux:button>
                    </div>
                </div>
            </div>
        @else
            <div class="bg-yellow-50 border-l-4 border-yellow-400 p-6 rounded-lg">
                <div class="flex items-center">
                    <x-heroicon-o-exclamation-triangle class="w-6 h-6 text-yellow-600 mr-3" />
                    <div class="flex-1">
                        <h3 class="text-lg font-semibold text-yellow-800">No tienes un paquete activo</h3>
                        <p class="text-yellow-700 mt-1">Contrata un paquete para acceder a todas las funcionalidades.</p>
                    </div>
                    <flux:button 
                        variant="warning" 
                        href="{{ route('packages.available') }}"
                        class="ml-4"
                    >
                        Contratar Ahora
                    </flux:button>
                </div>
            </div>
        @endif

        <!-- Estadísticas del Negocio -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Órdenes Hoy</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $business->orders()->whereDate('created_at', today())->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-blue-100 rounded-full">
                        <x-heroicon-o-shopping-bag class="w-8 h-8 text-blue-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Órdenes Listas</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $business->orders()->where('status', 'ready')->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <x-heroicon-o-clock class="w-8 h-8 text-yellow-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Calificación</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ number_format($business->averageRating, 1) }} ⭐
                        </p>
                    </div>
                    <div class="p-3 bg-green-100 rounded-full">
                        <x-heroicon-o-star class="w-8 h-8 text-green-600" />
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-600">Total Órdenes</p>
                        <p class="mt-2 text-3xl font-bold text-gray-900">
                            {{ $business->orders()->count() }}
                        </p>
                    </div>
                    <div class="p-3 bg-purple-100 rounded-full">
                        <x-heroicon-o-chart-bar class="w-8 h-8 text-purple-600" />
                    </div>
                </div>
            </div>
        </div>

        <!-- Órdenes Recientes -->
        <div class="bg-white rounded-lg shadow">
            <div class="p-6 border-b flex items-center justify-between">
                <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                    <x-heroicon-o-clock class="w-6 h-6 mr-2" />
                    Órdenes Recientes
                </h3>
                <flux:button 
                    variant="ghost" 
                    href="{{ route('orders.index') }}"
                    icon="arrow-right"
                >
                    Ver todas
                </flux:button>
            </div>

            <div class="p-6">
                @if($recentOrders->count() > 0)
                    <div class="space-y-4">
                        @foreach($recentOrders as $order)
                            <div class="flex items-center justify-between p-4 border rounded-lg hover:shadow-md transition">
                                <div class="flex items-center space-x-4">
                                    <div class="p-2 bg-gray-100 rounded">
                                        <x-heroicon-o-shopping-bag class="w-6 h-6 text-gray-600" />
                                    </div>
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $order->order_number }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                <div class="flex items-center space-x-4">
                                    <p class="font-bold text-gray-900">${{ number_format($order->amount, 2) }}</p>
                                    <x-badge-status :status="$order->status" />
                                    <flux:button 
                                        variant="primary" 
                                        outline 
                                        size="sm"
                                        href="{{ route('orders.show', $order) }}"
                                    >
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </flux:button>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <x-empty-state
                        icon="shopping-bag"
                        title="No hay órdenes"
                        description="Aún no has creado ninguna orden"
                        actionText="Crear Primera Orden"
                        actionUrl="{{ route('orders.create') }}"
                    />
                @endif
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <x-heroicon-o-bolt class="w-6 h-6 mr-2" />
                Accesos Rápidos
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <a href="{{ route('orders.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                    <x-heroicon-o-plus-circle class="w-8 h-8 text-gray-600 mr-3" />
                    <div>
                        <p class="font-medium text-gray-900">Nueva Orden</p>
                        <p class="text-sm text-gray-500">Crear orden</p>
                    </div>
                </a>

                <a href="{{ route('business.edit') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                    <x-heroicon-o-cog-6-tooth class="w-8 h-8 text-gray-600 mr-3" />
                    <div>
                        <p class="font-medium text-gray-900">Configuración</p>
                        <p class="text-sm text-gray-500">Mi negocio</p>
                    </div>
                </a>

                @can('access-reports')
                    <a href="{{ route('reports.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                        <x-heroicon-o-chart-bar class="w-8 h-8 text-gray-600 mr-3" />
                        <div>
                            <p class="font-medium text-gray-900">Reportes</p>
                            <p class="text-sm text-gray-500">Estadísticas</p>
                        </div>
                    </a>
                @endcan
            </div>
        </div>
    </div>
</x-app-layout>