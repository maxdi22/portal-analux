<?php 
add_filter( 'woocommerce_enqueue_styles', '__return_empty_array' );
add_theme_support('wc-product-gallery-zoom');
add_theme_support('woocommerce');


add_action('init','change_woocommerce_action');
function change_woocommerce_action() {
    remove_action( 'woocommerce_before_main_content', 'woocommerce_breadcrumb', 20 );
    remove_action( 'woocommerce_shop_loop_item_title','woocommerce_template_loop_product_title', 10 );
    remove_action( 'woocommerce_after_shop_loop_item','woocommerce_template_loop_add_to_cart', 10 );
    remove_action( 'woocommerce_before_single_product_summary' , 'woocommerce_show_product_sale_flash', 10 );
    remove_action( 'woocommerce_after_single_product_summary' , 'woocommerce_output_product_data_tabs', 10 );
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_login_form', 10 );
    remove_action( 'woocommerce_before_checkout_form', 'woocommerce_checkout_coupon_form', 10 );  
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_show_product_loop_sale_flash', 10 );   
    remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );   
    remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cross_sell_display'); 
       
    add_filter( 'woocommerce_product_tabs', 'jws_woo_remove_reviews_tab', 98 );
    add_filter( 'woocommerce_product_tabs', 'woo_new_product_tab' );  
   
}

add_action('init','add_woocommerce_action');
function add_woocommerce_action() {

 $layout = jws_checkout_layout(); 

 if($layout == 'modern') {
            remove_action('woocommerce_checkout_order_review', 'woocommerce_checkout_payment', 20);
            add_action('woocommerce_checkout_after_customer_details', 'woocommerce_checkout_payment', 30);
            add_action('woocommerce_checkout_after_customer_details', 'jws_shipping_method_modern', 25);
            add_filter('woocommerce_update_order_review_fragments', 'jws_update_order_review_fragments');
            add_action('woocommerce_review_order_after_cart_contents', 'jws_checkout_coupon_form_clone');
            add_filter('woocommerce_checkout_fields', 'jws_override_billing_checkout_fields', 20, 1);
            add_filter( 'woocommerce_default_address_fields' , 'jws_rename_address_placeholders_checkout', 9999 );
  }
}

function woo_new_product_tab( $tabs ) {
	global $jws_option;
	// Adds the new tab
    $options2 = get_post_meta( get_the_ID(), '_shipping_content_tab', true );
    $content = '';
    if($options2){
        $content = $options2;
    }elseif(isset($jws_option['_shipping_content_tab']) && !empty($jws_option['_shipping_content_tab'])) {
       $content = $jws_option['_shipping_content_tab']; 
    }

    if(isset($content) &&  !empty($content)) {
	$tabs['test_tab'] = array(
		'title' 	=> __( 'Shipping & Delivery', 'auriane' ),
		'priority' 	=> 50,
		'callback' 	=> 'woo_new_product_tab_content'
	);
}
	return $tabs;

}
function woo_new_product_tab_content() {
    global $jws_option;
    $options2 = get_post_meta( get_the_ID(), '_shipping_content_tab', true );
    $content = '';
    if($options2){
        $content = $options2;
    }elseif(isset($jws_option['_shipping_content_tab']) && !empty($jws_option['_shipping_content_tab'])) {
       $content = $jws_option['_shipping_content_tab']; 
    }
    echo wpautop($content);	
}


function jws_shipping_method_modern() {
    ?>
    <div id="jws-shipping-methods">
            <h3><?php echo esc_html__('Shipping Methods', 'auriane'); ?></h3>
            <div class="jws-shipping-wrap"><div></div></div>
    </div>
    <?php
}


function jws_override_billing_checkout_fields($fields)
{

    $fields['billing']['billing_first_name']['placeholder'] = __('First Name*','auriane');
    $fields['billing']['billing_last_name']['placeholder'] = __('Last Name*','auriane');
    $fields['billing']['billing_company']['placeholder'] = __('Company name (optional)','auriane');
    $fields['billing']['billing_address_1']['placeholder'] = __('Street address*','auriane');
    $fields['billing']['billing_address_2']['placeholder'] = __('Street address*','auriane');
    $fields['billing']['billing_postcode']['placeholder'] = __('Postcode / ZIP','auriane');
    $fields['billing']['billing_phone']['placeholder'] = __('Phone*','auriane');
    $fields['billing']['billing_city']['placeholder'] = __('Town / City*','auriane');
    $fields['billing']['billing_email']['placeholder'] = __('Email Address*','auriane');
    $fields['billing']['billing_state']['placeholder'] = __(' State*','auriane');
    


    $fields['shipping']['shipping_first_name']['placeholder'] = __('First Name*','auriane');
    $fields['shipping']['shipping_last_name']['placeholder'] = __('Last Name*','auriane');
    $fields['shipping']['shipping_company']['placeholder'] = __('Company name (optional)','auriane');
    $fields['shipping']['shipping_address_1']['placeholder'] = __('Street address*','auriane');
    $fields['shipping']['shipping_postcode']['placeholder'] = __('Postcode / ZIP','auriane');
    $fields['shipping']['shipping_city']['placeholder'] = __('Town / City*','auriane');
    $fields['shipping']['shipping_state']['placeholder'] = __(' State*','auriane');
  

	return $fields;
}


function jws_rename_address_placeholders_checkout($address_fields) {
    $address_fields['address_1']['placeholder'] = __('House number and street name *','auriane');
    return $address_fields;
}


if (!function_exists('jws_checkout_coupon_form_clone')) :
    function jws_checkout_coupon_form_clone() {
        include JWS_ABS_PATH . '/woocommerce/checkout/jws-checkout-coupon-modern.php';
    }
endif;

/**
 * Add Filter 'woocommerce_update_order_review_fragments'.
 */
if (!function_exists('jws_update_order_review_fragments')) :
    function jws_update_order_review_fragments($fragments) {

        $packages = WC()->shipping->get_packages();
       print_r(WC()->countries->get_formatted_address($packages[0]['destination'], ', ') , true);
        if (isset($packages[0]) && $packages[0]['destination']) {
            $fragments['.jws-info-address'] = '<span class="jws-info-address">' . WC()->countries->get_formatted_address($packages[0]['destination'], ', ') . '</span>';
        }
        /**
         * Total price
         */
        ob_start();
        wc_cart_totals_order_total_html();
        $total = ob_get_clean();
        $fragments['.your-order-price'] = '<span class="your-order-price">' . $total . '</span>';
        
        /**
         * Shipping Method
         */
        ob_start();
        wc_cart_totals_shipping_html();
        $shipping = ob_get_clean();
        $fragments['.jws-shipping-wrap div'] = $shipping;
        
        return $fragments;
    }
endif;


/**
 * Add Filter 'woocommerce_checkout_fields'.
 */


if (!function_exists('jws_checkout_layout')) :
    function jws_checkout_layout() {
        $layout = 'classic';
        if(jws_theme_get_option('checkout_layout') == 'modern' || (isset($_COOKIE['checkout']) && $_COOKIE['checkout'] == 'modern')) { 
            $layout = 'modern';
        }
        return $layout;
    }
endif;





