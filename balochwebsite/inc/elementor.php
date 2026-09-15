<?php
/**
 * Baloch Heritage — Elementor Integration
 *
 * - Adds CBCSC widget category
 * - Loads 11 native widgets
 * - Adds theme support flag so Elementor recognizes the theme
 * - Syncs theme tokens to Elementor's Site Settings
 * - Registers an "Elementor Canvas" template
 *
 * @package BalochHeritage
 */

defined('ABSPATH') || exit;

/* ═══════════════════════════════════════════════════════
   THEME SUPPORT
   ═══════════════════════════════════════════════════════ */

add_action('after_setup_theme', function () {
    // Declare full Elementor theme support
    add_theme_support('elementor');
    add_theme_support('elementor-pro');
});

/* ═══════════════════════════════════════════════════════
   ADD CBCSC PAGE TEMPLATES
   ═══════════════════════════════════════════════════════ */

add_filter('theme_page_templates', function ($templates) {
    $opts = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    if (!empty($opts['el_canvas_template'])) {
        $templates['elementor_canvas.php']    = __('CBCSC Canvas (Elementor — no header/footer)', 'baloch-heritage');
        $templates['elementor_fullwidth.php'] = __('CBCSC Full-width (Elementor — keep header/footer)', 'baloch-heritage');
    }
    return $templates;
});

add_filter('template_include', function ($template) {
    if (!is_page()) return $template;
    $slug = get_page_template_slug();
    if ($slug === 'elementor_canvas.php') {
        $path = BH_DIR . '/templates/elementor-canvas.php';
        if (file_exists($path)) return $path;
    }
    if ($slug === 'elementor_fullwidth.php') {
        $path = BH_DIR . '/templates/elementor-fullwidth.php';
        if (file_exists($path)) return $path;
    }
    return $template;
});


/* ═══════════════════════════════════════════════════════
   WIDGET CATEGORY + REGISTRATION
   ═══════════════════════════════════════════════════════ */

add_action('elementor/elements/categories_registered', function ($manager) {
    $manager->add_category('cbcsc', [
        'title' => __('CBCSC — Baloch Heritage', 'baloch-heritage'),
        'icon'  => 'eicon-palette',
    ]);
});

add_action('elementor/widgets/register', function ($widgets_manager) {
    $opts    = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    $enabled = (array) ($opts['el_widgets_enabled'] ?? []);

    $widget_map = [
        'events'     => 'Class_CBCSC_Events',
        'leaders'    => 'Class_CBCSC_Leaders',
        'carpet'     => 'Class_CBCSC_Carpet',
        'rsvp'       => 'Class_CBCSC_Rsvp',
        'newsletter' => 'Class_CBCSC_Newsletter',
        'donate'     => 'Class_CBCSC_Donate',
        'gallery'    => 'Class_CBCSC_Gallery',
        'quote'      => 'Class_CBCSC_Quote',
        'login'      => 'Class_CBCSC_Login',
        'lang'       => 'Class_CBCSC_Lang',
        'filter'     => 'Class_CBCSC_Filter',
    ];

    foreach ($widget_map as $key => $class) {
        if (!in_array($key, $enabled, true)) continue;
        $file = BH_DIR . '/inc/elementor-widgets/class-cbcsc-' . $key . '.php';
        if (!file_exists($file)) continue;
        require_once $file;
        if (class_exists($class)) {
            $widgets_manager->register(new $class());
        }
    }
});


/* ═══════════════════════════════════════════════════════
   SYNC THEME TOKENS → ELEMENTOR SITE SETTINGS
   ═══════════════════════════════════════════════════════ */

add_filter('elementor/frontend/print_google_fonts', '__return_true');

add_action('elementor/css-file/global/parse', 'bh_sync_globals_to_elementor', 99);
function bh_sync_globals_to_elementor() {
    $opts = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    if (empty($opts['el_sync_colors']) && empty($opts['el_sync_fonts'])) return;

    if (!class_exists('Elementor\Plugin')) return;

    $kit_id = \Elementor\Plugin::instance()->kits_manager->get_active_id();
    if (!$kit_id) return;

    $kit_settings = get_post_meta($kit_id, '_elementor_page_settings', true);
    if (!is_array($kit_settings)) $kit_settings = [];

    if (!empty($opts['el_sync_colors'])) {
        // Map our tokens to the four standard Elementor system colors
        $kit_settings['system_colors'] = [
            ['_id' => 'primary',    'title' => 'Primary',   'color' => $opts['color_maroon']],
            ['_id' => 'secondary',  'title' => 'Secondary', 'color' => $opts['color_orange']],
            ['_id' => 'text',       'title' => 'Text',      'color' => $opts['color_brown']],
            ['_id' => 'accent',     'title' => 'Accent',    'color' => $opts['color_cream']],
        ];
        $kit_settings['custom_colors'] = [
            ['_id' => 'maroon_dark', 'title' => 'Maroon Dark', 'color' => $opts['color_maroon_dark']],
            ['_id' => 'terra',       'title' => 'Terra',       'color' => $opts['color_terra']],
            ['_id' => 'sand',        'title' => 'Sand',        'color' => $opts['color_sand']],
        ];
    }

    if (!empty($opts['el_sync_fonts'])) {
        $kit_settings['system_typography'] = [
            ['_id' => 'primary',   'title' => 'Primary',   'typography_typography' => 'custom', 'typography_font_family' => $opts['font_display'], 'typography_font_weight' => '700'],
            ['_id' => 'secondary', 'title' => 'Secondary', 'typography_typography' => 'custom', 'typography_font_family' => $opts['font_display'], 'typography_font_weight' => '600'],
            ['_id' => 'text',      'title' => 'Text',      'typography_typography' => 'custom', 'typography_font_family' => $opts['font_body'],    'typography_font_weight' => '400'],
            ['_id' => 'accent',    'title' => 'Accent',    'typography_typography' => 'custom', 'typography_font_family' => $opts['font_body'],    'typography_font_weight' => '700'],
        ];
    }

    update_post_meta($kit_id, '_elementor_page_settings', $kit_settings);
}


/* ═══════════════════════════════════════════════════════
   PROPAGATE CBCSC FONT STYLESHEET INSIDE ELEMENTOR EDITOR
   ═══════════════════════════════════════════════════════ */

add_action('elementor/editor/after_enqueue_styles', function () {
    wp_enqueue_style('bh-shared', BH_URI . '/assets/css/baloch-shared.css', [], BH_VERSION);
});

add_action('elementor/preview/enqueue_styles', function () {
    wp_enqueue_style('bh-shared', BH_URI . '/assets/css/baloch-shared.css', [], BH_VERSION);
});
