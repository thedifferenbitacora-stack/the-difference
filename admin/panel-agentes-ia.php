<?php
/**
 * PANEL DE AGENTES MULTI-IA - THE DIFFERENCE (CÓDIGO COMPLETO)
 * Visualiza el Consejo de Sabios Digitales y los Conceptos Modos generados.
 */
$baseDir = dirname(__DIR__, 2);
$memoryDir = $baseDir . '/.memory/conversations';

$analisisRecientes = [];
if (is_dir($memoryDir)) {
    $archivos = glob($memoryDir . '/*.json');
    // Ordenar por fecha de modificación (más reciente primero)
    usort($archivos, function($a, $b) {
        return filemtime($b) - filemtime($a);
    });
    
    // Tomar los últimos 10 registros
    foreach (array_slice($archivos, 0, 10) as $archivo) {
        $contenido = file_get_contents($archivo);
        $data = json_decode($contenido, true);
        if ($data) {
            $analisisRecientes[] = $data;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Consejo de Sabios - Fundación Ars Tekne</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            background: #0a0a0a;
            color: #e0e0e0;
            font-family: 'Courier New', Courier, monospace;
            padding: 2rem;
            min-height: 100vh;
        }
        .header {
            border-bottom: 2px solid #ff69b4;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .header h1 {
            color: #fffc34;
            font-size: 1.8rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .header p {
            color: #a0a0a0;
            margin-top: 0.5rem;
            font-size: 0.9rem;
        }
        .card {
            background: #151515;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            margin-bottom: 1.5rem;
            transition: border-color 0.3s;
        }
        .card:hover {
            border-color: #ff69b4;
        }
        .card-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
            border-bottom: 1px solid #2a2a2a;
            padding-bottom: 0.5rem;
        }
        .card-header h3 {
            color: #00bcd4;
            font-size: 1rem;
        }
        .fecha {
            color: #666;
            font-size: 0.8rem;
        }
        .texto-original {
            font-style: italic;
            color: #b0b0b0;
            margin-bottom: 1.5rem;
            padding-left: 1rem;
            border-left: 3px solid #fffc34;
            line-height: 1.5;
        }
        .ia-box {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 1rem;
            margin-bottom: 1.5rem;
        }
        .ia-respuesta {
            background: #1a1a1a;
            padding: 1rem;
            border-radius: 6px;
            font-size: 0.8rem;
            line-height: 1.4;
            border-left: 4px solid #555;
            max-height: 200px;
            overflow-y: auto;
        }
        .ia-respuesta strong {
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
        }
        .ia-qwen { border-left-color: #ff5722; }
        .ia-qwen strong { color: #ff5722; }
        
        .ia-gpt { border-left-color: #4caf50; }
        .ia-gpt strong { color: #4caf50; }
        
        .ia-gemini { border-left-color: #9c27b0; }
        .ia-gemini strong { color: #9c27b0; }

        .concepto-modo {
            background: #1a0f1a;
            border: 1px solid #ff69b4;
            padding: 1rem;
            border-radius: 6px;
        }
        .concepto-modo strong {
            color: #fffc34;
            display: block;
            margin-bottom: 0.5rem;
            font-size: 0.9rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        .tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        .tag {
            background: #ff69b4;
            color: #000;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .sin-datos {
            text-align: center;
            color: #666;
            padding: 3rem;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Consejo de Sabios Digitales</h1>
        <p>Integración Multi-IA para la desidentificación y generación de Conceptos Modos (Ars - Tekne - Espíritu)</p>
    </div>

    <?php if (empty($analisisRecientes)): ?>
        <div class="sin-datos">
            <p>No hay registros en la memoria del Consejo aún. Escribe en tu bitácora para invocar a los sabios.</p>
        </div>
    <?php else: ?>
        <?php foreach ($analisisRecientes as $analisis): ?>
            <div class="card">
                <div class="card-header">
                    <h3>[ <?= strtoupper(htmlspecialchars($analisis['tipo_bitacora'] ?? 'LOG')) ?> ] - ID: <?= htmlspecialchars($analisis['id']) ?></h3>
                    <span class="fecha"><?= date('d/m/Y H:i', strtotime($analisis['fecha'])) ?></span>
                </div>
                
                <div class="texto-original">
                    "<?= htmlspecialchars($analisis['texto_original']) ?>"
                </div>
                
                <div class="ia-box">
                    <div class="ia-respuesta ia-qwen">
                        <strong>QWEN</strong>
                        <?= nl2br(htmlspecialchars($analisis['analisis_ia']['qwen'] ?? 'Sin respuesta')) ?>
                    </div>
                    <div class="ia-respuesta ia-gpt">
                        <strong>CHATGPT</strong>
                        <?= nl2br(htmlspecialchars($analisis['analisis_ia']['gpt'] ?? 'Sin respuesta')) ?>
                    </div>
                    <div class="ia-respuesta ia-gemini">
                        <strong>GEMINI</strong>
                        <?= nl2br(htmlspecialchars($analisis['analisis_ia']['gemini'] ?? 'Sin respuesta')) ?>
                    </div>
                </div>

                <div class="concepto-modo">
                    <strong>Conceptos Modos Detectados:</strong>
                    <div class="tags">
                        <?php 
                        $conceptos = $analisis['analisis']['conceptos'] ?? [];
                        if (empty($conceptos)): 
                        ?>
                            <span class="tag" style="background: #555; color: #fff;">Pendiente de síntesis</span>
                        <?php else: ?>
                            <?php foreach ($conceptos as $concepto): ?>
                                <span class="tag"><?= htmlspecialchars($concepto) ?></span>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    <?php endif; ?>
</body>
</html>