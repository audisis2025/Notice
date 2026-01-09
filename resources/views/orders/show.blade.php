<x-app-layout>
    @section('page-title', 'Orden ' . $order->order_number)

    <div class="max-w-5xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('orders.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Volver al listado
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Columna principal --}}
            <div class="lg:col-span-2 space-y-6">
                {{-- Información general --}}
                <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                    <div class="bg-gradient-to-r from-black to-gray-800 px-6 py-4">
                        <h2 class="text-2xl font-bold text-white flex items-center">
                            <x-heroicon-o-shopping-bag class="w-8 h-8 mr-3" />
                            Orden {{ $order->order_number }}
                        </h2>
                    </div>

                    <div class="p-6 space-y-4">
                        {{-- Estado --}}
                        <div class="flex items-center justify-between">
                            <span class="text-sm font-medium text-gray-600">Estado actual:</span>
                            <x-badge-status :status="$order->status" />
                        </div>

                        {{-- Descripción --}}
                        <div>
                            <label class="text-sm font-medium text-gray-600 flex items-center mb-2">
                                <x-heroicon-o-document-text class="w-4 h-4 mr-1" />
                                Descripción
                            </label>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-lg">{{ $order->description }}</p>
                        </div>

                        {{-- Monto --}}
                        <div class="flex items-center justify-between p-4 bg-green-50 rounded-lg">
                            <span class="text-sm font-medium text-green-900 flex items-center">
                                <x-heroicon-o-currency-dollar class="w-5 h-5 mr-2" />
                                Monto Total
                            </span>
                            <span class="text-3xl font-bold text-green-900">${{ number_format($order->amount, 2) }}</span>
                        </div>

                        {{-- Cliente asociado --}}
                        @if($order->user)
                            <div class="border-t pt-4">
                                <label class="text-sm font-medium text-gray-600 flex items-center mb-3">
                                    <x-heroicon-o-user class="w-4 h-4 mr-1" />
                                    Cliente Asociado
                                </label>
                                <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                                    <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                                        <x-heroicon-o-user class="w-7 h-7 text-gray-500" />
                                    </div>
                                    <div class="ml-4">
                                        <p class="font-semibold text-gray-900">{{ $order->user->name }}</p>
                                        <p class="text-sm text-gray-500 flex items-center">
                                            <x-heroicon-o-phone class="w-4 h-4 mr-1" />
                                            {{ $order->user->phone }}
                                        </p>
                                        @if($order->user->email)
                                            <p class="text-sm text-gray-500 flex items-center">
                                                <x-heroicon-o-envelope class="w-4 h-4 mr-1" />
                                                {{ $order->user->email }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                                <p class="mt-2 text-xs text-gray-500 flex items-center">
                                    <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                    Asociado el {{ $order->associated_at->format('d/m/Y H:i') }}
                                </p>
                            </div>
                        @else
                            <div class="border-t pt-4">
                                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded">
                                    <div class="flex items-center">
                                        <x-heroicon-o-exclamation-triangle class="w-5 h-5 text-yellow-600 mr-3" />
                                        <p class="text-sm text-yellow-800">
                                            Esta orden aún no ha sido asociada con ningún cliente
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @endif

                        {{-- Fechas importantes --}}
                        <div class="border-t pt-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="text-xs text-gray-500 flex items-center mb-1">
                                    <x-heroicon-o-calendar class="w-4 h-4 mr-1" />
                                    Creada
                                </label>
                                <p class="text-sm font-medium text-gray-900">{{ $order->created_at->format('d/m/Y H:i') }}</p>
                            </div>

                            @if($order->paid_at)
                                <div>
                                    <label class="text-xs text-gray-500 flex items-center mb-1">
                                        <x-heroicon-o-credit-card class="w-4 h-4 mr-1" />
                                        Pagada
                                    </label>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->paid_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            @if($order->ready_at)
                                <div>
                                    <label class="text-xs text-gray-500 flex items-center mb-1">
                                        <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                        Lista
                                    </label>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->ready_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            @if($order->delivered_at)
                                <div>
                                    <label class="text-xs text-gray-500 flex items-center mb-1">
                                        <x-heroicon-o-check-badge class="w-4 h-4 mr-1" />
                                        Entregada
                                    </label>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->delivered_at->format('d/m/Y H:i') }}</p>
                                </div>
                            @endif

                            @if($order->cancelled_at)
                                <div class="md:col-span-2">
                                    <label class="text-xs text-red-500 flex items-center mb-1">
                                        <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                        Cancelada
                                    </label>
                                    <p class="text-sm font-medium text-gray-900">{{ $order->cancelled_at->format('d/m/Y H:i') }}</p>
                                    @if($order->cancellation_reason)
                                        <p class="mt-2 text-sm text-red-600 bg-red-50 p-3 rounded">
                                            <strong>Motivo:</strong> {{ $order->cancellation_reason }}
                                        </p>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                </div>

                {{-- Calificación --}}
                @if($order->rating)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-star class="w-6 h-6 mr-2 text-yellow-500" />
                            Calificación del Cliente
                        </h3>

                        <div class="flex items-center mb-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= $order->rating->stars)
                                    <x-heroicon-s-star class="w-6 h-6 text-yellow-400" />
                                @else
                                    <x-heroicon-o-star class="w-6 h-6 text-gray-300" />
                                @endif
                            @endfor
                            <span class="ml-2 text-lg font-bold text-gray-900">{{ $order->rating->stars }}/5</span>
                        </div>

                        @if($order->rating->comment)
                            <div class="bg-gray-50 p-4 rounded-lg">
                                <p class="text-sm text-gray-700 italic">"{{ $order->rating->comment }}"</p>
                            </div>
                        @endif

                        <p class="mt-2 text-xs text-gray-500">
                            Calificado el {{ $order->rating->created_at->format('d/m/Y H:i') }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Columna lateral --}}
            <div class="space-y-6">
                {{-- Acciones rápidas --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-bolt class="w-6 h-6 mr-2" />
                        Acciones
                    </h3>

                    <div class="space-y-3">
                        @if($order->status === 'pending')
                            <form action="{{ route('orders.mark-paid', $order) }}" method="POST">
                                @csrf
                                <flux:button 
                                    type="submit"
                                    variant="success"
                                    class="w-full"
                                >
                                    <x-heroicon-o-credit-card class="w-5 h-5 mr-2" />
                                    Marcar como Pagada
                                </flux:button>
                            </form>
                        @endif

                        @if($order->status === 'paid' && $order->user_id)
                            <form action="{{ route('orders.mark-ready', $order) }}" method="POST">
                                @csrf
                                <flux:button 
                                    type="submit"
                                    variant="warning"
                                    class="w-full"
                                >
                                    <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                                    Marcar como Lista
                                </flux:button>
                            </form>
                        @endif

                        @if($order->qr_code)
                            <a href="{{ route('orders.download-qr', [$order, 'association']) }}" download>
                                <flux:button 
                                    variant="info"
                                    class="w-full"
                                >
                                    <x-heroicon-o-qr-code class="w-5 h-5 mr-2" />
                                    Descargar QR Asociación
                                </flux:button>
                            </a>
                        @endif

                        @if($order->qr_delivery_code)
                            <a href="{{ route('orders.download-qr', [$order, 'delivery']) }}" download>
                                <flux:button 
                                    variant="success"
                                    class="w-full"
                                >
                                    <x-heroicon-o-qr-code class="w-5 h-5 mr-2" />
                                    Descargar QR Entrega
                                </flux:button>
                            </a>
                        @endif

                        @if(in_array($order->status, ['pending', 'paid', 'ready']))
                            <flux:button 
                                variant="danger"
                                outline
                                class="w-full"
                                onclick="showCancelOrderModal()"
                            >
                                <x-heroicon-o-x-circle class="w-5 h-5 mr-2" />
                                Cancelar Orden
                            </flux:button>
                        @endif
                    </div>
                </div>

                {{-- Chat --}}
                @if($order->chat_enabled)
                    <div class="bg-white rounded-lg shadow p-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-2" />
                            Chat
                        </h3>

                        <p class="text-sm text-gray-600 mb-4">
                            El chat está habilitado para esta orden debido al tiempo de entrega.
                        </p>

                        <flux:button 
                            variant="primary"
                            href="{{ route('chat.show', $order) }}"
                            class="w-full bg-black hover:bg-[#494949]"
                        >
                            <x-heroicon-o-chat-bubble-left-right class="w-5 h-5 mr-2" />
                            Abrir Chat
                        </flux:button>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function showCancelOrderModal() {
            Swal.fire({
                title: '¿Cancelar orden?',
                input: 'textarea',
                inputLabel: 'Motivo de cancelación',
                inputPlaceholder: 'Escribe el motivo detallado...',
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
                    // Crear formulario y enviar
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = '{{ route("orders.cancel", $order) }}';
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    
                    const reasonInput = document.createElement('input');
                    reasonInput.type = 'hidden';
                    reasonInput.name = 'cancellation_reason';
                    reasonInput.value = result.value;
                    
                    form.appendChild(csrfInput);
                    form.appendChild(reasonInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>