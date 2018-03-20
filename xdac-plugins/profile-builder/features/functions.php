<?php
/**
 * Functions Load
 *
 */
 
// whitelist options, you can add more register_settings changing the second parameter
function wppb_register_settings() {
	register_setting( 'wppb_option_group', 'wppb_default_settings' );
	register_setting( 'wppb_general_settings', 'wppb_general_settings', 'wppb_general_settings_sanitize' );
	register_setting( 'wppb_display_admin_settings', 'wppb_display_admin_settings' );
	register_setting( 'wppb_profile_builder_pro_serial', 'wppb_profile_builder_pro_serial' );
	register_setting( 'wppb_profile_builder_hobbyist_serial', 'wppb_profile_builder_hobbyist_serial' );
	register_setting( 'wppb_module_settings', 'wppb_module_settings' );
	register_setting( 'wppb_module_settings_description', 'wppb_module_settings_description' );
	register_setting( 'customRedirectSettings', 'customRedirectSettings' );
	register_setting( 'customUserListingSettings', 'customUserListingSettings' );
	register_setting( 'reCaptchaSettings', 'reCaptchaSettings' );
	register_setting( 'emailCustomizer', 'emailCustomizer' );
	register_setting( 'wppb_content_restriction_settings', 'wppb_content_restriction_settings' );
}




// WPML support
function wppb_icl_t($context, $name, $value){  
	if( function_exists( 'icl_t' ) )
		return icl_t( $context, $name, $value );
		
	else
		return $value;
}


function wppb_add_plugin_stylesheet() {
	$wppb_generalSettings = get_option( 'wppb_general_settings' );

	if ( ( file_exists( WPPB_PLUGIN_DIR . '/assets/css/style-front-end.css' ) ) && ( isset( $wppb_generalSettings['extraFieldsLayout'] ) && ( $wppb_generalSettings['extraFieldsLayout'] == 'default' ) ) ){
		wp_register_style( 'wppb_stylesheet', WPPB_PLUGIN_URL . 'assets/css/style-front-end.css', array(), PROFILE_BUILDER_VERSION );
		wp_enqueue_style( 'wppb_stylesheet' );
	}
	if( is_rtl() ) {
		if ( ( file_exists( WPPB_PLUGIN_DIR . '/assets/css/rtl.css' ) ) && ( isset( $wppb_generalSettings['extraFieldsLayout'] ) && ( $wppb_generalSettings['extraFieldsLayout'] == 'default' ) ) ){
			wp_register_style( 'wppb_stylesheet_rtl', WPPB_PLUGIN_URL . 'assets/css/rtl.css', array(), PROFILE_BUILDER_VERSION );
			wp_enqueue_style( 'wppb_stylesheet_rtl' );
		}
	}
}


function wppb_show_admin_bar($content){
	global $current_user;

	$adminSettingsPresent = get_option('wppb_display_admin_settings','not_found');
	$show = null;

	if ($adminSettingsPresent != 'not_found' && $current_user->ID)
		foreach ($current_user->roles as $role_key) {
			if (empty($GLOBALS['wp_roles']->roles[$role_key]))
				continue;
			$role = $GLOBALS['wp_roles']->roles[$role_key];
			if (isset($adminSettingsPresent[$role['name']])) {
				if ($adminSettingsPresent[$role['name']] == 'show')
					$show = true;
				if ($adminSettingsPresent[$role['name']] == 'hide' && $show === null)
					$show = false;
			}
		}
	return $show === null ? $content : $show;
}


if(!function_exists('wppb_curpageurl')){
	function wppb_curpageurl() {
		$pageURL = 'http';
		
		if ((isset($_SERVER["HTTPS"])) && ($_SERVER["HTTPS"] == "on"))
			$pageURL .= "s";
			
		$pageURL .= "://";

        if( strpos( $_SERVER["HTTP_HOST"], $_SERVER["SERVER_NAME"] ) !== false ){
            $pageURL .=$_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"];
        }
        else {
            if ($_SERVER["SERVER_PORT"] != "80")
                $pageURL .= $_SERVER["SERVER_NAME"] . ":" . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];
            else
                $pageURL .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];
        }
		
		if ( function_exists('apply_filters') ) $pageURL = apply_filters('wppb_curpageurl', $pageURL);

        return $pageURL;
	}
}


if ( is_admin() ){

	// register the settings for the menu only display sidebar menu for a user with a certain capability, in this case only the "admin"
	add_action( 'admin_init', 'wppb_register_settings' );	

	// display the same extra profile fields in the admin panel also
	if ( file_exists ( WPPB_PLUGIN_DIR.'/front-end/extra-fields/extra-fields.php' ) ){
		require_once( WPPB_PLUGIN_DIR.'/front-end/extra-fields/extra-fields.php' );
		
		add_action( 'show_user_profile', 'display_profile_extra_fields_in_admin', 10 );
		add_action( 'edit_user_profile', 'display_profile_extra_fields_in_admin', 10 );
        global $pagenow;
        if( $pagenow != 'user-new.php' )
            add_action( 'user_profile_update_errors', 'wppb_validate_backend_fields', 10, 3 );
		add_action( 'personal_options_update', 'save_profile_extra_fields_in_admin', 10 );
		add_action( 'edit_user_profile_update', 'save_profile_extra_fields_in_admin', 10 );
	}

}else if ( !is_admin() ){
	// include the stylesheet
	add_action( 'wp_print_styles', 'wppb_add_plugin_stylesheet' );		

	// include the menu file for the profile informations
	include_once( WPPB_PLUGIN_DIR.'/front-end/edit-profile.php' ); 
	include_once( WPPB_PLUGIN_DIR.'/front-end/class-formbuilder.php' );  	
	add_shortcode( 'wppb-edit-profile', 'wppb_front_end_profile_info' );

	// include the menu file for the login screen
	include_once( WPPB_PLUGIN_DIR.'/front-end/login.php' );       
	add_shortcode( 'wppb-login', 'wppb_front_end_login' );

    // include the menu file for the logout screen
    include_once( WPPB_PLUGIN_DIR.'/front-end/logout.php' );
    add_shortcode( 'wppb-logout', 'wppb_front_end_logout' );

	// include the menu file for the register screen
	include_once( WPPB_PLUGIN_DIR.'/front-end/register.php' );        		
	add_shortcode( 'wppb-register', 'wppb_front_end_register_handler' );	
	
	// include the menu file for the recover password screen
	include_once( WPPB_PLUGIN_DIR.'/front-end/recover.php' );        		
	add_shortcode( 'wppb-recover-password', 'wppb_front_end_password_recovery' );

	// set the front-end admin bar to show/hide
	add_filter( 'show_admin_bar' , 'wppb_show_admin_bar');

	// Shortcodes used for the widget area
	add_filter( 'widget_text', 'do_shortcode', 11 );
}


