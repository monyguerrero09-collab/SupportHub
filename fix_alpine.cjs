const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let c = fs.readFileSync(file, 'utf8');

c = c.replace(
    /window\.statisticsDashboardData = function\(\) \{/g,
    "Alpine.data('statisticsDashboardData', () => ({"
);

c = c.replace(
    /        \};\n    \};\n<\/script>/g,
    "        }));\n</script>"
);

fs.writeFileSync(file, c);
console.log('Fixed Alpine registration');
