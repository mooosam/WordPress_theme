<?php
/**
 * Template Name: Events
 * Template Post Type: page
 */
get_header();
$filter_type = sanitize_text_field($_GET['type'] ?? '');
$search_term = sanitize_text_field($_GET['s'] ?? '');
?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('Events','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Calendar 2025–2026','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('Events & Festivals','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('Celebrate Balochi culture — from national days to music nights, art exhibitions, and youth summits across Canada.','baloch-heritage'); ?></p>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 300px;gap:3rem;align-items:start;">

      <!-- EVENTS MAIN -->
      <div>
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:1.5rem;flex-wrap:wrap;gap:1rem;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--brown);" id="eventsLabel">
            <?php esc_html_e('Upcoming Events','baloch-heritage'); ?>
          </h2>
          <form method="get">
            <input type="search" name="s" value="<?php echo esc_attr($search_term); ?>"
              placeholder="<?php esc_attr_e('Search events...','baloch-heritage'); ?>"
              style="padding:0.55rem 1rem;border:1.5px solid rgba(122,28,28,0.2);border-radius:var(--radius-sm);font-size:0.88rem;outline:none;min-width:200px;">
          </form>
        </div>

        <?php
        $ev_args = [
          'post_type'      => 'bh_event',
          'posts_per_page' => 12,
          'post_status'    => 'publish',
          'meta_key'       => '_bh_event_date',
          'orderby'        => 'meta_value',
          'order'          => 'ASC',
        ];
        if ($filter_type) {
          $ev_args['tax_query'] = [['taxonomy'=>'bh_event_type','field'=>'slug','terms'=>$filter_type]];
        }
        if ($search_term) {
          $ev_args['s'] = $search_term;
        }
        $upcoming = [['key'=>'_bh_event_date','value'=>date('Y-m-d'),'compare'=>'>=','type'=>'DATE']];
        $ev_args['meta_query'] = isset($ev_args['meta_query']) ? array_merge($upcoming, $ev_args['meta_query']) : $upcoming;
        $ev_query = new WP_Query($ev_args);
        if ($ev_query->have_posts()):
          while ($ev_query->have_posts()): $ev_query->the_post();
            $date = get_post_meta(get_the_ID(), '_bh_event_date', true);
            $loc  = get_post_meta(get_the_ID(), '_bh_event_location', true);
            $cap  = (int)get_post_meta(get_the_ID(), '_bh_event_capacity', true);
            $rsvps= count(get_post_meta(get_the_ID(), '_bh_event_rsvps', true) ?: []);
            $d    = $date ? new DateTime($date) : null;
            $pct  = ($cap > 0) ? min(100, round($rsvps/$cap*100)) : 0;
        ?>
        <div style="background:var(--white);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);display:grid;grid-template-columns:180px 1fr;margin-bottom:1.2rem;transition:transform 0.3s,box-shadow 0.3s;" class="event-card-lg">
          <div style="background:linear-gradient(135deg,#561010,#8B2020);min-height:160px;display:flex;align-items:flex-start;padding:0.8rem;position:relative;overflow:hidden;">
            <?php if (has_post_thumbnail()): the_post_thumbnail('bh-thumb', ['style'=>'position:absolute;inset:0;width:100%;height:100%;object-fit:cover;']); endif; ?>
            <?php if ($d): ?>
            <div style="position:relative;z-index:2;background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);border-radius:6px;padding:0.4rem 0.6rem;text-align:center;">
              <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:#fff;line-height:1;"><?php echo $d->format('d'); ?></div>
              <div style="font-size:0.62rem;font-weight:700;color:rgba(255,255,255,0.7);text-transform:uppercase;"><?php echo $d->format('M Y'); ?></div>
            </div>
            <?php endif; ?>
          </div>
          <div style="padding:1.3rem 1.5rem;">
            <?php $type_terms = get_the_terms(get_the_ID(),'bh_event_type');
            if ($type_terms): ?><span class="card-tag"><?php echo esc_html($type_terms[0]->name); ?></span><?php endif; ?>
            <h3 style="font-family:'Playfair Display',serif;font-size:1.05rem;color:var(--brown);margin-bottom:0.5rem;"><?php the_title(); ?></h3>
            <div style="display:flex;flex-wrap:wrap;gap:0.8rem;margin-bottom:0.7rem;">
              <?php if ($loc): ?><div style="display:flex;align-items:center;gap:0.3rem;font-size:0.75rem;color:var(--text-light);">📍 <?php echo esc_html($loc); ?></div><?php endif; ?>
              <?php if ($d): ?><div style="font-size:0.75rem;color:var(--text-light);">📅 <?php echo $d->format('F j, Y'); ?></div><?php endif; ?>
            </div>
            <p style="font-size:0.82rem;color:var(--text-light);line-height:1.6;margin-bottom:1rem;"><?php echo wp_trim_words(get_the_excerpt(),25); ?></p>
            <div style="display:flex;align-items:center;gap:0.75rem;flex-wrap:wrap;">
              <a href="<?php the_permalink(); ?>" class="btn-sm"><?php esc_html_e('RSVP Now','baloch-heritage'); ?></a>
              <?php if ($cap > 0): ?>
              <span style="font-size:0.75rem;color:var(--text-light);">
                <?php echo ($cap - $rsvps); ?> <?php esc_html_e('spots left','baloch-heritage'); ?>
                <span style="display:inline-block;height:4px;background:var(--cream-dark);border-radius:2px;width:80px;vertical-align:middle;margin:0 0.4rem;overflow:hidden;">
                  <span style="display:block;height:100%;background:var(--orange);border-radius:2px;width:<?php echo $pct; ?>%;"></span>
                </span>
              </span>
              <?php endif; ?>
            </div>
          </div>
        </div>
        <?php endwhile; wp_reset_postdata();
        else: ?>
        <div style="text-align:center;padding:3rem;color:var(--text-light);">
          <div style="font-size:2.5rem;margin-bottom:1rem;">📅</div>
          <h3 style="font-family:'Playfair Display',serif;color:var(--brown);"><?php esc_html_e('No upcoming events found','baloch-heritage'); ?></h3>
          <p><?php esc_html_e('Check back soon for new events, or subscribe to our newsletter.','baloch-heritage'); ?></p>
        </div>
        <?php endif; ?>

        <?php the_posts_pagination(['prev_text'=>'‹','next_text'=>'›','mid_size'=>2]); ?>
      </div>

      <!-- SIDEBAR -->
      <div style="position:sticky;top:88px;">
        <!-- Filter -->
        <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);padding:1.2rem;margin-bottom:1.5rem;">
          <h4 style="font-size:0.82rem;font-weight:700;color:var(--brown);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.8rem;"><?php esc_html_e('Filter by Type','baloch-heritage'); ?></h4>
          <a href="?" style="display:block;padding:0.5rem 0.7rem;border-radius:var(--radius-sm);font-size:0.82rem;color:<?php echo !$filter_type?'var(--brown)':'var(--text-light)'; ?>;font-weight:<?php echo !$filter_type?'700':'400'; ?>;text-decoration:none;margin-bottom:0.2rem;"><?php esc_html_e('All Events','baloch-heritage'); ?></a>
          <?php
          $types = get_terms(['taxonomy'=>'bh_event_type','hide_empty'=>true]);
          foreach ((array)$types as $term):
          ?>
          <a href="?type=<?php echo esc_attr($term->slug); ?>"
             style="display:block;padding:0.5rem 0.7rem;border-radius:var(--radius-sm);font-size:0.82rem;color:<?php echo $filter_type===$term->slug?'var(--brown)':'var(--text-light)'; ?>;font-weight:<?php echo $filter_type===$term->slug?'700':'400'; ?>;text-decoration:none;margin-bottom:0.2rem;">
            <?php echo esc_html($term->name); ?>
          </a>
          <?php endforeach; ?>
        </div>
        <!-- Newsletter -->
        <div style="background:var(--maroon-dark);border-radius:var(--radius);padding:1.4rem;">
          <h4 style="font-family:'Playfair Display',serif;color:var(--cream);font-size:0.95rem;margin-bottom:0.6rem;"><?php esc_html_e('Event Updates','baloch-heritage'); ?></h4>
          <p style="font-size:0.78rem;color:rgba(255,255,255,0.65);margin-bottom:0.8rem;"><?php esc_html_e('Get notified about new events and cultural festivals.','baloch-heritage'); ?></p>
          <div class="newsletter-form">
            <input type="email" id="eventsNewsletterEmail" placeholder="<?php esc_attr_e('Your email','baloch-heritage'); ?>">
            <button type="button" onclick="bhNewsletterSignup('eventsNewsletterEmail')"><?php esc_html_e('Join','baloch-heritage'); ?></button>
          </div>
        </div>
      </div>

    </div>
  </div>
