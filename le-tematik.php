<?php
$configFile = __DIR__ . '/config/settings.json';
$config = [];
if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
}

function getVal($c, $k, $d) {
    return isset($c[$k]) ? $c[$k] : $d;
}

// PREFIJO DE ESTA PÁGINA (Cámbialo en cada archivo: 'le_tematik_', 'texvn_', etc.)
$prefix = 'le-tematik.php';

$titleText = getVal($config, $prefix . 'title_text', 'LE TEMATIK');
$titleSize = getVal($config, $prefix . 'title_size', 60);
$titleColor = getVal($config, $prefix . 'title_color', '#ffffff');
$titleFont = getVal($config, $prefix . 'title_font', 'Arial Black');
$titleX = getVal($config, $prefix . 'title_position_x', 50);
$titleY = getVal($config, $prefix . 'title_position_y', 15);

$subtitleText = getVal($config, $prefix . 'subtitle_text', 'SUBTÍTULO');
$subtitleSize = getVal($config, $prefix . 'subtitle_size', 14);
$subtitleColor = getVal($config, $prefix . 'subtitle_color', '#a0a0a0');
$subtitleX = getVal($config, $prefix . 'subtitle_position_x', 50);
$subtitleY = getVal($config, $prefix . 'subtitle_position_y', 28);

$logoType = getVal($config, $prefix . 'logo_type', 'image');
$logoPath = getVal($config, $prefix . 'logo_path', 'images/logo-feeling-autistic.png');
$videoPath = getVal($config, $prefix . 'video_path', 'videos/video-le-tematik.mp4');
$logoSize = getVal($config, $prefix . 'logo_size', 40);
$logoX = getVal($config, $prefix . 'logo_position_x', 50);
$logoY = getVal($config, $prefix . 'logo_position_y', 45);

$btnText = getVal($config, $prefix . 'btn_main_text', 'LE TEMATIK');
$btnLink = getVal($config, $prefix . 'btn_main_link', '#');
$btnSize = getVal($config, $prefix . 'btn_main_size', 45);
$btnColor = getVal($config, $prefix . 'btn_main_color', '#ffffff');
$btnHover = getVal($config, $prefix . 'btn_main_hover', '#ff69b4');
$btnFont = getVal($config, $prefix . 'btn_main_font', 'Arial Black');
$btnX = getVal($config, $prefix . 'btn_main_position_x', 50);
$btnY = getVal($config, $prefix . 'btn_main_position_y', 65);

// Botones secundarios dinámicos
$btnSecItems = getVal($config, $prefix . 'btn_sec_items', 'LOG,LE TEMATIK,PROJECT NADA BRAHMA,TEXVN,QUANTUMLAB,PENSAMIENTO AUTISTA,SAIAYIN DO,ARS TEKNE,QUIRÓN THEATRE');
$btnSecSize = getVal($config, $prefix . 'btn_sec_size', 14);
$btnSecColor = getVal($config, $prefix . 'btn_sec_color', '#fffc34');
$btnSecHover = getVal($config, $prefix . 'btn_sec_hover_color', '#ffffff');
$btnSecGap = getVal($config, $prefix . 'btn_sec_gap', 10);

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

.title { position: absolute; left: <?= $titleX ?>%; top: <?= $titleY ?>%; transform: translate(-50%, -50%); font-size: <?= $titleSize ?>px; font-weight: 900; font-family: '<?= $titleFont ?>', sans-serif; color: <?= $titleColor ?>; text-transform: uppercase; letter-spacing: 5px; text-align: center; white-space: nowrap; animation: fadeInDown 1s ease both; }
.subtitle { position: absolute; left: <?= $subtitleX ?>%; top: <?= $subtitleY ?>%; transform: translate(-50%, -50%); font-size: <?= $subtitleSize ?>px; font-weight: 400; font-family: '<?= $titleFont ?>', sans-serif; color: <?= $subtitleColor ?>; text-transform: uppercase; letter-spacing: 2px; text-align: center; white-space: nowrap; animation: fadeIn 1s ease 0.3s both; }

.logo-container { position: absolute; left: <?= $logoX ?>%; top: <?= $logoY ?>%; transform: translate(-50%, -50%); width: <?= $logoSize ?>vh; height: <?= $logoSize ?>vh; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 0 30px rgba(255,105,180,0.5); overflow: hidden; animation: zoomIn 1.5s ease both; }
.logo-container img, .logo-container video { width: 100%; height: 100%; object-fit: cover; }

.btn-main { position: absolute; left: <?= $btnX ?>%; top: <?= $btnY ?>%; transform: translate(-50%, -50%); font-size: <?= $btnSize ?>px; font-weight: 900; font-family: '<?= $btnFont ?>', sans-serif; color: <?= $btnColor ?>; text-transform: uppercase; letter-spacing: 4px; border: 3px solid <?= $btnColor ?>; padding: 18px 35px; background: transparent; text-decoration: none; cursor: pointer; transition: all 0.3s ease; white-space: nowrap; animation: fadeInUp 1s ease 0.5s both; }
.btn-main:hover { color: <?= $btnHover ?>; border-color: <?= $btnHover ?>; transform: translate(-50%, -50%) scale(1.05); box-shadow: 0 0 20px rgba(255,105,180,0.5); }

.btn-secondary-container { position: absolute; left: 50%; top: 75%; transform: translate(-50%, -50%); display: flex; flex-wrap: wrap; justify-content: center; gap: <?= $btnSecGap ?>px; max-width: 80vw; }
.btn-secondary { font-size: <?= $btnSecSize ?>px; font-weight: 900; color: <?= $btnSecColor ?>; text-transform: uppercase; letter-spacing: 1px; border: 2px solid #ffffff; padding: 8px 16px; background: transparent; text-decoration: none; transition: all 0.3s ease; animation: fadeIn 0.8s ease 0.5s both; }
.btn-secondary:hover { color: <?= $btnSecHover ?>; border-color: <?= $btnSecHover ?>; transform: scale(1.05); box-shadow: 0 0 15px rgba(255,252,52,0.5); }

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
        <?php 
        $items = explode(',', $btnSecItems);
        foreach ($items as $item): 
            $item = trim($item);
            if (!empty($item)) {
                // Convierte "LE TEMATIK" a "le-tematik.php"
                $fileName = strtolower(str_replace(' ', '-', $item)) . '.php';
                echo '<a href="' . htmlspecialchars($fileName) . '" class="btn-secondary">' . htmlspecialchars($item) . '</a>';
            }
        endforeach; 
        ?>
    </div>
</div>
</body>
</html>