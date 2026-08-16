# 📜 BITÁCORA DEL PROYECTO THE DIFFERENCE

**Filosofía:** El Ser Ahí es Presencia. El Decir es Huella.

---

## [2026-08-16 21:46:43] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:22:58] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:22:49] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:22:34] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:14:26] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:14:19] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:13:29] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:07:27] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:07:21] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:07:14] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:01:28] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:01:16] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 11:00:47] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:28:37] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:28:30] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:22:01] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:19:47] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:19:40] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:19:33] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:14:00] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:13:49] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:06:23] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:06:06] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:05:56] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:05:49] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:03:11] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 10:03:03] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 09:40:22] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 09:40:12] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 09:33:14] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 09:33:02] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-16 09:32:47] - Configuración guardada

**Usuario:** Desarrollador

---

## [2026-08-15 04:16:15] - Configuración guardada

**Usuario:** Desarrollador
# DECISIÓN · Panel-General (16 ago 2026)

## Qué se decidió
- `panel-general.php` vive en la RAÍZ del proyecto.
- Usa `data/reloj-consciente.json` (reloj) y `config/tareas.json` (tareas).
- Conecta con `admin/api/guardar-reloj.php` y `admin/api/listar-reloj.php`.
- NO toca `admin/hub-central.php` ni `data/log-personal.json`.

## Corrección aplicada (compatibilidad PHP)
- Se ELIMINARON los ternarios anidados `? ... ?: ... : ...` en la carga de datos.
- Se reemplazaron por bloques `if` simples (compatibles con cualquier PHP).
- Error original: "Parse error: unexpected token ';' line 11".

## Valoración de la hora
- NO es un cálculo multiplicativo.
- Es una VALORACIÓN INTEGRAL: emerge de la presencia simultánea de
  rol + capital humano + capital infraestructura + etapa + tarea + ontológico.

## Gráficos
- Todos de ÁREA (línea con relleno).
- Vistas: diaria (hora 0-23), mensual (día 1-31), anual (mes), avance acumulado.
---

