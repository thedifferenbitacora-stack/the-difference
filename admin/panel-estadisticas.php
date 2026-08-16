<?php
/**
 * PANEL DE ESTADÍSTICAS / DASHBOARD - THE DIFFERENCE
 * Vista panorámica de la salud ontológica del sistema
 * Filosofía: "Ver el todo para comprender las partes"
 */

session_start();
$baseDir = dirname(__DIR__);

$bitacoraFile = $baseDir . '/config/bitacora.json';
$memoryDir = $baseDir . '/.memory/conversations';
$evolutionFile = $baseDir . '/.memory/evolution/mapa_evolucion.json';

function leerJSON($archivo) {
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?? [];
}

$bitacora = leerJSON($bitacoraFile);
$evolucion = leerJSON($evolutionFile);

// Cargar conversaciones
$conversaciones = [];
if (is_dir($memoryDir)) {
    foreach (glob($memoryDir . '/*.json') as $archivo) {
        $conv = leerJSON($archivo);
        if (!empty($conv)) $conversaciones[] = $conv;
    }
}

// ============================================
// ESTADÍSTICAS GENERALES
// ============================================
$totalBitacoras = count($bitacora);
$totalConversaciones = count($conversaciones);
$totalEvoluciones = count($evolucion['evoluciones'] ?? []);

// Fechas
$fechas = array_map(function($b) { return strtotime($b['fecha'] ?? '2000-01-01'); }, $bitacora);
$fechaInicio = !empty($fechas) ? min($fechas) : time();
$fechaFin = !empty($fechas) ? max($fechas) : time();
$diasActivos = max(1, round(($fechaFin - $fechaInicio) / (60 * 60 * 24)));

// ============================================
// DISTRIBUCIÓN POR TIPO DE PENSAMIENTO
// ============================================
$tiposPensamiento = [];
foreach ($bitacora as $b) {
    $tipo = $b['tipo_pensamiento'] ?? 'general';
    $tiposPensamiento[$tipo] = ($tiposPensamiento[$tipo] ?? 0) + 1;
}

// ============================================
// DISTRIBUCIÓN POR PROCESO
// ============================================
$procesos = [];
foreach ($bitacora as $b) {
    $proceso = $b['proceso'] ?? 'general';
    $procesos[$proceso] = ($procesos[$proceso] ?? 0) + 1;
}

// ============================================
// CONCEPTOS MÁS FRECUENTES
// ============================================
$conceptosFrecuencia = [];
foreach ($conversaciones as $conv) {
    foreach ($conv['analisis']['conceptos'] ?? [] as $concepto) {
        $c = mb_strtolower($concepto, 'UTF-8');
        $conceptosFrecuencia[$c] = ($conceptosFrecuencia[$c] ?? 0) + 1;
    }
}
arsort($conceptosFrecuencia);
$topConceptos = array_slice($conceptosFrecuencia, 0, 10, true);

// ============================================
// RELACIONES (cargar desde bitácora)
// ============================================
$totalRelaciones = 0;
foreach ($bitacora as $b) {
    $totalRelaciones += count($b['relacionado_a'] ?? []);
}
$totalRelaciones = intdiv($totalRelaciones, 2); // Cada relación se cuenta 2 veces

// ============================================
// IDIOMAS DETECTADOS
// ============================================
$idiomas = [];
foreach ($conversaciones as $conv) {
    $idioma = $conv['metadata']['idioma'] ?? 'desconocido';
    $idiomas[$idioma] = ($idiomas[$idioma] ?? 0) + 1;
}

// ============================================
// MÉTRICAS DERIVADAS
// ============================================
$densidadConceptual = $totalConversaciones > 0 ? round(array_sum($conceptosFrecuencia) / $totalConversaciones, 1) : 0;
$indiceConexion = $totalBitacoras > 0 ? round($totalRelaciones / $totalBitacoras, 2) : 0;
$riquezaLexica = count($conceptosFrecuencia);

