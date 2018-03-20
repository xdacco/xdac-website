<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

    $img = wp_get_attachment_image_src($image, 'large');

?>


<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    
    <?php if( $about_style == 'about-style-1' ) : ?>

        <div class="single-feature media">
            <div class="feature-icon media-left">
                <?php if( $use_icon == 'yes' ) : ?>
                    <i class="icon <?php echo esc_attr( $icon ); ?>"></i>
                <?php endif; ?>

                <?php if( $use_image == 'yes' ) : ?>
                    <img src="<?php echo esc_url( $img[0] ); ?>" alt="<?php echo esc_html( $title ); ?>">
                <?php endif; ?>

            </div>
            <div class="feature-details media-body">
                <h5><?php echo esc_html( $title ); ?></h5>
                <p><?php echo esc_html( $desc ); ?></p>
            </div>
        </div>

    <?php elseif( $about_style == 'about-style-2' ) : ?>

        <div class="single-feature feature-style-2">

            <div class="feature-icon">
                <?php if( $use_icon == 'yes' ) : ?>
                    <i class="icon <?php echo esc_attr( $icon ); ?>"></i>
                <?php endif; ?>

                <?php if( $use_image == 'yes' ) : ?>
                    <img src="<?php echo esc_url( $img[0] ); ?>" alt="<?php echo esc_html( $title ); ?>">
                <?php endif; ?>
            </div>
            
            <div class="feature-details">
                <h5><?php echo esc_html( $title ); ?></h5>
                <p><?php echo esc_html( $desc ); ?></p>
            </div>
        </div>

    <?php endif; ?>
</div>