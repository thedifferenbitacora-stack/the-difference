<?php
$configFile = __DIR__ . '/config/settings.json';
$defaults = [
    'portada_bg_type' => 'solid',
    'portada_bg_color' => '#000000',
    'portada_bg_image_path' => '',
    'portada_bg_video_path' => '',
    'portada_bg_overlay_color' => '#000000',
    'portada_bg_overlay_opacity' => 0.3,
    'portada_title_text' => 'FEELING AUTISTIC',
    'portada_title_size' => 60,
    'portada_title_color' => '#ffffff',
    'portada_title_font' => 'Arial Black',
    'portada_title_font_weight' => 900,
    'portada_title_letter_spacing' => 5,
    'portada_title_text_transform' => 'uppercase',
    'portada_title_text_align' => 'center',
    'portada_title_white_space' => 'nowrap',
    'portada_title_max_width' => 100,
    'portada_title_line_height' => 1.2,
    'portada_title_position_x' => 50,
    'portada_title_position_y' => 30,
    'portada_title_animation' => 'fadeInDown',
    'portada_title_zindex' => 10,
    'portada_subtitle_text' => 'INTUITIVE ANALITYC NEURODIVERGENCE CREATIVE PLATFORM',
    'portada_subtitle_size' => 14,
    'portada_subtitle_color' => '#a0a0a0',
    'portada_subtitle_font' => 'Arial Black',
    'portada_subtitle_font_weight' => 400,
    'portada_subtitle_letter_spacing' => 2,
    'portada_subtitle_text_transform' => 'uppercase',
    'portada_subtitle_text_align' => 'center',
    'portada_subtitle_white_space' => 'nowrap',
    'portada_subtitle_max_width' => 100,
    'portada_subtitle_line_height' => 1.4,
    'portada_subtitle_position_x' => 50,
    'portada_subtitle_position_y' => 45,
    'portada_subtitle_zindex' => 10,
    'portada_subsubtitle_text' => 'SYSTEM BITACORA TEXVN',
    'portada_subsubtitle_size' => 11,
    'portada_subsubtitle_color' => '#666666',
    'portada_subsubtitle_font' => 'Arial Black',
    'portada_subsubtitle_font_weight' => 400,
    'portada_subsubtitle_letter_spacing' => 2,
    'portada_subsubtitle_text_transform' => 'uppercase',
    'portada_subsubtitle_text_align' => 'center',
    'portada_subsubtitle_white_space' => 'nowrap',
    'portada_subsubtitle_max_width' => 100,
    'portada_subsubtitle_line_height' => 1.4,
    'portada_subsubtitle_position_x' => 50,
    'portada_subsubtitle_position_y' => 50,
    'portada_subsubtitle_zindex' => 10,
    'portada_logo_type' => 'image',
    'portada_logo_path' => 'images/logo-feeling-autistic.png',
    'portada_video_path' => '',
    'portada_logo_size' => 50,
    'portada_logo_border_radius' => 50,
    'portada_logo_border_width' => 3,
    'portada_logo_border_color' => '#ffffff',
    'portada_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
    'portada_logo_position_x' => 50,
    'portada_logo_position_y' => 15,
    'portada_logo_zindex' => 10,
    'portada_logo_animation' => 'zoomIn',
    'portada_eye_type' => 'image',
    'portada_eye_path' => 'images/eye-bg.png',
    'portada_eye_size' => 60,
    'portada_eye_border_radius' => 50,
    'portada_eye_object_fit' => 'contain',
    'portada_eye_shadow' => '0 0 40px rgba(255,105,180,0.6)',
    'portada_eye_position_x' => 50,
    'portada_eye_position_y' => 15,
    'portada_eye_zindex' => 9,
    'portada_eye_animation' => 'zoomIn',
    'portada_btn_main_text' => 'THE DIFFERENCE',
    'portada_btn_main_link' => 'menu.php',
    'portada_btn_main_size' => 50,
    'portada_btn_main_color' => '#ffffff',
    'portada_btn_main_hover' => '#ff69b4',
    'portada_btn_main_border_width' => 3,
    'portada_btn_main_border_color' => '#ffffff',
    'portada_btn_main_border_radius' => 0,
    'portada_btn_main_padding_v' => 20,
    'portada_btn_main_padding_h' => 40,
    'portada_btn_main_letter_spacing' => 5,
    'portada_btn_main_font' => 'Arial Black',
    'portada_btn_main_font_weight' => 900,
    'portada_btn_main_text_transform' => 'uppercase',
    'portada_btn_main_text_align' => 'center',
    'portada_btn_main_white_space' => 'nowrap',
    'portada_btn_main_max_width' => 90,
    'portada_btn_main_shadow_hover' => '0 0 30px rgba(255,105,180,0.5)',
    'portada_btn_main_transform_hover' => 'scale(1.05)',
    'portada_btn_main_animation' => 'fadeInUp',
    'portada_btn_main_position_x' => 50,
    'portada_btn_main_position_y' => 65,
    'portada_btn_main_zindex' => 10,
    'portada_btn_sec_text' => 'LE TEMATIK DESIGN',
    'portada_btn_sec_link' => 'le-tematik.php',
    'portada_btn_sec_size' => 16,
    'portada_btn_sec_color' => '#fffc34',
    'portada_btn_sec_hover' => '#ffffff',
    'portada_btn_sec_border_width' => 2,
    'portada_btn_sec_border_color' => '#ffffff',
    'portada_btn_sec_border_radius' => 0,
    'portada_btn_sec_padding_v' => 8,
    'portada_btn_sec_padding_h' => 20,
    'portada_btn_sec_letter_spacing' => 2,
    'portada_btn_sec_font' => 'Arial Black',
    'portada_btn_sec_font_weight' => 900,
    'portada_btn_sec_text_transform' => 'uppercase',
    'portada_btn_sec_text_align' => 'center',
    'portada_btn_sec_white_space' => 'nowrap',
    'portada_btn_sec_max_width' => 90,
    'portada_btn_sec_shadow_hover' => '0 0 20px rgba(255,252,52,0.5)',
    'portada_btn_sec_transform_hover' => 'scale(1.05)',
    'portada_btn_sec_animation' => 'fadeInRight',
    'portada_btn_sec_position_x' => 85,
    'portada_btn_sec_position_y' => 90,
    'portada_btn_sec_zindex' => 10,
    'portada_page_scroll_enabled' => true,
];

