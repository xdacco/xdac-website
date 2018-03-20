<li <?php esc_attr( post_class() ); ?> >

    <?php
        global $appaiObj;
        $post_link = get_the_permalink();

        if( has_post_thumbnail() ) :
    ?>

        <div class="post-thumbnail">
            <a href="<?php echo esc_url( $post_link ); ?>">
                <?php the_post_thumbnail('appai-post-img-large'); ?>
            </a>
        </div>

    <?php endif; ?>

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

            <p class="post-excerpt">
                <?php echo $appaiObj->postExcerpt(20, get_the_excerpt() ); ?>
            </p>

        </div>
        <div class="read-more-wrapper">
            <a href="<?php echo esc_url( $post_link ); ?>" class="read-more-btn">

            <?php esc_html_e('Read More', 'appai'); ?><i class="icofont icofont-long-arrow-right"></i></a>
        </div>
    </div>

</li>
