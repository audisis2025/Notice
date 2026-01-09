@props([
    'type' => 'button',
    'variant' => 'primary',
    'size' => 'md',
    'outline' => false,
    'loading' => false,
    'icon' => null,
    'iconPosition' => 'left'
])

@php
$baseClasses = 'inline-flex items-center justify-center font-medium rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2';

$variants = [
    'primary' => $outline 
        ? 'border-2 border-black text-black hover:bg-black hover:text-white focus:ring-black'
        : 'bg-black text-white hover:bg-gray-800 focus:ring-gray-900',
    'secondary' => $outline
        ? 'border-2 border-gray-600 text-gray-600 hover:bg-gray-600 hover:text-white focus:ring-gray-600'
        : 'bg-gray-600 text-white hover:bg-gray-700 focus:ring-gray-600',
    'success' => $outline
        ? 'border-2 border-green-600 text-green-600 hover:bg-green-600 hover:text-white focus:ring-green-600'
        : 'bg-green-600 text-white hover:bg-green-700 focus:ring-green-600',
    'danger' => $outline
        ? 'border-2 border-red-600 text-red-600 hover:bg-red-600 hover:text-white focus:ring-red-600'
        : 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-600',
    'warning' => $outline
        ? 'border-2 border-yellow-600 text-yellow-600 hover:bg-yellow-600 hover:text-white focus:ring-yellow-600'
        : 'bg-yellow-600 text-white hover:bg-yellow-700 focus:ring-yellow-600',
    'info' => $outline
        ? 'border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white focus:ring-blue-600'
        : 'bg-blue-600 text-white hover:bg-blue-700 focus:ring-blue-600',
    'ghost' => 'text-gray-700 hover:bg-gray-100 focus:ring-gray-500',
];

$sizes = [
    'xs' => 'px-2.5 py-1.5 text-xs',
    'sm' => 'px-3 py-2 text-sm',
    'md' => 'px-4 py-2 text-sm',
    'lg' => 'px-5 py-3 text-base',
    'xl' => 'px-6 py-3.5 text-base',
];

$classes = $baseClasses . ' ' . $variants[$variant] . ' ' . $sizes[$size];
@endphp

<button {{ $attributes->merge(['type' => $type, 'class' => $classes]) }} @if($loading) disabled @endif>
    @if($loading)
        <svg class="animate-spin -ml-1 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
        </svg>
        Procesando...
    @else
        @if($icon && $iconPosition === 'left')
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 mr-2" />
        @endif
        
        {{ $slot }}
        
        @if($icon && $iconPosition === 'right')
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-5 h-5 ml-2" />
        @endif
    @endif
</button>
