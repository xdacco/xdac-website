<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

	$slider_images = explode(',', $images);

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	
	<?php if( $slider_style == 'screenshot-slider-1' ) :  ?>

	    <div class="slider-wrapper-2">

			<?php 
				if( count( $slider_images ) > 0 ) : 
					foreach( $slider_images as $slider ) :


	    			$img = wp_get_attachment_image_src($slider, 'large');
			?>

		        <div class="slide one">
		            <div class="slider-image">
		                <img src="<?php echo esc_url( $img[0] ); ?>" alt="" class="img-responsive">
		                <div class="preview-icon">
		                    <a href="<?php echo esc_url( $img[0] ); ?>"><i class="icofont icofont-image"></i></a>
		                </div>
		            </div>
		        </div>
				
			<?php endforeach; endif; ?>

	    </div>
	
	<?php elseif( $slider_style == 'screenshot-slider-2' ) :  ?>
        
        <div class="swiper-container two">
            <div class="swiper-wrapper">

				<?php 
					if( count( $slider_images ) > 0 ) : 
						foreach( $slider_images as $slider ) :


		    			$img = wp_get_attachment_image_src($slider, 'large');
				?>
	                <div class="swiper-slide">
	                    <div class="slider-image">
	                        <img src="<?php echo esc_url( $img[0] ); ?>" alt="" class="img-responsive">
	                        <div class="preview-icon">
	                            <a href="<?php echo esc_url( $img[0] ); ?>"><i class="icofont icofont-image"></i></a>
	                        </div>
	                    </div>
	                </div>

				<?php endforeach; endif; ?>

            </div>
            <!-- Add Pagination -->
            <div class="swiper-pagination"></div>
        </div>

	<?php endif; ?>

</div>