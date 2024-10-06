<?php

/**
 * Custom template parts for this theme.
 *
 * Eventually, some of the functionality here could be replaced by core features.
 *
 * @package styler
*/


/*************************************************
##  LOGO
*************************************************/
if ( ! function_exists( 'styler_logo' ) ) {
    function styler_logo( $mobile=false )
    {
        $logo          = styler_settings( 'logo_type', 'sitename' );
        $mainlogo      = '' != styler_settings( 'img_logo' ) ? styler_settings( 'img_logo' )[ 'url' ] : '';
        $stickylogo    = '' != styler_settings( 'sticky_logo' ) ? styler_settings( 'sticky_logo' )[ 'url' ] : '';
        $mobilelogo    = $mobile == true && '' != styler_settings( 'mobile_logo' ) ? styler_settings( 'mobile_logo' )[ 'url' ] : '';
        $hasstickylogo = '' != $stickylogo ? ' has-sticky-logo': '';
        $type          = true == $mobile ? 'nav-logo logo-type-'.$logo : 'logo logo-type-'.$logo;

        if ( is_page() ) {
            $page_logo  = styler_page_settings( 'styler_page_header_logo' );
            $mainlogo   = !empty( $page_logo['url'] ) ? $page_logo['url'] : $mainlogo;
            $logo       = !empty( $page_logo['url'] ) ? 'img' : $logo;
            $page_slogo = styler_page_settings( 'styler_page_header_sticky_logo' );
            $stickylogo = !empty( $page_slogo['url'] ) ? $page_slogo['url'] : $stickylogo;
            $hasstickylogo = !empty( $page_slogo['url'] ) ? ' has-sticky-logo': $hasstickylogo;
        }

        if ( '0' != styler_settings( 'logo_visibility', '1' ) ) {
            ?>
            <div class="<?php echo esc_attr( $type ); ?>">
                <a href="<?php echo esc_url( home_url( '/' ) ); ?>"  aria-label="logo image" class="nt-logo header-logo logo-type-<?php echo esc_attr( $logo.$hasstickylogo ); ?>">

                    <?php if ( 'img' == $logo && '' != $mainlogo ) { ?>

                        <?php if ( true == $mobile && $mobilelogo ) { ?>

                            <img class="mobile-menu-logo" src="<?php echo esc_url( $mobilelogo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />

                        <?php } else { ?>

                            <?php if ( '' != $mainlogo ) { ?>

                                <img class="main-logo" src="<?php echo esc_url( $mainlogo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />

                                <?php if ( '' != $stickylogo ) { ?>

                                    <img class="main-logo sticky-logo" src="<?php echo esc_url( $stickylogo ); ?>" alt="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>" />

                                <?php } ?>

                            <?php } ?>

                        <?php } ?>

                    <?php } elseif ( 'sitename' == $logo ) { ?>

                        <span class="header-text-logo"><?php bloginfo( 'name' ); ?></span>

                    <?php } elseif ( 'customtext' == $logo ) { ?>

                        <span class="header-text-logo"><?php echo styler_settings( 'text_logo' ); ?></span>

                    <?php } else { ?>

                        <span class="header-text-logo"> <?php bloginfo( 'name' ); ?> </span>

                    <?php } ?>
                </a>
            </div>
            <?php
        }
    }
}


