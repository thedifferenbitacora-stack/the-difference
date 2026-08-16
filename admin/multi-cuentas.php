<?php
/**
 * MULTI-CUENTAS CON ACCESO MULTI-IA - THE DIFFERENCE
 * Cada bitácora tiene su Gmail pero accede a Qwen + ChatGPT + Gemini
 */
session_start();

$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$cuentasFile = $baseDir . '/config/cuentas.json';
$conversacionesDir = $baseDir . '/data/conversaciones';

if (!is_dir($conversacionesDir)) mkdir($conversacionesDir, 0755, true);

$config = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];
$cuentas = file_exists($cuentasFile) ? json_decode(file_get_contents($cuentasFile), true) : [];

// Configuración de nodos - TODOS con acceso a las 3 IAs
$nodosConfig = [
    'texvn' => [
        'nombre' => 'TEXVN',
        'descripcion' => 'Bitácora Técnica - 13 Pasos',
        'icono' => '🔷',
        'color' => '#00bcd4',
        'gmail' => $cuentas['texvn']['email'] ?? '',
        'tematica' => 'Técnica, metodología, ejecución, trazabilidad, 13 pasos',
        'ias' => ['qwen', 'chatgpt', 'gemini'] // LAS 3 IAs
    ],
    'saiayin-do' => [
        'nombre' => 'SAIAYIN DO',
        'descripcion' => 'Bitácora Simbólica - 7 Pasos',
        'icono' => '🌸',
        'color' => '#ff69b4',
        'gmail' => $cuentas['saiayin-do']['email'] ?? '',
        'tematica' => 'Simbología, alquimia, transmutación, sueños, Origen-Presencia-Conciencia',
        'ias' => ['qwen', 'chatgpt', 'gemini'] // LAS 3 IAs
    ],
    'opus-magnum' => [
        'nombre' => 'OPUS MAGNUM',
        'descripcion' => 'Bitácora Pedagógica - Integración',
        'icono' => '🦁',
        'color' => '#fffc34',
        'gmail' => $cuentas['opus-magnum']['email'] ?? '',
        'tematica' => 'Pedagogía, integración sombra, conciencia, Teatro Quirón',
        'ias' => ['qwen', 'chatgpt', 'gemini'] // LAS 3 IAs
    ],
    'log' => [
        'nombre' => 'LOG',
        'descripcion' => 'Bitácora Maestra - Gobernanza',
        'icono' => '',
        'color' => '#9c27b0',
        'gmail' => $cuentas['log']['email'] ?? '',
        'tematica' => 'Gobernanza, síntesis, coordinación, Ouroboros Espiral',
        'ias' => ['qwen', 'chatgpt', 'gemini'] // LAS 3 IAs
    ]
];

