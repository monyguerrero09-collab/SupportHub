const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let content = fs.readFileSync(file, 'utf8');

const startStr = `<template x-if="activeTab === 'statistics'">`;
const startIndex = content.indexOf(startStr);

if (startIndex === -1) {
    console.error("Start not found");
    process.exit(1);
}

const endStr = `</template>`;
const usersTabStr = `{{-- Users Tab --}}`;
let usersIndex = content.indexOf(usersTabStr, startIndex);
if (usersIndex === -1) usersIndex = content.indexOf(`activeTab === 'users'`, startIndex);

let endIndex = -1;
if (usersIndex !== -1) {
    endIndex = content.lastIndexOf(endStr, usersIndex);
} else {
    endIndex = content.indexOf(endStr, startIndex);
}

if (endIndex === -1) {
    console.error("End not found");
    process.exit(1);
}

let recovered = fs.readFileSync('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_statistics.blade.php', 'utf8');

// I also need to make sure the root x-data is back to its original state. 
// Actually, earlier I did `git checkout` so the root x-data is ALREADY perfectly valid!
// And I also need to make sure the x-data of the statistics template doesn't break due to double quotes.
// The recovered_statistics.blade.php uses:
// x-data="{ tickets: @php echo json_encode($allTicketsForJs, 15, 512) @endphp, ... }"
// Let's replace that with a safer approach!
// We can just keep it as is, BUT replace double quotes with single quotes in the x-data attribute!
recovered = recovered.replace(/x-data="\{/, "x-data='{");
// Find the closing brace of x-data block (it ends at line 440)
// The end is }">
recovered = recovered.replace(/\}"\>/, "}'\>");

// Also replace @php echo json_encode(...) @endphp with @json() to be proper blade
recovered = recovered.replace(/@php echo json_encode\(\$allTicketsForJs, 15, 512\) @endphp/, "@js($allTicketsForJs)");
// @js() creates a safe JS object without quotes, which is perfect for single-quoted x-data attribute!

let newContent = content.substring(0, startIndex) + recovered + content.substring(endIndex + endStr.length);

fs.writeFileSync(file, newContent);
console.log("Restored successfully!");
