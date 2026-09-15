<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Newsletter extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_newsletter'; }
    public function get_title() { return __('Newsletter Form', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-email-field'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('heading', [
            'label'   => __('Heading', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __('Join our newsletter', 'baloch-heritage'),
        ]);
        $this->add_control('button', [
            'label'   => __('Button', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __('Subscribe', 'baloch-heritage'),
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <form class="cbcsc-newsletter-form" onsubmit="return cbcscNewsletter(event,this);">
            <h4><?php echo esc_html($s['heading']); ?></h4>
            <input type="email" name="email" placeholder="<?php esc_attr_e('Your email', 'baloch-heritage'); ?>" required>
            <button type="submit" class="btn-primary"><?php echo esc_html($s['button']); ?></button>
        </form>
        <script>
        if (!window.cbcscNewsletter) window.cbcscNewsletter = function(e, f){
            e.preventDefault();
            const fd = new FormData(f); fd.append('action','bh_newsletter');
            fd.append('nonce',(window.bhAjax||{}).nonce||'');
            fetch((window.bhAjax||{}).url||'/wp-admin/admin-ajax.php',{method:'POST',body:fd}).then(r=>r.json()).then(d=>{
                alert(d.data?.message||'Subscribed.'); if (d.success) f.reset();
            });
            return false;
        };
        </script>
        <?php
    }
}