if ( ! class_exists( 'Styler_Header' ) ) {
    class Styler_Header
    {
        private static $instance = null;
        public static $location  = 'header_menu';
        public static $menu      = '';

        public static function get_instance() {
            if ( is_null( self::$instance ) ) {
                self::$instance = new self;
            }
            return self::$instance;
        }

        function __construct()
        {
            add_action( 'styler_theme_header_layouts', [ $this, 'header_layouts' ] );
            add_action( 'styler_theme_mobile_header', [ $this, 'mobile_header' ] );
            add_action( 'styler_theme_header_mobile_sidebar', [ $this, 'mobile_sidebar_menu' ] );
            add_action( 'styler_theme_header_elementor', [ $this, 'header_elementor' ] );
            add_action( 'styler_header_action', [ $this, 'main_header' ] );
            add_action( 'styler_theme_header_before', [ $this, 'header_before' ] );
            add_action( 'styler_theme_header_after', [ $this, 'header_after' ] );
            add_action( 'styler_mobile_menu_bottom', [ $this, 'sidebar_menu_copyright' ] );
            add_action( 'styler_after_mobile_menu', [ $this, 'sidebar_menu_lang' ] );
            add_action( 'styler_before_wp_footer', [ $this, 'my_account_form_popup_template' ] );
        }

        public static function check_layout_manager( $layouts, $item )
        {
            if ( is_array( $layouts ) ) {
                unset( $layouts['show']['placebo'] );
                return isset( $layouts['show'][$item] ) ? true : false;
            }
            return false;
        }

        public static function get_nav_menu( $location, $menu )
        {
            self::$location = $location;
            self::$menu = $menu;
            return wp_nav_menu(
                array(
                    'menu' => self::$menu,
                    'theme_location' => self::$location,
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => '',
                    'menu_id' => '',
                    'items_wrap' => '%3$s',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'depth' => 4,
                    'echo' => true,
                    'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                    'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                )
            );
        }

        public static function get_sidebar_nav_menu()
        {
            $sidemenu_location = has_nav_menu( 'sidebar_menu' ) ? 'sidebar_menu' : 'header_menu';
            return wp_nav_menu(
                array(
                    'menu' => '',
                    'theme_location' => $sidemenu_location,
                    'container' => '',
                    'container_class' => '',
                    'container_id' => '',
                    'menu_class' => '',
                    'menu_id' => '',
                    'items_wrap' => '%3$s',
                    'before' => '',
                    'after' => '',
                    'link_before' => '',
                    'link_after' => '',
                    'depth' => 4,
                    'echo' => true,
                    'fallback_cb' => 'Styler_Sliding_Navwalker::fallback',
                    'walker' => new \Styler_Sliding_Navwalker()
                )
            );
        }

        public static function header_menu()
        {
            ?>
            <div class="styler-header-top-menu-area">
                <ul class="navigation primary-menu">
                    <?php echo self::get_nav_menu( self::$location, self::$menu ); ?>
                </ul>
            </div>
            <?php
        }

        public static function header_mini_menu()
        {
            $html = '';
            if ( has_nav_menu( 'header_mini_menu' ) ) {
                $mini_menu = wp_nav_menu(
                    array(
                        'menu' => '',
                        'theme_location' => 'header_mini_menu',
                        'container' => '',
                        'container_class' => '',
                        'container_id' => '',
                        'menu_class' => '',
                        'menu_id' => '',
                        'items_wrap' => '%3$s',
                        'before' => '',
                        'after' => '',
                        'link_before' => '',
                        'link_after' => '',
                        'depth' => 1,
                        'echo' => false,
                        'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                        'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                    )
                );
                $html .= '<div class="styler-header-top-mini-menu-area">';
                    $html .= '<ul class="navigation-mini secondary-menu">'.$mini_menu.'</ul>';
                $html .= '</div>';
            }
            echo apply_filters( 'styler_secondary_mini_menu', $html );
        }

        public static function header_double_menu()
        {
            ?>
            <div class="styler-header-top-double-menu">
                <?php
                if ( has_nav_menu( 'header_menu' ) ) {
                    self::header_menu();
                }
                if ( has_nav_menu( 'header_mini_menu' ) ) {
                    self::header_mini_menu();
                }
                ?>
            </div>
            <?php
        }

        public static function menu_center_logo()
        {
            $menu      = apply_filters('styler_menu_left', '' );
            $menu2     = apply_filters('styler_menu_right', '' );
            $location  = apply_filters('styler_menu_left', 'left_menu' );
            $location2 = apply_filters('styler_menu_right', 'rigt_menu' );
            ?>
            <div class="styler-header-top-menu-area nav-logo-center styler-flex styler-align-center styler-justify-center">
                <ul class="navigation primary-menu left-menu styler-flex-right">
                    <?php echo self::get_nav_menu( $location, $menu )?>
                </ul>
                <div class="center-logo-wrapper flex-center-items">
                    <?php styler_logo(false); ?>
                </div>
                <ul class="navigation primary-menu right-menu styler-flex-left">
                    <?php echo self::get_nav_menu( $location2, $menu2 )?>
                </ul>
            </div>
            <?php
        }

        public static function header_buttons_layouts()
        {
            $catalog_mode = styler_settings( 'woo_catalog_mode', '0' );
            $layouts      = styler_settings( 'header_buttons_layouts' );

            if ( styler_is_woocommerce() ) {
                if ( is_product() && '1' == styler_settings( 'single_shop_different_header_layouts', '0' ) ) {
                    $layouts  = styler_settings( 'single_shop_header_buttons_layouts' );
                } elseif ( is_shop() && '1' == styler_settings( 'shop_different_header_layouts', '0' ) ) {
                    $layouts  = styler_settings( 'shop_header_buttons_layouts' );
                }
            }

            $layouts = apply_filters( 'header_buttons_layouts', $layouts );
            $html = $html_out = '';
            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );
                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {
                        case 'search':
                        $html .= '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                        break;

                        case 'cart':
                        if ( styler_is_woocommerce() && '1' != $catalog_mode ) {
                            $html .= '<div class="top-action-btn" data-name="cart"><span class="styler-cart-count styler-wc-count">'.WC()->cart->get_cart_contents_count().'</span>'.styler_svg_lists( 'bag', 'styler-svg-icon' ).'</div>';
                        }
                        break;

                        case 'wishlist':
                        if ( styler_is_woocommerce() && '1' != $catalog_mode && '1' == styler_settings( 'wishlist_visibility', '1' ) ) {
                            $wbtn_action = styler_settings( 'header_wishlist_btn_action', 'panel' );
                            $wpage_id    = styler_settings('wishlist_page_id');
                            $wpage_link  = $wpage_id ? get_page_link($wpage_id) : wc_get_page_permalink( 'shop' );
                            $wicon       = styler_svg_lists( 'love', 'styler-svg-icon' );
                            if ( 'page' == $wbtn_action ) {
                                $html .= '<div class="top-action-btn no-panel"><a class="wishlist-link" href="'.esc_url( $wpage_link ).'"><span class="styler-wishlist-count styler-wc-count"></span>'.$wicon.'</a></div>';
                            } else {
                                $html .= '<div class="top-action-btn" data-name="wishlist"><span class="styler-wishlist-count styler-wc-count"></span>'.$wicon.'</div>';
                            }
                        }
                        break;

                        case 'compare':
                        if ( styler_is_woocommerce() && '1' != $catalog_mode && '1' == styler_settings( 'compare_visibility', '1' ) ) {
                            $btn_action = styler_settings( 'header_compare_btn_action', 'panel' );
                            $page_id    = styler_settings('compare_page_id');
                            $page_link  = $page_id ? get_page_link($page_id) : wc_get_page_permalink( 'shop' );
                            $icon       = styler_svg_lists( 'compare', 'styler-svg-icon' );
                            if ( 'page' == $btn_action ) {
                                $html .= '<div class="top-action-btn no-panel"><a class="compare-page-link" href="'.esc_url( $page_link ).'"><span class="styler-compare-count styler-wc-count"></span>'.$icon.'</a></div>';
                            } elseif ( 'popup' == $btn_action ) {
                                $html .= '<div class="top-action-btn open-compare-popup no-panel"><span class="styler-compare-count styler-wc-count"></span>'.$icon.'</div>';
                            } else {
                                $html .= '<div class="top-action-btn" data-name="compare"><span class="styler-compare-count styler-wc-count"></span>'.$icon.'</div>';
                            }
                        }
                        break;

                        case 'account':
                        if ( styler_is_woocommerce() ) {
                            $action_type  = styler_settings( 'header_myaccount_action_type', 'panel' );
                            $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                            $account_url  = apply_filters('styler_myaccount_page_url', $account_url );
                            $account_link = '<a class="account-page-link" href="'.esc_url( $account_url ).'">';
                            $account_data = '';
                            $is_panel     = '';

                            if ( !is_account_page() ) {

                                if ( 'popup' == $action_type ) {
                                    $account_link  = '<a class="styler-open-popup" href="#styler-account-popup">';
                                    $account_data  = '';
                                    $is_panel      = ' no-panel';
                                } elseif ( 'page' == $action_type ) {
                                    $account_link  = '<a class="account-page-link" href="'.esc_url( $account_url ).'">';
                                    $account_data = '';
                                    $is_panel      = ' no-panel';
                                } else {
                                    $account_link = '<a class="account-page-link" href="#0">';
                                    $account_data = ' data-account-action="account"';
                                }
                            }

                            $html .= '<div class="top-action-btn header-top-account'.$is_panel.'"'.$account_data.'>'.$account_link.styler_svg_lists( 'user-1', 'styler-svg-icon' ).'</a></div>';
                        }
                        break;
                    }
                }
                $html_out = '<div class="header-top-buttons">'.$html.'</div>';
            }
            echo apply_filters('styler_header_buttons_html', $html_out );
        }

        public static function mobile_buttons_layouts()
        {
            $layouts = styler_settings( 'mobile_header_buttons_layouts' );
            $layouts = apply_filters( 'styler_mobile_header_buttons_layouts', $layouts );

            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );

                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {
                        case 'search':
                        echo '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                        break;

                        case 'cart':
                        if ( styler_is_woocommerce() && '1' != styler_settings( 'woo_catalog_mode', '0' ) ) {
                            echo '<div class="top-action-btn" data-name="cart"><span class="styler-cart-count styler-wc-count">'.WC()->cart->get_cart_contents_count().'</span>'.styler_svg_lists( 'bag', 'styler-svg-icon' ).'</div>';
                        }
                        break;

                        case 'account':
                        if ( styler_is_woocommerce() && !is_account_page() ) {
                            $action_type  = styler_settings( 'header_myaccount_action_type', 'panel' );
                            $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                            $account_url  = apply_filters('styler_myaccount_page_url', $account_url );
                            $account_link = '<a class="account-page-link" href="'.esc_url( $account_url ).'">';
                            $account_data = '';
                            $is_panel     = '';

                            if ( 'popup' == $action_type ) {
                                $is_panel      = ' no-panel';
                                $account_link  = '<a class="styler-open-popup" href="#styler-account-popup">';
                            } elseif ( 'page' == $action_type ) {
                                $is_panel      = ' no-panel';
                                $account_link  = '<a class="account-page-link" href="'.esc_url( $account_url ).'">';
                            } else {
                                $account_link = '<a class="account-page-link" href="#0">';
                                $account_data = ' data-account-action="account"';
                            }

                            echo '<div class="top-action-btn'.$is_panel.'"'.$account_data.'>'.$account_link.styler_svg_lists( 'user-1', 'styler-svg-icon' ).'</a></div>';
                        }
                        break;
                    }
                }
            }
        }

        public static function sidebar_buttons_layouts()
        {
            $catalog_mode = styler_settings( 'woo_catalog_mode', '0' );
            $layouts      = styler_settings( 'sidebar_menu_buttons_layouts' );
            $layouts      = apply_filters( 'styler_sidebar_buttons_layouts', $layouts );
            $cat_count    = self::get_products_categories_count();

            if ( $layouts ) {
                unset( $layouts['show']['placebo'] );

                if ( '1' == styler_settings( 'sidebar_header_menubar_visibility' ) ) {
                    echo '<div class="top-action-btn" data-name="smenu">'.styler_svg_lists( 'bars', 'styler-svg-icon' ).'</div>';
                }
                foreach ( $layouts['show'] as $key => $value ) {

                    switch ( $key ) {
                        case 'search':
                        if ( styler_is_woocommerce() ) {
                            echo '<div class="top-action-btn" data-name="search-cats"><span class="styler-category-count styler-wc-count">'.$cat_count.'</span>'.styler_svg_lists( 'paper-search', 'styler-svg-icon' ).'</div>';
                        }
                        break;

                        case 'contact':
                        echo '<div class="top-action-btn" data-name="contact">'.styler_svg_lists( 'contact-form', 'styler-svg-icon' ).'</div>';
                        break;

                        case 'cart':
                        if ( styler_is_woocommerce() && '1' != $catalog_mode ) {
                            echo '<div class="top-action-btn" data-name="cart"><span class="styler-cart-count styler-wc-count">'.WC()->cart->get_cart_contents_count().'</span>'.styler_svg_lists( 'bag', 'styler-svg-icon' ).'</div>';
                        }
                        break;

                        case 'wishlist':
                        if ( styler_is_woocommerce() && '1' != $catalog_mode && '1' == styler_settings( 'wishlist_visibility', '1' ) ) {
                            $wbtn_action = styler_settings( 'header_wishlist_btn_action', 'panel' );
                            $wpage_id    = styler_settings('wishlist_page_id');
                            $wpage_link  = $wpage_id ? get_page_link($wpage_id) : wc_get_page_permalink( 'shop' );
                            $wicon       = styler_svg_lists( 'love', 'styler-svg-icon' );
                            if ( 'page' == $wbtn_action ) {
                                echo '<div class="top-action-btn no-panel"><a class="wishlist-link" href="'.esc_url( $wpage_link ).'"><span class="styler-wishlist-count styler-wc-count"></span>'.$wicon.'</a></div>';
                            } else {
                                echo '<div class="top-action-btn" data-name="wishlist"><span class="styler-wishlist-count styler-wc-count"></span>'.$wicon.'</div>';
                            }
                        }
                        break;

                        case 'compare':
                        if ( styler_is_woocommerce() && '1' != $catalog_mode && '1' == styler_settings( 'compare_visibility', '1' ) ) {
                            $btn_action = styler_settings( 'header_compare_btn_action', 'panel' );
                            $page_id    = styler_settings('compare_page_id');
                            $page_link  = $page_id ? get_page_link($page_id) : wc_get_page_permalink( 'shop' );
                            $icon       = styler_svg_lists( 'compare', 'styler-svg-icon' );
                            if ( 'page' == $btn_action ) {
                                echo '<div class="top-action-btn no-panel"><a class="compare-page-link" href="'.esc_url( $page_link ).'"><span class="styler-compare-count styler-wc-count"></span>'.$icon.'</a></div>';
                            } elseif ( 'popup' == $btn_action ) {
                                echo '<div class="top-action-btn open-compare-popup no-panel"><span class="styler-compare-count styler-wc-count"></span>'.$icon.'</div>';
                            } else {
                                echo '<div class="top-action-btn" data-name="compare"><span class="styler-compare-count styler-wc-count"></span>'.$icon.'</div>';
                            }
                        }
                        break;

                        case 'account':
                        if ( styler_is_woocommerce() && !is_account_page() ) {
                            $action_type  = styler_settings( 'header_myaccount_action_type', 'panel' );
                            $account_url  = class_exists('WooCommerce') ? wc_get_page_permalink( 'myaccount' ) : '';
                            $account_url  = apply_filters('styler_myaccount_page_url', $account_url );
                            $link_open    = '<a class="account-page-link" href="'.esc_url( $account_url ).'">';
                            $link_close   = '</a>';
                            $account_data = '';
                            $is_panel     = '';

                            if ( 'popup' == $action_type ) {
                                $is_panel   = ' no-panel';
                                $link_open  = '<a class="styler-open-popup" href="#styler-account-popup">';
                            } elseif ( 'page' == $action_type ) {
                                $is_panel      = ' no-panel';
                                $link_open  = '<a class="account-page-link" href="'.esc_url( $account_url ).'">';
                            } else {
                                $link_open = '';
                                $link_close = '';
                                $account_data = ' data-name="account"';
                            }

                            echo '<div class="top-action-btn'.$is_panel.'"'.$account_data.'>'.$link_open.styler_svg_lists( 'user-1', 'styler-svg-icon' ).$link_close .'</div>';
                        }
                        break;

                        case 'socials':
                        echo '<div class="top-action-btn share" data-name="share">'.styler_svg_lists( 'share', 'styler-svg-icon' ).'</div>';
                        break;
                    }
                }
            }
        }

        public static function header_layouts()
        {
            $layouts = styler_settings( 'header_layouts' );

            if ( styler_is_woocommerce() ) {
                if ( is_product() && '1' == styler_settings( 'single_shop_different_header_layouts', '0' ) ) {
                    $layouts  = styler_settings( 'single_shop_header_layouts' );
                } elseif ( is_shop() && '1' == styler_settings( 'shop_different_header_layouts', '0' ) ) {
                    $layouts  = styler_settings( 'shop_header_layouts' );
                }
            }

            $breakpoint  = styler_settings( 'mobile_header_breakpoint', '1280' );
            $custom_html = styler_settings( 'header_custom_html', '' );
            $layouts     = apply_filters( 'styler_header_layouts', $layouts );
            $bg_type     = apply_filters( 'styler_header_bg_type', styler_settings( 'header_bg_type', 'default' ) );
            $header_w    = apply_filters( 'styler_header_width', styler_settings( 'header_width', 'default' ) );
            $menu_title  = styler_settings( 'menu_title', '0' ) ? styler_settings( 'menu_title', '0' ) : esc_html__('Menu', 'styler');
            $menu_title  = '1' == styler_settings( 'menu_title_visibility', '0' ) ? '<span class="menu-title">'.$menu_title.'</span>' : '';

            echo '<header class="styler-header-default header-width-'.$header_w.'" data-breakpoint="'.$breakpoint.'">';
                echo '<div class="container-xl styler-container-xl">';
                    echo '<div class="styler-header-content">';
                        if ( $layouts ) {

                            unset( $layouts['left']['placebo'] );
                            unset( $layouts['center']['placebo'] );
                            unset( $layouts['right']['placebo'] );

                            if ( !empty( $layouts['left'] ) ) {

                                echo '<div class="styler-header-top-left header-top-side">';
                                    echo '<div class="styler-header-default-inner">';
                                        foreach ( $layouts['left'] as $key => $value ) {

                                            switch( $key ) {
                                                case 'logo': styler_logo();
                                                break;

                                                case 'search': echo '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                                                break;

                                                case 'sidemenu': echo '<div class="mobile-toggle">'.styler_svg_lists( 'bars', 'styler-svg-icon' ).$menu_title.'</div>';
                                                break;

                                                case 'menu': self::header_menu();
                                                break;

                                                case 'mini-menu': self::header_mini_menu();
                                                break;

                                                case 'double-menu': self::header_double_menu();
                                                break;

                                                case 'custom-html': echo styler_settings( 'header_custom_html', '' );
                                                break;

                                                case 'buttons': self::header_buttons_layouts();
                                                break;
                                            }
                                        }
                                        do_action( 'styler_theme_header_left' );
                                    echo '</div>';
                                echo '</div>';
                            }
                            if ( !empty( $layouts['center'] ) ) {
                                echo '<div class="styler-header-top-center">';
                                    echo '<div class="styler-header-default-inner">';
                                        foreach ( $layouts['center'] as $key => $value ) {

                                            switch( $key ) {
                                                case 'logo': styler_logo();
                                                break;

                                                case 'search': echo '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                                                break;

                                                case 'sidemenu': echo '<div class="mobile-toggle">'.styler_svg_lists( 'bars', 'styler-svg-icon' ).$menu_title.'</div>';
                                                break;

                                                case 'menu': self::header_menu();
                                                break;

                                                case 'mini-menu': self::header_mini_menu();
                                                break;

                                                case 'double-menu': self::header_double_menu();
                                                break;

                                                case 'custom-html': echo styler_settings( 'header_custom_html', '' );
                                                break;

                                                case 'center-logo': self::menu_center_logo();
                                                break;

                                                case 'buttons': self::header_buttons_layouts();
                                                break;
                                            }
                                        }
                                        do_action( 'styler_theme_header_center' );
                                    echo '</div>';
                                echo '</div>';
                            }
                            if ( !empty( $layouts['right'] ) ) {
                                echo '<div class="styler-header-top-right header-top-side">';
                                    echo '<div class="styler-header-default-inner">';
                                        foreach ( $layouts['right'] as $key => $value ) {

                                            switch( $key ) {
                                                case 'logo': styler_logo();
                                                break;

                                                case 'search': echo '<div class="top-action-btn" data-name="search-popup">'.styler_svg_lists( 'search', 'styler-svg-icon' ).'</div>';
                                                break;

                                                case 'sidemenu': echo '<div class="mobile-toggle">'.styler_svg_lists( 'bars', 'styler-svg-icon' ).$menu_title.'</div>';
                                                break;

                                                case 'menu': self::header_menu();
                                                break;

                                                case 'mini-menu': self::header_mini_menu();
                                                break;

                                                case 'double-menu': self::header_double_menu();
                                                break;

                                                case 'custom-html': echo styler_settings( 'header_custom_html', '' );
                                                break;

                                                case 'buttons': self::header_buttons_layouts();
                                                break;
                                            }
                                        }
                                        do_action( 'styler_theme_header_right' );
                                    echo '</div>';
                                echo '</div>';
                            }
                        }
                    echo '</div>';

                    self::header_bottom_bar();

                echo '</div>';
            echo '</header>';
        }

        public static function header_bottom_bar()
        {
            if ( '1' == styler_settings( 'header_bottom_area_visibility', '0' ) ) {
                $template_type  = styler_settings( 'header_bottom_area_template_type', 'filters' );
                $header_filter  = styler_settings( 'header_bottom_area_display_type', 'show-on-scroll' );
                $header_filter .= ' shop-layout-'.styler_settings( 'shop_layout', 'left-sidebar' );
                $header_filter .= ' fixed-sidebar-'.styler_settings( 'shop_hidden_sidebar_position', 'left' );

                if ( styler_is_woocommerce() && ( is_shop() || is_product_category() || is_product_tag() ) && 'filters' == $template_type ) {
                    echo '<div class="styler-header-bottom-bar '.$header_filter.'">';
                        shop_loop_filters_layouts();
                    echo '</div>';
                } else {
                    echo '<div class="styler-header-bottom-bar styler-elementor-template '.styler_settings( 'header_bottom_area_display_type', 'show-on-scroll' ).'">';
                        echo styler_print_elementor_templates( 'header_bottom_bar_template' );
                    echo '</div>';
                }
            }
        }

        public static function mobile_header()
        {
            $layouts = styler_settings( 'mobile_header_layouts' );
            $layouts = apply_filters( 'styler_mobile_header_layouts', $layouts );
            $bg_type = apply_filters( 'styler_sidebar_menu_bg_type', styler_settings( 'sidebar_menu_bg_type', 'default' ) );
            $breakpoint  = styler_settings( 'mobile_header_breakpoint', '1280' );

            if ( $layouts ) {
                if ( !empty( $layouts ) && isset( $layouts['show'] ) ) {
                    unset( $layouts['show']['placebo'] );
                }
                echo '<div class="styler-header-mobile-top styler-container-xl mobile-header-bg-type-'.$bg_type.'" data-breakpoint="'.$breakpoint.'">';
                    foreach ( $layouts['show'] as $key => $value ) {

                        switch ( $key ) {
                            case 'toggle':
                            echo '<div class="mobile-toggle">'.styler_svg_lists( 'bars', 'styler-svg-icon' ).'</div>';
                            break;

                            case 'logo':
                            echo '<div class="styler-header-mobile-logo">';
                                styler_logo(true);
                            echo '</div>';
                            break;

                            case 'buttons':
                            echo '<div class="styler-header-mobile-top-actions">';
                                self::mobile_buttons_layouts();
                            echo '</div>';
                            break;
                        }
                    }
                echo '</div>';
            }
        }

        public static function mobile_sidebar_menu()
        {
            $catalog_mode = styler_settings( 'woo_catalog_mode', '0' );
            $layouts = styler_settings( 'sidebar_menu_layouts' );
            $layouts = apply_filters( 'styler_mobile_header_sidebar_layouts', $layouts );
            if ( !empty( $layouts ) && isset( $layouts['show'] ) ) {
                unset( $layouts['show']['placebo'] );
            }

            $sidebar_layouts = styler_settings( 'sidebar_menu_buttons_layouts' );
            $bg_type         = apply_filters( 'styler_sidebar_menu_bg_type', styler_settings( 'sidebar_menu_bg_type', 'default' ) );

            if ( styler_is_woocommerce() ) {
                if ( is_product() && '1' == styler_settings( 'single_shop_different_header_bg_type', '0' ) ) {
                    $bg_type    = styler_settings( 'single_shop_header_bg_type' );
                    $mb_header_bg_type = get_post_meta( get_the_ID(), 'styler_product_header_type', true );
                    $bg_type    = $mb_header_bg_type != 'custom' && $mb_header_bg_type != '' ? $mb_header_bg_type : $bg_type;
                } elseif ( is_shop() && '1' == styler_settings( 'shop_different_header_bg_type', '0' ) ) {
                    $bg_type    = styler_settings( 'shop_header_bg_type' );
                }
            }

            $class     = !empty($layouts['show']) ? 'has-bar' : 'no-bar';
            $class    .= ' sidebar-header-bg-type-'.$bg_type;
            $class    .= isset($layouts['show']['buttons']) ? ' has-buttons' : ' no-buttons';
            $class    .= isset($layouts['show']['socials']) ? ' has-socials' : ' no-socials';
            $class    .= isset($layouts['show']['logo']) ? ' has-logo' : ' no-logo';
            $cf7_form  = styler_settings('sidebar_menu_cf7') ? '[contact-form-7 id="'.styler_settings('sidebar_menu_cf7').'"]' : '';
            $form      = styler_settings('sidebar_menu_custom_form') ? styler_settings('sidebar_menu_custom_form') : $cf7_form;

            ?>
            <nav class="styler-header-mobile <?php echo esc_attr( $class ); ?>">
                <div class="styler-panel-close no-bar"></div>
                <?php
                if ( !empty( $layouts['show'] ) ) {
                    echo '<div class="styler-header-mobile-sidebar">';
                        echo '<div class="styler-panel-close styler-panel-close-button"></div>';
                        echo '<div class="styler-header-mobile-sidebar-inner">';
                            foreach ( $layouts['show'] as $key => $value ) {

                                switch ( $key ) {
                                    case 'socials':
                                    echo '<div class="styler-header-mobile-sidebar-bottom" data-target-name="share">';
                                        echo '<div class="sidebar-bottom-socials">';
                                            echo styler_settings('sidebar_menu_socials');
                                        echo '</div>';
                                    echo '</div>';
                                    break;

                                    case 'logo':
                                    echo '<div class="styler-header-mobile-sidebar-logo">';
                                        styler_logo(true);
                                    echo '</div>';
                                    break;

                                    case 'buttons':
                                    echo '<div class="sidebar-top-action">';
                                        self::sidebar_buttons_layouts();
                                    echo '</div>';
                                    break;
                                }
                            }
                        echo '</div>';
                    echo '</div>';
                }
                ?>

                <div class="styler-header-mobile-content">

                    <div class="styler-header-slide-menu menu-area">

                        <?php if ( styler_is_woocommerce() && '0' != styler_settings('ajax_search_visibility', '1') ) { ?>
                            <div class="search-area-top active">
                                <?php
                                    if ( 'cats' == styler_settings('ajax_search_type', 'cats' ) ) {
                                        echo do_shortcode('[styler_wc_ajax_product_search class="popup-search-style style-inline" cats="hide"]');
                                    } else {
                                        echo styler_svg_lists( 'search', 'styler-svg-icon' );
                                        echo do_shortcode('[styler_wc_ajax_search]');
                                    }
                                ?>
                            </div>
                        <?php } ?>

                        <div class="styler-header-mobile-slide-menu">
                            <ul class="navigationn primary-menuu">
                                <?php echo self::get_sidebar_nav_menu(); ?>
                            </ul>
                        </div>

                        <?php do_action( 'styler_after_mobile_menu' ); ?>

                        <?php do_action( 'styler_mobile_menu_bottom' ); ?>
                    </div>

                    <?php if ( self::check_layout_manager( $sidebar_layouts, 'search' ) ) { ?>
                        <div class="category-area action-content" data-target-name="search-cats">
                            <?php if ( '' != styler_settings('sidebar_panel_categories_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( styler_settings('sidebar_panel_categories_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'All Products Categories', 'styler' ); ?></span>
                            <?php } ?>
                            <div class="category-area-inner styler-perfect-scrollbar">
                                <?php self::get_all_products_categories(); ?>
                            </div>
                        </div>
                    <?php } ?>

                    <?php if ( styler_is_woocommerce() && self::check_layout_manager( $sidebar_layouts, 'cart' ) && '1' != $catalog_mode ) { ?>
                        <div class="cart-area action-content" data-target-name="cart">
                            <?php if ( '' != styler_settings('sidebar_panel_cart_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( styler_settings('sidebar_panel_cart_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'Your Cart', 'styler' ); ?></span>
                            <?php } ?>
                            <?php get_template_part( 'woocommerce/minicart/minicart' ); ?>
                        </div>
                    <?php } ?>

                    <?php if ( self::check_layout_manager( $sidebar_layouts, 'contact' ) && $form ) { ?>
                        <div class="contact-area action-content" data-target-name="contact">
                            <?php if ( '' != styler_settings('sidebar_panel_contact_custom_title') ) { ?>
                                <span class="panel-top-title"><?php echo esc_html( styler_settings('sidebar_panel_contact_custom_title') ); ?></span>
                            <?php } else { ?>
                                <span class="panel-top-title"><?php esc_html_e( 'Contact Us', 'styler' ); ?></span>
                            <?php } ?>
                            <?php echo do_shortcode( $form ); ?>
                        </div>
                    <?php } ?>

                    <?php
                    if ( styler_is_woocommerce() && self::check_layout_manager( $sidebar_layouts, 'wishlist' ) || self::check_layout_manager( $sidebar_layouts, 'compare' ) && '1' != $catalog_mode ) {
                        /**
                        *
                        * Hook: styler_mobile_panel_content_after_cart.
                        *
                        * @hooked Styler_Compare::side_mobile_panel_content()
                        * @hooked Styler_Wishlist::template_mobile_header_content()
                        */
                        do_action( 'styler_mobile_panel_content_after_cart' );
                    }

                    if ( styler_is_woocommerce() && 'panel' == styler_settings( 'header_myaccount_action_type', 'panel' ) ) {
                        if ( self::check_layout_manager( $sidebar_layouts, 'account' ) ) {
                            self::my_account_form_template();
                        }
                    }
                    ?>
                </div>
            </nav>
            <?php
        }

        public static function sidebar_menu_lang()
        {
            if ( '1' == styler_settings( 'sidebar_menu_lang_visibility', '0' ) ) {

                if ( has_action( 'wpml_add_language_selector' ) ) {

                    echo '<div class="styler-sidemenu-lang-switcher">';
                        do_action('wpml_add_language_selector');
                    echo '</div>';

                } elseif ( function_exists( 'pll_the_languages' ) ) {

                    echo '<div class="styler-sidemenu-lang-switcher">';
                        pll_the_languages(
                            array(
                                'show_flags'=>1,
                                'show_names'=>1,
                                'dropdown'=>1,
                                'raw'=>0,
                                'hide_current'=>0,
                                'display_names_as'=>'name'
                            )
                        );
                    echo '</div>';

                } else {

                    if ( has_nav_menu( 'header_lang_menu' ) ) {
                        echo '';
                        $lang_menu = wp_nav_menu(
                            array(
                                'menu' => '',
                                'theme_location' => 'header_lang_menu',
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => '',
                                'menu_id' => '',
                                'items_wrap' => '%3$s',
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'depth' => 2,
                                'echo' => false,
                                'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                                'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                            )
                        );
                        echo '<div class="styler-sidemenu-lang-switcher">
                            <div class="styler-header-lang-slide-menu">
                                <ul class="styler-lang-menu">'.$lang_menu.'</ul>
                            </div>
                        </div>';
                    }
                }
            }
        }

        public static function sidebar_menu_copyright()
        {
            if ( styler_settings( 'sidebar_menu_copyright', '' ) ) {
                echo '<div class="styler-sidemenu-copyright">'.styler_settings( 'sidebar_menu_copyright', '' ).'</div>';
            }
        }

        public static function header_elementor()
        {
            $header_id = false;

            if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {

                $page_settings = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' )->get_model( get_the_ID() );
                $pageheader_id = $page_settings->get_settings( 'styler_page_header_template' );
                $header_id     = isset( $pageheader_id ) !== '' ? $pageheader_id : $header_id;
            }

            if ( $header_id ) {

                $frontend = new \Elementor\Frontend;
                printf( '<div class="styler-elementor-header header-'.$header_id.'">%1$s</div>', $frontend->get_builder_content_for_display( $header_id, false ) );

            } else {

                echo styler_print_elementor_templates( 'header_elementor_templates', 'styler-elementor-header header-'.$pageheader_id );

            }
        }

        public static function sidebar_header()
        {
            ?>
            <div class="styler-mobile-header-spacer"></div>
            <div class="styler-mobile-header">
                <?php styler_logo(); ?>
                <div class="styler-mobile-header-actions">
                    <span class="styler-mobile-search-trigger mobile-header-actions">
                        <?php echo styler_svg_lists( 'search', 'styler-svg-icon' ); ?>
                    </span>
                    <span class="styler-mobile-menu-trigger mobile-header-actions">
                        <?php echo styler_svg_lists( 'bars', 'styler-svg-icon' ); ?>
                    </span>
                </div>
            </div>

            <div class="styler-main-sidebar-header ninetheme-scrollbar">
                <div class="styler-mobile-header-top">
                    <div class="styler-mobile-header-actions">
                        <?php styler_logo(); ?>
                        <div class="styler-mobile-menu-close-trigger styler-panel-close-button"></div>
                    </div>
                    <?php if ( has_nav_menu( 'header_menu' ) ) { ?>
                        <div class="styler-main-sidebar-inner ninetheme-scrollbar">
                            <ul class="primary-menu">
                                <?php
                                echo wp_nav_menu(array(
                                    'menu' => '',
                                    'theme_location' => 'header_menu',
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => '',
                                    'menu_id' => '',
                                    'items_wrap' => '%3$s',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'echo' => true,
                                    'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                                    'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                                ));
                                ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <?php if ( has_nav_menu( 'sidebar_second_menu' ) ) { ?>
                        <div class="styler-main-sidebar-inner second-menu ninetheme-scrollbar">
                            <ul class="primary-menu">
                                <?php
                                echo wp_nav_menu(array(
                                    'menu' => '',
                                    'theme_location' => 'sidebar_second_menu',
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => '',
                                    'menu_id' => '',
                                    'items_wrap' => '%3$s',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'echo' => true,
                                    'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                                    'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                                ));
                                ?>
                            </ul>
                        </div>
                    <?php } ?>
                    <?php self::sidebar_menu_lang(); ?>
                </div>
                <div class="styler-mobile-header-bottom">

                    <?php self::header_buttons_layouts(); ?>

                    <?php if ( class_exists('WooCommerce') && '0' != styler_settings('sidebar_header_search_visibility', '1') ) { ?>
                        <div class="search-area-top active">
                            <?php
                                if ( styler_is_woocommerce() && 'cats' == styler_settings('ajax_search_type', 'cats' ) ) {
                                    echo do_shortcode('[styler_wc_ajax_product_search class="popup-search-style style-inline" cats="hide"]');
                                } else {
                                    echo styler_svg_lists( 'search', 'styler-svg-icon' );
                                    echo do_shortcode('[styler_wc_ajax_search]');
                                }
                            ?>
                        </div>
                    <?php } ?>
                </div>
            </div>

            <nav class="styler-header-mobile after-sidebar-header">
                <div class="styler-panel-close no-bar"></div>

                <div class="styler-header-mobile-content">
                    <div class="category-area action-content" data-target-name="search-cats">
                        <?php if ( '' != styler_settings('sidebar_panel_categories_custom_title') ) { ?>
                            <span class="panel-top-title"><?php echo esc_html( styler_settings('sidebar_panel_categories_custom_title') ); ?></span>
                        <?php } else { ?>
                            <span class="panel-top-title"><?php esc_html_e( 'All Products Categories', 'styler' ); ?></span>
                        <?php } ?>
                        <div class="category-area-inner styler-perfect-scrollbar">
                            <?php self::get_all_products_categories(); ?>
                        </div>
                    </div>

                    <?php self::my_account_form_template(); ?>
                </div>
            </nav>

            <?php
        }

        public static function main_header()
        {

            if ( '0' == styler_settings( 'header_visibility', '1' ) ) {
                return;
            }

            if ( ! class_exists( 'Redux' ) || false == styler_settings( 'header_layouts' ) ) {
                self::header_default();
                return;
            }

            $header_template = styler_settings( 'header_template', 'default' );

            if ( 'elementor' == $header_template ) {

                /**
                * HEADER ELEMENTOR TEMPLATES.
                * Hook: styler_theme_header_elementor.
                *
                * @hooked header_elementor
                */
                do_action( 'styler_theme_header_elementor' );

            } elseif ( 'sidebar' == $header_template ) {


                if ( styler_is_woocommerce() && is_product() && '1' == styler_settings( 'single_shop_different_header_layouts', '0' ) ) {

                    self::header_custom_layouts();

                } elseif ( styler_is_woocommerce() && is_shop() && '1' == styler_settings( 'shop_different_header_layouts', '0' ) ) {

                    self::header_custom_layouts();

                } else {

                    self::sidebar_header();
                }

            } else {

                self::header_custom_layouts();

            }
        }

        public static function header_custom_layouts()
        {
            do_action( 'styler_theme_header_before' );

            /**
            * HEADER TOP
            * Hook: styler_theme_header_layouts.
            *
            * @hooked header_layouts
            */
            do_action( 'styler_theme_header_layouts' );

            do_action( 'styler_theme_header_after' );

            /**
            * HEADER MOBILE
            * Hook: styler_theme_mobile_header.
            *
            * @hooked mobile_header
            */
            do_action( 'styler_theme_mobile_header' );

            /**
            * HEADER SIDEBAR MENU
            * Hook: styler_theme_header_mobile_sidebar.
            *
            * @hooked mobile_sidebar_menu
            */
            do_action( 'styler_theme_header_mobile_sidebar' );

        }

        public static function get_all_products_categories()
        {
            if ( !class_exists( 'WooCommerce' )  ) {
                return;
            }

            $product_categories = get_terms( 'product_cat', array(
                'orderby'    => 'name',
                'order'      => 'asc',
                'hide_empty' => '1' == styler_settings( 'header_panel_cats_hide_empty', '1' ) ? true : false,
                'parent'     => '1' == styler_settings( 'header_panel_only_cats_parents', '0' ) ? 0 : ''
            ));
            $img      = styler_settings( 'header_panel_cats_img_visibility', '1' );
            $img_none = '0' == $img ? ' img-none' : '';
            $count    = styler_settings( 'header_panel_cats_count_visibility', '1' );
            $column   = styler_settings( 'header_panel_cats_column', '3' );
            $imgsize  = styler_settings( 'header_panel_cats_imgsize', 'thumbnail' );

            if ( !empty( $product_categories ) ) {
                ?>
                <div class="row row-cols-<?php echo esc_attr( $column.$img_none ); ?>">
                    <?php
                    foreach ( $product_categories as $key => $category ) {
                        $thumbnail_id = get_term_meta( $category->term_id, 'thumbnail_id', true );
                        ?>
                        <div class="col">
                            <div class="product-category">
                                <a href="<?php echo esc_url( get_term_link( $category ) ); ?>">
                                    <?php
                                    if ( '1' == $img ) {
                                        if ( $thumbnail_id ) {
                                            echo wp_get_attachment_image($thumbnail_id, $imgsize );
                                        } else {
                                            echo wc_placeholder_img();
                                        }
                                    }
                                    ?>
                                    <?php if ( '1' == $count ) { ?>
                                        <span class="cat-count"><?php echo esc_html( $category->count ); ?></span>
                                    <?php } ?>
                                    <span class="category-title"><?php echo esc_html( $category->name ); ?></span>
                                </a>
                            </div>
                        </div>
                        <?php
                    }
                    ?>
                </div>
                <?php
            }
        }

        public static function get_products_categories_count()
        {
            if ( !class_exists( 'WooCommerce' )  ) {
                return;
            }

            $product_categories = get_terms( 'product_cat', array(
                'orderby'    => 'name',
                'order'      => 'asc',
                'hide_empty' => true,
            ));

            if ( !empty( $product_categories ) ) {
                return count( $product_categories );
            }
        }

        public static function print_account_register_form()
        {
            if ( !class_exists( 'WooCommerce' ) ) {
                return;
            }
            if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) {
                ?>
                <form method="post" class="woocommerce-form woocommerce-form-register register" <?php do_action( 'woocommerce_register_form_tag' ); ?> >

                    <?php do_action( 'woocommerce_register_form_start' ); ?>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>

                        <p class="form-row styler-is-required">
                            <label for="reg_username"><?php esc_html_e( 'Username', 'styler' ); ?>&nbsp;<span class="required">*</span></label>
                            <input type="text" class="woocommerce-Input woocommerce-Input--text input-text" name="username" id="reg_username" autocomplete="username" value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                            <span class="styler-form-message"></span>
                        </p>

                    <?php endif; ?>

                    <p class="form-row styler-is-required">
                        <label for="reg_email"><?php esc_html_e( 'Email address', 'styler' ); ?>&nbsp;<span class="required">*</span></label>
                        <input type="email" class="woocommerce-Input woocommerce-Input--text input-text" name="email" id="reg_email" autocomplete="email" value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" /><?php // @codingStandardsIgnoreLine ?>
                        <span class="styler-form-message"></span>
                    </p>

                    <?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

                        <p class="form-row styler-is-required">
                            <label for="reg_password"><?php esc_html_e( 'Password', 'styler' ); ?>&nbsp;<span class="required">*</span></label>
                            <input type="password" class="woocommerce-Input woocommerce-Input--text input-text" name="password" id="reg_password" autocomplete="new-password" />
                            <span class="styler-form-message"></span>
                        </p>

                    <?php else : ?>

                        <p><?php esc_html_e( 'A password will be sent to your email address.', 'styler' ); ?></p>

                    <?php endif; ?>

                    <?php do_action( 'woocommerce_register_form' ); ?>

                    <p class="form-row">
                        <?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
                        <button type="submit" class="woocommerce-Button woocommerce-button button woocommerce-form-register__submit styler-btn-medium styler-btn styler-bg-black" name="register" value="<?php esc_attr_e( 'Register', 'styler' ); ?>"><?php esc_html_e( 'Register', 'styler' ); ?></button>
                    </p>

                    <?php do_action( 'woocommerce_register_form_end' ); ?>

                </form>
                <?php
            }
        }

        public static function my_account_form_template()
        {
            if ( !class_exists( 'WooCommerce' ) || is_account_page() ) {
                return;
            }

            if ( is_user_logged_in() ) {
                $current_user = wp_get_current_user();

                ?>
                <?php if ( 'popup' == styler_settings( 'header_myaccount_action_type', 'panel' ) ) { ?>
                    <div class="account-area account-logged-in">
                <?php } else { ?>
                    <div class="account-area action-content account-logged-in" data-target-name="account">
                <?php } ?>
                    <span class="panel-top-title"><?php echo esc_html__( 'Hello', 'styler' ); ?><strong class="nt-strong-sfot"> <?php echo esc_html( $current_user->display_name );?></strong></span>
                    <ul class="navigation">
                    <?php foreach ( wc_get_account_menu_items() as $endpoint => $label ) { ?>
                        <li class="menu-item <?php echo esc_attr( wc_get_account_menu_item_classes( $endpoint ) ); ?>">
                            <a href="<?php echo esc_url( wc_get_account_endpoint_url( $endpoint ) ); ?>"><?php echo esc_html( $label ); ?></a>
                        </li>
                    <?php } ?>

                    </ul>
                </div>
                <?php

            } else {

                $url         = wc_get_page_permalink( 'myaccount' );
                $actionturl  = styler_settings( 'header_account_url', '' );
                $redirecturl = '' != $actionturl ? array( 'redirect' => $actionturl ) : '';
                $redirecturl = class_exists('NextendSocialLogin', false) ? ' has-social-login' : '';
                ?>
                <?php if ( 'popup' == styler_settings( 'header_myaccount_action_type', 'panel' ) ) { ?>
                    <div class="account-area account-logged-in">
                <?php } else { ?>
                    <div class="account-area action-content" data-target-name="account">
                <?php } ?>

                    <div class="panel-top-title">
                        <span class="form-action-btn signin-title active" data-target-form="login">
                            <span><?php esc_html_e( 'Sign in', 'styler' ); ?>&nbsp;</span>
                            <?php echo styler_svg_lists( 'arrow-right' ); ?>
                        </span>
                        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                            <span class="form-action-btn register-title" data-target-form="register">
                                <?php echo styler_svg_lists( 'user-2' ); ?>
                                <span>&nbsp;<?php esc_html_e( 'Register', 'styler' ); ?></span>
                            </span>
                    <?php } ?>
                    </div>

                    <div class="account-area-form-wrapper">
                        <div class="login-form-content active">
                            <?php woocommerce_login_form( $redirecturl ); ?>
                        </div>
                        <?php if ( 'yes' === get_option( 'woocommerce_enable_myaccount_registration' ) ) { ?>
                            <div class="register-form-content">
                                <?php self::print_account_register_form(); ?>
                            </div>
                        <?php } ?>
                    </div>

                    <?php
                    if ( class_exists('NextendSocialLogin', false ) ) {
                        echo '<div class="account-area-social-form-wrapper">';
                        echo NextendSocialLogin::renderButtonsWithContainer();
                        echo '</div>';
                    }
                    ?>
                </div>
                <?php
            }
        }

        public static function my_account_form_popup_template()
        {
            if ( !class_exists( 'WooCommerce' ) || is_account_page() ) {
                return;
            }
            if ( 'popup' == styler_settings( 'header_myaccount_action_type', 'panel' ) ) {
                ?>
                <div class="account-popup-content styler-popup-item zoom-anim-dialog mfp-hide" id="styler-account-popup">
                    <?php self::my_account_form_template(); ?>
                </div>
                <?php
            }
        }

        public static function header_before()
        {
            echo styler_print_elementor_templates( 'before_header_template', 'header-top-area styler-elementor-before-header' );
        }

        public static function header_after()
        {
            echo styler_print_elementor_templates( 'after_header_template', 'header-search-area styler-elementor-after-header' );
        }

        public static function header_default()
        {
            ?>
            <header class="styler-header-default sticky header-basic">
                <div class="container-xl styler-container-xl">
                    <div class="styler-header-content">
                        <div class="styler-header-top-left header-top-side">
                            <div class="styler-header-default-inner">
                                <div class="styler-default-logo">
                                    <?php styler_logo(); ?>
                                </div>
                            </div>
                        </div>
                        <div class="styler-header-top-center">
                            <div class="styler-header-default-inner">
                                <div class="styler-header-top-menu-area">
                                    <ul class="navigation primary-menu">
                                        <?php echo self::get_nav_menu( self::$location, self::$menu ); ?>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="styler-header-top-right header-top-side">
                            <div class="styler-header-default-inner">
                                <div class="top-action-btn" data-name="search-popup">
                                    <?php echo styler_svg_lists( 'search', 'styler-svg-icon' ); ?>
                                </div>
                                <?php if ( class_exists('WooCommerce') ) { ?>
                                    <div class="header-top-buttons">
                                        <div class="top-action-btn" data-name="cart">
                                            <span class="styler-cart-count styler-wc-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                                            <?php echo styler_svg_lists( 'bag', 'styler-svg-icon' ); ?>
                                        </div>
                                    </div>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </header>

            <div class="styler-header-mobile-top styler-container-xl">
                <div class="mobile-toggle"><?php echo styler_svg_lists( 'bars', 'styler-svg-icon' ); ?></div>
                <div class="styler-header-mobile-logo">
                    <?php styler_logo(); ?>
                </div>
                <?php if ( class_exists('WooCommerce') ) { ?>
                    <div class="anarkali-header-mobile-top-actions">
                        <div class="top-action-btn" data-name="cart">
                            <span class="anarkali-cart-count anarkali-wc-count"><?php echo WC()->cart->get_cart_contents_count(); ?></span>
                            <?php echo styler_svg_lists( 'bag', 'styler-svg-icon' ); ?>
                        </div>
                    </div>
                <?php } ?>
            </div>

            <nav class="styler-header-mobile no-bar">
                <div class="styler-panel-close no-bar"></div>
                <div class="styler-header-mobile-content">
                    <div class="styler-header-slide-menu menu-area">
                        <div class="styler-header-mobile-slide-menu">
                            <ul class="navigationn primary-menuu">
                                <?php echo self::get_sidebar_nav_menu(); ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </nav>
            <?php
        }
    }
    Styler_Header::get_instance();
}


