<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Events extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_events_grid'; }
    public function get_title() { return __('Events Grid', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-calendar'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);

        $this->add_control('limit', [
            'label'   => __('How many events', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::NUMBER,
            'default' => 3,
            'min'     => 1, 'max' => 24,
        ]);
        $this->add_control('upcoming', [
            'label'   => __('Upcoming only', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);
        $this->add_control('show_rsvp', [
            'label'   => __('Show RSVP button', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::SWITCHER,
            'default' => 'yes',
        ]);

        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        echo do_shortcode(sprintf(
            '[bh_events limit="%d" upcoming="%s"]',
            (int)$s['limit'],
            $s['upcoming'] === 'yes' ? 'yes' : 'no'
        ));
    }
}
