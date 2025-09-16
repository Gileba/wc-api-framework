<?php
/**
 * Admin Settings
 *
 * Handles admin settings page and configuration
 *
 * @package WC_API_Framework
 * @since 1.0.0
 */

defined('ABSPATH') || exit();

/**
 * Admin Settings class
 */
class WC_API_Framework_Admin_Settings {
    
    /**
     * Initialize admin settings
     */
    public static function init() {
        add_action('admin_menu', array(__CLASS__, 'add_admin_menu'));
        add_action('admin_init', array(__CLASS__, 'register_settings'));
    }
    
    /**
     * Add admin menu
     */
    public static function add_admin_menu() {
        add_options_page(
            __('WC API Framework', 'wc-api-framework'),
            __('WC API Framework', 'wc-api-framework'),
            'manage_options',
            'wc-api-framework-settings',
            array(__CLASS__, 'settings_page')
        );
    }
    
    /**
     * Register settings
     */
    public static function register_settings() {
        register_setting('wc_api_framework_options', 'wc_api_framework_options', array(__CLASS__, 'validate_options'));
        
        // Cache settings section
        add_settings_section(
            'wc_api_framework_cache_section',
            __('Cache Settings', 'wc-api-framework'),
            array(__CLASS__, 'display_cache_section'),
            'wc-api-framework-settings'
        );
        
        add_settings_field(
            'stock_cache_duration',
            __('Stock Cache Duration', 'wc-api-framework'),
            array(__CLASS__, 'stock_cache_duration_callback'),
            'wc-api-framework-settings',
            'wc_api_framework_cache_section'
        );
        
        add_settings_field(
            'price_cache_duration',
            __('Price Cache Duration', 'wc-api-framework'),
            array(__CLASS__, 'price_cache_duration_callback'),
            'wc-api-framework-settings',
            'wc_api_framework_cache_section'
        );
    }
    
    /**
     * Display cache section
     */
    public static function display_cache_section() {
        echo '<p>' . __('Configure cache durations for API responses.', 'wc-api-framework') . '</p>';
    }
    
    /**
     * Stock cache duration callback
     */
    public static function stock_cache_duration_callback() {
        $options = get_option('wc_api_framework_options', array());
        $value = $options['stock_cache_duration'] ?? '3600';
        echo '<input type="number" id="stock_cache_duration" name="wc_api_framework_options[stock_cache_duration]" value="' . esc_attr($value) . '" min="60" max="86400" style="width: 100px;" />';
        echo ' <span>' . __('seconds', 'wc-api-framework') . '</span>';
        echo '<p class="description">' . __('How long to cache stock data (60-86400 seconds).', 'wc-api-framework') . '</p>';
    }
    
    /**
     * Price cache duration callback
     */
    public static function price_cache_duration_callback() {
        $options = get_option('wc_api_framework_options', array());
        $value = $options['price_cache_duration'] ?? '7200';
        echo '<input type="number" id="price_cache_duration" name="wc_api_framework_options[price_cache_duration]" value="' . esc_attr($value) . '" min="60" max="86400" style="width: 100px;" />';
        echo ' <span>' . __('seconds', 'wc-api-framework') . '</span>';
        echo '<p class="description">' . __('How long to cache price data (60-86400 seconds).', 'wc-api-framework') . '</p>';
    }
    
    /**
     * Validate options
     *
     * @param array $input
     * @return array
     */
    public static function validate_options($input) {
        $sanitized = array();
        
        if (isset($input['stock_cache_duration'])) {
            $sanitized['stock_cache_duration'] = absint($input['stock_cache_duration']);
            if ($sanitized['stock_cache_duration'] < 60) {
                $sanitized['stock_cache_duration'] = 60;
            } elseif ($sanitized['stock_cache_duration'] > 86400) {
                $sanitized['stock_cache_duration'] = 86400;
            }
        }
        
        if (isset($input['price_cache_duration'])) {
            $sanitized['price_cache_duration'] = absint($input['price_cache_duration']);
            if ($sanitized['price_cache_duration'] < 60) {
                $sanitized['price_cache_duration'] = 60;
            } elseif ($sanitized['price_cache_duration'] > 86400) {
                $sanitized['price_cache_duration'] = 86400;
            }
        }
        
        return $sanitized;
    }
    
    /**
     * Display settings page
     */
    public static function settings_page() {
        $active_extension = WC_API_Framework_Manager::get_active_extension();
        ?>
        <div class="wrap">
            <h1><?php echo __('WC API Framework Settings', 'wc-api-framework'); ?></h1>
            
            <?php if ($active_extension): ?>
                <div class="notice notice-info">
                    <p>
                        <strong><?php echo __('Active Extension:', 'wc-api-framework'); ?></strong>
                        <?php echo esc_html($active_extension->get_provider_name()); ?>
                    </p>
                </div>
            <?php endif; ?>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('wc_api_framework_options');
                do_settings_sections('wc-api-framework-settings');
                submit_button();
                ?>
            </form>
        </div>
        <?php
    }
}
