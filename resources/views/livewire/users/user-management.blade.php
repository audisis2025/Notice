{{--
/**
 * Nombre de la vista           : user-management.blade.php
 * Descripción de la vista      : Componente Livewire para gestión completa de usuarios
 *                                con filtros, búsqueda, paginación y CRUD
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

<div>
    {{-- Encabezado y botón crear --}}
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Gestión de Usuarios</h2>
        
        <flux:button 
            variant="primary" 
            icon="plus"
            wire:click="create"
            class="bg-black hover:bg-[#494949]"
        >
            Crear Usuario
        </flux:button>
    </div>

    {{-- Filtros de búsqueda --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            {{-- Búsqueda --}}
            <div class="md:col-span-2">
                <flux:input 
                    wire:model.live.debounce.300ms="search"
                    placeholder="Buscar por nombre, email o teléfono..."
                    icon="magnifying-glass"
                />
            </div>

            {{-- Filtro por rol --}}
            <div>
                <flux:select wire:model.live="roleFilter">
                    <option value="">Todos los roles</option>
                    <option value="SuperAdministrator">Super Administrador</option>
                    <option value="BusinessAdministrator">Admin. Negocio</option>
                    <option value="MobileUser">Usuario Móvil</option>
                </flux:select>
            </div>

            {{-- Filtro por estado --}}
            <div>
                <flux:select wire:model.live="statusFilter">
                    <option value="">Todos los estados</option>
                    <option value="1">Activos</option>
                    <option value="0">Inactivos</option>
                </flux:select>
            </div>
        </div>
    </div>

    {{-- Tabla de usuarios --}}
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                <thead class="bg-gray-50 dark:bg-gray-700">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Nombre
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Email
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Teléfono
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Rol
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Estado
                        </th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                            Acciones
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                    @forelse($users as $user)
                        <tr wire:key="user-{{ $user->id }}" class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center">
                                    <x-heroicon-o-user class="w-5 h-5 text-gray-400 mr-2" />
                                    <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                        {{ $user->name }}
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-600 dark:text-gray-300">
                                    @if($user->email)
                                        <x-heroicon-o-envelope class="w-4 h-4 mr-1" />
                                        {{ $user->email }}
                                    @else
                                        <span class="text-gray-400">Sin email</span>
                                    @endif
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="flex items-center text-sm text-gray-900 dark:text-gray-100">
                                    <x-heroicon-o-phone class="w-4 h-4 text-gray-400 mr-1" />
                                    {{ $user->phone ?? 'Sin teléfono' }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($user->role === 'SuperAdministrator')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                        Super Admin
                                    </span>
                                @elseif($user->role === 'BusinessAdministrator')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200">
                                        Admin Negocio
                                    </span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200">
                                        Usuario Móvil
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <button 
                                    wire:click="toggleStatus({{ $user->id }})"
                                    class="inline-flex items-center"
                                >
                                    @if($user->is_active)
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200">
                                            <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                            Activo
                                        </span>
                                    @else
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200">
                                            <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                            Inactivo
                                        </span>
                                    @endif
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                <div class="flex justify-center space-x-2">
                                    {{-- Botón Ver --}}
                                    <a 
                                        href="{{ route('users.show', $user) }}"
                                        class="text-blue-600 hover:text-blue-900 dark:text-blue-400 dark:hover:text-blue-600"
                                        title="Ver detalles"
                                    >
                                        <x-heroicon-o-eye class="w-5 h-5" />
                                    </a>

                                    {{-- Botón Editar --}}
                                    <button 
                                        wire:click="edit({{ $user->id }})"
                                        class="text-yellow-600 hover:text-yellow-900 dark:text-yellow-400 dark:hover:text-yellow-600"
                                        title="Editar"
                                    >
                                        <x-heroicon-o-pencil class="w-5 h-5" />
                                    </button>

                                    {{-- Botón Eliminar --}}
                                    <button 
                                        onclick="confirmUserDelete({{ $user->id }})"
                                        class="text-red-600 hover:text-red-900 dark:text-red-400 dark:hover:text-red-600"
                                        title="Eliminar"
                                    >
                                        <x-heroicon-o-trash class="w-5 h-5" />
                                    </button>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-12 text-center">
                                <x-heroicon-o-user-group class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                                <p class="text-gray-500 dark:text-gray-400">No se encontraron usuarios</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Paginación --}}
        @if($users->hasPages())
            <div class="px-6 py-4 border-t border-gray-200 dark:border-gray-700">
                {{ $users->links() }}
            </div>
        @endif
    </div>

    {{-- Modal de crear/editar --}}
    @if($showModal)
        <flux:modal wire:model="showModal" variant="flyout">
            <form wire:submit="save">
                <div class="space-y-4">
                    {{-- Título --}}
                    <h3 class="text-xl font-semibold flex items-center text-gray-900 dark:text-gray-100">
                        <x-heroicon-o-user-plus class="w-6 h-6 mr-2" />
                        {{ $editMode ? 'Editar Usuario' : 'Crear Usuario' }}
                    </h3>

                    {{-- Nombre --}}
                    <flux:input 
                        label="Nombre completo"
                        wire:model.blur="name"
                        required
                        placeholder="Ej: Juan Pérez"
                        error="{{ $errors->first('name') }}"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-user class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Email --}}
                    <flux:input 
                        label="Email"
                        type="email"
                        wire:model.blur="email"
                        required
                        placeholder="correo@ejemplo.com"
                        error="{{ $errors->first('email') }}"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-envelope class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Teléfono --}}
                    <flux:input 
                        label="Teléfono (opcional)"
                        wire:model.blur="phone"
                        placeholder="+52 123 456 7890"
                        error="{{ $errors->first('phone') }}"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-phone class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Fecha de nacimiento --}}
                    <flux:input 
                        label="Fecha de nacimiento (opcional)"
                        type="date"
                        wire:model="birth_date"
                        error="{{ $errors->first('birth_date') }}"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-cake class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Rol --}}
                    <flux:select 
                        label="Rol"
                        wire:model="role"
                        required
                    >
                        <option value="SuperAdministrator">Super Administrador</option>
                        <option value="BusinessAdministrator">Administrador de Negocio</option>
                        <option value="MobileUser">Usuario Móvil</option>
                    </flux:select>

                    {{-- Contraseña --}}
                    <flux:input 
                        label="{{ $editMode ? 'Nueva contraseña (dejar en blanco para mantener)' : 'Contraseña' }}"
                        type="password"
                        wire:model="password"
                        :required="!$editMode"
                        placeholder="Mínimo 8 caracteres"
                        error="{{ $errors->first('password') }}"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Confirmar contraseña --}}
                    <flux:input 
                        label="Confirmar contraseña"
                        type="password"
                        wire:model="password_confirmation"
                        :required="!$editMode"
                        placeholder="Repetir contraseña"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Usuario activo --}}
                    <flux:checkbox 
                        label="Usuario activo"
                        wire:model="is_active"
                    />

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-2 pt-4">
                        <flux:button 
                            variant="ghost"
                            type="button"
                            wire:click="closeModal"
                        >
                            <x-heroicon-o-x-mark class="w-5 h-5 mr-1" />
                            Cancelar
                        </flux:button>

                        <flux:button 
                            variant="primary"
                            type="submit"
                            class="bg-black hover:bg-[#494949]"
                            wire:loading.attr="disabled"
                        >
                            <x-heroicon-o-check class="w-5 h-5 mr-1" />
                            <span wire:loading.remove wire:target="save">
                                {{ $editMode ? 'Actualizar' : 'Crear' }}
                            </span>
                            <span wire:loading wire:target="save">
                                Guardando...
                            </span>
                        </flux:button>
                    </div>
                </div>
            </form>
        </flux:modal>
    @endif

    {{-- Scripts de SweetAlert2 --}}
    @script
    <script>
        // Confirmar eliminación
        function confirmUserDelete(userId) {
            Swal.fire({
                title: '¿Eliminar usuario?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#dc2626',
                cancelButtonColor: '#6b7280',
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('user-delete-confirmed', { userId: userId });
                }
            });
        }

        // Listener para confirmación de eliminación
        $wire.on('confirm-delete', (event) => {
            confirmUserDelete(event.userId);
        });
    </script>
    @endscript
</div>