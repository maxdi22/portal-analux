<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    function jws_register_questions() {
        
	 // Set UI labels for Custom Post Type
            $labels = array(
                'name' => _x('Questions & Answers', 'Post Type General Name', 'auriane'),
                'singular_name' => _x('Question', 'Post Type Singular Name', 'auriane'),
                'menu_name' => __('Questions & Answers', 'auriane'),
                'parent_item_colon' => __('Parent discussion', 'auriane'),
                'all_items' => __('All discussion', 'auriane'),
                'view_item' => __('View discussions', 'auriane'),
                'add_new_item' => __('Add new question', 'auriane'),
                'add_new' => __('Add new', 'auriane'),
                'edit_item' => __('Edit discussion', 'auriane'),
                'update_item' => __('Update discussion', 'auriane'),
                'search_items' => __('Search discussion', 'auriane'),
                'not_found' => __('Not found', 'auriane'),
                'not_found_in_trash' => __('Not found in the bin', 'auriane'),
            );

            // Set other options for Custom Post Type

            $args = array(
                'label' => __('Questions & Answers', 'auriane'),
                'description' => __('YITH Questions and Answers', 'auriane'),
                'labels' => $labels,
                // Features this CPT supports in Post Editor
                'supports' => array(
                    'title',
                    //'editor',
                    //'author',
                ),
                'hierarchical' => false,
                'public' => false,
                'show_ui' => true,
                'show_in_menu' => true,
                'show_in_nav_menus' => false,
                'show_in_admin_bar' => false,
                'menu_position' => 9,
                'can_export' => false,
                'has_archive' => true,
                'exclude_from_search' => true,
                'menu_icon' => 'dashicons-clipboard',
                'query_var' => false,
                'publicly_queryable'  => true,
                'capability_type'     => 'page',
            );

     


        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'questions', $args );
        }

		
	};
add_action( 'init', 'jws_register_questions', 1 );

