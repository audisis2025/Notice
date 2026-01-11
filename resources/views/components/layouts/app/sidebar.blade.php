{{--
/**
 * Nombre de la vista           : sidebar.blade.php
 * Descripción de la vista      : Layout de barra lateral de navegación con menú principal,
 *                                perfil de usuario y navegación responsiva para escritorio y móvil
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
--}}

<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">
    <head>
        @include('partials.head')
    </head>
    <body class="min-h-screen bg-white dark:bg-zinc-800">
        <flux:sidebar sticky stashable class="border-e border-zinc-200 bg-zinc-50 dark:border-zinc-700 dark:bg-zinc-900">
            <flux:sidebar.toggle class="lg:hidden" icon="x-mark" />

            <a href="{{ route('dashboard') }}" class="me-5 flex items-center space-x-2 rtl:space-x-reverse px-4 py-4" wire:navigate>
                <div class="flex items-center gap-3">
                    <div class="flex aspect-square size-10 items-center justify-center rounded-md bg-black">
                        <x-heroicon-o-bell-alert class="size-6 text-white" />
                    </div>
                    <span class="text-xl font-bold text-gray-800 dark:text-gray-200">SISNOTICE</span>
                </div>
            </a>

            <flux:navlist variant="outline">
                <flux:navlist.group :heading="__('Menú')" class="grid">
                    <flux:navlist.item 
                        icon="home" 
                        :href="route('dashboard')" 
                        :current="request()->routeIs('dashboard')" 
                        wire:navigate
                    >
                        {{ __('Dashboard') }}
                    </flux:navlist.item>

                    @can('viewAny', App\Models\User::class)
                        <flux:navlist.item 
                            icon="users" 
                            :href="route('users.index')" 
                            :current="request()->routeIs('users.*')" 
                            wire:navigate
                        >
                            {{ __('Usuarios') }}
                        </flux:navlist.item>
                    @endcan

                    @can('viewAny', App\Models\Package::class)
                        <flux:navlist.item 
                            icon="cube" 
                            :href="route('packages.index')" 
                            :current="request()->routeIs('packages.*')" 
                            wire:navigate
                        >
                            {{ __('Paquetes') }}
                        </flux:navlist.item>
                    @endcan

                    @can('viewAny', App\Models\Business::class)
                        <flux:navlist.item 
                            icon="building-storefront" 
                            :href="route('businesses.index')" 
                            :current="request()->routeIs('businesses.*')" 
                            wire:navigate
                        >
                            {{ __('Negocios') }}
                        </flux:navlist.item>
                    @endcan

                    @can('viewAny', App\Models\Coupon::class)
                        <flux:navlist.item 
                            icon="ticket" 
                            :href="route('coupons.index')" 
                            :current="request()->routeIs('coupons.*')" 
                            wire:navigate
                        >
                            {{ __('Cupones') }}
                        </flux:navlist.item>
                    @endcan

                    @if(auth()->user()->role === 'BusinessAdministrator')
                        <flux:navlist.item 
                            icon="shopping-bag" 
                            :href="route('orders.index')" 
                            :current="request()->routeIs('orders.*')" 
                            wire:navigate
                        >
                            {{ __('Órdenes') }}
                        </flux:navlist.item>

                        <flux:navlist.item 
                            icon="chart-bar" 
                            :href="route('reports.index')" 
                            :current="request()->routeIs('reports.*')" 
                            wire:navigate
                        >
                            {{ __('Reportes') }}
                        </flux:navlist.item>
                    @endif
                </flux:navlist.group>
            </flux:navlist>

            <flux:spacer />

            <!-- Desktop User Menu -->
            <flux:dropdown class="hidden lg:block" position="top" align="start">
                <flux:profile
                    :name="auth()->user()->name"
                    :initials="auth()->user()->initials()"
                    :description="auth()->user()->email"
                    icon:trailing="chevrons-up-down"
                    data-test="sidebar-menu-button"
                />

                <flux:menu class="w-[220px]">
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Perfil') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:sidebar>

        <!-- Mobile User Menu -->
        <flux:header class="lg:hidden">
            <flux:sidebar.toggle class="lg:hidden" icon="bars-2" inset="left" />

            <flux:spacer />

            <flux:dropdown position="top" align="end">
                <flux:profile
                    :initials="auth()->user()->initials()"
                    icon-trailing="chevron-down"
                />

                <flux:menu>
                    <flux:menu.radio.group>
                        <div class="p-0 text-sm font-normal">
                            <div class="flex items-center gap-2 px-1 py-1.5 text-start text-sm">
                                <span class="relative flex h-8 w-8 shrink-0 overflow-hidden rounded-lg">
                                    <span class="flex h-full w-full items-center justify-center rounded-lg bg-neutral-200 text-black dark:bg-neutral-700 dark:text-white">
                                        {{ auth()->user()->initials() }}
                                    </span>
                                </span>

                                <div class="grid flex-1 text-start text-sm leading-tight">
                                    <span class="truncate font-semibold">{{ auth()->user()->name }}</span>
                                    <span class="truncate text-xs">{{ auth()->user()->email }}</span>
                                </div>
                            </div>
                        </div>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <flux:menu.radio.group>
                        <flux:menu.item :href="route('profile.edit')" icon="cog" wire:navigate>
                            {{ __('Perfil') }}
                        </flux:menu.item>
                    </flux:menu.radio.group>

                    <flux:menu.separator />

                    <form method="POST" action="{{ route('logout') }}" class="w-full">
                        @csrf
                        <flux:menu.item as="button" type="submit" icon="arrow-right-start-on-rectangle" class="w-full" data-test="logout-button">
                            {{ __('Cerrar Sesión') }}
                        </flux:menu.item>
                    </form>
                </flux:menu>
            </flux:dropdown>
        </flux:header>

        {{ $slot }}

        @fluxScripts
    </body>
</html>