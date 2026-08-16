<?php
/**
 * AGENTE DE EVOLUCIÓN - THE DIFFERENCE
 * Rastrea la genealogía y transformación de los conceptos en el tiempo
 * Filosofía: "El pensamiento no es estático, es un río que fluye"
 */

set_time_limit(300);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');

$baseDir = dirname(__DIR__, 3);
$bitacoraFile = $baseDir . '/config/bitacora.json';
$memoryDir = $baseDir . '/.memory/conversations';
$evolutionDir = $baseDir . '/.memory/evolution';

if (!is_dir($evolutionDir)) {
    mkdir($evolutionDir, 0755, true);
}

function leerJSON($archivo) {
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?? [];
}

function guardarJSON($archivo, $datos) {
    file_put_contents($archivo, json_encode($datos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

try {
    $bitacora = leerJSON($bitacoraFile);
    
    if (empty($bitacora)) {
        echo json_encode(['success' => false, 'mensaje' => 'No hay bitácoras para analizar'], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        exit;
    }
    
    // Cargar conversaciones procesadas
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
    
    // 1. ORDENAR BITÁCORAS CRONOLÓGICAMENTE (de más antigua a más reciente)
    usort($bitacora, function($a, $b) {
        $fechaA = strtotime($a['fecha'] ?? '2000-01-01');
        $fechaB = strtotime($b['fecha'] ?? '2000-01-01');
        return $fechaA <=> $fechaB;
    });
    
    // 2. RASTREAR APARICIONES DE CADA CONCEPTO
    $historialConceptos = [];
    
    foreach ($bitacora as $entrada) {
        $id = $entrada['id'];
        $fecha = $entrada['fecha'] ?? '';
        $proceso = $entrada['proceso'] ?? 'general';
        $tipoPensamiento = $entrada['tipo_pensamiento'] ?? 'general';
        
        $conv = $mapaConv[$id] ?? null;
        $conceptos = $conv ? ($conv['analisis']['conceptos'] ?? []) : [];
        $resumen = $conv ? ($conv['analisis']['resumen'] ?? '') : ($entrada['contenido'] ?? '');
        
        foreach ($conceptos as $concepto) {
            $conceptoLower = mb_strtolower($concepto, 'UTF-8');
            
            if (!isset($historialConceptos[$conceptoLower])) {
                $historialConceptos[$conceptoLower] = [
                    'concepto' => $conceptoLower,
                    'total_apariciones' => 0,
                    'primera_aparicion' => null,
                    'ultima_aparicion' => null,
                    'trayectoria' => [],
                    'procesos_involucrados' => [],
                    'tipos_pensamiento' => []
                ];
            }
            
            $historialConceptos[$conceptoLower]['total_apariciones']++;
            $historialConceptos[$conceptoLower]['procesos_involucrados'][] = $proceso;
            $historialConceptos[$conceptoLower]['tipos_pensamiento'][] = $tipoPensamiento;
            
            $historialConceptos[$conceptoLower]['trayectoria'][] = [
                'id' => $id,
                'fecha' => $fecha,
                'proceso' => $proceso,
                'tipo_pensamiento' => $tipoPensamiento,
                'resumen_contexto' => substr(strip_tags($resumen), 0, 100) . '...'
            ];
            
            // Actualizar primera y última aparición
            if (!$historialConceptos[$conceptoLower]['primera_aparicion']) {
                $historialConceptos[$conceptoLower]['primera_aparicion'] = [
                    'id' => $id,
                    'fecha' => $fecha,
                    'tipo' => $tipoPensamiento
                ];
            }
            $historialConceptos[$conceptoLower]['ultima_aparicion'] = [
                'id' => $id,
                'fecha' => $fecha,
                'tipo' => $tipoPensamiento
            ];
        }
    }
    
    // 3. GENERAR RESUMEN DE EVOLUCIÓN Y LIMPIAR DATOS
    $evoluciones = [];
    foreach ($historialConceptos as $concepto => $datos) {
        // Solo analizar conceptos que aparecen 2 o más veces (para ver evolución real)
        if ($datos['total_apariciones'] >= 2) {
            $procesosUnicos = array_unique($datos['procesos_involucrados']);
            $tiposUnicos = array_unique($datos['tipos_pensamiento']);
            
            $primera = $datos['primera_aparicion'];
            $ultima = $datos['ultima_aparicion'];
            
            // Generar narrativa de evolución automática
            $narrativa = "El concepto surgió inicialmente como '{$primera['tipo']}' ";
            if ($primera['tipo'] !== $ultima['tipo']) {
                $narrativa .= "y evolucionó hacia '{$ultima['tipo']}'. ";
            } else {
                $narrativa .= "manteniendo su naturaleza '{$primera['tipo']}'. ";
            }
            
            if (count($procesosUnicos) > 1) {
                $narrativa .= "Transitó entre los procesos: " . implode(', ', $procesosUnicos) . ".";
            } else {
                $narrativa .= "Se consolidó dentro del proceso '{$procesosUnicos[0]}'.";
            }
            
            $evoluciones[$concepto] = [
                'concepto' => $concepto,
                'frecuencia' => $datos['total_apariciones'],
                'primera_aparicion' => $primera,
                'ultima_aparicion' => $ultima,
                'procesos' => $procesosUnicos,
                'tipos_pensamiento' => $tiposUnicos,
                'narrativa_evolucion' => $narrativa,
                'trayectoria_detallada' => $datos['trayectoria']
            ];
        }
    }
    
    // Ordenar evoluciones por frecuencia (los más evolutivos primero)
    uasort($evoluciones, function($a, $b) {
        return $b['frecuencia'] <=> $a['frecuencia'];
    });
    
    // 4. GUARDAR EN MEMORIA DE EVOLUCIÓN
    $archivoEvolucion = $evolutionDir . '/mapa_evolucion.json';
    guardarJSON($archivoEvolucion, [
        'fecha_generacion' => date('Y-m-d H:i:s'),
        'total_conceptos_rastreados' => count($evoluciones),
        'evoluciones' => array_values($evoluciones)
    ]);
    
    // 5. RESPUESTA JSON
    echo json_encode([
        'success' => true,
        'mensaje' => 'Análisis de evolución completado',
        'estadisticas' => [
            'total_bitacoras_analizadas' => count($bitacora),
            'total_conversaciones' => count($conversaciones),
            'conceptos_con_evolucion_detectada' => count($evoluciones),
            'archivo_guardado' => '.memory/evolution/mapa_evolucion.json'
        ],
        'top_evoluciones' => array_slice(array_values($evoluciones), 0, 5) // Mostrar top 5 en la respuesta
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error en el agente de evolución',
        'mensaje' => $e->getMessage()
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
}
?>