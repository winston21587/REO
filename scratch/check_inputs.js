const fs = require('fs');
const readline = require('readline');
const rl = readline.createInterface({
  input: fs.createReadStream('C:/Users/janus/.gemini/antigravity-ide/brain/75331234-b238-4612-9357-e6859cfda27f/.system_generated/logs/transcript.jsonl'),
  crlfDelay: Infinity
});
const userInputs = [];
rl.on('line', (line) => {
  if (line.includes('"USER_INPUT"')) {
    try {
      const parsed = JSON.parse(line);
      userInputs.push(parsed.content);
    } catch(e) {}
  }
});
rl.on('close', () => {
  console.log('--- Last 6 user inputs ---');
  userInputs.slice(-6).forEach((input, i) => {
    console.log(`\n=== Input ${i} ===\n`, input);
  });
});
