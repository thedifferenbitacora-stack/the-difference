<?php
// === CONFIGURACIÓN DINÁMICA ===
// Estas variables se definen en cada panel específico antes de incluir este archivo
// $prefix = 'log_'; (ejemplo)
// $pageName = 'Log'; (ejemplo)
// $pageSlug = 'log'; (ejemplo)

// Guards para apertura directa (sin variables previas)
if (!isset($prefix))   $prefix   = 'nodo_';
if (!isset($pageName)) $pageName = 'Nodo';
if (!isset($pageSlug)) $pageSlug = 'nodo';

session_start();
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$imagesDir = $baseDir . '/images';
$videosDir = $baseDir . '/videos';

if (!is_dir($imagesDir)) mkdir($imagesDir, 0777, true);
if (!is_dir($videosDir)) mkdir($videosDir, 0777, true);

$mensaje = '';

function loadConfig($f) { return file_exists($f) ? json_decode(file_get_contents($f), true) ?: [] : []; }
function saveConfig($f, $c) { return file_put_contents($f, json_encode($c, JSON_PRETTY_PRINT)); }

function processFileUpload($file, $type, $page, $imagesDir, $videosDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return false;
    $dir = ($type === 'image') ? $imagesDir : $videosDir;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = ($type === 'image') ? "logo-{$page}.{$ext}" : "video-{$page}.mp4";
    $targetPath = $dir . '/' . $fileName;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ($type === 'image') ? "images/{$fileName}" : "videos/{$fileName}";
    }
    return false;
}

// Lectura segura con valor por defecto
function val($config, $key, $def = '') {
    return isset($config[$key]) ? $config[$key] : $def;
}

$config = loadConfig($configFile);

$defaults = [
    $prefix . 'title_text' => strtoupper($pageName),
    $prefix . 'title_size' => 60,
    $prefix . 'title_color' => '#ffffff',
    $prefix . 'title_font' => 'Arial Black',
    $prefix . 'title_animation' => 'fadeInDown',
    $prefix . 'title_position_x' => 50,
    $prefix . 'title_position_y' => 20,
    $prefix . 'subtitle_text' => 'Subtítulo de ' . $pageName,
    $prefix . 'subtitle_size' => 14,
    $prefix . 'subtitle_color' => '#a0a0a0',
    $prefix . 'subtitle_font' => 'Arial Black',
    $prefix . 'subtitle_position_x' => 50,
    $prefix . 'subtitle_position_y' => 35,
    $prefix . 'logo_type' => 'image',
    $prefix . 'logo_path' => 'images/logo-feeling-autistic.png',
    $prefix . 'logo_size' => 40,
    $prefix . 'logo_border_radius' => 50,
    $prefix . 'logo_border_width' => 3,
    $prefix . 'logo_border_color' => '#ffffff',
    $prefix . 'logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
    $prefix . 'logo_position_x' => 50,
    $prefix . 'logo_position_y' => 50,
    $prefix . 'btn_main_text' => strtoupper($pageName),
    $prefix . 'btn_main_link' => '#',
    $prefix . 'btn_main_size' => 45,
    $prefix . 'btn_main_color' => '#ffffff',
    $prefix . 'btn_main_hover' => '#ff69b4',
    $prefix . 'btn_main_border_width' => 3,
    $prefix . 'btn_main_border_color' => '#ffffff',
    $prefix . 'btn_main_position_x' => 50,
    $prefix . 'btn_main_position_y' => 70,
    $prefix . 'btn_sec_items' => '',
    $prefix . 'btn_sec_size' => 14,
    $prefix . 'btn_sec_color' => '#fffc34',
    $prefix . 'btn_sec_hover_color' => '#ffffff',
    $prefix . 'btn_sec_border_width' => 2,
    $prefix . 'btn_sec_border_color' => '#ffffff',
    $prefix . 'btn_sec_position_x' => 50,
    $prefix . 'btn_sec_position_y' => 80,
    $prefix . 'btn_bottom_text' => 'LE TEMATIK DESIGN',
    $prefix . 'btn_bottom_link' => 'le-tematik.php',
    $prefix . 'btn_bottom_size' => 14,
    $prefix . 'btn_bottom_color' => '#ff69b4',
    $prefix . 'btn_bottom_position_x' => 50,
    $prefix . 'btn_bottom_position_y' => 95,
];