function jws_woo_remove_reviews_tab($tabs) {
unset($tabs['reviews']);
return $tabs;
}

function jws_shop_single_info_more() {
    if(jws_theme_get_option('shop_single_info_more')) {
        echo '<div class="shop_info_more">'.jws_theme_get_option('shop_single_info_more').'</div>';
    }
}
add_action( 'woocommerce_single_product_summary', 'jws_shop_single_info_more', 25 );  






function jwsChangeProductsTitle() {
   echo '<div class="woocommerce-loop-product__title"><a href="'.get_the_permalink().'">' . get_the_title() . '</a></div>';
}
add_action('woocommerce_shop_loop_item_title', 'jwsChangeProductsTitle', 10 );

add_action( 'woocommerce_before_shop_loop_item_title', 'jws_product_label', 10 ); 

add_action( 'woocommerce_before_shop_loop_item_title', 'jws_product_thumbnail_gallery', 15 ); 

function jws_product_thumbnail_gallery() {
    

    global $product;
    if ( version_compare( WC_VERSION, '3.0.0', '<' ) ) {
    	$attachment_ids = $product->get_gallery_image_ids();
    } else {
    	$attachment_ids = $product->get_gallery_image_ids();
    }
    if ( isset( $attachment_ids[0] ) ) {

		$attachment_id = $attachment_ids[0];

		$title = get_the_title();
		$link  = get_the_permalink();
        $size_img = 'woocommerce_thumbnail';
		$image = wp_get_attachment_image( $attachment_id, $size_img );

		echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', sprintf( '<div class="gallery" title="%s">%s</div>', $title, $image ), $attachment_id, get_the_ID() );
	}
 
    
}
    		


/**
 * Change number of products that are displayed per page (shop page)
 */
add_filter('loop_shop_per_page', 'jws_new_loop_shop_per_page', 20);

function jws_new_loop_shop_per_page($cols)
{
    global $jws_option;
    // $cols contains the current number of products per page based on the value stored on Options -> Reading
    // Return the number of products you wanna show per page.
    $cols = (isset($jws_option['product_per_page']) && !empty($jws_option['product_per_page'])) ? $jws_option['product_per_page'] : 12;
    if(isset($_GET['number']) && $_GET['number']) {  
      $cols = $_GET['number'];  
    }
    return $cols;
}


function jws_button_product_grid($cart, $wishlist , $quickview) {
    ?>
        <ul class="ct_ul_ol">
            <?php if($cart) : ?>
            <li class="btn-cart"><?php woocommerce_template_loop_add_to_cart(); ?></li>
            <?php endif; ?>
            <?php if($wishlist) : ?>
            <li class="btn-wishlist"><?php jws_add_to_wishlist_btn(); ?></li>
            <?php endif; ?>
            <?php if($quickview) : ?>
            <li class="btn-quickview">
                <a data-product_id="<?php echo get_the_ID(); ?>" class="quickview-button"> <i class="jws-icon-eye-thin"></i><span><?php echo esc_html__('Quick view','auriane') ?></span></a>
            </li>
            <?php endif; ?>
        </ul>
    <?php
}

if ( ! function_exists( 'jws_product_quickview_button' ) ) {
	/**
	 * Add wishlist Button to Product Image
	 */
	function jws_product_quickview_button() {

		?>
		<div class="quickview-icon">
			<button data-product_id="<?php echo get_the_ID(); ?>" class="quickview-button is-outline circle icon">
				<span class="lnr lnr-eye"></span>
                <div class="quickview-popup">
				    <?php echo esc_html__( 'Quick View', 'auriane' ); ?>
			    </div>
			</button>
		</div>
		<?php
	}
}
   
if( ! function_exists( 'jws_ajax_load_product_quickview' ) ) {
    	function jws_ajax_load_product_quickview($id = false) {
    		if( isset($_GET['id']) ) {
    			$id = (int) $_GET['id'];
    		}
    
    
    		global $post, $product;
    
    
    		$args = array( 'post__in' => array($id), 'post_type' => 'product' );
    
    		$quick_posts = get_posts( $args );
    
    	
    
    		foreach( $quick_posts as $post ) :
    			setup_postdata($post);
    			$product = wc_get_product($post);
                wc_get_template_part( 'quickview/content', 'quickview' );
    		endforeach; 
    
    		wp_reset_postdata(); 
    
    		die();
    	}
    
        
        // Note: Keep default AJAX actions in case WooCommerce endpoint URL is unavailable
        add_action('wp_ajax_jws_ajax_load_product_quickview', 'jws_ajax_load_product_quickview');
        add_action('wp_ajax_nopriv_jws_ajax_load_product_quickview', 'jws_ajax_load_product_quickview');
    
} 

if( ! function_exists( 'jws_ajax_load_product_video_quickview' ) ) {
    	function jws_ajax_load_product_video_quickview($id = false) {
    		if( isset($_GET['id']) ) {
    			$id = (int) $_GET['id'];
    		}
    
    
    		global $post, $product;
    
    
    		$args = array( 'post__in' => array($id), 'post_type' => 'product' );
    
    		$quick_posts = get_posts( $args );
    
    	      
    
    		foreach( $quick_posts as $post ) :
           
    			setup_postdata($post);
    			$product = wc_get_product($post);
                wc_get_template_part( 'quickview-video/content', 'quickview' );
    		endforeach; 
    
    		wp_reset_postdata(); 
    
    		die();
    	}
    
        
        // Note: Keep default AJAX actions in case WooCommerce endpoint URL is unavailable
        add_action('wp_ajax_jws_ajax_load_product_video_quickview', 'jws_ajax_load_product_video_quickview');
        add_action('wp_ajax_nopriv_jws_ajax_load_product_video_quickview', 'jws_ajax_load_product_video_quickview');
    
}

