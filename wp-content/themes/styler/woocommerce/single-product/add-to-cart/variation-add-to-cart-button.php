<?php
/**
* Single variation cart button
*
* @see https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 7.0.1
*/

defined( 'ABSPATH' ) || exit;
if ( '1' == styler_settings( 'woo_catalog_mode', '0' ) && '1' == styler_settings( 'woo_disable_product_addtocart', '0' ) ) {
    return;
}
global $product;
?>
<div class="woocommerce-variation-add-to-cart variations_button">

    <?php
    do_action( 'woocommerce_before_add_to_cart_button' );

    do_action( 'woocommerce_before_add_to_cart_quantity' );

    woocommerce_quantity_input(
        array(
            'min_value' => apply_filters( 'woocommerce_quantity_input_min', $product->get_min_purchase_quantity(), $product ),
            'max_value' => apply_filters( 'woocommerce_quantity_input_max', $product->get_max_purchase_quantity(), $product ),
            'input_value' => isset( $_POST['quantity'] ) ? wc_stock_amount( wp_unslash( $_POST['quantity'] ) ) : $product->get_min_purchase_quantity(), // WPCS: CSRF ok, input var ok.
        )
    );

    do_action( 'woocommerce_after_add_to_cart_quantity' );
    ?>

    <button type="submit" class="single_add_to_cart_button styler-btn styler-btn-medium styler-bg-black" data-added-title="<?php esc_attr_e( 'Added to Cart','styler' ); ?>"><span class="button-title"><?php echo esc_html( $product->single_add_to_cart_text() ); ?></span><div class="loading-wrapper"><span class="ajax-loading"></span></div></button>

    <?php do_action( 'woocommerce_after_add_to_cart_button' ); ?>

    <input type="hidden" name="add-to-cart" value="<?php echo absint( $product->get_id() ); ?>" />
    <input type="hidden" name="product_id" value="<?php echo absint( $product->get_id() ); ?>" />
    <input type="hidden" name="variation_id" class="variation_id" value="0" />
</div>