// ============================================
// COLORES
// ============================================
$coloresTipos = [
    'observacion' => '#00bcd4',
    'reflexion' => '#ff69b4',
    'intuicion' => '#fffc34',
    'pensamiento_critico' => '#9c27b0',
    'sintesis' => '#4caf50',
    'decision' => '#ff5722',
    'general' => '#999999'
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Ontológico - The Difference</title>
    
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
        
        .header .subtitle {
            color: #a0a0a0;
            font-size: 0.9rem;
            margin-top: 0.25rem;
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
        
        /* Grid de métricas principales */
        .metricas-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        
        .metrica-card {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 1.5rem;
            text-align: center;
            transition: all 0.3s;
        }
        
        .metrica-card:hover {
            border-color: #ff69b4;
            transform: translateY(-5px);
            box-shadow: 0 8px 24px rgba(255, 105, 180, 0.2);
        }
        
        .metrica-valor {
            font-size: 2.5rem;
            font-weight: bold;
            color: #fffc34;
            margin-bottom: 0.5rem;
        }
        
        .metrica-label {
            color: #a0a0a0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        
        .metrica-sublabel {
            color: #666;
            font-size: 0.75rem;
            margin-top: 0.25rem;
        }
        
        /* Secciones */
        .seccion {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 2rem;
            margin-bottom: 2rem;
        }
        
        .seccion h2 {
            color: #ff69b4;
            font-size: 1.3rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #333;
        }
        
        /* Barras de distribución */
        .barra-container {
            margin-bottom: 1rem;
        }
        
        .barra-label {
            display: flex;
            justify-content: space-between;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
        }
        
        .barra-label .nombre {
            color: #fff;
        }
        
        .barra-label .valor {
            color: #fffc34;
            font-weight: bold;
        }
        
        .barra-fondo {
            background: #252525;
            border-radius: 4px;
            height: 24px;
            overflow: hidden;
        }
        
        .barra-relleno {
            height: 100%;
            border-radius: 4px;
            transition: width 0.5s ease;
            display: flex;
            align-items: center;
            padding-left: 0.5rem;
            font-size: 0.75rem;
            color: #000;
            font-weight: bold;
        }
        
        /* Grid de dos columnas */
        .grid-doble {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 2rem;
        }
        
        @media (max-width: 768px) {
            .grid-doble {
                grid-template-columns: 1fr;
            }
        }
        
        /* Top conceptos */
        .concepto-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.75rem;
            background: #252525;
            border-radius: 6px;
            margin-bottom: 0.5rem;
            border-left: 3px solid #ff69b4;
        }
        
        .concepto-nombre {
            color: #fff;
            font-size: 0.95rem;
        }
        
        .concepto-frecuencia {
            color: #fffc34;
            font-weight: bold;
            font-size: 1.1rem;
        }
        
        /* Timeline mini */
        .timeline-mini {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.75rem;
            background: #252525;
            border-radius: 6px;
            margin-bottom: 0.5rem;
        }
        
        .timeline-punto {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        
        .timeline-info {
            flex: 1;
            font-size: 0.85rem;
        }
        
        .timeline-fecha {
            color: #a0a0a0;
            font-size: 0.75rem;
        }
        
        /* Salud del sistema */
        .salud-indicador {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem;
            background: #252525;
            border-radius: 8px;
            margin-bottom: 1rem;
        }
        
        .salud-icono {
            font-size: 2rem;
        }
        
        .salud-info h4 {
            color: #fff;
            margin-bottom: 0.25rem;
        }
        
        .salud-info p {
            color: #a0a0a0;
            font-size: 0.85rem;
        }
        
        .salud-estado {
            margin-left: auto;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.85rem;
            font-weight: bold;
        }
        
        .estado-bueno {
            background: rgba(76, 175, 80, 0.2);
            color: #4caf50;
            border: 1px solid #4caf50;
        }
        
        .estado-medio {
            background: rgba(255, 252, 52, 0.2);
            color: #fffc34;
            border: 1px solid #fffc34;
        }
        
        .estado-bajo {
            background: rgba(255, 87, 34, 0.2);
            color: #ff5722;
            border: 1px solid #ff5722;
        }
        
        /* Footer */
        .footer {
            text-align: center;
            padding: 2rem;
            color: #666;
            font-size: 0.85rem;
            border-top: 1px solid #333;
            margin-top: 2rem;
        }
    </style>
</head>
<body>
    <div class="header">
        <div>
            <h1> DASHBOARD ONTOLÓGICO</h1>
            <div class="subtitle">Vista panorámica de la salud del sistema</div>
        </div>
    </div>
    
    <div class="nav-buttons">
        <a href="panel-grafo.php" class="nav-btn">🕸️ Grafo</a>
        <a href="panel-conceptos.php" class="nav-btn">☁️ Conceptos</a>
        <a href="panel-evolucion.php" class="nav-btn">⏳ Evolución</a>
        <a href="panel-estadisticas.php" class="nav-btn active">📊 Estadísticas</a>
        <a href="bitacora.php" class="nav-btn">📓 Bitácora</a>
        <a href="../" target="_blank" class="nav-btn">🏠 Ver Sitio</a>
    </div>
    
    <!-- MÉTRICAS PRINCIPALES -->
    <div class="metricas-grid">
        <div class="metrica-card">
            <div class="metrica-valor"><?= $totalBitacoras ?></div>
            <div class="metrica-label">Bitácoras</div>
            <div class="metrica-sublabel">Entradas registradas</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-valor"><?= $riquezaLexica ?></div>
            <div class="metrica-label">Conceptos únicos</div>
            <div class="metrica-sublabel">Riqueza vocabular</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-valor"><?= $totalRelaciones ?></div>
            <div class="metrica-label">Conexiones</div>
            <div class="metrica-sublabel">Trazabilidad detectada</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-valor"><?= $totalEvoluciones ?></div>
            <div class="metrica-label">Evoluciones</div>
            <div class="metrica-sublabel">Conceptos con historia</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-valor"><?= $diasActivos ?></div>
            <div class="metrica-label">Días activos</div>
            <div class="metrica-sublabel">Período de registro</div>
        </div>
        
        <div class="metrica-card">
            <div class="metrica-valor"><?= $densidadConceptual ?></div>
            <div class="metrica-label">Densidad conceptual</div>
            <div class="metrica-sublabel">Conceptos por entrada</div>
        </div>
    </div>
    
    <!-- SALUD DEL SISTEMA -->
    <div class="seccion">
        <h2>🏥 Salud del Sistema</h2>
        
        <div class="salud-indicador">
            <div class="salud-icono">🧠</div>
            <div class="salud-info">
                <h4>Diversidad de pensamiento</h4>
                <p><?= count($tiposPensamiento) ?> tipos diferentes detectados</p>
            </div>
            <div class="salud-estado estado-bueno">
                <?= count($tiposPensamiento) >= 4 ? 'Óptimo' : (count($tiposPensamiento) >= 2 ? 'Bueno' : 'Bajo') ?>
            </div>
        </div>
        
        <div class="salud-indicador">
            <div class="salud-icono">🕸️</div>
            <div class="salud-info">
                <h4>Índice de conexión</h4>
                <p><?= $indiceConexion ?> relaciones por bitácora</p>
            </div>
            <div class="salud-estado <?= $indiceConexion >= 1 ? 'estado-bueno' : ($indiceConexion >= 0.5 ? 'estado-medio' : 'estado-bajo') ?>">
                <?= $indiceConexion >= 1 ? 'Alto' : ($indiceConexion >= 0.5 ? 'Medio' : 'Bajo') ?>
            </div>
        </div>
        
        <div class="salud-indicador">
            <div class="salud-icono">🌍</div>
            <div class="salud-info">
                <h4>Cobertura lingüística</h4>
                <p><?= count($idiomas) ?> idioma(s) detectado(s)</p>
            </div>
            <div class="salud-estado <?= count($idiomas) >= 3 ? 'estado-bueno' : (count($idiomas) >= 2 ? 'estado-medio' : 'estado-bajo') ?>">
                <?= count($idiomas) >= 3 ? 'Multilingüe' : (count($idiomas) >= 2 ? 'Bilingüe' : 'Monolingüe') ?>
            </div>
        </div>
        
        <div class="salud-indicador">
            <div class="salud-icono">⏳</div>
            <div class="salud-info">
                <h4>Continuidad temporal</h4>
                <p><?= $diasActivos ?> día(s) de registro continuo</p>
            </div>
            <div class="salud-estado <?= $diasActivos >= 7 ? 'estado-bueno' : ($diasActivos >= 3 ? 'estado-medio' : 'estado-bajo') ?>">
                <?= $diasActivos >= 7 ? 'Sostenido' : ($diasActivos >= 3 ? 'Intermitente' : 'Inicial') ?>
            </div>
        </div>
    </div>
    
    <!-- DISTRIBUCIONES -->
    <div class="grid-doble">
        <div class="seccion">
            <h2>🎭 Distribución por Tipo de Pensamiento</h2>
            <?php
            $maxTipo = max($tiposPensamiento);
            foreach ($tiposPensamiento as $tipo => $cantidad):
                $porcentaje = round(($cantidad / $totalBitacoras) * 100);
                $color = $coloresTipos[$tipo] ?? '#999';
            ?>
                <div class="barra-container">
                    <div class="barra-label">
                        <span class="nombre"><?= ucfirst(str_replace('_', ' ', $tipo)) ?></span>
                        <span class="valor"><?= $cantidad ?> (<?= $porcentaje ?>%)</span>
                    </div>
                    <div class="barra-fondo">
                        <div class="barra-relleno" style="width: <?= $porcentaje ?>%; background: <?= $color ?>;">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="seccion">
            <h2> Distribución por Proceso</h2>
            <?php
            $maxProceso = max($procesos);
            foreach ($procesos as $proceso => $cantidad):
                $porcentaje = round(($cantidad / $totalBitacoras) * 100);
            ?>
                <div class="barra-container">
                    <div class="barra-label">
                        <span class="nombre"><?= ucfirst($proceso) ?></span>
                        <span class="valor"><?= $cantidad ?> (<?= $porcentaje ?>%)</span>
                    </div>
                    <div class="barra-fondo">
                        <div class="barra-relleno" style="width: <?= $porcentaje ?>%; background: #ff69b4;">
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- TOP CONCEPTOS Y EVOLUCIONES RECIENTES -->
    <div class="grid-doble">
        <div class="seccion">
            <h2>⭐ Top 10 Conceptos</h2>
            <?php foreach ($topConceptos as $concepto => $frecuencia): ?>
                <div class="concepto-item">
                    <span class="concepto-nombre"><?= htmlspecialchars($concepto) ?></span>
                    <span class="concepto-frecuencia"><?= $frecuencia ?></span>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="seccion">
            <h2>🧬 Evoluciones Detectadas</h2>
            <?php foreach (array_slice($evolucion['evoluciones'] ?? [], 0, 5) as $evo): 
                $primera = $evo['primera_aparicion'];
                $ultima = $evo['ultima_aparicion'];
                $colorPrimero = $coloresTipos[$primera['tipo']] ?? '#999';
                $colorUltimo = $coloresTipos[$ultima['tipo']] ?? '#999';
            ?>
                <div class="timeline-mini">
                    <div class="timeline-punto" style="background: <?= $colorPrimero ?>;"></div>
                    <div class="timeline-info">
                        <div style="color: #fffc34; font-weight: bold;"><?= htmlspecialchars($evo['concepto']) ?></div>
                        <div class="timeline-fecha">
                            <?= date('d/m', strtotime($primera['fecha'])) ?> 
                            (<?= $primera['tipo'] ?>) 
                            → 
                            <?= date('d/m', strtotime($ultima['fecha'])) ?> 
                            (<?= $ultima['tipo'] ?>)
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    
    <!-- IDIOMAS -->
    <?php if (!empty($idiomas)): ?>
    <div class="seccion">
        <h2>🌍 Idiomas Detectados</h2>
        <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <?php foreach ($idiomas as $idioma => $cantidad): ?>
                <div style="background: #252525; padding: 1rem 1.5rem; border-radius: 8px; border: 1px solid #333;">
                    <div style="font-size: 1.5rem; font-weight: bold; color: #fffc34;"><?= strtoupper($idioma) ?></div>
                    <div style="color: #a0a0a0; font-size: 0.85rem;"><?= $cantidad ?> entrada(s)</div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
    <?php endif; ?>
    
    <!-- FOOTER -->
    <div class="footer">
        <p>️ The Difference · Dashboard Ontológico · Generado el <?= date('d/m/Y H:i:s') ?></p>
        <p style="margin-top: 0.5rem; font-size: 0.75rem;">
            "El Ser Ahí es Presencia. El Decir es Huella."
        </p>
    </div>
</body>
</html>