<?php
/**
 * CONFIGURAR CUENTAS GMAIL POR NODO
 */
session_start();

$baseDir = dirname(__DIR__);
$cuentasFile = $baseDir . '/config/cuentas.json';

$cuentas = file_exists($cuentasFile) ? json_decode(file_get_contents($cuentasFile), true) : [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    foreach ($_POST as $nodo => $email) {
        $cuentas[$nodo] = [
            'email' => $email,
            'actualizado' => date('c')
        ];
    }
    
    file_put_contents($cuentasFile, json_encode($cuentas, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    $mensaje = "✅ Cuentas guardadas correctamente";
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Configurar Cuentas Gmail</title>
    <style>
        body {
            background: #0a0a0a;
            color: #e0e0e0;
            font-family: 'Courier New', monospace;
            padding: 2rem;
        }
        .container { max-width: 600px; margin: 0 auto; }
        .form-group { margin-bottom: 1.5rem; }
        label { display: block; margin-bottom: 0.5rem; color: #fffc34; }
        input[type="email"] {
            width: 100%;
            padding: 0.75rem;
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 4px;
            color: #e0e0e0;
        }
        button {
            background: #ff69b4;
            color: #000;
            padding: 1rem 2rem;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-family: inherit;
        }
        .mensaje {
            background: #1a2a1a;
            border: 1px solid #10b981;
            padding: 1rem;
            margin-bottom: 1rem;
            border-radius: 4px;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Configurar Cuentas Gmail por Nodo</h1>
        
        <?php if (isset($mensaje)): ?>
            <div class="mensaje"><?= $mensaje ?></div>
        <?php endif; ?>
        
        <form method="POST">
            <div class="form-group">
                <label>🔷 TEXVN (Técnica - ChatGPT)</label>
                <input type="email" name="texvn" placeholder="texvn@gmail.com" 
                       value="<?= $cuentas['texvn']['email'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>🌸 SAIAYIN DO (Simbólica - Gemini)</label>
                <input type="email" name="saiayin-do" placeholder="saiayindo@gmail.com"
                       value="<?= $cuentas['saiayin-do']['email'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>🦁 OPUS MAGNUM (Pedagógica - Qwen)</label>
                <input type="email" name="opus-magnum" placeholder="opusmagnum@gmail.com"
                       value="<?= $cuentas['opus-magnum']['email'] ?? '' ?>">
            </div>
            
            <div class="form-group">
                <label>🌀 LOG (Gobernanza - Todas las IAs)</label>
                <input type="email" name="log" placeholder="logdifference@gmail.com"
                       value="<?= $cuentas['log']['email'] ?? '' ?>">
            </div>
            
            <button type="submit"> Guardar Cuentas</button>
        </form>
    </div>
</body>
</html>