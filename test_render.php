<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Try to compile and render the blade file directly
    // Since it's a Livewire view, we might need variables. Let's just compile the Blade to PHP
    $path = __DIR__.'/resources/views/livewire/support-hub.blade.php';
    $compiler = app('blade.compiler');
    $compiled = $compiler->compileString(file_get_contents($path));
    
    // Evaluate the compiled PHP to check for syntax errors in the generated PHP!
    $tempFile = tempnam(sys_get_temp_dir(), 'blade_');
    file_put_contents($tempFile, $compiled);
    
    // Check syntax of compiled file
    $output = [];
    $returnVar = 0;
    exec("php -l " . escapeshellarg($tempFile), $output, $returnVar);
    
    if ($returnVar !== 0) {
        echo "SYNTAX ERROR IN COMPILED BLADE:\n";
        echo implode("\n", $output);
    } else {
        echo "Compiled Blade PHP syntax is valid.\n";
    }
    unlink($tempFile);
    
} catch (\Exception $e) {
    echo "ERROR COMILING BLADE: " . $e->getMessage() . "\n";
}
