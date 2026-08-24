<?php
/**
 * Reusable site form helpers.
 *
 * @package ukladka-trotuarnoy-plitki
 */

/**
 * Validates that form_id is unique.
 *
 * @param bool|string $valid Current validation state.
 * @param mixed       $value Submitted value.
 * @return bool|string
 */
function ukladka_trotuarnoy_plitki_validate_unique_form_id( $valid, $value ) {
	if ( true !== $valid ) {
		return $valid;
	}

	$form_id = sanitize_key( (string) $value );

	if ( '' === $form_id ) {
		return 'Укажите form_id.';
	}

	$current_post_id = isset( $_POST['post_ID'] ) ? absint( wp_unslash( $_POST['post_ID'] ) ) : 0;
	$existing_forms  = get_posts(
		array(
			'post_type'      => 'site_form',
			'post_status'    => array( 'publish', 'draft', 'pending', 'private', 'future' ),
			'posts_per_page' => 1,
			'post__not_in'   => $current_post_id ? array( $current_post_id ) : array(),
			'meta_key'       => 'form_id',
			'meta_value'     => $form_id,
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	return empty( $existing_forms ) ? $valid : 'Такая форма с form_id уже существует. Укажите уникальный ID.';
}
add_filter( 'acf/validate_value/name=form_id', 'ukladka_trotuarnoy_plitki_validate_unique_form_id', 10, 2 );

/**
 * Normalizes form_id before saving.
 *
 * @param mixed $value Submitted value.
 * @return string
 */
function ukladka_trotuarnoy_plitki_sanitize_form_id( $value ) {
	return sanitize_key( (string) $value );
}
add_filter( 'acf/update_value/name=form_id', 'ukladka_trotuarnoy_plitki_sanitize_form_id' );

/**
 * Finds a published form by its form_id.
 *
 * @param string $form_id Form identifier.
 * @return int
 */
function ukladka_trotuarnoy_plitki_get_form_post_id( $form_id ) {
	$form_ids = get_posts(
		array(
			'post_type'      => 'site_form',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'meta_key'       => 'form_id',
			'meta_value'     => sanitize_key( (string) $form_id ),
			'fields'         => 'ids',
			'no_found_rows'  => true,
		)
	);

	return ! empty( $form_ids ) ? (int) $form_ids[0] : 0;
}
