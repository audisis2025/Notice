<x-app-layout>
    @section('page-title', 'Cupones de Descuento')

    <div>
        {{-- Encabezado --}}
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <x-heroicon-o-ticket class="w-8 h-8 text-gray-700 mr-3" />
                <h2 class="text-3xl font-bold text-gray-900">Gestión de Cupones</h2>
            </div>
            
            <flux:button 
                variant="primary" 
                href="{{ route('coupons.create') }}"
                class="bg-black hover:bg-[#494949]"
            >
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Generar Cupón
            </flux:button>
        </div>

        {{-- Filtros --}}
        <div class="bg-white rounded-lg shadow p-6 mb-6">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="md:col-span-3">
                    <flux:input 
                        name="search"
                        :value="request('search')"
                        placeholder="Buscar por código..."
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                <flux:select name="status">
                    <option value="">Todos los estados</option>
                    <option value="available" @selected(request('status') === 'available')>Disponibles</option>
                    <option value="used" @selected(request('status') === 'used')>Usados</option>
                    <option value="expired" @selected(request('status') === 'expired')>Expirados</option>
                </flux:select>
            </form>
        </div>

        {{-- Tabla de cupones --}}
        <div class="bg-white rounded-lg shadow overflow-hidden">
            <flux:table>
                <flux:thead>
                    <flux:tr>
                        <flux:th>Código</flux:th>
                        <flux:th>Descuento</flux:th>
                        <flux:th>Estado</flux:th>
                        <flux:th>Expira</flux:th>
                        <flux:th>Usado Por</flux:th>
                        <flux:th class="text-center">Acciones</flux:th>
                    </flux:tr>
                </flux:thead>
                <flux:tbody>
                    @forelse($coupons as $coupon)
                        <flux:tr wire:key="coupon-{{ $coupon->id }}">
                            <flux:td>
                                <div class="flex items-center">
                                    <x-heroicon-o-ticket class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="font-mono font-bold text-lg">{{ $coupon->code }}</span>
                                </div>
                            </flux:td>
                            <flux:td>
                                <span class="text-2xl font-bold text-green-600">
                                    {{ $coupon->discount_percentage }}%
                                </span>
                            </flux:td>
                            <flux:td>
                                @if($coupon->is_used)
                                    <flux:badge variant="gray">
                                        <x-heroicon-o-check-badge class="w-4 h-4 mr-1" />
                                        Usado
                                    </flux:badge>
                                @elseif($coupon->isExpired())
                                    <flux:badge variant="danger">
                                        <x-heroicon-o-clock class="w-4 h-4 mr-1" />
                                        Expirado
                                    </flux:badge>
                                @elseif($coupon->is_active)
                                    <flux:badge variant="success">
                                        <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                        Disponible
                                    </flux:badge>
                                @else
                                    <flux:badge variant="warning">
                                        <x-heroicon-o-pause-circle class="w-4 h-4 mr-1" />
                                        Inactivo
                                    </flux:badge>
                                @endif
                            </flux:td>
                            <flux:td>
                                <div class="text-sm">
                                    {{ $coupon->expiration_date->format('d/m/Y') }}
                                </div>
                                <div class="text-xs text-gray-500">
                                    @if($coupon->isExpired())
                                        Expiró {{ $coupon->expiration_date->diffForHumans() }}
                                    @else
                                        Expira {{ $coupon->expiration_date->diffForHumans() }}
                                    @endif
                                </div>
                            </flux:td>
                            <flux:td>
                                @if($coupon->usedByBusiness)
                                    <div class="text-sm">
                                        <p class="font-medium text-gray-900">{{ $coupon->usedByBusiness->business_name }}</p>
                                        <p class="text-xs text-gray-500">{{ $coupon->used_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                @else
                                    <span class="text-gray-400 text-sm">Sin usar</span>
                                @endif
                            </flux:td>
                            <flux:td>
                                <div class="flex justify-center space-x-2">
                                    <flux:button 
                                        variant="primary" 
                                        outline 
                                        size="sm"
                                        href="{{ route('coupons.show', $coupon) }}"
                                        title="Ver detalles"
                                    >
                                        <x-heroicon-o-eye class="w-4 h-4" />
                                    </flux:button>

                                    @if(!$coupon->is_used)
                                        <flux:button 
                                            variant="warning" 
                                            outline 
                                            size="sm"
                                            href="{{ route('coupons.edit', $coupon) }}"
                                            title="Editar cupón"
                                        >
                                            <x-heroicon-o-pencil class="w-4 h-4" />
                                        </flux:button>

                                        <flux:button 
                                            variant="danger" 
                                            outline 
                                            size="sm"
                                            onclick="confirmCouponDelete({{ $coupon->id }})"
                                            title="Eliminar cupón"
                                        >
                                            <x-heroicon-o-trash class="w-4 h-4" />
                                        </flux:button>
                                    @endif
                                </div>
                            </flux:td>
                        </flux:tr>
                    @empty
                        <flux:tr>
                            <flux:td colspan="6">
                                <x-empty-state
                                    icon="ticket"
                                    title="No se encontraron cupones"
                                    description="No hay cupones que coincidan con los filtros seleccionados"
                                    actionText="Generar Primer Cupón"
                                    actionUrl="{{ route('coupons.create') }}"
                                />
                            </flux:td>
                        </flux:tr>
                    @endforelse
                </flux:tbody>
            </flux:table>

            <div class="px-6 py-4 border-t">
                {{ $coupons->links() }}
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function confirmCouponDelete(couponId) {
            Swal.fire({
                title: '¿Eliminar cupón?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium',
                    cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium ml-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    const form = document.createElement('form');
                    form.method = 'POST';
                    form.action = `/coupons/${couponId}`;
                    
                    const csrfInput = document.createElement('input');
                    csrfInput.type = 'hidden';
                    csrfInput.name = '_token';
                    csrfInput.value = '{{ csrf_token() }}';
                    
                    const methodInput = document.createElement('input');
                    methodInput.type = 'hidden';
                    methodInput.name = '_method';
                    methodInput.value = 'DELETE';
                    
                    form.appendChild(csrfInput);
                    form.appendChild(methodInput);
                    document.body.appendChild(form);
                    form.submit();
                }
            });
        }
    </script>
    @endpush
</x-app-layout>