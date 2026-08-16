<?php
session_start();
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$imagesDir = $baseDir . '/images';
$videosDir = $baseDir . '/videos';

if (!is_dir($imagesDir)) mkdir($imagesDir, 0777, true);
if (!is_dir($videosDir)) mkdir($videosDir, 0777, true);

// 1. CARGA SEGURA: Preserva TODO lo que ya exista en el JSON
$currentConfig = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];

// Valores por defecto COMPLETOS (se conservan portada/menu para no perder datos al guardar)
$defaults = [
    // PORTADA - TÍTULO
    'portada_title_text' => 'FEELING AUTISTIC', 'portada_title_size' => 60, 'portada_title_color' => '#ffffff',
    'portada_title_font' => 'Arial Black', 'portada_title_font_weight' => 900, 'portada_title_letter_spacing' => 5,
    'portada_title_text_transform' => 'uppercase', 'portada_title_text_align' => 'center',
    'portada_title_white_space' => 'nowrap', 'portada_title_max_width' => 100,
    'portada_title_position_x' => 50, 'portada_title_position_y' => 30, 'portada_title_animation' => 'fadeInDown',
    'portada_title_anim_duration' => 1, 'portada_title_anim_delay' => 0,
    // PORTADA - SUBTÍTULO
    'portada_subtitle_text' => 'INTUITIVE ANALITYC NEURODIVERGENCE CREATIVE PLATFORM', 'portada_subtitle_size' => 14,
    'portada_subtitle_color' => '#a0a0a0', 'portada_subtitle_font' => 'Arial Black', 'portada_subtitle_font_weight' => 400,
    'portada_subtitle_letter_spacing' => 2, 'portada_subtitle_text_transform' => 'uppercase',
    'portada_subtitle_position_x' => 50, 'portada_subtitle_position_y' => 45, 'portada_subtitle_animation' => 'fadeIn',
    'portada_subtitle_anim_duration' => 1, 'portada_subtitle_anim_delay' => 0.3,
    // PORTADA - LOGO/VIDEO
    'portada_logo_type' => 'image', 'portada_logo_path' => 'images/logo-feeling-autistic.png',
    'portada_video_path' => 'videos/logo-portada.mp4', 'portada_logo_size' => 50,
    'portada_logo_border_radius' => 50, 'portada_logo_border_width' => 3, 'portada_logo_border_color' => '#ffffff',
    'portada_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)', 'portada_logo_position_x' => 50, 'portada_logo_position_y' => 15,
    'portada_logo_animation' => 'zoomIn', 'portada_logo_anim_duration' => 1.5, 'portada_logo_anim_delay' => 0,
    // PORTADA - BOTÓN PRINCIPAL
    'portada_btn_main_text' => 'THE DIFFERENCE', 'portada_btn_main_size' => 50, 'portada_btn_main_color' => '#ffffff',
    'portada_btn_main_hover' => '#ff69b4', 'portada_btn_main_border_width' => 3, 'portada_btn_main_border_color' => '#ffffff',
    'portada_btn_main_border_radius' => 0, 'portada_btn_main_padding_v' => 20, 'portada_btn_main_padding_h' => 40,
    'portada_btn_main_letter_spacing' => 5, 'portada_btn_main_font' => 'Arial Black', 'portada_btn_main_font_weight' => 900,
    'portada_btn_main_shadow_hover' => '0 0 30px rgba(255,105,180,0.5)', 'portada_btn_main_transform_hover' => 'scale(1.05)',
    'portada_btn_main_animation' => 'fadeInUp', 'portada_btn_main_anim_duration' => 1, 'portada_btn_main_anim_delay' => 0.5,
    'portada_btn_main_position_x' => 50, 'portada_btn_main_position_y' => 65,
    // PORTADA - BOTÓN SECUNDARIO
    'portada_btn_secondary_text' => 'LE TEMATIK DESIGN', 'portada_btn_secondary_size' => 16,
    'portada_btn_secondary_color' => '#fffc34', 'portada_btn_secondary_hover' => '#ffffff',
    'portada_btn_secondary_border_width' => 2, 'portada_btn_secondary_border_color' => '#ffffff',
    'portada_btn_secondary_border_radius' => 0, 'portada_btn_secondary_padding_v' => 8, 'portada_btn_secondary_padding_h' => 20,
    'portada_btn_secondary_letter_spacing' => 2, 'portada_btn_secondary_font' => 'Arial Black', 'portada_btn_secondary_font_weight' => 900,
    'portada_btn_secondary_text_transform' => 'uppercase', 'portada_btn_secondary_shadow_hover' => '0 0 20px rgba(255,252,52,0.5)',
    'portada_btn_secondary_transform_hover' => 'scale(1.05)', 'portada_btn_secondary_animation' => 'fadeInRight',
    'portada_btn_secondary_anim_duration' => 1, 'portada_btn_secondary_anim_delay' => 1,
    'portada_btn_secondary_position_x' => 85, 'portada_btn_secondary_position_y' => 90,
    // MENÚ - TÍTULO
    'menu_title_text' => 'FEELING AUTISTIC', 'menu_title_size' => 60, 'menu_title_color' => '#ffffff',
    'menu_title_font' => 'Arial Black', 'menu_title_font_weight' => 900, 'menu_title_letter_spacing' => 5,
    'menu_title_text_transform' => 'uppercase', 'menu_title_position_x' => 50, 'menu_title_position_y' => 15,
    'menu_title_animation' => 'fadeInDown', 'menu_title_anim_duration' => 1, 'menu_title_anim_delay' => 0,
    // MENÚ - SUBTÍTULO
    'menu_subtitle_text' => 'NEURODIVERGENCE CREATIVE PHILOSOPHY PLATFORM', 'menu_subtitle_size' => 14,
    'menu_subtitle_color' => '#a0a0a0', 'menu_subtitle_font' => 'Arial Black', 'menu_subtitle_font_weight' => 400,
    'menu_subtitle_letter_spacing' => 2, 'menu_subtitle_text_transform' => 'uppercase',
    'menu_subtitle_position_x' => 50, 'menu_subtitle_position_y' => 28, 'menu_subtitle_animation' => 'fadeIn',
    'menu_subtitle_anim_duration' => 1, 'menu_subtitle_anim_delay' => 0.3,
    // MENÚ - LOGO/VIDEO
    'menu_logo_type' => 'image', 'menu_logo_path' => 'images/logo-feeling-autistic.png',
    'menu_video_path' => 'videos/logo-menu.mp4', 'menu_logo_size' => 40,
    'menu_logo_border_radius' => 50, 'menu_logo_border_width' => 3, 'menu_logo_border_color' => '#ffffff',
    'menu_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)', 'menu_logo_position_x' => 50, 'menu_logo_position_y' => 45,
    'menu_logo_animation' => 'zoomIn', 'menu_logo_anim_duration' => 1.5, 'menu_logo_anim_delay' => 0,
    // MENÚ - BOTÓN PRINCIPAL
    'menu_btn_main_text' => 'THE DIFFERENCE', 'menu_btn_main_size' => 45, 'menu_btn_main_color' => '#ffffff',
    'menu_btn_main_hover' => '#ff69b4', 'menu_btn_main_border_width' => 3, 'menu_btn_main_border_color' => '#ffffff',
    'menu_btn_main_border_radius' => 0, 'menu_btn_main_padding_v' => 18, 'menu_btn_main_padding_h' => 35,
    'menu_btn_main_letter_spacing' => 4, 'menu_btn_main_font' => 'Arial Black', 'menu_btn_main_font_weight' => 900,
    'menu_btn_main_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)', 'menu_btn_main_transform_hover' => 'scale(1.05)',
    'menu_btn_main_animation' => 'fadeInUp', 'menu_btn_main_anim_duration' => 1, 'menu_btn_main_anim_delay' => 0.3,
    'menu_btn_main_position_x' => 50, 'menu_btn_main_position_y' => 60,
    // MENÚ - BOTÓN INFERIOR
    'menu_btn_bottom_text' => 'LE TEMATIK DESIGN', 'menu_btn_bottom_size' => 14,
    'menu_btn_bottom_color' => '#ff69b4', 'menu_btn_bottom_hover' => '#ffffff',
    'menu_btn_bottom_border_width' => 2, 'menu_btn_bottom_border_color' => '#ff69b4',
    'menu_btn_bottom_border_radius' => 0, 'menu_btn_bottom_padding_v' => 8, 'menu_btn_bottom_padding_h' => 20,
    'menu_btn_bottom_letter_spacing' => 2, 'menu_btn_bottom_font' => 'Arial Black', 'menu_btn_bottom_font_weight' => 900,
    'menu_btn_bottom_text_transform' => 'uppercase', 'menu_btn_bottom_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)',
    'menu_btn_bottom_transform_hover' => 'scale(1.05)', 'menu_btn_bottom_animation' => 'fadeInUp',
    'menu_btn_bottom_anim_duration' => 1, 'menu_btn_bottom_anim_delay' => 1,
    'menu_btn_bottom_position_x' => 50, 'menu_btn_bottom_position_y' => 95,
    // FONDO Y FUENTES
    'bg_color' => '#000000', 'bg_type' => 'solid',
    'bg_gradient_start' => '#000000', 'bg_gradient_end' => '#1a1a2e', 'bg_gradient_angle' => 135,
    'bg_video_path' => '', 'bg_video_overlay' => 0.3,
    'bg_image_path' => '', 'bg_image_overlay' => 0.3,
    'google_fonts' => 'Arial Black,Roboto,Playfair Display,Orbitron,Space Mono'
];

