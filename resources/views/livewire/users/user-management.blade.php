<div>
    {{-- Encabezado y botón crear --}}
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-800">Gestión de Usuarios</h2>
        
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
    <div class="bg-white rounded-lg shadow p-6 mb-6">
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
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <flux:table>
            <flux:thead>
                <flux:tr>
                    <flux:th>Nombre</flux:th>
                    <flux:th>Email</flux:th>
                    <flux:th>Teléfono</flux:th>
                    <flux:th>Rol</flux:th>
                    <flux:th>Estado</flux:th>
                    <flux:th class="text-center">Acciones</flux:th>
                </flux:tr>
            </flux:thead>
            <flux:tbody>
                @forelse($users as $user)
                    <flux:tr wire:key="user-{{ $user->id }}">
                        <flux:td>
                            <div class="flex items-center">
                                <x-heroicon-o-user class="w-5 h-5 text-gray-400 mr-2" />
                                {{ $user->name }}
                            </div>
                        </flux:td>
                        <flux:td>
                            <div class="flex items-center text-sm text-gray-600">
                                @if($user->email)
                                    <x-heroicon-o-envelope class="w-4 h-4 mr-1" />
                                    {{ $user->email }}
                                @else
                                    <span class="text-gray-400">Sin email</span>
                                @endif
                            </div>
                        </flux:td>
                        <flux:td>
                            <div class="flex items-center text-sm">
                                <x-heroicon-o-phone class="w-4 h-4 text-gray-400 mr-1" />
                                {{ $user->phone }}
                            </div>
                        </flux:td>
                        <flux:td>
                            @if($user->role === 'SuperAdministrator')
                                <flux:badge variant="danger">Super Admin</flux:badge>
                            @elseif($user->role === 'BusinessAdministrator')
                                <flux:badge variant="warning">Admin Negocio</flux:badge>
                            @else
                                <flux:badge variant="info">Usuario Móvil</flux:badge>
                            @endif
                        </flux:td>
                        <flux:td>
                            <button 
                                wire:click="toggleStatus({{ $user->id }})"
                                class="inline-flex items-center"
                            >
                                @if($user->is_active)
                                    <flux:badge variant="success">
                                        <x-heroicon-o-check-circle class="w-4 h-4 mr-1" />
                                        Activo
                                    </flux:badge>
                                @else
                                    <flux:badge variant="danger">
                                        <x-heroicon-o-x-circle class="w-4 h-4 mr-1" />
                                        Inactivo
                                    </flux:badge>
                                @endif
                            </button>
                        </flux:td>
                        <flux:td>
                            <div class="flex justify-center space-x-2">
                                {{-- Botón Ver --}}
                                <flux:button 
                                    variant="primary" 
                                    outline 
                                    size="sm"
                                    href="{{ route('users.show', $user) }}"
                                >
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </flux:button>

                                {{-- Botón Editar --}}
                                <flux:button 
                                    variant="warning" 
                                    outline 
                                    size="sm"
                                    wire:click="edit({{ $user->id }})"
                                >
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </flux:button>

                                {{-- Botón Eliminar --}}
                                <flux:button 
                                    variant="danger" 
                                    outline 
                                    size="sm"
                                    onclick="confirmUserDelete({{ $user->id }})"
                                >
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </flux:button>
                            </div>
                        </flux:td>
                    </flux:tr>
                @empty
                    <flux:tr>
                        <flux:td colspan="6" class="text-center text-gray-500 py-8">
                            <x-heroicon-o-user-group class="w-12 h-12 mx-auto mb-2 text-gray-300" />
                            <p>No se encontraron usuarios</p>
                        </flux:td>
                    </flux:tr>
                @endforelse
            </flux:tbody>
        </flux:table>

        {{-- Paginación --}}
        <div class="px-6 py-4 border-t">
            {{ $users->links() }}
        </div>
    </div>

    {{-- Modal de crear/editar --}}
    @if($showModal)
        <flux:modal wire:model="showModal" variant="flyout">
            <form wire:submit="save">
                <div class="space-y-4">
                    {{-- Título --}}
                    <h3 class="text-xl font-semibold flex items-center">
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
                        label="Email (opcional)"
                        type="email"
                        wire:model.blur="email"
                        placeholder="correo@ejemplo.com"
                        error="{{ $errors->first('email') }}"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-envelope class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Teléfono --}}
                    <flux:input 
                        label="Teléfono"
                        wire:model.blur="phone"
                        required
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
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition',
                    cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition ml-2'
                },
                buttonsStyling: false
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