/**
 * Function that overwrites the default wp_mail function and sends out emails
 *
 * @since v.2.0
 *
 * @param string $to
 * @param string $subject
 * @param string $message
 * @param string $message_from
 *
 */
function wppb_mail( $to, $subject, $message, $message_from = null, $context = null, $headers = '' ) {
	$to = apply_filters( 'wppb_send_email_to', $to );
	$send_email = apply_filters( 'wppb_send_email', true, $to, $subject, $message, $context );

	$message = apply_filters( 'wppb_email_message', $message, $context );

	do_action( 'wppb_before_sending_email', $to, $subject, $message, $send_email, $context );
	
	if ( $send_email ) {
		//we add this filter to enable html encoding
		add_filter( 'wp_mail_content_type', create_function( '', 'return "text/html"; ' ) );

		$atts = apply_filters( 'wppb_mail', compact( 'to', 'subject', 'message', 'headers' ), $context );

		$sent = wp_mail( $atts['to'] , html_entity_decode( htmlspecialchars_decode( $atts['subject'], ENT_QUOTES ), ENT_QUOTES ), $atts['message'], $atts['headers'] );

		do_action( 'wppb_after_sending_email', $sent, $to, $subject, $message, $send_email, $context );

		return $sent;
	}

	return '';
}

function wppb_activate_account_check(){
	if ( ( isset( $_GET['activation_key'] ) ) && ( trim( $_GET['activation_key'] ) != '' ) ){
		global $post;
		$activation_key = sanitize_text_field( $_GET['activation_key'] );

		$wppb_generalSettings = get_option( 'wppb_general_settings' );
		$activation_landing_page_id = ( ( isset( $wppb_generalSettings['activationLandingPage'] ) && ( trim( $wppb_generalSettings['activationLandingPage'] ) != '' ) ) ? $wppb_generalSettings['activationLandingPage'] : 'not_set' );
		
		if ( $activation_landing_page_id != 'not_set' ){
			//an activation page was selected, but we still need to check if the current page doesn't already have the registration shortcode
			if ( strpos( $post->post_content, '[wppb-register' ) === false )
				add_filter( 'the_content', 'wppb_add_activation_message' );

		}elseif ( strpos( $post->post_content, '[wppb-register' ) === false ){
			//no activation page was selected, and the sent link pointed to the home url
			wp_redirect( apply_filters( 'wppb_activatate_account_redirect_url', WPPB_PLUGIN_URL.'assets/misc/fallback-page.php?activation_key='.urlencode( $activation_key ).'&site_name='.urlencode( get_bloginfo( 'name' ) ).'&site_url='.urlencode( get_bloginfo( 'url' ) ).'&message='.urlencode( $activation_message = wppb_activate_signup( $activation_key ) ), $activation_key, $activation_message ) );
			exit;
		}
	}
}
add_action( 'template_redirect', 'wppb_activate_account_check' );


function wppb_add_activation_message( $content ){

	return wppb_activate_signup( sanitize_text_field( $_GET['activation_key'] ) ) . $content;
}


// Create a new, top-level page
$args = array(							
			'page_title'	=> 'Profile Builder',
			'menu_title'	=> 'Profile Builder',
			'capability'	=> 'manage_options',
			'menu_slug' 	=> 'profile-builder',
			'page_type'		=> 'menu_page',
			'position' 		=> '70,69',
			'priority' 		=> 1,
			'icon_url' 		=> WPPB_PLUGIN_URL . 'assets/images/pb-menu-icon.png'
		);
new WCK_Page_Creator_PB( $args );

/**
 * Remove the automatically created submenu page
 *
 * @since v.2.0
 *
 * @return void
 */
function wppb_remove_main_menu_page(){
	remove_submenu_page( 'profile-builder', 'profile-builder' );
}
add_action( 'admin_menu', 'wppb_remove_main_menu_page', 11 );

/**
 * Add scripts to the back-end CPT's to remove the slug from the edit page
 *
 * @since v.2.0
 *
 * @return void
 */
