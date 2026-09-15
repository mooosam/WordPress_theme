<?php
/**
 * Template Name: Join / Membership Application
 * Template Post Type: page
 */
get_header(); ?>

<div class="page-hero">
  <div class="container page-hero-content">
    <div class="breadcrumb"><a href="<?php echo home_url('/'); ?>"><?php esc_html_e('Home','baloch-heritage'); ?></a><span>/</span><span class="current"><?php esc_html_e('Join Us','baloch-heritage'); ?></span></div>
    <span class="section-label"><?php esc_html_e('Become a Member','baloch-heritage'); ?></span>
    <h1><?php esc_html_e('Join the Baloch Community','baloch-heritage'); ?></h1>
    <p><?php esc_html_e('Become part of Canada\'s vibrant Baloch community. Your application will be reviewed and approved by our community team.','baloch-heritage'); ?></p>
  </div>
</div>

<section style="background:var(--cream);">
  <div class="container">
    <?php if (is_user_logged_in() && bh_is_approved()): ?>
    <div style="text-align:center;padding:3rem;background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);max-width:500px;margin:0 auto;">
      <div style="font-size:3rem;margin-bottom:1rem;">✓</div>
      <h2 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:0.75rem;"><?php esc_html_e('You\'re Already a Member!','baloch-heritage'); ?></h2>
      <p style="color:var(--text-light);margin-bottom:1.5rem;"><?php esc_html_e('You have an active membership. Visit your member portal to manage your profile and access community features.','baloch-heritage'); ?></p>
      <a href="<?php echo home_url('/members/'); ?>" class="btn-primary"><?php esc_html_e('Go to Member Portal','baloch-heritage'); ?></a>
    </div>
    <?php else: ?>
    <div style="display:grid;grid-template-columns:1fr 1.2fr;gap:4rem;align-items:start;">

      <!-- SIDEBAR INFO -->
      <div style="position:sticky;top:88px;">
        <div style="background:var(--maroon-dark);border-radius:var(--radius);padding:2rem;color:#fff;margin-bottom:1.5rem;position:relative;overflow:hidden;">
          <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.02) 0,rgba(255,255,255,0.02) 1px,transparent 1px,transparent 24px);pointer-events:none;"></div>
          <h3 style="font-family:'Playfair Display',serif;font-size:1.2rem;color:var(--cream);margin-bottom:1rem;position:relative;z-index:1;"><?php esc_html_e('Membership Levels','baloch-heritage'); ?></h3>
          <?php
          $tiers = [
            ['icon'=>'👤','title'=>'Member',       'desc'=>'Full community access, event RSVPs, forum participation, and member directory.'],
            ['icon'=>'🛡️','title'=>'Moderator',    'desc'=>'All member benefits plus ability to approve new member applications (Level 1).'],
            ['icon'=>'⭐','title'=>'Board Member', 'desc'=>'All moderator benefits plus governance participation and final approval authority.'],
            ['icon'=>'🔑','title'=>'Administrator','desc'=>'Full system access — member management, content editing, and site configuration.'],
          ];
          foreach ($tiers as $t): ?>
          <div style="display:flex;align-items:flex-start;gap:0.9rem;padding:0.85rem;background:rgba(255,255,255,0.06);border-radius:var(--radius-sm);margin-bottom:0.6rem;border:1px solid rgba(255,255,255,0.08);position:relative;z-index:1;">
            <div style="font-size:1.3rem;flex-shrink:0;"><?php echo $t['icon']; ?></div>
            <div>
              <h4 style="font-size:0.85rem;font-weight:700;color:#fff;margin-bottom:0.2rem;"><?php echo esc_html($t['title']); ?></h4>
              <p style="font-size:0.75rem;color:rgba(255,255,255,0.6);line-height:1.4;margin:0;"><?php echo esc_html($t['desc']); ?></p>
            </div>
          </div>
          <?php endforeach; ?>
        </div>

        <div style="background:var(--cream-dark);border-radius:var(--radius);padding:1.4rem;">
          <h4 style="font-family:'Playfair Display',serif;color:var(--brown);font-size:0.95rem;margin-bottom:1rem;"><?php esc_html_e('How Approval Works','baloch-heritage'); ?></h4>
          <?php foreach([
            'Submit your membership application',
            'Application reviewed by a Moderator or Board Member',
            'You receive an email notification of approval',
            'Access your member portal and community features',
          ] as $i => $step): ?>
          <div style="display:flex;align-items:center;gap:0.7rem;margin-bottom:0.6rem;font-size:0.8rem;color:var(--text-light);">
            <div style="width:24px;height:24px;background:var(--orange);border-radius:50%;display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;flex-shrink:0;"><?php echo $i+1; ?></div>
            <?php echo esc_html($step); ?>
          </div>
          <?php endforeach; ?>
          <p style="font-size:0.72rem;color:var(--text-light);margin-top:0.75rem;"><?php esc_html_e('Typical review time: 1–3 business days.','baloch-heritage'); ?></p>
        </div>
      </div>

      <!-- APPLICATION FORM -->
      <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-md);overflow:hidden;">
        <div style="background:var(--maroon);padding:1.5rem 2rem;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:#fff;margin-bottom:0.3rem;"><?php esc_html_e('New Member Application','baloch-heritage'); ?></h2>
          <p style="font-size:0.82rem;color:rgba(255,255,255,0.65);"><?php esc_html_e('All fields marked * are required. Your information is kept private and secure.','baloch-heritage'); ?></p>
        </div>
        <div style="padding:2rem;">
          <div id="joinSuccess" style="display:none;text-align:center;padding:2rem;">
            <div style="width:64px;height:64px;background:rgba(76,175,80,0.12);border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 1rem;border:2px solid #4caf50;">
              <svg viewBox="0 0 32 32" fill="none" width="28" height="28"><path d="M6 16l8 8L26 8" stroke="#4caf50" stroke-width="2.5" stroke-linecap="round"/></svg>
            </div>
            <h3 style="font-family:'Playfair Display',serif;color:var(--brown);margin-bottom:0.75rem;" id="joinSuccessTitle"><?php esc_html_e('Application Submitted!','baloch-heritage'); ?></h3>
            <p style="color:var(--text-light);margin-bottom:1.5rem;" id="joinSuccessMsg"><?php esc_html_e('Thank you! Your application is under review. You\'ll receive an email within 1–3 business days.','baloch-heritage'); ?></p>
            <a href="<?php echo home_url('/members/'); ?>" class="btn-primary"><?php esc_html_e('Go to Member Portal','baloch-heritage'); ?></a>
          </div>
          <form id="joinForm" novalidate>
            <?php wp_nonce_field('bh_register_nonce','bh_nonce_field'); ?>
            <div class="form-row">
              <div class="form-group"><label class="form-label"><?php esc_html_e('First Name *','baloch-heritage'); ?></label><input class="form-control" id="joinFirst" type="text" required><div class="form-error"></div></div>
              <div class="form-group"><label class="form-label"><?php esc_html_e('Last Name *','baloch-heritage'); ?></label><input class="form-control" id="joinLast" type="text" required><div class="form-error"></div></div>
            </div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Email Address *','baloch-heritage'); ?></label><input class="form-control" id="joinEmail" type="email" required><div class="form-error"></div></div>
            <div class="form-row">
              <div class="form-group"><label class="form-label"><?php esc_html_e('Password *','baloch-heritage'); ?></label><input class="form-control" id="joinPass" type="password" placeholder="<?php esc_attr_e('Min. 8 characters','baloch-heritage'); ?>" required minlength="8"><div class="form-error"></div></div>
              <div class="form-group"><label class="form-label"><?php esc_html_e('Confirm Password *','baloch-heritage'); ?></label><input class="form-control" id="joinPassConfirm" type="password" required><div class="form-error"></div></div>
            </div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('City / Province *','baloch-heritage'); ?></label><input class="form-control" id="joinCity" type="text" placeholder="<?php esc_attr_e('Toronto, ON','baloch-heritage'); ?>" required><div class="form-error"></div></div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Baloch Tribe / Region (optional)','baloch-heritage'); ?></label><input class="form-control" id="joinTribe" type="text" placeholder="<?php esc_attr_e('e.g. Marri, Bugti, Mengal...','baloch-heritage'); ?>"></div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('How did you hear about us?','baloch-heritage'); ?></label>
              <select class="form-control" id="joinSource">
                <option><?php esc_html_e('Social Media','baloch-heritage'); ?></option>
                <option><?php esc_html_e('Friend or Family','baloch-heritage'); ?></option>
                <option><?php esc_html_e('Community Event','baloch-heritage'); ?></option>
                <option><?php esc_html_e('Online Search','baloch-heritage'); ?></option>
                <option><?php esc_html_e('Other','baloch-heritage'); ?></option>
              </select>
            </div>
            <div class="form-group"><label class="form-label"><?php esc_html_e('Brief Introduction (optional)','baloch-heritage'); ?></label><textarea class="form-control" id="joinIntro" rows="3" placeholder="<?php esc_attr_e('Tell us a little about yourself...','baloch-heritage'); ?>"></textarea></div>
            <label class="form-check" style="margin-bottom:1.2rem;">
              <input type="checkbox" id="joinAgree" required>
              <span><?php printf(esc_html__('I agree to the %sCommunity Guidelines%s and %sPrivacy Policy%s *','baloch-heritage'),'<a href="#" style="color:var(--orange);">','</a>','<a href="#" style="color:var(--orange);">','</a>'); ?></span>
            </label>
            <button type="submit" class="btn-primary" style="width:100%;"><?php esc_html_e('Submit Application','baloch-heritage'); ?></button>
          </form>
          <div style="text-align:center;margin-top:1.5rem;font-size:0.85rem;color:var(--text-light);">
            <?php esc_html_e('Already a member?','baloch-heritage'); ?> <a href="<?php echo home_url('/members/'); ?>" style="color:var(--orange);font-weight:700;"><?php esc_html_e('Sign in →','baloch-heritage'); ?></a>
          </div>
        </div>
      </div>

    </div>
    <?php endif; ?>
  </div>
