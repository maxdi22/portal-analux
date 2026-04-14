<?php // Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

    function jws_register_inventory() {
        global $jws_option;
		$labels = array(
			'name'                => _x( 'inventory', 'Post Type General Name', 'auriane' ),
			'singular_name'       => _x( 'inventory', 'Post Type Singular Name', 'auriane' ),
			'menu_name'           => esc_html__( 'Inventory', 'auriane' ),
			'parent_item_colon'   => esc_html__( 'Parent Item:', 'auriane' ),
			'all_items'           => esc_html__( 'All Items', 'auriane' ),
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
			'label'               => esc_html__( 'Inventory', 'auriane' ),
		    'labels'              => $labels,
            'supports'            => array( 'title', 'editor', 'excerpt','page-attributes', 'post-formats','author' , 'custom-fields' , 'revisions' ),
            'taxonomies'          => array( 'cars_cat' ),
            'hierarchical'        => true,
            'public'              => true,
            'show_ui'             => true,
            'show_in_menu'        => true,
            'show_in_nav_menus'   => true,
            'show_in_admin_bar'   => true,
            'menu_position'       => 5,
            'menu_icon'           => ''.JWS_URI_PATH.'/assets/image/posttyle_icon/inventory_icon_type.png',
            'can_export'          => true,
            'has_archive'         => true,
            'exclude_from_search' => false,
            'publicly_queryable'  => true,
            'capability_type'     => 'page',
         
		);


        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'cars', $args );
        }

		/**
		 * Create a taxonomy category for inventory
		 *
		 * @uses  Inserts new taxonomy object into the list
		 * @uses  Adds query vars
		 *
		 * @param string  Name of taxonomy object
		 * @param array|string  Name of the object type for the taxonomy object.
		 * @param array|string  Taxonomy arguments
		 * @return null|WP_Error WP_Error if errors, otherwise null.
		 */
		
		$labels = array(
			'name'					=> _x( 'inventory Categories', 'Taxonomy plural name', 'auriane' ),
			'singular_name'			=> _x( 'inventoryCategory', 'Taxonomy singular name', 'auriane' ),
			'search_items'			=> esc_html__( 'Search Categories', 'auriane' ),
			'popular_items'			=> esc_html__( 'Popular inventory Categories', 'auriane' ),
			'all_items'				=> esc_html__( 'All inventory Categories', 'auriane' ),
			'parent_item'			=> esc_html__( 'Parent Category', 'auriane' ),
			'parent_item_colon'		=> esc_html__( 'Parent Category', 'auriane' ),
			'edit_item'				=> esc_html__( 'Edit Category', 'auriane' ),
			'update_item'			=> esc_html__( 'Update Category', 'auriane' ),
			'add_new_item'			=> esc_html__( 'Add New Category', 'auriane' ),
			'new_item_name'			=> esc_html__( 'New Category', 'auriane' ),
			'add_or_remove_items'	=> esc_html__( 'Add or remove Categories', 'auriane' ),
			'choose_from_most_used'	=> esc_html__( 'Choose from most used text-domain', 'auriane' ),
			'menu_name'				=> esc_html__( 'Category', 'auriane' ),
		);
	
		$args = array(
			'hierarchical'      => true,
            'labels'            => $labels,
            'show_ui'           => true,
            'show_admin_column' => true,
            'query_var'         => true,
            'rewrite'           => array( 'slug' => 'car_cat' ),
		);
        

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_cat', array( 'cars' ), $args  );
        }
        
        $labels = array(
            'name' => esc_html__( 'Tags', 'auriane' ),
            'singular_name' => esc_html__( 'Tag',  'auriane'  ),
            'search_items' =>  esc_html__( 'Search Tags' , 'auriane' ),
            'popular_items' => esc_html__( 'Popular Tags' , 'auriane' ),
            'all_items' => esc_html__( 'All Tags' , 'auriane' ),
            'parent_item' => null,
            'parent_item_colon' => null,
            'edit_item' => esc_html__( 'Edit Tag' , 'auriane' ), 
            'update_item' => esc_html__( 'Update Tag' , 'auriane' ),
            'add_new_item' => esc_html__( 'Add New Tag' , 'auriane' ),
            'new_item_name' => esc_html__( 'New Tag Name' , 'auriane' ),
            'separate_items_with_commas' => esc_html__( 'Separate tags with commas' , 'auriane' ),
            'add_or_remove_items' => esc_html__( 'Add or remove tags' , 'auriane' ),
            'choose_from_most_used' => esc_html__( 'Choose from the most used tags' , 'auriane' ),
            'menu_name' => esc_html__( 'Tags','auriane'),
        ); 
    
        $args = array(
            'hierarchical' => false,
            'labels' => $labels,
            'show_ui' => true,
            'update_count_callback' => '_update_post_term_count',
            'query_var' => true,
            'rewrite' => array( 'slug' => 'inventory_tag' ),
        );
        
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'inventory_tag', array( 'inventory' ), $args  );
        }

	};
