{{--
/**
 * Nombre de la vista           : index.blade.php
 * Descripción de la vista      : Formulario de generación de reportes
 *                                Con el mismo estilo de create order
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Sistema
 * Versión                      : 1.0
 */
--}}
<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black dark:text-white">
                {{ __('Generar Reporte de Órdenes') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">Selecciona el rango de fechas para generar un reporte detallado</p>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Total Órdenes</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow border border-blue-200 dark:border-blue-800 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 uppercase">Este Mes</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $currentMonthOrders }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 012.25-2.25h13.5A2.25 2.25 0 0121 7.5v11.25m-18 0A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75m-18 0v-7.5A2.25 2.25 0 015.25 9h13.5A2.25 2.25 0 0121 11.25v7.5" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Formulario de reporte --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="p-6">
                <h2 class="text-xl font-bold text-black dark:text-white mb-6">
                    {{ __('Configurar Reporte') }}
                </h2>

                <form action="{{ route('reports.generate') }}" method="POST" class="space-y-6" id="reportForm">
                    @csrf

                    {{-- Rango de fechas --}}
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Inicio <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="start_date" 
                                id="start_date"
                                value="{{ old('start_date', now()->startOfMonth()->format('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('start_date') border-red-500 @enderror"
                            />
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Fecha de Fin <span class="text-red-500">*</span>
                            </label>
                            <input 
                                type="date" 
                                name="end_date" 
                                id="end_date"
                                value="{{ old('end_date', now()->format('Y-m-d')) }}"
                                required
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('end_date') border-red-500 @enderror"
                            />
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Botones de rango rápido --}}
                    <div class="bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                        <p class="text-sm font-semibold text-gray-700 dark:text-gray-300 mb-3">
                            Rangos rápidos:
                        </p>
                        <div class="flex flex-wrap gap-2">
                            <button 
                                type="button"
                                onclick="setDateRange('today')"
                                class="px-4 py-2 text-sm bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                Hoy
                            </button>
                            <button 
                                type="button"
                                onclick="setDateRange('week')"
                                class="px-4 py-2 text-sm bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                Esta Semana
                            </button>
                            <button 
                                type="button"
                                onclick="setDateRange('month')"
                                class="px-4 py-2 text-sm bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                Este Mes
                            </button>
                            <button 
                                type="button"
                                onclick="setDateRange('last_month')"
                                class="px-4 py-2 text-sm bg-white dark:bg-zinc-800 border border-gray-200 dark:border-zinc-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors">
                                Mes Pasado
                            </button>
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
                                <p class="font-semibold text-blue-800 dark:text-blue-300 mb-2">El reporte incluirá:</p>
                                <ul class="list-disc list-inside space-y-1 text-blue-700 dark:text-blue-400">
                                    <li>Total de órdenes en el período seleccionado</li>
                                    <li>Ingresos totales y promedio por orden</li>
                                    <li>Distribución de órdenes por estado</li>
                                    <li>Detalle completo de cada orden</li>
                                    <li>Opción de exportar a CSV</li>
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
                                        d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 013 19.875v-6.75zM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V8.625zM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 01-1.125-1.125V4.125z" />
                                </svg>
                                Generar Reporte
                            </button>
                            <a href="{{ route('dashboard') }}"
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
                const form = document.getElementById('reportForm');
                const startDateInput = document.getElementById('start_date');
                const endDateInput = document.getElementById('end_date');

                // Validación antes de enviar
                form.addEventListener('submit', function(e) {
                    const startDate = new Date(startDateInput.value);
                    const endDate = new Date(endDateInput.value);

                    // Validar fechas
                    if (!startDateInput.value || !endDateInput.value) {
                        e.preventDefault();
                        showWarning('Debes seleccionar ambas fechas.', 'Campos requeridos');
                        return false;
                    }

                    if (startDate > endDate) {
                        e.preventDefault();
                        showWarning('La fecha de inicio no puede ser mayor a la fecha de fin.', 'Fechas inválidas');
                        return false;
                    }

                    // Si todo está bien, mostrar loading
                    Swal.fire({
                        title: 'Generando reporte...',
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

            function setDateRange(range) {
                const today = new Date();
                let startDate, endDate;

                switch(range) {
                    case 'today':
                        startDate = endDate = new Date(today);
                        break;
                    case 'week':
                        const firstDayOfWeek = new Date(today);
                        firstDayOfWeek.setDate(today.getDate() - today.getDay());
                        startDate = firstDayOfWeek;
                        endDate = new Date(today);
                        break;
                    case 'month':
                        startDate = new Date(today.getFullYear(), today.getMonth(), 1);
                        endDate = new Date(today);
                        break;
                    case 'last_month':
                        startDate = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                        endDate = new Date(today.getFullYear(), today.getMonth(), 0);
                        break;
                }

                document.getElementById('start_date').value = formatDate(startDate);
                document.getElementById('end_date').value = formatDate(endDate);
            }

            function formatDate(date) {
                return date.toISOString().split('T')[0];
            }
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