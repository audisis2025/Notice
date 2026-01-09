<x-app-layout>
    @section('page-title', 'Crear Orden')

    <div class="max-w-3xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('orders.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-shopping-bag class="w-8 h-8 mr-3" />
                    Crear Nueva Orden
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Complete los datos de la orden. Una vez marcada como pagada, se generará el código QR para asociar con el cliente.
                </p>
            </div>

            <form action="{{ route('orders.store') }}" method="POST" class="space-y-6">
                @csrf

                {{-- Descripción --}}
                <div>
                    <flux:textarea 
                        label="Descripción de la orden"
                        name="description"
                        rows="4"
                        required
                        placeholder="Ej: 3 pantalones, 2 camisas, lavado y planchado"
                        :error="$errors->first('description')"
                    >{{ old('description') }}</flux:textarea>
                    <p class="mt-1 text-sm text-gray-500 flex items-center">
                        <x-heroicon-o-information-circle class="w-4 h-4 mr-1" />
                        Sea específico con los detalles del servicio
                    </p>
                </div>

                {{-- Monto --}}
                <div>
                    <flux:input 
                        label="Monto total"
                        name="amount"
                        type="number"
                        step="0.01"
                        min="0"
                        :value="old('amount')"
                        required
                        placeholder="0.00"
                        :error="$errors->first('amount')"
                    >
                        <x-slot:iconLeading>
                            <span class="text-gray-500">$</span>
                        </x-slot:iconLeading>
                        <x-slot:iconTrailing>
                            <x-heroicon-o-currency-dollar class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Información adicional --}}
                <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded">
                    <div class="flex items-start">
                        <x-heroicon-o-light-bulb class="w-5 h-5 text-blue-600 mr-3 mt-0.5" />
                        <div class="text-sm text-blue-800">
                            <p class="font-semibold mb-1">¿Qué sucede después?</p>
                            <ol class="list-decimal list-inside space-y-1">
                                <li>La orden se creará en estado "Pendiente"</li>
                                <li>Marca la orden como "Pagada" para generar el QR de asociación</li>
                                <li>El cliente escanea el QR para asociar la orden a su cuenta</li>
                                <li>Cuando esté lista, márcala como "Lista" para notificar al cliente</li>
                                <li>Se generará un QR de entrega que el cliente debe escanear</li>
                            </ol>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button 
                        variant="ghost" 
                        type="button"
                        href="{{ route('orders.index') }}"
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
                        Crear Orden
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>