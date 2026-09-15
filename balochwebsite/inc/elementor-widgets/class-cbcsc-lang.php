<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Lang extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_lang'; }
    public function get_title() { return __('Language Switch', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-site-search'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('en_label', ['label' => 'EN label', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'EN']);
        $this->add_control('bal_label',['label' => 'Balochi label', 'type' => \Elementor\Controls_Manager::TEXT, 'default' => 'بلوچی']);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        ?>
        <button class="cbcsc-lang-toggle" onclick="cbcscLangToggle(this)" style="display:inline-flex;align-items:center;gap:0.4rem;background:transparent;border:1.5px solid currentColor;border-radius:20px;padding:0.3rem 0.75rem;font-weight:700;font-size:0.85rem;cursor:pointer;">
            <span class="en"><?php echo esc_html($s['en_label']); ?></span>
            <span style="opacity:0.5;">·</span>
            <span class="bal" style="font-family:'Noto Nastaliq Urdu',serif;"><?php echo esc_html($s['bal_label']); ?></span>
        </button>
        <script>
        if (!window.cbcscLangToggle) window.cbcscLangToggle = function(btn){
            const cur = localStorage.getItem('cbcsc_lang') || 'en';
            const next = cur === 'en' ? 'bal' : 'en';
            localStorage.setItem('cbcsc_lang', next);
            document.documentElement.setAttribute('data-lang', next);
            document.dispatchEvent(new CustomEvent('cbcscLangChange',{detail:next}));
        };
        </script>
        <?php
    }
}
