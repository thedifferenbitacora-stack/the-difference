<?php
/**
 * RECOPILATORIO · Inteligencia Local + SINCRONIZACIÓN
 * Al guardar: archiva en recopilatorio + entrada en Bitácora + tareas al Panel-General.
 */
$baseDir = dirname(__DIR__);
$recFile = $baseDir . '/data/recopilatorio.json';
$tareasFile = $baseDir . '/config/tareas.json';
$bitFile = $baseDir . '/config/bitacora.json';

$db = file_exists($recFile) ? json_decode(file_get_contents($recFile), true) : array('registros'=>array());
if (!isset($db['registros']) || !is_array($db['registros'])) $db['registros'] = array();

$mensaje = '';

function parseTag($texto) {
    $area = 'general'; $titulo = trim($texto);
    if (preg_match('/^\[([a-zá-ú]+)\]\s*(.+)$/iu', $titulo, $m)) {
        $area = strtolower($m[1]); $titulo = trim($m[2]);
    }
    return array($area, $titulo);
}

// ── EXPORTAR EXCEL (CSV) ─────────────────────────────────
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=recopilatorio-the-difference.csv');
    $out = fopen('php://output', 'w');
    fputcsv($out, array('id','fecha','taller','roles','etapa','horas','valor_hora_constante','valor_total','avances','tareas_cubiertas','tareas_pendientes'));
    foreach ($db['registros'] as $r) {
        fputcsv($out, array(
            $r['id'], $r['fecha'], $r['taller'],
            is_array($r['roles']) ? implode(' | ', $r['roles']) : $r['roles'],
            $r['etapa'], $r['horas'], $r['valor_hora_constante'], $r['valor_total'],
            is_array($r['avances']) ? implode(' | ', $r['avances']) : $r['avances'],
            is_array($r['tareas_cubiertas']) ? implode(' | ', $r['tareas_cubiertas']) : $r['tareas_cubiertas'],
            is_array($r['tareas_pendientes']) ? implode(' | ', $r['tareas_pendientes']) : $r['tareas_pendientes']
        ));
    }
    fclose($out); exit;
}

