<?php
/**
 * Baloch Heritage Canada — functions.php
 * Main theme setup, CPTs, user roles, membership system
 */

defined('ABSPATH') || exit;

define('BH_VERSION', '1.0.0');
define('BH_DIR', get_template_directory());
define('BH_URI', get_template_directory_uri());

/* ═══════════════════════════════════════════════════════
   1. THEME SETUP
   ═══════════════════════════════════════════════════════ */
function bh_setup() {
    load_theme_textdomain('baloch-heritage', BH_DIR . '/languages');
    add_theme_support('title-tag');
    add_theme_support('post-thumbnails');
    add_theme_support('html5', ['search-form','comment-form','comment-list','gallery','caption','style','script']);
    add_theme_support('custom-logo', ['height'=>80,'width'=>200,'flex-height'=>true,'flex-width'=>true]);
    add_theme_support('customize-selective-refresh-widgets');
    add_theme_support('wp-block-styles');
    add_theme_support('align-wide');
    add_image_size('bh-card',   600, 400, true);
    add_image_size('bh-hero',  1440, 600, true);
    add_image_size('bh-thumb',  400, 300, true);
    register_nav_menus([
        'primary'   => __('Primary Navigation', 'baloch-heritage'),
        'footer'    => __('Footer Navigation',   'baloch-heritage'),
        'mobile'    => __('Mobile Navigation',   'baloch-heritage'),
    ]);
}
add_action('after_setup_theme', 'bh_setup');

/* ═══════════════════════════════════════════════════════
   2. ENQUEUE SCRIPTS & STYLES
   ═══════════════════════════════════════════════════════ */
function bh_enqueue() {
    // Google Fonts
    wp_enqueue_style('bh-fonts',
        'https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700;900&family=Lato:wght@300;400;700&family=Noto+Nastaliq+Urdu&display=swap',
        [], null);

    // Main stylesheet
    wp_enqueue_style('bh-shared', BH_URI . '/assets/css/baloch-shared.css', ['bh-fonts'], BH_VERSION);

    // Page-specific styles
    $page_template = get_page_template_slug();
    if ($page_template) {
        $slug = str_replace(['page-', '.php'], '', basename($page_template));
        $css_file = BH_DIR . "/assets/css/page-{$slug}.css";
        if (file_exists($css_file)) {
            wp_enqueue_style("bh-page-{$slug}", BH_URI . "/assets/css/page-{$slug}.css", ['bh-shared'], BH_VERSION);
        }
    }

    // Main JS
    wp_enqueue_script('bh-shared', BH_URI . '/assets/js/baloch-shared.js', [], BH_VERSION, true);

    // Localize script with AJAX data
    wp_localize_script('bh-shared', 'bhAjax', [
        'url'   => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('bh_nonce'),
        'isLoggedIn' => is_user_logged_in(),
        'userId' => get_current_user_id(),
        'userRole' => bh_get_user_role_label(),
    ]);
}
add_action('wp_enqueue_scripts', 'bh_enqueue');

/* ═══════════════════════════════════════════════════════
   3. CUSTOM POST TYPES
   ═══════════════════════════════════════════════════════ */
require_once BH_DIR . '/inc/custom-post-types.php';

/* ═══════════════════════════════════════════════════════
   4. USER ROLES & MEMBERSHIP
   ═══════════════════════════════════════════════════════ */
require_once BH_DIR . '/inc/user-roles.php';
require_once BH_DIR . '/inc/membership.php';

/* ═══════════════════════════════════════════════════════
   4a. ADMIN SETTINGS PANEL + ELEMENTOR INTEGRATION
   ═══════════════════════════════════════════════════════ */
require_once BH_DIR . '/inc/admin-panel.php';
require_once BH_DIR . '/inc/elementor.php';

/* ═══════════════════════════════════════════════════════
   5. CUSTOM FIELDS (without ACF dependency)
   ═══════════════════════════════════════════════════════ */
function bh_register_meta_boxes() {
    // Event meta
    add_meta_box('bh_event_details', __('Event Details', 'baloch-heritage'), 'bh_event_meta_box', 'bh_event', 'normal', 'high');
    // Resource meta
    add_meta_box('bh_resource_details', __('Resource Details', 'baloch-heritage'), 'bh_resource_meta_box', 'bh_resource', 'normal', 'high');
    // Leadership meta
    add_meta_box('bh_leader_details', __('Leader Details', 'baloch-heritage'), 'bh_leader_meta_box', 'bh_leadership', 'normal', 'high');
}
add_action('add_meta_boxes', 'bh_register_meta_boxes');

