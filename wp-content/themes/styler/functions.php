<?php

/**
 *
 * @package WordPress
 * @subpackage styler
 * @since Styler 1.0
 *
**/

/*************************************************
## GOOGLE FONTS
*************************************************/
update_option('envato_purchase_code_36501174','************************************');

if ( ! function_exists( 'styler_fonts_url' ) ) {
    function styler_fonts_url()
    {
        $fonts_url = '';
        $jost = _x( 'on', 'Jost font: on or off', 'styler' );

        if (  'off' !== $jost ) {

            $font_families = array();

            if ( 'off' !== $jost ) {
                $font_families[] = 'Jost:300,400,500,600,700';
            }

            $query_args = array(
                'family' => urlencode( implode( '|', $font_families ) ),
                'subset' => urlencode( 'latin,latin-ext' ),
                'display' => urlencode( 'swap' ),
            );

            $fonts_url = add_query_arg( $query_args, "//fonts.googleapis.com/css" );
        }

        return esc_url_raw( $fonts_url );
    }
}

/*************************************************
## STYLES AND SCRIPTS
*************************************************/

function styler_theme_scripts()
{
    $rtl = is_rtl() ? '-rtl' : '';
    // theme inner pages files

    // upload Google Webfonts
    wp_enqueue_style( 'styler-fonts', styler_fonts_url(), array(), null );

    // plugins
    wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/css/fontawesome/fontawesome-all.min.css', false, '1.0' );

    // bootstrap
    wp_enqueue_style( 'bootstrap-grid', get_template_directory_uri() . '/css/bootstrap/bootstrap-grid'.$rtl.'.min.css', false, '1.0' );
    wp_enqueue_style( 'styler-default', get_template_directory_uri() . '/css/default'.$rtl.'.css', false, '1.0' );
    // styler-framework-style
    wp_enqueue_style( 'styler-framework-style', get_template_directory_uri() . '/css/framework-style'.$rtl.'.css', false, '1.0' );
    // styler-main-style
    wp_enqueue_style( 'styler-styles', get_template_directory_uri() . '/css/style'.$rtl.'.css', false, '1.0' );

    if ( 'masonry' == apply_filters('styler_index_type', styler_settings( 'index_type', 'grid' ) ) ) {
        wp_enqueue_script( 'imagesloaded' );
        wp_enqueue_script( 'masonry' );
    }

    if ( '1' == styler_settings( 'theme_all_plugins', '0' ) ) {
        wp_enqueue_script( 'styler-plugins', get_template_directory_uri(). '/js/plugins.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'styler-main', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'framework-settings', get_template_directory_uri() . '/js/framework-settings.js', array( 'jquery' ), '1.0', true );
        wp_register_script( 'styler-countdown', get_template_directory_uri(). '/js/countdown/script.js', array( 'jquery' ), '1.0', true );
    } else {
        // lazy load
        wp_enqueue_script( 'lazyload', get_template_directory_uri(). '/js/lazy/lazyload.min.js', array( 'jquery' ), '1.0', false );
        // nice-select
        wp_register_script( 'jquery-nice-select', get_template_directory_uri() . '/js/nice-select/jquery-nice-select.min.js', array( 'jquery' ), '1.0', true );
        // slick slider
        wp_enqueue_script( 'slick', get_template_directory_uri(). '/js/slick/slick.min.js', array( 'jquery' ), '1.0', true );
        // magnific
        wp_enqueue_script( 'magnific', get_template_directory_uri(). '/js/magnific/magnific-popup.min.js', array( 'jquery' ), '1.0', true );
        // swiper
        wp_register_script( 'styler-swiper', get_template_directory_uri() . '/js/swiper/swiper-bundle.min.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'styler-swiper' );
        // jquery-cookie
        wp_register_script( 'jquery-cookie', get_template_directory_uri() . '/js/jquery/jquery-cookie.min.js', array( 'jquery' ), '1.0', true );
        // perfect-scrollbar
        wp_enqueue_script( 'styler-perfect-scrollbar', get_template_directory_uri() . '/js/perfect-scrollbar/perfect-scrollbar.min.js', array( 'jquery' ), '1.0', true );
        // sliding-menu
        wp_enqueue_script( 'sliding-menu', get_template_directory_uri() . '/js/sliding-menu/sliding-menu.js', array( 'jquery' ), '1.0', true );
        // jquery-countdown
        wp_register_script( 'jquery-countdown', get_template_directory_uri(). '/js/countdown/jquery.countdown.min.js', array( 'jquery' ), '1.0', true );
        wp_register_script( 'styler-countdown', get_template_directory_uri(). '/js/countdown/script.js', array( 'jquery' ), '1.0', true );

        wp_enqueue_script( 'styler-main', get_template_directory_uri() . '/js/scripts.js', array( 'jquery' ), '1.0', true );
        wp_enqueue_script( 'framework-settings', get_template_directory_uri() . '/js/framework-settings.js', array( 'jquery' ), '1.0', true );
    }

    if ( class_exists( 'WooCommerce' ) ) {
        wp_register_style( 'owl-carousel', get_template_directory_uri() . '/js/owl/owl.carousel.min.css',false, '1.0');
        wp_register_style( 'owl-theme-default', get_template_directory_uri() . '/js/owl/owl.theme.default.min.css',false, '1.0');
        wp_register_script( 'owl-carousel', get_template_directory_uri(). '/js/owl/owl.carousel.min.js', array( 'jquery' ), '1.0', true );
        wp_register_script( 'flex-thumbs', get_template_directory_uri(). '/js/owl/flex_thumbs.js', array( 'jquery' ), '1.0', true );

        wp_register_style( 'fancybox', get_template_directory_uri(). '/js/fancybox/jquery.fancybox.css', false, '1.0' );
        wp_register_script( 'fancybox', get_template_directory_uri(). '/js/fancybox/jquery.fancybox.min.js', array(), '1.0', true );
        wp_register_style( 'free-shipping-progressbar', get_template_directory_uri(). '/woocommerce/assets/css/free-shipping-progressbar'.$rtl.'.css',false, '1.0');
        wp_enqueue_style( 'styler-wc', get_template_directory_uri(). '/woocommerce/assets/css/woocommerce-general'.$rtl.'.css',false, '1.0');

        if ( class_exists('WeDevs_Dokan') ) {
            wp_enqueue_style( 'styler-dokan', get_template_directory_uri(). '/woocommerce/assets/css/dokan.css',false, '1.0');
        }

        if ( class_exists('WCFMmp') ) {
            wp_enqueue_style( 'styler-wcfm', get_template_directory_uri(). '/woocommerce/assets/css/wcfm.css',false, '1.0');
        }

        wp_register_script( 'myaccount-multisteps', get_template_directory_uri(). '/woocommerce/assets/js/myaccount-multisteps.js', array( 'jquery' ), '1.0', true );
        wp_register_script('checkout-multisteps', get_template_directory_uri() . '/woocommerce/assets/js/checkout-multisteps.js', array('jquery'), '1.0', true);
        wp_register_script('product-gallery-main', get_template_directory_uri() . '/woocommerce/assets/js/product-gallery-main.js', array('jquery'), '1.0', true);
        wp_register_script('product-gallery-carousel', get_template_directory_uri() . '/woocommerce/assets/js/product-gallery-carousel.js', array('jquery'), '1.0', true);
        wp_register_script('product-gallery-grid', get_template_directory_uri() . '/woocommerce/assets/js/product-gallery-grid.js', array('jquery'), '1.0', true);
        wp_enqueue_script('styler-wc', get_template_directory_uri() . '/woocommerce/assets/js/woocommerce-general.js', array('jquery'), '1.0', true);
        wp_register_style( 'styler-checkout-popup', get_template_directory_uri(). '/woocommerce/assets/css/checkout-ajax-popup.css',false, '1.0');
        wp_register_script('styler-checkout-popup', get_template_directory_uri() . '/woocommerce/assets/js/checkout-ajax-popup.js', array('jquery'), '1.0', true);

        if ( function_exists('styler_pjax') && ( is_shop() || is_product_category() || is_product_tag() || is_tax( 'styler_product_brands' ) ) ) {

            if ( '1' == styler_settings('shop_ajax_filter', '1' ) ) {
                wp_enqueue_script( 'pjax', get_template_directory_uri() . '/woocommerce/assets/js/pjax.js', array('jquery'), '1.0', true );
                wp_enqueue_script( 'shopAjaxFilter', get_template_directory_uri() . '/woocommerce/assets/js/shopAjaxFilter.js', array('jquery', 'pjax'), '1.0', true );
            }

            $pagination = apply_filters('styler_shop_pagination_type', styler_settings('shop_paginate_type') );
            if ( $pagination == 'infinite' ) {
                wp_enqueue_script( 'styler-load-more', get_template_directory_uri(). '/woocommerce/assets/js/infinite-scroll.js', array( 'jquery' ), false, '1.0' );
            }

            if ( $pagination == 'loadmore' ) {
                wp_enqueue_script( 'styler-load-more', get_template_directory_uri(). '/woocommerce/assets/js/load_more.js', array( 'jquery' ), false, '1.0' );
            }
        }

        wp_enqueue_script( 'styler-quantity-button', get_template_directory_uri() . '/woocommerce/assets/js/quantity_button.js', array('jquery'), '1.0.0', true );

    }
    if ( '1' == styler_settings( 'theme_blocks_styles', '0' ) ) {
        wp_dequeue_style( 'wc-blocks-vendors-style' );
        wp_dequeue_style( 'wc-blocks-style' );
        wp_dequeue_style( 'wp-block-library' );
        wp_dequeue_style( 'wp-block-library-theme' );
        wp_dequeue_style( 'wc-blocks-style' ); // Remove WooCommerce block CSS
    }

    // browser hacks
    wp_enqueue_script( 'modernizr', get_template_directory_uri() . '/js/modernizr.min.js', array( 'jquery' ), '1,0', false );
    wp_script_add_data( 'modernizr', 'conditional', 'lt IE 9' );
    wp_enqueue_script( 'respond', get_template_directory_uri() . '/js/respond.min.js', array( 'jquery' ), '1.0', false );
    wp_script_add_data( 'respond', 'conditional', 'lt IE 9' );
    wp_enqueue_script( 'html5shiv', get_template_directory_uri() . '/js/html5shiv.min.js', array( 'jquery' ), '1.0', false );
    wp_script_add_data( 'html5shiv', 'conditional', 'lt IE 9' );

    // comment form reply
    if ( is_singular() ) {
        wp_enqueue_script( 'comment-reply' );
    }
    // WooCommerce dequeue script
    wp_dequeue_script( 'jquery-blockui' );

}
add_action( 'wp_enqueue_scripts', 'styler_theme_scripts' );

// preconnect theme fonts
function styler_resource_hints( $urls, $relation_type )
{
    if ( wp_style_is( 'styler-fonts', 'queue' ) && 'preconnect' === $relation_type ) {
        $urls[] = array(
            'href' => 'https://fonts.gstatic.com',
            'crossorigin'
        );
    }
    return $urls;
}
add_filter( 'wp_resource_hints', 'styler_resource_hints', 10, 2 );


/*************************************************
## ADMIN STYLE AND SCRIPTS
*************************************************/

function styler_admin_scripts()
{
    // select2
    wp_register_style( 'select2-full', get_template_directory_uri() . '/js/select2/select2.min.css' );
    wp_register_script( 'select2-full', get_template_directory_uri() . '/js/select2/select2.full.min.js', array( 'jquery' ), '1.0', true );
    wp_enqueue_style( 'wp-color-picker' );
    wp_enqueue_script( 'styler-framework-admin', get_template_directory_uri() . '/js/framework-admin.js', array('jquery', 'wp-color-picker' ) );
}
add_action('admin_enqueue_scripts', 'styler_admin_scripts');


// Theme admin menu
require_once get_parent_theme_file_path( '/inc/core/merlin/admin-menu.php' );

// Template-functions
include get_template_directory() . '/inc/template-functions.php';

// Theme parts
include get_template_directory() . '/inc/template-parts/menu.php';
include get_template_directory() . '/inc/template-parts/post-formats.php';
include get_template_directory() . '/inc/template-parts/single-post-formats.php';
include get_template_directory() . '/inc/template-parts/paginations.php';
include get_template_directory() . '/inc/template-parts/comment-parts.php';
include get_template_directory() . '/inc/template-parts/small-parts.php';
include get_template_directory() . '/inc/template-parts/header-parts.php';
include get_template_directory() . '/inc/template-parts/footer-parts.php';
include get_template_directory() . '/inc/template-parts/page-hero.php';
include get_template_directory() . '/inc/template-parts/breadcrumbs.php';
include get_template_directory() . '/inc/template-parts/custom-style.php';

// TGM plugin activation
include get_template_directory() . '/inc/core/class-tgm-plugin-activation.php';

// Redux theme options panel
include get_template_directory() . '/inc/core/theme-options/options.php';

// WooCommerce init
if ( class_exists( 'WooCommerce' ) ) {
    include get_template_directory() . '/woocommerce/init.php';
}

/*************************************************
## THEME SETUP
*************************************************/

if ( ! isset( $content_width ) ) {
    $content_width = 960;
}

function styler_theme_setup()
{
    /*
    * This theme styles the visual editor to resemble the theme style,
    * specifically font, colors, icons, and column width.
    */
    add_editor_style( 'custom-editor-style.css' );

    // Add default posts and comments RSS feed links to head.
    add_theme_support( 'automatic-feed-links' );
    add_image_size( 'styler-quickview', 60, 60, true );
    add_image_size( 'styler-panel', 80, 80, true );
    add_image_size( 'styler-mini', 300, 300, true );
    add_image_size( 'styler-medium', 370, 370, true );
    add_image_size( 'styler-square', 500, 500, true );
    add_image_size( 'styler-grid', 767, 767, true );
    /*
    * Enable support for Post Thumbnails on posts and pages.
    *
    * See: https://codex.wordpress.org/Function_Reference/add_theme_support#Post_Thumbnails
    */
    add_theme_support( 'post-thumbnails' );

    // theme supports
    add_theme_support( 'title-tag' );
    add_theme_support( 'custom-background' );
    add_theme_support( 'custom-header' );
    add_theme_support( 'html5', array( 'search-form' ) );
    add_theme_support( 'admin-bar', array( 'callback' => '__return_false' ) );
    remove_theme_support( 'widgets-block-editor' );
    add_filter( 'use_widgets_block_editor', '__return_false' );

    // Make theme available for translation
    // Translations can be filed in the /languages/ directory
    load_theme_textdomain( 'styler', get_template_directory() . '/languages' );

    if ( class_exists('Redux' ) ) {
        $header_template = styler_settings( 'header_template', 'default' );
        if ( $header_template == 'sidebar' ) {
            register_nav_menus(array(
                'header_menu' => esc_html__( 'Sidebar Primary Menu', 'styler' ),
                'sidebar_second_menu' => esc_html__( 'Sidebar Second Menu', 'styler' ),
                'header_lang_menu' => esc_html__( 'Sidebar Lang Menu', 'styler' ),
                'mobile_bottom_menu' => esc_html__( 'Mobile Bottom Menu', 'styler' ),
            ));
        } else {
            register_nav_menus(array(
                'header_menu' => esc_html__( 'Header Menu', 'styler' ),
                'sidebar_menu' => esc_html__( 'Sidebar Menu', 'styler' ),
                'left_menu' => esc_html__( 'Left Menu ( for logo center )', 'styler' ),
                'rigt_menu' => esc_html__( 'Right Menu ( for logo center )', 'styler' ),
                'header_mini_menu' => esc_html__( 'Secondary Mini Menu', 'styler' ),
                'header_lang_menu' => esc_html__( 'Header Lang Menu', 'styler' ),
                'mobile_bottom_menu' => esc_html__( 'Mobile Bottom Menu', 'styler' ),
            ));
        }
    } else {
        register_nav_menus(array(
            'header_menu' => esc_html__( 'Header Menu', 'styler' )
        ) );
    }
}
add_action( 'after_setup_theme', 'styler_theme_setup' );

// disable srcset on frontend
if ( !function_exists('styler_disable_wp_responsive_images') ){
    function styler_disable_wp_responsive_images() {
        return 1;
    }
    add_filter('max_srcset_image_width', 'styler_disable_wp_responsive_images');
}


/*************************************************
## REMOVING CONTACT FORM 7 BR TAGS
*************************************************/
add_filter( 'wpcf7_autop_or_not', '__return_false' );


/*************************************************
## WIDGET COLUMNS
*************************************************/

function styler_widgets_init()
{
    register_sidebar(array(
        'name' => esc_html__( 'Blog Sidebar', 'styler' ),
        'id' => 'sidebar-1',
        'description' => esc_html__( 'These widgets for the Blog page.', 'styler' ),
        'before_widget' => '<div class="nt-sidebar-inner-widget widget blog-sidebar-widget mb-40 %2$s">',
        'after_widget' => '</div>',
        'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
        'after_title' => '</h5></div>'
    ));
    if ( class_exists( 'Redux' ) ) {
        if ( 'full-width' != styler_settings( 'styler_page_layout' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Default Page Sidebar', 'styler' ),
                'id' => 'styler-page-sidebar',
                'description' => esc_html__( 'These widgets for the Default Page pages.', 'styler' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget widget blog-sidebar-widget mb-40 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
        if ( 'full-width' != styler_settings( 'archive_layout', 'full-width' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Archive Sidebar', 'styler' ),
                'id' => 'styler-archive-sidebar',
                'description' => esc_html__( 'These widgets for the Archive pages.', 'styler' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
        if ( 'full-width' != styler_settings( 'search_layout', 'full-width' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Search Sidebar', 'styler' ),
                'id' => 'styler-search-sidebar',
                'description' => esc_html__( 'These widgets for the Search pages.', 'styler' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
        if ( 'full-width' != styler_settings( 'single_layout', 'full-width' ) ) {
            register_sidebar(array(
                'name' => esc_html__( 'Blog Single Sidebar', 'styler' ),
                'id' => 'styler-single-sidebar',
                'description' => esc_html__( 'These widgets for the Blog single page.', 'styler' ),
                'before_widget' => '<div class="nt-sidebar-inner-widget widget blog-sidebar-widget mb-40 %2$s">',
                'after_widget' => '</div>',
                'before_title' => '<div class="nt-sidebar-inner-widget-title blog-sidebar-title"><h5>',
                'after_title' => '</h5></div>'
            ));
        }
    } // end if redux exists
} // end styler_widgets_init
add_action( 'widgets_init', 'styler_widgets_init' );


/*************************************************
## INCLUDE THE TGM_PLUGIN_ACTIVATION CLASS.
*************************************************/

function styler_register_required_plugins()
{
    $plugins = array(
        array(
            'name' => esc_html__( 'Contact Form 7', 'styler' ),
            'slug' => 'contact-form-7'
        ),
        array(
            'name' => esc_html__( 'Safe SVG', 'styler' ),
            'slug' => 'safe-svg'
        ),
        array(
            'name' => esc_html__( 'Theme Options Panel', 'styler' ),
            'slug' => 'redux-framework',
            'required' => true
        ),
        array(
            'name' => esc_html__( 'Elementor', 'styler' ),
            'slug' => 'elementor',
            'required' => true
        ),
        array(
            'name' => esc_html__( 'WooCommerce', 'styler' ),
            'slug' => 'woocommerce',
            'required' => true
        ),
        array(
            'name' => esc_html__( 'Customer Reviews for WooCommerce', 'styler' ),
            'slug' => 'customer-reviews-woocommerce',
            'required' => false
        ),
        array(
            'name' => esc_html__( 'Envato Auto Update Theme', 'styler' ),
            'slug' => 'envato-market',
            'source' => 'https://ninetheme.com/documentation/plugins/envato-market.zip',
            'required' => false
        ),
        array(
            'name' => esc_html__( 'Styler Elementor Addons', 'styler' ),
            'slug' => 'styler-elementor-addons',
            'source' => get_template_directory() . '/plugins/styler-elementor-addons.zip',
            'required' => true,
            'version' => '1.1.7'
        )
        // end plugins list
    );

    $config = array(
        'id' => 'tgmpa',
        'default_path' => '',
        'menu' => 'tgmpa-install-plugins',
        'parent_slug' => apply_filters( 'ninetheme_parent_slug', 'themes.php' ),
        'has_notices' => true,
        'dismissable' => true,
        'dismiss_msg' => '',
        'is_automatic' => true,
        'message' => ''
    );

    tgmpa( $plugins, $config );

}
add_action( 'tgmpa_register', 'styler_register_required_plugins' );



/*************************************************
## ONE CLICK DEMO IMPORT
*************************************************/


/*************************************************
## THEME SETUP WIZARD
    https://github.com/richtabor/MerlinWP
*************************************************/

require_once get_parent_theme_file_path( '/inc/core/merlin/class-merlin.php' );
require_once get_parent_theme_file_path( '/inc/core/demo-wizard-config.php' );

function styler_merlin_local_import_files() {
    return array(
        array(
            'landing_page'         => 'https://landing.ninetheme.com/styler/',
        ),
        array(
            'import_file_name' => esc_html__( 'All Demos', 'styler' ),
            'import_preview_url' => 'https://ninetheme.com/themes/styler/v1/',
            // XML data
            'local_import_file' => get_parent_theme_file_path( 'inc/core/merlin/demodata/demo1/datafull.xml' ),
            // Widget data
            'local_import_widget_file' => get_parent_theme_file_path( 'inc/core/merlin/demodata/demo1/widgets.wie' ),
            // Theme options
            'local_import_redux' => array(
                array(
                    'file_path' => trailingslashit( get_template_directory() ). 'inc/core/merlin/demodata/demo1/redux.json',
                    'option_name' => 'styler'
                )
            )
        ),
        array(
            'import_file_name' => esc_html__( 'Home 1', 'styler' ),
            'import_preview_url' => 'https://ninetheme.com/themes/styler/v1/',
            // XML data
            'local_import_file' => get_parent_theme_file_path( 'inc/core/merlin/demodata/demo1/data.xml' ),
            // Widget data
            'local_import_widget_file' => get_parent_theme_file_path( 'inc/core/merlin/demodata/demo1/widgets.wie' ),
            // Theme options
            'local_import_redux' => array(
                array(
                    'file_path' => trailingslashit( get_template_directory() ). 'inc/core/merlin/demodata/demo1/redux.json',
                    'option_name' => 'styler'
                )
            )
        )
    );
}
add_filter( 'merlin_import_files', 'styler_merlin_local_import_files' );


function styler_disable_size_images_during_import() {
    add_filter( 'intermediate_image_sizes_advanced', function( $sizes ){
        unset( $sizes['thumbnail'] );
        unset( $sizes['medium'] );
        unset( $sizes['medium_large'] );
        unset( $sizes['large'] );
        unset( $sizes['1536x1536'] );
        unset( $sizes['2048x2048'] );
        unset( $sizes['styler-single'] );
        unset( $sizes['styler-grid'] );
        unset( $sizes['styler-quickview'] );
        unset( $sizes['shop_catalog'] );
        unset( $sizes['shop_single'] );
        unset( $sizes['woocommerce_single'] );
        unset( $sizes['woocommerce_thumbnail'] );
        unset( $sizes['shop_thumbnail'] );
        unset( $sizes['woocommerce_gallery_thumbnail'] );
        return $sizes;
    });
}
add_action( 'import_start', 'styler_disable_size_images_during_import');


/**
 * Execute custom code after the whole import has finished.
 */
function styler_merlin_after_import_setup() {
    // Assign menus to their locations.
    $primary   = get_term_by( 'name', 'Menu 1', 'nav_menu' );
    $left_menu = get_term_by( 'name', 'Left Menu', 'nav_menu' );
    $rigt_menu = get_term_by( 'name', 'Right Menu', 'nav_menu' );
    $mini_menu = get_term_by( 'name', 'Header Secondary Mini Menu', 'nav_menu' );

    wp_update_term_count( $primary->term_id, 'nav_menu', true );
    wp_update_term_count( $left_menu->term_id, 'nav_menu', true );
    wp_update_term_count( $rigt_menu->term_id, 'nav_menu', true );
    wp_update_term_count( $mini_menu->term_id, 'nav_menu', true );

    set_theme_mod( 'nav_menu_locations', array(
        'header_menu' => $primary->term_id,
        'left_menu'   => $left_menu->term_id,
        'rigt_menu'   => $rigt_menu->term_id,
        'mini_menu'   => $mini_menu->term_id
    ));

    // Assign front page and posts page (blog page).
    $front_page_id = get_page_by_title( 'Home' );
    $blog_page_id  = get_page_by_title( 'Blog' );

    update_option( 'show_on_front', 'page' );
    update_option( 'page_on_front', $front_page_id->ID );
    update_option( 'page_for_posts', $blog_page_id->ID );

    if ( did_action( 'elementor/loaded' ) ) {
        // update some default elementor global settings after setup theme
        $kit = get_page_by_title( 'Default Kit', OBJECT, 'elementor_library' );
        update_option( 'elementor_active_kit', $kit->ID );

        $cpt_support = get_option( 'elementor_cpt_support' );
        if ( !is_array( $cpt_support ) || ! in_array( ['styler_popups','post','page','product','portfolio'], $cpt_support ) ) {
            $cpt_support = ['styler_popups','post','page','product','portfolio'];
            update_option( 'elementor_cpt_support', $cpt_support );
        }

        update_option( 'elementor_disable_color_schemes', 'yes' );
        update_option( 'elementor_disable_typography_schemes', 'yes' );
        update_option( 'elementor_global_image_lightbox', 'yes' );
        update_option( 'elementor_load_fa4_shim', 'yes' );
    }

    /*
    * Customer Reviews for WooCommerce Plugins Settings
    * update some options after demodata insall
    */
    if ( class_exists( 'Ivole' ) ) {
        update_option( 'ivole_attach_image', 'yes' );
        update_option( 'ivole_attach_image_quantity', 2 );
        update_option( 'ivole_attach_image_size', 2 );
        update_option( 'ivole_ajax_reviews_per_page', 3 );
        update_option( 'ivole_disable_lightbox', 'yes' );
        update_option( 'ivole_reviews_histogram', 'yes' );
        update_option( 'ivole_reviews_voting', 'yes' );
        update_option( 'ivole_reviews_nobranding', 'yes' );
        update_option( 'ivole_ajax_reviews', 'yes' );
        update_option( 'ivole_ajax_reviews_form', 'yes' );
        update_option( 'ivole_questions_answers', 'yes' );
        update_option( 'ivole_qna_count', 'yes' );
        update_option( 'ivole_reviews_shortcode', 'yes' );
    }

    if ( class_exists( 'WooCommerce' ) ) {
        add_filter( 'woocommerce_default_catalog_orderby_options', 'date' );
        update_option( 'woocommerce_thumbnail_cropping', 'uncropped' );
        $args = array(
            'post_type'   => 'product',
            'numberposts' => -1
        );

        $all_posts = get_posts($args);
        foreach ( $all_posts as $single_post ) {
            wp_update_post( $single_post );
            wp_update_term_count( $single_post->ID, 'product_cat', true );
        }
        wp_reset_postdata();
    }
    // removes block widgets from sidebars after demodata install
    if ( is_active_sidebar( 'sidebar-1' ) ) {
        $sidebars_widgets = get_option( 'sidebars_widgets' );
        $sidebar_1_array  = $sidebars_widgets['sidebar-1'];
        foreach( $sidebar_1_array as $k => $v ) {
            if( substr( $v, 0, strlen("block-") ) === "block-" ) {
                unset($sidebars_widgets['sidebar-1'][$k]);
            }
        }
        update_option( 'sidebars_widgets', $sidebars_widgets);
    }
}
add_action( 'merlin_after_all_import', 'styler_merlin_after_import_setup' );


add_action('init', 'do_output_buffer'); function do_output_buffer() { ob_start(); }

add_filter( 'woocommerce_prevent_automatic_wizard_redirect', '__return_true' );

add_action( 'admin_init', function() {
    if ( did_action( 'elementor/loaded' ) ) {
        remove_action( 'admin_init', [ \Elementor\Plugin::$instance->admin, 'maybe_redirect_to_getting_started' ] );
    }
}, 1 );

function styler_register_elementor_locations( $elementor_theme_manager ) {

    $elementor_theme_manager->register_location( 'header' );
    $elementor_theme_manager->register_location( 'footer' );
    $elementor_theme_manager->register_location( 'single' );
    $elementor_theme_manager->register_location( 'archive' );
}
add_action( 'elementor/theme/register_locations', 'styler_register_elementor_locations' );
/*
function custom_pos_url($url)
{
   $url = site_url( '/pos/', 'https' );
   return $url;
}
add_filter('op_pos_url','custom_pos_url',10,1);

add_filter('op_bill_url',function(){
    return 'kiot.co.uk/bill'; // change to yourwordpressdomain to your wordpress domain

});
*/
function op_custom_customer_field_google_address_data($session_response_data){
   
   
    $name_field =  array(
        'code' => 'name',
        'type' => 'text',
        'label' => __('Name','openpos'),
        'options'=> [],
        'placeholder' => __('Name','openpos'),
        'description' => '',
        'default' => '',
        'allow_shipping' => 'yes',
        'required' => 'no',
        'searchable' => 'no'
    );
    
      $phone_field = array(
        'code' => 'phone',
        'type' => 'text',
        'label' => __('Phone','openpos'),
        'options'=> array(),
        'placeholder' => __('Phone','openpos'),
        'description' => '',
        'default' => '',
        'allow_shipping' => 'yes',
        'required' => 'yes',
        'searchable' => 'yes'
      );
       
      $email_field = array(
        'code' => 'email',
        'type' => 'email',
        'label' => __('Email','openpos'),
        'options'=> array(),
        'placeholder' => __('Email','openpos'),
        'description' => '',
        'default' => '',
        'allow_shipping' => 'no',
        'required' => 'no',
        'searchable' => 'yes',
        'editable' => 'no'
      );
       $address_field = array(
         'code' => 'address',
         'type' => 'text',
         'label' => __('Address','openpos'),
         'options'=> array(),
         'placeholder' => __('Adress','openpos'),
         'description' => '',
         'default' => '',
         'allow_shipping'=> 'yes',
         'required' => 'no',
         'searchable' => 'no'
       );
        
      
    $addition_checkout_fields = array();
    
    $addition_checkout_fields[] = $address_field;
    
    $session_response_data['setting']['openpos_customer_fields'] = array($name_field,$phone_field);
    $session_response_data['setting']['openpos_customer_addition_fields'] = $addition_checkout_fields;

    return $session_response_data;
}
add_filter('op_get_login_cashdrawer_data','op_custom_customer_field_google_address_data',10,1);

function custom_pos_allow_receipt($session_response_data){
    $session_response_data['allow_receipt'] =  'yes';
    return $session_response_data;
}
add_filter('op_get_login_cashdrawer_data','custom_pos_allow_receipt',11,1);

function custom_pos_cart_subtotal_incl_tax($session_response_data){
    
    $session_response_data['setting']['pos_cart_subtotal_incl_tax'] = 'yes';
   return $session_response_data;

}
add_filter('op_get_login_cashdrawer_data','custom_pos_cart_subtotal_incl_tax',10,1);

function sv_add_sku_sorting( $args ) {

	$orderby_value = isset( $_GET['orderby'] ) ? wc_clean( $_GET['orderby'] ) : apply_filters( 'woocommerce_default_catalog_orderby', get_option( 'woocommerce_default_catalog_orderby' ) );

	if ( 'sku' == $orderby_value ) {
		$args['orderby'] = 'meta_value';
		$args['order'] = 'asc'; // lists SKUs alphabetically 0-9, a-z; change to desc for reverse alphabetical
		$args['meta_key'] = '_sku';
	}

	return $args;
}
add_filter( 'woocommerce_get_catalog_ordering_args', 'sv_add_sku_sorting' );

if(!function_exists('pricechangeable_op_product_data'))
{
    function pricechangeable_op_product_data($product_data,$product){
        $product_data['allow_change_price'] = true;
        
        return $product_data;
    }
}
add_filter('op_product_data','pricechangeable_op_product_data',10,2);

/**
 Remove all possible fields
 **/
function wedevs_remove_checkout_fields( $fields ) {

    // Billing fields
    unset( $fields['billing']['billing_company'] );
    unset( $fields['billing']['billing_state'] );
    unset( $fields['billing']['billing_last_name'] );
    unset( $fields['billing']['billing_address_2'] );
    unset( $fields['billing']['billing_city'] );
    unset( $fields['billing']['billing_postcode'] );

    // Order fields
    return $fields;
}
add_filter( 'woocommerce_checkout_fields', 'wedevs_remove_checkout_fields' );

add_filter( 'woocommerce_checkout_fields', 'customize_woo_checkout_fields' );
  
function customize_woo_checkout_fields( $fields ) {
    unset($fields['shipping']['shipping_country']);
	unset( $fields['shipping']['shipping_last_name'] );
    $fields['shipping']['shipping_first_name']['placeholder'] = 'Full name';
    $fields['shipping']['shipping_first_name']['label'] = 'Full name';

	unset($fields['billing']['billing_country']);
    unset( $fields['billing']['billing_last_name'] );
    $fields['billing']['billing_first_name']['placeholder'] = 'Full name';
    $fields['billing']['billing_first_name']['label'] = 'Full name';

    return $fields;

}

add_filter('woocommerce_checkout_fields', 'WPSE0309_woocommerce_checkout_fields');

function WPSE0309_woocommerce_checkout_fields( $fields ) {
     $fields['billing']['billing_email']['required'] = false;

     return $fields;
}

add_action( 'wp_footer', 'auto_update_cart_on_qty_change' );
function auto_update_cart_on_qty_change() {
    if ( is_cart() ) :
    ?>
    <script type="text/javascript">
    (function($){
        $( document.body ).on( 'change input', 'input.qty', function() {
            $('[name=update_cart]').trigger('click');
        });
    })(jQuery);
    </script>
    <?php
    endif;
}