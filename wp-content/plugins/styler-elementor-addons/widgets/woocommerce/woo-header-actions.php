<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Header_Actions extends Widget_Base {
    public function get_name() {
        return 'styler-woo-header-ations';
    }
    public function get_title() {
        return 'WC Header Actions (N)';
    }
    public function get_icon() {
        return 'eicon-cart';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    // Registering Controls
    protected function register_controls() {

        /* HEADER MINICART SETTINGS */
        $this->start_controls_section( 'header_wc_action_section',
            [
                'label' => esc_html__( 'Icon', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'trigger',
            [
                'label' => esc_html__( 'Action Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'cart' => esc_html__( 'Cart Page', 'styler' ),
                    'account' => esc_html__( 'Account Form', 'styler' ),
                    'wl' => esc_html__( 'Wishlist Popup', 'styler' ),
                    'compare' => esc_html__( 'Compare Popup', 'styler' ),
                ],
                'default' => 'cart'
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Redirect URL', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => ''
                ],
                'show_external' => true,
                'condition' => ['trigger' => 'account']
            ]
        );
        $this->add_control( 'icon',
            [
                'label' => esc_html__( 'Icon', 'styler' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
        $this->end_controls_section();
        /* HEADER WC ACTIONS STYLE SETTINGS */
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'control_separator1',
            [
                'label' => esc_html__( 'ICON', 'styler' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control( 'icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 200
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .styler_mini_account_form, {{WRAPPER}} .header_shop_action, {{WRAPPER}} .main-header__cart-btn' => 'width: {{SIZE}}px;height: {{SIZE}}px;',
                ]
            ]
        );
        $this->add_responsive_control( 'icon_fssize',
            [
                'label' => esc_html__( 'Icon Font Size', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn i' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .styler-icon-shopping-cart' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .header_shop_action i' => 'font-size: {{SIZE}}px;',
                ]
            ]
        );
        $this->add_responsive_control( 'icon_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler_mini_account_form,{{WRAPPER}} .header_shop_action, {{WRAPPER}} .main-header__cart-btn' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->add_control( 'control_separator2',
            [
                'label' => esc_html__( 'COUNT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control( 'count_size',
            [
                'label' => esc_html__( 'Count Size', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn span.header_cart_label_text' => 'width: {{SIZE}}px;height: {{SIZE}}px;',
                    '{{WRAPPER}} .header_shop_action .label_count' => 'width: {{SIZE}}px;height: {{SIZE}}px;',
                ]
            ]
        );
        $this->add_responsive_control( 'count_fsize',
            [
                'label' => esc_html__( 'Count Font Size', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn span.header_cart_label_text' => 'font-size: {{SIZE}}px;',
                    '{{WRAPPER}} .header_shop_action .label_count' => 'font-size: {{SIZE}}px;',
                ]
            ]
        );
        $this->add_responsive_control( 'count_top',
            [
                'label' => esc_html__( 'Count Top Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn span.header_cart_label_text' => 'top: {{SIZE}}px;',
                    '{{WRAPPER}} .header_shop_action .label_count' => 'top: {{SIZE}}px;',
                ]
            ]
        );
        $this->add_responsive_control( 'count_right',
            [
                'label' => esc_html__( 'Count Right Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => -50,
                        'max' => 100,
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn span.header_cart_label_text' => 'right: {{SIZE}}px;',
                    '{{WRAPPER}} .header_shop_action .label_count' => 'right: {{SIZE}}px;',
                ]
            ]
        );
        $this->add_control( 'control_separator3',
            [
                'label' => esc_html__( 'ICON and COUNT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->start_controls_tabs('btn_tabs');
        $this->start_controls_tab( 'btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'icon_color',
            [
                'label' => esc_html__( 'Icon Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .styler-icon-shopping-cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header_shop_action i' => 'color: {{VALUE}};',
                    ],
            ]
        );
        $this->add_control( 'icon_bgcolor',
            [
                'label' => esc_html__( 'Icon Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .header_shop_action, {{WRAPPER}} .main-header__cart-btn' => 'background-color: {{VALUE}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .header_shop_action',
            ]
        );
        $this->add_responsive_control( 'icon_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .header_shop_action, {{WRAPPER}} .main-header__cart-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => ''
                ]
            ]
        );
        $this->add_control( 'icon_text_bgcolor',
            [
                'label' => esc_html__( 'Count Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .header_cart_label_text' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .label_count' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control( 'icon_text_color',
            [
                'label' => esc_html__( 'Count Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .header_cart_label_text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .label_count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab('icon_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
        $this->add_control( 'icon_hvrcolor',
            [
                'label' => esc_html__( 'Icon Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn:hover i' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .main-header__cart-btn:hover .styler-icon-shopping-cart' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header_shop_action:hover i' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control( 'icon_hvrbgcolor',
            [
                'label' => esc_html__( 'Icon Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .header_shop_action:hover, {{WRAPPER}} .main-header__cart-btn:hover' => 'background-color: {{VALUE}};'],
            ]
        );
        $this->add_control( 'icon_text_hvrbgcolor',
            [
                'label' => esc_html__( 'Count Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn:hover .header_cart_label_text' => 'background-color: {{VALUE}};',
                    '{{WRAPPER}} .header_shop_action:hover .label_count' => 'background-color: {{VALUE}};',
                ],
            ]
        );
        $this->add_control( 'icon_text_hvrcolor',
            [
                'label' => esc_html__( 'Count Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .main-header__cart-btn:hover .header_cart_label_text' => 'color: {{VALUE}};',
                    '{{WRAPPER}} .header_shop_action:hover .label_count' => 'color: {{VALUE}};',
                ],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'icon_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .header_shop_action:hover,{{WRAPPER}} .main-header__cart-btn:hover',
            ]
        );
        $this->add_responsive_control( 'icon_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .header_shop_action:hover, {{WRAPPER}} .main-header__cart-btn:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render() {
        $settings = $this->get_settings_for_display();

        if ( class_exists( 'WooCommerce' ) ) {

            global $woocommerce;

            if ( 'cart' == $settings[ 'trigger'] ){
                echo'<div class="header-shop-cart">';
                    echo'<a class="styler-cart-btn" href="'.wc_get_cart_url().'">';
                        if ( !empty( $settings['icon']['value'] ) ){
                            Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        } else {
                            echo'<i class="flaticon-shopping-bag"></i>';
                        }
                        echo'<span class="styler-cart-count cart-count">'.esc_html( $count ).'</span>';
                    echo'</a>';
                    echo'<span class="styler-cart-total-price cart-total-price">'.wc_price( $total ).'</span>';
                echo'</div>';
            }
            if ( 'wl' == $settings[ 'trigger'] && shortcode_exists( 'woosw' ) ){

                echo'<div class="header-shop-wishlist">';
                    echo'<a class="styler-wishlist-link woosw-btn" href="#0">';
                        if ( !empty( $settings['icon']['value'] ) ){
                            Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        } else {
                            echo'<i aria-hidden="true" class="flaticon-heart"></i>';
                        }
                        echo'<span class="styler-wishlist-count"></span>';
                    echo'</a>';
                echo'</div>';
            }
            if ( 'compare' == $settings[ 'trigger'] && shortcode_exists( 'woosc' ) ){
                echo'<div class="header-shop-compare">';
                    echo'<a class="open-compare-btn woosc-btn wooscp-btn" href="#0">';
                        if ( !empty( $settings['icon']['value'] ) ){
                            Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                        } else {
                            echo'<i class="flaticon-two-arrows"></i>';
                        }
                        echo'<span class="styler-compare-count"></span>';
                    echo'</a>';
                echo'</div>';
            }
            if ( 'account' == $settings[ 'trigger'] ) {
                    $url = wc_get_page_permalink( 'myaccount' );
                echo'<a class="styler_header_account styler-open-popup" href="#styler_myaccount">';
                    if ( !empty( $settings['icon']['value'] ) ){
                        Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] );
                    } else {
                        echo'<i aria-hidden="true" class="icons far fa-user"></i>';
                    }
                echo'</a>';
                echo'<div class="styler_mini_account_form zoom-anim-dialog mfp-hide" id="styler_myaccount">';
                    if ( !is_admin() && !is_user_logged_in() ) {
                        echo'<div class="account-dropdown">';
                            echo'<div class="account-wrap">';
                                echo'<div class="login-form-head">';
                                    echo'<span class="login-form-title">'.esc_html__( 'Sign in', 'styler' ).'</span>';
                                    echo'<span class="register-form-title">';
                                        echo'<a class="register-link" href="'.$url.'" title="Register">'.esc_html__( 'Create an Account', 'styler' ).'</a>';
                                    echo'</span>';
                                echo'</div>';
                                $redirecturl = !empty( $settings['link']['url'] ) ? array( 'redirect' => $settings['link']['url'] ) : '';
                                woocommerce_login_form( $redirecturl );
                            echo'</div>';
                        echo'</div>';
                    }
                echo'</div>';
            }
        }
    }
}