function bh_event_meta_box($post) {
    wp_nonce_field('bh_event_meta', 'bh_event_nonce');
    $date     = get_post_meta($post->ID, '_bh_event_date', true);
    $location = get_post_meta($post->ID, '_bh_event_location', true);
    $capacity = get_post_meta($post->ID, '_bh_event_capacity', true);
    $type     = get_post_meta($post->ID, '_bh_event_type', true);
    $price    = get_post_meta($post->ID, '_bh_event_price', true);
    $rsvp     = get_post_meta($post->ID, '_bh_event_rsvp_email', true);
    $types    = ['cultural','music','youth','community','sports','education'];
    echo '<table class="form-table"><tbody>';
    echo "<tr><th><label>Event Date *</label></th><td><input type='date' name='bh_event_date' value='" . esc_attr($date) . "' class='regular-text' required></td></tr>";
    echo "<tr><th><label>Location *</label></th><td><input type='text' name='bh_event_location' value='" . esc_attr($location) . "' class='regular-text' placeholder='Venue, City, Province'></td></tr>";
    echo "<tr><th><label>Capacity</label></th><td><input type='number' name='bh_event_capacity' value='" . esc_attr($capacity) . "' class='small-text'></td></tr>";
    echo "<tr><th><label>Event Type</label></th><td><select name='bh_event_type'>";
    foreach ($types as $t) echo "<option value='$t'" . selected($type, $t, false) . ">" . ucfirst($t) . "</option>";
    echo "</select></td></tr>";
    echo "<tr><th><label>Price</label></th><td><input type='text' name='bh_event_price' value='" . esc_attr($price) . "' placeholder='Free / \$20'></td></tr>";
    echo "<tr><th><label>RSVP Email</label></th><td><input type='email' name='bh_event_rsvp_email' value='" . esc_attr($rsvp) . "' class='regular-text'></td></tr>";
    echo '</tbody></table>';
}

function bh_resource_meta_box($post) {
    wp_nonce_field('bh_resource_meta', 'bh_resource_nonce');
    $type     = get_post_meta($post->ID, '_bh_resource_type', true);
    $link     = get_post_meta($post->ID, '_bh_resource_link', true);
    $deadline = get_post_meta($post->ID, '_bh_resource_deadline', true);
    $status   = get_post_meta($post->ID, '_bh_resource_status', true);
    $file_id  = get_post_meta($post->ID, '_bh_resource_file_id', true);
    $types    = ['education','language','empowerment','social','download','link'];
    echo '<table class="form-table"><tbody>';
    echo "<tr><th><label>Resource Type</label></th><td><select name='bh_resource_type'>";
    foreach ($types as $t) echo "<option value='$t'" . selected($type, $t, false) . ">" . ucfirst($t) . "</option>";
    echo "</select></td></tr>";
    echo "<tr><th><label>External Link</label></th><td><input type='url' name='bh_resource_link' value='" . esc_attr($link) . "' class='regular-text'></td></tr>";
    echo "<tr><th><label>Deadline</label></th><td><input type='date' name='bh_resource_deadline' value='" . esc_attr($deadline) . "'></td></tr>";
    echo "<tr><th><label>Status Badge</label></th><td><input type='text' name='bh_resource_status' value='" . esc_attr($status) . "' placeholder='e.g. Open · Deadline: June 30'></td></tr>";
    echo '</tbody></table>';
}

