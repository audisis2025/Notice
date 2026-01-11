{{-- 
/**
 * Nombre de la vista           : index.blade.php
 * Descripción de la vista      : Vista principal para gestión de cupones
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

<x-layouts.app.sidebar :title="__('Cupones')">
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            Gestión de Cupones
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Header con botón crear -->
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                            Lista de Cupones
                        </h3>
                        <a href="{{ route('coupons.create') }}"
                            class="inline-flex items-center px-4 py-2 bg-black border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-900">
                            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                            Crear Cupón
                        </a>
                    </div>

                    <!-- Filtros -->
                    <div class="mb-6">
                        <form method="GET" action="{{ route('coupons.index') }}" class="flex items-center space-x-4">
                            <select name="status"
                                class="rounded-md border-gray-300 shadow-sm focus:border-black focus:ring-black"
                                onchange="this.form.submit()">
                                <option value="">Todos los cupones</option>
                                <option value="available" {{ request('status') === 'available' ? 'selected' : '' }}>
                                    Disponibles
                                </option>
                                <option value="used" {{ request('status') === 'used' ? 'selected' : '' }}>
                                    Usados
                                </option>
                                <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>
                                    Expirados
                                </option>
                            </select>

                            @if (request('status'))
                                <a href="{{ route('coupons.index') }}"
                                    class="text-sm text-gray-600 hover:text-gray-900">
                                    Limpiar filtro
                                </a>
                            @endif
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.app.sidebar>
