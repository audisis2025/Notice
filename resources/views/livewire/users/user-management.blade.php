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
 * Fecha de mantenimiento       : 13/01/2026
 * Folio de mantenimiento       : 1
 * Tipo de mantenimiento        : Correctivo
 * Descripción del mantenimiento: Integración con layout sidebar
 * Responsable                  : Jesús Núñez
 * Revisor                      : 
 */
--}}

<x-layouts.app.sidebar>
    {{-- Contenido principal --}}
    <div class="p-6">
        {{-- Encabezado y botón crear --}}
        <div class="mb-6 flex justify-between items-center">
            <div>
                <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-100">Gestión de Usuarios</h2>
                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Administra los usuarios del sistema</p>
            </div>
            
            <button 
                wire:click="create"
                class="flex items-center px-4 py-2 bg-black text-white rounded-lg hover:bg-gray-800 transition-colors dark:bg-gray-700 dark:hover:bg-gray-600"
            >
                <x-heroicon-o-plus class="w-5 h-5 mr-2" />
                Crear Usuario
            </button>
        </div>

        {{-- Filtros de búsqueda --}}
        <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-6 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                {{-- Búsqueda --}}
                <div class="md:col-span-2">
                    <div class="relative">
                        <x-heroicon-o-magnifying-glass class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                        <input 
                            type="text" 
                            wire:model.live.debounce.300ms="search"
                            placeholder="Buscar por nombre, email o teléfono..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                        />
                    </div>
                </div>

                {{-- Filtro por rol --}}
                <div>
                    <select 
                        wire:model.live="roleFilter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos los roles</option>
                        <option value="SuperAdministrator">Super Administrador</option>
                        <option value="BusinessAdministrator">Admin. Negocio</option>
                        <option value="MobileUser">Usuario Móvil</option>
                    </select>
                </div>

                {{-- Filtro por estado --}}
                <div>
                    <select 
                        wire:model.live="statusFilter"
                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                    >
                        <option value="">Todos los estados</option>
                        <option value="1">Activos</option>
                        <option value="0">Inactivos</option>
                    </select>
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
    </div>

    {{-- Modal de crear/editar --}}
    @if($showModal)
        <!-- Modal Backdrop -->
        <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <!-- Background overlay -->
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>
                
                <!-- Modal panel -->
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full dark:bg-gray-800">
                    <form wire:submit="save">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4 dark:bg-gray-800">
                            <div class="space-y-4">
                                {{-- Título --}}
                                <h3 class="text-xl font-semibold flex items-center text-gray-900 dark:text-gray-100">
                                    <x-heroicon-o-user-plus class="w-6 h-6 mr-2" />
                                    {{ $editMode ? 'Editar Usuario' : 'Crear Usuario' }}
                                </h3>

                                {{-- Nombre --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Nombre completo *
                                    </label>
                                    <div class="relative">
                                        <x-heroicon-o-user class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input 
                                            type="text" 
                                            wire:model.blur="name"
                                            required
                                            placeholder="Ej: Juan Pérez"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('name') border-red-500 @enderror"
                                        />
                                    </div>
                                    @error('name') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Email --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Email *
                                    </label>
                                    <div class="relative">
                                        <x-heroicon-o-envelope class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input 
                                            type="email" 
                                            wire:model.blur="email"
                                            required
                                            placeholder="correo@ejemplo.com"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('email') border-red-500 @enderror"
                                        />
                                    </div>
                                    @error('email') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Teléfono --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Teléfono (opcional)
                                    </label>
                                    <div class="relative">
                                        <x-heroicon-o-phone class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input 
                                            type="text" 
                                            wire:model.blur="phone"
                                            placeholder="+52 123 456 7890"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('phone') border-red-500 @enderror"
                                        />
                                    </div>
                                    @error('phone') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Fecha de nacimiento --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Fecha de nacimiento (opcional)
                                    </label>
                                    <div class="relative">
                                        <x-heroicon-o-cake class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input 
                                            type="date" 
                                            wire:model="birth_date"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('birth_date') border-red-500 @enderror"
                                        />
                                    </div>
                                    @error('birth_date') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Rol --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Rol *
                                    </label>
                                    <select 
                                        wire:model="role"
                                        required
                                        class="w-full border border-gray-300 rounded-lg px-3 py-2 focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                    >
                                        <option value="SuperAdministrator">Super Administrador</option>
                                        <option value="BusinessAdministrator">Administrador de Negocio</option>
                                        <option value="MobileUser">Usuario Móvil</option>
                                    </select>
                                </div>

                                {{-- Contraseña --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        {{ $editMode ? 'Nueva contraseña (dejar en blanco para mantener)' : 'Contraseña' }}
                                        @if(!$editMode) * @endif
                                    </label>
                                    <div class="relative">
                                        <x-heroicon-o-lock-closed class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input 
                                            type="password" 
                                            wire:model="password"
                                            @if(!$editMode) required @endif
                                            placeholder="Mínimo 8 caracteres"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white @error('password') border-red-500 @enderror"
                                        />
                                    </div>
                                    @error('password') <span class="text-red-500 text-sm">{{ $message }}</span> @enderror
                                </div>

                                {{-- Confirmar contraseña --}}
                                <div>
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                        Confirmar contraseña
                                        @if(!$editMode) * @endif
                                    </label>
                                    <div class="relative">
                                        <x-heroicon-o-lock-closed class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" />
                                        <input 
                                            type="password" 
                                            wire:model="password_confirmation"
                                            @if(!$editMode) required @endif
                                            placeholder="Repetir contraseña"
                                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-black focus:border-transparent dark:bg-gray-700 dark:border-gray-600 dark:text-white"
                                        />
                                    </div>
                                </div>

                                {{-- Usuario activo --}}
                                <div class="flex items-center">
                                    <input 
                                        type="checkbox" 
                                        wire:model="is_active"
                                        id="is_active"
                                        class="h-4 w-4 text-black focus:ring-black border-gray-300 rounded dark:bg-gray-700 dark:border-gray-600"
                                    />
                                    <label for="is_active" class="ml-2 block text-sm text-gray-900 dark:text-gray-300">
                                        Usuario activo
                                    </label>
                                </div>
                            </div>
                        </div>

                        {{-- Botones del modal --}}
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse dark:bg-gray-700">
                            <button 
                                type="submit"
                                wire:loading.attr="disabled"
                                class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-black text-base font-medium text-white hover:bg-gray-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black dark:bg-gray-600 dark:hover:bg-gray-500 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <x-heroicon-o-check class="w-5 h-5 mr-1" />
                                <span wire:loading.remove wire:target="save">
                                    {{ $editMode ? 'Actualizar' : 'Crear' }}
                                </span>
                                <span wire:loading wire:target="save">
                                    Guardando...
                                </span>
                            </button>
                            
                            <button 
                                type="button"
                                wire:click="closeModal"
                                class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-black dark:bg-gray-600 dark:border-gray-500 dark:text-gray-300 dark:hover:bg-gray-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm"
                            >
                                <x-heroicon-o-x-mark class="w-5 h-5 mr-1" />
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
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
</x-layouts.app.sidebar>