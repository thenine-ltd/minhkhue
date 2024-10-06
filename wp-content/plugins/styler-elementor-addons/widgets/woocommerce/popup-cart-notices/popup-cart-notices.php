<?php

if ( ! class_exists( 'StylerWooCartNotice' ) && class_exists( 'WC_Product' ) ) {
    class StylerWooCartNotice {
        function __construct() {
            // frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'stylercn_enqueue_scripts' ) );
            // add the time
            add_action( 'woocommerce_add_to_cart', array( $this, 'stylercn_add_to_cart' ), 10 );
            // fragments
            add_filter( 'woocommerce_add_to_cart_fragments', array( $this, 'stylercn_cart_fragment' ) );
            // footer
            add_action( 'wp_footer', array( $this, 'stylercn_footer' ) );
        }
        function stylercn_enqueue_scripts() {

            wp_enqueue_script( 'stylercn-frontend', STYLER_PLUGIN_URL . 'widgets/woocommerce/popup-cart-notices/script.js', array( 'jquery' ), STYLER_PLUGIN_VERSION, true );
        }

        function stylercn_get_product() {
            $items = WC()->cart->get_cart();
            $html  = '<div class="styler-popup-notices">';

            if ( count( $items ) > 0 ) {
                foreach ( $items as $key => $item ) {
                    if ( ! isset( $item['styler_popup_notices_time'] ) ) {
                        $items[ $key ]['styler_popup_notices_time'] = time() - 10000;
                    }
                }
                
                array_multisort( array_column( $items, 'styler_popup_notices_time' ), SORT_ASC, $items );
                $styler_product = end( $items )['data'];

                if ( $styler_product && ( $styler_product_id = $styler_product->get_id() ) ) {
                    if ( ! in_array( $styler_product_id, apply_filters( 'styler_exclude_ids', array( 0 ) ), true ) ) {
                        $html .= '<div class="styler-text">' . sprintf( esc_html__( '%s was added to the cart.', 'styler' ), '<a href="' . $styler_product->get_permalink() . '" target="_blank">' . $styler_product->get_name() . '</a>' ) . '</div>';
                        $cart_content_data = '<span class="styler-popup-cart-content-total">' . wp_kses_post( WC()->cart->get_cart_subtotal() ) . '</span> <span class="styler-cart-content-count">' . wp_kses_data( sprintf( _n( '%d item', '%d items', WC()->cart->get_cart_contents_count(), 'styler' ), WC()->cart->get_cart_contents_count() ) ) . '</span>';
                        $cart_content = sprintf( esc_html__( 'Your cart: %s', 'styler' ), $cart_content_data );
                        $html .= '<div class="styler-cart-content">' . $cart_content . '</div>';
                    }
                }
            }

            $html .= '</div>';

            return $html;
        }

        function stylercn_add_to_cart( $cart_item_key ) {

            WC()->cart->cart_contents[ $cart_item_key ]['styler_popup_notices_time'] = time();

        }

        function stylercn_cart_fragment( $fragments ) {
            $fragments['.styler-popup-notices'] = $this->stylercn_get_product();

            return $fragments;
        }

        function stylercn_footer() {
            echo '<div class="styler-popup-notices"></div>';
        }
    }
    new StylerWooCartNotice();
}
