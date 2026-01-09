<x-app-layout>
    @section('page-title', 'Contratar ' . $package->name)

    <div class="max-w-4xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('packages.available') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Ver todos los paquetes
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Resumen del paquete --}}
            <div class="lg:col-span-1">
                <div class="bg-white rounded-lg shadow-lg overflow-hidden sticky top-6">
                    <div class="bg-gradient-to-r @if($package->max_orders === null) from-purple-600 to-purple-700 @else from-gray-800 to-gray-900 @endif p-6 text-white">
                        <h3 class="text-2xl font-bold mb-2">{{ $package->name }}</h3>
                        <div class="flex items-baseline">
                            <span class="text-4xl font-bold">${{ number_format($package->price, 2) }}</span>
                        </div>
                        <p class="mt-2 text-sm opacity-90">{{ $package->duration_days }} días</p>
                    </div>

                    <div class="p-6 space-y-3 text-sm">
                        @if($package->max_orders === null)
                            <div class="flex items-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Órdenes ilimitadas</span>
                            </div>
                        @else
                            <div class="flex items-center">
                                <x-heroicon-o-shopping-bag class="w-5 h-5 text-blue-500 mr-2" />
                                <span>Hasta {{ number_format($package->max_orders) }} órdenes</span>
                            </div>
                        @endif

                        @if($package->has_reports)
                            <div class="flex items-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Reportes avanzados</span>
                            </div>
                        @endif

                        @if($package->has_statistics)
                            <div class="flex items-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Estadísticas detalladas</span>
                            </div>
                        @endif

                        @if($package->has_filters)
                            <div class="flex items-center">
                                <x-heroicon-o-check-circle class="w-5 h-5 text-green-500 mr-2" />
                                <span>Filtros avanzados</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Formulario de pago --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow-lg p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6 flex items-center">
                        <x-heroicon-o-credit-card class="w-8 h-8 mr-3" />
                        Información de Pago
                    </h2>

                    <form action="{{ route('packages.contract') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="package_id" value="{{ $package->id }}">

                        {{-- Código de cupón --}}
                        <div>
                            <flux:input 
                                label="Código de cupón (opcional)"
                                name="coupon_code"
                                :value="old('coupon_code')"
                                placeholder="Ej: PROMO50"
                                :error="$errors->first('coupon_code')"
                            >
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-ticket class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                            <p class="mt-1 text-xs text-gray-500">Si tienes un cupón de descuento, ingrésalo aquí</p>
                        </div>

                        {{-- Método de pago --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Método de pago</label>
                            <flux:select 
                                name="payment_method"
                                required
                            >
                                <option value="credit_card">Tarjeta de Crédito</option>
                                <option value="debit_card">Tarjeta de Débito</option>
                            </flux:select>
                        </div>

                        {{-- Número de tarjeta --}}
                        <div>
                            <flux:input 
                                label="Número de tarjeta"
                                name="card_number"
                                :value="old('card_number')"
                                required
                                placeholder="1234 5678 9012 3456"
                                maxlength="16"
                                :error="$errors->first('card_number')"
                            >
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-credit-card class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                            <p class="mt-1 text-xs text-gray-500 flex items-center">
                                <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
                                Esta es una simulación de pago. Datos seguros.
                            </p>
                        </div>

                        {{-- Nombre del titular --}}
                        <div>
                            <flux:input 
                                label="Nombre del titular"
                                name="card_holder"
                                :value="old('card_holder')"
                                required
                                placeholder="Como aparece en la tarjeta"
                                :error="$errors->first('card_holder')"
                            >
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-user class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                        </div>

                        {{-- Fecha de expiración y CVV --}}
                        <div class="grid grid-cols-2 gap-4">
                            <flux:input 
                                label="Fecha de expiración"
                                name="card_expiry"
                                :value="old('card_expiry')"
                                required
                                placeholder="MM/AA"
                                maxlength="5"
                                :error="$errors->first('card_expiry')"
                            />

                            <flux:input 
                                label="CVV"
                                name="card_cvv"
                                :value="old('card_cvv')"
                                required
                                placeholder="123"
                                maxlength="3"
                                type="password"
                                :error="$errors->first('card_cvv')"
                            >
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-lock-closed class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                        </div>

                        {{-- Términos y condiciones --}}
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <div class="flex items-start">
                                <flux:checkbox 
                                    name="terms"
                                    required
                                />
                                <label class="ml-2 text-sm text-gray-700">
                                    Acepto los términos y condiciones del servicio. Entiendo que el pago se procesará
                                    inmediatamente y mi paquete se activará por {{ $package->duration_days }} días.
                                </label>
                            </div>
                        </div>

                        {{-- Botones --}}
                        <div class="flex justify-end space-x-3 pt-6 border-t">
                            <flux:button 
                                variant="ghost" 
                                type="button"
                                href="{{ route('packages.available') }}"
                            >
                                <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                                Cancelar
                            </flux:button>

                            <flux:button 
                                variant="primary"
                                type="submit"
                                class="bg-black hover:bg-[#494949]"
                            >
                                <x-heroicon-o-check class="w-5 h-5 mr-2" />
                                Confirmar y Pagar ${{ number_format($package->price, 2) }}
                            </flux:button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>