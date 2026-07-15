<?php
$html = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\render_utf8.html');
$start = strpos($html, '<template x-if="activeTab === \'statistics\'">');
$end = strpos($html, '</template>', $start) + 11;
$block = substr($html, $start, $end - $start);

libxml_use_internal_errors(true);
$doc = new DOMDocument();
// Wrap in a div so it has a single root, and suppress warnings
$result = $doc->loadHTML('<?xml encoding="utf-8" ?><div>' . $block . '</div>');

$errors = libxml_get_errors();
if (empty($errors)) {
    echo "HTML IS PERFECTLY VALID.\n";
} else {
    echo "HTML VALIDATION ERRORS:\n";
    foreach ($errors as $error) {
        echo "- Line {$error->line}: {$error->message}\n";
    }
}
libxml_clear_errors();
