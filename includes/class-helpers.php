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
        
        // Prevent WooCommerce deactivation if framework or extensions are active
        add_filter('plugin_action_links_woocommerce/woocommerce.php', array(__CLASS__, 'prevent_woocommerce_deactivation'));
        add_action('admin_init', array(__CLASS__, 'prevent_woocommerce_deactivation_bulk'));
        add_action('admin_notices', array(__CLASS__, 'woocommerce_deactivation_notice'));
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
     * Prevent WooCommerce deactivation if framework or extensions are active
     *
     * @param array $actions
     * @return array
     */
    public static function prevent_woocommerce_deactivation($actions) {
        // Check if framework is active
        if (is_plugin_active(WC_API_FRAMEWORK_PLUGIN_BASENAME)) {
            unset($actions['deactivate']);
            $actions['deactivate'] = '<span class="delete">' . __('Deactivate', 'wc-api-framework') . '</span>';
            return $actions;
        }
        
        // Check if any extensions are active
        $extensions = WC_API_Framework_Manager::get_extensions();
        if (!empty($extensions)) {
            unset($actions['deactivate']);
            $actions['deactivate'] = '<span class="delete">' . __('Deactivate', 'wc-api-framework') . '</span>';
            return $actions;
        }
        
        return $actions;
    }
    
    /**
     * Prevent WooCommerce deactivation through bulk actions
     */
    public static function prevent_woocommerce_deactivation_bulk() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Check if user is trying to deactivate WooCommerce
        if (isset($_GET['action']) && $_GET['action'] === 'deactivate' && isset($_GET['plugin']) && $_GET['plugin'] === 'woocommerce/woocommerce.php') {
            // Check if framework or extensions are active
            if (is_plugin_active(WC_API_FRAMEWORK_PLUGIN_BASENAME) || !empty(WC_API_Framework_Manager::get_extensions())) {
                wp_redirect(admin_url('plugins.php?wc-api-framework-woocommerce-protected=1'));
                exit;
            }
        }
        
        // Check bulk actions
        if (isset($_POST['action']) && $_POST['action'] === 'deactivate-selected' && isset($_POST['checked'])) {
            $plugins_to_deactivate = $_POST['checked'];
            if (in_array('woocommerce/woocommerce.php', $plugins_to_deactivate)) {
                // Check if framework or extensions are active
                if (is_plugin_active(WC_API_FRAMEWORK_PLUGIN_BASENAME) || !empty(WC_API_Framework_Manager::get_extensions())) {
                    wp_redirect(admin_url('plugins.php?wc-api-framework-woocommerce-protected=1'));
                    exit;
                }
            }
        }
    }
    
    /**
     * Display notice when WooCommerce deactivation is prevented
     */
    public static function woocommerce_deactivation_notice() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        if (isset($_GET['wc-api-framework-woocommerce-protected'])) {
            echo '<div class="notice notice-error is-dismissible">';
            echo '<p><strong>' . __('WC API Framework', 'wc-api-framework') . ':</strong> ';
            echo __('WooCommerce cannot be deactivated while the WC API Framework or its extensions are active. Please deactivate the framework and all extensions first.', 'wc-api-framework');
            echo '</p>';
            echo '</div>';
        }
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
