<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

$appai = get_option('appai');

?>


    <?php

        if( isset( $appai['coming_soon_mode'] ) && $appai['coming_soon_mode'] == false ) {
            get_template_part('templates/footer', 'tpl');
        }

    ?>

    </div>
    <!-- page wrapper end -->

    <?php wp_footer(); ?>
</body>

</html>
