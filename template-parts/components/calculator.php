<?php
/**
 * Routes a selected calculator entity to its own template.
 *
 * @package ukladka-trotuarnoy-plitki
 */

$calculator_id = absint( $args['calculator_id'] ?? 0 );

if ( $calculator_id ) {
	get_template_part(
		'template-parts/calculators/universal',
		null,
		array(
			'calculator_id' => $calculator_id,
		)
	);
}
