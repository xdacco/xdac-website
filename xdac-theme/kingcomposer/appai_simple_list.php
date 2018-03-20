<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	<ul class="address-info">
		<?php
			if( count( $lists ) > 0 )  :
				foreach( $lists as $list ) :
		?>
		    <li class="phone-number">
		    	<?php if( ! empty( $list->icon ) ) : ?>
			        <div class="info-icon dsp-tc">
			            <i class="<?php echo esc_attr( $list->icon ); ?>"></i>
			        </div>
			    <?php endif; ?>
		        <div class="info-details dsp-tc">
		            <p><?php echo esc_html( $list->title ); ?></p>
		        </div>
		    </li>
		<?php
				endforeach;
			endif;
		?>

	</ul>
</div>