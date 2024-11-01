<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://idomit.com
 * @since             1.0.0
 * @package           Wp_Cloudflare_Turnstile
 *
 * @wordpress-plugin
 * Plugin Name:       WP Turnstile Cloudflare CAPTCHA
 * Plugin URI:        https://store.idomit.com
 * Description:       Add Cloudflare Turnstile Captcha to WordPress, WooCommerce & more. The user-friendly, reCAPTCHA replacement. 100% free!
 * Version:           1.0.1
 * Author:            idomit
 * Author URI:        https://idomit.com
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       wp-cloudflare-turnstile
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

if ( ! function_exists( 'wtcc_fs' ) ) {
    // Create a helper function for easy SDK access.
    function wtcc_fs() {
        global $wtcc_fs;

        if ( ! isset( $wtcc_fs ) ) {
            // Include Freemius SDK.
            require_once dirname(__FILE__) . '/freemius/start.php';

            $wtcc_fs = fs_dynamic_init( array(
                'id'                  => '11626',
                'slug'                => 'wp-turnstile-cloudflare-captcha',
                'type'                => 'plugin',
                'public_key'          => 'pk_4a1ceed519d4d27236beeb4494f78',
                'is_premium'          => false,
                'has_addons'          => false,
                'has_paid_plans'      => false,
                'menu'                => array(
                    'slug'           => 'wp-cloudflare-turnstile',
                    'override_exact' => true,
                    'first-path'     => 'options-general.php?page=wp-cloudflare-turnstile#tab-dashboard',
                    'account'        => false,
                    'contact'        => false,
                    'support'        => false,
                    'parent'         => array(
                        'slug' => 'options-general.php',
                    ),
                ),
            ) );
        }

        return $wtcc_fs;
    }

    // Init Freemius.
    wtcc_fs();
    // Signal that SDK was initiated.
    do_action( 'wtcc_fs_loaded' );

    function wtcc_fs_settings_url() {
        return admin_url( 'options-general.php?page=wp-cloudflare-turnstile#tab-dashboard' );
    }

    wtcc_fs()->add_filter('connect_url', 'wtcc_fs_settings_url');
    wtcc_fs()->add_filter('after_skip_url', 'wtcc_fs_settings_url');
    wtcc_fs()->add_filter('after_connect_url', 'wtcc_fs_settings_url');
    wtcc_fs()->add_filter('after_pending_connect_url', 'wtcc_fs_settings_url');
}
/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'WP_CLOUDFLARE_TURNSTILE_VERSION', '1.0.1' );
define( 'WP_CLOUDFLARE_TURNSTILE_PLUGIN_ADMIN_URLPATH', plugin_dir_url( __FILE__ ) . 'admin/' );
/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-wp-cloudflare-turnstile-activator.php
 */
function activate_wp_cloudflare_turnstile() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-cloudflare-turnstile-activator.php';
	Wp_Cloudflare_Turnstile_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-wp-cloudflare-turnstile-deactivator.php
 */
function deactivate_wp_cloudflare_turnstile() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-wp-cloudflare-turnstile-deactivator.php';
	Wp_Cloudflare_Turnstile_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_wp_cloudflare_turnstile' );
register_deactivation_hook( __FILE__, 'deactivate_wp_cloudflare_turnstile' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-wp-cloudflare-turnstile.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_wp_cloudflare_turnstile() {

	$plugin = new Wp_Cloudflare_Turnstile();
	$plugin->run();

}
run_wp_cloudflare_turnstile();
