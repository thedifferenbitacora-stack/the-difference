const fs = require('fs');
const path = require('path');

const timestamp = new Date().toISOString().replace(/[:.]/g, '-').slice(0, 19);
const backupDir = path.join(__dirname, 'backups', `snapshot_${timestamp}`);

// Crear el directorio de backups
if (!fs.existsSync(backupDir)) {
    fs.mkdirSync(backupDir, { recursive: true });
}

// Lista de archivos raíz a respaldar
const filesToCopy = ['BITACORA_GENERAL.md', 'GESTION_NODO.md', 'mcp-config.json'];

// Copiar archivos
filesToCopy.forEach(file => {
    const source = path.join(__dirname, '..', file);
    if (fs.existsSync(source)) {
        fs.copyFileSync(source, path.join(backupDir, file));
    }
});

// Copiar carpeta src/pages
const sourcePages = path.join(__dirname, '..', 'src', 'pages');
if (fs.existsSync(sourcePages)) {
    const destPages = path.join(backupDir, 'src', 'pages');
    fs.mkdirSync(path.join(backupDir, 'src'), { recursive: true });
    fs.cpSync(sourcePages, destPages, { recursive: true });
}

console.log(`SNAPSHOT_SUCCESS: Backup guardado en ${backupDir}`);