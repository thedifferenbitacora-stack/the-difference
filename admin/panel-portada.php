<?php
session_start();
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$imagesDir = $baseDir . '/images';
$videosDir = $baseDir . '/videos';
if (!is_dir($imagesDir)) mkdir($imagesDir, 0777, true);
if (!is_dir($videosDir)) mkdir($videosDir, 0777, true);

$prefix = 'portada_';
$mensaje = '';

function loadConfig($f) {
    if (file_exists($f)) {
        $data = json_decode(file_get_contents($f), true);
        if ($data) {
            return $data;
        }
    }
    return array();
}

function saveConfig($f, $c) {
    return file_put_contents($f, json_encode($c, JSON_PRETTY_PRINT));
}

function processFileUpload($file, $type, $page, $imagesDir, $videosDir) {
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) return false;
    $dir = ($type === 'image') ? $imagesDir : $videosDir;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = ($type === 'image') ? "logo-" . $page . "." . $ext : "video-" . $page . ".mp4";
    $targetPath = $dir . '/' . $fileName;
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ($type === 'image') ? "images/" . $fileName : "videos/" . $fileName;
    }
    return false;
}

$config = loadConfig($configFile);

$defaults = array(
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
    $prefix . 'title_white_space' => 'nowrap',
    $prefix . 'title_max_width' => 100,
    $prefix . 'title_line_height' => 1.2,
    $prefix . 'title_position_x' => 50,
    $prefix . 'title_position_y' => 30,
    $prefix . 'title_animation' => 'fadeInDown',
    $prefix . 'title_zindex' => 10,
    $prefix . 'subtitle_text' => 'INTUITIVE ANALITYC NEURODIVERGENCE CREATIVE PLATFORM',
    $prefix . 'subtitle_size' => 14,
    $prefix . 'subtitle_color' => '#a0a0a0',
    $prefix . 'subtitle_font' => 'Arial Black',
    $prefix . 'subtitle_font_weight' => 400,
    $prefix . 'subtitle_letter_spacing' => 2,
    $prefix . 'subtitle_text_transform' => 'uppercase',
    $prefix . 'subtitle_text_align' => 'center',
    $prefix . 'subtitle_white_space' => 'nowrap',
    $prefix . 'subtitle_max_width' => 100,
    $prefix . 'subtitle_line_height' => 1.4,
    $prefix . 'subtitle_position_x' => 50,
    $prefix . 'subtitle_position_y' => 45,
    $prefix . 'subtitle_zindex' => 10,
    $prefix . 'subsubtitle_text' => 'SYSTEM BITACORA TEXVN',
    $prefix . 'subsubtitle_size' => 11,
    $prefix . 'subsubtitle_color' => '#666666',
    $prefix . 'subsubtitle_font' => 'Arial Black',
    $prefix . 'subsubtitle_font_weight' => 400,
    $prefix . 'subsubtitle_letter_spacing' => 2,
    $prefix . 'subsubtitle_text_transform' => 'uppercase',
    $prefix . 'subsubtitle_text_align' => 'center',
    $prefix . 'subsubtitle_white_space' => 'nowrap',
    $prefix . 'subsubtitle_max_width' => 100,
    $prefix . 'subsubtitle_line_height' => 1.4,
    $prefix . 'subsubtitle_position_x' => 50,
    $prefix . 'subsubtitle_position_y' => 50,
    $prefix . 'subsubtitle_zindex' => 10,
    $prefix . 'logo_type' => 'image',
    $prefix . 'logo_path' => 'images/logo-feeling-autistic.png',
    $prefix . 'logo_size' => 50,
    $prefix . 'logo_border_radius' => 50,
    $prefix . 'logo_border_width' => 3,
    $prefix . 'logo_border_color' => '#ffffff',
    $prefix . 'logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
    $prefix . 'logo_position_x' => 50,
    $prefix . 'logo_position_y' => 15,
    $prefix . 'logo_zindex' => 10,
    $prefix . 'logo_animation' => 'zoomIn',
    $prefix . 'eye_type' => 'image',
    $prefix . 'eye_path' => 'images/eye-bg.png',
    $prefix . 'eye_size' => 60,
    $prefix . 'eye_border_radius' => 50,
    $prefix . 'eye_object_fit' => 'contain',
    $prefix . 'eye_shadow' => '0 0 40px rgba(255,105,180,0.6)',
    $prefix . 'eye_position_x' => 50,
    $prefix . 'eye_position_y' => 15,
    $prefix . 'eye_zindex' => 9,
    $prefix . 'eye_animation' => 'zoomIn',
    $prefix . 'btn_main_text' => 'THE DIFFERENCE',
    $prefix . 'btn_main_link' => 'menu.php',
    $prefix . 'btn_main_size' => 50,
    $prefix . 'btn_main_color' => '#ffffff',
    $prefix . 'btn_main_hover' => '#ff69b4',
    $prefix . 'btn_main_border_width' => 3,
    $prefix . 'btn_main_border_color' => '#ffffff',
    $prefix . 'btn_main_border_radius' => 0,
    $prefix . 'btn_main_padding_v' => 20,
    $prefix . 'btn_main_padding_h' => 40,
    $prefix . 'btn_main_letter_spacing' => 5,
    $prefix . 'btn_main_font' => 'Arial Black',
    $prefix . 'btn_main_font_weight' => 900,
    $prefix . 'btn_main_text_transform' => 'uppercase',
    $prefix . 'btn_main_text_align' => 'center',
    $prefix . 'btn_main_white_space' => 'nowrap',
    $prefix . 'btn_main_max_width' => 90,
    $prefix . 'btn_main_shadow_hover' => '0 0 30px rgba(255,105,180,0.5)',
    $prefix . 'btn_main_transform_hover' => 'scale(1.05)',
    $prefix . 'btn_main_animation' => 'fadeInUp',
    $prefix . 'btn_main_position_x' => 50,
    $prefix . 'btn_main_position_y' => 65,
    $prefix . 'btn_main_zindex' => 10,
    $prefix . 'btn_sec_text' => 'LE TEMATIK DESIGN',
    $prefix . 'btn_sec_link' => 'le-tematik.php',
    $prefix . 'btn_sec_size' => 16,
    $prefix . 'btn_sec_color' => '#fffc34',
    $prefix . 'btn_sec_hover' => '#ffffff',
    $prefix . 'btn_sec_border_width' => 2,
    $prefix . 'btn_sec_border_color' => '#ffffff',
    $prefix . 'btn_sec_border_radius' => 0,
    $prefix . 'btn_sec_padding_v' => 8,
    $prefix . 'btn_sec_padding_h' => 20,
    $prefix . 'btn_sec_letter_spacing' => 2,
    $prefix . 'btn_sec_font' => 'Arial Black',
    $prefix . 'btn_sec_font_weight' => 900,
    $prefix . 'btn_sec_text_transform' => 'uppercase',
    $prefix . 'btn_sec_text_align' => 'center',
    $prefix . 'btn_sec_white_space' => 'nowrap',
    $prefix . 'btn_sec_max_width' => 90,
    $prefix . 'btn_sec_shadow_hover' => '0 0 20px rgba(255,252,52,0.5)',
    $prefix . 'btn_sec_transform_hover' => 'scale(1.05)',
    $prefix . 'btn_sec_animation' => 'fadeInRight',
    $prefix . 'btn_sec_position_x' => 85,
    $prefix . 'btn_sec_position_y' => 90,
    $prefix . 'btn_sec_zindex' => 10,
    $prefix . 'page_scroll_enabled' => true
);

