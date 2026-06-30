const getPixels = require('get-pixels');

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
            if (r > 150 && g < 80 && b < 80) {
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
    
    console.log(`Found ${dots.length} dots.`);
    dots.sort((a,b) => a.y - b.y);
    const result = { dots: [] };
    for (let i = 0; i < dots.length; i++) {
        const dot = dots[i];
        const pctX = (dot.x / w * 100).toFixed(1);
        const pctY = (dot.y / h * 100).toFixed(1);
        result.dots.push({top: pctY + '%', left: pctX + '%'});
    }
    console.log(JSON.stringify(result, null, 2));
});