function wppb_print_cpt_script( $hook ){
	wp_enqueue_script( 'jquery-ui-dialog' );
    wp_enqueue_style( 'wp-jquery-ui-dialog' );
    
	if ( $hook == 'profile-builder_page_manage-fields' ){
		wp_enqueue_script( 'wppb-manage-fields-live-change', WPPB_PLUGIN_URL . 'assets/js/jquery-manage-fields-live-change.js', array(), PROFILE_BUILDER_VERSION, true );
	}

	if (( $hook == 'profile-builder_page_manage-fields' ) ||
		( $hook == 'profile-builder_page_profile-builder-basic-info' ) ||
		( $hook == 'profile-builder_page_profile-builder-modules' ) ||
		( $hook == 'profile-builder_page_profile-builder-general-settings' ) ||
		( $hook == 'profile-builder_page_profile-builder-admin-bar-settings' ) ||
		( $hook == 'profile-builder_page_profile-builder-register' ) ||
		( $hook == 'profile-builder_page_profile-builder-wppb_userListing' ) ||
		( $hook == 'profile-builder_page_custom-redirects' ) ||
		( $hook == 'profile-builder_page_profile-builder-wppb_emailCustomizer' ) ||//?what is this
		( $hook == 'profile-builder_page_profile-builder-wppb_emailCustomizerAdmin' ) ||//?what is this
		( $hook == 'profile-builder_page_profile-builder-add-ons' ) ||
		( $hook == 'profile-builder_page_profile-builder-woocommerce-sync' ) ||
        ( $hook == 'profile-builder_page_profile-builder-bbpress') ||
        ( $hook == 'profile-builder_page_admin-email-customizer') ||
        ( $hook == 'profile-builder_page_user-email-customizer') ||
        ( $hook == 'profile-builder_page_profile-builder-content_restriction' ) ||
        ( strpos( $hook, 'profile-builder_page_' ) === 0 ) ||
        ( $hook == 'edit.php' && ( isset( $_GET['post_type'] ) && $_GET['post_type'] == 'wppb-roles-editor' ) ) ||
		( $hook == 'admin_page_profile-builder-pms-promo') ) {
			wp_enqueue_style( 'wppb-back-end-style', WPPB_PLUGIN_URL . 'assets/css/style-back-end.css', false, PROFILE_BUILDER_VERSION );
	}
	
	if ( $hook == 'profile-builder_page_profile-builder-general-settings' )
		wp_enqueue_script( 'wppb-manage-fields-live-change', WPPB_PLUGIN_URL . 'assets/js/jquery-email-confirmation.js', array(), PROFILE_BUILDER_VERSION, true );

    if( ($hook == 'profile-builder_page_profile-builder-add-ons' ) ||
        ($hook == 'admin_page_profile-builder-pms-promo' ) ) {
        wp_enqueue_script('wppb-add-ons', WPPB_PLUGIN_URL . 'assets/js/jquery-pb-add-ons.js', array(), PROFILE_BUILDER_VERSION, true);
        wp_enqueue_style( 'thickbox' );
        wp_enqueue_script( 'thickbox' );
    }

	if ( isset( $_GET['post_type'] ) || isset( $_GET['post'] ) ){
		if ( isset( $_GET['post_type'] ) )
			$post_type = sanitize_text_field( $_GET['post_type'] );
		
		elseif ( isset( $_GET['post'] ) )
			$post_type = get_post_type( absint( $_GET['post'] ) );
		
		if ( ( 'wppb-epf-cpt' == $post_type ) || ( 'wppb-rf-cpt' == $post_type ) || ( 'wppb-ul-cpt' == $post_type ) ){
			wp_enqueue_style( 'wppb-back-end-style', WPPB_PLUGIN_URL . 'assets/css/style-back-end.css', false, PROFILE_BUILDER_VERSION );
			wp_enqueue_script( 'wppb-epf-rf', WPPB_PLUGIN_URL . 'assets/js/jquery-epf-rf.js', array(), PROFILE_BUILDER_VERSION, true );
		}
		else if( 'wppb-roles-editor' == $post_type ){
			wp_enqueue_style( 'wppb-back-end-style', WPPB_PLUGIN_URL . 'assets/css/style-back-end.css', array(), PROFILE_BUILDER_VERSION );
		}
	}
    if ( file_exists ( WPPB_PLUGIN_DIR.'/update/update-checker.php' ) ) {
        wp_enqueue_script( 'wppb-sitewide', WPPB_PLUGIN_URL . 'assets/js/jquery-pb-sitewide.js', array(), PROFILE_BUILDER_VERSION, true );
    }
    wp_enqueue_style( 'wppb-serial-notice-css', WPPB_PLUGIN_URL . 'assets/css/serial-notice.css', false, PROFILE_BUILDER_VERSION );
}
add_action( 'admin_enqueue_scripts', 'wppb_print_cpt_script' );


//the function used to overwrite the avatar across the wp installation
function wppb_changeDefaultAvatar( $avatar, $id_or_email, $size, $default, $alt ){
	/* Get user info. */
	if(is_object($id_or_email)){
		$my_user_id = $id_or_email->user_id;

	}elseif(is_numeric($id_or_email)){
		$my_user_id = $id_or_email;

	}elseif(!is_integer($id_or_email)){
		$user_info = get_user_by( 'email', $id_or_email );
		$my_user_id = ( is_object( $user_info ) ? $user_info->ID : '' );
	}else
		$my_user_id = $id_or_email;

	$wppb_manage_fields = get_option( 'wppb_manage_fields', 'not_found' );
	if ( $wppb_manage_fields != 'not_found' ){
		foreach( $wppb_manage_fields as $value ){
			if ( $value['field'] == 'Avatar'){
				$avatar_field = $value;
			}
		}
	}

	/* for multisite if we don't have an avatar try to get it from the main blog */
	if( is_multisite() && empty( $avatar_field ) ){
		switch_to_blog(1);
		$wppb_switched_blog = true;
		$wppb_manage_fields = get_option( 'wppb_manage_fields', 'not_found' );
		if ( $wppb_manage_fields != 'not_found' ){
			foreach( $wppb_manage_fields as $value ){
				if ( $value['field'] == 'Avatar'){
					$avatar_field = $value;
				}
			}
		}
	}

	if ( !empty( $avatar_field ) ){

		$customUserAvatar = get_user_meta( $my_user_id, Wordpress_Creation_Kit_PB::wck_generate_slug( $avatar_field['meta-name'] ), true );
		if( !empty( $customUserAvatar ) ){
			if( is_numeric( $customUserAvatar ) ){
				$img_attr = wp_get_attachment_image_src( $customUserAvatar, 'wppb-avatar-size-'.$size );
				if( $img_attr[3] === false ){
					$img_attr = wp_get_attachment_image_src( $customUserAvatar, 'thumbnail' );
					$avatar = "<img alt='{$alt}' src='{$img_attr[0]}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
				}
				else
					$avatar = "<img alt='{$alt}' src='{$img_attr[0]}' class='avatar avatar-{$size} photo avatar-default' height='{$img_attr[2]}' width='{$img_attr[1]}' />";
			}
			else {
				$customUserAvatar = get_user_meta($my_user_id, 'resized_avatar_' . $avatar_field['id'], true);
				$customUserAvatarRelativePath = get_user_meta($my_user_id, 'resized_avatar_' . $avatar_field['id'] . '_relative_path', true);

				if ((($customUserAvatar != '') || ($customUserAvatar != null)) && file_exists($customUserAvatarRelativePath)) {
					$avatar = "<img alt='{$alt}' src='{$customUserAvatar}' class='avatar avatar-{$size} photo avatar-default' height='{$size}' width='{$size}' />";
				}
			}
		}

	}

	/* if we switched the blog restore it */
	if( is_multisite() && !empty( $wppb_switched_blog ) && $wppb_switched_blog )
		restore_current_blog();

	return $avatar;
}
add_filter( 'get_avatar', 'wppb_changeDefaultAvatar', 21, 5 );


