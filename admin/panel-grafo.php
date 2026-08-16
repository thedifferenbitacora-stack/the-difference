<?php
session_start();

// Headers para CORS
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

$baseDir = dirname(__DIR__);
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grafo de Pensamiento - The Difference</title>
    
    <!-- Cytoscape.js para visualización de grafos -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/cytoscape/3.26.0/cytoscape.min.js"></script>
    
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            font-family: 'Courier New', monospace;
            background: #0a0a0a;
            color: #fff;
            min-height: 100vh;
            overflow: hidden;
        }
        
        .header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            background: rgba(10, 10, 10, 0.95);
            border-bottom: 1px solid #333;
            padding: 1rem 2rem;
            z-index: 1000;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        
        .header h1 {
            font-size: 1.5rem;
            color: #ff69b4;
            letter-spacing: 2px;
        }
        
        .header .stats {
            display: flex;
            gap: 2rem;
            font-size: 0.85rem;
            color: #a0a0a0;
        }
        
        .header .stats span {
            color: #fffc34;
        }
        
        #grafo-container {
            position: fixed;
            top: 80px;
            left: 0;
            right: 0;
            bottom: 0;
            background: #0a0a0a;
        }
        
        .info-panel {
            position: fixed;
            bottom: 20px;
            left: 20px;
            right: 20px;
            background: rgba(26, 26, 26, 0.95);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1.5rem;
            max-height: 200px;
            overflow-y: auto;
            display: none;
            z-index: 1000;
        }
        
        .info-panel.active {
            display: block;
        }
        
        .info-panel h3 {
            color: #ff69b4;
            margin-bottom: 0.5rem;
            font-size: 1.1rem;
        }
        
        .info-panel .meta {
            font-size: 0.8rem;
            color: #a0a0a0;
            margin-bottom: 1rem;
        }
        
        .info-panel .contenido {
            line-height: 1.6;
            color: #d0d0d0;
        }
        
        .leyenda {
            position: fixed;
            top: 100px;
            right: 20px;
            background: rgba(26, 26, 26, 0.95);
            border: 1px solid #333;
            border-radius: 8px;
            padding: 1rem;
            font-size: 0.8rem;
        }
        
        .leyenda h4 {
            color: #fffc34;
            margin-bottom: 0.5rem;
        }
        
        .leyenda-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.3rem;
        }
        
        .leyenda-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
        }
        
        .loading {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            color: #ff69b4;
        }
        
        .error-message {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 1.5rem;
            color: #ff69b4;
            text-align: center;
            background: rgba(26, 26, 26, 0.9);
            padding: 2rem;
            border-radius: 8px;
            border: 1px solid #ff69b4;
            max-width: 80%;
        }
        
        .error-message pre {
            font-size: 0.9rem;
            color: #a0a0a0;
            margin-top: 1rem;
            text-align: left;
            white-space: pre-wrap;
            word-wrap: break-word;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>👁️ GRAFO DE PENSAMIENTO</h1>
        <div class="stats">
            <div>Nodos: <span id="total-nodos">0</span></div>
            <div>Conexiones: <span id="total-aristas">0</span></div>
            <div>Bitácoras: <span id="total-bitacoras">0</span></div>
        </div>
    </div>
    
    <div id="grafo-container"></div>
    
    <div class="leyenda">
        <h4>Tipos de Pensamiento</h4>
        <div class="leyenda-item">
            <div class="leyenda-dot" style="background: #00bcd4;"></div>
            <span>Observación</span>
        </div>
        <div class="leyenda-item">
            <div class="leyenda-dot" style="background: #ff69b4;"></div>
            <span>Reflexión</span>
        </div>
        <div class="leyenda-item">
            <div class="leyenda-dot" style="background: #fffc34;"></div>
            <span>Intuición</span>
        </div>
        <div class="leyenda-item">
            <div class="leyenda-dot" style="background: #9c27b0;"></div>
            <span>Pensamiento Crítico</span>
        </div>
        <div class="leyenda-item">
            <div class="leyenda-dot" style="background: #4caf50;"></div>
            <span>Síntesis</span>
        </div>
    </div>
    
    <div class="info-panel" id="info-panel">
        <h3 id="info-titulo"></h3>
        <div class="meta" id="info-meta"></div>
        <div class="contenido" id="info-contenido"></div>
    </div>
    
    <div class="loading" id="loading">Cargando grafo...</div>
    <div class="error-message" id="error-message" style="display: none;">
        Error al cargar el grafo
        <pre id="error-details"></pre>
    </div>

    <script>
        // Colores por tipo de pensamiento
        const coloresPorTipo = {
            'observacion': '#00bcd4',
            'reflexion': '#ff69b4',
            'intuicion': '#fffc34',
            'pensamiento_critico': '#9c27b0',
            'sintesis': '#4caf50',
            'decision': '#ff5722',
            'general': '#666666'
        };
        
        // Función para mostrar error
        function mostrarError(mensaje, detalle = '') {
            document.getElementById('loading').style.display = 'none';
            const errorDiv = document.getElementById('error-message');
            document.getElementById('error-details').textContent = detalle;
            errorDiv.style.display = 'block';
            console.error(mensaje, detalle);
        }
        
        // Cargar datos del grafo
        async function cargarGrafo() {
            try {
                const respuesta = await fetch('./api/trazabilidad.php');
                
                if (!respuesta.ok) {
                    throw new Error(`HTTP ${respuesta.status}: ${respuesta.statusText}`);
                }
                
                const texto = await respuesta.text();
                
                try {
                    const datos = JSON.parse(texto);
                    
                    if (!datos.success) {
                        throw new Error(datos.error || 'Error en la API');
                    }
                    
                    // Actualizar estadísticas
                    document.getElementById('total-nodos').textContent = datos.datos.grafo.total_nodos;
                    document.getElementById('total-aristas').textContent = datos.datos.grafo.total_aristas;
                    document.getElementById('total-bitacoras').textContent = datos.datos.bitacora.total;
                    
                    // Preparar elementos para Cytoscape
                    const elementos = [];
                    
                    // Crear nodos
                    datos.datos.bitacora.entradas.forEach(entrada => {
                        const color = coloresPorTipo[entrada.tipo_pensamiento] || coloresPorTipo.general;
                        
                        elementos.push({
                            data: {
                                id: entrada.id,
                                label: entrada.titulo,
                                tipo: entrada.tipo_pensamiento,
                                categoria: entrada.categoria,
                                fecha: entrada.fecha,
                                contenido: entrada.contenido,
                                proceso: entrada.proceso,
                                sujeto: entrada.sujeto,
                                color: color  // Guardar el color en data
                            },
                            position: { 
                                x: Math.random() * window.innerWidth, 
                                y: Math.random() * (window.innerHeight - 150) 
                            }
                        });
                    });
                    
                    // Crear aristas (conexiones)
                    datos.datos.bitacora.entradas.forEach(entrada => {
                        if (entrada.relacionado_a && entrada.relacionado_a.length > 0) {
                            entrada.relacionado_a.forEach(relacionId => {
                                elementos.push({
                                    data: {
                                        source: entrada.id,
                                        target: relacionId
                                    }
                                });
                            });
                        }
                    });
                    
                    // Inicializar Cytoscape
                    const cy = cytoscape({
                        container: document.getElementById('grafo-container'),
                        elements: elementos,
                        style: [
                            {
                                selector: 'node',
                                style: {
                                    'background-color': 'data(color)',  // USAR data(color)
                                    'label': 'data(label)',
                                    'text-valign': 'bottom',
                                    'text-halign': 'center',
                                    'text-wrap': 'wrap',
                                    'text-max-width': 150,
                                    'width': 70,
                                    'height': 70,
                                    'font-size': '11px',
                                    'color': '#fff',
                                    'text-outline-color': '#000',
                                    'text-outline-width': 2,
                                    'text-margin-y': -45,
                                    'border-width': 2,
                                    'border-color': '#fff',
                                    'border-opacity': 0.3
                                }
                            },
                            {
                                selector: 'edge',
                                style: {
                                    'width': 2,
                                    'line-color': '#555',
                                    'target-arrow-color': '#555',
                                    'target-arrow-shape': 'triangle',
                                    'curve-style': 'bezier',
                                    'arrow-scale': 1.5
                                }
                            },
                            {
                                selector: 'node:selected',
                                style: {
                                    'border-width': 4,
                                    'border-color': '#fffc34',
                                    'border-opacity': 1
                                }
                            }
                        ],
                        layout: {
                            name: 'cose',
                            animate: true,
                            animationDuration: 1000,
                            fit: true,
                            padding: 30,
                            nodeDimensionsIncludeLabels: true,
                            gravity: -500,
                            nodeRepulsion: 5000
                        },
                        minZoom: 0.5,
                        maxZoom: 3,
                        wheelSensitivity: 0.3
                    });
                    
                    // Evento: clic en nodo
                    cy.on('tap', 'node', function(evt) {
                        const nodo = evt.target;
                        const datos = nodo.data();
                        
                        mostrarInfoPanel(datos);
                    });
                    
                    // Clic en el fondo para deseleccionar
                    cy.on('tap', function(evt) {
                        if (evt.target === cy) {
                            document.getElementById('info-panel').classList.remove('active');
                        }
                    });
                    
                    // Hover effect
                    cy.on('mouseover', 'node', function(evt) {
                        const nodo = evt.target;
                        nodo.style({
                            'width': 80,
                            'height': 80,
                            'border-opacity': 1
                        });
                    });
                    
                    cy.on('mouseout', 'node', function(evt) {
                        const nodo = evt.target;
                        nodo.style({
                            'width': 70,
                            'height': 70,
                            'border-opacity': 0.3
                        });
                    });
                    
                    // Ocultar loading
                    document.getElementById('loading').style.display = 'none';
                    
                } catch (parseError) {
                    mostrarError('Error al parsear JSON:', parseError.message + '\n\nRespuesta recibida:\n' + texto.substring(0, 500));
                }
                
            } catch (error) {
                mostrarError('Error al cargar grafo:', error.message);
            }
        }
        
        function mostrarInfoPanel(datos) {
            const panel = document.getElementById('info-panel');
            const titulo = document.getElementById('info-titulo');
            const meta = document.getElementById('info-meta');
            const contenido = document.getElementById('info-contenido');
            
            titulo.textContent = datos.label;
            meta.innerHTML = `
                <span style="color: ${datos.color}; font-weight: bold;">●</span> 
                <strong>Tipo:</strong> ${datos.tipo}<br>
                <strong>Categoría:</strong> ${datos.categoria}<br>
                <strong>Proceso:</strong> ${datos.proceso}<br>
                <strong>Fecha:</strong> ${new Date(datos.fecha).toLocaleString()}<br>
                <strong>Sujeto:</strong> ${datos.sujeto}
            `;
            contenido.textContent = datos.contenido;
            
            panel.classList.add('active');
        }
        
        // Cargar grafo al iniciar
        window.addEventListener('DOMContentLoaded', cargarGrafo);
    </script>
</body>
</html>