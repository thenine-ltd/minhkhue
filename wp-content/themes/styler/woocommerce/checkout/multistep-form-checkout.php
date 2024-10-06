<?php
/**
* Checkout Form
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$labels = apply_filters( 'styler_checkout_multisteps_strings', array(
    'login'    => _x( 'Login', 'Checkout: user multisteps', 'styler' ),
    'billing'  => _x( 'Billing & Shipping', 'Checkout: user multisteps', 'styler' ),
    'order'    => _x( 'Order & Payment', 'Checkout: user multisteps', 'styler' ),
    'next'     => esc_html__( 'Next', 'styler' ),
    'prev'     => esc_html__( 'Previous', 'styler' ),
    'required' => esc_html__( 'This field is required', 'styler' )
));

$checkout_url = apply_filters( 'woocommerce_get_checkout_url', wc_get_checkout_url() );
$checkout = new WC_Checkout();

wp_enqueue_script('checkout-multisteps');
?>
<div class="container-sm styler-checkout-content-wrapper styler-page-multistep-checkout">
    <div class="row styler-justify-center">
        <div class="col-12 col-md-10 col-lg-8">

            <div class="styler-checkout-labels">
                <div class="styler-checkout-labels-inner">
                    <?php if ( ! is_user_logged_in() ) : ?>
                        <div class="styler-swiper-pagination" data-steps-labels='{"labels":["<?php echo esc_html( $labels['login'] ); ?>","<?php echo esc_html( $labels['billing'] ); ?>","<?php echo esc_html( $labels['order'] ); ?>"]}'></div>
                    <?php else : ?>
                        <div class="styler-swiper-pagination" data-steps-labels='{"labels":["<?php echo esc_html( $labels['billing'] ); ?>","<?php echo esc_html( $labels['order'] ); ?>"]}'></div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="checkout_coupon" class="styler-woocommerce-checkout-coupon">
                <?php woocommerce_checkout_coupon_form(); ?>
            </div>

            <form name="checkout" method="post" class="checkout woocommerce-checkout" action="<?php echo esc_url( $checkout_url ); ?>" enctype="multipart/form-data">
                <div class="swiper-container styler-checkout-content">

                    <div class="swiper-wrapper">
                        <?php if ( ! is_user_logged_in() ) : ?>
                            <div class="swiper-slide">
                                <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                                    <div class="swiper-container styler-checkout-form-login">
                                        <div class="styler-inner-steps-buttons">
                                            <span class="styler-checkout-form-button-login signin-title">
                                                <?php echo styler_svg_lists( 'arrow-right', 'styler-svg-icon' ); ?>
                                                <span><?php esc_html_e( 'Sign in', 'styler' ); ?></span>
                                            </span>
                                            <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                                                <span class="styler-checkout-form-button-register register-title">
                                                    <?php echo styler_svg_lists( 'user-2', 'styler-svg-icon' ); ?>
                                                    <span><?php esc_html_e( 'Register', 'styler' ); ?></span>
                                                </span>
                                            <?php } ?>
                                        </div>
                                        <div class="swiper-wrapper">
                                            <div class="swiper-slide">
                                                <div id="checkout_login" class="styler-woocommerce-checkout-login">
                                                    <?php woocommerce_login_form(); ?>
                                                </div>
                                            </div>
                                            <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                                                <div class="swiper-slide">
                                                    <div class="register-form-content">

                                                        <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                                                            <?php do_action( 'woocommerce_register_form_start' ); ?>

                                                            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
                                                                <p class="form-row styler-is-required">
                                                                    <label for="reg_username"><?php esc_html_e( 'Username', 'styler' ); ?>&nbsp;<span class="required">*</span></label>
                                                                    <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                                                                    <span class="styler-form-message"></span>
                                                                </p>
                                                            <?php endif; ?>

                                                            <p class="form-row styler-is-required">
                                                                <label for="reg_email"><?php esc_html_e( 'Email address', 'styler' ); ?>&nbsp;<span class="required">*</span></label>
                                                                <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                                                                <span class="styler-form-message"></span>
                                                            </p>

                                                            <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>
                                                                <p class="form-row styler-is-required">
                                                                    <label for="reg_password"><?php esc_html_e( 'Password', 'styler' ); ?>&nbsp;<span class="required">*</span></label>
                                                                    <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                                                                    <span class="styler-form-message"></span>
                                                                </p>
                                                            <?php else : ?>
                                                                <p><?php esc_html_e( 'A password will be sent to your email address.', 'styler' ); ?></p>
                                                            <?php endif; ?>

                                                            <?php do_action( 'woocommerce_register_form' ); ?>

                                                            <p class="form-row">
                                                                <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                                                                <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit" name="register" value="<?php esc_attr_e( 'Register', 'styler' ); ?>"><?php esc_html_e( 'Register', 'styler' ); ?></button>
                                                            </p>

                                                            <?php do_action( 'woocommerce_register_form_end' ); ?>

                                                        </form>
                                                    </div>
                                                </div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div id="checkout_login" class="styler-woocommerce-checkout-login">
                                        <?php woocommerce_login_form(); ?>
                                    </div>
                                <?php } ?>
                            </div>
                        <?php endif; ?>

                        <?php if ( $checkout->get_checkout_fields() ) : ?>
                            <div class="swiper-slide">

                                <?php do_action( 'woocommerce_checkout_before_customer_details' ); ?>

                                <div class="row check-form-required">
                                    <div class="col-12 col-lg-6">
                                        <div class="styler-customer-billing-details <?php echo is_user_logged_in() ? 'logged-in' : 'not-logged-in'; ?>" id="styler-customer-billing-details">
                                            <?php do_action( 'woocommerce_checkout_billing' ); ?>
                                        </div>
                                    </div>
                                    <div class="col-12 col-lg-6">
                                        <div class="styler-customer-shipping-details" id="styler-customer-shipping-details">
                                            <?php do_action( 'woocommerce_checkout_shipping' ); ?>
                                        </div>
                                    </div>
                                </div>

                                <div class="styler-form-error hidden"><?php echo esc_html( $labels['required'] ); ?></div>

                                <?php do_action( 'woocommerce_checkout_after_customer_details' ); ?>

                            </div>
                        <?php endif; ?>

                        <div class="swiper-slide">

                            <div class="styler-order-review">
                                <?php do_action( 'woocommerce_checkout_before_order_review' ); ?>
                                <div id="order_review">
                                    <h4 class="styler-form-title"><?php esc_html_e( 'Your order', 'styler' ); ?></h4>
                                    <?php echo woocommerce_order_review(); ?>
                                </div>
                                <div class="styler-order-checkout-payment mt-40">
                                    <?php echo woocommerce_checkout_payment(); ?>
                                </div>
                                <?php do_action( 'woocommerce_checkout_after_order_review' ); ?>
                            </div>
                        </div>
                    </div>

                    <div class="styler-checkout-button-wrapper">
                        <div class="styler-checkout-button-prev button"><?php echo esc_html( $labels['prev'] ) ?></div>
                        <div class="styler-checkout-button-next button <?php echo !is_user_logged_in() ? 'disabled' : 'enabled' ?>"><?php echo esc_html( $labels['next'] ) ?></div>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>

<?php do_action( 'woocommerce_after_checkout_form', $checkout );
