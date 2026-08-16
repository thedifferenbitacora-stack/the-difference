<?php
$configFile = __DIR__ . '/config/settings.json';
$defaults = [
    'menu_bg_color' => '#000000',
    'menu_title_text' => 'FEELING AUTISTIC',
    'menu_title_size' => 60,
    'menu_title_color' => '#ffffff',
    'menu_title_font' => 'Arial Black',
    'menu_title_position_x' => 50,
    'menu_title_position_y' => 15,
    'menu_subtitle_text' => 'NEURODIVERGENCE CREATIVE PHILOSOPHY PLATFORM',
    'menu_subtitle_size' => 14,
    'menu_subtitle_color' => '#a0a0a0',
    'menu_subtitle_font' => 'Arial Black',
    'menu_subtitle_position_x' => 50,
    'menu_subtitle_position_y' => 28,
    'menu_logo_path' => 'images/logo-feeling-autistic.png',
    'menu_logo_size' => 55,
    'menu_logo_position_x' => 50,
    'menu_logo_position_y' => 45,
    'menu_logo_zindex' => 10,
    'menu_logo_border_width' => 0,
    'menu_logo_border_color' => '#ffffff',
    'menu_logo_shadow' => 'none',
    'menu_btn_main_text' => 'THE DIFFERENCE',
    'menu_btn_main_link' => 'portada.php',
    'menu_btn_main_size' => 45,
    'menu_btn_main_color' => '#ffffff',
    'menu_btn_main_hover' => '#ff69b4', // AGREGADO
    'menu_btn_main_border_width' => 0,
    'menu_btn_main_border_color' => '#ffffff',
    'menu_btn_main_padding_v' => 18, // AGREGADO
    'menu_btn_main_padding_h' => 35, // AGREGADO
    'menu_btn_main_position_x' => 50,
    'menu_btn_main_position_y' => 65,
    'menu_btn_sec_items' => 'LOG, LE TEMATIK, PROJECT NADA BRAHMA, TEXVN, QUANTUMLAB, PENSAMIENTO AUTISTA, SAIAYIN DO, ARS TEKNE, QUIRÓN THEATRE',
    'menu_btn_sec_size' => 14,
    'menu_btn_sec_color' => '#fffc34',
    'menu_btn_sec_hover' => '#ffffff', // AGREGADO
    'menu_btn_sec_border_width' => 0,
    'menu_btn_sec_border_color' => '#ffffff',
    'menu_btn_sec_padding_v' => 8, // AGREGADO
    'menu_btn_sec_padding_h' => 16, // AGREGADO
    'menu_btn_sec_position_x' => 50,
    'menu_btn_sec_position_y' => 80,
    'menu_btn_bottom_text' => 'LE TEMATIK DESIGN',
    'menu_btn_bottom_link' => 'le-tematik.php',
    'menu_btn_bottom_size' => 14,
    'menu_btn_bottom_color' => '#ff69b4',
    'menu_btn_bottom_hover' => '#ffffff',
    'menu_btn_bottom_border_width' => 0,
    'menu_btn_bottom_border_color' => '#ff69b4',
    'menu_btn_bottom_padding_v' => 8, // AGREGADO
    'menu_btn_bottom_padding_h' => 20, // AGREGADO
    'menu_btn_bottom_position_x' => 50,
    'menu_btn_bottom_position_y' => 95,
    'menu_eye_type' => 'image',
    'menu_eye_path' => 'images/eye-bg.png',
    'menu_eye_size' => 55,
    'menu_eye_position_x' => 50,
    'menu_eye_position_y' => 45,
    'menu_eye_border_radius' => 50,
    'menu_eye_object_fit' => 'contain',
    'menu_eye_shadow' => '0 0 40px rgba(255,105,180,0.6)',
    'menu_eye_zindex' => 9,
    'menu_eye_animation' => 'zoomIn',
    // PREPARACIÓN PARA LA SIGUIENTE ETAPA (VIDEO AL HACER CLIC)
    'menu_eye_click_action' => 'none', // Opciones: 'none', 'play_video'
    'menu_eye_video_path' => '',
    // CONFIGURACIÓN DE PÁGINA (VIÑETA)
    'menu_page_scroll_enabled' => true,
];

$config = file_exists($configFile) ? array_merge($defaults, json_decode(file_get_contents($configFile), true) ?: []) : $defaults;

function g($c, $k, $d = '') { return $c[$k] ?? $d; }

$btnSecItemsRaw = g($config, 'menu_btn_sec_items', '');
$btnSecItems = array_filter(array_map('trim', explode(',', $btnSecItemsRaw)));

