<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $hub = new App\Livewire\SupportHub();
    // Simulate mount to get data
    $hub->mount();
    
    // Call render and explicitly capture output
    $view = $hub->render();
    $html = $view->render();
    
    preg_match('/<div id="stats-json-payload"[^>]*data-payload="([^"]*)"/', $html, $m);
    
    if (isset($m[1])) {
        $decoded = htmlspecialchars_decode($m[1], ENT_QUOTES);
        echo "Decoded JSON:\n";
        echo $decoded . "\n";
        $parsed = json_decode($decoded, true);
        if (json_last_error() === JSON_ERROR_NONE) {
            echo "JSON is VALID PHP json_decode!\n";
        } else {
            echo "JSON ERROR: " . json_last_error_msg() . "\n";
        }
    } else {
        echo "Could not find stats-json-payload!\n";
    }
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
