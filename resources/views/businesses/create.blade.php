{{--
/**
 * Nombre de la vista           : create.blade.php
 * Descripción de la vista      : Formulario para registrar un nuevo negocio
 *                                Con estilo estándar de la aplicación
 * Fecha de creación            : 09/01/2026
 * Elaboró                      : Jesús Núñez
 * Fecha de liberación          : 09/01/2026
 * Autorizó                     : Jesús Núñez
 * Versión                      : 2.0
 * Fecha de mantenimiento       : 15/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Perfectivo
 * Descripción del mantenimiento: Actualización a estándar visual de la app
 * Responsable                  : Sistema
 * Revisor                      : Jesús Núñez
 */
--}}
<x-layouts.app.sidebar>
    <div class="p-6">
        <div class="mb-6">
            <h1 class="text-3xl font-bold text-black dark:text-white">
                {{ __('Registrar Nuevo Negocio') }}
            </h1>
            <p class="text-gray-600 dark:text-gray-400 mt-2">
                Complete todos los datos de su negocio para comenzar a operar en la plataforma
            </p>
        </div>

        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow-sm border border-gray-200 dark:border-zinc-700">
            <div class="p-6">
                <form action="{{ route('business.store') }}" method="POST" enctype="multipart/form-data" 
                    class="space-y-8" id="businessForm">
                    @csrf

                    {{-- Información General --}}
                    <div>
                        <h2 class="text-xl font-bold text-black dark:text-white mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M13.5 21v-7.5a.75.75 0 01.75-.75h3a.75.75 0 01.75.75V21m-4.5 0H2.36m11.14 0H18m0 0h3.64m-1.39 0V9.349m-16.5 11.65V9.35m0 0a3.001 3.001 0 003.75-.615A2.993 2.993 0 009.75 9.75c.896 0 1.7-.393 2.25-1.016a2.993 2.993 0 002.25 1.016c.896 0 1.7-.393 2.25-1.016a3.001 3.001 0 003.75.614m-16.5 0a3.004 3.004 0 01-.621-4.72L4.318 3.44A1.5 1.5 0 015.378 3h13.243a1.5 1.5 0 011.06.44l1.19 1.189a3 3 0 01-.621 4.72m-13.5 8.65h3.75a.75.75 0 00.75-.75V13.5a.75.75 0 00-.75-.75H6.75a.75.75 0 00-.75.75v3.75c0 .415.336.75.75.75z" />
                            </svg>
                            {{ __('Información General') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="business_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Nombre del Negocio <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="business_name" id="business_name"
                                    value="{{ old('business_name') }}" required
                                    placeholder="Ej: Lavandería Express"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('business_name') border-red-500 @enderror" />
                                @error('business_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="legal_name"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Razón Social <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="legal_name" id="legal_name"
                                    value="{{ old('legal_name') }}" required
                                    placeholder="Ej: Lavandería Express S.A. de C.V."
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('legal_name') border-red-500 @enderror" />
                                @error('legal_name')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="tax_id"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    RFC / Identificador Fiscal <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="tax_id" id="tax_id" value="{{ old('tax_id') }}"
                                    required placeholder="Ej: LEX850614HF3"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('tax_id') border-red-500 @enderror" />
                                @error('tax_id')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="logo"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Logo del Negocio (opcional)
                                </label>
                                <input type="file" name="logo" id="logo" accept="image/*"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:text-sm file:font-semibold file:bg-gray-600 file:text-white hover:file:bg-gray-700" />
                                @error('logo')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="mt-4">
                            <label for="description"
                                class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Descripción del Negocio
                            </label>
                            <textarea name="description" id="description" rows="3"
                                placeholder="Describe brevemente los servicios que ofrece tu negocio..."
                                class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('description') border-red-500 @enderror">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    {{-- Información de Contacto --}}
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-8">
                        <h2 class="text-xl font-bold text-black dark:text-white mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M2.25 6.75c0 8.284 6.716 15 15 15h2.25a2.25 2.25 0 002.25-2.25v-1.372c0-.516-.351-.966-.852-1.091l-4.423-1.106c-.44-.11-.902.055-1.173.417l-.97 1.293c-.282.376-.769.542-1.21.38a12.035 12.035 0 01-7.143-7.143c-.162-.441.004-.928.38-1.21l1.293-.97c.363-.271.527-.734.417-1.173L6.963 3.102a1.125 1.125 0 00-1.091-.852H4.5A2.25 2.25 0 002.25 4.5v2.25z" />
                            </svg>
                            {{ __('Información de Contacto') }}
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="phone"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Teléfono <span class="text-red-500">*</span>
                                </label>
                                <input type="tel" name="phone" id="phone" value="{{ old('phone') }}"
                                    required placeholder="+52 123 456 7890"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('phone') border-red-500 @enderror" />
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="email"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Email (opcional)
                                </label>
                                <input type="email" name="email" id="email" value="{{ old('email') }}"
                                    placeholder="contacto@negocio.com"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('email') border-red-500 @enderror" />
                                @error('email')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label for="website"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Sitio Web (opcional)
                                </label>
                                <input type="url" name="website" id="website" value="{{ old('website') }}"
                                    placeholder="https://www.sunegocio.com"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('website') border-red-500 @enderror" />
                                @error('website')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Dirección --}}
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-8">
                        <h2 class="text-xl font-bold text-black dark:text-white mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M15 10.5a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1115 0z" />
                            </svg>
                            {{ __('Dirección') }}
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="address"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Dirección <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="address" id="address" value="{{ old('address') }}"
                                    required placeholder="Calle, número, colonia"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('address') border-red-500 @enderror" />
                                @error('address')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="city"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Ciudad <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="city" id="city" value="{{ old('city') }}"
                                        required placeholder="Ciudad"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('city') border-red-500 @enderror" />
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="state"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Estado <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="state" id="state" value="{{ old('state') }}"
                                        required placeholder="Estado"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('state') border-red-500 @enderror" />
                                    @error('state')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="postal_code"
                                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                        Código Postal <span class="text-red-500">*</span>
                                    </label>
                                    <input type="text" name="postal_code" id="postal_code"
                                        value="{{ old('postal_code') }}" required placeholder="12345"
                                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('postal_code') border-red-500 @enderror" />
                                    @error('postal_code')
                                        <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <div>
                                <label for="country"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    País <span class="text-red-500">*</span>
                                </label>
                                <input type="text" name="country" id="country"
                                    value="{{ old('country', 'México') }}" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('country') border-red-500 @enderror" />
                                @error('country')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Ubicación GPS --}}
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-8">
                        <h2 class="text-xl font-bold text-black dark:text-white mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9 6.75V15m6-6v8.25m.503 3.498l4.875-2.437c.381-.19.622-.58.622-1.006V4.82c0-.836-.88-1.38-1.628-1.006l-3.869 1.934c-.317.159-.69.159-1.006 0L9.503 3.252a1.125 1.125 0 00-1.006 0L3.622 5.689C3.24 5.88 3 6.27 3 6.695V19.18c0 .836.88 1.38 1.628 1.006l3.869-1.934c.317-.159.69-.159 1.006 0l4.994 2.497c.317.158.69.158 1.006 0z" />
                            </svg>
                            {{ __('Ubicación GPS') }} <span class="text-sm font-normal text-gray-500 ml-2">(opcional)</span>
                        </h2>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label for="latitude"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Latitud
                                </label>
                                <input type="number" name="latitude" id="latitude" step="any"
                                    value="{{ old('latitude') }}" placeholder="19.432608"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('latitude') border-red-500 @enderror" />
                                @error('latitude')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="longitude"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Longitud
                                </label>
                                <input type="number" name="longitude" id="longitude" step="any"
                                    value="{{ old('longitude') }}" placeholder="-99.133209"
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('longitude') border-red-500 @enderror" />
                                @error('longitude')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <div class="bg-blue-50 dark:bg-blue-900/20 border-l-4 border-blue-400 dark:border-blue-600 p-4 rounded mt-4">
                            <div class="flex items-start">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor"
                                    class="w-5 h-5 text-blue-600 dark:text-blue-400 mr-3 mt-0.5 flex-shrink-0">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                                </svg>
                                <p class="text-sm text-blue-700 dark:text-blue-400">
                                    La ubicación GPS ayuda a los usuarios móviles a encontrar negocios cercanos.
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Configuraciones --}}
                    <div class="border-t border-gray-200 dark:border-zinc-700 pt-8">
                        <h2 class="text-xl font-bold text-black dark:text-white mb-6 flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                                stroke="currentColor" class="w-6 h-6 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M9.594 3.94c.09-.542.56-.94 1.11-.94h2.593c.55 0 1.02.398 1.11.94l.213 1.281c.063.374.313.686.645.87.074.04.147.083.22.127.324.196.72.257 1.075.124l1.217-.456a1.125 1.125 0 011.37.49l1.296 2.247a1.125 1.125 0 01-.26 1.431l-1.003.827c-.293.24-.438.613-.431.992a6.759 6.759 0 010 .255c-.007.378.138.75.43.99l1.005.828c.424.35.534.954.26 1.43l-1.298 2.247a1.125 1.125 0 01-1.369.491l-1.217-.456c-.355-.133-.75-.072-1.076.124a6.57 6.57 0 01-.22.128c-.331.183-.581.495-.644.869l-.213 1.28c-.09.543-.56.941-1.11.941h-2.594c-.55 0-1.02-.398-1.11-.94l-.213-1.281c-.062-.374-.312-.686-.644-.87a6.52 6.52 0 01-.22-.127c-.325-.196-.72-.257-1.076-.124l-1.217.456a1.125 1.125 0 01-1.369-.49l-1.297-2.247a1.125 1.125 0 01.26-1.431l1.004-.827c.292-.24.437-.613.43-.992a6.932 6.932 0 010-.255c.007-.378-.138-.75-.43-.99l-1.004-.828a1.125 1.125 0 01-.26-1.43l1.297-2.247a1.125 1.125 0 011.37-.491l1.216.456c.356.133.751.072 1.076-.124.072-.044.146-.087.22-.128.332-.183.582-.495.644-.869l.214-1.281z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            {{ __('Configuraciones') }}
                        </h2>

                        <div class="space-y-4">
                            <div>
                                <label for="delivery_period_minutes"
                                    class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                    Período de entrega (minutos) <span class="text-red-500">*</span>
                                </label>
                                <input type="number" name="delivery_period_minutes" id="delivery_period_minutes"
                                    min="5" value="{{ old('delivery_period_minutes', 30) }}" required
                                    class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-gray-300 dark:focus:ring-gray-600 focus:border-transparent @error('delivery_period_minutes') border-red-500 @enderror" />
                                @error('delivery_period_minutes')
                                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="bg-gray-50 dark:bg-zinc-900 border border-gray-200 dark:border-zinc-700 rounded-lg p-4">
                                <div class="flex items-center">
                                    <input type="hidden" name="can_be_rated" value="0">
                                    <input type="checkbox" name="can_be_rated" id="can_be_rated" value="1"
                                        {{ old('can_be_rated', true) ? 'checked' : '' }}
                                        class="w-4 h-4 text-gray-900 bg-gray-100 border-gray-300 rounded focus:ring-gray-500 focus:ring-2" />
                                    <label for="can_be_rated"
                                        class="ml-3 text-sm font-medium text-gray-700 dark:text-gray-300">
                                        Permitir que los usuarios califiquen mi negocio
                                    </label>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end items-center pt-6 border-t border-gray-200 dark:border-zinc-700">
                        <div class="flex space-x-4">
                            <button type="submit"
                                class="inline-flex items-center px-6 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                Registrar Negocio
                            </button>
                            <a href="{{ route('dashboard') }}"
                                class="inline-flex items-center px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors font-medium">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                                Cancelar
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                const form = document.getElementById('businessForm');

                // Prevenir envío múltiple
                form.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = `
                        <svg class="animate-spin h-5 w-5 mr-2 inline" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" fill="none"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Registrando...
                    `;
                });

                // Mostrar alertas de sesión
                @if (session('success'))
                    showSuccess('{{ session('success') }}');
                @endif

                @if (session('error'))
                    showError('{{ session('error') }}');
                @endif

                @if ($errors->any())
                    showError(
                        '@foreach ($errors->all() as $error){{ $error }}<br>@endforeach',
                        'Error de validación');
                @endif
            });
        </script>
    @endpush

    @push('styles')
        <style>
            /* Animación para campos con error */
            .border-red-500 {
                animation: shake 0.3s;
            }

            @keyframes shake {
                0%, 100% {
                    transform: translateX(0);
                }
                25% {
                    transform: translateX(-5px);
                }
                75% {
                    transform: translateX(5px);
                }
            }
        </style>
    @endpush
</x-layouts.app.sidebar>