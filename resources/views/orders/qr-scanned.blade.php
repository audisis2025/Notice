{{--
/**
 * Vista que se muestra cuando el cliente escanea el QR exitosamente
 * resources/views/orders/qr-scanned.blade.php
 */
--}}
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>✅ Orden Registrada</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body class="bg-gradient-to-br from-green-50 to-emerald-100 min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full">
        {{-- Tarjeta de éxito --}}
        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">
            {{-- Animación de éxito --}}
            <div class="bg-gradient-to-r from-green-500 to-emerald-600 p-8 text-center">
                <div class="inline-flex items-center justify-center w-24 h-24 bg-white rounded-full mb-4 animate-bounce">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2"
                        stroke="currentColor" class="w-12 h-12 text-green-600">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <h1 class="text-3xl font-bold text-white mb-2">
                    ¡Registro Exitoso!
                </h1>
                <p class="text-green-100">
                    Tu orden ha sido vinculada
                </p>
            </div>

            {{-- Información de la orden --}}
            <div class="p-8">
                <div class="bg-gray-50 rounded-xl p-6 mb-6">
                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600 text-sm">Número de Orden</span>
                        <span class="text-2xl font-bold text-gray-900">#{{ $order->order_number }}</span>
                    </div>

                    <div class="flex items-center justify-between mb-4">
                        <span class="text-gray-600 text-sm">Negocio</span>
                        <span class="font-semibold text-gray-900">{{ $business->business_name }}</span>
                    </div>

                    <div class="flex items-center justify-between">
                        <span class="text-gray-600 text-sm">Estado</span>
                        <span
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                            {{ ucfirst($order->status) }}
                        </span>
                    </div>
                </div>

                {{-- Instrucciones --}}
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-6">
                    <div class="flex">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor" class="w-6 h-6 text-blue-600 mr-3 flex-shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                        </svg>
                        <div>
                            <h3 class="font-bold text-blue-900 mb-2">¿Qué sigue?</h3>
                            <ul class="text-sm text-blue-800 space-y-1">
                                <li>✅ Tu orden ha sido registrada exitosamente</li>
                                <li>📱 Puedes cerrar esta ventana</li>
                                <li>🔔 Recibirás notificaciones del estado</li>
                            </ul>
                        </div>
                    </div>
                </div>

                {{-- Información de contacto --}}
                @if ($business->phone)
                    <div class="bg-gray-50 rounded-lg p-4 mb-6">
                        <p class="text-sm text-gray-600 mb-2">¿Necesitas ayuda?</p>
                        <a href="tel:{{ $business->phone }}" class="flex items-center text-green-600 font-semibold">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            {{ $business->phone }}
                        </a>
                    </div>
                @endif

                {{-- Botón para cerrar --}}
                <button onclick="window.close()"
                    class="w-full bg-green-600 hover:bg-green-700 text-white font-semibold py-4 rounded-xl transition-colors">
                    Cerrar
                </button>

                {{-- Nota --}}
                <p class="text-xs text-gray-500 text-center mt-4">
                    Guarda este número de orden: <strong>#{{ $order->order_number }}</strong>
                </p>
            </div>
        </div>

        {{-- Información adicional --}}
        <div class="text-center mt-6">
            <p class="text-sm text-gray-600">
                Escaneado el {{ now()->format('d/m/Y H:i') }}
            </p>
        </div>
    </div>
</body>

</html>
