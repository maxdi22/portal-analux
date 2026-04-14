<?php
/**
 * Show options for ordering
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/loop/orderby.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce/Templates 
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

$shop = jws_check_layout_shop();

$list_view_layout = array(
    '4',
    '3',
    '2',
	'1',
);

$layout_css_style = array();
foreach ( $list_view_layout as $key => $value ) {
	$layout_css_style[ $key ] = ( $shop['shop_columns']  === $value ) ? "sel-active" : '';
}
global $wp;
if ( '' === get_option( 'permalink_structure' ) ) {
	$form_action = remove_query_arg( array( 'page', 'paged' ), add_query_arg( $wp->query_string, '', home_url( $wp->request ) ) );
} else {
	$form_action = preg_replace( '%\/page/[0-9]+%', '', home_url( trailingslashit( $wp->request ) ) );
}
?>
<div class="col-xl-6 col-lg-6 col-6">
<div class="shop-top-filters-right">
       <?php if($shop['filter_layout'] == 'top') { ?>
            <p class="woocommerce-result-count">
            	<?php
                    jws_woo_found();
            	?>
            </p>
       <?php } ?> 
    <?php if(isset($shop['columns_review'])) : ?>
	<div class="grid-view">
    <span><?php echo esc_html__('VIEW','auriane'); ?></span>
	<div class="view-icon">
        <div class="view-icon-list">
        <?php if($shop['columns_review']['4'] == '1' && $shop['filter_layout'] != 'top') : ?>
        <a class="view-product-4 <?php echo esc_attr( $layout_css_style[0] ); ?>" data-id="4" href="<?php echo add_query_arg('lay_style', '4', jws_shop_page_link(true)); ?>"></a>
        <?php endif; ?>
        <?php if($shop['columns_review']['3'] == '1') : ?>
		<a class="view-product-3 <?php echo esc_attr( $layout_css_style[1] ); ?>" data-id="3" href="<?php echo add_query_arg('lay_style', '3', jws_shop_page_link(true)); ?>"></a>
        <?php endif; ?>
         <?php if($shop['columns_review']['2'] == '1' && !$shop['fullwidth']) : ?>
		<a class="view-product-2 <?php echo esc_attr( $layout_css_style[2] ); ?>" data-id="2" href="<?php echo add_query_arg('lay_style', '2', jws_shop_page_link(true)); ?>"></a>
        <?php endif; ?>
        <?php if($shop['columns_review']['1'] == '1' && $shop['filter_layout'] != 'top') : ?>
        <a class="view-product-1 <?php echo esc_attr( $layout_css_style[3] ); ?>" data-id="1" href="<?php echo add_query_arg('lay_style', '1', jws_shop_page_link(true)); ?>"></a>
        <?php endif; ?>
	    </div>
    </div>
	</div><!--.grid-view-->
    <?php endif; ?>
    <div class="woo-ordering">
    <form class="woocommerce-ordering" method="get" action="<?php echo esc_url( $form_action ); ?>">
    	<select name="orderby" class="orderby" aria-label="<?php esc_attr_e( 'Shop order', 'auriane' ); ?>">
    		<?php foreach ( $catalog_orderby_options as $id => $name ) : ?>
    			<option value="<?php echo esc_attr( $id ); ?>" <?php selected( $orderby, $id ); ?>><?php echo esc_html( $name ); ?></option>
    		<?php endforeach; ?>
    	</select>
        <div class="orderby">
            <span class="orderby-current"><?php echo esc_html($catalog_orderby_options[$orderby]); ?></span>
            <ul>
            	<?php foreach ( $catalog_orderby_options as $id => $name ) :
                      $current = ($orderby == $id) ? 'current' : '';  
                 ?>
        			<li><span class="<?php echo esc_attr($current); ?>" data-orderby="<?php echo esc_attr( $id ); ?>"><?php echo esc_html( $name ); ?></span></li>
        		<?php endforeach; ?>
            </ul>
        </div>
    	<input type="hidden" name="paged" value="1" />
    	<?php wc_query_string_form_fields( null, array( 'orderby', 'submit', 'paged', 'product-page' ) ); ?>
    </form>
    </div>
</div>
</div>
</div>