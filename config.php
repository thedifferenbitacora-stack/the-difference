<?php
// config.php - Configuración de Base de Datos
// Ubicación: C:\Program Files\AMPPS\www\the-difference-php\config.php

// Configuración de MySQL (AMPPS)
$host = 'localhost';
$dbname = 'the_difference';
$username = 'root';
$password = 'EO7PwSCthFp2@FfM'; // Contraseña real de AMPPS

// Crear conexión PDO
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    
    // Configurar PDO para que lance excepciones en caso de error
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    
} catch (PDOException $e) {
    // Si hay error de conexión, mostrar mensaje claro
    die("Error de conexión: " . $e->getMessage());
}
?>