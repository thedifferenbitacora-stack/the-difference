<?php
/**
 * DIAGNÓSTICO DE CONCEPTOS
 * Muestra los conceptos de cada bitácora para entender por qué no hay relaciones
 */

header('Content-Type: application/json; charset=utf-8');

$baseDir = dirname(__DIR__, 3);
$memoryDir = $baseDir . '/.memory/conversations';

if (!is_dir($memoryDir)) {
    $memoryDir = dirname(__DIR__) . '/.memory/conversations';
}

$conversaciones = [];
if (is_dir($memoryDir)) {
    $archivos = glob($memoryDir . '/*.json');
    foreach ($archivos as $archivo) {
        $contenido = file_get_contents($archivo);
        $conv = json_decode($contenido, true);
        if (!empty($conv)) {
            $conversaciones[] = $conv;
        }
    }
}

$diagnostico = [];
foreach ($conversaciones as $conv) {
    $diagnostico[$conv['id']] = [
        'titulo' => $conv['titulo'] ?? 'Sin título',
        'tipo_pensamiento' => $conv['tipo_pensamiento_original'] ?? 'N/A',
        'conceptos' => $conv['analisis']['conceptos'] ?? [],
        'palabras_clave' => $conv['analisis']['palabras_clave'] ?? [],
        'proceso' => $conv['metadata']['proceso'] ?? 'N/A'
    ];
}

echo json_encode([
    'diagnostico' => $diagnostico,
    'total_conversaciones' => count($conversaciones),
    'memoryDir' => $memoryDir
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>