<x-app-layout>
    <x-flash-messages />
    @section('page-title', 'Calificaciones de ' . $business->business_name)

    <div class="max-w-7xl mx-auto">
        {{-- Encabezado --}}
        <div class="mb-6 flex justify-between items-center">
            <div class="flex items-center">
                <x-heroicon-o-star class="w-8 h-8 text-yellow-500 mr-3" />
                <h2 class="text-3xl font-bold text-gray-900">Calificaciones y Reseñas</h2>
            </div>
            
            <flux:button 
                variant="ghost" 
                href="{{ route('dashboard') }}"
            >
                <x-heroicon-o-arrow-left class="w-5 h-5 mr-2" />
                Regresar
            </flux:button>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            {{-- Estadísticas --}}
            <div class="lg:col-span-1 space-y-6">
                {{-- Calificación promedio --}}
                <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                    <div class="mb-4">
                        <div class="text-6xl font-bold text-gray-900">
                            {{ number_format($stats['average_stars'], 1) }}
                        </div>
                        <div class="flex items-center justify-center my-3">
                            @for($i = 1; $i <= 5; $i++)
                                @if($i <= floor($stats['average_stars']))
                                    <x-heroicon-s-star class="w-8 h-8 text-yellow-400" />
                                @elseif($i - $stats['average_stars'] < 1)
                                    <x-heroicon-s-star class="w-8 h-8 text-yellow-200" />
                                @else
                                    <x-heroicon-o-star class="w-8 h-8 text-gray-300" />
                                @endif
                            @endfor
                        </div>
                        <p class="text-sm text-gray-600">
                            Basado en {{ number_format($stats['total_ratings']) }} calificaciones
                        </p>
                    </div>
                </div>

                {{-- Distribución de estrellas --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">Distribución</h3>
                    <div class="space-y-3">
                        @for($i = 5; $i >= 0; $i--)
                            <div class="flex items-center">
                                <div class="flex items-center w-20">
                                    <span class="text-sm font-medium text-gray-700">{{ $i }}</span>
                                    <x-heroicon-s-star class="w-4 h-4 text-yellow-400 ml-1" />
                                </div>
                                
                                <div class="flex-1 mx-3">
                                    <div class="bg-gray-200 rounded-full h-3">
                                        <div 
                                            class="bg-yellow-400 h-3 rounded-full transition-all duration-500"
                                            style="width: {{ $stats['distribution'][$i]['percentage'] }}%"
                                        ></div>
                                    </div>
                                </div>
                                
                                <div class="w-16 text-right">
                                    <span class="text-sm font-medium text-gray-700">
                                        {{ $stats['distribution'][$i]['count'] }}
                                    </span>
                                    <span class="text-xs text-gray-500">
                                        ({{ number_format($stats['distribution'][$i]['percentage'], 1) }}%)
                                    </span>
                                </div>
                            </div>
                        @endfor
                    </div>
                </div>

                {{-- Estado de calificaciones --}}
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                        <x-heroicon-o-cog-6-tooth class="w-6 h-6 mr-2" />
                        Configuración
                    </h3>
                    
                    <form action="{{ route('business.toggle-ratings') }}" method="POST">
                        @csrf
                        <input type="hidden" name="can_be_rated" value="{{ $business->can_be_rated ? 0 : 1 }}">
                        
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg mb-4">
                            <div class="flex items-center">
                                @if($business->can_be_rated)
                                    <x-heroicon-o-check-circle class="w-6 h-6 text-green-500 mr-3" />
                                    <div>
                                        <p class="font-medium text-gray-900">Calificaciones activadas</p>
                                        <p class="text-sm text-gray-500">Los clientes pueden calificar</p>
                                    </div>
                                @else
                                    <x-heroicon-o-x-circle class="w-6 h-6 text-red-500 mr-3" />
                                    <div>
                                        <p class="font-medium text-gray-900">Calificaciones desactivadas</p>
                                        <p class="text-sm text-gray-500">Los clientes no pueden calificar</p>
                                    </div>
                                @endif
                            </div>
                        </div>

                        <flux:button 
                            type="submit"
                            variant="{{ $business->can_be_rated ? 'danger' : 'success' }}"
                            class="w-full"
                        >
                            @if($business->can_be_rated)
                                <x-heroicon-o-x-circle class="w-5 h-5 mr-2" />
                                Desactivar Calificaciones
                            @else
                                <x-heroicon-o-check-circle class="w-5 h-5 mr-2" />
                                Activar Calificaciones
                            @endif
                        </flux:button>
                    </form>
                </div>
            </div>

            {{-- Lista de calificaciones --}}
            <div class="lg:col-span-2">
                <div class="bg-white rounded-lg shadow">
                    <div class="p-6 border-b">
                        <h3 class="text-lg font-semibold text-gray-900 flex items-center">
                            <x-heroicon-o-chat-bubble-left-ellipsis class="w-6 h-6 mr-2" />
                            Reseñas de Clientes
                        </h3>
                    </div>

                    <div class="divide-y">
                        @forelse($ratings as $rating)
                            <div class="p-6 hover:bg-gray-50 transition">
                                {{-- Encabezado de la reseña --}}
                                <div class="flex items-start justify-between mb-3">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-12 w-12 bg-gray-200 rounded-full flex items-center justify-center">
                                            <x-heroicon-o-user class="w-7 h-7 text-gray-500" />
                                        </div>
                                        <div class="ml-4">
                                            <p class="font-semibold text-gray-900">{{ $rating->user->name }}</p>
                                            <div class="flex items-center mt-1">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $rating->stars)
                                                        <x-heroicon-s-star class="w-5 h-5 text-yellow-400" />
                                                    @else
                                                        <x-heroicon-o-star class="w-5 h-5 text-gray-300" />
                                                    @endif
                                                @endfor
                                                <span class="ml-2 text-sm font-medium text-gray-700">
                                                    {{ $rating->stars }}/5
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    <div class="text-right">
                                        <p class="text-sm text-gray-500">{{ $rating->created_at->format('d/m/Y') }}</p>
                                        <p class="text-xs text-gray-400">{{ $rating->created_at->diffForHumans() }}</p>
                                    </div>
                                </div>

                                {{-- Comentario --}}
                                @if($rating->comment)
                                    <div class="mt-3 p-4 bg-gray-50 rounded-lg">
                                        <p class="text-gray-700 text-sm italic">"{{ $rating->comment }}"</p>
                                    </div>
                                @endif

                                {{-- Información de la orden --}}
                                <div class="mt-3 flex items-center text-xs text-gray-500">
                                    <x-heroicon-o-shopping-bag class="w-4 h-4 mr-1" />
                                    <span>Orden: </span>
                                    <a href="{{ route('orders.show', $rating->order) }}" class="ml-1 font-mono font-medium text-blue-600 hover:text-blue-800">
                                        {{ $rating->order->order_number }}
                                    </a>
                                </div>
                            </div>
                        @empty
                            <div class="p-12">
                                <x-empty-state
                                    icon="star"
                                    title="Sin calificaciones aún"
                                    description="Aún no has recibido calificaciones de tus clientes"
                                />
                            </div>
                        @endforelse
                    </div>

                    {{-- Paginación --}}
                    @if($ratings->hasPages())
                        <div class="px-6 py-4 border-t">
                            {{ $ratings->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>