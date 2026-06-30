<?php
$newContent = <<<'HTML'
            <template x-if="activeTab === 'generar_ticket'">
                <div class="animate-in fade-in duration-300 w-full h-full bg-[#f8fafc] absolute inset-0 z-50 overflow-y-auto"
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
                            
                            'Instalación de App': ['Necesito PDF', 'Necesito Office'],
                            'Licencia Vencida': ['Office sin licencia', 'Windows pide activación'],
                            'Sistema ERP': ['Error 500', 'No carga módulo'],
                            
                            'Problema con Tóner': ['Macha hoja', 'Nivel bajo'],
                            'Atasco de Papel': ['Atasco en bandeja 1'],
                            'Falla Mantenimiento': ['Hace ruido extraño'],
                            
                            'Computadora PC': ['No enciende', 'Pantalla Azul'],
                            'Laptop': ['Batería no carga', 'No da video'],
                            'Periféricos (Mouse/Teclado)': ['No funciona botón', 'Teclas rotas']
                        },

                        get isReady() {
                            return Boolean(this.area && this.service && this.category && this.description);
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
                            $wire.set('ticketSubcategory', this.service + ' - ' + this.category);
                            $wire.set('ticketDescription', this.description);
                            $wire.createTicket();
                        }
                     }"
                     @ticket-created.window="clearAll();">
                     
                     <div class="max-w-[1400px] mx-auto p-6 md:p-8 lg:p-12 h-screen flex flex-col">
                         
                         {{-- TOP HEADER IN LIGHT COMPONENT --}}
                         <div class="flex justify-between items-center mb-8 shrink-0">
                            <div>
                                <h1 class="text-[32px] font-black text-[#0f172a] tracking-tight leading-none mb-2">Genera Ticket</h1>
                                <p class="text-slate-500 font-medium text-[15px]">Configura los detalles de tu requerimiento técnico</p>
                            </div>
                            <button @click="activeTab = 'mis_tickets'" class="text-white bg-blue-600 hover:bg-blue-700 px-5 py-2.5 rounded-xl font-bold shadow-md transition-all text-sm md:hidden">
                                Volver a Mis Tickets
                            </button>
                         </div>

                         <div class="flex-1 grid lg:grid-cols-[1fr_450px] gap-6 lg:gap-10 pb-8 h-full min-h-0">
                             
                             {{-- LEFT: WHITE CARDS CONTAINER --}}
                             <div class="bg-white rounded-[2rem] shadow-[0_2px_20px_rgba(0,0,0,0.03)] border border-slate-100 p-8 flex flex-col h-full overflow-hidden">
                                 
                                 <div class="shrink-0 mb-6">
                                     <h2 class="text-2xl font-bold text-[#0f172a] mb-1">
                                         <span x-show="!area">Selecciona el Área</span>
                                         <span x-show="area && !service">Selecciona el Servicio</span>
                                         <span x-show="area && service && !category">Selecciona la Incidencia</span>
                                         <span x-show="area && service && category">Comentarios Adicionales</span>
                                     </h2>
                                     <p class="text-slate-400 text-sm font-medium">Haz clic en una opción para continuar</p>
                                 </div>

                                 <div class="flex-1 overflow-y-auto custom-scrollbar-light pr-2">
                                     {{-- STEP 1: AREAS --}}
                                     <div x-show="!area" class="grid grid-cols-2 md:grid-cols-3 gap-5 animate-in fade-in duration-300">
                                         <template x-for="a in areas" :key="a.id">
                                             <button 
                                                 @click="area = a.id; service = null; category = null;"
                                                 class="aspect-[5/4] rounded-[1.5rem] border-2 border-slate-100 bg-white hover:border-blue-400 hover:shadow-lg transition-all text-center flex flex-col items-center justify-center gap-3 p-4 group"
                                             >
                                                 <div class="text-slate-300 group-hover:text-blue-500 transition-colors">
                                                     <svg x-show="a.icon === 'ShieldCheck'" class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                                                     <svg x-show="a.icon === 'Globe'" class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"/><path stroke-linecap="round" stroke-linejoin="round" d="M2 12h20M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"/></svg>
                                                     <svg x-show="a.icon === 'HardDrive'" class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><line x1="22" y1="12" x2="2" y2="12"/><path d="M5.45 5.11 2 12v6a2 2 0 0 0 2 2h16a2 2 0 0 0 2-2v-6l-3.45-6.89A2 2 0 0 0 16.76 4H7.24a2 2 0 0 0-1.79 1.11z"/><line x1="6" y1="16" x2="6.01" y2="16"/><line x1="10" y1="16" x2="10.01" y2="16"/></svg>
                                                     <svg x-show="a.icon === 'Cpu'" class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="16" height="16" x="4" y="4" rx="2"/><rect width="6" height="6" x="9" y="9" rx="1"/><path d="M15 2v2"/><path d="M15 20v2"/><path d="M2 15h2"/><path d="M2 9h2"/><path d="M20 15h2"/><path d="M20 9h2"/><path d="M9 2v2"/><path d="M9 20v2"/></svg>
                                                     <svg x-show="a.icon === 'Printer'" class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><polyline points="6 9 6 2 18 2 18 9"/><path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2"/><rect width="12" height="8" x="6" y="14"/></svg>
                                                     <svg x-show="a.icon === 'Monitor'" class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><rect width="20" height="14" x="2" y="3" rx="2"/><line x1="8" y1="21" x2="16" y2="21"/><line x1="12" y1="17" x2="12" y2="21"/></svg>
                                                 </div>
                                                 <span class="text-[11px] font-bold text-slate-800 tracking-[0.05em] uppercase px-2" x-text="a.name"></span>
                                             </button>
                                         </template>
                                     </div>

                                     {{-- STEP 2: SERVICES --}}
                                     <div x-show="area && !service" style="display:none;" class="animate-in fade-in slide-in-from-right-4 duration-300">
                                         <button @click="area = null" class="mb-6 flex items-center gap-2 text-xs text-blue-600 font-bold hover:text-blue-700 transition">
                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             ← Atrás a Áreas
                                         </button>
                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                             <template x-for="s in servicesData[area]" :key="s">
                                                 <button 
                                                     @click="service = s; category = null;"
                                                     class="p-5 rounded-[1.2rem] border-2 border-slate-100 bg-white hover:border-blue-400 hover:shadow-md transition-all text-left flex items-center justify-between group"
                                                 >
                                                    <span class="text-sm font-bold text-slate-800" x-text="s"></span>
                                                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                 </button>
                                             </template>
                                         </div>
                                     </div>

                                     {{-- STEP 3: CATEGORIES --}}
                                     <div x-show="area && service && !category" style="display:none;" class="animate-in fade-in slide-in-from-right-4 duration-300">
                                         <button @click="service = null" class="mb-6 flex items-center gap-2 text-xs text-blue-600 font-bold hover:text-blue-700 transition">
                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             ← Atrás a Servicios
                                         </button>
                                         <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                             <template x-for="c in (categoriesData[service] || ['Otro Problema'])" :key="c">
                                                 <button 
                                                     @click="category = c"
                                                     class="p-5 rounded-[1.2rem] border-2 border-slate-100 bg-white hover:border-blue-400 hover:shadow-md transition-all text-left flex items-center justify-between group"
                                                 >
                                                    <span class="text-sm font-bold text-slate-800" x-text="c"></span>
                                                    <svg class="w-5 h-5 text-slate-300 group-hover:text-blue-500 transition-colors" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/></svg>
                                                 </button>
                                             </template>
                                         </div>
                                     </div>

                                     {{-- STEP 4: SUGGESTIONS --}}
                                     <div x-show="area && service && category" style="display:none;" class="animate-in fade-in slide-in-from-right-4 duration-300">
                                         <button @click="category = null" class="mb-8 flex items-center gap-2 text-xs text-blue-600 font-bold hover:text-blue-700 transition">
                                             <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/></svg>
                                             ← Atrás a Incidencias
                                         </button>
                                         <div class="p-8 bg-slate-50 border-2 border-dashed border-slate-200 rounded-[1.5rem] flex flex-col items-center justify-center text-center">
                                            <div class="w-16 h-16 bg-blue-100 text-blue-500 rounded-full flex items-center justify-center mb-4">
                                                <svg class="w-8 h-8" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                                            </div>
                                            <h3 class="text-lg font-bold text-slate-800">Categorización Completada</h3>
                                            <p class="text-slate-500 text-sm mt-2">Por favor, detalla lo ocurrido usando el panel oscuro a la derecha.</p>
                                         </div>
                                     </div>

                                 </div>
                             </div>

                             {{-- RIGHT: DARK TICKET PREVIEW CARD --}}
                             <div class="bg-[#0f172a] rounded-[2rem] p-8 md:p-10 shadow-[0_20px_50px_rgba(15,23,42,0.5)] flex flex-col h-full overflow-y-auto custom-scrollbar-dark relative">
                                 
                                 {{-- TICKET HEADER --}}
                                 <div class="flex justify-between items-center pb-6 border-b border-white/10 mb-8 shrink-0">
                                     <div class="flex items-center gap-3">
                                         <div class="bg-blue-600 w-10 h-10 rounded-xl flex justify-center items-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/></svg>
                                         </div>
                                         <h3 class="text-2xl font-bold text-white tracking-tight">Ticket</h3>
                                     </div>
                                     <button @click="clearAll" class="text-[10px] uppercase font-bold tracking-widest text-[#64748b] hover:text-white flex items-center gap-2 transition-colors">
                                         <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
                                         BORRAR
                                     </button>
                                 </div>

                                 <div class="space-y-6 flex-1 flex flex-col">
                                     
                                     {{-- AREA --}}
                                     <div>
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Área Seleccionada</label>
                                         <div class="w-full bg-[#1e293b]/50 border border-[#334155] rounded-xl px-5 h-14 flex items-center text-sm font-medium transition-colors"
                                              :class="area ? 'text-white' : 'text-slate-500'">
                                             <span x-text="areaName || 'Pendiente...'"></span>
                                         </div>
                                     </div>

                                     {{-- SERVICIO --}}
                                     <div>
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Servicio / Motivo</label>
                                         <div class="w-full bg-[#1e293b]/50 border border-[#334155] rounded-xl px-5 h-14 flex items-center text-sm font-medium transition-colors"
                                             :class="service ? 'text-white' : 'text-slate-500'">
                                             <span x-text="service || 'Esperando servicio...'"></span>
                                         </div>
                                     </div>

                                     {{-- INCIDENCIA --}}
                                     <div>
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Incidencia</label>
                                         <div class="w-full border rounded-xl px-5 h-14 flex items-center text-sm font-medium transition-colors shadow-inner"
                                            :class="category ? 'border-blue-500 bg-[#1e3a8a]/20 text-white shadow-[0_0_15px_rgba(37,99,235,0.2)]' : 'border-[#334155] bg-[#1e293b]/50 text-slate-500'">
                                             <span x-text="category || 'Esperando problema...'"></span>
                                         </div>
                                     </div>

                                     {{-- COMENTARIO --}}
                                     <div class="flex-1 flex flex-col relative">
                                         <label class="block text-[10px] font-bold text-slate-400 uppercase tracking-widest mb-3">Comentario</label>
                                         <textarea 
                                             x-model="description"
                                             placeholder="Describe aquí lo ocurrido..."
                                             class="w-full h-full min-h-[140px] bg-[#1e293b]/50 border border-[#334155] rounded-xl p-5 text-sm text-white font-medium focus:outline-none focus:border-blue-500 focus:bg-[#1e293b]/80 shadow-inner resize-none transition-colors placeholder:text-slate-500"
                                         ></textarea>
                                     </div>
                                 </div>

                                 {{-- BUTTON --}}
                                 <button 
                                     @click="submitTicket"
                                     :disabled="!isReady"
                                     :class="isReady ? 'bg-[#2563eb] hover:bg-blue-500 text-white shadow-[0_0_30px_rgba(37,99,235,0.4)]' : 'bg-[#1e293b] text-slate-500 cursor-not-allowed'"
                                     class="mt-8 w-full py-4 sm:py-5 rounded-2xl flex justify-center items-center gap-3 font-black text-xs tracking-widest uppercase transition-all"
                                 >
                                     <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                                     Generar Ticket
                                 </button>

                             </div>

                         </div>
                     </div>
                     <style>
                         .custom-scrollbar-light::-webkit-scrollbar { width: 6px; }
                         .custom-scrollbar-light::-webkit-scrollbar-track { background: transparent; }
                         .custom-scrollbar-light::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
                         .custom-scrollbar-dark::-webkit-scrollbar { width: 6px; }
                         .custom-scrollbar-dark::-webkit-scrollbar-track { background: transparent; }
                         .custom-scrollbar-dark::-webkit-scrollbar-thumb { background: #334155; border-radius: 10px; }
                     </style>
                </div>
            </template>
HTML;

$file = 'C:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$lines = file($file);

$startIndex = -1;
$endIndex = -1;
for ($i = 0; $i < count($lines); $i++) {
    if (strpos($lines[$i], "<template x-if=\"activeTab === 'generar_ticket'\">") !== false) {
        $startIndex = $i;
    }
    if ($startIndex !== -1 && $i > $startIndex && strpos($lines[$i], "</template>") !== false) {
        // verify next active tab
        $foundNext = false;
        for ($j = $i + 1; $j < $i + 10 && $j < count($lines); $j++) {
            if (strpos($lines[$j], "activeTab ===") !== false) {
                $foundNext = true;
                break;
            }
        }
        if ($foundNext) {
            $endIndex = $i;
            break;
        }
    }
}

if ($startIndex !== -1 && $endIndex !== -1) {
    array_splice($lines, $startIndex, $endIndex - $startIndex + 1, [$newContent . "\n"]);
    file_put_contents($file, implode("", $lines));
    echo "Replaced correctly!";
} else {
    echo "Could not find bounds: start $startIndex, end $endIndex";
}
?>
