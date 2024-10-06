<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_WC_Page_Title extends Widget_Base {

	public function get_name() {
		return 'styler-wc-page-title';
	}

	public function get_title() {
		return __( 'Woo Archive Title', 'styler' );
	}

	public function get_icon() {
		return 'eicon-product-description';
	}

	public function get_keywords() {
		return [ 'woocommerce', 'woo','title','heading','wc', 'shop', 'store', 'text', 'description', 'category', 'product', 'archive' ];
	}

    public function get_categories() {
		return [ 'styler-woo' ];
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_product_title_style',
			[
				'label' => __( 'Style', 'styler' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag for SEO', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
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
						'icon' => 'eicon-text-align-center'
					],
					'right' => [
						'title' => __( 'Right', 'styler' ),
						'icon' => 'eicon-text-align-right'
					],
					'justify' => [
						'title' => __( 'Justified', 'styler' ),
						'icon' => 'eicon-text-align-justify'
					]
				],
				'selectors' => ['{{WRAPPER}} .styler-page-title' => 'text-align: {{VALUE}}']
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => __( 'Text Color', 'styler' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [ '{{WRAPPER}} .styler-page-title' => 'color: {{VALUE}}' ]
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'label' => __( 'Typography', 'styler' ),
				'selector' => '{{WRAPPER}} .styler-page-title'
			]
		);

		$this->end_controls_section();
	}

	protected function render() {
	    $settings = $this->get_settings_for_display();
        global $post;
        $post_type = get_post_type( $post->ID );
        if ( $post_type == 'elementor_library' ) {
            echo '<'.$settings['tag'].' class="styler-page-title">'.get_the_title().'</'.$settings['tag'].'>';
        } else {
            echo '<'.$settings['tag'].' class="styler-page-title">'.get_the_archive_title().'</'.$settings['tag'].'>';
        }
	}
}
