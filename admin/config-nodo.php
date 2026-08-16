<?php
/**
 * CONFIG-NODO - Configuración AISLADA de un solo nodo
 * Muestra SOLO la viñeta del nodo pedido + flecha gris para volver
 */
session_start();
$baseDir = dirname(__DIR__);
$nodosFile = $baseDir . '/config/nodos.json';

// Registro de nodos (mismo orden que panel-nodos)
$registro = [
    'the-difference' => ['nombre'=>'THE DIFFERENCE','icono'=>'🌀','color'=>'#ff69b4','publica'=>'the-difference.php'],
    'log' => ['nombre'=>'LOG','icono'=>'📓','color'=>'#9c27b0','publica'=>'log.php'],
    'le-tematik' => ['nombre'=>'LE TEMATIK','icono'=>'🎨','color'=>'#fffc34','publica'=>'le-tematik.php'],
    'project-nada-brahma' => ['nombre'=>'PROJECT NADA BRAHMA','icono'=>'🕉️','color'=>'#00bcd4','publica'=>'project-nada-brahma.php'],
    'texvn' => ['nombre'=>'TEXVN','icono'=>'🔷','color'=>'#00bcd4','publica'=>'texvn.php'],
    'quantumlab' => ['nombre'=>'QUANTUM LAB','icono'=>'⚛️','color'=>'#8b5cf6','publica'=>'quantumlab.php'],
    'pensamiento-autista' => ['nombre'=>'PENSAMIENTO AUTISTA','icono'=>'🧩','color'=>'#10b981','publica'=>'pensamiento-autista.php'],
    'saiayin-do' => ['nombre'=>'SAIAYIN DO','icono'=>'🌸','color'=>'#ff69b4','publica'=>'saiayin-do.php'],
    'ars-tekne' => ['nombre'=>'ARS TEKNE','icono'=>'🏛️','color'=>'#ff69b4','publica'=>'ars-tekne.php'],
    'quiron-theatre' => ['nombre'=>'QUIRÓN THEATRE','icono'=>'🎭','color'=>'#f59e0b','publica'=>'quiron-theatre.php'],
    'opus-magnum' => ['nombre'=>'OPUS MAGNUM','icono'=>'🦁','color'=>'#fffc34','publica'=>'opus-magnum.php']
];

$nodo = $_GET['nodo'] ?? $_POST['nodo'] ?? 'log';
if (!isset($registro[$nodo])) $nodo = 'log';
$meta = $registro[$nodo];

// Cargar configuración guardada del nodo
$nodos = file_exists($nodosFile) ? json_decode(file_get_contents($nodosFile), true) : [];
$default = ['titulo'=>$meta['nombre'], 'descripcion'=>'', 'color'=>$meta['color'], 'activa'=>true];
$cfg = array_merge($default, $nodos[$nodo] ?? []);

$mensaje = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nodos[$nodo] = [
        'titulo' => $_POST['titulo'] ?? $cfg['titulo'],
        'descripcion' => $_POST['descripcion'] ?? '',
        'color' => $_POST['color'] ?? $cfg['color'],
        'activa' => isset($_POST['activa'])
    ];
    file_put_contents($nodosFile, json_encode($nodos, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $cfg = $nodos[$nodo];
    $mensaje = "✅ Configuración de {$meta['nombre']} guardada";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Configurar <?= htmlspecialchars($meta['nombre']) ?> | The Difference</title>
<style>
* { margin:0; padding:0; box-sizing:border-box; }
body { font-family: system-ui, sans-serif; background:#0f0f0f; color:#fff; min-height:100vh; padding:2rem; }
.container { max-width:700px; margin:0 auto; }
/* FLECHA GRIS ARRIBA IZQUIERDA */
.back { display:inline-flex; align-items:center; gap:0.4rem; color:#9ca3af; text-decoration:none; font-size:1.4rem; margin-bottom:1.5rem; transition:color 0.2s; }
.back:hover { color:#fff; }
.back span { font-size:0.85rem; }
.card { background:#1a1a1a; border:1px solid #333; border-top:3px solid <?= $cfg['color'] ?>; border-radius:12px; padding:2rem; }
.card h1 { color:<?= $cfg['color'] ?>; font-size:1.5rem; margin-bottom:0.25rem; }
.card .sub { color:#a0a0a0; font-size:0.85rem; margin-bottom:2rem; }
.form-group { margin-bottom:1.25rem; }
.form-group label { display:block; color:#a0a0a0; font-size:0.85rem; margin-bottom:0.5rem; }
.form-group input[type="text"], .form-group textarea { width:100%; padding:0.75rem; background:#252525; border:1px solid #333; border-radius:6px; color:#fff; font-family:inherit; }
.form-group input[type="color"] { width:100%; height:45px; border:1px solid #333; border-radius:6px; background:#252525; cursor:pointer; }
.form-group textarea { min-height:90px; resize:vertical; }
.checkbox-group { display:flex; align-items:center; gap:0.5rem; }
.checkbox-group input { width:auto; }
.alert { padding:0.9rem; border-radius:6px; background:rgba(16,185,129,0.2); border:1px solid #10b981; color:#10b981; margin-bottom:1.5rem; }
.btn-save { width:100%; padding:0.9rem; background:<?= $cfg['color'] ?>; color:#000; border:none; border-radius:6px; font-weight:bold; cursor:pointer; }
.btn-save:hover { filter:brightness(1.15); }
</style>
</head>
<body>
<div class="container">
    <!-- FLECHA GRIS PARA VOLVER -->
    <a href="panel-nodos.php" class="back">← <span>Volver al Sistema de Páginas</span></a>

    <div class="card">
        <h1><?= $meta['icono'] ?> Configurar: <?= htmlspecialchars($cfg['titulo']) ?></h1>
        <p class="sub">Viñeta exclusiva de <?= htmlspecialchars($meta['nombre']) ?> · archivo: <?= htmlspecialchars($meta['publica']) ?></p>

        <?php if ($mensaje): ?><div class="alert"><?= htmlspecialchars($mensaje) ?></div><?php endif; ?>

        <form method="POST">
            <input type="hidden" name="nodo" value="<?= $nodo ?>">
            <div class="form-group">
                <label>Título de la página</label>
                <input type="text" name="titulo" value="<?= htmlspecialchars($cfg['titulo']) ?>">
            </div>
            <div class="form-group">
                <label>Descripción / propósito</label>
                <textarea name="descripcion"><?= htmlspecialchars($cfg['descripcion']) ?></textarea>
            </div>
            <div class="form-group">
                <label>Color del nodo</label>
                <input type="color" name="color" value="<?= htmlspecialchars($cfg['color']) ?>">
            </div>
            <div class="form-group checkbox-group">
                <input type="checkbox" name="activa" id="activa" <?= $cfg['activa'] ? 'checked' : '' ?>>
                <label for="activa" style="margin:0;">Página activa</label>
            </div>
            <button type="submit" class="btn-save">💾 Guardar <?= htmlspecialchars($meta['nombre']) ?></button>
        </form>
    </div>
</div>
</body>
</html>