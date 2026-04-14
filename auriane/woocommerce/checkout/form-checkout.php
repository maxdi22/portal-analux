<?php
/**
 * Checkout Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-checkout.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $jws_option; 
$layout = jws_checkout_layout();

echo '<div class="page_checkout_'.$layout.'">';

include( JWS_ABS_PATH.'/woocommerce/checkout/layout/'.$layout.'.php' );

echo '</div>';