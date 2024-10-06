<?php

/*
** theme options panel and metabox settings
** will change some parts of theme via custom style
*/

function styler_custom_css()
{

  // stop on admin pages
    if (is_admin()) {
        return false;
    }

    // Redux global
    global $styler;

    $is_right = is_rtl() ? 'right' : 'left';
    $is_left = is_rtl() ? 'left' : 'right';
    /* CSS to output */
    $theCSS = '';

    /*************************************************
    ## HEADER SETTINGS
    *************************************************/

    if ( styler_settings('content_width_md', '') ) {
        $theCSS .= '@media (min-width: 1024px){
        .styler-container.container,
        .styler-container-xl.container-xl {
            max-width: '.styler_settings('content_width_md', '').'px;
        }}';
    }
    if ( styler_settings('content_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){
        .styler-container.container,
        .styler-container-xl.container-xl {
            max-width: '.styler_settings('content_width', '').'px;
        }}';
    }

    if ( styler_settings('quick_view_width_sm', '') ) {
        $theCSS .= '@media (min-width: 1024px){
        .styler-quickview-wrapper {
            max-width: '.styler_settings('quick_view_width_sm', '').'px;
        }}';
    }
    if ( styler_settings('quick_view_width', '') ) {
        $theCSS .= '@media (min-width: 1200px){
        .styler-quickview-wrapper {
            max-width: '.styler_settings('quick_view_width', '').'px;
        }}';
    }
    if ( styler_settings('quick_shop_width', '') ) {
        $theCSS .= '@media (min-width: 1024px){
        .styler-quickshop-wrapper {
            max-width: '.styler_settings('quick_shop_width', '').'px;
        }}';
    }
    if ( styler_settings('quick_shop_width_sm', '') ) {
        $theCSS .= '@media (min-width: 1200px){
        .styler-quickshop-wrapper {
            max-width: '.styler_settings('quick_shop_width_sm', '').'px;
        }}';
    }

    if ( styler_settings('header_height', '') ) {
        $theCSS .= '.header-spacer,
        .styler-header-top-menu-area>ul>li.menu-item {
            height: '.styler_settings('header_height', '').'px;
        }';
    }
    if ( styler_settings('header_right_item_spacing', '') ) {
        $theCSS .= '.styler-header-top-right .styler-header-default-inner>div:not(:first-child) {
            margin-'.$is_right.': '.styler_settings('header_right_item_spacing', '').'px;
        }';
    }
    if ( styler_settings('header_left_item_spacing', '') ) {
        $theCSS .= '.styler-header-top-left .styler-header-default-inner>div:not(:last-child) {
            margin-'.$is_left.': '.styler_settings('header_right_item_spacing', '').'px;
        }';
    }

    if ( styler_settings('header_buttons_spacing', '') ) {
        $theCSS .= '.styler-header-default .top-action-btn {
            margin-'.$is_right.': '.styler_settings('header_buttons_spacing', '').'px;
        }';
    }
    if ( styler_settings('sidebar_menu_content_width', '') ) {
        $theCSS .= '.styler-header-mobile {
            max-width: '.styler_settings('sidebar_menu_content_width', '').'px;
        }';
    }
    if ( styler_settings('sidebar_menu_bar_width', '') ) {
        $theCSS .= '.styler-header-mobile-sidebar {
            min-width: '.styler_settings('sidebar_menu_bar_width', '').'px;
        }';
    }
    // logo size
    if ( styler_settings('logo_size', '') ) {
        $theCSS .= '.nt-logo img {
            max-width: '.styler_settings('logo_size', '').'px;
        }';
    }
    if ( styler_settings('sticky_logo_size', '') ) {
        $theCSS .= '.nt-logo img.sticky-logo {
            max-width: '.styler_settings('sticky_logo_size', '').'px;
        }';
    }
    if ( styler_settings('mobile_logo_size', '') ) {
        $theCSS .= '.nt-logo img.mobile-menu-logo {
            max-width: '.styler_settings('mobile_logo_size', '').'px;
        }';
    }
    if ( styler_settings('sidebar_logo_size', '') ) {
        $theCSS .= '.styler-header-mobile-sidebar-logo .nt-logo img {
            max-width: '.styler_settings('sidebar_logo_size', '').'px;
        }';
    }

    /*************************************************
    ## PRELOADER SETTINGS
    *************************************************/
    if ( '0' != styler_settings('preloader_visibility') ) {

        $pretype = styler_settings('pre_type', 'default');
        $prebg = styler_settings('pre_bg', '#fff');
        $prebg = $prebg ? $prebg : '#f1f1f1';
        $spinclr = styler_settings('pre_spin', '#000');
        $spinclr = $spinclr ? $spinclr : '#000';
        if ( 'default' == $pretype ) {
            $theCSS .= 'body.dark .pace, body.light .pace { background-color: '. esc_attr( $spinclr ) .';}';
            $theCSS .= '#preloader:after, #preloader:before{ background-color:'. esc_attr( $prebg ) .';}';
        }

        $theCSS .= 'div#nt-preloader {background-color: '. esc_attr($prebg) .';overflow: hidden;background-repeat: no-repeat;background-position: center center;height: 100%;left: 0;position: fixed;top: 0;width: 100%;z-index: 9999999;}';
        $spin_rgb = styler_hex2rgb($spinclr);

        if ('01' == $pretype) {
            $theCSS .= '.loader01 {width: 56px;height: 56px;border: 8px solid '. $spinclr .';border-right-color: transparent;border-radius: 50%;position: relative;animation: loader-rotate 1s linear infinite;top: 50%;margin: -28px auto 0; }.loader01::after {content: "";width: 8px;height: 8px;background: '. $spinclr .';border-radius: 50%;position: absolute;top: -1px;left: 33px; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('02' == $pretype) {
            $theCSS .= '.loader02 {width: 56px;height: 56px;border: 8px solid rgba('. $spin_rgb .', 0.25);border-top-color: '. $spinclr .';border-radius: 50%;position: relative;animation: loader-rotate 1s linear infinite;top: 50%;margin: -28px auto 0; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('03' == $pretype) {
            $theCSS .= '.loader03 {width: 56px;height: 56px;border: 8px solid transparent;border-top-color: '. $spinclr .';border-bottom-color: '. $spinclr .';border-radius: 50%;position: relative;animation: loader-rotate 1s linear infinite;top: 50%;margin: -28px auto 0; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('04' == $pretype) {
            $theCSS .= '.loader04 {width: 56px;height: 56px;border: 2px solid rgba('. $spin_rgb .', 0.5);border-radius: 50%;position: relative;animation: loader-rotate 1s ease-in-out infinite;top: 50%;margin: -28px auto 0; }.loader04::after {content: "";width: 10px;height: 10px;border-radius: 50%;background: '. $spinclr .';position: absolute;top: -6px;left: 50%;margin-left: -5px; }@keyframes loader-rotate {0% {transform: rotate(0); }100% {transform: rotate(360deg); } }';
        }
        if ('05' == $pretype) {
            $theCSS .= '.loader05 {width: 56px;height: 56px;border: 4px solid '. $spinclr .';border-radius: 50%;position: relative;animation: loader-scale 1s ease-out infinite;top: 50%;margin: -28px auto 0; }@keyframes loader-scale {0% {transform: scale(0);opacity: 0; }50% {opacity: 1; }100% {transform: scale(1);opacity: 0; } }';
        }
        if ('06' == $pretype) {
            $theCSS .= '.loader06 {width: 56px;height: 56px;border: 4px solid transparent;border-radius: 50%;position: relative;top: 50%;margin: -28px auto 0; }.loader06::before {content: "";border: 4px solid rgba('. $spin_rgb .', 0.5);border-radius: 50%;width: 67.2px;height: 67.2px;position: absolute;top: -9.6px;left: -9.6px;animation: loader-scale 1s ease-out infinite;animation-delay: 1s;opacity: 0; }.loader06::after {content: "";border: 4px solid '. $spinclr .';border-radius: 50%;width: 56px;height: 56px;position: absolute;top: -4px;left: -4px;animation: loader-scale 1s ease-out infinite;animation-delay: 0.5s; }@keyframes loader-scale {0% {transform: scale(0);opacity: 0; }50% {opacity: 1; }100% {transform: scale(1);opacity: 0; } }';
        }
        if ('07' == $pretype) {
            $theCSS .= '.loader07 {width: 16px;height: 16px;border-radius: 50%;position: relative;animation: loader-circles 1s linear infinite;top: 50%;margin: -8px auto 0; }@keyframes loader-circles {0% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.05), 19px -19px 0 0 rgba('. $spin_rgb .', 0.1), 27px 0 0 0 rgba('. $spin_rgb .', 0.2), 19px 19px 0 0 rgba('. $spin_rgb .', 0.3), 0 27px 0 0 rgba('. $spin_rgb .', 0.4), -19px 19px 0 0 rgba('. $spin_rgb .', 0.6), -27px 0 0 0 rgba('. $spin_rgb .', 0.8), -19px -19px 0 0 '. $spinclr .'; }12.5% {box-shadow: 0 -27px 0 0 '. $spinclr .', 19px -19px 0 0 rgba('. $spin_rgb .', 0.05), 27px 0 0 0 rgba('. $spin_rgb .', 0.1), 19px 19px 0 0 rgba('. $spin_rgb .', 0.2), 0 27px 0 0 rgba('. $spin_rgb .', 0.3), -19px 19px 0 0 rgba('. $spin_rgb .', 0.4), -27px 0 0 0 rgba('. $spin_rgb .', 0.6), -19px -19px 0 0 rgba('. $spin_rgb .', 0.8); }25% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.8), 19px -19px 0 0 '. $spinclr .', 27px 0 0 0 rgba('. $spin_rgb .', 0.05), 19px 19px 0 0 rgba('. $spin_rgb .', 0.1), 0 27px 0 0 rgba('. $spin_rgb .', 0.2), -19px 19px 0 0 rgba('. $spin_rgb .', 0.3), -27px 0 0 0 rgba('. $spin_rgb .', 0.4), -19px -19px 0 0 rgba('. $spin_rgb .', 0.6); }37.5% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.6), 19px -19px 0 0 rgba('. $spin_rgb .', 0.8), 27px 0 0 0 '. $spinclr .', 19px 19px 0 0 rgba('. $spin_rgb .', 0.05), 0 27px 0 0 rgba('. $spin_rgb .', 0.1), -19px 19px 0 0 rgba('. $spin_rgb .', 0.2), -27px 0 0 0 rgba('. $spin_rgb .', 0.3), -19px -19px 0 0 rgba('. $spin_rgb .', 0.4); }50% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.4), 19px -19px 0 0 rgba('. $spin_rgb .', 0.6), 27px 0 0 0 rgba('. $spin_rgb .', 0.8), 19px 19px 0 0 '. $spinclr .', 0 27px 0 0 rgba('. $spin_rgb .', 0.05), -19px 19px 0 0 rgba('. $spin_rgb .', 0.1), -27px 0 0 0 rgba('. $spin_rgb .', 0.2), -19px -19px 0 0 rgba('. $spin_rgb .', 0.3); }62.5% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.3), 19px -19px 0 0 rgba('. $spin_rgb .', 0.4), 27px 0 0 0 rgba('. $spin_rgb .', 0.6), 19px 19px 0 0 rgba('. $spin_rgb .', 0.8), 0 27px 0 0 '. $spinclr .', -19px 19px 0 0 rgba('. $spin_rgb .', 0.05), -27px 0 0 0 rgba('. $spin_rgb .', 0.1), -19px -19px 0 0 rgba('. $spin_rgb .', 0.2); }75% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.2), 19px -19px 0 0 rgba('. $spin_rgb .', 0.3), 27px 0 0 0 rgba('. $spin_rgb .', 0.4), 19px 19px 0 0 rgba('. $spin_rgb .', 0.6), 0 27px 0 0 rgba('. $spin_rgb .', 0.8), -19px 19px 0 0 '. $spinclr .', -27px 0 0 0 rgba('. $spin_rgb .', 0.05), -19px -19px 0 0 rgba('. $spin_rgb .', 0.1); }87.5% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.1), 19px -19px 0 0 rgba('. $spin_rgb .', 0.2), 27px 0 0 0 rgba('. $spin_rgb .', 0.3), 19px 19px 0 0 rgba('. $spin_rgb .', 0.4), 0 27px 0 0 rgba('. $spin_rgb .', 0.6), -19px 19px 0 0 rgba('. $spin_rgb .', 0.8), -27px 0 0 0 '. $spinclr .', -19px -19px 0 0 rgba('. $spin_rgb .', 0.05); }100% {box-shadow: 0 -27px 0 0 rgba('. $spin_rgb .', 0.05), 19px -19px 0 0 rgba('. $spin_rgb .', 0.1), 27px 0 0 0 rgba('. $spin_rgb .', 0.2), 19px 19px 0 0 rgba('. $spin_rgb .', 0.3), 0 27px 0 0 rgba('. $spin_rgb .', 0.4), -19px 19px 0 0 rgba('. $spin_rgb .', 0.6), -27px 0 0 0 rgba('. $spin_rgb .', 0.8), -19px -19px 0 0 '. $spinclr .'; } }';
        }
        if ('08' == $pretype) {
            $theCSS .= '.loader08 {width: 20px;height: 20px;position: relative;animation: loader08 1s ease infinite;top: 50%;margin: -46px auto 0; }@keyframes loader08 {0%, 100% {box-shadow: -13px 20px 0 '. $spinclr .', 13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 46px 0 rgba('. $spin_rgb .', 0.2), -13px 46px 0 rgba('. $spin_rgb .', 0.2); }25% {box-shadow: -13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 20px 0 '. $spinclr .', 13px 46px 0 rgba('. $spin_rgb .', 0.2), -13px 46px 0 rgba('. $spin_rgb .', 0.2); }50% {box-shadow: -13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 46px 0 '. $spinclr .', -13px 46px 0 rgba('. $spin_rgb .', 0.2); }75% {box-shadow: -13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 20px 0 rgba('. $spin_rgb .', 0.2), 13px 46px 0 rgba('. $spin_rgb .', 0.2), -13px 46px 0 '. $spinclr .'; } }';
        }
        if ('09' == $pretype) {
            $theCSS .= '.loader09 {width: 10px;height: 48px;background: '. $spinclr .';position: relative;animation: loader09 1s ease-in-out infinite;animation-delay: 0.4s;top: 50%;margin: -28px auto 0; }.loader09::after, .loader09::before {content:  "";position: absolute;width: 10px;height: 48px;background: '. $spinclr .';animation: loader09 1s ease-in-out infinite; }.loader09::before {right: 18px;animation-delay: 0.2s; }.loader09::after {left: 18px;animation-delay: 0.6s; }@keyframes loader09 {0%, 100% {box-shadow: 0 0 0 '. $spinclr .', 0 0 0 '. $spinclr .'; }50% {box-shadow: 0 -8px 0 '. $spinclr .', 0 8px 0 '. $spinclr .'; } }';
        }
        if ('10' == $pretype) {
            $theCSS .= '.loader10 {width: 28px;height: 28px;border-radius: 50%;position: relative;animation: loader10 0.9s ease alternate infinite;animation-delay: 0.36s;top: 50%;margin: -42px auto 0; }.loader10::after, .loader10::before {content: "";position: absolute;width: 28px;height: 28px;border-radius: 50%;animation: loader10 0.9s ease alternate infinite; }.loader10::before {left: -40px;animation-delay: 0.18s; }.loader10::after {right: -40px;animation-delay: 0.54s; }@keyframes loader10 {0% {box-shadow: 0 28px 0 -28px '. $spinclr .'; }100% {box-shadow: 0 28px 0 '. $spinclr .'; } }';
        }
        if ('11' == $pretype) {
            $theCSS .= '.loader11 {width: 20px;height: 20px;border-radius: 50%;box-shadow: 0 40px 0 '. $spinclr .';position: relative;animation: loader11 0.8s ease-in-out alternate infinite;animation-delay: 0.32s;top: 50%;margin: -50px auto 0; }.loader11::after, .loader11::before {content:  "";position: absolute;width: 20px;height: 20px;border-radius: 50%;box-shadow: 0 40px 0 '. $spinclr .';animation: loader11 0.8s ease-in-out alternate infinite; }.loader11::before {left: -30px;animation-delay: 0.48s;}.loader11::after {right: -30px;animation-delay: 0.16s; }@keyframes loader11 {0% {box-shadow: 0 40px 0 '. $spinclr .'; }100% {box-shadow: 0 20px 0 '. $spinclr .'; } }';
        }
        if ('12' == $pretype) {
            $theCSS .= '.loader12 {width: 20px;height: 20px;border-radius: 50%;position: relative;animation: loader12 1s linear alternate infinite;top: 50%;margin: -50px auto 0; }@keyframes loader12 {0% {box-shadow: -60px 40px 0 2px '. $spinclr .', -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }25% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 2px '. $spinclr .', 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }50% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 2px '. $spinclr .', 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }75% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 2px '. $spinclr .', 60px 40px 0 0 rgba('. $spin_rgb .', 0.2); }100% {box-shadow: -60px 40px 0 0 rgba('. $spin_rgb .', 0.2), -30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 0 40px 0 0 rgba('. $spin_rgb .', 0.2), 30px 40px 0 0 rgba('. $spin_rgb .', 0.2), 60px 40px 0 2px '. $spinclr .'; } }';
        }
    }

    $root_clr1 = styler_settings( 'theme_clr1' );
    $root_clr2 = styler_settings( 'theme_clr2' );
    $root_clr3 = styler_settings( 'theme_clr3' );
    $root_clr4 = styler_settings( 'theme_clr4' );

    if( $root_clr1 || $root_clr2 || $root_clr3 || $root_clr4 ) {
        $theCSS .= ':root {';
            $theCSS .= $root_clr1 ? '--styler-base: '.$root_clr1.';' : '';
            $theCSS .= $root_clr1 ? '--styler-base-rgb: '.styler_hex2rgb($root_clr1).';' : '';
            $theCSS .= $root_clr2 ? '--styler-primary: '.$root_clr2.';' : '';
            $theCSS .= $root_clr2 ? '--styler-primary-rgb: '.styler_hex2rgb($root_clr2).';' : '';
            $theCSS .= $root_clr3 ? '--styler-black: '.$root_clr3.';' : '';
            $theCSS .= $root_clr3 ? '--styler-black-rgb: '.styler_hex2rgb($root_clr3).';' : '';
            $theCSS .= $root_clr4 ? '--styler-black2: '.$root_clr4.';' : '';
            $theCSS .= $root_clr4 ? '--styler-black2-rgb: '.styler_hex2rgb($root_clr4).';' : '';
        $theCSS .= '}';
    }

    // use page/post ID for page settings
    $page_id = get_the_ID();

    /*************************************************
    ## THEME PAGINATION
    *************************************************/
    // pagination color
    $pag_radius = styler_settings('pagination_border_radius');
    $pag_align  = styler_settings('pagination_alignment');
    $mobile_breakpoint  = styler_settings('mobile_header_breakpoint');

    // pagination border radius
    if ( $pag_radius ) {
        $theCSS .= '.nt-pagination .nt-pagination-item .nt-pagination-link,
        .styler-woocommerce-pagination ul li a,
        .styler-woocommerce-pagination ul li span { border-radius: '. esc_attr( $pag_radius ) .'px; }';
    }
    // pagination border radius
    if ( $pag_align ) {
        $theCSS .= 'body .styler-woocommerce-pagination ul {justify-content: '. esc_attr( $pag_align ) .';}';
    }
    if ( $mobile_breakpoint != 1280 ) {
        $theCSS .= '@media (min-width: '.$mobile_breakpoint.'px) {header.styler-header-default {display: flex;}}';
        $theCSS .= '@media (min-width: '.$mobile_breakpoint.'px) {.styler-header-mobile-top {display: none;}}';
        $theCSS .= '@media (max-width: '.$mobile_breakpoint.'px) {
            header.styler-header-default {display: none;}
            .styler-header-mobile-top {display: flex;}
        }';
    }


    /*************************************************
    ## PAGE METABOX SETTINGS
    *************************************************/
    if ( class_exists( 'WooCommerce' ) ) {
        $shop_hero_tablet_bg = styler_settings( 'shop_hero_tablet_bg', '' );
        if ( !empty( $shop_hero_tablet_bg['url'] ) ) {
            $theCSS .= '@media(max-width:768px){ body #nt-shop-page.nt-shop-page .styler-page-hero {
                background-image:url('.esc_url( $shop_hero_tablet_bg['url'] ).');
            }}';
        }
        $shop_hero_mobile_bg = styler_settings( 'shop_hero_mobile_bg', '' );
        if ( !empty( $shop_hero_mobile_bg['url'] ) ) {
            $theCSS .= '@media(max-width:576px){ body #nt-shop-page.nt-shop-page .styler-page-hero {
                background-image:url('.esc_url( $shop_hero_mobile_bg['url'] ).');
            }}';
        }

        $shop_hero_tablet_height = styler_settings( 'shop_hero_tablet_height', '' );
        if ( !empty( $shop_hero_tablet_height['height'] ) ) {
            $theCSS .= '@media(max-width:768px){ body #nt-shop-page.nt-shop-page .styler-page-hero {
                min-height:'.$shop_hero_tablet_height['height'].';height:auto;
            }}';
        }
        $shop_hero_mobile_height = styler_settings( 'shop_hero_mobile_height', '' );
        if ( !empty( $shop_hero_mobile_height['height'] ) ) {
            $theCSS .= '@media(max-width:576px){ body #nt-shop-page.nt-shop-page .styler-page-hero {
                min-height:'.$shop_hero_mobile_height['height'].';height:auto;
            }}';
        }

        if ( is_product() ) {

            $share_shape_type  = styler_settings( 'single_shop_share_shape_type', '' );
            $popup_cart_maxwidth  = styler_settings( 'product_bottom_popup_cart_maxwidth', '' );
            $summarybg_type_ot = styler_settings( 'single_shop_showcase_bg_type', '' );
            $summarybg_type_mb = get_post_meta( $page_id, 'styler_showcase_bg_type', true );
            $summarybg_type    = $summarybg_type_mb ? $summarybg_type_mb : $summarybg_type_ot;
            $summarybg_type    = apply_filters('styler_showcase_bg_type', $summarybg_type );

            $summarybg_ot   = styler_settings( 'single_shop_showcase_custom_bgcolor', '' );
            $summarybg_mb   = get_post_meta( $page_id, 'styler_showcase_custom_bgcolor', true );
            $summarybg      = $summarybg_mb ? $summarybg_mb : $summarybg_ot;
            $summarybg      = apply_filters('styler_showcase_custom_bgcolor', $summarybg );

            $summarytext_ot = styler_settings( 'single_shop_showcase_custom_textcolor', '' );
            $summarytext_mb = get_post_meta( $page_id, 'styler_showcase_custom_textcolor', true );
            $summarytext    = $summarytext_mb ? $summarytext_mb : $summarytext_ot;
            $summarytext    = apply_filters('styler_showcase_custom_textcolor', $summarytext );
            $active_tab      = styler_settings( 'product_tabs_active_tab', '' );

            $terms_brd_radius = styler_settings( 'selected_variations_terms_brd_radius', '' );
            $disabled_terms_opacity = styler_settings( 'selected_variations_disabled_terms_opacity', '' );
            if ( 'square' == $share_shape_type ) {
                $theCSS .= '.styler-product-summary .styler-product-share a { border-radius: 0; }';
            }
            if ( 'round' == $share_shape_type ) {
                $theCSS .= '.styler-product-summary .styler-product-share a { border-radius: 4px; }';
            }
            if ( !empty( $popup_cart_maxwidth['width'] ) ) {
                $theCSS .= '.postid-'.$page_id.' .styler-product-bottom-popup-cart:not(.relative) { max-width:'.$popup_cart_maxwidth['width'].';left: 50%;transform: translateX(-50%) translateY(0%); }';
            }
            if ( $terms_brd_radius ) {
                $theCSS .= '.postid-'.$page_id.' .styler-selected-variations-terms-wrapper .styler-selected-variations-terms { border-radius:'.esc_attr( $terms_brd_radius ).'px; }';
            }
            if ( $disabled_terms_opacity ) {
                $theCSS .= '.styler-variations .styler-terms .styler-term.styler-disabled {
                    opacity:'.$disabled_terms_opacity.';
                }';
            }
            if ( $active_tab != '' && $active_tab != 'all' ) {
                $theCSS .= '.styler-product-accordion-wrapper .styler-accordion-item'.$active_tab.' .styler-accordion-body {
                    display: block;
                }';
            }
            if ( $active_tab == 'all' ) {
                $theCSS .= '.styler-product-accordion-wrapper .styler-accordion-item .styler-accordion-body {
                    display: block;
                }';
            }
            if ( 'custom' == $summarybg_type ) {
                if ( $summarybg ) {
                    $theCSS .= '.page-'.$page_id.' .styler-product-showcase, .postid-'.$page_id.' .styler-product-showcase.styler-bg-custom { background-color:'.esc_url( $summarybg ).'; }';
                }
                if ( $summarytext ) {
                    $theCSS .= '.styler-product-showcase.styler-bg-custom .styler-summary-item.styler-product-title,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-summary-item.styler-price,
                    .styler-product-showcase.styler-bg-custom .styler-price span.del > span,
                    .styler-product-showcase.styler-bg-custom .styler-summary-item .woocommerce-product-details__short-description,
                    .styler-product-showcase.styler-bg-custom .styler-small-title,
                    .styler-product-showcase.styler-bg-custom .styler-small-title a,
                    .styler-product-showcase.styler-bg-custom .styler-product-view,
                    .styler-product-showcase.styler-bg-custom .styler-product-view span,
                    .styler-product-showcase.styler-bg-custom .styler-estimated-delivery,
                    .styler-product-showcase.styler-bg-custom .styler-estimated-delivery span,
                    .styler-product-showcase.styler-bg-custom a.styler-open-popup,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-product-meta .posted_in,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-product-meta .tagged_as,
                    .styler-product-showcase.styler-bg-custom .quantity-button.plus,
                    .styler-product-showcase.styler-bg-custom .quantity-button.minus,
                    .styler-product-showcase.styler-bg-custom .input-text.qty,
                    .styler-product-showcase.styler-bg-custom .woocommerce-product-details__short-description,
                    .styler-product-showcase.styler-bg-custom .styler-single-product-stock .stock-details span,
                    .styler-product-showcase.styler-bg-custom .styler-breadcrumb li,
                    .styler-product-showcase.styler-bg-custom .styler-breadcrumb li a,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-product-meta .styler-brands a,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-product-meta .posted_in a,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-product-meta .tagged_as a,
                    .styler-product-showcase.styler-bg-custom span.styler-shop-link-icon,
                    .styler-product-showcase.styler-bg-custom .product-nav-link,
                    .styler-product-showcase.styler-bg-custom .styler-product-summary .styler-product-meta .styler-brands {
                        color: '.esc_url( $summarytext ).';
                    }
                    .styler-product-showcase.styler-bg-custom span.styler-shop-link-icon:before,
                    .styler-product-showcase.styler-bg-custom span.styler-shop-link-icon:after {
                        border-color: '.esc_url( $summarytext ).';
                    }';
                }
            }

            $product_header_mb   = get_post_meta( $page_id, 'styler_product_header_type', true );
            if ( 'custom' == $product_header_mb ) {
                $header_bgcolor           = get_post_meta( $page_id, 'styler_product_header_bgcolor', true );
                $menuitem_color           = get_post_meta( $page_id, 'styler_product_header_menuitem_color', true );
                $menuitem_hvrcolor        = get_post_meta( $page_id, 'styler_product_header_menuitem_hvrcolor', true );
                $svgicon_color            = get_post_meta( $page_id, 'styler_product_header_svgicon_color', true );
                $counter_bgcolor          = get_post_meta( $page_id, 'styler_product_header_counter_bgcolor', true );
                $counter_color            = get_post_meta( $page_id, 'styler_product_header_counter_color', true );
                $sticky_header_bgcolor    = get_post_meta( $page_id, 'styler_product_sticky_header_bgcolor', true );
                $sticky_menuitem_color    = get_post_meta( $page_id, 'styler_product_sticky_header_menuitem_color', true );
                $sticky_menuitem_hvrcolor = get_post_meta( $page_id, 'styler_product_sticky_header_menuitem_hvrcolor', true );
                $sticky_svgicon_color     = get_post_meta( $page_id, 'styler_product_sticky_header_svgicon_color', true );
                $sticky_counter_bgcolor   = get_post_meta( $page_id, 'styler_product_sticky_header_counter_bgcolor', true );
                $sticky_counter_color     = get_post_meta( $page_id, 'styler_product_sticky_header_counter_color', true );

                if ( $header_bgcolor ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' header.styler-header-default {
                        background-color:'.esc_url( $header_bgcolor ).'!important;
                    }';
                }
                if ( $menuitem_color ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a,
                    .single-product.postid-'.$page_id.' .styler-header-top-menu-area ul li .submenu>li.menu-item>a {
                        color:'.esc_url( $menuitem_color ).'!important;
                    }';
                }
                if ( $menuitem_hvrcolor ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default .styler-header-top-menu-area>ul>li.menu-item>a:hover,
                    .single-product.postid-'.$page_id.' .styler-header-default .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,
                    .single-product.postid-'.$page_id.' .styler-header-default .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    .single-product.postid-'.$page_id.' .styler-header-default .styler-header-top-menu-area ul li .submenu>li.menu-item.active>a {
                        color:'.esc_url( $menuitem_hvrcolor ).'!important;
                    }';
                }
                if ( $svgicon_color ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default .styler-svg-icon {
                        fill:'.esc_url( $svgicon_color ).'!important;
                        color:'.esc_url( $svgicon_color ).'!important;
                    }';
                }
                if ( $counter_bgcolor ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default .styler-wc-count {
                        background-color:'.esc_url( $counter_bgcolor ).'!important;
                    }';
                }
                if ( $counter_color ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default .styler-wc-count {
                        color:'.esc_url( $counter_color ).'!important;
                    }';
                }
                if ( $sticky_header_bgcolor ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' header.styler-header-default.sticky-start {
                        background-color:'.esc_url( $sticky_header_bgcolor ).'!important;
                    }';
                }
                if ( $sticky_menuitem_color ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-header-top-menu-area>ul>li.menu-item>a,
                    .single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a {
                        color:'.esc_url( $sticky_menuitem_color ).'!important;
                    }';
                }
                if ( $sticky_menuitem_hvrcolor ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-header-top-menu-area>ul>li.menu-item>a:hover,
                    .single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover,
                    .single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    .single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-header-top-menu-area ul li .submenu>li.menu-item.active>a {
                        color:'.esc_url( $sticky_menuitem_hvrcolor ).'!important;
                    }';
                }
                if ( $sticky_svgicon_color ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-svg-icon {
                        fill:'.esc_url( $sticky_svgicon_color ).'!important;
                        color:'.esc_url( $sticky_svgicon_color ).'!important;
                    }';
                }
                if ( $sticky_counter_bgcolor ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-wc-count {
                        background-color:'.esc_url( $sticky_counter_bgcolor ).'!important;
                    }';
                }
                if ( $sticky_counter_color ) {
                    $theCSS .= '.single-product.postid-'.$page_id.' .styler-header-default.sticky-start .styler-wc-count {
                        color:'.esc_url( $sticky_counter_color ).'!important;
                    }';
                }
            }
        }
    }

    /*************************************************
    ## PAGE METABOX SETTINGS
    *************************************************/

    if ( is_page() && ! styler_check_is_elementor() ) {

        $heroimg = wp_get_attachment_image_src( $page_id, 'full' );
        if ( $heroimg ) {
            $theCSS .= '.page-'.$page_id.' .breadcrumb-bg { background-image:url('.esc_url( $heroimg ).'); }';
        }
    }

    $extraCSS = '';
    $extraCSS = apply_filters( 'styler_add_custom_css', $extraCSS );
    $theCSS .= $extraCSS;

    /* Add CSS to style.css */
    wp_register_style('styler-custom-style', false);
    wp_enqueue_style('styler-custom-style');
    wp_add_inline_style('styler-custom-style', $theCSS );
}

