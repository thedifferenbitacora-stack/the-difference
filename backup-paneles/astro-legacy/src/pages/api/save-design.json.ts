import type { APIRoute } from 'astro';
import fs from 'fs/promises';
import path from 'path';

export const prerender = false;

export const POST: APIRoute = async ({ request }) => {
  try {
    const body = await request.json();
    const { page, elements, backgroundImage } = body;
    
    const configPath = path.join(process.cwd(), 'src/data/design-config.json');
    
    let currentConfig: any = {};
    try {
      const data = await fs.readFile(configPath, 'utf-8');
      currentConfig = JSON.parse(data);
    } catch (e) {
      console.log('Creando nueva configuración');
    }
    
    currentConfig[page] = {
      elements,
      backgroundImage: backgroundImage || currentConfig[page]?.backgroundImage || '',
      globalStyles: currentConfig[page]?.globalStyles || {}
    };
    
    await fs.writeFile(
      configPath,
      JSON.stringify(currentConfig, null, 2),
      'utf-8'
    );
    
    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    console.error('Error saving design:', error);
    return new Response(JSON.stringify({ error: String(error) }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};