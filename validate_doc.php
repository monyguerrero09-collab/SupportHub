<?php
$html = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\document-viewer.blade.php');
libxml_use_internal_errors(true);
$doc = new DOMDocument();
// Wrap in a div so it has a single root, and suppress warnings
$result = $doc->loadHTML('<?xml encoding="utf-8" ?><div>' . $html . '</div>');

$errors = libxml_get_errors();
if (empty($errors)) {
    echo "HTML IS PERFECTLY VALID.\n";
} else {
    echo "HTML VALIDATION ERRORS:\n";
    foreach ($errors as $error) {
        if ($error->code == 801 || $error->code == 68) continue;
        echo "- Line {$error->line}: {$error->message} ({$error->code})\n";
    }
}
libxml_clear_errors();