$config = file_exists($configFile) ? array_merge($defaults, json_decode(file_get_contents($configFile), true) ?: []) : $defaults;

$positionKeys = [
    'portada_title_position_x', 'portada_title_position_y',
    'portada_subtitle_position_x', 'portada_subtitle_position_y',
    'portada_subsubtitle_position_x', 'portada_subsubtitle_position_y',
    'portada_logo_position_x', 'portada_logo_position_y',
    'portada_eye_position_x', 'portada_eye_position_y',
    'portada_btn_main_position_x', 'portada_btn_main_position_y',
    'portada_btn_sec_position_x', 'portada_btn_sec_position_y',
];

$needsSave = false;
foreach ($positionKeys as $key) {
    $val = floatval($config[$key]);
    if ($val < 0 || $val > 100 || is_nan($val) || is_infinite($val)) {
        $config[$key] = $defaults[$key];
        $needsSave = true;
    }
}
if ($needsSave) file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));

function g($c, $k, $d = '') { return $c[$k] ?? $d; }

$googleFonts = g($config, 'google_fonts', 'Arial Black,Roboto,Playfair Display,Syne,Inter,Montserrat,Poppins');
$fontsArray = array_map('trim', explode(',', $googleFonts));
$primaryFont = $fontsArray[0];
$googleFontsUrl = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', array_map(function($f) {
    return str_replace(' ', '+', $f) . ':wght@100;200;300;400;500;600;700;800;900';
}, $fontsArray)) . '&display=swap';

