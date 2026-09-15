<?php
/**
 * Baloch Heritage — Custom Post Types & Taxonomies
 */
defined('ABSPATH') || exit;

function bh_register_post_types() {

    /* ── EVENTS ── */
    register_post_type('bh_event', [
        'labels' => [
            'name'               => __('Events', 'baloch-heritage'),
            'singular_name'      => __('Event', 'baloch-heritage'),
            'add_new'            => __('Add Event', 'baloch-heritage'),
            'add_new_item'       => __('Add New Event', 'baloch-heritage'),
            'edit_item'          => __('Edit Event', 'baloch-heritage'),
            'all_items'          => __('All Events', 'baloch-heritage'),
            'search_items'       => __('Search Events', 'baloch-heritage'),
            'menu_name'          => __('Events', 'baloch-heritage'),
        ],
        'public'              => true,
        'has_archive'         => true,
        'rewrite'             => ['slug' => 'events'],
        'supports'            => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'        => true,
        'menu_icon'           => 'dashicons-calendar-alt',
        'menu_position'       => 5,
        'capability_type'     => 'post',
        'taxonomies'          => ['bh_event_type'],
    ]);

    /* ── ARTICLES / NEWS ── */
    register_post_type('bh_article', [
        'labels' => [
            'name'          => __('Articles', 'baloch-heritage'),
            'singular_name' => __('Article', 'baloch-heritage'),
            'add_new'       => __('Add Article', 'baloch-heritage'),
            'add_new_item'  => __('Add New Article', 'baloch-heritage'),
            'edit_item'     => __('Edit Article', 'baloch-heritage'),
            'all_items'     => __('All Articles', 'baloch-heritage'),
            'menu_name'     => __('Articles', 'baloch-heritage'),
        ],
        'public'          => true,
        'has_archive'     => true,
        'rewrite'         => ['slug' => 'community/articles'],
        'supports'        => ['title', 'editor', 'thumbnail', 'excerpt', 'author', 'comments'],
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-media-text',
        'menu_position'   => 6,
        'taxonomies'      => ['bh_article_category', 'post_tag'],
    ]);

    /* ── RESOURCES ── */
    register_post_type('bh_resource', [
        'labels' => [
            'name'          => __('Resources', 'baloch-heritage'),
            'singular_name' => __('Resource', 'baloch-heritage'),
            'add_new'       => __('Add Resource', 'baloch-heritage'),
            'add_new_item'  => __('Add New Resource', 'baloch-heritage'),
            'edit_item'     => __('Edit Resource', 'baloch-heritage'),
            'all_items'     => __('All Resources', 'baloch-heritage'),
            'menu_name'     => __('Resources', 'baloch-heritage'),
        ],
        'public'          => true,
        'has_archive'     => true,
        'rewrite'         => ['slug' => 'resources'],
        'supports'        => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-book',
        'menu_position'   => 7,
        'taxonomies'      => ['bh_resource_cat'],
    ]);

    /* ── LEADERSHIP / TEAM ── */
    register_post_type('bh_leadership', [
        'labels' => [
            'name'          => __('Leadership', 'baloch-heritage'),
            'singular_name' => __('Leader', 'baloch-heritage'),
            'add_new'       => __('Add Leader', 'baloch-heritage'),
            'add_new_item'  => __('Add New Leader', 'baloch-heritage'),
            'edit_item'     => __('Edit Leader', 'baloch-heritage'),
            'all_items'     => __('All Leaders', 'baloch-heritage'),
            'menu_name'     => __('Leadership', 'baloch-heritage'),
        ],
        'public'          => true,
        'has_archive'     => false,
        'rewrite'         => ['slug' => 'about/team'],
        'supports'        => ['title', 'editor', 'thumbnail', 'excerpt'],
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-businessperson',
        'menu_position'   => 8,
    ]);

    /* ── GALLERY ITEMS ── */
    register_post_type('bh_gallery', [
        'labels' => [
            'name'          => __('Gallery', 'baloch-heritage'),
            'singular_name' => __('Gallery Item', 'baloch-heritage'),
            'add_new'       => __('Add Gallery Item', 'baloch-heritage'),
            'all_items'     => __('All Gallery Items', 'baloch-heritage'),
            'menu_name'     => __('Gallery', 'baloch-heritage'),
        ],
        'public'          => true,
        'has_archive'     => true,
        'rewrite'         => ['slug' => 'gallery'],
        'supports'        => ['title', 'thumbnail', 'excerpt'],
        'show_in_rest'    => true,
        'menu_icon'       => 'dashicons-format-gallery',
        'menu_position'   => 9,
        'taxonomies'      => ['bh_gallery_cat'],
    ]);
}
add_action('init', 'bh_register_post_types');


