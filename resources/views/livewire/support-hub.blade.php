<div class="flex h-screen overflow-hidden bg-transparent text-gray-200 selection:bg-blue-500/30 selection:text-white" 
     style="font-family: 'Inter', 'Figtree', sans-serif;"
     x-data="{ 
        sidebarOpen: false, 
        activeTab: @entangle('activeTab'),
        sendingTicket: false,
        showSuccessScreen: false,
        chatListSidebarOpen: false
     }"
     @resize.window="if(window.innerWidth < 768) sidebarOpen = false;"
     wire:poll.5s
     @play-notification-sound.window="playChime()"
     @show-sending-overlay.window="sendingTicket = true;"
     @hide-sending-overlay.window="sendingTicket = false;"
     @ticket-created.window="showSuccessScreen = true; sendingTicket = false;">
    
    {{-- Scripts for Charts --}}

    {{-- Estilos Globales para Partículas --}}
    <style>
        .transition-particle {
            position: absolute;
            border-radius: 50%;
            background: white;
            pointer-events: none;
            opacity: 0;
            z-index: 2;
            box-shadow: 0 0 4px rgba(255, 255, 255, 0.8);
        }
        @keyframes floatParticle {
            0% { opacity: 0; transform: translateY(0); }
            20% { opacity: 0.6; }
            80% { opacity: 0.6; }
            100% { opacity: 0; transform: translateY(-30px); }
        }
        @keyframes lightPulse {
            0%, 100% { opacity: 0.8; transform: scale(1); }
            50% { opacity: 1; transform: scale(1.1); }
        }
        .glow-background {
            position: absolute;
            inset: 0;
            background: radial-gradient(circle at center, rgba(130, 170, 255, 0.35) 0%, rgba(80, 120, 220, 0.2) 40%, rgba(40, 60, 100, 0.05) 75%, transparent 100%);
            animation: lightPulse 8s ease-in-out infinite;
        }
    </style>

    {{-- Fondo Espacial Global --}}
    <div class="fixed inset-0 z-[-1] bg-[#020205]">
        <div class="glow-background"></div>
        <div id="particleContainer" class="absolute inset-0 pointer-events-none overflow-hidden"></div>
    </div>

    {{-- SIDEBAR --}}
    <aside 
        @mouseenter="if(window.innerWidth >= 768) sidebarOpen = true"
        @mouseleave="if(window.innerWidth >= 768) sidebarOpen = false"
        :class="sidebarOpen ? 'translate-x-0 !w-72' : '-translate-x-full md:translate-x-0 md:!w-[72px]'" 
        class="absolute md:relative z-[100] md:z-20 h-full w-72 bg-[#07071a]/98 backdrop-blur-3xl border-r border-white/5 transition-all duration-300 flex flex-col shadow-[4px_0_30px_rgba(0,0,0,0.8)]"
    >
        <div class="flex items-center gap-3 px-4 py-5 shrink-0" :class="sidebarOpen ? 'px-5' : 'justify-center'">
            <div class="w-10 h-10 rounded-xl shadow-[0_0_20px_rgba(37,99,235,0.5)] flex items-center justify-center bg-blue-600 border border-white/20 shrink-0">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 10V3L4 14h7v7l9-11h-7z" /></svg>
            </div>
            <div x-show="sidebarOpen" x-transition:enter="transition ease-out duration-200" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">
                <h1 class="text-lg font-black tracking-tighter text-white uppercase leading-none">Support<span class="text-blue-400">Hub</span></h1>
                <p class="text-[8px] font-bold text-blue-400/50 uppercase tracking-[0.3em] mt-0.5">Galaxy Infrastructure</p>
            </div>
        </div>
        
        <nav class="flex-1 px-2 py-4 space-y-1 overflow-y-auto">
            @if(auth()->user()->role === 'user')
                {{-- Rutas de usuario normal --}}
                <button @click="activeTab = 'inicio'"
                    :class="activeTab === 'inicio' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-house group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Inicio</span>
                </button>
                <button @click="activeTab = 'generar_ticket'"
                    :class="activeTab === 'generar_ticket' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-plus group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Generar Ticket</span>
                </button>
                <button @click="activeTab = 'mis_tickets'"
                    :class="activeTab === 'mis_tickets' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-ticket group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Mis Tickets</span>
                </button>
                <button @click="activeTab = 'gestion_archivos'"
                    :class="activeTab === 'gestion_archivos' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-folder-open group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Gestión de Archivos</span>
                </button>
            @else
                {{-- Rutas de Admin/Agente --}}
                <div x-show="sidebarOpen" class="px-4 py-2 mt-2">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Menu</p>
                </div>
                <button @click="activeTab = 'inicio'"
                    :class="activeTab === 'inicio' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-gauge-high group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Dashboard</span>
                </button>
                <button @click="activeTab = 'generar_ticket'"
                    :class="activeTab === 'generar_ticket' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-plus group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Crear Ticket</span>
                </button>
                @if(auth()->user()->role === 'gestor')
                <button @click="activeTab = 'mis_tickets'"
                    :class="activeTab === 'mis_tickets' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-ticket group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Mis Tickets</span>
                </button>
                @endif
                
                @if(auth()->user()->role !== 'gestor')
                {{-- Dropdown TICKETS --}}
                <div x-data="{ ticketsOpen: false }">
                    <button @click="ticketsOpen = !ticketsOpen; if(!sidebarOpen) sidebarOpen = true;"
                        :class="['tickets', 'causas', 'motivos', 'historial'].includes(activeTab) ? 'bg-white/5 text-white' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                        class="w-full flex items-center rounded-xl transition-all duration-200 group"
                        :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                        <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                            <i class="fa-solid fa-ticket group-hover:scale-110 transition-transform text-lg"></i>
                        </div>
                        <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap flex-1 text-left">TICKETS</span>
                        <svg x-show="sidebarOpen" :class="{'rotate-180': ticketsOpen}" class="w-3.5 h-3.5 transition-transform duration-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div x-show="sidebarOpen && ticketsOpen" x-collapse.duration.200ms class="pl-11 pr-4 py-1 space-y-1">
                        <button @click="activeTab = 'tickets'" :class="activeTab === 'tickets' ? 'text-blue-400' : 'text-gray-400 hover:text-white'" class="w-full text-left text-[10px] font-bold uppercase tracking-wider py-2 transition-colors flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full" :class="activeTab === 'tickets' ? 'bg-blue-400' : 'bg-gray-600'"></span> Panel General
                        </button>
                        <button @click="activeTab = 'historial'" :class="activeTab === 'historial' ? 'text-blue-400' : 'text-gray-400 hover:text-white'" class="w-full text-left text-[10px] font-bold uppercase tracking-wider py-2 transition-colors flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full" :class="activeTab === 'historial' ? 'bg-blue-400' : 'bg-gray-600'"></span> Historial
                        </button>
                        <button @click="activeTab = 'causas'" :class="activeTab === 'causas' ? 'text-blue-400' : 'text-gray-400 hover:text-white'" class="w-full text-left text-[10px] font-bold uppercase tracking-wider py-2 transition-colors flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full" :class="activeTab === 'causas' ? 'bg-blue-400' : 'bg-gray-600'"></span> Causas de Solución
                        </button>
                        <button @click="activeTab = 'motivos'" :class="activeTab === 'motivos' ? 'text-blue-400' : 'text-gray-400 hover:text-white'" class="w-full text-left text-[10px] font-bold uppercase tracking-wider py-2 transition-colors flex items-center gap-2">
                            <span class="w-1.5 h-1.5 rounded-full" :class="activeTab === 'motivos' ? 'bg-blue-400' : 'bg-gray-600'"></span> Motivos de Cancelación
                        </button>
                    </div>
                </div>

                <button @click="activeTab = 'statistics'"
                    :class="activeTab === 'statistics' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-chart-pie group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Reporte tickets</span>
                </button>
                @endif
                
            @endif
            @if(auth()->user()->role === 'admin')
                <button @click="activeTab = 'users'"
                    :class="activeTab === 'users' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-users group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Usuarios</span>
                </button>
            @endif
            @if(auth()->user()->role !== 'user')
                
                {{-- Legacy Tools (Kept as requested) --}}
                <div x-show="sidebarOpen" class="px-4 py-2 mt-2 border-t border-white/5 pt-4">
                    <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Herramientas</p>
                </div>
                @if(in_array(auth()->user()->role, ['admin', 'gestor']))
                <button @click="activeTab = 'inventory'"
                    :class="activeTab === 'inventory' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-boxes-stacked group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Inventario</span>
                </button>
                @endif
                <button @click="activeTab = 'gestion_archivos'"
                    :class="activeTab === 'gestion_archivos' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-folder-open group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Gestión de Archivos</span>
                </button>
                <button @click="activeTab = 'map'"
                    :class="activeTab === 'map' ? 'bg-gradient-to-r from-blue-600 to-cyan-600 text-white shadow-[0_0_25px_rgba(59,130,246,0.5)] ring-1 ring-cyan-400/50' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <i class="fa-solid fa-map-location-dot group-hover:scale-110 transition-transform text-lg"></i>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Infra Map</span>
                </button>
            @endif
        </nav>

        {{-- USER PROFILE FOOTER --}}
        <div class="shrink-0 px-2 pb-4" x-data="{ profileMenuOpen: false }">
            <div class="rounded-2xl bg-white/[0.04] border border-white/[0.06] transition-all overflow-hidden">
                <div class="flex items-center cursor-pointer hover:bg-white/[0.04] transition-all p-3"
                     :class="sidebarOpen ? 'gap-3' : 'justify-center'"
                     @click="sidebarOpen && (profileMenuOpen = !profileMenuOpen)">
                    <div class="w-9 h-9 rounded-xl bg-[#0b0c16] border border-white/10 shrink-0 shadow-[0_0_12px_rgba(37,99,235,0.5)] overflow-hidden">
                        <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover">
                    </div>
                    <div x-show="sidebarOpen" class="min-w-0 flex-1">
                        <p class="text-xs font-bold truncate text-white tracking-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-gray-500 truncate tracking-widest uppercase mt-0.5">
                            {{ auth()->user()->role === 'user' ? 'Operativo' : (auth()->user()->role === 'admin' ? 'Administrador' : (auth()->user()->role === 'gestor' ? 'Gestor de Stocks' : 'Agente TI')) }}
                        </p>
                    </div>
                    <svg x-show="sidebarOpen" class="w-3.5 h-3.5 text-gray-600 transition-transform duration-200 shrink-0" :class="profileMenuOpen ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7" /></svg>
                </div>
                <div x-show="sidebarOpen && profileMenuOpen" x-collapse.duration.200ms>
                    <div class="border-t border-white/[0.06] py-1 px-1">
                        @if(auth()->user()->role !== 'user')
                        <button @click="activeTab = 'settings'; profileMenuOpen = false" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[10px] font-semibold uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" /></svg>
                            Configuración
                        </button>
                        @endif
                        <button @click="activeTab = 'mi_perfil'; profileMenuOpen = false" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[10px] font-semibold uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all w-full">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Mi Perfil
                        </button>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[10px] font-semibold uppercase tracking-widest text-red-400 hover:bg-red-500/10 hover:text-red-300 transition-all">
                                <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" /></svg>
                                Salir
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </aside>

    {{-- Overlay on mobile when sidebar is open --}}
    <div x-show="sidebarOpen" @click="if(window.innerWidth < 768) sidebarOpen = false" class="md:hidden fixed inset-0 bg-black/60 z-[90] animate-in fade-in duration-300"></div>

    {{-- Main Area --}}
    <main class="flex-1 flex flex-col relative z-10 overflow-hidden w-full min-w-0">
        
        <header class="h-16 sm:h-20 px-4 sm:px-6 md:px-10 border-b border-white/5 flex items-center justify-between bg-[#050510]/60 backdrop-blur-3xl shrink-0 z-50 relative">
            <div class="flex items-center gap-3 min-w-0">
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden p-2 bg-white/5 hover:bg-white/10 rounded-xl text-white transition-all shrink-0">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 6h16M4 12h16M4 18h16" /></svg>
                </button>
                <div class="min-w-0">
                    <h2 class="text-base sm:text-xl md:text-2xl font-black text-white tracking-tighter uppercase leading-none truncate" x-text="activeTab.replace(/_/g, ' ').toUpperCase()"></h2>
                    <p class="text-[10px] text-gray-500 font-medium mt-0.5 truncate" x-show="activeTab === 'generar_ticket'">Configura los detalles de tu requerimiento técnico</p>
                    <p class="text-[10px] text-gray-500 font-medium mt-0.5 truncate" x-show="activeTab === 'inicio'">Bienvenido al panel general de CGR Connect</p>
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 shrink-0" x-data="{ notifOpen: false }">
                
                {{-- CHAT NOTIFICATION --}}
                <div class="relative">
                    @if(auth()->user()->role === 'user')
                    <button wire:click="openAiChat" @click="chatListSidebarOpen = !chatListSidebarOpen"
                            class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-600/10 border border-purple-500/20 flex items-center justify-center hover:bg-purple-600/20 active:scale-95 transition-all relative">
                    @else
                    <button @click="$wire.closeChatWidget(); chatListSidebarOpen = !chatListSidebarOpen"
                            class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-purple-600/10 border border-purple-500/20 flex items-center justify-center hover:bg-purple-600/20 active:scale-95 transition-all relative">
                    @endif
                        <svg class="w-5 h-5 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                        @if(count($chatNotificationsList) > 0 && auth()->user()->role !== 'user')
                            <span class="absolute top-1.5 right-1.5 w-4 h-4 bg-purple-500 text-white flex items-center justify-center text-[8px] font-bold rounded-full ring-2 ring-[#050510] animate-pulse">{{ count($chatNotificationsList) }}</span>
                        @endif
                    </button>
                </div>

                {{-- NOTIFICATION BELL --}}
                <div class="relative">
                    <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                            class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-white/5 border border-white/8 flex items-center justify-center hover:bg-white/10 active:scale-95 transition-all relative">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        @if(count($notificationsList) > 0)
                            <span class="absolute top-1.5 right-1.5 w-2 h-2 bg-blue-500 rounded-full ring-2 ring-[#050510] animate-pulse"></span>
                        @endif
                    </button>

                    {{-- DROPDOWN PANEL --}}
                    <div x-show="notifOpen"
                         x-transition:enter="transition ease-out duration-200"
                         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
                         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
                         x-transition:leave="transition ease-in duration-150"
                         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
                         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
                         class="absolute right-0 top-full mt-3 w-[340px] rounded-2xl border border-white/10 shadow-[0_20px_60px_rgba(0,0,0,0.8)] z-[200] overflow-hidden"
                         style="background: rgba(8, 8, 22, 0.97); backdrop-filter: blur(30px);">

                        <div class="flex items-center justify-between px-5 py-4 border-b border-white/8">
                            <h4 class="text-sm font-black text-white tracking-tight">Notificaciones</h4>
                            @if(count($notificationsList) > 0)
                                <button wire:click="clearAllNotifications" class="text-[10px] font-black text-rose-400 hover:text-rose-300 uppercase tracking-wider transition-colors flex items-center gap-1 focus:outline-none">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                    Borrar Todo
                                </button>
                            @else
                                <span class="text-[9px] font-black text-gray-500 uppercase tracking-widest">Recientes</span>
                            @endif
                        </div>

                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                            @forelse($notificationsList as $notif)
                                <div class="flex items-center justify-between gap-2 px-4 py-3 hover:bg-white/5 transition-colors border-b border-white/5 bg-blue-500/5 group relative">
                                    {{-- Main clickable area --}}
                                    <div wire:click="viewNotificationTicket({{ $notif['id'] }}); notifOpen = false" class="flex gap-3 min-w-0 flex-1 cursor-pointer">
                                        <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0 bg-blue-600/20">
                                            <span>{{ $notif['icon'] }}</span>
                                        </div>
                                        <div class="min-w-0 flex-1">
                                            <p class="text-xs font-bold text-white truncate">{{ $notif['title'] }}</p>
                                            <p class="text-[11px] text-gray-300 mt-0.5 leading-relaxed truncate">{{ $notif['msg'] }}</p>
                                            <p class="text-[10px] text-gray-500 mt-1 font-medium">{{ $notif['time'] }}</p>
                                        </div>
                                    </div>
                                    
                                    {{-- Delete/dismiss button --}}
                                    <button wire:click.stop="dismissNotification({{ $notif['id'] }})"
                                            class="w-7 h-7 rounded-lg flex items-center justify-center text-gray-500 hover:text-rose-400 hover:bg-rose-500/10 transition-colors focus:outline-none shrink-0"
                                            title="Eliminar notificación">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                                    </button>
                                </div>
                            @empty
                                <div class="px-5 py-8 text-center text-gray-500 text-xs font-bold uppercase tracking-wider">
                                    Sin notificaciones recientes
                                </div>
                            @endforelse
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto overflow-x-auto p-3 sm:p-5 md:p-8 lg:p-10 space-y-6 md:space-y-10">
            


            <template x-if="activeTab === 'tickets'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-6 md:space-y-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-xl md:text-3xl font-black text-white uppercase tracking-tighter">Control de Incidencias</h3>
                        <button wire:click="$set('showingNewTicket', true)" class="bg-teal-600 hover:bg-teal-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[.25em] shadow-lg transition-all self-start md:self-auto">
                            + Generar Ticket
                        </button>
                    </div>

                    {{-- Filters control bar --}}
                    <div :class="chatListSidebarOpen || ($wire.chatWidgetTicketId && !$wire.isChatWidgetMinimized) ? 'flex-col items-stretch' : 'flex-col xl:flex-row items-stretch xl:items-center'" class="flex gap-5 justify-between bg-[#101026]/60 backdrop-blur-2xl rounded-2xl p-5 border border-white/5">
                        {{-- Status buttons --}}
                        <div class="flex flex-wrap items-center gap-2">
                            <button wire:click="$set('statusFilter', 'Todos')" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border {{ $statusFilter === 'Todos' ? 'bg-blue-600 border-blue-500/20 text-white shadow-lg' : 'bg-white/5 border-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }} focus:outline-none">
                                Todos
                            </button>
                            <button wire:click="$set('statusFilter', 'Abierto')" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border flex items-center gap-1.5 {{ $statusFilter === 'Abierto' ? 'bg-blue-600 border-blue-500/20 text-white shadow-lg' : 'bg-white/5 border-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }} focus:outline-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-blue-400"></span> Abiertos
                            </button>
                            <button wire:click="$set('statusFilter', 'En Proceso')" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border flex items-center gap-1.5 {{ $statusFilter === 'En Proceso' ? 'bg-yellow-600 border-yellow-500/20 text-white shadow-lg' : 'bg-white/5 border-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }} focus:outline-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span> En Proceso
                            </button>
                        </div>

                        {{-- Calendar Picker filter --}}
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-wider shrink-0">Filtrar por Día:</label>
                                <div class="relative">
                                    <input type="date" wire:model.live="dateFilter" class="bg-[#0b0b1e]/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all font-bold select-none" />
                                </div>
                            </div>
                            <div class="flex items-center gap-2 ml-2">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-wider shrink-0">Planta:</label>
                                <select wire:model.live="plantaFilter" class="bg-[#0b0b1e]/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all font-bold select-none appearance-none">
                                    <option value="">Todas</option>
                                    <option value="1">Planta 1</option>
                                    <option value="2">Planta 2</option>
                                </select>
                            </div>
                            @if($statusFilter !== 'Todos' || $dateFilter || $plantaFilter)
                                <button wire:click="$set('statusFilter', 'Todos'); $set('dateFilter', ''); $set('plantaFilter', '');" class="text-[9px] font-black text-rose-400 hover:text-rose-300 uppercase tracking-widest transition-colors focus:outline-none">
                                    Limpiar Filtros
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Tickets Table... --}}
                    <div wire:poll.15s class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[1.5rem] md:rounded-[3rem] overflow-x-auto shadow-2xl">
                        <table class="w-full text-left border-collapse min-w-[800px] md:min-w-0" style="table-layout: auto;">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Incidencia</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Prioridad</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Asignado A</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Estado</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Hora</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.03]">
                                @forelse($tickets as $ticket)
                                <tr class="group hover:bg-white/[0.03] transition-all">
                                    <td class="px-4 md:px-5 py-4 text-blue-500 font-black text-xs">#{{ $ticket->id }}</td>
                                    <td class="px-4 md:px-5 py-4">
                                        <p class="text-sm font-black text-white uppercase tracking-tight">{{ $ticket->titulo }}</p>
                                        @if($ticket->creador)
                                            <p class="text-[10px] font-bold text-gray-600 mt-1 uppercase">{{ $ticket->creador->name }}</p>
                                        @endif
                                        @if($ticket->hora_visita)
                                            <p class="mt-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">
                                                    🕒 Visita: {{ $ticket->hora_visita }}
                                                </span>
                                            </p>
                                        @endif
                                        @if($ticket->tiempo_resolucion)
                                            <p class="mt-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black bg-teal-500/10 text-teal-400 border border-teal-500/20 uppercase tracking-wider">
                                                    ⏱️ Resol: {{ $ticket->tiempo_resolucion }} min
                                                </span>
                                            </p>
                                        @endif
                                        @if($ticket->planta)
                                            <p class="mt-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase tracking-wider">
                                                    <i class="fa-solid fa-building"></i> Planta {{ $ticket->planta }}
                                                </span>
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        <span class="text-[10px] font-black uppercase text-gray-400">{{ $ticket->prioridad->nombre }}</span>
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        @if($ticket->agente)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center text-[9px] font-black uppercase border border-blue-500/20">
                                                    {{ substr($ticket->agente->nombre_completo, 0, 2) }}
                                                </div>
                                                <span class="text-xs font-bold text-gray-300 uppercase tracking-tight">{{ $ticket->agente->nombre_completo }}</span>
                                            </div>
                                        @else
                                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-600 bg-white/5 px-2.5 py-1 rounded-lg">Sin Asignar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        @php
                                            $estadoNombre = $ticket->estado->nombre;
                                            $estadoClass = match(true) {
                                                in_array($estadoNombre, ['Completado', 'Cerrado', 'Resuelto']) => 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/30',
                                                in_array($estadoNombre, ['Abierto', 'Nuevo']) => 'bg-blue-600/20 text-blue-300 border border-blue-500/30',
                                                in_array($estadoNombre, ['En Proceso', 'En progreso']) => 'bg-yellow-600/20 text-yellow-300 border border-yellow-500/30',
                                                default => 'bg-white/10 text-gray-300 border border-white/10'
                                            };
                                        @endphp
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $estadoClass }}">
                                            {{ $estadoNombre }}
                                        </span>
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        <span class="text-[10px] font-black uppercase text-gray-400 block">{{ $ticket->created_at->format('H:i') }}</span>
                                        <span class="text-[8px] font-bold text-gray-500 mt-1 block">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="px-4 md:px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            @if(in_array(auth()->user()->role, ['admin', 'agente']))
                                                <div class="flex gap-2">
                                                    <button wire:click.stop="openChatWidget({{ $ticket->id }})" class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-purple-600/20 text-purple-400 border border-purple-500/20 hover:bg-purple-600/40 transition-all flex items-center gap-1.5" title="Abrir Chat">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                        Chat
                                                    </button>
                                                    <button wire:click="viewAdminTicket({{ $ticket->id }})" class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-blue-600/20 text-blue-400 border border-blue-500/20 hover:bg-blue-600/40 transition-all flex items-center gap-1.5" title="Gestionar Ticket">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                        Gestionar
                                                    </button>
                                                    <button wire:click="deleteTicket({{ $ticket->id }})" wire:confirm="¿Estás seguro de eliminar este ticket? Se enviará a la papelera." class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-red-600/20 text-red-400 border border-red-500/20 hover:bg-red-600/40 transition-all flex items-center gap-1.5" title="Eliminar duplicado">
                                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                                    </button>
                                                </div>
                                                {{-- Mostrar Reabrir rápido en tabla si está finalizado --}}
                                                @if(in_array($ticket->estado->nombre, ['Completado', 'Cerrado', 'Resuelto']))
                                                    <button wire:click="reopenTicket('{{ $ticket->id }}')"
                                                            wire:confirm="¿Estás seguro de reabrir este ticket?"
                                                            class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-amber-600/20 text-amber-400 border border-amber-500/20 hover:bg-amber-600/40 transition-all flex items-center gap-1.5">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                        Reabrir
                                                    </button>
                                                @endif
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="5" class="p-10 text-center text-gray-500 font-bold uppercase text-[10px] tracking-widest">No hay tickets</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            {{-- Tab for Ticket History --}}
            <template x-if="activeTab === 'historial'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 max-w-7xl mx-auto space-y-8 w-full">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <div>
                            <h3 class="text-xl md:text-3xl font-black text-white uppercase tracking-tighter">Historial de Incidencias</h3>
                            <p class="text-xs text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Registro de tickets resueltos y cerrados</p>
                        </div>
                    </div>

                    {{-- Filters control bar --}}
                    <div class="flex flex-col xl:flex-row gap-5 items-stretch xl:items-center justify-between bg-[#101026]/60 backdrop-blur-2xl rounded-2xl p-5 border border-white/5">
                        {{-- Calendar Picker filter --}}
                        <div class="flex items-center gap-3">
                            <div class="flex items-center gap-2">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-wider shrink-0">Filtrar por Día:</label>
                                <input type="date" wire:model.live="dateFilter" class="bg-[#0b0b1e]/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all font-bold select-none" />
                            </div>
                            <div class="flex items-center gap-2 ml-2">
                                <label class="text-[9px] font-black text-gray-500 uppercase tracking-wider shrink-0">Planta:</label>
                                <select wire:model.live="plantaFilter" class="bg-[#0b0b1e]/60 border border-white/10 rounded-xl px-4 py-2.5 text-xs text-white focus:outline-none focus:border-blue-500 transition-all font-bold select-none appearance-none">
                                    <option value="">Todas</option>
                                    <option value="1">Planta 1</option>
                                    <option value="2">Planta 2</option>
                                </select>
                            </div>
                            @if($dateFilter || $plantaFilter)
                                <button wire:click="$set('dateFilter', ''); $set('plantaFilter', '');" class="text-[9px] font-black text-rose-400 hover:text-rose-300 uppercase tracking-widest transition-colors focus:outline-none ml-2">
                                    Limpiar Filtros
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Historial Table --}}
                    <div wire:poll.15s class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[1.5rem] md:rounded-[3rem] overflow-x-auto shadow-2xl">
                        <table class="w-full text-left border-collapse min-w-[800px] md:min-w-0" style="table-layout: auto;">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Incidencia</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Prioridad</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Asignado A</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Estado</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest">Hora</th>
                                    <th class="px-4 md:px-5 py-4 md:py-5 text-[9px] font-black text-gray-500 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.03]">
                                @forelse($historialTickets as $ticket)
                                <tr class="group hover:bg-white/[0.03] transition-all">
                                    <td class="px-4 md:px-5 py-4 text-blue-500 font-black text-xs">#{{ $ticket->id }}</td>
                                    <td class="px-4 md:px-5 py-4">
                                        <p class="text-sm font-black text-white uppercase tracking-tight">{{ $ticket->titulo }}</p>
                                        @if($ticket->creador)
                                            <p class="text-[10px] font-bold text-gray-600 mt-1 uppercase">{{ $ticket->creador->name }}</p>
                                        @endif
                                        @if($ticket->hora_visita)
                                            <p class="mt-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">
                                                    🕒 Visita: {{ $ticket->hora_visita }}
                                                </span>
                                            </p>
                                        @endif
                                        @if($ticket->tiempo_resolucion)
                                            <p class="mt-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black bg-teal-500/10 text-teal-400 border border-teal-500/20 uppercase tracking-wider">
                                                    ⏱️ Resol: {{ $ticket->tiempo_resolucion }} min
                                                </span>
                                            </p>
                                        @endif
                                        @if($ticket->planta)
                                            <p class="mt-1.5">
                                                <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[9px] font-black bg-indigo-500/10 text-indigo-400 border border-indigo-500/20 uppercase tracking-wider">
                                                    <i class="fa-solid fa-building"></i> Planta {{ $ticket->planta }}
                                                </span>
                                            </p>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        <span class="text-[10px] font-black uppercase text-gray-400">{{ $ticket->prioridad->nombre }}</span>
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        @if($ticket->agente)
                                            <div class="flex items-center gap-2">
                                                <div class="w-6 h-6 rounded-full bg-blue-500/10 text-blue-400 flex items-center justify-center text-[9px] font-black uppercase border border-blue-500/20">
                                                    {{ substr($ticket->agente->nombre_completo, 0, 2) }}
                                                </div>
                                                <span class="text-xs font-bold text-gray-300 uppercase tracking-tight">{{ $ticket->agente->nombre_completo }}</span>
                                            </div>
                                        @else
                                            <span class="text-[9px] font-black uppercase tracking-widest text-gray-600 bg-white/5 px-2.5 py-1 rounded-lg">Sin Asignar</span>
                                        @endif
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        @php
                                            $estadoNombre = $ticket->estado->nombre;
                                            $estadoClass = match(true) {
                                                in_array($estadoNombre, ['Completado', 'Cerrado', 'Resuelto']) => 'bg-emerald-600/20 text-emerald-300 border border-emerald-500/30',
                                                in_array($estadoNombre, ['Abierto', 'Nuevo']) => 'bg-blue-600/20 text-blue-300 border border-blue-500/30',
                                                in_array($estadoNombre, ['En Proceso', 'En progreso']) => 'bg-yellow-600/20 text-yellow-300 border border-yellow-500/30',
                                                default => 'bg-white/10 text-gray-300 border border-white/10'
                                            };
                                        @endphp
                                        <span class="px-4 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest {{ $estadoClass }}">
                                            {{ $estadoNombre }}
                                        </span>
                                    </td>
                                    <td class="px-4 md:px-5 py-4">
                                        <span class="text-[10px] font-black uppercase text-gray-400 block">{{ $ticket->created_at->format('H:i') }}</span>
                                        <span class="text-[8px] font-bold text-gray-500 mt-1 block">{{ $ticket->created_at->diffForHumans() }}</span>
                                    </td>
                                    <td class="px-4 md:px-5 py-4 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            <button wire:click.stop="openChatWidget({{ $ticket->id }})" class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-purple-600/20 text-purple-400 border border-purple-500/20 hover:bg-purple-600/40 transition-all flex items-center gap-1.5" title="Abrir Chat">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M8 10h.01M12 10h.01M16 10h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg>
                                                Chat
                                            </button>
                                            <button wire:click="viewAdminTicket({{ $ticket->id }})" class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-blue-600/20 text-blue-400 border border-blue-500/20 hover:bg-blue-600/40 transition-all flex items-center gap-1.5" title="Gestionar Ticket">
                                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                                Gestionar
                                            </button>
                                            <button wire:click="reopenTicket('{{ $ticket->id }}')"
                                                    wire:confirm="¿Estás seguro de reabrir este ticket?"
                                                    class="px-2.5 py-1.5 rounded-lg text-[8.5px] font-black uppercase bg-amber-600/20 text-amber-400 border border-amber-500/20 hover:bg-amber-600/40 transition-all flex items-center gap-1.5">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                                Reabrir
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="6" class="p-10 text-center text-gray-500 font-bold uppercase text-[10px] tracking-widest">No hay tickets cerrados en el historial</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </template>

            {{-- Tabs for Normal User --}}
            <template x-if="activeTab === 'inicio'">
                <div class="animate-in fade-in duration-500 px-4 sm:px-6 lg:px-8 pt-6 pb-8 flex-grow flex flex-col justify-start max-w-7xl mx-auto w-full relative z-10" x-init="initInicioWidgets()">
                    <!-- Contenedor Principal Glassmorphism -->
                    <div wire:ignore class="relative w-full rounded-[2rem] bg-white/5 backdrop-blur-xl border border-white/10 p-6 sm:p-8 lg:p-10 shadow-[0_0_50px_rgba(37,99,235,0.15)] flex flex-col justify-between overflow-hidden min-h-[580px]">
                        
                        <!-- Destellos Galácticos en esquinas -->
                        <div class="absolute top-0 right-0 w-[450px] h-[450px] bg-blue-600/20 rounded-full blur-[120px] pointer-events-none"></div>
                        <div class="absolute bottom-0 left-0 w-96 h-96 bg-cyan-500/10 rounded-full blur-[100px] pointer-events-none"></div>

                        <!-- Cabecera -->
                        <div class="relative z-10 w-full mb-6">
                            <div class="flex flex-col sm:flex-row sm:items-baseline sm:justify-between gap-4 flex-wrap">
                                <h2 class="text-3xl sm:text-4xl lg:text-5xl font-extrabold tracking-tight select-none uppercase drop-shadow-[0_0_15px_rgba(59,130,246,0.5)] pr-4 pb-2 text-white">
                                    BIENVENIDO, <span class="text-blue-400">{{ auth()->user()->nombre_completo ?? auth()->user()->name ?? 'USUARIO' }}</span>!
                                </h2>
                                <span class="text-xs font-semibold text-blue-400 tracking-wider uppercase font-mono whitespace-nowrap">CGR International • México</span>
                            </div>
                            
                            <div class="relative flex items-center mt-3 w-full max-w-2xl">
                                <div class="w-3 h-3 rounded-full border-2 border-blue-500 bg-[#050510] flex-shrink-0 shadow-[0_0_10px_rgba(59,130,246,0.8)]"></div>
                                <div class="flex-grow h-[2px] bg-gradient-to-r from-blue-500 to-transparent opacity-50"></div>
                            </div>
                        </div>

                        <!-- WIDGETS (Hora, Clima, Calendario, Info) -->
                        <div :class="chatListSidebarOpen || ($wire.chatWidgetTicketId && !$wire.isChatWidgetMinimized) ? 'flex flex-col' : 'grid grid-cols-1 lg:grid-cols-12'" class="gap-6 relative z-10 my-auto items-stretch">
                            
                            <!-- COLUMNA IZQUIERDA: Hora y Clima -->
                            <div class="lg:col-span-5 flex flex-col justify-between gap-6">
                                
                                <!-- Reloj Digital 3D + AM/PM Integrado -->
                                <div class="bg-white/5 backdrop-blur-md p-6 rounded-2xl border border-white/10 flex flex-col justify-center items-center shadow-[0_4px_30px_rgba(0,0,0,0.1)] relative overflow-hidden group hover:border-blue-500/40 hover:bg-white/10 hover:-translate-y-1 hover:shadow-[0_10px_40px_rgba(59,130,246,0.2)] transition-all duration-500">
                                    <div class="absolute -right-10 -bottom-10 w-24 h-24 bg-blue-500/10 rounded-full blur-xl group-hover:bg-blue-500/30 transition"></div>
                                    
                                    <span class="text-[10px] uppercase tracking-widest font-black text-gray-400 mb-3 self-start flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-400 animate-ping"></span>
                                        Tiempo de Planta (QRO)
                                    </span>

                                    <div class="flex items-center gap-3">
                                        <!-- Dígitos del Reloj 3D -->
                                        <div class="flex items-center text-white font-black text-4xl sm:text-5xl tracking-tighter tabular-nums">
                                            <span id="clock-hours" class="bg-white/10 text-white px-3 py-1.5 rounded-xl mx-0.5 border-b-4 border-blue-500/50 shadow-[0_4px_15px_rgba(0,0,0,0.5)] inline-block min-w-[3rem] text-center">{{ now()->format('h') }}</span>
                                            
                                            <!-- Indicadores Neón -->
                                            <div class="flex flex-col gap-2 mx-2">
                                                <div class="w-2 h-2 bg-blue-400 rounded-full shadow-[0_0_10px_rgba(96,165,250,0.8)] animate-pulse"></div>
                                                <div class="w-2 h-2 bg-blue-400 rounded-full shadow-[0_0_10px_rgba(96,165,250,0.8)] animate-pulse"></div>
                                            </div>
                                            
                                            <span id="clock-minutes" class="bg-white/10 text-white px-3 py-1.5 rounded-xl mx-0.5 border-b-4 border-blue-500/50 shadow-[0_4px_15px_rgba(0,0,0,0.5)] inline-block min-w-[3rem] text-center">{{ now()->format('i') }}</span>
                                        </div>

                                        <!-- Indicador AM / PM -->
                                        <div class="flex flex-col justify-center bg-[#07071a]/50 border border-white/10 px-3 py-2 rounded-xl tabular-nums">
                                            <span id="clock-ampm" class="text-lg font-black text-cyan-400 tracking-wider">{{ now()->format('A') }}</span>
                                            <span class="text-[8px] text-gray-400 font-bold uppercase text-center mt-0.5" id="seconds-counter">{{ now()->format('s') }}s</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- WIDGET 2: Clima -->
                                <div class="bg-white/5 backdrop-blur-md p-5 rounded-2xl border border-white/10 flex items-center justify-between shadow-[0_4px_30px_rgba(0,0,0,0.1)] hover:border-cyan-400/40 hover:bg-white/10 hover:-translate-y-1 hover:shadow-[0_10px_40px_rgba(34,211,238,0.15)] transition-all duration-500 group">
                                    <div class="flex flex-col">
                                        <span class="text-[10px] uppercase tracking-widest font-bold text-gray-400 mb-1 flex items-center gap-1.5">
                                            <svg class="w-3 h-3 text-cyan-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 15a4 4 0 004 4h9a5 5 0 10-.1-9.999 5.002 5.002 0 10-9.78 2.096A4.001 4.001 0 003 15z" /></svg>
                                            Clima en Querétaro
                                        </span>
                                        <span class="text-xl font-extrabold text-white" id="weather-temp">--°C</span>
                                        <span class="text-[11px] text-gray-400 font-medium" id="weather-desc">Cargando...</span>
                                        <span class="text-[9px] text-gray-500 mt-1">Planta El Pueblito, Qro.</span>
                                    </div>

                                    <div class="flex flex-col items-center justify-center bg-white/5 p-3.5 rounded-xl border border-white/10">
                                        <i id="weather-icon" class="fa-solid fa-cloud-sun text-4xl text-amber-400 animate-bounce" style="animation-duration: 3s;"></i>
                                        <div class="flex gap-2 mt-2 text-[9px] text-gray-400">
                                            <span><i class="fa-solid fa-droplet text-blue-400 mr-0.5"></i> 45%</span>
                                            <span><i class="fa-solid fa-wind text-gray-400 mr-0.5"></i> 12 km/h</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- COLUMNA CENTRAL-DERECHA: Calendario y Marca -->
                            <div class="lg:col-span-7 bg-white/5 backdrop-blur-md p-5 rounded-3xl border border-white/10 flex flex-col md:flex-row gap-5 items-stretch shadow-[0_8px_32px_rgba(0,0,0,0.2)] hover:border-white/20 transition-all duration-500">
                                
                                <!-- Calendario Dinámico -->
                                <div class="bg-[#050510]/80 border border-white/10 text-white rounded-2xl p-4 shadow-2xl flex-grow w-full md:w-1/2 transition-all duration-300">
                                    <div class="flex justify-between items-center mb-2 pb-1.5 border-b border-white/10">
                                        <span class="bg-blue-500/20 text-blue-400 font-extrabold px-2.5 py-0.5 rounded-lg text-xs" id="cal-year">----</span>
                                        <div class="flex items-center gap-1.5">
                                            <button @click="prevMonth()" class="text-gray-400 hover:text-white transition p-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg></button>
                                            <span class="font-black tracking-wider text-gray-200 text-xs uppercase" id="cal-month">---</span>
                                            <button @click="nextMonth()" class="text-gray-400 hover:text-white transition p-1"><svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg></button>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-7 gap-0.5 text-center text-[9px] font-black text-blue-400 mb-1 uppercase">
                                        <div>D</div><div>L</div><div>M</div><div>M</div><div>J</div><div>V</div><div>S</div>
                                    </div>

                                    <div class="grid grid-cols-7 gap-0.5 text-center text-[11px] font-bold" id="calendar-days">
                                        <!-- Inyectado por JS -->
                                    </div>
                                </div>

                                <!-- Panel de Marca CGR (Galaxy style) -->
                                <div class="bg-gradient-to-br from-blue-900/40 to-blue-600/10 rounded-2xl p-5 w-full md:w-1/2 flex flex-col justify-between border border-blue-500/20 shadow-inner text-white relative overflow-hidden group">
                                    <div class="absolute -right-8 -bottom-8 w-40 h-28 opacity-10 pointer-events-none">
                                        <svg class="w-full h-full overflow-visible" viewBox="0 0 100 50">
                                            <path d="M 5,25 C 5,45 85,45 95,25 C 95,5 15,5 5,25" fill="none" stroke="#ffffff" stroke-width="5" />
                                        </svg>
                                    </div>

                                    <div>
                                        <span class="text-[9px] uppercase tracking-widest text-cyan-300 font-extrabold bg-blue-900/50 border border-blue-500/30 px-2 py-0.5 rounded-md">Identidad</span>
                                        <div class="flex items-center gap-2 mt-4">
                                            <div class="relative w-16 h-10 flex-shrink-0">
                                                <span class="absolute top-0.5 left-0.5 text-2xl font-black text-white tracking-tighter z-10" style="text-shadow: 0 0 10px rgba(255,255,255,0.5);">CGR</span>
                                                <svg class="absolute -top-1 -left-2 w-20 h-12 overflow-visible" viewBox="0 0 100 50">
                                                    <path d="M 5,25 C 5,45 85,45 95,25 C 95,5 15,5 5,25" fill="none" stroke="#3b82f6" stroke-width="4.5" class="drop-shadow-[0_0_8px_rgba(59,130,246,0.8)]" />
                                                </svg>
                                            </div>
                                        </div>
                                        <p class="text-[11px] text-gray-300 italic mt-3 font-medium">"Form your world."</p>
                                    </div>

                                    <div class="mt-4 pt-3 border-t border-white/10 text-[10px] text-gray-400">
                                        <p class="font-bold text-gray-200">CGR Querétaro Plant</p>
                                        <p>Manufactura de alta precisión para autopartes y herramentales.</p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Tarjeta Inspiradora -->
                        <div :class="chatListSidebarOpen || ($wire.chatWidgetTicketId && !$wire.isChatWidgetMinimized) ? 'flex flex-col' : 'grid grid-cols-1 md:grid-cols-12'" class="relative z-10 w-full mt-6 gap-6 items-center">
                            
                            <div class="md:col-span-8 relative rounded-2xl overflow-hidden h-36 border border-white/10 shadow-[0_8px_32px_rgba(0,0,0,0.2)] hover:shadow-[0_12px_40px_rgba(59,130,246,0.2)] hover:border-blue-500/30 transition-all duration-500 group hover:-translate-y-1">
                                <img src="https://images.unsplash.com/photo-1581091226825-a6a2a5aee158?auto=format&fit=crop&w=800&q=80" 
                                     alt="Manufactura CGR" 
                                     class="absolute inset-0 w-full h-full object-cover filter brightness-[0.25] group-hover:scale-105 group-hover:brightness-[0.35] transition duration-700">
                                
                                <div class="absolute inset-0 bg-gradient-to-r from-[#020205]/90 via-[#020205]/60 to-transparent"></div>

                                <div class="absolute inset-0 p-5 flex flex-col justify-center">
                                    <span class="text-[8px] sm:text-[9px] uppercase tracking-widest font-extrabold text-blue-400 mb-1">CGR Motivación Diario</span>
                                    <blockquote class="text-xs sm:text-sm font-semibold text-gray-200 italic leading-relaxed max-w-xl">
                                        "La precisión en nuestro trabajo moldea el estándar del mañana. Con esfuerzo y seguridad, en CGR Querétaro formamos nuestro mundo."
                                    </blockquote>
                                    <p class="text-[9px] text-gray-400 mt-2 font-bold">— Equipo de Calidad y Procesos, CGR México.</p>
                                </div>
                            </div>

                            <div class="md:col-span-4 bg-white/5 border border-white/10 rounded-2xl p-4 h-36 flex flex-col justify-center backdrop-blur-md hover:bg-white/10 hover:border-blue-500/30 hover:shadow-[0_8px_30px_rgba(59,130,246,0.15)] transition-all duration-500 group hover:-translate-y-1">
                                <div class="flex items-center gap-2 mb-1.5">
                                    <span class="inline-block w-2.5 h-2.5 rounded-full bg-emerald-400 shadow-[0_0_10px_rgba(52,211,153,0.8)] animate-ping"></span>
                                    <p class="text-blue-400 text-xs font-bold uppercase tracking-wider">Estado Operativo</p>
                                </div>
                                <p class="text-xs text-gray-300 leading-relaxed">
                                    Turno de producción: <span class="text-white font-bold" id="current-shift">
                                        @php
                                            $hour = now()->hour;
                                            if($hour >= 6 && $hour < 14) echo 'Matutino (Turno A)';
                                            elseif($hour >= 14 && $hour < 22) echo 'Vespertino (Turno B)';
                                            else echo 'Nocturno (Turno C)';
                                        @endphp
                                    </span>.<br>
                                    Para reportar fallas críticas o paros de línea, use la pestaña de <span class="text-blue-400 font-extrabold">Generar Ticket</span>.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </template>

            <template x-if="activeTab === 'generar_ticket'">
                <div class="animate-in fade-in duration-500 w-full block text-left" 
                     style="background: linear-gradient(135deg, #020210 0%, #06051a 40%, #030318 70%, #010108 100%); position: relative;"
                     x-data="{
                        mode: '{{ auth()->user()->role === 'agente' ? 'manual' : 'selection' }}',
                        step: 1,
                        selectedSector: null,
                        selectedSectorName: '',
                        selectedArea: null,
                        selectedAreaName: '',
                        selectedService: null,
                        selectedServiceName: '',
                        selectedProblem: null,
                        selectedProblemText: '',
                        sectors: {{ json_encode($SECTORS) }},
                        areas: {{ json_encode($AREAS) }},
                        vigilanciaAreas: [
                            { id: 'vig_camara', name: 'Cámara', services: 1, icon: 'Video' },
                            { id: 'vig_equipo', name: 'Equipo', services: 1, icon: 'Monitor' },
                            { id: 'vig_wifi', name: 'Red de WiFi', services: 1, icon: 'Globe' }
                        ],
                        servicesByArea: {{ json_encode($SERVICES_BY_AREA) }},
                        categoriesByService: {{ json_encode($CATEGORIES_BY_SERVICE) }},
                        manualProblem: '',
                        manualDescription: '',
                        manualStation: '',
                        manualPriority: '2',
                        manualSector: '',
                        manualPlanta: '',
                        showProduccionSub: false,
                        category: null,
                        subcategory: null,
                        ticketPlanta: '',
                        intStation: '',
                        intComment: '',
                        maquinas: {{ json_encode($maquinas ?? []) }},
                        findMachineId(name) {
                            if (!name) return null;
                            let lower = name.toLowerCase().trim();
                            let match = this.maquinas.find(m => (m.nombre || '').toLowerCase().includes(lower) || (m.external_id || '').toLowerCase() === lower);
                            return match ? match.id : null;
                        },
                        get isManualReady() { return Boolean(this.manualProblem.trim() && this.manualDescription.trim() && this.manualStation.trim() && this.manualSector && this.manualPlanta); },
                        get isIntuitiveReady() { return Boolean(this.category && this.subcategory && this.intStation.trim() && this.ticketPlanta); },
                        clearAll() {
                            this.manualProblem = ''; this.manualDescription = ''; this.manualStation = ''; this.manualPriority = '2'; this.manualSector = ''; this.manualPlanta = '';
                            this.showProduccionSub = false;
                            this.category = null; this.subcategory = null; this.ticketPlanta = ''; this.intStation = ''; this.intComment = '';
                            this.step = 1;
                            this.clearStepsFrom(1);
                        },
                        clearStepsFrom(stepNum) {
                            if (stepNum <= 1) {
                                this.selectedSector = null;
                                this.selectedSectorName = '';
                            }
                            if (stepNum <= 2) {
                                this.selectedArea = null;
                                this.selectedAreaName = '';
                                this.category = null;
                            }
                            if (stepNum <= 3) {
                                this.selectedService = null;
                                this.selectedServiceName = '';
                            }
                            if (stepNum <= 4) {
                                this.selectedProblem = null;
                                this.selectedProblemText = '';
                                this.subcategory = null;
                            }
                        },
                        getIconClass(icon) {
                            let map = {
                                'ShieldCheck': 'fa-solid fa-shield-halved',
                                'Globe': 'fa-solid fa-earth-americas',
                                'HardDrive': 'fa-solid fa-server',
                                'Cpu': 'fa-solid fa-microchip',
                                'Printer': 'fa-solid fa-print',
                                'Monitor': 'fa-solid fa-desktop',
                                'Key': 'fa-solid fa-key',
                                'ShieldAlert': 'fa-solid fa-shield-virus',
                                'MailWarning': 'fa-solid fa-envelope-open-text',
                                'Lock': 'fa-solid fa-lock',
                                'Smartphone': 'fa-solid fa-mobile-screen-button',
                                'Wifi': 'fa-solid fa-wifi',
                                'Network': 'fa-solid fa-network-wired',
                                'Shield': 'fa-solid fa-shield-halved',
                                'Files': 'fa-solid fa-folder-tree',
                                'Package': 'fa-solid fa-box-archive',
                                'FileKey': 'fa-solid fa-file-signature',
                                'FileText': 'fa-regular fa-file-lines',
                                'Binary': 'fa-solid fa-code',
                                'Scan': 'fa-solid fa-expand'
                            };
                            return map[icon] || 'fa-solid fa-circle-question';
                        },
                        init() {
                            let initialLocation = $wire.get('ticketLocation');
                            if (initialLocation) {
                                let machine = this.maquinas.find(m => m.id == initialLocation);
                                if (machine) {
                                    this.manualStation = machine.nombre;
                                    this.intStation = machine.nombre;
                                    if (machine.sector_id) {
                                        this.selectedSector = machine.sector_id;
                                        let sMatch = this.sectors.find(s => s.id == machine.sector_id);
                                        this.selectedSectorName = sMatch ? sMatch.name : '';
                                        this.step = 2;
                                    }
                                }
                            }
                            this.$watch('$wire.ticketLocation', value => {
                                if (value) {
                                    let machine = this.maquinas.find(m => m.id == value);
                                    if (machine) {
                                        this.manualStation = machine.nombre;
                                        this.intStation = machine.nombre;
                                        if (machine.sector_id) {
                                            this.selectedSector = machine.sector_id;
                                            let sMatch = this.sectors.find(s => s.id == machine.sector_id);
                                            this.selectedSectorName = sMatch ? sMatch.name : '';
                                            if (this.step === 1) {
                                                this.step = 2;
                                            }
                                        }
                                    }
                                }
                            });
                        },
                        async submitManualTicket() {
                            if (!this.isManualReady) return;
                            window.dispatchEvent(new CustomEvent('show-sending-overlay'));
                            let mId = this.findMachineId(this.manualStation);
                            
                            try {
                                await $wire.createManualTicket({
                                    sectorId: this.manualSector,
                                    subcategory: this.manualProblem,
                                    description: this.manualDescription + '\n\n[Estación/Área Ingresada]: ' + this.manualStation,
                                    planta: this.manualPlanta,
                                    location: mId
                                });
                            } catch (e) {
                                console.error(e);
                                alert('Error de validación: por favor revisa que todos los campos y archivos estén correctos.');
                                window.dispatchEvent(new CustomEvent('hide-sending-overlay'));
                            }
                        },
                        async submitIntuitiveTicket() {
                            if (!this.isIntuitiveReady) return;
                            window.dispatchEvent(new CustomEvent('show-sending-overlay'));
                            $wire.set('ticketSectorId', this.selectedSector);
                            $wire.set('ticketCategory', this.category);
                            $wire.set('ticketSubcategory', this.subcategory);
                            let desc = 'Generado automáticamente por Botón rápido de TI.';
                            if (this.intComment.trim()) desc += '\n\nComentario extra: ' + this.intComment;
                            desc += '\n\n[Estación/Área Ingresada]: ' + this.intStation;
                            let pri = 2;
                            if (this.category === 'Seguridad TI' || this.category === 'Redes/Wifi') pri = 3;
                            else if (this.category === 'Impresión') pri = 1;
                            let mId = this.findMachineId(this.intStation);
                            
                            try {
                                await $wire.createIntuitiveTicket({
                                    sectorId: this.selectedSector,
                                    category: this.category,
                                    subcategory: this.subcategory,
                                    description: desc,
                                    planta: this.ticketPlanta,
                                    priority: pri,
                                    location: mId
                                });
                            } catch (e) {
                                console.error('Error al generar ticket rápido:', e);
                                alert('Error de validación: por favor revisa que todos los campos y archivos estén correctos.');
                                window.dispatchEvent(new CustomEvent('hide-sending-overlay'));
                            }
                        }
                     }"
                     @clear-ticket-form.window="clearAll(); mode = '{{ auth()->user()->role === 'agente' ? 'manual' : 'selection' }}';">
                     <div style="position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0;">
                         <div style="position:absolute;width:60vw;height:60vh;top:-10vh;right:-10vw;border-radius:50%;filter:blur(90px);background:radial-gradient(circle, rgba(45,27,150,0.25) 0%, rgba(30,15,100,0.1) 40%, transparent 70%);animation:nebulaDrift 28s ease-in-out infinite alternate;"></div>
                         <div style="position:absolute;width:50vw;height:50vh;bottom:-10vh;left:-5vw;border-radius:50%;filter:blur(80px);background:radial-gradient(circle, rgba(10,50,150,0.2) 0%, transparent 70%);animation:nebulaDrift 22s ease-in-out infinite alternate;animation-delay:-10s;"></div>
                         <div style="position:absolute;width:40vw;height:40vh;top:40vh;left:40vw;border-radius:50%;filter:blur(100px);background:radial-gradient(circle, rgba(80,20,120,0.15) 0%, transparent 70%);animation:nebulaDrift 35s ease-in-out infinite alternate;animation-delay:-5s;"></div>
                         <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px);background-size:70px 70px;opacity:0.08;"></div>
                         <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.7) 1.5px, transparent 1.5px);background-size:130px 130px;background-position:35px 35px;opacity:0.06;"></div>
                     </div>
                     <div class="max-w-[1200px] w-full px-4 sm:px-6 py-6 md:px-8 mx-auto relative z-20">
                         <div x-show="mode === 'selection'" class="w-full max-w-5xl mx-auto py-8 animate-in fade-in duration-300">
                             
                             <div class="bg-[#11111e]/80 backdrop-blur-xl border border-white/5 rounded-[2rem] p-8 md:p-12 relative shadow-2xl">
                                 <!-- Top icon / Header -->
                                 <div class="flex justify-center mb-3">
                                     <div class="text-indigo-400 text-2xl"><i class="fa-solid fa-ticket"></i></div>
                                 </div>
                                 
                                 <h2 class="text-3xl md:text-4xl font-extrabold text-white text-center mb-2 tracking-tight">
                                     ¿CÓMO DESEAS <span class="text-indigo-500">GENERAR TU TICKET?</span>
                                 </h2>
                                 <p class="text-gray-400 text-center mb-12 text-sm">
                                     Elige la opción que mejor se adapte a tu necesidad. Estamos <span class="text-indigo-400 font-bold">aquí para ayudarte</span>.
                                 </p>

                                 <!-- Cards Container -->
                                 <div :class="chatListSidebarOpen || ($wire.chatWidgetTicketId && !$wire.isChatWidgetMinimized) ? 'flex flex-col' : 'grid md:grid-cols-2'" class="gap-8 mb-12">
                                     
                                     <!-- Card 1 -->
                                     <div class="bg-white/[0.02] border border-white/10 rounded-2xl p-6 md:p-8 flex flex-col relative transition-transform hover:-translate-y-1 hover:shadow-xl hover:border-indigo-500/30">
                                         <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                                             <!-- Illustration circle -->
                                             <div class="shrink-0">
                                                 <svg viewBox="0 0 200 200" class="w-24 h-24 sm:w-28 sm:h-28">
                                                   <circle cx="100" cy="100" r="100" fill="#E8DEFF" />
                                                   <!-- Clipboard -->
                                                   <rect x="55" y="40" width="70" height="95" rx="8" fill="#FFFFFF" stroke="#291854" stroke-width="6" />
                                                   <!-- Clip -->
                                                   <path d="M75 40 V 30 C 75 25, 80 20, 85 20 H 95 C 100 20, 105 25, 105 30 V 40 Z" fill="#8B5CF6" stroke="#291854" stroke-width="6" />
                                                   <rect x="70" y="35" width="40" height="10" rx="3" fill="#8B5CF6" stroke="#291854" stroke-width="6" />
                                                   <circle cx="90" cy="28" r="4" fill="#FFFFFF" />
                                                   <!-- Lines and Checkboxes -->
                                                   <rect x="65" y="60" width="12" height="12" rx="2" fill="#D8B4FE" stroke="#291854" stroke-width="4" />
                                                   <path d="M 68 66 L 70 69 L 75 63" fill="none" stroke="#8B5CF6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                                   <line x1="85" y1="66" x2="115" y2="66" stroke="#291854" stroke-width="5" stroke-linecap="round" />
                                                   <rect x="65" y="85" width="12" height="12" rx="2" fill="#D8B4FE" stroke="#291854" stroke-width="4" />
                                                   <path d="M 68 91 L 70 94 L 75 88" fill="none" stroke="#8B5CF6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                                   <line x1="85" y1="91" x2="115" y2="91" stroke="#291854" stroke-width="5" stroke-linecap="round" />
                                                   <rect x="65" y="110" width="12" height="12" rx="2" fill="#D8B4FE" stroke="#291854" stroke-width="4" />
                                                   <path d="M 68 116 L 70 119 L 75 113" fill="none" stroke="#8B5CF6" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" />
                                                   <line x1="85" y1="116" x2="105" y2="116" stroke="#291854" stroke-width="5" stroke-linecap="round" />
                                                   <!-- Pencil -->
                                                   <g transform="translate(135, 75) rotate(35)">
                                                     <rect x="0" y="15" width="20" height="70" fill="#9333EA" stroke="#291854" stroke-width="5" />
                                                     <line x1="7" y1="15" x2="7" y2="85" stroke="#7E22CE" stroke-width="3" />
                                                     <line x1="13" y1="15" x2="13" y2="85" stroke="#7E22CE" stroke-width="3" />
                                                     <polygon points="0,85 10,115 20,85" fill="#FDE047" stroke="#291854" stroke-width="5" stroke-linejoin="round" />
                                                     <polygon points="6,103 10,115 14,103" fill="#291854" />
                                                     <rect x="0" y="15" width="20" height="10" fill="#CBD5E1" stroke="#291854" stroke-width="5" />
                                                     <path d="M 0 15 V 8 C 0 3, 4 0, 10 0 C 16 0, 20 3, 20 8 V 15 Z" fill="#291854" />
                                                     <path d="M 3 13 V 8 C 3 5, 6 3, 10 3 C 14 3, 17 5, 17 8 V 13 Z" fill="#D8B4FE" />
                                                   </g>
                                                 </svg>
                                             </div>
                                             <!-- Text -->
                                             <div class="text-center sm:text-left">
                                                 <h3 class="text-2xl font-black text-white mb-2 leading-tight">Redactar<br class="hidden sm:block"> problema</h3>
                                                 <p class="text-gray-400 text-xs leading-relaxed">Describe tu problema con detalle para que podamos entender y ayudarte mejor.</p>
                                             </div>
                                         </div>
                                         
                                         <div class="bg-indigo-500/10 rounded-lg p-3.5 mb-8 flex items-start gap-3 mx-auto sm:mx-0">
                                             <i class="fa-regular fa-lightbulb text-indigo-400 text-base mt-0.5"></i>
                                             <p class="text-[11px] text-indigo-200/80 font-medium leading-snug">Ideal para situaciones nuevas o que requieren explicación.</p>
                                         </div>

                                         <button @click="mode = 'manual'" class="w-full py-4 bg-[#4f46e5] hover:bg-[#4338ca] text-white rounded-xl font-bold text-sm tracking-wide text-center transition-colors flex items-center justify-center gap-2 mt-auto">
                                             Redactar problema <i class="fa-solid fa-arrow-right"></i>
                                         </button>
                                     </div>

                                     <!-- Card 2 -->
                                     <div class="bg-white/[0.02] border border-white/10 rounded-2xl p-6 md:p-8 flex flex-col relative transition-transform hover:-translate-y-1 hover:shadow-xl hover:border-blue-500/30">
                                         <div class="flex flex-col sm:flex-row items-center sm:items-start gap-6 mb-6">
                                             <!-- Illustration circle -->
                                             <div class="shrink-0">
                                                 <svg viewBox="0 0 200 200" class="w-24 h-24 sm:w-28 sm:h-28">
                                                   <circle cx="100" cy="100" r="100" fill="#DBEAFE" />
                                                   <!-- Click Rays -->
                                                   <g stroke="#1D4ED8" stroke-width="8" stroke-linecap="round">
                                                     <line x1="70" y1="35" x2="70" y2="55" />
                                                     <line x1="105" y1="70" x2="125" y2="70" />
                                                     <line x1="15" y1="70" x2="35" y2="70" />
                                                     <line x1="40" y1="40" x2="55" y2="55" />
                                                     <line x1="100" y1="40" x2="85" y2="55" />
                                                     <line x1="40" y1="100" x2="55" y2="85" />
                                                   </g>
                                                   
                                                   <!-- Hand -->
                                                   <g transform="translate(10, 0)">
                                                     <!-- Sleeve -->
                                                     <path d="M90 145 L160 120 L185 180 L115 205 Z" fill="#3B82F6" />
                                                     <path d="M85 145 L160 120 L165 140 L90 165 Z" fill="#1D4ED8" />
                                                     
                                                     <!-- Hand Body -->
                                                     <path d="M75 105 L115 145 L155 125 C 165 115, 170 100, 155 85 C 140 70, 120 70, 105 85 L90 100 Z" fill="#FDBA74" />
                                                     <path d="M75 105 L115 145 L155 125 C 165 115, 170 100, 155 85 C 140 70, 120 70, 105 85 L90 100 Z" fill="#FCD34D" opacity="0.3" />
                                                     
                                                     <!-- Index Finger -->
                                                     <path d="M75 105 C 50 80, 50 50, 65 45 C 80 40, 90 60, 105 85 Z" fill="#FDBA74" />
                                                     <path d="M75 105 C 50 80, 50 50, 65 45 C 80 40, 90 60, 105 85 Z" fill="#FCD34D" opacity="0.3" />
                                                     
                                                     <!-- Folded Fingers -->
                                                     <path d="M100 95 C 110 80, 130 75, 140 85 C 150 95, 140 110, 125 115 Z" fill="#FDBA74" />
                                                     <path d="M115 85 C 125 70, 145 65, 155 75 C 165 85, 155 100, 140 105 Z" fill="#FDBA74" />
                                                     
                                                     <!-- Thumb -->
                                                     <path d="M70 125 C 55 115, 60 95, 75 85 L100 115 Z" fill="#FDBA74" />
                                                     <path d="M70 125 C 55 115, 60 95, 75 85 L100 115 Z" fill="#FCD34D" opacity="0.3" />
                                                   </g>
                                                 </svg>
                                             </div>
                                             <!-- Text -->
                                             <div class="text-center sm:text-left">
                                                 <h3 class="text-2xl font-black text-white mb-2 leading-tight">Seleccionar<br class="hidden sm:block"> botones rápidos</h3>
                                                 <p class="text-gray-400 text-xs leading-relaxed">Elige una categoría y genera tu ticket en segundos con opciones predefinidas.</p>
                                             </div>
                                         </div>
                                         
                                         <div class="bg-blue-500/10 rounded-lg p-3.5 mb-8 flex items-start gap-3 mx-auto sm:mx-0">
                                             <i class="fa-solid fa-bolt text-blue-400 text-base mt-0.5"></i>
                                             <p class="text-[11px] text-blue-200/80 font-medium leading-snug">Ideal para solicitudes comunes y procesos rápidos.</p>
                                         </div>

                                         <button @click="mode = 'intuitive'" class="w-full py-4 bg-[#2563eb] hover:bg-[#1d4ed8] text-white rounded-xl font-bold text-sm tracking-wide text-center transition-colors flex items-center justify-center gap-2 mt-auto">
                                             Seleccionar botones rápidos <i class="fa-solid fa-arrow-right"></i>
                                         </button>
                                     </div>
                                 </div>

                                 <!-- Features Bar -->
                                 <div class="bg-white/[0.03] border border-white/10 rounded-2xl py-5 px-6 flex flex-wrap justify-center md:justify-between items-center gap-6 md:gap-4 mb-8">
                                     <div class="flex items-center gap-3">
                                         <i class="fa-solid fa-shield-halved text-indigo-400 text-2xl"></i>
                                         <div class="text-left">
                                             <h4 class="text-white text-xs font-bold">Seguridad</h4>
                                             <p class="text-gray-400 text-[10px]">Tu información está protegida</p>
                                         </div>
                                     </div>
                                     <div class="w-px h-8 bg-white/10 hidden md:block"></div>
                                     <div class="flex items-center gap-3">
                                         <i class="fa-regular fa-clock text-purple-400 text-2xl"></i>
                                         <div class="text-left">
                                             <h4 class="text-white text-xs font-bold">Respuesta rápida</h4>
                                             <p class="text-gray-400 text-[10px]">Nos comprometemos a ayudarte</p>
                                         </div>
                                     </div>
                                     <div class="w-px h-8 bg-white/10 hidden md:block"></div>
                                     <div class="flex items-center gap-3">
                                         <i class="fa-solid fa-users text-indigo-400 text-2xl"></i>
                                         <div class="text-left">
                                             <h4 class="text-white text-xs font-bold">Soporte experto</h4>
                                             <p class="text-gray-400 text-[10px]">Nuestro equipo está para apoyarte</p>
                                         </div>
                                     </div>
                                     <div class="w-px h-8 bg-white/10 hidden md:block"></div>
                                     <div class="flex items-center gap-3">
                                         <i class="fa-solid fa-chart-line text-purple-400 text-2xl"></i>
                                         <div class="text-left">
                                             <h4 class="text-white text-xs font-bold">Seguimiento</h4>
                                             <p class="text-gray-400 text-[10px]">Rastrea el estado de tu ticket</p>
                                         </div>
                                     </div>
                                 </div>

                                 <!-- Footer link -->
                                 <div class="text-center">
                                     <p class="text-gray-400 text-xs font-medium flex items-center justify-center gap-2">
                                         <i class="fa-regular fa-circle-question text-indigo-500 text-lg"></i>
                                         ¿Necesitas ayuda? Consulta nuestra <a href="#" class="text-indigo-400 hover:text-indigo-300 hover:underline">Guía de Uso <i class="fa-solid fa-arrow-up-right-from-square text-[9px] ml-0.5"></i></a>
                                     </p>
                                 </div>
                             </div>
                         </div>
                          @if(in_array(auth()->user()->role, ['agente', 'gestor']))
                          <!-- ULTRA PROFESSIONAL AGENT MANUAL FORM -->
                          <div x-show="mode === 'manual'" class="w-full max-w-4xl mx-auto animate-in fade-in slide-in-from-bottom-4 duration-500" style="display:none;">
                              <div class="relative w-full rounded-2xl p-[1px] bg-gradient-to-br from-indigo-500/40 via-purple-500/10 to-blue-500/40 shadow-[0_15px_50px_rgba(79,70,229,0.2)]">
                                  <div class="bg-[#070b14]/95 backdrop-blur-3xl rounded-2xl w-full h-full overflow-hidden flex flex-col md:flex-row border border-white/5">
                                      
                                      <!-- Left Panel: Context -->
                                      <div class="w-full md:w-5/12 bg-gradient-to-b from-[#0e1526] to-[#070a13] p-8 md:p-10 border-b md:border-b-0 md:border-r border-white/5 relative overflow-hidden flex flex-col">
                                          <div class="absolute top-0 right-0 w-48 h-48 bg-indigo-500/10 rounded-full blur-3xl pointer-events-none"></div>
                                          <div class="absolute bottom-0 left-0 w-32 h-32 bg-blue-500/10 rounded-full blur-2xl pointer-events-none"></div>
                                          
                                          <div class="relative z-10">
                                              <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-indigo-500/20 to-blue-500/10 border border-indigo-500/30 flex items-center justify-center text-indigo-400 mb-8 text-2xl shadow-[0_0_20px_rgba(99,102,241,0.2)]">
                                                  <i class="fa-solid fa-shield-halved"></i>
                                              </div>
                                              <h3 class="text-2xl font-black text-white tracking-tight mb-3 leading-tight">Escalación<br><span class="text-indigo-400">Administrativa</span></h3>
                                              <p class="text-xs text-gray-400 mb-8 font-medium leading-relaxed">
                                                  Portal de uso exclusivo para Soporte TI. 
                                                  Al generar este ticket, se omiten las reglas estándar de ruteo y el caso se asigna de manera <strong class="text-indigo-300">directa y confidencial</strong> al Administrador General del sistema.
                                              </p>
                                          </div>
                                          
                                          <div class="mt-auto relative z-10 space-y-4">
                                              <div class="flex items-center gap-3 text-[10px] text-gray-500 font-bold tracking-widest uppercase bg-white/5 px-4 py-3 rounded-xl border border-white/5">
                                                  <i class="fa-solid fa-bolt text-amber-400 text-sm"></i> Prioridad Máxima Forzada
                                              </div>
                                              <div class="flex items-center gap-3 text-[10px] text-gray-500 font-bold tracking-widest uppercase bg-white/5 px-4 py-3 rounded-xl border border-white/5">
                                                  <i class="fa-solid fa-user-tie text-blue-400 text-sm"></i> Canal Administrador
                                              </div>
                                          </div>
                                      </div>

                                      <!-- Right Panel: Form Fields -->
                                      <div class="w-full md:w-7/12 p-8 md:p-10 flex flex-col relative">
                                          <div class="space-y-6 flex-1">
                                              <!-- Asunto -->
                                              <div class="space-y-2">
                                                  <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest"><span class="text-indigo-500 mr-1">01.</span> Asunto Técnico *</label>
                                                  <input type="text" x-model="manualProblem" required placeholder="Ej. Falla crítica en servidor de base de datos..."
                                                      class="w-full px-4 py-3.5 rounded-xl border border-white/5 focus:border-indigo-500/50 focus:ring-4 focus:ring-indigo-500/10 bg-black/40 text-white placeholder:text-gray-600 transition-all text-xs outline-none font-medium shadow-inner">
                                              </div>
                                              
                                              <!-- Descripción -->
                                              <div class="space-y-2">
                                                  <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest"><span class="text-blue-500 mr-1">02.</span> Reporte Detallado *</label>
                                                  <textarea x-model="manualDescription" required rows="4" placeholder="Ingresa los logs, diagnóstico previo, o pasos para reproducir la falla..."
                                                      class="w-full px-4 py-3.5 rounded-xl border border-white/5 focus:border-blue-500/50 focus:ring-4 focus:ring-blue-500/10 bg-black/40 text-white placeholder:text-gray-600 transition-all text-xs outline-none resize-none font-medium leading-relaxed shadow-inner"></textarea>
                                              </div>

                                              <!-- Location Grid -->
                                              <div class="space-y-2">
                                                  <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-3"><span class="text-purple-500 mr-1">03.</span> Origen de la Falla</label>
                                                  <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                                      <select x-model="manualPlanta" required class="w-full px-4 py-3 rounded-xl border border-white/5 focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20 bg-black/40 text-white transition-all text-xs outline-none font-bold">
                                                          <option class="bg-[#0f172a] text-gray-400" value="">Planta *</option>
                                                          <option class="bg-[#0f172a]" value="1">Planta 1</option>
                                                          <option class="bg-[#0f172a]" value="2">Planta 2</option>
                                                      </select>
                                                      <select x-model="manualSector" required class="w-full px-4 py-3 rounded-xl border border-white/5 focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20 bg-black/40 text-white transition-all text-xs outline-none font-bold">
                                                          <option class="bg-[#0f172a] text-gray-400" value="">Sector *</option>
                                                          <template x-for="sec in sectors" :key="sec.id"><option class="bg-[#0f172a]" :value="sec.id" x-text="sec.name"></option></template>
                                                      </select>
                                                      <input type="text" x-model="manualStation" required placeholder="Estación *" class="w-full px-4 py-3 rounded-xl border border-white/5 focus:border-purple-500/50 focus:ring-2 focus:ring-purple-500/20 bg-black/40 text-white placeholder:text-gray-600 transition-all text-xs outline-none font-medium">
                                                  </div>
                                              </div>

                                              <!-- File Upload -->
                                              <div class="space-y-2">
                                                  <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest"><span class="text-green-500 mr-1">04.</span> Evidencia (Opcional)</label>
                                                  <label class="relative w-full h-12 border border-dashed border-white/10 hover:border-indigo-500/50 rounded-xl bg-black/20 flex items-center justify-center cursor-pointer transition-all group overflow-hidden">
                                                      <input type="file" multiple wire:model="ticketFiles" class="hidden" />
                                                      <div class="flex items-center gap-2 text-[11px] font-bold text-gray-500 group-hover:text-indigo-400 transition-colors">
                                                          <i class="fa-solid fa-cloud-arrow-up text-sm"></i>
                                                          <span>Subir capturas o archivos de log...</span>
                                                      </div>
                                                  </label>
                                                  <!-- File List -->
                                                  @if(!empty($ticketFiles))
                                                      <div class="mt-2 space-y-1.5 max-h-24 overflow-y-auto custom-scrollbar">
                                                          @foreach($ticketFiles as $index => $file)
                                                              @if($file)
                                                              @php
                                                                  $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                                                                  $iconClass = match($ext) {
                                                                      'pdf' => 'fa-solid fa-file-pdf text-red-400',
                                                                      'doc', 'docx' => 'fa-solid fa-file-word text-blue-400',
                                                                      'xls', 'xlsx' => 'fa-solid fa-file-excel text-green-400',
                                                                      'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp' => 'fa-solid fa-file-image text-purple-400',
                                                                      default => 'fa-solid fa-file text-gray-400',
                                                                  };
                                                              @endphp
                                                              <div class="flex items-center justify-between bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-xs text-gray-300">
                                                                  <div class="flex items-center gap-2 truncate">
                                                                      <i class="{{ $iconClass }} text-sm shrink-0"></i>
                                                                      <span class="truncate font-medium text-[10px]">{{ $file->getClientOriginalName() }}</span>
                                                                      <span class="text-[8px] font-black uppercase bg-white/10 text-gray-400 px-1.5 py-0.5 rounded ml-1">{{ $ext }}</span>
                                                                  </div>
                                                                  <button type="button" wire:click.prevent="removeTicketFile({{ $index }})" class="text-rose-500 hover:text-rose-400 ml-2 shrink-0 transition-colors">
                                                                      <i class="fa-solid fa-xmark"></i>
                                                                  </button>
                                                              </div>
                                                              @endif
                                                          @endforeach
                                                      </div>
                                                  @endif
                                              </div>
                                          </div>

                                          <!-- Submit -->
                                          <div class="mt-8 pt-6 border-t border-white/5 relative z-10">
                                              <button @click="submitManualTicket"
                                                  :disabled="!isManualReady"
                                                  :class="isManualReady ? 'bg-gradient-to-r from-indigo-600 to-blue-600 hover:from-indigo-500 hover:to-blue-500 text-white shadow-[0_0_30px_rgba(79,70,229,0.3)] hover:scale-[1.02] active:scale-95' : 'bg-white/5 text-gray-600 cursor-not-allowed border border-white/10'"
                                                  class="w-full font-black py-4 px-6 rounded-xl transition-all flex items-center justify-center gap-3 text-xs border border-indigo-400/20 uppercase tracking-widest group">
                                                  <span>PROCEDER CON LA ESCALACIÓN</span>
                                                  <i class="fa-solid fa-paper-plane group-hover:translate-x-1 group-hover:-translate-y-1 transition-transform" x-show="isManualReady"></i>
                                              </button>
                                          </div>
                                      </div>
                                  </div>
                              </div>
                          </div>
                          @endif
                          @if(!in_array(auth()->user()->role, ['agente', 'gestor']))
                          <!-- STANDARD USER MANUAL FORM -->
                          <div x-show="mode === 'manual'" class="w-full max-w-xl mx-auto animate-in fade-in duration-300" style="display:none;">
                              <button @click="mode = 'selection'" class="mb-3 flex items-center text-[10px] font-black uppercase tracking-wider text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-chevron-left mr-1.5 text-xs"></i> Volver</button>
                              <div class="bg-[#0b1221]/90 backdrop-blur-2xl rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden">
                                  {{-- Header --}}
                                  <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-5 py-4">
                                      <div class="flex items-center gap-3">
                                          <span class="p-2 bg-white/10 rounded-xl text-lg"><i class="fa-solid fa-file-pen text-white"></i></span>
                                          <div>
                                              <h2 class="text-sm font-black uppercase tracking-tight">Generar Ticket Manual</h2>
                                              <p class="text-[10px] text-blue-200 mt-0.5">Describe el problema para que un técnico sea asignado.</p>
                                          </div>
                                      </div>
                                  </div>
                                  {{-- Campos --}}
                                  <div class="p-5 space-y-4">
                                      {{-- Problema --}}
                                      <div class="space-y-1">
                                          <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-0.5">
                                              <span class="flex items-center gap-1.5"><i class="fa-solid fa-triangle-exclamation text-amber-500"></i> Problema o Asunto *</span>
                                          </label>
                                          <input type="text" x-model="manualProblem" required placeholder="Ej. No puedo ingresar al correo corporativo"
                                              class="w-full px-3.5 py-2.5 rounded-lg border border-white/10 focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 transition-all text-xs outline-none font-medium">
                                      </div>
                                      {{-- Descripción --}}
                                      <div class="space-y-1">
                                          <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-0.5">
                                              <span class="flex items-center gap-1.5"><i class="fa-solid fa-align-left text-blue-400"></i> Descripción del problema *</span>
                                          </label>
                                          <textarea x-model="manualDescription" required rows="3" placeholder="Describe a detalle lo ocurrido..."
                                              class="w-full px-3.5 py-2.5 rounded-lg border border-white/10 focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 transition-all text-xs outline-none resize-none font-medium leading-relaxed"></textarea>
                                      </div>
                                      {{-- Planta + Sector + Estación en grid --}}
                                      <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                                          <div class="space-y-1">
                                              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-0.5">
                                                  <span class="flex items-center gap-1.5"><i class="fa-solid fa-building text-indigo-400"></i> Planta *</span>
                                              </label>
                                              <select x-model="manualPlanta" required
                                                  class="w-full px-3.5 py-2.5 rounded-lg border border-white/10 focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10 bg-[#131b2f] text-white transition-all text-xs outline-none font-bold">
                                                  <option class="bg-[#0f172a]" value="">(Seleccionar)</option>
                                                  <option class="bg-[#0f172a]" value="1">Planta 1</option>
                                                  <option class="bg-[#0f172a]" value="2">Planta 2</option>
                                              </select>
                                          </div>
                                          <div class="space-y-1">
                                              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-0.5">
                                                  <span class="flex items-center gap-1.5"><i class="fa-solid fa-industry text-purple-400"></i> Sector *</span>
                                              </label>
                                              <select x-model="manualSector" required
                                                  class="w-full px-3.5 py-2.5 rounded-lg border border-white/10 focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10 bg-[#131b2f] text-white transition-all text-xs outline-none font-bold">
                                                  <option class="bg-[#0f172a]" value="">(Seleccionar)</option>
                                                  <template x-for="sec in sectors" :key="sec.id">
                                                      <option class="bg-[#0f172a]" :value="sec.id" x-text="sec.name"></option>
                                                  </template>
                                              </select>
                                          </div>
                                          <div class="space-y-1">
                                              <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-0.5">
                                                  <span class="flex items-center gap-1.5"><i class="fa-solid fa-map-pin text-rose-500"></i> Área / Estación *</span>
                                              </label>
                                              <input type="text" x-model="manualStation" required placeholder="Ej. Estación 45"
                                                  class="w-full px-3.5 py-2.5 rounded-lg border border-white/10 focus:border-blue-500/60 focus:ring-2 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 transition-all text-xs outline-none font-medium">
                                          </div>
                                      </div>
                                      {{-- Adjuntar Archivos --}}
                                      <div class="space-y-1">
                                          <label class="block text-[10px] font-bold text-gray-400 uppercase tracking-widest px-0.5">Adjuntar Archivos (PDF, Word, Imágenes)</label>
                                          <label class="relative w-full h-12 border border-dashed border-white/10 hover:border-blue-500/50 rounded-lg bg-[#131b2f] flex items-center justify-center cursor-pointer transition-all group overflow-hidden">
                                              <input type="file" multiple wire:model="ticketFiles" class="hidden" />
                                              <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 group-hover:text-blue-400 transition-colors">
                                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" /></svg>
                                                  <span>Seleccionar archivos...</span>
                                              </div>
                                          </label>
                                          {{-- Listado de archivos --}}
                                          @if(!empty($ticketFiles))
                                              <div class="mt-2 space-y-1.5 max-h-24 overflow-y-auto custom-scrollbar">
                                                  @foreach($ticketFiles as $index => $file)
                                                      @if($file)
                                                      @php
                                                          $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                                                          $iconClass = match($ext) {
                                                              'pdf' => 'fa-solid fa-file-pdf text-red-400',
                                                              'doc', 'docx' => 'fa-solid fa-file-word text-blue-400',
                                                              'xls', 'xlsx' => 'fa-solid fa-file-excel text-green-400',
                                                              'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp' => 'fa-solid fa-file-image text-purple-400',
                                                              default => 'fa-solid fa-file text-gray-400',
                                                          };
                                                      @endphp
                                                      <div class="flex items-center justify-between bg-white/[0.03] border border-white/5 rounded-lg px-3 py-2 text-xs text-gray-300">
                                                          <div class="flex items-center gap-2 truncate">
                                                              <i class="{{ $iconClass }} text-[14px] shrink-0 w-3.5 h-3.5 flex items-center justify-center"></i>
                                                              <span class="truncate text-[10px]">{{ $file->getClientOriginalName() }}</span>
                                                              <span class="text-[8px] font-bold uppercase bg-white/10 px-1.5 py-0.5 rounded ml-1">{{ $ext }}</span>
                                                          </div>
                                                          <button type="button" wire:click.prevent="removeTicketFile({{ $index }})" class="text-rose-400 hover:text-rose-300 ml-2 shrink-0">
                                                              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                          </button>
                                                      </div>
                                                      @endif
                                                  @endforeach
                                              </div>
                                          @endif
                                      </div>
                                      {{-- Botón Enviar --}}
                                      <button @click="submitManualTicket"
                                          :disabled="!isManualReady"
                                          :class="isManualReady ? 'bg-blue-600 hover:bg-blue-700 text-white shadow-[0_6px_20px_rgba(37,99,235,0.35)] hover:scale-[1.01] active:scale-95' : 'bg-white/5 text-gray-600 cursor-not-allowed border border-white/10'"
                                          class="w-full font-black py-3 px-4 rounded-xl transition-all flex items-center justify-center gap-2 text-xs border border-blue-400/20 uppercase tracking-widest mt-1">
                                          <i class="fa-solid fa-paper-plane"></i>
                                          <span>GENERAR TICKET</span>
                                      </button>
                                  </div>
                              </div>
                          </div>
                          @endif
                         <div x-show="mode === 'intuitive'" class="w-full animate-in fade-in duration-500" style="display:none;">
                              <div class="grid grid-cols-1 md:grid-cols-4 lg:grid-cols-12 gap-8 items-start w-full">
                                  {{-- Left Column: Stepper Indicators --}}
                                  <div class="col-span-1 md:col-span-1 lg:col-span-3 flex flex-col gap-6 py-4">
                                      <div class="space-y-6">
                                          <!-- Step 1: SECTOR -->
                                          <div class="flex items-center space-x-4 cursor-pointer group" @click="if(step > 1) { step = 1; clearStepsFrom(1); }">
                                              <div class="relative flex items-center justify-center">
                                                  <!-- Connector line -->
                                                  <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-6 bg-white/5 -z-10"></div>
                                                  
                                                  <div x-show="step > 1" class="w-10 h-10 rounded-full bg-emerald-950/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center transition-all duration-300">
                                                      <i class="fa-solid fa-check text-sm"></i>
                                                  </div>
                                                  <div x-show="step === 1" class="w-10 h-10 rounded-full bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.6)] flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      1
                                                  </div>
                                                  <div x-show="step < 1" class="w-10 h-10 rounded-full bg-[#131b2f] border border-white/5 text-gray-600 flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      1
                                                  </div>
                                              </div>
                                              <div>
                                                  <p class="text-xs font-black uppercase tracking-widest leading-none transition-colors" :class="step === 1 ? 'text-white' : 'text-gray-500'">SECTOR</p>
                                                  <p x-show="step === 1" class="text-[9px] font-bold text-gray-500 mt-1.5 uppercase tracking-wider">Paso Actual</p>
                                              </div>
                                          </div>
                                          
                                          <!-- Step 2: ÁREA -->
                                          <div class="flex items-center space-x-4 cursor-pointer group" @click="if(step > 2) { step = 2; clearStepsFrom(2); }">
                                              <div class="relative flex items-center justify-center">
                                                  <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-6 bg-white/5 -z-10"></div>
                                                  
                                                  <div x-show="step > 2" class="w-10 h-10 rounded-full bg-emerald-950/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center transition-all duration-300">
                                                      <i class="fa-solid fa-check text-sm"></i>
                                                  </div>
                                                  <div x-show="step === 2" class="w-10 h-10 rounded-full bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.6)] flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      2
                                                  </div>
                                                  <div x-show="step < 2" class="w-10 h-10 rounded-full bg-[#131b2f] border border-white/5 text-gray-600 flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      2
                                                  </div>
                                              </div>
                                              <div>
                                                  <p class="text-xs font-black uppercase tracking-widest leading-none transition-colors" :class="step === 2 ? 'text-white' : 'text-gray-500'">ÁREA</p>
                                                  <p x-show="step === 2" class="text-[9px] font-bold text-gray-500 mt-1.5 uppercase tracking-wider">Paso Actual</p>
                                              </div>
                                          </div>

                                          <!-- Step 3: SERVICIO -->
                                          <div class="flex items-center space-x-4 cursor-pointer group" @click="if(step > 3) { step = 3; clearStepsFrom(3); }">
                                              <div class="relative flex items-center justify-center">
                                                  <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-6 bg-white/5 -z-10"></div>
                                                  
                                                  <div x-show="step > 3" class="w-10 h-10 rounded-full bg-emerald-950/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center transition-all duration-300">
                                                      <i class="fa-solid fa-check text-sm"></i>
                                                  </div>
                                                  <div x-show="step === 3" class="w-10 h-10 rounded-full bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.6)] flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      3
                                                  </div>
                                                  <div x-show="step < 3" class="w-10 h-10 rounded-full bg-[#131b2f] border border-white/5 text-gray-600 flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      3
                                                  </div>
                                              </div>
                                              <div>
                                                  <p class="text-xs font-black uppercase tracking-widest leading-none transition-colors" :class="step === 3 ? 'text-white' : 'text-gray-500'">SERVICIO</p>
                                                  <p x-show="step === 3" class="text-[9px] font-bold text-gray-500 mt-1.5 uppercase tracking-wider">Paso Actual</p>
                                              </div>
                                          </div>

                                          <!-- Step 4: PROBLEMA -->
                                          <div class="flex items-center space-x-4 cursor-pointer group" @click="if(step > 4) { step = 4; clearStepsFrom(4); }">
                                              <div class="relative flex items-center justify-center">
                                                  <div class="absolute top-10 left-1/2 -translate-x-1/2 w-0.5 h-6 bg-white/5 -z-10"></div>
                                                  
                                                  <div x-show="step > 4" class="w-10 h-10 rounded-full bg-emerald-950/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center transition-all duration-300">
                                                      <i class="fa-solid fa-check text-sm"></i>
                                                  </div>
                                                  <div x-show="step === 4" class="w-10 h-10 rounded-full bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.6)] flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      4
                                                  </div>
                                                  <div x-show="step < 4" class="w-10 h-10 rounded-full bg-[#131b2f] border border-white/5 text-gray-600 flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      4
                                                  </div>
                                              </div>
                                              <div>
                                                  <p class="text-xs font-black uppercase tracking-widest leading-none transition-colors" :class="step === 4 ? 'text-white' : 'text-gray-500'">PROBLEMA</p>
                                                  <p x-show="step === 4" class="text-[9px] font-bold text-gray-500 mt-1.5 uppercase tracking-wider">Paso Actual</p>
                                              </div>
                                          </div>

                                          <!-- Step 5: SOLICITUD -->
                                          <div class="flex items-center space-x-4 cursor-pointer group" @click="if(step > 5) { step = 5; clearStepsFrom(5); }">
                                              <div class="relative flex items-center justify-center">
                                                  <div x-show="step > 5" class="w-10 h-10 rounded-full bg-emerald-950/20 border border-emerald-500/30 text-emerald-400 flex items-center justify-center transition-all duration-300">
                                                      <i class="fa-solid fa-check text-sm"></i>
                                                  </div>
                                                  <div x-show="step === 5" class="w-10 h-10 rounded-full bg-blue-600 text-white shadow-[0_0_15px_rgba(37,99,235,0.6)] flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      5
                                                  </div>
                                                  <div x-show="step < 5" class="w-10 h-10 rounded-full bg-[#131b2f] border border-white/5 text-gray-600 flex items-center justify-center font-black text-sm transition-all duration-300">
                                                      5
                                                  </div>
                                              </div>
                                              <div>
                                                  <p class="text-xs font-black uppercase tracking-widest leading-none transition-colors" :class="step === 5 ? 'text-white' : 'text-gray-500'">SOLICITUD</p>
                                                  <p x-show="step === 5" class="text-[9px] font-bold text-gray-500 mt-1.5 uppercase tracking-wider">Paso Actual</p>
                                              </div>
                                          </div>
                                      </div>
                                  </div>

                                  {{-- Right Column: Active Step Card --}}
                                  <div class="col-span-1 md:col-span-3 lg:col-span-9 bg-[#0b0c16]/80 backdrop-blur-2xl rounded-[2rem] p-8 shadow-2xl border border-white/5 flex flex-col min-h-[500px] justify-between relative overflow-hidden">
                                      <div class="absolute -right-24 -bottom-24 w-80 h-80 bg-blue-600/5 rounded-full blur-[80px] pointer-events-none"></div>
                                      
                                      <div class="w-full">
                                          {{-- Step Header --}}
                                          <div class="flex flex-col sm:flex-row sm:items-center gap-4 sm:gap-5 mb-8 relative z-10 bg-[#101026]/80 backdrop-blur-md rounded-[2rem] border border-white/5 p-6 sm:p-8 shadow-[0_0_50px_rgba(37,99,235,0.15)] overflow-hidden">
                                              <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/10 rounded-full blur-[80px] pointer-events-none"></div>
                                              <div class="w-12 h-12 sm:w-14 sm:h-14 rounded-full bg-blue-600 flex items-center justify-center shrink-0 shadow-[0_0_20px_rgba(37,99,235,0.4)] border border-blue-500/50 z-10 relative">
                                                  <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6 sm:w-7 sm:h-7" viewBox="0 0 24 24" fill="white">
                                                      <rect x="3" y="3" width="7" height="7" rx="1"></rect>
                                                      <rect x="14" y="3" width="7" height="7" rx="1"></rect>
                                                      <rect x="14" y="14" width="7" height="7" rx="1"></rect>
                                                      <rect x="3" y="14" width="7" height="7" rx="1"></rect>
                                                  </svg>
                                              </div>
                                              <div class="z-10 relative">
                                                  <h2 class="text-white text-xl sm:text-2xl font-bold tracking-wide" x-text="step === 1 ? 'Seleccione un sector...' : (step === 2 ? 'Seleccione un área de servicios...' : (step === 3 ? 'Seleccione un servicio...' : (step === 4 ? 'Seleccione el problema...' : 'Detalles de la solicitud...')))"></h2>
                                                  <p class="text-gray-400 text-xs sm:text-sm mt-1" x-text="step === 1 ? 'Elige el sector que mejor se adapte a tu requerimiento técnico' : (step === 2 ? 'Elige el área que mejor se adapte a tu necesidad' : (step === 3 ? 'Selecciona el servicio específico' : (step === 4 ? 'Describe o selecciona el problema' : 'Completa la información final')))">
                                                  </p>
                                              </div>
                                          </div>

                                          <script>
                                              window.getAreaSVG = function(name) {
                                                  const n = name.toLowerCase();
                                                  let svg = '';
                                                  const s = 'xmlns="http://www.w3.org/2000/svg" width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"';

                                                  if (n.includes('software')) {
                                                      svg = `<svg ${s}>
                                                               <rect x="4" y="4" width="16" height="16" rx="2" ry="2"></rect>
                                                               <rect x="9" y="9" width="6" height="6"></rect>
                                                               <line x1="9" y1="1" x2="9" y2="4"></line>
                                                               <line x1="15" y1="1" x2="15" y2="4"></line>
                                                               <line x1="9" y1="20" x2="9" y2="23"></line>
                                                               <line x1="15" y1="20" x2="15" y2="23"></line>
                                                               <line x1="20" y1="9" x2="23" y2="9"></line>
                                                               <line x1="20" y1="14" x2="23" y2="14"></line>
                                                               <line x1="1" y1="9" x2="4" y2="9"></line>
                                                               <line x1="1" y1="14" x2="4" y2="14"></line>
                                                             </svg>`;
                                                  } else if (n.includes('impres')) {
                                                      svg = `<svg ${s}>
                                                               <polyline points="6 9 6 2 18 2 18 9"></polyline>
                                                               <path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"></path>
                                                               <rect x="6" y="14" width="12" height="8"></rect>
                                                             </svg>`;
                                                  } else if (n.includes('equipo')) {
                                                      svg = `<svg ${s}>
                                                               <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                                               <line x1="8" y1="21" x2="16" y2="21"></line>
                                                               <line x1="12" y1="17" x2="12" y2="21"></line>
                                                             </svg>`;
                                                  } else if (n.includes('seguridad')) {
                                                      svg = `<svg ${s}>
                                                               <path d="M12 22s8-4 8-10V5l-8-3-8 3v7c0 6 8 10 8 10z"></path>
                                                             </svg>`;
                                                  } else if (n.includes('redes') || n.includes('wifi')) {
                                                      svg = `<svg ${s}>
                                                               <circle cx="12" cy="12" r="10"></circle>
                                                               <line x1="2" y1="12" x2="22" y2="12"></line>
                                                               <path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path>
                                                             </svg>`;
                                                  } else if (n.includes('archivo')) {
                                                      svg = `<svg ${s}>
                                                               <rect x="2" y="2" width="20" height="6" rx="2" ry="2"></rect>
                                                               <rect x="2" y="9" width="20" height="6" rx="2" ry="2"></rect>
                                                               <rect x="2" y="16" width="20" height="6" rx="2" ry="2"></rect>
                                                               <line x1="6" y1="5" x2="6.01" y2="5"></line>
                                                               <line x1="6" y1="12" x2="6.01" y2="12"></line>
                                                               <line x1="6" y1="19" x2="6.01" y2="19"></line>
                                                             </svg>`;
                                                  } else if (n.includes('abas')) {
                                                      svg = `<svg ${s}>
                                                               <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path>
                                                               <polyline points="14 2 14 8 20 8"></polyline>
                                                               <line x1="16" y1="13" x2="8" y2="13"></line>
                                                               <line x1="16" y1="17" x2="8" y2="17"></line>
                                                               <polyline points="10 9 9 9 8 9"></polyline>
                                                             </svg>`;
                                                  } else {
                                                      svg = `<svg ${s}><circle cx="12" cy="12" r="10"></circle><line x1="2" y1="12" x2="22" y2="12"></line><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"></path></svg>`;
                                                  }
                                                  return svg;
                                              };

                                              window.getAreaColorStyles = function(name) {
                                                  const n = name.toLowerCase();
                                                  if (n.includes('software')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-cyan-400 to-cyan-600 shadow-[0_0_20px_rgba(6,182,212,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-cyan-500 shadow-[0_8px_20px_-10px_rgba(6,182,212,0.5)]',
                                                          subText: 'text-cyan-400',
                                                          hoverText: 'group-hover:text-cyan-300'
                                                      };
                                                  } else if (n.includes('impres')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-pink-400 to-pink-600 shadow-[0_0_20px_rgba(236,72,153,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-pink-500 shadow-[0_8px_20px_-10px_rgba(236,72,153,0.5)]',
                                                          subText: 'text-pink-400',
                                                          hoverText: 'group-hover:text-pink-300'
                                                      };
                                                  } else if (n.includes('equipo')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-indigo-400 to-indigo-600 shadow-[0_0_20px_rgba(99,102,241,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-indigo-500 shadow-[0_8px_20px_-10px_rgba(99,102,241,0.5)]',
                                                          subText: 'text-indigo-400',
                                                          hoverText: 'group-hover:text-indigo-300'
                                                      };
                                                  } else if (n.includes('seguridad')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-emerald-400 to-emerald-600 shadow-[0_0_20px_rgba(16,185,129,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-emerald-500 shadow-[0_8px_20px_-10px_rgba(16,185,129,0.5)]',
                                                          subText: 'text-emerald-400',
                                                          hoverText: 'group-hover:text-emerald-300'
                                                      };
                                                  } else if (n.includes('redes') || n.includes('wifi')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-purple-500 to-purple-700 shadow-[0_0_20px_rgba(147,51,234,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-purple-600 shadow-[0_8px_20px_-10px_rgba(147,51,234,0.5)]',
                                                          subText: 'text-purple-400',
                                                          hoverText: 'group-hover:text-purple-300'
                                                      };
                                                  } else if (n.includes('archivo')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-orange-400 to-orange-500 shadow-[0_0_20px_rgba(249,115,22,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-orange-500 shadow-[0_8px_20px_-10px_rgba(249,115,22,0.5)]',
                                                          subText: 'text-orange-400',
                                                          hoverText: 'group-hover:text-orange-300'
                                                      };
                                                  } else if (n.includes('abas')) {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-blue-500 to-blue-700 shadow-[0_0_20px_rgba(37,99,235,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-blue-600 shadow-[0_8px_20px_-10px_rgba(37,99,235,0.5)]',
                                                          subText: 'text-blue-400',
                                                          hoverText: 'group-hover:text-blue-300'
                                                      };
                                                  } else {
                                                      return {
                                                          iconBg: 'bg-gradient-to-br from-blue-400 to-blue-600 shadow-[0_0_20px_rgba(59,130,246,0.6)]',
                                                          cardStyles: 'border-b-[3px] border-b-blue-500 shadow-[0_8px_20px_-10px_rgba(59,130,246,0.5)]',
                                                          subText: 'text-blue-400',
                                                          hoverText: 'group-hover:text-blue-300'
                                                      };
                                                  }
                                              }
                                          </script>


                                          {{-- STEP 1: SECTORS --}}
                                          <div x-show="step === 1" class="animate-in fade-in duration-300">
                                              <div x-show="!showProduccionSub" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8 py-4">
                                                  <!-- Button 1: Administración -->
                                                  <button @click="
                                                      let adminSector = sectors.find(s => s.name === 'Administración');
                                                      if (adminSector) {
                                                          selectedSector = adminSector.id;
                                                          selectedSectorName = adminSector.name;
                                                          $wire.set('ticketSectorId', adminSector.id);
                                                          step = 2;
                                                      }
                                                  "
                                                  class="group p-8 rounded-3xl bg-[#14142b]/40 border-2 border-white/5 hover:border-blue-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-300 hover:-translate-y-1 hover:scale-[1.01] flex flex-col justify-between h-48 outline-none">
                                                      <div class="w-16 h-16 bg-gradient-to-br from-blue-500/20 to-blue-700/20 border border-blue-500/30 text-blue-400 group-hover:from-blue-500 group-hover:to-blue-600 group-hover:text-white rounded-[1.25rem] flex items-center justify-center transition-all duration-300 shrink-0 shadow-[0_0_15px_rgba(37,99,235,0.15)] group-hover:shadow-[0_0_25px_rgba(37,99,235,0.5)]">
                                                          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                              <path stroke-linecap="round" stroke-linejoin="round" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                                          </svg>
                                                      </div>
                                                      <div>
                                                          <span class="font-extrabold text-white text-xl block group-hover:text-blue-400 transition-colors">Administración</span>
                                                          <span class="text-gray-400 text-[13px] mt-1.5 block font-medium leading-tight">Oficinas y departamentos administrativos</span>
                                                      </div>
                                                  </button>

                                                  <!-- Button 2: Producción -->
                                                  <button @click="showProduccionSub = true"
                                                  class="group p-8 rounded-3xl bg-[#14142b]/40 border-2 border-white/5 hover:border-blue-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-300 hover:-translate-y-1 hover:scale-[1.01] flex flex-col justify-between h-48 outline-none">
                                                      <div class="w-16 h-16 bg-gradient-to-br from-purple-500/20 to-purple-700/20 border border-purple-500/30 text-purple-400 group-hover:from-purple-500 group-hover:to-purple-600 group-hover:text-white rounded-[1.25rem] flex items-center justify-center transition-all duration-300 shrink-0 shadow-[0_0_15px_rgba(168,85,247,0.15)] group-hover:shadow-[0_0_25px_rgba(168,85,247,0.5)]">
                                                          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                              <path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                                          </svg>
                                                      </div>
                                                      <div>
                                                          <span class="font-extrabold text-white text-xl block group-hover:text-purple-400 transition-colors">Producción</span>
                                                          <span class="text-gray-400 text-[13px] mt-1.5 block font-medium leading-tight">Áreas operativas y maquinaria industrial</span>
                                                      </div>
                                                  </button>

                                                  <!-- Button 3: Vigilancia -->
                                                  <button @click="
                                                      let vigSector = sectors.find(s => s.name === 'Vigilancia');
                                                      if (vigSector) {
                                                          selectedSector = vigSector.id;
                                                          selectedSectorName = vigSector.name;
                                                          $wire.set('ticketSectorId', vigSector.id);
                                                          step = 2;
                                                      }
                                                  "
                                                  class="group p-8 rounded-3xl bg-[#14142b]/40 border-2 border-white/5 hover:border-amber-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-300 hover:-translate-y-1 hover:scale-[1.01] flex flex-col justify-between h-48 outline-none">
                                                      <div class="w-16 h-16 bg-gradient-to-br from-amber-500/20 to-amber-700/20 border border-amber-500/30 text-amber-400 group-hover:from-amber-500 group-hover:to-amber-600 group-hover:text-white rounded-[1.25rem] flex items-center justify-center transition-all duration-300 shrink-0 shadow-[0_0_15px_rgba(245,158,11,0.15)] group-hover:shadow-[0_0_25px_rgba(245,158,11,0.5)]">
                                                          <svg xmlns="http://www.w3.org/2000/svg" class="w-8 h-8" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                                              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10l4.553-2.276A1 1 0 0121 8.618v6.764a1 1 0 01-1.447.894L15 14M5 18h8a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v8a2 2 0 002 2z" />
                                                          </svg>
                                                      </div>
                                                      <div>
                                                          <span class="font-extrabold text-white text-xl block group-hover:text-amber-400 transition-colors">Vigilancia</span>
                                                          <span class="text-gray-400 text-[13px] mt-1.5 block font-medium leading-tight">Cámaras, red de video y equipos de seguridad</span>
                                                      </div>
                                                  </button>
                                              </div>

                                              <!-- Sub-menu for Producción -->
                                              <div x-show="showProduccionSub" class="animate-in fade-in slide-in-from-bottom-4 duration-300 space-y-6">
                                                  <button @click="showProduccionSub = false" class="flex items-center text-xs font-black uppercase tracking-wider text-gray-500 hover:text-white transition-colors mb-4 focus:outline-none">
                                                      <i class="fa-solid fa-chevron-left mr-2 text-sm"></i> Volver a sectores
                                                  </button>
                                                  <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                                                      <template x-for="prodSecName in ['Compresión', 'Tensión', 'Torsión', 'Plat', 'Wire Standing', 'Wipers', 'Mecatrónicos', 'Welding', 'Bending']">
                                                          <button @click="
                                                              let fullName = 'Producción - ' + prodSecName;
                                                              let dbSec = sectors.find(s => s.name === fullName);
                                                              if (dbSec) {
                                                                  selectedSector = dbSec.id;
                                                                  selectedSectorName = dbSec.name;
                                                                  $wire.set('ticketSectorId', dbSec.id);
                                                                  step = 2;
                                                              }
                                                          "
                                                          class="group p-5 rounded-2xl bg-[#14142b]/40 border border-white/5 hover:border-purple-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-between outline-none">
                                                              <span class="font-bold text-white text-sm group-hover:text-purple-400 transition-colors" x-text="prodSecName"></span>
                                                              <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-purple-400 transition-colors text-sm"></i>
                                                          </button>
                                                      </template>
                                                  </div>
                                              </div>
                                          </div>

                                          {{-- STEP 2: AREAS --}}
                                          <div x-show="step === 2" class="animate-in fade-in duration-300 w-full">
                                              
                                              <!-- 3-Column Grid -->
                                              <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 relative z-10 mt-4">
                                                      <template x-for="area in (selectedSectorName === 'Vigilancia' ? vigilanciaAreas : areas)" :key="area.id">
                                                          <button @click="
                                                               selectedArea = area.id;
                                                               selectedAreaName = area.name;
                                                               category = area.name;
                                                               let services = servicesByArea[area.id] || [];
                                                               if (services.length === 1) {
                                                                   selectedService = services[0].id;
                                                                   selectedServiceName = services[0].name;
                                                                   step = 4;
                                                               } else {
                                                                   step = 3;
                                                               }
                                                           "
                                                                  class="group p-5 rounded-[1.25rem] bg-[#12142e] border border-white/5 transition-all duration-300 flex items-center text-left gap-5 outline-none hover:-translate-y-1 hover:bg-[#1a1d3f]"
                                                                  :class="window.getAreaColorStyles(area.name).cardStyles">
                                                              <div class="w-[4.5rem] h-[4.5rem] rounded-full flex-shrink-0 flex items-center justify-center transition-all duration-300 relative"
                                                                   :class="window.getAreaColorStyles(area.name).iconBg">
                                                                  <div x-html="window.getAreaSVG(area.name)" class="z-10 scale-95 drop-shadow-md"></div>
                                                              </div>
                                                              <div class="flex flex-col">
                                                                  <span class="font-bold text-white text-[15px] tracking-wide transition-colors"
                                                                        :class="window.getAreaColorStyles(area.name).hoverText" 
                                                                        x-text="area.name"></span>
                                                                  <span class="text-[12px] font-medium mt-1 transition-colors" 
                                                                        :class="window.getAreaColorStyles(area.name).subText" 
                                                                        x-text="area.services + ' Servicios'"></span>
                                                              </div>
                                                          </button>
                                                      </template>
                                                  </div>
                                          </div>

                                          {{-- STEP 3: SERVICES --}}
                                          <div x-show="step === 3" class="animate-in fade-in duration-300">
                                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                                                  <template x-for="serv in (servicesByArea[selectedArea] || [])" :key="serv.id">
                                                      <button @click="selectedService = serv.id; selectedServiceName = serv.name; step = 4;"
                                                              class="group p-6 rounded-2xl bg-[#14142b]/40 border-2 border-white/5 hover:border-blue-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-300 hover:-translate-y-1 hover:scale-[1.01] flex items-start space-x-4 outline-none">
                                                          <div class="w-12 h-12 bg-blue-600/10 text-blue-400 group-hover:bg-blue-600 group-hover:text-white rounded-xl flex items-center justify-center text-2xl transition-all duration-300 shrink-0">
                                                              <i :class="getIconClass(serv.icon)"></i>
                                                          </div>
                                                          <div>
                                                              <span class="font-extrabold text-white text-base block group-hover:text-blue-400 transition-colors" x-text="serv.name"></span>
                                                              <span class="text-gray-400 text-xs mt-1.5 block leading-normal" x-text="serv.description"></span>
                                                          </div>
                                                      </button>
                                                  </template>
                                              </div>
                                          </div>

                                          {{-- STEP 4: PROBLEMS --}}
                                          <div x-show="step === 4" class="animate-in fade-in duration-300">
                                              <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                  <template x-for="prob in (categoriesByService[selectedService] || [])" :key="prob.id">
                                                      <button @click="
                                                           selectedProblem = prob.id;
                                                           selectedProblemText = prob.name;
                                                           subcategory = prob.name;
                                                           step = 5;
                                                       "
                                                              class="group p-5 rounded-xl bg-[#14142b]/40 border border-white/5 hover:border-blue-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-200 hover:-translate-y-0.5 flex items-center justify-between outline-none">
                                                          <span class="font-bold text-white text-sm group-hover:text-blue-400 transition-colors" x-text="prob.name"></span>
                                                          <i class="fa-solid fa-chevron-right text-gray-600 group-hover:text-blue-400 transition-colors text-sm"></i>
                                                      </button>
                                                  </template>
                                              </div>
                                          </div>

                                          {{-- STEP 5: FINAL FORM (SOLICITUD) --}}
                                          <div x-show="step === 5" class="animate-in fade-in duration-300">
                                              <div class="grid grid-cols-1 md:grid-cols-12 gap-8 items-start w-full">
                                                  {{-- Receipt Summary --}}
                                                  <div class="col-span-1 md:col-span-5 bg-gradient-to-br from-[#0a0b16] to-[#12132a] border border-blue-500/15 text-white rounded-[2rem] p-6 shadow-2xl relative overflow-hidden backdrop-blur-md">
                                                      <div class="absolute -right-12 -bottom-12 w-48 h-48 bg-blue-500/5 rounded-full blur-[60px] pointer-events-none"></div>
                                                      
                                                      <div class="relative z-10">
                                                          <div class="flex items-center space-x-2.5 mb-5">
                                                              <i class="fa-solid fa-receipt text-blue-400 text-lg"></i>
                                                              <span class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Resumen del Ticket Rápido</span>
                                                          </div>
                                                          
                                                          <div class="space-y-4">
                                                              <div>
                                                                  <span class="text-[10px] text-gray-500 uppercase font-extrabold tracking-widest block mb-1">Sector seleccionado</span>
                                                                  <span class="font-black text-xs uppercase text-white tracking-tight" x-text="selectedSectorName || '—'"></span>
                                                              </div>
                                                              <div class="border-t border-dashed border-white/10 my-3"></div>
                                                              <div>
                                                                  <span class="text-[10px] text-gray-500 uppercase font-extrabold tracking-widest block mb-1">Área de servicio</span>
                                                                  <span class="font-black text-xs uppercase text-white tracking-tight" x-text="selectedAreaName || '—'"></span>
                                                              </div>
                                                              <div class="border-t border-dashed border-white/10 my-3"></div>
                                                              <div>
                                                                  <span class="text-[10px] text-gray-500 uppercase font-extrabold tracking-widest block mb-1">Servicio</span>
                                                                  <span class="font-black text-xs uppercase text-white tracking-tight" x-text="selectedServiceName || '—'"></span>
                                                              </div>
                                                              <div class="border-t border-dashed border-white/10 my-3"></div>
                                                              <div>
                                                                  <span class="text-[10px] text-gray-500 uppercase font-extrabold tracking-widest block mb-1">Incidencia / Sub-problema</span>
                                                                  <span class="font-black text-xs uppercase text-blue-400 tracking-tight leading-relaxed block" x-text="selectedProblemText || '—'"></span>
                                                              </div>
                                                          </div>
                                                      </div>
                                                  </div>

                                                  {{-- Inputs --}}
                                                  <div class="col-span-1 md:col-span-7 space-y-5">
                                                      <div>
                                                          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1"><i class="fa-solid fa-industry text-indigo-500 mr-1"></i> Planta *</label>
                                                          <div class="grid grid-cols-2 gap-3">
                                                              <button @click="ticketPlanta = 1" type="button"
                                                                      :class="ticketPlanta === 1 ? 'bg-indigo-600 border-indigo-400 text-white shadow-[0_0_15px_rgba(79,70,229,0.3)]' : 'bg-[#131b2f] border-white/10 text-gray-400 hover:border-indigo-500/50 hover:text-white'"
                                                                      class="py-3 px-4 rounded-xl border transition-all font-bold uppercase tracking-widest text-xs flex items-center justify-center gap-2 outline-none">
                                                                  <i class="fa-solid fa-building"></i> Planta 1
                                                              </button>
                                                              <button @click="ticketPlanta = 2" type="button"
                                                                      :class="ticketPlanta === 2 ? 'bg-indigo-600 border-indigo-400 text-white shadow-[0_0_15px_rgba(79,70,229,0.3)]' : 'bg-[#131b2f] border-white/10 text-gray-400 hover:border-indigo-500/50 hover:text-white'"
                                                                      class="py-3 px-4 rounded-xl border transition-all font-bold uppercase tracking-widest text-xs flex items-center justify-center gap-2 outline-none">
                                                                  <i class="fa-solid fa-building"></i> Planta 2
                                                              </button>
                                                          </div>
                                                      </div>
                                                      <div>
                                                          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1"><i class="fa-solid fa-map-pin text-rose-500 mr-1"></i> Área / Estación *</label>
                                                          <input type="text" x-model="intStation" required placeholder="Piso / Cubículo o Nombre del área" class="w-full px-5 py-4 rounded-xl border border-white/10 focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 text-sm outline-none transition-all font-medium">
                                                      </div>
                                                      <div>
                                                          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1"><i class="fa-solid fa-comment-dots text-gray-500 mr-1"></i> Detalles Extra (Opcional)</label>
                                                          <textarea x-model="intComment" rows="2" placeholder="Agrega cualquier dato adicional..." class="w-full px-5 py-4 rounded-xl border border-white/10 focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 text-sm outline-none transition-all resize-none font-medium leading-relaxed"></textarea>
                                                      </div>
                                                      <div class="pt-1">
                                                          <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Adjuntar Evidencia (Opcional)</label>
                                                          <label class="relative w-full h-14 border border-dashed border-white/10 hover:border-blue-500/50 rounded-[0.75rem] bg-[#131b2f] flex flex-col items-center justify-center cursor-pointer transition-all group overflow-hidden">
                                                              <input type="file" multiple wire:model="ticketFiles" class="hidden" />
                                                              <div class="flex items-center gap-2 text-[10px] font-bold text-gray-500 group-hover:text-blue-400 transition-colors">
                                                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" />
                                                                  </svg>
                                                                  <span>Adjuntar archivos...</span>
                                                              </div>
                                                          </label>
                                                          {{-- Listado de archivos --}}
                                                          @if(!empty($ticketFiles))
                                                              <div class="mt-2 space-y-1.5 max-h-24 overflow-y-auto custom-scrollbar">
                                                                  @foreach($ticketFiles as $index => $file)
                                                                      @if($file)
                                                                      @php
                                                                          $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                                                                          $iconClass = match($ext) {
                                                                              'pdf' => 'fa-solid fa-file-pdf text-red-400',
                                                                              'doc', 'docx' => 'fa-solid fa-file-word text-blue-400',
                                                                              'xls', 'xlsx' => 'fa-solid fa-file-excel text-green-400',
                                                                              'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp' => 'fa-solid fa-file-image text-purple-400',
                                                                              default => 'fa-solid fa-file text-gray-400',
                                                                          };
                                                                      @endphp
                                                                      <div class="flex items-center justify-between bg-white/[0.03] border border-white/5 rounded-lg px-3 py-2 text-xs text-gray-300">
                                                                          <div class="flex items-center gap-2 truncate">
                                                                              <i class="{{ $iconClass }} text-[14px] shrink-0 w-3.5 h-3.5 flex items-center justify-center"></i>
                                                                              <span class="truncate text-[10px]">{{ $file->getClientOriginalName() }}</span>
                                                                              <span class="text-[8px] font-bold uppercase bg-white/10 px-1.5 py-0.5 rounded ml-1">{{ $ext }}</span>
                                                                          </div>
                                                                          <button type="button" wire:click.prevent="removeTicketFile({{ $index }})" class="text-rose-400 hover:text-rose-300 ml-2 shrink-0">
                                                                              <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                                                          </button>
                                                                      </div>
                                                                      @endif
                                                                  @endforeach
                                                              </div>
                                                          @endif
                                                      </div>
                                                      <div class="pt-2">
                                                          <button @click="submitIntuitiveTicket" :disabled="!isIntuitiveReady"
                                                                  :class="isIntuitiveReady ? 'bg-gradient-to-r from-blue-600 via-indigo-600 to-violet-600 hover:from-blue-500 hover:via-indigo-500 hover:to-violet-500 text-white shadow-[0_0_25px_rgba(99,102,241,0.3)] hover:shadow-[0_0_40px_rgba(99,102,241,0.5)] hover:scale-[1.01] active:scale-[0.99] border-blue-400/20' : 'bg-white/5 text-gray-600 cursor-not-allowed border-white/10'"
                                                                  class="w-full font-bold py-4 px-6 rounded-2xl shadow-md transition-all flex items-center justify-center space-x-2 text-sm uppercase tracking-wider border outline-none">
                                                              <i class="fa-solid fa-circle-check"></i>
                                                              <span>ENVIAR TICKET RÁPIDO</span>
                                                          </button>
                                                      </div>
                                                  </div>
                                              </div>
                                          </div>
                                      </div>

                                      {{-- Step Footer Navigation --}}
                                      <div class="w-full border-t border-white/5 pt-6 mt-8 flex items-center justify-between">
                                          <button x-show="step > 1" @click="
                                               if (step === 4) {
                                                   let services = servicesByArea[selectedArea] || [];
                                                   if (services.length === 1) {
                                                       step = 2;
                                                   } else {
                                                       step--;
                                                   }
                                               } else {
                                                   step--;
                                               }
                                               clearStepsFrom(step);
                                           "
                                                  class="flex items-center text-xs font-black uppercase tracking-wider text-gray-400 hover:text-white transition-colors outline-none">
                                              <i class="fa-solid fa-chevron-left mr-2 text-sm"></i> Paso Anterior
                                          </button>
                                          <span x-show="step <= 1"></span> {{-- Placeholder for alignment --}}
                                          
                                          <button @click="mode = 'selection'; step = 1; clearStepsFrom(1);"
                                                  class="text-xs font-black uppercase tracking-wider text-gray-500 hover:text-red-400 transition-colors outline-none">
                                              Cancelar
                                          </button>
                                      </div>
                                  </div>
                              </div>
                </div>
            </template>

            <template x-if="activeTab === 'mis_tickets'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 max-w-7xl mx-auto space-y-8 w-full">
                    
                    <div class="flex flex-col sm:flex-row justify-between sm:items-end gap-6 mb-8 border-b border-white/10 pb-6">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-[0.2em] mt-1">Historial y Seguimiento de Tickets</p>
                        </div>
                        <div class="relative w-full sm:w-72">
                            <svg class="w-4 h-4 absolute left-4 top-1/2 -translate-y-1/2 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                            <input type="text" placeholder="Búsqueda por palabra clave..." class="w-full pl-12 pr-4 py-3 bg-[#1a1a2e]/60 border border-white/10 rounded-2xl text-xs font-bold text-white outline-none focus:border-blue-500 focus:bg-blue-600/5 transition-all shadow-inner placeholder:text-gray-600 uppercase tracking-widest" />
                        </div>
                    </div>
                    
                    <div class="grid gap-4">
                        @forelse($tickets->where('usuario_creador_id', auth()->id()) as $ticket)
                        <div class="bg-[#1a1a2e]/40 p-6 rounded-[2rem] border transition-all cursor-pointer flex items-center gap-5 md:gap-8 group hover:-translate-y-1 relative overflow-hidden backdrop-blur-md shadow-lg
                             @if($ticket->estado->nombre === 'Completado' || $ticket->estado->nombre === 'Cerrado') border-emerald-500/20 hover:border-emerald-500/50 hover:shadow-[0_0_20px_rgba(16,185,129,0.2)] 
                             @else border-blue-500/20 hover:border-blue-500/50 hover:shadow-[0_0_30px_rgba(37,99,235,0.2)] @endif" wire:click="viewUserTicket({{ $ticket->id }})">
                            <div class="absolute inset-0 bg-gradient-to-r @if($ticket->estado->nombre === 'Completado' || $ticket->estado->nombre === 'Cerrado') from-emerald-500/5 @else from-blue-600/5 @endif to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                            
                            <div class="relative z-10 w-14 h-14 rounded-2xl flex items-center justify-center shrink-0 border
                                @if($ticket->estado->nombre === 'Completado' || $ticket->estado->nombre === 'Cerrado') bg-emerald-500/10 border-emerald-500/30 text-emerald-400 group-hover:bg-emerald-500/20 
                                @else bg-blue-600/10 border-blue-500/30 text-blue-400 group-hover:bg-blue-600/20 @endif transition-colors">
                                @if($ticket->estado->nombre === 'Completado' || $ticket->estado->nombre === 'Cerrado')
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg>
                                @else
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                @endif
                            </div>
                            
                            <div class="relative z-10 flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-1.5">
                                    <span class="text-[9px] font-black uppercase tracking-[0.2em] text-gray-500 group-hover:text-gray-400 transition-colors">ID #{{ substr($ticket->id, 0, 8) }}</span>
                                    <span class="px-3 py-1 rounded-full text-[8px] font-black uppercase tracking-widest border
                                        @if($ticket->estado->nombre === 'Completado' || $ticket->estado->nombre === 'Cerrado') bg-emerald-500/20 text-emerald-300 border-emerald-500/30 
                                        @else bg-blue-600/20 text-blue-300 border-blue-500/30 @endif">
                                        {{ $ticket->estado->nombre }}
                                    </span>
                                </div>
                                <h4 class="font-black text-white text-sm uppercase tracking-tight truncate">{{ $ticket->titulo }}</h4>
                                <div class="flex flex-wrap items-center gap-2 mt-2">
                                     <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-[0.5rem] text-[9px] font-black uppercase tracking-wider
                                         @if($ticket->agente) bg-blue-500/10 text-blue-400 border border-blue-500/20 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                         👤 Soporte: {{ $ticket->agente ? $ticket->agente->nombre_completo : 'Por asignar' }}
                                     </span>
                                     <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-[0.5rem] text-[9px] font-black uppercase tracking-wider
                                         @if($ticket->hora_visita) bg-amber-500/10 text-amber-400 border border-amber-500/20 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                         🕒 Visita: {{ $ticket->hora_visita ?? 'Pendiente' }}
                                     </span>
                                     <span class="inline-flex items-center gap-1 px-2.5 py-0.5 rounded-[0.5rem] text-[9px] font-black uppercase tracking-wider
                                         @if($ticket->tiempo_resolucion) bg-teal-500/10 text-teal-400 border border-teal-500/20 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                         ⏱️ Resol: {{ $ticket->tiempo_resolucion ? $ticket->tiempo_resolucion . ' min' : 'Pendiente' }}
                                     </span>
                                </div>
                            </div>
                            <div class="relative z-10 text-right hidden sm:block w-32 shrink-0">
                                <p class="text-[9px] font-black tracking-[0.2em] text-gray-500 uppercase">Apertura</p>
                                <p class="text-sm font-bold text-gray-300 mt-0.5">{{ $ticket->created_at->format('d/m/Y') }}</p>
                            </div>
                            
                            <div class="relative z-10 flex items-center gap-3 shrink-0">

                                <button wire:click.stop="deleteTicket({{ $ticket->id }})" wire:confirm="¿Estás seguro que deseas eliminar permanentemente este ticket? Esta acción no se puede deshacer y desaparecerá de todas las bandejas." class="w-9 h-9 rounded-full bg-red-500/10 text-red-400 border border-red-500/20 hover:bg-red-500 hover:text-white hover:border-red-500 hover:shadow-[0_0_15px_rgba(239,68,68,0.5)] flex items-center justify-center transition-all focus:outline-none opacity-0 group-hover:opacity-100 -translate-x-2 group-hover:translate-x-0" title="Eliminar ticket">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                </button>
                                <svg class="w-6 h-6 text-gray-600 group-hover:text-white transition-all shrink-0 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-24 bg-white/5 rounded-[3rem] border border-white/5 border-dashed">
                            <div class="w-20 h-20 bg-blue-600/10 border border-blue-500/20 text-blue-500 rounded-[2rem] mx-auto mb-6 flex items-center justify-center">
                                <svg class="w-10 h-10" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect width="8" height="4" x="8" y="2" rx="1" ry="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><path d="M12 11h4"/><path d="M12 16h4"/><path d="M8 11h.01"/><path d="M8 16h.01"/></svg>
                            </div>
                            <p class="text-gray-400 font-bold uppercase tracking-widest text-[11px]">No has generado tickets recientemente</p>
                            <button @click="activeTab = 'generar_ticket'" class="mt-8 mx-auto px-8 py-3 border border-blue-500/30 bg-blue-600/10 hover:bg-blue-600 hover:border-blue-600 text-white rounded-2xl font-black text-[10px] uppercase tracking-[0.2em] shadow-lg transition-all flex items-center gap-3">
                                <span>Iniciar Nueva Solicitud</span>
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 18 6-6-6-6"/></svg>
                            </button>
                        </div>
                        @endforelse
                        </div>

                        {{-- USER MODAL FOR SELECTED TICKET --}}
                        @if($showingDetail && $selectedTicketId)
                        <div class="fixed inset-0 bg-[#01010a]/85 backdrop-blur-xl z-[100] flex items-center justify-center p-4 sm:p-6">
                            <div class="w-full max-w-2xl bg-[#07071c] rounded-3xl shadow-[0_30px_80px_rgba(0,0,0,0.9)] flex flex-col border border-white/10 relative overflow-hidden" style="max-height: 90vh;">
                                @php
                                    $detTicket = \App\Models\Ticket::find($selectedTicketId);
                                @endphp
                                @if($detTicket)
                                <div class="p-8 border-b border-white/5 flex justify-between items-center bg-white/5">
                                    <div>
                                        <h3 class="text-2xl font-black text-white uppercase tracking-tighter">Radiografía del Ticket</h3>
                                        <p class="text-[9px] text-gray-500 uppercase font-black tracking-[0.3em] mt-1 text-blue-400">UUID: {{ $detTicket->id }}</p>
                                    </div>
                                    <button wire:click="$set('showingDetail', false)" class="w-12 h-12 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-2xl transition-all border border-white/5 hover:border-white/20">
                                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                                    </button>
                                </div>

                                <div class="flex-1 overflow-y-auto p-8 space-y-10 custom-scrollbar">
                                    <div class="p-6 rounded-[2rem] flex items-center gap-6 border @if($detTicket->estado->nombre === 'Completado') bg-emerald-500/10 border-emerald-500/20 @else bg-blue-600/10 border-blue-500/20 @endif">
                                        @if($detTicket->estado->nombre === 'Completado') <svg class="text-emerald-400 w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M12 22c5.523 0 10-4.477 10-10S17.523 2 12 2 2 6.477 2 12s4.477 10 10 10z"/><path d="m9 12 2 2 4-4"/></svg> 
                                        @else <svg class="text-blue-400 w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg> @endif
                                        <div class="flex-1">
                                            <p class="font-black text-xs uppercase tracking-[0.2em] mb-1 @if($detTicket->estado->nombre === 'Completado') text-emerald-400 @else text-blue-400 @endif">
                                                Estado Actual: {{ $detTicket->estado->nombre }}
                                            </p>
                                            <p class="text-[10px] text-gray-500 font-bold tracking-widest uppercase">Monitoreo en Tiempo Real</p>
                                        </div>
                                    </div>

                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-8 bg-white/5 p-8 rounded-[2rem] border border-white/5">
                                        <div>
                                            @if(auth()->user()->role === 'user')
                                                <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Encargado de Soporte</h5>
                                                <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest @if($detTicket->agente) bg-blue-600/20 text-blue-400 border border-blue-500/30 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                                    👤 {{ $detTicket->agente ? $detTicket->agente->nombre_completo : 'Sin Asignar' }}
                                                </span>
                                            @else
                                                <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Importancia</h5>
                                                <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-purple-600/20 text-purple-400 border border-purple-500/30">{{ $detTicket->prioridad->nombre ?? 'N/A' }}</span>
                                            @endif
                                        </div>
                                        <div>
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Motivo Reportado</h5>
                                            <p class="text-xs font-bold text-gray-300 uppercase tracking-tight">{{ $detTicket->titulo }}</p>
                                        </div>
                                        <div>
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Hora de Visita Agente TI</h5>
                                            <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest @if($detTicket->hora_visita) bg-amber-500/20 text-amber-400 border border-amber-500/30 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                                🕒 {{ $detTicket->hora_visita ?? 'Pendiente de programar' }}
                                            </span>
                                        </div>
                                        <div>
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Tiempo Estimado de Resolución</h5>
                                            <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest @if($detTicket->tiempo_resolucion) bg-teal-500/20 text-teal-400 border border-teal-500/30 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                                ⏱️ {{ $detTicket->tiempo_resolucion ? $detTicket->tiempo_resolucion . ' minutos' : 'Pendiente de estimar' }}
                                            </span>
                                        </div>
                                        <div class="col-span-1 md:col-span-2 border-t border-white/10 pt-6 mt-2">
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Declaración Inicial</h5>
                                            <div class="bg-black/40 p-6 rounded-2xl text-xs font-medium text-gray-400 border border-white/5 shadow-inner leading-relax italic mb-6">
                                                "{{ $detTicket->descripcion }}"
                                            </div>
                                        </div>

                                        @if($detTicket->archivosAdjuntos && $detTicket->archivosAdjuntos->count() > 0)
                                        <div class="col-span-1 md:col-span-2 border-t border-white/10 pt-6 mt-2">
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Archivos Adjuntos</h5>
                                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                                @foreach($detTicket->archivosAdjuntos as $adjunto)
                                                <div class="flex items-center justify-between bg-white/[0.02] border border-white/5 rounded-2xl p-4 transition-all hover:bg-white/[0.04]">
                                                    <div class="flex items-center gap-3 truncate min-w-0">
                                                        <div class="w-9 h-9 rounded-xl bg-blue-600/10 border border-blue-500/20 text-blue-400 flex items-center justify-center shrink-0">
                                                            @if(Str::endsWith(strtolower($adjunto->nombre_archivo), ['.pdf']))
                                                                📄
                                                            @elseif(Str::endsWith(strtolower($adjunto->nombre_archivo), ['.jpg', '.jpeg', '.png', '.gif', '.svg']))
                                                                🖼️
                                                            @elseif(Str::endsWith(strtolower($adjunto->nombre_archivo), ['.doc', '.docx']))
                                                                📝
                                                            @else
                                                                📁
                                                            @endif
                                                        </div>
                                                        <div class="truncate">
                                                            <p class="font-bold text-white text-xs truncate" title="{{ $adjunto->nombre_archivo }}">{{ $adjunto->nombre_archivo }}</p>
                                                            <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-0.5">Evidencia</p>
                                                        </div>
                                                    </div>
                                                    <div class="flex gap-2 shrink-0">
                                                        <button type="button" wire:click="viewAttachment({{ $adjunto->id }})" class="px-3 py-1.5 bg-teal-600/20 hover:bg-teal-600 text-teal-400 hover:text-white rounded-xl border border-teal-500/20 transition-all font-bold text-[9px] uppercase tracking-wider">
                                                            Ver
                                                        </button>
                                                        <button type="button" wire:click="downloadAttachment({{ $adjunto->id }})" class="px-3 py-1.5 bg-white/5 hover:bg-white/10 text-gray-300 rounded-xl border border-white/5 transition-all font-bold text-[9px] uppercase tracking-wider">
                                                            Bajar
                                                        </button>
                                                    </div>
                                                </div>
                                                @endforeach
                                            </div>
                                        </div>
                                        @endif
                                    </div>

                                    <div class="space-y-6">
                                        <h5 class="font-black text-white uppercase tracking-tighter text-xl flex items-center gap-3">
                                            <svg class="w-5 h-5 text-blue-500" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                                            Historial de Actividad
                                        </h5>
                                        <div class="space-y-6">

                                            @foreach($detTicket->historial ?? [] as $hist)
                                                @if($hist->visible_para_usuario || in_array(auth()->user()->role, ['admin', 'agente']))
                                                <div class="flex flex-col items-center animate-in fade-in slide-in-from-bottom-2 my-4">
                                                    <div class="w-full max-w-lg p-5 rounded-[2rem] text-xs leading-relaxed {{ $hist->accion == 'Ticket Cancelado' ? 'bg-red-900/20 border border-red-500/30' : 'bg-green-900/20 border border-green-500/30' }} text-center shadow-lg">
                                                        <p class="font-black text-[11px] mb-2 uppercase tracking-[0.2em] {{ $hist->accion == 'Ticket Cancelado' ? 'text-red-400' : 'text-green-400' }}">
                                                            <i class="fa-solid {{ $hist->accion == 'Ticket Cancelado' ? 'fa-ban' : 'fa-check-double' }}"></i> 
                                                            {{ $hist->accion }}
                                                        </p>
                                                        @if($hist->causa_solucion_id)
                                                            <p class="font-bold text-white uppercase text-[10px] tracking-widest mb-1">Causa de Solución: <span class="text-gray-300">{{ optional($hist->causaSolucion)->nombre }}</span></p>
                                                        @endif
                                                        @if($hist->motivo_cancelacion_id)
                                                            <p class="font-bold text-white uppercase text-[10px] tracking-widest mb-1">Motivo de Cancelación: <span class="text-gray-300">{{ optional($hist->motivoCancelacion)->nombre }}</span></p>
                                                        @endif
                                                        @if($hist->detalles)
                                                            <p class="text-gray-300 italic mt-2 px-4">"{{ $hist->detalles }}"</p>
                                                        @endif
                                                        @if($hist->adjunto_path)
                                                            <div class="mt-3">
                                                                <button type="button" wire:click="downloadAttachmentPath('{{ $hist->adjunto_path }}')" class="inline-flex items-center gap-1 bg-blue-600/10 hover:bg-blue-600/20 text-blue-400 border border-blue-500/20 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase transition-all">
                                                                    <i class="fa-solid fa-paperclip"></i> Descargar Evidencia Adjunta
                                                                </button>
                                                            </div>
                                                        @endif
                                                        <p class="text-[9px] text-gray-500 font-bold uppercase tracking-widest mt-4">Registrado por: {{ optional($hist->usuario)->nombre_completo ?? 'Sistema' }}</p>
                                                    </div>
                                                    <span class="text-[9px] text-gray-600 font-bold uppercase tracking-widest mt-2 px-3">{{ $hist->created_at->format('d/m/Y H:i') }}</span>
                                                </div>
                                                @endif
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                {{-- Chat input moved to floating widget --}}
                                
                                {{-- Permite al usuario reabrir si se cerró/completó --}}
                                @if(in_array($detTicket->estado->nombre, ['Completado', 'Cerrado', 'Resuelto']))
                                    <div class="px-8 pb-8 bg-[#050510] flex justify-end">
                                        <button wire:click="reopenTicket('{{ $detTicket->id }}'); $set('showingDetail', false)"
                                                wire:confirm="¿Estás seguro de reabrir este ticket?"
                                                class="px-6 py-3 rounded-xl text-xs font-black uppercase bg-amber-600/10 text-amber-500 border border-amber-500/30 hover:bg-amber-600/20 transition-all flex items-center gap-2">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                            Reabrir Ticket
                                        </button>
                                    </div>
                                @endif
                                
                                @endif
                            </div>
                            
                            {{-- ZENDESK-STYLE FLOATING CHAT WIDGET ELIMINADO AQUÍ (MUDADO A WIDGET GLOBAL) --}}
                        </div>
                        @endif

                    </div>
                </div>
            </template>


            {{-- MI PERFIL - Integrated Profile View (Read-only for all roles) --}}
            <template x-if="activeTab === 'mi_perfil'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 max-w-2xl mx-auto">
                    {{-- Header --}}
                    <div class="mb-8 flex items-center justify-between">
                        <div>
                            <h3 class="text-3xl font-black text-white uppercase tracking-tighter">Mi Perfil</h3>
                            <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Información de tu cuenta — solo lectura</p>
                        </div>
                        <button @click="activeTab = 'generar_ticket'" class="flex items-center gap-2 px-4 py-2.5 bg-white/5 hover:bg-white/10 border border-white/10 rounded-xl text-[10px] font-black text-gray-400 hover:text-white uppercase tracking-widest transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                            Volver
                        </button>
                    </div>

                    {{-- Profile Card --}}
                    <div class="bg-[#0d0d20]/80 backdrop-blur-2xl border border-white/8 rounded-[2rem] overflow-hidden shadow-[0_20px_50px_rgba(0,0,0,0.7)]">
                        
                        <form wire:submit.prevent="updateMyProfile">
                            {{-- Avatar / Hero Section --}}
                            <div class="relative px-8 pt-10 pb-8 bg-gradient-to-br from-blue-600/10 via-indigo-600/5 to-transparent border-b border-white/5">
                                <div class="absolute inset-0 pointer-events-none">
                                    <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full blur-3xl"></div>
                                    <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-600/5 rounded-full blur-3xl"></div>
                                </div>
                                <div class="relative z-10 flex items-center gap-6">
                                    {{-- Editable Avatar Circle --}}
                                    <div class="relative group cursor-pointer" onclick="document.getElementById('profilePhotoInput').click()">
                                        <div class="w-20 h-20 rounded-[1.5rem] bg-[#0b0c16] border border-white/10 flex items-center justify-center text-white font-black text-2xl shadow-[0_0_30px_rgba(99,102,241,0.4)] shrink-0 overflow-hidden relative">
                                            @if ($newProfilePhoto)
                                                <img src="{{ $newProfilePhoto->temporaryUrl() }}" class="w-full h-full object-cover">
                                            @else
                                                <img src="{{ Auth::user()->profile_photo_url }}" class="w-full h-full object-cover">
                                            @endif
                                            <div class="absolute inset-0 bg-black/50 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col items-center justify-center">
                                                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                                            </div>
                                        </div>
                                        <input type="file" id="profilePhotoInput" wire:model="newProfilePhoto" class="hidden" accept="image/*">
                                    </div>
                                    <div>
                                        <h2 class="text-2xl font-black text-white tracking-tight uppercase leading-tight">
                                            {{ Auth::user()->nombre_completo }}
                                        </h2>
                                        @php
                                            $roleLabel = match(auth()->user()->role) {
                                                'admin' => 'Administrador',
                                                'agente' => 'Agente TI',
                                                default => 'Usuario',
                                            };
                                            $roleColor = match(auth()->user()->role) {
                                                'admin' => 'text-emerald-400',
                                                'agente' => 'text-purple-400',
                                                default => 'text-blue-400',
                                            };
                                        @endphp
                                        <p class="text-sm font-bold uppercase tracking-widest mt-1 {{ $roleColor }}">{{ $roleLabel }}</p>
                                        <p class="text-[10px] text-gray-500 font-medium mt-0.5">
                                            Cuenta activa desde {{ Auth::user()->created_at ? Auth::user()->created_at->format('M Y') : 'N/A' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            {{-- Fields --}}
                            <div class="p-8 space-y-6">

                                {{-- Nombre --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                        Nombre Completo
                                    </label>
                                    <input type="text" wire:model="newProfileName" required
                                        class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder-gray-500">
                                    @error('newProfileName') <span class="text-xs text-red-400 font-bold block mt-1 pl-1">{{ $message }}</span> @enderror
                                </div>

                                {{-- Botón de Guardar General (Foto y Nombre) --}}
                                <div class="pt-2">
                                    <button type="submit"
                                        class="px-6 py-3 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white text-[11px] font-black uppercase tracking-wider rounded-xl transition-all shadow-[0_0_20px_rgba(37,99,235,0.4)] flex items-center gap-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Guardar Cambios del Perfil
                                    </button>
                                </div>
                                
                                <div class="w-full h-px bg-white/5 my-4"></div>

                                {{-- Email --}}
                                <div class="space-y-2">
                                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                        Correo Electrónico
                                    </label>
                                    <div class="flex gap-3">
                                        <div class="relative flex-1">
                                            <input type="email" wire:model="userProfileEmail" required
                                                class="w-full bg-white/[0.03] border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all">
                                        </div>
                                        <button type="button" wire:click="updateProfileEmail"
                                            class="px-5 py-3 bg-white/10 hover:bg-white/15 border border-white/10 active:scale-95 text-white text-[10px] font-black uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center gap-1.5 shrink-0">
                                            Actualizar Correo
                                        </button>
                                    </div>
                                    @error('userProfileEmail') <span class="text-xs text-red-400 font-bold block mt-1 pl-1">{{ $message }}</span> @enderror
                                    @if(session()->has('profile_success'))
                                        <span class="text-xs text-emerald-400 font-bold block mt-1 pl-1 animate-pulse">{{ session('profile_success') }}</span>
                                    @endif
                                </div>

                            {{-- Credencial / Código de acceso --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/></svg>
                                    Credencial de Acceso
                                </label>
                                <div class="w-full bg-white/[0.03] border border-white/8 rounded-xl px-4 py-3.5 flex items-center gap-3">
                                    <div class="flex items-center gap-3 flex-1">
                                        <span class="font-black text-sm tracking-widest {{ $roleColor }}">
                                            {{ Auth::user()->codigo_acceso ?? '—' }}
                                        </span>
                                    </div>
                                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest border border-white/8 px-2 py-0.5 rounded">No editable</span>
                                </div>
                                <p class="text-[9px] text-gray-600 uppercase tracking-widest ml-1">
                                    🔒 La credencial es administrada exclusivamente por el área de TI.
                                </p>
                            </div>

                            {{-- Rol Badge --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                    Rol en el Sistema
                                </label>
                                <div class="w-full bg-white/[0.03] border border-white/8 rounded-xl px-4 py-3.5 flex items-center gap-3">
                                    <span class="text-sm font-black uppercase tracking-widest {{ $roleColor }}">{{ $roleLabel }}</span>
                                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest border border-white/8 px-2 py-0.5 rounded ml-auto">No editable</span>
                                </div>
                            </div>

                        </div>

                        {{-- Footer note --}}
                        <div class="px-8 pb-8">
                            <div class="bg-blue-600/5 border border-blue-500/10 rounded-xl p-4 flex items-start gap-3">
                                <svg class="w-4 h-4 text-blue-400 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                <p class="text-[10px] text-gray-400 font-medium leading-relaxed">
                                    Para actualizar tu información o credencial de acceso, contacta al <span class="text-blue-400 font-black">Administrador del Sistema</span> o al área de TI.
                                </p>
                            </div>
                        </div>

                    </div>
                </div>
            </template>

            {{-- ADMIN CONFIGURATION / SETTINGS --}}
            <template x-if="activeTab === 'settings'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 max-w-6xl mx-auto space-y-12">
                    <div class="flex items-center justify-between border-b border-white/10 pb-8">
                        <h3 class="text-4xl font-black text-white uppercase tracking-tighter">Administración</h3>
                        <div class="flex gap-4">
                            <button class="px-8 py-3 bg-white/5 hover:bg-white/10 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                                + Nuevo
                            </button>
                            <button class="px-8 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all">
                                Guardar
                            </button>
                        </div>
                    </div>

                    {{-- Dynamic CSS layout mapping the image --}}
                    <div class="space-y-16">
                        
                        {{-- Componentes Destacados Section --}}
                        <div class="space-y-6">
                            <h4 class="text-xl font-bold text-gray-300 font-black tracking-tighter">Destacado</h4>
                            <div class="flex gap-6">
                                <div class="group cursor-pointer relative overflow-hidden w-64 h-32 rounded-2xl bg-[#091515] p-6 border border-white/10 transition-all hover:scale-105 active:scale-95 shadow-2xl">
                                  {{-- Resorte Decorativo --}}
                                  <div class="absolute inset-0 overflow-hidden rounded-2xl pointer-events-none">
                                    <svg class="absolute -right-2 top-1/2 -translate-y-1/2 w-28 h-32 opacity-20 text-white" viewBox="0 0 100 120" fill="none">
                                      {{-- Sombra/Profundidad del resorte --}}
                                      <g stroke="currentColor" stroke-width="16" stroke-linecap="round" stroke-opacity="0.1">
                                        <path d="M 80 20 Q 95 35, 80 50" />
                                        <path d="M 80 50 Q 95 65, 80 80" />
                                        <path d="M 80 80 Q 95 95, 80 110" />
                                      </g>
                                      {{-- Vueltas frontales --}}
                                      <g stroke="currentColor" stroke-width="14" stroke-linecap="round" stroke-opacity="0.4">
                                        <path d="M 90 15 C 60 15, 60 35, 90 35" />
                                        <path d="M 90 40 C 60 40, 60 60, 90 60" />
                                        <path d="M 90 65 C 60 65, 60 85, 90 85" />
                                        <path d="M 90 90 C 60 90, 60 110, 90 110" />
                                      </g>
                                    </svg>
                                  </div>
                            
                                  {{-- Texto Principal --}}
                                  <div class="relative z-10 h-full flex items-center">
                                    <h3 class="text-4xl font-black text-white italic tracking-tighter uppercase">
                                      BUCH
                                    </h3>
                                  </div>
                                </div>
                            </div>
                        </div>

                        {{-- Recientes Section --}}
                        <div class="space-y-6">
                            <h4 class="text-xl font-bold text-gray-300 font-black tracking-tighter">Recientes</h4>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all flex items-center gap-5">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg></div>
                                    <p class="text-sm font-bold text-gray-200 uppercase tracking-tight">Planes y Facturación</p>
                                </a>
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all flex items-center gap-5">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 5.636l-3.536 3.536m0 5.656l3.536 3.536M9.172 9.172L5.636 5.636m3.536 9.192l-3.536 3.536M21 12a9 9 0 11-18 0 9 9 0 0118 0zm-5 0a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                                    <p class="text-sm font-bold text-gray-200 uppercase tracking-tight">Configuración de Ayuda</p>
                                </a>
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all flex items-center gap-5">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                    <p class="text-sm font-bold text-gray-200 uppercase tracking-tight">Info de la Cuenta</p>
                                </a>
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all flex items-center gap-5">
                                    <div class="text-blue-500"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                                    <p class="text-sm font-bold text-gray-200 uppercase tracking-tight">Correo Electrónico</p>
                                </a>
                            </div>
                        </div>

                        {{-- Equipo Section --}}
                        <div class="space-y-6">
                            <h4 class="text-xl font-bold text-gray-300 font-black tracking-tighter">Equipo</h4>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="text-teal-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg></div>
                                        <h5 class="text-md font-bold text-white tracking-widest uppercase">Agentes</h5>
                                    </div>
                                    <p class="text-[10px] text-gray-500 tracking-wide">Defina el tipo, idioma y alcance del trabajo de los agentes.</p>
                                </a>
                                <a href="#" class="p-6 bg-blue-600/10 border border-blue-500/20 rounded-3xl hover:bg-blue-600/20 transition-all group relative overflow-hidden">
                                    <div class="absolute inset-0 bg-blue-500/5 group-hover:bg-blue-500/10 transition"></div>
                                    <div class="relative z-10 flex items-center gap-3 mb-3">
                                        <div class="text-blue-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/></svg></div>
                                        <h5 class="text-md font-bold text-white tracking-widest uppercase">Grupos</h5>
                                    </div>
                                    <p class="relative z-10 text-[10px] text-gray-400 tracking-wide">Organice a los agentes y reciba notificaciones acerca de tickets sin atender.</p>
                                </a>
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="text-purple-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg></div>
                                        <h5 class="text-md font-bold text-white tracking-widest uppercase">Horario Laboral</h5>
                                    </div>
                                    <p class="text-[10px] text-gray-500 tracking-wide">Defina horarios diarios y festivos para establecer expectativas.</p>
                                </a>
                            </div>
                        </div>

                        {{-- Canales Section --}}
                        <div class="space-y-6">
                            <h4 class="text-xl font-bold text-gray-300 font-black tracking-tighter">Canales</h4>
                            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="text-rose-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/></svg></div>
                                        <h5 class="text-md font-bold text-white tracking-widest uppercase">Portales</h5>
                                    </div>
                                    <p class="text-[10px] text-gray-500 tracking-wide">Personalice la marca, visibilidad y estructura de su portal.</p>
                                </a>
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="text-rose-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg></div>
                                        <h5 class="text-md font-bold text-white tracking-widest uppercase">Correo Electrónico</h5>
                                    </div>
                                    <p class="text-[10px] text-gray-500 tracking-wide">Integre buzones, configure respuestas y servidores personalizados.</p>
                                </a>
                                <a href="#" class="p-6 bg-white/5 border border-white/5 rounded-3xl hover:bg-white/10 transition-all group">
                                    <div class="flex items-center gap-3 mb-3">
                                        <div class="text-rose-400"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"/></svg></div>
                                        <h5 class="text-md font-bold text-white tracking-widest uppercase">Widgets</h5>
                                    </div>
                                    <p class="text-[10px] text-gray-500 tracking-wide">Inserte artículos de ayuda o un formulario de contacto en su página.</p>
                                </a>
                            </div>
                        </div>

                    </div>
                </div>
            </template>

            <template x-if="activeTab === 'inventory'">
                <livewire:inventory-panel wire:key="inventory-panel-core" />
            </template>

            <template x-if="activeTab === 'gestion_archivos'">
                <livewire:document-viewer wire:key="document-viewer-core" />
            </template>

            @if(auth()->user()->role !== 'user')
            <div x-show="activeTab === 'map'" class="h-full">
                <livewire:station-map wire:key="station-map-core" />
            </div>
            @endif

                                                
{{-- ═══════════════════════════════════════════════════════
     STATISTICS TAB — uses x-show so canvases always exist in DOM
     Charts are initialised in the <script> block below.
═══════════════════════════════════════════════════════════ --}}
<div x-show="activeTab === 'statistics'" style="display: none;"
     wire:poll.15s
     id="statistics-container"
     data-stats="{{ json_encode([
         'months' => $trendMonths->values(),
         'created' => $trendData,
         'closed' => $trendClosedData,
         'canceled' => $trendCanceledData,
         'pLabels' => array_keys($plantaCounts),
         'pValues' => array_values($plantaCounts),
         'cLabels' => $categoryData->keys()->values(),
         'cValues' => $categoryData->values()->values(),
         'sOpen' => $statusCounts[1] ?? 0,
         'sProc' => $statusCounts[2] ?? 0,
         'sDone' => ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0),
         'sCanc' => $statusCounts[5] ?? 0
     ]) }}"
     x-init="$watch('activeTab', val => { if (val === 'statistics' && window.buildHubCharts) { setTimeout(window.buildHubCharts, 350); } })" 
     class="pb-10">
    {{-- ── HEADER BANNER ───────────────────────────────────────── --}}
    <div style="position:relative;overflow:hidden;border-radius:1.5rem;background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(6,182,212,0.10),rgba(16,185,129,0.08));border:1px solid rgba(99,102,241,0.25);margin-bottom:2rem;">
        <div style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;background:radial-gradient(circle,rgba(99,102,241,0.3),transparent 70%);pointer-events:none;"></div>
        <div style="position:relative;z-index:1;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1.5rem;padding:2rem 2.5rem;">
            <div>
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                    <div style="width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-chart-mixed" style="color:#fff;font-size:14px;"></i>
                    </div>
                    <h2 style="font-size:1.5rem;font-weight:900;background:linear-gradient(to right,#fff,#c7d2fe,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-transform:uppercase;letter-spacing:-0.03em;margin:0;">
                        Centro de Mando Analítico
                    </h2>
                </div>
                <p style="font-size:13px;color:rgba(147,197,253,0.7);font-weight:500;max-width:500px;margin:0;">
                    Métricas en tiempo real desde tu base de datos operativa.
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:8px;padding:6px 14px;border-radius:10px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);">
                    <span style="width:8px;height:8px;background:#34d399;border-radius:50%;display:inline-block;animation:pulse 1.5s ease-in-out infinite;box-shadow:0 0 8px #34d399;"></span>
                    <span style="font-size:10px;font-weight:800;color:#34d399;text-transform:uppercase;letter-spacing:0.15em;">Sistema en línea</span>
                </div>

            </div>
        </div>
    </div>

    {{-- ── KPI CARDS (Bento Grid Colorido) ───────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(200px,1fr));gap:1rem;margin-bottom:1.5rem;">
        
        {{-- Registrados --}}
        <div class="rounded-2xl p-5 flex flex-col justify-between shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6">
                <span class="text-white font-bold text-xs uppercase tracking-widest drop-shadow-sm">Registrados</span>
                <i class="fa-solid fa-file-lines text-white/40 text-3xl"></i>
            </div>
            <div>
                <div class="text-white font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ array_sum($statusCounts->toArray()) }}</div>
            </div>
        </div>

        {{-- Asignados --}}
        <div class="rounded-2xl p-5 flex flex-col justify-between shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6">
                <span class="text-white font-bold text-xs uppercase tracking-widest drop-shadow-sm">Asignados</span>
                <i class="fa-solid fa-user-check text-white/40 text-3xl"></i>
            </div>
            <div>
                <div class="text-white font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ $stats['assigned'] ?? 0 }}</div>
            </div>
        </div>

        {{-- En Proceso --}}
        <div class="rounded-2xl p-5 flex flex-col justify-between shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6">
                <span class="text-white font-bold text-xs uppercase tracking-widest drop-shadow-sm">En Proceso</span>
                <i class="fa-solid fa-spinner text-white/40 text-3xl"></i>
            </div>
            <div>
                <div class="text-white font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ $statusCounts[2] ?? 0 }}</div>
            </div>
        </div>

        {{-- Finalizados --}}
        <div class="rounded-2xl p-5 flex flex-col justify-between shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6">
                <span class="text-white font-bold text-xs uppercase tracking-widest drop-shadow-sm">Finalizados</span>
                <i class="fa-solid fa-check-double text-white/40 text-3xl"></i>
            </div>
            <div>
                <div class="text-white font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0) }}</div>
            </div>
        </div>
        
        {{-- Cancelados --}}
        <div class="rounded-2xl p-5 flex flex-col justify-between shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6">
                <span class="text-white font-bold text-xs uppercase tracking-widest drop-shadow-sm">Cancelados</span>
                <i class="fa-solid fa-ban text-white/40 text-3xl"></i>
            </div>
            <div>
                <div class="text-white font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ $stats['canceled'] ?? 0 }}</div>
            </div>
        </div>
        
        {{-- Inventario --}}
        <div class="rounded-2xl p-5 flex flex-col justify-between shadow-lg relative overflow-hidden group hover:scale-[1.02] transition-transform" style="background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);">
            <div class="absolute -right-4 -top-4 w-24 h-24 bg-white opacity-10 rounded-full group-hover:scale-125 transition-transform duration-500 pointer-events-none"></div>
            <div class="flex justify-between items-start mb-6">
                <span class="text-white font-bold text-xs uppercase tracking-widest drop-shadow-sm">Inventario Global</span>
                <i class="fa-solid fa-boxes-stacked text-white/40 text-3xl"></i>
            </div>
            <div>
                <div class="text-white font-black text-4xl sm:text-5xl drop-shadow-md leading-none">{{ $stats['inv_total'] ?? 0 }}</div>
            </div>
        </div>

    </div>

    {{-- ── TENDENCIA (full width) ───────────────────────────────── --}}
    <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);margin-bottom:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.5rem 1.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:10px;background:rgba(99,102,241,0.2);border:1px solid rgba(99,102,241,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-line" style="color:#818cf8;font-size:11px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.15em;margin:0;">Tendencia de Tickets</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin:2px 0 0;">Últimos 7 meses · datos en vivo</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:18px;height:3px;background:#6366f1;border-radius:2px;"></div>
                    <span style="font-size:10px;color:#94a3b8;font-weight:600;">Creados</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:18px;height:0;border-top:2px dashed #10b981;"></div>
                    <span style="font-size:10px;color:#94a3b8;font-weight:600;">Cerrados</span>
                </div>
            </div>
        </div>
        <div style="padding:1rem 1.75rem 1.75rem;">
            <div wire:ignore style="position:relative;height:280px;width:100%;">
                <canvas id="ch-trend"></canvas>
            </div>
        </div>
    </div>

    {{-- ── 3 CHARTS ROW ─────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem;margin-bottom:1.5rem;">

        {{-- Distribución Planta --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:10px;padding:1.25rem 1.5rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:30px;height:30px;border-radius:10px;background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building" style="color:#22d3ee;font-size:10px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.12em;margin:0;">Distribución Planta</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;margin:1px 0 0;">Tickets por planta</p>
                </div>
            </div>
            <div style="padding:1rem 1.25rem 1.25rem;flex:1;">
                <div wire:ignore style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-planta"></canvas>
                </div>
            </div>
        </div>

        {{-- Tipos de Problema --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:10px;padding:1.25rem 1.5rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:30px;height:30px;border-radius:10px;background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-tags" style="color:#c084fc;font-size:10px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.12em;margin:0;">Tipos de Problema</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;margin:1px 0 0;">Categorías de incidencia</p>
                </div>
            </div>
            <div style="padding:1rem 1.25rem 1.25rem;flex:1;">
                <div wire:ignore style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-cat"></canvas>
                </div>
            </div>
        </div>

        {{-- Proporción de Estados --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:10px;padding:1.25rem 1.5rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:30px;height:30px;border-radius:10px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-pie" style="color:#fbbf24;font-size:10px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.12em;margin:0;">Proporción de Estados</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;margin:1px 0 0;">Distribución de estados</p>
                </div>
            </div>
            <div style="padding:1rem 1.25rem 1.25rem;flex:1;">
                <div wire:ignore style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-status"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- ── TABLA RECIENTE ──────────────────────────────────────────── --}}
    <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.5rem 1.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:10px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-bolt" style="color:#fbbf24;font-size:11px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.15em;margin:0;">Actividad Reciente</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin:2px 0 0;">Últimos tickets registrados</p>
                </div>
            </div>
            <button wire:click="setTab('tickets')" style="font-size:10px;font-weight:800;color:#818cf8;text-transform:uppercase;letter-spacing:0.12em;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;padding:0;">
                Ver todos <i class="fa-solid fa-arrow-right" style="font-size:8px;"></i>
            </button>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;">
                <thead>
                    <tr>
                        <th style="padding:11px 16px;text-align:left;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">ID</th>
                        <th style="padding:11px 16px;text-align:left;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Título</th>
                        <th style="padding:11px 16px;text-align:left;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Planta</th>
                        <th style="padding:11px 16px;text-align:center;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Estado</th>
                        <th style="padding:11px 16px;text-align:right;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets->sortByDesc('created_at')->take(8) as $t)
                    <tr style="border-top:1px solid rgba(255,255,255,0.04);">
                        <td style="padding:12px 16px;">
                            <span style="font-size:11px;font-weight:800;color:#475569;background:rgba(255,255,255,0.05);padding:3px 8px;border-radius:6px;">#{{ str_pad($t->id,4,'0',STR_PAD_LEFT) }}</span>
                        </td>
                        <td style="padding:12px 16px;font-size:12px;font-weight:600;color:#cbd5e1;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ Str::limit($t->titulo ?? 'Sin título', 42) }}
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:9px;font-weight:800;color:#c4b5fd;background:rgba(139,92,246,0.1);padding:3px 10px;border-radius:6px;border:1px solid rgba(139,92,246,0.2);text-transform:uppercase;letter-spacing:0.1em;">Planta {{ $t->planta ?? 1 }}</span>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            @if($t->estado_id == 1)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#60a5fa;background:rgba(59,130,246,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(59,130,246,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#3b82f6;border-radius:50%;display:inline-block;"></span>Abierto</span>
                            @elseif($t->estado_id == 2)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#fbbf24;background:rgba(245,158,11,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(245,158,11,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#f59e0b;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span>Proceso</span>
                            @elseif($t->estado_id == 3)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#34d399;background:rgba(16,185,129,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(16,185,129,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#10b981;border-radius:50%;display:inline-block;"></span>Resuelto</span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#94a3b8;background:rgba(100,116,139,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(100,116,139,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#64748b;border-radius:50%;display:inline-block;"></span>Cerrado</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;font-size:11px;font-weight:600;color:#475569;text-align:right;white-space:nowrap;">
                            {{ $t->created_at->format('d/m/Y') }}
                            <span style="color:#334155;font-size:10px;margin-left:4px;">{{ $t->created_at->format('H:i') }}</span>
                            <div style="font-size:8px;color:#64748b;margin-top:2px;">{{ $t->created_at->diffForHumans() }}</div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;font-size:13px;color:#475569;">No hay tickets registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@once
<script>
(function () {
    var TIP = {
        backgroundColor: '#0f172a',
        titleColor: '#f1f5f9',
        bodyColor: '#94a3b8',
        borderColor: '#1e293b',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 10
    };

        window.buildHubCharts = function() {
        if (typeof Chart === 'undefined') {
            let script = document.createElement('script');
            script.src = 'https://cdn.jsdelivr.net/npm/chart.js';
            script.onload = function() { window.buildHubCharts(); };
            document.head.appendChild(script);
            return;
        }

        var container = document.getElementById('statistics-container');
        if (!container) return;
        var statsAttr = container.getAttribute('data-stats');
        if (!statsAttr) return;
        var STAT_DATA = JSON.parse(statsAttr);

        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(51,65,85,0.25)';

                /* 1. TENDENCIA */
        var c1 = document.getElementById('ch-trend');
        if (c1) {
            if (c1._ch) {
                c1._ch.data.labels = STAT_DATA.months;
                c1._ch.data.datasets[0].data = STAT_DATA.created;
                c1._ch.data.datasets[1].data = STAT_DATA.closed;
                if(c1._ch.data.datasets[2]) c1._ch.data.datasets[2].data = STAT_DATA.canceled;
                c1._ch.update();
            } else {
                c1._ch = new Chart(c1, {
                    type: 'line',
                    data: {
                        labels: STAT_DATA.months,
                        datasets: [
                            {
                                label: 'Creados', data: STAT_DATA.created, borderColor: '#6366f1',
                                backgroundColor: function(ctx) {
                                    var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 260);
                                    g.addColorStop(0, 'rgba(99,102,241,0.28)');
                                    g.addColorStop(1, 'rgba(99,102,241,0)');
                                    return g;
                                },
                                borderWidth: 3, tension: 0.45, fill: true,
                                pointBackgroundColor: '#6366f1', pointBorderColor: '#0f172a',
                                pointRadius: 5, pointHoverRadius: 9, pointBorderWidth: 2
                            },
                            {
                                label: 'Cerrados', data: STAT_DATA.closed, borderColor: '#10b981',
                                backgroundColor: 'transparent',
                                borderWidth: 2, borderDash: [6, 4], tension: 0.45,
                                pointBackgroundColor: '#10b981', pointBorderColor: '#0f172a',
                                pointRadius: 4, pointHoverRadius: 7, pointBorderWidth: 2
                            },
                            {
                                label: 'Cancelados', data: STAT_DATA.canceled, borderColor: '#ef4444',
                                backgroundColor: 'transparent',
                                borderWidth: 2, borderDash: [3, 3], tension: 0.45,
                                pointBackgroundColor: '#ef4444', pointBorderColor: '#0f172a',
                                pointRadius: 4, pointHoverRadius: 7, pointBorderWidth: 2
                            }
                        ]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        interaction: { mode: 'index', intersect: false },
                        plugins: { legend: { labels: { usePointStyle: true, padding: 20, font: { size: 11, weight: 'bold' } } }, tooltip: TIP },
                        scales: { x: { grid: { color: 'rgba(51,65,85,0.2)' } }, y: { grid: { color: 'rgba(51,65,85,0.2)' }, beginAtZero: true } }
                    }
                });
            }
        }

        /* 2. DISTRIBUCIÓN PLANTA */
        var c2 = document.getElementById('ch-planta');
        var pLabels = STAT_DATA.pLabels.length ? STAT_DATA.pLabels : ['Sin datos'];
        var pValues = STAT_DATA.pValues.length ? STAT_DATA.pValues : [0.001]; // Prevents crash
        if (c2) {
            if (c2._ch) {
                c2._ch.data.labels = pLabels;
                c2._ch.data.datasets[0].data = pValues;
                c2._ch.update();
            } else {
                c2._ch = new Chart(c2, {
                    type: 'doughnut',
                    data: { labels: pLabels, datasets: [{ data: pValues, backgroundColor: ['#6366f1','#06b6d4'], borderColor: '#080c1a', borderWidth: 4, hoverOffset: 8 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }, tooltip: TIP } }
                });
            }
        }

        /* 3. TIPOS DE PROBLEMA */
        var c3 = document.getElementById('ch-cat');
        var cLabels = STAT_DATA.cLabels.length ? STAT_DATA.cLabels : ['Sin datos'];
        var cValues = STAT_DATA.cValues.length ? STAT_DATA.cValues : [0.001];
        if (c3) {
            var bgs = ['rgba(168,85,247,0.75)','rgba(59,130,246,0.75)','rgba(16,185,129,0.75)','rgba(245,158,11,0.75)','rgba(239,68,68,0.75)','rgba(20,184,166,0.75)'];
            if (c3._ch) {
                c3._ch.data.labels = cLabels;
                c3._ch.data.datasets[0].data = cValues;
                c3._ch.update();
            } else {
                c3._ch = new Chart(c3, {
                    type: 'bar',
                    data: { labels: cLabels, datasets: [{ label: 'Tickets', data: cValues, backgroundColor: bgs, borderRadius: 8, barThickness: 22 }] },
                    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: TIP }, scales: { x: { beginAtZero: true } } }
                });
            }
        }

        /* 4. PROPORCIÓN DE ESTADOS */
        var c4 = document.getElementById('ch-status');
        if (c4) {
            if (c4._ch) {
                c4._ch.data.datasets[0].data = [STAT_DATA.sOpen, STAT_DATA.sProc, STAT_DATA.sDone, STAT_DATA.sCanc];
                c4._ch.update();
            } else {
                c4._ch = new Chart(c4, {
                    type: 'doughnut',
                    data: { labels: ['Abiertos','En Proceso','Resueltos','Cancelados'], datasets: [{ data: [STAT_DATA.sOpen, STAT_DATA.sProc, STAT_DATA.sDone, STAT_DATA.sCanc], backgroundColor: ['#3b82f6','#f59e0b','#10b981','#ef4444'], borderColor: '#080c1a', borderWidth: 4, hoverOffset: 8 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }, tooltip: TIP } }
                });
            }
        }
    };

    /* Run after page fully loads */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', window.buildHubCharts);
    } else {
        window.buildHubCharts();
    }

    /* Also re-run whenever Livewire refreshes the component */
    document.addEventListener('livewire:morph', window.buildHubCharts);
    document.addEventListener('livewire:update', function() { setTimeout(window.buildHubCharts, 150); });
})();
</script>
@endonce



            {{-- Causas Tab --}}
            <template x-if="activeTab === 'causas'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-8 max-w-4xl mx-auto">
                    <div class="flex items-center justify-between">
                        <h3 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">Causas de Solución</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Cierre de Tickets</p>
                    </div>
                    
                    <div class="bg-white/5 border border-white/10 p-8 rounded-[2rem] shadow-2xl">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">1. Seleccionar Ticket a Finalizar</h4>
                        <select wire:model.live="tabSelectedTicketId" class="w-full bg-[#07071c] border border-white/10 rounded-xl px-4 py-4 text-xs font-bold text-white uppercase tracking-wider outline-none focus:border-green-500 transition-all mb-8">
                            <option value="">-- ELIGE UN TICKET ABIERTO --</option>
                            @php
                                $openTicketsC = \App\Models\Ticket::whereIn('estado_id', [1, 2]);
                                if(auth()->user()->role === 'agente') {
                                    $openTicketsC->where('agente_asignado_id', auth()->id());
                                }
                            @endphp
                            @foreach($openTicketsC->get() as $t)
                                <option value="{{ $t->id }}">TICKET #{{ $t->id }} - {{ Str::limit($t->titulo, 50) }}</option>
                            @endforeach
                        </select>

                        @if($tabSelectedTicketId && $tabTicketModel)
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">2. Información del Ticket</h4>
                            <div class="bg-[#050510] p-6 rounded-2xl border border-white/5 mb-8">
                                <p class="text-xs text-white font-bold mb-2 uppercase tracking-wide">Reportado por: <span class="text-gray-400">{{ $tabTicketModel->creador->name ?? 'N/A' }}</span></p>
                                <p class="text-[10px] text-gray-300 italic">"{{ $tabTicketModel->descripcion }}"</p>
                            </div>

                            <form wire:submit.prevent="guardarFinalizacionDesdeTab" class="space-y-6">
                                <div>
                                    <label class="text-[9px] font-black text-green-500 uppercase tracking-widest ml-1 mb-2 block">Causa de Solución</label>
                                    <select wire:model="causaSolucionId" class="w-full bg-[#07071c] border border-white/10 rounded-xl px-4 py-3 text-xs text-white outline-none focus:border-green-500 transition-all">
                                        <option value="">Seleccione una causa...</option>
                                        @foreach($causasSolucion as $causa)
                                            <option value="{{ $causa->id }}">{{ $causa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('causaSolucionId') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-green-500 uppercase tracking-widest ml-1 mb-2 block">Detalles de la Solución</label>
                                    <textarea wire:model="detallesResolucion" rows="4" class="w-full bg-[#07071c] border border-white/10 rounded-xl px-4 py-3 text-xs text-white outline-none focus:border-green-500 transition-all"></textarea>
                                    @error('detallesResolucion') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Adjuntar Evidencia (Opcional)</label>
                                    <input type="file" wire:model="archivoResolucion" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all cursor-pointer">
                                </div>
                                <button type="submit" class="w-full py-4 bg-green-600 hover:bg-green-500 text-white rounded-xl text-[11px] font-black uppercase tracking-[.3em] shadow-[0_0_15px_rgba(34,197,94,0.3)] transition-all">
                                    FINALIZAR TICKET AHORA
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </template>

            {{-- Motivos Tab --}}
            <template x-if="activeTab === 'motivos'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-8 max-w-4xl mx-auto">
                    <div class="flex items-center justify-between">
                        <h3 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">Motivos de Cancelación</h3>
                        <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest">Cancelación de Tickets</p>
                    </div>
                    
                    <div class="bg-white/5 border border-white/10 p-8 rounded-[2rem] shadow-2xl">
                        <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">1. Seleccionar Ticket a Cancelar</h4>
                        <select wire:model.live="tabSelectedTicketId" class="w-full bg-[#07071c] border border-white/10 rounded-xl px-4 py-4 text-xs font-bold text-white uppercase tracking-wider outline-none focus:border-red-500 transition-all mb-8">
                            <option value="">-- ELIGE UN TICKET ABIERTO --</option>
                            @php
                                $openTicketsM = \App\Models\Ticket::whereIn('estado_id', [1, 2]);
                                if(auth()->user()->role === 'agente') {
                                    $openTicketsM->where('agente_asignado_id', auth()->id());
                                }
                            @endphp
                            @foreach($openTicketsM->get() as $t)
                                <option value="{{ $t->id }}">TICKET #{{ $t->id }} - {{ Str::limit($t->titulo, 50) }}</option>
                            @endforeach
                        </select>

                        @if($tabSelectedTicketId && $tabTicketModel)
                            <h4 class="text-xs font-black text-gray-400 uppercase tracking-widest mb-6">2. Información del Ticket</h4>
                            <div class="bg-[#050510] p-6 rounded-2xl border border-white/5 mb-8">
                                <p class="text-xs text-white font-bold mb-2 uppercase tracking-wide">Reportado por: <span class="text-gray-400">{{ $tabTicketModel->creador->name ?? 'N/A' }}</span></p>
                                <p class="text-[10px] text-gray-300 italic">"{{ $tabTicketModel->descripcion }}"</p>
                            </div>

                            <form wire:submit.prevent="guardarCancelacionDesdeTab" class="space-y-6">
                                <div>
                                    <label class="text-[9px] font-black text-red-500 uppercase tracking-widest ml-1 mb-2 block">Motivo de Cancelación</label>
                                    <select wire:model="motivoCancelacionId" class="w-full bg-[#07071c] border border-white/10 rounded-xl px-4 py-3 text-xs text-white outline-none focus:border-red-500 transition-all">
                                        <option value="">Seleccione un motivo...</option>
                                        @foreach($motivosCancelacion as $motivo)
                                            <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @error('motivoCancelacionId') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                <div>
                                    <label class="text-[9px] font-black text-red-500 uppercase tracking-widest ml-1 mb-2 block">Detalles de Cancelación</label>
                                    <textarea wire:model="detallesResolucion" rows="4" class="w-full bg-[#07071c] border border-white/10 rounded-xl px-4 py-3 text-xs text-white outline-none focus:border-red-500 transition-all"></textarea>
                                    @error('detallesResolucion') <span class="text-red-400 text-[10px]">{{ $message }}</span> @enderror
                                </div>
                                
                                <div class="flex items-center gap-3 bg-[#07071c] p-4 rounded-xl border border-white/5">
                                    <input type="checkbox" wire:model="visibleAlUsuario" id="visibleUsuarioTab" class="w-4 h-4 rounded border-gray-600 bg-gray-700 text-red-500 focus:ring-red-500">
                                    <label for="visibleUsuarioTab" class="text-xs font-bold text-gray-300 uppercase tracking-wider cursor-pointer">Mostrar cancelación al usuario</label>
                                </div>

                                <div>
                                    <label class="text-[9px] font-black text-gray-500 uppercase tracking-widest ml-1 mb-2 block">Adjuntar Evidencia (Opcional)</label>
                                    <input type="file" wire:model="archivoResolucion" class="w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-black file:uppercase file:tracking-widest file:bg-white/10 file:text-white hover:file:bg-white/20 transition-all cursor-pointer">
                                </div>
                                <button type="submit" class="w-full py-4 bg-red-600 hover:bg-red-500 text-white rounded-xl text-[11px] font-black uppercase tracking-[.3em] shadow-[0_0_15px_rgba(220,38,38,0.3)] transition-all">
                                    CANCELAR TICKET AHORA
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </template>

            {{-- Users Tab --}}
            <template x-if="activeTab === 'users'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-12">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                         <h3 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">Gestión de Usuarios</h3>
                         <div class="flex items-center gap-4 w-full md:w-auto">
                             <div class="relative w-full md:w-80">
                                 <input type="text" wire:model.live="searchUser" placeholder="Buscar usuario..." class="w-full bg-white/5 border border-white/10 rounded-2xl px-4 py-3.5 pl-11 text-xs text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600 uppercase tracking-wider font-bold">
                                 <svg class="w-5 h-5 text-gray-500 absolute left-4 top-1/2 -translate-y-1/2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /></svg>
                             </div>
                             <button wire:click="openUserModal()" class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[.25em] shadow-2xl transition-all shrink-0">
                                 + Registrar Usuario
                             </button>
                         </div>
                    </div>
                    <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[3rem] overflow-x-auto shadow-3xl">
                         <table class="w-full text-left border-collapse min-w-[800px]">
                             <thead>
                                 <tr class="bg-white/5 border-b border-white/5">
                                     <th class="px-10 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Nombre</th>
                                     <th class="px-10 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Email</th>
                                     @if(auth()->user()->role === 'admin')
                                     <th class="px-10 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Acciones</th>
                                     @endif
                                 </tr>
                             </thead>
                             <tbody class="divide-y divide-white/[0.03]">
                                 @foreach($users as $user)
                                 <tr class="group hover:bg-white/[0.03] transition-all">
                                     <td class="px-10 py-7 text-sm font-black text-white uppercase tracking-tight">{{ $user->name }}</td>
                                     <td class="px-10 py-7 text-xs font-bold text-gray-500">{{ $user->email }}</td>
                                     @if(auth()->user()->role === 'admin')
                                     <td class="px-10 py-7 text-right">
                                         <div class="flex items-center justify-end gap-6">
                                             <button wire:click="openUserModal({{ $user->id }})" class="text-blue-500 hover:text-white transition-colors bg-blue-500/10 p-2 rounded-xl">
                                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                             </button>
                                             <button @click="confirm('¿Eliminar?') && $wire.deleteUser({{ $user->id }})" class="text-red-500 hover:text-white transition-colors bg-red-500/10 p-2 rounded-xl">
                                                 <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" /></svg>
                                             </button>
                                         </div>
                                     </td>
                                     @endif
                                 </tr>
                                 @endforeach
                             </tbody>
                         </table>
                    </div>
                </div>
            </template>
        </div>
    </main>

    {{-- MODAL: ALTA DE HARDWARE --}}
    @if($showingAddEquipment)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-md bg-[#0a0a1a] rounded-[2rem] border border-blue-500/20 shadow-2xl overflow-hidden flex flex-col relative">

            {{-- Botón Cerrar --}}
            <button wire:click="$set('showingAddEquipment', false)" class="absolute top-5 right-5 w-8 h-8 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-xl text-gray-400 hover:text-white transition-all z-20 border border-white/5">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6"/></svg>
            </button>

            {{-- Header --}}
            <div class="px-7 py-6 border-b border-white/5 flex items-center gap-3">
                <div class="w-9 h-9 rounded-xl bg-blue-600/20 border border-blue-500/30 flex items-center justify-center shrink-0">
                    <svg class="w-5 h-5 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                </div>
                <div>
                    <h3 class="text-lg font-black text-white uppercase tracking-tighter">Alta de Hardware</h3>
                    <p class="text-[9px] text-blue-500 uppercase font-bold tracking-widest mt-0.5">Registro de activo</p>
                </div>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="createEquipment" class="p-7 space-y-5">
                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Nombre</label>
                    <input type="text" wire:model="equipmentName"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                           placeholder="Ej. Monitor HP 24">
                </div>

                <div class="grid grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Categoría</label>
                        <div class="relative">
                            <select wire:model="equipmentCategory" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs font-bold outline-none appearance-none focus:border-blue-500 transition-all">
                                <option value="Pantalla">Pantalla</option>
                                <option value="CPU">CPU</option>
                                <option value="Impresora">Impresora</option>
                                <option value="Laptop">Laptop</option>
                                <option value="Mouse">Mouse</option>
                                <option value="Teclado">Teclado</option>
                                <option value="UPS">UPS</option>
                                <option value="Otro">Otro</option>
                            </select>
                            <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>
                    <div class="space-y-2">
                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Modelo</label>
                        <input type="text" wire:model="equipmentModel"
                               class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                               placeholder="Ej. EliteDisplay E243">
                    </div>
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">S/N (Número de Serie)</label>
                    <input type="text" wire:model="equipmentBarcode"
                           class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-sm outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                           placeholder="Ej. SN-2024-0001">
                </div>

                <div class="space-y-2">
                    <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Ubicación Física</label>
                    <div class="relative">
                        <select wire:model="equipmentPhysLocation" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs font-bold outline-none appearance-none focus:border-blue-500 transition-all">
                            <option value="-- Bodega --">-- Bodega --</option>
                            @foreach($maquinas as $m)
                                <option value="{{ $m->id }}">{{ $m->nombre }}</option>
                            @endforeach
                        </select>
                        <svg class="w-4 h-4 absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>

                <div class="pt-2 flex gap-3">
                    <button type="button" wire:click="$set('showingAddEquipment', false)"
                            class="flex-1 py-3 border border-white/10 text-gray-500 hover:text-white hover:border-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all">
                        Cancelar
                    </button>
                    <button type="submit"
                            class="flex-1 py-3 bg-blue-600 hover:bg-blue-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest transition-all shadow-[0_0_15px_rgba(37,99,235,0.4)] flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                        Guardar
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif


    {{-- MODAL: USER MANAGEMENT (Edit/Create) - Compact size --}}
    @if($showingUserModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-md bg-[#0a0a1a] rounded-[2rem] border border-blue-500/20 shadow-4xl overflow-y-auto max-h-[92vh]">
            <div class="p-5 flex justify-between items-center border-b border-white/5 sticky top-0 bg-[#0a0a1a] z-10">
                <h3 class="text-lg font-black text-white uppercase tracking-tighter">{{ $selectedUserId ? 'EDITAR USUARIO' : 'NUEVO ACCESO' }}</h3>
                <button wire:click="$set('showingUserModal', false)" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12" /></svg>
                </button>
            </div>
            <form wire:submit.prevent="saveUser" class="p-5 space-y-4 pb-6">
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Nombre Completo</label>
                        <input type="text" wire:model="userName" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                            placeholder="">
                        @error('userName') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Teléfono / WhatsApp</label>
                        <input type="text" wire:model="userTelefono"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                            placeholder="Ej. 1234567890">
                        @error('userTelefono') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Código de Acceso</label>
                        <input type="text" wire:model="userCodigoAcceso" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                            placeholder="Ej. US-0001">
                        @error('userCodigoAcceso') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Rol</label>
                        <select wire:model="userRole" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none appearance-none">
                            <option value="user">Usuario</option>
                            <option value="agente">Agente TI</option>
                            <option value="gestor">Gestor de Stocks</option>
                            <option value="admin">Administrador</option>
                        </select>
                        @error('userRole') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Contraseña (Opcional)</label>
                    <input type="password" wire:model="userPassword"
                        placeholder="Dejar en blanco para usar código de acceso"
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600">
                    @error('userPassword') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                </div>
                <div class="pt-2">
                    <button type="submit" class="w-full py-3.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-xs font-black uppercase tracking-widest shadow-lg transition-all">
                        {{ $selectedUserId ? 'ACTUALIZAR' : 'REGISTRAR EN NEXUS' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif


    {{-- MODAL: NUEVO TICKET --}}
    @if($showingNewTicket)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-xl bg-[#0a0a1a] rounded-[2rem] border border-teal-500/20 shadow-2xl flex flex-col max-h-[92vh]">
            
            {{-- Header --}}
            <div class="p-6 flex justify-between items-center border-b border-white/5 shrink-0">
                <div class="flex items-center gap-3">
                    <div class="w-8 h-8 rounded-xl bg-teal-500/10 border border-teal-500/20 flex items-center justify-center">
                        <svg class="w-4 h-4 text-teal-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4"/></svg>
                    </div>
                    <h3 class="text-base font-black text-white uppercase tracking-tighter">Nuevo Ticket</h3>
                </div>
                <button wire:click="$set('showingNewTicket', false)" class="text-gray-500 hover:text-white transition-colors p-1">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Scrollable form body --}}
            <form wire:submit.prevent="createTicket" class="overflow-y-auto custom-scrollbar">
                <div class="p-6 space-y-5">

                    {{-- Tipo de Problema --}}
                    <div class="space-y-3">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Tipo de Problema</label>
                        <div class="grid grid-cols-2 gap-4">
                            <button type="button" wire:click="$set('ticketCategory', 'Software')"
                                :class="$wire.ticketCategory === 'Software' ? 'bg-blue-600/20 border-blue-500/50 text-blue-300' : 'bg-white/5 border-white/8 text-gray-400 hover:bg-white/8'"
                                class="flex flex-col items-center justify-center py-5 rounded-xl border transition-all gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4"/></svg>
                                <span class="font-black uppercase tracking-widest text-[10px]">Software</span>
                            </button>
                            <button type="button" wire:click="$set('ticketCategory', 'Hardware')"
                                :class="$wire.ticketCategory === 'Hardware' ? 'bg-purple-600/20 border-purple-500/50 text-purple-300' : 'bg-white/5 border-white/8 text-gray-400 hover:bg-white/8'"
                                class="flex flex-col items-center justify-center py-5 rounded-xl border transition-all gap-2">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                                <span class="font-black uppercase tracking-widest text-[10px]">Hardware</span>
                            </button>
                        </div>
                        @error('ticketCategory') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Subcategoría --}}
                    @if($ticketCategory)
                    <div class="space-y-3 animate-in fade-in slide-in-from-top-2 duration-200">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Detalle Específico</label>
                        <div class="grid grid-cols-2 gap-3">
                            @php
                                $options = $ticketCategory === 'Software'
                                    ? ['Problemas para iniciar sesión','Virus o malware','Aplicaciones que se congelan','Sistema lento','Otro (Software)']
                                    : ['Impresora no imprime','Teclado no responde','Mouse no funciona','Computadora no enciende','Cable desconectado','Otro (Hardware)'];
                            @endphp
                            @foreach($options as $option)
                                <button type="button" wire:click="$set('ticketSubcategory', '{{ $option }}')"
                                    :class="$wire.ticketSubcategory === '{{ $option }}' ? 'bg-teal-500/15 text-teal-300 border-teal-500/40 shadow-[0_0_15px_rgba(20,184,166,0.15)]' : 'bg-white/[0.03] text-gray-400 hover:bg-white/8 hover:text-white border-white/8'"
                                    class="px-4 py-3 rounded-lg border text-[10px] font-bold text-left transition-all leading-snug">
                                    {{ $option }}
                                </button>
                            @endforeach
                        </div>
                        @error('ticketSubcategory') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>
                    @endif

                    {{-- Título --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Título del Ticket</label>
                        <input type="text" wire:model="ticketTitle" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-teal-500/60 transition-all placeholder:text-gray-600"
                            placeholder="Describe brevemente el problema">
                        @error('ticketTitle') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Descripción --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Descripción del Problema</label>
                        <textarea wire:model="ticketDescription" required rows="3"
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-teal-500/60 transition-all resize-none placeholder:text-gray-600"
                            placeholder="Describe con detalle lo ocurrido..."></textarea>
                        @error('ticketDescription') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Adjuntar Archivos --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Adjuntar Archivos (PDF, Word, Imágenes)</label>
                        <div class="relative w-full h-14 border border-dashed border-white/10 hover:border-teal-500/40 rounded-xl bg-white/[0.03] flex items-center justify-center cursor-pointer transition-all group overflow-hidden">
                            <input type="file" multiple wire:model="ticketFiles" class="absolute inset-0 opacity-0 cursor-pointer z-10"/>
                            <div class="flex items-center gap-2 text-[10px] font-bold text-gray-500 group-hover:text-teal-400 transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1"/></svg>
                                <span>Seleccionar archivos...</span>
                            </div>
                        </div>
                        {{-- Listado de archivos --}}
                        @if(!empty($ticketFiles))
                            <div class="space-y-1.5 max-h-24 overflow-y-auto custom-scrollbar">
                                @foreach($ticketFiles as $index => $file)
                                    @if($file)
                                    @php
                                        $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                                        $iconClass = match($ext) {
                                            'pdf' => 'fa-solid fa-file-pdf text-red-400',
                                            'doc', 'docx' => 'fa-solid fa-file-word text-blue-400',
                                            'xls', 'xlsx' => 'fa-solid fa-file-excel text-green-400',
                                            'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp' => 'fa-solid fa-file-image text-purple-400',
                                            default => 'fa-solid fa-file text-gray-400',
                                        };
                                    @endphp
                                    <div class="flex items-center justify-between bg-white/[0.03] border border-white/5 rounded-lg px-3 py-2.5 text-xs text-gray-300">
                                        <div class="flex items-center gap-2 truncate">
                                            <i class="{{ $iconClass }} text-[14px] shrink-0 w-3.5 h-3.5 flex items-center justify-center"></i>
                                            <span class="truncate">{{ $file->getClientOriginalName() }}</span>
                                            <span class="text-[8px] font-bold uppercase bg-white/10 px-1.5 py-0.5 rounded ml-1 text-teal-300">{{ $ext }}</span>
                                        </div>
                                        <button type="button" wire:click="removeTicketFile({{ $index }})" class="text-red-400 hover:text-red-300 ml-2 shrink-0">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                                        </button>
                                    </div>
                                    @endif
                                @endforeach
                            </div>
                        @endif
                    </div>

                    {{-- Planta --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Planta</label>
                        <select wire:model="ticketPlanta" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none appearance-none focus:border-teal-500/60 transition-all">
                            <option value="">Selecciona Planta</option>
                            <option value="1">Planta 1</option>
                            <option value="2">Planta 2</option>
                        </select>
                        @error('ticketPlanta') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>

                    {{-- Ubicación / Máquina --}}
                    <div class="space-y-2">
                        <label class="text-[10px] font-black text-teal-400 uppercase tracking-widest">Ubicación / Máquina</label>
                        <select wire:model="ticketLocation" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none appearance-none focus:border-teal-500/60 transition-all">
                            <option value="">(Ninguna)</option>
                            @foreach($maquinas as $maquina)
                                <option value="{{ $maquina->id }}">{{ $maquina->nombre }}</option>
                            @endforeach
                        </select>
                    </div>

                </div>

                {{-- Footer con botones --}}
                <div class="p-6 border-t border-white/5 flex gap-4 shrink-0">
                    <button type="button" 
                        @if($ticketCategory)
                            wire:click="$set('ticketCategory', null)"
                        @else
                            wire:click="$set('showingNewTicket', false)"
                        @endif
                        class="flex-1 py-3.5 border border-white/10 text-gray-400 hover:text-white hover:border-white/20 rounded-xl text-[10px] font-black uppercase tracking-widest transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 19l-7-7 7-7"/></svg>
                        Regresar
                    </button>
                    <button type="submit"
                        class="flex-[2] py-3.5 bg-teal-600 hover:bg-teal-500 text-white rounded-xl text-[10px] font-black uppercase tracking-widest shadow-lg transition-all flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        Generar Ticket
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif


    {{-- MODAL: GESTIONAR TICKET (ADMIN / AGENTE) --}}
    @if($showingAdminTicket)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-lg bg-[#0a0a1a] rounded-[2rem] border border-blue-500/20 shadow-2xl overflow-hidden max-h-[90vh] flex flex-col relative">
            
            {{-- Botón Cerrar (Absoluto) --}}
            <button wire:click="$set('showingAdminTicket', false)" class="absolute top-6 right-6 w-8 h-8 flex items-center justify-center bg-white/5 hover:bg-white/10 rounded-xl text-gray-400 hover:text-white transition-all z-20 border border-white/5 hover:border-white/20">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M6 18L18 6" /></svg>
            </button>

            <div class="px-8 py-6 border-b border-white/5 sticky top-0 bg-[#0a0a1a]/95 backdrop-blur-sm z-10 shrink-0">
                <h3 class="text-xl font-black text-white uppercase tracking-tighter">Gestionar Ticket #{{ $aTicketId }}</h3>
                <p class="text-[9px] text-blue-500 uppercase font-bold tracking-widest mt-1">Configuración y Asignación</p>
            </div>
            
            <div class="overflow-y-auto custom-scrollbar flex-1">
                <form wire:submit.prevent="updateAdminTicket" class="p-8 space-y-6">
                    
                    @if($adminTicketModel)
                    <div class="bg-white/5 border border-white/10 rounded-2xl p-5 space-y-3.5 text-xs">
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-bold uppercase tracking-wider">Quién lo hizo:</span>
                            <span class="text-white font-black uppercase tracking-tight">{{ $adminTicketModel->creador?->nombre_completo ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-bold uppercase tracking-wider">Sector:</span>
                            <span class="text-blue-400 font-black uppercase tracking-tight">{{ $adminTicketModel->sector?->nombre ?? 'N/A' }}</span>
                        </div>
                        <div class="flex items-center justify-between">
                            <span class="text-gray-400 font-bold uppercase tracking-wider">Fecha y Hora:</span>
                            <span class="text-gray-200 font-bold tracking-tight">{{ $adminTicketModel->created_at?->format('d/m/Y H:i') ?? 'N/A' }}</span>
                        </div>
                        <div class="border-t border-white/5 my-2"></div>
                        <div class="space-y-1.5">
                            <span class="text-gray-400 font-bold uppercase tracking-wider block">Incidencia:</span>
                            <p class="text-white font-black uppercase tracking-tight text-xs leading-relaxed">{{ $adminTicketModel->titulo }}</p>
                        </div>
                        <div class="space-y-1.5 mt-2">
                            <span class="text-gray-400 font-bold uppercase tracking-wider block">Descripción del Usuario:</span>
                            <p class="text-gray-300 font-medium tracking-tight whitespace-pre-wrap leading-relaxed bg-[#0b0b1a] p-3 rounded-xl border border-white/5">{{ $adminTicketModel->descripcion }}</p>
                        </div>
                        @if($adminTicketModel->archivosAdjuntos && $adminTicketModel->archivosAdjuntos->count() > 0)
                        <div class="space-y-1.5 mt-2">
                            <span class="text-gray-400 font-bold uppercase tracking-wider block">Archivos del Usuario:</span>
                            <div class="flex flex-wrap gap-2">
                                @foreach($adminTicketModel->archivosAdjuntos as $adj)
                                    <div class="inline-flex items-center gap-1 bg-blue-600/10 border border-blue-500/20 px-2.5 py-1 rounded-xl text-[9px] font-black uppercase text-blue-400">
                                        📎 {{ Str::limit($adj->nombre_archivo, 20) }}
                                        <button type="button" wire:click="viewAttachment({{ $adj->id }})" class="ml-2 hover:text-white px-1 py-0.5 rounded bg-teal-500/20 text-teal-300 transition-colors cursor-pointer">Ver</button>
                                        <button type="button" wire:click="downloadAttachment({{ $adj->id }})" class="ml-1 hover:text-white px-1 py-0.5 rounded bg-blue-500/20 text-blue-300 transition-colors cursor-pointer">Bajar</button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                        @endif
                    </div>
                    @endif

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Estado</label>
                            <div class="relative">
                                <select wire:model="aTicketStatus" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none appearance-none font-bold focus:border-blue-500 transition-all">
                                    @foreach($estadosLocales as $est)
                                        <option value="{{ $est->id }}">{{ $est->nombre }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>

                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Prioridad</label>
                            <div class="relative">
                                <select wire:model="aTicketPriority" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none appearance-none font-bold focus:border-blue-500 transition-all">
                                    @foreach($prioridades as $pri)
                                        <option value="{{ $pri->id }}">{{ $pri->nombre }}</option>
                                    @endforeach
                                </select>
                                <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Agente Asignado</label>
                        <div class="relative">
                            <select wire:model="aTicketAgent" class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none appearance-none font-bold focus:border-blue-500 transition-all">
                                <option value="">-- Sin Asignar --</option>
                                @foreach($agentes as $ag)
                                    <option value="{{ $ag->id }}">{{ $ag->name }}</option>
                                @endforeach
                            </select>
                            <svg class="w-4 h-4 absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Hora de Visita Agente TI</label>
                            <input type="text" wire:model="aTicketHoraVisita" placeholder="Ej. 10:00 am o 2:00 pm" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:border-blue-500 transition-all placeholder:text-gray-600">
                        </div>
                        <div class="space-y-3">
                            <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Tiempo de Resolución (Minutos)</label>
                            <input type="number" wire:model="aTicketTiempoResolucion" placeholder="Ej. 15, 30, 60" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:border-blue-500 transition-all placeholder:text-gray-600">
                        </div>
                    </div>

                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Adjuntar Documentos/Evidencia Resolución</label>
                        <div class="relative w-full h-14 border border-dashed border-white/10 hover:border-blue-500/50 rounded-xl bg-white/5 flex items-center justify-center cursor-pointer transition-all group overflow-hidden">
                            <input type="file" multiple wire:model="aTicketFiles" class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                            <div class="flex items-center gap-1.5 text-[10px] font-black text-gray-500 group-hover:text-blue-400 transition-colors uppercase tracking-wider">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" /></svg>
                                <span>Subir Archivos...</span>
                            </div>
                        </div>
                        {{-- List of files selected for upload --}}
                        @if(!empty($aTicketFiles))
                            <div class="flex flex-wrap gap-2 mt-2">
                                @foreach($aTicketFiles as $index => $file)
                                    @php
                                        $ext = strtolower(pathinfo($file->getClientOriginalName(), PATHINFO_EXTENSION));
                                        $iconClass = match($ext) {
                                            'pdf' => 'fa-solid fa-file-pdf text-red-400',
                                            'doc', 'docx' => 'fa-solid fa-file-word text-blue-400',
                                            'xls', 'xlsx' => 'fa-solid fa-file-excel text-green-400',
                                            'png', 'jpg', 'jpeg', 'gif', 'svg', 'webp' => 'fa-solid fa-file-image text-purple-400',
                                            default => 'fa-solid fa-file text-blue-400',
                                        };
                                    @endphp
                                    <div class="inline-flex items-center gap-1.5 bg-blue-600/10 border border-blue-500/20 px-3 py-1.5 rounded-xl text-[9px] font-black uppercase text-blue-400">
                                        <i class="{{ $iconClass }} text-[12px]"></i>
                                        <span>{{ Str::limit($file->getClientOriginalName(), 15) }}</span>
                                        <span class="text-[7px] font-black uppercase bg-white/10 px-1 py-0.5 rounded text-blue-300">{{ $ext }}</span>
                                        <button type="button" wire:click="removeAdminTicketFile({{ $index }})" class="text-rose-400 hover:text-rose-300 font-bold ml-1 text-[11px] focus:outline-none">✕</button>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>


                    

                    <div class="pt-3 border-t border-white/5">
                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-black uppercase tracking-[.3em] shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all flex items-center justify-center gap-2 border border-blue-500/50">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                            GUARDAR CAMBIOS GENERALES
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <style>
         @keyframes scale-in { 0% { transform: scale(0.5); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
         @keyframes flyAwayAnimation {
             0% {
                 transform: translate(-100px, 100px) rotate(45deg) scale(0.6);
                 opacity: 0;
             }
             15% {
                 opacity: 1;
             }
             50% {
                 transform: translate(0px, 0px) rotate(45deg) scale(1);
             }
             85% {
                 opacity: 1;
             }
             100% {
                 transform: translate(150px, -150px) rotate(45deg) scale(0.4);
                 opacity: 0;
             }
         }
         .animate-fly-away {
             animation: flyAwayAnimation 2.2s ease-in-out infinite;
         }
     </style>

     {{-- SENDING SCREEN OVERLAY --}}
     <div wire:ignore x-show="sendingTicket" class="fixed inset-0 z-[2000] flex flex-col items-center justify-center bg-slate-950/90 backdrop-blur-md" x-transition.opacity style="display: none;">
         <div class="text-center space-y-8">
             <div class="relative w-40 h-40 mx-auto flex items-center justify-center">
                 {{-- Paper plane icon flying --}}
                 <div class="animate-fly-away absolute">
                     <svg class="w-20 h-20 text-blue-500 drop-shadow-[0_0_20px_rgba(59,130,246,0.6)]" fill="currentColor" viewBox="0 0 24 24">
                         <path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/>
                     </svg>
                 </div>
                 {{-- Decorative circles / radar lines --}}
                 <div class="absolute inset-0 rounded-full border border-white/5 animate-ping"></div>
                 <div class="absolute inset-4 rounded-full border border-white/10"></div>
             </div>
             <div class="space-y-2 animate-pulse">
                 <h4 class="text-lg font-black text-white uppercase tracking-widest">Enviando Incidencia</h4>
                 <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Despegando hacia el Service Desk...</p>
             </div>
         </div>
     </div>

     {{-- SUCCESS ANIMATION OVERLAY --}}
     <style>
         .anim-overlay {
             position: fixed; inset: 0; z-index: 2000;
             background: rgba(0,0,0,.7); backdrop-filter: blur(6px);
             display: flex; justify-content: center; align-items: center;
         }
         .anim-container {
             width: 420px; height: 340px;
             background: linear-gradient(135deg, #1e3a8a, #0f172a); border: 1px solid rgba(255,255,255,0.1);
             border-radius: 25px; position: relative; overflow: hidden; text-align: center; color: white;
             box-shadow: 0 20px 60px rgba(0,0,0,.6);
         }
         .anim-path { position: absolute; left: 40px; top: 40px; }
         .anim-plane {
             position: absolute; left: 25px; top: 145px; font-size: 55px;
             offset-path: path("M40 130 Q150 10 280 70"); offset-distance: 0%; transform: rotate(-15deg);
         }
         .anim-fly { animation: flyAnim 2s ease-in-out forwards; }
         @keyframes flyAnim {
             0% { offset-distance: 0%; opacity: 1; }
             85% { opacity: 1; }
             100% { offset-distance: 100%; opacity: 0; }
         }
         .anim-check {
             position: absolute; left: 50%; top: 95px; transform: translateX(-50%) scale(0);
             width: 90px; height: 90px; border-radius: 50%; background: #10b981;
             font-size: 55px; display: flex; justify-content: center; align-items: center;
             box-shadow: 0 10px 30px rgba(16,185,129,0.4);
         }
         .anim-showCheck { animation: checkAnim .6s cubic-bezier(.17,.89,.32,1.49) forwards; }
         @keyframes checkAnim {
             from { transform: translateX(-50%) scale(0); }
             to { transform: translateX(-50%) scale(1); }
         }
         .anim-text { position: absolute; width: 100%; opacity: 0; }
         .anim-title { bottom: 80px; font-size: 24px; font-weight: 900; letter-spacing: -0.02em; }
         .anim-subtitle { bottom: 45px; font-size: 13px; color: #94a3b8; font-weight: 500; }
         .anim-showText { animation: textAnim .5s forwards; }
         @keyframes textAnim {
             from { opacity: 0; transform: translateY(15px); }
             to { opacity: 1; transform: translateY(0); }
         }
     </style>

     <div wire:ignore 
          x-show="showSuccessScreen" 
          x-transition.opacity 
          class="anim-overlay" 
          style="display: none;"
          x-data="{ step: 0 }"
          @ticket-created.window="
              step = 1;
              setTimeout(() => step = 2, 2000);
              setTimeout(() => {
                  showSuccessScreen = false;
                  window.dispatchEvent(new CustomEvent('clear-ticket-form'));
                  if ('{{ auth()->user()->role }}' === 'user') { $wire.set('activeTab', 'mis_tickets'); }
                  setTimeout(() => step = 0, 300);
              }, 4500);
          ">
         <div class="anim-container">
             <svg class="anim-path" width="320" height="180">
                 <path d="M40 130 Q150 10 280 70" fill="none" stroke="#ffffff" stroke-width="3" stroke-dasharray="8 8" opacity="0.3"/>
             </svg>
             <div class="anim-plane" :class="step >= 1 ? 'anim-fly' : ''">🛩️</div>
             
             <div class="anim-check" :class="step >= 2 ? 'anim-showCheck' : ''">✔</div>
             
             <h2 class="anim-text anim-title" :class="step >= 2 ? 'anim-showText' : ''">¡Ticket enviado con éxito!</h2>
             <p class="anim-text anim-subtitle" :class="step >= 2 ? 'anim-showText' : ''">Tu solicitud fue enviada correctamente.</p>
         </div>
     </div>

    {{-- MODAL: FINALIZAR TICKET --}}
    @if($mostrarModalFinalizar)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-data="{ 
                fecha: new Date().toISOString().split('T')[0], 
                hora: new Date().toTimeString().slice(0,5) 
             }">
            <div class="bg-gray-900 border border-green-500/50 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col" @click.stop>
                <div class="bg-green-600 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-white font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                        <i class="fa-solid fa-check-double"></i> Finalizar Ticket
                    </h2>
                    <button wire:click="cerrarModalResolucion" class="text-white/70 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 flex-1 overflow-y-auto space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Causa de Solución</label>
                        <select wire:model="causaSolucionId" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500">
                            <option value="">-- Seleccionar --</option>
                            @foreach($causasSolucion as $causa)
                                <option value="{{ $causa->id }}">{{ $causa->nombre }}</option>
                            @endforeach
                        </select>
                        @error('causaSolucionId') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Fecha</label>
                            <input type="date" x-model="fecha" disabled class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-gray-400 text-sm cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Hora</label>
                            <input type="time" x-model="hora" disabled class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-gray-400 text-sm cursor-not-allowed">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Solución</label>
                        <textarea wire:model="detallesResolucion" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:ring-2 focus:ring-green-500 focus:border-green-500 resize-none" placeholder="Describe cómo se solucionó..."></textarea>
                        @error('detallesResolucion') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Adjunto</label>
                        <input type="file" wire:model="archivoResolucion" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-green-500/10 file:text-green-500 hover:file:bg-green-500/20">
                    </div>
                </div>
                <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-700 flex justify-end gap-3">
                    <button wire:click="cerrarModalResolucion" class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-gray-400 hover:text-white transition-colors bg-gray-800 hover:bg-gray-700 border border-gray-700">Regresar</button>
                    <button wire:click="guardarFinalizacion" class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-green-600 hover:bg-green-500 shadow-lg shadow-green-500/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-check" wire:loading.remove wire:target="guardarFinalizacion, archivoResolucion"></i>
                        <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="guardarFinalizacion, archivoResolucion"></i>
                        Finalizar Ticket
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- MODAL: CANCELAR TICKET --}}
    @if($mostrarModalCancelar)
        <div class="fixed inset-0 z-[60] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm"
             x-data="{ 
                fecha: new Date().toISOString().split('T')[0], 
                hora: new Date().toTimeString().slice(0,5) 
             }">
            <div class="bg-gray-900 border border-red-500/50 rounded-2xl w-full max-w-md shadow-2xl overflow-hidden flex flex-col" @click.stop>
                <div class="bg-red-600 px-6 py-4 flex items-center justify-between">
                    <h2 class="text-white font-bold uppercase tracking-wider text-sm flex items-center gap-2">
                        <i class="fa-solid fa-ban"></i> Cancelar Ticket
                    </h2>
                    <button wire:click="cerrarModalResolucion" class="text-white/70 hover:text-white transition-colors">
                        <i class="fa-solid fa-xmark text-lg"></i>
                    </button>
                </div>
                <div class="p-6 flex-1 overflow-y-auto space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Motivo de Cancelación</label>
                        <select wire:model="motivoCancelacionId" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-2.5 text-white text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500">
                            <option value="">-- Seleccionar --</option>
                            @foreach($motivosCancelacion as $motivo)
                                <option value="{{ $motivo->id }}">{{ $motivo->nombre }}</option>
                            @endforeach
                        </select>
                        @error('motivoCancelacionId') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Fecha</label>
                            <input type="date" x-model="fecha" disabled class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-gray-400 text-sm cursor-not-allowed">
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Hora</label>
                            <input type="time" x-model="hora" disabled class="w-full bg-gray-800/50 border border-gray-700/50 rounded-lg px-4 py-2 text-gray-400 text-sm cursor-not-allowed">
                        </div>
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Detalles / Motivo</label>
                        <textarea wire:model="detallesResolucion" rows="3" class="w-full bg-gray-800 border border-gray-700 rounded-lg px-4 py-3 text-white text-sm focus:ring-2 focus:ring-red-500 focus:border-red-500 resize-none" placeholder="Explica el motivo de cancelación..."></textarea>
                        @error('detallesResolucion') <span class="text-red-400 text-xs mt-1">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase tracking-wider mb-1">Adjunto</label>
                        <input type="file" wire:model="archivoResolucion" class="w-full text-sm text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-xs file:font-bold file:bg-red-500/10 file:text-red-500 hover:file:bg-red-500/20">
                    </div>
                    <div class="flex items-center gap-2 mt-2 bg-gray-800 p-3 rounded-lg border border-gray-700">
                        <input type="checkbox" wire:model="visibleAlUsuario" id="visible_user" class="w-4 h-4 text-red-600 bg-gray-900 border-gray-600 rounded focus:ring-red-500 focus:ring-2">
                        <label for="visible_user" class="text-xs text-gray-300 font-bold uppercase tracking-wider">Mostrar cancelación al usuario</label>
                    </div>
                </div>
                <div class="bg-gray-800/50 px-6 py-4 border-t border-gray-700 flex justify-end gap-3">
                    <button wire:click="cerrarModalResolucion" class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-gray-400 hover:text-white transition-colors bg-gray-800 hover:bg-gray-700 border border-gray-700">Regresar</button>
                    <button wire:click="guardarCancelacion" class="px-5 py-2.5 rounded-lg text-xs font-bold uppercase tracking-wider text-white bg-red-600 hover:bg-red-500 shadow-lg shadow-red-500/30 transition-all flex items-center gap-2">
                        <i class="fa-solid fa-ban" wire:loading.remove wire:target="guardarCancelacion, archivoResolucion"></i>
                        <i class="fa-solid fa-spinner fa-spin" wire:loading wire:target="guardarCancelacion, archivoResolucion"></i>
                        Cancelar Ticket
                    </button>
                </div>
            </div>
        </div>
    @endif

    {{-- Script de Notificaciones y Partículas --}}
    <script>
        function playChime() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const playNote = (frequency, startTime, duration) => {
                    const osc = audioCtx.createOscillator();
                    const gain = audioCtx.createGain();
                    
                    osc.type = 'sine';
                    osc.frequency.setValueAtTime(frequency, startTime);
                    
                    gain.gain.setValueAtTime(0, startTime);
                    gain.gain.linearRampToValueAtTime(0.25, startTime + 0.03); 
                    gain.gain.exponentialRampToValueAtTime(0.001, startTime + duration); 
                    
                    osc.connect(gain);
                    gain.connect(audioCtx.destination);
                    
                    osc.start(startTime);
                    osc.stop(startTime + duration);
                };
                
                // Double chime sound
                playNote(587.33, audioCtx.currentTime, 0.35); // D5
                playNote(880.00, audioCtx.currentTime + 0.1, 0.5); // A5
            } catch (e) {
                console.error("Autoplay/Web Audio blocked or not supported:", e);
            }
        }

        document.addEventListener('DOMContentLoaded', () => {
            const container = document.getElementById('particleContainer');
            if(!container) return;

            function createParticle() {
                const div = document.createElement('div');
                div.classList.add('transition-particle');
               
                const x = Math.random() * 100;
                const y = Math.random() * 100;
               
                const size = Math.random() * 2 + 1;
                const duration = Math.random() * 5 + 4;
                const delay = Math.random() * 5;

                div.style.left = `${x}vw`;
                div.style.top = `${y}vh`;
                div.style.width = `${size}px`;
                div.style.height = `${size}px`;
                div.style.animation = `floatParticle ${duration}s ease-in-out infinite ${delay}s`;
               
                container.appendChild(div);
            }

            for (let i = 0; i < 100; i++) {
                createParticle();
            }
        });

        document.addEventListener('livewire:initialized', () => {
            // Re-run initStatsCharts on every successful Livewire request/render
            Livewire.hook('request', ({ respond, succeed }) => {
                succeed(({ snapshot, effect }) => {
                    setTimeout(() => {
                        if (typeof window.initStatsCharts === 'function') {
                            window.initStatsCharts();
                        }
                    }, 50);
                });
            });
        });
    </script>





<script>
    window.renderSupportHubCharts = function(data) {
        if (typeof Chart === 'undefined') {
            setTimeout(() => window.renderSupportHubCharts(data), 50);
            return;
        }

        Chart.defaults.color = '#94a3b8';
        Chart.defaults.font.family = 'Inter, sans-serif';
        if (Chart.defaults.plugins.legend) {
            Chart.defaults.plugins.legend.labels.color = '#e2e8f0';
        }

        const destroyChart = (id) => {
            const el = document.getElementById(id);
            if (el && el.__chartInstance) {
                el.__chartInstance.destroy();
            }
        };

        // 1. CHART TENDENCIA (Línea / Barra Mixta)
        destroyChart('trendChart');
        const ctxTrend = document.getElementById('trendChart');
        if (ctxTrend) {
            ctxTrend.__chartInstance = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: Object.values(data.trendMonths),
                    datasets: [
                        {
                            label: 'Creados',
                            data: Object.values(data.trendData),
                            type: 'line',
                            borderColor: '#818cf8', // Indigo/purple
                            backgroundColor: 'rgba(129, 140, 248, 0.2)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.4,
                            pointBackgroundColor: '#818cf8',
                            pointHoverRadius: 7,
                            order: 3
                        },
                        {
                            label: 'Cerrados',
                            data: Object.values(data.trendClosedData || data.trendData),
                            type: 'line',
                            borderColor: '#10b981', // Emerald green
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [5, 5],
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#10b981',
                            pointHoverRadius: 7,
                            order: 2
                        },
                        {
                            label: 'Cancelados',
                            data: Object.values(data.trendCanceledData || []),
                            type: 'line',
                            borderColor: '#ef4444', // Red
                            backgroundColor: 'transparent',
                            borderWidth: 2,
                            borderDash: [3, 3],
                            fill: false,
                            tension: 0.4,
                            pointBackgroundColor: '#ef4444',
                            pointHoverRadius: 7,
                            order: 1
                        }
                    ]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 15, font: { size: 11 } } },
                        tooltip: { backgroundColor: '#1e293b', titleColor: '#fff', bodyColor: '#cbd5e1', cornerRadius: 8 }
                    },
                    scales: {
                        y: { grid: { color: 'rgba(51, 65, 85, 0.3)' }, ticks: { stepSize: 1, color: '#94a3b8' } },
                        x: { grid: { display: false }, ticks: { color: '#94a3b8' } }
                    }
                }
            });
        }

        // 2. CHART PLANTAS (Dona)
        destroyChart('plantChart');
        const ctxPlant = document.getElementById('plantChart');
        if (ctxPlant) {
            ctxPlant.__chartInstance = new Chart(ctxPlant, {
                type: 'doughnut',
                data: {
                    labels: ['Planta 1', 'Planta 2'],
                    datasets: [{
                        data: [data.plantaCounts['Planta 1'] || 0, data.plantaCounts['Planta 2'] || 0],
                        backgroundColor: ['#6366f1', '#f59e0b'],
                        borderWidth: 3,
                        borderColor: '#0f172a'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    cutout: '65%',
                    plugins: {
                        legend: { position: 'bottom', labels: { boxWidth: 12, padding: 15, font: { size: 10 } } },
                        tooltip: { backgroundColor: '#1e293b' }
                    }
                }
            });
        }

        // 3. CHART ÁREAS (Barras Horizontales)
        destroyChart('areaChart');
        const ctxArea = document.getElementById('areaChart');
        if (ctxArea) {
            const labels = Object.keys(data.categoryData || {});
            const counts = Object.values(data.categoryData || {});
            
            ctxArea.__chartInstance = new Chart(ctxArea, {
                type: 'bar',
                data: {
                    labels: labels.length ? labels : ['Sin datos'],
                    datasets: [{
                        label: 'Número de Solicitudes',
                        data: counts.length ? counts : [0],
                        backgroundColor: 'rgba(168, 85, 247, 0.65)',
                        borderColor: '#a855f7',
                        borderWidth: 1.5,
                        borderRadius: 6,
                        barThickness: 18
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b' } },
                    scales: {
                        x: { grid: { color: 'rgba(51, 65, 85, 0.3)' }, ticks: { precision: 0, color: '#94a3b8' } },
                        y: { grid: { display: false }, ticks: { color: '#94a3b8', font: { size: 10 } } }
                    }
                }
            });
        }

        // 4. CHART ESTADOS (Polar)
        destroyChart('stateChart');
        const ctxState = document.getElementById('stateChart');
        if (ctxState) {
            ctxState.__chartInstance = new Chart(ctxState, {
                type: 'polarArea',
                data: {
                    labels: ['Abiertos', 'En Proceso', 'Resueltos', 'Cancelados'],
                    datasets: [{
                        data: [data.statusCounts[1] || 0, data.statusCounts[2] || 0, (data.statusCounts[3] || 0) + (data.statusCounts[4] || 0), data.statusCounts[5] || 0],
                        backgroundColor: ['rgba(59, 130, 246, 0.55)', 'rgba(245, 158, 11, 0.55)', 'rgba(16, 185, 129, 0.55)', 'rgba(239, 68, 68, 0.55)'],
                        borderColor: '#0f172a',
                        borderWidth: 2
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: { r: { grid: { color: 'rgba(51, 65, 85, 0.3)' }, ticks: { backdropColor: 'transparent', color: '#94a3b8' } } },
                    plugins: { legend: { display: false }, tooltip: { backgroundColor: '#1e293b' } }
                }
            });
        }
    };

    // --- LÓGICA DE WIDGETS PANEL DE INICIO ---
    window.initInicioWidgets = function() {
        if (!document.getElementById('clock-hours')) return; // Solo ejecutar si los elementos existen

        // Reloj
        function updateClock() {
            const now = new Date();
            let hours = now.getHours();
            const minutes = String(now.getMinutes()).padStart(2, '0');
            const seconds = String(now.getSeconds()).padStart(2, '0');
            const ampm = hours >= 12 ? 'PM' : 'AM';

            hours = hours % 12;
            hours = hours ? hours : 12; 
            const hoursStr = String(hours).padStart(2, '0');

            const clockHours = document.getElementById('clock-hours');
            if(clockHours) {
                clockHours.innerText = hoursStr;
                document.getElementById('clock-minutes').innerText = minutes;
                document.getElementById('clock-ampm').innerText = ampm;
                document.getElementById('seconds-counter').innerText = `${seconds}s`;

                let currentShift = "Turno Mixto (C)";
                const realHour = now.getHours();
                if (realHour >= 6 && realHour < 14) currentShift = "Matutino (Turno A)";
                else if (realHour >= 14 && realHour < 22) currentShift = "Vespertino (Turno B)";
                else currentShift = "Nocturno (Turno C)";
                
                document.getElementById('current-shift').innerText = currentShift;
            }
        }
        
        // Clima
        function updateWeather() {
            const now = new Date();
            const hour = now.getHours();
            let temp = 24, desc = "Parcialmente Nublado", iconClass = "fa-solid fa-cloud-sun text-gray-400";

            if (hour >= 6 && hour < 12) {
                temp = 19; desc = "Despejado y Fresco"; iconClass = "fa-solid fa-sun text-amber-400 drop-shadow-[0_0_10px_rgba(251,191,36,0.8)]";
            } else if (hour >= 12 && hour < 18) {
                temp = 27; desc = "Cálido y Despejado"; iconClass = "fa-solid fa-sun text-amber-500 drop-shadow-[0_0_15px_rgba(245,158,11,0.8)] animate-spin";
            } else if (hour >= 18 && hour < 22) {
                temp = 21; desc = "Templado / Nublado"; iconClass = "fa-solid fa-cloud-sun text-gray-300";
            } else {
                temp = 15; desc = "Fresco y Despejado"; iconClass = "fa-solid fa-moon text-indigo-300 drop-shadow-[0_0_10px_rgba(165,180,252,0.8)]";
            }

            const wTemp = document.getElementById('weather-temp');
            if(wTemp) {
                wTemp.innerText = `${temp}°C`;
                document.getElementById('weather-desc').innerText = desc;
                const iconElement = document.getElementById('weather-icon');
                iconElement.className = `${iconClass} text-4xl`;
                iconElement.style.animationDuration = (hour >= 12 && hour < 18) ? "25s" : "3s";
            }
        }

        // Calendario
        let currentMonth = new Date().getMonth();
        let currentYear = new Date().getFullYear();
        const monthNames = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"];

        function renderCalendar() {
            const daysContainer = document.getElementById('calendar-days');
            if(!daysContainer) return;
            
            document.getElementById('cal-month').innerText = monthNames[currentMonth];
            document.getElementById('cal-year').innerText = currentYear;

            daysContainer.innerHTML = '';
            const firstDayIndex = new Date(currentYear, currentMonth, 1).getDay();
            const totalDays = new Date(currentYear, currentMonth + 1, 0).getDate();
            const prevTotalDays = new Date(currentYear, currentMonth, 0).getDate();

            for (let i = firstDayIndex; i > 0; i--) {
                const dayDiv = document.createElement('div');
                dayDiv.className = "text-gray-600 p-1.5 select-none opacity-40";
                dayDiv.innerText = prevTotalDays - i + 1;
                daysContainer.appendChild(dayDiv);
            }

            const today = new Date();
            for (let day = 1; day <= totalDays; day++) {
                const dayDiv = document.createElement('div');
                const isToday = (day === today.getDate() && currentMonth === today.getMonth() && currentYear === today.getFullYear());
                
                if (isToday) {
                    dayDiv.className = "p-1.5 bg-blue-600 text-white rounded-lg font-black shadow-[0_0_15px_rgba(37,99,235,0.6)] cursor-default select-none ring-2 ring-blue-400";
                } else {
                    dayDiv.className = "p-1.5 text-gray-300 hover:bg-white/10 hover:text-white rounded-lg cursor-default select-none transition-colors";
                }
                
                dayDiv.innerText = day;
                daysContainer.appendChild(dayDiv);
            }

            const totalSlots = firstDayIndex + totalDays;
            const remainingSlots = 42 - totalSlots;
            for (let i = 1; i <= remainingSlots; i++) {
                const dayDiv = document.createElement('div');
                dayDiv.className = "text-gray-600 p-1.5 select-none opacity-40";
                dayDiv.innerText = i;
                daysContainer.appendChild(dayDiv);
            }
        }

        window.prevMonth = function() {
            currentMonth--;
            if (currentMonth < 0) { currentMonth = 11; currentYear--; }
            renderCalendar();
        };

        window.nextMonth = function() {
            currentMonth++;
            if (currentMonth > 11) { currentMonth = 0; currentYear++; }
            renderCalendar();
        };

        // Limpiar intervalos previos para evitar múltiples ejecuciones y parpadeos
        if (window.inicioClockInterval) clearInterval(window.inicioClockInterval);
        if (window.inicioWeatherInterval) clearInterval(window.inicioWeatherInterval);

        // Arrancar
        updateClock();
        window.inicioClockInterval = setInterval(updateClock, 1000);
        
        updateWeather();
        window.inicioWeatherInterval = setInterval(updateWeather, 300000); 
        
        renderCalendar();
    };

    // Asegurar que se inicie al cargar la página si la pestaña es inicio
    document.addEventListener('livewire:initialized', () => {
        if(Livewire.first().activeTab === 'inicio') {
            setTimeout(window.initInicioWidgets, 100);
        }
        
        // Escuchar cambios de pestaña para inicializar el reloj cuando cambien a inicio
        Livewire.hook('commit', ({ component, commit, respond, succeed, fail }) => {
            succeed(({ snapshot, effect }) => {
                const activeTab = component.ephemeral.activeTab || snapshot.data.activeTab;
                if (activeTab === 'inicio') {
                    setTimeout(window.initInicioWidgets, 100);
                }
            })
        });
    });
</script>

{{-- GLOBAL CHAT BUBBLE WIDGET (DARK THEME) --}}
@if($chatWidgetTicketModel && $isChatWidgetMinimized)
    {{-- BUBBLE MODE (Facebook Chat Head style) --}}
    <div x-data="{
            x: 0,
            y: 0,
            dragging: false,
            isDragAction: false,
            startX: 0,
            startY: 0,
            startDrag(e) {
                if (e.target.closest('.no-drag')) return;
                this.dragging = true;
                this.isDragAction = false;
                this.startX = (e.type === 'touchstart' ? e.touches[0].clientX : e.clientX) - this.x;
                this.startY = (e.type === 'touchstart' ? e.touches[0].clientY : e.clientY) - this.y;
                
                const moveHandler = (e) => this.drag(e);
                const upHandler = (e) => {
                    this.dragging = false;
                    window.removeEventListener('mousemove', moveHandler);
                    window.removeEventListener('mouseup', upHandler);
                    window.removeEventListener('touchmove', moveHandler);
                    window.removeEventListener('touchend', upHandler);
                };
                window.addEventListener('mousemove', moveHandler);
                window.addEventListener('mouseup', upHandler);
                window.addEventListener('touchmove', moveHandler, { passive: false });
                window.addEventListener('touchend', upHandler);
            },
            drag(e) {
                if (!this.dragging) return;
                const currentX = e.type === 'touchmove' ? e.touches[0].clientX : e.clientX;
                const currentY = e.type === 'touchmove' ? e.touches[0].clientY : e.clientY;
                
                if (Math.abs(currentX - this.startX - this.x) > 5 || Math.abs(currentY - this.startY - this.y) > 5) {
                    this.isDragAction = true;
                }
                if (e.type === 'touchmove' && this.isDragAction) e.preventDefault();
                this.x = currentX - this.startX;
                this.y = currentY - this.startY;
            }
         }"
         :style="`transform: translate(${x}px, ${y}px);`"
         class="fixed bottom-4 right-4 z-[9999] transition-opacity duration-300 flex flex-col items-end"
         style="will-change: transform;">
         
        <div @mousedown="startDrag" @touchstart="startDrag" @click="if(!isDragAction) $wire.toggleMinimizeChat()"
             class="w-16 h-16 rounded-full bg-gradient-to-r from-blue-600 to-indigo-600 shadow-[0_10px_25px_rgba(37,99,235,0.5)] flex items-center justify-center cursor-move hover:scale-105 transition-transform border-4 border-[#0b0c16] relative group animate-in zoom-in">
            
            @if($notifCount > 0)
            <div class="absolute -top-1 -right-1 w-5 h-5 bg-rose-500 rounded-full flex items-center justify-center border-2 border-[#0b0c16] animate-pulse shadow-[0_0_10px_rgba(244,63,94,0.6)] z-30">
                <span class="text-white text-[10px] font-bold">{{ $notifCount > 99 ? '99+' : $notifCount }}</span>
            </div>
            @endif

            <div class="text-white font-black text-xl uppercase pointer-events-none">
                @if(auth()->user()->role === 'user')
                    {{ substr(optional($chatWidgetTicketModel->agente)->nombre ?? 'S', 0, 1) }}
                @else
                    {{ substr(optional($chatWidgetTicketModel->creador)->name ?? 'U', 0, 1) }}
                @endif
            </div>

            {{-- Expand Button Overlay (Visual) --}}
            <div class="absolute inset-0 w-full h-full rounded-full opacity-0 group-hover:opacity-100 flex items-center justify-center bg-black/40 transition-opacity pointer-events-none">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 0l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
            </div>
            
            {{-- Close Button --}}
            <button wire:click.stop="closeChatWidget" class="absolute -top-1 -right-1 w-6 h-6 bg-rose-500 rounded-full text-white flex items-center justify-center hover:bg-rose-600 shadow-md no-drag border-2 border-[#0b0c16] focus:outline-none z-10" title="Cerrar Chat">
                <svg class="w-3 h-3" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
    </div>
@endif

{{-- SINGLE RIGHT SIDEBAR (Chat List & Active Chat) --}}
<aside x-show="chatListSidebarOpen || ($wire.chatWidgetTicketId && !$wire.isChatWidgetMinimized)" x-cloak style="display: none;"
       class="w-80 sm:w-80 md:w-[400px] bg-[#050510] flex flex-col h-full border-l border-white/10 shadow-[-15px_0_40px_rgba(0,0,0,0.6)] z-40 relative shrink-0 transition-all duration-300 origin-right"
       x-transition:enter="transition ease-out duration-300"
       x-transition:enter-start="translate-x-full opacity-0"
       x-transition:enter-end="translate-x-0 opacity-100"
       x-transition:leave="transition ease-in duration-200"
       x-transition:leave-start="translate-x-0 opacity-100"
       x-transition:leave-end="translate-x-full opacity-0">
       
@if(auth()->user()->role === 'user' && !($chatWidgetTicketModel && !$isChatWidgetMinimized))
    {{-- AI CHATBOT MODE (User Only) --}}
    <div class="flex flex-col h-full w-full animate-in fade-in duration-200">
        {{-- Header --}}
        <div class="bg-white/5 backdrop-blur-xl px-4 py-3 flex items-center text-white shrink-0 shadow-[0_4px_30px_rgba(0,0,0,0.1)] z-10 border-b border-white/10 relative">
            <div class="flex items-center gap-3 ml-2">
                <div class="relative group cursor-default">
                    <div class="absolute -inset-0.5 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full blur opacity-40 group-hover:opacity-75 transition duration-300"></div>
                    <img src="https://ui-avatars.com/api/?name=B+C&background=1e1f38&color=4ade80&bold=true&font-size=0.4" class="relative w-10 h-10 rounded-full object-cover border-2 border-[#16172b] shadow-xl">
                    <div class="absolute bottom-0 right-0 w-3 h-3 bg-green-500 border-2 border-[#0f101f] rounded-full shadow-[0_0_8px_rgba(34,197,94,0.6)]"></div>
                </div>
                <div>
                    <h4 class="font-bold text-sm leading-tight text-white tracking-wide truncate max-w-[150px]">
                        Bryan C. (Soporte TI)
                    </h4>
                    <p class="text-[10px] text-green-400 font-bold tracking-widest mt-0.5">
                        En línea - Asistente IA
                    </p>
                </div>
            </div>
            <div class="flex items-center ml-auto gap-1">
                <button wire:click.stop="clearAiChat" @click="chatListSidebarOpen = false" class="hover:bg-white/10 p-1.5 rounded-lg transition-colors focus:outline-none text-gray-400 hover:text-white" title="Cerrar">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>
        </div>

        {{-- Chat Body --}}
        <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-transparent flex flex-col custom-scrollbar relative" id="ai-chat-body" x-data x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight; })" @scroll-bottom.window="$el.scrollTop = $el.scrollHeight">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>
            @foreach($aiChatMessages as $c)
            @php 
                $isMine = $c['sender'] === 'user'; 
                $time = \Carbon\Carbon::parse($c['created_at'])->format('H:i');
            @endphp
            <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} relative z-10 group">
                <div class="max-w-[75%] px-3 py-2 text-[12.5px] leading-snug shadow-sm break-words {{ $isMine ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm shadow-[0_4px_15px_rgba(37,99,235,0.2)]' : 'bg-white/5 text-gray-200 border border-white/10 rounded-2xl rounded-bl-sm shadow-[0_4px_15px_rgba(0,0,0,0.1)]' }} relative backdrop-blur-md">{!! nl2br(e(trim($c['message']))) !!}</div>
                <span class="text-[9px] text-gray-500/80 font-bold uppercase tracking-widest mt-1.5 {{ $isMine ? 'pr-2' : 'pl-2' }} opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $time }}</span>
            </div>
            @endforeach
            <div wire:loading wire:target="sendAiMessage" class="flex flex-col items-start relative z-10 animate-in fade-in slide-in-from-bottom-2">
                <div class="px-4 py-3.5 bg-white/5 border border-white/10 rounded-2xl rounded-bl-sm shadow-[0_4px_15px_rgba(0,0,0,0.1)] flex items-center gap-1.5 backdrop-blur-md">
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 0ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 150ms"></span>
                    <span class="w-1.5 h-1.5 rounded-full bg-gray-400 animate-bounce" style="animation-delay: 300ms"></span>
                </div>
            </div>
        </div>

        {{-- Chat Footer (Input) --}}
        <div class="p-3 bg-white/5 backdrop-blur-md border-t border-white/10 shrink-0 relative z-10" x-data="{ showEmoji: false }">
            <form wire:submit.prevent="sendAiMessage" class="relative flex items-center bg-black/20 border border-white/10 rounded-[1.25rem] focus-within:border-blue-500/50 focus-within:bg-black/40 transition-all shadow-inner pr-2">
                <button type="button" @click="showEmoji = !showEmoji" class="px-3 text-gray-400 hover:text-white transition-colors focus:outline-none">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                </button>
                <textarea wire:model="aiChatMessageInput" wire:keydown.enter.prevent="sendAiMessage" rows="1" placeholder="Escribe tu mensaje..." class="flex-1 bg-transparent py-3.5 pr-2 text-xs text-white outline-none resize-none custom-scrollbar placeholder:text-gray-500" style="min-height: 44px;"></textarea>
                <div class="flex items-center justify-center pl-1">
                    <button type="submit" class="w-8 h-8 bg-blue-600 text-white hover:bg-blue-500 transition-colors rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(37,99,235,0.4)] group focus:outline-none">
                        <svg class="w-4 h-4 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform ml-0.5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                    </button>
                </div>
                
                {{-- Emoji Picker Popup --}}
                <div x-show="showEmoji" @click.away="showEmoji = false" class="absolute bottom-14 left-0 z-50 shadow-2xl" x-cloak>
                    <emoji-picker @emoji-click="
                        $wire.set('aiChatMessageInput', $wire.get('aiChatMessageInput') + $event.detail.unicode); 
                        showEmoji = false;
                    " class="dark"></emoji-picker>
                </div>
            </form>
        </div>
    </div>
@else

    @if($chatWidgetTicketModel && !$isChatWidgetMinimized)
        
        {{-- FULL CHAT MODE --}}
        <div class="flex flex-col h-full w-full animate-in fade-in duration-200">
            {{-- Header --}}
            <div class="bg-white/5 backdrop-blur-xl px-3 py-3 flex items-center text-white shrink-0 shadow-[0_4px_30px_rgba(0,0,0,0.1)] z-10 border-b border-white/10 relative">
                
                {{-- Back button --}}
                <div class="flex items-center gap-1 no-drag" wire:ignore>
                    <button wire:click.stop="closeChatWidget" @click="chatListSidebarOpen = true" class="hover:bg-white/10 p-2 rounded-xl transition-colors focus:outline-none text-blue-400 hover:text-blue-300" title="Volver">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                    </button>
                </div>

                {{-- User Info --}}
                @php
                    $otherUser = (auth()->user()->role === 'user') ? $chatWidgetTicketModel->agente : $chatWidgetTicketModel->creador;
                    $isOnline = $otherUser ? $otherUser->isOnline() : false;
                @endphp
                <div class="flex items-center gap-3 ml-2 pointer-events-none">
                    <div class="relative group">
                        <div class="absolute -inset-0.5 bg-gradient-to-br from-blue-400 to-indigo-600 rounded-full blur opacity-40"></div>
                        <img src="{{ $otherUser ? $otherUser->profile_photo_url : 'https://ui-avatars.com/api/?name=U&background=1e1f38&color=4ade80' }}" class="relative w-10 h-10 rounded-full object-cover border-2 border-[#16172b] shadow-xl">
                        <div class="absolute bottom-0 right-0 w-3 h-3 {{ $isOnline ? 'bg-green-500 shadow-[0_0_8px_rgba(34,197,94,0.6)]' : 'bg-gray-500' }} border-2 border-[#0f101f] rounded-full"></div>
                    </div>
                    <div>
                        <h4 class="font-bold text-sm leading-tight text-white tracking-wide truncate max-w-[150px]">
                            {{ $otherUser ? $otherUser->nombre_completo : 'Soporte TI' }}
                        </h4>
                        <p class="text-[10px] {{ $isOnline ? 'text-green-400' : 'text-gray-500' }} font-bold tracking-widest mt-0.5">
                            {{ $isOnline ? 'En línea' : 'Fuera de línea' }}
                        </p>
                    </div>
                </div>

                {{-- Options / Minimize --}}
                <div class="flex items-center ml-auto gap-1 no-drag" wire:ignore>
                    <button wire:click.stop="toggleMinimizeChat" class="hover:bg-white/10 p-1.5 rounded-lg transition-colors focus:outline-none text-gray-400 hover:text-white" title="Minimizar">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                </div>
            </div>

            {{-- Chat Body --}}
            <div class="flex-1 overflow-y-auto p-5 space-y-4 bg-transparent flex flex-col custom-scrollbar relative" id="chat-messages-container" x-data x-init="$nextTick(() => { $el.scrollTop = $el.scrollHeight; })">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-5 pointer-events-none"></div>
                <div class="text-center mb-2 relative z-10">
                    <span class="text-[9px] font-black text-blue-400 uppercase tracking-[0.2em] bg-blue-900/30 border border-blue-500/20 px-3 py-1 rounded-full shadow-inner">Inicio de conversación</span>
                </div>
                @foreach($chatWidgetTicketModel->comentarios ?? [] as $c)
                @php
                    $isMine = (auth()->user()->role === 'user' && $c->es_cliente) || (auth()->user()->role !== 'user' && !$c->es_cliente);
                @endphp
                <div class="flex flex-col {{ $isMine ? 'items-end' : 'items-start' }} animate-in fade-in slide-in-from-bottom-2 relative z-10 group mb-2">
                    <div class="flex items-end gap-2 {{ $isMine ? 'flex-row-reverse' : 'flex-row' }}">
                        @if(!$isMine)
                            @php
                                $cAvatarPath = 'storage/avatars/perfil_' . $c->usuario_id . '.jpg';
                                $cHasAvatar = file_exists(public_path($cAvatarPath));
                            @endphp
                            @if($cHasAvatar)
                                <img src="{{ asset($cAvatarPath) }}?v={{ filemtime(public_path($cAvatarPath)) }}" class="w-7 h-7 rounded-full object-cover shadow-sm mb-0.5" alt="Avatar">
                            @else
                                <div class="w-7 h-7 rounded-full bg-gradient-to-br from-indigo-500 to-purple-600 shadow-inner text-white flex items-center justify-center text-[10px] mb-0.5">{{ substr(optional($c->usuario)->name ?? 'U', 0, 1) }}</div>
                            @endif
                        @endif
                        <div class="max-w-[85%] px-3.5 py-2.5 text-[13.5px] leading-snug shadow-sm break-words {{ $isMine ? 'bg-blue-600 text-white rounded-2xl rounded-br-sm shadow-[0_4px_15px_rgba(37,99,235,0.2)]' : 'bg-white/5 text-gray-200 border border-white/10 rounded-2xl rounded-bl-sm shadow-[0_4px_15px_rgba(0,0,0,0.1)]' }} relative backdrop-blur-md" style="font-family: 'Apple Color Emoji', 'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji', sans-serif;"><span class="font-medium">{!! nl2br(e(trim($c->mensaje))) !!}</span></div>
                    </div>
                    <span class="text-[9px] text-gray-500/80 font-bold uppercase tracking-widest mt-1.5 {{ $isMine ? 'pr-2' : 'pl-9' }} opacity-0 group-hover:opacity-100 transition-opacity duration-300">{{ $c->created_at->format('H:i') }}</span>
                </div>
                @endforeach
            </div>

            {{-- Chat Footer (Input) --}}
            <div class="p-3 bg-white/5 backdrop-blur-md border-t border-white/10 shrink-0 relative z-10" x-data="{ showEmoji: false }">
                @php
                    $isTicketClosed = in_array(optional($chatWidgetTicketModel->estado)->nombre, ['Completado', 'Cerrado']);
                    $hasAgentComment = $chatWidgetTicketModel->comentarios->where('es_cliente', false)->count() > 0;
                    $canReply = (auth()->user()->role !== 'user') || $hasAgentComment;
                @endphp
                
                @if($isTicketClosed)
                <div class="text-center p-3 bg-white/5 backdrop-blur-md rounded-xl border border-white/10 shadow-inner">
                    <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest leading-relaxed">
                        Este ticket ha finalizado.<br>
                        <span class="text-blue-400 cursor-pointer hover:underline mt-1 block" wire:click.stop="closeChatWidget" @click="chatListSidebarOpen = true">Regresar al Chatbot IA</span>
                    </p>
                </div>
                @elseif($canReply)
                <form wire:submit.prevent="sendWidgetMessage" class="relative flex items-center bg-black/20 border border-white/10 rounded-[1.25rem] focus-within:border-blue-500/50 focus-within:bg-black/40 transition-all shadow-inner pr-2">
                    <button type="button" @click="showEmoji = !showEmoji" class="px-3 text-gray-400 hover:text-white transition-colors focus:outline-none">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M14.828 14.828a4 4 0 01-5.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
                    </button>
                    <textarea wire:model="chatWidgetMessage" wire:keydown.enter.prevent="sendWidgetMessage" rows="1" placeholder="Escribe tu mensaje..." class="flex-1 bg-transparent py-3.5 pr-2 text-xs text-white outline-none resize-none custom-scrollbar placeholder:text-gray-500" style="min-height: 44px;"></textarea>
                    <div class="flex items-center justify-center pl-1">
                        <button type="submit" class="w-8 h-8 bg-blue-600 text-white hover:bg-blue-500 transition-colors rounded-full flex items-center justify-center shadow-[0_0_15px_rgba(37,99,235,0.4)] group focus:outline-none">
                            <svg class="w-4 h-4 group-hover:translate-x-0.5 group-hover:-translate-y-0.5 transition-transform ml-0.5 mb-0.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M22 2L11 13M22 2l-7 20-4-9-9-4 20-7z"/></svg>
                        </button>
                    </div>
                    
                    {{-- Emoji Picker Popup --}}
                    <div x-show="showEmoji" @click.away="showEmoji = false" class="absolute bottom-14 left-0 z-50 shadow-2xl" x-cloak>
                        <emoji-picker @emoji-click="
                            $wire.set('chatWidgetMessage', $wire.get('chatWidgetMessage') + $event.detail.unicode); 
                            showEmoji = false;
                        " class="dark"></emoji-picker>
                    </div>
                </form>
                @else
                <div class="text-center p-2 bg-white/5 backdrop-blur-md rounded-xl border border-white/10 shadow-inner">
                    <p class="text-[9px] text-gray-400 font-bold uppercase tracking-widest">
                        Esperando respuesta del Agente de TI.
                    </p>
                </div>
                @endif
            </div>
        </div>

    @else
        
        {{-- CHAT LIST SIDEBAR --}}
        <div class="flex flex-col h-full w-full animate-in fade-in duration-200 relative">
            {{-- Loading Overlay --}}
            <div wire:loading wire:target="openChatWidget" class="absolute inset-0 bg-[#0b0c16]/80 backdrop-blur-sm z-50 flex flex-col items-center justify-center animate-in fade-in">
                <svg class="animate-spin w-8 h-8 text-blue-500 mb-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                <span class="text-[10px] font-bold text-blue-400 uppercase tracking-widest">Abriendo chat...</span>
            </div>

            {{-- Header --}}
            <div class="px-5 py-4 flex items-center justify-between border-b border-white/5 shrink-0 bg-[#0f101f]">
                <h3 class="text-xl font-black text-white tracking-tight">Chats</h3>
                <button wire:click="clearAllChatsOnClose" @click="chatListSidebarOpen = false" class="text-gray-400 hover:text-white p-1.5 rounded-lg hover:bg-white/10 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/></svg>
                </button>
            </div>

            {{-- Search --}}
            <div class="px-4 py-3 shrink-0">
                <div class="relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                    </div>
                    <input type="text" wire:model.live.debounce.300ms="chatSearch" placeholder="Búsqueda" class="w-full pl-10 pr-4 py-2.5 bg-white/5 border border-white/10 rounded-full text-sm text-white placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500/50 focus:bg-white/10 transition-all">
                </div>
            </div>

            {{-- Accordion Sections --}}
            <div class="flex-1 overflow-y-auto custom-scrollbar pb-4" x-data="{ openPrivado: true }">
                {{-- Privado Section Header --}}
                <button @click="openPrivado = !openPrivado" class="w-full px-5 py-3 flex items-center justify-between text-gray-400 hover:text-white transition-colors bg-[#101026]/50">
                    <span class="text-xs font-bold uppercase tracking-wider">Privado ({{ count($chatNotificationsList) }})</span>
                    <svg class="w-4 h-4 transition-transform duration-200" :class="openPrivado ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M19 9l-7 7-7-7"/></svg>
                </button>
                
                <div x-show="openPrivado" x-collapse>
                    @forelse($chatNotificationsList as $chatNotif)
                        <button wire:click="openChatWidget({{ $chatNotif['id'] }})" class="w-full text-left px-5 py-3.5 hover:bg-white/5 transition-all flex items-center gap-4 group relative border-b border-white/5 last:border-0">
                            
                            {{-- Avatar --}}
                            <div class="relative shrink-0">
                                <img src="{{ $chatNotif['avatar'] }}" alt="Avatar" class="w-12 h-12 rounded-full object-cover border border-white/10 group-hover:border-blue-500/50 transition-colors">
                                @if($chatNotif['is_online'])
                                    <div class="absolute bottom-0 right-0 w-3.5 h-3.5 bg-green-500 border-2 border-[#0b0c16] rounded-full"></div>
                                @endif
                            </div>

                            {{-- Chat Info --}}
                            <div class="flex-1 min-w-0 pr-6">
                                <div class="flex items-center justify-between mb-1">
                                    <p class="text-sm font-bold text-white truncate">{{ $chatNotif['name'] }}</p>
                                    <span class="text-[10px] font-medium text-gray-500 shrink-0">{{ $chatNotif['time'] }}</span>
                                </div>
                                <p class="text-xs text-gray-400 truncate group-hover:text-gray-300 transition-colors">{{ $chatNotif['msg'] }}</p>
                            </div>
                        </button>
                    @empty
                        <div class="px-5 py-10 text-center">
                            <div class="w-12 h-12 rounded-full bg-white/5 flex items-center justify-center mx-auto mb-3">
                                <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                            </div>
                            <p class="text-xs font-bold text-gray-500">No hay chats activos</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>

    @endif
@endif
</aside>


</div>

<script type="module" src="https://cdn.jsdelivr.net/npm/emoji-picker-element@1/index.js"></script>
