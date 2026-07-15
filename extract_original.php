<?php
$content = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php');
$start = strpos($content, '<template x-if="activeTab === \'statistics\'">');
$end = strpos($content, '</template>', $start) + 11;
file_put_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\original_stats.html', substr($content, $start, $end - $start));
echo "Extracted original stats";
