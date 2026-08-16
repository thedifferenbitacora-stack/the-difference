<?php
/**
 * API DE TRAZABILIDAD - THE DIFFERENCE
 * Endpoint maestro que conecta nodos, bitácoras y memoria
 * Filosofía: "El Decir es Huella. La Huella es Trazabilidad."
 */

// Headers para API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

// Manejo de preflight CORS
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

// Configuración de rutas
$baseDir = dirname(__DIR__, 2); // Sube desde admin/api/ a la raíz del proyecto
$bitacoraFile = $baseDir . '/config/bitacora.json';
$settingsFile = $baseDir . '/config/settings.json';
$memoryDir = $baseDir . '/.memory';

// ============================================
// FUNCIÓN: Leer archivo JSON con seguridad
// ============================================
function leerJSON($archivo) {
    if (!file_exists($archivo)) {
        return [];
    }
    
    $contenido = file_get_contents($archivo);
    $datos = json_decode($contenido, true);
    
    return $datos ?? [];
}

// ============================================
// FUNCIÓN: Escanear carpeta .memory
// ============================================
function escanearMemoria($memoryDir) {
    $estructura = [];
    
    if (!is_dir($memoryDir)) {
        return $estructura;
    }
    
    $directorios = scandir($memoryDir);
    
    foreach ($directorios as $dir) {
        if ($dir === '.' || $dir === '..') {
            continue;
        }
        
        $rutaCompleta = $memoryDir . '/' . $dir;
        
        if (is_dir($rutaCompleta)) {
            $archivos = array_diff(scandir($rutaCompleta), ['.', '..']);
            $estructura[$dir] = [
                'tipo' => 'directorio',
                'cantidad' => count($archivos),
                'archivos' => array_values($archivos)
            ];
        } else {
            $estructura[$dir] = [
                'tipo' => 'archivo',
                'tamano' => filesize($rutaCompleta)
            ];
        }
    }
    
    return $estructura;
}

// ============================================
// FUNCIÓN: Construir grafo de relaciones
// ============================================
function construirGrafo($bitacora) {
    $nodos = [];
    $aristas = [];
    
    foreach ($bitacora as $entrada) {
        $id = $entrada['id'] ?? uniqid();
        
        // Crear nodo
        $nodos[] = [
            'id' => $id,
            'titulo' => $entrada['titulo'] ?? 'Sin título',
            'categoria' => $entrada['categoria'] ?? 'general',
            'fecha' => $entrada['fecha'] ?? date('Y-m-d'),
            'tipo' => $entrada['tipo_pensamiento'] ?? 'observacion'
        ];
        
        // Crear aristas si hay relaciones
        if (isset($entrada['relacionado_a']) && is_array($entrada['relacionado_a'])) {
            foreach ($entrada['relacionado_a'] as $relacionId) {
                $aristas[] = [
                    'source' => $id,
                    'target' => $relacionId,
                    'tipo' => 'relacionado'
                ];
            }
        }
    }
    
    return [
        'nodos' => $nodos,
        'aristas' => $aristas,
        'total_nodos' => count($nodos),
        'total_aristas' => count($aristas)
    ];
}

// ============================================
// FUNCIÓN: Obtener estadísticas del sistema
// ============================================
function obtenerEstadisticas($bitacora, $estructuraMemoria) {
    $categorias = [];
    $tiposPensamiento = [];
    
    foreach ($bitacora as $entrada) {
        $cat = $entrada['categoria'] ?? 'general';
        $tipo = $entrada['tipo_pensamiento'] ?? 'observacion';
        
        $categorias[$cat] = ($categorias[$cat] ?? 0) + 1;
        $tiposPensamiento[$tipo] = ($tiposPensamiento[$tipo] ?? 0) + 1;
    }
    
    return [
        'total_entradas' => count($bitacora),
        'categorias' => $categorias,
        'tipos_pensamiento' => $tiposPensamiento,
        'memoria' => [
            'directorios' => count(array_filter($estructuraMemoria, fn($v) => ($v['tipo'] ?? '') === 'directorio')),
            'archivos_totales' => array_sum(array_column($estructuraMemoria, 'cantidad'))
        ]
    ];
}

// ============================================
// EJECUCIÓN PRINCIPAL
// ============================================

try {
    // 1. Cargar datos
    $bitacora = leerJSON($bitacoraFile);
    $settings = leerJSON($settingsFile);
    $estructuraMemoria = escanearMemoria($memoryDir);
    
    // 2. Construir grafo
    $grafo = construirGrafo($bitacora);
    
    // 3. Obtener estadísticas
    $estadisticas = obtenerEstadisticas($bitacora, $estructuraMemoria);
    
    // 4. Definir nodos del sistema (basado en tu estructura)
    $nodosSistema = [
        'ars-tekne' => [
            'nombre' => 'Ars Tekne',
            'panel' => 'panel-ars-tekne.php',
            'activo' => true
        ],
        'le-tematik' => [
            'nombre' => 'Le Tematik',
            'panel' => 'panel-le-tematik.php',
            'activo' => true
        ],
        'project-nada-brahma' => [
            'nombre' => 'Project Nada Brahma',
            'panel' => 'panel-project-nada-brahma.php',
            'activo' => true
        ],
        'texvn' => [
            'nombre' => 'TEXVN',
            'panel' => 'panel-texvn.php',
            'activo' => true
        ],
        'quantumlab' => [
            'nombre' => 'Quantum Lab',
            'panel' => 'panel-quantumlab.php',
            'activo' => true
        ],
        'saiayin-do' => [
            'nombre' => 'Saiayin Do',
            'panel' => 'panel-saiayin-do.php',
            'activo' => true
        ],
        'quiron-theatre' => [
            'nombre' => 'Quirón Theatre',
            'panel' => 'panel-quiron-theatre.php',
            'activo' => true
        ],
        'pensamiento-autista' => [
            'nombre' => 'Pensamiento Autista',
            'panel' => 'panel-pensamiento-autista.php',
            'activo' => true
        ]
    ];
    
    // 5. Construir respuesta completa
    $respuesta = [
        'success' => true,
        'timestamp' => date('Y-m-d H:i:s'),
        'version' => '1.0.0',
        'datos' => [
            'bitacora' => [
                'total' => count($bitacora),
                'entradas' => array_slice($bitacora, 0, 50) // Últimas 50 entradas
            ],
            'grafo' => $grafo,
            'estadisticas' => $estadisticas,
            'nodos_sistema' => $nodosSistema,
            'memoria' => $estructuraMemoria,
            'configuracion' => [
                'visual' => $settings['visual'] ?? [],
                'ontologico' => $settings['ontologico'] ?? []
            ]
        ],
        'meta' => [
            'filosofia' => 'El Ser Ahí es Presencia. El Decir es Huella.',
            'modo_ia' => 'espejo',
            'ritmo' => 'pausado'
        ]
    ];
    
    // 6. Enviar respuesta
    echo json_encode($respuesta, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        'success' => false,
        'error' => 'Error en el servidor',
        'mensaje' => $e->getMessage(),
        'timestamp' => date('Y-m-d H:i:s')
    ], JSON_PRETTY_PRINT);
}
?>