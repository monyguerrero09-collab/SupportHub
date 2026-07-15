<?php

$bladeContent = <<<'HTML'
            <template x-if="activeTab === 'statistics'">
                 <div class="space-y-6"
                      x-data='{
                          init() {
                              const render = () => {
                                  if (window.renderSupportHubCharts) {
                                      window.renderSupportHubCharts({
                                          categoryData: @json($categoryData),
                                          statusCounts: @json($statusCounts),
                                          plantaCounts: @json($plantaCounts),
                                          trendMonths: @json($trendMonths),
                                          trendData: @json($trendData),
                                          trendClosedData: @json($trendClosedData),
                                          slaPercent: {{ (int)($slaPercent ?? 0) }}
                                      });
                                  }
                              };
                              this.$watch("activeTab", (val) => {
                                  if (val === "statistics") {
                                      this.$nextTick(render);
                                  }
                              });
                              if (this.activeTab === "statistics") {
                                  this.$nextTick(render);
                              }
                              
                              window.addEventListener("ticket-created", () => {
                                  if (this.activeTab === "statistics") this.$nextTick(render);
                              });
                          }
                      }'>

                    <!-- Info/Intro Banner -->
                    <div class="bg-gradient-to-r from-slate-900 via-brand-950 to-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4 shadow-lg shadow-black/20">
                        <div>
                            <h2 class="text-xl font-bold text-white mb-1">¡Bienvenido a tu Centro de Mando Analítico!</h2>
                            <p class="text-sm text-slate-400 max-w-2xl">
                                Este reporte consolida las métricas clave de tus operaciones. Registra incidentes usando el panel lateral y observa las gráficas actualizarse de inmediato.
                            </p>
                        </div>
                    </div>

                    <!-- 4 KPI Cards -->
                    <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                        <!-- Card 1: Total Incidents (Hubo) -->
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group shadow-lg">
                            <div class="absolute -right-3 -bottom-3 text-slate-800 opacity-20 text-7xl font-bold group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-database"></i>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Histórico General</span>
                                    <h3 class="text-3xl font-extrabold text-white mt-1">{{ $stats['total'] ?? 0 }}</h3>
                                </div>
                                <span class="p-2.5 bg-slate-800 text-slate-400 rounded-xl border border-slate-700">
                                    <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                <span class="text-emerald-400 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> 100%</span>
                                acumulado en base
                            </p>
                        </div>

                        <!-- Card 2: Day Incidents (Hay en el día) -->
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group shadow-lg">
                            <div class="absolute -right-3 -bottom-3 text-amber-500 opacity-10 text-7xl font-bold group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-triangle-exclamation"></i>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Registros Hoy</span>
                                    <h3 class="text-3xl font-extrabold text-amber-400 mt-1">{{ $stats['dueToday'] ?? 0 }}</h3>
                                </div>
                                <span class="p-2.5 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20">
                                    <i class="fa-solid fa-bell text-sm animate-pulse"></i>
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                <span class="text-slate-300 font-semibold">{{ $stats['open'] ?? 0 }} abiertos</span>
                                pendientes hoy
                            </p>
                        </div>

                        <!-- Card 3: Resolved (Cuántos se resolvieron) -->
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group shadow-lg">
                            <div class="absolute -right-3 -bottom-3 text-emerald-500 opacity-10 text-7xl font-bold group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-circle-check"></i>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Resueltos Totales</span>
                                    @php
                                        $resolvedTotal = ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0);
                                        $resRate = ($stats['total'] ?? 0) > 0 ? round(($resolvedTotal / $stats['total']) * 100) : 0;
                                    @endphp
                                    <h3 class="text-3xl font-extrabold text-emerald-400 mt-1">{{ $resolvedTotal }}</h3>
                                </div>
                                <span class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
                                    <i class="fa-solid fa-check text-sm"></i>
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                <span class="text-emerald-400 font-semibold">{{ $resRate }}%</span>
                                efectividad general
                            </p>
                        </div>

                        <!-- Card 4: Critical Level (Mayor Concentración) -->
                        <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group shadow-lg">
                            <div class="absolute -right-3 -bottom-3 text-rose-500 opacity-10 text-7xl font-bold group-hover:scale-110 transition-transform">
                                <i class="fa-solid fa-industry"></i>
                            </div>
                            <div class="flex justify-between items-start">
                                <div>
                                    <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Planta Crítica</span>
                                    @php
                                        $p1 = $plantaCounts['Planta 1'] ?? 0;
                                        $p2 = $plantaCounts['Planta 2'] ?? 0;
                                        $maxP = $p1 >= $p2 ? 'Planta 1' : 'Planta 2';
                                        $maxC = max($p1, $p2);
                                    @endphp
                                    <h3 class="text-xl font-bold text-rose-400 mt-2 truncate">{{ $maxC > 0 ? $maxP : 'Sin alertas' }}</h3>
                                </div>
                                <span class="p-2.5 bg-rose-500/10 text-rose-400 rounded-xl border border-rose-500/20">
                                    <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                                </span>
                            </div>
                            <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                {{ $maxC > 0 ? "Concentra {$maxC} incidentes" : "No hay reportes." }}
                            </p>
                        </div>
                    </section>

                    <!-- Filter Bar -->
                    <section class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-wrap gap-4 items-center justify-between shadow-lg">
                        <div class="flex items-center gap-2">
                            <i class="fa-solid fa-filter text-brand-400 text-sm"></i>
                            <span class="text-sm font-semibold text-white">Controles de Filtrado</span>
                        </div>
                        <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                            <select wire:model.live="plantaFilter" class="bg-slate-800 text-slate-200 border border-slate-700 text-xs rounded-xl px-3 py-2 outline-none focus:border-brand-500 transition-all flex-grow sm:flex-grow-0">
                                <option value="">Todas las Plantas</option>
                                <option value="1">Planta 1</option>
                                <option value="2">Planta 2</option>
                            </select>
                            <select wire:model.live="statusFilter" class="bg-slate-800 text-slate-200 border border-slate-700 text-xs rounded-xl px-3 py-2 outline-none focus:border-brand-500 transition-all flex-grow sm:flex-grow-0">
                                <option value="Todos">Todos los Estados</option>
                                <option value="Abierto">Abierto</option>
                                <option value="En Proceso">En Proceso</option>
                                <option value="Resuelto">Resuelto</option>
                            </select>
                            <button wire:click="$set('plantaFilter', ''); $set('statusFilter', 'Todos');" class="text-xs text-slate-400 hover:text-white hover:bg-slate-800 px-3 py-2 rounded-xl transition-all border border-slate-700">
                                Restaurar
                            </button>
                        </div>
                    </section>

                    <!-- Charts Grid -->
                    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        
                        <!-- Left Chart Block: Line/Bar combo -->
                        <div class="lg:col-span-8 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-base font-bold text-white flex items-center gap-2">
                                        <i class="fa-solid fa-chart-line text-brand-400"></i> Tendencia General
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Relación temporal de reportados vs. resueltos</p>
                                </div>
                                <span class="text-[10px] uppercase font-bold tracking-wider text-brand-400 px-2.5 py-1 bg-brand-500/10 border border-brand-500/20 rounded-full">
                                    Últimos 7 Meses
                                </span>
                            </div>
                            <div class="relative h-[320px] w-full flex items-center justify-center">
                                <canvas id="trendChart"></canvas>
                            </div>
                        </div>

                        <!-- Right Chart Block: Donut -->
                        <div class="lg:col-span-4 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-base font-bold text-white flex items-center gap-2">
                                        <i class="fa-solid fa-industry text-amber-400"></i> Distribución
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Tickets por Planta</p>
                                </div>
                            </div>
                            <div class="relative h-[280px] w-full flex items-center justify-center">
                                <canvas id="plantChart"></canvas>
                            </div>
                        </div>

                        <!-- Bottom Left Chart Block: Horizontal Bars -->
                        <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-base font-bold text-white flex items-center gap-2">
                                        <i class="fa-solid fa-users text-purple-400"></i> Áreas Solicitantes
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Departamentos que requieren ayuda</p>
                                </div>
                            </div>
                            <div class="relative h-[280px] w-full">
                                <canvas id="areaChart"></canvas>
                            </div>
                        </div>

                        <!-- Bottom Right Chart Block: Polar Chart -->
                        <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between shadow-lg">
                            <div class="flex items-center justify-between mb-4">
                                <div>
                                    <h4 class="text-base font-bold text-white flex items-center gap-2">
                                        <i class="fa-solid fa-shield-halved text-rose-400"></i> Estado Actual
                                    </h4>
                                    <p class="text-xs text-slate-400 mt-0.5">Clasificación del backlog</p>
                                </div>
                            </div>
                            <div class="relative h-[250px] w-full flex items-center justify-center">
                                <canvas id="stateChart"></canvas>
                            </div>
                            <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                                <div class="bg-slate-950/50 p-2 rounded-xl border border-slate-800/60">
                                    <span class="block text-[10px] text-slate-400 font-medium">Abiertos</span>
                                    <span class="text-sm font-extrabold text-blue-400">{{ $statusCounts[1] ?? 0 }}</span>
                                </div>
                                <div class="bg-slate-950/50 p-2 rounded-xl border border-slate-800/60">
                                    <span class="block text-[10px] text-slate-400 font-medium">En Curso</span>
                                    <span class="text-sm font-extrabold text-amber-400">{{ $statusCounts[2] ?? 0 }}</span>
                                </div>
                                <div class="bg-slate-950/50 p-2 rounded-xl border border-slate-800/60">
                                    <span class="block text-[10px] text-slate-400 font-medium">Resueltos</span>
                                    <span class="text-sm font-extrabold text-emerald-400">{{ $resolvedTotal }}</span>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Simulator & Recent Tickets -->
                    <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                        <!-- Left side: Simulator Form -->
                        <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-slate-900/90 border border-slate-800 rounded-2xl p-6 relative shadow-lg">
                            <div class="absolute right-4 top-4 text-slate-800 text-4xl font-extrabold pointer-events-none opacity-20">
                                <i class="fa-solid fa-gamepad"></i>
                            </div>
                            <h4 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                                <i class="fa-solid fa-square-plus text-brand-400"></i> Generación Masiva (Simulador)
                            </h4>
                            <p class="text-xs text-slate-400 mb-5">Ingresa datos rápidos para crear tickets reales y ver gráficas en vivo.</p>

                            <form wire:submit.prevent="crearTicketSimulador" class="space-y-4">
                                <div>
                                    <label class="block text-xs font-semibold text-slate-300 mb-1">Descripción del Incidente *</label>
                                    <input type="text" wire:model.defer="simDesc" placeholder="Ej: Falla eléctrica" required
                                        class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700 focus:border-brand-500 rounded-xl px-3 py-2.5 text-xs text-white placeholder-slate-500 outline-none transition-all">
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1">Planta *</label>
                                        <select wire:model.defer="simPlant" required class="w-full bg-slate-950 border border-slate-800 focus:border-brand-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                            <option value="1">Planta 1</option>
                                            <option value="2">Planta 2</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1">Área *</label>
                                        <select wire:model.defer="simArea" required class="w-full bg-slate-950 border border-slate-800 focus:border-brand-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                            <option value="Sistemas / TI">Sistemas / TI</option>
                                            <option value="Mantenimiento">Mantenimiento</option>
                                            <option value="Producción">Producción</option>
                                            <option value="Calidad">Calidad</option>
                                            <option value="Logística">Logística</option>
                                            <option value="RRHH">RRHH</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1">Prioridad *</label>
                                        <select wire:model.defer="simPriority" required class="w-full bg-slate-950 border border-slate-800 focus:border-brand-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                            <option value="Baja">Baja</option>
                                            <option value="Media">Media</option>
                                            <option value="Alta">Alta</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-xs font-semibold text-slate-300 mb-1">Estado *</label>
                                        <select wire:model.defer="simStatus" required class="w-full bg-slate-950 border border-slate-800 focus:border-brand-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                            <option value="Abierto">Abierto</option>
                                            <option value="En Proceso">En Proceso</option>
                                            <option value="Resuelto">Resuelto</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="pt-2">
                                    <button type="submit" class="w-full bg-brand-600 hover:bg-brand-500 active:bg-brand-700 text-white font-semibold py-3 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-brand-600/20">
                                        <i class="fa-solid fa-paper-plane"></i> Generar Ticket
                                        <div wire:loading wire:target="crearTicketSimulador" class="w-4 h-4 ml-2 border-2 border-white/30 border-t-white rounded-full animate-spin"></div>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right side: Real-time ticket list -->
                        <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between shadow-lg">
                            <div>
                                <div class="flex items-center justify-between mb-4">
                                    <div>
                                        <h4 class="text-base font-bold text-white flex items-center gap-2">
                                            <i class="fa-solid fa-list-check text-indigo-400"></i> Registro Reciente
                                        </h4>
                                        <p class="text-xs text-slate-400">Últimos tickets creados en la plataforma.</p>
                                    </div>
                                </div>
                                <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950 max-h-[290px] overflow-y-auto">
                                    <table class="w-full text-left text-xs text-slate-300">
                                        <thead class="bg-slate-900 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800 sticky top-0">
                                            <tr>
                                                <th class="px-4 py-3">ID / Descripción</th>
                                                <th class="px-4 py-3">Planta / Área</th>
                                                <th class="px-4 py-3">Estado</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($tickets->take(8) as $t)
                                            <tr class="border-b border-slate-900 hover:bg-slate-900/40 transition-colors">
                                                <td class="px-4 py-3">
                                                    <div class="font-semibold text-white text-xs">T-{{ $t->id }}</div>
                                                    <div class="text-slate-400 text-[11px] truncate max-w-[180px]">{{ $t->descripcion ?? $t->titulo }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    <div class="text-slate-200 text-xs">Planta {{ $t->planta }}</div>
                                                    <div class="text-[10px] text-slate-500">{{ explode(']', str_replace('[', '', $t->titulo))[0] ?? 'N/A' }}</div>
                                                </td>
                                                <td class="px-4 py-3">
                                                    @if($t->estado_id == 1)
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] bg-blue-500/10 text-blue-400 border border-blue-500/20 font-medium"><span class="w-1.5 h-1.5 rounded-full bg-blue-500"></span>Abierto</span>
                                                    @elseif($t->estado_id == 2)
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] bg-amber-500/10 text-amber-400 border border-amber-500/20 font-medium"><span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-ping"></span>En Curso</span>
                                                    @else
                                                        <span class="inline-flex items-center gap-1.5 px-2 py-0.5 rounded-full text-[10px] bg-emerald-500/10 text-emerald-400 border border-emerald-500/20 font-medium"><span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>Resuelto</span>
                                                    @endif
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </section>
                 </div>
            </template>
