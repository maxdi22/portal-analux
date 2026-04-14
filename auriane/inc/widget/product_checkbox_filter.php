<?php 
class jws_CHECKBOX_FILTER_class extends WP_Widget {


    
    function __construct() {
    parent::__construct(
      
    // Base ID of your widget
    'widget-filter-checkbox', 
      
    esc_html__( 'Jws Filter Product By Taxonomy', 'auriane' ),
      
    // Widget description
    array( 'description' => esc_html__( 'It displays Filter', 'auriane' ), ) 
    );
    }

	/**
	 * method to display in the admin
	 *
	 * @param $instance
	 */
	function form( $instance ) {
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => esc_html__( 'Type', 'auriane' ), // Legacy.
                'type' => 'car_type', // Legacy.

			)
		);

		extract( $instance );

		?>
		<p>
			<label
				for="<?php echo esc_attr( esc_attr( $this->get_field_id( 'title' ) ) ); ?>"> <?php esc_html_e( 'Title:',
					'auriane' ); ?></label>
			<input class="widefat" id="<?php echo esc_attr( esc_attr( $this->get_field_id( 'title' ) ) ); ?>"
			       name="<?php echo esc_attr( esc_attr( $this->get_field_name( 'title' ) ) ); ?>" type="text"
			       value="<?php if ( isset( $title ) ) {
				       echo esc_attr( $title );
			       } ?>">
		</p>

		      <label for="<?php echo esc_attr($this->get_field_id( 'type' )); ?> "><?php esc_html_e('Type Filter:','auriane'); ?></label>
            <select id="<?php echo esc_attr($this->get_field_id( 'type' )) ?>" name="<?php echo esc_attr($this->get_field_name( 'type' )); ?>">
                 <option <?php selected( $type, 'category' ); ?> value="category" ><?php echo esc_attr('category','auriane'); ?></option>
                 <option <?php selected( $type, 'brand' ); ?> value="brand" ><?php echo esc_attr('brand','auriane'); ?></option>
                 <option <?php selected( $type, 'features' ); ?> value="features" ><?php echo esc_attr('features','auriane'); ?></option>
            </select>
		</p>
		<?php
	}

	/**
	 * frontend for the site
	 *
	 * @param $args
	 * @param $instance
	 */
	function widget( $args, $instance ) {
		//default values
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => esc_html__( 'Categories', 'auriane' ), // Legacy.
                'type' => 'category', // Legacy.
			)
		);


		extract( $args );
		extract( $instance );
        $widget_id = $args;
   
		// Create a filter to the other plug-ins can change them
		$title         = sanitize_text_field( apply_filters( 'widget_title', $title ) );
		$before_widget = str_ireplace( 'class="widget"', 'class="widget widget-tag-cloud"', $before_widget );
		echo ''.$before_widget;
		echo ''.$before_title . esc_attr( $title ) . ''.$after_title;
		?>

		<div class="type checkbox">

			<?php

            if($type == 'brand') {
                $tax = 'product_brand';
              
            }elseif($type == 'category') {
                $tax = 'product_cat';
              
            }elseif($type == 'features') {
                $tax = 'product_features';
      
            }

            $args = array(
    			'orderby'    => 'name',
    			'order'      => 'ASC',
    			'hide_empty' => 1,
    			'pad_counts' => true,
    			'child_of'   => '0',
                'parent'     => '0',
    		);
            $cats  =  get_terms($tax,$args );
    
        
             $filter_name    = $type;

                 
            global $wp;
     
            $cat_current = get_queried_object_id();
         
			?>
   
			<ul class="product-categories">

				<?php
                $args = array();
              
      
				foreach ( $cats as $cat ) {
					if ( ! isset( $cat->name ) ) {
						continue;
					}
                    $id = $cat->term_id; 
                    $link = get_category_link( $id );
                    
                    if ( isset( $_GET['min_price'] ) ) {
            			$link = add_query_arg( 'min_price', wc_clean( $_GET['min_price'] ), $link );
            		}
            
            		if ( isset( $_GET['max_price'] ) ) {
            			$link = add_query_arg( 'max_price', wc_clean( $_GET['max_price'] ), $link );
            		}
                    
                    $link = add_parameter_after_custom_link($link);
                    
                    
					?>
             
                    <li class="cat-item cat-item-<?php echo esc_attr($id); if (in_array($cat->slug, $args) || ($cat_current == $id )) echo esc_attr(' current-cat'); ?>">
                        <a href="<?php echo esc_url( $link ); ?>"><?php echo esc_html( $cat->name ); ?></a>
                    </li>
                   
				
				<?php } ?>
			</ul> 
          
           
		</div>

		<?php
		echo  ''.$after_widget;
	}

	function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
    		$new_instance         = wp_parse_args( (array) $new_instance, array(
    			'title'    => '',
    			'type' => 'category',
    		) );
    		$instance['title']    = sanitize_text_field( $new_instance['title'] );
    		$instance['type'] = $new_instance['type'] ? $new_instance['type'] : 'category';
    		return $instance;
	}
}

function get_current_term_id() {
		return absint( is_tax() ? get_queried_object()->term_id : 0 );
}

if(function_exists('insert_widgets')) {
    insert_widgets( 'jws_CHECKBOX_FILTER_class' );
}