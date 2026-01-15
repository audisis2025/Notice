{{--
/**
 * Nombre de la vista           : create.blade.php
 * Descripción de la vista      : Formulario de creación de cupones
 *                                Con estilo estándar de la aplicación
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Sistema
 * Versión                      : 2.0
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Actualización a estándar visual
 */
--}}
<x-layouts.app.sidebar>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black dark:text-white">
                {{ __('Generar Nuevo Cupón') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Configure los parámetros del cupón de descuento
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-xl font-bold text-black dark:text-white mb-6">
                    {{ __('Información del Cupón') }}
                </h2>

                <form action="{{ route('coupons.store') }}" method="POST" class="space-y-6" id="couponForm">
                    @csrf

                    {{-- Código del cupón --}}
                    <div>
                        <label for="code" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Código del Cupón <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="code" 
                            id="code"
                            value="{{ old('code', strtoupper(Str::random(8))) }}"
                            required
                            maxlength="50"
                            placeholder="Ej: PROMO2025"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('code') border-red-500 @enderror"
                        />
                        @error('code')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                El código es único y no distingue entre mayúsculas y minúsculas
                            </p>
                        @enderror
                    </div>

                    {{-- Porcentaje de descuento --}}
                    <div>
                        <label for="discount_percentage" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Porcentaje de Descuento <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <input 
                                type="number" 
                                name="discount_percentage" 
                                id="discount_percentage"
                                value="{{ old('discount_percentage') }}"
                                required
                                min="1"
                                max="100"
                                placeholder="Ej: 20"
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('discount_percentage') border-red-500 @enderror"
                            />
                            <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                <span class="text-gray-500 dark:text-gray-400">%</span>
                            </div>
                        </div>
                        @error('discount_percentage')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Ingrese un valor entre 1 y 100
                            </p>
                        @enderror
                    </div>

                    {{-- Fecha de expiración --}}
                    <div>
                        <label for="expiration_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Fecha de Expiración <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="date" 
                            name="expiration_date" 
                            id="expiration_date"
                            value="{{ old('expiration_date', now()->addDays(30)->format('Y-m-d')) }}"
                            min="{{ now()->format('Y-m-d') }}"
                            required
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('expiration_date') border-red-500 @enderror"
                        />
                        @error('expiration_date')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                El cupón expirará a las 23:59 de esta fecha
                            </p>
                        @enderror
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
                                Cupón activo (puede ser utilizado inmediatamente)
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
                                <p class="font-semibold text-blue-800 dark:text-blue-300 mb-2">Características del cupón:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-700 dark:text-blue-400">
                                    <li>El cupón puede usarse una sola vez</li>
                                    <li>Es válido solo para la contratación de paquetes</li>
                                    <li>No se puede combinar con otras promociones</li>
                                    <li>Una vez usado, no puede reutilizarse</li>
                                </ul>
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
                                Generar Cupón
                            </button>
                            <a href="{{ route('coupons.index') }}"
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
                const form = document.getElementById('couponForm');
                const codeInput = document.getElementById('code');

                // Convertir código a mayúsculas automáticamente
                codeInput.addEventListener('input', function() {
                    this.value = this.value.toUpperCase();
                });

                // Validación antes de enviar
                form.addEventListener('submit', function(e) {
                    const code = codeInput.value.trim();
                    const discount = document.getElementById('discount_percentage').value;
                    const expirationDate = document.getElementById('expiration_date').value;

                    // Validar código
                    if (code.length === 0) {
                        e.preventDefault();
                        showWarning('Debes ingresar un código de cupón.', 'Campo requerido');
                        codeInput.focus();
                        return false;
                    }

                    // Validar descuento
                    if (discount < 1 || discount > 100) {
                        e.preventDefault();
                        showWarning('El descuento debe estar entre 1% y 100%.', 'Valor inválido');
                        document.getElementById('discount_percentage').focus();
                        return false;
                    }

                    // Validar fecha
                    const today = new Date().toISOString().split('T')[0];
                    if (expirationDate < today) {
                        e.preventDefault();
                        showWarning('La fecha de expiración no puede ser en el pasado.', 'Fecha inválida');
                        document.getElementById('expiration_date').focus();
                        return false;
                    }

                    // Si todo está bien, mostrar loading
                    Swal.fire({
                        title: 'Generando cupón...',
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