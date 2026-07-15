<?php

$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

// 1. Fix canvas styles and add wire:ignore
$content = str_replace(
    '<div style="position:relative;height:280px;width:100%;display:flex;align-items:center;justify-content:center;">
                <canvas id="ch-trend" style="width:100%;height:100%;"></canvas>
            </div>',
    '<div wire:ignore style="position:relative;height:280px;width:100%;">
                <canvas id="ch-trend"></canvas>
            </div>',
    $content
);

$content = str_replace(
    '<div style="padding:1rem 1.25rem 1.25rem;flex:1;display:flex;align-items:center;justify-content:center;">
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-planta" style="width:100%;height:100%;"></canvas>
                </div>
            </div>',
    '<div style="padding:1rem 1.25rem 1.25rem;flex:1;">
                <div wire:ignore style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-planta"></canvas>
                </div>
            </div>',
    $content
);

$content = str_replace(
    '<div style="padding:1rem 1.25rem 1.25rem;flex:1;display:flex;align-items:center;justify-content:center;">
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-cat" style="width:100%;height:100%;"></canvas>
                </div>
            </div>',
    '<div style="padding:1rem 1.25rem 1.25rem;flex:1;">
                <div wire:ignore style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-cat"></canvas>
                </div>
            </div>',
    $content
);

$content = str_replace(
    '<div style="padding:1rem 1.25rem 1.25rem;flex:1;display:flex;align-items:center;justify-content:center;">
                <div style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-status" style="width:100%;height:100%;"></canvas>
                </div>
            </div>',
    '<div style="padding:1rem 1.25rem 1.25rem;flex:1;">
                <div wire:ignore style="position:relative;height:220px;width:100%;">
                    <canvas id="ch-status"></canvas>
                </div>
            </div>',
    $content
);

// 2. Change the JS to update charts instead of killing them
// This requires rewriting the JS block. I'll replace the block from "kill('ch-trend');" to the end of the 4 charts.

$jsNew = <<<JS
        /* 1. TENDENCIA */
        var c1 = document.getElementById('ch-trend');
        if (c1) {
            if (c1._ch) {
                c1._ch.data.labels = STAT_DATA.months;
                c1._ch.data.datasets[0].data = STAT_DATA.created;
                c1._ch.data.datasets[1].data = STAT_DATA.closed;
                c1._ch.update();
            } else {
                c1._ch = new Chart(c1, {
                    type: 'line',
                    data: {
                        labels: STAT_DATA.months,
                        datasets: [
                            {
                                label: 'Creados', data: STAT_DATA.created, borderColor: '#6366f1',
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
                                label: 'Cerrados', data: STAT_DATA.closed, borderColor: '#10b981',
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
                        plugins: { legend: { labels: { usePointStyle: true, padding: 20, font: { size: 11, weight: 'bold' } } }, tooltip: TIP },
                        scales: { x: { grid: { color: 'rgba(51,65,85,0.2)' } }, y: { grid: { color: 'rgba(51,65,85,0.2)' }, beginAtZero: true } }
                    }
                });
            }
        }

        /* 2. DISTRIBUCIÓN PLANTA */
        var c2 = document.getElementById('ch-planta');
        var pLabels = STAT_DATA.pLabels.length ? STAT_DATA.pLabels : ['Sin datos'];
        var pValues = STAT_DATA.pValues.length ? STAT_DATA.pValues : [0.001]; // Prevents crash
        if (c2) {
            if (c2._ch) {
                c2._ch.data.labels = pLabels;
                c2._ch.data.datasets[0].data = pValues;
                c2._ch.update();
            } else {
                c2._ch = new Chart(c2, {
                    type: 'doughnut',
                    data: { labels: pLabels, datasets: [{ data: pValues, backgroundColor: ['#6366f1','#06b6d4'], borderColor: '#080c1a', borderWidth: 4, hoverOffset: 8 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '72%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }, tooltip: TIP } }
                });
            }
        }

        /* 3. TIPOS DE PROBLEMA */
        var c3 = document.getElementById('ch-cat');
        var cLabels = STAT_DATA.cLabels.length ? STAT_DATA.cLabels : ['Sin datos'];
        var cValues = STAT_DATA.cValues.length ? STAT_DATA.cValues : [0.001];
        if (c3) {
            var bgs = ['rgba(168,85,247,0.75)','rgba(59,130,246,0.75)','rgba(16,185,129,0.75)','rgba(245,158,11,0.75)','rgba(239,68,68,0.75)','rgba(20,184,166,0.75)'];
            if (c3._ch) {
                c3._ch.data.labels = cLabels;
                c3._ch.data.datasets[0].data = cValues;
                c3._ch.update();
            } else {
                c3._ch = new Chart(c3, {
                    type: 'bar',
                    data: { labels: cLabels, datasets: [{ label: 'Tickets', data: cValues, backgroundColor: bgs, borderRadius: 8, barThickness: 22 }] },
                    options: { indexAxis: 'y', responsive: true, maintainAspectRatio: false, plugins: { legend: { display: false }, tooltip: TIP }, scales: { x: { beginAtZero: true } } }
                });
            }
        }

        /* 4. PROPORCIÓN DE ESTADOS */
        var c4 = document.getElementById('ch-status');
        if (c4) {
            if (c4._ch) {
                c4._ch.data.datasets[0].data = [STAT_DATA.sOpen, STAT_DATA.sProc, STAT_DATA.sDone];
                c4._ch.update();
            } else {
                c4._ch = new Chart(c4, {
                    type: 'doughnut',
                    data: { labels: ['Abiertos','En Proceso','Resueltos'], datasets: [{ data: [STAT_DATA.sOpen, STAT_DATA.sProc, STAT_DATA.sDone], backgroundColor: ['#3b82f6','#f59e0b','#10b981'], borderColor: '#080c1a', borderWidth: 4, hoverOffset: 8 }] },
                    options: { responsive: true, maintainAspectRatio: false, cutout: '70%', plugins: { legend: { position: 'bottom', labels: { usePointStyle: true, padding: 16 } }, tooltip: TIP } }
                });
            }
        }
JS;

// Find the section to replace: from "kill('ch-trend');" up to just before the closing "/* Run after page fully loads */"
$startStr = "/* 1. TENDENCIA */";
$endStr = "/* Run after page fully loads */";

$startPos = strpos($content, $startStr);
$endPos = strpos($content, $endStr);

if ($startPos !== false && $endPos !== false) {
    // Also include the kill function removal
    $killFuncStr = "function kill(id) {\n        var el = document.getElementById(id);\n        if (el && el._ch) { el._ch.destroy(); delete el._ch; }\n    }\n\n";
    $content = str_replace($killFuncStr, "", $content);

    // Now recalculate start and end to replace charts
    $startPos = strpos($content, $startStr);
    $endPos = strpos($content, $endStr);
    
    if ($startPos !== false && $endPos !== false) {
        $before = substr($content, 0, $startPos);
        $after = substr($content, $endPos);
        $content = $before . $jsNew . "\n\n    " . $after;
    }
}

file_put_contents($file, $content);
echo "OK";
