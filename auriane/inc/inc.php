<?php 
if ( ! defined( 'ABSPATH' ) ) {
	exit( 'Direct script access denied.' );
}

require JWS_ABS_PATH . '/inc/template-functions.php';
require JWS_ABS_PATH . '/inc/template-tags.php';

/**
 * Install Plugin
 */
require JWS_ABS_PATH . '/inc/admin/TGM-Plugin-Activation/plugin-option.php';
require JWS_ABS_PATH . '/inc/admin/cmb2.php';
/**
 * Add Postype
 */
require_once JWS_ABS_PATH . '/inc/admin/posttyle/header_footer.php';
require_once JWS_ABS_PATH . '/inc/admin/posttyle/questions.php';
require_once JWS_ABS_PATH . '/inc/admin/posttyle/projects.php';

/**
 * Add Theme Option
 */
require_once JWS_ABS_PATH . '/inc/admin/theme_option.php';
/**
 * Add Elementor Widget
 */
if (did_action( 'elementor/loaded' ) ) { 
    require_once JWS_ABS_PATH . '/inc/elementor_widget/elementor_plugin.php';
    require_once JWS_ABS_PATH . '/inc/template_elementor.php';
} 
/**
 * Add Content Ajax.
 */  
require_once JWS_ABS_PATH . '/inc/elementor_widget/content-ajax/call-ajax-content.php';
require_once JWS_ABS_PATH . '/inc/elementor_widget/content-ajax/content-ajax-minicart.php';

/**
 * Add Menu Custom.
 */
require_once (JWS_ABS_PATH.'/inc/menu.php');
require_once (JWS_ABS_PATH.'/inc/jws_walker_page.php');

require_once JWS_ABS_PATH . '/inc/widget/widget.php';    


require_once (JWS_ABS_PATH.'/inc/admin/css_inline.php');


require_once JWS_ABS_PATH . '/inc/admin/less_to_css.php'; 