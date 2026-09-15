<?php
/**
 * Baloch Heritage — single.php
 * Single post / article template
 */
get_header(); ?>

<?php while (have_posts()): the_post(); ?>

<div class="page-hero" style="min-height:280px;">
  <div class="container page-hero-content">
    <div class="breadcrumb">
      <a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a>
      <span>/</span>
      <a href="<?php echo home_url('/news/'); ?>"><?php esc_html_e('Community','baloch-heritage'); ?></a>
      <span>/</span>
      <span class="current"><?php the_title(); ?></span>
    </div>
    <span class="section-label"><?php echo esc_html(get_the_category()[0]->name ?? 'Article'); ?></span>
    <h1 style="font-size:clamp(1.6rem,4vw,2.8rem);"><?php the_title(); ?></h1>
    <div style="display:flex;align-items:center;gap:1rem;margin-top:0.75rem;flex-wrap:wrap;">
      <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.82rem;color:rgba(255,255,255,0.65);">
        <?php echo get_avatar(get_the_author_meta('ID'), 28, '', '', ['class'=>'','style'=>'border-radius:50%;']); ?>
        <?php the_author(); ?>
      </div>
      <span style="font-size:0.78rem;color:rgba(255,255,255,0.5);"><?php echo get_the_date(); ?></span>
      <span style="font-size:0.78rem;color:rgba(255,255,255,0.5);"><?php printf(esc_html__('%s min read','baloch-heritage'), ceil(str_word_count(get_the_content())/200)); ?></span>
    </div>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 280px;gap:3rem;align-items:start;">
      <article>
        <?php if (has_post_thumbnail()): ?>
        <div style="border-radius:var(--radius);overflow:hidden;margin-bottom:2rem;box-shadow:var(--shadow-md);">
          <?php the_post_thumbnail('bh-hero', ['style'=>'width:100%;height:auto;display:block;']); ?>
        </div>
        <?php endif; ?>
        <div style="background:var(--white);border-radius:var(--radius);padding:2rem;box-shadow:var(--shadow-sm);font-size:0.95rem;line-height:1.8;color:var(--text);font-family:'Lato',sans-serif;">
          <?php the_content(); ?>
        </div>
        <div style="margin-top:1.5rem;padding-top:1.5rem;border-top:1px solid var(--cream-dark);display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
          <div style="font-size:0.82rem;color:var(--text-light);"><?php the_tags('<span>Tags: </span>','', ''); ?></div>
          <div style="display:flex;gap:0.75rem;">
            <?php previous_post_link('<a href="%link" class="btn-outline" style="font-size:0.8rem;padding:0.4rem 1rem;">← %title</a>'); ?>
            <?php next_post_link('<a href="%link" class="btn-outline" style="font-size:0.8rem;padding:0.4rem 1rem;">%title →</a>'); ?>
          </div>
        </div>
        <?php if (comments_open()): comments_template(); endif; ?>
      </article>
      <!-- Sidebar -->
      <aside style="position:sticky;top:88px;">
        <?php if (is_active_sidebar('sidebar-1')): dynamic_sidebar('sidebar-1');
        else: ?>
        <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:1.4rem;margin-bottom:1.5rem;">
          <h4 style="font-family:'Playfair Display',serif;color:var(--brown);font-size:1rem;margin-bottom:1rem;"><?php esc_html_e('About the Author','baloch-heritage'); ?></h4>
          <div style="display:flex;align-items:center;gap:0.75rem;margin-bottom:0.75rem;">
            <?php echo get_avatar(get_the_author_meta('ID'), 48, '', '', ['style'=>'border-radius:50%;']); ?>
            <div><div style="font-weight:700;font-size:0.88rem;color:var(--brown);"><?php the_author(); ?></div><div style="font-size:0.75rem;color:var(--text-light);"><?php the_author_meta('description') ? the_author_meta('description') : esc_html_e('Community Contributor','baloch-heritage'); ?></div></div>
          </div>
        </div>
        <div style="background:var(--maroon-dark);border-radius:var(--radius);padding:1.4rem;">
          <h4 style="font-family:'Playfair Display',serif;color:var(--cream);font-size:0.95rem;margin-bottom:0.75rem;"><?php esc_html_e('Join Our Community','baloch-heritage'); ?></h4>
          <p style="font-size:0.78rem;color:rgba(255,255,255,0.65);margin-bottom:1rem;"><?php esc_html_e('Become a member and get access to events, forums, and more.','baloch-heritage'); ?></p>
          <a href="<?php echo home_url('/join/'); ?>" class="btn-primary" style="display:block;text-align:center;font-size:0.82rem;"><?php esc_html_e('Apply Now','baloch-heritage'); ?></a>
        </div>
        <?php endif; ?>
      </aside>
    </div>
  </div>
</section>

<?php endwhile; ?>
<?php get_footer(); ?>
