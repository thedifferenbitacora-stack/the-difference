<?php
// config-core.php - Cerebro de la configuración
$baseDir = dirname(__DIR__);
$configFile = $baseDir . '/config/settings.json';
$configDir = $baseDir . '/config';
$imagesDir = $baseDir . '/images';
$videosDir = $baseDir . '/videos';

if (!is_dir($configDir)) mkdir($configDir, 0777, true);
if (!is_dir($imagesDir)) mkdir($imagesDir, 0777, true);
if (!is_dir($videosDir)) mkdir($videosDir, 0777, true);

// Función para cargar configuración
function loadConfig() {
    global $configFile;
    if (file_exists($configFile)) {
        return json_decode(file_get_contents($configFile), true) ?: [];
    }
    return [];
}

// Función para guardar configuración
function saveConfig($config) {
    global $configFile;
    return file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
}

// Función para obtener valor con fallback
function getVal($c, $k, $d) {
    return isset($c[$k]) ? $c[$k] : $d;
}

// Función para procesar subida de archivos
function processFileUpload($file, $type, $page) {
    global $imagesDir, $videosDir;
    
    if (!isset($file) || $file['error'] !== UPLOAD_ERR_OK) {
        return false;
    }
    
    $dir = ($type === 'image') ? $imagesDir : $videosDir;
    $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
    $fileName = ($type === 'image') ? "logo-{$page}.{$ext}" : "video-{$page}.mp4";
    $targetPath = $dir . '/' . $fileName;
    
    if (move_uploaded_file($file['tmp_name'], $targetPath)) {
        return ($type === 'image') ? "images/{$fileName}" : "videos/{$fileName}";
    }
    
    return false;
}

// Valores por defecto para cualquier página
function getDefaultPageConfig($page, $name) {
    $p = $page . '_';
    return [
        // Título
        $p . 'title_text' => $name,
        $p . 'title_size' => 60,
        $p . 'title_color' => '#ffffff',
        $p . 'title_font' => 'Arial Black',
        $p . 'title_position_x' => 50,
        $p . 'title_position_y' => 15,
        $p . 'title_animation' => 'fadeInDown',
        
        // Subtítulo
        $p . 'subtitle_text' => 'SUBTÍTULO DE ' . $name,
        $p . 'subtitle_size' => 14,
        $p . 'subtitle_color' => '#a0a0a0',
        $p . 'subtitle_position_x' => 50,
        $p . 'subtitle_position_y' => 28,
        
        // Logo/Video
        $p . 'logo_type' => 'image',
        $p . 'logo_path' => 'images/logo-feeling-autistic.png',
        $p . 'video_path' => 'videos/video-' . $page . '.mp4',
        $p . 'logo_size' => 40,
        $p . 'logo_border_radius' => 50,
        $p . 'logo_position_x' => 50,
        $p . 'logo_position_y' => 45,
        
        // Botón Principal
        $p . 'btn_main_text' => $name,
        $p . 'btn_main_link' => '#',
        $p . 'btn_main_size' => 45,
        $p . 'btn_main_color' => '#ffffff',
        $p . 'btn_main_hover' => '#ff69b4',
        $p . 'btn_main_position_x' => 50,
        $p . 'btn_main_position_y' => 65,
        
        // Botones Secundarios (personalizables)
        $p . 'btn_sec_1_text' => 'BOTÓN 1',
        $p . 'btn_sec_1_link' => '#',
        $p . 'btn_sec_2_text' => 'BOTÓN 2',
        $p . 'btn_sec_2_link' => '#',
        $p . 'btn_sec_3_text' => 'BOTÓN 3',
        $p . 'btn_sec_3_link' => '#',
        $p . 'btn_sec_size' => 14,
        $p . 'btn_sec_color' => '#fffc34',
        $p . 'btn_sec_hover_color' => '#ffffff',
        $p . 'btn_sec_position_x' => 50,
        $p . 'btn_sec_position_y' => 75,
        
        // Botón Inferior
        $p . 'btn_bottom_text' => 'LE TEMATIK DESIGN',
        $p . 'btn_bottom_link' => '#',
        $p . 'btn_bottom_size' => 14,
        $p . 'btn_bottom_color' => '#ff69b4',
        $p . 'btn_bottom_position_x' => 50,
        $p . 'btn_bottom_position_y' => 95,
    ];
}
?>