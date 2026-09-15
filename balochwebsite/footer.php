<?php
/**
 * Baloch Heritage — footer.php
 */
?>

<?php echo bh_carpet_border('#2A1508'); ?>

<footer>
  <div class="container">
    <div class="footer-grid">
      <div class="footer-brand">
        <h3><?php bloginfo('name'); ?></h3>
        <p><?php echo esc_html(get_theme_mod('bh_footer_desc', 'The Baloch Cultural Society of Canada — uniting the Baloch diaspora, preserving our rich culture, language, and traditions across all provinces.')); ?></p>
        <div class="footer-contact-item">
          <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M1 3l5.5 4L12 3" stroke="rgba(255,255,255,0.5)" stroke-width="1.2"/><rect x="1" y="2" width="11" height="9" rx="1.5" stroke="rgba(255,255,255,0.5)" stroke-width="1.2"/></svg>
          <a href="mailto:<?php echo esc_attr(get_theme_mod('bh_email','info@balochheritage.org')); ?>" style="color:rgba(255,255,255,0.65);text-decoration:none;">
            <?php echo esc_html(get_theme_mod('bh_email','info@balochheritage.org')); ?>
          </a>
        </div>
        <div class="footer-contact-item">
          <svg width="13" height="13" viewBox="0 0 13 13" fill="none"><path d="M6.5 1C4.6 1 3 2.6 3 4.5c0 2.6 3.5 7.5 3.5 7.5S10 7.1 10 4.5C10 2.6 8.4 1 6.5 1zm0 4.75a1.25 1.25 0 110-2.5 1.25 1.25 0 010 2.5z" fill="rgba(255,255,255,0.5)"/></svg>
          <?php echo esc_html(get_theme_mod('bh_address','Industry Street, Toronto, ON')); ?>
        </div>
        <div class="footer-contact-item">
          <?php echo esc_html(get_theme_mod('bh_phone','(416) 555-0001')); ?>
        </div>
      </div>

      <div class="footer-col">
        <h4><?php esc_html_e('Quick Links', 'baloch-heritage'); ?></h4>
        <?php wp_nav_menu(['theme_location'=>'footer','container'=>false,'menu_class'=>'footer-links','fallback_cb'=>'bh_footer_nav_fallback']); ?>
      </div>

      <div class="footer-col">
        <h4><?php esc_html_e('Follow Us', 'baloch-heritage'); ?></h4>
        <div class="social-links">
          <?php if ($fb = get_theme_mod('bh_facebook','#')): ?><a href="<?php echo esc_url($fb); ?>" class="social-btn" target="_blank" rel="noopener">f</a><?php endif; ?>
          <?php if ($ig = get_theme_mod('bh_instagram','#')): ?><a href="<?php echo esc_url($ig); ?>" class="social-btn" target="_blank" rel="noopener">ig</a><?php endif; ?>
          <?php if ($yt = get_theme_mod('bh_youtube','#')): ?><a href="<?php echo esc_url($yt); ?>" class="social-btn" target="_blank" rel="noopener">▶</a><?php endif; ?>
          <?php if ($tw = get_theme_mod('bh_twitter','#')): ?><a href="<?php echo esc_url($tw); ?>" class="social-btn" target="_blank" rel="noopener">tw</a><?php endif; ?>
        </div>
        <ul class="footer-links">
          <li><a href="<?php echo esc_url(get_theme_mod('bh_facebook','#')); ?>">Facebook</a></li>
          <li><a href="<?php echo esc_url(get_theme_mod('bh_instagram','#')); ?>">Instagram</a></li>
          <li><a href="<?php echo esc_url(get_theme_mod('bh_youtube','#')); ?>">YouTube</a></li>
          <li><a href="<?php echo esc_url(get_theme_mod('bh_twitter','#')); ?>">Twitter / X</a></li>
        </ul>
      </div>

      <div class="footer-col">
        <h4><?php esc_html_e('Newsletter', 'baloch-heritage'); ?></h4>
        <p style="font-size:0.82rem;margin-bottom:0.75rem;"><?php esc_html_e('Stay connected with community updates and event announcements.', 'baloch-heritage'); ?></p>
        <div class="newsletter-form">
          <input type="email" id="footerNewsletterEmail" placeholder="<?php esc_attr_e('Your email', 'baloch-heritage'); ?>">
          <button type="button" onclick="bhNewsletterSignup()"><?php esc_html_e('Join', 'baloch-heritage'); ?></button>
        </div>
        <div style="margin-top:1.2rem;">
          <a href="<?php echo esc_url(home_url('/join/')); ?>" class="btn-sm"><?php esc_html_e('Join Community →', 'baloch-heritage'); ?></a>
        </div>
      </div>
    </div>

    <div class="footer-bottom">
      <span>
        <svg width="14" height="14" viewBox="0 0 26 26" fill="none" style="vertical-align:middle;margin-right:4px;"><circle cx="13" cy="13" r="10" stroke="rgba(255,255,255,0.35)" stroke-width="1.5"/><path d="M7 13 Q10 7 13 13 Q16 19 19 13" stroke="rgba(255,255,255,0.35)" stroke-width="1.5" fill="none"/></svg>
        © <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. <?php esc_html_e('All Rights Reserved.', 'baloch-heritage'); ?>
      </span>
      <span style="color:rgba(255,255,255,0.4);"><?php esc_html_e('Celebrating Balochi culture across Canada 🍁', 'baloch-heritage'); ?></span>
    </div>
  </div>
</footer>

<script>
function bhNewsletterSignup() {
  const email = document.getElementById('footerNewsletterEmail').value;
  if (!email || !email.includes('@')) { alert('Please enter a valid email.'); return; }
  fetch(bhAjax.url, {
    method: 'POST',
    headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=bh_newsletter&nonce=' + bhAjax.nonce + '&email=' + encodeURIComponent(email)
  }).then(r=>r.json()).then(d => {
    if (d.success) showNotification(d.data.message, 'success');
    else showNotification(d.data.message, 'error');
  });
}
</script>

<?php wp_footer(); ?>
</body>
</html>

<?php
if (!function_exists('bh_footer_nav_fallback')):
function bh_footer_nav_fallback() {
  $links = ['Home'=>'/','Culture'=>'/culture/','History'=>'/history/','Events'=>'/events/','Resources'=>'/resources/','About Us'=>'/about/','Contact Us'=>'/contact/'];
  echo '<ul class="footer-links">';
  foreach ($links as $l => $u) echo '<li><a href="' . esc_url(home_url($u)) . '">' . esc_html($l) . '</a></li>';
  echo '</ul>';
}
endif;
?>
