<?php

    extract( $atts );

    //custom class
    $wrap_class  = apply_filters( 'kc-el-class', $atts );

    if( ! empty( $extra_class ) ) {
        $wrap_class[] = $extra_class;
    }
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="button-group store-buttons">

        <?php
            if( count( $buttons ) > 0 ) :
                foreach( $buttons as $btn ) :

                $link = kc_parse_link( $btn->href );


                echo '<a href="'. esc_url( $link['url'] ) .'"';
                    if( $border_style == 'gradient-border' ) :
                        echo ' class="btn btn-bordered-grad"';
                    else :
                        echo ' class="btn btn-bordered-white"';
                    endif;

                    if( ! empty( $link['target'] ) ) :
                        echo ' target="'. $link['target'] .'"';
                    endif;

                    if( ! empty( $link['rel'] ) ) :
                        echo ' rel="'. $link['rel'] .'"';
                    endif;

                echo '>';
                    echo '<i class="'. esc_attr( $btn->icon ) .' dsp-tc"></i>';
                    echo '<p class="dsp-tc">'.  $btn->btn_label .'</p>';
                echo '</a>';
        ?>



        <?php endforeach; endif; ?>

    </div>
</div>