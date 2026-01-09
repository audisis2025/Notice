<div>
    {{-- Encabezado --}}
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 flex items-center">
            <x-heroicon-o-shopping-bag class="w-8 h-8 mr-2" />
            Gestión de Órdenes
        </h2>
        
        <flux:button 
            variant="primary" 
            icon="plus"
            href="{{ route('orders.create') }}"
            class="bg-black hover:bg-[#494949]"
        >
            Nueva Orden
        </flux:button>
    </div>

    {{-- Filtros --}}
    <div class="bg-white rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <flux:input 
                wire:model.live.debounce.300ms="search"
                placeholder="Buscar por número de orden..."
                icon="magnifying-glass"
            />

            <flux:select wire:model.live="statusFilter">
                <option value="">Todos los estados</option>
                <option value="pending">Pendiente</option>
                <option value="paid">Pagada</option>
                <option value="ready">Lista</option>
                <option value="delivered">Entregada</option>
                <option value="cancelled">Cancelada</option>
            </flux:select>
        </div>
    </div>

    {{-- Tabla de órdenes --}}
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <flux:table>
            <flux:thead>
                <flux:tr>
                    <flux:th>Número</flux:th>
                    <flux:th>Cliente</flux:th>
                    <flux:th>Descripción</flux:th>
                    <flux:th>Monto</flux:th>
                    <flux:th>Estado</flux:th>
                    <flux:th>Fecha</flux:th>
                    <flux:th class="text-center">Acciones</flux:th>
                </flux:tr>
            </flux:thead>
            <flux:tbody>
                @forelse($orders as $order)
                    <flux:tr wire:key="order-{{ $order->id }}">
                        <flux:td class="font-mono text-sm">
                            {{ $order->order_number }}
                        </flux:td>
                        <flux:td>
                            @if($order->user)
                                <div class="flex items-center">
                                    <x-heroicon-o-user class="w-4 h-4 text-gray-400 mr-1" />
                                    {{ $order->user->name }}
                                </div>
                            @else
                                <span class="text-gray-400 text-sm">Sin asociar</span>
                            @endif
                        </flux:td>
                        <flux:td>
                            <div class="max-w-xs truncate">
                                {{ $order->description }}
                            </div>
                        </flux:td>
                        <flux:td class="font-semibold">
                            ${{ number_format($order->amount, 2) }}
                        </flux:td>
                        <flux:td>
                            @php
                                $statusConfig = [
                                    'pending' => ['variant' => 'gray', 'icon' => 'clock', 'text' => 'Pendiente'],
                                    'paid' => ['variant' => 'info', 'icon' => 'credit-card', 'text' => 'Pagada'],
                                    'ready' => ['variant' => 'warning', 'icon' => 'check-circle', 'text' => 'Lista'],
                                    'delivered' => ['variant' => 'success', 'icon' => 'check-badge', 'text' => 'Entregada'],
                                    'cancelled' => ['variant' => 'danger', 'icon' => 'x-circle', 'text' => 'Cancelada'],
                                ];
                                $config = $statusConfig[$order->status] ?? $statusConfig['pending'];
                            @endphp
                            
                            <flux:badge variant="{{ $config['variant'] }}">
                                @php
                                    $iconComponent = "heroicon-o-{$config['icon']}";
                                @endphp
                                <x-dynamic-component :component="$iconComponent" class="w-4 h-4 mr-1" />
                                {{ $config['text'] }}
                            </flux:badge>
                        </flux:td>
                        <flux:td class="text-sm text-gray-600">
                            {{ $order->created_at->format('d/m/Y H:i') }}
                        </flux:td>
                        <flux:td>
                            <div class="flex justify-center space-x-1">
                                {{-- Ver detalles --}}
                                <flux:button 
                                    variant="primary" 
                                    outline 
                                    size="sm"
                                    href="{{ route('orders.show', $order) }}"
                                    title="Ver detalles"
                                >
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </flux:button>

                                {{-- Marcar como pagada --}}
                                @if($order->status === 'pending')
                                    <flux:button 
                                        variant="success" 
                                        outline 
                                        size="sm"
                                        wire:click="markAsPaid({{ $order->id }})"
                                        title="Marcar como pagada"
                                    >
                                        <x-heroicon-o-credit-card class="w-4 h-4" />
                                    </flux:button>
                                @endif

                                {{-- Ver QR de asociación --}}
                                @if($order->status === 'paid' && $order->qr_code)
                                    <flux:button 
                                        variant="info" 
                                        outline 
                                        size="sm"
                                        wire:click="showQR({{ $order->id }}, 'association')"
                                        title="Ver QR de asociación"
                                    >
                                        <x-heroicon-o-qr-code class="w-4 h-4" />
                                    </flux:button>
                                @endif

                                {{-- Marcar como lista --}}
                                @if($order->status === 'paid' && $order->user_id)
                                    <flux:button 
                                        variant="warning" 
                                        outline 
                                        size="sm"
                                        wire:click="markAsReady({{ $order->id }})"
                                        title="Marcar como lista"
                                    >
                                        <x-heroicon-o-check-circle class="w-4 h-4" />
                                    </flux:button>
                                @endif

                                {{-- Ver QR de entrega --}}
                                @if($order->status === 'ready' && $order->qr_delivery_code)
                                    <flux:button 
                                        variant="success" 
                                        outline 
                                        size="sm"
                                        wire:click="showQR({{ $order->id }}, 'delivery')"
                                        title="Ver QR de entrega"
                                    >
                                        <x-heroicon-o-qr-code class="w-4 h-4" />
                                    </flux:button>
                                @endif

                                {{-- Cancelar --}}
                                @if(in_array($order->status, ['pending', 'paid', 'ready']))
                                    <flux:button 
                                        variant="danger" 
                                        outline 
                                        size="sm"
                                        onclick="confirmOrderCancel({{ $order->id }})"
                                        title="Cancelar orden"
                                    >
                                        <x-heroicon-o-x-circle class="w-4 h-4" />
                                    </flux:button>
                                @endif
                            </div>
                        </flux:td>
                    </flux:tr>
                @empty
                    <flux:tr>
                        <flux:td colspan="7" class="text-center text-gray-500 py-8">
                            <x-heroicon-o-shopping-bag class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                            <p>No se encontraron órdenes</p>
                        </flux:td>
                    </flux:tr>
                @endforelse
            </flux:tbody>
        </flux:table>

        <div class="px-6 py-4 border-t">
            {{ $orders->links() }}
        </div>
    </div>

    {{-- Modal de QR --}}
    @if($showQRModal && $selectedOrder)
        <flux:modal wire:model="showQRModal">
            <div class="text-center p-6">
                <h3 class="text-xl font-semibold mb-4 flex items-center justify-center">
                    <x-heroicon-o-qr-code class="w-6 h-6 mr-2" />
                    Código QR - {{ $qrType === 'association' ? 'Asociación' : 'Entrega' }}
                </h3>
                
                <p class="text-gray-600 mb-4">
                    Orden: <span class="font-mono font-bold">{{ $selectedOrder->order_number }}</span>
                </p>

                <div class="bg-white p-4 rounded-lg inline-block">
                    @php
                        $qrPath = $qrType === 'delivery' ? $selectedOrder->qr_delivery_code : $selectedOrder->qr_code;
                    @endphp
                    <img 
                        src="{{ Storage::url($qrPath) }}" 
                        alt="Código QR"
                        class="w-64 h-64 mx-auto"
                    >
                </div>

                <div class="mt-6 space-y-2">
                    <p class="text-sm text-gray-600">
                        @if($qrType === 'association')
                            <x-heroicon-o-information-circle class="w-5 h-5 inline mr-1" />
                            El cliente debe escanear este QR para asociar la orden a su cuenta
                        @else
                            <x-heroicon-o-shield-check class="w-5 h-5 inline mr-1" />
                            El cliente debe escanear este QR para confirmar la entrega
                        @endif
                    </p>
                </div>

                <div class="mt-6 flex justify-center space-x-2">
                    <flux:button 
                        variant="ghost"
                        wire:click="closeQRModal"
                    >
                        Cerrar
                    </flux:button>

                    <flux:button 
                        variant="primary"
                        href="{{ route('orders.download-qr', [$selectedOrder, $qrType]) }}"
                        class="bg-black hover:bg-[#494949]"
                    >
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-1" />
                        Descargar
                    </flux:button>
                </div>
            </div>
        </flux:modal>
    @endif

    @script
    <script>
        function confirmOrderCancel(orderId) {
            Swal.fire({
                title: '¿Cancelar orden?',
                input: 'textarea',
                inputLabel: 'Motivo de cancelación',
                inputPlaceholder: 'Escribe el motivo...',
                inputAttributes: {
                    'aria-label': 'Motivo de cancelación'
                },
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar orden',
                cancelButtonText: 'No cancelar',
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition',
                    cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition ml-2'
                },
                buttonsStyling: false,
                inputValidator: (value) => {
                    if (!value) {
                        return 'Debes escribir un motivo'
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
    </script>
    @endscript
</div>