</section>

<!-- RSVP MODAL -->
<div class="modal-overlay" id="rsvpModal">
  <div class="modal">
    <div class="modal-header"><h3 id="rsvpModalTitle"><?php esc_html_e('RSVP for Event','baloch-heritage'); ?></h3><button class="modal-close" onclick="closeModal(document.getElementById('rsvpModal'))">✕</button></div>
    <div class="modal-body">
      <input type="hidden" id="rsvpEventId">
      <div class="form-group"><label class="form-label"><?php esc_html_e('Full Name *','baloch-heritage'); ?></label><input class="form-control" id="rsvpName" type="text" required></div>
      <div class="form-group"><label class="form-label"><?php esc_html_e('Email *','baloch-heritage'); ?></label><input class="form-control" id="rsvpEmail" type="email" required></div>
      <div class="form-group"><label class="form-label"><?php esc_html_e('Number of Guests','baloch-heritage'); ?></label>
        <select class="form-control"><option>1 (<?php esc_html_e('Just me','baloch-heritage'); ?>)</option><option>2</option><option>3</option><option>4</option><option>5+</option></select>
      </div>
    </div>
    <div class="modal-footer">
      <button class="btn-outline" onclick="closeModal(document.getElementById('rsvpModal'))"><?php esc_html_e('Cancel','baloch-heritage'); ?></button>
      <button class="btn-primary" onclick="submitRSVP()"><?php esc_html_e('Confirm RSVP','baloch-heritage'); ?></button>
    </div>
  </div>
