<?php
session_start();
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$paginasFile = $baseDir . '/config/paginas.json';

// Crear archivo de páginas si no existe
if (!file_exists($paginasFile)) {
    $defaultPaginas = [
        'portada' => [
            'titulo' => 'Portada',
            'archivo' => 'index.php',
            'activa' => true,
            'orden' => 1
        ],
        'menu' => [
            'titulo' => 'Menú',
            'archivo' => 'menu.php',
            'activa' => true,
            'orden' => 2
        ]
    ];
    file_put_contents($paginasFile, json_encode($defaultPaginas, JSON_PRETTY_PRINT));
}

$mensaje = '';
$paginas = json_decode(file_get_contents($paginasFile), true) ?? [];

// Acciones
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'agregar') {
        $id = uniqid('page-');
        $paginas[$id] = [
            'titulo' => $_POST['titulo'] ?? '',
            'archivo' => $_POST['archivo'] ?? '',
            'activa' => true,
            'orden' => count($paginas) + 1
        ];
        file_put_contents($paginasFile, json_encode($paginas, JSON_PRETTY_PRINT));
        $mensaje = "✅ Página agregada";
    }
    
    if ($_POST['accion'] === 'editar') {
        $id = $_POST['id'] ?? '';
        if (isset($paginas[$id])) {
            $paginas[$id]['titulo'] = $_POST['titulo'] ?? $paginas[$id]['titulo'];
            $paginas[$id]['archivo'] = $_POST['archivo'] ?? $paginas[$id]['archivo'];
            $paginas[$id]['activa'] = isset($_POST['activa']);
            file_put_contents($paginasFile, json_encode($paginas, JSON_PRETTY_PRINT));
            $mensaje = "✅ Página actualizada";
        }
    }
    
    if ($_POST['accion'] === 'eliminar') {
        $id = $_POST['id'] ?? '';
        unset($paginas[$id]);
        file_put_contents($paginasFile, json_encode($paginas, JSON_PRETTY_PRINT));
        $mensaje = "✅ Página eliminada";
    }
    
    if ($_POST['accion'] === 'toggle') {
        $id = $_POST['id'] ?? '';
        if (isset($paginas[$id])) {
            $paginas[$id]['activa'] = !$paginas[$id]['activa'];
            file_put_contents($paginasFile, json_encode($paginas, JSON_PRETTY_PRINT));
            $mensaje = "✅ Estado actualizado";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Páginas - The Difference</title>
<style>
* { margin: 0; padding: 0; box-sizing: border-box; }
body {
font-family: system-ui, sans-serif;
background: #0f0f0f;
color: #fff;
min-height: 100vh;
padding: 2rem;
}
.container {
max-width: 1200px;
margin: 0 auto;
background: #1a1a1a;
border: 1px solid #333;
border-radius: 12px;
padding: 2rem;
}
h1 {
font-size: 2rem;
margin-bottom: 0.5rem;
color: #ff69b4;
}
.subtitle {
color: #a0a0a0;
margin-bottom: 2rem;
}
.alert {
padding: 1rem;
border-radius: 6px;
margin-bottom: 1.5rem;
background: rgba(16,185,129,0.2);
border: 1px solid #10b981;
color: #10b981;
}
.nav-buttons {
display: flex;
gap: 1rem;
margin-bottom: 2rem;
flex-wrap: wrap;
}
.nav-btn {
padding: 0.75rem 1.5rem;
background: #252525;
color: #fff;
text-decoration: none;
border-radius: 6px;
border: 1px solid #333;
transition: all 0.2s;
display: flex;
align-items: center;
gap: 0.5rem;
}
.nav-btn:hover {
background: #333;
border-color: #ff69b4;
transform: translateY(-2px);
}
.nav-btn.active {
background: #ff69b4;
border-color: #ff69b4;
}
.form-section {
background: #252525;
border: 1px solid #333;
border-radius: 8px;
padding: 1.5rem;
margin-bottom: 2rem;
}
.form-section h2 {
font-size: 1.2rem;
margin-bottom: 1rem;
color: #ff69b4;
}
.form-grid {
display: grid;
grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
gap: 1rem;
}
.form-group {
margin-bottom: 1rem;
}
.form-group label {
display: block;
margin-bottom: 0.5rem;
color: #a0a0a0;
font-size: 0.85rem;
}
.form-group input,
.form-group select {
width: 100%;
padding: 0.75rem;
background: #1a1a1a;
border: 1px solid #333;
border-radius: 4px;
color: #fff;
}
.checkbox-group {
display: flex;
align-items: center;
gap: 0.5rem;
}
.checkbox-group input[type="checkbox"] {
width: auto;
}
.btn {
padding: 0.75rem 1.5rem;
background: #ff69b4;
color: #fff;
border: none;
border-radius: 6px;
cursor: pointer;
font-size: 0.9rem;
transition: all 0.2s;
}
.btn:hover {
background: #ff1493;
transform: translateY(-2px);
}
.btn-secondary {
background: #252525;
border: 1px solid #333;
}
.btn-danger {
background: #dc2626;
}
.btn-sm {
padding: 0.5rem 1rem;
font-size: 0.85rem;
}
.pages-grid {
display: grid;
grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
gap: 1.5rem;
margin-top: 2rem;
}
.page-card {
background: #252525;
border: 1px solid #333;
border-radius: 8px;
padding: 1.5rem;
transition: all 0.2s;
}
.page-card:hover {
border-color: #ff69b4;
transform: translateY(-2px);
}
.page-card.inactive {
opacity: 0.6;
}
.page-header {
display: flex;
justify-content: space-between;
align-items: flex-start;
margin-bottom: 1rem;
}
.page-title {
font-size: 1.1rem;
color: #fff;
}
.page-file {
font-size: 0.8rem;
color: #a0a0a0;
font-family: monospace;
margin-top: 0.25rem;
}
.status-badge {
display: inline-block;
padding: 0.25rem 0.75rem;
border-radius: 4px;
font-size: 0.75rem;
text-transform: uppercase;
}
.status-active {
background: #10b981;
color: #fff;
}
.status-inactive {
background: #6b7280;
color: #fff;
}
.page-actions {
display: flex;
gap: 0.5rem;
margin-top: 1rem;
}
.empty-state {
text-align: center;
padding: 3rem;
color: #a0a0a0;
grid-column: 1 / -1;
}
</style>
</head>
<body>
<div class="container">
<h1>📄 Panel de Páginas</h1>
<p class="subtitle">Gestión de páginas del sitio</p>

<?php if ($mensaje): ?>
<div class="alert"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<div class="nav-buttons">
<a href="configuracion.php" class="nav-btn">⚙️ Configuración</a>
<a href="bitacora.php" class="nav-btn">📓 Bitácora</a>
<a href="paginas.php" class="nav-btn active">📄 Páginas</a>
<a href="../" target="_blank" class="nav-btn">🏠 Ver Sitio</a>
</div>

<!-- FORMULARIO AGREGAR PÁGINA -->
<div class="form-section">
<h2>➕ Agregar Nueva Página</h2>
<form method="POST" action="">
<input type="hidden" name="accion" value="agregar">
<div class="form-grid">
<div class="form-group">
<label>Título de la Página</label>
<input type="text" name="titulo" required placeholder="Ej: Contacto">
</div>
<div class="form-group">
<label>Archivo PHP</label>
<input type="text" name="archivo" required placeholder="Ej: contacto.php">
</div>
</div>
<button type="submit" class="btn">💾 Agregar Página</button>
</form>
</div>

<!-- LISTA DE PÁGINAS -->
<h2 style="color: #ff69b4; margin-bottom: 1rem;">📋 Páginas Registradas</h2>

<div class="pages-grid">
<?php if (empty($paginas)): ?>
<div class="empty-state">
<p>No hay páginas registradas aún.</p>
</div>
<?php else: ?>
<?php foreach ($paginas as $id => $page): ?>
<div class="page-card <?= !$page['activa'] ? 'inactive' : '' ?>">
<div class="page-header">
<div>
<h3 class="page-title"><?= htmlspecialchars($page['titulo']) ?></h3>
<div class="page-file"><?= htmlspecialchars($page['archivo']) ?></div>
</div>
<span class="status-badge <?= $page['activa'] ? 'status-active' : 'status-inactive' ?>">
<?= $page['activa'] ? '✓ Activa' : '○ Inactiva' ?>
</span>
</div>
<div class="page-actions">
<form method="POST" style="display: inline;">
<input type="hidden" name="accion" value="toggle">
<input type="hidden" name="id" value="<?= $id ?>">
<button type="submit" class="btn btn-sm btn-secondary">
<?= $page['activa'] ? '🔴 Desactivar' : '🟢 Activar' ?>
</button>
</form>
<form method="POST" style="display: inline;">
<input type="hidden" name="accion" value="eliminar">
<input type="hidden" name="id" value="<?= $id ?>">
<button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('¿Eliminar esta página?')">🗑️</button>
</form>
<a href="<?= $page['archivo'] ?>" target="_blank" class="btn btn-sm btn-secondary">👁️ Ver</a>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
</body>
</html>