<?php
/**
 * CONSEJO DE GUARDIANES - SEGURIDAD ONTOLÓGICA
 * Agentes que cuestionan y validan cada interacción con las IAs
 */
header('Content-Type: application/json; charset=utf-8');

session_start();
$baseDir = dirname(__DIR__, 2);
$guardianesFile = $baseDir . '/config/guardianes.json';

// Cargar configuración de guardianes
$guardianes = file_exists($guardianesFile) ? json_decode(file_get_contents($guardianesFile), true) : [];

// Guardianes disponibles
$guardianesDisponibles = [
    'identidad' => [
        'nombre' => 'Guardián de Identidad',
        'pregunta' => '¿Quién eres y por qué accedes a estos datos?',
        'validacion' => 'Verificar autenticación y permisos'
    ],
    'proposito' => [
        'nombre' => 'Guardián de Propósito',
        'pregunta' => '¿Cuál es el propósito legítimo de esta acción?',
        'validacion' => 'Validar que el propósito esté alineado con The Difference'
    ],
    'integridad' => [
        'nombre' => 'Guardián de Integridad',
        'pregunta' => '¿Los datos están completos y sin corrupción?',
        'validacion' => 'Verificar checksum e integridad de archivos'
    ],
    'etica' => [
        'nombre' => 'Guardián de Ética',
        'pregunta' => '¿Esta acción es ética y respeta la privacidad?',
        'validacion' => 'Evaluar implicaciones éticas y de privacidad'
    ],
    'memoria' => [
        'nombre' => 'Guardián de Memoria',
        'pregunta' => '¿Se registró toda la interacción en la bitácora?',
        'validacion' => 'Verificar que todo quede trazado en LOG'
    ]
];

// Si es GET, devolver estado de guardianes
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    echo json_encode([
        'success' => true,
        'guardianes' => $guardianesDisponibles,
        'activos' => $guardianes['activos'] ?? array_keys($guardianesDisponibles),
        'ultimo_control' => $guardianes['ultimo_control'] ?? null
    ]);
    exit;
}

// Si es POST, validar acceso
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    
    $accion = $input['accion'] ?? '';
    $datos = $input['datos'] ?? [];
    $usuario = $_SESSION['user_id'] ?? 'anonimo';
    
    $resultadoGuardianes = [];
    $accesoPermitido = true;
    
    // Ejecutar cada guardián activo
    foreach ($guardianes['activos'] ?? array_keys($guardianesDisponibles) as $guardianKey) {
        $guardian = $guardianesDisponibles[$guardianKey];
        
        // Simulación de validación (aquí iría la lógica real)
        $validacion = [
            'guardian' => $guardian['nombre'],
            'pregunta' => $guardian['pregunta'],
            'respuesta' => 'Validado',
            'estado' => 'APROBADO',
            'timestamp' => date('c')
        ];
        
        // Lógica específica por guardián
        switch ($guardianKey) {
            case 'identidad':
                if (empty($usuario) || $usuario === 'anonimo') {
                    $validacion['estado'] = 'DENEGADO';
                    $validacion['razon'] = 'Usuario no autenticado';
                    $accesoPermitido = false;
                }
                break;
                
            case 'proposito':
                if (empty($input['proposito'])) {
                    $validacion['estado'] = 'CUESTIONADO';
                    $validacion['razon'] = 'Propósito no declarado';
                }
                break;
                
            case 'integridad':
                // Verificar que los datos no estén corruptos
                if (!is_array($datos) && !is_string($datos)) {
                    $validacion['estado'] = 'CUESTIONADO';
                    $validacion['razon'] = 'Formato de datos inválido';
                }
                break;
                
            case 'etica':
                // Aquí irían validaciones éticas complejas
                // Por ejemplo: detectar si se intenta acceder a datos sensibles
                break;
                
            case 'memoria':
                // Verificar que se registre en LOG
                $logFile = $baseDir . '/config/bitacora.json';
                if (!file_exists($logFile)) {
                    $validacion['estado'] = 'CUESTIONADO';
                    $validacion['razon'] = 'Bitácora LOG no existe';
                }
                break;
        }
        
        $resultadoGuardianes[] = $validacion;
        
        if ($validacion['estado'] === 'DENEGADO') {
            $accesoPermitido = false;
        }
    }
    
    // Guardar registro del control
    $guardianes['ultimo_control'] = [
        'fecha' => date('c'),
        'accion' => $accion,
        'usuario' => $usuario,
        'resultado' => $accesoPermitido ? 'APROBADO' : 'DENEGADO',
        'guardianes' => $resultadoGuardianes
    ];
    
    file_put_contents($guardianesFile, json_encode($guardianes, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    
    // Responder
    if ($accesoPermitido) {
        echo json_encode([
            'success' => true,
            'mensaje' => 'Acceso autorizado por el Consejo de Guardianes',
            'guardianes' => $resultadoGuardianes,
            'timestamp' => date('c')
        ]);
    } else {
        http_response_code(403);
        echo json_encode([
            'success' => false,
            'mensaje' => 'Acceso denegado por el Consejo de Guardianes',
            'guardianes' => $resultadoGuardianes,
            'razon' => 'Uno o más guardianes denegaron el acceso',
            'timestamp' => date('c')
        ]);
    }
    exit;
}
?>