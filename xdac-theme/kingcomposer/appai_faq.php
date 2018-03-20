<?php

    extract( $atts );

    //custom class
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}


?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">

    <div class="faq-content-wrapper">
        <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="false">

        	<?php
        		if( count( $faqs ) > 0 ) :

        			$counter = 1;

        			foreach( $faqs as $faq ) :

        				$uid = uniqid();

        	?>
	            <div class="panel">
	                <div class="panel-heading" role="tab" id="<?php echo esc_attr( $uid ); ?>">
	                    <h4 class="panel-title">
	                    <a
	                    	role="button"
	                    	data-toggle="collapse"
	                    	data-parent="#accordion"
	                    	href="#id-<?php echo esc_attr( $uid ); ?>"
	                    	aria-expanded="true"
	                    	aria-controls="id-<?php echo esc_attr( $uid ); ?>"
	                    	class="collapsed"
	                    >
	                    	<?php echo esc_html( $counter ) . '. ' . esc_html( $faq->title ); ?>
	                    </a>
	                </h4>
	                </div>
	                <div
	                	id="id-<?php echo esc_attr( $uid ); ?>"
	                	class="panel-collapse collapse <?php if( $counter == 1) echo 'in'; ?>"
	                	role="tabpanel"
	                >
	                    <div class="panel-body">
	                        <p><?php echo $faq->answer; ?></p>
	                    </div>
	                </div>
	            </div>

	        <?php
	        		// Increment the counter
	        		$counter = $counter + 1;

	        		endforeach;

	        	else:
	        ?>

	            <div class="panel">
	                <div class="panel-heading" role="tab" id="headingOne">
	                    <h4 class="panel-title">
	                    <a
	                    	role="button"
	                    	data-toggle="collapse"
	                    	data-parent="#accordion"
	                    	href="#collapseOne"
	                    	aria-expanded="true"
	                    	aria-controls="collapseOne"
	                    	class="collapsed"
	                    >
	                    	No FAQ's
	                    </a>
	                </h4>
	                </div>
	                <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
	                    <div class="panel-body">
	                        <p>Please create a FAQ</p>
	                    </div>
	                </div>
	            </div>

	        <?php endif; ?>

        </div>
    </div>

</div>
