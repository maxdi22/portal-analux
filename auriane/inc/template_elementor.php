<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
/**
 * Elementor class
 *
 * @package OceanWP WordPress theme
 */

if ( ! class_exists( 'Jws_Elementor' ) ) :

	class Jws_Elementor {

		/**
		 * Setup class.
		 *
		 * @since 1.4.0
		 */
		public function __construct() {
			add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_styles' ));
		}


		/**
		 * Get the header ID.
		 *
		 * @since 1.4.0
		 */
		public static function get_header_id() {

			// Template
		    global $jws_option; 
            if(class_exists('Woocommerce')) {
                $is_shop = (is_shop() || is_product() || is_product_category() || is_product_tag());
            }
            $id = '';
            $page_header = (is_page()) ? get_post_meta(get_the_ID(), 'page_select_header', true) : '';
     

                    if((is_single() && 'post' == get_post_type()) && (isset($jws_option['select-header-blog']) && !empty($jws_option['select-header-blog']))) {
                                    
                    $id =  $jws_option['select-header-blog'];
                                    
                    }elseif((is_single() && 'services' == get_post_type()) && (isset($jws_option['select-header-service']) && !empty($jws_option['select-header-service']))){
            
                                   $id = $jws_option['select-header-service'];
                                  
                    }elseif(('sfwd-courses' == get_post_type()) && (isset($jws_option['select-header-course']) && !empty($jws_option['select-header-course']))){
            
                                   $id = $jws_option['select-header-course'];  
                                  
                    }elseif((isset($is_shop) && !empty($is_shop)) && (isset($jws_option['select-header-shop']) && !empty($jws_option['select-header-shop']))){
                                       $id = $jws_option['select-header-shop'];  
                    }elseif(isset($page_header) && !empty($page_header)){
                                $id = $page_header; 
                    } 
                    elseif(isset($jws_option['select-header'])) {
                            $id = $jws_option['select-header'];
                    }    

			// If template is selected
			if ( ! empty( $id ) ) {
		
				return $id;
			}

			// Return
			return false;
			
		}
        
        /**
		 * Get the footer ID.
		 *
		 * @since 1.4.0
		 */
		public static function get_footer_id() {

			// Template
		    global $jws_option; 
            $is_shop = $id = '';
            if(class_exists('Woocommerce')) {
                $is_shop = (is_shop() || is_product() || is_product_category() || is_product_tag());
            }
            $page_footer = (is_page()) ? get_post_meta(get_the_ID(), 'page_select_footer', true) : '';
            if(isset($page_footer) && !empty($page_footer)){ 
                $id = $page_footer; 
            }elseif((is_single() && 'services' == get_post_type()) && (isset($jws_option['select-footer-service']) && !empty($jws_option['select-footer-service']))){
            
                    $id = $jws_option['select-footer-service'];
                                  
             }elseif((isset($is_shop) && !empty($is_shop)) && (isset($jws_option['select-footer-shop']) && !empty($jws_option['select-footer-shop']))){
                    $id = $jws_option['select-footer-shop'];  
            }elseif (isset($jws_option['select-footer']) && !empty($jws_option['select-footer'])) {
               $id = $jws_option['select-footer'];
            }


			// If template is selected
			if ( ! empty( $id ) ) {
				return $id;
			}

			// Return
			return false;
			
		}
        
        /**
		 * Get the 404 ID.
		 *
		 * @since 1.4.0
		 */
		public static function get_404_id() {
			// Template
		    global $jws_option; 
            $id = '';
            if (isset($jws_option['select-content-404']) && !empty($jws_option['select-content-404'])) {
               $id = $jws_option['select-content-404'];
            }
			// If template is selected
			if ( ! empty( $id ) ) {
				return $id;
			}

			// Return
			return false;
			
		}

        
        /**
		 * Get the Title Bar ID.
		 *
		 * @since 1.4.0
		 */
		public static function get_titlebar_id() {

			// Template
		    global $jws_option; 
            $id = '';
            $page_titlebar = (is_page()) ? get_post_meta(get_the_ID(), 'page_select_titlebar', true) : '';
            $is_shop = '';
            $is_shop_single = '';
            if(class_exists('Woocommerce')) {
                $is_shop = (is_shop() || is_product_category() || is_product_tag());
                $is_shop_single = (is_product());
            }
            if((is_single() && 'projects' == get_post_type()) && (isset($jws_option['select-titlebar-projects']) && !empty($jws_option['select-titlebar-projects'])) ){
    
               $id = $jws_option['select-titlebar-service'];  
              
            }elseif((is_single() && 'post' == get_post_type()) && (isset($jws_option['select-titlebar-blog']) && !empty($jws_option['select-titlebar-blog'])) ){
    
               $id = $jws_option['select-titlebar-blog'];  
              
            }elseif(((is_archive() || is_home()) && 'post' == get_post_type()) && (isset($jws_option['select-titlebar-blog-archive']) && !empty($jws_option['select-titlebar-blog-archive'])) ){
    
               $id = $jws_option['select-titlebar-blog-archive']; 
              
            }elseif($is_shop && (isset($jws_option['select-titlebar-shop']) && !empty($jws_option['select-titlebar-shop'])) ){
               if(isset($_GET['shop_layout']) && $_GET['shop_layout'] == '2') {  
                 $id = '5999'; 
               }elseif(isset($_GET['shop_layout']) && ($_GET['shop_layout'] == '3' || $_GET['shop_layout'] == '4')){
                 $id = '6181';   
               } else {
                 $id = $jws_option['select-titlebar-shop']; 
               }
            }elseif($is_shop_single && (isset($jws_option['select-titlebar-shop-single']) && !empty($jws_option['select-titlebar-shop-single'])) ){
    
               $id = $jws_option['select-titlebar-shop-single']; 
              
            }elseif(isset($page_titlebar) && !empty($page_titlebar)){
                
               $id = $page_titlebar; 
                
            } elseif(isset($jws_option['select-titlebar'])) {
                            $id = $jws_option['select-titlebar'];
             }
             
             
            $page_css = get_post_meta( intval( $id ), 'page_css', true );
            wp_add_inline_style( 'jws-style-theme',  $page_css  ); 

			// If template is selected
			if ( ! empty( $id ) ) {
				return $id;
			}
			// Return
			return false;
			
		}
        
       /**
		 * Get the footer ID.
		 *
		 * @since 1.4.0
		 */
		public static function get_blog_sidebar_single_id() {

			// Template
		    global $jws_option; 
            $id = '';

            if (isset($jws_option['select-sidebar-post-single']) && !empty($jws_option['select-sidebar-post-single'])) { 
                $id = $jws_option['select-sidebar-post-single']; 
            }


			// If template is selected
			if ( ! empty( $id ) ) {
				return $id;
			}

			// Return
			return false;
			
		}

		public static function get_sidebar_post_id() {
			// Template
		    global $jws_option; 
            $id = '';
            if (isset($jws_option['select-sidebar-post']) && !empty($jws_option['select-sidebar-post'])) {
               $id = $jws_option['select-sidebar-post'];
            }
			// If template is selected
			if ( ! empty( $id ) ) {
				return $id;
			}

			// Return
			return false;
			
		}
        
		/**
		 * Enqueue styles
		 *
		 * @since 1.4.0
		 */
		public static function enqueue_styles() {

			if ( class_exists( '\Elementor\Core\Files\CSS\Post' ) ) {

				$header_id 					= self::get_header_id();
                $footer_id 					= self::get_footer_id();
                $titlebar_id 					= self::get_titlebar_id();
                $page404_id 					= self::get_404_id();
                $blog_sidebar_single_id = self::get_blog_sidebar_single_id();
                $blog_sidebar_id = self::get_sidebar_post_id();
                
				// Enqueue header css file
				if ( false != $header_id ) {
					$error_css = new \Elementor\Core\Files\CSS\Post( $header_id );
					$error_css->enqueue();
                    $page_css = get_post_meta( intval( $header_id ), '_elementor_page_settings', true );
                    if(isset($page_css['page_css'])) {
                       wp_add_inline_style( 'jws-style-theme',  $page_css['page_css']  );  
                    }
                    
				}
                // Enqueue footer css file
				if ( false != $footer_id ) {
					$error_css = new \Elementor\Core\Files\CSS\Post( $footer_id );
					$error_css->enqueue();
				}
                // Enqueue title bar css file
				if ( false != $titlebar_id ) {
					$error_css = new \Elementor\Core\Files\CSS\Post( $titlebar_id );
					$error_css->enqueue();
				}
                // Enqueue blog sidebar css file
				if ( false != $blog_sidebar_single_id ) {
					$error_css = new \Elementor\Core\Files\CSS\Post( $blog_sidebar_single_id );
					$error_css->enqueue();
				}
				if ( false != $blog_sidebar_id ) {
					$error_css = new \Elementor\Core\Files\CSS\Post( $blog_sidebar_id );
					$error_css->enqueue();
				}
                // Enqueue 404 css file
				if ( false != $page404_id ) {
					$error_css = new \Elementor\Core\Files\CSS\Post( $page404_id );
					$error_css->enqueue();
				}
                
                

			}

		}

		/**
		 * Prints header content.
		 *
		 * @since 1.4.0
		 */
		public static function display_header() {
			echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( self::get_header_id() );
		}
        /**
		 * Prints footer content.
		 *
		 * @since 1.4.0
		 */
		public static function display_footer() {
			echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( self::get_footer_id() );
		}
        /**
		 * Prints title bar content.
		 *
		 * @since 1.4.0
		 */
		public static function display_titlebar() {
			echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( self::get_titlebar_id() );
		}
        
        /**
		 * Prints 404 content.
		 *
		 * @since 1.4.0
		 */
		public static function display_404() {
			echo Elementor\Plugin::instance()->frontend->get_builder_content_for_display( self::get_404_id() );
		}

	}

endif;

 new Jws_Elementor();

 ?>