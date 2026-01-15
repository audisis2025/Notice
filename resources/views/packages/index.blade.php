{{--
/**
 * Nombre de la vista           : index.blade.php
 * Descripción de la vista      : Vista principal para gestión de paquetes
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.1
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 2
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Actualización a estándar Flux UI del sistema
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */
--}}

<x-layouts.app.sidebar :title="__('Paquetes')">
<x-flash-messages />
    @if(session('success'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: '{{ session('success') }}',
                    confirmButtonColor: '#000000',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    @if(session('error'))
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: '{{ session('error') }}',
                    confirmButtonColor: '#dc2626',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endif

    <div class="max-w-7xl mx-auto p-6 space-y-6">

        {{-- Header con título y botón --}}
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-black dark:text-white">Paquetes</h1>
                <p class="text-gray-600 dark:text-gray-400 mt-2">
                    Gestión de paquetes de suscripción del sistema
                </p>
            </div>

            <flux:button 
                icon="plus" 
                icon-variant="outline" 
                :href="route('packages.create')" 
                variant="primary" 
                class="bg-black hover:bg-gray-900 text-white"
            >
                Crear Paquete
            </flux:button>
        </div>

        {{-- Tabla de paquetes --}}
        <div class="rounded-xl border border-zinc-200 dark:border-zinc-700 bg-white dark:bg-zinc-800">
            
            {{-- Encabezado de tabla --}}
            <div class="p-4 border-b border-zinc-200 dark:border-zinc-700">
                <flux:heading size="sm" class="text-black dark:text-white">
                    Lista de Paquetes
                </flux:heading>
            </div>

            {{-- Contenido de tabla --}}
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead class="bg-zinc-50 dark:bg-zinc-900">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Nombre
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Precio
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Duración
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Características
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Estado
                            </th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-zinc-500 dark:text-zinc-400 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-zinc-200 dark:divide-zinc-700">
                        @forelse($packages as $package)
                            <tr class="hover:bg-zinc-50 dark:hover:bg-zinc-900/50 transition-colors">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-black dark:text-white">
                                        {{ $package->name }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-black dark:text-white">
                                        ${{ number_format($package->price, 2) }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-black dark:text-white">
                                        {{ $package->duration_days }} días
                                    </div>
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex flex-wrap gap-1">
                                        @if($package->has_reports)
                                            <flux:badge size="sm" color="blue" variant="solid">
                                                Reportes
                                            </flux:badge>
                                        @endif
                                        @if($package->has_statistics)
                                            <flux:badge size="sm" color="purple" variant="solid">
                                                Estadísticas
                                            </flux:badge>
                                        @endif
                                        @if($package->has_filters)
                                            <flux:badge size="sm" color="green" variant="solid">
                                                Filtros
                                            </flux:badge>
                                        @endif
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if($package->is_active)
                                        <flux:badge size="sm" color="green" variant="solid">
                                            Activo
                                        </flux:badge>
                                    @else
                                        <flux:badge size="sm" color="zinc" variant="solid">
                                            Inactivo
                                        </flux:badge>
                                    @endif
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="flex justify-end gap-2">
                                        <flux:button 
                                            size="sm" 
                                            variant="ghost"
                                            :href="route('packages.edit', $package)"
                                            class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300"
                                        >
                                            Editar
                                        </flux:button>

                                        <form method="POST" action="{{ route('packages.toggle-status', $package) }}" class="inline">
                                            @csrf
                                            <flux:button 
                                                type="submit" 
                                                size="sm" 
                                                variant="ghost"
                                                class="text-zinc-600 hover:text-zinc-800 dark:text-zinc-400 dark:hover:text-zinc-300"
                                            >
                                                {{ $package->is_active ? 'Desactivar' : 'Activar' }}
                                            </flux:button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-12 text-center">
                                    <div class="flex flex-col items-center justify-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-12 h-12 text-zinc-400 dark:text-zinc-600 mb-3">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 7.5l-.625 10.632a2.25 2.25 0 01-2.247 2.118H6.622a2.25 2.25 0 01-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125z" />
                                        </svg>
                                        <p class="text-sm font-medium text-zinc-600 dark:text-zinc-400">
                                            No hay paquetes registrados
                                        </p>
                                        <p class="text-xs text-zinc-500 dark:text-zinc-500 mt-1">
                                            Crea tu primer paquete para comenzar
                                        </p>
                                    </div>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if($packages->hasPages())
                <div class="p-4 border-t border-zinc-200 dark:border-zinc-700">
                    {{ $packages->links() }}
                </div>
            @endif
        </div>

    </div>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    @endpush

</x-layouts.app.sidebar>