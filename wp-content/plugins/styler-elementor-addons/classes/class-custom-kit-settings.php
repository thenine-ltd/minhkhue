<?php

namespace Elementor;

use Elementor\Controls_Manager;
use Elementor\Core\Kits\Documents\Kit;
use Elementor\Core\Kits\Documents\Tabs\Tab_Base;

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
* Class Theme_Style_Kits
*
* @package Analog\Elementor\Kit\Tabs
*/
class Themee_Style_Kits extends Tab_Base {

    /**
    * Tab ID.
    *
    * @return string
    */
    public function get_id() {
        return 'themee-style-kits';
    }

    /**
    * Tab title.
    *
    * @return string|void
    */
    public function get_title() {
        return __( 'Styler Style Kits', 'ang' );
    }

    /**
    * Tab Group.
    *
    * @return string
    */
    public function get_group() {
        return 'styler-style';
    }

    /**
    * Tab icon.
    *
    * @return string
    */
    public function get_icon() {
        return 'eicon-global-settings';
    }

    /**
    * Tab help URL.
    *
    * @return string
    */
    public function get_help_url() {
        return 'https://docs.analogwp.com/';
    }

    /**
    * Tab controls.
    *
    * Tab controls are hooked mostly on `elementor/element/kit/section_buttons/after_section_end`.
    */
    protected function register_tab_controls() {

        $this->start_controls_section(
            'vsection_' . $this->get_id(),
            [
                'label' => $this->get_title(),
                'tab' => $this->get_id(),
            ]
        );
        $this->add_responsive_control(
            'styler_column_default_padding',
            [
                'label' => esc_html__( 'Default Column Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'frontend_available' => true,
                'prefix_class' => 'styler-column-default-padding',
                'variable'  => 'styler_column_default_padding',
                'selectors' => array(
                    '{{WRAPPER}}' => '--styler_column_default_padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );
        $this->add_responsive_control(
            'styler_column_wide_padding',
            [
                'label' => esc_html__( 'Wide Column Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'frontend_available' => true,
                'prefix_class' => 'styler-column-wide-padding',
                'variable'  => 'styler_column_wide_padding',
                'selectors' => array(
                    '{{WRAPPER}}' => '--styler_column_wide_padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );
        $this->add_responsive_control(
            'styler_column_wider_padding',
            [
                'label' => esc_html__( 'Wider Column Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'frontend_available' => true,
                'prefix_class' => 'styler-column-wider-padding',
                'variable'  => 'styler_column_wider_padding',
                'selectors' => array(
                    '{{WRAPPER}}' => '--styler_column_wider_padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );
        $this->add_responsive_control(
            'styler_column_extended_padding',
            [
                'label' => esc_html__( 'Extended Column Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px', 'em', '%' ],
                'frontend_available' => true,
                'variable'  => 'styler_column_extended_padding',
                'selectors' => array(
                    '{{WRAPPER}}' => '--styler_column_extended_padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
                ),
            ]
        );
        $this->end_controls_section();
    }
}

new Themee_Style_Kits( Kit::class );

/**
* Fires on tabs registering.
*/
add_action(
    'elementor/kit/register_tabs',
    function( $kit ) {
        $kit->register_tab( 'themee-style-kits', Themee_Style_Kits::class );
    }
);
