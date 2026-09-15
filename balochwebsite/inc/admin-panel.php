<?php
/**
 * Baloch Heritage — Admin Settings Panel
 *
 * Adds a top-level "Baloch Heritage" menu with a tabbed settings page
 * that lets the admin modify every aspect of the theme.
 *
 * Settings are stored as a single option: bh_theme_options
 *
 * @package BalochHeritage
 */

defined('ABSPATH') || exit;

/* ═══════════════════════════════════════════════════════
   OPTIONS API
   ═══════════════════════════════════════════════════════ */

/**
 * Default theme options.
 */
function bh_default_options() {
    return [
        // Identity
        'site_title'        => get_bloginfo('name'),
        'tagline'           => 'Celebrating heritage, building community across Canada.',
        'hero_title'        => 'Celebrating Baloch Heritage & Community',
        'hero_subtitle'     => 'Explore our rich culture, history, and vibrant traditions across Canada and beyond.',
        'bilingual_mode'    => 'en_plus_balochi',

        // Contact
        'phone'             => '(416) 555-0001',
        'email'             => 'info@balochheritage.org',
        'address'           => '123 Industry Street, Toronto, ON M5H 3X9',
        'office_hours'      => 'Mon–Fri · 10:00 – 18:00 EST',
        'map_coords'        => '43.6532, -79.3832',

        // Social
        'facebook'          => '',
        'instagram'         => '',
        'youtube'           => '',
        'twitter'           => '',
        'tiktok'            => '',
        'whatsapp'          => '',

        // Appearance — colors
        'color_maroon'      => '#7A1C1C',
        'color_maroon_dark' => '#561010',
        'color_orange'      => '#E07B39',
        'color_terra'       => '#C4622D',
        'color_sand'        => '#D4A574',
        'color_cream'       => '#F7EDD8',
        'color_brown'       => '#2A1508',

        // Typography
        'font_display'      => 'Playfair Display',
        'font_body'         => 'Lato',
        'font_urdu'         => 'Noto Nastaliq Urdu',
        'base_size'         => 16,
        'heading_scale'     => 1.25,
        'line_height'       => 1.65,

        // Layout
        'container_width'   => 1240,
        'section_spacing'   => 'comfortable',
        'border_radius'     => 8,
        'shadow_depth'      => 'soft',
        'header_style'      => 'classic',

        // Carpet motif
        'motif_pattern'     => 'diamond',
        'motif_density'     => 36,
        'motif_stroke'      => 0.8,
        'motif_enabled'     => 1,

        // Homepage sections (order + visibility) — IDs match front-page.php sections.
        'home_sections'     => [
            ['id' => 'hero',      'enabled' => 1, 'label' => 'Hero — Heritage Banner'],
            ['id' => 'stats',     'enabled' => 1, 'label' => 'Stat band (members / cities / events)'],
            ['id' => 'about',     'enabled' => 1, 'label' => 'About / Mission'],
            ['id' => 'culture',   'enabled' => 1, 'label' => 'Culture & Traditions cards'],
            ['id' => 'history',   'enabled' => 1, 'label' => 'History timeline'],
            ['id' => 'resources', 'enabled' => 1, 'label' => 'Resources & Initiatives'],
            ['id' => 'events',    'enabled' => 1, 'label' => 'Upcoming Events'],
            ['id' => 'news',      'enabled' => 1, 'label' => 'Community Hub & News'],
            ['id' => 'cta',       'enabled' => 1, 'label' => 'Join community CTA'],
        ],

        // Quick toggles
        'maintenance_mode'  => 0,
        'allow_signups'     => 1,
        'allow_rsvp'        => 1,
        'allow_newsletter'  => 1,
        'show_lang_toggle'  => 1,
        'elementor_enabled' => 1,

        // Elementor
        'el_enabled_templates' => ['front-page', 'page', 'single', 'page-events', 'page-gallery', 'page-about'],
        'el_widgets_enabled'   => ['events', 'leaders', 'carpet', 'rsvp', 'newsletter', 'donate', 'gallery', 'quote', 'login', 'lang', 'filter'],
        'el_sync_colors'       => 1,
        'el_sync_fonts'        => 1,
        'el_canvas_template'   => 1,

        // Forms
        'contact_to'        => '',
        'captcha_provider'  => 'none',
        'captcha_site_key'  => '',
        'captcha_secret'    => '',
        'honeypot'          => 1,

        // Newsletter
        'newsletter_provider' => 'database',
        'newsletter_double_optin' => 1,

        // Performance
        'cache_enabled'     => 1,
        'minify_css'        => 1,
        'minify_js'         => 1,
        'lazy_load'         => 1,
    ];
}

/**
 * Get a single theme option (with fallback to default).
 */
function bh_get_option($key, $fallback = null) {
    $options = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    if (isset($options[$key])) return $options[$key];
    return $fallback;
}

/**
 * Update theme options.
 */
function bh_update_options($new) {
    $current = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    $merged  = array_merge($current, $new);
    update_option('bh_theme_options', $merged);
    return $merged;
}


/* ═══════════════════════════════════════════════════════
   ADMIN MENU
   ═══════════════════════════════════════════════════════ */

add_action('admin_menu', 'bh_register_admin_menu', 5);
function bh_register_admin_menu() {
    $cap = 'manage_options';
    add_menu_page(
        __('Baloch Heritage', 'baloch-heritage'),
        __('Baloch Heritage', 'baloch-heritage'),
        $cap,
        'bh-theme',
        'bh_render_admin_page',
        'dashicons-palmtree',
        3
    );
    add_submenu_page('bh-theme', __('Dashboard', 'baloch-heritage'),       __('Dashboard', 'baloch-heritage'),       $cap, 'bh-theme',                          'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Site Identity', 'baloch-heritage'),   __('Site Identity', 'baloch-heritage'),   $cap, 'bh-theme&tab=identity',             'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Appearance', 'baloch-heritage'),      __('Appearance', 'baloch-heritage'),      $cap, 'bh-theme&tab=appearance',           'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Homepage', 'baloch-heritage'),        __('Homepage', 'baloch-heritage'),        $cap, 'bh-theme&tab=homepage',             'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Elementor', 'baloch-heritage'),       __('Elementor', 'baloch-heritage'),       $cap, 'bh-theme&tab=elementor',            'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Forms', 'baloch-heritage'),           __('Forms', 'baloch-heritage'),           $cap, 'bh-theme&tab=forms',                'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Performance', 'baloch-heritage'),     __('Performance', 'baloch-heritage'),     $cap, 'bh-theme&tab=performance',          'bh_render_admin_page');
    add_submenu_page('bh-theme', __('Tools', 'baloch-heritage'),           __('Tools', 'baloch-heritage'),           $cap, 'bh-theme&tab=tools',                'bh_render_admin_page');
}

/**
 * Enqueue admin-panel CSS/JS on our settings screen only.
 */
add_action('admin_enqueue_scripts', 'bh_admin_enqueue');
function bh_admin_enqueue($hook) {
    if (strpos($hook, 'bh-theme') === false) return;
    wp_enqueue_style('bh-admin-panel',
        BH_URI . '/assets/css/admin-panel.css',
        [],
        BH_VERSION
    );
    wp_enqueue_style('bh-admin-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=Lato:wght@400;700&family=JetBrains+Mono:wght@400;600&display=swap',
        [],
        null
    );
    wp_enqueue_script('wp-color-picker');
    wp_enqueue_style('wp-color-picker');
    wp_enqueue_script('bh-admin-panel',
        BH_URI . '/assets/js/admin-panel.js',
        ['wp-color-picker', 'jquery', 'jquery-ui-sortable'],
        BH_VERSION,
        true
    );
    wp_localize_script('bh-admin-panel', 'bhAdmin', [
        'nonce' => wp_create_nonce('bh_admin'),
        'ajax'  => admin_url('admin-ajax.php'),
    ]);
}


