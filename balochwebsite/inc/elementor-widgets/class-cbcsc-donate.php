<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Donate extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_donate'; }
    public function get_title() { return __('Donation CTA', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-heart'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('heading', ['label' => __('Heading', 'baloch-heritage'), 'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('Support our community', 'baloch-heritage')]);
        $this->add_control('subhead', ['label' => __('Subhead', 'baloch-heritage'), 'type' => \Elementor\Controls_Manager::TEXTAREA, 'default' => __('Your contribution funds youth programs, language classes, and cultural events.', 'baloch-heritage')]);
        $this->add_control('goal',    ['label' => __('Goal (CAD)', 'baloch-heritage'),    'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 10000]);
        $this->add_control('raised',  ['label' => __('Raised so far', 'baloch-heritage'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 6200]);
        $this->add_control('button_text', ['label' => __('Button label', 'baloch-heritage'),'type' => \Elementor\Controls_Manager::TEXT, 'default' => __('Donate now', 'baloch-heritage')]);
        $this->add_control('button_url',  ['label' => __('Button URL', 'baloch-heritage'),  'type' => \Elementor\Controls_Manager::URL,  'default' => ['url' => home_url('/donate')]]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $goal   = max(1, (int)$s['goal']);
        $raised = max(0, (int)$s['raised']);
        $pct    = min(100, round($raised / $goal * 100));
        $url    = $s['button_url']['url'] ?? home_url('/donate');
        ?>
        <div class="cbcsc-donate" style="background:var(--maroon);color:var(--cream);padding:2rem;border-radius:var(--bh-radius,8px);">
            <h3 style="font-family:var(--bh-font-display);font-size:1.5rem;margin-bottom:0.5rem;"><?php echo esc_html($s['heading']); ?></h3>
            <p style="opacity:0.85;margin-bottom:1.25rem;"><?php echo esc_html($s['subhead']); ?></p>
            <div style="background:rgba(255,255,255,0.18);border-radius:6px;height:12px;overflow:hidden;margin-bottom:0.5rem;">
                <div style="width:<?php echo $pct; ?>%;background:var(--orange);height:100%;border-radius:6px;transition:width 1s;"></div>
            </div>
            <div style="display:flex;justify-content:space-between;font-size:0.85rem;opacity:0.85;margin-bottom:1.25rem;">
                <span>$<?php echo number_format($raised); ?> raised</span>
                <span>$<?php echo number_format($goal); ?> goal</span>
            </div>
            <a href="<?php echo esc_url($url); ?>" class="btn-primary" style="background:var(--orange);color:#fff;display:inline-block;padding:0.75rem 1.5rem;border-radius:6px;text-decoration:none;font-weight:700;"><?php echo esc_html($s['button_text']); ?></a>
        </div>
        <?php
    }
}
