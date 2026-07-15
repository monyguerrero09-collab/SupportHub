const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let c = fs.readFileSync(file, 'utf8');

// Find the script tag we added at the end
const regex = /<script>\s*document\.addEventListener\('alpine:init', \(\) => {\s*Alpine\.data\('statisticsDashboardData'[\s\S]*?<\/script>\s*$/;
const match = c.match(regex);

if (match) {
    // Remove it from the very end
    c = c.replace(regex, '');
    
    // Find the very last </div>
    const lastDivIdx = c.lastIndexOf('</div>');
    if (lastDivIdx !== -1) {
        // Insert the script right BEFORE the last </div>
        c = c.substring(0, lastDivIdx) + '\n' + match[0] + '\n</div>' + c.substring(lastDivIdx + 6);
        fs.writeFileSync(file, c);
        console.log('Fixed root element! Script moved inside root div.');
    } else {
        console.log('</div> not found');
    }
} else {
    console.log('script not found at the end of file');
}
