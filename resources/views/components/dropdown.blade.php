@props(['align' => 'right', 'width' => '48'])

<div class="relative" x-data="{ open: false }" @click.away="open = false" @close.stop="open = false">
    <div @click="open = ! open">
        {{ $trigger }}
    </div>

    <div x-show="open"
            x-transition
            class="absolute z-50 mt-2 {{ $width === '48' ? 'w-48' : '' }} rounded-md shadow-lg {{ $align === 'right' ? 'right-0' : 'left-0' }}"
            style="display: none;">
        <div class="rounded-md ring-1 ring-black ring-opacity-5 py-1 bg-white">
            {{ $content }}
        </div>
    </div>
</div>