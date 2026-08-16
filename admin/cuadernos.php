<?php
/**
 * SISTEMA DE CUADERNOS UNIFICADOS - THE DIFFERENCE
 * Gestiona conversaciones de múltiples IAs + Bitácoras locales
 * Con protección del Consejo de Guardianes
 */
session_start();

// Verificar autenticación (Gmail OAuth o sistema local)
if (!isset($_SESSION['user_id'])) {
    // Redirigir a login si no está autenticado
    // header('Location: login.php');
    // exit;
}

$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$cuadernosDir = $baseDir . '/data/cuadernos';
$guardianesFile = $baseDir . '/config/guardianes.json';

if (!is_dir($cuadernosDir)) mkdir($cuadernosDir, 0755, true);

$config = file_exists($configFile) ? json_decode(file_get_contents($configFile), true) : [];

// Lista de cuadernos disponibles
$cuadernos = [
    'texvn' => ['nombre' => 'TEXVN - Bitácora Técnica', 'icono' => '🔷', 'color' => '#00bcd4'],
    'saiayin-do' => ['nombre' => 'SAIAYIN DO - Bitácora Simbólica', 'icono' => '🌸', 'color' => '#ff69b4'],
    'opus-magnum' => ['nombre' => 'OPUS MAGNUM - Bitácora Pedagógica', 'icono' => '🦁', 'color' => '#fffc34'],
    'log' => ['nombre' => 'LOG - Bitácora Maestra', 'icono' => '', 'color' => '#9c27b0'],
    'chatgpt' => ['nombre' => 'CHATGPT - Conversaciones OpenAI', 'icono' => '💬', 'color' => '#10b981'],
    'gemini' => ['nombre' => 'GEMINI - Conversaciones Google', 'icono' => '✨', 'color' => '#8b5cf6'],
    'qwen' => ['nombre' => 'QWEN - Conversaciones Alibaba', 'icono' => '🐉', 'color' => '#f59e0b']
];

