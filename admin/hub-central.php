<?php
/**
 * HUB CENTRAL - THE DIFFERENCE
 * Panel general con acceso organizado a todos los espacios del ecosistema
 * Ontología: Ouroboros → Trinidad → Sabios → Memoria → Guardianes → Sistema
 */
session_start();

$baseDir = dirname(__DIR__);
$configDir = $baseDir . '/config';
$dataDir = $baseDir . '/data';

// Cargar métricas rápidas para mostrar en el hub
$settingsFile = $configDir . '/settings.json';
$bitacoraFile = $configDir . '/bitacora.json';
$cuentasFile = $configDir . '/cuentas.json';
$logPersonalFile = $dataDir . '/log-personal.json';

$settings = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];
$bitacora = file_exists($bitacoraFile) ? json_decode(file_get_contents($bitacoraFile), true) : [];
$cuentas = file_exists($cuentasFile) ? json_decode(file_get_contents($cuentasFile), true) : [];
$logPersonal = file_exists($logPersonalFile) ? json_decode(file_get_contents($logPersonalFile), true) : [];

// Conteos rápidos
$totalBitacoras = is_array($bitacora) ? count($bitacora) : 0;
$cuentasConfiguradas = is_array($cuentas) ? count(array_filter($cuentas, fn($c) => !empty($c['email'] ?? ''))) : 0;
$totalLogPersonal = is_array($logPersonal) ? 
    (count($logPersonal['niñes'] ?? []) + count($logPersonal['juventud'] ?? []) + 
     count($logPersonal['adultes'] ?? []) + count($logPersonal['proyecto_actual'] ?? [])) : 0;
$iasActivas = count(array_filter([
    $settings['ia']['openai_key'] ?? '',
    $settings['ia']['gemini_key'] ?? '',
    $settings['ia']['qwen_key'] ?? ''
]));

