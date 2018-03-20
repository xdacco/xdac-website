<?php 

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { 
	exit; 
}

// Define the DHRUBOK Folder
if( ! defined( 'APPAI_DIR' ) ) {
	define('APPAI_DIR', get_template_directory() );
}

// Define the DHRUBOK Folder
if( ! defined( 'APPAI_TEMPLATE_DIR' ) ) {
	define('APPAI_TEMPLATE_DIR', get_template_directory_uri() );
}

// Define the DHRUBOK Partials Folder
if( ! defined( 'APPAI_PARTIALS_DIR' ) ) {
	define('APPAI_PARTIALS_DIR', trailingslashit( APPAI_DIR ) . 'partials' );
}

// Define the Inc Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_ASSETS_DIR' ) ) {
	define('APPAI_ASSETS_DIR', trailingslashit( APPAI_DIR ) . 'assets' );
}


// Define the Inc Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_INC_DIR' ) ) {
	define('APPAI_INC_DIR', trailingslashit( APPAI_DIR ) . 'inc' );
}

// Define the Inc Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_FRAMEWORK_DIR' ) ) {
	define('APPAI_FRAMEWORK_DIR', trailingslashit( APPAI_INC_DIR ) . 'framework' );
}

// Define the Libs Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_LIBS_DIR' ) ) {
	define('APPAI_LIBS_DIR', trailingslashit( APPAI_DIR ) . 'libs' );
}

// Define the Shortcodes Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_SHORTCODES_DIR' ) ) {
	define('APPAI_SHORTCODES_DIR', trailingslashit( APPAI_INC_DIR ) . 'shortcodes' );
}

// Define the Classes Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_CLASSES_DIR' ) ) {
	define('APPAI_CLASSES_DIR', trailingslashit( APPAI_INC_DIR ) . 'classes' );
}

// Define the Widgets Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_WIDGETS_DIR' ) ) {
	define('APPAI_WIDGETS_DIR', trailingslashit( APPAI_INC_DIR ) . 'widgets' );
}

// Define the Widgets Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_INC_VC_DIR' ) ) {
	define('APPAI_INC_VC_DIR', trailingslashit( APPAI_INC_DIR ) . 'visual_composer' );
}

// Define the PLUGINS Folder of the DHRUBOK Directory
if( ! defined( 'APPAI_INC_PLUGINS_DIR' ) ) {
	define('APPAI_INC_PLUGINS_DIR', trailingslashit( APPAI_INC_DIR ) . 'plugins' );
}
