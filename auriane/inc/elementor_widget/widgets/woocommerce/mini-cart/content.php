<div class="jws_mini_cart">
    <div class="jws-cart-nav">
        <a href="<?php echo wc_get_cart_url(); ?>">
             <span class="cart_icon">
                  <?php  
                  if ( isset($settings['icon']) && !empty($settings['icon']['value']) ) {
						\Elementor\Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
					} else{ ?>
					    <i class="jws-icon-handbag-light-1" aria-hidden="true"></i>   
				  <?php }  
                  if($settings['show_count'] == 'yes') : ?>
                 <span class="jws_cart_count"><?php echo jws_get_cart_contents_count(); ?></span>
                 <?php endif; ?>
             </span> 
             <?php if($settings['show_price'] == 'yes') : ?> 
             <span class="jws_price_total">(<?php if(function_exists('jws_get_cart_contents_total')) echo jws_get_cart_contents_total(); ?>)
             </span>  
             <?php endif; ?>
            
        </a>
    </div>
</div>
