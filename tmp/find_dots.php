<?php
$img = imagecreatefrompng("c:/Users/monyg/OneDrive/Documentos/ticket_plataforma/public/img/layout_planta1_final.png");
if (!$img) die("Failed to load image");

$width = imagesx($img);
$height = imagesy($img);

$dots = [];
for ($y = 0; $y < $height; $y += 5) {
    for ($x = 0; $x < $width; $x += 5) {
        $rgb = imagecolorat($img, $x, $y);
        $r = ($rgb >> 16) & 0xFF;
        $g = ($rgb >> 8) & 0xFF;
        $b = $rgb & 0xFF;
        
        // Pure red dots are probably 255, 0, 0
        if ($r > 200 && $g <= 30 && $b <= 30) {
            $dots[] = ["x" => $x, "y" => $y];
        }
    }
}

// Cluster the dots
$clusters = [];
foreach ($dots as $dot) {
    $found = false;
    foreach ($clusters as &$cluster) {
        $cx = $cluster['sum_x'] / $cluster['count'];
        $cy = $cluster['sum_y'] / $cluster['count'];
        if (abs($dot['x'] - $cx) < 50 && abs($dot['y'] - $cy) < 50) {
            $cluster['sum_x'] += $dot['x'];
            $cluster['sum_y'] += $dot['y'];
            $cluster['count']++;
            $found = true;
            break;
        }
    }
    if (!$found) {
        $clusters[] = [
            'sum_x' => $dot['x'],
            'sum_y' => $dot['y'],
            'count' => 1
        ];
    }
}

foreach ($clusters as $i => $cluster) {
    if ($cluster['count'] > 5) { // Filter out random small red spots
        $cx = $cluster['sum_x'] / $cluster['count'];
        $cy = $cluster['sum_y'] / $cluster['count'];
        $px = round(($cx / $width) * 100, 2);
        $py = round(($cy / $height) * 100, 2);
        echo "Cluster $i: X=$px%, Y=$py% (Count: {$cluster['count']})\n";
    }
}
