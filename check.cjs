const fs = require('fs');
const content = fs.readFileSync('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php', 'utf8');

// Find the template x-if="activeTab === 'statistics'"
const startIndex = content.indexOf(`template x-if="activeTab === 'statistics'"`);
if (startIndex === -1) {
    console.log("Template not found");
    process.exit(1);
}

const xDataStart = content.indexOf(`x-data="{`, startIndex);
if (xDataStart === -1) {
    console.log("x-data not found");
    process.exit(1);
}

// Extract the string manually by counting braces
let openBraces = 0;
let extracted = '';
let started = false;

for (let i = xDataStart + 8; i < content.length; i++) {
    const char = content[i];
    if (char === '{') {
        if (!started) started = true;
        openBraces++;
    } else if (char === '}') {
        openBraces--;
    }
    extracted += char;
    
    if (started && openBraces === 0) {
        break;
    }
}

// Prepend the first brace
extracted = '{' + extracted;
fs.writeFileSync('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\extracted.js', extracted);

try {
    const f = new Function('return ' + extracted);
    f();
    console.log("Syntax OK");
} catch (e) {
    console.error("Syntax error in x-data:", e);
}
