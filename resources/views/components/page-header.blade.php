@props(['title', 'icon' => null])

<div class="mb-6">
    <div class="flex items-center">
        @if($icon)
            <x-dynamic-component :component="'heroicon-o-' . $icon" class="w-8 h-8 text-gray-700 mr-3" />
        @endif
        <h2 class="text-3xl font-bold text-gray-900">{{ $title }}</h2>
    </div>
    @if(isset($subtitle))
        <p class="mt-2 text-sm text-gray-600">{{ $subtitle }}</p>
    @endif
</div>
