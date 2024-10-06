<?php
/**
* Single Product Image
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/product-image.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 7.8.0
*/

defined( 'ABSPATH' ) || exit;

// Note: `wc_get_gallery_image_html` was added in WC 3.3.2 and did not exist prior. This check protects against theme overrides being used on older versions of WC.
if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
    return;
}

global $product;

$post_thumbnail_id = $product->get_image_id();
$columns = apply_filters( 'woocommerce_product_thumbnails_columns', 7 );
$video   = get_post_meta( $product->get_id(), 'styler_product_popup_video', true );
$layout  = get_post_meta( $product->get_id(), 'styler_gallery', true );
$layout  = '' != $layout ? $layout : styler_settings('product_thumbs_layout', 'default');
$owl     = '1' == styler_settings('single_gallery_owl_carousel', '0') ? 'styler-gallery-owl-enabled' : 'styler-gallery-owl-disabled';

$wrapper_classes = apply_filters(
    'woocommerce_single_product_image_gallery_classes',
    array(
        'woocommerce-product-gallery',
        'images_'.$layout,
        'woocommerce-product-gallery--' . ( $post_thumbnail_id ? 'with-images' : 'without-images' ),
        'woocommerce-product-gallery--columns-' . absint( $columns ),
        'images',
        $owl
    )
);

if ( '1' == styler_settings('single_gallery_owl_carousel', '0') ) {
    // OWL CAROUSEL
    wp_enqueue_style( 'owl-carousel');
    wp_enqueue_style( 'owl-theme-default');
    wp_enqueue_script( 'owl-carousel');
    wp_enqueue_script( 'flex-thumbs');
}

?>
<div class="<?php echo esc_attr( implode( ' ', array_map( 'sanitize_html_class', $wrapper_classes ) ) ); ?>" data-columns="<?php echo esc_attr( $columns ); ?>">
    <?php
    if ( $video ) {
        wp_enqueue_style( 'magnific' );
        wp_enqueue_script( 'magnific' );
        echo '<a class="styler-product-video-button mfp-iframe" href="'.esc_url( $video ).'" data-product_id="'.$product->get_image_id().'"><i class="fa fa-play"></i></a>';
    }
    ?>
    <div class="woocommerce-product-gallery__wrapper">
        <?php
        if ( $post_thumbnail_id ) {
            $html = wc_get_gallery_image_html( $post_thumbnail_id, true );
        } else {
            $html  = '<div class="woocommerce-product-gallery__image--placeholder">';
            $html .= sprintf( '<img src="%s" alt="%s" class="wp-post-image" />', esc_url( wc_placeholder_img_src( 'woocommerce_single' ) ), esc_html__( 'Awaiting product image', 'styler' ) );
            $html .= '</div>';
        }

        echo apply_filters( 'woocommerce_single_product_image_thumbnail_html', $html, $post_thumbnail_id );

        do_action( 'woocommerce_product_thumbnails' );
        ?>
    </div>
</div>
