<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Group_Control_Image_Size;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Jws_Shop_Video extends Widget_Base {

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
		return 'jws_shop_video';
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
		return esc_html__( 'Jws Product Video', 'auriane' );
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
		return 'eicon-banner';
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
        public function get_script_depends() {
		return [ 'owl-carousel','jws-woocommerce' , 'magnificPopup'];
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
			'setting_section_list',
			[
				'label' => esc_html__( 'List', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   

        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
                'media_types' => [ 'video' ],
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);   
        $repeater->add_control('inc_product_ids', [
					'label' => esc_html__('Include product IDs', 'auriane'),
					'description' => esc_html__('', 'auriane'),
					'type' => Controls_Manager::SELECT2,
					'multiple' => false,
					'options' => $this->get_list_posts('product'),
			]);


        
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'List', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),

				'title_field' => '{{{ image }}}',
			]
		);
      $this->end_controls_section();

      $this->start_controls_section(
			'box_style',
			[
				'label' => esc_html__( 'Style', 'auriane' ),
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
					'{{WRAPPER}} .slick-slide' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .slick-list' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);
        $this->add_responsive_control('product_pad',
            [
                'label'         => esc_html__('Product Padding', 'auriane'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .product-item ' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );        
        $this->add_responsive_control('product_mar',
            [
                'label'         => esc_html__('Product Margin', 'auriane'),
                'type'          => Controls_Manager::DIMENSIONS,
                'size_units'    => [ 'px', 'em', '%' ],
                'selectors'     => [
                    '{{WRAPPER}} .product-item' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}}',
                ]
            ]
        );
		$this->add_control(
				'box_size',
				[
					'label'     => esc_html__( 'Box Style', 'auriane' ),
					'type'      => Controls_Manager::HEADING,
					'separator' => 'before',
				]
			);  
			$this->add_responsive_control(
				'banner_height',
				array(
					'label'      => esc_html__( 'Box Height', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px', '%', 'em', 'rem', 'custom' ],
					'range' => [
						'px' => [
							'min' => 0,
							'max' => 1000,
							'step' => 5,
						],
						'%' => [
							'min' => 0,
							'max' => 100,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .jws-product-inner ' => 'min-height: {{SIZE}}{{UNIT}};',
                        
					],
			
				)
			);  
  
                       
        $this->end_controls_section();
				$this->start_controls_section(
					'section_slider_options',
					[
						'label'     => esc_html__( 'Slider Options', 'auriane' ),
						'type'      => Controls_Manager::SECTION,
						
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
					'transition_speed',
					[
						'label'     => esc_html__( 'Transition Speed (ms)', 'auriane' ),
						'type'      => Controls_Manager::NUMBER,
						'default'   => 500,
					]
				);
				$this->end_controls_section();
       
        

         

	}
     protected function get_list_posts($post_type = 'product')
     {
            $args = array(
                'post_type'        => $post_type,
                'suppress_filters' => true,
                'posts_per_page'   => -1,
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
       

        
        
          
          $class_row = 'jws-products-slider '; 
					$class_row .= ' slider';
					$class_column = ' jws-products-banner-item slider-item slick-slide'; 
					$dots = ($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') ? 'true' : 'false';
					$arrows = ($settings['navigation'] == 'arrows' || $settings['navigation'] == 'both') ? 'true' : 'false';
					$autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
					$pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
					$infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
					$autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
					
					$settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '3';
					$settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
					$settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
					$settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
					$settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
					$settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
					
					$data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"pauseOnHover":'.$pause_on_hover.',"infinite":'.$infinite.',
					"speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
					{"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
          

         ?>
         <div class="jws-products-slider-element">  
            <div class="<?php echo esc_attr($class_row); ?> "  <?php echo ''.$data_slick; ?> data-banner="jws-custom-<?php echo esc_attr($this->get_id()); ?>"> 
							<?php  
								foreach (  $settings['list'] as $index => $item ) {
								    $img_atts = $item['image']['url'] ;								    
                                    echo '<div class="'.esc_attr($class_column).'" >
										<div class="jws-product-inner" >';  
                                  
                                             echo '<video loop muted playsinline autoplay poster="" src="'.$img_atts.'"></video>';            
    						                  
                                                                                           
                            	if(!empty($item['inc_product_ids'])) {
    							 ?>
    							<a href="javascript:void(0);" data-product_video="<?php echo $img_atts;?>" data-product_id="<?php echo $item['inc_product_ids'];?>" class="popup_view"></a>
									<?php
										$args = array(
										'post_type' => 'product',
										'post_status' => 'publish',
										'post__in' => array( intval($item['inc_product_ids']) ), 
                                        'posts_per_page' => 1,  // id of the post you want to query
										);
                                      
										$products = new \WP_Query($args);  
					                	
                                                                     
										if($products->have_posts()) :
							
												while ( $products->have_posts() ) : 
                                                $products->the_post(); 
													echo '<div class="product-item">';     
														wc_get_template_part( 'archive-layout/content-layout5');
													echo '</div>';
												endwhile; //end the while loop
												wp_reset_postdata();
										endif; // end of the loop.   
                            
    							} else {
    									echo esc_html__('No product','auriane');
    							}  
                echo '</div></div>';              
							}   
							?>             
         </div> 
          <?php if($arrows == 'true'|| $settings['navigation'] == 'both') : ?>
             <nav class="jws-banner-nav">
                   <span class="prev-item jws-carousel-btn"><span class="jws-icon-caret-left-thin"></span></span>
                    <span class="next-item jws-carousel-btn"><span class="jws-icon-caret-right-thin"></span></span>
              </nav>
            <?php endif; ?>  
         <?php if($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') : ?><div class="custom_dots"></div><?php endif; ?>
            
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