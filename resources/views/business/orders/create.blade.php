@extends('layouts.app')

@section('title', 'Nueva Orden')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Nueva Orden</h2>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <form method="POST" action="{{ route('business.orders.store') }}" id="orderForm">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Datos del Cliente -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Datos del Cliente (Opcional)</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nombre</label>
                        <input type="text" name="client_name" value="{{ old('client_name') }}"
                            class="w-full border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono</label>
                        <input type="text" name="client_phone" value="{{ old('client_phone') }}"
                            class="w-full border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                        <input type="email" name="client_email" value="{{ old('client_email') }}"
                            class="w-full border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>
                </div>

                <!-- Detalles de la Orden -->
                <div>
                    <h3 class="text-lg font-semibold mb-4">Detalles de la Orden</h3>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Monto Total *</label>
                        <input type="number" name="total_amount" value="{{ old('total_amount') }}" step="0.01"
                            min="0" required
                            class="w-full border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Notas</label>
                        <textarea name="notes" rows="4"
                            class="w-full border rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('notes') }}</textarea>
                    </div>
                </div>
            </div>

            <!-- Productos/Servicios -->
            <div class="mb-6">
                <h3 class="text-lg font-semibold mb-4">Productos/Servicios</h3>
                <div id="products-container">
                    <div class="product-item grid grid-cols-4 gap-4 mb-3">
                        <input type="text" name="products[0][name]" placeholder="Nombre"
                            class="border rounded-md px-3 py-2" required>
                        <input type="number" name="products[0][quantity]" placeholder="Cantidad" min="1"
                            value="1" class="border rounded-md px-3 py-2" required>
                        <input type="number" name="products[0][price]" placeholder="Precio" step="0.01" min="0"
                            class="border rounded-md px-3 py-2" required>
                        <button type="button" onclick="removeProduct(this)"
                            class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                            Eliminar
                        </button>
                    </div>
                </div>
                <button type="button" onclick="addProduct()"
                    class="mt-2 bg-green-500 text-white px-4 py-2 rounded-md hover:bg-green-600">
                    + Agregar Producto
                </button>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('business.orders.index') }}"
                    class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                    Cancelar
                </a>
                <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                    Crear Orden
                </button>
            </div>
        </form>
    </div>

    @push('scripts')
        <script>
            let productIndex = 1;

            function addProduct() {
                const container = document.getElementById('products-container');
                const newProduct = `
        <div class="product-item grid grid-cols-4 gap-4 mb-3">
            <input type="text" name="products[${productIndex}][name]" placeholder="Nombre" 
                   class="border rounded-md px-3 py-2" required>
            <input type="number" name="products[${productIndex}][quantity]" placeholder="Cantidad" 
                   min="1" value="1" class="border rounded-md px-3 py-2" required>
            <input type="number" name="products[${productIndex}][price]" placeholder="Precio" 
                   step="0.01" min="0" class="border rounded-md px-3 py-2" required>
            <button type="button" onclick="removeProduct(this)" class="bg-red-500 text-white px-4 py-2 rounded-md hover:bg-red-600">
                Eliminar
            </button>
        </div>
    `;
                container.insertAdjacentHTML('beforeend', newProduct);
                productIndex++;
            }

            function removeProduct(button) {
                if (document.querySelectorAll('.product-item').length > 1) {
                    button.closest('.product-item').remove();
                } else {
                    alert('Debe haber al menos un producto');
                }
            }
        </script>
    @endpush
@endsection

{{-- resources/views/business/orders/show.blade.php --}}
@extends('layouts.app')

