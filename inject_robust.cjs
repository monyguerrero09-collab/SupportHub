const fs = require('fs');

const bladeContent = `
            <template x-if="activeTab === 'statistics'">
                 <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-6"
                      x-data='{
                          init() {
                              const render = () => {
                                  if (window.renderSupportHubCharts) {
                                      window.renderSupportHubCharts({
                                          priorityCounts: @json($priorityCounts),
                                          statusCounts: @json($statusCounts),
                                          plantaCounts: @json($plantaCounts),
                                          trendMonths: @json($trendMonths),
                                          trendData: @json($trendData),
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
                          }
                      }'>

                    <!-- Top 6 cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <!-- Card: ATRASADOS -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Atrasados</p>
                             <p class="text-4xl font-black text-rose-500">{{ str_pad((string)($stats['overdue'] ?? 0), 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: HOY -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Hoy</p>
                             <p class="text-4xl font-black text-amber-500">{{ str_pad((string)($stats['dueToday'] ?? 0), 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: ABIERTOS -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Abiertos</p>
                             <p class="text-4xl font-black text-blue-500">{{ str_pad((string)($stats['open'] ?? 0), 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: EN ESPERA -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">En Espera</p>
                             <p class="text-4xl font-black text-amber-500">{{ str_pad((string)($stats['hold'] ?? 0), 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: SIN ASIGNAR -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Sin Asignar</p>
                             <p class="text-4xl font-black text-purple-400">{{ str_pad((string)($stats['unassigned'] ?? 0), 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: TOTAL -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Total</p>
                             <p class="text-4xl font-black text-white">{{ str_pad((string)($stats['total'] ?? 0), 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>

                    <!-- Second row: 3 charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Priority Chart -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 relative shadow-lg">
                             <h3 class="text-[10px] font-black text-white uppercase tracking-widest border-l-4 border-blue-500 pl-3 mb-6">Tickets Por Prioridad</h3>
                             <div class="h-56 relative flex items-center justify-center">
                                 <canvas id="priorityChart"></canvas>
                                 <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                                     <span class="text-[10px] font-bold text-gray-400 tracking-widest">TOTAL</span>
                                     <span class="text-3xl font-black text-white">{{ $stats['total'] ?? 0 }}</span>
                                 </div>
                             </div>
                        </div>
                        <!-- Status Chart -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 relative shadow-lg">
                             <h3 class="text-[10px] font-black text-white uppercase tracking-widest border-l-4 border-emerald-500 pl-3 mb-6">Tickets Por Estado</h3>
                             <div class="h-56 relative">
                                 <canvas id="stateChart"></canvas>
                             </div>
                        </div>
                        <!-- Plant Chart -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 relative shadow-lg">
                             <h3 class="text-[10px] font-black text-white uppercase tracking-widest border-l-4 border-purple-500 pl-3 mb-6">Tickets Por Planta</h3>
                             <div class="h-56 relative">
                                 <canvas id="plantChart"></canvas>
                             </div>
                        </div>
                    </div>

                    <!-- Third row: 2 charts -->
                    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4">
                        <!-- Monthly Trend -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 relative lg:col-span-2 shadow-lg">
                             <h3 class="text-[10px] font-black text-white uppercase tracking-widest border-l-4 border-blue-500 pl-3 mb-6">Tendencia Mensual (Últimos 7 Meses)</h3>
                             <div class="h-64 relative">
                                 <canvas id="trendChart"></canvas>
                             </div>
                        </div>
                        <!-- SLA Compliance -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 relative flex flex-col items-center shadow-lg">
                             <h3 class="text-[10px] font-black text-white uppercase tracking-widest border-l-4 border-emerald-500 pl-3 self-start w-full">Cumplimiento SLA</h3>
                             <p class="text-[9px] text-gray-500 uppercase tracking-widest mb-6 self-start pl-4 mt-2">Resueltos en < 2 días</p>
                             <div class="h-48 w-48 relative">
                                 <canvas id="slaChart"></canvas>
                                 <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none mt-2">
                                     <span class="text-3xl font-black text-white">{{ (int)($slaPercent ?? 0) }}%</span>
                                     <span class="text-[9px] font-bold text-gray-400 tracking-widest">CUMPLIMIENTO</span>
                                 </div>
                             </div>
                        </div>
                    </div>

                 </div>
            </template>
`;

