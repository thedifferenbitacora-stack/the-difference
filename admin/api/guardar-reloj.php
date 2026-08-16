<?php
/**
 * API: Guardar sesión del Reloj Consciente
 */
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { exit; }

$baseDir = dirname(__DIR__, 2);
$dataFile = $baseDir . '/data/reloj-consciente.json';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Método no permitido']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['inicio']) || !isset($input['fin'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos incompletos']);
    exit;
}

$sesiones = [];
if (file_exists($dataFile)) {
    $sesiones = json_decode(file_get_contents($dataFile), true) ?: [];
}

$nueva = [
    'id' => uniqid('RELOJ-'),
    'inicio' => (int)$input['inicio'],
    'fin' => (int)$input['fin'],
    'duracion_seg' => (int)$input['duracionSeg'],
    'duracion_horas' => (float)$input['duracionHoras'],
    'valor_hora' => (float)$input['valorHora'],
    'valor' => (float)$input['valor'],
    'fecha' => date('Y-m-d', (int)$input['inicio'] / 1000),
    'hora_inicio' => date('H:i:s', (int)$input['inicio'] / 1000),
    'hora_fin' => date('H:i:s', (int)$input['fin'] / 1000),
    'nodo' => $input['nodo'] ?? 'general',
    'tematica' => $input['tematica'] ?? 'trabajo-consciente',
    'rol' => $input['rol'] ?? 'fundador',
    'proyecto' => $input['proyecto'] ?? 'the-difference',
    'timestamp_registro' => time()
];

$sesiones[] = $nueva;

if (file_put_contents($dataFile, json_encode($sesiones, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'mensaje' => 'Sesión guardada', 'sesion' => $nueva]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No se pudo guardar']);
}
?>