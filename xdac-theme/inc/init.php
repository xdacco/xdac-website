<?php 

// File Security Check
if ( ! defined( 'ABSPATH' ) ) { 
	exit; 
}

// Theme Constants
require_once trailingslashit( get_template_directory() ) . 'inc/constants.php' ;

// Theme Setup
require_once APPAI_CLASSES_DIR . '/AppaiMain.php';

// Theme Setup
require_once APPAI_INC_DIR . '/theme-setup.php';

// Require ACF fields
require_once APPAI_INC_DIR . '/acf-fields.php';

// Theme Style and Scripts Enqueye
require_once APPAI_INC_DIR . '/theme-style-and-scripts.php';

// Theme Shortcodes
require_once APPAI_INC_DIR . '/nav-menu-walker.php';

// Theme Widgets
require_once APPAI_INC_DIR . '/widgets.php';

// Custom Theme Options Css
require_once APPAI_INC_DIR . '/custom_theme_options_style.php';

// Plugin Install
require_once APPAI_INC_PLUGINS_DIR . '/install-plugin.php';



// 
// CS Framework Init
// 

// Options Framework
require_once APPAI_FRAMEWORK_DIR . '/theme-options.php';

require_once APPAI_FRAMEWORK_DIR . '/cmb2-metabox.php';



