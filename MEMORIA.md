# MEMORIA DEL PROCESO · THE DIFFERENCE
Última actualización: 2026-08-16 (sesión 04:58–10:28)

## ⚠️ REGLAS PERMANENTES (la IA debe cumplirlas SIEMPRE)
1. **CÓDIGO COMPLETO SIEMPRE**: entregar archivos completos listos para reemplazar,
   nunca fragmentos ni inserciones sueltas.
2. **Sin ternarios anidados** (el PHP del usuario los rechaza → Parse error).
3. **Eliminar = mover a backup-paneles/** (nunca borrado definitivo).
4. **Clasificar cada tarea** por área + roles del oficiante (leyenda abajo).
5. **Ritual de memoria**: al INICIAR sesión leer este archivo primero;
   al CERRAR, actualizar "Estado actual" y "Próximos pasos".

## IDENTIDAD
- Proyecto: THE DIFFERENCE / FEELING AUTISTIC · plataforma creativa-filosófica neurodivergente.
- Taller: Quantum Lab · valor hora constante $70.000.
- Roles del oficiante: presidente, director, arquitecto, ingeniero, programador,
  diseñador, ejecutor técnico, CEO, filósofo, mago, oficiante creativo.

## MAPA DE ARQUITECTURA
- Público: portada.php, menu.php, the-difference.php, nodos (ars-tekne, le-tematik,
  quantumlab, quiron-theatre, saiayin-do, texvn, pensamiento-autista, project-nada-brahma),
  panel-general.php.
- Admin: hub-central.php, configuracion.php (Fondo+Fuentes), panel-portada.php,
  panel-menu.php, bitacora.php, recopilatorio.php, inventario.php (v5.1 salud).
- APIs: guardar-reloj, listar-reloj, guardar-log-personal, guardar-log-con-ia, agentes/*.
- Datos: reloj-consciente.json, reloj-consciente-test.json (pruebas), recopilatorio.json,
  tareas.json, log-personal.json, salud-cache.json, inventario-log.json. Config: settings.json.

## ESTADO ACTUAL (2026-08-16)
- Sistema IMPECABLE: 0 fallas reales; solo POST-only (400/405) como normales.
- Reloj end-to-end + botón 🔄 RESET (archiva pruebas, no borra).
- Inventario v5.1: ✅/🧪/❌ + agente de diagnóstico + informe descargable + 👁️ Abrir.
- settings.json parcheado (17 claves X/Y); guards en panel-nodo-template.php.
- 24+ tareas en tareas.json.

## PRÓXIMOS PASOS (prioridad)
1. Pegar síntesis del día en recopilatorio (reloj=test; trabajo real ~5.5 h).
2. T-025: crear MEMORIA.md (este archivo) + método de recopilación de línea de tiempo.
3. T-026: agente "Línea de Tiempo" (Git + filemtime + reloj + bitácoras + informes).
4. Elegir: despliegue GitHub+Vercel O landing del fundador bilingüe.
5. Luego: dossier institucional, Modo Cine, Knowledge Base RAG, plan 90 días.

## LEYENDA DE CLASIFICACIÓN
- backend: programador, ingeniero, ejecutor_tecnico
- frontend: diseñador, oficiante_creativo
- datos: ingeniero, arquitecto
- direccion: director, presidente, CEO, arquitecto
- contenido: filósofo, mago, oficiante_creativo