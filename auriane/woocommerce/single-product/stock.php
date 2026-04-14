<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

?>
<p class="stock <?php echo esc_attr( $class ); ?>">
	<?php  
   
        $stock = $product->get_stock_quantity();

    	if ( ! $product->managing_stock() && ! $product->is_in_stock() ){
    		echo wp_kses_post( $availability ); 
            jws_wc_waitlist_form();
		
    	} elseif( $product->is_in_stock() && ! $product->managing_stock()){
    		echo wp_kses_post( $availability ); 
		
           jws_send_mail_waitlist(get_the_ID());
    	}else{
    		if($stock == 0){
    			echo wp_kses_post( $availability ); 
                jws_wc_waitlist_form();
    		}else{
		
    			echo wp_kses_post( $availability ); 
                jws_send_mail_waitlist(get_the_ID());
    		}
    	}  
    
	?>
</p> 