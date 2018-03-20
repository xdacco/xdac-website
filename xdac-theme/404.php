<?php


// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

get_header();
?>


    <!-- MAIN WRAPPER START -->
    <div class="not-found-wrapper">
        <div class="container">
            <div class="content ">
                <div class="row">
                    <div class="col-md-6">
                        <div class="not-found-page">
                            <div class="not-found-message">
                                <h4><?php  esc_html_e('Oops!', 'appai'); ?> </h4>
                            </div>
                            <div class="not-found-title text-left">

                                <h1> <?php esc_html_e('404', 'appai') .'<span>'. esc_html_e(' Error!', 'appai') .'</span>'; ?> </h1>
                                <h5> <?php esc_html_e('The page or content you are looking for cannot be found!', 'appai') ?></h5>
                                <h5> <?php esc_html_e('Please search or go back to home', 'appai') ?></h5>

                                    <?php get_search_form(); ?>
                            </div>
                            <div class="back-home">
                                <?php
                                    echo '<a href="'. home_url() .'">'. esc_html__('Take me to the home page instead', 'appai') .'</a>';
                                ?>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="search-img-404">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/img/helper-images/search404.png" alt="<?php esc_attr_e( 'Not found' , 'appai' ); ?>">
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!-- MAIN WRAPPER END -->


<?php
get_footer();
?>
