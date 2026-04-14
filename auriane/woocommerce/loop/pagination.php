<?php
/**
 * Pagination - Show numbered pagination for catalog pages
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/pagination.php.
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

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$shop = jws_check_layout_shop();
$total   = isset( $total ) ? $total : wc_get_loop_prop( 'total_pages' );
$current = isset( $current ) ? $current : wc_get_loop_prop( 'current_page' );
$base    = isset( $base ) ? $base : esc_url_raw( str_replace( 999999999, '%#%', remove_query_arg( 'add-to-cart', get_pagenum_link( 999999999, false ) ) ) );
$format  = isset( $format ) ? $format : '';

?>
<nav class="woocommerce-pagination">
<?php
 global $wp_query;
 if($shop['shop_pagination_layout'] == 'number') {
    
    echo jws_query_pagination($wp_query); 
    
 }else {
    
    if (get_next_posts_link()) { ?>
            <a href="<?php echo next_posts($wp_query->max_num_pages, false); ?>" class="btn <?php  if($shop['shop_pagination_layout'] == 'loadmore') : echo esc_attr('click_load_more'); else: echo esc_attr('auto_load_more'); endif; ?> jws-products-load-more">
               <i></i>
               <span class="before_ajax"><?php esc_html_e('Load More', 'auriane'); ?></span>
            </a>
            <?php
                if($shop['shop_pagination_layout'] == 'infinity') { ?>
                   <div class="spinner">
                        <?php 
                            for ($i = 1; $i <= 8; $i++) {
                                echo '<div class="spinner-blade"></div>';
                            }
                        ?>
                   </div> 
                <?php }
             ?>
    <?php }else {
        ?>
            <span class="jws-product-loaded"><?php jws_woo_found(); ?></span>
        <?php
    }
    
 }
?> </nav>