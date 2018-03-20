<?php
/**
 * Function that creates the "Basic Information" submenu page
 *
 * @since v.2.0
 *
 * @return void
 */
function wppb_register_basic_info_submenu_page() {
	add_submenu_page( 'profile-builder', __( 'Basic Information', 'profile-builder' ), __( 'Basic Information', 'profile-builder' ), 'manage_options', 'profile-builder-basic-info', 'wppb_basic_info_content' );
}
add_action( 'admin_menu', 'wppb_register_basic_info_submenu_page', 2 );

/**
 * Function that adds content to the "Basic Information" submenu page
 *
 * @since v.2.0
 *
 * @return string
 */
function wppb_basic_info_content() {
	
	$version = 'Free';
	$version = ( ( PROFILE_BUILDER == 'Profile Builder Pro' ) ? 'Pro' : $version );
	$version = ( ( PROFILE_BUILDER == 'Profile Builder Hobbyist' ) ? 'Hobbyist' : $version );

?>
	<div class="wrap wppb-wrap wppb-info-wrap">
		<div class="wppb-badge <?php echo $version; ?>"><span><?php printf( __( 'Version %s' ), PROFILE_BUILDER_VERSION ); ?></span></div>
		<h1><?php printf( __( '<strong>Profile Builder </strong> %s', 'profile-builder' ), $version ); ?></h1>
		<p class="wppb-info-text"><?php printf( __( 'The best way to add front-end registration, edit profile and login forms.', 'profile-builder' ) ); ?></p>
		<hr />
		<h2 class="wppb-callout"><?php _e( 'For Modern User Interaction', 'profile-builder' ); ?></h2>
		<div class="wppb-row wppb-3-col">
			<div>
				<h3><?php _e( 'Login', 'profile-builder' ); ?></h3>
				<p><?php printf( __( 'Friction-less login using %s shortcode or a widget.', 'profile-builder' ), '<strong class="nowrap">[wppb-login]</strong>' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Registration', 'profile-builder'  ); ?></h3>
				<p><?php printf( __( 'Beautiful registration forms fully customizable using the %s shortcode.', 'profile-builder' ), '<strong class="nowrap">[wppb-register]</strong>' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Edit Profile', 'profile-builder' ); ?></h3>
				<p><?php printf( __( 'Straight forward edit profile forms using %s shortcode.', 'profile-builder' ), '<strong class="nowrap">[wppb-edit-profile]</strong>' ); ?></p>
			</div>
		</div>
		<?php ob_start(); ?>
		<hr/>
		<div>
			<h3><?php _e( 'Extra Features', 'profile-builder' );?></h3>
			<p><?php _e( 'Features that give you more control over your users, increased security and help you fight user registration spam.', 'profile-builder' ); ?></p>
			<p><a href="admin.php?page=profile-builder-general-settings" class="button"><?php _e( 'Enable extra features', 'profile-builder' ); ?></a></p>
		</div>
		<div class="wppb-row wppb-3-col">
			<div>
				<h3><?php _e( 'Recover Password', 'profile-builder' ); ?></h3>
				<p><?php printf( __( 'Allow users to recover their password in the front-end using the %s.', 'profile-builder' ), '<strong class="nowrap">[wppb-recover-password]</strong>' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Admin Approval (*)', 'profile-builder' ); ?></h3>
				<p><?php _e( 'You decide who is a user on your website. Get notified via email or approve multiple users at once from the WordPress UI.', 'profile-builder' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Email Confirmation', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Make sure users sign up with genuine emails. On registration users will receive a notification to confirm their email address.', 'profile-builder' ); ?></p>
			</div>
            <div>
                <h3><?php _e( 'Content Restriction', 'profile-builder' ); ?></h3>
                <p><?php _e( 'Restrict users from accessing certain pages, posts or custom post types based on user role or logged-in status.', 'profile-builder' ); ?></p>
            </div>
			<div>
				<h3><?php _e( 'Minimum Password Length and Strength Meter', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Eliminate weak passwords altogether by setting a minimum password length and enforcing a certain password strength.', 'profile-builder' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Login with Email or Username', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Allow users to log in with their email or username when accessing your site.', 'profile-builder' ); ?></p>
			</div>
			<div style="clear:left;">
				<h3><?php _e( 'Roles Editor', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Add, remove, clone and edit roles and also capabilities for these roles.', 'profile-builder' ); ?></p>
			</div>
		</div>

		<?php
		// Output here the Extra Features html for the Free version
		$extra_features_html = ob_get_contents();
		ob_end_clean();
		if ( $version == 'Free' ) echo $extra_features_html; ?>

		<hr/>
		<div class="wppb-row wppb-2-col">
			<div>
				<h3><?php _e( 'Customize Your Forms The Way You Want (*)', 'profile-builder' ); ?></h3>
				<p><?php _e( 'With Extra Profile Fields you can create the exact registration form your project needs.', 'profile-builder' ); ?></p>
				<?php if ($version == 'Free'){ ?>
					<p><a href="https://www.cozmoslabs.com/wordpress-profile-builder/?utm_source=wpbackend&utm_medium=clientsite&utm_content=basicinfo-extrafields&utm_campaign=PBFree" class="wppb-button-free"><?php _e( 'Extra Profile Fields are available in Hobbyist or PRO versions', 'profile-builder' ); ?></a></p>
				<?php } else {?>
					<p><a href="admin.php?page=manage-fields" class="button"><?php _e( 'Get started with extra fields', 'profile-builder' ); ?></a></p>
				<?php } ?>
				<ul style="float: left; margin-right: 50px;">
					<li><?php _e( 'Avatar Upload', 'profile-builder' ); ?></li>
					<li><?php _e( 'Generic Uploads', 'profile-builder' ); ?></li>
					<li><?php _e( 'Agree To Terms Checkbox', 'profile-builder' ); ?></li>
					<li><?php _e( 'Datepicker', 'profile-builder' ); ?> </li>
                    <li><?php _e( 'Timepicker', 'profile-builder' ); ?> </li>
                    <li><?php _e( 'Colorpicker', 'profile-builder' ); ?> </li>
					<li><?php _e( 'reCAPTCHA', 'profile-builder' ); ?></li>
					<li><?php _e( 'Country Select', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Currency Select', 'profile-builder' ); ?></li>
					<li><?php _e( 'Timezone Select', 'profile-builder' ); ?></li>
				</ul>

                <ul style="float: left;">
                    <li><?php _e( 'Input / Hidden Input', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Number', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Checkbox', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Select', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Radio Buttons', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Textarea', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Validation', 'profile-builder' ); ?></li>
                    <li><?php _e( 'Map', 'profile-builder' ); ?></li>
                    <li><?php _e( 'HTML', 'profile-builder' ); ?></li>
                </ul>
			</div>
			<div>
				<img src="<?php echo WPPB_PLUGIN_URL; ?>assets/images/pb_fields.png" alt="Profile Builder Extra Fields" class="wppb-fields-image" />
			</div>
		</div>
		<hr/>
		<div> 
			<h3><?php _e( 'Powerful Modules (**)', 'profile-builder' );?></h3>
			<p><?php _e( 'Everything you will need to manage your users is probably already available using the Pro Modules.', 'profile-builder' ); ?></p>
            <?php if( file_exists ( WPPB_PLUGIN_DIR.'/modules/modules.php' ) ): ?>
			    <p><a href="admin.php?page=profile-builder-modules" class="button"><?php _e( 'Enable your modules', 'profile-builder' ); ?></a></p>
            <?php endif; ?>
			<?php if ($version == 'Free'){ ?>
				<p><a href="https://www.cozmoslabs.com/wordpress-profile-builder/?utm_source=wpbackend&utm_medium=clientsite&utm_content=basicinfo-modules&utm_campaign=PBFree" class="wppb-button-free"><?php _e( 'Find out more about PRO Modules', 'profile-builder' ); ?></a></p>
			<?php }?>
		</div>
		<div class="wppb-row wppb-3-col">
			<div>
				<h3><?php _e( 'User Listing', 'profile-builder' ); ?></h3>
				<?php if ($version == 'Free'): ?>
				<p><?php _e( 'Easy to edit templates for listing your website users as well as creating single user pages. Shortcode based, offering many options to customize your listings.', 'profile-builder' ); ?></p>
				<?php else : ?>
				<p><?php printf( __( 'To create a page containing the users registered to this current site/blog, insert the following shortcode in a page of your chosing: %s.', 'profile-builder' ), '<strong class="nowrap">[wppb-list-users]</strong>' ); ?></p>
				<?php endif;?>
			</div>
			<div>
				<h3><?php _e( 'Email Customizer', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Personalize all emails sent to your users or admins. On registration, email confirmation, admin approval / un-approval.', 'profile-builder' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Custom Redirects', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Keep your users out of the WordPress dashboard, redirect them to the front-page after login or registration, everything is just a few clicks away.', 'profile-builder' ); ?></p>
			</div>
		</div>
		<div class="wppb-row wppb-3-col">
			<div>
				<h3><?php _e( 'Multiple Registration Forms', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Set up multiple registration forms with different fields for certain user roles. Capture different information from different types of users.', 'profile-builder' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Multiple Edit-profile Forms', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Allow different user roles to edit their specific information. Set up multiple edit-profile forms with different fields for certain user roles.', 'profile-builder' ); ?></p>
			</div>
			<div>
				<h3><?php _e( 'Repeater Fields', 'profile-builder' ); ?></h3>
				<p><?php _e( 'Set up a repeating group of fields on register and edit profile forms. Limit the number of repeated groups for each user role.', 'profile-builder' ); ?></p>
			</div>
		</div>

		<?php
		//Output here Extra Features html for Hobbyist or Pro versions
		if ( $version != 'Free' ) echo $extra_features_html; ?>
		<hr/>
		<div class="wrap wppb-wrap wppb-1-3-col">
			<div>
				<a href="<?php echo admin_url('options.php?page=profile-builder-pms-promo'); ?>"><img src="<?php echo plugins_url( '../assets/images/pb-pms-cross-promotion.png', __FILE__ ); ?>" alt="paid member subscriptions"/></a>
			</div>
			<div>
				<h3>Paid user profiles with Profile Builder and Paid Member Subscriptions</h3>
				<p>One of the most requested features in Profile Builder was for users to be able to pay for an account.</p>
				<p>Now that's possible using the free WordPress plugin - <a href="<?php echo admin_url('options.php?page=profile-builder-pms-promo'); ?>">Paid Member Subscriptions</a>.</p>
				<p><a href="<?php echo admin_url('options.php?page=profile-builder-pms-promo'); ?>" class="button">Find out how</a></p>

			</div>
		</div>
		<div class="wrap wppb-wrap wppb-1-3-col">
			<div>
				<a href="https://wordpress.org/plugins/translatepress-multilingual/" target="_blank"><img src="<?php echo plugins_url( '../assets/images/pb-trp-cross-promotion.png', __FILE__ ); ?>" alt="TranslatePress Logo"/></a>
			</div>
			<div>
				<h3>Easily translate your entire WordPress website</h3>
				<p>Translate your Profile Builder forms with a WordPress translation plugin that anyone can use.</p>
				<p>It offers a simpler way to translate WordPress sites, with full support for WooCommerce and site builders.</p>
				<p><a href="https://wordpress.org/plugins/translatepress-multilingual/" class="button" target="_blank">Find out how</a></p>

			</div>
		</div>
		<hr/>
		<div>
			<h3>Extra Notes</h3>
			<ul>
				<li><?php printf( __( ' * only available in the %1$sHobbyist and Pro versions%2$s.', 'profile-builder' ) ,'<a href="https://www.cozmoslabs.com/wordpress-profile-builder/?utm_source=wpbackend&utm_medium=clientsite&utm_content=basicinfo-extranotes&utm_campaign=PB'.$version.'" target="_blank">', '</a>' );?></li>
				<li><?php printf( __( '** only available in the %1$sPro version%2$s.', 'profile-builder' ), '<a href="https://www.cozmoslabs.com/wordpress-profile-builder/?utm_source=wpbackend&utm_medium=clientsite&utm_content=basicinfo-extranotes&utm_campaign=PB'.$version.'" target="_blank">', '</a>' );?></li>
			</ul>
		</div>
	</div>
<?php
}