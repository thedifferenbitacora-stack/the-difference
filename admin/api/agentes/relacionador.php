<?php
/**
 * AGENTE RELACIONADOR - THE DIFFERENCE (CÓDIGO COMPLETO ACTUALIZADO)
 * Umbral optimizado: 0.10. Matriz actualizada a la Trinidad Ontológica.
 */
set_time_limit(300);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$baseDir = dirname(__DIR__, 3);
$bitacoraFile = $baseDir . '/config/bitacora.json';
$memoryDir = $baseDir . '/.memory/conversations';

if (!is_dir($memoryDir)) {
    $memoryDir = dirname(__DIR__) . '/.memory/conversations';
}

function leerJSON($archivo) {
    if (!file_exists($archivo)) return [];
    $data = json_decode(file_get_contents($archivo), true);
    return $data ?? [];
}

function guardarJSON($archivo, $datos) {
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
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
    // MATRIZ ACTUALIZADA A LA TRINIDAD ONTOLÓGICA
    $matriz = [
        'tekne' => ['tekne' => 0.5, 'ars' => 0.2, 'espiritu' => 0.3, 'log' => 0.4],
        'ars' => ['tekne' => 0.2, 'ars' => 0.5, 'espiritu' => 0.4, 'log' => 0.3],
        'espiritu' => ['tekne' => 0.3, 'ars' => 0.4, 'espiritu' => 0.5, 'log' => 0.4],
        'log' => ['tekne' => 0.4, 'ars' => 0.3, 'espiritu' => 0.4, 'log' => 0.5],
        // Retrocompatibilidad con tipos antiguos
        'observacion' => ['reflexion' => 0.3, 'intuicion' => 0.2, 'sintesis' => 0.4],
        'reflexion' => ['observacion' => 0.3, 'sintesis' => 0.4, 'pensamiento_critico' => 0.4],
        'sintesis' => ['reflexion' => 0.4, 'pensamiento_critico' => 0.4, 'observacion' => 0.3]
    ];
    return $matriz[$a][$b] ?? 0.1;
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

function determinarTipoRelacion($tipoA, $tipoB) {
    // MATRIZ ACTUALIZADA A LA TRINIDAD ONTOLÓGICA
    $matriz = [
        'tekne' => [
            'ars' => 'estructura_alma', 'espiritu' => 'metodo_sanacion', 'log' => 'huella_gobernanza', 'tekne' => 'refuerzo_metodo'
        ],
        'ars' => [
            'tekne' => 'alma_estructura', 'espiritu' => 'simbolo_sombra', 'log' => 'memoria_viva', 'ars' => 'resonancia_estetica'
        ],
        'espiritu' => [
            'tekne' => 'sanacion_metodo', 'ars' => 'sombra_simbolo', 'log' => 'conciencia_ascendente', 'espiritu' => 'profundizacion_sombra'
        ],
        'log' => [
            'tekne' => 'gobernanza_huella', 'ars' => 'gobernanza_memoria', 'espiritu' => 'gobernanza_conciencia', 'log' => 'espiral_continua'
        ],
        // Retrocompatibilidad
        'observacion' => ['reflexion' => 'profundiza', 'sintesis' => 'contribuye_a'],
        'sintesis' => ['reflexion' => 'concluye', 'observacion' => 'integra']
    ];
    return $matriz[$tipoA][$tipoB] ?? 'resuena_con';
}

try {
    $bitacora = leerJSON($bitacoraFile);
    
    if (empty($bitacora)) {
        echo json_encode(['success' => false, 'mensaje' => 'No hay bitácoras para relacionar'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    $conversaciones = [];
    if (is_dir($memoryDir)) {
        foreach (glob($memoryDir . '/*.json') as $archivo) {
            $conv = leerJSON($archivo);
            if (!empty($conv)) $conversaciones[] = $conv;
        }
    }
    
    $mapaConv = [];
    foreach ($conversaciones as $c) {
        $mapaConv[$c['id']] = $c;
    }
    
    $umbralSimilitud = 0.10;
    $relacionesDetectadas = [];
    
    foreach ($bitacora as $bitacoraA) {
        $idA = $bitacoraA['id'];
        $convA = $mapaConv[$idA] ?? null;
        if (!$convA) continue;
        
        $relacionesActuales = $bitacoraA['relacionado_a'] ?? [];
        
        foreach ($bitacora as $bitacoraB) {
            $idB = $bitacoraB['id'];
            if ($idA === $idB || in_array($idB, $relacionesActuales)) continue;
            
            $convB = $mapaConv[$idB] ?? null;
            if (!$convB) continue;
            
            $conceptosA = $convA['analisis']['conceptos'] ?? [];
            $conceptosB = $convB['analisis']['conceptos'] ?? [];
            
            $simConceptos = calcularSimilitudConceptos($conceptosA, $conceptosB);
            $simProceso = calcularSimilitudProceso($bitacoraA['proceso'] ?? 'general', $bitacoraB['proceso'] ?? 'general');
            $simPensamiento = calcularSimilitudPensamiento($bitacoraA['tipo_pensamiento'] ?? 'general', $bitacoraB['tipo_pensamiento'] ?? 'general');
            $simTemporal = calcularSimilitudTemporal($bitacoraA['fecha'] ?? '', $bitacoraB['fecha'] ?? '');
            
            $total = ($simConceptos * 0.4) + ($simProceso * 0.3) + ($simPensamiento * 0.2) + ($simTemporal * 0.1);
            
            if ($total >= $umbralSimilitud) {
                $relacionesDetectadas[$idA][] = [
                    'id' => $idB,
                    'similitud_total' => round($total, 3),
                    'similitud_conceptos' => round($simConceptos, 3),
                    'similitud_proceso' => round($simProceso, 3),
                    'similitud_pensamiento' => round($simPensamiento, 3),
                    'similitud_temporal' => round($simTemporal, 3),
                    'conceptos_compartidos' => array_values(array_intersect($conceptosA, $conceptosB)),
                    'tipo_relacion' => determinarTipoRelacion(
                        $bitacoraA['tipo_pensamiento'] ?? 'general',
                        $bitacoraB['tipo_pensamiento'] ?? 'general'
                    )
                ];
            }
        }
    }
    
    $actualizaciones = 0;
    foreach ($bitacora as &$entrada) {
        $id = $entrada['id'];
        if (isset($relacionesDetectadas[$id])) {
            $relacionesActuales = $entrada['relacionado_a'] ?? [];
            foreach ($relacionesDetectadas[$id] as $nuevaRelacion) {
                if (!in_array($nuevaRelacion['id'], $relacionesActuales)) {
                    $relacionesActuales[] = $nuevaRelacion['id'];
                    $actualizaciones++;
                }
            }
            $entrada['relacionado_a'] = $relacionesActuales;
        }
    }
    
    guardarJSON($bitacoraFile, $bitacora);
    
    $reporte = [];
    foreach ($relacionesDetectadas as $id => $relaciones) {
        foreach ($relaciones as $rel) {
            $reporte[] = [
                'origen' => $id,
                'destino' => $rel['id'],
                'similitud_total' => $rel['similitud_total'],
                'conceptos_compartidos' => $rel['conceptos_compartidos'],
                'tipo_relacion' => $rel['tipo_relacion']
            ];
        }
    }
    
    echo json_encode([
        'success' => true,
        'mensaje' => 'Relacionamiento completado (Umbral 0.10 - Matriz Trinidad)',
        'estadisticas' => [
            'total_bitacoras' => count($bitacora),
            'total_conversaciones' => count($conversaciones),
            'relaciones_detectadas' => count($reporte),
            'actualizaciones_realizadas' => $actualizaciones
        ],
        'relaciones' => $reporte
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $e->getMessage()], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>