$config = array_merge($defaults, $config);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $key => $value) {
        if (strpos($key, $prefix) === 0) {
            if ($key === $prefix . 'page_scroll_enabled') {
                $config[$key] = ($value === '1' || $value === 'on');
            } else {
                $config[$key] = is_numeric($value) ? (strpos($value, '.') !== false ? (float)$value : (int)$value) : $value;
            }
        }
    }
    
    if (isset($_FILES[$prefix . 'logo'])) {
        $result = processFileUpload($_FILES[$prefix . 'logo'], 'image', 'portada', $imagesDir, $videosDir);
        if ($result) {
            $config[$prefix . 'logo_path'] = $result;
            $mensaje .= "Logo subido. ";
        }
    }
    if (isset($_FILES[$prefix . 'video'])) {
        $result = processFileUpload($_FILES[$prefix . 'video'], 'video', 'portada', $imagesDir, $videosDir);
        if ($result) {
            $config[$prefix . 'video_path'] = $result;
            $config[$prefix . 'logo_type'] = 'video';
            $mensaje .= "Video logo subido. ";
        }
    }
    if (isset($_FILES[$prefix . 'eye'])) {
        $result = processFileUpload($_FILES[$prefix . 'eye'], 'image', 'eye-portada', $imagesDir, $videosDir);
        if ($result) {
            $config[$prefix . 'eye_path'] = $result;
            $mensaje .= "Ojo subido. ";
        }
    }
    if (isset($_FILES[$prefix . 'bg_image'])) {
        $result = processFileUpload($_FILES[$prefix . 'bg_image'], 'image', 'bg-portada', $imagesDir, $videosDir);
        if ($result) {
            $config[$prefix . 'bg_image_path'] = $result;
            $config[$prefix . 'bg_type'] = 'image';
            $mensaje .= "Fondo subido. ";
        }
    }
    if (isset($_FILES[$prefix . 'bg_video'])) {
        $result = processFileUpload($_FILES[$prefix . 'bg_video'], 'video', 'bg-portada', $imagesDir, $videosDir);
        if ($result) {
            $config[$prefix . 'bg_video_path'] = $result;
            $config[$prefix . 'bg_type'] = 'video';
            $mensaje .= "Video fondo subido. ";
        }
    }
    
    if (saveConfig($configFile, $config)) {
        $mensaje .= "Configuracion guardada.";
        $config = loadConfig($configFile);
        $config = array_merge($defaults, $config);
    }
}

$googleFontsList = array(
    'Arial Black', 'Roboto', 'Playfair Display', 'Orbitron', 'Space Mono',
    'Montserrat', 'Oswald', 'Raleway', 'Poppins', 'Bebas Neue',
    'Syne', 'Inter', 'Nunito', 'Quicksand', 'Lora',
    'Merriweather', 'Cormorant Garamond', 'Cinzel', 'DM Sans', 'Plus Jakarta Sans',
    'Work Sans', 'Outfit', 'Manrope', 'Lexend', 'Sora'
);
$animations = array('none'=>'Sin animacion','fadeIn'=>'Fade In','fadeInUp'=>'Fade In Up','fadeInDown'=>'Fade In Down','zoomIn'=>'Zoom In','bounceIn'=>'Bounce In');
$shadows = array('none'=>'Sin sombra','0 4px 6px rgba(0,0,0,0.3)'=>'Sombra suave','0 10px 20px rgba(0,0,0,0.5)'=>'Sombra media','0 0 20px rgba(255,105,180,0.5)'=>'Glow rosa','0 0 30px rgba(255,105,180,0.6)'=>'Glow rosa fuerte','0 0 30px rgba(255,252,52,0.5)'=>'Glow amarillo','0 0 40px rgba(255,252,52,0.6)'=>'Glow amarillo fuerte','0 0 30px rgba(102,126,234,0.5)'=>'Glow azul');
$transforms = array('scale(1.05)'=>'Escalar 1.05x','scale(1.1)'=>'Escalar 1.1x','scale(1.2)'=>'Escalar 1.2x','translateY(-5px)'=>'Subir 5px','translateY(-10px)'=>'Subir 10px','none'=>'Sin transformacion');
$objectFits = array('contain'=>'Contain (ajustar)','cover'=>'Cover (cortar)','fill'=>'Fill (estirar)','none'=>'None (original)');
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Panel Portada</title>
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
</style>
</head>
<body>
<div class="container">
<h1>Panel de Portada</h1>
<p class="subtitle">Configuracion completa de portada.php</p>
<?php if ($mensaje): ?><div class="alert"><?php echo htmlspecialchars($mensaje); ?></div><?php endif; ?>
<div class="nav-links">
<a href="panel-portada.php" class="active">Portada</a>
<a href="panel-menu.php">Menu</a>
<a href="../portada.php" target="_blank">Ver Portada</a>
</div>
<form method="POST" enctype="multipart/form-data">

