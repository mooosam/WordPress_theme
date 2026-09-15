<?php
/**
 * Template Name: Member Portal
 * Template Post Type: page
 */
get_header();

// If not logged in, show login/register form
if (!is_user_logged_in()):
?>
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:6rem 1.5rem 3rem;background:linear-gradient(135deg,#2A1508,#561010,#8B3A20);position:relative;overflow:hidden;">
  <div style="position:absolute;inset:0;background-image:repeating-linear-gradient(45deg,rgba(255,255,255,0.025) 0,rgba(255,255,255,0.025) 1px,transparent 1px,transparent 24px);pointer-events:none;"></div>
  <div style="background:var(--white);border-radius:var(--radius-lg);box-shadow:0 32px 80px rgba(0,0,0,0.4);max-width:440px;width:100%;overflow:hidden;position:relative;z-index:1;">
    <div style="background:var(--maroon);padding:1.8rem 2rem 1.4rem;">
      <h2 style="font-family:'Playfair Display',serif;font-size:1.4rem;color:#fff;margin-bottom:0.3rem;"><?php esc_html_e('Member Portal','baloch-heritage'); ?></h2>
      <p style="font-size:0.82rem;color:rgba(255,255,255,0.65);"><?php esc_html_e('Sign in to access your community dashboard','baloch-heritage'); ?></p>
    </div>
    <div style="padding:1.5rem 2rem 2rem;">
      <div style="background:rgba(224,123,57,0.08);border:1px solid rgba(224,123,57,0.2);border-radius:var(--radius-sm);padding:0.75rem 1rem;margin-bottom:1.2rem;font-size:0.78rem;color:var(--text-light);line-height:1.5;">
        <strong style="color:var(--terra);"><?php esc_html_e('New members:','baloch-heritage'); ?></strong>
        <?php printf(esc_html__('Apply at the %sJoin Us%s page — approval takes 1–3 days.','baloch-heritage'),'<a href="'.home_url('/join/').'" style="color:var(--orange);">','</a>'); ?>
      </div>
      <div id="loginError" style="display:none;background:#ffeaea;border:1px solid #e53935;border-radius:var(--radius-sm);padding:0.75rem 1rem;color:#c62828;font-size:0.85rem;margin-bottom:1rem;"></div>
      <form id="loginForm" novalidate>
        <div class="form-group"><label class="form-label"><?php esc_html_e('Email Address *','baloch-heritage'); ?></label><input class="form-control" id="loginEmail" type="email" required></div>
        <div class="form-group"><label class="form-label"><?php esc_html_e('Password *','baloch-heritage'); ?></label><input class="form-control" id="loginPassword" type="password" required></div>
        <label class="form-check" style="margin-bottom:1.2rem;"><input type="checkbox" id="loginRemember"><span><?php esc_html_e('Remember me','baloch-heritage'); ?></span></label>
        <button type="submit" class="btn-primary" style="width:100%;margin-bottom:1rem;"><?php esc_html_e('Sign In to Portal','baloch-heritage'); ?></button>
        <div style="text-align:center;font-size:0.82rem;color:var(--text-light);">
          <a href="#" onclick="bhPasswordReset()" style="color:var(--orange);"><?php esc_html_e('Forgot password?','baloch-heritage'); ?></a> &nbsp;·&nbsp;
          <a href="<?php echo home_url('/join/'); ?>" style="color:var(--orange);"><?php esc_html_e('Apply to join →','baloch-heritage'); ?></a>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
document.getElementById('loginForm').addEventListener('submit', function(e) {
  e.preventDefault();
  const errEl = document.getElementById('loginError');
  errEl.style.display = 'none';
  fetch(bhAjax.url, {
    method: 'POST', headers: {'Content-Type':'application/x-www-form-urlencoded'},
    body: 'action=bh_login&nonce=' + bhAjax.nonce +
          '&email=' + encodeURIComponent(document.getElementById('loginEmail').value) +
          '&password=' + encodeURIComponent(document.getElementById('loginPassword').value) +
          '&remember=' + (document.getElementById('loginRemember').checked ? '1' : '0')
  }).then(r=>r.json()).then(d => {
    if (d.success) { window.location.href = d.data.redirect || window.location.href; }
    else { errEl.textContent = d.data.message; errEl.style.display = 'block'; }
  });
});
function bhPasswordReset() {
  const email = document.getElementById('loginEmail').value;
  if (!email) { alert('<?php esc_html_e('Please enter your email address first.','baloch-heritage'); ?>'); return; }
  fetch(bhAjax.url, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_password_reset&nonce='+bhAjax.nonce+'&email='+encodeURIComponent(email)
  }).then(r=>r.json()).then(d=>showNotification(d.data.message,'success',4000));
}
</script>

