<?php
/**
 * SISTEMA DE PÁGINAS - THE DIFFERENCE
 * Listado de todas las páginas de los botones del menú
 * LOG → Quirón Theatre + Opus Magnum + The Difference
 * Cada "Configurar" abre SOLO la viñeta de ese nodo
 */
session_start();
$baseDir = dirname(__DIR__);

// ============================================
// REGISTRO DE NODOS: página pública + descripción
// ============================================
$nodos = [
    'the-difference' => ['nombre' => 'THE DIFFERENCE', 'icono' => '🌀', 'color' => '#ff69b4', 'publica' => 'the-difference.php', 'desc' => 'Página del botón general del menú.'],
    'log' => ['nombre' => 'LOG', 'icono' => '📓', 'color' => '#9c27b0', 'publica' => 'log.php', 'desc' => 'Bitácora Maestra. El Ouroboros.'],
    'le-tematik' => ['nombre' => 'LE TEMATIK', 'icono' => '🎨', 'color' => '#fffc34', 'publica' => 'le-tematik.php', 'desc' => 'Diseño y estética simbólica.'],
    'project-nada-brahma' => ['nombre' => 'PROJECT NADA BRAHMA', 'icono' => '🕉️', 'color' => '#00bcd4', 'publica' => 'project-nada-brahma.php', 'desc' => 'El sonido como origen.'],
    'texvn' => ['nombre' => 'TEXVN', 'icono' => '🔷', 'color' => '#00bcd4', 'publica' => 'texvn.php', 'desc' => 'Bitácora Técnica. 13 pasos.'],
    'quantumlab' => ['nombre' => 'QUANTUM LAB', 'icono' => '⚛️', 'color' => '#8b5cf6', 'publica' => 'quantumlab.php', 'desc' => 'Laboratorio cuántico.'],
    'pensamiento-autista' => ['nombre' => 'PENSAMIENTO AUTISTA', 'icono' => '🧩', 'color' => '#10b981', 'publica' => 'pensamiento-autista.php', 'desc' => 'Neurodivergencia creativa.'],
    'saiayin-do' => ['nombre' => 'SAIAYIN DO', 'icono' => '🌸', 'color' => '#ff69b4', 'publica' => 'saiayin-do.php', 'desc' => 'Bitácora Simbólica. 7 pasos.'],
    'ars-tekne' => ['nombre' => 'ARS TEKNE', 'icono' => '🏛️', 'color' => '#ff69b4', 'publica' => 'ars-tekne.php', 'desc' => 'Fundación Ars Tekne.'],
    'quiron-theatre' => ['nombre' => 'QUIRÓN THEATRE', 'icono' => '🎭', 'color' => '#f59e0b', 'publica' => 'quiron-theatre.php', 'desc' => 'Teatro Quirón. La herida que sana.'],
    'opus-magnum' => ['nombre' => 'OPUS MAGNUM', 'icono' => '🦁', 'color' => '#fffc34', 'publica' => 'opus-magnum.php', 'desc' => 'Bitácora Pedagógica. Sombra y luz.']
];

// Verificar existencia física de cada página pública
foreach ($nodos as $key => &$n) {
    $n['existe_publica'] = file_exists($baseDir . '/' . $n['publica']);
}
unset($n);

$total = count($nodos);
$existentes = count(array_filter($nodos, fn($n) => $n['existe_publica']));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Sistema de Páginas | The Difference</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body { font-family: system-ui, sans-serif; background: #0f0f0f; color: #fff; min-height: 100vh; padding: 2rem; }
.container { max-width: 1100px; margin: 0 auto; background: #1a1a1a; border: 1px solid #333; border-radius: 12px; padding: 2rem; }
h1 { font-size: 2rem; margin-bottom: 0.5rem; color: #ff69b4; }
.subtitle { color: #a0a0a0; margin-bottom: 2rem; }
.nav-links { display: flex; gap: 0.5rem; margin-bottom: 2rem; flex-wrap: wrap; padding-bottom: 1rem; border-bottom: 1px solid #333; }
.nav-links a { padding: 0.5rem 1rem; background: #252525; color: #a0a0a0; text-decoration: none; border-radius: 6px; border: 1px solid #333; font-size: 0.85rem; }
.nav-links a:hover { border-color: #ff69b4; color: #fff; }
.nav-links a.active { background: #ff69b4; color: #fff; border-color: #ff69b4; }
.nodos-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; }
.nodo-card { background: #252525; border: 1px solid #333; border-radius: 8px; padding: 1.5rem; transition: all 0.2s; display: flex; flex-direction: column; }
.nodo-card:hover { border-color: #ff69b4; transform: translateY(-2px); }
.nodo-card h3 { color: #ff69b4; margin-bottom: 0.5rem; font-size: 1.1rem; }
.nodo-card p { color: #a0a0a0; font-size: 0.85rem; margin-bottom: 1rem; flex: 1; }
.estado { font-size: 0.7rem; margin-bottom: 0.75rem; color: #666; }
.estado .ok { color: #10b981; }
.estado .no { color: #ef4444; }
.nodo-actions { display: flex; gap: 0.5rem; }
.nodo-actions a { flex: 1; text-align: center; padding: 0.5rem 0.75rem; color: #fff; text-decoration: none; border-radius: 4px; font-size: 0.8rem; transition: all 0.2s; }
.btn-ver { background: #10b981; }
.btn-ver:hover { background: #059669; }
.btn-config { background: #00bcd4; }
.btn-config:hover { background: #0891b2; }
a.disabled { opacity: 0.35; pointer-events: none; background: #444; }
</style>
</head>
<body>
<div class="container">
<h1>📄 Sistema de Páginas</h1>
<p class="subtitle">Todas las páginas de los botones del menú · <?= $existentes ?>/<?= $total ?> existentes</p>

<div class="nav-links">
<a href="panel-portada.php">🏠 Portada</a>
<a href="panel-menu.php">📋 Menú</a>
<a href="panel-nodos.php" class="active">📄 Nodos</a>
<a href="hub-central.php">🌌 Hub Central</a>
<a href="configuracion.php">🎨 Configuración</a>
</div>

<div class="nodos-grid">
<?php foreach ($nodos as $key => $n): ?>
    <div class="nodo-card">
        <h3><?= $n['icono'] ?> <?= htmlspecialchars($n['nombre']) ?></h3>
        <p><?= htmlspecialchars($n['desc']) ?></p>
        <div class="estado">
            Página pública: <?= $n['existe_publica'] ? '<span class="ok">✅ EXISTE</span>' : '<span class="no">❌ FALTA</span>' ?>
        </div>
        <div class="nodo-actions">
            <a href="../<?= htmlspecialchars($n['publica']) ?>" target="_blank" class="btn-ver <?= $n['existe_publica'] ? '' : 'disabled' ?>">👁️ Ver Página</a>
            <a href="config-nodo.php?nodo=<?= urlencode($key) ?>" class="btn-config">⚙️ Configurar</a>
        </div>
    </div>
<?php endforeach; ?>
</div>
</div>
</body>
</html>