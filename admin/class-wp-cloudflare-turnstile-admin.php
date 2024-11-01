<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://idomit.com
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/admin
 * @author     idomit <wp@idomit.com>
 */
class Wp_Cloudflare_Turnstile_Admin {

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
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;

        add_action('update_option_wtcc_cloudflare_site_key', array( $this , 'wtcc_cloudflare_keys_updated' ) , 10);
        add_action('update_option_wtcc_cloudflare_secret_key', array( $this , 'wtcc_cloudflare_keys_updated' ), 10);

	}

    public function wtcc_cloudflare_keys_updated() 
    {
       update_option('wtcc_cloudflare_tested', 'no');
    }

	/**
	 * Register the stylesheets for the admin area.
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
        if (isset($_GET['page']) == 'wp-cloudflare-turnstile' ) :
		    wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/wp-cloudflare-turnstile-admin.css', array(), $this->version, 'all' );
        endif;
	}

	/**
	 * Register the JavaScript for the admin area.
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

		if (isset($_GET['page']) == 'wp-cloudflare-turnstile' ) :
                    wp_localize_script( $this->plugin_name, 'waef', array(
                        'nonce' => wp_create_nonce( 'waef-ajax-nonce' ),
                        'ajax_url'=> admin_url('admin-ajax.php')
                    ) );
                    wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/wp-cloudflare-turnstile-admin.js', array( 'jquery' ), $this->version, false );
                    wp_enqueue_script( 'wpct-freemius-checkout', 'https://checkout.freemius.com/checkout.min.js', array(), '1', true );
                    
        endif;
	}

	public	function wtcc_cloudflare_turnstile_register_options_page() {
      
      add_submenu_page( 'options-general.php', 'WP Cloudflare Captcha', 'WP Cloudflare Captcha', 'manage_options', 'wp-cloudflare-turnstile' , array($this,'wtcc_cloudflare_turnstile_options_page') );

	  add_action( 'admin_init', array($this,'wtcc_cloudflare_turnstile_options'));

    }
    

    public function wtcc_cloudflare_turnstile_options_page()
    {
    	require_once 'partials/wp-cloudflare-turnstile-admin-display.php';
    }

    public function wtcc_cloudflare_turnstile_options() {

        $options = array( 'wtcc_cloudflare_site_key' , 'wtcc_cloudflare_secret_key', 'wtcc_cloudflare_wplogin' , 'wtcc_cloudflare_wpregister' , 'wtcc_cloudflare_wpreset' , 'wtcc_cloudflare_wpcomment', 'wtcc_cloudflare_woo_checkout' , 'wtcc_cloudflare_woo_login' , 'wtcc_cloudflare_woo_register' , 'wtcc_cloudflare_woo_reset' , 'wtcc_cloudflare_woo_guest_checkout' , 'wtcc_cloudflare_error_message' , 'wtcc_cloudflare_disable_button' , 'wtcc_cloudflare_theme' );

        foreach($options as $option)
        {
            register_setting( 'cloudflare-turnstile-options-group', $option );
        }
	}

     // Admin test form to check Turnstile response
    public function wtcc_cloudflare_admin_test() {
        ?>
        <form action="" method="POST">
        <?php

        if(!empty(get_option('wtcc_cloudflare_site_key')) && !empty(get_option('wtcc_cloudflare_secret_key'))) 
        {

            $check = wtcc_wp_cloudflare_check();
            $success = '';
            $error = '';

            if(isset($check['success'])) $success = $check['success'];
            if(isset($check['error_code'])) $error = $check['error_code'];

            if($success != true) 
            {
              echo '<br/><div class="cloudflare-box"><div class="row"><div class="column">';
                echo '<p class="almost_done_text">' . __( 'Almost done...', 'wp-cloudflare-turnstile' ) . '</p>';
            }
            if(!isset($_POST['cf-turnstile-response'])) 
            {
                echo '<p>'
                . '<span class="error_text">' . __( 'Turnstile API keys have been updated. Please test the Turnstile API response below.', 'wp-cloudflare-turnstile' ) . '</span>'
                . '<br/>'
                . __( 'Turnstile Captcha will not be added to any login forms until the test is successfully complete.', 'wp-cloudflare-turnstile' )
                . '</p>';
            } else
            {
                if($success == true) 
                {
                    update_option('wtcc_cloudflare_tested', 'yes');
                } 
                else 
                {
                    if($error == "missing-input-response") 
                    {
                        echo '<p class="error_text">' .wtcc_cloudflare_failed_message() . '</p>';
                    } 
                    else 
                    {
                        echo '<p class="error_text">' . __( 'Failed! There is an error with your API settings. Please check & update them.', 'wp-cloudflare-turnstile' ) . '</p>';
                    }
                }
                if($error) 
                {
                    echo '<p class="text_bold">' . esc_html__( 'Error message:', 'simple-cloudflare-turnstile' ) . " " . $this->wtcc_wp_wtcc_cloudflare_error_message($error) . '</p>';
                }
            }
            if($success != true) 
            {
                echo '</div><div class="column"><div class="ml-15">';
                echo wtcc_wp_cloudflare_field_show('', '');
                echo '<div class="mb-20"></div></div>';
                echo '<button class="submit_button" type="submit">
                '.__( 'TEST API RESPONSE', 'wp-cloudflare-turnstile').' <span class="dashicons dashicons-arrow-right-alt"></span>
                </button></div>';
                echo '</div></div><br>';
            }
        }
        ?>


        </form>
        <?php
    }

    public function wtcc_wp_wtcc_cloudflare_error_message($code) {

        switch ( $code ) 
        {
            case 'missing-input-secret':
                return __( 'The secret parameter was not passed.', 'wp-cloudflare-turnstile' );
            case 'invalid-input-secret':
                return __( 'The secret parameter was invalid or did not exist.', 'wp-cloudflare-turnstile' );
            case 'missing-input-response':
                return __( 'The response parameter was not passed.', 'wp-cloudflare-turnstile' );
            case 'invalid-input-response':
                return __( 'The response parameter is invalid or has expired.', 'wp-cloudflare-turnstile' );
            case 'bad-request':
                return __( 'The request was rejected because it was malformed.', 'wp-cloudflare-turnstile' );
            case 'timeout-or-duplicate':
                return __( 'The response parameter has already been validated before.', 'wp-cloudflare-turnstile' );
            case 'internal-error':
                return __( 'An internal error happened while validating the response. The request can be retried.', 'wp-cloudflare-turnstile' );
            default:
                return __( 'There was an error with Turnstile response. Please check your keys are correct.', 'wp-cloudflare-turnstile' );
        }
    }

}


    
?>
