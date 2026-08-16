<?php
/**
 * CONSEJO DE SABIOS - CONFIGURACIÓN EXCLUSIVA DE IAs
 * Panel dedicado a las 3 Inteligencias Artificiales
 * Lee y escribe la clave 'ia' de config/settings.json
 */
session_start();

$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';

$config = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];
$ia = $config['ia'] ?? [
    'openai_key' => '', 'modelo_gpt' => 'gpt-3.5-turbo',
    'gemini_key' => '', 'modelo_gemini' => 'gemini-1.5-flash',
    'qwen_key' => '', 'modelo_qwen' => 'qwen-max'
];

$mensaje = '';
$tipoMensaje = '';

// ============================================
// GUARDAR CLAVES (fusiona solo la clave 'ia')
// ============================================
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $config['ia'] = [
        'openai_key' => trim($_POST['openai_key'] ?? ''),
        'modelo_gpt' => trim($_POST['modelo_gpt'] ?? 'gpt-3.5-turbo'),
        'gemini_key' => trim($_POST['gemini_key'] ?? ''),
        'modelo_gemini' => trim($_POST['modelo_gemini'] ?? 'gemini-1.5-flash'),
        'qwen_key' => trim($_POST['qwen_key'] ?? ''),
        'modelo_qwen' => trim($_POST['modelo_qwen'] ?? 'qwen-max')
    ];
    
    if (file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $mensaje = '✅ Claves del Consejo de Sabios guardadas.';
        $tipoMensaje = 'success';
        $ia = $config['ia'];
    } else {
        $mensaje = '❌ Error al guardar.';
        $tipoMensaje = 'error';
    }
}

// ============================================
// PROBAR CONEXIÓN DE UNA IA
// ============================================
$resultadoPrueba = null;
if (isset($_GET['probar'])) {
    $prov = $_GET['probar'];
    
    if ($prov === 'openai' && !empty($ia['openai_key'])) {
        $ch = curl_init('https://api.openai.com/v1/models');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Authorization: Bearer ' . $ia['openai_key']]);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $resultadoPrueba = ['prov' => 'ChatGPT', 'ok' => ($code === 200), 'code' => $code];
    }
    
    if ($prov === 'gemini' && !empty($ia['gemini_key'])) {
        $ch = curl_init('https://generativelanguage.googleapis.com/v1beta/models?key=' . $ia['gemini_key']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $resultadoPrueba = ['prov' => 'Gemini', 'ok' => ($code === 200), 'code' => $code];
    }
    
    if ($prov === 'qwen' && !empty($ia['qwen_key'])) {
        $ch = curl_init('https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 10);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'model' => $ia['modelo_qwen'],
            'input' => ['messages' => [['role' => 'user', 'content' => 'ping']]]
        ]));
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $ia['qwen_key'],
            'Content-Type: application/json'
        ]);
        curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        $resultadoPrueba = ['prov' => 'Qwen', 'ok' => ($code === 200), 'code' => $code];
    }
}

