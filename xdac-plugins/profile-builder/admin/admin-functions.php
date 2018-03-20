<?php
/**
 * Function which returns the same field-format over and over again.
 *
 * @since v.2.0
 *
 * @param string $field
 * @param string $field_title
 *
 * @return string
 */
function wppb_field_format ( $field_title, $field ){

	return trim( $field_title ).' ( '.trim( $field ).' )';
}


/**
 * Add a notification for either the Username or the Email field letting the user know that, even though it is there, it won't do anything
 *
 * @since v.2.0
 *
 * @param string $form
 * @param integer $id
 * @param string $value
 *
 * @return string $form
 */

function wppb_manage_fields_display_field_title_slug( $form ){
    // add a notice to fields
	global $wppb_results_field;
    switch ($wppb_results_field){
        case 'Default - Username':
            $wppb_generalSettings = get_option( 'wppb_general_settings', 'not_found' );
            if ( $wppb_generalSettings != 'not_found' && $wppb_generalSettings['loginWith'] == 'email' ) {
                $form .= '<div id="wppb-login-email-nag" class="wppb-backend-notice">' . sprintf(__('Login is set to be done using the E-mail. This field will NOT appear in the front-end! ( you can change these settings under the "%s" tab )', 'profile-builder'), '<a href="' . admin_url('admin.php?page=profile-builder-general-settings') . '" target="_blank">' . __('General Settings', 'profile-builder') . '</a>') . '</div>';
            }
            break;
        case 'Default - Display name publicly as':
            $form .= '<div id="wppb-display-name-nag" class="wppb-backend-notice">' . __( 'Display name publicly as - only appears on the Edit Profile page!', 'profile-builder' ) . '</div>';
            break;
        case 'Default - Blog Details':
            $form .= '<div id="wppb-blog-details-nag" class="wppb-backend-notice">' . __( 'Blog Details - only appears on the Registration page!', 'profile-builder' ) . '</div>';
            break;
    }

    return $form;
}

add_filter( 'wck_after_content_element', 'wppb_manage_fields_display_field_title_slug' );

/**
 * Check if field type is 'Default - Display name publicly as' so we can add a notification for it
 *
 * @since v.2.2
 *
 * @param string $wck_element_class
 * @param string $meta
 * @param array $results
 * @param integer $element_id
 *
 */
function wppb_manage_fields_display_name_notice( $wck_element_class, $meta, $results, $element_id ) {
	global $wppb_results_field;

	$wppb_results_field = $results[$element_id]['field'];
}
add_filter( 'wck_element_class_wppb_manage_fields', 'wppb_manage_fields_display_name_notice', 10, 4 );



/**
 * Function that adds a custom class to the existing container
 *
 * @since v.2.0
 *
 * @param string $update_container_class - the new class name
 * @param string $meta - the name of the meta
 * @param array $results
 * @param integer $element_id - the ID of the element
 *
 * @return string
 */
function wppb_update_container_class( $update_container_class, $meta, $results, $element_id ) {
	$wppb_element_type = Wordpress_Creation_Kit_PB::wck_generate_slug( $results[$element_id]["field"] );
	
	return "class='wck_update_container update_container_$meta update_container_$wppb_element_type element_type_$wppb_element_type'";
}
add_filter( 'wck_update_container_class_wppb_manage_fields', 'wppb_update_container_class', 10, 4 );


/**
 * Function that adds a custom class to the existing element
 *
 * @since v.2.0
 *
 * @param string $element_class - the new class name
 * @param string $meta - the name of the meta
 * @param array $results
 * @param integer $element_id - the ID of the element
 *
 * @return string
 */
function wppb_element_class( $element_class, $meta, $results, $element_id ){
	$wppb_element_type = Wordpress_Creation_Kit_PB::wck_generate_slug( $results[$element_id]["field"] );
	
	return "class='element_type_$wppb_element_type added_fields_list'";
}
add_filter( 'wck_element_class_wppb_manage_fields', 'wppb_element_class', 10, 4 );

/**
 * Functions to check password length and strength
 *
 * @since v.2.0
 */
/* on add user and update profile from WP admin area */
add_action( 'user_profile_update_errors', 'wppb_password_check_on_profile_update', 0, 3 );
function wppb_password_check_on_profile_update( $errors, $update, $user ){
    wppb_password_check_extra_conditions( $errors, $user );
}

/* on reset password */
add_action( 'validate_password_reset', 'wppb_password_check_extra_conditions', 10, 2 );
function wppb_password_check_extra_conditions( $errors, $user ){
    $password = ( isset( $_POST[ 'pass1' ] ) && trim( $_POST[ 'pass1' ] ) ) ? $_POST[ 'pass1' ] : false;

    if( $password ){
        $wppb_generalSettings = get_option( 'wppb_general_settings' );
        if( !empty( $wppb_generalSettings['minimum_password_length'] ) ){
            if( strlen( $password ) < $wppb_generalSettings['minimum_password_length'] )
                $errors->add( 'pass', sprintf( __( '<strong>ERROR</strong>: The password must have the minimum length of %s characters', 'profile-builder' ), $wppb_generalSettings['minimum_password_length'] ) );
        }

        if( isset( $_POST['wppb_password_strength'] ) && !empty( $wppb_generalSettings['minimum_password_strength'] ) ){
            $password_strength_array = array( 'short' => 0, 'bad' => 1, 'good' => 2, 'strong' => 3 );
            $password_strength_text = array( 'short' => __( 'Very weak', 'profile-builder' ), 'bad' => __( 'Weak', 'profile-builder' ), 'good' => __( 'Medium', 'profile-builder' ), 'strong' => __( 'Strong', 'profile-builder' ) );

            foreach( $password_strength_text as $psr_key => $psr_text ){
                if( $psr_text == sanitize_text_field( $_POST['wppb_password_strength'] ) ){
                    $password_strength_result_slug = $psr_key;
                    break;
                }
            }

            if( !empty( $password_strength_result_slug ) ){
                if( $password_strength_array[$password_strength_result_slug] < $password_strength_array[$wppb_generalSettings['minimum_password_strength']] )
                    $errors->add( 'pass', sprintf( __( '<strong>ERROR</strong>: The password must have a minimum strength of %s', 'profile-builder' ), $password_strength_text[$wppb_generalSettings['minimum_password_strength']] ) );
            }
        }
    }

    return $errors;
}

