<?php
/**
 * Display single product reviews (comments)
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product-reviews.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 */

defined( 'ABSPATH' ) || exit;

global $product;

if ( ! comments_open() ) {
	return;
}
$enble_ques_anws = jws_theme_get_option('auestions-enble');
$cc_args = array(
    'posts_per_page'   => jws_theme_get_option('auestions-number'),
    'post_type'        => 'questions',
    'meta_key'         => 'product_questions',
    'meta_value'       => get_the_ID()
);
$cc_query = new WP_Query( $cc_args );
$average_rate  = number_format( $product->get_average_rating(), 1 );
$display_rate  = $average_rate * 20;
$count         = $product->get_review_count();
?>
<div id="reviews" class="woocommerce-Reviews">

    <div class="row review-based">
        	<div class="col-xl-6 col-lg-6 col-12">
				<div class="product-reviews">
					<div class="avg-rating-container">
						<mark><?php echo '' . $average_rate; ?></mark>
						<span class="avg-rating">
							<span class="star-rating">
								<span style="width: <?php echo  esc_attr($display_rate)  . '%'; ?>;">Rated</span>
							</span>
							<span class="ratings-review"><?php echo sprintf( esc_html__( 'Based on %1$s Reviews', 'auriane' ), $count );?></span>
						</span>
					</div>
					<div class="ratings-list">
						<?php
						$ratings_count      = $product->get_rating_counts();
						$total_rating_value = 0;

						foreach ( $ratings_count as $key => $value ) {
							$total_rating_value += intval( $key ) * intval( $value );
						}

						for ( $i = 5; $i > 0; $i-- ) {
							$rating_value = isset( $ratings_count[ $i ] ) ? $ratings_count[ $i ] : 0;
							?>
							<div class="ratings-item" data-rating="<?php echo esc_attr( $i ); ?>">
								<div class="star-rating">
									<span style="width: <?php echo absint( $i ) * 20 . '%'; ?>">Rated</span>
								</div>
								<div class="rating-percent">
									<span style="width: 
									<?php
									if ( ! intval( $rating_value ) == 0 ) {
										echo round( floatval( number_format( ( $rating_value * $i ) / $total_rating_value, 3 ) * 100 ), 1 ) . '%';
									} else {
										echo '0%';
									}
									?>
									;"></span>
								</div>
                                <span class="count">(<?php echo esc_attr($rating_value); ?>)</span>
							</div>
							<?php
						}
						?>
					</div>
				
				</div>
               
			</div>
            <div class="col-xl-6 col-lg-6 col-12">
                <div class="jws_action_review">
                    <span class="active" data-tabs="reviews" href="#"><?php echo esc_html__('Write a review','auriane'); ?></span>
                    <?php if($enble_ques_anws) : ?>
                    <span data-tabs="questions" href="#"><?php echo esc_html__('Ask a question','auriane'); ?></span>
                    <?php endif; ?>
                </div>
            </div>
    </div>
    <?php if($enble_ques_anws) : ?>
    <div id="product-questions">
        <div><?php echo esc_html__('Ask a question','auriane'); ?></div>
        <form action="<?php echo get_permalink( get_the_ID() ); ?>" method="post" class="form-questions" data-product="<?php echo get_the_ID(); ?>" novalidate>
            <div class="row">
                <div class="col-xl-6 col-lg-6 col-12">
                    <label><?php echo esc_html__('Name','auriane') ?>*</label>
                    <input type="text" name="q-name" value="" id="q-name" placeholder="<?php echo esc_attr_x( 'Your Name', 'placeholder', 'auriane' ); ?>" aria-required="true">
                </div>
                <div class="col-xl-6 col-lg-6 col-12">
                    <label><?php echo esc_html__('Email','auriane') ?>*</label>
                    <input type="email" name="q-email" value="" id="q-email" placeholder="<?php echo esc_attr_x( 'Email Address', 'placeholder', 'auriane' ); ?>" aria-required="true">
                </div>
                <div class="col-12">
                    <label><?php echo esc_html__('Question','auriane') ?>*</label>
                    <textarea id="question" name="jws-product-questions" cols="45" rows="8" placeholder="<?php echo esc_attr_x( 'Type your question here...', 'placeholder', 'auriane' ); ?>" aria-required="true"></textarea>
                </div>
                <div class="col-12"><button class="submit" type="submit"><?php echo esc_html__('Submit question','auriane') ?></button></div>
            </div>
        </form>
    </div>
    <?php endif; ?>
		<div id="review_form_wrapper" class="active">
        	<?php if ( get_option( 'woocommerce_review_rating_verification_required' ) === 'no' || wc_customer_bought_product( '', get_current_user_id(), $product->get_id() ) ) : ?>
			<div id="review_form">
				<?php
				$commenter    = wp_get_current_commenter();
				$comment_form = array(
					/* translators: %s is product title */
					'title_reply'         => have_comments() ? esc_html__( 'Write A Review', 'auriane' ) : sprintf( esc_html__( 'Be the first to review &ldquo;%s&rdquo;', 'auriane' ), get_the_title() ),
					/* translators: %s is product title */
					'title_reply_to'      => esc_html__( 'Leave a Reply to %s', 'auriane' ),
					'title_reply_before'  => '<span id="reply-title" class="comment-reply-title">',
					'title_reply_after'   => '</span>',
					'comment_notes_after' => '',
					'label_submit'        => esc_html__( 'Submit review', 'auriane' ),
					'logged_in_as'        => '',
					'comment_field'       => '',
                    'fields'               => array(
						'author' => '<div class="row"><p class="comment-form-author col-xl-6">' .
										'<label>'.esc_html__('Name *','auriane').'</label><input id="author" class="field-simple" name="author" placeholder="'.esc_attr__('Your Name','auriane').'" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" size="30" aria-required="true" /></p>',
						'email'  => '<p class="comment-form-email col-xl-6">' .
										'<label>'.esc_html__('Email *','auriane').'</label><input id="email" class="field-simple" name="email" placeholder="'.esc_attr__('Email Address','auriane').'" type="email" value="' . esc_attr(  $commenter['comment_author_email'] ) . '" size="30" aria-required="true" /></p></div>',
					),
				);

				$name_email_required = (bool) get_option( 'require_name_email', 1 );

	

				$account_page_url = wc_get_page_permalink( 'myaccount' );
				if ( $account_page_url ) {
					/* translators: %s opening and closing link tags respectively */
					$comment_form['must_log_in'] = '<p class="must-log-in">' . sprintf( esc_html__( 'You must be %1$slogged in%2$s to post a review.', 'auriane' ), '<a href="' . esc_url( $account_page_url ) . '">', '</a>' ) . '</p>';
				}

		

				$comment_form['comment_field'] .= '<p class="comment-form-comment"><label>'.esc_html__('Review','auriane').'</label><textarea id="comment" placeholder="'.esc_attr__('Type your comment here...','auriane').'" type="email" name="comment" cols="45" rows="8" required></textarea></p>';

				comment_form( apply_filters( 'woocommerce_product_review_comment_form_args', $comment_form ) );
				?>
			</div>
            <?php else : ?>
		          <p class="woocommerce-verification-required"><?php esc_html_e( 'Only logged in customers who have purchased this product may leave a review.', 'auriane' ); ?></p>
        	<?php endif; ?>
		</div>
	
        <div class="tabs_review_questios">
            <div class="tabs_review_nav">
                <span class="active" data-tabs="reviews"><?php echo sprintf( esc_html__( 'Reviews (%1$s)', 'auriane' ), $count ); ?></span>
                <?php if($enble_ques_anws) : ?>
                <span data-tabs="questions"><?php echo sprintf( esc_html__( 'Questions (%1$s)', 'auriane' ), $cc_query->found_posts );  ?></span>
                <?php endif; ?>
            </div>
        </div>
    	<div id="comments" class="active">
		<h2 class="woocommerce-Reviews-title">
			<?php
			$count = $product->get_review_count();
			if ( $count && wc_review_ratings_enabled() ) {
				/* translators: 1: reviews count 2: product name */
				$reviews_title = sprintf( esc_html( _n( '%1$s review for %2$s', '%1$s reviews for %2$s', $count, 'auriane' ) ), esc_html( $count ), '<span>' . get_the_title() . '</span>' );
				echo apply_filters( 'woocommerce_reviews_title', $reviews_title, $count, $product ); // WPCS: XSS ok.
			} else {
				esc_html_e( 'Reviews', 'auriane' );
			}
			?>
		</h2>

		<?php  if ( have_comments() ) : ?>
			<ol class="commentlist ct_ul_ol">
				<?php wp_list_comments( apply_filters( 'woocommerce_product_review_list_args', array( 'callback' => 'woocommerce_comments' ) ) ); ?>
			</ol>

			<?php
			if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) :
				echo '<nav class="jws-pagination-number">';
				paginate_comments_links(
					apply_filters(
						'woocommerce_comment_pagination_args',
						array(
							'prev_text' => '<i class="jws-icon-expand_right_double"></i>',
							'next_text' => '<i class="jws-icon-expand_right_double"></i>',
							'type'      => 'list',
						)
					)
				);
				echo '</nav>';
			endif;
			?>
		<?php else : ?>
			<div class="woocommerce-noreviews"><?php esc_html_e( 'There are no reviews yet.', 'auriane' ); ?></div>
		<?php endif; ?>
	</div>
    <?php if($enble_ques_anws) : ?>
    <div id="answer-question-list" data_question_url="<?php echo get_post_type_archive_link( 'questions' ); ?>" data_product_id="<?php echo get_the_ID(); ?>">
        <?php 
            if ( $cc_query->have_posts() ) {
                echo '<ul>';
                while ( $cc_query->have_posts() ) {
                    $cc_query->the_post();
                    
                    ?>    
                      <li>
                        <div class="q-name">
                           <?php echo  get_post_meta( get_the_ID(), 'product_name', true ); ?>
                        </div>
                        <span class="q-question">Q: <?php echo get_the_title(); ?></span>
                        <span class="q-date"><?php echo get_the_date(); ?></span>
                        <div class="answer_content">
                            <?php
                                $answer_content = get_post_meta( get_the_ID(), 'answer_content', true );
                                if($answer_content) {
                                      $author_name  =  get_the_author_meta( 'display_name' );
                                      echo '<div>'.$author_name.'</div>';
                                  echo '<p>A: '.$answer_content.'</p>';
                                }else{
                                  echo esc_html__('There are no answers for this question yet.','auriane');  
                                }
                             ?>
                        </div>
                      </li>
                    
                    <?php
                }
                echo '</ul>';
               
            } else {
                echo esc_html__('There are no question yet.','auriane');
            }
            echo jws_query_pagination($cc_query);
            /* Restore original Post Data */
            wp_reset_postdata();
        ?>
    </div>
    <?php endif; ?>
	<div class="clear"></div>
</div>