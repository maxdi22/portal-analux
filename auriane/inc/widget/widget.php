<?php 	
    require_once 'post-rent.php';
if (class_exists('Woocommerce')) { 
  require_once 'woocommerce-filter-attr.php';
  require_once 'product-category-list.php';
  require_once 'product-search.php';  
  require_once 'widget-filter-product.php';  
  require_once 'reset_filter.php'; 
  require_once 'class-wc-widget-layered-nav-filters.php';
  require_once 'product_checkbox_filter.php';
  require_once 'class-wc-widget-product-tag-cloud.php';  
}

function jws_remove_widget() {
   insert_remove_widget( 'WC_Widget_Product_Tag_Cloud' );
}

if(function_exists('insert_remove_widget')) {
   add_action( 'widgets_init', 'jws_remove_widget' );  
}