$scrollEnabled = g($config, 'portada_page_scroll_enabled', true);
$scrollCSS = $scrollEnabled ? 'auto' : 'hidden';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars(g($config, 'portada_title_text', 'The Difference')) ?></title>
<link href="<?= $googleFontsUrl ?>" rel="stylesheet">
<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -50%) translateY(-30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes fadeInRight { from { opacity: 0; transform: translate(-50%, -50%) translateX(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateX(0); } }
@keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }

* { margin: 0; padding: 0; box-sizing: border-box; }
html { scroll-behavior: smooth; }
body { 
    background: <?= g($config, 'portada_bg_color', '#000000') ?>; 
    font-family: '<?= $primaryFont ?>', sans-serif;
    min-height: 100vh;
    position: relative;
    user-select: none;
    overflow-x: hidden;
    overflow-y: <?= $scrollCSS ?>;
}

.bg-media { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; object-fit: cover; z-index: 1; }
.bg-overlay { position: fixed; top: 0; left: 0; width: 100vw; height: 100vh; background: <?= g($config, 'portada_bg_overlay_color', '#000000') ?>; opacity: <?= g($config, 'portada_bg_overlay_opacity', 0.3) ?>; z-index: 2; }

.main-container { 
    width: 100vw; 
    min-height: <?= $scrollEnabled ? '200vh' : '100vh' ?>;
    position: relative; 
    z-index: 5;
}

.title {
    position: absolute;
    left: <?= g($config, 'portada_title_position_x', 50) ?>%;
    top: <?= g($config, 'portada_title_position_y', 30) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'portada_title_size', 60) ?>px;
    font-weight: <?= g($config, 'portada_title_font_weight', 900) ?>;
    font-family: '<?= g($config, 'portada_title_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'portada_title_color', '#ffffff') ?>;
    text-transform: <?= g($config, 'portada_title_text_transform', 'uppercase') ?>;
    letter-spacing: <?= g($config, 'portada_title_letter_spacing', 5) ?>px;
    line-height: <?= g($config, 'portada_title_line_height', 1.2) ?>;
    text-align: <?= g($config, 'portada_title_text_align', 'center') ?>;
    white-space: <?= g($config, 'portada_title_white_space', 'nowrap') ?>;
    max-width: <?= g($config, 'portada_title_max_width', 100) ?>%;
    z-index: <?= g($config, 'portada_title_zindex', 10) ?>;
    animation: <?= g($config, 'portada_title_animation', 'fadeInDown') ?> 1s ease both;
}

.subtitle {
    position: absolute;
    left: <?= g($config, 'portada_subtitle_position_x', 50) ?>%;
    top: <?= g($config, 'portada_subtitle_position_y', 45) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'portada_subtitle_size', 14) ?>px;
    font-weight: <?= g($config, 'portada_subtitle_font_weight', 400) ?>;
    font-family: '<?= g($config, 'portada_subtitle_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'portada_subtitle_color', '#a0a0a0') ?>;
    text-transform: <?= g($config, 'portada_subtitle_text_transform', 'uppercase') ?>;
    letter-spacing: <?= g($config, 'portada_subtitle_letter_spacing', 2) ?>px;
    line-height: <?= g($config, 'portada_subtitle_line_height', 1.4) ?>;
    text-align: <?= g($config, 'portada_subtitle_text_align', 'center') ?>;
    white-space: <?= g($config, 'portada_subtitle_white_space', 'nowrap') ?>;
    max-width: <?= g($config, 'portada_subtitle_max_width', 100) ?>%;
    z-index: <?= g($config, 'portada_subtitle_zindex', 10) ?>;
    animation: fadeIn 1s ease 0.3s both;
}

