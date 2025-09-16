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
        }
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
        echo __('WooCommerce is required for this plugin to work.', 'wc-api-framework');
        echo '</p>';
        echo '</div>';
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
