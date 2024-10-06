<?php
/**
* Single Product tabs
*
* This template can be overridden by copying it to yourtheme/woocommerce/single-product/tabs/tabs.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 3.8.0
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
* Filter tabs and allow third parties to add their own.
*
* Each tab is an array containing title, callback and priority.
*
* @see woocommerce_default_product_tabs()
*/

$product_tabs = apply_filters( 'woocommerce_product_tabs', array() );
if ( !empty( styler_wc_extra_tabs_array() ) ) {
    $extra_tabs   = styler_wc_extra_tabs_array();
    $product_tabs = array_merge($product_tabs, $extra_tabs);
}

if ( ! empty( $product_tabs ) ) {

    $count = $count2 = 0;
    $layout         = apply_filters('styler_single_shop_layout', styler_settings( 'single_shop_layout', 'default' ) );
    $stretch_temp   = apply_filters('styler_single_shop_stretch_elementor_template', styler_settings( 'single_shop_stretch_elementor_template', null ) );
    $tabs_type      = apply_filters( 'styler_product_tabs_type', styler_settings( 'product_tabs_type', 'tabs' ) );
    $accordion_type = 'accordion-2' == $tabs_type ? 'styler-section styler-accordion-after-summary' : 'styler-summary-item styler-accordion-in-summary';

    if ( 'accordion' == $tabs_type || 'accordion-2' == $tabs_type ) { ?>

        <div class="styler-product-accordion-wrapper <?php echo esc_attr( $accordion_type ); ?>" id="accordionProduct">
            <?php if ( 'stretch' == $layout && $stretch_temp ) { ?>
                <div class="row">
                <div class="col-12 col-lg-6">
            <?php } ?>
            <?php foreach ( $product_tabs as $key => $product_tab ) : ?>
                <?php if ( !empty( $product_tab['title'] ) ) { ?>
                    <div class="styler-accordion-item attr-<?php echo esc_attr( $key ); ?>">
                        <div class="styler-accordion-header" data-id="accordion-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </div>
                        <div data-id="accordion-<?php echo esc_attr( $key ); ?>" class="styler-accordion-body">
                            <?php
                            if ( isset( $product_tab['callback'] ) ) {
                                call_user_func( $product_tab['callback'], $key, $product_tab );
                            } elseif( isset( $product_tab['content'] ) ){
                                echo do_shortcode($product_tab['content'] );
                            }
                            ?>
                        </div>
                    </div>
                <?php } ?>
            <?php endforeach; ?>

            <?php if ( 'stretch' == $layout && $stretch_temp ) { ?>
                    </div>
                    <div class="col-12 col-lg-6 styler-section">
                        <?php echo styler_print_elementor_templates( $stretch_temp, 'styler-after-tabs', true ); ?>
                    </div>
                </div>
            <?php } ?>
        </div>

    <?php } else { ?>

        <div class="styler-product-tabs-wrapper tabs-type-1 styler-section" id="productTabContent">
        <?php if ( 'stretch' == $layout && $stretch_temp ) { ?>
            <div class="row">
            <div class="col-12 col-lg-6">
        <?php } ?>
            <div class="styler-product-tab-title">
                <?php foreach ( $product_tabs as $key => $product_tab ) :
                    $active = $count == 0 ? ' active' : '';
                    $count++;
                    ?>
                    <?php if ( !empty($product_tab['title']) ) { ?>
                        <div class="styler-product-tab-title-item <?php echo esc_attr( $active ); ?>" data-id="tab-<?php echo esc_attr( $key ); ?>">
                            <?php echo wp_kses_post( apply_filters( 'woocommerce_product_' . $key . '_tab_title', $product_tab['title'], $key ) ); ?>
                        </div>
                    <?php } ?>
                <?php endforeach; ?>
                <?php do_action( 'styler_product_extra_tabs_title' ); ?>
            </div>
            <div class="styler-product-tabs-content">
                <?php foreach ( $product_tabs as $key => $product_tab ) :
                    $active = $count2 == 0 ? ' show active' : '';
                    $count2++;
                    ?>
                    <?php if ( !empty($product_tab['title']) ) { ?>
                    <div class="styler-product-tab-content-item <?php echo esc_attr( $active ); ?>" data-id="tab-<?php echo esc_attr( $key ); ?>">
                        <?php
                        if ( isset( $product_tab['callback'] ) ) {
                            call_user_func( $product_tab['callback'], $key, $product_tab );
                        } elseif( isset( $product_tab['content'] ) ){
                            echo do_shortcode($product_tab['content'] );
                        }
                        ?>
                    </div>
                <?php } ?>
                <?php endforeach; ?>

                <?php do_action( 'styler_product_extra_tabs_content' ); ?>
                <?php do_action( 'woocommerce_product_after_tabs' ); ?>
            </div>
            <?php if ( 'stretch' == $layout && $stretch_temp ) { ?>
                </div>
                <div class="col-12 col-lg-6">
                    <?php echo styler_print_elementor_templates( $stretch_temp, 'styler-after-tabs mt-40', true ); ?>
                </div>
            </div>
            <?php } ?>
        </div>
        <?php
    }
}
?>
