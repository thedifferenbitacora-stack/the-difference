import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';
import { sendBackupNotification } from '../src/lib/email.js';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, '..');
const backupDir = path.join(rootDir, 'backup');
const snapshotDir = path.join(backupDir, 'snapshots');

async function runBackup() {
  // Formato de fecha seguro para nombres de carpetas (sin : ni .)
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
  const currentSnapshotDir = path.join(snapshotDir, timestamp);

  try {
    console.log('🌱 Iniciando proceso de backup consciente...');
    
    // 1. Crear directorios base si no existen
    await fs.mkdir(snapshotDir, { recursive: true });
    await fs.mkdir(currentSnapshotDir, { recursive: true });

    // 2. Directorios que SÍ queremos respaldar siempre
    const directoriesToBackup = ['memory', 'sources', 'foundation', 'logs'];
    const results = [];

    for (const dir of directoriesToBackup) {
      const src = path.join(backupDir, dir);
      const dest = path.join(currentSnapshotDir, dir);
      try {
        // Verificar si existe antes de copiar
        await fs.access(src);
        await fs.cp(src, dest, { recursive: true });
        results.push(`✅ backup/${dir}`);
      } catch (err) {
        results.push(`⚠️ backup/${dir}: No existe o está vacío (se omitió)`);
      }
    }

    // 3. Manejo consciente de la carpeta 'snapshots'
    // Copiamos los snapshots ANTERIORES, pero NO la carpeta actual que estamos creando
    try {
      const oldSnapshots = await fs.readdir(snapshotDir);
      const historicalSnapshots = oldSnapshots.filter(item => item !== timestamp);
      
      if (historicalSnapshots.length > 0) {
        const destHistorical = path.join(currentSnapshotDir, 'snapshots');
        await fs.mkdir(destHistorical, { recursive: true });
        
        for (const item of historicalSnapshots) {
          const src = path.join(snapshotDir, item);
          const dest = path.join(destHistorical, item);
          await fs.cp(src, dest, { recursive: true });
        }
        results.push(`✅ backup/snapshots (histórico preservado)`);
      } else {
        results.push(`ℹ️ backup/snapshots: Sin historial previo que preservar`);
      }
    } catch (err) {
      results.push(`⚠️ backup/snapshots: ${err.message}`);
    }

    // 4. Reporte y notificación
    const report = results.join('\n');
    console.log('\n' + report);

    console.log('\n📧 Enviando notificación al útero digital (email)...');
    await sendBackupNotification('success', report);
    console.log('✅ Notificación enviada. El ciclo de backup ha concluido.\n');

  } catch (error) {
    console.error('❌ Error crítico en el proceso de backup:', error);
    await sendBackupNotification('error', error.message);
  }
}

runBackup();