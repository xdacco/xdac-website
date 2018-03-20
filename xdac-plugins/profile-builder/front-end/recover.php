<?php
/**
 * Function that checks if a user is approved before reseting the password
 *
 * @param string $data either the user login or the users email
 * @param string $what what field we query for when getting the user
 */
function wppb_check_for_unapproved_user( $data, $what ){
	$retMessage = '';
	$messageNo = '';

	$wppb_generalSettings = get_option( 'wppb_general_settings' );
	if( !empty( $wppb_generalSettings['adminApproval'] ) && $wppb_generalSettings['adminApproval'] == 'yes' ){
		$user = ( ( $what == 'user_email' ) ? get_user_by( 'email', $data ) : get_user_by( 'login', $data ) );

		if ( wp_get_object_terms( $user->data->ID, 'user_status' ) ){
			$retMessage = '<strong>'. __('ERROR', 'profile-builder') . '</strong>: ' . __('Your account has to be confirmed by an administrator before you can use the "Password Reset" feature.', 'profile-builder');
			$retMessage = apply_filters('wppb_recover_password_unapporved_user', $retMessage);

			$messageNo = '6';
		}
	}

	return array( $retMessage, $messageNo );
}

/**
 * Function that retrieves the unique user key from the database. If we don't have one we generate one and add it to the database
 *
 * @param string $requested_user_login the user login
 *
 */
function wppb_retrieve_activation_key( $requested_user_login ){
	global $wpdb;

	$key = $wpdb->get_var( $wpdb->prepare( "SELECT user_activation_key FROM $wpdb->users WHERE user_login = %s", $requested_user_login ) );

	if ( empty( $key ) ) {

		// Generate something random for a key...
		$key = wp_generate_password( 20, false );
		do_action('wppb_retrieve_password_key', $requested_user_login, $key);

		// Now insert the new md5 key into the db
		$wpdb->update($wpdb->users, array('user_activation_key' => $key), array('user_login' => $requested_user_login));
	}

	return $key;
}

 /**
 * Function that creates a generate new password form
 *
 * @param array $post_data $_POST
 *
 */
function wppb_create_recover_password_form( $user, $post_data ){
	?>
	<form enctype="multipart/form-data" method="post" id="wppb-recover-password" class="wppb-user-forms" action="<?php echo esc_url( add_query_arg( 'finalAction', 'yes', wppb_curpageurl() ) ); ?>">
		<ul>
	<?php

        if( !empty( $post_data['passw1'] ) )
            $passw_one = $post_data['passw1'];
        else
            $passw_one = '';

        if( !empty( $post_data['passw2'] ) )
            $passw_two = $post_data['passw2'];
        else
            $passw_two = '';

		$password_label = __( 'Password', 'profile-builder' );
		$repeat_password_label = __( 'Repeat Password', 'profile-builder' );

		$recover_inputPassword = '
			<li class="wppb-form-field passw1">
				<label for="passw1">'. $password_label .'</label>
				<input class="password" name="passw1" type="password" id="passw1" value="" autocomplete="off" title="'. wppb_password_length_text() .'" '. apply_filters( 'wppb_recover_password_extra_attr', '', $password_label, 'password' ) .' />
				<span class="wppb-description-delimiter">'. wppb_password_length_text() .' '. wppb_password_strength_description() .'</span>
			</li><!-- .passw1 -->
			<input type="hidden" name="userData" value="'.$user->ID.'"/>
			<li class="wppb-form-field passw2">
				<label for="passw2">'. $repeat_password_label .'</label>
				<input class="password" name="passw2" type="password" id="passw2" value="" autocomplete="off" '. apply_filters( 'wppb_recover_password_extra_attr', '', $repeat_password_label, 'repeat_password' ) .' />
			</li><!-- .passw2 -->';

        /* if we have active the password strength checker */
        $recover_inputPassword .= wppb_password_strength_checker_html();

		echo apply_filters( 'wppb_recover_password_form_input', $recover_inputPassword, $passw_one, $passw_two, $user->ID );
?>
		</ul>
		<p class="form-submit">
			<?php $button_name = __('Reset Password', 'profile-builder'); ?>
			<input name="recover_password2" type="submit" id="wppb-recover-password-button" class="<?php echo apply_filters( 'wppb_recover_submit_class', "submit button" );?>" value="<?php echo apply_filters('wppb_recover_password_button_name1', $button_name); ?>" />
			<input name="action2" type="hidden" id="action2" value="recover_password2" />
		</p><!-- .form-submit -->
		<?php wp_nonce_field( 'verify_true_password_recovery2_'.$user->ID, 'password_recovery_nonce_field2' ); ?>
	</form><!-- #recover_password -->
	<?php
}

