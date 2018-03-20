<article>

    <?php if( has_post_thumbnail() ) : ?>

        <div class="post-thumbnail">
            <a href="<?php echo esc_url( $post_link ); ?>">
                <?php the_post_thumbnail('maxive-post-img-large'); ?>
            </a>
        </div>

    <?php  endif; ?>

    <div class="post-content">
        <div class="post-inner-content">

            <?php

               the_content();

            ?>


        </div>
    </div>
</article>


    <?php
        comments_template();
    ?>
