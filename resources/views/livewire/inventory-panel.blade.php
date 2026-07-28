<div class="space-y-8 animate-in fade-in slide-in-from-bottom-5 duration-700">
    
    {{-- Header with Title and Search --}}
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 pb-4 border-b border-white/5">
        <div>
            <h3 class="text-3xl font-black text-white tracking-tighter uppercase">Inventario de Bodega y Control</h3>
            <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Sistemas de stock unificado en tiempo real</p>
        </div>
        
        {{-- Search & Plant Filter --}}
        <div class="flex flex-col md:flex-row items-center gap-3 w-full md:w-auto">
            <div class="flex bg-[#101026]/80 border border-white/10 rounded-2xl p-1 shadow-inner">
                <button wire:click="$set('globalPlantFilter', 'Todas')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $globalPlantFilter === 'Todas' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Todas</button>
                <button wire:click="$set('globalPlantFilter', 'Planta 1')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $globalPlantFilter === 'Planta 1' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Planta 1</button>
                <button wire:click="$set('globalPlantFilter', 'Planta 2')" class="px-4 py-2 rounded-xl text-xs font-black uppercase tracking-widest transition-all {{ $globalPlantFilter === 'Planta 2' ? 'bg-indigo-600 text-white shadow-md' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">Planta 2</button>
            </div>
            <div class="relative w-full md:w-72">
                <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-gray-500">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                </span>
                <input
                    type="text"
                    placeholder="Buscar en inventario..."
                    wire:model.live.debounce.250ms="searchTerm"
                    class="w-full pl-10 pr-4 py-3 bg-[#0b0b1e]/90 border border-white/10 rounded-2xl focus:outline-none focus:border-blue-500/50 focus:ring-0 text-xs text-white placeholder:text-gray-600 transition-all font-semibold"
                />
            </div>
        </div>
    </div>

    {{-- Top Summary Stats Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
        {{-- Stat 1: Stock en Bodega --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2rem] p-6 flex items-center gap-5 shadow-[0_20px_50px_rgba(0,0,0,0.3)] group hover:border-blue-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Stock en Bodega</p>
                <p class="text-2xl font-black text-white leading-none tracking-tighter">{{ $totalInWarehouse }} <span class="text-xs font-bold text-gray-500">pzas</span></p>
            </div>
        </div>

        {{-- Stat 2: Equipos Activos --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2rem] p-6 flex items-center gap-5 shadow-[0_20px_50px_rgba(0,0,0,0.3)] group hover:border-emerald-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Equipos Activos</p>
                <p class="text-2xl font-black text-white leading-none tracking-tighter">{{ $totalAssignedActive }} <span class="text-xs font-bold text-gray-500">en uso</span></p>
            </div>
        </div>

        {{-- Stat 3: Historial Retornos --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2rem] p-6 flex items-center gap-5 shadow-[0_20px_50px_rgba(0,0,0,0.3)] group hover:border-amber-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-amber-600/10 border border-amber-500/20 flex items-center justify-center text-amber-400 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Retornos Históricos</p>
                <p class="text-2xl font-black text-white leading-none tracking-tighter">{{ $totalAssignedReturned }} <span class="text-xs font-bold text-gray-500">retornados</span></p>
            </div>
        </div>

        {{-- Stat 4: Alertas Stock Bajo --}}
        <div class="backdrop-blur-3xl border rounded-[2rem] p-6 flex items-center gap-5 shadow-[0_20px_50px_rgba(0,0,0,0.3)] group transition-all {{ $lowStockAlerts > 0 ? 'bg-red-600/5 border-red-500/20 hover:border-red-500/40' : 'bg-[#1a1a2e]/40 border-white/5 hover:border-purple-500/20' }}">
            <div class="w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 transition-all duration-300 {{ $lowStockAlerts > 0 ? 'bg-red-600/20 text-red-400 group-hover:bg-red-600 group-hover:text-white' : 'bg-purple-600/10 border border-purple-500/20 text-purple-400 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white' }}">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Modelos en Alerta</p>
                <p class="text-2xl font-black text-white leading-none tracking-tighter">{{ $lowStockAlerts }} <span class="text-xs font-bold text-gray-500">críticos</span></p>
            </div>
        </div>
    </div>

    {{-- Main Workspace: Sidebar Tab + Content Panel --}}
    <div class="flex flex-col lg:flex-row gap-8">
        
        {{-- Left Navigation Menu --}}
        <div class="w-full lg:w-64 bg-[#101026]/40 backdrop-blur-2xl border border-white/5 rounded-[2.5rem] p-6 space-y-2.5 shrink-0 shadow-2xl">
            <button wire:click="$set('subTab', 'bodega')" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest border {{ $subTab === 'bodega' ? 'bg-blue-600 border-blue-500/30 text-white shadow-[0_0_20px_rgba(37,99,235,0.4)]' : 'bg-transparent border-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                <span>Stock de Bodega</span>
            </button>
            <button wire:click="$set('subTab', 'assignments')" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest border {{ $subTab === 'assignments' ? 'bg-blue-600 border-blue-500/30 text-white shadow-[0_0_20px_rgba(37,99,235,0.4)]' : 'bg-transparent border-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                <span>Asignaciones</span>
            </button>
            <button wire:click="$set('subTab', 'logs')" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest border {{ $subTab === 'logs' ? 'bg-blue-600 border-blue-500/30 text-white shadow-[0_0_20px_rgba(37,99,235,0.4)]' : 'bg-transparent border-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                <span>Movimientos / Logs</span>
            </button>
            <div class="h-px bg-white/5 my-4"></div>
            <button wire:click="$set('subTab', 'equipos')" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest border {{ $subTab === 'equipos' ? 'bg-blue-600 border-blue-500/30 text-white shadow-[0_0_20px_rgba(37,99,235,0.4)]' : 'bg-transparent border-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                <span>Equipos Físicos</span>
            </button>
            <button wire:click="$set('subTab', 'scanner')" class="w-full flex items-center gap-3 px-5 py-4 rounded-2xl transition-all font-bold text-[10px] uppercase tracking-widest border {{ $subTab === 'scanner' ? 'bg-emerald-600 border-emerald-500/30 text-white shadow-[0_0_20px_rgba(16,185,129,0.4)]' : 'bg-transparent border-transparent text-gray-400 hover:bg-white/5 hover:text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                <span>Escáner / Pistola</span>
            </button>
        </div>

        {{-- Right Panel Content --}}
        <div class="flex-1 min-w-0">
            
            {{-- SUB-TAB 1: BODEGA (Grouped Stock) --}}
            @if($subTab === 'bodega')
            <div class="space-y-6">
                {{-- Header with action buttons --}}
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xl font-black text-white uppercase tracking-tight font-black">Material en Resguardo (Bodega)</h4>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Define mínimos y máximos manualmente por cada equipo para gestionar alertas visuales automáticas</p>
                    </div>
                    <div class="flex gap-3">
                        <button wire:click="$set('showAddMaterialModal', true)" class="bg-indigo-600 hover:bg-indigo-500 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-2 shadow-xl shadow-indigo-600/20 transition-all focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                            Ingresar Material
                        </button>
                        <button wire:click="$set('showAssignItemModal', true)" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-2 shadow-xl shadow-blue-600/20 transition-all focus:outline-none">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                            Asignar Equipo
                        </button>
                    </div>
                </div>

                {{-- DASHBOARD SUMMARY MINI-CARDS (React Style) --}}
                <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
                    <div class="bg-[#101026]/40 backdrop-blur-3xl p-6 rounded-3xl border border-white/5 shadow-md flex items-center gap-4">
                        <div class="p-3 bg-white/5 rounded-2xl text-gray-400">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                        </div>
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-500">Modelos en Bodega</p>
                            <h3 class="text-2xl font-black text-white leading-none tracking-tight mt-1.5">{{ $summary['total'] }}</h3>
                        </div>
                    </div>

                    <div class="bg-[#101026]/40 backdrop-blur-3xl p-6 rounded-3xl border border-white/5 shadow-md flex items-center gap-4">
                        <div class="p-3 bg-emerald-500/10 rounded-2xl text-emerald-400">
                            <span class="w-3 h-3 rounded-full bg-emerald-500 inline-block animate-pulse"></span>
                        </div>
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-500">Abastecido (Verde)</p>
                            <h3 class="text-2xl font-black text-emerald-400 leading-none tracking-tight mt-1.5">{{ $summary['green'] }}</h3>
                        </div>
                    </div>

                    <div class="bg-[#101026]/40 backdrop-blur-3xl p-6 rounded-3xl border border-white/5 shadow-md flex items-center gap-4">
                        <div class="p-3 bg-amber-500/10 rounded-2xl text-amber-400">
                            <span class="w-3 h-3 rounded-full bg-amber-500 inline-block"></span>
                        </div>
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-500">Stock Bajo (Amarillo)</p>
                            <h3 class="text-2xl font-black text-amber-400 leading-none tracking-tight mt-1.5">{{ $summary['yellow'] }}</h3>
                        </div>
                    </div>

                    <div class="bg-[#101026]/40 backdrop-blur-3xl p-6 rounded-3xl border border-white/5 shadow-md flex items-center gap-4">
                        <div class="p-3 bg-rose-500/10 rounded-2xl text-rose-400">
                            <span class="w-3 h-3 rounded-full bg-rose-500 inline-block animate-ping"></span>
                        </div>
                        <div>
                            <p class="text-[8px] font-black uppercase tracking-widest text-gray-500">Hay que pedir (Rojo)</p>
                            <h3 class="text-2xl font-black text-rose-400 leading-none tracking-tight mt-1.5">{{ $summary['red'] }}</h3>
                        </div>
                    </div>
                </section>

                {{-- FILTERS CONTROL BAR (React Style) --}}
                <section class="bg-[#101026]/60 backdrop-blur-3xl rounded-[2rem] p-5 border border-white/5 shadow-sm flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    {{-- Search Input --}}
                    <div class="relative flex-1">
                        <span class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        </span>
                        <input
                            type="text"
                            wire:model.live="searchTerm"
                            placeholder="Buscar equipo o categoría..."
                            class="w-full pl-11 pr-4 py-3 rounded-xl border border-white/10 bg-[#0b0b1e]/60 text-white focus:outline-none focus:border-indigo-500/60 transition-all text-xs font-semibold"
                        />
                    </div>

                    {{-- Dropdown Filters --}}
                    <div class="flex flex-wrap items-center gap-4">
                        <div class="flex items-center gap-2">
                            <label class="text-[9px] font-black text-gray-500 uppercase">Categoría:</label>
                            <select
                                wire:model.live="selectedCategory"
                                class="rounded-xl border border-white/10 bg-[#0b0b1e]/60 px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 font-bold"
                            >
                                <option value="Todas">Todas las categorías</option>
                                @foreach($categories as $cat)
                                    <option value="{{ $cat }}">{{ $cat }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="flex items-center gap-2">
                            <label class="text-[9px] font-black text-gray-500 uppercase">Semáforo:</label>
                            <select
                                wire:model.live="selectedStatus"
                                class="rounded-xl border border-white/10 bg-[#0b0b1e]/60 px-4 py-2.5 text-xs text-white focus:outline-none focus:border-indigo-500 font-bold"
                            >
                                <option value="Todos">Todos los estados</option>
                                <option value="green">Verde (Abastecido)</option>
                                <option value="yellow">Amarillo (Bajo, no pedir)</option>
                                <option value="red">Rojo (Hay que pedir)</option>
                            </select>
                        </div>

                        {{-- Reset Filters Button --}}
                        @if($searchTerm || $selectedCategory !== 'Todas' || $selectedStatus !== 'Todos')
                            <button
                                wire:click="$set('searchTerm', ''); $set('selectedCategory', 'Todas'); $set('selectedStatus', 'Todos');"
                                class="text-[10px] font-black text-indigo-400 hover:text-indigo-300 uppercase tracking-wider focus:outline-none"
                            >
                                Limpiar Filtros
                            </button>
                        @endif
                    </div>
                </section>

                {{-- Grouped Stock Cards --}}
                @if($stockGrouped->isEmpty())
                    <div class="text-center py-20 bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 border-dashed rounded-[2.5rem]">
                        <svg class="w-12 h-12 text-gray-600 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0a2 2 0 01-2 2H6a2 2 0 01-2-2m16 0V9a2 2 0 00-2-2H6a2 2 0 00-2 2v4.5m12+3.5h.01M5.071 19.071l1.414-1.414M18.364 5.636l-1.414 1.414m-12 0l1.414 1.414m10.586 10.586l-1.414-1.414"/></svg>
                        <h3 class="text-lg font-bold text-white uppercase tracking-tight">No se encontraron equipos</h3>
                        <p class="text-xs text-gray-500 mt-1">Intenta ajustando tus filtros o ingresa un nuevo equipo manualmente.</p>
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($stockGrouped as $item)
                            @php
                                $status = $item->status_info;
                                $percentage = min(100, round(($item->quantity / ($item->max_stock ?: 25)) * 100));
                                $minMarkerPercentage = round((($item->min_stock ?: 5) / ($item->max_stock ?: 25)) * 100);
                            @endphp
                            <div class="bg-[#101026]/80 backdrop-blur-2xl rounded-[2rem] border {{ $status['borderClass'] }} shadow-[0_20px_50px_rgba(0,0,0,0.3)] overflow-hidden flex flex-col justify-between transition-all duration-200 hover:scale-[1.01] hover:border-blue-500/20">
                                
                                {{-- Card Header --}}
                                <div class="p-6 pb-4 border-b border-white/5">
                                    <div class="flex items-start justify-between gap-2 mb-3">
                                        <span class="text-[9px] font-black text-gray-400 bg-white/5 px-2.5 py-1.5 rounded-lg uppercase tracking-wider">
                                            {{ $item->type }}
                                        </span>
                                        {{-- Badge dinámico de Semáforo --}}
                                        <span class="text-[9px] font-black px-2.5 py-1.5 rounded-full uppercase flex items-center gap-1.5 {{ $status['badgeBg'] }}">
                                            <span class="w-1.5 h-1.5 rounded-full {{ $status['bgClass'] }}"></span>
                                            {{ $status['label'] }}
                                        </span>
                                    </div>
                                    <h3 class="font-extrabold text-md leading-snug text-white line-clamp-2 uppercase tracking-tight min-h-[48px]" title="{{ $item->model }}">
                                        {{ $item->model }}
                                    </h3>
                                </div>

                                {{-- Body: Min, Actual, Max and Progress Bar --}}
                                <div class="p-6 space-y-5 flex-grow">
                                    {{-- Values grid --}}
                                    @if($globalPlantFilter === 'Todas')
                                        <div class="grid grid-cols-4 gap-2 text-center">
                                            <div class="p-2.5 bg-white/[0.02] rounded-xl border border-white/5 flex flex-col justify-center">
                                                <span class="block text-[8px] uppercase font-black text-gray-500 tracking-wider">Mín</span>
                                                <span class="text-xs font-black text-rose-500">{{ $item->min_stock }}</span>
                                            </div>
                                            <div class="p-2.5 bg-indigo-500/10 rounded-xl border border-indigo-500/20 flex flex-col justify-center">
                                                <span class="block text-[8px] uppercase font-black text-indigo-400 tracking-wider">P1</span>
                                                <span class="text-sm font-black text-indigo-300">{{ $item->stockP1 }}</span>
                                            </div>
                                            <div class="p-2.5 bg-teal-500/10 rounded-xl border border-teal-500/20 flex flex-col justify-center">
                                                <span class="block text-[8px] uppercase font-black text-teal-400 tracking-wider">P2</span>
                                                <span class="text-sm font-black text-teal-300">{{ $item->stockP2 }}</span>
                                            </div>
                                            <div class="p-2.5 bg-white/[0.02] rounded-xl border border-white/5 flex flex-col justify-center">
                                                <span class="block text-[8px] uppercase font-black text-gray-500 tracking-wider">Total</span>
                                                <span class="text-base font-black {{ $status['textClass'] }}">{{ $item->quantity }}</span>
                                            </div>
                                        </div>
                                    @else
                                        <div class="grid grid-cols-3 gap-3 text-center">
                                            <div class="p-2.5 bg-white/[0.02] rounded-xl border border-white/5">
                                                <span class="block text-[8px] uppercase font-black text-gray-500 tracking-wider">Mínimo</span>
                                                <span class="text-sm font-black text-rose-500">{{ $item->min_stock }}</span>
                                            </div>
                                            <div class="p-2.5 bg-white/[0.02] rounded-xl border border-white/5">
                                                <span class="block text-[8px] uppercase font-black text-gray-500 tracking-wider">Actual</span>
                                                <span class="text-base font-black {{ $status['textClass'] }}">{{ $item->quantity }}</span>
                                            </div>
                                            <div class="p-2.5 bg-white/[0.02] rounded-xl border border-white/5">
                                                <span class="block text-[8px] uppercase font-black text-gray-500 tracking-wider">Máximo</span>
                                                <span class="text-sm font-black text-emerald-500">{{ $item->max_stock }}</span>
                                            </div>
                                        </div>
                                    @endif

                                    {{-- Visual Progress Bar --}}
                                    <div class="space-y-2">
                                        <div class="flex justify-between text-[10px] text-gray-500 font-bold uppercase tracking-wider">
                                            <span>Capacidad de bodega</span>
                                            <span>{{ $percentage }}%</span>
                                        </div>
                                        
                                        <div class="relative w-full h-3 bg-white/5 rounded-full overflow-visible">
                                            {{-- Actual fill --}}
                                            <div class="h-full rounded-full transition-all duration-300 {{ $status['bgClass'] }}"
                                                 style="width: {{ $percentage }}%">
                                            </div>

                                            {{-- Min Marker --}}
                                            @if($minMarkerPercentage < 100)
                                                <div class="absolute top-0 bottom-0 w-0.5 bg-rose-500 z-10"
                                                     style="left: {{ $minMarkerPercentage }}%"
                                                     title="Límite mínimo establecido: {{ $item->min_stock }} unidades">
                                                    <span class="absolute -top-4 -left-2 text-[7px] font-black text-rose-400">MIN</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>

                                {{-- Quick Actions --}}
                                <div class="p-4 bg-white/[0.02] border-t border-white/5 flex items-center justify-between gap-4">
                                    {{-- Quick Quantity Adjustments --}}
                                    <div class="flex items-center gap-2">
                                        <button wire:click="decrementStock('{{ $item->type }}', '{{ $item->model }}')"
                                                class="bg-white/5 hover:bg-white/10 border border-white/10 p-2 rounded-lg text-gray-300 hover:text-white transition focus:outline-none">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M20 12H4"/></svg>
                                        </button>
                                        <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Ajuste</span>
                                        <button wire:click="incrementStock('{{ $item->type }}', '{{ $item->model }}')"
                                                class="bg-white/5 hover:bg-white/10 border border-white/10 p-2 rounded-lg text-gray-300 hover:text-white transition focus:outline-none">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                                        </button>
                                    </div>

                                    {{-- Edit & Delete --}}
                                    <div class="flex items-center gap-1">
                                        <button wire:click="openAssignFor('{{ $item->type }}', '{{ $item->model }}')"
                                                class="text-indigo-400 hover:text-white hover:bg-indigo-600/20 p-2 rounded-lg transition-colors focus:outline-none mr-2"
                                                title="Asignar material">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/></svg>
                                        </button>
                                        <button wire:click="openEditMaterial('{{ $item->type }}', '{{ $item->model }}')"
                                                class="text-gray-400 hover:text-indigo-400 hover:bg-indigo-600/10 p-2 rounded-lg transition-colors focus:outline-none"
                                                title="Editar parámetros">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                        </button>
                                        <button onclick="confirm('¿Estás seguro de que deseas eliminar {{ $item->model }} del stock?') || event.stopImmediatePropagation()"
                                                wire:click="deleteStockGroup('{{ $item->type }}', '{{ $item->model }}')"
                                                class="text-gray-400 hover:text-rose-500 hover:bg-rose-500/10 p-2 rounded-lg transition-colors focus:outline-none"
                                                title="Eliminar del stock">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
            @endif

            {{-- SUB-TAB 5: SCANNER --}}
            @if($subTab === 'scanner')
            <div class="space-y-6 animate-in fade-in slide-in-from-right-4 duration-500">
                <div>
                    <h4 class="text-xl font-black text-white uppercase tracking-tight">Escáner Auto Multi-Planta</h4>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Control de inventario rápido mediante pistola de código de barras</p>
                </div>

                <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                    <div class="space-y-6">
                        <div class="bg-[#101026]/40 backdrop-blur-3xl border border-white/5 rounded-[2rem] p-6 md:p-8 shadow-2xl relative overflow-hidden">
                            
                            {{-- Plant Selector --}}
                            <div class="bg-gradient-to-r from-indigo-900/20 to-purple-900/20 p-4 rounded-2xl border border-indigo-500/20 mb-6 space-y-3">
                                <div class="flex items-center justify-between">
                                    <span class="text-[10px] font-black text-indigo-400 uppercase tracking-widest flex items-center gap-1.5">
                                        <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg>
                                        Planta Destino
                                    </span>
                                    <span class="bg-indigo-500/20 border border-indigo-500/40 text-indigo-300 text-[10px] px-3 py-1 rounded-full font-black tracking-widest uppercase">
                                        {{ $scannerActivePlant }}
                                    </span>
                                </div>
                                <div class="grid grid-cols-2 gap-3 text-xs">
                                    <button wire:click="$set('scannerActivePlant', 'Planta 1')" class="py-2.5 px-4 rounded-xl font-black uppercase tracking-wider transition-all flex items-center justify-center gap-2 {{ $scannerActivePlant === 'Planta 1' ? 'bg-indigo-600 text-white shadow-[0_0_15px_rgba(79,70,229,0.3)]' : 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10' }}">
                                        Planta 1
                                    </button>
                                    <button wire:click="$set('scannerActivePlant', 'Planta 2')" class="py-2.5 px-4 rounded-xl font-black uppercase tracking-wider transition-all flex items-center justify-center gap-2 {{ $scannerActivePlant === 'Planta 2' ? 'bg-teal-600 text-white shadow-[0_0_15px_rgba(13,148,136,0.3)]' : 'bg-white/5 text-gray-400 hover:text-white hover:bg-white/10' }}">
                                        Planta 2
                                    </button>
                                </div>
                            </div>

                            {{-- Mode Selector --}}
                            <div class="mb-6 bg-white/[0.02] p-2 rounded-2xl border border-white/5 grid grid-cols-3 gap-2 text-center">
                                <button wire:click="$set('scannerMode', 'add')" class="py-3 px-2 rounded-xl font-black uppercase tracking-widest text-[9px] sm:text-[10px] transition-all {{ $scannerMode === 'add' ? 'bg-emerald-600 text-white shadow-lg shadow-emerald-600/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="w-5 h-5 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    + Entrada
                                </button>
                                <button wire:click="$set('scannerMode', 'deduct')" class="py-3 px-2 rounded-xl font-black uppercase tracking-widest text-[9px] sm:text-[10px] transition-all {{ $scannerMode === 'deduct' ? 'bg-rose-600 text-white shadow-lg shadow-rose-600/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="w-5 h-5 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    - Salida
                                </button>
                                <button wire:click="$set('scannerMode', 'verify')" class="py-3 px-2 rounded-xl font-black uppercase tracking-widest text-[9px] sm:text-[10px] transition-all {{ $scannerMode === 'verify' ? 'bg-blue-600 text-white shadow-lg shadow-blue-600/30' : 'text-gray-400 hover:text-white hover:bg-white/5' }}">
                                    <svg class="w-5 h-5 mx-auto mb-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                    Verificar
                                </button>
                            </div>

                            @if(!$stagedBarcode)
                                {{-- Scanner Input --}}
                                <div class="mt-4">
                                    <label class="block text-[10px] font-black uppercase tracking-widest text-gray-500 mb-2">Lectura por Pistola (Apunta y Dispara)</label>
                                    <div class="relative">
                                        <span class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-indigo-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                        </span>
                                        <input 
                                            type="text" 
                                            wire:model="scannedBarcode" 
                                            wire:keydown.enter="processScan"
                                            autofocus
                                            placeholder="Escanea con pistola o escribe código y presiona Enter..." 
                                            class="w-full bg-[#0b0b1e]/90 border border-white/10 text-white text-sm rounded-2xl pl-12 pr-4 py-4 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-mono font-bold"
                                        >
                                    </div>
                                </div>

                                {{-- Settings Toggles --}}
                                <div class="mt-6 pt-5 border-t border-white/5 grid grid-cols-1 gap-4">
                                    <label class="flex items-center gap-3 cursor-pointer group">
                                        <input type="checkbox" wire:model="scanSoundEnabled" class="w-4 h-4 rounded border-gray-600 text-indigo-600 focus:ring-indigo-600 bg-gray-700">
                                        <span class="text-xs font-bold text-gray-400 group-hover:text-gray-200 transition">Sonido al Escanear</span>
                                    </label>
                                </div>
                            @else
                                {{-- Staging Form --}}
                                <div class="mt-6 border border-indigo-500/30 bg-indigo-900/10 rounded-2xl p-5 shadow-inner">
                                    <h5 class="text-xs font-black uppercase text-indigo-400 mb-4 flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                        Confirmar Acción: {{ $scannerMode === 'add' ? 'Entrada' : 'Salida' }}
                                    </h5>
                                    
                                    <div class="space-y-4">
                                        {{-- Barcode (Readonly) --}}
                                        <div>
                                            <label class="block text-[9px] font-black text-gray-500 uppercase mb-1">Código</label>
                                            <div class="px-4 py-3 bg-[#0b0b1e]/60 border border-white/5 rounded-xl text-gray-400 font-mono text-xs">
                                                {{ $stagedBarcode }}
                                                @if($stagedIsNew) <span class="ml-2 text-[9px] bg-emerald-500/20 text-emerald-400 px-2 py-0.5 rounded-full">(Nuevo)</span> @endif
                                            </div>
                                        </div>
                                        
                                        {{-- Row 1: Producto and Modelo --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            {{-- Producto/Name --}}
                                            <div>
                                                <label class="block text-[9px] font-black text-gray-500 uppercase mb-1">Producto</label>
                                                <input type="text" wire:model="stagedName" placeholder="Ej: Toner, Teclado..." class="w-full bg-[#0b0b1e]/90 border border-white/10 text-white text-xs rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-bold" {{ $scannerMode === 'deduct' && !$stagedIsNew ? 'readonly' : '' }}>
                                            </div>
                                            {{-- Model --}}
                                            <div>
                                                <label class="block text-[9px] font-black text-gray-500 uppercase mb-1">Modelo</label>
                                                <input type="text" wire:model="stagedModel" placeholder="Ej: TB330FU..." class="w-full bg-[#0b0b1e]/90 border border-white/10 text-white text-xs rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-bold" {{ $scannerMode === 'deduct' && !$stagedIsNew ? 'readonly' : '' }}>
                                            </div>
                                        </div>

                                        {{-- Row 2: Description --}}
                                        <div>
                                            <label class="block text-[9px] font-black text-gray-500 uppercase mb-1">Descripción</label>
                                            <input type="text" wire:model="stagedDescription" placeholder="Ej: CARTUCHO LEON..." class="w-full bg-[#0b0b1e]/90 border border-white/10 text-white text-xs rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-bold" {{ $scannerMode === 'deduct' && !$stagedIsNew ? 'readonly' : '' }}>
                                        </div>

                                        {{-- Row 3: Category and Quantity --}}
                                        <div class="grid grid-cols-2 gap-4">
                                            {{-- Category --}}
                                            <div>
                                                <label class="block text-[9px] font-black text-gray-500 uppercase mb-1">Categoría</label>
                                                <select wire:model="stagedType" class="w-full bg-[#0b0b1e]/90 border border-white/10 text-white text-xs rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 transition font-bold" {{ $scannerMode === 'deduct' && !$stagedIsNew ? 'disabled' : '' }}>
                                                    @foreach($categories as $cat)
                                                        <option value="{{ $cat }}">{{ $cat }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                            {{-- Quantity --}}
                                            <div>
                                                <label class="block text-[9px] font-black text-gray-500 uppercase mb-1">Cantidad (Unidades)</label>
                                                <input type="number" wire:model="stagedQty" min="1" class="w-full bg-[#0b0b1e]/90 border border-white/10 text-white text-xs rounded-xl px-4 py-3 focus:outline-none focus:border-indigo-500 focus:ring-1 focus:ring-indigo-500 transition font-bold text-center">
                                            </div>
                                        </div>
                                        
                                        {{-- Actions --}}
                                        <div class="flex items-center gap-3 pt-3">
                                            <button wire:click="cancelScan" class="flex-1 py-3 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-gray-300 font-black text-[10px] uppercase tracking-widest transition-all">
                                                Cancelar
                                            </button>
                                            <button wire:click="commitScan" class="flex-1 py-3 rounded-xl font-black text-[10px] uppercase tracking-widest transition-all shadow-lg {{ $scannerMode === 'add' ? 'bg-emerald-600 hover:bg-emerald-500 text-white shadow-emerald-600/30' : 'bg-rose-600 hover:bg-rose-500 text-white shadow-rose-600/30' }}">
                                                Confirmar {{ $scannerMode === 'add' ? '+' : '-' }}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            {{-- Feedback Alert --}}
                            @if($lastScanFeedback)
                                <div class="mt-6 p-4 rounded-2xl border flex items-center justify-between transition-all animate-in zoom-in-95 {{ $lastScanFeedback['color'] === 'emerald' ? 'bg-emerald-950/40 border-emerald-500/40 text-emerald-300' : ($lastScanFeedback['color'] === 'amber' ? 'bg-amber-950/40 border-amber-500/40 text-amber-300' : ($lastScanFeedback['color'] === 'rose' ? 'bg-rose-950/40 border-rose-500/40 text-rose-300' : 'bg-blue-950/40 border-blue-500/40 text-blue-300')) }}">
                                    <div class="flex items-start gap-3">
                                        <div class="mt-1">
                                            @if($lastScanFeedback['color'] === 'emerald')
                                                <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @elseif($lastScanFeedback['color'] === 'amber')
                                                <svg class="w-5 h-5 text-amber-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/></svg>
                                            @elseif($lastScanFeedback['color'] === 'rose')
                                                <svg class="w-5 h-5 text-rose-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @else
                                                <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                            @endif
                                        </div>
                                        <div>
                                            <p class="font-black tracking-tight text-sm">{{ $lastScanFeedback['title'] }}</p>
                                            <p class="text-xs opacity-80 mt-0.5">{{ $lastScanFeedback['detail'] }}</p>
                                        </div>
                                    </div>
                                    <span class="text-[10px] font-bold opacity-50 uppercase tracking-widest shrink-0">{{ $lastScanFeedback['time'] }}</span>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="space-y-6">
                        {{-- Quick History for Scanner --}}
                        <div class="bg-[#101026]/40 backdrop-blur-3xl border border-white/5 rounded-[2rem] p-6 shadow-2xl">
                            <h4 class="text-xs font-black text-white uppercase tracking-widest mb-4 flex items-center gap-2">
                                <svg class="w-4 h-4 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Últimos Movimientos Escáner
                            </h4>
                            <div class="space-y-4">
                                @forelse(collect($movements)->filter(fn($m) => str_contains($m->details, 'Escáner Auto'))->take(5) as $m)
                                    <div class="bg-white/[0.02] border border-white/5 rounded-xl p-3 flex flex-col gap-1">
                                        <div class="flex items-center justify-between">
                                            <span class="text-[10px] font-black {{ $m->action === 'Ingreso' ? 'text-emerald-400' : ($m->action === 'Salida' ? 'text-amber-400' : 'text-blue-400') }} uppercase tracking-widest">{{ $m->action }}</span>
                                            <span class="text-[9px] text-gray-500 font-bold">{{ $m->created_at->diffForHumans() }}</span>
                                        </div>
                                        <p class="text-xs text-gray-300 font-semibold">{{ $m->details }}</p>
                                    </div>
                                @empty
                                    <p class="text-xs text-gray-500 text-center py-4 font-bold uppercase tracking-widest">Sin escaneos recientes</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            {{-- SUB-TAB 2: ASIGNACIONES --}}
            @if($subTab === 'assignments')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xl font-black text-white uppercase tracking-tight">Equipos Asignados (Estaciones o Usuarios)</h4>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Equipos en uso activo en el floor o asignados a personal</p>
                    </div>
                    <button wire:click="$set('showAssignItemModal', true)" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-2 shadow-xl shadow-blue-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" /></svg>
                        Asignar Equipo
                    </button>
                </div>

                {{-- Assignments Table --}}
                <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1200px]">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Equipo / Modelo</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Asignado a</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Tipo</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Código Único</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Fecha Asignación</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest">Estado</th>
                                    <th class="px-8 py-6 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Acción</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.03]">
                                @forelse($assignments as $as)
                                <tr class="group hover:bg-white/[0.03] transition-all">
                                    <td class="px-8 py-5">
                                        <p class="text-sm font-black text-white uppercase tracking-tight">{{ $as->type }}</p>
                                        <p class="text-[10px] font-bold text-gray-500 uppercase mt-0.5">{{ $as->model }}</p>
                                    </td>
                                    <td class="px-8 py-5 text-sm text-gray-300 font-bold uppercase tracking-tight">
                                        @if($as->maquina)
                                            {{ $as->maquina->nombre }}
                                        @elseif($as->usuario)
                                            {{ $as->usuario->name }}
                                        @else
                                            —
                                        @endif
                                    </td>
                                    <td class="px-8 py-5">
                                        <span class="px-2.5 py-1 rounded-lg text-[9px] font-black uppercase tracking-wider border {{ $as->maquina_id ? 'bg-purple-500/10 border-purple-500/20 text-purple-400' : 'bg-blue-500/10 border-blue-500/20 text-blue-400' }}">
                                            {{ $as->maquina_id ? 'Estación' : 'Usuario' }}
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 font-mono text-[11px] text-gray-400 font-bold tracking-widest">{{ $as->barcode }}</td>
                                    <td class="px-8 py-5 text-xs text-gray-500 font-bold">{{ $as->installed_at ? \Carbon\Carbon::parse($as->installed_at)->format('d/m/Y') : '—' }}</td>
                                    <td class="px-8 py-5">
                                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 rounded-xl text-[9px] font-black uppercase tracking-widest">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 animate-pulse"></span>
                                            <span>Activo</span>
                                        </span>
                                    </td>
                                    <td class="px-8 py-5 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            {{-- Documento --}}
                                            @if($as->pdf_path)
                                                <button wire:click="$dispatch('viewDocument', { uniqueId: 'eq_{{ $as->id }}' })" title="Ver Responsiva en Plataforma" class="w-8 h-8 rounded-xl bg-teal-600/10 border border-teal-500/20 text-teal-400 hover:bg-teal-600 hover:text-white transition-all flex items-center justify-center animate-in fade-in duration-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <button wire:click="downloadPdf({{ $as->id }})" title="Descargar Responsiva/Contrato" class="w-8 h-8 rounded-xl bg-blue-600/10 border border-blue-500/20 text-blue-400 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center animate-in fade-in duration-300">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </button>
                                            @endif
                                            <button onclick="document.getElementById('pdf-upload-assign-{{ $as->id }}').click()" title="{{ $as->pdf_path ? 'Reemplazar PDF' : 'Subir Responsiva/Contrato' }}" class="w-8 h-8 rounded-xl {{ $as->pdf_path ? 'bg-white/5 text-gray-400 border-white/10' : 'bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 hover:bg-indigo-600 hover:text-white' }} transition-all flex items-center justify-center relative">
                                                <div wire:loading wire:target="pdfFiles.{{ $as->id }}" class="absolute inset-0 bg-indigo-600 rounded-xl flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                </div>
                                                <svg wire:loading.remove wire:target="pdfFiles.{{ $as->id }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            </button>
                                            <input type="file" id="pdf-upload-assign-{{ $as->id }}" class="hidden" wire:model="pdfFiles.{{ $as->id }}" accept=".pdf">

                                            <button wire:click="openEditAssignment({{ $as->id }})" title="Editar Asignación" class="w-8 h-8 rounded-xl bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white transition-all flex items-center justify-center border border-white/10 focus:outline-none">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/></svg>
                                            </button>

                                            <button wire:click="returnItem({{ $as->id }})" class="bg-amber-600/10 hover:bg-amber-600 text-amber-400 hover:text-white px-4 py-2 rounded-xl text-[10px] font-black uppercase tracking-wider border border-amber-500/20 transition-all flex items-center gap-1.5">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/></svg>
                                                <span>Retornar</span>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-8 py-20 text-center opacity-30">
                                        <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        <p class="text-xs font-black uppercase tracking-widest text-gray-500">Sin asignaciones registradas</p>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

            {{-- SUB-TAB 3: LOGS / HISTORIAL --}}
            @if($subTab === 'logs')
            <div class="space-y-6">
                <div>
                    <h4 class="text-xl font-black text-white uppercase tracking-tight">Bitácora de Movimientos (Tiempo Real)</h4>
                    <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Registro de auditoría de todas las entradas, salidas y asignaciones</p>
                </div>

                {{-- Timeline Component --}}
                <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] p-8 shadow-2xl relative">
                    <div class="absolute left-[39px] top-10 bottom-10 w-0.5 bg-white/5"></div>
                    
                    <div class="space-y-8 relative">
                        @forelse($movements as $m)
                        <div class="flex items-start gap-6 animate-in fade-in slide-in-from-bottom-2 duration-300">
                            {{-- Icon Circle --}}
                            @php
                                $logStyles = match($m->action) {
                                    'Ingreso'    => ['icon' => '📥', 'bg' => 'bg-indigo-600/20 border-indigo-500/30 text-indigo-400'],
                                    'Asignación' => ['icon' => '📤', 'bg' => 'bg-blue-600/20 border-blue-500/30 text-blue-400'],
                                    'Retorno'    => ['icon' => '↩️', 'bg' => 'bg-amber-600/20 border-amber-500/30 text-amber-400'],
                                    default      => ['icon' => '📝', 'bg' => 'bg-white/10 border-white/10 text-white'],
                                };
                            @endphp
                            <div class="w-10 h-10 rounded-xl border flex items-center justify-center text-sm font-bold {{ $logStyles['bg'] }} z-10 shrink-0">
                                {{ $logStyles['icon'] }}
                            </div>
                            
                            {{-- Message Content --}}
                            <div class="flex-1 bg-white/[0.02] border border-white/5 rounded-2xl p-5 shadow-lg">
                                <div class="flex items-center justify-between gap-4 mb-2">
                                    <h5 class="text-xs font-black text-white uppercase tracking-widest">{{ $m->action }}</h5>
                                    <span class="text-[9px] font-bold text-gray-600 uppercase tracking-wider">{{ $m->created_at->diffForHumans() }} ({{ $m->created_at->format('d/m/Y H:i') }})</span>
                                </div>
                                <p class="text-sm text-gray-300 font-semibold tracking-tight">{{ $m->details }}</p>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-10 opacity-30">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <p class="text-xs font-black uppercase tracking-widest text-gray-500">Sin movimientos registrados</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
            @endif

            {{-- SUB-TAB 4: EQUIPOS ORIGINALES (Físico Serializados) --}}
            @if($subTab === 'equipos')
            <div class="space-y-6">
                <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h4 class="text-xl font-black text-white uppercase tracking-tight">Equipos Físicos Serializados</h4>
                        <p class="text-xs text-gray-500 font-bold uppercase tracking-widest mt-1">Inventario detallado de hardware y contratos / responsivas subidas</p>
                    </div>
                    <button wire:click="$dispatch('openAddEquipment')" class="bg-blue-600 hover:bg-blue-500 text-white px-6 py-3 rounded-2xl text-[10px] font-black uppercase tracking-wider flex items-center gap-2 shadow-xl shadow-blue-600/20 transition-all">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                        Alta de Equipo
                    </button>
                </div>

                {{-- Table container --}}
                <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] overflow-hidden shadow-2xl">
                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse min-w-[1200px]">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Equipo</th>
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Código / Modelo</th>
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Asignado a</th>
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Movimiento</th>
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Ubicación</th>
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">F. Adquisición</th>
                                    <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.03]">
                                @forelse($inventory as $item)
                                <tr class="group hover:bg-white/[0.03] transition-all">
                                    {{-- Equipo --}}
                                    <td class="px-8 py-6">
                                        <div class="flex items-center gap-4">
                                            <div class="w-10 h-10 rounded-xl bg-white/5 flex items-center justify-center text-gray-400 group-hover:bg-blue-600/20 group-hover:text-blue-400 transition-all shrink-0">
                                                @if($item->type === 'Pantalla')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                                @elseif($item->type === 'CPU')
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                                @else
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                                @endif
                                            </div>
                                            <div>
                                                <p class="text-sm font-black text-white uppercase tracking-tight">{{ $item->name }}</p>
                                                <p class="text-[10px] font-bold text-gray-600 uppercase">{{ $item->type }}</p>
                                            </div>
                                        </div>
                                    </td>

                                    {{-- Código / Modelo --}}
                                    <td class="px-8 py-6">
                                        <p class="text-[11px] font-black text-gray-300 uppercase tracking-widest">{{ $item->barcode ?? '—' }}</p>
                                        <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest mt-0.5">{{ $item->model ?? '—' }}</p>
                                    </td>

                                    {{-- Asignado a: Usuario o Estación --}}
                                    <td class="px-8 py-6">
                                        @if($item->maquina)
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-blue-600/20 flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-[11px] font-black text-white uppercase tracking-tight">{{ $item->maquina->nombre }}</p>
                                                    <p class="text-[9px] font-bold text-gray-600 uppercase tracking-wider">Estación asignada</p>
                                                </div>
                                            </div>
                                        @elseif($item->usuario)
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-emerald-600/20 flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                                </div>
                                                <div>
                                                    <p class="text-[11px] font-black text-white uppercase tracking-tight">{{ $item->usuario->name }}</p>
                                                    <p class="text-[9px] font-bold text-gray-600 uppercase tracking-wider">Usuario asignado</p>
                                                </div>
                                            </div>
                                        @else
                                            <div class="flex items-center gap-2">
                                                <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                                    <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                                </div>
                                                <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Sin asignar</p>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Movimiento --}}
                                    <td class="px-8 py-6">
                                        @php
                                            $mov = match($item->status) {
                                                'deployed'    => ['label' => 'Alta / Activo', 'class' => 'bg-emerald-600/20 text-emerald-300 border-emerald-500/30'],
                                                'in-stock'    => ['label' => 'En Bodega', 'class' => 'bg-blue-600/20 text-blue-300 border-blue-500/30'],
                                                'retired'     => ['label' => 'Baja',      'class' => 'bg-red-600/20 text-red-300 border-red-500/30'],
                                                'repair'      => ['label' => 'Reparación','class' => 'bg-amber-600/20 text-amber-300 border-amber-500/30'],
                                                default       => ['label' => $item->status, 'class' => 'bg-white/10 text-gray-400 border-white/10'],
                                            };
                                        @endphp
                                        <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest border {{ $mov['class'] }}">
                                            {{ $mov['label'] }}
                                        </span>
                                    </td>

                                    {{-- Ubicación --}}
                                    <td class="px-8 py-6">
                                        <p class="text-xs font-bold text-gray-400 uppercase tracking-tight">
                                            @if($item->maquina)
                                                {{ $item->maquina->nombre }}
                                            @elseif($item->usuario)
                                                Asignación Personal
                                            @else
                                                Bodega Principal
                                            @endif
                                        </p>
                                        @if($item->installed_at)
                                            <p class="text-[9px] font-bold text-gray-600 mt-0.5">Desde: {{ \Carbon\Carbon::parse($item->installed_at)->format('d/m/Y') }}</p>
                                        @endif
                                    </td>
                                    <td class="px-8 py-6 text-xs text-gray-500 font-bold">
                                        {{ $item->created_at ? $item->created_at->format('d/m/Y') : '—' }}
                                    </td>

                                    {{-- Acciones --}}
                                    <td class="px-8 py-6 text-right">
                                        <div class="flex items-center justify-end gap-3 transition-all">
                                            {{-- Documento --}}
                                            @if($item->pdf_path)
                                                <button wire:click="$dispatch('viewDocument', { uniqueId: 'eq_{{ $item->id }}' })" title="Ver Responsiva en Plataforma" class="w-8 h-8 rounded-xl bg-teal-600/10 border border-teal-500/20 text-teal-400 hover:bg-teal-600 hover:text-white transition-all flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/></svg>
                                                </button>
                                                <button wire:click="downloadPdf({{ $item->id }})" title="Descargar Responsiva/Contrato" class="w-8 h-8 rounded-xl bg-blue-600/10 border border-blue-500/20 text-blue-400 hover:bg-blue-600 hover:text-white transition-all flex items-center justify-center">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/></svg>
                                                </button>
                                            @endif
                                            <button onclick="document.getElementById('pdf-upload-{{ $item->id }}').click()" title="{{ $item->pdf_path ? 'Reemplazar PDF' : 'Subir Responsiva/Contrato' }}" class="w-8 h-8 rounded-xl {{ $item->pdf_path ? 'bg-white/5 text-gray-400 border-white/10' : 'bg-indigo-600/10 border border-indigo-500/20 text-indigo-400 hover:bg-indigo-600 hover:text-white' }} transition-all flex items-center justify-center relative">
                                                <div wire:loading wire:target="pdfFiles.{{ $item->id }}" class="absolute inset-0 bg-indigo-600 rounded-xl flex items-center justify-center">
                                                    <svg class="w-4 h-4 text-white animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                                </div>
                                                <svg wire:loading.remove wire:target="pdfFiles.{{ $item->id }}" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-8l-4-4m0 0L8 8m4-4v12"/></svg>
                                            </button>
                                            <input type="file" id="pdf-upload-{{ $item->id }}" class="hidden" wire:model="pdfFiles.{{ $item->id }}" accept=".pdf">

                                            {{-- Editar --}}
                                            <button wire:click="editEquipment({{ $item->id }})" title="Editar" class="w-8 h-8 rounded-xl bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white transition-all flex items-center justify-center border border-white/10">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"/></svg>
                                            </button>
                                            {{-- Eliminar --}}
                                            <button wire:click="deleteEquipment({{ $item->id }})" wire:confirm="¿Estás seguro de que deseas eliminar este equipo del inventario?" title="Eliminar" class="w-8 h-8 rounded-xl bg-red-600/10 border border-red-500/20 text-red-500 hover:bg-red-600 hover:text-white transition-all flex items-center justify-center">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="6" class="px-10 py-24 text-center">
                                        <div class="flex flex-col items-center opacity-30">
                                            <svg class="w-16 h-16 text-gray-300 mb-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                                            <p class="text-sm font-black uppercase text-gray-500 tracking-[0.3em]">Sin Equipos Registrados</p>
                                        </div>
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            @endif

        </div>
    </div>

    {{-- MODAL 1: INGRESAR MATERIAL (Stock de Bodega) --}}
    @if($showAddMaterialModal)
    <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md animate-in fade-in duration-300">
        <div class="bg-[#0b0b1e]/95 border border-white/10 rounded-[2rem] w-full max-w-lg p-8 shadow-2xl relative overflow-hidden backdrop-blur-3xl animate-in zoom-in-95 duration-200">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/20 blur-[50px] rounded-full"></div>
            
            <h4 class="text-xl font-black text-white uppercase tracking-wider mb-6 border-l-4 border-indigo-600 pl-3">Ingreso Manual de Nuevo Material</h4>

            <form wire:submit.prevent="addMaterial" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Nombre del Equipo o Material *</label>
                    <input type="text" wire:model="newMaterialModel" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all" placeholder="Ej. Mouse Ergonómico Logitech MX Master" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Categoría</label>
                    <div class="relative">
                        <select wire:model.live="newMaterialCategory" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all appearance-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="Otro">Otro...</option>
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                @if($newMaterialCategory === 'Otro')
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Especifique Categoría</label>
                    <input type="text" wire:model="newMaterialCustomCategory" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all" placeholder="Ej. Diadema Telefónica" required>
                    @error('newMaterialCustomCategory') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2" title="Límite mínimo (semáforo Rojo)">Límite Mín. 💡</label>
                        <input type="number" min="0" wire:model.live="newMaterialMin" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-rose-400 font-bold focus:outline-none focus:border-rose-500/50 transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Stock Actual</label>
                        <input type="number" min="0" wire:model.live="newMaterialQuantity" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white font-bold focus:outline-none focus:border-indigo-500/50 transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2" title="Capacidad de estante">Límite Máx. 💡</label>
                        <input type="number" min="1" wire:model.live="newMaterialMax" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-emerald-400 font-bold focus:outline-none focus:border-emerald-500/50 transition-all" required>
                    </div>
                </div>

                {{-- Real-time Status Preview --}}
                @php
                    $previewStatus = $this->getStockStatus($newMaterialQuantity, $newMaterialMin, $newMaterialMax);
                @endphp
                <div class="p-4 bg-white/[0.02] border border-white/5 rounded-xl space-y-2">
                    <span class="text-[8px] uppercase font-black text-gray-500 block">Vista previa del estado calculado</span>
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full {{ $previewStatus['bgClass'] }} animate-pulse"></span>
                        <div class="text-xs text-gray-300">
                            El equipo estará clasificado como <strong class="{{ $previewStatus['textClass'] }}">{{ $previewStatus['label'] }}</strong>.
                        </div>
                    </div>
                    <p class="text-[9px] text-gray-500 leading-normal">
                        * <strong>Rojo</strong> si es menor o igual al Mínimo.<br/>
                        * <strong>Amarillo</strong> si supera el Mínimo pero está por debajo del rango seguro.<br/>
                        * <strong>Verde</strong> si está bien abastecido.
                      </p>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Fecha de Adquisición</label>
                    <input type="date" wire:model="newMaterialAcquisitionDate" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Notas / Observaciones</label>
                    <textarea wire:model="newMaterialNotes" rows="2" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all placeholder:text-gray-600" placeholder="Observaciones de ingreso..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button type="button" wire:click="$set('showAddMaterialModal', false)" class="px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider text-gray-300 hover:bg-white/10 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition-all">
                        Ingresar Equipo
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL 2: ASIGNAR EQUIPO --}}
    @if($showAssignItemModal)
    <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md animate-in fade-in duration-300">
        <div class="bg-[#0b0b1e]/95 border border-white/10 rounded-[2rem] w-full max-w-lg p-8 shadow-2xl relative overflow-hidden backdrop-blur-3xl animate-in zoom-in-95 duration-200">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/20 blur-[50px] rounded-full"></div>
            
            <h4 class="text-xl font-black text-white uppercase tracking-wider mb-6 border-l-4 border-blue-600 pl-3">Asignar Equipo de Bodega</h4>

            <form wire:submit.prevent="assignItem" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Seleccione Material (Disponible)</label>
                    <div class="relative">
                        <select wire:model.live="newAssignmentStockKey" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all appearance-none">
                            <option value="">-- Seleccionar Equipo --</option>
                            @foreach($stockGrouped as $s)
                                <option value="{{ $s->type }}|{{ $s->model }}">{{ $s->type }} - {{ $s->model }} ({{ $s->quantity }} disponibles)</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    @error('newAssignmentStockKey') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Destinatario</label>
                        <div class="flex bg-white/[0.04] p-1.5 border border-white/10 rounded-xl gap-1">
                            <button type="button" wire:click="changeNewAssignmentTargetType('Estación')" class="flex-1 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all {{ $newAssignmentTargetType === 'Estación' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                                Estación
                            </button>
                            <button type="button" wire:click="changeNewAssignmentTargetType('Usuario')" class="flex-1 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all {{ $newAssignmentTargetType === 'Usuario' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                                Usuario
                            </button>
                        </div>
                    </div>
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Cantidad a Asignar</label>
                        <input type="number" min="1" wire:model="newAssignmentQuantity" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                        @error('newAssignmentQuantity') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Ubicación / Asignado a</label>
                    <div class="relative">
                        <select wire:model="newAssignmentTargetId" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all appearance-none">
                            <option value="">-- Seleccionar Destinatario --</option>
                            @if($newAssignmentTargetType === 'Estación')
                                @foreach($maquinas as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                @endforeach
                            @else
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                                @endforeach
                            @endif
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    @error('newAssignmentTargetId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Fecha de Asignación / Entrega</label>
                    <input type="date" wire:model="newAssignmentDate" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Notas de Asignación</label>
                    <textarea wire:model="newAssignmentNotes" rows="3" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all placeholder:text-gray-600" placeholder="Observaciones o notas de la entrega..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button type="button" wire:click="$set('showAssignItemModal', false)" class="px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider text-gray-300 hover:bg-white/10 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-lg shadow-blue-600/30 transition-all">
                        Asignar y Entregar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL 2.5: EDITAR ASIGNACION --}}
    @if($showEditAssignmentModal)
    <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md animate-in fade-in duration-300">
        <div class="bg-[#0b0b1e]/95 border border-white/10 rounded-[2rem] w-full max-w-lg p-8 shadow-2xl relative overflow-hidden backdrop-blur-3xl animate-in zoom-in-95 duration-200">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/20 blur-[50px] rounded-full"></div>
            
            <h4 class="text-xl font-black text-white uppercase tracking-wider mb-6 border-l-4 border-blue-600 pl-3">Editar Asignación</h4>

            <form wire:submit.prevent="saveEditAssignment" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Destinatario</label>
                    <div class="flex bg-white/[0.04] p-1.5 border border-white/10 rounded-xl gap-1">
                        <button type="button" wire:click="changeEditAssignmentTargetType('Estación')" class="flex-1 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all {{ $editAssignmentTargetType === 'Estación' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                            Estación
                        </button>
                        <button type="button" wire:click="changeEditAssignmentTargetType('Usuario')" class="flex-1 py-2 text-[10px] font-black uppercase tracking-wider rounded-lg transition-all {{ $editAssignmentTargetType === 'Usuario' ? 'bg-blue-600 text-white shadow-md' : 'text-gray-400 hover:text-white' }}">
                            Usuario
                        </button>
                    </div>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Ubicación / Asignado a</label>
                    <div class="relative">
                        <select wire:model="editAssignmentTargetId" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all appearance-none">
                            <option value="">-- Seleccionar Destinatario --</option>
                            @if($editAssignmentTargetType === 'Estación')
                                @foreach($maquinas as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                @endforeach
                            @else
                                @foreach($usuarios as $u)
                                    <option value="{{ $u->id }}">{{ $u->name }} ({{ $u->role }})</option>
                                @endforeach
                            @endif
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                    @error('editAssignmentTargetId') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Fecha de Asignación / Entrega</label>
                    <input type="date" wire:model="editAssignmentDate" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Notas de Asignación</label>
                    <textarea wire:model="editAssignmentNotes" rows="3" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all placeholder:text-gray-600" placeholder="Observaciones o notas de la entrega..."></textarea>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button type="button" wire:click="$set('showEditAssignmentModal', false)" class="px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider text-gray-300 hover:bg-white/10 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-lg shadow-blue-600/30 transition-all">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL 3: EDITAR EQUIPO (Original modal) --}}
    @if($showingEditModal)
        <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md animate-in fade-in duration-300">
            <div class="bg-[#0b0b1e]/95 border border-white/10 rounded-[2rem] w-full max-w-lg p-8 shadow-2xl relative overflow-hidden backdrop-blur-3xl animate-in zoom-in-95 duration-200">
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-blue-600/20 blur-[50px] rounded-full"></div>
                
                <h4 class="text-xl font-black text-white uppercase tracking-wider mb-6 border-l-4 border-blue-600 pl-3">Editar Equipo</h4>

                <form wire:submit.prevent="updateEquipment" class="space-y-6">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Nombre del Equipo</label>
                        <input type="text" wire:model="editName" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Tipo</label>
                            <input type="text" wire:model="editType" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Estado</label>
                            <div class="relative" x-data="{ 
                                open: false, 
                                selected: @entangle('editStatus'),
                                get selectedLabel() {
                                    return {
                                        'deployed': 'Alta (Activo)',
                                        'in-stock': 'En Bodega',
                                        'repair': 'Reparación',
                                        'retired': 'Baja'
                                    }[this.selected] || 'Seleccionar Estado';
                                }
                            }">
                                <!-- Trigger Button -->
                                <button type="button" @click="open = !open" @click.away="open = false" class="w-full flex items-center justify-between bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all">
                                    <span x-text="selectedLabel"></span>
                                    <svg class="w-4 h-4 text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>

                                <!-- Dropdown List -->
                                <div x-show="open" x-transition class="absolute z-[1050] w-full mt-2 bg-[#0b0b1e]/98 border border-white/10 rounded-xl shadow-2xl overflow-hidden backdrop-blur-3xl">
                                    <button type="button" @click="selected = 'deployed'; open = false" class="w-full text-left px-4 py-3 text-sm text-white hover:bg-blue-600/20 hover:text-blue-400 transition-all">Alta (Activo)</button>
                                    <button type="button" @click="selected = 'in-stock'; open = false" class="w-full text-left px-4 py-3 text-sm text-white hover:bg-blue-600/20 hover:text-blue-400 transition-all">En Bodega</button>
                                    <button type="button" @click="selected = 'repair'; open = false" class="w-full text-left px-4 py-3 text-sm text-white hover:bg-blue-600/20 hover:text-blue-400 transition-all">Reparación</button>
                                    <button type="button" @click="selected = 'retired'; open = false" class="w-full text-left px-4 py-3 text-sm text-white hover:bg-blue-600/20 hover:text-blue-400 transition-all">Baja</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Modelo</label>
                            <input type="text" wire:model="editModel" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                        </div>
                        <div>
                            <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Código / S/N</label>
                            <input type="text" wire:model="editBarcode" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Estación Asignada / Ubicación</label>
                        <div class="relative">
                            <select wire:model="editMaquinaId" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-blue-500/50 transition-all appearance-none">
                                <option value="">-- Bodega Principal (Sin Asignar) --</option>
                                @foreach($maquinas as $m)
                                    <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="flex items-center justify-end gap-3 mt-8">
                        <button type="button" wire:click="$set('showingEditModal', false)" class="px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider text-gray-300 hover:bg-white/10 transition-all">
                            Cancelar
                        </button>
                        <button type="submit" class="px-6 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-lg shadow-blue-600/30 transition-all">
                            Guardar Cambios
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- MODAL: EDITAR MATERIAL EN BODEGA --}}
    @if($showEditMaterialModal)
    <div class="fixed inset-0 z-[1000] flex items-center justify-center p-4 bg-black/70 backdrop-blur-md animate-in fade-in duration-300">
        <div class="bg-[#0b0b1e]/95 border border-white/10 rounded-[2rem] w-full max-w-lg p-8 shadow-2xl relative overflow-hidden backdrop-blur-3xl animate-in zoom-in-95 duration-200">
            <div class="absolute -top-10 -right-10 w-40 h-40 bg-indigo-600/20 blur-[50px] rounded-full"></div>
            
            <h4 class="text-xl font-black text-white uppercase tracking-wider mb-6 border-l-4 border-indigo-600 pl-3">Editar Parámetros de Stock</h4>

            <form wire:submit.prevent="saveEditMaterial" class="space-y-5">
                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Nombre del Equipo o Material *</label>
                    <input type="text" wire:model="editNewModel" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all" required>
                </div>

                <div>
                    <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Categoría</label>
                    <div class="relative">
                        <select wire:model="editNewType" class="w-full bg-[#0b0b1e] border border-white/10 rounded-xl px-4 py-3 text-sm text-white focus:outline-none focus:border-indigo-500/50 transition-all appearance-none">
                            @foreach($categories as $cat)
                                <option value="{{ $cat }}">{{ $cat }}</option>
                            @endforeach
                            <option value="Otro">Otro...</option>
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="grid grid-cols-3 gap-4">
                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2" title="Límite mínimo (semáforo Rojo)">Límite Mín. 💡</label>
                        <input type="number" min="0" wire:model.live="editNewMin" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-rose-400 font-bold focus:outline-none focus:border-rose-500/50 transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2">Stock Actual</label>
                        <input type="number" min="0" wire:model.live="editNewQuantity" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-white font-bold focus:outline-none focus:border-indigo-500/50 transition-all" required>
                    </div>

                    <div>
                        <label class="block text-[10px] font-black text-gray-500 uppercase tracking-widest mb-2" title="Capacidad de estante">Límite Máx. 💡</label>
                        <input type="number" min="1" wire:model.live="editNewMax" class="w-full bg-white/[0.04] border border-white/10 rounded-xl px-4 py-3 text-sm text-emerald-400 font-bold focus:outline-none focus:border-emerald-500/50 transition-all" required>
                    </div>
                </div>

                {{-- Status Preview --}}
                @php
                    $editPreviewStatus = $this->getStockStatus($editNewQuantity, $editNewMin, $editNewMax);
                @endphp
                <div class="p-4 bg-white/[0.02] border border-white/5 rounded-xl space-y-2">
                    <span class="text-[8px] uppercase font-black text-gray-500 block">Vista previa del estado calculado</span>
                    <div class="flex items-center gap-2.5">
                        <span class="w-2.5 h-2.5 rounded-full {{ $editPreviewStatus['bgClass'] }} animate-pulse"></span>
                        <div class="text-xs text-gray-300">
                            El equipo estará clasificado como <strong class="{{ $editPreviewStatus['textClass'] }}">{{ $editPreviewStatus['label'] }}</strong>.
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 mt-8">
                    <button type="button" wire:click="$set('showEditMaterialModal', false)" class="px-6 py-3 bg-white/5 border border-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider text-gray-300 hover:bg-white/10 transition-all">
                        Cancelar
                    </button>
                    <button type="submit" class="px-6 py-3 bg-indigo-600 hover:bg-indigo-500 text-white rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-lg shadow-indigo-600/30 transition-all">
                        Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif
</div>
