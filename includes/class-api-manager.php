<?php
/**
 * API Manager
 *
 * Manages API extensions and provides a unified interface
 *
 * @package WC_API_Framework
 * @since 1.0.0
 */

defined('ABSPATH') || exit();

/**
 * API Manager class
 */
class WC_API_Framework_Manager {
    
    /**
     * Registered API extensions
     *
     * @var array
     */
    private static $extensions = array();
    
    /**
     * Active API extension
     *
     * @var WC_API_Framework_Interface|null
     */
    private static $active_extension = null;
    
    /**
     * Initialize the API manager
     */
    public static function init() {
        add_action('init', array(__CLASS__, 'load_extensions'));
        add_action('admin_init', array(__CLASS__, 'check_extensions'));
    }
    
    /**
     * Register an API extension
     *
     * @param WC_API_Framework_Interface $extension
     * @return bool
     */
    public static function register_extension($extension) {
        if (!$extension instanceof WC_API_Framework_Interface) {
            return false;
        }
        
        $slug = $extension->get_provider_slug();
        self::$extensions[$slug] = $extension;
        
        // Set as active if it's the first one or if it's explicitly set
        if (self::$active_extension === null) {
            self::$active_extension = $extension;
        }
        
        return true;
    }
    
    /**
     * Get all registered extensions
     *
     * @return array
     */
    public static function get_extensions() {
        return self::$extensions;
    }
    
    /**
     * Get active extension
     *
     * @return WC_API_Framework_Interface|null
     */
    public static function get_active_extension() {
        return self::$active_extension;
    }
    
    /**
     * Set active extension
     *
     * @param string $slug
     * @return bool
     */
    public static function set_active_extension($slug) {
        if (isset(self::$extensions[$slug])) {
            self::$active_extension = self::$extensions[$slug];
            return true;
        }
        return false;
    }
    
    /**
     * Load extensions from other plugins
     */
    public static function load_extensions() {
        do_action('wc_api_framework_register_extensions');
    }
    
    /**
     * Check if extensions are available
     */
    public static function check_extensions() {
        if (empty(self::$extensions)) {
            add_action('admin_notices', array(__CLASS__, 'no_extensions_notice'));
        }
    }
    
    /**
     * Display notice when no extensions are available
     */
    public static function no_extensions_notice() {
        if (!current_user_can('manage_options')) {
            return;
        }
        
        echo '<div class="notice notice-warning is-dismissible">';
        echo '<p><strong>' . __('WC API Framework', 'wc-api-framework') . ':</strong> ';
        echo __('No API extensions are currently active. Please install an API extension plugin to use this framework.', 'wc-api-framework');
        echo '</p>';
        echo '</div>';
    }
}
