<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

	$divider = ( $show_divider == 'yes' ) ? ' has_divider' : '';
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
    <div class="media<?php echo esc_attr( $divider ); ?>">
        <div class="usage-icon media-left">
            <i class="<?php echo esc_attr( $stats_icon ); ?>"></i>
        </div>
        <div class="useges-quantity media-body">
            <h2><?php echo esc_html( $stats_number ); ?></h2>
            <p><?php echo esc_html( $stats_name ); ?></p>
        </div>
    </div>
</div>