function bh_leader_meta_box($post) {
    wp_nonce_field('bh_leader_meta', 'bh_leader_nonce');
    $title    = get_post_meta($post->ID, '_bh_leader_title', true);
    $email    = get_post_meta($post->ID, '_bh_leader_email', true);
    $linkedin = get_post_meta($post->ID, '_bh_leader_linkedin', true);
    $twitter  = get_post_meta($post->ID, '_bh_leader_twitter', true);
    $city     = get_post_meta($post->ID, '_bh_leader_city', true);
    $order    = get_post_meta($post->ID, '_bh_leader_order', true);
    echo '<table class="form-table"><tbody>';
    echo "<tr><th><label>Title/Role *</label></th><td><input type='text' name='bh_leader_title' value='" . esc_attr($title) . "' class='regular-text' placeholder='President & Founder'></td></tr>";
    echo "<tr><th><label>Email</label></th><td><input type='email' name='bh_leader_email' value='" . esc_attr($email) . "' class='regular-text'></td></tr>";
    echo "<tr><th><label>LinkedIn URL</label></th><td><input type='url' name='bh_leader_linkedin' value='" . esc_attr($linkedin) . "' class='regular-text'></td></tr>";
    echo "<tr><th><label>Twitter URL</label></th><td><input type='url' name='bh_leader_twitter' value='" . esc_attr($twitter) . "' class='regular-text'></td></tr>";
    echo "<tr><th><label>City</label></th><td><input type='text' name='bh_leader_city' value='" . esc_attr($city) . "' class='regular-text'></td></tr>";
    echo "<tr><th><label>Display Order</label></th><td><input type='number' name='bh_leader_order' value='" . esc_attr($order) . "' class='small-text'></td></tr>";
    echo '</tbody></table>';
}

function bh_save_meta($post_id) {
    if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) return;
    if (!current_user_can('edit_post', $post_id)) return;

    $meta_map = [
        'bh_event'      => ['bh_event_date','bh_event_location','bh_event_capacity','bh_event_type','bh_event_price','bh_event_rsvp_email'],
        'bh_resource'   => ['bh_resource_type','bh_resource_link','bh_resource_deadline','bh_resource_status'],
        'bh_leadership' => ['bh_leader_title','bh_leader_email','bh_leader_linkedin','bh_leader_twitter','bh_leader_city','bh_leader_order'],
    ];

    $post_type = get_post_type($post_id);
    if (!isset($meta_map[$post_type])) return;

    foreach ($meta_map[$post_type] as $field) {
        if (isset($_POST[$field])) {
            update_post_meta($post_id, '_' . $field, sanitize_text_field($_POST[$field]));
        }
    }
}
add_action('save_post', 'bh_save_meta');

/* ═══════════════════════════════════════════════════════
   6. THEME OPTIONS (Customizer)
   ═══════════════════════════════════════════════════════ */
function bh_customizer($wp_customize) {
    // Panel
    $wp_customize->add_panel('bh_panel', ['title'=>__('Baloch Heritage', 'baloch-heritage'), 'priority'=>30]);

    // Section: General
    $wp_customize->add_section('bh_general', ['title'=>__('General Settings','baloch-heritage'), 'panel'=>'bh_panel']);
    $settings = [
        'bh_phone'       => ['label'=>'Phone Number',    'default'=>'(416) 555-0001'],
        'bh_email'       => ['label'=>'Email Address',   'default'=>'info@balochheritage.org'],
        'bh_address'     => ['label'=>'Address',         'default'=>'123 Industry Street, Toronto, ON M5H 3X9'],
        'bh_facebook'    => ['label'=>'Facebook URL',    'default'=>'#'],
        'bh_instagram'   => ['label'=>'Instagram URL',   'default'=>'#'],
        'bh_youtube'     => ['label'=>'YouTube URL',     'default'=>'#'],
        'bh_twitter'     => ['label'=>'Twitter URL',     'default'=>'#'],
        'bh_hero_title'  => ['label'=>'Hero Title',      'default'=>'Celebrating Baloch Heritage & Community'],
        'bh_hero_sub'    => ['label'=>'Hero Subtitle',   'default'=>'Explore our rich culture, history, and vibrant traditions.'],
    ];
    foreach ($settings as $id => $opts) {
        $wp_customize->add_setting($id, ['default'=>$opts['default'], 'sanitize_callback'=>'sanitize_text_field', 'transport'=>'refresh']);
        $wp_customize->add_control($id, ['label'=>$opts['label'], 'section'=>'bh_general', 'type'=>'text']);
    }

    // Section: Colors
    $wp_customize->add_section('bh_colors', ['title'=>__('Color Palette','baloch-heritage'), 'panel'=>'bh_panel']);
    $colors = [
        'bh_color_maroon' => ['label'=>'Primary Maroon',   'default'=>'#7A1C1C'],
        'bh_color_orange' => ['label'=>'Accent Orange',    'default'=>'#E07B39'],
        'bh_color_cream'  => ['label'=>'Background Cream', 'default'=>'#F7EDD8'],
    ];
    foreach ($colors as $id => $opts) {
        $wp_customize->add_setting($id, ['default'=>$opts['default'], 'sanitize_callback'=>'sanitize_hex_color', 'transport'=>'postMessage']);
        $wp_customize->add_control(new WP_Customize_Color_Control($wp_customize, $id, ['label'=>$opts['label'], 'section'=>'bh_colors']));
    }
}
add_action('customize_register', 'bh_customizer');

