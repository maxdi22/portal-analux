<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use ELementor\Group_Control_Background;
use ELementor\Group_Control_Border;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Css_Filter;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Embed;
use Elementor\Utils;
use Elementor\Plugin;
use Elementor\Modules\DynamicTags\Module as TagsModule;

class Jws_Section extends Elementor\Element_Section {

	/**
	 * Section edit tools.
	 *
	 * Holds the section edit tools.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @var array Section edit tools.
	 */
	protected static $_edit_tools;

	/**
	 * Section predefined columns presets.
	 *
	 * Holds the predefined columns width for each columns count available by
	 * default by Elementor. Default is an empty array.
	 *
	 * Note that when the user creates a section he can define custom sizes for
	 * the columns. But Elementor sets default values for predefined columns.
	 *
	 * For example two columns 50% width each one, or three columns 33.33% each
	 * one. This property hold the data for those preset values.
	 *
	 * @since 1.0.0
	 * @access private
	 * @static
	 *
	 * @var array Section presets.
	 */
	private static $presets = [];

	/**
	 * Get default edit tools.
	 *
	 * Retrieve the section default edit tools. Used to set initial tools.
	 *
	 * @since 1.0.0
	 * @access protected
	 * @static
	 *
	 * @return array Default section edit tools.
	 */
	protected static function get_default_edit_tools() {
		$section_label = __( 'Section', 'auriane' );

		return [
			'duplicate' => [
				/* translators: %s: Section label */
				'title' => sprintf( __( 'Duplicate %s', 'auriane' ), $section_label ),
				'icon' => 'clone',
			],
			'add' => [
				/* translators: %s: Section label */
				'title' => sprintf( __( 'Add %s', 'auriane' ), $section_label ),
				'icon' => 'plus',
			],
			'save' => [
				/* translators: %s: Section label */
				'title' => sprintf( __( 'Save %s', 'auriane' ), $section_label ),
				'icon' => 'save',
			],
			'remove' => [
				/* translators: %s: Section label */
				'title' => sprintf( __( 'Remove %s', 'auriane' ), $section_label ),
				'icon' => 'close',
			],
		];
	}

	/**
	 * Get section name.
	 *
	 * Retrieve the section name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Section name.
	 */
	public function get_name() {
		return 'section';
	}

	/**
	 * Get section title.
	 *
	 * Retrieve the section title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Section title.
	 */
	public function get_title() {
		return __( 'Section', 'auriane' );
	}

	/**
	 * Get section icon.
	 *
	 * Retrieve the section icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Section icon.
	 */
	public function get_icon() {
		return 'eicon-columns';
	}

	/**
	 * Get presets.
	 *
	 * Retrieve a specific preset columns for a given columns count, or a list
	 * of all the preset if no parameters passed.
	 *
	 * @since 1.0.0
	 * @access public
	 * @static
	 *
	 * @param int $columns_count Optional. Columns count. Default is null.
	 * @param int $preset_index  Optional. Preset index. Default is null.
	 *
	 * @return array Section presets.
	 */
	public static function get_presets( $columns_count = null, $preset_index = null ) {
		if ( ! self::$presets ) {
			self::init_presets();
		}

		$presets = self::$presets;

		if ( null !== $columns_count ) {
			$presets = $presets[ $columns_count ];
		}

		if ( null !== $preset_index ) {
			$presets = $presets[ $preset_index ];
		}

		return $presets;
	}

