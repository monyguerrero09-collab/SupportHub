{{
                           tickets: @json($allTicketsForJs),
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
                      }