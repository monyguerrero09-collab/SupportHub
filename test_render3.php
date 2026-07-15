<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // We must mock the request as we are in CLI
    request()->server->set('REQUEST_URI', '/supporthub');
    
    Auth::loginUsingId(1);
    // Mount the component
    $html = \Livewire\Livewire::mount('support-hub');
    echo "RENDER SUCCESSFUL!\n";
    // Check if the word "Estadísticas" is in the output
    if (strpos((string)$html, 'Gestión de Usuarios') !== false) {
        echo "Found 'Gestión de Usuarios' in HTML!\n";
    } else {
        echo "MISSING 'Gestión de Usuarios' IN HTML!\n";
    }
} catch (\Throwable $e) {
    echo "RUNTIME ERROR:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
