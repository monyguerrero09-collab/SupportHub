const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let c = fs.readFileSync(file, 'utf8');

c = c.replace('<script>\n    window.statisticsDashboardData', '@script\n<script>\n    window.statisticsDashboardData');
c = c.replace('    };\n</script>\n</div>', '    };\n</script>\n@endscript\n</div>');

fs.writeFileSync(file, c);
console.log('Added @script directives');
