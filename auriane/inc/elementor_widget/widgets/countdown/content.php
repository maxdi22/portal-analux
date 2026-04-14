<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * jws Heading Countdown Render
 *
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'date'        => '',
			'type'        => 'block',
			'label'       => 'Offer Ends In:',
			'label_type'  => '',
			'label_pos'   => '',
			'date_format' => array( 'D', 'H', 'M', 'S' ),
			'timezone'    => '',
			'hide_split'  => false,
			'enable_grid' => 'no',
		),
		$atts
	)
);

$html = '';

$container_cls = '';

if ( isset( $enable_grid ) && 'yes' == $enable_grid ) {
	$container_cls .= ' grid-countdown';
}

if ( $date ) {
	$until = strtotime( $date );
	$now   = strtotime( 'now' );
	$until = $until - $now;

	$class = 'countdown';
	if ( $label_pos ) {
		$class .= ' outer-period';
	}
	if ( $timezone ) {
		$class .= ' user-tz';
	}
	if ( true == $hide_split ) {
		$class .= ' no-split';
	}

	$format = '';
	if ( is_array( $date_format ) ) {
		foreach ( $date_format as $f ) {
			$format .= $f;
		}
	}

	$html .= '<div class="countdown-container ' . ( $type ) . '-type' . esc_attr( $container_cls ) . '">';

	if ( 'inline' == $type ) {
		$html .= '<label class="countdown-label">' . sanitize_text_field( $label ) . '</label>';
	}

	$html .= '<div class="' . esc_attr( $class ) . '" data-until="' . esc_attr( $until ) . '" data-relative="true" ' . ( 'inline' == $type ? 'data-compact="true" ' : ' ' ) . ( 'short' == $label_type ? ' data-labels-short="true"' : '' ) . ' data-format="' . esc_attr( $format ) . '" data-time-now="' . esc_attr( str_replace( '-', '/', current_time( 'mysql' ) ) ) . '" ></div>';

	$html .= '</div>';
}

echo ''.$html;
