<?php
/**
 * Loop Rating
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/rating.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $product;

if ( ! wc_review_ratings_enabled() ) {
	return;
}
$review_count = $product->get_review_count();
$rating = $product->get_average_rating();
if ( $rating > 0 ) { 
    $rating_html = '<div class="star-rating-wap">'; 
    $rating_html .= '<span class="star-rating">'; 
    $rating_html .= '<span style="width:' . ( ( $rating / 5 ) * 100 ) . '%"></span>'; 
    $rating_html .= '</span>'; 
    $rating_html .= '<span class="review-count">('.$review_count.')</span>'; 
    $rating_html .= '</div>'; 
} else { 
    $rating_html = ''; 
} 
echo apply_filters( 'woocommerce_product_get_rating_html', $rating_html, $rating ); 