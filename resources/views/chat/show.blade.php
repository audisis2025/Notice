<x-app-layout>
    @section('page-title', 'Chat - Orden ' . $order->order_number)

    <div class="max-w-5xl mx-auto">
        @livewire('chat.chat-component', ['order' => $order])
    </div>
</x-app-layout>