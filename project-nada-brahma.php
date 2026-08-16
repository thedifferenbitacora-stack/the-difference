<?php
$configFile = __DIR__ . '/config/settings.json';
$config = [];
if (file_exists($configFile)) {
    $config = json_decode(file_get_contents($configFile), true);
}

function getVal($c, $k, $d) {
    return isset($c[$k]) ? $c[$k] : $d;
}

// ⚠️ ESTE ES EL PREFIJO CLAVE PARA ESTA PÁGINA
$prefix = 'project_nada_brahma_';

// TÍTULO
$titleText = getVal($config, $prefix . 'title_text', 'PROJECT NADA BRAHMA');
$titleSize = getVal($config, $prefix . 'title_size', 60);
$titleColor = getVal($config, $prefix . 'title_color', '#ffffff');
$titleFont = getVal($config, $prefix . 'title_font', 'Arial Black');
$titleWeight = getVal($config, $prefix . 'title_font_weight', 900);
$titleSpacing = getVal($config, $prefix . 'title_letter_spacing', 5);
$titleTransform = getVal($config, $prefix . 'title_text_transform', 'uppercase');
$titleX = getVal($config, $prefix . 'title_position_x', 50);
$titleY = getVal($config, $prefix . 'title_position_y', 15);
$titleAnim = getVal($config, $prefix . 'title_animation', 'fadeInDown');
$titleDuration = getVal($config, $prefix . 'title_anim_duration', 1);
$titleDelay = getVal($config, $prefix . 'title_anim_delay', 0);

// SUBTÍTULO
$subtitleText = getVal($config, $prefix . 'subtitle_text', 'PROYECTO CREATIVO');
$subtitleSize = getVal($config, $prefix . 'subtitle_size', 14);
$subtitleColor = getVal($config, $prefix . 'subtitle_color', '#a0a0a0');
$subtitleFont = getVal($config, $prefix . 'subtitle_font', 'Arial Black');
$subtitleWeight = getVal($config, $prefix . 'subtitle_font_weight', 400);
$subtitleSpacing = getVal($config, $prefix . 'subtitle_letter_spacing', 2);
$subtitleTransform = getVal($config, $prefix . 'subtitle_text_transform', 'uppercase');
$subtitleX = getVal($config, $prefix . 'subtitle_position_x', 50);
$subtitleY = getVal($config, $prefix . 'subtitle_position_y', 28);
$subtitleAnim = getVal($config, $prefix . 'subtitle_animation', 'fadeIn');
$subtitleDuration = getVal($config, $prefix . 'subtitle_anim_duration', 1);
$subtitleDelay = getVal($config, $prefix . 'subtitle_anim_delay', 0.3);

// LOGO/VIDEO
$logoType = getVal($config, $prefix . 'logo_type', 'image');
$logoPath = getVal($config, $prefix . 'logo_path', 'images/logo-feeling-autistic.png');
$videoPath = getVal($config, $prefix . 'video_path', 'videos/video-project-nada-brahma.mp4');
$logoSize = getVal($config, $prefix . 'logo_size', 40);
$logoRadius = getVal($config, $prefix . 'logo_border_radius', 50);
$logoWidth = getVal($config, $prefix . 'logo_border_width', 3);
$logoColor = getVal($config, $prefix . 'logo_border_color', '#ffffff');
$logoShadow = getVal($config, $prefix . 'logo_shadow', '0 0 30px rgba(255,105,180,0.5)');
$logoX = getVal($config, $prefix . 'logo_position_x', 50);
$logoY = getVal($config, $prefix . 'logo_position_y', 45);
$logoAnim = getVal($config, $prefix . 'logo_animation', 'zoomIn');
$logoDuration = getVal($config, $prefix . 'logo_anim_duration', 1.5);
$logoDelay = getVal($config, $prefix . 'logo_anim_delay', 0);
$videoAutoplay = getVal($config, $prefix . 'video_autoplay', true);
$videoLoop = getVal($config, $prefix . 'video_loop', true);
$videoMuted = getVal($config, $prefix . 'video_muted', true);
$videoPlaysinline = getVal($config, $prefix . 'video_playsinline', true);

