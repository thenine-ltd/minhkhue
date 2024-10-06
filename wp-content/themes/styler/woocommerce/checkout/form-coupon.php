<?php
/**
* Checkout coupon form
*
* This template can be overridden by copying it to yourtheme/woocommerce/checkout/form-coupon.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 7.0.1
*/

defined( 'ABSPATH' ) || exit;

if ( ! wc_coupons_enabled() ) { // @codingStandardsIgnoreLine.
return;
}

?>
<div class="woocommerce-form-coupon-toggle">
<?php wc_print_notice( apply_filters( 'woocommerce_checkout_coupon_message', esc_html__( 'Have a coupon?', 'styler' ) . ' <a href="#" class="showcoupon">' . esc_html__( 'Click here to enter your code', 'styler' ) . '</a>' ), 'notice' ); ?>
</div>

<form class="checkout_coupon woocommerce-form-coupon" method="post" style="display:none">

    <p class="styler-coupon-info"><?php esc_html_e( 'If you have a coupon code, please apply it below.', 'styler' ); ?></p>
    <?php if ( is_checkout() ) : ?>
        <p class="form-row styler-coupon-row">
            <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'styler' ); ?>" id="coupon_code" value="" />
            <button type="submit" class="button styler-btn-medium styler-btn styler-bg-black" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'styler' ); ?>"><?php esc_html_e( 'Apply coupon', 'styler' ); ?><span class="loading-wrapper"><span class="ajax-loading"></span></span></button>
        </p>
    <?php else : ?>
        <p class="form-row styler-coupon-row">
            <input type="text" name="coupon_code" class="input-text" placeholder="<?php esc_attr_e( 'Coupon code', 'styler' ); ?>" id="coupon_code" value="" />
            <button type="submit" class="button styler-btn-medium styler-btn styler-bg-black" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'styler' ); ?>"><?php esc_html_e( 'Apply coupon', 'styler' ); ?><span class="loading-wrapper"><span class="ajax-loading"></span></span></button>
        </p>
    <?php endif; ?>

    <div class="clear"></div>
</form>
