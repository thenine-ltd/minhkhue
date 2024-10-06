<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Core\DocumentTypes\PageBase as PageBase;
use Elementor\Modules\Library\Documents\Page as LibraryPageDocument;

if( !defined( 'ABSPATH' ) ) exit;

class Styler_Customizing_Page_Settings {
    use Styler_Helper;
    private static $instance = null;

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new Styler_Customizing_Page_Settings();
        }
        return self::$instance;
    }

    public function __construct(){

        add_action( 'elementor/documents/register_controls',[ $this,'styler_page_settings'], 10 );
    }

    public function styler_page_settings( $document )
    {
        $document->start_controls_section( 'styler_page_header_settings',
            [
                'label' => esc_html__( 'STYLER PAGE HEADER-FOOTER', 'styler' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'styler_page_header_settings_heading',
            [
                'label' => esc_html__( 'STYLER PAGE HEADER', 'styler' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_header_bg_type',
            [
                'label' => esc_html__( 'Header Background Type', 'decoraty' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'multiple' => false,
                'options' => array(
                    '' => esc_html__( 'Select an option', 'decoraty' ),
                    'default' => esc_html__( 'Deafult', 'decoraty' ),
                    'dark' => esc_html__( 'Dark', 'decoraty' ),
                    'trans-light' => esc_html__( 'Transparent Light', 'decoraty' ),
                    'trans-dark' => esc_html__( 'Transparent Dark', 'decoraty' )
                )
            ]
        );
        $document->add_control( 'styler_page_header_template',
            [
                'label' => esc_html__( 'Select Header Template', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->styler_get_elementor_templates()
            ]
        );
        $document->add_control( 'styler_page_footer_settings_heading',
            [
                'label' => esc_html__( 'STYLER PAGE FOOTER', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'styler_page_footer_template',
            [
                'label' => esc_html__( 'Select Footer Template', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->styler_get_elementor_templates()
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'styler_page_header_logo_settings',
            [
                'label' => esc_html__( 'DECORATY PAGE HEADER LOGO', 'decoraty' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'styler_page_header_logo_update',
            [
                'label' => '<div class="elementor-update-preview" style="background-color: #fff;display: block;"><div class="elementor-update-preview-button-wrapper" style="display:block;"><button class="elementor-update-preview-button elementor-button elementor-button-success" style="background: #d30c5c; margin: 0 auto; display:block;">Apply Changes</button></div><div class="elementor-update-preview-title" style="display:block;text-align:center;margin-top: 10px;">Update changes to pages</div></div>',
                'type' => Controls_Manager::RAW_HTML
            ]
        );
        $document->add_control( 'styler_page_header_logo',
            [
                'label' => esc_html__( 'Logo', 'decoraty' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $document->add_responsive_control( 'styler_page_header_logo_max_width',
            [
                'label' => esc_html__( 'Image Max-Width', 'decoraty' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000]
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo:not(.sticky-logo)' => 'max-width: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_responsive_control( 'styler_page_header_logo_max_height',
            [
                'label' => esc_html__( 'Image Max-Height', 'decoraty' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000]
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo:not(.sticky-logo)' => 'max-height: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_control( 'styler_page_header_sticky_logo',
            [
                'label' => esc_html__( 'Sticky Logo', 'decoraty' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $document->add_responsive_control( 'styler_page_header_sticky_logo_max_width',
            [
                'label' => esc_html__( 'Sticky Logo Max-Width', 'decoraty' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000],
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo.sticky-logo' => 'max-width: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_responsive_control( 'styler_page_header_sticky_logo_max_height',
            [
                'label' => esc_html__( 'Sticky Logo Max-Height', 'decoraty' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => ['max' => 1000]
                ],
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .main-logo.sticky-logo' => 'max-height: {{SIZE}}{{UNIT}};' ]
            ]
        );
        $document->add_control( 'styler_page_header_text_logo_color',
            [
                'label' => esc_html__( 'Text Logo Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .nt-logo.header-logo .header-text-logo' => 'color:{{VALUE}};' ]
            ]
        );
        $document->add_control( 'styler_page_header_sticky_text_logo_color',
            [
                'label' => esc_html__( 'Sticky Text Logo Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}}.scroll-start .nt-logo.header-logo .header-text-logo' => 'color:{{VALUE}};' ]
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'styler_page_header_customize_settings',
            [
                'label' => esc_html__( 'DECORATY PAGE HEADER CUSTOMIZE', 'decoraty' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'styler_page_header_bgcolor',
            [
                'label' => esc_html__( 'Header Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.has-default-header-type-default header.styler-header-default,
                    {{WRAPPER}}.has-default-header-type-dark header.styler-header-default,
                    {{WRAPPER}} .styler-header-top-menu-area ul li .submenu,
                    {{WRAPPER}} .styler-header-top-menu-area ul li>.item-shortcode-wrapper,
                    {{WRAPPER}} .styler-header-wc-categories .submenu,
                    {{WRAPPER}} .styler-header-mobile-top,
                    {{WRAPPER}} .styler-header-mobile,
                    {{WRAPPER}}.has-default-header-type-trans header.styler-header-default' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_header_menu_settings',
            [
                'label' => esc_html__( 'Menu Items', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_header_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}}.has-default-header-type-trans:not(.scroll-start) .styler-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}} .styler-header-wc-categories .product_cat,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}} .styler-header-top-menu-area ul li .submenu>li.menu-item>a' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_header_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}}.has-default-header-type-trans:not(.scroll-start) .styler-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}} .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.has-default-header-type-trans:not(.scroll-start) .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}} .current-menu-parent>a,
                    {{WRAPPER}} .current-menu-item>a,
                    {{WRAPPER}} .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}} .styler-header-top-menu-area>ul>li.menu-item>a:hover,
                    {{WRAPPER}} .styler-header-wc-categories .product_cat:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}} .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_header_settings',
            [
                'label' => esc_html__( 'STICKY HEADER', 'decoraty' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'styler_page_sticky_header_bgcolor',
            [
                'label' => esc_html__( 'Sticky Header Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.styler-header-default,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area ul li .submenu,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area ul li>.item-shortcode-wrapper,
                    {{WRAPPER}}.scroll-start .styler-header-wc-categories .submenu,
                    {{WRAPPER}}.scroll-start .styler-header-mobile-top,
                    {{WRAPPER}}.scroll-start .styler-header-mobile,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start header.styler-header-default' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_header_menu_settings',
            [
                'label' => esc_html__( 'Sticky Menu Items', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_sticky_header_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a,
                    {{WRAPPER}}.scroll-start .styler-header-wc-categories .product_cat,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_header_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start .styler-header-top-menu-area>ul>li.menu-item:hover>a,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.has-default-header-type-trans.scroll-start .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.scroll-start .current-menu-parent>a,
                    {{WRAPPER}}.scroll-start .current-menu-item>a,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area>ul>li.menu-item.active>a,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area>ul>li.menu-item>a:hover,
                    {{WRAPPER}}.scroll-start .styler-header-wc-categories .product_cat:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}}.scroll-start .styler-header-top-menu-area ul li .submenu>li.menu-item>a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_header_svg_icons_settings',
            [
                'label' => esc_html__( 'HEADER SVG ICONS', 'decoraty' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'styler_page_header_svg_icons_color',
            [
                'label' => esc_html__( 'Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} header.styler-header-default .styler-svg-icon,
                    {{WRAPPER}} .styler-header-mobile-top .styler-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Counter Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} header.styler-header-default .styler-wc-count,
                    {{WRAPPER}} .styler-header-mobile-top .styler-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_header_svg_counter_color',
            [
                'label' => esc_html__( 'Counter Number Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} header.styler-header-default .styler-wc-count,
                    {{WRAPPER}} .styler-header-mobile-top .styler-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_header_svg_icons_settings',
            [
                'label' => esc_html__( 'Sticky Header Color', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_sticky_header_svg_icons_color',
            [
                'label' => esc_html__( 'Sticky Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.styler-header-default .styler-svg-icon,
                    {{WRAPPER}}.scroll-start .styler-header-mobile-top .styler-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Sticky Counter Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.styler-header-default .styler-wc-count,
                    {{WRAPPER}}.scroll-start .styler-header-mobile-top .styler-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_header_svg_counter_color',
            [
                'label' => esc_html__( 'Sticky Counter Number Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start header.styler-header-default .styler-wc-count,
                    {{WRAPPER}}.scroll-start .styler-header-mobile-top .styler-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'styler_page_mobile_header_customize_settings',
            [
                'label' => esc_html__( 'DECORATY PAGE MOBILE HEADER', 'decoraty' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'styler_page_mobile_header_bgcolor',
            [
                'label' => esc_html__( 'Header Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-top' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_mobile_header_toggle_btn_color',
            [
                'label' => esc_html__( 'Toggle Button Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-top .mobile-toggle' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_mobile_header_toggle_btn_hvrcolor',
            [
                'label' => esc_html__( 'Toggle Button Color ( Hover )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-top .mobile-toggle:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_settings',
            [
                'label' => esc_html__( 'Sticky Header Color', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_bgcolor',
            [
                'label' => esc_html__( 'Header Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile-top' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_toggle_btn_color',
            [
                'label' => esc_html__( 'Toggle Button Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile-top .mobile-toggle' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_toggle_btn_hvrcolor',
            [
                'label' => esc_html__( 'Toggle Button Color ( Hover )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile-top .mobile-toggle:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_mobile_header_svg_icons_settings',
            [
                'label' => esc_html__( 'HEADER SVG ICONS', 'decoraty' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'styler_page_mobile_header_svg_icons_color',
            [
                'label' => esc_html__( 'Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-top .styler-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_mobile_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Counter Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-top .styler-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_mobile_header_svg_counter_color',
            [
                'label' => esc_html__( 'Counter Number Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-top .styler-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_svg_icons_settings',
            [
                'label' => esc_html__( 'Sticky Header Color', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_svg_icons_color',
            [
                'label' => esc_html__( 'Sticky Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile-top .styler-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Sticky Counter Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile-top .styler-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_mobile_header_svg_counter_color',
            [
                'label' => esc_html__( 'Sticky Counter Number Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile-top .styler-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->end_controls_section();

        $document->start_controls_section( 'styler_page_slide_menu_customize_settings',
            [
                'label' => esc_html__( 'DECORATY PAGE MOBILE SLIDE MENU', 'decoraty' ),
                'tab' => Controls_Manager::TAB_SETTINGS
            ]
        );
        $document->add_control( 'styler_page_slide_menu_close_btn_bgcolor',
            [
                'label' => esc_html__( 'Close Button Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .styler-panel-close.no-bar,
                    {{WRAPPER}} .styler-header-mobile .styler-panel-close' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_close_btn_color',
            [
                'label' => esc_html__( 'Close Button Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .styler-panel-close-button:before,
                    {{WRAPPER}} .styler-header-mobile .styler-panel-close-button:after,
                    {{WRAPPER}} .styler-header-mobile .styler-panel-close.no-bar:before,
                    {{WRAPPER}} .styler-header-mobile .styler-panel-close.no-bar:after' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .styler-header-mobile .styler-header-mobile-content .action-content' => 'background-color:transparent;',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_minibar_bgcolor',
            [
                'label' => esc_html__( 'Minibar Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .styler-header-mobile-sidebar' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_minibar_svg_icons_color',
            [
                'label' => esc_html__( 'SVG Icon Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .styler-svg-icon' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_minibar_svg_icons_hvrbgcolor',
            [
                'label' => esc_html__( 'SVG Icon Background Color ( Active )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .sidebar-top-action .top-action-btn.active' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_minibar_svg_icons_hvrcolor',
            [
                'label' => esc_html__( 'SVG Icon Color ( Active )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .sidebar-top-action .top-action-btn.active' => 'fill:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_minibar_svg_counter_bgcolor',
            [
                'label' => esc_html__( 'Icon Counter Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .styler-wc-count' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_minibar_svg_counter_color',
            [
                'label' => esc_html__( 'Icon Counter Number Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .styler-wc-count' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_items_settings',
            [
                'label' => esc_html__( 'Menu Items', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_slide_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}} .styler-header-mobile .account-area li.menu-item a' => 'color:{{VALUE}};'
                ]
            ]
        );

        $document->add_control( 'styler_page_slide_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}} .styler-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}} .styler-header-mobile .account-area li.menu-item a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_slide_menu_settings',
            [
                'label' => esc_html__( 'STICKY HEADER', 'decoraty' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $document->add_control( 'styler_page_sticky_slide_menu_bgcolor',
            [
                'label' => esc_html__( 'Sticky Header Background Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile' => 'background-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_menu_back_brdcolor',
            [
                'label' => esc_html__( 'Border Separator Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .sliding-menu .sliding-menu__back:after' => 'border-bottom-color:{{VALUE}};',
                    '{{WRAPPER}} .styler-sidemenu-lang-switcher' => 'border-top-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_slide_menu_items_settings',
            [
                'label' => esc_html__( 'Sticky Menu Items', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_sticky_slide_menu_item_color',
            [
                'label' => esc_html__( 'Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu-inner li a,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li .sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.current-menu-parent>.sliding-menu__nav,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__back:before,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__nav:before,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .account-area li.menu-item a' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_slide_menu_item_hvrcolor',
            [
                'label' => esc_html__( 'Color ( Hover/Active )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.current-menu-item>.sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.current-menu-item>a:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li a:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li.active a,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu li .sliding-menu__nav:hover,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__back:hover:before,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .sliding-menu .sliding-menu__nav:hover:before,
                    {{WRAPPER}}.scroll-start .styler-header-mobile .account-area li.menu-item a:hover' => 'color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_sticky_slide_menu_back_brdcolor',
            [
                'label' => esc_html__( 'Border Separator Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}}.scroll-start .sliding-menu .sliding-menu__back:after' => 'border-bottom-color:{{VALUE}};',
                    '{{WRAPPER}}.scroll-start .styler-sidemenu-lang-switcher' => 'border-top-color:{{VALUE}};'
                ]
            ]
        );
        $document->add_control( 'styler_page_minibar_social_settings',
            [
                'label' => esc_html__( 'SOCIAL ICONS', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_minibar_social_color',
            [
                'label' => esc_html__( 'Minibar Social Icon Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .sidebar-bottom-socials a' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_minibar_social_hvrcolor',
            [
                'label' => esc_html__( 'Minibar Social Icon Color ( Hover )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .sidebar-bottom-socials a:hover' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_title_color_settings',
            [
                'label' => esc_html__( 'PANEL WOOCOMMERCE', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_top_title_color',
            [
                'label' => esc_html__( 'Top Title Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .panel-top-title' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_top_title_brdcolor',
            [
                'label' => esc_html__( 'Top Title Border Bottom Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .panel-top-title:after' => 'border-bottom-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_product_title_color',
            [
                'label' => esc_html__( 'Product Title Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .styler-content-info .product-name' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_product_stock_color',
            [
                'label' => esc_html__( 'Product Stock Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .styler-content-info .product-stock' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_product_addtocart_color',
            [
                'label' => esc_html__( 'Add to Cart Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .styler-content-item .styler-content-info .styler-btn-small' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_product_trash_icon_color',
            [
                'label' => esc_html__( 'Trash Icon Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .styler-content-item .styler-svg-icon.mini-icon' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_product_subtotal_color',
            [
                'label' => esc_html__( 'Cart Subtotal Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-area .cart-total-price' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_product_text_color',
            [
                'label' => esc_html__( 'Extra Text Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .minicart-extra-text' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_cart_buttons_settings',
            [
                'label' => esc_html__( 'Buttons', 'decoraty' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_buttons_color',
            [
                'label' => esc_html__( 'Buttons Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-bottom-btn .styler-btn' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_buttons_hvrcolor',
            [
                'label' => esc_html__( 'Buttons Color ( Hover )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-bottom-btn .styler-btn:hover' => 'color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_buttons_bgcolor',
            [
                'label' => esc_html__( 'Buttons Backgroud Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-bottom-btn .styler-btn' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_buttons_hvrbgcolor',
            [
                'label' => esc_html__( 'Buttons Backgroud Color ( Hover )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-bottom-btn .styler-btn:hover' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_buttons_brdcolor',
            [
                'label' => esc_html__( 'Buttons Border Color', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-bottom-btn .styler-btn' => 'border-color:{{VALUE}};',
                ]
            ]
        );
        $document->add_control( 'styler_page_slide_left_panel_buttons_hvrbrdcolor',
            [
                'label' => esc_html__( 'Buttons Border Color ( Hover )', 'decoraty' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-header-mobile-content .cart-bottom-btn .styler-btn:hover' => 'border-color:{{VALUE}};',
                ]
            ]
        );
        $document->end_controls_section();

    }

    public function styler_add_custom_css_to_page_settings( $page )
    {

        if ( isset($page) && $page->get_id() > "" ){

            $nt_post_type = false;
            $nt_post_type = get_post_type($page->get_id());

            if ( $nt_post_type == 'page' || $nt_post_type == 'revision' ) {

                $page->start_controls_section( 'header_custom_css_controls_section',
                    [
                        'label' => esc_html__( 'STYLER PAGE CUSTOM CSS', 'styler' ),
                        'tab' => Controls_Manager::TAB_SETTINGS
                    ]
                );
                $page->add_control( 'styler_page_custom_css',
                    [
                        'label' => esc_html__( 'Custom CSS', 'styler' ),
                        'type' => Controls_Manager::CODE,
                        'language' => 'css',
                        'rows' => 20
                    ]
                );
                $page->end_controls_section();
            }
        }
    }

    public function styler_page_registered_nav_menus()
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
Styler_Customizing_Page_Settings::get_instance();
