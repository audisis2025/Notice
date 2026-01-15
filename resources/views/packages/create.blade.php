{{--
/**
 * Nombre de la vista           : create.blade.php
 * Descripción de la vista      : Formulario de creación de paquetes
 *                                Con estilo estándar de la aplicación
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Sistema
 * Versión                      : 2.0
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Actualización a estándar visual
 */
-->>
--}}
<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black dark:text-white">
                {{ __('Crear Nuevo Paquete') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Configure las características del nuevo paquete comercial
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-xl font-bold text-black dark:text-white mb-6">
                    {{ __('Información del Paquete') }}
                </h2>

                <form action="{{ route('packages.store') }}" method="POST" class="space-y-6" id="packageForm">
                    @csrf

                    {{-- Nombre --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Nombre del Paquete <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="name" 
                            id="name"
                            value="{{ old('name') }}"
                            required
                            placeholder="Ej: Paquete Premium"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('name') border-red-500 @enderror"
                        />
                        @error('name')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Descripción --}}
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Descripción (opcional)
                        </label>
                        <textarea 
                            name="description" 
                            id="description" 
                            rows="3"
                            placeholder="Describe las características principales del paquete..."
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                        @error('description')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Precio y duración --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="price" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Precio <span class="text-red-500">*</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                                    <span class="text-gray-500 dark:text-gray-400">$</span>
                                </div>
                                <input 
                                    type="number" 
                                    name="price" 
                                    id="price"
                                    value="{{ old('price') }}"
                                    required
                                    step="0.01"
                                    min="0"
                                    placeholder="0.00"
                                    class="w-full pl-8 pr-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('price') border-red-500 @enderror"
                                />
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="duration_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Duración (días) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="duration_days" 
                                id="duration_days"
                                value="{{ old('duration_days', 30) }}"
                                required
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('duration_days') border-red-500 @enderror"
                            />
                            @error('duration_days')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Órdenes máximas y retención --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="max_orders" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Órdenes Máximas
                            </label>
                            <input 
                                type="number" 
                                name="max_orders" 
                                id="max_orders"
                                value="{{ old('max_orders') }}"
                                min="1"
                                placeholder="Ej: 100 (vacío = ilimitado)"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('max_orders') border-red-500 @enderror"
                            />
                            @error('max_orders')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Si se deja vacío, el paquete permitirá órdenes ilimitadas
                                </p>
                            @enderror
                        </div>

                        <div>
                            <label for="data_retention_days" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Retención de Datos (días) <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="number" 
                                name="data_retention_days" 
                                id="data_retention_days"
                                value="{{ old('data_retention_days', 365) }}"
                                required
                                min="1"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('data_retention_days') border-red-500 @enderror"
                            />
                            @error('data_retention_days')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @else
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                    Tiempo que se mantienen los datos históricos
                                </p>
                            @enderror
                        </div>
                    </div>

                    {{-- Características --}}
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-6">
                        <h3 class="text-lg font-semibold text-black dark:text-white mb-4">
                            Características del Paquete
                        </h3>
                        <div class="space-y-3 bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                            <div class="flex items-center">
                                <input 
                                    type="hidden" 
                                    name="has_reports" 
                                    value="0"
                                />
                                <input 
                                    type="checkbox" 
                                    name="has_reports" 
                                    id="has_reports" 
                                    value="1"
                                    {{ old('has_reports', false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-gray-900 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2"
                                />
                                <label for="has_reports" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Acceso a reportes avanzados
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input 
                                    type="hidden" 
                                    name="has_statistics" 
                                    value="0"
                                />
                                <input 
                                    type="checkbox" 
                                    name="has_statistics" 
                                    id="has_statistics" 
                                    value="1"
                                    {{ old('has_statistics', false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-gray-900 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2"
                                />
                                <label for="has_statistics" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Estadísticas detalladas
                                </label>
                            </div>

                            <div class="flex items-center">
                                <input 
                                    type="hidden" 
                                    name="has_filters" 
                                    value="0"
                                />
                                <input 
                                    type="checkbox" 
                                    name="has_filters" 
                                    id="has_filters" 
                                    value="1"
                                    {{ old('has_filters', false) ? 'checked' : '' }}
                                    class="w-4 h-4 text-gray-900 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2"
                                />
                                <label for="has_filters" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                    Filtros avanzados
                                </label>
                            </div>
                        </div>
                    </div>

                    {{-- Estado activo --}}
                    <div class="bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                        <div class="flex items-center">
                            <input 
                                type="hidden" 
                                name="is_active" 
                                value="0"
                            />
                            <input 
                                type="checkbox" 
                                name="is_active" 
                                id="is_active" 
                                value="1"
                                {{ old('is_active', true) ? 'checked' : '' }}
                                class="w-4 h-4 text-gray-900 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2"
                            />
                            <label for="is_active" class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                Paquete activo (disponible para contratación inmediatamente)
                            </label>
                        </div>
                    </div>

                    {{-- Información adicional --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 p-4 rounded">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <div class="text-sm">
                                <p class="font-semibold text-blue-800 dark:text-blue-300 mb-2">Nota importante:</p>
                                <p class="text-blue-700 dark:text-blue-400">
                                    Los paquetes activos estarán disponibles inmediatamente para que los negocios los contraten. 
                                    Asegúrate de configurar correctamente todas las características antes de activarlo.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end items-center pt-6 border-t border-gray-200 dark:border-zinc-700">
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Crear Paquete
                            </button>
                            <a href="{{ route('packages.index') }}"
                                class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                    stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('packageForm');

                // Validación antes de enviar
                form.addEventListener('submit', function(e) {
                    const name = document.getElementById('name').value.trim();
                    const price = parseFloat(document.getElementById('price').value);
                    const durationDays = parseInt(document.getElementById('duration_days').value);
                    const retentionDays = parseInt(document.getElementById('data_retention_days').value);

                    // Validar nombre
                    if (name.length === 0) {
                        e.preventDefault();
                        showWarning('Debes ingresar un nombre para el paquete.', 'Campo requerido');
                        document.getElementById('name').focus();
                        return false;
                    }

                    // Validar precio
                    if (isNaN(price) || price < 0) {
                        e.preventDefault();
                        showWarning('El precio debe ser mayor o igual a 0.', 'Valor inválido');
                        document.getElementById('price').focus();
                        return false;
                    }

                    // Validar duración
                    if (isNaN(durationDays) || durationDays < 1) {
                        e.preventDefault();
                        showWarning('La duración debe ser al menos 1 día.', 'Valor inválido');
                        document.getElementById('duration_days').focus();
                        return false;
                    }

                    // Validar retención
                    if (isNaN(retentionDays) || retentionDays < 1) {
                        e.preventDefault();
                        showWarning('La retención de datos debe ser al menos 1 día.', 'Valor inválido');
                        document.getElementById('data_retention_days').focus();
                        return false;
                    }

                    // Si todo está bien, mostrar loading
                    Swal.fire({
                        title: 'Creando paquete...',
                        text: 'Por favor espera',
                        allowOutsideClick: false,
                        allowEscapeKey: false,
                        showConfirmButton: false,
                        willOpen: () => {
                            Swal.showLoading();
                        }
                    });

                    return true;
                });

                // Mostrar alertas de sesión
                @if (session('success'))
                    showSuccess('{{ session('success') }}');
                @endif

                @if (session('error'))
                    showError('{{ session('error') }}');
                @endif

                @if ($errors->any())
                    showError(
                        '@foreach ($errors->all() as $error){{ $error }}<br>@endforeach',
                        'Error de validación');
                @endif
            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* Animación para campos con error */
            .border-red-500 {
                animation: shake 0.3s;
            }

            @keyframes shake {
                0%, 100% {
                    transform: translateX(0);
                }
                25% {
                    transform: translateX(-5px);
                }
                75% {
                    transform: translateX(5px);
                }
            }
        </style>
    @endpush
</x-layouts.app.sidebar>