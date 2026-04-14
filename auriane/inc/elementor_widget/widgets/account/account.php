<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Account extends Widget_Base {

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
		return 'jws_account';
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
		return esc_html__( 'Jws Account Popup', 'auriane' );
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
		return 'eicon-lock-user';
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
			'section_account_setting',
			[
				'label' => esc_html__( 'Toggle', 'auriane' ),
			]
		);

        $this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Icon', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
		);
		$this->add_control(
            'show_text',
            [
                'label'         => esc_html__( 'Show Text(Name User)', 'auriane' ),
                'type'          => Controls_Manager::SWITCHER,
                'label_on'      => esc_html__( 'Yes', 'auriane' ),
                'label_off'     => esc_html__( 'No', 'auriane' ),
                'default'   => 'yes',
            ]
        );
        $this->add_control(
			'text_position',
			[
				'label' => esc_html__( 'Text Position', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'right',
				'options' => [
					'left'  => esc_html__( 'Left', 'auriane' ),
					'right' => esc_html__( 'right', 'auriane' ),
				],
			]
		);
        $this->add_control(
			'text',
			[
				'label' => esc_html__( 'Text', 'auriane' ),
				'type' =>  Controls_Manager::TEXT,
				'default' => esc_html__( 'Login / Register', 'auriane' ),
                'condition'	=> [
						'show_text' => 'yes',
				],
			]
          
		);
        $this->add_control(
			'text_after_login',
			[
				'label' => esc_html__( 'Text After Login', 'auriane' ),
				'type' =>  Controls_Manager::TEXT,
				'default' => esc_html__( 'My Accounts', 'auriane' ),
                'condition'	=> [
						'show_text' => 'yes',
				],
			]
          
		);
        $this->add_control(
			'ac_link',
			[
				'label' => esc_html__( 'Link', 'auriane' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'Login / Register Url', 'auriane' ),
                'dynamic'		=> [ 'active' => true ],
		
			]
		);
        $this->add_control(
			'ac_link_2',
			[
				'label' => esc_html__( 'My Acccount When Logged', 'auriane' ),
				'type' => Controls_Manager::URL,
				'placeholder' => esc_html__( 'My Account Url', 'auriane' ),
                'dynamic'		=> [ 'active' => true ],
		
			]
		);

		$this->end_controls_section();
  
		$this->start_controls_section(
			'toggle_style',
			[
				'label' => esc_html__( 'Toggle Style', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
				'align',
				[
					'label' 		=> esc_html__( 'Align', 'auriane' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> 'left',
					'options' 		=> [
						'left'    		=> [
							'title' 	=> esc_html__( 'Left', 'auriane' ),
							'icon' 		=> 'eicon-h-align-left',
						],
						'center' 		=> [
							'title' 	=> esc_html__( 'Center', 'auriane' ),
							'icon' 		=> 'eicon-h-align-center',
						],
						'right' 		=> [
							'title' 	=> esc_html__( 'Right', 'auriane' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
                    'selectors' => [
							'{{WRAPPER}} .jws_account' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
         $this->add_control(
					'icon_color',
					[
						'label' 	=> esc_html__( 'Icon Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_a_icon' => 'color: {{VALUE}};',
						],
					]
		);
        $this->add_control(
				'icon_size',
				[
					'label' 		=> esc_html__( 'Icon Size', 'auriane' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 1,
							'max' => 100,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws_a_icon' => 'font-size: {{SIZE}}px;',
					],
				]
		);
        $this->add_responsive_control(
					'icon_margin',
					[
						'label' 		=> esc_html__( 'Icon Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_account a .jws_a_icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_control(
					'text_color',
					[
						'label' 	=> esc_html__( 'Text Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_account_text' => 'color: {{VALUE}};',
						],
                        'condition'	=> [
						'show_text' => 'yes',
				        ],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'content_typography',
				'label' => esc_html__( 'Typography', 'auriane'),
				
				'selector' => '{{WRAPPER}} .jws_account_text',
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'popup_style',
			[
				'label' => esc_html__( 'Popup Style', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_group_control(
			Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background Overlay', 'auriane' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} .jws_account_overlay',
			]
		);
		$this->end_controls_section();
	}

	/**
	 * Render the widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();
        $show_text = $settings['show_text'];
        $text = $settings['text'];
        $text_after_login = $settings['text_after_login'];
        $url = $settings['ac_link']['url'];
        $target = $settings['ac_link']['is_external'] ? ' target="_blank"' : '';
		$nofollow = $settings['ac_link']['nofollow'] ? ' rel="nofollow"' : '';   
        
        $url2 = $settings['ac_link_2']['url'];
        $target2 = $settings['ac_link_2']['is_external'] ? ' target="_blank"' : '';
		$nofollow2 = $settings['ac_link_2']['nofollow'] ? ' rel="nofollow"' : '';  
            
        include( 'content.php' );

	}

	/**
	 * Render the widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 1.0.0
	 *
	 * @access protected
	 */
	protected function content_template() {}
}