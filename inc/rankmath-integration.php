<?php
/**
 * RankMath SEO Integration
 *
 * Integrates ACF custom fields with RankMath's Product schema.
 * Feeds your custom data into RankMath's schema output for rich snippets.
 *
 * @package ResellPiacenza
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Add custom fields to RankMath Product schema
 *
 * This filter allows us to enhance RankMath's Product schema
 * with our custom ACF fields for better SEO.
 *
 * @param array $entity Schema data
 * @param object $post Post object
 * @return array Modified schema data
 */
add_filter('rank_math/snippet/rich_snippet_product_entity', 'rps_enhance_rankmath_product_schema', 10, 2);

function rps_enhance_rankmath_product_schema($entity, $post) {
    // Only proceed if ACF is active
    if (!function_exists('get_field')) {
        return $entity;
    }

    // Get product ID
    $product_id = $post->ID;
    $fields = rps_get_product_fields();

    // Material Composition
    $material = get_field('material_composition', $product_id);
    if (!empty($material)) {
        $entity['material'] = sanitize_text_field($material);
    }

    // Colorway
    $colorway = get_field('colorway', $product_id);
    if (!empty($colorway)) {
        // Schema.org uses 'color' property
        $entity['color'] = sanitize_text_field($colorway);
    }

    // Gender Target (Audience)
    $gender = get_field('gender_target', $product_id);
    if (!empty($gender)) {
        $entity['audience'] = array(
            '@type' => 'PeopleAudience',
            'suggestedGender' => sanitize_text_field($gender)
        );
    }

    // Additional properties for other fields
    $additional_props = array();

    // Sizing Guide
    $sizing = get_field('sizing_guide', $product_id);
    if (!empty($sizing)) {
        $additional_props[] = array(
            '@type' => 'PropertyValue',
            'name' => 'Sizing Guide',
            'value' => sanitize_text_field($sizing)
        );
    }

    // Fit Type
    $fit_type = get_field('fit_type', $product_id);
    if (!empty($fit_type)) {
        $additional_props[] = array(
            '@type' => 'PropertyValue',
            'name' => 'Fit Type',
            'value' => sanitize_text_field($fit_type)
        );
    }

    // Style Notes (can be appended to description)
    $style_notes = get_field('style_notes', $product_id);
    if (!empty($style_notes)) {
        // If description exists, append. Otherwise create.
        if (isset($entity['description'])) {
            $entity['description'] .= ' ' . sanitize_text_field($style_notes);
        } else {
            $additional_props[] = array(
                '@type' => 'PropertyValue',
                'name' => 'Style Notes',
                'value' => sanitize_text_field($style_notes)
            );
        }
    }

    // Add additional properties if we have any
    if (!empty($additional_props)) {
        $entity['additionalProperty'] = $additional_props;
    }

    return $entity;
}

/**
 * Disable Shoptimizer's built-in schema (if needed)
 *
 * Uncomment this if you want to completely disable Shoptimizer's schema
 * and rely only on RankMath. Generally not needed as RankMath overrides it.
 */
// add_filter('shoptimizer_product_schema', '__return_false');

/**
 * Additional RankMath filters for product optimization
 */

// Example: Add custom fields to product title in schema (optional)
// add_filter('rank_math/snippet/rich_snippet_product_entity', function($entity, $post) {
//     $colorway = get_field('colorway', $post->ID);
//     if ($colorway && isset($entity['name'])) {
//         $entity['name'] .= ' - ' . $colorway;
//     }
//     return $entity;
// }, 20, 2);
