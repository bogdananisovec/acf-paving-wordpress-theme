<?php
/**
 * Theme constants and configuration helpers.
 *
 * @package ukladka-trotuarnoy-plitki
 */

if ( ! defined( 'UKLADKA_THEME_VERSION' ) ) {
	define( 'UKLADKA_THEME_VERSION', '1.0.0' );
}

/**
 * Returns an asset version based on its modification time.
 *
 * @param string $relative_path Path relative to the theme root.
 * @return string
 */
function ukladka_trotuarnoy_plitki_asset_version( $relative_path ) {
	$file = get_template_directory() . '/' . ltrim( (string) $relative_path, '/' );

	return is_file( $file ) ? (string) filemtime( $file ) : UKLADKA_THEME_VERSION;
}

/**
 * Returns Flexible Content rows in the front-page design order.
 *
 * Rows missing from the configuration retain their relative order at the end.
 *
 * @param array[] $rows Flexible Content rows.
 * @return array[]
 */
function ukladka_trotuarnoy_plitki_order_home_sections( $rows ) {
	$layout_order = array(
		'hero_quiz',
		'hero_quiz_modal',
		'hero',
		'completed_works',
		'before_after',
		'products',
		'prices',
		'calculator',
		'scope',
		'patterns',
		'areas',
		'mobile_showroom',
		'foundation',
		'why_durable',
		'steps',
		'guarantee',
		'reviews',
		'messenger',
		'team',
		'materials',
		'surface_compare',
		'when_order',
		'duration',
		'promotions',
		'faq',
		'seo',
	);
	$positions    = array_flip( $layout_order );

	$calculator_index = 0;

	foreach ( $rows as $index => &$row ) {
		$layout                 = sanitize_key( (string) ( $row['acf_fc_layout'] ?? '' ) );
		$row['_original_index'] = $index;
		$row['_home_order']     = $positions[ $layout ] ?? PHP_INT_MAX;

		if ( 'calculator' === $layout && $calculator_index++ > 0 ) {
			$row['_home_order'] = $positions['promotions'] + 0.5;
		}
	}
	unset( $row );

	usort(
		$rows,
		static function ( $first, $second ) {
			$first_order  = $first['_home_order'] ?? PHP_INT_MAX;
			$second_order = $second['_home_order'] ?? PHP_INT_MAX;

			if ( $first_order === $second_order ) {
				return (int) $first['_original_index'] <=> (int) $second['_original_index'];
			}

			return $first_order <=> $second_order;
		}
	);

	foreach ( $rows as &$row ) {
		unset( $row['_original_index'], $row['_home_order'] );
	}
	unset( $row );

	return $rows;
}
