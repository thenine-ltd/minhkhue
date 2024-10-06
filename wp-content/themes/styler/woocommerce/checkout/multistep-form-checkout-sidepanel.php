<?php
/**
* Checkout Form
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$labels = apply_filters( 'styler_checkout_multisteps_strings', array(
    'billing'  => _x( 'Billing & Shipping', 'Checkout: user multisteps', 'styler' ),
    'order'    => _x( 'Order & Payment', 'Checkout: user multisteps', 'styler' ),
    'next'     => esc_html__( 'Next', 'styler' ),
    'prev'     => esc_html__( 'Previous', 'styler' )
));
$checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
$checkout = new WC_Checkout();

// If checkout registration is disabled and not logged in, the user cannot checkout.
if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
    echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', esc_html__( 'You must be logged in to checkout.', 'styler' ) ) );
    return;
}

if ( ! is_cart() && ! is_checkout() && 'multisteps' == styler_settings( 'panels_checkout_button_status', 'multisteps' ) ) {
    ?>
    <div class="styler-panel-checkout-content-wrapper">
        <div class="styler-panel-checkout-labels">
            <div class="styler-panel-checkout-labels-inner">
                <div class="styler-step-item styler-step-item-1 active-step">
                    <span class="styler-step">1</span>
                    <span class="styler-step-label"><?php echo esc_html( $labels['billing'] ); ?></span>
                </div>
                <div class="styler-step-item styler-step-item-2">
                    <span class="styler-step">2</span>
                    <span class="styler-step-label"><?php echo esc_html( $labels['order'] ); ?></span>
                </div>
            </div>
        </div>

        <div class="styler-panel-checkout-form-wrapper styler-perfect-scrollbar">
            <div id="checkout_coupon" class="styler-woocommerce-checkout-coupon">
                <?php woocommerce_checkout_coupon_form(); ?>
            </div>

            <form name="checkout" method="post" class="checkout woocommerce-checkout styler-checkout-scrollbar" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">
                <div class="styler-panel-checkout-content">
                    <?php if ( $checkout->get_checkout_fields() ) : ?>
                        <div class="styler-panel-checkout-content-inner active-step" data-target-name="billing">

                            <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                            <div class="check-form-required">
                                <div class="styler-customer-billing-details <?php echo is_user_logged_in() ? 'logged-in' : 'not-logged-in'; ?>" id="styler-customer-billing-details">
                                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                                </div>
                                <div class="styler-customer-shipping-details" id="styler-customer-shipping-details">
                                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                                </div>
                            </div>

                            <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                        </div>
                    <?php endif; ?>

                    <div class="styler-panel-checkout-content-inner" data-target-name="order">
                        <div class="styler-order-review">
                            <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                            <div id="order_review">
                                <h4 class="styler-form-title"><?php esc_html_e( 'Your order', 'styler' ); ?></h4>
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                            </div>
                            <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                        </div>
                    </div>

                </div>
            </form>
        </div>
        <div class="styler-panel-checkout-button-wrapper">
            <div class="styler-panel-checkout-button-prev styler-btn-medium styler-btn styler-bg-black"><?php echo esc_html( $labels['prev'] ) ?></div>
            <div class="styler-panel-checkout-button-next styler-btn-medium styler-btn styler-bg-black"><?php echo esc_html( $labels['next'] ) ?></div>
        </div>

    </div>
    <?php
    wp_enqueue_script( 'wc-checkout' );
    wp_enqueue_script('styler-panel-checkout');
    do_action( 'woocommerce_after_checkout_form', $checkout );
}
?>
