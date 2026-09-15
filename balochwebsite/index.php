<?php
/**
 * Baloch Heritage — index.php
 * Fallback template / Blog archive
 */
get_header(); ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <span class="section-label"><?php esc_html_e('Latest', 'baloch-heritage'); ?></span>
    <h1><?php
      if (is_home() && !is_front_page()) single_post_title();
      elseif (is_archive()) the_archive_title();
      elseif (is_search()) printf(esc_html__('Search: %s', 'baloch-heritage'), get_search_query());
      else esc_html_e('Latest News', 'baloch-heritage');
    ?></h1>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:3rem;align-items:start;">
      <div>
        <?php if (have_posts()): ?>
        <div class="news-grid stagger" style="grid-template-columns:1fr 1fr;">
          <?php while (have_posts()): the_post(); ?>
          <div class="news-card" style="background:var(--white);">
            <?php if (has_post_thumbnail()): ?>
            <div class="news-img" style="height:180px;overflow:hidden;position:relative;">
              <?php the_post_thumbnail('bh-card', ['style'=>'width:100%;height:100%;object-fit:cover;']); ?>
            </div>
            <?php endif; ?>
            <div class="news-body" style="padding:1.2rem;">
              <div class="news-tag"><?php echo esc_html(get_the_category()[0]->name ?? 'News'); ?></div>
              <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:0.5rem;"><a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;"><?php the_title(); ?></a></h3>
              <p style="font-size:0.82rem;color:var(--text-light);line-height:1.6;margin-bottom:0.75rem;"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
              <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read more →', 'baloch-heritage'); ?></a>
            </div>
          </div>
          <?php endwhile; ?>
        </div>
        <div class="pagination"><?php the_posts_pagination(['prev_text'=>'‹','next_text'=>'›','mid_size'=>2]); ?></div>
        <?php else: ?>
        <p><?php esc_html_e('No posts found.', 'baloch-heritage'); ?></p>
        <?php endif; ?>
      </div>
      <!-- SIDEBAR -->
      <aside>
        <?php if (is_active_sidebar('sidebar-1')): dynamic_sidebar('sidebar-1');
        else: ?>
        <div class="sidebar-card" style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:1.4rem;margin-bottom:1.5rem;">
          <h4 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:1rem;"><?php esc_html_e('Quick Links', 'baloch-heritage'); ?></h4>
          <ul style="list-style:none;">
            <?php foreach(['Culture'=>'/culture/','History'=>'/history/','Events'=>'/events/','Resources'=>'/resources/','Join Us'=>'/join/'] as $l=>$u): ?>
            <li style="padding:0.4rem 0;border-bottom:1px solid var(--cream-dark);"><a href="<?php echo esc_url(home_url($u)); ?>" style="color:var(--text-light);text-decoration:none;font-size:0.85rem;"><?php echo esc_html($l); ?></a></li>
            <?php endforeach; ?>
          </ul>
        </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>

<?php get_footer(); ?>
