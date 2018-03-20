<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


if( ! function_exists( 'appai_theme_setup' ) ) :

	function appai_theme_setup() {

		// Load the theme text domain
		load_theme_textdomain( 'appai', get_template_directory() . '/lang' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		// Add title tag
		add_theme_support( 'title-tag' );

		// Add post-thumbnails
		add_theme_support( 'post-thumbnails' );

		add_theme_support( 'woocommerce' );

        $defaults = array(
            'default-color'          => '#f8f8f8',
        );
        add_theme_support( 'custom-background', $defaults );

	    add_image_size( 'appai-post-img-small', 370, 235, true );
	    add_image_size( 'appai-post-img-medium', 555, 350, true );
	    add_image_size( 'appai-post-img-large', 1140, 600, true );

	    // Set content width
	    if ( ! isset( $content_width ) ) {
			$content_width = 600;
		}



		// Registering menu

		if( function_exists('register_nav_menus') ) {
			register_nav_menus( array(
                'primary-menu' => esc_html__('Primary Menu', 'appai'),
			) );
		}

	}

endif;

add_action( 'after_setup_theme', 'appai_theme_setup' );





/**
 * Detect Homepage
 *
 * @return boolean value
 */
function appai_detect_homepage() {
    // If front page is set to display a static page, get the URL of the posts page.
    $homepage_id = get_option( 'page_on_front' );

    // current page id
    $current_page_id = ( is_page( get_the_ID() ) ) ? get_the_ID() : '';

    if( $homepage_id == $current_page_id ) {
        return true;
    } else {
        return false;
    }

}

function appai_dd($var){
	echo '<pre>';
	print_r( $var );
	echo '</pre>';
}



/**
 *
 * Coming Soon Mode
 *
 */
function appai_coming_soon_mode() {
	global $current_user, $appai;

	$manage_options    = current_user_can( 'manage_options' );


	if( isset( $appai['coming_soon_mode'] ) && $appai['coming_soon_mode'] == true ) {

		if ( $manage_options == false ) {
			get_template_part( 'coming-soon-mode' );
			die();
		}

	}


}

add_action( 'template_redirect', 'appai_coming_soon_mode' );



function appai_admin_bar_menu(){
	global $wp_admin_bar, $appai;


	if( isset( $appai['coming_soon_mode'] ) && $appai['coming_soon_mode'] == true ) {

		//Add the main siteadmin menu item
		$wp_admin_bar->add_menu( array(
			'id'     => 'appai-coming-soon-notice',
			'href' => admin_url().'admin.php?page=Appai&tab=13',
			'parent' => 'top-secondary',
			'title'  => 'Coming Soon Mode Active',
			'meta'   => array( 'class' => 'appai-csmode-active' ),
		) );
	}

}

add_action( 'admin_bar_menu', 'appai_admin_bar_menu', 1000 );

/**
 *
 * The posts loop pagination
 *
 */
function appai_page_posts_loop( $template )
{
    if( have_posts() ) :

        while( have_posts() ) : the_post();

            get_template_part('templates/content', $template );

        endwhile;

    else:
        get_template_part('templates/no-post');
    endif;
}


/**
 * Menu fallback. Link to the menu editor if that is useful.
 *
 * @param  array $args
 * @return string
 */
function appai_link_to_menu_editor( $args )
{
    if ( ! current_user_can( 'manage_options' ) )
    {
        return;
    }

    // see wp-includes/nav-menu-template.php for available arguments
    extract( $args );

    $link = $link_before
        . '<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_attr( $before ) . esc_attr__('Create a menu', 'appai') . esc_attr($after) . '</a>'
        . $link_after;

    // We have a list
    if ( FALSE !== stripos( $items_wrap, '<ul' )
        or FALSE !== stripos( $items_wrap, '<ol' )
    )
    {
        $link = "<li>" . $link ."</li>";
    }

    $output = sprintf( $items_wrap, $menu_id, $menu_class, $link );
    if ( ! empty ( $container ) )
    {
        $output  = "<". esc_attr($container) ." class='". esc_attr($container_class) ."' id='". esc_attr($container_id) ."'>$output</". esc_attr($container) .">";
    }

    if ( $echo )
    {
        echo $output;
    }

    return $output;
}

// Change number or products per row to 3
add_filter('loop_shop_columns', 'appai_loop_columns');

if (!function_exists('appai_loop_columns')) {
	function appai_loop_columns() {
		return 3; // 3 products per row
	}
}


/**
 *
 * By adding filter to loop_shop_per_page
 *
 */
add_filter( 'loop_shop_per_page', create_function( '$cols', 'return 9;' ), 20 );



/**
 * woo_hide_page_title
 *
 * Removes the "shop" title on the main shop page
 *
 * @access      public
 * @since       1.0
 * @return      void
*/
add_filter( 'woocommerce_show_page_title' , 'appai_hide_shop_page_title' );
function appai_hide_shop_page_title() {
    return false;
}

/**
 *
 * WordPress link pages
 *
 */
function appai_wp_link_pages() {

	wp_link_pages( array(
		'before'      => '<div class="page-links"><span class="page-links-title">' . esc_html__( 'Pages:', 'appai' ) . '</span>',
		'after'       => '</div>',
		'link_before' => '<span>',
		'link_after'  => '</span>',
	) );

}


function appai_get_pulled_sidebar($pull_class) {

	echo '<aside class="col-md-4 '. $pull_class .' widget_col">';

				if( is_active_sidebar('appai_sidebar') ) :
					dynamic_sidebar( 'appai_sidebar' );
				endif;

	echo '</aside>';
}



/**
 *
 * Add classes to post class by filting
 * @return $classes
 */

add_filter('post_class', 'appai_post_class');

function appai_post_class( $classes ) {

    global $appai;

	$classes[] = 'blog-post';


    if( isset($appai['blog_grid']))
        if( !is_single() )
            $classes[] = $appai['blog_grid'];

	return $classes;
}


/**
 *
 * Build the classes for page builder wrapper class
 * @return $classes
 */
function appai_page_builder_wrapper_class() {

    global $appai;

    $output_class = '';

    $output_class .= 'page_builder_wrapper';
    $output_class .= ' clearfix';

    return $output_class;
}



/**
 *
 * Ajax Portfolio Load More
 * @return $classes
 *
 */
add_action('wp_ajax_nopriv_appai_ajax_pf_load_more', 'appai_ajax_pf_load_more');
add_action('wp_ajax_appai_ajax_pf_load_more', 'appai_ajax_pf_load_more');

function appai_ajax_pf_load_more(){

	$ppp = isset( $_POST['posts_per_page'] ) ? $_POST['posts_per_page'] : 3;
	$orderby = isset( $_POST['orderby'] ) ? $_POST['orderby'] : 'date';
	$order = isset( $_POST['order'] ) ? $_POST['order'] : 'ASC';
	$pf_cat = isset( $_POST['pf_category'] ) ? $_POST['pf_category'] : 0;
	$pf_big_items = isset( $_POST['pf_big_items'] ) ? $_POST['pf_big_items'] : false;
	$pf_extra_large_items = isset( $_POST['pf_extra_large_items'] ) ? $_POST['pf_extra_large_items'] : false;
	$pf_style = isset( $_POST['pf_style'] ) ? $_POST['pf_style'] : 'hover-with-border';
	$paged = isset( $_POST['paged'] ) ? $_POST['paged'] : 2;
	$paged++;

    $args = array(
        'post_type' => 'portfolio',
        'posts_per_page' => $ppp,
        'paged'    => $paged,
        'orderby'   => $orderby,
        'order'   => $order,
        'tax_query'   => array(
            array(
                'taxonomy' => 'portfolio-category',
                'field' => 'term_id',
                'terms' => array($pf_cat),
            )
        )
    );

    $posts = new WP_Query($args);

   	if( $posts->have_posts() ) :

        while( $posts->have_posts() ) :
        $posts->the_post();

        // Get taxonomies for single portfolio posts
        $tags      = get_the_terms( $posts->ID, 'portfolio-tag' );
        $tagsCount = count( $tags );
        $count      = 0;
        $termArr = array();

        // Loop over throuth each the terms
        if( $tags ) {
            foreach($tags as $type) {
                    $termArr[] = strtolower( $type->name ) ;
            }
        }

        // Get the post thumbnail attachment id and attachment meta data
        $attachment_id = get_post_thumbnail_id( get_the_ID() );
        $attachment_src = wp_get_attachment_image_src($attachment_id, 'maxive-portfolio-img-large');
        $thumb_img_meta = wp_get_attachment_metadata( $attachment_id );

        // Build classes for Portfolio Item
        $build_pf_item_classes = array();
        $build_pf_item_classes[] = 'portfolio-item';

        if( $pf_big_items == true ) {
	        if( $thumb_img_meta['width'] >= 600 ) {
	            $build_pf_item_classes[] = 'portfolio-item-big';
	        }
        }

        if( $pf_extra_large_items == true ) {
            if( $thumb_img_meta['width'] >= 1050 ) {
                $build_pf_item_classes[] = 'portfolio-extra-large';
            }
        }

        // Get the portfolio post terms
        $build_pf_item_classes[] = implode(' ', $termArr);

?>

        <div class="<?php  echo implode(' ', $build_pf_item_classes); ?>">
            <div class="portfolio-item-content">
                <?php if ( has_post_thumbnail() ) : ?>
                    <div class="item-thumbnail">

                        <?php the_post_thumbnail(); ?>


                        <?php if( $pf_style == 'portfolio-description-bottom' || $pf_style == 'portfolio-homepage-five' || $pf_style == 'portfolio-description-top' || $pf_style == 'portfolio-homepage-seven' || $pf_style == 'description-bottom' ) : ?>
                            <ul class="portfolio-action-btn">
                                <li><a href="<?php echo esc_url( $attachment_src[0] ); ?>" rel="lightbox" data-lightbox="portfolio-set" data-title="<?php echo get_the_title(); ?>"><i class="ti-fullscreen"></i></a></li>
                                <li><a href="<?php the_permalink(); ?>"><i class="ti-link"></i></a></li>
                            </ul>
                        <?php endif; ?>

                        <?php if( $pf_style == 'description-center' ) : ?>
                            <div class="description-action-btn-wrappwe">
                                <ul class="portfolio-action-btn-center">
                                    <li><a href="<?php echo esc_url( $attachment_src[0] ); ?>" rel="lightbox" data-lightbox="portfolio-set"  data-title="<?php echo get_the_title(); ?>"><i class="ti-fullscreen"></i></a></li>
                                    <li><a href="<?php the_permalink(); ?>"><i class="ti-link"></i></a></li>
                                </ul>
                                <div class="portfolio-description-center">
                                    <h4><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h4>
                                    <span class="portfolio-category">
                                        <?php
                                            // Increment the counter
                                            $count++;
                                            echo  implode(', ', $termArr);
                                        ?>
                                    </span>
                                </div>
                            </div>

                        <?php endif; ?>

                    </div>
                <?php else: ?>
                    <div class="item-thumbnail">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/img/placeholder-image.jpg" alt="">
                    </div>
                <?php endif; ?>

                <?php if( $pf_style !== 'description-center' ) : ?>
                    <div class="portfolio-description">
                        <h4><a href="<?php the_permalink(); ?>" ><?php the_title(); ?></a></h4>
                        <span class="portfolio-category">
                            <?php
                                // Increment the counter
                                $count++;

                                echo  implode(', ', $termArr);

                            ?>
                        </span>
                    </div>
                <?php endif; ?>

            </div>
        </div>


<?php

    endwhile;
    wp_reset_postdata();


    endif;

    die();


}



/**
 *
 * Appai Post Social Share Buttons
 * @return $classes
 *
 */

function appai_post_share_buttons() {


    global $appaiObj;


    // Get the post id
    $post_id = get_the_ID();

    // Get the post Title
    $post_title = get_the_title();

    // Get post excerpt
    $post_description = $appaiObj->postExcerpt(10, get_the_excerpt() );

    // Get the post media
    $attachment_id = get_post_thumbnail_id( $post_id );
    $post_media = wp_get_attachment_image_src( $attachment_id, 'appai-post-img-large');

    // get the post url
    $post_url = get_the_permalink();


	$share_media = array(
        array(
            'type'  => 'facebook',
            'icon'  => 'facebook',
        ),
        array(
            'type'  => 'twitter',
            'icon'  => 'twitter',
        ),
        array(
            'type'  => 'pinterest',
            'icon'  => 'pinterest',
        ),
        array(
            'type'  => 'googleplus',
            'icon'  => 'google-plus',
        ),
        array(
            'type'  => 'linkedin',
            'icon'  => 'linkedin',
        )
    );

	$output = '';

    $output .= '<ul class="appai-social-share list-inline">';

        foreach( $share_media as $media  ) :

        $output .=  '<li><a href="#"
			            data-type="' . esc_attr( $media['type'] ) .'"
			            data-url="' . esc_url( $post_url ) .'"
			            data-title="' . esc_attr( $post_title ) .'"
			            data-description="' . esc_attr( $post_description ) .'"
			            data-media="' . esc_url( $post_media[0] ) .'"
			        >
			            <i class="icofont icofont-social-' . esc_attr($media['icon']) . '"></i>
			        </a></li>';

        endforeach;

    $output .=  '</ul> ';

    return $output ;


}


/**
 * Registers an editor stylesheet for the theme.
 */
function appai_theme_add_editor_styles() {
    add_editor_style( 'custom-editor-style.css' );
}
add_action( 'admin_init', 'appai_theme_add_editor_styles' );



/**
 *
 * Building Comments Lists
 *
 */
function appai_comments_list( $comment, $args, $depth ) {

    $GLOBALS['comment'] = $comment;
    switch( $comment->comment_type ) :
        case 'tracback' :
        case 'pingback' : ?>

        <li <?php esc_attr( comment_class() ); ?> id="comment-<?php esc_attr( comment_ID() ); ?>">
		<p><span class="title"><?php esc_html_e( 'Pingback:', 'appai' ); ?></span> <?php esc_url( comment_author_link() ); ?> <?php esc_url ( edit_comment_link( __( '(Edit)', 'appai' ), '<span class="edit-link">', '</span>' ) ); ?></p>

        <?php break;
        default : ?>
		<article <?php esc_attr( comment_class() ); ?>  id="comment-<?php esc_attr(comment_ID() ); ?>">

			<div class="media">
				<div class="media-left">
					<a href="#">
					 	<?php echo get_avatar( $comment, 90 ); ?>
					</a>
				</div>
				<div class="media-body">
					<h5 class="media-heading"><?php esc_html( comment_author() ); ?></h5>

					<div class="clearfix comment-meta">
						<span class="time">
							<?php echo esc_html( the_time( get_option('date_format') ) );?> <?php  esc_html( comment_time() ); ?>
							<span class="edit-link">
								<?php esc_url( edit_comment_link( esc_html__('- Edit', 'appai') ) ); ?>
							</span>

						</span>
						<span class="reply-link">
							<?php
						        comment_reply_link( array_merge( $args, array(
						            'reply_text' => esc_html__(' - Reply', 'appai'),
						            'after' => ' <span> &#8595; </span>',
						            'depth' => $depth,
						            'max_depth' => $args['max_depth']
						        ) ) );
						    ?>
						</span>
					</div>

					<div class="comment-text-body">
						<?php
							if( $comment->comment_approved  == 0 ) {
								esc_html_e('Your comment is awating for moderation.', 'appai');
							} else {
								comment_text();
							}
						?>

					</div>




				</div>
			</div>
		</article>

        <?php // End the default styling of comment
        break;
    endswitch;
}



/**
 *
 * Registering Google Fonts
 *
 */
function appai_google_fonts_url() {

    $font_url = '';

    if ( 'off' !== _x( 'on', 'Google font: on or off', 'appai' ) ) {
        $font_url = add_query_arg( 'family', urlencode( 'Raleway:300,400,500,600,700|Roboto:300,400,500,700&subset=latin,latin-ext' ), "//fonts.googleapis.com/css" );
    }

    return $font_url;
}


/**
 *
 * Enqueuing Google Maps Script with  API key
 *
 */
function appai_google_maps_js_script() {

	global $appai;

	if( isset( $appai['google-api-key'] ) ) {
		$api_key = $appai['google-api-key'];

		wp_enqueue_script( 'appai-google-map-api', 'https://maps.googleapis.com/maps/api/js?key=' . $api_key, array('jquery'), null, true );
	}
}





/**
 *
 * Custom Comment Form
 *
 */

add_filter('comment_form_defaults', 'appai_custom_comment_form');

function appai_custom_comment_form( $defaults ) {
	$defaults['title_reply'] = esc_attr__('Leave a Reply', 'appai');
	$defaults['comment_notes_before'] = esc_attr__(' ', 'appai');
	$defaults['comment_notes_after'] = '';
	$defaults['class_form'] = 'comment_form animated fadeInUp';	$defaults['comment_field'] = '<textarea name="comment" placeholder="'. esc_attr__('', 'appai') .'"></textarea>';

	return $defaults;
}


/**
 *
 * Shape custom comments field
 *
 */

add_filter('comment_form_default_fields', 'appai_custom_comment_form_fields');

function appai_custom_comment_form_fields() {
	$commenter = wp_get_current_commenter();
	$req = get_option('required_name_email');
	$aria_req = ($req ? " aria-required='true'" : ' ');

	$yourNamePlaceholder  = $aria_req ? esc_attr__('Your name *', 'appai') : esc_attr__('Your name ', 'appai');
	$yourEmailPlaceholder = $aria_req ? esc_attr__('Your email *', 'appai') : esc_attr__('Your email ', 'appai');

	$fields = array(
		'author' => '<div class="row"><div class="col-md-4"><input
						type="text"
						id="author"
						name="author"
						placeholder="'. $yourNamePlaceholder .'"
						value="'. esc_attr( $commenter['comment_author'] ) .'"
						'. $aria_req .'
					></div>',

		'email' => '<div class="col-md-4"><input
						type="email"
						id="email"
						name="email"
						placeholder="'. $yourEmailPlaceholder .'"
						value="'. esc_attr( $commenter['comment_author_email'] ) .'"
						'. $aria_req .'
					></div>',

		'url' => '<div class="col-md-4"><input
						type="url"
						id="url"
						name="url"
						placeholder="'. $yourEmailPlaceholder .'"
						value="'. esc_attr( $commenter['comment_author_url'] ) .'"
						'. $aria_req .'
					></div></div>',

	);

	return $fields;
}


/**
 *
 * Maxive Tag Cloud font size
 *
 */
function appai_tag_cloud_widget($args) {
    $args['largest'] = 12; //largest tag
    $args['smallest'] = 12; //smallest tag
    $args['unit'] = 'px'; //tag font unit
    return $args;
}
add_filter( 'widget_tag_cloud_args', 'appai_tag_cloud_widget' );





/**
 *
 * Remove Redux Framework Notices
 *
 */

function appai_remove_demo_redux_notice() {
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_filter( 'plugin_row_meta', array( ReduxFrameworkPlugin::get_instance(), 'plugin_metalinks'), null, 2 );
    }
    if ( class_exists('ReduxFrameworkPlugin') ) {
        remove_action('admin_notices', array( ReduxFrameworkPlugin::get_instance(), 'admin_notices' ) );
    }
}
add_action('init', 'appai_remove_demo_redux_notice');