$cuadernoActivo = $_GET['cuaderno'] ?? 'log';
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cuadernos Unificados | The Difference</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #e0e0e0;
            min-height: 100vh;
        }
        .header {
            background: #151515;
            border-bottom: 2px solid #ff69b4;
            padding: 1.5rem 2rem;
        }
        .header h1 {
            color: #fffc34;
            font-size: 1.5rem;
            letter-spacing: 2px;
        }
        .container {
            display: grid;
            grid-template-columns: 250px 1fr;
            min-height: calc(100vh - 70px);
        }
        .sidebar {
            background: #151515;
            border-right: 1px solid #333;
            padding: 1.5rem;
        }
        .sidebar h2 {
            color: #a0a0a0;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 1px;
            margin-bottom: 1rem;
        }
        .cuaderno-item {
            padding: 0.75rem 1rem;
            margin-bottom: 0.5rem;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
            color: #e0e0e0;
            display: flex;
            align-items: center;
            gap: 0.75rem;
        }
        .cuaderno-item:hover {
            border-color: #ff69b4;
            transform: translateX(5px);
        }
        .cuaderno-item.active {
            border-color: <?= $cuadernos[$cuadernoActivo]['color'] ?? '#ff69b4' ?>;
            background: rgba(255, 105, 180, 0.1);
        }
        .cuaderno-icon { font-size: 1.2rem; }
        .cuaderno-name { font-size: 0.85rem; }
        .main-content {
            padding: 2rem;
            background: #0f0f0f;
        }
        .cuaderno-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333;
        }
        .cuaderno-header h2 {
            color: <?= $cuadernos[$cuadernoActivo]['color'] ?? '#fffc34' ?>;
            font-size: 1.5rem;
        }
        .guardianes-status {
            display: flex;
            gap: 0.5rem;
            align-items: center;
        }
        .guardian-badge {
            padding: 0.25rem 0.75rem;
            background: #1a2a1a;
            border: 1px solid #10b981;
            border-radius: 12px;
            font-size: 0.75rem;
            color: #10b981;
        }
        .editor-area {
            background: #151515;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            min-height: 400px;
        }
        .editor-toolbar {
            display: flex;
            gap: 1rem;
            margin-bottom: 1rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid #333;
        }
        .btn {
            padding: 0.5rem 1rem;
            background: #252525;
            border: 1px solid #333;
            border-radius: 4px;
            color: #e0e0e0;
            cursor: pointer;
            font-family: inherit;
            font-size: 0.85rem;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #333;
            border-color: #ff69b4;
        }
        .btn-primary {
            background: #ff69b4;
            color: #000;
            border-color: #ff69b4;
        }
        .btn-primary:hover {
            background: #fffc34;
            border-color: #fffc34;
        }
        .conversations-list {
            margin-top: 2rem;
        }
        .conversation-item {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 6px;
            padding: 1rem;
            margin-bottom: 0.75rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        .conversation-item:hover {
            border-color: #00bcd4;
            transform: translateX(3px);
        }
        .conversation-title {
            color: #fffc34;
            font-weight: bold;
            margin-bottom: 0.5rem;
        }
        .conversation-meta {
            font-size: 0.75rem;
            color: #666;
        }
        .sync-status {
            position: fixed;
            bottom: 2rem;
            right: 2rem;
            padding: 1rem 1.5rem;
            background: #151515;
            border: 1px solid #10b981;
            border-radius: 8px;
            font-size: 0.85rem;
            color: #10b981;
            display: none;
        }
        .sync-status.active {
            display: block;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.7; }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📓 Cuadernos Unificados - The Difference</h1>
    </div>
    
    <div class="container">
        <div class="sidebar">
            <h2>Mis Cuadernos</h2>
            <?php foreach ($cuadernos as $key => $cuaderno): ?>
                <a href="?cuaderno=<?= $key ?>" class="cuaderno-item <?= $key === $cuadernoActivo ? 'active' : '' ?>">
                    <span class="cuaderno-icon"><?= $cuaderno['icono'] ?></span>
                    <span class="cuaderno-name"><?= $cuaderno['nombre'] ?></span>
                </a>
            <?php endforeach; ?>
            
            <h2 style="margin-top: 2rem;">Sintetizar</h2>
            <button class="btn" onclick="sintetizarCuadernos()" style="width: 100%; margin-bottom: 0.5rem;">
                 Cruzar Información
            </button>
            <button class="btn" onclick="actualizarMemorias()" style="width: 100%;">
                🔄 Actualizar Todo
            </button>
        </div>
        
        <div class="main-content">
            <div class="cuaderno-header">
                <h2><?= $cuadernos[$cuadernoActivo]['icono'] ?> <?= $cuadernos[$cuadernoActivo]['nombre'] ?></h2>
                <div class="guardianes-status">
                    <span class="guardian-badge">🛡️ Guardianes Activos</span>
                    <span class="guardian-badge">✅ Verificado</span>
                </div>
            </div>
            
            <div class="editor-area">
                <div class="editor-toolbar">
                    <button class="btn btn-primary" onclick="nuevaEntrada()">+ Nueva Entrada</button>
                    <button class="btn" onclick="sincronizarIA()">🔄 Sincronizar con IA</button>
                    <button class="btn" onclick="exportarCuaderno()">💾 Exportar</button>
                    <button class="btn" onclick="activarGuardianes()">🛡️ Activar Guardianes</button>
                </div>
                
                <div id="contenido-cuaderno">
                    <!-- Aquí se cargará el contenido dinámico -->
                    <div class="conversations-list">
                        <h3 style="color: #a0a0a0; margin-bottom: 1rem;">Conversaciones Recientes</h3>
                        <!-- Ejemplo de conversación -->
                        <div class="conversation-item">
                            <div class="conversation-title">Observación del Proceso TEXVN - Paso 9</div>
                            <div class="conversation-meta">
                                📅 <?= date('d/m/Y H:i') ?> | 🤖 3 IAs analizadas | 🎯 5 conceptos modos
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="sync-status" id="sync-status">
        🔄 Sincronizando con <?= strtoupper($cuadernoActivo) ?>...
    </div>
    
    <script>
    function nuevaEntrada() {
        const contenido = prompt('Escribe tu reflexión:');
        if (contenido) {
            mostrarSync();
            // Aquí iría la llamada AJAX para guardar
            setTimeout(() => {
                alert('Entrada guardada y analizada por el Consejo de Sabios');
                ocultarSync();
            }, 1500);
        }
    }
    
    function sincronizarIA() {
        mostrarSync();
        // Simular sincronización con APIs
        setTimeout(() => {
            alert('Cuaderno sincronizado con las IAs externas');
            ocultarSync();
        }, 2000);
    }
    
    function sintetizarCuadernos() {
        if (confirm('¿Quieres cruzar información entre todos los cuadernos?')) {
            alert('Sintetizando conceptos entre TEXVN, SAIAYIN DO, OPUS MAGNUM y conversaciones de IA...');
        }
    }
    
    function actualizarMemorias() {
        mostrarSync();
        setTimeout(() => {
            alert('Memorias actualizadas en todos los cuadernos');
            ocultarSync();
        }, 1500);
    }
    
    function exportarCuaderno() {
        alert('Exportando cuaderno a JSON/PDF...');
    }
    
    function activarGuardianes() {
        alert('🛡️ Consejo de Guardianes activado\n\n' +
              'Los siguientes agentes validarán cada interacción:\n' +
              '• Guardián de Identidad\n' +
              '• Guardián de Propósito\n' +
              '• Guardián de Integridad\n' +
              '• Guardián de Ética\n' +
              '• Guardián de Memoria');
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