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
 * @version 3.5.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

global $jws_option; 
do_action( 'woocommerce_before_checkout_form', $checkout );
?> 
<div class="checkout-pages">
<?php
// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
	echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'auriane' ) ) );
	return;
}

?>
<div class="row">
<div class="col-lg-60 col-md-60 col-sm-12 col-xs-12 modern-left">
    <?php 
        $logo = jws_logo_url();
        if(!empty($logo)) {
            echo '<a href="'.get_home_url( '/' ).'" class="modern-logo"><img src="'.$logo.'" alt="logo" "></a>';
        }
    ?>
    <nav class="jws-nav-modern">
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" title="<?php echo esc_attr__('CART', 'auriane'); ?>"><?php echo esc_html__('CART', 'auriane'); ?></a>
        <i class="jws-icon-caret-right-thin"></i>
        <a href="javascript:void(0);" title="<?php echo esc_attr__('INFORMATION', 'auriane'); ?>" rel="nofollow" class="jws-billing-step active"><?php echo esc_html__('INFORMATION', 'auriane'); ?></a>
        <i class="jws-icon-caret-right-thin"></i>
        <a href="javascript:void(0);" title="<?php echo esc_attr__('SHIPPING', 'auriane'); ?>" rel="nofollow" class="jws-shipping-step"><?php echo esc_html__('SHIPPING', 'auriane'); ?></a>
        <i class="jws-icon-caret-right-thin"></i>
        <a href="javascript:void(0);" title="<?php echo esc_attr__('PAYMENT', 'auriane'); ?>" rel="nofollow" class="jws-payment-step"><?php echo esc_html__('PAYMENT', 'auriane'); ?></a>
    </nav>
    <div id="jws-billing-info">
        <div class="customer-info-wrap">
            <div class="customer-info customer-info-email">
                <span class="info-left"><?php echo esc_html__('Contact', 'auriane'); ?></span>
                <span class="info-right"></span>
                <a href="javascript:void(0);" class="info-change jws-billing-step"><?php echo esc_html__('Change', 'auriane'); ?></a>
            </div>
            
            <div class="customer-info customer-info-address">
                <span class="info-left"><?php echo esc_html__('Ship to', 'auriane'); ?></span>
                <span class="info-right"><span class="jws-info-address"></span></span>
                <a href="javascript:void(0);" class="info-change jws-billing-step"><?php echo esc_html__('Change', 'auriane'); ?></a>
            </div>
            
            <div class="customer-info customer-info-method">
                <span class="info-left"><?php echo esc_html__('Method', 'auriane'); ?></span>
                <span class="info-right"></span>
                <a href="javascript:void(0);" class="info-change jws-shipping-step"><?php echo esc_html__('Change', 'auriane'); ?></a>
            </div>
        </div>
    </div>
    <?php 
        woocommerce_checkout_login_form();
        woocommerce_checkout_coupon_form();
    ?>
    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">
         <div id="jws-customer-details">
        	<?php if ( $checkout->get_checkout_fields() ) : ?>
    
        		<?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>
        
        		<div id="customer_details">
        		        <?php 
                            echo '<p class="jws-require-notice hidden">' . esc_html__('This field is required.', 'auriane') . '</p>';
                            echo '<p class="jws-email-notice hidden">' . esc_html__('Email incorrect format.', 'auriane') . '</p>';
                            echo '<p class="jws-phone-notice hidden">' . esc_html__('Phone incorrect format.', 'auriane') . '</p>';  
                        ?>
        				<?php do_action( 'woocommerce_checkout_billing' ); ?>
        		
        
        		 
        				<?php do_action( 'woocommerce_checkout_shipping' ); ?>
        		
        		</div>
        
        		<?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
        
        	<?php endif; ?>
            </div>
    </form>
    <div id="next-step-shipping" class="jws-nav-step">
        <a href="<?php echo esc_url(wc_get_cart_url()); ?>" rel="nofollow" class="back-to-cart jws-back-step"><?php echo esc_html__('Return to Cart', 'auriane'); ?></a>
        <a href="javascript:void(0);" rel="nofollow" class="jws-next-step"><?php echo esc_html__('Continue to Shipping', 'auriane'); ?></a>
    </div>
    <div id="next-step-payment" class="jws-nav-step">
        <a href="javascript:void(0);" rel="nofollow" class="jws-back-step"><?php echo esc_html__('Return to Information', 'auriane'); ?></a>
        <a href="javascript:void(0);" rel="nofollow" class="jws-next-step"><?php echo esc_html__('Continue to Payment', 'auriane'); ?></a>
    </div>
</div>     

<div class="col-lg-40 col-md-40 col-sm-12 col-xs-12">
            <div class="jws_woo_your_order">
            <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

        	<?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
           
        	<div id="order_review" class="woocommerce-checkout-review-order">
        		<?php do_action( 'woocommerce_checkout_order_review' ); ?> 
        	</div>
 
        	<?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
            
            </div>
         </div>


</div>
<?php do_action( 'woocommerce_after_checkout_form', $checkout ); ?>