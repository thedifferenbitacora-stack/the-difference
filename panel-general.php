<?php
/**
 * PANEL-GENERAL · The Difference
 * Reloj consciente + RESET + PUBLICAR (git) + tareas + bitácora + valor integral
 * + gráficos de área + progreso · viñeta Inventario junto a Dashboard
 */
$baseDir    = __DIR__;
$relojFile  = $baseDir . '/data/reloj-consciente.json';
$tareasFile = $baseDir . '/config/tareas.json';

// Carga segura (sin ternarios anidados)
$sesiones = array();
if (file_exists($relojFile)) {
    $tmp = json_decode(file_get_contents($relojFile), true);
    if (is_array($tmp)) { $sesiones = $tmp; }
}

$tareas = array('tareas' => array());
if (file_exists($tareasFile)) {
    $tmp = json_decode(file_get_contents($tareasFile), true);
    if (is_array($tmp)) { $tareas = $tmp; }
}
if (!isset($tareas['tareas']) || !is_array($tareas['tareas'])) {
    $tareas['tareas'] = array();
}

$mensaje = '';

// ── MANEJO POST ──────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action === 'agregar_tarea' && !empty($_POST['titulo'])) {
        $tareas['tareas'][] = array(
            'id' => 'T-' . strtoupper(substr(uniqid(), -6)),
            'titulo' => $_POST['titulo'],
            'proyecto' => !empty($_POST['proyecto']) ? $_POST['proyecto'] : 'the-difference',
            'area' => !empty($_POST['area']) ? $_POST['area'] : 'general',
            'estado' => 'pendiente',
            'estimado_horas' => (float)(isset($_POST['estimado_horas']) ? $_POST['estimado_horas'] : 0),
            'fecha_creacion' => date('Y-m-d'),
            'fecha_cierre' => null
        );
        file_put_contents($tareasFile, json_encode($tareas, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        $mensaje = '✅ Tarea agregada';
        $tareas = json_decode(file_get_contents($tareasFile), true);
    }

    if ($action === 'cambiar_estado' && !empty($_POST['id'])) {
        foreach ($tareas['tareas'] as &$t) {
            if ($t['id'] === $_POST['id']) {
                if ($t['estado'] !== 'completada') { $t['estado']='completada'; $t['fecha_cierre']=date('Y-m-d'); }
                else { $t['estado']='pendiente'; $t['fecha_cierre']=null; }
            }
        }
        unset($t);
        file_put_contents($tareasFile, json_encode($tareas, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        $mensaje = '✅ Estado actualizado';
        $tareas = json_decode(file_get_contents($tareasFile), true);
    }

    if ($action === 'reset_reloj') {
        $archivoTest = $baseDir . '/data/reloj-consciente-test.json';
        $previas = file_exists($archivoTest) ? json_decode(file_get_contents($archivoTest), true) : array();
        if (!is_array($previas)) $previas = array();
        $todas = array_merge($previas, $sesiones);
        file_put_contents($archivoTest, json_encode($todas, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE));
        file_put_contents($relojFile, json_encode(array(), JSON_PRETTY_PRINT));
        $sesiones = array();
        $mensaje = '✅ Reloj reseteado: las sesiones de prueba quedaron archivadas en data/reloj-consciente-test.json';
    }

    if ($action === 'publicar') {
        if (function_exists('shell_exec')) {
            $cmd = 'cd /d "' . $baseDir . '" && git add . && git commit -m "Publicar cambios ' . date('Y-m-d H:i') . '" && git push origin main 2>&1';
            $salida = shell_exec($cmd);
            $mensaje = '🚀 Publicación ejecutada → ' . ($salida !== null && $salida !== '' ? $salida : '(sin cambios que publicar)');
        } else {
            $mensaje = '❌ shell_exec no está disponible en este PHP. Usa publicar.bat del escritorio.';
        }
    }
}

// ── AGREGADOS DEL RELOJ ──────────────────────────────────
$totalHoras = 0; $totalValor = 0;
$porDia = array(); $porMes = array(); $porAnio = array(); $porHora = array();

foreach ($sesiones as $s) {
    $totalHoras += $s['duracion_horas'];
    $totalValor += $s['valor'];
    $f = $s['fecha'];
    $timestamp = (int)($s['inicio'] / 1000);
    $mes  = date('Y-m', $timestamp);
    $anio = date('Y', $timestamp);
    $hora = (int)date('H', $timestamp);

    if (!isset($porDia[$f])) $porDia[$f] = array('horas'=>0,'valor'=>0,'sesiones'=>0);
    $porDia[$f]['horas'] += $s['duracion_horas'];
    $porDia[$f]['valor'] += $s['valor'];
    $porDia[$f]['sesiones']++;

    if (!isset($porMes[$mes])) $porMes[$mes] = array('horas'=>0,'valor'=>0);
    $porMes[$mes]['horas'] += $s['duracion_horas'];
    $porMes[$mes]['valor'] += $s['valor'];

    if (!isset($porAnio[$anio])) $porAnio[$anio] = array('horas'=>0,'valor'=>0);
    $porAnio[$anio]['horas'] += $s['duracion_horas'];
    $porAnio[$anio]['valor'] += $s['valor'];

    if (!isset($porHora[$hora])) $porHora[$hora] = array('horas'=>0,'valor'=>0);
    $porHora[$hora]['horas'] += $s['duracion_horas'];
    $porHora[$hora]['valor'] += $s['valor'];
}
ksort($porDia); ksort($porMes); ksort($porAnio); ksort($porHora);

// ── VISTAS PARA GRÁFICOS DE ÁREA ─────────────────────────
$mesActual  = date('Y-m');
$anioActual = date('Y');

$diasMes = array_fill(1, 31, 0);
foreach ($porDia as $f => $d) {
    if (substr($f, 0, 7) === $mesActual) {
        $day = (int)substr($f, 8, 2);
        if ($day >= 1 && $day <= 31) $diasMes[$day] += $d['horas'];
    }
}

$mesesAnio = array_fill(1, 12, 0);
foreach ($porMes as $m => $d) {
    if (substr($m, 0, 4) === $anioActual) {
        $mo = (int)substr($m, 5, 2);
        if ($mo >= 1 && $mo <= 12) $mesesAnio[$mo] += $d['horas'];
    }
}

// ── AGREGADOS DE TAREAS ──────────────────────────────────
$pendientes = 0; $completadas = 0;
$pendPorProyecto = array(); $restPorArea = array(); $tareasComplDia = array();

foreach ($tareas['tareas'] as $t) {
    if ($t['estado'] === 'completada') {
        $completadas++;
        if (!empty($t['fecha_cierre'])) {
            $fc = $t['fecha_cierre'];
            $tareasComplDia[$fc] = (isset($tareasComplDia[$fc]) ? $tareasComplDia[$fc] : 0) + 1;
        }
    } else {
        $pendientes++;
        $p = $t['proyecto']; $a = $t['area'];
        $pendPorProyecto[$p] = (isset($pendPorProyecto[$p]) ? $pendPorProyecto[$p] : 0) + 1;
        $restPorArea[$a] = (isset($restPorArea[$a]) ? $restPorArea[$a] : 0) + $t['estimado_horas'];
    }
}

// ── AVANCE ACUMULADO ─────────────────────────────────────
$labelsAvance = array(); $cumHoras = array(); $cum = 0;
foreach ($porDia as $f => $d) { $cum += $d['horas']; $labelsAvance[] = $f; $cumHoras[] = round($cum, 2); }

$areas = array('backend','frontend','contenido','alianzas','diseno','infraestructura','general');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Panel-General · The Difference</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: system-ui, sans-serif; background: #0f0f0f; color: #fff; min-height: 100vh; padding: 2rem; }
.container { max-width: 1200px; margin: 0 auto; }
h1 { color: #ff69b4; font-size: 2rem; margin-bottom: 0.3rem; }
.subtitle { color: #a0a0a0; margin-bottom: 1.5rem; font-style: italic; }
.alert { padding: 0.8rem; border-radius: 6px; background: rgba(16,185,129,.15); border: 1px solid #10b981; color: #10b981; margin-bottom: 1rem; white-space: pre-wrap; font-family: 'Courier New', monospace; font-size: 0.8rem; }
.nav { display: flex; gap: 0.8rem; flex-wrap: wrap; margin-bottom: 2rem; align-items: center; }
.nav a { padding: 0.6rem 1.2rem; background: #252525; color: #fff; text-decoration: none; border-radius: 6px; border: 1px solid #333; transition: all 0.2s; }
.nav a:hover { border-color: #ff69b4; transform: translateY(-2px); }
.nav .hub { background: linear-gradient(135deg, #9c27b0, #ff69b4); border-color: transparent; font-weight: bold; }
.kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(160px, 1fr)); gap: 1rem; margin-bottom: 2rem; }
.kpi { background: #252525; border: 1px solid #333; border-top: 4px solid #ff69b4; border-radius: 6px; padding: 1rem; }
.kpi .label { font-size: 0.7rem; text-transform: uppercase; letter-spacing: 1px; color: #a0a0a0; }
.kpi .value { font-size: 1.6rem; font-weight: bold; color: #ff69b4; margin-top: 0.3rem; }
.kpi .detail { font-size: 0.7rem; color: #666; }
.reloj-section { background: #252525; border: 1px solid #333; border-radius: 8px; padding: 2.5rem; text-align: center; margin-bottom: 2rem; border-top: 5px solid #ff69b4; }
.reloj-display { font-family: 'Courier New', monospace; font-size: 4.5rem; font-weight: bold; letter-spacing: 4px; margin: 1rem 0 0.5rem; transition: color 0.3s; }
.reloj-display.activo { color: #10b981; }
.reloj-display.detenido { color: #ef4444; }
.valor-vivo { font-family: 'Courier New', monospace; font-size: 1.6rem; color: #b8860b; margin-bottom: 1.5rem; }
.reloj-estado { font-family: 'Courier New', monospace; font-size: 0.9rem; letter-spacing: 2px; margin-bottom: 1.5rem; color: #a0a0a0; }
.btn-reloj { font-family: 'Courier New', monospace; font-size: 1.1rem; padding: 1rem 3rem; border: none; border-radius: 6px; cursor: pointer; letter-spacing: 2px; }
.btn-iniciar { background: #10b981; color: #fff; }
.btn-detener { background: #ef4444; color: #fff; }
.grid2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(340px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem; }
.card { background: #252525; border: 1px solid #333; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
.card h3 { color: #ff69b4; font-size: 1rem; margin-bottom: 1rem; border-left: 3px solid #ff69b4; padding-left: 0.7rem; font-weight: normal; }
.chart-wrap { position: relative; height: 260px; }
table { width: 100%; border-collapse: collapse; font-size: 0.85rem; }
th, td { text-align: left; padding: 0.5rem; border-bottom: 1px solid #333; color: #a0a0a0; }
th { color: #ff69b4; }
.estado-pendiente { color: #fbbf24; }
.estado-completada { color: #10b981; }
form.tarea { display: grid; grid-template-columns: repeat(auto-fit, minmax(140px, 1fr)); gap: 0.8rem; }
form.tarea input, form.tarea select { padding: 0.5rem; background: #1a1a1a; color: #fff; border: 1px solid #333; border-radius: 4px; }
form.tarea button { grid-column: 1/-1; padding: 0.7rem; background: #10b981; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; }
.bitacora-list { max-height: 400px; overflow-y: auto; }
.sesion-item { font-family: 'Courier New', monospace; font-size: 0.85rem; padding: 0.7rem; border-bottom: 1px dashed #333; display: flex; justify-content: space-between; flex-wrap: wrap; gap: 0.5rem; color: #a0a0a0; }
.sesion-item:last-child { border: none; }
.vacio { color: #666; font-style: italic; text-align: center; padding: 1rem; }
</style>
</head>
<body>
<div class="container">

<!-- BARRA SUPERIOR: Inventario justo al lado de Dashboard -->
<div class="nav">
    <a href="admin/hub-central.php" class="hub">🎯 Ir al Hub Central</a>
    <a href="admin/recopilatorio.php" style="background:linear-gradient(135deg,#b8860b,#fffc34); border-color:transparent; font-weight:bold; color:#000;">🧠 Recopilatorio</a>
    <a href="admin/configuracion.php">🎛️ Configuración</a>
    <a href="admin/dashboard.php">📊 Dashboard</a>
    <a href="admin/inventario.php">🗂️ Inventario</a>
    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Publicar cambios en GitHub + Vercel?');">
        <input type="hidden" name="action" value="publicar">
        <button type="submit" style="padding:0.6rem 1.2rem;background:linear-gradient(135deg,#10b981,#00bcd4);color:#fff;border:none;border-radius:6px;cursor:pointer;font-weight:bold;">🚀 Publicar</button>
    </form>
</div>

<h1>🕐 Panel-General</h1>
<p class="subtitle">Reloj consciente · Tareas · Bitácora · Valor integral · Publicar · Gráficos</p>

<?php if ($mensaje): ?><div class="alert"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<div class="kpi-grid">
    <div class="kpi"><div class="label">Horas conscientes</div><div class="value"><?= round($totalHoras, 2) ?></div><div class="detail">trazadas</div></div>
    <div class="kpi"><div class="label">Valor integral</div><div class="value">$<?= number_format($totalValor, 0) ?></div><div class="detail">acumulado</div></div>
    <div class="kpi"><div class="label">Sesiones</div><div class="value"><?= count($sesiones) ?></div><div class="detail">registradas</div></div>
    <div class="kpi"><div class="label">Tareas pendientes</div><div class="value"><?= $pendientes ?></div><div class="detail">por hacer</div></div>
    <div class="kpi"><div class="label">Completadas</div><div class="value"><?= $completadas ?></div><div class="detail">cerradas</div></div>
</div>

<!-- RELOJ -->
<section class="reloj-section">
    <div class="reloj-estado" id="relojEstado">EN PAUSA · ROJO</div>
    <div class="reloj-display detenido" id="relojDisplay">00:00:00</div>
    <div class="valor-vivo" id="valorVivo">$0.00</div>
    <button class="btn-reloj btn-iniciar" id="btnReloj" onclick="toggleReloj()">INICIAR</button>
    <form method="POST" style="display:inline;" onsubmit="return confirm('¿Resetear el reloj? Las sesiones de prueba se archivan, no se borran.');">
        <input type="hidden" name="action" value="reset_reloj">
        <button type="submit" style="font-family:'Courier New',monospace;font-size:1.1rem;padding:1rem 2rem;border:none;border-radius:6px;cursor:pointer;letter-spacing:2px;background:#f59e0b;color:#000;margin-left:1rem;">🔄 RESET</button>
    </form>
    <div style="margin-top:1.5rem; color:#a0a0a0; font-size:0.85rem;">
        Valor hora integral: $<input type="number" id="valorHora" value="70000" style="width:90px; background:#1a1a1a; color:#fff; border:1px solid #333; padding:0.3rem;">
    </div>
</section>

<!-- VALOR INTEGRAL DE LA HORA -->
<div class="card">
    <h3>💰 Valor Integral de la Hora</h3>
    <p style="color:#a0a0a0; font-size:0.95rem; line-height:1.6; margin-bottom:1.2rem;">
        El costo de una hora es el <strong style="color:#b8860b;">valor ontológico del proceso</strong>
        en su <strong style="color:#b8860b;">presencia íntegra</strong>.
        No se calcula por partes: se señala como la concurrencia simultánea de todas
        las dimensiones del acto consciente.
    </p>
    <p style="color:#e0e0e0; font-size:1.05rem; letter-spacing:1px; line-height:1.9;">
        Capital humano&ensp;·&ensp;Capital infraestructura&ensp;·&ensp;Valor ontológico&ensp;·&ensp;Roles&ensp;·&ensp;Etapas
    </p>
</div>

<!-- GRÁFICOS DE ÁREA -->
<div class="grid2">
    <div class="card"><h3>📊 Vista diaria (por hora del día)</h3><div class="chart-wrap"><canvas id="chartDiario"></canvas></div></div>
    <div class="card"><h3>📅 Vista mensual (por día del mes)</h3><div class="chart-wrap"><canvas id="chartMensual"></canvas></div></div>
</div>
<div class="card"><h3>📆 Vista anual (por mes)</h3><div class="chart-wrap"><canvas id="chartAnual"></canvas></div></div>
<div class="card"><h3>📈 Avance acumulado de construcción</h3><div class="chart-wrap"><canvas id="chartAvance"></canvas></div></div>

<!-- PROGRESO DE TAREAS -->
<div class="grid2">
    <div class="card"><h3>🎯 Progreso general (listas vs pendientes)</h3><div class="chart-wrap"><canvas id="chartProgreso"></canvas></div></div>
    <div class="card"><h3>⏳ Pendientes por área (horas restantes)</h3><div class="chart-wrap"><canvas id="chartPendArea"></canvas></div></div>
</div>

<!-- RESUMEN + PENDIENTES -->
<div class="grid2">
    <div class="card">
        <h3>🗓️ Resumen diario (qué se hizo cada día)</h3>
        <table>
            <tr><th>Fecha</th><th>Sesiones</th><th>Horas</th><th>Valor</th><th>Tareas</th></tr>
            <?php foreach (array_reverse($porDia, true) as $f=>$d): ?>
            <tr><td><?= $f ?></td><td><?= $d['sesiones'] ?></td><td><?= round($d['horas'],2) ?></td><td>$<?= number_format($d['valor'],0) ?></td><td><?= isset($tareasComplDia[$f]) ? $tareasComplDia[$f] : 0 ?></td></tr>
            <?php endforeach; ?>
        </table>
    </div>
    <div class="card">
        <h3>⏳ Pendientes por proyecto / Restante por área</h3>
        <table><tr><th>Proyecto</th><th>Pendientes</th></tr>
        <?php foreach ($pendPorProyecto as $p=>$n): ?><tr><td><?= htmlspecialchars($p) ?></td><td><?= $n ?></td></tr><?php endforeach; ?>
        </table><br>
        <table><tr><th>Área</th><th>Horas restantes</th></tr>
        <?php foreach ($restPorArea as $a=>$h): ?><tr><td><?= htmlspecialchars($a) ?></td><td><?= $h ?> h</td></tr><?php endforeach; ?>
        </table>
    </div>
</div>

<!-- INSERTAR TAREA -->
<div class="card">
    <h3>➕ Insertar tarea pendiente</h3>
    <form method="POST" class="tarea">
        <input type="hidden" name="action" value="agregar_tarea">
        <input type="text" name="titulo" placeholder="Título de la tarea" required>
        <input type="text" name="proyecto" placeholder="Proyecto" value="the-difference">
        <select name="area"><?php foreach ($areas as $a): ?><option value="<?= $a ?>"><?= ucfirst($a) ?></option><?php endforeach; ?></select>
        <input type="number" name="estimado_horas" step="0.5" min="0" placeholder="Estimado (horas)">
        <button type="submit">Agregar tarea</button>
    </form>
</div>

<!-- BITÁCORA DE TAREAS -->
<div class="card">
    <h3>📋 Bitácora general de tareas (clic en estado para alternar)</h3>
    <div class="bitacora-list">
        <table>
            <tr><th>ID</th><th>Tarea</th><th>Proyecto</th><th>Área</th><th>Est.</th><th>Creada</th><th>Estado</th></tr>
            <?php foreach (array_reverse($tareas['tareas']) as $t): ?>
            <tr>
                <td><?= $t['id'] ?></td><td><?= htmlspecialchars($t['titulo']) ?></td><td><?= htmlspecialchars($t['proyecto']) ?></td>
                <td><?= htmlspecialchars($t['area']) ?></td><td><?= $t['estimado_horas'] ?>h</td><td><?= $t['fecha_creacion'] ?></td>
                <td><form method="POST" style="display:inline;"><input type="hidden" name="action" value="cambiar_estado"><input type="hidden" name="id" value="<?= $t['id'] ?>"><button type="submit" class="estado-<?= $t['estado'] ?>" style="background:none;border:none;cursor:pointer;font-size:0.85rem;"><?= $t['estado'] ?></button></form></td>
            </tr>
            <?php endforeach; ?>
            <?php if (empty($tareas['tareas'])): ?><tr><td colspan="7" style="text-align:center;color:#666;">Sin tareas aún</td></tr><?php endif; ?>
        </table>
    </div>
</div>

<!-- BITÁCORA DE SESIONES -->
<div class="card">
    <h3>📓 Bitácora de sesiones conscientes</h3>
    <div class="bitacora-list" id="listaSesiones"><div class="vacio">Cargando sesiones...</div></div>
</div>

</div>

<script>
let activo = false, inicioSesion = null, intervalo = null, segundosTranscurridos = 0;

function formatearTiempo(seg) {
    const h = String(Math.floor(seg/3600)).padStart(2,'0');
    const m = String(Math.floor((seg%3600)/60)).padStart(2,'0');
    const s = String(seg%60).padStart(2,'0');
    return h+':'+m+':'+s;
}

function toggleReloj() { activo ? detenerReloj() : iniciarReloj(); }

function iniciarReloj() {
    activo = true; inicioSesion = Date.now(); segundosTranscurridos = 0;
    document.getElementById('relojDisplay').className = 'reloj-display activo';
    document.getElementById('relojEstado').textContent = 'EN CURSO · VERDE';
    const btn = document.getElementById('btnReloj');
    btn.textContent = 'DETENER'; btn.className = 'btn-reloj btn-detener';
    intervalo = setInterval(function() {
        segundosTranscurridos++;
        document.getElementById('relojDisplay').textContent = formatearTiempo(segundosTranscurridos);
        const vh = parseFloat(document.getElementById('valorHora').value) || 70000;
        const vv = (segundosTranscurridos / 3600) * vh;
        document.getElementById('valorVivo').textContent = '$' + vv.toLocaleString('es', {maximumFractionDigits: 2});
    }, 1000);
}

function detenerReloj() {
    activo = false; clearInterval(intervalo);
    const finSesion = Date.now();
    const duracionSeg = Math.floor((finSesion - inicioSesion) / 1000);
    const vh = parseFloat(document.getElementById('valorHora').value) || 70000;
    const duracionHoras = duracionSeg / 3600;
    const valor = duracionHoras * vh;

    fetch('admin/api/guardar-reloj.php', {
        method: 'POST',
        headers: {'Content-Type': 'application/json'},
        body: JSON.stringify({ inicio: inicioSesion, fin: finSesion, duracionSeg: duracionSeg, duracionHoras: duracionHoras, valorHora: vh, valor: valor })
    }).then(function(r){ return r.json(); }).then(function(data){
        if (data.success) console.log('✅ Sesión guardada:', data.sesion.id);
    }).catch(function(e){ alert('Error al guardar: ' + e.message); });

    document.getElementById('relojDisplay').className = 'reloj-display detenido';
    document.getElementById('relojDisplay').textContent = '00:00:00';
    document.getElementById('valorVivo').textContent = '$0.00';
    document.getElementById('relojEstado').textContent = 'EN PAUSA · ROJO';
    const btn = document.getElementById('btnReloj');
    btn.textContent = 'INICIAR'; btn.className = 'btn-reloj btn-iniciar';
    cargarDatos();
    setTimeout(function(){ location.reload(); }, 500);
}

function cargarDatos() {
    fetch('admin/api/listar-reloj.php').then(function(r){ return r.json(); }).then(function(data){
        renderBitacora(data.sesiones);
    }).catch(function(e){ console.error('Error:', e); });
}

function renderBitacora(sesiones) {
    const lista = document.getElementById('listaSesiones');
    if (!sesiones || sesiones.length === 0) {
        lista.innerHTML = '<div class="vacio">Aún no hay sesiones. Presiona INICIAR.</div>';
        return;
    }
    lista.innerHTML = sesiones.slice().reverse().map(function(s){
        return '<div class="sesion-item"><span>'+s.fecha+' · '+s.hora_inicio+' → '+s.hora_fin+'</span><span>'+formatearTiempo(s.duracion_seg)+' · $'+Math.round(s.valor).toLocaleString('es')+'</span></div>';
    }).join('');
}

// ── GRÁFICOS DE ÁREA ────────────────────────────────────
Chart.defaults.color = '#a0a0a0';

const porHora = <?= json_encode($porHora) ?>;
const diasData = <?= json_encode(array_values($diasMes)) ?>;
const mesesData = <?= json_encode(array_values($mesesAnio)) ?>;
const labelsAvance = <?= json_encode($labelsAvance) ?>;
const cumHoras = <?= json_encode($cumHoras) ?>;

const horasLabels = []; for (let i=0;i<24;i++){ horasLabels.push(String(i).padStart(2,'0')+':00'); }
const horasData = horasLabels.map(function(_,i){ return (porHora[i] && porHora[i].horas) ? porHora[i].horas : 0; });

const diasLabels = []; for (let i=1;i<=31;i++){ diasLabels.push('Día '+i); }
const mesesLabels = ['Ene','Feb','Mar','Abr','May','Jun','Jul','Ago','Sep','Oct','Nov','Dic'];

new Chart(document.getElementById('chartDiario'), {
    type: 'line',
    data: { labels: horasLabels, datasets: [{ label:'Horas', data: horasData, borderColor:'#ff69b4', backgroundColor:'rgba(255,105,180,0.2)', fill:true, tension:0.4 }]},
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

new Chart(document.getElementById('chartMensual'), {
    type: 'line',
    data: { labels: diasLabels, datasets: [{ label:'Horas', data: diasData, borderColor:'#00bcd4', backgroundColor:'rgba(0,188,212,0.2)', fill:true, tension:0.4 }]},
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

new Chart(document.getElementById('chartAnual'), {
    type: 'line',
    data: { labels: mesesLabels, datasets: [{ label:'Horas', data: mesesData, borderColor:'#10b981', backgroundColor:'rgba(16,185,129,0.2)', fill:true, tension:0.4 }]},
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}}, scales:{y:{beginAtZero:true}} }
});

new Chart(document.getElementById('chartAvance'), {
    type: 'line',
    data: { labels: labelsAvance, datasets: [{ label:'Horas acumuladas', data: cumHoras, borderColor:'#b8860b', backgroundColor:'rgba(184,134,11,0.2)', fill:true, tension:0.3 }]},
    options: { responsive:true, maintainAspectRatio:false, scales:{y:{beginAtZero:true}} }
});

// ── GRÁFICOS DE PROGRESO DE TAREAS ──────────────────────
const pend = <?= (int)$pendientes ?>;
const comp = <?= (int)$completadas ?>;
const areasPend = <?= json_encode($restPorArea) ?>;

new Chart(document.getElementById('chartProgreso'), {
    type: 'doughnut',
    data: { labels: ['Completadas','Pendientes'], datasets: [{ data: [comp, pend], backgroundColor: ['#10b981','#ef4444'] }]},
    options: { responsive: true, maintainAspectRatio: false }
});

const aKeys = Object.keys(areasPend);
const aVals = aKeys.map(function(k){ return areasPend[k]; });

new Chart(document.getElementById('chartPendArea'), {
    type: 'bar',
    data: { labels: aKeys, datasets: [{ label:'Horas restantes', data: aVals, backgroundColor:'#fbbf24' }]},
    options: { responsive:true, maintainAspectRatio:false, plugins:{legend:{display:false}} }
});

cargarDatos();
</script>
</body>
</html>