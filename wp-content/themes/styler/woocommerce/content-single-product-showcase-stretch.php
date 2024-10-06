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

/**
* Hook: woocommerce_before_single_product.
*
* @hooked woocommerce_output_all_notices - 10
*/
//do_action( 'woocommerce_before_single_product' );

if ( post_password_required() ) {
    echo get_the_password_form(); // WPCS: XSS ok.
    return;
}

$thumbs_layout  = apply_filters( 'styler_product_thumbs_layout', styler_settings( 'product_thumbs_layout', 'slider' ) );
$sticky_summary = '1' == styler_settings( 'single_shop_sticky_summary', '0' ) && 'grid' == $thumbs_layout ? ' styler-is-sticky' : '';
$tabs_type      = apply_filters( 'styler_product_tabs_type', styler_settings( 'product_tabs_type', 'tabs' ) );
$gallery_col    = styler_settings( 'product_thumbs_column_width', 8 );
$summary_col    = $gallery_col >= '9'  ? 12 : 12 - ( $gallery_col + 2 );

?>
<div id="nt-woo-single" class="nt-woo-single styler-single-product-type-stretch">

    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'styler-section first-section', $product ); ?>>
        <div class="row styler-row-summary">
            <div class="col-12 col-lg-7 gallery-col">
                <?php styler_product_gallery_slider_stretch(); ?>
            </div>

            <div class="col-12 col-lg-4 summary-col px-lg-4 mt-4 mt-lg-0">
                <div class="styler-product-summary<?php echo esc_attr( $sticky_summary ); ?>">
                    <div class="styler-product-summary-inner">
                        <?php if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) { ?>
                            <div class="styler-product-top-nav styler-flex styler-align-center">
                                <?php echo woocommerce_breadcrumb(); ?>
                            </div>
                        <?php } ?>
                        <?php
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
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-container">
        <div class="container-xl styler-container-xl">
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
    </div>

</div>

<?php do_action( 'woocommerce_after_single_product' ); ?>