$estado = [
    'openai' => !empty($ia['openai_key']),
    'gemini' => !empty($ia['gemini_key']),
    'qwen' => !empty($ia['qwen_key'])
];
$activas = count(array_filter($estado));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Consejo de Sabios | The Difference</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family:'Courier New',monospace; background:#0a0a0a; color:#e0e0e0; min-height:100vh; padding:2rem; }
.container { max-width:1100px; margin:0 auto; }
.header { border-bottom:2px solid #00bcd4; padding-bottom:1rem; margin-bottom:2rem; display:flex; justify-content:space-between; align-items:center; }
.header h1 { color:#00bcd4; font-size:1.8rem; letter-spacing:3px; }
.header .count { background:#00bcd4; color:#000; padding:0.3rem 0.8rem; border-radius:12px; font-weight:bold; font-size:0.85rem; }
.alert { padding:1rem; border-radius:6px; margin-bottom:1.5rem; font-size:0.9rem; }
.alert.success { background:rgba(16,185,129,0.15); border:1px solid #10b981; color:#10b981; }
.alert.error { background:rgba(239,68,68,0.15); border:1px solid #ef4444; color:#ef4444; }
.alert.info { background:rgba(0,188,212,0.15); border:1px solid #00bcd4; color:#00bcd4; }
.ias-grid { display:grid; grid-template-columns:repeat(auto-fit,minmax(300px,1fr)); gap:1.5rem; margin-bottom:2rem; }
.ia-card { background:#151515; border:1px solid #333; border-radius:10px; padding:1.5rem; position:relative; overflow:hidden; }
.ia-card::before { content:''; position:absolute; top:0; left:0; right:0; height:3px; }
.ia-card.openai::before { background:#10b981; }
.ia-card.gemini::before { background:#8b5cf6; }
.ia-card.qwen::before { background:#f59e0b; }
.ia-head { display:flex; justify-content:space-between; align-items:center; margin-bottom:1rem; }
.ia-nombre { font-size:1.1rem; font-weight:bold; }
.openai .ia-nombre { color:#10b981; }
.gemini .ia-nombre { color:#8b5cf6; }
.qwen .ia-nombre { color:#f59e0b; }
.ia-status { padding:0.2rem 0.7rem; border-radius:12px; font-size:0.7rem; font-weight:bold; }
.ia-status.on { background:rgba(16,185,129,0.2); color:#10b981; }
.ia-status.off { background:rgba(102,102,102,0.2); color:#666; }
.form-group { margin-bottom:1rem; }
.form-group label { display:block; color:#a0a0a0; font-size:0.8rem; margin-bottom:0.4rem; }
.form-group input { width:100%; padding:0.6rem; background:#1a1a1a; border:1px solid #333; border-radius:4px; color:#e0e0e0; font-family:inherit; font-size:0.85rem; }
.form-group input:focus { outline:none; border-color:#00bcd4; }
.btn-test { width:100%; padding:0.6rem; background:transparent; border:1px solid #00bcd4; color:#00bcd4; border-radius:4px; cursor:pointer; font-family:inherit; font-size:0.8rem; text-decoration:none; display:block; text-align:center; transition:all 0.2s; }
.btn-test:hover { background:#00bcd4; color:#000; }
.save-bar { display:flex; justify-content:flex-end; padding-top:1.5rem; border-top:1px solid #333; }
.btn-save { padding:0.8rem 2rem; background:#00bcd4; color:#000; border:none; border-radius:6px; font-family:inherit; font-weight:bold; cursor:pointer; }
.btn-save:hover { background:#fffc34; }
.back { display:inline-block; margin-bottom:1.5rem; color:#666; text-decoration:none; font-size:0.85rem; }
.back:hover { color:#ff69b4; }
.intro { background:#151515; border:1px solid #333; border-radius:8px; padding:1.5rem; margin-bottom:2rem; }
.intro h3 { color:#fffc34; margin-bottom:0.75rem; font-size:1rem; }
.intro p { color:#a0a0a0; font-size:0.85rem; line-height:1.5; }
.intro ul { color:#a0a0a0; font-size:0.85rem; margin-left:1.5rem; margin-top:0.5rem; }
.intro li { margin-bottom:0.3rem; }
</style>
</head>
<body>
<div class="container">
    <a href="hub-central.php" class="back">← Volver al Hub Central</a>
    
    <div class="header">
        <h1>🧠 CONSEJO DE SABIOS</h1>
        <span class="count"><?= $activas ?>/3 IAs Activas</span>
    </div>
    
    <?php if ($mensaje): ?><div class="alert <?= $tipoMensaje ?>"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
    
    <?php if ($resultadoPrueba): ?>
        <div class="alert <?= $resultadoPrueba['ok'] ? 'success' : 'error' ?>">
            🧪 Prueba <strong><?= htmlspecialchars($resultadoPrueba['prov']) ?></strong> → 
            <?= $resultadoPrueba['ok'] ? '✅ CONECTADO' : '❌ FALLO (HTTP ' . $resultadoPrueba['code'] . ')' ?>
        </div>
    <?php endif; ?>
    
    <div class="intro">
        <h3>🌌 El Consejo de Sabios</h3>
        <p>Tres inteligencias artificiales analizan tus bitácoras en busca de Conceptos Modos y síntesis pedagógicas.</p>
        <ul>
            <li>💬 <strong>ChatGPT</strong> (OpenAI): Pensamiento crítico y reflexivo</li>
            <li>✨ <strong>Gemini</strong> (Google): Análisis simbólico y contextual</li>
            <li>🐉 <strong>Qwen</strong> (Alibaba): Visión técnica y ontológica</li>
        </ul>
    </div>
    
    <form method="POST">
        <div class="ias-grid">
            <!-- CHATGPT -->
            <div class="ia-card openai">
                <div class="ia-head">
                    <span class="ia-nombre">💬 ChatGPT · OpenAI</span>
                    <span class="ia-status <?= $estado['openai'] ? 'on' : 'off' ?>"><?= $estado['openai'] ? 'ACTIVO' : 'SIN CLAVE' ?></span>
                </div>
                <div class="form-group">
                    <label>API Key</label>
                    <input type="password" name="openai_key" value="<?= htmlspecialchars($ia['openai_key']) ?>" placeholder="sk-...">
                </div>
                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_gpt" value="<?= htmlspecialchars($ia['modelo_gpt']) ?>">
                </div>
                <a class="btn-test" href="?probar=openai">🧪 Probar Conexión</a>
            </div>
            
            <!-- GEMINI -->
            <div class="ia-card gemini">
                <div class="ia-head">
                    <span class="ia-nombre">✨ Gemini · Google</span>
                    <span class="ia-status <?= $estado['gemini'] ? 'on' : 'off' ?>"><?= $estado['gemini'] ? 'ACTIVO' : 'SIN CLAVE' ?></span>
                </div>
                <div class="form-group">
                    <label>API Key</label>
                    <input type="password" name="gemini_key" value="<?= htmlspecialchars($ia['gemini_key']) ?>" placeholder="AIza...">
                </div>
                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_gemini" value="<?= htmlspecialchars($ia['modelo_gemini']) ?>">
                </div>
                <a class="btn-test" href="?probar=gemini">🧪 Probar Conexión</a>
            </div>
            
            <!-- QWEN -->
            <div class="ia-card qwen">
                <div class="ia-head">
                    <span class="ia-nombre">🐉 Qwen · Alibaba</span>
                    <span class="ia-status <?= $estado['qwen'] ? 'on' : 'off' ?>"><?= $estado['qwen'] ? 'ACTIVO' : 'SIN CLAVE' ?></span>
                </div>
                <div class="form-group">
                    <label>API Key (DashScope)</label>
                    <input type="password" name="qwen_key" value="<?= htmlspecialchars($ia['qwen_key']) ?>" placeholder="sk-...">
                </div>
                <div class="form-group">
                    <label>Modelo</label>
                    <input type="text" name="modelo_qwen" value="<?= htmlspecialchars($ia['modelo_qwen']) ?>">
                </div>
                <a class="btn-test" href="?probar=qwen">🧪 Probar Conexión</a>
            </div>
        </div>
        
        <div class="save-bar">
            <button type="submit" class="btn-save">💾 Guardar Claves del Consejo</button>
        </div>
    </form>
</div>
</body>
</html>