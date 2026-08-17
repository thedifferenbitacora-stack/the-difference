# Development Log — The Difference

## Propósito
Esta bitácora documenta el proceso de desarrollo del proyecto para que cualquier asistente pueda retomar el trabajo sin perder contexto.

## Estado general del proyecto
- Proyecto: sitio web estático con Astro.
- Estilo visual: oscuro, minimalista, conceptual y sensorial.
- Objetivo: crear una experiencia de navegación interna con imagen, audio, carruseles y secciones temáticas.
- Respaldo: el proyecto está versionado con Git y se mantiene en GitHub.

## Historial de decisiones importantes

### 1. Estructura del sitio
- Se mantuvo una estructura basada en páginas independientes para cada sección.
- Se usó una plantilla común para conservar coherencia visual.

### 2. Navegación y pantalla inicial
- Se reorganizó la portada para que el usuario entre primero en una experiencia de inicio y luego acceda al dashboard.
- Se priorizó una navegación clara y una lectura de texto antes de las imágenes.

### 3. Carruseles y galerías
- Se implementaron carruseles de imágenes como piezas de recorrido visual.
- Se eligió un movimiento lento y continuo para que se sienta más contemplativo.
- Se ajustó la visualización para respetar la resolución original de las imágenes.

### 4. Recursos multimedia
- Se prepararon carpetas por página para imágenes, música, efectos de sonido y video.
- La idea es mantener cada sección con su propio set de recursos.

### 5. IA local y flujo profesional
- Se preparó una base de entorno local con Docker, Ollama, Open WebUI y Qdrant.
- Se añadieron documentos de contexto para que la IA entienda la identidad y la lógica del proyecto.

## Decisiones técnicas clave
- El sitio se construye con Astro y se despliega como contenido estático.
- Los recursos se almacenan en la carpeta public.
- Las imágenes se organizan por carpetas numeradas por sección.
- Los estilos globales centralizan la estética compartida.

## Archivos clave del proyecto
- src/pages/index.astro: pantalla inicial y dashboard.
- src/components/SectionImageGallery.astro: componente de galería para secciones.
- src/styles/global.css: estilos globales del proyecto.
- docker-compose.yml: base para entorno local de IA.
- docs/ai/README.md: guía de IA local.
- knowledge/project-context.md: contexto base del proyecto.

## Recomendación para continuidad
Cuando un nuevo asistente retome el proyecto, debe revisar primero:
1. este archivo,
2. la estructura de src/pages,
3. la carpeta public por páginas,
4. los documentos de IA local en docs/ai.

## Notas de mantenimiento
- Mantener el repositorio limpio con commits claros.
- Guardar cambios grandes con mensajes descriptivos.
- Si se cambian decisiones visuales importantes, actualizar esta bitácora.
