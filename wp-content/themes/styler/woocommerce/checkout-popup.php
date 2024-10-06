<?php
/**
* ajax quick shop handler.
*/
if ( !function_exists( 'ninetheme_ajax_checkout_popup' ) ) {

    add_action( 'wp_ajax_ninetheme_ajax_checkout_popup', 'ninetheme_ajax_checkout_popup' );
    add_action( 'wp_ajax_nopriv_ninetheme_ajax_checkout_popup', 'ninetheme_ajax_checkout_popup' );

    function ninetheme_ajax_checkout_popup()
    {
        define( 'WOOCOMMERCE_CHECKOUT', true );
        $labels = apply_filters( 'ninetheme_checkout_multisteps_strings', array(
            'billing'  => _x( 'Billing', 'Checkout: user multisteps', 'styler' ),
            'shipping' => _x( 'Shipping', 'Checkout: user multisteps', 'styler' ),
            'order'    => _x( 'Order & Payment', 'Checkout: user multisteps', 'styler' ),
            'next'     => esc_html__( 'Next', 'styler' ),
            'prev'     => esc_html__( 'Previous', 'styler' )
        ));

        $checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
        $checkout = new WC_Checkout();

        // If checkout registration is disabled and not logged in, the user cannot checkout.
        if ( ! $checkout->is_registration_enabled() && $checkout->is_registration_required() && ! is_user_logged_in() ) {
            echo esc_html( apply_filters( 'woocommerce_checkout_must_be_logged_in_message', __( 'You must be logged in to checkout.', 'styler' ) ) );
            return;
        }
        ?>
        <div class="ninetheme-ajax-checkout-popup-inner">
            <div class="ninetheme-panel-checkout-labels swiper-container ninetheme-checkout-popup-steps">
                <div class="swiper-wrapper">
                    <div class="ninetheme-step-item ninetheme-step-item-1 swiper-slide step-billing" data-id="ninetheme-customer-billing-details">
                        <span class="ninetheme-step">
                            <span class="ninetheme-step-number">1</span>
                            <span class="ninetheme-step-label"><?php echo esc_html( $labels['billing'] ); ?></span>
                            <span class="ninetheme-step-triangle"></span>
                        </span>
                    </div>
                    <div class="ninetheme-step-item ninetheme-step-item-2 swiper-slide step-shipping" data-id="ninetheme-customer-shipping-details">
                        <span class="ninetheme-step">
                            <span class="ninetheme-step-number">2</span>
                            <span class="ninetheme-step-label"><?php echo esc_html( $labels['shipping'] ); ?></span>
                            <span class="ninetheme-step-triangle"></span>
                        </span>
                    </div>
                    <div class="ninetheme-step-item ninetheme-step-item-3 swiper-slide step-order" data-id="order_review">
                        <span class="ninetheme-step">
                            <span class="ninetheme-step-number">3</span>
                            <span class="ninetheme-step-label"><?php echo esc_html( $labels['order'] ); ?></span>
                            <span class="ninetheme-step-triangle"></span>
                        </span>
                    </div>
                </div>
            </div>

            <div class="ninetheme-panel-checkout-form-wrapper ninetheme-scrollbar">
                <div id="checkout_coupon" class="ninetheme-woocommerce-checkout-coupon">
                    <?php woocommerce_checkout_coupon_form(); ?>
                </div>

                <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">
                    <div class="swiper-container ninetheme-checkout-popup-main">
                        <div class="swiper-wrapper">
                            <?php if ( $checkout->get_checkout_fields() ) : ?>
                                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                                <div class="ninetheme-customer-billing-details swiper-slide <?php echo is_user_logged_in() ? 'logged-in' : 'not-logged-in'; ?>" id="ninetheme-customer-billing-details">
                                    <?php do_action( 'woocommerce_checkout_billing' ); ?>
                                </div>

                                <div class="ninetheme-customer-shipping-details swiper-slide" id="ninetheme-customer-shipping-details">
                                    <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                                </div>

                                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>
                            <?php endif; ?>

                            <div class="ninetheme-order-review swiper-slide" id="order_review">
                                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                                <h4 class="ninetheme-form-title"><?php esc_html_e( 'Your order', 'styler' ); ?></h4>
                                <?php do_action( 'woocommerce_checkout_order_review' ); ?>
                                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                            </div>

                        </div>
                    </div>
                </form>
            </div>

        </div>
        <?php
        die();
    }
}
