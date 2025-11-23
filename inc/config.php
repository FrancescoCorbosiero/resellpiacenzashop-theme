<?php
/**
 * ResellPiacenza Configuration
 *
 * Central configuration for custom product fields.
 * Edit this file to add/remove/modify fields.
 *
 * @package ResellPiacenza
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Get product field definitions
 *
 * Structure matches ACF field naming convention.
 * These should match your ACF field names exactly.
 *
 * @return array Field configuration
 */
function rps_get_product_fields() {
    return array(
        'material_composition' => array(
            'label' => __('Material Composition', 'resellpiacenza'),
            'acf_name' => 'material_composition', // ACF field name
            'icon' => '🧵',
            'show_frontend' => true,
            'schema_property' => 'material', // Schema.org property name
        ),
        'sizing_guide' => array(
            'label' => __('Sizing Guide', 'resellpiacenza'),
            'acf_name' => 'sizing_guide',
            'icon' => '📏',
            'show_frontend' => true,
            'schema_property' => 'size', // Or use additionalProperty
        ),
        'fit_type' => array(
            'label' => __('Fit Type', 'resellpiacenza'),
            'acf_name' => 'fit_type',
            'icon' => '👔',
            'show_frontend' => true,
            'schema_property' => 'additionalProperty',
        ),
        'style_notes' => array(
            'label' => __('Style Notes', 'resellpiacenza'),
            'acf_name' => 'style_notes',
            'icon' => '✨',
            'show_frontend' => true,
            'schema_property' => 'description', // Can append to description
        ),
        'colorway' => array(
            'label' => __('Colorway', 'resellpiacenza'),
            'acf_name' => 'colorway',
            'icon' => '🎨',
            'show_frontend' => true,
            'schema_property' => 'color',
        ),
        'gender_target' => array(
            'label' => __('Gender Target', 'resellpiacenza'),
            'acf_name' => 'gender_target',
            'icon' => '👤',
            'show_frontend' => true,
            'schema_property' => 'audience',
        ),
    );
}

/**
 * Get theme version for cache busting
 */
function rps_get_theme_version() {
    return '1.0.1';
}