// Output custom color CSS vars — superseded by bh_output_dynamic_css() in inc/admin-panel.php.
// Kept here as a fallback when admin-panel.php is somehow not loaded; both emit !id attributes
// so the second one wins via cascade order.
function bh_output_color_vars() {
    if (function_exists('bh_output_dynamic_css')) return; // admin-panel handles it
    $maroon = get_theme_mod('bh_color_maroon', '#7A1C1C');
    $orange = get_theme_mod('bh_color_orange', '#E07B39');
    $cream  = get_theme_mod('bh_color_cream',  '#F7EDD8');
    echo "<style>:root{--maroon:{$maroon};--orange:{$orange};--cream:{$cream};}</style>\n";
}
add_action('wp_head', 'bh_output_color_vars');

/* ═══════════════════════════════════════════════════════
   7. HELPER FUNCTIONS
   ═══════════════════════════════════════════════════════ */
function bh_get_user_role_label($user_id = null) {
    if (!$user_id) $user_id = get_current_user_id();
    if (!$user_id) return '';
    $user = get_userdata($user_id);
    if (!$user) return '';
    $role = $user->roles[0] ?? '';
    $labels = [
        'pending_member' => 'Pending Approval',
        'bh_member'      => 'Member',
        'bh_moderator'   => 'Moderator',
        'bh_board'       => 'Board Member',
        'administrator'  => 'Administrator',
    ];
    return $labels[$role] ?? ucfirst($role);
}

function bh_can_approve() {
    return current_user_can('bh_approve_members');
}

function bh_carpet_border($color = '#561010') {
    $id = 'cp' . uniqid();
    return "<div class='carpet-border' aria-hidden='true'>
    <svg viewBox='0 0 1440 28' preserveAspectRatio='none' xmlns='http://www.w3.org/2000/svg'>
      <defs><pattern id='{$id}' x='0' y='0' width='36' height='28' patternUnits='userSpaceOnUse'>
        <polygon points='18,4 26,14 18,24 10,14' fill='rgba(224,123,57,0.15)' stroke='#E07B39' stroke-width='0.8'/>
        <polygon points='18,9 22,14 18,19 14,14' fill='rgba(196,98,45,0.2)' stroke='#D4A574' stroke-width='0.6'/>
        <circle cx='0' cy='14' r='1.5' fill='#E07B39'/><circle cx='36' cy='14' r='1.5' fill='#E07B39'/>
        <line x1='0' y1='14' x2='10' y2='14' stroke='#C4622D' stroke-width='0.5'/>
        <line x1='26' y1='14' x2='36' y2='14' stroke='#C4622D' stroke-width='0.5'/>
      </pattern></defs>
      <rect width='1440' height='28' fill='{$color}'/>
      <rect width='1440' height='28' fill='url(#{$id})'/>
      <line x1='0' y1='1.5' x2='1440' y2='1.5' stroke='#D4A574' stroke-width='1' opacity='0.5'/>
      <line x1='0' y1='26.5' x2='1440' y2='26.5' stroke='#D4A574' stroke-width='1' opacity='0.5'/>
    </svg></div>";
}

function bh_get_events($limit = 6, $upcoming_only = true) {
    $args = [
        'post_type'      => 'bh_event',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_key'       => '_bh_event_date',
        'orderby'        => 'meta_value',
        'order'          => 'ASC',
    ];
    if ($upcoming_only) {
        $args['meta_query'] = [[
            'key'     => '_bh_event_date',
            'value'   => date('Y-m-d'),
            'compare' => '>=',
            'type'    => 'DATE',
        ]];
    }
    return new WP_Query($args);
}

