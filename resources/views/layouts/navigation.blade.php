{{-- 
/**
 * Nombre de la vista           : navigation.blade.php
 * Descripción de la vista      : Barra de navegación del sistema que varía
 *                                según el rol del usuario
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 13/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Reemplazo de componentes Flux por HTML/Tailwind
 * Responsable                  : Jesús Núñez
 * Revisor                      : 
 */
--}}
<aside class="w-64 bg-white shadow-lg flex flex-col h-full">
    <!-- Logo -->
    <div class="p-6 border-b">
        <div class="flex items-center justify-center">
            <x-heroicon-o-bell-alert class="w-8 h-8 text-black mr-2" />
            <h1 class="text-2xl font-bold text-black">SISNOTICE</h1>
        </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex-1 px-4 py-6 space-y-1 overflow-y-auto">
        <!-- Dashboard -->
        <a 
            href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                   {{ request()->routeIs('dashboard') 
                      ? 'bg-black text-white' 
                      : 'text-gray-700 hover:bg-gray-100' }}"
        >
            <x-heroicon-o-home class="w-5 h-5 mr-3" />
            Dashboard
        </a>

        @if(auth()->user()->isSuperAdministrator())
            <!-- Super Admin Menu -->
            
            <!-- Grupo: Administración -->
            <div class="mt-6 mb-2">
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Administración
                </h3>
            </div>

            <!-- Usuarios -->
            <a 
                href="{{ route('users.index') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('users.*') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-user-group class="w-5 h-5 mr-3" />
                Usuarios
            </a>

            <!-- Negocios -->
            <a 
                href="{{ route('businesses.index') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('businesses.*') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-building-office class="w-5 h-5 mr-3" />
                Negocios
            </a>

            <!-- Paquetes -->
            <a 
                href="{{ route('packages.index') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('packages.*') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-cube class="w-5 h-5 mr-3" />
                Paquetes
            </a>

            <!-- Cupones -->
            <a 
                href="{{ route('coupons.index') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('coupons.*') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-ticket class="w-5 h-5 mr-3" />
                Cupones
            </a>

            <!-- Grupo: Reportes -->
            <div class="mt-6 mb-2">
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Reportes
                </h3>
            </div>

            <!-- Estadísticas Globales -->
            <a 
                href="#"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       text-gray-700 hover:bg-gray-100"
            >
                <x-heroicon-o-chart-bar class="w-5 h-5 mr-3" />
                Estadísticas Globales
            </a>

            <!-- Reportes del Sistema -->
            <a 
                href="#"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       text-gray-700 hover:bg-gray-100"
            >
                <x-heroicon-o-document-text class="w-5 h-5 mr-3" />
                Reportes del Sistema
            </a>
        @endif

        @if(auth()->user()->isBusinessAdministrator())
            <!-- Business Admin Menu -->
            
            <!-- Grupo: Mi Negocio -->
            <div class="mt-6 mb-2">
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Mi Negocio
                </h3>
            </div>

            <!-- Datos del Negocio -->
            <a 
                href="{{ route('business.edit') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('business.*') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-building-storefront class="w-5 h-5 mr-3" />
                Datos del Negocio
            </a>

            <!-- Órdenes -->
            <a 
                href="{{ route('orders.index') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('orders.*') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-shopping-bag class="w-5 h-5 mr-3" />
                Órdenes
            </a>

            <!-- Calificaciones -->
            <a 
                href="{{ route('business.ratings') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('business.ratings') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-star class="w-5 h-5 mr-3" />
                Calificaciones
            </a>

            <!-- Grupo: Paquetes -->
            <div class="mt-6 mb-2">
                <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                    Paquetes
                </h3>
            </div>

            <!-- Contratar Paquete -->
            <a 
                href="{{ route('packages.available') }}"
                class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                       {{ request()->routeIs('packages.available') 
                          ? 'bg-black text-white' 
                          : 'text-gray-700 hover:bg-gray-100' }}"
            >
                <x-heroicon-o-cube-transparent class="w-5 h-5 mr-3" />
                Contratar Paquete
            </a>

            @can('access-reports')
                <!-- Grupo: Reportes -->
                <div class="mt-6 mb-2">
                    <h3 class="px-4 text-xs font-semibold text-gray-500 uppercase tracking-wider">
                        Reportes
                    </h3>
                </div>

                <!-- Generar Reporte -->
                <a 
                    href="{{ route('reports.index') }}"
                    class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                           {{ request()->routeIs('reports.*') 
                              ? 'bg-black text-white' 
                              : 'text-gray-700 hover:bg-gray-100' }}"
                >
                    <x-heroicon-o-chart-bar class="w-5 h-5 mr-3" />
                    Generar Reporte
                </a>

                <!-- Estadísticas -->
                <a 
                    href="#"
                    class="flex items-center px-4 py-3 text-sm rounded-lg transition-colors
                           text-gray-700 hover:bg-gray-100"
                >
                    <x-heroicon-o-presentation-chart-line class="w-5 h-5 mr-3" />
                    Estadísticas
                </a>
            @endcan
        @endif
    </nav>

    <!-- Footer -->
    <div class="p-4 border-t mt-auto">
        <div class="text-xs text-gray-500 text-center">
            <p>&copy; {{ date('Y') }} SISNOTICE</p>
            <p class="mt-1">Software Solutions</p>
        </div>
    </div>
</aside>