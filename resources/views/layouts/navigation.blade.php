<aside class="w-64 bg-white shadow-lg flex flex-col">
    <!-- Logo -->
    <div class="p-6 border-b">
        <div class="flex items-center justify-center">
            <x-heroicon-o-bell-alert class="w-8 h-8 text-black mr-2" />
            <h1 class="text-2xl font-bold text-black">SISNOTICE</h1>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
        <flux:navlist>
            <!-- Dashboard -->
            <flux:navlist.item 
                icon="home" 
                href="{{ route('dashboard') }}"
                :active="request()->routeIs('dashboard')"
            >
                Dashboard
            </flux:navlist.item>

            @if(auth()->user()->isSuperAdministrator())
                <!-- Super Admin Menu -->
                <flux:navlist.group heading="Administración" expandable>
                    <flux:navlist.item 
                        icon="user-group" 
                        href="{{ route('users.index') }}"
                        :active="request()->routeIs('users.*')"
                    >
                        Usuarios
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="building-office" 
                        href="{{ route('businesses.index') }}"
                        :active="request()->routeIs('businesses.*')"
                    >
                        Negocios
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="cube" 
                        href="{{ route('packages.index') }}"
                        :active="request()->routeIs('packages.*')"
                    >
                        Paquetes
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="ticket" 
                        href="{{ route('coupons.index') }}"
                        :active="request()->routeIs('coupons.*')"
                    >
                        Cupones
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Reportes" expandable>
                    <flux:navlist.item 
                        icon="chart-bar" 
                        href="#"
                    >
                        Estadísticas Globales
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="document-text" 
                        href="#"
                    >
                        Reportes del Sistema
                    </flux:navlist.item>
                </flux:navlist.group>
            @endif

            @if(auth()->user()->isBusinessAdministrator())
                <!-- Business Admin Menu -->
                <flux:navlist.group heading="Mi Negocio" expandable>
                    <flux:navlist.item 
                        icon="building-storefront" 
                        href="{{ route('business.edit') }}"
                        :active="request()->routeIs('business.*')"
                    >
                        Datos del Negocio
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="shopping-bag" 
                        href="{{ route('orders.index') }}"
                        :active="request()->routeIs('orders.*')"
                    >
                        Órdenes
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="star" 
                        href="{{ route('business.ratings') }}"
                        :active="request()->routeIs('business.ratings')"
                    >
                        Calificaciones
                    </flux:navlist.item>
                </flux:navlist.group>

                <flux:navlist.group heading="Paquetes" expandable>
                    <flux:navlist.item 
                        icon="cube-transparent" 
                        href="{{ route('packages.available') }}"
                        :active="request()->routeIs('packages.available')"
                    >
                        Contratar Paquete
                    </flux:navlist.item>

                    <flux:navlist.item 
                        icon="clock" 
                        href="{{ route('packages.history') }}"
                        :active="request()->routeIs('packages.history')"
                    >
                        Mi Historial
                    </flux:navlist.item>
                </flux:navlist.group>

                @can('access-reports')
                    <flux:navlist.group heading="Reportes" expandable>
                        <flux:navlist.item 
                            icon="chart-bar" 
                            href="{{ route('reports.index') }}"
                            :active="request()->routeIs('reports.*')"
                        >
                            Generar Reporte
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="presentation-chart-line" 
                            href="#"
                        >
                            Estadísticas
                        </flux:navlist.item>
                    </flux:navlist.group>
                @endcan
            @endif
        </flux:navlist>
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t">
        <div class="text-xs text-gray-500 text-center">
            <p>&copy; {{ date('Y') }} SISNOTICE</p>
            <p class="mt-1">Software Solutions</p>
        </div>
    </div>
</aside>
