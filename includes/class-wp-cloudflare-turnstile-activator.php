<?php

/**
 * Fired during plugin activation
 *
 * @link       https://idomit.com
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/includes
 * @author     idomit <wp@idomit.com>
 */
class Wp_Cloudflare_Turnstile_Activator {

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function activate() {
        add_option('wtcc_cloudflare_tested', 'no');
	}

}
