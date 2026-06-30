const getPixels = require('get-pixels');
const fs = require('fs');

getPixels('../public/img/layout_planta1_final.png', function(err, pixels) {
    if (err) {
        console.error('Bad image path', err);
        return;
    }
    const w = pixels.shape[0];
    const h = pixels.shape[1];
    const redPixels = [];
    
    // Look for pure red pixels
    for (let y = 0; y < h; y++) {
        for (let x = 0; x < w; x++) {
            const r = pixels.get(x, y, 0);
            const g = pixels.get(x, y, 1);
            const b = pixels.get(x, y, 2);
            if (r > 150 && g < 100 && b < 100 && r > g * 2 && r > b * 2) {
                redPixels.push({x, y});
            }
        }
    }
    
    // Cluster pixels
    const dots = [];
    const threshold = 15; // distance
    for (let p of redPixels) {
        let found = false;
        for (let dot of dots) {
            const dx = dot.x - p.x;
            const dy = dot.y - p.y;
            if (Math.sqrt(dx*dx + dy*dy) < threshold) {
                dot.points.push(p);
                dot.x = (dot.x * (dot.points.length - 1) + p.x) / dot.points.length;
                dot.y = (dot.y * (dot.points.length - 1) + p.y) / dot.points.length;
                found = true;
                break;
            }
        }
        if (!found) {
            dots.push({x: p.x, y: p.y, points: [p]});
        }
    }
    
    // Sort by size
    dots.sort((a,b) => b.points.length - a.points.length);
    
    // We want the top 23
    let bestDots = dots.slice(0, 23);
    
    // Let's also include some logging for the top 30
    for(let i=0; i<30; i++) {
        if(dots[i]) console.log(`Dot ${i+1}: size=${dots[i].points.length}, pctX=${(dots[i].x / w * 100).toFixed(1)}%, pctY=${(dots[i].y / h * 100).toFixed(1)}%`);
    }

    // Sort the 23 best dots by Y then X for a nice top-to-bottom layout
    bestDots.sort((a,b) => a.y - b.y);

    const result = [];
    for (let i = 0; i < bestDots.length; i++) {
        const dot = bestDots[i];
        const pctX = (dot.x / w * 100).toFixed(1);
        const pctY = (dot.y / h * 100).toFixed(1);
        result.push(`['top' => '${pctY}%', 'left' => '${pctX}%']`);
    }
    fs.writeFileSync('dots_23.txt', '[\n    ' + result.join(',\n    ') + '\n]');
});
