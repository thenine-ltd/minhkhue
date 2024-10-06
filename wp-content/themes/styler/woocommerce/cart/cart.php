<?php
/**
* Cart Page
*
* This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
*
* HOWEVER, on occasion WooCommerce will need to update template files and you
* (the theme developer) will need to copy the new files to your theme to
* maintain compatibility. We try to do this as little as possible, but it does
* happen. When this occurs the version of the template file will be bumped and
* the readme will list any important changes.
*
* @see     https://docs.woocommerce.com/document/template-structure/
* @package WooCommerce\Templates
* @version 7.9.0
*/

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' );

remove_action( 'woocommerce_cart_collaterals', 'woocommerce_cart_totals', 10 );
?>
<div class="row styler-cart-row">
    <div class="col-lg-8">
        <?php if ( '1' == styler_settings( 'free_shipping_progressbar_cartpage_visibility', '1' ) ) { ?>
            <div class="styler-before-cart-table">
                <?php do_action( 'styler_before_cart_table' ); ?>
            </div>
        <?php } ?>
        <form class="woocommerce-cart-form styler-woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
            <?php do_action( 'woocommerce_before_cart_table' ); ?>

            <div class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
                <?php do_action( 'woocommerce_before_cart_contents' ); ?>

                <?php
                foreach ( WC()->cart->get_cart() as $key => $item ) {
                    $p    = apply_filters( 'woocommerce_cart_item_product', $item['data'], $item, $key );
                    $pid  = apply_filters( 'woocommerce_cart_item_product_id', $item['product_id'], $item, $key );
                    $size = apply_filters( 'styler_cart_item_img_size', 'thumbnail' );
                    $name = apply_filters( 'woocommerce_cart_item_name', $p->get_name(), $item, $key );
                    $qty  = $item['quantity'];

                    if ( $p && $p->exists() && $qty > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $item, $key ) ) {
                        $permalink = apply_filters( 'woocommerce_cart_item_permalink', $p->is_visible() ? $p->get_permalink( $item ) : '', $item, $key );
                        $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $p->get_image( $size ), $item, $key );
                        ?>
                        <div class="row styler-cart-item styler-align-center woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $item, $key ) ); ?>">
                            <div class="col-12 col-sm-6">
                                <div class="row styler-meta-left styler-flex styler-align-center">
                                    <div class="col-3 product-thumbnail">
                                        <?php
                                        if ( ! $permalink ) {
                                            printf( '%s', $thumbnail );
                                        } else {
                                            printf( '<a href="%s">%s</a>', esc_url( $permalink ), $thumbnail );
                                        }
                                        ?>
                                    </div>
                                    <div class="col-9 product-name styler-small-title" data-title="<?php esc_attr_e( 'Product', 'styler' ); ?>">
                                        <?php
                                        if ( ! $permalink ) {
                                            echo wp_kses_post( $name ).'&nbsp;';
                                        } else {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $permalink ), $name ), $item, $key ) );
                                        }

                                        do_action( 'woocommerce_after_cart_item_name', $item, $key );

                                        // Meta data.
                                        echo wc_get_formatted_cart_item_data( $item );

                                        // Backorder notification.
                                        if ( $p->backorders_require_notification() && $p->is_on_backorder( $qty ) ) {
                                            echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'styler' ) . '</p>', $pid ) );
                                        }
                                        ?>
                                        <div class="product-price styler-price" data-title="<?php esc_attr_e( 'Price', 'styler' ); ?>">
                                            <span class="price">
                                                <?php echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $p ), $item, $key ); ?>
                                            </span>
                                            <span class="cart-quantity"><?php printf( esc_html__( 'X %1$s', 'styler' ), $qty ); ?></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-12 col-sm-6">
                                <div class="row styler-meta-right styler-align-center">
                                    <div class="col-auto product-quantity styler-quantity-small" data-title="<?php esc_attr_e( 'Quantity', 'styler' ); ?>">
                                        <?php
                                        if ( $p->is_sold_individually() ) {
                                            $min = 1;
                                            $max = 1;
                                        } else {
                                            $min = 0;
                                            $max = $p->get_max_purchase_quantity();
                                        }
                                        $quantity = woocommerce_quantity_input(
                                            array(
                                                'input_name'   => "cart[{$key}][qty]",
                                                'input_value'  => $qty,
                                                'max_value'    => $max,
                                                'min_value'    => $min,
                                                'product_name' => $name
                                            ),
                                            $p,
                                            false
                                        );
                                        echo apply_filters( 'woocommerce_cart_item_quantity', $quantity, $key, $item );
                                        ?>
                                    </div>
                                    <div class="col-auto product-subtotal styler-price" data-title="<?php esc_attr_e( 'Subtotal', 'styler' ); ?>">
                                        <?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $p, $qty ), $item, $key );?>
                                    </div>
                                    <div class="col-auto product-remove">
                                        <?php
                                        echo apply_filters(
                                            'woocommerce_cart_item_remove_link',
                                            sprintf(
                                                '<a href="%s" class="remove del-icon" aria-label="%s" data-product_id="%s" data-product_sku="%s">'.styler_svg_lists( 'trash', 'styler-svg-icon mini-icon' ).'</a>',
                                                esc_url( wc_get_cart_remove_url( $key ) ),
                                                esc_attr( sprintf( __( 'Remove %s from cart', 'styler' ), wp_strip_all_tags( $name ) ) ),
                                                esc_attr( $pid ),
                                                esc_attr( $p->get_sku() )
                                            ),
                                            $key
                                        );
                                        ?>
                                        <span class="loading-wrapper"><span class="ajax-loading"></span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
                do_action( 'woocommerce_cart_contents' );
                ?>
                <div class="styler-cart-item styler-actions">
                    <div class="row">
                        <?php if ( wc_coupons_enabled() ) { ?>
                            <div class="col col-lg-8">
                                <div class="styler-flex">
                                    <input type="text" name="coupon_code" class="input-text styler-input styler-input-small" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'styler' ); ?>" />
                                    <button type="submit" class="styler-btn styler-bg-black styler-btn-large cart-apply-button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'styler' ); ?>"><?php esc_html_e( 'Apply coupon', 'styler' ); ?></button>
                                    <?php do_action( 'woocommerce_cart_coupon' ); ?>
                                </div>
                            </div>
                        <?php } ?>
                        <div class="col col-lg-4">
                            <div class="styler-hidden styler-flex styler-flex-right">
                                <button type="submit" class="styler-btn styler-bg-black styler-btn-large cart-update-button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'styler' ); ?>"><?php esc_html_e( 'Update cart', 'styler' ); ?></button>
                                <?php do_action( 'woocommerce_cart_actions' ); ?>
                                <?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
                            </div>
                        </div>
                    </div>
                </div>

                <?php do_action( 'woocommerce_after_cart_contents' ); ?>

            </div>
            <?php do_action( 'woocommerce_after_cart_table' ); ?>
        </form>
    </div>
    <div class="col-lg-4">
        <?php woocommerce_cart_totals(); ?>
    </div>
    <div class="col-lg-12">
        <?php do_action( 'woocommerce_before_cart_collaterals' ); ?>
        <?php
        /**
        * Cart collaterals hook.
        *
        * @hooked woocommerce_cross_sell_display
        * @hooked woocommerce_cart_totals - 10
        */
        do_action( 'woocommerce_cart_collaterals' );
        ?>
    </div>
</div>
<?php do_action( 'woocommerce_after_cart' ); ?>
