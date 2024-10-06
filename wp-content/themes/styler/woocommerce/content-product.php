<?php
/**
 * The template for displaying product content within loops
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/content-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.6.0
 */

defined( 'ABSPATH' ) || exit;

global $product;

// Ensure visibility.
if ( empty( $product ) || ! $product->is_visible() ) {
    return;
}

$css_class  = 'styler-loop-product styler-product-type-'.apply_filters( 'styler_loop_product_type', styler_settings( 'shop_product_type', '1' ) );
$css_class .= wp_doing_ajax() ? ' animated '.apply_filters( 'styler_loop_product_animation', styler_settings( 'shop_product_animation_type', 'fadeInUp' ) ) : '';
$animation  = apply_filters( 'styler_loop_product_animation', styler_settings( 'shop_product_animation_type', 'fadeInUp' ) );

?>
<div <?php wc_product_class( $css_class, $product ); ?> data-product-animation="<?php echo esc_attr( $animation ); ?>">

    <?php do_action( 'woocommerce_before_shop_loop_item_title'); ?>

    <?php styler_loop_product_layout_manager(); ?>

    <?php do_action( 'styler_after_shop_loop_item'); ?>

</div>
<?php
