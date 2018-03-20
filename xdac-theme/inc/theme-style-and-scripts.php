<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 *
 * Enqueue style and scripts
 *
 */
function appai_scripts() {


	//
	// Enqueuing Styles
	//

	wp_enqueue_style( 'google-fonts', appai_google_fonts_url(), array(), null );

	wp_enqueue_style( 'bootstrap-css', get_template_directory_uri() . '/assets/css/bootstrap.min.css' );

	wp_enqueue_style( 'animate-css', get_template_directory_uri() . '/assets/css/animate.css' );

	wp_enqueue_style( 'appai-color-variation', get_template_directory_uri() . '/assets/css/color-variation.css' );

	wp_enqueue_style( 'appai-elements', get_template_directory_uri() . '/assets/css/elements.css' );

	wp_enqueue_style( 'appai-fakeLoader-css', get_template_directory_uri() . '/assets/css/fakeLoader.css' );

	wp_enqueue_style( 'icofont', get_template_directory_uri() . '/assets/css/icofont.css' );

	wp_enqueue_style( 'magnific-popup', get_template_directory_uri() . '/assets/css/magnific-popup.css' );

	wp_enqueue_style( 'slick', get_template_directory_uri() . '/assets/css/slick.css' );

	wp_enqueue_style( 'slicknav', get_template_directory_uri() . '/assets/css/slicknav.min.css' );

	wp_enqueue_style( 'appai-swiper', get_template_directory_uri() . '/assets/css/swiper.min.css' );

	wp_enqueue_style( 'appai-blog', get_template_directory_uri() . '/assets/css/blog.css' );

	wp_enqueue_style( 'appai-style', get_template_directory_uri() . '/assets/css/style.css' );

	wp_enqueue_style( 'appai-responsive', get_template_directory_uri() . '/assets/css/responsive.css' );


	wp_enqueue_style( 'appai-stylesheet', get_stylesheet_uri() );


	//
	// Enqueuing Scripts
	//

	wp_register_script( 'appai-google-map-s1', get_template_directory_uri() . '/assets/js/appai.map.js', array('jquery'), null, true );

	wp_register_script( 'appai-google-map-s2', get_template_directory_uri() . '/assets/js/appai.map-2.js', array('jquery'), null, true );

	wp_enqueue_script( 'bootstrap-js', get_template_directory_uri() . '/assets/js/bootstrap.min.js', array('jquery'), null, true );

	wp_enqueue_script( 'slick', get_template_directory_uri() . '/assets/js/slick.min.js', array('jquery'), null, true );

	wp_enqueue_script( 'slicknav', get_template_directory_uri() . '/assets/js/jquery.slicknav.min.js', array('jquery'), null, true );

	wp_enqueue_script( 'prettySocial', get_template_directory_uri() . '/assets/js/jquery.prettySocial.js', array('jquery'), null, true );

	wp_enqueue_script( 'swiper', get_template_directory_uri() . '/assets/js/swiper.min.js', array('jquery'), null, true );

	wp_enqueue_script( 'appai-plugins', get_template_directory_uri() . '/assets/js/plugins.js', array('jquery'), null, true );

	wp_enqueue_script( 'appai-map', get_template_directory_uri() . '/assets/js/appai.map.js', array('jquery', 'appai-google-map'), null, true );

	wp_enqueue_script( 'appai-map-2', get_template_directory_uri() . '/assets/js/appai.map-2.js', array('jquery', 'appai-google-map'), null, true );

	wp_enqueue_script( 'appai-fakeLoader', get_template_directory_uri() . '/assets/js/fakeLoader.min.js', array('jquery'), null, true );

	wp_enqueue_script( 'particles-js', get_template_directory_uri() . '/assets/js/particles.min.js', array('jquery'), null, true );

	wp_register_script( 'angle-js', get_template_directory_uri() . '/assets/js/angle.js', array('jquery'), null, true );

	wp_register_script( 'appai-particle-style1', get_template_directory_uri() . '/assets/js/appai_particles_style1.js', array('jquery'), null, true );
	wp_register_script( 'appai-particle-style2', get_template_directory_uri() . '/assets/js/appai_particles_style2.js', array('jquery'), null, true );

	wp_enqueue_script( 'appai-main', get_template_directory_uri() . '/assets/js/main.js', array('jquery'), null, true );


	if( is_singular() && comments_open() && get_option( 'thread_comment' ) ) {
		wp_enqueue_script( 'comment-reply' );
	}


	appai_google_maps_js_script();



}
add_action( 'wp_enqueue_scripts', 'appai_scripts' );




/**
 *
 * Admin Enqueue styles and scripts
 *
 */

function appai_admin_scripts() {

	wp_enqueue_style( 'appai-admin-style', get_template_directory_uri() . '/assets/css/appai_admin.css');

	wp_enqueue_script( 'appai-admin-script', get_template_directory_uri() . '/assets/js/appai_admin.js', array('jquery'), null, true );

}
add_action( 'admin_enqueue_scripts', 'appai_admin_scripts' );
