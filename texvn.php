<?php
$configFile = __DIR__ . '/config/settings.json';
$config = [];
if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
}

// Función auxiliar
function getVal($c, $k, $d) {
    return isset($c[$k]) ? $c[$k] : $d;
}

$prefix = 'texvn_';

// Obtener todos los parámetros con el prefijo le_tematik_
$titleText = getVal($config, $prefix . 'title_text', 'LE TEMATIK');
$titleSize = getVal($config, $prefix . 'title_size', 60);
$titleColor = getVal($config, $prefix . 'title_color', '#ffffff');
$titleFont = getVal($config, $prefix . 'title_font', 'Arial Black');
$titleWeight = getVal($config, $prefix . 'title_font_weight', 900);
$titleSpacing = getVal($config, $prefix . 'title_letter_spacing', 5);
$titleTransform = getVal($config, $prefix . 'title_text_transform', 'uppercase');
$titleX = getVal($config, $prefix . 'title_position_x', 50);
$titleY = getVal($config, $prefix . 'title_position_y', 15);
$titleAnim = getVal($config, $prefix . 'title_animation', 'fadeInDown');
$titleDuration = getVal($config, $prefix . 'title_anim_duration', 1);
$titleDelay = getVal($config, $prefix . 'title_anim_delay', 0);

$subtitleText = getVal($config, $prefix . 'subtitle_text', 'DISEÑO Y FILOSOFÍA CREATIVA');
$subtitleSize = getVal($config, $prefix . 'subtitle_size', 14);
$subtitleColor = getVal($config, $prefix . 'subtitle_color', '#a0a0a0');
$subtitleFont = getVal($config, $prefix . 'subtitle_font', 'Arial Black');
$subtitleWeight = getVal($config, $prefix . 'subtitle_font_weight', 400);
$subtitleSpacing = getVal($config, $prefix . 'subtitle_letter_spacing', 2);
$subtitleTransform = getVal($config, $prefix . 'subtitle_text_transform', 'uppercase');
$subtitleX = getVal($config, $prefix . 'subtitle_position_x', 50);
$subtitleY = getVal($config, $prefix . 'subtitle_position_y', 28);
$subtitleAnim = getVal($config, $prefix . 'subtitle_animation', 'fadeIn');
$subtitleDuration = getVal($config, $prefix . 'subtitle_anim_duration', 1);
$subtitleDelay = getVal($config, $prefix . 'subtitle_anim_delay', 0.3);

$logoType = getVal($config, $prefix . 'logo_type', 'image');
$logoPath = getVal($config, $prefix . 'logo_path', 'images/logo-feeling-autistic.png');
$videoPath = getVal($config, $prefix . 'video_path', 'videos/video-le-tematik.mp4');
$logoSize = getVal($config, $prefix . 'logo_size', 40);
$logoRadius = getVal($config, $prefix . 'logo_border_radius', 50);
$logoWidth = getVal($config, $prefix . 'logo_border_width', 3);
$logoColor = getVal($config, $prefix . 'logo_border_color', '#ffffff');
$logoShadow = getVal($config, $prefix . 'logo_shadow', '0 0 30px rgba(255,105,180,0.5)');
$logoX = getVal($config, $prefix . 'logo_position_x', 50);
$logoY = getVal($config, $prefix . 'logo_position_y', 45);
$logoAnim = getVal($config, $prefix . 'logo_animation', 'zoomIn');
$logoDuration = getVal($config, $prefix . 'logo_anim_duration', 1.5);
$logoDelay = getVal($config, $prefix . 'logo_anim_delay', 0);

