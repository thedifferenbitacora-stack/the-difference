<?php
session_start();
require_once '../config.php'; // Tu conexión existente

$action = $_GET['action'] ?? 'list';
$message = '';
$msg_type = '';

// --- LÓGICA (GEIST) ---

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nombre = trim($_POST['nombre'] ?? '');
    $slug = trim($_POST['slug'] ?? '');
    $descripcion = trim($_POST['descripcion'] ?? '');
    $estructura = trim($_POST['estructura'] ?? '{}');
    $estilos = trim($_POST['estilos'] ?? '');
    $activa = isset($_POST['activa']) ? 1 : 0;
    $id = $_POST['id'] ?? null;

    // Validación silenciosa de JSON
    json_decode($estructura);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $message = 'La estructura debe ser un JSON válido.';
        $msg_type = 'error';
        $action = 'edit';
    } else {
        try {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE plantillas SET nombre=?, slug=?, descripcion=?, estructura=?, estilos=?, activa=? WHERE id=?");
                $stmt->execute([$nombre, $slug, $descripcion, $estructura, $estilos, $activa, $id]);
                $message = 'Plantilla actualizada.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO plantillas (nombre, slug, descripcion, estructura, estilos, activa) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->execute([$nombre, $slug, $descripcion, $estructura, $estilos, $activa]);
                $message = 'Plantilla creada.';
            }
            $msg_type = 'success';
            $action = 'list';
        } catch (PDOException $e) {
            $message = 'Error de base de datos.';
            $msg_type = 'error';
        }
    }
}

if ($action === 'delete' && isset($_GET['id'])) {
    try {
        $stmt = $pdo->prepare("DELETE FROM plantillas WHERE id=?");
        $stmt->execute([$_GET['id']]);
        $message = 'Plantilla eliminada.';
        $msg_type = 'success';
    } catch (PDOException $e) {
        $message = 'Error al eliminar.';
        $msg_type = 'error';
    }
    $action = 'list';
}

// Obtener datos para la vista
$stmt = $pdo->query("SELECT * FROM plantillas ORDER BY creado_en DESC");
$plantillas = $stmt->fetchAll();

