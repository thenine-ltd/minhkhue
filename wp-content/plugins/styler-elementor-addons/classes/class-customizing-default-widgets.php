<?php

namespace Elementor;

if( !defined( 'ABSPATH' ) ) exit;

use Elementor\Controls_Manager;
use Elementor\Core\Base\Document;
use Elementor\Core\Base\Module as BaseModule;
use Elementor\Plugin;
use Elementor\Utils;
use Elementor\Element_Base;
use Elementor\Core\DocumentTypes\PageBase as PageBase;
use Elementor\Modules\Library\Documents\Page as LibraryPageDocument;

class Styler_Customizing_Default_Widgets {
    use Styler_Helper;
    private static $instance = null;

    public static function get_instance() {
        if ( null == self::$instance ) {
            self::$instance = new Styler_Customizing_Default_Widgets();
        }
        return self::$instance;
    }

    public function __construct(){
        add_action( 'elementor/element/heading/section_title/after_section_end', [ $this, 'styler_add_transform_to_heading' ] );
        add_action( 'elementor/element/spacer/section_spacer/before_section_end', [ $this, 'styler_add_rotate_to_spacer' ] );
        add_action( 'elementor/element/icon/section_icon/before_section_end', [ $this, 'styler_add_action_to_icon' ] );
        //add_action( 'elementor/element/image/section_image/after_section_end', [ $this, 'styler_add_custom_controls_to_image' ] );
        add_action( 'elementor/frontend/widget/before_render',[ $this, 'styler_add_custom_attr_to_widget' ], 10 );
        //add_action( 'elementor/frontend/widget/after_render',[ $this, 'styler_after_render_widget' ], 10 );
        /*
        $tiltelements = array(
            'image-box' => 'section_image',
        );
        foreach ( $tiltelements as $el => $section ) {
            add_action( 'elementor/element/'.$el.'/'.$section.'/after_section_end', [ $this,'styler_add_tilt_effect_to_element']);
        }
        */

    }

