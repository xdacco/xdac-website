<?php


// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

get_header();
?>

        <?php
            echo $appaiObj->appai_breadcrumb_bridge();
        ?>


        <!-- START blog AREA -->
        <div class="blog-area">
            <div class="container">
                <div class="row">

                    <?php

                        if( isset( $appai['shop_page_layout'] ) ) :
                            if( $appai['shop_page_layout'] == 'fullpage' ) :

                                $appaiObj->getWoocommerceLoop('col-md-12');

                            elseif(  $appai['shop_page_layout'] == 'right-sidebar' ) :

                                $appaiObj->getWoocommerceLoop('col-md-9');

                                $appaiObj->getWoocommerceSidebar('col-md-3');

                            elseif(  $appai['shop_page_layout'] == 'left-sidebar' ) :

                                $appaiObj->getWoocommerceLoop('col-md-9 col-md-push-3');

                                $appaiObj->getWoocommerceSidebar('col-md-3 col-md-pull-9');

                            endif;

                        else:
                            $appaiObj->getWoocommerceLoop('col-md-9');

                            $appaiObj->getWoocommerceSidebar('col-md-3');
                        endif;


                    ?>


                </div>
            </div>
        </div>
        <!-- END blog AREA -->

<?php
get_footer();
?>
