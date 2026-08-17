import { google } from 'googleapis';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, '../..');

const CREDENTIALS_PATH = path.join(rootDir, 'client_secret.json');
const TOKEN_PATH = path.join(rootDir, 'token.json');

// Alcance necesario para enviar correos
const SCOPES = ['https://www.googleapis.com/auth/gmail.send'];

async function authorize() {
  const content = await fs.readFile(CREDENTIALS_PATH);
  const credentials = JSON.parse(content);
  const { client_secret, client_id, redirect_uris } = credentials.installed || credentials.web;

  const oAuth2Client = new google.auth.OAuth2(client_id, client_secret, redirect_uris[0]);

  try {
    // Intentar leer el token guardado de sesiones anteriores
    const token = await fs.readFile(TOKEN_PATH);
    oAuth2Client.setCredentials(JSON.parse(token));
    return oAuth2Client;
  } catch (error) {
    // Si no existe, hay que autorizar por primera vez
    console.log('\n⚠️  No se encontró token. Ejecuta: npm run authorize');
    throw new Error('Necesitas autorizar primero');
  }
}

export async function sendEmail(to, subject, html) {
  try {
    const auth = await authorize();
    const gmail = google.gmail({ version: 'v1', auth });

    const emailLines = [
      `To: ${to}`,
      `Subject: ${subject}`,
      'MIME-Version: 1.0',
      'Content-Type: text/html; charset=utf-8',
      '',
      html
    ];
    const message = emailLines.join('\r\n');
    const encodedMessage = Buffer.from(message).toString('base64').replace(/\+/g, '-').replace(/\//g, '_').replace(/=+$/, '');

    const res = await gmail.users.messages.send({
      userId: 'me',
      requestBody: {
        raw: encodedMessage,
      },
    });

    console.log(`✅ Email enviado exitosamente. ID: ${res.data.id}`);
    return res.data;
  } catch (error) {
    console.error(`❌ Error enviando email:`, error.message);
    throw error;
  }
}

export async function sendBackupNotification(status, details) {
  const subject = status === 'success' ? '✅ Backup completado' : '❌ Error en backup';
  const html = `
    <h2>Reporte de Backup - The Difference</h2>
    <p><strong>Estado:</strong> ${status === 'success' ? 'Éxito' : 'Fallido'}</p>
    <p><strong>Detalles:</strong></p>
    <pre style="background:#f4f4f4; padding:10px;">${details}</pre>
    <p><strong>Fecha:</strong> ${new Date().toLocaleString('es-ES')}</p>
  `;

  return await sendEmail('quantum.sound.lab@gmail.com', subject, html);
}