<?php
/**
 * Front page template.
 *
 * @package ukladka-trotuarnoy-plitki
 */

get_header();
get_template_part(
	'template-parts/page-flexible',
	null,
	array(
		'field_name' => 'home_sections',
		'context'    => 'pages',
	)
);
get_footer();
