<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Category_Grid extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-category-grid';
    }
    public function get_title() {
        return 'WC Categories (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'wc', 'woo', 'product' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_settings',
            [
                'label' => esc_html__('Product Categories', 'styler'),
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
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Per Page', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 10,
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
                'selectors' => [ '{{WRAPPER}} .styler-category-item' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );' ],
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
                    '{{WRAPPER}} .styler-category-grid .styler-category-item' => 'padding-left: calc({{VALUE}}px / 2 ); padding-right: calc({{VALUE}}px / 2 ); margin-bottom: {{VALUE}}px',
                    '{{WRAPPER}} .styler-category-grid.row' => 'margin: 0 calc(-{{VALUE}}px / 2 ) -{{VALUE}}px calc(-{{VALUE}}px / 2 );',
                ],
                'condition' => ['type' => 'grid']
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'thumbnail'
            ]
        );
        $this->add_control( 'order',
            [
                'label' => esc_html__( 'Select Order', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'ASC' => esc_html__( 'Ascending', 'styler' ),
                    'DESC' => esc_html__( 'Descending', 'styler' )
                ],
                'default' => 'ASC'
            ]
        );
        $this->add_control( 'orderby',
            [
                'label' => esc_html__( 'Order By', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'id' => esc_html__( 'Post ID', 'styler' ),
                    'menu_order' => esc_html__( 'Menu Order', 'styler' ),
                    'rand' => esc_html__( 'Random', 'styler' ),
                    'date' => esc_html__( 'Date', 'styler' ),
                    'title' => esc_html__( 'Title', 'styler' ),
                ],
                'default' => 'id',
            ]
        );
        $this->add_control( 'category_exclude',
            [
                'label' => esc_html__( 'Exclude Category', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s) to Exclude'
            ]
        );
        $this->add_control( 'cat_count',
            [
                'label' => esc_html__( 'Category Count', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'cat_desc',
            [
                'label' => esc_html__( 'Category Description', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
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
        $this->add_control( 'image_sdivider',
            [
                'label' => esc_html__( 'ITEM BOX', 'styler' ),
                'type' => Controls_Manager::HEADING,
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
                'selectors' => [
                    '{{WRAPPER}} .styler-category-item-inner,
                    {{WRAPPER}} .styler-category-content .styler-category-title,
                    {{WRAPPER}} .styler-category-content .styler-category-description' => 'text-align: {{VALUE}};'
                ]
            ]
        );
        $this->add_control( 'img_alignment',
            [
                'label' => esc_html__( 'Image Alignment', 'styler' ),
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
                'default' => ''
            ]
        );
        $this->add_control( 'box_content_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-category-item-inner' => 'background-color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'post_item_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-category-item-inner' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
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
                'selector' => '{{WRAPPER}} .styler-category-item-inner',
            ]
        );
        $this->add_responsive_control( 'post_item_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-category-item-inner' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-category-item-inner'
            ]
        );
        $this->add_control( 'text_content_sdivider',
            [
                'label' => esc_html__( 'TEXT CONTENT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'text_content_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-category-content' => 'background-color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'text_content_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-category-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
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
                'default' => 'h6',
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
                'selectors' => ['{{WRAPPER}} .styler-category-title' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-category-title'
            ]
        );
        $this->add_control( 'count_sdivider',
            [
                'label' => esc_html__( 'CATEGORY COUNT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_control( 'count_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-category-count' => 'color:{{VALUE}};' ],
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'count_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-category-count',
                'condition' => ['cat_count' => 'yes']
            ]
        );
        $this->add_control( 'text_sdivider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['description' => 'yes']
            ]
        );
        $this->add_control( 'text_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-category-description' => 'color:{{VALUE}};' ],
                'condition' => ['description' => 'yes']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'text_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-category-description',
                'condition' => ['description' => 'yes']
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
        $this->add_control( 'centermode',
            [
                'label' => esc_html__( 'Center Mode', 'styler' ),
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

        $cats = get_terms(
            array(
                'taxonomy' => 'product_cat',
                'number'   => $settings['post_per_page'],
                'order'    => $settings['order'],
                'orderby'  => $settings['orderby'],
                'exclude'  => $settings['category_exclude']
            )
        );

        $type = 'class="styler-category-grid row"';
        if ( 'slider' == $settings['type'] ) {
            $rtl        = is_rtl() ? 'true' : 'false';
            $isrtl      = is_rtl() ? 'is-rtl' : '';
            $dots       = 'yes' == $settings['dots'] ? 'true': 'false';
            $autoplay   = 'yes' == $settings['autoplay'] ? 'true': 'false';
            $centermode = 'yes' == $settings['centermode'] ? 'true': 'false';
            $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

            $type = 'class="styler-product-category-slider styler-slick styler-slick-slider'.$editmode.$isrtl.'" data-slick=\'{"rtl":'.$rtl.',"autoplay":'.$autoplay.',"infinite": false,"speed": '.$settings['speed'].',"slidesToShow": '.$settings['mditems'].',"slidesToScroll": '.$settings['mditems'].',"adaptiveHeight": false,"dots": '.$dots.',"arrows": false,"centerMode":'.$centermode.',"responsive": [{"breakpoint": 1025,"settings": {"slidesToShow": '.$settings['mditems'].',"slidesToScroll": '.$settings['mditems'].'}},{"breakpoint": 790,"settings": {"slidesToShow": '.$settings['smitems'].',"slidesToScroll": '.$settings['smitems'].'}},{"breakpoint": 576,"settings": {"slidesToShow": '.$settings['xsitems'].',"slidesToScroll": '.$settings['xsitems'].'}}]}\'';
        }

        echo '<div '.$type.'>';
            foreach ( $cats as $cat ) {
                $imgid = get_term_meta( $cat->term_id, 'thumbnail_id', true );
                echo '<div class="styler-category-item col-12 col-sm-2 col-md-3">';
                    echo '<div class="styler-category-item-inner">';
                        echo '<a href="'.esc_url( get_term_link( $cat ) ).'" title="'.$cat->name.'">';
                            if ( $imgid ) {
                                echo '<div class="styler-category-thumb img-'.$settings['img_alignment'].'">';
                                    echo wp_get_attachment_image( $imgid, $size, false, ['srcset'=> '', 'class'=>'styler-category-item-image'] );
                                echo '</div>';
                            }
                            echo '<div class="styler-category-content">';
                                $catcounts = $cat->count > 0 && $cat->count == 1 ? esc_html__('Product', 'styler') : esc_html__('Products', 'styler');
                                $count = 'yes' == $settings['cat_count'] ? '<span class="styler-category-count"> / '.$cat->count.' '.$catcounts.'</span>' : '';
                                echo '<'.$settings['tag'].' class="styler-category-title">'.$cat->name.$count.'</'.$settings['tag'].'>';
                                if ( $cat->description && 'yes' == $settings['cat_desc'] ) {
                                    echo '<p class="styler-category-description">'.$cat->description.'</p>';
                                }
                            echo '</div>';
                        echo '</a>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() && 'slider' == $settings['type'] ) { ?>
            <script>
            jQuery( document ).ready( function($) {
                $('.styler-slick-slider-<?php echo $id ?>').not('.slick-initialized').slick();
            });
            </script>
            <?php
        }
    }
}
