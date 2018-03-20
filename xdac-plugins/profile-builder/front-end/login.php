<?php
// add hidden input to our form to identify that is a profile builder form
function wppb_login_form_bottom( $form_part, $args ){
	// we set this id in the wp_login_form() function
	if( $args['id_submit'] == 'wppb-submit' ){
		if( in_the_loop() )
			$form_location = 'page';
		else
			$form_location = 'widget';

		$form_part = '<input type="hidden" name="wppb_login" value="true"/>';
		$form_part .= '<input type="hidden" name="wppb_form_location" value="'. $form_location .'"/>';

		$form_part .= '<input type="hidden" name="wppb_request_url" value="'. esc_url( wppb_curpageurl() ).'"/>';
        $form_part .= '<input type="hidden" name="wppb_lostpassword_url" value="'.esc_url( $args['lostpassword_url'] ).'"/>';
		$form_part .= '<input type="hidden" name="wppb_redirect_priority" value="'. esc_attr( isset( $args['redirect_priority'] ) ? $args['redirect_priority'] : '' ) .'"/>';
		$form_part .= '<input type="hidden" name="wppb_referer_url" value="'.esc_url( isset( $_SERVER['HTTP_REFERER'] ) ? $_SERVER['HTTP_REFERER'] : '' ).'"/>';
		$form_part .= wp_nonce_field( 'wppb_login', 'CSRFToken-wppb', true, false );
	}

    $form_part .= '<input type="hidden" name="wppb_redirect_check" value="true"/>';

	return $form_part;
}
add_filter( 'login_form_bottom', 'wppb_login_form_bottom', 10, 2 );

// when email login is enabled we need to change the post data for the username
function wppb_change_login_with_email(){
    if( !empty( $_POST['log'] ) ){
		// only do this for our form
		if( isset( $_POST['wppb_login'] ) ){
			global $wpdb, $_POST, $wp_version;
			// apply filter to allow stripping slashes if necessary
			$_POST['log'] = apply_filters( 'wppb_before_processing_email_from_forms', $_POST['log'] );

			/* since version 4.5 there is in the core the option to login with email so we don't need the bellow code but for backward compatibility we will keep it */
			if( version_compare( $wp_version, '4.5.0' ) >= 0 )
				return;

			$wppb_generalSettings = get_option( 'wppb_general_settings' );

			// if this setting is active, the posted username is, in fact the user's email
			if( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'email' ) ){
				$username = $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM $wpdb->users WHERE user_email= %s LIMIT 1", sanitize_email( $_POST['log'] ) ) );
				
				if( !empty( $username ) )
					$_POST['log'] = $username;
				
				else {
					// if we don't have a username for the email entered we can't have an empty username because we will receive a field empty error
					$_POST['log'] = 'this_is_an_invalid_email'.time();
				}
			}

			// if this setting is active, the posted username is, in fact the user's email or username
			if( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'usernameemail' ) ) {
				if( is_email( $_POST['log'] ) ) {
					$username = $wpdb->get_var( $wpdb->prepare( "SELECT user_login FROM $wpdb->users WHERE user_email= %s LIMIT 1", sanitize_email( $_POST['log'] ) ) );
				} else {
					$username = $_POST['log'];
				}

				if( !empty( $username ) )
					$_POST['log'] = $username;

				else {
					// if we don't have a username for the email entered we can't have an empty username because we will receive a field empty error
					$_POST['log'] = 'this_is_an_invalid_email'.time();
				}
			}
		}
	}
}
add_action( 'login_init', 'wppb_change_login_with_email' );

/**
 * Remove email login when username login is selected
 * inspiration from https://wordpress.org/plugins/no-login-by-email-address/
 */
$wppb_generalSettings = get_option( 'wppb_general_settings' );
if( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'username' ) ) {
	function wppb_login_username_label()
	{
		add_filter('gettext', 'wppb_login_username_label_change', 20, 3);
		function wppb_login_username_label_change($translated_text, $text, $domain)
		{
			if ($text === 'Username or Email') {
				$translated_text = __( 'Username', 'profile-builder' );
			}
			return $translated_text;
		}
	}

	add_action('login_head', 'wppb_login_username_label');

	/**
	 * Filter wp_login_form username default
	 *
	 */
	function wppb_change_login_username_label($defaults)
	{
		$defaults['label_username'] = __( 'Username', 'profile-builder' );
		return $defaults;
	}

	add_filter('login_form_defaults', 'wppb_change_login_username_label');

	/**
	 * Remove email/password authentication
	 *
	 */
	remove_filter('authenticate', 'wp_authenticate_email_password', 20);
}

