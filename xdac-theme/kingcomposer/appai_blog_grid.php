<?php 

    extract( $atts );

    //custom class		
	$wrap_class  = apply_filters( 'kc-el-class', $atts );

	if( ! empty( $extra_class ) ) {
		$wrap_class[] = $extra_class;
	}
?>

<div class="<?php echo esc_attr( implode( ' ', $wrap_class ) ); ?>">
	<?php 	

		global $appaiObj;

        // Query for blog posts
        $args = array(
            'post_type' => 'post',
            'posts_per_page'   => $limit,
	        'orderby'   => $orderby,
	        'order'   => $order,
        );

        $posts = new WP_Query( $args );

        if( $posts->have_posts() ) :

        	echo '<ul>';

        	while( $posts->have_posts() ) :
        		$posts->the_post();

        		$post_permalink = get_the_permalink();

	?>
		
		<li class="<?php echo esc_attr( $blog_grid ); ?>">
		        <article class="blog-post appai-shortcode">

		            <?php if( $post_thumbnail_show == 'yes' ) : ?>
		        		<?php if( has_post_thumbnail() ) : ?>

				            <div class="post-thumbnail">
					            <a href="<?php echo esc_url( $post_permalink ); ?>">
				                    <?php the_post_thumbnail($posts->ID); ?>
				                </a>
				            </div>

		                <?php endif; ?>
		        	<?php endif; ?>

		            <div class="post-content">
		                <div class="post-content-inner">

		                	<?php if( $post_meta_display == 'yes' ) : ?>

			                    <ul class="meta-info list-inline">

			                        <li class="posted-by">
			                        	<?php esc_html_e('By: ', 'appai');  esc_url( the_author_posts_link() ); ?>
                   					</li>

			                        <li class="post-date">
			                        	<a href="<?php echo esc_url( $post_permalink ); ?>"><?php esc_html( the_time( get_option('date_format') ) ); ?></a>
			                        </li>

			                        <li class="comments-quantity">
				                        <?php if( comments_open() ) : ?>
						                        <?php comments_popup_link(
						                            esc_html__('No Comments', 'appai'), 
						                            esc_html__('1 Comment', 'appai'), 
						                            esc_html__('% Comments', 'appai')
						                        ); ?> 
						                <?php endif; ?>
					                </li>
					                
			                    </ul>

		                   	<?php endif; ?>

		                	<?php if( $post_title_show == 'yes' ) : ?>

			                    <?php echo '<' . esc_attr( $title_tag ) . ' class="post-title">'; ?>
			                    	<a href="<?php echo esc_url( $post_permalink ); ?>">
			                    		<?php esc_html(  the_title() ); ?>
		                    		</a>
			                    <?php echo '</' . esc_attr( $title_tag ) . '>'; ?>

		                   	<?php endif; ?>

		                	<?php if( $post_excerpt_display == 'yes' ) : ?>

			                    <p>
			                    	<?php 
			                    		echo esc_html(  $appaiObj->postExcerpt( $content_limit , get_the_excerpt() ) ); 
			                    	?>
			                    </p>

		                   	<?php endif; ?>

		                </div>

		               	<?php if( $post_permalink_display == 'yes' ) : ?>

			                <div class="read-more-wrapper">
			                    <a href="<?php echo esc_url( $post_permalink ); ?>" class="read-more-btn">
			                    	<?php echo esc_html( $read_more_btn_text ); ?> <i class="icofont icofont-long-arrow-right"></i>
			                    </a>
			                </div>

		                <?php endif; ?>

		            </div>
		        </article>
		</li>


	<?php 
			endwhile; 

			echo '</ul>';

		endif; 
	?>

</div>