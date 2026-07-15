const fs = require('fs');

const compiledViewPath = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\storage\\framework\\views\\6fb9b626dc4edf2a8f2167994aa1f434.php';
let compiled = fs.readFileSync(compiledViewPath, 'utf8');

// Find the start of the statistics template
const startStr = `<template x-if="activeTab === 'statistics'">`;
const startIndex = compiled.indexOf(startStr);
if (startIndex === -1) {
    console.error("Start not found");
    process.exit(1);
}

// Find the end of the statistics template
const endStr = `</template>`;
// The template ends right before the Users Tab
const usersTabStr = `{{-- Users Tab --}}`;
let usersIndex = compiled.indexOf(usersTabStr, startIndex);
if (usersIndex === -1) usersIndex = compiled.indexOf(`activeTab === 'users'`, startIndex);

let endIndex = -1;
if (usersIndex !== -1) {
    endIndex = compiled.lastIndexOf(endStr, usersIndex);
} else {
    endIndex = compiled.indexOf(endStr, startIndex);
}

if (endIndex === -1) {
    console.error("End not found");
    process.exit(1);
}

let extracted = compiled.substring(startIndex, endIndex + endStr.length);

// Basic decompilation of blade tags
extracted = extracted.replace(/<\?php echo e\((.*?)\); \?>/g, '{{ $1 }}');
extracted = extracted.replace(/<\?php echo (.*?); \?>/g, '{!! $1 !!}');
extracted = extracted.replace(/<\?php if\((.*?)\): \?>/g, '@if($1)');
extracted = extracted.replace(/<\?php elseif\((.*?)\): \?>/g, '@elseif($1)');
extracted = extracted.replace(/<\?php else: \?>/g, '@else');
extracted = extracted.replace(/<\?php endif; \?>/g, '@endif');
extracted = extracted.replace(/<\?php foreach\((.*?) as (.*?)\): \?>/g, '@foreach($1 as $2)');
extracted = extracted.replace(/<\?php endforeach; \?>/g, '@endforeach');
extracted = extracted.replace(/<\?php/g, '@php');
extracted = extracted.replace(/\?>/g, '@endphp');

fs.writeFileSync('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_statistics.blade.php', extracted);
console.log("Successfully extracted and partially decompiled statistics tab!");
