<?php
session_start();
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$bitacoraFile = $baseDir . '/config/bitacora.json';

// Crear archivo de bitácora si no existe
if (!file_exists($bitacoraFile)) {
    file_put_contents($bitacoraFile, json_encode([], JSON_PRETTY_PRINT));
}

$mensaje = '';

// Cargar bitácora
$bitacora = json_decode(file_get_contents($bitacoraFile), true) ?? [];

// Agregar nueva entrada
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['accion'])) {
    if ($_POST['accion'] === 'agregar') {
        $nuevaEntrada = [
            'id' => uniqid('BIT-'),
            'fecha' => date('Y-m-d H:i:s'),
            'titulo' => $_POST['titulo'] ?? '',
            'contenido' => $_POST['contenido'] ?? '',
            'categoria' => $_POST['categoria'] ?? 'general',
            'estado' => 'activo'
        ];
        
        array_unshift($bitacora, $nuevaEntrada);
        file_put_contents($bitacoraFile, json_encode($bitacora, JSON_PRETTY_PRINT));
        $mensaje = "✅ Entrada de bitácora agregada";
    }
    
    if ($_POST['accion'] === 'eliminar') {
        $id = $_POST['id'] ?? '';
        $bitacora = array_filter($bitacora, function($entry) use ($id) {
            return $entry['id'] !== $id;
        });
        file_put_contents($bitacoraFile, json_encode(array_values($bitacora), JSON_PRETTY_PRINT));
        $mensaje = "✅ Entrada eliminada";
    }
    
    if ($_POST['accion'] === 'editar') {
        $id = $_POST['id'] ?? '';
        foreach ($bitacora as &$entry) {
            if ($entry['id'] === $id) {
                $entry['titulo'] = $_POST['titulo'] ?? $entry['titulo'];
                $entry['contenido'] = $_POST['contenido'] ?? $entry['contenido'];
                $entry['categoria'] = $_POST['categoria'] ?? $entry['categoria'];
                break;
            }
        }
        file_put_contents($bitacoraFile, json_encode($bitacora, JSON_PRETTY_PRINT));
        $mensaje = "✅ Entrada actualizada";
    }
}

$categorias = ['general', 'reflexion', 'proyecto', 'idea', 'logro'];
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Bitácora - The Difference</title>
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
.form-group.full-width {
grid-column: 1 / -1;
}
.form-group label {
display: block;
margin-bottom: 0.5rem;
color: #a0a0a0;
font-size: 0.85rem;
}
.form-group input,
.form-group select,
.form-group textarea {
width: 100%;
padding: 0.75rem;
background: #1a1a1a;
border: 1px solid #333;
border-radius: 4px;
color: #fff;
font-family: inherit;
}
.form-group textarea {
min-height: 120px;
resize: vertical;
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
.btn-secondary:hover {
background: #333;
}
.btn-danger {
background: #dc2626;
}
.btn-danger:hover {
background: #b91c1c;
}
.entries-list {
margin-top: 2rem;
}
.entry-card {
background: #252525;
border: 1px solid #333;
border-radius: 8px;
padding: 1.5rem;
margin-bottom: 1rem;
transition: all 0.2s;
}
.entry-card:hover {
border-color: #ff69b4;
}
.entry-header {
display: flex;
justify-content: space-between;
align-items: flex-start;
margin-bottom: 1rem;
}
.entry-title {
font-size: 1.1rem;
color: #fff;
margin-bottom: 0.25rem;
}
.entry-meta {
font-size: 0.8rem;
color: #a0a0a0;
}
.entry-category {
display: inline-block;
padding: 0.25rem 0.75rem;
background: #ff69b4;
color: #fff;
border-radius: 4px;
font-size: 0.75rem;
text-transform: uppercase;
}
.entry-content {
color: #a0a0a0;
line-height: 1.6;
margin-bottom: 1rem;
}
.entry-actions {
display: flex;
gap: 0.5rem;
}
.empty-state {
text-align: center;
padding: 3rem;
color: #a0a0a0;
}
</style>
</head>
<body>
<div class="container">
<h1> Panel Bitácora</h1>
<p class="subtitle">Registro de huellas y conciencia</p>

<?php if ($mensaje): ?>
<div class="alert"><?= htmlspecialchars($mensaje) ?></div>
<?php endif; ?>

<!-- NAVEGACIÓN (sin "Ver Sitio" ni "Páginas") -->
<div class="nav-buttons">
<a href="configuracion.php" class="nav-btn">⚙️ Configuración</a>
<a href="bitacora.php" class="nav-btn active">📓 Bitácora</a>
<a href="../panel-general.php" class="nav-btn">🕐 Panel-General</a>
<a href="hub-central.php" class="nav-btn">🎯 Hub Central</a>
<a href="recopilatorio.php" class="nav-btn">🧠 Recopilatorio</a>
</div>

<!-- FORMULARIO AGREGAR/EDITAR -->
<div class="form-section">
<h2>✍️ Nueva Entrada de Bitácora</h2>
<form method="POST" action="">
<input type="hidden" name="accion" value="agregar">
<div class="form-grid">
<div class="form-group">
<label>Título</label>
<input type="text" name="titulo" required placeholder="Título de la entrada">
</div>
<div class="form-group">
<label>Categoría</label>
<select name="categoria">
<?php foreach ($categorias as $cat): ?>
<option value="<?= $cat ?>"><?= ucfirst($cat) ?></option>
<?php endforeach; ?>
</select>
</div>
<div class="form-group full-width">
<label>Contenido</label>
<textarea name="contenido" required placeholder="Escribe tu reflexión, idea o registro..."></textarea>
</div>
</div>
<button type="submit" class="btn">💾 Guardar Entrada</button>
</form>
</div>

<!-- LISTA DE ENTRADAS -->
<div class="entries-list">
<h2 style="color: #ff69b4; margin-bottom: 1rem;"> Entradas Registradas</h2>

<?php if (empty($bitacora)): ?>
<div class="empty-state">
<p>No hay entradas en la bitácora aún.</p>
<p style="font-size: 0.9rem; margin-top: 0.5rem;">Comienza agregando tu primera reflexión o registro.</p>
</div>
<?php else: ?>
<?php foreach ($bitacora as $entry): ?>
<div class="entry-card">
<div class="entry-header">
<div>
<h3 class="entry-title"><?= htmlspecialchars($entry['titulo']) ?></h3>
<div class="entry-meta">
<?= date('d/m/Y H:i', strtotime($entry['fecha'])) ?>
</div>
</div>
<span class="entry-category"><?= htmlspecialchars($entry['categoria']) ?></span>
</div>
<div class="entry-content">
<?= nl2br(htmlspecialchars($entry['contenido'])) ?>
</div>
<div class="entry-actions">
<form method="POST" style="display: inline;">
<input type="hidden" name="accion" value="eliminar">
<input type="hidden" name="id" value="<?= $entry['id'] ?>">
<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('¿Eliminar esta entrada?')">🗑️ Eliminar</button>
</form>
</div>
</div>
<?php endforeach; ?>
<?php endif; ?>
</div>
</div>
</body>
</html>