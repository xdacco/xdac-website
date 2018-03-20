<?php

/*
Template name: Template: Page builder
*/

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

get_header();
?>

        <div class="<?php echo appai_page_builder_wrapper_class(); ?>">

                <?php
                    if( have_posts() ) :
                        while( have_posts() ) :

                            the_post();

                            the_content();


                        endwhile;
                    endif;
                ?>
        </div>


<?php
get_footer();
?>