// BOTÓN PRINCIPAL
$btnText = getVal($config, $prefix . 'btn_main_text', 'PROJECT NADA BRAHMA');
$btnLink = getVal($config, $prefix . 'btn_main_link', '#');
$btnSize = getVal($config, $prefix . 'btn_main_size', 45);
$btnColor = getVal($config, $prefix . 'btn_main_color', '#ffffff');
$btnHover = getVal($config, $prefix . 'btn_main_hover', '#ff69b4');
$btnFont = getVal($config, $prefix . 'btn_main_font', 'Arial Black');
$btnWeight = getVal($config, $prefix . 'btn_main_font_weight', 900);
$btnSpacing = getVal($config, $prefix . 'btn_main_letter_spacing', 4);
$btnTransform = getVal($config, $prefix . 'btn_main_text_transform', 'uppercase');
$btnBorderWidth = getVal($config, $prefix . 'btn_main_border_width', 3);
$btnBorderColor = getVal($config, $prefix . 'btn_main_border_color', '#ffffff');
$btnRadius = getVal($config, $prefix . 'btn_main_border_radius', 0);
$btnPaddingV = getVal($config, $prefix . 'btn_main_padding_v', 18);
$btnPaddingH = getVal($config, $prefix . 'btn_main_padding_h', 35);
$btnShadowHover = getVal($config, $prefix . 'btn_main_shadow_hover', '0 0 20px rgba(255,105,180,0.5)');
$btnTransformHover = getVal($config, $prefix . 'btn_main_transform_hover', 'scale(1.05)');
$btnAnim = getVal($config, $prefix . 'btn_main_animation', 'fadeInUp');
$btnDuration = getVal($config, $prefix . 'btn_main_anim_duration', 1);
$btnDelay = getVal($config, $prefix . 'btn_main_anim_delay', 0.5);
$btnX = getVal($config, $prefix . 'btn_main_position_x', 50);
$btnY = getVal($config, $prefix . 'btn_main_position_y', 65);

// BOTONES SECUNDARIOS (MATRIZ DINÁMICA)
$btnSecItems = getVal($config, $prefix . 'btn_sec_items', 'LOG,LE TEMATIK,PROJECT NADA BRAHMA,TEXVN,QUANTUMLAB,PENSAMIENTO AUTISTA,SAIAYIN DO,ARS TEKNE,QUIRÓN THEATRE');
$btnSecSize = getVal($config, $prefix . 'btn_sec_size', 14);
$btnSecColor = getVal($config, $prefix . 'btn_sec_color', '#fffc34');
$btnSecHoverColor = getVal($config, $prefix . 'btn_sec_hover_color', '#ffffff');
$btnSecFont = getVal($config, $prefix . 'btn_sec_font', 'Arial Black');
$btnSecFontWeight = getVal($config, $prefix . 'btn_sec_font_weight', 900);
$btnSecLetterSpacing = getVal($config, $prefix . 'btn_sec_letter_spacing', 1);
$btnSecTextTransform = getVal($config, $prefix . 'btn_sec_text_transform', 'uppercase');
$btnSecBorderWidth = getVal($config, $prefix . 'btn_sec_border_width', 2);
$btnSecBorderColor = getVal($config, $prefix . 'btn_sec_border_color', '#ffffff');
$btnSecBorderRadius = getVal($config, $prefix . 'btn_sec_border_radius', 0);
$btnSecPaddingV = getVal($config, $prefix . 'btn_sec_padding_v', 8);
$btnSecPaddingH = getVal($config, $prefix . 'btn_sec_padding_h', 16);
$btnSecShadowHover = getVal($config, $prefix . 'btn_sec_shadow_hover', '0 0 15px rgba(255,252,52,0.5)');
$btnSecTransformHover = getVal($config, $prefix . 'btn_sec_transform_hover', 'scale(1.05)');
$btnSecAnim = getVal($config, $prefix . 'btn_sec_animation', 'fadeIn');
$btnSecAnimDuration = getVal($config, $prefix . 'btn_sec_anim_duration', 0.8);
$btnSecAnimDelay = getVal($config, $prefix . 'btn_sec_anim_delay', 0.5);
$btnSecGap = getVal($config, $prefix . 'btn_sec_gap', 10);
$btnSecX = getVal($config, $prefix . 'btn_sec_position_x', 50);
$btnSecY = getVal($config, $prefix . 'btn_sec_position_y', 75);

