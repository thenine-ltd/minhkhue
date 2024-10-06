<?php

if( !defined( 'ABSPATH' ) ) exit;

use Elementor\Utils;
use Elementor\Repeater;
use Elementor\Element_Base;
use Elementor\Elementor_Base;
use Elementor\Controls_Manager;
use Elementor\Core\Responsive\Responsive;
use Elementor\Widget_Base;
use Elementor\Group_Control_Background;
use \Elementor\Group_Control_Image_Size;

class Styler_Section_Parallax {

    private static $instance = null;

    public function __construct(){
        // section register settings
        add_action('elementor/element/section/section_structure/after_section_end',array($this,'lazyload_background_controls'), 10 );
        add_action('elementor/element/column/layout/after_section_end',array($this,'column_lazyload_background_controls'), 10 );
        add_action('elementor/element/section/section_structure/after_section_end',array($this,'section_parallax_controls'), 10 );
        //add_action('elementor/element/section/section_structure/after_section_end',array($this,'styler_add_particle_effect_to_section'), 10 );
        add_action('elementor/element/section/section_structure/after_section_end',array($this,'styler_add_vegas_slider_to_section'), 10 );
        add_action('elementor/element/section/section_layout/before_section_end',array($this,'register_change_section_indent_structure'), 10 );
        add_action('elementor/element/section/section_background_overlay/before_section_end',array($this,'register_add_section_overlay_width'), 10 );
        add_action('elementor/frontend/section/before_render',array($this,'styler_custom_attr_to_section'), 10);
        add_action('elementor/frontend/column/before_render',array($this,'styler_custom_attr_to_column'), 10);

        // column register settings and before render column functions
        //add_action('elementor/element/column/layout/after_section_end',array($this,'add_tilt_effect_to_column'), 10 );
    }
    /*****   START PARALLAX CONTROLS   ******/
    public function lazyload_background_controls( $element ) {

        $element->start_controls_section( 'styler_lazyload_section',
            [
                'label' => esc_html__( 'Styler LazyLoad Background', 'styler' ),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );
        $element->add_responsive_control( 'styler_lazy_bg_image',
            [
                'label' => esc_html__( 'Image', 'styler' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $element->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'styler_lazy_bg',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => ['classic'],
                'exclude' => ['image']
            ]
        );
        $element->end_controls_section();
    }
    public function column_lazyload_background_controls( $element ) {

		$is_dome_optimization_active = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		$main_selector_element = $is_dome_optimization_active ? 'widget' : 'column';

        $element->start_controls_section( 'styler_lazyload_section',
            [
                'label' => esc_html__( 'Styler LazyLoad Background', 'styler' ),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );
        $element->add_responsive_control( 'styler_lazy_bg_image',
            [
                'label' => esc_html__( 'Image', 'styler' ),
                'type' => Controls_Manager::MEDIA
            ]
        );
        $element->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'styler_lazy_bg',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => ['classic'],
                'exclude' => ['image'],
				'selector' => '{{WRAPPER}}:not(.elementor-motion-effects-element-type-background) > .elementor-' . $main_selector_element . '-wrap, {{WRAPPER}} > .elementor-' . $main_selector_element . '-wrap > .elementor-motion-effects-container > .elementor-motion-effects-layer',
				'fields_options' => [
					'background' => [
						'frontend_available' => true,
					],
				],
            ]
        );
        $element->end_controls_section();
    }

    /*****   START PARALLAX CONTROLS   ******/
    public function section_parallax_controls( $element ) {

        $template = basename( get_page_template() );

        $element->start_controls_section( 'styler_parallax_section',
            [
                'label' => esc_html__( 'Styler Parallax', 'styler' ),
                'tab' => Controls_Manager::TAB_LAYOUT
            ]
        );
        $element->add_control( 'styler_parallax_switcher',
            [
                'label' => esc_html__( 'Enable Parallax', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-parallax jarallax parallax-',
            ]
        );
        $element->add_control( 'styler_parallax_update',
            [
                'label' => '<div class="elementor-update-preview" style="background-color: #fff;display: block;"><div class="elementor-update-preview-button-wrapper" style="display:block;"><button class="elementor-update-preview-button elementor-button elementor-button-success" style="background: #d30c5c; margin: 0 auto; display:block;">Apply Changes</button></div><div class="elementor-update-preview-title" style="display:block;text-align:center;margin-top: 10px;">Update changes to pages</div></div>',
                'type' => Controls_Manager::RAW_HTML,
                'condition' => ['styler_parallax_switcher' => 'yes'],
            ]
        );
        $element->add_control( 'styler_parallax_type',
            [
                'label' => esc_html__( 'Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => 'true',
                'condition' => ['styler_parallax_switcher' => 'yes'],
                'default' => 'scroll',
                'options' => [
                    'scroll' => esc_html__( 'Scroll', 'styler' ),
                    'scroll-opacity' => esc_html__( 'Scroll with Opacity', 'styler' ),
                    'opacity' => esc_html__( 'Fade', 'styler' ),
                    'scale' => esc_html__( 'Zoom', 'styler' ),
                    'scale-opacity' => esc_html__( 'Zoom with Fade', 'styler' )
                ]
            ]
        );
        $element->add_control( 'styler_parallax_bg_size',
            [
                'label' => esc_html__( 'Image Size', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'auto',
                'condition' => ['styler_parallax_switcher' => 'yes'],
                'options' => [
                    'auto' => esc_html__( 'Auto', 'styler' ),
                    'cover' => esc_html__( 'Cover', 'styler' ),
                    'contain' => esc_html__( 'Contain', 'styler' )
                ]
            ]
        );
        $element->add_control( 'styler_parallax_speed',
            [
                'label' => esc_html__( 'Parallax Speed', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1,
                'max' => 2,
                'step' => 0.1,
                'default' => 0.2,
                'condition' => ['styler_parallax_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_parallax_mobile_support',
            [
                'label' => esc_html__( 'Parallax on Mobile Devices', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-mobile-parallax-',
                'condition' => ['styler_parallax_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_add_parallax_video',
            [
                'label' => esc_html__( 'Use Background Video', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-parallax-video-',
                'condition' => ['styler_parallax_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_local_video_format',
            [
                'label' => esc_html__( 'Video Format', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => 'true',
                'default' => 'external',
                'options' => [
                    'external' => esc_html__( 'External (Youtube,Vimeo)', 'styler' ),
                    'mp4' => esc_html__( 'Local MP4', 'styler' ),
                    'webm' => esc_html__( 'Local Webm', 'styler' ),
                    'ogv' => esc_html__( 'Local Ogv', 'styler' ),
                ],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_parallax_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_add_parallax_video',
                            'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_parallax_video_url',
            [
                'label' => esc_html__( 'Video URL', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => 'https://www.youtube.com/watch?v=AeeE6PyU-dQ',
                'description' => esc_html__( 'YouTube/Vimeo link, or link to video file (mp4 is recommended).', 'styler' ),
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_parallax_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_add_parallax_video',
                            'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_parallax_video_start_time',
            [
                'label' => esc_html__( 'Start Time', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'placeholder' => '10',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_parallax_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_add_parallax_video',
                            'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_parallax_video_end_time',
            [
                'label' => esc_html__( 'End Time', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'placeholder' => '70',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_parallax_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_add_parallax_video',
                            'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_parallax_video_volume',
            [
                'label' => esc_html__( 'Video Volume', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'placeholder' => '0',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_parallax_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_add_parallax_video',
                            'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_parallax_video_play_once',
            [
                'label' => esc_html__( 'Play Once', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'label_on' => esc_html__( 'Yes', 'styler' ),
                'label_off' => esc_html__( 'No', 'styler' ),
                'return_value' => 'yes',
                'default' => 'no',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_parallax_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_add_parallax_video',
                            'operator' => '==', // it accepts:  =,==, !=,!==,  in, !in etc.
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->end_controls_section();
    }

    /*****   START COLUMN CONTROLS   ******/
    public function add_tilt_effect_to_column( $element ) {
        $element->start_controls_section( 'styler_tilt_effect_section',
            [
                'label' => esc_html__( 'Styler Tilt Effect', 'styler' ),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );
        $element->add_control( 'styler_tilt_effect_switcher',
            [
                'label' => esc_html__( 'Enable Tilt Effect', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'You can use this option if you want to use tilt effect for the elementor heading and image in the column when the mouse is over the column.', 'styler' ),
            ]
        );
        $element->add_control( 'styler_tilt_effect_maxtilt',
            [
                'label' => esc_html__( 'Max Tilt', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => 20,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_perspective',
            [
                'label' => esc_html__( 'Perspective', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 100,
                'default' => 1000,
                'description' => esc_html__( 'Transform perspective, the lower the more extreme the tilt gets.', 'styler' ),
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_easing',
            [
                'label' => esc_html__( 'Custom Easing', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'cubic-bezier(.03,.98,.52,.99)',
                'label_block' => true,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_scale',
            [
                'label' => esc_html__( 'Scale', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 1,
                'default' => 1,
                'description' => esc_html__( '2 = 200%, 1.5 = 150%, etc..', 'styler' ),
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_speed',
            [
                'label' => esc_html__( 'Speed', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 5000,
                'step' => 10,
                'default' => 300,
                'description' => esc_html__( 'Speed of the enter/exit transition.', 'styler' ),
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_transition',
            [
                'label' => esc_html__( 'Transition', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Set a transition on enter/exit.', 'styler' ),
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_disableaxis',
            [
                'label' => esc_html__( 'Disable Axis', 'styler' ),
                'description' => esc_html__( 'What axis should be disabled. Can be X or Y.', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'options' => [
                    '' => esc_html__( 'None', 'styler' ),
                    'vertical' => esc_html__( 'X Axis', 'styler' ),
                    'horizontal' => esc_html__( 'Y Axis', 'styler' ),
                ],
                'condition' => [ 'styler_tilt_effect_switcher' => 'yes' ],
            ]
        );
        $element->add_control( 'styler_tilt_effect_reset',
            [
                'label' => esc_html__( 'Reset', 'styler' ),
                'description' => esc_html__( 'If the tilt effect has to be reset on exit.', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_glare',
            [
                'label' => esc_html__( 'Glare Effect', 'styler' ),
                'description' => esc_html__( 'Enables glare effect', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_tilt_effect_maxglare',
            [
                'label' => esc_html__( 'Max Glare', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1,
                'step' => 0.1,
                'default' => 1,
                'description' => esc_html__( 'From 0 - 1.', 'styler' ),
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_tilt_effect_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_tilt_effect_glare',
                            'operator' => '==',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'styler_tilt_effect_glareclr',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => ['gradient'],
                'selector' => '{{WRAPPER}} .js-tilt-glare-inner',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_tilt_effect_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_tilt_effect_glare',
                            'operator' => '==',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->end_controls_section();
    }
    /*****   END COLUMN CONTROLS   ******/

    /*****   START CONTROLS SECTION   ******/
    public function register_change_section_indent_structure( $element ) {
        $element->add_control( 'styler_make_fixed_section_switcher',
            [
                'label' => esc_html__( 'Make Fixed On Scroll', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
                'prefix_class' => 'styler-section-fixed-',
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'html_tag',
                            'operator' => '==',
                            'value' => 'nav'
                        ],
                        [
                            'name' => 'html_tag',
                            'operator' => '=',
                            'value' => 'header'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_fixed_section_bgcolor',
            [
                'label' => esc_html__( 'On Scroll BG Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    'body .section-fixed-active{{WRAPPER}}' => 'background-color:{{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'html_tag',
                            'operator' => '==',
                            'value' => 'nav'
                        ],
                        [
                            'name' => 'html_tag',
                            'operator' => '=',
                            'value' => 'header'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_fixed_section_heading_color',
            [
                'label' => esc_html__( 'On Scroll Text Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    'body .section-fixed-active{{WRAPPER}} .elementor-widget-wrap .elementor-element .elementor-widget-container .elementor-heading-title' => 'color:{{VALUE}};',
                    'body .section-fixed-active{{WRAPPER}} .elementor-widget-wrap .elementor-element .elementor-widget-container .elementor-icon' => 'color:{{VALUE}};',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'html_tag',
                            'operator' => '==',
                            'value' => 'nav'
                        ],
                        [
                            'name' => 'html_tag',
                            'operator' => '=',
                            'value' => 'header'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_fixed_section_link_color',
            [
                'label' => esc_html__( 'On Scroll Link Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    'body .section-fixed-active{{WRAPPER}} .elementor-widget-wrap .elementor-element .elementor-widget-container a' => 'color: {{VALUE}} !important;',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'html_tag',
                            'operator' => '==',
                            'value' => 'nav'
                        ],
                        [
                            'name' => 'html_tag',
                            'operator' => '=',
                            'value' => 'header'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_fixed_section_link_hvrcolor',
            [
                'label' => esc_html__( 'On Scroll Link Hover', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => [
                    'body .section-fixed-active{{WRAPPER}} .elementor-widget-wrap .elementor-element .elementor-widget-container a:hover' => 'color: {{VALUE}} !important;',
                ],
                'conditions' => [
                    'relation' => 'or',
                    'terms' => [
                        [
                            'name' => 'html_tag',
                            'operator' => '==',
                            'value' => 'nav'
                        ],
                        [
                            'name' => 'html_tag',
                            'operator' => '=',
                            'value' => 'header'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_section_indent',
            [
                'label' => esc_html__( 'Section Indent', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'label_block' => 'true',
                'default' => '',
                'prefix_class' => 'nt-section ',
                'separator' => 'before',
                'options' => [
                    '' => esc_html__( 'Default', 'styler' ),
                    'section-padding' => esc_html__( 'Indent Top and Bottom', 'styler' ),
                    'section-padding pt-0' => esc_html__( 'Indent Bottom No Top', 'styler' ),
                    'section-padding pb-0' => esc_html__( 'Indent Top No Bottom', 'styler' ),
                ]
            ]
        );
    }


    /*****   START CONTROLS SECTION   ******/
    public function register_add_section_overlay_width( $element )
    {
        $element->add_responsive_control( 'styler_section_overlay_width',
            [
                'label' => esc_html__( 'Styler Overlay Width', 'styler' ),
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
                'default' => [
                    'unit' => '%',
                    'size' => 100
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-background-overlay' => 'width: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );

        $element->add_responsive_control( 'styler_section_overlay_height',
            [
                'label' => esc_html__( 'Styler Overlay Height', 'styler' ),
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
                'default' => [
                    'unit' => '%',
                    'size' => 100
                ],
                'selectors' => [
                    '{{WRAPPER}} .elementor-background-overlay' => 'height: {{SIZE}}{{UNIT}};',
                ],
                'separator' => 'before'
            ]
        );
    }

    // Registering Controls
    public function styler_add_particle_effect_to_section( $element ) {
        $element->start_controls_section('styler_particles_settings',
            [
                'label' => esc_html__( 'Styler Particles Effect', 'styler' ),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );
        $element->add_control( 'styler_particles_type',
            [
                'label' => esc_html__( 'Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'none',
                'options' => [
                    'none' => esc_html__( 'None', 'styler' ),
                    'default' => esc_html__( 'default', 'styler' ),
                    'nasa' => esc_html__( 'nasa', 'styler' ),
                    'bubble' => esc_html__( 'bubble', 'styler' ),
                    'snow' => esc_html__( 'snow', 'styler' ),
                ]
            ]
        );
        $element->add_control( 'styler_particles_options_heading',
            [
                'label' => esc_html__( 'Particles Options', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['styler_particles_type!' => 'none']
            ]
        );

        $element->add_control( 'styler_particles_shape',
            [
                'label' => esc_html__( 'Shape Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'circle',
                'options' => [
                    'circle' => esc_html__( 'circle', 'styler' ),
                    'edge' => esc_html__( 'edge', 'styler' ),
                    'triangle' => esc_html__( 'triangle', 'styler' ),
                    'polygon' => esc_html__( 'polygon', 'styler' ),
                    'star' => esc_html__( 'star', 'styler' ),
                ],
                'condition' => ['styler_particles_type!' => 'none']
            ]
        );
        $element->add_control( 'styler_particles_number',
            [
                'label' => esc_html__( 'Number', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 60,
                'condition' => ['styler_particles_type!' => 'none']
            ]
        );
        $element->add_control( 'styler_particles_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#fff',
                'condition' => ['styler_particles_type!' => 'none']
            ]
        );
        $element->add_control( 'styler_particles_opacity',
            [
                'label' => esc_html__( 'Opacity', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0.1,
                'max' => 1,
                'step' => 0.1,
                'default' => 0.4,
                'condition' => ['styler_particles_type!' => 'none']
            ]
        );
        $element->add_control( 'styler_particles_size',
            [
                'label' => esc_html__( 'Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 1,
                'max' => 1000,
                'step' => 1,
                'default' => 6,
                'condition' => ['styler_particles_type!' => 'none']
            ]
        );
        $element->end_controls_section();
    }

    // Registering Controls
    public function styler_add_vegas_slider_to_section( $element ) {
        $element->start_controls_section('styler_vegas_settings',
            [
                'label' => esc_html__( 'Styler Vegas Slider', 'styler' ),
                'tab' => Controls_Manager::TAB_LAYOUT,
            ]
        );
        $element->add_control( 'styler_vegas_switcher',
            [
                'label' => esc_html__( 'Enable Background Slider', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $element->add_control( 'styler_vegas_images',
            [
                'label' => __( 'Add Images', 'styler' ),
                'type' => Controls_Manager::GALLERY,
                'default' => [],
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_options_heading',
            [
                'label' => esc_html__( 'Slider Options', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => ['styler_vegas_images!' => '']
            ]
        );
        $element->add_control( 'styler_vegas_animation_type',
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
                    'kenburnsDownRight' => esc_html__( 'kenburnsDownRight', 'styler' ),
                ],
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_transition_type',
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
                    'flash2' => esc_html__( 'flash2', 'styler' ),
                ],
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_overlay_type',
            [
                'label' => esc_html__( 'Overlay Image Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => '',
                'prefix_class' => 'styler-vegas-overlay vegas-overlay-',
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
                    '09' => esc_html__( 'Overlay 9', 'styler' ),
                ],
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_delay',
            [
                'label' => esc_html__( 'Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 7000,
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_duration',
            [
                'label' => esc_html__( 'Transition Duration', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'default' => 2000,
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_shuffle',
            [
                'label' => esc_html__( 'Enable Shuffle', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_vegas_switcher' => 'yes']
            ]
        );
        $element->add_control( 'styler_vegas_timer',
            [
                'label' => esc_html__( 'Enable Timer', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_vegas_switcher' => 'yes'],
                'selectors' => ['{{WRAPPER}} .vegas-timer' => 'display:block!important;'],
            ]
        );
        $element->add_control( 'styler_vegas_timer_size',
            [
                'label' => esc_html__( 'Timer Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 5,
                'selectors' => ['{{WRAPPER}} .vegas-timer' => 'height:{{VALUE}};'],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_vegas_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_vegas_timer',
                            'operator' => '==',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->add_control( 'styler_vegas_timer_color',
            [
                'label' => esc_html__( 'Timer Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => ['{{WRAPPER}} .vegas-timer-progress' => 'background-color:{{VALUE}};'],
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_vegas_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_vegas_timer',
                            'operator' => '==',
                            'value' => 'yes'
                        ]
                    ]
                ]
            ]
        );
        $element->end_controls_section();
    }

    public function styler_custom_attr_to_column( $element ) {
        $data     = $element->get_data();
        $type     = $data['elType'];
        $settings = $data['settings'];
        $isInner  = $data['isInner'];// inner section

		$is_dom_optimization_active = \Elementor\Plugin::$instance->experiments->is_feature_active( 'e_dom_optimization' );
		$wrapper_attribute_string = $is_dom_optimization_active ? '_widget_wrapper' : '_inner_wrapper';

        // Section LazyLoad Bg image
        $wrapper = 'column' === $type ? '_widget_wrapper' : '_wrapper';
        $deskbg = $element->get_settings('styler_lazy_bg_image');

        if ( !empty( $deskbg['url'] ) ) {

            $element->add_render_attribute( $wrapper_attribute_string, 'data-bg', $deskbg['url']);
        }

        $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
        if ( !empty( $breakpoints ) ) {
            $breakpoints = array_reverse($breakpoints);
            if ( !empty( $deskbg['url'] ) ) {
                $databg['desktop'] = $deskbg['url'];
            }
            foreach ( $breakpoints as $point ) {
                $label = strtolower( $point->get_label() );
                $resbg = $element->get_settings('styler_lazy_bg_image_'.$label);
                if ( !empty( $resbg['url'] ) ) {
                    $databg[$label] = $resbg['url'];
                }
            }
            if ( !empty( $databg ) ) {
                $element->add_render_attribute( '_widget_wrapper', 'data-bg-responsive', json_encode($databg));
            }
        }
    }

    public function styler_custom_attr_to_section( $element ) {
        $data     = $element->get_data();
        $type     = $data['elType'];
        $settings = $data['settings'];
        $isInner  = $data['isInner'];// inner section

        $template = basename( get_page_template() );

        if ( 'section' === $element->get_name() ) {
            // Section LazyLoad Bg image
            $wrapper = 'column' === $type ? ' _widget_wrapper' : '_wrapper';
            $deskbg = $element->get_settings('styler_lazy_bg_image');
            //var_dump($type);
            if ( !empty( $deskbg['url'] ) ) {

                $element->add_render_attribute( $wrapper, 'data-bg', $deskbg['url']);
            }

            $breakpoints = \Elementor\Plugin::$instance->breakpoints->get_active_breakpoints();
            if ( !empty( $breakpoints ) ) {
                $breakpoints = array_reverse($breakpoints);
                if ( !empty( $deskbg['url'] ) ) {
                    $databg['desktop'] = $deskbg['url'];
                }
                foreach ( $breakpoints as $point ) {
                    $label = strtolower( $point->get_label() );
                    $resbg = $element->get_settings('styler_lazy_bg_image_'.$label);
                    if ( !empty( $resbg['url'] ) ) {
                        $databg[$label] = $resbg['url'];
                    }
                }
                if ( !empty( $databg ) ) {
                    $element->add_render_attribute( $wrapper, 'data-bg-responsive', json_encode($databg));
                }
            }
        }

        if ( 'section' === $element->get_name() ) {
            $gap = $element->get_settings('gap');
            $element->add_render_attribute( 'wrapper', 'class', $element->get_settings('styler_section_indent') );
            $element->add_render_attribute( '_wrapper', 'class', 'gap-'.$gap );

            // Particles Options
            if ( 'none' != $element->get_settings('styler_particles_type') ) {
                wp_enqueue_script( 'particles');
            }
            if ( 'yes' === $element->get_settings('styler_vegas_switcher') ) {
                wp_enqueue_style( 'vegas');
                wp_enqueue_script( 'vegas');

                $delay = $element->get_settings('styler_vegas_delay');
                $duration = $element->get_settings('styler_vegas_duration');
                $timer = $element->get_settings('styler_vegas_timer');
                $shuffle = $element->get_settings('styler_vegas_shuffle');
                $overlay = $element->get_settings('styler_vegas_overlay_type');
                $images = $element->get_settings('styler_vegas_images');

                $transitions = $element->get_settings('styler_vegas_transition_type');
                $transition = array();
                foreach ( $transitions as $trans ) {
                    $transition[] =  '"'.$trans.'"';
                }
                $transition = implode(',', $transition);

                $animations = $element->get_settings('styler_vegas_animation_type');
                $animation = array();
                foreach ( $animations as $anim ) {
                    $animation[] =  '"'.$anim.'"';
                }
                $animation = implode(',', $animation);

                $slides = array();
                foreach ( $images as $image ) {
                    $slides[] =  '{"src":"'.$image['url'].'"}';
                }

                $element->add_render_attribute( '_wrapper', 'data-vegas-settings',  '{"slides":['.implode(',', $slides).'],"animation":['.$animation.'],"transition":['.$transition.'],"delay":'.$delay.',"duration":'.$duration.',"timer":"'.$timer.'","shuffle":"'.$shuffle.'","overlay":"'.$overlay.'"}' );

                $element->add_render_attribute( '_wrapper', 'data-vegas-id', $data['id'] );

            }

            // Parallax Effect Options
            if ( 'yes' === $element->get_settings('styler_parallax_switcher') && $template != 'locomotive-page.php' ) {
                wp_enqueue_script( 'jarallax');
                // Parallax attr
                $type = $element->get_settings('styler_parallax_type');
                $speed = $element->get_settings('styler_parallax_speed');
                $bgsize = $element->get_settings('styler_parallax_bg_size');
                $mobile = $element->get_settings('styler_parallax_mobile_support');
                $bgimg = $element->get_settings('background_image');
                $bgimg = $bgimg['url'];

                if ( 'yes' === $element->get_settings('styler_add_parallax_video') && $element->get_settings('styler_parallax_video_url') ) {

                    if ( 'mp4' === $element->get_settings('styler_local_video_format')) {
                        $videosrc = 'mp4:'.$element->get_settings('styler_parallax_video_url');
                    } elseif ( 'webm' === $element->get_settings('styler_local_video_format')) {
                        $videosrc = 'webm:'.$element->get_settings('styler_parallax_video_url');
                    } elseif ( 'ogv' === $element->get_settings('styler_local_video_format')) {
                        $videosrc = 'ogv:'.$element->get_settings('styler_parallax_video_url');
                    } else {
                        //$settings['background_video_link'] // elementor background video link
                        $videosrc = $element->get_settings('styler_parallax_video_url');
                    }

                    $element->add_render_attribute( '_wrapper', 'data-jarallax data-video-src', $videosrc);

                    if ( $element->get_settings('styler_parallax_video_start_time') ) {
                        $element->add_render_attribute( '_wrapper', 'data-video-start-time', $element->get_settings('styler_parallax_video_start_time'));
                    }
                    if ( $element->get_settings('styler_parallax_video_end_time') ) {
                        $element->add_render_attribute( '_wrapper', 'data-video-end-time', $element->get_settings('styler_parallax_video_end_time'));
                    }
                    if ( 'yes' === $element->get_settings('styler_parallax_video_play_once') ) {
                        $element->add_render_attribute( '_wrapper', 'data-jarallax-video-loop', 'false' );
                    }
                    if ( $element->get_settings('styler_parallax_video_volume') ) {
                        $element->add_render_attribute( '_wrapper', 'data-video-volume', $element->get_settings('styler_parallax_video_volume') );
                    }

                } else {
                    $parallaxattr = '{"type":"'.$type.'","speed":"'.$speed.'","imgsize":"'.$bgsize.'","imgsrc":"'.$bgimg.'","mobile":"'.$mobile.'"}';
                    $element->add_render_attribute( '_wrapper', 'data-styler-parallax', $parallaxattr);
                }
            }

        } // end if section
    }

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new self;
        }
        return self::$instance;
    }
}
Styler_Section_Parallax::get_instance();
