<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_WC_Product_Short_Description extends Widget_Base {

	public function get_name() {
		return 'styler-wc-product-short-description';
	}

	public function get_title() {
		return __( 'Short Description', 'styler' );
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'shop', 'store', 'text', 'description', 'product' ];
	}
    public function get_categories() {
		return [ 'styler-woo-product' ];
	}
	protected function register_controls() {

		$this->start_controls_section(
			'section_product_description_style',
			[
				'label' => __( 'Style', 'styler' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
		$this->add_responsive_control(
			'text_align',
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
				'selectors' => [
					'{{WRAPPER}}' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .woocommerce-product-details__short-description,{{WRAPPER}} .woocommerce-product-details__short-description p' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'styler' ),
				'selector' => '{{WRAPPER}} .woocommerce-product-details__short-description',
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
		global $product;
		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}
		wc_get_template( 'single-product/short-description.php' );
	}

}