.subsubtitle {
    position: absolute;
    left: <?= g($config, 'portada_subsubtitle_position_x', 50) ?>%;
    top: <?= g($config, 'portada_subsubtitle_position_y', 50) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'portada_subsubtitle_size', 11) ?>px;
    font-weight: <?= g($config, 'portada_subsubtitle_font_weight', 400) ?>;
    font-family: '<?= g($config, 'portada_subsubtitle_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'portada_subsubtitle_color', '#666666') ?>;
    text-transform: <?= g($config, 'portada_subsubtitle_text_transform', 'uppercase') ?>;
    letter-spacing: <?= g($config, 'portada_subsubtitle_letter_spacing', 2) ?>px;
    line-height: <?= g($config, 'portada_subsubtitle_line_height', 1.4) ?>;
    text-align: <?= g($config, 'portada_subsubtitle_text_align', 'center') ?>;
    white-space: <?= g($config, 'portada_subsubtitle_white_space', 'nowrap') ?>;
    max-width: <?= g($config, 'portada_subsubtitle_max_width', 100) ?>%;
    z-index: <?= g($config, 'portada_subsubtitle_zindex', 10) ?>;
    animation: fadeIn 1s ease 0.6s both;
}

/* OJO: contenedor con border-radius y object-fit */
.eye-container {
    position: absolute;
    left: <?= g($config, 'portada_eye_position_x', 50) ?>%;
    top: <?= g($config, 'portada_eye_position_y', 15) ?>%;
    transform: translate(-50%, -50%);
    width: <?= g($config, 'portada_eye_size', 60) ?>vh;
    height: <?= g($config, 'portada_eye_size', 60) ?>vh;
    border-radius: <?= g($config, 'portada_eye_border_radius', 50) ?>%;
    overflow: hidden;
    box-shadow: <?= g($config, 'portada_eye_shadow', '0 0 40px rgba(255,105,180,0.6)') ?>;
    z-index: <?= g($config, 'portada_eye_zindex', 9) ?>;
    animation: <?= g($config, 'portada_eye_animation', 'zoomIn') ?> 1.5s ease both;
    display: flex;
    align-items: center;
    justify-content: center;
}
.eye-container img {
    width: 100%;
    height: 100%;
    object-fit: <?= g($config, 'portada_eye_object_fit', 'contain') ?>;
}

.logo-container {
    position: absolute;
    left: <?= g($config, 'portada_logo_position_x', 50) ?>%;
    top: <?= g($config, 'portada_logo_position_y', 15) ?>%;
    transform: translate(-50%, -50%);
    width: <?= g($config, 'portada_logo_size', 50) ?>vh;
    height: <?= g($config, 'portada_logo_size', 50) ?>vh;
    border-radius: <?= g($config, 'portada_logo_border_radius', 50) ?>%;
    border: <?= g($config, 'portada_logo_border_width', 3) ?>px solid <?= g($config, 'portada_logo_border_color', '#ffffff') ?>;
    box-shadow: <?= g($config, 'portada_logo_shadow', '0 0 30px rgba(255,105,180,0.5)') ?>;
    overflow: hidden;
    z-index: <?= g($config, 'portada_logo_zindex', 10) ?>;
    animation: <?= g($config, 'portada_logo_animation', 'zoomIn') ?> 1.5s ease both;
}
.logo-container img, .logo-container video { width: 100%; height: 100%; object-fit: cover; }

