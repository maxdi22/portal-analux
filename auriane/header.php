<?php
/**
 * The header for our theme
 *
 * This is the template that displays all of the <head> section and everything up until <div id="content">
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package WordPress
 * @subpackage auriane
 * @since 1.0.0
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link rel="profile" href="https://gmpg.org/xfn/11" />
	<?php wp_head(); ?>
</head>
<?php  
    global $jws_option; 
?>
<body <?php body_class(); ?>>

<?php wp_body_open(); ?>
      
<div id="page" class="site">
    <?php if(function_exists('jws_header')) jws_header(); ?>
	<div id="content" class="site-content">
    <?php 
    if(function_exists('jws_title_bar')) {
        if(is_page()) {
           if((get_post_meta( get_the_ID(), 'title_bar_checkbox', 1 ) == null || !get_post_meta( get_the_ID(), 'title_bar_checkbox', 1 ))) {
                jws_title_bar();
           }  
        }elseif((is_single() && 'product' == get_post_type())){
            if((isset($jws_option['product-single-title-bar-switch']) && $jws_option['product-single-title-bar-switch']) || !isset($jws_option['product-single-title-bar-switch'])) {
              jws_title_bar();  
            } 
        }elseif((is_single() && 'projects' == get_post_type())){
            if((isset($jws_option['projects-title-bar-switch']) && $jws_option['projects-title-bar-switch']) || !isset($jws_option['projects-title-bar-switch'])) {
              jws_title_bar();  
            } 
        }else {
           jws_title_bar(); 
        }  
    } 
?>