<?php

/**
 * WooCommerce wishlist functions
 *
 * @package jws
 */


if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! function_exists( 'jws_wishlist_shortcode' ) ) {
	/**
	 * WooCommerce wishlist page shortcode.
	 *
	 * @since 4.5
	 */
	function jws_wishlist_shortcode() {
		if ( !class_exists('Woocommerce') ) return;
		ob_start();
             jws_get_wishlistd_products_table(); 
             
		return ob_get_clean();
	}
   
   if(function_exists('insert_shortcode')) {
	   insert_shortcode( 'jws_wishlist', 'jws_wishlist_shortcode' ); 
   }
}

if ( ! function_exists( 'jws_add_to_wishlist' ) ) {
    
	/**
	 * Add product to comapre
	 *
	 * @since 4.5
	 */
	function jws_add_to_wishlist() {

		$id = sanitize_text_field( $_GET['id'] );

		$cookie_name = jws_wishlist_cookie_name();

		if ( jws_is_product_in_wishlist( $id ) ) {
			jws_wishlist_json_response();
		}

		$products = jws_get_wishlistd_products();

		$products[] = $id;

		setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );

		$_COOKIE[$cookie_name] = json_encode( $products );

		jws_wishlist_json_response();
	}

	add_action( 'wp_ajax_jws_add_to_wishlist', 'jws_add_to_wishlist' );
	add_action( 'wp_ajax_nopriv_jws_add_to_wishlist', 'jws_add_to_wishlist' );
}


if ( ! function_exists( 'jws_add_to_wishlist_btn' ) ) {
	/**
	 * Add product to comapre button
	 *
	 * @since 4.5
	 */
	function jws_add_to_wishlist_btn() {
	
        $class_added = '';
        $ids = jws_get_wishlistd_products();

        if(in_array(get_the_ID(), $ids)) {
            $class_added = ' added'; 
        }
		if(jws_theme_get_option('wishlist')) echo '<a href="' . esc_url( jws_get_wishlist_page_url() ) . '" class="jws-wishlist-btn'.esc_attr($class_added).'" data-id="' . esc_attr( get_the_ID() ) . '">
            <i class="jws-icon-heart-straight-light"></i>
            <span class="notadd">'.esc_html__('Add to wishlist','auriane').'</span>
            <span class="added">'.esc_html__('Browse Wishlist','auriane').'</span>
        </a>';
	}
}

if ( ! function_exists( 'jws_add_to_wishlist_single_btn' ) ) {
	/**
	 * Add product to comapre button on single product
	 *
	 * @since 4.4
	 */
	function jws_add_to_wishlist_single_btn() {
		global $product;
		echo '<a class="jws-wishlistsg-btn" data-added-text="' . esc_html__('Wishlist products', 'auriane') . '" data-id="' . esc_attr( $product->get_id() ) . '"><span class="jws-icon-heart-straight-thin"></span></a>';
	}
}


if ( ! function_exists( 'jws_wishlist_json_response' ) ) {
	/**
	 * Compare JSON reponse.
	 *
	 * @since 4.5
	 */
	function jws_wishlist_json_response() {
		$count = 0;
		$products = jws_get_wishlistd_products();

		ob_start();

		jws_get_wishlistd_products_table();

		$table_html = ob_get_clean();

		if ( is_array( $products ) ) {
			$count = count( $products );
		}

		wp_send_json( array(
			'count' => $count,
			'table' => $table_html,
		) );
	}
}

if ( ! function_exists( 'jws_get_wishlist_page_url' ) ) {
    
	/**
	 * Get wishlist page ID.
	 *
	 * @since 4.5
	 */
	function jws_get_wishlist_page_url() {
	    global $jws_option;
		$page_id = (isset($jws_option['wishlist_page'])) ? $jws_option['wishlist_page'] : '';
		return get_permalink( $page_id );
	}
}


