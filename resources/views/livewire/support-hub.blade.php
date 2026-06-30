<div class="flex h-screen overflow-hidden bg-transparent text-gray-200 selection:bg-blue-500/30 selection:text-white" 
     style="font-family: 'Inter', 'Figtree', sans-serif;"
     x-data="{ 
        sidebarOpen: false, 
        activeTab: @entangle('activeTab')
     }"
     @resize.window="if(window.innerWidth < 768) sidebarOpen = false;">
    
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
                        <a href="/profile" class="flex items-center gap-2.5 px-3 py-2.5 rounded-xl text-[10px] font-semibold uppercase tracking-widest text-gray-400 hover:bg-white/5 hover:text-white transition-all">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                            Mi Perfil
                        </a>
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
            <div class="flex items-center gap-2 sm:gap-3 shrink-0" x-data="{ notifOpen: false, notifications: [
                { id: 1, icon: '🎫', title: 'Ticket #00042 actualizado', msg: 'Tu ticket fue tomado por un agente.', time: 'Hace 5 min', read: false },
                { id: 2, icon: '✅', title: 'Ticket #00039 resuelto', msg: 'El problema fue solucionado.', time: 'Hace 1h', read: false },
                { id: 3, icon: '💬', title: 'Nuevo comentario', msg: 'El agente dejó un mensaje en tu ticket.', time: 'Hace 3h', read: true }
            ]}">
                {{-- NOTIFICATION BELL --}}
                <div class="relative">
                    <button @click="notifOpen = !notifOpen" @click.outside="notifOpen = false"
                            class="w-10 h-10 sm:w-11 sm:h-11 rounded-2xl bg-white/5 border border-white/8 flex items-center justify-center hover:bg-white/10 active:scale-95 transition-all relative">
                        <svg class="w-5 h-5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /></svg>
                        <span x-show="notifications.some(n => !n.read)" class="absolute top-1.5 right-1.5 w-2 h-2 bg-blue-500 rounded-full ring-2 ring-[#050510] animate-pulse"></span>
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
                            <button @click="notifications = notifications.map(n => ({...n, read:true}))" class="text-[10px] font-bold text-blue-400 hover:text-blue-300 uppercase tracking-wider transition-colors">Marcar todo leído</button>
                        </div>

                        <div class="max-h-80 overflow-y-auto custom-scrollbar">
                            <template x-for="n in notifications" :key="n.id">
                                <div class="flex gap-3 px-4 py-3.5 hover:bg-white/5 transition-colors cursor-pointer border-b border-white/5"
                                     :class="!n.read ? 'bg-blue-500/5' : ''">
                                    <div class="w-9 h-9 rounded-xl flex items-center justify-center text-lg shrink-0"
                                         :class="!n.read ? 'bg-blue-600/20' : 'bg-white/5'">
                                        <span x-text="n.icon"></span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <p class="text-xs font-bold text-white truncate" x-text="n.title"></p>
                                        <p class="text-[11px] text-gray-500 mt-0.5 leading-relaxed" x-text="n.msg"></p>
                                        <p class="text-[10px] text-gray-600 mt-1 font-medium" x-text="n.time"></p>
                                    </div>
                                    <div x-show="!n.read" class="w-2 h-2 bg-blue-500 rounded-full mt-1 shrink-0"></div>
                                </div>
                            </template>
                        </div>

                        <div class="px-5 py-3 border-t border-white/8 text-center">
                            <button class="text-[11px] font-bold text-gray-500 hover:text-white transition-colors uppercase tracking-widest">Ver todas las notificaciones</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <div class="flex-1 overflow-y-auto p-3 sm:p-5 md:p-8 lg:p-10 space-y-6 md:space-y-10">
            
            <template x-if="activeTab === 'tickets'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-6 md:space-y-10">
                    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
                        <h3 class="text-xl md:text-3xl font-black text-white uppercase tracking-tighter">Control de Incidencias</h3>
                        <button wire:click="$set('showingNewTicket', true)" class="bg-teal-600 hover:bg-teal-500 text-white px-6 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[.25em] shadow-lg transition-all self-start md:self-auto">
                            + Generar Ticket
                        </button>
                    </div>
                    {{-- Tickets Table... --}}
                    <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[1.5rem] md:rounded-[3rem] overflow-x-auto shadow-2xl">
                        <table class="w-full text-left border-collapse min-w-[700px]">
                            <thead>
                                <tr class="bg-white/5 border-b border-white/5">
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Incidencia</th>
                                    <th class="px-6 md:px-10 py-5 md:py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Prioridad</th>
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
                                    </td>
                                    <td class="px-10 py-8">
                                        <span class="text-[10px] font-black uppercase text-gray-400">{{ $ticket->prioridad->nombre }}</span>
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
                <div class="animate-in fade-in duration-500 w-full h-full absolute inset-0 z-40 overflow-y-auto flex justify-center text-left" 
                     style="background: linear-gradient(135deg, #020210 0%, #06051a 40%, #030318 70%, #010108 100%); position: relative;"
                     x-data="{
                        area: null,
                        service: null,
                        category: null,
                        description: '',
                        
                        areas: [
                            { id: 'sec', name: 'SEGURIDAD TI', icon: 'ShieldCheck' },
                            { id: 'red', name: 'REDES/WIFI', icon: 'Globe' },
                            { id: 'serv', name: 'ARCHIVOS', icon: 'HardDrive' },
                            { id: 'software', name: 'SOFT Y LICENCIAS', icon: 'Cpu' },
                            { id: 'print', name: 'IMPRESIÓN', icon: 'Printer' },
                            { id: 'equipos', name: 'EQUIPOS', icon: 'Monitor' }
                        ],
                        
                        servicesData: {
                            sec: ['Accesos y contraseñas', 'Antivirus y amenazas', 'Phishing'],
                            red: ['WiFi Local', 'VPN Remota', 'Cableado'],
                            serv: ['Carpetas Compartidas', 'Permisos de OneDrive', 'Respaldos'],
                            software: ['Instalación de App', 'Licencia Vencida', 'Sistema ERP'],
                            print: ['Problema con Tóner', 'Atasco de Papel', 'Falla Mantenimiento'],
                            equipos: ['Computadora PC', 'Laptop', 'Periféricos (Mouse/Teclado)']
                        },
                        
                        categoriesData: {
                            'Accesos y contraseñas': ['Olvidé mi clave', 'Usuario bloqueado'],
                            'Antivirus y amenazas': ['Alerta de virus', 'Página bloqueada'],
                            'Phishing': ['Correo sospechoso'],
                            
                            'WiFi Local': ['No conecta', 'Intermitencia', 'Muy lento'],
                            'VPN Remota': ['No conecta desde casa', 'Se desconecta seguido'],
                            'Cableado': ['Cable roto', 'No hay link'],
                            
                            'Carpetas Compartidas': ['Falta de permisos', 'No veo la carpeta'],
                            'Permisos de OneDrive': ['No sincroniza'],
                            'Respaldos': ['Deseo restaurar archivo'],
                            
                            'Instalación de App': ['Necesito instalar un programa', 'No abre sistema ERP'],
                            'Licencia Vencida': ['Office sin licencia', 'Windows pide activación'],
                            'Sistema ERP': ['Error 500', 'No carga módulo'],
                            
                            'Problema con Tóner': ['Macha hoja', 'Nivel bajo'],
                            'Atasco de Papel': ['Atasco en bandeja 1'],
                            'Falla Mantenimiento': ['Hace ruido extraño', 'No enciende panel'],
                            
                            'Computadora PC': ['No enciende', 'Pantalla Azul recurrentemente'],
                            'Laptop': ['Batería no carga', 'No da video', 'Se reinicia sola'],
                            'Periféricos (Mouse/Teclado)': ['No funciona clic', 'Teclas rotas']
                        },

                        get isReady() {
                            return Boolean(this.area && this.service && this.category && this.description.trim() !== '');
                        },

                        clearAll() {
                            this.area = null;
                            this.service = null;
                            this.category = null;
                            this.description = '';
                        },

                        get areaName() { return this.areas.find(a => a.id === this.area)?.name; },

                        submitTicket() {
                            if (!this.isReady) return;
                            $wire.set('ticketCategory', this.areaName);
                            $wire.set('ticketSubcategory', this.service + ' -> ' + this.category);
                            $wire.set('ticketDescription', this.description);
                            $wire.createTicket();
                        }
                     }"
                     @ticket-created.window="clearAll();">

                     {{-- GALAXY NEBULA LAYERS (decorative, fixed to this panel) --}}
                     <div style="position:fixed;inset:0;pointer-events:none;overflow:hidden;z-index:0;">
                         <div style="position:absolute;width:60vw;height:60vh;top:-10vh;right:-10vw;border-radius:50%;filter:blur(90px);background:radial-gradient(circle, rgba(45,27,150,0.25) 0%, rgba(30,15,100,0.1) 40%, transparent 70%);animation:nebulaDrift 28s ease-in-out infinite alternate;"></div>
                         <div style="position:absolute;width:50vw;height:50vh;bottom:-10vh;left:-5vw;border-radius:50%;filter:blur(80px);background:radial-gradient(circle, rgba(10,50,150,0.2) 0%, transparent 70%);animation:nebulaDrift 22s ease-in-out infinite alternate;animation-delay:-10s;"></div>
                         <div style="position:absolute;width:40vw;height:40vh;top:40vh;left:40vw;border-radius:50%;filter:blur(100px);background:radial-gradient(circle, rgba(80,20,120,0.15) 0%, transparent 70%);animation:nebulaDrift 35s ease-in-out infinite alternate;animation-delay:-5s;"></div>
                         <!-- Star dust grid -->
                         <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.5) 1px, transparent 1px);background-size:70px 70px;opacity:0.08;"></div>
                         <div style="position:absolute;inset:0;background-image:radial-gradient(rgba(255,255,255,0.7) 1.5px, transparent 1.5px);background-size:130px 130px;background-position:35px 35px;opacity:0.06;"></div>
                     </div>
                     
                     <div class="max-w-[1300px] w-full px-4 sm:px-6 py-5 md:px-8 flex flex-col" style="min-height: 100%; position: relative; z-index: 2;">
                         
                         {{-- SUBTITLE ONLY (title is in header already) --}}
                         <div class="mb-4 shrink-0">
                            <p class="text-gray-500 font-medium text-[12px]">Selecciona el área, servicio e incidencia, luego describe el problema.</p>
                         </div>

                         {{-- RESPONSIVE TWO-COLUMN LAYOUT: stacks on mobile --}}
                         <div class="flex-1 flex flex-col lg:flex-row gap-4 sm:gap-6 lg:gap-8 pb-6 sm:pb-8 min-h-0 min-w-0 w-full">
                             
                             {{-- LEFT: DARK GALAXY INTERACTION CONTAINER --}}
                             <div class="flex-1 min-w-0 rounded-2xl border border-white/8 p-4 sm:p-6 flex flex-col overflow-hidden transition-all duration-300" style="background: rgba(10,12,30,0.7); backdrop-filter: blur(20px); min-height: 380px;">
                                 
                                 <div class="shrink-0 mb-4 pb-4 border-b border-white/10">
                                     <h2 class="text-lg sm:text-xl font-[800] text-white tracking-tight mb-1">
                                         <span x-show="!area">Selecciona el Área</span>
                                         <span x-show="area && !service">Selecciona el Servicio</span>
                                         <span x-show="area && service && !category">Selecciona la Incidencia</span>
                                         <span x-show="area && service && category">¡Listo! Revisa tu selección →</span>
                                     </h2>
                                     <p class="text-gray-500 text-sm">
                                         <span x-show="!category" class="select-none">Haz clic en una opción para continuar</span>
                                         <span x-show="category" class="select-none">Redacta el comentario en el panel de la derecha.</span>
                                     </p>
                                 </div>

                                 <div class="flex-1 overflow-y-auto pb-2 custom-scrollbar">
                                     {{-- STEP 1: AREAS GRID - DARK GALAXY STYLE --}}
                                     <div x-show="!area" class="grid grid-cols-2 sm:grid-cols-3 gap-3 animate-in fade-in duration-300">
                                         <template x-for="a in areas" :key="a.id">
                                             <button 
                                                 @click="area = a.id; service = null; category = null;"
                                                 class="rounded-2xl border border-white/10 hover:border-blue-500/60 hover:shadow-[0_0_20px_rgba(37,99,235,0.15)] transition-all text-center flex flex-col items-center justify-center gap-3 p-4 group outline-none focus:border-blue-500/60"
                                                 style="background: rgba(255,255,255,0.04); aspect-ratio: 4/3;"
                                             >
                                                 <div class="text-gray-400 group-hover:text-blue-400 transition-colors duration-200 group-hover:scale-110 transform">
                                                     <svg x-show="a.icon === 'ShieldCheck'" class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                     <svg x-show="a.icon === 'Globe'" class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                                     <svg x-show="a.icon === 'HardDrive'" class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/></svg>
                                                     <svg x-show="a.icon === 'Cpu'" class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2M15 20v2M2 15h2M2 9h2M20 15h2M20 9h2M9 2v2M9 20v2"/></svg>
                                                     <svg x-show="a.icon === 'Printer'" class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                                     <svg x-show="a.icon === 'Monitor'" class="w-9 h-9 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                                 </div>
                                                 <span class="text-[10px] font-[700] text-gray-300 uppercase leading-tight" x-text="a.name"></span>
                                             </button>
                                         </template>
                                     </div>

                                     {{-- STEP 2: SERVICES LIST --}}
                                     <div x-show="area && !service" style="display:none;" class="animate-in fade-in duration-300">
                                         <button @click="area = null" class="mb-4 flex items-center gap-2 text-[11px] uppercase tracking-widest text-gray-500 hover:text-blue-400 font-bold transition group select-none outline-none">
                                             <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             Volver
                                         </button>
                                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                             <template x-for="(s, index) in servicesData[area]" :key="s">
                                                 <button 
                                                     @click="service = s; category = null;"
                                                     class="px-4 py-3.5 rounded-xl border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 transition-all text-left flex items-center justify-between group outline-none"
                                                     style="background: rgba(255,255,255,0.04);"
                                                 >
                                                    <div class="font-[600] text-gray-200 text-sm" x-text="s"></div>
                                                    <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-400 transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                 </button>
                                             </template>
                                         </div>
                                     </div>

                                     {{-- STEP 3: CATEGORIES LIST --}}
                                     <div x-show="area && service && !category" style="display:none;" class="animate-in fade-in duration-300">
                                         <button @click="service = null" class="mb-4 flex items-center gap-2 text-[11px] uppercase tracking-widest text-gray-500 hover:text-blue-400 font-bold transition group select-none outline-none">
                                             <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             Volver
                                         </button>
                                         <div class="grid grid-cols-1 sm:grid-cols-2 gap-2.5">
                                             <template x-for="(c, index) in (categoriesData[service] || ['Falla General', 'Otro problema'])" :key="c">
                                                 <button 
                                                     @click="category = c"
                                                     class="px-4 py-3.5 rounded-xl border border-white/10 hover:border-blue-500/50 hover:bg-blue-500/10 transition-all text-left flex items-center justify-between group outline-none"
                                                     style="background: rgba(255,255,255,0.04);"
                                                 >
                                                    <div class="font-[600] text-gray-200 text-sm" x-text="c"></div>
                                                    <svg class="w-4 h-4 text-gray-600 group-hover:text-blue-400 transition-colors shrink-0" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                 </button>
                                             </template>
                                         </div>
                                     </div>

                                     {{-- STEP 4: CONFIRMED --}}
                                     <div x-show="area && service && category" style="display:none;" class="animate-in fade-in duration-400">
                                         <button @click="category = null" class="mb-4 flex items-center gap-2 text-[11px] uppercase tracking-widest text-gray-500 hover:text-blue-400 font-bold transition group select-none outline-none">
                                             <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             Cambiar Incidencia
                                         </button>
                                         <div class="flex flex-col items-center text-center py-6">
                                            <div class="w-16 h-16 bg-emerald-500/15 border border-emerald-500/30 text-emerald-400 rounded-2xl flex items-center justify-center mb-4 relative">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <h3 class="text-lg font-[800] text-white">¡Selección Completa!</h3>
                                            <p class="text-gray-500 text-sm mt-1 mb-5">Redacta el comentario en el panel de la derecha y envía.</p>
                                            <div class="w-full space-y-2">
                                                <p class="text-[10px] text-gray-600 uppercase tracking-widest mb-2 text-left">Frases rápidas:</p>
                                                <template x-for="text in ['El problema ocurrió de repente, necesito apoyo urgente.', 'El error aparece tal como fue descrito arriba.']" :key="text">
                                                    <button @click="description = text" class="w-full text-left px-4 py-2.5 rounded-xl border border-white/10 text-gray-400 text-xs hover:text-blue-300 hover:border-blue-500/40 transition-all" style="background:rgba(255,255,255,0.04);">
                                                        <span x-text="text"></span>
                                                    </button>
                                                </template>
                                            </div>
                                         </div>
                                     </div>

                                 </div>
                             </div>

                             {{-- RIGHT: DARK TICKET PREVIEW CARD --}}
                             <div class="w-full lg:w-[350px] xl:w-[400px] shrink-0 bg-[#0b1221] rounded-[1.5rem] p-5 sm:p-6 lg:p-8 shadow-[0_20px_50px_rgba(15,23,42,0.3)] flex flex-col overflow-y-auto custom-scrollbar-dark relative border border-[#1e293b]/50 transition-all duration-300 transform" :class="isReady ? 'scale-[1.01] shadow-[0_30px_60px_rgba(15,23,42,0.6)] border-indigo-500/30' : ''" style="min-height: 300px; max-height: 70vh;">
                                 
                                 {{-- TICKET HEADER --}}
                                 <div class="flex justify-between items-center pb-8 border-b border-[#1e293b] mb-8 shrink-0">
                                     <div class="flex items-center gap-3">
                                         <div class="w-10 h-10 flex justify-center items-center bg-[#1e293b] rounded-xl text-[#3b82f6]">
                                            <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/></svg>
                                         </div>
                                         <h3 class="text-[20px] font-[800] text-white tracking-tight">Detalle Ticket</h3>
                                     </div>
                                 </div>

                                 <div class="space-y-6 flex-1 flex flex-col">
                                     
                                     {{-- 1. AREA --}}
                                     <div>
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Área Seleccionada</label>
                                         <div class="w-full bg-[#131b2f] border border-[#1e293b]/50 rounded-[0.75rem] px-5 h-[52px] flex items-center text-[13px] font-[600] transition-colors"
                                              :class="area ? 'text-white border-[#334155]/80' : 'text-slate-600'">
                                             <span x-text="areaName || 'Pendiente...'"></span>
                                         </div>
                                     </div>

                                     {{-- 2. SERVICIO --}}
                                     <div>
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Servicio / Motivo</label>
                                         <div class="w-full bg-[#131b2f] border border-[#1e293b]/50 rounded-[0.75rem] px-5 h-[52px] flex items-center text-[13px] font-[600] transition-colors"
                                             :class="service ? 'text-white border-[#334155]/80' : 'text-slate-600'">
                                             <span x-text="service || 'Esperando servicio...'"></span>
                                         </div>
                                     </div>

                                     {{-- 3. INCIDENCIA --}}
                                     <div>
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Incidencia</label>
                                         <div class="w-full border rounded-[0.75rem] px-5 h-[52px] flex items-center text-[13px] font-[600] transition-all duration-300"
                                            :class="category ? 'border-[#3b82f6] bg-[#1e3a8a]/20 text-white shadow-[0_0_15px_rgba(59,130,246,0.1)]' : 'border-[#1e293b]/50 bg-[#131b2f] text-slate-600'">
                                             <span x-text="category || 'Esperando problema...'"></span>
                                         </div>
                                     </div>

                                     {{-- 4. COMENTARIO --}}
                                     <div class="flex-1 flex flex-col pt-1">
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-2 px-1">Comentario</label>
                                         <textarea 
                                             x-model="description"
                                             placeholder="Describe aquí lo ocurrido..."
                                             class="w-full flex-1 min-h-[140px] bg-[#131b2f] border border-[#1e293b]/50 rounded-[0.75rem] p-5 text-[14px] text-white font-[500] focus:outline-none focus:border-[#3b82f6] shadow-sm resize-none transition-all placeholder:text-slate-600 focus:bg-[#162035] leading-relaxed"
                                         ></textarea>
                                     </div>
                                 </div>

                                 {{-- SUBMIT BUTTON (Matches bottom paper airplane button from mock) --}}
                                 <button 
                                     @click="submitTicket"
                                     :disabled="!isReady"
                                     :class="isReady ? 'bg-[#2563eb] hover:bg-[#1d4ed8] text-white shadow-[0_8px_25px_rgba(37,99,235,0.4)]' : 'bg-[#1e293b]/50 text-slate-500 cursor-not-allowed border border-[#334155]/20'"
                                     class="mt-8 w-full h-[54px] rounded-[0.75rem] flex items-center justify-center gap-3 font-bold text-[13px] tracking-wide transition-all duration-300 shrink-0"
                                 >
                                     <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                     <span class="mt-0.5">Generar Ticket</span>
                                 </button>

                             </div>

                         </div>
                     </div>
                     <style>
                         @keyframes scale-in { 0% { transform: scale(0.5); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
                     </style>
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
                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-widest truncate mt-1">PRIORIDAD: {{ $ticket->prioridad->nombre }}</p>
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
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Importancia</h5>
                                            <span class="px-3 py-1 rounded-full text-[9px] font-black uppercase tracking-widest bg-purple-600/20 text-purple-400 border border-purple-500/30">{{ $detTicket->prioridad->nombre ?? 'N/A' }}</span>
                                        </div>
                                        <div>
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-2">Motivo Reportado</h5>
                                            <p class="text-xs font-bold text-gray-300 uppercase tracking-tight">{{ $detTicket->titulo }}</p>
                                        </div>
                                        <div class="col-span-1 md:col-span-2 border-t border-white/10 pt-6 mt-2">
                                            <h5 class="text-[9px] font-black text-gray-500 uppercase tracking-[0.2em] mb-4">Declaración Inicial</h5>
                                            <div class="bg-black/40 p-6 rounded-2xl text-xs font-medium text-gray-400 border border-white/5 shadow-inner leading-relax italic">
                                                "{{ $detTicket->descripcion }}"
                                            </div>
                                        </div>
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
                    <div class="flex items-center justify-between">
                         <h3 class="text-3xl font-black text-white tracking-tighter uppercase leading-none">Gestión de Usuarios</h3>
                         <button wire:click="openUserModal()" class="bg-blue-600 hover:bg-blue-500 text-white px-10 py-4 rounded-2xl text-[11px] font-black uppercase tracking-[.25em] shadow-2xl transition-all">
                             + Registrar Usuario
                         </button>
                    </div>
                    <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[3rem] overflow-hidden shadow-3xl">
                         <table class="w-full text-left border-collapse">
                             <thead>
                                 <tr class="bg-white/5 border-b border-white/5">
                                     <th class="px-10 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Nombre</th>
                                     <th class="px-10 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Email</th>
                                     <th class="px-10 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Acciones</th>
                                 </tr>
                             </thead>
                             <tbody class="divide-y divide-white/[0.03]">
                                 @foreach($users as $user)
                                 <tr class="group hover:bg-white/[0.03] transition-all">
                                     <td class="px-10 py-7 text-sm font-black text-white uppercase tracking-tight">{{ $user->name }}</td>
                                     <td class="px-10 py-7 text-xs font-bold text-gray-500">{{ $user->email }}</td>
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


    {{-- MODAL: USER MANAGEMENT (Edit/Create) --}}
    @if($showingUserModal)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-xl bg-[#0a0a1a] rounded-[3rem] border border-blue-500/20 shadow-4xl overflow-hidden pb-10">
            <div class="p-10 flex justify-between items-center border-b border-white/5">
                <h3 class="text-3xl font-black text-white uppercase tracking-tighter">{{ $selectedUserId ? 'EDITAR PERFIL' : 'NUEVO ACCESO' }}</h3>
                <button wire:click="$set('showingUserModal', false)" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6" /></svg>
                </button>
            </div>
            <form wire:submit.prevent="saveUser" class="p-10 space-y-8">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Nombre Usuario</label>
                    <input type="text" wire:model="userName" class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none focus:border-blue-500 transition-all">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Directorio Email</label>
                    <input type="email" wire:model="userEmail" class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none focus:border-blue-500 transition-all">
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Rol</label>
                        <select wire:model="userRole" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none appearance-none">
                            <option value="user">Usuario</option>
                            <option value="admin">Administrador</option>
                        </select>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Contraseña</label>
                        <input type="password" wire:model="userPassword" placeholder="********" class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none focus:border-blue-500 transition-all">
                    </div>
                </div>
                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-blue-600 hover:bg-blue-700 text-white rounded-3xl text-[12px] font-black uppercase tracking-[.4em] shadow-2xl transition-all">
                        {{ $selectedUserId ? 'ACTUALIZAR NEXUS' : 'REGISTRAR EN NEXUS' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    {{-- MODAL: NUEVO TICKET --}}
    @if($showingNewTicket)
    <div class="fixed inset-0 z-[100] flex items-center justify-center p-6 bg-[#04040a]/95 backdrop-blur-xl animate-in zoom-in duration-300">
        <div class="w-full max-w-xl bg-[#0a0a1a] rounded-[3rem] border border-blue-500/20 shadow-4xl overflow-hidden pb-10">
            <div class="p-10 flex justify-between items-center border-b border-white/5">
                <h3 class="text-3xl font-black text-white uppercase tracking-tighter">NUEVO TICKET</h3>
                <button wire:click="$set('showingNewTicket', false)" class="text-gray-500 hover:text-white transition-colors">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M6 18L18 6" /></svg>
                </button>
            </div>
            <form wire:submit.prevent="createTicket" class="p-10 space-y-8">
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4 mb-2 block">Tipo de Problema</label>
                    <div class="grid grid-cols-2 gap-4">
                        <button type="button" wire:click="$set('ticketCategory', 'Software'); $set('ticketSubcategory', null);" :class="$wire.ticketCategory === 'Software' ? 'bg-blue-600 border-blue-500 shadow-[0_0_20px_rgba(37,99,235,0.4)]' : 'bg-white/5 border-white/5 hover:bg-white/10'" class="flex flex-col items-center justify-center p-6 rounded-2xl border transition-all">
                            <svg class="w-8 h-8 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 20l4-16m4 4l4 4-4 4M6 16l-4-4 4-4" /></svg>
                            <span class="text-white font-black uppercase tracking-widest text-[10px]">Software</span>
                        </button>
                        <button type="button" wire:click="$set('ticketCategory', 'Hardware'); $set('ticketSubcategory', null);" :class="$wire.ticketCategory === 'Hardware' ? 'bg-purple-600 border-purple-500 shadow-[0_0_20px_rgba(147,51,234,0.4)]' : 'bg-white/5 border-white/5 hover:bg-white/10'" class="flex flex-col items-center justify-center p-6 rounded-2xl border transition-all">
                            <svg class="w-8 h-8 mb-2 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                            <span class="text-white font-black uppercase tracking-widest text-[10px]">Hardware</span>
                        </button>
                    </div>
                    @error('ticketCategory') <span class="text-xs text-red-500 mt-1 font-bold ml-4">{{ $message }}</span> @enderror
                    
                    {{-- Subcategory Options Modal --}}
                    @if($ticketCategory)
                    <div class="mt-6 space-y-4 animate-in fade-in slide-in-from-top-4 duration-300">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4 mb-2 block">Detalle Específico</label>
                        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
                            @php
                                $options = $ticketCategory === 'Software' ? ['Problemas para iniciar sesión', 'Virus o malware', 'Aplicaciones que se congelan', 'Sistema lento', 'Otro (Software)'] : ['Impresora no imprime o atasca papel', 'Teclado no responde', 'Mouse no se mueve', 'Computadora no enciende', 'Cable de red desconectado', 'Otro (Hardware)'];
                            @endphp
                            @foreach($options as $option)
                                <button type="button" wire:click="$set('ticketSubcategory', '{{ $option }}')"
                                    :class="$wire.ticketSubcategory === '{{ $option }}' ? 'bg-teal-500/20 text-teal-300 border-teal-500 shadow-[0_0_15px_rgba(20,184,166,0.3)]' : 'bg-white/5 text-gray-400 hover:bg-white/10 hover:text-white border-transparent text-left'"
                                    class="px-4 py-3 rounded-xl border border-white/5 text-[11px] font-bold text-center lg:text-left transition-all leading-tight">
                                    {{ $option }}
                                </button>
                            @endforeach
                        </div>
                        @error('ticketSubcategory') <span class="text-xs text-red-500 mt-1 font-bold ml-4">{{ $message }}</span> @enderror
                    </div>
                    @endif
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Título del Ticket</label>
                    <input type="text" wire:model="ticketTitle" required class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none focus:border-blue-500 transition-all">
                </div>
                <div class="space-y-4">
                    <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Descripción del Problema</label>
                    <textarea wire:model="ticketDescription" required rows="4" class="w-full bg-white/5 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none focus:border-blue-500 transition-all"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-6">
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Prioridad</label>
                        <select wire:model="ticketPriority" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none appearance-none">
                            @foreach($prioridades as $prioridad)
                                <option value="{{ $prioridad->id }}">{{ $prioridad->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="space-y-4">
                        <label class="text-[10px] font-black text-blue-500 uppercase tracking-widest ml-4">Ubicación / Máquina</label>
                        <select wire:model="ticketLocation" class="w-full bg-slate-900 border border-white/10 rounded-2xl px-8 py-5 text-white outline-none appearance-none">
                            <option value="">(Ninguna)</option>
                            @foreach($maquinas as $maquina)
                                <option value="{{ $maquina->id }}">{{ $maquina->nombre }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="pt-6">
                    <button type="submit" class="w-full py-6 bg-teal-600 hover:bg-teal-700 text-white rounded-3xl text-[12px] font-black uppercase tracking-[.4em] shadow-2xl transition-all">
                        GENERAR TICKET
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

    {{-- Script de Partículas --}}
    <script>
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
