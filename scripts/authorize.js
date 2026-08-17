import { google } from 'googleapis';
import fs from 'fs/promises';
import path from 'path';
import { fileURLToPath } from 'url';
import * as readline from 'readline';

const __dirname = path.dirname(fileURLToPath(import.meta.url));
const rootDir = path.resolve(__dirname, '..');

const CREDENTIALS_PATH = path.join(rootDir, 'client_secret.json');
const TOKEN_PATH = path.join(rootDir, 'token.json');
const SCOPES = ['https://www.googleapis.com/auth/gmail.send'];

async function authorize() {
  const content = await fs.readFile(CREDENTIALS_PATH);
  const credentials = JSON.parse(content);
  const { client_secret, client_id, redirect_uris } = credentials.installed || credentials.web;

  const oAuth2Client = new google.auth.OAuth2(client_id, client_secret, redirect_uris[0]);

  const authUrl = oAuth2Client.generateAuthUrl({
    access_type: 'offline',
    scope: SCOPES,
  });

  console.log('\n🔗 Abre este enlace en tu navegador para autorizar:');
  console.log('\n' + authUrl + '\n');
  console.log('📋 Después de autorizar, Google te dará un código. Pégalo abajo:\n');

  const rl = readline.createInterface({
    input: process.stdin,
    output: process.stdout,
  });

  rl.question('Código de autorización: ', async (code) => {
    rl.close();
    try {
      const { tokens } = await oAuth2Client.getToken(code);
      oAuth2Client.setCredentials(tokens);
      await fs.writeFile(TOKEN_PATH, JSON.stringify(tokens));
      console.log('\n✅ ¡Autorización exitosa! Token guardado en token.json');
      console.log('Ahora ya puedes ejecutar: npm run backup\n');
    } catch (error) {
      console.error('\n❌ Error al obtener el token:', error.message);
    }
  });
}

authorize().catch(console.error);