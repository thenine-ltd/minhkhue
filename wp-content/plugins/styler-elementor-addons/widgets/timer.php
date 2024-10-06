<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.

class Styler_Timer extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-countdown';
    }
    public function get_title() {
        return 'Countdown (N)';
    }
    public function get_icon() {
        return 'eicon-countdown';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    public function get_keywords() {
		return [ 'time', 'timer', 'count', 'counter', 'countdown', 'date' ];
	}
    public function get_script_depends() {
        return [ 'jquery-countdown' ];
    }
    // Registering Controls
    protected function register_controls() {
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section('countdown_settings',
            [
                'label' => esc_html__( 'Countdown Settings', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_control( 'date',
            [
                'label' => esc_html__( 'Time', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'placeholder' => esc_html__( 'Usage : 2030/01/01', 'styler' ),
                'default' => '2030/01/01'
            ]
        );
        $this->add_control( 'hr',
            [
                'label' => esc_html__( 'Hour', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Hr'
            ]
        );
        $this->add_control( 'min',
            [
                'label' => esc_html__( 'Minute', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Min'
            ]
        );
        $this->add_control( 'sec',
            [
                'label' => esc_html__( 'Second', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => 'Sec'
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'style_section',
            [
                'label'=> esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_responsive_control('alignment',
            [
                'label' => esc_html__( 'Alignment', 'styler' ),
                'type' => Controls_Manager::CHOOSE,
                'toggle' => true,
                'default' => 'left',
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
                'selectors' => [ '{{WRAPPER}} .styler-coming-time' => 'justify-content: {{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'time_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-coming-time .time-count'
            ]
        );
        $this->add_responsive_control( 'time_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_responsive_control( 'time_last_color',
            [
                'label' => esc_html__( 'Last Item Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count:last-child' => 'color:{{VALUE}};' ],
            ]
        );
        $this->add_control( 'hide_sep',
            [
                'label' => esc_html__( 'Hide Separator', 'styler' ),
                'type' => Controls_Manager::SWITCHER,
                'default' => 'no',
            ]
        );
        $this->add_control( 'time_sep',
            [
                'label' => esc_html__( 'Seperator', 'styler' ),
                'type' => Controls_Manager::TEXT,
                'default' => ':',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count + .time-count:before' => 'content:"{{VALUE}}";' ],
                'condition' => ['hide_sep' => 'no']
            ]
        );
        $this->add_responsive_control( 'time_sep_color',
            [
                'label' => esc_html__( 'Seperator Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count + .time-count:before' => 'color:{{VALUE}};' ],
                'condition' => ['hide_sep' => 'no']
            ]
        );
        $this->add_responsive_control( 'time_sep_size',
            [
                'label' => esc_html__( 'Seperator Size', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 15,
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count + .time-count:before' => 'font-size:{{SIZE}}px;' ],
                'condition' => ['hide_sep' => 'no']
            ]
        );
        $this->add_responsive_control( 'time_min_width',
            [
                'label' => esc_html__( 'Item Min Width', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count' => 'min-width:{{SIZE}}px;' ],
            ]
        );
        $this->add_responsive_control( 'time_min_height',
            [
                'label' => esc_html__( 'Item Min height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count' => 'min-height:{{SIZE}}px;' ],
            ]
        );
        $this->add_responsive_control( 'time_sep_space',
            [
                'label' => esc_html__( 'Space Between Items', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => 15,
                'selectors' => [
                    '{{WRAPPER}} .styler-coming-time .time-count + .time-count:before' => 'margin:0 {{SIZE}}px;',
                    '{{WRAPPER}} .styler-coming-time.separator-none .time-count + .time-count' => 'margin-left:{{SIZE}}px;',
                ],
            ]
        );
        $this->add_responsive_control( 'time_padding',
            [
                'label' => esc_html__( 'Item Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
                'default' => [
                    'top' => '',
                    'right' => '',
                    'bottom' => '',
                    'left' => '',
                ]
            ]
        );
        $this->add_responsive_control( 'time_bgcolor',
            [
                'label' => esc_html__( 'Item Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'time_last_bgcolor',
            [
                'label' => esc_html__( 'Last Item Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count:last-child' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'time_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-coming-time .time-count',
            ]
        );
        $this->add_responsive_control( 'time_last_brdcolor',
            [
                'label' => esc_html__( 'Last Item Border Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-coming-time .time-count:last-child' => 'border-color:{{VALUE}};' ]
            ]
        );
        $this->add_responsive_control( 'time_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => [
                    '{{WRAPPER}} .styler-coming-time .time-count' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'
                ]
            ]
        );
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $s  = $this->get_settings_for_display();
        $id = $this->get_id();

        if ( $s['date'] ) {
            $sep = 'yes' == $s['hide_sep'] ? ' separator-none' : '';
            echo '<div class="styler-coming-time styler-widget-coming-time'.$sep.'" data-countdown=\'{"date":"'.$s['date'].'","hr":"'.$s['hr'].'","min":"'.$s['min'].'","sec":"'.$s['sec'].'"}\'></div>';
        }
    }
}
