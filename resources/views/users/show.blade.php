
<x-app-layout>
    @section('page-title', 'Detalles de Usuario')

    <div class="max-w-4xl mx-auto">
        {{-- Botones de acción --}}
        <div class="flex justify-between items-center mb-6">
            <flux:button 
                variant="ghost" 
                href="{{ route('users.index') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Volver al listado
            </flux:button>

            <div class="flex space-x-3">
                <flux:button 
                    variant="warning"
                    href="{{ route('users.edit', $user) }}"
                >
                    <x-heroicon-o-pencil class="w-5 h-5 mr-2" />
                    Editar
                </flux:button>

                <form action="{{ route('users.destroy', $user) }}" method="POST" onsubmit="return confirm('¿Estás seguro de eliminar este usuario?')">
                    @csrf
                    @method('DELETE')
                    <flux:button 
                        variant="danger"
                        type="submit"
                    >
                        <x-heroicon-o-trash class="w-5 h-5 mr-2" />
                        Eliminar
                    </flux:button>
                </form>
            </div>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            {{-- Header --}}
            <div class="bg-gradient-to-r from-black to-gray-800 px-8 py-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-20 w-20 bg-white rounded-full flex items-center justify-center">
                        <x-heroicon-o-user class="w-12 h-12 text-gray-600" />
                    </div>
                    <div class="ml-6 text-white">
                        <h2 class="text-3xl font-bold">{{ $user->name }}</h2>
                        <p class="text-gray-300 mt-1">{{ $user->getRoleLabel() }}</p>
                    </div>
                </div>
            </div>

            {{-- Contenido --}}
            <div class="p-8">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    {{-- Información de contacto --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-phone class="w-6 h-6 mr-2" />
                            Información de Contacto
                        </h3>

                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Teléfono</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->phone }}</dd>
                            </div>

                            @if($user->email)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Correo electrónico</dt>
                                    <dd class="mt-1 text-sm text-gray-900">{{ $user->email }}</dd>
                                </div>
                            @endif

                            @if($user->birth_date)
                                <div>
                                    <dt class="text-sm font-medium text-gray-500">Fecha de nacimiento</dt>
                                    <dd class="mt-1 text-sm text-gray-900">
                                        {{ $user->birth_date->format('d/m/Y') }}
                                        ({{ $user->birth_date->age }} años)
                                    </dd>
                                </div>
                            @endif
                        </dl>
                    </div>

                    {{-- Información del sistema --}}
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-cog-6-tooth class="w-6 h-6 mr-2" />
                            Información del Sistema
                        </h3>

                        <dl class="space-y-3">
                            <div>
                                <dt class="text-sm font-medium text-gray-500">Rol</dt>
                                <dd class="mt-1">
                                    @if($user->role === 'SuperAdministrator')
                                        <flux:badge variant="danger">Super Administrador</flux:badge>
                                    @elseif($user->role === 'BusinessAdministrator')
                                        <flux:badge variant="warning">Administrador de Negocio</flux:badge>
                                    @else
                                        <flux:badge variant="info">Usuario Móvil</flux:badge>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Estado</dt>
                                <dd class="mt-1">
                                    @if($user->is_active)
                                        <flux:badge variant="success">Activo</flux:badge>
                                    @else
                                        <flux:badge variant="danger">Inactivo</flux:badge>
                                    @endif
                                </dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Fecha de registro</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->created_at->format('d/m/Y H:i') }}</dd>
                            </div>

                            <div>
                                <dt class="text-sm font-medium text-gray-500">Última actualización</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $user->updated_at->format('d/m/Y H:i') }}</dd>
                            </div>
                        </dl>
                    </div>
                </div>

                {{-- Negocio asociado (si es BusinessAdministrator) --}}
                @if($user->role === 'BusinessAdministrator' && $user->business)
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-building-storefront class="w-6 h-6 mr-2" />
                            Negocio Asociado
                        </h3>

                        <div class="bg-gray-50 rounded-lg p-4">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="font-semibold text-gray-900">{{ $user->business->business_name }}</p>
                                    <p class="text-sm text-gray-600">{{ $user->business->legal_name }}</p>
                                </div>
                                <flux:button 
                                    variant="primary"
                                    outline
                                    size="sm"
                                    href="{{ route('businesses.show', $user->business) }}"
                                >
                                    Ver Negocio
                                </flux:button>
                            </div>
                        </div>
                    </div>
                @endif

                {{-- Estadísticas de órdenes (si es MobileUser) --}}
                @if($user->role === 'MobileUser')
                    <div class="mt-8 pt-8 border-t">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                            <x-heroicon-o-shopping-bag class="w-6 h-6 mr-2" />
                            Estadísticas de Órdenes
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <div class="bg-blue-50 rounded-lg p-4">
                                <p class="text-sm text-blue-700">Total de Órdenes</p>
                                <p class="text-3xl font-bold text-blue-900">{{ $user->orders()->count() }}</p>
                            </div>

                            <div class="bg-green-50 rounded-lg p-4">
                                <p class="text-sm text-green-700">Órdenes Completadas</p>
                                <p class="text-3xl font-bold text-green-900">{{ $user->orders()->where('status', 'delivered')->count() }}</p>
                            </div>

                            <div class="bg-yellow-50 rounded-lg p-4">
                                <p class="text-sm text-yellow-700">Calificaciones Dadas</p>
                                <p class="text-3xl font-bold text-yellow-900">{{ $user->ratings()->count() }}</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>