// login redirect filter. used to redirect from wp-login.php if it errors out
function wppb_login_redirect( $redirect_to, $requested_redirect_to, $user ){
    // custom redirect after login on default wp login form
    if( ! isset( $_POST['wppb_login'] ) && ! is_wp_error( $user ) ) {
        // we don't have an error make sure to remove the error from the query arg
        $redirect_to = remove_query_arg( 'loginerror', $redirect_to );

        // CHECK FOR REDIRECT
        $redirect_to = wppb_get_redirect_url( 'normal', 'after_login', $redirect_to, $user );
        $redirect_to = apply_filters( 'wppb_after_login_redirect_url', $redirect_to );
    }

	// if login action initialized by our form
    if( isset( $_POST['wppb_login'] ) ){
		if( is_wp_error( $user ) ) {
            // if we don't have a successful login we must redirect to the url of the form, so make sure this happens
            $redirect_to = esc_url_raw( $_POST['wppb_request_url'] );
            $request_form_location = sanitize_text_field( $_POST['wppb_form_location'] );
            $error_string = $user->get_error_message();

            $wppb_generalSettings = get_option('wppb_general_settings');

            if (isset($wppb_generalSettings['loginWith'])) {
                $LostPassURL = site_url('/wp-login.php?action=lostpassword');

                // if the Login shortcode has a lostpassword argument set, give the lost password error link that value
                if (!empty($_POST['wppb_lostpassword_url'])) {
                    if ( wppb_check_missing_http( $_POST['wppb_lostpassword_url'] ) ) $LostPassURL = "http://" . $_POST['wppb_lostpassword_url'];
                    else $LostPassURL = $_POST['wppb_lostpassword_url'];
                }

                //apply filter to allow changing Lost your Password link
                $LostPassURL = apply_filters('wppb_pre_login_url_filter', $LostPassURL);

                if ($user->get_error_code() == 'incorrect_password') {
                    $error_string = '<strong>' . __('ERROR', 'profile-builder') . '</strong>: ' . __('The password you entered is incorrect.', 'profile-builder') . ' ';
                    $error_string .= '<a href="' . esc_url( $LostPassURL ) . '" title="' . __('Password Lost and Found.', 'profile-builder') . '">' . __('Lost your password', 'profile-builder') . '</a>?';

                    // change the recover password link
                    $error_string = str_replace(site_url('/wp-login.php?action=lostpassword'), $LostPassURL, $error_string);
                }
                if ($user->get_error_code() == 'invalid_username') {
                    $error_string = '<strong>' . __('ERROR', 'profile-builder') . '</strong>: ' . __('Invalid username.', 'profile-builder') . ' ';
                    $error_string .= '<a href="' . esc_url( $LostPassURL ) . '" title="' . __('Password Lost and Found.', 'profile-builder') . '">' . __('Lost your password', 'profile-builder') . '</a>?';
                }
                // if login with email is enabled change the word username with email
                if ($wppb_generalSettings['loginWith'] == 'email')
                    $error_string = str_replace( __('username','profile-builder'), __('email','profile-builder'), $error_string);

				// if login with username and email is enabled change the word username with username or email
				if ($wppb_generalSettings['loginWith'] == 'usernameemail')
					$error_string = str_replace( __('username','profile-builder'), __('username or email','profile-builder'), $error_string);

            }
            // if the error string is empty it means that none of the fields were completed
            if (empty($error_string)) {
                $error_string = '<strong>' . __('ERROR', 'profile-builder') . '</strong>: ' . __('Both fields are empty.', 'profile-builder') . ' ';
                $error_string = apply_filters('wppb_login_empty_fields_error_message', $error_string);
            }

            $error_string = apply_filters('wppb_login_wp_error_message', $error_string, $user);

            // encode the error string and send it as a GET parameter
            $arr_params = array('loginerror' => urlencode(base64_encode($error_string)), 'request_form_location' => $request_form_location);
            $redirect_to = add_query_arg($arr_params, $redirect_to);
            wp_safe_redirect($redirect_to);
        }
		else{
			// we don't have an error make sure to remove the error from the query arg
			$redirect_to = remove_query_arg( 'loginerror', $redirect_to );

            // CHECK FOR REDIRECT
            $redirect_to = wppb_get_redirect_url( sanitize_text_field( $_POST['wppb_redirect_priority'] ), 'after_login', $redirect_to, $user );
            $redirect_to = apply_filters( 'wppb_after_login_redirect_url', $redirect_to );
		}
	}

	return $redirect_to;
}
add_filter( 'login_redirect', 'wppb_login_redirect', 10, 3 );


