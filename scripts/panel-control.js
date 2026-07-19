import express from 'express';
import { exec, spawn } from 'child_process';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, '..');
const publicDir = path.join(rootDir, 'public');

const app = express();
const PORT = 3000;

let astroProcess = null;

app.use(express.static(publicDir));
app.use(express.json());

// ESTADO INTELIGENTE: Verifica si el puerto 4321 realmente responde
app.get('/api/status', async (req, res) => {
  try {
    // Intenta conectarse al sitio de Astro
    const response = await fetch('http://localhost:4321', { method: 'HEAD' });
    // Si responde (aunque sea 404), el servidor está vivo
    res.json({ running: true, url: 'http://localhost:4321' });
  } catch (error) {
    // Si no puede conectar, está muerto
    res.json({ running: false, url: 'http://localhost:4321' });
  }
});

// Iniciar servidor Astro
app.post('/api/start', (req, res) => {
  if (astroProcess) {
    return res.json({ success: false, message: 'Ya está corriendo' });
  }
  
  astroProcess = spawn('npm', ['run', 'dev'], { 
    cwd: rootDir,
    shell: true
  });

  astroProcess.stdout.on('data', (data) => console.log(`[Astro] ${data}`));
  astroProcess.stderr.on('data', (data) => console.error(`[Astro Error] ${data}`));
  
  astroProcess.on('close', (code) => {
    astroProcess = null;
    console.log(`Servidor Astro detenido con código ${code}`);
  });

  res.json({ success: true, message: 'Iniciando...' });
});

// PROTOCOLO DE CIERRE: Respaldo + Apagado Limpio
app.post('/api/backup-and-shutdown', (req, res) => {
  res.json({ success: true, message: 'Iniciando protocolo de respaldo y apagado...' });

  // 1. Ejecutar el backup consciente primero
  exec('npm run backup', { cwd: rootDir }, (error, stdout, stderr) => {
    console.log('✅ Respaldo de memoria completado.');
    
    // 2. Apagar el servidor Astro de forma limpia
    if (astroProcess) {
      astroProcess.kill('SIGINT'); // Señal de apagado suave
      astroProcess = null;
      console.log(' Servidor Astro entrando en estado de reposo.');
    } else {
      // Si el panel no lo inició, forzamos el cierre del puerto 4321
      console.log(' Cerrando procesos en puerto 4321...');
      exec('for /f "tokens=5" %a in (\'netstat -aon ^| findstr :4321 ^| findstr LISTENING\') do taskkill /F /PID %a', () => {
        console.log('Puerto 4321 liberado.');
      });
    }
  });
});

app.listen(PORT, () => {
  console.log(`\n🎛️  PANEL DE CONTROL ACTIVO`);
  console.log(` Abre tu navegador en: http://localhost:${PORT}/control-panel.html\n`);
});