<x-app-layout>
    @section('page-title', 'Editar Paquete')

    <div class="max-w-3xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('packages.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-pencil class="w-8 h-8 mr-3" />
                    Editar Paquete
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Modifique las características del paquete.
                </p>
            </div>

            <form action="{{ route('packages.update', $package) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre --}}
                <div>
                    <flux:input 
                        label="Nombre del paquete"
                        name="name"
                        :value="old('name', $package->name)"
                        required
                        :error="$errors->first('name')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-cube class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Precio y duración --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <flux:input 
                            label="Precio"
                            name="price"
                            type="number"
                            step="0.01"
                            min="0"
                            :value="old('price', $package->price)"
                            required
                            :error="$errors->first('price')"
                        >
                            <x-slot:iconLeading>
                                <span class="text-gray-500">$</span>
                            </x-slot:iconLeading>
                        </flux:input>
                    </div>

                    <div>
                        <flux:input 
                            label="Duración (días)"
                            name="duration_days"
                            type="number"
                            min="1"
                            :value="old('duration_days', $package->duration_days)"
                            required
                            :error="$errors->first('duration_days')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-calendar class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>
                    </div>
                </div>

                {{-- Órdenes máximas --}}
                <div>
                    <flux:input 
                        label="Órdenes máximas (dejar vacío para ilimitado)"
                        name="max_orders"
                        type="number"
                        min="1"
                        :value="old('max_orders', $package->max_orders)"
                        :error="$errors->first('max_orders')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-shopping-bag class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                    <p class="mt-1 text-sm text-gray-500">
                        Actualmente: {{ $package->max_orders ? number_format($package->max_orders) : 'Ilimitado' }}
                    </p>
                </div>

                {{-- Retención de datos --}}
                <div>
                    <flux:input 
                        label="Retención de datos (días)"
                        name="data_retention_days"
                        type="number"
                        min="1"
                        :value="old('data_retention_days', $package->data_retention_days)"
                        required
                        :error="$errors->first('data_retention_days')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-server class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Características --}}
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4">Características del Paquete</h3>
                    <div class="space-y-3">
                        <div class="flex items-center">
                            <flux:checkbox 
                                name="has_reports"
                                :checked="old('has_reports', $package->has_reports)"
                            />
                            <label class="ml-2 text-sm text-gray-700">
                                Acceso a reportes avanzados
                            </label>
                        </div>

                        <div class="flex items-center">
                            <flux:checkbox 
                                name="has_statistics"
                                :checked="old('has_statistics', $package->has_statistics)"
                            />
                            <label class="ml-2 text-sm text-gray-700">
                                Estadísticas detalladas
                            </label>
                        </div>

                        <div class="flex items-center">
                            <flux:checkbox 
                                name="has_filters"
                                :checked="old('has_filters', $package->has_filters)"
                            />
                            <label class="ml-2 text-sm text-gray-700">
                                Filtros avanzados
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Estado activo --}}
                <div class="flex items-center pt-4 border-t">
                    <flux:checkbox 
                        name="is_active"
                        :checked="old('is_active', $package->is_active)"
                    />
                    <label class="ml-2 text-sm text-gray-700">
                        Paquete activo (disponible para contratación)
                    </label>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button 
                        variant="ghost" 
                        type="button"
                        href="{{ route('packages.index') }}"
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
</x-app-layout>
