<?php
// Obtener el nodo de la URL (?nodo=log, ?nodo=le-tematik, etc.)
$nodo_slug = $_GET['nodo'] ?? '';

// Mapeo de slugs a nombres y configuraciones
$nodos_config = [
    'log' => [
        'nombre' => 'LOG',
        'subtitulo' => 'BITÁCORA DE CONCIENCIA Y PROCESO',
        'logo_path' => 'images/logo-log.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'le-tematik' => [
        'nombre' => 'LE TEMATIK',
        'subtitulo' => 'DISEÑO Y ARTE VISUAL',
        'logo_path' => 'images/logo-le-tematik.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'project-nada-brahma' => [
        'nombre' => 'PROJECT NADA BRAHMA',
        'subtitulo' => 'DEPARTAMENTO TÉCNICO',
        'logo_path' => 'images/logo-nada-brahma.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'texvn' => [
        'nombre' => 'TEXVN',
        'subtitulo' => 'ARTE Y TÉCNICA INTEGRADA',
        'logo_path' => 'images/logo-texvn.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'quantumlab' => [
        'nombre' => 'QUANTUMLAB',
        'subtitulo' => 'INVESTIGACIÓN Y OTREDAD',
        'logo_path' => 'images/logo-quantumlab.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'pensamiento-autista' => [
        'nombre' => 'PENSAMIENTO AUTISTA',
        'subtitulo' => 'ZONA ONTOLÓGICA Y CAPACIDAD',
        'logo_path' => 'images/logo-pensamiento-autista.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'saiayin-do' => [
        'nombre' => 'SAIAYIN DO',
        'subtitulo' => 'CAMINO DE SUPERACIÓN',
        'logo_path' => 'images/logo-saiayin-do.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'ars-tekne' => [
        'nombre' => 'ARS TEKNE',
        'subtitulo' => 'ARTE Y TÉCNICA FILOSÓFICA',
        'logo_path' => 'images/logo-ars-tekne.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ],
    'quiron-theatre' => [
        'nombre' => 'QUIRÓN THEATRE',
        'subtitulo' => 'TEATRO SANADOR Y PERFORMANCE',
        'logo_path' => 'images/logo-quiron-theatre.png',
        'logo_fallback' => 'images/logo-feeling-autistic.png'
    ]
];

// Validar que el nodo exista
if (!isset($nodos_config[$nodo_slug])) {
    header('Location: menu.php');
    exit;
}

$nodo = $nodos_config[$nodo_slug];

// Cargar configuración global
$configFile = __DIR__ . '/config/settings.json';
$defaults = [
    'bg_color' => '#000000',
    'google_fonts' => 'Arial Black,Roboto,Playfair Display,Orbitron,Space Mono',
    // Configuración por nodo
    'nodo_title_size' => 60,
    'nodo_title_color' => '#ffffff',
    'nodo_title_font' => 'Arial Black',
    'nodo_title_font_weight' => 900,
    'nodo_title_letter_spacing' => 5,
    'nodo_title_text_transform' => 'uppercase',
    'nodo_title_position_x' => 50,
    'nodo_title_position_y' => 15,
    'nodo_title_animation' => 'fadeInDown',
    'nodo_title_anim_duration' => 1,
    'nodo_title_anim_delay' => 0,
    'nodo_subtitle_size' => 14,
    'nodo_subtitle_color' => '#a0a0a0',
    'nodo_subtitle_font' => 'Arial Black',
    'nodo_subtitle_font_weight' => 400,
    'nodo_subtitle_letter_spacing' => 2,
    'nodo_subtitle_text_transform' => 'uppercase',
    'nodo_subtitle_position_x' => 50,
    'nodo_subtitle_position_y' => 28,
    'nodo_subtitle_animation' => 'fadeIn',
    'nodo_subtitle_anim_duration' => 1,
    'nodo_subtitle_anim_delay' => 0.3,
    'nodo_logo_size' => 40,
    'nodo_logo_border_radius' => 50,
    'nodo_logo_border_width' => 3,
    'nodo_logo_border_color' => '#ffffff',
    'nodo_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
    'nodo_logo_position_x' => 50,
    'nodo_logo_position_y' => 45,
    'nodo_logo_animation' => 'zoomIn',
    'nodo_logo_anim_duration' => 1.5,
    'nodo_logo_anim_delay' => 0,
    'nodo_btn_main_size' => 45,
    'nodo_btn_main_color' => '#ffffff',
    'nodo_btn_main_hover' => '#ff69b4',
    'nodo_btn_main_border_width' => 3,
    'nodo_btn_main_border_color' => '#ffffff',
    'nodo_btn_main_border_radius' => 0,
    'nodo_btn_main_padding_v' => 18,
    'nodo_btn_main_padding_h' => 35,
    'nodo_btn_main_letter_spacing' => 4,
    'nodo_btn_main_font' => 'Arial Black',
    'nodo_btn_main_font_weight' => 900,
    'nodo_btn_main_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)',
    'nodo_btn_main_transform_hover' => 'scale(1.05)',
    'nodo_btn_main_animation' => 'fadeInUp',
    'nodo_btn_main_anim_duration' => 1,
    'nodo_btn_main_anim_delay' => 0.3,
    'nodo_btn_main_position_x' => 50,
    'nodo_btn_main_position_y' => 60,
    'nodo_btn_sec_size' => 14,
    'nodo_btn_sec_color' => '#fffc34',
    'nodo_btn_sec_hover_color' => '#ffffff',
    'nodo_btn_sec_border_width' => 2,
    'nodo_btn_sec_border_color' => '#ffffff',
    'nodo_btn_sec_border_radius' => 0,
    'nodo_btn_sec_padding_v' => 8,
    'nodo_btn_sec_padding_h' => 16,
    'nodo_btn_sec_letter_spacing' => 1,
    'nodo_btn_sec_font' => 'Arial Black',
    'nodo_btn_sec_font_weight' => 900,
    'nodo_btn_sec_text_transform' => 'uppercase',
    'nodo_btn_sec_shadow_hover' => '0 0 15px rgba(255,252,52,0.5)',
    'nodo_btn_sec_transform_hover' => 'scale(1.05)',
    'nodo_btn_sec_animation' => 'fadeIn',
    'nodo_btn_sec_anim_duration' => 0.8,
    'nodo_btn_sec_anim_delay' => 0.5,
    'nodo_btn_sec_gap' => 10,
    'nodo_btn_sec_position_x' => 50,
    'nodo_btn_sec_position_y' => 75
];

$config = $defaults;
if (file_exists($configFile)) {
    $saved = json_decode(file_get_contents($configFile), true);
    if (is_array($saved)) {
        // Cargar configuración específica del nodo si existe
        if (isset($saved['nodos'][$nodo_slug])) {
            $config = array_merge($defaults, $saved['nodos'][$nodo_slug]);
        } else {
            $config = array_merge($defaults, $saved);
        }
    }
}

// Determinar qué logo usar
$logo_final = file_exists(__DIR__ . '/' . $nodo['logo_path']) 
    ? $nodo['logo_path'] 
    : $nodo['logo_fallback'];

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
    <title><?= htmlspecialchars($nodo['nombre']) ?> - THE DIFFERENCE</title>
    <link href="<?= $googleFontsUrl ?>" rel="stylesheet">
    <style>
        @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
        @keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
        @keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -50%) translateY(-30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
        @keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { background: <?= $config['bg_color'] ?>; min-height: 100vh; font-family: '<?= $config['nodo_btn_main_font'] ?>', sans-serif; overflow: hidden; }
        .main-container { width: 100vw; height: 100vh; position: relative; }
        .title {
            position: absolute;
            left: <?= $config['nodo_title_position_x'] ?>%;
            top: <?= $config['nodo_title_position_y'] ?>%;
            transform: translate(-50%, -50%);
            font-size: <?= $config['nodo_title_size'] ?>px;
            font-weight: <?= $config['nodo_title_font_weight'] ?>;
            font-family: '<?= $config['nodo_title_font'] ?>', sans-serif;
            color: <?= $config['nodo_title_color'] ?>;
            text-transform: <?= $config['nodo_title_text_transform'] ?>;
            letter-spacing: <?= $config['nodo_title_letter_spacing'] ?>px;
            text-align: center;
            white-space: nowrap;
            animation: <?= $config['nodo_title_animation'] ?> <?= $config['nodo_title_anim_duration'] ?>s ease <?= $config['nodo_title_anim_delay'] ?>s both;
        }
        .subtitle {
            position: absolute;
            left: <?= $config['nodo_subtitle_position_x'] ?>%;
            top: <?= $config['nodo_subtitle_position_y'] ?>%;
            transform: translate(-50%, -50%);
            font-size: <?= $config['nodo_subtitle_size'] ?>px;
            font-weight: <?= $config['nodo_subtitle_font_weight'] ?>;
            font-family: '<?= $config['nodo_subtitle_font'] ?>', sans-serif;
            color: <?= $config['nodo_subtitle_color'] ?>;
            text-transform: <?= $config['nodo_subtitle_text_transform'] ?>;
            letter-spacing: <?= $config['nodo_subtitle_letter_spacing'] ?>px;
            text-align: center;
            white-space: nowrap;
            animation: <?= $config['nodo_subtitle_animation'] ?> <?= $config['nodo_subtitle_anim_duration'] ?>s ease <?= $config['nodo_subtitle_anim_delay'] ?>s both;
        }
        .logo-container {
            position: absolute;
            left: <?= $config['nodo_logo_position_x'] ?>%;
            top: <?= $config['nodo_logo_position_y'] ?>%;
            transform: translate(-50%, -50%);
            width: <?= $config['nodo_logo_size'] ?>vh;
            height: <?= $config['nodo_logo_size'] ?>vh;
            border-radius: <?= $config['nodo_logo_border_radius'] ?>%;
            border: <?= $config['nodo_logo_border_width'] ?>px solid <?= $config['nodo_logo_border_color'] ?>;
            box-shadow: <?= $config['nodo_logo_shadow'] ?>;
            overflow: hidden;
            animation: <?= $config['nodo_logo_animation'] ?> <?= $config['nodo_logo_anim_duration'] ?>s ease <?= $config['nodo_logo_anim_delay'] ?>s both;
        }
        .logo-container img { width: 100%; height: 100%; object-fit: cover; }
        .btn-main {
            position: absolute;
            left: <?= $config['nodo_btn_main_position_x'] ?>%;
            top: <?= $config['nodo_btn_main_position_y'] ?>%;
            transform: translate(-50%, -50%);
            font-size: <?= $config['nodo_btn_main_size'] ?>px;
            font-weight: <?= $config['nodo_btn_main_font_weight'] ?>;
            font-family: '<?= $config['nodo_btn_main_font'] ?>', sans-serif;
            color: <?= $config['nodo_btn_main_color'] ?>;
            text-transform: uppercase;
            letter-spacing: <?= $config['nodo_btn_main_letter_spacing'] ?>px;
            border: <?= $config['nodo_btn_main_border_width'] ?>px solid <?= $config['nodo_btn_main_border_color'] ?>;
            border-radius: <?= $config['nodo_btn_main_border_radius'] ?>px;
            padding: <?= $config['nodo_btn_main_padding_v'] ?>px <?= $config['nodo_btn_main_padding_h'] ?>px;
            background: transparent;
            text-decoration: none;
            cursor: pointer;
            transition: all 0.3s ease;
            white-space: nowrap;
            animation: <?= $config['nodo_btn_main_animation'] ?> <?= $config['nodo_btn_main_anim_duration'] ?>s ease <?= $config['nodo_btn_main_anim_delay'] ?>s both;
        }
        .btn-main:hover {
            color: <?= $config['nodo_btn_main_hover'] ?>;
            border-color: <?= $config['nodo_btn_main_hover'] ?>;
            transform: translate(-50%, -50%) <?= $config['nodo_btn_main_transform_hover'] ?>;
            box-shadow: <?= $config['nodo_btn_main_shadow_hover'] ?>;
        }
        .btn-secondary-container {
            position: absolute;
            left: <?= $config['nodo_btn_sec_position_x'] ?>%;
            top: <?= $config['nodo_btn_sec_position_y'] ?>%;
            transform: translate(-50%, -50%);
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: <?= $config['nodo_btn_sec_gap'] ?>px;
            max-width: 80vw;
        }
        .btn-secondary {
            font-size: <?= $config['nodo_btn_sec_size'] ?>px;
            font-weight: <?= $config['nodo_btn_sec_font_weight'] ?>;
            font-family: '<?= $config['nodo_btn_sec_font'] ?>', sans-serif;
            color: <?= $config['nodo_btn_sec_color'] ?>;
            text-transform: <?= $config['nodo_btn_sec_text_transform'] ?>;
            letter-spacing: <?= $config['nodo_btn_sec_letter_spacing'] ?>px;
            border: <?= $config['nodo_btn_sec_border_width'] ?>px solid <?= $config['nodo_btn_sec_border_color'] ?>;
            border-radius: <?= $config['nodo_btn_sec_border_radius'] ?>px;
            padding: <?= $config['nodo_btn_sec_padding_v'] ?>px <?= $config['nodo_btn_sec_padding_h'] ?>px;
            background: transparent;
            text-decoration: none;
            transition: all 0.3s ease;
            animation: <?= $config['nodo_btn_sec_animation'] ?> <?= $config['nodo_btn_sec_anim_duration'] ?>s ease <?= $config['nodo_btn_sec_anim_delay'] ?>s both;
        }
        .btn-secondary:hover {
            color: <?= $config['nodo_btn_sec_hover_color'] ?>;
            border-color: <?= $config['nodo_btn_sec_hover_color'] ?>;
            transform: <?= $config['nodo_btn_sec_transform_hover'] ?>;
            box-shadow: <?= $config['nodo_btn_sec_shadow_hover'] ?>;
        }
        .back-link {
            position: fixed; top: 20px; left: 20px;
            background: rgba(255,255,255,0.1); color: rgba(255,255,255,0.6);
            padding: 8px 16px; border-radius: 4px; text-decoration: none;
            font-size: 12px; font-family: system-ui, sans-serif;
            border: 1px solid rgba(255,255,255,0.2); transition: all 0.2s; z-index: 100;
        }
        .back-link:hover { background: rgba(255,255,255,0.2); color: white; }
    </style>
</head>
<body>
    <a href="menu.php" class="back-link">← Volver al Menú</a>
    <div class="main-container">
        <div class="title"><?= htmlspecialchars($nodo['nombre']) ?></div>
        <div class="subtitle"><?= htmlspecialchars($nodo['subtitulo']) ?></div>
        <div class="logo-container">
            <img src="<?= $logo_final ?>" alt="<?= htmlspecialchars($nodo['nombre']) ?>">
        </div>
        <a href="#" class="btn-main"><?= htmlspecialchars($nodo['nombre']) ?></a>
        <div class="btn-secondary-container">
            <a href="nodo.php?nodo=log" class="btn-secondary">LOG</a>
            <a href="nodo.php?nodo=le-tematik" class="btn-secondary">LE TEMATIK</a>
            <a href="nodo.php?nodo=project-nada-brahma" class="btn-secondary">PROJECT NADA BRAHMA</a>
            <a href="nodo.php?nodo=texvn" class="btn-secondary">TEXVN</a>
            <a href="nodo.php?nodo=quantumlab" class="btn-secondary">QUANTUMLAB</a>
            <a href="nodo.php?nodo=pensamiento-autista" class="btn-secondary">PENSAMIENTO AUTISTA</a>
            <a href="nodo.php?nodo=saiayin-do" class="btn-secondary">SAIAYIN DO</a>
            <a href="nodo.php?nodo=ars-tekne" class="btn-secondary">ARS TEKNE</a>
            <a href="nodo.php?nodo=quiron-theatre" class="btn-secondary">QUIRÓN THEATRE</a>
        </div>
    </div>
</body>
</html>