$scrollEnabled = g($config, 'menu_page_scroll_enabled', true);
$scrollCSS = $scrollEnabled ? 'auto' : 'hidden';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars(g($config, 'menu_title_text', 'Menú')) ?></title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
html, body { 
    background: <?= g($config, 'menu_bg_color', '#000000') ?>; 
    font-family: 'Arial Black', sans-serif;
    overflow-x: hidden;
    overflow-y: <?= $scrollCSS ?>; /* CONTROL DE SCROLL DESDE EL PANEL */
    width: 100vw;
    min-height: 100vh;
}

.main-container { 
    position: relative; 
    width: 100vw; 
    min-height: 100vh;
    padding-bottom: 50px;
}

.eye-container {
    position: absolute;
    left: <?= g($config, 'menu_eye_position_x', 50) ?>%;
    top: <?= g($config, 'menu_eye_position_y', 45) ?>%;
    transform: translate(-50%, -50%);
    width: <?= g($config, 'menu_eye_size', 55) ?>vh;
    height: <?= g($config, 'menu_eye_size', 55) ?>vh;
    border-radius: <?= g($config, 'menu_eye_border_radius', 50) ?>%;
    overflow: visible;
    z-index: <?= g($config, 'menu_eye_zindex', 9) ?>;
    animation: <?= g($config, 'menu_eye_animation', 'zoomIn') ?> 1.5s ease both;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: <?= g($config, 'menu_eye_click_action', 'none') !== 'none' ? 'pointer' : 'default' ?>;
    <?php if (g($config, 'menu_eye_shadow', '') !== 'none'): ?>
    box-shadow: <?= g($config, 'menu_eye_shadow', '0 0 40px rgba(255,105,180,0.6)') ?>;
    <?php endif; ?>
}
.eye-container img {
    width: 100%;
    height: 100%;
    object-fit: <?= g($config, 'menu_eye_object_fit', 'contain') ?>;
    border-radius: <?= g($config, 'menu_eye_border_radius', 50) ?>%;
}

.logo-container {
    position: absolute;
    left: <?= g($config, 'menu_logo_position_x', 50) ?>%;
    top: <?= g($config, 'menu_logo_position_y', 45) ?>%;
    transform: translate(-50%, -50%);
    width: <?= g($config, 'menu_logo_size', 55) ?>vh;
    height: <?= g($config, 'menu_logo_size', 55) ?>vh;
    border-radius: 50%;
    overflow: visible;
    z-index: <?= g($config, 'menu_logo_zindex', 10) ?>;
    animation: zoomIn 1.5s ease 0.5s both;
    <?php 
    $logoBorderWidth = g($config, 'menu_logo_border_width', 0);
    if ($logoBorderWidth > 0): 
    ?>
    border: <?= $logoBorderWidth ?>px solid <?= g($config, 'menu_logo_border_color', '#ffffff') ?>;
    <?php endif; ?>
    <?php if (g($config, 'menu_logo_shadow', '') !== 'none'): ?>
    box-shadow: <?= g($config, 'menu_logo_shadow', 'none') ?>;
    <?php endif; ?>
}
.logo-container img { 
    width: 100%; 
    height: 100%; 
    object-fit: cover; 
    border-radius: 50%;
}

.title {
    position: absolute;
    left: <?= g($config, 'menu_title_position_x', 50) ?>%;
    top: <?= g($config, 'menu_title_position_y', 15) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'menu_title_size', 60) ?>px;
    font-family: '<?= g($config, 'menu_title_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'menu_title_color', '#ffffff') ?>;
    text-transform: uppercase;
    letter-spacing: 5px;
    text-align: center;
    z-index: 10;
    animation: fadeInDown 1s ease both;
}

.subtitle {
    position: absolute;
    left: <?= g($config, 'menu_subtitle_position_x', 50) ?>%;
    top: <?= g($config, 'menu_subtitle_position_y', 28) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'menu_subtitle_size', 14) ?>px;
    font-family: '<?= g($config, 'menu_subtitle_font', 'Arial Black') ?>', sans-serif;
    color: <?= g($config, 'menu_subtitle_color', '#a0a0a0') ?>;
    text-transform: uppercase;
    letter-spacing: 2px;
    text-align: center;
    z-index: 10;
    animation: fadeIn 1s ease 0.3s both;
}

