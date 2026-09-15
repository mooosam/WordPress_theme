<?php
/**
 * Baloch Heritage — front-page.php
 * Homepage template
 *
 * Each top-level section respects the on/off toggle in
 * Baloch Heritage → Homepage Builder. Disabled sections are skipped.
 */

$demo_data = [];
$demo_file = get_template_directory() . '/demo-content/demo-data.json';
if (file_exists($demo_file)) {
    $demo_data = json_decode(file_get_contents($demo_file), true)['demoData'] ?? [];
}

get_header(); ?>

<?php if (bh_section_enabled('hero')): ?>
<!-- HERO -->
<?php 
$hero = $demo_data['heroSection'] ?? [];
$hero_title = !empty($hero['title']) ? $hero['title'] : get_theme_mod('bh_hero_title', 'Celebrating <em>Baloch Heritage</em> &amp; Community');
$hero_sub = !empty($hero['subtitle']) ? $hero['subtitle'] : get_theme_mod('bh_hero_sub', 'Explore our rich culture, history, and vibrant traditions. Connecting the Baloch diaspora across Canada while preserving our ancient heritage for future generations.');
$hero_btns = !empty($hero['buttons']) ? $hero['buttons'] : [
  ['url' => '/about/', 'style' => 'primary', 'text' => 'Discover Our Story'],
  ['url' => '/culture/', 'style' => 'ghost', 'text' => 'Explore Culture']
];
$hero_img = !empty($hero['image'])
  ? get_template_directory_uri() . '/demo-content/' . $hero['image']
  : get_template_directory_uri() . '/assets/images/community-hero.webp';
?>
<section class="hero-panorama-wrapper" id="hero-panorama-wrapper" style="height: 300vh; position: relative;">
  <div class="sticky-hero" style="position: sticky; top: 0; height: 100vh; overflow: hidden; display: flex; align-items: center;">
    <?php if ($hero_img): ?>
    <div class="panorama-bg" id="panoramaBg" style="position: absolute; top: 0; left: 0; width: 300vw; height: 100%; background: url('<?php echo esc_url($hero_img); ?>') left center / cover no-repeat; transform: translateX(0); z-index: 1;"></div>
    <?php else: ?>
    <div class="hero-bg" style="z-index: 1;"></div>
    <?php endif; ?>
    
    <div class="hero-overlay" style="position: absolute; inset: 0; background: linear-gradient(to right, rgba(0,0,0,0.8) 0%, rgba(0,0,0,0.3) 100%); z-index: 2;"></div>

    <div class="hero-shapes" style="z-index: 3;">
      <div class="hero-circle hero-c1" data-parallax="0.15"></div>
      <div class="hero-circle hero-c2" data-parallax="0.08"></div>
    </div>
    
    <div class="hero-content" style="position: relative; z-index: 10; margin-left: 5%; max-width: 600px; padding: 2rem;">
      <div class="hero-eyebrow" style="color: var(--sand);"><?php esc_html_e('Baloch Cultural Society of Canada', 'baloch-heritage'); ?></div>
      <h1 style="color: #fff; text-shadow: 0 2px 4px rgba(0,0,0,0.5);"><?php echo wp_kses_post($hero_title); ?></h1>
      <p style="color: #eee; font-size: 1.1rem; line-height: 1.6; text-shadow: 0 1px 2px rgba(0,0,0,0.5);"><?php echo esc_html($hero_sub); ?></p>
      <div class="hero-btns" style="margin-top: 2rem; display: flex; gap: 1rem;">
        <?php foreach ($hero_btns as $btn): 
            $btn_class = (isset($btn['style']) && $btn['style'] === 'primary') ? 'btn-primary' : 'btn-ghost';
        ?>
        <a href="<?php echo esc_url(home_url($btn['url'])); ?>" class="<?php echo esc_attr($btn_class); ?>"><?php echo esc_html($btn['text']); ?></a>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="hero-scroll-indicator" style="z-index: 10;">
      <div class="scroll-dot"></div>
      <span><?php esc_html_e('Scroll to explore', 'baloch-heritage'); ?></span>
    </div>
  </div>
