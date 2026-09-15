<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Quote extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_quote'; }
    public function get_title() { return __('Story Quote', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-blockquote'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('text', [
            'label'   => __('Quote text', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::TEXTAREA,
            'default' => __('The Baloch carry their homeland in their hearts, wherever they go.', 'baloch-heritage'),
        ]);
        $this->add_control('attribution', [
            'label'   => __('Attribution', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::TEXT,
            'default' => __('— Community Elder, Toronto', 'baloch-heritage'),
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <blockquote class="cbcsc-quote" style="position:relative;padding:1.5rem 0 1.5rem 2.5rem;border-left:3px solid var(--orange);">
            <span style="position:absolute;left:0.25rem;top:-0.5rem;font-family:var(--bh-font-display);font-size:4rem;line-height:1;color:var(--orange);opacity:0.4;">&ldquo;</span>
            <p style="font-family:var(--bh-font-display);font-size:1.25rem;color:var(--brown);font-style:italic;line-height:1.5;"><?php echo esc_html($s['text']); ?></p>
            <cite style="display:block;font-size:0.85rem;color:var(--terra);margin-top:0.75rem;font-style:normal;font-weight:700;"><?php echo esc_html($s['attribution']); ?></cite>
        </blockquote>
        <?php
    }
}
