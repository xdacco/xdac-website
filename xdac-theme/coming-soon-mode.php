<?php

get_header();

$appai = get_option('appai');

?>

<!-- prelaoder end -->
<div class="comming-soon-wrapper">
    <div class="ovarlay"></div>
    <header>
        <div class="container">
            <div class="logo">
                <a href="#"><img src="img/logo/logo-3.png" alt="" class="img-responsive"></a>
            </div>
        </div>
    </header>
    <div class="counter-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">
                    <div class="header-text">
                        <h1>
                        <?php
                            if( isset( $appai['csm_title'] ) )
                                echo $appai['csm_title'];
                        ?>
                        </h1>
                        <?php
                            if( isset( $appai['csm_description'] ) )
                                echo '<p>';
                                echo $appai['csm_description'];
                                echo '</p>';
                        ?>
                    </div>
                </div>
            </div>
            <div class="count-down-area">
                <?php
                    if( isset( $appai['csm_date'] ) )
                        echo '<div class="clearfix" data-countdown="'. $appai['csm_date'] .'"></div>'
                ?>

            </div>
        </div>
    </div>


    <!-- footer area start -->
    <footer id="footer-area">
        <div class="container">
            <ul class="social list-inline text-center grad-bg-hover">
                <?php
                    if( shortcode_exists('appai_social_list') ) {
                        echo do_shortcode( $appai['csm_footer_shortcode'] );
                    }
                ?>
            </ul>
            <div class="copyright text-center">
                <?php
                    if( isset($appai['csm_footer_copyright']) ) {

                        // here no need to use esc_html
                        // because, html mark up can be used
                        echo '<p>' . $appai['csm_footer_copyright'] .'</p>';
                    }
                ?>

            </div>
        </div>
    </footer>
    <!-- footer area end -->
</div>

<?php

get_footer();

?>
