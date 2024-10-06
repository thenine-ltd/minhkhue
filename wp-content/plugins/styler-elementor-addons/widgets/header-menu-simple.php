<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Header_Menu_Simple extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-menu-simple';
    }
    public function get_title() {
        return 'Header Menu Simple (N)';
    }
    public function get_icon() {
        return 'eicon-nav-menu';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('menu_general_settings',
            [
                'label' => esc_html__( 'General', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'register_menus',
            [
                'label' => esc_html__( 'Select Menu', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'label_block' => true,
                'options' => $this->registered_nav_menus(),
            ]
        );
        $this->add_control( 'sticky',
            [
                'label' => esc_html__( 'Sticky?', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $this->add_control( 'show_megasubmenu',
            [
                'label' => esc_html__( 'Show Mega submenu for edit?', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'show_submenu',
            [
                'label' => esc_html__( 'Show submenu for edit?', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_responsive_control( 'submenu_offset',
            [
                'label' => esc_html__( 'SubMenu Top Offset', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 500,
                        'step' => 1
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .styler-header-main .submenu.depth_0' => 'top:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .styler-header-main .submenu.depth_0:before' => 'top:-{{SIZE}}{{UNIT}};height:{{SIZE}}{{UNIT}};'
                ]
            ]
        );
        $this->add_control( 'mega_submenu_layout',
            [
                'label' => esc_html__( 'Mega Submenu Horizontal Alignment', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'main-header__one',
                'options' => [
                    'center' => esc_html__( 'center', 'styler' ),
                    'flex-start' => esc_html__( 'flex-start', 'styler' ),
                    'space-around' => esc_html__( 'space-around', 'styler' ),
                    'space-between' => esc_html__( 'space-between', 'styler' ),
                    'space-evenly' => esc_html__( 'space-evenly', 'styler' )
                ],
                'selectors' => [ '{{WRAPPER}} .styler-header-main>ul>li.menu-item-mega-parent>ul.submenu' => 'justify-content:{{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'mega_submenu_col_padding',
            [
                'label' => esc_html__( 'Mega Submenu Column Spacing', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 100,
                        'step' => 1
                    ]
                ],
                'selectors' => ['{{WRAPPER}} .styler-header-main>ul>li.menu-item-mega-parent>ul.submenu.depth_0>li.menu-item' => 'padding: 0 {{SIZE}}{{UNIT}};' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render()
    {
        $settings = $this->get_settings_for_display();
        $id = $this->get_id();
        
        echo '<header class="header-widget">';
 
            echo '<div class="styler-header-top-menu-area">';
                echo '<ul class="navigation primary-menu">';
                    echo wp_nav_menu(
                        array(
                            'menu' => $settings['register_menus'],
                            'container' => '',
                            'container_class' => '',
                            'container_id' => '',
                            'menu_class' => '',
                            'menu_id' => '',
                            'items_wrap' => '%3$s',
                            'before' => '',
                            'after' => '',
                            'link_before' => '',
                            'link_after' => '',
                            'depth' => 4,
                            'echo' => true,
                            'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                            'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                        )
                    );
                echo '</ul>';
            echo '</div>';

            // mobile menu
            echo '<div class="styler-header-mobile">';
                echo '<div class="styler-header-mobile-top">';
                    echo '<div class="mobile-toggle"><svg class="bars" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="feather feather-menu"><line x1="3" y1="12" x2="21" y2="12"></line><line x1="3" y1="6" x2="21" y2="6"></line><line x1="3" y1="18" x2="21" y2="18"></line></svg></div>';
                    echo '<div class="styler-header-mobile-logo">STYLER</div>';
                    echo '<div class="top-action-btn"><span class="styler-cart-count styler-wc-count"></span>'.styler_svg_lists( 'bag', 'styler-svg-icon' ).'</div>';
                echo '</div>';
            
                echo '<div class="styler-header-mobile-content">';
                    echo '<ul class="navigation primary-menu">';
                        echo wp_nav_menu(
                            array(
                                'menu' => $settings['register_menus'],
                                'container' => '',
                                'container_class' => '',
                                'container_id' => '',
                                'menu_class' => '',
                                'menu_id' => '',
                                'items_wrap' => '%3$s',
                                'before' => '',
                                'after' => '',
                                'link_before' => '',
                                'link_after' => '',
                                'depth' => 4,
                                'echo' => true,
                                'fallback_cb' => 'Styler_Wp_Bootstrap_Navwalker::fallback',
                                'walker' => new \Styler_Wp_Bootstrap_Navwalker()
                            )
                        );
                    echo '</ul>';
                echo '</div>';
            echo '</div>';

        echo '</header>';

    }
}
