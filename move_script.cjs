const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let c = fs.readFileSync(file, 'utf8');

const regex = /<script>\s*document\.addEventListener\('alpine:init', \(\) => {\s*Alpine\.data\('statisticsDashboardData'[\s\S]*?<\/script>/;
const match = c.match(regex);

if(match) {
    c = c.replace(match[0], '');
    c += '\n' + match[0];
    fs.writeFileSync(file, c);
    console.log('Moved script to end');
} else {
    console.log('Not found');
}
