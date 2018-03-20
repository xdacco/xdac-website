<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}


?>


<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="awesome-feature one media">
        <div class="awesome-feature-icon media-left">
            <span><i class="<?php echo esc_attr( $icon ); ?>"></i></span>
        </div>
        <div class="asesome-feature-details media-body">
            <h5><?php echo esc_html( $title ); ?></h5>
            <p><?php echo esc_html( $desc ); ?></p>
        </div>
    </div>
</div>