<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Login extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_member_login'; }
    public function get_title() { return __('Member Login', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-lock-user'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('redirect_url', [
            'label'   => __('Redirect after login', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::URL,
            'default' => ['url' => home_url('/members/')],
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        if (is_user_logged_in()) {
            $user = wp_get_current_user();
            echo '<div class="cbcsc-login"><p>' . sprintf(esc_html__('You are signed in as %s.', 'baloch-heritage'), esc_html($user->display_name)) . '</p>';
            echo '<a href="' . esc_url(wp_logout_url(home_url())) . '" class="btn-outline">' . esc_html__('Log out', 'baloch-heritage') . '</a></div>';
            return;
        }
        $s   = $this->get_settings_for_display();
        $redirect = $s['redirect_url']['url'] ?? home_url('/members/');
        ?>
        <form class="cbcsc-login-form" method="post" action="<?php echo esc_url(wp_login_url($redirect)); ?>">
            <input type="text"     name="log" placeholder="<?php esc_attr_e('Email or username', 'baloch-heritage'); ?>" required>
            <input type="password" name="pwd" placeholder="<?php esc_attr_e('Password', 'baloch-heritage'); ?>" required>
            <label style="display:flex;align-items:center;gap:0.5rem;font-size:0.85rem;">
                <input type="checkbox" name="rememberme" value="forever"> <?php esc_html_e('Remember me', 'baloch-heritage'); ?>
            </label>
            <input type="hidden" name="redirect_to" value="<?php echo esc_url($redirect); ?>">
            <button type="submit" class="btn-primary"><?php esc_html_e('Sign in', 'baloch-heritage'); ?></button>
            <p><a href="<?php echo esc_url(home_url('/join/')); ?>"><?php esc_html_e('Apply for membership →', 'baloch-heritage'); ?></a></p>
        </form>
        <?php
    }
}
