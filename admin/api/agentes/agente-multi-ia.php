<?php
/**
 * AGENTE MULTI-IA - THE DIFFERENCE (CÓDIGO COMPLETO)
 * Filosofía: "El Consejo de Sabios Digitales al servicio de la Trinidad"
 * Integra Qwen, ChatGPT y Gemini para generar Conceptos Modos.
 */
set_time_limit(120);
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit(0);
}

$baseDir = dirname(__DIR__, 3);
$settingsFile = $baseDir . '/config/settings.json';
$memoryDir = $baseDir . '/.memory/conversations';

if (!is_dir($memoryDir)) {
    mkdir($memoryDir, 0755, true);
}

$settings = file_exists($settingsFile) ? json_decode(file_get_contents($settingsFile), true) : [];
$iaConfig = $settings['ia'] ?? [];

// ==========================================
// FUNCIÓN: Llamar a API de IA con cURL robusto
// ==========================================
function llamarIA($url, $headers, $payload) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_TIMEOUT, 45);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $error = curl_error($ch);
    curl_close($ch);
    
    if ($httpCode === 200 && !$error) {
        $data = json_decode($response, true);
        // OpenAI / Qwen format
        if (isset($data['choices'][0]['message']['content'])) {
            return $data['choices'][0]['message']['content'];
        }
        // Gemini format
        if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
            return $data['candidates'][0]['content']['parts'][0]['text'];
        }
        return "Respuesta sin formato esperado: " . substr($response, 0, 100);
    }
    return "Error HTTP $httpCode: " . ($error ?: substr($response, 0, 100));
}

// ==========================================
// EJECUCIÓN PRINCIPAL
// ==========================================
$input = json_decode(file_get_contents('php://input'), true);
$texto = $input['texto'] ?? '';
$tipoBitacora = $input['tipo_bitacora'] ?? 'log'; // texvn, ars, espiritu, log
$bitacoraId = $input['bitacora_id'] ?? uniqid('LOG-');

if (empty($texto)) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'El campo "texto" es requerido']);
    exit;
}

// Prompt Ontológico Estricto para forzar JSON
$promptBase = "Eres un agente ontológico de la Fundación Ars Tekne. Analiza este texto desde la filosofía de 'The Difference'. 
El usuario busca desidentificarse de la 'mente mono' y encontrar el 'Concepto Modo' (síntesis pedagógica). 
Tipo de bitácora: $tipoBitacora. 
Texto del usuario: '$texto'. 
Responde EXCLUSIVAMENTE en formato JSON válido con esta estructura exacta: 
{\"conceptos_clave\": [\"concepto1\", \"concepto2\"], \"nivel_conciencia_sugerido\": 1, \"reflexion_ontologica\": \"tu reflexion aqui\"}";

$resultados = [
    'qwen' => 'No configurado o error',
    'gpt' => 'No configurado o error',
    'gemini' => 'No configurado o error'
];

// 1. QWEN (Alibaba DashScope)
if (!empty($iaConfig['qwen_key'])) {
    $payloadQwen = [
        "model" => $iaConfig['modelo_qwen'] ?? 'qwen-max',
        "input" => ["messages" => [["role" => "user", "content" => $promptBase]]]
    ];
    $resultados['qwen'] = llamarIA(
        'https://dashscope.aliyuncs.com/api/v1/services/aigc/text-generation/generation',
        ['Authorization: Bearer ' . $iaConfig['qwen_key'], 'Content-Type: application/json', 'X-DashScope-SSE: disable'],
        $payloadQwen
    );
}

// 2. CHATGPT (OpenAI)
if (!empty($iaConfig['openai_key'])) {
    $payloadGPT = [
        "model" => $iaConfig['modelo_gpt'] ?? 'gpt-3.5-turbo',
        "messages" => [["role" => "system", "content" => "Responde solo en JSON válido."], ["role" => "user", "content" => $promptBase]]
    ];
    $resultados['gpt'] = llamarIA(
        'https://api.openai.com/v1/chat/completions',
        ['Authorization: Bearer ' . $iaConfig['openai_key'], 'Content-Type: application/json'],
        $payloadGPT
    );
}

// 3. GEMINI (Google)
if (!empty($iaConfig['gemini_key'])) {
    $payloadGemini = [
        "contents" => [["parts" => [["text" => $promptBase . " Responde solo en JSON válido."]]]]
    ];
    $resultados['gemini'] = llamarIA(
        'https://generativelanguage.googleapis.com/v1beta/models/' . ($iaConfig['modelo_gemini'] ?? 'gemini-1.5-flash') . ':generateContent?key=' . $iaConfig['gemini_key'],
        ['Content-Type: application/json'],
        $payloadGemini
    );
}

// ==========================================
// SÍNTESIS Y EXTRACCIÓN DE CONCEPTOS MODOS
// ==========================================
$conceptosUnidos = [];
$reflexiones = [];

foreach ($resultados as $ia => $respuesta) {
    // Extraer JSON entre llaves
    if (preg_match('/\{.*\}/s', $respuesta, $matches)) {
        $jsonIA = json_decode($matches[0], true);
        if (is_array($jsonIA)) {
            if (isset($jsonIA['conceptos_clave']) && is_array($jsonIA['conceptos_clave'])) {
                $conceptosUnidos = array_merge($conceptosUnidos, $jsonIA['conceptos_clave']);
            }
            if (isset($jsonIA['reflexion_ontologica'])) {
                $reflexiones[$ia] = $jsonIA['reflexion_ontologica'];
            }
        }
    }
}

$conceptosModos = array_values(array_unique($conceptosUnidos));

// ==========================================
// GUARDAR EN MEMORIA (.memory/conversations/)
// ==========================================
$registroMemoria = [
    'id' => $bitacoraId,
    'fecha' => date('c'),
    'tipo_bitacora' => $tipoBitacora,
    'texto_original' => $texto,
    'analisis_ia' => $resultados,
    'reflexiones_sintetizadas' => $reflexiones,
    'analisis' => [
        'conceptos' => $conceptosModos,
        'resumen' => "Síntesis del Consejo de Sabios: Se detectaron " . count($conceptosModos) . " conceptos modo para la desidentificación."
    ]
];

$archivoMemoria = $memoryDir . '/' . $bitacoraId . '.json';
file_put_contents($archivoMemoria, json_encode($registroMemoria, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

echo json_encode([
    'success' => true,
    'bitacora_id' => $bitacoraId,
    'mensaje' => 'El Consejo de Sabios ha procesado la entrada y guardado la huella.',
    'conceptos_modos_generados' => $conceptosModos,
    'archivo_guardado' => $archivoMemoria
], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
?>