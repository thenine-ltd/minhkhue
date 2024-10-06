<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Banner extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-banner';
    }
    public function get_title() {
        return 'Banner (N)';
    }
    public function get_icon() {
        return 'eicon-icon-box';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_section',
            [
                'label'=> esc_html__( 'Banner', 'styler' ),
                'tab'=> Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'category',
            [
                'label' => esc_html__( 'Select Category', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->styler_cpt_taxonomies('product_cat')
            ]
        );
        $this->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'styler' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $this->add_control( 'use_video',
            [
                'label' => esc_html__( 'Use Background Video', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'video_provider',
            [
                'label' => esc_html__( 'Video Source', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'youtube',
                'options' => [
                    'youtube' => esc_html__( 'YouTube', 'styler' ),
                    'vimeo' => esc_html__( 'Vimeo', 'styler' ),
                    'local' => esc_html__( 'Local', 'styler' ),
                    'iframe' => esc_html__( 'Custom Iframe Embed', 'styler' ),
                ],
                'condition' => ['use_video' => 'yes']
            ]
        );
        $this->add_control( 'iframe_embed',
            [
                'label' => esc_html__( 'Custom Iframe Embed Code', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '==','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'loacal_video_url',
            [
                'label' => esc_html__( 'Loacal Video URL', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '==','value' => 'local' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_id',
            [
                'label' => esc_html__( 'Video ID', 'styler' ),
                'placeholder' => '',
                'description' => esc_html__( 'YouTube/Vimeo video ID.', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'local' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_loop',
            [
                'label' => esc_html__( 'Loop', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_start',
            [
                'label' => esc_html__( 'Video Start', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'video_end',
            [
                'label' => esc_html__( 'Video Start', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'video_provider','operator' => '!=','value' => 'iframe' ]
                    ]
                ]
            ]
        );
        $this->add_control( 'auto_calculate',
            [
                'label' => esc_html__( 'Auto Calculate Video Size', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $this->add_control( 'aspect_ratio',
            [
                'label' => esc_html__( 'Aspect Ratio', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '16:9' => esc_html__( '16:9 ( Standard Video )', 'styler' ),
                    '9:16' => esc_html__( '9:16 ( for vertical video )', 'styler' ),
                    '1:1' =>esc_html__( '1:1', 'styler' ),
                    '4:3' => esc_html__( '4:3', 'styler' ),
                    '3:2' => esc_html__( '3:2', 'styler' ),
                    '21:9' => esc_html__( '21:9', 'styler' ),
                ],
                'default' => '16:9',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'auto_calculate','operator' => '==','value' => 'yes' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'video_box_size',
            [
                'label' => esc_html__( 'Video Box Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => 100,
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper' => 'height:{{SIZE}}px;' ],
                'condition' => ['use_video' => 'yes']
            ]
        );
        $this->add_responsive_control( 'video_width',
            [
                'label' => esc_html__( 'Custom Video Width', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-iframe-wrapper iframe' => 'width:{{SIZE}}px;max-width:none;' ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'auto_calculate','operator' => '!=','value' => 'yes' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'video_height',
            [
                'label' => esc_html__( 'Custom Video Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-iframe-wrapper iframe' => 'height:{{SIZE}}px;max-width:none;' ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [ 'name' => 'use_video','operator' => '==','value' => 'yes' ],
                        [ 'name' => 'auto_calculate','operator' => '!=','value' => 'yes' ]
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'fit_size',
            [
                'label' => esc_html__( 'Box Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => 75,
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper .styler-banner-image' => 'padding-top:{{SIZE}}%;' ],
                'condition' => ['use_video!' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
                'condition' => ['use_video!' => 'yes']
            ]
        );
        $this->add_control( 'title',
            [
                'label' => esc_html__( 'Custom Title/Text', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Free Shipping On Over $ 50',
                'label_block' => true,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h6',
                'options' => [
                    'h1' => esc_html__( 'H1', 'styler' ),
                    'h2' => esc_html__( 'H2', 'styler' ),
                    'h3' => esc_html__( 'H3', 'styler' ),
                    'h4' => esc_html__( 'H4', 'styler' ),
                    'h5' => esc_html__( 'H5', 'styler' ),
                    'h6' => esc_html__( 'H6', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' )
                ]
            ]
        );
        $this->add_control( 'desc',
            [
                'label' => esc_html__( 'Short Description', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Agricultural mean crops livestock',
                'label_block' => true
            ]
        );
        $this->add_control( 'count_text',
            [
                'label' => esc_html__( 'After Count Text', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'btn_title',
            [
                'label' => esc_html__( 'Button Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'See More Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'icon',
            [
                'label' => esc_html__( 'Button Icon', 'styler' ),
                'type' => Controls_Manager::ICONS,
                'default' => [
                    'value' => '',
                    'library' => 'solid'
                ]
            ]
        );
        $this->add_control( 'icon_spacing',
            [
                'label' => esc_html__( 'Icon Spacing', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 50
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-button i' => 'margin-left: {{SIZE}}px;']
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Custom Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'baner_style',
            [
                'label' => esc_html__( 'Banner Style', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'card'  => esc_html__( 'Card', 'styler' ),
                    'card-hover'  => esc_html__( 'Card Hover', 'styler' ),
                    'classic' => esc_html__( 'Classic', 'styler' )
                ],
                'default' => 'card'
            ]
        );
        $repeater = new Repeater();
        $repeater->add_control( 'item_order',
            [
                'label' => esc_html__( 'Content Item order', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'cat'  => esc_html__( 'Category', 'styler' ),
                    'title'  => esc_html__( 'Title', 'styler' ),
                    'desc' => esc_html__( 'Description', 'styler' ),
                    'count' => esc_html__( 'Count', 'styler' ),
                    'button' => esc_html__( 'Button', 'styler' ),
                ],
                'default' => 'cat',
            ]
        );
        $repeater->add_control( 'item_position',
            [
                'label' => esc_html__( 'Select Item Position', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'multiple' => false,
                'options' => [
                    'top'  => esc_html__( 'Top', 'styler' ),
                    'center'  => esc_html__( 'Center', 'styler' ),
                    'bottom' => esc_html__( 'Bottom', 'styler' ),
                ],
                'default' => 'top',
            ]
        );
        $this->add_control('content_orders',
            [
                'label' => esc_html__( 'Content Items Order', 'styler' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [
                    [
                        'item_order' => 'cat',
                        'item_position' => 'top'
                    ],
                    [
                        'item_order' => 'title',
                        'item_position' => 'top'
                    ],
                ],
                'title_field' => '{{{item_order}}} - {{{item_position}}}',
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Box Content Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-woo-banner-wrapper .styler-banner-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'selector' => '{{WRAPPER}} .styler-woo-banner-wrapper'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Box Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-woo-banner-wrapper' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
            ]
        );
        $this->add_responsive_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-woo-banner-wrapper:not(.banner-style-classic):before,
                    {{WRAPPER}} .styler-woo-banner-wrapper.banner-style-classic .styler-banner-image:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'overlay_hvrcolor',
            [
                'label' => esc_html__( 'Hover Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-woo-banner-wrapper:not(.banner-style-classic):hover::before,
                    {{WRAPPER}} .styler-woo-banner-wrapper.banner-style-classic .styler-banner-image:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'text_hvrcolor',
            [
                'label' => esc_html__( 'Hover Text Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper:hover .styler-banner-content .styler-banner-title,{{WRAPPER}} .styler-woo-banner-wrapper:hover .styler-banner-content ' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'image_hvrscale',
            [
                'label' => esc_html__( 'Hover Image Scale', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 2,
                'step' => 0.1,
                'default' => 1.2,
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper:hover .styler-banner-image img' => 'transform: scale( {{SIZE}} );' ],
            ]
        );
        $this->add_responsive_control( 'alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .styler-woo-banner-wrapper .styler-banner-content' => 'text-align: {{VALUE}};'],
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
                'default' => 'flex-start'
            ]
        );
        $this->add_control( 'cat_divider',
            [
                'label' => esc_html__( 'CATEGORY', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'cat_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-catname' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'cat_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-catname' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'cat_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-catname'
            ]
        );
        $this->add_responsive_control( 'cat_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-catname' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'cat_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-catname' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'cat_border',
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-catname'
            ]
        );
        $this->add_responsive_control( 'cat_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-catname' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
            ]
        );
        $this->add_control( 'catcount_divider',
            [
                'label' => esc_html__( 'CATEGORY COUNT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'catcount_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-catcount' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'catcount_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-catcount' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'catcount_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-catcount'
            ]
        );
        $this->add_responsive_control( 'catcount_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-catcount' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'catcount_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-catcount' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'catcount_border',
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-catcount'
            ]
        );
        $this->add_responsive_control( 'catcount_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-catcount' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
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
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-title'
            ]
        );
        $this->add_responsive_control( 'title_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'title_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
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
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-desc'
            ]
        );
        $this->add_responsive_control( 'desc_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-desc' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'desc_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-desc' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'btn_divider',
            [
                'label' => esc_html__( 'BUTTON', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-button' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper:hover .styler-banner-content .styler-banner-button' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-banner-content .styler-banner-button' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'btn_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-woo-banner-wrapper:hover .styler-banner-content .styler-banner-button' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-button'
            ]
        );
        $this->add_responsive_control( 'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_responsive_control( 'btn_margin',
            [
                'label' => esc_html__( 'Margin', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'selector' => '{{WRAPPER}} .styler-banner-content .styler-banner-button'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-banner-content .styler-banner-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {

        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        
        $settings  = $this->get_settings_for_display();

        $term  = get_term( $settings['category'], 'product_cat' );
        $name  = !empty( $term ) ? $term->name : '';
        $count = !empty( $term ) ? $term->count : '';
        if ( !empty( $settings['link']['url'] ) ) {
            $target = !empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
            $rel = !empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
            $link = $settings['link']['url'];
        } else {
            $link = get_category_link( $settings['category'] );
        }


        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'thumbnail';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }

        echo '<div class="styler-woo-banner-wrapper banner-style-'.$settings['baner_style'].'">';

            $count_text = $settings['count_text'] ? ' '.$settings['count_text'] : '';

            if ( !empty( $settings['link']['url'] ) ) {
                $target = !empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
                $rel = !empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                echo '<a class="styler-banner-link" href="'.$settings['link']['url'].'"'.$target.$rel.'></a>';
            } else {
                echo '<a class="styler-banner-link" href="'.get_category_link( $settings['category'] ).'"></a>';
            }
            if ( $settings['use_video'] == 'yes' ) {

                $vid      = $settings['video_id'];
                $as_ratio = $settings['aspect_ratio'] ? $settings['aspect_ratio'] : '16:9';
                $provider = $settings['video_provider'];
                $start    = $settings['video_start'] ? '&start='.$settings['video_start'] : '';
                $end      = $settings['video_end'] ? '&end='.$settings['video_end'] : '';
                $vstart   = $settings['video_start'] ? $settings['video_start'].',' : '';
                $vend     = $settings['video_end'] ? $settings['video_end'] : '';
                $vtime    = $vstart || $vend ? '#t='.$vstart.$vend : '';
                $playlist = $settings['video_loop'] ? 'playlist='.$vid : '';
                $loop     = $settings['video_loop'] ? 1 : 0;
                $autocalc = $settings['auto_calculate'] == 'yes' ? ' styler-video-calculate' : '';

                echo '<div class="styler-woo-banner-iframe-wrapper styler-video-'.$provider.$autocalc.'" data-styler-bg-video="'.$vid.'">';

                    if ( $provider == 'vimeo' && $vid ) {

                        echo '<iframe data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" src="https://player.vimeo.com/video/'.$vid.'?autoplay=1&loop='.$loop.'&title=0&byline=0&portrait=0&sidedock=0&controls=0&playsinline=1&muted=1" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';

                    } elseif ( $provider == 'youtube' && $vid ) {

                        echo '<iframe data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" data-src="https://www.youtube.com/embed/'.$vid.'?'.$playlist.'&modestbranding=0&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop='.$loop.$start.$end.'" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                    } elseif ( $provider == 'iframe' && $settings['iframe_embed'] ) {

                        echo do_shortcode( $settings['iframe_embed'] );

                    } elseif ( $provider == 'local' && $settings['loacal_video_url'] ) {

                        echo '<video data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" controls="0" autoplay="true" loop="true" muted="true" playsinline="true" data-src="'.$settings['loacal_video_url'].$vtime.'"></video>';
                    }

                echo '</div>';

            } else {
                if ( !empty( $settings['image']['id'] ) ) {
                    echo '<div class="styler-banner-image">';
                        echo wp_get_attachment_image( $settings['image']['id'], $size, false );
                    echo '</div>';
                }
            }

            echo '<div class="styler-banner-content">';
                echo '<div class="styler-banner-content-top">';
                    foreach (  $settings['content_orders'] as $item ) {
                        if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'top' ) {
                            echo '<span class="styler-banner-catname banner-content-item">'.$name.'</span>';
                        }
                        if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'top' ) {
                            echo '<span class="styler-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                        }
                        if ( $settings['title'] && $item['item_order'] == 'title' && $item['item_position'] == 'top' ) {
                            echo '<'.$settings['tag'].' class="styler-banner-title banner-content-item">'.$settings['title'].'</'.$settings['tag'].'>';
                        }
                        if ( $settings['desc'] && $item['item_order'] == 'desc' && $item['item_position'] == 'top' ) {
                            echo '<span class="styler-banner-desc banner-content-item">'.$settings['desc'].'</span>';
                        }
                        if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'top' ) {
                            echo '<span class="styler-banner-button banner-content-item">'.$settings['btn_title'].' ';if ( !empty( $settings['icon']['value'] ) ) { Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); }echo '</span>';
                        }
                    }
                echo '</div>';
                echo '<div class="styler-banner-content-center">';
                    foreach (  $settings['content_orders'] as $item ) {
                        if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'center' ) {
                            echo '<span class="styler-banner-catname banner-content-item">'.$name.'</span>';
                        }
                        if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'center' ) {
                            echo '<span class="styler-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                        }
                        if ( $settings['title'] && $item['item_order'] == 'title' && $item['item_position'] == 'center' ) {
                            echo '<'.$settings['tag'].' class="styler-banner-title banner-content-item">'.$settings['title'].'</'.$settings['tag'].'>';
                        }
                        if ( $settings['desc'] && $item['item_order'] == 'desc' && $item['item_position'] == 'center' ) {
                            echo '<span class="styler-banner-desc banner-content-item">'.$settings['desc'].'</span>';
                        }
                        if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'center' ) {
                            echo '<span class="styler-banner-button banner-content-item">'.$settings['btn_title'].' ';if ( !empty( $settings['icon']['value'] ) ) { Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); }echo '</span>';
                        }
                    }
                echo '</div>';
                echo '<div class="styler-banner-content-bottom">';
                    foreach (  $settings['content_orders'] as $item ) {
                        if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'bottom' ) {
                            echo '<span class="styler-banner-catname banner-content-item">'.$name.'</span>';
                        }
                        if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'bottom' ) {
                            echo '<span class="styler-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                        }
                        if ( $settings['title'] && $item['item_order'] == 'title' && $item['item_position'] == 'bottom' ) {
                            echo '<'.$settings['tag'].' class="styler-banner-title banner-content-item">'.$settings['title'].'</'.$settings['tag'].'>';
                        }
                        if ( $settings['desc'] && $item['item_order'] == 'desc' && $item['item_position'] == 'bottom' ) {
                            echo '<span class="styler-banner-desc banner-content-item">'.$settings['desc'].'</span>';
                        }
                        if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'bottom' ) {
                            echo '<span class="styler-banner-button banner-content-item">'.$settings['btn_title'].' ';if ( !empty( $settings['icon']['value'] ) ) { Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); }echo '</span>';
                        }
                    }
                echo '</div>';
            echo '</div>';
        echo '</div>';

    }
}
