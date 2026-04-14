<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Jws_Slider extends Widget_Base {

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
		return 'jws_slider';
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
		return esc_html__( 'Jws slider', 'auriane' );
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
		return 'eicon-slider-grid';
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
 
    public function get_tabs_list() { 
        
        global $jws_option;
        
        
        if(isset($jws_option['slider_category']) && !empty($jws_option['slider_category'])) {
          
    
      
            $tabsok = array();
            foreach (  $jws_option['slider_category'] as $index => $item_tabs ) { 
              $tabsok[ preg_replace('/[^a-zA-Z]+/', '', $item_tabs) ] = $item_tabs;     
           
            };  
            return $tabsok;
        }
        
    
    }
    /**
     * Load style
     */
    public function get_style_depends()
    {
        return [''];
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
        return [''];
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
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        

        $this->add_control(
			'slider_layout',
			[
				'label' => esc_html__( 'Layout', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout1',
				'options' => [
					'layout1'  => esc_html__( 'Layout 1', 'auriane' ),
                    'layout2'  => esc_html__( 'Layout Demo', 'auriane' ),
				],
			]
		);

        $this->end_controls_section(); 
 
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'slider List', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   
        $repeater = new \Elementor\Repeater();
        
        
        $repeater->start_controls_tabs(
        	'style_tabs'
        );
        
        $repeater->start_controls_tab(
        	'style_background_tab',
        	[
        		'label' => esc_html__( 'Background', 'auriane' ),
        	]
        );
        
        
        $repeater->add_group_control(
			\Elementor\Group_Control_Background::get_type(),
			[
				'name' => 'background',
				'label' => esc_html__( 'Background', 'auriane' ),
				'types' => [ 'classic', 'gradient', 'video' ],
				'selector' => '{{WRAPPER}} {{CURRENT_ITEM}}',
			]
		);
        
        
        $repeater->end_controls_tab();
        
        $repeater->start_controls_tab(
        	'style_content_tab',
        	[
        		'label' => esc_html__( 'Content', 'auriane' ),
        	]
        );
        
        
        $repeater->add_control(
			'slider_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title', 'auriane' ),
			]
		);
        
        $repeater->add_control(
			'slider_description',
			[
				'label' => esc_html__( 'Content', 'auriane' ),
				'type' => \Elementor\Controls_Manager::WYSIWYG,
				'default' => esc_html__( 'Default description', 'auriane' ),
				'placeholder' => esc_html__( 'Type your description here', 'auriane' ),
			]
		);
        
        $repeater->add_control(
			'slider_button',
			[
				'label' => esc_html__( 'Button', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Button Text', 'auriane' ),
			]
		);
        
        $repeater->add_control(
			'slider_button_url',
			[
				'label' => esc_html__( 'Button Url', 'auriane' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'auriane' ),
			]
		);

        
        
        $repeater->end_controls_tab();
        
         $repeater->start_controls_tab(
        	'style_mix_content_tab',
        	[
        		'label' => esc_html__( 'Mix Content', 'auriane' ),
        	]
        );
        
        
        $repeater->add_control(
			'mix-image',
			[
				'label' => esc_html__( 'Choose Image', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
        
        $repeater->add_control(
			'mix-image2',
			[
				'label' => esc_html__( 'Choose Image', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
        
        $repeater->end_controls_tab();
        

        $repeater->end_controls_tabs();

        
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Menu List', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'setting_navigation',
			[
				'label' => esc_html__( 'Setting Navigation', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
    				'enable_nav',
    				[
    					'label'        => esc_html__( 'Enable Nav', 'auriane' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'auriane' ),
    					'label_off'    => esc_html__( 'No', 'auriane' ),
    					'return_value' => 'yes',
    					'default'      => 'yes',
    					'description'  => esc_html__( 'Enable nav arrow.', 'auriane' ),
    				]
    	);
        $this->add_control(
    				'enable_dots',
    				[
    					'label'        => esc_html__( 'Enable Dots', 'auriane' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'auriane' ),
    					'label_off'    => esc_html__( 'No', 'auriane' ),
    					'return_value' => 'yes',
    					'default'      => 'yes',
    					'description'  => esc_html__( 'Enable dot.', 'auriane' ),
    				]
    	);

        $this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws_slider .flickity-page-dots li.is-selected' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .jws_slider_element .jws_slider .flickity-page-dots li:before' => 'background: {{VALUE}}',
				],
			]
		);
        $this->end_controls_section();  

        	$this->start_controls_section(
			'section_slider_options',
			[
				'label'     => esc_html__( 'Slider Options', 'auriane' ),
				'type'      => Controls_Manager::SECTION,
			]
		);


		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'          => esc_html__( 'posts to Show', 'auriane' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'          => esc_html__( 'posts to Scroll', 'auriane' ),
				'type'           => Controls_Manager::NUMBER,
				'default'        => 1,
				'tablet_default' => 1,
				'mobile_default' => 1,
			]
		);

		$this->add_control(
			'autoplay',
			[
				'label'        => esc_html__( 'Autoplay', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
		$this->add_control(
			'autoplay_speed',
			[
				'label'     => esc_html__( 'Autoplay Speed', 'auriane' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 5000,
				'selectors' => [
					'{{WRAPPER}} .slick-slide-bg' => 'animation-duration: calc({{VALUE}}ms*1.2); transition-duration: calc({{VALUE}}ms)',
				],
				'condition' => [
					'autoplay'             => 'yes',
				],
			]
		);
		$this->add_control(
			'pause_on_hover',
			[
				'label'        => esc_html__( 'Pause on Hover', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
				'condition'    => [
					'autoplay'             => 'yes',
				],
			]
		);

		$this->add_control(
			'infinite',
			[
				'label'        => esc_html__( 'Infinite Loop', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => 'yes',
			]
		);
        
        $this->add_control(
			'center',
			[
				'label'        => esc_html__( 'Cener Mode', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
        
       
        $this->add_control(
			'variablewidth',
			[
				'label'        => esc_html__( 'variable Width', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);

		$this->add_control(
			'transition_speed',
			[
				'label'     => esc_html__( 'Transition Speed (ms)', 'auriane' ),
				'type'      => Controls_Manager::NUMBER,
				'default'   => 500,
			]
		);
		$this->end_controls_section();
        
        $this->start_controls_section(
			'slides_style',
			[
				'label' => esc_html__( 'Slides', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'slider_content_position',
			[
				'label' => esc_html__( 'Position', 'auriane' ),
				'type' => \Elementor\Controls_Manager::CHOOSE,
				'options' => [
					'top' => [
						'title' => esc_html__( 'top', 'auriane' ),
						'icon' => 'eicon-v-align-top',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon' => 'eicon-h-align-center',
					],
					'bottom' => [
						'title' => esc_html__( 'Bottom', 'auriane' ),
						'icon' => 'eicon-v-align-bottom',
					],
				],
				'default' => 'center',
				'toggle' => true,
			]
		);
        
        
          $this->add_responsive_control(
			'slider_width',
			[
				'label' => esc_html__( 'Slider Width', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .slider-content-wraps' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
         $this->add_responsive_control(
			'slider_height',
			[
				'label' => esc_html__( 'Slider Height', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 10000,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'default' => [
					'unit' => 'px',
					'size' => 500,
				],
				'selectors' => [
					'{{WRAPPER}} .jws_slider_element .jws_slider_item' => 'height: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
         $this->add_responsive_control(
			'slider_content_padding',
			[
				'label' 		=> esc_html__( 'Slider Content Padding', 'auriane' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .slider-content-wrap' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);
        $this->add_responsive_control(
			'slider_content_margin',
			[
				'label' 		=> esc_html__( 'Slider Content Margin', 'auriane' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .slider-content-wrap' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);
        
        
        $this->end_controls_section();
        
        $this->start_controls_section(
			'title_style',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
         $this->add_control(
			'title_color',
			[
				'label' => esc_html__( 'Title Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .slider-title' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .slider-title',
			]
		);
        
        $this->add_responsive_control(
			'title-width',
			[
				'label' => esc_html__( 'Width', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-title' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'title_margin',
			[
				'label' 		=> esc_html__( 'Margin', 'auriane' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .slider-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section(
			'description_style',
			[
				'label' => esc_html__( 'Description', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
         $this->add_control(
			'description_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .slider-description' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'description_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .slider-description',
			]
		);
        
        $this->add_responsive_control(
			'description-width',
			[
				'label' => esc_html__( 'Width', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-description' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->add_responsive_control(
			'description_margin',
			[
				'label' 		=> esc_html__( 'Margin', 'auriane' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .slider-description' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
        $this->start_controls_section(
			'button_style',
			[
				'label' => esc_html__( 'Button', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
         $this->add_control(
			'button_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .slider-button' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'button_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .slider-button',
			]
		);

        $this->add_responsive_control(
			'button_margin',
			[
				'label' 		=> esc_html__( 'Margin', 'auriane' ),
				'type' 			=> Controls_Manager::DIMENSIONS,
				'size_units' 	=> [ 'px', 'em', '%' ],
				'selectors' 	=> [
					'{{WRAPPER}} .slider-button' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],

				'separator' => 'before',
			]
		);

        $this->end_controls_section();
        
        
        $this->start_controls_section(
			'mix_content_style',
			[
				'label' => esc_html__( 'Mix Content', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->start_controls_tabs(
        	'min_content_tabs'
        );
        
        $this->start_controls_tab(
        	'style_image1_tab',
        	[
        		'label' => esc_html__( 'Image 1', 'auriane' ),
        	]
        );
         $this->add_responsive_control(
			'mix-image-width',
			[
				'label' => esc_html__( 'Width', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-1 img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
             
        $this->add_responsive_control(
			'mix-image-position-left',
			[
				'label' => esc_html__( 'Left', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-1' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image-position-right',
			[
				'label' => esc_html__( 'Right', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-1' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image-position-top',
			[
				'label' => esc_html__( 'Top', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-1' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image-position-bottom',
			[
				'label' => esc_html__( 'Bottom', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-1' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
        $this->start_controls_tab(
        	'style_image2_tab',
        	[
        		'label' => esc_html__( 'Image 2', 'auriane' ),
        	]
        );
         $this->add_responsive_control(
			'mix-image2-width',
			[
				'label' => esc_html__( 'Width', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-2 img' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        
        $this->add_responsive_control(
			'mix-image2-position-left',
			[
				'label' => esc_html__( 'Left', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-2' => 'left: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image2-position-right',
			[
				'label' => esc_html__( 'Right', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-2' => 'right: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image2-position-top',
			[
				'label' => esc_html__( 'Top', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-2' => 'top: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'mix-image2-position-bottom',
			[
				'label' => esc_html__( 'Bottom', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-mix-2' => 'bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_tab();
        
           $this->start_controls_tab(
        	'style_slider_dots_tab',
        	[
        		'label' => esc_html__( 'Dots Slider', 'auriane' ),
        	]
        );
         $this->add_responsive_control(
			'slider-dots-position',
			[
				'label' => esc_html__( 'Position', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' , 'vh' ],
				'range' => [
					'px' => [
						'min' => 0,
						'max' => 1920,
						'step' => 1,
					],
					'%' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .slider-dots-box' => 'left: {{SIZE}}{{UNIT}};',
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
	   
		$settings = $this->get_settings_for_display();

          $class_column = ' jws_slider_item';
    
          $class_row = 'jws_slider slider '.$settings['slider_layout']; 
          $class_row .= ' slider_content_position-'.$settings['slider_content_position'];  

          
          
          

         
        $class_row .= ' jws-slider';
        $class_column .= ' slider-item slick-slide'; 
        $dots = ($settings['enable_dots'] == 'yes') ? 'true' : 'false';
        $arrows = ($settings['enable_nav'] == 'yes') ? 'true' : 'false';
        $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
        $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
        $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
        $variableWidth = ($settings['variablewidth'] == 'yes') ? 'true' : 'false';
        $center = ($settings['center'] == 'yes') ? 'true' : 'false';
        
        $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
        $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
        
        $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
        $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
        
        
        $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
        $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"variableWidth":'.$variableWidth.',"pauseOnHover":'.$pause_on_hover.',"centerMode":'.$center.', "infinite":'.$infinite.',
        "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
        {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
          

 
         ?>
         <div class="jws_slider_element">

            <div class="<?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?> data-slider="jws-custom-<?php echo esc_attr($this->get_id()); ?>">
                <?php foreach (  $settings['list'] as $index => $item ) { ?>
                    <div class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?><?php echo esc_attr($class_column); ?>">
                    <div class="slider-content-wraps">
                        <div class="slider-content-wrap">
                            <?php 
                                if(!empty($item['slider_title'])) {
                                    echo '<h3 class="slider-title">'.$item['slider_title'].'</h3>';
                                }
                                if(!empty($item['slider_description'])) {
                                    echo '<div class="slider-description">'.$item['slider_description'].'</div>';
                                }
                                if(!empty($item['slider_button'])) {
                                   $link_key = 'link' . $index;  
                                    
                                   if($item['slider_button_url']['is_external']) $this->add_render_attribute( $link_key, 'rel',  'nofollow' );
                                   if($item['slider_button_url']['nofollow']) $this->add_render_attribute( $link_key, 'target',  '_blank' );  
                                   $this->add_render_attribute( $link_key, 'href',  $item['slider_button_url']['url'] ); 
                                   echo '<a '.$this->get_render_attribute_string($link_key).' class="slider-button"><span>'.$item['slider_button'].'<i class="jws-icon-arrow-right-thin"></i></span></a>';
                                }
                            ?>
                        </div>
                      </div>  
                        <?php if(isset($item['mix-image']['url'])) {
                          echo '<div class="slider-mix-1">
                            '.wp_get_attachment_image( $item['mix-image']['id'], 'full' , "", array( "class" => "img-responsive" ) ).'
                          </div>';
                        } ?>
                        <?php if(isset($item['mix-image2']['url'])) {
                          echo '<div class="slider-mix-2">
                            '.wp_get_attachment_image( $item['mix-image2']['id'], 'full' , "", array( "class" => "img-responsive" ) ).'
                          </div>';
                        } ?>
                        
                    </div>
                 <?php } ?>
            </div>
            <div class="slider-dots-box"></div>

         </div>   
        <?php

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