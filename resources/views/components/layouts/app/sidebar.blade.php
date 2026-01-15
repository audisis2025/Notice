{{--
/**
 * Nombre de la vista           : sidebar.blade.php
 * Descripción de la vista      : Layout de barra lateral de navegación con menú principal
 *                                y perfil de usuario (altura completa y fija)
 *                                Mi Paquete como item del menú
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.3
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 6
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Mi Paquete como item de menú, no card
 * Responsable                  : Sistema
 * Revisor                      : Jesús Núñez
 */
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark h-full">

<head>
    @include('partials.head')
</head>

<body class="h-full bg-white dark:bg-zinc-800">
    <div class="flex h-full">

        <!-- Sidebar fijo -->
        <div class="hidden lg:block fixed inset-y-0 left-0 z-30">
            <flux:sidebar sticky stashable
                class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900 h-screen w-64">
                <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

                <!-- LOGO (NO navegación SPA) -->
                <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse px-4 py-4">
                    <div class="flex items-center gap-3">
                        <div class="flex aspect-square size-10 items-center justify-center rounded-md bg-black">
                            <x-heroicon-o-bell-alert class="size-6 text-white" />
                        </div>
                        <span class="text-xl font-bold text-gray-800 dark:text-gray-200">SISNOTICE</span>
                    </div>
                </a>

                <flux:navlist variant="outline">
                    <flux:navlist.group :heading="__('Menú')" class="grid">

                        <!-- Dashboard (Blade normal) -->
                        <flux:navlist.item icon="home" :href="route('dashboard')"
                            :current="request()->routeIs('dashboard')" wire:navigate>
                            Inicio
                        </flux:navlist.item>

                        @can('viewAny', App\Models\User::class)
                            <flux:navlist.item icon="users" :href="route('users.index')"
                                :current="request()->routeIs('users.*')" wire:navigate>
                                Usuarios
                            </flux:navlist.item>
                        @endcan

                        @can('viewAny', App\Models\Package::class)
                            <flux:navlist.item icon="cube" :href="route('packages.index')"
                                :current="request()->routeIs('packages.*')" wire:navigate>
                                Paquetes
                            </flux:navlist.item>
                        @endcan

                        @can('viewAny', App\Models\Business::class)
                            <flux:navlist.item icon="building-storefront" :href="route('businesses.index')"
                                :current="request()->routeIs('businesses.*')" wire:navigate>
                                Negocios
                            </flux:navlist.item>
                        @endcan

                        @can('viewAny', App\Models\Coupon::class)
                            <flux:navlist.item icon="ticket" :href="route('coupons.index')"
                                :current="request()->routeIs('coupons.*')" wire:navigate>
                                Cupones
                            </flux:navlist.item>
                        @endcan

                        {{-- LIVEWIRE CLÁSICO → SIN wire:navigate --}}
                        @if (auth()->user()->role === 'BusinessAdministrator')
                            {{-- Verificar si tiene negocio y paquete --}}
                            @php
                                $business = auth()->user()->business;
                                $currentPackage = $business?->currentPackage();
                            @endphp

                            {{-- Órdenes y Reportes: SOLO si tiene paquete activo --}}
                            @if($currentPackage)
                                <flux:navlist.item icon="shopping-bag" :href="route('orders.index')"
                                    :current="request()->routeIs('orders.*')">
                                    Órdenes
                                </flux:navlist.item>

                                <flux:navlist.item icon="chart-bar" :href="route('reports.index')"
                                    :current="request()->routeIs('reports.*')">
                                    Reportes
                                </flux:navlist.item>
                            @endif

                            {{-- Mi Paquete: SIEMPRE visible para BusinessAdministrator con negocio --}}
                            @if($business)
                                @if($currentPackage)
                                    <flux:navlist.item icon="cube" :href="route('select.package')"
                                        :current="request()->routeIs('select.package')">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Mi Paquete</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900/30 dark:text-green-300">
                                                Activo
                                            </span>
                                        </div>
                                    </flux:navlist.item>
                                @else
                                    <flux:navlist.item icon="cube" :href="route('select.package')"
                                        :current="request()->routeIs('select.package')">
                                        <div class="flex items-center justify-between w-full">
                                            <span>Mi Paquete</span>
                                            <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900/30 dark:text-yellow-300">
                                                Sin paquete
                                            </span>
                                        </div>
                                    </flux:navlist.item>
                                @endif
                            @endif
                        @endif

                    </flux:navlist.group>
                </flux:navlist>

                <flux:spacer />

                <!-- Desktop User Menu -->
                <flux:dropdown class="hidden lg:block" position="top" align="start">
                    <flux:profile :name="auth()->user()->name" :initials="auth()->user()->initials()"
                        :description="auth()->user()->email" icon:trailing="chevrons-up-down" />

                    <flux:menu class="w-[220px]">
                        <flux:menu.separator />

                        <form method="POST" action="{{ route('logout') }}" class="w-full">
                            @csrf
                            <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle"
                                class="w-full">
                                Cerrar Sesión
                            </flux:menu.item>
                        </form>
                    </flux:menu>
                </flux:dropdown>

            </flux:sidebar>
        </div>

        <!-- Área principal -->
        <div class="flex-1 lg:ml-64 flex flex-col min-h-screen">
            <main class="flex-1">
                {{ $slot }}
            </main>
        </div>

    </div>

    @fluxScripts
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>

</html>