add_action( 'init', 'jws_register_inventory', 1 );

function add_featured_image_column_inventory($defaults) {
    $defaults['price'] = esc_html__('Price','auriane');
    $defaults['featured_image'] = esc_html__('Features Image','auriane');
    
    $defaults['featured'] = esc_html__('Features','auriane');
    return $defaults;
}
add_filter('manage_cars_posts_columns', 'add_featured_image_column_inventory');
 
function show_featured_image_column_inventory($column_name, $post_id) {
    
    if ($column_name == 'featured') { 
      $featured =  get_post_meta( $post_id , 'car_asset_type',  true ); 

      ?> <a href="javascript:void(0)" data-id="<?php echo esc_attr($post_id); ?>" class="jws-make-features<?php echo (isset($featured) && $featured == 'featured') ? ' active' : ''; ?>"><i class="jws-icon-star-full"></i></a><?php
        
    }
    if ($column_name == 'featured_image') {
      $car_images =  get_post_meta( $post_id , 'car_images',  true );      
      if(!empty($car_images)) {
        $img_car = jws_getImageBySize(array('attach_id' => $car_images[0], 'thumb_size' => '508x360', 'class' => 'car-images-'.$car_images[0].''));
        if(!empty($img_car['thumbnail'])) echo ''.$img_car['thumbnail'];
        
      }
    }
    
     if ($column_name == 'price') {
      $price =  get_post_meta( $post_id , 'regular_price',  true );      
      if(!empty($price)) {
        jws_car_price_html($class = '', $id = null, $tax_label = false, $echo = true);
       jws_car_price_msrp_html();
        
      }
    }
}
add_action('manage_cars_posts_custom_column', 'show_featured_image_column_inventory', 10, 2); 

// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Year', 'auriane' ),
			'singular_name'              => esc_html__( 'Year', 'auriane' ),
			'search_items'               => esc_html__( 'Search Year', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Year', 'auriane' ),
			'all_items'                  => esc_html__( 'All Year', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Year', 'auriane' ),
			'update_item'                => esc_html__( 'Update Year', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Year', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Year Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate year with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Year', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Year', 'auriane' ),
			'not_found'                  => esc_html__( 'No year found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Year', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'year' ),
		);


        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_year', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Make', 'auriane' ),
			'singular_name'              => esc_html__( 'Make', 'auriane' ),
			'search_items'               => esc_html__( 'Search Make', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Make', 'auriane' ),
			'all_items'                  => esc_html__( 'All Make', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Make', 'auriane' ),
			'update_item'                => esc_html__( 'Update Make', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Make', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Make Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate make with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Make', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Make', 'auriane' ),
			'not_found'                  => esc_html__( 'No make found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Make', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'make' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_make', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Model', 'auriane' ),
			'singular_name'              => esc_html__( 'Model', 'auriane' ),
			'search_items'               => esc_html__( 'Search Model', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Model', 'auriane' ),
			'all_items'                  => esc_html__( 'All Model', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Model', 'auriane' ),
			'update_item'                => esc_html__( 'Update Model', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Model', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Model Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate model with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Model', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Model', 'auriane' ),
			'not_found'                  => esc_html__( 'No model found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Model', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'model' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_model', array( 'cars' ), $args  );
        }
        
		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Body Style', 'auriane' ),
			'singular_name'              => esc_html__( 'Body Style', 'auriane' ),
			'search_items'               => esc_html__( 'Search Body Style', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Body Style', 'auriane' ),
			'all_items'                  => esc_html__( 'All Body Style', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Body Style', 'auriane' ),
			'update_item'                => esc_html__( 'Update Body Style', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Body Style', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Body Style Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate body style with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove body style', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used body style', 'auriane' ),
			'not_found'                  => esc_html__( 'No body style found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Body Style', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'body-style' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_body_style', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Mileage', 'auriane' ),
			'singular_name'              => esc_html__( 'Mileage', 'auriane' ),
			'search_items'               => esc_html__( 'Search Mileage', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Mileage', 'auriane' ),
			'all_items'                  => esc_html__( 'All Mileage', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Mileage', 'auriane' ),
			'update_item'                => esc_html__( 'Update Mileage', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Mileage', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Mileage Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate mileage with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Mileage', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Mileage', 'auriane' ),
			'not_found'                  => esc_html__( 'No mileage found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Mileage', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'mileage' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_mileage', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Transmission', 'auriane' ),
			'singular_name'              => esc_html__( 'Transmission', 'auriane' ),
			'search_items'               => esc_html__( 'Search Transmission', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Transmission', 'auriane' ),
			'all_items'                  => esc_html__( 'All Transmission', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Transmission', 'auriane' ),
			'update_item'                => esc_html__( 'Update Transmission', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Transmission', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Transmission Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate transmission with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Transmission', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Transmission', 'auriane' ),
			'not_found'                  => esc_html__( 'No transmission found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Transmission', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'transmission' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_transmission', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Condition', 'auriane' ),
			'singular_name'              => esc_html__( 'Condition', 'auriane' ),
			'search_items'               => esc_html__( 'Search Condition', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Condition', 'auriane' ),
			'all_items'                  => esc_html__( 'All Condition', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Condition', 'auriane' ),
			'update_item'                => esc_html__( 'Update Condition', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Condition', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Condition Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate condition with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Condition', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Condition', 'auriane' ),
			'not_found'                  => esc_html__( 'No condition found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Condition', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'condition' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_condition', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Drivetrain', 'auriane' ),
			'singular_name'              => esc_html__( 'Drivetrain', 'auriane' ),
			'search_items'               => esc_html__( 'Search Drivetrain', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Drivetrain', 'auriane' ),
			'all_items'                  => esc_html__( 'All Drivetrain', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Drivetrain', 'auriane' ),
			'update_item'                => esc_html__( 'Update Drivetrain', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Drivetrain', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Drivetrain Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate drivetrain with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Drivetrain', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Drivetrain', 'auriane' ),
			'not_found'                  => esc_html__( 'No drivetrain found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Drivetrain', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'drivetrain' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_drivetrain', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Engine', 'auriane' ),
			'singular_name'              => esc_html__( 'Engine', 'auriane' ),
			'search_items'               => esc_html__( 'Search Engine', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Engine', 'auriane' ),
			'all_items'                  => esc_html__( 'All Engine', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Engine', 'auriane' ),
			'update_item'                => esc_html__( 'Update Engine', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Engine', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Engine Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate engine with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Engine', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Engine', 'auriane' ),
			'not_found'                  => esc_html__( 'No engine found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Engine', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'engine' ),
		);

        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_engine', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Fuel Economy', 'auriane' ),
			'singular_name'              => esc_html__( 'Fuel Economy', 'auriane' ),
			'search_items'               => esc_html__( 'Search Fuel Economy', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Fuel Economy', 'auriane' ),
			'all_items'                  => esc_html__( 'All Fuel Economy', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Fuel Economy', 'auriane' ),
			'update_item'                => esc_html__( 'Update Fuel Economy', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Fuel Economy', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Fuel Economy Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate fuel-economy with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Fuel Economy', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Fuel Economy', 'auriane' ),
			'not_found'                  => esc_html__( 'No fuel-economy found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Fuel Economy', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'fuel-economy' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_fuel_economy', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Exterior Color', 'auriane' ),
			'singular_name'              => esc_html__( 'Exterior Color', 'auriane' ),
			'search_items'               => esc_html__( 'Search Exterior Color', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Exterior Color', 'auriane' ),
			'all_items'                  => esc_html__( 'All Exterior Color', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Exterior Color', 'auriane' ),
			'update_item'                => esc_html__( 'Update Exterior Color', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Exterior Color', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Exterior Color Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate exterior-color with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Exterior Color', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Exterior Color', 'auriane' ),
			'not_found'                  => esc_html__( 'No exterior-color found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Exterior Color', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'exterior-color' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_exterior_color', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Interior Color', 'auriane' ),
			'singular_name'              => esc_html__( 'Interior Color', 'auriane' ),
			'search_items'               => esc_html__( 'Search Interior Color', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Interior Color', 'auriane' ),
			'all_items'                  => esc_html__( 'All Interior Color', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Interior Color', 'auriane' ),
			'update_item'                => esc_html__( 'Update Interior Color', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Interior Color', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Interior Color Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate interior-color with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Interior Color', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Interior Color', 'auriane' ),
			'not_found'                  => esc_html__( 'No interior-color found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Interior Color', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'interior-color' ),
		);
		
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_interior_color', array( 'cars' ), $args  );
        }
        
        
        $labels = array(
			'name'                       => esc_html__( 'Drive Train', 'auriane' ),
			'singular_name'              => esc_html__( 'Drive Train', 'auriane' ),
			'search_items'               => esc_html__( 'Search Drive Train', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Drive Train', 'auriane' ),
			'all_items'                  => esc_html__( 'All Drive Train', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Drive Train', 'auriane' ),
			'update_item'                => esc_html__( 'Update Drive Train', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Drive Train', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Drive Train Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate drive-train with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Drive Train', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Drive Train', 'auriane' ),
			'not_found'                  => esc_html__( 'No drive-train found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Drive Train', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'drive-train' ),
		);
		
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_drive_train', array( 'cars' ), $args  );
        }
        
        $labels = array(
			'name'                       => esc_html__( 'Registered', 'auriane' ),
			'singular_name'              => esc_html__( 'Registered', 'auriane' ),
			'search_items'               => esc_html__( 'Search Registered', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Registered', 'auriane' ),
			'all_items'                  => esc_html__( 'All Registered', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Registered', 'auriane' ),
			'update_item'                => esc_html__( 'Update Registered', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Registered', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Registered Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate registered with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Registered', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Registered', 'auriane' ),
			'not_found'                  => esc_html__( 'No registered found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Registered', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'registered' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_registered', array( 'cars' ), $args  );
        }
        
        $labels = array(
			'name'                       => esc_html__( 'Door Number', 'auriane' ),
			'singular_name'              => esc_html__( 'Door Number', 'auriane' ),
			'search_items'               => esc_html__( 'Search Door Number', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Door Number', 'auriane' ),
			'all_items'                  => esc_html__( 'All Door Number', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Door Number', 'auriane' ),
			'update_item'                => esc_html__( 'Update Door Number', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Door Number', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Door Number Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate Door Number with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Door Number', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Door Number', 'auriane' ),
			'not_found'                  => esc_html__( 'No Door Number found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Door Number', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'Door Number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_door_number', array( 'cars' ), $args  );
        }
        
        $labels = array(
			'name'                       => esc_html__( 'Seat Number', 'auriane' ),
			'singular_name'              => esc_html__( 'Seat Number', 'auriane' ),
			'search_items'               => esc_html__( 'Search Seat Number', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Seat Number', 'auriane' ),
			'all_items'                  => esc_html__( 'All Seat Number', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Seat Number', 'auriane' ),
			'update_item'                => esc_html__( 'Update Seat Number', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Seat Number', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Seat Number Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate Seat Number with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Seat Number', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Seat Number', 'auriane' ),
			'not_found'                  => esc_html__( 'No Seat Number found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Seat Number', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'Seat Number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_seat_number', array( 'cars' ), $args  );
        }
        
		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Stock Number', 'auriane' ),
			'singular_name'              => esc_html__( 'Stock Number', 'auriane' ),
			'search_items'               => esc_html__( 'Search Stock Number', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Stock Number', 'auriane' ),
			'all_items'                  => esc_html__( 'All Stock Number', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Stock Number', 'auriane' ),
			'update_item'                => esc_html__( 'Update Stock Number', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Stock Number', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Stock Number Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate stock-number with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Stock Number', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Stock Number', 'auriane' ),
			'not_found'                  => esc_html__( 'No stock-number found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Stock Number', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'stock-number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_stock_number', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'VIN Number', 'auriane' ),
			'singular_name'              => esc_html__( 'VIN Number', 'auriane' ),
			'search_items'               => esc_html__( 'Search VIN Number', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular VIN Number', 'auriane' ),
			'all_items'                  => esc_html__( 'All VIN Number', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit VIN Number', 'auriane' ),
			'update_item'                => esc_html__( 'Update VIN Number', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New VIN Number', 'auriane' ),
			'new_item_name'              => esc_html__( 'New VIN Number Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate vin-number with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove VIN Number', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used VIN Number', 'auriane' ),
			'not_found'                  => esc_html__( 'No vin-number found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'VIN Number', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'vin-number' ),
		);
	
        if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_vin_number', array( 'cars' ), $args  );
        }

		$labels = array(
			'name'                       => esc_html__( 'Fuel Type', 'auriane' ),
			'singular_name'              => esc_html__( 'Fuel Type', 'auriane' ),
			'search_items'               => esc_html__( 'Search Fuel Type', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Fuel Type', 'auriane' ),
			'all_items'                  => esc_html__( 'All Fuel Type', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Fuel Type', 'auriane' ),
			'update_item'                => esc_html__( 'Update Fuel Type', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Fuel Type', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Fuel Type Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate fuel-type with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Fuel Type', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Fuel Type', 'auriane' ),
			'not_found'                  => esc_html__( 'No fuel-type found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Fuel Type', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'fuel-type' ),
		);
	
         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_fuel_type', array( 'cars' ), $args  );
        }

		$labels = array(
			'name'                       => esc_html__( 'Trim', 'auriane' ),
			'singular_name'              => esc_html__( 'Trim', 'auriane' ),
			'search_items'               => esc_html__( 'Search Trim', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Trim', 'auriane' ),
			'all_items'                  => esc_html__( 'All Trim', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Trim', 'auriane' ),
			'update_item'                => esc_html__( 'Update Trim', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Trim', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Trim Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate trim-type with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Trim', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Trim', 'auriane' ),
			'not_found'                  => esc_html__( 'No trim found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Trim', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => false,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'trim' ),
		);

         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_trim', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Features & Options', 'auriane' ),
			'singular_name'              => esc_html__( 'Features & Options', 'auriane' ),
			'search_items'               => esc_html__( 'Search Features & Options', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Features & Options', 'auriane' ),
			'all_items'                  => esc_html__( 'All Features & Options', 'auriane' ),
			'parent_item'                => esc_html__( 'Parent Feature', 'auriane' ),
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Features & Options', 'auriane' ),
			'update_item'                => esc_html__( 'Update Features & Options', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Features & Options', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Features & Options Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate features-options with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Features & Options', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Features & Options', 'auriane' ),
			'not_found'                  => esc_html__( 'No features-options found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Features & Options', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => array( 'slug' => 'features-options' ),
		);
	
         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_features_options', array( 'cars' ), $args  );
        }

		// Add new taxonomy, NOT hierarchical (like tags).
		$labels = array(
			'name'                       => esc_html__( 'Vehicle Review Stamps', 'auriane' ),
			'singular_name'              => esc_html__( 'Vehicle Review Stamps', 'auriane' ),
			'search_items'               => esc_html__( 'Search Vehicle Review Stamps', 'auriane' ),
			'popular_items'              => esc_html__( 'Popular Vehicle Review Stamps', 'auriane' ),
			'all_items'                  => esc_html__( 'All Vehicle Review Stamps', 'auriane' ),
			'parent_item'                => null,
			'parent_item_colon'          => null,
			'edit_item'                  => esc_html__( 'Edit Vehicle Review Stamps', 'auriane' ),
			'update_item'                => esc_html__( 'Update Vehicle Review Stamps', 'auriane' ),
			'add_new_item'               => esc_html__( 'Add New Vehicle Review Stamps', 'auriane' ),
			'new_item_name'              => esc_html__( 'New Vehicle Review Stamps Name', 'auriane' ),
			'separate_items_with_commas' => esc_html__( 'Separate Vehicle Review Stamps with commas', 'auriane' ),
			'add_or_remove_items'        => esc_html__( 'Add or remove Vehicle Review Stamps', 'auriane' ),
			'choose_from_most_used'      => esc_html__( 'Choose from the most used Vehicle Review Stamps', 'auriane' ),
			'not_found'                  => esc_html__( 'No Vehicle Review Stamps found.', 'auriane' ),
			'menu_name'                  => esc_html__( 'Vehicle Review Stamps', 'auriane' ),
		);

		$args = array(
			'hierarchical'          => true,
			'labels'                => $labels,
			'show_ui'               => true,
			'update_count_callback' => '_update_post_term_count',
			'query_var'             => true,
			'rewrite'               => true,
		);
	
         if(function_exists('custom_reg_taxonomy')){
            custom_reg_taxonomy( 'car_vehicle_review_stamps', array( 'cars' ), $args  );
        }
        
        add_action( 'admin_init', 'jws_remove_metabox' );
if ( ! function_exists( 'jws_remove_metabox' ) ) {
	/**
	 * Remove metabox.
	 */
	function jws_remove_metabox() {
		remove_meta_box( 'tagsdiv-car_year', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_make', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_model', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_body_style', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_condition', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_mileage', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_transmission', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_drivetrain', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_engine', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_economy', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_exterior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_interior_color', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_stock_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_vin_number', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_fuel_type', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_trim', 'cars', 'side' );
		remove_meta_box( 'tagsdiv-car_features_options', 'cars', 'side' );
		remove_meta_box( 'car_features_optionsdiv', 'cars', 'side' );
		remove_meta_box( 'car_vehicle_review_stampsdiv', 'cars', 'side' );
	}
}


