<?php
/**
* The template for displaying product content in the single-product.php template
*
* This template can be overridden by copying it to yourtheme/woocommerce/content-single-product.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 3.6.0
*/

defined( 'ABSPATH' ) || exit;

global $product;

remove_action( 'woocommerce_before_single_product', 'woocommerce_output_all_notices', 10 );
/**
* Hook: woocommerce_before_single_product.
*
* @hooked woocommerce_output_all_notices - 10
*/
do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

$thumbs_layout  = apply_filters( 'styler_product_thumbs_layout', styler_settings( 'product_thumbs_layout', 'slider' ) );
$sticky_summary = '1' == styler_settings( 'single_shop_sticky_summary', '0' ) && 'grid' == $thumbs_layout ? ' styler-is-sticky' : '';

$tabs_type   = apply_filters( 'styler_product_tabs_type', styler_settings( 'product_tabs_type', 'tabs' ) );
$gallery_col = styler_settings( 'product_thumbs_column_width', 7 );
$summary_col = $gallery_col >= '9'  ? 12 : 12 - $gallery_col;

if ( 'accordion' == $tabs_type ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 31 );
    add_action( 'woocommerce_after_single_product_summary', 'styler_wc_move_product_reviews', 21 );
}

?>
<div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'styler-single-product-type-1', $product ); ?>>

    <div class="row styler-row-summary">
        <div class="col-12 col-lg-<?php echo esc_attr( $gallery_col ); ?> gallery-col">
            <?php
            if ( 'wc' == $thumbs_layout ) {
                do_action( 'woocommerce_before_single_product_summary' );
            } elseif ( 'grid' == $thumbs_layout ) {
                wp_enqueue_script('product-gallery-grid');
                styler_product_gallery_grid();
            } else {
                wp_enqueue_script('product-gallery-main');
                styler_product_gallery_slider();
            }
            ?>
        </div>

        <div class="col-12 col-lg-<?php echo esc_attr( $summary_col ); ?> summary-col px-lg-4 mt-4 mt-lg-0">
            <div class="styler-product-summary<?php echo esc_attr( $sticky_summary ); ?>">
                <div class="styler-product-summary-inner">
                    <?php
                    if ( 'custom' == styler_settings( 'single_shop_summary_layout_type', 'default' ) ) {

                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
                        remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_meta', 40 );

                        shop_product_summary_layouts_manager();

                        do_action( 'woocommerce_single_product_summary' );

                    } else {

                        /**
                        * Hook: woocommerce_single_product_summary.
                        *
                        * @hooked woocommerce_template_single_title - 5
                        * @hooked woocommerce_template_single_rating - 10
                        * @hooked woocommerce_template_single_price - 10
                        * @hooked woocommerce_template_single_excerpt - 20
                        * @hooked woocommerce_template_single_add_to_cart - 30
                        * @hooked woocommerce_template_single_meta - 40
                        * @hooked woocommerce_template_single_sharing - 50
                        * @hooked WC_Structured_Data::generate_product_data() - 60
                        */
                        do_action( 'woocommerce_single_product_summary' );
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>

    <div class="row styler-row-after-summary">
        <div class="col-12">
            <?php
            /**
            * Hook: woocommerce_after_single_product_summary.
            *
            * @hooked woocommerce_output_product_data_tabs - 10
            * @hooked woocommerce_upsell_display - 15
            * @hooked woocommerce_output_related_products - 20
            */
            do_action( 'woocommerce_after_single_product_summary' );
            ?>
        </div>
    </div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