.btn-main {
    position: absolute;
    left: <?= g($config, 'menu_btn_main_position_x', 50) ?>%;
    top: <?= g($config, 'menu_btn_main_position_y', 65) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'menu_btn_main_size', 45) ?>px;
    font-family: 'Arial Black', sans-serif;
    color: <?= g($config, 'menu_btn_main_color', '#ffffff') ?>;
    text-transform: uppercase;
    letter-spacing: 4px;
    <?php 
    $btnMainBorderWidth = g($config, 'menu_btn_main_border_width', 0);
    if ($btnMainBorderWidth > 0): 
    ?>
    border: <?= $btnMainBorderWidth ?>px solid <?= g($config, 'menu_btn_main_border_color', '#ffffff') ?>;
    <?php else: ?>
    border: none;
    <?php endif; ?>
    /* PADDING LEYENDO DEL PANEL */
    padding: <?= g($config, 'menu_btn_main_padding_v', 18) ?>px <?= g($config, 'menu_btn_main_padding_h', 35) ?>px;
    background: transparent;
    text-decoration: none;
    cursor: pointer;
    z-index: 10;
    animation: fadeInUp 1s ease 0.8s both;
    transition: all 0.3s ease;
}
.btn-main:hover {
    color: <?= g($config, 'menu_btn_main_hover', '#ff69b4') ?>;
    <?php if ($btnMainBorderWidth > 0): ?>
    border-color: <?= g($config, 'menu_btn_main_hover', '#ff69b4') ?>;
    <?php endif; ?>
    transform: translate(-50%, -50%) scale(1.05);
    box-shadow: 0 0 20px rgba(255,105,180,0.5);
}

.btn-secondary-container {
    position: absolute;
    left: <?= g($config, 'menu_btn_sec_position_x', 50) ?>%;
    top: <?= g($config, 'menu_btn_sec_position_y', 80) ?>%;
    transform: translate(-50%, -50%);
    display: flex;
    flex-wrap: wrap;
    justify-content: center;
    gap: 12px;
    max-width: 90vw;
    z-index: 10;
    animation: fadeIn 1s ease 1s both;
}

.btn-secondary {
    font-size: <?= g($config, 'menu_btn_sec_size', 14) ?>px;
    font-family: 'Arial Black', sans-serif;
    color: <?= g($config, 'menu_btn_sec_color', '#fffc34') ?>;
    text-transform: uppercase;
    letter-spacing: 1px;
    <?php 
    $btnSecBorderWidth = g($config, 'menu_btn_sec_border_width', 0);
    if ($btnSecBorderWidth > 0): 
    ?>
    border: <?= $btnSecBorderWidth ?>px solid <?= g($config, 'menu_btn_sec_border_color', '#ffffff') ?>;
    <?php else: ?>
    border: none;
    <?php endif; ?>
    /* PADDING LEYENDO DEL PANEL */
    padding: <?= g($config, 'menu_btn_sec_padding_v', 8) ?>px <?= g($config, 'menu_btn_sec_padding_h', 16) ?>px;
    background: transparent;
    text-decoration: none;
    cursor: pointer;
    transition: all 0.3s ease;
}
.btn-secondary:hover {
    /* HOVER LEYENDO DEL PANEL */
    color: <?= g($config, 'menu_btn_sec_hover', '#ffffff') ?>;
    <?php if ($btnSecBorderWidth > 0): ?>
    border-color: <?= g($config, 'menu_btn_sec_hover', '#ffffff') ?>;
    <?php endif; ?>
    transform: scale(1.05);
    box-shadow: 0 0 15px rgba(255,252,52,0.5);
}

.btn-bottom {
    position: absolute;
    left: <?= g($config, 'menu_btn_bottom_position_x', 50) ?>%;
    top: <?= g($config, 'menu_btn_bottom_position_y', 95) ?>%;
    transform: translate(-50%, -50%);
    font-size: <?= g($config, 'menu_btn_bottom_size', 14) ?>px;
    font-family: 'Arial Black', sans-serif;
    color: <?= g($config, 'menu_btn_bottom_color', '#ff69b4') ?>;
    text-transform: uppercase;
    letter-spacing: 2px;
    <?php 
    $btnBottomBorderWidth = g($config, 'menu_btn_bottom_border_width', 0);
    if ($btnBottomBorderWidth > 0): 
    ?>
    border: <?= $btnBottomBorderWidth ?>px solid <?= g($config, 'menu_btn_bottom_border_color', '#ff69b4') ?>;
    <?php else: ?>
    border: none;
    <?php endif; ?>
    padding: <?= g($config, 'menu_btn_bottom_padding_v', 8) ?>px <?= g($config, 'menu_btn_bottom_padding_h', 20) ?>px;
    background: transparent;
    text-decoration: none;
    cursor: pointer;
    z-index: 10;
    animation: fadeInUp 1s ease 1.2s both;
    transition: all 0.3s ease;
}
.btn-bottom:hover {
    color: <?= g($config, 'menu_btn_bottom_hover', '#ffffff') ?>;
    <?php if ($btnBottomBorderWidth > 0): ?>
    border-color: <?= g($config, 'menu_btn_bottom_hover', '#ffffff') ?>;
    <?php endif; ?>
    transform: translate(-50%, -50%) scale(1.05);
}

