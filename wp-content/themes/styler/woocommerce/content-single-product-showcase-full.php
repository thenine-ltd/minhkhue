<?php


global $product;

$layout           = apply_filters('styler_single_shop_layout', styler_settings( 'single_shop_layout', 'showcase' ) );

$showcase_type_ot = styler_settings( 'single_shop_showcase_type', 'carousel' );
$showcase_type_mb = get_post_meta( get_the_ID(), 'styler_showcase_type', true );
$showcase_type    = $showcase_type_mb ? $showcase_type_mb : $showcase_type_ot;
$showcase_type    = apply_filters('styler_showcase_type', $showcase_type );

$popup_video      = get_post_meta( get_the_ID(), 'styler_product_popup_video', true );
$video_type       = apply_filters( 'styler_product_video_type', get_post_meta( get_the_ID(), 'styler_product_video_type', true ) );

$bg_type_ot = styler_settings( 'single_shop_showcase_bg_type', 'dark' );
$bg_type_mb = get_post_meta( get_the_ID(), 'styler_showcase_bg_type', true );
$bg_type    = $bg_type_mb ? $bg_type_mb : $bg_type_ot;
$bg_type    = apply_filters('styler_showcase_bg_type', $bg_type );
$bg_type   .= '1' == styler_settings( 'single_shop_hero_visibility', '1' ) ? ' styler-has-breadcrumb' : '';

$order_column   = 'right-sidebar' == $layout && is_active_sidebar( 'shop-single-sidebar' ) ? ' order-1' : ' order-1 order-lg-2';
$order_sidebar  = 'right-sidebar' == $layout && is_active_sidebar( 'shop-single-sidebar' ) ? ' order-2' : ' order-2 order-lg-1';
$sticky_sidebar = is_active_sidebar( 'shop-single-sidebar' ) && '1' == styler_settings( 'single_shop_sticky_sidebar', '0' ) ? ' styler-is-sticky' : '';
$column         = ( 'left-sidebar' == $layout || 'right-sidebar' == $layout ) && is_active_sidebar( 'shop-single-sidebar' ) ? 'col-lg-9 shop-has-sidebar styler-sidebar-'.$layout.$order_column : 'col-lg-12';

$thumbs_layout  = apply_filters( 'styler_product_thumbs_layout', styler_settings( 'product_thumbs_layout', 'slider' ) );
$sticky_summary = '1' == styler_settings( 'single_shop_sticky_summary', '0' ) ? ' styler-is-sticky' : '';


$tabs_type  = apply_filters( 'styler_product_tabs_type', styler_settings( 'product_tabs_type', 'tabs' ) );

remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_title', 5 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_rating', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_price', 10 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_excerpt', 20 );
remove_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );

if ( 'accordion' == $tabs_type ) {
    remove_action( 'woocommerce_after_single_product_summary', 'woocommerce_output_product_data_tabs', 10 );
    add_action( 'woocommerce_single_product_summary', 'woocommerce_output_product_data_tabs', 31 );
    if ( '0' != styler_settings('single_shop_review_visibility', '1' ) ) {
        add_action( 'woocommerce_after_single_product_summary', 'styler_wc_move_product_reviews', 21 );
    }
}

?>
<div id="nt-woo-single" class="nt-woo-single styler-product-showcase-style">
    <div id="product-<?php the_ID(); ?>" <?php wc_product_class( 'styler-single-product-type-3', $product ); ?>>
        <div class="styler-product-showcase styler-bg-<?php echo esc_attr( $bg_type ); ?>">

            <?php if ( '0' != styler_settings( 'single_shop_hero_visibility', '1' ) ) { ?>
                <div class="styler-product-breadcrumb-nav">
                    <div class="container-xl styler-container-xl">
                        <div class="row">
                            <div class="col-12">
                                <div class="styler-flex styler-align-center">
                                    <?php
                                    if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                        echo woocommerce_breadcrumb();
                                    }
                                    ?>
                                    <?php styler_single_product_nav_two(); ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            <?php } ?>

            <?php
            if ( 'full' == $showcase_type ) {
                styler_product_gallery_slider_full();
            } else {
                styler_product_gallery_slider_carousel();
                wp_enqueue_script('product-gallery-carousel');
            }
            ?>

            <div class="styler-product-summary">
                <div class="container-xl styler-container-xl">
                    <div class="row">
                        <div class="col-12 col-lg-6">
                            <?php
                            if ( 'carousel' == $showcase_type && $popup_video && 'popup' == $video_type ) {
                                echo '<a href="'.$popup_video.'" class="styler-product-video-button styler-in-content mfp-iframe"><i class="fa fa-play"></i></a>';
                            }
                            woocommerce_template_single_title();
                            woocommerce_template_single_rating();
                            woocommerce_template_single_price();
                            woocommerce_template_single_add_to_cart();
                            woocommerce_template_single_excerpt();
                            ?>
                        </div>
                        <div class="col-12 col-lg-1"></div>
                        <div class="col-12 col-lg-5 mt-4 mt-lg-0">
                            <?php
                            /**
                            * Hook: woocommerce_single_product_summary.
                            *
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
    </div>

    <div class="nt-styler-inner-container pb-100">
        <div class="container-xl styler-container-xl">
            <div class="row styler-shop-single-row shop-layout-<?php echo esc_attr( $layout ); ?>">
                <div class="<?php echo esc_attr( $column ); ?>">
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
    </div>
</div>
