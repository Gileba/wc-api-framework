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
     * Get plugin options with static caching
     *
     * @return array
     */
    private static function get_plugin_options() {
        static $options_cache = null;
        if ($options_cache === null) {
            $options_cache = get_option('wc_api_framework_options', array());
        }
        return $options_cache;
    }
    
    /**
     * Get cache duration for stock data
     *
     * @return int
     */
    public static function get_stock_cache_duration() {
        $options = self::get_plugin_options();
        return absint($options['stock_cache_duration'] ?? 3600);
    }
    
    /**
     * Get cache duration for price data
     *
     * @return int
     */
    public static function get_price_cache_duration() {
        $options = self::get_plugin_options();
        return absint($options['price_cache_duration'] ?? 7200);
    }
    
    /**
     * Get cache duration for unknown product retry
     *
     * @return int
     */
    public static function get_unknown_retry_duration() {
        $options = self::get_plugin_options();
        return absint($options['unknown_retry_duration'] ?? 86400);
    }
    
    /**
     * Get cache duration for unknown product cleanup
     *
     * @return int
     */
    public static function get_unknown_cleanup_duration() {
        $options = self::get_plugin_options();
        return absint($options['unknown_cleanup_duration'] ?? 604800);
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
