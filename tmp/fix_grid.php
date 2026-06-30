<?php
$file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
$content = file_get_contents($file);

$content = str_replace(
    'class="flex-1 grid lg:grid-cols-[1fr_400px] gap-8 pb-8 h-full min-h-0"',
    'class="flex-1 flex flex-row gap-6 lg:gap-8 pb-8 h-full min-h-0 min-w-0 w-full"',
    $content
);

$content = str_replace(
    '{{-- LEFT: WHITE INTERACTION CONTAINER --}}
                             <div class="bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-slate-100 p-8 flex flex-col h-full overflow-hidden transition-all duration-300">',
    '{{-- LEFT: WHITE INTERACTION CONTAINER --}}
                             <div class="flex-1 min-w-0 bg-white rounded-[1.5rem] shadow-[0_10px_40px_rgba(0,0,0,0.04)] border border-slate-100 p-6 lg:p-8 flex flex-col h-full overflow-hidden transition-all duration-300">',
    $content
);

$content = str_replace(
    '{{-- RIGHT: DARK TICKET PREVIEW CARD (EXACTLY LIKE IMAGE) --}}
                             <div class="bg-[#0b1221] rounded-[1.5rem] p-8 md:p-10 shadow-[0_20px_50px_rgba(15,23,42,0.3)] flex flex-col h-full overflow-y-auto custom-scrollbar-dark relative border border-[#1e293b]/50 transition-all duration-300 transform"',
    '{{-- RIGHT: DARK TICKET PREVIEW CARD (EXACTLY LIKE IMAGE) --}}
                             <div class="w-[340px] md:w-[380px] lg:w-[420px] shrink-0 bg-[#0b1221] rounded-[1.5rem] p-6 lg:p-10 shadow-[0_20px_50px_rgba(15,23,42,0.3)] flex flex-col h-full overflow-y-auto custom-scrollbar-dark relative border border-[#1e293b]/50 transition-all duration-300 transform"',
    $content
);

file_put_contents($file, $content);
echo "Enforced dual column strictly.";
?>