</section>
<script>
  window.addEventListener('scroll', function() {
    var wrapper = document.getElementById('hero-panorama-wrapper');
    var bg = document.getElementById('panoramaBg');
    if (!wrapper || !bg) return;
    
    var rect = wrapper.getBoundingClientRect();
    var maxScroll = rect.height - window.innerHeight;
    var progress = Math.max(0, Math.min(1, -rect.top / maxScroll));
    
    bg.style.transform = 'translateX(' + (-progress * 200) + 'vw)';
  });
</script>

<?php echo bh_carpet_border('#561010'); ?>

<?php endif; // hero ?>

<?php if (bh_section_enabled('stats')): ?>
<!-- STATS BAND -->
<div class="stat-band">
  <div class="container">
    <div class="stat-grid stagger">
      <?php 
      $stats = !empty($demo_data['statsBand']) ? $demo_data['statsBand'] : null;
      if ($stats):
          foreach ($stats as $stat): 
            $val = preg_replace('/[^0-9]/', '', $stat['value']);
            $suffix = preg_replace('/[0-9,]/', '', $stat['value']);
      ?>
      <div class="stat-item"><h3><span class="count-up" data-target="<?php echo esc_attr($val); ?>">0</span><span class="sa"><?php echo esc_html($suffix); ?></span></h3><p><?php echo esc_html($stat['label']); ?></p></div>
      <?php endforeach; else: ?>
      <div class="stat-item"><h3><span class="count-up" data-target="<?php echo esc_attr(get_theme_mod('bh_stat_members','5000')); ?>">0</span><span class="sa">+</span></h3><p><?php esc_html_e('Community Members', 'baloch-heritage'); ?></p></div>
      <div class="stat-item"><h3><span class="count-up" data-target="<?php echo esc_attr(get_theme_mod('bh_stat_cities','12')); ?>">0</span></h3><p><?php esc_html_e('Cities Across Canada', 'baloch-heritage'); ?></p></div>
      <div class="stat-item"><h3><span class="count-up" data-target="<?php echo esc_attr(get_theme_mod('bh_stat_events','25')); ?>">0</span><span class="sa">+</span></h3><p><?php esc_html_e('Annual Events', 'baloch-heritage'); ?></p></div>
      <div class="stat-item"><h3><span class="count-up" data-target="<?php echo esc_attr(get_theme_mod('bh_stat_year','1990')); ?>">0</span></h3><p><?php esc_html_e('Est. Year', 'baloch-heritage'); ?></p></div>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php endif; // stats ?>

