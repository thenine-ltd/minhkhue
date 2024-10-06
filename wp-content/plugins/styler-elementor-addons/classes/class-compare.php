<?php


if ( ! class_exists( 'Styler_Compare' ) && class_exists( 'WC_Product' ) ) {
    class Styler_Compare {

        private static $instance = null;

        function __construct() {
            
            // enqueue scripts
            add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_scripts' ) );

            // after user login
            add_action( 'wp_login', array( $this, 'set_user_cookie' ), 10, 2 );

            // load content to side panel
            add_action( 'styler_side_panel_header_after_cart', array( $this, 'side_panel_header' ), 20 );
            add_action( 'styler_side_panel_content_after_cart', array( $this, 'side_panel_content' ) );
            add_action( 'styler_mobile_panel_content_after_cart', array( $this, 'side_mobile_panel_content' ) );

            // ajax load compare table
            add_action( 'wp_ajax_styler_add_compare', array( $this, 'load_table' ) );
            add_action( 'wp_ajax_nopriv_styler_add_compare', array( $this, 'load_table' ) );

            add_action( 'wp_ajax_styler_load_compare_table', array( $this, 'load_compare_table' ) );
            add_action( 'wp_ajax_nopriv_styler_load_compare_table', array( $this, 'load_compare_table' ) );

            add_shortcode( 'styler_compare', array( $this, 'get_compare_table' ) );
        }

        public function enqueue_scripts() {
            wp_enqueue_script( 'styler-compare', STYLER_PLUGIN_URL . 'assets/front/js/compare/compare.js', array( 'jquery' ), STYLER_PLUGIN_VERSION, true );
            wp_localize_script( 'styler-compare', 'compare_vars', array(
                'ajaxurl'       => admin_url( 'admin-ajax.php' ),
                'limit'         => styler_settings( 'compare_max_count' ) ? styler_settings( 'compare_max_count' ) : 100,
                'notice'        => esc_html__( 'You can add a maximum of {max_limit} products to the compare table.', 'styler' ),
                'empty'         => esc_html__( 'There are no products on the compare!', 'styler' ),
                'inlist'        => esc_html__( '{name} is already in the Compare list.', 'styler' ),
                'added'         => esc_html__( '{name} has been added to Compare list.', 'styler' ),
                'removed'       => esc_html__( '{name} has been removed from the Compare list.', 'styler' ),
                'count'         => self::get_count(),
                'nonce'         => wp_create_nonce( 'styler-compare-nonce' ),
                'user_id'       => md5( 'styler' . get_current_user_id() ),
                'products'      => self::get_products_ids(),
                'btn_action'    => styler_settings( 'compare_btn_action', 'panel' ),
                'header_action' => styler_settings( 'header_compare_btn_action', 'panel' ),
                'compare_page'  => get_the_ID() == styler_settings('compare_page_id') ? 'yes' : 'no'
            ));
        }

        public function set_user_cookie( $user_login, $user ) {
            if ( isset( $user->data->ID ) ) {
                $user_products = get_user_meta( $user->data->ID, 'styler_products', true );
                $user_fields   = get_user_meta( $user->data->ID, 'styler_fields', true );

                if ( ! empty( $user_products ) ) {
                    setcookie( 'styler_products_' . md5( 'styler' . $user->data->ID ), $user_products, time() + 604800, '/' );
                }

                if ( ! empty( $user_fields ) ) {
                    setcookie( 'styler_fields_' . md5( 'styler' . $user->data->ID ), $user_fields, time() + 604800, '/' );
                }
            }
        }

        public function load_table() {
            self::get_compare();
            wp_die();
        }

        public function side_panel_header()
        {
            $btn_action    = styler_settings( 'compare_btn_action', 'panel' );
            $header_action = styler_settings( 'header_compare_btn_action', 'panel' );

            if ( 'panel' == $btn_action || 'panel' == $header_action ) {
                ?>
                <div class="panel-header-compare panel-header-btn" data-name="compare">
                    <span class="styler-compare-count styler-wc-count"><?php echo esc_html( self::get_count() ); ?></span>
                    <?php echo styler_svg_lists( 'compare', 'styler-svg-icon' ); ?>
                </div>
                <?php
            }
        }

        public function side_mobile_panel_content()
        {
            $btn_action    = styler_settings( 'compare_btn_action', 'panel' );
            $header_action = styler_settings( 'header_compare_btn_action', 'panel' );
            $has_product   = self::get_count() ? ' has-product' : '';
            $url           = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class     = is_shop() ? ' styler-panel-close' : '';

            if ( 'panel' == $btn_action || 'panel' == $header_action ) {
                ?>
                <div class="compare-area action-content<?php echo esc_attr( $has_product ); ?>" data-target-name="compare" data-compare-count="<?php echo esc_attr( self::get_count() ); ?>">
                    <div class="compare-content">
                        <?php if ( function_exists('styler_settings') && '' != styler_settings('sidebar_panel_compare_custom_title') ) { ?>
                            <span class="panel-top-title"><?php echo esc_html( styler_settings('sidebar_panel_compare_custom_title') ); ?></span>
                        <?php } else { ?>
                            <span class="panel-top-title"><?php esc_html_e( 'Your compared products', 'styler' ); ?></span>
                        <?php } ?>

                        <div class="styler-panel-content-items styler-compare-content-items styler-perfect-scrollbar">
                            <?php self::get_compare(); ?>
                        </div>
                        <div class="styler-panel-content-notice styler-compare-content-notice">
                            <div class="styler-empty-content">
                                <?php echo styler_svg_lists( 'compare', 'styler-big-svg-icon' ); ?>
                                <div class="styler-small-title"><?php echo esc_html_e( 'No product is added to the compare list!', 'styler' ); ?></div>
                                <a class="styler-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'styler' ); ?></a>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        public function side_panel_content()
        {
            $btn_action    = styler_settings( 'compare_btn_action', 'panel' );
            $header_action = styler_settings( 'header_compare_btn_action', 'panel' );
            $has_product   = self::get_count() ? ' has-product' : '';
            $url           = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $btn_class     = is_shop() ? ' styler-panel-close' : '';

            if ( 'panel' == $btn_action || 'panel' == $header_action ) {
                ?>
                <div class="compare-area panel-content-item<?php echo esc_attr( $has_product ); ?>" data-name="compare" data-compare-count="<?php echo esc_attr( self::get_count() ); ?>">
                    <div class="compare-content">
                        <?php if ( function_exists('styler_settings') && '' != styler_settings('sidebar_panel_compare_custom_title') ) { ?>
                            <span class="panel-top-title"><?php echo esc_html( styler_settings('sidebar_panel_compare_custom_title') ); ?></span>
                        <?php } else { ?>
                            <span class="panel-top-title"><?php esc_html_e( 'Your compared products', 'styler' ); ?></span>
                        <?php } ?>
                        <div class="styler-panel-content-items styler-compare-content-items styler-perfect-scrollbar">
                            <?php self::get_compare(); ?>
                        </div>
                        <div class="styler-panel-content-notice styler-compare-content-notice styler-empty-content">
                            <?php echo styler_svg_lists( 'compare', 'styler-big-svg-icon' ); ?>
                            <div class="styler-small-title"><?php echo esc_html_e( 'No product is added to the compare list!', 'styler' ); ?></div>
                            <a class="styler-btn-small mt-10<?php echo esc_attr( $btn_class ); ?>" href="<?php echo esc_url( $url ); ?>"><?php esc_html_e( 'Start Shopping', 'styler' ); ?></a>
                        </div>
                    </div>
                </div>
                <?php
            }
        }

        public static function get_cookie()
        {
            $products = array();
            if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
                $products = explode( ',', $_POST['products'] );
            } else {
                $cookie = 'styler_products_' . md5( 'styler' . get_current_user_id() );

                if ( isset( $_COOKIE[ $cookie ] ) && ! empty( $_COOKIE[ $cookie ] ) ) {
                    if ( is_user_logged_in() ) {
                        update_user_meta( get_current_user_id(), 'styler_products', $_COOKIE[ $cookie ] );
                    }

                    $products = explode( ',', $_COOKIE[ $cookie ] );
                }
            }
            return $products;
        }

        public static function get_products_ids()
        {
            $ids       = array();
            $products  = self::get_cookie();

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {

                foreach ( $products as $product ) {
                    $ids[] = $product;
                }
                return $ids;
            }
        }

        public static function get_compare()
        {
            // get items
            $products_data = array();
            $products      = self::get_cookie();
            $limit         = styler_settings( 'compare_max_count', 100 );
            $limit         = $limit ? $limit : 100;
            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {
                $pcount = 1;
                foreach ( $products as $p ) {
                    $product = wc_get_product( $p );

                    if ( ! $product ) {
                        continue;
                    }

                    $products_data[$p]['id']    = $product->get_id();
                    $products_data[$p]['link']  = $product->get_permalink();
                    $products_data[$p]['name']  = $product->get_name();
                    $products_data[$p]['image'] = $product->get_image( 'styler-panel', array( 'class' => 'compare-thumb' ) );
                    $products_data[$p]['price'] = $product->get_price_html();
                    $products_data[$p]['stock'] = $product->is_in_stock() ? esc_html__( 'In stock', 'styler' ) : esc_html__( 'Out of stock', 'styler' );

                    $pcount++;

                    if ( $pcount > $limit  ) {
                        break;
                    }
                }

                foreach ( $products_data as $cproduct ) {
                    $imgurl = get_the_post_thumbnail_url($cproduct['id'],'styler-panel');
                    $imgsrc = $imgurl ? $imgurl : wc_placeholder_img_src();
                    $img    = '<img width="80" height="80" src="'.$imgsrc.'" alt="'.esc_html( $cproduct['name'] ).'"/>';
                    ?>
                    <div class="styler-content-item styler-compare-item" data-id="<?php echo esc_attr( $cproduct['id'] ); ?>">
                        <div class="styler-content-item-inner">
                            <?php printf( '<a href="%s">%s</a>',esc_url( $cproduct['link'] ), $img ); ?>
                            <div class="styler-content-info">
                                <div class="styler-small-title">
                                    <a class="styler-content-link" data-id="<?php echo esc_attr( $cproduct['id'] ); ?>" href="<?php echo esc_url( $cproduct['link'] ); ?>">
                                        <span class="product-name"><?php echo esc_html( $cproduct['name'] ); ?></span>
                                        <span>
                                            <?php if ( $cproduct['price'] ) { ?>
                                                <span class="product-price styler-price"><?php printf('%s', $cproduct['price'] ); ?></span> /
                                            <?php } ?>
                                            <span class="product-stock styler-stock"> <?php echo esc_html( $cproduct['stock'] ); ?></span>
                                        </span>
                                    </a>
                                </div>
                                <?php echo do_shortcode('[add_to_cart style="" show_price="false" id="'.$cproduct['id'].'"]'); ?>
                            </div>
                            <div class="styler-content-del-icon styler-compare-del-icon" data-id="<?php echo esc_attr( $cproduct['id'] ); ?>"><?php echo styler_svg_lists( 'trash', 'styler-svg-icon mini-icon' ); ?></div>
                        </div>
                    </div>
                    <?php
                }
            }
        }

        public static function get_compare_table()
        {
            $defaults = array(
                'image'        => 0,
                'price'        => 0,
                'sku'          => 0,
                'rating'       => 0,
                'stock'        => 0,
                'desc'         => 0,
                'content'      => 0,
                'weight'       => 0,
                'dimensions'   => 0,
                'additional'   => 0,
                'availability' => 0,
                'cart'         => 0
            );
            // get items
            $products_data = array();
            $products      = self::get_cookie();
            $url           = !is_shop() ? apply_filters( 'woocommerce_return_to_shop_redirect', wc_get_page_permalink( 'shop' ) ) : '#0';
            $limit         = styler_settings( 'compare_max_count', 5 );
            $limit         = $limit ? $limit : 5;
            $table         = styler_settings( 'compare_table', null );
            $table         = !empty($table) ? $table : $defaults;

            if ( is_array( $products ) && ( count( $products ) > 0 ) ) {
                $pcount = 1;
                foreach ( $products as $p ) {
                    $product = wc_get_product( $p );

                    if ( ! $product ) {
                        continue;
                    }
                    $products_data[$p]['id']           = $product->get_id();
                    $products_data[$p]['link']         = $product->get_permalink();
                    $products_data[$p]['name']         = $product->get_name();
                    $products_data[$p]['image']        = $product->get_image( 'woocommerce_thumbnail', array( 'class' => 'compare-thumb' ) );
                    $products_data[$p]['price']        = $product->get_price_html();
                    $products_data[$p]['content']      = $product->get_description();
                    $products_data[$p]['desc']         = $product->get_short_description();
                    $products_data[$p]['sku']          = $product->get_sku();
                    $products_data[$p]['weight']       = wc_format_weight( $product->get_weight() );
                    $products_data[$p]['dimensions']   = wc_format_dimensions( $product->get_dimensions( false ) );
                    $products_data[$p]['rating']       = wc_get_rating_html( $product->get_average_rating() );
                    $products_data[$p]['availability'] = $product->get_availability();
                    $products_data[$p]['additional']   = $product->get_attributes();
                    $products_data[$p]['stock']        = $product->is_in_stock() ? esc_html__( 'In stock', 'styler' ) : esc_html__( 'Out of stock', 'styler' );

                    $pcount++;

                    if ( $pcount > $limit  ) {
                        break;
                    }
                }

                $placeholder     = wc_placeholder_img('woocommerce_thumbnail');
                $placeholder_src = wc_placeholder_img_src('woocommerce_thumbnail');
                $count           = count( $products );
                $placeholder_td  = '';

                if ( $count < 3 && $count == 2 ) {
                    $placeholder_td = '</td><td class="td-placeholder"></td>';
                } elseif ( $count < 3 && $count == 1 ) {
                    $placeholder_td = '<td class="td-placeholder"></td><td class="td-placeholder"></td>';
                }
                echo '<div class="styler-compare-items container" data-count="'.count( $products ).'" data-placeholder="'.$placeholder_src.'">';
                    echo '<table>';
                        echo '<thead>';
                            echo '<tr>';
                                echo '<th>Name</th>';
                                foreach ( $products_data as $cp ) {
                                    $icon = '<div class="styler-compare-del-icon" data-id="'.$cp['id'].'">'.styler_svg_lists( 'trash', 'styler-svg-icon mini-icon' ).'</div>';
                                    $cart = '1' == $table['cart'] ? do_shortcode('[add_to_cart style="" show_price="false" id="'.$cp['id'].'"]') : '';
                                    echo '<th data-id="'.$cp['id'].'"><a class="name" href="'.esc_url( $cp['link'] ).'">'.$cp['name'].'</a>'.$cart.$icon.'</th>';
                                }
                                if ( $count < 3 && $count == 2 ) {
                                    echo '<th class="th-placeholder"></th>';
                                } elseif ( $count < 3 && $count == 1 ) {
                                    echo '<th class="th-placeholder"></th><th class="th-placeholder"></th>';
                                }
                            echo '</tr>';
                        echo '</thead>';
                        echo '<tbody>';
                            if ( '1' == $table['image'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Image', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td class="image" data-id="'.$cp['id'].'"><a href="'.esc_url( $cp['link'] ).'">'.$cp['image'].'</a></td>';
                                    }
                                    if ( $count < 3 && $count == 2 ) {
                                        echo '<td class="placeholder-image">'.$placeholder.'</td>';
                                    } elseif ( $count < 3 && $count == 1 ) {
                                        echo '<td class="placeholder-image">'.$placeholder.'</td><td class="placeholder-image">'.$placeholder.'</td>';
                                    }
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['price'] ) && '1' == $table['price'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Price', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['price'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['sku'] ) && '1' == $table['sku'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'SKU', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['sku'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['stock'] ) && '1' == $table['stock'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Stock Status', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['stock'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['rating'] ) && '1' == $table['rating'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Rating', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['rating'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['desc'] ) && '1' == $table['desc'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Description', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['desc'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['content'] ) && '1' == $table['content'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Content', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['content'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['weight'] ) && '1' == $table['weight'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Weight', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['weight'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['dimensions'] ) && '1' == $table['dimensions'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Dimensions', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['dimensions'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['availability'] ) && '1' == $table['availability'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Availability', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        echo '<td data-id="'.$cp['id'].'">'.$cp['availability']['availability'].'</td>';
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                            if ( !empty( $products_data[$p]['additional'] ) && '1' == $table['additional'] ) {
                                echo '<tr>';
                                    echo '<td>'.esc_html__( 'Additional information', 'styler' ).'</td>';
                                    foreach ( $products_data as $cp ) {
                                        ob_start();
                                        wc_display_product_attributes( $product );
                                        $additional = ob_get_clean();
                                        printf( '<td data-id="'.$cp['id'].'">%s</td>', $additional );
                                    }
                                    echo $placeholder_td;
                                echo '</tr>';
                            }
                        echo '</tbody>';
                    echo '</table>';
                    echo '<div class="styler-empty-content">';
                        echo styler_svg_lists( 'compare', 'styler-big-svg-icon' );
                        echo '<div class="styler-small-title">'.esc_html__( 'No product is added to the compare list!', 'styler' ).'</div>';
                        echo '<a class="styler-btn-small mt-10" href="'.esc_url( $url ).'">'.esc_html__( 'Start Shopping', 'styler' ).'</a>';
                    echo '</div>';
                echo '</div>';
            } else {
                echo '<div class="styler-compare-items styler-empty-content no-product">';
                    echo styler_svg_lists( 'compare', 'styler-big-svg-icon' );
                    echo '<div class="styler-small-title">'.esc_html__( 'No product is added to the compare list!', 'styler' ).'</div>';
                    echo '<a class="styler-btn-small mt-10" href="'.esc_url( $url ).'">'.esc_html__( 'Start Shopping', 'styler' ).'</a>';
                echo '</div>';
            }
        }

        public static function load_compare_table()
        {
            if ( 'popup' == styler_settings( 'compare_btn_action', 'panel' ) || 'popup' == styler_settings( 'header_compare_btn_action', 'panel' ) ) {
                echo self::get_compare_table();
            }
            wp_die();
        }

        public static function get_count()
        {
            $products = array();

            if ( isset( $_POST['products'] ) && ( $_POST['products'] !== '' ) ) {
                $products = explode( ',', $_POST['products'] );
            } else {
                $cookie = 'styler_products_' . md5( 'styler' . get_current_user_id() );
                if ( isset( $_COOKIE[ $cookie ] ) && ! empty( $_COOKIE[ $cookie ] ) ) {
                    $products = explode( ',', $_COOKIE[ $cookie ] );
                }
            }

            return count( $products );
        }

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }
    }
    Styler_Compare::get_instance();
}