//the function used to resize the avatar image; the new function uses a user ID as parameter to make pages load faster
function wppb_resize_avatar( $userID, $userlisting_size = null, $userlisting_crop = null ){
	// include the admin image API
	require_once( ABSPATH . '/wp-admin/includes/image.php' );

	// retrieve first a list of all the current custom fields
	$wppb_manage_fields = get_option( 'wppb_manage_fields', 'not_found' );
	if ( $wppb_manage_fields != 'not_found' ){
		foreach( $wppb_manage_fields as $value ){
			if ( $value['field'] == 'Avatar'){
				$avatar_field = $value;
			}
		}
	}

	/* for multisite if we don't have an avatar try to get it from the main blog */
	if( is_multisite() && empty( $avatar_field ) ){
		switch_to_blog(1);
		$wppb_switched_blog = true;
		$wppb_manage_fields = get_option( 'wppb_manage_fields', 'not_found' );
		if ( $wppb_manage_fields != 'not_found' ){
			foreach( $wppb_manage_fields as $value ){
				if ( $value['field'] == 'Avatar'){
					$avatar_field = $value;
				}
			}
		}
	}


	if ( !empty( $avatar_field ) ){

		// retrieve width and height of the image
		$width = $height = '';

		//this checks if it only has 1 component
		if ( is_numeric( $avatar_field['avatar-size'] ) ){
			$width = $height = $avatar_field['avatar-size'];

		}else{
			//this checks if the entered value has 2 components
			$sentValue = explode( ',', $avatar_field['avatar-size'] );
			$width = $sentValue[0];
			$height = $sentValue[1];
		}

		$width = ( !empty( $userlisting_size ) ? $userlisting_size : $width );
		$height = ( !empty( $userlisting_size ) ? $userlisting_size : $height );

		if( !strpos( get_user_meta( $userID, 'resized_avatar_'.$avatar_field['id'], true ), $width . 'x' . $height ) ) {
			// retrieve the original image (in original size)
			$avatar_directory_path = get_user_meta( $userID, 'avatar_directory_path_'.$avatar_field['id'], true );

			$image = wp_get_image_editor( $avatar_directory_path );
			if ( !is_wp_error( $image ) ) {
				do_action( 'wppb_before_avatar_resizing', $image, $userID, Wordpress_Creation_Kit_PB::wck_generate_slug( $avatar_field['meta-name'] ), $avatar_field['avatar-size'] );

				$crop = apply_filters( 'wppb_avatar_crop_resize', ( !empty( $userlisting_crop ) ? $userlisting_crop : false ) );

				$resize = $image->resize( $width, $height, $crop );

				if ($resize !== FALSE) {
					do_action( 'wppb_avatar_resizing', $image, $resize );

					$fileType = apply_filters( 'wppb_resized_file_extension', 'png' );

					$wp_upload_array = wp_upload_dir(); // Array of key => value pairs

					//create file(name); both with directory and url
					$fileName_dir = $image->generate_filename( NULL, $wp_upload_array['basedir'].'/profile_builder/avatars/', $fileType );

					if ( PHP_OS == "WIN32" || PHP_OS == "WINNT" )
						$fileName_dir = str_replace( '\\', '/', $fileName_dir );

					$fileName_url = str_replace( str_replace( '\\', '/', $wp_upload_array['basedir'] ), $wp_upload_array['baseurl'], $fileName_dir );

					//save the newly created (resized) avatar on the disc
					$saved_image = $image->save( $fileName_dir );

					if ( !is_wp_error( $saved_image ) ) {
						/* the image save sometimes doesn't save with the desired extension so we need to see with what extension it saved it with and
						if it differs replace the extension	in the path and url that we save as meta */
						$validate_saved_image = wp_check_filetype_and_ext( $saved_image['path'], $saved_image['path'] );
						$ext = substr( $fileName_dir,strrpos( $fileName_dir, '.', -1 ), strlen($fileName_dir) );
						if( !empty( $validate_saved_image['ext'] ) && $validate_saved_image['ext'] != $ext ){
							$fileName_url = str_replace( $ext, '.'.$validate_saved_image['ext'], $fileName_url );
							$fileName_dir = str_replace( $ext, '.'.$validate_saved_image['ext'], $fileName_dir );
						}

						update_user_meta( $userID, 'resized_avatar_'.$avatar_field['id'], $fileName_url );
						update_user_meta( $userID, 'resized_avatar_'.$avatar_field['id'].'_relative_path', $fileName_dir );

						do_action( 'wppb_after_avatar_resizing', $image, $fileName_dir, $fileName_url );
					}
				}
			}
		}
	}

	/* if we switched the blog restore it */
	if( is_multisite() && !empty( $wppb_switched_blog ) && $wppb_switched_blog )
		restore_current_blog();

}


if ( is_admin() ){
	// add a hook to delete the user from the _signups table if either the email confirmation is activated, or it is a wpmu installation
	function wppb_delete_user_from_signups_table($user_id) {
		global $wpdb;

		$userLogin = $wpdb->get_var( $wpdb->prepare( "SELECT user_login, user_email FROM " . $wpdb->users . " WHERE ID = %d LIMIT 1", $user_id ) );
		if ( is_multisite() )
			$delete = $wpdb->delete( $wpdb->signups, array( 'user_login' => $userLogin ) );
		else
			$delete = $wpdb->delete( $wpdb->prefix.'signups', array( 'user_login' => $userLogin ) );
	}

    $wppb_generalSettings = get_option( 'wppb_general_settings' );
    if ( !empty( $wppb_generalSettings['emailConfirmation'] ) && ( $wppb_generalSettings['emailConfirmation'] == 'yes' ) ) {
        if( is_multisite() )
            add_action( 'wpmu_delete_user', 'wppb_delete_user_from_signups_table' );
        else
            add_action('delete_user', 'wppb_delete_user_from_signups_table');
    }
}



