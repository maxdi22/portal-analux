<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}
global $jws_option;  

do_action( 'woocommerce_before_customer_login_form' );
echo '<div class="woo-account-form">';
    if((isset($jws_option['select-shop-form-login']) && !empty($jws_option['select-shop-form-login']))) {
        echo do_shortcode('[hf_template id="'.$jws_option['select-shop-form-login'].'"]'); 
    }else{
      jws_get_content_form_login(true,true,'login');
    } 
    do_action( 'woocommerce_after_customer_login_form' ); 
echo '</div>';