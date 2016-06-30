<?php
/**
 * Plugin Name: Woo Generate New Password Reset Link
 * Plugin URI: http://andrewrminion.com/2016/06/woo-generate-new-password-reset-link/
 * Description: Sends new account users an email with a password reset link rather than an auto-generated password
 * Version: 1.0.0
 * Author: AndrewRMinion Design
 * Author URI: https://www.andrewrminion.com
 */

if (!defined('ABSPATH')) {
    exit;
}

// generate password reset link
// borrowed from WordPress core
function woo_new_account_password_reset_link( $user_login ) {
    $user_data = get_user_by('login', $user_login);

    // Redefining user_login ensures we return the right case in the email
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key = get_password_reset_key( $user_data );

    return network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
}

// use plugin-supplied template
add_filter( 'woocommerce_locate_template', 'woo_new_account_password_locate_templates', 10, 3 );
function woo_new_account_password_locate_templates( $template, $template_name, $template_path ) {
    global $woocommerce;

    $old_template = $template;
    if ( ! $template_path ) {
        $template_path = $woocommerce->template_url;
    }
    $plugin_path  = plugin_dir_path( __FILE__ ) . 'woocommerce/';

    // Look within passed path within the theme - this is priority
    $template = locate_template(
        array(
            $template_path . $template_name,
            $template_name,
        )
    );

    // Modification: Get the template from this plugin, if it exists
    if ( ! $template && file_exists( $plugin_path . $template_name ) ) {
        $template = $plugin_path . $template_name;
    }

    // Use default template
    if ( ! $template ) {
        $template = $old_template;
    }

    // Return what we found
    return $template;
}
