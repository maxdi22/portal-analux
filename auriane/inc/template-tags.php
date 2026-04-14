<?php
// Do not allow directly accessing this file.
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
} 
// **********************************************************************// 
// ! Add favicon 
// **********************************************************************// 
if (!function_exists('jws_favicon')) {
    function jws_favicon()
    {

        if (function_exists('has_site_icon') && has_site_icon()) return '';

        // Get the favicon.
        $favicon = '';


        global $jws_option;
        
        if(isset($jws_option['favicon']) && !empty($jws_option['favicon'])) {
            $favicon = $jws_option['favicon']['url'];
        }

        ?>
        <link rel="shortcut icon" href="<?php echo esc_attr($favicon); ?>">
        <link rel="apple-touch-icon-precomposed" sizes="152x152" href="<?php echo esc_attr($favicon); ?>">
        <?php
    }

    add_action('wp_head', 'jws_favicon');
}

if (!function_exists('jws_logo_url')) {
    function jws_logo_url()
    {

        $logo = '';
        global $jws_option;
        
        if(isset($jws_option['logo']) && !empty($jws_option['logo'])) {
            if(!empty($jws_option['logo'])) {
                $logo = $jws_option['logo']['url'];
            }
        }
        
        return $logo;

      
    }
}

//Lets add Open Graph Meta Info
 
function jws_insert_fb_in_head() {
    global $post;
    if ( !is_singular()) //if it is not a post or a page
        return;
        echo '<meta property="og:title" content="' . get_the_title() . '"/>';
        echo '<meta property="og:type" content="article"/>';
        echo '<meta property="og:url" content="' . get_permalink() . '"/>';
        echo '<meta property="og:site_name" content="'.get_bloginfo( 'name' ).'"/>';
    if(has_post_thumbnail( $post->ID )) { //the post does not have featured image, use a default image
        $thumbnail_src = wp_get_attachment_image_src( get_post_thumbnail_id( $post->ID ), 'full' );
        echo '<meta property="og:image" content="' . esc_attr( !empty($thumbnail_src[0]) ? $thumbnail_src[0] : '' ) . '"/>';
        echo '<meta property="og:image:secure_url" content="' . esc_attr( !empty($thumbnail_src[0]) ? $thumbnail_src[0] : ''  ) . '">';
        echo '<meta property="og:image:width" content="500">';
        echo '<meta property="og:image:height" content="400">';
    }

    echo "
";
}
add_action( 'wp_head', 'jws_insert_fb_in_head', 5 );



/**
 * Add extra initialisation for Contact 7 Form in Elementor popups.
 **/
add_action( 'wp_footer', 'jws_back_top_top'); 
function jws_back_top_top() {
    global $jws_option;
    $layout = (isset($jws_option['to-top-layout'])) ? $jws_option['to-top-layout'] : 'with-shadow';
    $class = 'backToTop fas fa-arrow-up ';
    $class .= $layout;
    if(isset($jws_option['box-change-style']) && $jws_option['box-change-style']) {
        require_once JWS_ABS_PATH.'/inc/box-style.php';
    }
    ?>
        <a href="#" class="<?php echo esc_attr($class); ?>"></a>
    <?php
}

/**
 * Add toolbar for mobile.
 **/
