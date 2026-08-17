<?php
error_reporting(E_ALL);
ini_set('display_errors', '1');

echo "<h2>TEST 2: Cargando portada.php</h2>";

$portada = __DIR__ . '/../portada.php';

if (!file_exists($portada)) {
    die("ERROR: portada.php NO EXISTE en $portada");
}

echo "portada.php encontrado<br>";
echo "Intentando cargar...<br>";

chdir(dirname($portada));

// Intentar cargar
try {
    require $portada;
} catch (\Throwable $t) {
    echo "<pre style='background:#220000;color:#ff8888;padding:12px'>";
    echo "ERROR CAPTURADO: " . htmlspecialchars($t->getMessage()) . "<br>";
    echo "Archivo: " . htmlspecialchars($t->getFile()) . ":" . $t->getLine() . "<br>";
    echo "Stack trace:<br>" . $t->getTraceAsString();
    echo "</pre>";
}
