<?php

// File Security Check
if ( ! defined( 'ABSPATH' ) ) {
   exit;
}

/**
 * The template for displaying comments
 *
 * The area of the page that contains both current comments
 * and the comment form.
 *
 */
if ( post_password_required() ) {
	return;
}
?>

<div id="comments" class="comments-area">
	<?php if( have_comments() ) : ?>

		<h3 class="comments-title">
			<?php esc_html_e('COMMENTS ON THIS POST', 'appai'); ?> <br>
			<span class="comments-number">

			<?php
				comments_popup_link(
			        esc_html__('No comments', 'appai'),
			        esc_html__('1 Comments', 'appai'),
			        esc_html__('% comments', 'appai')
			    )
			?>

			</span>
		</h3>

		<?php the_comments_navigation(); ?>

		<ol class="comment-list">
			<?php
				wp_list_comments( array(
					'style'       => 'ol',
					'short_ping'  => true,
					'avatar_size' => 42,
					'callback'	=> 'appai_comments_list'
				) );
			?>
		</ol><!-- .comment-list -->

	<?php endif; // check for the have comments  ?>

	<?php
		// If comments are closed and there are comments, let's leave a little note, shall we?
		if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
	?>
		<p class="no-comments"><?php esc_html_e( 'Comments are closed.', 'appai' ); ?></p>
	<?php endif; ?>


</div>

<div class="comments-form-area">
	<?php
		comment_form( array(
			'title_reply_before' => '<h5 id="reply-title" class="comment-reply-title">',
			'title_reply_after'  => '</h5>',
		) );
	?>
</div>
