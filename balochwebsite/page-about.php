<?php
/**
 * Template Name: About Us & Leadership
 * Template Post Type: page
 */
get_header(); ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('About Us','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Our Story','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('About Baloch Heritage Canada','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('A community rooted in pride, unity, and the enduring spirit of the Baloch people — from Balochistan to the heart of Canada.','baloch-heritage'); ?></p>
  </div>
</div>

<!-- MISSION -->
<section style="background:var(--white);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1fr;gap:4rem;align-items:center;">
      <div class="reveal-left">
        <span class="section-label"><?php esc_html_e('Our Mission','baloch-heritage'); ?></span>
        <h2 class="section-title"><?php esc_html_e('Preserving Heritage, Building Futures','baloch-heritage'); ?></h2>
        <?php while (have_posts()): the_post(); ?>
        <?php if (get_the_content()): ?>
        <div style="color:var(--text-light);line-height:1.75;font-size:0.95rem;"><?php the_content(); ?></div>
        <?php else: ?>
        <p style="color:var(--text-light);line-height:1.75;margin-bottom:1rem;font-size:0.95rem;"><?php esc_html_e('The Baloch Cultural Society of Canada was founded to unite the Baloch diaspora, celebrate our ancient culture, and ensure that our language, traditions, and values thrive for generations to come.','baloch-heritage'); ?></p>
        <p style="color:var(--text-light);line-height:1.75;margin-bottom:1.5rem;font-size:0.95rem;"><?php esc_html_e('We believe that cultural identity is a foundation for community strength — connecting families, empowering youth, and advocating for Balochi voices in Canadian public life.','baloch-heritage'); ?></p>
        <?php endif; endwhile; ?>
        <ul style="list-style:none;display:flex;flex-direction:column;gap:1rem;margin-top:1.5rem;">
          <?php foreach([
            ['Cultural Preservation','Safeguarding Balochi language, arts, and traditions for future generations born in Canada.'],
            ['Community Unity','Connecting Baloch families across all Canadian provinces — from Toronto to Vancouver.'],
            ['Advocacy & Representation','Amplifying Balochi voices in Canadian civic life.'],
            ['Youth Empowerment','Nurturing the next generation of proud Balochi Canadians.'],
          ] as $v): ?>
          <li style="display:flex;align-items:flex-start;gap:0.9rem;padding:1rem 1.2rem;background:var(--cream);border-radius:var(--radius-sm);border-left:3px solid var(--orange);">
            <div style="width:36px;height:36px;background:rgba(224,123,57,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;">✓</div>
            <div><h4 style="font-size:0.88rem;font-weight:700;color:var(--brown);margin-bottom:0.2rem;"><?php echo esc_html($v[0]); ?></h4><p style="font-size:0.78rem;color:var(--text-light);line-height:1.5;margin:0;"><?php echo esc_html($v[1]); ?></p></div>
          </li>
          <?php endforeach; ?>
        </ul>
      </div>
      <div class="reveal-right" style="border-radius:var(--radius);overflow:hidden;aspect-ratio:4/3;box-shadow:var(--shadow-lg);position:relative;background:linear-gradient(135deg,#6B2A10,#C4622D);">
        <?php
        $about_img_id = get_post_thumbnail_id();
        if ($about_img_id) echo wp_get_attachment_image($about_img_id, 'bh-card', false, ['style'=>'width:100%;height:100%;object-fit:cover;']);
        else echo '<div style="position:absolute;inset:0;background:repeating-linear-gradient(-45deg,transparent 0,transparent 10px,rgba(255,255,255,0.04) 10px,rgba(255,255,255,0.04) 11px);"></div>';
        ?>
      </div>
    </div>
  </div>
</section>

<!-- MILESTONES -->
<div style="background:var(--maroon-dark);padding:4rem 0;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.018) 0,rgba(255,255,255,0.018) 1px,transparent 1px,transparent 28px);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div class="section-header center reveal" style="margin-bottom:3rem;">
      <span class="section-label" style="color:var(--sand);"><?php esc_html_e('Our Journey','baloch-heritage'); ?></span>
      <h2 class="section-title" style="color:var(--cream);"><?php esc_html_e('Key Milestones','baloch-heritage'); ?></h2>
    </div>
    <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:2rem;" class="stagger">
      <?php foreach([['1990','Society Founded in Toronto by pioneering Baloch families.'],['2002','First Annual Cultural Festival with 500+ attendees.'],['2015','Balochi Language Program launched with York University.'],['2026','5,000+ members across 12 Canadian cities.']] as $m): ?>
      <div style="text-align:center;padding:1.5rem 1rem;">
        <div style="font-family:'Playfair Display',serif;font-size:2rem;font-weight:900;color:var(--orange);margin-bottom:0.4rem;"><?php echo esc_html($m[0]); ?></div>
        <div style="font-size:0.82rem;color:rgba(255,255,255,0.7);line-height:1.55;"><?php echo esc_html($m[1]); ?></div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<!-- LEADERSHIP -->
<section style="background:var(--cream);">
  <div class="container">
    <div class="section-header center reveal">
      <span class="section-label"><?php esc_html_e('Our Team','baloch-heritage'); ?></span>
      <h2 class="section-title"><?php esc_html_e('Board & Leadership','baloch-heritage'); ?></h2>
      <p class="section-subtitle"><?php esc_html_e('Dedicated Baloch Canadians committed to serving our community with integrity, passion, and cultural pride.','baloch-heritage'); ?></p>
    </div>
    <?php echo do_shortcode('[bh_leaders]'); ?>
    <?php if (!bh_get_leaders()->have_posts()): ?>
    <!-- Fallback leadership grid -->
    <div style="display:grid;grid-template-columns:repeat(3,1fr);gap:2rem;" class="stagger">
      <?php foreach([
        ['AB','Ahmad Baloch','President & Founder','A community organizer with over 30 years of experience. Ahmad founded the society with a vision of a unified, proud Baloch diaspora in Canada.','lp1'],
        ['FM','Fareeda Mengal','Vice President & Arts Director','Master textile artist and cultural advocate. Fareeda leads our arts programming and oversees the annual Cultural Festival.','lp2'],
        ['ZR','Zara Rind','Youth & Outreach Director','Dynamic youth advocate. Zara leads youth programs, mentorship initiatives, and university outreach.','lp3'],
        ['HM','Hassan Marri','Treasurer & Finance Lead','Chartered accountant ensuring transparent financial management and strategic resource allocation.','lp4'],
        ['NK','Nadia Khan','Secretary & Communications','Communications professional managing our digital presence, newsletter, and community outreach.','lp5'],
        ['SB','Saleem Bugti','Cultural Programs Director','Historian and linguist overseeing language preservation, library programs, and cultural education.','lp6'],
      ] as $l): ?>
      <div style="background:var(--white);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);text-align:center;transition:transform 0.3s,box-shadow 0.3s;" data-tilt>
        <div style="height:180px;background:linear-gradient(135deg,#561010,#8B2020);display:flex;align-items:center;justify-content:center;position:relative;">
          <span style="font-family:'Playfair Display',serif;font-size:3rem;font-weight:900;color:rgba(255,255,255,0.4);position:relative;z-index:1;"><?php echo esc_html($l[0]); ?></span>
          <span style="position:absolute;bottom:0.75rem;left:50%;transform:translateX(-50%);background:var(--orange);color:#fff;font-size:0.68rem;font-weight:700;padding:0.2rem 0.75rem;border-radius:20px;letter-spacing:0.08em;text-transform:uppercase;white-space:nowrap;"><?php echo esc_html(explode('&',$l[2])[0]); ?></span>
        </div>
        <div style="padding:1.4rem;">
          <h3 style="font-family:'Playfair Display',serif;font-size:1.05rem;font-weight:700;color:var(--brown);margin-bottom:0.2rem;"><?php echo esc_html($l[1]); ?></h3>
          <div style="font-size:0.78rem;font-weight:700;color:var(--terra);letter-spacing:0.06em;text-transform:uppercase;margin-bottom:0.6rem;"><?php echo esc_html($l[2]); ?></div>
          <p style="font-size:0.8rem;color:var(--text-light);line-height:1.6;"><?php echo esc_html($l[3]); ?></p>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>
    <div style="text-align:center;margin-top:3rem;" class="reveal">
      <a href="<?php echo home_url('/join/'); ?>" class="btn-primary"><?php esc_html_e('Join Our Community','baloch-heritage'); ?></a>
      <a href="<?php echo home_url('/contact/'); ?>" class="btn-outline" style="margin-left:1rem;"><?php esc_html_e('Get in Touch','baloch-heritage'); ?></a>
    </div>
  </div>
</section>

<?php get_footer(); ?>