// This function offers compatibility with the all in one event calendar plugin
function wppb_aioec_compatibility(){

	wp_deregister_script( 'jquery.tools-form');
}
add_action('admin_print_styles-users_page_ProfileBuilderOptionsAndSettings', 'wppb_aioec_compatibility');


function wppb_user_meta_exists( $id, $meta_name ){
	global $wpdb;
	
	return $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $wpdb->usermeta WHERE user_id = %d AND meta_key = %s", $id, $meta_name ) );
}


// function to check if there is a need to add the http:// prefix
function wppb_check_missing_http( $redirectLink ) {
	return preg_match( '#^(?:[a-z\d]+(?:-+[a-z\d]+)*\.)+[a-z]+(?::\d+)?(?:/|$)#i', $redirectLink );
}



//function to output the password strength checker on frontend forms
function wppb_password_strength_checker_html(){
    $wppb_generalSettings = get_option( 'wppb_general_settings' );
    if( !empty( $wppb_generalSettings['minimum_password_strength'] ) ){
        $password_strength = '<span id="pass-strength-result">'.__('Strength indicator', 'profile-builder' ).'</span>
        <input type="hidden" value="" name="wppb_password_strength" id="wppb_password_strength"/>';
        return $password_strength;
    }
    return '';
}

//function to check password length check
function wppb_check_password_length( $password ){
    $wppb_generalSettings = get_option( 'wppb_general_settings' );
    if( !empty( $wppb_generalSettings['minimum_password_length'] ) ){
        if( strlen( $password ) < $wppb_generalSettings['minimum_password_length'] ){
            return true;
        }
        else
            return false;
    }
    return false;
}

//function to check password strength
function wppb_check_password_strength(){
    $wppb_generalSettings = get_option( 'wppb_general_settings' );
    if( isset( $_POST['wppb_password_strength'] ) && !empty( $wppb_generalSettings['minimum_password_strength'] ) ){
		$wppb_password_strength = sanitize_text_field( $_POST['wppb_password_strength'] );
        $password_strength_array = array( 'short' => 0, 'bad' => 1, 'good' => 2, 'strong' => 3 );
        $password_strength_text = array( 'short' => __( 'Very Weak', 'profile-builder' ), 'bad' => __( 'Weak', 'profile-builder' ), 'good' => __( 'Medium', 'profile-builder' ), 'strong' => __( 'Strong', 'profile-builder' ) );
        if( $password_strength_array[$wppb_password_strength] < $password_strength_array[$wppb_generalSettings['minimum_password_strength']] ){
            return $password_strength_text[$wppb_generalSettings['minimum_password_strength']];
        }
        else
            return false;
    }
    return false;
}

/* function to output password length requirements text */
function wppb_password_length_text(){
    $wppb_generalSettings = get_option( 'wppb_general_settings' );
    if( !empty( $wppb_generalSettings['minimum_password_length'] ) ){
        return sprintf(__('Minimum length of %d characters.', 'profile-builder'), $wppb_generalSettings['minimum_password_length']);
    }
    return '';
}

/* function to output password strength requirements text */
function wppb_password_strength_description() {
	$wppb_generalSettings = get_option( 'wppb_general_settings' );

	if( ! empty( $wppb_generalSettings['minimum_password_strength'] ) ) {
		$password_strength_text = array( 'short' => __( 'Very Weak', 'profile-builder' ), 'bad' => __( 'Weak', 'profile-builder' ), 'good' => __( 'Medium', 'profile-builder' ), 'strong' => __( 'Strong', 'profile-builder' ) );
		$password_strength_description = '<br>'. sprintf( __( 'The password must have a minimum strength of %s.', 'profile-builder' ), $password_strength_text[$wppb_generalSettings['minimum_password_strength']] );

		return $password_strength_description;
	} else {
		return '';
	}
}

/**
 * Include password strength check scripts on frontend where we have shortoces present
 */
add_action( 'wp_footer', 'wppb_enqueue_password_strength_check' );
function wppb_enqueue_password_strength_check() {
    global $wppb_shortcode_on_front;
    if( $wppb_shortcode_on_front ){
        $wppb_generalSettings = get_option( 'wppb_general_settings' );
        if( !empty( $wppb_generalSettings['minimum_password_strength'] ) ){
             wp_enqueue_script( 'password-strength-meter' );
        }
    }
}
add_action( 'wp_footer', 'wppb_password_strength_check', 102 );
function wppb_password_strength_check(){
    global $wppb_shortcode_on_front;
    if( $wppb_shortcode_on_front ){
        $wppb_generalSettings = get_option( 'wppb_general_settings' );
        if( !empty( $wppb_generalSettings['minimum_password_strength'] ) ){
            ?>
            <script type="text/javascript">
                function check_pass_strength() {
                    var pass1 = jQuery('#passw1').val(), pass2 = jQuery('#passw2').val(), strength;

                    jQuery('#pass-strength-result').removeClass('short bad good strong');
                    if ( ! pass1 ) {
                        jQuery('#pass-strength-result').html( pwsL10n.empty );
                        return;
                    }

                    strength = wp.passwordStrength.meter( pass1, wp.passwordStrength.userInputBlacklist(), pass2 );

                    switch ( strength ) {
                        case 2:
                            jQuery('#pass-strength-result').addClass('bad').html( pwsL10n.bad );
                            jQuery('#wppb_password_strength').val('bad');
                            break;
                        case 3:
                            jQuery('#pass-strength-result').addClass('good').html( pwsL10n.good );
                            jQuery('#wppb_password_strength').val('good');
                            break;
                        case 4:
                            jQuery('#pass-strength-result').addClass('strong').html( pwsL10n.strong );
                            jQuery('#wppb_password_strength').val('strong');
                            break;
                        case 5:
                            jQuery('#pass-strength-result').addClass('short').html( pwsL10n.mismatch );
                            jQuery('#wppb_password_strength').val('short');
                            break;
                        default:
                            jQuery('#pass-strength-result').addClass('short').html( pwsL10n['short'] );
                            jQuery('#wppb_password_strength').val('short');
                    }
                }
                jQuery( document ).ready( function() {
                    // Binding to trigger checkPasswordStrength
                    jQuery('#passw1').val('').keyup( check_pass_strength );
                    jQuery('#passw2').val('').keyup( check_pass_strength );
                    jQuery('#pass-strength-result').show();
                });
            </script>
        <?php
        }
    }
}
/**
 * Create functions for repeating error messages in front-end forms
 */
