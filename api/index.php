<?php
/**
 * THE DIFFERENCE · Front Controller (Vercel) + diagnóstico
 */
error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('log_errors', '1');

register_shutdown_function(function () {
    $e = error_get_last();
    if ($e && in_array($e['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        echo '<pre style="background:#220000;color:#ff8888;padding:12px;margin-top:20px">'
            . 'FATAL PHP: ' . htmlspecialchars($e['message'])
            . ' @ ' . $e['file'] . ':' . $e['line'] . '</pre>';
    }
});

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
