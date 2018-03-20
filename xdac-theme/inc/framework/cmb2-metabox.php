<?php
/**
 * Include and setup custom metaboxes and fields. (make sure you copy this file to outside the CMB2 directory)
 *
 * Be sure to replace all instances of 'yourprefix_' with your project's prefix.
 * http://nacin.com/2010/05/11/in-wordpress-prefix-everything/
 *
 * @category YourThemeOrPlugin
 * @package  Demo_CMB2
 * @license  http://www.opensource.org/licenses/gpl-license.php GPL v2.0 (or later)
 * @link     https://github.com/WebDevStudios/CMB2
 */

/**
 * Get the bootstrap! If using the plugin from wordpress.org, REMOVE THIS!
 */


/**
 * Conditionally displays a metabox when used as a callback in the 'show_on_cb' cmb2_box parameter
 *
 * @param  CMB2 object $cmb CMB2 object
 *
 * @return bool             True if metabox should show
 */
function yourprefix_show_if_front_page( $cmb ) {
	// Don't show this metabox if it's not the front page template
	if ( $cmb->object_id !== get_option( 'page_on_front' ) ) {
		return false;
	}
	return true;
}

/**
 * Conditionally displays a field when used as a callback in the 'show_on_cb' field parameter
 *
 * @param  CMB2_Field object $field Field object
 *
 * @return bool                     True if metabox should show
 */
function yourprefix_hide_if_no_cats( $field ) {
	// Don't show this field if not in the cats category
	if ( ! has_tag( 'cats', $field->object_id ) ) {
		return false;
	}
	return true;
}

/**
 * Manually render a field.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 */
function yourprefix_render_row_cb( $field_args, $field ) {
	$classes     = $field->row_classes();
	$id          = $field->args( 'id' );
	$label       = $field->args( 'name' );
	$name        = $field->args( '_name' );
	$value       = $field->escaped_value();
	$description = $field->args( 'description' );
	?>
	<div class="custom-field-row <?php echo esc_attr( $classes ); ?>">
		<p><label for="<?php echo esc_attr(  $id ); ?>"><?php echo esc_attr( $label ); ?></label></p>
		<p><input id="<?php echo esc_attr( $id ); ?>" type="text" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>"/></p>
		<p class="description"><?php echo esc_attr( $description ); ?></p>
	</div>
	<?php
}

/**
 * Manually render a field column display.
 *
 * @param  array      $field_args Array of field arguments.
 * @param  CMB2_Field $field      The field object
 */
function yourprefix_display_text_small_column( $field_args, $field ) {
	?>
	<div class="custom-column-display <?php echo  esc_attr(  $field->row_classes() ); ?>">
		<p><?php echo esc_attr( $field->escaped_value() ); ?></p>
		<p class="description"><?php echo esc_attr( $field->args( 'description' ) ); ?></p>
	</div>
	<?php
}

/**
 * Conditionally displays a message if the $post_id is 2
 *
 * @param  array             $field_args Array of field parameters
 * @param  CMB2_Field object $field      Field object
 */
function yourprefix_before_row_if_2( $field_args, $field ) {
	if ( 2 == $field->object_id ) {
		echo '<p>Testing <b>"before_row"</b> parameter (on $post_id 2)</p>';
	} else {
		echo '<p>Testing <b>"before_row"</b> parameter (<b>NOT</b> on $post_id 2)</p>';
	}
}

add_action( 'cmb2_admin_init', 'maxive_page_header_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function maxive_page_header_metabox() {
	$prefix = 'maxive_header_';

	/**
	 * Metabox for Pages
	 */
	$maxive_page_options = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Page Header Options', 'appai' ),
		'object_types'  => array( 'page', ), // Post type
		'classes'    => 'maxive-custom-page-options-class', // Extra cmb2-wrap classes
	) );


	$maxive_page_options->add_field( array(
	  'name'             	=> __( 'Page header style', 'appai' ),
		'id'   			   	=> $prefix . 'page_header_style',
		'desc' 				=> __( 'Select the page header options', 'appai' ),
	    'type'             => 'radio',
	    'options'          => array(
	        'header-style-1' => ' <span class="radio-text">Header style 1</span><img src="'. get_template_directory_uri() .'/assets/img/headers/header-style-1.png" alt="" class="img-responsive">',
	        'header-style-3' => ' <span class="radio-text">Header style 2</span><img src="'. get_template_directory_uri() .'/assets/img/headers/header-style-3.png" alt="" class="img-responsive">',
	        'header-style-2' => ' <span class="radio-text">Header style 3</span><img src="'. get_template_directory_uri() .'/assets/img/headers/header-style-2.png" alt="" class="img-responsive">',
	        'header-style-4' => ' <span class="radio-text">Header style 4</span><img src="'. get_template_directory_uri() .'/assets/img/headers/header-style-4.png" alt="" class="img-responsive">',
	        'header-style-5' => ' <span class="radio-text">Header style 5</span><img src="'. get_template_directory_uri() .'/assets/img/headers/header-style-5.png" alt="" class="img-responsive">',
	    ),
	) );


	$maxive_page_options->add_field( array(
		'name'       => __( 'Header Container', 'appai' ),
		'desc'       => __( 'Select the header navigation bar container width', 'appai' ),
		'id'         => $prefix . 'container',
		'type'       => 'select',
		'default'          => 'container',
		'options'          => array(
			'container' => __( 'Container', 'appai' ),
			'container-fluid'   => __( 'Container Fluid', 'appai' ),
		),
	) );

	$maxive_page_options->add_field( array(
		'name'       => __( 'Transparent Header ( Header style 2 only )', 'appai' ),
		'desc'       => __( 'Check this if you want your header style 2 as a transparent header.', 'appai' ),
		'id'         => $prefix . 'transparent',
		'type'       => 'checkbox',
	) );


}


