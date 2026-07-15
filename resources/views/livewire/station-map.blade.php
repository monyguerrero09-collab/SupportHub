<div class="w-full h-full pb-10" x-data="{ selectedStation: @entangle('selectedStationId') }">
    <div class="max-w-[95%] lg:max-w-7xl mx-auto h-[calc(100vh-8rem)] min-h-[650px] animate-in fade-in slide-in-from-bottom-4 duration-500">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 h-full">
            
            {{-- IZQUIERDA --}}
            <div class="lg:col-span-8 flex flex-col gap-8 overflow-y-auto pr-2 pb-10">

                {{-- LAYOUT REAL --}}
                <div class="bg-[#0a0a1a]/80 backdrop-blur-3xl p-8 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5">
                    
                    {{-- HEADER WITH TABS --}}
                    <div class="flex flex-col md:flex-row md:items-center justify-between mb-8 gap-4">
                        <h3 class="text-2xl font-black flex items-center gap-4 text-white uppercase tracking-tighter">
                            <svg class="w-7 h-7 text-blue-500 drop-shadow-[0_0_8px_rgba(59,130,246,0.8)]" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M6 22V4a2 2 0 0 1 2-2h8a2 2 0 0 1 2 2v18Z"/> <path d="M6 12H4a2 2 0 0 0-2 2v6a2 2 0 0 0 2 2h2"/> <path d="M18 9h2a2 2 0 0 1 2 2v9a2 2 0 0 1-2 2h-2"/> <path d="M10 6h4"/> <path d="M10 10h4"/> <path d="M10 14h4"/> <path d="M10 18h4"/>
                            </svg>
                            Layout Corporativo
                        </h3>
                        
                        {{-- Tabs --}}
                        <div class="flex items-center gap-2 bg-[#050510] p-1.5 rounded-2xl border border-white/10">
                            <button wire:click="switchPlant(1)" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activePlant == 1 ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.5)]' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                                Planta 1
                            </button>
                            <button wire:click="switchPlant(2)" class="px-6 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $activePlant == 2 ? 'bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.5)]' : 'text-gray-500 hover:text-white hover:bg-white/5' }}">
                                Planta 2
                            </button>
                        </div>
                    </div>

                    <div class="relative rounded-3xl overflow-hidden border border-white/10 bg-[#050510] shadow-inner flex items-center justify-center p-4">

                        {{-- IMAGEN PLANTA ACTIVA --}}
                        @if($activePlant == 1)
                            <img
                                src="{{ asset('img/layout_cgr.png') }}?v={{ time() }}"
                                alt="Layout CGR Planta 1"
                                class="w-full h-auto opacity-90 transition-opacity"
                                style="filter: invert(1) brightness(1.5);"
                            />
                        @else
                            <img
                                src="{{ asset('img/CGR-PLANT 2-Model Sin cotas.svg') }}?v={{ time() }}"
                                alt="Layout CGR Planta 2"
                                class="w-full h-auto opacity-90 transition-opacity"
                                style="filter: invert(1) brightness(1.5);"
                            />
                        @endif

                        {{-- ZONAS CLICKABLES --}}
                        @foreach($stations->where('planta', $activePlant) as $index => $station)
                            @php
                                $isActive = $selectedStationId == $station['db_id'];
                                // Distribuimos las posiciones independientemente de si es planta 1 o 2 (solo visual)
                                $positions = [
                                    ['top' => '17.1%', 'left' => '31.5%'],
                                    ['top' => '18.0%', 'left' => '51.9%'],
                                    ['top' => '20.9%', 'left' => '51.9%'],
                                    ['top' => '21.2%', 'left' => '37.4%'],
                                    ['top' => '29.2%', 'left' => '89.5%'],
                                    ['top' => '30.4%', 'left' => '74.5%'],
                                    ['top' => '32.4%', 'left' => '37.8%'],
                                    ['top' => '38.0%', 'left' => '51.8%'],
                                    ['top' => '38.0%', 'left' => '72.2%'],
                                    ['top' => '44.1%', 'left' => '81.6%'],
                                    ['top' => '46.8%', 'left' => '72.0%'],
                                    ['top' => '47.0%', 'left' => '31.7%'],
                                    ['top' => '55.3%', 'left' => '71.8%'],
                                    ['top' => '55.4%', 'left' => '29.5%'],
                                    ['top' => '55.4%', 'left' => '52.0%'],
                                    ['top' => '64.4%', 'left' => '51.4%'],
                                    ['top' => '66.4%', 'left' => '72.8%'],
                                    ['top' => '66.7%', 'left' => '81.1%'],
                                    ['top' => '69.4%', 'left' => '24.7%'],
                                    ['top' => '79.5%', 'left' => '29.2%'],
                                    ['top' => '80.3%', 'left' => '72.6%'],
                                    ['top' => '80.3%', 'left' => '85.7%'],
                                    ['top' => '80.4%', 'left' => '62.1%']
                                ];
                                $posIndex = ($station['db_id'] - 1) % count($positions);
                                $pos = $positions[$posIndex];
                                
                                $top = $station['pos_y'] ?? $pos['top'];
                                $left = $station['pos_x'] ?? $pos['left'];
                                $canDrag = in_array(auth()->user()->role, ['admin', 'agente']);
                            @endphp
                            
                            <div
                                wire:click="setSelectedStation('{{ $station['db_id'] }}')"
                                @if($canDrag)
                                x-data="{
                                    dragging: false,
                                    startX: 0, startY: 0,
                                    elTop: parseFloat('{{ $top }}'),
                                    elLeft: parseFloat('{{ $left }}'),
                                    startDrag(e) {
                                        if(e.target.closest('.delete-btn')) return;
                                        this.dragging = true;
                                        this.startX = e.touches ? e.touches[0].clientX : e.clientX;
                                        this.startY = e.touches ? e.touches[0].clientY : e.clientY;
                                    },
                                    doDrag(e) {
                                        if(!this.dragging) return;
                                        let currentX = e.touches ? e.touches[0].clientX : e.clientX;
                                        let currentY = e.touches ? e.touches[0].clientY : e.clientY;
                                        let dx = currentX - this.startX;
                                        let dy = currentY - this.startY;
                                        
                                        let parent = $el.parentElement.getBoundingClientRect();
                                        this.elLeft += (dx / parent.width) * 100;
                                        this.elTop += (dy / parent.height) * 100;
                                        
                                        $el.style.left = this.elLeft + '%';
                                        $el.style.top = this.elTop + '%';
                                        
                                        this.startX = currentX;
                                        this.startY = currentY;
                                    },
                                    stopDrag() {
                                        if(!this.dragging) return;
                                        this.dragging = false;
                                        $wire.updatePosition('{{ $station['db_id'] }}', this.elLeft + '%', this.elTop + '%');
                                    }
                                }"
                                @mousedown.prevent="startDrag"
                                @window.mousemove="doDrag"
                                @window.mouseup="stopDrag"
                                @touchstart.prevent="startDrag"
                                @window.touchmove="doDrag"
                                @window.touchend="stopDrag"
                                style="position: absolute; top: {{ $top }}; left: {{ $left }}; transform: translate(-50%, -50%); cursor: move; touch-action: none; z-index: 5;"
                                @else
                                style="position: absolute; top: {{ $top }}; left: {{ $left }}; transform: translate(-50%, -50%); z-index: 5;"
                                @endif
                                class="group/marker relative"
                            >
                                {{-- Marcador principal --}}
                                <div class="w-8 h-8 md:w-10 md:h-10 rounded-full flex items-center justify-center transition-all border-2 shadow-[0_0_12px_rgba(220,38,38,0.4)]
                                {{ $isActive 
                                    ? 'bg-red-600 border-white scale-125 shadow-[0_0_25px_rgba(220,38,38,0.9)]' 
                                    : 'bg-[#050510]/80 backdrop-blur-md border-red-500/60 hover:scale-110 hover:bg-red-600/30' }}">
                                    <svg class="w-4 h-4 md:w-5 md:h-5 {{ $isActive ? 'text-white' : 'text-red-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M20 10c0 6-8 12-8 12s-8-6-8-12a8 8 0 0 1 16 0Z"/><circle cx="12" cy="10" r="3"/>
                                    </svg>
                                </div>

                                {{-- Tooltip nombre --}}
                                <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-black/80 text-white text-[9px] font-black uppercase tracking-widest rounded-lg whitespace-nowrap pointer-events-none opacity-0 group-hover/marker:opacity-100 transition-opacity">
                                    {{ $station['name'] }}
                                </div>

                                @if($canDrag)
                                {{-- Botón eliminar (solo admin/agente) --}}
                                <button
                                    wire:click.stop="deleteStation('{{ $station['db_id'] }}')"
                                    wire:confirm="¿Ocultar este marcador del mapa?"
                                    class="delete-btn absolute -top-2 -right-2 w-5 h-5 bg-red-600 hover:bg-red-500 rounded-full flex items-center justify-center opacity-0 group-hover/marker:opacity-100 transition-all shadow-lg border border-red-400 z-10"
                                    title="Eliminar marcador"
                                >
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6"/></svg>
                                </button>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    {{-- GEOMETRÍA --}}
                    <div class="mt-10 flex justify-center">
                        <div class="w-[300px] h-[300px] bg-[#050510] border border-white/5 rounded-[2rem] flex items-center justify-center shadow-[inset_0_0_30px_rgba(0,0,0,0.8)] relative overflow-hidden group">
                            
                            {{-- Ejética visual --}}
                            <div class="absolute inset-0 bg-blue-500/5 opacity-0 group-hover:opacity-100 transition-opacity"></div>
                            <span class="absolute top-4 left-4 text-[8px] font-black tracking-widest uppercase text-gray-600">Schematic View</span>

                            <svg viewBox="150 100 500 500" class="w-full h-full drop-shadow-[0_0_15px_rgba(59,130,246,0.3)]">
                                {{-- COLUMNA --}}
                                <rect x="370" y="295" width="60" height="8" rx="4" fill="#3b82f6" />
                                <rect x="380" y="300" width="40" height="270" fill="#3b82f6" />
                                <rect x="360" y="570" width="80" height="10" rx="4" fill="#3b82f6" />

                                {{-- MONITORES --}}
                                <rect x="247" y="170" width="147" height="87" rx="8" fill="#1e293b" stroke="#334155" stroke-width="4" />
                                <rect x="406" y="170" width="147" height="87" rx="8" fill="#1e293b" stroke="#334155" stroke-width="4" />

                                {{-- TECLADO --}}
                                <rect x="290" y="370" width="220" height="15" rx="4" fill="#0f172a" stroke="#1e293b" stroke-width="2" />

                                {{-- IMPRESORA --}}
                                <rect x="300" y="440" width="100" height="40" rx="4" fill="#e2e8f0" />

                                {{-- UPS --}}
                                <rect x="440" y="435" width="30" height="45" rx="4" fill="#64748b" />
                            </svg>
                        </div>
                    </div>

                </div>

                {{-- RESUMEN --}}
                <div class="bg-[#0a0a1a]/80 backdrop-blur-3xl p-8 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.5)] border border-white/5 shrink-0">
                    <h3 class="text-[11px] font-black text-blue-500 uppercase tracking-widest mb-6">
                        Estadísticas por Área
                    </h3>

                    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4">
                        @foreach($stations->where('planta', $activePlant) as $station)
                            @php
                                $itemsCount = count($station['equipment']);
                            @endphp
                            @if($itemsCount > 0)
                            <div class="p-5 bg-white/5 border border-white/5 rounded-2xl flex flex-col items-center justify-center text-center transition-all hover:bg-white/10">
                                <p class="font-black text-white text-lg uppercase tracking-tighter">{{ $station['name'] }}</p>
                                <p class="text-[10px] font-bold text-gray-400 mt-1 uppercase tracking-widest">{{ $itemsCount }} equipos</p>
                            </div>
                            @endif
                        @endforeach
                    </div>
                </div>

            </div>

            {{-- DERECHA --}}
            <div class="lg:col-span-4 h-full pb-10">
                @if($selectedStation)
                <div class="bg-[#0a0a1a]/80 backdrop-blur-3xl rounded-[2rem] border border-white/5 shadow-[0_20px_50px_rgba(0,0,0,0.5)] h-full flex flex-col overflow-hidden animate-in zoom-in-95 duration-300">

                    {{-- Cabecera Card --}}
                    <div class="bg-[#050510] border-b border-white/10 p-8 relative flex-shrink-0">
                        <h3 class="font-black text-3xl text-white uppercase tracking-tighter mb-2">{{ $selectedStation['name'] }}</h3>
                        <p class="text-[9px] font-bold text-blue-500 uppercase tracking-[0.3em]">
                            ID ESTACIÓN: {{ $selectedStation['id'] }}
                        </p>
                        <button wire:click="setSelectedStation(null)" class="absolute top-8 right-8 text-white/20 hover:text-white transition-colors">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="3">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6"/>
                            </svg>
                        </button>
                    </div>

                    {{-- Scroll Area para Inventario --}}
                    <div class="p-8 flex-1 overflow-y-auto space-y-4">
                        <div class="flex items-center justify-between mb-6 border-b border-white/5 pb-2">
                            <h4 class="text-[10px] font-black text-gray-600 uppercase tracking-widest">Activos Vinculados</h4>
                            @if(in_array(auth()->user()->role ?? '', ['admin', 'agente']))
                            <button wire:click="openAssignModal" class="px-3.5 py-1.5 bg-blue-600/20 hover:bg-blue-600 text-blue-400 hover:text-white rounded-xl border border-blue-500/20 transition-all font-black text-[9px] uppercase tracking-wider flex items-center gap-1.5 shadow-lg">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                                Vincular
                            </button>
                            @endif
                        </div>
                        @forelse($selectedStation['equipment'] as $item)
                        <div class="p-5 bg-white/[0.03] border border-white/5 rounded-2xl group hover:bg-white/10 transition-all flex items-center justify-between gap-4">
                            <div class="min-w-0 flex-1">
                                <p class="font-black text-white text-sm uppercase tracking-tight truncate">{{ $item->name ?? 'Equipo #'.$item->id }}</p>
                                <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-1 truncate">
                                    {{ $item->model ?? 'N/A' }} | S/N: {{ $item->barcode ?? $item->serial_number ?? 'N/A' }}
                                </p>
                            </div>
                            @if(in_array(auth()->user()->role ?? '', ['admin', 'agente']))
                            <button wire:click="unlinkEquipment({{ $item->id }})" wire:confirm="¿Desvincular este equipo de la estación?" class="px-2.5 py-1.5 bg-red-500/10 hover:bg-red-500 text-red-500 hover:text-white rounded-xl border border-red-500/20 hover:border-red-500 transition-all shrink-0" title="Desvincular equipo">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                            @endif
                        </div>
                        @empty
                        <div class="p-8 text-center bg-white/5 border border-white/5 rounded-2xl">
                            <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">Sin asignar</p>
                        </div>
                        @endforelse
                    </div>

                    {{-- Botón Inferior --}}
                    <div class="p-8 border-t border-white/10 bg-[#050510]/50 flex-shrink-0">
                        <button wire:click="$dispatch('openNewTicket', { stationId: {{ $selectedStation['db_id'] }} })" class="w-full py-5 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl text-[11px] font-black uppercase tracking-[.4em] shadow-[0_0_30px_rgba(37,99,235,0.4)] transition-all flex items-center justify-center gap-3">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                                <path d="M12 4v16m8-8H4"/>
                            </svg>
                            Generar Reporte
                        </button>
                    </div>

                </div>
                @else
                <div class="bg-[#0a0a1a]/40 backdrop-blur-3xl rounded-[2rem] border border-white/5 border-dashed h-full flex flex-col items-center justify-center text-center p-12">
                    <svg class="w-16 h-16 text-white/10 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="1.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 15l-2 5L9 9l11 4-5 2zm0 0l5 5M7.188 2.239l.777 2.897M5.136 7.965l-2.898-.777M13.95 4.05l-2.122 2.122m-5.657 5.656l-2.12 2.122"/>
                    </svg>
                    <h3 class="font-black text-xl text-white uppercase tracking-tighter mb-2">Panel de Inspección</h3>
                    <p class="text-[10px] font-bold text-gray-500 uppercase tracking-widest leading-relaxed">Selecciona un marcador interactivo en el plano para examinar sus equipos en tiempo real.</p>
                </div>
                @endif
            </div>

    {{-- MODAL: VINCULAR EQUIPOS A LA ESTACIÓN --}}
    @if($showingAssignModal && $selectedStation)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-md bg-[#0a0a1a] rounded-[2rem] border border-blue-500/20 shadow-4xl overflow-hidden max-h-[85vh] flex flex-col">
            
            {{-- Header --}}
            <div class="p-6 border-b border-white/5 flex justify-between items-center bg-[#0a0a1a] z-10 shrink-0">
                <div>
                    <h3 class="text-lg font-black text-white uppercase tracking-tighter">Vincular Equipos</h3>
                    <p class="text-[9px] text-blue-500 uppercase font-bold tracking-widest mt-0.5">{{ $selectedStation['name'] }}</p>
                </div>
                <button wire:click="$set('showingAssignModal', false)" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>

            {{-- Buscador --}}
            <div class="p-5 border-b border-white/5 shrink-0">
                <div class="relative w-full">
                    <input type="text" wire:model.live="searchEquipment" placeholder="Buscar equipo por nombre o modelo..." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 pl-11 text-xs text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600 uppercase tracking-wider font-bold">
                    <svg class="w-5 h-5 text-gray-600 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                </div>
            </div>

            {{-- Lista de Equipos disponibles en Bodega --}}
            <div class="p-5 overflow-y-auto flex-1 space-y-3 custom-scrollbar">
                <h4 class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-4">Equipos en Bodega</h4>
                @forelse($availableEquipment as $item)
                <div class="p-4 bg-white/[0.02] border border-white/5 rounded-xl hover:bg-white/[0.05] transition-all flex items-center justify-between gap-4">
                    <div class="min-w-0 flex-1">
                        <p class="font-black text-white text-xs uppercase tracking-tight truncate">{{ $item->name }}</p>
                        <p class="text-[9px] font-bold text-gray-500 uppercase tracking-widest mt-0.5 truncate">
                            {{ $item->model ?? 'N/A' }} | S/N: {{ $item->barcode ?? $item->serial_number ?? 'N/A' }}
                        </p>
                    </div>
                    <button wire:click="assignEquipment({{ $item->id }})" class="px-3 py-1.5 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[9px] font-black uppercase tracking-wider transition-all shrink-0">
                        Vincular
                    </button>
                </div>
                @empty
                <div class="text-center py-10 bg-white/[0.01] border border-white/5 border-dashed rounded-2xl">
                    <p class="text-xs font-bold text-gray-500 uppercase tracking-widest">No hay equipos disponibles</p>
                    <p class="text-[9px] text-gray-600 uppercase font-black tracking-widest mt-2">Registra nuevos activos en Inventario</p>
                </div>
                @endforelse
            </div>

            {{-- Footer --}}
            <div class="p-5 border-t border-white/5 bg-[#050510]/50 shrink-0">
                <button wire:click="$set('showingAssignModal', false)" class="w-full py-3 bg-white/5 hover:bg-white/10 text-gray-400 hover:text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                    Cerrar
                </button>
            </div>

        </div>
    </div>
    @endif
        </div>
    </div>
</div>
