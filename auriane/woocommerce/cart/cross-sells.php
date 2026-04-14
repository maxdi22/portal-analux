<?php
/**
 * Cross-sells
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cross-sells.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates 
 */

defined( 'ABSPATH' ) || exit;

if ( $cross_sells ) : ?>

	<div class="cross-sells">
		<?php
		$heading = apply_filters( 'woocommerce_product_cross_sells_products_heading', __( 'You may be interested in&hellip;', 'auriane' ) );

		if ( $heading ) :
			?>
			<h5><?php echo esc_html( $heading ); ?></h5>
		<?php endif; ?>
        <div class="cross-sells-inner">
            <div class="row cross-sells-slider" data-slick='{"slidesToShow":1 ,"slidesToScroll": 1, "infinite" : true, "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": 1}},{"breakpoint": 767,"settings":{"slidesToShow": 1}},{"breakpoint": 480,"settings":{"slidesToShow": 1}}]}'>
        		<?php foreach ( $cross_sells as $cross_sell ) : ?>
                    <div class="product-item product slick-slide col-12">
                                <?php 
                                    $post_object = get_post( $cross_sell->get_id() );
        
                					setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found
                
                					wc_get_template_part( 'archive-layout/content-cross' );
                                ?>
                    </div>
        		<?php endforeach; ?>
            </div>
        </div>
	</div>
	<?php
endif;

wp_reset_postdata();