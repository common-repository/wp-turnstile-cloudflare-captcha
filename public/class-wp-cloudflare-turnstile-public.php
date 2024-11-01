<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       https://idomit.com
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/public
 * @author     idomit <wp@idomit.com>
 */
class Wp_Cloudflare_Turnstile_Public {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of the plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

		//wordpress login
		if(get_option('wtcc_cloudflare_wplogin')) 
		{
			if(empty(get_option('wtcc_cloudflare_tested')) || get_option('wtcc_cloudflare_tested') == 'yes') 
			{

				add_action('login_form', array( $this , 'wtcc_cloudflare_field'));
				add_action('wp_authenticate_user', array( $this ,  'wtcc_cloudflare_login_check' ), 10, 1);	
			}
		}

		// WP Register Check
		if(get_option('wtcc_cloudflare_wpregister')) 
		{
			add_action('register_form', array( $this , 'wtcc_cloudflare_field'));
			add_action('registration_errors', array( $this , 'wtcc_cloudflare_register_check' ) , 10, 3);
		}

		// WP Reset Check
		if(get_option('wtcc_cloudflare_wpreset')) 
		{
		  if(!is_admin()) 
		  {
		  	add_action('lostpassword_form', array( $this , 'wtcc_cloudflare_field'));
		  	add_action('lostpassword_post', array( $this , 'wtcc_cloudflare_reset_check' ), 10, 1);
		  }
		}

		// WP Comment
		if(get_option('wtcc_cloudflare_wpcomment')) 
		{
		  if(!is_admin()) 
		  {
		    add_action("comment_form_after", "wp_cloudflare_force_render");
		  	add_action('comment_form_submit_button', array( $this , 'wtcc_cloudflare_field_comment' ), 100, 2);
		  	

		  	// Comment Validation
		  	add_action('preprocess_comment', array( $this , 'wtcc_cloudflare_comment_check' ), 10, 1);
		  }
		}

		// Woo Login Check
		if(get_option('wtcc_cloudflare_woo_login')) 
		{
			if(empty(get_option('wtcc_cloudflare_tested')) || get_option('wtcc_cloudflare_tested') == 'yes') 
			{
				add_action('woocommerce_login_form', array( $this , 'wtcc_cloudflare_field_woo_login' ));
				add_action('wp_authenticate_user', array( $this , 'wtcc_cloudflare_woo_login_check' ) , 10, 1);
			}
		}

		// Woo Register Check
		if(get_option('wtcc_cloudflare_woo_register')) 
		{

			add_action('woocommerce_register_form', array( $this , 'wtcc_cloudflare_field_woo_register') );
			add_action('woocommerce_register_post', array( $this , 'wtcc_cloudflare_woo_register_check' ) , 10, 3);
			
		}

		// Woo Reset Check
		if(get_option('wtcc_cloudflare_woo_reset')) 
		{
			add_action('woocommerce_lostpassword_form', array( $this , 'wtcc_cloudflare_field_woo_reset' ));
			add_action('lostpassword_post', array( $this , 'wtcc_cloudflare_woo_reset_check' ), 10, 1);
			
		}

