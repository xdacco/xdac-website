<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

class AppaiMain{

	//
	// Get the header style variation
	//
	public function headerStyles()
	{
		global $appai;

	    $header_style_opt = isset($appai['header_layout']) ? $appai['header_layout'] : 'header-style-1';

			$headerOptions = $this->headerVariations();

			// Get the  header styles from Page Meta Data
			$pageStyle = get_post_meta(get_the_ID(), 'appai_header_page_header_style', true);


			// Apply Header Style if the page has any specific styles
			// Priority  Level: 1
			if (  $pageStyle  ) {
					include( get_template_directory() . '/templates/headers/'. $pageStyle .'.php' );
			} else {

				// If the page has no specific style
				// Then select header style from Theme Options
				// Priority Level: 2
				foreach($headerOptions as $header) {

					if( $header == $header_style_opt ){

							// If the file exists locate the file
							// or include the default one
							if( file_exists( get_template_directory() . '/templates/headers/'. $header_style_opt .'.php' ) ) {

									include( get_template_directory() . '/templates/headers/'. $header_style_opt .'.php' );
							} else {
									include( get_template_directory() . '/templates/headers/header-style-1.php' );
							}
					}

				}
			}
	}


	// Get header variations
	public function headerVariations()
	{
		$arr = array(
			'header-style-1',
		);

	    return $arr;
	}


	/**
	 *
	 * Get Single Post Sidebar Options
	 *
	 */
	public function get_sidebar_options()
	{
	 	$blog_sidebar = get_post_meta(get_the_ID(), 'page_blog_sidebar', true);

	 	if( $blog_sidebar ) {
	 		return $blog_sidebar;
	 	} else if( isset( $appai['blog_layout'] ) ) {
	 		return $appai['blog_layout'];
	 	} else {
	 		return 'right-sidebar';
	 	}

	}

	//
	// Get WooCoomerce Post Loop
	//
	public function getWoocommerceLoop( $col_class )
	{
	    echo '<div class="'. $col_class .'">';

                if ( have_posts() ) :
                    woocommerce_content();
                endif;

       echo '</div>';

	}


	//
	// Get WooCoomerce Sidebar
	//
	public function getWoocommerceSidebar( $col_class )
	{
        echo '<div class="'. $col_class .'  widget_col">';
                if( is_active_sidebar('appai_shop_widgets') ) {
                    dynamic_sidebar('appai_shop_widgets');
                }
        echo '</div>';
	}


	/**
	 *
	 * Appai posts loop with essential markup
	 *
	 */
	public function thePostLoop( $col_class )
	{ ?>
		<div class="m-blog-posts-wrapper <?php echo esc_attr( $col_class ) ?>">


	        <?php
            	if( is_single() ) {
            		echo '<div class="m-blog-posts-listing">';
            			$this->postLoop( 'single' );
            		echo '</div>';
            	} elseif( is_page() ) {
            		echo '<ul class="m-blog-posts-listing">';
            			$this->postLoop( 'page' );
            		echo '</ul>';
            	} else {
            		echo '<ul class="m-blog-posts-listing">';
            		$this->blogGrid();
            		echo '</ul>';
            	}
            ?>


            <div class="row pagination">
                <div class="col-sm-12">
                    <!-- pagination -->
                    <?php $this->pagination(); ?>
                </div>
            </div>
        </div>

	<?php
	}


	/**
	 *
	 * Appai blog grid
	 *
	 */
	public function blogGrid()
	{
	    global $appai;


	    //
	    // Check if the plugin is active or not
	    //
	    if( appai_is_plugin_active('redux-framework/redux-framework.php') !== false  ) {

		    if( isset( $appai['blog_grid'] ) ) :

				$this->postLoop( $appai['blog_grid'] );

			endif;

	    } else {

			$this->postLoop( 'one-column' );

	    }


	}

