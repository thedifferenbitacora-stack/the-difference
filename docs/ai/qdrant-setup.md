# Configuración básica de Qdrant para The Difference

## Objetivo
Usar Qdrant como base de conocimiento local para documentos, guías y contexto del proyecto.

## Recomendación rápida
1. Levanta el stack con Docker Compose.
2. Abre http://localhost:6333/dashboard.
3. Crea un collection llamada `the-difference`.
4. Sube documentos con contexto del proyecto, filosofía, estructura y tono visual.

## Ejemplo de estructura de documentos
- project-context.md
- README.md
- AGENTS.md
- docs/ai/README.md

## Uso ideal
- Ayuda a que la IA recuerde la identidad del sitio.
- Permite construir asistentes con memoria documental local.
- Es útil para futuras mejoras del proyecto sin depender de servicios externos.
