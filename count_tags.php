<?php
$c = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\render_utf8.html');
$start = strpos($c, '<template x-if="activeTab === \'statistics\'">');
$end = strpos($c, '</template>', $start);
$block = substr($c, $start, $end - $start);

$openDivs = substr_count($block, '<div');
$closeDivs = substr_count($block, '</div');
$openSecs = substr_count($block, '<section');
$closeSecs = substr_count($block, '</section');
$openSpans = substr_count($block, '<span');
$closeSpans = substr_count($block, '</span');

echo "Divs: $openDivs / $closeDivs\n";
echo "Sections: $openSecs / $closeSecs\n";
echo "Spans: $openSpans / $closeSpans\n";
