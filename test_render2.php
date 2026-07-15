<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $component = app(\App\Livewire\SupportHub::class);
    // Call render and explicitly capture exceptions
    $view = $component->render();
    echo $view->render();
    echo "\nRENDER SUCCESSFUL!\n";
} catch (\Throwable $e) {
    echo "RUNTIME ERROR:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
