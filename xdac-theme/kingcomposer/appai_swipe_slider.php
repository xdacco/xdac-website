<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">

	<?php
		if( count( $swipe_sliders ) > 0 ) :
	?>
		<div class="swiper-container one">
		    <div class="swiper-wrapper">

		    	<?php 
		    		foreach( $swipe_sliders as $slider ) :  
    				$img = wp_get_attachment_image_src($slider->image, 'large');
		    	?>
			        <div class="swiper-slide">
			            <img src="<?php echo esc_url( $img[0] ); ?>" alt="" class="img-responsive">
			        </div>

			    <?php endforeach; ?>
		    </div>
		</div>
	<?php else: ?>
		<?php esc_html_e('Please add slider images.', 'appai'); ?>
	<?php endif; ?>
</div>