/* we need to create a hidden field that contains the results of the password strength from the js strength tester */
add_action( 'admin_footer', 'wppb_add_hidden_password_strength_on_backend' );
add_action( 'login_footer', 'wppb_add_hidden_password_strength_on_backend' );
function wppb_add_hidden_password_strength_on_backend(){
    if( $GLOBALS['pagenow'] == 'profile.php' || $GLOBALS['pagenow'] == 'user-new.php' || ( $GLOBALS['pagenow'] == 'wp-login.php' && isset( $_GET['action'] ) && ( $_GET['action'] == 'rp' || $_GET['action'] == 'resetpass' ) ) ){
        $wppb_generalSettings = get_option( 'wppb_general_settings' );
        if( !empty( $wppb_generalSettings['minimum_password_strength'] ) ){
            ?>
            <script type="text/javascript">
                jQuery( document ).ready( function() {
                    var passswordStrengthResult = jQuery( '#pass-strength-result' );
                    // Check for password strength meter
                    if ( passswordStrengthResult.length ) {
                        // Attach submit event to form
                        passswordStrengthResult.parents( 'form' ).on( 'submit', function() {
                            // Store check results in hidden field
                            jQuery( this ).append( '<input type="hidden" name="wppb_password_strength" value="' + passswordStrengthResult.text() + '">' );
                        });
                    }
                });
            </script>
            <?php
        }
    }
}


/* Modify the Add Entry buttons for WCK metaboxes according to context */
add_filter( 'wck_add_entry_button', 'wppb_change_add_entry_button', 10, 2 );
function wppb_change_add_entry_button( $string, $meta ){
    if( $meta == 'wppb_manage_fields' || $meta == 'wppb_epf_fields' || $meta == 'wppb_rf_fields' ){
        return __( "Add Field", 'profile-builder' );
    }elseif( $meta == 'wppb_epf_page_settings' || $meta == 'wppb_rf_page_settings' || $meta == 'wppb_ul_page_settings' ){
        return __( "Save Settings", 'profile-builder' );
    }

    return $string;
}

/* Add admin footer text for encouraging users to leave a review of the plugin on wordpress.org */
function wppb_admin_rate_us( $footer_text ) {
    global $current_screen;

    if ($current_screen->parent_base == 'profile-builder'){
        $rate_text = sprintf( __( 'If you enjoy using <strong> %1$s </strong> please <a href="%2$s" target="_blank">rate us on WordPress.org</a>. More happy users means more features, less bugs and better support for everyone. ', 'profile-builder' ),
            PROFILE_BUILDER,
            'https://wordpress.org/support/view/plugin-reviews/profile-builder?filter=5#postform'
        );
        return '<span id="footer-thankyou">' .$rate_text . '</span>';
    } else {
        return $footer_text;
    }
}
add_filter('admin_footer_text','wppb_admin_rate_us');

/* In plugin notifications */
add_action( 'admin_init', 'wppb_add_plugin_notifications' );
function wppb_add_plugin_notifications() {
    /* initiate the plugin notifications class */
    $notifications = WPPB_Plugin_Notifications::get_instance();
    /* this must be unique */
    $notification_id = 'wppb_new_add_on_woocommerce';

    $message  = '<img style="float: left; margin: 10px 12px 10px 0; max-width: 100px;" src="https://www.cozmoslabs.com/wp-content/themes/cozmiclight/img/pb_addon_small_woosync.png" alt="WooSync Addon"/>';
    $message .= '<p style="margin-top: 16px;">' . __( 'Extend WooCommerce checkout page with support for all the Profile Builder Pro custom field types, conditional logic and repeater fields with the latest <strong>WooSync addon</strong> for Profile Builder.', 'profile-builder' ) . '</p>';
    // be careful to use wppb_dismiss_admin_notification as query arg
    $message .= '<p><a href="' . add_query_arg( array( 'page' => 'profile-builder-add-ons', 'wppb_dismiss_admin_notification' => $notification_id ), admin_url( 'admin.php' ) ) . '" class="button-primary">' . __( 'Check it out!', 'profile-builder' ) . '</a></p>';
    $message .= '<a href="' . add_query_arg( array( 'wppb_dismiss_admin_notification' => $notification_id ) ) . '" type="button" class="notice-dismiss"><span class="screen-reader-text">' . __( 'Dismiss this notice.', 'profile-builder' ) . '</span></a>';

    $notifications->add_notification( $notification_id, $message, 'wppb-notice wppb-narrow notice notice-info', true, array( 'profile-builder-add-ons' ) );
}