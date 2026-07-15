<?php

$targetFile = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$c = file_get_contents($targetFile);

// Replace @json($var) with {!! json_encode($var) !!} inside the statistics template
// We know it's only these specific lines:
$c = str_replace(
    'categoryData: @json($categoryData),',
    'categoryData: {!! json_encode($categoryData) !!},',
    $c
);
$c = str_replace(
    'statusCounts: @json($statusCounts),',
    'statusCounts: {!! json_encode($statusCounts) !!},',
    $c
);
$c = str_replace(
    'plantaCounts: @json($plantaCounts),',
    'plantaCounts: {!! json_encode($plantaCounts) !!},',
    $c
);
$c = str_replace(
    'trendMonths: @json($trendMonths),',
    'trendMonths: {!! json_encode($trendMonths) !!},',
    $c
);
$c = str_replace(
    'trendData: @json($trendData),',
    'trendData: {!! json_encode($trendData) !!},',
    $c
);
$c = str_replace(
    'trendClosedData: @json($trendClosedData),',
    'trendClosedData: {!! json_encode($trendClosedData) !!},',
    $c
);

file_put_contents($targetFile, $c);
echo "Fixed JSON encoding successfully.\n";
