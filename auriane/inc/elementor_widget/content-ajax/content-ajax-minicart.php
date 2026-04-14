<?php function render_menu_cart() {

$jws_cart_empty_class_attr = ( WC()->cart->is_empty() ) ? ' jws-cart-panel-empty' : ' jws-has-product';
$count = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : '0'; 
?>
<div class="jws_cart_content cart-panel-content <?php echo esc_attr($jws_cart_empty_class_attr); ?>">
<div class="cart-head">
    <label><?php echo esc_html__('Your Cart','auriane'); ?><span class="count"><?php echo esc_attr($count); ?></span></label>
    <span class="cart-close"><i aria-hidden="true" class="jws-icon-cross"></i></span>
</div>
<div class="jws-cart-panel jws-scrollbar">
<div class="jws-cart-panel-list-wrap">
<ul class="cart_list">
    <?php if ( ! WC()->cart->is_empty() ) : ?>

        <?php
            do_action( 'woocommerce_before_mini_cart_contents' );
    
            foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
                $_product     = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
                $product_id   = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

                if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
                    $product_name      = apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key );
                    $thumbnail         = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image('woocommerce_thumbnail'), $cart_item, $cart_item_key );
                    $product_price     = apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key );
                    $product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );

                    // jws
                  
                    if ( ! $_product->is_visible() ) {
                        $product_name = '<span class="jws-cart-panel-product-title">' . $product_name . '</span>';
                    } else {
                        $product_permalink = esc_url( $product_permalink );
                        $thumbnail = '<a href="' . $product_permalink . '">' . $thumbnail . '</a>';
                        $product_name = '<div class="jws-cart-panel-product-title"><a href="' . $product_permalink . '">' . $product_name . '</a></div>';
                    }
            
                    ?>
                    <li class="jws-cart-panel-item-<?php echo esc_attr( $cart_item_key ); ?>" class="woocommerce-mini-cart-item <?php echo esc_attr( apply_filters( 'woocommerce_mini_cart_item_class', 'mini_cart_item', $cart_item, $cart_item_key ) ); ?>">
                        
                        <div class="jws_cart_item_inner">
                        <div class="jws-cart-panel-item-thumbnail">
                            <div class="jws-cart-panel-thumbnail-wrap">
                                <?php echo str_replace( array( 'http:', 'https:' ), '', $thumbnail ); ?>
                            </div>
                        </div>
                        <div class="jws-cart-panel-item-content">
                            <?php echo trim($product_name); ?>
                            <?php jws_short_text_after_title($product_id,false); ?>
                            
                            <?php
                              echo apply_filters( 'woocommerce_cart_item_remove_link', sprintf(
                                        '<a href="%s" class="remove" title="%s" data-product_id="%s" data-product_sku="%s" data-cart-item-key="%s"></a>',    
                                        esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
                                         esc_html__( 'Remove this item', 'auriane' ),
                                        esc_attr( $product_id ),
                                        esc_attr( $_product->get_sku() ),
                                        $cart_item_key
                              ), $cart_item_key );
                            ?>
                            
                            <div class="jws-cart-panel-item-price">
                                  <?php echo apply_filters('woocommerce_widget_cart_item_quantity', sprintf('%s',  $product_price) , $cart_item, $cart_item_key); ?>
                            </div>

                            <?php if ( $_product->is_sold_individually() ) : ?>
                                    <?php
                                        echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . esc_html__( 'Qty', 'auriane' ) . ': ' . $cart_item['quantity'] . '</span>', $cart_item, $cart_item_key );
                                    ?>
                            <?php else: ?>
                            <div class="quanty-ajax">
                                  
                                        <?php
                                          $min_value = 1;  
                                          $max_value  = $_product->backorders_allowed() ? '' : $_product->get_stock_quantity();
                                          $input_name = "cart[{$cart_item_key}][qty]";
                                          $step  = 1;
                                          $input_value = $cart_item['quantity'];
                                          if ( $max_value && $min_value === $max_value ) {
                                            ?>
                                            	<input type="hidden" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $min_value ); ?>" />
                                            	<?php
                                            } else {
                                                ?>
                                            
                                            
                                                    <div class="quantity">
                                                        <span class="jws-qty-minus"></span>
                                                        <span class="jws-qty-plus"></span>
                                                        <input type="number" class="input-text qty" step="<?php echo esc_attr( $step ); ?>" min="<?php echo esc_attr( $min_value ); ?>" max="<?php echo esc_attr( 0 < $max_value ? $max_value : '' ); ?>" name="<?php echo esc_attr( $input_name ); ?>" value="<?php echo esc_attr( $input_value ); ?>" size="4" pattern="[0-9]*" />
                                                    </div>
                                                
                                            <?php
                                            }
                                        ?>
                                    
                                </div>   
                              <?php endif; ?>
                            <?php echo wc_get_formatted_cart_item_data( $cart_item ); ?>
                        </div>
                        </div>  
                    </li>
                    <?php
                }
            }
    
            do_action( 'woocommerce_mini_cart_contents' );
        ?>

    <?php endif; ?>
    <?php if (WC()->cart->is_empty()) : ?>
         <li class="cart_empty">
            <span class="jws-icon-icon_cart_alt"></span>
            <?php esc_html_e( 'No Products in the Cart.', 'auriane' ); ?>
            <a class="elementor-button btn-main" href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>"><?php echo esc_html__('Continue Shopping','auriane'); ?></a>
         </li>
    <?php endif; ?>
   

