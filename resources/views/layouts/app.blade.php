{{-- 
/**
 * Nombre de la vista           : app.blade.php
 * Descripción de la vista      : Layout principal del sistema para usuarios
 *                                autenticados
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
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'SISNOTICE') }} - @yield('title', 'Sistema de Notificaciones')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @livewireStyles
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen flex">
        <!-- Sidebar -->
        @include('layouts.navigation')

        <!-- Main Content -->
        <div class="flex-1 flex flex-col">
            <!-- Top Bar -->
            <header class="bg-white shadow-sm z-10">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between items-center">
                    <!-- Page Title -->
                    <h1 class="text-2xl font-bold text-gray-900">
                        @yield('page-title', 'Dashboard')
                    </h1>

                    <!-- User Menu -->
                    <div class="flex items-center space-x-4">
                        <!-- Notifications -->
                        <button class="relative p-2 text-gray-600 hover:text-gray-900">
                            <x-heroicon-o-bell class="w-6 h-6" />
                            <span class="absolute top-0 right-0 block h-2 w-2 rounded-full bg-red-500"></span>
                        </button>

                        <!-- User Dropdown -->
                        <flux:dropdown>
                            <flux:button variant="ghost" icon-trailing="chevron-down">
                                <div class="flex items-center">
                                    <x-heroicon-o-user-circle class="w-8 h-8 text-gray-600 mr-2" />
                                    <span class="text-sm font-medium">{{ auth()->user()->name }}</span>
                                </div>
                            </flux:button>

                            <flux:menu>
                                <flux:menu.item icon="user" href="{{ route('profile.edit') }}">
                                    Mi Perfil
                                </flux:menu.item>
                                
                                <flux:menu.item icon="cog-6-tooth" href="#">
                                    Configuración
                                </flux:menu.item>

                                <flux:menu.separator />

                                <flux:menu.item 
                                    icon="arrow-right-on-rectangle"
                                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
                                    Cerrar Sesión
                                </flux:menu.item>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                                    @csrf
                                </form>
                            </flux:menu>
                        </flux:dropdown>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto p-6">
                <!-- Flash Messages -->
                @if (session('success'))
                    <div class="mb-4">
                        <flux:alert variant="success" closable>
                            <x-heroicon-o-check-circle class="w-5 h-5" />
                            {{ session('success') }}
                        </flux:alert>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-4">
                        <flux:alert variant="danger" closable>
                            <x-heroicon-o-x-circle class="w-5 h-5" />
                            {{ session('error') }}
                        </flux:alert>
                    </div>
                @endif

                @if (session('warning'))
                    <div class="mb-4">
                        <flux:alert variant="warning" closable>
                            <x-heroicon-o-exclamation-triangle class="w-5 h-5" />
                            {{ session('warning') }}
                        </flux:alert>
                    </div>
                @endif

                @if (session('info'))
                    <div class="mb-4">
                        <flux:alert variant="info" closable>
                            <x-heroicon-o-information-circle class="w-5 h-5" />
                            {{ session('info') }}
                        </flux:alert>
                    </div>
                @endif

                {{ $slot }}
            </main>
        </div>
    </div>

    @livewireScripts
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Custom Scripts -->
    @stack('scripts')
</body>
</html>