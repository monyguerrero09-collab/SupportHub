<div class="w-full h-full flex flex-col min-h-0 relative select-none animate-in fade-in duration-500" 
     style="min-height: 500px;"
     x-data="{ uploadOpen: @entangle('showingUploadModal') }">

    {{-- Layout: Dos columnas en pantallas grandes --}}
    <div class="flex-1 flex flex-col lg:flex-row gap-6 min-h-0 min-w-0 w-full pb-6">
        
        {{-- COLUMNA IZQUIERDA: Listado, Buscador y Filtros --}}
        <div class="flex-1 min-w-0 flex flex-col gap-4">
            
            {{-- Barra superior de búsqueda y botón subir --}}
            <div class="flex flex-col sm:flex-row gap-3">
                <div class="flex-1 relative">
                    <input 
                        type="text" 
                        wire:model.live.debounce.300ms="search" 
                        placeholder="Buscar por nombre, ticket o serie..." 
                        class="w-full bg-[#131b2f]/80 border border-white/10 rounded-2xl pl-12 pr-6 py-3.5 text-xs text-white placeholder:text-gray-500 outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all shadow-inner"
                    />
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                </div>
                
                @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agente')
                <button 
                    wire:click="$set('showingUploadModal', true)"
                    class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3.5 rounded-2xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20 flex items-center justify-center gap-2 shrink-0 border border-blue-400"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/></svg>
                    Subir Manual / Guía
                </button>
                @endif
            </div>

            {{-- Filtros por Origen (Tickets, Inventario, General) --}}
            @if(auth()->user()->role !== 'user')
            <div class="flex gap-2 p-1.5 bg-[#080816]/60 border border-white/5 rounded-2xl overflow-x-auto shrink-0 custom-scrollbar">
                @php
                    $sources = [
                        ['id' => 'all', 'name' => 'Todos'],
                        ['id' => 'general', 'name' => 'Manuales TI'],
                        ['id' => 'tickets', 'name' => 'Evidencias Tickets'],
                        ['id' => 'inventory', 'name' => 'Responsivas Equipos'],
                    ];
                @endphp
                @foreach($sources as $src)
                <button 
                    wire:click="$set('filterSource', '{{ $src['id'] }}')" 
                    class="px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-widest whitespace-nowrap transition-all border {{ $filterSource === $src['id'] ? 'bg-blue-600/90 text-white border-blue-500/30 shadow-[0_0_15px_rgba(37,99,235,0.3)]' : 'bg-transparent text-gray-400 border-transparent hover:bg-white/5 hover:text-white' }}"
                >
                    {{ $src['name'] }}
                </button>
                @endforeach
            </div>
            @endif

            {{-- Filtros por Tipo de Archivo (PDF, Imagen, Word, Texto, Otros) --}}
            <div class="flex gap-2 overflow-x-auto pb-1 shrink-0 custom-scrollbar">
                @php
                    $types = [
                        ['id' => 'all', 'name' => 'Todos', 'icon' => '📁'],
                        ['id' => 'pdf', 'name' => 'PDF', 'icon' => '📄'],
                        ['id' => 'image', 'name' => 'Imágenes', 'icon' => '🖼️'],
                        ['id' => 'word', 'name' => 'Documentos', 'icon' => '📝'],
                        ['id' => 'text', 'name' => 'Consola/Texto', 'icon' => '💻'],
                        ['id' => 'other', 'name' => 'Otros', 'icon' => '📦'],
                    ];
                @endphp
                @foreach($types as $t)
                <button 
                    wire:click="$set('filterType', '{{ $t['id'] }}')" 
                    class="px-3 py-2 bg-white/[0.03] hover:bg-white/[0.06] border {{ $filterType === $t['id'] ? 'border-blue-500/30 bg-blue-500/5 text-blue-400' : 'border-white/5 text-gray-400' }} rounded-xl text-[9px] font-bold uppercase tracking-wider flex items-center gap-1.5 transition-all whitespace-nowrap"
                >
                    <span>{{ $t['icon'] }}</span>
                    <span>{{ $t['name'] }}</span>
                </button>
                @endforeach
            </div>

            {{-- Grid de Documentos --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar pr-1 max-h-[65vh]" style="min-height: 300px;">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @forelse($documents as $doc)
                    <div 
                        wire:click="selectDocument('{{ $doc['id'] }}')"
                        class="p-5 rounded-2xl border transition-all cursor-pointer flex gap-4 items-start relative overflow-hidden group hover:scale-[1.01] {{ $selectedDocId === $doc['id'] ? 'bg-blue-600/10 border-blue-500/40 shadow-[0_0_20px_rgba(37,99,235,0.15)]' : 'bg-[#121226]/40 border-white/5 hover:border-white/15' }}"
                    >
                        {{-- Icono de tipo de archivo --}}
                        <div class="w-12 h-12 rounded-xl flex items-center justify-center shrink-0 text-2xl transition-all group-hover:scale-110 {{ $selectedDocId === $doc['id'] ? 'bg-blue-600/20 border border-blue-500/30' : 'bg-white/5 border border-white/10' }}">
                            @if($doc['tipo'] === 'pdf')
                                📄
                            @elseif($doc['tipo'] === 'image')
                                🖼️
                            @elseif($doc['tipo'] === 'word')
                                📝
                            @elseif($doc['tipo'] === 'text')
                                💻
                            @else
                                📦
                            @endif
                        </div>

                        {{-- Datos del archivo --}}
                        <div class="flex-1 min-w-0">
                            <h4 class="font-black text-white text-xs truncate group-hover:text-blue-400 transition-colors uppercase tracking-tight" title="{{ $doc['nombre'] }}">
                                {{ $doc['nombre'] }}
                            </h4>
                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-1 truncate">
                                Asociado: <span class="text-gray-300 font-medium">{{ $doc['entidad_asociada'] }}</span>
                            </p>
                            <div class="flex items-center gap-3 mt-3.5 text-[8px] font-bold text-gray-600 uppercase tracking-wider">
                                <span class="truncate">Por: {{ $doc['autor'] }}</span>
                                <span class="shrink-0">•</span>
                                <span class="shrink-0">{{ $doc['fecha']->format('d/m/Y H:i') }}</span>
                            </div>
                        </div>

                        {{-- Origen label --}}
                        <div class="absolute top-4 right-4 text-[7px] font-black uppercase tracking-[0.15em] px-2 py-0.5 rounded border 
                            {{ $doc['origen'] === 'general' ? 'bg-teal-500/10 text-teal-400 border-teal-500/20' : 
                               ($doc['origen'] === 'tickets' ? 'bg-purple-500/10 text-purple-400 border-purple-500/20' : 
                               'bg-blue-500/10 text-blue-400 border-blue-500/20') }}">
                            {{ $doc['origen'] }}
                        </div>
                    </div>
                    @empty
                    <div class="col-span-2 py-20 text-center flex flex-col items-center justify-center border border-white/5 border-dashed rounded-3xl bg-[#121226]/20">
                        <div class="text-4xl mb-4 text-gray-600">📂</div>
                        <h4 class="font-black text-white text-xs uppercase tracking-widest">No se encontraron archivos</h4>
                        <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1.5">Intenta buscar con otros filtros</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>

        {{-- COLUMNA DERECHA: Visualizador Interactivo --}}
        <div class="w-full lg:w-[450px] xl:w-[500px] shrink-0 flex flex-col">
            
            <div class="flex-1 bg-[#0b1221]/90 backdrop-blur-xl rounded-[2rem] border border-white/5 flex flex-col overflow-hidden min-h-[500px] max-h-[75vh] shadow-[0_20px_50px_rgba(0,0,0,0.8)] relative">
                
                @if($selectedDoc)
                    
                    {{-- Header del visor --}}
                    <div class="p-6 border-b border-white/5 bg-white/5 flex justify-between items-center sticky top-0 z-10 shrink-0">
                        <div class="min-w-0">
                            <h3 class="text-sm font-black text-white uppercase tracking-tight truncate pr-6" title="{{ $selectedDoc['nombre'] }}">
                                {{ $selectedDoc['nombre'] }}
                            </h3>
                            <p class="text-[9px] text-blue-400 font-bold uppercase tracking-widest mt-0.5">
                                {{ $selectedDoc['origen'] }} • {{ strtoupper($selectedDoc['ext']) }}
                            </p>
                        </div>
                        
                        <div class="flex gap-1.5 items-center shrink-0">
                            <button 
                                wire:click="downloadDocument('{{ $selectedDoc['id'] }}')" 
                                class="w-10 h-10 bg-white/5 hover:bg-white/10 rounded-xl flex items-center justify-center border border-white/5 hover:border-white/20 text-white transition-all"
                                title="Descargar archivo"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/></svg>
                            </button>
                            
                            @if(auth()->user()->role === 'admin')
                            <button 
                                wire:click="deleteDocument('{{ $selectedDoc['id'] }}')" 
                                wire:confirm="¿Estás completamente seguro de eliminar permanentemente este documento del servidor?"
                                class="w-10 h-10 bg-red-600/10 hover:bg-red-600 border border-red-500/20 text-red-400 hover:text-white rounded-xl flex items-center justify-center transition-all"
                                title="Eliminar archivo"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                            </button>
                            @endif

                            <button 
                                wire:click="closeViewer" 
                                class="w-10 h-10 bg-white/5 hover:bg-white/10 border border-white/5 hover:border-white/20 rounded-xl flex items-center justify-center text-gray-400 hover:text-white transition-all ml-1.5"
                                title="Cerrar visor"
                            >
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                            </button>
                        </div>
                    </div>

                    @if(!empty($selectedDoc['descripcion']))
                    <div class="px-6 py-4 bg-blue-600/5 border-b border-white/5 text-xs text-slate-300 font-medium italic shrink-0">
                        <span class="text-[9px] font-black text-blue-400 uppercase tracking-widest block not-italic mb-1.5">Descripción / Propósito:</span>
                        "{{ $selectedDoc['descripcion'] }}"
                    </div>
                    @endif

                    {{-- Contenido del Visor --}}
                    <div class="flex-1 overflow-y-auto p-6 flex flex-col justify-center min-h-0">
                        
                        @if($selectedDoc['tipo'] === 'pdf')
                            {{-- Visualizador de PDF --}}
                            <div class="w-full h-full flex flex-col flex-1 rounded-2xl overflow-hidden border border-white/10 bg-black/40">
                                <iframe 
                                    src="{{ asset('storage/' . $selectedDoc['ruta_real']) }}#toolbar=0" 
                                    class="w-full h-full min-h-[420px]" 
                                    style="border: none;"
                                ></iframe>
                            </div>
                            
                        @elseif($selectedDoc['tipo'] === 'image')
                            {{-- Visualizador de Imagen con Controles Alpine --}}
                            <div x-data="{ zoom: 1, rotate: 0 }" class="relative flex-1 flex flex-col items-center justify-center bg-black/30 rounded-2xl border border-white/5 overflow-hidden min-h-[380px]">
                                {{-- Controles de Imagen --}}
                                <div class="absolute top-4 right-4 flex gap-1.5 z-20">
                                    <button @click="zoom = Math.min(zoom + 0.25, 4)" class="w-8 h-8 bg-black/60 hover:bg-blue-600 border border-white/10 hover:border-blue-500 rounded-lg flex items-center justify-center text-white font-black transition-all" title="Acercar">+</button>
                                    <button @click="zoom = Math.max(zoom - 0.25, 0.5)" class="w-8 h-8 bg-black/60 hover:bg-blue-600 border border-white/10 hover:border-blue-500 rounded-lg flex items-center justify-center text-white font-black transition-all" title="Alejar">-</button>
                                    <button @click="rotate = (rotate + 90) % 360" class="w-8 h-8 bg-black/60 hover:bg-blue-600 border border-white/10 hover:border-blue-500 rounded-lg flex items-center justify-center text-white transition-all" title="Rotar 90º">🔄</button>
                                    <button @click="zoom = 1; rotate = 0" class="w-8 h-8 bg-black/60 hover:bg-blue-600 border border-white/10 hover:border-blue-500 rounded-lg flex items-center justify-center text-white text-[8px] font-black uppercase tracking-wider transition-all" title="Restablecer">Rst</button>
                                </div>
                                
                                {{-- Contenedor de Imagen --}}
                                <div class="w-full h-full flex items-center justify-center p-6 overflow-auto">
                                    <img 
                                        src="{{ asset('storage/' . $selectedDoc['ruta_real']) }}" 
                                        :style="'transform: scale(' + zoom + ') rotate(' + rotate + 'deg); transition: transform 0.2s ease;'" 
                                        class="max-w-full max-h-[400px] object-contain rounded-lg shadow-2xl" 
                                    />
                                </div>
                            </div>
                            
                        @elseif($selectedDoc['tipo'] === 'text')
                            {{-- Visualizador de Archivo de Texto / Logs en Consola --}}
                            <div class="flex-1 flex flex-col min-h-0 bg-black/80 rounded-2xl border border-white/10 overflow-hidden font-mono shadow-inner">
                                <div class="px-4 py-2 border-b border-white/5 bg-black/40 flex justify-between items-center shrink-0">
                                    <span class="text-[8px] font-black text-gray-500 uppercase tracking-widest">Consola Terminal de Texto</span>
                                    <div class="flex gap-1">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500/60"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-500/60"></span>
                                        <span class="w-1.5 h-1.5 rounded-full bg-green-500/60"></span>
                                    </div>
                                </div>
                                <pre class="flex-1 p-5 overflow-auto text-emerald-400 text-[10px] leading-relaxed custom-scrollbar selection:bg-emerald-500/30 selection:text-white" style="margin: 0;"><code>{{ $textContent }}</code></pre>
                            </div>
                            
                        @else
                            {{-- Visualización No Soportada: Mostrar Ficha Técnica y Descargar --}}
                            <div class="p-8 text-center flex flex-col items-center justify-center bg-black/10 border border-white/5 rounded-2xl flex-1">
                                <div class="w-20 h-20 rounded-3xl bg-blue-600/10 border border-blue-500/20 text-blue-400 flex items-center justify-center text-4xl mb-6 shadow-[0_0_20px_rgba(37,99,235,0.1)]">
                                    @if($selectedDoc['tipo'] === 'word')
                                        📝
                                    @else
                                        📦
                                    @endif
                                </div>
                                <h4 class="font-black text-white text-base uppercase tracking-tighter mb-2">{{ $selectedDoc['nombre'] }}</h4>
                                <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mb-6">Ficha de Información</p>
                                
                                <div class="w-full space-y-3 bg-[#111124]/50 border border-white/5 p-4 rounded-xl text-left text-xs mb-8">
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Asociación:</span>
                                        <span class="text-white font-bold">{{ $selectedDoc['entidad_asociada'] }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Tamaño:</span>
                                        <span class="text-white font-bold">{{ $selectedDoc['tamaño'] ? number_format($selectedDoc['tamaño'] / 1024, 1) . ' KB' : 'Desconocido' }}</span>
                                    </div>
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Subido por:</span>
                                        <span class="text-white font-bold">{{ $selectedDoc['autor'] }}</span>
                                    </div>
                                    @if(!empty($selectedDoc['licencia']))
                                    <div class="flex justify-between">
                                        <span class="text-gray-500">Licencia:</span>
                                        <span class="text-white font-bold capitalize">{{ str_replace('-', ' ', $selectedDoc['licencia']) }}</span>
                                    </div>
                                    @endif
                                </div>
                                
                                <p class="text-[10px] text-gray-500 uppercase tracking-wider mb-6">
                                    Este tipo de archivo ({{ strtoupper($selectedDoc['ext']) }}) no admite previsualización nativa.
                                </p>
                                
                                <button 
                                    wire:click="downloadDocument('{{ $selectedDoc['id'] }}')" 
                                    class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20 border border-blue-400"
                                >
                                    Descargar para Visualizar
                                </button>
                            </div>
                        @endif

                    </div>

                @else
                    
                    {{-- Estado inicial / Splash screen --}}
                    <div class="flex-1 flex flex-col items-center justify-center p-8 text-center animate-pulse">
                        <div class="w-24 h-24 bg-white/[0.02] border border-white/5 text-gray-600 rounded-[2rem] flex items-center justify-center text-4xl mb-6 shadow-inner relative">
                            <div class="absolute inset-0 bg-blue-500/5 rounded-[2rem] blur-xl"></div>
                            🔍
                        </div>
                        <h4 class="font-black text-white text-xs uppercase tracking-[0.2em]">Visualización de Archivos</h4>
                        <p class="text-[9px] text-gray-600 font-bold uppercase tracking-[0.15em] mt-2.5 max-w-[250px] leading-relaxed">
                            Selecciona una responsiva, guía o adjunto de la lista para previsualizar aquí en tiempo real
                        </p>
                    </div>

                @endif

            </div>

        </div>

    </div>

    {{-- MODAL: SUBIR DOCUMENTO GENERAL TI (Selector de archivos Moodle-style - Dark Space Theme) --}}
    @if($showingUploadModal)
    <div class="fixed inset-0 z-[200] flex items-center justify-center p-4 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-4xl bg-[#0a0a1a]/95 text-gray-200 rounded-[2rem] shadow-[0_20px_50px_rgba(0,0,0,0.8)] border border-blue-500/20 overflow-hidden flex flex-col max-h-[85vh]">
            
            {{-- Header (Selector de archivos) --}}
            <div class="px-8 py-5 border-b border-white/5 bg-white/5 flex justify-between items-center shrink-0">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-lg bg-blue-500/10 border border-blue-500/20 flex items-center justify-center text-xs">📂</div>
                    <h3 class="text-xs font-black text-white uppercase tracking-wider">Selector de archivos</h3>
                </div>
                <button wire:click="$set('showingUploadModal', false)" class="text-gray-400 hover:text-white transition-all">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6" /></svg>
                </button>
            </div>

            {{-- Main Layout (Sidebar + Content) --}}
            <div class="flex-1 flex min-h-0 divide-x divide-white/5">
                
                {{-- Left Sidebar --}}
                <div class="w-56 bg-[#080816]/60 p-4 space-y-1 shrink-0 overflow-y-auto hidden md:block">
                    <div class="flex items-center gap-2.5 px-3.5 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 rounded-xl hover:bg-white/5 hover:text-white cursor-pointer transition-all">
                        <span>📁</span>
                        <span>Archivos recientes</span>
                    </div>
                    <div class="flex items-center gap-2.5 px-3.5 py-3 text-[10px] font-black uppercase tracking-widest text-white bg-blue-600/90 border border-blue-500/30 rounded-xl cursor-pointer transition-all shadow-[0_0_15px_rgba(37,99,235,0.3)]">
                        <span>📤</span>
                        <span>Subir un archivo</span>
                    </div>
                    <div class="flex items-center gap-2.5 px-3.5 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 rounded-xl hover:bg-white/5 hover:text-white cursor-pointer transition-all">
                        <span>🔒</span>
                        <span>Archivos privados</span>
                    </div>
                    <div class="flex items-center gap-2.5 px-3.5 py-3 text-[10px] font-black uppercase tracking-widest text-gray-400 rounded-xl hover:bg-white/5 hover:text-white cursor-pointer transition-all">
                        <span>🌐</span>
                        <span>Wikimedia</span>
                    </div>
                </div>

                {{-- Right Content Panel --}}
                <div class="flex-1 p-8 overflow-y-auto bg-transparent min-w-0 custom-scrollbar">
                    <form wire:submit.prevent="uploadGeneralFile" class="space-y-6">
                        
                        {{-- Adjunto file input row --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Adjunto</label>
                            <div class="flex items-center gap-4">
                                <label class="cursor-pointer bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl px-5 py-3 text-[9px] font-black uppercase tracking-wider text-white transition-all shrink-0">
                                    Elegir archivo
                                    <input type="file" wire:model="generalFile" required class="hidden" />
                                </label>
                                <span class="text-xs text-gray-400 truncate font-semibold">
                                    @if($generalFile)
                                        {{ $generalFile->getClientOriginalName() }}
                                    @else
                                        No se eligió ningún archivo
                                    @endif
                                </span>
                            </div>
                            @error('generalFile') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Guardar como --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Guardar como</label>
                            <input 
                                type="text" 
                                wire:model="generalFileName" 
                                placeholder="Ej. Guía de Conexión VPN" 
                                required 
                                class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all placeholder:text-gray-600"
                            />
                            @error('generalFileName') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Autor --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Autor</label>
                            <input 
                                type="text" 
                                wire:model="generalAuthor" 
                                required 
                                class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all"
                            />
                            @error('generalAuthor') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Seleccionar Licencia --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Seleccionar licencia</label>
                            <select 
                                wire:model="generalLicense"
                                class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all appearance-none"
                            >
                                <option value="no-especificada" class="bg-[#131b2f]">Licencia no especificada</option>
                                <option value="dominio-publico" class="bg-[#131b2f]">Dominio público</option>
                                <option value="creative-commons" class="bg-[#131b2f]">Creative Commons (CC BY)</option>
                                <option value="todos-derechos-reservados" class="bg-[#131b2f]">Todos los derechos reservados</option>
                            </select>
                            @error('generalLicense') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Categoría del Documento --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Categoría</label>
                            <select 
                                wire:model="generalCategory" 
                                class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all appearance-none"
                            >
                                <option value="Manual" class="bg-[#131b2f]">Manual de Sistema</option>
                                <option value="Guía" class="bg-[#131b2f]">Guía Rápida TI</option>
                                <option value="Política" class="bg-[#131b2f]">Política de Seguridad</option>
                                <option value="General" class="bg-[#131b2f]">Documento General</option>
                            </select>
                            @error('generalCategory') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Metadata fields for Admin/Agent (Device and Area) --}}
                        @if(auth()->user()->role === 'admin' || auth()->user()->role === 'agente')
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            {{-- Dispositivo Asociado --}}
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Dispositivo / Equipo (Opcional)</label>
                                <select 
                                    wire:model="generalEquipmentId" 
                                    class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all appearance-none"
                                >
                                    <option value="" class="bg-[#131b2f]">-- Ninguno --</option>
                                    @foreach($equipments as $item)
                                        <option value="{{ $item->id }}" class="bg-[#131b2f]">{{ $item->name }} ({{ $item->barcode ?? 'S/N' }})</option>
                                    @endforeach
                                </select>
                                @error('generalEquipmentId') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                            </div>

                            {{-- Área Asociada --}}
                            <div class="space-y-2">
                                <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Área de TI (Opcional)</label>
                                <select 
                                    wire:model="generalArea" 
                                    class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all appearance-none"
                                >
                                    <option value="" class="bg-[#131b2f]">-- Ninguna --</option>
                                    @foreach($areasList as $areaItem)
                                        <option value="{{ $areaItem['id'] }}" class="bg-[#131b2f]">{{ $areaItem['name'] }}</option>
                                    @endforeach
                                </select>
                                @error('generalArea') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        @endif

                        {{-- Descripción del Manual --}}
                        <div class="space-y-2">
                            <label class="block text-[10px] font-black text-blue-500 uppercase tracking-widest ml-2">Descripción / Propósito</label>
                            <textarea 
                                wire:model="generalDescription" 
                                placeholder="Describe para qué sirve este manual, si es para un dispositivo nuevo, etc..." 
                                rows="3" 
                                class="w-full bg-[#131b2f] border border-white/10 rounded-xl px-4 py-3.5 text-xs text-white outline-none focus:border-blue-500 focus:bg-[#162035]/90 transition-all placeholder:text-gray-600 resize-none"
                            ></textarea>
                            @error('generalDescription') <span class="text-xs text-red-500 mt-1 font-bold ml-2 block">{{ $message }}</span> @enderror
                        </div>

                        {{-- Submit Button --}}
                        <div class="pt-4 flex justify-center">
                            <button 
                                type="submit" 
                                class="px-10 py-4 bg-blue-600 hover:bg-blue-500 text-white rounded-2xl text-[11px] font-black uppercase tracking-widest transition-all shadow-lg shadow-blue-500/20 border border-blue-400"
                            >
                                Subir este archivo
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

</div>
