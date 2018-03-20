<?php
/**
 * Function that creates the "Register your version" submenu page
 *
 * @since v.2.0
 *
 * @return void
 */

if( !is_multisite() ){
    function wppb_register_your_version_submenu_page()
    {
        if (PROFILE_BUILDER != 'Profile Builder Free')
            add_submenu_page('profile-builder', __('Register Your Version', 'profile-builder'), __('Register Version', 'profile-builder'), 'manage_options', 'profile-builder-register', 'wppb_register_your_version_content');
    }
    add_action('admin_menu', 'wppb_register_your_version_submenu_page', 20);
}
else{
    function wppb_multisite_register_your_version_page()
    {
        if (PROFILE_BUILDER != 'Profile Builder Free')
            add_menu_page(__('Profile Builder Register', 'profile-builder'), __('Profile Builder Register', 'profile-builder'), 'manage_options', 'profile-builder-register', 'wppb_register_your_version_content', WPPB_PLUGIN_URL . 'assets/images/pb-menu-icon.png');
    }
    add_action('network_admin_menu', 'wppb_multisite_register_your_version_page', 20);
}


/**
 * Function that adds content to the "Register your Version" submenu page
 *
 * @since v.2.0
 *
 * @return string
 */
function wppb_register_your_version_content() {

    ?>
    <div class="wrap wppb-wrap">
        <?php
        if ( PROFILE_BUILDER == 'Profile Builder Pro' ){
            wppb_serial_form('pro', 'Profile Builder Pro');
        }elseif ( PROFILE_BUILDER == 'Profile Builder Hobbyist' ){
            wppb_serial_form('hobbyist', 'Profile Builder Hobbyist');
        }
        ?>

    </div>
<?php
}

/**
 * Function that creates the "Register your version" form depending on Pro or Hobbyist version
 *
 * @since v.2.0
 *
 * @return void
 */
function wppb_serial_form($version, $fullname){
    ?>

    <h2><?php printf( __( "Register your version of %s", 'profile-builder' ), $fullname ); ?></h2>

    <form method="post" action="<?php echo get_admin_url( 1, 'options.php' ) ?>">

        <?php $wppb_profile_builder_serial = get_option( 'wppb_profile_builder_'.$version.'_serial' ); ?>
        <?php $wppb_profile_builder_serial_status = get_option( 'wppb_profile_builder_'.$version.'_serial_status' ); ?>
        <?php settings_fields( 'wppb_profile_builder_'.$version.'_serial' ); ?>

        <p><?php printf( __( "Now that you acquired a copy of %s, you should take the time and register it with the serial number you received", 'profile-builder'), $fullname);?></p>
        <p><?php _e( "If you register this version of Profile Builder, you'll receive information regarding upgrades, patches, and technical support.", 'profile-builder' );?></p>
        <p class="wppb-serial-wrap">
            <label for="wppb_profile_builder_<?php echo $version; ?>_serial"><?php _e(' Serial Number:', 'profile-builder' );?></label>
                <input type="password" size="50" name="wppb_profile_builder_<?php echo $version; ?>_serial" id="wppb_profile_builder_<?php echo $version; ?>_serial" class="regular-text" <?php if ( $wppb_profile_builder_serial != ''){ echo ' value="'.$wppb_profile_builder_serial.'"';} ?>/>

                <?php
                if( $wppb_profile_builder_serial_status == 'found' )
                    echo '<span class="validateStatus"><img src="'.WPPB_PLUGIN_URL.'/assets/images/accept.png" title="'.__( 'The serial number was successfully validated!', 'profile-builder' ).'"/></span>';
                elseif ( $wppb_profile_builder_serial_status == 'notFound' )
                    echo '<span class="validateStatus"><img src="'.WPPB_PLUGIN_URL.'/assets/images/icon_error.png" title="'.__( 'The serial number entered couldn\'t be validated!','profile-builder' ).'"/></span>';
                elseif ( strpos( $wppb_profile_builder_serial_status, 'aboutToExpire')  !== false )//instead of aboutToExpire if the client has autobbiling on then he will receive 'found' instead
                    echo '<span class="validateStatus"><img src="' . WPPB_PLUGIN_URL . '/assets/images/icon_error.png" title="' . __('The serial number is about to expire soon!', 'profile-builder') . '"/>'. sprintf( __(' Your serial number is about to expire, please %1$s Renew Your License%2$s.','profile-builder'), "<a href='https://www.cozmoslabs.com/account/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-Renewal' >", "</a>").'</span>';
                elseif ( $wppb_profile_builder_serial_status == 'expired' )
                    echo '<span class="validateStatus"><img src="'.WPPB_PLUGIN_URL.'/assets/images/icon_error.png" title="'.__( 'The serial number couldn\'t be validated because it expired!','profile-builder' ).'"/>'. sprintf( __(' Your serial number is expired, please %1$s Renew Your License%2$s.','profile-builder'), "<a href='https://www.cozmoslabs.com/account/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-Renewal' >", "</a>").'</span>';
                elseif ( $wppb_profile_builder_serial_status == 'serverDown' )
                    echo '<span class="validateStatus"><img src="'.WPPB_PLUGIN_URL.'/assets/images/icon_error.png" title="'.__( 'The serial number couldn\'t be validated because process timed out. This is possible due to the server being down. Please try again later!','profile-builder' ).'"/></span>';
                ?>
        <span class="wppb-serialnumber-descr"><?php _e( '(e.g. RMPB-15-SN-253a55baa4fbe7bf595b2aabb8d72985)', 'profile-builder' );?></span>
        </p>


        <div id="wppb_submit_button_div">
            <input type="hidden" name="action" value="update" />
            <p class="submit">
                <?php wp_nonce_field( 'wppb_register_version_nonce', 'wppb_register_version_nonce' ); ?>
                <input type="submit" name="wppb_serial_number_activate" class="button-primary" value="<?php _e('Save Changes') ?>" />
            </p>
        </div>

    </form>
<?php
}


