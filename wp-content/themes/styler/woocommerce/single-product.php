<?php
/**
 * The Template for displaying all single products
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/single-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see         https://docs.woocommerce.com/document/template-structure/
 * @package     WooCommerce\Templates
 * @version     1.6.4
 */

get_header();

do_action( "styler_before_wc_single" );

$layout         = apply_filters('styler_single_shop_layout', styler_settings( 'single_shop_layout', 'full-width' ) );
$order_column   = 'right-sidebar' == $layout && is_active_sidebar( 'shop-single-sidebar' ) ? ' order-1' : ' order-1 order-lg-2';
$order_sidebar  = 'right-sidebar' == $layout && is_active_sidebar( 'shop-single-sidebar' ) ? ' order-2' : ' order-2 order-lg-1';
$sticky_sidebar = is_active_sidebar( 'shop-single-sidebar' ) && '1' == styler_settings( 'single_shop_sticky_sidebar', '0' ) ? ' styler-is-sticky' : '';
$bread_off      = '0' == styler_settings( 'breadcrumbs_visibility', '1' ) ? ' styler-bread-off' : '';
$column         = ( 'left-sidebar' == $layout || 'right-sidebar' == $layout ) && is_active_sidebar( 'shop-single-sidebar' ) ? 'col-lg-9 shop-has-sidebar styler-sidebar-'.$layout.$order_column : 'col-lg-12';


// Elementor `single` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'single' ) ) {

    if ( styler_check_is_elementor() ) {
        wp_enqueue_script('product-gallery-main');
        while ( have_posts() ) {

            the_post();
            $product = wc_get_product();
            ?>
            <div id="product-<?php the_ID(); ?>" <?php wc_product_class( '', $product ); ?>>
                <?php the_content(); ?>
            </div>
            <?php
        }

    } else {

        if ( 'stretch' == $layout ) {
            wp_enqueue_script('product-gallery-main');
            while ( have_posts() ) {
                the_post();
                wc_get_template_part( 'content', 'single-product-showcase-stretch' );
            }

        } elseif ( 'showcase' == $layout ) {
            wp_enqueue_script('product-gallery-main');
            while ( have_posts() ) {
                the_post();
                wc_get_template_part( 'content', 'single-product-showcase-full' );
            }

        } else {

            ?>
            <!-- WooCommerce product page container -->
            <div id="nt-woo-single" class="nt-woo-single">

                <div class="nt-styler-inner-container pt-30 pb-100">
                    <div class="container-xl styler-container-xl">
                        <div class="row">
                            <div class="col-12">
                                <div class="styler-product-top-nav styler-flex styler-align-center<?php echo esc_attr( $bread_off ); ?>">
                                    <?php
                                    if ( '0' != styler_settings( 'breadcrumbs_visibility', '1' ) ) {
                                        echo woocommerce_breadcrumb();
                                    }
                                    ?>
                                    <?php styler_single_product_nav_two(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="row styler-shop-single-row shop-layout-<?php echo esc_attr( $layout ); ?>">

                            <!-- Left sidebar -->
                            <?php
                            if ( ( 'right-sidebar' == $layout || 'left-sidebar' == $layout ) && is_active_sidebar( 'shop-single-sidebar' ) ) {
                                echo '<div id="nt-sidebar" class="col-lg-3'.$order_sidebar.'">';
                                    echo '<div class="shop-sidebar nt-sidebar-inner'.$sticky_sidebar.'">';
                                        dynamic_sidebar( 'shop-single-sidebar' );
                                    echo '</div>';
                                echo '</div>';
                            }
                            ?>

                            <div class="<?php echo esc_attr( $column ); ?>">
                                <div class="content-container">

                                    <?php
                                    while ( have_posts() ) {
                                        the_post();
                                        wc_get_template_part( 'content', 'single-product' );
                                    }
                                    ?>

                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
            <?php
        }
    }
}

do_action( "styler_after_wc_single" );

get_footer();

?>
