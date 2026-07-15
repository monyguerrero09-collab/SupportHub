<?php
$targetFile = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$c = file_get_contents($targetFile);

// Remove the injected @script block from the bottom
$c = preg_replace('/@script\s*<script>\s*Alpine\.data\(\'statisticsDashboardData\'[\s\S]*?<\/script>\s*@endscript/', '', $c);

// The JS logic to inline
$inlineData = <<<'HTML'
{
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
                const slaVal = {{ (int)($slaPercent ?? 0) }};
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
    }
HTML;

// Find the x-data="statisticsDashboardData()" and replace it with x-data="{ ... inlineData ... }"
$c = str_replace('x-data="statisticsDashboardData()"', "x-data=\"" . htmlspecialchars($inlineData, ENT_QUOTES, 'UTF-8') . "\"", $c);

file_put_contents($targetFile, $c);
echo "Inlined x-data successfully.\n";
