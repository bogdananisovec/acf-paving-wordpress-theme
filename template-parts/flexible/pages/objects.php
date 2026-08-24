<?php
/**
 * Cost-page portfolio objects.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$section = is_array( $args['section'] ?? null ) ? $args['section'] : array();
$mapped  = array_merge(
	$section,
	array(
		'portfolio_source' => $section['source'] ?? 'latest',
		'portfolio_count'  => $section['count'] ?? 8,
		'portfolio_items'  => $section['selected'] ?? array(),
	)
);

$args['section'] = $mapped;
$args['layout']  = 'portfolio_grid';

load_template( get_template_directory() . '/template-parts/flexible/portfolio/portfolio-grid.php', false, $args );
