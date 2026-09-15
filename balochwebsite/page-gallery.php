<?php
/**
 * Template Name: Art Gallery
 * Template Post Type: page
 */
get_header();
$active_tab = sanitize_text_field($_GET['tab'] ?? 'all');
?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('Art & Gallery','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Visual Heritage','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('Art & Craftsmanship','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('A curated gallery of Balochi embroidery, pottery, music traditions, and visual art — centuries of skill captured in form and color.','baloch-heritage'); ?></p>
  </div>
</div>

<!-- GALLERY TABS -->
<div style="background:var(--white);border-bottom:1px solid var(--cream-dark);position:sticky;top:64px;z-index:100;">
  <div class="container">
    <div style="display:flex;overflow-x:auto;">
      <?php
      $tabs = [];
      $tabs['all'] = __('All Work','baloch-heritage');
      $gal_cats = get_terms(['taxonomy'=>'bh_gallery_cat','hide_empty'=>true]);
      foreach ((array)$gal_cats as $gc) $tabs[$gc->slug] = $gc->name;
      foreach ($tabs as $slug => $label): ?>
      <a href="?tab=<?php echo esc_attr($slug); ?>"
         style="display:inline-block;padding:0.75rem 1.4rem;border-bottom:3px solid <?php echo $active_tab===$slug?'var(--maroon)':'transparent'; ?>;font-size:0.88rem;font-weight:<?php echo $active_tab===$slug?'700':'400'; ?>;color:<?php echo $active_tab===$slug?'var(--maroon)':'var(--text-light)'; ?>;text-decoration:none;white-space:nowrap;">
        <?php echo esc_html($label); ?>
      </a>
      <?php endforeach; ?>
    </div>
  </div>
</div>

<section style="background:var(--cream-dark);">
  <div class="container">
    <?php
    $gal_args = [
      'post_type'      => 'bh_gallery',
      'posts_per_page' => 24,
      'post_status'    => 'publish',
      'orderby'        => 'date',
      'order'          => 'DESC',
    ];
    if ($active_tab && $active_tab !== 'all') {
      $gal_args['tax_query'] = [['taxonomy'=>'bh_gallery_cat','field'=>'slug','terms'=>$active_tab]];
    }
    $gal_query = new WP_Query($gal_args);

    if ($gal_query->have_posts()):
    ?>
    <!-- Masonry grid (CSS columns) -->
    <div style="columns:4;column-gap:1rem;" id="galleryMasonry">
      <?php while ($gal_query->have_posts()): $gal_query->the_post();
        $caption = get_the_excerpt() ?: get_the_title();
      ?>
      <div style="break-inside:avoid;margin-bottom:1rem;border-radius:var(--radius);overflow:hidden;position:relative;cursor:pointer;"
           onclick="bhOpenLightbox('<?php echo esc_js(get_the_post_thumbnail_url(get_the_ID(),'bh-hero') ?: ''); ?>', '<?php echo esc_js(get_the_title()); ?>')">
        <?php if (has_post_thumbnail()): ?>
        <div style="overflow:hidden;">
          <?php the_post_thumbnail('bh-card', ['style'=>'width:100%;display:block;transition:transform 0.5s;','loading'=>'lazy']); ?>
        </div>
        <?php else: ?>
        <div style="height:<?php echo rand(140,280); ?>px;background:linear-gradient(135deg,#561010,#8B3A20);display:flex;align-items:center;justify-content:center;">
          <span style="color:rgba(255,255,255,0.4);font-size:0.72rem;text-align:center;padding:1rem;"><?php the_title(); ?></span>
        </div>
        <?php endif; ?>
        <div style="position:absolute;inset:0;background:linear-gradient(to top,rgba(42,21,8,0.7) 0%,transparent 60%);opacity:0;transition:opacity 0.3s;display:flex;align-items:flex-end;padding:1rem;" class="gallery-hover-overlay">
          <span style="color:#fff;font-size:0.78rem;font-weight:700;"><?php the_title(); ?></span>
        </div>
      </div>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
    <?php else: ?>
    <!-- Fallback placeholder gallery -->
    <div style="margin-bottom:1.5rem;">
      <span class="section-label"><?php esc_html_e('Gallery Placeholders','baloch-heritage'); ?></span>
      <h2 class="section-title"><?php esc_html_e('Add gallery items in WordPress Admin → Gallery','baloch-heritage'); ?></h2>
    </div>
    <div style="columns:4;column-gap:1rem;">
      <?php $heights=['220px','160px','300px','180px','240px','140px','200px','260px','175px','220px','155px','195px'];
      $colors=['linear-gradient(135deg,#561010,#8B2020)','linear-gradient(135deg,#8B3A10,#C4622D)','linear-gradient(135deg,#3A2010,#6B3A1A)','linear-gradient(135deg,#1A2A5C,#2A4A8A)','linear-gradient(135deg,#1A3A1A,#3A6A2A)','linear-gradient(135deg,#6B4A1A,#A07A3A)','linear-gradient(135deg,#3A1A5C,#6A3A9A)','linear-gradient(135deg,#1A3A4A,#2A6A7A)'];
      $labels=[__('Balochi Doch Embroidery','baloch-heritage'),__('Traditional Dress','baloch-heritage'),__('Wedding Garment','baloch-heritage'),__('Geometric Motifs','baloch-heritage'),__('Mirror Work Detail','baloch-heritage'),__('Balochistan Mountains','baloch-heritage'),__('Makran Coastline','baloch-heritage'),__('Desert Dunes','baloch-heritage'),__('Damburag Player','baloch-heritage'),__('Lewa Folk Dance','baloch-heritage'),__('Traditional Pottery','baloch-heritage'),__('Balochi Silver Jewelry','baloch-heritage')];
      foreach (range(0,11) as $i): ?>
      <div style="break-inside:avoid;margin-bottom:1rem;border-radius:var(--radius);overflow:hidden;position:relative;cursor:pointer;">
        <div style="height:<?php echo $heights[$i]; ?>;background:<?php echo $colors[$i%8]; ?>;display:flex;align-items:flex-end;padding:0.75rem;position:relative;overflow:hidden;">
          <div style="position:absolute;inset:0;background:repeating-linear-gradient(-45deg,transparent 0,transparent 12px,rgba(255,255,255,0.035) 12px,rgba(255,255,255,0.035) 13px);"></div>
          <span style="position:relative;z-index:1;color:rgba(255,255,255,0.7);font-size:0.7rem;font-weight:700;background:rgba(0,0,0,0.4);backdrop-filter:blur(4px);padding:0.2rem 0.5rem;border-radius:3px;"><?php echo esc_html($labels[$i]); ?></span>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <!-- PAGE CONTENT -->
    <?php while (have_posts()): the_post(); if (get_the_content()): ?>
    <div style="background:var(--white);border-radius:var(--radius);padding:2rem;box-shadow:var(--shadow-sm);margin-top:2rem;font-size:0.95rem;line-height:1.8;"><?php the_content(); ?></div>
    <?php endif; endwhile; ?>

  </div>
</section>

<!-- LIGHTBOX -->
<div id="bhLightbox" style="position:fixed;inset:0;background:rgba(0,0,0,0.95);z-index:9999;display:none;align-items:center;justify-content:center;padding:2rem;" onclick="if(event.target===this)bhCloseLightbox()">
  <div style="position:relative;max-width:900px;width:100%;text-align:center;">
    <button onclick="bhCloseLightbox()" style="position:absolute;top:-2.5rem;right:0;background:none;border:none;color:rgba(255,255,255,0.7);font-size:1.5rem;cursor:pointer;">✕</button>
    <img id="bhLightboxImg" src="" alt="" style="max-width:100%;border-radius:var(--radius);box-shadow:0 24px 80px rgba(0,0,0,0.8);">
    <div id="bhLightboxCaption" style="margin-top:1rem;color:rgba(255,255,255,0.6);font-size:0.88rem;"></div>
  </div>
</div>

<style>
div:hover .gallery-hover-overlay { opacity: 1 !important; }
@media (max-width:900px) { #galleryMasonry { columns: 2 !important; } }
@media (max-width:600px) { #galleryMasonry { columns: 2 !important; } }
</style>

<script>
function bhOpenLightbox(src, caption) {
  const lb = document.getElementById('bhLightbox');
  const img = document.getElementById('bhLightboxImg');
  const cap = document.getElementById('bhLightboxCaption');
  if (src) { img.src = src; img.style.display = ''; } else { img.style.display = 'none'; }
  cap.textContent = caption || '';
  lb.style.display = 'flex';
  document.body.style.overflow = 'hidden';
}
function bhCloseLightbox() {
  document.getElementById('bhLightbox').style.display = 'none';
  document.body.style.overflow = '';
}
document.addEventListener('keydown', e => { if (e.key === 'Escape') bhCloseLightbox(); });
</script>

<?php get_footer(); ?>
