<?php

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">

	<?php if( $slider_style == 'style1' ) : ?>
        <section id="slider-area" class="home-style-1">
            <div class="slider-wrapper">

            	<?php
            		if( count( $appai_slides ) > 0 ) :
            			foreach( $appai_slides as $slide ) :
            				$img = wp_get_attachment_image_src( $slide->image, 'larger' );
            				$anime_name = $slide->img_animation;
            	?>
	                <div class="slide one">
	                    <div class="container">
	                        <div class="slider-text wow fadeIn">
	                            <h3 class="slider-title">
	                            	<?php
	                            		// No need to esc_htlm here
	                            		// HTML markup can be used
	                            		echo   $slide->title; ?>
	                            </h3>
	                            <p><?php echo esc_html( $slide->description ); ?></p>

	                            <?php if( $slider_btn_switch == 'yes' ) : ?>

                                    <div class="button-group">
                                        <?php
                                            if( !empty( $slide->btn1_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn1_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn1_label ) .'</a>';
                                            endif;
                                            if( !empty( $slide->btn2_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn2_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn2_label ) .'</a>';
                                            endif;
                                            if( !empty( $slide->btn3_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn3_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn3_label ) .'</a>';
                                            endif;
                                        ?>

                                    </div>
    		                    <?php endif; ?>

	                        </div>
	                        <div class="slider-image wow <?php echo esc_attr( $anime_name ); ?>" data-wow-duration="2s">
	                            <img src="<?php echo esc_url( $img[0] ); ?>" alt="<?php echo esc_html( $slide->title ); ?>">
	                        </div>
	                    </div>
	                </div>
				<?php endforeach; endif; ?>
            </div>

			<?php if( $scroll_down_icon_switch == 'yes' ) : ?>
				<div class="scroll-icon text-center">
	                <a href="#<?php echo esc_attr( $scroll_to_link ); ?>" class="wow fadeInDown infinite" data-wow-duration="2s">
	                	<i class="icofont icofont-scroll-long-down"></i>
	                </a>
	            </div>
	        <?php endif; ?>
        </section>

	<?php elseif( $slider_style == 'style2' ) : ?>
        <section id="slider-area" class="home-style-3">
            <div class="slider-wrapper">

            	<?php
            		if( count( $appai_slides ) > 0 ) :
            			foreach( $appai_slides as $slide ) :
            				$img = wp_get_attachment_image_src( $slide->image, 'larger' );
            				$anime_name = $slide->img_animation;
            	?>
	                <div class="slide one">
	                    <div class="container">
	                        <div class="slider-text wow fadeIn">
	                            <h3 class="slider-title">
	                            	<?php
	                            		// No need to esc_htlm here
	                            		// HTML markup can be used
	                            		echo   $slide->title; ?>
	                            </h3>
	                            <p><?php echo esc_html( $slide->description ); ?></p>

                                <?php if( $slider_btn_switch == 'yes' ) : ?>

                                    <div class="button-group">
                                        <?php
                                            if( !empty( $slide->btn1_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn1_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn1_label ) .'</a>';
                                            endif;
                                            if( !empty( $slide->btn2_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn2_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn2_label ) .'</a>';
                                            endif;
                                            if( !empty( $slide->btn3_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn3_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn3_label ) .'</a>';
                                            endif;
                                        ?>

                                    </div>
    		                    <?php endif; ?>
	                        </div>
	                        <div class="slider-image wow <?php echo esc_attr( $anime_name ); ?>" data-wow-duration="2s">
	                            <img src="<?php echo esc_url( $img[0] ); ?>" alt="<?php echo esc_html( $slide->title ); ?>">
	                        </div>
	                    </div>
	                </div>
				<?php endforeach; endif; ?>
            </div>

			<?php if( $scroll_down_icon_switch == 'yes' ) : ?>
				<div class="scroll-icon text-center">
	                <a href="#<?php echo esc_attr( $scroll_to_link ); ?>" class="wow fadeInDown infinite" data-wow-duration="2s">
	                	<i class="icofont icofont-scroll-long-down"></i>
	                </a>
	            </div>
	        <?php endif; ?>
        </section>


	<?php elseif( $slider_style == 'style3' ) : ?>
        <section id="slider-area" class="home-style-10">
            <div class="slider-wrapper">

            	<?php
            		if( count( $appai_slides ) > 0 ) :
            			foreach( $appai_slides as $slide ) :
            				$img = wp_get_attachment_image_src( $slide->image, 'larger' );
            				$anime_name = $slide->img_animation;
            	?>
	                <div class="slide one">
	                    <div class="container">
	                        <div class="slider-text wow fadeIn">
	                            <h3 class="slider-title">
	                            	<?php
	                            		// No need to esc_htlm here
	                            		// HTML markup can be used
	                            		echo   $slide->title; ?>
	                            </h3>
	                            <p><?php echo esc_html( $slide->description ); ?></p>

                                <?php if( $slider_btn_switch == 'yes' ) : ?>

                                    <div class="button-group">
                                        <?php
                                            if( !empty( $slide->btn1_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn1_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn1_label ) .'</a>';
                                            endif;
                                            if( !empty( $slide->btn2_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn2_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn2_label ) .'</a>';
                                            endif;
                                            if( !empty( $slide->btn3_label ) ) :
                                                echo '<a href="'. esc_url($slide->btn3_url) .'" class="btn btn-bordered-white">'. esc_html( $slide->btn3_label ) .'</a>';
                                            endif;
                                        ?>

                                    </div>
    		                    <?php endif; ?>
	                        </div>
	                        <div class="slider-image wow <?php echo esc_attr( $anime_name ); ?>" data-wow-duration="2s">
	                            <img src="<?php echo esc_url( $img[0] ); ?>" alt="<?php echo esc_html( $slide->title ); ?>">
	                        </div>
	                    </div>
	                </div>
				<?php endforeach; endif; ?>
            </div>

			<?php if( $scroll_down_icon_switch == 'yes' ) : ?>
				<div class="scroll-icon text-center">
	                <a href="#<?php echo esc_attr( $scroll_to_link ); ?>" class="wow fadeInDown infinite" data-wow-duration="2s">
	                	<i class="icofont icofont-scroll-long-down"></i>
	                </a>
	            </div>
	        <?php endif; ?>
        </section>

    <?php endif; ?>

</div>
