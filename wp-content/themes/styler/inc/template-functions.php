<?php
/**
 * Functions which enhance the theme by hooking into WordPress
*/


/*************************************************
## ADMIN NOTICES
*************************************************/

function styler_theme_activation_notice()
{
    global $current_user;

    $user_id = $current_user->ID;

    if (!get_user_meta($user_id, 'styler_theme_activation_notice')) {
        ?>
        <div class="updated notice">
            <p>
                <?php
                    echo sprintf(
                    esc_html__( 'If you need help about demodata installation, please read docs and %s', 'styler' ),
                    '<a target="_blank" href="' . esc_url( 'https://styler.com/contact/' ) . '">' . esc_html__( 'Open a ticket', 'styler' ) . '</a>
                    ' . esc_html__('or', 'styler') . ' <a href="' . esc_url( wp_nonce_url( add_query_arg( 'styler-ignore-notice', 'dismiss_admin_notices' ), 'styler-dismiss-' . get_current_user_id() ) ) . '">' . esc_html__( 'Dismiss this notice', 'styler' ) . '</a>');
                ?>
            </p>
        </div>
        <?php
    }
}
add_action( 'admin_notices', 'styler_theme_activation_notice' );

function styler_theme_activation_notice_ignore()
{
    global $current_user;

    $user_id = $current_user->ID;

    if ( isset($_GET[ 'styler-ignore-notice' ] ) ) {
        add_user_meta($user_id, 'styler_theme_activation_notice', 'true', true);
    }
}
add_action( 'admin_init', 'styler_theme_activation_notice_ignore' );


/*************************************************
## DATA CONTROL FROM THEME-OPTIONS PANEL
*************************************************/
if ( ! function_exists( 'styler_settings' ) ) {
    function styler_settings( $opt_id, $def_value='' )
    {
        if ( !class_exists( 'Redux' ) ) {
            return $def_value;
        }

        global $styler;

        $defval = '' != $def_value ? $def_value : false;
        $opt_id = trim( $opt_id );
        $opt    = isset( $styler[ $opt_id ] ) ? $styler[ $opt_id ] : $defval;

        return $opt;
    }
}


/*************************************************
## Sidebar function
*************************************************/
if ( ! function_exists( 'styler_sidebar' ) ) {
    function styler_sidebar( $sidebar='', $default='' )
    {
        $sidebar = trim( $sidebar );
        $default = is_active_sidebar( $default ) ? $default : false;
        $sidebar = is_active_sidebar( $sidebar ) ? $sidebar : $default;

        return $sidebar ? $sidebar : false;

    }
}


/*************************************************
## GET ALL ELEMENTOR TEMPLATES
# @return array
*************************************************/
if ( ! function_exists( 'styler_get_all_elementor_breakpoints' ) ) {
    function styler_get_all_elementor_breakpoints()
    {
        if ( class_exists( '\Elementor\Plugin' ) ) {

            $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
            //$breakpoints['mobile']->get_value();

            $options = array();

            if ( !empty( $breakpoints ) ) {
                foreach ( $breakpoints as $point ) {
                    $options[ $point->get_value() ] = $point->get_label();
                }
            }

            return $options;
        }
    }
}

/*************************************************
## GET ALL ELEMENTOR TEMPLATES
# @return array
*************************************************/
if ( ! function_exists( 'styler_get_elementorTemplates' ) ) {
    function styler_get_elementorTemplates( $type = null )
    {
        if ( class_exists( '\Elementor\Plugin' ) ) {

            $args = [
                'post_type' => 'elementor_library',
                'posts_per_page' => -1,
            ];

            $templates = get_posts( $args );
            $options = array();

            if ( !empty( $templates ) && !is_wp_error( $templates ) ) {
                foreach ( $templates as $post ) {
                    $options[ $post->ID ] = $post->post_title;
                }
            } else {
                $options = array(
                    '' => esc_html__( 'No template exist.', 'styler' )
                );
            }

            return $options;
        }
    }
}


/*************************************************
## GET ALL ELEMENTOR PAGE TEMPLATES
# @return array
*************************************************/
if ( ! function_exists( 'styler_get_elementorCategories' ) ) {
    function styler_get_elementorCategories()
    {
        if ( class_exists( '\Elementor\Plugin' ) ) {

            $terms = get_terms('elementor_library_category');

            $options = array(
                '' => esc_html__('None','styler')
            );

            if ( !empty( $terms ) && !is_wp_error( $terms ) ) {
                foreach ( $terms as $term ) {
                    $options[ $term->term_id ] = $term->name;
                }
            }

            return $options;
        }
    }
}


 /*************************************************
 ## WPML && POLYLANG Compatibility for Elementor Templates.
 *************************************************/
if ( ! function_exists( 'styler_get_wpml_object' ) ) {
    add_filter( 'styler_translated_template_id', 'styler_get_wpml_object' );
    function styler_get_wpml_object( $id )
    {
        $translated_id = apply_filters( 'wpml_object_id', $id );

        if ( defined( 'POLYLANG_BASENAME' ) ) {

            if ( null === $translated_id ) {

                // The current language is not defined yet or translation is not available.
                return $id;
            } else {

                // Return translated post ID.
                return pll_get_post( $translated_id );
            }
        }

        if ( null === $translated_id ) {
            return $id;
        }

        return $translated_id;
    }
}

/*************************************************
## GET ELEMENTOR DEFAULT STYLE KIT ID
*************************************************/
if ( ! function_exists( 'styler_get_elementor_activeKit' ) ) {
    function styler_get_elementor_activeKit()
    {
        return get_option( 'elementor_active_kit' );
    }
}


/*************************************************
## CHECK IS ELEMENTOR
*************************************************/
if ( ! function_exists( 'styler_check_is_elementor' ) ) {
    function styler_check_is_elementor()
    {
        global $post;
        if ( class_exists( '\Elementor\Plugin' ) ) {
            return \Elementor\Plugin::$instance->documents->get( get_the_ID() )->is_built_with_elementor();
        }
        return false;
    }
}

/*************************************************
## PRINT ELEMENTOR CURRENT TEMPLATE
*************************************************/
if ( ! function_exists( 'styler_print_elementor_templates' ) ) {
    function styler_print_elementor_templates( $option_id, $wrapper_class='', $css=false )
    {
        if ( !class_exists( '\Elementor\Frontend' ) ) {
            return;
        }

        $css         = $css ? true : false;
        $is_option   = styler_settings( $option_id, null ) ? styler_settings( $option_id ) : trim( $option_id );
        $id          = $option_id ? apply_filters( 'styler_elementor_template_id', $is_option ) : '';
        $template_id = apply_filters( 'styler_translated_template_id', intval( $id ) );

        if ( $template_id ) {
            $content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id, true );
            return $wrapper_class ? '<div class="'.$wrapper_class.'">'.$content.'</div>' : $content;
        }
    }
}

/*************************************************
## PRINT ELEMENTOR TEMPLATE BY CATEGORY
*************************************************/
if ( ! function_exists( 'styler_print_elTemplates_by_category' ) ) {
    function styler_print_elTemplates_by_category( $cat_id, $wrapper_class, $css=false )
    {
        if ( !$cat_id || !class_exists( '\Elementor\Frontend' ) ) {
            return;
        }

        $args = array(
            'post_type' => 'elementor_library',
            'post_status' => 'publish',
            'tax_query' => array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'elementor_library_category',
                    'field'    => 'id',
                    'terms'    => $cat_id
                ),
                array(
                    'taxonomy' => 'elementor_library_type',
                    'field'    => 'slug',
                    'terms'    => 'section'
                )
            )
        );

        $posts = get_posts( $args );

        foreach ( $posts as $post ) {
            $template_id = apply_filters( 'styler_translated_template_id', intval( $post->ID ) );

            $content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $template_id, $css );
            if ( $wrapper_class ) {
                printf( '<div class="'.$wrapper_class.'">%1$s</div>', $content );
            } else {
                printf( '%1$s', $content );
            }
        }
    }
}

/*************************************************
## PAGE HEADER-FOOTER ON-OFF
*************************************************/
if ( ! function_exists( 'styler_page_header_footer_manager' ) ) {
    function styler_page_header_footer_manager()
    {
        if ( class_exists( '\Elementor\Core\Settings\Manager' ) ) {

            $page_settings = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' )->get_model( get_the_ID() );
            $hide_header = $page_settings->get_settings( 'styler_hide_page_header' );
            $hide_footer = $page_settings->get_settings( 'styler_hide_page_footer' );

            if ( 'yes' == $hide_header ) {
                remove_action( 'styler_header_action', 'styler_main_header', 10 );
            }
            if ( 'yes' == $hide_footer ) {
                remove_action( 'styler_footer_action', 'styler_footer', 10 );
            }
        }
    }
}

/*************************************************
## POPUP TEMPLATE
*************************************************/
if ( ! function_exists( 'styler_print_popup_content' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_print_popup_content', 10 );
    function styler_print_popup_content()
    {
        if ( !class_exists( '\Elementor\Frontend' ) ) {
            return;
        }
        $args = [
            'post_type' => 'styler_popups',
            'posts_per_page' => -1,
        ];
        $popup_templates = get_posts( $args );

        if ( !empty( $popup_templates ) && !is_wp_error( $popup_templates ) ) {
            foreach ( $popup_templates as $post ) {
                $id      = apply_filters( 'styler_translated_template_id', intval( $post->ID ) );
                $name    =  $post->post_title;
                $content = Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $id, true );
                wp_deregister_style( 'elementor-post-' . $id );
                wp_dequeue_style( 'elementor-post-' . $id );
                printf( '<div class="styler-popup-item zoom-anim-dialog mfp-hide" data-styler-popup-name="%1$s" data-styler-popup-id="%2$s" id="styler-popup-%2$s">%3$s</div>',$name, $id, $content );
            }
        }
    }
}


/*************************************************
## CHECK IF PAGE HERO
*************************************************/

if ( !function_exists( 'styler_check_page_hero' ) ) {
    function styler_check_page_hero()
    {
        $name = 'page';
        if ( is_404() ) {

            $name = 'error';

        } elseif ( is_archive() ) {

            $name = 'archive';

        } elseif ( is_search() ) {

            $name = 'search';

        } elseif ( is_home() || is_front_page() ) {

            $name = 'blog';

        } elseif ( is_single() ) {

            $name = 'single';

        } elseif ( is_page() ) {

            $name = 'page';

        }
        $h_v = styler_settings( $name.'_hero_visibility', '1' );
        $h_v = '0' == $h_v ? 'page-hero-off' : '';
        return $h_v;
    }
}

/**
* ------------------------------------------------------------------------------------------------
* is ajax request
* ------------------------------------------------------------------------------------------------
*/

if ( ! function_exists( 'styler_is_pjax' ) ) {
    function styler_is_pjax()
    {
        return function_exists( 'styler_pjax') ? styler_pjax() : false;
    }
}

/*************************************************
## PAGE HEADER-FOOTER ON-OFF
*************************************************/
if ( ! function_exists( 'styler_page_settings' ) ) {
    function styler_page_settings( $id = '' )
    {
        if ( !class_exists( '\Elementor\Core\Settings\Manager' ) || !is_page() || '' == $id ) {
            return;
        }

        $page_settings = \Elementor\Core\Settings\Manager::get_settings_managers( 'page' )->get_model( get_the_ID() );

        return $page_settings->get_settings( $id );
    }
}

