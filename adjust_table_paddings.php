<?php

$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

// Replace header paddings
$content = str_replace(
    'px-6 md:px-10 py-5 md:py-7 text-[10px]',
    'px-4 md:px-5 py-4 md:py-5 text-[9px]',
    $content
);

// Replace cell paddings
$content = str_replace(
    'px-10 py-8',
    'px-4 md:px-5 py-4',
    $content
);

// Reduce button padding
$content = str_replace(
    'px-3.5 py-2 rounded-xl text-[9px]',
    'px-2.5 py-1.5 rounded-lg text-[8.5px]',
    $content
);
$content = str_replace(
    'px-4 py-2 rounded-xl text-[9px]',
    'px-2.5 py-1.5 rounded-lg text-[8.5px]',
    $content
);

// Also change the table class to enforce reasonable min-width that still fits inside a normal screen
// We removed min-w-[1100px] before. Let's make sure white-space is normal to allow text wrapping
$content = str_replace(
    '<table class="w-full text-left border-collapse">',
    '<table class="w-full text-left border-collapse min-w-[800px] md:min-w-0" style="table-layout: auto;">',
    $content
);

file_put_contents($file, $content);
echo "OK";