/* shortcode function */
function wppb_front_end_login( $atts ){
	/* define a global so we now we have the shortcode login present */
	global $wppb_login_shortcode;
	$wppb_login_shortcode = true;

    extract( shortcode_atts( array( 'display' => true, 'redirect' => '', 'redirect_url' => '', 'logout_redirect_url' => wppb_curpageurl(), 'register_url' => '', 'lostpassword_url' => '', 'redirect_priority' => 'normal' ), $atts ) );

	$wppb_generalSettings = get_option('wppb_general_settings');

	if( !is_user_logged_in() ){
		// set up the form arguments
		$form_args = array( 'echo' => false, 'id_submit' => 'wppb-submit' );

		// maybe set up the redirect argument
		if( ! empty( $redirect ) ) {
			$redirect_url = $redirect;
		}

        if ( ! empty( $redirect_url ) ) {
            if( $redirect_priority == 'top' ) {
                $form_args['redirect_priority'] = 'top';
            } else {
                $form_args['redirect_priority'] = 'normal';
            }

			$form_args['redirect'] = trim( $redirect_url );
		}

		// change the label argument for username is login with email is enabled
		if ( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'email' ) )
			$form_args['label_username'] = __( 'Email', 'profile-builder' );

        if ( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'username' ) )
            $form_args['label_username'] = __( 'Username', 'profile-builder' );

		// change the label argument for username on login with username or email when Username and Email is enabled
		if ( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'usernameemail' ) )
			$form_args['label_username'] = __( 'Username or Email', 'profile-builder' );

		// initialize our form variable
		$login_form = '';

		// display our login errors
		if( isset( $_GET['loginerror'] ) || isset( $_POST['loginerror'] ) ){
            $loginerror = isset( $_GET['loginerror'] ) ? $_GET['loginerror'] : $_POST['loginerror'];
            $loginerror = '<p class="wppb-error">'. wp_kses_post( urldecode( base64_decode( $loginerror ) ) ) .'</p><!-- .error -->';
            if( isset( $_GET['request_form_location'] ) ){
                if( $_GET['request_form_location'] == 'widget' && !in_the_loop() ){
                    $login_form .= $loginerror;
                }
                elseif( $_GET['request_form_location'] == 'page' && in_the_loop() ){
                    $login_form .= $loginerror;
                }
            }
		}
		// build our form
		$login_form .= '<div id="wppb-login-wrap" class="wppb-user-forms">';
        $form_args['lostpassword_url'] = $lostpassword_url;
		$login_form .= wp_login_form( apply_filters( 'wppb_login_form_args', $form_args ) );

		if ((!empty($register_url)) || (!empty($lostpassword_url))) {
                $login_form .= '<p class="login-register-lost-password">';
                $i = 0;
                if (!empty($register_url)) {
                    if ( wppb_check_missing_http( $register_url ) ) $register_url = "http://" . $register_url;
                    $login_form .= '<a href="' . esc_url($register_url) . '">'. apply_filters('wppb_login_register_text', __('Register','profile-builder')) .'</a>';
                    $i++;
                }
                if (!empty($lostpassword_url)) {
                    if ($i != 0) $login_form .= ' | ';
                    if ( wppb_check_missing_http( $lostpassword_url ) ) $lostpassword_url = "http://" . $lostpassword_url;
                    $login_form .= '<a href="'. esc_url($lostpassword_url) .'">'. apply_filters('wppb_login_lostpass_text', __('Lost your password?','profile-builder')) .'</a>';
                }
                $login_form .= '</p>';
        }

        $login_form .= apply_filters( 'wppb_login_form_bottom', '', $form_args );

        $login_form .= '</div>';
		return $login_form;

	}else{
		$user_ID = get_current_user_id();
		$wppb_user = get_userdata( $user_ID );
		
		if( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'email' ) )
			$display_name = $wppb_user->user_email;
		
		elseif($wppb_user->display_name !== '')
			$display_name = $wppb_user->user_login;
		
		else
			$display_name = $wppb_user->display_name;

		if( isset( $wppb_generalSettings['loginWith'] ) && ( $wppb_generalSettings['loginWith'] == 'usernameemail' ) )
			if( $wppb_user->user_login == Wordpress_Creation_Kit_PB::wck_generate_slug( trim( $wppb_user->user_email ) ) )
			$display_name = $wppb_user->user_email;

		elseif($wppb_user->display_name !== '')
			$display_name = $wppb_user->user_login;

		else
			$display_name = $wppb_user->display_name;

		$logged_in_message = '<p class="wppb-alert">';

        // CHECK FOR REDIRECT
        $logout_redirect_url = wppb_get_redirect_url( $redirect_priority, 'after_logout', $logout_redirect_url, $wppb_user );
        $logout_redirect_url = apply_filters( 'wppb_after_logout_redirect_url', $logout_redirect_url );

        $logout_url = '<a href="'.wp_logout_url( $logout_redirect_url ).'" class="wppb-logout-url" title="'.__( 'Log out of this account', 'profile-builder' ).'">'. __( 'Log out', 'profile-builder').' &raquo;</a>';
		$logged_in_message .= sprintf(__( 'You are currently logged in as %1$s. %2$s', 'profile-builder' ), $display_name, $logout_url );

        $logged_in_message .= '</p><!-- .wppb-alert-->';
		
		return apply_filters( 'wppb_login_message', $logged_in_message, $wppb_user->ID, $display_name );
	}
}

function wppb_login_security_check( $user, $password ) {
	if( apply_filters( 'wppb_enable_csrf_token_login_form', false ) ){
		if (isset($_POST['wppb_login'])) {
			if (!isset($_POST['CSRFToken-wppb']) || !wp_verify_nonce($_POST['CSRFToken-wppb'], 'wppb_login')) {
				$errorMessage = __('You are not allowed to do this.', 'profile-builder');
				return new WP_Error('wppb_login_csrf_token_error', $errorMessage);
			}
		}
	}

    return $user;
}
add_filter( 'wp_authenticate_user', 'wppb_login_security_check', 10, 2 );