<?php
$content = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php');
$startIdx = strpos($content, '<template x-if="activeTab === \'statistics\'">');
$endIdx = strpos($content, '</template>', $startIdx);

if ($startIdx !== false && $endIdx !== false) {
    $statisticsBlock = substr($content, $startIdx, $endIdx - $startIdx);
    $opens = substr_count(strtolower($statisticsBlock), '<div');
    $closes = substr_count(strtolower($statisticsBlock), '</div');
    echo "Statistics Div Opens: $opens\n";
    echo "Statistics Div Closes: $closes\n";
    echo "Statistics Div Balance: " . ($opens - $closes) . "\n";
} else {
    echo "Template not found\n";
}
