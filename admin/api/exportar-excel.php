<?php
$baseDir = dirname(__DIR__, 2);
$file = $baseDir . '/data/bitacora-general.json';
$registros = file_exists($file) ? json_decode(file_get_contents($file), true) : [];
if (!is_array($registros)) $registros = [];

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="bitacora-general.csv"');
echo "\xEF\xBB\xBF"; // BOM para que Excel lea acentos

$out = fopen('php://output', 'w');
fputcsv($out, ['ID','Fecha','Taller','Roles','Proyecto','Area','Foco','Horas','ValorHora','ValorTotal','Estado','Avances','TareasCubiertas','TareasPendientes']);
foreach ($registros as $r) {
    fputcsv($out, array(
        $r['id'], $r['fecha'], $r['taller'],
        implode(' | ', $r['roles']), $r['proyecto'], $r['area'],
        implode(' | ', $r['foco']), $r['horas'], $r['valor_hora'], $r['valor_total'], $r['estado_proceso'],
        implode(' / ', $r['avances']),
        implode(' / ', $r['tareas_cubiertas']),
        implode(' / ', $r['tareas_pendientes'])
    ));
}
fclose($out);
?>