/* ═══════════════════════════════════════════════════════
   SAVE HANDLER
   ═══════════════════════════════════════════════════════ */

add_action('admin_post_bh_save_settings', 'bh_handle_save_settings');
function bh_handle_save_settings() {
    if (!current_user_can('manage_options')) wp_die(__('Permission denied.', 'baloch-heritage'));
    check_admin_referer('bh_save_settings', 'bh_nonce');

    $tab = sanitize_key($_POST['tab'] ?? 'dashboard');
    $in  = $_POST;
    unset($in['_wpnonce'], $in['_wp_http_referer'], $in['action'], $in['tab'], $in['bh_nonce'], $in['submit']);

    $updates = [];
    $textarea_fields = ['address', 'hero_subtitle']; // preserve newlines for these
    foreach ($in as $key => $value) {
        if (is_array($value)) {
            $updates[$key] = array_map('sanitize_text_field', $value);
        } elseif (in_array($key, $textarea_fields, true)) {
            $updates[$key] = sanitize_textarea_field(wp_unslash($value));
        } else {
            $updates[$key] = sanitize_text_field(wp_unslash($value));
        }
    }

    // Special handling: homepage section order
    if (!empty($_POST['home_section_order']) && !empty($_POST['home_section_enabled'])) {
        $order   = (array) $_POST['home_section_order'];
        $enabled = (array) $_POST['home_section_enabled'];
        $defs    = bh_default_options()['home_sections'];
        $by_id   = [];
        foreach ($defs as $d) $by_id[$d['id']] = $d;
        $sections = [];
        foreach ($order as $id) {
            $id = sanitize_key($id);
            if (!isset($by_id[$id])) continue;
            $sections[] = [
                'id'      => $id,
                'enabled' => in_array($id, $enabled) ? 1 : 0,
                'label'   => $by_id[$id]['label'],
            ];
        }
        $updates['home_sections'] = $sections;
        unset($updates['home_section_order'], $updates['home_section_enabled']);
    }

    // Multi-value checkbox groups for Elementor
    if ($tab === 'elementor') {
        $updates['el_enabled_templates'] = isset($_POST['el_enabled_templates']) ? array_map('sanitize_key', (array)$_POST['el_enabled_templates']) : [];
        $updates['el_widgets_enabled']   = isset($_POST['el_widgets_enabled'])   ? array_map('sanitize_key', (array)$_POST['el_widgets_enabled'])   : [];
    }

    bh_update_options($updates);

    wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => $tab, 'saved' => 1], admin_url('admin.php')));
    exit;
}


/* ═══════════════════════════════════════════════════════
   PAGE RENDERER
   ═══════════════════════════════════════════════════════ */

function bh_render_admin_page() {
    $tab  = sanitize_key($_GET['tab'] ?? 'dashboard');
    $opts = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());

    $tabs = [
        'dashboard'   => ['label' => 'Dashboard',     'icon' => 'dashicons-dashboard'],
        'identity'    => ['label' => 'Site Identity', 'icon' => 'dashicons-admin-customizer'],
        'appearance'  => ['label' => 'Appearance',    'icon' => 'dashicons-art'],
        'homepage'    => ['label' => 'Homepage',      'icon' => 'dashicons-admin-home'],
        'menus'       => ['label' => 'Menus',         'icon' => 'dashicons-menu'],
        'elementor'   => ['label' => 'Elementor',     'icon' => 'dashicons-layout'],
        'forms'       => ['label' => 'Forms',         'icon' => 'dashicons-feedback'],
        'performance' => ['label' => 'Performance',   'icon' => 'dashicons-performance'],
        'tools'       => ['label' => 'Tools',         'icon' => 'dashicons-admin-tools'],
    ];
    ?>
    <div class="bh-admin-wrap">
        <div class="bh-admin-topbar">
            <div class="bh-tb-left">
                <span class="bh-tb-logo">CB</span>
                <div>
                    <strong><?php echo esc_html(get_bloginfo('name')); ?></strong>
                    <span><?php esc_html_e('Baloch Heritage theme · v', 'baloch-heritage'); echo esc_html(BH_VERSION); ?></span>
                </div>
            </div>
            <div class="bh-tb-right">
                <a href="<?php echo esc_url(home_url('/')); ?>" target="_blank" class="bh-tb-view">↗ <?php esc_html_e('View site', 'baloch-heritage'); ?></a>
                <a href="<?php echo esc_url(admin_url('customize.php')); ?>" class="bh-tb-view"><?php esc_html_e('Live Customizer', 'baloch-heritage'); ?></a>
            </div>
        </div>

        <?php if (!empty($_GET['saved'])): ?>
            <div class="bh-toast"><?php esc_html_e('✓ Settings saved.', 'baloch-heritage'); ?></div>
        <?php endif; ?>

        <div class="bh-admin-layout">
            <aside class="bh-admin-side">
                <?php foreach ($tabs as $key => $info):
                    $url = add_query_arg(['page' => 'bh-theme', 'tab' => $key], admin_url('admin.php'));
                    $active = $tab === $key ? ' active' : '';
                ?>
                    <a href="<?php echo esc_url($url); ?>" class="bh-side-link<?php echo $active; ?>">
                        <span class="dashicons <?php echo esc_attr($info['icon']); ?>"></span>
                        <?php echo esc_html($info['label']); ?>
                    </a>
                <?php endforeach; ?>

                <div class="bh-side-sep"></div>
                <a href="<?php echo esc_url(admin_url('admin.php?page=bh-members')); ?>" class="bh-side-link">
                    <span class="dashicons dashicons-groups"></span>
                    <?php esc_html_e('Members', 'baloch-heritage'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('edit.php?post_type=bh_event')); ?>" class="bh-side-link">
                    <span class="dashicons dashicons-calendar-alt"></span>
                    <?php esc_html_e('Events', 'baloch-heritage'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="bh-side-link">
                    <span class="dashicons dashicons-menu-alt3"></span>
                    <?php esc_html_e('Navigation Menus', 'baloch-heritage'); ?>
                </a>
                <a href="<?php echo esc_url(admin_url('themes.php')); ?>" class="bh-side-link">
                    <span class="dashicons dashicons-admin-appearance"></span>
                    <?php esc_html_e('WP Themes', 'baloch-heritage'); ?>
                </a>
            </aside>

            <main class="bh-admin-main">
                <?php
                $renderer = 'bh_tab_' . $tab;
                if (function_exists($renderer)) {
                    call_user_func($renderer, $opts);
                } else {
                    bh_tab_dashboard($opts);
                }
                ?>
            </main>
        </div>
    </div>
    <?php
}

/**
 * Reusable form-open / form-close helpers
 */
