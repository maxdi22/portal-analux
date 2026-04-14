<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * jws Custom Css & Js
 *
 *
 * @since 1.0
 * @since 1.0 added in independent widget file
 */

use Elementor\Controls_Manager;

add_action( 'jws_elementor_add_common_options', 'jws_elementor_add_css_js_controls', 90 );

function jws_elementor_add_css_js_controls( $self ) {
	$self->start_controls_section(
		'_jws_section_custom_css',
		array(
			'label' => __( 'Custom Page CSS', 'auriane' ),
			'tab'   => Jws_Elementor_Editor_Custom_Tabs::TAB_CUSTOM,
		)
	);

		$self->add_control(
			'_jws_custom_css',
			array(
				'type' => Controls_Manager::TEXTAREA,
				'rows' => 40,
			)
		);

	$self->end_controls_section();
}
