<?php

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

	$link = kc_parse_link( $href );

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="single-price-table one text-center ">
        <div class="pricing-head">
            <div class="price-tage-wrap">
                <h1 class="price-value "><sub class="doller-sign"><?php echo esc_html( $currency ); ?></sub><?php echo esc_html( $price ); ?><sub class="duration"><?php echo esc_html( $interval ); ?></sub></h1>
            </div>
            <h5 class="plan-title blue-grad-bg"><?php echo esc_html( $title ); ?></h5>
        </div>
        <div class="pricing-content">
            <?php

            	// No need to esc_html here
            	// Because HTML is allowed here
            	echo $content;

            ?>

        </div>
        <div class="pricing-footer ">
            <p><?php echo esc_html( $help_text ); ?></p>
            <?php
                if( $link['url'] ) :

	            echo '<a href="'. esc_url( $link['url'] ) .'"';
	            echo ' class="btn btn-bordered-grad"';

                    if( ! empty( $link['target'] ) ) :
                        echo ' target="'. $link['target'] .'"';
                    endif;

                    if( ! empty( $link['rel'] ) ) :
                        echo ' rel="'. $link['rel'] .'"';
                    endif;


                echo '>';
	            	 echo esc_html( $link['title'] );
                echo '</a>';
	        endif; ?>
        </div>

    </div>
</div>