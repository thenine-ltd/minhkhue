<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Grid extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-grid';
    }
    public function get_title() {
        return 'WC Products Grid (N)';
    }
    public function get_icon() {
        return 'eicon-gallery-grid';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'product', 'wc' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'post_query_scenario_section',
            [
                'label' => esc_html__( 'Query', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control( 'col',
            [
                'label' => esc_html__( 'Column', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 6,
                'default' => 4,
                'selectors' => ['{{WRAPPER}} .styler-products.row>.col,{{WRAPPER}} .styler-products.row>div' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );width: calc(100% / {{VALUE}} );']
            ]
        );
        $this->add_control('column_gap',
            [
                'label' => __( 'Columns Gap', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .styler-products.row>.col,{{WRAPPER}} .styler-products.row>div' => 'padding: 0 {{SIZE}}px;margin-bottom: {{SIZE}}px;',
                    '{{WRAPPER}} .styler-products.row' => 'margin: 0 -{{SIZE}}px -{{SIZE}}px -{{SIZE}}px;'
                ]
            ]
        );
        $this->add_control( 'limit',
            [
                'label' => esc_html__( 'Posts Per Page', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => count( get_posts( array('post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => '-1') ) ),
                'default' => 12
            ]
        );
        $this->add_control( 'scenario',
            [
                'label' => esc_html__( 'Select Scenerio', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'newest' => esc_html__( 'Newest', 'styler' ),
                    'featured' => esc_html__( 'Featured', 'styler' ),
                    'popularity' => esc_html__( 'Popularity', 'styler' ),
                    'best' => esc_html__( 'Best Selling', 'styler' ),
                    'attr' => esc_html__( 'Attribute Display', 'styler' ),
                    'custom_cat' => esc_html__( 'Specific Categories', 'styler' ),
                ],
                'default' => 'newest'
            ]
        );
        $this->add_control( 'paginate',
            [
                'label' => esc_html__( 'Pagination', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        $this->add_control( 'hr0',
            [
                'type' => Controls_Manager::DIVIDER,
                'condition' => [ 'scenario' => 'attr' ]
            ]
        );
        $this->add_control( 'attribute',
            [
                'label' => esc_html__( 'Select Attribute', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_woo_attributes(),
                'description' => 'Select Attribute(s)',
                'condition' => [ 'scenario' => 'attr' ]
            ]
        );
        $this->add_control( 'attr_terms',
            [
                'label' => esc_html__( 'Select Attribute Terms', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_woo_attributes_taxonomies(),
                'description' => 'Select Attribute(s)',
                'condition' => [ 'scenario' => 'attr' ]
            ]
        );
        $this->add_control( 'hr1',['type' => Controls_Manager::DIVIDER]);

        $this->add_control( 'cat_filter',
            [
                'label' => esc_html__( 'Filter Category', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_cpt_taxonomies('product_cat'),
                'description' => 'Select Category(s)',
            ]
        );
        $this->add_control( 'cat_operator',
            [
                'label' => esc_html__( 'Category Operator', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'AND' => esc_html__( 'display all of the chosen categories', 'styler' ),
                    'IN' => esc_html__( 'display styler-products within the chosen category', 'styler' ),
                    'NOT IN' => esc_html__( 'display styler-products that are not in the chosen category.', 'styler' ),
                ],
                'default' => 'AND',
                'condition' => [ 'scenario' => 'custom_cat' ]
            ]
        );

        $this->add_control( 'hr2',['type' => Controls_Manager::DIVIDER]);

        $this->add_control( 'tag_filter',
            [
                'label' => esc_html__( 'Filter Tag(s)', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'options' => $this->styler_cpt_taxonomies('product_tag','name'),
                'description' => 'Select Tag(s)',
            ]
        );
        $this->add_control( 'tag_operator',
            [
                'label' => esc_html__( 'Tags Operator', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'AND' => esc_html__( 'display all of the chosen tags', 'styler' ),
                    'IN' => esc_html__( 'display styler-products within the chosen tags', 'styler' ),
                    'NOT IN' => esc_html__( 'display styler-products that are not in the chosen tags.', 'styler' ),
                ],
                'default' => 'AND',
            ]
        );

        $this->add_control( 'hr3',['type' => Controls_Manager::DIVIDER]);

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
                    'popularity' => esc_html__( 'Popularity', 'styler' ),
                    'rand' => esc_html__( 'Random', 'styler' ),
                    'rating' => esc_html__( 'Rating', 'styler' ),
                    'date' => esc_html__( 'Date', 'styler' ),
                    'title' => esc_html__( 'Title', 'styler' ),
                ],
                'default' => 'id',
                'condition' => [ 'scenario!' => 'custom_cat' ]
            ]
        );
        $this->add_control( 'cat_orderby',
            [
                'label' => esc_html__( 'Order By', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'options' => [
                    'id' => esc_html__( 'Post ID', 'styler' ),
                    'menu_order' => esc_html__( 'Menu Order', 'styler' ),
                    'name' => esc_html__( 'Name', 'styler' ),
                    'slug' => esc_html__( 'Slug', 'styler' ),
                ],
                'default' => 'id',
                'condition' => [ 'scenario' => 'custom_cat' ]
            ]
        );
        $this->add_control( 'show_cat_empty',
            [
                'label' => esc_html__( 'Show Empty Categories', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'styler' ),
                'label_off' => esc_html__( 'No', 'styler' ),
                'return_value' => 'yes',
                'default' => 'no',
                'condition' => [ 'scenario' => 'custom_cat' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
                'name' => 'thumbnail',
				'default' => 'woocommerce-thumbnail'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('post_style_section',
            [
                'label' => esc_html__( 'Post Style', 'styler' ),
                'tab' => Controls_Manager::TAB_STYLE,
            ]
        );
        $this->add_responsive_control( 'post_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .woocommerce.styler-product' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->add_control( 'post_bgcolor',
            [
                'label' => esc_html__( 'Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .woocommerce.styler-product' => 'background-color: {{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'post_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .woocommerce.styler-product'
            ]
        );
        $this->add_group_control(
            Group_Control_Box_Shadow::get_type(),
            [
                'name' => 'post_item_box_shadow',
                'label' => esc_html__( 'Box Shadow', 'styler' ),
                'selector' => '{{WRAPPER}} .woocommerce.styler-product'
            ]
        );
        $this->add_control( 'title_heading',
            [
                'label' => esc_html__( 'TITLE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .woocommerce.styler-product .styler-product-name'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .woocommerce.styler-product .styler-product-name' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_heading',
            [
                'label' => esc_html__( 'PRICE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'price_color',
            [
                'label' => esc_html__( 'Price Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .woocommerce.styler-product span.del > span' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'price_color2',
            [
                'label' => esc_html__( 'Price Color 2', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}}  div.product .woocommerce.styler-product .styler-price' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'price_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} div.product .woocommerce.styler-product .styler-price'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    public function thumb_size() {
        $settings = $this->get_settings_for_display();
        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : 'full';
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        return $size;
    }
    
    protected function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
        $settings  = $this->get_settings_for_display();
        $elementid = $this->get_id();

        $limit = 'limit="'.$settings['limit'].'"';
        $order = ' order="'.$settings['order'].'"';
        $orderby = ' orderby="'.$settings['orderby'].'"';
        $paginate = 'yes'== $settings['paginate'] ? ' paginate="true"' : '';
        $hide_empty = 'yes'== $settings['show_cat_empty'] ? ' hide_empty="0"' : '';
        $operator = ' cat_operator="'.$settings['cat_operator'].'"';
        $tag_operator = ' tag_operator="'.$settings['tag_operator'].'"';
        $cat_orderby = ' orderby="'.$settings['cat_orderby'].'"';
        $cat_filter = is_array($settings['cat_filter']) ? ' category="'.implode(', ',$settings['cat_filter']).'"' : '';
        $hide_empty_cat = 'yes'== $settings['show_cat_empty'] ? ' hide_empty="0"' : '';
        $tag_filter = is_array($settings['tag_filter']) ? ' tag="'.implode(', ',$settings['tag_filter']).'"' : '';
        $attr_filter = is_array($settings['attribute']) ? ' attribute="'.implode(', ',$settings['attribute']).'"' : '';
        $attr_terms = is_array($settings['attr_terms']) ? ' terms="'.implode(', ',$settings['attr_terms']).'"' : '';

        add_filter( 'styler_product_thumb_size', [ $this, 'thumb_size' ] );
        echo '<div class="section-custom-categories">';
            if ( 'newest' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$orderby.$order.$tag_filter.$paginate.' visibility="visible"]');
            } elseif ( 'featured' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$orderby.$order.$tag_filter.$paginate.' visibility="featured"]');
            } elseif ( 'popularity' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$order.$tag_filter.$paginate.' orderby="popularity" on_sale="true"]');
            } elseif ( 'best' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$orderby.$order.$cat_filter.$operator.$hide_empty_cat.$tag_filter.$paginate.' best_selling="true"]');
            } elseif ( 'custom_cat' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$cat_orderby.$order.$cat_filter.$operator.$hide_empty_cat.$tag_filter.$paginate.']');
            } elseif ( 'attr' == $settings['scenario'] ) {
                echo do_shortcode('[products '.$limit.$attr_filter.$attr_terms.$limit.$orderby.$order.$paginate.']');
            } else {
                echo do_shortcode('[products '.$limit.$orderby.$order.$tag_filter.$operator.$paginate.' visibility="visible"]');
            }
        echo '</div>';
        remove_filter( 'styler_product_thumb_size', [ $this, 'thumb_size' ] );
    }
}
