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

 $appai = get_option('appai');

 ?>

        <!-- footer area start -->
        <footer id="footer-area">
            <div class="container">

                <?php if( isset( $appai['footer_shortcode'] ) && ! empty( $appai['footer_shortcode'] ) ) : ?>
                    <ul class="social list-inline text-center">
                        <?php
                            if( shortcode_exists('appai_social_list') ) {
                                echo do_shortcode( $appai['footer_shortcode'] );
                            }
                        ?>
                    </ul>
                <?php endif; ?>

                <div class="copyright text-center">
                    <p>
                        <?php
                            if( isset($appai['footer_copyright']) ) {

                                // here no need to use esc_html
                                // because, html mark up can be used
                                echo $appai['footer_copyright'];
                            }
                        ?>
                    </p>
                </div>
            </div>
        </footer>
        <!-- footer area end -->
