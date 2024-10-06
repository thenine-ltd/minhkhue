<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Banner_Slider extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-banner-slider';
    }
    public function get_title() {
        return 'Banner Slider (N)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
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
        $repeater = new Repeater();
        $repeater->add_control( 'category',
            [
                'label' => esc_html__( 'Select Category', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->styler_cpt_taxonomies('product_cat')
            ]
        );
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'styler' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => '']
            ]
        );
        $repeater->add_control( 'use_video',
            [
                'label' => esc_html__( 'Use Background Video', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'video_provider',
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
        $repeater->add_control( 'iframe_embed',
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
        $repeater->add_control( 'loacal_video_url',
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
        $repeater->add_control( 'video_id',
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
        $repeater->add_control( 'video_start',
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
        $repeater->add_control( 'video_end',
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
        $repeater->add_control( 'auto_calculate',
            [
                'label' => esc_html__( 'Auto Calculate Video Size', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $repeater->add_control( 'aspect_ratio',
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
        $repeater->add_responsive_control( 'video_width',
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
        $repeater->add_responsive_control( 'video_height',
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
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Custom Title/Text', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true,
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'desc',
            [
                'label' => esc_html__( 'Short Description', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'label_block' => true
            ]
        );
        $repeater->add_control( 'link',
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
        $this->add_control('all_cats',
            [
                'label' => esc_html__( 'All Categories', 'styler' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'default' => [],
                'title_field' => "Category - {{{ category }}}",
            ]
        );
        $this->add_responsive_control( 'box_height',
            [
                'label' => esc_html__( 'Box Height', 'styler' ),
                'description' => esc_html__( 'if you are using a background image calculate your height as a percentage ( % ), if you are using a video then calculate it in pixels ( px )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 4000,
                'step' => 1,
                'default' => 100,
                'separator' => 'before',
                'selectors' => [
                    '{{WRAPPER}} .styler-woo-banner-wrapper.has-video' => 'height:{{SIZE}}px;',
                    '{{WRAPPER}} .styler-woo-banner-wrapper.has-image .styler-banner-image' => 'padding-top:{{SIZE}}%;',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail',
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
        $this->add_control( 'video_loop',
            [
                'label' => esc_html__( 'Video Loop', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
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
                'separator' => 'before',
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
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label' => esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
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
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control( 'slide_item_space',
            [
                'label' => esc_html__( 'Space Between Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-product-category-slider .styler-category-item' => 'padding: 0 calc({{VALUE}}px / 2 );',
                    '{{WRAPPER}} .styler-product-category-slider .slick-list' => 'margin: 0 calc(-{{VALUE}}px / 2 );',
                ]
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
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
        $this->add_control( 'items',
            [
                'label' => esc_html__( 'Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 5
            ]
        );
        $this->add_control( 'mditems',
            [
                'label' => esc_html__( 'Desktop Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'step' => 1,
                'default' => 4
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
                'default' => 2
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['dots' => 'yes']
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
                'selectors' => [ '{{WRAPPER}} .slick-dots' => 'margin-top:{{SIZE}}px;' ]
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
                'selectors' => [ '{{WRAPPER}} .slick-dots' => 'text-align:{{VALUE}};' ]
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
                'selectors' => [ '{{WRAPPER}} .slick-dots li button' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
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
                    '{{WRAPPER}} .slick-dots li' => 'margin:0 {{SIZE}}px;',
                    '{{WRAPPER}} .slick-dots' => 'margin:0 -{{SIZE}}px;'
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
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li' => 'border-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li button' => 'background-color:{{VALUE}};',
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .slick-dots li',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .slick-dots li,{{WRAPPER}} .slick-dots li button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'dots_hover_tab',
            [ 'label' => esc_html__( 'Active', 'styler' ) ]
        );
        $this->add_control( 'dots_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li:hover button' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li.slick-active button' => 'background-color:{{VALUE}};',
                    '{{WRAPPER}} .slick-dots li.slick-active' => 'border-color:{{VALUE}};'
                ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .slick-dots li.slick-active'
            ]
        );
        $this->add_responsive_control( 'dots_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .slick-dots li.slick-active, {{WRAPPER}} .slick-dots li.slick-active button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }

        $rtl        = is_rtl() ? 'true' : 'false';
        $isrtl      = is_rtl() ? 'is-rtl' : '';
        $dots       = 'yes' == $settings['dots'] ? 'true': 'false';
        $autoplay   = 'yes' == $settings['autoplay'] ? 'true': 'false';
        $centermode = 'yes' == $settings['centermode'] ? 'true': 'false';
        $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        echo '<div class="styler-product-category-slider styler-slick styler-slick-slider'.$editmode.$isrtl.'" data-slick=\'{"rtl":'.$rtl.',"autoplay":'.$autoplay.',"infinite": false,"speed": '.$settings['speed'].',"slidesToShow": '.$settings['items'].',"slidesToScroll": '.$settings['items'].',"adaptiveHeight": false,"dots": '.$dots.',"arrows": false,"responsive": [{"breakpoint": 1200,"settings": {"slidesToShow": '.$settings['items'].',"slidesToScroll": '.$settings['items'].'}},{"breakpoint": 1025,"settings": {"slidesToShow": '.$settings['mditems'].',"slidesToScroll": '.$settings['mditems'].'}},{"breakpoint": 790,"settings": {"slidesToShow": '.$settings['smitems'].',"slidesToScroll": '.$settings['smitems'].'}},{"breakpoint": 576,"settings": {"slidesToShow": '.$settings['xsitems'].',"slidesToScroll": '.$settings['xsitems'].'}}]}\'>';
            foreach ( $settings['all_cats'] as $cat ) {

                $term  = get_term( $cat['category'], 'product_cat' );
                $name  = !empty( $term ) ? $term->name : '';
                $count = !empty( $term ) ? $term->count : '';
                $title = !empty( $cat['title'] ) ? $cat['title'] : '';
                $desc = !empty( $cat['desc'] ) ? $cat['desc'] : '';
                $is_img = $cat['use_video'] == 'yes' ? ' has-video' : ' has-image';

                echo '<div class="styler-category-item">';

                    echo '<div class="styler-woo-banner-wrapper banner-style-'.$settings['baner_style'].$is_img.'">';
                        if ( !empty( $cat['link']['url'] ) ) {
                            $target = !empty( $settings['link']['is_external'] ) ? ' target="_blank"' : '';
                            $rel = !empty( $settings['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                            echo '<a class="styler-banner-link" href="'.$cat['link']['url'].'"'.$target.$rel.'></a>';
                        } else {
                            echo '<a class="styler-banner-link" href="'.get_category_link( $cat['category'] ).'"></a>';
                        }
                        $count_text = $settings['count_text'] ? ' '.$settings['count_text'] : '';

                        if ( $cat['use_video'] == 'yes' ) {

                            $vid      = $cat['video_id'];
                            $as_ratio = !empty( $cat['aspect_ratio'] ) ? $cat['aspect_ratio'] : '16:9';
                            $provider = !empty( $cat['video_provider'] ) ? $cat['video_provider'] : 'youtube';
                            $start    = !empty( $cat['video_start'] ) ? '&start='.$cat['video_start'] : '';
                            $end      = !empty( $cat['video_end'] ) ? '&end='.$cat['video_end'] : '';
                            $vstart   = !empty( $cat['video_start'] ) ? $cat['video_start'].',' : '';
                            $vend     = !empty( $cat['video_end'] ) ? $cat['video_end'] : '';
                            $vtime    = $vstart || $vend ? '#t='.$vstart.$vend : '';
                            $playlist = $settings['video_loop'] == 'yes' ? 'playlist='.$vid : '';
                            $loop     = $settings['video_loop'] == 'yes' ? 1 : 0;
                            $autocalc = $cat['auto_calculate'] == 'yes' ? ' styler-video-calculate' : '';

                            echo '<div class="styler-woo-banner-iframe-wrapper styler-video-'.$provider.$autocalc.'" data-styler-bg-video="'.$vid.'">';

                                if ( $provider == 'vimeo' && $vid ) {
                                    wp_enqueue_script( 'vimeo-player' );
                                    echo '<iframe data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" data-src="https://player.vimeo.com/video/'.$vid.'?autoplay=1&loop='.$loop.'&title=0&byline=0&portrait=0&sidedock=0&controls=0&playsinline=1&muted=1" allow="autoplay; fullscreen; picture-in-picture" allowfullscreen></iframe>';

                                } elseif ( $provider == 'youtube' && $vid ) {

                                    echo '<iframe data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" loading="lazy" data-src="https://www.youtube.com/embed/'.$vid.'?'.$playlist.'&modestbranding=0&rel=0&controls=0&autoplay=1&enablejsapi=1&showinfo=0&mute=1&loop='.$loop.$start.$end.'" allow="autoplay; fullscreen; accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

                                } elseif ( $provider == 'iframe' && !empty( $cat['iframe_embed'] ) ) {

                                    echo do_shortcode( $cat['iframe_embed'] );

                                } elseif ( $provider == 'local' && !empty( $cat['loacal_video_url'] ) ) {

                                    echo '<video  data-bg-aspect-ratio="'.$as_ratio.'" class="lazy" controls="0" autoplay="true" loop="true" muted="true" playsinline="true" data-src="'.$cat['loacal_video_url'].$vtime.'"></video>';
                                }

                            echo '</div>';

                        } else {
                            if ( !empty( $cat['image']['id'] ) ) {
                                echo '<div class="styler-banner-image">';
                                    echo wp_get_attachment_image( $cat['image']['id'], $size, false, ['class'=>'styler-category-item-image'] );
                                echo '</div>';
                            }
                        }

                        echo '<div class="styler-banner-content">';
                            echo '<div class="styler-banner-content-top">';
                                foreach ( $settings['content_orders'] as $item ) {
                                    if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'top' ) {
                                        echo '<span class="styler-banner-catname banner-content-item">'.$name.'</span>';
                                    }
                                    if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'top' ) {
                                        echo '<span class="styler-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                                    }
                                    if ( $title && $item['item_order'] == 'title' && $item['item_position'] == 'top' ) {
                                        echo '<'.$settings['tag'].' class="styler-banner-title banner-content-item">'.$title.'</'.$settings['tag'].'>';
                                    }
                                    if ( $desc && $item['item_order'] == 'desc' && $item['item_position'] == 'top' ) {
                                        echo '<span class="styler-banner-desc banner-content-item">'.$desc.'</span>';
                                    }
                                    if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'top' ) {
                                        echo '<span class="styler-banner-button banner-content-item">'.$settings['btn_title'].' ';if ( !empty( $settings['icon']['value'] ) ) { Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); }echo '</span>';
                                    }
                                }
                            echo '</div>';
                            echo '<div class="styler-banner-content-center">';
                                foreach ( $settings['content_orders'] as $item ) {
                                    if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'center' ) {
                                        echo '<span class="styler-banner-catname banner-content-item">'.$name.'</span>';
                                    }
                                    if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'center' ) {
                                        echo '<span class="styler-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                                    }
                                    if ( $title && $item['item_order'] == 'title' && $item['item_position'] == 'center' ) {
                                        echo '<'.$settings['tag'].' class="styler-banner-title banner-content-item">'.$title.'</'.$settings['tag'].'>';
                                    }
                                    if ( $desc && $item['item_order'] == 'desc' && $item['item_position'] == 'center' ) {
                                        echo '<span class="styler-banner-desc banner-content-item">'.$desc.'</span>';
                                    }
                                    if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'center' ) {
                                        echo '<span class="styler-banner-button banner-content-item">'.$settings['btn_title'].' ';if ( !empty( $settings['icon']['value'] ) ) { Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); }echo '</span>';
                                    }
                                }
                            echo '</div>';
                            echo '<div class="styler-banner-content-bottom">';
                                foreach ( $settings['content_orders'] as $item ) {
                                    if ( $name && $item['item_order'] == 'cat' && $item['item_position'] == 'bottom' ) {
                                        echo '<span class="styler-banner-catname banner-content-item">'.$name.'</span>';
                                    }
                                    if ( $name && $item['item_order'] == 'count' && $item['item_position'] == 'bottom' ) {
                                        echo '<span class="styler-banner-catcount banner-content-item">'.$count.$count_text.'</span>';
                                    }
                                    if ( $title && $item['item_order'] == 'title' && $item['item_position'] == 'bottom' ) {
                                        echo '<'.$settings['tag'].' class="styler-banner-title banner-content-item">'.$title.'</'.$settings['tag'].'>';
                                    }
                                    if ( $desc && $item['item_order'] == 'desc' && $item['item_position'] == 'bottom' ) {
                                        echo '<span class="styler-banner-desc banner-content-item">'.$desc.'</span>';
                                    }
                                    if ( $settings['btn_title'] && $item['item_order'] == 'button' && $item['item_position'] == 'bottom' ) {
                                        echo '<span class="styler-banner-button banner-content-item">'.$settings['btn_title'].' ';if ( !empty( $settings['icon']['value'] ) ) { Icons_Manager::render_icon( $settings['icon'], [ 'aria-hidden' => 'true' ] ); }echo '</span>';
                                    }
                                }
                            echo '</div>';
                        echo '</div>';
                    echo '</div>';

                echo '</div>';
            }
        echo '</div>';
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                $('.styler-slick-slider-<?php echo $id ?>').not('.slick-initialized').slick();
            });
            </script>
            <?php
        }
    }
}
