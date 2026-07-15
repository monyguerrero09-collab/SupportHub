<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$months = collect();
for ($i = 6; $i >= 0; $i--) {
    $month = now()->subMonths($i);
    $months->push($month->format('M'));
}
echo json_encode($months) . "\n";

$plantaCounts = [
    'Planta 1' => 10,
    'Planta 2' => 20,
];
echo json_encode($plantaCounts) . "\n";
