<?php
/**
 * Sidebar template for Appai WordPress theme
 *
 * @package WordPress
 * @subpackage appai
 * @since appai 1.0
 */

 // File Security Check
 if ( ! defined( 'ABSPATH' ) ) {
 	exit;
 }

    if( is_active_sidebar('appai_sidebar') )
        // Load the sidebar if it is active
        dynamic_sidebar('appai_sidebar');
?>