<?php get_footer(); return; endif;
// ── LOGGED IN: PORTAL ──
$current_user = wp_get_current_user();
$user_id      = $current_user->ID;
$user_role    = bh_get_role($user_id);
$user_status  = get_user_meta($user_id, 'bh_member_status', true) ?: 'pending';
$can_approve  = bh_can_approve_members();
$is_admin     = current_user_can('administrator') || bh_has_role('bh_board');
$all_members  = $can_approve ? get_users(['number'=>100,'orderby'=>'registered','order'=>'DESC']) : [];
$pending_users= get_users(['role'=>'pending_member','number'=>100]);
$role_label   = bh_get_user_role_label($user_id);
?>

<!-- PORTAL TOP BAR -->
<div style="background:var(--maroon-dark);padding:1rem 0;margin-top:64px;">
  <div class="container" style="display:flex;align-items:center;justify-content:space-between;flex-wrap:wrap;gap:1rem;">
    <div style="display:flex;align-items:center;gap:1rem;">
      <div style="width:44px;height:44px;border-radius:50%;background:var(--maroon);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;color:#fff;font-size:1.1rem;">
        <?php echo strtoupper(substr($current_user->first_name ?: $current_user->display_name, 0, 1)); ?>
      </div>
      <div>
        <div style="font-family:'Playfair Display',serif;font-size:1rem;color:#fff;"><?php echo esc_html($current_user->display_name); ?></div>
        <div style="font-size:0.75rem;color:rgba(255,255,255,0.6);"><?php echo esc_html($role_label); ?></div>
      </div>
    </div>
    <div style="display:flex;gap:0.75rem;align-items:center;">
      <span style="background:rgba(224,123,57,0.2);color:var(--orange);font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;letter-spacing:0.08em;text-transform:uppercase;"><?php echo esc_html($role_label); ?></span>
      <a href="<?php echo wp_logout_url(home_url('/')); ?>" class="btn-ghost" style="font-size:0.8rem;padding:0.4rem 1rem;"><?php esc_html_e('Sign Out','baloch-heritage'); ?></a>
    </div>
  </div>
</div>

