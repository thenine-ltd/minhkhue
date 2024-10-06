<?php
/**
 * Variable product add to cart
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/add-to-cart/variable.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 6.1.0
 */

defined( 'ABSPATH' ) || exit;
if ( '1' == styler_settings( 'woo_catalog_mode', '0' ) && '1' == styler_settings( 'woo_disable_product_addtocart', '0' ) ) {
    return;
}
global $product;

$attribute_keys  = array_keys( $attributes );
$variations_json = wp_json_encode( $available_variations );
$variations_attr = function_exists( 'wc_esc_json' ) ? wc_esc_json( $variations_json ) : _wp_specialchars( $variations_json, ENT_QUOTES, 'UTF-8', true );
$has_buynow      = '1' == styler_settings( 'buy_now_visibility', '0' ) ? ' has-buynow' : '';

do_action( 'woocommerce_before_add_to_cart_form' ); ?>

<form class="styler-summary-item styler-flex variations_form cart<?php echo esc_attr( $has_buynow ); ?>" action="<?php echo esc_url( apply_filters( 'woocommerce_add_to_cart_form_action', $product->get_permalink() ) ); ?>" method="post" enctype='multipart/form-data' data-product_id="<?php echo absint( $product->get_id() ); ?>" data-product_variations="<?php echo esc_attr( $variations_attr ); // WPCS: XSS ok. ?>">
    <?php do_action( 'woocommerce_before_variations_form' ); ?>

    <?php if ( empty( $available_variations ) && false !== $available_variations ) : ?>
        <p class="stock out-of-stock"><?php echo esc_html( apply_filters( 'woocommerce_out_of_stock_message', __( 'This product is currently out of stock and unavailable.', 'styler' ) ) ); ?></p>
    <?php else : ?>
        <div class="styler-variations variations<?php echo '1' == styler_settings('variations_terms_hints_visibility', '0') ? ' show-hints' : ''; ?>">
            <?php if ( '1' == styler_settings('selected_variations_terms_visibility', '1' ) ) : ?>
                <?php if ( '' != styler_settings('select_variations_terms_title', '' ) ) : ?>
                    <span class="styler-select-variations-terms-title"><?php echo styler_settings('select_variations_terms_title'); ?></span>
                <?php else : ?>
                    <span class="styler-select-variations-terms-title"><?php esc_html_e( 'Select Features', 'styler' ); ?></span>
                <?php endif; ?>
                <div class="styler-selected-variations-terms-wrapper">
                    <?php if ( '' != styler_settings('selected_variations_terms_title', '' ) ) : ?>
                        <span class="styler-selected-variations-terms-title"><?php echo styler_settings('selected_variations_terms_title'); ?></span>
                    <?php else : ?>
                        <span class="styler-selected-variations-terms-title"><?php esc_html_e( 'Selected Features', 'styler' ); ?></span>
                    <?php endif; ?>
                    <div class="styler-selected-variations-terms"></div>
                </div>
            <?php endif; ?>
            <?php foreach ( $attributes as $attribute_name => $options ) : ?>
                <div class="styler-variations-items variations-items">
                    <div class="styler-flex styler-align-center">
                        <span class="styler-small-title"><?php echo wc_attribute_label( $attribute_name ); // WPCS: XSS ok. ?></span>
                        <div class="styler-flex value">
                            <?php
                            wc_dropdown_variation_attribute_options(
                                array(
                                    'options'   => $options,
                                    'attribute' => $attribute_name,
                                    'product'   => $product,
                                )
                            );
                            echo end( $attribute_keys ) === $attribute_name ? wp_kses_post( apply_filters( 'woocommerce_reset_variations_link', '<a class="styler-btn-reset reset_variations" href="#">' . esc_html__( 'Clear', 'styler' ) . '</a>' ) ) : '';
                            ?>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <?php do_action( 'woocommerce_after_variations_table' ); ?>

        <div class="single_variation_wrap single-product-add-to-cart-type-gray">
            <?php
            /**
            * Hook: woocommerce_before_single_variation.
            */
            do_action( 'woocommerce_before_single_variation' );

            /**
            * Hook: woocommerce_single_variation. Used to output the cart button and placeholder for variation data.
            *
            * @since 2.4.0
            * @hooked woocommerce_single_variation - 10 Empty div for variation data.
            * @hooked woocommerce_single_variation_add_to_cart_button - 20 Qty and cart button.
            */
            do_action( 'woocommerce_single_variation' );

            /**
            * Hook: woocommerce_after_single_variation.
            */
            do_action( 'woocommerce_after_single_variation' );
            ?>
        </div>
    <?php endif; ?>

    <?php do_action( 'woocommerce_after_variations_form' ); ?>
</form>

<?php
do_action( 'woocommerce_after_add_to_cart_form' );
