<template x-if="activeTab === 'statistics'">
                 <div class="animate-in fade-in slide-in-from-bottom-5 duration-700 space-y-6"
                      x-data="{
                           tickets: @php echo json_encode($allTicketsForJs, 15, 512) @endphp,
                           filterPlant: 'all',
                           filterPriority: 'all',
                           
                           // Reactive KPI Metrics
                           totalCount: 0,
                           todayCount: 0,
                           todayActiveCount: 0,
                           resolvedCount: 0,
                           resolvedPct: '0%',
                           critPlantName: 'Sin alertas',
                           critPlantDesc: 'No hay reportes asignados en los filtros.',
                           
                           // State counts
                           openCount: 0,
                           processCount: 0,
                           resCount: 0,
                           
                           // Area Winner
                           maxAreaName: '-',
                           maxAreaVal: 0,
                           
                           // Plant legend array for loop
                           plantLegend: [],

                          // Simulator Form Fields
                          simDesc: '',
                          simPlant: 'Planta 1',
                          simArea: 'Sistemas / TI',
                          simPriority: 'Media',
                          simStatus: 'Abierto',

                          init() {
                              this.$nextTick(() => {
                                  this.initCharts();
                                  this.renderDashboard();
                              });
                              
                              this.$watch('activeTab', (val) => {
                                  if (val === 'statistics') {
                                      this.$nextTick(() => {
                                          this.initCharts();
                                          this.renderDashboard();
                                      });
                                  }
                              });
                          },

                          // Chart Instances
                          trendChartInstance: null,
                          plantChartInstance: null,
                          areaChartInstance: null,
                          stateChartInstance: null,

                          initCharts() {
                              if (typeof Chart === 'undefined' || !window.Chart) {
                                  setTimeout(() => this.initCharts(), 50);
                                  return;
                              }

                              ['#trendChart', '#plantChart', '#areaChart', '#stateChart'].forEach(id => {
                                  var el = document.querySelector(id);
                                  if (el && el.__chartInstance) {
                                      try { el.__chartInstance.destroy(); } catch(e) {}
                                  }
                              });

                              Chart.defaults.color = '#94a3b8';
                              Chart.defaults.font.family = 'Inter, sans-serif';
                              Chart.defaults.plugins.legend.labels.color = '#e2e8f0';

                              // 1. CHART TENDENCIA (Línea / Barra Mixta)
                              const ctxTrend = document.getElementById('trendChart');
                              if (ctxTrend) {
                                  this.trendChartInstance = new Chart(ctxTrend.getContext('2d'), {
                                      type: 'bar',
                                      data: {
                                          labels: [],
                                          datasets: [
                                              {
                                                  label: 'Incidencias Totales',
                                                  data: [],
                                                  backgroundColor: 'rgba(99, 102, 241, 0.4)',
                                                  borderColor: '#6366f1',
                                                  borderWidth: 2,
                                                  borderRadius: 6,
                                                  order: 2
                                              },
                                              {
                                                  label: 'Incidentes Resueltos',
                                                  data: [],
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
                                              y: {
                                                  grid: { color: 'rgba(255,255,255,0.05)' },
                                                  ticks: { stepSize: 1, color: '#94a3b8' }
                                              },
                                              x: {
                                                  grid: { display: false },
                                                  ticks: { color: '#94a3b8' }
                                              }
                                          }
                                      }
                                  });
                                  ctxTrend.__chartInstance = this.trendChartInstance;
                              }

                              // 2. CHART PLANTAS (Dona)
                              const ctxPlant = document.getElementById('plantChart');
                              if (ctxPlant) {
                                  this.plantChartInstance = new Chart(ctxPlant.getContext('2d'), {
                                      type: 'doughnut',
                                      data: {
                                          labels: [],
                                          datasets: [{
                                              data: [],
                                              backgroundColor: ['#6366f1', '#f59e0b', '#ec4899', '#06b6d4'],
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
                                  ctxPlant.__chartInstance = this.plantChartInstance;
                              }

                              // 3. CHART ÁREAS (Barras Horizontales)
                              const ctxArea = document.getElementById('areaChart');
                              if (ctxArea) {
                                  this.areaChartInstance = new Chart(ctxArea.getContext('2d'), {
                                      type: 'bar',
                                      data: {
                                          labels: [],
                                          datasets: [{
                                              label: 'Número de Solicitudes',
                                              data: [],
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
                                          plugins: {
                                              legend: { display: false },
                                              tooltip: { backgroundColor: '#1e293b' }
                                          },
                                          scales: {
                                              x: {
                                                  grid: { color: 'rgba(255,255,255,0.05)' },
                                                  ticks: { precision: 0, color: '#94a3b8' }
                                              },
                                              y: {
                                                  grid: { display: false },
                                                  ticks: { color: '#94a3b8', font: { size: 10 } }
                                              }
                                          }
                                      }
                                  });
                                  ctxArea.__chartInstance = this.areaChartInstance;
                              }

                              // 4. CHART ESTADOS (Polar Area)
                              const ctxState = document.getElementById('stateChart');
                              if (ctxState) {
                                  this.stateChartInstance = new Chart(ctxState.getContext('2d'), {
                                      type: 'polarArea',
                                      data: {
                                          labels: ['Abiertos', 'En Proceso', 'Resueltos'],
                                          datasets: [{
                                              data: [0, 0, 0],
                                              backgroundColor: [
                                                  'rgba(59, 130, 246, 0.55)',
                                                  'rgba(245, 158, 11, 0.55)',
                                                  'rgba(16, 185, 129, 0.55)'
                                              ],
                                              borderColor: '#0f172a',
                                              borderWidth: 2
                                          }]
                                      },
                                      options: {
                                          responsive: true,
                                          maintainAspectRatio: false,
                                          scales: {
                                              r: {
                                                  grid: { color: 'rgba(255,255,255,0.05)' },
                                                  ticks: { backdropColor: 'transparent', color: '#94a3b8' }
                                              }
                                          },
                                          plugins: {
                                              legend: { display: false },
                                              tooltip: { backgroundColor: '#1e293b' }
                                          }
                                      }
                                  });
                                  ctxState.__chartInstance = this.stateChartInstance;
                              }
                          },

                          getFilteredTickets() {
                              return this.tickets.filter(t => {
                                  const matchPlant = (this.filterPlant === 'all' || t.plant === this.filterPlant);
                                  const matchPriority = (this.filterPriority === 'all' || t.priority === this.filterPriority);
                                  return matchPlant && matchPriority;
                              });
                          },

                          applyFilters() {
                              this.renderDashboard();
                          },

                          clearFilters() {
                              this.filterPlant = 'all';
                              this.filterPriority = 'all';
                              this.renderDashboard();
                          },

                          renderDashboard() {
                              if (typeof Chart === 'undefined' || !window.Chart) {
                                  setTimeout(() => this.renderDashboard(), 50);
                                  return;
                              }

                              const filtered = this.getFilteredTickets();

                              // 1. KPI Cards
                              this.totalCount = filtered.length;
                              
                              // Get counts for today (latest ticket date)
                              let latestDate = '2026-07-14';
                              if (this.tickets.length > 0) {
                                  const dates = this.tickets.map(t => t.date);
                                  dates.sort();
                                  latestDate = dates[dates.length - 1];
                              }
                              
                              const todayIncidents = filtered.filter(t => t.date === latestDate);
                              this.todayCount = todayIncidents.length;
                              this.todayActiveCount = todayIncidents.filter(t => t.status !== 'Resuelto').length;

                              this.resolvedCount = filtered.filter(t => t.status === 'Resuelto').length;
                              this.resolvedPct = this.totalCount > 0 ? Math.round((this.resolvedCount / this.totalCount) * 100) + '%' : '0%';

                              // 2. Critical Plant
                              const plantCounts = {};
                              filtered.forEach(t => {
                                  plantCounts[t.plant] = (plantCounts[t.plant] || 0) + 1;
                              });

                              let maxPlant = '-';
                              let maxPlantCount = 0;
                              for (const [plant, val] of Object.entries(plantCounts)) {
                                  if (val > maxPlantCount) {
                                      maxPlantCount = val;
                                      maxPlant = plant;
                                  }
                              }
                              const cleanPlantName = maxPlant.split(' (')[0];
                              this.critPlantName = maxPlantCount > 0 ? cleanPlantName : 'Sin alertas';
                              this.critPlantDesc = maxPlantCount > 0 
                                  ? `Concentra <strong class='text-rose-400 font-bold'>${maxPlantCount}</strong> incidentes (${Math.round((maxPlantCount / (this.totalCount || 1)) * 100)}%)`
                                  : 'No hay reportes asignados en los filtros.';

                              // A. Trend Chart (Last 5 dates)
                              if (this.trendChartInstance) {
                                  const uniqueDates = Array.from(new Set(this.tickets.map(t => t.date))).sort();
                                  const latest5Dates = uniqueDates.slice(-5);
                                  
                                  const daysLabels = latest5Dates.map(d => {
                                      const parts = d.split('-');
                                      const monthNames = ['Ene', 'Feb', 'Mar', 'Abr', 'May', 'Jun', 'Jul', 'Ago', 'Sep', 'Oct', 'Nov', 'Dic'];
                                      const mName = monthNames[parseInt(parts[1]) - 1] || 'Jul';
                                      return d === latestDate ? `Hoy (${mName} ${parts[2]})` : `${mName} ${parts[2]}`;
                                  });

                                  const countsTotalByDay = latest5Dates.map(d => filtered.filter(t => t.date === d).length);
                                  const countsResolvedByDay = latest5Dates.map(d => filtered.filter(t => t.date === d && t.status === 'Resuelto').length);

                                  this.trendChartInstance.data.labels = daysLabels;
                                  this.trendChartInstance.data.datasets[0].data = countsTotalByDay;
                                  this.trendChartInstance.data.datasets[1].data = countsResolvedByDay;
                                  this.trendChartInstance.update();
                              }

                              // B. Plant Chart
                              if (this.plantChartInstance) {
                                  const plantLabels = ['Planta 1', 'Planta 2'];
                                  const plantsMappingKeys = ['Planta 1', 'Planta 2'];
                                  const countsByPlant = plantsMappingKeys.map(k => filtered.filter(t => t.plant === k).length);

                                  this.plantChartInstance.data.labels = plantLabels;
                                  this.plantChartInstance.data.datasets[0].data = countsByPlant;
                                  this.plantChartInstance.update();

                                  this.plantLegend = plantLabels.map((label, i) => {
                                      const count = countsByPlant[i];
                                      const pct = this.totalCount > 0 ? Math.round((count / this.totalCount) * 100) : 0;
                                      return {
                                          label: label,
                                          count: count,
                                          pct: pct,
                                          color: this.plantChartInstance.data.datasets[0].backgroundColor[i]
                                      };
                                  });
                              }

                              // C. Area Chart
                              if (this.areaChartInstance) {
                                  const areaKeys = ['Sistemas / TI', 'Mantenimiento', 'Producción', 'Calidad', 'Logística & Embarques', 'Recursos Humanos'];
                                  const countsByArea = areaKeys.map(a => filtered.filter(t => t.area === a).length);

                                  this.areaChartInstance.data.labels = areaKeys;
                                  this.areaChartInstance.data.datasets[0].data = countsByArea;
                                  this.areaChartInstance.update();

                                  let maxArea = '-';
                                  let maxAreaVal = 0;
                                  areaKeys.forEach((area, idx) => {
                                      if(countsByArea[idx] > maxAreaVal) {
                                          maxAreaVal = countsByArea[idx];
                                          maxArea = area;
                                      }
                                  });
                                  this.maxAreaName = maxArea;
                                  this.maxAreaVal = maxAreaVal;
                              }

                              // D. Polar State Chart
                              if (this.stateChartInstance) {
                                  this.openCount = filtered.filter(t => t.status === 'Abierto').length;
                                  this.processCount = filtered.filter(t => t.status === 'En Proceso').length;
                                  this.resCount = filtered.filter(t => t.status === 'Resuelto').length;

                                  this.stateChartInstance.data.datasets[0].data = [this.openCount, this.processCount, this.resCount];
                                  this.stateChartInstance.update();
                              }
                          },

                          changeStatus(id, newStatus) {
                              const ticket = this.tickets.find(x => x.id === id);
                              if (ticket) {
                                  ticket.status = newStatus;
                                  this.renderDashboard();
                                  this.showToast('Actualizado', `El estado del ticket ${id} se actualizó a ${newStatus}.`, 'success');
                                  $wire.resolveTicketFromStats(id);
                              }
                          },

                          resetData() {
                              $wire.$refresh().then(() => {
                                  this.showToast('Restaurado', 'Los datos reales del servidor han sido refrescados.', 'info');
                              });
                          },

                          handleNewTicket(e) {
                              e.preventDefault();
                              
                              const nextIdNum = this.tickets.length > 0
                                  ? Math.max(...this.tickets.map(t => parseInt(t.id.replace('T-', '')))) + 1
                                  : 101;
                              const simulatedId = 'T-' + nextIdNum;
                              const todayStr = new Date().toISOString().split('T')[0];

                              const newTicket = {
                                  id: simulatedId,
                                  desc: this.simDesc,
                                  plant: this.simPlant,
                                  area: this.simArea,
                                  priority: this.simPriority,
                                  status: this.simStatus,
                                  date: todayStr
                              };

                              this.tickets.push(newTicket);
                              this.renderDashboard();
                              this.showToast('Creado', `Ticket ${simulatedId} agregado de forma reactiva en gráficos.`, 'success');

                              $wire.createTicketFromStats({
                                  desc: this.simDesc,
                                  plant: this.simPlant,
                                  area: this.simArea,
                                  priority: this.simPriority,
                                  status: this.simStatus
                              });

                              this.simDesc = '';
                          },

                          showToast(title, desc, type) {
                              const toast = document.getElementById('toast');
                              if (toast) {
                                  document.getElementById('toast-title').innerText = title;
                                  document.getElementById('toast-desc').innerText = desc;
                                  
                                  toast.className = 'fixed bottom-5 right-5 transform transition-all duration-300 bg-slate-900 border text-slate-100 px-4 py-3 rounded-xl shadow-2xl z-[9999] flex items-center gap-3 ' + 
                                      (type === 'success' ? 'border-emerald-500/30' : (type === 'info' ? 'border-blue-500/30' : 'border-amber-500/30'));
                                  
                                  toast.classList.remove('translate-y-24', 'opacity-0');
                                  toast.classList.add('translate-y-0', 'opacity-100');

                                  setTimeout(() => {
                                      toast.classList.remove('translate-y-0', 'opacity-100');
                                      toast.classList.add('translate-y-24', 'opacity-0');
                                  }, 3500);
                              }
                          }
                      }">

                      <!-- Info/Intro Banner -->
                      <div class="bg-gradient-to-r from-slate-900 via-blue-950/20 to-slate-900 border border-slate-800/80 rounded-2xl p-6 flex flex-col md:flex-row md:items-center justify-between gap-4">
                          <div>
                              <h2 class="text-xl font-bold text-white mb-1">¡Bienvenido a tu Centro de Mando Analítico!</h2>
                              <p class="text-sm text-slate-400 max-w-2xl">
                                  Este reporte consolida las métricas clave de tus operaciones en tiempo real. Registra nuevos incidentes usando el simulador al final y observa las gráficas actualizarse al instante.
                              </p>
                          </div>
                          <div class="flex items-center gap-3">
                              <a href="#simulator-section" class="inline-flex items-center gap-2 text-xs font-semibold bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white px-4 py-2.5 rounded-xl transition-all shadow-lg shadow-blue-600/20">
                                  <i class="fa-solid fa-plus"></i> Registrar Incidente
                              </a>
                              <button @click="resetData()" class="p-2.5 text-slate-400 hover:text-white bg-slate-800 hover:bg-slate-700 rounded-xl transition-all border border-slate-700" title="Reiniciar datos">
                                  <i class="fa-solid fa-arrow-rotate-right"></i>
                              </button>
                          </div>
                      </div>

                      <!-- KPI Cards -->
                      <section class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
                          
                          <!-- Card 1: Total Incidents -->
                          <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group">
                              <div class="absolute -right-3 -bottom-3 text-slate-800 opacity-20 text-7xl font-bold group-hover:scale-110 transition-transform">
                                  <i class="fa-solid fa-database"></i>
                              </div>
                              <div class="flex justify-between items-start">
                                  <div>
                                      <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Histórico General</span>
                                      <h3 class="text-3xl font-extrabold text-white mt-1" x-text="totalCount">0</h3>
                                  </div>
                                  <span class="p-2.5 bg-slate-800 text-slate-400 rounded-xl border border-slate-700">
                                      <i class="fa-solid fa-clock-rotate-left text-sm"></i>
                                  </span>
                              </div>
                              <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                  <span class="text-emerald-400 font-semibold"><i class="fa-solid fa-arrow-trend-up"></i> 100%</span>
                                  acumulado de registros en base
                              </p>
                          </div>

                          <!-- Card 2: Day Incidents -->
                          <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group">
                              <div class="absolute -right-3 -bottom-3 text-amber-500 opacity-10 text-7xl font-bold group-hover:scale-110 transition-transform">
                                  <i class="fa-solid fa-triangle-exclamation"></i>
                              </div>
                              <div class="flex justify-between items-start">
                                  <div>
                                      <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Registros Hoy</span>
                                      <h3 class="text-3xl font-extrabold text-amber-400 mt-1" x-text="todayCount">0</h3>
                                  </div>
                                  <span class="p-2.5 bg-amber-500/10 text-amber-400 rounded-xl border border-amber-500/20">
                                      <i class="fa-solid fa-bell text-sm animate-pulse"></i>
                                  </span>
                              </div>
                              <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                  <span class="text-slate-300 font-semibold" x-text="todayActiveCount + ' activos'">0 activos</span>
                                  pendientes de atención hoy
                              </p>
                          </div>

                          <!-- Card 3: Resolved -->
                          <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group">
                              <div class="absolute -right-3 -bottom-3 text-emerald-500 opacity-10 text-7xl font-bold group-hover:scale-110 transition-transform">
                                  <i class="fa-solid fa-circle-check"></i>
                              </div>
                              <div class="flex justify-between items-start">
                                  <div>
                                      <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Resueltos Totales</span>
                                      <h3 class="text-3xl font-extrabold text-emerald-400 mt-1" x-text="resolvedCount">0</h3>
                                  </div>
                                  <span class="p-2.5 bg-emerald-500/10 text-emerald-400 rounded-xl border border-emerald-500/20">
                                      <i class="fa-solid fa-check text-sm"></i>
                                  </span>
                              </div>
                              <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5">
                                  <span class="text-emerald-400 font-semibold" x-text="resolvedPct">0%</span>
                                  tasa de efectividad de solución
                              </p>
                          </div>

                          <!-- Card 4: Critical Level -->
                          <div class="bg-slate-900 border border-slate-800 hover:border-slate-700 rounded-2xl p-5 relative overflow-hidden transition-all duration-300 group">
                              <div class="absolute -right-3 -bottom-3 text-rose-500 opacity-10 text-7xl font-bold group-hover:scale-110 transition-transform">
                                  <i class="fa-solid fa-industry"></i>
                              </div>
                              <div class="flex justify-between items-start">
                                  <div>
                                      <span class="text-xs font-medium text-slate-400 uppercase tracking-wider">Planta Crítica</span>
                                      <h3 class="text-xl font-bold text-rose-400 mt-2 truncate" x-text="critPlantName">-</h3>
                                  </div>
                                  <span class="p-2.5 bg-rose-500/10 text-rose-400 rounded-xl border border-rose-500/20">
                                      <i class="fa-solid fa-triangle-exclamation text-sm"></i>
                                  </span>
                              </div>
                              <p class="text-xs text-slate-400 mt-3 flex items-center gap-1.5" x-html="critPlantDesc">
                                  Mayor concentración de incidentes
                              </p>
                          </div>
                      </section>

                      <!-- Filters section -->
                      <section class="bg-slate-900 border border-slate-800 rounded-2xl p-4 flex flex-wrap gap-4 items-center justify-between">
                          <div class="flex items-center gap-2">
                              <i class="fa-solid fa-filter text-blue-400 text-sm"></i>
                              <span class="text-sm font-semibold text-white">Controles de Filtrado Rápido</span>
                          </div>
                          <div class="flex flex-wrap items-center gap-3 w-full sm:w-auto">
                              <select x-model="filterPlant" @change="applyFilters()" class="bg-slate-800 text-slate-200 border border-slate-700 text-xs rounded-xl px-3 py-2 outline-none focus:border-blue-500 transition-all flex-grow sm:flex-grow-0">
                                  <option value="all">Todas las Plantas</option>
                                  <option value="Planta 1">Planta 1</option>
                                  <option value="Planta 2">Planta 2</option>
                              </select>
                              <select x-model="filterPriority" @change="applyFilters()" class="bg-slate-800 text-slate-200 border border-slate-700 text-xs rounded-xl px-3 py-2 outline-none focus:border-blue-500 transition-all flex-grow sm:flex-grow-0">
                                  <option value="all">Todas las Prioridades</option>
                                  <option value="Alta">Prioridad Alta</option>
                                  <option value="Media">Prioridad Media</option>
                                  <option value="Baja">Prioridad Baja</option>
                              </select>
                              <button @click="clearFilters()" class="text-xs text-slate-400 hover:text-white hover:bg-slate-800 px-3 py-2 rounded-xl transition-all border border-slate-700">
                                  Restaurar Filtros
                              </button>
                          </div>
                      </section>

                      <!-- Charts Grid -->
                      <section class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                          
                          <!-- Left Chart Block: Trend -->
                          <div class="lg:col-span-8 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                              <div class="flex items-center justify-between mb-4">
                                  <div>
                                      <h4 class="text-base font-bold text-white flex items-center gap-2">
                                          <i class="fa-solid fa-chart-line text-blue-400"></i> Tendencia General de Incidentes
                                      </h4>
                                      <p class="text-xs text-slate-400 mt-0.5">Relación temporal de reportados vs. resueltos en los últimos días</p>
                                  </div>
                                  <span class="text-[10px] uppercase font-bold tracking-wider text-blue-400 px-2.5 py-1 bg-blue-500/10 border border-blue-500/20 rounded-full">
                                      Histórico Diario
                                  </span>
                              </div>
                              <div class="relative h-[320px] w-full">
                                  <canvas id="trendChart"></canvas>
                              </div>
                          </div>

                          <!-- Right Chart Block: Plants -->
                          <div class="lg:col-span-4 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                              <div class="flex items-center justify-between mb-4">
                                  <div>
                                      <h4 class="text-base font-bold text-white flex items-center gap-2">
                                          <i class="fa-solid fa-industry text-amber-400"></i> Distribución por Planta
                                      </h4>
                                      <p class="text-xs text-slate-400 mt-0.5">¿Qué planta reporta más incidencias?</p>
                                  </div>
                              </div>
                              <div class="relative h-[250px] w-full flex items-center justify-center">
                                  <canvas id="plantChart"></canvas>
                              </div>
                              <div id="plant-legend-info" class="mt-4 pt-3 border-t border-slate-800 text-xs text-slate-400 space-y-1">
                                  <!-- Se calculará dinámicamente -->
                              </div>
                          </div>

                          <!-- Bottom Left Chart Block: Department Areas -->
                          <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                              <div class="flex items-center justify-between mb-4">
                                  <div>
                                      <h4 class="text-base font-bold text-white flex items-center gap-2">
                                          <i class="fa-solid fa-users text-purple-400"></i> Áreas Solicitantes de Soporte
                                      </h4>
                                      <p class="text-xs text-slate-400 mt-0.5">¿Qué departamento requiere más ayuda?</p>
                                  </div>
                                  <span class="text-xs text-slate-500" id="area-winner-txt">Calculando...</span>
                              </div>
                              <div class="relative h-[280px] w-full">
                                  <canvas id="areaChart"></canvas>
                              </div>
                          </div>

                          <!-- Bottom Right Chart Block: Polar Area State -->
                          <div class="lg:col-span-5 bg-slate-900 border border-slate-800 rounded-2xl p-5 flex flex-col justify-between">
                              <div class="flex items-center justify-between mb-4">
                                  <div>
                                      <h4 class="text-base font-bold text-white flex items-center gap-2">
                                          <i class="fa-solid fa-shield-halved text-rose-400"></i> Estado & Severidad
                                      </h4>
                                      <p class="text-xs text-slate-400 mt-0.5">Clasificación actual del backlog de tickets</p>
                                  </div>
                              </div>
                              <div class="relative h-[220px] w-full flex items-center justify-center">
                                  <canvas id="stateChart"></canvas>
                              </div>
                              <div class="grid grid-cols-3 gap-2 mt-4 text-center">
                                  <div class="bg-slate-950/50 p-2 rounded-xl border border-slate-800/60">
                                      <span class="block text-[10px] text-slate-400 font-medium">Abiertos</span>
                                      <span class="text-sm font-extrabold text-blue-400" id="status-open-badge">0</span>
                                  </div>
                                  <div class="bg-slate-950/50 p-2 rounded-xl border border-slate-800/60">
                                      <span class="block text-[10px] text-slate-400 font-medium">En Curso</span>
                                      <span class="text-sm font-extrabold text-amber-400" id="status-process-badge">0</span>
                                  </div>
                                  <div class="bg-slate-950/50 p-2 rounded-xl border border-slate-800/60">
                                      <span class="block text-[10px] text-slate-400 font-medium">Resueltos</span>
                                      <span class="text-sm font-extrabold text-emerald-400" id="status-resolved-badge">0</span>
                                  </div>
                              </div>
                          </div>
                      </section>

                      <!-- Simulator and Recent Tickets Table Section -->
                      <section id="simulator-section" class="grid grid-cols-1 lg:grid-cols-12 gap-6">
                          
                          <!-- Left: Simulator Form -->
                          <div class="lg:col-span-5 bg-gradient-to-br from-slate-900 to-slate-900/90 border border-slate-800 rounded-2xl p-6 relative">
                              <div class="absolute right-4 top-4 text-slate-800 text-4xl font-extrabold pointer-events-none opacity-20">
                                  <i class="fa-solid fa-gamepad"></i>
                              </div>
                              
                              <h4 class="text-base font-bold text-white mb-1 flex items-center gap-2">
                                  <i class="fa-solid fa-square-plus text-blue-400"></i> Simulador de Tickets Activo
                              </h4>
                              <p class="text-xs text-slate-400 mb-5">Ingresa nuevos datos para interactuar con los gráficos y el servidor al instante.</p>

                              <form @submit="handleNewTicket" class="space-y-4">
                                  <div>
                                      <label class="block text-xs font-semibold text-slate-300 mb-1">Descripción del Incidente *</label>
                                      <input type="text" x-model="simDesc" placeholder="Ej: Falla eléctrica en prensa hidráulica #4" required
                                          class="w-full bg-slate-950 border border-slate-800 hover:border-slate-700 focus:border-blue-500 rounded-xl px-3 py-2.5 text-xs text-white placeholder-slate-500 outline-none transition-all">
                                  </div>

                                  <div class="grid grid-cols-2 gap-4">
                                      <div>
                                          <label class="block text-xs font-semibold text-slate-300 mb-1 font-semibold">Departamento/Área *</label>
                                          <select x-model="simArea" required class="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                              <option value="Sistemas / TI">Sistemas / TI</option>
                                              <option value="Mantenimiento">Mantenimiento</option>
                                              <option value="Producción">Producción</option>
                                              <option value="Calidad">Calidad</option>
                                              <option value="Logística & Embarques">Logística & Embarques</option>
                                              <option value="Recursos Humanos">Recursos Humanos</option>
                                          </select>
                                      </div>
                                      <div>
                                          <label class="block text-xs font-semibold text-slate-300 mb-1">Planta Operativa *</label>
                                          <select x-model="simPlant" required class="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                              <option value="Planta 1">Planta 1</option>
                                              <option value="Planta 2">Planta 2</option>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="grid grid-cols-2 gap-4">
                                      <div>
                                          <label class="block text-xs font-semibold text-slate-300 mb-1">Prioridad *</label>
                                          <div class="flex gap-2">
                                              <label class="flex-1 text-center cursor-pointer">
                                                  <input type="radio" x-model="simPriority" value="Baja" class="sr-only peer">
                                                  <span class="block text-[11px] py-1.5 rounded-lg border border-slate-800 peer-checked:border-emerald-500 peer-checked:bg-emerald-500/10 peer-checked:text-emerald-400 bg-slate-950 text-slate-400 font-medium hover:bg-slate-800/50 transition-all">Baja</span>
                                              </label>
                                              <label class="flex-1 text-center cursor-pointer">
                                                  <input type="radio" x-model="simPriority" value="Media" class="sr-only peer">
                                                  <span class="block text-[11px] py-1.5 rounded-lg border border-slate-800 peer-checked:border-amber-500 peer-checked:bg-amber-500/10 peer-checked:text-amber-400 bg-slate-950 text-slate-400 font-medium hover:bg-slate-800/50 transition-all">Media</span>
                                              </label>
                                              <label class="flex-1 text-center cursor-pointer">
                                                  <input type="radio" x-model="simPriority" value="Alta" class="sr-only peer">
                                                  <span class="block text-[11px] py-1.5 rounded-lg border border-slate-800 peer-checked:border-rose-500 peer-checked:bg-rose-500/10 peer-checked:text-rose-400 bg-slate-950 text-slate-400 font-medium hover:bg-slate-800/50 transition-all">Alta</span>
                                              </label>
                                          </div>
                                      </div>
                                      <div>
                                          <label class="block text-xs font-semibold text-slate-300 mb-1">Estado Inicial *</label>
                                          <select x-model="simStatus" required class="w-full bg-slate-950 border border-slate-800 focus:border-blue-500 rounded-xl px-3 py-2.5 text-xs text-white outline-none transition-all">
                                              <option value="Abierto">Abierto</option>
                                              <option value="En Proceso">En Proceso</option>
                                              <option value="Resuelto">Resuelto</option>
                                          </select>
                                      </div>
                                  </div>

                                  <div class="pt-2">
                                      <button type="submit" class="w-full bg-blue-600 hover:bg-blue-500 active:bg-blue-700 text-white font-semibold py-3 px-4 rounded-xl text-xs transition-all flex items-center justify-center gap-2 shadow-lg shadow-blue-600/20">
                                          <i class="fa-solid fa-paper-plane"></i> Insertar en Gráficos y Tabla
                                      </button>
                                  </div>
                              </form>
                          </div>

                          <!-- Right: Real-time ticket list -->
                          <div class="lg:col-span-7 bg-slate-900 border border-slate-800 rounded-2xl p-6 flex flex-col justify-between">
                              <div>
                                  <div class="flex items-center justify-between mb-4">
                                      <div>
                                          <h4 class="text-base font-bold text-white flex items-center gap-2">
                                              <i class="fa-solid fa-list-check text-indigo-400"></i> Registro Reciente de Incidentes
                                          </h4>
                                          <p class="text-xs text-slate-400 font-medium">Monitoreo en tiempo real de la base. Haz clic en "Resolver" para solucionar.</p>
                                      </div>
                                      <span class="text-[10px] text-slate-400 bg-slate-800 border border-slate-700 rounded-full px-2.5 py-1 font-bold" id="record-count">0 tickets</span>
                                  </div>

                                  <div class="overflow-x-auto rounded-xl border border-slate-800 bg-slate-950 max-h-[290px] overflow-y-auto">
                                      <table class="w-full text-left text-xs text-slate-300">
                                          <thead class="bg-slate-900 text-slate-400 uppercase tracking-wider text-[10px] border-b border-slate-800 sticky top-0">
                                              <tr>
                                                  <th class="px-4 py-3">ID / Descripción</th>
                                                  <th class="px-4 py-3">Planta / Área</th>
                                                  <th class="px-4 py-3">Prioridad</th>
                                                  <th class="px-4 py-3">Estado</th>
                                                  <th class="px-4 py-3 text-right">Acción</th>
                                              </tr>
                                          </thead>
                                          <tbody id="tickets-table-body">
                                              <!-- Filas dinámicas por JS -->
                                          </tbody>
                                      </table>
                                  </div>
                              </div>

                              <div class="mt-4 pt-3 border-t border-slate-800 flex flex-wrap justify-between items-center gap-3 text-xs text-slate-400">
                                  <div class="flex items-center gap-3">
                                      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-blue-500 block"></span> Abierto</span>
                                      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-amber-500 block"></span> En Curso</span>
                                      <span class="flex items-center gap-1.5"><span class="w-2.5 h-2.5 rounded-full bg-emerald-500 block"></span> Resuelto</span>
                                  </div>
                                  <span class="font-medium">Actualizado en tiempo real</span>
                              </div>
                          </div>
                      </section>

                      <!-- No-alert Toast/Modal Notification -->
                      <div id="toast" class="fixed bottom-5 right-5 transform translate-y-24 opacity-0 transition-all duration-300 bg-slate-900 border border-emerald-500/30 text-slate-100 px-4 py-3 rounded-xl shadow-2xl z-[9999] flex items-center gap-3">
                          <div class="p-1 bg-emerald-500/20 text-emerald-400 rounded-lg">
                              <i class="fa-solid fa-circle-check text-sm"></i>
                          </div>
                          <div class="text-xs">
                              <p class="font-bold text-white" id="toast-title">Notificación</p>
                              <p class="text-slate-400 font-medium" id="toast-desc">Detalle del mensaje.</p>
                          </div>
                      </div>

                  </div>
             </template>