<section style="background:var(--cream-dark);">
  <div class="container">
    <div style="display:grid;grid-template-columns:240px 1fr;gap:2rem;padding:2rem 0;">

      <!-- SIDEBAR NAV -->
      <div style="position:sticky;top:88px;align-self:start;">
        <div style="background:var(--white);border-radius:var(--radius);overflow:hidden;box-shadow:var(--shadow-sm);">
          <?php
          $panels = [
            ['id'=>'dashboard','icon'=>'⊞','label'=>__('Dashboard','baloch-heritage'),'always'=>true],
            ['id'=>'profile',  'icon'=>'◉','label'=>__('My Profile','baloch-heritage'),'always'=>true],
            ['id'=>'events',   'icon'=>'◈','label'=>__('My Events','baloch-heritage'),'always'=>true],
          ];
          if ($can_approve) {
            $panels[] = ['id'=>'members', 'icon'=>'◎','label'=>__('All Members','baloch-heritage'),'mod'=>true];
            $panels[] = ['id'=>'approvals','icon'=>'◑','label'=>__('Approvals','baloch-heritage'),'mod'=>true,'badge'=>count($pending_users)];
          }
          if ($is_admin) {
            $panels[] = ['id'=>'admin','icon'=>'★','label'=>__('Admin Panel','baloch-heritage'),'admin'=>true];
          }
          foreach ($panels as $p): ?>
          <button onclick="bhShowPanel('<?php echo esc_js($p['id']); ?>', this)"
            data-panel="<?php echo esc_attr($p['id']); ?>"
            class="portal-nav-btn<?php echo $p['id']==='dashboard'?' active':''; ?>"
            style="display:flex;align-items:center;gap:0.7rem;padding:0.85rem 1.2rem;border:none;border-left:3px solid transparent;background:none;width:100%;text-align:left;font-size:0.88rem;font-weight:600;color:var(--text-light);cursor:pointer;transition:all 0.2s;border-top:none;border-right:none;border-bottom:none;">
            <span style="font-size:0.9rem;"><?php echo $p['icon']; ?></span>
            <?php echo esc_html($p['label']); ?>
            <?php if (!empty($p['badge']) && $p['badge'] > 0): ?>
            <span style="margin-left:auto;background:var(--orange);color:#fff;font-size:0.65rem;font-weight:700;padding:0.1rem 0.4rem;border-radius:20px;"><?php echo (int)$p['badge']; ?></span>
            <?php endif; ?>
          </button>
          <?php endforeach; ?>
          <div style="height:1px;background:var(--cream-dark);margin:0.3rem 0;"></div>
          <a href="<?php echo home_url('/'); ?>" style="display:flex;align-items:center;gap:0.7rem;padding:0.85rem 1.2rem;text-decoration:none;font-size:0.88rem;font-weight:600;color:var(--text-light);transition:color 0.2s;">
            <span>⌂</span><?php esc_html_e('Back to Website','baloch-heritage'); ?>
          </a>
        </div>
      </div>

      <!-- MAIN PANELS -->
      <div>

        <!-- DASHBOARD -->
        <div id="panel-dashboard" class="portal-panel">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown);margin-bottom:0.3rem;">
            <?php printf(esc_html__('Welcome back, %s!','baloch-heritage'), esc_html($current_user->first_name ?: $current_user->display_name)); ?>
          </h2>
          <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;">
            <?php printf(esc_html__('Signed in as %s','baloch-heritage'), esc_html($role_label)); ?>
          </p>

          <?php if ($user_status === 'pending'): ?>
          <div style="background:rgba(224,123,57,0.1);border:1px solid rgba(224,123,57,0.3);border-radius:var(--radius-sm);padding:1.2rem 1.5rem;margin-bottom:1.5rem;">
            <strong style="color:var(--terra);">⏳ <?php esc_html_e('Application Pending','baloch-heritage'); ?></strong>
            <p style="font-size:0.85rem;color:var(--text-light);margin-top:0.4rem;"><?php esc_html_e('Your membership application is under review. You\'ll receive an email once approved (1–3 business days).','baloch-heritage'); ?></p>
          </div>
          <?php endif; ?>

          <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
            <?php if ($can_approve):
              $total_members = count(get_users(['role__in'=>['bh_member','bh_moderator','bh_board','administrator']]));
              $pending_count = count($pending_users);
              $cities = count(array_unique(array_filter(array_map(fn($u)=>explode(',',get_user_meta($u->ID,'bh_city',true))[0]??'', get_users(['number'=>500])))));
              $recent = count(get_users(['date_query'=>[['after'=>'30 days ago']]]));
              $stats = [
                [__('Total Members','baloch-heritage'), $total_members, __('approved members','baloch-heritage')],
                [__('Pending','baloch-heritage'), $pending_count, __('awaiting approval','baloch-heritage'), 'var(--orange)'],
                [__('Cities','baloch-heritage'), $cities, __('across Canada','baloch-heritage')],
                [__('This Month','baloch-heritage'), $recent, __('new applications','baloch-heritage')],
              ];
            else:
              $since_year = date('Y', strtotime($current_user->user_registered));
              $stats = [
                [__('Status','baloch-heritage'), $user_status==='approved'?'✓ Active':'⏳ Pending', '', $user_status==='approved'?'#2e7d32':'var(--orange)'],
                [__('Member Since','baloch-heritage'), $since_year, ''],
                [__('Role','baloch-heritage'), $role_label, '', 'var(--maroon)'],
                [__('Events','baloch-heritage'), '3', __('registered','baloch-heritage')],
              ];
            endif;
            foreach ($stats as $stat): ?>
            <div style="background:var(--white);border-radius:var(--radius);padding:1.2rem 1.4rem;box-shadow:var(--shadow-sm);">
              <div style="font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.4rem;"><?php echo esc_html($stat[0]); ?></div>
              <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:<?php echo esc_attr($stat[3]??'var(--brown)'); ?>;"><?php echo esc_html($stat[1]); ?></div>
              <?php if (!empty($stat[2])): ?><div style="font-size:0.72rem;color:var(--text-light);margin-top:0.2rem;"><?php echo esc_html($stat[2]); ?></div><?php endif; ?>
            </div>
            <?php endforeach; ?>
          </div>

          <?php if ($can_approve && count($pending_users) > 0): ?>
          <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;">
            <div style="padding:1.2rem 1.5rem;border-bottom:1px solid var(--cream-dark);">
              <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);"><?php esc_html_e('Recent Applications','baloch-heritage'); ?></h3>
            </div>
            <div style="overflow-x:auto;">
              <table style="width:100%;border-collapse:collapse;">
                <thead><tr style="background:var(--cream);">
                  <th style="padding:0.7rem 1.2rem;text-align:left;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php esc_html_e('Name','baloch-heritage'); ?></th>
                  <th style="padding:0.7rem 1.2rem;text-align:left;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php esc_html_e('Email','baloch-heritage'); ?></th>
                  <th style="padding:0.7rem 1.2rem;text-align:left;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php esc_html_e('City','baloch-heritage'); ?></th>
                  <th style="padding:0.7rem 1.2rem;text-align:left;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php esc_html_e('Applied','baloch-heritage'); ?></th>
                  <th style="padding:0.7rem 1.2rem;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php esc_html_e('Actions','baloch-heritage'); ?></th>
                </tr></thead>
                <tbody>
                  <?php foreach (array_slice($pending_users, 0, 5) as $pu): ?>
                  <tr style="border-bottom:1px solid var(--cream-dark);" id="pending-row-<?php echo $pu->ID; ?>">
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);">
                      <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--maroon);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;flex-shrink:0;">
                          <?php echo strtoupper(substr($pu->first_name ?: $pu->display_name, 0, 1)); ?>
                        </div>
                        <?php echo esc_html($pu->display_name); ?>
                      </div>
                    </td>
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);"><?php echo esc_html($pu->user_email); ?></td>
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);"><?php echo esc_html(get_user_meta($pu->ID,'bh_city',true) ?: '—'); ?></td>
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);"><?php echo date('M j, Y', strtotime($pu->user_registered)); ?></td>
                    <td style="padding:0.85rem 1.2rem;">
                      <div style="display:flex;gap:0.4rem;">
                        <button onclick="bhApprove(<?php echo $pu->ID; ?>)" style="padding:0.3rem 0.75rem;border-radius:20px;font-size:0.7rem;font-weight:700;cursor:pointer;border:none;background:rgba(76,175,80,0.12);color:#2e7d32;transition:all 0.2s;"><?php esc_html_e('Approve','baloch-heritage'); ?></button>
                        <button onclick="bhReject(<?php echo $pu->ID; ?>)" style="padding:0.3rem 0.75rem;border-radius:20px;font-size:0.7rem;font-weight:700;cursor:pointer;border:none;background:rgba(229,57,53,0.1);color:#c62828;transition:all 0.2s;"><?php esc_html_e('Reject','baloch-heritage'); ?></button>
                      </div>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
          <?php endif; ?>
        </div>

        <!-- PROFILE -->
        <div id="panel-profile" class="portal-panel" style="display:none;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown);margin-bottom:0.3rem;"><?php esc_html_e('My Profile','baloch-heritage'); ?></h2>
          <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;"><?php esc_html_e('Manage your personal information and account settings.','baloch-heritage'); ?></p>
          <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;">
            <div style="height:100px;background:linear-gradient(135deg,#561010,#C4622D);"></div>
            <div style="padding:0 1.5rem 1.5rem;">
              <div style="margin-top:-36px;margin-bottom:1rem;">
                <div style="width:72px;height:72px;border-radius:50%;background:var(--maroon);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;color:#fff;font-size:1.8rem;border:3px solid #fff;">
                  <?php echo strtoupper(substr($current_user->first_name ?: $current_user->display_name, 0, 1)); ?>
                </div>
              </div>
              <h2 style="font-family:'Playfair Display',serif;font-size:1.3rem;color:var(--brown);"><?php echo esc_html($current_user->display_name); ?></h2>
              <p style="color:var(--text-light);font-size:0.85rem;margin-bottom:1.5rem;"><?php echo esc_html($role_label); ?></p>
              <div style="display:grid;grid-template-columns:1fr 1fr;gap:1rem;margin-bottom:1.5rem;">
                <?php $fields = [
                  [__('Email','baloch-heritage'), $current_user->user_email],
                  [__('City','baloch-heritage'), get_user_meta($user_id,'bh_city',true) ?: '—'],
                  [__('Member Since','baloch-heritage'), date('F j, Y', strtotime($current_user->user_registered))],
                  [__('Status','baloch-heritage'), $user_status==='approved'?'✓ Active Member':'⏳ Pending Approval'],
                ];
                foreach ($fields as $f): ?>
                <div>
                  <label style="font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;display:block;margin-bottom:0.3rem;"><?php echo esc_html($f[0]); ?></label>
                  <p style="font-size:0.88rem;color:var(--brown);font-weight:600;"><?php echo esc_html($f[1]); ?></p>
                </div>
                <?php endforeach; ?>
              </div>
              <hr style="border:none;border-top:1px solid var(--cream-dark);margin:1.5rem 0;">
              <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:1rem;"><?php esc_html_e('Update Profile','baloch-heritage'); ?></h3>
              <div class="form-row">
                <div class="form-group"><label class="form-label"><?php esc_html_e('First Name','baloch-heritage'); ?></label><input class="form-control" id="editFirst" type="text" value="<?php echo esc_attr($current_user->first_name); ?>"></div>
                <div class="form-group"><label class="form-label"><?php esc_html_e('Last Name','baloch-heritage'); ?></label><input class="form-control" id="editLast" type="text" value="<?php echo esc_attr($current_user->last_name); ?>"></div>
              </div>
              <div class="form-group"><label class="form-label"><?php esc_html_e('City / Province','baloch-heritage'); ?></label><input class="form-control" id="editCity" type="text" value="<?php echo esc_attr(get_user_meta($user_id,'bh_city',true)); ?>"></div>
              <div class="form-group"><label class="form-label"><?php esc_html_e('New Password (leave blank to keep current)','baloch-heritage'); ?></label><input class="form-control" id="editPass" type="password" placeholder="<?php esc_attr_e('New password...','baloch-heritage'); ?>"></div>
              <button class="btn-primary" onclick="bhSaveProfile()"><?php esc_html_e('Save Changes','baloch-heritage'); ?></button>
            </div>
          </div>
        </div>

        <!-- EVENTS -->
        <div id="panel-events" class="portal-panel" style="display:none;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown);margin-bottom:0.3rem;"><?php esc_html_e('My Events','baloch-heritage'); ?></h2>
          <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;"><?php esc_html_e('Upcoming events you\'ve registered for.','baloch-heritage'); ?></p>
          <?php
          // Get events where user has RSVP'd
          $rsvp_events = new WP_Query(['post_type'=>'bh_event','posts_per_page'=>10,'post_status'=>'publish','meta_key'=>'_bh_event_date','orderby'=>'meta_value','order'=>'ASC']);
          if ($rsvp_events->have_posts()):
            while ($rsvp_events->have_posts()): $rsvp_events->the_post();
              $rsvps = get_post_meta(get_the_ID(),'_bh_event_rsvps',true) ?: [];
              $user_rsvped = array_filter($rsvps, fn($r) => ($r['user_id']??0) == $user_id || ($r['email']??'') == $current_user->user_email);
              if (!$user_rsvped) continue;
              $d = new DateTime(get_post_meta(get_the_ID(),'_bh_event_date',true));
          ?>
          <div style="background:var(--white);border-radius:var(--radius-sm);padding:1rem 1.2rem;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:1.2rem;margin-bottom:0.8rem;">
            <div style="min-width:52px;text-align:center;background:var(--maroon);border-radius:var(--radius-sm);padding:0.5rem;">
              <div style="font-family:'Playfair Display',serif;font-size:1.4rem;font-weight:900;color:#fff;line-height:1;"><?php echo $d->format('d'); ?></div>
              <div style="font-size:0.65rem;font-weight:700;color:rgba(255,255,255,0.7);text-transform:uppercase;"><?php echo $d->format('M'); ?></div>
            </div>
            <div style="flex:1;">
              <h4 style="font-size:0.92rem;font-weight:700;color:var(--brown);margin-bottom:0.2rem;"><?php the_title(); ?></h4>
              <p style="font-size:0.78rem;color:var(--text-light);"><?php echo esc_html(get_post_meta(get_the_ID(),'_bh_event_location',true)); ?></p>
            </div>
            <span style="background:rgba(76,175,80,0.12);color:#2e7d32;font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;"><?php esc_html_e('Registered','baloch-heritage'); ?></span>
          </div>
          <?php endwhile; wp_reset_postdata();
          else: ?>
          <p style="color:var(--text-light);"><?php esc_html_e('You have not RSVPed to any upcoming events yet.','baloch-heritage'); ?></p>
          <?php endif; ?>
          <div style="margin-top:1.5rem;">
            <a href="<?php echo home_url('/events/'); ?>" class="btn-outline"><?php esc_html_e('Browse All Events','baloch-heritage'); ?></a>
          </div>
        </div>

        <?php if ($can_approve): ?>
        <!-- ALL MEMBERS -->
        <div id="panel-members" class="portal-panel" style="display:none;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown);margin-bottom:0.3rem;"><?php esc_html_e('All Members','baloch-heritage'); ?></h2>
          <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;"><?php esc_html_e('Manage and view all registered community members.','baloch-heritage'); ?></p>
          <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;">
            <div style="padding:1.2rem 1.5rem;display:flex;align-items:center;justify-content:space-between;border-bottom:1px solid var(--cream-dark);">
              <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);"><?php printf(esc_html__('%d Members','baloch-heritage'), count($all_members)); ?></h3>
              <input type="search" placeholder="<?php esc_attr_e('Search members...','baloch-heritage'); ?>" oninput="bhFilterMembers(this.value)"
                style="padding:0.45rem 0.8rem;border:1.5px solid rgba(122,28,28,0.2);border-radius:var(--radius-sm);font-size:0.82rem;outline:none;">
            </div>
            <div style="overflow-x:auto;">
              <table style="width:100%;border-collapse:collapse;" id="membersTable">
                <thead><tr style="background:var(--cream);">
                  <?php foreach([__('Name','baloch-heritage'),__('Email','baloch-heritage'),__('City','baloch-heritage'),__('Role','baloch-heritage'),__('Status','baloch-heritage'),__('Joined','baloch-heritage'),__('Actions','baloch-heritage')] as $th): ?>
                  <th style="padding:0.7rem 1.2rem;text-align:left;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php echo esc_html($th); ?></th>
                  <?php endforeach; ?>
                </tr></thead>
                <tbody id="membersTableBody">
                  <?php foreach ($all_members as $mu):
                    $mrole   = bh_get_role($mu->ID);
                    $mstatus = get_user_meta($mu->ID,'bh_member_status',true) ?: 'pending';
                    $mlabel  = bh_get_user_role_label($mu->ID);
                    $statusColors = ['approved'=>'#2e7d32','rejected'=>'#c62828','pending'=>'#E07B39'];
                  ?>
                  <tr style="border-bottom:1px solid var(--cream-dark);" data-name="<?php echo esc_attr(strtolower($mu->display_name)); ?>" data-email="<?php echo esc_attr(strtolower($mu->user_email)); ?>">
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;">
                      <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--maroon);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;flex-shrink:0;"><?php echo strtoupper(substr($mu->display_name,0,1)); ?></div>
                        <?php echo esc_html($mu->display_name); ?>
                      </div>
                    </td>
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);"><?php echo esc_html($mu->user_email); ?></td>
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);"><?php echo esc_html(get_user_meta($mu->ID,'bh_city',true) ?: '—'); ?></td>
                    <td style="padding:0.85rem 1.2rem;"><span style="background:rgba(122,28,28,0.1);color:var(--maroon);font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;"><?php echo esc_html($mlabel); ?></span></td>
                    <td style="padding:0.85rem 1.2rem;"><span style="color:<?php echo esc_attr($statusColors[$mstatus]??'#888'); ?>;font-size:0.78rem;font-weight:700;text-transform:capitalize;"><?php echo esc_html($mstatus); ?></span></td>
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;color:var(--text);"><?php echo date('M j, Y', strtotime($mu->user_registered)); ?></td>
                    <td style="padding:0.85rem 1.2rem;">
                      <?php if ($mstatus === 'pending'): ?>
                      <div style="display:flex;gap:0.4rem;">
                        <button onclick="bhApprove(<?php echo $mu->ID; ?>)" style="padding:0.3rem 0.7rem;border-radius:20px;font-size:0.7rem;font-weight:700;cursor:pointer;border:none;background:rgba(76,175,80,0.12);color:#2e7d32;"><?php esc_html_e('Approve','baloch-heritage'); ?></button>
                        <button onclick="bhReject(<?php echo $mu->ID; ?>)" style="padding:0.3rem 0.7rem;border-radius:20px;font-size:0.7rem;font-weight:700;cursor:pointer;border:none;background:rgba(229,57,53,0.1);color:#c62828;"><?php esc_html_e('Reject','baloch-heritage'); ?></button>
                      </div>
                      <?php endif; ?>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <!-- APPROVALS -->
        <div id="panel-approvals" class="portal-panel" style="display:none;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown);margin-bottom:0.3rem;"><?php esc_html_e('Pending Approvals','baloch-heritage'); ?></h2>
          <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;"><?php esc_html_e('Review and approve or reject new member applications.','baloch-heritage'); ?></p>
          <div id="approvalsContent">
            <?php if (empty($pending_users)): ?>
            <div style="text-align:center;padding:3rem;background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);">
              <div style="font-size:2.5rem;margin-bottom:1rem;">✓</div>
              <h3 style="font-family:'Playfair Display',serif;color:var(--brown);"><?php esc_html_e('All caught up!','baloch-heritage'); ?></h3>
              <p style="color:var(--text-light);"><?php esc_html_e('No pending applications at this time.','baloch-heritage'); ?></p>
            </div>
            <?php else: ?>
            <?php foreach ($pending_users as $pu): ?>
            <div style="background:var(--white);border-radius:var(--radius);padding:1.5rem;box-shadow:var(--shadow-sm);display:flex;align-items:center;gap:1.5rem;margin-bottom:1rem;" id="approval-card-<?php echo $pu->ID; ?>">
              <div style="width:56px;height:56px;border-radius:50%;background:var(--maroon);display:flex;align-items:center;justify-content:center;font-family:'Playfair Display',serif;font-weight:700;color:#fff;font-size:1.3rem;flex-shrink:0;">
                <?php echo strtoupper(substr($pu->display_name,0,1)); ?>
              </div>
              <div style="flex:1;">
                <h4 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);margin-bottom:0.2rem;"><?php echo esc_html($pu->display_name); ?></h4>
                <p style="font-size:0.82rem;color:var(--text-light);">
                  <?php echo esc_html($pu->user_email); ?> · <?php echo esc_html(get_user_meta($pu->ID,'bh_city',true) ?: '—'); ?> ·
                  <?php printf(esc_html__('Applied %s','baloch-heritage'), date('M j, Y', strtotime($pu->user_registered))); ?>
                </p>
                <?php $intro = get_user_meta($pu->ID,'bh_intro',true); if ($intro): ?>
                <p style="font-size:0.78rem;color:var(--text-light);margin-top:0.4rem;font-style:italic;">"<?php echo esc_html(wp_trim_words($intro,25)); ?>"</p>
                <?php endif; ?>
              </div>
              <div style="display:flex;gap:0.5rem;flex-shrink:0;">
                <button onclick="bhApprove(<?php echo $pu->ID; ?>)" class="btn-sm" style="background:#4caf50;">✓ <?php esc_html_e('Approve','baloch-heritage'); ?></button>
                <button onclick="bhReject(<?php echo $pu->ID; ?>)" class="btn-sm" style="background:#e53935;">✕ <?php esc_html_e('Reject','baloch-heritage'); ?></button>
              </div>
            </div>
            <?php endforeach; endif; ?>
          </div>
        </div>
        <?php endif; ?>

        <?php if ($is_admin): ?>
        <!-- ADMIN PANEL -->
        <div id="panel-admin" class="portal-panel" style="display:none;">
          <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;color:var(--brown);margin-bottom:0.3rem;"><?php esc_html_e('Admin Panel','baloch-heritage'); ?></h2>
          <p style="color:var(--text-light);font-size:0.88rem;margin-bottom:1.5rem;"><?php esc_html_e('Full system management — roles, content, and site settings.','baloch-heritage'); ?></p>

          <?php
          $all_users = get_users(['number'=>200]);
          $admin_stats = [
            [__('Total Users','baloch-heritage'), count($all_users)],
            [__('Approved','baloch-heritage'), count(get_users(['role__in'=>['bh_member','bh_moderator','bh_board','administrator']])),'#2e7d32'],
            [__('Pending','baloch-heritage'), count($pending_users),'var(--orange)'],
            [__('Rejected','baloch-heritage'), count(array_filter($all_users, fn($u)=>get_user_meta($u->ID,'bh_member_status',true)==='rejected')),'#c62828'],
          ];
          ?>
          <div style="display:grid;grid-template-columns:repeat(4,1fr);gap:1rem;margin-bottom:2rem;">
            <?php foreach ($admin_stats as $s): ?>
            <div style="background:var(--white);border-radius:var(--radius);padding:1.2rem 1.4rem;box-shadow:var(--shadow-sm);">
              <div style="font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.1em;margin-bottom:0.4rem;"><?php echo esc_html($s[0]); ?></div>
              <div style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:900;color:<?php echo esc_attr($s[2]??'var(--brown)'); ?>;"><?php echo esc_html($s[1]); ?></div>
            </div>
            <?php endforeach; ?>
          </div>

          <div style="background:var(--white);border-radius:var(--radius);box-shadow:var(--shadow-sm);overflow:hidden;">
            <div style="padding:1.2rem 1.5rem;border-bottom:1px solid var(--cream-dark);">
              <h3 style="font-family:'Playfair Display',serif;font-size:1rem;color:var(--brown);"><?php esc_html_e('Role Management','baloch-heritage'); ?></h3>
            </div>
            <div style="overflow-x:auto;">
              <table style="width:100%;border-collapse:collapse;">
                <thead><tr style="background:var(--cream);">
                  <?php foreach([__('Member','baloch-heritage'),__('Current Role','baloch-heritage'),__('Change Role','baloch-heritage'),__('Action','baloch-heritage')] as $th): ?>
                  <th style="padding:0.7rem 1.2rem;text-align:left;font-size:0.72rem;font-weight:700;color:var(--text-light);text-transform:uppercase;letter-spacing:0.08em;"><?php echo esc_html($th); ?></th>
                  <?php endforeach; ?>
                </tr></thead>
                <tbody>
                  <?php foreach (get_users(['role__in'=>['bh_member','bh_moderator','bh_board','administrator']]) as $au):
                    $arole = bh_get_role($au->ID);
                    $alabel= bh_get_user_role_label($au->ID);
                  ?>
                  <tr style="border-bottom:1px solid var(--cream-dark);">
                    <td style="padding:0.85rem 1.2rem;font-size:0.82rem;">
                      <div style="display:flex;align-items:center;gap:0.5rem;">
                        <div style="width:28px;height:28px;border-radius:50%;background:var(--maroon);display:flex;align-items:center;justify-content:center;color:#fff;font-size:0.7rem;font-weight:700;flex-shrink:0;"><?php echo strtoupper(substr($au->display_name,0,1)); ?></div>
                        <?php echo esc_html($au->display_name); ?>
                      </div>
                    </td>
                    <td style="padding:0.85rem 1.2rem;"><span style="background:rgba(122,28,28,0.1);color:var(--maroon);font-size:0.7rem;font-weight:700;padding:0.2rem 0.6rem;border-radius:20px;"><?php echo esc_html($alabel); ?></span></td>
                    <td style="padding:0.85rem 1.2rem;">
                      <select id="role-select-<?php echo $au->ID; ?>" style="padding:0.3rem 0.5rem;border:1px solid var(--cream-dark);border-radius:3px;font-size:0.8rem;">
                        <?php foreach(['bh_member'=>__('Member','baloch-heritage'),'bh_moderator'=>__('Moderator','baloch-heritage'),'bh_board'=>__('Board Member','baloch-heritage')] as $rv=>$rl): ?>
                        <option value="<?php echo esc_attr($rv); ?>" <?php selected($arole,$rv); ?>><?php echo esc_html($rl); ?></option>
                        <?php endforeach; ?>
                      </select>
                    </td>
                    <td style="padding:0.85rem 1.2rem;">
                      <button onclick="bhChangeRole(<?php echo $au->ID; ?>)" style="padding:0.3rem 0.75rem;border-radius:var(--radius-sm);font-size:0.7rem;font-weight:700;cursor:pointer;border:none;background:rgba(122,28,28,0.08);color:var(--maroon);transition:all 0.2s;"><?php esc_html_e('Update','baloch-heritage'); ?></button>
                    </td>
                  </tr>
                  <?php endforeach; ?>
                </tbody>
              </table>
            </div>
          </div>
        </div>
        <?php endif; ?>

      </div><!-- /portal-main -->
    </div>
  </div>
