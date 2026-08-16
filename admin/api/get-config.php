<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

$configFile = '../../config/settings.json';

if (file_exists($configFile)) {
    echo file_get_contents($configFile);
} else {
    echo json_encode([
        'logo_size' => 90,
        'logo_margin' => 40,
        'btn_diff_size' => 70,
        'btn_diff_color' => '#ffffff',
        'btn_diff_hover' => '#ff69b4',
        'btn_margin' => 40,
        'btn_tematik_size' => 18,
        'btn_tematik_color' => '#fffc34',
        'bg_color' => '#000000'
    ]);
}
?>