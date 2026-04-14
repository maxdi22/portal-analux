<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    function jws_register_dealer_review() {
        global $jws_option;
		$labels = array(
			'name'                => _x( 'Dealer Review', 'Post Type General Name', 'auriane' ),
			'singular_name'       => _x( 'Dealer Review', 'Post Type Singular Name', 'auriane' ),
			'menu_name'           => esc_html__( 'Dealer Review', 'auriane' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'auriane' ),
			'all_items'           => esc_html__( 'Dealer Review', 'auriane' ),
			'view_item'           => esc_html__( 'View Item', 'auriane' ),
			'add_new_item'        => esc_html__( 'Add New Item', 'auriane' ),
			'add_new'             => esc_html__( 'Add New', 'auriane' ),
			'edit_item'           => esc_html__( 'Edit Item', 'auriane' ),
			'update_item'         => esc_html__( 'Update Item', 'auriane' ),
			'search_items'        => esc_html__( 'Search Item', 'auriane' ),
			'not_found'           => esc_html__( 'Not found', 'auriane' ),
			'not_found_in_trash'  => esc_html__( 'Not found in Trash', 'auriane' ),
		);

		$args = array(
			'label'               => esc_html__( 'Dealer Review', 'auriane' ),
		    'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt', 'thumbnail','page-attributes', 'post-formats' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'		    =>	'jws_settings.php',
            'menu_icon'           => ''.JWS_URI_PATH.'/assets/image/posttyle_icon/dealer_review_icon_type.png',
            'can_export'          => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'post',
         
		);


        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'dealer_review', $args );
        }
	};
add_action( 'init', 'jws_register_dealer_review', 1 );