<?php
/**
 * Result Count
 *
 * Shows text: Showing x - x of x results.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/result-count.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
$shop = jws_check_layout_shop();
?>
<div class="row shop-nav-top<?php echo ' filter-'.$shop['filter_layout'].''; ?>">
    <div class="col-xl-6 col-lg-6 col-6">
    <?php 
        if( $shop['filter_layout'] == 'top') { ?>
          <div class="filter-shop-nav">
            <span><?php echo esc_html__('FILTER BY','auriane'); ?></span>
            <?php 
              if ( is_active_sidebar( 'sidebar-shop-filter-navtop' ) ) {
                    dynamic_sidebar( 'sidebar-shop-filter-navtop' );  
              } 
            ?>
          </div>  
        <?php }else { ?>
        <?php
        if($shop['position'] != 'left' && $shop['position'] != 'right') { ?>
                <button class="show_filter_shop"><i class="jws-icon-plus"></i><?php echo esc_html__('FILTER','auriane'); ?></button> 
        <?php } else {?>
            <button class="show_filter_shop hidden_dektop"><i class="jws-icon-plus"></i><?php echo esc_html__('FILTER','auriane'); ?></button> 
        <?php } ?>
            <p class="woocommerce-result-count">
            	<?php
                	jws_woo_found();
            	?>
            </p>
        <?php } ?>
         
</div>