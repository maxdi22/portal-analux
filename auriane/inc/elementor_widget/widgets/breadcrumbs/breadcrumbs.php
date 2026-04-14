<?php
namespace Elementor;

// Elementor Extras Classes
use Elementor\Widget_Base;


// Elementor Classes
use Elementor\Icons_Manager;
use Elementor\Controls_Manager;
use Elementor\Repeater;
use Elementor\Group_Control_Border;
use Elementor\Group_Control_Typography;
use Elementor\Group_Control_Text_Shadow;
use Elementor\Group_Control_Image_Size;
use Elementor\Group_Control_Box_Shadow;
use Elementor\Core\Schemes\Color;
use Elementor\Core\Schemes\Typography;
use Elementor\Modules\DynamicTags\Module as TagsModule;

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

/**
 * Breadcrumbs
 *
 * @since 1.2.0
 */
class Jws_Breadcrumbs extends Widget_Base {

	/**
	 * Query
	 *
	 * @since  1.2.0
	 * @var    \WP_Query
	 */
	private $_query = null;

	/**
	 * Separator
	 *
	 * @since  1.2.0
	 * @var    string
	 */
	private $_separator = null;

	/**
	 * Get Name
	 * 
	 * Get the name of the widget
	 *
	 * @since  1.2.0
	 * @return string
	 */
	public function get_name() {
		return 'jws-breadcrumbs';
	}

	/**
	 * Get Title
	 * 
	 * Get the title of the widget
	 *
	 * @since  1.2.0
	 * @return string
	 */
	public function get_title() {
		return esc_html__( 'Jws Breadcrumbs', 'auriane' );
	}

