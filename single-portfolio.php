<?php
/**
 * Single portfolio template.
 *
 * @package ukladka-trotuarnoy-plitki
 */

get_header();
get_template_part(
	'template-parts/page-flexible',
	null,
	array(
		'field_name' => 'page_sections',
		'context'    => 'portfolio',
	)
);
get_footer();