.btn-main {
    position: absolute;
    left: <?= g($config, 'portada_btn_main_position_x', 50) ?>%;
    top: <?= g($config, 'portada_btn_main_position_y', 65) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'portada_btn_main_size', 50) ?>px;
    font-weight: <?= g($config, 'portada_btn_main_font_weight', 900) ?>;
    font-family: '<?= g($config, 'portada_btn_main_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'portada_btn_main_color', '#ffffff') ?>;
    text-transform: <?= g($config, 'portada_btn_main_text_transform', 'uppercase') ?>;
    text-align: <?= g($config, 'portada_btn_main_text_align', 'center') ?>;
    letter-spacing: <?= g($config, 'portada_btn_main_letter_spacing', 5) ?>px;
    white-space: <?= g($config, 'portada_btn_main_white_space', 'nowrap') ?>;
    max-width: <?= g($config, 'portada_btn_main_max_width', 90) ?>%;
    border: <?= g($config, 'portada_btn_main_border_width', 3) ?>px solid <?= g($config, 'portada_btn_main_border_color', '#ffffff') ?>;
    border-radius: <?= g($config, 'portada_btn_main_border_radius', 0) ?>px;
    padding: <?= g($config, 'portada_btn_main_padding_v', 20) ?>px <?= g($config, 'portada_btn_main_padding_h', 40) ?>px;
    background: transparent;
    text-decoration: none;
    cursor: pointer;
    z-index: <?= g($config, 'portada_btn_main_zindex', 10) ?>;
    animation: <?= g($config, 'portada_btn_main_animation', 'fadeInUp') ?> 1s ease 0.5s both;
    transition: all 0.3s ease;
    display: block;
}
.btn-main:hover {
    color: <?= g($config, 'portada_btn_main_hover', '#ff69b4') ?>;
    border-color: <?= g($config, 'portada_btn_main_hover', '#ff69b4') ?>;
    transform: translate(-50%, -50%) <?= g($config, 'portada_btn_main_transform_hover', 'scale(1.05)') ?>;
    box-shadow: <?= g($config, 'portada_btn_main_shadow_hover', '0 0 30px rgba(255,105,180,0.5)') ?>;
}

.btn-secondary {
    position: absolute;
    left: <?= g($config, 'portada_btn_sec_position_x', 85) ?>%;
    top: <?= g($config, 'portada_btn_sec_position_y', 90) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'portada_btn_sec_size', 16) ?>px;
    font-weight: <?= g($config, 'portada_btn_sec_font_weight', 900) ?>;
    font-family: '<?= g($config, 'portada_btn_sec_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'portada_btn_sec_color', '#fffc34') ?>;
    text-transform: <?= g($config, 'portada_btn_sec_text_transform', 'uppercase') ?>;
    text-align: <?= g($config, 'portada_btn_sec_text_align', 'center') ?>;
    letter-spacing: <?= g($config, 'portada_btn_sec_letter_spacing', 2) ?>px;
    white-space: <?= g($config, 'portada_btn_sec_white_space', 'nowrap') ?>;
    max-width: <?= g($config, 'portada_btn_sec_max_width', 90) ?>%;
    border: <?= g($config, 'portada_btn_sec_border_width', 2) ?>px solid <?= g($config, 'portada_btn_sec_border_color', '#ffffff') ?>;
    border-radius: <?= g($config, 'portada_btn_sec_border_radius', 0) ?>px;
    padding: <?= g($config, 'portada_btn_sec_padding_v', 8) ?>px <?= g($config, 'portada_btn_sec_padding_h', 20) ?>px;
    background: transparent;
    text-decoration: none;
    cursor: pointer;
    z-index: <?= g($config, 'portada_btn_sec_zindex', 10) ?>;
    animation: <?= g($config, 'portada_btn_sec_animation', 'fadeInRight') ?> 1s ease 1s both;
    transition: all 0.3s ease;
    display: block;
}
.btn-secondary:hover {
    color: <?= g($config, 'portada_btn_sec_hover', '#ffffff') ?>;
    border-color: <?= g($config, 'portada_btn_sec_hover', '#ffffff') ?>;
    transform: translate(-50%, -50%) <?= g($config, 'portada_btn_sec_transform_hover', 'scale(1.05)') ?>;
    box-shadow: <?= g($config, 'portada_btn_sec_shadow_hover', '0 0 20px rgba(255,252,52,0.5)') ?>;
}

