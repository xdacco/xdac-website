<?php
/*
//load WP if needed
$path_to_wp_install_dir = '';
include_once ( $path_to_wp_install_dir.'wp-load.php' );
*/

$site_name = ( isset( $_GET['site_name'] ) ? urldecode( $_GET['site_name'] ) : '' );
$site_url = ( isset( $_GET['site_url'] ) ? urldecode( $_GET['site_url'] ) : '' );
$message = ( isset( $_GET['message'] ) ? urldecode( $_GET['message'] ) : '' );
?> 	
			
<html>
	<head>
		<style type="text/css">
			body {font-family:Arial; padding: 5px; margin-top:100px; text-align: center;}
		</style>
		
		<title><?php echo htmlspecialchars( $site_name, ENT_QUOTES ); ?></title>
	</head>
	
	<body id="wppb_content">
		<h1><?php echo htmlspecialchars( $site_name, ENT_QUOTES ); ?></h1>
		
		<?php echo '<p>'. htmlspecialchars( strip_tags( $message ) ). '</p>'; ?>
		
		<?php echo 'Click <a href="'. htmlspecialchars(  $site_url, ENT_QUOTES ) .'">here</a> to return to the main site'; ?>
	</body>
</html>