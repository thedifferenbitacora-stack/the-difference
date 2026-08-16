# 🎨 GUÍA DE ESTILOS VISUALES - THE DIFFERENCE

> **Última actualización:** 2025-01-20  
> **Estado:**  En construcción

---

## 📋 ÍNDICE
1. [Identidad Visual](#identidad-visual)
2. [Tipografía](#tipografía)
3. [Paleta de Colores](#paleta-de-colores)
4. [Layout y Posicionamiento](#layout-y-posicionamiento)
5. [Referencias Visuales](#referencias-visuales)
6. [Parámetros del Panel](#parámetros-del-panel)

---

## 🎯 IDENTIDAD VISUAL

**Concepto:** Plataforma web minimalista y oscura para agencia/fundación neurodivergente.

**Filosofía:** 
- Intuitive Analityc Neurodivergence Creative Platform
- Minimalismo oscuro con acentos vibrantes
- Posicionamiento absoluto de elementos
- Animaciones suaves y elegantes

---

##  TIPOGRAFÍA

### Fuentes Principales

| Elemento | Fuente | Peso | Tamaño | Color |
|----------|--------|------|--------|-------|
| **Título Principal** | Arial Black | 900 | 60px | #ffffff |
| **Subtítulo** | Arial Black | 400 | 14px | #a0a0a0 |
| **Botón Principal** | Arial Black | 900 | 50px | #ffffff |
| **Botón Secundario** | Arial Black | 900 | 16px | #fffc34 |

### Alternativas Google Fonts
- Roboto
- Playfair Display
- Orbitron
- Space Mono
- Montserrat
- Oswald

### Características Tipográficas
- **Text Transform:** UPPERCASE (mayúsculas)
- **Letter Spacing:** 5px (títulos), 2px (subtítulos)
- **Alineación:** Center (centrado)
- **Soporte Multilínea:** white-space: normal cuando sea necesario

---

## 🎨 PALETA DE COLORES

### Colores Principales

| Color | Hex | Uso |
|-------|-----|-----|
| **Negro Fondo** | #000000 | Fondo principal |
| **Blanco** | #ffffff | Texto principal, bordes |
| **Rosa Glow** | #ff69b4 | Hover botones, acentos |
| **Amarillo** | #fffc34 | Botones secundarios |
| **Gris Subtítulo** | #a0a0a0 | Texto secundario |

### Gradientes
- **Inicio:** #000000
- **Fin:** #1a1a2e
- **Ángulo:** 135deg

### Sombras y Efectos
- **Glow Rosa:** `0 0 30px rgba(255,105,180,0.5)`
- **Glow Amarillo:** `0 0 20px rgba(255,252,52,0.5)`
- **Sombra Suave:** `0 4px 6px rgba(0,0,0,0.3)`

---

## 📐 LAYOUT Y POSICIONAMIENTO

### Sistema de Coordenadas
- **Tipo:** Absolute positioning
- **Unidades:** Porcentajes (%)
- **Centro:** `transform: translate(-50%, -50%)`

### Posiciones Estándar

| Elemento | X | Y |
|----------|---|---|
| **Título** | 50% | 30% |
| **Subtítulo** | 50% | 45% |
| **Logo Circular** | 50% | 15% |
| **Botón Principal** | 50% | 65% |
| **Botón Secundario** | 85% | 90% |

### Logo Circular
- **Tamaño:** 50vh (alto/igual ancho)
- **Border Radius:** 50% (círculo perfecto)
- **Borde:** 3px solid #ffffff
- **Sombra:** Glow rosa

---

## 🖼️ REFERENCIAS VISUALES

### Portada Esperada
![Portada Referencia](referencias/portada-esperada.jpg)

### Tipografía Ejemplo
![Tipografía](referencias/tipografia-ejemplo.png)

### Paleta de Colores
![Colores](referencias/colores-paleta.png)

### Layout Completo
![Layout](referencias/layout-referencia.jpg)

---

## ⚙️ PARÁMETROS DEL PANEL

### Título Principal - Controles Clave

| Control | Valor Esperado | Efecto Visual |
|---------|----------------|---------------|
| **Texto** | FEELING AUTISTIC | Texto visible |
| **Tamaño** | 60px | Grande, impactante |
| **Color** | #ffffff | Blanco puro |
| **Fuente** | Arial Black | Bold, fuerte |
| **Alineación** | center | Centrado |
| **Salto de línea** | nowrap / normal | Una línea o multilínea |
| **Ancho máximo** | 100% / 80% | Controla wrapping |
| **Posición X** | 50% | Centrado horizontal |
| **Posición Y** | 30% | Arriba del centro |
| **Animación** | fadeInDown | Entrada desde arriba |

### Logo Circular - Controles Clave

| Control | Valor Esperado | Efecto Visual |
|---------|----------------|---------------|
| **Tipo** | image / video | Imagen o video |
| **Tamaño** | 50vh | Grande, visible |
| **Radio Borde** | 50% | Círculo perfecto |
| **Grosor Borde** | 3px | Borde visible |
| **Color Borde** | #ffffff | Blanco |
| **Sombra** | Glow rosa | Efecto neon |
| **Posición Y** | 15% | Arriba |

### Botón Principal - Controles Clave

| Control | Valor Esperado | Efecto Visual |
|---------|----------------|---------------|
| **Texto** | THE DIFFERENCE | Texto del botón |
| **Tamaño** | 50px | Grande |
| **Color** | #ffffff | Blanco |
| **Color Hover** | #ff69b4 | Rosa al pasar mouse |
| **Padding V/H** | 20px / 40px | Tamaño interno |
| **Posición Y** | 65% | Debajo del logo |

---

## 📝 NOTAS DE DISEÑO

### Responsive
- En móviles (< 768px):
  - Título: 30px (50% del tamaño)
  - Logo: 30vh (60% del tamaño)
  - Botones: 25px (50% del tamaño)

### Animaciones
- **fadeInDown:** Entrada desde arriba (título)
- **zoomIn:** Escalado desde 0.5 (logo)
- **fadeInUp:** Entrada desde abajo (botones)
- **fadeInRight:** Entrada desde derecha (botón secundario)

### Overlay de Fondo
- **Video/Imagen:** Opacidad 0.3 (30% oscuro)
- Permite ver el contenido sobre el fondo

---

##  ENLACES ÚTILES

- **Panel de Control:** `/admin/configuracion.php`
- **Preview Portada:** `/portada.php`
- **Bitácora del Proyecto:** `/docs/bitacora/PLANNER.md`

---

*Esta guía se actualiza conforme evolucionan los controles del panel y las referencias visuales.*