add_action('wp_enqueue_scripts', 'styler_custom_css');


// customization on admin pages
function styler_admin_custom_css()
{
    if ( ! is_admin() ) {
        return false;
    }

    /* CSS to output */
    $theCSS = '';
    $is_right = is_rtl() ? 'right' : 'left';
    $is_left = is_rtl() ? 'left' : 'right';
    $theCSS .= '
    #setting-error-tgmpa, #setting-error-styler {
        display: block !important;
    }
    .menu-item.menu-item-depth-0 .et_menu_options .styler-field-link-shortcode,
    .menu-item.menu-item-depth-0 .et_menu_options .styler-field-link-hidetitle,
    .menu-item.menu-item-depth-0 .et_menu_options .styler-field-link-title,
    .menu-item.menu-item-depth-0 .et_menu_options .styler-field-link-label,
    .menu-item.menu-item-depth-0 .et_menu_options .styler-field-link-labelcolor,
    .menu-item.menu-item-depth-0 .et_menu_options .styler-field-link-image,
    .menu-item:not(.menu-item-depth-0) .et_menu_options .styler-field-link-mega,
    .menu-item:not(.menu-item-depth-0) .et_menu_options .styler-field-link-mega-columns{
        display: none;
    }
    .styler_menu_options .small-tag {
        font-size: 10px;
        font-weight: 400;
        position: relative;
        top: -2px;
        display: inline-block;
        margin-'.$is_right.': 4px;
        color: #fff;
        background-color: #bbb;
        line-height: 1;
        padding: 3px 6px;
        border-radius: 3px;
    }
    .styler-panel-heading {
        padding: 10px 12px;
        border-bottom: 1px solid #ddd;
    }
    .styler-panel-subheading {
        padding: 0px 12px;
    }
    .styler-panel-divider {
        margin: 10px 0;
        border-bottom: 1px solid #ddd;
        display: block;
    }
    .reduxd_field_th {
        color: #191919;
        font-weight: 700;
    }
    .redux-container .redux-main .form-table tr {
        position: relative;
    }
    .redux-container .redux-main .form-table tr.hide-field {
        position: relative;
        min-height: 40px;
    }
    .toggle-field {
        position: absolute;
        top: 10px;
        right: 0;
        display: flex;
        align-items: center;
        justify-content: center;
        width: 20px;
        height: 20px;
        cursor: pointer;
        background: #fff;
        border: 1px solid #7e8993;
    }
    .toggle-field.hide-field {
        background: #000;
        color: #fff;
    }
    .toggle-field.hide-field i {
        transform:rotate(180deg);
    }
    fieldset#styler-shop_hero_custom_layout,
    fieldset#styler-shop_loop_product_layouts {
        padding-right: 50px;
    }
    fieldset#shop_hero_custom_layout,
    fieldset#shop_loop_product_layouts {
        display: flex;
    }
    fieldset#shop_hero_custom_layout {
        flex-wrap: wrap;
    }
    fieldset#shop_hero_custom_layout ul,
    fieldset#shop_loop_product_layouts ul {
        flex: auto;
        float: none;
        min-width: 200px;
        width: auto!important;
    }
    fieldset#shop_hero_custom_layout ul {
        min-width: auto;
    }
    @media screen and (max-width: 768px) {
        fieldset#shop_hero_custom_layout,
        fieldset#shop_loop_product_layouts {
            flex-wrap: wrap;
            flex-direction: column;
        }
        fieldset#shop_hero_custom_layout ul,
        fieldset#shop_loop_product_layouts ul#shop_loop_product_layouts_hide {
            margin-top: 15px!important;
        }
    }
    ul#shop_loop_product_layouts_hide .shop_loop_product_layouts_inner {
        max-height: 400px;
        overflow: auto;
        display: flex;
        flex-wrap: wrap;
    }
    .shop_loop_product_layouts_inner li {
        flex: auto;
        margin: 10px 5px 10px 5px;
    }
    .redux-container .redux-main #styler-shop_product_type img {
        max-width: 175px!important;
    }
    #styler-shop_product_type.redux-container-image_select ul.redux-image-select li {
        padding: 15px!important;
    }
    #styler-shop_product_type.redux-container-image_select ul.redux-image-select{
        margin-left: -15px!important;
        margin-right: -15px!important;
    }
    input#styler_badge_color {
        margin: 0!important;
    }
    p.form-field.styler_wc_cat_banner_field span {
        display: block;
        max-width: 95%;
    }
    td.styler_cat_banner.column-styler_cat_banner {
        position: relative;
    }
    .styler_cat_banner span.wc-banner:before {
        font-family: Dashicons;
        font-weight: 400;
        text-transform: none;
        line-height: 1;
        -webkit-font-smoothing: antialiased;
        text-indent: 0px;
        color: #2271b1;
        content: "\f155";
        font-variant: normal;
        margin: 0px;
        font-size: 18px;
    }
    span.wc-banner {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        width: 100%;
        height: 100%;
        position: absolute;
        top: 0;
        left: 25px;
        text-align: center;
    }
    .woocommerce_options_panel .styler-color-field-wrapper .wp-picker-input-wrap label{
        margin: 0;
        width: auto;
    }
    th#taxonomy-styler_product_brands,th#woosw {
        width: 11%!important;
    }
    .image-preview-wrapper {
        max-width: 100px;
    }
    .image-preview-wrapper img {
        max-width: 100%;
    }
    .redux-main .description {
        display: block;
        font-weight: normal;
    }
    li#toplevel_page_wpclever,
    #redux-connect-message {
        opacity: 0 !important;
        display: none !important;
        visibility : hidden;
    }
    .redux-main .wp-picker-container .wp-color-result-text {
        line-height: 28px;
    }
    .redux-container .redux-main .input-append .add-on,
    .redux-container .redux-main .input-prepend .add-on {
        line-height: 22px;
    }
    .redux-main .redux-field-container {
        max-width: calc(100% - 40px);
    }
  	#customize-controls img {
  		max-width: 75%;
  	}
    .redux-info-desc .thm-btn:hover {
        color: #000;
    }
    .redux-info-desc .thm-btn i {
        margin-'.$is_right.': 10px;
    }
    .redux-info-desc .thm-btn {
        -moz-user-select: none;
        border: medium none;
        border-radius: 4px;
        color: #fff;
        background-color: #2271b1;
        cursor: pointer;
        display: flex;
        justify-content: center;
        align-items: center;
        text-decoration: none;
        height: 40px;
        min-width: 160px;
        font-size: 14px;
        font-weight: 700;
        letter-spacing: 0;
        line-height: 1;
        margin-bottom: 0;
        text-align: center;
        text-transform: uppercase;
        touch-action: manipulation;
        transition: all 0.3s ease 0s;
        vertical-align: middle;
        white-space: nowrap;
    }
    .styler-column-item {
        display: inline-block;
        width: 40px;
        height: 40px;
        background-color: #eee;
        box-sizing: border-box;
        border: 1px solid #eee;
    }
    #styler_swatches_image_thumbnail {
        float: left;
        margin-'.$is_left.': 10px;
    }
    #styler_swatches_image_wrapper {
        line-height: 60px;
    }
    li.menu-item.mega-parent .styler-field-link-label,
    li.menu-item.mega-parent .styler-field-link-labelcolor,
    li.menu-item.mega-parent .styler-field-link-image,
    li.menu-item:not(.menu-item-depth-0):not(.mega-parent) .styler-field-link-mega,
    li.menu-item:not(.menu-item-depth-0) .styler-field-link-mega-columns {
        display: none;
    }
    span.styler-mega-menu-item-title,
    span.styler-mega-column-menu-item-title {
        margin-'.$is_right.': 10px;
        padding: 2px 4px;
        background: #2271b1;
        color: #fff;
        line-height: 1;
        font-size: 9px;
    }
    .styler-panel-subheading.menu-customize:not(.show_if_header_custom),
    .styler_product_header_bgcolor_field:not(.show_if_header_custom),
    .styler_product_header_menuitem_color_field:not(.show_if_header_custom),
    .styler_product_header_menuitem_hvrcolor_field:not(.show_if_header_custom),
    .styler_product_header_svgicon_color_field:not(.show_if_header_custom),
    .styler_product_header_counter_bgcolor_field:not(.show_if_header_custom),
    .styler_product_header_counter_color_field:not(.show_if_header_custom),
    .styler_product_sticky_header_type_field:not(.show_if_header_custom),
    .styler_product_sticky_header_bgcolor_field:not(.show_if_header_custom),
    .styler_product_sticky_header_menuitem_color_field:not(.show_if_header_custom),
    .styler_product_sticky_header_menuitem_hvrcolor_field:not(.show_if_header_custom),
    .styler_product_sticky_header_svgicon_color_field:not(.show_if_header_custom),
    .styler_product_sticky_header_counter_bgcolor_field:not(.show_if_header_custom),
    .styler_product_sticky_header_counter_color_field:not(.show_if_header_custom) {
        display: none;
    }';
    // end $theCSS

    /* Add CSS to style.css */
    wp_register_style('styler-admin-custom-style', false);
    wp_enqueue_style('styler-admin-custom-style');
    wp_add_inline_style('styler-admin-custom-style', $theCSS);
}
add_action('admin_enqueue_scripts', 'styler_admin_custom_css');