		// Woo Checkout Check
		if(get_option('wtcc_cloudflare_woo_checkout')) 
		{
			add_action('woocommerce_review_order_before_payment',
			array( $this , 'wtcc_cloudflare_field_checkout' ) , 10);
			add_action('woocommerce_checkout_process', array( $this , 'wp_wtcc_cloudflare_woo_checkout_check' ) );
		}

	}

	public function wtcc_cloudflare_login_check($user)
	{
			if($GLOBALS['pagenow'] === 'wp-login.php')
			{
				$check = wtcc_wp_cloudflare_check();
				$success = $check['success'];
				if($success != true) 
				{
					$user = new WP_Error( 'authentication_failed', wtcc_cloudflare_failed_message() );
				}
			}
				return $user;
	}

	public function wtcc_cloudflare_register_check($errors, $sanitized_user_login, $user_email){
				$check = wtcc_wp_cloudflare_check();
				$success = $check['success'];
				if($success != true) 
				{
					$errors->add( 'cfturnstile_error', sprintf('<strong>%s</strong>: %s',__( 'ERROR', 'simple-cloudflare-turnstile' ), wtcc_cloudflare_failed_message() ) );
				}
				return $errors;
	}

	public function wtcc_cloudflare_reset_check($validation_errors){
		  		if ( $GLOBALS['pagenow'] === 'wp-login.php' ) 
		  		{
		  			$check = wtcc_wp_cloudflare_check();
		  			$success = $check['success'];
		  			if($success != true) 
		  			{
		  				$validation_errors->add( 'cfturnstile_error', wtcc_cloudflare_failed_message() );
		  			}
		  		}
	}

	// Create and display the turnstile field for comments.
	public function wtcc_cloudflare_field_comment( $submit_button, $args ){
		do_action("wtcc_cloudflare_enqueue_scripts");
		$key = esc_attr( get_option('wtcc_cloudflare_site_key') );
		    	$theme = esc_attr( get_option('cfturnstile_theme') );
		    		$unique_id = mt_rand();
		    		$submit_before = '';
		    		$submit_after = '';
		    		$callback = '';
		    		if(get_option('wtcc_cloudflare_disable_button')) 
		    		{
		    			$callback = 'wpcloudflareCommentCallback';
		    		}
		    		
		    		$submit_before .= '<div class="cf-turnstile" data-callback="'.$callback.'" data-sitekey="'.sanitize_text_field($key).'" data-theme="'.sanitize_text_field($theme).'"></div>';

		    		if(get_option('wtcc_cloudflare_disable_button')) 
		    		{
		    			$submit_before .= '<div class="cf-turnstile-comment" style="pointer-events: none; opacity: 0.5;">';
		    			$submit_after .= "</div>";
		    		}

		    		$submit_after .= wp_cloudflare_force_render("-c-" . $unique_id);
		      
		    		return $submit_before . $submit_button . $submit_after;
	}

	public function wtcc_cloudflare_comment_check($commentdata){
		      if( !empty($_POST) ) 
		      {
		    		$check = wtcc_wp_cloudflare_check();
		    		$success = $check['success'];
		    		if($success != true) 
		    		{
		    			wp_die( '<p><strong>' . esc_html__( 'ERROR:', 'simple-cloudflare-turnstile' ) . '</strong> ' . wtcc_cloudflare_failed_message() . '</p>', 'simple-cloudflare-turnstile', array( 'response'  => 403, 'back_link' => 1, ) );
		    		}
		    		return $commentdata;
		      }
	}

	// Get turnstile field: WP
	public function wtcc_cloudflare_field(){   
        wtcc_wp_cloudflare_field_show('#wp-submit', 'wpcloudflareCallback', '', '-' . mt_rand()); 
    }

	// Get turnstile field: Woo Login
	public function wtcc_cloudflare_field_woo_login() 
	{ 
		wtcc_wp_cloudflare_field_show('.woocommerce-form-login__submit', 'wpcloudflareWooLoginCallback', '', '-woo-login'); 
	}

	// Get turnstile field: Woo Register
	public function wtcc_cloudflare_field_woo_register() 
	{ 	
		wtcc_wp_cloudflare_field_show('.woocommerce-form-register__submit', 'wpcloudflareWooRegisterCallback', '', '-woo-register');
	}

	// Get turnstile field: Woo Reset
	public function wtcc_cloudflare_field_woo_reset() 
	{	
		wtcc_wp_cloudflare_field_show('.woocommerce-ResetPassword .button', 'wpcloudflareWooResetCallback', '', '-woo-reset'); 
	}

	// Get turnstile field: Woo Checkout
	public function wtcc_cloudflare_field_checkout() 
	{
		$guest_only = esc_attr( get_option('wtcc_cloudflare_woo_guest_checkout') );
		if( !$guest_only || ($guest_only && !is_user_logged_in()) ) 
		{
			wtcc_wp_cloudflare_field_show('', '', '', '-woo-checkout');
		}
	}

	public function wtcc_cloudflare_woo_login_check($user){
					if(isset($_POST['woocommerce-login-nonce']))
					{
						$check = wtcc_wp_cloudflare_check();
						$success = $check['success'];
						if($success != true) 
						{
							$user = new WP_Error( 'authentication_failed', wtcc_cloudflare_failed_message() );
						}
					}
					return $user;
	}

	public function wtcc_cloudflare_woo_register_check($username, $email, $validation_errors){
				if(!is_checkout()) 
				{
					$check = wtcc_wp_cloudflare_check();
					$success = $check['success'];
					if($success != true) 
					{
						$validation_errors->add( 'cfturnstile_error', wtcc_cloudflare_failed_message() );
					}
				}
	}

	public function wtcc_cloudflare_woo_reset_check($validation_errors){
				if(isset($_POST['woocommerce-lost-password-nonce'])) 
				{
					$check = wtcc_wp_cloudflare_check();
					$success = $check['success'];
					if($success != true) 
					{
						$validation_errors->add( 'cfturnstile_error', wtcc_cloudflare_failed_message() );
					}
				}
	}

	public function wp_wtcc_cloudflare_woo_checkout_check() {
				$guest = esc_attr( get_option('wtcc_cloudflare_woo_guest_checkout') );
				if( !$guest || ( $guest && !is_user_logged_in() ) ) 
				{
					$check = wtcc_wp_cloudflare_check();
					$success = $check['success'];
					if($success != true) 
					{
						wc_add_notice( wtcc_cloudflare_failed_message(), 'error');
					}
				}
	}

	/**
	 * Register the stylesheets for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Cloudflare_Turnstile_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Cloudflare_Turnstile_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-cloudflare-turnstile-public.css', array(), $this->version, 'all' );

	}

	/**
	 * Register the JavaScript for the public-facing side of the site.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {

		/**
		 * This function is provided for demonstration purposes only.
		 *
		 * An instance of this class should be passed to the run() function
		 * defined in Wp_Cloudflare_Turnstile_Loader as all of the hooks are defined
		 * in that particular class.
		 *
		 * The Wp_Cloudflare_Turnstile_Loader will then create the relationship
		 * between the defined hooks and the functions defined in this
		 * class.
		 */

		wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-cloudflare-turnstile-public.js', array( 'jquery' ), $this->version, false );

	}

}



