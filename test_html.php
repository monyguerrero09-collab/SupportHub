<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

Auth::loginUsingId(1);
request()->server->set('REQUEST_URI', '/supporthub');
$html = \Livewire\Livewire::mount('support-hub');
file_put_contents('render_utf8.html', (string)$html);
echo "HTML dumped.\n";
