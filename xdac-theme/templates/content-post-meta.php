<?php
     // If the post is sticky
    if( is_sticky() ) {
        echo '<span class="featured-post"><i class="icofont-tack-pin"></i> '. esc_html__('Sticky - ', 'appai') .'</span>';
    }
?>

<?php
    // Get the category list
    $categories_list = get_the_category_list( esc_html__( ', ', 'appai' ) );
    if ( $categories_list ) {
        echo '<span class="appai-meta-title">'. esc_html__('In: ', 'appai') .'</span>';
        echo '<span class="categories-links">'. $categories_list .' - </span>';
    }
?>

<?php
    // Get the tag list
    $tag_list = get_the_tag_list( '', esc_html__( ', ', 'appai' ) );
    if ( $tag_list ) {
        echo '<span class="appai-meta-title">'. esc_html__('Tags: ', 'appai') .'</span>';
        echo '<span class="tags-links">'. $tag_list .' - </span>';
    }

?>


<span class="comments-quantity">
    <?php
    comments_popup_link(
        esc_html__('No comments', 'appai'),
        esc_html__('1 Comments', 'appai'),
        esc_html__('% comments', 'appai')
    ); ?>
    -
</span>

<span class="post-date">
    <a href="<?php echo esc_url( the_permalink() ); ?>">
        <?php esc_html( the_time( get_option('date_format') ) ); ?>
    </a>
    -
</span>


<span class="posted-by">
    <span class="appai-meta-title">
        <?php esc_html_e('By: ', 'appai'); ?>
    </span>
    <?php esc_url( the_author_posts_link() ); ?>
    -
</span>


<?php
    edit_post_link(
        /* translators: %s: Name of current post */
        esc_html__( ' Edit', 'appai' ),
        '<span>',
        '</span>'
    );
?>
