<x-layouts.app.sidebar>
    <x-flash-messages />

    @section('page-title', 'Editar Negocio')

    <div class="max-w-4xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button variant="ghost" href="{{ route('dashboard') }}">
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-building-storefront class="w-8 h-8 mr-3" />
                    Editar Información del Negocio
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Actualice la información de su negocio.
                </p>
            </div>

            <form action="{{ route('business.update') }}" method="POST" enctype="multipart/form-data"
                class="space-y-8">
                @csrf
                @method('PUT')

                {{-- Información General --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                        Información General
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input label="Nombre comercial del negocio" name="business_name"
                            :value="old('business_name', $business->business_name)" required
                            :error="$errors->first('business_name')">
                            <x-slot:iconTrailing>
                                <x-heroicon-o-building-storefront class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input label="Razón social" name="legal_name"
                            :value="old('legal_name', $business->legal_name)" required
                            :error="$errors->first('legal_name')">
                            <x-slot:iconTrailing>
                                <x-heroicon-o-document-text class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input label="RFC" name="rfc" :value="old('rfc', $business->rfc)" required
                            maxlength="13" :error="$errors->first('rfc')">
                            <x-slot:iconTrailing>
                                <x-heroicon-o-identification class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Logo del negocio
                            </label>
                            <input type="file" name="logo" accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-gray-800">
                            @if ($business->logo)
                                <p class="mt-2 text-xs text-gray-500">Logo actual disponible</p>
                            @endif
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-6">
                        <flux:textarea label="Descripción del negocio" name="description" rows="4"
                            :error="$errors->first('description')">{{ old('description', $business->description) }}
                        </flux:textarea>
                    </div>
                </div>

                {{-- Contacto --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                        Información de Contacto
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <flux:input label="Teléfono" name="phone" :value="old('phone', $business->phone)" required
                            :error="$errors->first('phone')">
                            <x-slot:iconTrailing>
                                <x-heroicon-o-phone class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input label="Correo electrónico (opcional)" name="email" type="email"
                            :value="old('email', $business->email)" :error="$errors->first('email')">
                            <x-slot:iconTrailing>
                                <x-heroicon-o-envelope class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input label="Sitio web (opcional)" name="website" type="url"
                            :value="old('website', $business->website)" placeholder="https://www.ejemplo.com"
                            :error="$errors->first('website')">
                            <x-slot:iconTrailing>
                                <x-heroicon-o-globe-alt class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>
                    </div>
                </div>

                {{-- Dirección --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                        Dirección
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="md:col-span-2">
                            <flux:input label="Dirección completa" name="address"
                                :value="old('address', $business->address)" required
                                :error="$errors->first('address')">
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-map-pin class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                        </div>

                        <flux:input label="Ciudad" name="city" :value="old('city', $business->city)" required
                            :error="$errors->first('city')" />

                        <flux:input label="Estado" name="state" :value="old('state', $business->state)" required
                            :error="$errors->first('state')" />

                        <flux:input label="Código postal" name="postal_code"
                            :value="old('postal_code', $business->postal_code)" required maxlength="5"
                            :error="$errors->first('postal_code')" />

                        <flux:input label="País" name="country" :value="old('country', $business->country)" required
                            :error="$errors->first('country')" />
                    </div>
                </div>

                {{-- Configuraciones --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-2 border-b">
                        Configuraciones
                    </h3>

                    <div class="space-y-4">
                        <div>
                            <flux:input label="Período de entrega (minutos)" name="delivery_period_minutes"
                                type="number" min="1"
                                :value="old('delivery_period_minutes', $business->delivery_period_minutes)" required
                                :error="$errors->first('delivery_period_minutes')">
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-clock class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                            <p class="mt-1 text-sm text-gray-500">
                                Tiempo promedio para preparar una orden. Si excede este tiempo, se habilita el chat.
                            </p>
                        </div>

                        <div class="flex items-center">
                            <flux:checkbox name="can_be_rated"
                                :checked="old('can_be_rated', $business->can_be_rated)" />
                            <label class="ml-2 text-sm text-gray-700">
                                Permitir que los clientes califiquen mi negocio
                            </label>
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button variant="ghost" type="button" href="{{ route('dashboard') }}">
                        <x-heroicon-o-x-mark class="w-5 h-5 mr-2" />
                        Cancelar
                    </flux:button>

                    <flux:button variant="primary" type="submit" class="bg-black hover:bg-[#494949]">
                        <x-heroicon-o-check class="w-5 h-5 mr-2" />
                        Guardar Cambios
                    </flux:button>
                </div>
            </form>
        </div>
    </div>
</x-layouts.app.sidebar>
