<?php
/**
 * jws CF7 Styler.
 *
 * @package jws
 */

namespace Elementor;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Background;
use Elementor\Widget_Base;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class Cf7_Styler.
 */
class Jws_cf7 extends Widget_Base {

	/**
	 * Retrieve CF7 Styler Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jws_cf7';
	}

	/**
	 * Retrieve CF7 Styler Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return 'Contact Form 7 Style';
	}

	/**
	 * Retrieve CF7 Styler Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-site-search';
	}

	/**
	 * Retrieve Widget Keywords.
	 *
	 * @since 1.5.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_keywords() {
		return ['form','7','contact'];
	}


	/**
	 * Function to integrate CF7 Forms.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_cf7_forms() {

		$field_options = array();

		if ( class_exists( 'WPCF7_ContactForm' ) ) {
			$args               = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);
			$forms              = get_posts( $args );
			$field_options['0'] = 'Select';
			if ( $forms ) {
				foreach ( $forms as $form ) {
					$field_options[ $form->ID ] = $form->post_title;
				}
			}
		}

		if ( empty( $field_options ) ) {
			$field_options = array(
				'-1' => esc_html__( 'You have not added any Contact Form 7 yet.', 'auriane' ),
			);
		}
		return $field_options;
	}

	/**
	 * Function to get CF7 Forms id.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_cf7_form_id() {
		if ( class_exists( 'WPCF7_ContactForm' ) ) {
			$args  = array(
				'post_type'      => 'wpcf7_contact_form',
				'posts_per_page' => -1,
			);
			$forms = get_posts( $args );

			if ( $forms ) {
				foreach ( $forms as $form ) {
					return $form->ID;
				}
			}
		}
		return -1;
	}


	/**
	 * Register CF7 Styler controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_controls() {

		$this->register_general_content_controls();
		$this->register_input_style_controls();
        $this->register_textarea_content_controls();
		$this->register_radio_content_controls();

		$this->register_button_content_controls();
		$this->register_error_content_controls();

		// Style Tab.
		$this->register_typography_style_controls();

		$this->register_helpful_information();
	}

	/**
	 * Register CF7 Styler General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_general_content_controls() {

		$this->start_controls_section(
			'section_general_field',
			[
				'label' => esc_html__( 'General', 'auriane' ),
			]
		);
			$this->add_control(
				'select_form',
				[
					'label'   => esc_html__( 'Select Form', 'auriane' ),
					'type'    => Controls_Manager::SELECT,
					'options' => $this->get_cf7_forms(),
					'default' => '0',
					'help'    => esc_html__( 'Choose the form that you want for this page for styling', 'auriane' ),
				]
			);

			$this->add_control(
				'cf7_style',
				[
					'label'        => esc_html__( 'Field Style', 'auriane' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'box',
					'options'      => [
						'box'       => esc_html__( 'Box', 'auriane' ),
						'underline' => esc_html__( 'Underline', 'auriane' ),
					],
					'prefix_class' => 'jws-cf7-style-',
				]
			);

			$this->add_control(
				'input_size',
				[
					'label'        => esc_html__( 'Field Size', 'auriane' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'sm',
					'options'      => [
						'xs' => esc_html__( 'Extra Small', 'auriane' ),
						'sm' => esc_html__( 'Small', 'auriane' ),
						'md' => esc_html__( 'Medium', 'auriane' ),
						'lg' => esc_html__( 'Large', 'auriane' ),
						'xl' => esc_html__( 'Extra Large', 'auriane' ),
					],
					'prefix_class' => 'jws-cf7-input-size-',
				]
			);

			$this->add_responsive_control(
				'cf7_input_padding',
				[
					'label'      => esc_html__( 'Field Padding', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style input:not([type="submit"]), {{WRAPPER}} .jws-cf7-style select, {{WRAPPER}} .jws-cf7-style textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style select[multiple="multiple"]'  => 'padding: 0px;',
						'{{WRAPPER}} .jws-cf7-style select[multiple="multiple"] option'  => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type="checkbox"] + span:before,{{WRAPPER}} .jws-cf7-style input[type="radio"] + span:before' => 'height: {{TOP}}{{UNIT}}; width: {{TOP}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-style-underline input[type="checkbox"] + span:before,{{WRAPPER}} .jws-cf7-style-underline input[type="radio"] + span:before' => 'height: {{TOP}}{{UNIT}}; width: {{TOP}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline input[type="checkbox"]:checked + span:before' => 'font-size: calc({{BOTTOM}}{{UNIT}} / 1.2);',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-webkit-slider-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-thumb' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-webkit-slider-runnable-track' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-track' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-lower' => 'font-size: {{BOTTOM}}{{UNIT}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-upper' => 'font-size: {{BOTTOM}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'cf7_input_bgcolor',
				[
					'label'     => esc_html__( 'Field Background Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fafafa',
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style input:not([type=submit]), {{WRAPPER}} .jws-cf7-style select, {{WRAPPER}} .jws-cf7-style textarea, {{WRAPPER}} .jws-cf7-style .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}} .jws-cf7-style .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}} .jws-cf7-style .wpcf7-radio input[type="radio"]:not(:checked) + span:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-webkit-slider-runnable-track,{{WRAPPER}} .jws-cf7-style input[type=range]:focus::-webkit-slider-runnable-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-track,{{WRAPPER}} input[type=range]:focus::-moz-range-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-lower,{{WRAPPER}} .jws-cf7-style input[type=range]:focus::-ms-fill-lower' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-upper,{{WRAPPER}} .jws-cf7-style input[type=range]:focus::-ms-fill-upper' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-style-box .wpcf7-radio input[type="radio"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-radio input[type="radio"]:checked + span:before' => 'box-shadow:inset 0px 0px 0px 4px {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_label_color',
				[
					'label'     => esc_html__( 'Label Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,					
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style .wpcf7 form.wpcf7-form:not(input)' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_input_color',
				[
					'label'     => esc_html__( 'Input Text / Placeholder Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,					
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style .wpcf7 input:not([type=submit]),{{WRAPPER}} .jws-cf7-style .wpcf7 input::placeholder, {{WRAPPER}} .jws-cf7-style .wpcf7 select, {{WRAPPER}} .jws-cf7-style .wpcf7 textarea, {{WRAPPER}} .jws-cf7-style .wpcf7 textarea::placeholder,{{WRAPPER}} .jws-cf7-style .jws-cf7-select-custom:after' => 'color: {{VALUE}};',
						'{{WRAPPER}}.elementor-widget-jws-cf7-styler .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.elementor-widget-jws-cf7-styler .wpcf7-acceptance input[type="checkbox"]:checked + span:before' => 'color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-style-box .wpcf7-radio input[type="radio"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-radio input[type="radio"]:checked + span:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-webkit-slider-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'input_border_style',
				[
					'label'       => esc_html__( 'Border Style', 'auriane' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'solid',
					'label_block' => false,
					'options'     => [
						'none'   => esc_html__( 'None', 'auriane' ),
						'solid'  => esc_html__( 'Solid', 'auriane' ),
						'double' => esc_html__( 'Double', 'auriane' ),
						'dotted' => esc_html__( 'Dotted', 'auriane' ),
						'dashed' => esc_html__( 'Dashed', 'auriane' ),
					],
					'condition'   => [
						'cf7_style' => 'box',
					],
					'selectors'   => [
						'{{WRAPPER}} .jws-cf7-style input:not([type=submit]), {{WRAPPER}} .jws-cf7-style select,{{WRAPPER}} .jws-cf7-style textarea,{{WRAPPER}}.jws-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-style: {{VALUE}};',
					],
				]
			);
			$this->add_control(
				'input_border_size',
				[
					'label'      => esc_html__( 'Border Width', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px' ],
					'default'    => [
						'top'    => '1',
						'bottom' => '1',
						'left'   => '1',
						'right'  => '1',
						'unit'   => 'px',
					],
					'condition'  => [
						'cf7_style'           => 'box',
						'input_border_style!' => 'none',
					],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style input:not([type=submit]), {{WRAPPER}} .jws-cf7-style select,{{WRAPPER}} .jws-cf7-style textarea,{{WRAPPER}}.jws-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'input_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'cf7_style'           => 'box',
						'input_border_style!' => 'none',
					],
					'default'   => '#eaeaea',
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style input:not([type=submit]), {{WRAPPER}} .jws-cf7-style select,{{WRAPPER}} .jws-cf7-style textarea,{{WRAPPER}}.jws-cf7-style-box .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-style-box .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-style-box .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-webkit-slider-runnable-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-lower' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-upper' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_border_bottom',
				[
					'label'      => esc_html__( 'Border Size', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 20,
						],
					],
					'default'    => [
						'size' => '2',
						'unit' => 'px',
					],
					'condition'  => [
						'cf7_style' => 'underline',
					],
					'selectors'  => [
						'{{WRAPPER}}.jws-cf7-style-underline input:not([type=submit]),{{WRAPPER}}.jws-cf7-style-underline select,{{WRAPPER}}.jws-cf7-style-underline textarea' => 'border-width: 0 0 {{SIZE}}{{UNIT}} 0; border-style: solid;',
						'{{WRAPPER}}.jws-cf7-style-underline .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-style-underline .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}} .wpcf7-radio input[type="radio"] + span:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid; box-sizing: content-box;',
					],
				]
			);

			$this->add_control(
				'cf7_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'cf7_style' => 'underline',
					],
					'default'   => '#c4c4c4',
					'selectors' => [
						'{{WRAPPER}}.jws-cf7-style-underline input:not([type=submit]),{{WRAPPER}}.jws-cf7-style-underline select,{{WRAPPER}}.jws-cf7-style-underline textarea, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-style-underline .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style-underline input[type=range]::-webkit-slider-runnable-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-track' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-lower' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-fill-upper' => 'border: 0.2px solid {{VALUE}}; box-shadow: 1px 1px 1px {{VALUE}}, 0px 0px 1px {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_ipborder_active',
				[
					'label'     => esc_html__( 'Border Active Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style .wpcf7 form input:not([type=submit]):focus, {{WRAPPER}} .jws-cf7-style select:focus, {{WRAPPER}} .jws-cf7-style .wpcf7 textarea:focus, {{WRAPPER}} .jws-cf7-style .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}} .jws-cf7-style .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}} .jws-cf7-style .wpcf7-radio input[type="radio"]:checked + span:before' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_input_radius',
				[
					'label'      => esc_html__( 'Rounded Corners', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style input:not([type="submit"]), {{WRAPPER}} .jws-cf7-style select, {{WRAPPER}} .jws-cf7-style textarea, {{WRAPPER}} .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-acceptance input[type="checkbox"] + span:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'    => '0',
						'bottom' => '0',
						'left'   => '0',
						'right'  => '0',
						'unit'   => 'px',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_text_align',
				[
					'label'     => esc_html__( 'Field Alignment', 'auriane' ),
					'type'      => Controls_Manager::CHOOSE,
					'options'   => [
						'left'   => [
							'title' => esc_html__( 'Left', 'auriane' ),
							'icon'  => 'fa fa-align-left',
						],
						'center' => [
							'title' => esc_html__( 'Center', 'auriane' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'  => [
							'title' => esc_html__( 'Right', 'auriane' ),
							'icon'  => 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style .wpcf7, {{WRAPPER}} .jws-cf7-style input:not([type=submit]),{{WRAPPER}} .jws-cf7-style textarea' => 'text-align: {{VALUE}};',
						' {{WRAPPER}} .jws-cf7-style select' => 'text-align-last:{{VALUE}};',
					],
				]
			);
            
            
         $this->add_group_control(
			\Elementor\Group_Control_Box_Shadow::get_type(),
			[
				'name' => 'cf7_input_box_shadow',
				'label' => esc_html__( 'Box Shadow', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-cf7-style input:not([type="submit"]), {{WRAPPER}} .jws-cf7-style select, {{WRAPPER}} .jws-cf7-style textarea, {{WRAPPER}} .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}} .wpcf7-acceptance input[type="checkbox"] + span:before'
			]
		);
            
            

		$this->end_controls_section();
	}

	/**
	 * Register CF7 Styler Radio Input & Checkbox Input Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_radio_content_controls() {

		$this->start_controls_section(
			'cf7_radio_check_style',
			[
				'label' => esc_html__( 'Radio & Checkbox', 'auriane' ),
			]
		);

			$this->add_control(
				'cf7_radio_check_adv',
				[
					'label'        => esc_html__( 'Override Current Style', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'auriane' ),
					'label_off'    => esc_html__( 'No', 'auriane' ),
					'return_value' => 'yes',
					'default'      => '',
					'separator'    => 'before',
					'prefix_class' => 'jws-cf7-check-',
				]
			);

			$this->add_control(
				'cf7_radio_check_size',
				[
					'label'        => esc_html__( 'Size', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'condition'  => [
						'cf7_radio_check_adv!' => '',
					],
					'default'    => [
						'unit' => 'px',
						'size' => 20,
					],
					'range'      => [
						'px' => [
							'min' => 15,
							'max' => 50,
						],
					],
					'selectors'  => [
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'width: {{SIZE}}{{UNIT}}; height:{{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before'  => 'font-size: calc( {{SIZE}}{{UNIT}} / 1.2 );',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-webkit-slider-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-moz-range-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-ms-thumb' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-webkit-slider-runnable-track' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-moz-range-track' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-ms-fill-lower' => 'font-size: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-ms-fill-upper' => 'font-size: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'cf7_radio_check_bgcolor',
				[
					'label'     => esc_html__( 'Background Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#fafafa',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-radio input[type="radio"]:not(:checked) + span:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-webkit-slider-runnable-track,{{WRAPPER}}.jws-cf7-check-yes input[type=range]:focus::-webkit-slider-runnable-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-moz-range-track,{{WRAPPER}} input[type=range]:focus::-moz-range-track' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-ms-fill-lower,{{WRAPPER}}.jws-cf7-check-yes input[type=range]:focus::-ms-fill-lower' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-ms-fill-upper,{{WRAPPER}}.jws-cf7-check-yes input[type=range]:focus::-ms-fill-upper' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-radio input[type="radio"]:checked + span:before' => 'box-shadow:inset 0px 0px 0px 4px {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_selected_color',
				[
					'label'     => esc_html__( 'Selected Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,					
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before' => 'color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-radio input[type="radio"]:checked + span:before' => 'background-color: {{VALUE}};',
						'{{WRAPPER}}.jws-cf7-check-yes input[type=range]::-webkit-slider-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-moz-range-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
						'{{WRAPPER}} .jws-cf7-style input[type=range]::-ms-thumb' => 'border: 1px solid {{VALUE}}; background: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_radio_label_color',
				[
					'label'     => esc_html__( 'Label Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .jws-cf7-style input[type="checkbox"] + span, .jws-cf7-style input[type="radio"] + span' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_check_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '#eaeaea',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors' => [
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before' => 'border-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'cf7_check_border_width',
				[
					'label'      => esc_html__( 'Border Width', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 20,
						],
					],
					'default'    => [
						'size' => '1',
						'unit' => 'px',
					],
					'condition'  => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors'  => [
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before,{{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"]:checked + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-radio input[type="radio"] + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"]:checked + span:before' => 'border-width: {{SIZE}}{{UNIT}}; border-style: solid;',
					],
				]
			);

			$this->add_control(
				'cf7_check_border_radius',
				[
					'label'      => esc_html__( 'Checkbox Rounded Corners', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'condition'  => [
						'cf7_radio_check_adv!' => '',
					],
					'selectors'  => [
						'{{WRAPPER}}.jws-cf7-check-yes .wpcf7-checkbox input[type="checkbox"] + span:before, {{WRAPPER}}.jws-cf7-check-yes .wpcf7-acceptance input[type="checkbox"] + span:before' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'default'    => [
						'top'    => '0',
						'bottom' => '0',
						'left'   => '0',
						'right'  => '0',
						'unit'   => 'px',
					],
				]
			);

		$this->end_controls_section();
	}
    /**
	 * Register CF7 Styler Textarea Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_textarea_content_controls() {
	   	$this->start_controls_section(
			'cf7_textarea',
			[
				'label' => esc_html__( 'Textarea', 'auriane' ),
			]
		);
        $this->add_responsive_control(
				'cf7_textarea_height',
				[
					'label'      => esc_html__( 'Height', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 700,
						],
					],
					'default'    => [
						'size' => '150',
						'unit' => 'px',
					],
					'selectors'  => [
						'{{WRAPPER}} textarea' => 'height:{{SIZE}}{{UNIT}};',
					],
				]
		);
        $this->add_responsive_control(
				'cf7_textarea_padding',
				[
					'label'      => esc_html__( 'Field Padding', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style textarea' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
		);
        $this->end_controls_section();
    }   
	/**
	 * Register CF7 Styler Button Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_button_content_controls() {

		$this->start_controls_section(
			'cf7_submit_button',
			[
				'label' => esc_html__( 'Submit Button', 'auriane' ),
			]
		);

			$this->add_responsive_control(
				'cf7_button_align',
				[
					'label'        => esc_html__( 'Button Alignment', 'auriane' ),
					'type'         => Controls_Manager::CHOOSE,
					'options'      => [
						'left'    => [
							'title' => esc_html__( 'Left', 'auriane' ),
							'icon'  => 'fa fa-align-left',
						],
						'center'  => [
							'title' => esc_html__( 'Center', 'auriane' ),
							'icon'  => 'fa fa-align-center',
						],
						'right'   => [
							'title' => esc_html__( 'Right', 'auriane' ),
							'icon'  => 'fa fa-align-right',
						],
						'justify' => [
							'title' => esc_html__( 'Justified', 'auriane' ),
							'icon'  => 'fa fa-align-justify',
						],
					],
					'default'      => 'left',
					'prefix_class' => 'jws%s-cf7-button-',
					'toggle'       => false,
				]
			);

			$this->add_control(
				'btn_size',
				[
					'label'        => esc_html__( 'Size', 'auriane' ),
					'type'         => Controls_Manager::SELECT,
					'default'      => 'sm',
					'options'      => [
						'xs' => esc_html__( 'Extra Small', 'auriane' ),
						'sm' => esc_html__( 'Small', 'auriane' ),
						'md' => esc_html__( 'Medium', 'auriane' ),
						'lg' => esc_html__( 'Large', 'auriane' ),
						'xl' => esc_html__( 'Extra Large', 'auriane' ),
					],
					'prefix_class' => 'jws-cf7-btn-size-',
				]
			);

			$this->add_responsive_control(
				'cf7_button_padding',
				[
					'label'      => esc_html__( 'Padding', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style .wpcf7-submit' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'after',
				]
			);
            $this->add_responsive_control(
				'cf7_button_margin',
				[
					'label'      => esc_html__( 'Margin', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style .wpcf7-submit' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'separator'  => 'after',
				]
			);
			$this->start_controls_tabs( 'tabs_button_style' );

				$this->start_controls_tab(
					'tab_button_normal',
					[
						'label' => esc_html__( 'Normal', 'auriane' ),
					]
				);

				$this->add_control(
						'button_text_color',
						[
							'label'     => esc_html__( 'Text Color', 'auriane' ),
							'type'      => Controls_Manager::COLOR,
							'default'   => '',
							'selectors' => [
								'{{WRAPPER}} .jws-cf7-style .wpcf7-submit' => 'color: {{VALUE}};',
							],
						]
					);
                    
                    $this->add_control(
        			'button_type',
        			[
        				'label' => esc_html__( 'Button Background Type', 'auriane' ),
        				'type' => Controls_Manager::CHOOSE,
        				'options' => [
        					'classic'    => [
        						'title' => esc_html__( 'Classic', 'auriane' ),
        						'icon' => 'eicon-paint-brush',
        					],
        					'gradient' => [
        						'title' => esc_html__( 'Gradient', 'auriane' ),
        						'icon' => 'eicon-barcode',
        					],
        				],
        				'default' => '',
        			]
        		);
                $this->add_control(
        			'button_bg_color',
        			[
        				'label' => esc_html__( 'Background Color', 'auriane' ),
        				'type' => Controls_Manager::COLOR,
                        'selectors' => [
        					'{{WRAPPER}} .jws-cf7-style .wpcf7-submit' => 'background:{{VALUE}};',
        				],
        				'default' => '',
                        'condition' => [
        					'button_type' => 'classic',
        				],
        			]
        		);
                $this->add_control(
        			'button_bggradient_color',
        			[
        				'label' => esc_html__( 'Background Color', 'auriane' ),
        				'type' => Controls_Manager::COLOR,
        				'default' => '',
                        'condition' => [
        					'button_type' => 'gradient',
        				],
        			]
        		);
                $this->add_control(
        			'button_bggradient_color2',
        			[
        				'label' => esc_html__( 'Background Second Color', 'auriane' ),
        				'type' => Controls_Manager::COLOR,
        				'default' => '',
        				'selectors' => [
        					'{{WRAPPER}} .jws-cf7-style .wpcf7-submit' => 'background-image: linear-gradient(to right, {{button_bggradient_color.VALUE}} 0%, {{VALUE}} 51%, {{button_bggradient_color.VALUE}} 100%);background-size: 200% auto;',
                            '{{WRAPPER}} .jws-cf7-style .wpcf7-submit:hover' => 'background-position: right center;',
        				],
                        'condition' => [
        					'button_type' => 'gradient',
        				],
        			]
        		);


					$this->add_group_control(
						Group_Control_Border::get_type(),
						[
							'name'        => 'btn_border',
							'label'       => esc_html__( 'Border', 'auriane' ),
							'placeholder' => '1px',
							'default'     => '1px',
							'selector'    => '{{WRAPPER}} .jws-cf7-style .wpcf7-submit',
						]
					);

					$this->add_responsive_control(
						'btn_border_radius',
						[
							'label'      => esc_html__( 'Border Radius', 'auriane' ),
							'type'       => Controls_Manager::DIMENSIONS,
							'size_units' => [ 'px', '%' ],
							'selectors'  => [
								'{{WRAPPER}} .jws-cf7-style .wpcf7-submit' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
							],
						]
					);

					$this->add_group_control(
						Group_Control_Box_Shadow::get_type(),
						[
							'name'     => 'button_box_shadow',
							'selector' => '{{WRAPPER}} .jws-cf7-style .wpcf7-submit',
						]
					);

				$this->end_controls_tab();

				$this->start_controls_tab(
					'tab_button_hover',
					[
						'label' => esc_html__( 'Hover', 'auriane' ),
					]
				);

					$this->add_control(
						'btn_hover_color',
						[
							'label'     => esc_html__( 'Text Color', 'auriane' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .jws-cf7-style .wpcf7-submit:hover' => 'color: {{VALUE}};',
							],
						]
					);

					$this->add_control(
						'button_hover_border_color',
						[
							'label'     => esc_html__( 'Border Hover Color', 'auriane' ),
							'type'      => Controls_Manager::COLOR,
							'selectors' => [
								'{{WRAPPER}} .jws-cf7-style .wpcf7-submit:hover' => 'border-color: {{VALUE}};',
							],
						]
					);
                      $this->add_control(
            			'button_background_hover_color',
            			[
            				'label' => esc_html__( 'Background Color', 'auriane' ),
            				'type' => Controls_Manager::COLOR,
                            'selectors' => [
            					'{{WRAPPER}} .jws-cf7-style .wpcf7-submit:hover' => 'background:{{VALUE}};',
            				],
            				'default' => '',
                            'condition' => [
            					'button_type' => 'classic',
            				],
            			]
            		);


				$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register CF7 Styler Error Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_error_content_controls() {

		$this->start_controls_section(
			'cf7_error_field',
			[
				'label' => esc_html__( 'Success / Error Message', 'auriane' ),
			]
		);

			$this->add_control(
				'cf7_field_message',
				[
					'label'     => esc_html__( 'Field Validation', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

				$this->add_control(
					'cf7_highlight_style',
					[
						'label'        => esc_html__( 'Message Position', 'auriane' ),
						'type'         => Controls_Manager::SELECT,
						'default'      => 'default',
						'options'      => [
							'default'      => esc_html__( 'Default', 'auriane' ),
							'tooltip' => esc_html__( 'Tooltip', 'auriane' ),
						],
						'prefix_class' => 'jws-cf7-highlight-style-',
					]
				);

				$this->add_control(
					'cf7_message_color',
					[
						'label'     => esc_html__( 'Border Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ff0000',
						'condition' => [
							'cf7_highlight_style' => 'default',
						],
						'selectors' => [
							'{{WRAPPER}} .jws-cf7-style input:not([type=submit]).wpcf7-not-valid' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_message_highlight_color',
					[
						'label'     => esc_html__( 'Message Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ffffff',
						'condition' => [
							'cf7_highlight_style' => 'tooltip',
						],
						'selectors' => [
							'{{WRAPPER}} .jws-cf7-style span.wpcf7-not-valid-tip' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_message_bgcolor',
					[
						'label'     => esc_html__( 'Message Background Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => 'rgba(255, 0, 0, 0.6)',
						'condition' => [
							'cf7_highlight_style' => 'tooltip',
						],
						'selectors' => [
							'{{WRAPPER}} .jws-cf7-style span.wpcf7-not-valid-tip' => 'background-color: {{VALUE}}; padding: 0.1em 0.8em;',
                            '{{WRAPPER}} .jws-cf7-style span.wpcf7-not-valid-tip:after' => 'border-top: 5px solid {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_highlight_border',
					[
						'label'        => esc_html__( 'Highlight Borders', 'auriane' ),
						'type'         => Controls_Manager::SWITCHER,
						'label_on'     => esc_html__( 'Yes', 'auriane' ),
						'label_off'    => esc_html__( 'No', 'auriane' ),
						'return_value' => 'yes',
						'default'      => '',
						'prefix_class' => 'jws-cf7-highlight-',
					]
				);

				$this->add_control(
					'cf7_highlight_border_color',
					[
						'label'     => esc_html__( 'Highlight Border Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ff0000',
						'condition' => [
							'cf7_highlight_border' => 'yes',
						],
						'selectors' => [
							'{{WRAPPER}} .jws-cf7-style .wpcf7-form-control.wpcf7-not-valid, {{WRAPPER}} .jws-cf7-style .wpcf7-form-control.wpcf7-not-valid .wpcf7-list-item-label:before' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => 'cf7_message_typo',
						
						'selector' => '{{WRAPPER}} .jws-cf7-style span.wpcf7-not-valid-tip',
					]
				);

			$this->add_control(
				'cf7_validation_message',
				[
					'label'     => esc_html__( 'Form Success / Error Validation', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

				$this->add_control(
					'cf7_success_message_color',
					[
						'label'     => esc_html__( 'Success Message Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .wpcf7 form.sent .wpcf7-response-output' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_success_message_bgcolor',
					[
						'label'     => esc_html__( 'Success Message Background Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .wpcf7 form.sent .wpcf7-response-output' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_success_border_color',
					[
						'label'     => esc_html__( 'Success Border Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#008000',
						'condition' => [
							'cf7_valid_border_size!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .wpcf7 form.sent .wpcf7-response-output' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_error_message_color',
					[
						'label'     => esc_html__( 'Error Message Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .wpcf7 form.invalid .wpcf7-response-output, {{WRAPPER}} .wpcf7 form.unaccepted .wpcf7-response-output' => 'color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_error_message_bgcolor',
					[
						'label'     => esc_html__( 'Error Message Background Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .wpcf7 form.invalid .wpcf7-response-output, {{WRAPPER}} .wpcf7 form.unaccepted .wpcf7-response-output' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'cf7_error_border_color',
					[
						'label'     => esc_html__( 'Error Border Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '#ff0000',
						'condition' => [
							'cf7_valid_border_size!' => '',
						],
						'selectors' => [
							'{{WRAPPER}} .wpcf7 form.invalid .wpcf7-response-output, {{WRAPPER}} .wpcf7 form.unaccepted .wpcf7-response-output' => 'border-color: {{VALUE}};',
						],
					]
				);

				$this->add_responsive_control(
					'cf7_valid_border_size',
					[
						'label'      => esc_html__( 'Border Size', 'auriane' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'default'    => [
							'top'    => '2',
							'bottom' => '2',
							'left'   => '2',
							'right'  => '2',
							'unit'   => 'px',
						],
						'selectors'  => [
							'{{WRAPPER}} .wpcf7 form .wpcf7-response-output' => 'border-width: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}; border-style: solid;',
						],
					]
				);

				$this->add_responsive_control(
					'cf7_valid_message_radius',
					[
						'label'      => esc_html__( 'Rounded Corners', 'auriane' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .wpcf7 form .wpcf7-response-output' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_responsive_control(
					'cf7_valid_message_padding',
					[
						'label'      => esc_html__( 'Message Padding', 'auriane' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .wpcf7 form .wpcf7-response-output' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);
                
                $this->add_responsive_control(
					'cf7_valid_message_margin',
					[
						'label'      => esc_html__( 'Message Margin', 'auriane' ),
						'type'       => Controls_Manager::DIMENSIONS,
						'size_units' => [ 'px', 'em', '%' ],
						'selectors'  => [
							'{{WRAPPER}} .wpcf7 form .wpcf7-response-output' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
				);

				$this->add_group_control(
					Group_Control_Typography::get_type(),
					[
						'name'     => 'cf7_validation_typo',
						
						'selector' => '{{WRAPPER}} .wpcf7 form .wpcf7-response-output',
					]
				);

		$this->end_controls_section();
	}


	/**
	 * Register CF7 Styler Input Style Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_input_style_controls() {

		$this->start_controls_section(
			'cf7_input_spacing',
			[
				'label' => esc_html__( 'Spacing', 'auriane' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_responsive_control(
				'cf7_input_margin_top',
				[
					'label'      => esc_html__( 'Between Label & Input', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 60,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 5,
					],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style input:not([type=submit]):not([type=checkbox]):not([type=radio]), {{WRAPPER}} .jws-cf7-style select, {{WRAPPER}} .jws-cf7-style textarea, {{WRAPPER}} .jws-cf7-style span.wpcf7-list-item' => 'margin-top: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'cf7_input_margin_bottom',
				[
					'label'      => esc_html__( 'Between Fields', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', 'em', 'rem' ],
					'range'      => [
						'px' => [
							'min' => 1,
							'max' => 60,
						],
					],
					'default'    => [
						'unit' => 'px',
						'size' => 10,
					],
					'selectors'  => [
						'{{WRAPPER}} .jws-cf7-style input:not([type=submit]):not([type=checkbox]):not([type=radio]), {{WRAPPER}} .jws-cf7-style select, {{WRAPPER}} .jws-cf7-style textarea, {{WRAPPER}} .jws-cf7-style span.wpcf7-list-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}


	/**
	 * Register CF7 Styler Input Typography Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_typography_style_controls() {

		$this->start_controls_section(
			'cf7_typo',
			[
				'label' => esc_html__( 'Typography', 'auriane' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'cf7_label_typo',
				[
					'label'     => esc_html__( 'Form Label', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'form_label_typography',
					
					'selector' => '{{WRAPPER}} .jws-cf7-style .wpcf7 form.wpcf7-form label',
				]
			);

			$this->add_control(
				'cf7_input_typo',
				[
					'label'     => esc_html__( 'Input Text / Placeholder', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'input_typography',
					
					'selector' => '{{WRAPPER}} .jws-cf7-style .wpcf7 input:not([type=submit]), {{WRAPPER}} .jws-cf7-style .wpcf7 input::placeholder, {{WRAPPER}} .wpcf7 select,{{WRAPPER}} .jws-cf7-style .wpcf7 textarea, {{WRAPPER}} .jws-cf7-style .wpcf7 textarea::placeholder, {{WRAPPER}} .jws-cf7-style input[type=range]::-webkit-slider-thumb,{{WRAPPER}} .jws-cf7-style .jws-cf7-select-custom',
				]
			);

			$this->add_control(
				'btn_typography_label',
				[
					'label'     => esc_html__( 'Button', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'btn_typography',
					'label'    => esc_html__( 'Typography', 'auriane' ),
					
					'selector' => '{{WRAPPER}} .jws-cf7-style .wpcf7-submit',
				]
			);

			$this->add_control(
				'cf7_radio_check_typo',
				[
					'label'     => esc_html__( 'Radio Button & Checkbox', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'radio_check_typography',
					
					'condition' => [
						'cf7_radio_check_adv!' => '',
					],
					'selector'  => '{{WRAPPER}} .jws-cf7-style input[type="checkbox"] + span, .jws-cf7-style input[type="radio"] + span',
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Helpful Information.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_helpful_information() {

			$this->start_controls_section(
				'section_helpful_info',
				[
					'label' => esc_html__( 'Helpful Information', 'auriane' ),
				]
			);

			$this->add_control(
				'help_doc_3',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( esc_html__( '%1$s Getting started video » %2$s', 'auriane' ), '<a href="https://www.youtube.com/watch?v=qhFfKf2R-SM&list=PL1kzJGWGPrW_7HabOZHb6z88t_S8r-xAc&index=8" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'jws-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_1',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s Doc Link */
					'raw'             => sprintf( esc_html__( '%1$s Display input fields in column » %2$s', 'auriane' ), '<a href="https://jwsementor.com/docs/how-to-set-multiple-column-fields-in-contact-form-7-styler-of-jws/?utm_source=auriane-dashboard&utm_medium=jws-editor-screen&utm_campaign=auriane-plugin" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'jws-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_2',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s Doc Link */
					'raw'             => sprintf( esc_html__( '%1$s Unable to see Checkbox / Radio / Acceptance control » %2$s', 'auriane' ), '<a href="https://jwsementor.com/docs/unable-to-style-checkbox-radio-buttons-acceptance-control-using-contact-form-7-styler-of-jws/?utm_source=auriane-dashboard&utm_medium=jws-editor-screen&utm_campaign=auriane-plugin" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'jws-editor-doc',
				]
			);

