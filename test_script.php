<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $component = app(Livewire\LivewireManager::class)->test(\App\Livewire\DashboardCharts::class);
    echo "Component tested successfully\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
} catch (\Error $e) {
    echo "Error: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
}