if ( ! function_exists( 'jws_remove_from_wishlist' ) ) {
	/**
	 * Add product to comapre
	 *
	 * @since 4.5
	 */
	function jws_remove_from_wishlist() {

		$id = sanitize_text_field( $_GET['id'] );

		$cookie_name = jws_wishlist_cookie_name();

		if ( ! jws_is_product_in_wishlist( $id ) ) {
			jws_wishlist_json_response();
		}

		$products = jws_get_wishlistd_products();

		foreach ( $products as $k => $product_id ) {
			if ( intval( $id ) == $product_id ) {
				unset( $products[ $k ] );
			}
		}

		if ( empty( $products ) ) {
			setcookie( $cookie_name, false, 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[$cookie_name] = false;
		} else {
			setcookie( $cookie_name, json_encode( $products ), 0, COOKIEPATH, COOKIE_DOMAIN, false, false );
			$_COOKIE[$cookie_name] = json_encode( $products );
		}

		jws_wishlist_json_response();
	}

	add_action( 'wp_ajax_jws_remove_from_wishlist', 'jws_remove_from_wishlist' );
	add_action( 'wp_ajax_nopriv_jws_remove_from_wishlist', 'jws_remove_from_wishlist' );
}


if ( ! function_exists( 'jws_is_product_in_wishlist' ) ) {
	/**
	 * Is product in wishlist
	 *
	 * @since 4.5
	 */
	function jws_is_product_in_wishlist( $id ) {
		$list = jws_get_wishlistd_products();

		return in_array( $id, $list, true );
	}
}


if ( ! function_exists( 'jws_get_wishlist_count' ) ) {
	/**
	 * Get wishlist number.
	 *
	 * @since 4.5
	 */
	function jws_get_wishlist_count() {
		$count = 0;
		$products = jws_get_wishlistd_products();

		if ( is_array( $products ) ) {
			$count = count( $products );
		}

		return $count;
	}
}


if ( ! function_exists( 'jws_wishlist_cookie_name' ) ) {
	/**
	 * Get wishlist cookie namel.
	 *
	 * @since 4.5
	 */
	function jws_wishlist_cookie_name() {
		$name = 'jws_wishlist_list';

        if ( is_multisite() ) $name .= '_' . get_current_blog_id();

        return $name;

	}
}


if ( ! function_exists( 'jws_get_wishlistd_products' ) ) {
	/**
	 * Get wishlistd products IDs array
	 *
	 * @since 4.5
	 */
	function jws_get_wishlistd_products() {
		$cookie_name = jws_wishlist_cookie_name();
        return isset( $_COOKIE[ $cookie_name ] ) ? json_decode( wp_unslash( $_COOKIE[ $cookie_name ] ), true ) : array();
	}
}

if ( ! function_exists( 'jws_is_products_have_field' ) ) {
	/**
	 * Checks if the products have such a field.
	 *
	 * @since 3.4
	 */
	function jws_is_products_have_field( $field_id, $products ) {
		foreach ( $products as $product_id => $product ) {
			if ( isset( $product[ $field_id ] ) && ( ! empty( $product[ $field_id ] ) && '-' !== $product[ $field_id ] && 'N/A' !== $product[ $field_id ] ) ) {
				return true;
			}
		}

		return false;
	}
}

if (isset( $_REQUEST['multiple-item-to-cart'] ) ) {
  add_action( 'wp_loaded', 'add_multiple_to_cart_action', 20 );   
}

function add_multiple_to_cart_action() {

    wc_nocache_headers();

    $product_ids        = apply_filters( 'woocommerce_add_to_cart_product_id', wp_unslash( $_REQUEST['multiple-item-to-cart'] ) ); // phpcs:ignore WordPress.Security.NonceVerification.NoNonceVerification
    $product_ids = explode( '|', $product_ids );
    if( ! is_array( $product_ids ) ) return;

    $product_ids = array_map( 'absint', $product_ids );
    $was_added_to_cart = false;
    $last_product_id = end($product_ids);
    //stop re-direction
    add_filter( 'woocommerce_add_to_cart_redirect', '__return_false' );
    foreach ($product_ids as $index => $product_id ) {
        $product_id = absint(  $product_id  );
        if( empty( $product_id ) ) continue;
        $_REQUEST['add-to-cart'] = $product_id;
        if( $product_id === $last_product_id ) {

            add_filter( 'option_woocommerce_cart_redirect_after_add', function() { 
                return 'yes'; 
            } );
        } else {
            add_filter( 'option_woocommerce_cart_redirect_after_add', function() { 
                return 'no'; 
            } );
        }

        WC_Form_Handler::add_to_cart_action();
    }
}


if ( ! function_exists( 'jws_get_wishlistd_products_table' ) ) {
	/**
	 * Get wishlistd products data table HTML
	 *
	 * @since 4.5
	 */
	function jws_get_wishlistd_products_table() {
		$products = jws_get_wishlistd_products_data();
		$fields = jws_get_wishlist_fields();
        global $jws_option;
		$empty_wishlist_text = $jws_option['empty_wishlist_text'];

		?>
		<div class="jws-wishlist-table">
			<?php
			if ( ! empty( $products ) ) {
				array_unshift( $products, array() );
				foreach ( $fields as $field_id => $field ) {
					if ( ! jws_is_products_have_field( $field_id, $products ) ) {
						continue;
					}
					?>

						<table class="jws-wishlist-row">
                            <thead>
                    			<tr>
                                    <th class="title_wishlist"></th>
                    				<th class="title_wishlist"><?php echo esc_html__('Product Detail','auriane'); ?></th>
                                    <th class="title_wishlist"><?php echo esc_html__('Price','auriane'); ?></th>
                    				<th class="title_wishlist"><?php echo esc_html__('Stock Status','auriane'); ?></th>
                                    <th class="title_wishlist"></th>
                    			</tr>
                			</thead>
                            <tbody>
    							<?php $all_id = array(); foreach ( $products as $product_id => $product) :   ?>
    								<?php if ( ! empty( $product ) ) : $all_id[] = $product['id']; ?>
    									<tr class="jws-wishlist-col">
    										<?php jws_wishlist_display_field( $field_id, $product ); ?>
    									</tr>
    								<?php endif; ?>
    
    							<?php endforeach ?>
                            </tbody>
						</table>
                        <div class="add_all_product">
                            <?php 
                                $product_ids = implode('|', $all_id) ;
                                $add_to_cart_url = add_query_arg( 'multiple-item-to-cart', $product_ids );
                                if(isset($jws_option['shop_layout']) && $jws_option['shop_layout'] != 'rent') { ?>
                                    <a href="<?php echo esc_url_raw( $add_to_cart_url); ?>" data-product_id="<?php echo esc_attr($product_ids); ?>"><?php esc_html_e('Add all to cart', 'auriane'); ?></a>
                                <?php } ?>
                        </div>
					<?php
				}	
			} else {
				?>
					<h4 class="jws-empty-wishlist">
						<?php esc_html_e('Wishlist is empty.', 'auriane'); ?>
					</h4>
					<?php if ( $empty_wishlist_text ) : ?>
						<div class="jws-empty-page-text"><?php echo wp_kses( $empty_wishlist_text, array('p' => array(), 'h1' => array(), 'h2' => array(), 'h3' => array(), 'strong' => array(), 'em' => array(), 'span' => array(), 'div' => array() , 'br' => array()) ); ?></div>
					<?php endif; ?>
					<p class="return-to-shop">
						<a class="button" href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>">
							<?php esc_html_e( 'Return to shop', 'auriane' ); ?>
						</a>
					</p>
				<?php
			}

			?>
			<div class="wishlist-loader"></div>
		</div>
		<?php
	}
}


if ( ! function_exists( 'jws_get_wishlist_fields' ) ) {
	/**
	 * Get wishlist fields data.
	 *
	 * @since 4.5
	 */
	function jws_get_wishlist_fields() {
		$fields = array(
			'basic' => ''
		);

		return $fields;
	}
}


if ( ! function_exists( 'jws_wishlist_display_field' ) ) {
	/**
	 * Get wishlist fields data.
	 *
	 * @since 4.5
	 */
	function jws_wishlist_display_field( $field_id, $product ) {
        global $jws_option;
		$type = $field_id;

		if ( 'pa_' === substr( $field_id, 0, 3 ) ) {
			$type = 'attribute';
		}
		
		switch ( $type ) {
			case 'basic':
                    echo '<td><a href="#" class="jws-wishlist-remove" data-id="' . esc_attr( $product['id'] ) . '"><span class="jws-icon-cross"></span></a></td>';
					echo '<td class="jws_wishlist_detail">
                           <a class="product-image" href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['image']. '</a>
					      <div class="product-title"><a href="' . get_permalink( $product['id'] ) . '">' . $product['basic']['title']. '</a></div>
                    </td>';
					echo '<td class="price">
    						'.wp_kses( $product['basic']['price'],'' ).'
                            '.$product['basic']['rating'].'
					</td>';
                    echo '<td class="stock">
						'.wp_kses( $product['basic']['stock'],'' ).'
					</td>';
                    echo '<td class="wishlist_action">
                        '.apply_filters( 'jws_wishlist_add_to_cart_btn', $product['basic']['add_to_cart'] ).'
                    </td>';				
			break;

			case 'attribute':

					echo wp_kses( $product[ $field_id ],'' );
			
				break;

			case 'weight':
				if ( $product[ $field_id ] ) {
					$unit = $product[ $field_id ] !== '-' ? $jws_option['woocommerce_weight_unit'] : '';
					echo wc_format_localized_decimal( $product[ $field_id ] ) . ' ' . esc_attr( $unit );
				} 
				break;

			default:
				echo wp_kses( $product[ $field_id ],'' );
				break;
		}
	}
}


if ( ! function_exists( 'jws_get_wishlistd_products_data' ) ) {
	/**
	 * Get wishlistd products data
	 *
	 * @since 4.5
	 */
	function jws_get_wishlistd_products_data() {
		$ids = jws_get_wishlistd_products();

		if ( empty( $ids ) ) {
			return array();
		}

		$args = array(
			'include' => $ids,
		);

		$products = wc_get_products( $args );

		$products_data = array();

		$fields = jws_get_wishlist_fields();

		$fields = array_filter( $fields, function(  $field ) {
			return 'pa_' === substr( $field, 0, 3 );
		}, ARRAY_FILTER_USE_KEY );

		$divider = '-';

		foreach ( $products as $product ) {
			$rating_count = $product->get_rating_count();
			$average = $product->get_average_rating();

			$products_data[ $product->get_id() ] = array(
				'basic' => array(
					'title' => $product->get_title() ? $product->get_title() : $divider,
					'image' => $product->get_image() ? $product->get_image() : $divider,
					'rating' => wc_get_rating_html_compare( $average, $rating_count ),
					'price' => $product->get_price_html() ? $product->get_price_html() : $divider,
                    'stock' => jws_wishlist_availability_html( $product ),
					'add_to_cart' => jws_wishlist_add_to_cart_html( $product ) ? jws_wishlist_add_to_cart_html( $product ) :$divider,
				),
				'id' => $product->get_id(),

			);

			foreach ( $fields as $field_id => $field_name ) {
				if ( taxonomy_exists( $field_id ) ) {
					$products_data[ $product->get_id() ][ $field_id ] = array();
					$terms = get_the_terms( $product->get_id(), $field_id );
					if ( ! empty( $terms ) ) {
						foreach ( $terms as $term ) {
							$term = sanitize_term( $term, $field_id );
							$products_data[ $product->get_id() ][ $field_id ][] = $term->name;
						}
					} else {
						$products_data[ $product->get_id() ][ $field_id ][] = '-';
					}
					$products_data[ $product->get_id() ][ $field_id ] = implode( ', ', $products_data[ $product->get_id() ][ $field_id ] );
				}
			}
		}

		return $products_data;
	}
}

if ( ! function_exists( 'jws_wishlist_availability_html' ) ) {
	/**
	 * Get product availability html.
	 *
	 * @since 4.5
	 */
	function jws_wishlist_availability_html( $product ) {
		$html         = '';
		$availability = $product->get_availability();

		if( empty( $availability['availability'] ) ) {
			$availability['availability'] = esc_html__( 'In stock', 'auriane' );
		}

		if ( ! empty( $availability['availability'] ) ) {
			ob_start();

			wc_get_template( 'single-product/stock.php', array(
				'product'      => $product,
				'class'        => $availability['class'],
				'availability' => $availability['availability'],
			) );

			$html = ob_get_clean();
		}

		return apply_filters( 'woocommerce_get_stock_html', $html, $product );
	}
}

if ( ! function_exists( 'jws_wishlist_add_to_cart_html' ) ) {
	/**
	 * Get product add to cart html.
	 *
	 * @since 4.5
	 */
	function jws_wishlist_add_to_cart_html( $product ) {
	    global $jws_option;   
		if (!$product ) return;

		$defaults = array(
			'quantity'   => 1,
			'class'      => implode( ' ', array_filter( array(
				'button',
				'product_type_' . $product->get_type(),
				$product->is_purchasable() && $product->is_in_stock() ? 'add_to_cart_button' : '',
				$product->supports( 'ajax_add_to_cart' ) && $product->is_purchasable() && $product->is_in_stock() ? 'ajax_add_to_cart' : '',
			) ) ),
			'attributes' => array(
				'data-product_id'  => $product->get_id(),
				'data-product_sku' => $product->get_sku(),
				'aria-label'       => $product->add_to_cart_description(),
				'rel'              => 'nofollow',
			),
		);

		$args = apply_filters( 'woocommerce_loop_add_to_cart_args', $defaults, $product );

		if ( isset( $args['attributes']['aria-label'] ) ) {
			$args['attributes']['aria-label'] = strip_tags( $args['attributes']['aria-label'] );
		}


		return apply_filters( 'woocommerce_loop_add_to_cart_link', 
			sprintf( '<a href="%s" data-quantity="%s" class="%s add-to-cart-loop" %s><span>%s</span></a>',
				esc_url( $product->add_to_cart_url() ),
				esc_attr( isset( $args['quantity'] ) ? $args['quantity'] : 1 ),
				esc_attr( isset( $args['class'] ) ? $args['class'] : 'button' ),
				isset( $args['attributes'] ) ? wc_implode_html_attributes( $args['attributes'] ) : '',
				esc_html( $product->add_to_cart_text() )
			),
		$product, $args );
	}
}

