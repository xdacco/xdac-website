<?php


// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function appai_theme_options_style() {

	// Globalizing theme options values
	global $appai;

	//
	// Enqueueing StyleSheet file
	//
	wp_enqueue_style( 'appai-theme-custom-style', get_template_directory_uri() . '/assets/css/theme_options_style.css'  );

	$css_output = '';


	/*=============================================
	=            CUSTOM FOOTER STYLES             =
	=============================================*/

    $custom_footer_padding_top = get_post_meta( get_the_ID(), 'footer_top_padding', true );
    $custom_footer_padding_bottom = get_post_meta( get_the_ID(), 'footer_bottom_padding', true );

    if( $custom_footer_padding_top ) {

    	$css_output = "
			footer#footer-area{
				padding-top: {$custom_footer_padding_top}px;
			}
    	";
    }

    if( $custom_footer_padding_bottom ) {

    	$css_output = "
			footer#footer-area{
				padding-bottom: {$custom_footer_padding_bottom}px;
			}
    	";
    }

	wp_add_inline_style( 'appai-theme-custom-style', $css_output );

}
add_action('wp_enqueue_scripts', 'appai_theme_options_style');
