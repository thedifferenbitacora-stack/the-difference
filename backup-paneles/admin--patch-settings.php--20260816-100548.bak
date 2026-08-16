<?php
/**
 * API: Guardar LOG Personal con Trazabilidad (segura, sin warnings)
 */
header('Content-Type: application/json; charset=utf-8');

$baseDir = dirname(__DIR__, 2);
$logPersonalFile = $baseDir . '/data/log-personal.json';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'mensaje' => 'Usa POST']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);
if (!is_array($input)) $input = array();

if (empty($input['titulo'])) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Falta titulo']);
    exit;
}

$etapa = isset($input['etapa']) ? $input['etapa'] : 'niñes';
$registro = [
    'id' => uniqid('LOG-'),
    'titulo' => $input['titulo'],
    'contenido' => isset($input['contenido']) ? $input['contenido'] : '',
    'edad' => isset($input['edad']) ? $input['edad'] : null,
    'fecha' => isset($input['fecha']) ? $input['fecha'] : null,
    'fecha_creacion' => date('c'),
    'conexion_proyecto' => isset($input['conexion_proyecto']) ? $input['conexion_proyecto'] : null,
    'conceptos_modos' => []
];

$logPersonal = file_exists($logPersonalFile) ? json_decode(file_get_contents($logPersonalFile), true) : null;
if (!is_array($logPersonal)) {
    $logPersonal = ['niñes' => [], 'juventud' => [], 'adultes' => [], 'proyecto_actual' => [], 'conexiones' => []];
}

if (!isset($logPersonal[$etapa]) || !is_array($logPersonal[$etapa])) $logPersonal[$etapa] = [];
$logPersonal[$etapa][] = $registro;

if (!empty($registro['conexion_proyecto'])) {
    $conexionKey = $etapa . '-proyecto';
    if (!isset($logPersonal['conexiones'][$conexionKey])) $logPersonal['conexiones'][$conexionKey] = [];
    $logPersonal['conexiones'][$conexionKey][] = [
        'registro_id' => $registro['id'],
        'etapa' => $etapa,
        'proyecto' => $registro['conexion_proyecto'],
        'fecha' => date('c')
    ];
}

if (file_put_contents($logPersonalFile, json_encode($logPersonal, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
    echo json_encode(['success' => true, 'mensaje' => 'Registro guardado en ' . strtoupper($etapa), 'registro' => $registro, 'conexiones_creadas' => !empty($registro['conexion_proyecto']) ? 1 : 0]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'No se pudo guardar']);
}
?>