if ( !function_exists( 'styler_bottom_mobile_menu' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_bottom_mobile_menu' );
    function styler_bottom_mobile_menu() {
        if ( '0' == styler_settings( 'bottom_mobile_nav_visibility', '1' ) ) {
            return;
        }
        $menu_type = styler_settings( 'bottom_mobile_menu_type' );
        $display_type = styler_settings( 'bottom_mobile_menu_display_type' );
        ?>
        <nav class="styler-bottom-mobile-nav <?php echo esc_attr( $display_type ); ?>">
            <?php
            if ( 'elementor' == $menu_type ) {

                echo styler_print_elementor_templates( 'mobile_bottom_menu_elementor_templates' );

            } elseif ( 'wp-menu' == $menu_type ) {

                if ( has_nav_menu( 'mobile_bottom_menu' ) ) {

                    echo '<div class="mobile-nav-wrapper">';
                        echo '<ul>';
                            echo wp_nav_menu(
                                array(
                                    'menu' => '',
                                    'theme_location' => 'mobile_bottom_menu',
                                    'container' => '',
                                    'container_class' => '',
                                    'container_id' => '',
                                    'menu_class' => '',
                                    'menu_id' => '',
                                    'items_wrap' => '%3$s',
                                    'before' => '',
                                    'after' => '',
                                    'link_before' => '',
                                    'link_after' => '',
                                    'depth' => 1,
                                    'echo' => true,
                                    'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                                    'walker' => new Styler_Wp_Bootstrap_Navwalker()
                                )
                            );
                        echo '</ul>';
                    echo '</div>';
                }

            } else {

                $layouts = styler_settings( 'mobile_bottom_menu_layouts' );
                $layouts = apply_filters( 'styler_mobile_bottom_menu_layouts', $layouts );
                $arrow = is_rtl() ? 'arrow-right' : 'arrow-left';
                if ( !empty( $layouts ) && isset( $layouts['show'] ) ) {
                    unset( $layouts['show']['placebo'] );
                }
                if ( !empty( $layouts['show'] ) ) {
                    echo '<div class="mobile-nav-wrapper">';
                        echo '<ul>';
                        foreach ( $layouts['show'] as $key => $value ) {

                            switch ( $key ) {
                                case 'home':
                                    if ( '1' == styler_settings( 'bottom_mobile_nav_item_customize' ) && '' != styler_settings( 'mobile_bottom_menu_custom_home_html' ) ) {
                                        echo styler_settings( 'mobile_bottom_menu_custom_home_html' );
                                    } else {
                                        echo '<li class="menu-item">';
                                            echo '<a href="'.esc_url( home_url( '/' ) ).'" class="home-page-link">';
                                                echo styler_svg_lists( $arrow, 'styler-svg-icon' );
                                                echo '<span>'.esc_html__( 'Home', 'styler' ).'</span>';
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                break;

                                case 'shop':
                                    if ( '1' == styler_settings( 'bottom_mobile_nav_item_customize' ) && '' != styler_settings( 'mobile_bottom_menu_custom_shop_html' ) ) {
                                        echo styler_settings( 'mobile_bottom_menu_custom_shop_html' );
                                    } else {
                                        if ( styler_is_woocommerce() ) {
                                            echo '<li class="menu-item">';
                                                echo '<a href="'.esc_url( wc_get_page_permalink( 'shop' ) ).'" class="shop-page-link">';
                                                    echo styler_svg_lists( 'store', 'styler-svg-icon' );
                                                    echo '<span>'.esc_html__( 'Store', 'styler' ).'</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                    }
                                break;

                                case 'cart':
                                    if ( '1' == styler_settings( 'bottom_mobile_nav_item_customize' ) && '' != styler_settings( 'mobile_bottom_menu_custom_cart_html' ) ) {
                                        echo styler_settings( 'mobile_bottom_menu_custom_cart_html' );
                                    } else {
                                        if ( styler_is_woocommerce() ) {
                                            $cart_link_type = styler_settings( 'bottom_mobile_menu_cart_link_type', 'page' );
                                            $cart_link      = 'popup' == $cart_link_type ? '#0' : esc_url( wc_get_page_permalink( 'cart' ) );
                                            $cart_trigger   = 'popup' == $cart_link_type ? 'cart-bottom-popup-trigger' : 'cart-page-link';
                                            echo '<li class="menu-item">';
                                                echo '<a href="'.$cart_link.'" class="'.$cart_trigger.'">';
                                                    echo styler_svg_lists( 'bag', 'styler-svg-icon' );
                                                    echo '<span class="styler-cart-count styler-wc-count">'.WC()->cart->get_cart_contents_count().'</span>';
                                                    echo '<span>'.esc_html__( 'Cart', 'styler' ).'</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                    }
                                break;

                                case 'account':
                                    if ( '1' == styler_settings( 'bottom_mobile_nav_item_customize' ) && '' != styler_settings( 'mobile_bottom_menu_custom_account_html' ) ) {
                                        echo styler_settings( 'mobile_bottom_menu_custom_account_html' );
                                    } else {
                                        if ( styler_is_woocommerce() ) {
                                            $action_type   = styler_settings( 'header_myaccount_action_type', 'panel' );
                                            $account_url   = wc_get_page_permalink( 'myaccount' );
                                            $account_url   = apply_filters('styler_myaccount_page_url', $account_url );
                                            $account_class = 'acoount-page-link';
                                            $account_data  = '';
                                            $is_panel      = '';

                                            if ( !is_account_page() ) {
                                                if ( 'popup' == $action_type ) {
                                                    $account_class = 'styler-open-popup';
                                                    $account_url   = '#styler-account-popup';
                                                    $is_panel      = ' no-panel';
                                                }
                                                if ( 'panel' == $action_type ) {
                                                    $account_class = 'styler-open-account-panel';
                                                    $account_url   = '#0';
                                                    $account_data  = ' data-account-action="account"';
                                                }
                                            }

                                            echo '<li class="menu-item">';
                                                echo '<a href="'.esc_url( $account_url ).'" class="'.$account_class.$is_panel.'"'.$account_data.'>';
                                                    echo styler_svg_lists( 'user-1', 'styler-svg-icon' );
                                                    echo '<span>'.esc_html__( 'Account', 'styler' ).'</span>';
                                                echo '</a>';
                                            echo '</li>';
                                        }
                                    }
                                break;

                                case 'search':
                                    if ( '1' == styler_settings( 'bottom_mobile_nav_item_customize' ) && '' != styler_settings( 'mobile_bottom_menu_custom_search_html' ) ) {
                                        echo styler_settings( 'mobile_bottom_menu_custom_search_html' );
                                    } else {
                                        echo '<li class="menu-item">';
                                            echo '<a href="#0" data-name="search-popup" class="search-link">';
                                                echo styler_svg_lists( 'search', 'styler-svg-icon' );
                                                echo '<span>'.esc_html__( 'Search', 'styler' ).'</span>';
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                break;

                                case 'cats':
                                    if ( '1' == styler_settings( 'bottom_mobile_nav_item_customize' ) && '' != styler_settings( 'mobile_bottom_menu_custom_cats_html' ) ) {
                                        echo styler_settings( 'mobile_bottom_menu_custom_cats_html' );
                                    } else {
                                        echo '<li class="menu-item">';
                                            echo '<a href="#0" data-name="search-cats" class="search-category-link">';
                                                echo styler_svg_lists( 'paper-search', 'styler-svg-icon' );
                                                echo '<span>'.esc_html__( 'Categories', 'styler' ).'</span>';
                                            echo '</a>';
                                        echo '</li>';
                                    }
                                break;
                            }
                        }
                        echo '</ul>';
                    echo '</div>';
                }
            }
            ?>
        </nav>
        <?php
    }
}
