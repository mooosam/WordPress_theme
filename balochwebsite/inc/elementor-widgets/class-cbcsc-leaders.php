<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Leaders extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_leaders'; }
    public function get_title() { return __('Leadership Grid', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-person'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('limit', [
            'label'   => __('Limit (-1 = all)', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => -1,
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        echo do_shortcode('[bh_leaders limit="' . (int)$s['limit'] . '"]');
    }
}