</section>

<script>
document.getElementById('joinForm') && document.getElementById('joinForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const pass = document.getElementById('joinPass').value;
  const conf = document.getElementById('joinPassConfirm').value;
  if (pass !== conf) { showNotification('<?php esc_html_e('Passwords do not match.','baloch-heritage'); ?>', 'error'); return; }
  if (!document.getElementById('joinAgree').checked) { showNotification('<?php esc_html_e('Please agree to the Community Guidelines.','baloch-heritage'); ?>', 'error'); return; }

  const data = {
    action: 'bh_register',
    nonce: bhAjax.nonce,
    first_name: document.getElementById('joinFirst').value,
    last_name:  document.getElementById('joinLast').value,
    email:      document.getElementById('joinEmail').value,
    password:   pass,
    city:       document.getElementById('joinCity').value,
    tribe:      document.getElementById('joinTribe').value,
    source:     document.getElementById('joinSource').value,
    intro:      document.getElementById('joinIntro').value,
    agree_terms: '1',
  };

  fetch(bhAjax.url, {
    method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: Object.entries(data).map(([k,v]) => encodeURIComponent(k)+'='+encodeURIComponent(v)).join('&')
  }).then(r=>r.json()).then(d => {
    if (d.success) {
      document.getElementById('joinForm').style.display = 'none';
      document.getElementById('joinSuccess').style.display = 'block';
      if (d.data.message) document.getElementById('joinSuccessMsg').textContent = d.data.message;
    } else {
      showNotification(d.data.message, 'error', 5000);
    }
  }).catch(() => showNotification('<?php esc_html_e('An error occurred. Please try again.','baloch-heritage'); ?>', 'error'));
});
</script>

<?php get_footer(); ?>
