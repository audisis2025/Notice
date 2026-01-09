<x-app-layout>
    @section('page-title', 'Dashboard Super Administrador')

    <div class="space-y-6">
        <!-- Estadísticas Globales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <x-stat-card
                title="Total Negocios"
                :value="$statistics['total_businesses']"
                icon="building-office"
                color="blue"
            />

            <x-stat-card
                title="Negocios Activos"
                :value="$statistics['active_businesses']"
                icon="check-badge"
                color="green"
            />

            <x-stat-card
                title="Total Órdenes"
                :value="number_format($statistics['total_orders'])"
                icon="shopping-bag"
                color="purple"
            />

            <x-stat-card
                title="Ingresos Totales"
                :value="'$' . number_format($statistics['total_revenue'], 2)"
                icon="banknotes"
                color="yellow"
            />
        </div>

        <!-- Estadísticas del Mes -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <x-heroicon-o-calendar class="w-6 h-6 mr-2" />
                Estadísticas del Mes Actual
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="flex items-center p-4 bg-blue-50 rounded-lg">
                    <div class="p-3 bg-blue-100 rounded-full mr-4">
                        <x-heroicon-o-shopping-bag class="w-8 h-8 text-blue-600" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Órdenes este mes</p>
                        <p class="text-2xl font-bold text-gray-900">{{ number_format($statistics['orders_this_month']) }}</p>
                    </div>
                </div>

                <div class="flex items-center p-4 bg-green-50 rounded-lg">
                    <div class="p-3 bg-green-100 rounded-full mr-4">
                        <x-heroicon-o-currency-dollar class="w-8 h-8 text-green-600" />
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Ingresos este mes</p>
                        <p class="text-2xl font-bold text-gray-900">${{ number_format($statistics['revenue_this_month'], 2) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Accesos Rápidos -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                <x-heroicon-o-bolt class="w-6 h-6 mr-2" />
                Accesos Rápidos
            </h3>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                <a href="{{ route('users.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                    <x-heroicon-o-user-plus class="w-8 h-8 text-gray-600 mr-3" />
                    <div>
                        <p class="font-medium text-gray-900">Crear Usuario</p>
                        <p class="text-sm text-gray-500">Nuevo usuario</p>
                    </div>
                </a>

                <a href="{{ route('packages.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                    <x-heroicon-o-cube class="w-8 h-8 text-gray-600 mr-3" />
                    <div>
                        <p class="font-medium text-gray-900">Crear Paquete</p>
                        <p class="text-sm text-gray-500">Nuevo plan</p>
                    </div>
                </a>

                <a href="{{ route('coupons.create') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                    <x-heroicon-o-ticket class="w-8 h-8 text-gray-600 mr-3" />
                    <div>
                        <p class="font-medium text-gray-900">Generar Cupón</p>
                        <p class="text-sm text-gray-500">Descuento</p>
                    </div>
                </a>

                <a href="{{ route('businesses.index') }}" class="flex items-center p-4 border-2 border-gray-200 rounded-lg hover:border-black hover:shadow-md transition">
                    <x-heroicon-o-building-office class="w-8 h-8 text-gray-600 mr-3" />
                    <div>
                        <p class="font-medium text-gray-900">Ver Negocios</p>
                        <p class="text-sm text-gray-500">Gestionar</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
</x-app-layout>