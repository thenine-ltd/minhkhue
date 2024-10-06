<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Instagram_Slider extends Widget_Base {
    public function get_name() {
        return 'styler-instagram-slider';
    }
    public function get_title() {
        return 'Instagram Carousel (N)';
    }
    public function get_icon() {
        return 'eicon-carousel';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    public function get_script_depends() {
        return [ 'instafeed','styler-swiper' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'items_settings',
            [
                'label' => esc_html__('Instagram', 'styler'),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'token',
            [
                'label' => esc_html__( 'Access Token', 'styler' ),
                'type' => Controls_Manager::TEXT
            ]
        );
        $this->add_control( 'limit',
            [
                'label' => esc_html__( 'Limit', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 100,
                'step' => 1,
                'default' => 6
            ]
        );
        $this->add_control( 'target',
            [
                'label' => esc_html__( 'Open Link', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => '_self',
                'options' => [
                    '_blank' => esc_html__( 'New window or tab', 'styler' ),
                    '_self' => esc_html__( 'In the same frame', 'styler' )
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_options_section',
            [
                'label'=> esc_html__( 'SLIDER OPTIONS', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'loop',
            [
                'label' => esc_html__( 'Infinite', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'centermode',
            [
                'label' => esc_html__( 'Center Mode', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'item_opacity',
            [
                'label' => esc_html__( 'Inactive Item Opacity', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.01,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-slide:not(.swiper-slide-active)' => 'opacity:{{SIZE}};' ],
                'condition' => ['centermode' => 'yes']
            ]
        );
        $this->add_control( 'nav',
            [
                'label' => esc_html__( 'Navigation', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no'
            ]
        );
        $this->add_control( 'dots',
            [
                'label' => esc_html__( 'Dots', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'space',
            [
                'label' => esc_html__( 'Space Between Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 30
            ]
        );
        $this->add_control( 'speed',
            [
                'label' => esc_html__( 'Speed', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 100,
                'max' => 10000,
                'step' => 100,
                'default' => 1000
            ]
        );
        $this->add_control( 'mditems',
            [
                'label' => esc_html__( 'Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 20,
                'step' => 1,
                'default' => 3
            ]
        );
        $this->add_control( 'smitems',
            [
                'label' => esc_html__( 'Items Tablet', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 2
            ]
        );
        $this->add_control( 'xsitems',
            [
                'label' => esc_html__( 'Items Phone', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 10,
                'step' => 1,
                'default' => 1
            ]
        );
        $this->add_control( 'effect',
            [
                'label' => esc_html__( 'Effect', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'slide',
                'options' => [
                    'slide' => esc_html__( 'Slide', 'styler' ),
                    'flip' => esc_html__( 'flip', 'styler' ),
                    'coverflow' => esc_html__( 'Coverflow', 'styler' ),
                    'creative' => esc_html__( 'Creative', 'styler' ),
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('image_style_section',
            [
                'label'=> esc_html__( 'IMAGE STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_responsive_control( 'image_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .swiper-slide img' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->start_controls_tabs( 'image_tabs');
        $this->start_controls_tab( 'image_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .swiper-slide img'
            ]
        );
        $this->add_responsive_control( 'image_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .swiper-slide img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'image_hover_tab',
            [ 'label' => esc_html__( 'Active', 'styler' ) ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'image_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .swiper-slide.swiper-slide-active img'
            ]
        );
        $this->add_responsive_control( 'image_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .swiper-slide.swiper-slide-active img' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('navs_style_section',
            [
                'label'=> esc_html__( 'SLIDER NAV STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['nav' => 'yes']
            ]
        );
        $this->add_control( 'navs_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-button-prev:after,{{WRAPPER}} .styler-swiper-theme-style .swiper-button-next:after' => 'font-size:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'navs_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .swiper-button-prev:after,{{WRAPPER}} .styler-swiper-theme-style .swiper-button-next:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'navs_hvrcolor',
            [
                'label' => esc_html__( 'Hover Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-swiper-theme-style .swiper-button-prev:hover:after,{{WRAPPER}} .styler-swiper-theme-style .swiper-button-next:hover:after' => 'color:{{VALUE}};' ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('dots_style_section',
            [
                'label'=> esc_html__( 'SLIDER DOTS STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => ['dots' => 'yes']
            ]
        );
        $this->add_control( 'dots_top_offset',
            [
                'label' => esc_html__( 'Top Offset', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -300,
                'max' => 300,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-pagination-bullets' => 'margin-top:{{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'dots_alignment',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-swiper-theme-style .swiper-pagination-bullets' => 'text-align:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'dots_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'width:{{SIZE}}px;height:{{SIZE}}px;' ]
            ]
        );
        $this->add_control( 'dots_space',
            [
                'label' => esc_html__( 'Space', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [
                    '{{WRAPPER}} .swiper-horizontal > .swiper-pagination-bullets .swiper-pagination-bullet + .swiper-pagination-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                    '{{WRAPPER}} .swiper-pagination-horizontal.swiper-pagination-bullets .swiper-pagination-bullet + .swiper-pagination-bullet' => 'margin: 0;margin-left: {{SIZE}}px;',
                ]
            ]
        );
        $this->start_controls_tabs( 'dots_nav_tabs');
        $this->start_controls_tab( 'dots_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'dots_bgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet',
            ]
        );
        $this->add_responsive_control( 'dots_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet:before,{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'dots_hover_tab',
            [ 'label' => esc_html__( 'Active', 'styler' ) ]
        );
        $this->add_control( 'dots_hvrbgcolor',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active:before' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'dots_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .swiper-pagination-bullet.swiper-pagination-bullet-active'
            ]
        );
        $this->add_responsive_control( 'dots_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active:before,{{WRAPPER}} .swiper-pagination-bullets .swiper-pagination-bullet.swiper-pagination-bullet-active' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $id       = $this->get_id();

        if ( $settings['token'] ) {
            $editmode = \Elementor\Plugin::$instance->editor->is_edit_mode() ? '-'.$id: '';
            $effect = $settings['effect'];

            $slider_options = json_encode( array(
                    "slidesPerView" => 1,
                    "loop"          => 'yes' == $settings['loop'] ? true: false,
                    "autoHeight"    => false,
                    "autoplay"      => 'yes' == $settings['autoplay'] ? true: false,
                    "centeredSlides"=> 'yes' == $settings['centermode'] ? true: false,
                    "speed"         => $settings['speed'],
                    "spaceBetween"  => $settings['space'] ? $settings['space'] : 0,
                    "direction"     => "horizontal",
                    "navigation" => [
                        "nextEl" => ".styler-instagram-slider$editmode .slide-next-{$id}",
                        "prevEl" => ".styler-instagram-slider$editmode .slide-prev-{$id}"
                    ],
                    "effect" => "{$effect}",
                    "flipEffect" => [
                        "slideShadows" => false
                    ],
                    "fadeEffect" => [
                        "crossFade" => true
                    ],
                    "creativeEffect" => [
                        "prev" => [ "translate" => [0, 0, -400] ],
                        "next" => [ "translate" => ['100%', 0, 0] ]
                    ],
                    "coverflowEffect" => [
                        "rotate" => 30,
                        "slideShadows" => false
                    ],
                    "pagination" => [
                        "el" => ".styler-instagram-slider$editmode .styler-pagination-$id",
                        "type" => "bullets",
                        "clickable" => true
                    ],
                    "breakpoints" => [
                        "0" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['xsitems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['xsitems']
                        ],
                        "768" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['smitems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['smitems']
                        ],
                        "1024" => [
                            "slidesPerView" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['mditems'],
                            "slidesPerGroup" => 'creative' == $effect || 'flip' == $effect ? 1 : $settings['mditems']
                        ]
                    ]
                )
            );

            $blank_img = get_template_directory().'/images/blank.gif';
            $blank_img = file_exists( $blank_img ) ? get_template_directory_uri().'/images/blank.gif' : $placeholder;
            echo '<div class="styler-instagram-slider'.$editmode.' styler-swiper-theme-style swiper-container styler-swiper-slider'.$editmode.'" data-insta=\'{"token":"'.$settings['token'].'","limit":'.$settings['limit'].',"target":"swiper-wrapper_'.$id.'","blankimage":"'.$blank_img.'","link_target":"'.$settings['target'].'"}\' data-swiper-options=\''.$slider_options.'\'>';
                echo '<div class="swiper-wrapper" id="swiper-wrapper_'.$id.'">';
                echo '</div>';
                if ( 'yes' == $settings['dots'] ) {
                    echo '<div class="swiper-pagination styler-pagination-'.$id.'"></div>';
                }
                if ( 'yes' == $settings['nav'] ) {
                    if ( is_rtl() ) {
                        echo '<div class="swiper-button-next slide-next-'.$id.'"></div>';
                        echo '<div class="swiper-button-prev slide-prev-'.$id.'"></div>';
                    } else {
                        echo '<div class="swiper-button-prev slide-prev-'.$id.'"></div>';
                        echo '<div class="swiper-button-next slide-next-'.$id.'"></div>';
                    }
                }
            echo '</div>';
            if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
                <script>
                jQuery( document ).ready( function($) {
                    var feed = new Instafeed({
                        target: '<?php echo 'swiper-wrapper_'.$id; ?>',
                        accessToken: '<?php echo $settings['token']; ?>',
                        limit: <?php echo (-1 == $settings['limit'] ) ? 100 : $settings['limit'] ?>,
                        resolution: 'low_resolution',
                        template: '<div class="styler-image-wrapper swiper-slide"><a class="styler-instagram-link" href="{{link}}" title="{{caption}}"><svg height="512" viewBox="0 0 64 64" width="512" xmlns="http://www.w3.org/2000/svg"><g fill-rule="evenodd"><path d="m48 64h-32a16.0007 16.0007 0 0 1 -16-16v-32a16.0007 16.0007 0 0 1 16-16h32a16 16 0 0 1 16 16v32a16 16 0 0 1 -16 16" fill="#ff3a55"/><path d="m30 18h18a9.0006 9.0006 0 0 0 .92-17.954c-.306-.017-.609-.046-.92-.046h-32a16.0007 16.0007 0 0 0 -16 16v32a30.0007 30.0007 0 0 1 30-30" fill="#ff796c"/><path d="m48 32a16 16 0 1 0 16 16v-32a16 16 0 0 1 -16 16" fill="#e00047"/></g><circle cx="44.5" cy="19.5" fill="#fff" r="2.5"/><path d="m32 24a8 8 0 1 1 -8 8 8.0042 8.0042 0 0 1 8-8zm0-4a12 12 0 1 1 -12 12 12.0057 12.0057 0 0 1 12-12z" fill="#fff" fill-rule="evenodd"/><path d="m52 22a10 10 0 0 0 -10-10h-20a10 10 0 0 0 -10 10v20a10 10 0 0 0 10 10h20a10 10 0 0 0 10-10zm4 0a14 14 0 0 0 -14-14h-20a14 14 0 0 0 -14 14v20a14 14 0 0 0 14 14h20a14 14 0 0 0 14-14z" fill="#fff" fill-rule="evenodd"/></svg><img title="{{caption}}" src="{{image}}" /></a></div>'
                    });
                    feed.run();

                    var options =  $('.styler-swiper-slider-<?php echo $id ?>').data('swiper-options');
                    const mySlider<?php echo $id ?> = new NTSwiper('.styler-swiper-slider-<?php echo $id ?>', options);

                    $(this).hover(function() {
                        if ( options.autoplay == true ) {
                            mySlider<?php echo $id ?>.autoplay.stop();
                        }
                    }, function() {
                        if ( options.autoplay == true ) {
                            mySlider<?php echo $id ?>.autoplay.start();
                        }
                    });
                });
                </script>
                <?php
            }
        } else {
            echo '<p class="text-center">Please add your <b><i>Instagram Access Token</b></i> for preview</p>';
        }
    }
}
