<?php 
/**
 * Adds Cardealer Helpert Widget Cars Filters.
 *
 * @package car-dealer-helper/functions
 * @version 1.0.0
 */

/**
 * Cardealer Helpert Widget Cars Filters.
 */
class CarDealer_Helper_Widget_Product_Filters extends WC_Widget {


    /**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_layered_nav woocommerce-widget-layered-nav';
		$this->widget_description = __( 'Display a list of attributes to filter products in your store.','auriane' );
		$this->widget_id          = 'product_filters_with_cars';
		$this->widget_name        = __( 'JWS Product filter with cars attr','auriane' );
		parent::__construct();
	}
    	/**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget->update
	 *
	 * @param array $new_instance New Instance.
	 * @param array $old_instance Old Instance.
	 *
	 * @return array
	 */
	public function update( $new_instance, $old_instance ) {
		$this->init_settings();
		return parent::update( $new_instance, $old_instance );
	}

	/**
	 * Outputs the settings update form.
	 *
	 * @see WP_Widget->form
	 *
	 * @param array $instance Instance.
	 */
	public function form( $instance ) {
		$this->init_settings();
		parent::form( $instance );
	}

	/**
	 * Init settings after post types are registered.
	 */
	public function init_settings() {
		$attribute_array      = array();
		$std_attribute        = '';
		$attribute_taxonomies = wc_get_attribute_taxonomies();
		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					$attribute_array[ $tax->attribute_name ] = $tax->attribute_name;
				}
			}
			$std_attribute = current( $attribute_array );
		}

		$this->settings = array(
			'title'        => array(
				'type'  => 'text',
				'std'   => __( 'Filter by','auriane' ),
				'label' => __( 'Title','auriane' ),
			),
			'make'    => array(
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Make','auriane' ),
				'options' => $attribute_array,
			),
            'model'    => array(
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Model','auriane' ),
				'options' => $attribute_array,
			),
            'year'    => array(
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Year','auriane' ),
				'options' => $attribute_array,
			),
            'body'    => array(
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Body','auriane' ),
				'options' => $attribute_array,
			),
            'engines'    => array(
				'type'    => 'select',
				'std'     => $std_attribute,
				'label'   => __( 'Engines','auriane' ),
				'options' => $attribute_array,
			),

		);
	}
	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {
	

		$widget_id = ! isset( $args['widget_id'] ) ? 1 : $args['widget_id'];
		echo ''.$args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

		if ( ! empty( $instance['title'] ) ) {
			echo''. $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . '' . ''.$args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
       
        $query_type         = $this->get_instance_query_type( $instance );
        

        $attr = array();
        if(isset($instance['make']) && !empty($instance['make'])) {
            $attr[] = $instance['make'];
        }
        if(isset($instance['model']) && !empty($instance['model'])) {
            $attr[] = $instance['model'];
        }
        if(isset($instance['year']) && !empty($instance['year'])) {
            $attr[] = $instance['year'];
        }
        if(isset($instance['body']) && !empty($instance['body'])) {
            $attr[] = $instance['body'];
        }
        if(isset($instance['engines']) && !empty($instance['engines'])) {
            $attr[] = $instance['engines'];
        }
        $array = array_unique($attr);
        
        global $wp;
        
        
        if(!is_shop() || !is_tax()) {
            $form_action = get_permalink( wc_get_page_id( 'shop' ) );
        }else{
    		if ( '' === get_option( 'permalink_structure' ) ) {
    			$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
    		} else {
    			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
    		}
        }
        
       
                    ?>
                    <div class="form-filter-product-cars">
                        <div class="tabs-nav">
                            <div class="container">
                				<span data-tabs="tab1" class="active"><?php echo esc_html__('Shop By Vehicle','auriane'); ?></span>
                				<span data-tabs="tab2"><?php echo esc_html__('Shop By VIN','auriane'); ?></span>
                				<span data-tabs="tab3"><?php echo esc_html__('Shop By Parts','auriane'); ?></span>
                            </div>
            			</div>
                        <div class="container">
                        <div id="tab1" class="tabcontent active">
                        <form class="aurianeduct-filter-attr" method="get" action="<?php echo esc_url( $form_action ); ?>">
                            	<div class="cdhl-vehicle-filters row">
                        			<?php
                                        $exlude_array = array();
                           
                                        foreach($array as $value) {
                                 
                                            $taxonomy           = $this->get_instance_taxonomy( $value );
                                            $taxonomy_filter_name = wc_attribute_taxonomy_slug( $taxonomy );
                                            
                                            $terms = get_terms( $taxonomy, array( 'hide_empty' => '1' ) );
                                            echo '<div class="col-xl-2 col-lg-6 col-12">';
                                            
                                            $this->layered_nav_dropdown( $terms, $taxonomy, $query_type );
                                         
                                            echo '</div>';
                                            
                                            $exlude_array[] = 'filter_' . $taxonomy_filter_name;
                                            
                                        }
                                       
                        
			                             echo wc_query_string_form_fields( null, $exlude_array, '', true );
                        			
                        			?>
                                    <div class="col-xl-2 col-lg-6 col-12"><label class="opacity0"><?php echo esc_html__( 'Submit','auriane' ); ?></label><button class="product-submit-btn" type="submit"><?php echo esc_attr__( 'Search','auriane' ); ?></button></div>
                                  
                        		</div>
                        </form>        
                        </div>
                	    <div id="tab2" class="tabcontent">
                            <form class="aurianeduct-filter-attr" method="get" action="<?php echo esc_url( $form_action ); ?>">
                             <label><?php echo esc_html__( 'Enter VIN','auriane' ); ?></label>
                            <div class="sort-filters">
                                <div class="search-field">
                                    <input type="text" data-id="filter_vin-number" class="aurianeduct_vin_number" value="<?php echo isset($_GET['filter_vin-number']) ? $_GET['filter_vin-number'] : ''; ?>" placeholder="--<?php echo esc_attr__('Search by VIN','auriane'); ?>--" name="filter_vin-number"> 
                                </div>
                                <?php echo wc_query_string_form_fields( null, array('filter_vin-number') , '', true ); ?>
                               <button class="search-vin" type="submit"><?php echo esc_html__('Search','auriane'); ?></button>                  
                			</div>
                            </form>
                        </div>
                        <div id="tab3" class="tabcontent">
                            <form class="aurianeduct-filter-attr" method="get" action="<?php echo esc_url( $form_action ); ?>">
                            <label><?php echo esc_html__( 'Enter part number','auriane' ); ?></label>
                            <div class="sort-filters">
                                <div class="search-field">
                                <input type="text" data-id="filter_part-number" value="<?php echo isset($_GET['filter_part-number']) ? $_GET['filter_part-number'] : ''; ?>" class="aurianeduct_part_number" placeholder="--<?php echo esc_attr__('Search by Part Number','auriane'); ?>--" name="filter_part-number"> 
                                </div>
                                <?php echo wc_query_string_form_fields( null, array('filter_part-number') , '', true ); ?>
                                <button class="search-part" type="submit"><?php echo esc_html__('Search','auriane'); ?></button>                  
                			</div>
                            </form>  
                        </div>
                        </div>
                        </div>
            		<?php


		echo ''.$args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}
    
    protected function get_instance_query_type( $instance ) {
		return  'and';
	}
    	protected function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
	}
    	/**
	 * Get this widgets taxonomy.
	 *
	 * @param array $instance Array of instance options.
	 * @return string
	 */
	protected function get_instance_taxonomy( $instance ) {
		if ( isset( $instance ) ) {
			return wc_attribute_taxonomy_name( $instance);
		}

		$attribute_taxonomies = wc_get_attribute_taxonomies();
   

		if ( ! empty( $attribute_taxonomies ) ) {
			foreach ( $attribute_taxonomies as $tax ) {
		
				if ( taxonomy_exists( wc_attribute_taxonomy_name( $tax->attribute_name ) ) ) {
					return wc_attribute_taxonomy_name( $tax->attribute_name );
				}
			}
		}

		return '';
	}
    protected function get_current_taxonomy() {
		return is_tax() ? get_queried_object()->taxonomy : '';
	}
    	/**
	 * Wrapper for WC_Query::get_main_tax_query() to ease unit testing.
	 *
	 * @since 4.4.0
	 * @return array
	 */
	protected function get_main_tax_query() {
		return WC_Query::get_main_tax_query();
	}

	/**
	 * Wrapper for WC_Query::get_main_search_query_sql() to ease unit testing.
	 *
	 * @since 4.4.0
	 * @return string
	 */
	protected function get_main_search_query_sql() {
		return WC_Query::get_main_search_query_sql();
	}

	/**
	 * Wrapper for WC_Query::get_main_search_queryget_main_meta_query to ease unit testing.
	 *
	 * @since 4.4.0
	 * @return array
	 */
	protected function get_main_meta_query() {
		return WC_Query::get_main_meta_query();
	}
    	/**
	 * Count products within certain terms, taking the main WP query into consideration.
	 *
	 * This query allows counts to be generated based on the viewed products, not all products.
	 *
	 * @param  array  $term_ids Term IDs.
	 * @param  string $taxonomy Taxonomy.
	 * @param  string $query_type Query Type.
	 * @return array
	 */
	protected function get_filtered_term_product_counts( $term_ids, $taxonomy, $query_type ) {
		global $wpdb;

		$tax_query  = $this->get_main_tax_query();
		$meta_query = $this->get_main_meta_query();

		if ( 'or' === $query_type ) {
			foreach ( $tax_query as $key => $query ) {
				if ( is_array( $query ) && $taxonomy === $query['taxonomy'] ) {
					unset( $tax_query[ $key ] );
				}
			}
		}

		$meta_query     = new WP_Meta_Query( $meta_query );
		$tax_query      = new WP_Tax_Query( $tax_query );
		$meta_query_sql = $meta_query->get_sql( 'post', $wpdb->posts, 'ID' );
		$tax_query_sql  = $tax_query->get_sql( $wpdb->posts, 'ID' );
		$term_ids_sql   = '(' . implode( ',', array_map( 'absint', $term_ids ) ) . ')';

		// Generate query.
		$query           = array();
		$query['select'] = "SELECT COUNT( DISTINCT {$wpdb->posts}.ID ) AS term_count, terms.term_id AS term_count_id";
		$query['from']   = "FROM {$wpdb->posts}";
		$query['join']   = "
			INNER JOIN {$wpdb->term_relationships} AS term_relationships ON {$wpdb->posts}.ID = term_relationships.object_id
			INNER JOIN {$wpdb->term_taxonomy} AS term_taxonomy USING( term_taxonomy_id )
			INNER JOIN {$wpdb->terms} AS terms USING( term_id )
			" . $tax_query_sql['join'] . $meta_query_sql['join'];

		$query['where'] = "
			WHERE {$wpdb->posts}.post_type IN ( 'product' )
			AND {$wpdb->posts}.post_status = 'publish'
			{$tax_query_sql['where']} {$meta_query_sql['where']}
			AND terms.term_id IN $term_ids_sql";

		$search = $this->get_main_search_query_sql();
		if ( $search ) {
			$query['where'] .= ' AND ' . $search;
		}

		$query['group_by'] = 'GROUP BY terms.term_id';
		$query             = apply_filters( 'woocommerce_get_filtered_term_product_counts_query', $query );
		$query_sql         = implode( ' ', $query );

		// We have a query - let's see if cached results of this query already exist.
		$query_hash = md5( $query_sql );

		// Maybe store a transient of the count values.
		$cache = apply_filters( 'woocommerce_layered_nav_count_maybe_cache', true );
		if ( true === $cache ) {
			$cached_counts = (array) get_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ) );
		} else {
			$cached_counts = array();
		}

		if ( ! isset( $cached_counts[ $query_hash ] ) ) {
			// phpcs:ignore WordPress.DB.PreparedSQL.NotPrepared
			$results                      = $wpdb->get_results( $query_sql, ARRAY_A );
			$counts                       = array_map( 'absint', wp_list_pluck( $results, 'term_count', 'term_count_id' ) );
			$cached_counts[ $query_hash ] = $counts;
			if ( true === $cache ) {
				set_transient( 'wc_layered_nav_counts_' . sanitize_title( $taxonomy ), $cached_counts, DAY_IN_SECONDS );
			}
		}

		return array_map( 'absint', (array) $cached_counts[ $query_hash ] );
	}
    protected function layered_nav_dropdown( $terms, $taxonomy, $query_type ) {

		$found = false;

		if ( $taxonomy !== $this->get_current_taxonomy() ) {
		     if(!is_shop() || !is_tax()) { 
		      $term_counts          = 1;
		     }else {
		      $term_counts          = $this->get_filtered_term_product_counts( wp_list_pluck( $terms, 'term_id' ), $taxonomy, $query_type );
		     }   
			
			$_chosen_attributes   = WC_Query::get_layered_nav_chosen_attributes();
			$taxonomy_filter_name = wc_attribute_taxonomy_slug( $taxonomy );
			$taxonomy_label       = wc_attribute_label( $taxonomy );

			/* translators: %s: taxonomy name */
			$any_label      = apply_filters( 'woocommerce_layered_nav_any_label', sprintf( __( '--All %s--','auriane' ), $taxonomy_label ), $taxonomy_label, $taxonomy );
			$multiple       = 'or' === $query_type;
			$current_values = isset( $_chosen_attributes[ $taxonomy ]['terms'] ) ? $_chosen_attributes[ $taxonomy ]['terms'] : array();


           echo '<label>'.esc_html__('Select ','auriane').esc_html($taxonomy_label).'</label>';
			echo '<select id="filter_pa_' . esc_attr( $taxonomy_filter_name ) . '" data-id="pa_' . esc_attr( $taxonomy_filter_name ) . '" class="product-filter-select box_pa_' . esc_attr( $taxonomy_filter_name ) . '"' . ( $multiple ? 'multiple="multiple"' : '' ) . ' data-tax="' . esc_html__('All ','auriane').esc_html( $taxonomy_label ) . '">';
			echo '<option value>' . esc_html( $any_label ) . '</option>';

			foreach ( $terms as $term ) {

				

				// Get count based on current view.
				$option_is_set = in_array( $term->slug, $current_values, true );
				$count         = isset( $term_counts[ $term->term_id ] ) ? $term_counts[ $term->term_id ] : 0;

				// Only show options with count > 0.
				if ( 0 < $count ) {
					$found = true;
				} 

				echo '<option value="' . esc_attr( urldecode( $term->slug ) ) . '" ' . selected( $option_is_set, true, false ) . '>' . esc_html( $term->name ) . '</option>';
			}

			echo '</select>';
               echo '<input type="hidden" class="value_pa_' . esc_attr( $taxonomy_filter_name ) . '" name="filter_' . esc_attr( $taxonomy_filter_name ) . '" value="' . esc_attr( implode( ',', $current_values ) ) . '" />';

		}

		return $found;
	}

}

if(function_exists('insert_widgets')) {
    insert_widgets( 'CarDealer_Helper_Widget_Product_Filters' );
}