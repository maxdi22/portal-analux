
<?php
/**
 *    jws: Quick view product content
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

//jws_quick_view_vg_data(true);
global $post, $product;

$attachment_ids = $product->get_gallery_image_ids();
$attachment_count = count( $attachment_ids );
$slider_disabled_class = (count($attachment_ids) == 2) ? ' jws-carousel-disabled' : ' slick-slider slick-arrows-small';


// Main wrapper class
$class = 'product main-product' . ' product-quick-view single-product-content product-' . $product->get_type();

?>
<div class="shop-single shopvideo_popup">
<div id="product-<?php the_ID(); ?>" <?php post_class($class); ?>>
    <div class="row">
        <div class="jws-videos col-xl-6 col-lg-6 col-12">
            <?php include( 'product-video.php' ); ?>
            <div class="product-action">
                <div class="jws-products-banner-item">
                  <div class="product-item">
                  <?php wc_get_template_part( 'archive-layout/content-layout5');?>
                  </div>  
                   <a href="javascript:void(0)" data-product_id="<?php echo get_the_ID(); ?>" class="quickview-button"><?php echo esc_html__('Show now','auriane')?></a>
                </div>
            </div>
        </div>

        <div class="jws-summary col-xl-6 col-lg-6 col-12">
        
            <div class="summary entry-summary quickview-summary jws-scrollbar">
              <div class="woo-variation-gallery-slider-wrapper">
                
                <div id="jws-quickview-video-slider"
                     class="woocommerce-product-gallery__wrapper quick-view-gallery product-images <?php echo esc_attr($slider_disabled_class); ?>">
                     <?php
            			$attributes = array(
            				'title' => esc_attr( get_the_title( get_post_thumbnail_id() ) )
            			);
            
            			if ( has_post_thumbnail() ) {
            
            				echo '<figure class="woocommerce-product-gallery__image quick-view-gallery">' . get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ), $attributes ) . '</figure>';
            
            
            				if ( $attachment_count > 0 ) {
            					foreach ( $attachment_ids as $attachment_id ) {
            						echo '<div class="product-image-wrap"><figure class="woocommerce-product-gallery__image quick-view-gallery">' . wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'woocommerce_single' ) ) . '</figure></div>';
            					}
            				}
            
            			} else {
            
            				echo '<figure class="woocommerce-product-gallery__image--placeholder">' . apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), esc_html__( 'Placeholder', 'auriane' ) ), $post->ID ) . '</figure>';
            
            			}
            
            		?>
                 </div>
               </div>   
                <?php jws_product_label(); ?>
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
                <a href="<?php echo get_the_permalink(); ?>" class="button"><?php echo esc_html__('View Detail','auriane');?></a>
            </div>
        </div>
    </div>
</div>
</div>
