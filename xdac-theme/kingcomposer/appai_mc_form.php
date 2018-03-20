<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	
	<?php if( ! empty( $content ) ) : ?>
	    <div class="subscribe-box">
	        <?php echo do_shortcode( $content ); ?>
	    </div>
	<?php endif; ?>

</div>