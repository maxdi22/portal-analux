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
class CarDealer_Helper_Widget_Product_Search extends WP_Widget {

    
   function __construct() {
		$args = array(
			'name'        => esc_html__( 'JWS Product Search', 'auriane' ),
			'description' => esc_html__( 'Add product search widget in product listing top widget area.', 'auriane' ),
			'classname'   => 'product_search',
            'customize_selective_refresh' => true,
		);

	   parent::__construct( 'product_search', __( 'JWS Product Search','auriane' ), $args );
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
	
        wp_enqueue_script('jquery-autocomplete');
		$widget_id = ! isset( $args['widget_id'] ) ? 1 : $args['widget_id'];
		echo ''.$args['before_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE

		if ( ! empty( $instance['title'] ) ) {
			echo ''.$args['before_title'] . apply_filters( 'widget_title', $instance['title'] ) . '' . $args['after_title']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
		}
        ?>
        <div class="aurianeduct-search">
            <form role="search" action="<?php echo esc_url( home_url( '/' ) ); ?>" method="get" class="search-form jws-ajax-search"  data-count="20" data-post_type="product" data-thumbnail="1" data-price="1">
                <button type="submit" class="searchsubmit"> <i aria-hidden="true" class="jws-icon-magnifying-glass-thin"></i> </button>
                <input type="text" class="s" placeholder="<?php echo esc_attr_x( 'Search product...', 'placeholder', 'auriane' ); ?>" value="" name="s" autocomplete="off">
                <input type="hidden" name="post_type" value="product">
                <span class="form-loader"></span>
            </form>
            <div class="jws-search-results"></div>
        </div>    
    	<?php


		echo ''.$args['after_widget']; // phpcs:ignore WordPress.XSS.EscapeOutput.OutputNotE
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		return $new_instance;
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		$title = ! empty( $instance['title'] ) ? $instance['title'] : esc_html__( '', 'auriane' );
		?>
		<p>
			<label for="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>"><?php esc_html_e( 'Title:', 'auriane' ); ?></label> 
			<input class="widefat" id="<?php echo esc_attr( $this->get_field_id( 'title' ) ); ?>" name="<?php echo esc_attr( $this->get_field_name( 'title' ) ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
		</p>
		<?php
	}
}

if(function_exists('insert_widgets')) {
    insert_widgets( 'CarDealer_Helper_Widget_Product_Search' );
}