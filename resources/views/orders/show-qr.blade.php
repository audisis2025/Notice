{{--
/**
 * Nombre de la vista           : show-qr.blade.php
 * Descripción de la vista      : Modal/página que muestra el QR de asociación
 *                                inmediatamente después de crear la orden
 * Fecha de creación            : 15/01/2026
 * Elaboró                      : Jesús Núñez
 * Versión                      : 1.0
 */
--}}
<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="min-h-screen flex items-center justify-center p-6 bg-gray-50 dark:bg-zinc-900">
        <div class="max-w-2xl w-full">
            {{-- Tarjeta principal --}}
            <div
                class="bg-white dark:bg-zinc-800 rounded-2xl shadow-xl border border-gray-200 dark:border-zinc-700 overflow-hidden">

                {{-- Header --}}
                <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-center">
                    <div
                        class="inline-flex items-center justify-center w-20 h-20 bg-white/20 rounded-full mb-4 backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-10 h-10 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-bold text-white mb-2">
                        ¡Orden Creada!
                    </h1>
                    <p class="text-green-100 text-lg">
                        Orden #{{ $order->order_number }}
                    </p>
                </div>

                {{-- Contenido --}}
                <div class="p-8">
                    {{-- Instrucciones --}}
                    <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-500 p-6 rounded-r-lg mb-8">
                        <div class="flex items-start">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor"
                                class="w-6 h-6 text-blue-600 dark:text-blue-400 mr-3 flex-shrink-0 mt-0.5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                            </svg>
                            <div>
                                <h3 class="font-bold text-blue-800 dark:text-blue-300 mb-2">
                                    📱 Escanea este QR con el celular del cliente
                                </h3>
                                <ol class="space-y-2 text-sm text-blue-700 dark:text-blue-400">
                                    <li class="flex items-start">
                                        <span class="font-bold mr-2">1.</span>
                                        <span>El cliente abre la cámara de su celular</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="font-bold mr-2">2.</span>
                                        <span>Escanea el código QR</span>
                                    </li>
                                    <li class="flex items-start">
                                        <span class="font-bold mr-2">3.</span>
                                        <span>Se registra automáticamente y vincula la orden</span>
                                    </li>
                                </ol>
                            </div>
                        </div>
                    </div>

                    {{-- QR Code --}}
                    <div class="text-center mb-8">
                        <div
                            class="inline-block bg-white p-6 rounded-2xl shadow-lg border-4 border-gray-200 dark:border-zinc-600">
                            @if ($order->qr_code)
                                <img src="{{ asset('storage/' . $order->qr_code) }}" alt="QR de Asociación"
                                    class="w-80 h-80 mx-auto" id="qrImage">
                            @else
                                <div
                                    class="w-80 h-80 flex items-center justify-center bg-gray-100 dark:bg-zinc-700 rounded-lg">
                                    <p class="text-gray-500 dark:text-gray-400">QR no disponible</p>
                                </div>
                            @endif
                        </div>
                        <p class="text-sm text-gray-500 dark:text-gray-400 mt-4">
                            Orden #{{ $order->order_number }}
                        </p>
                    </div>

                    {{-- Información adicional --}}
                    <div class="bg-gray-50 dark:bg-zinc-900 rounded-lg p-6 mb-8">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Estado:</span>
                                <span class="font-semibold text-gray-900 dark:text-white ml-2">
                                    {{ ucfirst($order->status) }}
                                </span>
                            </div>
                            <div>
                                <span class="text-gray-600 dark:text-gray-400">Creada:</span>
                                <span class="font-semibold text-gray-900 dark:text-white ml-2">
                                    {{ $order->created_at->format('H:i') }}
                                </span>
                            </div>
                        </div>
                    </div>

                    {{-- Botones de acción --}}
                    <div class="flex flex-col sm:flex-row gap-4">
                        <button onclick="printQR()"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M6.72 13.829c-.24.03-.48.062-.72.096m.72-.096a42.415 42.415 0 0110.56 0m-10.56 0L6.34 18m10.94-4.171c.24.03.48.062.72.096m-.72-.096L17.66 18m0 0l.229 2.523a1.125 1.125 0 01-1.12 1.227H7.231c-.662 0-1.18-.568-1.12-1.227L6.34 18m11.318 0h1.091A2.25 2.25 0 0021 15.75V9.456c0-1.081-.768-2.015-1.837-2.175a48.055 48.055 0 00-1.913-.247M6.34 18H5.25A2.25 2.25 0 013 15.75V9.456c0-1.081.768-2.015 1.837-2.175a48.041 48.041 0 011.913-.247m10.5 0a48.536 48.536 0 00-10.5 0m10.5 0V3.375c0-.621-.504-1.125-1.125-1.125h-8.25c-.621 0-1.125.504-1.125 1.125v3.659M18 10.5h.008v.008H18V10.5zm-3 0h.008v.008H15V10.5z" />
                            </svg>
                            Imprimir QR
                        </button>

                        <a href="{{ route('orders.index') }}"
                            class="flex-1 inline-flex items-center justify-center px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            Continuar
                        </a>
                    </div>

                    {{-- Nota --}}
                    <div
                        class="mt-6 p-4 bg-yellow-50 dark:bg-yellow-900/20 border border-yellow-200 dark:border-yellow-800 rounded-lg">
                        <p class="text-xs text-yellow-800 dark:text-yellow-300 text-center">
                            💡 El cliente podrá ver el estado de su orden en tiempo real una vez escaneado el QR
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function printQR() {
                const qrImage = document.getElementById('qrImage');
                const orderNumber = '{{ $order->order_number }}';

                // Crear ventana de impresión
                const printWindow = window.open('', '_blank');
                printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                <head>
                    <title>QR - Orden ${orderNumber}</title>
                    <style>
                        body {
                            font-family: Arial, sans-serif;
                            display: flex;
                            justify-content: center;
                            align-items: center;
                            min-height: 100vh;
                            margin: 0;
                            padding: 20px;
                        }
                        .container {
                            text-align: center;
                        }
                        img {
                            max-width: 400px;
                            margin: 20px 0;
                        }
                        h1 {
                            font-size: 24px;
                            margin-bottom: 10px;
                        }
                        .order-number {
                            font-size: 32px;
                            font-weight: bold;
                            color: #059669;
                            margin: 20px 0;
                        }
                        .instructions {
                            margin-top: 30px;
                            font-size: 14px;
                            color: #666;
                        }
                        @media print {
                            body {
                                padding: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="container">
                        <h1>🧺 {{ Auth::user()->business->business_name }}</h1>
                        <div class="order-number">Orden #${orderNumber}</div>
                        <img src="${qrImage.src}" alt="QR Code">
                        <div class="instructions">
                            <p><strong>Instrucciones:</strong></p>
                            <p>1. Escanea este código con la cámara de tu celular</p>
                            <p>2. Sigue el enlace que aparece</p>
                            <p>3. Registra tus datos para recibir actualizaciones</p>
                        </div>
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                        }
                    <\/script>
                </body>
                </html>
            `);
                printWindow.document.close();
            }

            // Auto-refresh para verificar si se escaneó
            let checkInterval = setInterval(function() {
                fetch('{{ route('orders.check-scanned', $order) }}')
                    .then(response => response.json())
                    .then(data => {
                        if (data.scanned) {
                            clearInterval(checkInterval);
                            showSuccess('¡QR escaneado! El cliente ha sido vinculado.');
                            setTimeout(() => {
                                window.location.href = '{{ route('orders.index') }}';
                            }, 2000);
                        }
                    })
                    .catch(err => console.log('Checking...'));
            }, 3000); // Verificar cada 3 segundos

            // Limpiar intervalo al salir
            window.addEventListener('beforeunload', function() {
                clearInterval(checkInterval);
            });
        </script>
    @endpush
</x-layouts.app.sidebar>
