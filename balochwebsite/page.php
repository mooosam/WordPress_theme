<?php
/**
 * Baloch Heritage — page.php
 * Default page template
 */
get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <?php
    $parent = wp_get_post_parent_id(get_the_ID());
    if ($parent):
      echo '<div class="breadcrumb"><a href="' . home_url('/') . '">' . esc_html__('Home','baloch-heritage') . '</a><span>/</span><a href="' . get_permalink($parent) . '">' . esc_html(get_the_title($parent)) . '</a><span>/</span><span class="current">' . esc_html(get_the_title()) . '</span></div>';
    else:
      echo '<div class="breadcrumb"><a href="' . home_url('/') . '">' . esc_html__('Home','baloch-heritage') . '</a><span>/</span><span class="current">' . esc_html(get_the_title()) . '</span></div>';
    endif;
    ?>
    <h1><?php the_title(); ?></h1>
    <?php if (has_excerpt()): ?><p><?php the_excerpt(); ?></p><?php endif; ?>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <div style="background:var(--white);border-radius:var(--radius);padding:2.5rem;box-shadow:var(--shadow-sm);max-width:800px;margin:0 auto;">
      <?php if (has_post_thumbnail()): ?>
      <div style="border-radius:var(--radius);overflow:hidden;margin-bottom:2rem;">
        <?php the_post_thumbnail('bh-hero', ['style'=>'width:100%;height:auto;']); ?>
      </div>
      <?php endif; ?>
      <div style="font-size:0.95rem;line-height:1.8;color:var(--text);"><?php the_content(); ?></div>
    </div>
  </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
