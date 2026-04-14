<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * Jws Heading Widget
 *
 * jws Widget to display heading.
 *
 * @since 1.0
 */

use Elementor\Controls_Manager;
use Elementor\Core\Schemes\Color;
use Elementor\Group_Control_Typography;
use Elementor\Core\Schemes\Typography;
use Elementor\Group_Control_Text_Shadow;
use ELementor\Group_Control_Box_Shadow;


class Jws_Heading_Elementor_Widget extends Widget_Base {
	public function get_name() {
		return 'jws_widget_heading';
	}

	public function get_title() {
		return esc_html__( 'Jws Heading', 'auriane' );
	}

	public function get_categories() {
		return array( 'jws-elements' );
	}

	public function get_keywords() {
		return array( 'heading', 'title', 'subtitle', 'text', 'jws', 'dynamic' );
	}

	public function get_icon() {
		return 'jws-elementor-widget-icon widget-icon-heading';
	}

	public function get_script_depends() {
		return array();
	}

	protected function register_controls() {

		$this->start_controls_section(
			'section_heading_title',
			array(
				'label' => esc_html__( 'Title', 'auriane' ),
			)
		);

		$this->add_control(
			'content_type',
			array(
				'label'       => esc_html__( 'Content', 'auriane' ),
				'description' => esc_html__( 'Select a certain content type among Custom and Dynamic.', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'custom'  => esc_html__( 'Custom Text', 'auriane' ),
					'dynamic' => esc_html__( 'Dynamic Content', 'auriane' ),
				),
				'default'     => 'custom',
			)
		);

		$this->add_control(
			'dynamic_content',
			array(
				'label'       => esc_html__( 'Dynamic Content', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'title'        => esc_html__( 'Page Title', 'auriane' ),
					'subtitle'     => esc_html__( 'Page Subtitle', 'auriane' ),
					'product_cnt'  => esc_html__( 'Products Count', 'auriane' ),
					'site_tagline' => esc_html__( 'Site Tag Line', 'auriane' ),
					'site_title'   => esc_html__( 'Site Title', 'auriane' ),
					'date'         => esc_html__( 'Current Date Time', 'auriane' ),
					'user_info'    => esc_html__( 'User Info', 'auriane' ),
				),
				'default'     => 'title',
				'condition'   => array(
					'content_type' => 'dynamic',
				),
				'description' => esc_html__( 'Select the certain dynamic content you want to show in your page. ( ex. page title, subtitle, user info and so on )', 'auriane' ),
			)
		);

		$this->add_control(
			'userinfo_type',
			array(
				'label'       => esc_html__( 'User Info Field', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'id'           => esc_html__( 'ID', 'auriane' ),
					'display_name' => esc_html__( 'Display Name', 'auriane' ),
					'login'        => esc_html__( 'Username', 'auriane' ),
					'first_name'   => esc_html__( 'First Name', 'auriane' ),
					'last_name'    => esc_html__( 'Last Name', 'auriane' ),
					'description'  => esc_html__( 'Bio', 'auriane' ),
					'email'        => esc_html__( 'Email', 'auriane' ),
					'url'          => esc_html__( 'Website', 'auriane' ),
					'meta'         => esc_html__( 'User Meta', 'auriane' ),
				),
				'default'     => 'display_name',
				'condition'   => array(
					'content_type'    => 'dynamic',
					'dynamic_content' => 'user_info',
				),
				'description' => esc_html__( 'Select the certain user information you want to show in your page. ( ex. username, email and so on )', 'auriane' ),
			)
		);

		$this->add_control(
			'title',
			array(
				'label'       => esc_html__( 'Title', 'auriane' ),
				'description' => esc_html__( 'Type a certain heading you want to display.', 'auriane' ),
				'type'        => Controls_Manager::TEXTAREA,
				'dynamic'     => [
					'active' => true,
				],
				'default'     => esc_html__( 'Add Your Heading Text Here', 'auriane' ),
				'placeholder' => esc_html__( 'Enter your title', 'auriane' ),
				'condition'   => array(
					'content_type' => 'custom',
				),
			)
		);

		$this->add_control(
			'tag',
			array(
				'label'       => esc_html__( 'HTML Tag', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'options'     => array(
					'h1' => 'H1',
					'h2' => 'H2',
					'h3' => 'H3',
					'h4' => 'H4',
					'h5' => 'H5',
					'h6' => 'H6',
					'p'  => 'p',
				),
				'default'     => 'h2',
				'description' => esc_html__( 'Select the HTML Heading tag from H1 to H6 and P tag,too.', 'auriane' ),
			)
		);

		$this->add_control(
			'decoration',
			array(
				'type'        => Controls_Manager::SELECT,
				'label'       => esc_html__( 'Decoration Type', 'auriane' ),
				'default'     => '',
				'options'     => array(
					''          => esc_html__( 'Simple', 'auriane' ),
					'cross'     => esc_html__( 'Cross', 'auriane' ),
					'underline' => esc_html__( 'Underline', 'auriane' ),
				),
				'description' => esc_html__( 'Select the decoration type among Simple, Cross and Underline options. The Default type is the Simple type.', 'auriane' ),
			)
		);

		$this->add_control(
			'hide_underline',
			array(
				'label'       => esc_html__( 'Hide Active Underline?', 'auriane' ),
				'description' => esc_html__( 'Toggle for making your heading has an active underline or not.', 'auriane' ),
				'type'        => Controls_Manager::SWITCHER,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title::after' => 'content: none',
				),
				'condition'   => array(
					'decoration' => 'underline',
				),
			)
		);

		$this->add_control(
			'title_align',
			array(
				'label'       => esc_html__( 'Title Align', 'auriane' ),
				'type'        => Controls_Manager::CHOOSE,
				'default'     => 'left',
				'options'     => array(
					'left'   => array(
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon'  => 'eicon-text-align-left',
					),
					'center' => array(
						'title' => esc_html__( 'Center', 'auriane' ),
						'icon'  => 'eicon-text-align-center',
					),
					'right'  => array(
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
                'selectors'   => array(
					'.elementor-element-{{ID}} .title' => 'text-align: {{VALUE}};',
				),
				'description' => esc_html__( 'Controls the alignment of title. Options are left, center and right.', 'auriane' ),
			)
		);

		$this->add_responsive_control(
			'decoration_spacing',
			array(
				'label'       => esc_html__( 'Decoration Spacing', 'auriane' ),
				'description' => esc_html__( 'Controls the space between the heading and the decoration.', 'auriane' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -100,
						'max'  => 100,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title::before' => 'margin-right: {{SIZE}}{{UNIT}};',
					'.elementor-element-{{ID}} .title::after'  => 'margin-left: {{SIZE}}{{UNIT}};',
				),
				'condition'   => array(
					'decoration' => 'cross',
				),
			)
		);

		$this->add_control(
			'show_link',
			array(
				'label'       => esc_html__( 'Show Link?', 'auriane' ),
				'description' => esc_html__( 'Toggle for making your heading has link or not.', 'auriane' ),
				'type'        => Controls_Manager::SWITCHER,
				'default'     => '',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_link',
			array(
				'label'     => esc_html__( 'Link', 'auriane' ),
				'condition' => array(
					'show_link' => 'yes',
				),
			)
		);

		$this->add_control(
			'link_url',
			array(
				'label'       => esc_html__( 'Link Url', 'auriane' ),
				'description' => esc_html__( 'Type a certain URL to link through other pages.', 'auriane' ),
				'type'        => Controls_Manager::URL,
				'default'     => array(
					'url' => '',
				),
			)
		);

		$this->add_control(
			'link_label',
			array(
				'label'       => esc_html__( 'Link Label', 'auriane' ),
				'description' => esc_html__( 'Type a certain label of your heading link.', 'auriane' ),
				'type'        => Controls_Manager::TEXT,
				'default'     => 'link',
			)
		);

		$this->add_control(
			'icon',
			array(
				'label'       => esc_html__( 'Icon', 'auriane' ),
				'description' => esc_html__( 'Upload a certain icon of your heading link.', 'auriane' ),
				'type'        => Controls_Manager::ICONS,
			)
		);

		$this->add_control(
			'icon_pos',
			array(
				'label'       => esc_html__( 'Icon Position', 'auriane' ),
				'description' => esc_html__( 'Select a certain position of your icon.', 'auriane' ),
				'type'        => Controls_Manager::SELECT,
				'default'     => 'after',
				'options'     => array(
					'after'  => esc_html__( 'After', 'auriane' ),
					'before' => esc_html__( 'Before', 'auriane' ),
				),
			)
		);

		$this->add_responsive_control(
			'icon_space',
			array(
				'label'       => esc_html__( 'Icon Spacing (px)', 'auriane' ),
				'description' => esc_html__( 'Type a certain number for the space between label and icon.', 'auriane' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .icon-before i' => 'margin-right: {{SIZE}}px;',
					'.elementor-element-{{ID}} .icon-after i'  => 'margin-left: {{SIZE}}px;',
				),
			)
		);

		$this->add_responsive_control(
			'icon_size',
			array(
				'label'       => esc_html__( 'Icon Size (px)', 'auriane' ),
				'description' => esc_html__( 'Type a certain number for your icon size.', 'auriane' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 50,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} i' => 'font-size: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'link_align',
			array(
				'label'       => esc_html__( 'Link Align', 'auriane' ),
				'description' => esc_html__( 'Choose a certain alignment of your heading link.', 'auriane' ),
				'type'        => Controls_Manager::CHOOSE,
				'options'     => array(
					'link-left'  => array(
						'title' => esc_html__( 'Left', 'auriane' ),
						'icon'  => 'eicon-text-align-left',
					),
					'link-right' => array(
						'title' => esc_html__( 'Right', 'auriane' ),
						'icon'  => 'eicon-text-align-right',
					),
				),
				'default'     => 'link-right',
			)
		);

		$this->add_control(
			'show_divider',
			array(
				'label'       => esc_html__( 'Show Divider?', 'auriane' ),
				'description' => esc_html__( 'Toggle for making your heading has a divider or not. It is only available in left alignment.', 'auriane' ),
				'type'        => Controls_Manager::SWITCHER,
				'condition'   => array(
					'link_align' => 'link-left',
				),
			)
		);

		$this->add_responsive_control(
			'link_gap',
			array(
				'label'       => esc_html__( 'Link Space', 'auriane' ),
				'description' => esc_html__( 'Type a certain number for the space between heading and link.', 'auriane' ),
				'type'        => Controls_Manager::SLIDER,
				'size_units'  => array(
					'px',
					'%',
				),
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => -50,
						'max'  => 50,
					),
					'%'  => array(
						'step' => 1,
						'min'  => 0,
						'max'  => 100,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .link' => 'margin-left: {{SIZE}}{{UNIT}};',
				),
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_title_style',
			array(
				'label' => esc_html__( 'Title', 'auriane' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_responsive_control(
			'title_spacing',
			array(
				'label'       => esc_html__( 'Title Spacing', 'auriane' ),
				'description' => esc_html__( 'Controls the padding of your heading.', 'auriane' ),
				'type'        => Controls_Manager::DIMENSIONS,
				'size_units'  => array(
					'px',
					'em',
					'%',
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
				),
			)
		);

		$this->add_control(
			'title_color',
			array(
				'label'       => esc_html__( 'Title Color', 'auriane' ),
				'description' => esc_html__( 'Controls the heading color.', 'auriane' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title' => 'color: {{VALUE}};',
				),
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'     => 'title_typography',
				
				'selector' => '.elementor-element-{{ID}} .title',
			)
		);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_link_style',
			array(
				'label' => esc_html__( 'Link', 'auriane' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_group_control(
			Group_Control_Typography::get_type(),
			array(
				'name'        => 'link_typography',
				'description' => esc_html__( 'Controls the link typography.', 'auriane' ),
				
				'selector'    => '.elementor-element-{{ID}} .link',
			)
		);

		$this->start_controls_tabs( 'tabs_heading_link' );

		$this->start_controls_tab(
			'tab_link_color_normal',
			array(
				'label' => esc_html__( 'Normal', 'auriane' ),
			)
		);

		$this->add_control(
			'link_color',
			array(
				'label'       => esc_html__( 'Link Color', 'auriane' ),
				'description' => esc_html__( 'Controls the link color.', 'auriane' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .link' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->start_controls_tab(
			'tab_link_color_hover',
			array(
				'label' => esc_html__( 'Hover', 'auriane' ),
			)
		);

		$this->add_control(
			'link_hover_color',
			array(
				'label'       => esc_html__( 'Link Color', 'auriane' ),
				'description' => esc_html__( 'Controls the link hover color.', 'auriane' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .link:hover' => 'color: {{VALUE}};',
				),
			)
		);

		$this->end_controls_tab();

		$this->end_controls_tabs();

		$this->end_controls_section();

		$this->start_controls_section(
			'section_heading_border_style',
			array(
				'label' => esc_html__( 'Border', 'auriane' ),
				'tab'   => Controls_Manager::TAB_STYLE,
			)
		);

		$this->add_control(
			'border_color',
			array(
				'label'       => esc_html__( 'Color', 'auriane' ),
				'description' => esc_html__( 'Controls the border color.', 'auriane' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title-cross .title::before, .elementor-element-{{ID}} .title-cross .title::after, .elementor-element-{{ID}} .title-underline::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_active_color',
			array(
				'label'       => esc_html__( 'Active Color', 'auriane' ),
				'description' => esc_html__( 'Controls the active border color.', 'auriane' ),
				'type'        => Controls_Manager::COLOR,
				'selectors'   => array(
					'.elementor-element-{{ID}} .title-underline .title::after' => 'background-color: {{VALUE}};',
				),
			)
		);

		$this->add_control(
			'border_height',
			array(
				'label'       => esc_html__( 'Height', 'auriane' ),
				'description' => esc_html__( 'Controls the border width.', 'auriane' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title::before, .elementor-element-{{ID}} .title::after, .elementor-element-{{ID}} .title-wrapper::after' => 'height: {{SIZE}}px;',
				),
			)
		);

		$this->add_control(
			'active_border_height',
			array(
				'label'       => esc_html__( 'Active Border Height', 'auriane' ),
				'description' => esc_html__( 'Controls the active border width.', 'auriane' ),
				'type'        => Controls_Manager::SLIDER,
				'range'       => array(
					'px' => array(
						'step' => 1,
						'min'  => 1,
						'max'  => 30,
					),
				),
				'selectors'   => array(
					'.elementor-element-{{ID}} .title-underline .title::after' => 'height: {{SIZE}}px;',
				),
				'condition'   => array(
					'decoration' => 'underline',
				),
			)
		);

		$this->end_controls_section();
	}

	protected function render() {
		$settings = $this->get_settings_for_display();
		if ( 'custom' == $settings['content_type'] ) {
			$this->add_inline_editing_attributes( 'title' );
		}
		$this->add_inline_editing_attributes( 'link_label' );
		include 'content.php';
	}
}