$btnText = getVal($config, $prefix . 'btn_main_text', 'LE TEMATIK');
$btnLink = getVal($config, $prefix . 'btn_main_link', '#');
$btnSize = getVal($config, $prefix . 'btn_main_size', 45);
$btnColor = getVal($config, $prefix . 'btn_main_color', '#ffffff');
$btnHover = getVal($config, $prefix . 'btn_main_hover', '#ff69b4');
$btnFont = getVal($config, $prefix . 'btn_main_font', 'Arial Black');
$btnWeight = getVal($config, $prefix . 'btn_main_font_weight', 900);
$btnSpacing = getVal($config, $prefix . 'btn_main_letter_spacing', 4);
$btnTransform = getVal($config, $prefix . 'btn_main_text_transform', 'uppercase');
$btnBorderWidth = getVal($config, $prefix . 'btn_main_border_width', 3);
$btnBorderColor = getVal($config, $prefix . 'btn_main_border_color', '#ffffff');
$btnRadius = getVal($config, $prefix . 'btn_main_border_radius', 0);
$btnPaddingV = getVal($config, $prefix . 'btn_main_padding_v', 18);
$btnPaddingH = getVal($config, $prefix . 'btn_main_padding_h', 35);
$btnShadowHover = getVal($config, $prefix . 'btn_main_shadow_hover', '0 0 20px rgba(255,105,180,0.5)');
$btnTransformHover = getVal($config, $prefix . 'btn_main_transform_hover', 'scale(1.05)');
$btnAnim = getVal($config, $prefix . 'btn_main_animation', 'fadeInUp');
$btnDuration = getVal($config, $prefix . 'btn_main_anim_duration', 1);
$btnDelay = getVal($config, $prefix . 'btn_main_anim_delay', 0.5);
$btnX = getVal($config, $prefix . 'btn_main_position_x', 50);
$btnY = getVal($config, $prefix . 'btn_main_position_y', 65);

