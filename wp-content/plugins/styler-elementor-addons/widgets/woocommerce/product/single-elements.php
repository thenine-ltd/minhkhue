<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_WC_Single_Elements extends Widget_Base {

	public function get_name() {
		return 'styler-wc-single-elements';
	}

	public function get_title() {
		return __( 'Woo - Single Elements', 'styler' );
	}

	public function get_icon() {
		return 'eicon-woocommerce';
	}

    public function get_categories() {
		return [ 'styler-woo-product' ];
	}

	protected function register_controls() {
		$this->start_controls_section(
			'section_product',
			[
				'label' => __( 'Element', 'styler' ),
			]
		);

		$this->add_control(
			'element',
			[
				'label' => __( 'Element', 'styler' ),
				'type' => Controls_Manager::SELECT,
				'options' => [
					'' => '— ' . __( 'Select', 'styler' ) . ' —',
					'woocommerce_output_product_data_tabs' => __( 'Data Tabs', 'styler' ),
					'woocommerce_template_single_title' => __( 'Title', 'styler' ),
					'woocommerce_template_single_rating' => __( 'Rating', 'styler' ),
					'woocommerce_template_single_price' => __( 'Price', 'styler' ),
					'woocommerce_template_single_excerpt' => __( 'Excerpt', 'styler' ),
					'woocommerce_template_single_meta' => __( 'Meta', 'styler' ),
					'woocommerce_template_single_sharing' => __( 'Sharing', 'styler' ),
					'woocommerce_show_product_sale_flash' => __( 'Sale Flash', 'styler' ),
					'woocommerce_product_additional_information_tab' => __( 'Additional Information Tab', 'styler' ),
					'woocommerce_upsell_display' => __( 'Upsell', 'styler' ),
					'wc_get_stock_html' => __( 'Stock Status', 'styler' ),
				],
			]
		);

		$this->end_controls_section();
	}

	public function remove_description_tab( $tabs ) {
		unset( $tabs['description'] );

		return $tabs;
	}

	private function get_element() {
        global $product;

		$product = wc_get_product();

		if ( empty( $product ) ) {
			return;
		}
        
		$settings = $this->get_settings();
		$html = '';

		switch ( $settings['element'] ) {
			case '':
				break;

			case 'wc_get_stock_html':
				$html = wc_get_stock_html( $product );
				break;

			case 'woocommerce_output_product_data_tabs':
				add_filter( 'woocommerce_product_tabs', [ $this, 'remove_description_tab' ], 11 /* after default tabs*/ );
				ob_start();
				woocommerce_output_product_data_tabs();
				// Wrap with the internal woocommerce `product` class
				$html = '<div class="product">' . ob_get_clean() . '</div>';
				remove_filter( 'woocommerce_product_tabs', [ $this, 'remove_description_tab' ], 11 );
				break;

			case 'woocommerce_template_single_rating':
				$is_edit_mode = Plugin::elementor()->editor->is_edit_mode();

				if ( 'no' === get_option( 'woocommerce_enable_review_rating' ) ) {
					if ( $is_edit_mode ) {
						$html = __( 'Admin Notice:', 'styler' ) . ' ' . __( 'Please enable the Review Rating', 'styler' );
					}
					break;
				}

				ob_start();
				woocommerce_template_single_rating();
				$html = ob_get_clean();
				if ( '' === $html && $is_edit_mode ) {
					$html = __( 'Admin Notice:', 'styler' ) . ' ' . __( 'No Rating Reviews', 'styler' );
				}
				break;

			default:
				if ( is_callable( $settings['element'] ) ) {
					$html = call_user_func( $settings['element'] );
				}
		}

		return $html;
	}

	protected function render() {
		echo $this->get_element();
	}

	public function render_plain_content() {}
}
