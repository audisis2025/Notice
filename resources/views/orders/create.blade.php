{{--
/**
 * Nombre de la vista           : create.blade.php
 * Descripción de la vista      : Formulario de creación de órdenes
 *                                Con submit correcto y alertas SweetAlert
 * Fecha de creación            : 14/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 14/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 5.1
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Corrección de submit y alertas
 */
-->>
--}}
<x-layouts.app.sidebar>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black dark:text-white">
                {{ __('Registrar Nueva Orden') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Ingresa el número de orden para iniciar el registro</p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-xl font-bold text-black dark:text-white mb-6">
                    {{ __('Información de la Orden') }}
                </h2>

                <form action="{{ route('orders.store') }}" method="POST" class="space-y-6" id="orderForm">
                    @csrf

                    <div>
                        <label for="order_number" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                            Número de Orden <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            name="order_number" 
                            id="order_number"
                            value="{{ old('order_number') }}"
                            required
                            autofocus
                            placeholder="Ej: 001, A-001, ORD-2026-001"
                            class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('order_number') border-red-500 @enderror"
                        />
                        @error('order_number')
                            <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                        @else
                            <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                Usa el formato que prefieras (números, letras, guiones, etc.)
                            </p>
                        @enderror
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
            <p class="font-semibold text-blue-800 dark:text-blue-300 mb-2">¿Qué sucede después?</p>
            <ol class="list-decimal list-inside space-y-1 text-blue-700 dark:text-blue-400">
                <li>La orden se creará en estado "Pendiente"</li>
                <li>Podrás marcarla como "Pagada" para generar el QR</li>
                <li>El cliente escanea el QR para vincular la orden</li>
                <li>Marca como "Lista" cuando esté terminada</li>
                <li>El cliente confirma la entrega con el QR final</li>
            </ol>
        </div>
    </div>
</div>

{{-- Ejemplos de formato --}}
<div class="bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
    <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
        Ejemplos de formatos válidos:
    </p>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-2 text-sm">
        <div
            class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 rounded px-3 py-2 font-mono text-center text-gray-900 dark:text-white">
            001
        </div>
        <div
            class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 rounded px-3 py-2 font-mono text-center text-gray-900 dark:text-white">
            A-001
        </div>
        <div
            class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 rounded px-3 py-2 font-mono text-center text-gray-900 dark:text-white">
            ORD-001
        </div>
        <div
            class="bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 rounded px-3 py-2 font-mono text-center text-gray-900 dark:text-white">
            LV-2026-01
        </div>
    </div>
</div>

<div class="flex justify-end items-center pt-6 border-t border-gray-200 dark:border-zinc-700">
    <div class="flex space-x-4">
        <button type="submit"
            class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                stroke="currentColor" class="w-5 h-5 mr-2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17.25 8.25L21 12m0 0l-3.75 3.75M21 12H3" />
            </svg>
            Confirmar
        </button>
        <a href="{{ route('orders.index') }}"
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
            const form = document.getElementById('orderForm');
            const orderNumberInput = document.getElementById('order_number');

            // Convertir automáticamente a mayúsculas
            orderNumberInput.addEventListener('input', function() {
                this.value = this.value.toUpperCase();
            });

            // Validación antes de enviar
            form.addEventListener('submit', function(e) {
                const orderNumber = orderNumberInput.value.trim();

                // Validar que no esté vacío
                if (orderNumber.length === 0) {
                    e.preventDefault();
                    showWarning('Debes ingresar un número de orden.', 'Campo requerido');
                    orderNumberInput.focus();
                    return false;
                }

                // Si todo está bien, mostrar loading
                Swal.fire({
                    title: 'Creando orden...',
                    text: 'Por favor espera',
                    allowOutsideClick: false,
                    allowEscapeKey: false,
                    showConfirmButton: false,
                    willOpen: () => {
                        Swal.showLoading();
                    }
                });

                // Permitir que el form se envíe normalmente
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

            0%,
            100% {
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
