<?php
/**
 * Elementor full-width template (header + footer kept, no sidebar/wrapper).
 */
defined('ABSPATH') || exit;
get_header(); ?>
<main class="bh-elementor-fullwidth">
  <?php while (have_posts()): the_post(); the_content(); endwhile; ?>
</main>
<?php get_footer();
