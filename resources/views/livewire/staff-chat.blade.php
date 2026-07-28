<div class="h-full flex flex-col bg-[#101026]">
    <div class="p-6 border-b border-white/5 flex-shrink-0 bg-[#1a1a2e]">
        <h2 class="text-xl font-black text-white uppercase tracking-wider flex items-center gap-3">
            <i class="fa-brands fa-whatsapp text-green-500 text-2xl"></i>
            Staff Chat
        </h2>
        <p class="text-xs text-gray-500 mt-1 uppercase tracking-widest font-bold">Chat privado (Admin, Agente TI, Gestor)</p>
    </div>

    <div class="flex-1 overflow-y-auto p-6 space-y-4" id="staff-chat-container" wire:poll.3s>
        @foreach($messages as $msg)
            @php
                $isMine = $msg->user_id === Auth::id();
            @endphp
            <div class="flex w-full {{ $isMine ? 'justify-end' : 'justify-start' }}">
                <div class="flex {{ $isMine ? 'flex-row-reverse' : 'flex-row' }} items-end gap-2 max-w-[80%]">
                    {{-- Avatar --}}
                    <div class="w-8 h-8 rounded-full flex-shrink-0 flex items-center justify-center text-[10px] font-black text-white shadow-lg {{ $isMine ? 'bg-indigo-600' : 'bg-slate-700' }}">
                        @if($msg->user && $msg->user->profile_photo_path)
                            <img src="{{ asset('storage/' . $msg->user->profile_photo_path) }}" class="w-full h-full rounded-full object-cover">
                        @else
                            {{ strtoupper(substr($msg->user->nombre_completo ?? '?', 0, 2)) }}
                        @endif
                    </div>
                    
                    {{-- Message Box --}}
                    <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }}">
                        <span class="text-[9px] text-gray-500 font-bold uppercase tracking-wider mb-1 px-1">
                            {{ $msg->user->nombre_completo ?? 'Usuario' }} • {{ $msg->created_at->format('h:i A') }}
                        </span>
                        <div class="px-4 py-3 rounded-2xl text-sm shadow-xl {{ $isMine ? 'bg-indigo-600 text-white rounded-br-none' : 'bg-slate-800 text-gray-300 rounded-bl-none border border-white/5' }}">
                            {{ $msg->message }}
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="p-4 border-t border-white/5 bg-[#1a1a2e] flex-shrink-0">
        <form wire:submit.prevent="sendMessage" class="flex gap-2 relative">
            <input type="text" wire:model="message" placeholder="Escribe un mensaje..."
                class="w-full bg-slate-900 border border-white/10 rounded-2xl px-6 py-4 text-sm text-white outline-none focus:border-indigo-500 transition-all placeholder:text-gray-600">
            <button type="submit" class="absolute right-2 top-2 bottom-2 aspect-square bg-indigo-600 hover:bg-indigo-500 rounded-xl text-white flex items-center justify-center transition-all shadow-lg active:scale-95 disabled:opacity-50" wire:loading.attr="disabled" wire:target="sendMessage">
                <i class="fa-solid fa-paper-plane" wire:loading.class="hidden" wire:target="sendMessage"></i>
                <i class="fa-solid fa-circle-notch fa-spin hidden" wire:loading.class.remove="hidden" wire:target="sendMessage"></i>
            </button>
        </form>
    </div>

    <script>
        document.addEventListener('livewire:initialized', () => {
            const scrollToBottom = () => {
                const container = document.getElementById('staff-chat-container');
                if (container) {
                    container.scrollTop = container.scrollHeight;
                }
            };
            
            Livewire.on('scroll-bottom-staff', () => {
                setTimeout(scrollToBottom, 50);
            });
            
            // Initial scroll
            setTimeout(scrollToBottom, 100);
        });
    </script>
</div>
