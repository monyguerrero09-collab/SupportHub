const fs = require('fs');
const readline = require('readline');
const path = 'C:\\Users\\monyg\\.gemini\\antigravity\\brain\\6b2fbe8e-05d4-4143-956f-18d1010a2557\\.system_generated\\logs\\transcript.jsonl';

async function extractViewFile() {
    const fileStream = fs.createReadStream(path);
    const rl = readline.createInterface({ input: fileStream, crlfDelay: Infinity });
    let count = 0;

    for await (const line of rl) {
        const obj = JSON.parse(line);
        if (obj.type === 'TOOL_RESPONSE' && obj.name === 'view_file') {
            if (obj.output && obj.output.includes('support-hub.blade.php')) {
                fs.writeFileSync(`c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_view_${obj.step_index}.txt`, obj.output);
                count++;
            }
        }
    }
    console.log(`Recovered ${count} view_file outputs.`);
}
extractViewFile();
