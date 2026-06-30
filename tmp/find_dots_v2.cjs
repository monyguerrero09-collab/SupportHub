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
    
    // Look for pure red pixels (adjusting thresholds)
    for (let y = 0; y < h; y++) {
        for (let x = 0; x < w; x++) {
            const r = pixels.get(x, y, 0);
            const g = pixels.get(x, y, 1);
            const b = pixels.get(x, y, 2);
            // Strong red: R > 150, G < 100, B < 100, R > G*2, R > B*2
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
    
    console.log(`Found ${dots.length} dots total.`);
    
    // Filter by dot size (number of pixels) to avoid small noise or huge patches
    // Sort by number of pixels descending
    dots.sort((a,b) => b.points.length - a.points.length);
    
    // output top 30 dots with their size
    for(let i=0; i<Math.min(30, dots.length); i++) {
        const dot = dots[i];
        const pctX = (dot.x / w * 100).toFixed(1);
        const pctY = (dot.y / h * 100).toFixed(1);
        console.log(`Dot ${i+1}: size=${dot.points.length}, pctY=${pctY}%, pctX=${pctX}%`);
    }

    const goodDots = dots.filter(d => d.points.length > 5 && d.points.length < 500);
    console.log(`\nFiltered down to ${goodDots.length} dots based on size (5 to 500 pixels).`);
    
    // Sort by Y for readability
    goodDots.sort((a,b) => a.y - b.y);

    const result = { positions: [] };
    for (let i = 0; i < goodDots.length; i++) {
        const dot = goodDots[i];
        const pctX = (dot.x / w * 100).toFixed(1);
        const pctY = (dot.y / h * 100).toFixed(1);
        result.positions.push(`['top' => '${pctY}%', 'left' => '${pctX}%']`);
    }
    fs.writeFileSync('dots_result.txt', result.positions.join(',\n'));
    console.log('Saved to dots_result.txt');
});
