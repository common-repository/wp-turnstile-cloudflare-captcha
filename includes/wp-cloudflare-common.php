<?php

    add_action("wtcc_cloudflare_enqueue_scripts", "wtcc_cloudflare_script_enqueue");

    function wtcc_cloudflare_script_enqueue() 
    {
        wp_enqueue_script( 'cloudflare-admin', plugins_url( '/js/wp-cloudflare-turnstile-common.js', __FILE__ ), array('jquery'), '1.0', false);

        wp_enqueue_script("cloudflarejs", "https://challenges.cloudflare.com/turnstile/v0/api.js?onload=onloadTurnstileCallback", array(), null, 'true');
    }

    

	function wtcc_wp_cloudflare_field_show($button_id = '', $callback = '', $g = false, $unique_id = '') 
    {
        do_action("wtcc_cloudflare_enqueue_scripts");
    
        $key = esc_attr( get_option('wtcc_cloudflare_site_key') );
        $theme = esc_attr( get_option('wtcc_cloudflare_theme') );
        ?>
        <div id="wp-cloudflare<?php echo sanitize_text_field($unique_id); ?>" class="cf-turnstile" <?php if(get_option('wtcc_cloudflare_disable_button')) { ?>data-callback="<?php echo sanitize_text_field($callback); ?>"<?php } ?>
        data-sitekey="<?php echo sanitize_text_field($key); ?>"
        data-theme="<?php echo sanitize_text_field($theme); ?>"
        data-retry="auto" data-retry-interval="1000"
        data-name="wp-cloudflare" style="<?php if(!is_page()) { ?>margin-left: -15px;<?php } else { ?>margin-left: -2px;<?php } ?>"></div>
        <?php if($button_id && get_option('wtcc_cloudflare_disable_button')) { ?>
        <style><?php echo sanitize_text_field($button_id); ?> { pointer-events: none; opacity: 0.5; }</style>
        <?php } ?>
        <br/>
        <?php
        do_action("wp_cloudflare_after_field", $unique_id);
    }

    function wtcc_wp_cloudflare_check($postdata = "") 
    {

        $results = array();

        if(empty($postdata) && isset($_POST['cf-turnstile-response'])) {
            $postdata = sanitize_text_field( $_POST['cf-turnstile-response'] );
        }

        $key = sanitize_text_field( get_option('wtcc_cloudflare_site_key') );
        $secret = sanitize_text_field( get_option('wtcc_cloudflare_secret_key') );
        if($key && $secret) {
            $headers = array(
                'body' => [
                    'secret' => $secret,
                    'response' => $postdata
                ]
            );
            $verify = wp_remote_post( 'https://challenges.cloudflare.com/turnstile/v0/siteverify', $headers );
            $verify = wp_remote_retrieve_body( $verify );
            $response = json_decode($verify);

            $results['success'] = $response->success;

            foreach($response as $key => $val){
                if($key == 'error-codes')
                foreach($val as $key => $error_val){
                    $results['error_code'] = $error_val;
                }
            }

            return $results;

        } 
        else 
        {
            return false;
        }
    }

    function wtcc_cloudflare_failed_message($default = "") 
    {
        if(!$default && !empty(get_option('wtcc_cloudflare_error_message')) && get_option('wtcc_cloudflare_error_message')) {
            return sanitize_text_field( get_option('wtcc_cloudflare_error_message') );
        } else {
            return __( 'Please verify that you are human.', 'wp-cloudflare-turnstile' );
        }
    }

    add_action("wp_cloudflare_after_field", "wp_cloudflare_force_render", 10, 1);
  function wp_cloudflare_force_render($unique_id = '') 
  {
    $unique_id = sanitize_text_field($unique_id);
    ?>
    <script>

    if (typeof jQuery != 'undefined') {
      jQuery(document).ready(function() {
        setTimeout(function() {
         if (jQuery('#wp-cloudflare<?php echo $unique_id; ?> iframe').length <= 0) {
              turnstile.remove('#wp-cloudflare<?php echo $unique_id; ?>');
              turnstile.render('#wp-cloudflare<?php echo $unique_id; ?>', { sitekey: '<?php echo sanitize_text_field( get_option('wtcc_cloudflare_site_key') ); ?>', });
          }
        }, 200);
      });
    } else {
      document.addEventListener("DOMContentLoaded", function() {
        setTimeout(function() {
          turnstile.remove('#wp-cloudflare<?php echo $unique_id; ?>');
          turnstile.render('#wp-cloudflare<?php echo $unique_id; ?>', { sitekey: '<?php echo sanitize_text_field( get_option('wtcc_cloudflare_site_key') ); ?>', });
        }, 200);
      });
    }
    </script>
    <?php
}

?>