add_action( 'cmb2_admin_init', 'maxive_page_breadcrumb_metabox' );
/**
 * Hook in and add a demo metabox. Can only happen on the 'cmb2_admin_init' or 'cmb2_init' hook.
 */
function maxive_page_breadcrumb_metabox() {
	$prefix = 'maxive_';


	/**
	 * Metabox for Pages
	 */
	$maxive_page_options = new_cmb2_box( array(
		'id'            => $prefix . 'metabox',
		'title'         => __( 'Page Breadcrumb Options', 'appai' ),
		'object_types'  => array( 'page', ), // Post type
		'classes'    => 'maxive-custom-page-options-class', // Extra cmb2-wrap classes
	) );


	$maxive_page_options->add_field( array(
		'name'       => __( 'Breadcrumb Background  Image', 'appai' ),
		'desc'       => __( 'Set Breadcrumb Background Image ', 'appai' ),
		'id'         => $prefix . 'breadcrumb_bg_image',
		'type'       => 'file',
	) );

	$maxive_page_options->add_field( array(
		'name'       => __( 'Breadcrumb Background Color', 'appai' ),
		'desc'       => __( 'Set Breadcrumb Area Background Color ', 'appai' ),
		'id'         => $prefix . 'breadcrumb_bg_color',
		'type'       => 'colorpicker',
	) );


	$maxive_page_options->add_field( array(
		'name'       => __( 'Use Custom Breadcrumb Title ', 'appai' ),
		'desc'       => __( 'Check this to use custom breadcrumb title. Otherwise breadcrumb will show default page title.', 'appai' ),
		'id'         => $prefix . 'breadcrumb_title_switch',
		'type'       => 'checkbox',
		'options'          => array(
			'on' => __( 'Breadcrumb On', 'appai' ),
		),
	) );

	$maxive_page_options->add_field( array(
		'name'       => __( 'Breadcrumb Custom Title', 'appai' ),
		'desc'       => __( 'Give the breadcrumb title', 'appai' ),
		'id'         => $prefix . 'breadcrumb_title',
		'type'       => 'text',
	) );


	$maxive_page_options->add_field( array(
		'name'       => __( 'Hide Footer', 'appai' ),
		'desc'       => __( 'Check this if you want to hide the footer on this specific page only.', 'appai' ),
		'id'         => $prefix . 'footer_switch',
		'type'       => 'checkbox',
	) );

}




add_action( 'cmb2_admin_init', 'maxive_register_user_profile_metabox' );
/**
 * Hook in and add a metabox to add fields to the user profile pages
 */
function maxive_register_user_profile_metabox() {
	$prefix = 'maxive_';

	/**
	 * Metabox for the user profile screen
	 */
	$cmb_user = new_cmb2_box( array(
		'id'               => $prefix . 'user',
		'title'            => __( 'User Profile Metabox', 'appai' ), // Doesn't output for user boxes
		'object_types'     => array( 'user' ), // Tells CMB2 to use user_meta vs post_meta
		'show_names'       => true,
		'new_user_section' => 'add-new-user', // where form will show on new user page. 'add-existing-user' is only other valid option.
	) );


	$cmb_user->add_field( array(
		'name' => __( 'Facebook URL', 'appai' ),
		'desc' => __( 'field description (optional)', 'appai' ),
		'id'   => $prefix . 'facebookurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Twitter URL', 'appai' ),
		'desc' => __( 'field description (optional)', 'appai' ),
		'id'   => $prefix . 'twitterurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Google+ URL', 'appai' ),
		'desc' => __( 'field description (optional)', 'appai' ),
		'id'   => $prefix . 'googleplusurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Youtube URL', 'appai' ),
		'desc' => __( 'field description (optional)', 'appai' ),
		'id'   => $prefix . 'youtubeurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Linkedin URL', 'appai' ),
		'desc' => __( 'field description (optional)', 'appai' ),
		'id'   => $prefix . 'linkedinurl',
		'type' => 'text_url',
	) );

	$cmb_user->add_field( array(
		'name' => __( 'Pinterest URL', 'appai' ),
		'desc' => __( 'field description (optional)', 'appai' ),
		'id'   => $prefix . 'pinteresturl',
		'type' => 'text_url',
	) );
}

