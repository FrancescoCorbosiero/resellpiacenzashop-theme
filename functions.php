<?php
/**
 * Shoptimizer Child Theme Functions
 */

// Enqueue parent and child theme styles
add_action('wp_enqueue_scripts', 'shoptimizer_child_enqueue_styles');
function shoptimizer_child_enqueue_styles() {
    wp_enqueue_style('shoptimizer-parent', get_template_directory_uri() . '/style.css');
    wp_enqueue_style('shoptimizer-child', get_stylesheet_directory_uri() . '/style.css', array('shoptimizer-parent'));
}

// Your custom code goes below this line
// ============================================

/**
 * Load modular components
 * Clean, organized structure for maintainability
 */
$rps_inc_dir = get_stylesheet_directory() . '/inc/';

if (file_exists($rps_inc_dir . 'config.php')) {
    require_once $rps_inc_dir . 'config.php';
}

if (file_exists($rps_inc_dir . 'frontend-display.php')) {
    require_once $rps_inc_dir . 'frontend-display.php';
}

if (file_exists($rps_inc_dir . 'rankmath-integration.php')) {
    require_once $rps_inc_dir . 'rankmath-integration.php';
}