<?php

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	<div class="section-heading text-center">
	    <h2><?php echo esc_html( $title ); ?></h2>
	    <p><?php echo esc_html( $desc ); ?></p>
	</div>
</div>
