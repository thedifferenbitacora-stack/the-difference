<?php
session_start();
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$imagesDir = $baseDir . '/images';
$videosDir = $baseDir . '/videos';
if (!is_dir($imagesDir)) mkdir($imagesDir, 0777, true);
if (!is_dir($videosDir)) mkdir($videosDir, 0777, true);

$prefix = 'menu_';
$mensaje = '';

function loadConfig($f) { 
    return file_exists($f) ? (json_decode(file_get_contents($f), true) ?: []) : []; 
}
function saveConfig($f, $c) { 
    return file_put_contents($f, json_encode($c, JSON_PRETTY_PRINT)); 
}
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

$config = loadConfig($configFile);
$defaults = [
    $prefix . 'bg_type' => 'solid',
    $prefix . 'bg_color' => '#000000',
    $prefix . 'bg_image_path' => '',
    $prefix . 'bg_video_path' => '',
    $prefix . 'bg_overlay_color' => '#000000',
    $prefix . 'bg_overlay_opacity' => 0.3,
    $prefix . 'title_text' => 'FEELING AUTISTIC',
    $prefix . 'title_size' => 60,
    $prefix . 'title_color' => '#ffffff',
    $prefix . 'title_font' => 'Arial Black',
    $prefix . 'title_font_weight' => 900,
    $prefix . 'title_letter_spacing' => 5,
    $prefix . 'title_text_transform' => 'uppercase',
    $prefix . 'title_text_align' => 'center',
    $prefix . 'title_position_x' => 50,
    $prefix . 'title_position_y' => 15,
    $prefix . 'title_animation' => 'fadeInDown',
    $prefix . 'title_zindex' => 10,
    $prefix . 'subtitle_text' => 'NEURODIVERGENCE CREATIVE PHILOSOPHY PLATFORM',
    $prefix . 'subtitle_size' => 14,
    $prefix . 'subtitle_color' => '#a0a0a0',
    $prefix . 'subtitle_font' => 'Arial Black',
    $prefix . 'subtitle_font_weight' => 400,
    $prefix . 'subtitle_letter_spacing' => 2,
    $prefix . 'subtitle_text_transform' => 'uppercase',
    $prefix . 'subtitle_position_x' => 50,
    $prefix . 'subtitle_position_y' => 28,
    $prefix . 'subtitle_zindex' => 10,
    $prefix . 'logo_type' => 'image',
    $prefix . 'logo_path' => 'images/logo-feeling-autistic.png',
    $prefix . 'logo_size' => 55, // Alineado con el ojo
    $prefix . 'logo_border_radius' => 50,
    $prefix . 'logo_border_width' => 0, // Sin borde por defecto
    $prefix . 'logo_border_color' => '#ffffff',
    $prefix . 'logo_shadow' => 'none',
    $prefix . 'logo_position_x' => 50,
    $prefix . 'logo_position_y' => 45,
    $prefix . 'logo_zindex' => 10,
    $prefix . 'logo_animation' => 'zoomIn',
    // ============================================
    // OJO INDEPENDIENTE DEL MENÚ
    // ============================================
    $prefix . 'eye_type' => 'image',
    $prefix . 'eye_path' => 'images/eye-bg.png',
    $prefix . 'eye_size' => 55,
    $prefix . 'eye_border_radius' => 50,
    $prefix . 'eye_object_fit' => 'contain',
    $prefix . 'eye_shadow' => '0 0 40px rgba(255,105,180,0.6)',
    $prefix . 'eye_position_x' => 50,
    $prefix . 'eye_position_y' => 45,
    $prefix . 'eye_zindex' => 9,
    $prefix . 'eye_animation' => 'zoomIn',
    // PREPARACIÓN PRÓXIMA ETAPA (VIDEO AL HACER CLIC)
    $prefix . 'eye_click_action' => 'none',
    $prefix . 'eye_video_path' => '',
    // BOTONES
    $prefix . 'btn_main_text' => 'THE DIFFERENCE',
    $prefix . 'btn_main_link' => 'portada.php',
    $prefix . 'btn_main_size' => 45,
    $prefix . 'btn_main_color' => '#ffffff',
    $prefix . 'btn_main_hover' => '#ff69b4',
    $prefix . 'btn_main_border_width' => 0,
    $prefix . 'btn_main_border_color' => '#ffffff',
    $prefix . 'btn_main_border_radius' => 0,
    $prefix . 'btn_main_padding_v' => 18,
    $prefix . 'btn_main_padding_h' => 35,
    $prefix . 'btn_main_letter_spacing' => 4,
    $prefix . 'btn_main_font' => 'Arial Black',
    $prefix . 'btn_main_font_weight' => 900,
    $prefix . 'btn_main_text_transform' => 'uppercase',
    $prefix . 'btn_main_text_align' => 'center',
    $prefix . 'btn_main_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)',
    $prefix . 'btn_main_transform_hover' => 'scale(1.05)',
    $prefix . 'btn_main_animation' => 'fadeInUp',
    $prefix . 'btn_main_position_x' => 50,
    $prefix . 'btn_main_position_y' => 65,
    $prefix . 'btn_main_zindex' => 10,
    $prefix . 'btn_sec_items' => 'LOG, LE TEMATIK, PROJECT NADA BRAHMA, TEXVN, QUANTUMLAB, PENSAMIENTO AUTISTA, SAIAYIN DO, ARS TEKNE, QUIRÓN THEATRE',
    $prefix . 'btn_sec_size' => 14,
    $prefix . 'btn_sec_color' => '#fffc34',
    $prefix . 'btn_sec_hover' => '#ffffff',
    $prefix . 'btn_sec_border_width' => 0,
    $prefix . 'btn_sec_border_color' => '#ffffff',
    $prefix . 'btn_sec_border_radius' => 0,
    $prefix . 'btn_sec_padding_v' => 8,
    $prefix . 'btn_sec_padding_h' => 16,
    $prefix . 'btn_sec_letter_spacing' => 1,
    $prefix . 'btn_sec_font' => 'Arial Black',
    $prefix . 'btn_sec_font_weight' => 900,
    $prefix . 'btn_sec_text_transform' => 'uppercase',
    $prefix . 'btn_sec_text_align' => 'center',
    $prefix . 'btn_sec_shadow_hover' => '0 0 15px rgba(255,252,52,0.5)',
    $prefix . 'btn_sec_transform_hover' => 'scale(1.05)',
    $prefix . 'btn_sec_animation' => 'fadeIn',
    $prefix . 'btn_sec_gap' => 10,
    $prefix . 'btn_sec_position_x' => 50,
    $prefix . 'btn_sec_position_y' => 80,
    $prefix . 'btn_sec_zindex' => 10,
    $prefix . 'btn_bottom_text' => 'LE TEMATIK DESIGN',
    $prefix . 'btn_bottom_link' => 'le-tematik.php',
    $prefix . 'btn_bottom_size' => 14,
    $prefix . 'btn_bottom_color' => '#ff69b4',
    $prefix . 'btn_bottom_hover' => '#ffffff',
    $prefix . 'btn_bottom_border_width' => 0,
    $prefix . 'btn_bottom_border_color' => '#ff69b4',
    $prefix . 'btn_bottom_border_radius' => 0,
    $prefix . 'btn_bottom_padding_v' => 8,
    $prefix . 'btn_bottom_padding_h' => 20,
    $prefix . 'btn_bottom_letter_spacing' => 2,
    $prefix . 'btn_bottom_font' => 'Arial Black',
    $prefix . 'btn_bottom_font_weight' => 900,
    $prefix . 'btn_bottom_text_transform' => 'uppercase',
    $prefix . 'btn_bottom_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)',
    $prefix . 'btn_bottom_transform_hover' => 'scale(1.05)',
    $prefix . 'btn_bottom_animation' => 'fadeInUp',
    $prefix . 'btn_bottom_position_x' => 50,
    $prefix . 'btn_bottom_position_y' => 95,
    $prefix . 'btn_bottom_zindex' => 10,
    // CONFIGURACIÓN DE PÁGINA
    $prefix . 'page_scroll_enabled' => true,
];
$config = array_merge($defaults, $config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, $prefix) === 0) {
            // Manejo especial para el checkbox de scroll
            if ($key === $prefix . 'page_scroll_enabled') {
                $config[$key] = ($value === '1' || $value === 'on');
            } else {
                $config[$key] = is_numeric($value) ? (strpos($value, '.') !== false ? (float)$value : (int)$value) : $value;
            }
        }
    }
    
    // Si el checkbox no se envía (está desmarcado), forzar a false
    if (!isset($_POST[$prefix . 'page_scroll_enabled'])) {
        $config[$prefix . 'page_scroll_enabled'] = false;
    }

    if (isset($_FILES[$prefix . 'logo'])) {
        $result = processFileUpload($_FILES[$prefix . 'logo'], 'image', 'menu', $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'logo_path'] = $result; $mensaje .= "✅ Logo subido. "; }
    }
    if (isset($_FILES[$prefix . 'video'])) {
        $result = processFileUpload($_FILES[$prefix . 'video'], 'video', 'menu', $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'video_path'] = $result; $config[$prefix . 'logo_type'] = 'video'; $mensaje .= "✅ Video logo subido. "; }
    }
    if (isset($_FILES[$prefix . 'eye'])) {
        $result = processFileUpload($_FILES[$prefix . 'eye'], 'image', 'menu-eye', $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'eye_path'] = $result; $mensaje .= "✅ Ojo del Menú subido. "; }
    }
    // SUBIDA DEL VIDEO DEL OJO (PRÓXIMA ETAPA)
    if (isset($_FILES[$prefix . 'eye_video'])) {
        $result = processFileUpload($_FILES[$prefix . 'eye_video'], 'video', 'menu-eye-action', $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'eye_video_path'] = $result; $mensaje .= "✅ Video de acción del ojo subido. "; }
    }
    if (isset($_FILES[$prefix . 'bg_image'])) {
        $result = processFileUpload($_FILES[$prefix . 'bg_image'], 'image', 'bg-menu', $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'bg_image_path'] = $result; $config[$prefix . 'bg_type'] = 'image'; $mensaje .= "✅ Imagen de fondo subida. "; }
    }
    if (isset($_FILES[$prefix . 'bg_video'])) {
        $result = processFileUpload($_FILES[$prefix . 'bg_video'], 'video', 'bg-menu', $imagesDir, $videosDir);
        if ($result) { $config[$prefix . 'bg_video_path'] = $result; $config[$prefix . 'bg_type'] = 'video'; $mensaje .= "✅ Video de fondo subido. "; }
    }
    
    if (saveConfig($configFile, $config)) {
        $mensaje .= "✅ Configuración de Menú guardada.";
        $config = loadConfig($configFile);
        $config = array_merge($defaults, $config);
    }
}