function wppb_required_field_error($field_title='') {
    $required_error = apply_filters('wppb_required_error' , __('This field is required','profile-builder') , $field_title);

    return $required_error;

}

/**
 * Function that returns a certain field (from manage_fields) by a given id or meta_name
 */
function wppb_get_field_by_id_or_meta( $id_or_meta ){

    $id = 0;
    $meta = '';

    if ( is_numeric($id_or_meta) )
        $id = $id_or_meta;
    else
        $meta = $id_or_meta;

    $fields = get_option('wppb_manage_fields', 'not_found');

    if ($fields != 'not_found') {

        foreach ($fields as $key => $field) {
            if ( (!empty($id)) && ($field['id'] == $id) )
                return $field;
            if ( (!empty($meta)) && ($field['meta-name'] == $meta) )
                return $field;
        }

    }

    return '';
}


/* Function for displaying reCAPTCHA error on Login and Recover Password forms */
function wppb_recaptcha_field_error($field_title='') {
    $recaptcha_error = apply_filters('wppb_recaptcha_error' , __('Please enter a (valid) reCAPTCHA value','profile-builder') , $field_title);

    return $recaptcha_error;

}
/* Function for displaying phone field error */
function wppb_phone_field_error( $field_title = '' ) {
	$phone_error = apply_filters( 'wppb_phone_error' , __( 'Incorrect phone number', 'profile-builder' ) , $field_title );

	return $phone_error;
}

/* Create a wrapper function for get_query_var */
function wppb_get_query_var( $varname ){
    return apply_filters( 'wppb_get_query_var_'.$varname, get_query_var( $varname ) );
}

/* Filter the "Save Changes" button text, to make it translatable */
function wppb_change_save_changes_button($value){
    $value = __('Save Changes','profile-builder');
    return $value;
}
add_filter( 'wck_save_changes_button', 'wppb_change_save_changes_button', 10, 2);

/* Filter the "Cancel" button text, to make it translatable */
function wppb_change_cancel_button($value){
    $value = __('Cancel','profile-builder');
    return $value;
}
add_filter( 'wck_cancel_button', 'wppb_change_cancel_button', 10, 2);

/* ilter the "Delete" button text, to make it translatable */
function wppb_change_delete_button($value){
    $value = __('Delete','profile-builder');
    return $value;
}
add_filter( 'wck_delete_button', 'wppb_change_delete_button', 10, 2);

/*Filter the "Edit" button text, to make it translatable*/
function wppb_change_edit_button($value){
    $value = __('Edit','profile-builder');
    return $value;
}
add_filter( 'wck_edit_button', 'wppb_change_edit_button', 10, 2);

/*Filter the User Listing, Register Forms and Edit Profile forms metabox header content, to make it translatable*/
function wppb_change_metabox_content_header(){
  return '<thead><tr><th class="wck-number">#</th><th class="wck-content">'. __( 'Content', 'profile-builder' ) .'</th><th class="wck-edit">'. __( 'Edit', 'profile-builder' ) .'</th><th class="wck-delete">'. __( 'Delete', 'profile-builder' ) .'</th></tr></thead>';
}
add_filter('wck_metabox_content_header_wppb_ul_page_settings', 'wppb_change_metabox_content_header', 1);
add_filter('wck_metabox_content_header_wppb_rf_page_settings', 'wppb_change_metabox_content_header', 1);
add_filter('wck_metabox_content_header_wppb_epf_page_settings', 'wppb_change_metabox_content_header', 1);


/* Add a notice if people are not able to register via Profile Builder; Membership -> "Anyone can register" checkbox is not checked under WordPress admin UI -> Settings -> General tab */
if ( (get_option('users_can_register') == false) && (!class_exists('PMS_Add_General_Notices')) ) {
    if( is_multisite() ) {
        new WPPB_Add_General_Notices('wppb_anyone_can_register',
            sprintf(__('To allow users to register for your website via Profile Builder, you first must enable user registration. Go to %1$sNetwork Settings%2$s, and under Registration Settings make sure to check “User accounts may be registered”. %3$sDismiss%4$s', 'profile-builder'), "<a href='" . network_admin_url('settings.php') . "'>", "</a>", "<a href='" . esc_url( add_query_arg('wppb_anyone_can_register_dismiss_notification', '0') ) . "'>", "</a>"),
            'update-nag');
    }else{
        new WPPB_Add_General_Notices('wppb_anyone_can_register',
            sprintf(__('To allow users to register for your website via Profile Builder, you first must enable user registration. Go to %1$sSettings -> General%2$s tab, and under Membership make sure to check “Anyone can register”. %3$sDismiss%4$s', 'profile-builder'), "<a href='" . admin_url('options-general.php') . "'>", "</a>", "<a href='" . esc_url( add_query_arg('wppb_anyone_can_register_dismiss_notification', '0') ) . "'>", "</a>"),
            'update-nag');
    }
}

/*Filter default WordPress notices ("Post published. Post updated."), add post type name for User Listing, Registration Forms and Edit Profile Forms*/
function wppb_change_default_post_updated_messages($messages){
    global $post;
    $post_type = get_post_type($post->ID);
    $object = get_post_type_object($post_type);

    if ( ($post_type == 'wppb-rf-cpt')||($post_type == 'wppb-epf-cpt')||($post_type == 'wppb-ul-cpt') ){
        $messages['post'][1] = $object->labels->name . ' updated.';
        $messages['post'][6] = $object->labels->name . ' published.';
    }
    return $messages;
}
add_filter('post_updated_messages','wppb_change_default_post_updated_messages', 2);


