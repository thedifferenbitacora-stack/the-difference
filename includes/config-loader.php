<?php
/**
 * Config Loader - Funciones compartidas para todos los paneles
 * Carga y guarda la configuración desde/to config/settings.json
 */

function loadConfig() {
    $configFile = __DIR__ . '/../config/settings.json';
    
    $defaults = [
        // ============================================
        // CONFIGURACIÓN GLOBAL DE LA PÁGINA
        // ============================================
        'page_overflow' => 'hidden',
        'page_cursor' => 'default',
        'page_selection_color' => '#ff69b4',
        'page_selection_bg' => 'rgba(255,105,180,0.3)',
        'page_scrollbar_color' => '#ff69b4',
        'page_scrollbar_bg' => '#1a1a1a',
        
        // ============================================
        // FONDO GLOBAL
        // ============================================
        'bg_color' => '#000000',
        'bg_type' => 'solid',
        'bg_gradient_start' => '#000000',
        'bg_gradient_end' => '#1a1a2e',
        'bg_gradient_angle' => 135,
        'bg_image_path' => '',
        'bg_image_size' => 'cover',
        'bg_image_position' => 'center',
        'bg_image_repeat' => 'no-repeat',
        'bg_overlay_color' => '#000000',
        'bg_overlay_opacity' => 0,
        'bg_animation' => 'none',
        'bg_animation_speed' => 15,
        
        // ============================================
        // PORTADA - TÍTULO
        // ============================================
        'portada_title_text' => 'FEELING AUTISTIC',
        'portada_title_size' => 60,
        'portada_title_color' => '#ffffff',
        'portada_title_font' => 'Arial Black',
        'portada_title_font_weight' => 900,
        'portada_title_letter_spacing' => 5,
        'portada_title_text_transform' => 'uppercase',
        'portada_title_text_align' => 'center',
        'portada_title_text_shadow' => 'none',
        'portada_title_opacity' => 1,
        'portada_title_rotation' => 0,
        'portada_title_filter' => 'none',
        'portada_title_zindex' => 10,
        'portada_title_scale' => 1,
        'portada_title_max_width' => 100,
        'portada_title_bg' => 'transparent',
        'portada_title_bg_hover' => 'transparent',
        'portada_title_padding_v' => 0,
        'portada_title_padding_h' => 0,
        'portada_title_border_radius' => 0,
        'portada_title_border_width' => 0,
        'portada_title_border_color' => '#ffffff',
        'portada_title_cursor' => 'default',
        'portada_title_transition_duration' => 0.3,
        'portada_title_position_x' => 50,
        'portada_title_position_y' => 30,
        'portada_title_animation' => 'fadeInDown',
        'portada_title_anim_duration' => 1,
        'portada_title_anim_delay' => 0,
        
        // ============================================
        // PORTADA - SUBTÍTULO
        // ============================================
        'portada_subtitle_text' => 'INTUITIVE ANALITYC NEURODIVERGENCE CREATIVE PLATFORM',
        'portada_subtitle_size' => 14,
        'portada_subtitle_color' => '#a0a0a0',
        'portada_subtitle_font' => 'Arial Black',
        'portada_subtitle_font_weight' => 400,
        'portada_subtitle_letter_spacing' => 2,
        'portada_subtitle_text_transform' => 'uppercase',
        'portada_subtitle_text_align' => 'center',
        'portada_subtitle_text_shadow' => 'none',
        'portada_subtitle_opacity' => 1,
        'portada_subtitle_rotation' => 0,
        'portada_subtitle_filter' => 'none',
        'portada_subtitle_zindex' => 10,
        'portada_subtitle_scale' => 1,
        'portada_subtitle_max_width' => 100,
        'portada_subtitle_bg' => 'transparent',
        'portada_subtitle_bg_hover' => 'transparent',
        'portada_subtitle_padding_v' => 0,
        'portada_subtitle_padding_h' => 0,
        'portada_subtitle_border_radius' => 0,
        'portada_subtitle_border_width' => 0,
        'portada_subtitle_border_color' => '#ffffff',
        'portada_subtitle_cursor' => 'default',
        'portada_subtitle_transition_duration' => 0.3,
        'portada_subtitle_position_x' => 50,
        'portada_subtitle_position_y' => 45,
        'portada_subtitle_animation' => 'fadeIn',
        'portada_subtitle_anim_duration' => 1,
        'portada_subtitle_anim_delay' => 0.3,
        
        // ============================================
        // PORTADA - LOGO/VIDEO CIRCULAR
        // ============================================
        'portada_logo_type' => 'image',
        'portada_logo_path' => 'images/logo-feeling-autistic.png',
        'portada_video_path' => 'videos/logo-portada.mp4',
        'portada_logo_size' => 50,
        'portada_logo_border_radius' => 50,
        'portada_logo_border_width' => 3,
        'portada_logo_border_color' => '#ffffff',
        'portada_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
        'portada_logo_opacity' => 1,
        'portada_logo_rotation' => 0,
        'portada_logo_filter' => 'none',
        'portada_logo_filter_hover' => 'none',
        'portada_logo_zindex' => 10,
        'portada_logo_scale' => 1,
        'portada_logo_scale_hover' => 1.05,
        'portada_logo_object_fit' => 'cover',
        'portada_logo_cursor' => 'pointer',
        'portada_logo_transition_duration' => 0.3,
        'portada_logo_position_x' => 50,
        'portada_logo_position_y' => 15,
        'portada_logo_animation' => 'zoomIn',
        'portada_logo_anim_duration' => 1.5,
        'portada_logo_anim_delay' => 0,
        'portada_video_autoplay' => true,
        'portada_video_loop' => true,
        'portada_video_muted' => true,
        'portada_video_playsinline' => true,
        
        // ============================================
        // PORTADA - BOTÓN PRINCIPAL
        // ============================================
        'portada_btn_main_text' => 'THE DIFFERENCE',
        'portada_btn_main_size' => 50,
        'portada_btn_main_color' => '#ffffff',
        'portada_btn_main_hover' => '#ff69b4',
        'portada_btn_main_font' => 'Arial Black',
        'portada_btn_main_font_weight' => 900,
        'portada_btn_main_letter_spacing' => 5,
        'portada_btn_main_text_transform' => 'uppercase',
        'portada_btn_main_text_shadow' => 'none',
        'portada_btn_main_text_shadow_hover' => 'none',
        'portada_btn_main_opacity' => 1,
        'portada_btn_main_rotation' => 0,
        'portada_btn_main_filter' => 'none',
        'portada_btn_main_zindex' => 10,
        'portada_btn_main_scale' => 1,
        'portada_btn_main_max_width' => 90,
        'portada_btn_main_border_width' => 3,
        'portada_btn_main_border_color' => '#ffffff',
        'portada_btn_main_border_radius' => 0,
        'portada_btn_main_bg' => 'transparent',
        'portada_btn_main_bg_hover' => 'transparent',
        'portada_btn_main_padding_v' => 20,
        'portada_btn_main_padding_h' => 40,
        'portada_btn_main_shadow' => 'none',
        'portada_btn_main_shadow_hover' => '0 0 30px rgba(255,105,180,0.5)',
        'portada_btn_main_transform_hover' => 'scale(1.05)',
        'portada_btn_main_cursor' => 'pointer',
        'portada_btn_main_transition_duration' => 0.3,
        'portada_btn_main_position_x' => 50,
        'portada_btn_main_position_y' => 65,
        'portada_btn_main_animation' => 'fadeInUp',
        'portada_btn_main_anim_duration' => 1,
        'portada_btn_main_anim_delay' => 0.5,
        'portada_btn_main_link' => 'menu.php',
        
        // ============================================
        // PORTADA - BOTÓN SECUNDARIO (LE TEMATIK)
        // ============================================
        'portada_btn_secondary_text' => 'LE TEMATIK DESIGN',
        'portada_btn_secondary_size' => 16,
        'portada_btn_secondary_color' => '#fffc34',
        'portada_btn_secondary_hover' => '#ffffff',
        'portada_btn_secondary_font' => 'Arial Black',
        'portada_btn_secondary_font_weight' => 900,
        'portada_btn_secondary_letter_spacing' => 2,
        'portada_btn_secondary_text_transform' => 'uppercase',
        'portada_btn_secondary_text_shadow' => 'none',
        'portada_btn_secondary_opacity' => 1,
        'portada_btn_secondary_rotation' => 0,
        'portada_btn_secondary_filter' => 'none',
        'portada_btn_secondary_zindex' => 10,
        'portada_btn_secondary_scale' => 1,
        'portada_btn_secondary_max_width' => 90,
        'portada_btn_secondary_border_width' => 2,
        'portada_btn_secondary_border_color' => '#ffffff',
        'portada_btn_secondary_border_radius' => 0,
        'portada_btn_secondary_bg' => 'transparent',
        'portada_btn_secondary_bg_hover' => 'rgba(255,252,52,0.1)',
        'portada_btn_secondary_padding_v' => 8,
        'portada_btn_secondary_padding_h' => 20,
        'portada_btn_secondary_shadow' => 'none',
        'portada_btn_secondary_shadow_hover' => '0 0 20px rgba(255,252,52,0.5)',
        'portada_btn_secondary_transform_hover' => 'scale(1.05)',
        'portada_btn_secondary_cursor' => 'pointer',
        'portada_btn_secondary_transition_duration' => 0.3,
        'portada_btn_secondary_position_x' => 85,
        'portada_btn_secondary_position_y' => 90,
        'portada_btn_secondary_animation' => 'fadeInRight',
        'portada_btn_secondary_anim_duration' => 1,
        'portada_btn_secondary_anim_delay' => 1,
        'portada_btn_secondary_link' => '#',
        
        // ============================================
        // MENÚ - TODOS LOS PARÁMETROS (resumidos)
        // ============================================
        'menu_title_text' => 'FEELING AUTISTIC',
        'menu_title_size' => 60,
        'menu_title_color' => '#ffffff',
        'menu_title_font' => 'Arial Black',
        'menu_title_font_weight' => 900,
        'menu_title_letter_spacing' => 5,
        'menu_title_text_transform' => 'uppercase',
        'menu_title_text_shadow' => 'none',
        'menu_title_opacity' => 1,
        'menu_title_rotation' => 0,
        'menu_title_filter' => 'none',
        'menu_title_zindex' => 10,
        'menu_title_position_x' => 50,
        'menu_title_position_y' => 15,
        'menu_title_animation' => 'fadeInDown',
        'menu_title_anim_duration' => 1,
        'menu_title_anim_delay' => 0,
        'menu_subtitle_text' => 'NEURODIVERGENCE CREATIVE PHILOSOPHY PLATFORM',
        'menu_subtitle_size' => 14,
        'menu_subtitle_color' => '#a0a0a0',
        'menu_subtitle_font' => 'Arial Black',
        'menu_subtitle_font_weight' => 400,
        'menu_subtitle_letter_spacing' => 2,
        'menu_subtitle_text_transform' => 'uppercase',
        'menu_subtitle_text_shadow' => 'none',
        'menu_subtitle_opacity' => 1,
        'menu_subtitle_rotation' => 0,
        'menu_subtitle_filter' => 'none',
        'menu_subtitle_zindex' => 10,
        'menu_subtitle_position_x' => 50,
        'menu_subtitle_position_y' => 28,
        'menu_subtitle_animation' => 'fadeIn',
        'menu_subtitle_anim_duration' => 1,
        'menu_subtitle_anim_delay' => 0.3,
        'menu_logo_type' => 'image',
        'menu_logo_path' => 'images/logo-feeling-autistic.png',
        'menu_video_path' => 'videos/logo-menu.mp4',
        'menu_logo_size' => 40,
        'menu_logo_border_radius' => 50,
        'menu_logo_border_width' => 3,
        'menu_logo_border_color' => '#ffffff',
        'menu_logo_shadow' => '0 0 30px rgba(255,105,180,0.5)',
        'menu_logo_opacity' => 1,
        'menu_logo_rotation' => 0,
        'menu_logo_filter' => 'none',
        'menu_logo_filter_hover' => 'none',
        'menu_logo_zindex' => 10,
        'menu_logo_scale' => 1,
        'menu_logo_scale_hover' => 1.05,
        'menu_logo_object_fit' => 'cover',
        'menu_logo_position_x' => 50,
        'menu_logo_position_y' => 45,
        'menu_logo_animation' => 'zoomIn',
        'menu_logo_anim_duration' => 1.5,
        'menu_logo_anim_delay' => 0,
        'menu_btn_main_text' => 'THE DIFFERENCE',
        'menu_btn_main_size' => 45,
        'menu_btn_main_color' => '#ffffff',
        'menu_btn_main_hover' => '#ff69b4',
        'menu_btn_main_font' => 'Arial Black',
        'menu_btn_main_font_weight' => 900,
        'menu_btn_main_letter_spacing' => 4,
        'menu_btn_main_text_transform' => 'uppercase',
        'menu_btn_main_text_shadow' => 'none',
        'menu_btn_main_opacity' => 1,
        'menu_btn_main_rotation' => 0,
        'menu_btn_main_filter' => 'none',
        'menu_btn_main_zindex' => 10,
        'menu_btn_main_border_width' => 3,
        'menu_btn_main_border_color' => '#ffffff',
        'menu_btn_main_border_radius' => 0,
        'menu_btn_main_bg' => 'transparent',
        'menu_btn_main_bg_hover' => 'transparent',
        'menu_btn_main_padding_v' => 18,
        'menu_btn_main_padding_h' => 35,
        'menu_btn_main_shadow' => 'none',
        'menu_btn_main_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)',
        'menu_btn_main_transform_hover' => 'scale(1.05)',
        'menu_btn_main_cursor' => 'pointer',
        'menu_btn_main_transition_duration' => 0.3,
        'menu_btn_main_position_x' => 50,
        'menu_btn_main_position_y' => 60,
        'menu_btn_main_animation' => 'fadeInUp',
        'menu_btn_main_anim_duration' => 1,
        'menu_btn_main_anim_delay' => 0.3,
        'menu_btn_main_link' => '#',
        'menu_btn_sec_size' => 14,
        'menu_btn_sec_color' => '#fffc34',
        'menu_btn_sec_hover_color' => '#ffffff',
        'menu_btn_sec_font' => 'Arial Black',
        'menu_btn_sec_font_weight' => 900,
        'menu_btn_sec_letter_spacing' => 1,
        'menu_btn_sec_text_transform' => 'uppercase',
        'menu_btn_sec_text_shadow' => 'none',
        'menu_btn_sec_opacity' => 1,
        'menu_btn_sec_zindex' => 10,
        'menu_btn_sec_border_width' => 2,
        'menu_btn_sec_border_color' => '#ffffff',
        'menu_btn_sec_border_radius' => 0,
        'menu_btn_sec_bg' => 'transparent',
        'menu_btn_sec_bg_hover' => 'rgba(255,252,52,0.15)',
        'menu_btn_sec_padding_v' => 8,
        'menu_btn_sec_padding_h' => 16,
        'menu_btn_sec_shadow' => 'none',
        'menu_btn_sec_shadow_hover' => '0 0 15px rgba(255,252,52,0.5)',
        'menu_btn_sec_transform_hover' => 'scale(1.05)',
        'menu_btn_sec_cursor' => 'pointer',
        'menu_btn_sec_transition_duration' => 0.3,
        'menu_btn_sec_gap' => 10,
        'menu_btn_sec_position_x' => 50,
        'menu_btn_sec_position_y' => 75,
        'menu_btn_sec_animation' => 'fadeIn',
        'menu_btn_sec_anim_duration' => 0.8,
        'menu_btn_sec_anim_delay' => 0.5,
        'menu_btn_sec_items' => 'LOG,LE TEMATIK,PROJECT NADA BRAHMA,TEXVN,QUANTUMLAB,PENSAMIENTO AUTISTA,SAAIYIN DO,ARS TEKNE,QUIRÓN THEATRE',
        'menu_btn_bottom_text' => 'LE TEMATIK DESIGN',
        'menu_btn_bottom_size' => 14,
        'menu_btn_bottom_color' => '#ff69b4',
        'menu_btn_bottom_hover' => '#ffffff',
        'menu_btn_bottom_font' => 'Arial Black',
        'menu_btn_bottom_font_weight' => 900,
        'menu_btn_bottom_letter_spacing' => 2,
        'menu_btn_bottom_text_transform' => 'uppercase',
        'menu_btn_bottom_text_shadow' => 'none',
        'menu_btn_bottom_opacity' => 1,
        'menu_btn_bottom_rotation' => 0,
        'menu_btn_bottom_filter' => 'none',
        'menu_btn_bottom_zindex' => 10,
        'menu_btn_bottom_border_width' => 2,
        'menu_btn_bottom_border_color' => '#ff69b4',
        'menu_btn_bottom_border_radius' => 0,
        'menu_btn_bottom_bg' => 'transparent',
        'menu_btn_bottom_bg_hover' => 'rgba(255,105,180,0.1)',
        'menu_btn_bottom_padding_v' => 8,
        'menu_btn_bottom_padding_h' => 20,
        'menu_btn_bottom_shadow' => 'none',
        'menu_btn_bottom_shadow_hover' => '0 0 20px rgba(255,105,180,0.5)',
        'menu_btn_bottom_transform_hover' => 'scale(1.05)',
        'menu_btn_bottom_cursor' => 'pointer',
        'menu_btn_bottom_transition_duration' => 0.3,
        'menu_btn_bottom_position_x' => 50,
        'menu_btn_bottom_position_y' => 95,
        'menu_btn_bottom_animation' => 'fadeInUp',
        'menu_btn_bottom_anim_duration' => 1,
        'menu_btn_bottom_anim_delay' => 1,
        'menu_btn_bottom_link' => '#',
        
        // ============================================
        // GENERADOR AUTOMÁTICO PARA LAS 9 PÁGINAS NODO
        // (LOG, LE TEMATIK, PROJECT NADA BRAHMA, TEXVN, QUANTUMLAB, PENSAMIENTO AUTISTA, SAIAYIN DO, ARS TEKNE, QUIRÓN THEATRE)
        // ============================================
    ];
    
    // Array con los nombres de las 9 páginas nodo
    $node_pages = [
        'log' => 'LOG',
        'le_tematik' => 'LE TEMATIK',
        'project_nada_brahma' => 'PROJECT NADA BRAHMA',
        'texvn' => 'TEXVN',
        'quantumlab' => 'QUANTUMLAB',
        'pensamiento_autista' => 'PENSAMIENTO AUTISTA',
        'saiayin_do' => 'SAIAYIN DO',
        'ars_tekne' => 'ARS TEKNE',
        'quiron_theatre' => 'QUIRÓN THEATRE'
    ];
    
    // Generar automáticamente todos los parámetros para cada página nodo
    foreach ($node_pages as $prefix => $name) {
        // TÍTULO
        $defaults[$prefix . '_title_text'] = $name;
        $defaults[$prefix . '_title_size'] = 60;
        $defaults[$prefix . '_title_color'] = '#ffffff';
        $defaults[$prefix . '_title_font'] = 'Arial Black';
        $defaults[$prefix . '_title_font_weight'] = 900;
        $defaults[$prefix . '_title_letter_spacing'] = 5;
        $defaults[$prefix . '_title_text_transform'] = 'uppercase';
        $defaults[$prefix . '_title_text_align'] = 'center';
        $defaults[$prefix . '_title_text_shadow'] = 'none';
        $defaults[$prefix . '_title_opacity'] = 1;
        $defaults[$prefix . '_title_rotation'] = 0;
        $defaults[$prefix . '_title_filter'] = 'none';
        $defaults[$prefix . '_title_zindex'] = 10;
        $defaults[$prefix . '_title_scale'] = 1;
        $defaults[$prefix . '_title_max_width'] = 100;
        $defaults[$prefix . '_title_bg'] = 'transparent';
        $defaults[$prefix . '_title_bg_hover'] = 'transparent';
        $defaults[$prefix . '_title_padding_v'] = 0;
        $defaults[$prefix . '_title_padding_h'] = 0;
        $defaults[$prefix . '_title_border_radius'] = 0;
        $defaults[$prefix . '_title_border_width'] = 0;
        $defaults[$prefix . '_title_border_color'] = '#ffffff';
        $defaults[$prefix . '_title_cursor'] = 'default';
        $defaults[$prefix . '_title_transition_duration'] = 0.3;
        $defaults[$prefix . '_title_position_x'] = 50;
        $defaults[$prefix . '_title_position_y'] = 15;
        $defaults[$prefix . '_title_animation'] = 'fadeInDown';
        $defaults[$prefix . '_title_anim_duration'] = 1;
        $defaults[$prefix . '_title_anim_delay'] = 0;
        
        // SUBTÍTULO
        $defaults[$prefix . '_subtitle_text'] = 'SUBTÍTULO DE ' . $name;
        $defaults[$prefix . '_subtitle_size'] = 14;
        $defaults[$prefix . '_subtitle_color'] = '#a0a0a0';
        $defaults[$prefix . '_subtitle_font'] = 'Arial Black';
        $defaults[$prefix . '_subtitle_font_weight'] = 400;
        $defaults[$prefix . '_subtitle_letter_spacing'] = 2;
        $defaults[$prefix . '_subtitle_text_transform'] = 'uppercase';
        $defaults[$prefix . '_subtitle_text_align'] = 'center';
        $defaults[$prefix . '_subtitle_text_shadow'] = 'none';
        $defaults[$prefix . '_subtitle_opacity'] = 1;
        $defaults[$prefix . '_subtitle_rotation'] = 0;
        $defaults[$prefix . '_subtitle_filter'] = 'none';
        $defaults[$prefix . '_subtitle_zindex'] = 10;
        $defaults[$prefix . '_subtitle_scale'] = 1;
        $defaults[$prefix . '_subtitle_max_width'] = 100;
        $defaults[$prefix . '_subtitle_bg'] = 'transparent';
        $defaults[$prefix . '_subtitle_bg_hover'] = 'transparent';
        $defaults[$prefix . '_subtitle_padding_v'] = 0;
        $defaults[$prefix . '_subtitle_padding_h'] = 0;
        $defaults[$prefix . '_subtitle_border_radius'] = 0;
        $defaults[$prefix . '_subtitle_border_width'] = 0;
        $defaults[$prefix . '_subtitle_border_color'] = '#ffffff';
        $defaults[$prefix . '_subtitle_cursor'] = 'default';
        $defaults[$prefix . '_subtitle_transition_duration'] = 0.3;
        $defaults[$prefix . '_subtitle_position_x'] = 50;
        $defaults[$prefix . '_subtitle_position_y'] = 28;
        $defaults[$prefix . '_subtitle_animation'] = 'fadeIn';
        $defaults[$prefix . '_subtitle_anim_duration'] = 1;
        $defaults[$prefix . '_subtitle_anim_delay'] = 0.3;
        
        // LOGO/VIDEO
        $defaults[$prefix . '_logo_type'] = 'image';
        $defaults[$prefix . '_logo_path'] = 'images/logo-feeling-autistic.png';
        $defaults[$prefix . '_video_path'] = 'videos/video-' . $prefix . '.mp4';
        $defaults[$prefix . '_logo_size'] = 40;
        $defaults[$prefix . '_logo_border_radius'] = 50;
        $defaults[$prefix . '_logo_border_width'] = 3;
        $defaults[$prefix . '_logo_border_color'] = '#ffffff';
        $defaults[$prefix . '_logo_shadow'] = '0 0 30px rgba(255,105,180,0.5)';
        $defaults[$prefix . '_logo_opacity'] = 1;
        $defaults[$prefix . '_logo_rotation'] = 0;
        $defaults[$prefix . '_logo_filter'] = 'none';
        $defaults[$prefix . '_logo_filter_hover'] = 'none';
        $defaults[$prefix . '_logo_zindex'] = 10;
        $defaults[$prefix . '_logo_scale'] = 1;
        $defaults[$prefix . '_logo_scale_hover'] = 1.05;
        $defaults[$prefix . '_logo_object_fit'] = 'cover';
        $defaults[$prefix . '_logo_cursor'] = 'pointer';
        $defaults[$prefix . '_logo_transition_duration'] = 0.3;
        $defaults[$prefix . '_logo_position_x'] = 50;
        $defaults[$prefix . '_logo_position_y'] = 45;
        $defaults[$prefix . '_logo_animation'] = 'zoomIn';
        $defaults[$prefix . '_logo_anim_duration'] = 1.5;
        $defaults[$prefix . '_logo_anim_delay'] = 0;
        $defaults[$prefix . '_video_autoplay'] = true;
        $defaults[$prefix . '_video_loop'] = true;
        $defaults[$prefix . '_video_muted'] = true;
        $defaults[$prefix . '_video_playsinline'] = true;
        
        // BOTÓN PRINCIPAL
        $defaults[$prefix . '_btn_main_text'] = $name;
        $defaults[$prefix . '_btn_main_size'] = 45;
        $defaults[$prefix . '_btn_main_color'] = '#ffffff';
        $defaults[$prefix . '_btn_main_hover'] = '#ff69b4';
        $defaults[$prefix . '_btn_main_font'] = 'Arial Black';
        $defaults[$prefix . '_btn_main_font_weight'] = 900;
        $defaults[$prefix . '_btn_main_letter_spacing'] = 4;
        $defaults[$prefix . '_btn_main_text_transform'] = 'uppercase';
        $defaults[$prefix . '_btn_main_text_shadow'] = 'none';
        $defaults[$prefix . '_btn_main_text_shadow_hover'] = 'none';
        $defaults[$prefix . '_btn_main_opacity'] = 1;
        $defaults[$prefix . '_btn_main_rotation'] = 0;
        $defaults[$prefix . '_btn_main_filter'] = 'none';
        $defaults[$prefix . '_btn_main_zindex'] = 10;
        $defaults[$prefix . '_btn_main_scale'] = 1;
        $defaults[$prefix . '_btn_main_max_width'] = 90;
        $defaults[$prefix . '_btn_main_border_width'] = 3;
        $defaults[$prefix . '_btn_main_border_color'] = '#ffffff';
        $defaults[$prefix . '_btn_main_border_radius'] = 0;
        $defaults[$prefix . '_btn_main_bg'] = 'transparent';
        $defaults[$prefix . '_btn_main_bg_hover'] = 'transparent';
        $defaults[$prefix . '_btn_main_padding_v'] = 18;
        $defaults[$prefix . '_btn_main_padding_h'] = 35;
        $defaults[$prefix . '_btn_main_shadow'] = 'none';
        $defaults[$prefix . '_btn_main_shadow_hover'] = '0 0 20px rgba(255,105,180,0.5)';
        $defaults[$prefix . '_btn_main_transform_hover'] = 'scale(1.05)';
        $defaults[$prefix . '_btn_main_cursor'] = 'pointer';
        $defaults[$prefix . '_btn_main_transition_duration'] = 0.3;
        $defaults[$prefix . '_btn_main_position_x'] = 50;
        $defaults[$prefix . '_btn_main_position_y'] = 65;
        $defaults[$prefix . '_btn_main_animation'] = 'fadeInUp';
        $defaults[$prefix . '_btn_main_anim_duration'] = 1;
        $defaults[$prefix . '_btn_main_anim_delay'] = 0.5;
        $defaults[$prefix . '_btn_main_link'] = '#';
        
        // BOTONES SECUNDARIOS
        $defaults[$prefix . '_btn_sec_size'] = 14;
        $defaults[$prefix . '_btn_sec_color'] = '#fffc34';
        $defaults[$prefix . '_btn_sec_hover_color'] = '#ffffff';
        $defaults[$prefix . '_btn_sec_font'] = 'Arial Black';
        $defaults[$prefix . '_btn_sec_font_weight'] = 900;
        $defaults[$prefix . '_btn_sec_letter_spacing'] = 1;
        $defaults[$prefix . '_btn_sec_text_transform'] = 'uppercase';
        $defaults[$prefix . '_btn_sec_text_shadow'] = 'none';
        $defaults[$prefix . '_btn_sec_opacity'] = 1;
        $defaults[$prefix . '_btn_sec_zindex'] = 10;
        $defaults[$prefix . '_btn_sec_border_width'] = 2;
        $defaults[$prefix . '_btn_sec_border_color'] = '#ffffff';
        $defaults[$prefix . '_btn_sec_border_radius'] = 0;
        $defaults[$prefix . '_btn_sec_bg'] = 'transparent';
        $defaults[$prefix . '_btn_sec_bg_hover'] = 'rgba(255,252,52,0.15)';
        $defaults[$prefix . '_btn_sec_padding_v'] = 8;
        $defaults[$prefix . '_btn_sec_padding_h'] = 16;
        $defaults[$prefix . '_btn_sec_shadow'] = 'none';
        $defaults[$prefix . '_btn_sec_shadow_hover'] = '0 0 15px rgba(255,252,52,0.5)';
        $defaults[$prefix . '_btn_sec_transform_hover'] = 'scale(1.05)';
        $defaults[$prefix . '_btn_sec_cursor'] = 'pointer';
        $defaults[$prefix . '_btn_sec_transition_duration'] = 0.3;
        $defaults[$prefix . '_btn_sec_gap'] = 10;
        $defaults[$prefix . '_btn_sec_position_x'] = 50;
        $defaults[$prefix . '_btn_sec_position_y'] = 75;
        $defaults[$prefix . '_btn_sec_animation'] = 'fadeIn';
        $defaults[$prefix . '_btn_sec_anim_duration'] = 0.8;
        $defaults[$prefix . '_btn_sec_anim_delay'] = 0.5;
        $defaults[$prefix . '_btn_sec_items'] = 'LOG,LE TEMATIK,PROJECT NADA BRAHMA,TEXVN,QUANTUMLAB,PENSAMIENTO AUTISTA,SAAIYIN DO,ARS TEKNE,QUIRÓN THEATRE';
    }
    
    $defaults['google_fonts'] = 'Arial Black,Roboto,Playfair Display,Orbitron,Space Mono';
    
    if (file_exists($configFile)) {
        $saved = json_decode(file_get_contents($configFile), true);
        return is_array($saved) ? array_merge($defaults, $saved) : $defaults;
    }
    return $defaults;
}

