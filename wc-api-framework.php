<?php
/*
 * Plugin Name: WC API Framework
 * Plugin URI: https://www.gileba.be
 * Description: A flexible framework for integrating external APIs with WooCommerce
 * Version: 1.0.0-alpha.2 (build 1)
 * Author: Gileba
 * Author URI: https://www.gileba.be
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: wc-api-framework
 * Requires at least: 5.0
 * Tested up to: 6.8
 * Requires PHP: 7.4
 * WC requires at least: 3.0
 * WC tested up to: 10.1.2
 */

defined("ABSPATH") || exit();

/*
 * Plugin Requirements:
 * - WordPress: 5.0+ (6.0+ recommended)
 * - PHP: 7.4+ (8.0+ recommended)
 * - WooCommerce: 3.0+ (5.0+ recommended)
 * - Tested up to: WordPress 6.8, WooCommerce 10.1.2
 * - Required PHP Extensions: simplexml, json, curl
 */

// Define plugin constants
define('WC_API_FRAMEWORK_VERSION', '1.0.0-alpha.2');
define('WC_API_FRAMEWORK_BUILD', '1');
define('WC_API_FRAMEWORK_PLUGIN_FILE', __FILE__);
define('WC_API_FRAMEWORK_PLUGIN_BASENAME', plugin_basename(__FILE__));
define('WC_API_FRAMEWORK_PLUGIN_SLUG', 'wc-api-framework');

// Load plugin classes
require_once plugin_dir_path(__FILE__) . 'includes/class-api-interface.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-api-manager.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-cache-manager.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-admin-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-woocommerce-hooks.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-helpers.php';

// Load text domain early
add_action('init', array('WC_API_Framework_Helpers', 'load_textdomain'));

// Initialize plugin
add_action('plugins_loaded', 'wc_api_framework_init');

/**
 * Initialize the plugin
 */
function wc_api_framework_init() {
    // Check if WooCommerce is active
    add_action('admin_init', array('WC_API_Framework_Helpers', 'check_woocommerce'));

    // Declare HPOS compatibility
    add_action('before_woocommerce_init', array('WC_API_Framework_Helpers', 'declare_hpos_compatibility'));

    // Initialize API manager
    WC_API_Framework_Manager::init();

    // Initialize admin settings
    WC_API_Framework_Admin_Settings::init();

    // Initialize WooCommerce hooks
    WC_API_Framework_WooCommerce_Hooks::init();
}
