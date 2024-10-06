<?php
/**
* Related Products
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/related.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see         https://docs.woocommerce.com/document/template-structure/
* @package     WooCommerce\Templates
* @version     3.9.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

if ( '1' != styler_settings('single_shop_ralated_visibility', '1') ) {
    return;
}
if ( '1' == styler_settings('product_ralated_same_category', '0') ) {

    wc_get_template_part( 'single-product/related-category' );

} else {

    $heading = styler_settings('single_shop_related_title', '');
    $heading = $heading ? esc_html( $heading ) : apply_filters( 'woocommerce_product_related_products_heading', esc_html__( 'Viewers Also Liked', 'styler' ) );

    $perview   = styler_settings( 'shop_related_perview', 4 );
    $mdperview = styler_settings( 'shop_related_mdperview', 3 );
    $smperview = styler_settings( 'shop_related_smperview', 2 );
    $sattr    = array();
    $sattr[] .= '"speed":'.styler_settings( 'shop_related_speed', 1000 );
    $sattr[] .= '"slidesPerView":1,"slidesPerGroup":1';
    $sattr[] .= '"spaceBetween":'.styler_settings( 'shop_related_gap', 30 );
    $sattr[] .= '1' == styler_settings( 'shop_related_loop', 0 ) ? '"loop":true' : '"loop":false';
    $sattr[] .= '1' == styler_settings( 'shop_related_autoplay', 1 ) ? '"autoplay":true' : '"autoplay":false';
    $sattr[] .= '1' == styler_settings( 'shop_related_mousewheel', 0 ) ? '"mousewheel":true' : '"mousewheel":false';
    $sattr[] .= '1' == styler_settings( 'shop_related_freemode', 1 ) ? '  "freeMode":true' : '"freeMode":false';
    $sattr[] .= '"navigation": {"nextEl": ".related-slider-nav .styler-slide-next","prevEl": ".related-slider-nav .styler-slide-prev"}';
    $sattr[] .= '"breakpoints": {"0": {"slidesPerView": '.$smperview.',"slidesPerGroup":'.$smperview.'},"768": {"slidesPerView": '.$mdperview.',"slidesPerGroup":'.$mdperview.'},"1024": {"slidesPerView": '.$perview.',"slidesPerGroup":'.$perview.'}}';
    $rtl = is_rtl() ? '-rtl' : '';

    if ( $related_products ) {
        wp_enqueue_script( 'swiper' );
        ?>
        <div class="styler-product-related styler-related-product-wrapper styler-section">
            <div class="section-title-wrapper">
                <?php if ( $heading ) : ?>
                    <h4 class="section-title"><?php echo esc_html( $heading ); ?></h4>
                <?php endif; ?>
                <div class="related-slider-nav">
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

                        <?php foreach ( $related_products as $related_product ) : ?>
                            <div class="swiper-slide">
                                <?php
                                $post_object = get_post( $related_product->get_id() );

                                setup_postdata( $GLOBALS['post'] =& $post_object );

                                wc_get_template_part( 'content', 'product' );
                                ?>
                            </div>

                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    wp_reset_postdata();
}
