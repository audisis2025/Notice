<?php
/**
 * Nombre de la vista           : available.blade.php
 * Descripción de la vista      : Muestra paquetes disponibles para contratar
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
?>

<x-layouts.app.sidebar :title="__('Paquetes Disponibles')">
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            @if($currentPackage)
                <div class="mb-6 bg-green-50 dark:bg-green-900/20 border border-green-200 dark:border-green-800 rounded-lg p-4">
                    <p class="text-green-800 dark:text-green-200">
                        <strong>Paquete actual:</strong> {{ $currentPackage->package->name }} 
                        (Expira: {{ $currentPackage->end_date->format('d/m/Y') }})
                    </p>
                </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100 mb-6">
                    Elige el paquete perfecto para tu negocio
                </h3>

                <div class="grid md:grid-cols-3 gap-6">
                    @foreach($packages as $package)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-6 hover:shadow-lg transition">
                            <h4 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                {{ $package->name }}
                            </h4>

                            @if($package->description)
                                <p class="text-sm text-gray-600 dark:text-gray-400 mb-4">
                                    {{ $package->description }}
                                </p>
                            @endif

                            <div class="text-3xl font-bold text-gray-900 dark:text-gray-100 mb-4">
                                ${{ number_format($package->price, 2) }}
                                <span class="text-sm font-normal text-gray-500">
                                    / {{ $package->duration_days }} días
                                </span>
                            </div>

                            <form method="POST" action="{{ route('packages.subscribe', $package) }}">
                                @csrf
                                <button 
                                    type="submit"
                                    class="w-full px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-900 transition"
                                >
                                    Contratar paquete
                                </button>
                            </form>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @if (session('success'))
            Swal.fire({
                icon: 'success',
                title: '¡Éxito!',
                text: '{{ session("success") }}',
                confirmButtonText: 'Continuar',
                confirmButtonColor: '#10b981'
            });
        @endif

        @if (session('error'))
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: '{{ session("error") }}',
                confirmButtonText: 'Entendido',
                confirmButtonColor: '#dc2626'
            });
        @endif
    </script>
    @endpush
</x-layouts.app.sidebar>