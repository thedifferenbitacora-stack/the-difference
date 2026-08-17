import type { APIRoute } from 'astro';

export const POST: APIRoute = async () => {
  await new Promise(resolve => setTimeout(resolve, 1000));
  
  return new Response(JSON.stringify({ 
    success: true,
    message: 'Cache purgado' 
  }), {
    status: 200,
    headers: { 'Content-Type': 'application/json' }
  });
};