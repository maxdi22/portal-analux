<?php
/**
 * jws Off - Canvas.
 *
 * @package JWS
 */

namespace Elementor;

// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;


if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

/**
 * Class Offcanvas.
 */
class Offcanvas extends Widget_Base {

	/**
	 * Retrieve Off - Canvas Widget name.
	 *
	 * @since 1.11.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'Offcanvas';
	}

	/**
	 * Retrieve Offcanvas Widget title.
	 *
	 * @since 1.11.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return 'Jws Offcanvas';
	}

	/**
	 * Retrieve OffCanvas Widget icon.
	 *
	 * @since 1.11.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-button';
	}

	/**
	 * Retrieve Widget Keywords.
	 *
	 * @since 1.11.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_keywords() {
		return 'Offcanvas';
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.11.0
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'jws-canvas'];
	}
    /**
	 * Retrieve the list of categories the widget belongs to.
	 *
	 * Used to determine where to display the widget in the editor.
	 *
	 * Note that currently Elementor supports only one category.
	 * When multiple categories passed, Elementor uses the first one.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget categories.
	 */
	public function get_categories() {
		return [ 'jws-elements' ];
	}
	/**
	 * Register canvas controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->register_general_content_controls();
		$this->register_display_offcanvas_controls();
		$this->register_display_content_controls();
		$this->register_close_controls();

		$this->register_offcanvas_style_controls();
		$this->register_menu_style_controls();
		$this->register_content_style_controls();
		$this->register_button_style_controls();
		$this->register_icon_style_controls();
		$this->register_close_icon_style_controls();
	}

	/**
	 * Register Off - Canvas Content Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_general_content_controls() {
		$this->start_controls_section(
			'content',
			[
				'label' => esc_html__( 'Content', 'auriane' ),
                'condition'    => [
						'use_login!' => 'yes',
				],
			]
		);

		$this->add_control(
			'preview_offcanvas',
			[
				'label'        => esc_html__( 'Preview', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => esc_html__( 'No', 'auriane' ),
				'label_on'     => esc_html__( 'Yes', 'auriane' ),
			]
		);
        $this->add_control(
			'animation_offcanvas',
			[
				'label'        => esc_html__( 'Animation', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => esc_html__( 'No', 'auriane' ),
				'label_on'     => esc_html__( 'Yes', 'auriane' ),
			]
		);
		$this->add_control(
			'content_type',
			[
				'label'   => esc_html__( 'Content Type', 'auriane' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'content',
				'options' => $this->get_content_type(),
			]
		);

		$menus = $this->get_menus_list();

		if ( ! empty( $menus ) ) {
			$this->add_control(
				'menu',
				[
					'label'        => esc_html__( 'Menu', 'auriane' ),
					'type'         => Controls_Manager::SELECT,
					'options'      => $menus,
					'default'      => array_keys( $menus )[0],
					'save_default' => true,
					/* translators: %s admin link */
					'description'  => sprintf( esc_html__( 'Go to the <a href="%s" target="_blank">Menus screen</a> to manage your menus.', 'auriane' ), admin_url( 'nav-menus.php' ) ),
					'condition'    => [
						'content_type' => 'menu',
					],
				]
			);
		} else {
			$this->add_control(
				'menu',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( esc_html__( '<strong>There are no menus in your site.</strong><br>Go to the <a href="%s" target="_blank">Menus screen</a> to create one.', 'auriane' ), admin_url( 'nav-menus.php?action=edit&menu=0' ) ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-info',
					'condition'       => [
						'content_type' => 'menu',
					],
				]
			);
		}

		$this->add_control(
			'ct_content',
			[
				'label'      => esc_html__( 'Description', 'auriane' ),
				'type'       => Controls_Manager::WYSIWYG,
				'default'    => esc_html__( 'Enter content here. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.​ Lorem ipsum dolor sit amet, consectetur adipiscing elit. Ut elit tellus, luctus nec ullamcorper mattis, pulvinar dapibus leo.', 'auriane' ),
				'rows'       => 10,
				'show_label' => false,
				'dynamic'    => [
					'active' => true,
				],
				'condition'  => [
					'content_type' => 'content',
				],
			]
		);

		$this->add_control(
			'ct_saved_rows',
			[
				'label'     => esc_html__( 'Select Section', 'auriane' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'section' ),
				'default'   => '-1',
				'condition' => [
					'content_type' => 'saved_rows',
				],
			]
		);

		$this->add_control(
			'ct_page_templates',
			[
				'label'     => esc_html__( 'Select Page', 'auriane' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'page' ),
				'default'   => '-1',
				'condition' => [
					'content_type' => 'saved_page_templates',
				],
			]
		);

		$this->add_control(
			'ct_saved_modules',
			[
				'label'     => esc_html__( 'Select Widget', 'auriane' ),
				'type'      => Controls_Manager::SELECT,
				'options'   => $this->get_saved_data( 'widget' ),
				'default'   => '-1',
				'condition' => [
					'content_type' => 'saved_modules',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Off - Canvas Title Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_display_content_controls() {
		$this->start_controls_section(
			'offcanvas',
			[
				'label' => esc_html__( 'Display Settings', 'auriane' ),
			]
		);
        $this->add_control(
			'use_login',
			[
				'label'        => esc_html__( 'Use For Login', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => esc_html__( 'No', 'auriane' ),
				'label_on'     => esc_html__( 'Yes', 'auriane' ),
			]
		);
        $this->add_control(
			'use_login_dropdown',
			[
				'label'        => esc_html__( 'Use Dropdown Link After Login', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => esc_html__( 'No', 'auriane' ),
				'label_on'     => esc_html__( 'Yes', 'auriane' ),
			]
		);
		$this->add_control(
			'offcanvas_on',
			[
				'label'   => esc_html__( 'Display On', 'auriane' ),
				'type'    => Controls_Manager::SELECT,
				'default' => 'button',
				'options' => [
					'button'    => esc_html__( 'Button', 'auriane' ),
				],
			]
		);

		$this->add_control(
			'btn_text',
			[
				'label'       => esc_html__( 'Button Text', 'auriane' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Click Me', 'auriane' ),
				'placeholder' => esc_html__( 'Click Me', 'auriane' ),
				'dynamic'     => [
					'active' => true,
				],
                'condition'   => [
					'offcanvas_on' => 'button',
				],
			]
		);
        
        $this->add_control(
			'btn_text_after_login',
			[
				'label'       => esc_html__( 'Button Text After Login', 'auriane' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => esc_html__( 'Click Me', 'auriane' ),
				'placeholder' => esc_html__( 'Click Me', 'auriane' ),
				'dynamic'     => [
					'active' => true,
				],
				'condition'   => [
					'offcanvas_on' => 'button',
                    'use_login' => 'yes',
				],
			]
		);

        $this->add_control(
			'btn_url_after_login',
			[
				'label' => esc_html__( 'Button Url After Login', 'auriane' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Loged Url', 'auriane' ),
                'dynamic'		=> [ 'active' => true ],
                'condition'   => [
					'offcanvas_on' => 'button',
                    'use_login' => 'yes',
				],
		
			]
		);



		$this->add_control(
			'jws_display_position',
			[
				'label'        => esc_html__( 'Position', 'auriane' ),
				'type'         => Controls_Manager::SELECT,
				'default'      => 'inline',
				'options'      => [
					'inline'   => esc_html__( 'Inline', 'auriane' ),
					'floating' => esc_html__( 'Floating', 'auriane' ),
				],
				'condition'    => [
					'offcanvas_on' => array( 'button', 'icon' ),
				],
				'prefix_class' => 'jws-offcanvas-trigger-align-',
				'render_type'  => 'template',
			]
		);

		// If jws_display_position is Inline.
		$this->add_responsive_control(
			'jws_display_inline_button_align',
			[
				'label'     => esc_html__( 'Alignment', 'auriane' ),
				'type'      => Controls_Manager::CHOOSE,
				'options'   => [
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
				'default'   => 'left',
				'condition' => [
					'jws_display_position' => 'inline',
					'offcanvas_on'          => 'button',
				],
				'toggle'    => false,
			]
		);

		// If jws_display_position is Floating.
		$this->add_control(
			'jws_display_floating_align',
			[
				'label'       => esc_html__( 'Alignment', 'auriane' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'left',
				'options'     => [
					'left'  => [
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon'  => 'fa fa-align-left',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon'  => 'fa fa-align-right',
					],
				],
				'toggle'      => false,
				'label_block' => false,
				'condition'   => [
					'offcanvas_on'          => array( 'button' ),
					'jws_display_position' => 'floating',
				],
			]
		);

		$this->add_responsive_control(
			'jws_display_floating_on_window_position',
			[
				'label'          => esc_html__( 'Floating Position', 'auriane' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => '%',
				'default'        => [
					'size' => '50',
					'unit' => '%',
				],
				'tablet_default' => [
					'size' => '50',
					'unit' => '%',
				],
				'mobile_default' => [
					'size' => '50',
					'unit' => '%',
				],
				'range'          => [
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors'      => [
					'{{WRAPPER}} .jws-offcanvas-action-wrap .jws-button-wrapper .jws-offcanvas-action-alignment-left,
					{{WRAPPER}} .jws-offcanvas-action-wrap .jws-offcanvas-icon-wrap .jws-offcanvas-action-alignment-left,
                    {{WRAPPER}} .jws-offcanvas-action-wrap .jws-button-wrapper .jws-offcanvas-action-alignment-right,
                    {{WRAPPER}} .jws-offcanvas-action-wrap .jws-offcanvas-icon-wrap .jws-offcanvas-action-alignment-right' => 'top: {{SIZE}}{{UNIT}}; transform: translateY( -{{SIZE}}{{UNIT}} );',
				],
				'condition'      => [
					'jws_display_position' => 'floating',
					'offcanvas_on'          => array( 'button' ),
				],
			]
		);

		$this->add_control(
				'new_offcanvas_button_icon',
				[
					'label'            => esc_html__( 'Select Icon', 'auriane' ),
					'type'             => Controls_Manager::ICONS,
					'fa4compatibility' => 'offcanvas_button_icon',
					'condition'        => [
						'offcanvas_on' => 'button',
					],
					'render_type'      => 'template',
				]
		);
        
        
       $this->add_control(
			'btn_icon_size',
			[
				'label'     => esc_html__( 'Icon Size (px)', 'auriane' ),
				'type'      => Controls_Manager::SLIDER,
				'default'   => [
					'size' => 60,
				],
				'range'     => [
					'px' => [
						'max' => 500,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws-elementor-button .elementor-button-icon i,
					{{WRAPPER}} .jws-elementor-button .elementor-button-icon svg' => 'font-size: {{SIZE}}px; width: {{SIZE}}px; height: {{SIZE}}px; line-height: {{SIZE}}px;',
				],
				'condition' => [
					'offcanvas_on' => array(  'button' ),
				],
			]
		);
        
        
        $this->add_responsive_control(
			'btn_icon_padding',
			[
				'label'      => esc_html__( 'Icon Padding', 'auriane' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jws-elementor-button .elementor-button-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'offcanvas_on' => 'button',
				],
			]
		);


		$this->add_control(
			'offcanvas_button_icon_position',
			[
				'label'       => esc_html__( 'Icon Position', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'right',
				'label_block' => false,
				'options'     => [
					'right' => esc_html__( 'After Text', 'auriane' ),
					'left'  => esc_html__( 'Before Text', 'auriane' ),
				],
				'conditions'  => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'new_offcanvas_button_icon',
							'operator' => '!=',
							'value'    => '',
						],
						[
							'name'     => 'offcanvas_on',
							'operator' => '==',
							'value'    => 'button',
						],
					],
				],
			]
		);

		$this->add_control(
			'offcanvas_icon_spacing',
			[
				'label'      => esc_html__( 'Icon Spacing', 'auriane' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'default'    => [
					'size' => '5',
					'unit' => 'px',
				],
				'conditions' => [
					'relation' => 'and',
					'terms'    => [
						[
							'name'     => 'new_offcanvas_button_icon',
							'operator' => '!=',
							'value'    => '',
						],
						[
							'name'     => 'offcanvas_on',
							'operator' => '==',
							'value'    => 'button',
						],
					],
				],
				'selectors'  => [
					'{{WRAPPER}} .jws-elementor-button .elementor-align-icon-right, {{WRAPPER}} .jws-infobox-link-icon-after' => 'margin-left: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .jws-elementor-button .elementor-align-icon-left, {{WRAPPER}} .jws-infobox-link-icon-before' => 'margin-right: {{SIZE}}{{UNIT}};',
				],
			]
		);



		$this->add_control(
			'offcanvas_trigger_zindex',
			[
				'label'       => esc_html__( 'Z-Index', 'auriane' ),
				'description' => esc_html__( 'Adjust the z-index of the floating trigger if it is not visibile on the page. Defaults is set to 999', 'auriane' ),
				'type'        => Controls_Manager::NUMBER,
				'default'     => '999',
				'min'         => 0,
				'step'        => 1,
				'condition'   => [
					'offcanvas_on'          => array( 'button' ),
					'jws_display_position' => 'floating',
				],
				'selectors'   => [
					'{{WRAPPER}} .jws-offcanvas-trigger' => 'z-index: {{SIZE}};',
				],
			]
		);



		$this->end_controls_section();
	}

	/**
	 * Register Off - Canvas Close button.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_close_controls() {
		$this->start_controls_section(
			'section_close',
			[
				'label' => esc_html__( 'Close Button', 'auriane' ),
			]
		);



		$this->add_control(
				'new_close_icon',
				[
					'label'            => esc_html__( 'Select Close Icon', 'auriane' ),
					'type'             => Controls_Manager::ICONS,
					'default'          => [
						'value' => 'fa fa-times',
                		'library' => 'fa-solid',
					],
				]
		);

		$this->add_control(
			'close_inside_icon_position',
			[
				'label'      => esc_html__( 'Icon Alignment', 'auriane' ),
				'type'       => Controls_Manager::SELECT,
				'default'    => 'right-top',
				'options'    => [
					'left-top'  => esc_html__( 'Left Top', 'auriane' ),
					'right-top' => esc_html__( 'Right Top', 'auriane' ),
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'new_close_icon',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'close_icon_size',
			[
				'label'      => esc_html__( 'Size', 'auriane' ),
				'type'       => Controls_Manager::SLIDER,
				'range'      => [
					'px' => [
						'max' => 60,
					],
				],
				'selectors'  => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-close .jws-offcanvas-close-icon' => 'font-size:{{SIZE}}px;',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'new_close_icon',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'esc_keypress',
			[
				'label'        => esc_html__( 'Close on ESC Keypress', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => esc_html__( 'No', 'auriane' ),
				'label_on'     => esc_html__( 'Yes', 'auriane' ),
			]
		);

		$this->add_control(
			'overlay_click',
			[
				'label'        => esc_html__( 'Close on Overlay Click', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'default'      => 'no',
				'return_value' => 'yes',
				'label_off'    => esc_html__( 'No', 'auriane' ),
				'label_on'     => esc_html__( 'Yes', 'auriane' ),
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register off-canvas button Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_button_style_controls() {
		$this->start_controls_section(
			'section_button_style',
			[
				'label'     => esc_html__( 'Button', 'auriane' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'offcanvas_on' => 'button',
				],
			]
		);

		$this->add_control(
			'btn_html_message',
			[
				'type'      => Controls_Manager::RAW_HTML,
				'raw'       => sprintf( '<p style="font-size: 11px;font-style: italic;line-height: 1.4;color: #a4afb7;">%s</p>', esc_html__( 'To see these changes please turn off the preview setting from Content Tab.', 'auriane' ) ),
				'condition' => [
					'preview_offcanvas' => 'yes',
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name'      => 'btn_typography',
				'label'     => esc_html__( 'Typography', 'auriane' ),
				
				'selector'  => '{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button',
				'condition' => [
					'offcanvas_on' => 'button',
				],
			]
		);

		$this->add_responsive_control(
			'btn_padding',
			[
				'label'      => esc_html__( 'Padding', 'auriane' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'condition'  => [
					'offcanvas_on' => 'button',
				],
			]
		);

		$this->start_controls_tabs( 'tabs_button_style' );

		$this->start_controls_tab(
			'tab_button_normal',
			[
				'label'     => esc_html__( 'Normal', 'auriane' ),
				'condition' => [
					'offcanvas_on' => 'button',
				],
			]
		);

			$this->add_control(
				'button_text_color',
				[
					'label'     => esc_html__( 'Text Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button' => 'color: {{VALUE}};',
					],
					'condition' => [
						'offcanvas_on' => 'button',
					],
				]
			);

			$this->add_control(
				'btn_background_color',
				[
					'label'     => esc_html__( 'Background Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'offcanvas_on' => 'button',
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
					'selector'    => '{{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button',
					'condition'   => [
						'offcanvas_on' => 'button',
					],
				]
			);

			$this->add_control(
				'btn_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'offcanvas_on' => 'button',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Box_Shadow::get_type(),
				[
					'name'      => 'button_box_shadow',
					'selector'  => '{{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button',
					'condition' => [
						'offcanvas_on' => 'button',
					],
				]
			);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_button_hover',
			[
				'label'     => esc_html__( 'Hover', 'auriane' ),
				'condition' => [
					'offcanvas_on' => 'button',
				],
			]
		);

			$this->add_control(
				'btn_hover_color',
				[
					'label'     => esc_html__( 'Text Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button:hover, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button:hover' => 'color: {{VALUE}};',
					],
					'condition' => [
						'offcanvas_on' => 'button',
					],
				]
			);

			$this->add_control(
				'btn_hover_bg_color',
				[
					'label'     => esc_html__( 'Background Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'default'   => '',
					'selectors' => [
						'{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button:hover, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button:hover' => 'background-color: {{VALUE}};',
					],
					'condition' => [
						'offcanvas_on' => 'button',
					],
				]
			);

			$this->add_control(
				'button_hover_border_color',
				[
					'label'     => esc_html__( 'Border Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'condition' => [
						'border_border!' => '',
					],
					'selectors' => [
						'{{WRAPPER}} .jws-offcanvas-action-wrap a.jws-elementor-button:hover, {{WRAPPER}} .jws-offcanvas-action-wrap .jws-elementor-button:hover' => 'border-color: {{VALUE}};',
					],
					'condition' => [
						'offcanvas_on' => 'button',
					],
				]
			);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register off-canvas Icon Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_icon_style_controls() {
		$this->start_controls_section(
			'section_offcanvas_icon_display_style',
			[
				'label'     => esc_html__( 'Icon', 'auriane' ),
				'tab'       => Controls_Manager::TAB_STYLE,
			
			]
		);

		$this->start_controls_tabs( 'icon_style' );

			$this->start_controls_tab(
				'icon_normal',
				[
					'label'     => esc_html__( 'Normal', 'auriane' ),
					
				]
			);
				$this->add_control(
					'icon_color_normal',
					[
						'label'     => esc_html__( 'Icon Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .elementor-button-icon i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .elementor-button-icon svg' => 'fill: {{VALUE}};',
                            '{{WRAPPER}} .elementor-button-icon path' => 'fill: {{VALUE}};stroke: {{VALUE}};',
						],
					]
				);



			$this->end_controls_tab();

			$this->start_controls_tab(
				'icon_hover',
				[
					'label'     => esc_html__( 'Hover', 'auriane' ),
				
				]
			);

				$this->add_control(
					'icon_color_hover',
					[
						'label'     => esc_html__( 'Icon Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .elementor-button-content-wrapper:hover i' => 'color: {{VALUE}};',
							'{{WRAPPER}} .elementor-button-content-wrapper:hover svg' => 'fill: {{VALUE}};',
                            '{{WRAPPER}} .elementor-button-content-wrapper:hover path' => 'fill: {{VALUE}};stroke: {{VALUE}};',
						],
					
					]
				);

				$this->add_control(
					'icon_background_color_hover',
					[
						'label'     => esc_html__( 'Background Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .jws-offcanvas-icon-bg:hover' => 'background: {{VALUE}};',
						],
					
					]
				);

			$this->end_controls_tab();
                    	$this->start_controls_tab(
				'icon_sticky',
				[
					'label' => esc_html__( 'Sticky', 'auriane' ),
				]
			);
            $this->add_control(
					'icon_color_sticky',
					[
						'label'     => esc_html__( 'Icon Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'body[data-elementor-device-mode="desktop"] .elementor-element.is-sticky .elementor-button-content-wrapper:hover i' => 'color: {{VALUE}};',
							'body[data-elementor-device-mode="desktop"] .elementor-element.is-sticky .elementor-button-content-wrapper:hover svg' => 'fill: {{VALUE}};',
                            'body[data-elementor-device-mode="desktop"] .elementor-element.is-sticky .elementor-button-content-wrapper:hover path' => 'fill: {{VALUE}};stroke: {{VALUE}};',
						],
						'condition' => [
							'offcanvas_on' => 'icon',
						],
					]
				);

				$this->add_control(
					'icon_background_color_sticky',
					[
						'label'     => esc_html__( 'Background Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'body[data-elementor-device-mode="desktop"] .elementor-element.is-sticky .jws-offcanvas-icon-bg' => 'background: {{VALUE}} !important;',
						],
						'condition' => [
							'offcanvas_on' => 'icon',
						],
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register off-canvas CLose Icon Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_close_icon_style_controls() {

		$this->start_controls_section(
			'section_close_icon_style',
			[
				'label'      => esc_html__( 'Close Icon', 'auriane' ),
				'tab'        => Controls_Manager::TAB_STYLE,
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'new_close_icon',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'close_icon_color_normal',
			[
				'label'      => esc_html__( 'Icon Color', 'auriane' ),
				'type'       => Controls_Manager::COLOR,
				'default'    => '',
				'selectors'  => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-close .jws-offcanvas-close-icon i' => 'color: {{VALUE}};',
					'.uaoffcanvas-{{ID}} .jws-offcanvas-close .jws-offcanvas-close-icon svg' => 'fill: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'new_close_icon',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_control(
			'close_icon_color_hover',
			[
				'label'      => esc_html__( 'Background Color', 'auriane' ),
				'type'       => Controls_Manager::COLOR,
				'selectors'  => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-close' => 'background-color: {{VALUE}};',
				],
				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'new_close_icon',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->add_responsive_control(
			'close_icon_padding',
			[
				'label'      => esc_html__( 'Padding', 'auriane' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-close-icon-wrapper .jws-offcanvas-close' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'conditions' => [
					'relation' => 'or',
					'terms'    => [
						[
							'name'     => 'new_close_icon',
							'operator' => '!=',
							'value'    => '',
						],
					],
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register offcanvas Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_offcanvas_style_controls() {
		$this->start_controls_section(
			'section_offcanvas_style',
			[
				'label' => esc_html__( 'Off - Canvas', 'auriane' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'offcanvas_bg_color',
			[
				'label'     => esc_html__( 'Background Color', 'auriane' ),
				'type'      => Controls_Manager::COLOR,
				'selectors' => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-content-data' => 'background-color: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'offcanvas_spacing',
			[
				'label'      => esc_html__( 'Content Padding', 'auriane' ),
				'type'       => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors'  => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-content-data' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);

		$this->end_controls_section();
	}

	/**
	 * Register Menu Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_menu_style_controls() {
		$this->start_controls_section(
			'section_menu_style',
			[
				'label'     => esc_html__( 'Menu', 'auriane' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_type' => 'menu',
				],
			]
		);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'menu_typography',					
					'selector'  => '{{WRAPPER}} .jws-offcanvas-menu',
					'condition' => [
						'content_type' => 'menu',
					],
				]
			);

			$this->add_responsive_control(
				'menu_padding',
				[
					'label'      => esc_html__( 'Padding', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'default'    => [
						'top'    => '5',
						'bottom' => '5',
						'left'   => '20',
						'right'  => '20',
						'unit'   => 'px',
					],
					'selectors'  => [
						'{{WRAPPER}} .jws-offcanvas-menu .menu-item a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'content_type' => 'menu',
					],
				]
			);

			$this->start_controls_tabs( 'tabs_style_menu_item' );

			$this->start_controls_tab(
				'tab_menu_item_normal',
				[
					'label'     => esc_html__( 'Normal', 'auriane' ),
					'condition' => [
						'content_type' => 'menu',
					],
				]
			);

				$this->add_control(
					'menu_item_color',
					[
						'label'     => esc_html__( 'Text Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'default'   => '',
						'selectors' => [
							'{{WRAPPER}} .jws-offcanvas-menu .menu-item a' => 'color: {{VALUE}}',
						],
						'condition' => [
							'content_type' => 'menu',
						],
					]
				);

			$this->end_controls_tab();

			$this->start_controls_tab(
				'tab_menu_item_hover',
				[
					'label' => esc_html__( 'Hover', 'auriane' ),
				]
			);

				$this->add_control(
					'menu_item_color_hover',
					[
						'label'     => esc_html__( 'Text Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-offcanvas-menu .menu-item a:hover' => 'color: {{VALUE}}',
						],
						'condition' => [
							'content_type' => 'menu',
						],
					]
				);

				$this->add_control(
					'menu_item_bgcolor_hover',
					[
						'label'     => esc_html__( 'Background Color', 'auriane' ),
						'type'      => Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-offcanvas-menu .menu-item a:hover' => 'background-color: {{VALUE}}',
						],
						'condition' => [
							'content_type' => 'menu',
						],
					]
				);

			$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();
	}

	/**
	 * Register offcanvas Content Style Controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_content_style_controls() {
		$this->start_controls_section(
			'section_content_style',
			[
				'label'     => esc_html__( 'Content', 'auriane' ),
				'tab'       => Controls_Manager::TAB_STYLE,
				'condition' => [
					'content_type' => 'content',
				],
			]
		);

			$this->add_control(
				'content_text_color',
				[
					'label'     => esc_html__( 'Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,					
					'selectors' => [
						'.uaoffcanvas-{{ID}} .jws-offcanvas .jws-offcanvas-content-data' => 'color: {{VALUE}};',
						'{{WRAPPER}} .jws-offcanvas .jws-offcanvas-content-data' => 'color: {{VALUE}};',
					],
					'condition' => [
						'content_type' => 'content',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'      => 'content_typography',					
					'selector'  => '.uaoffcanvas-{{ID}} .jws-offcanvas-content .jws-text-editor',
					'separator' => 'before',
					'condition' => [
						'content_type' => 'content',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name'        => 'content_border',
					'label'       => esc_html__( 'Content Border', 'auriane' ),
					'placeholder' => '1px',
					'default'     => '1px',
					'selector'    => '{{WRAPPER}} .uaoffcanvas-{{ID}} .jws-offcanvas .jws-offcanvas-content',
					'condition'   => [
						'content_type' => 'content',
					],
				]
			);

			$this->add_control(
				'content_border_radius',
				[
					'label'      => esc_html__( 'Border Radius', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .uaoffcanvas-{{ID}} .jws-offcanvas .jws-offcanvas-content' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'condition'  => [
						'content_type' => 'content',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register Off-Canvas controls.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function register_display_offcanvas_controls() {
		$this->start_controls_section(
			'section_offcanvas_controls',
			[
				'label' => esc_html__( 'Off - Canvas', 'auriane' ),
			]
		);

		$this->add_responsive_control(
			'offcanvas_width',
			[
				'label'          => esc_html__( 'Width (px)', 'auriane' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', '%' ],
				'default'        => [
					'size' => '300',
					'unit' => 'px',
				],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'selectors'      => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas .jws-offcanvas-content-data' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'offcanvas_height',
			[
				'label'          => esc_html__( 'Height (px)', 'auriane' ),
				'type'           => Controls_Manager::SLIDER,
				'size_units'     => [ 'px', '%' ],
				'range'          => [
					'px' => [
						'min' => 0,
						'max' => 2000,
					],
				],
				'selectors'      => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas .jws-offcanvas-content-data' => 'min-height: {{SIZE}}{{UNIT}};',
				],
			]
		);
		$this->add_control(
			'offcanvas_position',
			[
				'label'       => esc_html__( 'Position', 'auriane' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'at-left',
				'options'     => [
					'at-left'  => [
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon'  => 'fa fa-align-left',
					],
                    'at-center'  => [
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon'  => 'fa fa-align-center',
					],
					'at-right' => [
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon'  => 'fa fa-align-right',
					],
                    'at-top' => [
						'title' => esc_html__( 'Top', 'auriane' ),
						'icon'  => 'fa fa-arrow-up',
					],
                    'at-bottom' => [
						'title' => esc_html__( 'Bottom', 'auriane' ),
						'icon'  => 'fa fa-arrow-down',
					],
				],
				'label_block' => false,
				'toggle'      => false,
			]
		);

		$this->add_control(
			'offcanvas_type',
			[
				'label'       => esc_html__( 'Appear Effect', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'normal',
				'label_block' => false,
				'options'     => [
					'normal' => esc_html__( 'Slide', 'auriane' ),
                    'fade'   => esc_html__( 'Fade', 'auriane' ),
				],
				'render_type' => 'template',
			]
		);

		$this->add_control(
			'page_overlay',
			[
				'label'       => esc_html__( 'Overlay Color', 'auriane' ),
				'type'        => Controls_Manager::COLOR,
				'default'     => 'rgba(0,0,0,0.75)',
				'selectors'   => [
					'.uaoffcanvas-{{ID}} .jws-offcanvas-overlay' => 'background: {{VALUE}};',
				],
				'render_type' => 'template',
			]
		);

		$this->end_controls_section();
	}


	/**
	 * Render content type list.
	 *
	 * @since 1.11.0
	 * @return array Array of content type
	 * @access public
	 */
	public function get_content_type() {
		$content_type = array(
			'content'              => esc_html__( 'Content', 'auriane' ),
			'menu'                 => esc_html__( 'Menu', 'auriane' ),
			'saved_rows'           => esc_html__( 'Saved Section', 'auriane' ),
			'saved_page_templates' => esc_html__( 'Saved Page', 'auriane' ),
		);

		if ( defined( 'ELEMENTOR_PRO_VERSION' ) ) {
			$content_type['saved_modules'] = esc_html__( 'Saved Widget', 'auriane' );
		}
		return $content_type;
	}

	/**
	 * Get available menus list
	 *
	 * @since 1.11.0
	 * @return array Array of menu
	 * @access public
	 */
	public function get_menus_list() {
		$menus = wp_get_nav_menus();

		$options = [];

		foreach ( $menus as $menu ) {
			$options[ $menu->slug ] = $menu->name;
		}

		return $options;
	}

	/**
	 * Render Menu HTML.
	 *
	 * @since 1.11.0
	 * @param array $settings The settings array.
	 * @param int   $node_id The node id.
	 * @return string menu HTML
	 * @access public
	 */
	public function get_menu_html( $settings, $node_id ) {

		$menus = $this->get_menus_list();

		if ( ! empty( $menus ) ) {

			$args = [
				'echo'        => false,
				'menu'        => $settings['menu'],
				'menu_class'  => 'jws-offcanvas-menu',
				'fallback_cb' => '__return_empty_string',
				'container'   => '',
			];

			$menu_html = wp_nav_menu( $args );

			return $menu_html;
		}
	}

	/**
	 * Render Off - Canvas widget classes names.
	 *
	 * @since 1.11.0
	 * @param array $settings The settings array.
	 * @param int   $node_id The node id.
	 * @return string Concatenated string of classes
	 * @access public
	 */
	public function get_offcanvas_content( $settings, $node_id ) {
		$content_type     = $settings['content_type'];
		$dynamic_settings = $this->get_settings_for_display();

		switch ( $content_type ) {
			case 'content':
				global $wp_embed;
				return '<div class="jws-text-editor elementor-inline-editing" data-elementor-setting-key="ct_content" data-elementor-inline-editing-toolbar="advanced">' . wpautop( $wp_embed->autoembed( $dynamic_settings['ct_content'] ) ) . '</div>';
			break;

			case 'menu':
				$menu_content = $this->get_menu_html( $settings, $node_id );
				return $menu_content;
			break;

			case 'saved_rows':
				return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_saved_rows'] );
			break;

			case 'saved_modules':
				return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_saved_modules'] );

			case 'saved_page_templates':
				return \Elementor\Plugin::$instance->frontend->get_builder_content_for_display( $settings['ct_page_templates'] );
			break;

			default:
				return;
			break;
		}
	}

	/**
	 * Render Button.
	 *
	 * @since 1.11.0
	 * @param int   $node_id The node id.
	 * @param array $settings The settings array.
	 * @access public
	 */
	public function render_button( $node_id, $settings ) {
		$this->add_render_attribute( 'wrapper', 'class', 'jws-button-wrapper elementor-button-wrapper' );
        if($settings['use_login'] === 'yes') {
            $this->add_render_attribute( 'button', 'class', 'jws-open-login' );  
        if ( is_user_logged_in() || isset($_GET['p']) ) {  
               $this->add_render_attribute( 'button', 'class', 'jws-offcanvas-trigger logged jws-elementor-button' ); 
               $this->add_render_attribute( 'button', 'href', ''.$settings['btn_url_after_login']['url'].'' );
           }else {
               $this->add_render_attribute( 'button', 'class', 'jws-offcanvas-trigger action jws-elementor-button' );
               $this->add_render_attribute( 'button', 'href', 'javascript:void(0);' );
           } 
        }else {
           $this->add_render_attribute( 'button', 'class', 'jws-offcanvas-trigger action jws-elementor-button' );
           $this->add_render_attribute( 'button', 'href', 'javascript:void(0);' ); 
        }
        
		$position = '';

		if ( 'button' === $settings['offcanvas_on'] ) {
			if ( 'floating' === $settings['jws_display_position'] ) {
				$position = ' jws-offcanvas-action-alignment-' . $settings['jws_display_floating_align'];

				$this->add_render_attribute( 'button', 'class', '' . $position . '' );
			} else {
				if ( ! empty( $settings['jws_display_inline_button_align'] ) ) {
					$this->add_render_attribute( 'wrapper', 'class', 'elementor-align-' . $settings['jws_display_inline_button_align'] );
                    if(isset($settings['jws_display_inline_button_align_tablet'])) 	$this->add_render_attribute( 'wrapper', 'class', 'elementor-tablet-align-' . $settings['jws_display_inline_button_align_tablet'] ); 
				    if(isset($settings['jws_display_inline_button_align_mobile']))  $this->add_render_attribute( 'wrapper', 'class', 'elementor-mobile-align-' . $settings['jws_display_inline_button_align_mobile'] );
				}
			}
		}

		?>
		<div <?php echo ''.$this->get_render_attribute_string( 'wrapper' ); ?>>
			<a <?php echo ''.$this->get_render_attribute_string( 'button' ); ?> data-offcanvas="<?php echo esc_attr($node_id); ?>">
				<?php $this->render_button_text(); ?>
			</a>
            <?php if($settings['use_login_dropdown'] == 'yes' && is_user_logged_in()) { ?>
                <div class="account-menu-dropdown">
                   
                       <?php 
                        $user_info  = get_userdata( get_current_user_id() );
                		$user_roles = $user_info->roles;
                
                		$out = '<ul>';
                
                		foreach ( wc_get_account_menu_items() as $endpoint => $label ) {
                			$out .= '<li class="' . wc_get_account_menu_item_classes( $endpoint ) . '"><a href="' . esc_url( wc_get_account_endpoint_url( $endpoint ) ) . '"><span>' . esc_html( $label ) . '</span></a></li>';
                		}
                
                		echo ''.$out . '</ul>';
                       ?>
                  
                </div>
            <?php } ?>
		</div>
		<?php
	}

	/**
	 * Render close icon.
	 *
	 * @since 1.11.0
	 * @param string $node_id The node id.
	 * @param array  $settings The settings array.
	 * @access protected
	 */
	protected function render_close_icon( $node_id, $settings ) {

		$close_position = '';

		$close_position = 'jws-offcanvas-close-icon-position-' . $settings['close_inside_icon_position'];

		$this->add_render_attribute( 'close-wrapper', 'class', 'jws-offcanvas-close-icon-wrapper elementor-icon-wrapper elementor-clickable' );

		$this->add_render_attribute( 'close-wrapper', 'class', $close_position );

		$this->add_render_attribute(
			'close-icon',
			'class',
			'jws-offcanvas-close elementor-icon-link elementor-clickable '
		);


		if ( ! isset( $settings['close_icon'] ) && ! \Elementor\Icons_Manager::is_migration_allowed() ) {
				// add old default.
				$settings['close_icon'] = 'fa fa-close';
			}
			$has_icon = ! empty( $settings['close_icon'] );

			if ( ! $has_icon && ! empty( $settings['new_close_icon']['value'] ) ) {
				$has_icon = true;
			}

			$close_migrated = isset( $settings['__fa4_migrated']['new_close_icon'] );
			$close_is_new   = ! isset( $settings['close_icon'] ) && \Elementor\Icons_Manager::is_migration_allowed();
		
				?>
				<div <?php echo ''.$this->get_render_attribute_string( 'close-wrapper' ); ?>>
					<span <?php echo ''.$this->get_render_attribute_string( 'close-icon' ); ?>>
						<span class="jws-offcanvas-close-icon">
							<?php if ( ( $close_migrated || $close_is_new) && (!empty($settings['new_close_icon']['value'])) ) { ?>
									<?php \Elementor\Icons_Manager::render_icon( $settings['new_close_icon'], [ 'aria-hidden' => 'true' ] ); ?>
							<?php } elseif ( ! empty( $settings['close_icon'] ) ) {  ?>
								<i class="<?php echo ''.$settings['close_icon']; ?>"></i>
							<?php }else { ?>
						         <i class="jws-icon-cross"></i> 
    						<?php } ?>
						</span>
					</span>
				</div>
				<?php
		
	}

	/**
	 * Render button text.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function render_button_text() {

		$settings = $this->get_settings();

		$this->add_render_attribute( 'content-wrapper', 'class', 'elementor-button-content-wrapper' );

		$this->add_render_attribute(
			'btn-text',
			[
				'class'                                 => 'elementor-button-text elementor-inline-editing',
				'data-elementor-setting-key'            => 'btn_text',
				'data-elementor-inline-editing-toolbar' => 'none',
			]
		);

		$this->add_render_attribute(
			'icon-align',
			[
				'class' => 'elementor-button-icon elementor-align-icon-' . $settings['offcanvas_button_icon_position'],
			]
		);

		?>
		<span <?php echo ''.$this->get_render_attribute_string( 'content-wrapper' ); ?>>


				<?php if ( ! empty( $settings['new_offcanvas_button_icon'] )&&  !empty($settings['new_offcanvas_button_icon']['value']) ) { ?>
					<span <?php echo ''.$this->get_render_attribute_string( 'icon-align' ); ?>>
						<?php
						$button_icon_migrated = isset( $settings['__fa4_migrated']['new_offcanvas_button_icon'] );
						if (!empty($settings['new_offcanvas_button_icon']['value'])) {
							\Elementor\Icons_Manager::render_icon( $settings['new_offcanvas_button_icon'], [ 'aria-hidden' => 'true' ] );
						}?>
					</span>
				<?php } ?>
			<span <?php echo ''.$this->get_render_attribute_string( 'btn-text' ); ?>><?php
                    
                    if ( is_user_logged_in() || isset($_GET['p']) ) {  
                        echo ''.$this->get_settings_for_display( 'btn_text_after_login' );
                    }else {
                        echo ''.$this->get_settings_for_display( 'btn_text' );
                    }
                    
            ?></span>
		</span>
		<?php
	}

	/**
	 * Render action HTML.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function render_action_html() {
		$settings = $this->get_settings();
		$id       = $this->get_id();

		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		if ( 'button' === $settings['offcanvas_on'] ) {
			$this->render_button( $id, $settings );
			if ( ( 'floating' === $settings['jws_display_position'] ) && $is_editor ) {
				?>
				<div class="jws-builder-msg" style="text-align: center;">
					<h5><?php esc_html_e( 'Off - Canvas - ID ', 'auriane' ); ?><?php echo esc_attr($id); ?></h5>
					<p><?php esc_html_e( 'Click here to edit the "Off- Canvas" settings. This text will not be visible on frontend.', 'auriane' ); ?></p>
				</div>

				<?php
			}
		} elseif ( 
			$is_editor
		) {
			?>
			<div class="jws-builder-msg" style="text-align: center;">
				<h5><?php esc_html_e( 'Off - Canvas - ID ', 'auriane' ); ?><?php echo esc_attr($id); ?></h5>
				<p><?php esc_html_e( 'Click here to edit the "Off- Canvas" settings. This text will not be visible on frontend.', 'auriane' ); ?></p>
			</div>
			<?php
		}
	}

	/**
	 * Close Render action HTML.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function close_render_action_html() {
		$settings = $this->get_settings_for_display();
		$id       = $this->get_id();

		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		if ( ! empty( $settings['close_icon'] ) || ! empty( $settings['new_close_icon'] ) ) {
			$this->render_close_icon( $id, $settings );
		}
	}

	/**
	 * Get Data Attributes.
	 *
	 * @since 1.11.0
	 * @param array $settings The settings array.
	 * @return string Data Attributes
	 * @access public
	 */
	public function get_parent_wrapper_attributes( $settings ) {
		$id = $this->get_id();
		$this->add_render_attribute(
			'parent-wrapper',
			[
                   
				'id'                    => $id . '-overlay',
				'data-trigger-on'       => $settings['offcanvas_on'],

				'data-close-on-overlay' => $settings['overlay_click'],

				'data-close-on-esc'     => $settings['esc_keypress'],

				'data-content'          => $settings['content_type'],



				'data-canvas-width'     => $settings['offcanvas_width']['size'],
                'data-canvas-width-tablet'     => isset($settings['offcanvas_width_tablet']['size']) ? $settings['offcanvas_width_tablet']['size'] : $settings['offcanvas_width']['size'],
                'data-canvas-width-mobile'     => isset($settings['offcanvas_width_mobile']['size']) ? $settings['offcanvas_width_mobile']['size'] : $settings['offcanvas_width']['size'],
                'data-canvas-unit'     => $settings['offcanvas_width']['unit'],
                'data-canvas-unit-tablet'     => isset($settings['offcanvas_width_tablet']['unit']) ? $settings['offcanvas_width_tablet']['unit'] : $settings['offcanvas_width']['unit'],
                'data-canvas-unit-mobile'     => isset($settings['offcanvas_width_mobile']['unit']) ? $settings['offcanvas_width_mobile']['unit'] : $settings['offcanvas_width']['unit'],
                'data-id' => $id, 
			]
		);

		$this->add_render_attribute(
			'parent-wrapper',
			'class',
			[
				'jws-offcanvas-parent-wrapper',
				'jws-module-content',
				'uaoffcanvas-' . $id,
			]
		);

		return $this->get_render_attribute_string( 'parent-wrapper' );
	}

	/**
	 *  Get Saved Widgets
	 *
	 *  @param string $type Type.
	 *  @since 1.11.0
	 *  @return string
	 */
	public function get_saved_data( $type = 'page' ) {
		$saved_widgets = $this->get_post_template( $type );
		$options[-1]   = esc_html__( 'Select', 'auriane' );
		if ( count( $saved_widgets ) ) {
			foreach ( $saved_widgets as $saved_row ) {
				$options[ $saved_row['id'] ] = $saved_row['name'];
			}
		} else {
			$options['no_template'] = esc_html__( 'It seems that, you have not saved any template yet.', 'auriane' );
		}
		return $options;
	}

	/**
	 *  Get Templates based on category
	 *
	 *  @param string $type Type.
	 *  @since 1.11.0
	 *  @return string
	 */
	public function get_post_template( $type = 'page' ) {
		$posts = get_posts(
			array(
				'post_type'      => 'elementor_library',
				'orderby'        => 'title',
				'order'          => 'ASC',
				'posts_per_page' => '-1',
				'tax_query'      => array(
					array(
						'taxonomy' => 'elementor_library_type',
						'field'    => 'slug',
						'terms'    => $type,
					),
				),
			)
		);

		$templates = array();

		foreach ( $posts as $post ) {
			$templates[] = array(
				'id'   => $post->ID,
				'name' => $post->post_title,
			);
		}

		return $templates;
	}

	/**
	 * Render offcanvas output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function render() {
		$settings  = $this->get_settings();
        if($settings['animation_offcanvas'] == 'yes') {
            wp_enqueue_script('anime');
        }
		$node_id   = $this->get_id();
		$is_editor = \Elementor\Plugin::instance()->editor->is_edit_mode();

		$class = ( $settings['preview_offcanvas'] == 'yes' ) ? 'jws-show-preview' : '';
        $class .= ( $settings['animation_offcanvas'] == 'yes' ) ? 'jws-show-animation' : '';
        

		$shadowclass = ( '' !== $settings['page_overlay'] ) ? 'jws-offcanvas-shadow-inset' : 'jws-offcanvas-shadow-normal';

		$editor_mode_class = ( $is_editor ) ? 'jws-editor-mode' : '';

		$this->add_inline_editing_attributes( 'btn_text', 'none' );
		$this->add_inline_editing_attributes( 'ct_content', 'advanced' );

		$this->add_render_attribute( 'inner-wrapper', 'id', 'offcanvas-' . $node_id );

		$this->add_render_attribute(
			'inner-wrapper',
			'class',
			[
				'jws-offcanvas',
				'jws-custom-offcanvas',
				$class,
				$editor_mode_class,
				'jws-offcanvas-type-' . $settings['offcanvas_type'],
				$shadowclass,
			]
		);

		$this->add_render_attribute( 'inner-wrapper', 'class', 'position-' . $settings['offcanvas_position'] );
        if($settings['use_login'] != 'yes') { 
		?>
		<div <?php echo ''.$this->get_parent_wrapper_attributes( $settings ); ?> >
			<div <?php echo ''.$this->get_render_attribute_string( 'inner-wrapper' ); ?>>
				<div class="jws-offcanvas-content">
					<div class="jws-offcanvas-text jws-offcanvas-content-data">
                        <div class="jws-offcanvas-action-wrap">
						  <?php echo ''.$this->close_render_action_html(); ?>
					    </div>
						<?php echo do_shortcode( $this->get_offcanvas_content( $settings, $node_id ) ); ?>
					</div>
				</div>
			</div>
			<div class="jws-offcanvas-overlay elementor-clickable"></div>
		</div>
        <?php } ?>
		<div class="jws-offcanvas-action-wrap">
			<?php echo ''.$this->render_action_html(); ?>
		</div>
		<?php
	}

	/**
	 * Rend offcanvas output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.11.0
	 * @access protected
	 */
	protected function content_template() {}
}
