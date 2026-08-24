<?php
/**
 * Client review helpers.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Returns a human-readable review source.
 *
 * @param string $source Source key.
 * @param string $custom_source Custom source.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_review_source_label( $source, $custom_source = '' ) {
	$labels = array(
		'yandex' => 'Яндекс Карты',
		'2gis'   => '2ГИС',
		'google' => 'Google',
		'other'  => $custom_source,
	);

	return trim( (string) ( $labels[ $source ] ?? $custom_source ) );
}

/**
 * Returns the first visible review card row.
 *
 * @param int $review_id Review post ID.
 * @return array
 */
function ukladka_trotuarnoy_plitki_get_review_card_data( $review_id ) {
	if ( ! function_exists( 'get_field' ) ) {
		return array();
	}

	$rows = get_field( 'review_sections', absint( $review_id ) );

	if ( ! is_array( $rows ) ) {
		return array();
	}

	foreach ( $rows as $row ) {
		if ( 'review_card' === ( $row['acf_fc_layout'] ?? '' ) && empty( $row['hide_section'] ) ) {
			return $row;
		}
	}

	return array();
}

/**
 * Forms review card HTML for lists and AJAX responses.
 *
 * @param int $review_id Review post ID.
 * @return string
 */
function ukladka_trotuarnoy_plitki_get_review_card_html( $review_id ) {
	$review_id = absint( $review_id );
	$section   = ukladka_trotuarnoy_plitki_get_review_card_data( $review_id );

	if ( ! $review_id || ! $section ) {
		return '';
	}

	ob_start();
	get_template_part(
		'template-parts/flexible/posts/review-card',
		null,
		array(
			'section'     => $section,
			'section_id'  => 'review-' . $review_id,
			'classes'     => array( 'review-card' ),
			'style'       => '',
			'post_id'     => $review_id,
			'is_fragment' => true,
		)
	);

	return (string) ob_get_clean();
}