</section>

<style>
.portal-nav-btn.active { background:rgba(122,28,28,0.06) !important; color:var(--maroon) !important; border-left-color:var(--maroon) !important; font-weight:700 !important; }
.portal-nav-btn:hover { background:var(--cream) !important; color:var(--brown) !important; }
</style>

<script>
function bhShowPanel(id, btn) {
  document.querySelectorAll('.portal-panel').forEach(p => p.style.display = 'none');
  document.getElementById('panel-' + id).style.display = 'block';
  document.querySelectorAll('.portal-nav-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');
}
function bhApprove(userId) {
  fetch(bhAjax.url, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_approve_member_ajax&nonce='+bhAjax.nonce+'&user_id='+userId
  }).then(r=>r.json()).then(d => {
    if (d.success) {
      ['pending-row-','approval-card-'].forEach(prefix => {
        const el = document.getElementById(prefix + userId);
        if (el) el.style.opacity = '0.4';
      });
      showNotification(d.data.message, 'success');
    } else showNotification(d.data.message, 'error');
  });
}
function bhReject(userId) {
  if (!confirm('<?php esc_html_e('Reject this application?','baloch-heritage'); ?>')) return;
  fetch(bhAjax.url, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_reject_member_ajax&nonce='+bhAjax.nonce+'&user_id='+userId
  }).then(r=>r.json()).then(d => {
    if (d.success) {
      ['pending-row-','approval-card-'].forEach(prefix => {
        const el = document.getElementById(prefix + userId);
        if (el) el.remove();
      });
      showNotification(d.data.message, 'error');
    } else showNotification(d.data.message, 'error');
  });
}
function bhSaveProfile() {
  fetch(bhAjax.url, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_update_profile&nonce='+bhAjax.nonce
      +'&first_name='+encodeURIComponent(document.getElementById('editFirst').value)
      +'&last_name='+encodeURIComponent(document.getElementById('editLast').value)
      +'&city='+encodeURIComponent(document.getElementById('editCity').value)
      +'&new_password='+encodeURIComponent(document.getElementById('editPass').value)
  }).then(r=>r.json()).then(d=>showNotification(d.data.message, d.success?'success':'error'));
}
function bhChangeRole(userId) {
  const role = document.getElementById('role-select-' + userId).value;
  fetch(bhAjax.url, {
    method:'POST', headers:{'Content-Type':'application/x-www-form-urlencoded'},
    body:'action=bh_change_role&nonce='+bhAjax.nonce+'&user_id='+userId+'&role='+encodeURIComponent(role)
  }).then(r=>r.json()).then(d=>showNotification(d.data.message, d.success?'success':'error'));
}
function bhFilterMembers(val) {
  const v = val.toLowerCase();
  document.querySelectorAll('#membersTableBody tr').forEach(row => {
    const name  = row.dataset.name  || '';
    const email = row.dataset.email || '';
    row.style.display = (!v || name.includes(v) || email.includes(v)) ? '' : 'none';
  });
}
</script>

<?php get_footer(); ?>
