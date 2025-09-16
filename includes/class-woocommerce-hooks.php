<?php
/**
 * WooCommerce Hooks
 *
 * Handles WooCommerce integration and product updates
 *
 * @package WC_API_Framework
 * @since 1.0.0
 */

defined('ABSPATH') || exit();

/**
 * WooCommerce Hooks class
 */
class WC_API_Framework_WooCommerce_Hooks {
    
    /**
     * Initialize WooCommerce hooks
     */
    public static function init() {
        // Add hooks for product updates
        add_action('woocommerce_product_object_updated_props', array(__CLASS__, 'handle_product_update'), 10, 2);
        
        // Add admin notices for framework status
        add_action('admin_notices', array(__CLASS__, 'display_framework_status'));
    }
    
    /**
     * Handle product updates
     *
     * @param WC_Product $product
     * @param array $updated_props
     */
    public static function handle_product_update($product, $updated_props) {
        // This will be implemented by extensions
        do_action('wc_api_framework_product_updated', $product, $updated_props);
    }
    
    /**
     * Display framework status
     */
    public static function display_framework_status() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        $screen = get_current_screen();
        if ($screen && $screen->id === 'woocommerce_page_wc-settings') {
            $extensions = WC_API_Framework_Manager::get_extensions();
            if (!empty($extensions)) {
                echo '<div class="notice notice-success is-dismissible">';
                echo '<p><strong>' . __('WC API Framework', 'wc-api-framework') . ':</strong> ';
                echo sprintf(
                    _n(
                        '%d API extension is active.',
                        '%d API extensions are active.',
                        count($extensions),
                        'wc-api-framework'
                    ),
                    count($extensions)
                );
                echo '</p>';
                echo '</div>';
            }
        }
    }
}
