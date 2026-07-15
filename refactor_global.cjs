const fs = require('fs');
const file = 'c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\resources\\views\\livewire\\support-hub.blade.php';
let c = fs.readFileSync(file, 'utf8');

// The current format is:
// <script>
//     document.addEventListener('alpine:init', () => {
//         Alpine.data('statisticsDashboardData', () => ({
//                            tickets: @js($allTicketsForJs),
// ...
//                       }));
//     });
// </script>

const startRegex = /<script>\s*document\.addEventListener\('alpine:init', \(\) => {\s*Alpine\.data\('statisticsDashboardData', \(\) => \(\{/;
const matchStart = c.match(startRegex);

if (matchStart) {
    c = c.replace(matchStart[0], "<script>\n    window.statisticsDashboardData = function() {\n        return {");
    
    // Now we need to replace the ending:
    //                       }));
    //     });
    // </script>
    const endRegex = /\}\)\);\s*\}\);\s*<\/script>/;
    const matchEnd = c.match(endRegex);
    if (matchEnd) {
        c = c.replace(matchEnd[0], "        };\n    };\n</script>");
        fs.writeFileSync(file, c);
        console.log("Refactored to global function successfully!");
    } else {
        console.log("End regex not found!");
    }
} else {
    console.log("Start regex not found!");
}