</ul><!-- end product list -->

</div>
    
<div class="jws-cart-panel-summary">
    
    <div class="jws-cart-panel-summary-inner">
       
        <?php if ( ! WC()->cart->is_empty() ) : ?>
        
         <div class="total-cart">
        <p class="woocommerce-mini-cart__total total">
            <strong><?php esc_html_e( 'Subtotal', 'auriane' ); ?>:</strong>
            <span class="jws-cart-panel-summary-subtotal">
                <?php echo WC()->cart->get_cart_subtotal(); ?>
            </span>
        </p>
      </div>
     
        <?php echo bbloomer_free_shipping_cart_notice_zones(); ?>
    
        <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
        <p class="woocommerce-mini-cart__buttons buttons_cart">
        <a href="<?php echo esc_url( wc_get_cart_url() ); ?>" class="button edit-cart"><?php esc_html_e( 'View cart', 'auriane' ); ?></a>
        </p>
        <p class="woocommerce-mini-cart__buttons buttons_checkout in_product">
            <a href="<?php echo esc_url( wc_get_checkout_url() ); ?>" class="button checkout wc-forward"><?php esc_html_e( 'Checkout', 'auriane' ); ?></a>
        </p>
        
        <?php endif; ?>
		
        
    </div>

</div>

<?php do_action( 'woocommerce_after_mini_cart' ); ?>
    
</div>
    </div>
