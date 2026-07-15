<?php
$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

$start = strpos($content, '<template x-if="activeTab === \'statistics\'">');
$end = strpos($content, '</template>', $start) + 11;

$newHtml = <<<'HTML'
<template x-if="activeTab === 'statistics'">
    <div class="space-y-8 pb-12 animate-in fade-in slide-in-from-bottom-4 duration-700">
        <!-- Secure JSON Data Bridge for Alpine -->
        <div id="stats-json-payload" class="hidden" data-payload="{{ json_encode([
            'categoryData' => $categoryData,
            'statusCounts' => $statusCounts,
            'plantaCounts' => $plantaCounts,
            'trendMonths' => $trendMonths,
            'trendData' => $trendData,
            'trendClosedData' => $trendClosedData,
            'slaPercent' => (int)($slaPercent ?? 0)
        ]) }}"></div>
        
        <!-- Alpine Component Initializer -->
        <div x-init="
            const initCharts = () => {
                setTimeout(() => {
                    if (window.renderSupportHubCharts) {
                        const payloadElement = document.getElementById('stats-json-payload');
                        if (payloadElement) {
                            try {
                                const data = JSON.parse(payloadElement.dataset.payload);
                                window.renderSupportHubCharts(data);
                            } catch (e) {
                                console.error('Failed to parse chart data:', e);
                            }
                        }
                    }
                }, 100);
            };
            initCharts();
            window.addEventListener('ticket-created', initCharts);
            window.addEventListener('refreshCharts', initCharts);
        "></div>

        <!-- Info/Intro Banner -->
        <div class="relative overflow-hidden bg-gradient-to-r from-blue-900/40 via-indigo-900/40 to-purple-900/40 border border-white/10 rounded-[2rem] p-8 md:p-10 flex flex-col md:flex-row md:items-center justify-between gap-6 shadow-[0_8px_30px_rgb(0,0,0,0.4)] backdrop-blur-xl group">
            <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/cubes.png')] opacity-10 mix-blend-overlay pointer-events-none"></div>
            <div class="absolute -top-24 -right-24 w-64 h-64 bg-blue-600 rounded-full mix-blend-multiply filter blur-[80px] opacity-40 group-hover:opacity-60 transition-opacity duration-700 pointer-events-none"></div>
            <div class="relative z-10">
                <h2 class="text-2xl md:text-4xl font-black text-transparent bg-clip-text bg-gradient-to-r from-white via-blue-100 to-indigo-200 mb-2 uppercase tracking-tighter drop-shadow-sm">¡Centro de Mando Analítico!</h2>
                <p class="text-sm md:text-base text-blue-200/80 max-w-2xl leading-relaxed font-medium">
                    Monitorización en tiempo real del rendimiento operativo. Supervisa el flujo de tickets, tiempos de respuesta y carga de trabajo distribuida por planta con métricas de alta precisión.
                </p>
            </div>
            <div class="relative z-10 flex items-center gap-4 shrink-0">
                <div class="flex items-center gap-2 bg-emerald-500/10 border border-emerald-500/30 px-4 py-2 rounded-xl">
                    <span class="w-2.5 h-2.5 bg-emerald-400 rounded-full animate-pulse shadow-[0_0_10px_rgba(52,211,153,0.8)]"></span>
                    <span class="text-emerald-400 font-bold text-[10px] uppercase tracking-widest">Sistema en línea</span>
                </div>
                <button wire:click="$refresh" class="bg-white/5 hover:bg-white/10 border border-white/10 text-white px-5 py-2 rounded-xl text-[11px] font-black uppercase tracking-wider transition-all flex items-center gap-2 hover:shadow-[0_0_20px_rgba(255,255,255,0.1)] active:scale-95">
                    <i class="fa-solid fa-rotate text-blue-400"></i> Sync Live
                </button>
            </div>
        </div>

        <!-- 4 KPI Cards -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <!-- Totales -->
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between group hover:-translate-y-1 transition-transform duration-300 backdrop-blur-xl">
                <div class="absolute -bottom-10 -right-10 text-white/5 group-hover:text-white/10 transition-colors duration-500">
                    <i class="fa-solid fa-ticket fa-5x"></i>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-indigo-500/20 border border-indigo-500/30 flex items-center justify-center text-indigo-400 shadow-[0_0_15px_rgba(99,102,241,0.2)]">
                            <i class="fa-solid fa-list-ol text-lg"></i>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Tickets Totales</span>
                    </div>
                    <div class="text-5xl font-black text-white tracking-tighter drop-shadow-md mb-1">{{ array_sum($statusCounts->toArray()) }}</div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-indigo-500 rounded-full"></span> Registrados globalmente</p>
                </div>
            </div>

            <!-- Abiertos -->
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between group hover:-translate-y-1 transition-transform duration-300 backdrop-blur-xl">
                <div class="absolute -bottom-10 -right-10 text-white/5 group-hover:text-blue-500/10 transition-colors duration-500">
                    <i class="fa-solid fa-folder-open fa-5x"></i>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-blue-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-blue-500/20 border border-blue-500/30 flex items-center justify-center text-blue-400 shadow-[0_0_15px_rgba(59,130,246,0.2)]">
                            <i class="fa-regular fa-clock text-lg"></i>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Abiertos</span>
                    </div>
                    <div class="text-5xl font-black text-white tracking-tighter drop-shadow-md mb-1">{{ $statusCounts[1] ?? 0 }}</div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Pendientes de atención</p>
                </div>
            </div>

            <!-- En Proceso -->
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between group hover:-translate-y-1 transition-transform duration-300 backdrop-blur-xl">
                <div class="absolute -bottom-10 -right-10 text-white/5 group-hover:text-amber-500/10 transition-colors duration-500">
                    <i class="fa-solid fa-gears fa-5x"></i>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-amber-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-amber-500/20 border border-amber-500/30 flex items-center justify-center text-amber-400 shadow-[0_0_15px_rgba(245,158,11,0.2)]">
                            <i class="fa-solid fa-spinner text-lg"></i>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">En Proceso</span>
                    </div>
                    <div class="text-5xl font-black text-white tracking-tighter drop-shadow-md mb-1">{{ $statusCounts[2] ?? 0 }}</div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Siendo atendidos</p>
                </div>
            </div>

            <!-- Resueltos / Cerrados -->
            <div class="bg-[#0a0f1d]/80 border border-white/5 rounded-[2rem] p-6 shadow-2xl relative overflow-hidden flex flex-col justify-between group hover:-translate-y-1 transition-transform duration-300 backdrop-blur-xl">
                <div class="absolute -bottom-10 -right-10 text-white/5 group-hover:text-emerald-500/10 transition-colors duration-500">
                    <i class="fa-solid fa-check-double fa-5x"></i>
                </div>
                <div class="absolute inset-0 bg-gradient-to-br from-emerald-500/5 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                <div class="relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-2xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center text-emerald-400 shadow-[0_0_15px_rgba(16,185,129,0.2)]">
                            <i class="fa-solid fa-check text-lg"></i>
                        </div>
                        <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em]">Resueltos</span>
                    </div>
                    <div class="text-5xl font-black text-white tracking-tighter drop-shadow-md mb-1">{{ ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0) }}</div>
                    <p class="text-[10px] text-gray-500 font-bold uppercase tracking-widest flex items-center gap-1.5"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Tareas finalizadas</p>
                </div>
            </div>
        </div>

        <!-- Toolbar / Filters -->
        <div class="flex flex-col md:flex-row md:items-center justify-between gap-4 bg-[#050810]/60 backdrop-blur-lg border border-white/10 rounded-2xl p-4 shadow-xl">
            <div class="flex items-center gap-3">
                <span class="text-[10px] font-black text-gray-400 uppercase tracking-[0.2em] ml-2">Filtros Activos:</span>
                <div class="flex gap-2">
                    <button class="px-4 py-1.5 bg-blue-600 text-white border border-blue-500/50 rounded-xl text-[10px] font-bold uppercase tracking-wider shadow-[0_0_10px_rgba(37,99,235,0.3)]">Este Mes</button>
                    <button class="px-4 py-1.5 bg-white/5 text-gray-400 hover:text-white border border-white/5 hover:bg-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all">Trimestre</button>
                    <button class="px-4 py-1.5 bg-white/5 text-gray-400 hover:text-white border border-white/5 hover:bg-white/10 rounded-xl text-[10px] font-bold uppercase tracking-wider transition-all">Año</button>
                </div>
            </div>
            <div class="flex items-center gap-2 pr-2">
                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">SLA Cumplido:</span>
                <span class="text-xs font-black text-emerald-400 bg-emerald-500/10 px-3 py-1 rounded-lg border border-emerald-500/20">{{ $slaPercent ?? 98 }}%</span>
            </div>
        </div>

        <!-- Charts Grid -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Tendencia -->
            <div class="bg-[#0a0f1d]/90 backdrop-blur-2xl border border-white/10 rounded-[2rem] p-6 shadow-2xl flex flex-col min-h-[400px]">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                        <i class="fa-solid fa-chart-line text-blue-500"></i> Tendencia de Tickets
                    </h3>
                    <div class="p-2 bg-blue-500/10 rounded-xl text-blue-400"><i class="fa-solid fa-ellipsis-vertical"></i></div>
                </div>
                <div class="flex-1 relative w-full h-[300px]">
                    <canvas id="trendChart"></canvas>
                </div>
            </div>

            <!-- Doughnuts Grid (Categorías y Planta) -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <!-- Por Planta -->
                <div class="bg-[#0a0f1d]/90 backdrop-blur-2xl border border-white/10 rounded-[2rem] p-6 shadow-2xl flex flex-col min-h-[400px]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-industry text-purple-500"></i> Distribución Planta
                        </h3>
                    </div>
                    <div class="flex-1 relative w-full h-full min-h-[250px] flex items-center justify-center">
                        <canvas id="plantaChart"></canvas>
                    </div>
                </div>

                <!-- Por Categoría -->
                <div class="bg-[#0a0f1d]/90 backdrop-blur-2xl border border-white/10 rounded-[2rem] p-6 shadow-2xl flex flex-col min-h-[400px]">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-xs font-black text-white uppercase tracking-widest flex items-center gap-2">
                            <i class="fa-solid fa-tags text-rose-500"></i> Tipos de Problema
                        </h3>
                    </div>
                    <div class="flex-1 relative w-full h-full min-h-[250px] flex items-center justify-center">
                        <canvas id="categoryChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabla resumen -->
        <div class="bg-[#0a0f1d]/90 backdrop-blur-2xl border border-white/10 rounded-[2rem] p-8 shadow-2xl">
            <div class="flex items-center justify-between mb-8">
                <h3 class="text-sm font-black text-white uppercase tracking-widest flex items-center gap-2">
                    <i class="fa-solid fa-bolt text-amber-500"></i> Actividad Reciente
                </h3>
                <button wire:click="setTab('tickets')" class="text-[10px] font-black text-blue-400 hover:text-blue-300 uppercase tracking-widest flex items-center gap-1 transition-colors">
                    Ver todos <i class="fa-solid fa-arrow-right"></i>
                </button>
            </div>
            
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-left border-collapse min-w-[600px]">
                    <thead>
                        <tr class="border-b border-white/10">
                            <th class="py-4 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">ID</th>
                            <th class="py-4 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Título / Asunto</th>
                            <th class="py-4 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest">Planta</th>
                            <th class="py-4 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Estado</th>
                            <th class="py-4 px-4 text-[10px] font-black text-gray-500 uppercase tracking-widest text-right">Fecha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($tickets->sortByDesc('created_at')->take(6) as $t)
                        <tr class="border-b border-white/5 hover:bg-white/[0.02] transition-colors group">
                            <td class="py-4 px-4">
                                <span class="text-xs font-black text-gray-400 bg-white/5 px-2 py-1 rounded-md">#{{ str_pad($t->id, 4, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="py-4 px-4 text-sm font-bold text-gray-200 group-hover:text-blue-400 transition-colors">
                                {{ Str::limit(explode(']', str_replace('[', '', $t->titulo))[0] ?? 'N/A', 40) }}
                            </td>
                            <td class="py-4 px-4">
                                <span class="text-[10px] font-black text-purple-300 bg-purple-500/10 px-2 py-1 rounded-md uppercase tracking-wider border border-purple-500/20">Planta {{ $t->planta ?? 1 }}</span>
                            </td>
                            <td class="py-4 px-4 text-center">
                                @if($t->estado_id == 1)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-blue-500/10 text-blue-400 border border-blue-500/20"><span class="w-1.5 h-1.5 bg-blue-500 rounded-full"></span> Abierto</span>
                                @elseif($t->estado_id == 2)
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-amber-500/10 text-amber-400 border border-amber-500/20"><span class="w-1.5 h-1.5 bg-amber-500 rounded-full animate-pulse"></span> Proceso</span>
                                @else
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-emerald-500/10 text-emerald-400 border border-emerald-500/20"><span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Resuelto</span>
                                @endif
                            </td>
                            <td class="py-4 px-4 text-[11px] font-bold text-gray-500 text-right">
                                {{ $t->created_at->format('d/m/Y H:i') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="py-8 text-center text-sm text-gray-500 font-medium">No hay tickets recientes para mostrar.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</template>
HTML;

$content = substr_replace($content, $newHtml, $start, $end - $start);
file_put_contents($file, $content);
echo "Statistics tab updated.\n";
