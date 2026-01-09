@props([
    'type' => 'info',
    'title' => null,
    'dismissible' => true,
    'icon' => true
])

@php
$styles = [
    'success' => 'bg-green-50 border-green-400 text-green-800',
    'error' => 'bg-red-50 border-red-400 text-red-800',
    'warning' => 'bg-yellow-50 border-yellow-400 text-yellow-800',
    'info' => 'bg-blue-50 border-blue-400 text-blue-800',
];

$icons = [
    'success' => 'check-circle',
    'error' => 'x-circle',
    'warning' => 'exclamation-triangle',
    'info' => 'information-circle',
];

$iconColors = [
    'success' => 'text-green-500',
    'error' => 'text-red-500',
    'warning' => 'text-yellow-500',
    'info' => 'text-blue-500',
];
@endphp

<div {{ $attributes->merge(['class' => 'border-l-4 p-4 rounded-lg ' . $styles[$type]]) }} role="alert">
    <div class="flex">
        @if($icon)
            <div class="flex-shrink-0">
                <x-dynamic-component :component="'heroicon-o-' . $icons[$type]" class="w-5 h-5 {{ $iconColors[$type] }}" />
            </div>
        @endif
        
        <div class="ml-3 flex-1">
            @if($title)
                <h3 class="font-semibold mb-1">{{ $title }}</h3>
            @endif
            
            <div class="text-sm">
                {{ $slot }}
            </div>
        </div>
        
        @if($dismissible)
            <div class="ml-auto pl-3">
                <button type="button" class="inline-flex rounded-md p-1.5 hover:bg-opacity-20 hover:bg-gray-600 focus:outline-none" onclick="this.closest('[role=alert]').remove()">
                    <x-heroicon-o-x-mark class="w-5 h-5" />
                </button>
            </div>
        @endif
    </div>
</div>
