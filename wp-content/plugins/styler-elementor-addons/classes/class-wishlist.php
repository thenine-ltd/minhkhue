<?php
/**
* Styler_Wishlist
*/
if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
if ( ! class_exists( 'Styler_Wishlist' ) ) {
    class Styler_Wishlist {
        private static $instance = null;
        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        function __construct()
        {
            if ( ! class_exists('WooCommerce') ) {
                return;
            }
            // frontend scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'wp_enqueue_scripts' ) );
            // print template
            add_action( 'styler_side_panel_header_after_cart', array( $this, 'template_header' ), 10 );
            add_action( 'styler_side_panel_content_after_cart', array( $this, 'template_content' ) );
            add_action( 'styler_mobile_panel_content_after_cart', array( $this, 'template_mobile_header_content' ) );
            // add wishlist
            add_action( 'wp_ajax_styler_wishlist_add', array( $this, 'wishlist_add' ) );
            add_action( 'wp_ajax_nopriv_styler_wishlist_add', array( $this, 'wishlist_add' ) );
            // remove wishlist
            add_action( 'wp_ajax_styler_wishlist_remove', array( $this, 'wishlist_remove' ) );
            add_action( 'wp_ajax_nopriv_styler_wishlist_remove', array( $this, 'wishlist_remove' ) );

            // clear all wishlist

            add_action( 'wp_ajax_styler_wishlist_clear', array( $this, 'wishlist_clear' ) );
            add_action( 'wp_ajax_nopriv_styler_wishlist_clear', array( $this, 'wishlist_clear' ) );

            // user login & logout
            add_action( 'wp_login', array( $this, 'wishlist_wp_login' ), 10, 2 );
            add_action( 'wp_logout', array( $this, 'wishlist_wp_logout' ), 10, 1 );

            add_shortcode( 'styler_wishlist', [ $this, 'template_wishlist_page' ] );
        }

        function wp_enqueue_scripts()
        {
            // localize
            $is_login = ! is_user_logged_in() && '1' == styler_settings('wishlist_disable_unauthenticated', '0' ) ? 'yes' : 'no';
            wp_enqueue_script( 'styler-wishlist', STYLER_PLUGIN_URL . 'assets/front/js/wishlist/wishlist.js', array( 'jquery' ), STYLER_PLUGIN_VERSION, true );
            wp_localize_script( 'styler-wishlist', 'wishlist_vars', array(
                    'ajax_url'          => admin_url( 'admin-ajax.php' ),
                    'count'             => $this->get_count(),
                    'max_count'         => styler_settings('wishlist_max_count'),
                    'max_message'       => esc_html__( 'Sorry, you\'ve reached the max product limit.You can\'t add more products.', 'styler' ),
                    'is_login'          => $is_login,
                    'login_mesage'      => esc_html__( 'Please log in to use the wishlist!', 'styler' ),
                    'products'          => $this->get_products(),
                    'nonce'             => wp_create_nonce( 'styler-wishlist-nonce' ),
                    'user_id'           => md5( 'styler_wishlist_' . get_current_user_id() ),
                    'btn_action'        => styler_settings( 'wishlist_btn_action', 'panel' ),
                    'header_btn_action' => styler_settings( 'header_wishlist_btn_action', 'panel' ),
                    'wishlist_page'     => get_the_ID() == styler_settings('wishlist_page_id') ? 'yes' : 'no'
                )
            );
        }

        function template_header()
        {
            ?>
            <div class="panel-header-wishlist panel-header-btn" data-name="wishlist">
                <span class="styler-wishlist-count styler-wc-count"><?php echo esc_html( $this->get_count() ); ?></span>
                <?php echo styler_svg_lists( 'love', 'styler-svg-icon' ); ?>
            </div>
            <?php
        }

        function template_wishlist_page()
        {
            $key           = self::get_key();
            $page_id       = styler_settings('wishlist_page_id');
            $page_link     = $page_id ? get_page_link($page_id) : '';
            $share_url_raw = trailingslashit( $page_link ) . $key;
            $share_url     = urlencode( $share_url_raw );
            $html= '';
            $html .='<div class="wishlist-content wishlist-all-items">';
                if ( $this->get_count() ) {
                    $html .='<div class="styler-wishlist-items">';
                        ob_start();
                        $this->print_wishlist();
                    $html .= ob_get_clean().'</div>';
                    if ( $page_link && '1' == styler_settings('wishlist_page_copy') ) {
                        $html .='<div class="styler-wishlist-copy">';
                            $html .='<span class="styler-wishlist-copy-label">'.esc_html__( 'Wishlist link:', 'styler' ).'</span> ';
                            $html .='<span class="styler-wishlist_copy_url"><input id="styler-wishlist_copy_url" type="url" value="'.esc_attr( $share_url_raw ).'" readonly/></span>';
                            $html .=' <span class="styler-wishlist_copy_btn"><input id="styler-wishlist_copy_btn" type="button" value="'.esc_attr__( 'Copy', 'styler' ).'"/></span>';
                        $html .='</div>';
                    }
                } else {
                    $html .='<div class="styler-panel-content-notice styler-wishlist-content-notice styler-empty-content">';
                        $html .= styler_svg_lists( 'love', 'styler-big-svg-icon' );
                        $html .='<div class="styler-small-title">'.esc_html__( 'There are no products on the wishlist!', 'styler' ).'</div>';
                        $html .='<a class="styler-btn-small mt-10" href="'.esc_url( wc_get_page_permalink( 'shop' ) ).'">'.esc_html__( 'Start Shopping', 'styler' ).'</a>';
                    $html .='</div>';
                }
            $html .='</div>';
            return $html;
        }

        function template_mobile_header_content()
        {
            $url        = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class  = is_shop() ? ' styler-panel-close' : '';
            $key        = self::get_key();
            $products   = get_option( 'styler_wishlist_' . $key );
            $count      = is_array( $products ) && ( count( $products ) > 0 ) ? count( $products ) : '0';
            $page_id    = styler_settings('wishlist_page_id');
            $page_link  = $page_id ? get_page_link($page_id) : wc_get_page_permalink( 'shop' );
            $show_clear = styler_settings('sidebar_panel_wishlist_clear_btn','0');
            $clear_btn  = '1' == $show_clear ? '<span class="clear-all-wishlist">'.esc_html__( 'Clear All', 'styler' ).'</span>' : '';
            $has_clear  = '1' == $show_clear ? ' has-clear-btn' : '';
            ?>
            <div class="wishlist-area action-content" data-target-name="wishlist" data-wishlist-count="<?php echo esc_attr( $count ); ?>">
                <div class="wishlist-content">
                    <?php if ( function_exists('styler_settings') && '' != styler_settings('sidebar_panel_wishlist_custom_title') ) { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php echo esc_html( styler_settings('sidebar_panel_wishlist_custom_title') ); ?><?php printf( '%s',$clear_btn ); ?></span>
                    <?php } else { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php esc_html_e( 'Your Wishlist', 'styler' ); ?><?php printf( '%s',$clear_btn ); ?></span>
                    <?php } ?>
                    <div class="styler-panel-content-items styler-wishlist-content-items styler-perfect-scrollbar">
                        <?php $this->print_wishlist(); ?>
                    </div>
                    <div class="styler-panel-content-notice styler-wishlist-content-notice styler-empty-content">
                        <?php if ( !$this->get_count() ) { ?>
                            <?php echo styler_svg_lists( 'love', 'styler-big-svg-icon' ); ?>
                            <div class="styler-small-title"><?php esc_html_e( 'There are no products on the wishlist!', 'styler' ); ?></div>
                            <a class="styler-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'styler' ); ?></a>
                        <?php } ?>
                    </div>
                    <?php if ( '' != styler_settings('wishlist_page_id') ) { ?>
                        <a class="styler-btn-small wishlist-page-link" href="<?php echo esc_url( $page_link ); ?>"><?php esc_html_e( 'Open Wishlist Page', 'styler' ); ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php
        }

        function template_content()
        {
            $url        = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class  = is_shop() ? ' styler-panel-close' : '';
            $key        = self::get_key();
            $products   = get_option( 'styler_wishlist_' . $key );
            $count      = is_array( $products ) && ( count( $products ) > 0 ) ? count( $products ) : '0';
            $page_id    = styler_settings('wishlist_page_id');
            $page_link  = $page_id ? get_page_link($page_id) : wc_get_page_permalink( 'shop' );
            $show_clear = styler_settings('sidebar_panel_wishlist_clear_btn','0');
            $clear_btn  = '1' == $show_clear ? '<span class="clear-all-wishlist">'.esc_html__( 'Clear All', 'styler' ).'</span>' : '';
            $has_clear  = '1' == $show_clear ? ' has-clear-btn' : '';
            ?>
            <div class="wishlist-area panel-content-item" data-name="wishlist" data-wishlist-count="<?php echo esc_attr( $count ); ?>">
                <div class="wishlist-content">
                    <?php if ( function_exists('styler_settings') && '' != styler_settings('sidebar_panel_wishlist_custom_title') ) { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php echo esc_html( styler_settings('sidebar_panel_wishlist_custom_title') ); ?><?php printf( '%s',$clear_btn ); ?></span>
                    <?php } else { ?>
                        <span class="panel-top-title<?php echo esc_attr( $has_clear ); ?>"><?php esc_html_e( 'Your Wishlist', 'styler' ); ?><?php printf( '%s',$clear_btn ); ?></span></span>

                    <?php } ?>
                    <div class="styler-panel-content-items styler-wishlist-content-items styler-perfect-scrollbar">
                        <?php $this->print_wishlist(); ?>
                    </div>
                    <div class="styler-panel-content-notice styler-wishlist-content-notice styler-empty-content">
                        <?php if ( !$this->get_count() ) { ?>
                            <?php echo styler_svg_lists( 'love', 'styler-big-svg-icon' ); ?>
                            <div class="styler-small-title"><?php esc_html_e( 'There are no products on the wishlist!', 'styler' ); ?></div>
                            <a class="styler-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'styler' ); ?></a>
                        <?php } ?>
                    </div>
                    <?php if ( '' != styler_settings('wishlist_page_id') ) { ?>
                        <a class="styler-btn-small wishlist-page-link" href="<?php echo esc_url( $page_link ); ?>"><?php esc_html_e( 'Open Wishlist Page', 'styler' ); ?></a>
                    <?php } ?>
                </div>
            </div>
            <?php
        }

        function print_wishlist()
        {
            $key = self::get_key();
            $products = get_option( 'styler_wishlist_' . $key );

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $product_id => $product_data ) {
                    $product = wc_get_product( $product_id );

                    if ( ! $product ) {
                        continue;
                    }
                    $stock_status = $product->is_in_stock() ? esc_html__( 'In stock', 'styler' ) : esc_html__( 'Out of stock', 'styler' );
                    ?>
                    <div class="styler-content-item styler-wishlist-item" data-id="<?php echo esc_attr( $product_id ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                        <div class="styler-content-item-inner">
                            <?php
                            $imgurl = get_the_post_thumbnail_url($product->get_id(),'styler-panel');
                            $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                            $img    = '<img width="80" height="80" src="'.$imgsrc.'" alt="'.$product->get_name().'"/>';
                            echo sprintf( '<a href="%s">%s</a>',
                                esc_url( $product->get_permalink() ),
                                $img
                            );
                            ?>
                            <div class="styler-content-info">
                                <div class="styler-small-title">
                                    <a class="styler-content-link" data-id="<?php echo esc_attr( $product_id ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                        <span class="product-name"><?php echo esc_html( $product->get_name() ); ?></span>
                                        <span>
                                            <?php if ( $product->get_price_html() ) { ?>
                                                <span class="product-price styler-price"><?php printf('%s', $product->get_price_html() ); ?></span> /
                                            <?php } ?>
                                            <span class="product-stock styler-stock"> <?php echo esc_html( $stock_status ); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <?php echo do_shortcode('[add_to_cart style="" show_price="false" id="'.$product_id.'"]'); ?>
                            </div>
                            <div class="styler-content-del-icon styler-wishlist-del-icon"><?php echo styler_svg_lists( 'trash', 'styler-svg-icon mini-icon' ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        function get_items( $key )
        {
            $key = self::get_key();
            $products = get_option( 'styler_wishlist_' . $key );

            ob_start();

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $product_id => $product_data ) {
                    $product = wc_get_product( $product_id );

                    if ( ! $product ) {
                        continue;
                    }
                    $stock_status = $product->is_in_stock() ? esc_html__( 'In stock', 'styler' ) : esc_html__( 'Out of stock', 'styler' );
                    ?>
                    <div class="styler-content-item styler-wishlist-item" data-id="<?php echo esc_attr( $product_id ); ?>" data-key="<?php echo esc_attr( $key ); ?>">
                        <div class="styler-content-item-inner">
                            <?php
                            $imgurl = get_the_post_thumbnail_url($product->get_id(),'styler-panel');
                            $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                            $img    = '<img width="80" height="80" src="'.$imgsrc.'" alt="'.$product->get_name().'"/>';
                            echo sprintf( '<a href="%s">%s</a>',
                                esc_url( $product->get_permalink() ),
                                $img
                            );
                            ?>
                            <div class="styler-content-info">
                                <div class="styler-small-title">
                                    <a class="styler-content-link" data-id="<?php echo esc_attr( $product_id ); ?>" href="<?php echo esc_url( $product->get_permalink() ); ?>">
                                        <span class="product-name"><?php echo esc_html( $product->get_name() ); ?></span>
                                        <span>
                                            <?php if ( $product->get_price_html() ) { ?>
                                                <span class="product-price styler-price"><?php printf('%s', $product->get_price_html() ); ?></span> /
                                            <?php } ?>
                                            <span class="product-stock styler-stock"> <?php echo esc_html( $stock_status ); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <?php echo do_shortcode('[add_to_cart style="" show_price="false" id="'.$product_id.'"]'); ?>
                            </div>
                            <div class="styler-content-del-icon styler-wishlist-del-icon"><?php echo styler_svg_lists( 'trash', 'styler-svg-icon mini-icon' ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
            $html = ob_get_clean();

            return $html;
        }

        function wishlist_add()
        {
            $return = array( 'status' => 0 );
            $product_id = absint( $_POST['product_id'] );
            $max_count  = styler_settings('wishlist_max_count', -1);
            $btn_action = styler_settings( 'wishlist_btn_action', 'panel' );
            if ( $product_id > 0 ) {
                $key = self::get_key();

                if ( $key === '#' ) {
                    $return['status'] = 0;
                    $return['notice'] = esc_html__( 'Please log in to use the wishlist!', 'styler' );
                    $return['value']  = esc_html__( 'Please log in to use the wishlist!', 'styler' );
                } else {
                    $products = get_option( 'styler_wishlist_' . $key ) ? get_option( 'styler_wishlist_' . $key ) : array();
                    $product  = wc_get_product( $product_id );

                    if ( ! array_key_exists( $product_id, $products ) ) {
                        $products = array(
                            $product_id => array('time' => time() )
                        ) + $products;

                        update_option( 'styler_wishlist_' . $key, $products );
                        $this->update_meta( $product_id, 'styler_wishlist_add' );

                        if ( $btn_action == 'message' ) {
                            $imgurl = get_the_post_thumbnail_url($product_id,'styler-panel');
                            $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                            $return['notice'] = sprintf('<img width="80" height="80" src="'.$imgsrc.'"/><div class="styler-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Added to the wishlist!', 'styler' )
                            );
                        } else {
                            $return['notice'] = sprintf('<div class="styler-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Added to the wishlist!', 'styler' )
                            );
                        }
                    } else {
                        $return['notice'] = sprintf('<div class="styler-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                            esc_html( $product->get_name() ),
                            esc_html__( 'Already in the wishlist!', 'styler' )
                        );
                    }

                    $return['status']   = 1;
                    $return['count']    = count( $products );
                    $return['value']    = $this->get_items( $key );
                    $return['products'] = $products;
                }
            } else {
                $product_id       = 0;
                $return['status'] = 0;
                $return['notice'] = esc_html__( 'Have an error, please try again!', 'styler' );
            }

            echo json_encode( $return );
            die();
        }

        function wishlist_remove()
        {
            $return     = array( 'status' => 0 );
            $product_id = absint( $_POST['product_id'] );
            $icon       = styler_svg_lists( 'love', 'styler-big-svg-icon' );
            $url        = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class  = is_shop() ? ' styler-panel-close' : '';

            if ( $product_id > 0 ) {
                $key = self::get_key();

                if ( $key === '#' ) {
                    $return['notice'] = esc_html__( 'Please log in to use the wishlist!', 'styler' );
                } else {

                    $products = get_option( 'styler_wishlist_' . $key ) ? get_option( 'styler_wishlist_' . $key ) : array();
                    $product  = wc_get_product( $product_id );
                    $name     = '<span class="product-name">'.esc_html( $product->get_name() ).'</span>';

                    if ( array_key_exists( $product_id, $products ) ) {
                        unset( $products[ $product_id ] );
                        update_option( 'styler_wishlist_' . $key, $products );
                        $this->update_meta( $product_id, 'styler_wishlist_remove' );
                        $return['count']  = count( $products );
                        $return['status'] = 1;

                        if ( count( $products ) > 0 ) {
                            $return['notice'] = sprintf('<div class="styler-small-title"><span class="product-name">%s</span> <span>%s</span></div>',
                                esc_html( $product->get_name() ),
                                esc_html__( 'Removed from wishlist!', 'styler' )
                            );
                        } else {

                            $return['notice_type'] = 'empty';
                            $return['notice']      = sprintf('%s<div class="styler-small-title">%s</div><a class="styler-btn-small mt-10%s" href="%s">%s</a>',
                                $icon,
                                esc_html__( 'There are no products on the wishlist!', 'styler' ),
                                $btn_class,
                                esc_url( $url ),
                                esc_html__( 'Start Shopping', 'styler' )
                            );
                        }
                    } else {
                        $return['notice'] = sprintf('%s<div class="styler-small-title">%s</div><a class="styler-btn-small mt-10%s" href="%s">%s</a>',
                            $icon,
                            esc_html__( 'The product does not exist on the wishlist!', 'styler' ),
                            $btn_class,
                            esc_url( $url ),
                            esc_html__( 'Start Shopping', 'styler' )
                        );
                    }
                }
            } else {
                $product_id = 0;
                $return['notice'] = esc_html__( 'Have an error, please try again!', 'styler' );
            }

            echo json_encode( $return );
            die();
        }

        function wishlist_clear()
        {
            if ( '0' == styler_settings('sidebar_panel_wishlist_clear_btn') ) {
                return;
                die();
            }

            $return    = array( 'status' => 0 );
            $icon      = styler_svg_lists( 'love', 'styler-big-svg-icon' );
            $url       = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class = is_shop() ? ' styler-panel-close' : '';
            $key       = self::get_key();

            if ( $key === '#' ) {
                $return['notice'] = esc_html__( 'Please log in to use the wishlist!', 'styler' );
            } else {

                $products = get_option( 'styler_wishlist_'.$key );

                foreach ( $products as $keyy => $value ) {
                    $this->update_meta( $keyy, 'styler_wishlist_clear' );
                }
                update_option( 'styler_wishlist_'.$key, 0 );

                $return['status']      = 1;
                $return['notice_type'] = 'empty';
                $return['notice']      = sprintf('%s<div class="styler-small-title">%s</div><a class="styler-btn-small mt-10%s" href="%s">%s</a>',
                    $icon,
                    esc_html__( 'There are no products on the wishlist!', 'styler' ),
                    $btn_class,
                    esc_url( $url ),
                    esc_html__( 'Start Shopping', 'styler' )
                );
            }

            echo json_encode( $return );
            die();
        }

        function update_meta( $product_id, $action = 'styler_wishlist_add' )
        {
            $meta_count = 'styler_wishlist_count';
            $count      = get_post_meta( $product_id, $meta_count, true );
            $new_count  = 0;

            if ( $action === 'styler_wishlist_add' ) {
                if ( $count ) {
                    $new_count = absint( $count ) + 1;
                } else {
                    $new_count = 1;
                }
            } elseif ( $action === 'styler_wishlist_remove' ) {
                if ( $count && ( absint( $count ) > 1 ) ) {
                    $new_count = absint( $count ) - 1;
                } else {
                    $new_count = 0;
                }
            } elseif ( $action === 'styler_wishlist_clear' ) {
                if ( $count && ( absint( $count ) > 1 ) ) {
                    $new_count = absint( $count ) - 1;
                } else {
                    $new_count = 0;
                }
            }

            update_post_meta( $product_id, $meta_count, $new_count );
            update_post_meta( $product_id, $action, time() );
        }

        public function wishlist_wp_login( $user_login, $user ) {
            if ( isset( $user->data->ID ) ) {
                $user_key = get_user_meta( $user->data->ID, 'styler_wishlist_key', true );

                if ( empty( $user_key ) ) {
                    $user_key = self::generate_key();

                    while ( self::exists_key( $user_key ) ) {
                        $user_key = self::generate_key();
                    }

                    // set a new key
                    update_user_meta( $user->data->ID, 'styler_wishlist_key', $user_key );
                }

                $secure   = apply_filters( 'styler_wishlist_cookie_secure', wc_site_is_https() && is_ssl() );
                $httponly = apply_filters( 'styler_wishlist_cookie_httponly', true );

                if ( isset( $_COOKIE['styler_wishlist_key'] ) && ! empty( $_COOKIE['styler_wishlist_key'] ) ) {
                    wc_setcookie( 'styler_wishlist_key_ori', $_COOKIE['styler_wishlist_key'], time() + 604800, $secure, $httponly );
                }

                wc_setcookie( 'styler_wishlist_key', $user_key, time() + 604800, $secure, $httponly );
            }
        }

        public function wishlist_wp_logout( $user_id ) {
            if ( isset( $_COOKIE['styler_wishlist_key_ori'] ) && ! empty( $_COOKIE['styler_wishlist_key_ori'] ) ) {
                $secure   = apply_filters( 'styler_wishlist_cookie_secure', wc_site_is_https() && is_ssl() );
                $httponly = apply_filters( 'styler_wishlist_cookie_httponly', true );

                wc_setcookie( 'styler_wishlist_key', $_COOKIE['styler_wishlist_key_ori'], time() + 604800, $secure, $httponly );
            } else {
                unset( $_COOKIE['styler_wishlist_key_ori'] );
                unset( $_COOKIE['styler_wishlist_key'] );
            }
        }

        public static function generate_key()
        {
            $key         = '';
            $key_str     = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789';
            $key_str_len = strlen( $key_str );

            for ( $i = 0; $i < 6; $i ++ ) {
                $key .= $key_str[ random_int( 0, $key_str_len - 1 ) ];
            }

            return $key;
        }

        public static function get_key()
        {
            if ( ! is_user_logged_in() && '1' == styler_settings('wishlist_disable_unauthenticated', '0' ) ) {
                return '#';
            }

            if ( is_user_logged_in() && ( ( $user_id = get_current_user_id() ) > 0 ) ) {
                $user_key = get_user_meta( $user_id, 'styler_wishlist_key', true );

                if ( empty( $user_key ) ) {
                    $user_key = self::generate_key();

                    while ( self::exists_key( $user_key ) ) {
                        $user_key = self::generate_key();
                    }

                    // set a new key
                    update_user_meta( $user_id, 'styler_wishlist_key', $user_key );
                }

                return $user_key;
            }

            if ( isset( $_COOKIE['styler_wishlist_key'] ) ) {
                return esc_attr( $_COOKIE['styler_wishlist_key'] );
            }

            return 'STYLERWL';
        }

        public static function exists_key( $key )
        {
            return get_option( 'styler_list_' . $key ) ? true : false;
        }

        public static function get_count( $key = null )
        {
            if ( ! $key ) {
                $key = self::get_key();
            }
            $products = get_option( 'styler_wishlist_' . $key );

            if ( ( $key != '' ) && $products && is_array( $products ) ) {
                $count = count( $products );
            } else {
                $count = 0;
            }

            return $count;
        }

        public static function get_products( $key = null )
        {
            if ( ! $key ) {
                $key = self::get_key();
            }
            $products = get_option( 'styler_wishlist_' . $key );
            $ids = array();
            if ( ( $key != '' ) && $products && is_array( $products ) ) {
                foreach ( $products as $key => $id ) {
                    $ids[] = $key;
                }
                return $ids;
            }
        }
    }
    Styler_Wishlist::get_instance();
}
