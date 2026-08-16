<?php
/**
 * DASHBOARDS UNIFICADOS - THE DIFFERENCE
 * Métricas, gráficos y datos medibles
 */
session_start();

$baseDir = dirname(__DIR__);
$dataDir = $baseDir . '/data';
$configDir = $baseDir . '/config';

$logPersonalFile = $dataDir . '/log-personal.json';
$bitacoraFile = $configDir . '/bitacora.json';
$cuentasFile = $configDir . '/cuentas.json';
$settingsFile = $configDir . '/settings.json';

$logPersonal = file_exists($logPersonalFile) ? json_decode(file_get_contents($logPersonalFile), true) : [];
$bitacora = file_exists($bitacoraFile) ? json_decode(file_get_contents($bitacoraFile), true) : [];
$cuentas = file_exists($cuentasFile) ? json_decode(file_get_contents($cuentasFile), true) : [];
$settings = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];

$metricas = [
    'log_personal' => [
        'niñes' => count($logPersonal['niñes'] ?? []),
        'juventud' => count($logPersonal['juventud'] ?? []),
        'adultes' => count($logPersonal['adultes'] ?? []),
        'proyecto' => count($logPersonal['proyecto_actual'] ?? []),
        'total' => array_sum([
            count($logPersonal['niñes'] ?? []), count($logPersonal['juventud'] ?? []),
            count($logPersonal['adultes'] ?? []), count($logPersonal['proyecto_actual'] ?? [])
        ]),
        'conexiones' => count($logPersonal['conexiones'] ?? [])
    ],
    'bitacoras' => [
        'total' => count($bitacora),
        'texvn' => count(array_filter($bitacora, fn($b) => ($b['tipo_bitacora'] ?? '') === 'texvn')),
        'saiayin_do' => count(array_filter($bitacora, fn($b) => ($b['tipo_bitacora'] ?? '') === 'saiayin-do')),
        'opus_magnum' => count(array_filter($bitacora, fn($b) => ($b['tipo_bitacora'] ?? '') === 'opus-magnum')),
        'log' => count(array_filter($bitacora, fn($b) => ($b['tipo_bitacora'] ?? '') === 'log'))
    ],
    'cuentas' => [
        'configuradas' => count(array_filter($cuentas, fn($c) => !empty($c['email']))),
        'total' => 4,
        'porcentaje' => round((count(array_filter($cuentas, fn($c) => !empty($c['email']))) / 4) * 100)
    ],
    'ias' => [
        'openai' => !empty($settings['ia']['openai_key']) ? 'activo' : 'inactivo',
        'gemini' => !empty($settings['ia']['gemini_key']) ? 'activo' : 'inactivo',
        'qwen' => !empty($settings['ia']['qwen_key']) ? 'activo' : 'inactivo'
    ]
];

