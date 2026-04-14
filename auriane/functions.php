<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}
/**
 * auriane functions and definitions
 *
 * @link https://developer.wordpress.org/themes/basics/theme-functions/
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */

/** Define THEME */ 
if (!defined('JWS_ABS_PATH')) define('JWS_ABS_PATH', get_template_directory());
if (!defined('JWS_ABS_PATH_ELEMENT')) define('JWS_ABS_PATH_ELEMENT', get_template_directory().'/inc/elementor_widget/widgets');
if (!defined('JWS_ABS_PATH_WC')) define('JWS_ABS_PATH_WC', get_template_directory().'/woocommerce');
if (!defined('JWS_URI_PATH')) define('JWS_URI_PATH', get_template_directory_uri());
if ( ! function_exists( 'jws_setup' ) ) :
	/**
	 * Sets up theme defaults and registers support for various WordPress features.
	 *
	 * Note that this function is hooked into the after_setup_theme hook, which
	 * runs before the init hook. The init hook is too late for some features, such
	 * as indicating support for post thumbnails.
	 */
	function jws_setup() {
		/*
		 * Make theme available for translation.
		 * Translations can be filed in the /languages/ directory.
		 * If you're building a theme based on auriane, use a find and replace
		 * to change 'auriane' to the name of your theme in all the template files.
		 */
		load_theme_textdomain( 'auriane', get_template_directory() . '/languages' );

		// Add default posts and comments RSS feed links to head.
		add_theme_support( 'automatic-feed-links' );

		/*
		 * Let WordPress manage the document title.
		 * By adding theme support, we declare that this theme does not use a
		 * hard-coded <title> tag in the document head, and expect WordPress to
		 * provide it for us.
		 */
		add_theme_support( 'title-tag' );

		/*
		 * Enable support for Post Thumbnails on posts and pages.
		 *
		 * @link https://developer.wordpress.org/themes/functionality/featured-images-post-thumbnails/
		 */
		add_theme_support( 'post-thumbnails' );
        add_theme_support( 'post-formats', array( 'video','link','quote','audio','gallery'));
		set_post_thumbnail_size( 1568, 9999 );

		/*
		 * Switch default core markup for search form, comment form, and comments
		 * to output valid HTML5.
		 */
		add_theme_support(
			'html5',
			array(
				'search-form',
				'comment-form',
				'comment-list',
				'gallery',
				'caption',
			)
		);

		// Add theme support for selective refresh for widgets.
		add_theme_support( 'customize-selective-refresh-widgets' );

		// Add support for Block Styles.
		add_theme_support( 'wp-block-styles' );

		// Add support for full and wide align images.
		add_theme_support( 'align-wide' );

		// Add support for editor styles.
		add_theme_support( 'editor-styles' );

		// Enqueue editor styles.
		add_editor_style( 'style-editor.css' );

		// Add support for responsive embedded content.
		add_theme_support( 'responsive-embeds' );
        // This theme uses wp_nav_menu() in two locations.
		register_nav_menus( array(
			'main_navigation'   => esc_html__( 'Main Menu','auriane' ),
		) );
        
	}
endif;
add_action( 'after_setup_theme', 'jws_setup' );

/**
 * Register widget area.
 *
 * @link https://developer.wordpress.org/themes/functionality/sidebars/#registering-a-sidebar
 */
