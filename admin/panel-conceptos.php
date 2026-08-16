<?php
/**
 * PANEL DE NUBE DE CONCEPTOS - THE DIFFERENCE
 * Visualización interactiva de conceptos extraídos por los agentes
 * Filosofía: "Los conceptos son las estrellas del pensamiento"
 */

session_start();
$baseDir = dirname(__DIR__);

// Headers CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// ============================================
// CARGAR DATOS
// ============================================
$bitacoraFile = $baseDir . '/config/bitacora.json';
$memoryDir = $baseDir . '/.memory/conversations';

function leerJSON($archivo) {
    if (!file_exists($archivo)) return [];
    return json_decode(file_get_contents($archivo), true) ?? [];
}

$bitacora = leerJSON($bitacoraFile);

// Cargar todas las conversaciones procesadas
$conversaciones = [];
if (is_dir($memoryDir)) {
    foreach (glob($memoryDir . '/*.json') as $archivo) {
        $conv = leerJSON($archivo);
        if (!empty($conv)) $conversaciones[] = $conv;
    }
}

// ============================================
// PROCESAR CONCEPTOS
// ============================================
$conceptosFrecuencia = [];
$conceptosBitacoras = [];
$terminosDominioGlobal = [];

foreach ($conversaciones as $conv) {
    $id = $conv['id'] ?? '';
    $titulo = $conv['titulo'] ?? 'Sin título';
    $idioma = $conv['metadata']['idioma'] ?? 'es';
    
    // Conceptos del análisis
    $conceptos = $conv['analisis']['conceptos'] ?? [];
    foreach ($conceptos as $concepto) {
        $conceptoLower = mb_strtolower($concepto, 'UTF-8');
        if (!isset($conceptosFrecuencia[$conceptoLower])) {
            $conceptosFrecuencia[$conceptoLower] = 0;
            $conceptosBitacoras[$conceptoLower] = [];
        }
        $conceptosFrecuencia[$conceptoLower]++;
        $conceptosBitacoras[$conceptoLower][] = [
            'id' => $id,
            'titulo' => $titulo,
            'idioma' => $idioma
        ];
    }
    
    // Palabras clave
    $palabrasClave = $conv['analisis']['palabras_clave'] ?? [];
    foreach ($palabrasClave as $pc) {
        $pcLower = mb_strtolower($pc, 'UTF-8');
        if (!isset($conceptosFrecuencia[$pcLower])) {
            $conceptosFrecuencia[$pcLower] = 0;
            $conceptosBitacoras[$pcLower] = [];
        }
        $conceptosFrecuencia[$pcLower]++;
        $conceptosBitacoras[$pcLower][] = [
            'id' => $id,
            'titulo' => $titulo,
            'idioma' => $idioma,
            'tipo' => 'palabra_clave'
        ];
    }
    
    // Términos del dominio
    $terminosDominio = $conv['analisis']['terminos_dominio'] ?? [];
    foreach ($terminosDominio as $td) {
        $tdLower = mb_strtolower($td, 'UTF-8');
        if (!in_array($tdLower, $terminosDominioGlobal)) {
            $terminosDominioGlobal[] = $tdLower;
        }
    }
}

// Ordenar por frecuencia
arsort($conceptosFrecuencia);

// Estadísticas
$totalConceptosUnicos = count($conceptosFrecuencia);
$totalConceptos = array_sum($conceptosFrecuencia);
$maxFrecuencia = !empty($conceptosFrecuencia) ? max($conceptosFrecuencia) : 1;
$minFrecuencia = !empty($conceptosFrecuencia) ? min($conceptosFrecuencia) : 1;

// Conteo por idioma
$idiomasCount = [];
foreach ($conversaciones as $conv) {
    $idioma = $conv['metadata']['idioma'] ?? 'desconocido';
    $idiomasCount[$idioma] = ($idiomasCount[$idioma] ?? 0) + 1;
}

