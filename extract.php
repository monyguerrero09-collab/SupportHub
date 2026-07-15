<?php
$c = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php');
if (preg_match('/@script\s*<script>(.*?)<\/script>/s', $c, $matches)) {
    // Replace Blade directives with empty strings or JS valid syntax just for JS syntax checking
    $js = preg_replace('/@js\((.*?)\)/', '[]', $matches[1]);
    $js = preg_replace('/\{\{.*?\}\}/', '""', $js);
    file_put_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\test_script.js', $js);
    echo "Extracted successfully";
} else {
    echo "Not found";
}