HTML;

$jsContent = <<<'HTML'
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
                type: 'bar',
                data: {
                    labels: Object.values(data.trendMonths),
                    datasets: [
                        {
                            label: 'Incidencias Totales',
                            data: Object.values(data.trendData),
                            backgroundColor: 'rgba(99, 102, 241, 0.4)',
                            borderColor: '#6366f1',
                            borderWidth: 2,
                            borderRadius: 6,
                            order: 2
                        },
                        {
                            label: 'Incidentes Resueltos',
                            data: Object.values(data.trendClosedData || data.trendData),
                            type: 'line',
                            borderColor: '#10b981',
                            backgroundColor: 'rgba(16, 185, 129, 0.1)',
                            borderWidth: 3,
                            fill: true,
                            tension: 0.3,
                            pointBackgroundColor: '#10b981',
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
                    labels: ['Abiertos', 'En Proceso', 'Resueltos'],
                    datasets: [{
                        data: [data.statusCounts[1] || 0, data.statusCounts[2] || 0, (data.statusCounts[3] || 0) + (data.statusCounts[4] || 0)],
                        backgroundColor: ['rgba(59, 130, 246, 0.55)', 'rgba(245, 158, 11, 0.55)', 'rgba(16, 185, 129, 0.55)'],
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
</script>
HTML;

$targetFile = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$c = file_get_contents($targetFile);

// Remove the old global scripts for renderSupportHubCharts just in case
$c = preg_replace('/<script>\s*window\.renderSupportHubCharts[\s\S]*?<\/script>/', '', $c);

// Replace the entire statistics template
$startMatch = '<template x-if="activeTab === \'statistics\'">';
$startIdx = strpos($c, $startMatch);

if ($startIdx !== false) {
    $endIdx = strpos($c, '</template>', $startIdx) + 11;
    $c = substr_replace($c, $bladeContent, $startIdx, $endIdx - $startIdx);
} else {
    echo "ERROR: Could not find <template x-if=\"activeTab === 'statistics'\">\n";
    exit(1);
}

// Inject the new global JS script right before the closing div
$lastDivIdx = strrpos($c, '</div>');
$c = substr_replace($c, "\n" . $jsContent . "\n</div>", $lastDivIdx, 6);

file_put_contents($targetFile, $c);
echo "Injected robust NEW layout successfully.\n";
