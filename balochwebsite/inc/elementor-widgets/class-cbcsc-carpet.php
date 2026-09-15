<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Carpet extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_carpet'; }
    public function get_title() { return __('Carpet Border', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-spacer'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Style', 'baloch-heritage')]);
        $this->add_control('bg_color', [
            'label'   => __('Background color', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::COLOR,
            'default' => '#561010',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        echo bh_carpet_border($s['bg_color'] ?: '#561010');
    }
}