/**
 * Get blog posts page URL.
 *
 * @return string The blog posts page URL.
 */
function appai_get_blog_posts_page_url() {
	// If front page is set to display a static page, get the URL of the posts page.
	if ( 'page' === get_option( 'show_on_front' ) ) {
		return esc_url(get_permalink( get_option( 'page_for_posts' ) ));
	}
	// The front page IS the posts page. Get its URL.
	return esc_url(get_home_url('/'));
}


/**
 *
 * Check if the specified plugin is active or not
 *
 * @return  boolean
 */
function appai_is_plugin_active( $name ) {

    if ( in_array( $name, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
        return true;
    } else {
        return false;
    }
}


/**
 *
 * Add custom class to body_class
 *
 */

add_filter( 'body_class', 'appai_extra_body_class' );
function appai_extra_body_class( $classes ) {

    if( function_exists('get_field') ) {

        // ----------  Header Positions for specific page  ----------
        $pageHeaderPosition = get_post_meta(get_the_ID(), 'choose_header_position', true);
        if( $pageHeaderPosition == 'static') {
            $classes[] = 'position-static';
        }


    }
		return $classes;

}

function appai_get_site_logo() {

    global $appai;

    $logo = '';
    $logo_url = '';

    $custom_logo = get_post_meta( get_the_ID(), 'page_custom_logo', true );

    if( $custom_logo ) {
        $img_url = wp_get_attachment_image_src( $custom_logo );
        $logo_url .= esc_url( $img_url[0] );
    } else if( isset( $appai['logo'] ) ) {
        $logo_url .= esc_url( $appai['logo']['url'] );
    } else {
        $logo_url .= get_template_directory_uri() .'/assets/img/logo/logo-3.png';
    }


    $logo .= '<img src="'. $logo_url .'" alt="'. get_bloginfo('title') .'">';

    return $logo;
}


/**
 * Set header class dynamically
 *
 */
function appai_set_header_class() {

    // Globalizing theme options variables
    global $appai;

    // Get the homepage ID
    $homepage_id = get_option( 'page_on_front' );

    // current page id
    $current_page_id = ( is_page( get_the_ID() ) ) ? get_the_ID() : '';



    $output_class = array();
    $output_class[] = 'global-header';

    // If transparent header is set from Theme Options
    if( isset($appai['transarent_header']) && $appai['transarent_header'] == true ) {

        if( $homepage_id == $current_page_id ) {
            $output_class[] = 'floating-header';
        }

    };

    if( isset( $appai['menu_scheme'] ) ) {
        $output_class[] = $appai['menu_scheme'];
    }


    //
    // If get_field function is exsits
    // This functions active when ACF is active
    //
    if( function_exists('get_field') ) {

        /*----------  Header Positions for specific page  ----------*/
        if( get_field('choose_header_position') == 'static' ) {

            if( ($key = array_search('floating-header', $output_class)) !== false) {
                unset($output_class[$key]);
            }

            $output_class[] = 'position-static';

        }

        if( get_field('choose_header_position') == 'absolute' ) {

            if(! array_search('floating-header', $output_class) !== false ) {
                $output_class[] = 'floating-header';
            }

        }


        /*----------  Header Menu Scheme for specific page  ----------*/
        if( get_field('menu_scheme') && get_field('menu_scheme') !== 'inherit' ) {

            if( ($key = array_search($appai['menu_scheme'], $output_class)) !== false) {
                unset($output_class[$key]);
            }

            $output_class[] = get_field('menu_scheme');

        }

        /*----------  Sticky Header Menu Scheme for specific page  ----------*/
        if( get_field('sticky_menu_scheme') && get_field('sticky_menu_scheme') !== 'inherit' ) {

            $output_class[] = 'sticky-' . get_field('sticky_menu_scheme');

        }


    }



    return $output_class;


}


/**
 *
 * Footer toggle depending on page meta and theme options
 *
 */
function appai_footer_toggle() {

	// Globalizing theme options variables
	global $maxive;

    $page_hide_footer = get_post_meta( get_the_ID(), 'appai_footer_switch', true );

    if( $page_hide_footer == 'on' ) {

        return 'hide';

    } elseif( isset($maxive['footer_switch']) && $maxive['footer_switch'] == false ) {

        return 'hide';

    } else {
    	return 'show';
    }


}



/**
 *
 * Set the header navbar class
 *
 */
function appai_set_nav_container_class() {

	// Globalizing theme options variables
	global $maxive;

    $output_class = '';


	$page_header_container = get_post_meta( get_the_ID(), 'appai_header_container', true );

	if( $page_header_container ) {

		$output_class .= esc_html( $page_header_container );

	} elseif( isset( $maxive['header_container'] ) ) {

		$output_class .= esc_html( $maxive['header_container'] );

    } else {
    	$output_class .= esc_html('container');
    }

    return $output_class;
}



/**
 *
 * Maxive Hide Logo On Frontpage
 *
 */
function appai_hide_logo_frontpage() {

	// Globalizing theme options variables
	global $maxive;

    // Get the homepage ID
    $homepage_id = get_option( 'page_on_front' );

    // current page id
    $current_page_id = get_the_ID();


    $output_class = '';

    if( isset($maxive['disable_logo_frontpage']) && $maxive['disable_logo_frontpage'] == true ) {

    	if( $homepage_id == $current_page_id ) {
            $output_class .= 'hide-logo ';
        }

    }

    return $output_class;

}








/**
 *
 * One click demo Installation for Shape theme
 *
 */

function appai_import_files() {
  return array(
    array(
      'import_file_name'           => 'Appai - All',
      'local_import_file'            => APPAI_INC_DIR . '/demo-contents/appai_content.xml',
      'local_import_widget_file'     => APPAI_INC_DIR . '/demo-contents/appai_widgets.wie',
      'import_redux'               => array(
        array(
          'file_url'    => 'http://themes.dhrubok.website/theme-demos/appai/appai_theme_option.json',
          'option_name' => 'appai',
        ),
      ),
      'import_preview_image_url'   => 'http://themes.dhrubok.website/theme-demos/appai/screenshot.png',
      'import_notice'              => esc_html__( 'After importing the demo, you need set your preferred homepage layout from ', 'appai') .
      		'<a href="'. trailingslashit(home_url('/')) .'wp-admin/options-reading.php">'. esc_html__('here', 'appai') .'</a>',
    ),
  );
}
add_filter( 'pt-ocdi/import_files', 'appai_import_files' );