	/**
	 * Initialize presets.
	 *
	 * Initializing the section presets and set the number of columns the
	 * section can have by default. For example a column can have two columns
	 * 50% width each one, or three columns 33.33% each one.
	 *
	 * Note that Elementor sections have default section presets but the user
	 * can set custom number of columns and define custom sizes for each column.

	 * @since 1.0.0
	 * @access public
	 * @static
	 */
	public static function init_presets() {
		$additional_presets = [
			2 => [
				[
					'preset' => [ 33, 66 ],
				],
				[
					'preset' => [ 66, 33 ],
				],
			],
			3 => [
				[
					'preset' => [ 25, 25, 50 ],
				],
				[
					'preset' => [ 50, 25, 25 ],
				],
				[
					'preset' => [ 25, 50, 25 ],
				],
				[
					'preset' => [ 16, 66, 16 ],
				],
			],
		];

		foreach ( range( 1, 10 ) as $columns_count ) {
			self::$presets[ $columns_count ] = [
				[
					'preset' => [],
				],
			];

			$preset_unit = floor( 1 / $columns_count * 100 );

			for ( $i = 0; $i < $columns_count; $i++ ) {
				self::$presets[ $columns_count ][0]['preset'][] = $preset_unit;
			}

			if ( ! empty( $additional_presets[ $columns_count ] ) ) {
				self::$presets[ $columns_count ] = array_merge( self::$presets[ $columns_count ], $additional_presets[ $columns_count ] );
			}

			foreach ( self::$presets[ $columns_count ] as $preset_index => & $preset ) {
				$preset['key'] = $columns_count . $preset_index;
			}
		}
	}

	/**
	 * Get initial config.
	 *
	 * Retrieve the current section initial configuration.
	 *
	 * Adds more configuration on top of the controls list, the tabs assigned to
	 * the control, element name, type, icon and more. This method also adds
	 * section presets.
	 *
	 * @since 1.0.10
	 * @access protected
	 *
	 * @return array The initial config.
	 */
	protected function get_initial_config() {
		$config = parent::get_initial_config();

		$config['presets'] = self::get_presets();

		return $config;
	}