function saveConfig($config) {
    $configFile = __DIR__ . '/../config/settings.json';
    return file_put_contents($configFile, json_encode($config, JSON_PRETTY_PRINT));
}

function getGoogleFontsList() {
    return ['Arial Black','Roboto','Playfair Display','Orbitron','Space Mono','Montserrat','Oswald','Raleway','Poppins','Bebas Neue','Lobster','Pacifico','Dancing Script','Permanent Marker','Righteous','Audiowide','Bungee','Russo One','Exo 2','Share Tech','Press Start 2P','VT323','Fira Code'];
}
function getAnimationsList() {
    return ['none'=>'Sin animación','fadeIn'=>'Fade In','fadeInUp'=>'Fade In Up','fadeInDown'=>'Fade In Down','fadeInLeft'=>'Fade In Left','fadeInRight'=>'Fade In Right','zoomIn'=>'Zoom In','bounceIn'=>'Bounce In','slideInUp'=>'Slide In Up','slideInLeft'=>'Slide In Left','slideInRight'=>'Slide In Right'];
}
function getShadowsList() {
    return ['none'=>'Sin sombra','0 4px 6px rgba(0,0,0,0.3)'=>'Sombra suave','0 10px 20px rgba(0,0,0,0.5)'=>'Sombra media','0 20px 40px rgba(0,0,0,0.7)'=>'Sombra fuerte','0 0 20px rgba(255,105,180,0.5)'=>'Glow rosa','0 0 30px rgba(255,252,52,0.5)'=>'Glow amarillo','0 0 30px rgba(102,126,234,0.5)'=>'Glow azul'];
}
function getTextShadowsList() {
    return ['none'=>'Sin sombra','0 2px 4px rgba(0,0,0,0.5)'=>'Sombra suave','0 4px 8px rgba(0,0,0,0.7)'=>'Sombra media','0 0 10px rgba(255,105,180,0.8)'=>'Glow rosa','0 0 10px rgba(255,252,52,0.8)'=>'Glow amarillo','2px 2px 0 #000'=>'Sólida'];
}
function getTransformsList() {
    return ['scale(1.05)'=>'Escalar 1.05x','scale(1.1)'=>'Escalar 1.1x','scale(1.2)'=>'Escalar 1.2x','translateY(-5px)'=>'Subir 5px','translateY(-10px)'=>'Subir 10px','rotate(5deg)'=>'Rotar 5°','none'=>'Sin transformación'];
}
function getFiltersList() {
    return ['none'=>'Sin filtro','grayscale(100%)'=>'Escala de grises','sepia(100%)'=>'Sepia','blur(2px)'=>'Desenfoque','brightness(1.2)'=>'Brillo alto','brightness(0.8)'=>'Brillo bajo','contrast(1.2)'=>'Contraste alto','hue-rotate(90deg)'=>'Rotar color','invert(100%)'=>'Invertir colores','saturate(2)'=>'Saturación alta'];
}
function getCursorsList() {
    return ['default'=>'Default','pointer'=>'Pointer','crosshair'=>'Crosshair','move'=>'Move','not-allowed'=>'Not Allowed','help'=>'Help'];
}
function getObjectFitsList() {
    return ['cover'=>'Cover','contain'=>'Contain','fill'=>'Fill','none'=>'None'];
}
function getBgSizesList() { return ['cover'=>'Cover','contain'=>'Contain','auto'=>'Auto','100% 100%'=>'Stretch']; }
function getBgPositionsList() { return ['center'=>'Center','top'=>'Top','bottom'=>'Bottom','left'=>'Left','right'=>'Right']; }
function getBgRepeatsList() { return ['no-repeat'=>'No Repeat','repeat'=>'Repeat','repeat-x'=>'Repeat X','repeat-y'=>'Repeat Y']; }
function getBgAnimationsList() { return ['none'=>'Sin animación','gradient'=>'Gradiente animado','pulse'=>'Pulse','kenburns'=>'Ken Burns']; }
function getOverflowOptionsList() { return ['hidden'=>'Hidden (sin scroll)','auto'=>'Auto','scroll'=>'Scroll siempre visible']; }
?>