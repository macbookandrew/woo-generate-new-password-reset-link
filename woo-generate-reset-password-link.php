<?php
/*
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
function woo_new_account_password_reset_link( $user_login ) {
    $user_data = get_user_by('login', $user_login);

    // Redefining user_login ensures we return the right case in the email
    $user_login = $user_data->user_login;
    $user_email = $user_data->user_email;
    $key = get_password_reset_key( $user_data );

    echo network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user_login), 'login');
}
