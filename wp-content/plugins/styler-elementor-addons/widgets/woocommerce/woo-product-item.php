<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Woo_Product_Item extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-woo-product-item';
    }
    public function get_title() {
        return 'WC Product (N)';
    }
    public function get_icon() {
        return 'eicon-image-box';
    }
    public function get_categories() {
        return [ 'styler-woo' ];
    }
    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'wc', 'woo', 'product' ];
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
        $this->add_control( 'filter_by',
            [
                'label' => esc_html__( 'Show Product By', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => 'true',
                'default' => 'title',
                'options' => [
                    'title' => esc_html__( 'Title', 'styler' ),
                    'sku' => esc_html__( 'SKU', 'styler' )
                ]
            ]
        );
        // Post Filter Heading
        $this->add_control( 'post_filter_heading',
            [
                'label' => esc_html__( 'Post Filter', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        // Specific Post
        $this->add_control( 'post_filter',
            [
                'label' => esc_html__( 'Specific Post(s)', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->get_all_posts_by_type('product'),
                'description' => 'Select Specific Post(s)',
                'condition' => [ 'filter_by' => 'title' ]
            ]
        );
        // Specific Post
        $this->add_control( 'post_skus',
            [
                'label' => esc_html__( 'Specific SKU', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->styler_woo_get_skus(),
                'description' => 'Select Specific SKU',
                'condition' => [ 'filter_by' => 'sku' ]
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
        $settings  = $this->get_settings_for_display();
        $elementid = $this->get_id();

        $args = array(
            'post_type' => 'product',
            'posts_per_page' => 1,
        );

        if ( 'sku' == $settings['filter_by'] ) {

            if ( $settings['post_skus'] ) {

                $args['meta_query'] = [
                    [
                        'key' => '_sku',
                        'value' => $settings['post_skus'],
                        'compare' => '='
                    ]
                ];
            }

        } else {

            if ( $settings['post_filter'] ) {
                $args['post__in'] = array($settings['post_filter']);
            }
        }

        $the_query = new \WP_Query( $args );
        if ( $the_query->have_posts() ) {

            echo '<div class="product-item woocommerce">';
                while ( $the_query->have_posts() ) {
                    $the_query->the_post();

                    wc_get_template_part( 'content', 'product' );

                }
            echo '</div>';
        }
        wp_reset_postdata();

    }
}
