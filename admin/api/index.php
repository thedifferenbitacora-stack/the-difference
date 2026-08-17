<?php
/**
 * FRONT CONTROLLER · Vercel (runtime vercel-php)
 * v2: chdir() para que los includes relativos (config.php, functions.php)
 *     se resuelvan desde la carpeta de cada página.
 * Los assets estáticos (imágenes/videos) los sirve Vercel directo (filesystem).
 * El taller con guardado sigue siendo el local; Vercel = galería pública.
 */
$base = dirname(__DIR__);
$uri  = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$uri  = urldecode($uri);

if ($uri === '/' || $uri === '') { $uri = '/portada.php'; }

// Seguridad: sin traversal
$uri  = str_replace(['..', "\0"], '', $uri);
$file = realpath($base . $uri);

if ($file && strpos($file, $base) === 0 && is_file($file)) {
    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));

    if ($ext === 'php') {
        chdir(dirname($file));   // ← LA CLAVE: ejecuta cada página desde su carpeta
        require $file;           // __DIR__ de cada archivo se mantiene correcto
        exit;
    }

    // JSON/CSS/JS que llegue aquí se sirve con su MIME
    $mime = array(
        'json' => 'application/json',
        'css'  => 'text/css',
        'js'   => 'text/javascript',
        'svg'  => 'image/svg+xml'
    );
    if (isset($mime[$ext])) {
        header('Content-Type: ' . $mime[$ext]);
        readfile($file);
        exit;
    }
}

http_response_code(404);
echo '404 · No encontrado';