if( ! function_exists( 'jws_product_label' ) ) {
	function jws_product_label() {
		global $product;

		$output = array();


		if ( $product->is_on_sale() ) {

			$percentage = '';

			if ( $product->get_type() == 'variable' ) {

				$available_variations = $product->get_variation_prices();
				$max_percentage = 0;

				foreach( $available_variations['regular_price'] as $key => $regular_price ) {
					$sale_price = $available_variations['sale_price'][$key];

					if ( $sale_price < $regular_price ) {
						$percentage = round( ( ( $regular_price - $sale_price ) / $regular_price ) * 100 );

						if ( $percentage > $max_percentage ) {
							$max_percentage = $percentage;
						}
					}
				}

				$percentage = $max_percentage;
			} elseif ( ( $product->get_type() == 'simple' || $product->get_type() == 'external' ) ) {
				$percentage = round( ( ( $product->get_regular_price() - $product->get_sale_price() ) / $product->get_regular_price() ) * 100 );
			}

			if ( $percentage ) {
				$output[] = '<span class="onsale jws_pr_label">' . esc_html__( 'Sale ', 'auriane' ).$percentage . '%' . '</span>';
			}else{
				$output[] = '<span class="onsale jws_pr_label">' . esc_html__( 'Sale', 'auriane' ) . '</span>';
			}
		}
		
		if( !$product->is_in_stock() && $product->get_type() != 'variable' ){
			$output[] = '<span class="out-of-stock jws_pr_label">' . esc_html__( 'Sold out', 'auriane' ) . '</span>';
		}

		if ( $product->is_featured()) {
			$output[] = '<span class="featured jws_pr_label">' . esc_html__( 'Best Seller', 'auriane' ) . '</span>';
		}
   
		if ( get_post_meta( get_the_ID(), '_jws_new_enabled', true ) == 'yes') {
			$output[] = '<span class="new jws_pr_label">' . esc_html__( 'New', 'auriane' ) . '</span>';
		}
		
        if ( get_post_meta( get_the_ID(), '_jws_limit_enabled', true ) == 'yes') {
			$output[] = '<span class="limit jws_pr_label">' . esc_html__( 'Limited Edition', 'auriane' ) . '</span>';
		}
		
		if ( $output ) {
			echo '<div class="jws_pr_labels">' . implode( '', $output ) . '</div>';
		}
	}
}
if( ! function_exists( 'jws_product_share' ) ) {
	function jws_product_share() { 
       
       if(jws_theme_get_option('product-single-share')) {
  
       ?>
	   
       <div class="product-share">
    
            <span class="share-label"><?php echo esc_html__('Share','auriane') ?></span>
          
            <span class="addthis_inline_share_toolbox" data-url="<?php the_permalink(); ?>" data-title="<?php the_title_attribute(); ?>">
            
                    <a target="_blank" href="https://www.facebook.com/sharer/sharer.php?u=<?php the_permalink(); ?>"><i class="fab fa-facebook-f"></i></a>
            
            		<a target="_blank" href="//twitter.com/share?url=<?php the_permalink(); ?>"><i class="fab fa-twitter"></i></a>
            
            		<a target="_blank"  href="//www.pinterest.com/pin/create/button/?url=<?php echo the_permalink(); ?>"><i class="fab fa-pinterest"></i></a>
            
            		<a target="_blank" href="//www.linkedin.com/shareArticle?mini=true&title=<?php echo get_the_title(); ?>&url=<?php  the_permalink(); ?>"><i class="fab fa-linkedin"></i></a>
               
                
           </span>     
       </div>
       
    <?php 
      }
    }
}    

add_filter( 'woocommerce_output_related_products_args', 'jws_related_products_args', 20 );
  function jws_related_products_args( $args ) {
	$args['posts_per_page'] = 100; // 4 related products
	return $args;
}



if (!function_exists('jws_shop_page_link')) {
    function jws_shop_page_link($keep_query = false, $link_out = '' ,$taxonomy = '')
    {
        // Base Link decided by current page
        if (defined('SHOP_IS_ON_FRONT')) {
            $link = home_url();
        } elseif (is_post_type_archive('product') || is_page(wc_get_page_id('shop'))) {
            $link = get_post_type_archive_link('product');

        } elseif (is_product_category()) {
            $link = get_term_link(get_query_var('product_cat'), 'product_cat');
        } elseif (is_product_tag()) {
            $link = get_term_link(get_query_var('product_tag'), 'product_tag');
        } else {
            $link = get_term_link(get_query_var('term'), get_query_var('taxonomy'));
        }
        
        if(!empty($link_out)) {
           $link = $link_out; 
        }

        if ($keep_query) {

            
            
            $link_array_slug = array(
                'min_price','max_price' ,'orderby','lay_style','shop_layout','layout','filter_layout'
                
            );
            
            
            foreach($link_array_slug as $get_slug) {
                if (isset($_GET[$get_slug])) {
                    $link = add_query_arg($get_slug, $_GET[$get_slug], $link);
                } 
            }
            
             // All current filters
            if ($_chosen_attributes = WC_Query::get_layered_nav_chosen_attributes()) {

                foreach ($_chosen_attributes as $name => $data) {
                    if ($name === $taxonomy) {
                        continue;
                    }

                    $filter_name = sanitize_title(str_replace('pa_', '', $name));
                    if (!empty($data['terms'])) {
                        $link = add_query_arg('filter_' . $filter_name, implode(',', $data['terms']), $link);

                    }
                    if ('or' == $data['query_type']) {
                        $link = add_query_arg('query_type_' . $filter_name, 'or', $link);

                    }
                }
            }

            /**
             * Search Arg.
             * To support quote characters, first they are decoded from &quot; entities, then URL encoded.
             */
            if (get_search_query()) {
                $link = add_query_arg('s', rawurlencode(wp_specialchars_decode(get_search_query())), $link);
            }

            
        }

        return $link;
    }
}



//move comment field to bottom of form
function wpb_move_comment_field_to_bottom( $fields ) {
    $comment_field = $fields['comment'];
    unset( $fields['comment'] );
    $fields['comment'] = $comment_field;
    return $fields;
}
 
add_filter( 'comment_form_fields', 'wpb_move_comment_field_to_bottom' );



//add comment meta field

function add_custom_comment_field( $comment_id ) {
   if(isset($_POST['title_comment'])) {
    add_comment_meta( $comment_id, 'title_comment', $_POST['title_comment'] );
   } 
}
add_action( 'comment_post', 'add_custom_comment_field' );

function extend_comment_edit_metafields( $comment_id ) {
    if( ! isset( $_POST['extend_comment_update'] ) || ! wp_verify_nonce( $_POST['extend_comment_update'], 'extend_comment_update' ) ) return;
 
	if ( ( isset( $_POST['title_comment'] ) ) && ( $_POST['title_comment'] != '') ) :
	$title_comment = wp_filter_nohtml_kses($_POST['title_comment']);
	update_comment_meta( $comment_id, 'title_comment', $phone );
	else :
	delete_comment_meta( $comment_id, 'title_comment');
	endif;

}
add_action( 'edit_comment', 'extend_comment_edit_metafields' );


// add field when logined
add_action( 'comment_form_logged_in_after', 'additional_fields' );
add_action( 'comment_form_after_fields', 'additional_fields' );
 
function additional_fields () {
     global $post_type;
  if($post_type =='product'){
     	if ( wc_review_ratings_enabled() ) {
     echo '<div class="comment-form-rating"><label for="rating">' . esc_html__( 'Rating', 'auriane' ) . ( wc_review_ratings_required() ? '&nbsp;<span class="required">*</span>' : '' ) . '</label><select name="rating" id="rating" required>
		<option value="">' . esc_html__( 'Rate&hellip;', 'auriane' ) . '</option>
		<option value="5">' . esc_html__( 'Perfect', 'auriane' ) . '</option>
		<option value="4">' . esc_html__( 'Good', 'auriane' ) . '</option>
		<option value="3">' . esc_html__( 'Average', 'auriane' ) . '</option>
		<option value="2">' . esc_html__( 'Not that bad', 'auriane' ) . '</option>
		<option value="1">' . esc_html__( 'Very poor', 'auriane' ) . '</option>
	</select></div>';
    }
    if ( wc_review_ratings_enabled() ) {
    echo '<p class="comment-form-title_comment">';
	echo '<label for="title_comment">' . __( 'Title of Review *','auriane' ) . '</label>';
	echo '<input id="title_comment" name="title_comment" placeholder="'.esc_attr__('Give your review a title','auriane').'" type="text"   tabindex="4" aria-required="true"/></p>';
    echo '</p>';
    }
  } 
}