@media (max-width: 768px) {
    .title { font-size: <?= max(24, g($config, 'portada_title_size', 60) * 0.5) ?>px !important; white-space: normal !important; max-width: 90vw !important; }
    .subtitle { font-size: 10px !important; white-space: normal !important; max-width: 90vw !important; }
    .subsubtitle { font-size: 8px !important; white-space: normal !important; max-width: 90vw !important; }
    .eye-container { width: <?= max(25, g($config, 'portada_eye_size', 60) * 0.6) ?>vh !important; height: <?= max(25, g($config, 'portada_eye_size', 60) * 0.6) ?>vh !important; }
    .logo-container { width: <?= max(20, g($config, 'portada_logo_size', 50) * 0.6) ?>vh !important; height: <?= max(20, g($config, 'portada_logo_size', 50) * 0.6) ?>vh !important; }
    .btn-main { font-size: <?= max(16, g($config, 'portada_btn_main_size', 50) * 0.5) ?>px !important; padding: 10px 20px !important; }
    .btn-secondary { font-size: <?= max(10, g($config, 'portada_btn_sec_size', 16) * 0.7) ?>px !important; padding: 5px 10px !important; }
}
</style>
</head>
<body>

<?php if (g($config, 'portada_bg_type') === 'video' && !empty(g($config, 'portada_bg_video_path'))): ?>
<video class="bg-media" autoplay loop muted playsinline>
    <source src="<?= g($config, 'portada_bg_video_path') ?>" type="video/mp4">
</video>
<div class="bg-overlay"></div>
<?php elseif (g($config, 'portada_bg_type') === 'image' && !empty(g($config, 'portada_bg_image_path'))): ?>
<img src="<?= g($config, 'portada_bg_image_path') ?>" class="bg-media" alt="Fondo">
<div class="bg-overlay"></div>
<?php endif; ?>

<?php if ($needsSave): ?>
<div style="position:fixed;top:80px;right:20px;z-index:9999;padding:10px 16px;background:rgba(16,185,129,0.95);color:white;border-radius:8px;font-size:12px;font-weight:700;">✅ Posiciones corregidas</div>
<?php endif; ?>

<div class="main-container">
    <div class="title"><?= htmlspecialchars(g($config, 'portada_title_text', 'FEELING AUTISTIC')) ?></div>
    <div class="subtitle"><?= htmlspecialchars(g($config, 'portada_subtitle_text', 'INTUITIVE ANALITYC NEURODIVERGENCE CREATIVE PLATFORM')) ?></div>
    <div class="subsubtitle"><?= htmlspecialchars(g($config, 'portada_subsubtitle_text', 'SYSTEM BITACORA TEXVN')) ?></div>
    
    <?php if (g($config, 'portada_eye_type', 'image') !== 'none' && !empty(g($config, 'portada_eye_path'))): ?>
    <div class="eye-container">
        <img src="<?= g($config, 'portada_eye_path', 'images/eye-bg.png') ?>" alt="Ojo">
    </div>
    <?php endif; ?>
    
    <div class="logo-container">
        <?php if (g($config, 'portada_logo_type') === 'video' && !empty(g($config, 'portada_video_path'))): ?>
        <video src="<?= g($config, 'portada_video_path') ?>" autoplay loop muted playsinline></video>
        <?php else: ?>
        <img src="<?= g($config, 'portada_logo_path', 'images/logo-feeling-autistic.png') ?>" alt="Logo">
        <?php endif; ?>
    </div>
    
    <a href="<?= htmlspecialchars(g($config, 'portada_btn_main_link', 'menu.php')) ?>" class="btn-main"><?= htmlspecialchars(g($config, 'portada_btn_main_text', 'THE DIFFERENCE')) ?></a>
    <a href="<?= htmlspecialchars(g($config, 'portada_btn_sec_link', 'le-tematik.php')) ?>" class="btn-secondary"><?= htmlspecialchars(g($config, 'portada_btn_sec_text', 'LE TEMATIK DESIGN')) ?></a>
</div>

</body>
</html>