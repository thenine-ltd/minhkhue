<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Slider extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-slider';
    }
    public function get_title() {
        return 'WC Slider (N)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    public function get_style_depends() {
        return [ 'styler-swiper' ];
    }
    public function get_script_depends() {
        return [ 'styler-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_section',
            [
                'label' => esc_html__( 'Query', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'scenario',
            [
                'label' => esc_html__( 'Select Scenario', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'Newest', 'styler' ),
                    'featured' => esc_html__( 'Featured', 'styler' ),
                    'on-sale' => esc_html__( 'On Sale', 'styler' ),
                    'best' => esc_html__( 'Best Selling', 'styler' ),
                    'custom' => esc_html__( 'Specific Categories', 'styler' ),
                ],
                'default' => ''
            ]
        );
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 20
            ]
        );
        $this->add_control( 'category_filter_heading',
            [
                'label' => esc_html__( 'Category Filter', 'styler' ),
                'type' => Controls_Manager::HEADING
            ]
        );
        $this->add_control( 'category_include',
            [
                'label' => esc_html__( 'Category', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s)',
                'condition' => [ 'scenario' => 'custom' ]
            ]
        );
        $this->add_control( 'category_exclude',
            [
                'label' => esc_html__( 'Exclude Category', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s) to Exclude',
                'condition' => [ 'scenario!' => 'custom' ]
            ]
        );
        $this->add_control( 'tag_filter_type',
            [
                'label' => esc_html__( 'Tag Filter Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    '' => esc_html__( 'None', 'styler' ),
                    'include' => esc_html__( 'Include', 'styler' ),
                    'exclude' => esc_html__( 'Exclude', 'styler' ),
                ],
                'default' => ''
            ]
        );
        $this->add_control( 'tag_filter',
            [
                'label' => esc_html__( 'Tag', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_cpt_taxonomies('product_tag'),
                'description' => 'Select Tag(s)',
                'condition' => [ 'tag_filter_type!' => '' ]
            ]
        );
        $this->add_control( 'post_filter_heading',
            [
                'label' => esc_html__( 'Post Filter', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'post_include',
            [
                'label' => esc_html__( 'Specific Post(s)', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Specific Post(s)',
                'condition' => [ 'scenario' => 'custom' ]
            ]
        );
        $this->add_control( 'post_exclude',
            [
                'label' => esc_html__( 'Exclude Post', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Post(s) to Exclude',
                'separator' => 'after',
            ]
        );
        $this->add_control( 'post_other_heading',
            [
                'label' => esc_html__( 'Other Filter', 'styler' ),
                'type' => Controls_Manager::HEADING
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
                'default' => 'DESC'
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
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
                'default' => 'styler-mini',
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
       /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
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
                'default' => 30
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
                'max' => 8,
                'step' => 1,
                'default' => 5
            ]
        );
        $this->add_control( 'smitems',
            [
                'label' => esc_html__( 'Items Tablet', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 3,
                'step' => 1,
                'default' => 3
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
        $this->start_controls_section('product_style_section',
            [
                'label'=> esc_html__( 'PRODUCT STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'title_color',
            [
                'label' => esc_html__( 'Title Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-product .styler-product-name,
                    {{WRAPPER}} .styler-product .styler-btn-small,
                    {{WRAPPER}} .styler-has-hidden-cart .styler-btn-small,
                    {{WRAPPER}} .styler-block-right .styler-btn-small' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'addto_color',
            [
                'label' => esc_html__( 'Add to Cart Title Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-product .styler-btn-small,
                    {{WRAPPER}} .styler-has-hidden-cart .styler-btn-small,
                    {{WRAPPER}} .styler-block-right .styler-btn-small' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'price_color',
            [
                'label' => esc_html__( 'Price Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .styler-product .styler-price,
                    {{WRAPPER}} .woocommerce-variation-price .price span.del>span,
                    {{WRAPPER}} .styler-price span.del>span' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'stars_color',
            [
                'label' => esc_html__( 'Star Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .star-rating::before' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'stars_rated_color',
            [
                'label' => esc_html__( 'Star Rated Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .star-rating>span::before' => 'color:{{VALUE}};' ]
            ]
        );
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
                'condition' => [ 'dots' => 'yes' ]
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
    }
    public function image_custom_size() {
        $settings = $this->get_settings_for_display();
        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : styler_settings('product_imgsize','styler-mini');
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }
        return $size;
    }
    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        global $wp_query;
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();

        $args = array(
            'post_type'      => 'product',
            'posts_per_page' => $settings['post_per_page'],
            'post__in'       => $settings['post_include'],
            'post__not_in'   => $settings['post_exclude'],
            'order'          => $settings['order']
        );

        if ( 'featured' == $settings['scenario'] ) {
            $args['tax_query'] = array(
                'relation' => 'AND',
                array(
                    'taxonomy' => 'product_visibility',
                    'field'    => 'name',
                    'terms'    => 'featured'
                )
            );

            if ( $settings['category_exclude'] ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $settings['category_exclude'],
                    'operator' => 'NOT IN'
                );
            }

            if ( '' != $settings['tag_filter_type'] && $settings['tag_exclude'] ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'id',
                    'terms'    => $settings['tag_filter'],
                    'operator' => 'include' == $settings['tag_filter_type'] ? 'IN' : 'NOT IN'
                );
            }

        } elseif ( 'on-sale' == $settings['scenario'] ) {

            $args['meta_query'] = array(
                'relation' => 'OR',
                array( // Simple products type
                    'key'     => '_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric'
                ),
                array( // Variable products type
                    'key'     => '_min_variation_sale_price',
                    'value'   => 0,
                    'compare' => '>',
                    'type'    => 'numeric'
                )
            );

        } elseif ( 'best' == $settings['scenario'] ) {

            $args['orderby']  = 'meta_value_num';
            $args['meta_key'] = 'total_sales';

        } else {

            $args['orderby'] = $settings['orderby'];

        }

        if ( 'custom' == $settings['scenario'] ) {
            $args['tax_query'] = array(
                'relation' => 'AND'
            );
            if ( $settings['category_include'] ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_cat',
                    'field'    => 'id',
                    'terms'    => $settings['category_include'],
                    'operator' => 'IN'
                );
            }
            if ( '' != $settings['tag_filter_type'] && $settings['tag_filter'] ) {
                $args['tax_query'][] = array(
                    'taxonomy' => 'product_tag',
                    'field'    => 'id',
                    'terms'    => $settings['tag_filter'],
                    'operator' => 'include' == $settings['tag_filter_type'] ? 'IN' : 'NOT IN'
                );
            }
        }

        $editmode   = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';

        $slider_options = json_encode( array(
                "slidesPerView" => 1,
                "touchRatio"    => 2,
                "loop"          => 'yes' == $settings['loop'] ? true: false,
                "autoHeight"    => false,
                "autoplay"      => 'yes' == $settings['autoplay'] ? true: false,
                "centeredSlides"=> 'yes' == $settings['centermode'] ? true: false,
                "speed"         => $settings['speed'],
                "spaceBetween"  => $settings['space'] ? $settings['space'] : 30,
                "direction"     => "horizontal",
                "navigation"    => [
                    "nextEl" => ".styler-products-widget-slider .slide-next-$id",
                    "prevEl" => ".styler-products-widget-slider .slide-prev-$id"
                ],
                "pagination"    => [
                    "el"        => ".styler-products-widget-slider .styler-pagination-$id",
                    "type"      => "bullets",
                    "clickable" => true
                ],
                "freeMode"      => [
                    "enabled"         => true,
                    "minimumVelocity" => 0.001
                ],
                "breakpoints"   => [
                    "0" => [
                        "freeMode" => [
                            "enabled"         => true,
                            "minimumVelocity" => 0.001
                        ],
                        "slidesPerView"  => $settings['xsitems'],
                        "slidesPerGroup" => $settings['xsitems']
                    ],
                    "768" => [
                        "freeMode" => [
                            "enabled"         => true,
                            "minimumVelocity" => 0.001
                        ],
                        "slidesPerView"  => $settings['smitems'],
                        "slidesPerGroup" => $settings['smitems']
                    ],
                    "1024" => [
                        "freeMode"       => false,
                        "slidesPerView"  => $settings['mditems'],
                        "slidesPerGroup" => $settings['mditems']
                    ]
                ]
            )
        );

        add_filter( 'styler_product_thumb_size', [$this, 'image_custom_size' ] );
        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {
            echo '<div class="styler-products-widget-slider styler-swiper-theme-style swiper-container styler-swiper-slider'.$editmode.'" data-swiper-options=\''.$slider_options.'\'>';
                echo '<div class="swiper-wrapper">';
                    while ( $the_query->have_posts() ) {
                        $the_query->the_post();
                        $product = new \WC_Product(get_the_ID());
                        $visibility = $product->get_catalog_visibility();

                        if ( $visibility != 'hidden' ) {
                            echo '<div class="swiper-slide product_item visibility-'.$visibility.'">';
                                wc_get_template_part( 'content', 'product' );
                            echo '</div>';
                        }
                    }
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

            echo '</div>';

        }
        wp_reset_postdata();

        remove_filter( 'styler_product_thumb_size', [$this, 'image_custom_size' ] );

        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
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
