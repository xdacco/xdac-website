<?php
include_once ( WPPB_PLUGIN_DIR.'/features/upgrades/upgrades-functions.php' );

/**
 * Function that assures backwards compatibility for all future versions, where this is needed
 *
 * @since v.1.3.13
 *
 * @return void
 */
function wppb_update_patch(){
	if ( !get_option( 'wppb_version' ) ) {
		add_option( 'wppb_version', '1.3.13' );
		
		do_action( 'wppb_set_initial_version_number', PROFILE_BUILDER_VERSION );
	}

	$wppb_version = get_option( 'wppb_version' );
	
	do_action( 'wppb_before_default_changes', PROFILE_BUILDER_VERSION, $wppb_version );
	
	if ( version_compare( PROFILE_BUILDER_VERSION, $wppb_version, '>' ) ) {
		if ( ( PROFILE_BUILDER == 'Profile Builder Pro' ) || ( PROFILE_BUILDER == 'Profile Builder Hobbyist' ) ){

			/* stopped creating them on 01.02.2016 */
			/*$upload_dir = wp_upload_dir();
			wp_mkdir_p( $upload_dir['basedir'].'/profile_builder' );
			wp_mkdir_p( $upload_dir['basedir'].'/profile_builder/attachments/' );
			wp_mkdir_p( $upload_dir['basedir'].'/profile_builder/avatars/' );*/
			
			// Flush the rewrite rules and add them, if need be, the proper way.
			if ( function_exists( 'wppb_flush_rewrite_rules' ) )
				wppb_flush_rewrite_rules();
			
			wppb_pro_hobbyist_v1_3_13();
		}
		
		if ( PROFILE_BUILDER == 'Profile Builder Pro' ){
			wppb_pro_v1_3_15();
		}
		
		update_option( 'wppb_version', PROFILE_BUILDER_VERSION );
	}

	//this should run only once, mainly if the old version is < 2.0 (can be anything)
	if ( version_compare( $wppb_version, 2.0, '<' ) ) {
		if ( ( PROFILE_BUILDER == 'Profile Builder Pro' ) || ( PROFILE_BUILDER == 'Profile Builder Hobbyist' ) || ( PROFILE_BUILDER == 'Profile Builder Free' ) ){
			wppb_pro_hobbyist_free_v2_0();
		}
		
		if ( PROFILE_BUILDER == 'Profile Builder Pro' ){
			wppb_pro_userlisting_compatibility_upgrade();
			wppb_pro_email_customizer_compatibility_upgrade();
		}
	}

	// this should run only once, mainly if the old version is < 2.2.5 (can be anything)
	if ( version_compare( $wppb_version, '2.2.5', '<' ) ) {
		if ( PROFILE_BUILDER == 'Profile Builder Pro' ) {
			wppb_new_custom_redirects_compatibility();
		}
	}

    if ( version_compare( $wppb_version, '2.2.5', '<=' ) ) {
        if( is_multisite() ){
            $wppb_general_settings = get_option( 'wppb_general_settings', 'not_set' );
			if ( $wppb_general_settings != 'not_set' ) {
				$wppb_general_settings['emailConfirmation'] = 'yes';
				update_option('wppb_general_settings', $wppb_general_settings);
			}
        }

    }
	
	do_action ( 'wppb_after_default_changes', PROFILE_BUILDER_VERSION, $wppb_version );	
}
add_action ( 'init', 'wppb_update_patch' );