@section('title', 'Detalle de Orden')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Orden {{ $order->order_code }}</h2>
        <div class="space-x-2">
            @if ($order->status !== 'delivered' && $order->status !== 'cancelled')
                <a href="{{ route('business.orders.edit', $order) }}"
                    class="bg-indigo-600 text-white px-4 py-2 rounded-md hover:bg-indigo-700">
                    Editar
                </a>
            @endif
            <button onclick="showQRModal()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                Ver QR
            </button>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Información Principal -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Estado y Acciones -->
            <div class="bg-white shadow rounded-lg p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-semibold">Estado Actual</h3>
                    <span
                        class="px-4 py-2 text-sm font-semibold rounded-full
                    {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                    {{ $order->status === 'ready' ? 'bg-blue-100 text-blue-800' : '' }}
                    {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                    {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                        {{ ucfirst($order->status) }}
                    </span>
                </div>

                @if ($order->status !== 'delivered' && $order->status !== 'cancelled')
                    <div class="flex flex-wrap gap-2 mt-4">
                        @if ($order->status === 'pending')
                            <form method="POST" action="{{ route('business.orders.change-status', $order) }}"
                                class="inline">
                                @csrf
                                <input type="hidden" name="status" value="ready">
                                <button type="submit"
                                    class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                                    Marcar como Lista
                                </button>
                            </form>
                        @endif

                        @if ($order->status === 'ready')
                            <form method="POST" action="{{ route('business.orders.change-status', $order) }}"
                                class="inline">
                                @csrf
                                <input type="hidden" name="status" value="delivered">
                                <button type="submit"
                                    class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                                    Marcar como Entregada
                                </button>
                            </form>
                        @endif

                        <form method="POST" action="{{ route('business.orders.change-status', $order) }}"
                            class="inline" onsubmit="return confirm('¿Estás seguro de cancelar esta orden?')">
                            @csrf
                            <input type="hidden" name="status" value="cancelled">
                            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md hover:bg-red-700">
                                Cancelar Orden
                            </button>
                        </form>
                    </div>
                @endif
            </div>

            <!-- Información del Cliente -->
            @if ($order->client_name || $order->client_phone || $order->client_email)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Información del Cliente</h3>
                    <dl class="space-y-2">
                        @if ($order->client_name)
                            <div class="flex">
                                <dt class="w-32 text-sm font-medium text-gray-500">Nombre:</dt>
                                <dd class="text-sm text-gray-900">{{ $order->client_name }}</dd>
                            </div>
                        @endif
                        @if ($order->client_phone)
                            <div class="flex">
                                <dt class="w-32 text-sm font-medium text-gray-500">Teléfono:</dt>
                                <dd class="text-sm text-gray-900">{{ $order->client_phone }}</dd>
                            </div>
                        @endif
                        @if ($order->client_email)
                            <div class="flex">
                                <dt class="w-32 text-sm font-medium text-gray-500">Email:</dt>
                                <dd class="text-sm text-gray-900">{{ $order->client_email }}</dd>
                            </div>
                        @endif
                    </dl>
                </div>
            @endif

            <!-- Productos -->
            @if ($order->products)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-4">Productos/Servicios</h3>
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Producto</th>
                                <th class="text-center py-2">Cantidad</th>
                                <th class="text-right py-2">Precio</th>
                                <th class="text-right py-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($order->products as $product)
                                <tr class="border-b">
                                    <td class="py-2">{{ $product['name'] }}</td>
                                    <td class="text-center py-2">{{ $product['quantity'] }}</td>
                                    <td class="text-right py-2">${{ number_format($product['price'], 2) }}</td>
                                    <td class="text-right py-2">
                                        ${{ number_format($product['quantity'] * $product['price'], 2) }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif

            <!-- Notas -->
            @if ($order->notes)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-semibold mb-2">Notas</h3>
                    <p class="text-gray-700">{{ $order->notes }}</p>
                </div>
            @endif
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- QR Code -->
            <div class="bg-white shadow rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold mb-4">Código QR</h3>
                @if ($order->qr_path)
                    <img src="{{ Storage::url($order->qr_path) }}" alt="QR Code" class="mx-auto mb-4 w-48 h-48">
                    <a href="{{ route('business.orders.download-qr', $order) }}"
                        class="text-blue-600 hover:text-blue-800 text-sm">
                        Descargar QR
                    </a>
                @else
                    <p class="text-gray-500">QR no generado</p>
                @endif
            </div>

            <!-- Resumen -->
            <div class="bg-white shadow rounded-lg p-6">
                <h3 class="text-lg font-semibold mb-4">Resumen</h3>
                <dl class="space-y-2">
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Total:</dt>
                        <dd class="text-lg font-bold text-gray-900">${{ number_format($order->total_amount, 2) }}</dd>
                    </div>
                    <div class="flex justify-between">
                        <dt class="text-sm text-gray-500">Creada:</dt>
                        <dd class="text-sm text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</dd>
                    </div>
                    @if ($order->ready_at)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Lista:</dt>
                            <dd class="text-sm text-gray-900">{{ $order->ready_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                    @if ($order->delivered_at)
                        <div class="flex justify-between">
                            <dt class="text-sm text-gray-500">Entregada:</dt>
                            <dd class="text-sm text-gray-900">{{ $order->delivered_at->format('d/m/Y H:i') }}</dd>
                        </div>
                    @endif
                </dl>
            </div>
        </div>
    </div>

    <!-- Modal QR -->
    <div id="qrModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-white rounded-lg p-8 max-w-md">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-xl font-bold">Código QR</h3>
                <button onclick="closeQRModal()" class="text-gray-500 hover:text-gray-700">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="text-center">
                @if ($order->qr_path)
                    <img src="{{ Storage::url($order->qr_path) }}" alt="QR Code" class="mx-auto mb-4">
                    <p class="text-sm text-gray-600 mb-2">Código: <span
                            class="font-mono font-bold">{{ $order->order_code }}</span></p>
                    <a href="{{ route('business.orders.download-qr', $order) }}"
                        class="inline-block bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                        Descargar
                    </a>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function showQRModal() {
                document.getElementById('qrModal').classList.remove('hidden');
                document.getElementById('qrModal').classList.add('flex');
            }

            function closeQRModal() {
                document.getElementById('qrModal').classList.add('hidden');
                document.getElementById('qrModal').classList.remove('flex');
            }
        </script>
    @endpush
@endsection
