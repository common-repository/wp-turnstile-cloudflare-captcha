<?php

/**
 * Define the internationalization functionality
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @link       https://idomit.com
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/includes
 */

/**
 * Define the internationalization functionality.
 *
 * Loads and defines the internationalization files for this plugin
 * so that it is ready for translation.
 *
 * @since      1.0.0
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/includes
 * @author     idomit <wp@idomit.com>
 */
class Wp_Cloudflare_Turnstile_i18n {


	/**
	 * Load the plugin text domain for translation.
	 *
	 * @since    1.0.0
	 */
	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'wp-cloudflare-turnstile',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}



}
