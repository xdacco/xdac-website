<?php
/**
 * Template for displaying search forms in appai
 *
 * @package WordPress
 * @subpackage appai
 * @since appai 1.0
 */
?>

<section class="appai-search-form">
     <form role="search" method="get" id="search-form" action="<?php echo esc_url( home_url( '/' ) ); ?>" >
    	<label class="screen-reader-text" for="s"><?php esc_html_e('Search',  'appai') ?></label>
     	<input type="search" value="<?php echo esc_attr( get_search_query() ); ?>" name="s" id="s" placeholder="<?php esc_attr_e('Type to search here...', 'appai'); ?>" />
     	<button type="submit" id="searchsubmit">
			<span class="screen-reader-text"><?php esc_html_e('Search',  'appai') ?></span>
     		<i class="icofont icofont-search"></i>
     	</button>
     </form>
</section>
