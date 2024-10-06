<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Posts_Base extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-posts-base';
    }
    public function get_title() {
        return 'Posts Base (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
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
        $this->start_controls_section( 'post_query',
            [
                'label' => esc_html__( 'Query', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->styler_query_controls( 'post' );

        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_options',
            [
                'label' => esc_html__( 'Post Options', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'type',
            [
                'label' => esc_html__( 'Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'grid',
                'options' => [
                    'grid' => esc_html__( 'Grid', 'styler' ),
                    'slider' => esc_html__( 'Slider', 'styler' )
                ]
            ]
        );
        $this->add_control( 'style',
            [
                'label' => esc_html__( 'Style', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'default',
                'options' => [
                    'default' => esc_html__( 'Classic', 'styler' ),
                    'card' => esc_html__( 'Card', 'styler' )
                ]
            ]
        );
        $this->add_responsive_control( 'card_min_height',
            [
                'label' => esc_html__( 'Min Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 1000,
                'step' => 1,
                'default' => 450,
                'selectors' => [ '{{WRAPPER}} .style-card .styler-blog-post-item-inner' => 'min-height: {{VALUE}}px;' ],
                'condition' => ['style' => 'card']
            ]
        );
        $this->add_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .style-card .styler-blog-post-item-inner:before' => 'background-color:{{VALUE}};' ],
                'condition' => ['style' => 'card']
            ]
        );
        $this->add_control( 'bg_image_size',
            [
                'label' => esc_html__( 'Background Image Size', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'cover',
                'options' => [
                    'cover' => esc_html__( 'Cover', 'styler' ),
                    'contain' => esc_html__( 'Contain', 'styler' ),
                    'auto' => esc_html__( 'Auto', 'styler' ),
                ],
                'selectors' => ['{{WRAPPER}} .style-card .styler-blog-post-item-inner' => 'background-size:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'column',
            [
                'label' => esc_html__( 'Column Width', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 12,
                'step' => 1,
                'default' => 3,
                'selectors' => [ '{{WRAPPER}} .styler-blog-post-item' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );' ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_responsive_control( 'item_space',
            [
                'label' => esc_html__( 'Space Between Column', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-blog-post-item' => 'padding-left: calc({{VALUE}}px / 2 ); padding-right: calc({{VALUE}}px / 2 ); margin-bottom: {{VALUE}}px',
                    '{{WRAPPER}} .styler-blog-grid.row' => 'margin: 0 calc(-{{VALUE}}px / 2 ) -{{VALUE}}px calc(-{{VALUE}}px / 2 );',
                ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_responsive_control( 'content_alignment',
            [
                'label' => esc_html__( 'Post Alignment', 'styler' ),
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
                'condition' => ['type' => 'grid'],
                'selectors' => [ '{{WRAPPER}} .styler-blog-grid' => 'justify-content: {{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-text-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-text-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-text-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-blog-post-item' => 'text-align: {{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'cat_alignment',
            [
                'label' => esc_html__( 'Category Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-text-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-text-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-text-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .style-card .styler-blog-post-category' => 'text-align: {{VALUE}};' ],
                'condition' => ['style' => 'card']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => '',
            ]
        );
        $this->add_control( 'hidecat',
            [
                'label' => esc_html__( 'Hide Category', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'hidetitle',
            [
                'label' => esc_html__( 'Hide Title', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'hideauthor',
            [
                'label' => esc_html__( 'Hide Author', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'hidedate',
            [
                'label' => esc_html__( 'Hide Date', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'hideexcerpt',
            [
                'label' => esc_html__( 'Hide Excerpt', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'excerpt_limit',
            [
                'label' => esc_html__( 'Excerpt Word Limit', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'default' => 20,
                'condition' => ['hideexcerpt!' => 'yes']
            ]
        );
        $this->add_control( 'pagination',
            [
                'label' => esc_html__( 'Pagination', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'condition' => ['type' => 'grid']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label'=> esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'post_content_sdivider',
            [
                'label' => esc_html__( 'POST CONTENT', 'styler' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_responsive_control( 'post_item_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-blog-post-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_item_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-blog-post-item-inner',
            ]
        );
        $this->add_responsive_control( 'post_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-blog-post-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-blog-post-item-inner'
            ]
        );
        $this->add_control( 'text_content_sdivider',
            [
                'label' => esc_html__( 'TEXT CONTENT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_responsive_control( 'text_content_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-blog-post-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->add_control( 'cat_sdivider',
            [
                'label' => esc_html__( 'CATEGORY', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'cat_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-blog-post-category span' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_control( 'cat_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-blog-post-category span' => 'background-color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'post_cat_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-blog-post-category span' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_control( 'title_sdivider',
            [
                'label' => esc_html__( 'TITLE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h4',
                'options' => [
                    'h1' => esc_html__( 'H1', 'styler' ),
                    'h2' => esc_html__( 'H2', 'styler' ),
                    'h3' => esc_html__( 'H3', 'styler' ),
                    'h4' => esc_html__( 'H4', 'styler' ),
                    'h5' => esc_html__( 'H5', 'styler' ),
                    'h6' => esc_html__( 'H6', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' ),
                ]
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-post-title a' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_control( 'title_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-post-title a:hover' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-post-title'
            ]
        );
        $this->add_control( 'meta_sdivider',
            [
                'label' => esc_html__( 'POST AUTHOR', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'meta_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-post-meta-title' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-blog-post-content .styler-post-meta-title'
            ]
        );
        $this->add_control( 'meta_date_sdivider',
            [
                'label' => esc_html__( 'POST DATE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'meta_date_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-post-meta-date' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'meta_date_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-blog-post-content .styler-post-meta-date'
            ]
        );
        $this->add_control( 'text_sdivider',
            [
                'label' => esc_html__( 'EXCERPT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'text_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-post-excerpt' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-post-excerpt'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['type' => 'slider']
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
        $this->add_control( 'centermode',
            [
                'label' => esc_html__( 'Center Mode', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
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
        $this->add_control( 'space',
            [
                'label' => esc_html__( 'Space Between Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 0
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
        $this->add_control( 'effect',
            [
                'label' => esc_html__( 'Effect', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__( 'Slide', 'styler' ),
                    'flip' => esc_html__( 'flip', 'styler' ),
                    'coverflow' => esc_html__( 'Coverflow', 'styler' ),
                    'creative' => esc_html__( 'Creative', 'styler' ),
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('navs_style_section',
            [
                'label'=> esc_html__( 'SLIDER NAV STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => '==',
                            'value' => 'slider'
                        ],
                        [
                            'name' => 'nav',
                            'operator' => '=',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $this->add_control( 'navs_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-button-prev:after,{{WRAPPER}} .styler-swiper-theme-style .swiper-button-next:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .swiper-button-prev:after,{{WRAPPER}} .styler-swiper-theme-style .swiper-button-next:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .swiper-button-prev:hover:after,{{WRAPPER}} .styler-swiper-theme-style .swiper-button-next:hover:after' => 'color:{{VALUE}};' ]
            ]
        );

        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'type',
                            'operator' => '==',
                            'value' => 'slider'
                        ],
                        [
                            'name' => 'dots',
                            'operator' => '=',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $this->add_control( 'dots_top_offset',
            [
                'label' => esc_html__( 'Top Offset', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-pagination-bullets' => 'margin-top:{{SIZE}}px;' ]
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
        $this->add_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'dots_space',
            [
                'label' => esc_html__( 'Space', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
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
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'pagination_style_section',
            [
                'label'=> esc_html__( 'PAGINATION STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['pagination' => 'yes']
            ]
        );
        $this->add_responsive_control( 'pag_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-pagination' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_responsive_control( 'pag_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_control( 'pag_sdivider',
            [
                'label' => esc_html__( 'BUTTON', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'pag_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .page-numbers' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'pag_space',
            [
                'label' => esc_html__( 'Spacing', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .page-numbers:not(:last-child)' => 'margin-right: {{SIZE}}px;' ],
                'condition' => ['type' => '1']
            ]
        );
        $this->start_controls_tabs( 'pag_tabs');
        $this->start_controls_tab( 'pag_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'pag_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'pag_bgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pag_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .page-numbers'
            ]
        );
        $this->add_responsive_control( 'pag_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .page-numbers' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'pag_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
        $this->add_control( 'pag_hvrcolor',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'pag_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'pag_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current'
            ]
        );
        $this->add_responsive_control( 'pag_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .page-numbers:hover,{{WRAPPER}} .page-numbers.current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        if ( is_home() || is_front_page() ) {
            $paged = get_query_var( 'page') ? get_query_var('page') : 1;
        } else {
            $paged = get_query_var( 'paged') ? get_query_var('paged') : 1;
        }

        $args['post_type']      = $settings['post_type'];
        $args['posts_per_page'] = $settings['posts_per_page'];
        $args['offset']         = $settings['offset'];
        $args['order']          = $settings['order'];
        $args['orderby']        = $settings['orderby'];
        $args['paged']          = $paged;
        $args[$settings['author_filter_type']] = $settings['author'];


        $post_type = $settings['post_type'];

        if ( ! empty( $settings[ $post_type . '_filter' ] ) ) {
            $args[ $settings[ $post_type . '_filter_type' ] ] = $settings[ $post_type . '_filter' ];
        }

        // Taxonomy Filter.
        $taxonomy = $this->get_post_taxonomies( $post_type );

        if ( ! empty( $taxonomy ) && ! is_wp_error( $taxonomy ) ) {

            foreach ( $taxonomy as $index => $tax ) {

                $tax_control_key = $index . '_' . $post_type;

                if ( $post_type == 'post' ) {
                    if ( $index == 'post_tag' ) {
                        $tax_control_key = 'tags';
                    } elseif ( $index == 'category' ) {
                        $tax_control_key = 'categories';
                    }
                }

                if ( ! empty( $settings[ $tax_control_key ] ) ) {

                    $operator = $settings[ $index . '_' . $post_type . '_filter_type' ];

                    $args['tax_query'][] = array(
                        'taxonomy' => $index,
                        'field' => 'term_id',
                        'terms' => $settings[ $tax_control_key ],
                        'operator' => $operator,
                    );
                }
            }
        }

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh, true ];
        }

        $type = 'class="styler-blog-post-items styler-blog-grid row"';
        $column = 'styler-blog-post-item col-12 col-sm-2 col-md-3';
        $effect = $settings['effect'];
        if ( 'slider' == $settings['type'] ) {

            $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

            $slider_options = json_encode( array(
                    "slidesPerView" => 1,
                    "loop"          => 'yes' == $settings['loop'] ? true: false,
                    "autoHeight"    => false,
                    "autoplay"      => 'yes' == $settings['autoplay'] ? true: false,
                    "centeredSlides"=> 'yes' == $settings['centermode'] ? true: false,
                    "speed"         => $settings['speed'],
                    "spaceBetween"  => $settings['space'] ? $settings['space'] : 0,
                    "direction"     => "horizontal",
                    "lazy"          => true,
                    "navigation" => [
                        "nextEl" => ".styler-blog-slider .slide-next-{$id}",
                        "prevEl" => ".styler-blog-slider .slide-prev-{$id}"
                    ],
                    "effect" => "{$effect}",
                    "flipEffect" => [
                        "slideShadows" => false
                    ],
                    "fadeEffect" => [
                        "crossFade" => true
                    ],
                    "creativeEffect" => [
                        "prev" => [ "translate" => [0, 0, -400] ],
                        "next" => [ "translate" => ['100%', 0, 0] ]
                    ],
                    "coverflowEffect" => [
                        "rotate" => 30,
                        "slideShadows" => false
                    ],
                    "pagination" => [
                        "el" => ".styler-blog-slider .styler-pagination-$id",
                        "type" => "bullets",
                        "clickable" => true
                    ],
                    "breakpoints" => [
                        "0" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['xsitems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['xsitems']
                        ],
                        "768" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['smitems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['smitems']
                        ],
                        "1024" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['mditems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['mditems']
                        ]
                    ]
                )
            );

            $type = 'class="styler-blog-slider styler-swiper-theme-style swiper-container styler-swiper-slider'.$editmode.'" data-swiper-options=\''.$slider_options.'\'';
            $column = 'styler-blog-post-item swiper-slide';
        }

        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            echo '<div '.$type.'>';
                echo 'slider' == $settings['type'] ? '<div class="swiper-wrapper">' : '';
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();
                    $postid = get_the_ID();
                    $categories = get_the_category($postid);
                    $bg = get_the_post_thumbnail_url($postid, $size);
                    $databg = 'card' == $settings['style'] && has_post_thumbnail() ? ' swiper-lazy" data-background="'.$bg.'"' : '"';
                    $catnone = 'yes' == $settings[ 'hidecat' ] ? ' category-none' : '';

                    echo '<div class="'.$column.' style-'.$settings['style'].' content-alignment-'.$settings[ 'alignment' ].'">';

                        echo '<div class="styler-blog-post-item-inner'.$catnone.$databg.'>';
                            if ( has_post_thumbnail() ) {
                                if ( 'card' == $settings['style']  ) {
                                    if ( !empty( $categories ) && 'yes' != $settings[ 'hidecat' ] ) {
                                        echo '<a class="styler-blog-post-category" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '"><span>' . esc_html( $categories[0]->name ) . '</span></a>';
                                    }
                                } else {
                                    echo '<div class="styler-blog-thumb">';
                                        echo '<a href="'.get_permalink().'">'.get_the_post_thumbnail($postid, $size). '</a>';
                                    if ( !empty( $categories ) && 'yes' != $settings[ 'hidecat' ] ) {
                                        echo '<a class="styler-blog-post-category" href="' . esc_url( get_category_link( $categories[0]->term_id ) ) . '"><span>' . esc_html( $categories[0]->name ) . '</span></a>';
                                    }
                                    echo '</div>';
                                }
                            }
                            if ( 'yes' != $settings['hideauthor'] || 'yes' != $settings[ 'hidetitle' ] || 'yes' != $settings[ 'hideexcerpt' ] && has_excerpt() ) {
                                echo '<div class="styler-blog-post-content">';
                                    echo '<div class="styler-blog-post-meta styler-inline-two-block">';
                                        if ( 'yes' != $settings['hideauthor'] ) {
                                            echo '<h6 class="styler-post-author">'.get_the_author().'</h6>';
                                        }
                                        if ( 'yes' != $settings['hidedate'] ) {
                                            echo '<h6 class="styler-post-date">'.get_the_date().'</h6>';
                                        }
                                    echo '</div>';
                                    if ( 'yes' != $settings[ 'hidetitle' ] ) {
                                        echo '<'.$settings['tag'].' class="styler-post-title"><a href="'.get_permalink().'">'.get_the_title().'</a></'.$settings['tag'].'>';
                                    }
                                    if ( 'yes' != $settings[ 'hideexcerpt' ] && has_excerpt() ) {
                                        echo '<p class="styler-post-excerpt">'.wp_trim_words( get_the_excerpt(), $settings['excerpt_limit'] ).'</p>';
                                    }
                                echo '</div>';
                            }
                        echo '</div>';
                    echo '</div>';
                }
                wp_reset_postdata();

                if ( 'slider' != $settings['type'] && 'yes' == $settings['pagination'] && $the_query->max_num_pages > 1 ) {
                    echo '<div class="styler-pagination styler-flex styler-align-center">';
                        echo paginate_links(array(
                            'base' => str_replace( 999999999, '%#%', esc_url( get_pagenum_link( 999999999 ) ) ),
                            'format' => '?paged=%#%',
                            'current' => max(1, $paged ),
                            'total' => $the_query->max_num_pages,
                            'type' => '',
                            'prev_text' => '<i class="fa fa-angle-left"></i>',
                            'next_text' => '<i class="fa fa-angle-right"></i>',
                            'before_page_number' => '<div class="nt-pagination-item">',
                            'after_page_number' => '</div>'
                        ));
                    echo '</div>';
                }

                if ( 'slider' == $settings['type'] ) {
                    echo '</div>';
                    if ( 'yes' == $settings['dots'] ) {
                        echo '<div class="swiper-pagination styler-pagination-'.$id.'"></div>';
                    }
                    if ( 'yes' == $settings['nav'] ) {
                        if ( is_rtl() ) {
                            echo '<div class="swiper-button-next slide-next-'.$id.'"></div>';
                            echo '<div class="swiper-button-prev slide-prev-'.$id.'"></div>';
                        } else {
                            echo '<div class="swiper-button-prev slide-prev-'.$id.'"></div>';
                            echo '<div class="swiper-button-next slide-next-'.$id.'"></div>';
                        }
                    }
                }
            echo '</div>';

        } else {
            echo '<p class="styler-not-found-info">' . esc_html__( 'No post found!', 'styler' ) . '</p>';
        }
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && 'slider' == $settings['type'] ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                $('.styler-swiper-slider-<?php echo $id ?>').each(function () {
                    const options  = JSON.parse(this.dataset.swiperOptions);
                    const mySlider = new NTSwiper(this, $(this).data('swiper-options'));

                    $(this).hover(function() {
                        if ( options.autoplay == true ) {
                            mySlider.autoplay.stop();
                        }
                    }, function() {
                        if ( options.autoplay == true ) {
                            mySlider.autoplay.start();
                        }
                    });

                });
            });
            </script>
            <?php
        }
    }
}
