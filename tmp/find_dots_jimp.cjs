const Jimp = require('jimp');

Jimp.read('../public/img/layout_planta1_final.png')
  .then(image => {
    const w = image.bitmap.width;
    const h = image.bitmap.height;
    const redPixels = [];
    
    // Look for pure red pixels
    for (let y = 0; y < h; y++) {
      for (let x = 0; x < w; x++) {
        const hex = image.getPixelColor(x, y);
        const rgba = Jimp.intToRGBA(hex);
        if (rgba.r > 150 && rgba.g < 80 && rgba.b < 80) {
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
  })
  .catch(err => {
    console.error(err);
  });
