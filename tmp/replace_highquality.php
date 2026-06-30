<?php
$newContent = <<<'HTML'
            <template x-if="activeTab === 'generar_ticket'">
                <div class="animate-in fade-in duration-500 w-full h-full bg-[#f8fafc] absolute inset-0 z-40 overflow-y-auto flex justify-center text-left"
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
                            // Combine Service + Category for backend
                            $wire.set('ticketSubcategory', this.service + ' -> ' + this.category);
                            $wire.set('ticketDescription', this.description);
                            $wire.createTicket();
                        }
                     }"
                     @ticket-created.window="clearAll();">
                     
                     <div class="max-w-[1200px] w-full px-6 py-10 lg:py-16 md:px-10 h-full flex flex-col pt-8">
                         
                         {{-- TOP HEADER IN LIGHT COMPONENT (Just like Image) --}}
                         <div class="flex justify-between items-start mb-8 shrink-0">
                            <div>
                                <h1 class="text-[36px] font-[900] text-[#0f172a] tracking-tight leading-none mb-2">Genera Ticket</h1>
                                <p class="text-slate-500 font-medium text-[15px]">Configura los detalles de tu requerimiento técnico</p>
                            </div>
                            <button @click="activeTab = 'mis_tickets'" class="text-white bg-[#0f172a] hover:bg-slate-800 px-6 py-2.5 rounded-xl font-bold shadow-lg transition-all text-sm md:hidden flex items-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                Mis Tickets
                            </button>
                         </div>

                         <div class="flex-1 grid lg:grid-cols-[1fr_400px] gap-8 pb-8 h-full min-h-0">
                             
                             {{-- LEFT: WHITE INTERACTION CONTAINER --}}
                             <div class="bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-slate-100 p-8 flex flex-col h-full overflow-hidden transition-all duration-300">
                                 
                                 <div class="shrink-0 mb-8 border-b border-transparent">
                                     <h2 class="text-[22px] font-[800] text-[#0f172a] tracking-tight mb-1">
                                         <span x-show="!area">Selecciona el Área</span>
                                         <span x-show="area && !service">Selecciona el Servicio</span>
                                         <span x-show="area && service && !category">Selecciona la Incidencia</span>
                                         <span x-show="area && service && category">Información Completada</span>
                                     </h2>
                                     <p class="text-slate-400 text-sm font-medium">
                                         <span x-show="!category" class="select-none">Haz clic en una opción para continuar</span>
                                         <span x-show="category" class="select-none">Verifica tu selección y redacta el comentario.</span>
                                     </p>
                                 </div>

                                 <div class="flex-1 overflow-y-auto px-1 -mx-1 pb-4 custom-scrollbar-light">
                                     {{-- STEP 1: AREAS GRID (MATCHING IMAGE 3x2) --}}
                                     <div x-show="!area" class="grid grid-cols-2 md:grid-cols-3 gap-4 xl:gap-5 animate-in fade-in slide-in-from-bottom-2 duration-300">
                                         <template x-for="a in areas" :key="a.id">
                                             <button 
                                                 @click="area = a.id; service = null; category = null;"
                                                 class="aspect-square sm:aspect-[4/3] rounded-[1.25rem] border border-slate-200 bg-white hover:border-blue-500 hover:shadow-[0_10px_20px_rgba(37,99,235,0.05)] transition-all text-center flex flex-col items-center justify-center gap-4 p-4 lg:p-6 group outline-none focus:ring-2 focus:ring-blue-500/50 focus:border-blue-500"
                                             >
                                                 <div class="text-slate-400 group-hover:text-[#2563eb] transition-colors duration-300 group-hover:scale-110 transform">
                                                     <svg x-show="a.icon === 'ShieldCheck'" class="w-[42px] h-[42px] mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                     <svg x-show="a.icon === 'Globe'" class="w-[42px] h-[42px] mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                                     <svg x-show="a.icon === 'HardDrive'" class="w-[42px] h-[42px] mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/></svg>
                                                     <svg x-show="a.icon === 'Cpu'" class="w-[42px] h-[42px] mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
                                                     <svg x-show="a.icon === 'Printer'" class="w-[42px] h-[42px] mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                                     <svg x-show="a.icon === 'Monitor'" class="w-[42px] h-[42px] mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                                 </div>
                                                 <span class="text-[10px] sm:text-[11px] font-[800] text-[#1e293b] uppercase px-1 leading-tight" x-text="a.name"></span>
                                             </button>
                                         </template>
                                     </div>

                                     {{-- STEP 2: SERVICES LIST --}}
                                     <div x-show="area && !service" style="display:none;" class="animate-in fade-in slide-in-from-right-4 duration-300">
                                         <button @click="area = null" class="mb-6 flex items-center gap-2 text-[11px] uppercase tracking-widest text-slate-400 hover:text-blue-600 font-bold transition group select-none outline-none">
                                             <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             Volver a Áreas
                                         </button>
                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                             <template x-for="(s, index) in servicesData[area]" :key="s">
                                                 <button 
                                                     @click="service = s; category = null;"
                                                     class="p-5 md:p-6 rounded-[1rem] border border-slate-200 bg-white hover:border-blue-500 hover:shadow-[0_10px_20px_rgba(37,99,235,0.05)] transition-all text-left flex items-center justify-between group outline-none focus:border-blue-500"
                                                     :style="`animation-delay: ${index * 50}ms`"
                                                 >
                                                    <div class="font-[700] text-slate-800 text-[13px] md:text-sm tracking-tight" x-text="s"></div>
                                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                    </div>
                                                 </button>
                                             </template>
                                         </div>
                                     </div>

                                     {{-- STEP 3: CATEGORIES LIST --}}
                                     <div x-show="area && service && !category" style="display:none;" class="animate-in fade-in slide-in-from-right-4 duration-300">
                                         <button @click="service = null" class="mb-6 flex items-center gap-2 text-[11px] uppercase tracking-widest text-slate-400 hover:text-blue-600 font-bold transition group select-none outline-none">
                                             <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             Volver a Servicios
                                         </button>
                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                             <template x-for="(c, index) in (categoriesData[service] || ['Falla General', 'Otro problema'])" :key="c">
                                                 <button 
                                                     @click="category = c"
                                                     class="p-5 md:p-6 rounded-[1rem] border border-slate-200 bg-white hover:border-blue-500 hover:shadow-[0_10px_20px_rgba(37,99,235,0.05)] transition-all text-left flex items-center justify-between group outline-none focus:border-blue-500"
                                                 >
                                                    <div class="font-[700] text-slate-800 text-[13px] md:text-sm tracking-tight" x-text="c"></div>
                                                    <div class="w-8 h-8 rounded-full bg-slate-50 flex items-center justify-center text-slate-300 group-hover:bg-blue-50 group-hover:text-blue-600 transition-colors">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                    </div>
                                                 </button>
                                             </template>
                                         </div>
                                     </div>

                                     {{-- STEP 4: SUGGESTIONS --}}
                                     <div x-show="area && service && category" style="display:none;" class="animate-in fade-in duration-500 h-full flex flex-col justify-center pb-12">
                                         <button @click="category = null" class="absolute top-8 left-8 flex items-center gap-2 text-[11px] uppercase tracking-widest text-slate-400 hover:text-blue-600 font-bold transition group select-none outline-none z-10 w-max">
                                             <svg class="w-4 h-4 group-hover:-translate-x-1 transition-transform" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             Re-seleccionar Incidencia
                                         </button>

                                         <div class="flex flex-col items-center justify-center text-center max-w-sm mx-auto mt-6">
                                            <div class="w-20 h-20 bg-emerald-50 text-emerald-500 rounded-full flex items-center justify-center mb-6 ring-8 ring-emerald-50/50 relative">
                                                <svg class="w-10 h-10 animate-[scale-in_0.3s_ease-out_forwards]" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                                <div class="absolute inset-0 border border-emerald-200 rounded-full animate-ping opacity-20"></div>
                                            </div>
                                            <h3 class="text-[22px] font-[900] text-[#0f172a] tracking-tight">Opciones Seleccionadas</h3>
                                            <p class="text-slate-500 text-[14px] mt-2 mb-8 font-medium">Ahora redacta o completa tu comentario en el panel oscuro de la derecha.</p>
                                            
                                            {{-- Magic Fill Options --}}
                                            <div class="w-full text-left space-y-2 relative">
                                                <div class="absolute -top-3 left-3 bg-white px-2 text-[9px] font-black uppercase text-slate-400 tracking-widest z-10">Autocompletar</div>
                                                <div class="border border-slate-200 bg-slate-50 rounded-2xl p-4 grid gap-2">
                                                    <template x-for="text in ['El problema ocurrió de repente y necesito apoyo urgente.', 'Aparece exactamente el error descrito.']" :key="text">
                                                        <button @click="description = text" class="w-full text-left px-4 py-3 rounded-xl bg-white border border-slate-200 text-slate-600 text-[13px] font-[600] hover:text-[#2563eb] hover:bg-blue-50/50 transition-colors shadow-sm focus:outline-none">
                                                            <span x-text="text"></span>
                                                        </button>
                                                    </template>
                                                </div>
                                            </div>
                                         </div>
                                     </div>

                                 </div>
                             </div>

                             {{-- RIGHT: DARK TICKET PREVIEW CARD (EXACTLY LIKE IMAGE) --}}
                             <div class="bg-[#0b1221] rounded-[1.5rem] p-8 md:p-10 shadow-[0_20px_50px_rgba(15,23,42,0.3)] flex flex-col h-full overflow-y-auto custom-scrollbar-dark relative border border-[#1e293b]/50 transition-all duration-300 transform" :class="isReady ? 'scale-[1.01] shadow-[0_30px_60px_rgba(15,23,42,0.6)] border-indigo-500/30' : ''">
                                 
                                 {{-- TICKET HEADER --}}
                                 <div class="flex justify-between items-center pb-8 border-b border-[#1e293b] mb-8 shrink-0">
                                     <div class="flex items-center gap-3">
                                         <div class="w-8 h-8 flex justify-center items-center">
                                            <svg class="w-7 h-7 text-[#3b82f6]" fill="currentColor" viewBox="0 0 24 24"><path d="M4 6V4h16v2h-2v14a2 2 0 01-2 2H8a2 2 0 01-2-2V6H4zm4 0v14h8V6H8zm2 2h4v2h-4V8zm0 4h4v2h-4v-2z" fill-rule="evenodd" clip-rule="evenodd" stroke="currentColor" stroke-width="0.5"/></svg>
                                         </div>
                                         <h3 class="text-[22px] font-[800] text-white tracking-tight">Ticket</h3>
                                     </div>
                                     <button @click="clearAll" class="text-[10px] uppercase font-[900] tracking-widest text-slate-500 hover:text-white flex items-center gap-2 transition-colors outline-none group select-none">
                                         <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                         BORRAR
                                     </button>
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
                         .custom-scrollbar-light::-webkit-scrollbar { width: 5px; }
                         .custom-scrollbar-light::-webkit-scrollbar-track { background: transparent; }
                         .custom-scrollbar-light::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
                         .custom-scrollbar-light::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
                         
                         .custom-scrollbar-dark::-webkit-scrollbar { width: 5px; }
                         .custom-scrollbar-dark::-webkit-scrollbar-track { background: transparent; }
                         .custom-scrollbar-dark::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
                         .custom-scrollbar-dark::-webkit-scrollbar-thumb:hover { background: #475569; }

                         @keyframes scale-in { 0% { transform: scale(0.5); opacity: 0; } 100% { transform: scale(1); opacity: 1; } }
                     </style>
                </div>
            </template>
HTML;

$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$lines = file($file);

$startIndex = -1;
$endIndex = -1;
for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], "<template x-if=\"activeTab === 'generar_ticket'\">") !== false) {
        $startIndex = $i;
    }
    if ($startIndex !== -1 && $i > $startIndex && strpos($lines[$i], "</template>") !== false) {
        // Find next template/tab to avoid mis-slicing
        $isCorrectEnd = false;
        for ($j = $i + 1; $j < $i + 15 && $j < count($lines); $j++) {
            if (strpos($lines[$j], "activeTab ===") !== false) {
                $isCorrectEnd = true;
                break;
            }
        }
        if ($isCorrectEnd || $i === count($lines) -1) {
            $endIndex = $i;
            break;
        }
    }
}

if ($startIndex !== -1 && $endIndex !== -1) {
    array_splice($lines, $startIndex, $endIndex - $startIndex + 1, [$newContent . "\n"]);
    file_put_contents($file, implode("", $lines));
    echo "Replaced correctly with EXTREMELY HIGH QUALITY! $startIndex -> $endIndex";
} else {
    echo "Could not find bounds.";
}
?>