<?php if (bh_section_enabled('about')): ?>
<!-- WHO WE ARE -->
<section id="about" style="background:var(--white);">
  <div class="container">
    <div class="about-grid">
      <?php $about_page = get_page_by_path('about'); ?>
      <div class="about-img reveal-left">
        <div class="about-img-inner">
          <?php if ($about_page && has_post_thumbnail($about_page->ID)):
            echo get_the_post_thumbnail($about_page->ID, 'bh-card');
          else: ?>
            <img src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/community-hero.webp'); ?>" alt="<?php esc_attr_e('Baloch Canadian community gathering', 'baloch-heritage'); ?>" loading="lazy">
          <?php endif; ?>
        </div>
      </div>
      <?php 
      $about = $demo_data['aboutSection'] ?? [];
      $about_title = !empty($about['title']) ? $about['title'] : 'A Diverse Community Rooted in Heritage';
      $about_content = !empty($about['content']) ? $about['content'] : get_theme_mod('bh_about_text1', 'The Baloch Cultural Society of Canada brings together thousands of Balochi Canadians from coast to coast. We celebrate our ancient culture, preserve our language, and empower our community through education, events, and solidarity.') . "\n" . get_theme_mod('bh_about_text2', 'Our community goals and values include youth development, cultural preservation, professional networking, and advocating for Balochi voices in Canadian public life.');
      ?>
      <div class="about-content reveal-right">
        <span class="section-label"><?php esc_html_e('Who We Are', 'baloch-heritage'); ?></span>
        <h2 class="section-title" style="font-size:clamp(1.5rem,3vw,2rem);"><?php echo esc_html($about_title); ?></h2>
        <?php 
        $paragraphs = explode("\n", $about_content);
        foreach ($paragraphs as $p):
          if(trim($p)) echo '<p>'.esc_html(trim($p)).'</p>';
        endforeach;
        ?>
        <div class="about-values">
          <?php 
          if (!empty($about['values'])):
            foreach ($about['values'] as $val): ?>
            <div class="about-value"><h4><?php echo esc_html($val); ?></h4></div>
          <?php endforeach; else: ?>
          <div class="about-value"><h4><?php esc_html_e('Cultural Preservation', 'baloch-heritage'); ?></h4><p><?php esc_html_e('Safeguarding Balochi language, arts, and traditions.', 'baloch-heritage'); ?></p></div>
          <div class="about-value"><h4><?php esc_html_e('Community Unity', 'baloch-heritage'); ?></h4><p><?php esc_html_e('Connecting Baloch families across all provinces.', 'baloch-heritage'); ?></p></div>
          <div class="about-value"><h4><?php esc_html_e('Education & Outreach', 'baloch-heritage'); ?></h4><p><?php esc_html_e('Sharing Balochi heritage with multicultural Canada.', 'baloch-heritage'); ?></p></div>
          <div class="about-value"><h4><?php esc_html_e('Youth Empowerment', 'baloch-heritage'); ?></h4><p><?php esc_html_e('Nurturing proud Balochi Canadian identity.', 'baloch-heritage'); ?></p></div>
          <?php endif; ?>
        </div>
        <div style="margin-top:1.5rem;display:flex;gap:1rem;flex-wrap:wrap;">
          <?php if (!empty($about['callToAction'])): ?>
          <a href="<?php echo esc_url(home_url($about['callToAction']['url'])); ?>" class="btn-primary"><?php echo esc_html($about['callToAction']['text']); ?></a>
          <?php else: ?>
          <a href="<?php echo esc_url(home_url('/about/')); ?>" class="btn-primary"><?php esc_html_e('Our Story', 'baloch-heritage'); ?></a>
          <a href="<?php echo esc_url(home_url('/join/')); ?>" class="btn-outline"><?php esc_html_e('Join Us', 'baloch-heritage'); ?></a>
          <?php endif; ?>
        </div>
      </div>
    </div>
  </div>
</section>

<?php echo bh_carpet_border('#561010'); ?>

<?php endif; // about ?>

<?php if (bh_section_enabled('culture')): ?>
<!-- CULTURE CARDS -->
<section id="culture" style="background:var(--cream);">
  <div class="container">
    <div class="section-header center reveal">
      <span class="section-label"><?php esc_html_e('Our Living Heritage', 'baloch-heritage'); ?></span>
      <h2 class="section-title"><?php esc_html_e('Culture & Traditions', 'baloch-heritage'); ?></h2>
      <p class="section-subtitle"><?php esc_html_e('Explore our rich culture, history, and vibrant traditions passed down through generations of Balochi people.', 'baloch-heritage'); ?></p>
    </div>
    <div class="culture-grid stagger">
      <?php
      $culture_items = !empty($demo_data['cultureCards']) ? $demo_data['cultureCards'] : [
        ['title'=>'Textiles & Doch','description'=>'Balochi embroidery (Doch) features intricate geometric patterns — a living tradition shared across generations.','url'=>home_url('/culture/'),'color'=>'cc1','theme_image'=>'doch-textiles.webp'],
        ['title'=>'Cuisine & Hospitality','description'=>'Sajji, Dampukht, Kaak bread — Balochi cuisine reflects a culture where hospitality (Melmastia) is sacred.','url'=>home_url('/culture/'),'color'=>'cc2'],
        ['title'=>'Music & Dance','description'=>'Damburag strings, Soroz melodies, and the powerful Lewa folk dance — the living sound of Baloch culture.','url'=>home_url('/gallery/'),'color'=>'cc3','theme_image'=>'balochi-music.webp'],
      ];
      $colors = ['cc1', 'cc2', 'cc3'];
      foreach ($culture_items as $i => $item): 
        $color = $item['color'] ?? $colors[$i % 3];
        $url = $item['url'] ?? home_url('/culture/');
        $img = !empty($item['image'])
          ? get_template_directory_uri() . '/demo-content/' . $item['image']
          : (!empty($item['theme_image']) ? get_template_directory_uri() . '/assets/images/' . $item['theme_image'] : '');
      ?>
      <a href="<?php echo esc_url($url); ?>" class="culture-card <?php echo esc_attr($color); ?>" style="text-decoration:none;display:block;">
        <div class="culture-card-img" <?php if($img) echo 'style="background: url(\''.esc_url($img).'\') center/cover no-repeat;"'; ?>>
          <div class="culture-card-img-inner"><?php if(!$img) echo esc_html($item['title']); ?></div>
        </div>
        <div class="culture-card-body">
          <h3><?php echo esc_html($item['title']); ?></h3>
          <p><?php echo esc_html($item['description'] ?? ''); ?></p>
          <span class="learn-more"><?php esc_html_e('Learn More →', 'baloch-heritage'); ?></span>
        </div>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</section>

