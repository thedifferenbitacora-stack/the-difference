<?php
/**
 * THE DIFFERENCE · Front Controller (Vercel)
 * Enruta cada URL a su PHP real en la raíz del repo.
 */

$uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
$uri = rawurldecode($uri);

if ($uri === '/' || $uri === '') {
    $uri = '/portada.php';
}

$file = __DIR__ . '/..' . $uri;

if (preg_match('~\.php$~i', $uri) && is_file($file)) {
    chdir(dirname($file));
    require $file;
    exit;
}

http_response_code(404);
echo '<h1>404 · Página no encontrada</h1>';
