<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Vegas_Slider extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-vegas-slider';
    }
    public function get_title() {
        return 'Vegas Slider (N)';
    }
    public function get_icon() {
        return 'eicon-slider-push';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    public function get_style_depends() {
        return [ 'vegas' ];
    }
    public function get_script_depends() {
        return [ 'vegas' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   END CONTROLS SECTION   ******/
        $this->start_controls_section( 'general_section',
            [
                'label' => esc_html__( 'Content', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control( 'minheight',
            [
                'label' => esc_html__( 'Min Height ( vh )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 100,
                'selectors' => ['{{WRAPPER}} .home-slider-vegas-wrapper' => 'height: {{SIZE}}vh;min-height: {{SIZE}}vh;']
            ]
        );
        $def_image = plugins_url( 'assets/front/img/placeholder.png', __DIR__ );
        $repeater = new Repeater();
        $repeater->add_control( 'image',
            [
                'label' => esc_html__( 'Image', 'styler' ),
                'type' => Controls_Manager::MEDIA,
                'default' => ['url' => $def_image]
            ]
        );
        $repeater->add_control( 'vurl',
            [
                'label' => esc_html__( 'Hosted Video URL', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => '',
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'mute',
            [
                'label' => esc_html__( 'Video Mute', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['vurl!' => '']
            ]
        );
        $repeater->add_responsive_control( 'sdelay',
            [
                'label' => esc_html__( 'Delay ( ms )', 'styler' ),
                'description' => esc_html__( 'Delay beetween slides in milliseconds.', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'step' => 100,
                'default' => ''
            ]
        );
        $repeater->add_control( 'title',
            [
                'label' => esc_html__( 'Title', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => 'Slider Title',
                'pleaceholder' => esc_html__( 'Enter title here', 'styler' ),
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'titleclr',
            [
                'label' => esc_html__( 'Title Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => ''
            ]
        );
        $repeater->add_control( 'desc',
            [
                'label' => esc_html__( 'Description', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'default' => '',
                'pleaceholder' => esc_html__( 'Enter description here', 'styler' ),
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'descclr',
            [
                'label' => esc_html__( 'Description Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => ''
            ]
        );
        $repeater->add_control( 'btn_title',
            [
                'label' => esc_html__( 'Button Title', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'label_block' => true,
                'default' => 'Shop Now',
                'pleaceholder' => esc_html__( 'Enter button title here', 'styler' ),
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'btn_link',
            [
                'label' => esc_html__( 'Button Link', 'styler' ),
                'type' => Controls_Manager::URL,
                'label_block' => true,
                'default' => [
                    'url' => '#0',
                    'is_external' => 'true'
                ],
                'placeholder' => esc_html__( 'Place URL here', 'styler' )
            ]
        );
        $repeater->add_control( 'overlayclr',
            [
                'label' => esc_html__( 'Overlay Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'bgcolor',
            [
                'label' => esc_html__( 'Slide Background Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => ''
            ]
        );
        $repeater->add_control( 'text_alignment',
            [
                'label' => esc_html__( 'Text Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'text-left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'text-center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-h-align-center'
                    ],
                    'text-right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => false,
                'default' => 'left',
                'separator' => 'before'
            ]
        );
        $repeater->add_control( 'vertical_alignment',
            [
                'label' => esc_html__( 'Vertical Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'flex-start' => [
                        'title' => esc_html__( 'Top', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'center' => [
                        'title' => esc_html__( 'Center', 'styler' ),
                        'icon' => 'eicon-v-align-middle'
                    ],
                    'flex-end' => [
                        'title' => esc_html__( 'Bottom', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => ''
            ]
        );
        $this->add_control( 'slides',
            [
                'label' => esc_html__( 'Slide Items', 'styler' ),
                'type' => Controls_Manager::REPEATER,
                'fields' => $repeater->get_controls(),
                'title_field' => '{{title}}',
                'separator' => 'before',
                'default' => [
                    [
                        'image' => ['url' => $def_image],
                        'title' => 'From The<br> Inside Out',
                        'btn_title' => 'Discover Work',
                        'btn_link' => '#0'
                    ],
                    [
                        'image' => ['url' => $def_image],
                        'title' => 'Luxury <br> Real Estate',
                        'btn_title' => 'Discover Work',
                        'btn_link' => '#0'
                    ],
                    [
                        'image' => ['url' => $def_image],
                        'title' => 'Classic <br> &Modern',
                        'btn_title' => 'Discover Work',
                        'btn_link' => '#0'
                    ],
                    [
                        'image' => ['url' => $def_image],
                        'title' => 'Explore <br>TheWorld',
                        'btn_title' => 'Discover Work',
                        'btn_link' => '#0'
                    ]
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/

        $this->start_controls_section( 'slider_options_section',
            [
                'label' => esc_html__( 'Slider Options', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control( 'animation',
            [
                'label' => esc_html__( 'Animation Type', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['kenburns'],
                'options' => [
                    'kenburns' => esc_html__( 'kenburns', 'styler' ),
                    'kenburnsUp' => esc_html__( 'kenburnsUp', 'styler' ),
                    'kenburnsDown' => esc_html__( 'kenburnsDown', 'styler' ),
                    'kenburnsLeft' => esc_html__( 'kenburnsLeft', 'styler' ),
                    'kenburnsRight' => esc_html__( 'kenburnsRight', 'styler' ),
                    'kenburnsUpLeft' => esc_html__( 'kenburnsUpLeft', 'styler' ),
                    'kenburnsUpRight' => esc_html__( 'kenburnsUpRight', 'styler' ),
                    'kenburnsDownLeft' => esc_html__( 'kenburnsDownLeft', 'styler' ),
                    'kenburnsDownRight' => esc_html__( 'kenburnsDownRight', 'styler' )
                ]
            ]
        );
        $this->add_control( 'transition',
            [
                'label' => esc_html__( 'Transition Type', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'multiple' => true,
                'default' => ['zoomIn','slideLeft','slideRight'],
                'options' => [
                    'fade' => esc_html__( 'fade', 'styler' ),
                    'fade2' => esc_html__( 'fade2', 'styler' ),
                    'slideLeft' => esc_html__( 'slideLeft', 'styler' ),
                    'slideLeft2' => esc_html__( 'slideLeft2', 'styler' ),
                    'slideRight' => esc_html__( 'slideRight', 'styler' ),
                    'slideRight2' => esc_html__( 'slideRight2', 'styler' ),
                    'slideUp' => esc_html__( 'slideUp', 'styler' ),
                    'slideUp2' => esc_html__( 'slideUp2', 'styler' ),
                    'slideDown' => esc_html__( 'slideDown', 'styler' ),
                    'slideDown2' => esc_html__( 'slideDown2', 'styler' ),
                    'zoomIn' => esc_html__( 'zoomIn', 'styler' ),
                    'zoomIn2' => esc_html__( 'zoomIn2', 'styler' ),
                    'zoomOut' => esc_html__( 'zoomOut', 'styler' ),
                    'zoomOut2' => esc_html__( 'zoomOut2', 'styler' ),
                    'swirlLeft' => esc_html__( 'swirlLeft', 'styler' ),
                    'swirlLeft2' => esc_html__( 'swirlLeft2', 'styler' ),
                    'swirlRight' => esc_html__( 'swirlRight', 'styler' ),
                    'swirlRight2' => esc_html__( 'swirlRight2', 'styler' ),
                    'burn' => esc_html__( 'burn', 'styler' ),
                    'burn2' => esc_html__( 'burn2', 'styler' ),
                    'blur' => esc_html__( 'blur', 'styler' ),
                    'blur2' => esc_html__( 'blur2', 'styler' ),
                    'flash' => esc_html__( 'flash', 'styler' ),
                    'flash2' => esc_html__( 'flash2', 'styler' )
                ]
            ]
        );
        $this->add_control( 'overlay',
            [
                'label' => esc_html__( 'Overlay Image Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    'none' => esc_html__( 'None', 'styler' ),
                    '01' => esc_html__( 'Overlay 1', 'styler' ),
                    '02' => esc_html__( 'Overlay 2', 'styler' ),
                    '03' => esc_html__( 'Overlay 3', 'styler' ),
                    '04' => esc_html__( 'Overlay 4', 'styler' ),
                    '05' => esc_html__( 'Overlay 5', 'styler' ),
                    '06' => esc_html__( 'Overlay 6', 'styler' ),
                    '07' => esc_html__( 'Overlay 7', 'styler' ),
                    '08' => esc_html__( 'Overlay 8', 'styler' ),
                    '09' => esc_html__( 'Overlay 9', 'styler' )
                ]
            ]
        );
        $this->add_control( 'delay',
            [
                'label' => esc_html__( 'Delay ( ms )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 7000
            ]
        );
        $this->add_control( 'duration',
            [
                'label' => esc_html__( 'Transition Duration ( ms )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 2000
            ]
        );
        $this->add_control( 'autoplay',
            [
                'label' => esc_html__( 'Autoplay', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes',
            ]
        );
        $this->add_control( 'shuffle',
            [
                'label' => esc_html__( 'Shuffle', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'arrows',
            [
                'label' => esc_html__( 'Arrows', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'yes'
            ]
        );
        $this->add_control( 'counter',
            [
                'label' => esc_html__( 'Counter', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_control( 'timer',
            [
                'label' => esc_html__( 'Timer', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'selectors'  => ['{{WRAPPER}} .vegas-timer' => 'display:block!important;']
            ]
        );
        $this->add_control( 'timer_size',
            [
                'label' => esc_html__( 'Timer Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 5,
                'selectors'  => ['{{WRAPPER}} .vegas-timer' => 'height:{{VALUE}};'],
                'condition'  => ['timer' => 'yes']
            ]
        );
        $this->add_control( 'timer_color',
            [
                'label' => esc_html__( 'Timer Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors'  => ['{{WRAPPER}} .vegas-timer-progress' => 'background-color:{{VALUE}};'],
                'condition'  => ['timer' => 'yes']
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_nav_style_section',
            [
                'label'=> esc_html__( 'ARROWS STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'arrows' => 'yes' ]
            ]
        );
        $this->add_control( 'container',
            [
                'label' => esc_html__( 'Wrap Container', 'styler' ),
                'type' => Controls_Manager::SWITCHER
            ]
        );
        $this->add_responsive_control( 'slider_nav_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .vegas-control .vegas-control-btn' => 'width: {{SIZE}}px;height: {{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'slider_nav_icon_size',
            [
                'label' => esc_html__( 'Icon Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .vegas-control .vegas-control-btn' => 'font-size: {{SIZE}}px;' ]
            ]
        );
        $this->start_controls_tabs( 'slider_nav_tabs');
        $this->start_controls_tab( 'slider_nav_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'nav_bgclr',
           [
               'label' => esc_html__( 'Background Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .vegas-control .vegas-control-btn' => 'background-color: {{VALUE}};']
           ]
        );
        $this->add_control( 'nav_clr',
           [
               'label' => esc_html__( 'Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .vegas-control .vegas-control-btn' => 'color: {{VALUE}};']
           ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .vegas-control .vegas-control-btn',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();

        $this->start_controls_tab( 'slider_nav_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
        $this->add_control( 'nav_hvrbgclr',
           [
               'label' => esc_html__( 'Background Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .vegas-control .vegas-control-btn:hover' => 'background-color: {{VALUE}};']
           ]
        );
        $this->add_control( 'nav_hvrclr',
           [
               'label' => esc_html__( 'Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .vegas-control .vegas-control-btn:hover i' => 'color: {{VALUE}};']
           ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'nav_hvr_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .vegas-control .vegas-control-btn:hover',
                'separator' => 'before'
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();

        $this->add_control( 'prev_heading',
            [
                'label' => esc_html__( 'PREV POSITION', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_control( 'prev_horz_align',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'horz-left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'horz-right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => ''
            ]
        );
        $this->add_responsive_control( 'prev_horizontal',
            [
                'label' => esc_html__( 'Horizontal Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 4000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .vegas-control .vegas-control-prev.horz-left' => 'left:{{SIZE}}{{UNIT}};right:auto;',
                    '{{WRAPPER}} .vegas-control .vegas-control-prev.horz-right' => 'right:{{SIZE}}{{UNIT}};left:auto;'
                ]
            ]
        );
        $this->add_control( 'prev_ver_align',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'vert-top' => [
                        'title' => esc_html__( 'Top', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'vert-bottom' => [
                        'title' => esc_html__( 'Bottom', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => ''
            ]
        );
        $this->add_responsive_control( 'prev_vertical',
            [
                'label' => esc_html__( 'Vertical Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 2000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .vegas-control .vegas-control-prev.vert-top' => 'top:{{SIZE}}{{UNIT}};bottom:auto;',
                    '{{WRAPPER}} .vegas-control .vegas-control-prev.vert-bottom' => 'bottom:{{SIZE}}{{UNIT}};top:auto;',
                ],
            ]
        );
        $this->add_control( 'next_heading',
            [
                'label' => esc_html__( 'NEXT POSITION', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
            ]
        );
        $this->add_control( 'next_horz_align',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'horz-left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'horz-right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => 'horz-right'
            ]
        );
        $this->add_responsive_control( 'next_horizontal',
            [
                'label' => esc_html__( 'Horizontal Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 4000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .vegas-control .vegas-control-next.horz-left' => 'left:{{SIZE}}{{UNIT}};right:auto;',
                    '{{WRAPPER}} .vegas-control .vegas-control-next.horz-right' => 'right:{{SIZE}}{{UNIT}};left:auto;'
                ]
            ]
        );
        $this->add_control( 'next_ver_align',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'vert-top' => [
                        'title' => esc_html__( 'Top', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'vert-bottom' => [
                        'title' => esc_html__( 'Bottom', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'vert-bottom'
            ]
        );
        $this->add_responsive_control( 'next_vertical',
            [
                'label' => esc_html__( 'Vertical Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 2000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .vegas-control .vegas-control-next.vert-top' => 'top:{{SIZE}}{{UNIT}};bottom:auto;',
                    '{{WRAPPER}} .vegas-control .vegas-control-next.vert-bottom' => 'bottom:{{SIZE}}{{UNIT}};top:auto;'
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('slider_counter_style_section',
            [
                'label'=> esc_html__( 'COUNTER STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'counter' => 'yes' ]
            ]
        );
        $this->add_control( 'counter_clr',
           [
               'label' => esc_html__( 'Color', 'styler' ),
               'type' => Controls_Manager::COLOR,
               'default' => '',
               'selectors' => ['{{WRAPPER}} .nt-vegas-slide-counter' => 'color: {{VALUE}};']
           ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'counter_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .nt-vegas-slide-counter'
            ]
        );
        $this->add_control( 'counter_horz_align',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'horz-left' => [
                        'title' => esc_html__( 'Left', 'styler' ),
                        'icon' => 'eicon-h-align-left'
                    ],
                    'horz-right' => [
                        'title' => esc_html__( 'Right', 'styler' ),
                        'icon' => 'eicon-h-align-right'
                    ]
                ],
                'toggle' => true,
                'default' => 'horz-right'
            ]
        );
        $this->add_responsive_control( 'counter_horizontal',
            [
                'label' => esc_html__( 'Horizontal Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .nt-vegas-slide-counter.horz-left' => 'left:{{SIZE}}{{UNIT}};right:auto;',
                    '{{WRAPPER}} .nt-vegas-slide-counter.horz-right' => 'right:{{SIZE}}{{UNIT}};left:auto;'
                ]
            ]
        );
        $this->add_control( 'counter_ver_align',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'options' => [
                    'vert-top' => [
                        'title' => esc_html__( 'Top', 'styler' ),
                        'icon' => 'eicon-v-align-top'
                    ],
                    'vert-bottom' => [
                        'title' => esc_html__( 'Bottom', 'styler' ),
                        'icon' => 'eicon-v-align-bottom'
                    ]
                ],
                'toggle' => true,
                'default' => 'vert-bottom'
            ]
        );
        $this->add_responsive_control( 'counter_vertical',
            [
                'label' => esc_html__( 'Vertical Position', 'styler' ),
                'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                    'min' => 0,
                        'max' => 1000,
                        'step' => 5
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100
                    ]
                ],
                'selectors' => [
                    '{{WRAPPER}} .nt-vegas-slide-counter.vert-top' => 'top:{{SIZE}}{{UNIT}};bottom:auto;',
                    '{{WRAPPER}} .nt-vegas-slide-counter.vert-bottom' => 'bottom:{{SIZE}}{{UNIT}};top:auto;'
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings = $this->get_settings_for_display();
        $settingsid = $this->get_id();
        $sliderattr = '';

        $autoplay = 'yes' == $settings['autoplay'] ? 'true' : 'false';
        $shuffle = 'yes' == $settings['shuffle'] ? 'true' : 'false';
        $timer = 'yes' == $settings['timer'] ? 'true' : 'false';
        $overlay = 'none' == $settings['overlay'] ? 'false' : 'true';

        $slides = array();
        foreach ( $settings['slides'] as $i ) {
            $sdelay = $i['sdelay'] ? ',"delay":'.$i['sdelay'] : '';
            $mute = 'yes' == $i['mute'] ? 'true' : 'false';
            $bgcolor = $i['bgcolor'] ? ',"color":"'.$i['bgcolor'].'"' : '';
            if ( $i['vurl'] != '' ) {
                $slides[] .= '{"src":"'.$i['image']['url'].'","video": {"src":"'.$i['vurl'].'","loop": false,"mute":'.$mute.'}'.$sdelay.$bgcolor.'}';
            } else {
                $slides[] .= '{"src":"'.$i['image']['url'].'"'.$sdelay.$bgcolor.'}';
            }
        }

        $animation = array();
        foreach ( $settings['animation'] as $anim ) {
            $animation[] .=  '"'.$anim.'"';
        }

        $transition = array();
        foreach ( $settings['transition'] as $trans ) {
            $transition[] .=  '"'.$trans.'"';
        }

        $sliderattr .= '"slides":['.implode(',', $slides).'],';
        $sliderattr .= '"animation":['.implode(',', $animation).'],';
        $sliderattr .= '"transition":['.implode(',', $transition).'],';
        $sliderattr .= '"delay":'.$settings['delay'].',';
        $sliderattr .= '"duration":'.$settings['duration'].',';
        $sliderattr .= '"timer":"'.$settings['timer'].'",';
        $sliderattr .= '"shuffle":"'.$settings['shuffle'].'",';
        $sliderattr .= '"overlay":"'.$settings['overlay'].'",';
        $sliderattr .= '"autoplay":'.$autoplay;

        echo '<div class="home-slider-vegas-wrapper slider-vegas-'.$settingsid.'">';
            echo '<div id="slider-'.$settingsid.'" class="nt-home-slider-vegas" data-slider-settings=\'{'.$sliderattr.'}\'></div>';
            foreach ( $settings['slides'] as $item ) {
                $target = $item['btn_link']['is_external'] ? ' target="_blank"' : '';
                $rel = $item['btn_link']['nofollow'] ? ' rel="nofollow"' : '';
                $hasvideo = '' != $item['vurl'] ? ' has-bg-video' : '';
                $vertical_alignment = '' != $item['vertical_alignment'] ? ' style="align-items:'.$item['vertical_alignment'].';"' : '';
                echo '<div class="nt-vegas-slide-content '.$item['text_alignment'].$hasvideo.'"'.$vertical_alignment.'>';
                    if ( $item['overlayclr'] ){
                        echo '<div class="nt-vegas-overlay" style="background-color:'.$item['overlayclr'].';"></div>';
                    }
                    echo '<div class="container">';
                        echo '<div class="row">';
                            echo '<div class="col-12">';

                                if ( $item['title'] ){
                                    $titleclr = $item['titleclr'] ? ' style="color:'.$item['titleclr'].';"' : '';
                                    echo '<h1 class="slider_title animated"'.$titleclr.'>'.$item['title'].'</h1>';
                                }
                                if ( $item['desc'] ){
                                    $descclr = $item['descclr'] ? ' style="color:'.$item['descclr'].';"' : '';
                                    echo '<p class="slider_desc animated"'.$descclr.'>'.$item['desc'].'</p>';
                                }
                                if ( $item['btn_title'] ){
                                    echo '<a href="'.$item['btn_link']['url'].'" '.$target.$rel.' class="btn yellow-btn animated">'.$item['btn_title'].'</a>';
                                }

                            echo '</div>';
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }

            if ( 'yes' == $settings['container'] ) {
                echo '<div class="container">';
                    echo '<div class="row">';
                        echo '<div class="col-12">';
            }
            if ( 'yes' == $settings['counter'] ) {
                echo '<div class="nt-vegas-slide-counter '.$settings['counter_horz_align'].' '.$settings['counter_ver_align'].'">';
                    echo '<span class="current">0</span>';
                    echo '<span class="sep"> / </span>';
                    echo '<span class="total">4</span>';
                echo '</div>';
            }
            if ( 'yes' == $settings['arrows'] ) {
                echo '<div class="vegas-control">';
                    echo '<span id="vegas-control-prev" class="vegas-control-prev vegas-control-btn '.$settings['prev_horz_align'].' '.$settings['prev_ver_align'].'"><i class="fas fa-angle-left"></i></span>';
                    echo '<span id="vegas-control-next" class="vegas-control-next vegas-control-btn '.$settings['next_horz_align'].' '.$settings['next_ver_align'].'"><i class="fas fa-angle-right"></i></span>';
                echo '</div>';
            }
            if ( 'yes' == $settings['container'] ) {
                        echo '</div>';
                    echo '</div>';
                echo '</div>';
            }
        echo '</div>';

        // Not in edit mode
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) { ?>
            <script>
            jQuery(document).ready(function ($) {

                var myEl       = $('.slider-vegas-<?php echo $settingsid; ?>'),
                    myVegasId  = myEl.find('.nt-home-slider-vegas').attr('id'),
                    myVegas    = $( '#' + myVegasId ),
                    myPrev     = myEl.find('.vegas-control-prev'),
                    myNext     = myEl.find('.vegas-control-next'),
                    mySettings = myEl.find('.nt-home-slider-vegas').data('slider-settings'),
                    myContent  = myEl.find('.nt-vegas-slide-content'),
                    myTitle    = myEl.find('.slider_title'),
                    myDesc     = myEl.find('.slider_desc'),
                    myBtn      = myEl.find('.btn'),
                    myCounter  = myEl.find('.nt-vegas-slide-counter');

                if( mySettings.slides.length ) {

                    myVegas.vegas({
                        autoplay: <?php echo $autoplay; ?>,
                        delay: <?php echo $settings['delay']; ?>,
                        timer: <?php echo $timer; ?>,
                        shuffle: <?php echo $shuffle; ?>,
                        animation: [<?php echo implode(',', $animation); ?>],
                        transition: [<?php echo implode(',', $transition); ?>],
                        transitionDuration: <?php echo $settings['duration']; ?>,
                        overlay: <?php echo $overlay; ?>,
                        slides: [<?php echo implode(',', $slides); ?>],
                        init: function (globalSettings) {
                            myContent.eq(0).addClass('active');
                            myTitle.eq(0).addClass('animated fadeInLeft');
                            myDesc.eq(0).addClass('animated fadeInLeft');
                            myBtn.eq(0).addClass('animated fadeInLeft');
                            var total = myContent.size();
                            myCounter.find('.total').html(total);
                        },
                        walk: function (index, slideSettings) {
                            myContent.removeClass('active').eq(index).addClass('active');
                            myTitle.removeClass('animated fadeInLeft').eq(index).addClass('animated fadeInLeft');
                            myDesc.removeClass('animated fadeInLeft').eq(index).addClass('animated fadeInLeft');
                            myBtn.removeClass('animated fadeInLeft').eq(index).addClass('animated fadeInLeft');
                            var current = index +1;
                            myCounter.find('.current').html(current);
                        }
                    });
                    myPrev.on('click', function () {
                        myVegas.vegas('previous');
                    });

                    myNext.on('click', function () {
                        myVegas.vegas('next');
                    });
                }
            });
            </script>
            <?php
        }
    }
}
