<?php
$lines = file('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php');
$divCount = 0;
foreach ($lines as $lineNum => $line) {
    $opens = substr_count(strtolower($line), '<div');
    $closes = substr_count(strtolower($line), '</div');
    $divCount += $opens - $closes;
    if ($divCount < 0) {
        echo "Negative div balance at line " . ($lineNum + 1) . "\n";
    }
}
echo "Final Div Balance: " . $divCount . "\n";
