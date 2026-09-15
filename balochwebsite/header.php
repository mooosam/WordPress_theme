<?php
/**
 * Baloch Heritage — header.php
 */
?><!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo('charset'); ?>">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<nav id="navbar">
  <a href="<?php echo home_url('/'); ?>" class="nav-logo">
    <div class="nav-logo-icon">
      <?php if (has_custom_logo()): the_custom_logo();
      else: ?>
      <svg viewBox="0 0 26 26" fill="none" width="26" height="26">
        <circle cx="13" cy="13" r="10" stroke="white" stroke-width="1.5"/>
        <path d="M7 13 Q10 7 13 13 Q16 19 19 13" stroke="white" stroke-width="1.5" fill="none"/>
        <circle cx="13" cy="13" r="2.5" fill="white"/>
      </svg>
      <?php endif; ?>
    </div>
    <div class="nav-logo-text">
      <span><?php bloginfo('name'); ?></span>
      <span>بلوچ ثقافتی</span>
    </div>
  </a>

  <?php
  wp_nav_menu([
    'theme_location' => 'primary',
    'container'      => false,
    'menu_class'     => 'nav-links',
    'items_wrap'     => '<ul class="nav-links">%3$s</ul>',
    'fallback_cb'    => 'bh_default_nav_menu',
  ]);
  ?>

  <div class="nav-icons">
    <?php if (is_user_logged_in()): ?>
    <a href="<?php echo home_url('/members/'); ?>" class="nav-member-btn" title="Member Portal">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
        <circle cx="8" cy="5.5" r="3" stroke="currentColor" stroke-width="1.5"/>
        <path d="M2 14c0-3.3 2.7-6 6-6s6 2.7 6 6" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
      </svg>
    </a>
    <?php else: ?>
    <a href="<?php echo home_url('/members/'); ?>" style="color:rgba(255,255,255,0.75);font-size:0.78rem;font-weight:700;text-decoration:none;padding:0.3rem 0.6rem;">Sign In</a>
    <?php endif; ?>
  </div>

  <button class="hamburger" id="hamburger" aria-label="<?php esc_attr_e('Menu', 'baloch-heritage'); ?>">
    <span></span><span></span><span></span>
  </button>
</nav>

<?php wp_nav_menu([
  'theme_location' => 'mobile',
  'container'      => 'div',
  'container_id'   => 'mobileMenu',
  'container_class'=> 'mobile-menu',
  'fallback_cb'    => 'bh_default_mobile_menu',
]); ?>

<?php
if (!function_exists('bh_default_nav_menu')):
function bh_default_nav_menu() {
  $pages = [
    'Home'      => home_url('/'),
    'Culture'   => home_url('/culture/'),
    'History'   => home_url('/history/'),
    'Art & Music'=> home_url('/gallery/'),
    'Community' => home_url('/news/'),
    'Events'    => home_url('/events/'),
    'Resources' => home_url('/resources/'),
    'About'     => home_url('/about/'),
  ];
  echo '<ul class="nav-links">';
  foreach ($pages as $label => $url) {
    $active = (get_permalink() === $url) ? ' class="active"' : '';
    echo "<li><a href='" . esc_url($url) . "'{$active}>" . esc_html($label) . "</a></li>";
  }
  echo '<li><a href="' . esc_url(home_url('/join/')) . '" class="nav-cta">Join Us</a></li>';
  echo '</ul>';
}
endif;

if (!function_exists('bh_default_mobile_menu')):
function bh_default_mobile_menu() {
  $pages = ['Home'=>'/','Culture'=>'/culture/','History'=>'/history/','Art & Music'=>'/gallery/','Community'=>'/news/','Events'=>'/events/','Resources'=>'/resources/','About'=>'/about/','Contact'=>'/contact/'];
  echo '<div class="mobile-menu" id="mobileMenu">';
  foreach ($pages as $label => $slug) echo '<a href="' . esc_url(home_url($slug)) . '">' . esc_html($label) . '</a>';
  echo '<a href="' . esc_url(home_url('/members/')) . '">Member Portal</a>';
  echo '<a href="' . esc_url(home_url('/join/')) . '" style="color:var(--orange);font-weight:700;">Join Us →</a>';
  echo '</div>';
}
endif;
?>
