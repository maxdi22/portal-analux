<?php // Exit if accessed directly.
if (!defined('ABSPATH')) {
    exit;
}
class jws_Admin {
    
	/**
	 * Current theme template
	 *
	 * @var String
	 */
	public $template;

	/**
	 * Instance of Elemenntor Frontend class.
	 *
	 * @var \Elementor\Frontend()
	 */
	private static $elementor_instance;
	/**
	 * Constructor
	 */
	function __construct() {

		$this->template = get_template();

		if ( defined( 'ELEMENTOR_VERSION' ) && is_callable( 'Elementor\Plugin::instance' ) ) {

	        self::$elementor_instance = Elementor\Plugin::instance();   
            add_action('init', array( $this, 'jws_register__header_blocks'));  
            // Add shortcode column to block list
            add_filter('manage_edit-hf_template_columns', array( $this, 'jws_edit_heading_header_columns'));
            add_action('manage_hf_template_posts_custom_column', array( $this,'jws_create_shortcode_header_vc'), 10, 2);
            add_action( 'template_redirect',array( $this, 'block_template_frontend' ));
            add_filter( 'single_template', array( $this, 'load_edit_template'  ));
            
            if(function_exists('insert_shortcode')) {
                 insert_shortcode( 'hf_template', array( $this, 'jws_get_content_header_block' ) );
                 insert_shortcode( 'elementor-template', array( $this, 'jws_get_content_elementor_template_block' ) );
            }    
		} 

	} 
    
    /**
	 * Single template function which will choose our template
	 *
	 * @since  1.0.1
	 *
	 * @param  String $single_template Single template.
	 */
	function load_edit_template( $single_template ) {

		global $post;

		if ( 'hf_template' == $post->post_type ) {

			$elementor_2_0_canvas = ELEMENTOR_PATH . '/modules/page-templates/templates/canvas.php';

			if ( file_exists( $elementor_2_0_canvas ) ) {
				return $elementor_2_0_canvas;
			} else {
				return ELEMENTOR_PATH . '/includes/page-templates/canvas.php';
			}
		}

		return $single_template;
	}
    
    
    function jws_register__header_blocks()
    {
        $labels = array(
            'name' => _x('Header , Footers And Template', 'Post Type General Name', 'auriane'),
            'singular_name' => _x('Header , Footers And Template', 'Post Type Singular Name', 'auriane'),
            'menu_name' => esc_html__('Header , Footers And Template', 'auriane'),
            'parent_item_colon' => esc_html__('Parent Item:', 'auriane'),
            'all_items' => esc_html__('Header , Footers And Template', 'auriane'),
            'view_item' => esc_html__('View Item', 'auriane'),
            'add_new_item' => esc_html__('Add New Item', 'auriane'),
            'add_new' => esc_html__('Add New', 'auriane'),
            'edit_item' => esc_html__('Edit Item', 'auriane'),
            'update_item' => esc_html__('Update Item', 'auriane'),
            'search_items' => esc_html__('Search Item', 'auriane'),
            'not_found' => esc_html__('Not found', 'auriane'),
            'not_found_in_trash' => esc_html__('Not found in Trash', 'auriane'),
        );
    
        $args = array(
            'label' => esc_html__('Header , Footers And Template', 'auriane'),
            'description' => esc_html__('Elemetor content for custom HTML to place in your pages', 'auriane'),
            'labels' => $labels,
            'menu_position' => 29,
            'menu_icon'           => ''.JWS_URI_PATH.'/assets/image/posttyle_icon/template_icon_type.png',
            'publicly_queryable' => true,
            	'public'              => true,
    			'show_ui'             => true,
    			'show_in_menu'		  => 'jws_settings.php',
    			'show_in_nav_menus'   => true,
    			'exclude_from_search' => true,
    			'capability_type'     => 'page',
    			'hierarchical'        => true,
    			'supports'            => array( 'title', 'thumbnail', 'auriane' , 'revisions'),
            
        );

         
        if(function_exists('custom_reg_post_type')){
        	custom_reg_post_type( 'hf_template', $args );
        } 
      
    
    }
    function jws_edit_heading_header_columns($columns)
    {
        
            $columns = array(
                'cb' => '<input type="checkbox" />',
                'title' => esc_html__('Title', 'auriane'),
                'shortcode' => esc_html__('Shortcode', 'auriane'),
                'date' => esc_html__('Date', 'auriane'),
            );
        
            return $columns;
    }
        
    function jws_create_shortcode_header_vc($column, $post_id)
        {
            switch ($column) {
                case 'shortcode' :
                    echo '<strong>[hf_template id="' . $post_id . '"]</strong>';
                    break;
            }
    }
    
    function block_template_frontend() {
    		if ( is_singular( 'hf_template' ) && ! current_user_can( 'edit_posts' ) ) {
    			wp_redirect( site_url(), 301 );
    			die;
    		}
    }
    function jws_get_content_header_block($atts) {
       	$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'hf_template'
		);

		$id = ! empty( $atts['id'] ) ? apply_filters( 'hfe_render_template_id', intval( $atts['id'] ) ) : '';

		if ( empty( $id ) ) {
			return '';
		}

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
    }
    
    function jws_get_content_elementor_template_block($atts) {
       	$atts = shortcode_atts(
			array(
				'id' => '',
			),
			$atts,
			'elementor_library'
		);

		$id = ! empty( $atts['id'] ) ? apply_filters( 'elementor_library_id', intval( $atts['id'] ) ) : '';

		if ( empty( $id ) ) {
			return '';
		}

		return Elementor\Plugin::instance()->frontend->get_builder_content_for_display( $id );
    }
    
    
    



}  
new jws_Admin();
