<?php
namespace Elementor;
use Elementor\Group_Control_Typography;
use Elementor\Widget_Base;
use Elementor\Controls_Manager;
use Elementor\Group_Control_Background;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Elementor Hello World
 *
 * Elementor widget for hello world.
 *
 * @since 1.0.0
 */
class Instagram extends Widget_Base {

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
		return 'jws_instagram';
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
		return esc_html__( 'Jws Instagram', 'auriane' );
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
		return 'eicon-video-camera';
	}
    
    public function get_script_depends() {
        return [
      
        ];
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
			'section_instagram_setting',
			[
				'label' => esc_html__( 'Setting', 'auriane' ),
			]
		);
        $this->add_control(
				'layout',
				[
					'label'     => esc_html__( 'Layout', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'grid',
					'options'   => [
                        'grid'   => esc_html__( 'Grid', 'auriane' ),
						'metro'   => esc_html__( 'Metro', 'auriane' ),
					],
				]
		);
        $this->add_control(
				'skins',
				[
					'label'     => esc_html__( 'Skins', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'skin1',
					'options'   => [
                        'skin1'   => esc_html__( 'Skin 1', 'auriane' ),
						'skin2'   => esc_html__( 'Skin 2', 'auriane' ),
					],
				]
		);
       $this->add_control(
				'image_limit',
				[
					'label'     => esc_html__( 'Image Limit', 'auriane' ),
					'type'      => Controls_Manager::NUMBER,
					'default'   => '9',
				]
		);
        $this->add_responsive_control(
				'align',
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
							'{{WRAPPER}} .jws_instagram' => 'text-align: {{VALUE}};',
					],
					'frontend_available' => true,
				]
		);
		$this->add_responsive_control(
			'columns',
			[
				'label'          => esc_html__( 'Columns', 'auriane' ),
				'type'           => Controls_Manager::SELECT,
				'default'        => '3',
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
        
        $this->start_controls_section(
			'section_instagram_list',
			[
				'label' => esc_html__( 'Instagram List', 'auriane' ),
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
        
        $this->add_control(
			'list',
			[
				'label' => esc_html__( 'Gallery List', 'auriane' ),
				'type' => \Elementor\Controls_Manager::REPEATER,
				'fields' => $repeater->get_controls(),
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
					'{{WRAPPER}} .jws-instagram-item' => 'padding-right: calc( {{SIZE}}{{UNIT}}/2 ); padding-left: calc( {{SIZE}}{{UNIT}}/2 );',
					'{{WRAPPER}} .instagram-wap' => 'margin-left: calc( -{{SIZE}}{{UNIT}}/2 ); margin-right: calc( -{{SIZE}}{{UNIT}}/2 );',
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
					'{{WRAPPER}} .jws-instagram-item' => 'margin-bottom: {{SIZE}}{{UNIT}};',
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
		$settings = $this->get_settings();
        $id       = $this->get_id();
        $image_size = '400x400';
        if ( ! empty( $settings['list'] ) ) {
		
            $class_column = 'jws-instagram-item';
            $clasrow = 'row instagram-wap';
            if($settings['layout'] == 'metro') {
                $loading_icon = '<div class="loader"><svg class="circular" viewBox="25 25 50 50"><circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10"></circle></svg></div>';
               $clasrow .= ' loading';
                wp_enqueue_script('isotope');
            }else {
                $class_column .= 'col-xl-'.$settings['columns'].'';
                $class_column .= (!empty($settings['columns_tablet'])) ? ' col-lg-'.$settings['columns_tablet'].'' : ' col-lg-'.$settings['columns'].'' ;
                $class_column .= (!empty($settings['columns_mobile'])) ? ' col-'.$settings['columns_mobile'].'' :  ' col-'.$settings['columns'].'';
                $loading_icon = '';
            }
            $class = 'jws-instagram ';
            $class .= $settings['layout'];
            $class .= ' '.$settings['skins'];
            
            
            
            ?> 
            <div class="<?php echo esc_attr($class); ?>">
            <div class="<?php echo esc_attr($clasrow); ?>">
            <?php
            echo ''.$loading_icon;
            $i = 1;
            foreach (  $settings['list'] as $key => $item ) {
               $link_key = 'link' . $key;  
               if($item['link_url']['nofollow']) $this->add_render_attribute( $link_key, 'rel',  'nofollow' );
               if($item['link_url']['is_external']) $this->add_render_attribute( $link_key, 'target',  '_blank' );  
               $this->add_render_attribute( $link_key, 'href',  $item['link_url']['url'] ); 
                $this->add_render_attribute( $link_key, 'class', 'overlay' );  
                $attach_id = $item['image']['id'];
                if($settings['layout'] == 'metro') { 
                    
                    if($i == '3') {
                       $class_column = 'col-xl-4 col-lg-4 col-4'; 
                       $image_size = '720x720';
                    }else {
                       $class_column = 'col-xl-2 col-lg-2 col-4 grid-sizer'; 
                       $image_size = '360x360';
                    }
                    
                }
                
                $img = jws_getImageBySize(array('attach_id' => $attach_id, 'thumb_size' => $image_size, 'class' => 'instagram-images'));
             
                ?>
                    <div class="jws-instagram-item <?php echo esc_attr($class_column); ?>">
                        <div class="jws-instagram-inner">
                            <a <?php echo ''.$this->get_render_attribute_string($link_key); ?>>
                                <i aria-hidden="true" class="fab fa-instagram"></i>
                            </a>
                            <?php echo ''.$img['thumbnail']; ?>
                        </div>
                    </div>
                <?php
            if ($i == 9) {
                $i = 1;
            }else {
               $i ++;  
            } }
            
		}
        ?>
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