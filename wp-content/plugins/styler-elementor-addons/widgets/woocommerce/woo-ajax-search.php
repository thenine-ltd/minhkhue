<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Ajax_Search extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-ajax-search';
    }
    public function get_title() {
        return 'WC Ajax Search (N)';
    }
    public function get_icon() {
        return 'eicon-site-search';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'wc', 'woo', 'product', 'search'  ];
    }
    // Registering Controls
    protected function register_controls() {

        /* HEADER MINICART SETTINGS */
        $this->start_controls_section( 'general_section',
            [
                'label' => esc_html__( 'Style', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'action',
            [
                'label' => esc_html__( 'Search Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'ajax',
                'options' => [
                    'cat' => esc_html__( 'Category Form', 'styler' ),
                    'ajax' => esc_html__( 'Simple Ajax Form', 'styler' )
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('style_section',
            [
                'label'=> esc_html__( 'Style', 'styler' ),
                'tab' => Controls_Manager::TAB_STYLE,
                'condition' => [ 'action' => 'ajax' ]
            ]
        );
        $this->add_responsive_control( 'form_maxwidth',
            [
                'label' => esc_html__( 'Min Width ( % )', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 2000,
                        'step' => 1
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .styler-asform' => 'max-width: {{SIZE}}%;']
            ]
        );
        $this->add_responsive_control( 'form_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-asform' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'form_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-asform' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->start_controls_tabs( 'slider_nav_tabs');
        $this->start_controls_tab( 'slider_nav_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-asform',
            ]
        );
        $this->add_control( 'form_bgclr',
           [
               'label' => esc_html__( 'Background Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .styler-asform input.styler-as' => 'background-color: {{VALUE}};']
           ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'slider_nav_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-asform:hover,{{WRAPPER}} .styler-asform:focus',
            ]
        );
        $this->add_control( 'form_hvrbgclr',
           [
               'label' => esc_html__( 'Background Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .styler-asform:hover input.styler-as,{{WRAPPER}} .styler-asform:focus input.styler-as' => 'background-color: {{VALUE}};']
           ]
        );
        $this->add_control( 'form_hvrclr',
           [
               'label' => esc_html__( 'Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .styler-asform:hover input.styler-as,{{WRAPPER}} .styler-asform:focus input.styler-as' => 'color: {{VALUE}};']
           ]
        );
        $this->add_control( 'submit_bgclr',
           [
               'label' => esc_html__( 'Submit Background Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .styler-asform .header-search-wrap form button' => 'background-color: {{VALUE}};']
           ]
        );
        $this->add_control( 'submit_iconclr',
           [
               'label' => esc_html__( 'Submit Icon Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => [
                   '{{WRAPPER}} .styler-asform .header-search-wrap form button' => 'color: {{VALUE}};',
                   '{{WRAPPER}} .styler-asform .header-search-wrap form button svg' => 'fill: {{VALUE}};',
               ]
           ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings = $this->get_settings_for_display();

        echo'<div class="styler_as_form_wrapper form-type-'.$settings['action'].'">';
            if ( 'cat' == $settings['action'] && function_exists( 'styler_wc_category_search_form' ) ) {
                styler_wc_category_search_form();
            } else {
                echo do_shortcode('[styler_wc_ajax_search]');
            }
        echo'</div>';
    }
}
