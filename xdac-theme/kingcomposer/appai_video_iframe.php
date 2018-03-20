<?php

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

    $img = wp_get_attachment_image_src($image, 'large');

    $link = kc_parse_link( $href );

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="video-demo-image wow fadeIn" data-wow-duration="2s">
        <div class="overlay-grad-one">
            <img src="<?php echo esc_url( $img['0'] ); ?>" alt="" class="img-responsive center-block">
        </div>
        <div class="video-play-icon">
            <a
                href="<?php echo esc_url( $link['url'] ); ?>"
                target="<?php echo esc_attr( $link['target'] ); ?>"
                rel="<?php echo esc_attr( $link['rel'] ); ?>"
            >
                <i class="<?php echo esc_attr( $icon ); ?>"></i>
            </a>
        </div>
    </div>
</div>
