<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Naturally_Woo_My_Account_Widget extends Widget_Base {
    use Naturally_Helper;
    public function get_name() {
        return 'naturally-woo-my-account';
    }
    public function get_title() {
        return 'WC My Account (N)';
    }
    public function get_icon() {
        return 'eicon-shortcode';
    }
    public function get_categories() {
        return [ 'naturally-woo' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'naturally_woo_my_account_settings',
            [
                'label' => esc_html__( 'May Account Content', 'naturally' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }
    protected function render() {
        if ( class_exists('WooCommerce') ) {
            echo do_shortcode('[woocommerce_my_account]');
        }
    }
}
