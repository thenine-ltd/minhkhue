<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_WC_Categories extends Widget_Base {

	protected $_has_template_content = false;

	public function get_name() {
		return 'styler-wc-categories';
	}

	public function get_title() {
		return __( 'Product Categories 2', 'styler' );
	}

	public function get_icon() {
		return 'eicon-product-categories';
	}

    public function get_keywords() {
        return [ 'woocommerce', 'shop', 'store', 'cat', 'wc', 'woo', 'product'  ];
    }

    public function get_categories() {
		return [ 'styler-woo' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_layout',
			[
				'label' => __( 'Layout', 'styler' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_responsive_control(
			'columns',
			[
				'label' => __( 'Columns', 'styler' ),
				'type' => Controls_Manager::NUMBER,
				'default' => 4,
				'min' => 1,
				'max' => 12,
				'selectors' => ['{{WRAPPER}} .col.product-category' => '-ms-flex: 0 0 calc(100% / {{VALUE}} );flex: 0 0 calc(100% / {{VALUE}} );max-width: calc(100% / {{VALUE}} );']
			]
		);

		$this->add_control(
			'number',
			[
				'label' => __( 'Categories Count', 'styler' ),
				'type' => Controls_Manager::NUMBER,
				'default' => '4'
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_filter',
			[
				'label' => __( 'Query', 'styler' ),
				'tab' => Controls_Manager::TAB_CONTENT
			]
		);

		$this->add_control(
			'source',
			[
				'label' => __( 'Source', 'styler' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => __( 'Show All', 'styler' ),
					'by_id' => __( 'Manual Selection', 'styler' ),
					'by_parent' => __( 'By Parent', 'styler' ),
					'current_subcategories' => __( 'Current Subcategories', 'styler' )
				],
				'label_block' => true
			]
		);

		$categories = get_terms( 'product_cat' );

		$options = [];
		foreach ( $categories as $category ) {
			$options[ $category->term_id ] = $category->name;
		}

		$this->add_control(
			'categories',
			[
				'label' => __( 'Categories', 'styler' ),
				'type' => Controls_Manager::SELECT2,
				'options' => $options,
				'default' => [],
				'label_block' => true,
				'multiple' => true,
				'condition' => [
					'source' => 'by_id'
				]
			]
		);

		$parent_options = [ '0' => __( 'Only Top Level', 'styler' ) ] + $options;
		$this->add_control(
			'parent',
			[
				'label' => __( 'Parent', 'styler' ),
				'type' => Controls_Manager::SELECT,
				'default' => '0',
				'options' => $parent_options,
				'condition' => [
					'source' => 'by_parent'
				],
			]
		);

		$this->add_control(
			'hide_empty',
			[
				'label' => __( 'Hide Empty', 'styler' ),
				'type' => Controls_Manager::SWITCHER,
				'default' => '',
				'label_on' => 'Hide',
				'label_off' => 'Show'
			]
		);

		$this->add_control(
			'orderby',
			[
				'label' => __( 'Order By', 'styler' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'name',
				'options' => [
					'name' => __( 'Name', 'styler' ),
					'slug' => __( 'Slug', 'styler' ),
					'description' => __( 'Description', 'styler' ),
					'count' => __( 'Count', 'styler' )
				],
			]
		);

		$this->add_control(
			'order',
			[
				'label' => __( 'Order', 'styler' ),
				'type' => Controls_Manager::SELECT,
				'default' => 'desc',
				'options' => [
					'asc' => __( 'ASC', 'styler' ),
					'desc' => __( 'DESC', 'styler' )
				]
			]
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_products_style',
			[
				'label' => __( 'Products', 'styler' ),
				'tab' => Controls_Manager::TAB_STYLE
			]
		);
		$this->add_control(
			'products_class',
			[
				'type' => Controls_Manager::HIDDEN,
				'default' => 'wc-products',
				'prefix_class' => 'styler-wc-categories '
			]
		);
		$this->add_control(
			'column_gap',
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
				    '{{WRAPPER}} .col.product-category' => 'padding: 0 {{SIZE}}px;margin-bottom: {{SIZE}}px;',
				    '{{WRAPPER}} .wc--row' => 'margin: 0 -{{SIZE}}px -{{SIZE}}px -{{SIZE}}px;'
				]
			]
		);
		$this->add_responsive_control(
			'align',
			[
				'label' => __( 'Alignment', 'styler' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => __( 'Left', 'styler' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => __( 'Center', 'styler' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => __( 'Right', 'styler' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => ['{{WRAPPER}} .col.product-category' => 'text-align: {{VALUE}}']
			]
		);

		$this->add_control(
			'heading_image_style',
			[
				'label' => __( 'Image', 'styler' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'image_border',
				'selector' => '{{WRAPPER}} a > img'
			]
		);

		$this->add_responsive_control(
			'image_border_radius',
			[
				'label' => __( 'Border Radius', 'styler' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} a > img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}'
				]
			]
		);

		$this->add_responsive_control(
			'image_spacing',
			[
				'label' => __( 'Spacing', 'styler' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'selectors' => [ '{{WRAPPER}} a > img' => 'margin-bottom: {{SIZE}}{{UNIT}}']
			]
		);

		$this->add_control(
			'heading_title_style',
			[
				'label' => __( 'Title', 'styler' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'title_color',
			[
				'label' => __( 'Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => ['{{WRAPPER}} .woocommerce .woocommerce-loop-category__title' => 'color: {{VALUE}}']
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'selector' => '{{WRAPPER}} .woocommerce .woocommerce-loop-category__title'
			]
		);

		$this->add_control(
			'heading_count_style',
			[
				'label' => __( 'Count', 'styler' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before'
			]
		);

		$this->add_control(
			'count_color',
			[
				'label' => __( 'Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => ['{{WRAPPER}} .woocommerce-loop-category__title .count' => 'color: {{VALUE}}']
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'count_typography',
				'selector' => '{{WRAPPER}} .woocommerce-loop-category__title .count'
			]
		);

		$this->end_controls_section();
	}

	public function render() {
        if ( ! class_exists('WooCommerce') ) {
            return;
        }
		$settings = $this->get_settings();

		$attributes = [
			'number' => $settings['number'],
			'columns' => $settings['columns'],
			'hide_empty' => ( 'yes' === $settings['hide_empty'] ) ? 1 : 0,
			'orderby' => $settings['orderby'],
			'order' => $settings['order'],
		];

		if ( 'by_id' === $settings['source'] ) {
			$attributes['ids'] = implode( ',', $settings['categories'] );
		} elseif ( 'by_parent' === $settings['source'] ) {
			$attributes['parent'] = $settings['parent'];
		} elseif ( 'current_subcategories' === $settings['source'] ) {
			$attributes['parent'] = get_queried_object_id();
		}

		$this->add_render_attribute( 'shortcode', $attributes );

		$shortcode = sprintf( '[product_categories %s]', $this->get_render_attribute_string( 'shortcode' ) );

		echo do_shortcode( $shortcode );
	}

}
