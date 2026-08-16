<?php
// C:\PROYECTO\THE DIFFERENCE\the-difference-php\functions.php

// Nota: Asegúrate de que DATA_PATH esté definido en config.php o aquí
if (!defined('DATA_PATH')) {
    define('DATA_PATH', __DIR__ . '/data');
}

function guardarEntradaEnJSON($entrada) {
    $nodo_slug = $entrada['nodo_slug'];
    $archivo = DATA_PATH . "/{$nodo_slug}/entrada_{$entrada['id']}.json";
    
    // Crear carpeta del nodo si no existe
    $carpeta = DATA_PATH . "/{$nodo_slug}";
    if (!is_dir($carpeta)) {
        mkdir($carpeta, 0777, true);
    }
    
    file_put_contents($archivo, json_encode($entrada, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

function obtenerNodoPorSlug($pdo, $slug) {
    $stmt = $pdo->prepare("SELECT * FROM nodos WHERE slug = ?");
    $stmt->execute([$slug]);
    return $stmt->fetch();
}

// ✅ FUNCIÓN CORREGIDA AQUÍ ✅
function obtenerEntradasPorNodo($pdo, $nodo_id, $limite = 50) {
    $stmt = $pdo->prepare("
        SELECT * FROM entradas 
        WHERE nodo_id = :nodo_id
        ORDER BY creado_en DESC 
        LIMIT :limite
    ");
    
    // Forzamos que los parámetros sean tratados como ENTEROS (INT)
    $stmt->bindValue(':nodo_id', $nodo_id, PDO::PARAM_INT);
    $stmt->bindValue(':limite', $limite, PDO::PARAM_INT);
    
    $stmt->execute();
    return $stmt->fetchAll();
}

function crearEntrada($pdo, $datos) {
    $sql = "
        INSERT INTO entradas 
        (nodo_id, titulo, contenido, tipo, nivel_poder, estado_energetico, salud_mental, herida_simbolica, transmutacion, fase_cosecha)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $datos['nodo_id'],
        $datos['titulo'],
        $datos['contenido'],
        $datos['tipo'],
        $datos['nivel_poder'] ?? 'base',
        $datos['estado_energetico'] ?? 'estable',
        $datos['salud_mental'] ?? 'estable',
        $datos['herida_simbolica'] ?? '',
        $datos['transmutacion'] ?? '',
        $datos['fase_cosecha'] ?? 'crecimiento'
    ]);
    
    return $pdo->lastInsertId();
}

function formatearFecha($fecha) {
    return date('d/m/Y H:i', strtotime($fecha));
}

function obtenerColorPorTipo($tipo) {
    $colores = [
        'interpelacion' => '#ff6b6b',
        'reparacion' => '#4ecdc4',
        'programacion' => '#45b7d1',
        'luminiscencia' => '#f9ca24'
    ];
    
    return $colores[$tipo] ?? '#ffffff';
}
?>