<?php

	global $wpdb;

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}

	$form = $wpdb->get_results("SELECT `ID` FROM `".$wpdb->posts."` WHERE `post_type` = 'wpcf7_contact_form' AND `post_name` = '".esc_attr(sanitize_title($slug))."' LIMIT 1");

?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
<div class="contact-box">
    <div class="container">


        <div class="contact-box-inner">
            <div class="row">
                <div class="col-sm-7">
                    <div class="get-in-touch p-100">

                        <h2><?php echo esc_html( $ls_title ); ?></h2>
                        <p><?php echo esc_html( $ls_subtitle ); ?></p>

    					<div id="appai-contact-form">

    						<?php
    							if( !empty( $form ) ){
    								echo do_shortcode('[contact-form-7 id="'.$form[0]->ID.'" title="'.esc_attr($ls_title).'"]');
    							}else{
    								echo esc_html__('Please select one of contact form 7 for display.', 'appai');
    							}
    						?>

    					</div>

                    </div>
                </div>
                <div class="col-sm-5">
                    <div class="address-box p-100">

                        <h2><?php echo esc_html( $rs_title ); ?></h2>
                        <p><?php echo esc_html( $rs_subtitle ); ?></p>

                        <ul class="address-info">
                        <?php
    			    		if( count( $c_infos ) > 0 ) :
    			    			foreach( $c_infos as $info ) :
    			    			 ?>

                            <li class="phone-number">
                                <div class="info-icon dsp-tc">
                                    <i class="<?php echo esc_attr( $info->icon ); ?>"></i>
                                </div>
                                <div class="info-details dsp-tc">
                                    <p><?php echo esc_html( $info->info ) ?></p>
                                </div>
                            </li>

    					<?php endforeach; endif; ?>
                        </ul>
                    </div>
                </div>
            </div>
            </div>
        </div>
    </div>
</div>
