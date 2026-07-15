<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$hub = new App\Livewire\SupportHub();
$blade = $hub->render()->render();
preg_match('/data-payload="([^"]+)"/', $blade, $m);
$json = htmlspecialchars_decode($m[1] ?? '');
echo $json;