.link-inicio {
    position: fixed;
    top: 20px;
    left: 20px;
    z-index: 9999;
    font-family: 'Arial Black', sans-serif;
    font-size: 12px;
    font-weight: 900;
    color: rgba(255,255,255,0.5);
    text-decoration: none;
    letter-spacing: 2px;
    text-transform: uppercase;
    padding: 8px 12px;
    border: 1px solid rgba(255,255,255,0.2);
    border-radius: 4px;
    transition: all 0.3s ease;
}
.link-inicio:hover {
    color: #ffffff;
    border-color: #ff69b4;
    background: rgba(255,105,180,0.1);
}

@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -50%) translateY(-30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }

@media (max-width: 768px) {
    .title { font-size: <?= max(28, g($config, 'menu_title_size', 60) * 0.5) ?>px !important; }
    .subtitle { font-size: 11px !important; max-width: 90vw !important; }
    .eye-container, .logo-container { 
        width: <?= max(30, g($config, 'menu_eye_size', 55) * 0.6) ?>vh !important; 
        height: <?= max(30, g($config, 'menu_eye_size', 55) * 0.6) ?>vh !important; 
    }
    .btn-main { font-size: <?= max(20, g($config, 'menu_btn_main_size', 45) * 0.6) ?>px !important; }
    .btn-secondary { font-size: 11px !important; }
    .btn-bottom { font-size: 11px !important; }
}
</style>
</head>
<body>

<a href="portada.php" class="link-inicio">← Inicio</a>

<div class="main-container">
    <div class="title"><?= htmlspecialchars(g($config, 'menu_title_text', 'FEELING AUTISTIC')) ?></div>
    <div class="subtitle"><?= htmlspecialchars(g($config, 'menu_subtitle_text', 'NEURODIVERGENCE CREATIVE PHILOSOPHY PLATFORM')) ?></div>
    
    <?php if (g($config, 'menu_eye_type', 'image') !== 'none' && !empty(g($config, 'menu_eye_path'))): ?>
    <div class="eye-container" id="menuEyeContainer">
        <img src="<?= g($config, 'menu_eye_path', 'images/eye-bg.png') ?>" alt="Ojo">
    </div>
    <?php endif; ?>
    
    <div class="logo-container">
        <img src="<?= g($config, 'menu_logo_path', 'images/logo-feeling-autistic.png') ?>" alt="Logo">
    </div>
    
    <a href="<?= htmlspecialchars(g($config, 'menu_btn_main_link', 'portada.php')) ?>" class="btn-main"><?= htmlspecialchars(g($config, 'menu_btn_main_text', 'THE DIFFERENCE')) ?></a>
    
    <div class="btn-secondary-container">
        <?php foreach ($btnSecItems as $item): ?>
            <?php if (!empty(trim($item))): ?>
            <a href="<?= strtolower(str_replace(' ', '-', trim($item))) ?>.php" class="btn-secondary">
                <?= htmlspecialchars(trim($item)) ?>
            </a>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
    
    <?php if (!empty(g($config, 'menu_btn_bottom_text'))): ?>
    <a href="<?= htmlspecialchars(g($config, 'menu_btn_bottom_link', 'le-tematik.php')) ?>" class="btn-bottom"><?= htmlspecialchars(g($config, 'menu_btn_bottom_text', 'LE TEMATIK DESIGN')) ?></a>
    <?php endif; ?>
</div>

<!-- PREPARACIÓN PARA LA SIGUIENTE ETAPA: LÓGICA DE VIDEO AL HACER CLIC -->
<script>
const eyeContainer = document.getElementById('menuEyeContainer');
const eyeClickAction = "<?= g($config, 'menu_eye_click_action', 'none') ?>";
const eyeVideoPath = "<?= g($config, 'menu_eye_video_path', '') ?>";

if (eyeContainer && eyeClickAction === 'play_video' && eyeVideoPath) {
    eyeContainer.addEventListener('click', function() {
        // Aquí irá la lógica de la siguiente etapa: reemplazar la imagen con el video del ojo abriéndose
        console.log("Preparado para reproducir: " + eyeVideoPath);
        // Ejemplo futuro: this.innerHTML = '<video src="' + eyeVideoPath + '" autoplay></video>';
    });
}
</script>

</body>
</html>