<?php
/**
 * DIAGNÓSTICO DE RELACIONES - THE DIFFERENCE
 * Muestra todas las similitudes calculadas entre pares de bitácoras
 */

header('Content-Type: application/json; charset=utf-8');

$baseDir = dirname(__DIR__, 3);
$bitacoraFile = $baseDir . '/config/bitacora.json';
$memoryDir = $baseDir . '/.memory/conversations';

if (!is_dir($memoryDir)) {
    $memoryDir = dirname(__DIR__) . '/.memory/conversations';
}

function leerJSON($archivo) {
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?? [];
}

function calcularSimilitudConceptos($a, $b) {
    if (empty($a) || empty($b)) return 0;
    $interseccion = array_intersect($a, $b);
    $union = array_unique(array_merge($a, $b));
    return empty($union) ? 0 : count($interseccion) / count($union);
}

function calcularSimilitudProceso($a, $b) {
    return ($a === $b && $a !== 'general') ? 0.5 : 0;
}

function calcularSimilitudPensamiento($a, $b) {
    $matriz = [
        'observacion' => ['reflexion' => 0.3, 'intuicion' => 0.2],
        'reflexion' => ['observacion' => 0.3, 'sintesis' => 0.4],
        'intuicion' => ['observacion' => 0.2, 'sintesis' => 0.3],
        'pensamiento_critico' => ['reflexion' => 0.3, 'sintesis' => 0.4],
        'sintesis' => ['reflexion' => 0.4, 'pensamiento_critico' => 0.4]
    ];
    return $matriz[$a][$b] ?? 0;
}

function calcularSimilitudTemporal($fechaA, $fechaB) {
    $tA = strtotime($fechaA);
    $tB = strtotime($fechaB);
    if (!$tA || !$tB) return 0;
    $dias = abs($tA - $tB) / (60 * 60 * 24);
    if ($dias <= 1) return 0.3;
    if ($dias <= 3) return 0.2;
    if ($dias <= 7) return 0.1;
    return 0;
}

try {
    $bitacora = leerJSON($bitacoraFile);
    
    // Cargar conversaciones
    $conversaciones = [];
    if (is_dir($memoryDir)) {
        foreach (glob($memoryDir . '/*.json') as $archivo) {
            $conv = leerJSON($archivo);
            if (!empty($conv)) $conversaciones[] = $conv;
        }
    }
    
    $mapaConv = [];
    foreach ($conversaciones as $c) $mapaConv[$c['id']] = $c;
    
    // Calcular todas las similitudes entre pares
    $todasLasSimilitudes = [];
    
    for ($i = 0; $i < count($bitacora); $i++) {
        for ($j = $i + 1; $j < count($bitacora); $j++) {
            $A = $bitacora[$i];
            $B = $bitacora[$j];
            $convA = $mapaConv[$A['id']] ?? null;
            $convB = $mapaConv[$B['id']] ?? null;
            
            $conceptosA = $convA['analisis']['conceptos'] ?? [];
            $conceptosB = $convB['analisis']['conceptos'] ?? [];
            
            $simConceptos = calcularSimilitudConceptos($conceptosA, $conceptosB);
            $simProceso = calcularSimilitudProceso($A['proceso'] ?? 'general', $B['proceso'] ?? 'general');
            $simPensamiento = calcularSimilitudPensamiento($A['tipo_pensamiento'] ?? 'general', $B['tipo_pensamiento'] ?? 'general');
            $simTemporal = calcularSimilitudTemporal($A['fecha'] ?? '', $B['fecha'] ?? '');
            
            $total = $simConceptos * 0.4 + $simProceso * 0.3 + $simPensamiento * 0.2 + $simTemporal * 0.1;
            
            $todasLasSimilitudes[] = [
                'par' => $A['id'] . ' ↔ ' . $B['id'],
                'titulo_A' => $A['titulo'] ?? '',
                'titulo_B' => $B['titulo'] ?? '',
                'proceso_A' => $A['proceso'] ?? 'general',
                'proceso_B' => $B['proceso'] ?? 'general',
                'tipo_A' => $A['tipo_pensamiento'] ?? 'general',
                'tipo_B' => $B['tipo_pensamiento'] ?? 'general',
                'conceptos_A' => $conceptosA,
                'conceptos_B' => $conceptosB,
                'similitud_conceptos' => round($simConceptos, 3),
                'similitud_proceso' => round($simProceso, 3),
                'similitud_pensamiento' => round($simPensamiento, 3),
                'similitud_temporal' => round($simTemporal, 3),
                'TOTAL' => round($total, 3),
                'supera_umbral_0.15' => $total >= 0.15,
                'supera_umbral_0.10' => $total >= 0.10,
                'supera_umbral_0.05' => $total >= 0.05
            ];
        }
    }
    
    // Ordenar por similitud total descendente
    usort($todasLasSimilitudes, function($a, $b) {
        return $b['TOTAL'] <=> $a['TOTAL'];
    });
    
    echo json_encode([
        'diagnostico' => 'Analisis completo de similitudes entre pares',
        'total_pares_analizados' => count($todasLasSimilitudes),
        'pares_con_similitud_mayor_a_0.15' => count(array_filter($todasLasSimilitudes, fn($p) => $p['TOTAL'] >= 0.15)),
        'pares_con_similitud_mayor_a_0.10' => count(array_filter($todasLasSimilitudes, fn($p) => $p['TOTAL'] >= 0.10)),
        'pares_con_similitud_mayor_a_0.05' => count(array_filter($todasLasSimilitudes, fn($p) => $p['TOTAL'] >= 0.05)),
        'todas_las_similitudes' => $todasLasSimilitudes,
        'recomendacion' => 'Si ningun par supera 0.15, bajar el umbral a 0.10 o 0.05 en el relacionador'
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>