<?php }
function menu_cart_fragments( $fragments ) {
		$has_cart = is_a( WC()->cart, 'WC_Cart' );
		if ( ! $has_cart ) {
			return $fragments;
		}

		ob_start();
		render_menu_cart();
		$menu_cart_html = ob_get_clean();

		if ( ! empty( $menu_cart_html ) ) {
			$fragments['.jws-mini-cart-wrapper .jws_cart_content'] = $menu_cart_html;
		}

		return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments',  'menu_cart_fragments'  );
/*
   *	Get cart contents count
   */
function jws_get_cart_contents_count()
{
    $count = is_object( WC()->cart ) ? WC()->cart->get_cart_contents_count() : '0';     
    $cart_count = apply_filters('jws_cart_count',$count);
    $count_class = ($cart_count > 0) ? '' : ' jws-count-zero';
    return '<span class="jws-menu-cart-count count' . $count_class . '">' . $cart_count .'</span>';
}

function jws_get_cart_contents_total()
{
    $total_price = is_object( WC()->cart ) ? WC()->cart->get_cart_total() : '$0.00'; 
    $cart_total = apply_filters('jws_cart_total',$total_price);
    return '<span class="jws_cart_total">' . $cart_total . '</span>';
}

/*
 *	Cart: Get refreshed header fragment
 */
add_filter('woocommerce_add_to_cart_fragments', 'jws_cart_total_fragment');
if (!function_exists('jws_cart_total_fragment')) {
    function jws_cart_total_fragment($fragments)
    {
        $cart_total = jws_get_cart_contents_total();
        $fragments['.jws_mini_cart .jws_cart_total'] = $cart_total;

        return $fragments;
    }
}
add_filter('woocommerce_add_to_cart_fragments', 'jws_header_add_to_cart_fragment'); 
if (!function_exists('jws_header_add_to_cart_fragment')) {
    function jws_header_add_to_cart_fragment($fragments)
    {
        $cart_count = jws_get_cart_contents_count();
        $fragments['.jws_mini_cart .count'] = $cart_count;

        return $fragments;
    }
}
if (!function_exists('jws_shipping_add_to_cart_fragment')) {
    function jws_shipping_add_to_cart_fragment($fragments)
    {
        $shipping_count = bbloomer_free_shipping_cart_notice_zones();
        $fragments['.jws-shipping'] = $shipping_count;

        return $fragments;
    }
}

add_filter('woocommerce_add_to_cart_fragments', 'jws_shipping_add_to_cart_fragment'); // Ensure cart contents update when products are added to the cart via Ajax

function jws_get_cart_fragments($return_array = array())
{
    // Get cart count
    $cart_count = jws_header_add_to_cart_fragment(array());

    // Get cart panel
    ob_start();
    woocommerce_mini_cart();
    $cart_panel = ob_get_clean();

    return apply_filters('woocommerce_add_to_cart_fragments', array(
        '.jws-menu-cart-count' => reset($cart_count),
        '.jws-shipping' => reset($shipping_count),
        'div.jws-cart-sidebar' => '<div class="jws-cart-sidebar">' . $cart_panel . '</div>'
    ));
}

/*
 *	Cart: Get refreshed hash
 */
function jws_get_cart_hash()
{
    return apply_filters('woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5(json_encode(WC()->cart->get_cart_for_session())) : '', WC()->cart->get_cart_for_session());
}

/*
 *	Cart panel: AJAX - Remove product from cart
 */
function jws_cart_panel_remove_product()
{
    $cart_item_key = $_POST['cart_item_key'];

    $cart = WC()->instance()->cart;
    $removed = $cart->remove_cart_item($cart_item_key); // Note: WP 2.3 >

    if ($removed) {
        $json_array['status'] = '1';

        // Not replacing whole cart-panel by default (thumbnails "flicker" when they're replaced)
        if (defined('jws_CART_PANEL_REPLACE')) {
            $json_array['fragments'] = jws_get_cart_fragments();
        } else {
            $json_array['fragments'] = apply_filters('woocommerce_add_to_cart_fragments', array(
                '.jws-menu-cart-count' => jws_get_cart_contents_count(), // Cart count
                '.jws-shipping' => bbloomer_free_shipping_cart_notice_zones(),
                '.jws_mini_cart .jws-cart-panel-summary-subtotal' => '<span class="jws-cart-panel-summary-subtotal">' . WC()->cart->get_cart_subtotal() . '</span>' // Cart subtotal
            ));
        }

        $json_array['cart_hash'] = jws_get_cart_hash();
    } else {
        $json_array['status'] = '0';
    }

    echo json_encode($json_array);

    exit;
}

add_action('wp_ajax_jws_cart_panel_remove_product', 'jws_cart_panel_remove_product');
add_action('wp_ajax_nopriv_jws_cart_panel_remove_product', 'jws_cart_panel_remove_product');
/*
 *	Cart panel: AJAX - Update quantity
 */
function jws_cart_panel_update_quantity()
{
    $jws_json_array = array();

    // WooCommerce: Code copied from the "../woocommerce/includes/class-wc-form-handler.php" source file
    $cart_updated = false;
    $cart_totals = isset($_POST['cart']) ? $_POST['cart'] : '';

    //if ( ! WC()->cart->is_empty() && is_array( $cart_totals ) ) {
    if (is_array($cart_totals)) {
        foreach (WC()->cart->get_cart() as $cart_item_key => $values) {

            $_product = $values['data'];

            // Skip product if no updated quantity was posted
            if (!isset($cart_totals[$cart_item_key]) || !isset($cart_totals[$cart_item_key]['qty'])) {
                continue;
            }

            // Sanitize
            $quantity = apply_filters('woocommerce_stock_amount_cart_item', wc_stock_amount(preg_replace("/[^0-9\.]/", '', $cart_totals[$cart_item_key]['qty'])), $cart_item_key);

            if ('' === $quantity || $quantity == $values['quantity'])
                continue;

            // Update cart validation
            $passed_validation = apply_filters('woocommerce_update_cart_validation', true, $cart_item_key, $values, $quantity);

            // is_sold_individually
            if ($_product->is_sold_individually() && $quantity > 1) {
                $passed_validation = false;
            }

            if ($passed_validation) {
                WC()->cart->set_quantity($cart_item_key, $quantity, false);
                $cart_updated = true;

                // jws
                // Save "cart item key" ("$cart_item_key" is overwritten)
                $jws_cart_item_key = $cart_item_key;
                // Code from "../blaazer/woocommerce/cart/cart.php" (variable names changed)
                $jws_cart_item_subtotal = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $quantity), $values, $cart_item_key);


                $product_price = apply_filters('woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal($_product, $quantity), $values, $cart_item_key);


                $jws_cart_item_subtotal = apply_filters('woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf('%s &times; %s', $quantity, $product_price) . '</span>', $values, $cart_item_key);


                //apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="quantity">' . sprintf( '%s &times; %s', $cart_item['quantity'], $product_price ) . '</span>', $cart_item, $cart_item_key );                

                // /jws
            }

        }
    }

    // Trigger action - let 3rd parties update the cart if they need to and update the $cart_updated variable
    $cart_updated = apply_filters('woocommerce_update_cart_action_cart_updated', $cart_updated);

    if ($cart_updated) {
        // Recalc our totals
        WC()->cart->calculate_totals();

        // jws
        $jws_json_array['status'] = '1';
        $jws_json_array['fragments'] = apply_filters('woocommerce_add_to_cart_fragments', array(
            '.jws-menu-cart-count' => jws_get_cart_contents_count(), // Cart count
            '.jws-shipping' => bbloomer_free_shipping_cart_notice_zones(),
            '.jws-cart-panel-item-' . $jws_cart_item_key . ' .jws-cart-panel-item-price' => '<div class="jws-cart-panel-item-price">' . $jws_cart_item_subtotal . '</div>', // Cart item subtotal
            '.jws_mini_cart .jws-cart-panel-summary-subtotal' => '<span class="jws-cart-panel-summary-subtotal">' . WC()->cart->get_cart_subtotal() . '</span>' // Cart subtotal
        ));
    } else {
        $jws_json_array['status'] = '0';
    }
    // /jws
    // /WooCommerce

    echo json_encode($jws_json_array);

    exit;
}


add_action('wp_ajax_jws_cart_panel_update', 'jws_cart_panel_update_quantity');
add_action('wp_ajax_nopriv_jws_cart_panel_update', 'jws_cart_panel_update_quantity');


/**
 * Hide shipping rates when free shipping is available.
 * Updated to support WooCommerce 2.6 Shipping Zones.
 *
 * @param array $rates Array of rates found for the package.
 * @return array
 */
function bbloomer_free_shipping_cart_notice_zones()
{
    global $woocommerce;

    // Get Free Shipping Methods for Rest of the World Zone & populate array $min_amounts

    $default_zone = new WC_Shipping_Zone(0);
    $default_methods = $default_zone->get_shipping_methods();

    foreach ($default_methods as $key => $value) {
        if ($value->id === "free_shipping") {
            if ($value->min_amount > 0) $min_amounts[] = $value->min_amount;
        }
    }

    // Get Free Shipping Methods for all other ZONES & populate array $min_amounts

    $delivery_zones = WC_Shipping_Zones::get_zones();

    foreach ($delivery_zones as $key => $delivery_zone) {
        foreach ($delivery_zone['shipping_methods'] as $key => $value) {
            if ($value->id === "free_shipping") {
                if ($value->min_amount > 0) $min_amounts[] = $value->min_amount;
            }
        }
    }

    // Find lowest min_amount

    if (isset($min_amounts) && is_array($min_amounts)) {

        $min_amount = min($min_amounts);

        // Get Cart Subtotal inc. Tax excl. Shipping

        $current = WC()->cart->subtotal;

        // If Subtotal < Min Amount Echo Notice
        // and add "Continue Shopping" button
        $html = '<div class="jws-shipping">';
        if ($current < $min_amount) { 
            $result = ($current / $min_amount) * 100;
            $html .= '<div class="progress_bar_total">
                        <div class="line"><span style="width:'.$result.'%;"><span><span class="number">'.round($result, 2).'%</span></span></span></div>
                     </div>';
            $html .= '<div class="jws_shipping_wap">' .esc_html__('Spend ', 'auriane') .  wc_price($min_amount - $current) . sprintf('%s %s',  __(' to reach', 'auriane') , '<span class="text-free">'.__(' free shipping', 'auriane').'</span>' ).  '</span></div>';
        } else {
            $html .= '<div class="jws_shipping_wap"><span class="fa fa-check-square"></span>' . esc_html__('Free standard delivery', 'auriane') . '</div>';
        }
        $html .= '</div>';
        return $html;
    }

}

if( ! function_exists( 'jws_ajax_add_to_cart' ) ) {
	function jws_ajax_add_to_cart() {

		// Get messages
		ob_start();

		wc_print_notices();

		$notices = ob_get_clean();


		// Get mini cart
		ob_start();

		render_menu_cart();

		$mini_cart = ob_get_clean();

		// Fragments and mini cart are returned
		$data = array(
			'notices' => $notices,
			'fragments' => apply_filters( 'woocommerce_add_to_cart_fragments', array(
					'div.jws_cart_content' => '<div class="jws_cart_content">' . $mini_cart . '</div>'
				)
			),
			'cart_hash' => apply_filters( 'woocommerce_add_to_cart_hash', WC()->cart->get_cart_for_session() ? md5( json_encode( WC()->cart->get_cart_for_session() ) ) : '', WC()->cart->get_cart_for_session() )
		);

		wp_send_json( $data );

		die();
	}
}

add_action( 'wp_ajax_jws_ajax_add_to_cart', 'jws_ajax_add_to_cart' );
add_action( 'wp_ajax_nopriv_jws_ajax_add_to_cart', 'jws_ajax_add_to_cart' );
