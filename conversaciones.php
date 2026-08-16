<?php
require_once 'config.php';

// Cargar conversaciones
$conversationsFile = 'data/conversations.json';
$conversations = [];

if (file_exists($conversationsFile)) {
  $conversations = json_decode(file_get_contents($conversationsFile), true) ?? [];
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Conversaciones con The Difference</title>
    <style>
        /* Mismos estilos que la versión Astro */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        
        body {
            background: #000000;
            min-height: 100vh;
            font-family: 'Helvetica', 'Arial', sans-serif;
            color: #ffffff;
            overflow: hidden;
        }
        
        .chat-container {
            display: flex;
            height: 100vh;
        }
        
        .sidebar {
            width: 300px;
            background: #0a0a0a;
            border-right: 2px solid #ffffff;
            padding: 20px;
            overflow-y: auto;
        }
        
        .sidebar-header {
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 2px solid #ff0000;
        }
        
        .sidebar-header h2 {
            font-size: 18px;
            color: #ffde00;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        
        .new-conversation-btn {
            width: 100%;
            padding: 12px;
            background: transparent;
            border: 2px solid #ffffff;
            color: #ffffff;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
            margin-bottom: 20px;
        }
        
        .new-conversation-btn:hover {
            background: #ffffff;
            color: #000000;
        }
        
        .conversation-list {
            list-style: none;
        }
        
        .conversation-item {
            padding: 12px;
            margin-bottom: 8px;
            background: #111111;
            border: 1px solid #333;
            cursor: pointer;
            transition: all 0.3s ease;
            font-size: 14px;
        }
        
        .conversation-item:hover,
        .conversation-item.active {
            border-color: #ffde00;
            background: #1a1a1a;
        }
        
        .chat-main {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        .chat-header {
            padding: 20px 30px;
            background: #0a0a0a;
            border-bottom: 2px solid #ffffff;
        }
        
        .chat-header h1 {
            font-size: 24px;
            color: #ffffff;
            text-transform: uppercase;
            letter-spacing: 4px;
        }
        
        .chat-header p {
            font-size: 12px;
            color: #888;
            margin-top: 5px;
            letter-spacing: 2px;
        }
        
        .chat-messages {
            flex: 1;
            padding: 30px;
            overflow-y: auto;
            display: flex;
            flex-direction: column;
            gap: 20px;
        }
        
        .message {
            max-width: 70%;
            padding: 15px 20px;
            border-radius: 8px;
            line-height: 1.6;
        }
        
        .message.user {
            align-self: flex-end;
            background: #1a1a1a;
            border: 2px solid #ffde00;
        }
        
        .message.assistant {
            align-self: flex-start;
            background: #0a0a0a;
            border: 2px solid #ff69b4;
        }
        
        .message-header {
            font-size: 11px;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin-bottom: 8px;
        }
        
        .message.user .message-header { color: #ffde00; }
        .message.assistant .message-header { color: #ff69b4; }
        
        .chat-input-area {
            padding: 20px 30px;
            background: #0a0a0a;
            border-top: 2px solid #ffffff;
        }
        
        .input-container {
            display: flex;
            gap: 10px;
            max-width: 1200px;
            margin: 0 auto;
        }
        
        .chat-input {
            flex: 1;
            padding: 15px 20px;
            background: #111111;
            border: 2px solid #ffffff;
            color: #ffffff;
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            border-radius: 4px;
            outline: none;
        }
        
        .chat-input:focus {
            border-color: #ffde00;
            box-shadow: 0 0 20px rgba(255, 222, 0, 0.3);
        }
        
        .send-btn {
            padding: 15px 30px;
            background: transparent;
            border: 2px solid #ff69b4;
            color: #ff69b4;
            font-weight: 900;
            text-transform: uppercase;
            letter-spacing: 2px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .send-btn:hover {
            background: #ff69b4;
            color: #000000;
        }
        
        .quick-suggestions {
            display: flex;
            gap: 10px;
            margin-top: 15px;
            flex-wrap: wrap;
            justify-content: center;
        }
        
        .suggestion-btn {
            padding: 8px 15px;
            background: transparent;
            border: 1px solid #333;
            color: #888;
            font-size: 12px;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        
        .suggestion-btn:hover {
            border-color: #ffde00;
            color: #ffde00;
        }
    </style>
</head>
<body>
    <div class="chat-container">
        <aside class="sidebar">
            <div class="sidebar-header">
                <h2>Conversaciones</h2>
            </div>
            
            <button class="new-conversation-btn" onclick="newConversation()">
                + Nueva Conversación
            </button>
            
            <ul class="conversation-list" id="conversationList">
                <?php foreach ($conversations as $index => $conv): ?>
                    <li class="conversation-item <?= $index === 0 ? 'active' : '' ?>" 
                        onclick="loadConversation(<?= $index ?>)">
                        <?= htmlspecialchars($conv['title'] ?? 'Conversación ' . ($index + 1)) ?>
                    </li>
                <?php endforeach; ?>
            </ul>
        </aside>
        
        <main class="chat-main">
            <header class="chat-header">
                <h1>Conversaciones con The Difference</h1>
                <p>Asistente de orientación para el proyecto</p>
            </header>
            
            <div class="chat-messages" id="chatMessages">
                <div class="message assistant">
                    <div class="message-header">The Difference</div>
                    <div class="message-content">
                        ¡Hola! Soy tu asistente de orientación para el proyecto <strong>THE DIFFERENCE</strong>. 
                        Puedo ayudarte con decisiones de diseño, plantillas, estrategias técnicas y documentación.
                        ¿En qué puedo orientarte hoy?
                    </div>
                </div>
            </div>
            
            <div class="chat-input-area">
                <div class="input-container">
                    <input 
                        type="text" 
                        class="chat-input" 
                        id="chatInput" 
                        placeholder="Escribe tu pregunta sobre el proyecto..."
                        onkeypress="handleKeyPress(event)"
                    />
                    <button class="send-btn" onclick="sendMessage()" id="sendBtn">
                        Enviar
                    </button>
                </div>
                
                <div class="quick-suggestions">
                    <button class="suggestion-btn" onclick="useSuggestion('¿Qué plantilla usar para el nodo LOG?')">
                        Plantilla para LOG
                    </button>
                    <button class="suggestion-btn" onclick="useSuggestion('¿Cómo conectar PHP y Astro?')">
                        Conexión PHP-Astro
                    </button>
                    <button class="suggestion-btn" onclick="useSuggestion('¿Qué paleta de colores usar?')">
                        Paleta de colores
                    </button>
                </div>
            </div>
        </main>
    </div>
    
    <script>
        let currentConversation = 0;
        let conversations = <?= json_encode($conversations) ?>;
        
        function handleKeyPress(event) {
            if (event.key === 'Enter') sendMessage();
        }
        
        function useSuggestion(text) {
            document.getElementById('chatInput').value = text;
            document.getElementById('chatInput').focus();
        }
        
        async function sendMessage() {
            const input = document.getElementById('chatInput');
            const message = input.value.trim();
            if (!message) return;
            
            const sendBtn = document.getElementById('sendBtn');
            sendBtn.disabled = true;
            input.value = '';
            
            addMessage('user', message);
            
            setTimeout(() => {
                const response = generateResponse(message);
                addMessage('assistant', response);
                sendBtn.disabled = false;
            }, 1000);
        }
        
        function addMessage(role, content) {
            const messagesContainer = document.getElementById('chatMessages');
            const messageDiv = document.createElement('div');
            messageDiv.className = `message ${role}`;
            
            const header = role === 'user' ? 'Tú' : 'The Difference';
            messageDiv.innerHTML = `
                <div class="message-header">${header}</div>
                <div class="message-content">${content}</div>
            `;
            
            messagesContainer.appendChild(messageDiv);
            messagesContainer.scrollTop = messagesContainer.scrollHeight;
        }
        
        function generateResponse(message) {
            const lowerMessage = message.toLowerCase();
            
            if (lowerMessage.includes('plantilla')) {
                return 'Para el nodo <strong>LOG</strong>, te recomiendo una plantilla tipo bitácora con entradas cronológicas. Para <strong>LE TEMATIK</strong>, una plantilla de artículo filosófico con secciones expandibles.';
            }
            
            if (lowerMessage.includes('conectar') || lowerMessage.includes('php')) {
                return 'La conexión entre <strong>PHP</strong> y <strong>Astro</strong> se hace mediante APIs. PHP maneja la base de datos MySQL y expone endpoints JSON. Astro consume esos endpoints para mostrar datos dinámicos.';
            }
            
            if (lowerMessage.includes('color') || lowerMessage.includes('paleta')) {
                return 'La paleta actual es:<br>• <strong>Negro</strong> (#000000) - Fondo<br>• <strong>Blanco</strong> (#ffffff) - Texto principal<br>• <strong>Amarillo</strong> (#ffde00) - Acentos<br>• <strong>Rosa</strong> (#ff69b4) - Efectos hover<br>• <strong>Rojo</strong> (#ff0000) - Líneas de énfasis';
            }
            
            return 'Entiendo tu pregunta. Para darte una orientación más precisa, ¿podrías darme más detalles sobre qué aspecto específico necesitas desarrollar?';
        }
        
        function newConversation() {
            currentConversation = conversations.length;
            conversations.push({ title: 'Nueva conversación', messages: [] });
            document.getElementById('chatMessages').innerHTML = '';
            updateConversationList();
        }
        
        function loadConversation(index) {
            currentConversation = index;
            const messagesContainer = document.getElementById('chatMessages');
            messagesContainer.innerHTML = '';
            
            if (conversations[index] && conversations[index].messages) {
                conversations[index].messages.forEach(msg => {
                    addMessage(msg.role, msg.content);
                });
            }
            
            updateConversationList();
        }
        
        function updateConversationList() {
            const list = document.getElementById('conversationList');
            list.innerHTML = conversations.map((conv, index) => `
                <li class="conversation-item ${index === currentConversation ? 'active' : ''}" 
                    onclick="loadConversation(${index})">
                    ${conv.title || 'Conversación ' + (index + 1)}
                </li>
            `).join('');
        }
    </script>
</body>
</html>