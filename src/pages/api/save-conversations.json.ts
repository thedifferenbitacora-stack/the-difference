import type { APIRoute } from 'astro';
import fs from 'fs/promises';
import path from 'path';

export const prerender = false;

export const POST: APIRoute = async ({ request }) => {
  try {
    const conversations = await request.json();
    
    const conversationsPath = path.join(process.cwd(), 'src/data/conversations.json');
    
    await fs.writeFile(
      conversationsPath,
      JSON.stringify(conversations, null, 2),
      'utf-8'
    );
    
    return new Response(JSON.stringify({ success: true }), {
      status: 200,
      headers: { 'Content-Type': 'application/json' }
    });
  } catch (error) {
    return new Response(JSON.stringify({ error: String(error) }), {
      status: 500,
      headers: { 'Content-Type': 'application/json' }
    });
  }
};