<?php endif; // culture ?>

<?php if (bh_section_enabled('history')): ?>
<!-- HISTORY TIMELINE -->
<section id="history" style="background:var(--maroon-dark);position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.018) 0,rgba(255,255,255,0.018) 1px,transparent 1px,transparent 28px),repeating-linear-gradient(-45deg,rgba(255,255,255,0.018) 0,rgba(255,255,255,0.018) 1px,transparent 1px,transparent 28px);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div class="section-header center reveal" style="margin-bottom:2rem;">
      <span class="section-label" style="color:var(--sand);"><?php esc_html_e('Our Roots', 'baloch-heritage'); ?></span>
      <h2 class="section-title" style="color:var(--cream);"><?php esc_html_e('History & Heritage', 'baloch-heritage'); ?></h2>
      <p class="section-subtitle" style="color:rgba(255,255,255,0.6);"><?php esc_html_e('A journey through the rich history of the Baloch people — from ancient civilizations to the vibrant diaspora in Canada today.', 'baloch-heritage'); ?></p>
    </div>
    <div class="timeline" id="timeline">
      <div class="timeline-line"><div class="timeline-line-fill" id="timelineFill"></div></div>
      <?php
      $tl_items = !empty($demo_data['historyTimeline']) ? $demo_data['historyTimeline'] : [
        ['side'=>'left', 'era'=>'Ancient Era', 'year'=>'7000 BCE', 'title'=>'Mehrgarh Civilization', 'desc'=>'One of the world\'s earliest farming settlements in Balochistan — a cradle of South Asian civilization.'],
        ['side'=>'right','era'=>'Tribal Confederation','year'=>'3rd c.','title'=>'Baloch Tribal Identity','desc'=>'The Baloch emerge with a distinct language, tribal confederation, and Balochiyat — a code of honor, pride, and hospitality.'],
        ['side'=>'left', 'era'=>'Medieval Period','year'=>'1666','title'=>'Khanate of Kalat Founded','desc'=>'Mir Ahmad Khan I establishes a powerful Baloch state spanning much of modern Balochistan.'],
        ['side'=>'right','era'=>'Diaspora','year'=>'1980s','title'=>'Baloch Community in Canada','desc'=>'Baloch families settle across Toronto, Vancouver, and Calgary — carrying their heritage across oceans.'],
        ['side'=>'left', 'era'=>'Organization','year'=>'1990','title'=>'Society Founded','desc'=>'The Baloch Cultural Society of Canada is formally established in Toronto.'],
        ['side'=>'right','era'=>'Today','year'=>'2026','title'=>'5,000+ Strong','desc'=>'A thriving diaspora across 12 cities — celebrating culture, empowering youth, and honouring history.'],
      ];
      foreach ($tl_items as $idx => $item):
        $side = $item['side'] ?? ($idx % 2 === 0 ? 'left' : 'right');
        $is_left = $side === 'left';
        $era = $item['era'] ?? $item['year'];
      ?>
      <div class="timeline-item">
        <?php if ($is_left): ?>
        <div class="timeline-content tl-left tl-left-side">
          <span class="tl-tag"><?php echo esc_html($era); ?></span>
          <h3><?php echo esc_html($item['event'] ?? $item['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($item['description'] ?? $item['desc'] ?? ''); ?></p>
        </div>
        <?php else: ?><div class="tl-empty"></div><?php endif; ?>
        <div class="timeline-dot">
          <div class="timeline-dot-inner">
            <div class="timeline-year" style="top:-1.6rem;left:50%;transform:translateX(-50%);text-align:center;"><?php echo esc_html($item['year']); ?></div>
          </div>
        </div>
        <?php if (!$is_left): ?>
        <div class="timeline-content tl-right">
          <span class="tl-tag"><?php echo esc_html($era); ?></span>
          <h3><?php echo esc_html($item['event'] ?? $item['title'] ?? ''); ?></h3>
          <p><?php echo esc_html($item['description'] ?? $item['desc'] ?? ''); ?></p>
        </div>
        <?php else: ?><div class="tl-empty"></div><?php endif; ?>
      </div>
      <?php endforeach; ?>
    </div>
    <div style="text-align:center;padding-bottom:3rem;position:relative;z-index:1;" class="reveal">
      <a href="<?php echo esc_url(home_url('/history/')); ?>" class="btn-ghost"><?php esc_html_e('Full History →', 'baloch-heritage'); ?></a>
    </div>
  </div>
</section>

<?php echo bh_carpet_border('#561010'); ?>

<?php endif; // history ?>

<?php if (bh_section_enabled('resources')): ?>
<!-- RESOURCES -->
<section id="resources" style="background:var(--maroon-dark);position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.015) 0,rgba(255,255,255,0.015) 1px,transparent 1px,transparent 28px);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <div class="section-header center reveal">
      <span class="section-label" style="color:var(--sand);"><?php esc_html_e('Support & Growth', 'baloch-heritage'); ?></span>
      <h2 class="section-title" style="color:var(--cream);"><?php esc_html_e('Resources & Initiatives', 'baloch-heritage'); ?></h2>
      <p class="section-subtitle" style="color:rgba(255,255,255,0.6);"><?php esc_html_e('Programs and services supporting the Baloch community across Canada.', 'baloch-heritage'); ?></p>
    </div>
    <div class="resources-grid stagger">
      <?php
      $res_query = bh_get_resources('', 4);
      if ($res_query->have_posts()):
        while ($res_query->have_posts()): $res_query->the_post();
          $type = get_post_meta(get_the_ID(), '_bh_resource_type', true);
      ?>
      <a href="<?php the_permalink(); ?>" class="resource-card">
        <div class="resource-icon">
          <svg viewBox="0 0 28 28" fill="none" width="28" height="28"><path d="M14 3L3 9l11 6 11-6-11-6z" stroke="#E07B39" stroke-width="1.4" stroke-linejoin="round"/><path d="M3 14l11 6 11-6" stroke="#E07B39" stroke-width="1.4" stroke-linecap="round"/></svg>
        </div>
        <h3><?php the_title(); ?></h3>
        <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
        <span class="resource-link"><?php esc_html_e('Explore →', 'baloch-heritage'); ?></span>
      </a>
      <?php endwhile; wp_reset_postdata();
      else:
        $fallback = !empty($demo_data['resourcesSection']) ? $demo_data['resourcesSection'] : [
          ['title'=>'Education','description'=>'Scholarships, tutoring, and mentorship for Balochi youth.', 'link'=>'/resources/'],
          ['title'=>'Language','description'=>'Classes, archives, and children\'s learning materials.', 'link'=>'/resources/'],
          ['title'=>'Empowerment','description'=>'Professional networks and leadership programs.', 'link'=>'/resources/'],
          ['title'=>'Social Support','description'=>'Settlement services and integration programs.', 'link'=>'/resources/'],
        ];
        foreach ($fallback as $r):
          $title = $r['title'] ?? $r[0];
          $desc = $r['description'] ?? $r[1];
          $link = $r['link'] ?? '/resources/';
      ?>
      <a href="<?php echo esc_url(home_url($link)); ?>" class="resource-card">
        <div class="resource-icon"><svg viewBox="0 0 28 28" fill="none" width="28" height="28"><path d="M14 3L3 9l11 6 11-6-11-6z" stroke="#E07B39" stroke-width="1.4" stroke-linejoin="round"/><path d="M3 14l11 6 11-6" stroke="#E07B39" stroke-width="1.4"/></svg></div>
        <h3><?php echo esc_html($title); ?></h3>
        <p><?php echo esc_html($desc); ?></p>
        <span class="resource-link"><?php esc_html_e('Explore →', 'baloch-heritage'); ?></span>
      </a>
      <?php endforeach; endif; ?>
    </div>
  </div>
</section>

<?php echo bh_carpet_border('#561010'); ?>

<?php endif; // resources ?>

<?php if (bh_section_enabled('events')): ?>
<!-- UPCOMING EVENTS -->
<section id="events" style="background:var(--cream);">
  <div class="container">
    <div class="section-header center reveal">
      <span class="section-label"><?php esc_html_e('Upcoming', 'baloch-heritage'); ?></span>
      <h2 class="section-title"><?php esc_html_e('Events & Festivals', 'baloch-heritage'); ?></h2>
      <p class="section-subtitle"><?php esc_html_e('Join us in celebrating Balochi culture across Canada.', 'baloch-heritage'); ?></p>
    </div>
    <div class="events-grid stagger">
      <?php
      $ev_query = bh_get_events(3, true);
      if ($ev_query->have_posts()):
        while ($ev_query->have_posts()): $ev_query->the_post();
          $date = get_post_meta(get_the_ID(), '_bh_event_date', true);
          $loc  = get_post_meta(get_the_ID(), '_bh_event_location', true);
          $d    = $date ? new DateTime($date) : null;
      ?>
      <div class="event-card">
        <div class="event-date-strip">
          <?php if ($d): ?><span class="event-day"><?php echo $d->format('d'); ?></span><span class="event-month"><?php echo $d->format('M Y'); ?></span><?php endif; ?>
        </div>
        <div class="event-body">
          <h3><?php the_title(); ?></h3>
          <?php if ($loc): ?><div class="event-loc">📍 <?php echo esc_html($loc); ?></div><?php endif; ?>
          <p><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
          <a href="<?php the_permalink(); ?>" class="btn-sm"><?php esc_html_e('RSVP', 'baloch-heritage'); ?></a>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata();
      else: // Fallback
        $ev_fallback = [
          ['02','Nov 2025','var(--maroon)','International Baloch Day','Aurora Event Centre, Ontario','Annual celebration of Baloch pride with performances, food, and community.'],
          ['28','Oct 2025','var(--terra)','Annual Cultural Festival','Toronto Convention Centre','Our flagship festival with Balochi music, dance, and traditional cuisine.'],
          ['12','Dec 2025','#2A5A1A','Balochi Music Night','Vancouver Arts Centre, BC','Damburag and Soroz music, Lewa dance and folk performances.'],        ];
        foreach ($ev_fallback as $ev): ?>
      <div class="event-card">
        <div class="event-date-strip" style="background:<?php echo $ev[2]; ?>;"><span class="event-day"><?php echo $ev[0]; ?></span><span class="event-month"><?php echo $ev[1]; ?></span></div>
        <div class="event-body">
          <h3><?php echo esc_html($ev[3]); ?></h3>
          <div class="event-loc">📍 <?php echo esc_html($ev[4]); ?></div>
          <p><?php echo esc_html($ev[5]); ?></p>
          <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn-sm" style="background:<?php echo $ev[2]; ?>;"><?php esc_html_e('RSVP', 'baloch-heritage'); ?></a>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem;" class="reveal">
      <a href="<?php echo esc_url(home_url('/events/')); ?>" class="btn-outline"><?php esc_html_e('View All Events', 'baloch-heritage'); ?></a>
    </div>
  </div>
</section>

<?php endif; // events ?>

<?php if (bh_section_enabled('news')): ?>
<!-- NEWS -->
<section id="news" style="background:var(--white);">
  <div class="container">
    <div class="section-header center reveal">
      <span class="section-label"><?php esc_html_e('Stay Connected', 'baloch-heritage'); ?></span>
      <h2 class="section-title"><?php esc_html_e('Community Hub & News', 'baloch-heritage'); ?></h2>
    </div>
    <div class="news-grid stagger">
      <?php
      $news_query = new WP_Query(['post_type'=>['post','bh_article'],'posts_per_page'=>3,'post_status'=>'publish']);
      if ($news_query->have_posts()):
        while ($news_query->have_posts()): $news_query->the_post();
      ?>
      <div class="news-card">
        <div class="news-img">
          <div class="news-img-inner ni1">
            <?php if (has_post_thumbnail()): the_post_thumbnail('bh-card'); endif; ?>
          </div>
        </div>
        <div class="news-body">
          <div class="news-tag"><?php echo esc_html(get_the_category()[0]->name ?? 'Community'); ?></div>
          <h3><?php the_title(); ?></h3>
          <p><?php echo wp_trim_words(get_the_excerpt(), 18); ?></p>
          <a href="<?php the_permalink(); ?>" class="read-more"><?php esc_html_e('Read more →', 'baloch-heritage'); ?></a>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata();
      else:
        $nf = [['ni1','Language','Preserving Balochi Language in Canada','New digital archive and language classes launching this spring.'],['ni2','Culture','Stories from Makran','Elders share their migration journeys to Canada.'],['ni3','Community','Community Spotlight','Fareeda Mengal turns traditional embroidery into a thriving business.']];
        foreach ($nf as $n): ?>
      <div class="news-card">
        <div class="news-img"><div class="news-img-inner <?php echo $n[0]; ?>"></div></div>
        <div class="news-body">
          <div class="news-tag"><?php echo esc_html($n[1]); ?></div>
          <h3><?php echo esc_html($n[2]); ?></h3>
          <p><?php echo esc_html($n[3]); ?></p>
          <a href="<?php echo esc_url(home_url('/news/')); ?>" class="read-more"><?php esc_html_e('Read more →', 'baloch-heritage'); ?></a>
        </div>
      </div>
      <?php endforeach; endif; ?>
    </div>
    <div style="text-align:center;margin-top:2.5rem;" class="reveal">
      <a href="<?php echo esc_url(home_url('/news/')); ?>" class="btn-outline"><?php esc_html_e('All Stories', 'baloch-heritage'); ?></a>
    </div>
  </div>
</section>

<?php endif; // news ?>

<?php if (bh_section_enabled('cta')): ?>
<!-- CTA -->
<div class="cta-strip" style="background:linear-gradient(135deg,#3a1205,#6b2010,#8B3A20);padding:5rem 0;text-align:center;position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0,rgba(255,255,255,0.025) 1px,transparent 1px,transparent 20px);pointer-events:none;"></div>
  <div class="container" style="position:relative;z-index:1;">
    <h2 style="font-family:'Playfair Display',serif;font-size:clamp(1.8rem,4vw,3rem);font-weight:900;color:#fff;margin-bottom:0.75rem;"><?php esc_html_e('Join Our Community Today', 'baloch-heritage'); ?></h2>
    <p style="color:rgba(255,255,255,0.7);font-size:1rem;margin-bottom:2rem;max-width:500px;margin-left:auto;margin-right:auto;"><?php esc_html_e('Become part of Canada\'s vibrant Baloch community. Connect, celebrate, and preserve our heritage together.', 'baloch-heritage'); ?></p>
    <div style="display:flex;justify-content:center;gap:1rem;flex-wrap:wrap;">
      <a href="<?php echo esc_url(home_url('/join/')); ?>" class="btn-primary"><?php esc_html_e('Apply for Membership', 'baloch-heritage'); ?></a>
      <a href="<?php echo esc_url(home_url('/members/')); ?>" class="btn-ghost"><?php esc_html_e('Member Portal', 'baloch-heritage'); ?></a>
      <a href="<?php echo esc_url(home_url('/contact/')); ?>" class="btn-ghost"><?php esc_html_e('Get in Touch', 'baloch-heritage'); ?></a>
    </div>
  </div>
</div>

<?php endif; // cta ?>

<script>
// Timeline animation
const tlFill = document.getElementById('timelineFill');
const tlSection = document.getElementById('timeline');
if (tlSection && tlFill) {
  const obs = new IntersectionObserver(entries => {
    entries.forEach(e => {
      if (!e.isIntersecting) return;
      let p = 0;
      const fill = setInterval(() => { p = Math.min(p+0.6,100); tlFill.style.height=p+'%'; if(p>=100)clearInterval(fill); }, 18);
      document.querySelectorAll('.timeline-content').forEach((c,i) => setTimeout(()=>c.classList.add('visible'),200+i*220));
      document.querySelectorAll('.timeline-dot-inner').forEach((d,i) => setTimeout(()=>d.classList.add('visible'),120+i*220));
      obs.unobserve(e.target);
    });
  }, { threshold: 0.05 });
  obs.observe(tlSection);
}
</script>

<?php get_footer(); ?>