$dashboardActivo = $_GET['dashboard'] ?? 'general';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Dashboards | The Difference</title>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Courier New',monospace; background:#0a0a0a; color:#e0e0e0; }
.layout { display:grid; grid-template-columns:280px 1fr; min-height:100vh; }
.sidebar { background:#151515; border-right:2px solid #ff69b4; padding:2rem 1rem; position:fixed; height:100vh; width:280px; overflow-y:auto; }
.sidebar-header { text-align:center; margin-bottom:2rem; padding-bottom:1.5rem; border-bottom:1px solid #333; }
.sidebar-header h1 { color:#fffc34; font-size:1.3rem; letter-spacing:2px; }
.nav-menu { list-style:none; }
.nav-item { margin-bottom:0.5rem; }
.nav-link { display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:1rem; border:1px solid transparent; border-radius:6px; color:#a0a0a0; text-decoration:none; transition:all 0.2s; }
.nav-link:hover, .nav-link.active { background:rgba(255,105,180,0.1); border-color:#ff69b4; color:#fff; }
.nav-link .badge { background:#10b981; color:#000; padding:0.15rem 0.5rem; border-radius:12px; font-size:0.75rem; font-weight:bold; }
.main-content { margin-left:280px; padding:2rem; }
.header-bar { display:flex; justify-content:space-between; align-items:center; margin-bottom:2rem; padding-bottom:1rem; border-bottom:1px solid #333; }
.header-bar h2 { color:#fffc34; font-size:1.8rem; }
.metrics-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(250px,1fr)); gap:1.5rem; margin-bottom:2rem; }
.metric-card { background:#151515; border:1px solid #333; border-radius:8px; padding:1.5rem; transition:all 0.3s; }
.metric-card:hover { border-color:#ff69b4; transform:translateY(-3px); }
.metric-icon { font-size:2.5rem; margin-bottom:0.75rem; }
.metric-value { font-size:2.5rem; font-weight:bold; color:#fffc34; margin-bottom:0.25rem; }
.metric-label { color:#a0a0a0; font-size:0.85rem; }
.metric-progress { margin-top:1rem; height:6px; background:#252525; border-radius:3px; overflow:hidden; }
.metric-progress-bar { height:100%; background:linear-gradient(90deg,#ff69b4,#fffc34); }
.charts-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(400px,1fr)); gap:2rem; margin-bottom:2rem; }
.chart-container { background:#151515; border:1px solid #333; border-radius:8px; padding:2rem; }
.chart-container h3 { color:#fffc34; margin-bottom:1.5rem; }
.dashboard-section { display:none; }
.dashboard-section.active { display:block; }
.action-buttons { display:flex; gap:1rem; margin-top:2rem; flex-wrap:wrap; }
.btn { padding:0.75rem 1.5rem; background:#ff69b4; color:#000; border:none; border-radius:6px; cursor:pointer; font-family:inherit; font-weight:bold; text-decoration:none; display:inline-flex; align-items:center; gap:0.5rem; }
.btn:hover { background:#fffc34; }
.btn-secondary { background:#252525; color:#e0e0e0; border:1px solid #333; }
.status-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(200px,1fr)); gap:1rem; margin-top:1rem; }
.status-item { background:#1a1a1a; border:1px solid #333; border-radius:6px; padding:1rem; display:flex; align-items:center; justify-content:space-between; }
.status-label { color:#a0a0a0; font-size:0.85rem; }
.status-value { font-weight:bold; padding:0.25rem 0.75rem; border-radius:12px; font-size:0.75rem; }
.status-active { background:rgba(16,185,129,0.2); color:#10b981; }
.status-inactive { background:rgba(102,102,102,0.2); color:#666; }
</style>
</head>
<body>
<div class="layout">
<aside class="sidebar">
    <div class="sidebar-header">
        <h1>📊 DASHBOARDS</h1>
        <p style="color:#666; font-size:0.75rem;">The Difference</p>
    </div>
    <nav>
        <ul class="nav-menu">
            <li class="nav-item"><a href="?dashboard=general" class="nav-link <?= $dashboardActivo==='general'?'active':'' ?>"><span>🏠 General</span><span class="badge"><?= $metricas['log_personal']['total'] ?></span></a></li>
            <li class="nav-item"><a href="?dashboard=log" class="nav-link <?= $dashboardActivo==='log'?'active':'' ?>"><span>📓 LOG Personal</span><span class="badge"><?= $metricas['log_personal']['total'] ?></span></a></li>
            <li class="nav-item"><a href="?dashboard=cuentas" class="nav-link <?= $dashboardActivo==='cuentas'?'active':'' ?>"><span>📧 Multi-Cuentas</span><span class="badge"><?= $metricas['cuentas']['configuradas'] ?>/4</span></a></li>
            <li class="nav-item"><a href="?dashboard=bitacoras" class="nav-link <?= $dashboardActivo==='bitacoras'?'active':'' ?>"><span>📝 Bitácoras</span><span class="badge"><?= $metricas['bitacoras']['total'] ?></span></a></li>
            <li class="nav-item"><a href="?dashboard=analitico" class="nav-link <?= $dashboardActivo==='analitico'?'active':'' ?>"><span>📈 Analítico</span></a></li>
            <li class="nav-item"><a href="hub-central.php" class="nav-link"><span>🌌 Hub Central</span></a></li>
            <li class="nav-item"><a href="configuracion.php" class="nav-link"><span>⚙️ Configuración</span></a></li>
        </ul>
    </nav>
</aside>

<main class="main-content">
    <div class="header-bar">
        <h2><?= strtoupper(str_replace('-',' ',$dashboardActivo)) ?></h2>
        <button class="btn btn-secondary" onclick="location.reload()">🔄 Actualizar</button>
    </div>

    <?php if ($dashboardActivo==='general'): ?>
    <div class="dashboard-section active">
        <div class="metrics-grid">
            <div class="metric-card"><div class="metric-icon">📓</div><div class="metric-value"><?= $metricas['log_personal']['total'] ?></div><div class="metric-label">Registros LOG</div><div class="metric-progress"><div class="metric-progress-bar" style="width:<?= min(($metricas['log_personal']['total']/50)*100,100) ?>%"></div></div></div>
            <div class="metric-card"><div class="metric-icon">📝</div><div class="metric-value"><?= $metricas['bitacoras']['total'] ?></div><div class="metric-label">Bitácoras</div><div class="metric-progress"><div class="metric-progress-bar" style="width:<?= min(($metricas['bitacoras']['total']/100)*100,100) ?>%"></div></div></div>
            <div class="metric-card"><div class="metric-icon">🔗</div><div class="metric-value"><?= $metricas['log_personal']['conexiones'] ?></div><div class="metric-label">Conexiones</div><div class="metric-progress"><div class="metric-progress-bar" style="width:<?= min(($metricas['log_personal']['conexiones']/20)*100,100) ?>%"></div></div></div>
            <div class="metric-card"><div class="metric-icon">📧</div><div class="metric-value"><?= $metricas['cuentas']['configuradas'] ?>/4</div><div class="metric-label">Cuentas Gmail</div><div class="metric-progress"><div class="metric-progress-bar" style="width:<?= $metricas['cuentas']['porcentaje'] ?>%"></div></div></div>
        </div>
        <div class="charts-grid">
            <div class="chart-container"><h3>📊 Distribución por Etapas</h3><canvas id="chart-log"></canvas></div>
            <div class="chart-container"><h3>📝 Bitácoras por Tipo</h3><canvas id="chart-bitacoras"></canvas></div>
        </div>
        <div class="chart-container">
            <h3>🤖 Estado de APIs de IA</h3>
            <div class="status-grid">
                <div class="status-item"><span class="status-label">OpenAI (ChatGPT)</span><span class="status-value <?= $metricas['ias']['openai']==='activo'?'status-active':'status-inactive' ?>"><?= strtoupper($metricas['ias']['openai']) ?></span></div>
                <div class="status-item"><span class="status-label">Google Gemini</span><span class="status-value <?= $metricas['ias']['gemini']==='activo'?'status-active':'status-inactive' ?>"><?= strtoupper($metricas['ias']['gemini']) ?></span></div>
                <div class="status-item"><span class="status-label">Alibaba Qwen</span><span class="status-value <?= $metricas['ias']['qwen']==='activo'?'status-active':'status-inactive' ?>"><?= strtoupper($metricas['ias']['qwen']) ?></span></div>
            </div>
        </div>
        <div class="action-buttons">
            <a href="log-personal.php" class="btn">📓 LOG Completo</a>
            <a href="multi-cuentas.php" class="btn">📧 Cuentas</a>
            <a href="configuracion.php" class="btn btn-secondary">⚙️ Configuración</a>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($dashboardActivo==='log'): ?>
    <div class="dashboard-section active">
        <div class="metrics-grid">
            <div class="metric-card"><div class="metric-icon">🌱</div><div class="metric-value"><?= $metricas['log_personal']['niñes'] ?></div><div class="metric-label">Niñes</div></div>
            <div class="metric-card"><div class="metric-icon">🔥</div><div class="metric-value"><?= $metricas['log_personal']['juventud'] ?></div><div class="metric-label">Juventud</div></div>
            <div class="metric-card"><div class="metric-icon">🦁</div><div class="metric-value"><?= $metricas['log_personal']['adultes'] ?></div><div class="metric-label">Adultes</div></div>
            <div class="metric-card"><div class="metric-icon">🌀</div><div class="metric-value"><?= $metricas['log_personal']['proyecto'] ?></div><div class="metric-label">Proyecto</div></div>
        </div>
        <div class="action-buttons"><a href="log-personal.php" class="btn">📓 Abrir LOG</a></div>
    </div>
    <?php endif; ?>

    <?php if ($dashboardActivo==='cuentas'): ?>
    <div class="dashboard-section active">
        <div class="metrics-grid">
            <div class="metric-card"><div class="metric-icon">✅</div><div class="metric-value"><?= $metricas['cuentas']['configuradas'] ?></div><div class="metric-label">Configuradas</div></div>
            <div class="metric-card"><div class="metric-icon">❌</div><div class="metric-value"><?= 4-$metricas['cuentas']['configuradas'] ?></div><div class="metric-label">Pendientes</div></div>
            <div class="metric-card"><div class="metric-icon">📊</div><div class="metric-value"><?= $metricas['cuentas']['porcentaje'] ?>%</div><div class="metric-label">Progreso</div></div>
        </div>
        <div class="action-buttons">
            <a href="configurar-cuentas.php" class="btn">📧 Configurar</a>
            <a href="multi-cuentas.php" class="btn">📧 Acceder</a>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($dashboardActivo==='bitacoras'): ?>
    <div class="dashboard-section active">
        <div class="metrics-grid">
            <div class="metric-card"><div class="metric-icon">🔷</div><div class="metric-value"><?= $metricas['bitacoras']['texvn'] ?></div><div class="metric-label">TEXVN</div></div>
            <div class="metric-card"><div class="metric-icon">🌸</div><div class="metric-value"><?= $metricas['bitacoras']['saiayin_do'] ?></div><div class="metric-label">SAIAYIN DO</div></div>
            <div class="metric-card"><div class="metric-icon">🦁</div><div class="metric-value"><?= $metricas['bitacoras']['opus_magnum'] ?></div><div class="metric-label">OPUS MAGNUM</div></div>
            <div class="metric-card"><div class="metric-icon">🌀</div><div class="metric-value"><?= $metricas['bitacoras']['log'] ?></div><div class="metric-label">LOG</div></div>
        </div>
    </div>
    <?php endif; ?>

    <?php if ($dashboardActivo==='analitico'): ?>
    <div class="dashboard-section active">
        <div class="charts-grid">
            <div class="chart-container"><h3>📈 Evolución</h3><canvas id="chart-evolucion"></canvas></div>
            <div class="chart-container"><h3>🎯 Progreso por Nodo</h3><canvas id="chart-nodos"></canvas></div>
        </div>
    </div>
    <?php endif; ?>
</main>
</div>

<script>
<?php if ($dashboardActivo==='general'): ?>
new Chart(document.getElementById('chart-log'),{type:'doughnut',data:{labels:['Niñes','Juventud','Adultes','Proyecto'],datasets:[{data:[<?= $metricas['log_personal']['niñes'] ?>,<?= $metricas['log_personal']['juventud'] ?>,<?= $metricas['log_personal']['adultes'] ?>,<?= $metricas['log_personal']['proyecto'] ?>],backgroundColor:['#10b981','#f59e0b','#ff69b4','#9c27b0']}]},options:{plugins:{legend:{labels:{color:'#e0e0e0'}}}}});
new Chart(document.getElementById('chart-bitacoras'),{type:'bar',data:{labels:['TEXVN','SAIAYIN DO','OPUS MAGNUM','LOG'],datasets:[{data:[<?= $metricas['bitacoras']['texvn'] ?>,<?= $metricas['bitacoras']['saiayin_do'] ?>,<?= $metricas['bitacoras']['opus_magnum'] ?>,<?= $metricas['bitacoras']['log'] ?>],backgroundColor:['#00bcd4','#ff69b4','#fffc34','#9c27b0']}]},options:{plugins:{legend:{display:false}},scales:{y:{ticks:{color:'#a0a0a0'}},x:{ticks:{color:'#a0a0a0'}}}}});
<?php endif; ?>
<?php if ($dashboardActivo==='analitico'): ?>
new Chart(document.getElementById('chart-evolucion'),{type:'line',data:{labels:['Niñes','Juventud','Adultes','Proyecto','Bitácoras','Total'],datasets:[{data:[<?= $metricas['log_personal']['niñes'] ?>,<?= $metricas['log_personal']['juventud'] ?>,<?= $metricas['log_personal']['adultes'] ?>,<?= $metricas['log_personal']['proyecto'] ?>,<?= $metricas['bitacoras']['total'] ?>,<?= $metricas['log_personal']['total'] ?>],borderColor:'#ff69b4',backgroundColor:'rgba(255,105,180,0.2)'}]},options:{scales:{y:{ticks:{color:'#a0a0a0'}},x:{ticks:{color:'#a0a0a0'}}}}});
new Chart(document.getElementById('chart-nodos'),{type:'radar',data:{labels:['TEXVN','SAIAYIN DO','OPUS MAGNUM','LOG'],datasets:[{data:[<?= $metricas['bitacoras']['texvn'] ?>,<?= $metricas['bitacoras']['saiayin_do'] ?>,<?= $metricas['bitacoras']['opus_magnum'] ?>,<?= $metricas['bitacoras']['log'] ?>],borderColor:'#fffc34',backgroundColor:'rgba(255,252,52,0.2)'}]},options:{scales:{r:{ticks:{color:'#a0a0a0'},pointLabels:{color:'#e0e0e0'}}}}});
<?php endif; ?>
</script>
</body>
</html>