function jws_widgets_init() {

    register_sidebar( 
      array(
			'name'          =>  esc_html__( 'Main Sidebar', 'auriane' ),
			'id'            => 'sidebar-main',
			'description'   =>  esc_html__( 'Add widgets here to appear in your blog.', 'auriane' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
	  )
    );
    register_sidebar( 
      array(
			'name'          =>  esc_html__( 'Single Blog Sidebar', 'auriane' ),
			'id'            => 'sidebar-single-blog',
			'description'   =>  esc_html__( 'Add widgets here to appear in your blog.', 'auriane' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
	  )
    );
    
    register_sidebar( 
      array(
			'name'          =>  esc_html__( 'Shop Page (Left And Right)', 'auriane' ),
			'id'            => 'sidebar-shop',
			'description'   =>  esc_html__( 'Add widgets here to appear in shop page.', 'auriane' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
	  )
    );
    
    register_sidebar( 
      array(
			'name'          =>  esc_html__( 'Shop Page Filter Modal', 'auriane' ),
			'id'            => 'sidebar-shop-filter-modal',
			'description'   =>  esc_html__( 'Add widgets here to appear in shop page filter modal.', 'auriane' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
	  )
    );
    
    register_sidebar( 
      array(
			'name'          =>  esc_html__( 'Shop Page Filter Nav Top', 'auriane' ),
			'id'            => 'sidebar-shop-filter-navtop',
			'description'   =>  esc_html__( 'Add widgets here to appear in shop page filter nav top.', 'auriane' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
	  )
    );

    register_sidebar( 
      array(
			'name'          =>  esc_html__( 'Sidebar Remove Filter Active', 'auriane' ),
			'id'            => 'remove_filter_active',
			'description'   =>  esc_html__( 'Add widgets here to appear in shop page.', 'auriane' ),
			'before_widget' => '<section id="%1$s" class="widget %2$s">',
			'after_widget'  => '</section>',
			'before_title'  => '<h5 class="widget-title">',
			'after_title'   => '</h5>',
	  )
    );

}
add_action( 'widgets_init', 'jws_widgets_init' );

/**
 * Add Theme Option
 */

/**
 * Set the content width in pixels, based on the theme's design and stylesheet.
 *
 * Priority 0 to make it available to lower priority callbacks.
 *
 * @global int $content_width Content width.
 */
function jws_content_width() {
	$GLOBALS['content_width'] = apply_filters( 'jws_content_width', 640 );
}

add_action( 'after_setup_theme', 'jws_content_width', 0 );


/**
 * Enqueue scripts and styles.
 */
if(!function_exists('jws_scripts')) {
  function jws_scripts() {
    
     global $jws_option;  
     $vertion = wp_get_theme()->get( 'Version' );

     wp_enqueue_style( 'jws-jwsicon', JWS_URI_PATH . '/assets/font/jws_icon/jwsicon.css', array(), $vertion, 'all' ); 
     wp_enqueue_style( 'jws-default', JWS_URI_PATH . '/assets/css/default.css', array(), $vertion , 'all' );
     wp_register_style( 'select2-min', JWS_URI_PATH . '/assets/css/select2.min.css', array(), $vertion , 'all' ); 
     wp_enqueue_style( 'magnificPopup', JWS_URI_PATH.'/assets/css/magnificPopup.css', array(), $vertion, 'all' );   
     wp_register_style( 'lightgallery', JWS_URI_PATH . '/assets/css/lightgallery.css' );
     wp_enqueue_style( 'slick', JWS_URI_PATH . '/assets/css/slick.css' );     
     wp_enqueue_style( 'awesome', JWS_URI_PATH . '/assets/font/awesome/awesome.css' );   
     
     /** Load our main stylesheet. It is generated with less in upload folder **/ 
     $upload_dir = wp_upload_dir();
     $style_dir = $upload_dir['baseurl'];
     $siteid = get_current_blog_id();
     $filename = 'jws-style.css';
     if (file_exists($upload_dir['basedir'] . '/'.$filename.'')) {
        wp_enqueue_style(
            'jws-style',
            set_url_scheme($style_dir) . '/'.$filename.'',
            ['elementor-frontend'],
            filemtime($upload_dir['basedir'] . '/'.$filename.'')
        );
     } else {
        wp_enqueue_style( 'jws-style', JWS_URI_PATH . '/assets/css/style.css', array(), $vertion, 'all'  );
        wp_enqueue_style( 'jws-style-reset', JWS_URI_PATH . '/assets/css/style-reset.css', array(), $vertion, 'all'  );
     } 
  
    wp_enqueue_style( 'jws-google-fonts', '//fonts.googleapis.com/css?family=Outfit:600,500,400,300|Cormorant+Garamond:600,500,400,300', false ); 


     /** Start Woocommerce **/
     wp_register_style( 'owl-carousel', JWS_URI_PATH.'/assets/css/owl.carousel.css', array(), $vertion, 'all' );
     wp_register_script( 'owl-carousel', JWS_URI_PATH. '/assets/js/lib/owl.carousel.js', [], '', true ); 
     wp_register_script( 'jws-woocommerce', JWS_URI_PATH. '/assets/js/woocommerce/woocommerce.js', [], '', true );


     if (class_exists('Woocommerce')) {
        if(is_cart() || is_checkout() || is_account_page()) {
           wp_enqueue_style('select2-min'); 
        }
        if(is_product() || is_shop() || is_tax() || is_cart() || is_checkout()) {
            wp_enqueue_script( 'stick-content', JWS_URI_PATH. '/assets/js/sticky_content.js', array(), '', true );
            wp_enqueue_style('owl-carousel');
            wp_enqueue_script('owl-carousel');
            wp_enqueue_script( 'jws-woocommerce');
            wp_register_script( 'jws-photoswipe', JWS_URI_PATH. '/assets/js/woocommerce/jws-photoswipe-bundle.min.js',  '', true );
            wp_enqueue_script( 'jquery-ui-core' );
	       
        }
      
     }else {
        wp_enqueue_script( 'js-cookie-min', JWS_URI_PATH. '/assets/js/lib/js.cookie.min.js', [], $vertion, true );
     }

	if ( wp_script_is( 'wc-add-to-cart-variation', 'registered' ) && ! wp_script_is( 'wc-add-to-cart-variation', 'enqueued' ) ) {
		wp_enqueue_script( 'wc-add-to-cart-variation' );
	}


     wp_register_script( 'isotope', JWS_URI_PATH. '/assets/js/lib/isotope.js', [], $vertion, true );
     wp_register_script( 'instafeed', JWS_URI_PATH. '/assets/js/lib/instafeed.js', [], $vertion, true );
     wp_register_script( 'lightgallery-all', JWS_URI_PATH. '/assets/js/lib/lightgallery-all.js', [],$vertion, true );
     wp_enqueue_script( 'magnificPopup', JWS_URI_PATH. '/assets/js/lib/magnificPopup.js', [] , $vertion, true );
     wp_register_script( 'jquery.countdown', JWS_URI_PATH. '/assets/js/lib/jquery.countdown.min.js', [] , $vertion, true );
     wp_register_script( 'jws-canvas', JWS_URI_PATH. '/assets/js/widget-js/jws-canvas.js', [] , $vertion, true );
     wp_register_script( 'jws-mini-cart', JWS_URI_PATH. '/assets/js/widget-js/mini-cart.js', [] , $vertion, true );
     wp_register_script( 'jquery-autocomplete', JWS_URI_PATH. '/assets/js/lib/jquery.autocomplete.js', [] , $vertion, true );
     wp_register_script( 'select2-min', JWS_URI_PATH. '/assets/js/lib/select2.min.js', [] , $vertion, true );
 
     
     /**
     *  Use for widget
     */
     wp_enqueue_script( 'jws-elementor-widget', JWS_URI_PATH. '/assets/js/widget-js/elementor_widget.js', [], $vertion, true );

       

     /**
     *  Use for all theme
     */
     wp_enqueue_script( 'slick-min', JWS_URI_PATH. '/assets/js/lib/slick.min.js', [], $vertion, true );    
     wp_enqueue_script('jquery-ui-tooltip');
     wp_enqueue_script( 'jquery-ui-slider' ); 
     wp_enqueue_script( 'jws-main', JWS_URI_PATH. '/assets/js/main.js' , [], $vertion, true );
     wp_enqueue_script( 'jws-optimize', JWS_URI_PATH. '/assets/js/optimize.js', [], $vertion, false ); 

     /**
     *  Add google services
     */

	$api_url     = 'https://maps.googleapis.com';
	
	if ( isset( $jws_option['google_api'] ) && '' !== $jws_option['google_api'] ) {
		$url      = $api_url . '/maps/api/js?key=' . $jws_option['google_api'].'&libraries=places&language=en';
		} else {
        $url = $api_url . '/maps/api/js';
	}
    wp_register_script( 'jws-google-maps-api', $url, [ 'jquery' ],'', true );   

    if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
    	wp_enqueue_script( 'comment-reply' );
    }
    /**
     *  Add validate js
     */
    wp_localize_script(
			'jws-main',
			'jws_script',
			array(
                'is_multisite' => is_multisite(),
		        'current_blog_id' => get_current_blog_id(),
				'ajax_url'        => admin_url( 'admin-ajax.php' ),
                'theme_path' => JWS_URI_PATH,
                'instaram_tk'  => (isset($jws_option['instagram_token']) && !empty($jws_option['instagram_token'])) ? $jws_option['instagram_token'] : '',
                'add_to_cart_text'  =>  esc_html__('Add to cart','auriane'),
                 'select_option_text'  => esc_html__('Select options','auriane'),
                'metera'  =>  esc_html__('Very weak','auriane'),
                'meterb'  =>  esc_html__('Weak','auriane'),
                'meterc'  =>  esc_html__('Medium','auriane'),
                'meterd'  =>  esc_html__('Strong','auriane'),
                'nextNonce' => wp_create_nonce('myajax-next-nonce'),

			)
	);
     wp_enqueue_style( 'jws-style-theme', get_stylesheet_uri());  
    if(function_exists('jws_custom_css')) {
       wp_add_inline_style('jws-style-theme', jws_custom_css()); 
    } 	
    
	$page_css = get_post_meta( intval( get_the_ID() ), 'page_css', true );
    wp_add_inline_style( 'jws-style-theme',  $page_css  ); 
        
}
add_action( 'wp_enqueue_scripts', 'jws_scripts' );  
} 

add_action('redux/page/jws_option/enqueue', 'jws_theme_redux_custom_css');

function jws_theme_redux_custom_css() {
     wp_enqueue_style('jws-admin-redux-styles', JWS_URI_PATH.'/assets/css/admin-redux.css'); 
}

// Update CSS within in Admin
function jws_admin_style() {
  wp_enqueue_style('jws-admin-styles', JWS_URI_PATH.'/assets/css/admin.css');   
  wp_enqueue_style( 'jws-icon', JWS_URI_PATH . '/assets/font/jws_icon/jwsicon.css', array(), wp_get_theme()->get( 'Version' ), 'all' );   
  wp_enqueue_style( 'awesome', JWS_URI_PATH . '/assets/font/awesome/awesome.css', array(), wp_get_theme()->get( 'Version' ), 'all' ); 
  wp_enqueue_script( 'jws-admin', JWS_URI_PATH. '/assets/js/admin.js', [], '', true ); 
  wp_enqueue_style( 'select2-min', JWS_URI_PATH . '/assets/css/select2.min.css', array(), wp_get_theme()->get( 'Version' )  , 'all' ); 
  wp_enqueue_script( 'select2-min', JWS_URI_PATH. '/assets/js/lib/select2.min.js', [], wp_get_theme()->get( 'Version' ) , false );  
  	//Geocoding google
    global $jws_option; 
    $api_url     = 'https://maps.googleapis.com';
	
     if ( isset( $jws_option['google_api'] ) && '' !== $jws_option['google_api'] ) {
   
		$url      = $api_url . '/maps/api/js?key=' . $jws_option['google_api'].'&libraries=places&language=en';
        
	} else {

		$url = $api_url . '/maps/api/js';
     }

  wp_enqueue_script( 'jws_googleapis_js_places', $url, array( 'jquery' ), false, true ); 
  
  wp_localize_script(
	'jws-admin',
	'jws_script',
	array(
        'current_blog_id' => get_current_blog_id(),
		'ajax_url'        => admin_url( 'admin-ajax.php' ),
	)
  ); 
   	
}
add_action('admin_enqueue_scripts', 'jws_admin_style' , 3);


/**
 * Enhance the theme by hooking into WordPress.
*/
require_once JWS_ABS_PATH . '/inc/inc.php';

/* Disable the Widgets Block Editor*/
function widget_theme_support() {
remove_theme_support( 'widgets-block-editor' );
}
add_action( 'after_setup_theme', 'widget_theme_support' );

/**
 * Add Woocommerce To Theme
*/
if (class_exists('Woocommerce')) {   
    require_once JWS_ABS_PATH . '/inc/woocommerce-function.php';
    require_once JWS_ABS_PATH . '/woocommerce/variation-gallery.php'; 
	require_once JWS_ABS_PATH . '/woocommerce/wc-template-function.php'; 
    require_once JWS_ABS_PATH . '/woocommerce/wishlist/wishlist.php';
    require_once JWS_ABS_PATH . '/woocommerce/woocommerce-ajax.php';
} 
if( ! function_exists( 'jws_photoswipe_template' ) && class_exists('Woocommerce')) {
	function jws_photoswipe_template() {
		get_template_part('woocommerce/single-product/photo-swipe-template');
	}     
}