  <div class="product-item-inner deal">

	<?php

	$countdown_time = get_post_meta( get_the_ID(), '_sale_price_dates_to', true );
    if(!empty($countdown_time)) { ?>
       <div class="jws-sale-time" data-d="<?php echo esc_attr__('DAYS','auriane'); ?>" data-h="<?php echo esc_attr__('HRS','auriane'); ?>" data-m="<?php echo esc_attr__('MIN','auriane'); ?>" data-s="<?php echo esc_attr__('SEC','auriane'); ?>" data-countdown="<?php echo esc_attr($countdown_time); ?>"></div> 
    <?php }?>
    <?php
    
      do_action( 'woocommerce_shop_loop_item_title' );
      woocommerce_template_loop_price();
       
      global $product;
      $units_sold = get_post_meta( $product->get_id(), 'total_sales', true );
      $total = $product->get_stock_quantity();
      
      if(!empty($units_sold) && !empty($total)) {
        $result = ($units_sold / $total) * 100;
        echo '<div class="progress-bar-sold">
                <p class="line"><span style="width:'.$result.'%"></span></p>
                <span class="sold_count"><span>'.esc_html__('Sold:','auriane').'</span><strong>'.$units_sold.'</strong></span>
                <span class="available_items"><span>'.esc_html__('Available:','auriane').'</span><strong>'.$total.'</strong></span>
             </div>';
      }  
    ?>
    <div class="product-short-description">
        <?php the_excerpt(); ?>
    </div>
   <a href="?add-to-cart=<?php echo get_the_ID(); ?>" data-quantity="1" class="btn-main elementor-button button product_type_simple add_to_cart_button ajax_add_to_cart" data-product_id="<?php echo get_the_ID(); ?>" data-product_sku=""  rel="nofollow" tabindex="-1"><span class="text">
    <?php 
        echo esc_html__('Add to cart','auriane');
        if( $product->is_on_sale() ) {
            echo wc_price($product->get_sale_price());
        }
    ?>
   </span></a>
  </div>  