const jsContent = `
<script>
    window.renderSupportHubCharts = function(data) {
        if (typeof Chart === 'undefined') {
            setTimeout(() => window.renderSupportHubCharts(data), 50);
            return;
        }

        Chart.defaults.color = '#64748b';
        Chart.defaults.font.family = 'Inter, sans-serif';

        const destroyChart = (id) => {
            const el = document.getElementById(id);
            if (el && el.__chartInstance) {
                el.__chartInstance.destroy();
            }
        };

        // Priority Doughnut Chart
        destroyChart('priorityChart');
        const ctxPriority = document.getElementById('priorityChart');
        if (ctxPriority) {
            ctxPriority.__chartInstance = new Chart(ctxPriority, {
                type: 'doughnut',
                data: {
                    labels: ['Alta', 'Media', 'Baja'],
                    datasets: [{
                        data: [data.priorityCounts[3] || 0, data.priorityCounts[2] || 0, data.priorityCounts[1] || 0],
                        backgroundColor: ['#ef4444', '#f59e0b', '#3b82f6'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, boxWidth: 6, font: { size: 10 } } } }
                }
            });
        }

        // Status Horizontal Bar Chart
        destroyChart('stateChart');
        const ctxState = document.getElementById('stateChart');
        if (ctxState) {
            ctxState.__chartInstance = new Chart(ctxState, {
                type: 'bar',
                data: {
                    labels: ['Abierto', 'En Proceso', 'Resuelto', 'Cerrado'],
                    datasets: [{
                        data: [data.statusCounts['Abierto'] || 0, data.statusCounts['En Proceso'] || 0, data.statusCounts['Resuelto'] || 0, data.statusCounts['Cerrado'] || 0],
                        backgroundColor: '#3b82f6',
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#ffffff10' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // Plant Horizontal Bar Chart
        destroyChart('plantChart');
        const ctxPlant = document.getElementById('plantChart');
        if (ctxPlant) {
            ctxPlant.__chartInstance = new Chart(ctxPlant, {
                type: 'bar',
                data: {
                    labels: ['Planta 1', 'Planta 2'],
                    datasets: [{
                        data: [data.plantaCounts['Planta 1'] || 0, data.plantaCounts['Planta 2'] || 0],
                        backgroundColor: '#8b5cf6',
                        borderRadius: 4,
                        barPercentage: 0.6
                    }]
                },
                options: {
                    indexAxis: 'y',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { color: '#ffffff10' } },
                        y: { grid: { display: false } }
                    }
                }
            });
        }

        // Monthly Trend Line Chart
        destroyChart('trendChart');
        const ctxTrend = document.getElementById('trendChart');
        if (ctxTrend) {
            ctxTrend.__chartInstance = new Chart(ctxTrend, {
                type: 'line',
                data: {
                    labels: data.trendMonths,
                    datasets: [{
                        label: 'Tickets',
                        data: data.trendData,
                        borderColor: '#3b82f6',
                        backgroundColor: 'rgba(59, 130, 246, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointBackgroundColor: '#3b82f6',
                        pointRadius: 4
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } },
                    scales: {
                        x: { grid: { display: false } },
                        y: { grid: { color: '#ffffff10' } }
                    }
                }
            });
        }

        // SLA Doughnut Chart
        destroyChart('slaChart');
        const ctxSla = document.getElementById('slaChart');
        if (ctxSla) {
            const slaVal = data.slaPercent;
            ctxSla.__chartInstance = new Chart(ctxSla, {
                type: 'doughnut',
                data: {
                    labels: ['Cumplimiento', 'Fuera de SLA'],
                    datasets: [{
                        data: [slaVal, 100 - Math.min(slaVal, 100)],
                        backgroundColor: ['#10b981', '#ffffff05'],
                        borderWidth: 0,
                        cutout: '75%'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: { legend: { display: false } }
                }
            });
        }
    };
</script>
`;

const targetFile = 'c:\\\\Users\\\\monyg\\\\OneDrive\\\\Documentos\\\\ticket_plataforma\\\\resources\\\\views\\\\livewire\\\\support-hub.blade.php';
let c = fs.readFileSync(targetFile, 'utf8');

// Replace the entire statistics template
const startMatch = '<template x-if="activeTab === \\'statistics\\'">';
const startIdx = c.indexOf(startMatch);

if (startIdx === -1) {
    console.error("ERROR: Could not find <template x-if=\\"activeTab === 'statistics'\\">");
    process.exit(1);
}

const endIdx = c.indexOf('</template>', startIdx) + 11;

c = c.substring(0, startIdx) + bladeContent + c.substring(endIdx);

// Inject the new global JS script right before the closing div
const lastDivIdx = c.lastIndexOf('</div>');
c = c.substring(0, lastDivIdx) + '\\n' + jsContent + '\\n' + c.substring(lastDivIdx);

fs.writeFileSync(targetFile, c);
console.log("Injected robust layout successfully.");
