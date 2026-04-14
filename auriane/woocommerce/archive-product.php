    <?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates 
 */

defined( 'ABSPATH' ) || exit;

$shop = jws_check_layout_shop();


get_header( 'shop' );

?>
<div class="shop-page">
<div class="<?php echo esc_attr($shop['class_wap']); ?>">
    <div class="row">
         <?php if($shop['position'] == 'left' || $shop['filter_layout'] == 'sideout') { ?>
            <div class="<?php echo esc_attr($shop['sidebar_col']); ?>">
                <div class="jws_sticky_move">
                <div class="jws-filter-modal">
                    <div class="modal-overlay"></div>
                    <div class="siderbar-inner jws-scrollbar modal-content sidebar left">
                    <div class="modal-top">
                        <span class="modal-title"><?php echo esc_html__('FILTERS','auriane'); ?></span>
                        <span class="modal-close"><?php echo esc_html__('Close','auriane'); ?></span>
                    </div>
                        <?php
                            if ( is_active_sidebar( 'sidebar-shop' ) ) {
                                    dynamic_sidebar( 'sidebar-shop' );
                            } 
                        ?>
                    </div>
                    
                    <?php
                        if ( is_active_sidebar( 'sidebar-shop-banner' ) ) {
                            echo '<div class="shop-banner">';
                                dynamic_sidebar( 'sidebar-shop-banner' );
                            echo '</div>';    
                        } 
                    ?>
                   
                </div>
                </div>
            </div>
        <?php } ?>
        <div class="<?php echo esc_attr($shop['content_col']);  ?>">
        <?php
        shop_banner_content();
        /**
         * Hook: woocommerce_before_main_content.
         *
         * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
         * @hooked WC_Structured_Data::generate_website_data() - 30
         */
        do_action( 'woocommerce_before_main_content' );
    	/**
    	 * Hook: woocommerce_before_shop_loop.
    	 *
    	 * @hooked woocommerce_output_all_notices - 10
    	 * @hooked woocommerce_result_count - 20
    	 * @hooked woocommerce_catalog_ordering - 30
    	 */
        do_action( 'woocommerce_before_shop_loop' );
        
        if($shop['filter_layout'] == 'bottom' || $shop['filter_layout'] == 'top') { 
          shop_filter_content();   
        }
        
        if ( woocommerce_product_loop() ) {

        
        	woocommerce_product_loop_start();
            
   
        	if ( wc_get_loop_prop( 'total' ) ) {
        		while ( have_posts() ) {
        			the_post();
            
        			/**
        			 * Hook: woocommerce_shop_loop.
        			 */
        			do_action( 'woocommerce_shop_loop' );
        
        			wc_get_template_part( 'content', 'product' );
        		}
        	}
        
        	woocommerce_product_loop_end();
        
        	/**
        	 * Hook: woocommerce_after_shop_loop.
        	 *
        	 * @hooked woocommerce_pagination - 10
        	 */
        	do_action( 'woocommerce_after_shop_loop' );
        } else {
        	/**
        	 * Hook: woocommerce_no_products_found.
        	 *
        	 * @hooked wc_no_products_found - 10
        	 */
        	do_action( 'woocommerce_no_products_found' );
        }
        
        /**
         * Hook: woocommerce_after_main_content.
         *
         * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
         */
        do_action( 'woocommerce_after_main_content' );
        ?>
        </div>
        <?php if($shop['position'] == 'right') { ?>
              <div class="<?php echo esc_attr($shop['sidebar_col']); ?>">
              <div class="jws_sticky_move">
                <div class="jws-filter-modal">
                    <div class="modal-overlay"></div>
                    <div class="siderbar-inner modal-content sidebar jws-scrollbar right">
                    <div class="modal-top">
                        <span class="modal-title"><?php echo esc_html__('FILTERS','auriane'); ?></span>
                        <span class="modal-close"><?php echo esc_html__('Close','auriane'); ?></span>
                    </div>
                        <?php
                            if ( is_active_sidebar( 'sidebar-shop' ) ) {
                                    dynamic_sidebar( 'sidebar-shop' );
                            } 
                        ?>
                    </div>
                    
                    <?php
                        if ( is_active_sidebar( 'sidebar-shop-banner' ) ) {
                            echo '<div class="shop-banner">';
                                dynamic_sidebar( 'sidebar-shop-banner' );
                            echo '</div>';    
                        } 
                    ?>
                  </div> 
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<?php if($shop['filter_layout'] == 'modal') : ?>
    <div class="jws-filter-modal">
        <div class="modal-overlay"></div>
        <div class="modal-content siderbar-inner jws-scrollbar">
            <div class="modal-top">
                <span class="modal-title"><?php echo esc_html__('FILTERS','auriane'); ?></span>
                <span class="modal-close"><?php echo esc_html__('Close','auriane'); ?></span>
            </div>
            <?php 
              if ( is_active_sidebar( 'sidebar-shop-filter-modal' ) ) {
                   dynamic_sidebar( 'sidebar-shop-filter-modal' );
              } 
            ?>
        </div>
    </div>
<?php endif; ?>
</div>
<?php   
get_footer( 'shop' );