<div class="section">
<h2>Fondo de Pantalla</h2>
<div class="form-grid">
<div class="form-group"><label>Tipo de Fondo</label><select name="<?php echo $prefix; ?>bg_type" id="bg_type" onchange="toggleBgSections()"><option value="solid" <?php echo $config[$prefix . 'bg_type'] === 'solid' ? 'selected' : ''; ?>>Color Solido</option><option value="image" <?php echo $config[$prefix . 'bg_type'] === 'image' ? 'selected' : ''; ?>>Imagen</option><option value="video" <?php echo $config[$prefix . 'bg_type'] === 'video' ? 'selected' : ''; ?>>Video</option></select></div>
<div class="form-group"><label>Color de Fondo</label><input type="color" name="<?php echo $prefix; ?>bg_color" value="<?php echo $config[$prefix . 'bg_color']; ?>"></div>
</div>
<div id="bg_image_section" class="<?php echo $config[$prefix . 'bg_type'] !== 'image' ? 'hidden' : ''; ?>">
<div class="form-grid"><div class="form-group"><label>Subir Imagen de Fondo</label><input type="file" name="<?php echo $prefix; ?>bg_image" accept="image/*"><?php if (!empty($config[$prefix . 'bg_image_path']) && file_exists($baseDir . '/' . $config[$prefix . 'bg_image_path'])): ?><div style="margin-top:10px"><img src="<?php echo $config[$prefix . 'bg_image_path']; ?>" style="max-width:200px;border-radius:4px"></div><?php endif; ?></div></div>
</div>
<div id="bg_video_section" class="<?php echo $config[$prefix . 'bg_type'] !== 'video' ? 'hidden' : ''; ?>">
<div class="form-grid"><div class="form-group"><label>Subir Video de Fondo (MP4)</label><input type="file" name="<?php echo $prefix; ?>bg_video" accept="video/*"><?php if (!empty($config[$prefix . 'bg_video_path']) && file_exists($baseDir . '/' . $config[$prefix . 'bg_video_path'])): ?><div style="margin-top:10px"><video src="<?php echo $config[$prefix . 'bg_video_path']; ?>" style="max-width:200px;border-radius:4px" controls></video></div><?php endif; ?></div></div>
</div>
<div class="form-grid" style="margin-top:1rem">
<div class="form-group"><label>Color del Overlay</label><input type="color" name="<?php echo $prefix; ?>bg_overlay_color" value="<?php echo $config[$prefix . 'bg_overlay_color']; ?>"></div>
<div class="form-group"><label>Opacidad del Overlay: <?php echo $config[$prefix . 'bg_overlay_opacity']; ?></label><input type="range" name="<?php echo $prefix; ?>bg_overlay_opacity" min="0" max="1" step="0.1" value="<?php echo $config[$prefix . 'bg_overlay_opacity']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'bg_overlay_opacity']; ?></div></div>
</div>
</div>

