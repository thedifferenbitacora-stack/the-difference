#!/usr/bin/env node

import readline from 'readline';
import fs from 'fs/promises';
import path from 'path';

const rl = readline.createInterface({
  input: process.stdin,
  output: process.stdout
});

const question = (query) => new Promise((resolve) => rl.question(query, resolve));

async function crearRegistroGEIST() {
  console.log('\n╔══════════════════════════════════════════════════════╗');
  console.log('║         GEIST // SISTEMA DE PREGUNTAS ONTOLÓGICAS   ║');
  console.log('╚══════════════════════════════════════════════════════╝\n');

  const registro = {
    id: 'GEIST-' + Date.now().toString().slice(-3),
    nodo: '',
    titulo: '',
    estado: 'EN_PROCESO',
    prioridad: 'ALTA',
    espacio: '',
    tiempo: new Date().toLocaleString('es-ES'),
    energia: '',
    habito: '',
    lenguaje: '',
    arte: false,
    valor: false,
    observacion: ''
  };

  console.log('[ FASE 1: FENOMENOLOGÍA INICIAL ]\n');
  registro.titulo = await question('> ¿Qué fenómeno estás observando en este momento?\n> ');
  registro.nodo = await question('\n> ¿Qué nodo está involucrado?\n> ');

  console.log('\n[ FASE 2: MEDICIÓN DIMENSIONAL ]\n');
  registro.espacio = await question('> ¿Dónde se ubica esto físicamente o conceptualmente en el taller?\n> ');
  registro.energia = await question('\n> ¿Cuánta Energía-Salud te está costando (1-10)?\n> ');
  registro.habito = await question('\n> ¿Qué disciplina o estado sostiene este proceso?\n> ');
  registro.lenguaje = await question('\n> ¿Qué palabras o conceptos usa el sistema para describirse?\n> ');

  console.log('\n[ FASE 3: CORRECCIÓN ONTOLÓGICA ]\n');
  const arteResp = await question('> ¿Se alinea con la visión artística (Ars) del nodo? (s/n)\n> ');
  registro.arte = arteResp.toLowerCase().trim() === 's';
  
  const valorResp = await question('\n> ¿Aporta al propósito ontológico (Valor) de la Fundación? (s/n)\n> ');
  registro.valor = valorResp.toLowerCase().trim() === 's';

  console.log('\n[ FASE 4: SÍNTESIS ]\n');
  registro.observacion = await question('> Describe la síntesis de este proceso:\n> ');
  registro.estado = 'COMPLETADA';

  await guardarRegistroLocal(registro);
  
  console.log('\n╔══════════════════════════════════════════════════════╗');
  console.log('║              REGISTRO GEIST CREADO                   ║');
  console.log('╚══════════════════════════════════════════════════════╝\n');
  console.log(`✅ Registro guardado exitosamente en: 01_BITACORA_GLOBAL/TAREAS_GEIST.md`);
  console.log(`📄 ID del registro: ${registro.id}`);

  rl.close();
}

async function guardarRegistroLocal(registro) {
  const dirPath = path.join(process.cwd(), '01_BITACORA_GLOBAL');
  const bitacoraPath = path.join(dirPath, 'TAREAS_GEIST.md');
  
  // CREA LA CARPETA AUTOMÁTICAMENTE SI NO EXISTE
  await fs.mkdir(dirPath, { recursive: true });
  
  let contenido = '';
  try {
    contenido = await fs.readFile(bitacoraPath, 'utf-8');
  } catch (e) {
    contenido = '# TAREAS GEIST // MEMORIA VIVA DEL SISTEMA\n\n';
  }

  const nuevoRegistro = `
## ${registro.id} - ${registro.titulo}

**NODO:** ${registro.nodo}  
**ESTADO:** ${registro.estado}  
**PRIORIDAD:** ${registro.prioridad}

### MEDICIÓN ONTOLÓGICA
- **ESPACIO:** ${registro.espacio}
- **TIEMPO:** ${registro.tiempo}
- **ENERGÍA:** ${registro.energia}/10
- **HÁBITO:** ${registro.habito}
- **LENGUAJE:** ${registro.lenguaje}

### CORRECCIÓN FINAL
- **ARTE:** ${registro.arte ? '✓ ALINEADO' : '✗ DESVIADO'}
- **VALOR:** ${registro.valor ? '✓ APORTE' : '✗ RUIDO'}

### OBSERVACIÓN / SÍNTESIS
${registro.observacion}

---
`;

  await fs.writeFile(bitacoraPath, contenido + nuevoRegistro, 'utf-8');
}

// Iniciar
crearRegistroGEIST();