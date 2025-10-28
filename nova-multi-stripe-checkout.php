<?php
/**
 * Plugin Name: Nova Multi-Stripe Checkout
 * Plugin URI: https://github.com/kbrookes/Nova-Multi-Stripe-Checkout
 * Description: Lightweight REST API for creating Stripe Checkout subscription sessions with per-seat quantities, using different Stripe accounts (AU/NZ) based on form input.
 * Version: 0.1.3
 * Author: Kelsey Brookes
 * Author URI: https://github.com/kbrookes/
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: nova-multi-stripe-checkout
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 8.1
 * Network: false
 * GitHub Plugin URI: kbrookes/Nova-Multi-Stripe-Checkout
 * Primary Branch: main
 * Update URI: https://github.com/kbrookes/Nova-Multi-Stripe-Checkout
 *
 * @package NovaMSC
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('NOVA_MSC_VER', '0.1.3');
define('NOVA_MSC_OPT', 'nova_msc_options');

// Load Stripe SDK - try Composer first, then fallback to embedded
$autoloader_path = plugin_dir_path(__FILE__) . 'vendor/autoload.php';
$stripe_init_path = plugin_dir_path(__FILE__) . 'includes/stripe-php/init.php';

if (file_exists($autoloader_path)) {
    require_once $autoloader_path;
} elseif (file_exists($stripe_init_path)) {
    require_once $stripe_init_path;
} else {
    // If neither exists, show a helpful error
    add_action('admin_notices', function() {
        echo '<div class="notice notice-error"><p><strong>Nova Multi-Stripe Checkout:</strong> Stripe SDK not found. Please reinstall the plugin.</p></div>';
    });
    return;
}

// Include core files
require_once plugin_dir_path(__FILE__) . 'includes/helpers.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-nova-settings.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-nova-prices.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-nova-rest.php';
require_once plugin_dir_path(__FILE__) . 'includes/class-nova-webhooks.php';

/**
 * Initialize the plugin
 */
function nova_msc_init() {
    // Initialize core classes
    new NovaMSC\Settings();
    new NovaMSC\REST();
    new NovaMSC\Webhooks();
}
add_action('plugins_loaded', 'nova_msc_init');

/**
 * Plugin activation hook
 */
function nova_msc_activate() {
    // Set default options
    $default_options = array(
        'mode' => 'test',
        'au_sk' => '',
        'nz_sk' => '',
        'success_url_au' => home_url('/success-au/'),
        'cancel_url_au' => home_url('/cancel-au/'),
        'success_url_nz' => home_url('/success-nz/'),
        'cancel_url_nz' => home_url('/cancel-nz/'),
        'portal_enabled' => false,
    );
    
    add_option(NOVA_MSC_OPT, $default_options);
}
register_activation_hook(__FILE__, 'nova_msc_activate');

/**
 * Plugin deactivation hook
 */
function nova_msc_deactivate() {
    // Clean up if needed
}
register_deactivation_hook(__FILE__, 'nova_msc_deactivate');
