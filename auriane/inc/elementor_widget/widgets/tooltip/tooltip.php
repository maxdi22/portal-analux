<?php
namespace Elementor;

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

use Elementor\Core\Schemes;

/**
 * Elementor icon list widget.
 *
 * Elementor widget that displays a bullet list with any chosen icons and texts.
 *
 * @since 1.0.0
 */
class Tooltip extends Widget_Base {

	/**
	 * Get widget name.
	 *
	 * Retrieve icon list widget name.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'tooltip';
	}

	/**
	 * Get widget title.
	 *
	 * Retrieve icon list widget title.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Jws Tooltip', 'auriane' );
	}

	/**
	 * Get widget icon.
	 *
	 * Retrieve icon list widget icon.
	 *
	 * @since 1.0.0
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-bullet-list';
	}

	/**
	 * Get widget keywords.
	 *
	 * Retrieve the list of keywords the widget belongs to.
	 *
	 * @since 2.1.0
	 * @access public
	 *
	 * @return array Widget keywords.
	 */
	public function get_keywords() {
		return [ 'popup', 'icon', 'tooltip' ];
	}

	/**
	 * Register icon list widget controls.
	 *
	 * Adds different input fields to allow the user to change and customize the widget settings.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function register_controls() {
		$this->start_controls_section(
			'section_icon',
			[
				'label' => esc_html__( 'Tooltip', 'auriane' ),
			]
		);
        $this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image Tooltip', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);
        $this->add_group_control(
    			\Elementor\Group_Control_Image_Size::get_type(),
    			[
    				'name' => 'image',
    				'default' => 'large',
    			]
    	 );
		$repeater = new Repeater();

        
        $repeater->add_control('inc_product_ids', [
                'label' => esc_html__('Include product IDs', 'auriane'),
                'description' => esc_html__('', 'auriane'),
                'type' => Controls_Manager::SELECT2,
                'multiple' => false,
                'options' => $this->get_list_posts('product'),
        ]);
		
          $repeater->add_responsive_control(
            'pos_x', [
                'label' => esc_html__('Position X (%)', 'auriane'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}" => 'left: {{SIZE}}{{UNIT}}',
                ],

            ]
        );
        
         $repeater->add_responsive_control(
            'pos_y', [
                'label' => esc_html__('Position Y (%)', 'auriane'),
                'type' => Controls_Manager::SLIDER,
                'size_units' => ['%','px'],
                'range' => [
                    '%' => [
                        'min' => -100,
                        'max' => 100,
                    ],
                    'px' => [
                        'min' => -1000,
                        'max' => 1000,
                        'step' => 1,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 10,
                ],
                'selectors' => [
                    "{{WRAPPER}} {{CURRENT_ITEM}}" => 'top: {{SIZE}}{{UNIT}}',
                ],

            ]
        );

		$this->add_control(
			'icon_list',
			[
				'label' => '',
				'type' => Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'text' => esc_html__( 'List Item #1', 'auriane' ),
					
					],
				],

			]
		);
        
       

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_list',
			[
				'label' => esc_html__( 'List', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);


		$this->add_responsive_control(
			'icon_align',
			[
				'label' => esc_html__( 'Alignment', 'auriane' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'prefix_class' => 'elementor%s-align-',
			]
		);
        
        $this->add_responsive_control(
			'image_width',
			[
				'label' => esc_html__( 'Image Map Width', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'%' => [
						'min' => 1,
                        'max' => 100,
 					],
				],
				'selectors' => [
					'{{WRAPPER}} .jws-tooltip-list > img' => 'width: {{SIZE}}%;',
				],
			]
		);    

		$this->end_controls_section();

		$this->start_controls_section(
			'section_icon_style',
			[
				'label' => esc_html__( 'Icon', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'icon_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-tooltip-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-tooltip-list-icon svg' => 'fill: {{VALUE}};',
				],				
			]
		);

		$this->add_control(
			'icon_color_hover',
			[
				'label' => esc_html__( 'Hover', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-tooltip-list-item:hover .elementor-tooltip-list-icon i' => 'color: {{VALUE}};',
					'{{WRAPPER}} .elementor-tooltip-list-item:hover .elementor-tooltip-list-icon svg' => 'fill: {{VALUE}};',
				],
			]
		);
        
        $this->add_control(
			'icon_bgcolor',
			[
				'label' => esc_html__( 'Background Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .jws-tooltip-list .elementor-tooltip-list-icon' => 'background: {{VALUE}};',
				],
			]
		);

		$this->add_responsive_control(
			'icon_size',
			[
				'label' => esc_html__( 'Size', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'default' => [
					'size' => 14,
				],
				'range' => [
					'px' => [
						'min' => 6,
					],
				],
				'selectors' => [
					'{{WRAPPER}} .elementor-tooltip-list-icon i' => 'font-size: {{SIZE}}{{UNIT}};',
					'{{WRAPPER}} .elementor-tooltip-list-icon svg' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);

          $this->add_responsive_control(
					'icon_self_padding',
					[
						'label' 		=> esc_html__( 'Padding', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-tooltip-list .elementor-tooltip-list-icon' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
        $this->add_responsive_control(
					'icon_self_margin',
					[
						'label' 		=> esc_html__( 'Margin', 'auriane' ),
						'type' 			=> Controls_Manager::DIMENSIONS,
						'size_units' 	=> [ 'px', 'em', '%' ],
						'selectors' 	=> [
							'{{WRAPPER}} .jws-tooltip-list .elementor-tooltip-list-icon' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
						],
					]
		);
		$this->end_controls_section();

		$this->start_controls_section(
			'section_text_style',
			[
				'label' => esc_html__( 'Text', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);

		$this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Text Color', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-icon-list-text' => 'color: {{VALUE}};',
				],				
			]
		);

		$this->add_control(
			'text_bgcolor',
			[
				'label' => esc_html__( 'Background', 'auriane' ),
				'type' => Controls_Manager::COLOR,
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-tooltip-list-item .elementor-icon-list-text' => 'background: {{VALUE}};',
                    '{{WRAPPER}} .elementor-tooltip-list-item .elementor-icon-list-text:after' => 'border-top-color: {{VALUE}};',
                 
				],
			]
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'icon_typography',
				'selector' => '{{WRAPPER}} .elementor-tooltip-list-item .elementor-icon-list-text',
				
			]
		);
        	$this->add_responsive_control(
			'text_padding',
			[
				'label' => esc_html__( 'Padding', 'auriane' ),
				'type' => Controls_Manager::DIMENSIONS,
				'size_units' => [ 'px', 'em', '%' ],
				'selectors' => [
					'{{WRAPPER}} .elementor-tooltip-list-item .elementor-icon-list-text' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				],
			]
		);
        $this->add_responsive_control(
			'text_width',
			[
				'label' => esc_html__( 'Width', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'range' => [
					'px' => [
						'min' => 100,
                        'max' => 1000,
 					],
				],
				'selectors' => [
					'{{WRAPPER}} .tooltip-content' => 'width: {{SIZE}}{{UNIT}};',
				],
			]
		);
    
		$this->add_responsive_control(
			'icon_self_align',
			[
				'label' => esc_html__( 'Alignment', 'auriane' ),
				'type' => Controls_Manager::CHOOSE,
				'options' => [
					'left' => [
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon' => 'eicon-h-align-left',
					],
					'center' => [
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon' => 'eicon-h-align-center',
					],
					'right' => [
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon' => 'eicon-h-align-right',
					],
				],
				'default' => '',
				'selectors' => [
					'{{WRAPPER}} .elementor-tooltip-list-item .elementor-icon-list-text' => 'text-align: {{VALUE}};',
				],
			]
		);
		$this->end_controls_section();
	}
    
     protected function get_list_posts($post_type = 'post')
     {
            $args = array(
                'post_type'        => $post_type,
                'suppress_filters' => true,
                'posts_per_page'   => 300,
                'no_found_rows'    => true,
            );
    
            $the_query = new \WP_Query($args);
            $results   = [];
    
            if (is_array($the_query->posts) && count($the_query->posts)) {
                foreach ($the_query->posts as $post) {
                    $results[ $post->ID ] = sanitize_text_field($post->post_title);
                }
            }
    
            return $results;
     }

	/**
	 * Render icon list widget output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 1.0.0
	 * @access protected
	 */
	protected function render() {
		$settings = $this->get_settings_for_display();

		$this->add_render_attribute( 'icon_list', 'class', 'elementor-tooltip-list-items ct_ul_ol' );

		?>
        <div class="jws-tooltip-list">
            <?php 
                   if (function_exists('jws_getImageBySize')) {
                         $attach_id = $settings['image']['id'];
                         if($settings['image_size'] == 'custom')  {
                            $image_size = $settings['image_custom_dimension']['width'].'x'.$settings['image_custom_dimension']['height']; 
                         }else{
                            $image_size = $settings['image_size'];
                         }  
                         $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'services-image'));
                         echo ''.$img['thumbnail'];
                       
                
                  }
            ?>
    		<ul <?php echo ''.$this->get_render_attribute_string( 'icon_list' ); ?>>
    			<?php
                $i = 1;
    			foreach ( $settings['icon_list'] as $index => $item ) :
    				$repeater_setting_key = $this->get_repeater_setting_key( 'text', 'icon_list', $index );
    
    				$this->add_render_attribute( $repeater_setting_key, 'class', 'elementor-icon-list-text' );
    
    				$this->add_inline_editing_attributes( $repeater_setting_key );
    				$migration_allowed = Icons_Manager::is_migration_allowed();
                    $item_key = 'item_' . $index;
                    
                    $this->add_render_attribute($item_key, 'class', array('elementor-tooltip-list-item','elementor-repeater-item-'.esc_attr($item['_id']).'') );
       
    				?>
    				<li <?php echo ''.$this->get_render_attribute_string( $item_key ); ?>>
                        <button><span><?php echo esc_html($i); ?></span></button>
    					<div class="tooltip-content">
                            <?php  
                                    if(!empty($item['inc_product_ids'])) {
                                          $args = array(
                                            'post_type' => 'product',
                                            'post_status' => 'publish',
                                            'p' => $item['inc_product_ids'],   // id of the post you want to query
                                            );
                                            $my_posts = new \WP_Query($args);  
                                        
                                           if($my_posts->have_posts()) : 
                                        
                                                while ( $my_posts->have_posts() ) : $my_posts->the_post(); 
                                                 echo '<div class="product-item product">';     
                                                    wc_get_template_part( 'archive-layout/content-mapper');
                                                 echo '</div>';
                                                endwhile; //end the while loop
                                                wp_reset_postdata();
                                           endif; // end of the loop.   
                                    } else {
                                        echo esc_html__('No product','auriane');
                                    }       
                            ?>
                            
                        </div>
    			
    				</li>
    				<?php
    			$i++; endforeach;
    			?>
    		</ul>
        </div>
		<?php
	}

	/**
	 * Render icon list widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 2.9.0
	 * @access protected
	 */
	protected function content_template() {

	}

	public function on_import( $element ) {
		return Icons_Manager::on_import_migration( $element, 'icon', 'selected_icon', true );
	}
}
