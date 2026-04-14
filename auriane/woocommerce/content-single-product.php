<?php
/**
 * The template for displaying product content in the single-product.php template
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product,$jws_option;

// Get page options
$meta_layout = get_post_meta( get_the_ID(), 'shop_single_layout', true );
$meta_position = get_post_meta( get_the_ID(), 'shop_single_thumbnail_position', true );

$layout = ($meta_layout) ? $meta_layout : ( jws_theme_get_option('shop_single_layout') ? jws_theme_get_option('shop_single_layout') : 'default' );
$postion = ($meta_position) ? $meta_position : ( jws_theme_get_option('shop_single_thumbnail_position') ? jws_theme_get_option('shop_single_thumbnail_position') : 'left' );
         
if($postion == 'left' || $postion == 'right') {
    $flex = 'true';
}else {
   $flex = 'false'; 
}

$class = 'main-product';
if($layout == 'default') {
$class .= ' thumbnail_position_'.$postion.''; /* themeoption */
$class .=  ' thumbnail_flex_'.$flex.''; /* themeoption */ 
$class .=  ' has-owl-carousel'; /* themeoption */  
}
if($layout == 'vertical' || $layout == 'vertical_metro') {
    $class .=  ' resize-slider-mobile'; /* themeoption */    
}

$class .= ' layout_'.$layout.''; /* themeoption */           
           

if ( post_password_required() ) {
	echo get_the_password_form(); // WPCS: XSS ok.
	return;
}
/**
 * Hook: woocommerce_before_single_product.
 *
 * @hooked woocommerce_output_all_notices - 10
 */
do_action( 'woocommerce_before_single_product' );
?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
    <div class="<?php echo esc_attr($class); ?>">
        <?php wc_get_template( 'single-product/layout/'.$layout.'.php'); ?>
    </div>
</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>