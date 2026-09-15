<?php
defined('ABSPATH') || exit;
require_once __DIR__ . '/class-cbcsc-base.php';

class Class_CBCSC_Gallery extends Class_CBCSC_Widget_Base {
    public function get_name()  { return 'cbcsc_gallery'; }
    public function get_title() { return __('Gallery Masonry', 'baloch-heritage'); }
    public function get_icon()  { return 'eicon-gallery-masonry'; }

    protected function register_controls() {
        $this->start_controls_section('content', ['label' => __('Content', 'baloch-heritage')]);
        $this->add_control('limit', ['label' => __('Limit', 'baloch-heritage'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 12]);
        $this->add_control('columns', ['label' => __('Columns', 'baloch-heritage'), 'type' => \Elementor\Controls_Manager::NUMBER, 'default' => 3, 'min' => 1, 'max' => 6]);

        $terms = get_terms(['taxonomy' => 'bh_gallery_cat', 'hide_empty' => false]);
        $choices = ['' => __('All categories', 'baloch-heritage')];
        if (!is_wp_error($terms)) foreach ($terms as $t) $choices[$t->slug] = $t->name;

        $this->add_control('category', [
            'label'   => __('Category', 'baloch-heritage'),
            'type'    => \Elementor\Controls_Manager::SELECT,
            'options' => $choices,
        ]);
        $this->end_controls_section();
    }

    protected function render() {
        $s     = $this->get_settings_for_display();
        $args  = ['post_type' => 'bh_gallery', 'posts_per_page' => (int)$s['limit'], 'post_status' => 'publish'];
        if ($s['category']) $args['tax_query'] = [['taxonomy' => 'bh_gallery_cat', 'field' => 'slug', 'terms' => $s['category']]];
        $q = new WP_Query($args);
        if (!$q->have_posts()) {
            echo '<p>' . esc_html__('No gallery items yet.', 'baloch-heritage') . '</p>';
            return;
        }
        $cols = max(1, min(6, (int)$s['columns']));
        echo '<div class="cbcsc-gallery" style="column-count:' . $cols . ';column-gap:0.75rem;">';
        while ($q->have_posts()) { $q->the_post();
            echo '<a href="' . esc_url(get_the_permalink()) . '" style="display:block;margin-bottom:0.75rem;break-inside:avoid;border-radius:6px;overflow:hidden;">';
            echo has_post_thumbnail() ? get_the_post_thumbnail(null, 'medium', ['style' => 'width:100%;display:block;']) : '<div style="aspect-ratio:1;background:var(--sand);"></div>';
            echo '</a>';
        }
        echo '</div>';
        wp_reset_postdata();
    }
}
