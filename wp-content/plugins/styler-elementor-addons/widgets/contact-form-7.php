<?php

namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) exit; // If this file is called directly, abort.
class Styler_Contact_Form_7 extends Widget_Base {
    use Styler_Helper;
    public function get_name() {
        return 'styler-contact-form-7';
    }
    public function get_title() {
        return 'Contact Form 7 (N)';
    }
    public function get_icon() {
        return 'eicon-form-horizontal';
    }
    public function get_categories() {
        return [ 'styler' ];
    }
    // Registering Controls
    protected function register_controls() {
        $this->start_controls_section( 'general_sections',
            [
                'label'=> esc_html__( 'Form Data', 'styler' ),
                'tab'=> Controls_Manager::TAB_CONTENT
            ]
        );
        $this->add_control('id_control',
            [
                'label'=> esc_html__( 'Select Form', 'styler' ),
                'type'=> Controls_Manager::SELECT,
                'multiple'=> false,
                'options'=> $this->styler_get_cf7(),
                'description'=> esc_html__( 'Select Form to Embed', 'styler' ),
            ]
        );
        $this->end_controls_section();
        /*****   START CONTROLS SECTION   ******/

        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'form_style_section',
            [
                'label'=> esc_html__( 'STYLE', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );

        $this->add_responsive_control( 'form_general_max_width',
            [
                'label' => esc_html__( 'Form Max Width ( % )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 10,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-cf7-form-wrapper' => 'max-width: {{SIZE}}%;' ]
            ]
        );
        $this->add_control( 'form_general_input_divider',
            [
                'label' => esc_html__( 'INPUT', 'styler' ),
                'type' => Controls_Manager::HEADING,
                'separator' => 'before'
            ]
        );
        $this->add_responsive_control( 'form_general_input_padding',
            [
                'label' => esc_html__( 'Padding', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input,{{WRAPPER}} .styler-cf7-form-wrapper select, {{WRAPPER}} .styler-cf7-form-wrapper textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};'],
            ]
        );
        $this->add_responsive_control( 'form_general_input_height',
            [
                'label' => esc_html__( 'Min Height', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 10,
                'max' => 200,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),{{WRAPPER}} .styler-cf7-form-wrapper select' => 'min-height: {{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'form_general_input_spacing',
            [
                'label' => esc_html__( 'Spacing', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 100,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-cf7-form-wrapper form>label,{{WRAPPER}} .styler-cf7-form-wrapper form>.wpcf7-form-control-wrap' => 'margin-bottom: {{SIZE}}px;' ]
            ]
        );
        $this->start_controls_tabs( 'form_general_tabs');
        $this->start_controls_tab( 'form_general_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'form_general_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),{{WRAPPER}} .styler-cf7-form-wrapper select, {{WRAPPER}} .styler-cf7-form-wrapper textarea' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'form_general_bgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),{{WRAPPER}} .styler-cf7-form-wrapper select, {{WRAPPER}} .styler-cf7-form-wrapper textarea' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_general_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),{{WRAPPER}} .styler-cf7-form-wrapper select, {{WRAPPER}} .styler-cf7-form-wrapper textarea'
            ]
        );
        $this->add_responsive_control( 'form_general_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]),{{WRAPPER}} .styler-cf7-form-wrapper select, {{WRAPPER}} .styler-cf7-form-wrapper textarea' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'form_general_hover_tab',
            [ 'label' => esc_html__( 'Focus', 'styler' ) ]
        );
        $this->add_control( 'form_general_hvrcolor',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):focus,{{WRAPPER}} .styler-cf7-form-wrapper select:focus, {{WRAPPER}} .styler-cf7-form-wrapper textarea:focus' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'form_general_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):focus,{{WRAPPER}} .styler-cf7-form-wrapper select:focus, {{WRAPPER}} .styler-cf7-form-wrapper textarea:focus' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_general_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):focus,{{WRAPPER}} .styler-cf7-form-wrapper select:focus, {{WRAPPER}} .styler-cf7-form-wrapper textarea:focus'
            ]
        );
        $this->add_responsive_control( 'form_general_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper input:not([type="checkbox"]):not([type="radio"]):not([type="submit"]):focus,{{WRAPPER}} .styler-cf7-form-wrapper select:focus, {{WRAPPER}} .styler-cf7-form-wrapper textarea:focus' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'form_style_width_section',
            [
                'label'=> esc_html__( 'FORM ITEMS WIDTH', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        for ($i = 1; $i <= 15; $i++) {
            $this->add_responsive_control( 'item'.$i.'_width',
                [
                    'label' => esc_html__( $i.'. Item Width ( % )', 'styler' ),
                    'type' => Controls_Manager::NUMBER,
                    'min' => 0,
                    'max' => 100,
                    'step' => 1,
                    'default' => '',
                    'selectors' => [ '{{WRAPPER}} .styler-cf7-form-wrapper form > .child-'.$i => 'flex:0 0 auto;width: {{SIZE}}%;' ]
                ]
            );
        }
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
        /*****   START CONTROLS SECTION   ******/
        $this->start_controls_section( 'form_style_btn_section',
            [
                'label'=> esc_html__( 'SUBMIT BUTTON', 'styler' ),
                'tab' => Controls_Manager::TAB_CONTENT,
            ]
        );
        $this->add_group_control(
            Group_Control_Typography::get_type(),
            [
                'name' => 'form_btn_typo',
                'label' => esc_html__( 'Typography', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit'
            ]
        );
        $this->add_responsive_control( 'form_btn_margin_top',
            [
                'label' => esc_html__( 'Top Spacing ( px )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit' => 'margin-top: {{SIZE}}px;' ]
            ]
        );
        $this->add_responsive_control( 'form_btn_width',
            [
                'label' => esc_html__( 'Width ( px )', 'styler' ),
                'type' => Controls_Manager::NUMBER,
                'min' => 0,
                'max' => 1000,
                'step' => 1,
                'default' => '',
                'selectors' => [ '{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit' => 'width: {{SIZE}}px;' ]
            ]
        );
        $this->start_controls_tabs( 'form_btn_tabs');
        $this->start_controls_tab( 'form_btn_normal_tab',
            [ 'label' => esc_html__( 'Normal', 'styler' ) ]
        );
        $this->add_control( 'form_btn_color',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'form_btn_bgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_btn_border',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit'
            ]
        );
        $this->add_responsive_control( 'form_btn_border_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"], {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->start_controls_tab( 'form_btn_hover_tab',
            [ 'label' => esc_html__( 'Hover', 'styler' ) ]
        );
        $this->add_control( 'form_btn_hvrcolor',
            [
                'label' => esc_html__( 'Color', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"]:hover, {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit:hover' => 'color:{{VALUE}};' ]
            ]
        );
        $this->add_control( 'form_btn_hvrbgcolor',
            [
                'label' => esc_html__( 'Background', 'styler' ),
                'type' => Controls_Manager::COLOR,
                'default' => '',
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"]:hover, {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit:hover' => 'background-color:{{VALUE}};' ]
            ]
        );
        $this->add_group_control(
            Group_Control_Border::get_type(),
            [
                'name' => 'form_btn_hvrborder',
                'label' => esc_html__( 'Border', 'styler' ),
                'selector' => '{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"]:hover, {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit:hover'
            ]
        );
        $this->add_responsive_control( 'form_btn_hvrborder_radius',
            [
                'label' => esc_html__( 'Border Radius', 'styler' ),
                'type' => Controls_Manager::DIMENSIONS,
                'size_units' => [ 'px' ],
                'selectors' => ['{{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form input[type="submit"]:hover, {{WRAPPER}} .styler-cf7-form-wrapper form.wpcf7-form button.wpcf-7-submit:hover' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};']
            ]
        );
        $this->end_controls_tab();
        $this->end_controls_tabs();
        $this->end_controls_section();
        /*****   END CONTROLS SECTION   ******/
    }

    protected function render() {
        $settings  = $this->get_settings_for_display();
        $elementid = \Elementor\Plugin::$instance->editor->is_edit_mode() ? 'form_edit_'.$this->get_id() : 'form_front';
        $formid    = $settings['id_control'];

        if ( !empty( $formid ) ) {
            echo '<div class="styler-cf7-form-wrapper '.$elementid.'">';
                echo do_shortcode( '[contact-form-7 id="'.$formid.'"]' );
            echo '</div>';
        } else {
            echo "Please Select a Form";
        }
        if ( \Elementor\Plugin::$instance->editor->is_edit_mode() ) {
            ?>
            <script>

            jQuery(document).ready( function ($) {

                jQuery('.styler-cf7-form-wrapper.<?php echo esc_attr($elementid); ?>').each( function(index,el){

                    $(this).find('form>*').each( function(index,el){
            
                        $(this).addClass('child-'+index);
            
                    });

                });
            });

            </script>
            <?php
        }
    }
}