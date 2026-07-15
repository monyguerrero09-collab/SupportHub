const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let content = fs.readFileSync(file, 'utf8');

const target = `x-data="{`;
const startIndex = content.indexOf(target);
if (startIndex === -1) {
    console.error("Target not found");
    process.exit(1);
}

// Find matching closing brace
let openBraces = 0;
let endIndex = -1;
let started = false;

for (let i = startIndex + 7; i < content.length; i++) {
    if (content[i] === '{') {
        started = true;
        openBraces++;
    } else if (content[i] === '}') {
        openBraces--;
    }
    
    if (started && openBraces === 0) {
        endIndex = i;
        break;
    }
}

if (endIndex === -1) {
    console.error("Could not find matching brace");
    process.exit(1);
}

const objContent = content.substring(startIndex + 7, endIndex + 1);

const replacement = `x-data="statisticsDashboardData()"`;
const scriptToAppend = `
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('statisticsDashboardData', () => (${objContent}));
    });
</script>
`;

// We will append the script right before </template> for statistics tab
// Let's find </template> after endIndex
const templateEndIndex = content.indexOf('</template>', endIndex);

if (templateEndIndex === -1) {
    console.error("Could not find </template>");
    process.exit(1);
}

// Replace in content
let newContent = content.substring(0, startIndex) + replacement + content.substring(endIndex + 1, templateEndIndex) + scriptToAppend + content.substring(templateEndIndex);

fs.writeFileSync(file, newContent);
console.log("Success");
