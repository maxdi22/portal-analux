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
class Dropdown_Text extends Widget_Base {

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
		return 'jws_dropdown_text';
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
		return esc_html__( 'Jws Dropdown Text', 'auriane' );
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
		return 'eicon-toggle';
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
				'submenu',
				[
					'label'     => esc_html__( 'Menu Position', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'bottom',
					'options'   => [
						'bottom'   => esc_html__( 'Bottom', 'auriane' ),
                        'top'   => esc_html__( 'Top', 'auriane' ),
					],
                    'prefix_class' => 'submenu-',
                    
				]
		);
        $this->add_control(
			'enble_icon',
			[
				'label' => esc_html__( 'Enble Icon', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        $this->add_control(
			'image',
			[
				'label' => esc_html__( 'Choose Image', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
			]
		);
        $this->add_control(
			'title', [
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'English' , 'auriane' ),
				'label_block' => true,
			]
		);
		$repeater = new \Elementor\Repeater();
        
		$repeater->add_control(
			'sub_title', [
				'label' => esc_html__( 'Sub Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Sub Title' , 'auriane' ),
				'label_block' => true,
			]
		);
        $repeater->add_control(
			'sub_url',
			[
				'label' => esc_html__( 'Sub Link', 'auriane' ),
				'type' => \Elementor\Controls_Manager::URL,
				'placeholder' => esc_html__( 'https://your-link.com', 'auriane' ),
				'show_external' => true,
				'default' => [
					'url' => '#',
				],
			]
		);
        $repeater->add_control(
			'image_list',
			[
				'label' => esc_html__( 'Choose Image', 'auriane' ),
				'type' => \Elementor\Controls_Manager::MEDIA,
				'default' => [
					'url' => \Elementor\Utils::get_placeholder_image_src(),
				],
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
						'sub_title' => esc_html__( 'Sub Title 1', 'auriane' ),
					],
				],
				'title_field' => '{{{ sub_title }}}',
			]
		);

		$this->end_controls_section();
        $this->start_controls_section(
			'dropdown_text_top_style',
			[
				'label' => esc_html__( 'Top', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
        $this->add_control(
				'dropdown_text_image_width',
				[
					'label' 		=> esc_html__( 'Width', 'auriane' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 1,
							'max' => 50,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws_dropdown_text .drop_top img' => 'max-width: {{SIZE}}px;',
					],
				]
		);
        $this->add_control(
					'dropdown_text_top_color',
					[
						'label' 	=> esc_html__( 'Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_dropdown_text .drop_top' => 'color: {{VALUE}};',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_text_top_typography',
				'label' => esc_html__( 'Typography', 'auriane'),
				'selector' => '{{WRAPPER}} .jws_dropdown_text .drop_top',
			]
		);
        $this->end_controls_section();
        $this->start_controls_section(
			'dropdown_text_sub_style',
			[
				'label' => esc_html__( 'Sub', 'auriane' ),
				'tab' => Controls_Manager::TAB_STYLE,
			]
		);
         $this->add_control(
				'dropdown_text_sub_width',
				[
					'label' 		=> esc_html__( 'Width', 'auriane' ),
					'type' 			=> Controls_Manager::SLIDER,
					'range' 		=> [
						'px' 		=> [
							'min' => 1,
							'max' => 300,
							'step' => 1,
						],
					],
					'selectors' 	=> [
						'{{WRAPPER}} .jws_dropdown_text .dropdown_sub' => 'width: {{SIZE}}px;',
					],
				]
		);
        $this->add_control(
					'dropdown_text_sub_bgcolor',
					[
						'label' 	=> esc_html__( 'Background Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_dropdown_text .dropdown_sub' => 'background: {{VALUE}};',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Border::get_type(),
			[
				'name' => 'dropdown_text_sub_border',
				'label' => esc_html__( 'Border', 'auriane' ),
				'selector' => '{{WRAPPER}} .jws_dropdown_text .dropdown_sub',
			]
		);
        $this->add_control(
					'dropdown_text_sub_color',
					[
						'label' 	=> esc_html__( 'Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_dropdown_text .dropdown_sub .drop_item a' => 'color: {{VALUE}};',
						],
					]
		);
        $this->add_control(
					'dropdown_text_sub_color_hover',
					[
						'label' 	=> esc_html__( 'Color Hover', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws_dropdown_text .dropdown_sub .drop_item a:hover' => 'color: {{VALUE}};',
						],
					]
		);
        $this->add_group_control(
			Group_Control_Typography::get_type(),
			[
				'name' => 'dropdown_text_sub_typography',
				'label' => esc_html__( 'Typography', 'auriane'),
				'selector' => '{{WRAPPER}} .jws_dropdown_text .dropdown_sub .drop_item a',
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
		      	<div class="jws_dropdown_text">
                  <span class="drop_top">
                    <?php
                    if($settings['enble_icon'] == 'yes') {
                          if ( isset($settings['image']['url']) && !empty($settings['image']['url']) ) {
    						echo '<img alt="usd_img" src="' . $settings['image']['url'] . '">';
    					} 
                    }
                 	?> 
                    <span><?php echo esc_html($settings['title']); ?></span>
                  </span>  
                  <div class="dropdown_sub">  
            		  <?php foreach (  $settings['list'] as $item ) {
            		        $target = $item['sub_url']['is_external'] ? ' target="_blank"' : '';
		                    $nofollow = $item['sub_url']['nofollow'] ? ' rel="nofollow"' : '';
                      ?>
            				<div class="drop_item">
                                <a href="<?php echo esc_url($item['sub_url']['url']); ?>" <?php echo esc_attr($target . $nofollow); ?>><?php echo wp_get_attachment_image( $item['image_list']['id'], 'full' ); echo esc_html($item['sub_title']); ?></a>
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