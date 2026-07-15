<?php
$bladePath = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$c = file_get_contents($bladePath);

$startMarker = '<template x-if="activeTab === \'statistics\'">';
$endMarker = '{{-- Users Tab --}}';

$startIdx = strpos($c, $startMarker);
$endIdx = strpos($c, $endMarker);

if ($startIdx === false || $endIdx === false) {
    echo "Markers not found!\n";
    exit(1);
}

// Load my new recovered HTML
$newHtml = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_statistics_new.html');

$c = substr_replace($c, $newHtml . "\n            ", $startIdx, $endIdx - $startIdx);
file_put_contents($bladePath, $c);
echo "Replaced properly.\n";
