<x-app-layout>
    @section('page-title', 'Editar Usuario')

    <div class="max-w-3xl mx-auto">
        {{-- Botón regresar --}}
        <div class="flex justify-end mb-4">
            <flux:button 
                variant="ghost" 
                href="{{ route('users.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="bg-white rounded-lg shadow-lg p-8">
            <div class="mb-8">
                <h2 class="text-2xl font-bold text-gray-900 flex items-center">
                    <x-heroicon-o-pencil class="w-8 h-8 mr-3" />
                    Editar Usuario
                </h2>
                <p class="mt-2 text-sm text-gray-600">
                    Modifique los datos del usuario. Deje la contraseña en blanco para mantener la actual.
                </p>
            </div>

            <form action="{{ route('users.update', $user) }}" method="POST" class="space-y-6">
                @csrf
                @method('PUT')

                {{-- Nombre completo --}}
                <div>
                    <flux:input 
                        label="Nombre completo"
                        name="name"
                        :value="old('name', $user->name)"
                        required
                        :error="$errors->first('name')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-user class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Email --}}
                <div>
                    <flux:input 
                        label="Correo electrónico (opcional)"
                        name="email"
                        type="email"
                        :value="old('email', $user->email)"
                        :error="$errors->first('email')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-envelope class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Teléfono --}}
                <div>
                    <flux:input 
                        label="Teléfono"
                        name="phone"
                        :value="old('phone', $user->phone)"
                        required
                        :error="$errors->first('phone')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-phone class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Fecha de nacimiento --}}
                <div>
                    <flux:input 
                        label="Fecha de nacimiento (opcional)"
                        name="birth_date"
                        type="date"
                        :value="old('birth_date', $user->birth_date?->format('Y-m-d'))"
                        :error="$errors->first('birth_date')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-cake class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>
                </div>

                {{-- Rol --}}
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">
                        Rol del usuario
                    </label>
                    <flux:select name="role" required>
                        <option value="MobileUser" @selected(old('role', $user->role) === 'MobileUser')>
                            Usuario Móvil
                        </option>
                        <option value="BusinessAdministrator" @selected(old('role', $user->role) === 'BusinessAdministrator')>
                            Administrador de Negocio
                        </option>
                        <option value="SuperAdministrator" @selected(old('role', $user->role) === 'SuperAdministrator')>
                            Super Administrador
                        </option>
                    </flux:select>
                    @error('role')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Contraseña --}}
                <div class="border-t pt-6">
                    <h3 class="text-lg font-semibold mb-4 flex items-center">
                        <x-heroicon-o-key class="w-6 h-6 mr-2" />
                        Cambiar Contraseña
                    </h3>
                    <p class="text-sm text-gray-600 mb-4">
                        Deje estos campos en blanco si no desea cambiar la contraseña.
                    </p>

                    <div class="space-y-4">
                        <flux:input 
                            label="Nueva contraseña"
                            name="password"
                            type="password"
                            placeholder="Mínimo 8 caracteres"
                            :error="$errors->first('password')"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-lock-closed class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>

                        <flux:input 
                            label="Confirmar nueva contraseña"
                            name="password_confirmation"
                            type="password"
                            placeholder="Repetir contraseña"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-lock-closed class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>
                    </div>
                </div>

                {{-- Estado activo --}}
                <div class="flex items-center pt-4">
                    <flux:checkbox 
                        name="is_active"
                        :checked="old('is_active', $user->is_active)"
                    />
                    <label class="ml-2 text-sm text-gray-700">
                        Usuario activo (puede acceder al sistema)
                    </label>
                </div>

                {{-- Botones --}}
                <div class="flex justify-end space-x-3 pt-6 border-t">
                    <flux:button 
                        variant="ghost" 
                        type="button"
                        href="{{ route('users.index') }}"
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