	/**
	 * Get Icon
	 * 
	 * Get the icon of the widget
	 *
	 * @since  1.2.0
	 * @return string
	 */
	public function get_icon() {
		return 'eicon-product-breadcrumbs';
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
	 * Register Widget Controls
	 *
	 * @since  1.2.0
	 * @return void
	 */
	protected function register_controls() {
		
		$this->start_controls_section(
			'section_content',
			[
				'label' => esc_html__( 'Display', 'auriane' ),
			]
		);

			$this->add_control(
				'source',
				[
					'label' 	=> esc_html__( 'Source', 'auriane' ),
					'type'		=> Controls_Manager::SELECT,
					'default'	=> '',
					'options'	=> [
						''		=> esc_html__( 'Current page', 'auriane' ),
						'id'	=> esc_html__( 'Specific page', 'auriane' ),
					]
				]
			);

			$this->add_control(
				'source_id',
				[
					'label' 		=> esc_html__( 'ID', 'auriane' ),
					'type'			=> Controls_Manager::NUMBER,
					'min' 			=> 0,
					'placeholder' 	=> '15',
					'condition'		=> [
						'source'	=> 'id',
					]
				]
			);
            
            $this->add_control(
				'show_title',
				[
					'label' 		=> esc_html__( 'Show Title', 'auriane' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'no',
					'label_on' 		=> esc_html__( 'Yes', 'auriane' ),
					'label_off' 	=> esc_html__( 'No', 'auriane' ),
					'return_value' 	=> 'yes',
				]
			);


			$this->add_control(
				'show_home',
				[
					'label' 		=> esc_html__( 'Show Home', 'auriane' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> esc_html__( 'Yes', 'auriane' ),
					'label_off' 	=> esc_html__( 'No', 'auriane' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'show_current',
				[
					'label' 		=> esc_html__( 'Show Current', 'auriane' ),
					'type' 			=> Controls_Manager::SWITCHER,
					'default' 		=> 'yes',
					'label_on' 		=> esc_html__( 'Yes', 'auriane' ),
					'label_off' 	=> esc_html__( 'No', 'auriane' ),
					'return_value' 	=> 'yes',
				]
			);

			$this->add_control(
				'cpt_crumbs',
				[
					'label' 		=> esc_html__( 'CPT Crumbs', 'auriane' ),
					'type' 			=> Controls_Manager::SELECT,
					'default' 		=> '',
					'options'		=> [
						'' 			=> esc_html__( 'CPT Name', 'auriane' ),
						'terms' 	=> esc_html__( 'Taxonomy Terms', 'auriane' ),
						'both' 		=> esc_html__( 'Both', 'auriane' ),
					],
				]
			);

			$this->add_control(
				'home_text',
				[
					'label' 		=> esc_html__( 'Home Text', 'auriane' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> esc_html__( 'Homepage', 'auriane' ),
					'dynamic'		=> [
						'active'	=> true,
						'categories' => [ TagsModule::POST_META_CATEGORY ]
					],
					'condition'		=> [
						'show_home' => 'yes'
					],
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_separator',
			[
				'label' => esc_html__( 'Separator', 'auriane' ),
			]
		);

			$this->add_control(
				'separator_type',
				[
					'label'		=> esc_html__( 'Type', 'auriane' ),
					'type' 		=> Controls_Manager::SELECT,
					'default' 	=> 'icon',
					'options' 	=> [
						'text' 		=> esc_html__( 'Text', 'auriane' ),
						'icon' 		=> esc_html__( 'Icon', 'auriane' ),
					],
				]
			);

			$this->add_control(
				'separator_text',
				[
					'label' 		=> esc_html__( 'Text', 'auriane' ),
					'type' 			=> Controls_Manager::TEXT,
					'default' 		=> esc_html__( '>', 'auriane' ),
					'condition'		=> [
						'separator_type' => 'text'
					],
				]
			);

			$this->add_control(
				'selected_separator_icon',
				[
					'label' => esc_html__( 'Icon', 'auriane' ),
					'type' => \Elementor\Controls_Manager::ICONS,
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_item_style',
			[
				'label' 	=> esc_html__( 'Crumbs', 'auriane' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);


			$this->add_responsive_control(
				'items_text_align',
				[
					'label' 		=> esc_html__( 'Align Text', 'auriane' ),
					'type' 			=> Controls_Manager::CHOOSE,
					'default' 		=> '',
					'options' 		=> [
						'left' 			=> [
							'title' 	=> esc_html__( 'Left', 'auriane' ),
							'icon' 		=> 'fa fa-align-left',
						],
						'center' 		=> [
							'title' 	=> esc_html__( 'Center', 'auriane' ),
							'icon' 		=> 'fa fa-align-center',
						],
						'right' 		=> [
							'title' 	=> esc_html__( 'Right', 'auriane' ),
							'icon' 		=> 'fa fa-align-right',
						],
					],
					'selectors' => [
						'{{WRAPPER}} .jws-breadcrumbs' => 'text-align: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'item_spacing',
				[
					'label' 	=> esc_html__( 'Spacing', 'auriane' ),
					'type' 		=> Controls_Manager::SLIDER,
					'default'	=> [
						'size'	=> 12
					],
					'range' 	=> [
						'px' 	=> [
							'max' => 36,
						],
					],
					'selectors' => [
						'{{WRAPPER}} .jws-breadcrumbs__item' => 'margin-left: {{SIZE}}{{UNIT}};',
						'{{WRAPPER}} .jws-breadcrumbs__separator' => 'margin-left: {{SIZE}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'item_padding',
				[
					'label' 		=> esc_html__( 'Padding', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-breadcrumbs__item' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'allowed_dimensions' => [ 'right', 'left' ],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'item_border',
					'label' 	=> esc_html__( 'Border', 'auriane' ),
					'selector' 	=> '{{WRAPPER}} .jws-breadcrumbs__item',
				]
			);

			$this->add_control(
				'item_border_radius',
				[
					'label' 		=> esc_html__( 'Border Radius', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-breadcrumbs__item' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'item_typography',
					'label' 	=> esc_html__( 'Typography', 'auriane' ),
					
					'selector' 	=> '{{WRAPPER}} .jws-breadcrumbs__text',
				]
			);

			$this->start_controls_tabs( 'crumb_style' );

			$this->start_controls_tab( 'crumb_default', [ 'label' => esc_html__( 'Default', 'auriane' ) ] );

				$this->add_control(
					'item_background_color',
					[
						'label' 	=> esc_html__( 'Background Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-breadcrumbs__item' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'item_color',
					[
						'label' 	=> esc_html__( 'Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws-breadcrumbs__item' => 'color: {{VALUE}};',
							'{{WRAPPER}} .jws-breadcrumbs__item a' => 'color: {{VALUE}};',
						],
					]
				);


			$this->end_controls_tab();

			$this->start_controls_tab( 'crumb_hover', [ 'label' => esc_html__( 'Hover', 'auriane' ) ] );

				$this->add_control(
					'item_background_color_hover',
					[
						'label' 	=> esc_html__( 'Background Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'selectors' => [
							'{{WRAPPER}} .jws-breadcrumbs__item:hover' => 'background-color: {{VALUE}};',
						],
					]
				);

				$this->add_control(
					'item_color_hover',
					[
						'label' 	=> esc_html__( 'Color', 'auriane' ),
						'type' 		=> Controls_Manager::COLOR,
						'default'	=> '',
						'selectors' => [
							'{{WRAPPER}} .jws-breadcrumbs__item:hover a' => 'color: {{VALUE}};',
						],
					]
				);
			
			$this->end_controls_tab();

			$this->end_controls_tabs();

		$this->end_controls_section();
        
        $this->start_controls_section(
			'section_title_style',
			[
				'label' 	=> esc_html__( 'Title', 'auriane' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
                'condition'		=> [
						'show_title' => 'yes'
					],
			]
		);

			$this->add_responsive_control(
				'title_padding',
				[
					'label' 		=> esc_html__( 'Padding', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} h1' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
			
				]
			);

            	$this->add_responsive_control(
				'title_margin',
				[
					'label' 		=> esc_html__( 'Margin', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} h1' => 'margin: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				
				]
			);
			$this->add_control(
				'title_color',
				[
					'label' 	=> esc_html__( 'Color', 'auriane' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} h1' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'title_typography',
					'label' 	=> esc_html__( 'Typography', 'auriane' ),
					
					'selector' 	=> '{{WRAPPER}} h1',
				]
			);

		$this->end_controls_section();
        
        
		$this->start_controls_section(
			'section_separator_style',
			[
				'label' 	=> esc_html__( 'Separators', 'auriane' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_control(
				'separator_padding',
				[
					'label' 		=> esc_html__( 'Padding', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', 'em', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-breadcrumbs__separator' => 'padding: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
					'allowed_dimensions' => [ 'right', 'left' ],
				]
			);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'separator_border',
					'label' 	=> esc_html__( 'Border', 'auriane' ),
					'selector' 	=> '{{WRAPPER}} .jws-breadcrumbs__separator',
				]
			);

			$this->add_control(
				'separator_border_radius',
				[
					'label' 		=> esc_html__( 'Border Radius', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-breadcrumbs__separator' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'separator_background_color',
				[
					'label' 	=> esc_html__( 'Background Color', 'auriane' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .jws-breadcrumbs__separator' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'separator_color',
				[
					'label' 	=> esc_html__( 'Color', 'auriane' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .jws-breadcrumbs__separator' => 'color: {{VALUE}};',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'separator_typography',
					'label' 	=> esc_html__( 'Typography', 'auriane' ),
					
					'selector' 	=> '{{WRAPPER}} .jws-breadcrumbs__separator',
				]
			);

		$this->end_controls_section();

		$this->start_controls_section(
			'section_current_style',
			[
				'label' 	=> esc_html__( 'Current', 'auriane' ),
				'tab' 		=> Controls_Manager::TAB_STYLE,
			]
		);

			$this->add_group_control(
				Group_Control_Border::get_type(),
				[
					'name' 		=> 'current_border',
					'label' 	=> esc_html__( 'Border', 'auriane' ),
					'selector' 	=> '{{WRAPPER}} .jws-breadcrumbs__item--current',
				]
			);

			$this->add_control(
				'current_border_radius',
				[
					'label' 		=> esc_html__( 'Border Radius', 'auriane' ),
					'type' 			=> Controls_Manager::DIMENSIONS,
					'size_units' 	=> [ 'px', '%' ],
					'selectors' 	=> [
						'{{WRAPPER}} .jws-breadcrumbs__item--current' => 'border-radius: {{TOP}}{{UNIT}} {{RIGHT}}{{UNIT}} {{BOTTOM}}{{UNIT}} {{LEFT}}{{UNIT}};',
					],
				]
			);

			$this->add_control(
				'current_background_color',
				[
					'label' 	=> esc_html__( 'Background Color', 'auriane' ),
					'type' 		=> Controls_Manager::COLOR,
					'selectors' => [
						'{{WRAPPER}} .jws-breadcrumbs__item--current' => 'background-color: {{VALUE}};',
					],
				]
			);

			$this->add_control(
				'current_color',
				[
					'label' 	=> esc_html__( 'Color', 'auriane' ),
					'type' 		=> Controls_Manager::COLOR,
					'default'	=> '',
					'selectors' => [
						'{{WRAPPER}} .jws-breadcrumbs__item--current' => 'color: {{VALUE}} !important;',
					],
				]
			);

			$this->add_group_control(
				Group_Control_Typography::get_type(),
				[
					'name' 		=> 'current_typography',
					'label' 	=> esc_html__( 'Typography', 'auriane' ),
					
					'selector' 	=> '{{WRAPPER}} .jws-breadcrumbs__item--current .jws-breadcrumbs__text',
				]
			);

		$this->end_controls_section();

	}

	/**
	 * Get Query
	 *
	 * @since  1.2.0
	 * @return \WP_Query|bool
	 */
	protected function get_query() {

		global $post;

		$settings 	= $this->get_settings_for_display();
		$_id 		= null;
		$_post_type = 'post';

		if ( 'id' === $settings['source'] && '' !== $settings['source_id'] ) {

			$_id = $settings['source_id'];
			$_post_type = 'any';

			$_args = array(
				'p' 		=> $_id,
				'post_type' => $_post_type,
			);

			// Create custom query
			$_post_query = new \WP_Query( $_args );

			return $_post_query;
		}

		return false;
	}

	/**
	 * Set Separator
	 *
	 * Sets the markup for the breadcrumbs separator
	 *
	 * @since  1.2.0
	 * @return string
	 */
	protected function set_separator() {

		$settings = $this->get_settings_for_display();
		$separator = '';

		if ( 'icon' === $settings['separator_type'] ) {
			if ( ! empty( $settings['separator_icon'] ) || ! empty( $settings['selected_separator_icon']['value'] ) ) {
				$migrated = isset( $settings['__fa4_migrated']['selected_separator_icon'] );
				$is_new = empty( $settings['icon'] ) && Icons_Manager::is_migration_allowed();

				$this->add_render_attribute( 'icon-wrapper', 'class', [
					'jws-icon',
					'jws-icon-support--svg',
				] );

				$separator .= '<span ' . $this->get_render_attribute_string( 'icon-wrapper' ) . '>';
				
				if ( $is_new || $migrated ) {
					ob_start();
			
                     \Elementor\Icons_Manager::render_icon( $settings['selected_separator_icon'], [ 'aria-hidden' => 'true' ] ); 
                   
					$separator .= ob_get_clean();
				} else {
					$this->add_render_attribute( 'icon', [
						'class' => $settings['separator_icon'],
						'aria-hidden' => 'true',
					] );

					ob_start();
					$separator .= '<i '. $this->get_render_attribute_string('icon') . '></i>';
				}

				$separator .= '</span>';
			}
		} else {
			$this->add_inline_editing_attributes( 'separator_text' );
			$this->add_render_attribute( 'separator_text', 'class', 'jws-breadcrumbs__separator__text' );
			
			$separator = '<span ' . $this->get_render_attribute_string( 'separator_text' ) . '>' . $settings['separator_text'] . '</span>';
		}

		$this->_separator = $separator;
	}

	/**
	 * Get Separator
	 *
	 * @since  1.2.0
	 * @return var\string
	 */
	protected function get_separator() {
		return $this->_separator;
	}

	/**
	 * Render
	 * 
	 * Render widget contents on frontend
	 *
	 * @since  1.2.0
	 * @return void
	 */
	protected function render() {

		$settings 	= $this->get_settings_for_display();
		$_query 	= $this->get_query();

		$this->set_separator();
		$this->add_render_attribute( 'breadcrumbs', [
			'class' => 'jws-breadcrumbs',
			'itemscope' => "",
			'itemtype' => "http://schema.org/BreadcrumbList",
		]);
        if($settings['show_title'] == 'yes') {
           echo '<h1>'.get_the_title().'</h1>'; 
        }
        
		if ( $_query ) {
			if ( $_query->have_posts() ) {

				// Setup post
				$_query->the_post();
              
				// Render using the new query
				$this->render_breadcrumbs( $_query );

				// Reset post data to original query
				wp_reset_postdata();

			} else {

				_e( 'Post or page not found', 'auriane' );

			}
		} else {
			// Render using the original query
			$this->render_breadcrumbs();
		}
	}

	/**
	 * Render Home Link
	 * 
	 * The markup for the home link crumb
	 *
	 * @since  1.2.0
	 * @return void
	 */
	protected function render_home_link() {
		$settings = $this->get_settings_for_display();

		$this->add_item_render_attribute( 'home-item', 0 );
		$this->add_render_attribute( 'home-item', [
			'class' => 'jws-breadcrumbs__item--home',
		] );

		$this->add_link_render_attribute( 'home-link' );
		$this->add_render_attribute( 'home-link', [
			'class' => [
				'jws-breadcrumbs__crumb--link',
				'jws-breadcrumbs__crumb--home'
			],
			'href' 	=> get_home_url('/'),
			'title' => $settings['home_text'],
		] );

		$this->add_render_attribute( 'home-text', [
			'itemprop' => 'name',
			'class' => 'jws-breadcrumbs__text',
		] );

		?><li <?php echo ''.$this->get_render_attribute_string( 'home-item' ); ?>>
			<a <?php echo ''.$this->get_render_attribute_string( 'home-link' ); ?>>
				<span <?php echo ''.$this->get_render_attribute_string( 'home-text' ); ?>>
					<?php echo ''.$settings['home_text']; ?>
				</span>
			</a>
		</li><?php

		$this->render_separator();

	}

	/**
	 * Render Separator
	 * 
	 * The markup for the separator item
	 *
	 * @since  1.2.0
	 * @return void
	 */
	protected function render_separator( $output = true ) {

		$this->add_render_attribute( 'separator', [
			'class' => [
				'jws-breadcrumbs__separator',
			],
		] );

		$markup = '<li ' . $this->get_render_attribute_string( 'separator' ) . '>';
		$markup .= $this->get_separator();
		$markup .= '</li>';

		if ( $output === true ) {
			echo ''.$markup;
			return;
		}

		return $markup;
	}
    /**
	 * Get list of terms for a specific post ID
	 * from a taxonomy with highter number of terms used
	 *
	 * @since 2.0.0
	 */
	protected function get_parent_terms_highest( $post_id ) {

		$taxonomies = get_post_taxonomies( $post_id );
		$tax 		= $taxonomies[0];
		$tax_term_c = 0;

		foreach ( $taxonomies as $taxonomy => $name ) {
			$taxonomy_terms = wp_get_post_terms( $post_id, $name );

			if ( count( $taxonomy_terms ) > $tax_term_c ) {
				$tax_term_c = count( $taxonomy_terms );
				$tax 		= $name;
			}
		}

		$terms = wp_get_post_terms( $post_id, $tax );

		return $terms;
	}
    	/**
	 * Get category with highest number of parents
	 * from a given list
	 *
	 * @since 2.0.0
	 */
	public static function get_most_parents_category( $categories = [] ) {

		$counted_cats = [];

		if ( ! is_array( $categories ) )
			return $categories;

		foreach ( $categories as $category ) {
			$category_parents = get_category_parents( $category->term_id, false, ',' );
			$category_parents = explode( ',', $category_parents );
			$counted_cats[ $category->term_id ] = count( $category_parents );
		}

		arsort( $counted_cats );
		reset( $counted_cats );

		return key( $counted_cats );
	}
	/**
	 * Render Breadcrumbs
	 * 
	 * Identifies and outputs all the breadcrumbs
	 *
	 * @since  1.2.0
	 * @return void
	 */
     
	protected function render_breadcrumbs( $query = false ) {

		global $post, $wp_query;

		if ( $query === false ) {

			// Reset post data to parent query
			$wp_query->reset_postdata();

			// Set active query to native query
			$query = $wp_query;
		}

		$settings = $this->get_settings_for_display();
		$separator = $this->get_separator();

		$custom_taxonomy = 'product_cat';

		if ( ! $query->is_front_page() ) { ?>
		
			<ul <?php echo ''.$this->get_render_attribute_string( 'breadcrumbs' ); ?>>

			<?php

			if ( 'yes' === $settings['show_home'] ) {
				$this->render_home_link();
			}


			if ( $query->is_archive() && ! $query->is_tax() && ! $query->is_category() && ! $query->is_tag() && ! $query->is_date() && ! $query->is_author() ) {

				$this->render_item( 'archive', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'archive',
					'content' 	=> post_type_archive_title( '', false ),
				] );
				

			} else if ( $query->is_archive() && $query->is_tax() && ! $query->is_category() && ! $query->is_tag() ) {

				$queried_object = get_queried_object();
				$parents = get_ancestors( $queried_object->term_id, $queried_object->taxonomy );

				$post_type = get_post_type();
                
                if(function_exists('is_product_category') && is_product_category()) {
                    $post_type = 'product';
                }
                
				$post_type_object = get_post_type_object( $post_type );
                
                if(isset($post_type_object->labels->name)) {
                   
                   $this->render_item( 'post-type-archive', [
    					'index'		=> 1,
    					'current' 	=> false,
    					'separator'	=> true,
    					'key' 		=> 'post-type-archive',
    					'ids' 		=> [ $post_type ],
    					'content' 	=> $post_type_object->labels->name,
    					'link'		=> get_post_type_archive_link( $post_type ),
    				] ); 
                    
                }            
        

				if ( $parents )  {
					$parent_terms = get_terms( [
						'taxonomy' => $queried_object->taxonomy,
						'include' => $parents,
					] );

					$parent_terms = array_reverse( $parent_terms );

					$counter = 2;
					foreach ( $parent_terms as $term ) {
						$this->render_item( 'custom-tax-archive-parents', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'custom-tax-archive-' . $term->term_id,
							'ids' 		=> [ $term->term_id, $term->slug ],
							'content' 	=> $term->name,
							'link'		=> get_term_link( $term ),
						] );
						$counter++;
					}
				}

				$this->render_item( 'custom-tax-archive', [

					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'custom-tax-archive',
					'ids' 		=> [ $post_type ],
					'content' 	=> get_queried_object()->name,
					'link'		=> '',
				] );

			} else if ( $query->is_post_type_archive() ) {

				$post_type = get_post_type();
				$post_type_object = get_post_type_object( $post_type );

				$this->render_item( 'post-type-archive', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'post-type-archive',
					'ids' 		=> [ $post_type ],
					'content' 	=> $post_type_object->labels->name,
					'link'		=> get_post_type_archive_link( $post_type ),
				] );
				
			} else if ( $query->is_single() ) {
				
				$post_type = get_post_type();
				
				if ( $post_type !== 'post' ) {

					$counter = 1;

					if ( '' === $settings['cpt_crumbs'] || 'both' === $settings['cpt_crumbs'] ) {

						$post_type_object = get_post_type_object( $post_type );
						$item_content = $post_type_object->labels->name;

						$this->render_item( 'post-type-archive', [
							'index'		=> 1,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'post-type-archive',
							'ids' 		=> [ $post_type ],
							'content' 	=> $item_content,
							'link'		=> get_post_type_archive_link( $post_type ),
						] );

						$counter++;
					}

					if ( in_array( $settings['cpt_crumbs'], [ 'terms', 'both' ] ) ) {
						$item_content = 'terms';

						$terms = $this->get_parent_terms_highest( $post->ID );

						if ( $terms ) {
							$counter = 1;
							foreach( $terms as $term ) {
								$this->render_item( 'post-type-terms', [
									'index'		=> $counter,
									'current' 	=> false,
									'separator'	=> true,
									'key' 		=> 'terms-' . $term->term_id,
									'ids' 		=> [ $term->term_id, $term->slug ],
									'content' 	=> $term->name,
									'link'		=> get_term_link( $term ),
								] );

								$counter++;
							}
						}

					}
					
				} else {

					$posts_page_id = get_option( 'page_for_posts' );

					if ( $posts_page_id ) {

						$posts_page = get_post( $posts_page_id );

						$this->render_item( 'blog', [
							'index'		=> 1,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'blog',
							'ids' 		=> [ $posts_page->ID ],
							'content' 	=> $posts_page->post_title,
							'link'		=> get_permalink( $posts_page->ID ),
						] );
					}
				}

				$category = get_the_category();
				$last_category = null;

				if( ! empty( $category ) ) {

					$cat_display = '';

					$values = array_values($category);

					$last_category = get_term( $this->get_most_parents_category( $category ) );
						
					$cat_parents = array_reverse( get_ancestors( $last_category->term_id, 'category' ) );
				}

				$taxonomy_exists = taxonomy_exists( $custom_taxonomy );

				if( empty( $last_category ) && ! empty( $custom_taxonomy ) && $taxonomy_exists ) {
						$taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );

					if ( $taxonomy_terms ) {
						$cat_id = $taxonomy_terms[0]->term_id;
						$cat_nicename = $taxonomy_terms[0]->slug;
						$cat_link = get_term_link( $taxonomy_terms[0]->term_id, $custom_taxonomy );
						$cat_name = $taxonomy_terms[0]->name;
					}
				}

				if( ! empty( $last_category ) ) {
					$counter = 1;

					foreach ( $cat_parents as $parent ) {
						$_parent = get_term( $parent );

						if ( has_category( $_parent->term_id, $post ) ) {

							$this->render_item( 'category', [
								'index'		=> $counter,
								'current' 	=> false,
								'separator'	=> true,
								'key' 		=> 'category-' . $_parent->term_id,
								'ids' 		=> [ $_parent->term_id, $_parent->slug ],
								'content' 	=> $_parent->name,
								'link'		=> get_term_link( $_parent ),
							] );

							$counter++;
						}
					}

					$this->render_item( 'category', [
						'index'		=> $counter,
						'current' 	=> false,
						'separator'	=> true,
						'key' 		=> 'category' . $last_category->term_id,
						'ids' 		=> [ $last_category->term_id, $last_category->slug ],
						'content' 	=> $last_category->name,
						'link'		=> get_term_link( $last_category ),
					] );

					$this->render_item( 'single', [
						'index'		=> $counter++,
						'current' 	=> true,
						'separator'	=> false,
						'key' 		=> 'single',
						'ids' 		=> [ $post->ID ],
						'content' 	=> get_the_title(),
					] );
					
				} else if ( ! empty( $cat_id ) ) {
					$this->render_item( 'single', [
						'index'		=> 2,
						'current' 	=> true,
						'separator'	=> false,
						'key' 		=> 'single',
						'ids' 		=> [ $post->ID ],
						'content' 	=> get_the_title(),
					] );

				} else {

					$this->render_item( 'single', [
						'index'		=> 1,
						'current' 	=> true,
						'separator'	=> false,
						'key' 		=> 'single',
						'ids' 		=> [ $post->ID ],
						'content' 	=> get_the_title(),
					] );

				}
				
			} else if ( $query->is_category() ) {

				$cat_id = get_query_var( 'cat' );
				$cat = get_category( $cat_id );

				$cat_parents = array_reverse( get_ancestors( $cat_id, 'category' ) );
				$counter = 1;

				foreach ( $cat_parents as $parent ) {
					$_parent = get_term( $parent );

					$this->render_item( 'category', [
						'index'		=> $counter,
						'current' 	=> false,
						'separator'	=> true,
						'key' 		=> 'category-' . $_parent->term_id,
						'ids' 		=> [ $_parent->term_id, $_parent->slug ],
						'content' 	=> $_parent->name,
						'link'		=> get_term_link( $_parent ),
					] );
					$counter++;
				}

				$this->render_item( 'category', [
					'index'		=> $counter,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'category',
					'ids' 		=> [ $cat_id, $cat->slug ],
					'content' 	=> single_cat_title( '', false ),
				] );
				
			} else if ( $query->is_page() ) {
				
				if ( $post->post_parent ) {
						
					$anc = get_post_ancestors( $post->ID );
					$anc = array_reverse($anc);
						
					if ( ! isset( $parents ) ) $parents = null;

					$counter = 1;

					foreach ( $anc as $ancestor ) {

						$this->render_item( 'ancestor', [
							'index'		=> $counter,
							'current' 	=> false,
							'separator'	=> true,
							'key' 		=> 'ancestor-' . $ancestor,
							'ids' 		=> [ $ancestor ],
							'content' 	=> get_the_title( $ancestor ),
							'link'		=> get_permalink( $ancestor ),
						] );

						$counter++;
					}
				}

				$counter = 1;

				$this->render_item( 'page', [
					'index'		=> $counter,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'page',
					'ids' 		=> [ $post->ID ],
					'content' 	=> get_the_title(),
				] );
				
			} else if ( $query->is_tag() ) {
				
				$term_id 		= get_query_var('tag_id');
				$taxonomy 		= 'post_tag';
				$args 			= 'include=' . $term_id;
				$terms 			= get_terms( $taxonomy, $args );
				$get_term_id 	= $terms[0]->term_id;
				$get_term_slug 	= $terms[0]->slug;
				$get_term_name 	= $terms[0]->name;

				$this->render_item( 'tag', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'tag',
					'ids' 		=> [ $get_term_id, $get_term_slug ],
					'content' 	=> sprintf( esc_html__( 'Tag: %s', 'auriane' ), $get_term_name ),
				] );
			
			} else if ( $query->is_day() ) {

				$this->render_item( 'year', [
					'index'		=> 1,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'year',
					'ids' 		=> [ get_the_time('Y') ],
					'content' 	=> sprintf( esc_html__( '%s Archives', 'auriane' ), get_the_time('Y') ),
					'link'		=> get_year_link( get_the_time('Y') ),
				] );

				$this->render_item( 'month', [
					'index'		=> 2,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'month',
					'ids' 		=> [ get_the_time('m') ],
					'content' 	=> sprintf( esc_html__( '%s Archives', 'auriane' ), get_the_time('F') ),
					'link'		=> get_month_link( get_the_time('Y'), get_the_time('m') ),
				] );

				$this->render_item( 'day', [
					'index'		=> 3,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'day',
					'ids' 		=> [ get_the_time('j') ],
					'content' 	=> sprintf( esc_html__( '%1$s %2$s Archives', 'auriane' ), get_the_time('F'), get_the_time('jS') ),
				] );
				
			} else if ( $query->is_month() ) {

				$this->render_item( 'year', [
					'index'		=> 1,
					'current' 	=> false,
					'separator'	=> true,
					'key' 		=> 'year',
					'ids' 		=> [ get_the_time('Y') ],
					'content' 	=> sprintf( esc_html__( '%s Archives', 'auriane' ), get_the_time('Y') ),
					'link'		=> get_year_link( get_the_time('Y') ),
				] );

				$this->render_item( 'month', [
					'index'		=> 2,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'month',
					'ids' 		=> [ get_the_time('m') ],
					'content' 	=> sprintf( esc_html__( '%s Archives', 'auriane' ), get_the_time('F') ),
				] );
				
			} else if ( $query->is_year() ) {

				$this->render_item( 'year', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'year',
					'ids' 		=> [ get_the_time('Y') ],
					'content' 	=> sprintf( esc_html__( '%s Archives', 'auriane' ), get_the_time('Y') ),
				] );
				
			} else if ( $query->is_author() ) {
				
				global $author;

				$userdata = get_userdata( $author );

				$this->render_item( 'author', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'author',
					'ids' 		=> [ $userdata->user_nicename ],
					'content' 	=> sprintf( esc_html__( 'Author: %s', 'auriane' ), $userdata->display_name ),
				] );
				
			} else if ( $query->is_search() ) {

				$this->render_item( 'search', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'search',
					'content' 	=> sprintf( esc_html__( 'Search results for: %s', 'auriane' ), get_search_query() ),
				] );
			
			} elseif ( $query->is_404() ) {

				$this->render_item( '404', [
					'index'		=> 1,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> '404',
					'content' 	=> esc_html__( 'Page not found', 'auriane' ),
				] );
			} elseif ( is_home() ) { 
			 
             	$counter = 1;

				$this->render_item( 'page', [
					'index'		=> $counter,
					'current' 	=> true,
					'separator'	=> false,
					'key' 		=> 'page',
					'ids' 		=> [ $post->ID ],
					'content' 	=> get_queried_object()->post_title,
				] );
			}
		
			echo '</ul>';
			
		}
	}

	/**
	 * Render Item
	 * 
	 * Gets the markup for a breadcrumb item
	 *
	 * @since  1.2.0
	 * @param. slug|string
	 * @param  args|array
	 * @return void
	 */
	protected function render_item( $slug, $args ) {

		$defaults = [
			'current' 		=> false,
			'key' 			=> false,
			'ids'			=> [],
			'content'		=> '',
			'index'			=> false,
			'link'			=> false,
		];

		$args = wp_parse_args( $args, $defaults );

		if ( $args['current'] && '' === $this->get_settings( 'show_current' ) ) {
			return;
		}

		$item_key 	= $args['key'] . '-item';
		$text_key 	= $args['key'] . '-text';
		$link_key 	= ( ! $args['current'] ) ? '-link' : '-current';
		$link_key 	= $args['key'] . $link_key;
		$link_tag 	= ( ! $args['current'] ) ? 'a' : 'span';
		$link 		= ( ! $args['current'] ) ? ' href="' . $args['link'] .'" ' : ' ';
		$classes 	= [];

		if ( $args['current'] ) {
			$classes[] = 'jws-breadcrumbs__item--current';
		} else {
			$classes[] = 'jws-breadcrumbs__item--parent';
		}

		if ( $slug )
			$classes[] = 'jws-breadcrumbs__item--' . $slug;

		if ( $args['ids'] ) {
			foreach( $args['ids'] as $id ) {
				if ( $slug ) {
					$classes[] = 'jws-breadcrumbs__item--' . $slug . '-' . $id;
				} else { $classes[] = 'jws-breadcrumbs__item--' . $id; }
			}
		}

		$this->add_item_render_attribute( $item_key, $args['index'] );
		$this->add_render_attribute( $item_key, [
			'class' => $classes,
		] );

		$this->add_link_render_attribute( $link_key );
		$this->add_render_attribute( $text_key, [
			'itemprop' 	=> 'name',
			'class' 	=> 'jws-breadcrumbs__text',
		] );

		?><li <?php echo ''.$this->get_render_attribute_string( $item_key ); ?>>
			<<?php echo ''.$link_tag; ?><?php echo ''.$link; ?><?php echo ''.$this->get_render_attribute_string( $link_key ); ?>>
				<span <?php echo ''.$this->get_render_attribute_string( $text_key ); ?>>
					<?php echo ''.$args['content']; ?>
				</span>
			</<?php echo ''.$link_tag; ?>>
		</li><?php

		if ( ! $args['current'] )
			$this->render_separator();
	}

	/**
	 * Add Item Render Attribute
	 * 
	 * Adds the render attributes for a specified item
	 *
	 * @since  1.2.0
	 * @param  key|string 	The render attribute key for the item
	 * @param. index|int 	The index of the item. Defaults to 0 
	 * @return void
	 */
	protected function add_item_render_attribute( $key, $index = 0 ) {

		$this->add_render_attribute( $key, [
			'class' => [
				'jws-breadcrumbs__item',
			],
			'itemprop' 	=> 'itemListElement',
			'position' 	=> $index,
			'itemscope' => '',
			'itemtype' 	=> 'http://schema.org/ListItem',
		] );
	}

	/**
	 * Add Link Render Attribute
	 * 
	 * Adds the render attributes for the item link
	 *
	 * @since  1.2.0
	 * @param  key|string 	The render attribute key for the item
	 * @return void
	 */
	protected function add_link_render_attribute( $key ) {
		$this->add_render_attribute( $key, [
			'class' => [
				'jws-breadcrumbs__crumb',
			],
			'itemprop' 	=> 'item',
			'rel' 		=> 'v:url',
			'property' 	=> 'v:title',
		] );
	}

	/**
	 * Content Template
	 * 
	 * Javascript content template for quick rendering. None in this case
	 *
	 * @since  1.2.0
	 * @return void
	 */
	protected function content_template() {}
}