	/**
	 * Register section controls.
	 *
	 * Used to add new controls to the section element.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {

	
        
        
        parent::register_controls();
        // Add JWS Controls
		$this->start_controls_section(
			'section_additional',
			array(
				'label' => esc_html__( 'Jws Settings', 'auriane' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
			)
		);

	   $this->add_control(
				'use_as',
				array(
					'type'    => Controls_Manager::SELECT,
					'label'   => esc_html__( 'Use Section For', 'auriane' ),
					'default' => '',
					'options' => array(
						''          => esc_html__( 'Default', 'auriane' ),
						'slider'    => esc_html__( 'Slider', 'auriane' ),
					),
				)
	   );
       
       $this->end_controls_section();
       
       $this->start_controls_section(
			'setting_navigation',
			[
				'label' => esc_html__( 'Setting Navigation', 'auriane' ),
				'tab'   => Controls_Manager::TAB_LAYOUT,
                'condition' => array(
						'use_as' => 'slider',
				),
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
				'nav_icon',
				[
					'label' => esc_html__( 'Nav Icon', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
		);
        
        $this->add_responsive_control(
			'nav_size',
			[
				'label' => esc_html__( 'Nav Size', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 90,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws_section_slider .jws-carousel-btn' => 'font-size: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
        $this->add_responsive_control(
			'nav_width_height',
			[
				'label' => esc_html__( 'Nav Width Height', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 90,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .jws_section_slider .jws-carousel-btn' => 'width: {{SIZE}}{{UNIT}}; height: {{SIZE}}{{UNIT}}; line-height: {{SIZE}}{{UNIT}};',
				],
				
			]
		);
        
        $this->add_control(
			'nav_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws_section_slider .jws-carousel-btn' => 'color: {{VALUE}};',
				],
			]
		);
        $this->add_control(
			'nav_color_hover',
			[
				'label' => esc_html__( 'Color Hover', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws_section_slider .jws-carousel-btn:hover' => 'color: {{VALUE}};',
				],
			]
		);
        
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'nav_border',
				'placeholder' => '1px',
				'default' => '1px',
				'selector' => '{{WRAPPER}} .jws_section_slider .jws-carousel-btn',
				'separator' => 'before',
                'condition'	=> [
						'enable_nav' => 'yes',
				],
			]
		);

		$this->add_control(
			'nav_border_radius',
			[
				'label' => esc_html__( 'Border Radius', 'auriane' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jws_section_slider .jws-carousel-btn' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
                'condition'	=> [
						'enable_nav' => 'yes',
				],
			]
		);
        
        $this->add_responsive_control(
			'nav_prev_space',
			[
				'label' => esc_html__( 'Nav Prev Space', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 90,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .jws_section_slider .prev-item' => 'left: {{SIZE}}{{UNIT}};',
				],
				
			]
		);
        
        $this->add_responsive_control(
			'nav_next_space',
			[
				'label' => esc_html__( 'Nav Next Space', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'max' => 90,
					],
				],
                'selectors' => [
					'{{WRAPPER}} .jws_section_slider .next-item' => 'right: {{SIZE}}{{UNIT}};',
				],
				
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
				'dots_align',
				[
					'label' 		=> esc_html__( 'Dots Align', 'auriane' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'options' 		=> [
						'left'    		=> [
							'title' 	=> esc_html__( 'Left', 'auriane' ),
							'icon' 		=> 'eicon-h-align-left',
						],
                        'center' => [
    						'title' => esc_html__( 'Center', 'auriane' ),
    						'icon'  => 'eicon-h-align-center',
					    ],
						'right' 		=> [
							'title' 	=> esc_html__( 'Right', 'auriane' ),
							'icon' 		=> 'eicon-h-align-right',
						],
					],
                    'prefix_class' => 'dots-align-',
					'frontend_available' => true,
                    'condition'	=> [
						'enable_dots' => 'yes',
				    ],
				]
		);

        $this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Dots Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .slider-dots-box .slider-dots button:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .slider-dots-box .slider-dots .slick-active button:after' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .slider-dots-box .slider-dots button:after' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .slider-dots-box .slider-dots button .circle-go' => 'stroke: {{VALUE}}',
				],
			]
		);
        
        $this->add_responsive_control(
			'dots_padding',
			[
				'label' => esc_html__( 'Dots Margin', 'auriane' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .jws_section_slider .slider-dots-box' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
				'separator' => 'before',
			]
		);
        
        $this->end_controls_section(); 
       
       $this->start_controls_section(
			'section_slider_options',
			[
				'label'     => esc_html__( 'Slider Options', 'auriane' ),
				'tab'      => Controls_Manager::TAB_LAYOUT,
                'condition' => array(
						'use_as' => 'slider',
				),
			]
		);


		$this->add_responsive_control(
			'slides_to_show',
			[
				'label'          => esc_html__( 'posts to Show', 'auriane' ),
				'type'           => Controls_Manager::NUMBER,
                'min' => 1,
			]
		);

		$this->add_responsive_control(
			'slides_to_scroll',
			[
				'label'          => esc_html__( 'posts to Scroll', 'auriane' ),
				'type'           => Controls_Manager::NUMBER,
                'min' => 1,
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
			'vertical',
			[
				'label'        => esc_html__( 'Vertical', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
			]
		);
        
        $this->add_control(
			'wheel',
			[
				'label'        => esc_html__( 'Wheel', 'auriane' ),
				'type'         => Controls_Manager::SWITCHER,
				'return_value' => 'yes',
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
			'fade',
			[
				'label'        => esc_html__( 'Fade', 'auriane' ),
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
					'{{WRAPPER}} .slick-slide' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .slick-list' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);
        
		$this->end_controls_section();
        
        /* adds advanced tab options */
		do_action( 'jws_elementor_add_common_options', $this );
        
       
	}


	/**
	 * Render section output in the editor.
	 *
	 * Used to generate the live preview, using a Backbone JavaScript template.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function content_template() {
	       
     
       
       
       
		?>
        <#
        let content_width = '';
		let extra_class = '';
		let extra_attrs = '';
		let wrapper_class = '';
		let wrapper_attrs = '';
        
            
        let data_slick = '';
        var class_layout = 'jws_section_'+settings.use_as;
        extra_class += class_layout;
        
        if ( 'slider' == settings.use_as ) { // if using as slider
         if ( 'yes' == settings.wheel ) { 
           extra_class += ' slick_wheel'; 
         }   
         var dots = settings.enable_dots == 'yes' ? 'true' : 'false';
         var arrows = settings.enable_nav == 'yes' ? 'true' : 'false';
         var autoplay_set  = settings.autoplay == 'yes' ? 'true' : 'false';
         var pause_on_hover  = settings.pause_on_hover == 'yes' ? 'true' : 'false';
         var infinite  = settings.infinite == 'yes' ? 'true' : 'false';  
         var variableWidth  = settings.variableWidth == 'yes' ? 'true' : 'false';
         var center  = settings.center == 'yes' ? 'true' : 'false';
         var fade  = settings.fade == 'yes' ? 'true' : 'false';
         var vertical  = settings.vertical == 'yes' ? 'true' : 'false';
        
         var slides_to_show = settings.slides_to_show ? settings.slides_to_show : '1';   
         var slides_to_show_tablet = settings.slides_to_show_tablet ? settings.slides_to_show_tablet : slides_to_show;
         var slides_to_show_mobile = settings.slides_to_show_mobile ? settings.slides_to_show_mobile : slides_to_show;
         
         var slides_to_scroll = settings.slides_to_scroll ? settings.slides_to_scroll : '1';
         var slides_to_scroll_tablet = settings.slides_to_scroll_tablet ? settings.slides_to_scroll_tablet : slides_to_scroll;
         var slides_to_scroll_mobile = settings.slides_to_scroll_mobile ? settings.slides_to_scroll_mobile : slides_to_scroll;
         
         var autoplay_speed = settings.autoplay_speed ? settings.autoplay_speed : '5000';
         
         extra_class += ' slick_'+settings.slides_to_show;    
      
		}   
        
        #>
        

		<div class="elementor-background-video-container elementor-hidden-phone">
			<div class="elementor-background-video-embed"></div>
			<video class="elementor-background-video-hosted" autoplay loop muted></video>
		</div>
		<div class="elementor-background-video-fallback"></div>
		<div class="elementor-background-overlay"></div>
		<div class="elementor-shape elementor-shape-top"></div>
		<div class="elementor-shape elementor-shape-bottom"></div>
        
        
        <# if ( 'slider' == settings.use_as ) { #>
        
            <div class="elementor-container elementor-column-gap-{{ settings.gap }} {{extra_class}}" data-slick='{  "slidesToShow": {{ slides_to_show }}, "slidesToScroll": {{slides_to_scroll}}, "autoplay": {{autoplay_set}},
            "draggable":false,"swipeToSlide":false,"arrows":{{arrows}},"dots":{{dots}},"autoplaySpeed":{{autoplay_speed}},"variableWidth":{{variableWidth}},"pauseOnHover":{{pause_on_hover}},"centerMode":{{center}},"fade":{{fade}},"vertical":{{vertical}},"infinite":false,"speed":{{settings.transition_speed}},
            "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": {{slides_to_show_tablet}} ,"slidesToScroll": {{slides_to_scroll_tablet}} }},
            {"breakpoint": 768,"settings":{"slidesToShow": {{slides_to_show_mobile}},"slidesToScroll": {{slides_to_scroll_mobile}} }}]   }'> 
        
        <# }else { #> 
        
            <div class="elementor-container elementor-column-gap-{{ settings.gap }} {{extra_class}}"> 
            
        <# } #> 
        
        <# if (dots) { #>
            <div class="slider-dots-box"></div>
        <# } #> 
        
           
        <# if (arrows) { 
            var icon_class = 'jws-icon-caret-right-thin';
            if ( '' != settings.nav_icon.value ) {
                
               icon_class = settings.nav_icon.value;
            
            }  #> 

            
            <div class="custom_navs">
                <span class="jws-carousel-btn prev-item nav_left"><i class="{{ icon_class }}"></i></span>
                <span class="jws-carousel-btn next-item nav_right"><i class="{{ icon_class }}"></i></span>
            </div>
        <# } #> 
        

            
		</div>
		<?php
	}

	/**
	 * Before section rendering.
	 *
	 * Used to add stuff before the section element.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function before_render() {
		$settings = $this->get_settings_for_display();
        $data_slick = '';
        $class_layout = 'jws_section_'.$settings['use_as'];
    
        if ( 'slider' === $settings['use_as'] ) { // if using as slider
        if ( 'yes' == $settings['wheel'] ) { 
           $class_layout .= ' slick_wheel'; 
         }  
	    $dots = ($settings['enable_dots'] == 'yes') ? 'true' : 'false';
        $arrows = ($settings['enable_nav'] == 'yes') ? 'true' : 'false';
        $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
        $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
        $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
        $variableWidth = ($settings['variablewidth'] == 'yes') ? 'true' : 'false';
        $center = ($settings['center'] == 'yes') ? 'true' : 'false';
        $fade = ($settings['fade'] == 'yes') ? 'true' : 'false';
        
        $vertical = ($settings['vertical'] == 'yes') ? 'true' : 'false';
        
        $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '1';
      
        $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
        $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
        
       
        
        $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
        $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
        $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
 
        
        $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
        $data_slick = ' data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"variableWidth":'.$variableWidth.',"pauseOnHover":'.$pause_on_hover.',"centerMode":'.$center.',"fade":'.$fade.',"vertical":'.$vertical.', "infinite":'.$infinite.',
        "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
        {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
		$class_layout .= ' slick_'.$settings['slides_to_show']; 

		} 
        

		?>
		<<?php echo esc_html( $this->get_html_tag() ); ?> <?php $this->print_render_attribute_string( '_wrapper' ); ?>>
			<?php
			if ( 'video' === $settings['background_background'] ) :
				if ( $settings['background_video_link'] ) :
					$video_properties = Embed::get_video_properties( $settings['background_video_link'] );
					?>
					<div class="elementor-background-video-container elementor-hidden-phone">
						<?php if ( $video_properties ) : ?>
							<div class="elementor-background-video-embed"></div>
						<?php else : ?>
							<video class="elementor-background-video-hosted elementor-html5-video" autoplay loop muted></video>
						<?php endif; ?>
					</div>
				<?php
				endif;
			endif;

			$has_background_overlay = (isset($settings['background_overlay_background']) && in_array( $settings['background_overlay_background'], [ 'classic', 'gradient' ], true )) || (isset($settings['background_overlay_hover_background']) &&  in_array( $settings['background_overlay_hover_background'], [ 'classic', 'gradient' ], true ));

			if ( $has_background_overlay ) :
			?>
				<div class="elementor-background-overlay"></div>
			<?php
			endif;

			if ( $settings['shape_divider_top'] ) {
				$this->print_shape_divider( 'top' );
			}

			if ( $settings['shape_divider_bottom'] ) {
				$this->print_shape_divider( 'bottom' );
			}
			?>
			<div class="elementor-container elementor-column-gap-<?php echo esc_attr( $settings['gap'] ); ?> <?php echo esc_attr($class_layout); ?>" <?php echo ''.$data_slick; ?>>
		<?php
	}

	/**
	 * After section rendering.
	 *
	 * Used to add stuff after the section element.
	 *
	 * @since 1.0.0
	 * @access public
	 */
	public function after_render() {
	    $settings = $this->get_settings_for_display();  
		?>
		    <?php if($settings['enable_dots'] == 'yes') echo '<div class="slider-dots-box"></div>';  ?>  
            
            <?php if($settings['enable_nav'] == 'yes') { ?>
                
             <?php  $icon_html = ( ! empty( $settings['nav_icon']['value'] ) ) ? '<i class="'.$settings['nav_icon']['value'].'"></i>'  : '<i class="jws-icon-caret-right-thin"></i>';  ?>

                <div class="custom_navs">
                    <span class="jws-carousel-btn prev-item nav_left"><?php echo ''.$icon_html; ?></span>
                    <span class="jws-carousel-btn next-item nav_right"><?php echo ''.$icon_html; ?></span>
                </div>
                
            <?php } ?>  
            
            	
			</div>
		</<?php echo esc_html( $this->get_html_tag() ); ?>>
		<?php
	}


}