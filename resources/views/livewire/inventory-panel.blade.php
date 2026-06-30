<div class="space-y-12 animate-in fade-in slide-in-from-bottom-5 duration-700">
    
    {{-- Header with Title and Button --}}
    <div class="flex items-center justify-between mb-2">
        <h3 class="text-3xl font-black text-white tracking-tighter uppercase">Inventario General</h3>
        <div class="flex items-center gap-3">
            <button wire:click="$dispatch('openAddEquipment')" class="bg-blue-600 hover:bg-blue-500 text-white px-8 py-3 rounded-2xl text-[11px] font-black uppercase tracking-[.2em] flex items-center gap-2 shadow-2xl shadow-blue-600/30 transition-all hover:scale-105 active:scale-95">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M12 4v16m8-8H4" /></svg>
                Alta de Equipo
            </button>
        </div>
    </div>

    {{-- Top Summary Cards --}}
    <h4 class="text-xl font-black text-white/90 uppercase tracking-tight mb-6">Resumen de Equipos</h4>
    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
        {{-- Stat 1: Pantallas --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] p-6 flex items-center gap-5 shadow-[0_30px_60px_-12px_rgba(0,0,0,0.4)] group hover:border-blue-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-blue-600/10 border border-blue-500/20 flex items-center justify-center text-blue-400 group-hover:scale-110 group-hover:bg-blue-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Pantallas</p>
                <p class="text-3xl font-black text-white leading-none tracking-tighter">{{ $pantallaCount }}</p>
            </div>
        </div>

        {{-- Stat 2: CPUs --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] p-6 flex items-center gap-5 shadow-[0_30px_60px_-12px_rgba(0,0,0,0.4)] group hover:border-purple-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-purple-600/10 border border-purple-500/20 flex items-center justify-center text-purple-400 group-hover:scale-110 group-hover:bg-purple-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">CPUs</p>
                <p class="text-3xl font-black text-white leading-none tracking-tighter">{{ $cpuCount }}</p>
            </div>
        </div>

        {{-- Stat 3: Impresoras --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] p-6 flex items-center gap-5 shadow-[0_30px_60px_-12px_rgba(0,0,0,0.4)] group hover:border-emerald-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-emerald-600/10 border border-emerald-500/20 flex items-center justify-center text-emerald-400 group-hover:scale-110 group-hover:bg-emerald-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Impresoras</p>
                <p class="text-3xl font-black text-white leading-none tracking-tighter">{{ $impresoraCount }}</p>
            </div>
        </div>

        {{-- Stat 4: Total --}}
        <div class="bg-[#1a1a2e]/40 backdrop-blur-3xl border border-white/5 rounded-[2.5rem] p-6 flex items-center gap-5 shadow-[0_30px_60px_-12px_rgba(0,0,0,0.4)] group hover:border-amber-500/20 transition-all">
            <div class="w-14 h-14 rounded-2xl bg-amber-600/10 border border-amber-500/20 flex items-center justify-center text-amber-400 group-hover:scale-110 group-hover:bg-amber-600 group-hover:text-white transition-all duration-300 shrink-0">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" /></svg>
            </div>
            <div>
                <p class="text-[9px] font-black text-gray-500 uppercase tracking-widest mb-1 opacity-60">Total</p>
                <p class="text-3xl font-black text-white leading-none tracking-tighter">{{ $pantallaCount + $cpuCount + $impresoraCount }}</p>
            </div>
        </div>
    </div>

    {{-- Inventory Table --}}
    <div class="bg-[#1a1a2e]/60 backdrop-blur-3xl border border-white/5 rounded-[3rem] overflow-hidden shadow-[0_40px_100px_rgba(0,0,0,0.5)]">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse min-w-[900px]">
                <thead>
                    <tr class="bg-white/5 border-b border-white/5">
                        <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Equipo</th>
                        <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Código / Modelo</th>
                        <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Asignado a</th>
                        <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Movimiento</th>
                        <th class="px-8 py-7 text-[10px] font-black text-gray-500 uppercase tracking-widest">Ubicación</th>
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
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                                    @elseif($item->type === 'CPU')
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 3v2m6-2v2M9 19v2m6-2v2M5 9H3m2 6H3m18-6h-2m2 6h-2M7 19h10a2 2 0 002-2V7a2 2 0 00-2-2H7a2 2 0 00-2 2v10a2 2 0 002 2zM9 9h6v6H9V9z" /></svg>
                                    @else
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" /></svg>
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
                            @else
                                <div class="flex items-center gap-2">
                                    <div class="w-7 h-7 rounded-lg bg-white/5 flex items-center justify-center shrink-0">
                                        <svg class="w-3.5 h-3.5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/></svg>
                                    </div>
                                    <p class="text-[10px] font-bold text-gray-600 uppercase tracking-widest">Sin asignar</p>
                                </div>
                            @endif
                        </td>

                        {{-- Movimiento: Alta / Baja / Reparación --}}
                        <td class="px-8 py-6">
                            @php
                                $mov = match($item->status) {
                                    'deployed'    => ['label' => 'Alta',      'class' => 'bg-emerald-600/20 text-emerald-300 border-emerald-500/30'],
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
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-tight">{{ $item->maquina->nombre ?? 'Bodega Principal' }}</p>
                            @if($item->installed_at)
                                <p class="text-[9px] font-bold text-gray-600 mt-0.5">Desde: {{ \Carbon\Carbon::parse($item->installed_at)->format('d/m/Y') }}</p>
                            @endif
                        </td>

                        {{-- Acciones --}}
                        <td class="px-8 py-6 text-right">
                            <div class="flex items-center justify-end gap-3 opacity-0 group-hover:opacity-100 transition-all">
                                {{-- Documento (Subir/Descargar) --}}
                                @if($item->pdf_path)
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

    {{-- Edit Equipment Modal --}}
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
</div>