<div class="section">
<h2>Titulo</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?php echo $prefix; ?>title_text" value="<?php echo htmlspecialchars($config[$prefix . 'title_text']); ?>"></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'title_size']; ?>px</label><input type="range" name="<?php echo $prefix; ?>title_size" min="20" max="200" value="<?php echo $config[$prefix . 'title_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'title_size']; ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?php echo $prefix; ?>title_color" value="<?php echo $config[$prefix . 'title_color']; ?>"></div>
<div class="form-group"><label>Fuente</label><select name="<?php echo $prefix; ?>title_font"><?php foreach ($googleFontsList as $f): ?><option value="<?php echo $f; ?>" <?php echo $config[$prefix . 'title_font'] === $f ? 'selected' : ''; ?>><?php echo $f; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?php echo $config[$prefix . 'title_font_weight']; ?></label><input type="range" name="<?php echo $prefix; ?>title_font_weight" min="100" max="900" step="100" value="<?php echo $config[$prefix . 'title_font_weight']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'title_font_weight']; ?></div></div>
<div class="form-group"><label>Espaciado: <?php echo $config[$prefix . 'title_letter_spacing']; ?>px</label><input type="range" name="<?php echo $prefix; ?>title_letter_spacing" min="0" max="30" value="<?php echo $config[$prefix . 'title_letter_spacing']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'title_letter_spacing']; ?>px</div></div>
<div class="form-group"><label>Transformacion</label><select name="<?php echo $prefix; ?>title_text_transform"><option value="uppercase" <?php echo $config[$prefix . 'title_text_transform'] === 'uppercase' ? 'selected' : ''; ?>>MAYUSCULAS</option><option value="lowercase" <?php echo $config[$prefix . 'title_text_transform'] === 'lowercase' ? 'selected' : ''; ?>>minusculas</option><option value="capitalize" <?php echo $config[$prefix . 'title_text_transform'] === 'capitalize' ? 'selected' : ''; ?>>Capitalizar</option><option value="none" <?php echo $config[$prefix . 'title_text_transform'] === 'none' ? 'selected' : ''; ?>>Normal</option></select></div>
<div class="form-group"><label>Alineacion</label><select name="<?php echo $prefix; ?>title_text_align"><option value="left" <?php echo $config[$prefix . 'title_text_align'] === 'left' ? 'selected' : ''; ?>>Izquierda</option><option value="center" <?php echo $config[$prefix . 'title_text_align'] === 'center' ? 'selected' : ''; ?>>Centro</option><option value="right" <?php echo $config[$prefix . 'title_text_align'] === 'right' ? 'selected' : ''; ?>>Derecha</option></select></div>
<div class="form-group"><label>Salto de linea</label><select name="<?php echo $prefix; ?>title_white_space"><option value="nowrap" <?php echo $config[$prefix . 'title_white_space'] === 'nowrap' ? 'selected' : ''; ?>>Una linea</option><option value="normal" <?php echo $config[$prefix . 'title_white_space'] === 'normal' ? 'selected' : ''; ?>>Multilinea</option></select></div>
<div class="form-group"><label>Ancho max: <?php echo $config[$prefix . 'title_max_width']; ?>%</label><input type="range" name="<?php echo $prefix; ?>title_max_width" min="20" max="100" value="<?php echo $config[$prefix . 'title_max_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'title_max_width']; ?>%</div></div>
<div class="form-group"><label>Espaciado entre lineas: <?php echo $config[$prefix . 'title_line_height']; ?></label><input type="range" name="<?php echo $prefix; ?>title_line_height" min="0.8" max="2" step="0.1" value="<?php echo $config[$prefix . 'title_line_height']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'title_line_height']; ?></div></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>title_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'title_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'title_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>title_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'title_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'title_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'title_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>title_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'title_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'title_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'title_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>title_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'title_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'title_position_y']; ?>%</div></div>
</div>
</div>

<div class="section">
<h2>Subtitulo</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?php echo $prefix; ?>subtitle_text" value="<?php echo htmlspecialchars($config[$prefix . 'subtitle_text']); ?>"></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'subtitle_size']; ?>px</label><input type="range" name="<?php echo $prefix; ?>subtitle_size" min="8" max="40" value="<?php echo $config[$prefix . 'subtitle_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'subtitle_size']; ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?php echo $prefix; ?>subtitle_color" value="<?php echo $config[$prefix . 'subtitle_color']; ?>"></div>
<div class="form-group"><label>Fuente</label><select name="<?php echo $prefix; ?>subtitle_font"><?php foreach ($googleFontsList as $f): ?><option value="<?php echo $f; ?>" <?php echo $config[$prefix . 'subtitle_font'] === $f ? 'selected' : ''; ?>><?php echo $f; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?php echo $config[$prefix . 'subtitle_font_weight']; ?></label><input type="range" name="<?php echo $prefix; ?>subtitle_font_weight" min="100" max="900" step="100" value="<?php echo $config[$prefix . 'subtitle_font_weight']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'subtitle_font_weight']; ?></div></div>
<div class="form-group"><label>Espaciado: <?php echo $config[$prefix . 'subtitle_letter_spacing']; ?>px</label><input type="range" name="<?php echo $prefix; ?>subtitle_letter_spacing" min="0" max="20" value="<?php echo $config[$prefix . 'subtitle_letter_spacing']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'subtitle_letter_spacing']; ?>px</div></div>
<div class="form-group"><label>Transformacion</label><select name="<?php echo $prefix; ?>subtitle_text_transform"><option value="uppercase" <?php echo $config[$prefix . 'subtitle_text_transform'] === 'uppercase' ? 'selected' : ''; ?>>MAYUSCULAS</option><option value="lowercase" <?php echo $config[$prefix . 'subtitle_text_transform'] === 'lowercase' ? 'selected' : ''; ?>>minusculas</option><option value="capitalize" <?php echo $config[$prefix . 'subtitle_text_transform'] === 'capitalize' ? 'selected' : ''; ?>>Capitalizar</option><option value="none" <?php echo $config[$prefix . 'subtitle_text_transform'] === 'none' ? 'selected' : ''; ?>>Normal</option></select></div>
<div class="form-group"><label>Alineacion</label><select name="<?php echo $prefix; ?>subtitle_text_align"><option value="left" <?php echo $config[$prefix . 'subtitle_text_align'] === 'left' ? 'selected' : ''; ?>>Izquierda</option><option value="center" <?php echo $config[$prefix . 'subtitle_text_align'] === 'center' ? 'selected' : ''; ?>>Centro</option><option value="right" <?php echo $config[$prefix . 'subtitle_text_align'] === 'right' ? 'selected' : ''; ?>>Derecha</option></select></div>
<div class="form-group"><label>Salto de linea</label><select name="<?php echo $prefix; ?>subtitle_white_space"><option value="nowrap" <?php echo $config[$prefix . 'subtitle_white_space'] === 'nowrap' ? 'selected' : ''; ?>>Una linea</option><option value="normal" <?php echo $config[$prefix . 'subtitle_white_space'] === 'normal' ? 'selected' : ''; ?>>Multilinea</option></select></div>
<div class="form-group"><label>Ancho max: <?php echo $config[$prefix . 'subtitle_max_width']; ?>%</label><input type="range" name="<?php echo $prefix; ?>subtitle_max_width" min="20" max="100" value="<?php echo $config[$prefix . 'subtitle_max_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'subtitle_max_width']; ?>%</div></div>
<div class="form-group"><label>Espaciado entre lineas: <?php echo $config[$prefix . 'subtitle_line_height']; ?></label><input type="range" name="<?php echo $prefix; ?>subtitle_line_height" min="0.8" max="2" step="0.1" value="<?php echo $config[$prefix . 'subtitle_line_height']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'subtitle_line_height']; ?></div></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>subtitle_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'subtitle_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'subtitle_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>subtitle_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'subtitle_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'subtitle_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'subtitle_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>subtitle_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'subtitle_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'subtitle_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'subtitle_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>subtitle_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'subtitle_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'subtitle_position_y']; ?>%</div></div>
</div>
</div>

<div class="section" style="border: 1px solid #ff69b4;">
<h2>Sub-Subtitulo</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?php echo $prefix; ?>subsubtitle_text" value="<?php echo htmlspecialchars($config[$prefix . 'subsubtitle_text']); ?>"></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'subsubtitle_size']; ?>px</label><input type="range" name="<?php echo $prefix; ?>subsubtitle_size" min="6" max="30" value="<?php echo $config[$prefix . 'subsubtitle_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_size']; ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?php echo $prefix; ?>subsubtitle_color" value="<?php echo $config[$prefix . 'subsubtitle_color']; ?>"></div>
<div class="form-group"><label>Fuente</label><select name="<?php echo $prefix; ?>subsubtitle_font"><?php foreach ($googleFontsList as $f): ?><option value="<?php echo $f; ?>" <?php echo $config[$prefix . 'subsubtitle_font'] === $f ? 'selected' : ''; ?>><?php echo $f; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?php echo $config[$prefix . 'subsubtitle_font_weight']; ?></label><input type="range" name="<?php echo $prefix; ?>subsubtitle_font_weight" min="100" max="900" step="100" value="<?php echo $config[$prefix . 'subsubtitle_font_weight']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_font_weight']; ?></div></div>
<div class="form-group"><label>Espaciado: <?php echo $config[$prefix . 'subsubtitle_letter_spacing']; ?>px</label><input type="range" name="<?php echo $prefix; ?>subsubtitle_letter_spacing" min="0" max="20" value="<?php echo $config[$prefix . 'subsubtitle_letter_spacing']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_letter_spacing']; ?>px</div></div>
<div class="form-group"><label>Transformacion</label><select name="<?php echo $prefix; ?>subsubtitle_text_transform"><option value="uppercase" <?php echo $config[$prefix . 'subsubtitle_text_transform'] === 'uppercase' ? 'selected' : ''; ?>>MAYUSCULAS</option><option value="lowercase" <?php echo $config[$prefix . 'subsubtitle_text_transform'] === 'lowercase' ? 'selected' : ''; ?>>minusculas</option><option value="capitalize" <?php echo $config[$prefix . 'subsubtitle_text_transform'] === 'capitalize' ? 'selected' : ''; ?>>Capitalizar</option><option value="none" <?php echo $config[$prefix . 'subsubtitle_text_transform'] === 'none' ? 'selected' : ''; ?>>Normal</option></select></div>
<div class="form-group"><label>Alineacion</label><select name="<?php echo $prefix; ?>subsubtitle_text_align"><option value="left" <?php echo $config[$prefix . 'subsubtitle_text_align'] === 'left' ? 'selected' : ''; ?>>Izquierda</option><option value="center" <?php echo $config[$prefix . 'subsubtitle_text_align'] === 'center' ? 'selected' : ''; ?>>Centro</option><option value="right" <?php echo $config[$prefix . 'subsubtitle_text_align'] === 'right' ? 'selected' : ''; ?>>Derecha</option></select></div>
<div class="form-group"><label>Salto de linea</label><select name="<?php echo $prefix; ?>subsubtitle_white_space"><option value="nowrap" <?php echo $config[$prefix . 'subsubtitle_white_space'] === 'nowrap' ? 'selected' : ''; ?>>Una linea</option><option value="normal" <?php echo $config[$prefix . 'subsubtitle_white_space'] === 'normal' ? 'selected' : ''; ?>>Multilinea</option></select></div>
<div class="form-group"><label>Ancho max: <?php echo $config[$prefix . 'subsubtitle_max_width']; ?>%</label><input type="range" name="<?php echo $prefix; ?>subsubtitle_max_width" min="20" max="100" value="<?php echo $config[$prefix . 'subsubtitle_max_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_max_width']; ?>%</div></div>
<div class="form-group"><label>Espaciado entre lineas: <?php echo $config[$prefix . 'subsubtitle_line_height']; ?></label><input type="range" name="<?php echo $prefix; ?>subsubtitle_line_height" min="0.8" max="2" step="0.1" value="<?php echo $config[$prefix . 'subsubtitle_line_height']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_line_height']; ?></div></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>subsubtitle_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'subsubtitle_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'subsubtitle_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>subsubtitle_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'subsubtitle_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'subsubtitle_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>subsubtitle_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'subsubtitle_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'subsubtitle_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>subsubtitle_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'subsubtitle_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'subsubtitle_position_y']; ?>%</div></div>
</div>
</div>

<div class="section" style="border: 1px solid #ff69b4;">
<h2>Ojo (contenedor del logo)</h2>
<div class="form-grid">
<div class="form-group"><label>Tipo</label><select name="<?php echo $prefix; ?>eye_type"><option value="image" <?php echo $config[$prefix . 'eye_type'] === 'image' ? 'selected' : ''; ?>>Imagen</option><option value="none" <?php echo $config[$prefix . 'eye_type'] === 'none' ? 'selected' : ''; ?>>Sin ojo</option></select></div>
<div class="form-group"><label>Subir Imagen del Ojo</label><input type="file" name="<?php echo $prefix; ?>eye" accept="image/*"><?php if (!empty($config[$prefix . 'eye_path']) && file_exists($baseDir . '/' . $config[$prefix . 'eye_path'])): ?><div style="margin-top:10px"><img src="<?php echo $config[$prefix . 'eye_path']; ?>" style="max-width:200px;border-radius:4px"></div><?php endif; ?></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'eye_size']; ?>vh</label><input type="range" name="<?php echo $prefix; ?>eye_size" min="10" max="150" value="<?php echo $config[$prefix . 'eye_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'vh'"><div class="value-display"><?php echo $config[$prefix . 'eye_size']; ?>vh</div></div>
<div class="form-group"><label>Radio Borde: <?php echo $config[$prefix . 'eye_border_radius']; ?>%</label><input type="range" name="<?php echo $prefix; ?>eye_border_radius" min="0" max="50" value="<?php echo $config[$prefix . 'eye_border_radius']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'eye_border_radius']; ?>%</div><p style="color:#a0a0a0;font-size:0.75rem;margin-top:5px">50% = circular (ojo de pez)</p></div>
<div class="form-group"><label>Ajuste de imagen</label><select name="<?php echo $prefix; ?>eye_object_fit"><?php foreach ($objectFits as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'eye_object_fit'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Glow (sombra)</label><select name="<?php echo $prefix; ?>eye_shadow"><?php foreach ($shadows as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'eye_shadow'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>eye_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'eye_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'eye_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>eye_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'eye_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'eye_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'eye_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>eye_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'eye_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'eye_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'eye_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>eye_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'eye_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'eye_position_y']; ?>%</div></div>
</div>
</div>

<div class="section">
<h2>Logo/Video (dentro del ojo)</h2>
<div class="form-grid">
<div class="form-group"><label>Tipo</label><select name="<?php echo $prefix; ?>logo_type"><option value="image" <?php echo $config[$prefix . 'logo_type'] === 'image' ? 'selected' : ''; ?>>Imagen</option><option value="video" <?php echo $config[$prefix . 'logo_type'] === 'video' ? 'selected' : ''; ?>>Video</option></select></div>
<div class="form-group"><label>Subir Imagen</label><input type="file" name="<?php echo $prefix; ?>logo" accept="image/*"></div>
<div class="form-group"><label>Subir Video</label><input type="file" name="<?php echo $prefix; ?>video" accept="video/*"></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'logo_size']; ?>vh</label><input type="range" name="<?php echo $prefix; ?>logo_size" min="10" max="100" value="<?php echo $config[$prefix . 'logo_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'vh'"><div class="value-display"><?php echo $config[$prefix . 'logo_size']; ?>vh</div></div>
<div class="form-group"><label>Radio Borde: <?php echo $config[$prefix . 'logo_border_radius']; ?>%</label><input type="range" name="<?php echo $prefix; ?>logo_border_radius" min="0" max="50" value="<?php echo $config[$prefix . 'logo_border_radius']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'logo_border_radius']; ?>%</div></div>
<div class="form-group"><label>Grosor Borde: <?php echo $config[$prefix . 'logo_border_width']; ?>px</label><input type="range" name="<?php echo $prefix; ?>logo_border_width" min="0" max="20" value="<?php echo $config[$prefix . 'logo_border_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'logo_border_width']; ?>px</div></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?php echo $prefix; ?>logo_border_color" value="<?php echo $config[$prefix . 'logo_border_color']; ?>"></div>
<div class="form-group"><label>Glow (sombra)</label><select name="<?php echo $prefix; ?>logo_shadow"><?php foreach ($shadows as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'logo_shadow'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>logo_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'logo_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'logo_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>logo_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'logo_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'logo_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'logo_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>logo_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'logo_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'logo_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'logo_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>logo_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'logo_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'logo_position_y']; ?>%</div></div>
</div>
</div>

<div class="section">
<h2>Boton Principal</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?php echo $prefix; ?>btn_main_text" value="<?php echo htmlspecialchars($config[$prefix . 'btn_main_text']); ?>"></div>
<div class="form-group"><label>Link</label><input type="text" name="<?php echo $prefix; ?>btn_main_link" value="<?php echo htmlspecialchars($config[$prefix . 'btn_main_link']); ?>"></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'btn_main_size']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_main_size" min="20" max="150" value="<?php echo $config[$prefix . 'btn_main_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_size']; ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?php echo $prefix; ?>btn_main_color" value="<?php echo $config[$prefix . 'btn_main_color']; ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?php echo $prefix; ?>btn_main_hover" value="<?php echo $config[$prefix . 'btn_main_hover']; ?>"></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?php echo $prefix; ?>btn_main_border_color" value="<?php echo $config[$prefix . 'btn_main_border_color']; ?>"></div>
<div class="form-group"><label>Grosor Borde: <?php echo $config[$prefix . 'btn_main_border_width']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_main_border_width" min="0" max="20" value="<?php echo $config[$prefix . 'btn_main_border_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_border_width']; ?>px</div></div>
<div class="form-group"><label>Radio Borde: <?php echo $config[$prefix . 'btn_main_border_radius']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_main_border_radius" min="0" max="100" value="<?php echo $config[$prefix . 'btn_main_border_radius']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_border_radius']; ?>px</div></div>
<div class="form-group"><label>Padding V: <?php echo $config[$prefix . 'btn_main_padding_v']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_main_padding_v" min="0" max="100" value="<?php echo $config[$prefix . 'btn_main_padding_v']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_padding_v']; ?>px</div></div>
<div class="form-group"><label>Padding H: <?php echo $config[$prefix . 'btn_main_padding_h']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_main_padding_h" min="0" max="200" value="<?php echo $config[$prefix . 'btn_main_padding_h']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_padding_h']; ?>px</div></div>
<div class="form-group"><label>Fuente</label><select name="<?php echo $prefix; ?>btn_main_font"><?php foreach ($googleFontsList as $f): ?><option value="<?php echo $f; ?>" <?php echo $config[$prefix . 'btn_main_font'] === $f ? 'selected' : ''; ?>><?php echo $f; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?php echo $config[$prefix . 'btn_main_font_weight']; ?></label><input type="range" name="<?php echo $prefix; ?>btn_main_font_weight" min="100" max="900" step="100" value="<?php echo $config[$prefix . 'btn_main_font_weight']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'btn_main_font_weight']; ?></div></div>
<div class="form-group"><label>Espaciado: <?php echo $config[$prefix . 'btn_main_letter_spacing']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_main_letter_spacing" min="0" max="30" value="<?php echo $config[$prefix . 'btn_main_letter_spacing']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_letter_spacing']; ?>px</div></div>
<div class="form-group"><label>Transformacion</label><select name="<?php echo $prefix; ?>btn_main_text_transform"><option value="uppercase" <?php echo $config[$prefix . 'btn_main_text_transform'] === 'uppercase' ? 'selected' : ''; ?>>MAYUSCULAS</option><option value="lowercase" <?php echo $config[$prefix . 'btn_main_text_transform'] === 'lowercase' ? 'selected' : ''; ?>>minusculas</option><option value="capitalize" <?php echo $config[$prefix . 'btn_main_text_transform'] === 'capitalize' ? 'selected' : ''; ?>>Capitalizar</option><option value="none" <?php echo $config[$prefix . 'btn_main_text_transform'] === 'none' ? 'selected' : ''; ?>>Normal</option></select></div>
<div class="form-group"><label>Alineacion</label><select name="<?php echo $prefix; ?>btn_main_text_align"><option value="left" <?php echo $config[$prefix . 'btn_main_text_align'] === 'left' ? 'selected' : ''; ?>>Izquierda</option><option value="center" <?php echo $config[$prefix . 'btn_main_text_align'] === 'center' ? 'selected' : ''; ?>>Centro</option><option value="right" <?php echo $config[$prefix . 'btn_main_text_align'] === 'right' ? 'selected' : ''; ?>>Derecha</option></select></div>
<div class="form-group"><label>Salto de linea</label><select name="<?php echo $prefix; ?>btn_main_white_space"><option value="nowrap" <?php echo $config[$prefix . 'btn_main_white_space'] === 'nowrap' ? 'selected' : ''; ?>>Una linea</option><option value="normal" <?php echo $config[$prefix . 'btn_main_white_space'] === 'normal' ? 'selected' : ''; ?>>Multilinea</option></select></div>
<div class="form-group"><label>Ancho max: <?php echo $config[$prefix . 'btn_main_max_width']; ?>%</label><input type="range" name="<?php echo $prefix; ?>btn_main_max_width" min="20" max="100" value="<?php echo $config[$prefix . 'btn_main_max_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_max_width']; ?>%</div></div>
<div class="form-group"><label>Sombra Hover</label><select name="<?php echo $prefix; ?>btn_main_shadow_hover"><?php foreach ($shadows as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'btn_main_shadow_hover'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Transform Hover</label><select name="<?php echo $prefix; ?>btn_main_transform_hover"><?php foreach ($transforms as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'btn_main_transform_hover'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>btn_main_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'btn_main_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'btn_main_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>btn_main_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'btn_main_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'btn_main_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'btn_main_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>btn_main_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'btn_main_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'btn_main_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>btn_main_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'btn_main_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'btn_main_position_y']; ?>%</div></div>
</div>
</div>

<div class="section">
<h2>Boton Secundario</h2>
<div class="form-grid">
<div class="form-group"><label>Texto</label><input type="text" name="<?php echo $prefix; ?>btn_sec_text" value="<?php echo htmlspecialchars($config[$prefix . 'btn_sec_text']); ?>"></div>
<div class="form-group"><label>Link</label><input type="text" name="<?php echo $prefix; ?>btn_sec_link" value="<?php echo htmlspecialchars($config[$prefix . 'btn_sec_link']); ?>"></div>
<div class="form-group"><label>Tamano: <?php echo $config[$prefix . 'btn_sec_size']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_sec_size" min="10" max="60" value="<?php echo $config[$prefix . 'btn_sec_size']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_size']; ?>px</div></div>
<div class="form-group"><label>Color</label><input type="color" name="<?php echo $prefix; ?>btn_sec_color" value="<?php echo $config[$prefix . 'btn_sec_color']; ?>"></div>
<div class="form-group"><label>Color Hover</label><input type="color" name="<?php echo $prefix; ?>btn_sec_hover" value="<?php echo $config[$prefix . 'btn_sec_hover']; ?>"></div>
<div class="form-group"><label>Color Borde</label><input type="color" name="<?php echo $prefix; ?>btn_sec_border_color" value="<?php echo $config[$prefix . 'btn_sec_border_color']; ?>"></div>
<div class="form-group"><label>Grosor Borde: <?php echo $config[$prefix . 'btn_sec_border_width']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_sec_border_width" min="0" max="20" value="<?php echo $config[$prefix . 'btn_sec_border_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_border_width']; ?>px</div></div>
<div class="form-group"><label>Radio Borde: <?php echo $config[$prefix . 'btn_sec_border_radius']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_sec_border_radius" min="0" max="50" value="<?php echo $config[$prefix . 'btn_sec_border_radius']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_border_radius']; ?>px</div></div>
<div class="form-group"><label>Padding V: <?php echo $config[$prefix . 'btn_sec_padding_v']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_sec_padding_v" min="0" max="50" value="<?php echo $config[$prefix . 'btn_sec_padding_v']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_padding_v']; ?>px</div></div>
<div class="form-group"><label>Padding H: <?php echo $config[$prefix . 'btn_sec_padding_h']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_sec_padding_h" min="0" max="100" value="<?php echo $config[$prefix . 'btn_sec_padding_h']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_padding_h']; ?>px</div></div>
<div class="form-group"><label>Fuente</label><select name="<?php echo $prefix; ?>btn_sec_font"><?php foreach ($googleFontsList as $f): ?><option value="<?php echo $f; ?>" <?php echo $config[$prefix . 'btn_sec_font'] === $f ? 'selected' : ''; ?>><?php echo $f; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Peso: <?php echo $config[$prefix . 'btn_sec_font_weight']; ?></label><input type="range" name="<?php echo $prefix; ?>btn_sec_font_weight" min="100" max="900" step="100" value="<?php echo $config[$prefix . 'btn_sec_font_weight']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_font_weight']; ?></div></div>
<div class="form-group"><label>Espaciado: <?php echo $config[$prefix . 'btn_sec_letter_spacing']; ?>px</label><input type="range" name="<?php echo $prefix; ?>btn_sec_letter_spacing" min="0" max="20" value="<?php echo $config[$prefix . 'btn_sec_letter_spacing']; ?>" oninput="this.nextElementSibling.textContent = this.value + 'px'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_letter_spacing']; ?>px</div></div>
<div class="form-group"><label>Transformacion</label><select name="<?php echo $prefix; ?>btn_sec_text_transform"><option value="uppercase" <?php echo $config[$prefix . 'btn_sec_text_transform'] === 'uppercase' ? 'selected' : ''; ?>>MAYUSCULAS</option><option value="lowercase" <?php echo $config[$prefix . 'btn_sec_text_transform'] === 'lowercase' ? 'selected' : ''; ?>>minusculas</option><option value="capitalize" <?php echo $config[$prefix . 'btn_sec_text_transform'] === 'capitalize' ? 'selected' : ''; ?>>Capitalizar</option><option value="none" <?php echo $config[$prefix . 'btn_sec_text_transform'] === 'none' ? 'selected' : ''; ?>>Normal</option></select></div>
<div class="form-group"><label>Alineacion</label><select name="<?php echo $prefix; ?>btn_sec_text_align"><option value="left" <?php echo $config[$prefix . 'btn_sec_text_align'] === 'left' ? 'selected' : ''; ?>>Izquierda</option><option value="center" <?php echo $config[$prefix . 'btn_sec_text_align'] === 'center' ? 'selected' : ''; ?>>Centro</option><option value="right" <?php echo $config[$prefix . 'btn_sec_text_align'] === 'right' ? 'selected' : ''; ?>>Derecha</option></select></div>
<div class="form-group"><label>Salto de linea</label><select name="<?php echo $prefix; ?>btn_sec_white_space"><option value="nowrap" <?php echo $config[$prefix . 'btn_sec_white_space'] === 'nowrap' ? 'selected' : ''; ?>>Una linea</option><option value="normal" <?php echo $config[$prefix . 'btn_sec_white_space'] === 'normal' ? 'selected' : ''; ?>>Multilinea</option></select></div>
<div class="form-group"><label>Ancho max: <?php echo $config[$prefix . 'btn_sec_max_width']; ?>%</label><input type="range" name="<?php echo $prefix; ?>btn_sec_max_width" min="20" max="100" value="<?php echo $config[$prefix . 'btn_sec_max_width']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_max_width']; ?>%</div></div>
<div class="form-group"><label>Sombra Hover</label><select name="<?php echo $prefix; ?>btn_sec_shadow_hover"><?php foreach ($shadows as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'btn_sec_shadow_hover'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Transform Hover</label><select name="<?php echo $prefix; ?>btn_sec_transform_hover"><?php foreach ($transforms as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'btn_sec_transform_hover'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Animacion</label><select name="<?php echo $prefix; ?>btn_sec_animation"><?php foreach ($animations as $v => $l): ?><option value="<?php echo $v; ?>" <?php echo $config[$prefix . 'btn_sec_animation'] === $v ? 'selected' : ''; ?>><?php echo $l; ?></option><?php endforeach; ?></select></div>
<div class="form-group"><label>Z-Index: <?php echo $config[$prefix . 'btn_sec_zindex']; ?></label><input type="range" name="<?php echo $prefix; ?>btn_sec_zindex" min="1" max="100" value="<?php echo $config[$prefix . 'btn_sec_zindex']; ?>" oninput="this.nextElementSibling.textContent = this.value"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_zindex']; ?></div></div>
</div>
<div class="position-grid">
<div class="form-group"><label>X: <?php echo $config[$prefix . 'btn_sec_position_x']; ?>%</label><input type="range" name="<?php echo $prefix; ?>btn_sec_position_x" min="0" max="100" value="<?php echo $config[$prefix . 'btn_sec_position_x']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_position_x']; ?>%</div></div>
<div class="form-group"><label>Y: <?php echo $config[$prefix . 'btn_sec_position_y']; ?>%</label><input type="range" name="<?php echo $prefix; ?>btn_sec_position_y" min="0" max="100" value="<?php echo $config[$prefix . 'btn_sec_position_y']; ?>" oninput="this.nextElementSibling.textContent = this.value + '%'"><div class="value-display"><?php echo $config[$prefix . 'btn_sec_position_y']; ?>%</div></div>
</div>
</div>

<div class="section" style="border: 1px solid #fffc34;">
<h2 style="color: #fffc34;">Configuracion de Pagina</h2>
<div class="form-grid">
<div class="form-group">
<label class="checkbox-label">
<input type="checkbox" name="<?php echo $prefix; ?>page_scroll_enabled" value="1" <?php echo $config[$prefix . 'page_scroll_enabled'] ? 'checked' : ''; ?>>
<span>Activar Scroll Vertical</span>
</label>
<p style="color:#a0a0a0;font-size:0.75rem;margin-top:5px">Desactivar para pagina fija sin scroll</p>
</div>
</div>
</div>

<button type="submit" class="save-btn">GUARDAR CONFIGURACION DE PORTADA</button>
</form>
</div>

<script>
function toggleBgSections() {
    var type = document.getElementById('bg_type').value;
    document.getElementById('bg_image_section').classList.toggle('hidden', type !== 'image');
    document.getElementById('bg_video_section').classList.toggle('hidden', type !== 'video');
}
</script>
</body>
</html>