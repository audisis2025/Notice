@props([
    'icon' => 'inbox',
    'title' => 'No hay datos',
    'description' => 'No se encontraron resultados',
    'actionText' => null,
    'actionUrl' => null
])

<div class="text-center py-12">
    <x-dynamic-component 
        :component="'heroicon-o-' . $icon" 
        class="w-16 h-16 mx-auto text-gray-300 mb-4" 
    />
    
    <h3 class="text-lg font-medium text-gray-900 mb-2">{{ $title }}</h3>
    <p class="text-sm text-gray-500 mb-6">{{ $description }}</p>
    
    @if($actionText && $actionUrl)
        <flux:button 
            variant="primary" 
            href="{{ $actionUrl }}"
            class="bg-black hover:bg-[#494949]"
        >
            {{ $actionText }}
        </flux:button>
    @endif
</div>