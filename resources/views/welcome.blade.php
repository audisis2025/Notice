{{-- 
/**
 * Nombre de la vista           : welcome.blade.php
 * Descripción de la vista      : Página principal del sistema donde se muestran los paquetes
 *                                disponibles y los enlaces para iniciar sesión o registrarse
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
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bienvenido - SISNOTICE</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-zinc-50 dark:bg-zinc-900 text-black dark:text-white flex flex-col min-h-screen">
    <header class="bg-white dark:bg-zinc-800 border-b border-zinc-200 dark:border-zinc-700 py-4 shadow-sm">
        <div class="w-full flex justify-between items-center px-6">
            <div class="flex items-center gap-3">
                <img src="{{ asset('images/logo_notice.png') }}" alt="SISNOTICE" class="h-12 w-auto rounded" />
                <flux:heading level="1" size="xl" class="text-2xl !font-bold text-black dark:text-white">
                    SISNOTICE
                </flux:heading>
            </div>

            <nav class="flex space-x-4">
                <flux:button icon="user-circle" icon-variant="outline" variant="primary" :href="route('login')"
                    class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-900">
                    Iniciar sesión
                </flux:button>

                <flux:button icon="user-plus" icon-variant="outline" variant="primary" :href="route('register')"
                    class="px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-900">
                    Registrarse
                </flux:button>
            </nav>
        </div>
    </header>

    <main class="flex-grow container mx-auto px-6 py-16 text-center">
        <flux:heading level="2" size="xl" class="text-4xl !font-extrabold mb-4">
            Bienvenido a SISNOTICE
        </flux:heading>

        <flux:text class="text-lg text-black/70 dark:text-white/70 mb-12">
            El sistema inteligente de notificaciones para negocios. Gestiona órdenes, notifica a tus clientes y optimiza
            tu servicio.
        </flux:text>

        <flux:heading level="3" size="lg" class="text-2xl font-semibold mb-8">
            Elige el paquete que mejor se adapte a tu negocio
        </flux:heading>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @forelse ($packages as $package)
                @php
                    $isFeatured =
                        str_contains(Str::lower($package->name), 'premium') ||
                        str_contains(Str::lower($package->name), 'pro') ||
                        $loop->index === 1;
                    $periodLabel = $package->duration_days === 30 ? 'mes' : $package->duration_days . ' días';
                @endphp

                <div
                    class="bg-white dark:bg-zinc-800 rounded-xl shadow-md p-6 flex flex-col {{ $isFeatured ? 'border-2 border-black' : 'border border-zinc-200 dark:border-zinc-700' }}">

                    {{-- Badge de destacado --}}
                    @if ($isFeatured)
                        <div class="mb-4">
                            <span class="bg-black text-white text-xs font-semibold px-3 py-1 rounded-full">
                                DESTACADO
                            </span>
                        </div>
                    @endif

                    <flux:heading level="4" size="lg"
                        class="text-xl font-bold mb-2 text-black dark:text-white">
                        {{ $package->name }}
                    </flux:heading>

                    <flux:text class="text-black/70 dark:text-white/70 flex-grow mb-4">
                        {{-- Descripción basada en características --}}
                        @if ($package->max_orders)
                            Hasta {{ number_format($package->max_orders) }} órdenes
                        @else
                            Órdenes ilimitadas
                        @endif
                    </flux:text>

                    {{-- Precio --}}
                    <flux:text variant="strong" class="mt-4 text-3xl font-bold text-black dark:text-white">
                        ${{ number_format($package->price, 2) }}
                        <span class="text-base text-black/60 dark:text-white/60">/{{ $periodLabel }}</span>
                    </flux:text>

                    {{-- Características --}}
                    <div class="mt-6 space-y-3 text-left text-sm text-black/70 dark:text-white/70">

                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            {{ $package->duration_days }} días de duración
                        </div>

                        @if ($package->has_reports)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Reportes avanzados
                            </div>
                        @endif

                        @if ($package->has_statistics)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Estadísticas detalladas
                            </div>
                        @endif

                        @if ($package->has_filters)
                            <div class="flex items-center">
                                <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M5 13l4 4L19 7" />
                                </svg>
                                Filtros avanzados
                            </div>
                        @endif

                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Retención de datos: {{ $package->data_retention_days }} días
                        </div>

                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Códigos QR ilimitados
                        </div>

                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Chat con clientes
                        </div>

                        <div class="flex items-center">
                            <svg class="w-4 h-4 text-green-600 mr-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M5 13l4 4L19 7" />
                            </svg>
                            Notificaciones automáticas
                        </div>

                    </div>

                </div>

            @empty
                <div class="col-span-1 md:col-span-3">
                    <div class="text-center text-black/60 dark:text-white/60 py-8">
                        <x-heroicon-o-inbox class="w-16 h-16 mx-auto mb-4 text-zinc-400" />
                        <flux:text class="text-black/60 dark:text-white/60">
                            Próximamente paquetes disponibles para tu negocio.
                        </flux:text>
                    </div>
                </div>
            @endforelse
        </div>
    </main>

    <footer class="bg-black dark:bg-zinc-800 border-t border-zinc-200 dark:border-zinc-700 text-center py-6 mt-auto">
        <flux:text variant="subtle" class="text-white dark:text-white/70 text-sm">
            © {{ date('Y') }} SISNOTICE. Todos los derechos reservados.
        </flux:text>

        <p class="mt-2">
            <flux:link href="#"
                class="text-white hover:text-gray-200 text-sm underline-offset-4 hover:underline" target="_blank"
                rel="noopener noreferrer">
                Términos y Condiciones
            </flux:link>
        </p>
    </footer>
</body>

</html>
