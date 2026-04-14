<?php
/**
 * Render custom styles.
 *
 * @since 1.0.0
 */
if ( ! function_exists( 'jws_custom_css' ) ) {
	function jws_custom_css( $css = array() ) {
    $page_id     = get_queried_object_id();

    $main_color_custom = get_post_meta($page_id, 'main_color', true);
    $bg_btn_color_custom = get_post_meta($page_id, 'button-bgcolor', true);
    $bg_btn_color2_custom = get_post_meta($page_id, 'button-bgcolor2', true);
    global $jws_option;
        /* Main Width */
        
        $website_width = (isset($jws_option['container-width']) && $jws_option['container-width']) ? $jws_option['container-width'] : '1200';
        $website_laptop_width = (isset($jws_option['container-laptop-width']) && $jws_option['container-laptop-width']) ? $jws_option['container-laptop-width'] : '1200';
        
        
        $main_color = (isset($jws_option['main-color']) && $jws_option['main-color']) ? $jws_option['main-color'] : '#A54201';
        $body_color = (isset($jws_option['color_body']) && $jws_option['color_body']) ? $jws_option['color_body'] : '#6f6f6f';
        $color_heading = (isset($jws_option['color_heading']) && $jws_option['color_heading']) ? $jws_option['color_heading'] : '#040202';
        $light_color = (isset($jws_option['color_light']) && $jws_option['color_light']) ? $jws_option['color_light'] : '#ffffff';
        $bg_btn_color = (isset($jws_option['button-bgcolor']) && $jws_option['button-bgcolor']) ? $jws_option['button-bgcolor'] : '';
        $bg_btn_color2 = (isset($jws_option['button-bgcolor2']) && $jws_option['button-bgcolor2']) ? $jws_option['button-bgcolor2'] : '';
        $btn_color = (isset($jws_option['button-color']) && $jws_option['button-color']) ? $jws_option['button-color'] : '';
        
        if ( $website_width ) { 
            	      $css[] = '@media only screen and (min-width: 1441px) {.container , .elementor-section.elementor-section-boxed > .elementor-container { max-width: ' . esc_attr( $website_width ) . 'px}}';  
        }
        if ( $website_laptop_width ) { 
            	      $css[] = '@media only screen and (max-width: 1440px) {.container , .elementor-section.elementor-section-boxed > .elementor-container { max-width: ' . esc_attr( $website_laptop_width ) . 'px}}';  
        }
        

        if(!empty($main_color)) {
          $css[] = 'body {--e-global-color-primary:' . esc_attr( $main_color ) . '; --main: ' . esc_attr( $main_color ) . '}';   
        }
        if(!empty($body_color)) {
          $css[] = 'body {--body:' . esc_attr( $body_color ) . '}';   
        }
        if(!empty($color_heading)) {
          $css[] = 'body {--heading:' . esc_attr( $color_heading ) . '}';   
        }
        if(!empty($light_color)) {
          $css[] = 'body {--light:' . esc_attr( $light_color ) . '}';   
        }
        if(!empty($bg_btn_color)) {
          $css[] = 'body {--btn-bgcolor:' . esc_attr( $bg_btn_color ) . '}';   
        }
        if(!empty($bg_btn_color2)) {
          $css[] = 'body {--btn-bgcolor2:' . esc_attr( $bg_btn_color2 ) . '}';   
        }
        if(!empty($btn_color)) {
          $css[] = 'body {--btn-color:' . esc_attr( $btn_color ) . '}';   
        }
        
        
        
        
        /* Custom Page Color */
        
        if(!empty($main_color_custom)) {
          $css[] = 'body {--e-global-color-primary:' . esc_attr( $main_color_custom ) . ' !important; --main: ' . esc_attr( $main_color_custom ) . '}';   
        }
        if(!empty($bg_btn_color_custom)) {
          $css[] = 'body {--btn-bgcolor: ' . esc_attr( $bg_btn_color_custom ) . '}';   
        }
        if(!empty($bg_btn_color2_custom)) {
          $css[] = 'body {--btn-bgcolor2: ' . esc_attr( $bg_btn_color2_custom ) . '}';   
        }
        
         /* Custom Font Family */
         $font2 = (isset($jws_option['opt-typography-font2']['font-family']) && $jws_option['opt-typography-font2']['font-family']) ? $jws_option['opt-typography-font2']['font-family'] : 'Marcellus';
         $body_font = (isset($jws_option['opt-typography-body']['font-family']) && $jws_option['opt-typography-body']['font-family']) ? $jws_option['opt-typography-body']['font-family'] : 'Outfit';
         $css[] = 'body {--body-font: ' . esc_attr( $body_font ) . ';--font2: ' . esc_attr( $font2 ) . ';}'; 

        $header_absolute = (isset($jws_option['choose-header-absolute']) && $jws_option['choose-header-absolute']) ? $jws_option['choose-header-absolute'] : '';
         if(!empty($header_absolute)) {
            foreach($header_absolute as $value) {
               $css[] ='.jws_header > .elementor-'.$value.'{position:absolute;width:100%;left:0;top:0;}' ;  
            }
         }

		return preg_replace( '/\n|\t/i', '', implode( '', $css ) );
	}
}