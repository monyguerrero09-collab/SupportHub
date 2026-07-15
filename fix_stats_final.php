<?php
/**
 * DEFINITIVE FIX: Replace <template x-if> statistics with <div x-show>
 * and move ALL chart JS into a proper <script> tag outside Alpine.
 */

$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

// ─── 1. Find the statistics template block ───────────────────────────────────
$startMarker = "<template x-if=\"activeTab === 'statistics'\">";
$startPos    = strpos($content, $startMarker);
if ($startPos === false) { die("ERROR: statistics template start not found\n"); }

$templateEnd = strpos($content, '</template>', $startPos) + strlen('</template>');
echo "Template end at char $templateEnd\n";

// Also eat any trailing whitespace lines up to next content
$replaceEnd  = $templateEnd;

// ─── 2. Build replacement ────────────────────────────────────────────────────
$html = <<<'BLADE'

{{-- ═══════════════════════════════════════════════════════
     STATISTICS TAB — uses x-show so canvases always exist in DOM
     Charts are initialised in the <script> block below.
═══════════════════════════════════════════════════════════ --}}
<div x-show="activeTab === 'statistics'" class="pb-10">

    {{-- ── HEADER BANNER ───────────────────────────────────────── --}}
    <div style="position:relative;overflow:hidden;border-radius:1.5rem;background:linear-gradient(135deg,rgba(99,102,241,0.15),rgba(6,182,212,0.10),rgba(16,185,129,0.08));border:1px solid rgba(99,102,241,0.25);margin-bottom:2rem;">
        <div style="position:absolute;top:-60px;right:-60px;width:200px;height:200px;background:radial-gradient(circle,rgba(99,102,241,0.3),transparent 70%);pointer-events:none;"></div>
        <div style="position:relative;z-index:1;display:flex;flex-wrap:wrap;align-items:center;justify-content:space-between;gap:1.5rem;padding:2rem 2.5rem;">
            <div>
                <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.5rem;">
                    <div style="width:38px;height:38px;border-radius:12px;background:linear-gradient(135deg,#6366f1,#06b6d4);display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fa-solid fa-chart-mixed" style="color:#fff;font-size:14px;"></i>
                    </div>
                    <h2 style="font-size:1.5rem;font-weight:900;background:linear-gradient(to right,#fff,#c7d2fe,#67e8f9);-webkit-background-clip:text;-webkit-text-fill-color:transparent;text-transform:uppercase;letter-spacing:-0.03em;margin:0;">
                        Centro de Mando Analítico
                    </h2>
                </div>
                <p style="font-size:13px;color:rgba(147,197,253,0.7);font-weight:500;max-width:500px;margin:0;">
                    Métricas en tiempo real desde tu base de datos operativa.
                </p>
            </div>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-shrink:0;">
                <div style="display:flex;align-items:center;gap:8px;padding:6px 14px;border-radius:10px;background:rgba(16,185,129,0.1);border:1px solid rgba(16,185,129,0.3);">
                    <span style="width:8px;height:8px;background:#34d399;border-radius:50%;display:inline-block;animation:pulse 1.5s ease-in-out infinite;box-shadow:0 0 8px #34d399;"></span>
                    <span style="font-size:10px;font-weight:800;color:#34d399;text-transform:uppercase;letter-spacing:0.15em;">Sistema en línea</span>
                </div>
                <button wire:click="$refresh" style="display:flex;align-items:center;gap:6px;padding:7px 16px;border-radius:10px;background:rgba(255,255,255,0.07);border:1px solid rgba(255,255,255,0.12);color:#e2e8f0;font-size:11px;font-weight:800;text-transform:uppercase;letter-spacing:0.1em;cursor:pointer;transition:background 0.2s;">
                    <i class="fa-solid fa-arrows-rotate" style="color:#818cf8;"></i> Actualizar
                </button>
            </div>
        </div>
    </div>

    {{-- ── KPI CARDS ────────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(180px,1fr));gap:1rem;margin-bottom:1.5rem;">
        {{-- Total --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(99,102,241,0.25);border-radius:1.25rem;padding:1.25rem;backdrop-filter:blur(20px);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div style="width:38px;height:38px;border-radius:12px;background:rgba(99,102,241,0.15);border:1px solid rgba(99,102,241,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-ticket" style="color:#818cf8;font-size:13px;"></i>
                </div>
                <span style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:#6366f1;background:rgba(99,102,241,0.12);padding:3px 10px;border-radius:99px;border:1px solid rgba(99,102,241,0.25);">TOTAL</span>
            </div>
            <div style="font-size:3rem;font-weight:900;color:#fff;line-height:1;letter-spacing:-0.05em;">{{ array_sum($statusCounts->toArray()) }}</div>
            <p style="font-size:10px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.12em;margin-top:6px;">Tickets globales</p>
        </div>

        {{-- Abiertos --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(59,130,246,0.25);border-radius:1.25rem;padding:1.25rem;backdrop-filter:blur(20px);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div style="width:38px;height:38px;border-radius:12px;background:rgba(59,130,246,0.15);border:1px solid rgba(59,130,246,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-regular fa-clock" style="color:#60a5fa;font-size:13px;"></i>
                </div>
                <span style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:#60a5fa;background:rgba(59,130,246,0.12);padding:3px 10px;border-radius:99px;border:1px solid rgba(59,130,246,0.25);">ABIERTOS</span>
            </div>
            <div style="font-size:3rem;font-weight:900;color:#60a5fa;line-height:1;letter-spacing:-0.05em;">{{ $statusCounts[1] ?? 0 }}</div>
            <p style="font-size:10px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.12em;margin-top:6px;">Pendientes</p>
        </div>

        {{-- En Proceso --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(245,158,11,0.25);border-radius:1.25rem;padding:1.25rem;backdrop-filter:blur(20px);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div style="width:38px;height:38px;border-radius:12px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-gear" style="color:#fbbf24;font-size:13px;"></i>
                </div>
                <span style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:#fbbf24;background:rgba(245,158,11,0.12);padding:3px 10px;border-radius:99px;border:1px solid rgba(245,158,11,0.25);">EN PROCESO</span>
            </div>
            <div style="font-size:3rem;font-weight:900;color:#fbbf24;line-height:1;letter-spacing:-0.05em;">{{ $statusCounts[2] ?? 0 }}</div>
            <p style="font-size:10px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.12em;margin-top:6px;">En atención</p>
        </div>

        {{-- Resueltos --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(16,185,129,0.25);border-radius:1.25rem;padding:1.25rem;backdrop-filter:blur(20px);">
            <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1rem;">
                <div style="width:38px;height:38px;border-radius:12px;background:rgba(16,185,129,0.15);border:1px solid rgba(16,185,129,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-check" style="color:#34d399;font-size:13px;"></i>
                </div>
                <span style="font-size:9px;font-weight:800;text-transform:uppercase;letter-spacing:0.12em;color:#34d399;background:rgba(16,185,129,0.12);padding:3px 10px;border-radius:99px;border:1px solid rgba(16,185,129,0.25);">RESUELTOS</span>
            </div>
            <div style="font-size:3rem;font-weight:900;color:#34d399;line-height:1;letter-spacing:-0.05em;">{{ ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0) }}</div>
            <p style="font-size:10px;font-weight:700;color:#475569;text-transform:uppercase;letter-spacing:0.12em;margin-top:6px;">Finalizados</p>
        </div>
    </div>

    {{-- ── TENDENCIA (full width) ───────────────────────────────── --}}
    <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);margin-bottom:1.5rem;">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.5rem 1.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:10px;background:rgba(99,102,241,0.2);border:1px solid rgba(99,102,241,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-line" style="color:#818cf8;font-size:11px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.15em;margin:0;">Tendencia de Tickets</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin:2px 0 0;">Últimos 7 meses · datos en vivo</p>
                </div>
            </div>
            <div style="display:flex;align-items:center;gap:16px;">
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:18px;height:3px;background:#6366f1;border-radius:2px;"></div>
                    <span style="font-size:10px;color:#94a3b8;font-weight:600;">Creados</span>
                </div>
                <div style="display:flex;align-items:center;gap:6px;">
                    <div style="width:18px;height:0;border-top:2px dashed #10b981;"></div>
                    <span style="font-size:10px;color:#94a3b8;font-weight:600;">Cerrados</span>
                </div>
            </div>
        </div>
        <div style="padding:1.25rem 1.75rem 1.75rem;height:280px;">
            <canvas id="ch-trend" style="width:100%;height:100%;"></canvas>
        </div>
    </div>

    {{-- ── 3 CHARTS ROW ─────────────────────────────────────────── --}}
    <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:1.25rem;margin-bottom:1.5rem;">

        {{-- Distribución Planta --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:10px;padding:1.25rem 1.5rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:30px;height:30px;border-radius:10px;background:rgba(6,182,212,0.15);border:1px solid rgba(6,182,212,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-building" style="color:#22d3ee;font-size:10px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.12em;margin:0;">Distribución Planta</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;margin:1px 0 0;">Tickets por planta</p>
                </div>
            </div>
            <div style="padding:1.25rem;flex:1;min-height:230px;position:relative;">
                <canvas id="ch-planta" style="width:100%;height:100%;"></canvas>
            </div>
        </div>

        {{-- Tipos de Problema --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:10px;padding:1.25rem 1.5rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:30px;height:30px;border-radius:10px;background:rgba(168,85,247,0.15);border:1px solid rgba(168,85,247,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-tags" style="color:#c084fc;font-size:10px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.12em;margin:0;">Tipos de Problema</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;margin:1px 0 0;">Categorías de incidencia</p>
                </div>
            </div>
            <div style="padding:1.25rem;flex:1;min-height:230px;position:relative;">
                <canvas id="ch-cat" style="width:100%;height:100%;"></canvas>
            </div>
        </div>

        {{-- Proporción de Estados --}}
        <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);display:flex;flex-direction:column;">
            <div style="display:flex;align-items:center;gap:10px;padding:1.25rem 1.5rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
                <div style="width:30px;height:30px;border-radius:10px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-chart-pie" style="color:#fbbf24;font-size:10px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.12em;margin:0;">Proporción de Estados</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;margin:1px 0 0;">Distribución de estados</p>
                </div>
            </div>
            <div style="padding:1.25rem;flex:1;min-height:230px;position:relative;">
                <canvas id="ch-status" style="width:100%;height:100%;"></canvas>
            </div>
        </div>
    </div>

    {{-- ── TABLA RECIENTE ──────────────────────────────────────────── --}}
    <div style="background:rgba(10,15,30,0.85);border:1px solid rgba(255,255,255,0.08);border-radius:1.5rem;backdrop-filter:blur(20px);">
        <div style="display:flex;align-items:center;justify-content:space-between;padding:1.5rem 1.75rem 1rem;border-bottom:1px solid rgba(255,255,255,0.05);">
            <div style="display:flex;align-items:center;gap:10px;">
                <div style="width:32px;height:32px;border-radius:10px;background:rgba(245,158,11,0.15);border:1px solid rgba(245,158,11,0.3);display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-bolt" style="color:#fbbf24;font-size:11px;"></i>
                </div>
                <div>
                    <h3 style="font-size:11px;font-weight:800;color:#fff;text-transform:uppercase;letter-spacing:0.15em;margin:0;">Actividad Reciente</h3>
                    <p style="font-size:9px;color:#475569;font-weight:600;text-transform:uppercase;letter-spacing:0.1em;margin:2px 0 0;">Últimos tickets registrados</p>
                </div>
            </div>
            <button wire:click="setTab('tickets')" style="font-size:10px;font-weight:800;color:#818cf8;text-transform:uppercase;letter-spacing:0.12em;background:none;border:none;cursor:pointer;display:flex;align-items:center;gap:6px;padding:0;">
                Ver todos <i class="fa-solid fa-arrow-right" style="font-size:8px;"></i>
            </button>
        </div>
        <div style="overflow-x:auto;">
            <table style="width:100%;border-collapse:collapse;min-width:540px;">
                <thead>
                    <tr>
                        <th style="padding:11px 16px;text-align:left;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">ID</th>
                        <th style="padding:11px 16px;text-align:left;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Título</th>
                        <th style="padding:11px 16px;text-align:left;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Planta</th>
                        <th style="padding:11px 16px;text-align:center;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Estado</th>
                        <th style="padding:11px 16px;text-align:right;font-size:9px;font-weight:800;color:#475569;text-transform:uppercase;letter-spacing:0.18em;">Fecha</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($tickets->sortByDesc('created_at')->take(8) as $t)
                    <tr style="border-top:1px solid rgba(255,255,255,0.04);">
                        <td style="padding:12px 16px;">
                            <span style="font-size:11px;font-weight:800;color:#475569;background:rgba(255,255,255,0.05);padding:3px 8px;border-radius:6px;">#{{ str_pad($t->id,4,'0',STR_PAD_LEFT) }}</span>
                        </td>
                        <td style="padding:12px 16px;font-size:12px;font-weight:600;color:#cbd5e1;max-width:220px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;">
                            {{ Str::limit($t->titulo ?? 'Sin título', 42) }}
                        </td>
                        <td style="padding:12px 16px;">
                            <span style="font-size:9px;font-weight:800;color:#c4b5fd;background:rgba(139,92,246,0.1);padding:3px 10px;border-radius:6px;border:1px solid rgba(139,92,246,0.2);text-transform:uppercase;letter-spacing:0.1em;">Planta {{ $t->planta ?? 1 }}</span>
                        </td>
                        <td style="padding:12px 16px;text-align:center;">
                            @if($t->estado_id == 1)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#60a5fa;background:rgba(59,130,246,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(59,130,246,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#3b82f6;border-radius:50%;display:inline-block;"></span>Abierto</span>
                            @elseif($t->estado_id == 2)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#fbbf24;background:rgba(245,158,11,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(245,158,11,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#f59e0b;border-radius:50%;display:inline-block;animation:pulse 1.5s infinite;"></span>Proceso</span>
                            @elseif($t->estado_id == 3)
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#34d399;background:rgba(16,185,129,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(16,185,129,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#10b981;border-radius:50%;display:inline-block;"></span>Resuelto</span>
                            @else
                                <span style="display:inline-flex;align-items:center;gap:5px;font-size:9px;font-weight:800;color:#94a3b8;background:rgba(100,116,139,0.1);padding:4px 10px;border-radius:99px;border:1px solid rgba(100,116,139,0.2);text-transform:uppercase;"><span style="width:6px;height:6px;background:#64748b;border-radius:50%;display:inline-block;"></span>Cerrado</span>
                            @endif
                        </td>
                        <td style="padding:12px 16px;font-size:11px;font-weight:600;color:#475569;text-align:right;white-space:nowrap;">
                            {{ $t->created_at->format('d/m/Y') }}
                            <span style="color:#334155;font-size:10px;margin-left:4px;">{{ $t->created_at->format('H:i') }}</span>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" style="padding:40px;text-align:center;font-size:13px;color:#475569;">No hay tickets registrados.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@once
<script>
(function () {
    /* ── PHP data injected server-side ── */
    var STAT_DATA = {
        months:   {{ Js::from($trendMonths->values()) }},
        created:  {{ Js::from($trendData) }},
        closed:   {{ Js::from($trendClosedData) }},
        pLabels:  {{ Js::from(array_keys($plantaCounts)) }},
        pValues:  {{ Js::from(array_values($plantaCounts)) }},
        cLabels:  {{ Js::from($categoryData->keys()->values()) }},
        cValues:  {{ Js::from($categoryData->values()->values()) }},
        sOpen:    {{ $statusCounts[1] ?? 0 }},
        sProc:    {{ $statusCounts[2] ?? 0 }},
        sDone:    {{ ($statusCounts[3] ?? 0) + ($statusCounts[4] ?? 0) }}
    };

    var TIP = {
        backgroundColor: '#0f172a',
        titleColor: '#f1f5f9',
        bodyColor: '#94a3b8',
        borderColor: '#1e293b',
        borderWidth: 1,
        padding: 12,
        cornerRadius: 10
    };

    function kill(id) {
        var el = document.getElementById(id);
        if (el && el._ch) { el._ch.destroy(); delete el._ch; }
    }

    function buildCharts() {
        if (typeof Chart === 'undefined') return;

        Chart.defaults.color = '#94a3b8';
        Chart.defaults.borderColor = 'rgba(51,65,85,0.25)';

        /* 1. TENDENCIA */
        kill('ch-trend');
        var c1 = document.getElementById('ch-trend');
        if (c1) {
            c1._ch = new Chart(c1, {
                type: 'line',
                data: {
                    labels: STAT_DATA.months,
                    datasets: [
                        {
                            label: 'Creados',
                            data: STAT_DATA.created,
                            borderColor: '#6366f1',
                            backgroundColor: function(ctx) {
                                var g = ctx.chart.ctx.createLinearGradient(0, 0, 0, 260);
                                g.addColorStop(0, 'rgba(99,102,241,0.28)');
                                g.addColorStop(1, 'rgba(99,102,241,0)');
                                return g;
                            },
                            borderWidth: 3, tension: 0.45, fill: true,
                            pointBackgroundColor: '#6366f1', pointBorderColor: '#0f172a',
                            pointRadius: 5, pointHoverRadius: 9, pointBorderWidth: 2
                        },
                        {
                            label: 'Cerrados',
                            data: STAT_DATA.closed,
                            borderColor: '#10b981',
                            backgroundColor: 'transparent',
                            borderWidth: 2, borderDash: [6, 4], tension: 0.45,
                            pointBackgroundColor: '#10b981', pointBorderColor: '#0f172a',
                            pointRadius: 4, pointHoverRadius: 7, pointBorderWidth: 2
                        }
                    ]
                },
                options: {
                    responsive: true, maintainAspectRatio: false,
                    interaction: { mode: 'index', intersect: false },
                    plugins: {
                        legend: { labels: { usePointStyle: true, pointStyleWidth: 10, padding: 20, font: { size: 11, weight: 'bold' } } },
                        tooltip: TIP
                    },
                    scales: {
                        x: { grid: { color: 'rgba(51,65,85,0.2)' }, ticks: { font: { size: 11 } } },
                        y: { grid: { color: 'rgba(51,65,85,0.2)' }, ticks: { precision: 0, font: { size: 11 } }, beginAtZero: true }
                    }
                }
            });
        }

        /* 2. DISTRIBUCIÓN PLANTA */
        kill('ch-planta');
        var c2 = document.getElementById('ch-planta');
        if (c2) {
            c2._ch = new Chart(c2, {
                type: 'doughnut',
                data: {
                    labels: STAT_DATA.pLabels,
                    datasets: [{ data: STAT_DATA.pValues, backgroundColor: ['#6366f1','#06b6d4'], borderColor: '#080c1a', borderWidth: 4, hoverOffset: 8 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '72%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16, font: { size: 11, weight: 'bold' } } },
                        tooltip: TIP
                    }
                }
            });
        }

        /* 3. TIPOS DE PROBLEMA */
        kill('ch-cat');
        var c3 = document.getElementById('ch-cat');
        if (c3) {
            var bgs = ['rgba(168,85,247,0.75)','rgba(59,130,246,0.75)','rgba(16,185,129,0.75)','rgba(245,158,11,0.75)','rgba(239,68,68,0.75)','rgba(20,184,166,0.75)'];
            c3._ch = new Chart(c3, {
                type: 'bar',
                data: {
                    labels: STAT_DATA.cLabels.length ? STAT_DATA.cLabels : ['Sin datos'],
                    datasets: [{ label: 'Tickets', data: STAT_DATA.cValues.length ? STAT_DATA.cValues : [0], backgroundColor: bgs, borderRadius: 8, barThickness: 22 }]
                },
                options: {
                    indexAxis: 'y', responsive: true, maintainAspectRatio: false,
                    plugins: { legend: { display: false }, tooltip: TIP },
                    scales: {
                        x: { grid: { color: 'rgba(51,65,85,0.2)' }, ticks: { precision: 0, font: { size: 10 } }, beginAtZero: true },
                        y: { grid: { display: false }, ticks: { font: { size: 10 } } }
                    }
                }
            });
        }

        /* 4. PROPORCIÓN DE ESTADOS */
        kill('ch-status');
        var c4 = document.getElementById('ch-status');
        if (c4) {
            c4._ch = new Chart(c4, {
                type: 'doughnut',
                data: {
                    labels: ['Abiertos','En Proceso','Resueltos'],
                    datasets: [{ data: [STAT_DATA.sOpen, STAT_DATA.sProc, STAT_DATA.sDone], backgroundColor: ['#3b82f6','#f59e0b','#10b981'], borderColor: '#080c1a', borderWidth: 4, hoverOffset: 8 }]
                },
                options: {
                    responsive: true, maintainAspectRatio: false, cutout: '70%',
                    plugins: {
                        legend: { position: 'bottom', labels: { usePointStyle: true, padding: 14, font: { size: 10, weight: 'bold' } } },
                        tooltip: TIP
                    }
                }
            });
        }
    }

    /* Run after page fully loads */
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', buildCharts);
    } else {
        buildCharts();
    }

    /* Also re-run whenever Livewire refreshes the component */
    document.addEventListener('livewire:morph', buildCharts);
    document.addEventListener('livewire:update', function() { setTimeout(buildCharts, 150); });
})();
</script>
@endonce

BLADE;

// ─── 3. Write ────────────────────────────────────────────────────────────────
$newContent = substr($content, 0, $startPos) . $html . substr($content, $replaceEnd);
file_put_contents($file, $newContent);
echo "Done! File is now " . strlen($newContent) . " bytes.\n";
echo "Statistics section replaced from char $startPos to $replaceEnd.\n";