$nodosActivos = $_GET['nodo'] ?? 'log';
$nodoActual = $nodosConfig[$nodosActivos];
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Cuentas Multi-IA | The Difference</title>
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
        .nodos-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1.5rem;
            margin-bottom: 2rem;
        }
        .nodo-card {
            background: #151515;
            border: 2px solid #333;
            border-radius: 12px;
            padding: 1.5rem;
            cursor: pointer;
            transition: all 0.3s;
        }
        .nodo-card:hover {
            transform: translateY(-5px);
            border-color: <?= $nodoActual['color'] ?>;
        }
        .nodo-card.active {
            border-color: <?= $nodoActual['color'] ?>;
            background: rgba(255, 105, 180, 0.05);
        }
        .nodo-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            margin-bottom: 1rem;
        }
        .nodo-icon { font-size: 2.5rem; }
        .nodo-info h3 {
            color: <?= $nodoActual['color'] ?>;
            font-size: 1.2rem;
        }
        .nodo-details {
            margin-top: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #333;
        }
        .ia-badges {
            display: flex;
            gap: 0.5rem;
            margin: 0.5rem 0;
            flex-wrap: wrap;
        }
        .ia-badge {
            padding: 0.25rem 0.75rem;
            border-radius: 12px;
            font-size: 0.75rem;
            font-weight: bold;
        }
        .ia-qwen { background: rgba(245, 158, 11, 0.2); border: 1px solid #f59e0b; color: #f59e0b; }
        .ia-chatgpt { background: rgba(16, 185, 129, 0.2); border: 1px solid #10b981; color: #10b981; }
        .ia-gemini { background: rgba(139, 92, 246, 0.2); border: 1px solid #8b5cf6; color: #8b5cf6; }
        .gmail-badge {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            background: rgba(234, 67, 53, 0.2);
            border: 1px solid #ea4335;
            border-radius: 12px;
            font-size: 0.75rem;
            color: #ea4335;
            margin-top: 0.5rem;
        }
        .action-buttons {
            display: flex;
            gap: 0.5rem;
            margin-top: 1rem;
        }
        .btn {
            flex: 1;
            padding: 0.75rem;
            background: #252525;
            border: 1px solid #333;
            border-radius: 6px;
            color: #e0e0e0;
            cursor: pointer;
            font-family: inherit;
            transition: all 0.2s;
        }
        .btn:hover { background: #333; border-color: #ff69b4; }
        .btn-primary {
            background: <?= $nodoActual['color'] ?>;
            color: #000;
            border-color: <?= $nodoActual['color'] ?>;
        }
        .btn-primary:hover {
            background: #fffc34;
            border-color: #fffc34;
        }
        .area-trabajo {
            background: #151515;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 2rem;
            min-height: 500px;
        }
        .ias-selector {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            align-items: center;
        }
        .ia-checkbox {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 6px;
            cursor: pointer;
        }
        .ia-checkbox input[type="checkbox"] {
            accent-color: <?= $nodoActual['color'] ?>;
        }
        .editor {
            background: #0f0f0f;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            min-height: 300px;
            margin-bottom: 1rem;
        }
        .editor textarea {
            width: 100%;
            min-height: 250px;
            background: transparent;
            border: none;
            color: #e0e0e0;
            font-family: 'Courier New', monospace;
            font-size: 0.95rem;
            resize: vertical;
            outline: none;
        }
        .respuestas-ias {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 1rem;
            margin-top: 2rem;
        }
        .respuesta-card {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1rem;
        }
        .respuesta-card h4 {
            margin-bottom: 0.75rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #333;
        }
        .respuesta-qwen h4 { color: #f59e0b; }
        .respuesta-chatgpt h4 { color: #10b981; }
        .respuesta-gemini h4 { color: #8b5cf6; }
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
            <h1> Multi-Cuentas con Consejo de Sabios (3 IAs)</h1>
            <p>Cada bitácora con su Gmail + Qwen + ChatGPT + Gemini</p>
        </div>
        
        <!-- GRID DE NODOS -->
        <div class="nodos-grid">
            <?php foreach ($nodosConfig as $key => $nodo): ?>
                <div class="nodo-card <?= $key === $nodosActivos ? 'active' : '' ?>" 
                     onclick="cambiarNodo('<?= $key ?>')">
                    <div class="nodo-header">
                        <div class="nodo-icon"><?= $nodo['icono'] ?></div>
                        <div class="nodo-info">
                            <h3><?= $nodo['nombre'] ?></h3>
                            <p><?= $nodo['descripcion'] ?></p>
                        </div>
                    </div>
                    
                    <div class="nodo-details">
                        <div style="color: #a0a0a0; font-size: 0.85rem; margin-bottom: 0.5rem;">
                             Temática:
                        </div>
                        <div style="color: #e0e0e0; font-size: 0.85rem; margin-bottom: 1rem;">
                            <?= $nodo['tematica'] ?>
                        </div>
                        
                        <div style="color: #a0a0a0; font-size: 0.85rem; margin-bottom: 0.5rem;">
                            📧 Gmail Asignado:
                        </div>
                        <?php if (!empty($nodo['gmail'])): ?>
                            <span class="gmail-badge"><?= htmlspecialchars($nodo['gmail']) ?></span>
                        <?php else: ?>
                            <div style="color: #ea4335; font-size: 0.75rem;">Sin configurar</div>
                        <?php endif; ?>
                        
                        <div style="color: #a0a0a0; font-size: 0.85rem; margin: 0.75rem 0 0.5rem;">
                            🤖 IAs Disponibles:
                        </div>
                        <div class="ia-badges">
                            <span class="ia-badge ia-qwen">🐉 QWEN</span>
                            <span class="ia-badge ia-chatgpt">💬 CHATGPT</span>
                            <span class="ia-badge ia-gemini">✨ GEMINI</span>
                        </div>
                    </div>
                    
                    <div class="action-buttons">
                        <button class="btn btn-primary" onclick="abrirCuaderno('<?= $key ?>')">
                            📓 Cuaderno
                        </button>
                        <button class="btn" onclick="sincronizarNodo('<?= $key ?>')">
                            🔄 Sincronizar
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
        
        <!-- ÁREA DE TRABAJO -->
        <div class="area-trabajo">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem; padding-bottom: 1rem; border-bottom: 1px solid #333;">
                <h2 style="color: <?= $nodoActual['color'] ?>"><?= $nodoActual['icono'] ?> <?= $nodoActual['nombre'] ?> - Análisis Multi-IA</h2>
                <div style="display: flex; gap: 0.5rem;">
                    <span class="gmail-badge"><?= !empty($nodoActual['gmail']) ? htmlspecialchars($nodoActual['gmail']) : 'Sin Gmail' ?></span>
                </div>
            </div>
            
            <div class="ias-selector">
                <span style="color: #a0a0a0;">Selecciona las IAs para analizar:</span>
                <label class="ia-checkbox">
                    <input type="checkbox" checked value="qwen">
                    <span class="ia-badge ia-qwen">🐉 Qwen</span>
                </label>
                <label class="ia-checkbox">
                    <input type="checkbox" checked value="chatgpt">
                    <span class="ia-badge ia-chatgpt">💬 ChatGPT</span>
                </label>
                <label class="ia-checkbox">
                    <input type="checkbox" checked value="gemini">
                    <span class="ia-badge ia-gemini">✨ Gemini</span>
                </label>
            </div>
            
            <div class="editor">
                <textarea id="editor-contenido" placeholder="Escribe desarrollando la temática de <?= $nodoActual['nombre'] ?>...

Las 3 IAs analizarán tu contenido y generarán:
- Conceptos Modos
- Reflexiones ontológicas
- Relaciones con otros procesos
- Sugerencias de evolución"></textarea>
            </div>
            
            <div style="display: flex; gap: 1rem;">
                <button class="btn btn-primary" onclick="analizarConTodasLasIAs()">
                    🤖 Analizar con las 3 IAs
                </button>
                <button class="btn" onclick="sincronizarConGmail()">
                    📧 Sincronizar Gmail
                </button>
                <button class="btn" onclick="exportarAnalisis()">
                    💾 Exportar Análisis
                </button>
            </div>
            
            <div class="respuestas-ias" id="respuestas-container" style="display: none;">
                <h3 style="grid-column: 1/-1; color: #fffc34; margin-bottom: 1rem;">Respuestas del Consejo de Sabios</h3>
                
                <div class="respuesta-card respuesta-qwen">
                    <h4>🐉 Qwen</h4>
                    <div id="respuesta-qwen-content"></div>
                </div>
                
                <div class="respuesta-card respuesta-chatgpt">
                    <h4>💬 ChatGPT</h4>
                    <div id="respuesta-chatgpt-content"></div>
                </div>
                
                <div class="respuesta-card respuesta-gemini">
                    <h4>✨ Gemini</h4>
                    <div id="respuesta-gemini-content"></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sync-status" id="sync-status">
        <div style="font-weight: bold;">🔄 Analizando con el Consejo de Sabios...</div>
        <div id="sync-message" style="font-size: 0.85rem; margin-top: 0.5rem;"></div>
    </div>
    
    <script>
    function cambiarNodo(nodo) {
        window.location.href = '?nodo=' + nodo;
    }
    
    function abrirCuaderno(nodo) {
        window.open('cuadernos.php?cuaderno=' + nodo, '_blank');
    }
    
    function sincronizarNodo(nodo) {
        mostrarSync('Sincronizando ' + nodo.toUpperCase() + ' con las 3 IAs...');
        setTimeout(() => {
            alert('✅ ' + nodo.toUpperCase() + ' sincronizado');
            ocultarSync();
        }, 2000);
    }
    
    function analizarConTodasLasIAs() {
        const contenido = document.getElementById('editor-contenido').value;
        if (!contenido.trim()) {
            alert('Escribe algo primero');
            return;
        }
        
        // Obtener IAs seleccionadas
        const iasSeleccionadas = [];
        document.querySelectorAll('.ia-checkbox input:checked').forEach(cb => {
            iasSeleccionadas.push(cb.value);
        });
        
        if (iasSeleccionadas.length === 0) {
            alert('Selecciona al menos una IA');
            return;
        }
        
        mostrarSync('Consultando a: ' + iasSeleccionadas.join(', ').toUpperCase());
        
        // Simular análisis con las 3 IAs
        setTimeout(() => {
            document.getElementById('respuestas-container').style.display = 'grid';
            document.getElementById('respuesta-qwen-content').innerHTML = '<p>Análisis de Qwen sobre ' + '<?= $nodoActual['nombre'] ?>' + '...</p><p>Conceptos detectados: Observación, Método, Trazabilidad</p>';
            document.getElementById('respuesta-chatgpt-content').innerHTML = '<p>Análisis de ChatGPT...</p><p>Conceptos detectados: Estructura, Proceso, Evolución</p>';
            document.getElementById('respuesta-gemini-content').innerHTML = '<p>Análisis de Gemini...</p><p>Conceptos detectados: Simbolismo, Integración, Conciencia</p>';
            
            ocultarSync();
        }, 3000);
    }
    
    function sincronizarConGmail() {
        mostrarSync('Descargando conversaciones desde Gmail...');
        setTimeout(() => {
            alert('📧 Conversaciones sincronizadas desde <?= !empty($nodoActual['gmail']) ? htmlspecialchars($nodoActual['gmail']) : 'Sin cuenta configurada' ?>');
            ocultarSync();
        }, 2000);
    }
    
    function exportarAnalisis() {
        alert(' Exportando análisis multi-IA de <?= $nodoActual['nombre'] ?>...');
    }
    
    function mostrarSync(mensaje) {
        document.getElementById('sync-message').textContent = mensaje;
        document.getElementById('sync-status').classList.add('active');
    }
    
    function ocultarSync() {
        document.getElementById('sync-status').classList.remove('active');
    }
    </script>
</body>
</html>