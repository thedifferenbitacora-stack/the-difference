# Entorno local de IA para The Difference

Este proyecto ya tiene una base de sitio Astro. Para trabajar de forma más profesional y local, esta carpeta prepara un stack mínimo con:

- Ollama para modelos locales
- Open WebUI para conversar con la IA desde el navegador
- Qdrant para una base de conocimiento local

## Inicio rápido en Windows

1. Instala Docker Desktop.
2. Desde la raíz del proyecto ejecuta:

```powershell
docker compose up -d
```

3. Descarga el modelo recomendado:

```powershell
docker compose exec ollama ollama pull qwen2.5:7b
```

4. Abre:
- Open WebUI: http://localhost:3000
- Qdrant: http://localhost:6333
- Ollama API: http://localhost:11434

## Qué usar después

- Usa Open WebUI para conversar con el proyecto, ideas y contenido.
- Usa Qdrant para guardar contexto del sitio, filosofía, páginas y recursos.
- Mantén el proyecto en GitHub como respaldo principal.
