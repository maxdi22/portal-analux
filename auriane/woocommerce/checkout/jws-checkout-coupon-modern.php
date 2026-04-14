<?php
/**
 * Checkout coupon form Clone
 * 
 * @author  JwsTheme
 * @package Jws-theme/WooCommerce
 * Since 1.0
 */
defined('ABSPATH') || exit;

if (!wc_coupons_enabled()) {
    return;
}
$coupn_text = jws_theme_get_option('checkout_coupon_text');
$coupn_text = !empty($coupn_text) ? $coupn_text : 'Have a coupon code? Try with "AURIANE"';
?>
<tr><td colspan="2" class="coupon-clone-td">
<a href="javascript:void(0);" class="showcoupon-clone" rel="nofollow"><?php echo esc_html__($coupn_text, 'auriane'); ?></a>
<div class="form-row coupon-clone-wrap hidden">
        <input type="text" name="coupon_code_clone" class="input-text" placeholder="<?php esc_attr_e('Coupon code', 'auriane'); ?>" id="coupon_code-clone" value="" />
        <button  class="button" name="apply_coupon_clone" value="<?php esc_attr_e('Apply coupon', 'auriane'); ?>" id="apply_coupon_clone"><?php esc_html_e('Apply', 'auriane'); ?></button>
</div>
</td></tr>
