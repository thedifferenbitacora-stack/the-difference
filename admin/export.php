<?php
session_start();
require_once '../config.php';

$message = '';
$msg_type = '';
$export_path = '../data/templates.json';

// Función de exportación
if ($_SERVER['REQUEST_METHOD'] === 'POST' || isset($_GET['auto'])) {
    try {
        // Obtener todas las plantillas
        $stmt = $pdo->query("SELECT * FROM plantillas ORDER BY creado_en DESC");
        $plantillas = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Transformar a formato Astro-friendly
        $export_data = [
            'generated_at' => date('c'),
            'total' => count($plantillas),
            'templates' => array_map(function($p) {
                return [
                    'id' => (int)$p['id'],
                    'name' => $p['nombre'],
                    'slug' => $p['slug'],
                    'description' => $p['descripcion'],
                    'structure' => json_decode($p['estructura'], true),
                    'styles' => $p['estilos'],
                    'active' => (bool)$p['activa'],
                    'created_at' => $p['creado_en']
                ];
            }, $plantillas)
        ];
        
        // Asegurar que la carpeta data existe
        if (!is_dir('../data')) {
            mkdir('../data', 0755, true);
        }
        
        // Guardar JSON principal
        $json_content = json_encode($export_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
        file_put_contents($export_path, $json_content);
        
        // Generar JSON individual por plantilla (opcional, útil para Astro)
        foreach ($plantillas as $p) {
            $individual_file = "../data/template-{$p['slug']}.json";
            $individual_data = [
                'id' => (int)$p['id'],
                'name' => $p['nombre'],
                'slug' => $p['slug'],
                'description' => $p['descripcion'],
                'structure' => json_decode($p['estructura'], true),
                'styles' => $p['estilos'],
                'active' => (bool)$p['activa'],
                'created_at' => $p['creado_en']
            ];
            file_put_contents(
                $individual_file, 
                json_encode($individual_data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
            );
        }
        
        $message = "Exportación completada. " . count($plantillas) . " plantillas sincronizadas.";
        $msg_type = 'success';
        
    } catch (Exception $e) {
        $message = 'Error en la exportación: ' . $e->getMessage();
        $msg_type = 'error';
    }
}

// Obtener estadísticas
$total_templates = $pdo->query("SELECT COUNT(*) FROM plantillas")->fetchColumn();
$active_templates = $pdo->query("SELECT COUNT(*) FROM plantillas WHERE activa = 1")->fetchColumn();
$last_export = file_exists($export_path) ? date('Y-m-d H:i:s', filemtime($export_path)) : 'Nunca';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Exportar a Astro / THE DIFFERENCE</title>
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
            max-width: 900px;
            margin: 0 auto;
        }
        h1 { font-size: 1.5rem; font-weight: 400; letter-spacing: -0.5px; margin-bottom: 2rem; }
        
        .msg { padding: 1rem; margin-bottom: 2rem; border-radius: 4px; font-size: 0.9rem; }
        .msg.success { background: #f0fdf4; color: var(--success); border: 1px solid #bbf7d0; }
        .msg.error { background: #fef2f2; color: var(--error); border: 1px solid #fecaca; }
        
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            padding: 2rem;
            border-radius: 6px;
            margin-bottom: 2rem;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .stat { text-align: center; padding: 1.5rem; background: var(--bg); border-radius: 4px; }
        .stat-value { font-size: 2rem; font-weight: 300; color: var(--text-primary); }
        .stat-label { font-size: 0.85rem; color: var(--text-secondary); margin-top: 0.5rem; }
        
        .info-box {
            background: var(--bg);
            padding: 1.5rem;
            border-radius: 4px;
            margin: 1.5rem 0;
            font-size: 0.9rem;
        }
        .info-box h3 { font-size: 0.95rem; font-weight: 600; margin-bottom: 1rem; color: var(--text-primary); }
        .info-box code { 
            display: block; 
            background: white; 
            padding: 0.75rem; 
            border-radius: 4px; 
            font-family: "SF Mono", Monaco, monospace;
            font-size: 0.8rem;
            margin: 0.5rem 0;
            border: 1px solid var(--border);
        }
        
        .btn {
            padding: 0.875rem 2rem;
            background: var(--accent);
            color: white;
            border: none;
            border-radius: 4px;
            font-size: 0.95rem;
            cursor: pointer;
            transition: opacity 0.2s;
            display: inline-block;
            text-decoration: none;
        }
        .btn:hover { opacity: 0.85; }
        .btn-secondary { background: white; color: var(--text-primary); border: 1px solid var(--border); margin-left: 1rem; }
        
        .file-list {
            list-style: none;
            margin-top: 1rem;
        }
        .file-list li {
            padding: 0.5rem 0;
            font-size: 0.85rem;
            color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }
        .file-list li:last-child { border-bottom: none; }
        .file-list code { color: var(--text-primary); font-family: "SF Mono", Monaco, monospace; }
    </style>
</head>
<body>

    <h1>Exportar a Astro</h1>

    <?php if ($message): ?>
        <div class="msg <?= $msg_type ?>"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="card">
        <h2 style="font-size: 1.1rem; font-weight: 500; margin-bottom: 1.5rem;">Estado de Sincronización</h2>
        
        <div class="stats-grid">
            <div class="stat">
                <div class="stat-value"><?= $total_templates ?></div>
                <div class="stat-label">Plantillas totales</div>
            </div>
            <div class="stat">
                <div class="stat-value"><?= $active_templates ?></div>
                <div class="stat-label">Plantillas activas</div>
            </div>
            <div class="stat">
                <div class="stat-value" style="font-size: 0.9rem;"><?= $last_export ?></div>
                <div class="stat-label">Última exportación</div>
            </div>
        </div>

        <form method="POST">
            <button type="submit" class="btn"> Sincronizar con Astro</button>
            <a href="plantillas.php" class="btn btn-secondary">← Volver a Plantillas</a>
        </form>
    </div>

    <div class="card">
        <h2 style="font-size: 1.1rem; font-weight: 500; margin-bottom: 1rem;">Archivos Generados</h2>
        <p style="color: var(--text-secondary); font-size: 0.9rem; margin-bottom: 1rem;">
            Estos archivos se crean en la carpeta <code>data/</code> y Astro los leerá automáticamente:
        </p>
        <ul class="file-list">
            <li><code>data/templates.json</code> — Todas las plantillas en un solo archivo</li>
            <li><code>data/template-principal.json</code> — Plantilla individual (Página Principal)</li>
            <li><code>data/template-bitacora.json</code> — Plantilla individual