    public function styler_add_action_to_icon( $widget )
    {
        $widget->add_control( 'styler_icon_popup_switcher',
            [
                'label' => esc_html__( 'Enable Popup Action', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-icon-popup icon-has-popup-',
            ]
        );
        $widget->add_control( 'styler_icon_popup_content',
            [
                'label' => esc_html__( 'Select Popup Template', 'styler' ),
                'type' => Controls_Manager::SELECT2,
                'default' => '',
                'multiple' => false,
                'options' => $this->styler_get_popup_templates(),
                'condition' => [ 'styler_icon_popup_switcher' => 'yes' ]
            ]
        );
    }
    public function styler_add_rotate_to_spacer( $widget )
    {
        $widget->add_control( 'styler_spacer_rotate',
            [
                'label' => esc_html__( 'Rotate', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 360,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .elementor-widget-container' => '-webkit-transform: rotate({{VALUE}}deg);transform: rotate({{VALUE}}deg);'],
            ]
        );
    }


    public function styler_add_tilt_effect_to_element( $widget )
    {
        $widget->start_controls_section( 'styler_tilt_effect_section',
            [
                'label' => esc_html__( 'Styler Tilt Effect', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $widget->add_control( 'styler_tilt_effect_switcher',
            [
                'label' => esc_html__( 'Enable Tilt Effect', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
            ]
        );
        $widget->add_control( 'styler_tilt_effect_maxtilt',
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
        $widget->add_control( 'styler_tilt_effect_perspective',
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
        $widget->add_control( 'styler_tilt_effect_easing',
            [
                'label' => esc_html__( 'Custom Easing', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'cubic-bezier(.03,.98,.52,.99)',
                'label_block' => true,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_tilt_effect_scale',
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
        $widget->add_control( 'styler_tilt_effect_speed',
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
        $widget->add_control( 'styler_tilt_effect_transition',
            [
                'label' => esc_html__( 'Transition', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'description' => esc_html__( 'Set a transition on enter/exit.', 'styler' ),
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_tilt_effect_disableaxis',
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
        $widget->add_control( 'styler_tilt_effect_reset',
            [
                'label' => esc_html__( 'Reset', 'styler' ),
                'description' => esc_html__( 'If the tilt effect has to be reset on exit.', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_tilt_effect_glare',
            [
                'label' => esc_html__( 'Glare Effect', 'styler' ),
                'description' => esc_html__( 'Enables glare effect', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_tilt_effect_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_tilt_effect_maxglare',
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
        $widget->add_group_control(
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
        $widget->end_controls_section();
    }

    public function styler_add_transform_to_heading( $widget )
    {
        $widget->start_controls_section( 'heading_css_transform_controls_section',
            [
                'label' => esc_html__( 'Styler CSS Transform', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $widget->add_control( 'heading_css_transform_type',
            [
                'label' => esc_html__( 'Transform Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'translate',
                'options' => [
                    'translate' => esc_html__( 'translate', 'styler' ),
                    'scale' => esc_html__( 'scale', 'styler' ),
                    'rotate' => esc_html__( 'rotate', 'styler' ),
                    'skew' => esc_html__( 'skew', 'styler' ),
                    'custom' => esc_html__( 'custom', 'styler' ),
                ],
                'prefix_class' => 'styler-transform transform-type-',
            ]
        );
        $widget->add_control( 'heading_css_transform_translate_heading',
            [
                'label' => esc_html__( 'Translate', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'heading_css_transform_type' => 'translate' ]
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_translate_xy',
            [
                'label' => esc_html__( 'Translate 2D ( X,Y )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Xpx,Ypx',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-translate .elementor-heading-title' => 'transform:translate( {{VALUE}} );'],
                'condition' => [ 'heading_css_transform_type' => 'translate' ]
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_translate_xyz',
            [
                'label' => esc_html__( 'Translate 3D ( X,Y,Z )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Xpx,Ypx,Zpx',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-translate.has-translate-xyz .elementor-heading-title' => 'transform:translate3d( {{VALUE}} );'],
                'prefix_class' => 'has-translate-xyz translate-xyz-',
                'condition' => [ 'heading_css_transform_type' => 'translate' ]
            ]
        );
        // Scale
        $widget->add_control( 'heading_css_transform_scale_heading',
            [
                'label' => esc_html__( 'Scale', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [ 'heading_css_transform_type' => 'scale' ],
                'separator' => 'before'
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_scale_xy',
            [
                'label' => esc_html__( 'Scale 2D ( X,Y )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Xpx,Ypx',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-translate .elementor-heading-title' => 'transform:scale( {{VALUE}} );'],
                'condition' => [ 'heading_css_transform_type' => 'scale' ]
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_scale_xyz',
            [
                'label' => esc_html__( 'Scale 3D ( X,Y,Z )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Xpx,Ypx,Zpx',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-scale.has-scale-xyz .elementor-heading-title' => 'transform:scale3d( {{VALUE}} );'],
                'prefix_class' => 'has-scale-xyz scale-xyz-',
                'condition' => [ 'heading_css_transform_type' => 'scale' ]
            ]
        );
        // Rotate
        $widget->add_control( 'heading_css_transform_rotate_heading',
            [
                'label' => esc_html__( 'Rotate', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'condition' => [ 'heading_css_transform_type' => 'rotate' ],
                'separator' => 'before'
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_rotate_xy',
            [
                'label' => esc_html__( 'Rotate 2D ( X,Y )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Xdeg,Ydeg',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-rotate .elementor-heading-title' => 'transform:rotate( {{VALUE}} );'],
                'condition' => [ 'heading_css_transform_type' => 'rotate' ]
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_rotate_xyz',
            [
                'label' => esc_html__( 'Rotate 3D ( X,Y,Z )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => '0,0,0',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-rotate.has-rotate-xyz .elementor-heading-title' => 'transform:translate3d( {{VALUE}}deg );'],
                'prefix_class' => 'has-rotate-xyz rotate-xyz-',
                'condition' => [ 'heading_css_transform_type' => 'rotate' ]
            ]
        );
        // Skew
        $widget->add_control( 'heading_css_transform_skew_heading',
            [
                'label' => esc_html__( 'Skew', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'heading_css_transform_type' => 'skew' ]
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_skew_xy',
            [
                'label' => esc_html__( 'Skew 2D ( X,Y )', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => 'Xdeg,Ydeg',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-skew .elementor-heading-title' => 'transform:skew( {{VALUE}} );'],
                'condition' => [ 'heading_css_transform_type' => 'skew' ]
            ]
        );
        // Custom
        $widget->add_control( 'heading_css_transform_custom_heading',
            [
                'label' => esc_html__( 'Custom Transform', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before',
                'condition' => [ 'heading_css_transform_type' => 'custom' ]
            ]
        );
        $widget->add_responsive_control( 'heading_css_transform_custom_xy',
            [
                'label' => esc_html__( 'Transform', 'styler' ),
                'type' => Controls_Manager::TEXTAREA,
                'label_block' => true,
                'placeholder' => 'rotate(Xdeg,Ydeg) translate(Xpx,Ypx) scale(X,Y)',
                'selectors' => [ '{{WRAPPER}}.styler-transform.transform-type-custom .elementor-heading-title' => 'transform:( {{VALUE}} );'],
                'condition' => [ 'heading_css_transform_type' => 'custom' ]
            ]
        );
        $widget->end_controls_section();

        $widget->start_controls_section( 'styler_heading_css_stroke_controls_section',
            [
                'label' => esc_html__( 'Styler CSS Stroke', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $widget->add_control( 'styler_heading_css_stroke_switcher',
            [
                'label' => esc_html__( 'Enable Stroke', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-stroke styler-has-stroke-',
            ]
        );
        $widget->add_control( 'styler_heading_css_stroke_type',
            [
                'label' => esc_html__( 'Stroke Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'full',
                'options' => [
                    'full' => esc_html__( 'Full Text', 'styler' ),
                    'part' => esc_html__( 'Part of Text', 'styler' ),
                ],
                'prefix_class' => 'styler-has-stroke-type stroke-type-',
                'condition' => ['styler_heading_css_stroke_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_heading_css_stroke_note',
            [
                'label' => esc_html__( 'Important Note', 'styler' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'Please add part of text in <b> your text </b>', 'styler' ),
                'content_classes' => 'styler-message',
                'conditions' => [
                    'relation' => 'and',
                    'terms' => [
                        [
                            'name' => 'styler_heading_css_stroke_switcher',
                            'operator' => '==',
                            'value' => 'yes'
                        ],
                        [
                            'name' => 'styler_heading_css_stroke_type',
                            'operator' => '==',
                            'value' => 'part'
                        ]
                    ]
                ]
            ]
        );
        $widget->add_control( 'styler_heading_css_stroke_width',
            [
                'label' => esc_html__( 'Stroke Width', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 20,
                'step' => 1,
                'default' => 1,
                'selectors' => [
                    '{{WRAPPER}}.styler-stroke.stroke-type-full .elementor-heading-title' => '-webkit-text-stroke-width: {{SIZE}}px;color:transparent;',
                    '{{WRAPPER}}.styler-stroke.stroke-type-part .elementor-heading-title b' => '-webkit-text-stroke-width: {{SIZE}}px;color:transparent;',
                ],
                'condition' => ['styler_heading_css_stroke_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_heading_css_stroke_color',
            [
                'label' => esc_html__( 'Stroke Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '#000',
                'selectors' => [
                    '{{WRAPPER}}.styler-stroke.stroke-type-full .elementor-heading-title' => '-webkit-text-stroke-color: {{VALUE}};',
                    '{{WRAPPER}}.styler-stroke.stroke-type-part .elementor-heading-title b' => '-webkit-text-stroke-color: {{VALUE}};',
                ],
                'condition' => ['styler_heading_css_stroke_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_heading_css_stroke_fill_color',
            [
                'label' => esc_html__( 'Fill Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => 'transparent',
                'selectors' => [
                    '{{WRAPPER}}.styler-stroke.stroke-type-full .elementor-heading-title' => '-webkit-text-fill-color: {{VALUE}};',
                    '{{WRAPPER}}.styler-stroke.stroke-type-part .elementor-heading-title b' => '-webkit-text-fill-color: {{VALUE}};',
                ],
                'condition' => ['styler_heading_css_stroke_switcher' => 'yes']
            ]
        );
        $widget->end_controls_section();
        
        $widget->start_controls_section( 'styler_heading_css_marquee_controls_section',
            [
                'label' => esc_html__( 'Styler CSS Marquee', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $widget->add_control( 'styler_heading_css_marquee_switcher',
            [
                'label' => esc_html__( 'Enable Marquee Animation', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-text-marquee styler-has-marquee-',
            ]
        );
        $widget->add_control( 'styler_heading_css_marquee_note',
            [
                'label' => esc_html__( 'Important Note', 'styler' ),
                'type' => Controls_Manager::RAW_HTML,
                'raw' => esc_html__( 'Please add part of text in <span> your text </span> <span> your text </span> <span> your text </span>', 'styler' ),
                'content_classes' => 'styler-message',
                'condition' => ['styler_heading_css_marquee_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_heading_css_marquee_speed',
            [
                'label' => esc_html__( 'Animation Speed', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 0.1,
                'default' => 3,
                'selectors' => ['{{WRAPPER}}.styler-text-marquee .elementor-heading-title span' => '-webkit-animation-duration: {{SIZE}}s;animation-duration: {{SIZE}}s;' ],
                'condition' => ['styler_heading_css_marquee_switcher' => 'yes']
            ]
        );
        $widget->end_controls_section();
        
        /*
        $template = basename( get_page_template() );


        $widget->start_controls_section( 'styler_heading_split_controls_section',
            [
                'label' => esc_html__( 'Styler Split Text', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT
            ]
        );
        $widget->add_control( 'styler_heading_split_switcher',
            [
                'label' => esc_html__( 'Enable Split', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-headig-split heading-has-split-',
            ]
        );
        $widget->add_control( 'styler_heading_split_type',
            [
                'label' => esc_html__( 'Split Type', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'chars',
                'options' => [
                    'chars' => esc_html__( 'Chars', 'styler' ),
                    'words' => esc_html__( 'Words', 'styler' ),
                ],
                'condition' => ['styler_heading_split_switcher' => 'yes'],
            ]
        );
        $widget->add_control( 'styler_heading_split_entrance_animation',
            [
                'label' => esc_html__( 'Entrance Animation', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'fadeInUp2',
                'options' => [
                    'fadeIn2' => esc_html__( 'fadeIn', 'styler' ),
                    'fadeInUp2' => esc_html__( 'fadeInUp', 'styler' ),
                    'fadeInRight2' => esc_html__( 'fadeInRight', 'styler' ),
                    'fadeInLeft2' => esc_html__( 'fadeInLeft', 'styler' ),
                    'fadeInDown2' => esc_html__( 'fadeInDown', 'styler' ),
                    'bounceIn2' => esc_html__( 'bounceIn', 'styler' ),
                    'bounceInUp2' => esc_html__( 'bounceInUp', 'styler' ),
                    'bounceInRight2' => esc_html__( 'bounceInRight', 'styler' ),
                    'bounceInLeft2' => esc_html__( 'bounceInLeft', 'styler' ),
                    'bounceInDown2' => esc_html__( 'bounceInDown', 'styler' ),
                    'slideIn' => esc_html__( 'slideIn', 'styler' ),
                    'slideInDown' => esc_html__( 'slideInDown', 'styler' ),
                    'slideInUp' => esc_html__( 'slideInUp', 'styler' ),
                    'slideInLeft' => esc_html__( 'slideInLeft', 'styler' ),
                    'slideInRight' => esc_html__( 'slideInRight', 'styler' ),
                    'zoomIn' => esc_html__( 'zoomIn', 'styler' ),
                    'zoomInDown' => esc_html__( 'zoomInDown', 'styler' ),
                    'zoomInUp' => esc_html__( 'zoomInUp', 'styler' ),
                    'zoomInLeft' => esc_html__( 'zoomInLeft', 'styler' ),
                    'zoomInRight' => esc_html__( 'zoomInRight', 'styler' ),
                    'rotateIn' => esc_html__( 'rotateIn', 'styler' ),
                    'rotateInDownRight' => esc_html__( 'rotateInDownRight', 'styler' ),
                    'rotateInUpLeft' => esc_html__( 'rotateInUpLeft', 'styler' ),
                    'rotateInUpRight' => esc_html__( 'rotateInUpRight', 'styler' ),
                ],
                'condition' => ['styler_heading_split_switcher' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title.animated .char' => '-webkit-animation: {{VALUE}} 0.4s cubic-bezier(0.3, 0, 0.7, 1) both; animation: {{VALUE}} 0.4s cubic-bezier(0.3, 0, 0.7, 1) both;',
                    '{{WRAPPER}} .elementor-heading-title.animated .word' => '-webkit-animation: {{VALUE}} 0.4s cubic-bezier(0.3, 0, 0.7, 1) both; animation: {{VALUE}} 0.4s cubic-bezier(0.3, 0, 0.7, 1) both;',
                ]
            ]
        );
        $widget->add_control( 'styler_heading_split_delay',
            [
                'label' => esc_html__( 'Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 500,
                'step' => 1,
                'default' => 30,
                'description'=> esc_html__( 'the delay is in millisecond', 'styler' ),
                'condition' => ['styler_heading_split_switcher' => 'yes'],
                'selectors' => [
                    '{{WRAPPER}} .elementor-heading-title.animated .char' => '-webkit-animation-delay: calc({{VALUE}}ms * var(--char-index)); animation-delay: calc({{VALUE}}ms * var(--char-index));',
                    '{{WRAPPER}} .elementor-heading-title.animated .word' => '-webkit-animation-delay: calc({{VALUE}}ms * var(--word-index)); animation-delay: calc({{VALUE}}ms * var(--word-index));',
                ]
            ]
        );
        $widget->add_control( 'styler_heading_split_space',
            [
                'label' => esc_html__( 'Space Between Word', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 50,
                'step' => 1,
                'default' => 10,
                'condition' => ['styler_heading_split_switcher' => 'yes'],
                'selectors' => ['{{WRAPPER}} .elementor-heading-title.splitting .whitespace' => 'width:{{VALUE}}px;']
            ]
        );

        $widget->end_controls_section();
        */
    }

    public function styler_add_custom_controls_to_image( $widget )
    {
        // parallax image
        $widget->start_controls_section( 'styler_image_parallax_controls_section',
            [
                'label' => esc_html__( 'Styler Parallax', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'image[url]!' => '' ],
            ]
        );
        $widget->add_control( 'styler_image_parallax_switcher',
            [
                'label' => esc_html__( 'Enable Parallax', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-image-parallax image-has-parallax-',
            ]
        );
        $widget->add_control( 'styler_image_parallax_overflow',
            [
                'label' => esc_html__( 'Overflow', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_image_parallax_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_parallax_orientation',
            [
                'label' => esc_html__( 'Orientation', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'up',
                'options' => [
                    'up' => esc_html__( 'up', 'styler' ),
                    'right' => esc_html__( 'right', 'styler' ),
                    'down' => esc_html__( 'down', 'styler' ),
                    'left' => esc_html__( 'left', 'styler' ),
                    'up left' => esc_html__( 'up left', 'styler' ),
                    'up right' => esc_html__( 'up right', 'styler' ),
                    'down left' => esc_html__( 'down left', 'styler' ),
                    'left right' => esc_html__( 'left right', 'styler' ),
                ],
                'condition' => ['styler_image_parallax_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_parallax_scale',
            [
                'label' => esc_html__( 'Scale', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'default' => 1.2,
                'description'=> esc_html__( 'need to be above 1.0', 'styler' ),
                'condition' => ['styler_image_parallax_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_parallax_delay',
            [
                'label' => esc_html__( 'Delay', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10,
                'step' => 0.1,
                'default' => 0.4,
                'description'=> esc_html__( 'the delay is in second', 'styler' ),
                'condition' => ['styler_image_parallax_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_parallax_maxtransition',
            [
                'label' => esc_html__( 'Max Transition ( % )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 99,
                'step' => 1,
                'default' => 0,
                'description'=> esc_html__( 'it should be a percentage between 1 and 99', 'styler' ),
                'condition' => ['styler_image_parallax_switcher' => 'yes']
            ]
        );
        $widget->end_controls_section();

        // reveal effects
        $widget->start_controls_section( 'styler_image_reveal_effects_controls_section',
            [
                'label' => esc_html__( 'Reveal Effects', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
                'condition' => [ 'image[url]!' => '' ],
            ]
        );
        $widget->add_control( 'styler_image_reveal_switcher',
            [
                'label' => esc_html__( 'Enable Reveal', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'prefix_class' => 'styler-image-reveal image-has-reveal-',
            ]
        );
        $widget->add_control( 'styler_image_reveal_orientation',
            [
                'label' => esc_html__( 'Orientation', 'styler' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => [
                    'top' => esc_html__( 'up', 'styler' ),
                    'right' => esc_html__( 'right', 'styler' ),
                    'bottom' => esc_html__( 'down', 'styler' ),
                    'left' => esc_html__( 'left', 'styler' ),
                ],
                'condition' => ['styler_image_reveal_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_reveal_delay',
            [
                'label' => esc_html__( 'Delay ( ms )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 10000,
                'step' => 1,
                'default' => '',
                'description' => esc_html__( 'the delay is in second', 'styler' ),
                'condition' => ['styler_image_reveal_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_reveal_offset',
            [
                'label' => esc_html__( 'Offset ( px )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => -1000,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'condition' => ['styler_image_reveal_switcher' => 'yes']
            ]
        );
        $widget->add_group_control(
            Group_Control_Background::get_type(),
            [
                'name' => 'styler_image_reveal_color',
                'label' => esc_html__( 'Background', 'styler' ),
                'types' => [ 'classic', 'gradient' ],
                'selector' => '{{WRAPPER}} .reveal-holder .reveal-block::before',
                'separator' => 'before',
                'condition' => ['styler_image_reveal_switcher' => 'yes']
            ]
        );
        $widget->add_control( 'styler_image_reveal_once',
            [
                'label' => esc_html__( 'Animate Once?', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'condition' => ['styler_image_reveal_switcher' => 'yes']
            ]
        );
        $widget->end_controls_section();
    }

    public function styler_after_render_widget( $widget )
    {
        $tilt_elements_attr = array(
            'image-box',
            'styler-team-member',
            'styler-services-item',
        );
        foreach ( $tilt_elements_attr as $w ) {
            if ( $w === $widget->get_name() && 'yes' === $widget->get_settings('styler_tilt_effect_switcher') ) {
                wp_enqueue_script( 'tilt' );
            }
        }
        if ( 'image' === $widget->get_name() && 'yes' == $widget->get_settings('styler_image_parallax_switcher') ) {
            wp_enqueue_script( 'simple-parallax' );
        }
        if ( 'image' === $widget->get_name() && 'yes' == $widget->get_settings('styler_image_reveal_switcher') ) {
            wp_enqueue_style( 'aos' );
            wp_enqueue_script( 'aos' );
        }
        if ( 'heading' === $widget->get_name() && 'yes' == $widget->get_settings('styler_heading_split_switcher') ) {
            wp_enqueue_style( 'splitting' );
            wp_enqueue_style( 'splitting-cells' );
            wp_enqueue_script( 'splitting' );
            wp_enqueue_script( 'wow' );
        }
    }
    public function styler_add_custom_attr_to_widget( $widget )
    {
        $template = basename( get_page_template() );

        if ( 'icon' === $widget->get_name() ) {
            $option_id = $widget->get_settings('styler_icon_popup_content');
            if ( 'yes' === $widget->get_settings('styler_icon_popup_switcher') && !empty( $option_id ) ) {
                $widget->add_render_attribute( '_wrapper', 'data-styler-popup', 'styler-popup-'.$option_id );
            }
        }
        /*
        if ( 'image' === $widget->get_name() ) {

            if ( 'yes' == $widget->get_settings('styler_image_parallax_switcher') ) {
                $mydata = array();
                $overflow = $widget->get_settings('styler_image_parallax_overflow');
                $orientation = $widget->get_settings('styler_image_parallax_orientation');
                $scale = $widget->get_settings('styler_image_parallax_scale');
                $delay = $widget->get_settings('styler_image_parallax_delay');
                $maxtrans = $widget->get_settings('styler_image_parallax_maxtransition');

                $mydata[] .= $orientation ? '"orientation":"'.$orientation.'"' : '"orientation":"up"';
                $mydata[] .= 'yes' == $overflow ? '"overflow": true' : '"overflow": false';
                $mydata[] .= '' != $scale ? '"scale":'.$scale : '"scale":1.2';
                $mydata[] .= '' != $delay ? '"delay":'.$delay : '"delay":0.4';
                $mydata[] .= '' != $maxtrans ? '"maxtrans":'.$maxtrans : '"maxtrans":0';
                $parallaxattr = '{'.implode(',', $mydata ).'}';
                $widget->add_render_attribute( '_wrapper', 'data-image-parallax-settings', $parallaxattr);
            }
            if ( 'yes' == $widget->get_settings('styler_image_reveal_switcher') ) {
                $mydata = array();
                $orientation = $widget->get_settings('styler_image_reveal_orientation');
                $delay = $widget->get_settings('styler_image_reveal_delay');
                $offset = $widget->get_settings('styler_image_reveal_offset');
                $once = $widget->get_settings('styler_image_reveal_once');

                $mydata[] .= $orientation ? '"orientation":"'.$orientation.'"' : '"orientation":"left"';
                $mydata[] .= '' != $delay ? '"delay":'.$delay : '"delay":""';
                $mydata[] .= '' != $offset ? '"offset":'.$offset : '"offset":""';
                $mydata[] .= '' != $once ? '"once": "true"' : '"once":"false"';
                $revealattr = '{'.implode(',', $mydata ).'}';
                $widget->add_render_attribute( '_wrapper', 'data-image-reveal-settings', $revealattr);
            }
        }

        if ( 'heading' === $widget->get_name() ) {

            if ( 'yes' == $widget->get_settings('styler_heading_split_switcher') ) {

                $animation = $widget->get_settings('styler_heading_split_entrance_animation');
                $animation = $animation ? $animation : 'fadeInUp';
                $split_type = $widget->get_settings('styler_heading_split_type');
                $mydata = '{"type":"'.$split_type.'","animation":"'.$animation.'"}';
                $widget->add_render_attribute( '_wrapper', 'data-split-settings', $mydata );
            }
        }

        $tilt_elements_attr = array(
            'image-box',
            'styler-team-member',
            'styler-services-item',
        );
        foreach ( $tilt_elements_attr as $w ) {
            if ( $w === $widget->get_name() && 'yes' === $widget->get_settings('styler_tilt_effect_switcher') ) {
                $transition = 'yes' === $widget->get_settings('styler_tilt_effect_transition') ? 'true' : 'false';
                $reset = 'yes' === $widget->get_settings('styler_tilt_effect_reset') ? 'true' : 'false';
                $glare = 'yes' === $widget->get_settings('styler_tilt_effect_glare') ? 'true' : 'false';
                $widget->add_render_attribute( '_wrapper', 'data-tilt', '' );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-max', $widget->get_settings('styler_tilt_effect_maxtilt') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-perspective', $widget->get_settings('styler_tilt_effect_perspective') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-easing', $widget->get_settings('styler_tilt_effect_easing') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-scale', $widget->get_settings('styler_tilt_effect_scale') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-speed', $widget->get_settings('styler_tilt_effect_speed') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-disableaxis', $widget->get_settings('styler_tilt_effect_disableaxis') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-maxglare', $widget->get_settings('styler_tilt_effect_maxglare') );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-transition', $transition );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-reset', $reset );
                $widget->add_render_attribute( '_wrapper', 'data-tilt-glare', $glare );
            }
        }
        */
    }

}
Styler_Customizing_Default_Widgets::get_instance();
