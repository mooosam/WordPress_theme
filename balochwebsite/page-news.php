<?php
/**
 * Template Name: News / Community Hub
 * Template Post Type: page
 */
get_header();
$filter_cat = sanitize_text_field($_GET['cat'] ?? '');
$search_q   = get_search_query();
?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('Community Hub','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Stories & Voices','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('Community Hub & News','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('Articles, stories, announcements, and conversations from the heart of the Baloch Canadian community.','baloch-heritage'); ?></p>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:3rem;align-items:start;">

      <!-- MAIN -->
      <div>
        <?php
        $args = [
          'post_type'      => ['post','bh_article'],
          'posts_per_page' => 8,
          'post_status'    => 'publish',
          'orderby'        => 'date',
          'order'          => 'DESC',
          'paged'          => get_query_var('paged') ?: 1,
        ];
        if ($filter_cat) $args['tax_query'] = [['taxonomy'=>'bh_article_category','field'=>'slug','terms'=>$filter_cat]];
        $news_q = new WP_Query($args);

        if ($news_q->have_posts()):
          // Featured (first post)
          $news_q->the_post();
        ?>
        <div style="display:grid;grid-template-columns:1fr 1fr;background:var(--white);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-md);margin-bottom:2rem;" class="reveal">
          <div style="min-height:260px;overflow:hidden;position:relative;">
            <?php if (has_post_thumbnail()): the_post_thumbnail('bh-card',['style'=>'width:100%;height:100%;object-fit:cover;position:absolute;inset:0;']);
            else: ?><div style="position:absolute;inset:0;background:linear-gradient(135deg,#6B1515,#A03020);"></div><?php endif; ?>
          </div>
          <div style="padding:2rem;display:flex;flex-direction:column;justify-content:center;">
            <span style="display:inline-block;background:var(--orange);color:#fff;font-size:0.65rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:3px;letter-spacing:0.1em;text-transform:uppercase;margin-bottom:0.75rem;"><?php esc_html_e('Featured Story','baloch-heritage'); ?></span>
            <div style="display:flex;align-items:center;gap:1rem;font-size:0.75rem;color:var(--text-light);margin-bottom:0.75rem;flex-wrap:wrap;">
              <span style="font-weight:700;color:var(--brown);"><?php the_author(); ?></span>
              <span><?php echo get_the_date(); ?></span>
            </div>
            <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;font-weight:700;color:var(--brown);margin-bottom:0.75rem;line-height:1.3;"><a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;"><?php the_title(); ?></a></h2>
            <p style="font-size:0.88rem;color:var(--text-light);line-height:1.7;margin-bottom:1.2rem;"><?php echo wp_trim_words(get_the_excerpt(),30); ?></p>
            <a href="<?php the_permalink(); ?>" class="btn-primary" style="align-self:flex-start;font-size:0.82rem;"><?php esc_html_e('Read Full Story','baloch-heritage'); ?></a>
          </div>
        </div>

        <!-- Category tabs -->
        <div class="tabs" style="margin-bottom:1.5rem;">
          <a href="?" class="tab-btn<?php echo !$filter_cat?' active':''; ?>" style="text-decoration:none;"><?php esc_html_e('All Stories','baloch-heritage'); ?></a>
          <?php
          $cats = get_terms(['taxonomy'=>'bh_article_category','hide_empty'=>true]);
          foreach ((array)$cats as $cat):
          ?>
          <a href="?cat=<?php echo esc_attr($cat->slug); ?>" class="tab-btn<?php echo $filter_cat===$cat->slug?' active':''; ?>" style="text-decoration:none;">
            <?php echo esc_html($cat->name); ?>
          </a>
          <?php endforeach; ?>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;" class="stagger">
          <?php while ($news_q->have_posts()): $news_q->the_post(); ?>
          <div style="background:var(--white);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);transition:transform 0.3s,box-shadow 0.3s;display:flex;flex-direction:column;" data-tilt>
            <div style="height:160px;position:relative;overflow:hidden;">
              <?php if (has_post_thumbnail()): the_post_thumbnail('bh-card',['style'=>'width:100%;height:100%;object-fit:cover;transition:transform 0.5s;']);
              else: ?><div style="position:absolute;inset:0;background:linear-gradient(135deg,#561010,#C4622D);"></div><?php endif; ?>
            </div>
            <div style="padding:1.2rem;flex:1;display:flex;flex-direction:column;">
              <div style="font-size:0.68rem;font-weight:700;color:var(--terra);letter-spacing:0.12em;text-transform:uppercase;margin-bottom:0.5rem;">
                <?php echo esc_html(get_the_category()[0]->name ?? 'Community'); ?>
              </div>
              <h3 style="font-family:'Playfair Display',serif;font-size:0.95rem;font-weight:700;color:var(--brown);margin-bottom:0.5rem;line-height:1.3;"><a href="<?php the_permalink(); ?>" style="text-decoration:none;color:inherit;"><?php the_title(); ?></a></h3>
              <p style="font-size:0.8rem;color:var(--text-light);line-height:1.6;flex:1;margin-bottom:1rem;"><?php echo wp_trim_words(get_the_excerpt(),18); ?></p>
              <a href="<?php the_permalink(); ?>" style="font-size:0.78rem;font-weight:700;color:var(--terra);text-decoration:none;"><?php esc_html_e('Read more →','baloch-heritage'); ?></a>
            </div>
          </div>
          <?php endwhile; ?>
        </div>

        <?php wp_reset_postdata();
        the_posts_pagination(['prev_text'=>'‹','next_text'=>'›','mid_size'=>2,'before_page_number'=>'<span class="page-btn">','after_page_number'=>'</span>']);
        else: ?>
        <p style="color:var(--text-light);"><?php esc_html_e('No articles found.','baloch-heritage'); ?></p>
        <?php endif; ?>
      </div>

      <!-- SIDEBAR -->
      <div style="position:sticky;top:88px;">
        <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:1.4rem;margin-bottom:1.5rem;">
          <h4 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:1rem;"><?php esc_html_e('Categories','baloch-heritage'); ?></h4>
          <?php
          $cats = get_terms(['taxonomy'=>'bh_article_category','hide_empty'=>true]);
          foreach ((array)$cats as $cat): ?>
          <div style="display:flex;justify-content:space-between;align-items:center;padding:0.5rem 0;border-bottom:1px solid var(--cream-dark);font-size:0.82rem;">
            <a href="?cat=<?php echo esc_attr($cat->slug); ?>" style="color:var(--text-light);text-decoration:none;font-weight:600;transition:color 0.2s;"><?php echo esc_html($cat->name); ?></a>
            <span style="background:var(--cream);color:var(--text-light);font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:20px;"><?php echo $cat->count; ?></span>
          </div>
          <?php endforeach; ?>
        </div>

        <?php if (is_user_logged_in() && current_user_can('bh_submit_article')): ?>
        <div style="background:var(--maroon-dark);border-radius:var(--radius);padding:1.4rem;margin-bottom:1.5rem;">
          <h4 style="font-family:'Playfair Display',serif;color:var(--cream);font-size:0.95rem;margin-bottom:0.6rem;"><?php esc_html_e('Submit a Story','baloch-heritage'); ?></h4>
          <p style="font-size:0.78rem;color:rgba(255,255,255,0.65);margin-bottom:1rem;"><?php esc_html_e('Share your story with the Baloch Heritage community.','baloch-heritage'); ?></p>
          <a href="<?php echo admin_url('post-new.php?post_type=bh_article'); ?>" class="btn-ghost" style="font-size:0.8rem;padding:0.5rem 1rem;"><?php esc_html_e('Submit Story →','baloch-heritage'); ?></a>
        </div>
        <?php endif; ?>

        <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:1.4rem;">
          <h4 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:1rem;"><?php esc_html_e('Newsletter','baloch-heritage'); ?></h4>
          <p style="font-size:0.8rem;color:var(--text-light);margin-bottom:0.75rem;"><?php esc_html_e('Get community stories and updates delivered to your inbox.','baloch-heritage'); ?></p>
          <div class="newsletter-form">
            <input type="email" id="newsNewsletterEmail" placeholder="<?php esc_attr_e('Your email','baloch-heritage'); ?>">
            <button onclick="bhNewsletterSignup('newsNewsletterEmail')"><?php esc_html_e('Join','baloch-heritage'); ?></button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
function bhNewsletterSignup(id) {
  const email = document.getElementById(id).value;
  if (!email) return;
  fetch(bhAjax.url, {method:'POST',headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_newsletter&nonce='+bhAjax.nonce+'&email='+encodeURIComponent(email)
  }).then(r=>r.json()).then(d=>showNotification(d.data.message,d.success?'success':'error'));
}
</script>

<?php get_footer(); ?>