function bh_form_open($tab) {
    echo '<form method="post" action="' . esc_url(admin_url('admin-post.php')) . '" class="bh-form">';
    echo '<input type="hidden" name="action" value="bh_save_settings">';
    echo '<input type="hidden" name="tab" value="' . esc_attr($tab) . '">';
    wp_nonce_field('bh_save_settings', 'bh_nonce');
}
function bh_form_close($label = null) {
    if (!$label) $label = __('Save changes', 'baloch-heritage');
    echo '<div class="bh-form-foot"><button type="submit" class="bh-btn bh-btn-primary">' . esc_html($label) . '</button></div>';
    echo '</form>';
}


/* ═══════════════════════════════════════════════════════
   TAB: DASHBOARD
   ═══════════════════════════════════════════════════════ */

function bh_tab_dashboard($opts) {
    $pending = count(get_users(['role' => 'pending_member']));
    $members = count(get_users(['role__in' => ['bh_member','bh_moderator','bh_board']]));
    $events  = wp_count_posts('bh_event')->publish ?? 0;
    $articles= wp_count_posts('bh_article')->publish ?? 0;
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Dashboard', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Overview of your site’s membership, content, and theme status.', 'baloch-heritage'); ?></p>
        </div>
        <div class="bh-head-actions">
            <a href="<?php echo esc_url(admin_url('post-new.php?post_type=bh_event')); ?>" class="bh-btn bh-btn-ghost"><?php esc_html_e('+ New event', 'baloch-heritage'); ?></a>
            <a href="<?php echo esc_url(admin_url('admin.php?page=bh-theme&tab=appearance')); ?>" class="bh-btn bh-btn-primary"><?php esc_html_e('Customize theme', 'baloch-heritage'); ?></a>
        </div>
    </div>

    <div class="bh-stat-grid">
        <div class="bh-stat bh-stat-maroon">
            <div class="bh-stat-label"><?php esc_html_e('Total Members', 'baloch-heritage'); ?></div>
            <div class="bh-stat-num"><?php echo esc_html($members); ?></div>
            <div class="bh-stat-foot"><?php echo (int) $pending; ?> <?php esc_html_e('pending approval', 'baloch-heritage'); ?></div>
        </div>
        <div class="bh-stat bh-stat-orange">
            <div class="bh-stat-label"><?php esc_html_e('Published Events', 'baloch-heritage'); ?></div>
            <div class="bh-stat-num"><?php echo esc_html($events); ?></div>
            <div class="bh-stat-foot"><a href="<?php echo esc_url(admin_url('edit.php?post_type=bh_event')); ?>"><?php esc_html_e('Manage events →', 'baloch-heritage'); ?></a></div>
        </div>
        <div class="bh-stat bh-stat-green">
            <div class="bh-stat-label"><?php esc_html_e('Articles', 'baloch-heritage'); ?></div>
            <div class="bh-stat-num"><?php echo esc_html($articles); ?></div>
            <div class="bh-stat-foot"><a href="<?php echo esc_url(admin_url('edit.php?post_type=bh_article')); ?>"><?php esc_html_e('Manage articles →', 'baloch-heritage'); ?></a></div>
        </div>
        <div class="bh-stat bh-stat-blue">
            <div class="bh-stat-label"><?php esc_html_e('Newsletter', 'baloch-heritage'); ?></div>
            <div class="bh-stat-num"><?php echo count((array) get_option('bh_newsletter_subscribers', [])); ?></div>
            <div class="bh-stat-foot"><?php esc_html_e('subscribers', 'baloch-heritage'); ?></div>
        </div>
    </div>

    <div class="bh-grid-2">
        <div class="bh-card">
            <h3><?php esc_html_e('Site Health', 'baloch-heritage'); ?></h3>
            <ul class="bh-health">
                <li><span class="bh-dot ok"></span> <?php esc_html_e('WordPress', 'baloch-heritage'); ?> <code><?php echo esc_html(get_bloginfo('version')); ?></code></li>
                <li><span class="bh-dot ok"></span> <?php esc_html_e('PHP', 'baloch-heritage'); ?> <code><?php echo esc_html(PHP_VERSION); ?></code></li>
                <li><span class="bh-dot <?php echo did_action('elementor/loaded') ? 'ok' : 'warn'; ?>"></span>
                    <?php esc_html_e('Elementor', 'baloch-heritage'); ?>
                    <code><?php echo did_action('elementor/loaded') ? esc_html(defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : 'active') : esc_html__('not installed', 'baloch-heritage'); ?></code></li>
                <li><span class="bh-dot ok"></span> <?php esc_html_e('Permalinks', 'baloch-heritage'); ?> <code><?php echo esc_html(get_option('permalink_structure') ?: '/?p=123'); ?></code></li>
                <li><span class="bh-dot ok"></span> <?php esc_html_e('SSL', 'baloch-heritage'); ?> <code><?php echo is_ssl() ? 'active' : 'off'; ?></code></li>
            </ul>
        </div>

        <div class="bh-card">
            <h3><?php esc_html_e('Quick Toggles', 'baloch-heritage'); ?></h3>
            <?php bh_form_open('dashboard'); ?>
            <?php bh_field_switch('maintenance_mode',  __('Maintenance mode',  'baloch-heritage'), __('Show "back soon" to non-admins.', 'baloch-heritage'), $opts); ?>
            <?php bh_field_switch('allow_signups',     __('Member registrations',  'baloch-heritage'), __('Accept new applications via /join.', 'baloch-heritage'), $opts); ?>
            <?php bh_field_switch('allow_rsvp',        __('Event RSVP',  'baloch-heritage'), __('Allow public RSVPs without login.', 'baloch-heritage'), $opts); ?>
            <?php bh_field_switch('allow_newsletter',  __('Newsletter signup',  'baloch-heritage'), __('Show footer newsletter form.', 'baloch-heritage'), $opts); ?>
            <?php bh_field_switch('show_lang_toggle',  __('Bilingual toggle',  'baloch-heritage'), __('Show EN / Balochi header switch.', 'baloch-heritage'), $opts); ?>
            <?php bh_field_switch('elementor_enabled', __('Elementor support',  'baloch-heritage'), __('Allow override of theme templates by Elementor.', 'baloch-heritage'), $opts); ?>
            <?php bh_form_close(); ?>
        </div>
    </div>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: SITE IDENTITY
   ═══════════════════════════════════════════════════════ */

