<?php
$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$lines = file($file);

$newContent = <<<'HTML'
            <template x-if="activeTab === 'generar_ticket'">
                <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 w-full"
                     x-data="{
                        query: '',
                        service: null,
                        category: null,
                        description: '',
                        availableTime: '',
                        services: [
                            { id: 'wifi', name: 'WiFi', icon: 'Wifi' },
                            { id: 'equipo', name: 'Equipo Hardware', icon: 'Monitor' },
                            { id: 'error', name: 'Sistema y Apps', icon: 'Cpu' },
                            { id: 'print', name: 'Impresión', icon: 'Printer' },
                            { id: 'red', name: 'Red/Internet', icon: 'Globe' }
                        ],
                        categories: {
                            wifi: ['No conecta', 'Se desconecta', 'Muy lento', 'Contraseña incorrecta'],
                            equipo: ['Está lento', 'No enciende', 'Pantalla falla', 'Teclado/Mouse roto'],
                            error: ['Error en sistema', 'No abre app', 'Necesito instalación', 'Acceso denegado'],
                            print: ['No imprime', 'Papel atascado', 'Sin tinta/tóner'],
                            red: ['No hay internet cableado', 'VPN desconectada']
                        },
                        smartText: {
                            wifi: ['No conecta desde la mañana', 'La red se cae constantemente', 'La señal Wifi es nula en esta zona'],
                            equipo: ['El equipo está demasiado lento para trabajar', 'El equipo no enciende', 'Aparece una pantalla azul recurrentemente'],
                            error: ['Me aparece un código de error al guardar', 'No puedo usar el sistema', 'Necesito permisos para una carpeta compartida'],
                            print: ['La impresora de mi área marca un atasco de papel', 'Aparece nivel bajo de tóner'],
                            red: ['Completamente sin conexión en mi nodo', 'Las páginas web no me cargan']
                        },
                        get filteredServices() {
                            return this.services.filter(s => s.name.toLowerCase().includes(this.query.toLowerCase()));
                        },
                        get isReady() {
                            return this.service && this.category && this.description;
                        },
                        submitTicket() {
                            if (!this.isReady) return;
                            @this.set('ticketCategory', this.services.find(s=>s.id===this.service)?.name);
                            @this.set('ticketSubcategory', this.category);
                            @this.set('ticketDescription', this.description);
                            @this.set('ticketAvailableTime', this.availableTime);
                            @this.createTicket();
                        }
                     }"
                     @ticket-created.window="service = null; category = null; description = ''; availableTime = ''; query = '';">
                     
                     <div class="grid lg:grid-cols-12 gap-8 lg:gap-12 w-full max-w-7xl mx-auto">
                         
                         {{-- LEFT: SELECTOR (Two Columns Design) --}}
                         <div class="lg:col-span-7 space-y-8 animate-in fade-in slide-in-from-left-4 duration-500">
                             
                             <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-2">
                                <h2 class="text-3xl font-black text-white uppercase tracking-tighter">Crear Solicitud</h2>
                                <div class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-4 py-2 mt-4 sm:mt-0 rounded-full text-[10px] font-black uppercase tracking-widest shadow-lg flex items-center gap-2 max-w-max">
                                     <svg class="w-3 h-3 animate-pulse" fill="currentColor" viewBox="0 0 24 24"><path d="M13 10V3L4 14h7v7l9-11h-7z"/></svg>
                                     Asistente Dinámico
                                </div>
                             </div>

                             {{-- SEARCH --}}
                             <div class="relative group">
                                 <svg class="absolute left-5 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-500 group-focus-within:text-blue-500 transition-colors" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                                 <input
                                   x-model="query"
                                   placeholder="Buscar un problema específico..."
                                   class="w-full pl-14 pr-6 py-5 rounded-3xl bg-white/[0.03] border border-white/5 text-white placeholder-gray-600 focus:outline-none focus:border-blue-500 focus:bg-blue-600/5 transition-all shadow-inner uppercase tracking-wider text-sm font-bold"
                                 />
                             </div>

                             {{-- SERVICES --}}
                             <div>
                                 <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 flex items-center gap-2"><span>01</span> | Selecciona el Área o Categoría</h3>
                                 <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                     <template x-for="s in filteredServices" :key="s.id">
                                         <button 
                                             @click="service = s.id; category = null; description = ''"
                                             :class="service === s.id ? 'bg-blue-600 border-blue-500 text-white shadow-[0_10px_30px_rgba(37,99,235,0.4)] relative overflow-hidden -translate-y-1 scale-[1.02]' : 'bg-[#1a1a2e]/60 border-white/5 hover:border-blue-500/50 hover:bg-white/10 text-gray-400 hover:text-gray-200 shadow-md group hover:-translate-y-1'"
                                             class="p-4 md:p-6 rounded-3xl border transition-all text-center flex flex-col items-center justify-center gap-3 font-bold"
                                         >
                                             <div :class="service === s.id ? 'text-white' : 'text-blue-500 group-hover:scale-110'" class="transition-transform duration-300 mb-1">
                                                 <svg x-show="s.icon === 'Wifi'" class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 20h.01M2 8.82a15 15 0 0 1 20 0M5 12.859a10 10 0 0 1 14 0M8.5 16.429a5 5 0 0 1 7 0"/></svg>
                                                 <svg x-show="s.icon === 'Monitor'" class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" x2="16" y1="21" y2="21"/><line x1="12" x2="12" y1="17" y2="21"/></svg>
                                                 <svg x-show="s.icon === 'Cpu'" class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
                                                 <svg x-show="s.icon === 'Printer'" class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 0 1-2-2v-5a2 2 0 0 1 2-2h16a2 2 0 0 1 2 2v5a2 2 0 0 1-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                                 <svg x-show="s.icon === 'Globe'" class="w-8 h-8 md:w-10 md:h-10" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path d="M12 2a15.3 15.3 0 0 1 4 10 15.3 15.3 0 0 1-4 10 15.3 15.3 0 0 1-4-10 15.3 15.3 0 0 1 4-10z"/><path d="M2 12h20"/></svg>
                                             </div>
                                             <span class="text-[10px] md:text-xs uppercase tracking-widest leading-tight" x-text="s.name"></span>
                                         </button>
                                     </template>
                                 </div>
                             </div>

                             {{-- CATEGORIES --}}
                             <div x-show="service" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                                 <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                                    <span>02</span> | ¿Qué ocurre exactamente?
                                 </h3>
                                 <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                     <template x-for="c in categories[service]" :key="c">
                                         <button 
                                             @click="category = c"
                                             :class="category === c ? 'bg-indigo-600 border-indigo-500 text-white shadow-[0_10px_30px_rgba(79,70,229,0.4)]' : 'bg-[#1a1a2e]/60 border-white/5 hover:border-indigo-500/50 hover:bg-white/10 text-gray-300 shadow-md transform hover:-translate-y-1'"
                                             class="p-4 rounded-xl border transition-all text-left text-[11px] font-bold uppercase tracking-wider flex items-center justify-between"
                                         >
                                            <span x-text="c" class="flex-1"></span>
                                            <svg x-show="category === c" class="w-5 h-5 text-indigo-300 shrink-0 ml-2" fill="none" stroke="currentColor" stroke-width="3" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m9 12 2 2 4-4"/></svg>
                                         </button>
                                     </template>
                                 </div>
                             </div>

                             {{-- SMART TEXT (QUICK COMMENTS) --}}
                             <div x-show="category" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0" style="display: none;">
                                 <h3 class="text-[10px] font-black text-gray-500 uppercase tracking-[0.3em] mb-4 flex items-center gap-2">
                                     <span>03</span> | Autocompletar Descripción 
                                 </h3>
                                 <div class="flex gap-2 flex-wrap">
                                     <template x-for="t in smartText[service]" :key="t">
                                         <button 
                                             @click="description = t"
                                             class="px-4 py-2 border border-white/10 bg-white/5 rounded-full text-[10px] font-bold uppercase tracking-widest text-gray-400 hover:bg-white/10 hover:border-blue-500/40 hover:text-white transition-colors shadow-sm"
                                         >
                                             <span x-text="t"></span>
                                         </button>
                                     </template>
                                 </div>
                             </div>

                         </div>

                         {{-- RIGHT: PREVIEW CARD --}}
                         <div class="lg:col-span-5 relative mt-4 lg:mt-0">
                             <div class="sticky top-6 bg-[#0a0a1a] border border-white/10 shadow-2xl rounded-[3rem] p-6 sm:p-8 flex flex-col min-h-[550px] shadow-[0_30px_80px_rgba(0,0,0,0.8)] group overflow-hidden">
                                 <div class="absolute inset-x-0 top-0 h-px bg-gradient-to-r from-transparent via-blue-500/50 to-transparent"></div>
                                 <div class="absolute inset-y-0 left-0 w-px bg-gradient-to-b from-transparent via-indigo-500/50 to-transparent"></div>
                                 <div class="absolute bottom-0 right-0 w-64 h-64 bg-blue-600/5 rounded-full blur-[100px] pointer-events-none"></div>

                                 <h3 class="text-xl sm:text-2xl font-black mb-8 text-white uppercase tracking-tighter flex items-center justify-between">
                                    <span>Radiografía</span>
                                    <div x-show="isReady" class="px-3 py-1.5 bg-emerald-500/10 text-emerald-400 border border-emerald-500/30 rounded-xl text-[9px] uppercase tracking-[0.2em] shadow-[0_0_15px_rgba(16,185,129,0.1)] flex items-center gap-2 transition-all">
                                      <span class="w-2 h-2 rounded-full bg-emerald-400 animate-ping"></span> Ticket Listo
                                    </div>
                                 </h3>

                                 <div class="space-y-6 flex-1 relative z-10 flex flex-col">
                                    {{-- PREVIEW ITEM: SERVICIO --}}
                                    <div class="relative group/field">
                                        <div class="absolute -left-3 top-0 bottom-0 w-1 rounded-full bg-blue-600 opacity-0 transition-opacity" :class="service ? 'opacity-100' : ''"></div>
                                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] mb-2 flex items-center gap-2">
                                            Categoría Reportada
                                        </p>
                                        <div class="min-h-[56px] flex items-center px-4 sm:px-5 rounded-2xl text-xs sm:text-sm font-bold border transition-colors uppercase tracking-wider"
                                             :class="service ? 'bg-blue-600/10 border-blue-500/20 text-blue-300' : 'bg-white/5 border-white/5 text-gray-600 border-dashed'">
                                            <span x-text="service ? services.find(s=>s.id===service)?.name : 'Esperando selección...'"></span>
                                        </div>
                                    </div>

                                    {{-- PREVIEW ITEM: PROBLEMA --}}
                                    <div class="relative group/field">
                                        <div class="absolute -left-3 top-0 bottom-0 w-1 rounded-full bg-indigo-600 opacity-0 transition-opacity" :class="category ? 'opacity-100' : ''"></div>
                                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] mb-2 flex items-center gap-2">
                                            Incidencia Detectada
                                        </p>
                                        <div class="min-h-[56px] flex items-center px-4 sm:px-5 rounded-2xl text-xs sm:text-sm font-bold border transition-colors uppercase tracking-wider"
                                            :class="category ? 'bg-indigo-600/10 border-indigo-500/20 text-indigo-300' : 'bg-white/5 border-white/5 text-gray-600 border-dashed'">
                                            <span x-text="category || 'Esperando selección...'"></span>
                                        </div>
                                    </div>

                                    {{-- DESCRIPTION --}}
                                    <div class="flex-1 flex flex-col relative group/field">
                                        <div class="absolute -left-3 top-0 bottom-0 w-1 rounded-full bg-teal-500 opacity-0 transition-opacity" :class="description ? 'opacity-100' : ''"></div>
                                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] mb-2">
                                            Declaración del Usuario
                                        </p>
                                        <textarea 
                                            x-model="description"
                                            class="w-full flex-1 min-h-[100px] sm:min-h-[120px] p-4 sm:p-5 rounded-2xl bg-[#111122]/40 border text-white transition-all resize-none text-xs sm:text-sm font-medium shadow-inner placeholder:text-gray-600 focus:outline-none"
                                            :class="description ? 'border-teal-500/40 focus:border-teal-500 bg-teal-900/10' : 'border-white/10 focus:border-white/30'"
                                            placeholder="Escriba o use sugerencias..."
                                        ></textarea>
                                    </div>

                                    {{-- HORARIO OPCIONAL --}}
                                    <div class="relative group/field">
                                        <p class="text-[9px] text-gray-500 font-black uppercase tracking-[0.3em] mb-2">
                                            Disponibilidad Técnica <span class="lowercase tracking-normal font-medium text-gray-600 ml-1">(Opcional)</span>
                                        </p>
                                        <div class="relative">
                                            <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-500" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><polyline points="12 6 12 12 16 14"/></svg>
                                            <input 
                                                x-model="availableTime"
                                                type="text"
                                                class="w-full pl-11 pr-4 py-4 rounded-2xl bg-[#111122]/40 border border-white/10 text-white transition-all text-xs font-bold shadow-inner placeholder:text-gray-600 focus:outline-none focus:border-blue-500"
                                                placeholder="Ej. De 10am a 2pm..."
                                            >
                                        </div>
                                    </div>

                                 </div>

                                 {{-- SUBMIT BTN --}}
                                 <button
                                     :disabled="!isReady"
                                     @click="submitTicket"
                                     class="mt-6 w-full py-4 sm:py-5 rounded-2xl font-black text-xs uppercase tracking-[0.2em] transition-all flex items-center justify-center gap-3 border shadow-2xl relative overflow-hidden group/btn z-10"
                                     :class="isReady ? 'bg-blue-600 text-white border-blue-500 hover:-translate-y-1 hover:shadow-[0_20px_40px_rgba(37,99,235,0.4)]' : 'bg-white/5 text-gray-500 border-white/5 cursor-not-allowed'"
                                 >
                                     <div x-show="isReady" class="absolute inset-0 bg-white/20 translate-y-full group-hover/btn:translate-y-0 transition-transform duration-300"></div>
                                     <span class="relative z-10">
                                         <span x-text="isReady ? 'Generar Ticket' : 'Requiere Datos'"></span>
                                     </span>
                                     <div x-show="isReady" class="relative z-10 animate-bounce-horizontal">
                                         <svg class="w-5 h-5 text-blue-200 group-hover/btn:text-white transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="m22 2-7 20-4-9-9-4Z"/><path stroke-linecap="round" stroke-linejoin="round" d="M22 2 11 13"/></svg>
                                     </div>
                                 </button>
                                 <style>
                                     @keyframes bounce-horizontal { 0%, 100% { transform: translateX(0); } 50% { transform: translateX(3px); } }
                                     .animate-bounce-horizontal { animation: bounce-horizontal 1.5s infinite; }
                                 </style>
                             </div>
                         </div>
                     </div>
                </div>
            </template>
HTML;

$startLine = 186; // 0-indexed would be 185
$endLine = 379;   // 0-indexed would be 378

array_splice($lines, $startLine, $endLine - $startLine, [$newContent . "\n"]);

file_put_contents($file, implode("", $lines));
echo "Done replacing.";
?>
