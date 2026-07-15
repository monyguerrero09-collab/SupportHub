const fs = require('fs');

const bladeContent = `
            <template x-if="activeTab === 'statistics'">
                 <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-6"
                      x-data="statisticsDashboardData()">

                    <!-- Top 6 cards -->
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-6 gap-4">
                        <!-- Card: ATRASADOS -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Atrasados</p>
                             <p class="text-4xl font-black text-rose-500">{{ str_pad($stats['overdue'], 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: HOY -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Hoy</p>
                             <p class="text-4xl font-black text-amber-500">{{ str_pad($stats['dueToday'], 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: ABIERTOS -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Abiertos</p>
                             <p class="text-4xl font-black text-blue-500">{{ str_pad($stats['open'], 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: EN ESPERA -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">En Espera</p>
                             <p class="text-4xl font-black text-amber-500">{{ str_pad($stats['hold'], 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: SIN ASIGNAR -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Sin Asignar</p>
                             <p class="text-4xl font-black text-purple-400">{{ str_pad($stats['unassigned'], 2, '0', STR_PAD_LEFT) }}</p>
                        </div>
                        <!-- Card: TOTAL -->
                        <div class="bg-[#11112b]/80 border border-white/5 rounded-2xl p-6 flex flex-col items-center justify-center relative overflow-hidden shadow-lg hover:border-white/10 transition-colors">
                             <p class="text-[10px] font-black text-gray-500 uppercase tracking-widest mb-3">Total</p>
                             <p class="text-4xl font-black text-white">{{ str_pad($stats['total'], 2, '0', STR_PAD_LEFT) }}</p>
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
                                     <span class="text-3xl font-black text-white">{{ $stats['total'] }}</span>
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
                                     <span class="text-3xl font-black text-white">{{ (int)$slaPercent }}%</span>
                                     <span class="text-[9px] font-bold text-gray-400 tracking-widest">CUMPLIMIENTO</span>
                                 </div>
                             </div>
                        </div>
                    </div>

                 </div>
            </template>
`;

const jsContent = `
@script
<script>
    Alpine.data('statisticsDashboardData', () => ({
        init() {
            this.$nextTick(() => {
                this.initCharts();
            });
            this.$watch('activeTab', (val) => {
                if (val === 'statistics') {
                    this.$nextTick(() => {
                        this.initCharts();
                    });
                }
            });
        },
        initCharts() {
            if (typeof Chart === 'undefined') {
                setTimeout(() => this.initCharts(), 50);
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
                const pChart = new Chart(ctxPriority, {
                    type: 'doughnut',
                    data: {
                        labels: ['Alta', 'Media', 'Baja'],
                        datasets: [{
                            data: [{{ (int)($priorityCounts[3] ?? 0) }}, {{ (int)($priorityCounts[2] ?? 0) }}, {{ (int)($priorityCounts[1] ?? 0) }}],
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
                ctxPriority.__chartInstance = pChart;
            }

            // Status Horizontal Bar Chart
            destroyChart('stateChart');
            const ctxState = document.getElementById('stateChart');
            if (ctxState) {
                const sChart = new Chart(ctxState, {
                    type: 'bar',
                    data: {
                        labels: ['Abierto', 'En Proceso', 'Resuelto', 'Cerrado'],
                        datasets: [{
                            data: [{{ (int)($statusCounts['Abierto'] ?? 0) }}, {{ (int)($statusCounts['En Proceso'] ?? 0) }}, {{ (int)($statusCounts['Resuelto'] ?? 0) }}, {{ (int)($statusCounts['Cerrado'] ?? 0) }}],
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
                ctxState.__chartInstance = sChart;
            }

            // Plant Horizontal Bar Chart
            destroyChart('plantChart');
            const ctxPlant = document.getElementById('plantChart');
            if (ctxPlant) {
                const plChart = new Chart(ctxPlant, {
                    type: 'bar',
                    data: {
                        labels: ['Planta 1', 'Planta 2'],
                        datasets: [{
                            data: [{{ (int)($plantaCounts['Planta 1'] ?? 0) }}, {{ (int)($plantaCounts['Planta 2'] ?? 0) }}],
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
                ctxPlant.__chartInstance = plChart;
            }

            // Monthly Trend Line Chart
            destroyChart('trendChart');
            const ctxTrend = document.getElementById('trendChart');
            if (ctxTrend) {
                const tChart = new Chart(ctxTrend, {
                    type: 'line',
                    data: {
                        labels: {!! json_encode($trendMonths) !!},
                        datasets: [{
                            label: 'Tickets',
                            data: {!! json_encode($trendData) !!},
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
                ctxTrend.__chartInstance = tChart;
            }

            // SLA Doughnut Chart
            destroyChart('slaChart');
            const ctxSla = document.getElementById('slaChart');
            if (ctxSla) {
                const slaVal = {{ (int)$slaPercent }};
                const slaChart = new Chart(ctxSla, {
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
                ctxSla.__chartInstance = slaChart;
            }
        }
    }));
</script>
@endscript
`;

const targetFile = 'c:\\\\Users\\\\monyg\\\\OneDrive\\\\Documentos\\\\ticket_plataforma\\\\resources\\\\views\\\\livewire\\\\support-hub.blade.php';
let c = fs.readFileSync(targetFile, 'utf8');

// Replace the statistics template
const startIdx = c.indexOf('<template x-if="activeTab === \\'statistics\\'">');
const endIdx = c.indexOf('</template>', startIdx) + 11;
c = c.substring(0, startIdx) + bladeContent + c.substring(endIdx);

// Remove old global scripts related to statistics
c = c.replace(/<script>\\s*document\\.addEventListener\\('alpine:init', \\(\\) => \\{[\\s\\S]*?<\\/script>/, '');
c = c.replace(/@script\\s*<script>\\s*Alpine\\.data\\('statisticsDashboardData'[\\s\\S]*?<\\/script>\\s*@endscript/, '');

// Inject the new JS script right before the closing div
const lastDivIdx = c.lastIndexOf('</div>');
c = c.substring(0, lastDivIdx) + jsContent + '\\n' + c.substring(lastDivIdx);

fs.writeFileSync(targetFile, c);
console.log('Injected screenshot-perfect layout successfully.');