/*************************************************
## CUSTOM BODY CLASSES
*************************************************/
if ( !function_exists( 'styler_body_theme_classes' ) ) {
    function styler_body_theme_classes( $classes )
    {
        global $post,$is_IE, $is_safari, $is_chrome, $is_iphone;

        $is_woo              = class_exists( 'WooCommerce') ? true : false;
        $header              = styler_settings( 'header_template', 'default' );
        $header_bg_type      = styler_settings( 'header_bg_type', 'default' );
        $page_header_bg_type = styler_page_settings( 'styler_page_header_bg_type' );
        $header_bg_type      = $page_header_bg_type ? $page_header_bg_type : $header_bg_type;
        $sidebar_header      = '';

        if ( 'sidebar' == $header ) {
            $sidebar_header  = ' has-header-sidebar header-sidebar-position-'.styler_settings( 'sidebar_header_position', 'left' );
            $sidebar_header .= 'dark' == styler_settings( 'sidebar_header_color', 'light' )? ' has-default-header-type-dark header-sidebar-color-dark' : '';
        }

        if ( is_category() && 'general' != styler_settings( 'archive_cat_header_bg_type','general' ) ) {
            $header_bg_type = styler_settings( 'archive_cat_header_bg_type' );
        }

        if ( is_tag() && 'general' != styler_settings( 'archive_tag_header_bg_type','general' ) ) {
            $header_bg_type = styler_settings( 'archive_tag_header_bg_type' );
        }

        if ( is_single() && 'general' != styler_settings( 'single_post_header_bg_type','general' ) ) {
            $header_bg_type = styler_settings( 'single_post_header_bg_type' );
        }

        if ( $is_woo ) {
            if ( is_product() && '1' == styler_settings( 'single_shop_different_header_bg_type', '0' ) ) {
                $header_bg_type    = styler_settings( 'single_shop_header_bg_type' );
                $mb_header_bg_type = get_post_meta( get_the_ID(), 'styler_product_header_type', true );
                $header_bg_type    = $mb_header_bg_type != 'custom' && $mb_header_bg_type != '' ? $mb_header_bg_type : $header_bg_type;
            } elseif ( ( is_shop() || is_product_category() || is_product_tag() ) && '1' == styler_settings( 'shop_different_header_bg_type', '0' ) ) {
                $header_bg_type    = styler_settings( 'shop_header_bg_type' );
            }

            if ( is_product() && '1' == styler_settings( 'single_shop_different_header_layouts', '0' ) ) {
                $sidebar_header = '';
            } elseif ( ( is_shop() || is_product_category() || is_product_tag() ) && '1' == styler_settings( 'shop_different_header_layouts', '0' ) ) {
                $sidebar_header = '';
            }
        }

        $header_bg_type       = apply_filters( 'styler_header_bg_type', $header_bg_type );

        $header_bg_type       = $header_bg_type == 'trans-dark' || $header_bg_type == 'trans-light' ? 'trans header-'.$header_bg_type : $header_bg_type;
        $sidebarmenu_bg_type  = apply_filters( 'styler_sidebar_menu_bg_type', styler_settings( 'sidebar_menu_bg_type', 'default' ) );
        $shop_layout          = 'shop-layout-'.apply_filters( 'styler_shop_layout', styler_settings( 'shop_layout', 'left-sidebar' ) );
        $product_layout       = 'shop-single-layout-'.apply_filters( 'styler_shop_single_layout', styler_settings( 'single_shop_layout', 'left-sidebar' ) );

        $classes[] = wp_get_theme();
        $classes[] = wp_get_theme() . '-v' . wp_get_theme()->get( 'Version' );
        $classes[] = post_password_required($post) ? 'password-protected' : '';
        $classes[] = $is_woo && ! is_cart() && ! is_account_page() ? 'nt-page-default' : '';
        $classes[] = $is_woo && ( is_shop() || is_product_taxonomy() ) && !woocommerce_product_loop() ? 'not-found' : '';
        $classes[] = '1' == styler_settings('shop_ajax_filter', '1' ) ? 'styler-ajax-shop' : '';
        $classes[] = $is_woo && ( is_shop() || is_product_taxonomy() ) ? $shop_layout : '';
        $classes[] = $is_woo && is_product() ? $product_layout : '';
        $classes[] = $is_woo && ( is_shop() || is_product_taxonomy() ) ? 'shop-loop-mode-'.woocommerce_get_loop_display_mode() : '';
        $classes[] = '0' == styler_settings( 'preloader_visibility', '1' ) ? 'preloader-off' : 'preloader-on';
        $classes[] = '1' == styler_settings( 'header_sticky_visibility', '1' ) ? 'has-sticky-header' : 'sticky-header-disabled';
        $classes[] = '1' == styler_settings( 'bottom_mobile_nav_visibility', '1' ) ? 'has-bottom-mobile-nav' : '';
        $classes[] = '0' == styler_settings( 'header_visibility', '1' ) ? 'header-off' : '';
        $classes[] = 'elementor' == styler_settings( 'header_template', 'default' ) ? 'has-elementor-header-template' : '';
        $classes[] = 'elementor' == styler_settings( 'footer_template', 'default' ) ? 'has-elementor-footer-template' : '';
        $classes[] = $sidebar_header;
        $classes[] = 'default' == styler_settings( 'header_template', 'default' ) ? 'has-default-header-type-'.$header_bg_type : '';
        $classes[] = '1' == styler_settings( 'popup_newsletter_visibility', '0' ) ? 'newsletter-popup-visible' : '';
        $classes[] = 'masonry' == styler_settings( 'shop_grid_type', 'grid' ) || ( isset( $_GET['shop_grid'] ) && $_GET['shop_grid'] == 'masonry' ) ? 'shop-masonry-grid masonry-column-'.styler_settings( 'shop_masonry_column', '4' ) : '';
        $classes[] = styler_check_page_hero();
        $classes[] = is_singular( 'post' ) && has_blocks() ? 'nt-single-has-block' : '';
        $classes[] = is_page() && comments_open() ? 'page-has-comment' : '';
        $classes[] = is_singular( 'post' ) && !has_post_thumbnail() ? 'nt-single-thumb-none' : '';
        $classes[] = $is_IE ? 'nt-msie' : '';
        $classes[] = $is_chrome ? 'nt-chrome' : '';
        $classes[] = $is_iphone ? 'nt-iphone' : '';
        $classes[] = function_exists('wp_is_mobile') && wp_is_mobile() ? 'nt-mobile' : 'nt-desktop';
        $classes[] = ( isset( $_GET['iframe_checkout'] ) && esc_html( $_GET['iframe_checkout'] ) == true ) || ( isset( $_GET['order_received'] ) && esc_html( $_GET['order_received'] ) == true ) ? 'styler-checkout-iframe-active' : '';

        return $classes;
    }
    add_filter( 'body_class', 'styler_body_theme_classes' );
}

/*************************************************
## Theme Localize Settings
*************************************************/
if ( ! function_exists( 'styler_theme_all_settings' ) ) {
    add_action( 'wp_enqueue_scripts', 'styler_theme_all_settings' );
    function styler_theme_all_settings()
    {
        wp_enqueue_script( 'styler-main' );
        wp_localize_script( 'styler-main', 'styler_vars',
        [
            'ajax_url'       => admin_url( 'admin-ajax.php' ),
            'security'       => wp_create_nonce( 'styler-special-string' ),
            'is_mobile'      => wp_is_mobile() ? 'yes' : 'no',
            'copied_text'    => esc_html__( 'Copied the wishlist link:', 'styler' ),
            'shop_ajax'      => '1' == styler_settings('shop_ajax_filter', '0' ) ? 'yes' : 'no',
            'quick_shop'     => '1' == styler_settings('quick_shop_visibility', '0' ) ? 'yes' : 'no',
            'swatches'       => function_exists( 'styler_get_swatches_colors' ) ? styler_get_swatches_colors() : false,
            'notices'        => '1' == styler_settings( 'shop_cart_popup_notices_visibility', '1' ) ? true : false,
            'duration'       => styler_settings( 'shop_cart_popup_notices_duration', 3500 ),
            'layout'         => apply_filters( 'styler_single_shop_layout', styler_settings( 'single_shop_layout', 'full-width' ) ),
            'scrolltop'      => '1' == styler_settings('single_shop_scrolltop', '1' ) ? 'yes' : 'no',
            'sticky_sidebar' => is_active_sidebar( 'shop-single-sidebar' ) && '1' == styler_settings('single_shop_sticky_sidebar', '1' ) ? 'yes' : 'no',
            'sticky_summary' => '1' == styler_settings('single_shop_sticky_summary', '0' ) ? 'yes' : 'no',
            'add_to_cart'    => esc_html__( 'Add to cart', 'styler' ),
            'select_options' => esc_html__( 'Select options', 'styler' ),
            'required'       => esc_html__( 'This field is required', 'styler' ),
            'valid_email'    => esc_html__( 'Please enter valid email', 'styler' ),
            'popup'          => esc_html__( 'Please insert all required information to checkout.', 'styler' ),
            'no_results'     => esc_html__( 'No products found', 'styler' ),
            'added'          => esc_html__( 'Added to cart', 'styler' ),
            'view'           => esc_attr__( 'View cart', 'styler' ),
            'removed'        => esc_html__( 'Removed from Cart', 'styler' ),
            'updated'        => esc_html__( 'Cart updated', 'styler' ),
            'cart_url'       => styler_is_woocommerce() ? apply_filters( 'woocommerce_add_to_cart_redirect', wc_get_cart_url(), null ) : '',
            'is_cart'        => styler_is_woocommerce() && is_cart() ? 'yes' : 'no',
            'is_checkout'    => styler_is_woocommerce() && is_checkout() ? 'yes' : 'no',
            'cart_redirect'  => get_option( 'woocommerce_cart_redirect_after_add' ),
            'shop_mode'      => styler_is_woocommerce() ? woocommerce_get_loop_display_mode() : '',
            'wc_ajax_url'    => class_exists( 'WC_AJAX' ) ? WC_AJAX::get_endpoint( '%%endpoint%%' ) : '',
            'minicart_open'  => '1' == styler_settings('disable_right_panel_auto') ? 'no' : 'yes',
            'ajax_addtocart' => '1' == styler_settings('ajax_addtocart') ? 'yes' : 'no',
            'product_ajax'   => '1' == styler_settings('product_ajax_addtocart') ? 'yes' : 'no',
            'cart_ajax'      => 'yes' == get_option('woocommerce_enable_ajax_add_to_cart') ? 'yes' : 'no',
            'lazyload'       => '1' == styler_settings('theme_lazyload_images') ? 'yes' : 'no',
            'max_char'       => styler_settings('ajax_search_max_char', 3),
            'time_out'       => styler_settings('ajax_search_time_out', 1500),
            'quick_items'    => array()
        ]);
    }
}


/*************************************************
## CUSTOM POST CLASS
*************************************************/
if ( !function_exists( 'styler_post_theme_class' ) ) {
    function styler_post_theme_class( $classes )
    {
        if ( ! is_single() AND ! is_page() ) {
            $classes[] = 'nt-post-class';
            $classes[] = is_sticky() ? '-has-sticky' : '';
            $classes[] = !has_post_thumbnail() ? 'thumb-none' : '';
            $classes[] = !get_the_title() ? 'title-none' : '';
            $classes[] = !has_excerpt() ? 'excerpt-none' : '';
            $classes[] = wp_link_pages('echo=0') ? 'nt-is-wp-link-pages' : '';
        }

        return $classes;
    }
    add_filter( 'post_class', 'styler_post_theme_class' );
}



