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
class Jws_Gallery extends Widget_Base {

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
		return 'jws_gallery';
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
		return esc_html__( 'Jws Gallery', 'auriane' );
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
        
        
        if(isset($jws_option['gallery_category']) && !empty($jws_option['gallery_category'])) {
          
    
      
            $tabsok = array();
            foreach (  $jws_option['gallery_category'] as $index => $item_tabs ) { 
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
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);
        $this->add_control(
				'gallery_display',
				[
					'label'     => esc_html__( 'Display', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'grid',
					'options'   => [
						'grid'   => esc_html__( 'Grid', 'auriane' ),
                        'metro'   => esc_html__( 'Metro', 'auriane' ),
						'slider'   => esc_html__( 'Slider', 'auriane' ),
					],
                    
				]
		);
        $this->add_control(
				'link_action',
				[
					'label'     => esc_html__( 'Link Action', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'lightbox',
					'options'   => [
						'lightbox'   => esc_html__( 'Light Box', 'auriane' ),
                        'linkurl'   => esc_html__( 'Link Href', 'auriane' ),
					],
                    
				]
	     );
        $this->add_control(
			'gallery_layout',
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
        $this->add_responsive_control(
				'gallery_columns',
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
        $this->add_control(
				'image_size',
				[
					'label'     => esc_html__( 'Image Size', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '770x450',
				]
		);
        $this->add_control(
				'image_size2',
				[
					'label'     => esc_html__( 'Image Size 2', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '770x450',
                    'condition'	=> [
						'gallery_display' => ['metro'],
				    ],
				]
		);
        $this->add_control(
				'image_size3',
				[
					'label'     => esc_html__( 'Image Size 3', 'auriane' ),
					'type'      => Controls_Manager::TEXT,
					'default'   => '770x450',
                    'condition'	=> [
						'gallery_display' => ['metro'],
				    ],
				]
		);
        $this->end_controls_section(); 
        $this->start_controls_section(
			'setting_section_tabs_list',
			[
				'label' => esc_html__( 'Tabs List', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		); 
        $this->add_control(
			'tabs_list',
			[
				'label' => __( 'Show Tabs', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
	            'multiple' => true,   
				'options' => $this->get_tabs_list(),

			]
		);
        $this->end_controls_section();  
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'gallery List', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
        
        $repeater->add_control(
			'link_url',
			[
				'label' => esc_html__( 'Link', 'auriane' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'auriane' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
				],
			]
		);
       $repeater->add_control(
			'show_elements',
			[
				'label' => __( 'Show Elements', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT2,
	            'multiple' => true,   
				'options' => $this->get_tabs_list(),
				'default' => [ 'title', 'description' ],
			]
		);
       $repeater->add_responsive_control(
			'variable_width',
			[
				'label' => __( 'variable Width', 'auriane' ),
				'type' => Controls_Manager::SLIDER,
				'size_units' => [ 'px', '%' ],
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
					'{{WRAPPER}} {{CURRENT_ITEM}} .jws_gallery_image' => 'max-width: {{SIZE}}{{UNIT}};',
				],
			]
		);
        
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
			'nav_position',
			[
				'label'     => esc_html__( 'Nav Position', 'auriane' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'out',
				'options'   => [
                    'out' => esc_html__( 'Out Side', 'auriane' ),
					'in' => esc_html__( 'In Side', 'auriane' ),
				],
                'condition'	=> [
					'enable_nav' => 'yes',
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
			'dots_position',
			[
				'label'     => esc_html__( 'Dots Position', 'auriane' ),
				'type'      => Controls_Manager::SELECT,
				'default'   => 'out',
				'options'   => [
                    'out' => esc_html__( 'Out Side', 'auriane' ),
					'in' => esc_html__( 'In Side', 'auriane' ),
				],
                'condition'	=> [
				    'enable_dots' => 'yes',
				],
			]
		);
        $this->add_control(
			'dots_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .jws_gallery_element .jws_gallery .flickity-page-dots li.is-selected' => 'border-color: {{VALUE}}',
                    '{{WRAPPER}} .jws_gallery_element .jws_gallery .flickity-page-dots li:before' => 'background: {{VALUE}}',
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
					'{{WRAPPER}} .jws_gallery .jws_gallery_item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws_gallery.row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .jws_gallery .jws_gallery_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
			]
		);
        $this->end_controls_section();
        	$this->start_controls_section(
			'section_slider_options',
			[
				'label'     => esc_html__( 'Slider Options', 'auriane' ),
				'type'      => Controls_Manager::SECTION,
				'condition' => [
					'gallery_display' => ['slider'],
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
        
          $class_column = 'jws_gallery_item';

          $class_row = 'jws_gallery gallery row '.$settings['gallery_layout']; 
          $class_row .= ' '.$settings['gallery_display']; 
          
          $class_row .= ' dots-'.$settings['dots_position'];
          $class_row .= ' navs-'.$settings['nav_position'];
          
          
          if($settings['gallery_display'] != 'slider') {
              $class_column .= ' col-xl-'.$settings['gallery_columns'].'';
              $class_column .= (!empty($settings['gallery_columns_tablet'])) ? ' col-lg-'.$settings['gallery_columns_tablet'].'' : ' col-lg-'.$settings['gallery_columns'].'' ;
              $class_column .= (!empty($settings['gallery_columns_mobile'])) ? ' col-'.$settings['gallery_columns_mobile'].'' :  ' col-'.$settings['gallery_columns'].'';
          }else{
            $class_column .= '';
          }   
              
          
          if($settings['gallery_display'] == 'slider') {
                $class_row .= ' jws-slider';
                $class_column .= ' slider-item slick-slide'; 
                $dots = ($settings['enable_dots'] == 'yes') ? 'true' : 'false';
                $arrows = ($settings['enable_nav'] == 'yes') ? 'true' : 'false';
                $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
                $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
                $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
                $variableWidth = ($settings['variablewidth'] == 'yes') ? 'true' : 'false';
                $center = ($settings['center'] == 'yes') ? 'true' : 'false';
                
                $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '3';
                $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
                $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
                $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
                $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
                $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
                
                
                $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
                $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"variableWidth":'.$variableWidth.',"pauseOnHover":'.$pause_on_hover.',"centerMode":'.$center.', "infinite":'.$infinite.',
                "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
                {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
           }else {
                $data_slick = '';   
           }

 
         ?>
         <div class="jws_gallery_element">
         <?php if(!empty($settings['tabs_list'])) : ?>
         <div class="gallery_tabs">
            <ul>
                 <?php
                    
                        echo '<li><a data-filter="*" class="filter-active" href="#">'.esc_html__('ALL','auriane').'</a></li>';   
                        foreach (  $settings['tabs_list'] as $index => $item_tabs ) { 
                            
                          echo '<li><a data-filter=".'.preg_replace('/[^a-zA-Z]+/', '', $item_tabs).'" href="#">'.$item_tabs.'</a></li>';   
                       
                        };   
                     
                 ?>  
            </ul>
         </div>
         <?php endif; ?>
         <?php if(isset($settings['enable_nav']) && $settings['enable_nav'] == 'yes') : ?>
                  <nav class="custom_navs">
                        <button class="nav_left"><span class="jws-icon-arrow_carrot-left"></span></button>
                        <button class="nav_right"><span class="jws-icon-arrow_carrot-right"></span></button>
                  </nav>
            <?php endif; ?>  
         
         
         
            <div class="<?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?> data-gallery="jws-custom-<?php echo esc_attr($this->get_id()); ?>">
                <?php if($settings['gallery_display'] == 'metro') { ?> <div class="grid-sizer col-xl-3 col-lg-3 col-3"></div> <?php } ?>
                <?php $i = 1; $n = 0; foreach (  $settings['list'] as $index => $item ) {
                 $link_key = 'link' . $index; 
                 
           
                
                 if($settings['link_action'] == 'linkurl') {
                   if($item['link_url']['is_external']) $this->add_render_attribute( $link_key, 'rel',  'nofollow' );
                   if($item['link_url']['nofollow']) $this->add_render_attribute( $link_key, 'target',  '_blank' );  
                   $this->add_render_attribute( $link_key, 'href',  $item['link_url']['url'] ); 
                 }else {
                   $this->add_render_attribute( $link_key, 'class', 'jws-popup-global' );
                   
                        $this->add_render_attribute( $link_key, 'href',  $item['image']['url'] );  
                   
                 }
 
                 if($settings['gallery_display'] == 'metro') {
                                        
                                       if($i == '4') {
                                          $class_column = 'jws_gallery_item col-xl-6 col-lg-6'; 
                                          $image_size = $settings['image_size3'];
                                        
                                        }elseif($i == '3') {
                                          $class_column = 'jws_gallery_item col-xl-6 col-lg-6'; 
                                          $image_size = $settings['image_size2'];
                                        }else {
                                           $image_size = $settings['image_size'];
                                           $class_column = 'jws_gallery_item col-xl-3 col-lg-3'; 
                                        }  
                                        
                                        
                                        if ($i == 4) {
                                            $i = 1;
                                        } else {
                                            $i++;
                                        }
                                        
                  }else {
                    $image_size = $settings['image_size'];
                  }
           
                
                 $cat2 = ' ';    
                 if(!empty($item['show_elements'])) {
                   foreach($item['show_elements'] as $cat)  {
                           $cat2 .=  ' '.$cat;
                      
                   }
                
                 }

                 $attach_id = $item['image']['id'];
                  $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                    ?>
                        <div class="elementor-repeater-item-<?php echo esc_attr($item['_id']); ?> <?php echo esc_attr($class_column.$cat2); ?>" <?php if($settings['link_action'] == 'lightbox') : ?>  data-gallery-image data-gallery-item="<?php echo esc_attr($n);?>" <?php endif; ?>>
                        <div class="jws_gallery_image">
                            <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
                                <?php if($settings['link_action'] == 'lightbox') {?> <span class="jws-icon-icon_plus"></span> <?php } ?>
                                <img class="thumbnail-popup" src="<?php echo esc_attr($img['p_img_large'][0]); ?>" class="jws-icon-icon_plus">
                            </a>    
                            <?php if($settings['link_action'] == 'lightbox') {
                                echo ''.$img['thumbnail'];
                            } ?>
                        </div>
                    </div>
                <?php $n++; } ?>
            </div>
            <?php if($settings['link_action'] == 'lightbox') : ?>
             <div class="jws-gallery-opened-sc clb-popup clb-gallery-lightbox" id="jws-custom-<?php echo esc_attr($this->get_id()); ?>">
            	<div class="close close-bar">
            	    <div class="clb-close btn-round round-animation circle-animation">
            	        <i class="jws-icon-icon_close"></i>
            	    </div>
            	    <div class="expand btn-round round-animation circle-animation vc_hidden-xs">
            	        <i class="jws-icon-arrow_expand"></i>
            	    </div>
            	</div>
                <div class="clb-popup-holder"></div>
               <nav class="custom-navs">
                    <button class="nav_left"><span class="jws-icon-arrow_carrot-left"></span></button>
                    <button class="nav_right"><span class="jws-icon-arrow_carrot-right"></span></button>
              </nav>
            </div>
            <?php endif; ?>
            
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