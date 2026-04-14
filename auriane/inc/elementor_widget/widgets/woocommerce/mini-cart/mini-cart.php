<?php
namespace Elementor;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Background;
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

class Menu_Cart extends Widget_Base {

	/**
	 * Retrieve the widget name.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jws_mini_cart';
	}

	/**
	 * Retrieve the widget title.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Jws Mini cart', 'auriane' );
	}

	/**
	 * Retrieve the widget icon.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-cart';
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
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'jws-mini-cart'];
	}
    
	/**
	 * Register the widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */

	protected function register_controls() {
		$this->start_controls_section(
			'section_menu_icon_content',
			[
				'label' => esc_html__( 'Menu Icon', 'auriane' ),
			]
		);

        $this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Icon', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fab fa-wordpress',
                		'library' => 'fa-brands',
					],
				]
		);
        $this->add_control(
			'show_count',
			[
				'label' => esc_html__( 'Show Count', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'show_price',
			[
				'label' => esc_html__( 'Show Price', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
		$this->add_responsive_control(
			'alignment',
			[
				'label' => esc_html__( 'Alignment', 'auriane' ),
				'type' => Controls_Manager::CHOOSE,
				'label_block' => false,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon' => 'fa fa-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon' => 'fa fa-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon' => 'fa fa-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart .jws-cart-nav' => 'text-align: {{VALUE}}',
				],
			]
		);

		$this->end_controls_section();
		$this->start_controls_section(
			'section_toggle_style',
			[
				'label' => esc_html__( 'Menu Icon', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
      
		$this->start_controls_tabs( 'toggle_button_colors' );

		$this->start_controls_tab( 'toggle_button_normal_colors', [ 'label' => esc_html__( 'Normal', 'auriane' ) ] );

		$this->add_control(
			'toggle_button_text_color',
			[
				'label' => esc_html__( 'Text Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .cart_text ,{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_price_total' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart a .cart_icon' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();

		$this->start_controls_tab( 'toggle_button_hover_colors', [ 'label' => esc_html__( 'Hover', 'auriane' ) ] );
        
		$this->add_control(
			'toggle_button_hover_text_color',
			[
				'label' => esc_html__( 'Text Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart a:hover' => 'color: {{VALUE}}',
				],
			]
		);

		$this->add_control(
			'toggle_button_hover_icon_color',
			[
				'label' => esc_html__( 'Icon Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > a:hover .cart_icon' => 'color: {{VALUE}}',
				],
			]
		);


		$this->end_controls_tab();

		$this->end_controls_tabs();

		

		$this->add_control(
			'heading_icon_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Icon', 'auriane' ),
				'separator' => 'before',
			]
		);

		$this->add_control(
			'toggle_icon_size',
			[
				'label' => esc_html__( 'Size', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 50,
					],
				],
				'size_units' => [ 'px', 'em' ],
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart a span.cart_icon' => 'font-size: {{SIZE}}{{UNIT}}',
				],
			]
		);
        $this->add_control(
			'heading_text_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Text', 'auriane' ),
				'separator' => 'before',
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cart_text_typography',
				
				'selector' => '{{WRAPPER}} .jws_mini_cart a > span:not(.cart_icon)',
			]
		);
        $this->add_control(
			'count_style',
			[
				'type' => Controls_Manager::HEADING,
				'label' => esc_html__( 'Count', 'auriane' ),
				'separator' => 'before',
			]
		);
        $this->add_control(
			'count_color',
			[
				'label' => esc_html__( 'Count Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_cart_count' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'count_bgcolor',
			[
				'label' => esc_html__( 'Count Background', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_cart_count' => 'background: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'count_typography',
				
				'selector' => '{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_cart_count',
			]
		);
        $this->add_control(
			'count_wh',
			[
				'label' => esc_html__( 'Count Width,Height', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 10,
						'max' => 50,
					],
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_cart_count' => 'width: {{SIZE}}px;height: {{SIZE}}px;line-height: {{SIZE}}px',
				],
			]
		);
        $this->add_control(
			'count_x',
			[
				'label' => esc_html__( 'X position', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_cart_count' => 'left: {{SIZE}}px;',
				],
			]
		);
        $this->add_control(
			'count_y',
			[
				'label' => esc_html__( 'Y position', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'size_units' => [ 'px'],
				'selectors' => [
					'{{WRAPPER}} .jws_mini_cart > .jws-cart-nav a .jws_cart_count' => 'top: {{SIZE}}px;',
				],
			]
		);
		$this->end_controls_section();


	   $this->start_controls_section(
			'section_style_emtry',
			[
				'label' => esc_html__( 'Content Emtry', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'emtry_icon',
			[
				'label' => esc_html__( 'Icon Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#cart-{{ID}} .jws_cart_content .jws-cart-panel.jws-cart-panel-empty .jws-cart-panel-list-wrap .cart_list li .flaticon-shopping-bag' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_responsive_control(
			'emtry_icon_size',
			[
				'label' => esc_html__( 'Icon Size', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
                'size_units' => [ 'px'],
				'range' => [
					'px' => [
						'min' => 14,
						'max' => 100,
					],
				],
				'selectors' => [
					'#cart-{{ID}} .jws_cart_content .jws-cart-panel.jws-cart-panel-empty .jws-cart-panel-list-wrap .cart_list li span:before' => 'font-size: {{SIZE}}{{UNIT}};line-height: {{SIZE}}{{UNIT}}',
				],
			]
		);
        $this->add_control(
			'emtry_text',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'selectors' => [
					'#cart-{{ID}} .jws_cart_content .jws-cart-panel.jws-cart-panel-empty .jws-cart-panel-list-wrap .cart_list li' => 'color: {{VALUE}}',

				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'emtry_typography',
				'selector' => '#cart-{{ID}} .jws_cart_content .jws-cart-panel.jws-cart-panel-empty .jws-cart-panel-list-wrap .cart_list li',
			]
		);
        $this->end_controls_section();
	}

	protected function render() {
	    $settings = $this->get_settings();

		if (class_exists('Woocommerce')) { wp_enqueue_script( 'wc-cart-fragments' ); include( 'content.php' ); }
	}

	protected function content_template() {}
}
