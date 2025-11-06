@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="mb-6">
        <h2 class="text-2xl font-bold text-gray-900">Dashboard</h2>
    </div>

    <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Total Órdenes</dt>
                <dd class="mt-1 text-3xl font-semibold text-gray-900">{{ $stats['total_orders'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Pendientes</dt>
                <dd class="mt-1 text-3xl font-semibold text-yellow-600">{{ $stats['pending_orders'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Listas</dt>
                <dd class="mt-1 text-3xl font-semibold text-blue-600">{{ $stats['ready_orders'] }}</dd>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <dt class="text-sm font-medium text-gray-500 truncate">Entregadas Hoy</dt>
                <dd class="mt-1 text-3xl font-semibold text-green-600">{{ $stats['delivered_today'] }}</dd>
            </div>
        </div>
    </div>

    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-semibold mb-4">Órdenes Recientes</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Código</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cliente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Monto</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Fecha</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach ($recent_orders as $order)
                        <tr>
                            <td class="px-6 py-4 text-sm font-medium text-gray-900">
                                <a href="{{ route('business.orders.show', $order) }}"
                                    class="text-blue-600 hover:text-blue-900">
                                    {{ $order->order_code }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">{{ $order->client_name ?: 'Sin nombre' }}</td>
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
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
