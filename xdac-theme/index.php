<?php
get_header();

?>

        <?php
            echo $appaiObj->appai_breadcrumb_bridge();
        ?>

        <!-- START blog AREA -->
        <div class="blog_area">
            <div class="container">
                <div class="row">

                    <?php

                        if( isset( $appai['blog_layout'] ) ) :
                            if( $appai['blog_layout'] == 'fullpage' ) :

                                $appaiObj->thePostLoop('col-md-12');

                            elseif(  $appai['blog_layout'] == 'right-sidebar' ) :

                                $appaiObj->thePostLoop('col-md-9');

                                $appaiObj->getPulledSidebar('col-md-3');

                            elseif(  $appai['blog_layout'] == 'left-sidebar' ) :

                                $appaiObj->thePostLoop('col-md-9 col-md-push-3');

                                $appaiObj->getPulledSidebar('col-md-3 col-md-pull-9');

                            endif;

                        else:
                            $appaiObj->thePostLoop('col-md-9');

                            $appaiObj->getPulledSidebar('col-md-3');
                        endif;


                    ?>
                </div>
            </div>
        </div>
        <!-- END blog AREA -->

<?php
get_footer();
?>
