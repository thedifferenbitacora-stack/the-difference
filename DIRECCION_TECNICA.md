# 🎨 THE DIFFERENCE - DIRECCIÓN TÉCNICA Y VISIÓN DEL PROYECTO

## 📌 INFORMACIÓN GENERAL
**Proyecto:** The Difference - Plataforma Web PHP
**Stack Técnico:** PHP 7.4+, HTML5, CSS3, JavaScript vanilla
**Almacenamiento:** JSON (config/settings.json)
**Servidor Local:** AMPPS (Apache + PHP)
**Ubicación:** C:\Program Files\Ampps\www\the-difference-php\

---

## 🎯 VISIÓN GENERAL

The Difference es una plataforma web que representa una agencia/fundación con identidad visual minimalista y oscura. El sistema permite configurar visualmente cada página desde un panel de control administrativo, con cambios que se reflejan inmediatamente en el frontend.

**Filosofía de Diseño:**
- Minimalismo oscuro (fondo negro #000000)
- Tipografía bold y impactante (Arial Black por defecto)
- Colores de acento: Rosa (#ff69b4), Amarillo (#fffc34), Blanco (#ffffff)
- Posicionamiento absoluto con coordenadas X/Y (%)
- Logo circular (imagen o video) como elemento central
- Botones con bordes, efectos hover y animaciones de entrada

---

## 📁 ESTRUCTURA DE ARCHIVOS
he-difference-php/
── admin/
│   └── configuracion.php          ← Panel de control administrativo
├── config/
│   └── settings.json              ← Almacenamiento de configuración
├── images/
│   ├── logo-portada.png           ← Logo de la portada
│   └── logo-menu.png              ← Logo del menú
├── videos/
│   ├── logo-portada.mp4           ← Video opcional portada
│   └── logo-menu.mp4              ← Video opcional menú
── index.php                      ← Página de portada (homepage)
└── menu.php                       ← Página de menú



---

##  PÁGINA 1: PORTADA (index.php)

### Estructura Visual (de arriba hacia abajo):
1. **Título Principal** - "FEELING AUTISTIC" (texto grande, blanco, centrado)
2. **Subtítulo** - "INTUITIVE ANALITYC NEURODIVERGENCE CREATIVE PLATFORM" (texto pequeño, gris)
3. **Logo Circular** - Imagen o video en formato circular (centrado, con borde blanco)
4. **Botón Principal** - "THE DIFFERENCE" (botón grande con borde, efecto hover rosa)
5. **Botón Secundario** - "LE TEMATIK DESIGN" (botón pequeño, esquina inferior derecha)

### Estética Definida:
- **Fondo:** Negro sólido (#000000) o gradiente
- **Título:** 60px, Arial Black, blanco, uppercase, letter-spacing 5px
- **Subtítulo:** 14px, Arial Black, gris (#a0a0a0), uppercase
- **Logo:** 50vh, border-radius 50% (círculo perfecto), borde 3px blanco, sombra glow rosa
- **Botón Principal:** 50px, borde 3px blanco, padding 20px 40px, hover cambia a rosa con glow
- **Botón Secundario:** 16px, color amarillo (#fffc34), borde 2px blanco, posición X:85% Y:90%

### Animaciones:
- Título: fadeInDown (1s)
- Subtítulo: fadeIn (1s, delay 0.3s)
- Logo: zoomIn (1.5s)
- Botón Principal: fadeInUp (1s, delay 0.5s)
- Botón Secundario: fadeInRight (1s, delay 1s)

### Funcionalidad:
- Botón Principal "THE DIFFERENCE" → navega a menu.php
- Botón Secundario "LE TEMATIK DESIGN" → enlace pendiente de definir
- Link "⚙️ Panel Admin" → navega a admin/configuracion.php (esquina superior derecha)

---

## 📋 PÁGINA 2: MENÚ (menu.php)

### Estructura Visual (de arriba hacia abajo):
1. **Título** - "FEELING AUTISTIC" (60px, blanco)
2. **Subtítulo** - "NEURODIVERGENCE CREATIVE PHILOSOPHY PLATFORM" (14px, gris)
3. **Logo Circular** - Imagen o video circular (40vh, centrado)
4. **Botón Principal** - "THE DIFFERENCE" (45px, con efectos)
5. **Botones Secundarios** - 9 botones en grid (LOG, LE TEMATIK, PROJECT NADA BRAHMA, TEXVN, QUANTUMLAB, PENSAMIENTO AUTISTA, SAIAYIN DO, ARS TEKNE, QUIRÓN THEATRE)
6. **Botón Inferior** - "LE TEMATIK DESIGN" (14px, rosa, posición Y:95%)

### Estética de Botones Secundarios:
- Tamaño: 14px
- Color: amarillo (#fffc34)
- Hover: blanco (#ffffff)
- Borde: 2px blanco
- Padding: 8px 16px
- Gap entre botones: 10px
- Transformación hover: scale(1.05)
- Sombra hover: glow amarillo

### Funcionalidad:
- Link "← Volver" → navega a / (portada)
- Botones secundarios → enlaces pendientes (actualmente #)
- Botón inferior → enlace pendiente

---

## ⚙️ PANEL DE CONTROL (admin/configuracion.php)

### Estructura de Pestañas:
1. **🏠 Portada** - Configuración de index.php
2. **📋 Menú** - Configuración de menu.php
3. **🎨 Fondo** - Color de fondo global
4. **🔤 Fuentes** - Fuentes Google Fonts disponibles

### Secciones por Pestaña:

#### Pestaña PORTADA:
- **Título:** Texto, tamaño (20-200px), color, fuente, peso, espaciado, transformación, animación, posición X/Y
- **Subtítulo:** Mismos controles que título
- **Logo/Video Circular:** Tipo (imagen/video), subir archivo, tamaño (10-100vh), radio borde (0-50%), grosor borde, color borde, sombra, animación, posición X/Y
- **Botón Principal:** Texto, tamaño, color texto, color hover, color borde, grosor borde, radio borde, padding V/H, fuente, peso, espaciado, sombra hover, transform hover, animación, posición X/Y
- **Botón Secundario "LE TEMATIK":** Mismos controles que botón principal

#### Pestaña MENÚ:
- **Título:** Mismos controles que portada
- **Subtítulo:** Mismos controles
- **Logo/Video Circular:** Mismos controles
- **Botón Principal:** Mismos controles
- **Botones Secundarios (unificados):** Tamaño, color, color hover, color borde, grosor borde, radio borde, padding V/H, fuente, peso, espaciado, transformación texto, sombra hover, transform hover, animación, espacio entre botones, posición X/Y
- **Botón Inferior:** Mismos controles que botón principal

#### Pestaña FONDO:
- Tipo: sólido o gradiente
- Color fondo (color picker)
- Gradiente inicio/fin (si es gradiente)
- Ángulo de gradiente (0-360°)

#### Pestaña FUENTES:
- Campo de texto para listar fuentes disponibles (separadas por coma)
- Lista de fuentes Google Fonts disponibles

### Funcionalidad del Panel:
- Botón "💾 Guardar" al final (esquina inferior derecha)
- Al guardar: actualiza config/settings.json
- Mensaje de confirmación verde al guardar exitosamente
- Navegación entre pestañas con JavaScript (showTab)
- Sliders con valor en tiempo real (oninput actualiza el display)

---

##  SISTEMA DE CONFIGURACIÓN

### Archivo: config/settings.json
- Formato: JSON con indentación (JSON_PRETTY_PRINT)
- Claves con prefijo: `portada_`, `menu_`, `bg_`, `google_fonts`
- Valores numéricos guardados como integer/float según corresponda
- Colores en formato hex (#ffffff)
- Rutas de archivos relativas (images/logo.png, videos/logo.mp4)

### Carga de Configuración:
```php
$configFile = __DIR__ . '/config/settings.json';
$defaults = [...]; // Valores por defecto
$config = $defaults;
if (file_exists($configFile)) {
    $saved = json_decode(file_get_contents($configFile), true);
    if (is_array($saved)) $config = array_merge($defaults, $saved);
}