$config = array_merge($defaults, $config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, $prefix) === 0) {
            $config[$key] = is_numeric($value) ? (strpos($value, '.') !== false ? (float)$value : (int)$value) : $value;
        }
    }
    
    if (isset($_FILES[$prefix . 'logo'])) {
        $result = processFileUpload($_FILES[$prefix . 'logo'], 'image', $pageSlug, $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'logo_path'] = $result; $mensaje .= "✅ Imagen subida. "; }
    }
    if (isset($_FILES[$prefix . 'video'])) {
        $result = processFileUpload($_FILES[$prefix . 'video'], 'video', $pageSlug, $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'video_path'] = $result; $config[$prefix . 'logo_type'] = 'video'; $mensaje .= "✅ Video subido. "; }
    }
    
    if (saveConfig($configFile, $config)) {
        $mensaje .= "✅ Configuración de {$pageName} guardada.";
        $config = loadConfig($configFile);
        $config = array_merge($defaults, $config);
    }
}

$googleFontsList = ['Arial Black','Roboto','Playfair Display','Orbitron','Space Mono'];
$animations = ['none'=>'Sin animación','fadeIn'=>'Fade In','fadeInUp'=>'Fade In Up','fadeInDown'=>'Fade In Down','zoomIn'=>'Zoom In'];
$shadows = ['none'=>'Sin sombra','0 0 30px rgba(255,105,180,0.5)'=>'Glow rosa','0 0 30px rgba(255,252,52,0.5)'=>'Glow amarillo'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel <?= htmlspecialchars($pageName) ?></title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: system-ui, sans-serif; background: #0f0f0f; color: #fff; min-height: 100vh; padding: 2rem; }
.container { max-width: 900px; margin: 0 auto; background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 2rem; }
h1 { font-size: 2rem; margin-bottom: 0.5rem; color: #ff69b4; }
.subtitle { color: #a0a0a0; margin-bottom: 2rem; }
.alert { padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; background: rgba(16,185,129,0.2); border: 1px solid #10b981; color: #10b981; }
.nav-links { display: flex; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap; padding-bottom: 1rem; border-bottom: 1px solid #333; }
.nav-links a { padding: 0.5rem 1rem; background: #252525; color: #a0a0a0; text-decoration: none; border-radius: 6px; border: 1px solid #333; font-size: 0.85rem; }
.nav-links a.active { background: #ff69b4; color: #fff; border-color: #ff69b4; }
.section { background: #252525; border: 1px solid #333; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
.section h2 { font-size: 1.2rem; margin-bottom: 1rem; color: #ff69b4; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.5rem; color: #a0a0a0; font-size: 0.85rem; }
.form-group input[type="range"] { width: 100%; accent-color: #ff69b4; }
.form-group input[type="color"] { width: 100%; height: 40px; border: 1px solid #333; border-radius: 4px; background: #1a1a1a; }
.form-group input[type="text"], .form-group select, .form-group input[type="file"] { width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333; border-radius: 4px; color: #fff; }
.value-display { color: #ff69b4; font-family: monospace; font-weight: bold; margin-top: 0.25rem; font-size: 0.9rem; }
.position-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #444; }
.save-btn { width: 100%; padding: 1rem; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 1rem; }
.save-btn:hover { background: #059669; }
.comma-help { color: #fffc34; font-size: 0.8rem; margin-top: 0.25rem; }
</style>
</head>
<body>
<div class="container">
<h1>📄 Panel de <?= htmlspecialchars($pageName) ?></h1>
<p class="subtitle">Configuración independiente de <?= htmlspecialchars($pageSlug) ?>.php</p>

<?php if ($mensaje): ?>
<div class="alert"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="nav-links">
<a href="panel-portada.php">🏠 Portada</a>
<a href="panel-menu.php">📋 Menú</a>
<a href="panel-log.php">📓 LOG</a>
<a href="panel-le-tematik.php">📄 LE TEMATIK</a>
<a href="panel-project-nada-brahma.php">📄 PROJECT NADA BRAHMA</a>
<a href="panel-texvn.php">📄 TEXVN</a>
<a href="panel-quantumlab.php">📄 QUANTUMLAB</a>
<a href="panel-pensamiento-autista.php">📄 PENSAMIENTO AUTISTA</a>
<a href="panel-saiayin-do.php">📄 SAIAYIN DO</a>
<a href="panel-ars-tekne.php">📄 ARS TEKNE</a>
<a href="panel-quiron-theatre.php">📄 QUIRÓN THEATRE</a>
<a href="../<?= htmlspecialchars($pageSlug) ?>.php" target="_blank">👁️ Ver <?= htmlspecialchars($pageName) ?></a>
</div>

<form method="POST" enctype="multipart/form-data">

<div class="section">
<h2>📝 Título</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>title_text" value="<?= htmlspecialchars(val($config, $prefix . 'title_text')) ?>"></div>
<div class="form-group"><label>Tamaño: <?= val($config, $prefix . 'title_size', 60) ?>px</label><input type="range" name="<?= $prefix ?>title_size" min="20" max="200" value="<?= val($config, $prefix . 'title_size', 60) ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= val($config, $prefix . 'title_size', 60) ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>title_color" value="<?= val($config, $prefix . 'title_color', '#ffffff') ?>"></div>
<div class="form-group"><label>Fuente</label><select name="<?= $prefix ?>title_font"><?php foreach ($googleFontsList as $f): ?><option value="<?= $f ?>" <?= val($config, $prefix . 'title_font', 'Arial Black') === $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>title_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= val($config, $prefix . 'title_animation', 'fadeInDown') === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= val($config, $prefix . 'title_position_x', 50) ?>%</label><input type="range" name="<?= $prefix ?>title_position_x" min="0" max="100" value="<?= val($config, $prefix . 'title_position_x', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'title_position_x', 50) ?>%</div></div>
<div class="form-group"><label>Y: <?= val($config, $prefix . 'title_position_y', 20) ?>%</label><input type="range" name="<?= $prefix ?>title_position_y" min="0" max="100" value="<?= val($config, $prefix . 'title_position_y', 20) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'title_position_y', 20) ?>%</div></div>
</div>
</div>

<div class="section">
<h2>📝 Subtítulo</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>subtitle_text" value="<?= htmlspecialchars(val($config, $prefix . 'subtitle_text')) ?>"></div>
<div class="form-group"><label>Tamaño: <?= val($config, $prefix . 'subtitle_size', 14) ?>px</label><input type="range" name="<?= $prefix ?>subtitle_size" min="8" max="40" value="<?= val($config, $prefix . 'subtitle_size', 14) ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= val($config, $prefix . 'subtitle_size', 14) ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>subtitle_color" value="<?= val($config, $prefix . 'subtitle_color', '#a0a0a0') ?>"></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= val($config, $prefix . 'subtitle_position_x', 50) ?>%</label><input type="range" name="<?= $prefix ?>subtitle_position_x" min="0" max="100" value="<?= val($config, $prefix . 'subtitle_position_x', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'subtitle_position_x', 50) ?>%</div></div>
<div class="form-group"><label>Y: <?= val($config, $prefix . 'subtitle_position_y', 35) ?>%</label><input type="range" name="<?= $prefix ?>subtitle_position_y" min="0" max="100" value="<?= val($config, $prefix . 'subtitle_position_y', 35) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'subtitle_position_y', 35) ?>%</div></div>
</div>
</div>

<div class="section">
<h2>🎬 Logo</h2>
<div class="form-grid">
<div class="form-group"><label>Tipo</label><select name="<?= $prefix ?>logo_type"><option value="image" <?= val($config, $prefix . 'logo_type', 'image') === 'image' ? 'selected' : '' ?>>Imagen</option><option value="video" <?= val($config, $prefix . 'logo_type', 'image') === 'video' ? 'selected' : '' ?>>Video</option></select></div>
<div class="form-group"><label>Subir Imagen</label><input type="file" name="<?= $prefix ?>logo" accept="image/*"></div>
<div class="form-group"><label>Subir Video</label><input type="file" name="<?= $prefix ?>video" accept="video/*"></div>
<div class="form-group"><label>Tamaño: <?= val($config, $prefix . 'logo_size', 40) ?>vh</label><input type="range" name="<?= $prefix ?>logo_size" min="10" max="100" value="<?= val($config, $prefix . 'logo_size', 40) ?>" oninput="this.nextElementSibling.textContent = this.value + 'vh'"><div class="value-display"><?= val($config, $prefix . 'logo_size', 40) ?>vh</div></div>
<div class="form-group"><label>Radio Borde: <?= val($config, $prefix . 'logo_border_radius', 50) ?>%</label><input type="range" name="<?= $prefix ?>logo_border_radius" min="0" max="50" value="<?= val($config, $prefix . 'logo_border_radius', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'logo_border_radius', 50) ?>%</div></div>
<div class="form-group"><label>Grosor Borde: <?= val($config, $prefix . 'logo_border_width', 3) ?>px</label><input type="range" name="<?= $prefix ?>logo_border_width" min="0" max="20" value="<?= val($config, $prefix . 'logo_border_width', 3) ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= val($config, $prefix . 'logo_border_width', 3) ?>px</div></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?= $prefix ?>logo_border_color" value="<?= val($config, $prefix . 'logo_border_color', '#ffffff') ?>"></div>
<div class="form-group"><label>Sombra</label><select name="<?= $prefix ?>logo_shadow"><?php foreach ($shadows as $v => $l): ?><option value="<?= $v ?>" <?= val($config, $prefix . 'logo_shadow', '0 0 30px rgba(255,105,180,0.5)') === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= val($config, $prefix . 'logo_position_x', 50) ?>%</label><input type="range" name="<?= $prefix ?>logo_position_x" min="0" max="100" value="<?= val($config, $prefix . 'logo_position_x', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'logo_position_x', 50) ?>%</div></div>
<div class="form-group"><label>Y: <?= val($config, $prefix . 'logo_position_y', 50) ?>%</label><input type="range" name="<?= $prefix ?>logo_position_y" min="0" max="100" value="<?= val($config, $prefix . 'logo_position_y', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'logo_position_y', 50) ?>%</div></div>
</div>
</div>

<div class="section">
<h2>🔘 Botón Principal</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>btn_main_text" value="<?= htmlspecialchars(val($config, $prefix . 'btn_main_text')) ?>"></div>
<div class="form-group"><label>Link</label><input type="text" name="<?= $prefix ?>btn_main_link" value="<?= htmlspecialchars(val($config, $prefix . 'btn_main_link')) ?>"></div>
<div class="form-group"><label>Tamaño: <?= val($config, $prefix . 'btn_main_size', 45) ?>px</label><input type="range" name="<?= $prefix ?>btn_main_size" min="20" max="150" value="<?= val($config, $prefix . 'btn_main_size', 45) ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= val($config, $prefix . 'btn_main_size', 45) ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>btn_main_color" value="<?= val($config, $prefix . 'btn_main_color', '#ffffff') ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?= $prefix ?>btn_main_hover" value="<?= val($config, $prefix . 'btn_main_hover', '#ff69b4') ?>"></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= val($config, $prefix . 'btn_main_position_x', 50) ?>%</label><input type="range" name="<?= $prefix ?>btn_main_position_x" min="0" max="100" value="<?= val($config, $prefix . 'btn_main_position_x', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'btn_main_position_x', 50) ?>%</div></div>
<div class="form-group"><label>Y: <?= val($config, $prefix . 'btn_main_position_y', 70) ?>%</label><input type="range" name="<?= $prefix ?>btn_main_position_y" min="0" max="100" value="<?= val($config, $prefix . 'btn_main_position_y', 70) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'btn_main_position_y', 70) ?>%</div></div>
</div>
</div>

<div class="section" style="border: 1px solid #fffc34;">
<h2 style="color: #fffc34;">🔗 Botones Secundarios (Separados por coma)</h2>
<div class="form-group">
<label>Items (separados por coma)</label>
<input type="text" name="<?= $prefix ?>btn_sec_items" value="<?= htmlspecialchars(val($config, $prefix . 'btn_sec_items')) ?>" style="font-size: 1.1rem; padding: 0.75rem;">
<div class="comma-help">Ejemplo: LOG, LE TEMATIK, PROJECT NADA BRAHMA</div>
</div>
<div class="form-grid">
<div class="form-group"><label>Tamaño: <?= val($config, $prefix . 'btn_sec_size', 14) ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_size" min="10" max="40" value="<?= val($config, $prefix . 'btn_sec_size', 14) ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= val($config, $prefix . 'btn_sec_size', 14) ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>btn_sec_color" value="<?= val($config, $prefix . 'btn_sec_color', '#fffc34') ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?= $prefix ?>btn_sec_hover_color" value="<?= val($config, $prefix . 'btn_sec_hover_color', '#ffffff') ?>"></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= val($config, $prefix . 'btn_sec_position_x', 50) ?>%</label><input type="range" name="<?= $prefix ?>btn_sec_position_x" min="0" max="100" value="<?= val($config, $prefix . 'btn_sec_position_x', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'btn_sec_position_x', 50) ?>%</div></div>
<div class="form-group"><label>Y: <?= val($config, $prefix . 'btn_sec_position_y', 80) ?>%</label><input type="range" name="<?= $prefix ?>btn_sec_position_y" min="0" max="100" value="<?= val($config, $prefix . 'btn_sec_position_y', 80) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'btn_sec_position_y', 80) ?>%</div></div>
</div>
</div>

<div class="section">
<h2>🔘 Botón Inferior</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>btn_bottom_text" value="<?= htmlspecialchars(val($config, $prefix . 'btn_bottom_text')) ?>"></div>
<div class="form-group"><label>Link</label><input type="text" name="<?= $prefix ?>btn_bottom_link" value="<?= htmlspecialchars(val($config, $prefix . 'btn_bottom_link')) ?>"></div>
<div class="form-group"><label>Tamaño: <?= val($config, $prefix . 'btn_bottom_size', 14) ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_size" min="10" max="40" value="<?= val($config, $prefix . 'btn_bottom_size', 14) ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= val($config, $prefix . 'btn_bottom_size', 14) ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>btn_bottom_color" value="<?= val($config, $prefix . 'btn_bottom_color', '#ff69b4') ?>"></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= val($config, $prefix . 'btn_bottom_position_x', 50) ?>%</label><input type="range" name="<?= $prefix ?>btn_bottom_position_x" min="0" max="100" value="<?= val($config, $prefix . 'btn_bottom_position_x', 50) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'btn_bottom_position_x', 50) ?>%</div></div>
<div class="form-group"><label>Y: <?= val($config, $prefix . 'btn_bottom_position_y', 95) ?>%</label><input type="range" name="<?= $prefix ?>btn_bottom_position_y" min="0" max="100" value="<?= val($config, $prefix . 'btn_bottom_position_y', 95) ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= val($config, $prefix . 'btn_bottom_position_y', 95) ?>%</div></div>
</div>
</div>

<button type="submit" class="save-btn">💾 GUARDAR CONFIGURACIÓN DE <?= strtoupper($pageName) ?></button>
</form>
</div>
</body>
</html>