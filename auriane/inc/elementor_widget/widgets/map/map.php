<?php
/**
 * jws GoogleMap.
 *
 * @package jws
 */

namespace Elementor;


// Elementor Classes.
use Elementor\Controls_Manager;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Typography;
use Elementor\Widget_Base;

use UltimateElementor\Classes\jws_Helper;

if ( ! defined( 'ABSPATH' ) ) {
	exit;   // Exit if accessed directly.
}

/**
 * Class GoogleMap.
 */
class GoogleMap extends Widget_Base {

	/**
	 * Retrieve GoogleMap Widget name.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget name.
	 */
	public function get_name() {
		return 'jws_map';
	}

	/**
	 * Retrieve GoogleMap Widget title.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget title.
	 */
	public function get_title() {
		return esc_html__( 'Jws Map', 'auriane' );
	}

	/**
	 * Retrieve GoogleMap Widget icon.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_icon() {
		return 'eicon-site-search';
	}

	/**
	 * Retrieve Widget Keywords.
	 *
	 * @since 1.5.1
	 * @access public
	 *
	 * @return string Widget icon.
	 */
	public function get_keywords() {
		return 'map';
	}

	/**
	 * Retrieve the list of scripts the image carousel widget depended on.
	 *
	 * Used to set scripts dependencies required to run the widget.
	 *
	 * @since 0.0.1
	 * @access public
	 *
	 * @return array Widget scripts dependencies.
	 */
	public function get_script_depends() {
		return [ 'jws-google-maps-api' ];
	}


