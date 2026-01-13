<x-layouts.app.sidebar>
    @section('page-title', 'Generar Cupón')

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
                    <x-heroicon-o-ticket class="w-8 h-8 mr-3" />
                    Generar Nuevo Cupón
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Configure los parámetros del cupón de descuento.
                </p>
            </div>

            <form action="{{ route('coupons.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Código del cupón --}}
                <div>
                    <flux:input 
                        label="Código del cupón"
                        name="code"
                        :value="old('code', strtoupper(Str::random(8)))"
                        required
                        placeholder="Ej: PROMO2025"
                        maxlength="50"
                        :error="$errors->first('code')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-ticket class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                    <p class="mt-1 text-sm text-gray-500 flex items-center">
                        <x-heroicon-o-information-circle class="w-4 h-4 mr-1" />
                        El código es único y no distingue entre mayúsculas y minúsculas
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
                        :value="old('discount_percentage')"
                        required
                        placeholder="Ej: 20"
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
                        :value="old('expiration_date', now()->addDays(30)->format('Y-m-d'))"
                        required
                        :min="now()->format('Y-m-d')"
                        :error="$errors->first('expiration_date')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-calendar class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                    <p class="mt-1 text-sm text-gray-500">
                        El cupón expirará a las 23:59 de esta fecha
                    </p>
                </div>

                {{-- Estado activo --}}
                <div class="flex items-center pt-4 border-t">
                    <flux:checkbox 
                        name="is_active"
                        :checked="old('is_active', true)"
                    />
                    <label class="ml-2 text-sm text-gray-700">
                        Cupón activo (puede ser utilizado)
                    </label>
                </div>

                {{-- Información adicional --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex items-start">
                        <x-heroicon-o-information-circle class="w-5 h-5 text-blue-600 mr-3 mt-0.5" />
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">Características del cupón:</p>
                            <ul class="list-disc list-inside space-y-1">
                                <li>El cupón puede usarse una sola vez</li>
                                <li>Es válido solo para la contratación de paquetes</li>
                                <li>No se puede combinar con otras promociones</li>
                                <li>Una vez usado, no puede reutilizarse</li>
                            </ul>
                        </div>
                    </div>
                </div>

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
                        Generar Cupón
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app.sidebar>