// ── INGRESAR + SINCRONIZAR ───────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['json_entrada'])) {
    $entrada = json_decode($_POST['json_entrada'], true);
    if (is_array($entrada)) {
        $lista = isset($entrada['registros']) ? $entrada['registros'] : array($entrada);
        $agregados = 0; $sincTareas = 0; $cerradas = 0; $bitacoras = 0;

        // Cargar tareas y bitácora una sola vez
        $tareasDb = file_exists($tareasFile) ? json_decode(file_get_contents($tareasFile), true) : null;
        if (!is_array($tareasDb)) $tareasDb = array('tareas'=>array());
        if (!isset($tareasDb['tareas']) || !is_array($tareasDb['tareas'])) $tareasDb['tareas'] = array();
        $bit = file_exists($bitFile) ? json_decode(file_get_contents($bitFile), true) : null;
        if (!is_array($bit)) $bit = array();

        foreach ($lista as $e) {
            if (!is_array($e)) continue;
            $horas = isset($e['horas']) ? (float)$e['horas'] : 0;
            $vhc = isset($e['valor_hora_constante']) ? (float)$e['valor_hora_constante'] : 70000;
            $reg = array(
                'id' => isset($e['id']) ? $e['id'] : 'REC-' . strtoupper(substr(uniqid(), -6)),
                'fecha' => isset($e['fecha']) ? $e['fecha'] : date('Y-m-d'),
                'taller' => isset($e['taller']) ? $e['taller'] : 'Quantum Lab',
                'roles' => isset($e['roles']) ? $e['roles'] : array(),
                'etapa' => isset($e['etapa']) ? $e['etapa'] : 'proyecto_actual',
                'foco' => isset($e['foco']) ? $e['foco'] : array(),
                'avances' => isset($e['avances']) ? $e['avances'] : array(),
                'tareas_cubiertas' => isset($e['tareas_cubiertas']) ? $e['tareas_cubiertas'] : array(),
                'tareas_pendientes' => isset($e['tareas_pendientes']) ? $e['tareas_pendientes'] : array(),
                'horas' => $horas,
                'valor_hora_constante' => $vhc,
                'valor_total' => isset($e['valor_total']) ? (float)$e['valor_total'] : round($horas*$vhc,2),
                'clasificado_por' => isset($e['clasificado_por']) ? $e['clasificado_por'] : 'IA',
                'ingresado_por' => isset($e['ingresado_por']) ? $e['ingresado_por'] : 'Presidente'
            );
            $db['registros'][] = $reg;
            $agregados++;

            // 1) Tareas pendientes → Panel-General (sin duplicados)
            $existentes = array();
            foreach ($tareasDb['tareas'] as $t) $existentes[strtolower(trim($t['titulo']))] = true;
            foreach ($reg['tareas_pendientes'] as $p) {
                list($area, $titulo) = parseTag($p);
                $key = strtolower(trim($titulo));
                if (isset($existentes[$key])) continue;
                $tareasDb['tareas'][] = array(
                    'id' => 'T-' . strtoupper(substr(uniqid(), -6)),
                    'titulo' => $titulo, 'proyecto' => 'the-difference', 'area' => $area,
                    'estado' => 'pendiente', 'estimado_horas' => 0,
                    'fecha_creacion' => date('Y-m-d'), 'fecha_cierre' => null
                );
                $existentes[$key] = true; $sincTareas++;
            }

            // 2) Tareas cubiertas → cerrar si existen pendientes
            foreach ($tareasDb['tareas'] as &$t) {
                if ($t['estado'] !== 'pendiente') continue;
                $tk = strtolower(trim($t['titulo']));
                foreach ($reg['tareas_cubiertas'] as $c) {
                    list($ca, $ct) = parseTag($c);
                    if (strtolower(trim($ct)) === $tk) { $t['estado']='completada'; $t['fecha_cierre']=date('Y-m-d'); $cerradas++; break; }
                }
            }
            unset($t);

            // 3) Entrada en Bitácora
            array_unshift($bit, array(
                'id' => uniqid('BIT-'), 'fecha' => date('Y-m-d H:i:s'),
                'titulo' => 'Síntesis ' . $reg['fecha'] . ' · ' . $reg['taller'],
                'contenido' => is_array($reg['avances']) ? implode("\n", $reg['avances']) : (string)$reg['avances'],
                'categoria' => 'logro', 'estado' => 'activo'
            ));
            $bitacoras++;
        }

        file_put_contents($recFile, json_encode($db, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        file_put_contents($tareasFile, json_encode($tareasDb, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        file_put_contents($bitFile, json_encode($bit, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        $db = json_decode(file_get_contents($recFile), true);
        $mensaje = "✅ $agregados registro(s) · $sincTareas tareas→Panel-General · $cerradas cerradas · $bitacoras entrada(s)→Bitácora";
    } else {
        $mensaje = '❌ El JSON no es válido.';
    }
}

// ── AGREGADOS ────────────────────────────────────────────
$totalHoras=0; $totalValor=0; $rolesCount=array(); $areaHoras=array(); $tareasCompletadas=0; $ultimasPendientes=array();
foreach ($db['registros'] as $r) {
    $totalHoras += $r['horas']; $totalValor += $r['valor_total'];
    if (is_array($r['roles'])) foreach ($r['roles'] as $rol) $rolesCount[$rol] = (isset($rolesCount[$rol])?$rolesCount[$rol]:0)+1;
    if (is_array($r['foco'])) foreach ($r['foco'] as $a=>$h) $areaHoras[$a] = (isset($areaHoras[$a])?$areaHoras[$a]:0)+$h;
    if (is_array($r['tareas_cubiertas'])) $tareasCompletadas += count($r['tareas_cubiertas']);
    if (is_array($r['tareas_pendientes'])) $ultimasPendientes = $r['tareas_pendientes'];
}
arsort($rolesCount); arsort($areaHoras);
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Recopilatorio · The Difference</title>
<style>
*{margin:0;padding:0;box-sizing:border-box;}
body{font-family:system-ui,sans-serif;background:#0f0f0f;color:#fff;padding:2rem;}
.container{max-width:1200px;margin:0 auto;}
h1{color:#fffc34;font-size:1.8rem;margin-bottom:.3rem;}
.subtitle{color:#a0a0a0;margin-bottom:1.5rem;font-style:italic;}
.alert{padding:.8rem;border-radius:6px;background:rgba(16,185,129,.15);border:1px solid #10b981;color:#10b981;margin-bottom:1rem;}
.alert.err{background:rgba(239,68,68,.15);border-color:#ef4444;color:#ef4444;}
.nav{display:flex;gap:.8rem;flex-wrap:wrap;margin-bottom:2rem;}
.nav a{padding:.6rem 1.2rem;background:#252525;color:#fff;text-decoration:none;border-radius:6px;border:1px solid #333;}
.nav a:hover{border-color:#fffc34;}
.nav .excel{background:#10b981;border-color:transparent;font-weight:bold;}
.kpi-grid{display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:1rem;margin-bottom:2rem;}
.kpi{background:#252525;border:1px solid #333;border-top:4px solid #fffc34;border-radius:6px;padding:1rem;}
.kpi .label{font-size:.7rem;text-transform:uppercase;letter-spacing:1px;color:#a0a0a0;}
.kpi .value{font-size:1.6rem;font-weight:bold;color:#fffc34;margin-top:.3rem;}
.grid2{display:grid;grid-template-columns:repeat(auto-fit,minmax(340px,1fr));gap:1.5rem;margin-bottom:1.5rem;}
.card{background:#252525;border:1px solid #333;border-radius:8px;padding:1.5rem;margin-bottom:1.5rem;}
.card h3{color:#fffc34;font-size:1rem;margin-bottom:1rem;border-left:3px solid #fffc34;padding-left:.7rem;font-weight:normal;}
table{width:100%;border-collapse:collapse;font-size:.85rem;}
th,td{text-align:left;padding:.5rem;border-bottom:1px solid #333;color:#a0a0a0;vertical-align:top;}
th{color:#fffc34;}
textarea{width:100%;min-height:220px;background:#1a1a1a;color:#e0e0e0;border:1px solid #333;border-radius:6px;padding:1rem;font-family:'Courier New',monospace;font-size:.85rem;}
.btn{display:inline-block;margin-top:1rem;padding:.7rem 1.5rem;background:#fffc34;color:#000;border:none;border-radius:6px;font-weight:bold;cursor:pointer;}
.tag{display:inline-block;background:#333;color:#fffc34;padding:.15rem .6rem;border-radius:10px;font-size:.7rem;margin:.1rem;}
</style>
</head>
<body>
<div class="container">
<div class="nav">
    <a href="../panel-general.php">🕐 Panel-General</a>
    <a href="hub-central.php">🎯 Hub Central</a>
    <a href="bitacora.php">📓 Bitácora</a>
    <a href="?export=csv" class="excel">📥 Exportar Excel (CSV)</a>
</div>
<h1>🧠 Recopilatorio · Inteligencia Local</h1>
<p class="subtitle">Al guardar: sincroniza Bitácora + tareas pendientes al Panel-General</p>
<?php if ($mensaje): ?><div class="alert <?= strpos($mensaje,'❌')===0?'err':'' ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="kpi-grid">
    <div class="kpi"><div class="label">Registros</div><div class="value"><?= count($db['registros']) ?></div></div>
    <div class="kpi"><div class="label">Horas totales</div><div class="value"><?= round($totalHoras,2) ?></div></div>
    <div class="kpi"><div class="label">Valor total</div><div class="value">$<?= number_format($totalValor,0) ?></div></div>
    <div class="kpi"><div class="label">Tareas cubiertas</div><div class="value"><?= $tareasCompletadas ?></div></div>
</div>

<div class="card">
    <h3>➕ Ingresar síntesis clasificada (pega el JSON de la IA)</h3>
    <form method="POST">
        <textarea name="json_entrada" placeholder='Pega aquí el bloque JSON clasificado...'></textarea>
        <button type="submit" class="btn">Guardar + Sincronizar</button>
    </form>
</div>

<div class="grid2">
    <div class="card"><h3>🎭 Roles ejercidos</h3><div><?php foreach($rolesCount as $rol=>$n):?><span class="tag"><?= $rol ?> ×<?= $n ?></span><?php endforeach; ?><?php if(empty($rolesCount)) echo '<span style="color:#666;">Sin datos</span>'; ?></div></div>
    <div class="card"><h3>🎯 Foco por área (horas)</h3><table><tr><th>Área</th><th>Horas</th></tr><?php foreach($areaHoras as $a=>$h):?><tr><td><?= htmlspecialchars($a) ?></td><td><?= round($h,2) ?> h</td></tr><?php endforeach; ?><?php if(empty($areaHoras)):?><tr><td colspan="2" style="color:#666;">Sin datos</td></tr><?php endif;?></table></div>
</div>

<div class="card">
    <h3>🔗 Trazabilidad (estado completo)</h3>
    <table><tr><th>Fecha</th><th>Taller</th><th>Horas</th><th>Valor</th><th>Avances</th><th>Pendientes</th></tr>
    <?php foreach (array_reverse($db['registros']) as $r): ?>
    <tr><td><?= $r['fecha'] ?></td><td><?= htmlspecialchars($r['taller']) ?></td><td><?= round($r['horas'],2) ?></td><td>$<?= number_format($r['valor_total'],0) ?></td>
    <td><?= is_array($r['avances'])?implode('<br>· ',array_map('htmlspecialchars',$r['avances'])):htmlspecialchars($r['avances']) ?></td>
    <td><?= is_array($r['tareas_pendientes'])?implode('<br>· ',array_map('htmlspecialchars',$r['tareas_pendientes'])):htmlspecialchars($r['tareas_pendientes']) ?></td></tr>
    <?php endforeach; ?>
    <?php if (empty($db['registros'])): ?><tr><td colspan="6" style="text-align:center;color:#666;">Sin registros aún.</td></tr><?php endif; ?>
    </table>
</div>
</div>
</body>
</html>