// BOTÓN INFERIOR
$btnBottomText = getVal($config, $prefix . 'btn_bottom_text', 'LE TEMATIK DESIGN');
$btnBottomLink = getVal($config, $prefix . 'btn_bottom_link', 'le-tematik.php');
$btnBottomSize = getVal($config, $prefix . 'btn_bottom_size', 14);
$btnBottomColor = getVal($config, $prefix . 'btn_bottom_color', '#ff69b4');
$btnBottomHover = getVal($config, $prefix . 'btn_bottom_hover', '#ffffff');
$btnBottomFont = getVal($config, $prefix . 'btn_bottom_font', 'Arial Black');
$btnBottomFontWeight = getVal($config, $prefix . 'btn_bottom_font_weight', 900);
$btnBottomLetterSpacing = getVal($config, $prefix . 'btn_bottom_letter_spacing', 2);
$btnBottomTextTransform = getVal($config, $prefix . 'btn_bottom_text_transform', 'uppercase');
$btnBottomBorderWidth = getVal($config, $prefix . 'btn_bottom_border_width', 2);
$btnBottomBorderColor = getVal($config, $prefix . 'btn_bottom_border_color', '#ff69b4');
$btnBottomBorderRadius = getVal($config, $prefix . 'btn_bottom_border_radius', 0);
$btnBottomPaddingV = getVal($config, $prefix . 'btn_bottom_padding_v', 8);
$btnBottomPaddingH = getVal($config, $prefix . 'btn_bottom_padding_h', 20);
$btnBottomShadowHover = getVal($config, $prefix . 'btn_bottom_shadow_hover', '0 0 20px rgba(255,105,180,0.5)');
$btnBottomTransformHover = getVal($config, $prefix . 'btn_bottom_transform_hover', 'scale(1.05)');
$btnBottomAnim = getVal($config, $prefix . 'btn_bottom_animation', 'fadeInUp');
$btnBottomAnimDuration = getVal($config, $prefix . 'btn_bottom_anim_duration', 1);
$btnBottomAnimDelay = getVal($config, $prefix . 'btn_bottom_anim_delay', 1);
$btnBottomX = getVal($config, $prefix . 'btn_bottom_position_x', 50);
$btnBottomY = getVal($config, $prefix . 'btn_bottom_position_y', 95);

// GLOBAL
$bgColor = getVal($config, 'bg_color', '#000000');
$googleFonts = getVal($config, 'google_fonts', 'Arial Black,Roboto');
$fonts = explode(',', $googleFonts);
$googleFontsUrl = 'https://fonts.googleapis.com/css2?family=' . implode('&family=', array_map(function($f) {
    return str_replace(' ', '+', trim($f)) . ':wght@100;200;300;400;500;600;700;800;900';
}, $fonts)) . '&display=swap';

$itemsArray = array_map('trim', explode(',', $btnSecItems));
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars($titleText) ?> - THE DIFFERENCE</title>
<link href="<?= $googleFontsUrl ?>" rel="stylesheet">
<style>
@keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
@keyframes fadeInUp { from { opacity: 0; transform: translate(-50%, -50%) translateY(30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes fadeInDown { from { opacity: 0; transform: translate(-50%, -50%) translateY(-30px); } to { opacity: 1; transform: translate(-50%, -50%) translateY(0); } }
@keyframes zoomIn { from { opacity: 0; transform: translate(-50%, -50%) scale(0.5); } to {