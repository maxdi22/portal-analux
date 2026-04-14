<?php 
    global $jws_option; 
    $meta_layout = get_post_meta( get_the_ID(), 'shop_single_layout', true );
    $layout = ($meta_layout) ? $meta_layout : ( $jws_option['shop_single_layout'] ? $jws_option['shop_single_layout'] : 'default' );
?>

<div class="row">
        <div class="col-xl-60 col-lg-6 col-12">
        	<?php
            	/**
            	 * Hook: woocommerce_before_single_product_summary.
            	 *
            	 * @hooked woocommerce_show_product_sale_flash - 10
            	 * @hooked woocommerce_show_product_images - 20
            	 */
            	do_action( 'woocommerce_before_single_product_summary' );
        	?>
        </div>
        <div class="col-xl-40 col-lg-6 col-12 summary_right">
        	<div class="summary entry-summary jws_sticky_move">
                <?php if($jws_option['product-single-breadcrumb']) echo '<div class="breadcrumb">'.jws_page_breadcrumb('/').'</div>'; ?>
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
                <div class="product-tabs">
                    <?php woocommerce_output_product_data_tabs(); ?>
                </div>
        	</div>    
        </div>
    </div>
    <?php if ( comments_open() ) : ?>
    <div class="comment-section">
        <div class="container">
            <h4 class="comments-heading"><?php echo esc_html__('Customer Reviews','auriane'); ?></h4>
            <?php call_user_func( 'comments_template', 999 ); ?>
        </div>
    </div>
    <?php endif; ?>
    <div class="container">
    	<?php
        
    	/**
    	 * Hook: woocommerce_after_single_product_summary.
    	 *
    	 * @hooked woocommerce_output_product_data_tabs - 10
    	 * @hooked woocommerce_upsell_display - 15
    	 * @hooked woocommerce_output_related_products - 20
    	 */
    	do_action( 'woocommerce_after_single_product_summary' );
        ?>
    </div>
    <?php 
    if ( is_active_sidebar( 'sidebar-single-shop-popup' ) ) { ?>
        <a class="product-search-single toggle-this" href="javascript:void(0)">
            <i class="jws-icon-icon_search2"></i>
            <span><span><?php echo esc_html__('Find perfect fit','auriane'); ?></span></span>
        </a>
        <div class="single-form-popp">
        <div class="overlay toggle-this"></div>
        <div class="widget-inner">
        <span class="jws-icon-icon_close close-search-popup toggle-this"></span>
        <?php dynamic_sidebar( 'sidebar-single-shop-popup' ); ?>
        </div>
        </div>   
    <?php } 