<x-layouts.app.sidebar>
    <x-flash-messages />
    @section('page-title', 'Cupón ' . $coupon->code)

    <div class="max-w-4xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-between items-center mb-6">
            <flux:button 
                variant="ghost" 
                href="{{ route('coupons.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Volver al listado
            </flux:button>

            @if(!$coupon->is_used)
                <flux:button 
                    variant="warning"
                    href="{{ route('coupons.edit', $coupon) }}"
                >
                    <x-heroicon-o-pencil class="w-5 h-5 mr-2" />
                    Editar
                </flux:button>
            @endif
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-purple-600 to-purple-700 px-8 py-10 text-center">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-white rounded-full mb-4">
                    <x-heroicon-o-ticket class="w-12 h-12 text-purple-600" />
                </div>
                <h1 class="text-4xl font-bold text-white mb-2 font-mono">{{ $coupon->code }}</h1>
                <p class="text-2xl font-bold text-purple-100">{{ $coupon->discount_percentage }}% de descuento</p>
            </div>

            {{-- Contenido --}}
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    {{-- Información del cupón --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-information-circle class="w-6 h-6 mr-2" />
                            Información del Cupón
                        </h3>

                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Código</dt>
                                <dd class="mt-1 text-lg font-mono font-bold text-gray-900">{{ $coupon->code }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Descuento</dt>
                                <dd class="mt-1 text-2xl font-bold text-green-600">{{ $coupon->discount_percentage }}%</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de expiración</dt>
                                <dd class="mt-1 text-sm text-gray-900">
                                    {{ $coupon->expiration_date->format('d/m/Y') }}
                                    @if($coupon->isExpired())
                                        <flux:badge variant="danger" class="ml-2">Expirado</flux:badge>
                                    @else
                                        <span class="text-gray-500">({{ $coupon->expiration_date->diffForHumans() }})</span>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    @if($coupon->is_used)
                                        <flux:badge variant="gray">Usado</flux:badge>
                                    @elseif($coupon->isExpired())
                                        <flux:badge variant="danger">Expirado</flux:badge>
                                    @elseif($coupon->is_active)
                                        <flux:badge variant="success">Disponible</flux:badge>
                                    @else
                                        <flux:badge variant="warning">Inactivo</flux:badge>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Creado</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $coupon->created_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>

                    {{-- Estado de uso --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-clock class="w-6 h-6 mr-2" />
                            Estado de Uso
                        </h3>

                        @if($coupon->is_used)
                            <div class="bg-gray-50 rounded-lg p-6">
                                <div class="flex items-center mb-4">
                                    <x-heroicon-o-check-badge class="w-8 h-8 text-green-600 mr-3" />
                                    <div>
                                        <p class="font-semibold text-gray-900">Cupón Utilizado</p>
                                        <p class="text-sm text-gray-600">{{ $coupon->used_at->format('d/m/Y H:i') }}</p>
                                    </div>
                                </div>

                                <div class="border-t pt-4">
                                    <p class="text-sm font-medium text-gray-500 mb-2">Usado por:</p>
                                    <div class="flex items-center">
                                        <x-heroicon-o-building-storefront class="w-5 h-5 text-gray-400 mr-2" />
                                        <span class="font-medium text-gray-900">{{ $coupon->usedByBusiness->business_name }}</span>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="bg-green-50 rounded-lg p-6 text-center">
                                <x-heroicon-o-ticket class="w-12 h-12 text-green-600 mx-auto mb-3" />
                                <p class="font-semibold text-green-900 mb-1">Cupón Disponible</p>
                                <p class="text-sm text-green-700">Este cupón aún no ha sido utilizado</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Instrucciones de uso --}}
                @if(!$coupon->is_used && !$coupon->isExpired() && $coupon->is_active)
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">Cómo usar este cupón</h3>
                        <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                            <ol class="list-decimal list-inside space-y-2 text-sm text-blue-800">
                                <li>Comparte el código <strong class="font-mono">{{ $coupon->code }}</strong> con el negocio</li>
                                <li>Al contratar un paquete, el negocio ingresará el código</li>
                                <li>El descuento se aplicará automáticamente al precio final</li>
                                <li>El cupón solo se puede usar una vez</li>
                            </ol>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-layouts.app.sidebar>
