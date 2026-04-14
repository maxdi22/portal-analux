  <div class="product-item-inner grid">
    <div class="product-image-wap">
    <div class="product-image">
        <?php 
            /**
        	 * Hook: woocommerce_before_shop_loop_item.
        	 *
        	 * @hooked woocommerce_template_loop_product_link_open - 10
        	 */
        	do_action( 'woocommerce_before_shop_loop_item' );
        
   
            $size = isset($image_size) ? $image_size : 'woocommerce_thumbnail';
            echo woocommerce_get_product_thumbnail($size);
            
        	/**
        	 * Hook: woocommerce_after_shop_loop_item.
        	 *
        	 * @hooked woocommerce_template_loop_product_link_close - 5
        	 * @hooked woocommerce_template_loop_add_to_cart - 10
        	 */
        	do_action( 'woocommerce_after_shop_loop_item' );
            ?>
    </div>
    </div>
    <div class="product-content">
	<?php
        /**
    	 * Hook: woocommerce_shop_loop_item_title.
    	 *
    	 * @hooked woocommerce_template_loop_product_title - 10
    	*/
        jws_add_to_wishlist_btn(); 
    	do_action( 'woocommerce_shop_loop_item_title' );
        echo '<div class="jws-attr">';
        jws_short_text_after_title('',false);
        echo '</div>';
        woocommerce_template_loop_price();
        woocommerce_template_loop_rating(); ?>
    </div>  
</div>  