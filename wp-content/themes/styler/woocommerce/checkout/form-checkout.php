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
* @package WooCommerce\Templates
* @version 3.5.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'styler' ) ) );
    return;
}

$styler_checkout_type = apply_filters( 'styler_checkout_enable_multistep', styler_settings( 'checkout_enable_multistep', 'default' ) );

if ( 'multisteps' == $styler_checkout_type || isset( $_GET['iframe_checkout'] ) && esc_html( $_GET['iframe_checkout'] ) == true ) {

    if ( isset( $_GET['iframe_checkout'] ) && esc_html( $_GET['iframe_checkout'] ) == true ) {
        styler_ajax_checkout_popup();
    } else {
        wc_get_template_part( 'checkout/multistep-form-checkout' );
    }

} else {
    ?>
    <div class="row row-cols-1 row-cols-lg-2 styler-before-checkout-form-warapper">
        <div class="col">
            <?php do_action( 'woocommerce_before_checkout_form', $checkout );?>
        </div>
    </div>

    <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( wc_get_checkout_url() ); ?>" enctype="multipart/form-data">

        <?php if ( $checkout->get_checkout_fields() ) : ?>

            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

            <div class="col2-set row row-cols-1 row-cols-lg-2" id="customer_details">

                <div class="col">
                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                </div>

                <div class="col">
                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>

                    <div class="styler-order-review">
                        <?php do_action( 'woocommerce_checkout_before_order_review_heading' ); ?>

                        <h4 class="styler-form-title" id="order_review_heading"><?php esc_html_e( 'Your order', 'styler' ); ?></h4>

                        <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>

                        <div id="order_review" class="woocommerce-checkout-review-order">
                            <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                        </div>

                        <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>

                    </div>
                </div>
            </div>

            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

        <?php endif; ?>

    </form>
    <?php
    do_action( 'woocommerce_after_checkout_form', $checkout );
}
?>