$bgColor = getVal($config, 'bg_color', '#000000');
$googleFonts = getVal($config, 'google_fonts', 'Arial Black,Roboto');
$fonts = explode(',', $googleFonts);
$googleFontsUrl = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', array_map(function($f) {
    return str_replace(' ', '+', trim($f)) . ':wght@100;200;300;400;500;600;700;800;900';
}, $fonts)) . '&display=swap';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titleText) ?> - THE DIFFERENCE</title>
<link href="<?= $googleFontsUrl ?>" rel="stylesheet">
<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -50%) translateY(-30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { background: <?= $bgColor ?>; min-height: 100vh; font-family: '<?= $btnFont ?>', sans-serif; overflow: hidden; }
.main-container { width: 100vw; height: 100vh; position: relative; }
.title { position: absolute; left: <?= $titleX ?>%; top: <?= $titleY ?>%; transform: translate(-50%, -50%); font-size: <?= $titleSize ?>px; font-weight: <?= $titleWeight ?>; font-family: '<?= $titleFont ?>', sans-serif; color: <?= $titleColor ?>; text-transform: <?= $titleTransform ?>; letter-spacing: <?= $titleSpacing ?>px; text-align: center; white-space: nowrap; animation: <?= $titleAnim ?> <?= $titleDuration ?>s ease <?= $titleDelay ?>s both; }
.subtitle { position: absolute; left: <?= $subtitleX ?>%; top: <?= $subtitleY ?>%; transform: translate(-50%, -50%); font-size: <?= $subtitleSize ?>px; font-weight: <?= $subtitleWeight ?>; font-family: '<?= $subtitleFont ?>', sans-serif; color: <?= $subtitleColor ?>; text-transform: <?= $subtitleTransform ?>; letter-spacing: <?= $subtitleSpacing ?>px; text-align: center; white-space: nowrap; animation: <?= $subtitleAnim ?> <?= $subtitleDuration ?>s ease <?= $subtitleDelay ?>s both; }
.logo-container { position: absolute; left: <?= $logoX ?>%; top: <?= $logoY ?>%; transform: translate(-50%, -50%); width: <?= $logoSize ?>vh; height: <?= $logoSize ?>vh; border-radius: <?= $logoRadius ?>%; border: <?= $logoWidth ?>px solid <?= $logoColor ?>; box-shadow: <?= $logoShadow ?>; overflow: hidden; animation: <?= $logoAnim ?> <?= $logoDuration ?>s ease <?= $logoDelay ?>s both; }
.logo-container img, .logo-container video { width: 100%; height: 100%; object-fit: cover; }
.btn-main { position: absolute; left: <?= $btnX ?>%; top: <?= $btnY ?>%; transform: translate(-50%, -50%); font-size: <?= $btnSize ?>px; font-weight: <?= $btnWeight ?>; font-family: '<?= $btnFont ?>', sans-serif; color: <?= $btnColor ?>; text-transform: <?= $btnTransform ?>; letter-spacing: <?= $btnSpacing ?>px; border: <?= $btnBorderWidth ?>px solid <?= $btnBorderColor ?>; border-radius: <?= $btnRadius ?>px; padding: <?= $btnPaddingV ?>px <?= $btnPaddingH ?>px; background: transparent; text-decoration: none; cursor: pointer; transition: all 0.3s ease; white-space: nowrap; animation: <?= $btnAnim ?> <?= $btnDuration ?>s ease <?= $btnDelay ?>s both; }
.btn-main:hover { color: <?= $btnHover ?>; border-color: <?= $btnHover ?>; transform: translate(-50%, -50%) <?= $btnTransformHover ?>; box-shadow: <?= $btnShadowHover ?>; }
.btn-secondary-container { position: absolute; left: 50%; top: 75%; transform: translate(-50%, -50%); display: flex; flex-wrap: wrap; justify-content: center; gap: 10px; max-width: 80vw; }
.btn-secondary { font-size: 14px; font-weight: 900; color: #fffc34; text-transform: uppercase; letter-spacing: 1px; border: 2px solid #ffffff; padding: 8px 16px; background: transparent; text-decoration: none; transition: all 0.3s ease; animation: fadeIn 0.8s ease 0.5s both; }
.btn-secondary:hover { color: #ffffff; border-color: #ffffff; transform: scale(1.05); box-shadow: 0 0 15px rgba(255,252,52,0.5); }
.back-link { position: fixed; top: 20px; left: 20px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-family: system-ui, sans-serif; border: 1px solid rgba(255,255,255,0.2); transition: all 0.2s; z-index: 100; }
.back-link:hover { background: rgba(255,255,255,0.2); color: white; }
.menu-link { position: fixed; top: 20px; right: 20px; background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6); padding: 8px 16px; border-radius: 4px; text-decoration: none; font-size: 12px; font-family: system-ui, sans-serif; border: 1px solid rgba(255,255,255,0.2); transition: all 0.2s; z-index: 100; }
.menu-link:hover { background: rgba(255,255,255,0.2); color: white; }
</style>
</head>
<body>
<a href="menu.php" class="back-link">← Volver al Menú</a>
<a href="menu.php" class="menu-link">📋 Menú</a>
<div class="main-container">
    <div class="title"><?= htmlspecialchars($titleText) ?></div>
    <div class="subtitle"><?= htmlspecialchars($subtitleText) ?></div>
    <div class="logo-container">
        <?php if ($logoType === 'video' && file_exists(__DIR__ . '/' . $videoPath)): ?>
            <video src="<?= $videoPath ?>" autoplay loop muted playsinline></video>
        <?php else: ?>
            <img src="<?= $logoPath ?>" alt="Logo">
        <?php endif; ?>
    </div>
    <a href="<?= htmlspecialchars($btnLink) ?>" class="btn-main"><?= htmlspecialchars($btnText) ?></a>
    <div class="btn-secondary-container">
        <a href="log.php" class="btn-secondary">LOG</a>
        <a href="le-tematik.php" class="btn-secondary">LE TEMATIK</a>
        <a href="project-nada-brahma.php" class="btn-secondary">PROJECT NADA BRAHMA</a>
        <a href="texvn.php" class="btn-secondary">TEXVN</a>
        <a href="quantumlab.php" class="btn-secondary">QUANTUMLAB</a>
        <a href="pensamiento-autista.php" class="btn-secondary">PENSAMIENTO AUTISTA</a>
        <a href="saiayin-do.php" class="btn-secondary">SAIAYIN DO</a>
        <a href="ars-tekne.php" class="btn-secondary">ARS TEKNE</a>
        <a href="quiron-theatre.php" class="btn-secondary">QUIRÓN THEATRE</a>
    </div>
</div>
</body>
</html>