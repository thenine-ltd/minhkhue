<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Products_List extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-products-list';
    }
    public function get_title() {
        return 'WC Products List (N)';
    }
    public function get_icon() {
        return 'eicon-post-list';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
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
                    'featured' => esc_html__( 'Featured', 'styler' ),
                    'on-sale' => esc_html__( 'On Sale', 'styler' ),
                    'best' => esc_html__( 'Best Selling', 'styler' ),
                    'custom' => esc_html__( 'Specific Categories', 'styler' )
                ],
                'default' => 'custom'
            ]
        );
        $this->add_control( 'post_per_page',
            [
                'label' => esc_html__( 'Posts Per Page', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'default' => 2
            ]
        );
        $this->add_control( 'category_filter_heading',
            [
                'label' => esc_html__( 'CATEGORY', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
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
            ]
        );
        $this->add_control( 'post_filter_heading',
            [
                'label' => esc_html__( 'POST', 'styler' ),
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
            ]
        );
        $this->add_control( 'post_other_heading',
            [
                'label' => esc_html__( 'OTHER FILTER', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
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
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('post_section',
            [
                'label'=> esc_html__( 'POST', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'spacing',
            [
                'label' => esc_html__( 'Bottom Spacing', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'max' => 100
                    ]
                ],
                'selectors' => [ '{{WRAPPER}} .styler-list-product-item:not(:last-child)' => 'margin-bottom: {{SIZE}}px;' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            ]
        );
        $this->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'All Products',
                'label_block' => true
            ]
        );
        $this->add_control( 'link_title',
            [
                'label' => esc_html__( 'Link Text', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'View All'
            ]
        );
        $this->add_control( 'link',
            [
                'label' => esc_html__( 'Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('style_section',
            [
                'label'=> esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );

        $this->add_control( 'box_sdivider',
            [
                'label' => esc_html__( 'ITEM BOX', 'styler' ),
                'type' => Controls_Manager::HEADING,
            ]
        );
        $this->add_control( 'box_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-item' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'box_hvrbgcolor',
            [
                'label' => esc_html__( 'Hover Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-item:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'box_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-list-product-item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'box_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-list-product-item'
            ]
        );
        $this->add_responsive_control( 'box_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'selectors' => ['{{WRAPPER}} .styler-list-product-item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'box_bxshodow',
                'label' => esc_html__( 'Box Shadow', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-list-product-item'
            ]
        );
        $this->add_control( 'boxtitle_sdivider',
            [
                'label' => esc_html__( 'TITLE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'boxtitle_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-top .title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'name_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-list-product-top .title'
            ]
        );
        $this->add_control( 'toplink_sdivider',
            [
                'label' => esc_html__( 'LINK', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'toplink_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-top .view-all' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'toplink_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-top .view-all:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'toplink_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-list-product-top .view-all'
            ]
        );
        $this->add_control( 'title_sdivider',
            [
                'label' => esc_html__( 'ITEM TITLE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-content .title a' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'title_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-list-product-content .title a:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-list-product-content .title a'
            ]
        );
        $this->add_control( 'stars_heading',
            [
                'label' => esc_html__( 'STARS', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'stars_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-list-product-content .rating i' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->add_control( 'price_heading',
            [
                'label' => esc_html__( 'PRICE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-list-product-content p' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->add_control( 'sale_price_color',
            [
                'label' => esc_html__( 'Save Price Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-list-product-content p span' => 'color: {{VALUE}};',
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        //global $wp_query;
        $settings = $this->get_settings_for_display();
        $elementid = $this->get_id();

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => $settings['post_per_page'],
            'post__in' => $settings['post_include'],
            'post__not_in' => $settings['post_exclude'],
            'order' => $settings['order']
        );

        if ( 'featured' == $settings['scenario'] ) {
           $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_visibility',
                    'field' => 'name',
                    'terms' => 'featured',
                )
            );

        } elseif( 'on-sale' == $settings['scenario'] ) {

            $args['meta_query'] = array(
                'relation' => 'OR',
                array( // Simple products type
                    'key' => '_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                ),
                array( // Variable products type
                    'key' => '_min_variation_sale_price',
                    'value' => 0,
                    'compare' => '>',
                    'type' => 'numeric'
                )
            );

        } elseif( 'best' == $settings['scenario'] ) {

            $args['orderby'] = 'meta_value_num';
            $args['meta_key'] = 'total_sales';

        } else {

            $args['orderby'] = $settings['orderby'];

        }

        if ( $settings['category_include'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $settings['category_include'],
                    'operator' => 'IN'
                )
            );
        }
        if ( $settings['category_exclude'] ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'product_cat',
                    'field' => 'id',
                    'terms' => $settings['category_exclude'],
                    'operator' => 'NOT IN'
                )
            );
        }

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size = [ $sizew, $sizeh ];
        }

        $the_query = new \WP_Query( $args );
        if( $the_query->have_posts() ) {

            if ( $settings['title'] ) {
                echo '<div class="styler-list-product-top">';
                    echo '<h4 class="title">'.$settings['title'].'</h4>';
                    echo '<a href="'.$settings['link']['url'].'" class="view-all">'.$settings['link_title'].'</a>';
                echo '</div>';
            }

            while ( $the_query->have_posts() ) {
                $the_query->the_post();
                global $product;

                echo '<div class="woocommerce styler-list-product-item layout-'.$settings['layout'].'">';
                    echo '<div class="styler-list-product-thumb">';
                        echo '<a href="'.get_permalink().'">';
                            echo get_the_post_thumbnail( $product->get_id(), $size );
                        echo '</a>';
                    echo '</div>';
                    echo '<div class="styler-list-product-content">';
                        echo '<h6 class="title"><a href="'.get_permalink().'">'.get_the_title().'</a></h6>';
                        echo '<p>'.wc_price( $product->get_price() ).'</p>';

                        if ( wc_review_ratings_enabled() ) {
                            echo '<div class="rating">';
                                woocommerce_template_loop_rating();
                            echo '</div>';
                        }
                    echo '</div>';

                    styler_product_badge();

                echo '</div>';
            }

        } else {
            echo '<p>No product found</p>';
        }
        wp_reset_postdata();

    }
}