// ============================================
// ESTRUCTURA ONTOLÓGICA DE ESPACIOS
// ============================================
$espacios = [
    'log' => [
        'nombre' => 'LOG · GOBERNANZA',
        'subtitulo' => 'El Ouroboros Espiral · Bitácora Maestra',
        'icono' => '🌀',
        'color' => '#9c27b0',
        'descripcion' => 'El ciclo eterno. El contenedor que gobierna todo el ecosistema.',
        'accesos' => [
            ['nombre' => 'Bitácora Maestra', 'url' => 'panel-log.php', 'icono' => '📓', 'desc' => 'Escribir en el LOG'],
            ['nombre' => 'Registro de Bitácora', 'url' => 'registro-bitacora.php', 'icono' => '📝', 'desc' => 'Ver registros'],
            ['nombre' => 'Trazabilidad', 'url' => 'api/trazabilidad.php', 'icono' => '🔗', 'desc' => 'API de conexiones']
        ]
    ],
    'trinidad' => [
        'nombre' => 'TRINIDAD · ARS TEKNE',
        'subtitulo' => 'Las 3 Bitácoras Sagradas (Ars · Tekne · Espíritu)',
        'icono' => '🔺',
        'color' => '#ff69b4',
        'descripcion' => 'El alma, la estructura y el espíritu del oficio.',
        'accesos' => [
            ['nombre' => 'TEXVN · Tekne', 'url' => 'panel-texvn.php', 'icono' => '🔷', 'desc' => '13 pasos técnicos'],
            ['nombre' => 'SAIAYIN DO · Ars', 'url' => 'panel-saiayin-do.php', 'icono' => '🌸', 'desc' => '7 pasos simbólicos'],
            ['nombre' => 'OPUS MAGNUM', 'url' => 'panel-quiron-theatre.php', 'icono' => '🦁', 'desc' => 'Teatro Quirón'],
            ['nombre' => 'Le Tematik', 'url' => 'panel-le-tematik.php', 'icono' => '🎨', 'desc' => 'Diseño estético']
        ]
    ],
    'sabios' => [
        'nombre' => 'CONSEJO DE SABIOS',
        'subtitulo' => 'Multi-IA · Qwen + ChatGPT + Gemini',
        'icono' => '🧠',
        'color' => '#00bcd4',
        'descripcion' => 'El tríada de inteligencias que analiza las bitácoras.',
        'badge' => $iasActivas . '/3 IAs',
        'accesos' => [
            ['nombre' => 'Multi-Cuentas', 'url' => 'multi-cuentas.php', 'icono' => '📧', 'desc' => 'Gmail + 3 IAs'],
            ['nombre' => 'Probar Consejo', 'url' => 'formulario-prueba-ia.html', 'icono' => '🧪', 'desc' => 'Invocar a las 3 IAs'],
            ['nombre' => 'Agentes IA', 'url' => 'panel-agentes-ia.php', 'icono' => '🤖', 'desc' => 'Ver análisis'],
            ['nombre' => 'Configurar Cuentas', 'url' => 'configurar-cuentas.php', 'icono' => '🔑', 'desc' => 'Gmail por nodo']
        ]
    ],
    'memoria' => [
        'nombre' => 'MEMORIA EVOLUTIVA',
        'subtitulo' => 'LOG Personal · Trazabilidad · Cuadernos',
        'icono' => '📜',
        'color' => '#fffc34',
        'descripcion' => 'La huella del viaje: Niñes → Juventud → Adultes → Proyecto.',
        'badge' => $totalLogPersonal . ' registros',
        'accesos' => [
            ['nombre' => 'LOG Personal', 'url' => 'log-personal.php', 'icono' => '🌱', 'desc' => '3 etapas de vida'],
            ['nombre' => 'Cuadernos', 'url' => 'cuadernos.php', 'icono' => '📚', 'desc' => 'Notebooks unificados'],
            ['nombre' => 'Evolución', 'url' => 'panel-evolucion.php', 'icono' => '📈', 'desc' => 'Conceptos en el tiempo'],
            ['nombre' => 'Grafo', 'url' => 'panel-grafo.php', 'icono' => '🕸️', 'desc' => 'Red de relaciones']
        ]
    ],
    'guardianes' => [
        'nombre' => 'GUARDIANES · SEGURIDAD',
        'subtitulo' => 'Consejo de Guardianes Ontológicos',
        'icono' => '🛡️',
        'color' => '#10b981',
        'descripcion' => 'Los 5 agentes que protegen la integridad del sistema.',
        'accesos' => [
            ['nombre' => 'Panel Guardianes', 'url' => 'guardianes.php', 'icono' => '⚔️', 'desc' => 'Estado de los 5'],
            ['nombre' => 'Validar Acceso', 'url' => 'api/guardianes.php', 'icono' => '✅', 'desc' => 'API de validación'],
            ['nombre' => 'Dashboards', 'url' => 'dashboards.php', 'icono' => '📊', 'desc' => 'Métricas del sistema']
        ]
    ],
    'sistema' => [
        'nombre' => 'SISTEMA · CONFIGURACIÓN',
        'subtitulo' => 'Inventario · Config · Preview',
        'icono' => '⚙️',
        'color' => '#f59e0b',
        'descripcion' => 'Control total del ecosistema técnico.',
        'accesos' => [
            ['nombre' => 'Configuración Visual', 'url' => 'configuracion.php', 'icono' => '🎛️', 'desc' => 'Logo, colores, botones'],
            ['nombre' => 'System Inventory', 'url' => 'inventario.php', 'icono' => '🗂️', 'desc' => 'Todos los archivos'],
            ['nombre' => 'Preview Portada', 'url' => '../portada.php', 'icono' => '👁️', 'desc' => 'Ver portada'],
            ['nombre' => 'Preview Menú', 'url' => '../menu.php', 'icono' => '📋', 'desc' => 'Ver menú']
        ]
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HUB · The Difference</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #e0e0e0;
            min-height: 100vh;
            padding: 2rem;
            background-image: 
                radial-gradient(circle at 20% 30%, rgba(255, 105, 180, 0.05) 0%, transparent 50%),
                radial-gradient(circle at 80% 70%, rgba(156, 39, 176, 0.05) 0%, transparent 50%);
        }
        
        .container { max-width: 1400px; margin: 0 auto; }
        
        /* HEADER */
        .header {
            text-align: center;
            margin-bottom: 3rem;
            padding: 2rem;
            border-bottom: 1px solid #333;
        }
        .header h1 {
            color: #fffc34;
            font-size: 2.5rem;
            letter-spacing: 6px;
            margin-bottom: 0.5rem;
            text-shadow: 0 0 20px rgba(255, 252, 52, 0.3);
        }
        .header .subtitle {
            color: #ff69b4;
            font-size: 0.9rem;
            letter-spacing: 3px;
            text-transform: uppercase;
        }
        .header .president {
            color: #666;
            font-size: 0.75rem;
            margin-top: 1rem;
            letter-spacing: 2px;
        }
        
        /* OUROBOROS CENTRAL */
        .ouroboros-center {
            display: flex;
            justify-content: center;
            margin-bottom: 3rem;
        }
        .ouroboros-ring {
            width: 180px;
            height: 180px;
            border: 3px solid #ff69b4;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            animation: rotate 30s linear infinite;
            box-shadow: 0 0 40px rgba(255, 105, 180, 0.2);
        }
        .ouroboros-ring::before {
            content: '';
            position: absolute;
            inset: 10px;
            border: 2px dashed #fffc34;
            border-radius: 50%;
            opacity: 0.5;
        }
        .ouroboros-center-text {
            animation: counter-rotate 30s linear infinite;
            text-align: center;
        }
        .ouroboros-center-text .icon { font-size: 3rem; display: block; }
        .ouroboros-center-text .label {
            color: #fffc34;
            font-size: 0.7rem;
            letter-spacing: 2px;
            margin-top: 0.5rem;
        }
        @keyframes rotate { from { transform: rotate(0deg); } to { transform: rotate(360deg); } }
        @keyframes counter-rotate { from { transform: rotate(0deg); } to { transform: rotate(-360deg); } }
        
        /* ESPACIOS GRID */
        .espacios-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(380px, 1fr));
            gap: 1.5rem;
            margin-bottom: 3rem;
        }
        
        .espacio-card {
            background: #151515;
            border: 1px solid #333;
            border-radius: 12px;
            overflow: hidden;
            transition: all 0.3s;
        }
        .espacio-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 15px 40px rgba(0,0,0,0.5);
        }
        
        .espacio-header {
            padding: 1.5rem;
            border-bottom: 1px solid #2a2a2a;
            position: relative;
        }
        .espacio-header::before {
            content: '';
            position: absolute;
            top: 0; left: 0; right: 0;
            height: 3px;
            background: var(--espacio-color);
        }
        
        .espacio-title-row {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 0.5rem;
        }
        .espacio-icon {
            font-size: 2rem;
            margin-right: 0.75rem;
        }
        .espacio-nombre {
            color: var(--espacio-color);
            font-size: 1rem;
            letter-spacing: 2px;
            font-weight: bold;
        }
        .espacio-subtitulo {
            color: #a0a0a0;
            font-size: 0.75rem;
            margin-top: 0.25rem;
            font-style: italic;
        }
        .espacio-desc {
            color: #666;
            font-size: 0.8rem;
            margin-top: 0.75rem;
            line-height: 1.4;
        }
        .espacio-badge {
            background: var(--espacio-color);
            color: #000;
            padding: 0.2rem 0.6rem;
            border-radius: 10px;
            font-size: 0.65rem;
            font-weight: bold;
            white-space: nowrap;
        }
        
        .espacio-accesos {
            padding: 1rem 1.5rem 1.5rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .acceso-btn {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            padding: 0.75rem 1rem;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 6px;
            color: #e0e0e0;
            text-decoration: none;
            transition: all 0.2s;
            position: relative;
            overflow: hidden;
        }
        .acceso-btn::before {
            content: '';
            position: absolute;
            left: 0; top: 0; bottom: 0;
            width: 3px;
            background: var(--espacio-color);
            transform: scaleY(0);
            transition: transform 0.2s;
        }
        .acceso-btn:hover {
            background: #252525;
            border-color: var(--espacio-color);
            transform: translateX(5px);
        }
        .acceso-btn:hover::before {
            transform: scaleY(1);
        }
        .acceso-icon { font-size: 1.2rem; }
        .acceso-info { flex: 1; }
        .acceso-nombre {
            font-size: 0.85rem;
            font-weight: bold;
            color: #fff;
        }
        .acceso-desc {
            font-size: 0.7rem;
            color: #666;
            margin-top: 0.1rem;
        }
        .acceso-arrow {
            color: #555;
            transition: all 0.2s;
        }
        .acceso-btn:hover .acceso-arrow {
            color: var(--espacio-color);
            transform: translateX(3px);
        }
        
        /* STATS BAR */
        .stats-bar {
            display: flex;
            justify-content: center;
            gap: 2rem;
            flex-wrap: wrap;
            padding: 1.5rem;
            background: #151515;
            border: 1px solid #333;
            border-radius: 12px;
            margin-bottom: 2rem;
        }
        .stat-item { text-align: center; }
        .stat-value {
            color: #fffc34;
            font-size: 1.8rem;
            font-weight: bold;
        }
        .stat-label {
            color: #666;
            font-size: 0.7rem;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-top: 0.25rem;
        }
        
        /* FOOTER */
        .footer {
            text-align: center;
            padding: 2rem;
            color: #444;
            font-size: 0.75rem;
            letter-spacing: 2px;
            border-top: 1px solid #1a1a1a;
            margin-top: 3rem;
        }
        .footer strong { color: #ff69b4; }
        
        /* LEYENDA DE COLORES */
        .leyenda {
            display: flex;
            justify-content: center;
            gap: 1.5rem;
            flex-wrap: wrap;
            padding: 0.75rem 1.5rem;
            background: #151515;
            border: 1px solid #333;
            border-radius: 20px;
            margin-bottom: 2rem;
            width: fit-content;
            margin-left: auto;
            margin-right: auto;
        }
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 0.4rem;
            font-size: 0.7rem;
            color: #a0a0a0;
        }
        .dot {
            width: 10px;
            height: 10px;
            border-radius: 50%;
        }
        
        /* RESPONSIVE */
        @media (max-width: 768px) {
            body { padding: 1rem; }
            .header h1 { font-size: 1.8rem; letter-spacing: 4px; }
            .espacios-grid { grid-template-columns: 1fr; }
            .stats-bar { gap: 1rem; }
        }
    </style>
</head>
<body>
    <div class="container">
        
        <!-- HEADER -->
        <div class="header">
            <h1>THE DIFFERENCE</h1>
            <div class="subtitle">Fundación Ars Tekne · Hub Central</div>
            <div class="president">Presidente Humano · José Miguel Cortez · In Lak'ech</div>
        </div>
        
        <!-- OUROBOROS CENTRAL -->
        <div class="ouroboros-center">
            <div class="ouroboros-ring">
                <div class="ouroboros-center-text">
                    <span class="icon">∞</span>
                    <div class="label">OPUS MAGNUM</div>
                </div>
            </div>
        </div>
        
        <!-- LEYENDA -->
        <div class="leyenda">
            <span class="leyenda-item"><span class="dot" style="background:#9c27b0"></span> Gobernanza</span>
            <span class="leyenda-item"><span class="dot" style="background:#ff69b4"></span> Ars · Tekne</span>
            <span class="leyenda-item"><span class="dot" style="background:#00bcd4"></span> Consejo IAs</span>
            <span class="leyenda-item"><span class="dot" style="background:#fffc34"></span> Memoria</span>
            <span class="leyenda-item"><span class="dot" style="background:#10b981"></span> Guardianes</span>
            <span class="leyenda-item"><span class="dot" style="background:#f59e0b"></span> Sistema</span>
        </div>
        
        <!-- STATS -->
        <div class="stats-bar">
            <div class="stat-item">
                <div class="stat-value"><?= $totalBitacoras ?></div>
                <div class="stat-label">Bitácoras</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $totalLogPersonal ?></div>
                <div class="stat-label">Registros LOG</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $cuentasConfiguradas ?>/4</div>
                <div class="stat-label">Cuentas Gmail</div>
            </div>
            <div class="stat-item">
                <div class="stat-value"><?= $iasActivas ?>/3</div>
                <div class="stat-label">IAs Activas</div>
            </div>
        </div>
        
        <!-- GRID DE ESPACIOS -->
        <div class="espacios-grid">
            <?php foreach ($espacios as $key => $espacio): ?>
                <div class="espacio-card" style="--espacio-color: <?= $espacio['color'] ?>;">
                    <div class="espacio-header">
                        <div class="espacio-title-row">
                            <div>
                                <span class="espacio-icon"><?= $espacio['icono'] ?></span>
                                <span class="espacio-nombre"><?= $espacio['nombre'] ?></span>
                            </div>
                            <?php if (!empty($espacio['badge'])): ?>
                                <span class="espacio-badge"><?= $espacio['badge'] ?></span>
                            <?php endif; ?>
                        </div>
                        <div class="espacio-subtitulo"><?= $espacio['subtitulo'] ?></div>
                        <div class="espacio-desc"><?= $espacio['descripcion'] ?></div>
                    </div>
                    
                    <div class="espacio-accesos">
                        <?php foreach ($espacio['accesos'] as $acceso): ?>
                            <a href="<?= htmlspecialchars($acceso['url']) ?>" class="acceso-btn" 
                               <?= strpos($acceso['url'], '../') === 0 || strpos($acceso['url'], '.html') !== false ? 'target="_blank"' : '' ?>>
                                <span class="acceso-icon"><?= $acceso['icono'] ?></span>
                                <div class="acceso-info">
                                    <div class="acceso-nombre"><?= htmlspecialchars($acceso['nombre']) ?></div>
                                    <div class="acceso-desc"><?= htmlspecialchars($acceso['desc']) ?></div>
                                </div>
                                <span class="acceso-arrow">→</span>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- FOOTER -->
        <div class="footer">
            <strong>Fundación Ars Tekne</strong> · <?= date('Y') ?> · El decir es huella · La huella es trazabilidad
        </div>
        
    </div>
</body>
</html>