{{--
/**
 * Nombre de la vista           : create.blade.php
 * Descripción de la vista      : Formulario para registrar un nuevo negocio
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 1.0
 * Fecha de mantenimiento       : 
 * Folio de mantenimiento       : 
 * Tipo de mantenimiento        :
 * Descripción del mantenimiento: 
 * Responsable                  : 
 * Revisor                      : 
 */
 --}}
 <x-layouts.app.sidebar>
    @section('page-title', 'Registrar Negocio')

    <div class="max-w-4xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('dashboard') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-building-office class="w-8 h-8 mr-3" />
                    Registrar Nuevo Negocio
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Complete todos los datos legales de su negocio para poder operar en la plataforma.
                </p>
            </div>

            <form action="{{ route('business.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                @csrf

                {{-- Información General --}}
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-information-circle class="w-6 h-6 mr-2" />
                        Información General
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input 
                            label="Nombre del Negocio"
                            name="business_name"
                            :value="old('business_name')"
                            required
                            placeholder="Ej: Lavandería Express"
                            :error="$errors->first('business_name')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-building-storefront class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input 
                            label="Razón Social"
                            name="legal_name"
                            :value="old('legal_name')"
                            required
                            placeholder="Ej: Lavandería Express S.A. de C.V."
                            :error="$errors->first('legal_name')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-document-text class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input 
                            label="RFC / Identificador Fiscal"
                            name="tax_id"
                            :value="old('tax_id')"
                            required
                            placeholder="Ej: LEX850614HF3"
                            :error="$errors->first('tax_id')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-identification class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">
                                Logo del Negocio (opcional)
                            </label>
                            <input 
                                type="file" 
                                name="logo"
                                accept="image/*"
                                class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-black file:text-white hover:file:bg-[#494949] cursor-pointer"
                            />
                            @error('logo')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <flux:textarea 
                            label="Descripción del Negocio"
                            name="description"
                            rows="3"
                            placeholder="Describe brevemente los servicios que ofrece tu negocio..."
                            :error="$errors->first('description')"
                        >{{ old('description') }}</flux:textarea>
                    </div>
                </div>

                {{-- Información de Contacto --}}
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-phone class="w-6 h-6 mr-2" />
                        Información de Contacto
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input 
                            label="Teléfono"
                            name="phone"
                            type="tel"
                            :value="old('phone')"
                            required
                            placeholder="+52 123 456 7890"
                            :error="$errors->first('phone')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-phone class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input 
                            label="Email (opcional)"
                            name="email"
                            type="email"
                            :value="old('email')"
                            placeholder="contacto@negocio.com"
                            :error="$errors->first('email')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-envelope class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <div class="md:col-span-2">
                            <flux:input 
                                label="Sitio Web (opcional)"
                                name="website"
                                type="url"
                                :value="old('website')"
                                placeholder="https://www.sunegocio.com"
                                :error="$errors->first('website')"
                            >
                                <x-slot:iconTrailing>
                                    <x-heroicon-o-globe-alt class="w-5 h-5" />
                                </x-slot:iconTrailing>
                            </flux:input>
                        </div>
                    </div>
                </div>

                {{-- Dirección --}}
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-map-pin class="w-6 h-6 mr-2" />
                        Dirección
                    </h3>

                    <div class="grid grid-cols-1 gap-4">
                        <flux:input 
                            label="Dirección"
                            name="address"
                            :value="old('address')"
                            required
                            placeholder="Calle, número, colonia"
                            :error="$errors->first('address')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-home class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <flux:input 
                                label="Ciudad"
                                name="city"
                                :value="old('city')"
                                required
                                placeholder="Ciudad"
                                :error="$errors->first('city')"
                            />

                            <flux:input 
                                label="Estado"
                                name="state"
                                :value="old('state')"
                                required
                                placeholder="Estado"
                                :error="$errors->first('state')"
                            />

                            <flux:input 
                                label="Código Postal"
                                name="postal_code"
                                :value="old('postal_code')"
                                required
                                placeholder="12345"
                                :error="$errors->first('postal_code')"
                            />
                        </div>

                        <flux:input 
                            label="País"
                            name="country"
                            :value="old('country', 'México')"
                            required
                            :error="$errors->first('country')"
                        />
                    </div>
                </div>

                {{-- Ubicación GPS --}}
                <div class="border-b pb-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-map class="w-6 h-6 mr-2" />
                        Ubicación GPS (opcional)
                    </h3>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <flux:input 
                            label="Latitud"
                            name="latitude"
                            type="number"
                            step="any"
                            :value="old('latitude')"
                            placeholder="19.432608"
                            :error="$errors->first('latitude')"
                        />

                        <flux:input 
                            label="Longitud"
                            name="longitude"
                            type="number"
                            step="any"
                            :value="old('longitude')"
                            placeholder="-99.133209"
                            :error="$errors->first('longitude')"
                        />
                    </div>

                    <p class="mt-2 text-sm text-gray-500 flex items-center">
                        <x-heroicon-o-information-circle class="w-4 h-4 mr-1" />
                        La ubicación GPS ayuda a los usuarios móviles a encontrar negocios cercanos.
                    </p>
                </div>

                {{-- Configuraciones --}}
                <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-cog-6-tooth class="w-6 h-6 mr-2" />
                        Configuraciones
                    </h3>

                    <div class="space-y-4">
                        <flux:input 
                            label="Período de entrega (minutos)"
                            name="delivery_period_minutes"
                            type="number"
                            min="5"
                            :value="old('delivery_period_minutes', 30)"
                            required
                            :error="$errors->first('delivery_period_minutes')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-clock class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                            <flux:checkbox 
                                label="Permitir que los usuarios califiquen mi negocio"
                                name="can_be_rated"
                                checked
                            />
                        </div>
                    </div>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button 
                        variant="ghost" 
                        type="button"
                        href="{{ route('dashboard') }}"
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
                        Registrar Negocio
                    </flux:button>
                </div>
            </form>
        </div>
    </div>

    @push('scripts')
    <script>
        // Prevenir envío múltiple del formulario
        document.querySelector('form').addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Registrando...';
        });
    </script>
    @endpush
 </x-layouts.app.sidebar>