const fs = require('fs');
const path = require('path');
const readline = require('readline');

const brainDir = 'C:\\Users\\monyg\\.gemini\\antigravity\\brain\\';

async function searchAll() {
    const dirs = fs.readdirSync(brainDir).filter(d => fs.statSync(path.join(brainDir, d)).isDirectory());
    for (const d of dirs) {
        const transcriptPath = path.join(brainDir, d, '.system_generated', 'logs', 'transcript.jsonl');
        if (fs.existsSync(transcriptPath)) {
            const fileStream = fs.createReadStream(transcriptPath);
            const rl = readline.createInterface({ input: fileStream, crlfDelay: Infinity });

            for await (const line of rl) {
                if (line.includes('simPlant') && line.includes('multi_replace_file_content')) {
                    const obj = JSON.parse(line);
                    if (obj.tool_calls) {
                        for (let t of obj.tool_calls) {
                            if (t.name === 'multi_replace_file_content' || t.name === 'replace_file_content') {
                                console.log(`Found in conversation ${d} step ${obj.step_index}`);
                                fs.writeFileSync(`c:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\recovered_${d}_${obj.step_index}.json`, JSON.stringify(t.args, null, 2));
                            }
                        }
                    }
                }
            }
        }
    }
}
searchAll();
