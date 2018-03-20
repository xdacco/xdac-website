<?php

    $appai = get_option('appai');

?>

<article <?php echo post_class(); ?>>
    <?php
        $post_link = get_the_permalink();
    ?>
    <?php if( has_post_thumbnail() ) : ?>

        <div class="post-thumbnail">
            <?php the_post_thumbnail('appai-post-img-large'); ?>
        </div>

    <?php  endif; ?>

    <div class="post-content">

        <div class="post-content-inner">

            <?php
                //
                // The post mark up will be difference and dependend on
                // the post thumbnail
                //
                if( has_post_thumbnail() ) : ?>
                <div class="blog-post-meta">
                    <?php get_template_part('templates/content', 'post-meta'); ?>
                </div>

                <h3 class="post-title">
                    <a href="<?php echo esc_url( $post_link ); ?>">
                        <?php esc_html(  the_title() ); ?>
                    </a>
                </h3>

            <?php else: ?>

                <h3 class="post-title">
                    <a href="<?php echo esc_url( $post_link ); ?>">
                        <?php esc_html(  the_title() ); ?>
                    </a>
                </h3>

                <div class="blog-post-meta">
                    <?php get_template_part('templates/content', 'post-meta'); ?>
                </div>

            <?php endif; ?>

            <div class="post-the-content">

                <?php

                   the_content();

                    // Show pagination if split the post into pages
                    esc_url( appai_wp_link_pages() );

                ?>
            </div>
        </div>



                <?php
                    // Get the tag list
                    $tag_list = get_the_tag_list( '', esc_html__( ', ', 'appai' ) );
                    if ( $tag_list ) {
                        echo '<div class="share-post-wrapper clearfix">';
                        echo '<div class="post-tags">';
                            echo '<span class="tags-links">' . esc_html__(' Tags: ', 'appai') . $tag_list . '</span>';
                        echo '</div>';
                        echo '</div>';
                    }
                ?>


    </div>
</article>


    <div class="prev-next-posts row">
        <div class="col-md-6 text-left">
            <div class="inner-col ">
                <?php esc_url( previous_post_link( "<i class='icofont-long-arrow-left'></i> %link", esc_html__("Previous", 'appai') ) ); ?>
            </div>
        </div>
        <div class="col-md-6 text-right">
            <div class="inner-col">
                <?php esc_url( next_post_link( "%link <i class='icofont-long-arrow-right'></i>", esc_html__("Next", 'appai') ) ); ?>
            </div>
        </div>
    </div>

    <?php
        // If comments is open
        comments_template();
    ?>