$googleFontsList = [
    'Arial Black', 'Roboto', 'Playfair Display', 'Orbitron', 'Space Mono',
    'Montserrat', 'Oswald', 'Raleway', 'Poppins', 'Bebas Neue',
    'Syne', 'Inter', 'Nunito', 'Quicksand', 'Lora',
    'Merriweather', 'Cormorant Garamond', 'Cinzel', 'DM Sans', 'Plus Jakarta Sans',
    'Work Sans', 'Outfit', 'Manrope', 'Lexend', 'Sora'
];
$animations = ['none'=>'Sin animación','fadeIn'=>'Fade In','fadeInUp'=>'Fade In Up','fadeInDown'=>'Fade In Down','zoomIn'=>'Zoom In','bounceIn'=>'Bounce In'];
$shadows = ['none'=>'Sin sombra','0 4px 6px rgba(0,0,0,0.3)'=>'Sombra suave','0 10px 20px rgba(0,0,0,0.5)'=>'Sombra media','0 0 20px rgba(255,105,180,0.5)'=>'Glow rosa','0 0 30px rgba(255,252,52,0.5)'=>'Glow amarillo'];
$transforms = ['scale(1.05)'=>'Escalar 1.05x','scale(1.1)'=>'Escalar 1.1x','scale(1.2)'=>'Escalar 1.2x','translateY(-5px)'=>'Subir 5px','translateY(-10px)'=>'Subir 10px','none'=>'Sin transformación'];
$objectFits = ['contain'=>'Contain (ajustar)','cover'=>'Cover (cortar)','fill'=>'Fill (estirar)','none'=>'None (original)'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Menú</title>
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
.form-group input[type="checkbox"] { width: auto; margin-right: 8px; accent-color: #ff69b4; }
.checkbox-label { display: flex; align-items: center; color: #fff; cursor: pointer; }
.value-display { color: #ff69b4; font-family: monospace; font-weight: bold; margin-top: 0.25rem; font-size: 0.9rem; }
.position-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; margin-top: 1rem; padding-top: 1rem; border-top: 1px solid #444; }
.save-btn { width: 100%; padding: 1rem; background: #10b981; color: white; border: none; border-radius: 6px; font-size: 1rem; font-weight: bold; cursor: pointer; margin-top: 1rem; }
.save-btn:hover { background: #059669; }
.hidden { display: none; }
.comma-help { color: #fffc34; font-size: 0.8rem; margin-top: 0.25rem; }
</style>
</head>
<body>
<div class="container">
<h1>📋 Panel de Menú</h1>
<p class="subtitle">Configuración completa de menu.php</p>
<?php if ($mensaje): ?><div class="alert"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>
<div class="nav-links">
<a href="panel-portada.php">🏠 Portada</a>
<a href="panel-menu.php" class="active">📋 Menú</a>
<a href="../menu.php" target="_blank">👁️ Ver Menú</a>
</div>
<form method="POST" enctype="multipart/form-data">

<div class="section">
<h2>🎨 Fondo de Pantalla</h2>
<div class="form-grid">
<div class="form-group"><label>Tipo de Fondo</label><select name="<?= $prefix ?>bg_type" id="bg_type" onchange="toggleBgSections()"><option value="solid" <?= $config[$prefix . 'bg_type'] === 'solid' ? 'selected' : '' ?>>Color Sólido</option><option value="image" <?= $config[$prefix . 'bg_type'] === 'image' ? 'selected' : '' ?>>🖼️ Imagen</option><option value="video" <?= $config[$prefix . 'bg_type'] === 'video' ? 'selected' : '' ?>>🎬 Video</option></select></div>
<div class="form-group"><label>Color de Fondo</label><input type="color" name="<?= $prefix ?>bg_color" value="<?= $config[$prefix . 'bg_color'] ?>"></div>
</div>
<div id="bg_image_section" class="<?= $config[$prefix . 'bg_type'] !== 'image' ? 'hidden' : '' ?>">
<div class="form-grid"><div class="form-group"><label>Subir Imagen de Fondo</label><input type="file" name="<?= $prefix ?>bg_image" accept="image/*"><?php if (!empty($config[$prefix . 'bg_image_path']) && file_exists($baseDir . '/' . $config[$prefix . 'bg_image_path'])): ?><div style="margin-top:10px"><img src="<?= $config[$prefix . 'bg_image_path'] ?>" style="max-width:200px;border-radius:4px"></div><?php endif; ?></div></div>
</div>
<div id="bg_video_section" class="<?= $config[$prefix . 'bg_type'] !== 'video' ? 'hidden' : '' ?>">
<div class="form-grid"><div class="form-group"><label>Subir Video de Fondo (MP4)</label><input type="file" name="<?= $prefix ?>bg_video" accept="video/*"><?php if (!empty($config[$prefix . 'bg_video_path']) && file_exists($baseDir . '/' . $config[$prefix . 'bg_video_path'])): ?><div style="margin-top:10px"><video src="<?= $config[$prefix . 'bg_video_path'] ?>" style="max-width:200px;border-radius:4px" controls></video></div><?php endif; ?></div></div>
</div>
<div class="form-grid" style="margin-top:1rem">
<div class="form-group"><label>Color del Overlay</label><input type="color" name="<?= $prefix ?>bg_overlay_color" value="<?= $config[$prefix . 'bg_overlay_color'] ?>"></div>
<div class="form-group"><label>Opacidad del Overlay: <?= $config[$prefix . 'bg_overlay_opacity'] ?></label><input type="range" name="<?= $prefix ?>bg_overlay_opacity" min="0" max="1" step="0.1" value="<?= $config[$prefix . 'bg_overlay_opacity'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'bg_overlay_opacity'] ?></div></div>
</div>
</div>

<div class="section">
<h2>📝 Título</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>title_text" value="<?= htmlspecialchars($config[$prefix . 'title_text']) ?>"></div>
<div class="form-group"><label>Tamaño: <?= $config[$prefix . 'title_size'] ?>px</label><input type="range" name="<?= $prefix ?>title_size" min="20" max="200" value="<?= $config[$prefix . 'title_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'title_size'] ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>title_color" value="<?= $config[$prefix . 'title_color'] ?>"></div>
<div class="form-group"><label>Fuente</label><select name="<?= $prefix ?>title_font"><?php foreach ($googleFontsList as $f): ?><option value="<?= $f ?>" <?= $config[$prefix . 'title_font'] === $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?= $config[$prefix . 'title_font_weight'] ?></label><input type="range" name="<?= $prefix ?>title_font_weight" min="100" max="900" step="100" value="<?= $config[$prefix . 'title_font_weight'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'title_font_weight'] ?></div></div>
<div class="form-group"><label>Espaciado: <?= $config[$prefix . 'title_letter_spacing'] ?>px</label><input type="range" name="<?= $prefix ?>title_letter_spacing" min="0" max="30" value="<?= $config[$prefix . 'title_letter_spacing'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'title_letter_spacing'] ?>px</div></div>
<div class="form-group"><label>Transformación</label><select name="<?= $prefix ?>title_text_transform"><option value="uppercase" <?= $config[$prefix . 'title_text_transform'] === 'uppercase' ? 'selected' : '' ?>>MAYÚSCULAS</option><option value="lowercase" <?= $config[$prefix . 'title_text_transform'] === 'lowercase' ? 'selected' : '' ?>>minúsculas</option><option value="capitalize" <?= $config[$prefix . 'title_text_transform'] === 'capitalize' ? 'selected' : '' ?>>Capitalizar</option><option value="none" <?= $config[$prefix . 'title_text_transform'] === 'none' ? 'selected' : '' ?>>Normal</option></select></div>
<div class="form-group"><label>Alineación</label><select name="<?= $prefix ?>title_text_align"><option value="left" <?= $config[$prefix . 'title_text_align'] === 'left' ? 'selected' : '' ?>>Izquierda</option><option value="center" <?= $config[$prefix . 'title_text_align'] === 'center' ? 'selected' : '' ?>>Centro</option><option value="right" <?= $config[$prefix . 'title_text_align'] === 'right' ? 'selected' : '' ?>>Derecha</option></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>title_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'title_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?= $config[$prefix . 'title_zindex'] ?></label><input type="range" name="<?= $prefix ?>title_zindex" min="1" max="100" value="<?= $config[$prefix . 'title_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'title_zindex'] ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= $config[$prefix . 'title_position_x'] ?>%</label><input type="range" name="<?= $prefix ?>title_position_x" min="0" max="100" value="<?= $config[$prefix . 'title_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'title_position_x'] ?>%</div></div>
<div class="form-group"><label>Y: <?= $config[$prefix . 'title_position_y'] ?>%</label><input type="range" name="<?= $prefix ?>title_position_y" min="0" max="100" value="<?= $config[$prefix . 'title_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'title_position_y'] ?>%</div></div>
</div>
</div>

<div class="section">
<h2>📝 Subtítulo</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>subtitle_text" value="<?= htmlspecialchars($config[$prefix . 'subtitle_text']) ?>"></div>
<div class="form-group"><label>Tamaño: <?= $config[$prefix . 'subtitle_size'] ?>px</label><input type="range" name="<?= $prefix ?>subtitle_size" min="8" max="40" value="<?= $config[$prefix . 'subtitle_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'subtitle_size'] ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>subtitle_color" value="<?= $config[$prefix . 'subtitle_color'] ?>"></div>
<div class="form-group"><label>Fuente</label><select name="<?= $prefix ?>subtitle_font"><?php foreach ($googleFontsList as $f): ?><option value="<?= $f ?>" <?= $config[$prefix . 'subtitle_font'] === $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?= $config[$prefix . 'subtitle_font_weight'] ?></label><input type="range" name="<?= $prefix ?>subtitle_font_weight" min="100" max="900" step="100" value="<?= $config[$prefix . 'subtitle_font_weight'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'subtitle_font_weight'] ?></div></div>
<div class="form-group"><label>Espaciado: <?= $config[$prefix . 'subtitle_letter_spacing'] ?>px</label><input type="range" name="<?= $prefix ?>subtitle_letter_spacing" min="0" max="20" value="<?= $config[$prefix . 'subtitle_letter_spacing'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'subtitle_letter_spacing'] ?>px</div></div>
<div class="form-group"><label>Transformación</label><select name="<?= $prefix ?>subtitle_text_transform"><option value="uppercase" <?= $config[$prefix . 'subtitle_text_transform'] === 'uppercase' ? 'selected' : '' ?>>MAYÚSCULAS</option><option value="lowercase" <?= $config[$prefix . 'subtitle_text_transform'] === 'lowercase' ? 'selected' : '' ?>>minúsculas</option><option value="capitalize" <?= $config[$prefix . 'subtitle_text_transform'] === 'capitalize' ? 'selected' : '' ?>>Capitalizar</option><option value="none" <?= $config[$prefix . 'subtitle_text_transform'] === 'none' ? 'selected' : '' ?>>Normal</option></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>subtitle_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'subtitle_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?= $config[$prefix . 'subtitle_zindex'] ?></label><input type="range" name="<?= $prefix ?>subtitle_zindex" min="1" max="100" value="<?= $config[$prefix . 'subtitle_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'subtitle_zindex'] ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= $config[$prefix . 'subtitle_position_x'] ?>%</label><input type="range" name="<?= $prefix ?>subtitle_position_x" min="0" max="100" value="<?= $config[$prefix . 'subtitle_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'subtitle_position_x'] ?>%</div></div>
<div class="form-group"><label>Y: <?= $config[$prefix . 'subtitle_position_y'] ?>%</label><input type="range" name="<?= $prefix ?>subtitle_position_y" min="0" max="100" value="<?= $config[$prefix . 'subtitle_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'subtitle_position_y'] ?>%</div></div>
</div>
</div>

<div class="section">
<h2>🎬 Logo/Video</h2>
<div class="form-grid">
<div class="form-group"><label>Tipo</label><select name="<?= $prefix ?>logo_type"><option value="image" <?= $config[$prefix . 'logo_type'] === 'image' ? 'selected' : '' ?>>Imagen</option><option value="video" <?= $config[$prefix . 'logo_type'] === 'video' ? 'selected' : '' ?>>Video</option></select></div>
<div class="form-group"><label>Subir Imagen</label><input type="file" name="<?= $prefix ?>logo" accept="image/*"></div>
<div class="form-group"><label>Subir Video</label><input type="file" name="<?= $prefix ?>video" accept="video/*"></div>
<div class="form-group"><label>Tamaño: <?= $config[$prefix . 'logo_size'] ?>vh</label><input type="range" name="<?= $prefix ?>logo_size" min="10" max="100" value="<?= $config[$prefix . 'logo_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'vh'"><div class="value-display"><?= $config[$prefix . 'logo_size'] ?>vh</div></div>
<div class="form-group"><label>Radio Borde: <?= $config[$prefix . 'logo_border_radius'] ?>%</label><input type="range" name="<?= $prefix ?>logo_border_radius" min="0" max="50" value="<?= $config[$prefix . 'logo_border_radius'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'logo_border_radius'] ?>%</div></div>
<div class="form-group"><label>Grosor Borde: <?= $config[$prefix . 'logo_border_width'] ?>px</label><input type="range" name="<?= $prefix ?>logo_border_width" min="0" max="20" value="<?= $config[$prefix . 'logo_border_width'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'logo_border_width'] ?>px</div></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?= $prefix ?>logo_border_color" value="<?= $config[$prefix . 'logo_border_color'] ?>"></div>
<div class="form-group"><label>Sombra</label><select name="<?= $prefix ?>logo_shadow"><?php foreach ($shadows as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'logo_shadow'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>logo_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'logo_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?= $config[$prefix . 'logo_zindex'] ?></label><input type="range" name="<?= $prefix ?>logo_zindex" min="1" max="100" value="<?= $config[$prefix . 'logo_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'logo_zindex'] ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= $config[$prefix . 'logo_position_x'] ?>%</label><input type="range" name="<?= $prefix ?>logo_position_x" min="0" max="100" value="<?= $config[$prefix . 'logo_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'logo_position_x'] ?>%</div></div>
<div class="form-group"><label>Y: <?= $config[$prefix . 'logo_position_y'] ?>%</label><input type="range" name="<?= $prefix ?>logo_position_y" min="0" max="100" value="<?= $config[$prefix . 'logo_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'logo_position_y'] ?>%</div></div>
</div>
</div>

<!-- ============================================ -->
<!-- OJO INDEPENDIENTE DEL MENÚ -->
<!-- ============================================ -->
<div class="section" style="border: 1px solid #ff69b4;">
<h2>👁️ Ojo del Menú (Detrás del Logo)</h2>
<p style="color:#a0a0a0; font-size:0.85rem; margin-bottom:1rem;">Configuración independiente. El ojo debe tener Z-Index menor que el del Logo (actualmente <?= $config[$prefix . 'logo_zindex'] ?>).</p>

<div class="form-grid">
<div class="form-group">
<label>Tipo</label>
<select name="<?= $prefix ?>eye_type">
<option value="image" <?= $config[$prefix . 'eye_type'] === 'image' ? 'selected' : '' ?>>Imagen</option>
<option value="none" <?= $config[$prefix . 'eye_type'] === 'none' ? 'selected' : '' ?>>Ocultar Ojo</option>
</select>
</div>

<div class="form-group">
<label>Subir Imagen del Ojo</label>
<input type="file" name="<?= $prefix ?>eye" accept="image/*">
<?php if (!empty($config[$prefix . 'eye_path']) && file_exists($baseDir . '/' . $config[$prefix . 'eye_path'])): ?>
<div style="margin-top:10px">
<img src="<?= $config[$prefix . 'eye_path'] ?>" style="max-width:150px; border-radius:50%; border:2px solid #ff69b4;">
<p style="font-size:0.75rem; color:#a0a0a0;">Actual: <?= $config[$prefix . 'eye_path'] ?></p>
</div>
<?php endif; ?>
</div>

<div class="form-group">
<label>Tamaño: <?= $config[$prefix . 'eye_size'] ?>vh</label>
<input type="range" name="<?= $prefix ?>eye_size" min="10" max="150" value="<?= $config[$prefix . 'eye_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'vh'">
<div class="value-display"><?= $config[$prefix . 'eye_size'] ?>vh</div>
</div>

<div class="form-group">
<label>Radio de Borde: <?= $config[$prefix . 'eye_border_radius'] ?>%</label>
<input type="range" name="<?= $prefix ?>eye_border_radius" min="0" max="50" value="<?= $config[$prefix . 'eye_border_radius'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'">
<div class="value-display"><?= $config[$prefix . 'eye_border_radius'] ?>% (50% = circular)</div>
</div>

<div class="form-group">
<label>Ajuste de Imagen</label>
<select name="<?= $prefix ?>eye_object_fit">
<?php foreach ($objectFits as $v => $l): ?>
<option value="<?= $v ?>" <?= $config[$prefix . 'eye_object_fit'] === $v ? 'selected' : '' ?>><?= $l ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<label>Glow (Sombra)</label>
<select name="<?= $prefix ?>eye_shadow">
<?php foreach ($shadows as $v => $l): ?>
<option value="<?= $v ?>" <?= $config[$prefix . 'eye_shadow'] === $v ? 'selected' : '' ?>><?= $l ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<label>Animación</label>
<select name="<?= $prefix ?>eye_animation">
<?php foreach ($animations as $v => $l): ?>
<option value="<?= $v ?>" <?= $config[$prefix . 'eye_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option>
<?php endforeach; ?>
</select>
</div>

<div class="form-group">
<label>Z-Index: <?= $config[$prefix . 'eye_zindex'] ?></label>
<input type="range" name="<?= $prefix ?>eye_zindex" min="1" max="20" value="<?= $config[$prefix . 'eye_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value">
<div class="value-display"><?= $config[$prefix . 'eye_zindex'] ?> (Debe ser menor que el del Logo)</div>
</div>
</div>

<div class="position-grid">
<div class="form-group">
<label>Posición X: <?= $config[$prefix . 'eye_position_x'] ?>%</label>
<input type="range" name="<?= $prefix ?>eye_position_x" min="0" max="100" value="<?= $config[$prefix . 'eye_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'">
<div class="value-display"><?= $config[$prefix . 'eye_position_x'] ?>%</div>
</div>
<div class="form-group">
<label>Posición Y: <?= $config[$prefix . 'eye_position_y'] ?>%</label>
<input type="range" name="<?= $prefix ?>eye_position_y" min="0" max="100" value="<?= $config[$prefix . 'eye_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'">
<div class="value-display"><?= $config[$prefix . 'eye_position_y'] ?>%</div>
</div>
</div>
</div>

<!-- ============================================ -->
<!-- VIÑETA: CONFIGURACIÓN DE PÁGINA Y SCROLL -->
<!-- ============================================ -->
<div class="section" style="border: 1px solid #fffc34;">
    <h2 style="color: #fffc34;">⚙️ Configuración de Página</h2>
    <div class="form-grid">
        <div class="form-group">
            <label class="checkbox-label">
                <input type="checkbox" name="<?= $prefix ?>page_scroll_enabled" value="1" <?= $config[$prefix . 'page_scroll_enabled'] ? 'checked' : '' ?>>
                <span>Activar Scroll Vertical</span>
            </label>
            <p style="color:#a0a0a0;font-size:0.75rem;margin-top:5px">Desactivar para página fija sin scroll (100vh)</p>
        </div>
    </div>
</div>

<!-- ============================================ -->
<!-- PREPARACIÓN SIGUIENTE ETAPA: ACCIÓN DEL OJO -->
<!-- ============================================ -->
<div class="section" style="border: 1px dashed #ff69b4;">
    <h2>👁️ Acción del Ojo (Próxima Etapa)</h2>
    <div class="form-grid">
        <div class="form-group">
            <label>Acción al hacer clic</label>
            <select name="<?= $prefix ?>eye_click_action">
                <option value="none" <?= $config[$prefix . 'eye_click_action'] === 'none' ? 'selected' : '' ?>>Ninguna (Solo imagen)</option>
                <option value="play_video" <?= $config[$prefix . 'eye_click_action'] === 'play_video' ? 'selected' : '' ?>>Reproducir Video (Ojo abriéndose)</option>
            </select>
        </div>
        <div class="form-group">
            <label>Video del Ojo (MP4)</label>
            <input type="file" name="<?= $prefix ?>eye_video" accept="video/mp4">
            <?php if (!empty($config[$prefix . 'eye_video_path'])): ?>
                <p style="color:#a0a0a0;font-size:0.75rem;margin-top:5px">Actual: <?= $config[$prefix . 'eye_video_path'] ?></p>
            <?php endif; ?>
        </div>
    </div>
</div>

<div class="section">
<h2>🔘 Botón Principal</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>btn_main_text" value="<?= htmlspecialchars($config[$prefix . 'btn_main_text']) ?>"></div>
<div class="form-group"><label>Link</label><input type="text" name="<?= $prefix ?>btn_main_link" value="<?= htmlspecialchars($config[$prefix . 'btn_main_link']) ?>"></div>
<div class="form-group"><label>Tamaño: <?= $config[$prefix . 'btn_main_size'] ?>px</label><input type="range" name="<?= $prefix ?>btn_main_size" min="20" max="150" value="<?= $config[$prefix . 'btn_main_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_main_size'] ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>btn_main_color" value="<?= $config[$prefix . 'btn_main_color'] ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?= $prefix ?>btn_main_hover" value="<?= $config[$prefix . 'btn_main_hover'] ?>"></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?= $prefix ?>btn_main_border_color" value="<?= $config[$prefix . 'btn_main_border_color'] ?>"></div>
<div class="form-group"><label>Grosor Borde: <?= $config[$prefix . 'btn_main_border_width'] ?>px</label><input type="range" name="<?= $prefix ?>btn_main_border_width" min="0" max="20" value="<?= $config[$prefix . 'btn_main_border_width'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_main_border_width'] ?>px</div></div>
<div class="form-group"><label>Radio Borde: <?= $config[$prefix . 'btn_main_border_radius'] ?>px</label><input type="range" name="<?= $prefix ?>btn_main_border_radius" min="0" max="100" value="<?= $config[$prefix . 'btn_main_border_radius'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_main_border_radius'] ?>px</div></div>
<div class="form-group"><label>Padding V: <?= $config[$prefix . 'btn_main_padding_v'] ?>px</label><input type="range" name="<?= $prefix ?>btn_main_padding_v" min="0" max="100" value="<?= $config[$prefix . 'btn_main_padding_v'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_main_padding_v'] ?>px</div></div>
<div class="form-group"><label>Padding H: <?= $config[$prefix . 'btn_main_padding_h'] ?>px</label><input type="range" name="<?= $prefix ?>btn_main_padding_h" min="0" max="200" value="<?= $config[$prefix . 'btn_main_padding_h'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_main_padding_h'] ?>px</div></div>
<div class="form-group"><label>Fuente</label><select name="<?= $prefix ?>btn_main_font"><?php foreach ($googleFontsList as $f): ?><option value="<?= $f ?>" <?= $config[$prefix . 'btn_main_font'] === $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?= $config[$prefix . 'btn_main_font_weight'] ?></label><input type="range" name="<?= $prefix ?>btn_main_font_weight" min="100" max="900" step="100" value="<?= $config[$prefix . 'btn_main_font_weight'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'btn_main_font_weight'] ?></div></div>
<div class="form-group"><label>Espaciado: <?= $config[$prefix . 'btn_main_letter_spacing'] ?>px</label><input type="range" name="<?= $prefix ?>btn_main_letter_spacing" min="0" max="30" value="<?= $config[$prefix . 'btn_main_letter_spacing'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_main_letter_spacing'] ?>px</div></div>
<div class="form-group"><label>Transformación</label><select name="<?= $prefix ?>btn_main_text_transform"><option value="uppercase" <?= $config[$prefix . 'btn_main_text_transform'] === 'uppercase' ? 'selected' : '' ?>>MAYÚSCULAS</option><option value="lowercase" <?= $config[$prefix . 'btn_main_text_transform'] === 'lowercase' ? 'selected' : '' ?>>minúsculas</option><option value="capitalize" <?= $config[$prefix . 'btn_main_text_transform'] === 'capitalize' ? 'selected' : '' ?>>Capitalizar</option><option value="none" <?= $config[$prefix . 'btn_main_text_transform'] === 'none' ? 'selected' : '' ?>>Normal</option></select></div>
<div class="form-group"><label>Alineación</label><select name="<?= $prefix ?>btn_main_text_align"><option value="left" <?= $config[$prefix . 'btn_main_text_align'] === 'left' ? 'selected' : '' ?>>Izquierda</option><option value="center" <?= $config[$prefix . 'btn_main_text_align'] === 'center' ? 'selected' : '' ?>>Centro</option><option value="right" <?= $config[$prefix . 'btn_main_text_align'] === 'right' ? 'selected' : '' ?>>Derecha</option></select></div>
<div class="form-group"><label>Sombra Hover</label><select name="<?= $prefix ?>btn_main_shadow_hover"><?php foreach ($shadows as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_main_shadow_hover'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Transform Hover</label><select name="<?= $prefix ?>btn_main_transform_hover"><?php foreach ($transforms as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_main_transform_hover'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>btn_main_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_main_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?= $config[$prefix . 'btn_main_zindex'] ?></label><input type="range" name="<?= $prefix ?>btn_main_zindex" min="1" max="100" value="<?= $config[$prefix . 'btn_main_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'btn_main_zindex'] ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= $config[$prefix . 'btn_main_position_x'] ?>%</label><input type="range" name="<?= $prefix ?>btn_main_position_x" min="0" max="100" value="<?= $config[$prefix . 'btn_main_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'btn_main_position_x'] ?>%</div></div>
<div class="form-group"><label>Y: <?= $config[$prefix . 'btn_main_position_y'] ?>%</label><input type="range" name="<?= $prefix ?>btn_main_position_y" min="0" max="100" value="<?= $config[$prefix . 'btn_main_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'btn_main_position_y'] ?>%</div></div>
</div>
</div>

<div class="section" style="border: 2px solid #fffc34;">
<h2 style="color: #fffc34;">🔘 Botones de Nodos (Separados por comas)</h2>
<p style="color: #a0a0a0; margin-bottom: 1rem; font-size: 0.9rem;">Escribe los nombres de los botones separados por coma. El sistema generará un botón por cada nombre.</p>
<div class="form-group">
<label>Nombres de los Botones (separados por coma)</label>
<input type="text" name="<?= $prefix ?>btn_sec_items" value="<?= htmlspecialchars($config[$prefix . 'btn_sec_items']) ?>" style="font-size: 1.1rem; padding: 0.75rem; border: 1px solid #fffc34;">
<div class="comma-help">Ejemplo: LOG, LE TEMATIK, PROJECT NADA BRAHMA, TEXVN, QUANTUMLAB</div>
</div>
<div class="form-grid">
<div class="form-group"><label>Tamaño: <?= $config[$prefix . 'btn_sec_size'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_size" min="10" max="40" value="<?= $config[$prefix . 'btn_sec_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_size'] ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>btn_sec_color" value="<?= $config[$prefix . 'btn_sec_color'] ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?= $prefix ?>btn_sec_hover" value="<?= $config[$prefix . 'btn_sec_hover'] ?>"></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?= $prefix ?>btn_sec_border_color" value="<?= $config[$prefix . 'btn_sec_border_color'] ?>"></div>
<div class="form-group"><label>Grosor Borde: <?= $config[$prefix . 'btn_sec_border_width'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_border_width" min="0" max="20" value="<?= $config[$prefix . 'btn_sec_border_width'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_border_width'] ?>px</div></div>
<div class="form-group"><label>Radio Borde: <?= $config[$prefix . 'btn_sec_border_radius'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_border_radius" min="0" max="50" value="<?= $config[$prefix . 'btn_sec_border_radius'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_border_radius'] ?>px</div></div>
<div class="form-group"><label>Padding V: <?= $config[$prefix . 'btn_sec_padding_v'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_padding_v" min="0" max="50" value="<?= $config[$prefix . 'btn_sec_padding_v'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_padding_v'] ?>px</div></div>
<div class="form-group"><label>Padding H: <?= $config[$prefix . 'btn_sec_padding_h'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_padding_h" min="0" max="100" value="<?= $config[$prefix . 'btn_sec_padding_h'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_padding_h'] ?>px</div></div>
<div class="form-group"><label>Fuente</label><select name="<?= $prefix ?>btn_sec_font"><?php foreach ($googleFontsList as $f): ?><option value="<?= $f ?>" <?= $config[$prefix . 'btn_sec_font'] === $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?= $config[$prefix . 'btn_sec_font_weight'] ?></label><input type="range" name="<?= $prefix ?>btn_sec_font_weight" min="100" max="900" step="100" value="<?= $config[$prefix . 'btn_sec_font_weight'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'btn_sec_font_weight'] ?></div></div>
<div class="form-group"><label>Espaciado: <?= $config[$prefix . 'btn_sec_letter_spacing'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_letter_spacing" min="0" max="20" value="<?= $config[$prefix . 'btn_sec_letter_spacing'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_letter_spacing'] ?>px</div></div>
<div class="form-group"><label>Transformación</label><select name="<?= $prefix ?>btn_sec_text_transform"><option value="uppercase" <?= $config[$prefix . 'btn_sec_text_transform'] === 'uppercase' ? 'selected' : '' ?>>MAYÚSCULAS</option><option value="lowercase" <?= $config[$prefix . 'btn_sec_text_transform'] === 'lowercase' ? 'selected' : '' ?>>minúsculas</option><option value="capitalize" <?= $config[$prefix . 'btn_sec_text_transform'] === 'capitalize' ? 'selected' : '' ?>>Capitalizar</option><option value="none" <?= $config[$prefix . 'btn_sec_text_transform'] === 'none' ? 'selected' : '' ?>>Normal</option></select></div>
<div class="form-group"><label>Alineación</label><select name="<?= $prefix ?>btn_sec_text_align"><option value="left" <?= $config[$prefix . 'btn_sec_text_align'] === 'left' ? 'selected' : '' ?>>Izquierda</option><option value="center" <?= $config[$prefix . 'btn_sec_text_align'] === 'center' ? 'selected' : '' ?>>Centro</option><option value="right" <?= $config[$prefix . 'btn_sec_text_align'] === 'right' ? 'selected' : '' ?>>Derecha</option></select></div>
<div class="form-group"><label>Sombra Hover</label><select name="<?= $prefix ?>btn_sec_shadow_hover"><?php foreach ($shadows as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_sec_shadow_hover'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Transform Hover</label><select name="<?= $prefix ?>btn_sec_transform_hover"><?php foreach ($transforms as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_sec_transform_hover'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>btn_sec_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_sec_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Espacio entre botones: <?= $config[$prefix . 'btn_sec_gap'] ?>px</label><input type="range" name="<?= $prefix ?>btn_sec_gap" min="5" max="30" value="<?= $config[$prefix . 'btn_sec_gap'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_sec_gap'] ?>px</div></div>
<div class="form-group"><label>Z-Index: <?= $config[$prefix . 'btn_sec_zindex'] ?></label><input type="range" name="<?= $prefix ?>btn_sec_zindex" min="1" max="100" value="<?= $config[$prefix . 'btn_sec_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'btn_sec_zindex'] ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= $config[$prefix . 'btn_sec_position_x'] ?>%</label><input type="range" name="<?= $prefix ?>btn_sec_position_x" min="0" max="100" value="<?= $config[$prefix . 'btn_sec_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'btn_sec_position_x'] ?>%</div></div>
<div class="form-group"><label>Y: <?= $config[$prefix . 'btn_sec_position_y'] ?>%</label><input type="range" name="<?= $prefix ?>btn_sec_position_y" min="0" max="100" value="<?= $config[$prefix . 'btn_sec_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'btn_sec_position_y'] ?>%</div></div>
</div>
</div>

<div class="section">
<h2>🔘 Botón Inferior</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?= $prefix ?>btn_bottom_text" value="<?= htmlspecialchars($config[$prefix . 'btn_bottom_text']) ?>"></div>
<div class="form-group"><label>Link</label><input type="text" name="<?= $prefix ?>btn_bottom_link" value="<?= htmlspecialchars($config[$prefix . 'btn_bottom_link']) ?>"></div>
<div class="form-group"><label>Tamaño: <?= $config[$prefix . 'btn_bottom_size'] ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_size" min="10" max="40" value="<?= $config[$prefix . 'btn_bottom_size'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_size'] ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?= $prefix ?>btn_bottom_color" value="<?= $config[$prefix . 'btn_bottom_color'] ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?= $prefix ?>btn_bottom_hover" value="<?= $config[$prefix . 'btn_bottom_hover'] ?>"></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?= $prefix ?>btn_bottom_border_color" value="<?= $config[$prefix . 'btn_bottom_border_color'] ?>"></div>
<div class="form-group"><label>Grosor Borde: <?= $config[$prefix . 'btn_bottom_border_width'] ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_border_width" min="0" max="20" value="<?= $config[$prefix . 'btn_bottom_border_width'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_border_width'] ?>px</div></div>
<div class="form-group"><label>Radio Borde: <?= $config[$prefix . 'btn_bottom_border_radius'] ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_border_radius" min="0" max="50" value="<?= $config[$prefix . 'btn_bottom_border_radius'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_border_radius'] ?>px</div></div>
<div class="form-group"><label>Padding V: <?= $config[$prefix . 'btn_bottom_padding_v'] ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_padding_v" min="0" max="50" value="<?= $config[$prefix . 'btn_bottom_padding_v'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_padding_v'] ?>px</div></div>
<div class="form-group"><label>Padding H: <?= $config[$prefix . 'btn_bottom_padding_h'] ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_padding_h" min="0" max="100" value="<?= $config[$prefix . 'btn_bottom_padding_h'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_padding_h'] ?>px</div></div>
<div class="form-group"><label>Fuente</label><select name="<?= $prefix ?>btn_bottom_font"><?php foreach ($googleFontsList as $f): ?><option value="<?= $f ?>" <?= $config[$prefix . 'btn_bottom_font'] === $f ? 'selected' : '' ?>><?= $f ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?= $config[$prefix . 'btn_bottom_font_weight'] ?></label><input type="range" name="<?= $prefix ?>btn_bottom_font_weight" min="100" max="900" step="100" value="<?= $config[$prefix . 'btn_bottom_font_weight'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'btn_bottom_font_weight'] ?></div></div>
<div class="form-group"><label>Espaciado: <?= $config[$prefix . 'btn_bottom_letter_spacing'] ?>px</label><input type="range" name="<?= $prefix ?>btn_bottom_letter_spacing" min="0" max="20" value="<?= $config[$prefix . 'btn_bottom_letter_spacing'] ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_letter_spacing'] ?>px</div></div>
<div class="form-group"><label>Transformación</label><select name="<?= $prefix ?>btn_bottom_text_transform"><option value="uppercase" <?= $config[$prefix . 'btn_bottom_text_transform'] === 'uppercase' ? 'selected' : '' ?>>MAYÚSCULAS</option><option value="lowercase" <?= $config[$prefix . 'btn_bottom_text_transform'] === 'lowercase' ? 'selected' : '' ?>>minúsculas</option><option value="capitalize" <?= $config[$prefix . 'btn_bottom_text_transform'] === 'capitalize' ? 'selected' : '' ?>>Capitalizar</option><option value="none" <?= $config[$prefix . 'btn_bottom_text_transform'] === 'none' ? 'selected' : '' ?>>Normal</option></select></div>
<div class="form-group"><label>Sombra Hover</label><select name="<?= $prefix ?>btn_bottom_shadow_hover"><?php foreach ($shadows as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_bottom_shadow_hover'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Transform Hover</label><select name="<?= $prefix ?>btn_bottom_transform_hover"><?php foreach ($transforms as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_bottom_transform_hover'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animación</label><select name="<?= $prefix ?>btn_bottom_animation"><?php foreach ($animations as $v => $l): ?><option value="<?= $v ?>" <?= $config[$prefix . 'btn_bottom_animation'] === $v ? 'selected' : '' ?>><?= $l ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?= $config[$prefix . 'btn_bottom_zindex'] ?></label><input type="range" name="<?= $prefix ?>btn_bottom_zindex" min="1" max="100" value="<?= $config[$prefix . 'btn_bottom_zindex'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config[$prefix . 'btn_bottom_zindex'] ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?= $config[$prefix . 'btn_bottom_position_x'] ?>%</label><input type="range" name="<?= $prefix ?>btn_bottom_position_x" min="0" max="100" value="<?= $config[$prefix . 'btn_bottom_position_x'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_position_x'] ?>%</div></div>
<div class="form-group"><label>Y: <?= $config[$prefix . 'btn_bottom_position_y'] ?>%</label><input type="range" name="<?= $prefix ?>btn_bottom_position_y" min="0" max="100" value="<?= $config[$prefix . 'btn_bottom_position_y'] ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?= $config[$prefix . 'btn_bottom_position_y'] ?>%</div></div>
</div>
</div>

<button type="submit" class="save-btn">💾 GUARDAR CONFIGURACIÓN DE MENÚ</button>
</form>
</div>

<script>
function toggleBgSections() {
    const type = document.getElementById('bg_type').value;
    document.getElementById('bg_image_section').classList.toggle('hidden', type !== 'image');
    document.getElementById('bg_video_section').classList.toggle('hidden', type !== 'video');
}
</script>
</body>
</html>