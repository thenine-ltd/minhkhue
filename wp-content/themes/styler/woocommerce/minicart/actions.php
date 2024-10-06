<?php
if ( ! function_exists( 'styler_side_panel_cart_content' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_side_panel_cart_content' );
    function styler_side_panel_cart_content()
    {
        if ( '1' == styler_settings( 'woo_catalog_mode', '0' ) ) {
            return;
        }
        $cart_count = WC()->cart->get_cart_contents_count();
        ?>
        <div class="styler-side-panel" data-cart-count="<?php echo esc_attr( $cart_count ); ?>">
            <div class="panel-header-wrapper">
                <div class="panel-header">
                    <div class="styler-panel-close styler-panel-close-button"></div>
                    <div class="panel-header-actions">
                        <div class="panel-header-cart panel-header-btn" data-name="cart">
                            <span class="styler-cart-count styler-wc-count"><?php echo esc_html( $cart_count ); ?></span>
                            <?php echo styler_svg_lists( 'bag', 'styler-svg-icon' ); ?>
                            <span class="styler-cart-total"><?php echo WC()->cart->get_cart_subtotal(); ?></span>
                        </div>
                        <?php do_action( 'styler_side_panel_header_after_cart' ); ?>
                    </div>
                </div>
                <?php
                if ( '1' == styler_settings( 'free_shipping_progressbar_minicart_visibility', '1' ) ) {
                    do_action( 'styler_side_panel_after_header' );
                }
                ?>
            </div>
            <div class="panel-content">
                <div class="cart-area panel-content-item active" data-name="cart">
                    <div class="cart-content">
                        <?php get_template_part('woocommerce/minicart/minicart'); ?>
                    </div>
                </div>
                <?php do_action( 'styler_side_panel_content_after_cart' ); ?>
                <?php if ( ! is_cart() && ! is_checkout()  ) {
                     if ( '1' == styler_settings('panel_checkout_visibility', '1' ) ) { ?>
                    <div class="checkout-area panel-content-item" data-name="checkout">
                        <div class="checkout-content">
                            <?php wc_get_template_part( 'checkout/multistep-form-checkout-sidepanel' ); ?>
                        </div>
                    </div>
                <?php } } ?>
            </div>
            <div class="panel-footer"></div>
        </div>
        <?php
    }
}
