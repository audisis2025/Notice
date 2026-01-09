<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SISNOTICE - Sistema de Notificaciones</title>
    @vite(['resources/css/app.css'])
</head>
<body class="antialiased">
    <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100">
        {{-- Navbar --}}
        <nav class="bg-white shadow-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <div class="flex items-center">
                        <x-heroicon-o-bell-alert class="w-8 h-8 text-black mr-3" />
                        <span class="text-2xl font-bold text-black">SISNOTICE</span>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        @auth
                            <a href="{{ route('dashboard') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-black transition">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="px-4 py-2 text-sm font-medium text-gray-700 hover:text-black transition">
                                Iniciar Sesión
                            </a>
                            <a href="{{ route('register') }}" class="px-4 py-2 bg-black text-white text-sm font-medium rounded-lg hover:bg-gray-800 transition">
                                Registrarse
                            </a>
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Hero Section --}}
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center">
                <div class="flex justify-center mb-8">
                    <div class="p-6 bg-black rounded-full">
                        <x-heroicon-o-bell-alert class="w-20 h-20 text-white" />
                    </div>
                </div>
                
                <h1 class="text-5xl md:text-6xl font-bold text-gray-900 mb-6">
                    Sistema de Notificaciones
                    <span class="block text-black">para tu Negocio</span>
                </h1>
                
                <p class="text-xl text-gray-600 mb-8 max-w-3xl mx-auto">
                    Gestiona tus órdenes, mantén informados a tus clientes y mejora la experiencia
                    de servicio con nuestro sistema de notificaciones en tiempo real.
                </p>
                
                <div class="flex justify-center space-x-4">
                    <a href="{{ route('register') }}" class="px-8 py-4 bg-black text-white text-lg font-semibold rounded-lg hover:bg-gray-800 transition flex items-center">
                        <x-heroicon-o-rocket-launch class="w-6 h-6 mr-2" />
                        Comenzar Gratis
                    </a>
                    <a href="#features" class="px-8 py-4 bg-white text-black text-lg font-semibold rounded-lg border-2 border-black hover:bg-gray-50 transition flex items-center">
                        <x-heroicon-o-information-circle class="w-6 h-6 mr-2" />
                        Conocer Más
                    </a>
                </div>
            </div>
        </div>

        {{-- Features Section --}}
        <div id="features" class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-bold text-gray-900 mb-4">Características Principales</h2>
                <p class="text-xl text-gray-600">Todo lo que necesitas para gestionar tu negocio</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                {{-- Feature 1 --}}
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="p-3 bg-blue-100 rounded-lg inline-block mb-4">
                        <x-heroicon-o-qr-code class="w-8 h-8 text-blue-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Códigos QR</h3>
                    <p class="text-gray-600">
                        Genera códigos QR automáticamente para asociar órdenes con clientes
                        y confirmar entregas de manera segura.
                    </p>
                </div>

                {{-- Feature 2 --}}
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="p-3 bg-green-100 rounded-lg inline-block mb-4">
                        <x-heroicon-o-bell class="w-8 h-8 text-green-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Notificaciones en Tiempo Real</h3>
                    <p class="text-gray-600">
                        Mantén a tus clientes informados con notificaciones push cuando sus
                        órdenes estén listas.
                    </p>
                </div>

                {{-- Feature 3 --}}
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="p-3 bg-purple-100 rounded-lg inline-block mb-4">
                        <x-heroicon-o-chat-bubble-left-right class="w-8 h-8 text-purple-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Chat Integrado</h3>
                    <p class="text-gray-600">
                        Comunícate directamente con tus clientes cuando haya retrasos en
                        las entregas.
                    </p>
                </div>

                {{-- Feature 4 --}}
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="p-3 bg-yellow-100 rounded-lg inline-block mb-4">
                        <x-heroicon-o-star class="w-8 h-8 text-yellow-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Sistema de Calificaciones</h3>
                    <p class="text-gray-600">
                        Recibe feedback de tus clientes y mejora continuamente tu servicio.
                    </p>
                </div>

                {{-- Feature 5 --}}
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="p-3 bg-red-100 rounded-lg inline-block mb-4">
                        <x-heroicon-o-chart-bar class="w-8 h-8 text-red-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">Reportes Detallados</h3>
                    <p class="text-gray-600">
                        Analiza el rendimiento de tu negocio con reportes y estadísticas
                        avanzadas.
                    </p>
                </div>

                {{-- Feature 6 --}}
                <div class="bg-white rounded-lg shadow-lg p-8 hover:shadow-xl transition">
                    <div class="p-3 bg-gray-100 rounded-lg inline-block mb-4">
                        <x-heroicon-o-device-phone-mobile class="w-8 h-8 text-gray-600" />
                    </div>
                    <h3 class="text-xl font-bold text-gray-900 mb-3">App Móvil</h3>
                    <p class="text-gray-600">
                        Tus clientes pueden gestionar sus órdenes desde la aplicación móvil
                        iOS y Android.
                    </p>
                </div>
            </div>
        </div>

        {{-- Footer --}}
        <footer class="bg-gray-900 text-white py-12 mt-20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col md:flex-row justify-between items-center">
                    <div class="mb-4 md:mb-0">
                        <div class="flex items-center">
                            <x-heroicon-o-bell-alert class="w-8 h-8 mr-3" />
                            <span class="text-2xl font-bold">SISNOTICE</span>
                        </div>
                        <p class="mt-2 text-gray-400">Software Solutions</p>
                    </div>
                    
                    <div class="text-center md:text-right">
                        <p class="text-gray-400">&copy; {{ date('Y') }} SISNOTICE. Todos los derechos reservados.</p>
                        <p class="mt-1 text-sm text-gray-500">Sistema de Notificaciones de Órdenes</p>
                    </div>
                </div>
            </div>
        </footer>
    </div>
</body>
</html>