// Top 20 conceptos
$topConceptos = array_slice($conceptosFrecuencia, 0, 20, true);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nube de Conceptos - The Difference</title>
    
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
        
        .controles input[type="range"] {
            width: 200px;
        }
        
        /* Nube de conceptos */
        .nube-container {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 3rem;
            min-height: 400px;
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 1rem;
            margin-bottom: 2rem;
        }
        
        .concepto {
            cursor: pointer;
            transition: all 0.3s;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            border: 1px solid transparent;
            text-align: center;
        }
        
        .concepto:hover {
            transform: scale(1.1);
            border-color: #ff69b4;
            background: rgba(255, 105, 180, 0.1);
        }
        
        .concepto.seleccionado {
            border-color: #fffc34;
            background: rgba(255, 252, 52, 0.2);
        }
        
        /* Panel de detalles */
        .detalles-panel {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 2rem;
            display: none;
        }
        
        .detalles-panel.activo {
            display: block;
        }
        
        .detalles-panel h3 {
            color: #ff69b4;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        
        .detalles-panel .meta {
            color: #a0a0a0;
            font-size: 0.85rem;
            margin-bottom: 1.5rem;
        }
        
        .bitacoras-lista {
            display: grid;
            gap: 1rem;
        }
        
        .bitacora-item {
            background: #252525;
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1rem;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .bitacora-item:hover {
            border-color: #ff69b4;
            transform: translateX(5px);
        }
        
        .bitacora-item .titulo {
            color: #fff;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }
        
        .bitacora-item .meta-item {
            color: #a0a0a0;
            font-size: 0.8rem;
        }
        
        /* Términos del dominio */
        .dominio-section {
            background: #1a1a1a;
            border: 1px solid #333;
            border-radius: 12px;
            padding: 2rem;
            margin-top: 2rem;
        }
        
        .dominio-section h3 {
            color: #fffc34;
            margin-bottom: 1rem;
        }
        
        .dominio-tags {
            display: flex;
            flex-wrap: wrap;
            gap: 0.5rem;
        }
        
        .dominio-tag {
            background: rgba(255, 252, 52, 0.1);
            border: 1px solid #fffc34;
            color: #fffc34;
            padding: 0.3rem 0.8rem;
            border-radius: 15px;
            font-size: 0.8rem;
        }
        
        /* Responsive */
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                gap: 1rem;
                align-items: flex-start;
            }
            
            .stats {
                flex-wrap: wrap;
            }
            
            .nube-container {
                padding: 1.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>☁️ NUBE DE CONCEPTOS</h1>
        <div class="stats">
            <div>Conceptos únicos: <span><?= $totalConceptosUnicos ?></span></div>
            <div>Total menciones: <span><?= $totalConceptos ?></span></div>
            <div>Bitácoras: <span><?= count($bitacora) ?></span></div>
            <div>Idiomas: <span><?= count($idiomasCount) ?></span></div>
        </div>
    </div>
    
    <div class="nav-buttons">
        <a href="panel-grafo.php" class="nav-btn">🕸️ Grafo</a>
        <a href="panel-conceptos.php" class="nav-btn active">☁️ Conceptos</a>
        <a href="bitacora.php" class="nav-btn">📓 Bitácora</a>
        <a href="panel-nodos.php" class="nav-btn"> Nodos</a>
        <a href="../" target="_blank" class="nav-btn">🏠 Ver Sitio</a>
    </div>
    
    <!-- Controles -->
    <div class="controles">
        <label for="filtro-idioma">Idioma:</label>
        <select id="filtro-idioma">
            <option value="todos">Todos</option>
            <?php foreach ($idiomasCount as $idioma => $count): ?>
                <option value="<?= $idioma ?>"><?= strtoupper($idioma) ?> (<?= $count ?>)</option>
            <?php endforeach; ?>
        </select>
        
        <label for="min-frecuencia">Frecuencia mínima:</label>
        <input type="range" id="min-frecuencia" min="1" max="<?= $maxFrecuencia ?>" value="1">
        <span id="min-frecuencia-valor">1</span>
        
        <label for="max-conceptos">Mostrar:</label>
        <select id="max-conceptos">
            <option value="20">Top 20</option>
            <option value="50">Top 50</option>
            <option value="100">Top 100</option>
            <option value="todos">Todos</option>
        </select>
    </div>
    
    <!-- Nube de conceptos -->
    <div class="nube-container" id="nube-container">
        <?php
        $contador = 0;
        $maxMostrar = 50;
        foreach ($conceptosFrecuencia as $concepto => $frecuencia):
            if ($contador >= $maxMostrar) break;
            
            // Calcular tamaño de fuente (entre 12px y 48px)
            $tamanio = 12 + (($frecuencia - $minFrecuencia) / max(1, $maxFrecuencia - $minFrecuencia)) * 36;
            $tamanio = round($tamanio);
            
            // Color según frecuencia
            $intensidad = round(($frecuencia / $maxFrecuencia) * 255);
            $color = "rgb(255, " . (105 + $intensidad/2) . ", " . (180 - $intensidad/3) . ")";
            
            $contador++;
        ?>
            <div class="concepto" 
                 data-concepto="<?= htmlspecialchars($concepto) ?>"
                 data-frecuencia="<?= $frecuencia ?>"
                 style="font-size: <?= $tamanio ?>px; color: <?= $color ?>;"
                 onclick="mostrarDetalles('<?= htmlspecialchars($concepto, ENT_QUOTES) ?>')">
                <?= htmlspecialchars($concepto) ?>
                <small style="display: block; font-size: 0.6em; opacity: 0.7;"><?= $frecuencia ?></small>
            </div>
        <?php endforeach; ?>
    </div>
    
    <!-- Panel de detalles -->
    <div class="detalles-panel" id="detalles-panel">
        <h3 id="detalle-titulo"></h3>
        <div class="meta" id="detalle-meta"></div>
        <div class="bitacoras-lista" id="detalle-bitacoras"></div>
    </div>
    
    <!-- Términos del dominio -->
    <div class="dominio-section">
        <h3>🎯 Términos del Dominio Ontológico</h3>
        <p style="color: #a0a0a0; font-size: 0.85rem; margin-bottom: 1rem;">
            Conceptos clave identificados por el agente normalizador en el diccionario de dominio
        </p>
        <div class="dominio-tags">
            <?php foreach ($terminosDominioGlobal as $termino): ?>
                <div class="dominio-tag"><?= htmlspecialchars($termino) ?></div>
            <?php endforeach; ?>
        </div>
    </div>

    <script>
        // Datos de conceptos y bitácoras
        const conceptosBitacoras = <?= json_encode($conceptosBitacoras, JSON_UNESCAPED_UNICODE) ?>;
        
        // Control de frecuencia mínima
        const sliderFrecuencia = document.getElementById('min-frecuencia');
        const valorFrecuencia = document.getElementById('min-frecuencia-valor');
        
        sliderFrecuencia.addEventListener('input', function() {
            valorFrecuencia.textContent = this.value;
            filtrarConceptos();
        });
        
        // Control de máximo de conceptos
        document.getElementById('max-conceptos').addEventListener('change', filtrarConceptos);
        
        // Control de idioma
        document.getElementById('filtro-idioma').addEventListener('change', filtrarConceptos);
        
        function filtrarConceptos() {
            const minFrec = parseInt(sliderFrecuencia.value);
            const maxConceptos = document.getElementById('max-conceptos').value;
            const idiomaFiltro = document.getElementById('filtro-idioma').value;
            
            const conceptos = document.querySelectorAll('.concepto');
            let visibles = 0;
            const limite = maxConceptos === 'todos' ? 9999 : parseInt(maxConceptos);
            
            conceptos.forEach(concepto => {
                const frecuencia = parseInt(concepto.dataset.frecuencia);
                const nombreConcepto = concepto.dataset.concepto;
                
                // Filtrar por frecuencia
                if (frecuencia < minFrec) {
                    concepto.style.display = 'none';
                    return;
                }
                
                // Filtrar por idioma
                if (idiomaFiltro !== 'todos') {
                    const bitacoras = conceptosBitacoras[nombreConcepto] || [];
                    const tieneIdioma = bitacoras.some(b => b.idioma === idiomaFiltro);
                    if (!tieneIdioma) {
                        concepto.style.display = 'none';
                        return;
                    }
                }
                
                // Limitar cantidad
                if (visibles >= limite) {
                    concepto.style.display = 'none';
                    return;
                }
                
                concepto.style.display = 'block';
                visibles++;
            });
        }
        
        function mostrarDetalles(concepto) {
            const panel = document.getElementById('detalles-panel');
            const titulo = document.getElementById('detalle-titulo');
            const meta = document.getElementById('detalle-meta');
            const bitacorasLista = document.getElementById('detalle-bitacoras');
            
            // Quitar selección anterior
            document.querySelectorAll('.concepto').forEach(c => c.classList.remove('seleccionado'));
            
            // Marcar como seleccionado
            const elemento = document.querySelector(`[data-concepto="${concepto}"]`);
            if (elemento) elemento.classList.add('seleccionado');
            
            const bitacoras = conceptosBitacoras[concepto] || [];
            const frecuencia = bitacoras.length;
            
            titulo.textContent = `Concepto: "${concepto}"`;
            meta.innerHTML = `
                <strong>Frecuencia:</strong> ${frecuencia} menciones | 
                <strong>Bitácoras relacionadas:</strong> ${bitacoras.length}
            `;
            
            // Mostrar bitácoras
            bitacorasLista.innerHTML = bitacoras.map(b => `
                <div class="bitacora-item" onclick="irABitacora('${b.id}')">
                    <div class="titulo">${b.titulo}</div>
                    <div class="meta-item">
                        ID: ${b.id} | Idioma: ${b.idioma.toUpperCase()} 
                        ${b.tipo ? '| Tipo: ' + b.tipo : ''}
                    </div>
                </div>
            `).join('');
            
            panel.classList.add('activo');
            panel.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }
        
        function irABitacora(id) {
            // Navegar a la bitácora específica
            window.location.href = `bitacora.php?filtrar=${id}`;
        }
        
        // Inicializar
        filtrarConceptos();
    </script>
</body>
</html>