<?php
/**
 * API Interface
 *
 * Defines the contract that all API extensions must implement
 *
 * @package WC_API_Framework
 * @since 1.0.0
 */

defined('ABSPATH') || exit();

/**
 * Interface for API extensions
 */
interface WC_API_Framework_Interface {
    
    /**
     * Get the API provider name
     *
     * @return string
     */
    public function get_provider_name();
    
    /**
     * Get the API provider slug
     *
     * @return string
     */
    public function get_provider_slug();
    
    /**
     * Get the API endpoint URL
     *
     * @return string
     */
    public function get_api_endpoint();
    
    /**
     * Get the credentials fields configuration
     *
     * @return array
     */
    public function get_credentials_fields();
    
    /**
     * Validate API credentials
     *
     * @param array $credentials
     * @return array
     */
    public function validate_credentials($credentials);
    
    /**
     * Parse API response
     *
     * @param string $response
     * @return array|false
     */
    public function parse_api_response($response);
    
    /**
     * Map API data to WooCommerce product data
     *
     * @param array $api_data
     * @param WC_Product $product
     * @return array
     */
    public function map_product_data($api_data, $product);
    
    /**
     * Get provider-specific settings fields
     *
     * @return array
     */
    public function get_provider_settings();
    
    /**
     * Get default cache durations
     *
     * @return array
     */
    public function get_default_cache_durations();
    
    /**
     * Get supported filtering types
     *
     * @return array
     */
    public function get_supported_filtering_types();
}