function bh_tab_identity($opts) {
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Site Identity', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Logo, name, contact details, and social profiles.', 'baloch-heritage'); ?></p>
        </div>
    </div>

    <?php bh_form_open('identity'); ?>

    <div class="bh-card">
        <h3><?php esc_html_e('Site title & taglines', 'baloch-heritage'); ?></h3>
        <?php bh_field_text('site_title',     __('Site title', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('tagline',        __('Tagline', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('hero_title',     __('Hero headline', 'baloch-heritage'), $opts); ?>
        <?php bh_field_textarea('hero_subtitle', __('Hero subhead', 'baloch-heritage'), $opts, 2); ?>
        <?php bh_field_select('bilingual_mode', __('Bilingual mode', 'baloch-heritage'), [
            'en_only'           => 'English only',
            'en_plus_balochi'   => 'EN + Balochi (Nastaliq)',
            'balochi_only'      => 'Balochi only',
        ], $opts); ?>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Logo & favicon', 'baloch-heritage'); ?></h3>
        <p class="bh-card-sub"><?php esc_html_e('Use the WordPress Customizer for image uploads — the theme uses the standard custom-logo + site-icon settings.', 'baloch-heritage'); ?></p>
        <div class="bh-row">
            <a href="<?php echo esc_url(admin_url('customize.php?autofocus[control]=custom_logo')); ?>" class="bh-btn bh-btn-ghost"><?php esc_html_e('Upload logo →', 'baloch-heritage'); ?></a>
            <a href="<?php echo esc_url(admin_url('customize.php?autofocus[section]=title_tagline')); ?>" class="bh-btn bh-btn-ghost"><?php esc_html_e('Upload favicon →', 'baloch-heritage'); ?></a>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Contact', 'baloch-heritage'); ?></h3>
        <div class="bh-row">
            <?php bh_field_text('phone', __('Phone', 'baloch-heritage'), $opts); ?>
            <?php bh_field_text('email', __('Email', 'baloch-heritage'), $opts); ?>
        </div>
        <?php bh_field_textarea('address', __('Address', 'baloch-heritage'), $opts, 2); ?>
        <div class="bh-row">
            <?php bh_field_text('office_hours', __('Office hours', 'baloch-heritage'), $opts); ?>
            <?php bh_field_text('map_coords',   __('Map coords (lat,lng)', 'baloch-heritage'), $opts); ?>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Social profiles', 'baloch-heritage'); ?></h3>
        <?php bh_field_text('facebook',  __('Facebook URL', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('instagram', __('Instagram URL', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('youtube',   __('YouTube URL', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('twitter',   __('Twitter / X URL', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('tiktok',    __('TikTok URL', 'baloch-heritage'), $opts); ?>
        <?php bh_field_text('whatsapp',  __('WhatsApp number', 'baloch-heritage'), $opts); ?>
    </div>

    <?php bh_form_close(); ?>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: APPEARANCE — colors, typography, layout, motif
   ═══════════════════════════════════════════════════════ */

function bh_tab_appearance($opts) {
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Appearance', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Colors, typography, layout, and the carpet motif. Changes apply on save.', 'baloch-heritage'); ?></p>
        </div>
    </div>

    <?php bh_form_open('appearance'); ?>

    <div class="bh-card">
        <h3><?php esc_html_e('Color palette', 'baloch-heritage'); ?></h3>
        <p class="bh-card-sub"><?php esc_html_e('Tokens map to CSS variables emitted in &lt;head&gt;.', 'baloch-heritage'); ?></p>
        <div class="bh-palette">
            <?php
            $colors = [
                'color_maroon'      => ['Primary maroon',   '--maroon',      'CTAs · headers'],
                'color_maroon_dark' => ['Maroon dark',      '--maroon-dark', 'Topbar · hover'],
                'color_orange'      => ['Accent orange',    '--orange',      'Links · accents'],
                'color_terra'       => ['Terra',            '--terra',       'Borders'],
                'color_sand'        => ['Sand',             '--sand',        'Ornament fills'],
                'color_cream'       => ['Background cream', '--cream',       'Page background'],
                'color_brown'       => ['Brown',            '--brown',       'Body text'],
            ];
            foreach ($colors as $key => $info):
                $val = $opts[$key];
            ?>
                <div class="bh-palette-row">
                    <label><?php echo esc_html($info[0]); ?> <code><?php echo esc_html($info[1]); ?></code></label>
                    <input type="text" name="<?php echo esc_attr($key); ?>" value="<?php echo esc_attr($val); ?>" class="bh-color-pick" data-default-color="<?php echo esc_attr(bh_default_options()[$key]); ?>">
                    <span class="bh-palette-use"><?php echo esc_html($info[2]); ?></span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Typography', 'baloch-heritage'); ?></h3>
        <div class="bh-row">
            <?php bh_field_select('font_display', __('Display / headings', 'baloch-heritage'), [
                'Playfair Display'   => 'Playfair Display',
                'Cormorant Garamond' => 'Cormorant Garamond',
                'Crimson Pro'        => 'Crimson Pro',
                'Spectral'           => 'Spectral',
                'EB Garamond'        => 'EB Garamond',
            ], $opts); ?>
            <?php bh_field_select('font_body', __('Body', 'baloch-heritage'), [
                'Lato'           => 'Lato',
                'Source Sans 3'  => 'Source Sans 3',
                'IBM Plex Sans'  => 'IBM Plex Sans',
                'Public Sans'    => 'Public Sans',
                'Nunito Sans'    => 'Nunito Sans',
            ], $opts); ?>
        </div>
        <div class="bh-row">
            <?php bh_field_select('font_urdu', __('Urdu / Balochi', 'baloch-heritage'), [
                'Noto Nastaliq Urdu'      => 'Noto Nastaliq Urdu',
                'Jameel Noori Nastaleeq'  => 'Jameel Noori Nastaleeq',
            ], $opts); ?>
            <?php bh_field_number('base_size', __('Base font size (px)', 'baloch-heritage'), $opts, 12, 22); ?>
        </div>
        <div class="bh-row">
            <?php bh_field_number('heading_scale', __('Heading scale', 'baloch-heritage'), $opts, 1.05, 1.7, 0.05); ?>
            <?php bh_field_number('line_height',   __('Body line height', 'baloch-heritage'), $opts, 1.3, 2.0, 0.05); ?>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Layout & spacing', 'baloch-heritage'); ?></h3>
        <div class="bh-row">
            <?php bh_field_number('container_width', __('Container width (px)', 'baloch-heritage'), $opts, 960, 1600, 20); ?>
            <?php bh_field_select('section_spacing', __('Section spacing', 'baloch-heritage'), [
                'compact'       => 'Compact',
                'comfortable'   => 'Comfortable',
                'airy'          => 'Airy',
            ], $opts); ?>
        </div>
        <div class="bh-row">
            <?php bh_field_number('border_radius', __('Border radius (px)', 'baloch-heritage'), $opts, 0, 24); ?>
            <?php bh_field_select('shadow_depth', __('Shadow depth', 'baloch-heritage'), [
                'none'  => 'None',
                'soft'  => 'Soft',
                'bold'  => 'Bold',
            ], $opts); ?>
        </div>
        <?php bh_field_select('header_style', __('Header style', 'baloch-heritage'), [
            'classic' => 'Classic — center logo, links below',
            'split'   => 'Split — logo left, CTA right',
            'mega'    => 'Mega — sticky with mega-menu',
        ], $opts); ?>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Carpet motif', 'baloch-heritage'); ?></h3>
        <p class="bh-card-sub"><?php esc_html_e('Decorative geometric pattern between sections.', 'baloch-heritage'); ?></p>
        <?php bh_field_switch('motif_enabled', __('Show carpet border between sections', 'baloch-heritage'), '', $opts); ?>
        <?php bh_field_select('motif_pattern', __('Pattern', 'baloch-heritage'), [
            'diamond'  => 'Diamond',
            'zigzag'   => 'Zigzag',
            'beaded'   => 'Beaded',
            'star'     => 'Star',
        ], $opts); ?>
        <div class="bh-row">
            <?php bh_field_number('motif_density', __('Density (px)', 'baloch-heritage'), $opts, 20, 60); ?>
            <?php bh_field_number('motif_stroke',  __('Stroke weight', 'baloch-heritage'), $opts, 0.4, 1.6, 0.1); ?>
        </div>
    </div>

    <?php bh_form_close(); ?>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: HOMEPAGE
   ═══════════════════════════════════════════════════════ */

function bh_tab_homepage($opts) {
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Homepage Builder', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Drag to reorder. Toggle to show or hide each section on the front page.', 'baloch-heritage'); ?></p>
        </div>
    </div>

    <?php bh_form_open('homepage'); ?>
    <div class="bh-card">
        <ul id="bh-section-list" class="bh-sections">
            <?php foreach ($opts['home_sections'] as $s): ?>
                <li class="bh-sec" data-id="<?php echo esc_attr($s['id']); ?>">
                    <span class="bh-sec-grip" aria-hidden="true">⋮⋮</span>
                    <strong><?php echo esc_html($s['label']); ?></strong>
                    <code><?php echo esc_html($s['id']); ?></code>
                    <input type="hidden" name="home_section_order[]" value="<?php echo esc_attr($s['id']); ?>">
                    <label class="bh-switch">
                        <input type="checkbox" name="home_section_enabled[]" value="<?php echo esc_attr($s['id']); ?>" <?php checked(!empty($s['enabled'])); ?>>
                        <span class="bh-slider"></span>
                    </label>
                </li>
            <?php endforeach; ?>
        </ul>
        <p class="bh-card-sub" style="margin-top:1rem;"><?php esc_html_e('Tip: hold the ⋮⋮ handle to drag a section up or down. Order persists on save.', 'baloch-heritage'); ?></p>
    </div>
    <?php bh_form_close(__('Save layout', 'baloch-heritage')); ?>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: MENUS — pointer to WP nav menus
   ═══════════════════════════════════════════════════════ */

function bh_tab_menus($opts) {
    $locations = get_registered_nav_menus();
    $assigned  = get_nav_menu_locations();
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Menus', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('The theme registers three menu locations. Build menus in Appearance → Menus and assign them here.', 'baloch-heritage'); ?></p>
        </div>
        <div class="bh-head-actions">
            <a href="<?php echo esc_url(admin_url('nav-menus.php')); ?>" class="bh-btn bh-btn-primary"><?php esc_html_e('Open Menu Editor', 'baloch-heritage'); ?></a>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Menu locations', 'baloch-heritage'); ?></h3>
        <table class="bh-dt">
            <thead><tr>
                <th><?php esc_html_e('Location', 'baloch-heritage'); ?></th>
                <th><?php esc_html_e('Assigned menu', 'baloch-heritage'); ?></th>
                <th></th>
            </tr></thead>
            <tbody>
            <?php foreach ($locations as $loc => $desc):
                $menu_id = $assigned[$loc] ?? 0;
                $menu    = $menu_id ? wp_get_nav_menu_object($menu_id) : null;
            ?>
                <tr>
                    <td><strong><?php echo esc_html($desc); ?></strong> <code><?php echo esc_html($loc); ?></code></td>
                    <td><?php echo $menu ? esc_html($menu->name) : '<em>' . esc_html__('Not assigned', 'baloch-heritage') . '</em>'; ?></td>
                    <td><a href="<?php echo esc_url(admin_url('nav-menus.php?action=locations')); ?>" class="bh-btn-tiny"><?php esc_html_e('Assign', 'baloch-heritage'); ?></a></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: ELEMENTOR
   ═══════════════════════════════════════════════════════ */

function bh_tab_elementor($opts) {
    $is_active     = did_action('elementor/loaded');
    $is_pro_active = defined('ELEMENTOR_PRO_VERSION');
    $el_version    = defined('ELEMENTOR_VERSION') ? ELEMENTOR_VERSION : '—';
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Elementor Integration', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('The theme registers a custom widget category, ships native widgets, and syncs design tokens into Elementor’s site settings.', 'baloch-heritage'); ?></p>
        </div>
        <?php if ($is_active): ?>
        <div class="bh-head-actions">
            <a href="<?php echo esc_url(admin_url('admin.php?page=elementor')); ?>" class="bh-btn bh-btn-ghost"><?php esc_html_e('Open Elementor Settings', 'baloch-heritage'); ?></a>
        </div>
        <?php endif; ?>
    </div>

    <?php if (!$is_active): ?>
        <div class="bh-card bh-callout">
            <strong><?php esc_html_e('Elementor is not installed yet.', 'baloch-heritage'); ?></strong>
            <p><?php esc_html_e('Install and activate Elementor to enable theme builder support and the 11 native CBCSC widgets. Without it, the theme renders via PHP templates only.', 'baloch-heritage'); ?></p>
            <a href="<?php echo esc_url(admin_url('plugin-install.php?s=elementor&tab=search&type=term')); ?>" class="bh-btn bh-btn-primary"><?php esc_html_e('Install Elementor', 'baloch-heritage'); ?></a>
        </div>
    <?php else: ?>
        <div class="bh-card bh-status">
            <div><span class="bh-dot ok"></span> <?php esc_html_e('Elementor', 'baloch-heritage'); ?> <code><?php echo esc_html($el_version); ?></code></div>
            <div><span class="bh-dot <?php echo $is_pro_active ? 'ok' : 'warn'; ?>"></span> <?php esc_html_e('Elementor Pro', 'baloch-heritage'); ?> <code><?php echo $is_pro_active ? esc_html(ELEMENTOR_PRO_VERSION) : esc_html__('not active', 'baloch-heritage'); ?></code></div>
            <div><span class="bh-dot ok"></span> <?php esc_html_e('CBCSC widgets', 'baloch-heritage'); ?> <code><?php echo count((array)$opts['el_widgets_enabled']); ?> / 11</code></div>
            <div><span class="bh-dot ok"></span> <?php esc_html_e('Site Settings sync', 'baloch-heritage'); ?> <code><?php echo !empty($opts['el_sync_colors']) ? esc_html__('on', 'baloch-heritage') : esc_html__('off', 'baloch-heritage'); ?></code></div>
        </div>
    <?php endif; ?>

    <?php bh_form_open('elementor'); ?>

    <div class="bh-card">
        <h3><?php esc_html_e('Builder support', 'baloch-heritage'); ?></h3>
        <p class="bh-card-sub"><?php esc_html_e('Templates where Elementor can override the PHP partial. Disabled = native theme PHP only.', 'baloch-heritage'); ?></p>

        <div class="bh-checkbox-grid">
            <?php
            $tpls = [
                'front-page'      => ['Front page',     'front-page.php'],
                'page'            => ['Default page',   'page.php'],
                'single'          => ['Single post',    'single.php'],
                'archive'         => ['Archive',        'archive.php'],
                'page-events'     => ['Events page',    'page-events.php'],
                'page-gallery'    => ['Gallery page',   'page-gallery.php'],
                'page-about'      => ['About page',     'page-about.php'],
                'page-news'       => ['News page',      'page-news.php'],
                'page-resources'  => ['Resources',      'page-resources.php'],
                'page-contact'    => ['Contact',        'page-contact.php'],
            ];
            $enabled = (array)$opts['el_enabled_templates'];
            foreach ($tpls as $key => $info):
            ?>
                <label class="bh-checkbox-card">
                    <input type="checkbox" name="el_enabled_templates[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $enabled)); ?>>
                    <span class="bh-checkbox-card-body">
                        <strong><?php echo esc_html($info[0]); ?></strong>
                        <code><?php echo esc_html($info[1]); ?></code>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Native widgets (CBCSC category)', 'baloch-heritage'); ?></h3>
        <p class="bh-card-sub"><?php esc_html_e('Toggle which custom widgets appear in the Elementor panel.', 'baloch-heritage'); ?></p>

        <div class="bh-checkbox-grid">
            <?php
            $widgets = [
                'events'     => ['Events Grid',      'cbcsc_events_grid'],
                'leaders'    => ['Leadership Grid',  'cbcsc_leaders'],
                'carpet'     => ['Carpet Border',    'cbcsc_carpet'],
                'rsvp'       => ['RSVP Form',        'cbcsc_rsvp'],
                'login'      => ['Member Login',     'cbcsc_member_login'],
                'newsletter' => ['Newsletter',       'cbcsc_newsletter'],
                'gallery'    => ['Gallery Masonry',  'cbcsc_gallery'],
                'donate'     => ['Donation CTA',     'cbcsc_donate'],
                'filter'     => ['CPT Filter Bar',   'cbcsc_filter_bar'],
                'quote'      => ['Story Quote',      'cbcsc_quote'],
                'lang'       => ['Language Switch',  'cbcsc_lang'],
            ];
            $enabled = (array)$opts['el_widgets_enabled'];
            foreach ($widgets as $key => $info):
            ?>
                <label class="bh-checkbox-card">
                    <input type="checkbox" name="el_widgets_enabled[]" value="<?php echo esc_attr($key); ?>" <?php checked(in_array($key, $enabled)); ?>>
                    <span class="bh-checkbox-card-body">
                        <strong><?php echo esc_html($info[0]); ?></strong>
                        <code><?php echo esc_html($info[1]); ?></code>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Site Settings sync', 'baloch-heritage'); ?></h3>
        <p class="bh-card-sub"><?php esc_html_e('Pushes theme color & font tokens to Elementor → Site Settings → Global Colors / Fonts.', 'baloch-heritage'); ?></p>
        <?php bh_field_switch('el_sync_colors', __('Sync color palette', 'baloch-heritage'), '', $opts); ?>
        <?php bh_field_switch('el_sync_fonts',  __('Sync typography',    'baloch-heritage'), '', $opts); ?>
        <?php bh_field_switch('el_canvas_template', __('Register "CBCSC Canvas" page template (no header/footer)', 'baloch-heritage'), '', $opts); ?>
    </div>

    <?php bh_form_close(); ?>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: FORMS
   ═══════════════════════════════════════════════════════ */

function bh_tab_forms($opts) {
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Forms', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Contact form, RSVP form, newsletter, and spam protection.', 'baloch-heritage'); ?></p>
        </div>
    </div>
    <?php bh_form_open('forms'); ?>

    <div class="bh-card">
        <h3><?php esc_html_e('Contact form', 'baloch-heritage'); ?></h3>
        <?php bh_field_text('contact_to', __('Send submissions to', 'baloch-heritage'), $opts, get_option('admin_email')); ?>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Captcha & spam', 'baloch-heritage'); ?></h3>
        <?php bh_field_select('captcha_provider', __('Captcha provider', 'baloch-heritage'), [
            'none'      => 'None',
            'hcaptcha'  => 'hCaptcha',
            'recaptcha' => 'reCAPTCHA v3',
            'turnstile' => 'Cloudflare Turnstile',
        ], $opts); ?>
        <div class="bh-row">
            <?php bh_field_text('captcha_site_key', __('Site key', 'baloch-heritage'), $opts); ?>
            <?php bh_field_text('captcha_secret',   __('Secret key', 'baloch-heritage'), $opts); ?>
        </div>
        <?php bh_field_switch('honeypot', __('Enable honeypot field on all forms', 'baloch-heritage'), '', $opts); ?>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('Newsletter', 'baloch-heritage'); ?></h3>
        <?php bh_field_select('newsletter_provider', __('Provider', 'baloch-heritage'), [
            'database'   => 'Database (built-in)',
            'mailchimp'  => 'Mailchimp',
            'mailerlite' => 'Mailerlite',
            'convertkit' => 'ConvertKit',
        ], $opts); ?>
        <?php bh_field_switch('newsletter_double_optin', __('Double opt-in', 'baloch-heritage'), '', $opts); ?>
    </div>

    <?php bh_form_close(); ?>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: PERFORMANCE
   ═══════════════════════════════════════════════════════ */

function bh_tab_performance($opts) {
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Performance', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Caching and asset optimization.', 'baloch-heritage'); ?></p>
        </div>
    </div>
    <?php bh_form_open('performance'); ?>
    <div class="bh-card">
        <h3><?php esc_html_e('Caching', 'baloch-heritage'); ?></h3>
        <?php bh_field_switch('cache_enabled', __('Enable page cache (requires WP Super Cache or similar)', 'baloch-heritage'), '', $opts); ?>
    </div>
    <div class="bh-card">
        <h3><?php esc_html_e('Asset optimization', 'baloch-heritage'); ?></h3>
        <?php bh_field_switch('minify_css', __('Minify theme CSS', 'baloch-heritage'), '', $opts); ?>
        <?php bh_field_switch('minify_js',  __('Minify theme JS',  'baloch-heritage'), '', $opts); ?>
        <?php bh_field_switch('lazy_load',  __('Lazy-load images', 'baloch-heritage'), '', $opts); ?>
    </div>
    <?php bh_form_close(); ?>
    <?php
}


/* ═══════════════════════════════════════════════════════
   TAB: TOOLS
   ═══════════════════════════════════════════════════════ */

function bh_tab_tools($opts) {
    $export_url = wp_nonce_url(admin_url('admin-post.php?action=bh_export_settings'), 'bh_export');
    $reset_url  = wp_nonce_url(admin_url('admin-post.php?action=bh_reset_settings'),  'bh_reset');
    ?>
    <div class="bh-head">
        <div>
            <h1><?php esc_html_e('Tools', 'baloch-heritage'); ?></h1>
            <p><?php esc_html_e('Export, import, reset, and inspect.', 'baloch-heritage'); ?></p>
        </div>
    </div>

    <div class="bh-grid-2">
        <div class="bh-card">
            <h3><?php esc_html_e('Export theme settings', 'baloch-heritage'); ?></h3>
            <p class="bh-card-sub"><?php esc_html_e('Downloads a JSON file of all current theme options.', 'baloch-heritage'); ?></p>
            <a href="<?php echo esc_url($export_url); ?>" class="bh-btn bh-btn-primary"><?php esc_html_e('Download JSON', 'baloch-heritage'); ?></a>
        </div>

        <div class="bh-card">
            <h3><?php esc_html_e('Import theme settings', 'baloch-heritage'); ?></h3>
            <p class="bh-card-sub"><?php esc_html_e('Paste an exported JSON to restore settings.', 'baloch-heritage'); ?></p>
            <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
                <input type="hidden" name="action" value="bh_import_settings">
                <?php wp_nonce_field('bh_import', 'bh_nonce'); ?>
                <textarea name="payload" rows="4" style="width:100%;font-family:monospace;font-size:12px;"></textarea>
                <button type="submit" class="bh-btn bh-btn-primary" style="margin-top:8px;"><?php esc_html_e('Import', 'baloch-heritage'); ?></button>
            </form>
        </div>

        <div class="bh-card">
            <h3><?php esc_html_e('Reset to defaults', 'baloch-heritage'); ?></h3>
            <p class="bh-card-sub"><?php esc_html_e('Wipes theme options. Content is untouched.', 'baloch-heritage'); ?></p>
            <a href="<?php echo esc_url($reset_url); ?>" class="bh-btn bh-btn-danger" onclick="return confirm('Reset all theme settings?');"><?php esc_html_e('Reset', 'baloch-heritage'); ?></a>
        </div>

        <div class="bh-card">
            <h3><?php esc_html_e('Flush rewrite rules', 'baloch-heritage'); ?></h3>
            <p class="bh-card-sub"><?php esc_html_e('Regenerate permalinks for custom post types.', 'baloch-heritage'); ?></p>
            <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=bh_flush_rewrites'), 'bh_flush')); ?>" class="bh-btn bh-btn-ghost"><?php esc_html_e('Flush now', 'baloch-heritage'); ?></a>
        </div>

        <div class="bh-card">
            <h3><?php esc_html_e('Generate Demo Content', 'baloch-heritage'); ?></h3>
            <p class="bh-card-sub"><?php esc_html_e('Creates standard pages, sets the homepage, configures permalinks, and adds dummy events/leaders.', 'baloch-heritage'); ?></p>
            <?php if (get_option('bh_demo_imported')): ?>
                <span style="color:var(--orange);">✓ <?php esc_html_e('Demo data already imported.', 'baloch-heritage'); ?></span>
            <?php else: ?>
                <a href="<?php echo esc_url(wp_nonce_url(admin_url('admin-post.php?action=bh_generate_demo'), 'bh_demo')); ?>" class="bh-btn bh-btn-primary" onclick="return confirm('This will create dummy pages and posts. Continue?');"><?php esc_html_e('Import Data', 'baloch-heritage'); ?></a>
            <?php endif; ?>
        </div>
    </div>

    <div class="bh-card">
        <h3><?php esc_html_e('System info', 'baloch-heritage'); ?></h3>
        <pre class="bh-sysinfo">CBCSC Theme            v<?php echo esc_html(BH_VERSION); ?>
WordPress              <?php echo esc_html(get_bloginfo('version')); ?>
PHP                    <?php echo esc_html(PHP_VERSION); ?>
Theme directory        <?php echo esc_html(get_template_directory()); ?>
Site URL               <?php echo esc_html(home_url()); ?>
Elementor              <?php echo defined('ELEMENTOR_VERSION') ? esc_html(ELEMENTOR_VERSION) : 'not installed'; ?>
Elementor Pro          <?php echo defined('ELEMENTOR_PRO_VERSION') ? esc_html(ELEMENTOR_PRO_VERSION) : 'not installed'; ?>
Custom post types      bh_event · bh_article · bh_resource · bh_leadership · bh_gallery
Active roles           pending_member · bh_member · bh_moderator · bh_board · administrator</pre>
    </div>
    <?php
}


/* ═══════════════════════════════════════════════════════
   FIELD HELPERS
   ═══════════════════════════════════════════════════════ */

function bh_field_text($key, $label, $opts, $placeholder = '') {
    $val = $opts[$key] ?? '';
    echo '<div class="bh-field">';
    echo '<label for="bh-' . esc_attr($key) . '">' . esc_html($label) . '</label>';
    echo '<input id="bh-' . esc_attr($key) . '" type="text" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '" placeholder="' . esc_attr($placeholder) . '">';
    echo '</div>';
}
function bh_field_textarea($key, $label, $opts, $rows = 3) {
    $val = $opts[$key] ?? '';
    echo '<div class="bh-field">';
    echo '<label for="bh-' . esc_attr($key) . '">' . esc_html($label) . '</label>';
    echo '<textarea id="bh-' . esc_attr($key) . '" name="' . esc_attr($key) . '" rows="' . (int)$rows . '">' . esc_textarea($val) . '</textarea>';
    echo '</div>';
}
function bh_field_select($key, $label, $choices, $opts) {
    $val = $opts[$key] ?? '';
    echo '<div class="bh-field">';
    echo '<label for="bh-' . esc_attr($key) . '">' . esc_html($label) . '</label>';
    echo '<select id="bh-' . esc_attr($key) . '" name="' . esc_attr($key) . '">';
    foreach ($choices as $value => $text) {
        printf('<option value="%s"%s>%s</option>', esc_attr($value), selected($val, $value, false), esc_html($text));
    }
    echo '</select></div>';
}
function bh_field_number($key, $label, $opts, $min = null, $max = null, $step = 1) {
    $val = $opts[$key] ?? '';
    $attrs = '';
    if ($min !== null) $attrs .= ' min="' . esc_attr($min) . '"';
    if ($max !== null) $attrs .= ' max="' . esc_attr($max) . '"';
    $attrs .= ' step="' . esc_attr($step) . '"';
    echo '<div class="bh-field">';
    echo '<label for="bh-' . esc_attr($key) . '">' . esc_html($label) . '</label>';
    echo '<input id="bh-' . esc_attr($key) . '" type="number" name="' . esc_attr($key) . '" value="' . esc_attr($val) . '"' . $attrs . '>';
    echo '</div>';
}
function bh_field_switch($key, $label, $sub, $opts) {
    $val = !empty($opts[$key]);
    echo '<label class="bh-switch-row">';
    echo '<span class="bh-switch-meta"><strong>' . esc_html($label) . '</strong>';
    if ($sub) echo '<span>' . esc_html($sub) . '</span>';
    echo '</span>';
    // Send "0" first so unchecked still posts a value
    echo '<input type="hidden" name="' . esc_attr($key) . '" value="0">';
    echo '<span class="bh-switch">';
    echo '<input type="checkbox" name="' . esc_attr($key) . '" value="1"' . checked($val, true, false) . '>';
    echo '<span class="bh-slider"></span></span>';
    echo '</label>';
}


/* ═══════════════════════════════════════════════════════
   TOOL HANDLERS
   ═══════════════════════════════════════════════════════ */

add_action('admin_post_bh_export_settings', function () {
    if (!current_user_can('manage_options')) wp_die('No.');
    check_admin_referer('bh_export');
    nocache_headers();
    header('Content-Type: application/json; charset=utf-8');
    header('Content-Disposition: attachment; filename="cbcsc-theme-settings.json"');
    echo wp_json_encode(get_option('bh_theme_options', []), JSON_PRETTY_PRINT);
    exit;
});

add_action('admin_post_bh_import_settings', function () {
    if (!current_user_can('manage_options')) wp_die('No.');
    check_admin_referer('bh_import', 'bh_nonce');
    $payload = wp_unslash($_POST['payload'] ?? '');
    $data    = json_decode($payload, true);
    if (is_array($data)) {
        update_option('bh_theme_options', array_merge(bh_default_options(), $data));
        wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => 'tools', 'saved' => 1], admin_url('admin.php')));
    } else {
        wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => 'tools', 'error' => 1], admin_url('admin.php')));
    }
    exit;
});

add_action('admin_post_bh_reset_settings', function () {
    if (!current_user_can('manage_options')) wp_die('No.');
    check_admin_referer('bh_reset');
    delete_option('bh_theme_options');
    wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => 'tools', 'saved' => 1], admin_url('admin.php')));
    exit;
});

add_action('admin_post_bh_flush_rewrites', function () {
    if (!current_user_can('manage_options')) wp_die('No.');
    check_admin_referer('bh_flush');
    flush_rewrite_rules();
    wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => 'tools', 'saved' => 1], admin_url('admin.php')));
    exit;
});

add_action('admin_post_bh_generate_demo', function () {
    if (!current_user_can('manage_options')) wp_die('No.');
    check_admin_referer('bh_demo');

    if (get_option('bh_demo_imported')) {
        wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => 'tools', 'saved' => 1], admin_url('admin.php')));
        exit;
    }

    // 1. Set Permalinks
    update_option('permalink_structure', '/%postname%/');
    flush_rewrite_rules();

    // 2. Create Pages
    $pages = [
        'Home'      => 'home',
        'About'     => 'about',
        'Events'    => 'events',
        'News'      => 'news',
        'Resources' => 'resources',
        'Contact'   => 'contact',
        'Gallery'   => 'gallery',
        'Join Us'   => 'join',
        'Members'   => 'members',
    ];
    $page_ids = [];
    foreach ($pages as $title => $slug) {
        $existing = get_page_by_path($slug);
        if (!$existing) {
            $id = wp_insert_post([
                'post_title'   => $title,
                'post_name'    => $slug,
                'post_status'  => 'publish',
                'post_type'    => 'page',
            ]);
            $page_ids[$slug] = $id;
        } else {
            $page_ids[$slug] = $existing->ID;
        }
    }

    // 3. Set Homepage
    if (isset($page_ids['home'])) {
        update_option('show_on_front', 'page');
        update_option('page_on_front', $page_ids['home']);
    }

    // 4. Dummy Content - Events
    $dummy_events = [
        ['title' => 'Annual Cultural Festival', 'date' => '+15 days', 'loc' => 'Toronto Convention Centre', 'type' => 'cultural'],
        ['title' => 'Balochi Music Night', 'date' => '+30 days', 'loc' => 'Vancouver Arts Centre', 'type' => 'music'],
        ['title' => 'Youth Leadership Summit', 'date' => '+45 days', 'loc' => 'Calgary Community Hall', 'type' => 'youth'],
    ];
    foreach ($dummy_events as $ev) {
        $id = wp_insert_post([
            'post_title'   => $ev['title'],
            'post_status'  => 'publish',
            'post_type'    => 'bh_event',
            'post_excerpt' => 'Join us for this wonderful event bringing the community together.',
        ]);
        update_post_meta($id, '_bh_event_date', date('Y-m-d', strtotime($ev['date'])));
        update_post_meta($id, '_bh_event_location', $ev['loc']);
        update_post_meta($id, '_bh_event_type', $ev['type']);
    }

    // Dummy Content - Leaders
    $dummy_leaders = [
        ['title' => 'Mir Ahmed', 'role' => 'President'],
        ['title' => 'Sara Baloch', 'role' => 'Vice President'],
        ['title' => 'Farhan Mengal', 'role' => 'Community Director'],
    ];
    foreach ($dummy_leaders as $idx => $ld) {
        $id = wp_insert_post([
            'post_title'   => $ld['title'],
            'post_status'  => 'publish',
            'post_type'    => 'bh_leadership',
            'post_excerpt' => 'Dedicated to preserving our culture and empowering the next generation.',
        ]);
        update_post_meta($id, '_bh_leader_title', $ld['role']);
        update_post_meta($id, '_bh_leader_order', $idx + 1);
    }

    // Dummy Content - Resources
    $dummy_resources = [
        ['title' => 'Balochi Language Primer', 'type' => 'language'],
        ['title' => 'Community Scholarship Form', 'type' => 'education'],
    ];
    foreach ($dummy_resources as $res) {
        $id = wp_insert_post([
            'post_title'   => $res['title'],
            'post_status'  => 'publish',
            'post_type'    => 'bh_resource',
            'post_excerpt' => 'A valuable resource for members of the Baloch Heritage community.',
        ]);
        update_post_meta($id, '_bh_resource_type', $res['type']);
    }

    // 5. Create Primary Menu
    $menu_name = 'Main Navigation';
    $menu_exists = wp_get_nav_menu_object($menu_name);
    if (!$menu_exists) {
        $menu_id = wp_create_nav_menu($menu_name);
        $menu_items = ['Home'=>'home', 'About'=>'about', 'Events'=>'events', 'Resources'=>'resources', 'Contact'=>'contact'];
        foreach ($menu_items as $title => $slug) {
            $pid = isset($page_ids[$slug]) ? $page_ids[$slug] : 0;
            if ($pid) {
                wp_update_nav_menu_item($menu_id, 0, [
                    'menu-item-title'     => $title,
                    'menu-item-object-id' => $pid,
                    'menu-item-object'    => 'page',
                    'menu-item-status'    => 'publish',
                    'menu-item-type'      => 'post_type',
                ]);
            }
        }
        $locations = get_theme_mod('nav_menu_locations');
        $locations['primary'] = $menu_id;
        set_theme_mod('nav_menu_locations', $locations);
    }

    update_option('bh_demo_imported', 1);

    wp_safe_redirect(add_query_arg(['page' => 'bh-theme', 'tab' => 'tools', 'saved' => 1], admin_url('admin.php')));
    exit;
});