//the function to check the validity of the serial number and save a variable in the DB; purely visual
function wppb_check_serial_number($oldVal, $newVal){

	$serial_number_set = $newVal;


	$response = wp_remote_get( 'http://updatemetadata.cozmoslabs.com/checkserial/?serialNumberSent='.$serial_number_set );
	if ( PROFILE_BUILDER == 'Profile Builder Pro' ){
        wppb_update_serial_status($response, 'pro');
        wp_clear_scheduled_hook( "check_plugin_updates-profile-builder-pro-update" );
    } else {
        wppb_update_serial_status($response, 'hobbyist');
        wp_clear_scheduled_hook( "check_plugin_updates-profile-builder-hobbyist-update" );
	}
    $user_ID = get_current_user_id();
	delete_user_meta( $user_ID, 'wppb_dismiss_notification' );
	
}

add_action( 'update_option_wppb_profile_builder_pro_serial', 'wppb_check_serial_number', 10, 2 );
add_action( 'update_option_wppb_profile_builder_hobbyist_serial', 'wppb_check_serial_number', 10, 2 );

add_action( 'add_option_wppb_profile_builder_pro_serial', 'wppb_check_serial_number', 10, 2 );
add_action( 'add_option_wppb_profile_builder_hobbyist_serial', 'wppb_check_serial_number', 10, 2 );

/**
 * @param $response
 */
function wppb_update_serial_status($response, $version)
{
    if (is_wp_error($response)) {
        update_option('wppb_profile_builder_'.$version.'_serial_status', 'serverDown'); //server down
    } elseif ((trim($response['body']) != 'notFound') && (trim($response['body']) != 'found') && (trim($response['body']) != 'expired') && (strpos( $response['body'], 'aboutToExpire' ) === false)) {
        update_option('wppb_profile_builder_'.$version.'_serial_status', 'serverDown'); //unknown response parameter
        update_option('wppb_profile_builder_'.$version.'_serial', ''); //reset the entered password, since the user will need to try again later

    } else {
        update_option('wppb_profile_builder_'.$version.'_serial_status', trim($response['body'])); //either found, notFound or expired
    }
}

//the update didn't work when the old value = new value, so we need to apply a filter on get_option (that is run before update_option), that resets the old value
function wppb_check_serial_number_fix($newvalue, $oldvalue){

	if ( $newvalue == $oldvalue )
		wppb_check_serial_number( $oldvalue, $newvalue );
		
	return $newvalue;
}
add_filter( 'pre_update_option_wppb_profile_builder_pro_serial', 'wppb_check_serial_number_fix', 10, 2 );
add_filter( 'pre_update_option_wppb_profile_builder_hobbyist_serial', 'wppb_check_serial_number_fix', 10, 2 );


/**
 * Class that adds a notice when either the serial number wasn't found, or it has expired
 *
 * @since v.2.0
 *
 * @return void
 */
class wppb_add_notices{
	public $pluginPrefix = '';
	public $pluginName = '';
	public $notificaitonMessage = '';
	public $pluginSerialStatus = '';
	
	function __construct( $pluginPrefix, $pluginName, $notificaitonMessage, $pluginSerialStatus ){
		$this->pluginPrefix = $pluginPrefix;
		$this->pluginName = $pluginName;
		$this->notificaitonMessage = $notificaitonMessage;
		$this->pluginSerialStatus = $pluginSerialStatus;
		
		add_action( 'admin_notices', array( $this, 'add_admin_notice' ) );
		add_action( 'admin_init', array( $this, 'dismiss_notification' ) );
	}
	

	// Display a notice that can be dismissed in case the serial number is inactive
	function add_admin_notice() {
		global $current_user ;
		global $pagenow;
		
		$user_id = $current_user->ID;
		
		do_action( $this->pluginPrefix.'_before_notification_displayed', $current_user, $pagenow );
		
		if ( current_user_can( 'manage_options' ) ){

				$plugin_serial_status = get_option( $this->pluginSerialStatus );
				if ( $plugin_serial_status != 'found' ){
					// Check that the user hasn't already clicked to ignore the message
					if ( ! get_user_meta($user_id, $this->pluginPrefix.'_dismiss_notification' ) ) {
						echo $finalMessage = apply_filters($this->pluginPrefix.'_notification_message','<div class="error wppb-serial-notification" >'.$this->notificaitonMessage.'</div>', $this->notificaitonMessage);
					}
				}
				
				do_action( $this->pluginPrefix.'_notification_displayed', $current_user, $pagenow, $plugin_serial_status );

		}
		
		do_action( $this->pluginPrefix.'_after_notification_displayed', $current_user, $pagenow );
		
	}