	public function pagePostLoop(  )
	{
		global $appai;


		$sp_layout = isset( $appai['single_page_layout'] ) ? $appai['single_page_layout'] : 'fullpage';

	    if( $sp_layout == 'right-sidebar' ) {
	    	echo '<div class="col-md-9">';
	    		$this->postLoop( 'page' );
	    	echo '</div>';

	    	get_sidebar();
	    } elseif( $sp_layout == 'left-sidebar' ) {
	    	get_sidebar();

	    	echo '<div class="col-md-9">';
	    		$this->postLoop( 'page' );
	    	echo '</div>';

	    } elseif ( $sp_layout == 'fullpage') {
	    	echo '<div class="col-md-12">';
	    		$this->postLoop( 'page' );
	    	echo '</div>';
	    }
	}


	/**
	 *
	 * Shape Post Loop
	 * @param   $template || string || accepts template column grid
	 *
	 */
	public function postLoop( $template )
	{
	    if( have_posts() ) :

	        while( have_posts() ) : the_post();

	            get_template_part('templates/content', $template );

	        endwhile;

	    else:
	        get_template_part('templates/content', 'no-post');
	    endif;
	}

	public function getPulledSidebar($col_class)
	{
	 	echo '<div class="blog_widgets '. $col_class .'">';
	 		get_sidebar();
	 	echo '</div>';
	}


	/**
	 * Excert data from the content
	 */
	public function postExcerpt($limit, $content = null) {
		if($content) {
			$post = $content;
		} else {
			$post = get_the_content();
		}

		$post_content = explode(' ', $post);
		$sliced_content = array_slice($post_content, 0, $limit);
		$return_content = implode(' ', $sliced_content);

		if( count( $post_content ) > $limit ) {
			return $return_content  . '... ' ;
		} else {
			return $return_content ;
		}

	}

	/**
	 *
	 * The WordPress pagination
	 *
	 */
	public function pagination()
	{
		return the_posts_pagination( array(
	            'prev_text' => esc_html__('prev', 'appai'),
	            'next_text' =>  esc_html__('next', 'appai'),
	            'screen_reader_text' => ' ',
	        ) );
	}




	/**
	 *
	 * Get page content and the comment
	 *
	 */

	public function theContentWithComment()
	{
		// The content
		the_content();


		// Wrapper for the comment
		echo '<div class="page-comments">';
			// If comments is open
		    if( comments_open() ) {
		        comments_template();
		    }
	    echo '</div>';
	}



	public function getPortfolioCategories()
	{
		$catArray = array();


	    return $catArray;
	}


