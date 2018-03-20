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
        <div class="blog-area ptb-80">
            <div class="container">
                <div class="row">

                    <?php

                            if( $appaiObj->get_sidebar_options() == 'fullpage' ) :

                                $appaiObj->thePostLoop('col-md-12');

                            elseif(  $appaiObj->get_sidebar_options() == 'right-sidebar' ) :

                                $appaiObj->thePostLoop('col-md-9');

                                $appaiObj->getPulledSidebar('col-md-3');

                            elseif(  $appaiObj->get_sidebar_options() == 'left-sidebar' ) :

                                $appaiObj->thePostLoop('col-md-9 col-md-push-3');

                                $appaiObj->getPulledSidebar('col-md-3 col-md-pull-9');

                            endif;


                    ?>
                </div>
            </div>
        </div>
        <!-- END blog AREA -->

<?php
get_footer();
?>
