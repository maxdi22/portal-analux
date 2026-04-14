<?php
/**
 * Tag Cloud Widget.
 *
 * @package WooCommerce\Widgets
 * @version 3.4.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Widget product tag cloud
 */
class JWS_WC_Widget_Product_Tag_Cloud extends WC_Widget {

	/**
	 * Constructor.
	 */
	public function __construct() {
		$this->widget_cssclass    = 'woocommerce widget_product_tag_cloud';
		$this->widget_description = __( 'A cloud of your most used product tags.', 'auriane' );
		$this->widget_id          = 'woocommerce_product_tag_cloud';
		$this->widget_name        = __( 'Product Tag Cloud', 'auriane' );
		$this->settings           = array(
			'title' => array(
				'type'  => 'text',
				'std'   => __( 'Product tags', 'auriane' ),
				'label' => __( 'Title', 'auriane' ),
			),
		);

		parent::__construct();
	}

	/**
	 * Output widget.
	 *
	 * @see WP_Widget
	 *
	 * @param array $args     Arguments.
	 * @param array $instance Widget instance.
	 */
	public function widget( $args, $instance ) {
		$current_taxonomy = $this->get_current_taxonomy( $instance );

		if ( empty( $instance['title'] ) ) {
			$taxonomy          = get_taxonomy( $current_taxonomy );
			$instance['title'] = $taxonomy->labels->name;
		}

		$this->widget_start( $args, $instance );

		echo '<div class="tagcloud">';

		$this->wc_tag_cloud(
			apply_filters(
				'woocommerce_product_tag_cloud_widget_args',
				array(
					'taxonomy'                  => $current_taxonomy,
					'topic_count_text_callback' => array( $this, 'topic_count_text' ),
				)
			)
		);

		echo '</div>';

		$this->widget_end( $args );
	}

	/**
	 * Return the taxonomy being displayed.
	 *
	 * @param  object $instance Widget instance.
	 * @return string
	 */
	public function get_current_taxonomy( $instance ) {
		return 'product_tag';
	}

	/**
	 * Returns topic count text.
	 *
	 * @since 3.4.0
	 * @param int $count Count text.
	 * @return string
	 */
	public function topic_count_text( $count ) {
		/* translators: %s: product count */
		return sprintf( _n( '%s product', '%s products', $count, 'auriane' ), number_format_i18n( $count ) );
	}

	// Ignore whole block to avoid warnings about PSR2.Methods.MethodDeclaration.Underscore violation.
	// @codingStandardsIgnoreStart
	/**
	 * Return the taxonomy being displayed.
	 *
	 * @deprecated 3.4.0
	 * @param  object $instance Widget instance.
	 * @return string
	 */
	public function _get_current_taxonomy( $instance ) {
		wc_deprecated_function( '_get_current_taxonomy', '3.4.0', 'WC_Widget_Product_Tag_Cloud->get_current_taxonomy' );
		return $this->get_current_taxonomy( $instance );
	}

	/**
	 * Returns topic count text.
	 *
	 * @deprecated 3.4.0
	 * @since 2.6.0
	 * @param int $count Count text.
	 * @return string
	 */
	public function _topic_count_text( $count ) {
		wc_deprecated_function( '_topic_count_text', '3.4.0', 'WC_Widget_Product_Tag_Cloud->topic_count_text' );
		return $this->topic_count_text( $count );
	}
	// @codingStandardsIgnoreEnd
    
    public function wc_tag_cloud( $args = '' ) {
        	$defaults = array(
                'smallest'   => 8,
                'largest'    => 22,
                'unit'       => 'pt',
                'number'     => 45,
                'format'     => 'flat',
                'separator'  => "\n",
                'orderby'    => 'name',
                'order'      => 'ASC',
                'exclude'    => '',
                'include'    => '',
                'link'       => 'view',
                'taxonomy'   => 'post_tag',
                'post_type'  => '',
                'echo'       => true,
                'show_count' => 0,
            );
         
            $args = wp_parse_args( $args, $defaults );
         
            $tags = get_terms(
                array_merge(
                    $args,
                    array(
                        'orderby' => 'count',
                        'order'   => 'DESC',
                    )
                )
            ); // Always query top tags.
         
            if ( empty( $tags ) || is_wp_error( $tags ) ) {
                return;
            }
         
            foreach ( $tags as $key => $tag ) {
                if ( 'edit' === $args['link'] ) {
                    $link = get_edit_term_link( $tag, $tag->taxonomy, $args['post_type'] );
                } else {
                    $link = jws_shop_page_link(true,get_term_link( $tag, $tag->taxonomy ));
                }
         
                if ( is_wp_error( $link ) ) {
                    return;
                }
         
                $tags[ $key ]->link = $link;
                $tags[ $key ]->id   = $tag->term_id;
            }
         
            // Here's where those top tags get sorted according to $args.
            $return = wp_generate_tag_cloud( $tags, $args );
         
            /**
             * Filters the tag cloud output.
             *
             * @since 2.3.0
             *
             * @param string|string[] $return Tag cloud as a string or an array, depending on 'format' argument.
             * @param array           $args   An array of tag cloud arguments. See wp_tag_cloud()
             *                                for information on accepted arguments.
             */
            $return = apply_filters( 'wp_tag_cloud', $return, $args );
         
            if ( 'array' === $args['format'] || empty( $args['echo'] ) ) {
                return $return;
            }
         
            echo ''.$return;
	}
}
if(function_exists('insert_widgets')) {
    insert_widgets( 'JWS_WC_Widget_Product_Tag_Cloud' );
}
