<?php
/**
 * Template Name: Resources & Initiatives
 * Template Post Type: page
 */
get_header();
$filter = sanitize_text_field($_GET['type'] ?? '');
?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('Resources','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Support & Growth','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('Resources & Initiatives','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('Programs, tools, and services supporting Baloch Canadians — from language learning to settlement support.','baloch-heritage'); ?></p>
  </div>
</div>

<!-- HERO CARDS -->
<section style="background:var(--maroon-dark);position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.015) 0,rgba(255,255,255,0.015) 1px,transparent 1px,transparent 28px);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div class="resources-grid stagger">
      <?php
      $types = [
        ['education','education','Education','Scholarships, tutoring, academic mentorship, and skill development programs for Balochi youth.'],
        ['language','language','Language','Balochi language preservation — classes, digital archives, dictionaries, and learning programs.'],
        ['empowerment','empowerment','Empowerment','Community support networks, professional development, and economic empowerment programs.'],
        ['social','social','Social Support','Settlement services, mental health resources, legal aid referrals, and integration programs.'],
      ];
      foreach ($types as $t): $active = $filter === $t[0]; ?>
      <a href="?type=<?php echo esc_attr($t[0]); ?>" class="resource-card" style="text-decoration:none;<?php echo $active?'background:rgba(255,255,255,0.15);':''; ?>">
        <div class="resource-icon">
          <svg viewBox="0 0 28 28" fill="none" width="28" height="28"><path d="M14 3L3 9l11 6 11-6-11-6z" stroke="#E07B39" stroke-width="1.4" stroke-linejoin="round"/><path d="M3 14l11 6 11-6" stroke="#E07B39" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round"/><path d="M3 19l11 6 11-6" stroke="#D4A574" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round" opacity="0.6"/></svg>
        </div>
        <h3><?php echo esc_html($t[2]); ?></h3>
        <p><?php echo esc_html($t[3]); ?></p>
        <span class="resource-link"><?php esc_html_e('Explore →','baloch-heritage'); ?></span>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<section style="background:var(--cream);">
  <div class="container">

    <!-- RESOURCES FROM CMS -->
    <?php
    $categories = ['education','language','empowerment','social'];
    $cat_labels = ['education'=>__('Education','baloch-heritage'),'language'=>__('Language','baloch-heritage'),'empowerment'=>__('Empowerment','baloch-heritage'),'social'=>__('Social Support','baloch-heritage')];
    $display_cats = $filter ? [$filter] : $categories;

    foreach ($display_cats as $cat):
      $res_query = bh_get_resources($cat, 6);
      if (!$res_query->have_posts()) continue;
    ?>
    <div style="margin-bottom:3rem;">
      <div class="section-header reveal">
        <span class="section-label"><?php echo esc_html($cat_labels[$cat] ?? $cat); ?></span>
        <h2 class="section-title"><?php printf(esc_html__('%s Programs & Resources','baloch-heritage'), esc_html($cat_labels[$cat] ?? $cat)); ?></h2>
      </div>
      <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:1.5rem;" class="stagger">
        <?php while ($res_query->have_posts()): $res_query->the_post();
          $status = get_post_meta(get_the_ID(),'_bh_resource_status',true);
          $link   = get_post_meta(get_the_ID(),'_bh_resource_link',true);
          $dl     = get_post_meta(get_the_ID(),'_bh_resource_deadline',true);
        ?>
        <div style="background:var(--white);border-radius:var(--radius);padding:1.4rem;box-shadow:var(--shadow-sm);display:flex;gap:1rem;transition:transform 0.3s,box-shadow 0.3s;cursor:pointer;" data-tilt>
          <div style="width:44px;height:44px;border-radius:var(--radius-sm);background:rgba(122,28,28,0.1);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1.2rem;">📋</div>
          <div>
            <h4 style="font-size:0.88rem;font-weight:700;color:var(--brown);margin-bottom:0.25rem;"><?php the_title(); ?></h4>
            <p style="font-size:0.78rem;color:var(--text-light);line-height:1.5;margin-bottom:0.5rem;"><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
            <?php if ($status): ?>
            <span style="background:rgba(76,175,80,0.1);color:#2e7d32;font-size:0.7rem;font-weight:700;padding:0.15rem 0.5rem;border-radius:20px;"><?php echo esc_html($status); ?></span>
            <?php endif; ?>
            <?php if ($link): ?>
            <a href="<?php echo esc_url($link); ?>" target="_blank" rel="noopener" style="display:block;margin-top:0.5rem;font-size:0.78rem;color:var(--terra);font-weight:700;text-decoration:none;"><?php esc_html_e('Learn More →','baloch-heritage'); ?></a>
            <?php endif; ?>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata(); ?>
      </div>
    </div>
    <?php endforeach; ?>

    <!-- PAGE CONTENT (editor content) -->
    <?php while (have_posts()): the_post(); ?>
    <?php if (get_the_content()): ?>
    <div style="background:var(--white);border-radius:var(--radius);padding:2rem;box-shadow:var(--shadow-sm);font-size:0.95rem;line-height:1.8;">
      <?php the_content(); ?>
    </div>
    <?php endif; endwhile; ?>

  </div>
</section>

<?php get_footer(); ?>
