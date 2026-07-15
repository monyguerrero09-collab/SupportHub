<?php
$content = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php');
$startIdx = strpos($content, '<template x-if="activeTab === \'users\'">');
$endIdx = strpos($content, '</template>', $startIdx);

if ($startIdx !== false && $endIdx !== false) {
    $usersBlock = substr($content, $startIdx, $endIdx - $startIdx);
    $opens = substr_count(strtolower($usersBlock), '<div');
    $closes = substr_count(strtolower($usersBlock), '</div');
    echo "Users Div Opens: $opens\n";
    echo "Users Div Closes: $closes\n";
    echo "Users Div Balance: " . ($opens - $closes) . "\n";
} else {
    echo "Template not found\n";
}
