<?php
/**
 * Cache Manager
 *
 * Handles caching for API responses and plugin data
 *
 * @package WC_API_Framework
 * @since 1.0.0
 */

defined('ABSPATH') || exit();

/**
 * Cache Manager class
 */
class WC_API_Framework_Cache_Manager {
    
    /**
     * Get cache duration for a specific type
     * This method will be called by extensions with their specific cache types
     *
     * @param string $type
     * @param int $default
     * @return int
     */
    public static function get_cache_duration($type, $default = 3600) {
        $options = get_option('wc_api_framework_options', array());
        $key = $type . '_cache_duration';
        return absint($options[$key] ?? $default);
    }
    
    /**
     * Get cached data
     *
     * @param string $key
     * @return mixed
     */
    public static function get_cache($key) {
        $cache_key = 'wc_api_framework_' . $key;
        return get_transient($cache_key);
    }
    
    /**
     * Set cached data
     *
     * @param string $key
     * @param mixed $data
     * @param int $duration
     */
    public static function set_cache($key, $data, $duration = 3600) {
        $cache_key = 'wc_api_framework_' . $key;
        set_transient($cache_key, $data, $duration);
    }
    
    /**
     * Delete cached data
     *
     * @param string $key
     */
    public static function delete_cache($key) {
        $cache_key = 'wc_api_framework_' . $key;
        delete_transient($cache_key);
    }
    
    /**
     * Clear all framework cache
     */
    public static function clear_all_cache() {
        global $wpdb;
        
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_wc_api_framework_%'
            )
        );
        
        $wpdb->query(
            $wpdb->prepare(
                "DELETE FROM {$wpdb->options} WHERE option_name LIKE %s",
                '_transient_timeout_wc_api_framework_%'
            )
        );
    }
}
