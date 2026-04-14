<?php 
class JWS_PRODUCT_CATEGORY_class extends WC_Widget {

	function __construct() {
	   
      
       
       
        
        $this->widget_cssclass    = 'widget-aurianeduct-category';
		$this->widget_description = __( 'It Displays Product Category List', 'auriane' );
		$this->widget_id          = 'widget-aurianeduct-category';
		$this->widget_name        = __( 'JWS Product Category List', 'auriane' );


		parent::__construct();
	}
    /**
	 * Updates a particular instance of a widget.
	 *
	 * @see WP_Widget->update
	 *
	 * @param array $new_instance
	 * @param array $old_instance
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
	 * @param array $instance
	 */
	public function form( $instance ) {
		$this->init_settings();
		parent::form( $instance );
	}
	/**
	 * Init settings after post types are registered.
	 */
	public function init_settings() {
	 	$product_cat = array();

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$product_categories = get_terms( 'product_cat', $cat_args );

		if ( ! empty( $product_categories ) ) {

			foreach ( $product_categories as $key => $category ) {
				$product_cat[ $category->term_id ] = $category->name;
			}
		}

	   $this->settings           = array(
			'title'  => array(
				'type'  => 'text',
				'std'   => __( 'Product categories', 'auriane' ),
				'label' => __( 'Title', 'auriane' ),
			),
            'hide_empty' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Hide empty categories', 'auriane' ),
			),
			'show_parent_only' => array(
				'type'  => 'checkbox',
				'std'   => 0,
				'label' => __( 'Only show parent category', 'auriane' ),
			),
            'hidden_category' => array(
				'type'  => 'select',
				'std'     => 'name',
				'label'   => __( 'Hidden category default','auriane' ),
				'options'   => $product_cat
			),
		);
	}
        
	/**
	 * frontend for the site
	 *
	 * @param $args
	 * @param $instance
	 */
	function widget( $args, $instance ) {

		extract( $args );
		extract( $instance );
        $widget_id = $args;
   

      
        $title  = sanitize_text_field( apply_filters( 'widget_title', $title ) ); 

		$before_widget = str_ireplace( 'class="widget"', 'class="widget widget-tag-cloud"', $before_widget );
		echo ''.$before_widget . "";

        $atts = array(
				'limit'      => '-1',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'columns'    => '4',
				'ids'        => '',
		);
        $parent = '';
        if($show_parent_only) {
          $parent = 0;  
        }  

		$ids        = array_filter( array_map( 'trim', explode( ',', $atts['ids'] ) ) );


		// Get terms and workaround WP bug with parents/pad counts.
		$args = array(
			'orderby'    => $atts['orderby'],
			'order'      => $atts['order'],
			'hide_empty' => $hide_empty,
			'include'    => $ids,
			'pad_counts' => true,
            'exclude' => array( $hidden_category ),
      
		);

		$product_categories = apply_filters(
			'woocommerce_product_categories',
			get_terms( 'product_cat', $args )
		);

		if ( '' !== $parent ) {
			$product_categories = wp_list_filter(
				$product_categories,
				array(
					'parent' => $parent
				)
			);
		}

		if ( $hide_empty ) {
			foreach ( $product_categories as $key => $category ) {
				if ( 0 === $category->count ) {
					unset( $product_categories[ $key ] );
				}
			}
		}

		$atts['limit'] = '-1' === $atts['limit'] ? null : intval( $atts['limit'] );
		if ( $atts['limit'] ) {
			$product_categories = array_slice( $product_categories, 0, $atts['limit'] );
		}
        
        
		?>

		<div class="aurianeduct-category-list">
           <div class="container">
               <?php 
                    if(!empty($title)) {
                       echo ''.$before_title . esc_attr( $title ) . ''. $after_title; 
                    }
                    if ( $product_categories ) {
                           ?><ul class="ct_ul_ol" data-slick='{"slidesToShow":9 ,"slidesToScroll": 1, "infinite" : false, "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": 5}},{"breakpoint": 767,"settings":{"slidesToShow": 3}},{"breakpoint": 480,"settings":{"slidesToShow": 2}}]}'>
                                <?php foreach ( $product_categories as $category ) {
                                   ?>
                                       <li class="slick-slide">
                                            <a href="<?php echo get_term_link( $category->term_id, 'product_cat' );  ?>">
                                                <?php echo wp_get_attachment_image( get_term_meta( $category->term_id, 'thumbnail_id', 1 ), 'full' ); ?>
                                                <h3><?php echo esc_html($category->name); ?></h3>
                                            </a>
                                       </li>
                                   <?php	
                    			}
                            ?></ul><?php
            		}
               ?>
           </div> 
		</div>

		<?php
		echo ''.$after_widget;
	}

}


if(function_exists('insert_widgets')) {
    insert_widgets( 'JWS_PRODUCT_CATEGORY_class' );
}