const fs = require('fs');
const readline = require('readline');
const path = 'C:\\Users\\monyg\\.gemini\\antigravity\\brain\\6b2fbe8e-05d4-4143-956f-18d1010a2557\\.system_generated\\logs\\transcript.jsonl';

async function processLineByLine() {
    const fileStream = fs.createReadStream(path);
    const rl = readline.createInterface({ input: fileStream, crlfDelay: Infinity });

    for await (const line of rl) {
        if (line.includes('simPlant')) {
            const obj = JSON.parse(line);
            if (obj.tool_calls) {
                console.log("Found in tool calls step: " + obj.step_index);
                for (let t of obj.tool_calls) {
                    if (t.name === 'write_to_file' || t.name === 'multi_replace_file_content' || t.name === 'replace_file_content') {
                        fs.writeFileSync('c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_tool_' + obj.step_index + '.json', JSON.stringify(t.args, null, 2));
                    }
                }
            }
        }
    }
}
processLineByLine();
