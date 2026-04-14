<?php
/**
 * The template for displaying product widget entries.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-widget-product.php.
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.5
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! is_a( $product, 'WC_Product' ) ) {
	return;
}

?>
<li class="product-item product">
    <div class="product-item-inner">
        <div class="product-image">
           <?php echo ''.$product->get_image(); ?>
        </div>
        <div class="product-content">
            <div class="woocommerce-loop-product__title">
                <a href="<?php echo esc_url( $product->get_permalink() ); ?>">
            	   <?php echo wp_kses_post( $product->get_name() ); ?>
            	</a>
                <?php jws_add_to_wishlist_btn(); ?>
            </div>
            <div class="jws-attr"><?php jws_short_text_after_title('',false); ?></div>
            <span class="price-item">
                <span class="price"><?php echo ''.$product->get_price_html(); ?></span>
            </span>
        </div>
    </div>
</li>
