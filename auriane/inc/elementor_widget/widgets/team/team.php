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
class Jws_Team extends Widget_Base {

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
		return 'jws_team';
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
		return esc_html__( 'Jws Team', 'auriane' );
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
		return 'eicon-posts-group';
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
				'team_display',
				[
					'label'     => esc_html__( 'Display', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'grid',
					'options'   => [
						'grid'   => esc_html__( 'Grid', 'auriane' ),
						'slider'   => esc_html__( 'Slider', 'auriane' ),
                        '3d_slider'   => esc_html__( '3d Slider', 'auriane' ),
					],
                    
				]
			);
        $this->add_control(
			'team_layout',
			[
				'label' => esc_html__( 'Layout', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout1',
				'options' => [
					'layout1'  => esc_html__( 'Layout 1', 'auriane' ),
                    'layout2'  => esc_html__( 'Layout 2', 'auriane' ),
                    'layout3'  => esc_html__( 'Layout 3', 'auriane' ),
                    'layout4'  => esc_html__( 'Layout 4', 'auriane' ),
                    'layout5'  => esc_html__( 'Layout 5', 'auriane' ),
                    'layout6'  => esc_html__( 'Layout 6', 'auriane' ),
                    'layout7'  => esc_html__( 'Layout 7', 'auriane' ),
                    'layout8'  => esc_html__( 'Layout 8', 'auriane' ),
				],
			]
		);
        $this->add_responsive_control(
				'team_columns',
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
			'team_show_add_team',
			[
				'label' => esc_html__( 'Show Add To Team', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'Show', 'auriane' ),
				'label_off' => esc_html__( 'Hide', 'auriane' ),
				'return_value' => 'yes',
			]
		);
        $this->add_control(
			'team_title_add_team',
			[
				'label' => esc_html__( 'Text Add To Team', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Want to Work With Us?', 'auriane' ),
                'condition'	=> [
					'team_show_add_team' => ['yes'],
				 ],
			]
		);
        $this->add_control(
			'team_url_add_team',
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
        $this->end_controls_section();   
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'Team List', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);   
        $repeater = new \Elementor\Repeater();

        $repeater->add_control(
			'team_url',
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
			'image',
			[
				'label' => esc_html__( 'Choose Avatar', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
			]
		);
        $repeater->add_group_control(
			Group_Control_Image_Size::get_type(),
			[
				'name' => 'image', // Usage: `{name}_size` and `{name}_custom_dimension`, in this case `image_size` and `image_custom_dimension`.
				'default' => 'large',
				'separator' => 'none',
			]
		);
        $repeater->add_control(
			'team_content',
			[
				'label' => esc_html__( 'Content', 'auriane' ),
				'type' => \Elementor\Controls_Manager::HEADING,
				'separator' => 'before',
			]
		);
        $repeater->add_control(
			'team_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title', 'auriane' ),
				'placeholder' => esc_html__( 'Type your title here', 'auriane' ),
			]
		);
        $repeater->add_control(
			'team_job',
			[
				'label' => esc_html__( 'Job', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title', 'auriane' ),
				'placeholder' => esc_html__( 'Type your title here', 'auriane' ),
			]
		);
        $repeater->start_controls_tabs(
        	'style_tabs'
        );
        $repeater->start_controls_tab(
        	'icon_tab',
        	[
        		'label' => esc_html__( 'Icon 1', 'auriane' ),
        	]
        );
        $repeater->add_control(
				'team_icon1',
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
			'team_icon_url1',
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
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
        	'icon_tab2',
        	[
        		'label' => esc_html__( 'Icon 2', 'auriane' ),
        	]
        );
        $repeater->add_control(
				'team_icon2',
				[
					'label' => esc_html__( 'Icon 2', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fab fa-wordpress',
                		'library' => 'fa-brands',
					],
				]
		);
        $repeater->add_control(
			'team_icon_url2',
			[
				'label' => esc_html__( 'Link 2', 'auriane' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'auriane' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
				],
			]
		);
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
        	'icon_tab3',
        	[
        		'label' => esc_html__( 'Icon 3', 'auriane' ),
        	]
        );
        $repeater->add_control(
				'team_icon3',
				[
					'label' => esc_html__( 'Icon 3', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fab fa-wordpress',
                		'library' => 'fa-brands',
					],
				]
		);
        $repeater->add_control(
			'team_icon_url3',
			[
				'label' => esc_html__( 'Link 3', 'auriane' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'auriane' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
				],
			]
		);
        $repeater->end_controls_tab();
        $repeater->start_controls_tab(
        	'icon_tab4',
        	[
        		'label' => esc_html__( 'Icon 4', 'auriane' ),
        	]
        );
        $repeater->add_control(
				'team_icon4',
				[
					'label' => esc_html__( 'Icon 4', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
					'default' => [
						'value' => 'fab fa-wordpress',
                		'library' => 'fa-brands',
					],
				]
		);
        $repeater->add_control(
			'team_icon_url4',
			[
				'label' => esc_html__( 'Link 4', 'auriane' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'auriane' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
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
				'default' => [
					[
						'team_title' => esc_html__( 'Name #1', 'auriane' ),
					],
				],
				'title_field' => '{{{ team_title }}}',
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
					'{{WRAPPER}} .jws_team .jws_team_item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws_team.row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .jws_team .jws_team_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
					'team_display' => ['slider'],
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
          $class_column = 'jws_team_item';
          if($settings['team_layout'] == 'layout5' || $settings['team_layout'] == 'layout7') {
            wp_enqueue_script('anime');
          }
          if($settings['team_display'] != 'slider') {
              wp_enqueue_script('isotope');  
              $class_column .= ' col-xl-'.$settings['team_columns'].'';
              $class_column .= (!empty($settings['team_columns_tablet'])) ? ' col-lg-'.$settings['team_columns_tablet'].'' : ' col-lg-'.$settings['team_columns'].'' ;
              $class_column .= (!empty($settings['team_columns_mobile'])) ? ' col-'.$settings['team_columns_mobile'].'' :  ' col-'.$settings['team_columns'].''; 
          }
          $class_row = 'jws_team row '.$settings['team_layout'];  
          if($settings['team_display'] == '3d_slider') {
            $class_row .= ' team-3d-slider team-no-masonry'; 
            $class_column = 'jws_team_item carousel-item'; 
          }
          if($settings['team_display'] == 'slider') {
            
            $settings['slides_to_show'] = isset($settings['slides_to_show']) && !empty($settings['slides_to_show']) ? $settings['slides_to_show'] : '3';
            $settings['slides_to_show_tablet'] = isset($settings['slides_to_show_tablet']) && !empty($settings['slides_to_show_tablet']) ? $settings['slides_to_show_tablet'] : $settings['slides_to_show'];
            $settings['slides_to_show_mobile'] = isset($settings['slides_to_show_mobile']) && !empty($settings['slides_to_show_mobile']) ? $settings['slides_to_show_mobile'] : $settings['slides_to_show'];
            $settings['slides_to_scroll'] = isset($settings['slides_to_scroll']) && !empty($settings['slides_to_scroll']) ? $settings['slides_to_scroll'] : '1';
            $settings['slides_to_scroll_tablet'] = isset($settings['slides_to_scroll_tablet']) && !empty($settings['slides_to_scroll_tablet']) ? $settings['slides_to_scroll_tablet'] : $settings['slides_to_scroll'];
            $settings['slides_to_scroll_mobile'] = isset($settings['slides_to_scroll_mobile']) && !empty($settings['slides_to_scroll_mobile']) ? $settings['slides_to_scroll_mobile'] : $settings['slides_to_scroll']; 
            
            $class_row .= ' jws_team_'.$settings['team_display'].' team-no-masonry';
            $class_column .= ' slick-slide';
            $dots = ($settings['navigation'] == 'dots' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $arrows = ($settings['navigation'] == 'arrows' || $settings['navigation'] == 'both') ? 'true' : 'false';
            $autoplay = ($settings['autoplay'] == 'yes') ? 'true' : 'false';
            $pause_on_hover = ($settings['pause_on_hover'] == 'yes') ? 'true' : 'false';
            $infinite = ($settings['infinite'] == 'yes') ? 'true' : 'false';
            $autoplay_speed = isset($settings['autoplay_speed']) ? $settings['autoplay_speed'] : '5000';
            $data_slick = 'data-slick=\'{"slidesToShow":'.$settings['slides_to_show'].' ,"slidesToScroll": '.$settings['slides_to_scroll'].', "autoplay": '.$autoplay.',"arrows": '.$arrows.', "dots":'.$dots.', "autoplaySpeed": '.$autoplay_speed.',"pauseOnHover":'.$pause_on_hover.',"infinite":'.$infinite.',
            "speed": '.$settings['transition_speed'].', "responsive":[{"breakpoint": 1024,"settings":{"slidesToShow": '.$settings['slides_to_show_tablet'].',"slidesToScroll": '.$settings['slides_to_scroll_tablet'].'}},
            {"breakpoint": 768,"settings":{"slidesToShow": '.$settings['slides_to_show_mobile'].',"slidesToScroll": '.$settings['slides_to_scroll_mobile'].'}}]}\''; 
       }else {
            $data_slick = '';
       }  
         ?>
         <div class="jws_team_element">
            <div class=" <?php echo esc_attr($class_row); ?>" <?php echo ''.$data_slick; ?>>
                <?php if($settings['team_display'] == '3d_slider') echo '<div class="carousel-items">';  ?>
                <?php foreach (  $settings['list'] as $item ) {
                      $url = $item['team_url']['url'];
                      $target = $item['team_url']['is_external'] ? ' target="_blank"' : '';
                      $nofollow = $item['team_url']['nofollow'] ? ' rel="nofollow"' : ''; 
                    ?>
                    <div class="<?php echo esc_attr($class_column); ?>">
                        <div class="jws_team_inner">
                          <?php include( 'layout/'.$settings['team_layout'].'.php' ); ?>
                        </div>
                    </div>
                <?php  } ?>
                <?php if($settings['team_show_add_team'] == 'yes') :
                      $url = $settings['team_url_add_team']['url'];
                      $target = $settings['team_url_add_team']['is_external'] ? ' target="_blank"' : '';
                      $nofollow = $settings['team_url_add_team']['nofollow'] ? ' rel="nofollow"' : ''; 
                 ?>
                    <div class="add-to-team <?php echo esc_attr($class_column); ?>">
                           <h3><?php echo esc_html($settings['team_title_add_team']); ?></h3>
                           <a href="<?php echo esc_url($url); ?>" <?php echo esc_attr($target.$nofollow); ?>><span class="icon-add"></span></a>
                    </div>
                <?php endif; ?>
                <?php if($settings['team_display'] == '3d_slider') echo '</div>';  ?>
            </div>
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