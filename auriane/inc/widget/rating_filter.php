<?php 
class jws_RATING_FILTER_class extends WP_Widget {

	function __construct() {
		$args = array(
			'name'        => esc_html__( 'Jws Rating filter', 'auriane' ),
			'description' => esc_html__( 'It displays Filter rating', 'auriane' ),
			'classname'   => 'widget-jws-filter-rating'
		);
		parent::__construct( '', '', $args );

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
                'layout' => 'checkbox', // Legacy.

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

		<?php
	}


     
	function widget( $args, $instance ) {
		//default values
		$instance = wp_parse_args(
			(array) $instance,
			array(
				'title'       => esc_html__( 'PRICE', 'auriane' ), // Legacy.
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

		<div class="checkbox">
        <?php
             global $wp;
                
    		if ( '' === get_option( 'permalink_structure' ) ) {
    			$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
    		} else {
    			$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
    		}
    		?>
            <form method="get" action="<?php echo esc_url( $form_action ); ?>">
                    <input name="rating" type="hidden" class="file_checkbox_value">
                    <?php echo wc_query_string_form_fields( null, array('rating'), '', true ); ?>
            </form>
	   	  <?php 
           $args = array();
            if(isset($_GET['rating'])) {
              $args   =   explode( ',',  $_GET['rating'] ); ;  
            }

    		echo '<ul class="ct_ul_ol">';
            ?>
            <li class="wc-layered-nav-rating"><span class="sort-product-checkbox catlog-layout<?php if (in_array('5', $args)) echo esc_attr(' active'); ?>" data-name="rating" data-value="5">
                <span class="check"></span>
                <span class="star-check">
                    <span class="text star-rating">
                        <span style="width:100%;"></span>
                    </span>
                    <span><?php echo '5'; echo esc_html__(' Only','auriane'); ?> </span>
                </span>
                </span></li>
            <?php
            for ($i = 4; $i >= 1; $i--) {
               if($i == '4') {
                 $width = '80%';
               }elseif($i == '3') {
                 $width = '60%';  
               }elseif($i == '2'){
                 $width = '40%';   
               }else{
                $width = '20%';
               }
               
               
               ?>
               
               <li class="wc-layered-nav-rating">
                    <span class="sort-product-checkbox catlog-layout<?php if (in_array($i, $args)) echo esc_attr(' active'); ?>" data-name="rating" data-value="<?php echo esc_attr($i); ?>">
                            <span class="check"></span>
                            <span class="star-check">
                            <span class="text star-rating">
                                    <span style="width:<?php echo esc_attr($width); ?>"></span>
                            </span>
                            <span><?php echo ''.$i; echo esc_html__(' and Up','auriane'); ?> </span>
                            </span>
                    </span>
               </li>
               
               <?php
               
               
            } ?>
            <?php
  
    		echo '</ul>';
          
          ?>
		</div>
		<?php
		echo ''.$after_widget;
	}

	function update( $new_instance, $old_instance ) {
			$instance             = $old_instance;
    		$new_instance         = wp_parse_args( (array) $new_instance, array(
    			'title'    => '',

    		) );
    		$instance['title']    = sanitize_text_field( $new_instance['title'] );

    		return $instance;
	}
}

if(function_exists('insert_widgets')) {
    insert_widgets( 'jws_RATING_FILTER_class' );
}