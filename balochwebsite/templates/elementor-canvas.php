<?php
/**
 * Elementor canvas template (no header/footer).
 */
defined('ABSPATH') || exit;

if (!is_user_logged_in()) {
    $bh_opts = get_option('bh_theme_options');
    if (is_array($bh_opts) && !empty($bh_opts['maintenance_mode'])) {
        wp_die(esc_html__('Back soon.', 'baloch-heritage'));
    }
}
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class('bh-elementor-canvas'); ?>>
<?php wp_body_open(); ?>
<?php while (have_posts()): the_post(); the_content(); endwhile; ?>
<?php wp_footer(); ?>
</body>
</html>
