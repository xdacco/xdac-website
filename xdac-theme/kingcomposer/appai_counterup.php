<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="single-fact text-center">
        <i class="<?php echo esc_attr( $icon ); ?>"></i>
        <h5><?php echo esc_html( $title ); ?></h5>
        <h2 class="counter"><?php echo esc_html( $counter ); ?></h2>
    </div>
</div>