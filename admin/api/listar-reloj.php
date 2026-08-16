<?php
header('Content-Type: application/json; charset=utf-8');
$baseDir  = dirname(__DIR__, 2);
$dataFile = $baseDir . '/data/reloj-consciente.json';

$sesiones = array();
if (file_exists($dataFile)) {
    $tmp = json_decode(file_get_contents($dataFile), true);
    if (is_array($tmp)) { $sesiones = $tmp; }
}

$totalHoras = 0; $totalValor = 0; $porDia = array();
foreach ($sesiones as $s) {
    $h = isset($s['duracion_horas']) ? (float)$s['duracion_horas'] : 0;
    $v = isset($s['valor']) ? (float)$s['valor'] : 0;
    $f = isset($s['fecha']) ? $s['fecha'] : date('Y-m-d');
    $totalHoras += $h;
    $totalValor += $v;
    if (!isset($porDia[$f])) $porDia[$f] = array('horas'=>0,'valor'=>0);
    $porDia[$f]['horas'] += $h;
    $porDia[$f]['valor'] += $v;
}

echo json_encode(array(
    'sesiones' => $sesiones,
    'kpi' => array(
        'total_horas' => round($totalHoras, 2),
        'total_valor' => round($totalValor, 0),
        'total_sesiones' => count($sesiones)
    ),
    'por_dia' => $porDia
), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>