/* for meta-names with spaces in them PHP converts the space to underline in the $_POST  */
function wppb_handle_meta_name( $meta_name ){
    $meta_name = str_replace( ' ', '_', $meta_name );
    $meta_name = str_replace( '.', '_', $meta_name );
    return $meta_name;
}


// change User Registered date and time according to timezone selected in WordPress settings
function wppb_get_register_date() {

	$time_format = "Y-m-d G:i:s";
	$wppb_get_date = date_i18n( $time_format, false, true );

	if( apply_filters( 'wppb_return_local_time_for_register', false ) ){
		$wppb_get_date = date_i18n( $time_format );
	}

	return $wppb_get_date;
}

/**
 * Function that ads the gmt offset from the general settings to a unix timestamp
 * @param $timestamp
 * @return mixed
 */
function wppb_add_gmt_offset( $timestamp ) {
	if( apply_filters( 'wppb_add_gmt_offset', true ) ){
		$timestamp = $timestamp + ( get_option( 'gmt_offset' ) * HOUR_IN_SECONDS );
	}
	return $timestamp;
}

/**
 * Add HTML tag 'required' to fields
 *
 * Add HTML tag 'required' for each field if the field is required. For browsers that don't support this HTML tag, we will still have the fallback.
 * Field type 'Checkbox' is explicitly excluded because there is no HTML support to check if at least one option is selected.
 * Other fields excluded are Avatar, Upload, Heading, ReCaptcha, WYSIWYG, Map.
 *
 * @since
 *
 * @param string $extra_attributes Extra attributes attached to the field HTML tag.
 * @param array $field Field description.
 * @return string $extra_attributes
 */
function wppb_add_html_tag_required_to_fields( $extra_attributes, $field, $form_location ) {
	if ( $field['field'] != "Checkbox" && isset( $field['required'] ) && $field['required'] == 'Yes' ){
		if( !( ( $field['field'] == "Default - Password" || $field['field'] == "Default - Repeat Password" ) && $form_location == 'edit_profile' ) )
			$extra_attributes .= ' required ';
	}
	return $extra_attributes;
}
add_filter( 'wppb_extra_attribute', 'wppb_add_html_tag_required_to_fields', 10, 3 );

/**
 * Add HTML tag 'required' to WooCommerce fields
 *
 * Add HTML tag 'required' for each WooCommerce field if the field is required. For browsers that don't support this HTML tag, we will still have the fallback.
 * Does not work on 'State / County' field, if it becomes required later depending on the Country Value
 *
 * @since
 *
 * @param string $extra_attributes Extra attributes attached to the field HTML tag.
 * @param array $field Field description.
 * @return string $extra_attributes
 */
function wppb_add_html_tag_required_to_woo_fields( $extra_attributes, $field ) {
	if ( isset( $field['required'] ) && $field['required'] == 'Yes' ){
		$extra_attributes .= ' required ';
	}
	return $extra_attributes;
}
add_filter( 'wppb_woo_extra_attribute', 'wppb_add_html_tag_required_to_woo_fields', 10, 2 );


/**
 * Add jQuery script to remove required attribute for hidden fields
 *
 * If a field is hidden dynamically via conditional fields or WooSync 'Ship to a different address' checkbox, then the required field needs to be removed.
 * If a field is made visible again, add the required field back again.
 *
 * @since
 *
 * @param string $extra_attributes Extra attributes attached to the field HTML tag.
 * @param array $field Field description.
 * @return string $extra_attributes
 */
function wppb_manage_required_attribute() {
	global $wppb_shortcode_on_front;
	if ($wppb_shortcode_on_front) {
		?>
		<script type="text/javascript">
			jQuery(document).on( "wppbAddRequiredAttributeEvent", wppbAddRequired );
			function wppbAddRequired(event) {
				var element = wppbEventTargetRequiredElement( event.target );
				if( jQuery( element ).attr( "wppb_cf_temprequired" ) ){
					jQuery( element  ).removeAttr( "wppb_cf_temprequired" );
					jQuery( element  ).attr( "required", "required" );
				}
			}

			jQuery(document).on( "wppbRemoveRequiredAttributeEvent", wppbRemoveRequired );
			function wppbRemoveRequired(event) {
				var element = wppbEventTargetRequiredElement( event.target );
				if ( jQuery( element ).attr( "required" ) ) {
					jQuery( element ).removeAttr( "required" );
					jQuery( element ).attr( "wppb_cf_temprequired", "wppb_cf_temprequired" );
				}
			}

			jQuery(document).on( "wppbToggleRequiredAttributeEvent", wppbToggleRequired );
			function wppbToggleRequired(event) {
				if ( jQuery( event.target ).attr( "required" ) ) {
					jQuery( event.target ).removeAttr( "required" );
					jQuery( event.target ).attr( "wppb_cf_temprequired", "wppb_cf_temprequired" );
				}else if( jQuery( event.target ).attr( "wppb_cf_temprequired" ) ){
					jQuery( event.target ).removeAttr( "wppb_cf_temprequired" );
					jQuery( event.target ).attr( "required", "required" );
				}
			}

			function wppbEventTargetRequiredElement( htmlElement ){
				if ( htmlElement.nodeName == "OPTION" ){
					// <option> is the target element, so we need to get the parent <select>, in order to apply the required attribute
					return htmlElement.parentElement;
				}else{
					return htmlElement;
				}
			}

		</script>
		<?php
	}
}
add_action( 'wp_footer', 'wppb_manage_required_attribute' );