/**
 * Function that generates the recover password form
 *
 * @param WP_User $user the user object
 * @param array $post_data $_POST
 *
 */
 function wppb_create_generate_password_form( $post_data ){
	?>
	<form enctype="multipart/form-data" method="post" id="wppb-recover-password" class="wppb-user-forms" action="<?php echo esc_url( add_query_arg( 'submitted', 'yes', wppb_curpageurl() ) ); ?>">
	<?php
	$wppb_generalSettings = get_option( 'wppb_general_settings' );

	if( !empty( $wppb_generalSettings['loginWith'] ) && $wppb_generalSettings['loginWith'] == 'email' ){
		$recover_notification = '<p>' . __( 'Please enter your email address.', 'profile-builder' );
		$username_email_label = __( 'E-mail', 'profile-builder' );
	}
	else{
		$recover_notification = '<p>' . __( 'Please enter your username or email address.', 'profile-builder' );
		$username_email_label = __( 'Username or E-mail', 'profile-builder' );
	}

	$recover_notification .= '<br/>'.__( 'You will receive a link to create a new password via email.', 'profile-builder' ).'</p>';
	echo apply_filters( 'wppb_recover_password_message1', $recover_notification );

	$username_email = ( isset( $post_data['username_email'] ) ? $post_data['username_email'] : '' );

	$recover_input = '<ul>
			<li class="wppb-form-field wppb-username-email">
				<label for="username_email">'. $username_email_label .'</label>
				<input class="text-input" name="username_email" type="text" id="username_email" value="'.esc_attr( trim( $username_email ) ).'" '. apply_filters( 'wppb_recover_password_extra_attr', '', $username_email_label, 'username_email' ) .' />
			</li><!-- .username_email --></ul>';
	echo apply_filters( 'wppb_recover_password_generate_password_input', $recover_input, trim( $username_email ) );
		?>
	<p class="form-submit">
		<?php $button_name = __('Get New Password', 'profile-builder'); ?>
		<input name="recover_password" type="submit" id="wppb-recover-password-button" class="submit button" value="<?php echo apply_filters('wppb_recover_password_button_name3', $button_name); ?>" />
		<input name="action" type="hidden" id="action" value="recover_password" />
	</p>
	<?php wp_nonce_field( 'verify_true_password_recovery', 'password_recovery_nonce_field' ); ?>
	</form>
	<?php
}

/**
 * The function for the recover password shortcode
 *
 */
