/* WP */
function wpcloudflareCallback() 
{
    jQuery('#wp-submit').css('pointer-events', 'auto');
    jQuery('#wp-submit').css('opacity', '1');
}

function wpcloudflareCommentCallback() {
    jQuery('.cf-turnstile-comment').css('pointer-events', 'auto');
    jQuery('.cf-turnstile-comment').css('opacity', '1');
}

/* Woo */
function wpcloudflareWooLoginCallback() {
    jQuery('.woocommerce-form-login__submit').css('pointer-events', 'auto');
    jQuery('.woocommerce-form-login__submit').css('opacity', '1');
}
function wpcloudflareWooRegisterCallback() {
    jQuery('.woocommerce-form-register__submit').css('pointer-events', 'auto');
    jQuery('.woocommerce-form-register__submit').css('opacity', '1');
}
function wpcloudflareWooResetCallback() {
    jQuery('.woocommerce-ResetPassword .button').css('pointer-events', 'auto');
    jQuery('.woocommerce-ResetPassword .button').css('opacity', '1');
}