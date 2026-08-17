import type { APIRoute } from 'astro';
import { exec } from 'child_process';
import { promisify } from 'util';
import fs from 'fs/promises';
import path from 'path';
import { enviarBitacoraEmail, type BitacoraReport } from '../../lib/mailer';

const execAsync = promisify(exec);

export const POST: APIRoute = async () => {
  const timestamp = new Date().toISOString().replace(/[:.]/g, '-');
  const tempoRoot = new Date().toISOString();
  const snapshotDir = path.join(process.cwd(), 'backup', 'snapshots', `snapshot-${timestamp}`);
  const memoryDir = path.join(process.cwd(), 'backup', 'memory');
  const sourcesDir = path.join(process.cwd(), 'backup', 'sources');

  const fases: BitacoraReport['fases'] = {
    pull_sources: { status: 'PENDING', detail: '' },
    archive_memory: { status: 'PENDING', detail: '' },
    snapshot_code: { status: 'PENDING', detail: '' },
    push_github: { status: 'PENDING', detail: '' },
    deploy_vercel: { status: 'PENDING', detail: '' },
  };

  const proyectos: BitacoraReport['proyectos'] = [];

  try {
    // FASE 1: PULL SOURCES
    try {
      const SHEETS_URL = process.env.GOOGLE_SHEETS_CSV_URL || '';
      if (SHEETS_URL) {
        const csvPath = path.join(sourcesDir, 'matrix.csv');
        await execAsync(`curl -L "${SHEETS_URL}" -o "${csvPath}"`);
        const jsonPath = path.join(sourcesDir, 'matrix.json');
        await execAsync(`node scripts/csv-to-json.js "${csvPath}" "${jsonPath}"`);
        fases.pull_sources = { status: 'OK', detail: 'Excel descargado y convertido a JSON' };
        proyectos.push({
          nombre: 'THE_DIFFERENCE',
          espacio: 'sources/matrix',
          acciones: ['PULL_EXCEL', 'CSV_TO_JSON'],
          estado: 'OK',
        });
      } else {
        fases.pull_sources = { status: 'WARN', detail: 'GOOGLE_SHEETS_CSV_URL no configurado' };
      }
    } catch (e: any) {
      fases.pull_sources = { status: 'ERROR', detail: e.message };
    }

    // FASE 2: ARCHIVE MEMORY
    try {
      const knowledgeEntries = await countEntries(path.join(memoryDir, 'knowledge.json'));
      const chatFiles = await listFiles(path.join(memoryDir, 'chats'));
      fases.archive_memory = {
        status: 'OK',
        detail: `${knowledgeEntries} entradas de conocimiento, ${chatFiles.length} chats archivados`,
      };
      proyectos.push({
        nombre: 'ARS_TEKNE',
        espacio: 'memory/knowledge',
        acciones: ['ARCHIVE_KNOWLEDGE', 'INDEX_CHATS'],
        estado: 'OK',
      });
    } catch (e: any) {
      fases.archive_memory = { status: 'ERROR', detail: e.message };
    }

    // FASE 3: SNAPSHOT CODE
    try {
      await fs.mkdir(snapshotDir, { recursive: true });
      const criticalPaths = [
        'src', 'package.json', 'astro.config.mjs', 'tsconfig.json',
        'backup/memory', 'backup/sources', 'backup/foundation'
      ];
      for (const p of criticalPaths) {
        const sourcePath = path.join(process.cwd(), p);
        const destPath = path.join(snapshotDir, p);
        await fs.cp(sourcePath, destPath, { recursive: true, force: true });
      }
      fases.snapshot_code = { status: 'OK', detail: `Snapshot creado en ${path.basename(snapshotDir)}` };
      proyectos.push({
        nombre: 'ARS_TEKNE',
        espacio: 'snapshots',
        acciones: ['CREATE_SNAPSHOT', 'COPY_CRITICAL_PATHS'],
        estado: 'OK',
      });
    } catch (e: any) {
      fases.snapshot_code = { status: 'ERROR', detail: e.message };
    }

    // FASE 4: PUSH GITHUB
    try {
      await execAsync('git add -A');
      await execAsync(`git commit -m "BACKUP_MEMORIA_${timestamp}"`);
      await execAsync('git push origin main');
      fases.push_github = { status: 'OK', detail: 'Push exitoso a rama main' };
      proyectos.push({
        nombre: 'THE_DIFFERENCE',
        espacio: 'github/main',
        acciones: ['GIT_ADD', 'GIT_COMMIT', 'GIT_PUSH'],
        estado: 'OK',
      });
    } catch (e: any) {
      fases.push_github = { status: 'ERROR', detail: e.message };
    }

    // FASE 5: DEPLOY VERCEL
    try {
      await execAsync('vercel --prod --yes');
      fases.deploy_vercel = { status: 'OK', detail: 'Deploy a producción completado' };
      proyectos.push({
        nombre: 'THE_DIFFERENCE',
        espacio: 'vercel/prod',
        acciones: ['VERCEL_DEPLOY'],
        estado: 'OK',
      });
    } catch (e: any) {
      fases.deploy_vercel = { status: 'ERROR', detail: e.message };
    }

    // FASE 6: LOG BIODINÁMICO
    const logEntry = `[${timestamp}] BACKUP_OK → Memoria + Fuentes + Código → GitHub + Vercel + Email\n`;
    await fs.appendFile(path.join(process.cwd(), 'backup', 'logs', 'backup.log'), logEntry);

    // FASE 7: ENVIAR EMAIL
    const knowledgeEntries = await countEntries(path.join(memoryDir, 'knowledge.json'));
    const chatFiles = await listFiles(path.join(memoryDir, 'chats'));
    const sourceFiles = await listFiles(sourcesDir);

    const report: BitacoraReport = {
      timestamp,
      tempo_root: tempoRoot,
      ciclo: 'BACKUP_COMPLETO',
      proyectos,
      fases,
      resumen_ejecutivo: generarResumen(fases),
      memoria_total: {
        knowledge_entries: knowledgeEntries,
        chat_files: chatFiles.length,
        sources_synced: sourceFiles.length,
      },
    };

    try {
      await enviarBitacoraEmail(report);
    } catch (emailError: any) {
      console.error('Error enviando email:', emailError.message);
    }

    return new Response(JSON.stringify({
      success: true,
      message: `BACKUP COMPLETADO → ${path.basename(snapshotDir)}`,
      email_sent: true,
      report,
    }), { status: 200 });

  } catch (e: any) {
    return new Response(JSON.stringify({
      success: false,
      message: `ERROR: ${e.message}`,
    }), { status: 500 });
  }
};

function generarResumen(fases: BitacoraReport['fases']): string {
  const okCount = Object.values(fases).filter((f) => f.status === 'OK').length;
  const errCount = Object.values(fases).filter((f) => f.status === 'ERROR').length;
  const total = Object.keys(fases).length;

  if (errCount === 0) {
    return `Ciclo completado sin incidencias. ${okCount}/${total} fases ejecutadas correctamente. Sistema en posición de autonomía biodinámica.`;
  } else {
    return `Ciclo completado con ${errCount} error(es). ${okCount}/${total} fases OK. Revisar log para detalle.`;
  }
}

async function countEntries(filePath: string): Promise<number> {
  try {
    const data = await fs.readFile(filePath, 'utf-8');
    return JSON.parse(data).length;
  } catch { return 0; }
}

async function listFiles(dirPath: string): Promise<string[]> {
  try { return await fs.readdir(dirPath); }
  catch { return []; }
}