function jws_add_question() {
$response = array();  
if(empty( $_POST['jws-product-questions'] ) || empty( $_POST['q-name'] ) || empty( $_POST['q-email'] )) {  
    $error_note = esc_html__('Please fill out required fields.','auriane');
    $response['note'] = $error_note;
    $response['status'] = 'error';
} else {
    // Add the content of the form to $post as an array
    $new_post = array(
        'post_title'    => $_POST['jws-product-questions'],
        'post_content'  => '',
        'tags_input'    => array($tags),
        'post_status'   => 'publish',           // Choose: publish, preview, future, draft, etc.
        'post_type' => 'questions'  //'post',page' or use a custom post type if you want to
    );
    
    if($pid = wp_insert_post($new_post)) { 
        
      update_post_meta($pid, 'product_questions', $_POST['product_id']);
      update_post_meta($pid, 'product_name', $_POST['q-name']);
      update_post_meta($pid, 'product_email', $_POST['q-email']);
      
      if ( is_user_logged_in() ) {
           $userid = get_current_user_id();
           update_post_meta($pid, 'user_id', $userid);
      }
    }
    
    $response['status'] = 'no_error';
 
}  
    
wp_send_json( $response );

  
}
add_action('wp_ajax_jws_add_question', 'jws_add_question');
add_action('wp_ajax_nopriv_jws_add_question', 'jws_add_question');

function wc_get_rating_html_compare( $rating ) { 
    if ( $rating > 0 ) { 
        $rating_html = '<div class="star-rating" title="' . sprintf( esc_attr__( 'Rated %s out of 5','auriane' ), $rating ) . '">'; 
        $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"><strong class="rating">' . $rating . '</strong> ' . esc_html__( 'out of 5','auriane' ) . '</span>'; 
        $rating_html .= '</div>'; 
    } else { 
        $rating_html = ''; 
    } 
    return $rating_html; 
}

function jws_is_shop() {
	if ( class_exists( 'WooCommerce' ) && is_shop() ) { // Shop Page
		return 'shop';
	}

	return apply_filters( 'jws_is_shop', false );
}

function jws_get_page_base_url() {
    	if ( defined( 'SHOP_IS_ON_FRONT' ) ) {
    		$link = home_url();
    	} elseif ( is_post_type_archive( 'product' ) || is_page( wc_get_page_id( 'shop' ) ) ) {
    		$link = get_post_type_archive_link( 'product' );
    	} elseif ( is_product_category() ) {
    		$link = get_term_link( get_query_var( 'product_cat' ), 'product_cat' );
    	} elseif ( is_product_tag() ) {
    		$link = get_term_link( get_query_var( 'product_tag' ), 'product_tag' );
    	} else {
    		$queried_object = get_queried_object();
    		$link   = get_term_link( $queried_object->slug, $queried_object->taxonomy );
    	}
    
    	return $link;
    }

function shop_filter_content() {
        $shop = jws_check_layout_shop();

        if($shop['position'] == 'left' || $shop['position'] == 'right') { 
            ?>
                <div class="shop-filter-actived">
                	<?php
                        if ( is_active_sidebar( 'remove_filter_active' ) ) {
                          dynamic_sidebar( 'remove_filter_active' );
                        } 
                	?>
                </div>    
            <?php
        }else {
            ?>
        	<div id="jws-shop-topbar" class="widgets-area shop-topbar">
    			<div class="shop-topbar-content">
    				<?php
    				$sidebar = 'sidebar-shop2';
    				if ( isset($shop['select-shop-top_filter']) ) {
    					echo do_shortcode('[elementor-template id="'.$shop['select-shop-top_filter'].'"]');
    				}
          
    				?>
    			</div>
        	</div>
            
            <div class="shop-filter-actived">
            	<?php
                    if ( is_active_sidebar( 'remove_filter_active' ) ) {
                      dynamic_sidebar( 'remove_filter_active' );
                    } 
            	?>
            </div>
            <?php  
        }		
}

function shop_banner_content() {
  $id = jws_theme_get_option('select-banner-before-product');
  if(isset($_GET['shop_layout']) && ($_GET['shop_layout'] == '3' || $_GET['shop_layout'] == '4')) { 
    $id = '6084'; 
  } 
  echo !empty($id) ? '<div class="shop-banner-content">'.do_shortcode('[hf_template id="'.$id.'"]').'</div>' : '';     
}


function jws_short_text_after_title($id,$return) {
   if(isset($id) && !empty($id)) {
     $id = $id;
   } else {
     $id = get_the_ID();
   }
   $text_attr = jws_theme_get_option('choose-attr-after-title'); 
   if ( isset($text_attr) ) { 
       $customize_text = get_post_meta($id , '_jws_customize_below_gid' , true); 
       $post_terms = wp_get_post_terms( $id, $text_attr );
       if(empty($post_terms) && empty($customize_text)) {
         return;
       }
       $content = '<p class="product-short-text">'; 
       if(!empty($customize_text)) {
           $content .= $customize_text;
       }else {
           foreach ( $post_terms as $term ) {
              $content .= esc_html($term->name);  
           }
       }
       $content .= '</p>'; 
       if($return) {
        return $content;
       }else {
        echo ''.$content;
       }
   }  
}



function add_parameter_after_custom_link($link) {

		if ( isset( $_GET['layout'] ) ) {
			$link = add_query_arg( 'layout', wc_clean( $_GET['layout'] ), $link );
		}
        
        if ( isset( $_GET['lay_style'] ) ) {
			$link = add_query_arg( 'lay_style', wc_clean( $_GET['lay_style'] ), $link );
		}
        if ( isset( $_GET['shop_layout'] ) ) {
			$link = add_query_arg( 'shop_layout', wc_clean( $_GET['shop_layout'] ), $link );
		}  
        if ( isset( $_GET['filter_layout'] ) ) {
			$link = add_query_arg( 'filter_layout', wc_clean( $_GET['filter_layout'] ), $link );
		}  
       
        return $link; 
}

function jws_wc_waitlist_form() {
    $page_id = (isset($jws_option['wishlist_page'])) ? $jws_option['wishlist_page'] : '';
    if(is_admin() || is_page( $page_id  ) || !is_product() ) { return false; }
    global $product;

        $title_form_waitlist = jws_theme_get_option('title_form_waitlist') ? jws_theme_get_option('title_form_waitlist') : '';
        $placeholder_input_waitlist = jws_theme_get_option('placeholder_input_waitlist') ? jws_theme_get_option('placeholder_input_waitlist')  : '';
        $submit_waitlist = jws_theme_get_option('submit_waitlist')  ? jws_theme_get_option('submit_waitlist') : '';
    ?>
    <div class="jws-waitlist-register">
    <label><?php echo esc_html($title_form_waitlist)?></label>
     <div class="jws-waitlist-form" name="jws-waitlist-register-form" action="" method="post" enctype='multipart/form-data'>
            
                        <input placeholder="<?php echo esc_attr($placeholder_input_waitlist ); ?>" type="email" name="waitlist_email" class="input required" required />
            <input type="hidden" name="product_id" value="<?php echo get_the_ID(); ?>" />
                    <input type="button" name="wp-submit" class="btn-button" value="<?php echo esc_attr($submit_waitlist); ?>" />
            <?php jws_add_to_wishlist_btn(); ?>
          
            
    </div>

    </div>



    <?php
}