add_action( 'wp_footer', 'jws_toolbar_mobile'); 
function jws_toolbar_mobile() {
    if (class_exists('Woocommerce') && jws_theme_get_option('toolbar_fix')) { 
    $shop = jws_check_layout_shop();
    ?>
        <div class="jws-toolbar-wap">
            <?php  if((is_home() && ((isset($_GET['sidebar']) && $_GET['sidebar'] != 'full') || !isset($_GET['sidebar'])) ) || (is_single() && 'post' == get_post_type() && !isset($_GET['sidebar']))) : ?>
            <div class="jws-toolbar-item">
                <a class="show_filter_shop" href="javascript:void(0)">
                    <i aria-hidden="true" class="jws-icon-dots-three-outline-vertical"></i>
                    <span><?php echo esc_html__('Sidebar','auriane'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php  if(is_shop() && ($shop['filter_layout'] == 'sideout' || $shop['position'] == 'left' || $shop['position'] == 'right')) : ?>
            <div class="jws-toolbar-item">
                <a class="show_filter_shop" href="javascript:void(0)">
                    <i aria-hidden="true" class="jws-icon-funnel"></i>
                    <span><?php echo esc_html__('Filter','auriane'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(jws_theme_get_option('toolbar_shop')) : ?>
            <div class="jws-toolbar-item">
                <a href="<?php echo get_permalink( wc_get_page_id( 'shop' ) ); ?>">
                    <i aria-hidden="true" class="jws-icon-storefront"></i>
                    <span><?php echo esc_html__('Shop','auriane'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(jws_theme_get_option('toolbar_search')) : ?>
            <div class="jws-toolbar-item">
                <a class="jws_toolbar_search" href="#">
                    <i aria-hidden="true" class="jws-icon-magnifying-glass-light"></i>
                    <span><?php echo esc_html__('Search','auriane'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(function_exists('jws_get_wishlist_page_url') && jws_theme_get_option('toolbar_wishlist')) : ?>
            <div class="jws-toolbar-item">
                <a class="jws_toolbar_wishlist" href="<?php echo esc_url( jws_get_wishlist_page_url() ); ?>">
                    <span class="jws_wishlist_count"><?php  echo jws_get_wishlist_count(); ?></span>
                    <i aria-hidden="true" class="jws-icon-heart-straight-light"></i>
                    <span><?php echo esc_html__('Wishlist','auriane'); ?></span>
                </a>
            </div>
            <?php endif; ?>
            <?php if(jws_theme_get_option('toolbar_account')) : ?>
            <div class="jws-toolbar-item">
                <a class="jws-open-login<?php if(is_user_logged_in()) echo ' logged'; ?>" href="<?php echo get_permalink( wc_get_page_id( 'myaccount' ) ); ?>">
                    <i aria-hidden="true" class="jws-icon-user-circle-light"></i>
                    <span><?php echo esc_html__('My account','auriane'); ?></span>
                </a>
            </div>
             <?php endif; ?>
             <?php 
                $custom_toolbar = jws_theme_get_option('toolbar_custom');
                $i = 0;
                if(!empty($custom_toolbar['redux_repeater_data'])) {
                   foreach($custom_toolbar['redux_repeater_data'] as $value) {
                        if(!empty($custom_toolbar['toolbar_custom_link'][$i])) {
                        ?>
                          <div class="jws-toolbar-item">
                                <a href="<?php echo esc_url($custom_toolbar['toolbar_custom_link'][$i]); ?>">
                                    <i aria-hidden="true" class="<?php echo esc_attr($custom_toolbar['toolbar_custom_icon'][$i]); ?>"></i>
                                    <span><?php echo esc_html($custom_toolbar['toolbar_custom_name'][$i]); ?></span>
                                </a>
                          </div>
                        <?php
                        }
                    $i++;} 
                }
             ?>
        </div>
    <?php
    }
}


/**
 * Add toolbar for mobile.
 **/
add_action( 'wp_footer', 'jws_form_login_popup'); 
function jws_form_login_popup() {
    global $jws_option;
    ?>
        <div class="jws-form-login-popup">
            <div class="jws-form-overlay"></div>
            <div class="jws-form-content">
                <div class="jws-close"><i aria-hidden="true" class="jws-icon-cross"></i></div>
                <?php jws_get_content_form_login(true,true,'login'); ?>
            </div>
        </div>
    <?php
}



/**
 * Add newseleter popup.
 **/
add_action( 'wp_footer', 'jws_form_newsletter_popup'); 
function jws_form_newsletter_popup() {
    global $jws_option;
    if(jws_theme_get_option('newsletter_enble') && !is_page( 'Landing Page' )) :
    ?>
        <div class="jws-newsletter-popup mfp-hide">
            <div class="jws-form-content">
                <div class="row">
                    <div class="col-xl-6 col-lg-6 col-12 newsletter-bg hidden_mobile">
                    
                    </div>
                    <div class="col-xl-6 col-lg-6 col-12">
                        <div class="newsletter-content">
                        <?php 
                            if(isset($jws_option['newsletter_content'])){
                                 echo do_shortcode($jws_option['newsletter_content']);
                            }
                            if(isset($jws_option['newsletter_no_thank'])){
                                echo '<a href="javascript:void(0)" class="sub-new-nothank">'.$jws_option['newsletter_no_thank'].'</a>';
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>    
    <?php
}


/**
 * Add extra initialisation for Contact 7 Form in Elementor popups.
 **/
function jws_ct_body_classes( $classes ) {
    global $jws_option;
    $layout = (isset($jws_option['button-layout'])) ? $jws_option['button-layout'] : 'default';
    $classes[] = 'button-'.$layout;
    if ( !is_user_logged_in() ) {
            $classes[] = 'user-not-logged-in';
    }

    $layoutcars = (isset($jws_option['cars-detail-layout'])) ? $jws_option['cars-detail-layout'] : 'layout1';
    
    if(isset($_GET['car_layout']) && $_GET['car_layout'] == 'layout2') {
         $layoutcars =  'layout2';
    }
    if($layoutcars == 'layout2') {
       $classes[] = 'hidden-title-bar'; 
    }
    
    if(isset($jws_option['shop_single_layout'])) {
       $classes[] = 'single-product-'.$jws_option['shop_single_layout']; 
    }
    
    if(!did_action( 'elementor/loaded' )) {
       $classes[] = 'not-elementor';  
    }
    /** Footer **/
    if(isset($jws_option['footer-switch-parallax']) && $jws_option['footer-switch-parallax']) {
    $classes[] = 'footer-parallax';
    }
    /** rtl **/
    $classes[] = (isset($jws_option['rtl']) && $jws_option['rtl']) ? 'rtl' : '';
    /** toolbar **/
    $classes[] = jws_theme_get_option('toolbar_fix') ? 'has_toolbar' : '';
    
    
      
    return $classes;
}
add_filter( 'body_class','jws_ct_body_classes' );

function jws_mini_cart_content2() { ?>
        <div class="jws-mini-cart-wrapper">
            <div class="jws-cart-sidebar">
                <div class="jws_cart_content">
                </div>
            </div>
            <div class="jws-cart-overlay"></div>
        </div>   
<?php }
if (class_exists('Woocommerce')) { 
   add_action( 'wp_footer', 'jws_mini_cart_content2' ); 
}

function jws_filter_backups_demos($demos)
	{
		$demos_array = array(
			'auriane' => array(
				'title' => esc_html__('Auriane Demo', 'auriane'),
				'screenshot' => 'https://gavencreative.com/import_demo/auriane/screenshot.jpg',
				'preview_link' => 'https://auriane.jwsuperthemes.com',
			),
		);
        $download_url = 'https://gavencreative.com/import_demo/auriane/download-script/';
		foreach ($demos_array as $id => $data) {
			$demo = new FW_Ext_Backups_Demo($id, 'piecemeal', array(
				'url' => $download_url,
				'file_id' => $id,
			));
			$demo->set_title($data['title']);
			$demo->set_screenshot($data['screenshot']);
			$demo->set_preview_link($data['preview_link']);
			$demos[$demo->get_id()] = $demo;
			unset($demo);
		}
		return $demos;
}
add_filter('fw:ext:backups-demo:demos', 'jws_filter_backups_demos');
if (!function_exists('jws_deactivate_plugins')){
	function jws_deactivate_plugins() {
		deactivate_plugins(array(
			'brizy/brizy.php'
		));    
		
	}
}
add_action( 'admin_init', 'jws_deactivate_plugins' );


if(class_exists('jws_theme_jwsLove') && !function_exists('post_favorite') ) {
    function post_favorite($return = '',$unit = '',$show_icon = true) {
    	global $post_favorite , $post;
        $love_count = get_post_meta(get_the_ID(), '_jws_love', true);
        if($love_count == '1') {
           $unit = esc_html__(' like','auriane'); 
        }else{
           $unit = esc_html__(' likes','auriane');  
        }
    	if($return == 'return') {
    		return $post_favorite->add_love($unit,$show_icon);
    	} else {
    		echo ''.$post_favorite->add_love($unit,$show_icon);
    	}
    }    
}


if (defined('aurianecore')) {

add_action( 'admin_menu', 'jws_add_menu_page' );


}

if(!function_exists('jws_add_menu_page')) {
  function jws_add_menu_page() {
    add_menu_page( 'Jws Settings', 'Jws Settings', 'manage_options', 'jws_settings.php', 'jws_settings', '', 3 );
  }  
}


// Hide all posts from users who are not logged-in or are not administrators or members
function jws_exclude_posts($query) {
  global $jws_option;
  if(isset($jws_option['exclude-blog']) && !empty($jws_option['exclude-blog'])) {
     $result = array_map('intval', array_filter($jws_option['exclude-blog'], 'is_numeric'));
     if(!is_admin() && $query->is_main_query() && !is_single()){
        set_query_var('post__not_in', $result);
    }  
  }
  if ( $query->is_post_type_archive( 'questions' ) && $query->is_main_query() && ! is_admin() ) {
    $query->set( 'posts_per_page', jws_theme_get_option('auestions-number') );
    if(isset($_GET['product_questions'])) {
        $meta_query = (array) $query->get( 'meta_query' );
         $meta_query[] = array(
            array(
    				'key'     => 'product_questions',
    				'value'   => sanitize_text_field( $_GET['product_questions'] ),
    				'compare' => 'LIKE',
    		)
       ) ;
	$query->set( 'meta_query', $meta_query );	
    }
     
  }
}
add_action('pre_get_posts', 'jws_exclude_posts');


