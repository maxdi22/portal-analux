<?php
/**
 * Review Comments Template
 *
 * Closing li is left out on purpose!.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/review.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}
?>
<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">

	<div id="comment-<?php comment_ID(); ?>" class="comment_container">
        <div class="user-avatar">
            <?php
    		/**
    		 * The woocommerce_review_before hook
    		 *
    		 * @hooked woocommerce_review_display_gravatar - 10
    		 */
            echo '<div class="avatar-left">'; 
            $verified = wc_review_is_from_verified_owner( $comment->comment_ID );
            if(strpos(get_avatar_url( get_the_author_meta( 'ID' ) ), 'secure') !== false) {
               $string = $comment->comment_author;
               echo '<span class="avatar-text">'.$string[0].'</span>';     
            }else {
              echo get_avatar( $comment, apply_filters( 'woocommerce_review_gravatar_size', '60' ), '' );   
            }
    		
            if($verified) {
                echo '<span class="jws-icon-check verified"></span>';
            }
            echo '</div>';
            echo '<div class="avatar-right">';
            /**
			 * The woocommerce_review_before_comment_meta hook.
			 *
			 * @hooked woocommerce_review_display_rating - 10
			 */
			do_action( 'woocommerce_review_before_comment_meta', $comment );

			/**
			 * The woocommerce_review_meta hook.
			 *
			 * @hooked woocommerce_review_display_meta - 10
			 */
			do_action( 'woocommerce_review_meta', $comment );
            
            
            echo '<span class="date-comment">'.get_comment_date().'</span>';
            echo '</div>';
    		?>
        </div>
	

		<div class="comment-text">

			<?php $review_title = get_comment_meta($comment->comment_ID, 'title_comment', true)?get_comment_meta($comment->comment_ID, 'title_comment', true):''; ?>
            <h5 class="comment-title"><?php echo esc_html($review_title);?></h5>
            <?php
			do_action( 'woocommerce_review_before_comment_text', $comment );

			/**
			 * The woocommerce_review_comment_text hook
			 *
			 * @hooked woocommerce_review_display_comment_text - 10
			 */
			do_action( 'woocommerce_review_comment_text', $comment );

			do_action( 'woocommerce_review_after_comment_text', $comment );
			?>

		</div>
	</div>