/* ── TAXONOMIES ── */
function bh_register_taxonomies() {

    // Event Type
    register_taxonomy('bh_event_type', ['bh_event'], [
        'labels'            => ['name'=>__('Event Types','baloch-heritage'), 'singular_name'=>__('Event Type','baloch-heritage'), 'all_items'=>__('All Event Types','baloch-heritage')],
        'hierarchical'      => true,
        'show_in_rest'      => true,
        'rewrite'           => ['slug' => 'event-type'],
    ]);

    // Article Category
    register_taxonomy('bh_article_category', ['bh_article'], [
        'labels'       => ['name'=>__('Article Categories','baloch-heritage'), 'singular_name'=>__('Category','baloch-heritage'), 'all_items'=>__('All Categories','baloch-heritage')],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'community/category'],
    ]);

    // Resource Category
    register_taxonomy('bh_resource_cat', ['bh_resource'], [
        'labels'       => ['name'=>__('Resource Categories','baloch-heritage'), 'singular_name'=>__('Category','baloch-heritage'), 'all_items'=>__('All Categories','baloch-heritage')],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'resource-category'],
    ]);

    // Gallery Category
    register_taxonomy('bh_gallery_cat', ['bh_gallery'], [
        'labels'       => ['name'=>__('Gallery Categories','baloch-heritage'), 'singular_name'=>__('Category','baloch-heritage'), 'all_items'=>__('All Categories','baloch-heritage')],
        'hierarchical' => true,
        'show_in_rest' => true,
        'rewrite'      => ['slug' => 'gallery-category'],
    ]);
}
add_action('init', 'bh_register_taxonomies');


/* ── DEFAULT TERMS ── */
function bh_create_default_terms() {
    $event_types    = ['Cultural', 'Music & Arts', 'Youth & Education', 'Community', 'Sports', 'Religious'];
    $article_cats   = ['Language & Preservation', 'Culture & Traditions', 'Community Stories', 'Youth & Education', 'Events & Festivals', 'Voices of Balochistan'];
    $resource_cats  = ['Education', 'Language', 'Empowerment', 'Social Support', 'Downloads', 'Legal & Settlement'];
    $gallery_cats   = ['Embroidery & Doch', 'Homeland Landscapes', 'Music & Dance', 'Pottery & Crafts', 'Events & Celebrations', 'Community Life'];

    foreach ($event_types   as $term) wp_insert_term($term, 'bh_event_type');
    foreach ($article_cats  as $term) wp_insert_term($term, 'bh_article_category');
    foreach ($resource_cats as $term) wp_insert_term($term, 'bh_resource_cat');
    foreach ($gallery_cats  as $term) wp_insert_term($term, 'bh_gallery_cat');
}
// Themes can't use register_activation_hook — fire on theme switch and on a one-time flag.
add_action('after_switch_theme', 'bh_create_default_terms');
add_action('init', function () {
    if (!get_option('bh_default_terms_created')) {
        bh_create_default_terms();
        update_option('bh_default_terms_created', 1);
    }
}, 20);