function bh_get_leaders($limit = -1) {
    return new WP_Query([
        'post_type'      => 'bh_leadership',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'meta_key'       => '_bh_leader_order',
        'orderby'        => 'meta_value_num',
        'order'          => 'ASC',
    ]);
}

function bh_get_resources($type = '', $limit = -1) {
    $args = [
        'post_type'      => 'bh_resource',
        'posts_per_page' => $limit,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];
    if ($type) {
        $args['meta_query'] = [['key'=>'_bh_resource_type','value'=>$type,'compare'=>'=']];
    }
    return new WP_Query($args);
}

/* ═══════════════════════════════════════════════════════
   8. AJAX HANDLERS
   ═══════════════════════════════════════════════════════ */
// RSVP for events
add_action('wp_ajax_bh_event_rsvp',        'bh_ajax_event_rsvp');
add_action('wp_ajax_nopriv_bh_event_rsvp', 'bh_ajax_event_rsvp');
function bh_ajax_event_rsvp() {
    check_ajax_referer('bh_nonce', 'nonce');
    $event_id = absint($_POST['event_id'] ?? 0);
    $name     = sanitize_text_field($_POST['name'] ?? '');
    $email    = sanitize_email($_POST['email'] ?? '');
    if (!$event_id || !$name || !$email) {
        wp_send_json_error(['message' => __('Please fill all required fields.', 'baloch-heritage')]);
    }
    // Store RSVP as post meta
    $rsvps   = get_post_meta($event_id, '_bh_event_rsvps', true) ?: [];
    $rsvps[] = ['name'=>$name,'email'=>$email,'date'=>current_time('mysql'),'user_id'=>get_current_user_id()];
    update_post_meta($event_id, '_bh_event_rsvps', $rsvps);
    // Send confirmation email
    $event_title = get_the_title($event_id);
    $event_date  = get_post_meta($event_id, '_bh_event_date', true);
    wp_mail($email, "RSVP Confirmed — {$event_title}", "Dear {$name},\n\nYour RSVP for {$event_title} on {$event_date} has been confirmed.\n\nBaloch Heritage Canada");
    wp_send_json_success(['message' => __('RSVP confirmed! Check your email.', 'baloch-heritage')]);
}

// Newsletter signup
add_action('wp_ajax_bh_newsletter',        'bh_ajax_newsletter');
add_action('wp_ajax_nopriv_bh_newsletter', 'bh_ajax_newsletter');
function bh_ajax_newsletter() {
    check_ajax_referer('bh_nonce', 'nonce');
    $email = sanitize_email($_POST['email'] ?? '');
    if (!is_email($email)) {
        wp_send_json_error(['message' => __('Please enter a valid email.', 'baloch-heritage')]);
    }
    $subscribers = get_option('bh_newsletter_subscribers', []);
    if (!in_array($email, $subscribers)) {
        $subscribers[] = $email;
        update_option('bh_newsletter_subscribers', $subscribers);
    }
    wp_send_json_success(['message' => __('Thank you for subscribing!', 'baloch-heritage')]);
}

// Contact form submission
add_action('wp_ajax_bh_contact',        'bh_ajax_contact');
add_action('wp_ajax_nopriv_bh_contact', 'bh_ajax_contact');
function bh_ajax_contact() {
    check_ajax_referer('bh_nonce', 'nonce');
    $name    = sanitize_text_field($_POST['name'] ?? '');
    $email   = sanitize_email($_POST['email'] ?? '');
    $subject = sanitize_text_field($_POST['subject'] ?? '');
    $message = sanitize_textarea_field($_POST['message'] ?? '');
    if (!$name || !$email || !$subject || !$message) {
        wp_send_json_error(['message' => __('Please fill all required fields.', 'baloch-heritage')]);
    }
    $admin_email = get_option('bh_contact_email', get_option('admin_email'));
    wp_mail($admin_email, "Contact Form: {$subject}", "From: {$name} ({$email})\n\n{$message}",
        ["Reply-To: {$name} <{$email}>"]);
    wp_send_json_success(['message' => __("Thank you! We'll be in touch within 1–2 business days.", 'baloch-heritage')]);
}

/* ═══════════════════════════════════════════════════════
   9. ADMIN CUSTOMIZATIONS
   ═══════════════════════════════════════════════════════ */