	/**
	 * Register GoogleMap controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_controls() {

		$this->register_addresses_controls();
		$this->register_layout_controls();
		$this->register_controls_controls();
		$this->register_info_window_controls();
		$this->register_helpful_information();
        $this->map_style();
	}

	/**
	 * Register GoogleMap Addresses Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_addresses_controls() {


		$this->start_controls_section(
			'section_map_addresses',
			[
				'label' => esc_html__( 'Addresses', 'auriane' ),
			]
		);


        $admin_link = '';
		$this->add_control(
				'err_msg',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %s admin link */
					'raw'             => sprintf( esc_html__( 'To display customized Google Map without an issue, you need to configure Google Map API key. Please configure API key from <a href="%s" target="_blank" rel="noopener">here</a>.', 'auriane' ), $admin_link ),
					'content_classes' => 'elementor-panel-alert elementor-panel-alert-warning',
				]
		);
    	   $repeater = new \Elementor\Repeater();
    
            $repeater->add_control(
    			'latitude',
    			[
    				'label'       => esc_html__( 'Latitude', 'auriane' ),
					'description' => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'auriane' ), esc_html__( 'to find Latitude of your location', 'auriane' ) ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
    			]
    		);
            $repeater->add_control(
    			'longitude',
    			[
    				'label'       => esc_html__( 'Longitude', 'auriane' ),
					'description' => sprintf( '<a href="https://www.latlong.net/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'auriane' ), esc_html__( 'to find Longitude of your location', 'auriane' ) ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
    			]
    		);
            $repeater->add_control(
    			'map_title',
    			[
    				'label'       => esc_html__( 'Longitude', 'auriane' ),
					'label'       => esc_html__( 'Address Title', 'auriane' ),
					'type'        => Controls_Manager::TEXT,
					'default'     => '',
					'label_block' => true,
					'dynamic'     => [
						'active' => true,
					],
    			]
    		);
            $repeater->add_control(
    			'marker_infowindow',
    			[
					'label'       => esc_html__( 'Display Info Window', 'auriane' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'none',
					'label_block' => true,
					'options'     => [
						'none'  => esc_html__( 'None', 'auriane' ),
						'click' => esc_html__( 'On Mouse Click', 'auriane' ),
						'load'  => esc_html__( 'On Page Load', 'auriane' ),
					],
    			]
    		);
            $repeater->add_control(
    			'map_description',
    			[
					'label'       => esc_html__( 'Address Information', 'auriane' ),
					'type'        => Controls_Manager::TEXTAREA,
					'label_block' => true,
					'conditions'  => [
						'terms' => [
							[
								'name'     => 'marker_infowindow',
								'operator' => '!=',
								'value'    => 'none',
							],
						],
					],
					'dynamic'     => [
						'active' => true,
					],
    			]
    		);
            $repeater->add_control(
    			'marker_images',
    			[
					'label'      => esc_html__( 'Content Images', 'auriane' ),
					'type'       => Controls_Manager::MEDIA,
    			]
    		);
            $repeater->add_control(
    			'marker_icon_type',
    			[
				    'label'   => esc_html__( 'Marker Icon', 'auriane' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'default',
					'options' => [
						'default' => esc_html__( 'Default', 'auriane' ),
						'custom'  => esc_html__( 'Custom', 'auriane' ),
                        'html'  => esc_html__( 'Html Animation', 'auriane' ),
					],
    			]
    		);
            $repeater->add_control(
    			'marker_icon',
    			[
				    'label'      => esc_html__( 'Select Marker', 'auriane' ),
					'type'       => Controls_Manager::MEDIA,
					'conditions' => [
						'terms' => [
							[
								'name'     => 'marker_icon_type',
								'operator' => '==',
								'value'    => 'custom',
							],
						],
					],
    			]
    		);
            $repeater->add_control(
    			'custom_marker_size',
    			[
				    'label'       => esc_html__( 'Marker Size', 'auriane' ),
					'type'        => Controls_Manager::SLIDER,
					'size_units'  => [ 'px' ],
					'description' => esc_html__( 'Note: If you want to retain the image original size, then set the Marker Size as blank.', 'auriane' ),
					'default'     => [
						'size' => 30,
						'unit' => 'px',
					],
					'range'       => [
						'px' => [
							'min' => 5,
							'max' => 100,
						],
					],
					'conditions'  => [
						'terms' => [
							[
								'name'     => 'marker_icon_type',
								'operator' => '==',
								'value'    => 'custom',
							],
						],
					],
    			]
    		);
            $this->add_control(
    			'addresses',
    			[
    				'label' => esc_html__( 'Addresses List', 'auriane' ),
    				'type' => \Elementor\Controls_Manager::REPEATER,
    				'fields' => $repeater->get_controls(),
    				'default' => [
    					[
    					   'latitude'        => 51.503333,
							'longitude'       => -0.119562,
							'map_title'       => esc_html__( 'Coca-Cola London Eye', 'auriane' ),
							'map_description' => '',
    					],
    				],
    				'title_field' => '{{{ map_title }}}',
    			]
    		);


		$this->end_controls_section();
	}

	/**
	 * Register GoogleMap Layout Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_layout_controls() {

		$this->start_controls_section(
			'section_map_settings',
			[
				'label' => esc_html__( 'Layout', 'auriane' ),
			]
		);

			$this->add_control(
				'type',
				[
					'label'   => esc_html__( 'Map Type', 'auriane' ),
					'type'    => Controls_Manager::SELECT,
					'default' => 'roadmap',
					'options' => [
						'roadmap'   => esc_html__( 'Road Map', 'auriane' ),
						'satellite' => esc_html__( 'Satellite', 'auriane' ),
						'hybrid'    => esc_html__( 'Hybrid', 'auriane' ),
						'terrain'   => esc_html__( 'Terrain', 'auriane' ),
					],
				]
			);

			$this->add_control(
				'skin',
				[
					'label'     => esc_html__( 'Map Skin', 'auriane' ),
					'type'      => Controls_Manager::SELECT,
					'default'   => 'standard',
					'options'   => [
						'standard'     => esc_html__( 'Standard', 'auriane' ),
						'silver'       => esc_html__( 'Silver', 'auriane' ),
						'retro'        => esc_html__( 'Retro', 'auriane' ),
						'dark'         => esc_html__( 'Dark', 'auriane' ),
						'night'        => esc_html__( 'Night', 'auriane' ),
						'aubergine'    => esc_html__( 'Aubergine', 'auriane' ),
						'aqua'         => esc_html__( 'Aqua', 'auriane' ),
						'classic_blue' => esc_html__( 'Classic Blue', 'auriane' ),
						'earth'        => esc_html__( 'Earth', 'auriane' ),
						'magnesium'    => esc_html__( 'Magnesium', 'auriane' ),
						'custom'       => esc_html__( 'Custom', 'auriane' ),
					],
					'condition' => [
						'type!' => 'satellite',
					],
				]
			);

			$this->add_control(
				'map_custom_style',
				[
					'label'       => esc_html__( 'Custom Style', 'auriane' ),
					'description' => sprintf( '<a href="https://mapstyle.withgoogle.com/" target="_blank">%1$s</a> %2$s', esc_html__( 'Click here', 'auriane' ), esc_html__( 'to get JSON style code to style your map', 'auriane' ) ),
					'type'        => Controls_Manager::TEXTAREA,
					'condition'   => [
						'skin'  => 'custom',
						'type!' => 'satellite',
					],
				]
			);

			$this->add_control(
				'animate',
				[
					'label'   => esc_html__( 'Marker Animation', 'auriane' ),
					'type'    => Controls_Manager::SELECT,
					'default' => '',
					'options' => [
						''       => esc_html__( 'None', 'auriane' ),
						'drop'   => esc_html__( 'On Load', 'auriane' ),
						'bounce' => esc_html__( 'Continuous', 'auriane' ),
					],
				]
			);
            
            $this->add_control(
				'map-offset',
				[
					'label'       => esc_html__( 'Map Offset', 'auriane' ),
					'type'        => Controls_Manager::SLIDER,
					'range'       => [
						'px' => [
							'min'  => 0,
							'max'  => 1000,
							'step' => 1,
						],
					],
					'size_units'  => [ 'px' ],
					'label_block' => true,
				]
			);

			$this->add_control(
				'zoom',
				[
					'label'   => esc_html__( 'Map Zoom', 'auriane' ),
					'type'    => Controls_Manager::SLIDER,
					'default' => [
						'size' => 12,
					],
					'range'   => [
						'px' => [
							'min' => 1,
							'max' => 22,
						],
					],
				]
			);

			$this->add_responsive_control(
				'height',
				[
					'label'      => esc_html__( 'Height', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'size_units' => [ 'px' ],
					'default'    => [
						'size' => 500,
						'unit' => 'px',
					],
					'range'      => [
						'px' => [
							'min' => 80,
							'max' => 1200,
						],
					],
					'selectors'  => [
						'{{WRAPPER}} .jws-google-map' => 'height: {{SIZE}}{{UNIT}};',
					],
				]
			);

		$this->end_controls_section();
	}

	/**
	 * Register GoogleMap Control Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_controls_controls() {

		$this->start_controls_section(
			'section_map_controls',
			[
				'label' => esc_html__( 'Controls', 'auriane' ),
			]
		);

			$this->add_control(
				'option_streeview',
				[
					'label'        => esc_html__( 'Street View Controls', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'auriane' ),
					'label_off'    => esc_html__( 'Off', 'auriane' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'type_control',
				[
					'label'        => esc_html__( 'Map Type Control', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'auriane' ),
					'label_off'    => esc_html__( 'Off', 'auriane' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'zoom_control',
				[
					'label'        => esc_html__( 'Zoom Control', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'auriane' ),
					'label_off'    => esc_html__( 'Off', 'auriane' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'fullscreen_control',
				[
					'label'        => esc_html__( 'Fullscreen Control', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'auriane' ),
					'label_off'    => esc_html__( 'Off', 'auriane' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'scroll_zoom',
				[
					'label'        => esc_html__( 'Zoom on Scroll', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'yes',
					'label_on'     => esc_html__( 'On', 'auriane' ),
					'label_off'    => esc_html__( 'Off', 'auriane' ),
					'return_value' => 'yes',
				]
			);

			$this->add_control(
				'auto_center',
				[
					'label'       => esc_html__( 'Map Alignment', 'auriane' ),
					'type'        => Controls_Manager::SELECT,
					'default'     => 'center',
					'options'     => [
						'center'   => esc_html__( 'Center', 'auriane' ),
						'moderate' => esc_html__( 'Moderate', 'auriane' ),
					],
					'description' => esc_html__( 'Generally, the map is center aligned. If you have multiple locations & wish to make your first location as a center point, then switch to moderate mode.', 'auriane' ),
				]
			);

			$this->add_control(
				'cluster',
				[
					'label'        => esc_html__( 'Cluster the Markers', 'auriane' ),
					'type'         => Controls_Manager::SWITCHER,
					'default'      => 'no',
					'label_on'     => esc_html__( 'On', 'auriane' ),
					'label_off'    => esc_html__( 'Off', 'auriane' ),
					'return_value' => 'yes',
				]
			);

	
			$this->add_control(
				'cluster_doc',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s admin link */
					'raw'             => sprintf( esc_html__( 'Enable this to group your markers together if you have many in a close proximity to only display one larger marker on your map.<br> Read %1$s this article %2$s for more information.', 'auriane' ), '<a href="https://jwsementor.com/docs/what-are-cluster-markers-in-jws/" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'jws-editor-doc',
					'condition'       => [ 'cluster' => 'yes' ],
				]
			);
	

		$this->end_controls_section();
	}

	/**
	 * Register GoogleMap Info Window Controls.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_info_window_controls() {

		$this->start_controls_section(
			'section_info_window_style',
			[
				'label' => esc_html__( 'Info Window', 'auriane' ),
			]
		);

			$this->add_control(
				'info_window_size',
				[
					'label'       => esc_html__( 'Max Width for Info Window', 'auriane' ),
					'type'        => Controls_Manager::SLIDER,
					'default'     => [
						'size' => 250,
						'unit' => 'px',
					],
					'range'       => [
						'px' => [
							'min'  => 50,
							'max'  => 1000,
							'step' => 1,
						],
					],
					'size_units'  => [ 'px' ],
					'label_block' => true,
				]
			);

			$this->add_responsive_control(
				'info_padding',
				[
					'label'      => esc_html__( 'Padding', 'auriane' ),
					'type'       => Controls_Manager::DIMENSIONS,
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .gm-style .jws-infowindow-content' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_responsive_control(
				'title_spacing',
				[
					'label'      => esc_html__( 'Spacing Between Title & Info.', 'auriane' ),
					'type'       => Controls_Manager::SLIDER,
					'range'      => [
						'px' => [
							'min'  => 0,
							'max'  => 100,
							'step' => 1,
						],
					],
					'default'    => [
						'size' => 5,
						'unit' => 'px',
					],
					'size_units' => [ 'px', 'em', '%' ],
					'selectors'  => [
						'{{WRAPPER}} .gm-style .jws-infowindow-description' => 'margin-top: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .gm-style .jws-infowindow-title' => 'font-weight: bold;',
					],
				]
			);

			$this->add_control(
				'title_heading',
				[
					'label' => esc_html__( 'Address Title', 'auriane' ),
					'type'  => Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'title_color',
				[
					'label'     => esc_html__( 'Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gm-style .jws-infowindow-title' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'title_typography',
					
					'selector' => '{{WRAPPER}} .gm-style .jws-infowindow-title',
				]
			);

			$this->add_control(
				'description_heading',
				[
					'label' => esc_html__( 'Address Information', 'auriane' ),
					'type'  => Controls_Manager::HEADING,
				]
			);

			$this->add_control(
				'description_color',
				[
					'label'     => esc_html__( 'Color', 'auriane' ),
					'type'      => Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .gm-style .jws-infowindow-description' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name'     => 'description_typography',
					'selector' => '{{WRAPPER}} .gm-style .jws-infowindow-description',
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Helpful Information.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function register_helpful_information() {

		
			$this->start_controls_section(
				'section_helpful_info',
				[
					'label' => esc_html__( 'Helpful Information', 'auriane' ),
				]
			);

			$this->add_control(
				'help_doc_1',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( esc_html__( '%1$s Getting started video Â» %2$s', 'auriane' ), '<a href="https://www.youtube.com/watch?v=jWzW_oT1iSQ&index=6&list=PL1kzJGWGPrW_7HabOZHb6z88t_S8r-xAc" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'jws-editor-doc',
				]
			);

			$this->add_control(
				'help_doc_2',
				[
					'type'            => Controls_Manager::RAW_HTML,
					/* translators: %1$s doc link */
					'raw'             => sprintf( esc_html__( '%1$s Google Map localization Â» %2$s', 'auriane' ), '<a href="https://jwsementor.com/docs/how-to-display-jwss-google-maps-widget-in-your-local-language/?utm_source=auriane-dashboard&utm_medium=jws-editor-screen&utm_campaign=auriane-plugin" target="_blank" rel="noopener">', '</a>' ),
					'content_classes' => 'jws-editor-doc',
				]
			);

			$this->end_controls_section();
		
	}
    
    	/**
	 * Helpful Information.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function map_style() {

		
			 $this->start_controls_section(
    			'map_style',
    			[
    				'label' => esc_html__( 'Style', 'auriane' ),
    				'tab' => Controls_Manager::TAB_STYLE,
    			]
    		);

    		$this->add_control(
    			'box_bgcolor',
    			[
    				'label' => esc_html__( 'background Html Animation Color', 'auriane' ),
    				'type' => \Elementor\Controls_Manager::COLOR,
    				'selectors' => [
    					'{{WRAPPER}} .map_pin_jws ,{{WRAPPER}} .map_pin_jws > div ' => 'background-color: {{VALUE}}',
    				],
    			]
    		);

			$this->end_controls_section();
		
	}


	/**
	 * Renders Locations JSON array.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_locations() {

		$settings = $this->get_settings_for_display();

		$locations = array();

		foreach ( $settings['addresses'] as $index => $item ) {

			$latitude  = apply_filters( 'jws_google_map_latitude', $item['latitude'] );
			$longitude = apply_filters( 'jws_google_map_longitude', $item['longitude'] );

			$location_object = array(
				$latitude,
				$longitude,
			);

			$location_object[] = ( 'none' !== $item['marker_infowindow'] ) ? true : false;
			$location_object[] = apply_filters( 'jws_google_map_title', $item['map_title'] );
			$location_object[] = apply_filters( 'jws_google_map_description', $item['map_description'] );
            $location_object[] = apply_filters( 'jws_google_map_images', $item['marker_images']['url'] );
    

			if (
				'custom' === $item['marker_icon_type'] && is_array( $item['marker_icon'] ) &&
				'' !== $item['marker_icon']['url']
			) {
				$location_object[] = 'custom';
				$location_object[] = $item['marker_icon']['url'];
				$location_object[] = $item['custom_marker_size']['size'];
			}elseif('html' === $item['marker_icon_type']){
			     $location_object[] = 'html';
			     $location_object[] = '';
				$location_object[] = '';
			} else {
				$location_object[] = '';
				$location_object[] = '';
				$location_object[] = '';
			}

			$location_object[] = ( 'load' === $item['marker_infowindow'] ) ? 'iw_open' : '';

			$locations[] = $location_object;
		}

		return $locations;
	}

	/**
	 * Renders Map Control option JSON array.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function get_map_options() {

		$settings = $this->get_settings_for_display();

		return array(
			'zoom'              => ( ! empty( $settings['zoom']['size'] ) ) ? $settings['zoom']['size'] : 4,
			'mapTypeId'         => ( ! empty( $settings['type'] ) ) ? $settings['type'] : 'roadmap',
			'mapTypeControl'    => ( 'yes' === $settings['type_control'] ) ? true : false,
			'streetViewControl' => ( 'yes' === $settings['option_streeview'] ) ? true : false,
			'zoomControl'       => ( 'yes' === $settings['zoom_control'] ) ? true : false,
			'fullscreenControl' => ( 'yes' === $settings['fullscreen_control'] ) ? true : false,
			'gestureHandling'   => ( 'yes' === $settings['scroll_zoom'] ) ? true : false,
		);
	}

	/**
	 * Render Google Map output on the frontend.
	 *
	 * Written in PHP and used to generate the final HTML.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function render() {

		$settings = $this->get_settings_for_display();

		ob_start();

		$map_options = $this->get_map_options();
		$locations   = $this->get_locations();

		$this->add_render_attribute(
			'google-map',
			[
				'id'               => 'jws-google-map-' . esc_attr( $this->get_id() ),
				'class'            => 'jws-google-map',
				'data-map_options' => wp_json_encode( $map_options ),
				'data-cluster'     => $settings['cluster'],
				'data-max-width'   => $settings['info_window_size']['size'],
				'data-locations'   => wp_json_encode( $locations ),
				'data-animate'     => $settings['animate'],
				'data-auto-center' => $settings['auto_center'],
                'data-offset'       => $settings['map-offset']['size'],
			]
		);

		if ( 'standard' !== $settings['skin'] ) {
			if ( 'custom' !== $settings['skin'] ) {
				$this->add_render_attribute( 'google-map', 'data-predefined-style', $settings['skin'] );
			} elseif ( ! empty( $settings['map_custom_style'] ) ) {
				$this->add_render_attribute( 'google-map', 'data-custom-style', $settings['map_custom_style'] );
			}
		}

		?>
		<div class="jws-google-map-wrap">
			<div <?php echo ''.$this->get_render_attribute_string( 'google-map' ); ?>></div>
		</div>
		<?php
		$html = ob_get_clean();
        if(function_exists('output_ech')) {
            echo output_ech($html);
        }
	}

	/**
	 * Render GoogleMap widget output in the editor.
	 *
	 * Written as a Backbone JavaScript template and used to generate the live preview.
	 *
	 * @since 0.0.1
	 * @access protected
	 */
	protected function content_template() {


	}

}