/* ═══════════════════════════════════════════════════════
   PUBLIC HELPER — check if a homepage section is enabled
   Used by front-page.php to gate top-level <section> blocks.
   ═══════════════════════════════════════════════════════ */
function bh_section_enabled($id) {
    $opts = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    foreach ((array) $opts['home_sections'] as $s) {
        if (($s['id'] ?? '') === $id) return !empty($s['enabled']);
    }
    return true; // unknown = show
}

/* ═══════════════════════════════════════════════════════
   OUTPUT THEME OPTIONS AS CSS VARIABLES ON THE FRONT-END
   ═══════════════════════════════════════════════════════ */

add_action('wp_head', 'bh_output_dynamic_css', 99);
function bh_output_dynamic_css() {
    $o = wp_parse_args(get_option('bh_theme_options', []), bh_default_options());
    $density = max(20, min(60, (int)$o['motif_density']));
    echo "<style id='bh-dynamic-vars'>:root{";
    echo "--maroon:" . esc_attr($o['color_maroon']) . ";";
    echo "--maroon-dark:" . esc_attr($o['color_maroon_dark']) . ";";
    echo "--orange:" . esc_attr($o['color_orange']) . ";";
    echo "--terra:" . esc_attr($o['color_terra']) . ";";
    echo "--sand:" . esc_attr($o['color_sand']) . ";";
    echo "--cream:" . esc_attr($o['color_cream']) . ";";
    echo "--brown:" . esc_attr($o['color_brown']) . ";";
    echo "--bh-font-display:'" . esc_attr($o['font_display']) . "',serif;";
    echo "--bh-font-body:'" . esc_attr($o['font_body']) . "',sans-serif;";
    echo "--bh-base-size:" . (int)$o['base_size'] . "px;";
    echo "--bh-line-height:" . esc_attr($o['line_height']) . ";";
    echo "--bh-container:" . (int)$o['container_width'] . "px;";
    echo "--bh-radius:" . (int)$o['border_radius'] . "px;";
    echo "--bh-motif-density:" . $density . "px;";
    echo "}</style>\n";

    // Maintenance mode
    if (!empty($o['maintenance_mode']) && !current_user_can('manage_options')) {
        wp_die(
            '<h1 style="font-family:Playfair Display,serif;color:#7A1C1C;">' . esc_html__('Back soon', 'baloch-heritage') . '</h1>' .
            '<p>' . esc_html__('We’re making improvements. Please check back shortly.', 'baloch-heritage') . '</p>',
            esc_html(get_bloginfo('name')),
            ['response' => 503]
        );
    }
}
