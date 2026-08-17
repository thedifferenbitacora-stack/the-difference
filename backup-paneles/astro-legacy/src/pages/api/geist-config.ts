import type { APIRoute } from 'astro';
import fs from 'fs/promises';
import path from 'path';

export const POST: APIRoute = async ({ request }) => {
  try {
    const config = await request.json();
    const configPath = path.join(process.cwd(), '00_BIOS_CORE', 'geist-config.json');
    
    // Crear directorio si no existe
    const dir = path.dirname(configPath);
    await fs.mkdir(dir, { recursive: true });
    
    // Guardar configuración
    await fs.writeFile(configPath, JSON.stringify(config, null, 2), 'utf-8');
    
    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    return new Response(JSON.stringify({ success: false, message: error.message }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};