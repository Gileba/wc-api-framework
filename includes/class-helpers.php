<?php
/**
 * Helper Functions
 *
 * @package WC_API_Framework
 * @since 1.0.0
 */

defined('ABSPATH') || exit();

/**
 * Helper functions class
 */
class WC_API_Framework_Helpers {
    
    /**
     * Load plugin text domain
     */
    public static function load_textdomain() {
        $domain = 'wc-api-framework';
        $locale = apply_filters('plugin_locale', get_locale(), $domain);
        $languages_path = dirname(plugin_basename(WC_API_FRAMEWORK_PLUGIN_FILE)) . '/languages/';
        return load_plugin_textdomain($domain, false, $languages_path);
    }
    
    /**
     * Check if WooCommerce is active
     */
    public static function check_woocommerce() {
        if (!class_exists('WooCommerce')) {
            add_action('admin_notices', array(__CLASS__, 'woocommerce_missing_notice'));
            // Auto-deactivate our plugin if WooCommerce is not active
            deactivate_plugins(plugin_basename(WC_API_FRAMEWORK_PLUGIN_FILE));
            return;
        }
        
        // WordPress will automatically handle plugin dependencies via the 'Requires Plugins' header
    }
    
    /**
     * Display WooCommerce missing notice
     */
    public static function woocommerce_missing_notice() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        echo '<div class="notice notice-error">';
        echo '<p><strong>' . __('WC API Framework', 'wc-api-framework') . ':</strong> ';
        echo __('WooCommerce is required for this plugin to work. The plugin has been deactivated.', 'wc-api-framework');
        echo '</p>';
        echo '</div>';
    }
    
    /**
     * Prevent WooCommerce deactivation when our plugin is active
     */
    public static function prevent_woocommerce_deactivation($actions, $plugin_file) {
        if (is_plugin_active(plugin_basename(WC_API_FRAMEWORK_PLUGIN_FILE)) && $plugin_file === 'woocommerce/woocommerce.php') {
            unset($actions['deactivate']);
            $actions['deactivate'] = '<span style="color: #999; cursor: not-allowed;" title="' . 
                esc_attr__('Cannot deactivate WooCommerce while WC API Framework is active', 'wc-api-framework') . '">' . 
                __('Deactivate', 'wc-api-framework') . '</span>';
        }
        return $actions;
    }
    
    /**
     * Add explanatory row below WooCommerce plugin when our plugin is active
     */
    public static function add_woocommerce_dependency_row($plugin_file, $plugin_data) {
        // Check if this is WooCommerce and our plugin is active
        if ($plugin_file === 'woocommerce/woocommerce.php' && is_plugin_active(plugin_basename(WC_API_FRAMEWORK_PLUGIN_FILE))) {
            echo '<tr class="plugin-update-tr wc-api-framework-notice" style="border-bottom: none;">';
            echo '<td colspan="4" class="plugin-update colspanchange" style="padding: 0; margin: 0;">';
            echo '<div class="update-message notice inline notice-warning notice-alt" style="margin: 0; padding: 8px 12px; background: #fff3cd; border-left: 4px solid #ffc107;">';
            echo '<p style="margin: 0; color: #856404;">';
            echo '<span style="color: #d63638; font-weight: 600;">⚠️ </span>';
            echo __('WooCommerce cannot be deactivated because it is required by WC API Framework.', 'wc-api-framework');
            echo '</p>';
            echo '</div>';
            echo '</td>';
            echo '</tr>';
        }
    }
    
    /**
     * Add custom admin styles for the dependency notice
     */
    public static function add_admin_styles() {
        echo '<style>
            .wc-api-framework-notice {
                border-bottom: none !important;
            }
            .wc-api-framework-notice .plugin-update {
                padding: 0 !important;
                margin: 0 !important;
            }
            .wc-api-framework-notice .update-message {
                margin: 0 !important;
                padding: 8px 12px !important;
            }
        </style>';
    }
    
    
    /**
     * Declare HPOS compatibility
     */
    public static function declare_hpos_compatibility() {
        if (class_exists('\Automattic\WooCommerce\Utilities\FeaturesUtil')) {
            \Automattic\WooCommerce\Utilities\FeaturesUtil::declare_compatibility('custom_order_tables', WC_API_FRAMEWORK_PLUGIN_FILE, true);
        }
    }
    
    /**
     * Check if debug mode is enabled
     *
     * @return bool
     */
    public static function is_debug_mode() {
        return defined('WP_DEBUG') && WP_DEBUG && defined('WP_DEBUG_LOG') && WP_DEBUG_LOG;
    }
    
    /**
     * Log debug information
     *
     * @param string $message
     * @param array $context
     */
    public static function log($message, $context = array()) {
        if (!self::is_debug_mode()) {
            return;
        }
        
        $log_message = '[' . current_time('Y-m-d H:i:s') . '] WC API Framework: ' . $message;
        if (!empty($context)) {
            $log_message .= ' | Context: ' . wp_json_encode($context);
        }
        
        error_log($log_message);
    }
}
