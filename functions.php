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

// 1. Register custom product fields
add_action('init', 'register_enhanced_product_fields');
function register_enhanced_product_fields() {
    $fields = array(
        '_custom_material_composition',
        '_custom_sizing_guide',
        '_custom_fit_type',
        '_custom_style_notes',
        '_custom_colorway',
        '_custom_gender_target'
    );
    
    foreach ($fields as $field) {
        register_meta('product', $field, array(
            'type' => 'string',
            'single' => true,
            'show_in_rest' => true,
        ));
    }
}

// 2. Output enhanced schema
add_action('wp_head', 'output_enhanced_product_schema', 20);
function output_enhanced_product_schema() {
    if (!is_product()) return;

    // Safe product retrieval
    $product = wc_get_product();
    if (!$product) return;

    $product_id = $product->get_id();
    
    $material = get_post_meta($product_id, '_custom_material_composition', true);
    $sizing = get_post_meta($product_id, '_custom_sizing_guide', true);
    $colorway = get_post_meta($product_id, '_custom_colorway', true);
    
    $schema = array(
        '@context' => 'https://schema.org/',
        '@type' => 'Product',
        '@id' => get_permalink($product_id) . '#enhanced',
    );
    
    if ($material) $schema['material'] = $material;
    if ($colorway) $schema['color'] = $colorway;
    
    if ($sizing) {
        $schema['additionalProperty'] = array(array(
            '@type' => 'PropertyValue',
            'name' => 'Sizing Guide',
            'value' => $sizing
        ));
    }
    
    if (count($schema) > 3) {
        echo '<script type="application/ld+json">';
        echo json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        echo '</script>';
    }
}