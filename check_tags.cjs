const fs = require('fs');
const html = fs.readFileSync('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php', 'utf8');

let tags = [];
const regex = /<\/?([a-zA-Z0-9]+)[^>]*>/g;
let match;
const selfClosing = ['input', 'img', 'br', 'hr', 'meta', 'link', 'svg', 'path', 'circle', 'rect'];

let inPhp = false;
let lines = html.split('\n');

console.log("Checking tags roughly...");
let divCount = 0;
let templateCount = 0;

while ((match = regex.exec(html)) !== null) {
    let tag = match[1].toLowerCase();
    let isClosing = match[0].startsWith('</');
    
    if (selfClosing.includes(tag)) continue;
    
    if (tag === 'div') {
        if (isClosing) divCount--;
        else divCount++;
    }
    if (tag === 'template') {
        if (isClosing) templateCount--;
        else templateCount++;
    }
}

console.log("Div Balance:", divCount);
console.log("Template Balance:", templateCount);

