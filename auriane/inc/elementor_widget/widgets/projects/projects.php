<?php

namespace Elementor;
use Elementor\Scheme_Color;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;
use Elementor\Widget_Base;
use Elementor\Group_Control_Border;
use Elementor\Controls_Manager;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Projects extends Widget_Base {

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
		return 'jws_project';
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
		return esc_html__( 'Jws Projects', 'auriane' );
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
		return 'eicon-gallery-grid';
	}
    /**
	 * Retrieve the list of scripts the widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 1.0.0
	 *
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
    public function get_script_depends() {
		return [ 'isotope','anime' ];
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
	 * Register Woo post Grid controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_controls() {

		/* General Tab */
        $this->register_content_general_controls();
        $this->register_content_filter_controls();
		$this->register_content_grid_controls();
        $this->register_content_pagination_controls();
        $this->register_content_slider_controls();
	}

    /**
	 * Register Woo posts General Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	*/
	protected function register_content_general_controls() {

		$this->start_controls_section(
			'section_general_field',
			[
				'label' => esc_html__( 'General', 'auriane' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
            $this->add_control(
				'project_layouts',
				[
					'label'     => esc_html__( 'Layout', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'layout1',
					'options'   => [
						'layout1'   => esc_html__( 'Layout 1', 'auriane' ),
					],
                    
				]
			);
            $this->add_control(
				'project_display',
				[
					'label'     => esc_html__( 'Display', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'grid',
					'options'   => [
						'grid'   => esc_html__( 'Grid', 'auriane' ),
					],
                    
				]
			);
            $this->add_control(
    				'slider_padding',
    				[
    					'label'        => esc_html__( 'Enable Slider Padding', 'auriane' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'auriane' ),
    					'label_off'    => esc_html__( 'No', 'auriane' ),
    					'return_value' => 'yes',
    					'default'      => '',
    					'condition'	=> [
						  'project_display' => ['slider'],
				        ],
    				]
    	   );
             $this->add_control(
    			'enable_loading',
    			[
    				'label' => esc_html__( 'Enable Loading', 'auriane' ),
    				'type' => \Elementor\Controls_Manager::SWITCHER,
    				'label_on' => esc_html__( 'On', 'auriane' ),
    				'label_off' => esc_html__( 'Off', 'auriane' ),
    				'return_value' => 'yes',
    			]
    		);
            $this->add_control(
    				'project_nav_on',
    				[
    					'label'        => esc_html__( 'Enable Nav', 'auriane' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'auriane' ),
    					'label_off'    => esc_html__( 'No', 'auriane' ),
    					'return_value' => 'yes',
    					'default'      => 'yes',
    					'description'  => esc_html__( 'Enable nav filter project.', 'auriane' ),
    				]
    	   );
           $this->add_control(
				'nav_text_first',
				[
					'label'     => esc_html__( 'Nav Text All', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
                    'condition'	=> [
						'project_nav_on' => 'yes',
				    ],
			 ]
		  );    
          $this->add_control(
			'image_size',
			[
				'label' => esc_html__( 'Image Size', 'auriane' ),
				'type' => \Elementor\Controls_Manager::IMAGE_DIMENSIONS,
				'default' => [
					'width' => '',
					'height' => '',
				],
			]
		  );
         
          $this->add_control(
			'ajax_page',
			[
				'label' => esc_html__( 'ajax_page', 'auriane' ),
				'type' => Controls_Manager::HIDDEN,
				'default' => '',
			]
		  );
		$this->end_controls_section();
        $this->start_controls_section(
			'loadmore_style',
			[
				'label' => esc_html__( 'Load More', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
				'loadmore_align',
				[
					'label' 		=> esc_html__( 'Align', 'auriane' ),
					'type' 			=> Controls_Manager::CHOOSE,
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
							'{{WRAPPER}} .jws-project-element .jws_pagination' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
              $this->add_responsive_control(
					'loadmore_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .jws_pagination' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
					'loadmore_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_control(
			'loadmore_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'loadmore_bgcolor',
			[
				'label' => esc_html__( 'Background Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more' => 'background: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'loadmore_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more:hover' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'loadmore_bgcolor_hover',
			[
				'label' => esc_html__( 'Background Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more:hover' => 'background: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'loadmore_border',
				'label' => esc_html__( 'Border', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more',
			]
		);
        $this->add_control(
			'loadmore_radius',
			[
				'label' => esc_html__( 'Border Radius', 'auriane' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_control(
			'loadmore_border_color_hover',
			[
				'label' => esc_html__( 'Border Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more:hover' => 'border-color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'loadmore_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-project-element .jws_pagination .jws-load-more',
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'nav_style',
			[
				'label' => esc_html__( 'Nav', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_responsive_control(
				'nav_align',
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
							'{{WRAPPER}} .jws-project-element .project_nav_container' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
        $this->add_responsive_control(
					'nav_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_nav' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'nav_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_nav' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_control(
			'nav_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
					'nav_color',
					[
						'label' 	=> esc_html__( 'Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-project-element .project_nav li a' => 'color: {{VALUE}} !important;',
						],
					]
		);
        $this->add_control(
					'nav_color_hover',
					[
						'label' 	=> esc_html__( 'Color Hover', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-project-element .project_nav li a:hover' => 'color: {{VALUE}} !important;',
						],
					]
		);
        $this->add_control(
					'nav_color_active',
					[
						'label' 	=> esc_html__( 'Color Active', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-project-element .project_nav li a.filter-active' => 'color: {{VALUE}} !important; border-color: {{VALUE}} !important; ',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'nav_typography',
				'label' => esc_html__( 'Typography', 'auriane'),
				'selector' => '{{WRAPPER}} .jws-project-element .project_nav li a',
			]
		);
        $this->add_responsive_control(
					'nav_padding_out',
					[
						'label' 		=> esc_html__( 'Padding Out', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_nav li' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'nav_padding_in',
					[
						'label' 		=> esc_html__( 'Padding In', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_nav li a' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_control(
			'nav_line',
			[
				'label' => esc_html__( 'Line', 'auriane' ),
				'type' => Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $this->add_control(
			'line_color',
			[
				'label' => esc_html__( 'Line Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_nav #magic_line' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .jws-project-element .project_nav li a.filter-active' => 'border-color: {{VALUE}}',
				],
			]
		);
         $this->add_responsive_control(
			'line_height',
			[
				'label'     => esc_html__( 'Line Height', 'auriane' ),
				'type'      => Controls_Manager::SLIDER,
				'range'     => [
					'px' => [
						'min' => 0,
						'max' => 20,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_nav #magic_line' => 'height:{{SIZE}}{{UNIT}};',
                    '{{WRAPPER}} .jws-project-element .project_nav li a' => 'border-width:{{SIZE}}{{UNIT}};',
				],
			]
		);

        $this->end_controls_section();
        $this->start_controls_section(
			'box_style',
			[
				'label' => esc_html__( 'Box', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'box_bgcolor',
			[
				'label' => esc_html__( 'Background', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content.jws_project_layout6 .jws_project_wap .jws_project_content' => 'background: {{VALUE}}',
				],
			]
		);
        $this->add_responsive_control(
					'box_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}} !important;',
						],

						'separator' => 'before',
					]
		);
        $this->add_responsive_control(
					'box_radius',
					[
						'label' 		=> esc_html__( 'Border Radius', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_content.jws_project_layout2 .jws_project_wap' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
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
					'{{WRAPPER}} .jws-project-element .jws_project_item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws-project-element .row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
                    '{{WRAPPER}} .custom_navs button.nav_left' => 'margin-left: calc( {{SIZE}}{{UNIT}}/2 + 25px );',
                    '{{WRAPPER}} .custom_navs button.nav_right' => 'margin-right: calc( {{SIZE}}{{UNIT}}/2 + 25px );',
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
					'{{WRAPPER}} .jws-project-element .jws_project_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();

        $this->start_controls_section(
			'box_title_style',
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
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .entry-title a' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .entry-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .entry-title',
			]
		);
         $this->add_responsive_control(
					'title_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'cat_style',
			[
				'label' => esc_html__( 'Category', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
              $this->add_control(
			'cat_color',
			[
				'label' => esc_html__( 'Title Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .projects_cat a , .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .projects_cat' => 'color: {{VALUE}} !important',
				],
			]
		);
        $this->add_control(
			'cat_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .projects_cat a:hover' => 'color: {{VALUE}} !important',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'cat_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .projects_cat',
			]
		);
         $this->add_responsive_control(
					'cat_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .projects_cat' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
                    ]    
		);	
        $this->end_controls_section();
        $this->start_controls_section(
			'readmore_style',
			[
				'label' => esc_html__( 'Read More', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'reicon_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .jws_project_readmore' => 'color: {{VALUE}} !important',
				],
			]
		);
        $this->add_control(
			'reicon_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .jws_project_readmore:hover' => 'color: {{VALUE}} !important',
				],
			]
		);
         $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'reicon_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .jws_project_content .jws_project_content_inner .jws_project_readmore',
			]
		);
        $this->end_controls_section();
        
        $this->start_controls_section(
			'line_style',
			[
				'label' => esc_html__( 'Line', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
        $this->add_control(
			'line_bgcolor',
			[
				'label' => esc_html__( 'Background Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .project_filter_content .jws_project_item .jws_project_wap:before' => 'background: {{VALUE}}',
				],
			]
		);
        $this->end_controls_section();
       $this->start_controls_section(
			'overlay_style',
			[
				'label' => esc_html__( 'Overlay', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

        $this->add_control(
			'overlay_background',
			[
				'label' => esc_html__( 'Overlay Background', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-project-element .project_content .jws_project_wap .overlay_inner' => 'background: {{VALUE}} !important',
				],
			]
		);

        $this->end_controls_section();
	}
     /**
	 * Register Pagination Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_pagination_controls() {

		$this->start_controls_section(
			'section_pagination_field',
			[
				'label'     => esc_html__( 'Pagination', 'auriane' ),
				'tab'       => Controls_Manager::TAB_CONTENT,
			]
		);

			$this->add_control(
				'pagination_type',
				[
					'label'     => esc_html__( 'Type', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '',
					'options'   => [
						''              => esc_html__( 'None', 'auriane' ),
                        'load_more' => esc_html__( 'Load More', 'auriane' ),
					],
				]
			);
            $this->add_control(
				'pagination_loadmore_label',
				[
					'label'     => esc_html__( 'Loadmore Label', 'auriane' ),
					'default'   => esc_html__( 'Load more works', 'auriane' ),
					'condition' => [
						'pagination_type'      => ['load_more'],
					],
				]
			);
		$this->end_controls_section();
	}/**
	 * Register Woo posts Filter Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_filter_controls() {

		$this->start_controls_section(
			'section_filter_field',
			[
				'label' => esc_html__( 'Query', 'auriane' ),
				'tab'   => Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
				'project_per_page',
				[
					'label'     => esc_html__( 'posts Per Page', 'auriane' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => '8',
					'condition' => [
						'query_type!'  => 'main',
					],
				]
			);
		$this->add_control(
				'query_type',
				[
					'label'   => esc_html__( 'Source', 'auriane' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'all',
					'options' => [
						'all'    => esc_html__( 'All posts', 'auriane' ),
						'custom' => esc_html__( 'Custom Query', 'auriane' ),
						'manual' => esc_html__( 'Manual Selection', 'auriane' ),
					],
				]
			);

			$this->add_control(
				'category_filter_rule',
				[
					'label'     => esc_html__( 'Category Filter Rule', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'IN',
					'options'   => [
						'IN'     => esc_html__( 'Match Categories', 'auriane' ),
						'NOT IN' => esc_html__( 'Exclude Categories', 'auriane' ),
					],
					'condition' => [
						'query_type' => 'custom',
					],
				]
			);
			$this->add_control(
				'category_filter',
				[
					'label'     => esc_html__( 'Select Categories', 'auriane' ),
					'type'      => Controls_Manager::SELECT2,
					'multiple'  => true,
					'default'   => '',
					'options'   => $this->get_project_categories(),
					'condition' => [
						'query_type' => 'custom',
					],
				]
			);
            
            
			$this->add_control(
				'tag_filter_rule',
				[
					'label'     => esc_html__( 'Tag Filter Rule', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'IN',
					'options'   => [
						'IN'     => esc_html__( 'Match Tags', 'auriane' ),
						'NOT IN' => esc_html__( 'Exclude Tags', 'auriane' ),
					],
					'condition' => [
						'query_type' => 'custom',
					],
				]
			);
			$this->add_control(
				'tag_filter',
				[
					'label'     => esc_html__( 'Select Tags', 'auriane' ),
					'type'      => Controls_Manager::SELECT2,
					'multiple'  => true,
					'default'   => '',
					'options'   => $this->get_project_tags(),
					'condition' => [
						'query_type' => 'custom',
					],
				]
			);
			$this->add_control(
				'offset',
				[
					'label'       => esc_html__( 'Offset', 'auriane' ),
					'type'        => Controls_Manager::NUMBER,
					'default'     => 0,
					'description' => esc_html__( 'Number of post to displace or pass over.', 'auriane' ),
					'condition'   => [
						'query_type' => 'custom',
					],
				]
			);

			$this->add_control(
				'query_manual_ids',
				[
					'label'     => esc_html__( 'Select posts', 'auriane' ),
					'type'      => 'jws-query-posts',
					'post_type' => 'projects',
					'multiple'  => true,
					'condition' => [
						'query_type' => 'manual',
					],
				]
			);

			/* Exclude */
			$this->add_control(
				'query_exclude',
				[
					'label'     => esc_html__( 'Exclude', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'query_type!' => [ 'manual', 'main' ],
					],
				]
			);
			$this->add_control(
				'query_exclude_ids',
				[
					'label'       => esc_html__( 'Select posts', 'auriane' ),
					'type'        => 'jws-query-posts',
					'post_type'   => 'projects',
					'multiple'    => true,
					'description' => esc_html__( 'Select posts to exclude from the query.', 'auriane' ),
					'condition'   => [
						'query_type!' => [ 'manual', 'main' ],
					],
				]
			);
			$this->add_control(
				'query_exclude_current',
				[
					'label'        => esc_html__( 'Exclude Current post', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'label_on'     => esc_html__( 'Yes', 'auriane' ),
					'label_off'    => esc_html__( 'No', 'auriane' ),
					'return_value' => 'yes',
					'default'      => '',
					'description'  => esc_html__( 'Enable this option to remove current post from the query.', 'auriane' ),
					'condition'    => [
						'query_type!' => [ 'manual', 'main' ],
					],
				]
			);

			/* Advanced Filter */
			$this->add_control(
				'query_advanced',
				[
					'label'     => esc_html__( 'Advanced', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
					'condition' => [
						'query_type!' => 'main',
					],
				]
			);
			$this->add_control(
				'filter_by',
				[
					'label'     => esc_html__( 'Filter By', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => '',
					'options'   => [
						''         => esc_html__( 'None', 'auriane' ),
						'featured' => esc_html__( 'Featured', 'auriane' ),
						'sale'     => esc_html__( 'Sale', 'auriane' ),
					],
					'condition' => [
						'query_type!' => 'main',
					],
				]
			);
			$this->add_control(
				'orderby',
				[
					'label'     => esc_html__( 'Order by', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'date',
					'options'   => [
						'date'       => esc_html__( 'Date', 'auriane' ),
						'title'      => esc_html__( 'Title', 'auriane' ),
						'price'      => esc_html__( 'Price', 'auriane' ),
						'popularity' => esc_html__( 'Popularity', 'auriane' ),
						'rating'     => esc_html__( 'Rating', 'auriane' ),
						'rand'       => esc_html__( 'Random', 'auriane' ),
						'menu_order' => esc_html__( 'Menu Order', 'auriane' ),
					],
					'condition' => [
						'query_type!' => 'main',
					],
				]
			);
			$this->add_control(
				'order',
				[
					'label'     => esc_html__( 'Order', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'desc',
					'options'   => [
						'desc' => esc_html__( 'Descending', 'auriane' ),
						'asc'  => esc_html__( 'Ascending', 'auriane' ),
					],
					'condition' => [
						'query_type!' => 'main',
					],
				]
			);

		$this->end_controls_section();
	}
    
   
	/**
	 * Register grid Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_grid_controls() {
		$this->start_controls_section(
			'section_grid_options',
			[
				'label'     => esc_html__( 'Grid Options', 'auriane' ),
				'type'      => Controls_Manager::SECTION,
			]
		);
		$this->add_responsive_control(
				'project_columns',
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
				]
		);
		$this->end_controls_section();
	}
    /**
	 * Register Slider Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_content_slider_controls() {
		$this->start_controls_section(
			'section_slider_options',
			[
				'label'     => esc_html__( 'Slider Options', 'auriane' ),
				'type'      => Controls_Manager::SECTION,
				'condition' => [
					'project_display' => ['slider'],
				],
			]
		);

		$this->add_control(
			'navigation',
			[
				'label'     => esc_html__( 'Navigation', 'auriane' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'both',
				'options'   => [
                    'both' => esc_html__( 'Arrows And Dots', 'auriane' ),
					'arrows' => esc_html__( 'Arrows', 'auriane' ),
                    'dots' => esc_html__( 'Dots', 'auriane' ),
					'none'   => esc_html__( 'None', 'auriane' ),
				],
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
			'rtl',
			[
				'label'        => esc_html__( 'Right To Left', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
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
        $this->add_control(
			'center_mode',
			[
				'label'        => esc_html__( 'Center Mode', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
				'default'      => '',
			]
		);
        $this->add_responsive_control(
			'center_padding',
			[
				'label'     => esc_html__( 'Center Padding', 'auriane' ),
				'type'      => Controls_Manager::TEXT,
				'default'   => '',
				'condition' => [
					'center_mode'             => 'yes',
				],
                'selectors' => [
					'{{WRAPPER}} .custom_navs button.nav_left' => 'left: {{VALUE}};',
                    '{{WRAPPER}} .custom_navs button.nav_right' => 'right: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}
    /**
	 * Get WooCommerce post Categories.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_project_categories() {

		$project_cat = array();

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$project_categories = get_terms( 'projects_cat', $cat_args );

		if ( ! empty( $project_categories ) && !is_wp_error( $project_categories ) ) {

			foreach ( $project_categories as $key => $category ) {

				$project_cat[ $category->term_id ] = $category->name;
			}
		}

		return $project_cat;
	}
    /**
	 * Get WooCommerce post Tags.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_project_tags() {

		$project_tag = array();

		$tag_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$project_tag = get_terms( 'projects_tag', $tag_args );

		if ( ! empty( $project_tag ) && !is_wp_error( $project_tag ) ) {

			foreach ( $project_tag as $key => $tag ) {

				$project_tag[ $tag->slug ] = $tag->name;
			}
		}

		return $project_tag;
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
        if($settings['project_layouts'] == 'layout3') {
            wp_enqueue_script('lightgallery-all');
            wp_enqueue_style('lightgallery'); 
        }
		
        $id       = $this->get_id();
        extract( $settings );
        $encoded_atts = json_encode( $settings );

			global $post;

			$query_args = [
				'post_type'      => 'projects',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'paged'          => 1,
				'post__not_in'   => array(),
			];

			$query_args['orderby'] = $settings['orderby'];
			$query_args['order']   = $settings['order'];
		    if ( $settings['project_per_page'] > 0 ) {
					$query_args['posts_per_page'] = $settings['project_per_page'];
			}
			if ( '' !== $settings['pagination_type'] ) {

					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '1';
                    if( $settings['ajax_page'] > 1 ) $paged = $settings['ajax_page'];
					if ( isset( $_POST['page_number'] ) && '' != $_POST['page_number'] ) {
						$paged = $_POST['page_number'];
					}

					$query_args['paged'] = $paged;
			}


			if ( 'custom' === $settings['query_type'] ) {

				if ( ! empty( $settings['category_filter'] ) ) {

					$cat_operator = $settings['category_filter_rule'];

			
                    
                    $taxonomy_names = get_object_taxonomies('projects');
                    $terms = get_terms($taxonomy_names, array(
                        'orderby' => 'name',
                        'include' => $settings['category_filter']
                    ));
                    if (!is_wp_error($terms) && !empty($terms)) {
                        $query_args['tax_query'] = array('relation' => 'OR');
                        foreach ($terms as $key => $term) {
                            $query_args['tax_query'][] = array(
                                'taxonomy' => $term->taxonomy,
                                'field' => 'slug',
                                'terms' => array($term->slug),
                                'include_children' => true,
                                'operator' => $cat_operator,
                                'include' => $settings['category_filter']
                            );
                        }
                    }
    
    				if ( ! empty( $settings['tag_filter'] ) ) {
    
    					$tag_operator = $settings['tag_filter_rule'];
    
    					$query_args['tax_query'][] = [
    						'taxonomy' => 'project_tag',
    						'field'    => 'slug',
    						'terms'    => $settings['tag_filter'],
    						'operator' => $tag_operator,
    					];
    				}  
				}
                
                $taxonomy_names = get_object_taxonomies('projects');
           
				if ( 0 < $settings['offset'] ) {

					/**
					 * Offset break the pagination. Using WordPress's work around
					 *
					 * @see https://codex.wordpress.org/Making_Custom_Queries_using_Offset_and_Pagination
					 */
					$query_args['offset_to_fix'] = $settings['offset'];
				}
			}

			if ( 'manual' === $settings['query_type'] ) {

				$manual_ids = $settings['query_manual_ids'];

				$query_args['post__in'] = $manual_ids;

			}

			if ( 'manual' !== $settings['query_type'] && 'main' !== $settings['query_type'] ) {

				if ( '' !== $settings['query_exclude_ids'] ) {

					$exclude_ids = $settings['query_exclude_ids'];

					$query_args['post__not_in'] = $exclude_ids;
				}

				if ( 'yes' === $settings['query_exclude_current'] ) {

					$query_args['post__not_in'][] = $post->ID;
				}
			}

			$query_args = apply_filters( 'jws_project_query_args', $query_args, $settings );

			$project = new \WP_Query( $query_args );
            
            
      $class_row = 'project_content row jws-isotope';  
      $class_row .= ' jws_project_'.$settings['project_layouts'].'';
      $class_row.= ' '.$settings['project_display'];
      $class_row .= ' project_ajax_'.$this->get_id().'';
      $class_column = 'jws_project_item';
      if($settings['slider_padding'] == 'yes')  $class_row.= ' has_wrap';
       if($settings['enable_loading'] == 'yes') {
            $class_row .= ' has-loading';
       }
       $arrows = false;
      if($settings['project_display'] == 'slider') {
            $class_row .= ' jws_project_'.$settings['project_display'].' fix_dots_slider';
            $dots = ($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $arrows = ($settings['navigation'] == 'arrows' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
            $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
            $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
            $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
            $center = ($settings['center_mode'] == 'yes') ? 'true' : 'false';
            $settings['center_padding_tablet'] = isset($settings['center_padding_tablet']) ? $settings['center_padding_tablet'] : '0px';
            $settings['center_padding_mobile'] = isset($settings['center_padding_mobile']) ? $settings['center_padding_mobile'] : '0px';
            $rtl = ($settings['rtl'] == 'yes') ? 'true' : 'false';
            
            $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : '1';
            $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : '1';
            $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : '1';
            $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : '1';
            
            $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].',
             "centerMode": '.$center.',"centerPadding": "'.$settings['center_padding'].'","rtl":'.$rtl.', "autoplay": '.$autoplay.',"arrows": '.$arrows.',
              "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"pauseOnHover":'.$pause_on_hover.',"infinite":'.$infinite.',
            "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',
            "slidesToScroll": '.$settings['slides_to_scroll_tablet'].',"centerPadding": "'.$settings['center_padding_tablet'].'"}}, 
            {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].',"centerPadding": "'.$settings['center_padding_mobile'].'"}}]}\''; 
       }else {
                $data_slick = '';
                $class_column .= ' col-xl-'.$settings['project_columns'].'';
              $class_column .= (!empty($settings['project_columns_tablet'])) ? ' col-lg-'.$settings['project_columns_tablet'].'' : ' col-lg-'.$settings['project_columns'].'' ;
              $class_column .= (!empty($settings['project_columns_mobile'])) ? ' col-'.$settings['project_columns_mobile'].'' :  ' col-'.$settings['project_columns'].''; 
       }
      if ( $project->max_num_pages > 1) { $class_row.= ' jws_has_pagination'; }
     

      ?>
 
		
		<div class="jws-project-element">
            <?php 
                $taxonomy_names = get_object_taxonomies('projects');
                if((isset($settings['category_filter_rule']) && $settings['category_filter_rule'] != 'IN') && 'custom' === $settings['query_type']) {
                   $cats = get_terms($taxonomy_names, array(
                       'orderby' => 'name',
                       'exclude' => $settings['category_filter'],
                   ));  
                } elseif((isset($settings['category_filter_rule']) && $settings['category_filter_rule'] == 'IN') && 'custom' === $settings['query_type']) {
                   $cats = get_terms($taxonomy_names, array(
                       'orderby' => 'name',
                       'include' => $settings['category_filter'],
                    ));
                }else {
                    $cats = get_terms($taxonomy_names, array(
                       'orderby' => 'name',
                    ));
                }
            ?>
            <?php if($settings['project_nav_on'] == 'yes') : ?>
            <div class="project_nav_container">
                <ul class="project_nav">
                    <li><a href="#" data-filter="*" class="filter-active"><?php echo (!empty($settings['nav_text_first'])) ? $settings['nav_text_first'] : esc_html__('All Projects', 'auriane'); ?></a></li>
                    <?php
                        foreach ($cats as $cat) {
                            ?>
                                <li><a href="#" data-filter="<?php echo "." . esc_attr($cat->slug); ?>"><?php echo esc_html($cat->name); ?></a></li>
                            <?php
                        }
                    ?>
                </ul>
            </div>   
            <?php endif; ?>    
            <div id="project_<?php echo esc_attr($id); ?>" class="<?php echo esc_attr($class_row); ?>" <?php echo wp_kses_post( $data_slick); ?> data-gallery="jws-custom-<?php echo esc_attr($this->get_id()); ?>">
                <?php 
                    if ($project->have_posts()) : $i = 1; $n = 0;
                        while ( $project->have_posts() ) :
                    			$project->the_post();
                                    $class_slug = '';
                                    $item_cats = get_the_terms(get_the_ID(), 'projects_cat');
                                    if ($item_cats):
                                        foreach ($item_cats as $item_cat) {
                                            $class_slug .= ' '.$item_cat->slug;
                                        }
                                    endif;
                       
                                     if(!empty($settings['image_size']['width']) && !empty($settings['image_size']['height'])) {
                                        $image_size = $settings['image_size']['width'].'x'.$settings['image_size']['height'];
                                     }else {
                                        $image_size = 'full';
                                     }  
                                 

                                    echo '<div class="'.$class_column.$class_slug.'" data-gallery-image data-gallery-item="'.$n.'">';
       
                                           include( 'layout/'.$settings['project_layouts'].'.php' );
                                     
                                   echo '</div>';   
                                    
                        
                        $n++;
                        endwhile;    
                    endif;
        
                	wp_reset_postdata();
                   
                ?>
            </div>
            <?php if($arrows == 'true') : ?>
              <nav class="custom_navs">
                    <button class="nav_left"><span class="icon-arrow-left2"></span></button>
                    <button class="nav_right"><span class="icon-arrow-right2"></span></button>
              </nav>
              <?php endif; ?>
            <?php  $url = get_next_posts_page_link( $project->max_num_pages ); if ( $project->max_num_pages > 1 && '' !== $settings['pagination_type']) { wp_enqueue_script( 'masonry');  $load_attr = 'data-ajaxify-options=\'{"wrapper":".project_ajax_'.$this->get_id().'","items":"> .jws_project_item","trigger":"click"}\'';  ?>
                <div class="jws_pagination jws_ajax">
                    <?php if(get_next_posts_page_link($project->max_num_pages )): ?>
                    <a class="jws-load-more" data-ajaxify="true"  href="<?php echo esc_url($url); ?>" <?php echo wp_kses_post( $load_attr); ?>>
                        <span class="has-loading"><?php echo esc_html($settings['pagination_loadmore_label']); ?></span>
                    </a>
                    <?php endif; ?>
                </div>
            <?php } ?>
        </div>

	<?php }
    
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