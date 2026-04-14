<?php
if (post_password_required()) {
	return;
}
?>
<div id="comments" class="comments-area">
	<?php // You can start editing here -- including this comment! ?>
	<?php if (have_comments()) :  ?>
		<div class="comment_top">
			<?php $comments_number = get_comments_number(); ?>
			<h4 class="comments-title">
				<?php
			
					if ('1' === $comments_number) {
						/* translators: %s: post title */
						printf(_x('Comment (1)', 'comments title', 'auriane'));
					}elseif($comments_number === '0')
                    {
                       esc_html_e( 'Join the Conversation', 'auriane' );
                    }
                     else {
						printf(
							/* translators: 1: number of comments, 2: post title */
							_x(
								'Comments (%s)',
								'comments title',
								'auriane'
								),
							number_format_i18n($comments_number)
							);
					}
			
				?>
			</h4>
          
			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
				<nav id="comment-nav-above" class="comment-navigation col-xs-12 col-sm-12 col-md-12 col-lg-12"
				role="navigation">
				<h1 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'auriane'); ?></h1>
				<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'auriane')); ?></div>
				<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'auriane')); ?></div>
			</nav><!-- #comment-nav-above -->
		<?php endif; // check for comment navigation ?>
		<ol class="comment-list">
			<?php
			wp_list_comments(array(
				'style' => 'ol',
				'short_ping' => true,
				'avatar_size' =>60,
				'callback' => 'jws_custom_comment',
				'reply_text' => 'Reply',
				));
				?>
			</ol><!-- .comment-list -->
			<?php if (get_comment_pages_count() > 1 && get_option('page_comments')) : // are there comments to navigate through ?>
				<nav id="comment-nav-below" class="comment-navigation" role="navigation">
					<h1 class="screen-reader-text"><?php esc_html_e('Comment navigation', 'auriane'); ?></h1>
					<div class="nav-previous"><?php previous_comments_link(esc_html__('&larr; Older Comments', 'auriane')); ?></div>
					<div class="nav-next"><?php next_comments_link(esc_html__('Newer Comments &rarr;', 'auriane')); ?></div>
				</nav><!-- #comment-nav-below -->
			<?php endif; // check for comment navigation ?>
		</div>
	<?php endif; // have_comments() ?>
	<?php
    // If comments are closed and there are comments, let's leave a little note, shall we?
	if (!comments_open() && '0' != get_comments_number() && post_type_supports(get_post_type(), 'comments')) :
		?>
	<p class="no-comments"><?php esc_html_e('Comments are closed.', 'auriane'); ?></p>
<?php endif; ?>
<?php
$commenter = wp_get_current_commenter();

$fields = array(
	'author' =>
	'<div class="row"><p class="comment-form-author col-xl-6 col-12"><input id="author" name="author" type="text" value="' . esc_attr($commenter['comment_author']) . '" size="30" aria-required="true" placeholder="' . esc_attr_x('Name *', 'placeholder', 'auriane') . '" /></p>',
	'email' =>
	'<p class="comment-form-email col-xl-6 col-12"><input id="email" name="email"  type="text" value="' . esc_attr($commenter['comment_author_email']) . '" size="30" aria-required="true" placeholder="' . esc_attr_x('Email *', 'placeholder', 'auriane') . '" /></p></div>',
    );
$args = array(
	'id_form' => 'commentform',
	'id_submit' => 'submit',
	'class_submit' => 'submit ',
	'name_submit' => 'submit',
	'title_reply' => esc_html__('Leave A Comment', 'auriane'),
	'title_reply_to' => esc_html__('Leave A Reply  %s', 'auriane'),
	'cancel_reply_link' => esc_html__('Cancel', 'auriane'),
	'label_submit' => esc_html__('Post Comment', 'auriane'),
	'format' => 'xhtml',
	'fields' => apply_filters('comment_form_default_fields', $fields),
	'must_log_in' => '<p class="must-log-in">' .
	sprintf(
		wp_kses('You must be <a href="%s">logged in</a> to post a comment.', 'auriane'),
		wp_login_url(apply_filters('the_permalink', get_permalink()))
		) . '</p>',
	'logged_in_as' => '<p class="logged-in-as">' .
	sprintf(
		wp_kses('Logged in as <a href="%1$s">%2$s</a>. <a href="%3$s" title="' . esc_attr('Log out of this account') . '">Log out?</a>', 'auriane'),
		admin_url('profile.php'),
		$user_identity,
		wp_logout_url(apply_filters('the_permalink', get_permalink()))
		) . '</p>',
	'comment_notes_after' => '',
	'comment_field' => '<p class="comment-form-comment2"><textarea id="comment" name="comment" cols="60" rows="6" aria-required="true" placeholder="' . esc_attr_x('Write your comment...', 'placeholder', 'auriane') . '">' . '</textarea></p>',
	);
comment_form($args);
?>
</div><!-- #comments -->