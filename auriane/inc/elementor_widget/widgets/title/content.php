<?php
if ( ! defined( 'ABSPATH' ) ) {
	die();
}

/**
 * jws Heading Widget Render
 *
 */

extract( // @codingStandardsIgnoreLine
	shortcode_atts(
		array(
			'content_type'    => 'custom',
			'dynamic_content' => 'title',
			'userinfo_type'   => 'display_name',
			'title'           => '',
			'tag'             => 'h2',
			'decoration'      => '',
			'show_link'       => '',
			'link_url'        => '',
			'link_label'      => '',
			'title_align'     => '',
			'link_align'      => '',
			'icon_pos'        => 'after',
			'icon'            => '',
			'show_divider'    => '',
			'class'           => '',
		),
		$settings
	)
);


$html = '';

if ( 'dynamic' == $content_type ) {
	$title = '';


	if ( 'title' == $dynamic_content ) {
	    if ( function_exists( 'jws_is_shop' ) && jws_is_shop() ) {   
	       	$title = get_the_title(get_option( 'woocommerce_shop_page_id' ));
	    }else {
	       	$title = get_the_archive_title();
	    }
	
        
	} 

	// Site Tag Line
	if ( 'site_tagline' == $dynamic_content ) {
		$title = esc_html( get_bloginfo( 'description' ) );
       
	}
	// Site Title
	if ( 'site_title' == $dynamic_content ) {
		$title = esc_html( get_bloginfo() );
	}
	// Current DateTime
	if ( 'date' == $dynamic_content ) {
		$format      = '';
		$date_format = get_option( 'date_format' );
		$time_format = get_option( 'time_format' );

		if ( $date_format ) {
			$format   = $date_format;
			$has_date = true;
		} else {
			$has_date = false;
		}

		if ( $time_format ) {
			if ( $has_date ) {
				$format .= ' ';
			}
			$format .= $time_format;
		}

		$title = esc_html( date_i18n( $format ) );
	}
	// User Info
	if ( 'user_info' == $dynamic_content ) {
		$user = wp_get_current_user();
		if ( empty( $userinfo_type ) || 0 === $user->ID ) {
			return;
		}

		$value = '';
		switch ( $userinfo_type ) {
			case 'login':
			case 'email':
			case 'url':
			case 'nicename':
				$field = 'user_' . $userinfo_type;
				$value = isset( $user->$field ) ? $user->$field : '';
				break;
			case 'id':
				$value = $user->ID;
				break;
			case 'description':
			case 'first_name':
			case 'last_name':
			case 'display_name':
				$value = isset( $user->$userinfo_type ) ? $user->$userinfo_type : '';
				break;
			case 'meta':
				$key = $this->get_settings( 'meta_key' );
				if ( ! empty( $key ) ) {
					$value = get_user_meta( $user->ID, $key, true );
				}
				break;
		}

		$title = esc_html( $value );
	}
}

if ( $title ) {
	$class = $class ? $class . ' title' : 'title';

	$wrapp_class = '';
	if ( $decoration ) {
		$wrapp_class .= ' title-' . $decoration;
	}

	if ( $title_align ) {
		$wrapp_class .= ' ' . $title_align;
	}

	if ( $link_align ) {
		$wrapp_class .= ' ' . $link_align;
	}
	$link_label = '<span ' . $this->get_render_attribute_string( 'link_label' ) . '>' . esc_html( $link_label ) . '</span>';

	if ( is_array( $icon ) && $icon['value'] ) {
		if ( 'before' == $icon_pos ) {
			$wrapp_class .= ' icon-before';
			$link_label   = '<i class="' . $icon['value'] . '"></i>' . $link_label;
		} else {
			$wrapp_class .= ' icon-after';
			$link_label  .= '<i class="' . $icon['value'] . '"></i>';
		}
	}
	$html .= '<div class="title-wrapper ' . $wrapp_class . '">';

	$this->add_render_attribute( 'title', 'class', $class );
	$html .= sprintf( '<%1$s ' . $this->get_render_attribute_string( 'title' ) . '>%2$s</%1$s>', $tag, $title );

	if ( 'yes' == $show_link ) { // If Link is allowed
		if ( 'yes' == $show_divider ) {
			$html .= '<span class="divider"></span>';
		}
		$html .= sprintf( '<a href="%1$s" class="link">%2$s</a>', $link_url['url'], ( $link_label ) );
	}
	$html .= '</div>';
}

echo do_shortcode($html );