function wppb_front_end_password_recovery(){
    global $wppb_shortcode_on_front;
    $wppb_shortcode_on_front = true;
	$message = $messageNo = $message2 = $messageNo2 = $linkLoginName = $linkKey = '';

	global $wpdb;

	if( is_user_logged_in() )
		return apply_filters( 'wppb_recover_password_already_logged_in', __( 'You are already logged in. You can change your password on the edit profile form.', 'profile-builder' ) );

	ob_start();

    //Get general settings
    $wppb_generalSettings = get_option( 'wppb_general_settings' );

	// If the user entered an email/username, process the request
	if ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action'] ) && $_POST['action'] == 'recover_password' && wp_verify_nonce($_POST['password_recovery_nonce_field'],'verify_true_password_recovery') ) {
		// filter must be applied on the $_POST variable so that the value returned to the form can be corrected too
		$_POST['username_email'] = apply_filters( 'wppb_before_processing_email_from_forms', $_POST['username_email'] );	//we get the raw data
		$postedData = $_POST['username_email'];
		//check to see if it's an e-mail (and if this is valid/present in the database) or is a username


		// if we do not have an email in the posted date we try to get the email for that user
		if( !is_email( $postedData ) ){
			/* make sure it is a username */
			$postedData = sanitize_user( $postedData );
			if (username_exists($postedData)){
				$query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_login= %s", $postedData ) );
				if( !empty( $query[0] ) ){
					$postedData = $query[0]->user_email;
				}
			}
			else{
				$message = __( 'The username entered wasn\'t found in the database!', 'profile-builder').'<br/>'.__('Please check that you entered the correct username.', 'profile-builder' );
				$message = apply_filters( 'wppb_recover_password_sent_message4', $message );
				$messageNo = '4';
			}
		}

		// we should have an email by this point
		if ( is_email( $postedData ) ){
			if ( email_exists( $postedData ) ){
				$retVal = wppb_check_for_unapproved_user($postedData, 'user_email');
				if ($retVal[0] != ''){
					$message = $retVal[0];
					$messageNo = $retVal [1];

				}else{
					$message = sprintf( __( 'Check your e-mail for the confirmation link.', 'profile-builder'), $postedData );
					$message = apply_filters( 'wppb_recover_password_sent_message1', $message, $postedData );
					$messageNo = '1';

				}

			}elseif ( !email_exists( $postedData ) ){
				$message = __('The email address entered wasn\'t found in the database!', 'profile-builder').'<br/>'.__('Please check that you entered the correct email address.', 'profile-builder');
				$message = apply_filters('wppb_recover_password_sent_message2', $message);
				$messageNo = '2';
			}
		}

        // For some extra validations you can filter messageNo
        $messageNo = apply_filters( 'wppb_recover_password_message_no', $messageNo );

        if( $messageNo == '1' ) {

            //verify e-mail validity
            $query = $wpdb->get_results( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_email= %s", sanitize_email( $postedData ) ) );
            if( !empty( $query[0] ) ){
                $requestedUserID = $query[0]->ID;
                $requestedUserLogin = $query[0]->user_login;
                $requestedUserEmail = $query[0]->user_email;
                $requestedUserNicename = $query[0]->user_nicename;

                if( $wppb_generalSettings['loginWith'] == 'username' || $wppb_generalSettings['loginWith'] == 'usernameemail' )
                    $display_username_email = $query[0]->user_login;
                else
                    $display_username_email = $query[0]->user_email;

                //search if there is already an activation key present, if not create one
                $key = wppb_retrieve_activation_key( $requestedUserLogin );

                //send primary email message
                $recoveruserMailMessage1  = sprintf( __('Someone requested that the password be reset for the following account: <b>%1$s</b><br/>If this was a mistake, just ignore this email and nothing will happen.<br/>To reset your password, visit the following link:%2$s', 'profile-builder'), $display_username_email, '<a href="'.esc_url( add_query_arg( array( 'loginName' => $requestedUserNicename, 'key' => $key ), wppb_curpageurl() ) ).'">'.esc_url( add_query_arg( array( 'loginName' => $requestedUserNicename, 'key' => $key ), wppb_curpageurl() ) ).'</a>');
                $recoveruserMailMessage1  = apply_filters( 'wppb_recover_password_message_content_sent_to_user1', $recoveruserMailMessage1, $requestedUserID, $requestedUserLogin, $requestedUserEmail );

                $recoveruserMailMessageTitle1 = sprintf(__('Password Reset from "%1$s"', 'profile-builder'), $blogname = get_option('blogname') );
                $recoveruserMailMessageTitle1 = apply_filters('wppb_recover_password_message_title_sent_to_user1', $recoveruserMailMessageTitle1, $requestedUserLogin);

				$recoveruserMailFrom = apply_filters ( 'wppb_recover_password_notification_email_from_field', get_bloginfo( 'name' ) );
				$recoveruserMailContext = 'email_user_recover';

                //send mail to the user notifying him of the reset request
                if (trim($recoveruserMailMessageTitle1) != ''){
                    $sent = wppb_mail($requestedUserEmail, $recoveruserMailMessageTitle1, $recoveruserMailMessage1, $recoveruserMailFrom, $recoveruserMailContext);
                    if ($sent === false){
                        $message = '<b>'. __( 'ERROR', 'profile-builder' ) .': </b>' . sprintf( __( 'There was an error while trying to send the activation link to %1$s!', 'profile-builder' ), $postedData );
                        $message = apply_filters( 'wppb_recover_password_sent_message_error_sending', $message );
                        $messageNo = '5';
                    }
                }
            }

        }

	}
	// If the user used the correct key-code, update his/her password
	elseif ( 'POST' == $_SERVER['REQUEST_METHOD'] && !empty( $_POST['action2'] ) && $_POST['action2'] == 'recover_password2' && wp_verify_nonce( $_POST['password_recovery_nonce_field2'], 'verify_true_password_recovery2_'.absint( $_POST['userData'] ) ) ) {

        if( ( $_POST['passw1'] == $_POST['passw2'] ) && ( !empty( $_POST['passw1'] ) && !empty( $_POST['passw2'] ) ) ){
            if( !empty( $wppb_generalSettings['minimum_password_length'] ) || ( isset( $_POST['wppb_password_strength'] ) && !empty( $wppb_generalSettings['minimum_password_strength'] ) ) ){
                $message2 = '';
                if( wppb_check_password_length( $_POST['passw1'] ) ){
                    $message2 .= '<br/>' . sprintf( __( "The password must have the minimum length of %s characters", "profile-builder" ), $wppb_generalSettings['minimum_password_length'] ) . '<br/>';
                    $messageNo2 = '2';
                }
                if( wppb_check_password_strength() ){
                    $message2 .= '<br/>'. sprintf( __( "The password must have a minimum strength of %s", "profile-builder" ), wppb_check_password_strength() );
                    $messageNo2 = '2';
                }
            }

            if( $messageNo2 != 2 ){

                $message2 = __( 'Your password has been successfully changed!', 'profile-builder' );
                $messageNo2 = '1';

                $userID = absint( $_POST['userData'] );
                $new_pass = $_POST['passw1'];

                //update the new password and delete the key
                do_action( 'wppb_password_reset', $userID, $new_pass );

                wp_set_password( $new_pass, $userID );

				/* log out of all sessions on password reset */
				$sessions = WP_Session_Tokens::get_instance( $userID );
				$sessions->destroy_all();


                $user_info = get_userdata( $userID );

                if( $wppb_generalSettings['loginWith'] == 'username' || $wppb_generalSettings['loginWith'] == 'usernameemail' )
                    $display_username_email = $user_info->user_login;
                else
                    $display_username_email = $user_info->user_email;

                //send secondary mail to the user containing the username and the new password
                $recoveruserMailMessage2  = __( 'You have successfully reset your password.', 'profile-builder' );
                $recoveruserMailMessage2  = apply_filters( 'wppb_recover_password_message_content_sent_to_user2', $recoveruserMailMessage2, $display_username_email, $new_pass, $userID );

                $recoveruserMailMessageTitle2 = sprintf( __('Password Successfully Reset for %1$s on "%2$s"', 'profile-builder' ), $display_username_email, $blogname = get_option('blogname') );
                $recoveruserMailMessageTitle2 = apply_filters( 'wppb_recover_password_message_title_sent_to_user2', $recoveruserMailMessageTitle2, $display_username_email );

				$recoveruserMailFrom2 = apply_filters ( 'wppb_recover_password_success_notification_email_from_field', get_bloginfo( 'name' ) );
				$recoveruserMailContext2 = 'email_user_recover_success';

                //send mail to the user notifying him of the reset request
                if ( trim( $recoveruserMailMessageTitle2 ) != '' )
                    wppb_mail( $user_info->user_email, $recoveruserMailMessageTitle2, $recoveruserMailMessage2, $recoveruserMailFrom2, $recoveruserMailContext2 );

                //send email to admin
                $recoveradminMailMessage = sprintf( __( '%1$s has requested a password change via the password reset feature.<br/>His/her new password is:%2$s', 'profile-builder' ), $display_username_email, $_POST['passw1'] );
                $recoveradminMailMessage = apply_filters( 'wppb_recover_password_message_content_sent_to_admin', $recoveradminMailMessage, $display_username_email, $_POST['passw1'], $userID );

                $recoveradminMailMessageTitle = sprintf( __( 'Password Successfully Reset for %1$s on "%2$s"', 'profile-builder' ), $display_username_email, $blogname = get_option('blogname'), ENT_QUOTES );
                $recoveradminMailMessageTitle = apply_filters( 'wppb_recover_password_message_title_sent_to_admin', $recoveradminMailMessageTitle, $display_username_email );


                //we disable the feature to send the admin a notification mail but can be still used using filters
                $recoveradminMailMessageTitle = '';
                $recoveradminMailMessageTitle = apply_filters( 'wppb_recover_password_message_title_sent_to_admin', $recoveradminMailMessageTitle, $display_username_email );

				$recoveradminMailContext = 'email_admin_recover_success';

                //send mail to the admin notifying him of of a user with a password reset request
                if (trim($recoveradminMailMessageTitle) != '')
                    wppb_mail(get_option('admin_email'), $recoveradminMailMessageTitle, $recoveradminMailMessage, $recoveruserMailFrom2, $recoveradminMailContext);
            }
		}
        else{
            $message2 = __( 'The entered passwords don\'t match!', 'profile-builder' );
            $messageNo2 = '2';
        }

	}

?>

	<div class="wppb_holder" id="wppb-recover-password-container">

<?php
			// use this action hook to add extra content before the password recovery form
			do_action( 'wppb_before_recover_password_fields' );

			//this is the part that handles the actual recovery
			if( isset( $_GET['submitted'] ) && isset( $_GET['loginName'] ) && isset( $_GET['key'] ) && !empty( $_GET['key'] ) ){
				//get the login name and key and verify if they match the ones in the database

				$key = sanitize_text_field( $_GET['key'] );
				$login_nicename = sanitize_user( $_GET['loginName'] );

				$user = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->users WHERE user_activation_key = %s AND user_nicename = %s", $key, $login_nicename ) );

				if( !empty( $user ) ){
					//check if the "finalAction" variable is not in the address bar, if it is, don't display the form anymore
					if( isset( $_GET['finalAction'] ) && ( $_GET['finalAction'] == 'yes' ) ){
						if( $messageNo2 == '2' ){
							echo apply_filters( 'wppb_recover_password_password_changed_message2', '<p class="wppb-error">'.$message2.'</p>', $message2 );

							wppb_create_recover_password_form( $user, $_POST );

						}elseif( $messageNo2 == '1' )
							echo apply_filters( 'wppb_recover_password_password_changed_message1', '<p class="wppb-success">'.$message2.'</p>', $message2 );

					}else{
						wppb_create_recover_password_form( $user, $_POST );
					}
				}else{
					if( $messageNo2 == '1' ) {
					    // CHECK FOR REDIRECT
                        $redirect_url = wppb_get_redirect_url( 'normal', 'after_success_password_reset', '', sanitize_user( $_GET['loginName'] ) );
						$redirect_delay = apply_filters( 'wppb_success_password_reset_redirect_delay', 3, sanitize_user( $_GET['loginName'] ) );
                        $redirect_message = wppb_build_redirect( $redirect_url, $redirect_delay, 'after_success_password_reset' );

						echo apply_filters( 'wppb_recover_password_password_changed_message1', '<p class="wppb-success">' . $message2 . '</p>', $message2 );

						if( isset( $redirect_message ) && ! empty( $redirect_message ) ) {
							echo '<p>' . $redirect_message . '</p>';
						}
					}

					elseif( $messageNo2 == '2' )
						echo apply_filters( 'wppb_recover_password_password_changed_message2', '<p class="wppb-error">'.$message2.'</p>', $message2 );

					else
						echo apply_filters( 'wppb_recover_password_invalid_key_message', '<p class="wppb-warning"><b>'.__( 'ERROR:', 'profile-builder' ).'</b>'.__( 'Invalid key!', 'profile-builder' ).'</p>' );
				}

			}else{
				//display error message and the form
				if (($messageNo == '') || ($messageNo == '2') || ($messageNo == '4')){
                    if( !empty( $message ) )
					    echo apply_filters( 'wppb_recover_password_displayed_message1', '<p class="wppb-warning">'.$message.'</p>' );

					wppb_create_generate_password_form( $_POST );

				}elseif (($messageNo == '5')  || ($messageNo == '6'))
					echo  apply_filters( 'wppb_recover_password_displayed_message1', '<p class="wppb-warning">'.$message.'</p>' );

				else
					echo apply_filters( 'wppb_recover_password_displayed_message2', '<p class="wppb-success">'.$message.'</p>' ); //display success message
			}

			// use this action hook to add extra content after the password recovery form.
			do_action( 'wppb_after_recover_password_fields' );
?>
	</div>

<?php
	$output = ob_get_contents();
    ob_end_clean();

    return $output;
}