<?php
/**
 * Mini-cart
 *
 * Contains the markup for the mini-cart, used by the cart widget.
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/mini-cart.php.
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
global $woocommerce;
do_action( 'woocommerce_before_mini_cart' ); ?>

<?php if ( ! WC()->cart->is_empty() ) : ?>
    <div class="woocommerce-mini-cart cart-area cart_list product_list_widget styler-minicart">
        <div class="woocommerce-mini-cart<?php echo !empty($args['list_class']) ? ' '.esc_attr( $args['list_class'] ) : ''; ?>">
            <div class="styler-perfect-scrollbar">
                <?php
                do_action( 'woocommerce_before_mini_cart_contents' );

                foreach ( WC()->cart->get_cart() as $key => $item ) {
                    $p   = apply_filters( 'woocommerce_cart_item_product', $item['data'], $item, $key );
                    $pid = apply_filters( 'woocommerce_cart_item_product_id', $item['product_id'], $item, $key );
                    $qty = $item['quantity'];

                    if ( $p && $p->exists() && $qty > 0 && apply_filters( 'woocommerce_widget_cart_item_visible', true, $item, $key ) ) {
                    $name      = apply_filters( 'woocommerce_cart_item_name', $p->get_name(), $item, $key );
                    $thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $p->get_image('thumbnail'), $item, $key );
                    $price     = apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $p ), $item, $key );
                    $permalink = apply_filters( 'woocommerce_cart_item_permalink', $p->is_visible() ? $p->get_permalink( $item ) : '', $item, $key );
                    ?>
                        <div class="woocommerce-mini-cart-item mini-cart-item styler-cart-item" data-key="<?php echo esc_attr( $key ); ?>">
                            <div class="cart-item-details">
                                <a class="product-link" href="<?php echo esc_url( get_permalink( $pid ) ) ?>"><?php printf( '%s', $thumbnail ); ?></a>
                                <div class="cart-item-title styler-small-title">
                                    <a class="product-link" href="<?php echo esc_url( get_permalink( $pid ) ) ?>">
                                    <?php printf( '<span class="cart-name">%s</span>', $name ); ?>
                                    <span class="styler-price price">
                                        <?php echo apply_filters( 'woocommerce_widget_cart_item_quantity', '<span class="new">' . sprintf( '%s', $price ) . '</span>', $item, $key ); ?>
                                        <span class="cart-quantity"><?php printf( esc_html__( 'X %1$s', 'styler' ), $qty ); ?></span>
                                    </span>
                                    </a>
                                    <div class="cart-quantity-wrapper ajax-quantity" data-product_id="<?php echo esc_attr( $pid ); ?>">
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
                                </div>
                            </div>
                            <div class="del-icon" data-id="<?php echo esc_attr( $pid ); ?>">
                                <?php
                                echo apply_filters(
                                    'woocommerce_cart_item_remove_link',
                                    sprintf(
                                        '<a href="%s" class="styler_remove_from_cart_button" aria-label="%s" data-product_id="%s" data-cart_item_key="%s" data-name="%s" data-qty="%s">'.styler_svg_lists( 'trash', 'styler-svg-icon mini-icon' ).'</a>',
                                        esc_url( wc_get_cart_remove_url( $key ) ),
                                        esc_attr( sprintf( __( 'Remove %s from cart', 'styler' ), wp_strip_all_tags( $name ) ) ),
                                        esc_attr( $pid ),
                                        esc_attr( $key ),
                                        esc_attr( $name ),
                                        $qty
                                    ),
                                    $key
                                );
                                ?>
                            </div>
                        </div>
                        <?php
                    }
                }
                do_action( 'woocommerce_mini_cart_contents' );
                ?>
            </div>
        </div>
        <div class="header-cart-footer">
            <div class="cart-total">
                <?php if ( '1' == styler_settings('sidebar_panel_cart_total_visibility', '0') ) { ?>
                    <div class="cart-total-price">
                        <div class="cart-total-price-left"><?php echo esc_html_e( 'Total: ', 'styler' ); ?></div>
                        <div class="cart-total-price-right"><?php printf( '%s', WC()->cart->get_cart_total() ); ?></div>
                    </div>
                <?php } ?>
                <div class="cart-total-price">
                    <div class="cart-total-price-left"><?php echo esc_html_e( 'Total: ', 'styler' ); ?></div>
                    <div class="cart-total-price-right"><?php echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $p, $qty ), $item, $key );?></div>
                </div>
            </div>
            <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
            <div class="cart-bottom-btn">
                <a class="styler-btn-medium styler-btn styler-bg-black" href="<?php echo esc_url( wc_get_cart_url() ); ?>"><?php echo esc_html_e( 'View Cart', 'styler' ); ?></a>
                <a class="styler-btn-medium styler-btn styler-bg-black" href="<?php echo esc_url( wc_get_checkout_url() ); ?>"><?php echo esc_html_e( 'Checkout', 'styler' ); ?></a>
            </div>
            <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
        </div>
    </div>
<?php else : ?>
    <div class="woocommerce-mini-cart styler-minicart">
        <div class="cart-empty-content">
            <?php echo styler_svg_lists( 'bag' ); ?>
            <span class="minicart-title"><?php echo esc_html__( 'Your Cart', 'styler' ); ?></span>
            <p class="empty-title styler-small-title"><?php esc_html_e( 'No products in the cart.', 'styler' ); ?></p>
            <?php do_action( 'woocommerce_widget_shopping_cart_before_buttons' ); ?>
            <div class="cart-empty-actions cart-bottom-btn">
                <a class="styler-btn-medium styler-btn styler-bg-black" href="<?php echo esc_url( apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) ); ?>"><?php esc_html_e( 'Start Shopping', 'styler' ); ?></a>
                <a class="styler-btn-medium styler-btn styler-bg-black" href="<?php echo esc_url( get_permalink( get_option( 'wp_page_for_privacy_policy' ) ) ); ?>"><?php esc_html_e( 'Return Policy', 'styler' ); ?></a>
            </div>
            <?php do_action( 'woocommerce_widget_shopping_cart_after_buttons' ); ?>
        </div>
    </div>
<?php endif; ?>
<?php do_action( 'woocommerce_after_mini_cart' ); ?>
