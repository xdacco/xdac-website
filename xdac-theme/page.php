<?php 
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
                        $appaiObj->pagePostLoop();
                    ?>
                    
                </div>
            </div>
        </div>
        <!-- END blog AREA -->

<?php 
get_footer();
?>