function jws_waitlist_form() {
$response = array();  
if(isset($_POST['waitlist_email']) && !empty($_POST['waitlist_email'])){ 
    $product_id = $_POST['product_id'];
    $email_added = get_post_meta($product_id,'stock_email',true);
    if(is_array($email_added)) {
        $email_added = $email_added;
    }else {
        $email_added = array();
        $email_added[] = get_post_meta($product_id,'stock_email',true);  
    }
    if(!in_array($_POST['waitlist_email'], $email_added))  {
        $email_added[] = $_POST['waitlist_email'];
        update_post_meta($product_id,'stock_email',$email_added);
        $response['note'] = '<div class="green">'.esc_html__('You have successfully subscribed, we will inform you when this product back in stock','auriane').'</div>';
    }else {
        $response['note'] = '<div class="red">'.esc_html__('Seems like you have already subscribed to this product','auriane').'</div>';
    }
} 
wp_send_json( $response ); 
}
add_action('wp_ajax_jws_waitlist_form', 'jws_waitlist_form');
add_action('wp_ajax_nopriv_jws_waitlist_form', 'jws_waitlist_form');





function jws_send_mail_waitlist($product_id) {
    $email_added = get_post_meta($product_id,'stock_email',true);
    if(!isset($email_added) || empty($email_added)) return false;
    global $jws_option;
    $subject_waitlist_email = (isset($jws_option['subject_waitlist_email']) && $jws_option['subject_waitlist_email'] ) ? $jws_option['subject_waitlist_email'] : 'A product you are waiting for is back in stock'; 
    $email_heading_waitlist = (isset($jws_option['email_heading_waitlist']) && $jws_option['email_heading_waitlist'] ) ? $jws_option['email_heading_waitlist'] : '{product_title} is now back in stock on {blogname}';  
    $email_content_waitlist = (isset($jws_option['email_content_waitlist']) && $jws_option['email_content_waitlist'] ) ? $jws_option['email_content_waitlist'] : 'Hi, {product_title} is now back in stock on {blogname}. You have been sent this email because your email address was registered in a waiting list for this product. If you would like to purchase {product_title}, please visit the following link: {product_link}';
    $url = get_permalink($product_id);
    $fields = array(
		'product_title'         => get_the_title($product_id),
		'product_link'        => $url,
		'blogname'         => get_bloginfo(),
	);
    foreach ( $fields as $tag => $value ) {
		if ( strpos( $email_content_waitlist, '{' . $tag . '}' ) !== false ) {
			$email_content_waitlist = str_replace( '{' . $tag . '}', $value, $email_content_waitlist );
		}
	} 
   
    $body = $email_content_waitlist;
    $headers = ['Content-Type: text/html; charset=UTF-8'];
    foreach($email_added as $to) {
        if(function_exists('jws_sv_ct3')) {
            $sent =   jws_sv_ct3($to, $subject_waitlist_email , $body, $headers );
            delete_post_meta($product_id, 'stock_email',$email_added);
        }      
    }      
}


function jws_banner_product() {
    if (defined('aurianecore') ) jws_product_share();
    $banner_meta = get_post_meta(get_the_ID(),'banner_product_image',true);
    if(!empty($banner_meta)) {
        echo '<img class="product_banner" src="'.$banner_meta.'">';
    }
}

add_action( 'woocommerce_single_product_summary', 'jws_banner_product', 50 ); 


/** Woocommerce CountDown Tabs Extention **/


add_filter( 'woocommerce_product_write_panel_tabs', 'jws_add_countdown_tab', 98 );
add_action( 'woocommerce_product_data_panels', 'jws_write_tab_options');


function jws_add_countdown_tab() {

    ?>
    
    <li class="product_countdown_options product_countdown_tab hide_if_grouped hide_if_external">
    	<a href="#product_countdown_tab"><span><?php esc_html_e( 'Product Countdown', 'auriane' ); ?></span></a>
    </li>

<?php

		}

function jws_write_tab_options() {

		global $post;

		$product = wc_get_product( $post );
		$sale_price_dates_from =  get_post_meta( $product->get_id(), '_jwspc_sale_price_dates_from', true );
		$sale_price_dates_to   =  get_post_meta( $product->get_id(), '_jwspc_sale_price_dates_to', true );


		?>

		<div id="product_countdown_tab" class="panel woocommerce_options_panel">

			<div class="options_group sales_countdown">

				<?php

				woocommerce_wp_checkbox(
					array(
						'id'            => '_jwspc_enabled',
						'wrapper_class' => '',
						'label'         => esc_html__( 'Enable ', 'auriane' ),
						'description'   => esc_html__( 'Enable Jws WooCommerce Product Countdown for this product', 'auriane' )
					)
				);
				?>
				<p class="form-field jwspc-dates">
					<label for="_jwspc_sale_price_dates_from"><?php esc_html_e( 'Countdown Dates', 'auriane' ) ?></label>
					<input type="text" autocomplete="off" class="short jwspc_sale_price_dates_from" name="_jwspc_sale_price_dates_from" id="_jwspc_sale_price_dates_from" value="<?php echo esc_attr( $sale_price_dates_from ) ?>" placeholder="<?php esc_html_e( 'From&hellip;', 'auriane' ) ?> YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
					<input type="text" autocomplete="off" class="short jwspc_sale_price_dates_to" name="_jwspc_sale_price_dates_to" id="_jwspc_sale_price_dates_to" value="<?php echo esc_attr( $sale_price_dates_to ) ?>" placeholder="<?php esc_html_e( 'To&hellip;', 'auriane' ) ?>  YYYY-MM-DD" maxlength="10" pattern="[0-9]{4}-(0[1-9]|1[012])-(0[1-9]|1[0-9]|2[0-9]|3[01])" />
					<?php echo esc_html__( 'The sale will end at the beginning of the set date.', 'auriane' ); ?>
				</p>
				<?php

				woocommerce_wp_text_input(
					array(
						'id'                => '_jwspc_discount_qty',
						'label'             => esc_html__( 'Discounted products', 'auriane' ),
						'placeholder'       => '',
						'desc_tip'          => 'true',
						'description'       => esc_html__( 'The number of discounted products.', 'auriane' ),
						'default'           => '0',
						'type'              => 'number',
						'custom_attributes' => array(
							'step' => 'any',
							'min'  => '0'
						)
					)
				);
				woocommerce_wp_text_input(
					array(
						'id'                => '_jwspc_sold_qty',
						'label'             => esc_html__( 'Already sold products', 'auriane' ),
						'placeholder'       => '',
						'desc_tip'          => 'true',
						'description'       => esc_html__( 'The number of already sold products.', 'auriane' ),
						'type'              => 'number',
						'custom_attributes' => array(
							'step' => 'any',
							'min'  => '0'
						)
					)
				);


				?>

			</div>
            
            <script>
            jQuery(function ($) {
     
                
                $("#_jwspc_sale_price_dates_from").datepicker({
                     dateFormat: 'yy-mm-dd',
                    onSelect: function() {
                        //- get date from another datepicker without language dependencies
                        var minDate = $('#_jwspc_sale_price_dates_from').datepicker('getDate');
                        $("#_jwspc_sale_price_dates_to").datepicker("change", { minDate: minDate , dateFormat: 'yy-mm-dd' });
                    }
                });
                
                $("#_jwspc_sale_price_dates_to").datepicker({
                     dateFormat: 'yy-mm-dd',
                    onSelect: function() {
                        //- get date from another datepicker without language dependencies
                        var maxDate = $('#_jwspc_sale_price_dates_to').datepicker('getDate');
                        $("#_jwspc_sale_price_dates_from").datepicker("change", { maxDate: maxDate , dateFormat: 'yy-mm-dd' });
                    }
                });
                
               
             } );
             </script>

		</div>

		<?php

	}
    
