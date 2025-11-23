<?php
/**
 * Frontend Display for Product Fields
 *
 * Displays ACF custom fields on WooCommerce product pages.
 * Uses Shoptimizer hooks for proper integration.
 *
 * @package ResellPiacenza
 */

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

/**
 * Display custom product fields on single product pages
 *
 * Hooked into: woocommerce_single_product_summary (priority 25)
 * Position: After short description, before add to cart
 */
add_action('woocommerce_single_product_summary', 'rps_display_product_fields', 25);

function rps_display_product_fields() {
    // Only proceed if ACF is active
    if (!function_exists('get_field')) {
        return;
    }

    global $product;
    if (!$product) {
        return;
    }

    $product_id = $product->get_id();
    $fields = rps_get_product_fields();

    // Collect fields that have values
    $fields_with_values = array();

    foreach ($fields as $field_key => $field_config) {
        if (!$field_config['show_frontend']) {
            continue;
        }

        $value = get_field($field_config['acf_name'], $product_id);

        if (!empty($value)) {
            $fields_with_values[$field_key] = array(
                'label' => $field_config['label'],
                'value' => $value,
                'icon' => $field_config['icon'] ?? '',
            );
        }
    }

    // Only output if we have fields to show
    if (empty($fields_with_values)) {
        return;
    }

    // Output fields
    echo '<div class="rps-product-details">';

    foreach ($fields_with_values as $field) {
        echo '<div class="rps-product-detail-item">';

        if (!empty($field['icon'])) {
            echo '<span class="rps-detail-icon">' . esc_html($field['icon']) . '</span>';
        }

        echo '<span class="rps-detail-label">' . esc_html($field['label']) . ':</span> ';
        echo '<span class="rps-detail-value">' . wp_kses_post($field['value']) . '</span>';

        echo '</div>';
    }

    echo '</div>';
}

/**
 * Enqueue frontend styles for product fields
 */
add_action('wp_enqueue_scripts', 'rps_enqueue_frontend_styles');

function rps_enqueue_frontend_styles() {
    // Only load on product pages
    if (!is_product()) {
        return;
    }

    // Inline CSS to avoid extra file (lightweight)
    $custom_css = "
        .rps-product-details {
            margin: 20px 0;
            padding: 15px;
            background: #f9f9f9;
            border-radius: 5px;
            font-size: 14px;
            line-height: 1.6;
        }

        .rps-product-detail-item {
            margin-bottom: 8px;
        }

        .rps-product-detail-item:last-child {
            margin-bottom: 0;
        }

        .rps-detail-icon {
            margin-right: 6px;
        }

        .rps-detail-label {
            font-weight: 600;
            color: #333;
        }

        .rps-detail-value {
            color: #666;
        }

        @media (max-width: 768px) {
            .rps-product-details {
                font-size: 13px;
            }
        }
    ";

    wp_add_inline_style('shoptimizer-parent', $custom_css);
}