$config = array_merge($defaults, $currentConfig);
$mensaje = '';

// 2. GUARDADO SEGURO
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if ($key !== 'active_tab') {
            $config[$key] = is_numeric($value) ? (strpos((string)$value, '.') !== false ? (float)$value : (int)$value) : (string)$value;
        }
    }

    // 3. PROCESAR SUBIDA DE ARCHIVOS (solo fondo ahora)
    $uploads = [
        'bg_image' => ['dir' => $imagesDir, 'key' => 'bg_image_path', 'type_key' => 'bg_type', 'type_val' => 'image'],
        'bg_video' => ['dir' => $videosDir, 'key' => 'bg_video_path', 'type_key' => 'bg_type', 'type_val' => 'video']
    ];

    foreach ($uploads as $inputName => $settings) {
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $fileName = $inputName . '-' . time() . '.' . $ext;
            $targetPath = $settings['dir'] . '/' . $fileName;
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetPath)) {
                $relativePath = str_replace($baseDir . '/', '', $targetPath);
                $config[$settings['key']] = $relativePath;
                if (isset($settings['type_key'])) $config[$settings['type_key']] = $settings['type_val'];
                $mensaje .= "✅ Archivo subido. ";
            }
        }
    }

    if (file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE))) {
        $mensaje .= "✅ Configuración guardada y protegida.";
    } else {
        $mensaje = "❌ Error al guardar el archivo.";
    }
}

