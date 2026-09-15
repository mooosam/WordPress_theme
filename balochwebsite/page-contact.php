<?php
/**
 * Template Name: Contact
 * Template Post Type: page
 */
get_header(); ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('Contact Us','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Get in Touch','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('Contact Us','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('Whether you have a question, want to volunteer, submit a story, or just say hello — we\'d love to hear from you.','baloch-heritage'); ?></p>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <div style="display:grid;grid-template-columns:1fr 1.4fr;gap:4rem;align-items:start;">

      <!-- INFO -->
      <div>
        <div style="background:var(--maroon-dark);border-radius:var(--radius);padding:2rem;color:#fff;margin-bottom:1.5rem;position:relative;overflow:hidden;">
          <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.02) 0,rgba(255,255,255,0.02) 1px,transparent 1px,transparent 24px);pointer-events:none;"></div>
          <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--cream);margin-bottom:1.2rem;position:relative;z-index:1;"><?php esc_html_e('Contact Information','baloch-heritage'); ?></h3>
          <?php
          $contacts = [
            ['icon'=>'email', 'label'=>'Email',   'value'=>get_theme_mod('bh_email','info@balochheritage.org'), 'is_link'=>true, 'prefix'=>'mailto:'],
            ['icon'=>'phone', 'label'=>'Phone',   'value'=>get_theme_mod('bh_phone','(416) 555-0001')],
            ['icon'=>'addr',  'label'=>'Address', 'value'=>get_theme_mod('bh_address','123 Industry Street, Toronto, ON M5H 3X9')],
            ['icon'=>'hours', 'label'=>'Hours',   'value'=>'Monday – Friday, 9:00am – 5:00pm EST'],
          ];
          foreach ($contacts as $c): ?>
          <div style="display:flex;align-items:flex-start;gap:0.9rem;margin-bottom:1.2rem;position:relative;z-index:1;">
            <div style="width:40px;height:40px;background:rgba(224,123,57,0.15);border:1px solid rgba(224,123,57,0.25);border-radius:var(--radius-sm);display:flex;align-items:center;justify-content:center;flex-shrink:0;font-size:1rem;">
              <?php echo ['email'=>'✉','phone'=>'📞','addr'=>'📍','hours'=>'🕐'][$c['icon']]; ?>
            </div>
            <div>
              <div style="font-size:0.72rem;font-weight:700;color:rgba(255,255,255,0.5);text-transform:uppercase;letter-spacing:0.08em;margin-bottom:0.2rem;"><?php echo esc_html($c['label']); ?></div>
              <?php if (!empty($c['is_link'])): ?>
              <a href="<?php echo esc_url($c['prefix'] . $c['value']); ?>" style="color:var(--sand);font-size:0.88rem;text-decoration:none;"><?php echo esc_html($c['value']); ?></a>
              <?php else: ?>
              <p style="font-size:0.88rem;color:var(--cream);margin:0;"><?php echo esc_html($c['value']); ?></p>
              <?php endif; ?>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="background:var(--cream-dark);border-radius:var(--radius);padding:1.4rem;">
          <h4 style="font-family:'Playfair Display',serif;color:var(--brown);font-size:0.95rem;margin-bottom:1rem;"><?php esc_html_e('Chapters Across Canada','baloch-heritage'); ?></h4>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:0.4rem;">
            <?php foreach(['Toronto, ON','Vancouver, BC','Calgary, AB','Ottawa, ON','Edmonton, AB','Montreal, QC','Mississauga, ON','Winnipeg, MB'] as $city): ?>
            <div style="display:flex;align-items:center;gap:0.4rem;font-size:0.8rem;color:var(--text-light);padding:0.3rem 0;">
              <div style="width:6px;height:6px;border-radius:50%;background:var(--orange);flex-shrink:0;"></div>
              <?php echo esc_html($city); ?>
            </div>
            <?php endforeach; ?>
          </div>
        </div>
      </div>

      <!-- FORM -->
      <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="background:var(--maroon);padding:1.5rem 2rem;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;margin-bottom:0.3rem;"><?php esc_html_e('Send Us a Message','baloch-heritage'); ?></h2>
          <p style="font-size:0.82rem;color:rgba(255,255,255,0.65);"><?php esc_html_e('We typically respond within 1–2 business days.','baloch-heritage'); ?></p>
        </div>
        <div style="padding:2rem;">
          <div id="contactSuccess" style="display:none;background:#eaf7ea;border:1px solid #4caf50;border-radius:var(--radius-sm);padding:1rem 1.2rem;color:#2e7d32;font-size:0.9rem;margin-bottom:1rem;">
            <?php esc_html_e('Thank you! We\'ll be in touch within 1–2 business days.','baloch-heritage'); ?>
          </div>
          <form id="contactForm" novalidate>
            <?php wp_nonce_field('bh_contact_form','bh_contact_nonce'); ?>
            <div class="form-row">
              <div class="form-group"><label class="form-label"><?php esc_html_e('First Name *','baloch-heritage'); ?></label><input class="form-control" id="cfFirst" type="text" required><div class="form-error"></div></div>
              <div class="form-group"><label class="form-label"><?php esc_html_e('Last Name *','baloch-heritage'); ?></label><input class="form-control" id="cfLast" type="text" required><div class="form-error"></div></div>
            </div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Email Address *','baloch-heritage'); ?></label><input class="form-control" id="cfEmail" type="email" required><div class="form-error"></div></div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Phone (optional)','baloch-heritage'); ?></label><input class="form-control" type="tel" placeholder="+1 (416) 555-0000"></div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Subject *','baloch-heritage'); ?></label><input class="form-control" id="cfSubject" type="text" required><div class="form-error"></div></div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Message *','baloch-heritage'); ?></label><textarea class="form-control" id="cfMessage" rows="5" required></textarea><div class="form-error"></div></div>
            <label class="form-check" style="margin-bottom:1.2rem;"><input type="checkbox" id="cfNewsletter" checked><span><?php esc_html_e('Subscribe me to community updates and event announcements','baloch-heritage'); ?></span></label>
            <button type="submit" class="btn-primary" style="width:100%;"><?php esc_html_e('Send Message','baloch-heritage'); ?></button>
          </form>
        </div>
      </div>

    </div>
  </div>
</section>

<script>
document.getElementById('contactForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const name    = document.getElementById('cfFirst').value + ' ' + document.getElementById('cfLast').value;
  const email   = document.getElementById('cfEmail').value;
  const subject = document.getElementById('cfSubject').value;
  const message = document.getElementById('cfMessage').value;
  if (!name.trim() || !email || !subject || !message) { showNotification('<?php esc_html_e('Please fill all required fields.','baloch-heritage'); ?>', 'error'); return; }
  fetch(bhAjax.url, {
    method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=bh_contact&nonce=' + bhAjax.nonce + '&name=' + encodeURIComponent(name) + '&email=' + encodeURIComponent(email) + '&subject=' + encodeURIComponent(subject) + '&message=' + encodeURIComponent(message)
  }).then(r=>r.json()).then(d => {
    if (d.success) {
      document.getElementById('contactForm').style.display = 'none';
      document.getElementById('contactSuccess').style.display = 'block';
      showNotification(d.data.message, 'success', 4000);
    } else {
      showNotification(d.data.message, 'error');
    }
  });
});
</script>

<?php get_footer(); ?>
