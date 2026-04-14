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
class Jws_Demo extends Widget_Base {

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
		return 'jws_demo';
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
		return esc_html__( 'Jws Demo', 'auriane' );
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
		return 'eicon-scroll';
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
			'setting_section',
			[
				'label' => esc_html__( 'Setting', 'auriane' ),
				'tab' => \Elementor\Controls_Manager::TAB_CONTENT,
			]
		);

        $this->add_control(
			'demo_layout',
			[
				'label' => esc_html__( 'Layout', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SELECT,
				'default' => 'layout1',
				'options' => [
					'layout1'  => esc_html__( 'Layout 1', 'auriane' ),
                    'layout2'  => esc_html__( 'Layout 2', 'auriane' ),
				],
			]
		);
        $this->add_responsive_control(
				'demo_columns',
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
				'icon',
				[
					'label' => esc_html__( 'Icon', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
		);
        $this->end_controls_section();   
	    $this->start_controls_section(
			'setting_section_list',
			[
				'label' => esc_html__( 'demo List', 'auriane' ),
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
			'demo_title',
			[
				'label' => esc_html__( 'Title', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( 'Default title', 'auriane' ),
				'placeholder' => esc_html__( 'Type your title here', 'auriane' ),
			]
		);
         $repeater->add_control(
			'demo_label',
			[
				'label' => esc_html__( 'Label', 'auriane' ),
				'type' => \Elementor\Controls_Manager::TEXT,
				'default' => esc_html__( '', 'auriane' ),
			]
		);
        $repeater->add_control(
			'label_bg_color',
			[
				'label' => esc_html__( 'Label background', 'auriane' ),
				'type' => \Elementor\Controls_Manager::COLOR,
                'default' => '',
				'selectors' => [
					'{{WRAPPER}} {{CURRENT_ITEM}} .jws_image .demo-label' => 'background: {{VALUE}}',
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
			'enble_commingsoon',
			[
				'label' => esc_html__( 'Comming Soon', 'auriane' ),
				'type' => \Elementor\Controls_Manager::SWITCHER,
				'label_on' => esc_html__( 'On', 'auriane' ),
				'label_off' => esc_html__( 'Off', 'auriane' ),
				'return_value' => 'yes',
				'default' => 'no',
			]
		);
        
        $this->add_control(
			'list_demo',
			[
				'label' => esc_html__( 'Menu List', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
				'default' => [
					[
						'demo_title' => esc_html__( 'Name #1', 'auriane' ),
					],
				],
				'title_field' => '{{{ demo_title }}}',
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
					'{{WRAPPER}} .jws_demo .jws_demo_item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .jws_demo.row' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .jws_demo .jws_demo_item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
				],
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

        
          $class_column = 'jws_demo_item';
          $class_column .= ' col-xl-'.$settings['demo_columns'].'';
          $class_column .= (!empty($settings['demo_columns_tablet'])) ? ' col-lg-'.$settings['demo_columns_tablet'].'' : ' col-lg-'.$settings['demo_columns'].'' ;
          $class_column .= (!empty($settings['demo_columns_mobile'])) ? ' col-'.$settings['demo_columns_mobile'].'' :  ' col-'.$settings['demo_columns'].''; 
          $class_row = 'jws_demo row '; 
         
         ?>
         <div class="jws_demo_element">
            <div class="<?php echo esc_attr($class_row); ?>">
                <?php $i = 1; $n = 0; foreach (  $settings['list_demo'] as $index => $item ) {
                     if($item['enble_commingsoon'] == 'yes') {
                        $class_column .= ' has-comming-soon';
                      }

                 $link_key = 'link' . $index;   
        
                 if($item['link_url']['is_external']) $this->add_render_attribute( $link_key, 'rel',  'nofollow' );
                 if($item['link_url']['nofollow']) $this->add_render_attribute( $link_key, 'target',  '_blank' );  
                $this->add_render_attribute( $link_key, 'href',  $item['link_url']['url'] ); 
              

                $image_size = $settings['image_size'];
                 
                    ?>
                    <div class="<?php echo esc_attr($class_column.' elementor-repeater-item-' . $item['_id'] ); ?>">
                       <div class="jws_column_content">
                            <div class="jws_image">
                                <?php if(!empty($item['demo_label'])) : ?><span class="demo-label"><?php echo esc_html($item['demo_label']); ?></span><?php endif; ?>
                                
                                    <?php
                                         $attach_id = $item['image']['id'];
                                         $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'attachment-large wp-post-image'));
                                         echo ''.$img['thumbnail'];
                                    ?>
                                <a class="overlay" <?php echo ''.$this->get_render_attribute_string($link_key); ?>></a>
                              
                                    
                                        <div class="jws_image_content_inner">
                                            <?php if($item['enble_commingsoon'] == 'yes') {
                                                echo '<span>'.esc_html__('Other demos','auriane').'</span>';
                                                echo '<h4>'.esc_html__('Coming Soon','auriane').'</h4>';
                                            }?>
                                        </div>
                             
                            </div>
                            <header class="jws_headline">
                                <h4><a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
                                <?php echo esc_html($item['demo_title']); ?>
                                </a></h4>
                            </header>
                     
                    </div>
                </div>
                <?php $n++; } ?>
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