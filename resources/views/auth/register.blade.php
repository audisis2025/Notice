<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registro de Negocio</title>
    @vite(['resources/css/app.css'])
</head>
<body class="bg-gray-100">
    <div class="min-h-screen py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-3xl mx-auto">
            <div class="text-center mb-8">
                <h2 class="text-3xl font-extrabold text-gray-900">
                    Registro de Negocio
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Completa los siguientes pasos para registrar tu negocio
                </p>
            </div>

            <div class="bg-white shadow rounded-lg p-8">
                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    @if($errors->any())
                        <div class="mb-6 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                            <ul class="list-disc list-inside">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Paso 1: Datos del Negocio -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Paso 1: Datos del Negocio</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre del Negocio *</label>
                                <input type="text" name="business_name" value="{{ old('business_name') }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">RFC *</label>
                                <input type="text" name="rfc" value="{{ old('rfc') }}" required maxlength="13"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Razón Social *</label>
                                <input type="text" name="legal_name" value="{{ old('legal_name') }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <!-- Paso 2: Dirección y Contacto -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Paso 2: Dirección y Contacto</h3>
                        <div class="grid grid-cols-1 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Dirección *</label>
                                <textarea name="address" required rows="3"
                                          class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">{{ old('address') }}</textarea>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Teléfono *</label>
                                    <input type="text" name="phone" value="{{ old('phone') }}" required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                    <input type="email" name="email" value="{{ old('email') }}" required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 mb-2">Persona de Contacto *</label>
                                    <input type="text" name="contact_person" value="{{ old('contact_person') }}" required
                                           class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Paso 3: Usuario Administrador -->
                    <div class="mb-8">
                        <h3 class="text-lg font-semibold mb-4 text-gray-900">Paso 3: Usuario Administrador</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nombre Completo *</label>
                                <input type="text" name="admin_name" value="{{ old('admin_name') }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
                                <input type="email" name="admin_email" value="{{ old('admin_email') }}" required
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Contraseña *</label>
                                <input type="password" name="admin_password" required minlength="8"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Confirmar Contraseña *</label>
                                <input type="password" name="admin_password_confirmation" required minlength="8"
                                       class="w-full border border-gray-300 rounded-md px-3 py-2 focus:ring-blue-500 focus:border-blue-500">
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-between">
                        <a href="{{ route('login') }}" class="bg-gray-300 text-gray-700 px-6 py-2 rounded-md hover:bg-gray-400">
                            Cancelar
                        </a>
                        <button type="submit" class="bg-blue-600 text-white px-6 py-2 rounded-md hover:bg-blue-700">
                            Registrar Negocio
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>