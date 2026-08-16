# ️ MAPEO DE CONTROLES ↔ RESULTADO VISUAL

> Guía rápida para entender qué hace cada control del panel

---

## 📝 TÍTULO PRINCIPAL

### Controles de Texto
- **Texto:** Lo que se muestra en pantalla
- **Tamaño:** Altura de las letras en píxeles (20-200px)
- **Color:** Color del texto (picker de color)
- **Fuente:** Tipografía (Arial Black, Roboto, etc.)

### Controles de Layout
- **Alineación:** 
  - `left` = Izquierda
  - `center` = Centro
  - `right` = Derecha
  
- **Salto de línea:**
  - `nowrap` = Una sola línea (no rompe)
  - `normal` = Multilínea (ajusta al ancho)
  
- **Ancho máximo:** 
  - 100% = Ocupa todo el ancho disponible
  - 80% = Limita el ancho (forza salto de línea si es multilínea)
  - 50% = Mitad del ancho

### Posicionamiento
- **X:** Posición horizontal (0% = izquierda, 50% = centro, 100% = derecha)
- **Y:** Posición vertical (0% = arriba, 50% = centro, 100% = abajo)

### Efectos
- **Animación:** Efecto de entrada (fadeIn, zoomIn, etc.)

---

##  LOGO/VIDEO CIRCULAR

### Tipo de Medio
- **Imagen:** Sube PNG/JPG
- **Video:** Sube MP4 (autoplay, loop, muted)

### Dimensiones
- **Tamaño:** Alto en vh (viewport height)
  - 50vh = 50% de la altura de la pantalla
  - Se mantiene proporción cuadrada

### Estilo del Círculo
- **Radio Borde:** 
  - 50% = Círculo perfecto
  - 0% = Cuadrado
  - 25% = Rectángulo redondeado
  
- **Grosor Borde:** Ancho del borde en píxeles
- **Color Borde:** Color del borde

### Efectos
- **Sombra:** 
  - `none` = Sin sombra
  - `Glow rosa` = Resplandor rosa
  - `Sombra suave` = Sombra negra difusa

---

## 🎨 FONDO DE PANTALLA COMPLETA

### Tipos de Fondo
1. **Color Sólido:** Un solo color de fondo
2. **Gradiente:** Transición entre dos colores
3. **Imagen:** Imagen fija a pantalla completa
4. **Video:** Video en loop a pantalla completa

### Controles de Imagen/Video
- **Subir:** Selecciona archivo desde tu PC
- **Opacidad del Overlay:** 
  - 0 = Sin oscurecer (imagen/video brillante)
  - 0.3 = 30% oscuro (recomendado)
  - 1 = Completamente negro (no se ve el fondo)

### Gradiente
- **Color inicio:** Primer color del gradiente
- **Color fin:** Segundo color del gradiente
- **Ángulo:** Dirección del gradiente (0°-360°)
  - 0° = Arriba hacia abajo
  - 90° = Izquierda a derecha
  - 135° = Diagonal (recomendado)

---

## 🔘 BOTONES

### Apariencia
- **Texto:** Lo que dice el botón
- **Tamaño:** Altura del texto en px
- **Color:** Color del texto
- **Color Hover:** Color al pasar el mouse

### Dimensiones
- **Padding V:** Relleno vertical (arriba/abajo)
- **Padding H:** Relleno horizontal (izquierda/derecha)
- **Radio Borde:** 
  - 0px = Bordes cuadrados
  - 50px = Bordes redondeados

### Posicionamiento
- **X:** Horizontal (0-100%)
- **Y:** Vertical (0-100%)

---

## 📊 TABLA DE REFERENCIA RÁPIDA

| Control | Valor Mín | Valor Máx | Recomendado | Efecto |
|---------|-----------|-----------|-------------|--------|
| **Tamaño Título** | 20px | 200px | 60px | Grande pero legible |
| **Tamaño Logo** | 10vh | 100vh | 50vh | Visible sin dominar |
| **Radio Borde Logo** | 0% | 50% | 50% | Círculo perfecto |
| **Opacidad Overlay** | 0 | 1 | 0.3 | Legibilidad óptima |
| **Posición X** | 0% | 100% | 50% | Centrado |
| **Posición Y** | 0% | 100% | 15-65% | Distribución vertical |

---

##  COMBINACIONES RECOMENDADAS

### Título en Dos Líneas
1. **Salto de línea:** `normal` (multilínea)
2. **Ancho máximo:** 80%
3. **Alineación:** center
4. **Resultado:** El texto se divide en dos líneas centradas

### Logo con Glow
1. **Radio Borde:** 50%
2. **Grosor Borde:** 3px
3. **Color Borde:** #ffffff
4. **Sombra:** Glow rosa
5. **Resultado:** Círculo blanco con resplandor

### Fondo Cinematográfico
1. **Tipo:** Video
2. **Opacidad Overlay:** 0.3
3. **Resultado:** Video oscuro con contenido legible encima

---

*Usa esta guía como referencia rápida al configurar el panel.*