<div class="product-item-inner">
    <div class="product-image">
       
        <?php 
            /**
        	 * Hook: woocommerce_before_shop_loop_item.
        	 *
        	 * @hooked woocommerce_template_loop_product_link_open - 10
        	 */
        	do_action( 'woocommerce_before_shop_loop_item' );
        
        	/**
        	 * Hook: woocommerce_before_shop_loop_item_title.
        	 *
        	 * @hooked woocommerce_show_product_loop_sale_flash - 10
        	 * @hooked woocommerce_template_loop_product_thumbnail - 10
        	 */
        	do_action( 'woocommerce_before_shop_loop_item_title' );
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
    <div class="product-content">
    	<?php
            do_action( 'woocommerce_shop_loop_item_title' );
            echo '<div class="jws-attr">';
            product_attribute();
            jws_short_text_after_title('',false);
            echo '</div>';
            
    	?>
    </div>
    <div class="cross-button">
        <?php woocommerce_template_loop_price(); ?>
        <div class="product-buy">
            <?php woocommerce_template_loop_add_to_cart(); ?>
        </div>
    </div> 
</div>  