$editing = null;
if ($action === 'edit' && isset($_GET['id'])) {
    $stmt = $pdo->prepare("SELECT * FROM plantillas WHERE id=?");
    $stmt->execute([$_GET['id']]);
    $editing = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plantillas / THE DIFFERENCE</title>
    <style>
        :root {
            --bg: #fafafa;
            --surface: #ffffff;
            --text-primary: #18181b;
            --text-secondary: #71717a;
            --border: #e4e4e7;
            --accent: #18181b;
            --success: #16a34a;
            --error: #dc2626;
        }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: system-ui, -apple-system, "Segoe UI", sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            line-height: 1.6;
            padding: 3rem 2rem;
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 { font-size: 1.5rem; font-weight: 400; letter-spacing: -0.5px; margin-bottom: 2rem; }
        h2 { font-size: 1.1rem; font-weight: 500; margin: 3rem 0 1rem; color: var(--text-secondary); }
        
        /* Mensajes */
        .msg { padding: 1rem; margin-bottom: 2rem; border-radius: 4px; font-size: 0.9rem; }
        .msg.success { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }
        .msg.error { background: #fef2f2; color: var(--error); border: 1px solid #fecaca; }

        /* Tabla */
        table { width: 100%; border-collapse: collapse; background: var(--surface); border: 1px solid var(--border); }
        th, td { text-align: left; padding: 1rem; border-bottom: 1px solid var(--border); font-size: 0.9rem; }
        th { font-weight: 500; color: var(--text-secondary); font-size: 0.8rem; text-transform: uppercase; letter-spacing: 0.5px; }
        tr:last-child td { border-bottom: none; }
        
        .badge { display: inline-block; padding: 0.25rem 0.5rem; border-radius: 4px; font-size: 0.75rem; font-weight: 500; }
        .badge.active { background: #f0fdf4; color: var(--success); }
        .badge.inactive { background: #f4f4f5; color: var(--text-secondary); }

        /* Enlaces y Botones */
        a { color: var(--text-primary); text-decoration: none; border-bottom: 1px solid var(--border); transition: border-color 0.2s; }
        a:hover { border-color: var(--text-primary); }
        .actions { display: flex; gap: 1rem; }
        
        /* Formulario */
        form { background: var(--surface); padding: 2rem; border: 1px solid var(--border); margin-top: 1rem; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-secondary); margin-bottom: 0.5rem; }
        input[type="text"], textarea {
            width: 100%; padding: 0.75rem; border: 1px solid var(--border); border-radius: 4px;
            font-family: inherit; font-size: 0.95rem; background: var(--bg); transition: border-color 0.2s;
        }
        input[type="text"]:focus, textarea:focus { outline: none; border-color: var(--text-primary); }
        textarea { min-height: 120px; font-family: "SF Mono", Monaco, monospace; font-size: 0.85rem; }
        .hint { font-size: 0.8rem; color: var(--text-secondary); margin-top: 0.5rem; }
        
        .checkbox-group { display: flex; align-items: center; gap: 0.5rem; margin-top: 1rem; }
        .checkbox-group label { margin: 0; cursor: pointer; }

        button {
            padding: 0.75rem 1.5rem; background: var(--accent); color: white; border: none;
            border-radius: 4px; font-size: 0.9rem; cursor: pointer; transition: opacity 0.2s;
        }
        button:hover { opacity: 0.85; }
        .btn-secondary { background: transparent; color: var(--text-primary); border: 1px solid var(--border); margin-left: 0.5rem; }
        .btn-secondary:hover { background: var(--bg); }
    </style>
</head>
<body>

    <h1>Gestión de Plantillas</h1>

    <?php if ($message): ?>
        <div class="msg <?= $msg_type ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <?php if ($action === 'list'): ?>
        <table>
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>Slug</th>
                    <th>Estado</th>
                    <th style="width: 150px;">Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($plantillas as $p): ?>
                <tr>
                    <td><?= htmlspecialchars($p['nombre']) ?></td>
                    <td><code><?= htmlspecialchars($p['slug']) ?></code></td>
                    <td>
                        <span class="badge <?= $p['activa'] ? 'active' : 'inactive' ?>">
                            <?= $p['activa'] ? 'Activa' : 'Inactiva' ?>
                        </span>
                    </td>
                    <td class="actions">
                        <a href="?action=edit&id=<?= $p['id'] ?>">Editar</a>
                        <a href="?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('¿Eliminar permanentemente?')">Eliminar</a>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php if (empty($plantillas)): ?>
                <tr><td colspan="4" style="text-align: center; color: var(--text-secondary); padding: 2rem;">No hay plantillas registradas.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
        <div style="margin-top: 2rem;">
            <a href="?action=new" style="border: 1px solid var(--border); padding: 0.75rem 1.5rem; border-radius: 4px;">+ Nueva Plantilla</a>
        </div>

    <?php else: ?>
        <h2><?= $editing ? 'Editar' : 'Nueva' ?> Plantilla</h2>
        <form method="POST">
            <?php if ($editing): ?>
                <input type="hidden" name="id" value="<?= $editing['id'] ?>">
            <?php endif; ?>
            
            <div class="form-group">
                <label for="nombre">Nombre</label>
                <input type="text" id="nombre" name="nombre" value="<?= htmlspecialchars($editing['nombre'] ?? '') ?>" required>
            </div>
            
            <div class="form-group">
                <label for="slug">Slug</label>
                <input type="text" id="slug" name="slug" value="<?= htmlspecialchars($editing['slug'] ?? '') ?>" required>
                <div class="hint">Identificador único en minúsculas, sin espacios (ej: pagina-principal)</div>
            </div>
            
            <div class="form-group">
                <label for="descripcion">Descripción</label>
                <input type="text" id="descripcion" name="descripcion" value="<?= htmlspecialchars($editing['descripcion'] ?? '') ?>">
            </div>
            
            <div class="form-group">
                <label for="estructura">Estructura (JSON)</label>
                <textarea id="estructura" name="estructura" required><?= htmlspecialchars($editing['estructura'] ?? '{"header": true, "footer": true}') ?></textarea>
                <div class="hint">Define los componentes activos en formato JSON válido.</div>
            </div>
            
            <div class="form-group">
                <label for="estilos">Estilos personalizados (CSS)</label>
                <textarea id="estilos" name="estilos"><?= htmlspecialchars($editing['estilos'] ?? '') ?></textarea>
            </div>
            
            <div class="checkbox-group">
                <input type="checkbox" id="activa" name="activa" value="1" <?= ($editing['activa'] ?? 0) ? 'checked' : '' ?>>
                <label for="activa">Marcar como activa</label>
            </div>
            
            <div style="margin-top: 2rem;">
                <button type="submit"><?= $editing ? 'Guardar Cambios' : 'Crear Plantilla' ?></button>
                <a href="plantillas.php" class="btn-secondary" style="padding: 0.75rem 1.5rem; border-radius: 4px; display: inline-block;">Cancelar</a>
            </div>
        </form>
    <?php endif; ?>

</body>
</html>