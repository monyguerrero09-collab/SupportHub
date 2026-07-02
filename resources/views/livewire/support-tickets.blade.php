<div wire:poll.5s>
    
    {{-- Header Button --}}
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-8 gap-4">
        <div>
            <h3 class="text-3xl font-black text-transparent bg-clip-text bg-gradient-to-r from-[#0c4aed] to-[#011860] dark:from-[#3a75ff] dark:to-[#8ab0ff] tracking-tight">Centro de Soporte</h3>
            <p class="text-sm font-bold text-gray-500 dark:text-gray-400 mt-1">
                ¿En qué podemos ayudarte el día de hoy?
            </p>
        </div>
        @if(!$isCreating)
        <button wire:click="showCreateForm" class="flex items-center gap-2 px-6 py-3.5 bg-[#0c4aed] hover:bg-[#0535b5] text-white rounded-xl text-sm font-bold transition-all duration-300 shadow-[0_10px_20px_-10px_rgba(12,74,237,0.5)] hover:-translate-y-1">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
            Crear Nuevo Ticket
        </button>
        @endif
    </div>

    @if(session()->has('message'))
    <div class="mb-6 p-4 rounded-xl bg-[#e6f4ea] dark:bg-emerald-500/10 border-l-4 border-[#34a853] text-[#137333] dark:text-emerald-400 flex items-center gap-3 shadow-sm animate-fade-in-down font-medium">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span class="font-bold">{{ session('message') }}</span>
    </div>
    @endif

    {{-- Creation Form --}}
    @if($isCreating)
    <div class="relative bg-white dark:bg-gray-900 rounded-3xl mb-10 border border-gray-100 dark:border-gray-800 shadow-[0_20px_60px_-15px_rgba(0,0,0,0.1)] transition-all flex flex-col lg:flex-row overflow-hidden">
        
        {{-- Left Side: Decorative & Info --}}
        <div class="lg:w-2/5 p-10 relative bg-gradient-to-br from-[#0c4aed] via-[#0535b5] to-[#011860] text-white flex flex-col justify-between overflow-hidden">
            {{-- Decorative Abstract Lines (matching login) --}}
            <div class="absolute inset-0 z-0" style="background: repeating-linear-gradient(45deg, transparent, transparent 18px, rgba(255,255,255,0.03) 18px, rgba(255,255,255,0.03) 36px);"></div>
            <div class="absolute -top-10 -right-20 w-[150%] h-[3px] bg-cyan-400/40 rotate-[-45deg] blur-[1px]"></div>
            <div class="absolute top-[20%] -right-20 w-[150%] h-[2px] bg-white/30 rotate-[-45deg] blur-[1px]"></div>
            <div class="absolute top-[40%] -right-20 w-[150%] h-[5px] bg-blue-400/20 rotate-[-45deg] blur-[2px]"></div>
            <div class="absolute -bottom-10 -left-10 w-[120%] h-[4px] bg-cyan-300/30 rotate-[-45deg]"></div>
            
            <div class="relative z-10">
                <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center mb-6 shadow-xl">
                    <svg class="w-7 h-7 text-[#0c4aed]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <h4 class="text-3xl font-black mb-2 -tracking-tight">Solución Rápida</h4>
                <p class="text-blue-100 font-medium leading-relaxed max-w-sm">Completa los campos con toda la información posible. Nuestro equipo de expertos revisará tu solicitud y te dará respuesta en tiempo récord.</p>
            </div>

            <div class="relative z-10 mt-10 lg:mt-0 space-y-4">
                <div class="flex items-center gap-3 bg-white/10 p-4 rounded-2xl backdrop-blur-sm border border-white/20">
                    <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
                    </div>
                    <div>
                        <p class="text-sm font-bold">Atención prioritaria</p>
                        <p class="text-xs text-blue-200">Soporte ininterrumpido a usuarios</p>
                    </div>
                </div>
            </div>
            <div class="absolute -bottom-32 -left-32 w-80 h-80 bg-[#16c8ff]/20 blur-[80px] rounded-full"></div>
        </div>

        {{-- Right Side: Form Inputs --}}
        <div class="lg:w-3/5 p-10 lg:p-12 relative z-10">
            <h4 class="text-xl font-bold mb-8 text-gray-800 dark:text-white flex items-center gap-3">
                <span class="w-8 h-8 rounded-lg bg-[#e8f0fe] dark:bg-[#0c4aed]/20 text-[#0c4aed] dark:text-[#3a75ff] flex items-center justify-center">
                    <svg class="w-4 h-4 text-inherit" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                </span>
                Detalles del Reporte
            </h4>
            
            <form wire:submit.prevent="saveTicket" enctype="multipart/form-data" class="space-y-6">
                
                {{-- Floating Label Input for Title --}}
                <div class="relative group">
                    <input type="text" id="tit" wire:model="title" class="block w-full px-5 py-4 text-sm font-medium text-gray-900 dark:text-white bg-gray-50/50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:border-[#0c4aed] focus:bg-white dark:focus:bg-gray-900 focus:ring-0 peer placeholder-transparent transition-all shadow-sm" placeholder="Asunto Principal" />
                    <label for="tit" class="absolute left-5 top-4 text-gray-500 dark:text-gray-400 text-sm font-bold transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-focus:top-1 peer-focus:text-[11px] peer-focus:text-[#0c4aed] dark:peer-focus:text-[#3a75ff]">
                        Asunto Principal
                    </label>
                    <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-400 group-focus-within:text-[#0c4aed]">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    @error('title') <span class="text-rose-500 text-[11px] font-bold mt-2 block pl-2">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 @if(Auth::user()?->role === 'admin' || Auth::user()?->role === 'agente') md:grid-cols-2 @endif gap-4">
                    {{-- Tipo de Ticket --}}
                    <div>
                        <label class="block text-[11px] font-bold text-[#0c4aed] dark:text-[#3a75ff] mb-1 pl-2">Tipo de Ticket</label>
                        <select wire:model="tipo_ticket_id" class="block w-full bg-gray-50/50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:border-[#0c4aed] px-4 py-3 text-sm text-gray-900 dark:text-white font-medium cursor-pointer transition-colors shadow-sm focus:ring-0">
                            <option value="">-- Seleccionar --</option>
                            @foreach($tipos as $tipo)
                            <option value="{{ $tipo->id }}">{{ $tipo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('tipo_ticket_id') <span class="text-rose-500 text-[11px] font-bold mt-1 block pl-2">{{ $message }}</span> @enderror
                    </div>

                    @if(Auth::user()?->role === 'admin' || Auth::user()?->role === 'agente')
                    {{-- Prioridad --}}
                    <div>
                        <label class="block text-[11px] font-bold text-[#0c4aed] dark:text-[#3a75ff] mb-1 pl-2">Nivel de Prioridad</label>
                        <select wire:model="prioridad_id" class="block w-full bg-gray-50/50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:border-[#0c4aed] px-4 py-3 text-sm text-gray-900 dark:text-white font-medium cursor-pointer transition-colors shadow-sm focus:ring-0">
                            <option value="">-- Seleccionar --</option>
                            @foreach($prioridades as $prioridad)
                            <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}</option>
                            @endforeach
                        </select>
                        @error('prioridad_id') <span class="text-rose-500 text-[11px] font-bold mt-1 block pl-2">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>



                {{-- Floating Label area --}}
                <div class="relative group mt-2">
                    <textarea id="desc" wire:model="description" rows="5" class="block w-full px-5 py-4 pt-6 text-sm font-medium text-gray-900 dark:text-white bg-gray-50/50 dark:bg-gray-800 border-2 border-gray-100 dark:border-gray-700 rounded-xl focus:border-[#0c4aed] focus:bg-white dark:focus:bg-gray-900 focus:ring-0 peer placeholder-transparent transition-all resize-none shadow-sm" placeholder="Descripción..."></textarea>
                    <label for="desc" class="absolute left-5 top-4 text-gray-500 dark:text-gray-400 text-sm font-bold transition-all peer-placeholder-shown:text-sm peer-placeholder-shown:top-4 peer-placeholder-shown:text-gray-400 peer-focus:top-2 peer-focus:text-[11px] peer-focus:text-[#0c4aed] dark:peer-focus:text-[#3a75ff]">
                        Descripción detallada del reporte
                    </label>
                    @error('description') <span class="text-rose-500 text-[11px] font-bold mt-2 block pl-2">{{ $message }}</span> @enderror
                </div>

                {{-- Attach Image --}}
                <div class="space-y-2">
                    <label class="block text-[11px] font-bold text-gray-500 dark:text-gray-400 mb-1 pl-2">Adjuntar imagen (máximo 2)</label>
                    <input type="file" wire:model="attachments" multiple accept="image/*" class="block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-[#0c4aed] file:text-white hover:file:bg-[#0535b5]" />
                    @error('attachments') <span class="text-rose-500 text-[11px] font-bold block pl-2">{{ $message }}</span> @enderror
                    @error('attachments.*') <span class="text-rose-500 text-[11px] font-bold block pl-2">{{ $message }}</span> @enderror

                    @if(!empty($attachments))
                        <div class="flex flex-wrap gap-3 mt-3">
                        @foreach($attachments as $index => $file)
                            <div class="relative group rounded-xl overflow-hidden border border-gray-200 dark:border-gray-700 inline-block bg-gray-100 dark:bg-gray-800">
                                <img src="{{ $file->temporaryUrl() }}" class="h-28 w-auto object-cover" alt="Vista previa">
                                <button type="button" wire:click="removeAttachment({{ $index }})" class="absolute top-1 right-1 p-1 bg-red-500 hover:bg-red-600 text-white rounded-lg opacity-0 group-hover:opacity-100 transition-opacity" title="Eliminar imagen">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                                <div class="absolute bottom-0 left-0 right-0 bg-black/60 p-1 text-center">
                                    <span class="text-[9px] text-white truncate px-1 block">{{ $file->getClientOriginalName() }}</span>
                                </div>
                            </div>
                        @endforeach
                        </div>
                    @endif
                </div>

                {{-- Action Buttons --}}
                <div class="flex items-center justify-end gap-3 pt-6 border-t border-gray-100 dark:border-gray-800">
                    <button type="button" wire:click="cancelCreate" class="px-6 py-3.5 text-gray-500 hover:text-gray-900 dark:text-gray-400 dark:hover:text-white font-bold text-sm transition-colors rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800">
                        Cancelar
                    </button>
                    <button type="submit" class="px-8 py-3.5 bg-[#0c4aed] text-white rounded-xl font-bold text-sm transition-all hover:scale-[1.02] active:scale-95 shadow-[0_10px_20px_-10px_rgba(12,74,237,0.5)] flex items-center gap-2">
                        Aperturar Ticket
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- Ticket List Grid --}}
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @forelse($tickets as $ticket)
        <div class="bg-[#0d1120] rounded-2xl p-5 border border-white/[0.07] hover:border-blue-500/30 hover:shadow-[0_8px_30px_rgba(37,99,235,0.1)] transition-all duration-300 group relative overflow-hidden flex flex-col h-full">
            
            {{-- Decorative Accent Bar (Very thin on top) --}}
            <div class="absolute top-0 left-0 w-full h-1 
                @if($ticket->estado->nombre === 'Abierto') bg-[#fbbc04]
                @elseif($ticket->estado->nombre === 'En Progreso') bg-[#4285f4]
                @elseif($ticket->estado->nombre === 'Resuelto') bg-[#0f9d58]
                @else bg-gray-400
                @endif
            "></div>

            <div class="flex items-center justify-between mb-4">
                <span class="text-[11px] font-black tracking-widest text-[#0c4aed] dark:text-[#3a75ff] flex items-center gap-1.5 bg-[#e8f0fe] dark:bg-[#0c4aed]/20 px-2 py-1 rounded-md">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"/></svg>
                    TIK-{{ str_pad($ticket->id, 5, '0', STR_PAD_LEFT) }}
                </span>
                
                <div class="flex items-center gap-1.5">
                    @if($ticket->prioridad->nombre === 'Alta' || $ticket->prioridad->nombre === 'Urgente')
                        <span class="px-2 py-0.5 bg-[#fce8e6] text-[#d93025] dark:bg-red-500/10 dark:text-red-400 text-[10px] font-bold uppercase tracking-wider rounded-md flex items-center gap-1">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                            {{ $ticket->prioridad->nombre }}
                        </span>
                    @endif

                    @if($ticket->estado->nombre === 'Abierto')
                        <span class="px-2 py-0.5 bg-[#fef7e0] text-[#e37400] text-[10px] font-bold uppercase tracking-wider rounded-md">Abierto</span>
                    @elseif($ticket->estado->nombre === 'En Progreso')
                        <span class="px-2 py-0.5 bg-[#e8f0fe] text-[#1a73e8] text-[10px] font-bold uppercase tracking-wider rounded-md">Progreso</span>
                    @elseif($ticket->estado->nombre === 'Resuelto')
                        <span class="px-2 py-0.5 bg-[#e6f4ea] text-[#137333] text-[10px] font-bold uppercase tracking-wider rounded-md">Resuelto</span>
                    @else
                        <span class="px-2 py-0.5 bg-gray-100 text-gray-700 text-[10px] font-bold uppercase tracking-wider rounded-md">{{ $ticket->estado->nombre }}</span>
                    @endif
                </div>
            </div>

            <div class="flex items-center gap-2 mb-2">
                <span class="text-[11px] font-bold text-gray-500 dark:text-gray-400">{{ $ticket->tipoTicket->nombre ?? 'N/A' }}</span>
                @if($ticket->maquina)
                    <span class="text-[10px] font-bold text-gray-400 dark:text-gray-500 border-l border-gray-200 dark:border-gray-700 pl-2">💻 {{ $ticket->maquina->nombre }}</span>
                @endif
            </div>

            <h4 class="text-base font-bold text-gray-900 dark:text-white mb-2 line-clamp-2 group-hover:text-[#0c4aed] dark:group-hover:text-[#3a75ff] transition-colors leading-snug">{{ $ticket->titulo }}</h4>
            <p class="text-[13px] text-gray-500 dark:text-gray-400 mb-6 line-clamp-3 leading-relaxed w-full font-medium">{{ $ticket->descripcion }}</p>

            <div class="mt-auto pt-4 border-t border-gray-100 dark:border-gray-800 flex items-center justify-between">
                <div class="flex items-center gap-2.5">
                    <div class="w-7 h-7 rounded-lg bg-[#011860] flex items-center justify-center text-white text-[10px] font-bold shadow-sm">
                        {{ substr($ticket->creador->name ?? '?', 0, 2) }}
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-800 dark:text-gray-200">{{ $ticket->creador->name ?? 'Desconocido' }}
                            @if($ticket->departamento)
                                <span class="text-[10px] font-medium text-[#0c4aed] dark:text-[#3a75ff] ml-1">({{ $ticket->departamento->nombre }})</span>
                            @endif
                        </p>
                        <p class="text-[10px] font-medium text-gray-400 dark:text-gray-500">{{ $ticket->created_at->diffForHumans() }}</p>
                    </div>
                </div>

                @if(Auth::user()->role === 'admin' || Auth::user()->role === 'agente')
                <div class="relative flex items-center gap-2">
                    @if(! $ticket->agente_asignado_id && Auth::user()->role === 'agente')
                    <button wire:click="tomarTicket({{ $ticket->id }})" class="px-3 py-1 bg-blue-100 dark:bg-blue-900/30 text-blue-700 dark:text-blue-400 font-bold text-[10px] rounded hover:bg-blue-200 transition-colors">
                        Tomar Ticket
                    </button>
                    @endif
                    <button wire:click="abrirModalEquipo({{ $ticket->id }})" class="px-3 py-1 bg-amber-100 dark:bg-amber-900/30 text-amber-700 dark:text-amber-400 font-bold text-[10px] rounded hover:bg-amber-200 transition-colors" title="Entregar Equipo">
                        Entregar
                    </button>
                    <select wire:change="changeStatus({{ $ticket->id }}, $event.target.value)" class="appearance-none pl-3 pr-6 py-1 text-[11px] font-bold bg-gray-50 dark:bg-gray-800 border-0 hover:bg-gray-100 dark:hover:bg-gray-700 dark:text-white rounded text-gray-600 focus:ring-0 cursor-pointer transition-colors align-middle focus:border-0 outline-none h-7">
                        @foreach($estados as $est)
                            <option value="{{ $est->id }}" {{ $ticket->estado_id == $est->id ? 'selected' : '' }}>
                                {{ $est->nombre }}
                            </option>
                        @endforeach
                    </select>
                </div>
                @endif
            </div>
            
        </div>
        @empty
        <div class="col-span-full">
            <div class="text-center py-20 bg-white dark:bg-gray-900 rounded-3xl border border-dashed border-gray-300 dark:border-gray-700 relative overflow-hidden flex flex-col items-center justify-center">
                
                <div class="w-20 h-20 bg-[#e8f0fe] dark:bg-[#0c4aed]/20 rounded-2xl flex items-center justify-center mb-6 shadow-sm">
                    <svg class="w-10 h-10 text-[#0c4aed] dark:text-[#3a75ff]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                    </svg>
                </div>
                <h3 class="text-xl font-black text-gray-900 dark:text-white mb-2">Bandeja Vacía</h3>
                <p class="text-sm text-gray-500 dark:text-gray-400 max-w-sm mx-auto font-medium">No se han registrado incidencias en el sistema recientemente.</p>
                
                @if(!$isCreating)
                <button wire:click="showCreateForm" class="mt-8 px-6 py-3 bg-white dark:bg-gray-800 text-[#0c4aed] dark:text-[#3a75ff] font-bold text-sm rounded-xl border border-gray-200 dark:border-gray-700 hover:border-[#0c4aed] transition-colors shadow-sm">
                    Iniciar un reporte
                </button>
                @endif
            </div>
        </div>
        @endforelse
    </div>

    @if($mostrarModalEquipo)
    <div class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/50 backdrop-blur-sm">
        <div class="bg-[#0b1225] border border-white/10 rounded-3xl p-7 max-w-md w-full shadow-[0_30px_80px_rgba(0,0,5,0.8)] backdrop-blur-xl">
            <h3 class="text-xl font-bold text-white mb-1">Entregar Equipo</h3>
            <p class="text-sm text-gray-500 mb-5 font-medium">Selecciona el equipo a entregar al usuario de este ticket.</p>
            
            <div class="mb-6">
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Equipo Disponible</label>
                <select wire:model="equipoSeleccionado" class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500/50 outline-none transition-colors">
                    <option value="">-- Selecciona equipo --</option>
                    @foreach($equiposDisponibles as $eq)
                    <option value="{{ $eq->id }}">{{ $eq->name }} (Stock: {{ $eq->stock }})</option>
                    @endforeach
                </select>
            </div>
            
            <div class="flex items-center justify-end gap-3">
                <button wire:click="cerrarModalEquipo" class="px-5 py-2.5 text-gray-500 hover:text-white font-bold text-sm transition-colors rounded-xl border border-white/10 hover:border-white/20">Cancelar</button>
                <button wire:click="entregarEquipo" class="px-5 py-2.5 bg-blue-600 text-white rounded-xl font-bold text-sm hover:bg-blue-500 transition-colors shadow-lg shadow-blue-600/30 hover:-translate-y-0.5" {{ empty($equipoSeleccionado) ? 'disabled' : '' }}>
                    Entregar y Descontar
                </button>
            </div>
        </div>
    </div>
    @endif
</div>
