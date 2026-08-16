<?php
$configFile = __DIR__ . '/config/settings.json';
$config = [];
if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
}
$bgColor = $config['bg_color'] ?? '#000000';
$googleFonts = $config['google_fonts'] ?? 'Arial Black,Roboto';
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
<title>LOG - THE DIFFERENCE</title>
<link href="<?= $googleFontsUrl ?>" rel="stylesheet">
<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -50%) translateY(-30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to { opacity: 1; transform: translate(-50%, -50%) scale(1); } }
* { margin: 0; padding: 0; box-sizing: border-box; }
body { background: <?= $bgColor ?>; min-height: 100vh; font-family: 'Arial Black', sans-serif; overflow: hidden; }
.main-container { width: 100vw; height: 100vh; position: relative; }
.title { position: absolute; left: 50%; top: 15%; transform: translate(-50%, -50%); font-size: 60px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: 5px; text-align: center; animation: fadeInDown 1s ease both; }
.subtitle { position: absolute; left: 50%; top: 28%; transform: translate(-50%, -50%); font-size: 14px; color: #a0a0a0; text-transform: uppercase; letter-spacing: 2px; text-align: center; animation: fadeIn 1s ease 0.3s both; }
.logo-container { position: absolute; left: 50%; top: 45%; transform: translate(-50%, -50%); width: 40vh; height: 40vh; border-radius: 50%; border: 3px solid #ffffff; box-shadow: 0 0 30px rgba(255,105,180,0.5); overflow: hidden; animation: zoomIn 1.5s ease both; }
.logo-container img { width: 100%; height: 100%; object-fit: cover; }
.btn-main { position: absolute; left: 50%; top: 65%; transform: translate(-50%, -50%); font-size: 45px; font-weight: 900; color: #ffffff; text-transform: uppercase; letter-spacing: 4px; border: 3px solid #ffffff; padding: 18px 35px; background: transparent; text-decoration: none; transition: all 0.3s ease; animation: fadeInUp 1s ease 0.5s both; }
.btn-main:hover { color: #ff69b4; border-color: #ff69b4; transform: translate(-50%, -50%) scale(1.05); box-shadow: 0 0 20px rgba(255,105,180,0.5); }
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
<div class="title">LOG</div>
<div class="subtitle">BITÁCORA DE CONCIENCIA Y PROCESO</div>
<div class="logo-container">
<img src="images/logo-feeling-autistic.png" alt="Logo">
</div>
<a href="#" class="btn-main">LOG</a>
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