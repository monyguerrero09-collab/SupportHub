<?php
$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

// Find the start of the statistics template
$startMarker = "<template x-if=\"activeTab === 'statistics'\">";
$startPos = strpos($content, $startMarker);

if ($startPos === false) {
    die("ERROR: Could not find statistics template start\n");
}

// Also find and remove the old external script block that starts right after </template>
// Find the </template> that closes the statistics section
// Starting from $startPos, find the closing </template>
$templateEnd = strpos($content, '</template>', $startPos) + strlen('</template>');
echo "Template ends at char: $templateEnd\n";
// After the </template>, is there a <script> block for renderSupportHubCharts?
// Check what comes after
$afterTemplate = substr($content, $templateEnd, 30);
echo "After template: " . json_encode($afterTemplate) . "\n";

// Find old script block
$oldScriptStart = strpos($content, "\n<script>\n    window.renderSupportHubCharts = function", $templateEnd - 10);
if ($oldScriptStart !== false) {
    $oldScriptEnd = strpos($content, '</script>', $oldScriptStart) + strlen('</script>');
    echo "Old script block found from $oldScriptStart to $oldScriptEnd\n";
} else {
    echo "No old script block found immediately after template\n";
    $oldScriptStart = $templateEnd;
    $oldScriptEnd = $templateEnd;
}

// Everything to replace: from startPos to end of old script
$replaceEnd = $oldScriptEnd;

