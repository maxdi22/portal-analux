<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
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
class Video_popup extends Widget_Base {

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
		return 'jws_video_popup';
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
		return esc_html__( 'Jws Video Popup', 'auriane' );
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
		return 'eicon-video-camera';
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
     * Load style
     */
    public function get_style_depends()
    {
        return ['lightgallery'];
    }
    /**
     * Retrieve the list of scripts the image carousel widget depended on.
     *
     * Used to set scripts dependencies required to run the widget.
     *
     * @since 1.3.0
     * @access public
     *
     * @return array Widget scripts dependencies.
     */
    public function get_script_depends()
    {
        return ['lightgallery-all'];
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
			'section_video_popup_setting',
			[
				'label' => esc_html__( 'Setting', 'auriane' ),
			]
		);
        $this->add_control(
				'skins',
				[
					'label'     => esc_html__( 'Skins', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'overlay',
					'options'   => [
                        'overlay'   => esc_html__( 'Overlay', 'auriane' ),
						'border_ani'   => esc_html__( 'Border Animation', 'auriane' ),
                        'border'   => esc_html__( 'Border', 'auriane' ),
					],
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
			'url',
			[
				'label' => esc_html__( 'URl Video', 'auriane' ),
				'type' =>  Controls_Manager::TEXT,
				'default' => esc_html__( 'https://www.youtube.com/watch?v=JtVd7q25FDA', 'auriane' ),
			]
          
		);
        $this->add_responsive_control(
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
							'{{WRAPPER}} .jws_video_popup' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
		$this->end_controls_section();
        $this->start_controls_section(
			'section_style',
			[
				'label' => esc_html__( 'Toggle Style', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->start_controls_tabs( 'tabs_video_style' );

		$this->start_controls_tab(
			'tab_video_normal',
			[
				'label' => esc_html__( 'Normal', 'auriane' ),
			]
		);
        $this->add_control(
					'icon_color',
					[
						'label' 	=> esc_html__( 'Icon Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '#ffffff',
						'selectors' => [
							'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a' => 'color: {{VALUE}};',
						],
					]
		);
        
        $this->add_control(
			'icon_type',
			[
				'label' => esc_html__( 'Icon Background Type', 'auriane' ),
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
			'icon_bg_color',
			[
				'label' => esc_html__( 'Background Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a span' => 'background:{{VALUE}};',
				],
				'default' => '',
                'condition' => [
					'icon_type' => 'classic',
				],
			]
		);
        $this->add_control(
			'icon_bggradient_color',
			[
				'label' => esc_html__( 'Background Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
                'condition' => [
					'icon_type' => 'gradient',
				],
			]
		);
        $this->add_control(
			'icon_bggradient_color2',
			[
				'label' => esc_html__( 'Background Second Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a span' => 'background-image: linear-gradient(to right, {{icon_bggradient_color.VALUE}} 0%, {{VALUE}} 51%, {{icon_bggradient_color.VALUE}} 100%);background-size: 200% auto;',
                    '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:hover span' => 'background-position: right center;',
				],
                'condition' => [
					'icon_type' => 'gradient',
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
						'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a span' => 'font-size: {{SIZE}}px;',
					],
				]
		);
        $this->add_control(
				'icon_width_height',
				[
					'label' 		=> esc_html__( 'Icon Width Height', 'auriane' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 40,
							'max' => 200,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a span' => 'height: {{SIZE}}px;line-height: {{SIZE}}px;width: {{SIZE}}px;',
                        '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:before' => 'height: calc({{SIZE}}px + 30px); width: calc({{SIZE}}px + 30px);',
                        '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:after' => 'height: calc({{SIZE}}px + 30px); width: calc({{SIZE}}px + 30px);',
					],
                    'condition' => [
						 'skins' => 'overlay',
				    ],
				]
		);
        $this->add_control(
				'icon_width_height_border_ani',
				[
					'label' 		=> esc_html__( 'Icon Width Height', 'auriane' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 40,
							'max' => 200,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a span' => 'height: {{SIZE}}px;line-height: {{SIZE}}px;width: {{SIZE}}px;',
                        '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:before' => 'height: calc({{SIZE}}px + 10px); width: calc({{SIZE}}px + 10px);',
                        '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:after' => 'height: calc({{SIZE}}px + 10px); width: calc({{SIZE}}px + 10px);',
					],
                    'condition' => [
						 'skins' => 'border_ani',
				    ],
				]
		);
        $this->add_control(
				'icon_width_height_border',
				[
					'label' 		=> esc_html__( 'Icon Width Height', 'auriane' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 40,
							'max' => 200,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a span' => 'height: {{SIZE}}px;line-height: {{SIZE}}px;width: {{SIZE}}px;',
                        '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:before' => 'height: calc({{SIZE}}px + 20px); width: calc({{SIZE}}px + 20px);',
					],
                    'condition' => [
						 'skins' => 'border',
				    ],
				]
		);

        $this->add_control(
					'icon_bgcolor2',
					[
						'label' 	=> esc_html__( 'Icon Background Overlay Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:before , {{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:after' => 'background: {{VALUE}};',
						],
                        'condition' => [
						  'skins' => 'overlay',
				        ],
					]
		);
        $this->add_control(
					'icon_border_ani_color',
					[
						'label' 	=> esc_html__( 'Icon Border Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_video_popup.video-border .jws_video_popup_inner a:before' => 'border-color: {{VALUE}};',
						],
                        'condition' => [
						  'skins' => 'border',
				        ],
					]
		);
        $this->add_control(
					'icon_border_ani_color2',
					[
						'label' 	=> esc_html__( 'Icon Border Color Animation', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws_video_popup.video-border .jws_video_popup_inner a:before' => 'border-color: {{VALUE}};',
						],
                        'condition' => [
						  'skins' => 'border',
				        ],
					]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(), [
				'name' => 'icon_border',
				'selector' => '{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a .video_icon',
			]
		);
        
        
        $this->end_controls_tab();

		$this->start_controls_tab(
			'tab_video_hover',
			[
				'label' => esc_html__( 'Hover', 'auriane' ),
			]
		);
        
      
       $this->add_control(
					'icon_color_hover',
					[
						'label' 	=> esc_html__( 'Icon Color Hover', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default' 	=> '#ed2121',
						'selectors' => [
							'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:hover' => 'color: {{VALUE}};',
						],
					]
		);
        
        $this->add_control(
			'icon_bg_color_hover',
			[
				'label' => esc_html__( 'Background Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
                'selectors' => [
					'{{WRAPPER}} .jws_video_popup .jws_video_popup_inner a:hover span' => 'background:{{VALUE}};',
				],
				'default' => '',
                'condition' => [
					'icon_type' => 'classic',
				],
			]
		);
        
        $this->end_controls_tab();

		$this->end_controls_tabs();
        
         
    
        
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
		$settings = $this->get_settings();

        $url = $settings['url'];
       
     
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