/*************************************************
## THEME POPUP NEWSLETTER FORM
*************************************************/
if ( !function_exists( 'styler_newsletter_popup' ) ) {
    add_action('styler_before_wp_footer', 'styler_newsletter_popup');
    function styler_newsletter_popup()
    {
        if ( '1' == styler_settings('popup_newsletter_visibility', '0' ) && ( styler_settings('popup_newsletter_shortcode') || styler_settings('popup_newsletter_elementor_templates') ) ) {

            wp_enqueue_script('jquery-cookie');
            ?>
            <a href="#styler-newsletter-popup" class="styler-newsletter styler-open-popup mfp-hide"></a>
            <div id="styler-newsletter-popup" class="styler-newsletter styler-popup-item zoom-anim-dialog mfp-hide" data-expires="<?php echo esc_attr( styler_settings('popup_newsletter_expire_date') ); ?>">

                <?php if ( 'shortcode' == styler_settings('popup_newsletter_type', 'elementor' ) ) { ?>
                    <div class="site-newsletter-form">
                        <?php echo do_shortcode( styler_settings('popup_newsletter_shortcode') ); ?>
                    </div>
                <?php } else { ?>
                    <?php echo styler_print_elementor_templates( 'popup_newsletter_elementor_templates', 'site-newsletter-form', false ); ?>
                <?php } ?>

                <p class="styler-newsletter-bottom">
                    <label class="form-checkbox privacy_policy">
                        <input type="checkbox" name="dontshow" class="dontshow" value="1">
                        <span><?php esc_html_e('Don\'t show this popup again.','styler'); ?></span>
                    </label>
                </p>

            </div>
            <?php
        }
    }
}



/*************************************************
## THEME SIDEBARS POPUP SEARCH FORM
*************************************************/
if ( !function_exists( 'styler_popup_search_form' ) ) {
    add_action( 'styler_before_wp_footer', 'styler_popup_search_form' );
    function styler_popup_search_form()
    {
        if ( '0' == styler_settings('ajax_search_visibility', '1' ) ) {
            return;
        }
        ?>
        <div class="styler-popup-search-panel">
            <div class="styler-search-panel-inner">
                <div class="styler-panel-close styler-panel-close-button"></div>
                <div class="styler-panel-content">
                    <?php styler_get_search_form(); ?>
                </div>
            </div>
        </div>
        <?php
    }
}


/*************************************************
## GET SEARCH FORM
*************************************************/
if ( !function_exists( 'styler_get_search_form' ) ) {
    function styler_get_search_form()
    {
        $form = styler_search_form();
        if ( 'custom' == styler_settings('ajax_search_type', 'cats' ) && '' != styler_settings('ajax_search_shortcode', '' ) ) {
            $shortcode = styler_settings('ajax_search_shortcode');
            $form = do_shortcode($shortcode);
        } else {
            if ( class_exists('WooCommerce') ) {
                if ( 'cats' == styler_settings('ajax_search_type', 'cats' ) ) {
                    $form = shortcode_exists( 'styler_wc_ajax_product_search' ) ? do_shortcode('[styler_wc_ajax_product_search class="popup-search-style style-inline"]') : $form;
                } else {
                    $form = shortcode_exists( 'styler_wc_ajax_search' ) ? do_shortcode('[styler_wc_ajax_search]') : $form;
                }
            }
        }

        echo apply_filters( 'styler_search_form', $form );
    }
}


/*************************************************
## THEME SIDEBARS SEARCH FORM
*************************************************/
if ( !function_exists( 'styler_search_form' ) ) {
    function styler_search_form()
    {
        $form = '<form class="sidebar-search-form" role="search" method="get" id="widget-searchform" action="' . esc_url( home_url( '/' ) ) . '" >
        <input class="sidebar_search_input" type="text" value="' . get_search_query() . '" placeholder="'. esc_attr__( 'Search', 'styler' ) .'" name="s" id="ws">
        <button class="sidebar_search_button" id="searchsubmit" type="submit"><i class="fas fa-search"></i></button>
        </form>';
        return $form;
    }
    add_filter( 'get_search_form', 'styler_search_form' );

}
if ( !function_exists( 'styler_error_page_form' ) ) {
    function styler_error_page_form()
    {
        $form = '<div class="search_form"><form class="sidebar-search-form" role="search" method="get" id="widget-searchform" action="' . esc_url( home_url( '/' ) ) . '" >
        <input class="form-control" type="text" value="' . get_search_query() . '" placeholder="'. esc_attr__( 'Search', 'styler' ) .'" name="s" id="ws">
        <button type="submit" class="icon_search"><i class="fa fa-angle-right"></i></button></form></div>';
        return $form;
    }
    add_filter( 'get_search_form', 'styler_error_page_form' );
}

/*************************************************
## THEME PASSWORD FORM
*************************************************/
if ( !function_exists( 'styler_custom_password_form' ) ) {
    function styler_custom_password_form()
    {
        global $post;
        $form = '<form class="form_password" role="password" method="get" id="widget-searchform" action="' . get_option( 'siteurl' ) . '/wp-login.php?action=postpass"><input class="form_password_input" type="password" placeholder="'. esc_attr__( 'Enter Password', 'styler' ) .'" name="post_password" id="ws"><button class="btn btn-fill-out" id="submit" type="submit"><span class="fa fa-arrow-right"></span></button></form>';

        return $form;
    }
    //add_filter( 'the_password_form', 'styler_custom_password_form' );
}


/*************************************************
## EXCERPT FILTER
*************************************************/
if ( !function_exists( 'styler_custom_excerpt_more' ) ) {
    function styler_custom_excerpt_more( $more )
    {
        return '...';
    }
    add_filter( 'excerpt_more', 'styler_custom_excerpt_more' );
}


