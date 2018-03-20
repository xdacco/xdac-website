<?php 
	global $appaiObj;

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	<section class="breadcrumb-area breadcrumb-shortcode">
	    <div class="container">
	        <div class="row">
	            <div class="col-sm-12">
	                <h2>
	                	<?php 
	                		if( $select_title_type == 'post_page_title' ) {
	                			echo get_the_title();
	                		} else if( $select_title_type == 'custom_title' ) {
	                			echo esc_html( $title ); 	                			
	                		}
	                	?>
	                </h2>
	               	
	           		<?php 
	           			if( $show_pg_link_breadcrumbs == 'yes' ) {
	           				echo $appaiObj->siteBreadcrumbs();
	           			}
	           		?>

	    		</div>
	        </div>
	    </div>
	</section>
</div>