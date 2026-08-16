<?php
/**
 * LOG PERSONAL - THE DIFFERENCE
 * Trazabilidad: Niñes → Juventud → Adultes → Proyecto Actual
 */
session_start();

$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$logPersonalFile = $baseDir . '/data/log-personal.json';
$bitacoraFile = $baseDir . '/config/bitacora.json';

if (!file_exists($logPersonalFile)) {
    $logInicial = [
        'niñes' => [],
        'juventud' => [],
        'adultes' => [],
        'proyecto_actual' => [],
        'conexiones' => []
    ];
    file_put_contents($logPersonalFile, json_encode($logInicial, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

$logPersonal = json_decode(file_get_contents($logPersonalFile), true);
$config = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];

$etapaActiva = $_GET['etapa'] ?? 'niñes';

$etapas = [
    'niñes' => [
        'nombre' => 'Niñes',
        'icono' => '🌱',
        'color' => '#10b981',
        'descripcion' => 'Semillas, primeras huellas, escucha original',
        'rango_edad' => '0-12 años'
    ],
    'juventud' => [
        'nombre' => 'Juventud',  // ✅ CORREGIDO: Agregado =>
        'icono' => '🔥',
        'color' => '#f59e0b',
        'descripcion' => 'Búsqueda, pérdida del oficio, cicatrices',
        'rango_edad' => '13-25 años'
    ],
    'adultes' => [
        'nombre' => 'Adultes',
        'icono' => '🦁',
        'color' => '#ff69b4',
        'descripcion' => 'Transmutación, conciencia, luz generada',
        'rango_edad' => '26+ años'
    ],
    'proyecto' => [
        'nombre' => 'Proyecto Actual',
        'icono' => '🌀',
        'color' => '#9c27b0',
        'descripcion' => 'The Difference - Fundación Ars Tekne',
        'rango_edad' => 'Presente'
    ]
];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LOG Personal | The Difference</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #e0e0e0;
            min-height: 100vh;
            padding: 2rem;
        }
        .container { max-width: 1400px; margin: 0 auto; }
        .header {
            border-bottom: 2px solid #ff69b4;
            padding-bottom: 1rem;
            margin-bottom: 2rem;
        }
        .header h1 {
            color: #fffc34;
            font-size: 1.8rem;
            letter-spacing: 3px;
        }
        .header p { color: #a0a0a0; margin-top: 0.5rem; }
        
        .timeline-nav {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 1rem;
            margin-bottom: 2rem;
        }
        .etapa-card {
            background: #151515;
            border: 2px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
            text-align: center;
        }
        .etapa-card:hover {
            transform: translateY(-3px);
            border-color: <?= $etapas[$etapaActiva]['color'] ?>;
        }
        .etapa-card.active {
            border-color: <?= $etapas[$etapaActiva]['color'] ?>;
            background: rgba(255, 105, 180, 0.1);
        }
        .etapa-icon { font-size: 2.5rem; margin-bottom: 0.5rem; }
        .etapa-nombre {
            color: <?= $etapas[$etapaActiva]['color'] ?>;
            font-weight: bold;
            margin-bottom: 0.25rem;
        }
        .etapa-edad {
            font-size: 0.75rem;
            color: #666;
            margin-bottom: 0.5rem;
        }
        .etapa-count {
            background: #252525;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            color: #10b981;
        }
        
        .main-content {
            display: grid;
            grid-template-columns: 1fr 2fr;
            gap: 2rem;
        }
        .sidebar {
            background: #151515;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            height: fit-content;
        }
        .sidebar h3 {
            color: <?= $etapas[$etapaActiva]['color'] ?>;
            margin-bottom: 1rem;
        }
        .trazabilidad-tree {
            margin-top: 1.5rem;
            padding-top: 1.5rem;
            border-top: 1px solid #333;
        }
        .tree-item {
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background: #1a1a1a;
            border-left: 3px solid #333;
            border-radius: 4px;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .tree-item:hover {
            border-left-color: #ff69b4;
            background: #252525;
        }
        .tree-item.connected {
            border-left-color: #10b981;
        }
        
        .content-area {
            background: #151515;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 2rem;
        }
        .etapa-header {
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333;
        }
        .etapa-header h2 {
            color: <?= $etapas[$etapaActiva]['color'] ?>;
            font-size: 1.5rem;
            margin-bottom: 0.5rem;
        }
        .etapa-header p { color: #a0a0a0; }
        
        .form-group { margin-bottom: 1.5rem; }
        label {
            display: block;
            color: #fffc34;
            margin-bottom: 0.5rem;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }
        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea,
        select {
            width: 100%;
            padding: 0.75rem;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 4px;
            color: #e0e0e0;
            font-family: 'Courier New', monospace;
            font-size: 0.9rem;
        }
        textarea { min-height: 150px; resize: vertical; }
        input:focus, textarea:focus, select:focus {
            outline: none;
            border-color: <?= $etapas[$etapaActiva]['color'] ?>;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            background: <?= $etapas[$etapaActiva]['color'] ?>;
            color: #000;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: inherit;
            font-weight: bold;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #fffc34;
            transform: translateY(-2px);
        }
        
        .entries-list {
            margin-top: 2rem;
        }
        .entry-card {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 1.5rem;
            margin-bottom: 1rem;
            position: relative;
        }
        .entry-card::before {
            content: '';
            position: absolute;
            left: -1px;
            top: 0;
            bottom: 0;
            width: 3px;
            background: <?= $etapas[$etapaActiva]['color'] ?>;
            border-radius: 6px 0 0 6px;
        }
        .entry-header {
            display: flex;
            justify-content: space-between;
            margin-bottom: 1rem;
        }
        .entry-titulo {
            color: #fffc34;
            font-weight: bold;
            font-size: 1.1rem;
        }
        .entry-meta {
            font-size: 0.75rem;
            color: #666;
        }
        .entry-contenido {
            color: #b0b0b0;
            line-height: 1.6;
            margin-bottom: 1rem;
        }
        .entry-conexiones {
            display: flex;
            gap: 0.5rem;
            flex-wrap: wrap;
        }
        .conexion-tag {
            background: rgba(16, 185, 129, 0.2);
            border: 1px solid #10b981;
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            color: #10b981;
        }
        
        .sync-status {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            background: #151515;
            border: 2px solid #10b981;
            border-radius: 8px;
            display: none;
        }
        .sync-status.active { display: block; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1> LOG Personal - Trazabilidad Evolutiva</h1>
            <p>Niñes → Juventud → Adultes → The Difference</p>
        </div>
        
        <!-- TIMELINE NAV -->
        <div class="timeline-nav">
            <?php foreach ($etapas as $key => $etapa): ?>
                <div class="etapa-card <?= $key === $etapaActiva ? 'active' : '' ?>" 
                     onclick="cambiarEtapa('<?= $key ?>')">
                    <div class="etapa-icon"><?= $etapa['icono'] ?></div>
                    <div class="etapa-nombre"><?= $etapa['nombre'] ?></div>
                    <div class="etapa-edad"><?= $etapa['rango_edad'] ?></div>
                    <div class="etapa-count">
                        <?= count($logPersonal[$key] ?? []) ?> registros
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <div class="main-content">
            <!-- SIDEBAR: TRAZABILIDAD -->
            <div class="sidebar">
                <h3>🌳 Árbol de Trazabilidad</h3>
                <p style="font-size: 0.85rem; color: #666; margin-bottom: 1rem;">
                    Conexiones entre etapas y proyecto actual
                </p>
                
                <div class="trazabilidad-tree">
                    <div class="tree-item connected" onclick="verConexion('niñes-proyecto')">
                        <strong> Niñes → Proyecto</strong>
                        <div style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">
                            <?= count($logPersonal['conexiones']['niñes-proyecto'] ?? []) ?> conexiones
                        </div>
                    </div>
                    
                    <div class="tree-item connected" onclick="verConexion('juventud-proyecto')">
                        <strong>🔥 Juventud → Proyecto</strong>
                        <div style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">
                            <?= count($logPersonal['conexiones']['juventud-proyecto'] ?? []) ?> conexiones
                        </div>
                    </div>
                    
                    <div class="tree-item connected" onclick="verConexion('adultes-proyecto')">
                        <strong>🦁 Adultes → Proyecto</strong>
                        <div style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">
                            <?= count($logPersonal['conexiones']['adultes-proyecto'] ?? []) ?> conexiones
                        </div>
                    </div>
                    
                    <div class="tree-item" onclick="verConexion('todas')" style="margin-top: 1rem; border-left-color: #fffc34;">
                        <strong> Ver Todas las Conexiones</strong>
                    </div>
                </div>
                
                <div style="margin-top: 2rem; padding-top: 1.5rem; border-top: 1px solid #333;">
                    <h4 style="color: #a0a0a0; font-size: 0.85rem; margin-bottom: 1rem;">
                        📊 Estadísticas
                    </h4>
                    <div style="font-size: 0.85rem; color: #666;">
                        <div style="margin-bottom: 0.5rem;">
                            Total registros: 
                            <span style="color: #10b981; font-weight: bold;">
                                <?= array_sum(array_map('count', array_filter($logPersonal, 'is_array'))) ?>
                            </span>
                        </div>
                        <div>
                            Conexiones activas: 
                            <span style="color: #ff69b4; font-weight: bold;">
                                <?= count($logPersonal['conexiones'], COUNT_RECURSIVE) ?>
                            </span>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- CONTENT AREA -->
            <div class="content-area">
                <div class="etapa-header">
                    <h2><?= $etapas[$etapaActiva]['icono'] ?> <?= $etapas[$etapaActiva]['nombre'] ?></h2>
                    <p><?= $etapas[$etapaActiva]['descripcion'] ?></p>
                </div>
                
                <!-- FORMULARIO NUEVO REGISTRO -->
                <form id="form-nuevo-registro" onsubmit="guardarRegistro(event)">
                    <div class="form-group">
                        <label>Título del Registro</label>
                        <input type="text" name="titulo" placeholder="Ej: Mi primera escucha del viento" required>
                    </div>
                    
                    <?php if ($etapaActiva !== 'proyecto'): ?>
                    <div class="form-group">
                        <label>Edad Aproximada</label>
                        <input type="number" name="edad" min="0" max="120" placeholder="Ej: 7 años">
                    </div>
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label>Fecha (opcional)</label>
                        <input type="date" name="fecha">
                    </div>
                    
                    <div class="form-group">
                        <label>Contenido / Reflexión</label>
                        <textarea name="contenido" placeholder="<?= 
                            $etapaActiva === 'niñes' ? 'Describe experiencias de tu niñez, primeras escucha, juegos, sueños...' :
                            ($etapaActiva === 'juventud' ? 'Describe tu búsqueda, pérdidas, descubrimientos, heridas...' :
                            ($etapaActiva === 'adultes' ? 'Describe tu proceso de transmutación, conciencia, luz generada...' :
                            'Describe tu trabajo actual en The Difference, proyectos, avances...'))
                        ?>" required></textarea>
                    </div>
                    
                    <?php if ($etapaActiva !== 'proyecto'): ?>
                    <div class="form-group">
                        <label>Conectar con Proyecto Actual</label>
                        <select name="conexion_proyecto">
                            <option value="">Sin conexión</option>
                            <option value="texvn">TEXVN - Bitácora Técnica</option>
                            <option value="saiayin-do">SAIAYIN DO - Bitácora Simbólica</option>
                            <option value="opus-magnum">OPUS MAGNUM - Bitácora Pedagógica</option>
                            <option value="log">LOG - Bitácora Maestra</option>
                        </select>
                        <p style="font-size: 0.75rem; color: #666; margin-top: 0.25rem;">
                            Establece cómo esta experiencia se conecta con tu trabajo actual
                        </p>
                    </div>
                    <?php endif; ?>
                    
                    <button type="submit" class="btn">
                        💾 Guardar en <?= strtoupper($etapas[$etapaActiva]['nombre']) ?>
                    </button>
                </form>
                
                <!-- LISTA DE REGISTROS -->
                <div class="entries-list">
                    <h3 style="color: #a0a0a0; margin-bottom: 1rem; font-size: 1.1rem;">
                        Registros en <?= strtoupper($etapas[$etapaActiva]['nombre']) ?>
                    </h3>
                    
                    <?php 
                    $registros = $logPersonal[$etapaActiva] ?? [];
                    if (empty($registros)): 
                    ?>
                        <div style="text-align: center; padding: 3rem; color: #666; font-style: italic;">
                            No hay registros aún en <?= $etapas[$etapaActiva]['nombre'] ?>. 
                            Sé el primero en escribir.
                        </div>
                    <?php else: ?>
                        <?php foreach (array_reverse($registros) as $registro): ?>
                            <div class="entry-card">
                                <div class="entry-header">
                                    <div class="entry-titulo"><?= htmlspecialchars($registro['titulo']) ?></div>
                                    <div class="entry-meta">
                                        <?= date('d/m/Y', strtotime($registro['fecha_creacion'])) ?>
                                        <?php if (isset($registro['edad'])): ?>
                                            | <?= $registro['edad'] ?> años
                                        <?php endif; ?>
                                    </div>
                                </div>
                                
                                <div class="entry-contenido">
                                    <?= nl2br(htmlspecialchars($registro['contenido'])) ?>
                                </div>
                                
                                <?php if (!empty($registro['conexion_proyecto'])): ?>
                                    <div class="entry-conexiones">
                                        <span class="conexion-tag">
                                             Conectado con <?= strtoupper($registro['conexion_proyecto']) ?>
                                        </span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sync-status" id="sync-status">
        <div style="font-weight: bold;">💾 Guardando...</div>
    </div>
    
    <script>
    function cambiarEtapa(etapa) {
        window.location.href = '?etapa=' + etapa;
    }
    
    function guardarRegistro(e) {
        e.preventDefault();
        
        const formData = new FormData(e.target);
        const data = Object.fromEntries(formData);
        
        mostrarSync();
        
        // Simular guardado (aquí iría la llamada AJAX real)
        setTimeout(() => {
            alert('✅ Registro guardado en ' + '<?= strtoupper($etapas[$etapaActiva]['nombre']) ?>' + 
                  (data.conexion_proyecto ? ' y conectado con ' + data.conexion_proyecto.toUpperCase() : ''));
            ocultarSync();
            // Recargar página para ver el nuevo registro
            location.reload();
        }, 1000);
    }
    
    function verConexion(tipo) {
        alert('Mostrando conexiones: ' + tipo);
    }
    
    function mostrarSync() {
        document.getElementById('sync-status').classList.add('active');
    }
    
    function ocultarSync() {
        document.getElementById('sync-status').classList.remove('active');
    }
    </script>
</body>
</html>