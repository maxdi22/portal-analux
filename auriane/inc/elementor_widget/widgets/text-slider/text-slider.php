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
class text_Slider extends Widget_Base {

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
		return 'jws_text_slider';
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
		return esc_html__( 'Jws text Slider', 'auriane' );
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
		return 'eicon-text';
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
    
		$repeater = new \Elementor\Repeater();

		$repeater->add_control(
			'list_name', [
				'label' => esc_html__( 'Text Name', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Text Name' , 'auriane' ),
				'label_block' => true,
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
						'list_name' => esc_html__( 'Name #1', 'auriane' ),
					],
				],
				'title_field' => '{{{ list_name }}}',
			]
		);

		$this->end_controls_section();
    
        $this->start_controls_section(
			'texts_slider_style',
			[
				'label' => esc_html__( 'Content', 'auriane' ),
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
					'{{WRAPPER}} .jws_texts_slider_wrap .texts_slider .slick-slide' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws_texts_slider_wrap .texts_slider' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
				],
			]
		);
    

        $this->end_controls_section();
        
        $this->start_controls_section(
			'texts_slider_name',
			[
				'label' => esc_html__( 'Text Name', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        
       $this->add_control(
			'text_color',
			[
				'label' => esc_html__( 'Color', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
				'selectors' => [
					'{{WRAPPER}} .text-name' => 'color: {{VALUE}}',
				],
			]
		);

        $this->add_group_control(
			\Elementor\Group_Control_Typography::get_type(),
			[
				'name' => 'text_typography',
				'selector' => '{{WRAPPER}} .text-name',
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
		$settings = $this->get_settings();

       
		if ( $settings['list'] ) {
		     ?>
		      	<div class="jws_texts_slider_wrap">
                  <div class="texts_slider">  
            		  <?php foreach (  $settings['list'] as $item ) {
            	
                          ?>
            				<div class="slick-slide">
                                <div class="text-name"><?php echo (!empty($item['list_name'])) ? $item['list_name'] : '';   ?>  </div> 
                            </div>
            		  <?php } ?>
                  </div>
                
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