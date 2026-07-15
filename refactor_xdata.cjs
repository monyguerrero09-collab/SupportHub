const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let content = fs.readFileSync(file, 'utf8');

// The problematic x-data starts with `x-data='{` and ends with `}'`
// Let's find it.
const startStr = `x-data='{`;
const startIndex = content.indexOf(startStr);

if (startIndex === -1) {
    console.error("Start not found");
    process.exit(1);
}

// Find the end of the x-data attribute
const endStr = `}'`;
const endIndex = content.indexOf(endStr, startIndex);

if (endIndex === -1) {
    console.error("End not found");
    process.exit(1);
}

// The Alpine object string without the x-data=' and '
const alpineObjectStr = content.substring(startIndex + 8, endIndex + 1); // +8 for `x-data='`

// Replace the x-data attribute with a function call
let newContent = content.substring(0, startIndex) + `x-data="statisticsDashboardData()"` + content.substring(endIndex + 2); // +2 for `}'`

// Append the script block defining the function right before the </template> for statistics
const templateEndStr = `</template>`;
// We need to find the </template> that corresponds to statistics.
// Start looking from the replaced position
const templateEndIndex = newContent.indexOf(templateEndStr, startIndex);

const scriptBlock = `
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('statisticsDashboardData', () => (${alpineObjectStr}));
    });
</script>
`;

newContent = newContent.substring(0, templateEndIndex) + scriptBlock + newContent.substring(templateEndIndex);

fs.writeFileSync(file, newContent);
console.log("Refactored x-data into script tag successfully!");
