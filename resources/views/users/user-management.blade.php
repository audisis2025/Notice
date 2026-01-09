<div>
    {{-- Encabezado y botón crear --}}
    <div class="mb-6 flex justify-between items-center">
        <div class="flex items-center">
            <x-heroicon-o-user-group class="w-8 h-8 text-gray-700 mr-3" />
            <h2 class="text-3xl font-bold text-gray-900">Gestión de Usuarios</h2>
        </div>
        
        <flux:button 
            variant="primary" 
            wire:click="create"
            class="bg-black hover:bg-[#494949]"
        >
            <x-heroicon-o-plus class="w-5 h-5 mr-2" />
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
                >
                    <x-slot:iconTrailing>
                        <x-heroicon-o-magnifying-glass class="w-5 h-5" />
                    </x-slot:iconTrailing>
                </flux:input>
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
                    <flux:th>Usuario</flux:th>
                    <flux:th>Contacto</flux:th>
                    <flux:th>Rol</flux:th>
                    <flux:th>Estado</flux:th>
                    <flux:th>Fecha Registro</flux:th>
                    <flux:th class="text-center">Acciones</flux:th>
                </flux:tr>
            </flux:thead>
            <flux:tbody>
                @forelse($users as $user)
                    <flux:tr wire:key="user-{{ $user->id }}">
                        <flux:td>
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                        <x-heroicon-o-user class="w-6 h-6 text-gray-500" />
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <div class="font-medium text-gray-900">{{ $user->name }}</div>
                                    @if($user->birth_date)
                                        <div class="text-sm text-gray-500 flex items-center">
                                            <x-heroicon-o-cake class="w-4 h-4 mr-1" />
                                            {{ $user->birth_date->format('d/m/Y') }}
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </flux:td>
                        <flux:td>
                            <div class="space-y-1">
                                @if($user->email)
                                    <div class="flex items-center text-sm text-gray-600">
                                        <x-heroicon-o-envelope class="w-4 h-4 mr-1" />
                                        {{ $user->email }}
                                    </div>
                                @endif
                                <div class="flex items-center text-sm text-gray-600">
                                    <x-heroicon-o-phone class="w-4 h-4 mr-1" />
                                    {{ $user->phone }}
                                </div>
                            </div>
                        </flux:td>
                        <flux:td>
                            @if($user->role === 'SuperAdministrator')
                                <flux:badge variant="danger">
                                    <x-heroicon-o-shield-check class="w-4 h-4 mr-1" />
                                    Super Admin
                                </flux:badge>
                            @elseif($user->role === 'BusinessAdministrator')
                                <flux:badge variant="warning">
                                    <x-heroicon-o-building-office class="w-4 h-4 mr-1" />
                                    Admin Negocio
                                </flux:badge>
                            @else
                                <flux:badge variant="info">
                                    <x-heroicon-o-device-phone-mobile class="w-4 h-4 mr-1" />
                                    Usuario Móvil
                                </flux:badge>
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
                            <div class="text-sm text-gray-600">
                                {{ $user->created_at->format('d/m/Y') }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ $user->created_at->diffForHumans() }}
                            </div>
                        </flux:td>
                        <flux:td>
                            <div class="flex justify-center space-x-2">
                                {{-- Botón Ver --}}
                                <flux:button 
                                    variant="primary" 
                                    outline 
                                    size="sm"
                                    title="Ver detalles"
                                >
                                    <x-heroicon-o-eye class="w-4 h-4" />
                                </flux:button>

                                {{-- Botón Editar --}}
                                <flux:button 
                                    variant="warning" 
                                    outline 
                                    size="sm"
                                    wire:click="edit({{ $user->id }})"
                                    title="Editar usuario"
                                >
                                    <x-heroicon-o-pencil class="w-4 h-4" />
                                </flux:button>

                                {{-- Botón Eliminar --}}
                                <flux:button 
                                    variant="danger" 
                                    outline 
                                    size="sm"
                                    onclick="confirmUserDelete({{ $user->id }})"
                                    title="Eliminar usuario"
                                >
                                    <x-heroicon-o-trash class="w-4 h-4" />
                                </flux:button>
                            </div>
                        </flux:td>
                    </flux:tr>
                @empty
                    <flux:tr>
                        <flux:td colspan="6">
                            <x-empty-state
                                icon="user-group"
                                title="No se encontraron usuarios"
                                description="No hay usuarios que coincidan con los filtros seleccionados"
                            />
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
                        :error="$errors->first('name')"
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
                        :error="$errors->first('email')"
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
                        :error="$errors->first('phone')"
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
                        :error="$errors->first('birth_date')"
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
                        :error="$errors->first('password')"
                    >
                        <x-slot:iconTrailing>
                            <x-heroicon-o-lock-closed class="w-5 h-5" />
                        </x-slot:iconTrailing>
                    </flux:input>

                    {{-- Confirmar contraseña --}}
                    @if(!$editMode || $password)
                        <flux:input 
                            label="Confirmar contraseña"
                            type="password"
                            wire:model="password_confirmation"
                            :required="!$editMode || !empty($password)"
                            placeholder="Repetir contraseña"
                        >
                            <x-slot:iconTrailing>
                                <x-heroicon-o-lock-closed class="w-5 h-5" />
                            </x-slot:iconTrailing>
                        </flux:input>
                    @endif

                    {{-- Usuario activo --}}
                    <div class="flex items-center p-4 bg-gray-50 rounded-lg">
                        <flux:checkbox 
                            label="Usuario activo"
                            wire:model="is_active"
                        />
                    </div>

                    {{-- Botones --}}
                    <div class="flex justify-end space-x-2 pt-4 border-t">
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
                                <x-loading-spinner size="sm" />
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
        function confirmUserDelete(userId) {
            Swal.fire({
                title: '¿Eliminar usuario?',
                text: "Esta acción no se puede revertir",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar',
                customClass: {
                    confirmButton: 'px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition font-medium',
                    cancelButton: 'px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-medium ml-2'
                },
                buttonsStyling: false
            }).then((result) => {
                if (result.isConfirmed) {
                    $wire.dispatch('user-delete-confirmed', { userId: userId });
                }
            });
        }

        $wire.on('confirm-delete', (event) => {
            confirmUserDelete(event.userId);
        });
    </script>
    @endscript
</div>