			$this->end_controls_section();
	}

	/**
	 * Render Editor Script. Which will show error at editor.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render_editor_script() {

		if ( \Elementor\Plugin::instance()->editor->is_edit_mode() === false ) {
			return;
		}

		$pre_url = wpcf7_get_request_uri();

		if ( strpos( $pre_url, 'admin-ajax.php' ) === false ) {
			return;
		}

		?><script type="text/javascript">
			jQuery( document ).ready( function( $ ) {

				$( '.jws-cf7-container' ).each( function() {

					var $node_id 	= '<?php echo esc_attr($this->get_id()); ?>';
					var	scope 		= $( '[data-id="' + $node_id + '"]' );
					var selector 	= $(this);

					if ( selector.closest( scope ).length < 1 ) {
						return;
					}

					if ( selector.find( 'div.wpcf7 > form' ).length < 1 ) {
						return;
					}

					selector.find( 'div.wpcf7 > form' ).each( function() {
						var $form = $( this );
						wpcf7.initForm( $form );
					} );
				});
			});
		</script>
		<?php
	}

	/**
	 * Render CF7 Styler output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		if ( ! class_exists( 'WPCF7_ContactForm' ) ) {
			return;
		}

		$settings      = $this->get_settings();
		$node_id       = $this->get_id();
		$field_options = array();
		$classname     = '';

		$args = array(
			'post_type'      => 'wpcf7_contact_form',
			'posts_per_page' => -1,
		);

		$forms              = get_posts( $args );
		$field_options['0'] = esc_html__( 'select', 'auriane' );
		if ( $forms ) {
			foreach ( $forms as $form ) {
				$field_options[ $form->ID ] = $form->post_title;
			}
		}

		$forms = $this->get_cf7_forms();

		$html = '';

		if ( ! empty( $forms ) && ! isset( $forms[-1] ) ) {
			if ( '0' === $settings['select_form'] ) {
				$html = esc_html__( 'Please select a Contact Form 7.', 'auriane' );
			} else {
				?>
				<div class = "jws-cf7-container">
						<div class = "jws-cf7 jws-cf7-style elementor-clickable">
						<?php
						if ( $settings['select_form'] ) {
							echo do_shortcode( '[contact-form-7 id=' . $settings['select_form'] . ']' );
						}
						?>
					</div>
				</div>
				<?php
			}
		} else {
			$html = esc_html__( 'You have not added any Contact Form 7 yet.', 'auriane' );
		}
		if(function_exists('output_ech')) {
            echo output_ech($html);
        }

		$this->render_editor_script();
	}

}

