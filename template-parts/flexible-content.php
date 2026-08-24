<?php
/**
 * Unified ACF Flexible Content renderer.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$field_name = sanitize_key( (string) ( $args['field_name'] ?? 'page_sections' ) );
$source_id  = $args['post_id'] ?? get_the_ID();
$post_id    = is_numeric( $source_id ) ? absint( $source_id ) : sanitize_key( (string) $source_id );
$context    = sanitize_key( (string) ( $args['context'] ?? 'pages' ) );
$rows       = isset( $args['rows'] ) && is_array( $args['rows'] )
	? $args['rows']
	: ( function_exists( 'get_field' ) ? get_field( $field_name, $post_id ) : array() );

if ( ! is_array( $rows ) ) {
	return;
}

if ( 'home_sections' === $field_name && is_front_page() ) {
	$rows = ukladka_trotuarnoy_plitki_order_home_sections( $rows );
}

$rendered_index = 0;

foreach ( $rows as $section_index => $section ) {
	if ( ! is_array( $section ) || empty( $section['acf_fc_layout'] ) || ! empty( $section['hide_section'] ) ) {
		continue;
	}

	$section   = ukladka_trotuarnoy_plitki_resolve_external_section( $section, $field_name );

	if ( ! ukladka_trotuarnoy_plitki_section_has_content( $section ) ) {
		continue;
	}

	$layout    = sanitize_key( (string) $section['acf_fc_layout'] );
	$template  = ukladka_trotuarnoy_plitki_get_layout_template( $layout, $context );
	$section_id = ukladka_trotuarnoy_plitki_get_unique_section_id( $section['section_id'] ?? '', $layout );

	if ( ! $template ) {
		continue;
	}

	$classes   = ukladka_trotuarnoy_plitki_get_section_classes( $section, $layout, $post_id );
	$classes[] = 'section-index-' . ( ++$rendered_index );

	ob_start();
	load_template(
		get_template_directory() . '/' . $template,
		false,
		array(
			'section'    => $section,
			'section_index' => absint( $section_index ),
			'field_name' => $field_name,
			'layout'     => $layout,
			'section_id' => $section_id,
			'classes'    => $classes,
			'style'      => ukladka_trotuarnoy_plitki_get_section_style( $section ),
			'post_id'    => $post_id,
			'context'    => $context,
		)
	);
	$section_markup = (string) ob_get_clean();

	if ( '' !== trim( wp_strip_all_tags( $section_markup ) ) || preg_match( '/<(?:img|picture|video|iframe)\b/i', $section_markup ) ) {
		echo $section_markup; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}

	if ( 'promotions' === $layout && is_page( 'stoimost-ukladki-trotuarnoj-plitki' ) ) {
		get_template_part( 'template-parts/flexible/pages/payment-steps' );
	}
}
