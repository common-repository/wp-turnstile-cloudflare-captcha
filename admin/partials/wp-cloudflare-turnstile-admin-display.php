<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link       https://idomit.com
 * @since      1.0.0
 *
 * @package    Wp_Cloudflare_Turnstile
 * @subpackage Wp_Cloudflare_Turnstile/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wtcc-settings wtcc-settings--">
<div class="wtcc-settings__header">
      <h2><?php _e('WP Turnstile Cloudflare CAPTCHA' , 'wp-cloudflare-turnstile'); ?></h2>
      <span style="margin: 0 0 0 auto; background: #f0f0f1; display: inline-block; padding: 0 10px; border-radius: 13px; height: 26px; line-height: 26px; white-space: nowrap; box-sizing: border-box; color: #656565;"><span style="background: green;color: #fff;padding: 2px;border-radius: 2px;" class="freemium">Free</span> v<?php echo $this->version?></span>
    </div>

<div class="wtcc-settings__content">
<ul class="wtcc-nav">
        <li class="wtcc-nav__item ">
          <a class="wtcc-nav__item-link" href="#tab-dashboard">Dashboard</a>
        </li>
</ul>
<div class="idomit-plugin-sidebar">
  <div class="idomit-settings-sidebar__widget idomit-settings-sidebar__widget--works-well">
      <h3><?php _e( 'Our WordPress Plugin', 'woocommerce-advanced-extra-fees' ); ?></h3>
      <div class="idomit-product">
          <div class="idomit-product__image">
            <img width="1200" height="720" src="<?php echo WP_CLOUDFLARE_TURNSTILE_PLUGIN_ADMIN_URLPATH?>images/waef-condtion.png" class="attachment-full size-full" alt="Advanced Easy Shipping For WooCommerce" loading="lazy">
          </div>
          <div class="idomit-product__content">
            <h4 class="idomit-product__title"><a target="_blank" href="https://store.idomit.com/product/woocommerce-advanced-extra-fees/?utm_source=Idomit-plugin&amp;utm_medium=Plugin&amp;utm_campaign=idomit-extrafees&amp;utm_content=cross-sell"><?php _e( 'WooCommerce Extra Fees', 'woocommerce-advanced-extra-fees' ); ?></a></h4>
            <p class="idomit-product__description"><?php _e( 'WooCommerce Advanced Extra Fees is the fastest and easiest WooCommerce extra fees plugin with breakthrough performance. Everything works on a fast and easy. Feel no delay – because your time is precious!', 'woocommerce-advanced-extra-fees' ); ?></p>
            <div class="idomit-product__buttons">
              <p><a href="https://checkout.freemius.com/mode/dialog/plugin/8791/plan/17078/" class="button idomit-buy-now idomit-button idomit-button--small" data-plugin-id="8791" data-plan-id="17078" data-public-key="pk_9e2cdb2a2dcc0324313c11e5c598d" data-type="premium"><?php _e( 'Buy Plugin', 'woocommerce-advanced-extra-fees' ); ?></a></p>
            </div>
          </div>
      </div>  
      <div class="idomit-product">
          <div class="idomit-product__image">
            <img width="1200" height="720" src="<?php echo WP_CLOUDFLARE_TURNSTILE_PLUGIN_ADMIN_URLPATH?>images/easy-shipping.png" class="attachment-full size-full" alt="Advanced Easy Shipping For WooCommerce" loading="lazy">
          </div>
          <div class="idomit-product__content">
            <h4 class="idomit-product__title"><a target="_blank" href="https://store.idomit.com/product/advanced-easy-shipping-for-woocommerce/?utm_source=Idomit-plugin&amp;utm_medium=Plugin&amp;utm_campaign=idomit-extrafees&amp;utm_content=cross-sell"><?php _e( 'Advanced Easy Shipping For WooCommerce', 'woocommerce-advanced-extra-fees' ); ?></a></h4>
            <p class="idomit-product__description"><?php _e( 'WooCommerce Advanced Easy Shipping Plugin helps make Shipping process easy and convenient for E-commerce Store owners. The plugin makes it easier by offering different options to decide shipping rates based on different criteria.', 'woocommerce-advanced-extra-fees' ); ?></p>
            <div class="idomit-product__buttons">
              <p><a href="https://checkout.freemius.com/mode/dialog/plugin/8790/plan/14731/" class="button idomit-buy-now idomit-button idomit-button--small"  data-plugin-id="8790" data-plan-id="14731" data-public-key="pk_2a55465e285686f167dda32ce0750" data-type="premium" ><?php _e( 'Buy Plugin', 'woocommerce-advanced-extra-fees' ); ?></a></p>
            </div>
          </div>
      </div>
  </div>
