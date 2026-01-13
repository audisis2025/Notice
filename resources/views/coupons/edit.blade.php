
<x-layouts.app.sidebar>
    @section('page-title', 'Editar Cupón')

    <div class="max-w-3xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('coupons.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-pencil class="w-8 h-8 mr-3" />
                    Editar Cupón
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Modifique los parámetros del cupón de descuento.
                </p>
            </div>

            <form action="{{ route('coupons.update', $coupon) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Código del cupón (readonly) --}}
                <div>
                    <flux:input 
                        label="Código del cupón"
                        name="code"
                        :value="$coupon->code"
                        readonly
                        disabled
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-ticket class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                    <p class="mt-1 text-sm text-gray-500">
                        El código no se puede modificar una vez creado
                    </p>
                </div>

                {{-- Porcentaje de descuento --}}
                <div>
                    <flux:input 
                        label="Porcentaje de descuento"
                        name="discount_percentage"
                        type="number"
                        min="1"
                        max="100"
                        :value="old('discount_percentage', $coupon->discount_percentage)"
                        required
                        :error="$errors->first('discount_percentage')"
                    >
                        <x-slot:iconTrailing>
                            <span class="text-gray-500">%</span>
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Fecha de expiración --}}
                <div>
                    <flux:input 
                        label="Fecha de expiración"
                        name="expiration_date"
                        type="date"
                        :value="old('expiration_date', $coupon->expiration_date->format('Y-m-d'))"
                        required
                        :min="now()->format('Y-m-d')"
                        :error="$errors->first('expiration_date')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Estado activo --}}
                <div class="flex items-center pt-4 border-t">
                    <flux:checkbox 
                        name="is_active"
                        :checked="old('is_active', $coupon->is_active)"
                    />
                    <label class="ml-2 text-sm text-gray-700">
                        Cupón activo (puede ser utilizado)
                    </label>
                </div>

                {{-- Estado de uso --}}
                @if($coupon->is_used)
                    <div class="bg-gray-50 border-l-4 border-gray-400 p-4 rounded">
                        <div class="flex items-start">
                            <x-heroicon-o-check-badge class="w-5 h-5 text-gray-600 mr-3 mt-0.5" />
                            <div class="text-sm text-gray-700">
                                <p class="font-semibold mb-1">Cupón ya utilizado</p>
                                <p>Usado por: <strong>{{ $coupon->usedByBusiness->business_name }}</strong></p>
                                <p>Fecha de uso: {{ $coupon->used_at->format('d/m/Y H:i') }}</p>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button 
                        variant="ghost" 
                        type="button"
                        href="{{ route('coupons.index') }}"
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
                        Guardar Cambios
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app.sidebar>