</div>

<script>
function openRSVP(id, title) {
  document.getElementById('rsvpEventId').value = id;
  document.getElementById('rsvpModalTitle').textContent = 'RSVP — ' + title;
  document.getElementById('rsvpModal').classList.add('active');
  document.body.style.overflow = 'hidden';
}
function submitRSVP() {
  const name  = document.getElementById('rsvpName').value;
  const email = document.getElementById('rsvpEmail').value;
  const id    = document.getElementById('rsvpEventId').value;
  if (!name || !email) { showNotification('<?php esc_html_e('Please fill all required fields.','baloch-heritage'); ?>', 'error'); return; }
  fetch(bhAjax.url, {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=bh_event_rsvp&nonce=' + bhAjax.nonce + '&event_id=' + id + '&name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email)
  }).then(r=>r.json()).then(d => {
    closeModal(document.getElementById('rsvpModal'));
    if (d.success) showNotification(d.data.message, 'success', 4000);
    else showNotification(d.data.message, 'error');
  });
}
function bhNewsletterSignup(inputId) {
  const email = document.getElementById(inputId).value;
  if (!email) return;
  fetch(bhAjax.url, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_newsletter&nonce='+bhAjax.nonce+'&email='+encodeURIComponent(email)
  }).then(r=>r.json()).then(d=>showNotification(d.data.message, d.success?'success':'error'));
}
</script>

<?php get_footer(); ?>