add_action( 'woocommerce_process_product_meta', 'jws_woocommerce_product_custom_fields_save' );   

function jws_woocommerce_product_custom_fields_save($post_id)
{
    $product         = wc_get_product( $post_id );
 
    if (isset($_POST['_jwspc_sale_price_dates_from']) && !empty($_POST['_jwspc_sale_price_dates_from'])) update_post_meta($product->get_id(), '_jwspc_sale_price_dates_from', esc_attr($_POST['_jwspc_sale_price_dates_from']));
    
    if (isset($_POST['_jwspc_sale_price_dates_to']) && !empty($_POST['_jwspc_sale_price_dates_to'])) update_post_meta($product->get_id(), '_jwspc_sale_price_dates_to', esc_attr($_POST['_jwspc_sale_price_dates_to']));
    
    if (isset($_POST['_jwspc_discount_qty']) && !empty($_POST['_jwspc_discount_qty'])) update_post_meta($product->get_id(), '_jwspc_discount_qty', esc_attr($_POST['_jwspc_discount_qty']));
    
    if (isset($_POST['_jwspc_sold_qty']) && !empty($_POST['_jwspc_sold_qty'])) update_post_meta($product->get_id(), '_jwspc_sold_qty', esc_attr($_POST['_jwspc_sold_qty']));
    
    $jwspc_enabled = isset($_POST['_jwspc_enabled']) && !empty($_POST['_jwspc_enabled']) ? 'yes' : 'no';
	update_post_meta( $product->get_id(), '_jwspc_enabled', $jwspc_enabled );

}

add_action( 'woocommerce_single_product_summary', 'jws_shop_single_countdown', 22 );  

function jws_shop_single_countdown() {
      $id = get_the_ID();  
      $enble = get_post_meta( $id , '_jwspc_enabled', true ); 
      if($enble == 'yes') {
      $countdown_time = get_post_meta( get_the_ID(), '_jwspc_sale_price_dates_to', true );   
      $html = '<div class="jws_single_count_down">';
            $html .= '<div class="count_down_top">';
            $html .= '<label><i class="jws-icon-alarm"></i>'.esc_html__('Hurry up! End of sale in','auriane').'</label>';
            $html .= '<div class="count_down"><div class="jws-sale-time" data-d="" data-h="" data-m="" data-s="" data-countdown="'.strtotime($countdown_time).'"></div></div>';
      $html .= '</div>';
            
            
      $units_sold = get_post_meta( get_the_ID() , '_jwspc_sold_qty', true );
      $total = get_post_meta( get_the_ID() , '_jwspc_discount_qty', true );
      
      if(!empty($units_sold) && !empty($total)) {
        $result = ($units_sold / $total) * 100;
        $html .= '<div class="progress-bar-sold">';
        $html .= '<span class="sold_count"><img src="'.JWS_URI_PATH . '/assets/image/fire.png">'.sprintf( __( 'Only %s item(s) left in stock', 'auriane' ), '<span>'.($total - $units_sold).'</span>').'</span>';
        $html .= '<span class="available_items">'.$units_sold.'/'.$total.esc_html__(' Sold','auriane').'</span>';
        $html .= '<p class="line"><span style="width:'.$result.'%"><span></span></span></p>';
        $html .= '</div>';
      } 
      $html .= '</div>';
      echo ''.$html; 
      }  
      
}

 

/** Woocommerce Customize Items Extention **/


add_filter( 'woocommerce_product_write_panel_tabs', 'jws_add_customize_items_tab', 98 );
add_action( 'woocommerce_product_data_panels', 'jws_customize_items_tab_options');


function jws_add_customize_items_tab() {

    ?>
    
    <li class="customize_items_options customize_items_tab hide_if_grouped hide_if_external">
    	<a href="#customize_items_tab"><span><?php esc_html_e( 'Jws Product Setting Items', 'auriane' ); ?></span></a>
    </li>

<?php

}

function jws_customize_items_tab_options() {

		global $post;

		$product = wc_get_product( $post );
		$sale_price_dates_from =  get_post_meta( $product->get_id(), '_jwspc_sale_price_dates_from', true );
		$sale_price_dates_to   =  get_post_meta( $product->get_id(), '_jwspc_sale_price_dates_to', true );


		?>

		<div id="customize_items_tab" class="panel woocommerce_options_panel">

			<div class="options_group">

				<?php
                    
                    woocommerce_wp_checkbox(
    					array(
    						'id'            => '_jws_customize_enabled',
    						'wrapper_class' => '',
    						'label'         => esc_html__( 'Enable', 'auriane' ),
    						'description'   => esc_html__( 'Enable Jws WooCommerce Product Customize Items for this product', 'auriane' )
    					)
    				);
                    woocommerce_wp_text_input(
    					array(
    						'id'                => '_jws_customize_below_gid',
    						'label'             => esc_html__( 'Text Customize Below Grid', 'auriane' ),
    						'placeholder'       => '',
    						'type'              => 'text',
    					)
    				);
                    woocommerce_wp_checkbox(
    					array(
    						'id'            => '_jws_new_enabled',
    						'wrapper_class' => '',
    						'label'         => esc_html__( 'Enable New Label', 'auriane' ),
    						'description'   => esc_html__( 'Enable New Label display in product item', 'auriane' )
    					)
    				);
                    woocommerce_wp_checkbox(
    					array(
    						'id'            => '_jws_limit_enabled',
    						'wrapper_class' => '',
    						'label'         => esc_html__( 'Enable Limited Edition Label', 'auriane' ),
    						'description'   => esc_html__( 'Enable Limited Edition Label display in product item', 'auriane' )
    					)
    				);
				?>

			</div>
		</div>
		<?php
	}



/**
 * Save the custom field
 * @since 1.0.0
 */
function jws_save_customize_items_field( $post_id ) {
 $product = wc_get_product( $post_id );
 $enble = isset( $_POST['_jws_customize_enabled'] ) && !empty($_POST['_jws_customize_enabled']) ? 'yes' : 'no';
 $new = isset( $_POST['_jws_new_enabled'] ) && !empty($_POST['_jws_new_enabled']) ? 'yes' : 'no';
 $limit = isset( $_POST['_jws_limit_enabled'] ) && !empty($_POST['_jws_limit_enabled']) ? 'yes' : 'no';
 $product->update_meta_data( '_jws_customize_enabled', $enble);
 $product->update_meta_data( '_jws_new_enabled', $new);
 $product->update_meta_data( '_jws_limit_enabled', $limit);
 if(isset( $_POST['_jws_customize_below_gid'])) {
    $product->update_meta_data( '_jws_customize_below_gid', $_POST['_jws_customize_below_gid']);
 }
 $product->save();
}
add_action( 'woocommerce_process_product_meta', 'jws_save_customize_items_field' );


