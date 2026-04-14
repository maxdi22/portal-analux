<?php
namespace Elementor;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
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
class Jws_Blog extends Widget_Base {

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
		return 'jws_blog';
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
		return esc_html__( 'Jws Blog', 'auriane' );
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
		return 'eicon-post';
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
		return [ 'magnificPopup'];
	  }
      public function get_style_depends() {
		return [ 'magnificPopup'];
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
				'blog_display',
				[
					'label'     => esc_html__( 'Display', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'grid',
					'options'   => [
						'grid'   => esc_html__( 'Grid', 'auriane' ),
						'slider'   => esc_html__( 'Slider', 'auriane' ),
                        'mixed'   => esc_html__( 'Mixed', 'auriane' ),
					],
                    
				]
			);
            $this->add_control(
    			'enable_masonry',
    			[
    				'label' => esc_html__( 'Enable Masonry', 'auriane' ),
    				'type' => \Elementor\Controls_Manager::SWITCHER,
    				'label_on' => esc_html__( 'On', 'auriane' ),
    				'label_off' => esc_html__( 'Off', 'auriane' ),
    				'return_value' => 'yes',
    			]
    		);
            $this->add_control(
    			'enable_animation',
    			[
    				'label' => esc_html__( 'Enable Animation', 'auriane' ),
    				'type' => \Elementor\Controls_Manager::SWITCHER,
    				'label_on' => esc_html__( 'On', 'auriane' ),
    				'label_off' => esc_html__( 'Off', 'auriane' ),
    				'return_value' => 'yes',
    			]
    		);
            $this->add_control(
    			'border_popover_toggle',
    			[
    				'label' => esc_html__( 'Border', 'auriane' ),
    				'type' => \Elementor\Controls_Manager::POPOVER_TOGGLE,
    				'label_off' => esc_html__( 'Default', 'auriane' ),
    				'label_on' => esc_html__( 'Custom', 'auriane' ),
    				'return_value' => 'yes',
    				'default' => 'yes',
    			]
		    );
            $this->add_control(
				'blog_layouts',
				[
					'label'     => esc_html__( 'Layout', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'layout1',
					'options'   => [
						'layout1'   => esc_html__( 'Layout 1', 'auriane' ),
						'layout2'   => esc_html__( 'Layout 2', 'auriane' ),
                        'layout3'   => esc_html__( 'Layout 3', 'auriane' ),
					],
                    
				]
			);
            $this->add_control(
				'excerpt_length',
				[
					'label'     => esc_html__( 'Excerpt Length', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '',
				]
			);
             $this->add_control(
				'excerpt_more',
				[
					'label'     => esc_html__( 'Excerpt More', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => ' ... ',
				]
			);
            $this->add_control(
				'readmore_text',
				[
					'label'     => esc_html__( 'Read More Text', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => 'Read More',
				]
			);
            $this->add_control(
				'icon',
				[
					'label' => esc_html__( 'Read More Icon', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fab fa-wordpress',
                		'library' => 'fa-brands',
					],
				]
		  );
          $this->add_control(
			'blog_image_size',
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
			'post_archive',
			[
				'label' => esc_html__( 'Show Post For Archive Category', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'post_related',
			[
				'label' => esc_html__( 'Show post related for single blog', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
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
          $this->add_control(
    				'post_nav_on',
    				[
    					'label'        => esc_html__( 'Enable Nav', 'auriane' ),
    					'type'         => Controls_Manager::SWITCHER,
    					'label_on'     => esc_html__( 'Yes', 'auriane' ),
    					'label_off'    => esc_html__( 'No', 'auriane' ),
    					'return_value' => 'yes',
    					'description'  => esc_html__( 'Enable nav filter post.', 'auriane' ),
    				]
    	   );
           $this->add_control(
				'post_text_first',
				[
					'label'     => esc_html__( 'Nav Text All', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
                    'default' => 'ALL CATEGORIES',
                    'condition'	=> [
						'post_nav_on' => 'yes',
				    ],
			 ]
		  );  
		$this->end_controls_section();
       
     }   
    	/**
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
                'condition' => [
						'post_archive!' => [ 'yes' ],
                        'post_related!' => [ 'yes' ],
				],
			]
		);
        
        $this->add_control(
				'blog_per_page',
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
					'options'   => $this->get_blog_categories(),
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
					'options'   => $this->get_blog_tags(),
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
					'post_type' => 'post',
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
					'post_type'   => 'post',
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
						'rand'       => esc_html__( 'Random', 'auriane' ),
						'post__in' => esc_html__( 'Post In', 'auriane' ),
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
				'blog_columns',
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
                'condition' => [
					'blog_display' => ['grid'],
				],
			]
		);
         $this->add_control(
				'pagination_align',
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
							'{{WRAPPER}} .jws-pagination-number ul' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
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
                        'pagination-number' => esc_html__( 'Number Pagination', 'auriane' ),
					],
				]
		);
        $this->add_control(
				'pagination_loadmore_label',
				[
					'label'     => esc_html__( 'Loadmore Label', 'auriane' ),
					'default'   => esc_html__( 'Load more posts', 'auriane' ),
					'condition' => [
						'pagination_type'      => ['load_more'],
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
					'blog_display' => ['slider'],
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

			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'          => esc_html__( 'posts to Scroll', 'auriane' ),
				'type'           => Controls_Manager::NUMBER,
	
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
		$this->end_controls_section();
             $this->start_controls_section(
			'box_style',
			[
				'label' => esc_html__( 'Box', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
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
					'{{WRAPPER}} .jws_blog_item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .blog_content.row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .jws_blog_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'align',
			[
				'label' => esc_html__( 'Alignment', 'auriane' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon' => 'eicon-text-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon' => 'eicon-text-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon' => 'eicon-text-align-right',
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_blog_item' => 'text-align: {{VALUE}};',
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
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_blog_item .jws_post_wap .jws_post_content .entry-title a' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			'title_color_hover',
			[
				'label' => esc_html__( 'Title Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_blog_item .jws_post_wap .jws_post_content .entry-title a:hover' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-blog-element .jws_blog_item .jws_post_wap .jws_post_content .entry-title',
			]
		);

        $this->add_responsive_control(
					'title_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-blog-element .jws_blog_item .jws_post_wap .jws_post_content .entry-title' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'title_typography_hover',
				'label' => esc_html__( 'Typography Hover', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-blog-element .jws_blog_item .jws_post_wap .jws_post_content .entry-title:hover',
			]
		);
        $this->add_control(
			'title_underline_color_hover',
			[
				'label' => esc_html__( 'Underline Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_blog_item .jws_post_wap .jws_post_content .entry-title a:hover' => 'text-decoration-color: {{VALUE}}; -webkit-text-decoration-color:{{VALUE}};-moz-text-decoration-color: {{VALUE}}',
				],
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'excerpt_style',
			[
				'label' => esc_html__( 'Excerpt', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'show_excerpt',
			[
				'label' => esc_html__( 'Show Excerpt', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'auriane' ),
				'label_off' => esc_html__( 'Hide', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'excerpt_color',
			[
				'label' => esc_html__( 'Excerpt Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_excerpt' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'excerpt_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_excerpt',
			]
		);
        $this->add_responsive_control(
					'excerpt_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_excerpt' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
					]
		);
        $this->end_controls_section();
        
        
        $this->start_controls_section(
			'date_style',
			[
				'label' => esc_html__( 'Date', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
			'show_date',
			[
				'label' => esc_html__( 'Show Date', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'auriane' ),
				'label_off' => esc_html__( 'Hide', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			'date_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_post_wap .entry-date a' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'date_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-blog-element .jws_post_wap .entry-date',
			]
		);
        $this->add_responsive_control(
					'date_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-blog-element .jws_post_wap .entry-date' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
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
			'show_readmore',
			[
				'label' => esc_html__( 'Show Readmore', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'auriane' ),
				'label_off' => esc_html__( 'Hide', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'yes',
			]
		);
        $this->add_control(
			're_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_content .jws_post_readmore' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_control(
			're_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_content .jws_post_readmore:hover' => 'color: {{VALUE}}',
				],
			]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'readmore_typography',
				'label' => esc_html__( 'Typography', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_content .jws_post_readmore',
			]
		);
        $this->add_responsive_control(
					'readmore_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-blog-element .jws_post_wap .jws_post_content .jws_post_readmore' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],

						'separator' => 'before',
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
	protected function get_blog_categories() {

		$blog_cat = array();

		$cat_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$blog_categories = get_terms( 'category', $cat_args );

		if ( ! empty( $blog_categories ) ) {

			foreach ( $blog_categories as $key => $category ) {
				$blog_cat[ $category->term_id ] = $category->name;
			}
		}

		return $blog_cat;
	}
    /**
	 * Get WooCommerce post Tags.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_blog_tags() {

		$blog_tag = array();

		$tag_args = array(
			'orderby'    => 'name',
			'order'      => 'asc',
			'hide_empty' => false,
		);

		$blog_tag = get_terms( 'post_tag', $tag_args );

		if ( ! empty( $blog_tag ) ) {

			foreach ( $blog_tag as $key => $tag ) {

				$blog_tag[ $tag->term_id ] = $tag->name;
			}
		}

		return $blog_tag;
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


			global $post;

			$query_args = [
				'post_type'      => 'post',
				'post_status'    => 'publish',
				'posts_per_page' => -1,
				'paged'          => 1,
				'post__not_in'   => array(),
			];
            // Default ordering args.
			$query_args['orderby'] = $settings['orderby'];
			$query_args['order']   = $settings['order'];
		    if ( $settings['blog_per_page'] > 0 ) {
					$query_args['posts_per_page'] = $settings['blog_per_page'];
			}
			if ( '' !== $settings['pagination_type'] ) {

					$paged = ( get_query_var( 'paged' ) ) ? get_query_var( 'paged' ) : '1';


					$query_args['paged'] = $paged;
                    
			}


			if ( 'custom' === $settings['query_type'] ) {

				if ( ! empty( $settings['category_filter'] ) ) {

					$cat_operator = $settings['category_filter_rule'];

					$query_args['tax_query'][] = [
						'taxonomy' => 'category',
						'field'    => 'id',
						'terms'    => $settings['category_filter'],
						'operator' => $cat_operator,
					];
				}

				if ( ! empty( $settings['tag_filter'] ) ) {

					$tag_operator = $settings['tag_filter_rule'];

					$query_args['tax_query'][] = [
						'taxonomy' => 'post_tag',
						'field'    => 'id',
						'terms'    => $settings['tag_filter'],
						'operator' => $tag_operator,
					];
				}

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

				if ( 'yes' === $settings['query_exclude_current'] && isset($post->ID) ) {

					$query_args['post__not_in'][] = $post->ID;
				}
			}


			$blog = new \WP_Query( $query_args );

      $class_row = 'row blog_content';  
      $class_row .= ' jws_blog_'.$settings['blog_layouts'].'';
      $class_row .= ' blog_ajax_'.$this->get_id().'';
      $class_row.= ' jws-isotope'; 
      if ( $blog->max_num_pages > 1) { $class_row.= ' jws_has_pagination'; }
      
      $class_column = 'jws_blog_item';
      //if($settings['blog_display'] != 'slider') { 
           $class_column .= ' col-xl-'.$settings['blog_columns'].'';
          $class_column .= (!empty($settings['blog_columns_tablet'])) ? ' col-lg-'.$settings['blog_columns_tablet'].'' : ' col-lg-'.$settings['blog_columns'].'' ;
          $class_column .= (!empty($settings['blog_columns_mobile'])) ? ' col-'.$settings['blog_columns_mobile'].'' :  ' col-'.$settings['blog_columns'].'';
      //}
 
      
      if($settings['blog_display'] == 'slider') {
            $class_row .= ' jws_blog_'.$settings['blog_display'].'';
            $class_column .= ' slick-slide';
            $dots = ($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $arrows = ($settings['navigation'] == 'arrows' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
            $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
            $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
            $rtl = ($settings['rtl'] == 'yes') ? 'true' : 'false';
            
            if(is_rtl()) {
               $rtl =  'true';
            }
            
            $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '3';

            $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
            $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
            
           
            
            $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
            
            $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
            $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
            
            
            $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
            $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].',"rtl":'.$rtl.', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"pauseOnHover":'.$pause_on_hover.',"infinite":'.$infinite.',
            "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
            {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
       }else {
            $data_slick = '';
             
       }
       if($settings['enable_masonry'] == 'yes') {
            $class_row .= ' has-masonry';
       }
       
       if($settings['enable_animation'] == 'yes') {
            $class_row .= ' has-animation';
            $class_column .= ' jws-ani-item';
       }
       
    
      $image_size = !empty($settings['blog_image_size']['width']) && !empty($settings['blog_image_size']['height']) ?  $settings['blog_image_size']['width'].'x'.$settings['blog_image_size']['height'] : 'full';
 

      ?>
 
		
		<div class="jws-blog-element<?php if($settings['post_nav_on'] == 'yes') echo esc_attr(' has-nav'); ?>">
            <?php if($settings['post_nav_on'] == 'yes') : 
                wp_enqueue_script('anime');
                $taxonomy_names = get_object_taxonomies('post');
                if((isset($settings['category_filter_rule']) && $settings['category_filter_rule'] != 'IN') && 'custom' === $settings['query_type']) {
                   $cats = get_categories(array(
                       'orderby' => 'name',
                       'exclude' => $settings['category_filter'],
                   ));  
                } elseif((isset($settings['category_filter_rule']) && $settings['category_filter_rule'] == 'IN') && 'custom' === $settings['query_type']) {
                   $cats = get_categories( array(
                       'orderby' => 'name',
                       'include' => $settings['category_filter'],
                    ));
                }else {
                    $cats  = get_categories( array(
                        'orderby' => 'name',
                        'parent'  => 0
                    ) );
                }
            ?>
            <div class="post_nav_container">
                <ul class="post_nav">
                    <li><a href="#" data-filter="*" class="filter-active"><?php echo (!empty($settings['post_text_first'])) ? $settings['post_text_first'] : esc_html__('All CATEGORIES', 'auriane'); ?></a></li>
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
            <div class="<?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?>>
                <?php 
                    if($settings['post_archive'] == 'yes') {
                       if (have_posts()) {
                            while ( have_posts() ) :
                        			the_post();
                                    $format = has_post_format() ? get_post_format() : 'no_format';  
                                        echo '<div class="'.$class_column.' '.$format.'">';
                         
                                               include( 'layout/'.$settings['blog_layouts'].'.php' );
                
            
                                        echo '</div>';
                            endwhile;    
                            wp_reset_postdata();      
                       }else{
                            get_template_part( 'template-parts/content/content', 'none' );
                       }
                    }elseif($settings['post_related'] == 'yes'){

                        $related = get_posts( array( 'category__in' => wp_get_post_categories($post->ID), 'numberposts' => 100,'post_type' => 'post', 'post__not_in' => array($post->ID) ) );
              
                        if( isset($related[0]) ) foreach( $related as $post ) {
                        setup_postdata($post); 
                            $format = has_post_format() ? get_post_format() : 'no_format'; 
                            echo '<div class="'.$class_column.' '.$format.'">';
                                               include( 'layout/'.$settings['blog_layouts'].'.php' );
                            echo '</div>';      
                        
                        }
                        wp_reset_postdata();     
                    }else {
                     $i = 1; if ($blog->have_posts()) :
                            while ( $blog->have_posts() ) :
                        			$blog->the_post();
                                    $class_slug = '';
                                    if($settings['post_nav_on'] == 'yes') {
                                        $item_cats = get_the_terms(get_the_ID(), 'category');
                                        if ($item_cats):
                                            foreach ($item_cats as $item_cat) {
                                                $class_slug .= ' '.$item_cat->slug;
                                            }
                                        endif;    
                                    }
                                    $format = has_post_format() ? get_post_format() : 'no_format'; 
                                 
                                    if($settings['blog_display'] == 'mixed') {
                                       if($i == 2 || $i == 3) {
                                         $mixed = ' true';
                                         $image_size = '612x410';
                                       }else {
                                         $mixed = ' false';
                                          $image_size = !empty($settings['blog_image_size']['width']) && !empty($settings['blog_image_size']['height']) ?  $settings['blog_image_size']['width'].'x'.$settings['blog_image_size']['height'] : 'full';
                                       }
                
                                       echo '<div class="'.$class_column.' '.$format.$class_slug.$mixed.'">';
                                               include( 'layout/'.$settings['blog_layouts'].'.php' );
                                        echo '</div>';  
                                    }else {
                                      echo '<div class="'.$class_column.' '.$format.$class_slug.'">';
                                               include( 'layout/'.$settings['blog_layouts'].'.php' );
                                      echo '</div>';  
                                    }        
                             if ($i == 3) {
                                  $i = 1;
                             } else {
                                  $i++;
                             } endwhile;    
                            wp_reset_postdata();
                           
                       endif;  
                    }
                     
                ?>
            </div>
            <?php if(isset($arrows) && $arrows == 'true') : ?>
                  <nav class="custom_navs">
                        <button class="nav_left"><span class="icon-arrow-left2"></span></button>
                        <button class="nav_right"><span class="icon-arrow-right2"></span></button>
                  </nav>
            <?php endif;
            
                global $wp_query;
                if($settings['post_archive'] == 'yes') { 
                   $number_page = $wp_query->max_num_pages;
                   $pagi_number = jws_query_pagination($wp_query);
                   $url = get_next_posts_page_link( $wp_query->max_num_pages );
                }else {
                   $number_page = $blog->max_num_pages;
                   $pagi_number = jws_query_pagination($blog);
                   $url = get_next_posts_page_link( $wp_query->max_num_pages ); 
                }
 
           ?>
            <?php if ( $number_page > 1 && $settings['pagination_type'] == 'load_more' ) {
                 wp_enqueue_script( 'masonry');
                 $load_attr = 'data-ajaxify-options=\'{"wrapper":".blog_ajax_'.$this->get_id().'","items":"> .jws_blog_item","trigger":"click"}\''; 
                ?>
                <div class="jws_pagination jws_ajax">
                    <a class="jws-load-more" data-ajaxify="true"  href="<?php echo add_query_arg( 'ajaxify', '1', $url); ?>" <?php echo wp_kses_post($load_attr); ?>>
                        <span class="has-loading"><?php echo esc_html($settings['pagination_loadmore_label']); ?></span>
                       <span class="has-loaded"><?php echo esc_html__('All items loaded ','auriane'); ?></span> 
                </a>
                </div>
            <?php }elseif($number_page > 1 && $settings['pagination_type'] == 'pagination-number'){
                      echo ''.$pagi_number;   
                  }  
            ?>
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