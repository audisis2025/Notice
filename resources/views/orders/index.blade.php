{{--
/**
 * Nombre de la vista           : index.blade.php
 * Descripción de la vista      : Vista principal de gestión de órdenes
 *                                CON columna "Escaneado desde" en lugar de Cliente
 * Versión                      : 5.1
 * Fecha de mantenimiento       : 15/01/2026
 * Descripción del mantenimiento: Mostrar info del dispositivo en lugar de cliente
 */
--}}

<x-layouts.app.sidebar>
    <x-flash-messages />
    <div class="p-6">
        {{-- Encabezado --}}
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-8 h-8 text-gray-700 dark:text-gray-300 mr-3">
                    <path stroke-linecap="round" stroke-linejoin="round"
                        d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                </svg>
                <div>
                    <h2 class="text-3xl font-bold text-gray-900 dark:text-white">Gestión de Órdenes</h2>
                    <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">Administra las órdenes de tu negocio</p>
                </div>
            </div>

            <a href="{{ route('orders.create') }}"
                class="flex items-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                    stroke="currentColor" class="w-5 h-5 mr-2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                Nueva Orden
            </a>
        </div>

        {{-- Estadísticas rápidas --}}
        <div class="grid grid-cols-1 md:grid-cols-5 gap-4 mb-6">
            <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 p-4">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-gray-500 dark:text-gray-400 uppercase">Todas</p>
                        <p class="text-2xl font-bold text-gray-900 dark:text-white">{{ $totalOrders }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-yellow-50 dark:bg-yellow-900/20 rounded-lg shadow border border-yellow-200 dark:border-yellow-800 p-4 cursor-pointer hover:bg-yellow-100 dark:hover:bg-yellow-900/30 transition"
                onclick="window.location.href='{{ route('orders.index', ['status' => 'pending']) }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-yellow-700 dark:text-yellow-300 uppercase">Pendientes</p>
                        <p class="text-2xl font-bold text-yellow-900 dark:text-yellow-100">{{ $pendingCount }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-yellow-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M12 6v6h4.5m4.5 0a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-blue-50 dark:bg-blue-900/20 rounded-lg shadow border border-blue-200 dark:border-blue-800 p-4 cursor-pointer hover:bg-blue-100 dark:hover:bg-blue-900/30 transition"
                onclick="window.location.href='{{ route('orders.index', ['status' => 'paid']) }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-blue-700 dark:text-blue-300 uppercase">Pagadas</p>
                        <p class="text-2xl font-bold text-blue-900 dark:text-blue-100">{{ $paidCount }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-blue-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z" />
                    </svg>
                </div>
            </div>

            <div class="bg-orange-50 dark:bg-orange-900/20 rounded-lg shadow border border-orange-200 dark:border-orange-800 p-4 cursor-pointer hover:bg-orange-100 dark:hover:bg-orange-900/30 transition"
                onclick="window.location.href='{{ route('orders.index', ['status' => 'ready']) }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-orange-700 dark:text-orange-300 uppercase">Listas</p>
                        <p class="text-2xl font-bold text-orange-900 dark:text-orange-100">{{ $readyCount }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-orange-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
            </div>

            <div class="bg-green-50 dark:bg-green-900/20 rounded-lg shadow border border-green-200 dark:border-green-800 p-4 cursor-pointer hover:bg-green-100 dark:hover:bg-green-900/30 transition"
                onclick="window.location.href='{{ route('orders.index', ['status' => 'delivered']) }}'">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-xs text-green-700 dark:text-green-300 uppercase">Entregadas</p>
                        <p class="text-2xl font-bold text-green-900 dark:text-green-100">{{ $deliveredCount }}</p>
                    </div>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-8 h-8 text-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285z" />
                    </svg>
                </div>
            </div>
        </div>

        {{-- Filtros --}}
        <div class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 p-6 mb-6">
            <form action="{{ route('orders.index') }}" method="GET" class="flex flex-col md:flex-row gap-4">
                <div class="flex-1">
                    <label class="text-gray-600 dark:text-gray-400 text-xs font-medium mb-1 block">
                        Búsqueda general
                    </label>
                    <div class="relative">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                            stroke="currentColor"
                            class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                        </svg>
                        <input type="text" name="search" placeholder="Buscar por número de orden..."
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent"
                            value="{{ request('search') }}">
                    </div>
                </div>

                <div class="w-full md:w-48">
                    <label class="text-gray-600 dark:text-gray-400 text-xs font-medium mb-1 block">
                        Estado
                    </label>
                    <select name="status"
                        class="w-full px-4 py-2 border border-gray-300 dark:border-zinc-600 bg-white dark:bg-zinc-800 text-gray-900 dark:text-white rounded-lg focus:outline-none focus:ring-2 focus:ring-black dark:focus:ring-white focus:border-transparent">
                        <option value="">Todos los estados</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>Pendiente
                        </option>
                        <option value="paid" {{ request('status') == 'paid' ? 'selected' : '' }}>Pagada</option>
                        <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Lista</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>Entregada
                        </option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>Cancelada
                        </option>
                    </select>
                </div>

                <div class="flex items-end gap-2">
                    <button type="submit"
                        class="px-6 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors h-[42px]">
                        <div class="flex items-center space-x-2">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M21 21l-5.197-5.197m0 0A7.5 7.5 0 105.196 5.196a7.5 7.5 0 0010.607 10.607z" />
                            </svg>
                            <span>Buscar</span>
                        </div>
                    </button>

                    @if (request('search') || request('status'))
                        <a href="{{ route('orders.index') }}"
                            class="px-6 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg transition-colors h-[42px] flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            Limpiar
                        </a>
                    @endif
                </div>
            </form>
        </div>

        {{-- Tabla de órdenes --}}
        <div
            class="bg-white dark:bg-zinc-800 rounded-lg shadow border border-gray-200 dark:border-zinc-700 overflow-hidden">
            @if ($orders->count() > 0)
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-zinc-700">
                        <thead class="bg-gray-50 dark:bg-zinc-900">
                            <tr>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Número
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Escaneado desde
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Estado
                                </th>
                                <th
                                    class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Fecha
                                </th>
                                <th
                                    class="px-6 py-3 text-center text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">
                                    Acciones
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-zinc-800 divide-y divide-gray-200 dark:divide-zinc-700">
                            @foreach ($orders as $order)
                                <tr class="hover:bg-gray-50 dark:hover:bg-zinc-700 transition">
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="font-mono text-sm font-semibold text-gray-900 dark:text-white">
                                            {{ $order->order_number }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if ($order->associated_at)
                                            @php
                                                // Extraer info del dispositivo de la descripción
                                                preg_match(
                                                    '/\[Escaneado desde: (.+?) - IP: (.+?)\]/',
                                                    $order->description,
                                                    $matches,
                                                );
                                                $device = $matches[1] ?? 'Desconocido';
                                                $ip = $matches[2] ?? '';
                                            @endphp
                                            <div class="flex items-start">
                                                <div class="flex-shrink-0 mr-3">
                                                    @if (str_contains($device, 'iOS'))
                                                        <svg class="w-5 h-5 text-gray-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M17.05 20.28c-.98.95-2.05.8-3.08.35-1.09-.46-2.09-.48-3.24 0-1.44.62-2.2.44-3.06-.35C2.79 15.25 3.51 7.59 9.05 7.31c1.35.07 2.29.74 3.08.8 1.18-.24 2.31-.93 3.57-.84 1.51.12 2.65.72 3.4 1.8-3.12 1.87-2.38 5.98.48 7.13-.57 1.5-1.31 2.99-2.54 4.09l.01-.01zM12.03 7.25c-.15-2.23 1.66-4.07 3.74-4.25.29 2.58-2.34 4.5-3.74 4.25z" />
                                                        </svg>
                                                    @elseif(str_contains($device, 'Android'))
                                                        <svg class="w-5 h-5 text-green-500" fill="currentColor"
                                                            viewBox="0 0 24 24">
                                                            <path
                                                                d="M17.6 9.48l1.84-3.18c.16-.31.04-.69-.26-.85-.29-.15-.65-.06-.83.22l-1.88 3.24a11.46 11.46 0 0 0-8.94 0L5.65 5.67c-.19-.28-.54-.37-.83-.22-.3.16-.42.54-.26.85l1.84 3.18C2.92 12.3 1.11 16.91 1.11 22h21.78c0-5.09-1.81-9.7-5.29-12.52zM7.4 19.93c-.68 0-1.23-.55-1.23-1.23s.55-1.23 1.23-1.23 1.23.55 1.23 1.23-.55 1.23-1.23 1.23zm9.2 0c-.68 0-1.23-.55-1.23-1.23s.55-1.23 1.23-1.23 1.23.55 1.23 1.23-.55 1.23-1.23 1.23z" />
                                                        </svg>
                                                    @else
                                                        <svg class="w-5 h-5 text-gray-500" fill="none"
                                                            viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                                stroke-width="2"
                                                                d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                                        </svg>
                                                    @endif
                                                </div>
                                                <div class="min-w-0 flex-1">
                                                    <p
                                                        class="text-sm font-medium text-gray-900 dark:text-white truncate">
                                                        {{ $device }}
                                                    </p>
                                                    @if ($ip)
                                                        <p
                                                            class="text-xs text-gray-500 dark:text-gray-400 font-mono truncate">
                                                            {{ $ip }}
                                                        </p>
                                                    @endif
                                                    <p class="text-xs text-gray-400 dark:text-gray-500">
                                                        {{ $order->associated_at->format('d/m/Y H:i') }}
                                                    </p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center text-gray-400">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="w-5 h-5 mr-2">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                </svg>
                                                <span class="text-sm">Sin escanear</span>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @php
                                            $statusColors = [
                                                'pending' =>
                                                    'bg-yellow-100 text-yellow-800 dark:bg-yellow-900/20 dark:text-yellow-300',
                                                'paid' =>
                                                    'bg-blue-100 text-blue-800 dark:bg-blue-900/20 dark:text-blue-300',
                                                'ready' =>
                                                    'bg-orange-100 text-orange-800 dark:bg-orange-900/20 dark:text-orange-300',
                                                'delivered' =>
                                                    'bg-green-100 text-green-800 dark:bg-green-900/20 dark:text-green-300',
                                                'cancelled' =>
                                                    'bg-red-100 text-red-800 dark:bg-red-900/20 dark:text-red-300',
                                            ];
                                            $statusText = [
                                                'pending' => 'Pendiente',
                                                'paid' => 'Pagada',
                                                'ready' => 'Lista',
                                                'delivered' => 'Entregada',
                                                'cancelled' => 'Cancelada',
                                            ];
                                        @endphp
                                        <span
                                            class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] }}">
                                            {{ $statusText[$order->status] }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="text-sm text-gray-600 dark:text-gray-300">
                                            {{ $order->created_at->format('d/m/Y') }}
                                        </div>
                                        <div class="text-xs text-gray-400">
                                            {{ $order->created_at->format('H:i') }}
                                        </div>
                                    </td>

                                    {{-- COLUMNA DE ACCIONES (sin cambios) --}}
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <div class="flex justify-center space-x-1">
                                            {{-- Dropdown para cambiar estado --}}
                                            <div class="relative inline-block text-left" x-data="{ open: false }">
                                                <button @click="open = !open" @click.away="open = false"
                                                    type="button"
                                                    class="inline-flex items-center px-3 py-1.5 border border-gray-300 dark:border-zinc-600 text-gray-700 dark:text-gray-300 rounded-lg hover:bg-gray-50 dark:hover:bg-zinc-700 transition-colors"
                                                    title="Cambiar estado">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4 mr-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0l3.181 3.183a8.25 8.25 0 0013.803-3.7M4.031 9.865a8.25 8.25 0 0113.803-3.7l3.181 3.182m0-4.991v4.99" />
                                                    </svg>
                                                    <span class="text-xs">Estado</span>
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-3 h-3 ml-1">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                                    </svg>
                                                </button>

                                                {{-- Dropdown desplegado --}}
                                                <div x-show="open"
                                                    x-transition:enter="transition ease-out duration-100"
                                                    x-transition:enter-start="transform opacity-0 scale-95"
                                                    x-transition:enter-end="transform opacity-100 scale-100"
                                                    x-transition:leave="transition ease-in duration-75"
                                                    x-transition:leave-start="transform opacity-100 scale-100"
                                                    x-transition:leave-end="transform opacity-0 scale-95"
                                                    class="absolute left-1/2 transform -translate-x-1/2 z-10 mt-2 w-56 origin-top rounded-md bg-white dark:bg-zinc-800 shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                                    style="display: none;">
                                                    <div class="py-1 flex flex-col">
                                                        {{-- Pendiente --}}
                                                        <form action="{{ route('orders.change-status', $order) }}"
                                                            method="POST" class="block w-full">
                                                            @csrf
                                                            <input type="hidden" name="status" value="pending">
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-yellow-50 dark:hover:bg-yellow-900/20 flex items-center"
                                                                onclick="return confirm('¿Cambiar a Pendiente?')">
                                                                <span
                                                                    class="w-2 h-2 rounded-full bg-yellow-500 mr-2 flex-shrink-0"></span>
                                                                <span>Pendiente</span>
                                                            </button>
                                                        </form>

                                                        {{-- Pagada --}}
                                                        <form action="{{ route('orders.change-status', $order) }}"
                                                            method="POST" class="block w-full">
                                                            @csrf
                                                            <input type="hidden" name="status" value="paid">
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-blue-50 dark:hover:bg-blue-900/20 flex items-center justify-between"
                                                                onclick="return confirm('¿Cambiar a Pagada?')">
                                                                <div class="flex items-center">
                                                                    <span
                                                                        class="w-2 h-2 rounded-full bg-blue-500 mr-2 flex-shrink-0"></span>
                                                                    <span>Pagada</span>
                                                                </div>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                    class="w-4 h-4 text-blue-500 flex-shrink-0">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                                </svg>
                                                            </button>
                                                        </form>

                                                        {{-- Lista --}}
                                                        <form action="{{ route('orders.change-status', $order) }}"
                                                            method="POST" class="block w-full">
                                                            @csrf
                                                            <input type="hidden" name="status" value="ready">
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-orange-50 dark:hover:bg-orange-900/20 flex items-center justify-between"
                                                                onclick="return confirm('¿Cambiar a Lista?')">
                                                                <div class="flex items-center">
                                                                    <span
                                                                        class="w-2 h-2 rounded-full bg-orange-500 mr-2 flex-shrink-0"></span>
                                                                    <span>Lista</span>
                                                                </div>
                                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                                    viewBox="0 0 24 24" stroke-width="1.5"
                                                                    stroke="currentColor"
                                                                    class="w-4 h-4 text-orange-500 flex-shrink-0">
                                                                    <path stroke-linecap="round"
                                                                        stroke-linejoin="round"
                                                                        d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                                </svg>
                                                            </button>
                                                        </form>

                                                        {{-- Entregada --}}
                                                        <form action="{{ route('orders.change-status', $order) }}"
                                                            method="POST" class="block w-full">
                                                            @csrf
                                                            <input type="hidden" name="status" value="delivered">
                                                            <button type="submit"
                                                                class="w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-green-50 dark:hover:bg-green-900/20 flex items-center"
                                                                onclick="return confirm('¿Cambiar a Entregada?')">
                                                                <span
                                                                    class="w-2 h-2 rounded-full bg-green-500 mr-2 flex-shrink-0"></span>
                                                                <span>Entregada</span>
                                                            </button>
                                                        </form>

                                                        <div
                                                            class="border-t border-gray-200 dark:border-zinc-700 my-1">
                                                        </div>

                                                        {{-- Cancelada --}}
                                                        <button type="button"
                                                            onclick="confirmCancel('{{ $order->id }}')"
                                                            class="w-full text-left px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 flex items-center">
                                                            <span
                                                                class="w-2 h-2 rounded-full bg-red-500 mr-2 flex-shrink-0"></span>
                                                            <span>Cancelar orden</span>
                                                        </button>
                                                    </div>
                                                </div>
                                            </div>

                                            {{-- Ver QR de asociación --}}
                                            @if ($order->status === 'paid' && $order->qr_code && !$order->user_id)
                                                <button type="button"
                                                    onclick="showQRModal('{{ $order->id }}', 'association', '{{ $order->order_number }}', '{{ Storage::url($order->qr_code) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 border border-cyan-600 text-cyan-600 rounded-lg hover:bg-cyan-50 dark:hover:bg-cyan-900/20 transition-colors"
                                                    title="Ver QR de asociación">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                    </svg>
                                                </button>
                                            @endif

                                            {{-- Ver QR de entrega --}}
                                            @if ($order->status === 'ready' && $order->qr_delivery_code)
                                                <button type="button"
                                                    onclick="showQRModal('{{ $order->id }}', 'delivery', '{{ $order->order_number }}', '{{ Storage::url($order->qr_delivery_code) }}')"
                                                    class="inline-flex items-center px-3 py-1.5 border border-green-600 text-green-600 rounded-lg hover:bg-green-50 dark:hover:bg-green-900/20 transition-colors"
                                                    title="Ver QR de entrega">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="w-4 h-4">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Paginación --}}
                @if ($orders->hasPages())
                    <div class="px-6 py-4 border-t border-gray-200 dark:border-zinc-700 bg-gray-50 dark:bg-zinc-900">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="px-6 py-12 text-center">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="w-12 h-12 mx-auto mb-2 text-gray-300">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M15.75 10.5V6a3.75 3.75 0 10-7.5 0v4.5m11.356-1.993l1.263 12c.07.665-.45 1.243-1.119 1.243H4.25a1.125 1.125 0 01-1.12-1.243l1.264-12A1.125 1.125 0 015.513 7.5h12.974c.576 0 1.059.435 1.119 1.007zM8.625 10.5a.375.375 0 11-.75 0 .375.375 0 01.75 0zm7.5 0a.375.375 0 11-.75 0 .375.375 0 01.75 0z" />
                    </svg>
                    <p class="text-gray-500 dark:text-gray-400">
                        @if (request('search') || request('status'))
                            No se encontraron órdenes
                        @else
                            No hay órdenes registradas
                        @endif
                    </p>
                    <a href="{{ route('orders.create') }}"
                        class="mt-4 inline-flex items-center px-4 py-2 bg-black dark:bg-white text-white dark:text-black rounded-lg hover:bg-gray-800 dark:hover:bg-gray-100 transition-colors">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        Crear Primera Orden
                    </a>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal de QR (sin cambios) --}}
    <div id="qrModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title"
        role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"
                onclick="closeQRModal()"></div>
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div
                class="inline-block align-bottom bg-white dark:bg-zinc-800 rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white dark:bg-zinc-800 p-6 text-center">
                    <div
                        class="mb-4 inline-flex items-center justify-center w-16 h-16 bg-gray-100 dark:bg-zinc-700 rounded-full">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                            stroke-width="1.5" stroke="currentColor"
                            class="w-10 h-10 text-gray-600 dark:text-gray-300">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 013.75 9.375v-4.5zM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 01-1.125-1.125v-4.5zM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0113.5 9.375v-4.5z" />
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M6.75 6.75h.75v.75h-.75v-.75zM6.75 16.5h.75v.75h-.75v-.75zM16.5 6.75h.75v.75h-.75v-.75zM13.5 13.5h.75v.75h-.75v-.75zM13.5 19.5h.75v.75h-.75v-.75zM19.5 13.5h.75v.75h-.75v-.75zM19.5 19.5h.75v.75h-.75v-.75zM16.5 16.5h.75v.75h-.75v-.75z" />
                        </svg>
                    </div>

                    <h3 id="qrModalTitle" class="text-xl font-semibold mb-2 text-gray-900 dark:text-white">
                        Código QR
                    </h3>

                    <p id="qrModalSubtitle" class="text-gray-600 dark:text-gray-400 mb-6">
                        Orden: <span id="qrOrderNumber" class="font-mono font-bold text-black dark:text-white"></span>
                    </p>

                    <div class="bg-white p-6 rounded-lg border-2 border-gray-200 dark:border-zinc-600 inline-block">
                        <img id="qrImage" src="" alt="Código QR" class="w-64 h-64 mx-auto">
                    </div>

                    <div id="qrDescription" class="mt-6 p-4 bg-blue-50 dark:bg-blue-900/20 rounded-lg">
                        <p class="text-sm text-blue-800 dark:text-blue-300 flex items-center justify-center">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor" class="w-5 h-5 mr-2">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="M11.25 11.25l.041-.02a.75.75 0 011.063.852l-.708 2.836a.75.75 0 001.063.853l.041-.021M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-9-3.75h.008v.008H12V8.25z" />
                            </svg>
                            <span id="qrDescriptionText"></span>
                        </p>
                    </div>

                    <div class="mt-6">
                        <button type="button" onclick="closeQRModal()"
                            class="px-6 py-2 bg-gray-600 hover:bg-gray-700 text-white rounded-lg transition-colors">
                            Cerrar
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Form oculto para cancelar órdenes --}}
    <form id="cancelForm" method="POST" style="display: none;">
        @csrf
        <input type="hidden" name="reason" id="cancelReason">
    </form>

    @push('scripts')
        <script>
            @if (session('success'))
                showSuccess('{{ session('success') }}');
            @endif

            @if (session('error'))
                showError('{{ session('error') }}');
            @endif

            @if (session('warning'))
                showWarning('{{ session('warning') }}', 'Atención');
            @endif

            @if (session('info'))
                showInfo('{{ session('info') }}');
            @endif

            function showQRModal(orderId, type, orderNumber, qrUrl) {
                const modal = document.getElementById('qrModal');
                const title = document.getElementById('qrModalTitle');
                const orderNumberSpan = document.getElementById('qrOrderNumber');
                const image = document.getElementById('qrImage');
                const description = document.getElementById('qrDescriptionText');

                title.textContent = type === 'association' ? 'QR de Asociación' : 'QR de Entrega';
                orderNumberSpan.textContent = orderNumber;
                image.src = qrUrl;
                description.textContent = type === 'association' ?
                    '📱 El cliente escanea este QR y la orden se asocia automáticamente' :
                    '✅ El cliente escanea este QR para confirmar que recibió su orden';

                modal.classList.remove('hidden');
            }

            function closeQRModal() {
                document.getElementById('qrModal').classList.add('hidden');
            }

            function confirmCancel(orderId) {
                Swal.fire({
                    title: '¿Cancelar orden?',
                    input: 'textarea',
                    inputLabel: 'Motivo de cancelación',
                    inputPlaceholder: 'Escribe el motivo...',
                    inputAttributes: {
                        'aria-label': 'Motivo de cancelación',
                        'rows': 4
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Sí, cancelar orden',
                    cancelButtonText: 'No cancelar',
                    confirmButtonColor: '#dc2626',
                    cancelButtonColor: '#6b7280',
                    inputValidator: (value) => {
                        if (!value) {
                            return 'Debes escribir un motivo'
                        }
                        if (value.length < 10) {
                            return 'El motivo debe tener al menos 10 caracteres'
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        const form = document.getElementById('cancelForm');
                        form.action = `/orders/${orderId}/cancel`;
                        document.getElementById('cancelReason').value = result.value;
                        form.submit();
                    }
                });
            }
        </script>
    @endpush
</x-layouts.app.sidebar>
