<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Border;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Image_Size;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Progress extends Widget_Base {

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
		return 'jws_progress';
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
		return esc_html__( 'Jws Progress', 'auriane' );
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
		return 'eicon-skill-bar';
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
			'content_section',
			[
				'label' => esc_html__( 'Menu List', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
				'process_layout',
				[
					'label'     => esc_html__( 'Layout', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'list',
					'options'   => [
						'list'   => esc_html__( 'List', 'auriane' ),
						'tab'   => esc_html__( 'Tabs', 'auriane' ),
                        'slider'   => esc_html__( 'Slider', 'auriane' ),
                        'list_hover'   => esc_html__( 'List Hover', 'auriane' ),
                        'grid_animation'   => esc_html__( 'Grid Animation', 'auriane' ),
					],
				]
		);
       	$this->add_responsive_control(
				'process_columns',
				[
					'label'          => esc_html__( 'Columns', 'auriane' ),
					'type'           => Controls_Manager::SELECT,
					'default'        => '12',
					'options'        => [
						'12' => '1',
						'6' => '2',
						'4' => '3',
						'3' => '4',
						'20' => '5',
						'2' => '6',
					],
                    'condition'	=> [
						  'process_layout' => ['grid_animation'],
				    ],
				]
		);
        $this->add_responsive_control(
			'column_gap',
			[
				'label'     => esc_html__( 'Columns Gap', 'auriane' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .progress_items' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
                'condition'	=> [
						  'process_layout' => ['grid_animation'],
				],
			]
		);

		$this->add_responsive_control(
			'row_gap',
			[
				'label'     => esc_html__( 'Rows Gap', 'auriane' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 100,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .progress_items' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
                'condition'	=> [
						  'process_layout' => ['grid_animation'],
				],
			]
		);
		$repeater = new \Elementor\Repeater();
        $repeater->add_control(
			'active',
			[
				'label' => esc_html__( 'Active', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
			]
		);
        $repeater->add_control(
			'list_number', [
				'label' => esc_html__( 'Number', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '1' , 'auriane' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
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

		$repeater->add_control(
			'list_title', [
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'List Title' , 'auriane' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'list_description', [
				'label' => esc_html__( 'Description', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXTAREA,
				'default' => esc_html__( 'Default description', 'auriane' ),
				'placeholder' => esc_html__( 'Type your description here', 'auriane' ),
			]
		);

		$this->add_control(
			'list',
			[
				'label' => esc_html__( 'Menu List', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'list_title' => esc_html__( 'Title #1', 'auriane' ),
					],
				],
				'title_field' => '{{{ list_title }}}',
			]
		);

		$this->end_controls_section();
        $this->start_controls_section(
			'progress_slider_style',
			[
				'label' => esc_html__( 'Content', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_responsive_control(
					'progress_slider_margin',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_progress .progress_item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'progress_slider_padding',
					[
						'type' 			=> Controls_Manager::DIMENSIONS,
						'label' 		=> esc_html__( 'Padding', 'auriane' ),
						'size_units' 	=> [ 'px', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws_progress .progress_item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);

        $this->add_control(
			'progress_des',
			[
				'label' => esc_html__( 'Description', 'auriane' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'progress_description_color',
					[
						'label' 	=> esc_html__( 'Description Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_progress .progress_item .progress_description' => 'color: {{VALUE}};',
						],
					]
		);

        $this->add_control(
			'progress_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'progress_title_color',
					[
						'label' 	=> esc_html__( 'Title Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_progress .progress_item .progress_title' => 'color: {{VALUE}};',
						],
					]
		);


         $this->add_control(
			'progress_icon',
			[
				'label' => esc_html__( 'Icon', 'auriane' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'icon_color',
					[
						'label' 	=> esc_html__( 'Icon Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_progress .progress_item .progress_number i' => 'color: {{VALUE}};',
						],
					]
		);
         $this->add_control(
					'icon_bgcolor',
					[
						'label' 	=> esc_html__( 'Icon Background Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_progress .progress_item .progress_number i' => 'background: {{VALUE}};',
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
						'{{WRAPPER}} .jws_progress .progress_item .progress_number i' => 'font-size: {{SIZE}}px;',
					],
				]
		);
       
    $this->add_control(
			'progress_number',
			[
				'label' => esc_html__( 'Number', 'auriane' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'progress_number_color',
					[
						'label' 	=> esc_html__( 'Title Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_progress .progress_item  .progress_number' => 'color: {{VALUE}};',
						],
					]
		);

        
        

        
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
        
        	if ( $settings['list'] ) {
		     ?>
                  <div class="jws_progress<?php echo ' layout_'.esc_attr($settings['process_layout']); if($settings['process_layout'] == 'grid_animation') echo ' row'; ?>">  
                      <?php include( 'layout/'.$settings['process_layout'].'.php' ); ?>  
                  </div>
		    <?php }  
		
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