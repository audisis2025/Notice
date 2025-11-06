@extends('layouts.app')

@section('title', 'Órdenes')

@section('content')
    <div class="mb-6 flex justify-between items-center">
        <h2 class="text-2xl font-bold text-gray-900">Órdenes</h2>
        <a href="{{ route('business.orders.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
            Nueva Orden
        </a>
    </div>

    <!-- Filtros -->
    <div class="bg-white p-4 rounded-lg shadow mb-4">
        <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-4">
            <input type="text" name="search" placeholder="Buscar por código o cliente..." value="{{ request('search') }}"
                class="border rounded-md px-3 py-2">

            <select name="status" class="border rounded-md px-3 py-2">
                <option value="">Todos los estados</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pendiente</option>
                <option value="ready" {{ request('status') === 'ready' ? 'selected' : '' }}>Lista</option>
                <option value="delivered" {{ request('status') === 'delivered' ? 'selected' : '' }}>Entregada</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelada</option>
            </select>

            <input type="date" name="date_from" value="{{ request('date_from') }}" placeholder="Desde"
                class="border rounded-md px-3 py-2">

            <input type="date" name="date_to" value="{{ request('date_to') }}" placeholder="Hasta"
                class="border rounded-md px-3 py-2">

            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-md hover:bg-gray-900">
                Filtrar
            </button>
        </form>
    </div>

    <!-- Tabla de Órdenes -->
    <div class="bg-white shadow-md rounded-lg overflow-hidden">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Acciones</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                @forelse($orders as $order)
                    <tr>
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">{{ $order->order_code }}</td>
                        <td class="px-6 py-4">
                            <div class="text-sm text-gray-900">{{ $order->client_name ?: 'Sin nombre' }}</div>
                            <div class="text-sm text-gray-500">{{ $order->client_phone }}</div>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-900">${{ number_format($order->total_amount, 2) }}</td>
                        <td class="px-6 py-4">
                            <span
                                class="px-2 py-1 text-xs font-semibold rounded-full
                            {{ $order->status === 'pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                            {{ $order->status === 'ready' ? 'bg-blue-100 text-blue-800' : '' }}
                            {{ $order->status === 'delivered' ? 'bg-green-100 text-green-800' : '' }}
                            {{ $order->status === 'cancelled' ? 'bg-red-100 text-red-800' : '' }}">
                                {{ ucfirst($order->status) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500">{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td class="px-6 py-4 text-sm font-medium space-x-2">
                            <a href="{{ route('business.orders.show', $order) }}"
                                class="text-blue-600 hover:text-blue-900">Ver</a>
                            @if ($order->status !== 'delivered' && $order->status !== 'cancelled')
                                <a href="{{ route('business.orders.edit', $order) }}"
                                    class="text-indigo-600 hover:text-indigo-900">Editar</a>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay órdenes</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="mt-4">
        {{ $orders->links() }}
    </div>
@endsection
