<?php
/**
 * GUARDAR LOG CON IA INTEGRADA - THE DIFFERENCE (CÓDIGO COMPLETO)
 * Guarda la entrada en bitacora.json y dispara el Agente Multi-IA automáticamente.
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

$baseDir = dirname(__DIR__, 2);
$bitacoraFile = $baseDir . '/config/bitacora.json';
$settingsFile = $baseDir . '/config/settings.json';

$input = json_decode(file_get_contents('php://input'), true);

$tipoBitacora = $input['tipo_bitacora'] ?? 'log';
$contenido = $input['contenido'] ?? '';
$nivelConciencia = (int)($input['nivel_conciencia'] ?? 1);
$conceptosModosInput = $input['conceptos_modos'] ?? [];

if (empty($contenido)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El contenido es requerido']);
    exit;
}

// 1. GUARDAR EN BITÁCORA PRINCIPAL
$bitacora = file_exists($bitacoraFile) ? json_decode(file_get_contents($bitacoraFile), true) : [];
if (!is_array($bitacora)) $bitacora = [];

$nuevaEntrada = [
    'id' => uniqid('LOG-'),
    'fecha' => date('c'),
    'tipo_bitacora' => $tipoBitacora,
    'tipo_pensamiento' => $tipoBitacora, // Para compatibilidad con el relacionador
    'proceso' => $tipoBitacora,
    'contenido' => $contenido,
    'nivel_conciencia' => $nivelConciencia,
    'conceptos_modos' => $conceptosModosInput,
    'relacionado_a' => []
];

$bitacora[] = $nuevaEntrada;
file_put_contents($bitacoraFile, json_encode($bitacora, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// 2. DISPARAR AGENTE MULTI-IA INTERNAMENTE (vía cURL local o inclusión)
// Usamos inclusión directa para evitar overhead de red local y mantener todo en una petición
$agentePath = $baseDir . '/admin/api/agentes/agente-multi-ia.php';

// Simulamos la petición POST al agente
ob_start();
$_SERVER['REQUEST_METHOD'] = 'POST';
$GLOBALS['HTTP_RAW_POST_DATA'] = json_encode([
    'bitacora_id' => $nuevaEntrada['id'],
    'tipo_bitacora' => $tipoBitacora,
    'texto' => $contenido
]);
include $agentePath;
$respuestaAgente = ob_get_clean();

$analisisIA = json_decode($respuestaAgente, true);

// 3. RESPUESTA FINAL AL FRONTEND
echo json_encode([
    'success' => true,
    'mensaje' => 'Entrada guardada y procesada por el Consejo de Sabios',
    'bitacora' => $nuevaEntrada,
    'analisis_ia' => $analisisIA ?? ['error' => 'No se pudo procesar la IA']
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>