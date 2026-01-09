<div>
    {{-- Encabezado --}}
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center">
            <x-heroicon-o-shopping-bag class="w-8 h-8 text-gray-700 mr-3" />
            <h2 class="text-3xl font-bold text-gray-900">Gestión de Órdenes</h2>
        </div>
        
        <flux:button 
            variant="primary" 
            href="{{ route('orders.create') }}"
            class="bg-black hover:bg-[#494949]"
        >
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
            Nueva Orden
        </flux:button>
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

        <div class="bg-yellow-50 rounded-lg shadow p-4 cursor-pointer" wire:click="$set('statusFilter', 'pending')">
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

        <div class="bg-blue-50 rounded-lg shadow p-4 cursor-pointer" wire:click="$set('statusFilter', 'paid')">
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

        <div class="bg-orange-50 rounded-lg shadow p-4 cursor-pointer" wire:click="$set('statusFilter', 'ready')">
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

        <div class="bg-green-50 rounded-lg shadow p-4 cursor-pointer" wire:click="$set('statusFilter', 'delivered')">
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
                <flux:input 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por número de orden..."
                >
                    <x-slot:iconTrailing>
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </x-slot:iconTrailing>
                </flux:input>
            </div>

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
                    <flux:tr wire:key="order-{{ $order->id }}" class="hover:bg-gray-50">
                        <flux:td>
                            <span class="font-mono text-sm font-semibold text-gray-900">
                                {{ $order->order_number }}
                            </span>
                        </flux:td>
                        <flux:td>
                            @if($order->user)
                                <div class="flex items-center">
                                    <div class="flex-shrink-0 h-8 w-8 bg-gray-200 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-user class="w-5 h-5 text-gray-500" />
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-sm font-medium text-gray-900">{{ $order->user->name }}</p>
                                        <p class="text-xs text-gray-500">{{ $order->user->phone }}</p>
                                    </div>
                                </div>
                            @else
                                <div class="flex items-center text-gray-400">
                                    <x-heroicon-o-user-circle class="w-5 h-5 mr-2" />
                                    <span class="text-sm">Sin asociar</span>
                                </div>
                            @endif
                        </flux:td>
                        <flux:td>
                            <div class="max-w-xs">
                                <p class="text-sm text-gray-900 truncate">{{ $order->description }}</p>
                            </div>
                        </flux:td>
                        <flux:td>
                            <span class="text-lg font-bold text-gray-900">
                                ${{ number_format($order->amount, 2) }}
                            </span>
                        </flux:td>
                        <flux:td>
                            <x-badge-status :status="$order->status" />
                        </flux:td>
                        <flux:td>
                            <div class="text-sm text-gray-600">
                                {{ $order->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $order->created_at->format('H:i') }}
                            </div>
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
                        <flux:td colspan="7">
                            <x-empty-state
                                icon="shopping-bag"
                                title="No se encontraron órdenes"
                                description="No hay órdenes que coincidan con los filtros seleccionados"
                                actionText="Crear Primera Orden"
                                actionUrl="{{ route('orders.create') }}"
                            />
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
                <div class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full">
                    <x-heroicon-o-qr-code class="w-10 h-10 text-gray-600" />
                </div>

                <h3 class="text-xl font-semibold mb-2">
                    Código QR - {{ $qrType === 'association' ? 'Asociación' : 'Entrega' }}
                </h3>
                
                <p class="text-gray-600 mb-6">
                    Orden: <span class="font-mono font-bold text-black">{{ $selectedOrder->order_number }}</span>
                </p>

                <div class="bg-white p-6 rounded-lg border-2 border-gray-200 inline-block">
                    @php
                        $qrPath = $qrType === 'delivery' ? $selectedOrder->qr_delivery_code : $selectedOrder->qr_code;
                    @endphp
                    <img 
                        src="{{ Storage::url($qrPath) }}" 
                        alt="Código QR"
                        class="w-64 h-64 mx-auto"
                    >
                </div>

                <div class="mt-6 p-4 bg-blue-50 rounded-lg">
                    <p class="text-sm text-blue-800 flex items-center justify-center">
                        @if($qrType === 'association')
                            <x-heroicon-o-information-circle class="w-5 h-5 mr-2" />
                            El cliente debe escanear este QR para asociar la orden a su cuenta
                        @else
                            <x-heroicon-o-shield-check class="w-5 h-5 mr-2" />
                            El cliente debe escanear este QR para confirmar la entrega
                        @endif
                    </p>
                </div>

                <div class="mt-6 flex justify-center space-x-3">
                    <flux:button 
                        variant="ghost"
                        wire:click="closeQRModal"
                    >
                        <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                        Cerrar
                    </flux:button>

                    <flux:button 
                        variant="primary"
                        href="{{ route('orders.download-qr', [$selectedOrder, $qrType]) }}"
                        class="bg-black hover:bg-[#494949]"
                    >
                        <x-heroicon-o-arrow-down-tray class="w-5 h-5 mr-2" />
                        Descargar QR
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
                    'aria-label': 'Motivo de cancelación',
                    'rows': 4
                },
                showCancelButton: true,
                confirmButtonText: 'Sí, cancelar orden',
                cancelButtonText: 'No cancelar',
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium',
                    cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium ml-2'
                },
                buttonsStyling: false,
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
    </script>
    @endscript
</div>