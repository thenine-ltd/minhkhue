<?php
/**
 * The Template for displaying product archives, including the main shop page which is a post type archive
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/archive-product.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.4.0
 */

// Elementor `archive` location
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'archive' ) ) {
    get_header();
}
// Elementor `archive` location
if ( ! function_exists( 'elementor_theme_do_location' ) || ! elementor_theme_do_location( 'archive' ) ) {
    $loop_mode  = woocommerce_get_loop_display_mode();
    $layout     = apply_filters('styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
    $column     = ( $layout == 'left-sidebar' || $layout == 'right-sidebar' ) && is_active_sidebar( 'shop-page-sidebar' ) ? 'col-lg-9' : 'col-lg-12';
    $container  = styler_settings( 'shop_container_width', 'default' );
    $container  = 'default' == $container ? 'container-xl styler-container-xl' : 'container-fluid';
    $pagination = apply_filters('styler_shop_pagination_type', styler_settings('shop_paginate_type') );

    wp_enqueue_script( 'jquery-nice-select');

    if ( '1' == styler_settings('shop_ajax_filter', '1' ) ) {
        wp_enqueue_script( 'pjax' );
        wp_enqueue_script( 'shopAjaxFilter' );
    }

    if ( $pagination == 'infinite' ) {
        wp_enqueue_script( 'styler-infinite-scroll' );
    }

    if ( $pagination == 'loadmore' ) {
        wp_enqueue_script( 'styler-load-more' );
    }

    if ( !styler_is_pjax() ) {
        get_header();
    }

    ?>
    <div class="nt-shop-page-wrapper">
        <div id="nt-shop-page" class="nt-shop-page loop-mode-<?php echo esc_attr( $loop_mode ); ?>">
            <?php
            /**
            * Hook: styler_before_shop_content.
            *
            * @hooked styler_wc_hero_section - 10
            * @hooked styler_before_shop_elementor_templates - 15
            */
            do_action( 'styler_before_shop_content' );
            ?>

            <div class="nt-styler-inner-container shop-area section-padding">
                <div class="<?php echo esc_attr( $container ); ?>">

                    <div class="row">

                        <?php
                        /**
                        * Hook: styler_shop_sidebar.
                        *
                        * @hooked styler_shop_sidebar - 10
                        */
                        do_action( 'styler_shop_sidebar' );
                        ?>

                        <div class="<?php echo esc_attr( $column ); ?> styler-products-column">
                            <?php
                            /**
                            * Hook: styler_shop_before_loop.
                            *
                            * @hooked styler_print_category_banner - 10
                            * @hooked shop_loop_filters_layouts - 15
                            * @hooked styler_shop_top_hidden_sidebar - 20
                            */
                            do_action( 'styler_shop_before_loop' );

                            /**
                            * Hook: styler_shop_main_loop.
                            *
                            * @hooked styler_shop_main_loop - 10
                            */
                            do_action( 'styler_shop_main_loop' );

                            /**
                            * Hook: styler_after_shop_loop.
                            *
                            * @hooked styler_after_shop_loop_elementor_templates - 10
                            */
                            do_action( 'styler_after_shop_loop' );
                            ?>
                        </div>

                    </div>
                </div>
            </div>
        </div>
        <?php
        /**
        * Hook: styler_after_shop_page.
        *
        * @hooked styler_after_shop_page_elementor_templates - 10
        * @hooked styler_shop_sidebar_fixed - 20
        */
        do_action('styler_after_shop_page');
        ?>
    </div>
    <?php
    if ( !styler_is_pjax() ) {
        get_footer();
    }
}
// Elementor `archive` location
if ( function_exists( 'elementor_theme_do_location' ) && elementor_theme_do_location( 'archive' ) ) {
    get_footer();
}
?>
