<?php
/**
 * Flexible Content rendering and section helpers.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Returns the current page modifier class.
 *
 * @param int $post_id Current post ID.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_page_class( $post_id = 0 ) {
	if ( is_post_type_archive() ) {
		$post_type = get_query_var( 'post_type' );
		$slug      = is_array( $post_type ) ? reset( $post_type ) : $post_type;
	} else {
		$post_id = is_numeric( $post_id ) ? absint( $post_id ) : get_queried_object_id();
		$slug    = $post_id ? get_post_field( 'post_name', $post_id ) : '';
	}

	if ( is_front_page() ) {
		$slug = 'home';
	}

	return 'page-' . sanitize_html_class( $slug ?: 'default' );
}

/**
 * Creates a unique section ID for the current request.
 *
 * @param string $requested_id Editor-provided ID.
 * @param string $layout Layout name.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_unique_section_id( $requested_id, $layout ) {
	static $used_ids = array();

	$base = sanitize_title( (string) $requested_id );

	if ( '' === $base ) {
		$base = sanitize_title( (string) $layout ) ?: 'section';
	}

	$count             = ( $used_ids[ $base ] ?? 0 ) + 1;
	$used_ids[ $base ] = $count;

	return 1 === $count ? $base : $base . '-' . $count;
}

/**
 * Builds section classes including background and page modifiers.
 *
 * @param array  $section Section data.
 * @param string $layout Layout name.
 * @param int    $post_id Current post ID.
 * @return array
 */
function ukladka_trotuarnoy_plitki_get_section_classes( $section, $layout, $post_id = 0 ) {
	$block      = str_replace( '_', '-', sanitize_html_class( $layout ) );
	$background = sanitize_html_class( (string) ( $section['section_background'] ?? '' ) );
	$classes    = array( $block, ukladka_trotuarnoy_plitki_get_page_class( $post_id ) );

	if ( $background ) {
		$classes[] = $block . '--background-' . $background;
	}

	foreach ( preg_split( '/\s+/', (string) ( $section['section_class'] ?? '' ) ) as $custom_class ) {
		$custom_class = sanitize_html_class( $custom_class );
		if ( $custom_class ) {
			$classes[] = $custom_class;
		}
	}

	return array_values( array_unique( $classes ) );
}

/**
 * Builds section spacing custom properties.
 *
 * @param array $section Section data.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_section_style( $section ) {
	$properties = array(
		'padding_top'    => '--section-padding-top',
		'padding_bottom' => '--section-padding-bottom',
		'margin_top'     => '--section-margin-top',
		'margin_bottom'  => '--section-margin-bottom',
	);
	$styles     = array();

	foreach ( $properties as $field => $property ) {
		if ( isset( $section[ $field ] ) && is_numeric( $section[ $field ] ) ) {
			$styles[] = $property . ': ' . (float) $section[ $field ] . 'px';
		}
	}

	return implode( '; ', $styles );
}

/**
 * Replaces local section data with a referenced section when requested.
 *
 * @param array  $section Current row data.
 * @param string $field_name Flexible Content field name.
 * @return array
 */
function ukladka_trotuarnoy_plitki_resolve_external_section( $section, $field_name ) {
	if ( empty( $section['use_external_content'] ) || ! function_exists( 'get_field' ) ) {
		return $section;
	}

	$source_page       = $section['source_page'] ?? 0;
	$source_post_id    = is_object( $source_page ) ? absint( $source_page->ID ?? 0 ) : absint( $source_page );
	$source_section_id = sanitize_title( (string) ( $section['source_section_id'] ?? '' ) );
	$source_rows_sets  = array();

	if ( $source_post_id ) {
		$source_rows_sets[] = get_field( $field_name, $source_post_id );
	}

	$front_page_id = absint( get_option( 'page_on_front' ) );

	if ( $front_page_id && $source_post_id === $front_page_id && 'home_sections' !== $field_name ) {
		$source_rows_sets[] = get_field( 'home_sections', $source_post_id );
	}

	foreach ( $source_rows_sets as $source_rows ) {
		if ( ! is_array( $source_rows ) ) {
			continue;
		}

		foreach ( $source_rows as $source_row ) {
			if ( $source_section_id === sanitize_title( (string) ( $source_row['section_id'] ?? '' ) ) ) {
				return array_replace( $section, $source_row );
			}
		}
	}

	return $section;
}

/**
 * Checks whether a section contains renderable editor content.
 *
 * @param array $section Flexible Content row.
 * @return bool
 */
function ukladka_trotuarnoy_plitki_section_has_content( $section ) {
	$control_fields = array(
		'acf_fc_layout',
		'admin_title',
		'hide_section',
		'section_id',
		'section_class',
		'section_background',
		'padding_top',
		'padding_bottom',
		'margin_top',
		'margin_bottom',
		'use_external_content',
		'source_page',
		'source_section_id',
	);

	$has_value = static function ( $value, $field_name = '' ) use ( &$has_value ) {
		$nested_control_fields = array(
			'item_type',
			'card_type',
			'source_type',
			'display_type',
			'open_by_default',
			'is_active',
			'enable_slider',
			'slider_on_mobile',
			'show_button',
			'columns',
			'items_per_page',
		);

		if ( in_array( (string) $field_name, $nested_control_fields, true ) ) {
			return false;
		}

		if ( is_array( $value ) ) {
			foreach ( $value as $nested_name => $nested_value ) {
				if ( $has_value( $nested_value, $nested_name ) ) {
					return true;
				}
			}

			return false;
		}

		if ( is_object( $value ) ) {
			return true;
		}

		return null !== $value && false !== $value && '' !== trim( (string) $value ) && '0' !== (string) $value;
	};

	foreach ( $section as $field_name => $value ) {
		if ( ! in_array( $field_name, $control_fields, true ) && $has_value( $value, $field_name ) ) {
			return true;
		}
	}

	return false;
}

/**
 * Returns a layout template path according to its ownership.
 *
 * @param string $layout Layout name.
 * @param string $context pages, posts or portfolio.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_layout_template( $layout, $context ) {
	$slug       = str_replace( '_', '-', sanitize_file_name( $layout ) );
	$candidates = array(
		'template-parts/flexible/global/' . $slug . '.php',
		'template-parts/flexible/' . sanitize_key( $context ) . '/' . $slug . '.php',
	);

	foreach ( $candidates as $candidate ) {
		if ( is_file( get_template_directory() . '/' . $candidate ) ) {
			return $candidate;
		}
	}

	return '';
}