	function dismiss_notification() {
		global $current_user;
		
		$user_id = $current_user->ID;
		
		do_action( $this->pluginPrefix.'_before_notification_dismissed', $current_user );
		
		// If user clicks to ignore the notice, add that to their user meta 
		if ( isset( $_GET[$this->pluginPrefix.'_dismiss_notification']) && '0' == $_GET[$this->pluginPrefix.'_dismiss_notification'] )
			add_user_meta( $user_id, $this->pluginPrefix.'_dismiss_notification', 'true', true ); 
		
		do_action( $this->pluginPrefix.'_after_notification_dismissed', $current_user );
	}
}

if( is_multisite() && function_exists( 'switch_to_blog' ) )
    switch_to_blog(1);

if ( PROFILE_BUILDER == 'Profile Builder Pro' ){
    $wppb_profile_builder_pro_hobbyist_serial_status = get_option( 'wppb_profile_builder_pro_serial_status', 'empty' );
    $version = 'pro';

} elseif( PROFILE_BUILDER == 'Profile Builder Hobbyist' ) {
    $wppb_profile_builder_pro_hobbyist_serial_status = get_option( 'wppb_profile_builder_hobbyist_serial_status', 'empty' );
    $version = 'hobbyist';
}
if( is_multisite() && function_exists( 'restore_current_blog' ) )
    restore_current_blog();

if ( $wppb_profile_builder_pro_hobbyist_serial_status == 'notFound' || $wppb_profile_builder_pro_hobbyist_serial_status == 'empty' ){
    if( !is_multisite() )
        $register_url = 'admin.php?page=profile-builder-register';
    else
        $register_url = network_admin_url( 'admin.php?page=profile-builder-register' );

	new wppb_add_notices( 'wppb', 'profile_builder_pro', sprintf( __( '<p>Your <strong>Profile Builder</strong> serial number is invalid or missing. <br/>Please %1$sregister your copy%2$s to receive access to automatic updates and support. Need a license key? %3$sPurchase one now%4$s</p>', 'profile-builder'), "<a href='". $register_url ."'>", "</a>", "<a href='https://www.cozmoslabs.com/wordpress-profile-builder/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-SN-Purchase' target='_blank' class='button-primary'>", "</a>" ), 'wppb_profile_builder_pro_serial_status' );
}
elseif ( $wppb_profile_builder_pro_hobbyist_serial_status == 'expired' ){
    new wppb_add_notices( 'wppb_expired', 'profile_builder_pro', sprintf( __( '<p>Your <strong>Profile Builder</strong> license has expired. <br/>Please %1$sRenew Your Licence%2$s to continue receiving access to product downloads, automatic updates and support. %3$sRenew now and get 40&#37; off %4$s %5$sDismiss%6$s</p>', 'profile-builder'), "<a href='https://www.cozmoslabs.com/downloads/profile-builder-". $version ."-v2-yearly-renewal/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-Renewal' target='_blank'>", "</a>", "<a href='https://www.cozmoslabs.com/downloads/profile-builder-".$version."-v2-yearly-renewal/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-Renewal' target='_blank' class='button-primary'>", "</a>", "<a href='". esc_url( add_query_arg( 'wppb_expired_dismiss_notification', '0' ) ) ."' class='wppb-dismiss-notification'>", "</a>" ), 'wppb_profile_builder_pro_serial_status' );
}
elseif( strpos( $wppb_profile_builder_pro_hobbyist_serial_status, 'aboutToExpire' ) === 0 ){
    $serial_status_parts = explode( '#', $wppb_profile_builder_pro_hobbyist_serial_status );
    $date = $serial_status_parts[1];
    new wppb_add_notices( 'wppb_about_to_expire', 'profile_builder_pro', sprintf( __( '<p>Your <strong>Profile Builder</strong> license is about to expire on %5$s. <br/>Please %1$sRenew Your Licence%2$s to continue receiving access to product downloads, automatic updates and support. %3$sRenew now and get 40&#37; off %4$s %6$sDismiss%7$s</p>', 'profile-builder'), "<a href='https://www.cozmoslabs.com/downloads/profile-builder-". $version ."-v2-yearly-renewal/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-Renewal' target='_blank'>", "</a>", "<a href='https://www.cozmoslabs.com/downloads/profile-builder-".$version."-v2-yearly-renewal/?utm_source=PB&utm_medium=dashboard&utm_campaign=PB-Renewal' target='_blank' class='button-primary'>", "</a>", $date, "<a href='". esc_url( add_query_arg( 'wppb_about_to_expire_dismiss_notification', '0' ) )."' class='wppb-dismiss-notification'>", "</a>" ), 'wppb_profile_builder_pro_serial_status' );
}