// Add Members admin menu
function bh_admin_menu() {
    add_menu_page(__('BH Members','baloch-heritage'), __('BH Members','baloch-heritage'), 'list_users', 'bh-members', 'bh_members_admin_page', 'dashicons-groups', 25);
    add_submenu_page('bh-members', __('Pending Approvals','baloch-heritage'), __('Pending Approvals','baloch-heritage'), 'bh_approve_members', 'bh-approvals', 'bh_approvals_admin_page');
    add_submenu_page('bh-members', __('All Members','baloch-heritage'), __('All Members','baloch-heritage'), 'list_users', 'bh-all-members', 'bh_all_members_admin_page');
}
add_action('admin_menu', 'bh_admin_menu');

function bh_members_admin_page() {
    $pending = get_users(['role'=>'pending_member']);
    $members = get_users(['role'=>'bh_member']);
    echo '<div class="wrap"><h1>' . __('Baloch Heritage Members', 'baloch-heritage') . '</h1>';
    echo '<div class="notice notice-info"><p>' . sprintf(__('%d pending approvals · %d active members', 'baloch-heritage'), count($pending), count($members)) . '</p></div>';
    if ($pending) {
        echo '<h2>' . __('Pending Approvals', 'baloch-heritage') . '</h2>';
        echo '<table class="wp-list-table widefat"><thead><tr><th>Name</th><th>Email</th><th>Registered</th><th>Actions</th></tr></thead><tbody>';
        foreach ($pending as $u) {
            $approve_url = wp_nonce_url(admin_url("admin-post.php?action=bh_approve_member&user_id={$u->ID}"), 'bh_approve_' . $u->ID);
            $reject_url  = wp_nonce_url(admin_url("admin-post.php?action=bh_reject_member&user_id={$u->ID}"),  'bh_reject_'  . $u->ID);
            echo "<tr><td>{$u->display_name}</td><td>{$u->user_email}</td><td>" . date('M j, Y', strtotime($u->user_registered)) . "</td>";
            echo "<td><a href='{$approve_url}' class='button button-primary'>" . __('Approve', 'baloch-heritage') . "</a> ";
            echo "<a href='{$reject_url}' class='button' onclick='return confirm(\"Reject this application?\")'>" . __('Reject', 'baloch-heritage') . "</a></td></tr>";
        }
        echo '</tbody></table>';
    } else {
        echo '<p>' . __('No pending approvals.', 'baloch-heritage') . '</p>';
    }
    echo '</div>';
}

function bh_approvals_admin_page() { bh_members_admin_page(); }

function bh_all_members_admin_page() {
    $members = get_users(['role__in'=>['bh_member','bh_moderator','bh_board','administrator']]);
    echo '<div class="wrap"><h1>' . __('All Active Members', 'baloch-heritage') . '</h1>';
    echo '<table class="wp-list-table widefat"><thead><tr><th>Name</th><th>Email</th><th>Role</th><th>Since</th></tr></thead><tbody>';
    foreach ($members as $u) {
        $role = bh_get_user_role_label($u->ID);
        echo "<tr><td><a href='" . get_edit_user_link($u->ID) . "'>{$u->display_name}</a></td><td>{$u->user_email}</td><td>{$role}</td><td>" . date('M j, Y', strtotime($u->user_registered)) . "</td></tr>";
    }
    echo '</tbody></table></div>';
}

/* ═══════════════════════════════════════════════════════
   10. SHORTCODES
   ═══════════════════════════════════════════════════════ */
// [bh_events limit="3"]
function bh_events_shortcode($atts) {
    $atts = shortcode_atts(['limit'=>6,'type'=>'','upcoming'=>'yes'], $atts);
    $query = bh_get_events(intval($atts['limit']), $atts['upcoming']==='yes');
    ob_start();
    if ($query->have_posts()): ?>
    <div class="events-row stagger">
    <?php while ($query->have_posts()): $query->the_post();
        $date = get_post_meta(get_the_ID(), '_bh_event_date', true);
        $loc  = get_post_meta(get_the_ID(), '_bh_event_location', true);
        $d    = $date ? new DateTime($date) : null; ?>
        <a href="<?php the_permalink(); ?>" class="ev-card">
            <?php if ($d): ?>
            <div class="ev-strip" style="background:var(--maroon);">
                <span class="ev-day"><?php echo $d->format('d'); ?></span>
                <span class="ev-mon"><?php echo $d->format('M Y'); ?></span>
            </div>
            <?php endif; ?>
            <div class="ev-body">
                <h3><?php the_title(); ?></h3>
                <?php if ($loc): ?><div class="ev-loc">📍 <?php echo esc_html($loc); ?></div><?php endif; ?>
                <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
                <span class="btn-sm">RSVP</span>
            </div>
        </a>
    <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif;
    return ob_get_clean();
}
add_shortcode('bh_events', 'bh_events_shortcode');

