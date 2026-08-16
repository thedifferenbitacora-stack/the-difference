<?php
$configFile = __DIR__ . '/config/settings.json';
$defaults = [
    'log_title_text' => 'LOG',
    'log_subtitle_text' => 'BITÁCORA DE CONCIENCIA Y PROCESO',
    'log_logo_type' => 'image',
    'log_logo_path' => 'images/logo-feeling-autistic.png',
    'log_video_path' => 'videos/logo-log.mp4',
    'log_logo_size' => 40,
    'log_logo_border_radius' => 50,
    'log_logo_border_width' => 3,
    'log_logo_border_color' => '#ffffff',
    'log_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
    'log_logo_position_x' => 50,
    'log_logo_position_y' => 45,
    'log_logo_animation' => 'zoomIn',
    'log_btn_1_text' => 'NIÑES',
    'log_btn_1_link' => '#',
    'log_btn_2_text' => 'JUVENTUD',
    'log_btn_2_link' => '#',
    'log_btn_3_text' => 'ADULTES',
    'log_btn_3_link' => '#',
    'log_btn_4_text' => 'CV',
    'log_btn_4_link' => '#',
    'log_btn_size' => 14,
    'log_btn_color' => '#fffc34',
    'log_btn_hover_color' => '#ffffff',
    'log_btn_border_width' => 2,
    'log_btn_border_color' => '#ffffff',
    'log_btn_border_radius' => 0,
    'log_btn_padding_v' => 8,
    'log_btn_padding_h' => 16,
    'log_btn_gap' => 10,
    'log_btn_position_x' => 50,
    'log_btn_position_y' => 75,
    'bg_color' => '#000000',
    'google_fonts' => 'Arial Black,Roboto,Playfair Display'
];
$config = $defaults;
if (file_exists($configFile)) {
    $saved = json_decode(file_get_contents($configFile), true);
    if (is_array($saved)) $config = array_merge($defaults, $saved);
}
$fonts = explode(',', $config['google_fonts']);
$googleFontsUrl = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', array_map(function($f) {
    return str_replace(' ', '+', trim($f)) . ':wght@100;200;300;400;500;600;700;800;900';
}, $fonts)) . '&display=swap';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($config['log_title_text']) ?> - THE DIFFERENCE</title>
    <link href="<?= $googleFontsUrl ?>" rel="stylesheet">
    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
        @keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: <?= $config['bg_color'] ?>;
            min-height: 100vh;
            font-family: 'Arial Black', sans-serif;
            overflow: hidden;
        }
        .main-container { width: 100vw; height: 100vh; position: relative; }
        .title {
            position: absolute;
            left: 50%;
            top: 15%;
            transform: translate(-50%, -50%);
            font-size: 60px;
            font-weight: 900;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 5px;
            text-align: center;
            animation: fadeInDown 1s ease both;
        }
        .subtitle {
            position: absolute;
            left: 50%;
            top: 28%;
            transform: translate(-50%, -50%);
            font-size: 14px;
            color: #a0a0a0;
            text-transform: uppercase;
            letter-spacing: 2px;
            text-align: center;
            animation: fadeIn 1s ease 0.3s both;
        }
        .logo-container {
            position: absolute;
            left: <?= $config['log_logo_position_x'] ?>%;
            top: <?= $config['log_logo_position_y'] ?>%;
            transform: translate(-50%, -50%);
            width: <?= $config['log_logo_size'] ?>vh;
            height: <?= $config['log_logo_size'] ?>vh;
            border-radius: <?= $config['log_logo_border_radius'] ?>%;
            border: <?= $config['log_logo_border_width'] ?>px solid <?= $config['log_logo_border_color'] ?>;
            box-shadow: <?= $config['log_logo_shadow'] ?>;
            overflow: hidden;
            animation: <?= $config['log_logo_animation'] ?> 1.5s ease both;
        }
        .logo-container img, .logo-container video { width: 100%; height: 100%; object-fit: cover; }
        .btn-container {
            position: absolute;
            left: <?= $config['log_btn_position_x'] ?>%;
            top: <?= $config['log_btn_position_y'] ?>%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: <?= $config['log_btn_gap'] ?>px;
            max-width: 80vw;
        }
        .btn-custom {
            font-size: <?= $config['log_btn_size'] ?>px;
            font-weight: 900;
            color: <?= $config['log_btn_color'] ?>;
            text-transform: uppercase;
            letter-spacing: 1px;
            border: <?= $config['log_btn_border_width'] ?>px solid <?= $config['log_btn_border_color'] ?>;
            border-radius: <?= $config['log_btn_border_radius'] ?>px;
            padding: <?= $config['log_btn_padding_v'] ?>px <?= $config['log_btn_padding_h'] ?>px;
            background: transparent;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: fadeIn 0.8s ease 0.5s both;
        }
        .btn-custom:hover {
            color: <?= $config['log_btn_hover_color'] ?>;
            border-color: <?= $config['log_btn_hover_color'] ?>;
            transform: scale(1.05);
            box-shadow: 0 0 15px rgba(255,252,52,0.5);
        }
        .back-link {
            position: fixed; top: 20px; left: 20px;
            background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6);
            padding: 8px 16px; border-radius: 4px; text-decoration: none;
            font-size: 12px; border: 1px solid rgba(255,255,255,0.2);
            transition: all 0.2s; z-index: 100;
        }
        .back-link:hover { background: rgba(255,255,255,0.2); color: white; }
    </style>
</head>
<body>
    <a href="menu.php" class="back-link">← Volver al Menú</a>
    <div class="main-container">
        <div class="title"><?= htmlspecialchars($config['log_title_text']) ?></div>
        <div class="subtitle"><?= htmlspecialchars($config['log_subtitle_text']) ?></div>
        <div class="logo-container">
            <?php if ($config['log_logo_type'] === 'video' && file_exists(__DIR__ . '/' . $config['log_video_path'])): ?>
                <video src="<?= $config['log_video_path'] ?>" autoplay loop muted playsinline></video>
            <?php else: ?>
                <img src="<?= $config['log_logo_path'] ?>" alt="Logo LOG">
            <?php endif; ?>
        </div>
        <div class="btn-container">
            <?php if (!empty($config['log_btn_1_text'])): ?>
                <a href="<?= htmlspecialchars($config['log_btn_1_link']) ?>" class="btn-custom"><?= htmlspecialchars($config['log_btn_1_text']) ?></a>
            <?php endif; ?>
            <?php if (!empty($config['log_btn_2_text'])): ?>
                <a href="<?= htmlspecialchars($config['log_btn_2_link']) ?>" class="btn-custom"><?= htmlspecialchars($config['log_btn_2_text']) ?></a>
            <?php endif; ?>
            <?php if (!empty($config['log_btn_3_text'])): ?>
                <a href="<?= htmlspecialchars($config['log_btn_3_link']) ?>" class="btn-custom"><?= htmlspecialchars($config['log_btn_3_text']) ?></a>
            <?php endif; ?>
            <?php if (!empty($config['log_btn_4_text'])): ?>
                <a href="<?= htmlspecialchars($config['log_btn_4_link']) ?>" class="btn-custom"><?= htmlspecialchars($config['log_btn_4_text']) ?></a>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>