$newBlock = <<<'BLADE'
<template x-if="activeTab === 'statistics'">
    <div class="space-y-8 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700"
         x-init="
            $nextTick(() => {
                if (typeof Chart === 'undefined') return;
                const kill = (id) => {
                    const el = document.getElementById(id);
                    if (el && el._ch) { el._ch.destroy(); delete el._ch; }
                };
                const trendMonths     = @json($trendMonths->values());
                const trendData       = @json($trendData);
                const trendClosedData = @json($trendClosedData);
                const plantaLabels    = @json(array_keys($plantaCounts));
                const plantaValues    = @json(array_values($plantaCounts));
                const catLabels       = @json($categoryData->keys()->values());
                const catValues       = @json($categoryData->values()->values());
                const statusOpen      = {{ $statusCounts[1] ?? 0 }};
                const statusProc      = {{ $statusCounts[2] ?? 0 }};
                const statusDone      = {{ ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0) }};

                const cfg = {
                    plugins: { tooltip: { backgroundColor:'#1e293b', titleColor:'#e2e8f0', bodyColor:'#94a3b8', borderColor:'#334155', borderWidth:1 } }
                };

                kill('trendChart');
                const c1 = document.getElementById('trendChart');
                if (c1) c1._ch = new Chart(c1, {
                    type: 'line',
                    data: {
                        labels: trendMonths,
                        datasets: [
                            { label:'Creados', data:trendData, borderColor:'#8b5cf6', backgroundColor:'rgba(139,92,246,0.12)', borderWidth:3, tension:0.4, fill:true, pointBackgroundColor:'#8b5cf6', pointRadius:5, pointHoverRadius:8 },
                            { label:'Cerrados', data:trendClosedData, borderColor:'#10b981', backgroundColor:'transparent', borderWidth:2, borderDash:[6,4], tension:0.4, pointBackgroundColor:'#10b981', pointRadius:4, pointHoverRadius:7 }
                        ]
                    },
                    options: {
                        responsive:true, maintainAspectRatio:false,
                        interaction:{ mode:'index', intersect:false },
                        plugins: { ...cfg.plugins, legend:{ labels:{ color:'#94a3b8', usePointStyle:true, padding:16 } } },
                        scales: {
                            x:{ grid:{ color:'rgba(51,65,85,0.25)' }, ticks:{ color:'#94a3b8' } },
                            y:{ grid:{ color:'rgba(51,65,85,0.25)' }, ticks:{ color:'#94a3b8', precision:0 }, beginAtZero:true }
                        }
                    }
                });

                kill('plantaChart');
                const c2 = document.getElementById('plantaChart');
                if (c2) c2._ch = new Chart(c2, {
                    type: 'doughnut',
                    data: { labels:plantaLabels, datasets:[{ data:plantaValues, backgroundColor:['#3b82f6','#8b5cf6'], borderColor:'#080c1a', borderWidth:3, hoverOffset:6 }] },
                    options: { responsive:true, maintainAspectRatio:false, cutout:'72%', plugins:{ ...cfg.plugins, legend:{ position:'bottom', labels:{ color:'#94a3b8', usePointStyle:true, padding:14 } } } }
                });

                kill('categoryChart');
                const c3 = document.getElementById('categoryChart');
                if (c3) c3._ch = new Chart(c3, {
                    type: 'bar',
                    data: {
                        labels: catLabels.length ? catLabels : ['Sin datos'],
                        datasets:[{ label:'Tickets', data:catValues.length ? catValues : [0], backgroundColor:['rgba(168,85,247,0.7)','rgba(59,130,246,0.7)','rgba(16,185,129,0.7)','rgba(245,158,11,0.7)','rgba(239,68,68,0.7)','rgba(20,184,166,0.7)'], borderColor:['#a855f7','#3b82f6','#10b981','#f59e0b','#ef4444','#14b8a6'], borderWidth:2, borderRadius:8, barThickness:22 }]
                    },
                    options: {
                        indexAxis:'y', responsive:true, maintainAspectRatio:false,
                        plugins:{ ...cfg.plugins, legend:{ display:false } },
                        scales:{ x:{ grid:{ color:'rgba(51,65,85,0.25)' }, ticks:{ color:'#94a3b8', precision:0 }, beginAtZero:true }, y:{ grid:{ display:false }, ticks:{ color:'#94a3b8', font:{ size:11 } } } }
                    }
                });

                kill('statusChart');
                const c4 = document.getElementById('statusChart');
                if (c4) c4._ch = new Chart(c4, {
                    type: 'polarArea',
                    data: { labels:['Abiertos','En Proceso','Resueltos'], datasets:[{ data:[statusOpen,statusProc,statusDone], backgroundColor:['rgba(59,130,246,0.55)','rgba(245,158,11,0.55)','rgba(16,185,129,0.55)'], borderColor:'#080c1a', borderWidth:2 }] },
                    options: { responsive:true, maintainAspectRatio:false, scales:{ r:{ grid:{ color:'rgba(51,65,85,0.3)' }, ticks:{ backdropColor:'transparent', color:'#94a3b8', precision:0 } } }, plugins:{ ...cfg.plugins, legend:{ position:'bottom', labels:{ color:'#94a3b8', usePointStyle:true, padding:14 } } } }
                });
            });
         ">

        <!-- ═══ INFO BANNER ═══ -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-900/40 via-indigo-900/40 to-purple-900/40 border border-white/10 rounded-[2rem] p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6 backdrop-blur-xl group">
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 group-hover:opacity-60 transition-opacity duration-700 pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="text-2xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-indigo-200 mb-2 uppercase tracking-tighter">¡Centro de Mando Analítico!</h2>
                <p class="text-sm text-blue-200/70 max-w-xl leading-relaxed">Métricas en tiempo real cargadas directamente desde tu base de datos operativa.</p>
            </div>
            <div class="relative z-10 flex items-center gap-4 shrink-0">
                <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 px-4 py-2 rounded-xl">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.8)]"></span>
                    <span class="text-emerald-400 font-bold text-[10px] uppercase tracking-widest">Sistema en línea</span>
                </div>
                <button wire:click="$refresh" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white px-5 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-2 active:scale-95">
                    <i class="fa-solid fa-rotate text-blue-400"></i> Sync Live
                </button>
            </div>
        </div>

        <!-- ═══ KPI CARDS ═══ -->
        <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 md:gap-6">
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[1.75rem] p-5 md:p-6 shadow-2xl relative overflow-hidden flex flex-col gap-3 group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -bottom-8 -right-8 text-white/[0.04] pointer-events-none"><i class="fa-solid fa-ticket fa-5x"></i></div>
                <div class="w-10 h-10 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400"><i class="fa-solid fa-list-ol"></i></div>
                <div><div class="text-4xl md:text-5xl font-black text-white tracking-tighter">{{ array_sum($statusCounts->toArray()) }}</div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Tickets Totales</p></div>
            </div>
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[1.75rem] p-5 md:p-6 shadow-2xl relative overflow-hidden flex flex-col gap-3 group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -bottom-8 -right-8 text-white/[0.04] pointer-events-none"><i class="fa-solid fa-folder-open fa-5x"></i></div>
                <div class="w-10 h-10 rounded-2xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400"><i class="fa-regular fa-clock"></i></div>
                <div><div class="text-4xl md:text-5xl font-black text-blue-400 tracking-tighter">{{ $statusCounts[1] ?? 0 }}</div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Abiertos</p></div>
            </div>
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[1.75rem] p-5 md:p-6 shadow-2xl relative overflow-hidden flex flex-col gap-3 group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -bottom-8 -right-8 text-white/[0.04] pointer-events-none"><i class="fa-solid fa-gears fa-5x"></i></div>
                <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400"><i class="fa-solid fa-spinner"></i></div>
                <div><div class="text-4xl md:text-5xl font-black text-amber-400 tracking-tighter">{{ $statusCounts[2] ?? 0 }}</div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">En Proceso</p></div>
            </div>
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[1.75rem] p-5 md:p-6 shadow-2xl relative overflow-hidden flex flex-col gap-3 group hover:-translate-y-1 transition-transform duration-300">
                <div class="absolute -bottom-8 -right-8 text-white/[0.04] pointer-events-none"><i class="fa-solid fa-check-double fa-5x"></i></div>
                <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400"><i class="fa-solid fa-check"></i></div>
                <div><div class="text-4xl md:text-5xl font-black text-emerald-400 tracking-tighter">{{ ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0) }}</div>
                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest mt-1">Resueltos</p></div>
            </div>
        </div>

        <!-- ═══ TENDENCIA (full width) ═══ -->
        <div class="bg-[#0a0f1d]/90 border border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-blue-500/20 flex items-center justify-center"><i class="fa-solid fa-chart-line text-blue-400 text-xs"></i></span>
                    Tendencia de Tickets — Últimos 7 Meses
                </h3>
                <span class="text-[10px] text-gray-600 font-bold uppercase tracking-widest">datos en vivo</span>
            </div>
            <div style="position:relative; height:280px; width:100%;">
                <canvas id="trendChart"></canvas>
            </div>
        </div>

        <!-- ═══ 3 CHARTS ═══ -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#0a0f1d]/90 border border-white/10 rounded-[2rem] p-6 shadow-2xl flex flex-col">
                <h3 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2 mb-5">
                    <span class="w-6 h-6 rounded-lg bg-blue-500/20 flex items-center justify-center"><i class="fa-solid fa-industry text-blue-400" style="font-size:10px;"></i></span>
                    Distribución por Planta
                </h3>
                <div style="position:relative; min-height:220px; flex:1;">
                    <canvas id="plantaChart"></canvas>
                </div>
            </div>
            <div class="bg-[#0a0f1d]/90 border border-white/10 rounded-[2rem] p-6 shadow-2xl flex flex-col">
                <h3 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2 mb-5">
                    <span class="w-6 h-6 rounded-lg bg-rose-500/20 flex items-center justify-center"><i class="fa-solid fa-tags text-rose-400" style="font-size:10px;"></i></span>
                    Tipos de Problema
                </h3>
                <div style="position:relative; min-height:220px; flex:1;">
                    <canvas id="categoryChart"></canvas>
                </div>
            </div>
            <div class="bg-[#0a0f1d]/90 border border-white/10 rounded-[2rem] p-6 shadow-2xl flex flex-col">
                <h3 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2 mb-5">
                    <span class="w-6 h-6 rounded-lg bg-amber-500/20 flex items-center justify-center"><i class="fa-solid fa-chart-pie text-amber-400" style="font-size:10px;"></i></span>
                    Proporción de Estados
                </h3>
                <div style="position:relative; min-height:220px; flex:1;">
                    <canvas id="statusChart"></canvas>
                </div>
            </div>
        </div>

        <!-- ═══ TABLA RECIENTE ═══ -->
        <div class="bg-[#0a0f1d]/90 border border-white/10 rounded-[2rem] p-6 md:p-8 shadow-2xl">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                    <span class="w-7 h-7 rounded-xl bg-amber-500/20 flex items-center justify-center"><i class="fa-solid fa-bolt text-amber-400 text-xs"></i></span>
                    Actividad Reciente
                </h3>
                <button wire:click="setTab('tickets')" class="text-[10px] font-black text-blue-400 hover:text-blue-300 uppercase tracking-widest flex items-center gap-1 transition-colors">
                    Ver todos <i class="fa-solid fa-arrow-right text-[8px]"></i>
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-left" style="min-width:540px; border-collapse:collapse;">
                    <thead>
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.08);">
                            <th class="py-3 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                            <th class="py-3 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Ticket</th>
                            <th class="py-3 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Planta</th>
                            <th class="py-3 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Estado</th>
                            <th class="py-3 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets->sortByDesc('created_at')->take(6) as $t)
                        <tr style="border-bottom:1px solid rgba(255,255,255,0.04);" class="hover:bg-white/[0.02] transition-colors group/row">
                            <td class="py-3 px-4"><span class="text-xs font-black text-gray-500 bg-white/5 px-2 py-1 rounded-md">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</span></td>
                            <td class="py-3 px-4 text-xs font-bold text-gray-200 group-hover/row:text-blue-400 transition-colors" style="max-width:200px; overflow:hidden; text-overflow:ellipsis; white-space:nowrap;">{{ Str::limit($t->titulo ?? 'Sin título', 38) }}</td>
                            <td class="py-3 px-4"><span class="text-[10px] font-black text-purple-300 bg-purple-500/10 px-2 py-1 rounded-md border border-purple-500/20">Planta {{ $t->planta ?? 1 }}</span></td>
                            <td class="py-3 px-4 text-center">
                                @if($t->estado_id == 1)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-500/10 text-blue-400 border border-blue-500/20"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span>Abierto</span>
                                @elseif($t->estado_id == 2)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-500/10 text-amber-400 border border-amber-500/20"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span>Proceso</span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span>Resuelto</span>
                                @endif
                            </td>
                            <td class="py-3 px-4 text-[11px] font-bold text-gray-500 text-right whitespace-nowrap">{{ $t->created_at->format('d/m/Y H:i') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="5" class="py-8 text-center text-sm text-gray-500">No hay tickets recientes.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
BLADE;

// Now do the replacement in the content string
$before = substr($content, 0, $startPos);
$after  = substr($content, $replaceEnd);

$newContent = $before . $newBlock . $after;
file_put_contents($file, $newContent);
echo "Done! Written " . strlen($newContent) . " bytes.\n";