// [bh_leaders]
function bh_leaders_shortcode($atts) {
    $atts  = shortcode_atts(['limit'=>-1], $atts);
    $query = bh_get_leaders(intval($atts['limit']));
    ob_start();
    if ($query->have_posts()): ?>
    <div class="leadership-grid stagger">
    <?php while ($query->have_posts()): $query->the_post();
        $title  = get_post_meta(get_the_ID(), '_bh_leader_title', true);
        $li     = get_post_meta(get_the_ID(), '_bh_leader_linkedin', true);
        $tw     = get_post_meta(get_the_ID(), '_bh_leader_twitter', true);
        $name   = get_the_title();
        $initials = strtoupper(implode('', array_map(fn($w)=>$w[0], explode(' ', $name)))); ?>
        <div class="leader-card">
            <div class="leader-photo lp1">
                <?php if (has_post_thumbnail()): the_post_thumbnail('bh-card');
                else: ?><span class="leader-photo-placeholder"><?php echo esc_html(substr($initials,0,2)); ?></span><?php endif; ?>
                <?php if ($title): ?><span class="leader-role-badge"><?php echo esc_html(explode('&',$title)[0]??$title); ?></span><?php endif; ?>
            </div>
            <div class="leader-body">
                <h3><?php the_title(); ?></h3>
                <?php if ($title): ?><div class="title"><?php echo esc_html($title); ?></div><?php endif; ?>
                <p><?php echo wp_trim_words(get_the_excerpt(), 25); ?></p>
                <div class="leader-social">
                    <?php if ($li): ?><a href="<?php echo esc_url($li); ?>" target="_blank" rel="noopener">in</a><?php endif; ?>
                    <?php if ($tw): ?><a href="<?php echo esc_url($tw); ?>" target="_blank" rel="noopener">tw</a><?php endif; ?>
                </div>
            </div>
        </div>
    <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php endif;
    return ob_get_clean();
}
add_shortcode('bh_leaders', 'bh_leaders_shortcode');

// [bh_carpet_border]
function bh_carpet_border_shortcode() { return bh_carpet_border(); }
add_shortcode('bh_carpet_border', 'bh_carpet_border_shortcode');

/* ═══════════════════════════════════════════════════════
   11. WIDGET AREAS
   ═══════════════════════════════════════════════════════ */
function bh_widgets_init() {
    register_sidebar(['name'=>__('Footer Column 1','baloch-heritage'),'id'=>'footer-1','before_widget'=>'<div class="footer-widget">','after_widget'=>'</div>','before_title'=>'<h4>','after_title'=>'</h4>']);
    register_sidebar(['name'=>__('Footer Column 2','baloch-heritage'),'id'=>'footer-2','before_widget'=>'<div class="footer-widget">','after_widget'=>'</div>','before_title'=>'<h4>','after_title'=>'</h4>']);
    register_sidebar(['name'=>__('Sidebar','baloch-heritage'),'id'=>'sidebar-1','before_widget'=>'<div class="sidebar-card">','after_widget'=>'</div>','before_title'=>'<h4>','after_title'=>'</h4>']);
}
add_action('widgets_init', 'bh_widgets_init');

/* ═══════════════════════════════════════════════════════
   12. SECURITY & MISC
   ═══════════════════════════════════════════════════════ */
remove_action('wp_head', 'wp_generator');
add_filter('login_errors', fn() => __('Invalid credentials.', 'baloch-heritage'));

// Redirect pending members away from restricted content
function bh_check_member_access() {
    if (is_user_logged_in()) {
        $user = wp_get_current_user();
        if (in_array('pending_member', $user->roles) && !is_page(['join','members','contact'])) {
            // Allow browsing but restrict member-only content via template conditions
        }
    }
}
add_action('template_redirect', 'bh_check_member_access');
