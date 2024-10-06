<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Slide_Show extends Widget_Base {
    public function get_name() {
        return 'styler-slide-show';
    }
    public function get_title() {
        return 'Animated Frame Slideshow (N)';
    }
    public function get_icon() {
        return 'eicon-carousel';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    public function get_script_depends() {
        return [ 'anime','imagesloaded' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'items_settings',
            [
                'label' => esc_html__('Slide Items', 'styler'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        
        $repeater = new Repeater();
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'styler' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'desc',
            [
                'label' => esc_html__( 'Description', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title',
            [
                'label' => esc_html__( 'Button Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'link',
            [
                'label' => esc_html__( 'Button Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $repeater->add_control( 'btn_id',
            [
                'label' => esc_html__( 'Button ID', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title2',
            [
                'label' => esc_html__( 'Button 2 Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_control( 'link2',
            [
                'label' => esc_html__( 'Button 2 Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#',
                    'is_external' => ''
                ],
                'show_external' => true
            ]
        );
        $repeater->add_control( 'btn_id2',
            [
                'label' => esc_html__( 'Button 2 ID', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => ''
            ]
        );
        $repeater->add_responsive_control( 'ihorz_alignment',
            [
                'label' => esc_html__( 'Horizontal Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.styler-slide' => 'align-items: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $repeater->add_responsive_control( 'ivert_alignment',
            [
                'label' => esc_html__( 'Vertical Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.styler-slide' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $repeater->add_responsive_control( 'islide_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} {{CURRENT_ITEM}}.styler-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'ioverlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-img:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ititle_color',
            [
                'label' => esc_html__( 'Title Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-title' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'idesc_color',
            [
                'label' => esc_html__( 'Description Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_bgcolor',
            [
                'label' => esc_html__( 'Button Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_color',
            [
                'label' => esc_html__( 'Button Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link' => 'color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_bgcolor2',
            [
                'label' => esc_html__( 'Button 2 Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link2' => 'background-color:{{VALUE}};' ]
            ]
        );
        $repeater->add_control( 'ibtn_color2',
            [
                'label' => esc_html__( 'Button 2 Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} {{CURRENT_ITEM}} .styler-slide-link2' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'items',
            [
                'label' => esc_html__( 'Items', 'styler' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => 'Image',
                'default' => [
                    [
                        'title' => 'Title 1',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ],
                    [
                        'title' => 'Title 2',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ],
                    [
                        'title' => 'Title 3',
                        'desc' => 'This is a description',
                        'btn_title' => 'Button Title 1'
                    ]
                ]
            ]
        );
        $this->add_responsive_control( 'box_height',
            [
                'label' => esc_html__( 'Slider Height', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', 'vh' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 1500,
                        'step' => 5,
                    ],
                    'vh' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => 'vh',
                    'size' => 100,
                ],
                'selectors' => [ '{{WRAPPER}} .styler-slideshow' => 'height:{{SIZE}}{{UNIT}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Image_Size::get_type(),
            [
            'name' => 'thumbnail',
            'separator' => 'before',
            ]
        );
        $this->add_control( 'tag',
            [
                'label' => esc_html__( 'Title Tag for SEO', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'h2',
                'options' => [
                    'h1' => esc_html__( 'h1', 'styler' ),
                    'h2' => esc_html__( 'h2', 'styler' ),
                    'h3' => esc_html__( 'h3', 'styler' ),
                    'h4' => esc_html__( 'h4', 'styler' ),
                    'h5' => esc_html__( 'h5', 'styler' ),
                    'h6' => esc_html__( 'h6', 'styler' ),
                    'div' => esc_html__( 'div', 'styler' ),
                    'p' => esc_html__( 'p', 'styler' ),
                    'span' => esc_html__( 'span', 'styler' )
                ]
            ]
        );
        $this->add_control( 'prev_title',
            [
                'label' => esc_html__( 'Button Prev Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Previous'
            ]
        );
        $this->add_control( 'next_title',
            [
                'label' => esc_html__( 'Button Next Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Next'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('image_style_section',
            [
                'label'=> esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'horz_alignment',
            [
                'label' => esc_html__( 'Horizontal Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .styler-slide' => 'align-items: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'vert_alignment',
            [
                'label' => esc_html__( 'Vertical Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'selectors' => ['{{WRAPPER}} .styler-slide' => 'justify-content: {{VALUE}};'],
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'center'
            ]
        );
        $this->add_responsive_control( 'slide_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-slide' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_control( 'overlay_color',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slide .styler-slide-img:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'shape_color',
            [
                'label' => esc_html__( 'Shape Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slideshow .shape path' => 'fill:{{VALUE}}!important;' ]
            ]
        );
        $this->add_control( 'title_divider',
            [
                'label' => esc_html__( 'TITLE', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'title_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slide-title' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'title_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-title'
            ]
        );
        $this->add_control( 'desc_divider',
            [
                'label' => esc_html__( 'DESCRIPTION', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'desc_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-slide-desc' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'desc_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-desc'
            ]
        );
        $this->add_control( 'desc_divider',
            [
                'label' => esc_html__( 'BUTTON', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'btn_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-link'
            ]
        );
        $this->start_controls_tabs('styler_btn_tabs');
        $this->start_controls_tab( 'styler_btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'btn_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-slide-link' => 'color: {{VALUE}};']
            ]
        );
        $this->add_responsive_control( 'btn_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-slide-link' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-link',
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-slide-link' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_background',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .styler-slide-link',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab('styler_btn_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
         $this->add_control( 'btn_hvr_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-slide-link:hover' => 'color: {{VALUE}};']
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'btn_hvr_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slide-link:hover',
                'separator' => 'before'
            ]
        );
        $this->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'btn_hvr_background',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .styler-slide-link:hover',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->add_control( 'nav_divider',
            [
                'label' => esc_html__( 'NAVIGATION', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'nav_top_offset',
            [
                'label' => esc_html__( 'Bottom Offset', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -300,
                'max' => 300,
                'step' => 1,
                'default' => 50,
                'selectors' => [ '{{WRAPPER}} .styler-slidenav' => 'bottom:{{SIZE}}px;' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'nav_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-slidenav-item'
            ]
        );
        $this->add_control( 'nav_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-slidenav-item' => 'color: {{VALUE}};']
            ]
        );
        $this->add_control( 'nav_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-slidenav-item:hover' => 'color: {{VALUE}};']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/

    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        $size = $settings['thumbnail_size'] ? $settings['thumbnail_size'] : [1000,1000];
        if ( 'custom' == $size ) {
            $sizew = $settings['thumbnail_custom_dimension']['width'];
            $sizeh = $settings['thumbnail_custom_dimension']['height'];
            $size  = [ $sizew, $sizeh ];
        }
        

        $count = 1;
        echo '<div class="styler-slideshow">';
            echo '<div class="styler-slides">';
                foreach ( $settings['items'] as $item ) {
                    if ( !empty( $item['image']['id'] ) ) {
                        $imgurl = wp_get_attachment_image_url( $item['image']['id'], $size );
                    }
                    $current = $count == 1 ? ' styler-slide-current' : ''; 
                    echo '<div class="styler-slide'.$current.' elementor-repeater-item-' . esc_attr( $item['_id'] ) . '">';
                        
                        if ( !empty( $item['image']['id'] ) ) {
                            echo '<div class="styler-slide-img" style="background-image: url('.$imgurl.')"></div>';
                            if ( !empty( $item['title']) ) {
                                echo '<'.$settings['tag'].' class="styler-slide-title">'.$item['title'].'</'.$settings['tag'].'>';
                            }
                            if ( !empty( $item['desc'] ) ) {
                                echo '<p class="styler-slide-desc">'.$item['desc'].'</p>';
                            }
                            if ( !empty( $item['btn_title'] ) && !empty( $item['btn_title2'] ) ) {
                                echo '<div class="styler-slide-link-wrapper">';
                            }
                            if ( !empty( $item['btn_title'] ) ) {
                                $target   = !empty( $item['link']['is_external'] ) ? ' target="_blank"' : '';
                                $nofollow = !empty( $item['link']['nofollow'] ) ? ' rel="nofollow"' : '';
                                $btn_id   = !empty( $item['btn_id'] ) ? ' id="'.$item['btn_id'].'"' : '';
                                echo '<a class="styler-btn-large styler-btn styler-bg-black styler-slide-link" href="'.$item['link']['url'].'"'.$btn_id.$target.$nofollow.'>'.$item['btn_title'].'</a>';
                            }
                            if ( !empty( $item['btn_title2'] ) ) {
                                $target2   = !empty( $item['link2']['is_external'] ) ? ' target="_blank"' : '';
                                $nofollow2 = !empty( $item['link2']['nofollow'] ) ? ' rel="nofollow"' : '';
                                $btn_id2   = !empty( $item['btn_id2'] ) ? ' id="'.$item['btn_id2'].'"' : '';
                                echo '<a class="styler-btn-large styler-btn styler-bg-black styler-slide-link styler-slide-link2" href="'.$item['link2']['url'].'"'.$btn_id2.$target2.$nofollow2.'>'.$item['btn_title2'].'</a>';
                            }
                            if ( !empty( $item['btn_title'] ) && !empty( $item['btn_title2'] ) ) {
                                echo '</div>';
                            }
                        }
                    echo '</div>';
                    $count++;
                }
            echo '</div>';

            echo '<div class="styler-slidenav">';
                echo '<div class="styler-slidenav-item styler-slidenav-item-prev">'.$settings['prev_title'].'</div>';
                echo '<span>/</span>';
                echo '<div class="styler-slidenav-item styler-slidenav-item-next">'.$settings['next_title'].'</div>';
            echo '</div>';
            
        echo '</div>';
    }
}
