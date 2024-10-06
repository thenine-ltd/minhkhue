<?php
/**
 * Single Product Price
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product/price.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.0.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

global $product;
$price_class = apply_filters( 'woocommerce_product_price_class', 'styler-summary-item styler-price price' );
$stock_show = styler_settings('single_shop_top_labels_visibility', '1' );
?>
<div class="<?php echo esc_attr( $price_class ); ?>"><?php printf('%s', $product->get_price_html() ); ?><?php if ( '0' != $stock_show ) { echo wc_get_stock_html($product); } ?></div>
