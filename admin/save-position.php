<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$configFile = dirname(__DIR__) . '/config/settings.json';

if (!file_exists($configFile)) {
    echo json_encode(['success' => false, 'message' => 'Config file not found']);
    exit;
}

$config = json_decode(file_get_contents($configFile), true) ?: [];
$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid data']);
    exit;
}

$saved = [];
$errors = [];

// Manejar array de posiciones (movimiento en bloque)
if (isset($input['positions']) && is_array($input['positions'])) {
    foreach ($input['positions'] as $pos) {
        if (!isset($pos['key']) || !isset($pos['value'])) continue;
        
        $key = $pos['key'];
        $value = round((float)$pos['value'], 2);
        
        if (strpos($key, 'portada_') !== 0 && strpos($key, 'menu_') !== 0) {
            $errors[] = "Invalid key: $key";
            continue;
        }
        
        $config[$key] = $value;
        $saved[] = $key;
    }
}
// Manejar posición individual (retrocompatibilidad)
elseif (isset($input['key']) && isset($input['x'])) {
    $key = $input['key'];
    $value = round((float)$input['x'], 2);
    
    if (strpos($key, 'portada_') !== 0 && strpos($key, 'menu_') !== 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid key prefix']);
        exit;
    }
    
    $config[$key] = $value;
    $saved[] = $key;
}
else {
    echo json_encode(['success' => false, 'message' => 'No valid data']);
    exit;
}

if (file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT))) {
    echo json_encode([
        'success' => true, 
        'saved' => count($saved),
        'keys' => $saved,
        'errors' => $errors
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Could not save']);
}