<?php
$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

$startMarker = "<template x-if=\"activeTab === 'statistics'\">";
$startPos = strpos($content, $startMarker);
if ($startPos === false) { die("ERROR: start marker not found\n"); }

// Find closing </template>
$templateEnd = strpos($content, '</template>', $startPos) + strlen('</template>');

// Check if old external script block follows
$peek = substr($content, $templateEnd, 50);
echo "After template peek: " . json_encode($peek) . "\n";

$replaceEnd = $templateEnd;

// Check for old script block right after
$oldScript = strpos($content, "\n<script>", $templateEnd - 5);
if ($oldScript !== false && $oldScript < $templateEnd + 5) {
    $scriptClose = strpos($content, '</script>', $oldScript) + strlen('</script>');
    echo "Found old script from $oldScript to $scriptClose\n";
    $replaceEnd = $scriptClose;
}

echo "Replacing chars $startPos to $replaceEnd\n";

$newBlock = file_get_contents('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\statistics_template.html');

$newContent = substr($content, 0, $startPos) . $newBlock . substr($content, $replaceEnd);
file_put_contents($file, $newContent);
echo "Done! File is now " . strlen($newContent) . " bytes.\n";
