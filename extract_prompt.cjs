const fs = require('fs');
const transcriptPath = 'C:\\Users\\monyg\\.gemini\\antigravity\\brain\\6b2fbe8e-05d4-4143-956f-18d1010a2557\\.system_generated\\logs\\transcript.jsonl';
const lines = fs.readFileSync(transcriptPath, 'utf8').split('\n');
for (const line of lines) {
    if (line.includes('Adapta el siguiente codigo')) {
        const obj = JSON.parse(line);
        if (obj.content) {
            const content = obj.content;
            const startIdx = content.indexOf('<!DOCTYPE html>');
            if (startIdx > -1) {
                fs.writeFileSync('C:\\Users\\monyg\\OneDrive\\Documentos\\ticket_plataforma\\raw_prompt.html', content.substring(startIdx));
                console.log('Successfully saved to raw_prompt.html');
                break;
            }
        }
    }
}
