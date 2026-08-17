import type { APIRoute } from 'astro';
import fs from 'fs/promises';
import path from 'path';

const TAREAS_PATH = path.join(process.cwd(), '01_BITACORA_GLOBAL', 'TAREAS_PROYECTOS.json');

export const GET: APIRoute = async ({ url }) => {
  try {
    const nodo = url.searchParams.get('nodo');
    let tareas: any[] = [];
    
    try {
      const data = await fs.readFile(TAREAS_PATH, 'utf-8');
      tareas = JSON.parse(data);
    } catch (e) {
      tareas = [];
    }
    
    if (nodo) {
      tareas = tareas.filter(t => String(t.nodo).toLowerCase() === String(nodo).toLowerCase());
    }
    
    return new Response(JSON.stringify({ success: true, tareas }), {
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

export const POST: APIRoute = async ({ request }) => {
  try {
    const body = await request.json();
    const action = body.action || 'crear';
    
    let tareas: any[] = [];
    try {
      const data = await fs.readFile(TAREAS_PATH, 'utf-8');
      tareas = JSON.parse(data);
    } catch (e) {
      tareas = [];
    }
    
    if (action === 'crear') {
      const nuevaTarea = {
        id: body.id || 'TSK-' + Date.now().toString().slice(-6),
        nodo: body.nodo || 'general',
        proyecto: body.proyecto || '',
        titulo: body.titulo || '',
        descripcion: body.descripcion || '',
        estado: body.estado || 'PENDIENTE',
        que_falta: body.que_falta || '',
        prioridad: body.prioridad || 'MEDIA',
        fecha_identificacion: new Date().toLocaleString('es-ES'),
        fecha_finalizacion: '',
        tiempo_total: '',
        energia_costo: body.energia_costo || '',
        energia_valor: body.energia_valor || '',
        biotipo: body.biotipo || '',
        estado_sistemico: body.estado_sistemico || '',
        observacion: body.observacion || '',
        usuario: body.usuario || 'Operador',
        timestamp: new Date().toLocaleString('es-ES')
      };
      tareas.push(nuevaTarea);
    }
    
    if (action === 'actualizar') {
      const index = tareas.findIndex(t => t.id === body.id);
      if (index !== -1) {
        tareas[index] = { ...tareas[index], ...body };
        if (body.estado === 'FINALIZADO' && !tareas[index].fecha_finalizacion) {
          tareas[index].fecha_finalizacion = new Date().toLocaleString('es-ES');
          tareas[index].tiempo_total = calcularTiempoLocal(tareas[index].fecha_identificacion);
        }
      }
    }
    
    if (action === 'eliminar') {
      tareas = tareas.filter(t => t.id !== body.id);
    }
    
    await fs.writeFile(TAREAS_PATH, JSON.stringify(tareas, null, 2), 'utf-8');
    
    return new Response(JSON.stringify({ success: true, tareas }), {
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

function calcularTiempoLocal(fechaInicio: string): string {
  try {
    const inicio = new Date(fechaInicio);
    const diffMs = Date.now() - inicio.getTime();
    const diffHoras = Math.floor(diffMs / (1000 * 60 * 60));
    const diffDias = Math.floor(diffHoras / 24);
    const horasRestantes = diffHoras % 24;
    if (diffDias > 0) return `${diffDias}d ${horasRestantes}h`;
    return `${diffHoras}h`;
  } catch (e) {
    return 'N/A';
  }
}