</div>

<div class="idomit-plugin-setting">
<div id="tab-dashboard" class="wtcc-section wtcc-tab wtcc-tab--dashboard">
    <?php
      if(empty(get_option('wtcc_cloudflare_tested')) || get_option('wtcc_cloudflare_tested') != 'yes') 
    {
        //$testObject = new Wp_Cloudflare_Turnstile_Admin();
        $this->wtcc_cloudflare_admin_test();
    }
    ?>
    <form method="post" action="options.php">

        <?php settings_fields( 'cloudflare-turnstile-options-group' ); ?>
        <?php do_settings_sections( 'cloudflare-turnstile-options-group' ); ?>
      <div class="postbox">
        <div>
            <h2><?php _e('API Key Settings : ' , 'wp-cloudflare-turnstile'); ?></h2>
            <p class="text_bold" style="margin-left: 20px;"><?php _e('You can get your site key and secret from here: ' , 'wp-cloudflare-turnstile'); ?> <a href="https://dash.cloudflare.com/?to=/:account/turnstile" target="_blank">https://dash.cloudflare.com/?to=/:account/turnstile</a></p>

            <?php
      if(get_option('wtcc_cloudflare_tested') == 'yes') {
        echo '<p class="text_green"><span class="dashicons dashicons-yes-alt"></span> ' . __( 'Success! Turnstile seems to be working correctly with your API keys.', 'wp-cloudflare-turnstile' ) . '</p>';
      } ?>
                <table class="form-table">
                    <tbody>
                        <tr>
                            <th scope="row"><?php _e( 'Site Key' , 'wp-cloudflare-turnstile') ?> 
                            </th>
                            <td>
                                <input type="text" style="width: 260px;" name="wtcc_cloudflare_site_key" value="<?php echo sanitize_text_field( get_option('wtcc_cloudflare_site_key') ); ?>">
                            </td>
                        </tr>
                        <tr>
                            <th scope="row"><?php _e( 'Secret Key' , 'wp-cloudflare-turnstile') ?></th>
                            <td>
                                <input type="text" style="width: 260px;" name="wtcc_cloudflare_secret_key" value="<?php echo sanitize_text_field( get_option('wtcc_cloudflare_secret_key') ); ?>">
                            </td>
                        </tr>
                    </tbody>
                </table>
                <br><br>
        </div>

        <h3 style="margin-left: 20px;"><?php _e(' General setting' , 'wp-cloudflare-turnstile') ?></h3>
        <table class="form-table" style="width:50%">
              <tr>
                <th><?php _e( 'Theme' , 'wp-cloudflare-turnstile') ?></th>
                <td><select name="wtcc_cloudflare_theme">
                        <option value="light" <?php if(!get_option('wtcc_cloudflare_theme') || get_option('wtcc_cloudflare_theme') == "light") { ?>selected<?php } ?>>Light</option>
                        <option value="dark" <?php if(get_option('wtcc_cloudflare_theme') == "dark") { ?>selected<?php } ?>>Dark</option>
                        <option value="auto" <?php if(get_option('wtcc_cloudflare_theme') == "auto") { ?>selected<?php } ?>>Auto</option>
                    </select>
              </tr>
              <tr>
                <th><?php _e( 'Disable Submit Button' , 'wp-cloudflare-turnstile') ?></th>
                <td><input type="checkbox" name="wtcc_cloudflare_disable_button" <?php if(get_option('wtcc_cloudflare_disable_button')) { ?>checked<?php } ?> ></td>
              </tr>
              <tr>
                <th style="vertical-align: top;"><?php _e( 'Custom Error Message' , 'wp-cloudflare-turnstile') ?></th>
                <td><input type="text"  name="wtcc_cloudflare_error_message" style="width:40%" value="<?php echo sanitize_text_field( get_option('wtcc_cloudflare_error_message') ); ?>" placeholder="<?php echo wtcc_cloudflare_failed_message(1); ?>"><br/><i style="font-size: 10px;"><?php echo __( 'Leave blank to use the captcha default message:', 'simple-cloudflare-turnstile' ) . ' "' . wtcc_cloudflare_failed_message(1) . '"'; ?></i></td>
              </tr>
              
            </table>
            <br><br>

        <div class="wct-form">
            <h2><?php _e( 'Defaults Wordpress Forms' , 'wp-cloudflare-turnstile') ?></h2>
            <h3 style="margin-left: 20px;"><?php _e( 'Enable Captcha on your wordpress forms:' , 'wp-cloudflare-turnstile') ?></h3>
            <table class="form-table" style="width:50%">
              <tr>
                <th><?php _e( 'WordPress Login' , 'wp-cloudflare-turnstile') ?></th>
                <td><input type="checkbox" name="wtcc_cloudflare_wplogin" <?php if(get_option('wtcc_cloudflare_wplogin')) { ?>checked<?php } ?> ></td>
              </tr>
              <tr>
                <th><?php _e( 'WordPress Register' , 'wp-cloudflare-turnstile') ?></th>
                <td><input type="checkbox" name="wtcc_cloudflare_wpregister" <?php if(get_option('wtcc_cloudflare_wpregister')) { ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <th><?php _e( 'WordPress Reset' , 'wp-cloudflare-turnstile') ?> Password</th>
                <td><input type="checkbox" name="wtcc_cloudflare_wpreset" <?php if(get_option('wtcc_cloudflare_wpreset')) { ?>checked<?php } ?>></td>
              </tr>
              <tr>
                <th><?php _e( 'WordPress Comment' , 'wp-cloudflare-turnstile') ?></th>
                <td><input type="checkbox" name="wtcc_cloudflare_wpcomment" <?php if(get_option('wtcc_cloudflare_wpcomment')) { ?>checked<?php } ?>></td>
              </tr>
            </table>
        </div><br><br>

        <?php // WooCommerce
            if ( class_exists( 'WooCommerce' ) ){?>
           
           <div class="wct-form">
              <h2><?php _e( 'WooCommerce Forms' , 'wp-cloudflare-turnstile') ?></h2>
              <h3 style="margin-left: 20px;"><?php _e( 'Enable Captcha on your WooCommerce forms:' , 'wp-cloudflare-turnstile') ?></h3>
              <table class="form-table" style="width:50%">
                <tr>
                  <th><?php _e( 'WooCommerce Checkout' , 'wp-cloudflare-turnstile') ?></th>
                  <td><input type="checkbox" name="wtcc_cloudflare_woo_checkout" <?php if(get_option('wtcc_cloudflare_woo_checkout')) { ?>checked<?php } ?> ></td>
                </tr>
                <tr>
                  <th><?php _e( 'Guest Checkout Only' , 'wp-cloudflare-turnstile') ?></th>
                  <td><input type="checkbox" name="wtcc_cloudflare_woo_guest_checkout" <?php if(get_option('wtcc_cloudflare_woo_guest_checkout')) { ?>checked<?php } ?> ></td>
                </tr>
                <tr>
                  <th><?php _e( 'WooCommerce Login' , 'wp-cloudflare-turnstile') ?></th>
                  <td><input type="checkbox" name="wtcc_cloudflare_woo_login" <?php if(get_option('wtcc_cloudflare_woo_login')) { ?>checked<?php } ?>></td>
                </tr>
                <tr>
                  <th><?php _e( 'WooCommerce Register' , 'wp-cloudflare-turnstile') ?></th>
                  <td><input type="checkbox" name="wtcc_cloudflare_woo_register" <?php if(get_option('wtcc_cloudflare_woo_register')) { ?>checked<?php } ?>></td>
                </tr>
                <tr>
                  <th><?php _e( 'WooCommerce Reset Password' , 'wp-cloudflare-turnstile') ?></th>
                  <td><input type="checkbox" name="wtcc_cloudflare_woo_reset" <?php if(get_option('wtcc_cloudflare_woo_reset')) { ?>checked<?php } ?>></td>
                </tr>
              </table>
            </div>
           
      </div>

        <?php } ?> 
        <?php submit_button(); ?>    
  </form>
</div>

</div>
</div>
</div>