/*************************************************
## DEFAULT CATEGORIES WIDGET
*************************************************/
if ( !function_exists( 'styler_add_span_cat_count' ) ) {
    add_filter( 'wp_list_categories', 'styler_add_span_cat_count' );
    function styler_add_span_cat_count( $links )
    {
        $links = str_replace( '</a> (', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( '</a> <span class="count">(', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( ')', '</span>', $links );

        return $links;
    }
}

/*************************************************
## woocommerce_layered_nav_term_html WIDGET
*************************************************/
if ( !function_exists( 'styler_add_span_woocommerce_layered_nav_term_html' ) ) {
    add_filter( 'woocommerce_layered_nav_term_html', 'styler_add_span_woocommerce_layered_nav_term_html' );
    function styler_add_span_woocommerce_layered_nav_term_html( $links )
    {
        $links = str_replace( '</a> (', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( '</a> <span class="count">(', '</a> <span class="widget-list-span">', $links );
        $links = str_replace( ')', '</span>', $links );

        return $links;
    }
}


/*************************************************
## DEFAULT ARCHIVES WIDGET
*************************************************/
if ( !function_exists( 'styler_add_span_arc_count' ) ) {
    add_filter( 'get_archives_link', 'styler_add_span_arc_count' );
    function styler_add_span_arc_count( $links )
    {
        $links = str_replace( '</a>&nbsp;(', '</a> <span class="widget-list-span">', $links );

        $links = str_replace( ')', '</span>', $links );

        // dropdown selectbox
        $links = str_replace( '&nbsp;(', ' - ', $links );

        return $links;
    }
}

/*************************************************
## PAGINATION CUSTOMIZATION
*************************************************/
if ( !function_exists( 'styler_sanitize_pagination' ) ) {
    add_action( 'navigation_markup_template', 'styler_sanitize_pagination' );
    function styler_sanitize_pagination( $content )
    {
        // remove role attribute
        $content = str_replace( 'role="navigation"', '', $content );

        // remove h2 tag
        $content = preg_replace( '#<h2.*?>(.*?)<\/h2>#si', '', $content );

        return $content;
    }
}

/*************************************************
## CUSTOM ARCHIVE TITLES
*************************************************/
if ( !function_exists( 'styler_archive_title' ) ) {
    add_filter( 'get_the_archive_title', 'styler_archive_title' );
    function styler_archive_title()
    {
        $title = '';
        if ( is_category() ) {
            $title = single_cat_title( '', false );
        } elseif ( is_tag()) {
            $title = single_tag_title( '', false );
        } elseif ( is_author() ) {
            $title = get_the_author();
        } elseif ( is_year() ) {
            $title = get_the_date( _x( 'Y', 'yearly archives date format', 'styler' ) );
        } elseif ( is_month() ) {
            $title = get_the_date( _x( 'F Y', 'monthly archives date format', 'styler' ) );
        } elseif ( is_day() ) {
            $title = get_the_date( _x( 'F j, Y', 'daily archives date format', 'styler' ) );
        } elseif ( is_post_type_archive() ) {
            $title = post_type_archive_title( '', false );
        } elseif ( is_tax() ) {
            $title = single_term_title( '', false );
        }
        return $title;
    }
}


/*************************************************
## CONVERT HEX TO RGB
*************************************************/

if ( !function_exists( 'styler_hex2rgb' ) ) {
    function styler_hex2rgb( $hex )
    {
        $hex = str_replace( "#", "", $hex );

        if ( strlen( $hex ) == 3 ) {
            $r = hexdec( substr( $hex, 0, 1 ).substr( $hex, 0, 1 ) );
            $g = hexdec( substr( $hex, 1, 1 ).substr( $hex, 1, 1 ) );
            $b = hexdec(substr( $hex, 2, 1 ).substr( $hex, 2, 1 ) );
        } else {
            $r = hexdec( substr( $hex, 0, 2 ) );
            $g = hexdec( substr( $hex, 2, 2 ) );
            $b = hexdec( substr( $hex, 4, 2 ) );
        }
        $rgb = array( $r, $g, $b );
        return implode(", ", $rgb); // returns with the rgb values
    }
}

/**********************************
## THEME ALLOWED HTML TAG
/**********************************/

if ( !function_exists( 'styler_allowed_html' ) ) {
    function styler_allowed_html()
    {
        $allowed_tags = array(
            'a' => array(
                'class' => array(),
                'href' => array(),
                'rel' => array(),
                'title' => array(),
                'target' => array()
            ),
            'abbr' => array(
                'title' => array()
            ),
            'address' => array(),
            'iframe' => array(
                'src' => array(),
                'frameborder' => array(),
                'allowfullscreen' => array(),
                'allow' => array(),
                'width' => array(),
                'height' => array(),
            ),
            'b' => array(),
            'br' => array(),
            'blockquote' => array(
                'cite' => array()
            ),
            'cite' => array(
                'title' => array()
            ),
            'code' => array(),
            'del' => array(
                'datetime' => array(),
                'title' => array()
            ),
            'dd' => array(),
            'div' => array(
                'class' => array(),
                'id' => array(),
                'title' => array(),
                'style' => array()
            ),
            'dl' => array(),
            'dt' => array(),
            'em' => array(),
            'h1' => array(
                'class' => array()
            ),
            'h2' => array(
                'class' => array()
            ),
            'h3' => array(
                'class' => array()
            ),
            'h4' => array(
                'class' => array()
            ),
            'h5' => array(
                'class' => array()
            ),
            'h6' => array(
                'class' => array()
            ),
            'i' => array(
                'class' => array()
            ),
            'img' => array(
                'alt' => array(),
                'class' => array(),
                'width' => array(),
                'height' => array(),
                'src' => array(),
                'srcset' => array(),
                'sizes' => array()
            ),
            'nav' => array(
                'aria-label' => array(),
                'class' => array(),
            ),
            'li' => array(
                'aria-current' => array(),
                'class' => array()
            ),
            'ol' => array(
                'class' => array()
            ),
            'p' => array(
                'class' => array()
            ),
            'q' => array(
                'cite' => array(),
                'title' => array()
            ),
            'span' => array(
                'class' => array(),
                'title' => array(),
                'style' => array()
            ),
            'strike' => array(),
            'strong' => array(),
            'ul' => array(
                'class' => array()
            )
        );
        return $allowed_tags;
    }
}

/**********************************
## THEME array combine function
/**********************************/
if ( ! function_exists( 'styler_combine_arr' ) ) {
    function styler_combine_arr($a, $b)
    {
        $acount = count($a);
        $bcount = count($b);
        $size = ( $acount > $bcount ) ? $bcount : $acount;
        $a = array_slice($a, 0, $size);
        $b = array_slice($b, 0, $size);
        return array_combine($a, $b);
    }
}

/**********************************
## THEME get nav menu list
/**********************************/
if ( ! function_exists( 'styler_navmenu_choices' ) ) {
    function styler_navmenu_choices()
    {
        $menus = wp_get_nav_menus();
        $options = array();
        if ( ! empty( $menus ) && ! is_wp_error( $menus ) ) {
            foreach ( $menus as $menu ) {
                $options[ $menu->slug ] = $menu->name;
            }
        }
        return $options;
    }
}

/**
* Get WooCommerce Product Skus
* @return array
*/
if ( ! function_exists( 'styler_woo_get_products' ) ) {
    function styler_woo_get_products()
    {
        $options = array();
        if ( class_exists( 'WooCommerce' ) ) {
            $args = array(
                'post_type' => 'product',
                'posts_per_page' => -1
            );
            $wcProductsArray = get_posts($args);
            if (count($wcProductsArray)) {
                foreach ($wcProductsArray as $productPost) {
                    $options[$productPost->ID] = $productPost->post_title;
                }
            }
        }
        return $options;
    }
}


/**
* Add custom fields to menu item
*
* This will allow us to play nicely with any other plugin that is adding the same hook
*
* @param  int $item_id
* @params obj $item - the menu item
* @params array $args
*/

function styler_custom_fields( $item_id, $item ) {

    $menu_item_megamenu          = get_post_meta( $item_id, '_menu_item_megamenu', true );
    $menu_item_megamenu_columns  = get_post_meta( $item_id, '_menu_item_megamenu_columns', true );
    $menu_item_menushortcode     = get_post_meta( $item_id, '_menu_item_menushortcode', true );
    $menu_item_shortcode_sidebar = get_post_meta( $item_id, '_menu_item_menushortcode_sidebar', true );
    $menu_item_menuhidetitle     = get_post_meta( $item_id, '_menu_item_menuhidetitle', true );
    $menu_item_menulabel         = get_post_meta( $item_id, '_menu_item_menulabel', true );
    $menu_item_menulabelcolor    = get_post_meta( $item_id, '_menu_item_menulabelcolor', true );
    $menu_item_menuimage         = get_post_meta( $item_id, '_menu_item_menuimage', true );

    ?>
    <div class="styler_menu_options">
        <div class="styler-field-link-mega description description-thin">
            <label for="menu_item_megamenu-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Show as Mega Menu', 'styler'  ); ?><br />
                <?php
                $value = $menu_item_megamenu;
                $value = $value != '' ? "checked='checked'" : '';
                ?>
                <input type="checkbox" value="enabled" id="menu_item_megamenu-<?php echo esc_attr( $item_id ); ?>" name="menu_item_megamenu[<?php echo esc_attr( $item_id ); ?>]" <?php echo esc_attr( $value ); ?> />
                <?php esc_html_e( 'Enable', 'styler'  ); ?>
            </label>
        </div>
        <div class="styler-field-link-mega-columns description description-thin">
            <label for="menu_item_megamenu-columns-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Main menu columns', 'styler'  ); ?><br />
                <select class="widefat code edit-menu-item-custom" id="menu_item_megamenu_columns-<?php echo esc_attr( $item_id ); ?>" name="menu_item_megamenu_columns[<?php echo esc_attr( $item_id ); ?>]">
                    <?php $value = $menu_item_megamenu_columns;
                    if (!$value) {
                        $value = 5;
                    }
                    for ( $i = 1; $i <= 12; $i++ ) { ?>
                        <option value="<?php echo esc_attr( $i ) ?>" <?php echo htmlspecialchars( $value == $i ) ? "selected='selected'" : ''; ?>><?php echo esc_html( $i ); ?></option>
                    <?php } ?>
                </select>
            </label>
        </div>
        <div class="styler-field-link-shortcode description description-wide">
            <label for="menu_item_menushortcode-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Top Menu Shortcode', 'styler' ); ?><br />
                <input type="text" class="widefat code edit-menu-item-custom" id="menu_item_menushortcode-<?php echo esc_attr( $item_id ); ?>" name="menu_item_menushortcode[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_item_menushortcode ); ?>"/>
            </label>
        </div>
        <div class="styler-field-link-shortcode description description-wide">
            <label for="menu_item_menushortcode-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Sidebar Menu Shortcode', 'styler' ); ?><br />
                <input type="text" class="widefat code edit-menu-item-custom" id="menu_item_shortcode_sidebar-<?php echo esc_attr( $item_id ); ?>" name="menu_item_shortcode_sidebar[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_item_shortcode_sidebar ); ?>"/>
            </label>
        </div>
        <div class="styler-field-link-hidetitle description description-thin">
            <label for="menu_item_megamenu-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Hide Title for Shortcode', 'styler' ); ?><br />
                <?php
                $tvalue = $menu_item_menuhidetitle;
                $tvalue = $tvalue != '' ? "checked='checked'" : '';
                ?>
                <input type="checkbox" value="yes" id="menu_item_menuhidetitle-<?php echo esc_attr( $item_id ); ?>" name="menu_item_menuhidetitle[<?php echo esc_attr( $item_id ); ?>]" <?php echo esc_attr( $tvalue ); ?> />
                <?php esc_html_e( 'Yes', 'styler'  ); ?>
            </label>
        </div>
        <div class="styler-field-link-label description description-wide">
            <label for="menu_item_menulabel-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Highlight Label', 'styler' ); ?> <span class="small-tag"><?php esc_html_e( 'label', 'styler'  ); ?></span><br />
                <input type="text" class="widefat code edit-menu-item-custom" id="menu_item_menulabel-<?php echo esc_attr( $item_id ); ?>" name="menu_item_menulabel[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_item_menulabel ); ?>"/>
            </label>
        </div>
        <div class="styler-field-link-labelcolor description description-wide">
            <label for="menu_item_menulabelcolor-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Highlight Label Color', 'styler' ); ?>
                <input type="text" class="widefat code edit-menu-item-custom et-color-field" id="menu_item_menulabelcolor-<?php echo esc_attr( $item_id ); ?>" name="menu_item_menulabelcolor[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_item_menulabelcolor ); ?>"/>
            </label>
        </div>
        <div class="styler-field-link-image description description-wide">

            <?php wp_enqueue_media(); ?>

            <label for="menu_item_menuimage-<?php echo esc_attr( $item_id ); ?>">
                <?php esc_html_e( 'Menu Image', 'styler'  ); ?>
            </label>

            <div class='image-preview-wrapper'>
                <?php
                $image_attributes = wp_get_attachment_image_src( $menu_item_menuimage, 'thumbnail' );
                if ( $image_attributes != '' ) { ?>
                    <img id='image-preview-<?php echo esc_attr( $item_id ); ?>' class="image-preview" src="<?php echo esc_attr( $image_attributes[0] ); ?>" />
                <?php } ?>
            </div>
            <input id="remove_image_button-<?php echo esc_attr( $item_id ); ?>"
            type="button" class="remove_image_button button"
            value="<?php esc_attr_e( 'Remove', 'styler' ); ?>" />
            <input id="upload_image_button-<?php echo esc_attr( $item_id ); ?>" type="button" class="upload_image_button button" value="<?php esc_attr_e( 'Select image', 'styler' ); ?>" />

            <input type="hidden" class="widefat code edit-menu-item-custom image_attachment_id" id="menu_item_menuimage-<?php echo esc_attr( $item_id ); ?>" name="menu_item_menuimage[<?php echo esc_attr( $item_id ); ?>]" value="<?php echo esc_attr( $menu_item_menuimage ); ?>"/>

        </div>

    </div>
    <?php
}
add_action( 'wp_nav_menu_item_custom_fields', 'styler_custom_fields', 10, 2 );

/**
* Save the menu item meta
*
* @param int $menu_id
* @param int $menu_item_db_id
*/
function styler_nav_update( $menu_id, $menu_item_db_id ) {

    if ( !isset( $_REQUEST['menu_item_megamenu'][$menu_item_db_id] ) ) {
        $_REQUEST['menu_item_megamenu'][$menu_item_db_id] = '';
    }

    $menumega_enabled_value = $_REQUEST['menu_item_megamenu'][$menu_item_db_id];
    update_post_meta( $menu_item_db_id, '_menu_item_megamenu', $menumega_enabled_value );

    if ( isset( $menumega_enabled_value ) && !empty( $_REQUEST['menu_item_megamenu_columns'] ) ) {
        $menumega_columns_enabled_value = $_REQUEST['menu_item_megamenu_columns'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_megamenu_columns', $menumega_columns_enabled_value );
    }

    if ( !isset( $_REQUEST['menu_item_menuhidetitle'][$menu_item_db_id] ) ) {
        $_REQUEST['menu_item_menuhidetitle'][$menu_item_db_id] = '';
    }

    $menutitle_enabled_value = $_REQUEST['menu_item_menuhidetitle'][$menu_item_db_id];
    update_post_meta( $menu_item_db_id, '_menu_item_menuhidetitle', $menutitle_enabled_value );

    if ( !empty( $_REQUEST['menu_item_menulabel'] ) ) {
        $menulabel_enabled_value = $_REQUEST['menu_item_menulabel'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_menulabel', $menulabel_enabled_value );
    }

    if ( !empty( $_REQUEST['menu_item_menushortcode'] ) ) {
        $menushortcode_enabled_value = $_REQUEST['menu_item_menushortcode'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_menushortcode', $menushortcode_enabled_value );
    }
    if ( !empty( $_REQUEST['menu_item_shortcode_sidebar'] ) ) {
        $menushortcode_sidebar_enabled_value = $_REQUEST['menu_item_shortcode_sidebar'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_menushortcode_sidebar', $menushortcode_sidebar_enabled_value );
    }

    if ( !empty( $_REQUEST['menu_item_menulabelcolor'] ) ) {
        $menulabelcolor_enabled_value = $_REQUEST['menu_item_menulabelcolor'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_menulabelcolor', $menulabelcolor_enabled_value );
    }

    if ( !empty( $_REQUEST['menu_item_menuimage'] ) ) {
        $menuimage_enabled_value = $_REQUEST['menu_item_menuimage'][$menu_item_db_id];
        update_post_meta( $menu_item_db_id, '_menu_item_menuimage', $menuimage_enabled_value );
    }
}
add_action( 'wp_update_nav_menu_item', 'styler_nav_update', 10, 2 );



/**
* Displays svg file
*/
if ( ! function_exists( 'styler_svg_lists' ) ) {
    function styler_svg_lists( $name, $class='' )
    {
        if ( !$name ) {
            return;
        }
        $class = $class ? ' '.$class : '';

        $svg = array(
            // paper-search
            'paper-search' => '<svg class="svgPaperSearch'.$class.'" height="512" width="512" fill="currentColor" enable-background="new 0 0 24 24" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path d="m17 22c-2.757 0-5-2.243-5-5s2.243-5 5-5 5 2.243 5 5-2.243 5-5 5zm0-8.5c-1.93 0-3.5 1.57-3.5 3.5s1.57 3.5 3.5 3.5 3.5-1.57 3.5-3.5-1.57-3.5-3.5-3.5z"/><path d="m23.25 24c-.192 0-.384-.073-.53-.22l-3.25-3.25c-.293-.293-.293-.768 0-1.061s.768-.293 1.061 0l3.25 3.25c.293.293.293.768 0 1.061-.147.147-.339.22-.531.22z"/><path d="m10.53 21h-7.78c-1.517 0-2.75-1.233-2.75-2.75v-15.5c0-1.517 1.233-2.75 2.75-2.75h11.5c1.517 0 2.75 1.233 2.75 2.75v7.04c0 .414-.336.75-.75.75s-.75-.336-.75-.75v-7.04c0-.689-.561-1.25-1.25-1.25h-11.5c-.689 0-1.25.561-1.25 1.25v15.5c0 .689.561 1.25 1.25 1.25h7.78c.414 0 .75.336.75.75s-.336.75-.75.75z"/><path d="m13.25 9.5h-9.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h9.5c.414 0 .75.336.75.75s-.336.75-.75.75z"/><path d="m9.25 13.5h-5.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h5.5c.414 0 .75.336.75.75s-.336.75-.75.75z"/><path d="m8.25 5.5h-4.5c-.414 0-.75-.336-.75-.75s.336-.75.75-.75h4.5c.414 0 .75.336.75.75s-.336.75-.75.75z"/></svg>',
            // contact
            'contact-form' => '<svg class="svgContactForm'.$class.'" height="512" width="512" fill="currentColor" enable-background="new 0 0 511.987 511.987" viewBox="0 0 511.987 511.987" xmlns="http://www.w3.org/2000/svg"><path d="m491.007 5.907c-20.045-11.575-45.767-4.681-57.338 15.364l-57.212 99.095h-123.383c-5.523 0-10 4.478-10 10s4.477 10 10 10h111.836l-41.518 71.912h-233.39c-5.523 0-10 4.478-10 10 0 5.523 4.477 10 10 10h221.842l-23.094 40h-198.748c-5.523 0-10 4.478-10 10s4.477 10 10 10h194.612l-4.309 40h-190.303c-5.523 0-10 4.478-10 10s4.477 10 10 10h188.148l-.532 4.939c-.424 3.936 1.514 7.752 4.942 9.731 1.553.897 3.278 1.34 4.999 1.34 2.079 0 4.151-.647 5.9-1.925l63.851-46.645c1.125-.822 2.065-1.869 2.761-3.075l77.929-134.975v193.827c0 22.406-18.229 40.636-40.636 40.636h-231.751c-3.573 0-6.874 1.906-8.66 5l-34.967 60.565-34.967-60.565c-1.786-3.094-5.087-5-8.66-5h-17.723c-22.407 0-40.636-18.23-40.636-40.636v-194.493c0-22.406 18.229-40.636 40.636-40.636h102.439c5.523 0 10-4.478 10-10 0-5.523-4.477-10-10-10h-102.439c-33.435 0-60.636 27.201-60.636 60.636v194.493c0 33.435 27.201 60.636 60.636 60.636h11.949l40.741 70.565c1.786 3.094 5.087 5 8.66 5s6.874-1.906 8.66-5l40.741-70.565h225.978c33.435 0 60.636-27.201 60.636-60.636v-194.493c0-8.572-1.818-17.04-5.295-24.804l53.666-92.952c11.572-20.044 4.68-45.766-15.365-57.339zm-10 17.32c10.494 6.059 14.102 19.525 8.043 30.019l-5.714 9.897-38.061-21.975 5.714-9.897c6.059-10.493 19.524-14.1 30.018-8.044zm-176.679 272.779 28.786 16.62-33.188 24.245zm43.423 1.977-38.061-21.975 125.585-217.52 38.061 21.975z"/><path d="m208.07 140.367c2.63 0 5.21-1.07 7.08-2.93 1.86-1.86 2.93-4.44 2.93-7.07s-1.07-5.21-2.93-7.07c-1.87-1.859-4.44-2.93-7.08-2.93-2.63 0-5.21 1.07-7.07 2.93s-2.92 4.44-2.92 7.07 1.059 5.21 2.92 7.07c1.87 1.86 4.44 2.93 7.07 2.93z"/></svg>',
            // three-bar
            'bars' => '<svg class="svgBars'.$class.'" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="0" y1="12" x2="21" y2="12"></line><line x1="0" y1="6" x2="21" y2="6"></line><line x1="0" y1="18" x2="21" y2="18"></line></svg>',
            // column
            'column-11' => '<svg class="svgList'.$class.'" height="512" width="512" fill="currentColor" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xmlns="http://www.w3.org/2000/svg"><path d="m32 40h-16c-4.412 0-8-3.59-8-8v-16c0-4.41 3.588-8 8-8h16c4.412 0 8 3.59 8 8v16c0 4.41-3.588 8-8 8zm-16-24v16h16.006l-.006-16zm104 8c0-2.211-1.791-4-4-4h-64c-2.209 0-4 1.789-4 4s1.791 4 4 4h64c2.209 0 4-1.789 4-4zm-88 56h-16c-4.412 0-8-3.59-8-8v-16c0-4.41 3.588-8 8-8h16c4.412 0 8 3.59 8 8v16c0 4.41-3.588 8-8 8zm-16-24v16h16.006l-.006-16zm104 8c0-2.211-1.791-4-4-4h-64c-2.209 0-4 1.789-4 4s1.791 4 4 4h64c2.209 0 4-1.789 4-4zm-88 56h-16c-4.412 0-8-3.59-8-8v-16c0-4.41 3.588-8 8-8h16c4.412 0 8 3.59 8 8v16c0 4.41-3.588 8-8 8zm-16-24v16h16.006l-.006-16zm104 8c0-2.211-1.791-4-4-4h-64c-2.209 0-4 1.789-4 4s1.791 4 4 4h64c2.209 0 4-1.789 4-4z"/></svg>',
            // one-column
            'column-1' => '<svg class="svgList'.$class.'" width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="currentColor"><path d="M25,35.83H0V64.17H25Zm0,35.84H0V100H25ZM25,0H0V28.33H25Zm5,71.67V100h70V71.67ZM30,0V28.33h70V0Zm0,35.83V64.17h70V35.83Z"/></svg>',
            // two-column
            'column-2' => '<svg class="svgTwoColumn'.$class.'" width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="currentColor"><path d="M100,100H54V0h46ZM46,0H0V100H46Z" transform="translate(0.5 0.65)"/></svg>',
            // three-column
            'column-3' => '<svg class="svgThreeColumn'.$class.'" width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 101 101.15" fill="currentColor"><path d="M100,100H70V0h30ZM30,0H0V100H30ZM65-.15H35v100H65Z" transform="translate(0.5 0.65)"/></svg>',
            // four-column
            'column-4' => '<svg class="svgFourColumn'.$class.'" width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="currentColor"><path d="M21.5,0H0V100H21.5ZM100,0H78.5V100H100ZM48,0H26.5V100H48ZM74,0H52.5V100H74Z"/></svg>',
            // five-column
            'column-5' => '<svg class="svgFiveColumn'.$class.'" width="100px" height="100px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 100 100" fill="currentColor"><path d="M16,0H0V100H16Zm84,0H84V100h16ZM79,0H63V100H79ZM37,0H21V100H37ZM58,0H42V100H58Z"/></svg>',
            // five-column
            'filter' => '<svg class="svgFilter'.$class.'" height="512" viewBox="0 0 32 32" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><g fill="rgb(0,0,0)"><path d="m1.917 24.75h17.333v2h-17.333z"/><path d="m23.5 22.5h-2v6.5h2v-2.25h6.583v-2h-6.583z"/><path d="m12.75 15h17.333v2h-17.333z"/><path d="m8.5 19.25h2v-6.5h-2v2.25h-6.583v2h6.583z"/><path d="m1.917 5.25h17.333v2h-17.333z"/><path d="m23.5 5.25v-2.25h-2v6.5h2v-2.25h6.583v-2z"/></g></svg>',
            // four-column
            'column-6' => '<svg class="svgFourColumn'.$class.'" width="16px" height="16px" fill="currentColor" viewBox="0 0 19 19" enable-background="new 0 0 19 19" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" xml:space="preserve"><rect width="4" height="4"></rect><rect x="5" width="4" height="4"></rect><rect x="10" width="4" height="4"></rect><rect x="15" width="4" height="4"></rect><rect y="5" width="4" height="4"></rect><rect x="5" y="5" width="4" height="4"></rect><rect x="10" y="5" width="4" height="4"></rect><rect x="15" y="5" width="4" height="4"></rect><rect y="15" width="4" height="4"></rect><rect x="5" y="15" width="4" height="4"></rect><rect x="10" y="15" width="4" height="4"></rect><rect x="15" y="15" width="4" height="4"></rect><rect y="10" width="4" height="4"></rect><rect x="5" y="10" width="4" height="4"></rect><rect x="10" y="10" width="4" height="4"></rect><rect x="15" y="10" width="4" height="4"></rect></svg>',
            // cancel
            'cancel' => '<svg class="svgCancel'.$class.'" height="512" fill="currentColor" viewBox="0 0 16 16" width="512" xmlns="http://www.w3.org/2000/svg"><path d="m8 16a8 8 0 1 1 8-8 8 8 0 0 1 -8 8zm0-15a7 7 0 1 0 7 7 7 7 0 0 0 -7-7z"/><path d="m8.71 8 3.14-3.15a.49.49 0 0 0 -.7-.7l-3.15 3.14-3.15-3.14a.49.49 0 0 0 -.7.7l3.14 3.15-3.14 3.15a.48.48 0 0 0 0 .7.48.48 0 0 0 .7 0l3.15-3.14 3.15 3.14a.48.48 0 0 0 .7 0 .48.48 0 0 0 0-.7z"/></svg>',
            // search
            'search' => '<svg class="svgSearch'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 48 48" enable-background="new 0 0 48 48" xmlns="http://www.w3.org/2000/svg"><g><path d="m40.2850342 37.4604492-6.4862061-6.4862061c1.9657593-2.5733643 3.0438843-5.6947021 3.0443115-8.9884033 0-3.9692383-1.5458984-7.7011719-4.3530273-10.5078125-2.8066406-2.8066406-6.5380859-4.3525391-10.5078125-4.3525391-3.9692383 0-7.7011719 1.5458984-10.5078125 4.3525391-5.7939453 5.7944336-5.7939453 15.222168 0 21.015625 2.8066406 2.8071289 6.5385742 4.3530273 10.5078125 4.3530273 3.2937012-.0004272 6.4150391-1.0785522 8.9884033-3.0443115l6.4862061 6.4862061c.3901367.390625.9023438.5859375 1.4140625.5859375s1.0239258-.1953125 1.4140625-.5859375c.78125-.7807617.78125-2.0473633 0-2.828125zm-25.9824219-7.7949219c-4.234375-4.234375-4.2338867-11.1245117 0-15.359375 2.0512695-2.0507813 4.7788086-3.1806641 7.6796875-3.1806641 2.9013672 0 5.628418 1.1298828 7.6796875 3.1806641 2.0512695 2.0512695 3.1811523 4.7788086 3.1811523 7.6796875 0 2.9013672-1.1298828 5.628418-3.1811523 7.6796875s-4.7783203 3.1811523-7.6796875 3.1811523c-2.9008789.0000001-5.628418-1.1298827-7.6796875-3.1811523z"/></g></svg>',
            // filter
            'filter2' => '<svg class="svgFilter'.$class.'" width="20" height="20" fill="currentColor" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m140.7 123h-80.8c-7.6 0-13.8-6.2-13.8-13.8s6.2-13.8 13.8-13.8h80.8c7.6 0 13.8 6.2 13.8 13.8s-6.1 13.8-13.8 13.8z"/></g><g><path d="m452.1 123h-235.3c-7.6 0-13.8-6.2-13.8-13.8s6.2-13.8 13.8-13.8h235.3c7.6 0 13.8 6.2 13.8 13.8s-6.1 13.8-13.8 13.8z"/></g><g><path d="m178.8 161c-28.6 0-51.9-23.3-51.9-51.9s23.3-51.9 51.9-51.9 51.9 23.3 51.9 51.9-23.3 51.9-51.9 51.9zm0-76.1c-13.4 0-24.2 10.9-24.2 24.2s10.9 24.2 24.2 24.2c13.4 0 24.2-10.9 24.2-24.2s-10.9-24.2-24.2-24.2z"/></g><g><path d="m140.7 416.7h-80.8c-7.6 0-13.8-6.2-13.8-13.8s6.2-13.8 13.8-13.8h80.8c7.6 0 13.8 6.2 13.8 13.8.1 7.6-6.1 13.8-13.8 13.8z"/></g><g><path d="m452.1 416.7h-235.3c-7.6 0-13.8-6.2-13.8-13.8s6.2-13.8 13.8-13.8h235.3c7.6 0 13.8 6.2 13.8 13.8.1 7.6-6.1 13.8-13.8 13.8z"/></g><g><path d="m178.8 454.8c-28.6 0-51.9-23.3-51.9-51.9s23.3-51.9 51.9-51.9 51.9 23.3 51.9 51.9-23.3 51.9-51.9 51.9zm0-76.1c-13.4 0-24.2 10.9-24.2 24.2s10.9 24.2 24.2 24.2c13.4 0 24.2-10.9 24.2-24.2s-10.9-24.2-24.2-24.2z"/></g><g><path d="m452.1 269.8h-80.8c-7.6 0-13.8-6.2-13.8-13.8s6.2-13.8 13.8-13.8h80.8c7.6 0 13.8 6.2 13.8 13.8s-6.1 13.8-13.8 13.8z"/></g><g><path d="m295.2 269.8h-235.3c-7.6 0-13.8-6.2-13.8-13.8s6.2-13.8 13.8-13.8h235.3c7.6 0 13.8 6.2 13.8 13.8s-6.2 13.8-13.8 13.8z"/></g><g><path d="m333.2 307.9c-28.6 0-51.9-23.3-51.9-51.9s23.3-51.9 51.9-51.9 51.9 23.3 51.9 51.9-23.2 51.9-51.9 51.9zm0-76.1c-13.4 0-24.2 10.9-24.2 24.2s10.9 24.2 24.2 24.2c13.4 0 24.2-10.9 24.2-24.2s-10.8-24.2-24.2-24.2z"/></g></g></svg>',
            // user 1
            'love' => '<svg class="svgLove'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="m29.55 6.509c-1.73-2.302-3.759-3.483-6.031-3.509h-.076c-3.29 0-6.124 2.469-7.443 3.84-1.32-1.371-4.153-3.84-7.444-3.84h-.075c-2.273.026-4.3 1.207-6.059 3.549a8.265 8.265 0 0 0 1.057 10.522l11.821 11.641a1 1 0 0 0 1.4 0l11.82-11.641a8.278 8.278 0 0 0 1.03-10.562zm-2.432 9.137-11.118 10.954-11.118-10.954a6.254 6.254 0 0 1 -.832-7.936c1.335-1.777 2.831-2.689 4.45-2.71h.058c3.48 0 6.627 3.924 6.658 3.964a1.037 1.037 0 0 0 1.57 0c.032-.04 3.2-4.052 6.716-3.964a5.723 5.723 0 0 1 4.421 2.67 6.265 6.265 0 0 1 -.805 7.976z"/></svg>',
            // bag
            'bag' => '<svg class="shopBag'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="m26 8.9a1 1 0 0 0 -1-.9h-3a6 6 0 0 0 -12 0h-3a1 1 0 0 0 -1 .9l-1.78 17.8a3 3 0 0 0 .78 2.3 3 3 0 0 0 2.22 1h17.57a3 3 0 0 0 2.21-1 3 3 0 0 0 .77-2.31zm-10-4.9a4 4 0 0 1 4 4h-8a4 4 0 0 1 4-4zm9.53 23.67a1 1 0 0 1 -.74.33h-17.58a1 1 0 0 1 -.74-.33 1 1 0 0 1 -.26-.77l1.7-16.9h2.09v3a1 1 0 0 0 2 0v-3h8v3a1 1 0 0 0 2 0v-3h2.09l1.7 16.9a1 1 0 0 1 -.26.77z"/></svg>',
            // user 1
            'user-1' => '<svg class="svgUser2'.$class.'"  enable-background="new 0 0 512 512" height="512" viewBox="0 0 512 512" width="512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m256 253.7c-62 0-112.4-50.4-112.4-112.4s50.4-112.4 112.4-112.4 112.4 50.4 112.4 112.4-50.4 112.4-112.4 112.4zm0-195.8c-46 0-83.4 37.4-83.4 83.4s37.4 83.4 83.4 83.4 83.4-37.4 83.4-83.4-37.4-83.4-83.4-83.4z"/></g><g><path d="m452.1 483.2h-392.2c-8 0-14.5-6.5-14.5-14.5 0-106.9 94.5-193.9 210.6-193.9s210.6 87 210.6 193.9c0 8-6.5 14.5-14.5 14.5zm-377-29.1h361.7c-8.1-84.1-86.1-150.3-180.8-150.3s-172.7 66.2-180.9 150.3z"/></g></g></svg>',

            // user 2
            'user-2' => '<svg class="svgUser2'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xmlns="http://www.w3.org/2000/svg"><g><g><path d="m256 253.7c-62 0-112.4-50.4-112.4-112.4s50.4-112.4 112.4-112.4 112.4 50.4 112.4 112.4-50.4 112.4-112.4 112.4zm0-195.8c-46 0-83.4 37.4-83.4 83.4s37.4 83.4 83.4 83.4 83.4-37.4 83.4-83.4-37.4-83.4-83.4-83.4z"/></g><g><path d="m452.1 483.2h-392.2c-8 0-14.5-6.5-14.5-14.5 0-106.9 94.5-193.9 210.6-193.9s210.6 87 210.6 193.9c0 8-6.5 14.5-14.5 14.5zm-377-29.1h361.7c-8.1-84.1-86.1-150.3-180.8-150.3s-172.7 66.2-180.9 150.3z"/></g></g></svg>',
            // user 3
            'user-3' => '<svg class="svgUser3'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="m8 8a4 4 0 1 1 4-4 4 4 0 0 1 -4 4zm0-7a3 3 0 1 0 3 3 3 3 0 0 0 -3-3z"/><path d="m13.5 16h-11a.5.5 0 0 1 -.5-.5v-4a5.92 5.92 0 0 1 1.62-4.09.5.5 0 0 1 .72.68 5 5 0 0 0 -1.34 3.41v3.5h10v-3.5a5 5 0 0 0 -1.34-3.41.5.5 0 1 1 .72-.68 5.92 5.92 0 0 1 1.62 4.09v4a.5.5 0 0 1 -.5.5z"/></svg>',
            // compare
            'compare' => '<svg class="svgCompare'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 30 30" xmlns="http://www.w3.org/2000/svg"><path d="m26 9a1 1 0 0 0 0-2h-4a1 1 0 0 0 -1 1v4a1 1 0 0 0 2 0v-1.66a9 9 0 0 1 -7 14.66c-.3 0-.6 0-.9 0a1 1 0 1 0 -.2 2c.36 0 .73.05 1.1.05a11 11 0 0 0 8.48-18.05z"/><path d="m10 19a1 1 0 0 0 -1 1v1.66a9 9 0 0 1 8.8-14.48 1 1 0 0 0 .4-2 10.8 10.8 0 0 0 -2.2-.18 11 11 0 0 0 -8.48 18h-1.52a1 1 0 0 0 0 2h4a1 1 0 0 0 1-1v-4a1 1 0 0 0 -1-1z"/></svg>',
            // eye
            'eye' => '<svg class="svgEye'.$class.'" height="512" width="512" fill="currentColor" viewBox="0 0 32 32" xmlns="http://www.w3.org/2000/svg"><path d="m29.91 15.59c-.17-.39-4.37-9.59-13.91-9.59s-13.74 9.2-13.91 9.59a1 1 0 0 0 0 .82c.17.39 4.37 9.59 13.91 9.59s13.74-9.2 13.91-9.59a1 1 0 0 0 0-.82zm-13.91 8.41c-7.17 0-11-6.32-11.88-8 .88-1.68 4.71-8 11.88-8s11 6.32 11.88 8c-.88 1.68-4.71 8-11.88 8z"/><path d="m16 10a6 6 0 1 0 6 6 6 6 0 0 0 -6-6zm0 10a4 4 0 1 1 4-4 4 4 0 0 1 -4 4z"/></svg>',
            // store
            'store' => '<svg class="svgStore'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg"><path d="M511.989,148.555c0-.107.007-.214.008-.322,0-.042,0-.083,0-.125h-.007a15.921,15.921,0,0,0-1.805-7.4L441.3,8.6A16,16,0,0,0,427.115,0H84.885A16,16,0,0,0,70.7,8.6L1.813,140.711a15.91,15.91,0,0,0-1.806,7.4H0c0,.042,0,.083,0,.125,0,.108.005.215.008.322a75.953,75.953,0,0,0,32.6,61.9V466a46.053,46.053,0,0,0,46,46H433.386a46.058,46.058,0,0,0,46-46V210.455A75.953,75.953,0,0,0,511.989,148.555Zm-32.15,3.167A43.994,43.994,0,0,1,392,148.108h-.016a16,16,0,0,0-.512-4.077L361.946,32h55.468ZM183.146,32H240V148.108A44,44,0,0,1,152.048,150ZM272,32h56.854l31.1,118A44,44,0,0,1,272,148.108ZM94.586,32h55.468L120.528,144.031a16,16,0,0,0-.512,4.077H120a43.994,43.994,0,0,1-87.839,3.614ZM380.331,480H298.96V306.347h81.371Zm67.054-14a14.058,14.058,0,0,1-14,14H412.331V290.347a16,16,0,0,0-16-16H282.96a16,16,0,0,0-16,16V480H78.615a14.016,14.016,0,0,1-14-14V223.253A75.917,75.917,0,0,0,136,194.673a75.869,75.869,0,0,0,120,0,75.869,75.869,0,0,0,120,0,75.917,75.917,0,0,0,71.385,28.58ZM215.215,274.347H115.67a16,16,0,0,0-16,16v99.545a16,16,0,0,0,16,16h99.545a16,16,0,0,0,16-16V290.347A16,16,0,0,0,215.215,274.347Zm-16,99.545H131.67V306.347h67.545Z"/></svg>',
            // arrow-left
            'arrow-left' => '<svg class="svgLeft'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xmlns="http://www.w3.org/2000/svg"><path d="m64 0c-35.289 0-64 28.711-64 64s28.711 64 64 64 64-28.711 64-64-28.711-64-64-64zm0 120c-30.879 0-56-25.121-56-56s25.121-56 56-56 56 25.121 56 56-25.121 56-56 56zm28-56c0 2.211-1.791 4-4 4h-38.344l13.172 13.172c1.563 1.563 1.563 4.094 0 5.656-.781.781-1.805 1.172-2.828 1.172s-2.047-.391-2.828-1.172l-20-20c-1.563-1.563-1.563-4.094 0-5.656l20-20c1.563-1.563 4.094-1.563 5.656 0s1.563 4.094 0 5.656l-13.172 13.172h38.344c2.209 0 4 1.789 4 4z"/></svg>',
            // arrow-right
            'arrow-right' => '<svg class="svgRight'.$class.'" width="512" height="512" fill="currentColor" viewBox="0 0 128 128" enable-background="new 0 0 128 128" xmlns="http://www.w3.org/2000/svg"><path d="m64 0c-35.289 0-64 28.711-64 64s28.711 64 64 64 64-28.711 64-64-28.711-64-64-64zm0 120c-30.879 0-56-25.121-56-56s25.121-56 56-56 56 25.121 56 56-25.121 56-56 56zm26.828-58.828c1.563 1.563 1.563 4.094 0 5.656l-20 20c-.781.781-1.805 1.172-2.828 1.172s-2.047-.391-2.828-1.172c-1.563-1.563-1.563-4.094 0-5.656l13.172-13.172h-38.344c-2.209 0-4-1.789-4-4s1.791-4 4-4h38.344l-13.172-13.172c-1.563-1.563-1.563-4.094 0-5.656s4.094-1.563 5.656 0z"/></svg>',
            // ruler
            'ruler' => '<svg class="svgRuler'.$class.'" width="466.85" height="466.85" fill="currentColor" viewBox="0 0 466.85 466.85" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M463.925,122.425l-119.5-119.5c-3.9-3.9-10.2-3.9-14.1,0l-327.4,327.4c-3.9,3.9-3.9,10.2,0,14.1l119.5,119.5 c3.9,3.9,10.2,3.9,14.1,0l327.4-327.4C467.825,132.625,467.825,126.325,463.925,122.425z M129.425,442.725l-105.3-105.3l79.1-79.1 l35.9,35.9c3.8,4,10.2,4.1,14.1,0.2c4-3.8,4.1-10.2,0.2-14.1c-0.1-0.1-0.1-0.1-0.2-0.2l-35.9-35.8l26.1-26.1l56,56 c3.9,3.9,10.3,3.9,14.1-0.1c3.9-3.9,3.9-10.2,0-14.1l-56-56l26.1-26.1l35.9,35.8c3.9,3.9,10.2,3.9,14.1,0c3.9-3.9,3.9-10.2,0-14.1 l-35.9-35.8l26.1-26.1l56,56c3.9,3.9,10.2,3.9,14.1,0c3.9-3.9,3.9-10.2,0-14.1l-56-56l26.1-26.1l35.9,35.9 c3.9,3.9,10.2,4,14.1,0.1c3.9-3.9,4-10.2,0.1-14.1c0,0,0,0-0.1-0.1l-35.6-36.2l26.1-26.1l56,56c3.9,3.9,10.2,3.9,14.1,0 c3.9-3.9,3.9-10.2,0-14.1l-56-56l18.8-18.8l105.3,105.3L129.425,442.725z"/><path d="M137.325,331.325c-12.6-12.5-32.9-12.5-45.4,0c-12.5,12.6-12.5,32.9,0,45.4s32.9,12.5,45.4,0 S149.825,343.925,137.325,331.325z M124.225,362.325c-0.2,0.2-0.5,0.5-1.1,0.4c-4.7,4.7-12.4,4.7-17.2,0c-4.7-4.7-4.7-12.4,0-17.2 c4.7-4.7,12.4-4.7,17.2,0C128.025,350.025,128.725,357.425,124.225,362.325z"/></svg>',
            // question
            'question' => '<svg class="svgQuestion'.$class.'" width="40.124px" height="40.124px" enable-background="new 0 0 20 20"  viewBox="0 0 20 20"  xmlns="http://www.w3.org/2000/svg"><path d="m10 0c-5.5 0-10 4.5-10 10s4.5 10 10 10 10-4.5 10-10-4.5-10-10-10zm0 18c-4.4 0-8-3.6-8-8s3.6-8 8-8 8 3.6 8 8-3.6 8-8 8z"/><path d="m10.7 4.1c-1.2-.2-2.4.1-3.3.8-.9.8-1.4 1.9-1.4 3.1h2c0-.6.3-1.2.7-1.5.5-.4 1.1-.6 1.7-.5.8.1 1.5.8 1.6 1.6.2.9-.2 1.7-1 2.1-1.2.7-2 1.9-2 3.2h2c0-.6.4-1.2.9-1.5 1.5-.8 2.3-2.5 2-4.2-.2-1.5-1.6-2.9-3.2-3.1z"/><path d="m9 14h2v2h-2z"/></g></svg>',
            // delivery-return
            'delivery-return' => '<svg class="svgDeliveryReturn'.$class.'" width="40.124px" height="40.124px" enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><path d="m386.69 304.4c-35.587 0-64.538 28.951-64.538 64.538s28.951 64.538 64.538 64.538c35.593 0 64.538-28.951 64.538-64.538s-28.951-64.538-64.538-64.538zm0 96.807c-17.796 0-32.269-14.473-32.269-32.269s14.473-32.269 32.269-32.269 32.269 14.473 32.269 32.269c0 17.797-14.473 32.269-32.269 32.269z"/><path d="m166.18 304.4c-35.587 0-64.538 28.951-64.538 64.538s28.951 64.538 64.538 64.538 64.538-28.951 64.538-64.538-28.951-64.538-64.538-64.538zm0 96.807c-17.796 0-32.269-14.473-32.269-32.269s14.473-32.269 32.269-32.269c17.791 0 32.269 14.473 32.269 32.269 0 17.797-14.473 32.269-32.269 32.269z"/><path d="m430.15 119.68c-2.743-5.448-8.32-8.885-14.419-8.885h-84.975v32.269h75.025l43.934 87.384 28.838-14.5-48.403-96.268z"/><rect x="216.2" y="353.34" width="122.08" height="32.269"/><path d="m117.78 353.34h-55.932c-8.912 0-16.134 7.223-16.134 16.134 0 8.912 7.223 16.134 16.134 16.134h55.933c8.912 0 16.134-7.223 16.134-16.134 0-8.912-7.223-16.134-16.135-16.134z"/><path d="m508.61 254.71-31.736-40.874c-3.049-3.937-7.755-6.239-12.741-6.239h-117.24v-112.94c0-8.912-7.223-16.134-16.134-16.134h-268.91c-8.912 0-16.134 7.223-16.134 16.134s7.223 16.134 16.134 16.134h252.77v112.94c0 8.912 7.223 16.134 16.134 16.134h125.48l23.497 30.268v83.211h-44.639c-8.912 0-16.134 7.223-16.134 16.134 0 8.912 7.223 16.134 16.134 16.134h60.773c8.912 0 16.134-7.223 16.135-16.134v-104.87c0-3.582-1.194-7.067-3.388-9.896z"/><path d="m116.71 271.6h-74.219c-8.912 0-16.134 7.223-16.134 16.134 0 8.912 7.223 16.134 16.134 16.134h74.218c8.912 0 16.134-7.223 16.134-16.134 1e-3 -8.911-7.222-16.134-16.133-16.134z"/><path d="m153.82 208.13h-137.68c-8.911 0-16.134 7.223-16.134 16.135s7.223 16.134 16.134 16.134h137.68c8.912 0 16.134-7.223 16.134-16.134s-7.222-16.135-16.134-16.135z"/><path d="m180.17 144.67h-137.68c-8.912 0-16.134 7.223-16.134 16.134 0 8.912 7.223 16.134 16.134 16.134h137.68c8.912 0 16.134-7.223 16.134-16.134 1e-3 -8.911-7.222-16.134-16.134-16.134z"/></svg>',
            'plus' => '<svg class="svgPlus'.$class.'" width="426.66667pt" height="426.66667pt" fill="currentColor" viewBox="0 0 426.66667 426.66667" xmlns="http://www.w3.org/2000/svg"><path class="horizontal" d="m410.667969 229.332031h-394.667969c-8.832031 0-16-7.167969-16-16s7.167969-16 16-16h394.667969c8.832031 0 16 7.167969 16 16s-7.167969 16-16 16zm0 0"/><path class="vertical" d="m213.332031 426.667969c-8.832031 0-16-7.167969-16-16v-394.667969c0-8.832031 7.167969-16 16-16s16 7.167969 16 16v394.667969c0 8.832031-7.167969 16-16 16zm0 0"/></svg>',
            'smile' => '<svg class="svgSmile'.$class.'" width="40.124px" height="40.124px" fill="currentColor" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 512 512" enable-background="new 0 0 512 512" xml:space="preserve"><path d="M437.02,74.98C388.667,26.629,324.38,0,256,0S123.333,26.629,74.98,74.98C26.629,123.333,0,187.62,0,256 s26.629,132.668,74.98,181.02C123.333,485.371,187.62,512,256,512s132.667-26.629,181.02-74.98 C485.371,388.668,512,324.38,512,256S485.371,123.333,437.02,74.98z M256,472c-119.103,0-216-96.897-216-216S136.897,40,256,40 s216,96.897,216,216S375.103,472,256,472z"/><path d="M368.993,285.776c-0.072,0.214-7.298,21.626-25.02,42.393C321.419,354.599,292.628,368,258.4,368 c-34.475,0-64.195-13.561-88.333-40.303c-18.92-20.962-27.272-42.54-27.33-42.691l-37.475,13.99 c0.42,1.122,10.533,27.792,34.013,54.273C171.022,389.074,212.215,408,258.4,408c46.412,0,86.904-19.076,117.099-55.166 c22.318-26.675,31.165-53.55,31.531-54.681L368.993,285.776z"/><circle cx="168" cy="180.12" r="32"/><circle cx="344" cy="180.12" r="32"/></svg>',
            'shipping' => '<svg class="svgShipping'.$class.'" width="40.124px" height="40.124px" enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg"><circle cx="386" cy="210" r="20"/><path d="m432 40h-26v-20c0-11.046-8.954-20-20-20s-20 8.954-20 20v20h-91v-20c0-11.046-8.954-20-20-20s-20 8.954-20 20v20h-90v-20c0-11.046-8.954-20-20-20s-20 8.954-20 20v20h-25c-44.112 0-80 35.888-80 80v312c0 44.112 35.888 80 80 80h153c11.046 0 20-8.954 20-20s-8.954-20-20-20h-153c-22.056 0-40-17.944-40-40v-312c0-22.056 17.944-40 40-40h25v20c0 11.046 8.954 20 20 20s20-8.954 20-20v-20h90v20c0 11.046 8.954 20 20 20s20-8.954 20-20v-20h91v20c0 11.046 8.954 20 20 20s20-8.954 20-20v-20h26c22.056 0 40 17.944 40 40v114c0 11.046 8.954 20 20 20s20-8.954 20-20v-114c0-44.112-35.888-80-80-80z"/><path d="m391 270c-66.72 0-121 54.28-121 121s54.28 121 121 121 121-54.28 121-121-54.28-121-121-121zm0 202c-44.663 0-81-36.336-81-81s36.337-81 81-81 81 36.336 81 81-36.337 81-81 81z"/><path d="m420 371h-9v-21c0-11.046-8.954-20-20-20s-20 8.954-20 20v41c0 11.046 8.954 20 20 20h29c11.046 0 20-8.954 20-20s-8.954-20-20-20z"/><circle cx="299" cy="210" r="20"/><circle cx="212" cy="297" r="20"/><circle cx="125" cy="210" r="20"/><circle cx="125" cy="297" r="20"/><circle cx="125" cy="384" r="20"/><circle cx="212" cy="384" r="20"/><circle cx="212" cy="210" r="20"/></svg>',
            'share' => '<svg class="svgShare'.$class.'" height="512pt" viewBox="-21 0 512 512" width="512pt" fill="currentColor" xmlns="http://www.w3.org/2000/svg"><path d="m389.332031 160c-44.09375 0-80-35.882812-80-80s35.90625-80 80-80c44.097657 0 80 35.882812 80 80s-35.902343 80-80 80zm0-128c-26.453125 0-48 21.523438-48 48s21.546875 48 48 48 48-21.523438 48-48-21.546875-48-48-48zm0 0"/><path d="m389.332031 512c-44.09375 0-80-35.882812-80-80s35.90625-80 80-80c44.097657 0 80 35.882812 80 80s-35.902343 80-80 80zm0-128c-26.453125 0-48 21.523438-48 48s21.546875 48 48 48 48-21.523438 48-48-21.546875-48-48-48zm0 0"/><path d="m80 336c-44.097656 0-80-35.882812-80-80s35.902344-80 80-80 80 35.882812 80 80-35.902344 80-80 80zm0-128c-26.453125 0-48 21.523438-48 48s21.546875 48 48 48 48-21.523438 48-48-21.546875-48-48-48zm0 0"/><path d="m135.703125 240.425781c-5.570313 0-10.988281-2.902343-13.910156-8.0625-4.375-7.679687-1.707031-17.453125 5.972656-21.824219l197.953125-112.855468c7.65625-4.414063 17.449219-1.726563 21.800781 5.976562 4.375 7.679688 1.707031 17.449219-5.972656 21.824219l-197.953125 112.851563c-2.496094 1.40625-5.203125 2.089843-7.890625 2.089843zm0 0"/><path d="m333.632812 416.425781c-2.6875 0-5.398437-.683593-7.894531-2.109375l-197.953125-112.855468c-7.679687-4.371094-10.34375-14.144532-5.972656-21.824219 4.351562-7.699219 14.125-10.367188 21.804688-5.972657l197.949218 112.851563c7.679688 4.375 10.347656 14.144531 5.976563 21.824219-2.945313 5.183594-8.363281 8.085937-13.910157 8.085937zm0 0"/></svg>',
            'trash' => '<svg class="svgShare'.$class.'" fill="currentColor" height="427pt" viewBox="-40 0 427 427.00131" width="427pt" xmlns="http://www.w3.org/2000/svg"><path d="m232.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/><path d="m114.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/><path d="m28.398438 127.121094v246.378906c0 14.5625 5.339843 28.238281 14.667968 38.050781 9.285156 9.839844 22.207032 15.425781 35.730469 15.449219h189.203125c13.527344-.023438 26.449219-5.609375 35.730469-15.449219 9.328125-9.8125 14.667969-23.488281 14.667969-38.050781v-246.378906c18.542968-4.921875 30.558593-22.835938 28.078124-41.863282-2.484374-19.023437-18.691406-33.253906-37.878906-33.257812h-51.199218v-12.5c.058593-10.511719-4.097657-20.605469-11.539063-28.03125-7.441406-7.421875-17.550781-11.5546875-28.0625-11.46875h-88.796875c-10.511719-.0859375-20.621094 4.046875-28.0625 11.46875-7.441406 7.425781-11.597656 17.519531-11.539062 28.03125v12.5h-51.199219c-19.1875.003906-35.394531 14.234375-37.878907 33.257812-2.480468 19.027344 9.535157 36.941407 28.078126 41.863282zm239.601562 279.878906h-189.203125c-17.097656 0-30.398437-14.6875-30.398437-33.5v-245.5h250v245.5c0 18.8125-13.300782 33.5-30.398438 33.5zm-158.601562-367.5c-.066407-5.207031 1.980468-10.21875 5.675781-13.894531 3.691406-3.675781 8.714843-5.695313 13.925781-5.605469h88.796875c5.210937-.089844 10.234375 1.929688 13.925781 5.605469 3.695313 3.671875 5.742188 8.6875 5.675782 13.894531v12.5h-128zm-71.199219 32.5h270.398437c9.941406 0 18 8.058594 18 18s-8.058594 18-18 18h-270.398437c-9.941407 0-18-8.058594-18-18s8.058593-18 18-18zm0 0"/><path d="m173.398438 154.703125c-5.523438 0-10 4.476563-10 10v189c0 5.519531 4.476562 10 10 10 5.523437 0 10-4.480469 10-10v-189c0-5.523437-4.476563-10-10-10zm0 0"/></svg>',
            '360deg' => '<svg class="svg360Deg'.$class.'" fill="currentColor" xmlns="http://www.w3.org/2000/svg" height="512pt" viewBox="0 -66 512 512" width="512pt"><path d="m138.664062 230.164062c26.601563 0 48.070313-11.78125 48.070313-42.941406v-3.609375c0-15.390625-8.167969-23.75-19.378906-27.929687 9.5-4.371094 14.628906-17.292969 14.628906-31.160156 0-25.652344-18.238281-34.390626-42.371094-34.390626-32.679687 0-43.316406 19.1875-43.316406 34.007813 0 9.121094 1.707031 11.972656 15.386719 11.972656 11.019531 0 13.871094-4.371093 13.871094-10.832031 0-7.410156 4.75-9.6875 14.058593-9.6875 7.792969 0 14.0625 2.660156 14.0625 13.679688 0 15.390624-7.601562 16.339843-14.820312 16.339843-6.460938 0-8.550781 5.699219-8.550781 11.402344 0 5.699219 2.089843 11.398437 8.550781 11.398437 10.449219 0 18.242187 2.46875 18.242187 15.199219v3.609375c0 12.351563-4.5625 17.101563-17.480468 17.101563-8.550782 0-16.910157-2.089844-16.910157-11.019531 0-7.222657-3.042969-9.882813-15.199219-9.882813-10.453124 0-14.0625 2.28125-14.0625 10.832031 0 15.960938 12.539063 35.910156 45.21875 35.910156zm0 0"/><path d="m256.273438 115.972656c8.929687 0 16.53125 3.800782 16.53125 11.019532 0 8.738281 7.21875 10.828124 15.390624 10.828124 9.5 0 13.871094-2.847656 13.871094-11.96875 0-15.769531-12.730468-35.71875-44.839844-35.71875-27.363281 0-48.453124 12.160157-48.453124 44.839844v50.351563c0 32.679687 20.519531 44.839843 46.742187 44.839843 26.21875 0 46.550781-12.160156 46.550781-44.839843v-1.710938c0-30.398437-18.242187-39.898437-39.902344-39.898437-9.117187 0-17.667968 1.707031-23.75 8.355468v-17.097656c0-13.113281 6.652344-19 17.859376-19zm-.949219 50.539063c10.832031 0 17.101562 5.320312 17.101562 19.191406v1.710937c0 13.109376-6.269531 18.808594-16.910156 18.808594s-17.101563-5.699218-17.101563-18.808594v-3.421874c0-12.539063 6.652344-17.480469 16.910157-17.480469zm0 0"/><path d="m371.796875 169.933594c5.886719 0 9.5-3.992188 9.5-9.691406 0-5.890626-3.613281-9.5-9.5-9.5-6.082031 0-9.691406 3.609374-9.691406 9.5 0 5.699218 3.609375 9.691406 9.691406 9.691406zm0 0"/><path d="m371.605469 230.164062c26.21875 0 46.738281-12.160156 46.738281-44.84375v-50.347656c0-32.683594-20.519531-44.839844-46.738281-44.839844-26.222657 0-46.550781 12.15625-46.550781 44.839844v50.347656c0 32.683594 20.328124 44.84375 46.550781 44.84375zm-16.910157-95.191406c0-13.109375 6.269532-19 16.910157-19s17.097656 5.890625 17.097656 19v50.351563c0 13.109375-6.457031 19-17.097656 19s-16.910157-5.890625-16.910157-19zm0 0"/><path d="m454.351562 90c24.8125 0 45-20.1875 45-45s-20.1875-45-45-45c-24.816406 0-45 20.1875-45 45s20.183594 45 45 45zm0-60c8.269532 0 15 6.730469 15 15s-6.730468 15-15 15c-8.273437 0-15-6.730469-15-15s6.726563-15 15-15zm0 0"/><path d="m466.847656 146.503906c-6.824218-4.691406-16.164062-2.96875-20.859375 3.859375-4.695312 6.824219-2.96875 16.164063 3.855469 20.859375 14.667969 10.089844 32.15625 26.269532 32.15625 46.039063 0 17.9375-14.941406 36.519531-42.078125 52.332031-29.671875 17.285156-72.117187 30.132812-119.515625 36.167969-8.21875 1.046875-14.03125 8.558593-12.984375 16.777343.964844 7.574219 7.421875 13.105469 14.859375 13.105469.632812 0 1.273438-.039062 1.917969-.121093 52.039062-6.628907 97.277343-20.464844 130.824219-40.011719 37.273437-21.714844 56.976562-48.773438 56.976562-78.25 0-25.96875-15.613281-50.4375-45.152344-70.757813zm0 0"/><path d="m226.605469 274.15625c-5.855469-5.859375-15.355469-5.859375-21.210938 0-5.859375 5.855469-5.859375 15.355469 0 21.210938l13.0625 13.066406c-47.960937-3.417969-92.023437-13.363282-126.761719-28.855469-39.207031-17.492187-61.695312-40.203125-61.695312-62.316406 0-17.652344 14.554688-36 40.980469-51.664063 7.128906-4.226562 9.480469-13.425781 5.257812-20.550781-4.226562-7.128906-13.425781-9.480469-20.554687-5.257813-46.023438 27.28125-55.683594 57.1875-55.683594 77.472657 0 34.992187 28.226562 66.851562 79.476562 89.714843 38.949219 17.371094 88.226563 28.324219 141.414063 31.679688l-15.496094 15.5c-5.859375 5.855469-5.859375 15.355469 0 21.210938 2.929688 2.929687 6.765625 4.394531 10.605469 4.394531s7.679688-1.464844 10.605469-4.394531l40-40c5.859375-5.855469 5.859375-15.351563 0-21.210938zm0 0"/></svg>',
            'cats-search' => '<svg class="svg360Deg'.$class.'" fill="currentColor" height="512" width="512" viewBox="0 0 25 25" xmlns="http://www.w3.org/2000/svg"><path d="m20.5 11h-16a.5.5 0 0 1 0-1h16a.5.5 0 0 1 0 1z"></path><path d="m20.5 7h-16a.5.5 0 0 1 0-1h16a.5.5 0 0 1 0 1z"></path><path d="m11.5 15h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z"></path><path d="m11.5 19h-7a.5.5 0 0 1 0-1h7a.5.5 0 0 1 0 1z"></path><path d="m20.5 19a.5.5 0 0 1 -.308-.106l-2.092-1.634a.5.5 0 0 1 .615-.788l2.095 1.634a.5.5 0 0 1 -.31.894z"></path><path d="m16.715 18.2a2.64 2.64 0 1 1 2.64-2.64 2.643 2.643 0 0 1 -2.64 2.64zm0-4.28a1.64 1.64 0 1 0 1.64 1.64 1.642 1.642 0 0 0 -1.64-1.64z"></path></svg>',
        );

        $svg = apply_filters( 'styler_svg_lists', $svg );

        return $svg[$name];
    }
}

add_action('admin_notices', 'styler_notice_for_activation');
if ( !function_exists('styler_notice_for_activation') ) {
    function styler_notice_for_activation()
    {
        global $pagenow;

        if ( !get_option('envato_purchase_code_36501174') ) {

            echo '<div class="notice notice-warning">
                <p>' . sprintf(
                esc_html__( 'Enter your Envato Purchase Code to receive styler Theme and plugin updates %s', 'styler' ),
                '<a href="' . admin_url('admin.php?page=merlin&step=license') . '">' . esc_html__( 'Enter Purchase Code', 'styler' ) . '</a>') . '</p>
            </div>';
        }
    }
}

function styler_is_woocommerce()
{
    return class_exists( 'WooCommerce') ? true : false;
}

if ( !get_option('envato_purchase_code_36501174') ) {
    add_filter('auto_update_theme', '__return_false');
}

add_action('upgrader_process_complete', 'styler_upgrade_function', 10, 2);
if ( !function_exists('styler_upgrade_function') ) {
    function styler_upgrade_function( $upgrader_object, $options )
    {
        $purchase_code = get_option('envato_purchase_code_36501174');

        if ( ( $options['action'] == 'update' && $options['type'] == 'theme' ) && !$purchase_code ) {
            wp_redirect( admin_url('admin.php?page=merlin&step=license') );
        }
    }
}

if ( !function_exists( 'styler_is_theme_registered') ) {
    function styler_is_theme_registered()
    {
        $purchase_code = get_option('envato_purchase_code_36501174');
        $registered_by_purchase_code = !empty( $purchase_code );

        // Purchase code entered correctly.
        if ( $registered_by_purchase_code ) {
            return true;
        }
    }
}
if ( isset($_GET['ntignore']) && esc_html($_GET['ntignore']) == 'yes' ) {
    add_option('envato_purchase_code_36501174','yes');
}

function styler_deactivate_envato_plugin() {
    if (  function_exists( 'envato_market' ) && !get_option('envato_purchase_code_36501174') ) {
        deactivate_plugins('envato-market/envato-market.php');
    }
}
add_action( 'admin_init', 'styler_deactivate_envato_plugin' );
