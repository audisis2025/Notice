@extends('layouts.app')

@section('title', 'Gestión de Negocios')

@section('content')
<div class="mb-6 flex justify-between items-center">
    <h2 class="text-2xl font-bold text-gray-900">Negocios</h2>
    <a href="{{ route('superadmin.businesses.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
        Crear Negocio
    </a>
</div>

<!-- Filtros -->
<div class="bg-white p-4 rounded-lg shadow mb-4">
    <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <input type="text" name="search" placeholder="Buscar..." value="{{ request('search') }}" 
               class="border rounded-md px-3 py-2">
        
        <select name="status" class="border rounded-md px-3 py-2">
            <option value="">Todos los estados</option>
            <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Activos</option>
            <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactivos</option>
        </select>
        
        <select name="package" class="border rounded-md px-3 py-2">
            <option value="">Todos los paquetes</option>
            @foreach($packages as $package)
                <option value="{{ $package->id }}" {{ request('package') == $package->id ? 'selected' : '' }}>
                    {{ $package->name }}
                </option>
            @endforeach
        </select>
        
        <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900">
            Filtrar
        </button>
    </form>
</div>

<!-- Tabla -->
<div class="bg-white shadow-md rounded-lg overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Negocio</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">RFC</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contacto</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Paquete</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($businesses as $business)
                <tr>
                    <td class="px-6 py-4">
                        <div class="text-sm font-medium text-gray-900">{{ $business->name }}</div>
                        <div class="text-sm text-gray-500">{{ $business->email }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">{{ $business->rfc }}</td>
                    <td class="px-6 py-4">
                        <div class="text-sm text-gray-900">{{ $business->contact_person }}</div>
                        <div class="text-sm text-gray-500">{{ $business->phone }}</div>
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900">
                        {{ $business->package?->name ?? 'Sin paquete' }}
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $business->is_active ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                            {{ $business->is_active ? 'Activo' : 'Inactivo' }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-sm font-medium space-x-2">
                        <a href="{{ route('superadmin.businesses.show', $business) }}" class="text-blue-600 hover:text-blue-900">Ver</a>
                        <a href="{{ route('superadmin.businesses.edit', $business) }}" class="text-indigo-600 hover:text-indigo-900">Editar</a>
                        <form method="POST" action="{{ route('superadmin.businesses.toggle-status', $business) }}" class="inline">
                            @csrf
                            <button type="submit" class="text-yellow-600 hover:text-yellow-900">
                                {{ $business->is_active ? 'Suspender' : 'Activar' }}
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay negocios registrados</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>

<div class="mt-4">
    {{ $businesses->links() }}
</div>
@endsection