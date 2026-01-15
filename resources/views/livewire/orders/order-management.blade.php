{{--
/**
 * Nombre de la vista           : order-management.blade.php
 * Descripción de la vista      : Componente Livewire para gestión de órdenes
 *                                con filtros, estadísticas y CRUD
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 14/01/2026
 * Folio de mantenimiento       : 3
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Corrección para compatibilidad con Livewire 3
 *                                selectedOrder viene desde render()
 * Responsable                  : Jesús Núñez
 * Revisor                      : Jesús Núñez
 */
--}}

<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="p-6">
        {{-- Encabezado --}}
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <x-heroicon-o-shopping-bag class="w-8 h-8 text-gray-700 mr-3" />
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Gestión de Órdenes</h2>
                    <p class="text-sm text-gray-600 mt-1">Administra las órdenes de tu negocio</p>
                </div>
            </div>

            <a href="{{ route('orders.create') }}"
                class="flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Nueva Orden
            </a>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 uppercase">Todas</p>
                        <p class="text-2xl font-bold text-gray-900">{{ $orders->total() }}</p>
                    </div>
                    <x-heroicon-o-shopping-bag class="w-8 h-8 text-gray-400" />
                </div>
            </div>

            <div class="bg-yellow-50 rounded-lg shadow p-4 cursor-pointer hover:bg-yellow-100 transition"
                wire:click="$set('statusFilter', 'pending')">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-yellow-700 uppercase">Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-900">
                            {{ auth()->user()->business->orders()->where('status', 'pending')->count() }}
                        </p>
                    </div>
                    <x-heroicon-o-clock class="w-8 h-8 text-yellow-500" />
                </div>
            </div>

            <div class="bg-blue-50 rounded-lg shadow p-4 cursor-pointer hover:bg-blue-100 transition"
                wire:click="$set('statusFilter', 'paid')">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-700 uppercase">Pagadas</p>
                        <p class="text-2xl font-bold text-blue-900">
                            {{ auth()->user()->business->orders()->where('status', 'paid')->count() }}
                        </p>
                    </div>
                    <x-heroicon-o-credit-card class="w-8 h-8 text-blue-500" />
                </div>
            </div>

            <div class="bg-orange-50 rounded-lg shadow p-4 cursor-pointer hover:bg-orange-100 transition"
                wire:click="$set('statusFilter', 'ready')">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-orange-700 uppercase">Listas</p>
                        <p class="text-2xl font-bold text-orange-900">
                            {{ auth()->user()->business->orders()->where('status', 'ready')->count() }}
                        </p>
                    </div>
                    <x-heroicon-o-check-circle class="w-8 h-8 text-orange-500" />
                </div>
            </div>

            <div class="bg-green-50 rounded-lg shadow p-4 cursor-pointer hover:bg-green-100 transition"
                wire:click="$set('statusFilter', 'delivered')">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-700 uppercase">Entregadas</p>
                        <p class="text-2xl font-bold text-green-900">
                            {{ auth()->user()->business->orders()->where('status', 'delivered')->count() }}
                        </p>
                    </div>
                    <x-heroicon-o-check-badge class="w-8 h-8 text-green-500" />
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="md:col-span-2">
                    <div class="relative">
                        <x-heroicon-o-magnifying-glass
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input type="text" wire:model.live.debounce.300ms="search"
                            placeholder="Buscar por número de orden..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent" />
                    </div>
                </div>

                <div>
                    <select wire:model.live="statusFilter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-black focus:border-transparent">
                        <option value="">Todos los estados</option>
                        <option value="pending">Pendiente</option>
                        <option value="paid">Pagada</option>
                        <option value="ready">Lista</option>
                        <option value="delivered">Entregada</option>
                        <option value="cancelled">Cancelada</option>
                    </select>
                </div>
            </div>
        </div>

        {{-- Tabla de órdenes --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Número
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Cliente
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Descripción
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Monto
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Estado
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Fecha
                            </th>
                            <th scope="col"
                                class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Acciones
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($orders as $order)
                            <tr wire:key="order-{{ $order->id }}" class="hover:bg-gray-50 transition">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="font-mono text-sm font-semibold text-gray-900">
                                        {{ $order->order_number }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @if ($order->user)
                                        <div class="flex items-center">
                                            <div
                                                class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                                <x-heroicon-o-user class="w-5 h-5 text-gray-500" />
                                            </div>
                                            <div class="ml-3">
                                                <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}
                                                </p>
                                                <p class="text-xs text-gray-500">{{ $order->user->phone }}</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center text-gray-400">
                                            <x-heroicon-o-user-circle class="w-5 h-5 mr-2" />
                                            <span class="text-sm">Sin asociar</span>
                                        </div>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="max-w-xs">
                                        <p class="text-sm text-gray-900 truncate" title="{{ $order->description }}">
                                            {{ $order->description }}
                                        </p>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="text-lg font-bold text-gray-900">
                                        ${{ number_format($order->amount, 2) }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'paid' => 'bg-blue-100 text-blue-800',
                                            'ready' => 'bg-orange-100 text-orange-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'cancelled' => 'bg-red-100 text-red-800',
                                        ];
                                        $statusText = [
                                            'pending' => 'Pendiente',
                                            'paid' => 'Pagada',
                                            'ready' => 'Lista',
                                            'delivered' => 'Entregada',
                                            'cancelled' => 'Cancelada',
                                        ];
                                    @endphp
                                    <span
                                        class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] }}">
                                        {{ $statusText[$order->status] }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-600">
                                        {{ $order->created_at->format('d/m/Y') }}
                                    </div>
                                    <div class="text-xs text-gray-400">
                                        {{ $order->created_at->format('H:i') }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-center">
                                    <div class="flex justify-center space-x-1">
                                        {{-- Ver detalles --}}
                                        <a href="{{ route('orders.show', $order) }}"
                                            class="inline-flex items-center px-3 py-1.5 border border-blue-600 text-blue-600 rounded-lg hover:bg-blue-50 transition-colors"
                                            title="Ver detalles">
                                            <x-heroicon-o-eye class="w-4 h-4" />
                                        </a>

                                        {{-- Marcar como pagada --}}
                                        @if ($order->status === 'pending')
                                            <button wire:click="markAsPaid({{ $order->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="markAsPaid({{ $order->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors disabled:opacity-50"
                                                title="Marcar como pagada">
                                                <x-heroicon-o-credit-card class="w-4 h-4" />
                                            </button>
                                        @endif

                                        {{-- Ver QR de asociación --}}
                                        @if ($order->status === 'paid' && $order->qr_code)
                                            <button wire:click="showQR({{ $order->id }}, 'association')"
                                                class="inline-flex items-center px-3 py-1.5 border border-cyan-600 text-cyan-600 rounded-lg hover:bg-cyan-50 transition-colors"
                                                title="Ver QR de asociación">
                                                <x-heroicon-o-qr-code class="w-4 h-4" />
                                            </button>
                                        @endif

                                        {{-- Marcar como lista --}}
                                        @if ($order->status === 'paid' && $order->user_id)
                                            <button wire:click="markAsReady({{ $order->id }})"
                                                wire:loading.attr="disabled"
                                                wire:target="markAsReady({{ $order->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-orange-600 text-orange-600 rounded-lg hover:bg-orange-50 transition-colors disabled:opacity-50"
                                                title="Marcar como lista">
                                                <x-heroicon-o-check-circle class="w-4 h-4" />
                                            </button>
                                        @endif

                                        {{-- Ver QR de entrega --}}
                                        @if ($order->status === 'ready' && $order->qr_delivery_code)
                                            <button wire:click="showQR({{ $order->id }}, 'delivery')"
                                                class="inline-flex items-center px-3 py-1.5 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 transition-colors"
                                                title="Ver QR de entrega">
                                                <x-heroicon-o-qr-code class="w-4 h-4" />
                                            </button>
                                        @endif

                                        {{-- Cancelar --}}
                                        @if (in_array($order->status, ['pending', 'paid', 'ready']))
                                            <button onclick="confirmOrderCancel({{ $order->id }})"
                                                class="inline-flex items-center px-3 py-1.5 border border-red-600 text-red-600 rounded-lg hover:bg-red-50 transition-colors"
                                                title="Cancelar orden">
                                                <x-heroicon-o-x-circle class="w-4 h-4" />
                                            </button>
                                        @endif
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-12 text-center">
                                    <x-heroicon-o-shopping-bag class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                                    <p class="text-gray-500">No se encontraron órdenes</p>
                                    <a href="{{ route('orders.create') }}"
                                        class="mt-4 inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                                        <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                                        Crear Primera Orden
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Paginación --}}
            @if ($orders->hasPages())
                <div class="px-6 py-4 border-t">
                    {{ $orders->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de QR - Ahora usa $selectedOrder que viene del render() --}}
    @if ($showQRModal && $selectedOrder)
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog"
            aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                {{-- Background overlay --}}
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                    wire:click="closeQRModal"></div>

                {{-- Centrar modal --}}
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                {{-- Modal panel --}}
                <div
                    class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <div class="bg-white p-6 text-center">
                        <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full">
                            <x-heroicon-o-qr-code class="w-10 h-10 text-gray-600" />
                        </div>

                        <h3 class="text-xl font-semibold mb-2">
                            Código QR - {{ $qrType === 'association' ? 'Asociación' : 'Entrega' }}
                        </h3>

                        <p class="text-gray-600 mb-6">
                            Orden: <span
                                class="font-mono font-bold text-black">{{ $selectedOrder->order_number }}</span>
                        </p>

                        <div class="bg-white p-6 rounded-lg border-2 border-gray-200 inline-block">
                            @php
                                $qrPath =
                                    $qrType === 'delivery' ? $selectedOrder->qr_delivery_code : $selectedOrder->qr_code;
                            @endphp
                            @if ($qrPath)
                                <img src="{{ Storage::url($qrPath) }}" alt="Código QR" class="w-64 h-64 mx-auto">
                            @else
                                <div class="w-64 h-64 flex items-center justify-center bg-gray-100 rounded">
                                    <p class="text-gray-500">QR no disponible</p>
                                </div>
                            @endif
                        </div>

                        <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                            <p class="text-sm text-blue-800 flex items-center justify-center">
                                @if ($qrType === 'association')
                                    <x-heroicon-o-information-circle class="w-5 h-5 mr-2" />
                                    El cliente debe escanear este QR para asociar la orden a su cuenta
                                @else
                                    <x-heroicon-o-shield-check class="w-5 h-5 mr-2" />
                                    El cliente debe escanear este QR para confirmar la entrega
                                @endif
                            </p>
                        </div>

                        <div class="mt-6 flex justify-center space-x-3">
                            <button type="button" wire:click="closeQRModal"
                                class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                                Cerrar
                            </button>

                            @if ($qrPath)
                                <a href="{{ route('orders.download-qr', [$selectedOrder->id, $qrType]) }}"
                                    class="inline-flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors">
                                    <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                                    Descargar QR
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    {{-- Scripts de SweetAlert2 --}}
    @script
        <script>
            function confirmOrderCancel(orderId) {
                Swal.fire({
                    title: '¿Cancelar orden?',
                    input: 'textarea',
                    inputLabel: 'Motivo de cancelación',
                    inputPlaceholder: 'Escribe el motivo...',
                    inputAttributes: {
                        'aria-label': 'Motivo de cancelación',
                        'rows': 4
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cancelar orden',
                    cancelButtonText: 'No cancelar',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Debes escribir un motivo'
                        }
                        if (value.length < 10) {
                            return 'El motivo debe tener al menos 10 caracteres'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        $wire.dispatch('order-cancel-confirmed', {
                            orderId: orderId,
                            reason: result.value
                        });
                    }
                });
            }

            $wire.on('confirm-cancel-order', (event) => {
                confirmOrderCancel(event.orderId);
            });

            // Escuchar eventos de éxito y error
            $wire.on('success', (event) => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Éxito!',
                    text: event.message,
                    confirmButtonColor: '#000000'
                });
            });

            $wire.on('error', (event) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: event.message,
                    confirmButtonColor: '#dc2626'
                });
            });
        </script>
    @endscript
</x-layouts.app.sidebar>
