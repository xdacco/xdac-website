<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>


    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

    <?php $appai = get_option('appai'); ?>

    <!--  THEME PRELOADER AREA-->

    <?php if( isset( $appai['preloader'] ) && $appai['preloader'] == true ) : ?>
        <div id="preloader-wrapper">
            <div class="preloader-wave-effect"></div>
        </div>
    <?php endif; ?>



    <!-- THEME PRELOADER AREA END -->
    <!-- MAIN WRAPPER START -->
    <div id="page-top" class="wrapper">
        <!-- HEADER AREA -->


        <?php
            // Globalizing Appai Object
            global $appaiObj;

                // Get Header
                $appaiObj->headerStyles();
        ?>
