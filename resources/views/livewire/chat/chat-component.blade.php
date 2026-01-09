<div class="h-[calc(100vh-12rem)] flex flex-col bg-white rounded-lg shadow-lg overflow-hidden">
    {{-- Header del chat --}}
    <div class="bg-gradient-to-r from-black to-gray-800 p-6 text-white">
        <div class="flex items-center justify-between">
            <div>
                <div class="flex items-center mb-2">
                    <x-heroicon-o-chat-bubble-left-right class="w-6 h-6 mr-3" />
                    <h2 class="text-xl font-bold">Chat - Orden {{ $order->order_number }}</h2>
                </div>
                <p class="text-sm text-gray-300">
                    @if(auth()->user()->isBusinessAdministrator())
                        Conversación con {{ $order->user->name }}
                    @else
                        Conversación con {{ $order->business->business_name }}
                    @endif
                </p>
            </div>
            
            <flux:button 
                variant="ghost"
                href="{{ auth()->user()->isBusinessAdministrator() ? route('orders.show', $order) : '#' }}"
                class="text-white border-white hover:bg-white hover:text-black"
            >
                <x-heroicon-o-x-mark class="w-5 h-5" />
            </flux:button>
        </div>
    </div>

    {{-- Área de mensajes --}}
    <div class="flex-1 overflow-y-auto p-6 space-y-4 bg-gray-50" id="messages-container">
        @if($messages->isEmpty())
            <div class="h-full flex items-center justify-center">
                <div class="text-center text-gray-400">
                    <x-heroicon-o-chat-bubble-left-ellipsis class="w-16 h-16 mx-auto mb-4" />
                    <p class="text-lg font-medium">No hay mensajes aún</p>
                    <p class="text-sm">Envía el primer mensaje para iniciar la conversación</p>
                </div>
            </div>
        @else
            @foreach($messages->reverse() as $message)
                <div class="flex @if($message->sender_id === auth()->id()) justify-end @else justify-start @endif">
                    <div class="max-w-xs lg:max-w-md">
                        {{-- Avatar y nombre --}}
                        <div class="flex items-center mb-2 @if($message->sender_id === auth()->id()) justify-end @endif">
                            @if($message->sender_id !== auth()->id())
                                <div class="flex-shrink-0 h-8 w-8 bg-gray-300 rounded-full flex items-center justify-center mr-2">
                                    <x-heroicon-o-user class="w-5 h-5 text-gray-600" />
                                </div>
                            @endif
                            
                            <span class="text-xs font-medium text-gray-600">
                                {{ $message->sender->name }}
                            </span>
                            
                            @if($message->sender_id === auth()->id())
                                <div class="flex-shrink-0 h-8 w-8 bg-black rounded-full flex items-center justify-center ml-2">
                                    <x-heroicon-o-user class="w-5 h-5 text-white" />
                                </div>
                            @endif
                        </div>

                        {{-- Mensaje --}}
                        <div class="@if($message->sender_id === auth()->id()) bg-black text-white @else bg-white text-gray-900 border border-gray-200 @endif rounded-2xl px-4 py-3 shadow">
                            <p class="text-sm break-words">{{ $message->message }}</p>
                            
                            <div class="flex items-center justify-between mt-2 pt-2 border-t @if($message->sender_id === auth()->id()) border-gray-700 @else border-gray-200 @endif">
                                <span class="text-xs @if($message->sender_id === auth()->id()) text-gray-300 @else text-gray-500 @endif">
                                    {{ $message->created_at->format('H:i') }}
                                </span>
                                
                                @if($message->sender_id === auth()->id())
                                    @if($message->is_read)
                                        <x-heroicon-o-check-badge class="w-4 h-4 text-blue-400" title="Leído" />
                                    @else
                                        <x-heroicon-o-check class="w-4 h-4 text-gray-400" title="Enviado" />
                                    @endif
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        @endif
    </div>

    {{-- Formulario de envío --}}
    <div class="p-4 bg-white border-t">
        <form wire:submit="sendMessage" class="flex items-end space-x-3">
            <div class="flex-1">
                <flux:textarea 
                    wire:model="messageText"
                    rows="2"
                    placeholder="Escribe tu mensaje..."
                    class="resize-none"
                    wire:keydown.enter.prevent="sendMessage"
                ></flux:textarea>
                @error('messageText')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <flux:button 
                type="submit"
                variant="primary"
                class="bg-black hover:bg-[#494949]"
                wire:loading.attr="disabled"
            >
                <span wire:loading.remove wire:target="sendMessage">
                    <x-heroicon-o-paper-airplane class="w-5 h-5" />
                </span>
                <span wire:loading wire:target="sendMessage">
                    <x-loading-spinner size="sm" />
                </span>
            </flux:button>
        </form>

        <p class="mt-2 text-xs text-gray-500 flex items-center">
            <x-heroicon-o-information-circle class="w-4 h-4 mr-1" />
            Presiona Enter para enviar, Shift+Enter para nueva línea
        </p>
    </div>

    @script
    <script>
        // Auto-scroll al último mensaje
        function scrollToBottom() {
            const container = document.getElementById('messages-container');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        }

        // Scroll inicial
        scrollToBottom();

        // Scroll después de actualizar mensajes
        $wire.on('message-sent', () => {
            setTimeout(scrollToBottom, 100);
        });

        // Actualizar mensajes cada 5 segundos
        setInterval(() => {
            $wire.call('loadChat');
        }, 5000);
    </script>
    @endscript
</div>