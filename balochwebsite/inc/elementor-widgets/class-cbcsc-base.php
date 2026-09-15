<?php
/**
 * Shared base for CBCSC Elementor widgets.
 */
defined('ABSPATH') || exit;

if (!class_exists('Class_CBCSC_Widget_Base')) {
    abstract class Class_CBCSC_Widget_Base extends \Elementor\Widget_Base {
        public function get_categories() {
            return ['cbcsc', 'general'];
        }
        public function get_keywords() {
            return ['cbcsc', 'baloch', 'heritage', 'community'];
        }
    }
}