/**
 * Display custom field on the front end
 * @since 1.0.0
 */
function jws_display_customize_items_field() {
   
 global $post;

 $enble = get_post_meta( $post->ID , '_jws_customize_enabled',false );

 if( isset($enble[0]) && $enble[0] == 'yes' ) { 
   
    WP_Filesystem();
    global $wp_filesystem;

    
    $string =  $wp_filesystem->get_contents(JWS_URI_PATH. '/assets/font/google_font.json');
    $json_a = json_decode($string,true);
 
    $icon_aray = array();
    
 ?>

 <div class="jws-custom-field-wrapper">
    <div class="enable_cutomize_product"><label><input type="hidden" name="enable_customize" value="0" /><input type="checkbox" /><?php echo esc_html__('Customize this item','auriane'); ?>?</label></div>
    <div class="cutomize_product_form">
        <label for="jws-fonttype-field"><?php echo esc_html__('Font type:','auriane'); ?></label>
        <select id="jws-fonttype-select" name="jws-fonttype-field">
            <?php 
                foreach ($json_a['items'] as $key => $value){
                    echo '<option value="'.$value['family'].'">'.$value['family'].'</option>';
                }  
            ?>
        </select>
        <label for="jws-engrave-field"><?php echo esc_html__('Engrave personalized text','auriane'); ?></label>
        <textarea id="jws-engrave-field" placeholder="<?php echo esc_attr_x( 'Enter your text here', 'placeholder', 'auriane' ); ?>..." name="jws-engrave-field" value=""></textarea>
    </div>
 </div>
 
 <script>
     (function($) {
     "use strict"; 
        
        $(document).ready(function() {
              
            var script = document.createElement( 'script' );
            var style = document.createElement( 'link' );
            if(!$("#jws-ct-js").length) {
                Object.assign(script, {
                    src: "<?php echo JWS_URI_PATH. "/assets/js/lib/select2.min.js"; ?>",
                    id: 'jws-ct-js'
                });  
            }
           if(!$("#jws-ct-css").length) { 
             Object.assign(style, {
                href: "<?php echo JWS_URI_PATH. "/assets/css/select2.min.css"; ?>",
                type: "text/css",
                rel: "stylesheet",
                media: "all",
                id: 'jws-ct-css'
            });
           }
            
    
            document.getElementsByTagName( "head" )[0].appendChild( script );
            document.getElementsByTagName( "head" )[0].appendChild( style ); 
                
             $(document).on("change", '.enable_cutomize_product input', function() {
                var $this = $(this);
                     if($this.is(":checked")) {
                        $('.cutomize_product_form').slideDown();
                        $('[name="enable_customize"]').val(1);
                    } else {
                        $('.cutomize_product_form').slideUp();
                        $('[name="enable_customize"]').val(0);
                    }
                    
            });
            
            $(document).on("click", '.single_add_to_cart_button', function() {
                var $this = $('.enable_cutomize_product input');
              
                     if($this.is(":checked") && !$('#jws-engrave-field').val()) {
                       alert("Please enter text for personalized text engraving."); 
                    }    
            });
            
            $('#jws-fonttype-select').on('change',function(){
              
              var $value = $(this).val();
              $('#font_customize').remove();
              $('head').append("<link rel='stylesheet' id='font_customize'  href='//fonts.googleapis.com/css?family="+$value+"%3A600%2C500%2C400%2C300&#038;ver=5.9.2' type='text/css' media='all' />");
              $('#jws-engrave-field').css("font-family", $value);
                
            });  
            setTimeout(function() {$('#jws-fonttype-select').select2();}, 500); 
        
        });
     })(jQuery);    
 </script>   

 <?php }
 
 
 }
add_action( 'woocommerce_before_add_to_cart_button', 'jws_display_customize_items_field' );


function jws_validate_customize_items_field( $passed, $product_id, $quantity, $variation_id = '' ) {
 if(isset($_POST['enable_customize']) && $_POST['enable_customize']) {
      if(empty( $_POST['jws-engrave-field'] ) ) {
         wc_add_notice( __( 'Please enter a value into the text field', 'auriane' ), 'error' );
         $passed = false; 
     }   
 }  
  return $passed;
}


add_filter( 'woocommerce_add_to_cart_validation', 'jws_validate_customize_items_field', 21, 4 );

function jws_add_customize_items_field_item_data( $cart_item_data, $product_id, $variation_id, $quantity ) { 
 if( ! empty( $_POST['jws-engrave-field'] ) ) {
 $cart_item_data['engrave_field'] = $_POST['jws-engrave-field'];
 $cart_item_data['fonttype_field'] = $_POST['jws-fonttype-field'];
 }
 return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'jws_add_customize_items_field_item_data', 10, 4 );


function jws_customize_items_cart_item_name( $name, $cart_item, $cart_item_key ) {
 if( isset( $cart_item['engrave_field'] ) ) {
 $name .= sprintf(
 '<div class="engrave"><span>'. __( 'Font Type Text: ', 'auriane' ).'</span>%s</div>',
 esc_html( $cart_item['fonttype_field'] )
 );   
 $name .= sprintf(
 '<div class="engrave"><span>'. __( 'Engrave Text: ', 'auriane' ).'</span>%s</div>',
 esc_html( $cart_item['engrave_field'] )
 );
 }
 return $name;
}
add_filter( 'woocommerce_cart_item_name', 'jws_customize_items_cart_item_name', 10, 3 );


function jws_add_customize_items_data_to_order( $item, $cart_item_key, $values, $order ) {
 foreach( $item as $cart_item_key=>$values ) {
 if( isset( $values['engrave_field'] ) ) {
 $item->add_meta_data( __( 'Engrave Text: ', 'auriane' ), $values['engrave_field'], true );
 $item->add_meta_data( __( 'Font Type Text: ', 'auriane' ), $values['fonttype_field'], true );
 }
 }
}
add_action( 'woocommerce_checkout_create_order_line_item', 'jws_add_customize_items_data_to_order', 10, 4 );

add_filter('woocommerce_product_categories_widget_args', 'jws_custom_product_categories_widget_args', 10, 1);

function jws_custom_product_categories_widget_args($args) {
  require JWS_ABS_PATH . '/woocommerce/includes/walkers/class-wc-product-cat-list-walker.php';
  $args['walker'] = new Custom_WC_Product_Cat_List_Walker;
  return $args;
}

