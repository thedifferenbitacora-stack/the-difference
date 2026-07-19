const fs = require('fs');
const path = require('path');

const csvPath = process.argv[2];
const jsonPath = process.argv[3];

if (!csvPath || !jsonPath) {
  console.error('Uso: node csv-to-json.js <archivo.csv> <archivo.json>');
  process.exit(1);
}

try {
  const csv = fs.readFileSync(csvPath, 'utf-8');
  const lines = csv.trim().split('\n');
  
  if (lines.length < 2) {
    console.log('CSV vacío o sin datos');
    fs.writeFileSync(jsonPath, '[]');
    process.exit(0);
  }
  
  const headers = lines[0].split(',').map(h => h.trim().replace(/"/g, ''));
  
  const data = lines.slice(1).map(line => {
    const values = line.split(',').map(v => v.trim().replace(/"/g, ''));
    const obj = {};
    headers.forEach((h, i) => obj[h] = values[i] || '');
    return obj;
  });
  
  fs.writeFileSync(jsonPath, JSON.stringify(data, null, 2));
  console.log(`✓ Convertido ${data.length} filas → ${jsonPath}`);
} catch (error) {
  console.error('Error:', error.message);
  process.exit(1);
}