<?php


// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


function appai_theme_sidebar() {

	// globalizing theme options framework
	global $appai;

	// Registering widgets for sidebar
	$args = array(
		'name'          => esc_html__( 'Appai Theme Sidebar', 'appai' ),
		'id'            => 'appai_sidebar',
		'description'   => esc_html__('Add widgets to the blog sidebar', 'appai'),
	    'class'         => '',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	register_sidebar($args);


	// Registering widgets for sidebar
	$args = array(
		'name'          => esc_html__( 'Appai Shop Sidebar', 'appai' ),
		'id'            => 'appai_shop_widgets',
		'description'   => esc_html__('Add widgets to the Products page sidebar', 'appai'),
	    'class'         => '',
		'before_widget' => '<div class="widget %2$s">',
		'after_widget'  => '</div>',
		'before_title'  => '<h4 class="widget-title">',
		'after_title'   => '</h4>'
	);

	register_sidebar($args);





}
add_action('widgets_init', 'appai_theme_sidebar');
