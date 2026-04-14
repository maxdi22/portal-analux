<?php
/**
 * Single Product tabs
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
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
	exit;
}

/**
 * Filter tabs and allow third parties to add their own.
 *
 * Each tab is an array containing title, callback and priority.
 *
 * @see woocommerce_default_product_tabs()
 */
global $jws_option; 
$meta_layout = get_post_meta( get_the_ID(), 'shop_single_layout', true );
$layout = ($meta_layout) ? $meta_layout : ( jws_theme_get_option('shop_single_layout') ? jws_theme_get_option('shop_single_layout') : 'default' ); 
$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
$tab_style = 'woocommerce-tabs wc-tabs-wrapper';
if($layout == 'default') {
    $tab_style .= ' horizontal';
    $tabs = true;
}elseif($layout == 'vertical_metro'){
    $tab_style .= ' horizontal tabs_modal';
    $tabs = true;
}else{
    $tab_style .= ' vertical';
    $tabs = false;
}
if ( ! empty( $product_tabs ) ) : ?>

	<div class="<?php echo esc_attr($tab_style); ?>">
        <?php if($tabs) : ?>
        <div class="container">
		<ul class="tabs wc-tabs" role="tablist">
			<?php foreach ( $product_tabs as $key => $product_tab ) : ?>
				<li class="<?php echo esc_attr( $key ); ?>_tab" id="tab-title-<?php echo esc_attr( $key ); ?>" role="tab" aria-controls="tab-<?php echo esc_attr( $key ); ?>">
					<a href="#tab-<?php echo esc_attr( $key ); ?>">
						<?php echo ''. apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ; ?>
					</a>
				</li>
			<?php endforeach; ?>
		</ul>
        </div>
        <?php endif; ?>
        <div class="jws-group-accordion-wap">
   
		<?php $i = 1; foreach ( $product_tabs as $key => $product_tab ) : ?>
        <div class="jws-group-accordion <?php if($i == 1) echo esc_attr('accordion-active'); ?>">
            <h5 class="tab-heading"><?php echo esc_html($product_tab['title']); ?><span class="jws-icon-arrow_carrot-down"></span></h5>
			<div class="woocommerce-Tabs-panel woocommerce-Tabs-panel--<?php echo esc_attr( $key ); ?> panel entry-content wc-tab" id="tab-<?php echo esc_attr( $key ); ?>" role="tabpanel" aria-labelledby="tab-title-<?php echo esc_attr( $key ); ?>">
                <?php
    				if ( isset( $product_tab['callback'] ) ) {
    					call_user_func( $product_tab['callback'], $key, $product_tab );
    				}
				?>
			</div>
        </div>
        
		<?php $i++; endforeach; ?>
       
  </div>  
		<?php do_action( 'woocommerce_product_after_tabs' ); ?>
	</div>

<?php endif; ?>