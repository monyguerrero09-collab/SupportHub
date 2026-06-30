<div class="antialiased min-h-screen font-sans" x-data="transitionOverlay()" x-init="initOverlay()">
    
    <template x-if="show">
        <div class="fixed inset-0 z-[9999] flex flex-col items-center justify-center bg-[#05060b] overflow-hidden transition-opacity duration-1000"
             :class="showing ? 'opacity-100' : 'opacity-0 pointer-events-none'">
            <div class="glow-background"></div>
            <div x-ref="particleContainer" class="absolute inset-0 z-0"></div>
            <div class="relative z-10 text-center space-y-6">
                 <div class="inline-flex items-center justify-center p-1 rounded-full bg-pink-500/10 border border-pink-500/20 mb-4">
                    <div class="px-4 py-1 text-xs font-bold tracking-[0.2em] text-pink-400 uppercase animate-pulse">Cargando Módulo</div>
                 </div>
                 <h2 class="text-5xl md:text-7xl font-black tracking-tighter text-white drop-shadow-[0_0_15px_rgba(255,255,255,0.3)]">
                     SUPPORT<span class="text-pink-500">ANALYTICS</span>
                 </h2>
            </div>
        </div>
    </template>

    @if(!$hasAccess)
        <div class="flex flex-col items-center justify-center min-h-screen space-y-4 bg-[#441857]">
            <div class="p-4 rounded-full bg-white/10 backdrop-blur-md border border-white/20">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m0 0v2m0-2h2m-2 0H10m11-3V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2zm-10 0V7a3 3 0 016 0v4" /></svg>
            </div>
            <p class="text-white/70 font-medium">Acceso restringido a estadísticas avanzadas.</p>
        </div>
    @else
        <div class="min-h-screen w-full bg-gradient-to-br from-[#2d0b3a] via-[#6d165f] to-[#b01a65] p-4 md:p-8 text-white">
            
            <div class="max-w-[1600px] mx-auto space-y-6">
                
                <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">
                    <div class="xl:col-span-3 bg-white/10 backdrop-blur-xl rounded-2xl border border-white/20 p-6 flex items-center shadow-2xl">
                        <h1 class="text-3xl font-black tracking-tight text-white drop-shadow-lg leading-tight">Support<br><span class="text-pink-400">Intelligence</span></h1>
                    </div>
                    
                    <div class="xl:col-span-5 bg-black/20 backdrop-blur-md rounded-2xl border border-white/10 p-2 flex gap-2 items-center">
                        @foreach(['Qtr 1', 'Qtr 2', 'Qtr 3', 'Qtr 4'] as $qtr)
                            <button class="flex-1 py-3 rounded-xl font-bold transition-all {{ $loop->first ? 'bg-pink-600 shadow-lg' : 'hover:bg-white/10' }}">
                                {{ $qtr }}
                            </button>
                        @endforeach
                    </div>
                    
                    <div class="xl:col-span-4 flex gap-4">
                        @php
                            $kpis = [
                                ['label' => 'Total Casos', 'val' => $totalTickets, 'color' => 'bg-blue-500/20'],
                                ['label' => 'Resueltos', 'val' => $totalResueltos, 'color' => 'bg-emerald-500/20'],
                                ['label' => 'Tasa Éxito', 'val' => ($totalTickets > 0 ? round(($totalResueltos / $totalTickets) * 100, 1) : 0) . '%', 'color' => 'bg-pink-500/20']
                            ];
                        @endphp
                        @foreach($kpis as $kpi)
                            <div class="{{ $kpi['color'] }} backdrop-blur-md rounded-2xl border border-white/10 p-4 flex-1 flex flex-col items-center justify-center transition-transform hover:scale-105">
                                <span class="text-2xl font-black">{{ $kpi['val'] }}</span>
                                <span class="text-[9px] font-bold uppercase tracking-widest text-white/60 mt-1 text-center">{{ $kpi['label'] }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @php
                        $charts = [
                            ['id' => 'chart1', 'title' => 'Frecuencia de Casos (Top)'],
                            ['id' => 'chart2', 'title' => 'Distribución por Estado'],
                            ['id' => 'chart3', 'title' => 'Incidentes Menos Comunes'],
                            ['id' => 'chart4', 'title' => 'Análisis de Categorías'],
                            ['id' => 'chart5', 'title' => 'Actividad Semanal'],
                            ['id' => 'chart6', 'title' => 'Rendimiento Mensual']
                        ];
                    @endphp

                    @foreach($charts as $chart)
                        <div class="bg-black/20 backdrop-blur-md rounded-3xl border border-white/10 p-6 shadow-2xl flex flex-col group hover:border-pink-500/50 transition-colors">
                            <h2 class="text-xs font-bold text-pink-300 uppercase tracking-widest mb-6 text-center drop-shadow-sm">{{ $chart['title'] }}</h2>
                            <div class="relative w-full h-[260px]">
                                <canvas id="{{ $chart['id'] }}"></canvas>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>

        <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
        
        <script>
            // Lógica del Overlay de Carga
            document.addEventListener('alpine:init', () => {
                Alpine.data('transitionOverlay', () => ({
                    show: true, showing: true,
                    initOverlay() {
                        this.$nextTick(() => {
                            if(!this.$refs.particleContainer) return;
                            for (let i = 0; i < 40; i++) {
                                const div = document.createElement('div');
                                div.classList.add('transition-particle');
                                Object.assign(div.style, {
                                    left: `${Math.random() * 100}vw`,
                                    top: `${Math.random() * 100}vh`,
                                    width: `${Math.random() * 2 + 1}px`,
                                    height: `${Math.random() * 2 + 1}px`,
                                    animation: `floatParticle ${Math.random() * 3 + 2}s infinite ${Math.random() * 2}s`
                                });
                                this.$refs.particleContainer.appendChild(div);
                            }
                        });
                        setTimeout(() => { 
                            this.showing = false; 
                            setTimeout(() => this.show = false, 1000); 
                        }, 2200); 
                    }
                }));
            });

            // Configuración de Gráficas Profesionales
            document.addEventListener('livewire:initialized', () => {
                Chart.defaults.color = 'rgba(255, 255, 255, 0.9)';
                Chart.defaults.font.family = "'Inter', system-ui, sans-serif";
                Chart.defaults.font.weight = '600';
                
                const gridCfg = {
                    color: 'rgba(255, 255, 255, 0.1)',
                    borderDash: [5, 5],
                    drawBorder: false
                };

                const colors = {
                    cyan: '#00f2ff',
                    pink: '#ff007f',
                    yellow: '#f9ff00',
                    purple: '#bc00ff',
                    emerald: '#00ffa3'
                };

                // Chart 1: Horizontal Bar
                new Chart(document.getElementById('chart1'), {
                    type: 'bar',
                    data: {
                        labels: @json($frecuentesLabels),
                        datasets: [{ data: @json($frecuentesData), backgroundColor: colors.cyan, borderRadius: 4 }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { grid: gridCfg }, y: { grid: { display: false } } }
                    }
                });

                // Chart 2: Doughnut
                new Chart(document.getElementById('chart2'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($estadosLabels),
                        datasets: [{ 
                            data: @json($estadosData), 
                            backgroundColor: [colors.purple, colors.pink, colors.cyan, colors.emerald],
                            borderWidth: 0,
                            hoverOffset: 20
                        }]
                    },
                    options: {
                        cutout: '75%', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } } }
                    }
                });

                // Chart 3: Horizontal Bar (Minor)
                new Chart(document.getElementById('chart3'), {
                    type: 'bar',
                    data: {
                        labels: @json($menosFrecuentesLabels),
                        datasets: [{ data: @json($menosFrecuentesData), backgroundColor: colors.pink, borderRadius: 4 }]
                    },
                    options: {
                        indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { grid: gridCfg }, y: { grid: { display: false } } }
                    }
                });

                // Chart 4: Doughnut Category
                new Chart(document.getElementById('chart4'), {
                    type: 'doughnut',
                    data: {
                        labels: @json($frecuentesLabels).slice(0,3),
                        datasets: [{ 
                            data: @json($frecuentesData).slice(0,3), 
                            backgroundColor: [colors.yellow, colors.purple, colors.cyan],
                            borderWidth: 0
                        }]
                    },
                    options: { cutout: '70%', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false } } }
                });

                // Chart 5: Weekly Bar
                new Chart(document.getElementById('chart5'), {
                    type: 'bar',
                    data: {
                        labels: @json($semanalLabels),
                        datasets: [{ data: @json($semanalData), backgroundColor: colors.emerald, borderRadius: 4 }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { grid: { display: false } }, y: { grid: gridCfg } }
                    }
                });

                // Chart 6: Monthly Line
                new Chart(document.getElementById('chart6'), {
                    type: 'line',
                    data: {
                        labels: @json($mensualLabels),
                        datasets: [{ 
                            data: @json($mensualData), 
                            borderColor: colors.yellow, 
                            borderWidth: 3, 
                            tension: 0.4,
                            pointBackgroundColor: colors.yellow,
                            pointRadius: 4,
                            fill: false
                        }]
                    },
                    options: {
                        responsive: true, maintainAspectRatio: false,
                        plugins: { legend: { display: false } },
                        scales: { x: { grid: { display: false } }, y: { grid: gridCfg } }
                    }
                });
            });
        </script>

        <style>
            @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap');
            
            .glow-background {
                position: absolute; width: 100%; height: 100%;
                background: radial-gradient(circle at center, rgba(236, 30, 111, 0.2) 0%, transparent 70%);
                animation: pulse 4s ease-in-out infinite;
            }
            @keyframes pulse { 0%, 100% { opacity: 0.5; transform: scale(1); } 50% { opacity: 1; transform: scale(1.1); } }
            @keyframes floatParticle { 0% { opacity: 0; transform: translateY(0); } 50% { opacity: 1; } 100% { opacity: 0; transform: translateY(-100px); } }
            .transition-particle { position: absolute; background: #ec1e6f; border-radius: 50%; pointer-events: none; z-index: 1; }
            canvas { filter: drop-shadow(0 0 5px rgba(255,255,255,0.05)); }
        </style>
    @endif
</div>
