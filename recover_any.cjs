const fs = require('fs');
const readline = require('readline');
const path = 'C:\\Users\\monyg\\.gemini\\antigravity\\brain\\6b2fbe8e-05d4-4143-956f-18d1010a2557\\.system_generated\\logs\\transcript.jsonl';

async function extractAny() {
    const fileStream = fs.createReadStream(path);
    const rl = readline.createInterface({ input: fileStream, crlfDelay: Infinity });

    for await (const line of rl) {
        if (line.includes('simPlant') && line.includes('TOOL_RESPONSE')) {
            const obj = JSON.parse(line);
            console.log(`Found simPlant in tool response at step ${obj.step_index}`);
            if (obj.output) {
                fs.writeFileSync(`c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_any_${obj.step_index}.txt`, obj.output);
            }
        }
    }
}
extractAny();