$googleFontsList = ['Arial Black','Roboto','Playfair Display','Orbitron','Space Mono','Montserrat','Oswald','Raleway','Poppins','Bebas Neue'];
$animations = ['none'=>'Sin animación','fadeIn'=>'Fade In','fadeInUp'=>'Fade In Up','fadeInDown'=>'Fade In Down','zoomIn'=>'Zoom In','bounceIn'=>'Bounce In'];
$shadows = ['none'=>'Sin sombra','0 4px 6px rgba(0,0,0,0.3)'=>'Sombra suave','0 10px 20px rgba(0,0,0,0.5)'=>'Sombra media','0 0 20px rgba(255,105,180,0.5)'=>'Glow rosa','0 0 30px rgba(255,252,52,0.5)'=>'Glow amarillo'];
$transforms = ['scale(1.05)'=>'Escalar 1.05x','scale(1.1)'=>'Escalar 1.1x','scale(1.2)'=>'Escalar 1.2x','translateY(-5px)'=>'Subir 5px','none'=>'Sin transformación'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Configuración - The Difference</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: system-ui, sans-serif; background: #0f0f0f; color: #fff; min-height: 100vh; padding: 2rem; }
.container { max-width: 1000px; margin: 0 auto; background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 2rem; }
h1 { font-size: 2rem; margin-bottom: 0.5rem; color: #ff69b4; }
.subtitle { color: #a0a0a0; margin-bottom: 2rem; }
.alert { padding: 1rem; border-radius: 6px; margin-bottom: 1.5rem; background: rgba(16,185,129,0.2); border: 1px solid #10b981; color: #10b981; }
.nav-buttons { display: flex; gap: 1rem; margin-bottom: 2rem; flex-wrap: wrap; }
.nav-btn { padding: 0.75rem 1.5rem; background: #252525; color: #fff; text-decoration: none; border-radius: 6px; border: 1px solid #333; transition: all 0.2s; display: flex; align-items: center; gap: 0.5rem; }
.nav-btn:hover { background: #333; border-color: #ff69b4; transform: translateY(-2px); }
.tabs { display: flex; gap: 0.5rem; margin-bottom: 2rem; border-bottom: 2px solid #333; flex-wrap: wrap; }
.tab { padding: 0.75rem 1.5rem; background: transparent; border: none; color: #a0a0a0; cursor: pointer; font-size: 0.9rem; transition: all 0.2s; }
.tab:hover { color: #fff; }
.tab.active { color: #ff69b4; border-bottom: 2px solid #ff69b4; margin-bottom: -2px; }
.tab-content { display: none; }
.tab-content.active { display: block; }
.section { background: #252525; border: 1px solid #333; border-radius: 8px; padding: 1.5rem; margin-bottom: 1.5rem; }
.section h2 { font-size: 1.2rem; margin-bottom: 1rem; color: #ff69b4; }
.form-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem; }
.form-group { margin-bottom: 1rem; }
.form-group label { display: block; margin-bottom: 0.5rem; color: #a0a0a0; font-size: 0.85rem; }
.form-group input[type="range"] { width: 100%; accent-color: #ff69b4; }
.form-group input[type="color"] { width: 100%; height: 40px; border: 1px solid #333; border-radius: 4px; background: #1a1a1a; }
.form-group input[type="text"], .form-group select, .form-group input[type="file"] { width: 100%; padding: 0.5rem; background: #1a1a1a; border: 1px solid #333; border-radius: 4px; color: #fff; }
.value-display { color: #ff69b4; font-family: monospace; font-weight: bold; margin-top: 0.25rem; font-size: 0.9rem; }
.save-btn-container { display: flex; justify-content: flex-end; margin-top: 2rem; padding-top: 1.5rem; border-top: 2px solid #333; }
.btn-save { padding: 0.6rem 1.2rem; background: transparent; color: #10b981; border: 2px solid #10b981; border-radius: 6px; font-size: 0.9rem; cursor: pointer; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s; }
.btn-save:hover { background: #10b981; color: #fff; transform: translateY(-2px); }
.preview-media { margin-top: 10px; max-width: 200px; border-radius: 4px; border: 1px solid #333; }
</style>
</head>
<body>
<div class="container">
<h1>🎛️ Configuración</h1>
<p class="subtitle">The Difference - Fondo y Fuentes (Portada y Menú en sus paneles)</p>
<?php if ($mensaje): ?><div class="alert"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

<!-- NAVEGACIÓN: enlaces a los paneles especializados -->
<div class="nav-buttons">
<a href="panel-portada.php" class="nav-btn"><span>🏠</span><span>Configurar Portada</span></a>
<a href="panel-menu.php" class="nav-btn"><span>📋</span><span>Configurar Menú</span></a>
<a href="../portada.php" target="_blank" class="nav-btn"><span>👁️</span><span>Ver Preview Portada</span></a>
<a href="../menu.php" target="_blank" class="nav-btn"><span>👁️</span><span>Ver Preview Menú</span></a>
</div>

<div class="tabs">
<button class="tab active" onclick="showTab('fondo')">🎨 Fondo</button>
<button class="tab" onclick="showTab('fuentes')">🔤 Fuentes</button>
</div>
<form method="POST" enctype="multipart/form-data">

<!-- ================= FONDO ================= -->
<div id="fondo" class="tab-content active">
    <div class="section">
        <h2>🎨 Fondo - Tipo de Medio</h2>
        <div class="form-grid">
            <div class="form-group">
                <label>Tipo de Fondo</label>
                <select name="bg_type" id="bg_type">
                    <option value="solid" <?= $config['bg_type'] === 'solid' ? 'selected' : '' ?>>Color Sólido</option>
                    <option value="gradient" <?= $config['bg_type'] === 'gradient' ? 'selected' : '' ?>>Gradiente</option>
                    <option value="image" <?= $config['bg_type'] === 'image' ? 'selected' : '' ?>>🖼️ Imagen Pantalla Completa</option>
                    <option value="video" <?= $config['bg_type'] === 'video' ? 'selected' : '' ?>>🎬 Video Pantalla Completa</option>
                </select>
            </div>
            <div class="form-group"><label>Color de respaldo</label><input type="color" name="bg_color" value="<?= $config['bg_color'] ?>"></div>
        </div>
    </div>
    <div class="section" id="gradient_section" style="<?= $config['bg_type'] === 'gradient' ? '' : 'display:none' ?>">
        <h2>⚙️ Configuración de Gradiente</h2>
        <div class="form-grid">
            <div class="form-group"><label>Color inicio</label><input type="color" name="bg_gradient_start" value="<?= $config['bg_gradient_start'] ?>"></div>
            <div class="form-group"><label>Color fin</label><input type="color" name="bg_gradient_end" value="<?= $config['bg_gradient_end'] ?>"></div>
            <div class="form-group"><label>Ángulo: <?= $config['bg_gradient_angle'] ?>°</label><input type="range" name="bg_gradient_angle" min="0" max="360" value="<?= $config['bg_gradient_angle'] ?>" oninput="this.nextElementSibling.textContent = this.value + '°'"><div class="value-display"><?= $config['bg_gradient_angle'] ?>°</div></div>
        </div>
    </div>
    <div class="section" id="image_section" style="<?= $config['bg_type'] === 'image' ? '' : 'display:none' ?>">
        <h2>🖼️ Imagen de Fondo</h2>
        <div class="form-grid">
            <div class="form-group">
                <label>Subir Imagen (JPG/PNG)</label>
                <input type="file" name="bg_image" accept="image/*">
                <?php if (!empty($config['bg_image_path']) && file_exists($baseDir . '/' . $config['bg_image_path'])): ?>
                <div style="margin-top: 10px;"><img src="<?= $config['bg_image_path'] ?>" class="preview-media" alt="Fondo actual"></div>
                <?php endif; ?>
            </div>
            <div class="form-group"><label>Opacidad del Overlay Oscuro: <?= $config['bg_image_overlay'] ?></label><input type="range" name="bg_image_overlay" min="0" max="1" step="0.1" value="<?= $config['bg_image_overlay'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config['bg_image_overlay'] ?></div></div>
        </div>
    </div>
    <div class="section" id="video_section" style="<?= $config['bg_type'] === 'video' ? '' : 'display:none' ?>">
        <h2>🎬 Video de Fondo (Pantalla Completa)</h2>
        <div class="form-grid">
            <div class="form-group">
                <label>Subir Video (MP4)</label>
                <input type="file" name="bg_video" accept="video/mp4,video/webm">
                <?php if (!empty($config['bg_video_path']) && file_exists($baseDir . '/' . $config['bg_video_path'])): ?>
                <div style="margin-top: 10px;"><video src="<?= $config['bg_video_path'] ?>" class="preview-media" controls></video></div>
                <?php endif; ?>
            </div>
            <div class="form-group"><label>Opacidad del Overlay Oscuro: <?= $config['bg_video_overlay'] ?></label><input type="range" name="bg_video_overlay" min="0" max="1" step="0.1" value="<?= $config['bg_video_overlay'] ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?= $config['bg_video_overlay'] ?></div></div>
        </div>
    </div>
</div>

<!-- ================= FUENTES ================= -->
<div id="fuentes" class="tab-content">
    <div class="section">
        <h2>🔤 Fuentes Globales</h2>
        <div class="form-group">
            <label>Fuentes disponibles (separadas por coma)</label>
            <input type="text" name="google_fonts" value="<?= $config['google_fonts'] ?>">
            <p style="color: #a0a0a0; font-size: 0.8rem; margin-top: 0.5rem;">Ej: Arial Black, Roboto, Playfair Display</p>
        </div>
    </div>
</div>

<div class="save-btn-container">
    <button type="submit" class="btn-save"><span class="icon">💾</span><span>Guardar Configuración</span></button>
</div>
</form>

<div class="debug" style="margin-top: 1rem; padding: 1rem; background: #0a0a0a; border: 1px solid #333; border-radius: 6px; font-family: monospace; font-size: 0.8rem; color: #60a5fa;">
    <strong>🔍 Info:</strong><br>
    Archivo: <?= htmlspecialchars($configFile) ?><br>
    Existe: <?= file_exists($configFile) ? '✅ SÍ' : '❌ NO' ?><br>
    Tamaño: <?= file_exists($configFile) ? filesize($configFile) . ' bytes' : 'N/A' ?>
</div>
</div>

<script>
function showTab(tabName) {
    document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
    document.querySelectorAll('.tab').forEach(t => t.classList.remove('active'));
    document.getElementById(tabName).classList.add('active');
    event.target.classList.add('active');
}
document.getElementById('bg_type')?.addEventListener('change', function() {
    document.getElementById('gradient_section').style.display = this.value === 'gradient' ? '' : 'none';
    document.getElementById('image_section').style.display = this.value === 'image' ? '' : 'none';
    document.getElementById('video_section').style.display = this.value === 'video' ? '' : 'none';
});
</script>
</body>
</html>