	public function appai_breadcrumb_bridge()
	{
		global $appai;

		if ( isset($appai['breadcrumb_on'] ) && $appai['breadcrumb_on'] == true ) {

        	echo $this->appai_get_the_breadcrumbs();

	    } elseif ( ! in_array( 'redux-framework/redux-framework.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

    		echo $this->appai_get_the_breadcrumbs();

        }

	}


	/**
	 *
	 * Breadcrumb
	 * @return breadcrumb
	 */
	public function appai_get_the_breadcrumbs()
	{

	    $appai_breadcrumb_cs_title_switch = get_post_meta( get_the_ID(), 'appai_breadcrumb_title_switch', true );
	    $appai_breadcrumb_cs_title = get_post_meta( get_the_ID(), 'appai_breadcrumb_title', true );

	    if( $appai_breadcrumb_cs_title_switch ) {
	    	$title  = $appai_breadcrumb_cs_title;
	    } else {
				$title = $this->generateBreadCrumbTitle();
	    }

	    $output  = '';

		$output .= '<section class="breadcrumb-area">
        <div class="container">
            <div class="row">
                <div class="col-sm-12">';

					if( $this->generateBreadCrumbTitle() ) :
	                    $output .= '<h2>'. $this->generateBreadCrumbTitle() .'</h2>';
					endif;

               $output .=  $this->siteBreadcrumbs();
        $output .= '</div>
            </div>
        </div>
    </section>';


		return $output;

	}


	/**
	 *
	 * Generate the breadcrumb title depending on the page
	 *
	 */
	public function generateBreadCrumbTitle()
	{
		global $appai;

	    $title = '';


		if (is_home() || is_front_page()) {
			$title = isset( $appai['bp_title'] ) ? $appai['bp_title'] : 'Read the latest <br> from our blog';
		} elseif( is_single() ) {
			$title = isset( $appai['sp_title'] ) ? $appai['sp_title'] : 'Blog Details';
		} elseif( is_page() ) {
			$title = get_the_title();
		} elseif( is_archive() ) {
			$title = get_the_archive_title();
		} elseif( is_search() ) {
			$title = esc_html__('Search results for: ', 'appai') . get_search_query();
		}


		if( appai_is_plugin_active('woocommerce/woocommerce.php') == true ) :

			if( is_shop() ){
				$title = isset( $appai['shop_p_title'] ) ? $appai['shop_p_title'] : 'Products';
			}

			if( is_product() ){
				$title = isset( $appai['product_title'] ) ? $appai['product_title'] : 'Product Details';
			}

		endif;

	    return $title;
	}



	public function siteBreadcrumbs() {

        $appai = get_option( 'appai' );

		$seperator_opt = ( isset( $appai['breadcrumb_sep'] ) ? $appai['breadcrumb_sep'] : '-' );

		/* === OPTIONS === */
		$text['home']     = esc_html__('Home', 'appai'); // text for the 'Home' link
		$text['category'] = esc_html__('Archive by Category "%s"', 'appai'); // text for a category page
		$text['search']   = esc_html__('Search Results for "%s" Query', 'appai'); // text for a search results page
		$text['tag']      = esc_html__('Posts Tagged "%s"', 'appai'); // text for a tag page
		$text['author']   = esc_html__('Articles Posted by %s', 'appai'); // text for an author page
		$text['404']      = esc_html__('Error 404', 'appai'); // text for the 404 page
		$text['page']     = esc_html__('Page %s', 'appai'); // text 'Page N'
		$text['cpage']    = esc_html__('Comment Page %s', 'appai'); // text 'Comment Page N'

		$wrap_before    = '<div class="breadcrumbs">'; // the opening wrapper tag
		$wrap_after     = '</div><!-- .breadcrumbs -->'; // the closing wrapper tag
		$sep            = $seperator_opt; // separator between crumbs
		$sep_before     = '<span class="sep">'; // tag before separator
		$sep_after      = '</span>'; // tag after separator
		$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
		$show_on_home   = 0; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_current   = 1; // 1 - show current page title, 0 - don't show
		$before         = '<span class="current">'; // tag before the current crumb
		$after          = '</span>'; // tag after the current crumb
		$output 		= '';
		/* === END OF OPTIONS === */

		global $post;
		$home_url       = esc_url( home_url('/') );
		$link_before    = '<span >';
		$link_after     = '</span>';
		$link_attr      = ' id="home"';
		$link_in_before = '<span>';
		$link_in_after  = '</span>';
		$link           = $link_before . '<a href="%1$s"' . '>' . $link_in_before . '%2$s' . $link_in_after . '</a>' . $link_after;
		$frontpage_id   = get_option('page_on_front');

		if(  is_object($post) && $post->post_parent !== null ) {
			$parent_id      = $post->post_parent;
		}
		$sep            = ' ' . $sep_before . $sep . $sep_after . ' ';
		$home_link      = $link_before . '<a href="' . $home_url . '"' . ' class="home">' . $link_in_before . $text['home'] . $link_in_after . '</a>' . $link_after;

		if (is_home() || is_front_page()) {

			if ($show_on_home) $output .= $wrap_before . $home_link . $wrap_after;

		} else {

			$output .= $wrap_before;
			if ($show_home_link) $output .= $home_link;

			if ( is_category() ) {
				$cat = get_category(get_query_var('cat'), false);
				if ($cat->parent != 0) {
					$cats = get_category_parents($cat->parent, TRUE, $sep);
					$cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
					$cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . '>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
					if ($show_home_link) $output .= $sep;
					$output .= $cats;
				}
				if ( get_query_var('paged') ) {
					$cat = $cat->cat_ID;
					$output .= $sep . sprintf($link, get_category_link($cat), get_cat_name($cat)) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current) $output .= $sep . $before . sprintf($text['category'], single_cat_title('', false)) . $after;
				}

			} elseif ( is_search() ) {
				if (have_posts()) {
					if ($show_home_link && $show_current) $output .= $sep;
					if ($show_current) $output .= $before . sprintf($text['search'], get_search_query()) . $after;
				} else {
					if ($show_home_link) $output .= $sep;
					$output .= $before . sprintf($text['search'], get_search_query()) . $after;
				}

			} elseif ( is_day() ) {
				if ($show_home_link) $output .= $sep;
				$output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $sep;
				$output .= sprintf($link, get_month_link(get_the_time('Y'), get_the_time('m')), get_the_time('F'));
				if ($show_current) $output .= $sep . $before . get_the_time('d') . $after;

			} elseif ( is_month() ) {
				if ($show_home_link) $output .= $sep;
				$output .= sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y'));
				if ($show_current) $output .= $sep . $before . get_the_time('F') . $after;

			} elseif ( is_year() ) {
				if ($show_home_link && $show_current) $output .= $sep;
				if ($show_current) $output .= $before . get_the_time('Y') . $after;

			} elseif ( is_single() && !is_attachment() ) {
				if ($show_home_link) $output .= $sep;
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					if ($show_current) $output .=  $before . get_the_title() . $after;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $sep);
					if (!$show_current || get_query_var('cpage')) $cats = preg_replace("#^(.+)$sep$#", "$1", $cats);
					$cats = preg_replace('#<a([^>]+)>([^<]+)<\/a>#', $link_before . '<a$1' . '>' . $link_in_before . '$2' . $link_in_after .'</a>' . $link_after, $cats);
					$output .= $cats;
					if ( get_query_var('cpage') ) {
						$output .= $sep . sprintf($link, get_permalink(), get_the_title()) . $sep . $before . sprintf($text['cpage'], get_query_var('cpage')) . $after;
					} else {
						if ($show_current) $output .= $before . get_the_title() . $after;
					}
				}

			// custom post type
			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				$post_type = get_post_type_object(get_post_type());
				if ( get_query_var('paged') ) {
					$output .= $sep . sprintf($link, get_post_type_archive_link($post_type->name), $post_type->label) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current) $output .= $sep . $before . $post_type->label . $after;
				}

			} elseif ( is_page() && !$parent_id ) {
				if ($show_current) $output .= $sep . $before . get_the_title() . $after;

			} elseif ( is_page() && $parent_id ) {
				if ($show_home_link) $output .= $sep;
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						$output .= $breadcrumbs[$i];
						if ($i != count($breadcrumbs)-1) $output .= $sep;
					}
				}
				if ($show_current) $output .= $sep . $before . get_the_title() . $after;

			} elseif ( is_tag() ) {
				if ( get_query_var('paged') ) {
					$tag_id = get_queried_object_id();
					$tag = get_tag($tag_id);
					$output .= $sep . sprintf($link, get_tag_link($tag_id), $tag->name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_current) $output .= $sep . $before . sprintf($text['tag'], single_tag_title('', false)) . $after;
				}

			} elseif ( is_author() ) {
				global $author;
				$author = get_userdata($author);
				if ( get_query_var('paged') ) {
					if ($show_home_link) $output .= $sep;
					$output .= sprintf($link, get_author_posts_url($author->ID), $author->display_name) . $sep . $before . sprintf($text['page'], get_query_var('paged')) . $after;
				} else {
					if ($show_home_link && $show_current) $output .= $sep;
					if ($show_current) $output .= $before . sprintf($text['author'], $author->display_name) . $after;
				}

			} elseif ( is_404() ) {
				if ($show_home_link && $show_current) $output .= $sep;
				if ($show_current) $output .= $before . $text['404'] . $after;

			} elseif ( has_post_format() && !is_singular() ) {
				if ($show_home_link) $output .= $sep;
				$output .= get_post_format_string( get_post_format() );
			}

			$output .= $wrap_after;

			return $output;

		}

	}
}

$appaiObj = new AppaiMain;