function jws_woo_found() {
    global $wp_query;
    // Define each variable again (before using it)
    $paged    = max( 1, $wp_query->get( 'paged' ) );
    $per_page = $wp_query->get( 'posts_per_page' );
    $total    = $wp_query->found_posts;
    $first    = ( $per_page * $paged ) - $per_page + 1;
    $last     = min( $total, $wp_query->get( 'posts_per_page' ) * $paged );
    $current = (get_query_var('paged')) ? get_query_var('paged') : 1; 

	// phpcs:disable WordPress.Security
	if ( 1 === intval( $total ) ) {
		_e( 'Single result', 'auriane' );
	} elseif ( $total <= $per_page || -1 === $per_page ) {
		/* translators: %d: total results */
		printf( _n( '%d product', '%d products', $total, 'auriane' ), $total );
	} else {
		$first = ( $per_page * $current ) - $per_page + 1;
		$last  = min( $total, $per_page * $current );
		/* translators: 1: first result 2: last result 3: total results */
		printf( _nx( 'Showing <span class="found-min">%1$d&ndash;</span>%2$d of %3$d result', 'Showing <span class="found-min">%1$d&ndash;</span class="found-total">%2$d <span class="result-total">of %3$d</span> results', $total, 'with first and last result', 'auriane' ), $first, $last, $total );
	}
}

/**
 * Get coupon display HTML.
 *
 * @param string|WC_Coupon $coupon Coupon data or code.
 */
function jws_wc_cart_totals_coupon_html( $coupon ) {
	if ( is_string( $coupon ) ) {
		$coupon = new WC_Coupon( $coupon );
	}

	$discount_amount_html = '';

	$amount               = WC()->cart->get_coupon_discount_amount( $coupon->get_code(), WC()->cart->display_cart_ex_tax );
	$discount_amount_html = '-' . wc_price( $amount );

	if ( $coupon->get_free_shipping() && empty( $amount ) ) {
		$discount_amount_html = __( 'Free shipping coupon', 'auriane' );
	}

	$discount_amount_html = apply_filters( 'woocommerce_coupon_discount_amount_html', $discount_amount_html, $coupon );
	$coupon_html          =   ' <a href="' . esc_url( add_query_arg( 'remove_coupon', rawurlencode( $coupon->get_code() ), wc_get_checkout_url()  ) ) . '" class="woocommerce-remove-coupon" data-coupon="' . esc_attr( $coupon->get_code() ) . '"><span>' . $coupon->get_code(). '</span></a>'.$discount_amount_html;

	echo wp_kses( apply_filters( 'woocommerce_cart_totals_coupon_html', $coupon_html, $coupon, $discount_amount_html ), array_replace_recursive( wp_kses_allowed_html( 'post' ), array( 'a' => array( 'data-coupon' => true ) ) ) ); // phpcs:ignore PHPCompatibility.PHP.NewFunctions.array_replace_recursiveFound
}




function jws_check_layout_shop() {
    global $jws_option;
    $value = array();
    
    // select-shop-top_filter //
    if(isset($jws_option['select-shop-top_filter'])) {
      $value['select-shop-top_filter'] = $jws_option['select-shop-top_filter'];  
    }
    
    // columns_review //
    
    if(isset($jws_option['columns_review'])) {
      $value['columns_review'] = $jws_option['columns_review'];  
    }
    
    
    
    // shop_pagination_layout //
    $layout = isset($jws_option['shop_pagination_layout']) ? $jws_option['shop_pagination_layout'] : 'number';
    if(isset($_GET['shop_layout']) && $_GET['shop_layout'] == '3') {
        $layout = 'loadmore';
    }elseif(isset($_GET['shop_layout']) && $_GET['shop_layout'] == '4') {
        $layout = 'infinity';
    }
    $value['shop_pagination_layout'] = $layout; 
    
    
    
    // fullwidth //
    if(isset($_GET['shop_layout']) && ($_GET['shop_layout'] == '2' || $_GET['shop_layout'] == '3' || $_GET['shop_layout'] == '4')) { 
      $fullwidth =  false; 
    }else {
      $fullwidth = (isset($jws_option['shop-fullwidth-switch']) && $jws_option['shop-fullwidth-switch']) ? $jws_option['shop-fullwidth-switch'] : false;  
    }
    $value['fullwidth']  = $fullwidth;
    
    
    
    // position //
    if(isset($_GET['layout']) && $_GET['layout']) { 
      $position = $_GET['layout']; 
    }else{
      $position = (isset($jws_option['shop_position_sidebar']) && $jws_option['shop_position_sidebar']) ? $jws_option['shop_position_sidebar'] : 'no_sidebar'; 
      
    }  
    $value['position']  = $position;
    
    
    
    
    // filter_layout //
    if(isset($_GET['filter_layout']) && $_GET['filter_layout']) { 
      $filter_layout = $_GET['filter_layout'];  
    }else{
      $filter_layout = (isset($jws_option['shop_click_filter']) && $jws_option['shop_click_filter']) ? $jws_option['shop_click_filter'] : 'bottom'; 
    }   
    $value['filter_layout']  = $filter_layout; 
    
    
    
    // class_wap //
    $value['class_wap']  = 'shop-container';
    if($fullwidth) {
       $value['class_wap'] .= ' no_container' ;
    }else {
       $value['class_wap'] .= ' container' ; 
    }
    if($position == 'no_sidebar' && $filter_layout != 'sideout') {
       $value['content_col'] = 'shop-content col-12'; 
       $value['sidebar_col'] = 'shop-sidebar sidebar_sticky ';
       $value['class_wap'] .= ' sidebar-no_sidebar'; 
    }else {
       $value['content_col'] = 'shop-content col-xl-9 col-lg-12 col-12';
       $value['sidebar_col'] = 'shop-sidebar col-xl-3 col-lg-12 col-12';
       $value['class_wap'] .= ' sidebar-has_sidebar';  
    }
    
    if($filter_layout == 'sideout') {
       $value['class_wap'] .= ' sidebar-sideout'; 
    } 
    
    
    
    
    
    $columns = (isset($jws_option['shop_columns']) && !empty($jws_option['shop_columns']) ) ? $jws_option['shop_columns'] : '3';
    $getlayout   = isset($_GET['lay_style']) ? $_GET['lay_style'] : $columns; 
    $value['shop_columns'] = $getlayout;
    
    
    
     
    
    $layout = (isset($jws_option['shop_layout']) && $getlayout != '1' ) ? $jws_option['shop_layout'] : 'layout1';
    if(isset($_GET['shop_layout']) && ($_GET['shop_layout'] == '2' || $_GET['shop_layout'] == '3')) {
      $layout = 'layout2';  
    }elseif(isset($_GET['shop_layout']) && $_GET['shop_layout'] == '4') {
      $layout = 'layout1';   
    }
    if($getlayout == '1') {
        $layout = '';
    }
    $value['layout'] = $layout; 
    
    
    

    return $value;
}

add_filter( 'woocommerce_cart_item_name', 'jws_quadlayers_product_image_checkout', 9999, 3 ); 
function jws_quadlayers_product_image_checkout ( $name, $cart_item, $cart_item_key ) {
 

    if ( ! is_checkout())  {return $name;}
    
    $product = $cart_item['data'];
    
    $thumbnail = $product->get_image( array( '50', '50' ), array( 'class' => 'alignleft' ) );
     
    return $thumbnail . $name;
    
}

function remove_image_zoom_support() {
    
    if(jws_theme_get_option('enable_zoom')==0){
        remove_theme_support( 'wc-product-gallery-zoom' );
    }
}


add_action( 'wp', 'remove_image_zoom_support', 100 );