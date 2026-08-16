<?php
/**
 * PANEL DE EVOLUCIÓN VISUAL - THE DIFFERENCE
 * Línea de tiempo interactiva de la genealogía conceptual
 * Filosofía: "El pensamiento es un río que fluye en el tiempo"
 */

session_start();
$baseDir = dirname(__DIR__);

$evolutionFile = $baseDir . '/.memory/evolution/mapa_evolucion.json';
$bitacoraFile = $baseDir . '/config/bitacora.json';

function leerJSON($archivo) {
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?? [];
}

$evolucion = leerJSON($evolutionFile);
$bitacora = leerJSON($bitacoraFile);

// Colores por tipo de pensamiento
$coloresTipos = [
    'observacion' => '#00bcd4',
    'reflexion' => '#ff69b4',
    'intuicion' => '#fffc34',
    'pensamiento_critico' => '#9c27b0',
    'sintesis' => '#4caf50',
    'decision' => '#ff5722',
    'general' => '#999999'
];

// Ordenar evoluciones por fecha de primera aparición
$evoluciones = $evolucion['evoluciones'] ?? [];
usort($evoluciones, function($a, $b) {
    return strtotime($a['primera_aparicion']['fecha']) <=> strtotime($b['primera_aparicion']['fecha']);
});

// Fechas límite
$fechas = array_map(function($b) { return strtotime($b['fecha']); }, $bitacora);
$minFecha = min($fechas);
$maxFecha = max($fechas);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Evolución del Pensamiento - The Difference</title>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #fff;
            min-height: 100vh;
            padding: 2rem;
        }
        
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333;
        }
        
        .header h1 {
            color: #ff69b4;
            font-size: 1.8rem;
            letter-spacing: 2px;
        }
        
        .stats {
            display: flex;
            gap: 2rem;
            font-size: 0.85rem;
            color: #a0a0a0;
        }
        
        .stats span {
            color: #fffc34;
            font-weight: bold;
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
        
        /* Controles */
        .controles {
            display: flex;
            gap: 1rem;
            margin-bottom: 2rem;
            flex-wrap: wrap;
            align-items: center;
        }
        
        .controles label {
            color: #a0a0a0;
            font-size: 0.85rem;
        }
        
        .controles select,
        .controles input {
            padding: 0.5rem 1rem;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 4px;
            color: #fff;
            font-family: inherit;
        }
        
        /* Timeline Container */
        .timeline-container {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
            overflow-x: auto;
            position: relative;
            min-height: 500px;
        }
        
        .timeline-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 2px solid #333;
        }
        
        .timeline-date {
            font-size: 0.85rem;
            color: #a0a0a0;
            flex: 1;
            text-align: center;
        }
        
        .evolution-line {
            margin-bottom: 2rem;
            padding: 1rem;
            background: #252525;
            border-radius: 8px;
            border-left: 4px solid;
            position: relative;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .evolution-line:hover {
            transform: translateX(10px);
            box-shadow: 0 4px 12px rgba(255, 105, 180, 0.3);
        }
        
        .evolution-line.expanded {
            background: #2a2a2a;
        }
        
        .concept-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }
        
        .concept-title {
            font-size: 1.3rem;
            color: #fffc34;
            font-weight: bold;
        }
        
        .concept-meta {
            color: #a0a0a0;
            font-size: 0.85rem;
        }
        
        .evolution-path {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin: 1rem 0;
            flex-wrap: wrap;
        }
        
        .evolution-point {
            flex: 1;
            min-width: 200px;
            padding: 0.75rem;
            background: #1a1a1a;
            border-radius: 6px;
            border: 2px solid;
            position: relative;
        }
        
        .evolution-point.first {
            border-color: #00bcd4;
        }
        
        .evolution-point.last {
            border-color: #4caf50;
        }
        
        .point-label {
            font-size: 0.75rem;
            color: #a0a0a0;
            margin-bottom: 0.25rem;
        }
        
        .point-date {
            font-size: 0.8rem;
            color: #fff;
            margin-bottom: 0.5rem;
        }
        
        .point-type {
            display: inline-block;
            padding: 0.2rem 0.5rem;
            border-radius: 4px;
            font-size: 0.75rem;
            font-weight: bold;
            color: #000;
        }
        
        .arrow {
            color: #666;
            font-size: 1.5rem;
        }
        
        .narrative {
            background: rgba(255, 252, 52, 0.1);
            border: 1px solid #fffc34;
            border-radius: 6px;
            padding: 1rem;
            margin-top: 1rem;
            font-style: italic;
            color: #d0d0d0;
            display: none;
        }
        
        .evolution-line.expanded .narrative {
            display: block;
        }
        
        .trajectory-details {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #333;
            display: none;
        }
        
        .evolution-line.expanded .trajectory-details {
            display: block;
        }
        
        .trajectory-item {
            background: #1a1a1a;
            padding: 0.75rem;
            margin: 0.5rem 0;
            border-radius: 4px;
            border-left: 3px solid #666;
        }
        
        .trajectory-item .fecha {
            color: #ff69b4;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
        }
        
        .trajectory-item .contexto {
            color: #a0a0a0;
            font-size: 0.8rem;
        }
        
        /* Leyenda */
        .leyenda {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 1.5rem;
            margin-top: 2rem;
        }
        
        .leyenda h3 {
            color: #fffc34;
            margin-bottom: 1rem;
        }
        
        .leyenda-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
        }
        
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.85rem;
        }
        
        .leyenda-color {
            width: 20px;
            height: 20px;
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .stats {
                flex-wrap: wrap;
            }
            
            .evolution-path {
                flex-direction: column;
            }
            
            .arrow {
                transform: rotate(90deg);
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>⏳ EVOLUCIÓN DEL PENSAMIENTO</h1>
        <div class="stats">
            <div>Conceptos evolutivos: <span><?= count($evoluciones) ?></span></div>
            <div>Bitácoras: <span><?= count($bitacora) ?></span></div>
            <div>Período: <span><?= date('d/m/Y', $minFecha) ?> - <?= date('d/m/Y', $maxFecha) ?></span></div>
        </div>
    </div>
    
    <div class="nav-buttons">
        <a href="panel-grafo.php" class="nav-btn">️ Grafo</a>
        <a href="panel-conceptos.php" class="nav-btn">☁️ Conceptos</a>
        <a href="panel-evolucion.php" class="nav-btn active">⏳ Evolución</a>
        <a href="bitacora.php" class="nav-btn">📓 Bitácora</a>
        <a href="../" target="_blank" class="nav-btn"> Ver Sitio</a>
    </div>
    
    <!-- Controles -->
    <div class="controles">
        <label for="filtro-proceso">Filtrar por proceso:</label>
        <select id="filtro-proceso">
            <option value="todos">Todos</option>
            <?php
            $procesos = array_unique(array_column($bitacora, 'proceso'));
            foreach ($procesos as $proceso): ?>
                <option value="<?= $proceso ?>"><?= $proceso ?></option>
            <?php endforeach; ?>
        </select>
        
        <label for="filtro-tipo">Filtrar por tipo:</label>
        <select id="filtro-tipo">
            <option value="todos">Todos</option>
            <option value="observacion">Observación</option>
            <option value="reflexion">Reflexión</option>
            <option value="intuicion">Intuición</option>
            <option value="pensamiento_critico">Pensamiento Crítico</option>
            <option value="sintesis">Síntesis</option>
        </select>
    </div>
    
    <!-- Timeline -->
    <div class="timeline-container" id="timeline">
        <?php foreach ($evoluciones as $index => $evolucionItem): 
            $primera = $evolucionItem['primera_aparicion'];
            $ultima = $evolucionItem['ultima_aparicion'];
            $colorPrimero = $coloresTipos[$primera['tipo']] ?? '#999';
            $colorUltimo = $coloresTipos[$ultima['tipo']] ?? '#999';
        ?>
            <div class="evolution-line" 
                 data-procesos="<?= implode(',', $evolucionItem['procesos']) ?>"
                 data-tipos="<?= implode(',', $evolucionItem['tipos_pensamiento']) ?>"
                 onclick="toggleEvolucion(this)"
                 style="border-left-color: <?= $colorPrimero ?>;">
                
                <div class="concept-header">
                    <div class="concept-title"><?= htmlspecialchars($evolucionItem['concepto']) ?></div>
                    <div class="concept-meta">
                        Frecuencia: <strong><?= $evolucionItem['frecuencia'] ?></strong> | 
                        Procesos: <strong><?= implode(', ', $evolucionItem['procesos']) ?></strong>
                    </div>
                </div>
                
                <div class="evolution-path">
                    <div class="evolution-point first">
                        <div class="point-label">Primera aparición</div>
                        <div class="point-date"><?= date('d/m/Y H:i', strtotime($primera['fecha'])) ?></div>
                        <span class="point-type" style="background: <?= $colorPrimero ?>;">
                            <?= $primera['tipo'] ?>
                        </span>
                        <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #a0a0a0;">
                            <?= $primera['id'] ?>
                        </div>
                    </div>
                    
                    <?php if ($primera['id'] !== $ultima['id']): ?>
                        <div class="arrow">→</div>
                        
                        <div class="evolution-point last">
                            <div class="point-label">Última aparición</div>
                            <div class="point-date"><?= date('d/m/Y H:i', strtotime($ultima['fecha'])) ?></div>
                            <span class="point-type" style="background: <?= $colorUltimo ?>;">
                                <?= $ultima['tipo'] ?>
                            </span>
                            <div style="margin-top: 0.5rem; font-size: 0.75rem; color: #a0a0a0;">
                                <?= $ultima['id'] ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div class="narrative">
                    <strong style="color: #fffc34;"> Narrativa evolutiva:</strong><br>
                    <?= $evolucionItem['narrativa_evolucion'] ?>
                </div>
                
                <div class="trajectory-details">
                    <strong style="color: #ff69b4;">🗺️ Trayectoria completa:</strong>
                    <?php foreach ($evolucionItem['trayectoria_detallada'] as $trayectoria): 
                        $colorTipo = $coloresTipos[$trayectoria['tipo_pensamiento']] ?? '#999';
                    ?>
                        <div class="trajectory-item" style="border-left-color: <?= $colorTipo ?>;">
                            <div class="fecha">
                                <?= date('d/m/Y H:i', strtotime($trayectoria['fecha'])) ?> 
                                <span style="color: <?= $colorTipo ?>; font-weight: bold;">
                                    [<?= $trayectoria['tipo_pensamiento'] ?>]
                                </span>
                                <span style="color: #fffc34;">(<?= $trayectoria['proceso'] ?>)</span>
                            </div>
                            <div class="contexto"><?= htmlspecialchars($trayectoria['resumen_contexto']) ?></div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Leyenda -->
    <div class="leyenda">
        <h3>🎨 Tipos de Pensamiento</h3>
        <div class="leyenda-grid">
            <?php foreach ($coloresTipos as $tipo => $color): ?>
                <div class="leyenda-item">
                    <div class="leyenda-color" style="background: <?= $color ?>;"></div>
                    <span><?= ucfirst(str_replace('_', ' ', $tipo)) ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        function toggleEvolucion(elemento) {
            // Cerrar todos los demás
            document.querySelectorAll('.evolution-line').forEach(el => {
                if (el !== elemento) el.classList.remove('expanded');
            });
            
            // Toggle del actual
            elemento.classList.toggle('expanded');
        }
        
        // Filtros
        document.getElementById('filtro-proceso').addEventListener('change', filtrar);
        document.getElementById('filtro-tipo').addEventListener('change', filtrar);
        
        function filtrar() {
            const procesoFiltro = document.getElementById('filtro-proceso').value;
            const tipoFiltro = document.getElementById('filtro-tipo').value;
            
            document.querySelectorAll('.evolution-line').forEach(linea => {
                const procesos = linea.dataset.procesos.split(',');
                const tipos = linea.dataset.tipos.split(',');
                
                const coincideProceso = procesoFiltro === 'todos' || procesos.includes(procesoFiltro);
                const coincideTipo = tipoFiltro === 'todos' || tipos.includes(tipoFiltro);
                
                linea.style.display = (coincideProceso && coincideTipo) ? 'block' : 'none';
            });
        }
        
        // Expandir el primero por defecto
        const primeraLinea = document.querySelector('.evolution-line');
        if (primeraLinea) {
            primeraLinea.classList.add('expanded');
        }
    </script>
</body>
</html>