<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_WC_Product_Rating extends Widget_Base {

	public function get_name() {
		return 'styler-wc-product-rating';
	}

	public function get_title() {
		return __( 'Product Rating', 'styler' );
	}

	public function get_icon() {
		return 'eicon-product-rating';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'rating', 'review', 'comments', 'stars', 'product' ];
	}
    public function get_categories() {
		return [ 'styler-woo-product' ];
	}
	protected function register_controls() {

		$this->start_controls_section(
			'section_product_rating_style',
			[
				'label' => __( 'Style', 'styler' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'star_color',
			[
				'label' => __( 'Star Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'empty_star_color',
			[
				'label' => __( 'Empty Star Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .star-rating::before' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'link_color',
			[
				'label' => __( 'Link Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-review-link' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .woocommerce-review-link',
			]
		);

		$this->add_control(
			'star_size',
			[
				'label' => __( 'Star Size', 'styler' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .star-rating' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_control(
			'space_between',
			[
				'label' => __( 'Space Between', 'styler' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', 'em' ],
				'default' => [
					'unit' => 'em',
				],
				'range' => [
					'em' => [
						'min' => 0,
						'max' => 4,
						'step' => 0.1,
					],
					'px' => [
						'min' => 0,
						'max' => 50,
						'step' => 1,
					],
				],
				'selectors' => [
					'.woocommerce:not(.rtl) {{WRAPPER}} .star-rating' => 'margin-right: {{SIZE}}{{UNIT}}',
					'.woocommerce.rtl {{WRAPPER}} .star-rating' => 'margin-left: {{SIZE}}{{UNIT}}',
				],
			]
		);

		$this->add_responsive_control(
			'alignment',
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
					'justify' => [
						'title' => __( 'Justified', 'styler' ),
						'icon' => 'eicon-text-align-justify',
					],
				],
				'prefix_class' => 'stylerduct-rating--align-',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		if ( ! post_type_supports( 'product', 'comments' ) ) {
			return;
		}

        global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}

		wc_get_template( 'single-product/rating.php' );
	}

	public function render_plain_content() {}
}
