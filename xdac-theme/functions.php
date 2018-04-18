<?php
/**
 * Appai theme functions and definitions
 */


/**
 * Redirects
 */
add_action('wp', 'redirectFromLoginpage');

function redirectFromLoginpage() {
	global $post;
    if (is_object($post) && (int) $post->ID === 2591 || is_object($post) && (int) $post->ID === 2594) {
        global $wppb_login;
        if (is_user_logged_in() || isset($wppb_login->ID)) {  // Already logged in

		//mail("info@xdac.co","1- ".$wppb_login->ID." ".is_user_logged_in()." $a","","");
            wp_redirect(site_url() . '/account/');
            die;
        }
    }
}

add_action('wp', 'redirectLogedoutUser');

function redirectLogedoutUser() {
	global $post;
	if ((int) $post->ID === 3391 || (int) $post->ID === 3416 || (int) $post->ID === 2601  || (int) $post->ID === 3562) {

		if (!is_user_logged_in() AND !isset($wppb_login->ID)) {  // Already logged out
			wp_redirect(site_url() . '/login/');
			die;
		}
    }
}

add_action('wp', 'redirectLogout');

function redirectLogout() {
	global $post;
	if ((int) $post->ID === 2599) {

		 if (is_user_logged_in() || isset($wppb_login->ID)) {
            wp_logout();
			wp_redirect(site_url() . '/');
			die;
		}
    }
}
// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Intialize Shape
 *
 */
require trailingslashit( get_template_directory() ) . 'inc/init.php';
