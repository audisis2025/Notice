@props([
    'title',
    'value',
    'icon',
    'color' => 'blue',
    'trend' => null,
    'trendUp' => true
])

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex items-center justify-between">
        <div class="flex-1">
            <p class="text-sm font-medium text-gray-600">{{ $title }}</p>
            <p class="mt-2 text-3xl font-bold text-gray-900">{{ $value }}</p>
            
            @if($trend)
                <div class="mt-2 flex items-center text-sm">
                    @if($trendUp)
                        <x-heroicon-o-arrow-trending-up class="w-4 h-4 text-green-500 mr-1" />
                        <span class="text-green-600 font-medium">{{ $trend }}</span>
                    @else
                        <x-heroicon-o-arrow-trending-down class="w-4 h-4 text-red-500 mr-1" />
                        <span class="text-red-600 font-medium">{{ $trend }}</span>
                    @endif
                    <span class="ml-1 text-gray-500">vs. mes anterior</span>
                </div>
            @endif
        </div>
        
        <div class="ml-4">
            <div class="p-3 rounded-full bg-{{ $color }}-100">
                <x-dynamic-component 
                    :component="'heroicon-o-' . $icon" 
                    class="w-8 h-8 text-{{ $color }}-600" 
                />
            </div>
        </div>
    </div>
</div>