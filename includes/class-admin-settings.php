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
        
        // Framework settings section
        add_settings_section(
            'wc_api_framework_general_section',
            __('General Settings', 'wc-api-framework'),
            array(__CLASS__, 'display_general_section'),
            'wc-api-framework-settings'
        );
    }
    
    /**
     * Display general section
     */
    public static function display_general_section() {
        echo '<p>' . __('General framework settings and information.', 'wc-api-framework') . '</p>';
    }
    
    /**
     * Validate options
     *
     * @param array $input
     * @return array
     */
    public static function validate_options($input) {
        // Framework-specific validation can be added here
        return $input;
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
