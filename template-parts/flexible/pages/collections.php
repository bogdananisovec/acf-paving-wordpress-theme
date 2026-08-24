<?php
/**
 * Cost-page paving collections.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$mapped  = array_merge(
	$section,
	array(
		'collections_source'   => $section['source'] ?? 'latest',
		'collections_count'    => $section['count'] ?? 8,
		'selected_collections' => $section['selected'] ?? array(),
		'initial_visible_count'=> min( 8, max( 1, absint( $section['count'] ?? 8 ) ) ),
		'show_load_more'       => ! empty( $section['show_load_more'] ),
		'load_more_text'       => $section['load_more_text'] ?? __( 'Загрузить ещё', 'ukladka-trotuarnoy-plitki' ),
		'load_more_count'      => max( 1, absint( $section['load_more_count'] ?? 4 ) ),
		'query_all_collections'=> ! empty( $section['show_load_more'] ),
		'show_filters'         => true,
		'cta'                  => array_merge(
			(array) ( $section['try_before_buy'] ?? array() ),
			array(
				'background_image' => $section['try_before_buy']['background'] ?? 0,
			)
		),
	)
);

$args['section'] = $mapped;
$args['layout']  = 'products';
$args['classes'] = array_values( array_unique( array_merge( (array) ( $args['classes'] ?? array() ), array( 'products' ) ) ) );

load_template( get_template_directory() . '/template-parts/flexible/pages/products.php', false, $args );
