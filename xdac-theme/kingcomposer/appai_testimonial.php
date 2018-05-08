<?php

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="slider-wrapper-3">

        <?php
            if( count( $testimonials ) > 0 ) :
            foreach($testimonials as $testimonial) :

            $img = wp_get_attachment_image_src($testimonial->image, 'large');
        ?>

        <div class="slide">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="slider-content text-center">
                        <!--<div class="client-image">
                            <img
                                src="<?php //echo esc_url( $img[0] ); ?>"
                                alt="<?php //echo esc_html( $testimonial->name ); ?>"
                                class="img-responsive img-circle center-block">
                        </div>-->
                        <div class="client-testimonial">
                            <h3><?php echo esc_html( $testimonial->name ); ?></h3>
                            <p class="client-designation"><?php echo esc_html( $testimonial->position ); ?></p>
                            <p class="client-review">
                                <?php echo esc_html( $testimonial->about ); ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php endforeach; ?>


        <?php else: ?>
            <h3><?php esc_html_e('Please add testimonials', 'appai'); ?></h3>
        <?php endif; ?>

    </div>
</div>
