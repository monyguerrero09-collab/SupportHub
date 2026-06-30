<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    \Illuminate\Support\Facades\Auth::login(\App\Models\User::first());
    $component = app(Livewire\LivewireManager::class)->test(\App\Livewire\DashboardCharts::class);
    echo "Component tested successfully\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . " at " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo $e->getTraceAsString();
}
