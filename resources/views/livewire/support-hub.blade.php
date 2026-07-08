<div class="flex h-screen overflow-hidden bg-transparent text-gray-200 selection:bg-blue-500/30 selection:text-white" 
     style="font-family: 'Inter', 'Figtree', sans-serif;"
     x-data="{ 
        sidebarOpen: false, 
        activeTab: @entangle('activeTab')
     }"
     @resize.window="if(window.innerWidth < 768) sidebarOpen = false;"
     wire:poll.5s
     @play-notification-sound.window="playChime()">
    
    {{-- Scripts for Charts --}}
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

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
                <button @click="activeTab = 'generar_ticket'"
                    :class="activeTab === 'generar_ticket' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Generar Ticket</span>
                </button>
                <button @click="activeTab = 'mis_tickets'"
                    :class="activeTab === 'mis_tickets' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Mis Tickets</span>
                </button>
                <button @click="activeTab = 'gestion_archivos'"
                    :class="activeTab === 'gestion_archivos' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Gestión de Archivos</span>
                </button>
            @else
                {{-- Rutas de Admin/Agente --}}
                <button @click="activeTab = 'statistics'"
                    :class="activeTab === 'statistics' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z" /><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Métricas</span>
                </button>
                <button @click="activeTab = 'tickets'"
                    :class="activeTab === 'tickets' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 012-2h10a2 2 0 012 2v14a2 2 0 01-2 2H7a2 2 0 01-2-2V5z" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Panel Tickets</span>
                </button>
                <button @click="activeTab = 'inventory'"
                    :class="activeTab === 'inventory' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Inventario</span>
                </button>
                <button @click="activeTab = 'gestion_archivos'"
                    :class="activeTab === 'gestion_archivos' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                    class="w-full flex items-center rounded-xl transition-all duration-200 group"
                    :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                    <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                        <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 4H6a2 2 0 00-2 2v12a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-2m-4-1v8m0 0l3-3m-3 3L9 8m-5 5h2.586a1 1 0 01.707.293l2.414 2.414a1 1 0 00.707.293h3.172a1 1 0 00.707-.293l2.414-2.414a1 1 0 01.707-.293H20" /></svg>
                    </div>
                    <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Gestión de Archivos</span>
                </button>
            @endif
            <button @click="activeTab = 'map'"
                :class="activeTab === 'map' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                class="w-full flex items-center rounded-xl transition-all duration-200 group"
                :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" /></svg>
                </div>
                <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Infra Map</span>
            </button>
            @if(auth()->user()->role !== 'user')
            <button @click="activeTab = 'users'"
                :class="activeTab === 'users' ? 'bg-blue-600/90 text-white shadow-[0_0_20px_rgba(37,99,235,0.5)]' : 'text-gray-400 hover:bg-white/5 hover:text-white'"
                class="w-full flex items-center rounded-xl transition-all duration-200 group"
                :class="sidebarOpen ? 'gap-3 px-4 py-3' : 'justify-center px-2 py-3'">
                <div class="w-8 h-8 flex-shrink-0 flex items-center justify-center">
                    <svg class="w-7 h-7 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" /></svg>
                </div>
                <span x-show="sidebarOpen" class="font-bold text-[11px] uppercase tracking-wider whitespace-nowrap">Usuarios</span>
            </button>
            @endif
        </nav>

        {{-- USER PROFILE FOOTER --}}
        <div class="shrink-0 px-2 pb-4" x-data="{ profileMenuOpen: false }">
            <div class="rounded-2xl bg-white/[0.04] border border-white/[0.06] transition-all overflow-hidden">
                <div class="flex items-center cursor-pointer hover:bg-white/[0.04] transition-all p-3"
                     :class="sidebarOpen ? 'gap-3' : 'justify-center'"
                     @click="sidebarOpen && (profileMenuOpen = !profileMenuOpen)">
                    <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-black text-xs shrink-0 shadow-[0_0_12px_rgba(37,99,235,0.5)]">
                        {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                    </div>
                    <div x-show="sidebarOpen" class="min-w-0 flex-1">
                        <p class="text-xs font-bold truncate text-white tracking-tight">{{ Auth::user()->name }}</p>
                        <p class="text-[9px] text-gray-500 truncate tracking-widest uppercase mt-0.5">
                            {{ auth()->user()->role === 'user' ? 'Operativo' : (auth()->user()->role === 'admin' ? 'Administrador' : 'Agente TI') }}
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
                </div>
            </div>
            <div class="flex items-center gap-2 sm:gap-3 shrink-0" x-data="{ notifOpen: false }">
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

        <div class="flex-1 overflow-y-auto p-3 sm:p-5 md:p-8 lg:p-10 space-y-6 md:space-y-10">
            
            {{-- Welcome Greeting Banner --}}
            <div class="animate-in fade-in slide-in-from-top-5 duration-700 shrink-0">
                <div class="bg-gradient-to-r from-blue-600/10 via-indigo-600/5 to-transparent border border-white/5 rounded-2xl p-5 md:p-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4 relative overflow-hidden">
                    <div class="absolute inset-0 bg-blue-500/[0.02] rounded-2xl blur-lg pointer-events-none"></div>
                    <div class="relative z-10">
                        <h1 class="text-lg sm:text-2xl md:text-3xl font-black text-white tracking-tight uppercase">
                            ¡Hola, {{ strtoupper(Auth::user()->name) }}! 👋
                        </h1>
                        <p class="text-[10px] sm:text-xs text-gray-400 font-bold uppercase tracking-wider mt-1">
                            Bienvenido a la plataforma • Rol: <span class="text-blue-400 font-black">{{ auth()->user()->role === 'user' ? 'Operativo' : (auth()->user()->role === 'admin' ? 'Administrador' : 'Agente TI') }}</span>
                        </p>
                    </div>
                </div>
            </div>

            <template x-if="activeTab === 'tickets'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-6 md:space-y-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-xl md:text-3xl font-black text-white uppercase tracking-tighter">Control de Incidencias</h3>
                        <button wire:click="$set('showingNewTicket', true)" class="bg-teal-600 hover:bg-teal-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[.25em] shadow-lg transition-all self-start md:self-auto">
                            + Generar Ticket
                        </button>
                    </div>

                    {{-- Filters control bar --}}
                    <div class="flex flex-col xl:flex-row gap-5 items-stretch xl:items-center justify-between bg-[#101026]/60 backdrop-blur-2xl rounded-2xl p-5 border border-white/5">
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
                            <button wire:click="$set('statusFilter', 'Resuelto')" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border flex items-center gap-1.5 {{ $statusFilter === 'Resuelto' ? 'bg-emerald-600 border-emerald-500/20 text-white shadow-lg' : 'bg-white/5 border-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }} focus:outline-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span> Resueltos
                            </button>
                            <button wire:click="$set('statusFilter', 'Cerrado')" class="px-5 py-2.5 rounded-xl text-[10px] font-black uppercase tracking-wider transition-all border flex items-center gap-1.5 {{ $statusFilter === 'Cerrado' ? 'bg-purple-600 border-purple-500/20 text-white shadow-lg' : 'bg-white/5 border-white/5 text-gray-400 hover:bg-white/10 hover:text-white' }} focus:outline-none">
                                <span class="w-1.5 h-1.5 rounded-full bg-purple-400"></span> Cerrados
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
                            @if($statusFilter !== 'Todos' || $dateFilter)
                                <button wire:click="$set('statusFilter', 'Todos'); $set('dateFilter', '');" class="text-[9px] font-black text-rose-400 hover:text-rose-300 uppercase tracking-widest transition-colors focus:outline-none">
                                    Limpiar Filtros
                                </button>
                            @endif
                        </div>
                    </div>

                    {{-- Tickets Table... --}}
                    <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[1.5rem] md:rounded-[3rem] overflow-x-auto shadow-2xl">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Incidencia</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Prioridad</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Asignado A</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Estado</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-white/[0.03]">
                                @forelse($tickets as $ticket)
                                <tr class="group hover:bg-white/[0.03] transition-all">
                                    <td class="px-10 py-8 text-blue-500 font-black text-xs">#{{ $ticket->id }}</td>
                                    <td class="px-10 py-8">
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
                                    </td>
                                    <td class="px-10 py-8">
                                        <span class="text-[10px] font-black uppercase text-gray-400">{{ $ticket->prioridad->nombre }}</span>
                                    </td>
                                    <td class="px-10 py-8">
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
                                    <td class="px-10 py-8">
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
                                    <td class="px-10 py-8 text-right">
                                        <div class="flex items-center justify-end gap-3">
                                            @if(in_array(auth()->user()->role, ['admin', 'agente']))
                                                <div class="flex gap-2">
                                                    <button wire:click="viewAdminTicket({{ $ticket->id }})" class="px-3 py-2 rounded-xl text-[9px] font-black uppercase bg-blue-600/20 text-blue-400 border border-blue-500/20 hover:bg-blue-600/40 transition-all flex items-center gap-1.5" title="Editar Prioridad/Estado">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" /></svg>
                                                        Editar
                                                    </button>
                                                    <button wire:click="viewAdminTicket({{ $ticket->id }})" class="px-3 py-2 rounded-xl text-[9px] font-black uppercase bg-purple-600/20 text-purple-400 border border-purple-500/20 hover:bg-purple-600/40 transition-all flex items-center gap-1.5" title="Asignar Agente">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                                                        Asignar
                                                    </button>
                                                    <button wire:click="viewAdminTicket({{ $ticket->id }})" class="px-3 py-2 rounded-xl text-[9px] font-black uppercase bg-teal-600/20 text-teal-400 border border-teal-500/20 hover:bg-teal-600/40 transition-all flex items-center gap-1.5" title="Enviar Notificación">
                                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8" /></svg>
                                                        Enviar
                                                    </button>
                                                </div>
                                                {{-- Mostrar Reabrir rápido en tabla si está finalizado --}}
                                                @if(in_array($ticket->estado->nombre, ['Completado', 'Cerrado', 'Resuelto']))
                                                    <button wire:click="reopenTicket('{{ $ticket->id }}')"
                                                            wire:confirm="¿Estás seguro de reabrir este ticket?"
                                                            class="px-4 py-2 rounded-xl text-[9px] font-black uppercase bg-amber-600/20 text-amber-400 border border-amber-500/20 hover:bg-amber-600/40 transition-all flex items-center gap-1.5">
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

            {{-- Tabs for Normal User --}}
            <template x-if="activeTab === 'generar_ticket'">
                <div class="animate-in fade-in duration-500 w-full block text-left" 
                     style="background: linear-gradient(135deg, #020210 0%, #06051a 40%, #030318 70%, #010108 100%); position: relative;"
                     x-data="{
                        mode: 'selection',
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
                        showProduccionSub: false,
                        category: null,
                        subcategory: null,
                        intStation: '',
                        intComment: '',
                        sendingTicket: false,
                        showSuccessScreen: false,
                        maquinas: {{ json_encode($maquinas ?? []) }},
                        findMachineId(name) {
                            if (!name) return null;
                            let lower = name.toLowerCase().trim();
                            let match = this.maquinas.find(m => m.nombre.toLowerCase().includes(lower) || m.external_id.toLowerCase() === lower);
                            return match ? match.id : null;
                        },
                        get isManualReady() { return Boolean(this.manualProblem.trim() && this.manualDescription.trim() && this.manualStation.trim() && this.manualSector); },
                        get isIntuitiveReady() { return Boolean(this.category && this.subcategory && this.intStation.trim()); },
                        clearAll() {
                            this.manualProblem = ''; this.manualDescription = ''; this.manualStation = ''; this.manualPriority = '2'; this.manualSector = '';
                            this.showProduccionSub = false;
                            this.category = null; this.subcategory = null; this.intStation = ''; this.intComment = '';
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
                            this.sendingTicket = true;
                            $wire.set('ticketSectorId', this.manualSector);
                            $wire.set('ticketCategory', 'MANUAL');
                            $wire.set('ticketSubcategory', this.manualProblem);
                            $wire.set('ticketDescription', this.manualDescription + '\n\n[Estación/Área Ingresada]: ' + this.manualStation);
                            $wire.set('ticketPriority', 2);
                            let mId = this.findMachineId(this.manualStation);
                            $wire.set('ticketLocation', mId);
                            
                            let startTime = Date.now();
                            try {
                                await $wire.createTicket();
                                let elapsed = Date.now() - startTime;
                                if (elapsed < 2000) {
                                    await new Promise(resolve => setTimeout(resolve, 2000 - elapsed));
                                }
                            } catch (e) {
                                console.error(e);
                            }
                            this.sendingTicket = false;
                        },
                        async submitIntuitiveTicket() {
                            if (!this.isIntuitiveReady) return;
                            this.sendingTicket = true;
                            $wire.set('ticketSectorId', this.selectedSector);
                            $wire.set('ticketCategory', this.category);
                            $wire.set('ticketSubcategory', this.subcategory);
                            let desc = 'Generado automáticamente por Botón rápido de TI.';
                            if (this.intComment.trim()) desc += '\n\nComentario extra: ' + this.intComment;
                            desc += '\n\n[Estación/Área Ingresada]: ' + this.intStation;
                            $wire.set('ticketDescription', desc);
                            let pri = 2;
                            if (this.category === 'Seguridad TI' || this.category === 'Redes/Wifi') pri = 3;
                            else if (this.category === 'Impresión') pri = 1;
                            $wire.set('ticketPriority', pri);
                            let mId = this.findMachineId(this.intStation);
                            $wire.set('ticketLocation', mId);
                            
                            let startTime = Date.now();
                            try {
                                await $wire.createTicket();
                                let elapsed = Date.now() - startTime;
                                if (elapsed < 2000) {
                                    await new Promise(resolve => setTimeout(resolve, 2000 - elapsed));
                                }
                            } catch (e) {
                                console.error(e);
                            }
                            this.sendingTicket = false;
                        }
                     }"
                     @ticket-created.window="showSuccessScreen = true;">
                     <div style="position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0;">
                         <div style="position:absolute;width:60vw;height:60vh;top:-10vh;right:-10vw;border-radius:50%;filter:blur(90px);background:radial-gradient(circle, rgba(45,27,150,0.25) 0%, rgba(30,15,100,0.1) 40%, transparent 70%);animation:nebulaDrift 28s ease-in-out infinite alternate;"></div>
                         <div style="position:absolute;width:50vw;height:50vh;bottom:-10vh;left:-5vw;border-radius:50%;filter:blur(80px);background:radial-gradient(circle, rgba(10,50,150,0.2) 0%, transparent 70%);animation:nebulaDrift 22s ease-in-out infinite alternate;animation-delay:-10s;"></div>
                         <div style="position:absolute;width:40vw;height:40vh;top:40vh;left:40vw;border-radius:50%;filter:blur(100px);background:radial-gradient(circle, rgba(80,20,120,0.15) 0%, transparent 70%);animation:nebulaDrift 35s ease-in-out infinite alternate;animation-delay:-5s;"></div>
                         <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px);background-size:70px 70px;opacity:0.08;"></div>
                         <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.7) 1.5px, transparent 1.5px);background-size:130px 130px;background-position:35px 35px;opacity:0.06;"></div>
                     </div>
                     <div class="max-w-[1200px] w-full px-4 sm:px-6 py-6 md:px-8 mx-auto relative z-20">
                         <div x-show="mode === 'selection'" class="w-full max-w-4xl text-center py-8 animate-in fade-in duration-300">
                             <h2 class="text-2xl sm:text-4xl font-extrabold text-white mb-3 tracking-tight uppercase">¿Cómo deseas generar tu ticket?</h2>
                             <p class="text-gray-400 max-w-lg mx-auto mb-12 text-sm sm:text-base">Selecciona el método que mejor se adapte a tu reporte de tecnología.</p>
                             <div class="grid md:grid-cols-2 gap-8 px-4">
                                 <button @click="mode = 'manual'" class="group text-left bg-[#1a1a2e]/40 border-2 border-white/5 hover:border-blue-500/50 rounded-[2rem] p-8 shadow-2xl hover:shadow-[0_0_40px_rgba(37,99,235,0.25)] transition-all duration-300 focus:outline-none relative overflow-hidden flex flex-col justify-between h-80 backdrop-blur-md">
                                     <div class="absolute top-0 right-0 w-32 h-32 bg-white/[0.01] group-hover:bg-blue-500/5 rounded-bl-full transition-colors duration-300 -z-0"></div>
                                     <div class="relative z-10">
                                         <div class="w-16 h-16 bg-white/5 group-hover:bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl transition-all duration-300 mb-6 shadow-sm border border-white/10 group-hover:border-transparent">
                                             <i class="fa-solid fa-file-signature"></i>
                                         </div>
                                         <h3 class="text-xl sm:text-2xl font-black text-white mb-3 group-hover:text-blue-400 transition-colors uppercase tracking-tight">Modo Manual</h3>
                                         <p class="text-gray-400 text-sm leading-relaxed font-medium">Escribe detalladamente todo el problema que ocurrió con tus equipos o software. Ideal para fallos únicos o situaciones complejas que requieran explicaciones minuciosas.</p>
                                     </div>
                                     <div class="relative z-10 flex items-center text-blue-400 font-bold text-xs uppercase tracking-wider mt-4">Redactar problema <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i></div>
                                 </button>
                                 <button @click="mode = 'intuitive'" class="group text-left bg-[#1a1a2e]/40 border-2 border-white/5 hover:border-blue-500/50 rounded-[2rem] p-8 shadow-2xl hover:shadow-[0_0_40px_rgba(37,99,235,0.25)] transition-all duration-300 focus:outline-none relative overflow-hidden flex flex-col justify-between h-80 backdrop-blur-md">
                                     <div class="absolute top-0 right-0 w-32 h-32 bg-white/[0.01] group-hover:bg-blue-500/5 rounded-bl-full transition-colors duration-300 -z-0"></div>
                                     <div class="relative z-10">
                                         <div class="w-16 h-16 bg-white/5 group-hover:bg-blue-600 text-white rounded-2xl flex items-center justify-center text-3xl transition-all duration-300 mb-6 shadow-sm border border-white/10 group-hover:border-transparent">
                                             <i class="fa-solid fa-circle-nodes"></i>
                                         </div>
                                         <h3 class="text-xl sm:text-2xl font-black text-white mb-3 group-hover:text-blue-400 transition-colors uppercase tracking-tight">Modo Botones</h3>
                                         <p class="text-gray-400 text-sm leading-relaxed font-medium">Selecciona los problemas más frecuentes en el área de TI mediante botones dinámicos clasificados. ¡Genera tu ticket con solo un par de clics y sin escribir de más!</p>
                                     </div>
                                     <div class="relative z-10 flex items-center text-blue-400 font-bold text-xs uppercase tracking-wider mt-4">Seleccionar botones rápidos <i class="fa-solid fa-arrow-right ml-2 group-hover:translate-x-2 transition-transform"></i></div>
                                 </button>
                             </div>
                             <p class="text-gray-500 text-[10px] font-bold uppercase tracking-widest mt-16 flex items-center justify-center gap-2"><i class="fa-solid fa-shield-halved text-blue-500"></i> Tu información y reportes están resguardados por el equipo de TI corporativo</p>
                         </div>
                          <div x-show="mode === 'manual'" class="w-full max-w-xl mx-auto animate-in fade-in duration-300" style="display:none;">
                              <button @click="mode = 'selection'" class="mb-3 flex items-center text-[10px] font-black uppercase tracking-wider text-gray-500 hover:text-white transition-colors"><i class="fa-solid fa-chevron-left mr-1.5 text-xs"></i> Volver</button>
                              <div class="bg-[#0b1221]/90 backdrop-blur-2xl rounded-2xl shadow-[0_10px_30px_rgba(0,0,0,0.6)] border border-white/10 overflow-hidden">
                                  {{-- Header compacto --}}
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
                                      {{-- Sector + Estación en grid --}}
                                      <div class="grid grid-cols-2 gap-3">
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
                                          <div class="relative w-full h-12 border border-dashed border-white/10 hover:border-blue-500/50 rounded-lg bg-[#131b2f] flex items-center justify-center cursor-pointer transition-all group overflow-hidden">
                                              <input type="file" multiple wire:model="ticketFiles" class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                                              <div class="flex items-center gap-1.5 text-[10px] font-bold text-gray-500 group-hover:text-blue-400 transition-colors">
                                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" /></svg>
                                                  <span>Seleccionar archivos...</span>
                                              </div>
                                          </div>
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
                                          <h2 class="text-xl sm:text-2xl font-extrabold text-white mb-8 tracking-tight"
                                              x-text="step === 1 ? 'Seleccione un sector...' : (step === 2 ? 'Seleccione un área de servicios...' : (step === 3 ? 'Seleccione un servicio...' : (step === 4 ? 'Seleccione el problema...' : 'Detalles de la solicitud...')))">
                                          </h2>

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
                                                      <div class="w-14 h-14 bg-blue-600/10 text-blue-400 group-hover:bg-blue-600 group-hover:text-white rounded-2xl flex items-center justify-center text-3xl transition-all duration-300 shrink-0">
                                                          💼
                                                      </div>
                                                      <div>
                                                          <span class="font-extrabold text-white text-lg block group-hover:text-blue-400 transition-colors">Administración</span>
                                                          <span class="text-gray-400 text-xs mt-1 block font-medium">Oficinas y departamentos administrativos</span>
                                                      </div>
                                                  </button>

                                                  <!-- Button 2: Producción -->
                                                  <button @click="showProduccionSub = true"
                                                  class="group p-8 rounded-3xl bg-[#14142b]/40 border-2 border-white/5 hover:border-blue-500/50 hover:bg-[#1c1c38]/40 text-left transition-all duration-300 hover:-translate-y-1 hover:scale-[1.01] flex flex-col justify-between h-48 outline-none">
                                                      <div class="w-14 h-14 bg-purple-600/10 text-purple-400 group-hover:bg-purple-600 group-hover:text-white rounded-2xl flex items-center justify-center text-3xl transition-all duration-300 shrink-0">
                                                          🏭
                                                      </div>
                                                      <div>
                                                          <span class="font-extrabold text-white text-lg block group-hover:text-purple-400 transition-colors">Producción</span>
                                                          <span class="text-gray-400 text-xs mt-1 block font-medium">Áreas operativas y maquinaria industrial</span>
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
                                                      <div class="w-14 h-14 bg-amber-600/10 text-amber-400 group-hover:bg-amber-600 group-hover:text-white rounded-2xl flex items-center justify-center text-3xl transition-all duration-300 shrink-0">
                                                          📹
                                                      </div>
                                                      <div>
                                                          <span class="font-extrabold text-white text-lg block group-hover:text-amber-400 transition-colors">Vigilancia</span>
                                                          <span class="text-gray-400 text-xs mt-1 block font-medium">Cámaras, red de video y equipos de seguridad</span>
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
                                          <div x-show="step === 2" class="animate-in fade-in duration-300">
                                              <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-3 gap-5">
                                                  <template x-for="area in (selectedSectorName === 'Vigilancia' ? vigilanciaAreas : areas)" :key="area.id">
                                                      <button @click="
                                                           selectedArea = area.id;
                                                           selectedAreaName = area.name;
                                                           this.category = area.name;
                                                           let services = servicesByArea[area.id] || [];
                                                           if (services.length === 1) {
                                                               selectedService = services[0].id;
                                                               selectedServiceName = services[0].name;
                                                               step = 4;
                                                           } else {
                                                               step = 3;
                                                           }
                                                       "
                                                              class="group p-8 rounded-2xl bg-[#14142b]/40 border border-white/5 hover:border-blue-500/30 hover:bg-[#1a1a36]/50 transition-all duration-300 hover:scale-[1.02] flex flex-col items-center text-center justify-center min-h-[190px] outline-none">
                                                          <div class="w-14 h-14 rounded-2xl bg-blue-600/10 text-blue-500 group-hover:text-blue-400 flex items-center justify-center text-3xl mb-5 transition-all duration-300 border border-blue-500/10">
                                                              <i :class="getIconClass(area.icon)"></i>
                                                          </div>
                                                          <span class="font-bold text-white text-sm block tracking-wide group-hover:text-blue-400 transition-colors" x-text="area.name"></span>
                                                          <span class="text-gray-500 text-[10px] mt-2 block font-normal" x-text="area.services + ' Servicios'"></span>
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
                                                           this.subcategory = prob.name;
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
                                                          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1"><i class="fa-solid fa-map-pin text-rose-500 mr-1"></i> Área / Estación *</label>
                                                          <input type="text" x-model="intStation" required placeholder="Piso / Cubículo o Nombre del área" class="w-full px-5 py-4 rounded-xl border border-white/10 focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 text-sm outline-none transition-all font-medium">
                                                      </div>
                                                      <div>
                                                          <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2 px-1"><i class="fa-solid fa-comment-dots text-gray-500 mr-1"></i> Detalles Extra (Opcional)</label>
                                                          <textarea x-model="intComment" rows="2" placeholder="Agrega cualquier dato adicional..." class="w-full px-5 py-4 rounded-xl border border-white/10 focus:border-blue-500/60 focus:ring-4 focus:ring-blue-500/10 bg-[#131b2f] text-white placeholder:text-gray-600 text-sm outline-none transition-all resize-none font-medium leading-relaxed"></textarea>
                                                      </div>
                                                      <div class="pt-1">
                                                          <label class="block text-[9px] font-bold text-gray-400 uppercase tracking-widest mb-2 px-1">Adjuntar Evidencia (Opcional)</label>
                                                          <div class="relative w-full h-14 border border-dashed border-white/10 hover:border-blue-500/50 rounded-[0.75rem] bg-[#131b2f] flex flex-col items-center justify-center cursor-pointer transition-all group overflow-hidden">
                                                              <input type="file" multiple wire:model="ticketFiles" class="absolute inset-0 opacity-0 cursor-pointer z-10" />
                                                              <div class="flex items-center gap-2 text-[10px] font-bold text-gray-500 group-hover:text-blue-400 transition-colors">
                                                                  <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                                                      <path stroke-linecap="round" stroke-linejoin="round" d="M12 16v-8m0 8l-4-4m4 4l4-4M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1" />
                                                                  </svg>
                                                                  <span>Adjuntar archivos...</span>
                                                              </div>
                                                          </div>
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
                      <div x-show="sendingTicket" class="fixed inset-0 z-[2000] flex flex-col items-center justify-center bg-slate-950/90 backdrop-blur-md" x-transition.opacity style="display: none;">
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

                      {{-- SUCCESS SCREEN OVERLAY --}}
                      <div x-show="showSuccessScreen" class="fixed inset-0 z-[2000] flex flex-col items-center justify-center bg-slate-950/90 backdrop-blur-md p-6" x-transition.opacity style="display: none;">
                          <div class="bg-[#0b0b1e]/95 border border-white/10 rounded-[2.5rem] p-8 max-w-md w-full text-center space-y-6 shadow-2xl relative overflow-hidden">
                              <div class="absolute -top-10 -right-10 w-40 h-40 bg-emerald-500/10 blur-[50px] rounded-full"></div>
                              
                              <div class="w-20 h-20 rounded-full bg-emerald-500/10 text-emerald-400 flex items-center justify-center text-4xl mx-auto border border-emerald-500/20" style="animation: scale-in 0.5s ease-out;">
                                  <i class="fa-solid fa-circle-check"></i>
                              </div>
                              
                              <div class="space-y-2">
                                  <h3 class="text-2xl font-black text-white uppercase tracking-tight">¡Ticket Generado!</h3>
                                  <p class="text-xs text-gray-400 font-bold uppercase tracking-wider leading-relaxed">Tu solicitud ha sido registrada exitosamente en la plataforma de soporte técnico.</p>
                              </div>
                              
                              <button @click="showSuccessScreen = false; clearAll(); mode = 'selection';" class="w-full py-4 bg-emerald-600 hover:bg-emerald-500 text-white rounded-2xl text-xs font-black uppercase tracking-widest shadow-lg shadow-emerald-600/20 transition-all focus:outline-none">
                                  Entendido
                              </button>
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
                                <div class="flex items-center gap-3 mt-1.5">
                                     @if(auth()->user()->role === 'user')
                                         <span class="inline-flex items-center gap-1.5 px-2.5 py-0.5 rounded-[0.5rem] text-[9px] font-black uppercase tracking-wider
                                             @if($ticket->agente) bg-blue-500/10 text-blue-400 border border-blue-500/20 @else bg-gray-500/10 text-gray-400 border border-gray-500/20 @endif">
                                             👤 Soporte: {{ $ticket->agente ? $ticket->agente->nombre_completo : 'Por asignar' }}
                                         </span>
                                     @else
                                         <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest truncate">PRIORIDAD: {{ $ticket->prioridad->nombre }}</p>
                                     @endif
                                    @if($ticket->hora_visita)
                                        <span class="inline-flex items-center gap-1 px-2 py-0.5 rounded-full text-[9px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20 uppercase tracking-wider">
                                            🕒 Visita: {{ $ticket->hora_visita }}
                                        </span>
                                    @endif
                                </div>
                            </div>
                            <div class="relative z-10 text-right hidden sm:block w-32 shrink-0">
                                <p class="text-[9px] font-black tracking-[0.2em] text-gray-500 uppercase">Apertura</p>
                                <p class="text-sm font-bold text-gray-300 mt-0.5">{{ $ticket->created_at->format('d/m/Y') }}</p>
                            </div>
                            <svg class="relative z-10 w-6 h-6 text-gray-600 group-hover:text-white transition-all shrink-0 group-hover:translate-x-1" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
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
                                        @if($detTicket->hora_visita)
                                        <div>
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Hora de Visita Agente TI</h5>
                                            <span class="px-3 py-1.5 rounded-full text-[9px] font-black uppercase tracking-widest bg-amber-500/20 text-amber-400 border border-amber-500/30">🕒 {{ $detTicket->hora_visita }}</span>
                                        </div>
                                        @endif
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
                                            Track de Seguimiento
                                        </h5>
                                        <div class="space-y-6">
                                            @foreach($detTicket->comentarios ?? [] as $c)
                                            <div class="flex flex-col {{ $c->es_cliente ? 'items-end' : 'items-start' }} animate-in fade-in slide-in-from-bottom-2">
                                                <div class="max-w-[85%] p-5 rounded-[2rem] text-xs leading-relaxed {{ $c->es_cliente ? 'bg-blue-600 text-white rounded-tr-sm shadow-[0_10px_20px_rgba(37,99,235,0.2)]' : 'bg-white/10 text-gray-300 border border-white/10 shadow-lg rounded-tl-sm' }}">
                                                    <p class="font-black text-[9px] mb-2 uppercase tracking-[0.2em] {{ $c->es_cliente ? 'text-blue-200' : 'text-gray-500' }}">{{ $c->es_cliente ? 'Tú (Autor)' : 'Ingeniero de Soporte' }}</p>
                                                    <p class="font-medium">{{ $c->mensaje }}</p>
                                                </div>
                                                <span class="text-[9px] text-gray-600 font-bold uppercase tracking-widest mt-2 px-3">{{ $c->created_at->format('d/m/Y H:i') }}</span>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                                <div class="p-8 border-t border-white/10 bg-[#050510] relative z-10 shrink-0">
                                    <form wire:submit.prevent="addCommentToUserTicket" class="flex gap-4">
                                        <input 
                                            wire:model="userComment" 
                                            type="text" 
                                            placeholder="Ingresar nueva evidencia o respuesta..." 
                                            class="flex-1 px-6 py-4 bg-[#1a1a2e] border border-white/10 rounded-[1.5rem] outline-none focus:border-blue-500 text-xs text-white font-bold transition-all shadow-inner placeholder:text-gray-600"
                                        />
                                        <button type="submit" class="px-6 py-4 bg-blue-600 text-white rounded-[1.5rem] hover:bg-blue-500 hover:scale-105 active:scale-95 transition-all shadow-[0_0_20px_rgba(37,99,235,0.4)] flex items-center justify-center border border-blue-400">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><path d="m22 2-7 20-4-9-9-4Z"/><path d="M22 2 11 13"/></svg>
                                        </button>
                                    </form>
                                </div>
                                
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
                        
                        {{-- Avatar / Hero Section --}}
                        <div class="relative px-8 pt-10 pb-8 bg-gradient-to-br from-blue-600/10 via-indigo-600/5 to-transparent border-b border-white/5">
                            <div class="absolute inset-0 pointer-events-none">
                                <div class="absolute top-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full blur-3xl"></div>
                                <div class="absolute bottom-0 left-0 w-48 h-48 bg-indigo-600/5 rounded-full blur-3xl"></div>
                            </div>
                            <div class="relative z-10 flex items-center gap-6">
                                {{-- Avatar Circle --}}
                                <div class="w-20 h-20 rounded-[1.5rem] bg-gradient-to-br from-blue-500 via-indigo-600 to-purple-700 flex items-center justify-center text-white font-black text-2xl shadow-[0_0_30px_rgba(99,102,241,0.4)] shrink-0">
                                    {{ strtoupper(substr(Auth::user()->name, 0, 2)) }}
                                </div>
                                <div>
                                    <h2 class="text-2xl font-black text-white tracking-tight uppercase leading-tight">
                                        {{ Auth::user()->name }}
                                    </h2>
                                    @php
                                        $roleLabel = match(auth()->user()->role) {
                                            'admin' => 'Administrador',
                                            'agente' => 'Agente TI',
                                            default => 'Operador',
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
                        <div class="p-8 space-y-5">

                            {{-- Nombre --}}
                            <div class="space-y-2">
                                <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest flex items-center gap-2">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                                    Nombre Completo
                                </label>
                                <div class="w-full bg-white/[0.03] border border-white/8 rounded-xl px-4 py-3.5 flex items-center gap-3">
                                    <span class="text-sm font-bold text-white flex-1">{{ Auth::user()->name }}</span>
                                    <span class="text-[9px] font-black text-gray-600 uppercase tracking-widest border border-white/8 px-2 py-0.5 rounded">Solo lectura</span>
                                </div>
                            </div>

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
                                        class="px-5 py-3 bg-blue-600 hover:bg-blue-500 active:scale-95 text-white text-[10px] font-black uppercase tracking-wider rounded-xl transition-all shadow-md flex items-center gap-1.5 shrink-0">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/></svg>
                                        Guardar
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

            <div x-show="activeTab === 'map'" class="h-full">
                <livewire:station-map wire:key="station-map-core" />
            </div>

            <template x-if="activeTab === 'statistics'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-8"
                     x-init="$nextTick(() => window.initStatsCharts())">

                    {{-- KPI Cards --}}
                    <div class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-6 gap-4">
                        @php
                            $kpis = [
                                ['label' => 'Atrasados',   'value' => $stats['overdue'],    'color' => 'text-red-400'],
                                ['label' => 'Hoy',         'value' => $stats['dueToday'],   'color' => 'text-amber-400'],
                                ['label' => 'Abiertos',    'value' => $stats['open'],       'color' => 'text-blue-400'],
                                ['label' => 'En Espera',   'value' => $stats['hold'],       'color' => 'text-yellow-400'],
                                ['label' => 'Sin Asignar', 'value' => $stats['unassigned'], 'color' => 'text-purple-400'],
                                ['label' => 'Total',       'value' => $stats['total'],      'color' => 'text-white'],
                            ];
                        @endphp
                        @foreach($kpis as $kpi)
                        <div class="bg-[#1a1a2e]/60 backdrop-blur border border-white/5 rounded-2xl p-5 text-center flex flex-col items-center justify-center group hover:bg-blue-600/10 hover:border-blue-500/20 transition-all">
                            <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-3 group-hover:text-blue-400">{{ $kpi['label'] }}</p>
                            <p class="text-4xl font-black {{ $kpi['color'] }} leading-none">{{ str_pad($kpi['value'], 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        @endforeach
                    </div>

                    {{-- Charts Row 1 --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="bg-[#1a1a2e]/60 backdrop-blur border border-white/5 rounded-2xl p-6 overflow-hidden">
                            <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-4 border-l-4 border-blue-600 pl-3">Tickets por Prioridad</h4>
                            <div id="priorityDonutNexus"></div>
                        </div>
                        <div class="bg-[#1a1a2e]/60 backdrop-blur border border-white/5 rounded-2xl p-6 overflow-hidden">
                            <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-4 border-l-4 border-teal-500 pl-3">Tickets por Estado</h4>
                            <div id="statusBarsNexus"></div>
                        </div>
                        <div class="bg-[#1a1a2e]/60 backdrop-blur border border-white/5 rounded-2xl p-6 overflow-hidden">
                            <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-4 border-l-4 border-purple-500 pl-3">Tickets por Área</h4>
                            <div id="categoryBarsNexus"></div>
                        </div>
                    </div>

                    {{-- Charts Row 2 --}}
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                        <div class="col-span-1 lg:col-span-2 bg-[#1a1a2e]/60 backdrop-blur border border-white/5 rounded-2xl p-6 overflow-hidden">
                            <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-4 border-l-4 border-blue-400 pl-3">Tendencia Mensual (Últimos 7 meses)</h4>
                            <div id="incidentTrendNexus"></div>
                        </div>
                        <div class="bg-[#1a1a2e]/60 backdrop-blur border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center overflow-hidden">
                            <h4 class="text-[10px] font-black text-white uppercase tracking-widest mb-2">Cumplimiento SLA</h4>
                            <p class="text-[9px] text-gray-600 uppercase tracking-widest mb-4">Resueltos en ≤ 2 días</p>
                            <div id="slaGaugeNexus" class="w-full"></div>
                        </div>
                    </div>

                </div>
            </template>

            {{-- Chart init data (always rendered so PHP vars are available) --}}
            @php
                $slaColor = $slaPercent >= 90 ? '#10b981' : ($slaPercent >= 70 ? '#f59e0b' : '#ef4444');
                $statusLabels = $estadoNames->values()->toJson();
                $statusData   = $estadoNames->keys()->map(fn($id) => $statusCounts[$id] ?? 0)->values()->toJson();
                $catLabels    = $categoryData->keys()->map(fn($k) => Str::limit($k, 18))->values()->toJson();
                $catData      = $categoryData->values()->toJson();
                $months       = $trendMonths->toJson();
                $trend        = json_encode($trendData);
            @endphp
            <script>
                window.initStatsCharts = function () {
                    if (!window.ApexCharts) return;

                    // Destroy previous instances if any
                    ['#priorityDonutNexus','#statusBarsNexus','#categoryBarsNexus','#incidentTrendNexus','#slaGaugeNexus'].forEach(id => {
                        var el = document.querySelector(id);
                        if (el && el.__apexCharts) { el.__apexCharts.destroy(); }
                        if (el) el.innerHTML = '';
                    });

                    var dark = { mode: 'dark' };
                    var gridOpts = { borderColor: 'rgba(255,255,255,0.05)' };

                    // 1. Priority Donut
                    new ApexCharts(document.querySelector('#priorityDonutNexus'), {
                        chart: { type: 'donut', height: 260, background: 'transparent' },
                        series: [{{ (int)($priorityCounts[3] ?? 0) }}, {{ (int)($priorityCounts[2] ?? 0) }}, {{ (int)($priorityCounts[1] ?? 0) }}],
                        labels: ['Alta', 'Media', 'Baja'],
                        colors: ['#ef4444', '#f59e0b', '#3b82f6'],
                        theme: dark,
                        plotOptions: { pie: { donut: { size: '75%', labels: { show: true, total: { show: true, label: 'TOTAL', color: '#9ca3af', fontSize: '11px', fontWeight: 900, formatter: function() { return {{ (int)$stats['total'] }}; } } } } } },
                        legend: { position: 'bottom', labels: { colors: '#9ca3af' } },
                        dataLabels: { enabled: false },
                        stroke: { width: 0 }
                    }).render();

                    // 2. Status Bars
                    new ApexCharts(document.querySelector('#statusBarsNexus'), {
                        chart: { type: 'bar', height: 250, background: 'transparent', toolbar: { show: false } },
                        series: [{ name: 'Tickets', data: {!! $statusData !!} }],
                        colors: ['#3b82f6'],
                        theme: dark,
                        plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '55%' } },
                        xaxis: { categories: {!! $statusLabels !!}, labels: { style: { colors: '#6b7280' } } },
                        yaxis: { labels: { style: { colors: '#6b7280' } } },
                        grid: gridOpts,
                        dataLabels: { enabled: true, style: { colors: ['#fff'], fontSize: '11px', fontWeight: 700 } }
                    }).render();

                    // 3. Category Bars
                    new ApexCharts(document.querySelector('#categoryBarsNexus'), {
                        chart: { type: 'bar', height: 250, background: 'transparent', toolbar: { show: false } },
                        series: [{ name: 'Tickets', data: {!! $catData !!} }],
                        colors: ['#8b5cf6'],
                        theme: dark,
                        plotOptions: { bar: { horizontal: true, borderRadius: 5, barHeight: '55%' } },
                        xaxis: { categories: {!! $catLabels !!}, labels: { style: { colors: '#6b7280' } } },
                        yaxis: { labels: { style: { colors: '#6b7280' } } },
                        grid: gridOpts,
                        dataLabels: { enabled: true, style: { colors: ['#fff'], fontSize: '11px', fontWeight: 700 } }
                    }).render();

                    // 4. Monthly Trend
                    new ApexCharts(document.querySelector('#incidentTrendNexus'), {
                        chart: { type: 'area', height: 280, background: 'transparent', toolbar: { show: false }, zoom: { enabled: false } },
                        series: [{ name: 'Tickets', data: {!! $trend !!} }],
                        colors: ['#3b82f6'],
                        stroke: { curve: 'smooth', width: 3 },
                        theme: dark,
                        fill: { type: 'gradient', gradient: { shadeIntensity: 1, opacityFrom: 0.35, opacityTo: 0.02 } },
                        xaxis: { categories: {!! $months !!}, labels: { style: { colors: '#6b7280' } } },
                        yaxis: { min: 0, labels: { style: { colors: '#6b7280' } } },
                        grid: gridOpts,
                        dataLabels: { enabled: false },
                        markers: { size: 5, colors: ['#3b82f6'], strokeColors: '#1e3a8a', strokeWidth: 2 }
                    }).render();

                    // 5. SLA Gauge
                    new ApexCharts(document.querySelector('#slaGaugeNexus'), {
                        chart: { type: 'radialBar', height: 260, background: 'transparent' },
                        series: [{{ (int)$slaPercent }}],
                        colors: ['{{ $slaColor }}'],
                        theme: dark,
                        plotOptions: { radialBar: { hollow: { size: '60%' }, dataLabels: { name: { color: '#6b7280', fontSize: '11px', fontWeight: 700, offsetY: 20 }, value: { fontSize: '34px', fontWeight: 900, color: '#fff', offsetY: -10, formatter: function(val) { return val + '%'; } } } } },
                        labels: ['Cumplimiento']
                    }).render();
                };
            </script>

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
                    <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[3rem] overflow-hidden shadow-3xl">
                         <table class="w-full text-left border-collapse">
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
                <div class="space-y-1.5">
                    <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Nombre Completo</label>
                    <input type="text" wire:model="userName" required
                        class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                        placeholder="">
                    @error('userName') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                </div>
                
                <div class="grid grid-cols-2 gap-3">
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Código de Acceso</label>
                        <input type="text" wire:model="userCodigoAcceso" required
                            class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none focus:border-blue-500 transition-all placeholder:text-gray-600"
                            placeholder="Ej. OP-0001">
                        @error('userCodigoAcceso') <span class="text-xs text-red-400 font-bold">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1.5">
                        <label class="text-[10px] font-black text-blue-400 uppercase tracking-widest">Rol</label>
                        <select wire:model="userRole" required class="w-full bg-slate-900 border border-white/10 rounded-xl px-4 py-3 text-sm text-white outline-none appearance-none">
                            <option value="user">Operador</option>
                            <option value="agente">Agente TI</option>
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
                                    <div class="flex items-center justify-between bg-white/[0.03] border border-white/5 rounded-lg px-3 py-2.5 text-xs text-gray-300">
                                        <div class="flex items-center gap-2 truncate">
                                            <svg class="w-3.5 h-3.5 text-teal-400 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                                            <span class="truncate">{{ $file->getClientOriginalName() }}</span>
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

                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Hora de Visita Agente TI</label>
                        <input type="text" wire:model="aTicketHoraVisita" placeholder="Ej. 10:00 am o 2:00 pm" class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:border-blue-500 transition-all placeholder:text-gray-600">
                    </div>

                    <div class="space-y-3">
                        <label class="text-[9px] font-black text-blue-500 uppercase tracking-widest ml-1">Anotación / Notificar Usuario</label>
                        <textarea wire:model="aTicketKoment" rows="3" placeholder="Mensaje opcional. Se notificará al usuario de los cambios de estado." class="w-full bg-white/5 border border-white/10 rounded-xl px-4 py-3 text-white text-xs outline-none focus:border-blue-500 transition-all"></textarea>
                    </div>
                    
                    <div class="pt-2">
                        <button type="submit" class="w-full py-4 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-[11px] font-black uppercase tracking-[.3em] shadow-[0_0_15px_rgba(37,99,235,0.4)] transition-all flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4" /></svg>
                            GUARDAR Y NOTIFICAR
                        </button>
                    </div>
                </form>
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
    </script>
</div>
