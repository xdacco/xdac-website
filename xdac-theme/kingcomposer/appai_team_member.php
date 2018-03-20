<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

	$image = wp_get_attachment_image_src($image, 'large');

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="single-member one text-center">

		<img alt="<?php echo esc_html( $name ); ?>" src="<?php echo esc_url( $image[0] ); ?>" class="img-responsive">

        <div class="member-description">
            <div class="member-description-inner">
                <h3 class="member-name"><?php echo esc_html( $name ); ?></h3>
                <p class="designation"><?php echo esc_html( $position ); ?></p>
                <p class="short-description"><?php echo esc_html( $about ); ?></p>
                <ul class="social list-inline">

	            	<?php foreach($social_profiles as $profiles) : ?>
	                	<li>
	                		<a href="<?php echo esc_html( $profiles->profile_link ); ?>">
	                		<i class="<?php echo esc_html( $profiles->social_icon ); ?>"></i>
	                		</a>
	                	</li>
	            	<?php endforeach; ?>
                    
                </ul>
            </div>
        </div>
    </div>
</div>