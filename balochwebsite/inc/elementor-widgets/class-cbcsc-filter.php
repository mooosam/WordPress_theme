<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Filter extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_filter_bar'; }
    public function get_title() { return __('CPT Filter Bar', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-filter'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('taxonomy', [
            'label'   => __('Taxonomy', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => [
                'bh_event_type'       => 'Event Types',
                'bh_article_category' => 'Article Categories',
                'bh_resource_cat'     => 'Resource Categories',
                'bh_gallery_cat'      => 'Gallery Categories',
            ],
            'default' => 'bh_event_type',
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s = $this->get_settings_for_display();
        $terms = get_terms(['taxonomy' => $s['taxonomy'], 'hide_empty' => false]);
        if (is_wp_error($terms) || !$terms) {
            echo '<p>' . esc_html__('No terms in this taxonomy.', 'baloch-heritage') . '</p>';
            return;
        }
        $current = get_query_var('term') ?: '';
        echo '<div class="cbcsc-filter" style="display:flex;flex-wrap:wrap;gap:0.5rem;">';
        echo '<a class="filter-pill ' . ($current === '' ? 'active' : '') . '" href="?term=" style="padding:0.4rem 0.85rem;border:1.5px solid var(--maroon);border-radius:20px;font-size:0.8rem;font-weight:700;text-decoration:none;color:var(--maroon);">' . esc_html__('All', 'baloch-heritage') . '</a>';
        foreach ($terms as $t) {
            $active = $current === $t->slug;
            echo '<a class="filter-pill" href="?term=' . esc_attr($t->slug) . '" style="padding:0.4rem 0.85rem;border:1.5px solid var(--maroon);border-radius:20px;font-size:0.8rem;font-weight:700;text-decoration:none;color:' . ($active ? '#fff' : 'var(--maroon)') . ';background:' . ($active ? 'var(--maroon)' : 'transparent') . ';">' . esc_html($t->name) . '</a>';
        }
        echo '</div>';
    }
}
