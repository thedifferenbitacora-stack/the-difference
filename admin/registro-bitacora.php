<?php
/**
 * AGENTE REGISTRADOR AUTOMÁTICO
 * Registra cada cambio en BITACORA.md automáticamente
 * Filosofía: "El Decir es Huella"
 */

$bitacoraFile = dirname(__DIR__) . '/BITACORA.md';
$configFile = dirname(__DIR__) . '/config/settings.json';

// Capturar la acción
$accion = $_POST['accion'] ?? 'Configuración guardada';
$detalles = $_POST['detalles'] ?? '';
$usuario = $_POST['usuario'] ?? 'Desarrollador';

// Crear entrada de bitácora
$timestamp = date('Y-m-d H:i:s');
$entrada = "## [{$timestamp}] - {$accion}\n\n";
$entrada .= "**Usuario:** {$usuario}\n\n";
if ($detalles) {
    $entrada .= "**Detalles:**\n{$detalles}\n\n";
}
$entrada .= "---\n\n";

// Leer bitácora existente o crear nueva
if (file_exists($bitacoraFile)) {
    $contenidoActual = file_get_contents($bitacoraFile);
} else {
    $contenidoActual = "# 📜 BITÁCORA DEL PROYECTO THE DIFFERENCE\n\n";
    $contenidoActual .= "**Filosofía:** El Ser Ahí es Presencia. El Decir es Huella.\n\n";
    $contenidoActual .= "---\n\n";
}

// Agregar nueva entrada al inicio (después del header)
$partes = explode("---\n\n", $contenidoActual, 2);
$nuevoContenido = $partes[0] . "---\n\n" . $entrada;
if (isset($partes[1])) {
    $nuevoContenido .= $partes[1];
}

// Guardar
file_put_contents($bitacoraFile, $nuevoContenido);

echo json_encode(['success' => true, 'message' => 'Registro creado en BITACORA.md']);
?>