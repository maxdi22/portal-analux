<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Jws_Elementor_Editor_Custom_Tabs
 *
 * register new custom tabs to elementor editor
 *
 * @since 1.0
 */

use Elementor\Controls_Manager;

class Jws_Elementor_Editor_Custom_Tabs {
	const TAB_CUSTOM = 'jws_custom_tab';

	private $custom_tabs;

	public function __construct() {
		$this->init_custom_tabs();

		$this->register_custom_tabs();
	}

	private function init_custom_tabs() {
		$this->custom_tabs = array();

		$this->custom_tabs[ $this::TAB_CUSTOM ] = esc_html__( 'Jws Options', 'auriane' );
	}

	public function register_custom_tabs() {
		foreach ( $this->custom_tabs as $key => $value ) {
			Elementor\Controls_Manager::add_tab( $key, $value );
		}
	}
}

new Jws_Elementor_Editor_Custom_Tabs;
