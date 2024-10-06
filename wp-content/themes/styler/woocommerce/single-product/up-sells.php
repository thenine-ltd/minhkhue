<?php
/**
* Single Product Up-Sells
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/up-sells.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see         https://docs.woocommerce.com/document/template-structure/
* @package     WooCommerce\Templates
* @version     3.0.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

$heading = styler_settings( 'shop_upsells_title', '' );
$heading = $heading ? esc_html( $heading ) : apply_filters( 'woocommerce_product_upsells_products_heading', esc_html__( 'You may also like', 'styler' ) );
$type    = apply_filters( 'styler_wc_product_upsells_type', styler_settings( 'shop_upsells_type', 'slider' ) );

$perview   = styler_settings( 'shop_upsells_perview', 4 );
$mdperview = styler_settings( 'shop_upsells_mdperview', 3 );
$smperview = styler_settings( 'shop_upsells_smperview', 2 );
$sattr    = array();
$sattr[] .= '"speed":'.styler_settings( 'shop_upsells_speed', 1000 );
$sattr[] .= '"slidesPerView":1,"slidesPerGroup":1';
$sattr[] .= '"spaceBetween":'.styler_settings( 'shop_upsells_gap', 30 );
$sattr[] .= '1' == styler_settings( 'shop_upsells_loop', 0 ) ? '"loop":true' : '"loop":false';
$sattr[] .= '1' == styler_settings( 'shop_upsells_autoplay', 1 ) ? '"autoplay":true' : '"autoplay":false';
$sattr[] .= '1' == styler_settings( 'shop_upsells_mousewheel', 0 ) ? '"mousewheel":true' : '"mousewheel":false';
$sattr[] .= '1' == styler_settings( 'shop_upsells_freemode', 1 ) ? '  "freeMode":true' : '"freeMode":false';
$sattr[] .= '"navigation": {"nextEl": ".upsells-slider-nav .styler-slide-next","prevEl": ".upsells-slider-nav .styler-slide-prev"}';
$sattr[] .= '"breakpoints": {"0": {"slidesPerView": '.$smperview.',"slidesPerGroup":'.$smperview.'},"768": {"slidesPerView": '.$mdperview.',"slidesPerGroup":'.$mdperview.'},"1024": {"slidesPerView": '.$perview.',"slidesPerGroup":'.$perview.'}}';

if ( $upsells ) {
    //add_filter( 'styler_product_thumb_size', 'shop_related_thumb_size' );
    if ( 'slider' == $type ) {
        wp_enqueue_script( 'swiper' );
        ?>
        <div class="up-sells upsells styler-section">
            <div class="section-title-wrapper">
                <?php if ( $heading ) : ?>
                    <h4 class="section-title"><?php echo esc_html( $heading ); ?></h4>
                <?php endif; ?>
                <div class="upsells-slider-nav">
                    <?php if ( is_rtl() ) { ?>
                        <div class="styler-slide-next swiper-button-next"></div>
                        <div class="styler-slide-prev swiper-button-prev"></div>
                    <?php } else { ?>
                        <div class="styler-slide-prev swiper-button-prev"></div>
                        <div class="styler-slide-next swiper-button-next"></div>
                    <?php } ?>
                </div>
            </div>
            <div class="styler-wc-swipper-wrapper woocommerce">
                <div class="styler-swiper-slider styler-swiper-slider2 swiper-container" data-swiper-options='{<?php echo implode( ',',$sattr ); ?>}'>
                    <div class="swiper-wrapper">

                        <?php foreach ( $upsells as $upsell ) : ?>
                            <div class="swiper-slide">
                                <?php
                                    $post_object = get_post( $upsell->get_id() );

                                    setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

                                    wc_get_template_part( 'content', 'product' );
                                ?>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
        wp_reset_postdata();

    } else {

    ?>
    <div class="up-sells upsells styler-section">
        <?php
        if ( $heading ) {
            ?>
            <div class="section-title-wrapper">
                <h4 class="section-title"><?php echo esc_html( $heading ); ?></h4>
            </div>
            <?php
        }
        woocommerce_product_loop_start();

        foreach ( $upsells as $upsell ) {

            $post_object = get_post( $upsell->get_id() );

            setup_postdata( $GLOBALS['post'] =& $post_object ); // phpcs:ignore WordPress.WP.GlobalVariablesOverride.Prohibited, Squiz.PHP.DisallowMultipleAssignments.Found

            wc_get_template_part( 'content', 'product' );

        }

        woocommerce_product_loop_end();
        ?>
    </div>
    <?php
    }
}
wp_reset_postdata();
