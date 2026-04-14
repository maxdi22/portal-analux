
<?php
/**
 *    jws: Quick view product content
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//jws_quick_view_vg_data(true);
global $post, $product;




// Main wrapper class
$class = 'product main-product' . ' product-quick-view single-product-content product-' . $product->get_type();

?>
<div class="shop-single">
<div id="product-<?php the_ID(); ?>" <?php post_class($class); ?>>
    <div class="row">
        <div class="col-xl-6 col-lg-6 col-12">
            <?php include( 'product-image.php' ); ?>
        </div>

        <div class="jws-summary col-xl-6 col-lg-6 col-12">
            <div class="summary entry-summary quickview-summary jws-scrollbar">
                <?php
                /**
                 * woocommerce_single_product_summary hook
                 *
                 * @hooked jws_qv_product_summary_open - 1
                 * @hooked woocommerce_template_single_title - 5
                 * @hooked woocommerce_template_single_price - 10
                 * @hooked jws_qv_product_summary_divider - 15
                 * @hooked woocommerce_template_single_excerpt - 20
                 * @hooked woocommerce_template_single_rating - 21
                 * @hooked woocommerce_template_single_add_to_cart - 30
                 * @hooked jws_qv_product_summary_actions - 30
                 * @hooked woocommerce_template_single_sharing - 50
                 * @hooked jws_qv_product_summary_close - 100
                 */
                do_action('woocommerce_single_product_summary');
                ?>
            </div>
        </div>
    </div>
</div>
</div>
