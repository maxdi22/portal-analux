<?php



/*  Search Ajax   */
if ( ! function_exists( 'jws_ajax_suggestions_product' ) ) {
            	function jws_ajax_suggestions_product() {
                    
                    global $jws_option;
    
                    if(isset($jws_option['exclude-product-in-shop']) && !empty($jws_option['exclude-product-in-shop'])) {
                        $result = array_map('intval', array_filter($jws_option['exclude-product-in-shop'], 'is_numeric'));
                    }else{
                        $result = '';
                    }
                    
            		$query_args = array(
            			'posts_per_page' => $_REQUEST['number'],
            			'post_status'    => 'publish',
            			'post_type'      => 'product',
            			'no_found_rows'  => 1,
                        'post__not_in' => $result
                        
                        
            		);

            
            			
        			$post_visibility_term_ids = wc_get_product_visibility_term_ids();
        			$query_args['tax_query'][] = array(
        				'taxonomy' => 'product_visibility',
        				'field'    => 'term_taxonomy_id',
        				'terms'    => $post_visibility_term_ids['exclude-from-search'],
        				'operator' => 'NOT IN',
                        
        			);
        
        			if ( ! empty( $_REQUEST['product_cat'] ) ) {
        				$query_args['product_cat'] = strip_tags( $_REQUEST['product_cat'] );
        			}
            
            
            		if ( ! empty( $_REQUEST['query'] ) ) {
            			$query_args['s'] = sanitize_text_field( $_REQUEST['query'] );
            		}
            
            		if ( ! empty( $_REQUEST['number'] ) ) {
            			$query_args['posts_per_page'] = (int) $_REQUEST['number'];
            		}
            
            		$results = new WP_Query( apply_filters( 'jws_ajax_get_results', $query_args ) );
            
            		$suggestions = array();
            
            		if ( $results->have_posts() ) {
            
            		
            			$factory = new WC_Product_Factory();
            		
            
            			while ( $results->have_posts() ) {
            				$results->the_post();
            
            			
            					$post = $factory->get_product( get_the_ID() );
            					$suggestions[] = array(
            						'value' => get_the_title(),
            						'permalink' => get_the_permalink(),
            						'price' => $post->get_price_html(),
            						'thumbnail' => $post->get_image(),
            					);
            				
            			}
            
            			wp_reset_postdata();
            		} else {
            			$suggestions[] = array(
            				'value' => esc_html__( 'No products found', 'auriane' ),
            				'no_found' => true,
            				'permalink' => ''
            			);
            		}
            
            		echo json_encode( array(
            			'suggestions' => $suggestions
            		) );
            
            		die();
            	}
            
            	add_action( 'wp_ajax_jws_ajax_search_product', 'jws_ajax_suggestions_product', 10 );
            	add_action( 'wp_ajax_nopriv_jws_ajax_search_product', 'jws_ajax_suggestions_product', 10 );
}




add_action( 'wp_ajax_product_cars_filter_query', 'product_cars_filter_query' );
add_action( 'wp_ajax_nopriv_product_cars_filter_query', 'product_cars_filter_query' );
if ( ! function_exists( 'product_cars_filter_query' ) ) {
	/**
	 * Filter query
	 */
	function product_cars_filter_query() {

			$attributs = product_get_all_filters_with_ajax();
			echo wp_json_encode( $attributs );


		exit();
	}
}




if ( ! function_exists( 'product_get_all_filters_with_ajax' ) ) {
	/**
	 * Filter with ajax
	 */
	function product_get_all_filters_with_ajax() {

		// @codingStandardsIgnoreStart
		if ( isset( $_REQUEST['selected_attr'] ) && ! empty( $_REQUEST['selected_attr'] ) ) {
			$taxonomys = explode( ',', $_REQUEST['selected_attr'] );
		}


		$result_filter   = array();
	
	
		$args    = product_make_filter_wp_query( $_POST );


        $query = new WP_Query( $args );



		$args['fields']        = 'ids';
		$args['no_found_rows'] = true;
		$filter_query_args     = array_replace( $args, array( 'posts_per_page' => -1 ) );
		$tax_query_arry        = jws_set_tex_query_array( $taxonomys, $_POST );
		$filter_query          = new WP_Query( $filter_query_args );
		$tot_result            = $filter_query->post_count;
			if ( $filter_query->have_posts() ) {

			foreach ( $taxonomys as $tax ) {
				if (( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) )) {
					$tax_args = array(
						'orderby' => 'name',
						'order'   => 'ASC',
						'fields'  => 'all',
					);
					$terms    = wp_get_object_terms( $filter_query->posts, $tax, $tax_args );

					foreach ( $terms as $tdata ) {

						if ( $tdata->taxonomy === $tax ) {
						
                            $check_resul_filter[ $tax ][] = array(
                                $tdata->slug => $tdata->name,
			
							);
						}
					}
				}else {
				    // When not set any filter.
					$terms = get_terms(
						array(
							'taxonomy'   => $tax,
							'hide_empty' => true,
						)
					);
					foreach ( $terms as $tdata ) {

						$check_resul_filter[ $tax ][] = array(
							$tdata->slug => $tdata->name,
						);
					}
				}
			}
        
		}

	
			$data = array(
				'status'      => 'success',
				'all_filters' => $check_resul_filter,
                'check' => $check_resul_filter,
			);
	
		return $data;
	}
}


if ( ! function_exists( 'product_make_filter_wp_query' ) ) {
	/**
	 * Make filter wp query
	 *
	 * @param array $request_method .
	 */
	function product_make_filter_wp_query( $request_method ) {

		$tax_query_arry = array();

		$args  = array(
			'post_type'      => 'product',
			'post_status'    => array( 'publish', 'acf-disabled' ),
			'posts_per_page' => -1,
		);


        if ( isset( $request_method['selected_attr'] ) && ! empty( $request_method['selected_attr'] ) ) {
			$taxonomys = explode( ',', $request_method['selected_attr'] );
		}
		foreach ( $taxonomys as $tax ) {
			if ( isset( $request_method[ $tax ] ) && '' !== $request_method[ $tax ] ) {
				foreach ( $request_method as $key => $val ) {
					if ( $key === $tax ) {
                       	$tax_query_arry[] = array(
						'taxonomy' => $tax,
						'field'    => 'slug',
						'terms'    => array( $request_method[ $tax ] ),
					     );
					}
				}
			}
		}

		if ( isset( $tax_query_arry ) && ! empty( $tax_query_arry ) ) {

			$args['tax_query'] = array( 'relation' => 'AND' );

			foreach ( $tax_query_arry as $k => $val ) {
				$args['tax_query'][ $k ] = $val;
			}
		}


		return $args;
	}
}
