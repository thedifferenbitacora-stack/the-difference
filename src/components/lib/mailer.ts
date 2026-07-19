import nodemailer from 'nodemailer';

const transporter = nodemailer.createTransport({
  host: process.env.EMAIL_HOST,
  port: Number(process.env.EMAIL_PORT),
  secure: false,
  auth: {
    user: process.env.EMAIL_USER,
    pass: process.env.EMAIL_PASS,
  },
});

export interface BitacoraReport {
  timestamp: string;
  tempo_root: string;
  ciclo: string;
  proyectos: Array<{
    nombre: string;
    espacio: string;
    acciones: string[];
    estado: 'OK' | 'WARN' | 'ERROR';
  }>;
  fases: {
    pull_sources: { status: string; detail: string };
    archive_memory: { status: string; detail: string };
    snapshot_code: { status: string; detail: string };
    push_github: { status: string; detail: string };
    deploy_vercel: { status: string; detail: string };
  };
  resumen_ejecutivo: string;
  memoria_total: {
    knowledge_entries: number;
    chat_files: number;
    sources_synced: number;
  };
}

export async function enviarBitacoraEmail(report: BitacoraReport) {
  const html = generarHTMLBitacora(report);
  const texto = generarTextoBitacora(report);

  const info = await transporter.sendMail({
    from: `"ARS TEKNE BIOS" <${process.env.EMAIL_USER}>`,
    to: process.env.EMAIL_TO,
    subject: `[BITÁCORA] ${report.timestamp} — Ciclo ${report.ciclo}`,
    text: texto,
    html: html,
  });

  return info;
}

function generarHTMLBitacora(r: BitacoraReport): string {
  const proyectosHTML = r.proyectos
    .map(
      (p) => `
    <tr>
      <td style="padding:8px;border:1px solid #0f0;">${p.nombre}</td>
      <td style="padding:8px;border:1px solid #0f0;">${p.espacio}</td>
      <td style="padding:8px;border:1px solid #0f0;">${p.acciones.join('<br>')}</td>
      <td style="padding:8px;border:1px solid #0f0;color:${
        p.estado === 'OK' ? '#0f0' : p.estado === 'WARN' ? '#ffb000' : '#ff3860'
      };font-weight:bold;">${p.estado}</td>
    </tr>`
    )
    .join('');

  const fasesHTML = Object.entries(r.fases)
    .map(
      ([k, v]) => `
    <li style="margin:4px 0;">
      <strong style="color:#0ff;">${k.toUpperCase()}</strong>: 
      <span style="color:${v.status === 'OK' ? '#0f0' : '#ff3860'};">${v.status}</span>
      — ${v.detail}
    </li>`
    )
    .join('');

  return `
<!DOCTYPE html>
<html>
<head><meta charset="utf-8"></head>
<body style="background:#000;color:#0f0;font-family:'Courier New',monospace;padding:20px;">
  <h1 style="color:#0f0;border-bottom:2px solid #0f0;padding-bottom:10px;">
    // ARS TEKNE — BITÁCORA BIODINÁMICA
  </h1>
  <p><strong>Timestamp:</strong> ${r.timestamp}</p>
  <p><strong>Tiempo Raíz:</strong> ${r.tempo_root}</p>
  <p><strong>Ciclo:</strong> ${r.ciclo}</p>
  <p><strong>Operador:</strong> ${process.env.OPERATOR_NAME || 'Jose_Miguel_Cortez_Correa'}</p>

  <h2 style="color:#0ff;margin-top:30px;"> PROYECTOS AFECTADOS</h2>
  <table style="border-collapse:collapse;width:100%;margin-top:10px;">
    <thead>
      <tr style="background:#001a00;">
        <th style="padding:8px;border:1px solid #0f0;text-align:left;">Proyecto</th>
        <th style="padding:8px;border:1px solid #0f0;text-align:left;">Espacio</th>
        <th style="padding:8px;border:1px solid #0f0;text-align:left;">Acciones</th>
        <th style="padding:8px;border:1px solid #0f0;text-align:left;">Estado</th>
      </tr>
    </thead>
    <tbody>${proyectosHTML}</tbody>
  </table>

  <h2 style="color:#0ff;margin-top:30px;">▸ FASES DEL CICLO</h2>
  <ul style="list-style:none;padding:0;">${fasesHTML}</ul>

  <h2 style="color:#0ff;margin-top:30px;"> MEMORIA TOTAL</h2>
  <ul>
    <li>Entradas de conocimiento: <strong>${r.memoria_total.knowledge_entries}</strong></li>
    <li>Archivos de chat: <strong>${r.memoria_total.chat_files}</strong></li>
    <li>Fuentes sincronizadas: <strong>${r.memoria_total.sources_synced}</strong></li>
  </ul>

  <h2 style="color:#0ff;margin-top:30px;">▸ RESUMEN EJECUTIVO</h2>
  <p style="color:#ffb000;">${r.resumen_ejecutivo}</p>

  <hr style="border:1px solid #0f0;margin-top:40px;">
  <p style="color:#666;font-size:12px;">
    // Enviado automáticamente por BIOS_BACKUP_PROTOCOL — ARS TEKNE<br>
    // Operador: ${process.env.OPERATOR_NAME || 'Jose_Miguel_Cortez_Correa'}<br>
    // localhost → github → vercel → email
  </p>
</body>
</html>`;
}

function generarTextoBitacora(r: BitacoraReport): string {
  return `
// ARS TEKNE — BITÁCORA BIODINÁMICA
Operador: ${process.env.OPERATOR_NAME || 'Jose_Miguel_Cortez_Correa'}
Timestamp: ${r.timestamp}
Tiempo Raíz: ${r.tempo_root}
Ciclo: ${r.ciclo}

 PROYECTOS AFECTADOS
${r.proyectos.map((p) => `• ${p.nombre} [${p.espacio}] → ${p.acciones.join(', ')} (${p.estado})`).join('\n')}

▸ FASES DEL CICLO
${Object.entries(r.fases)
  .map(([k, v]) => `• ${k}: ${v.status} — ${v.detail}`)
  .join('\n')}

▸ MEMORIA TOTAL
• Knowledge entries: ${r.memoria_total.knowledge_entries}
• Chat files: ${r.memoria_total.chat_files}
• Sources synced: ${r.memoria_total.sources_synced}

▸ RESUMEN EJECUTIVO
${r.resumen_ejecutivo}

// BIOS_BACKUP_PROTOCOL — ARS TEKNE
`.trim();
}