<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Home_Slider extends Widget_Base {
    public function get_name() {
        return 'styler-home-slider';
    }
    public function get_title() {
        return 'Home Main Slider (N)';
    }
    public function get_icon() {
        return 'eicon-carousel';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    public function get_script_depends() {
        return [ 'styler-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'items_settings',
            [
                'label' => esc_html__('Slide Items', 'styler'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $repeater = new Repeater();
        $repeater->add_control( 'iautoplay_delay',
            [
                'label' => esc_html__( 'Item Autoplay Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 50000,
                'step' => 100,
                'default' => ''
            ]
        );
        $repeater->add_control( 'anim_in',
            [
                'label' => esc_html__( 'Content Items Entrance Animation', 'styler' ),
                'type' => Controls_Manager::ANIMATION,
                'prefix_class' => '',
            ]
        );
        $repeater->add_control( 'hanim_delay',
            [
                'label' => esc_html__( 'Heading Animation Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 500,
                'condition' => [ 'anim_in!' => '' ]
            ]
        );
        $repeater->add_control( 'descanim_delay',
            [
                'label' => esc_html__( 'Description Animation Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 750,
                'condition' => [ 'anim_in!' => '' ]
            ]
        );
        $repeater->add_control( 'btnanim_delay',
            [
                'label' => esc_html__( 'Buttons Animation Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 10,
                'default' => 1000,
                'condition' => [ 'anim_in!' => '' ]
            ]
        );
        $repeater->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'bg_img',
                'label' => esc_html__( 'Background', 'personx' ),
                'types' => ['classic'],
                'selector' => '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-inner'
            ]
        );
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'desc',
            [
                'label' => esc_html__( 'Description', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title',
            [
                'label' => esc_html__( 'Button Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'link',
            [
                'label' => esc_html__( 'Button Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $repeater->add_control( 'btn_id',
            [
                'label' => esc_html__( 'Button ID', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title2',
            [
                'label' => esc_html__( 'Button 2 Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'link2',
            [
                'label' => esc_html__( 'Button 2 Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $repeater->add_control( 'btn_id2',
            [
                'label' => esc_html__( 'Button 2 ID', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_responsive_control( 'ihorz_alignment',
            [
                'label' => esc_html__( 'Horizontal Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-inner' => 'align-items: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $repeater->add_responsive_control( 'ivert_alignment',
            [
                'label' => esc_html__( 'Vertical Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-inner' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $repeater->add_responsive_control( 'islide_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.styler-slide-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'ioverlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-inner:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ititle_color',
            [
                'label' => esc_html__( 'Title Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-head' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'idesc_color',
            [
                'label' => esc_html__( 'Description Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-text' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_bgcolor',
            [
                'label' => esc_html__( 'Button Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_color',
            [
                'label' => esc_html__( 'Button Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_bgcolor2',
            [
                'label' => esc_html__( 'Button 2 Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link2' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_color2',
            [
                'label' => esc_html__( 'Button 2 Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link2' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'items',
            [
                'label' => esc_html__( 'Items', 'styler' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => 'Image',
                'default' => [
                    [
                        'title' => 'Title 1',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ],
                    [
                        'title' => 'Title 2',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ],
                    [
                        'title' => 'Title 3',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'box_height',
            [
                'label' => esc_html__( 'Slider Height', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 5,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
                'selectors' => [ '{{WRAPPER}} .styler-main-slider' => 'height:{{SIZE}}{{UNIT}};' ]
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Slider Heading Tag ( for SEO )', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => esc_html__( 'h1', 'styler' ),
                    'h2' => esc_html__( 'h2', 'styler' ),
                    'h3' => esc_html__( 'h3', 'styler' ),
                    'h4' => esc_html__( 'h4', 'styler' ),
                    'h5' => esc_html__( 'h5', 'styler' ),
                    'h6' => esc_html__( 'h6', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' ),
                    'span' => esc_html__( 'span', 'styler' )
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'loop',
            [
                'label' => esc_html__( 'Infinite', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'autoplay_delay',
            [
                'label' => esc_html__( 'Autoplay Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 50000,
                'step' => 100,
                'default' => 5000
            ]
        );
        $this->add_control( 'nav',
            [
                'label' => esc_html__( 'Navigation', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'dots',
            [
                'label' => esc_html__( 'Dots', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'speed',
            [
                'label' => esc_html__( 'Speed', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 100,
                'default' => 1000
            ]
        );
        $this->add_control( 'mditems',
            [
                'label' => esc_html__( 'Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'smitems',
            [
                'label' => esc_html__( 'Items Tablet', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control( 'xsitems',
            [
                'label' => esc_html__( 'Items Phone', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('image_style_section',
            [
                'label'=> esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'horz_alignment',
            [
                'label' => esc_html__( 'Horizontal Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .styler-slide-inner' => 'align-items: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'vert_alignment',
            [
                'label' => esc_html__( 'Vertical Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .styler-slide-inner' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'slide_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slide .styler-slide-inner:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'title_divider',
            [
                'label' => esc_html__( 'TITLE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slide-head' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-head'
            ]
        );
        $this->add_control( 'desc_divider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'desc_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slide-text' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-text'
            ]
        );
        $this->add_control( 'btn_divider',
            [
                'label' => esc_html__( 'BUTTON', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-link'
            ]
        );
        $this->start_controls_tabs('styler_btn_tabs');
        $this->start_controls_tab( 'styler_btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-slide-link' => 'color: {{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-slide-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-link',
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-slide-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .styler-slide-link',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('styler_btn_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
         $this->add_control( 'btn_hvr_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-slide-link:hover' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_hvr_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-link:hover',
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hvr_background',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .styler-slide-link:hover',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('navs_style_section',
            [
                'label'=> esc_html__( 'SLIDER NAV STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'nav' => 'yes' ]
            ]
        );
        $this->add_responsive_control( 'navs_size',
            [
                'label' => esc_html__( 'Slider Height', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'navs_icon_size',
            [
                'label' => esc_html__( 'Nav Icon Size', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 200,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrbgcolor',
            [
                'label' => esc_html__( 'Background Color ( Hover )', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_brdcolor',
            [
                'label' => esc_html__( 'Border Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrbrdcolor',
            [
                'label' => esc_html__( 'Border Color ( Hover )', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav:hover' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Icon Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Icon Color ( Hover )', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .slider-btn-nav:hover:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'dots' => 'yes' ]
            ]
        );
        $this->add_responsive_control( 'dots_offset',
            [
                'label' => esc_html__( 'Bottom Offset', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 500,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-pagination-bullets' => 'bottom:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'dots_alignment',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-pagination-bullets' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'dots_space',
            [
                'label' => esc_html__( 'Space', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-horizontal > .swiper-pagination-bullets .swiper-pagination-bullet + .swiper-pagination-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet + .swiper-pagination-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                ]
            ]
        );
        $this->start_controls_tabs( 'dots_nav_tabs');
        $this->start_controls_tab( 'dots_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'dots_bgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before,{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'dots_hover_tab',
            [ 'label' => esc_html__( 'Active', 'styler' ) ]
        );
        $this->add_control( 'dots_hvrbgcolor',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active'
            ]
        );
        $this->add_responsive_control( 'dots_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active:before,{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        $slider_options = json_encode( array(
                "slidesPerView" => 1,
                "loop"          => 'yes' == $settings['loop'] ? true : false,
                "autoHeight"    => false,
                "autoplay"      => 'yes' == $settings['autoplay'] ? [ "delay" => $settings['autoplay_delay'] ] : false,
                "speed"         => $settings['speed'],
                "spaceBetween"  => 0,
                "direction"     => "horizontal",
                "lazy"          => false,
                "navigation" => [
                    "nextEl" => ".slide-next-{$id}",
                    "prevEl" => ".slide-prev-{$id}"
                ],
                "pagination" => [
                    "el" => ".styler-main-slider .styler-pagination-$id",
                    "type" => "bullets",
                    "clickable" => true
                ]
            )
        );

        echo '<div class="styler-main-slider styler-swiper-theme-style swiper-container styler-swiper-slider'.$editmode.'" data-swiper-options=\''.$slider_options.'\'>';
            echo '<div class="swiper-wrapper">';
                foreach ( $settings['items'] as $item ) {
                    $attr = !empty( $item['iautoplay_delay']) ? ' data-swiper-autoplay="'.$item['iautoplay_delay'].'"' : '';
                    $attr .= !empty( $item['anim_in'] ) ? ' data-anim-in="'.$item['anim_in'].'"' : '';
                    $is_anim = !empty( $item['anim_in'] ) ? ' has-animation animated '.$item['anim_in'] : '';
                    $hanim_delay = !empty( $item['anim_in'] ) && !empty( $item['hanim_delay'] ) ? ' style="animation-delay:'.$item['hanim_delay'].'ms"' : '';
                    $descanim_delay = !empty( $item['anim_in'] ) && !empty( $item['descanim_delay'] ) ? ' style="animation-delay:'.$item['descanim_delay'].'ms"' : '';
                    $btnanim_delay = !empty( $item['anim_in'] ) && !empty( $item['btnanim_delay'] ) ? ' style="animation-delay:'.$item['btnanim_delay'].'ms"' : '';
                    echo '<div class="swiper-slide elementor-repeater-item-'.$item['_id'].'"'.$attr.'>';
                        echo '<div class="styler-slide-inner">';
                            if ( !empty( $item['title']) ) {
                                echo '<'.$settings['tag'].' class="styler-slide-head'.$is_anim.'"'.$hanim_delay.'>'.$item['title'].'</'.$settings['tag'].'>';
                            }
                            if ( !empty( $item['desc'] ) ) {
                                echo '<p class="styler-slide-text'.$is_anim.'"'.$descanim_delay.'>'.$item['desc'].'</p>';
                            }
                            if ( !empty( $item['btn_title'] ) || !empty( $item['btn_title2'] ) ) {
                                echo '<div class="styler-slide-link-wrapper'.$is_anim.'"'.$btnanim_delay.'>';
                            }
                                if ( !empty( $item['btn_title'] ) ) {
                                    $target   = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                    $nofollow = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                                    $btn_id   = !empty( $item['btn_id'] ) ? ' id="'.$item['btn_id'].'"' : '';
                                    echo '<a class="styler-btn-large styler-btn styler-bg-black styler-slide-link" href="'.$item['link']['url'].'"'.$btn_id.$target.$nofollow.'>'.$item['btn_title'].'</a>';
                                }
                                if ( !empty( $item['btn_title2'] ) ) {
                                    $target2   = !empty( $item['link2']['is_external'] ) ? ' target="_blank"' : '';
                                    $nofollow2 = !empty( $item['link2']['nofollow'] ) ? ' rel="nofollow"' : '';
                                    $btn_id2   = !empty( $item['btn_id2'] ) ? ' id="'.$item['btn_id2'].'"' : '';
                                    echo '<a class="styler-btn-large styler-btn styler-bg-black styler-slide-link styler-slide-link2" href="'.$item['link2']['url'].'"'.$btn_id2.$target2.$nofollow2.'>'.$item['btn_title2'].'</a>';
                                }
                            if ( !empty( $item['btn_title'] ) || !empty( $item['btn_title2'] ) ) {
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }
            echo '</div>';

            if ( 'yes' == $settings['dots'] ) {
                echo '<div class="swiper-pagination styler-pagination-'.$id.'"></div>';
            }

            if ( 'yes' == $settings['nav'] ) {
                echo '<div class="swiper-button-prev slider-btn-nav slide-prev-'.$id.'"></div>';
                echo '<div class="swiper-button-next slider-btn-nav slide-next-'.$id.'"></div>';
            }

        echo '</div>';

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                var options =  $('.styler-swiper-slider-<?php echo $id ?>').data('swiper-options');
                const mySlider<?php echo $id ?> = new NTSwiper('.styler-swiper-slider-<?php echo $id ?>', options);

                mySlider<?php echo $id ?>.on('transitionEnd', function () {
                    var animIn = $('.styler-swiper-slider-<?php echo $id ?> .swiper-slide').data('anim-in');
                    var active = $('.styler-swiper-slider-<?php echo $id ?>').find('.swiper-slide-active');
                    var inactive = $('.styler-swiper-slider-<?php echo $id ?>').find('.swiper-slide:not(.swiper-slide-active)');

                    if( typeof animIn != 'undefined' ) {
                        inactive.find('.has-animation').each(function(e){
                            $(this).removeClass('animated '+animIn);
                        });
                        active.find('.has-animation').each(function(e){
                            $(this).addClass('animated '+animIn);
                        });
                    }
                });
            });
            </script>
            <?php
        }
    }
}
