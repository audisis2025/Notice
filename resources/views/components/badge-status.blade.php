@props(['status'])

@php
    $statusConfig = [
        'pending' => ['variant' => 'gray', 'icon' => 'clock', 'text' => 'Pendiente'],
        'paid' => ['variant' => 'info', 'icon' => 'credit-card', 'text' => 'Pagada'],
        'ready' => ['variant' => 'warning', 'icon' => 'check-circle', 'text' => 'Lista'],
        'delivered' => ['variant' => 'success', 'icon' => 'check-badge', 'text' => 'Entregada'],
        'cancelled' => ['variant' => 'danger', 'icon' => 'x-circle', 'text' => 'Cancelada'],
        'active' => ['variant' => 'success', 'icon' => 'check-circle', 'text' => 'Activo'],
        'inactive' => ['variant' => 'danger', 'icon' => 'x-circle', 'text' => 'Inactivo'],
        'expired' => ['variant' => 'danger', 'icon' => 'clock', 'text' => 'Expirado'],
    ];
    
    $config = $statusConfig[$status] ?? ['variant' => 'gray', 'icon' => 'question-mark-circle', 'text' => ucfirst($status)];
@endphp

<flux:badge variant="{{ $config['variant'] }}">
    <x-dynamic-component :component="'heroicon-o-' . $config['icon']" class="w-4 h-4 mr-1" />
    {{ $config['text'] }}
</flux:badge>