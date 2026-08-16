<?php
// C:\PROYECTO\THE DIFFERENCE\the-difference-php\guardar_entrada.php

require_once 'config.php';
require_once 'functions.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

// Obtener datos del formulario
$datos = [
    'nodo_slug' => $_POST['nodo_slug'] ?? 'log',
    'titulo' => $_POST['titulo'] ?? '',
    'contenido' => $_POST['contenido'] ?? '',
    'tipo' => $_POST['tipo'] ?? 'interpelacion',
    'nivel_poder' => $_POST['nivel_poder'] ?? 'base',
    'estado_energetico' => $_POST['estado_energetico'] ?? '',
    'salud_mental' => $_POST['salud_mental'] ?? '',
    'herida_simbolica' => $_POST['herida_simbolica'] ?? '',
    'transmutacion' => $_POST['transmutacion'] ?? '',
    'fase_cosecha' => $_POST['fase_cosecha'] ?? 'crecimiento'
];

// Validar datos requeridos
if (empty($datos['titulo']) || empty($datos['contenido'])) {
    die("Título y contenido son requeridos");
}

// Obtener nodo
$nodo = obtenerNodoPorSlug($pdo, $datos['nodo_slug']);
if (!$nodo) {
    die("Nodo no encontrado");
}

$datos['nodo_id'] = $nodo['id'];

// Crear entrada
$entrada_id = crearEntrada($pdo, $datos);

// Obtener entrada completa para guardar en JSON
$stmt = $pdo->prepare("SELECT * FROM entradas WHERE id = ?");
$stmt->execute([$entrada_id]);
$entrada_completa = $stmt->fetch();
$entrada_completa['nodo_slug'] = $datos['nodo_slug'];

// Guardar en JSON
guardarEntradaEnJSON($entrada_completa);

// Redirigir de vuelta al nodo
header('Location: index.php?nodo=' . $datos['nodo_slug'] . '&success=1');
exit;
?>