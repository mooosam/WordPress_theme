<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Rsvp extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_rsvp'; }
    public function get_title() { return __('RSVP Form', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-form-horizontal'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Event', 'baloch-heritage')]);

        $events = get_posts(['post_type' => 'bh_event', 'numberposts' => 100, 'post_status' => 'publish']);
        $choices = ['' => __('— Current event in URL —', 'baloch-heritage')];
        foreach ($events as $ev) $choices[$ev->ID] = $ev->post_title;

        $this->add_control('event_id', [
            'label'   => __('Event', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => $choices,
            'default' => '',
        ]);
        $this->add_control('button_text', [
            'label'   => __('Button label', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __('RSVP', 'baloch-heritage'),
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s        = $this->get_settings_for_display();
        $event_id = (int) ($s['event_id'] ?: get_the_ID());
        $title    = $event_id ? get_the_title($event_id) : '';
        $btn      = esc_html($s['button_text']);
        ?>
        <form class="cbcsc-rsvp-form" data-event="<?php echo esc_attr($event_id); ?>" onsubmit="return cbcscRsvp(event,this);">
            <?php if ($title): ?><h4><?php echo esc_html($title); ?></h4><?php endif; ?>
            <input type="text"  name="name"  placeholder="<?php esc_attr_e('Your name', 'baloch-heritage'); ?>" required>
            <input type="email" name="email" placeholder="<?php esc_attr_e('Email',  'baloch-heritage'); ?>" required>
            <button type="submit" class="btn-primary"><?php echo $btn; ?></button>
        </form>
        <script>
        if (!window.cbcscRsvp) window.cbcscRsvp = function (e, form) {
            e.preventDefault();
            const fd = new FormData(form);
            fd.append('action','bh_event_rsvp');
            fd.append('nonce', (window.bhAjax||{}).nonce || '');
            fd.append('event_id', form.dataset.event);
            fetch((window.bhAjax||{}).url||'/wp-admin/admin-ajax.php', {method:'POST',body:fd}).then(r=>r.json()).then(d=>{
                alert(d.data && d.data.message ? d.data.message : 'Submitted.');
                if (d.success) form.reset();
            });
            return false;
        };
        </script>
        <?php
    }
}