function wpbb_specify_blog_details_on_signup_email( $message, $user_email, $user, $activation_key, $registration_page_url, $meta, $from_name, $context ){
	$meta = unserialize($meta);

	if ( is_multisite() && isset( $meta['wppb_create_new_site_checkbox'] ) && $meta['wppb_create_new_site_checkbox'] == 'yes' ) {
		$blog_details = wpmu_validate_blog_signup( $meta['wppb_blog_url'], $meta['wppb_blog_title'] );

		if ( empty($blog_details['errors']->errors['blogname']) && empty($blog_details['errors']->errors['blog_title'])) {
			$blog_path = $blog_details['domain'] . $blog_details['path'];
			$message .= __( '<br><br>Also, you will be able to visit your site at ', 'profile-builder' ) . '<a href="' . $blog_path . '">' . $blog_path . '</a>.';
		}
	}
	return $message;
}
add_filter( 'wppb_signup_user_notification_email_content', 'wpbb_specify_blog_details_on_signup_email', 5, 8 );

function wpbb_specify_blog_details_on_registration_email( $user_message_content, $email, $password, $user_message_subject, $context ){

	if ( is_multisite() ) {
		$user = get_user_by( 'email', $email );
		$blog_path = wppb_get_blog_url_of_user_id( $user->ID );
		if ( ! empty ( $blog_path ) ) {
			$user_message_content .= __( '<br><br>You can visit your site at ', 'profile-builder' ) . '<a href="' . $blog_path . '">' . $blog_path . '</a>.';
		}
	}
	return $user_message_content;

}
add_filter( 'wppb_register_user_email_message_without_admin_approval', 'wpbb_specify_blog_details_on_registration_email', 5, 5 );
add_filter( 'wppb_register_user_email_message_with_admin_approval', 'wpbb_specify_blog_details_on_registration_email', 5, 5 );


function wppb_get_blog_url_of_user_id( $user_id, $ignore_privacy = true ){
	$blog_id = get_user_meta( $user_id, 'primary_blog', true );
	if ( is_multisite() && !empty( $blog_id ) ){
		$blog_details = get_blog_details( $blog_id );
		if ( $ignore_privacy || $blog_details->public ) {
			return $blog_details->domain . $blog_details->path;
		}
	}
	return '';
}

function wppb_can_users_signup_blog(){
	if ( ! is_multisite() )
		return false;
	global $wpdb;
	$current_site           = get_current_site();
	$sitemeta_options_query = $wpdb->prepare( "SELECT meta_value FROM {$wpdb->sitemeta} WHERE meta_key = 'registration' AND site_id = %d", $current_site->id );
	$network_options_meta   = $wpdb->get_results( $sitemeta_options_query );

	if ( $network_options_meta[0]->meta_value == 'all' ){
		return true;
	}
	return false;
}

/**
 * Function that handle redirect URL
 *
 * @param	string				$redirect_priority	- it can be normal or top priority
 * @param	string				$redirect_type		- type of the redirect
 * @param	null|string			$redirect_url		- redirect URL if already set
 * @param	null|string|object	$user				- username, user email or user data
 * @param	null|string			$user_role			- user role
 *
 * @return	null|string	$redirect_url
 */
function wppb_get_redirect_url( $redirect_priority = 'normal', $redirect_type, $redirect_url = NULL, $user = NULL, $user_role = NULL ) {
	if( PROFILE_BUILDER == 'Profile Builder Pro' ) {
		$wppb_module_settings = get_option( 'wppb_module_settings' );

		if( isset( $wppb_module_settings['wppb_customRedirect'] ) && $wppb_module_settings['wppb_customRedirect'] == 'show' && $redirect_priority != 'top' && function_exists( 'wppb_custom_redirect_url' ) ) {
			$redirect_url = wppb_custom_redirect_url( $redirect_type, $redirect_url, $user, $user_role );
		}
	}

	if( ! empty( $redirect_url ) ) {
		$redirect_url = ( wppb_check_missing_http( $redirect_url ) ? 'http://'. $redirect_url : $redirect_url );
	}

	return $redirect_url;
}

/**
 * Function that builds the redirect
 *
 * @param	string		$redirect_url	- redirect URL
 * @param	int			$redirect_delay	- redirect delay in seconds
 * @param	null|string	$redirect_type	- the type of the redirect
 * @param	null|array	$form_args		- form args if set
 *
 * @return	string	$redirect_message
 */
function wppb_build_redirect( $redirect_url, $redirect_delay, $redirect_type = NULL, $form_args = NULL ) {
	if( isset( $redirect_type ) ) {
		$redirect_url = apply_filters( 'wppb_'. $redirect_type .'_redirect', $redirect_url );
	}

	$redirect_message = '';

	if( ! empty( $redirect_url ) ) {
		$redirect_url = ( wppb_check_missing_http( $redirect_url ) ? 'http://'. $redirect_url : $redirect_url );

		if( $redirect_delay == 0 ) {
			$redirect_message = '<meta http-equiv="Refresh" content="'. $redirect_delay .';url='. $redirect_url .'" />';
		} else {
			$redirect_url_href = apply_filters( 'wppb_redirect_url', '<a href="'. $redirect_url .'">'. __( 'here', 'profile-builder' ) .'</a>', $redirect_url, $redirect_type, $form_args );
			$redirect_message = apply_filters( 'wppb_redirect_message_before_returning', '<p class="redirect_message">'. sprintf( wp_slash( __( 'You will soon be redirected automatically. If you see this page for more than %1$d seconds, please click %2$s.%3$s', 'profile-builder' ) ), $redirect_delay, $redirect_url_href, '<meta http-equiv="Refresh" content="'. $redirect_delay .';url='. $redirect_url .'" />' ) .'</p>', $redirect_url, $redirect_delay, $redirect_url_href, $redirect_type, $form_args );
		}
	}

	return $redirect_message;
}

/**
 * Function that strips the script tags from an input
 * @param $string
 * @return mixed
 */
function wppb_sanitize_value( $string ){
	return preg_replace( '/<script\b[^>]*>(.*?)<\/script>/is', '', $string );
}

/**
 * Function that receives a user role and returns it's label.
 * Returns the original role if not found.
 *
 * @since v.2.7.1
 *
 * @param string $role
 *
 * @return string
 */
function wppb_get_role_name($role){
    global $wp_roles;

    if ( array_key_exists( $role, $wp_roles->role_names ) )
        return $wp_roles->role_names[$role];

    return $role;
}